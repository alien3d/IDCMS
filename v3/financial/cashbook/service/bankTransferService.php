<?php

namespace Core\Financial\Cashbook\BankTransfer\Service;

use Core\ConfigClass;
use Core\Financial\Ledger\Service\LedgerService;
$x = addslashes(realpath(__FILE__));
// auto detect if \ consider come from windows else / from Linux
$pos = strpos($x, "\\");
if ($pos !== false) {
    $d = explode("\\", $x);
} else {
    $d = explode("/", $x);
}
$newPath = null;
for ($i = 0; $i < count($d); $i++) {
    // if find the library or package then stop
    if ($d[$i] == 'library' || $d[$i] == 'v2' || $d[$i] == 'v3') {
        break;
    }
    $newPath[] .= $d[$i] . "/";
}
$fakeDocumentRoot = null;
for ($z = 0; $z < count($newPath); $z++) {
    $fakeDocumentRoot .= $newPath[$z];
}
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot); // start
require_once($newFakeDocumentRoot . "library/class/classAbstract.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
require_once($newFakeDocumentRoot . "v3/financial/shared/service/sharedService.php");
/**
 * Class BankTransferService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\Cashbook\BankTransfer\Service
 * @subpackage Cashbook
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class BankTransferService extends ConfigClass {

    /**
     * Asset->Balance Sheet Item
     */
    const ASSET = 'A';

    /**
     * Asset->Balance Sheet Item (SAGA Only)
     */
    const SAGA_ASSET = 'A00000';

    /**
     * Liability->Balance Sheet Item
     */
    const LIABILITY = 'I';

    /**
     * Liability->Balance Sheet Item(SAGA Only)
     */
    const SAGA_LIABILITY = 'L00000';

    /**
     * Equity->Balance Sheet Item
     */
    const EQUITY = 'OE';

    /**
     * Equity->Balance Sheet Item(SAGA only)
     */
    const SAGA_EQUITY = 'E00000';

    /**
     * Income->Profit And Loss
     */
    const INCOME = 'I';

    /**
     * Income->Profit And Loss
     */
    const SAGA_INCOME = 'B00000';

    /**
     * Expenses->Profit And Loss
     */
    const EXPENSES = 'E';

    /**
     * Expenses->Profit And Loss(SAGA only)
     */
    const SAGA_EXPENSES = 'H00000';

    /**
     * Connection to the database
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;

    /**
     * Translate Label
     * @var string
     */
    public $t;
    /**
     * Financial Shared Service
     * @var \Core\Financial\Ledger\Service\LedgerService
     */
    public $ledgerService;
    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
        if ($_SESSION['companyId']) {
            $this->setCompanyId($_SESSION['companyId']);
        } else {
            // fall back to default database if anything wrong
            $this->setCompanyId(1);
        }
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        if ($_SESSION['companyId']) {
            $this->setCompanyId($_SESSION['companyId']);
        } else {
            // fall back to default database if anything wrong
            $this->setCompanyId(1);
        }
        if ($_SESSION['staffId']) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            // fall back to default database if anything wrong
            $this->setStaffId(1);
        }
        $this->ledgerService = new LedgerService();
				$this->ledgerService->q = $this->q;
		$this->ledgerService->t = $this->t;
		$this->ledgerService->execute();
    }

    /**
     * Post BankTransfer To General Ledger
     * @param int $bankTransferId Bank Transfer Primary Key
     * @param int $leafId Leaf Primary Key
     * @param string $leafName Leaf Name
     * @throws \Exception
     */
    public function setPosting($bankTransferId, $leafId, $leafName) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            echo $sql = "
            SELECT  *
            FROM    `banktransfer`
            WHERE   `bankTransferId` IN (" . $bankTransferId . ")
            AND     `isPost`    =   0
            AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  *
            FROM    [bankTransfer]
            WHERE   [bankTransferId] IN (" . $bankTransferId . ")
            AND     [isPost] =0
            AND     [companyId] =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  *
            FROM    BANKTRANSFER
            WHERE   BANKTRANSFERID IN (" . $bankTransferId . ")
            AND     ISPOST      =   1
            AND     COMPANYID   =   '" . $this->getCompanyId() . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            while (($row = $this->q->fetchArray($result)) == true) {
                $businessPartnerId = $row['businessPartnerId'];
                $documentNumber = $row['documentNumber'];
                $cashBookDate = $row['bankTransferDate'];
                $cashBookAmount = $row['bankTransferAmount'];
                $cashBookDescription = $row['bankTransferDescription'];
                $bankTransferId = $row['bankTransferId'];
                $this->setCashBookLedger(
                        $businessPartnerId, $documentNumber, $cashBookDate, $cashBookAmount, $cashBookDescription, $leafId, $bankTransferId, $cashBookLedgerId = null
                );
            }
        }
        $journalNumber = $this->getDocumentNumber('GLPT');
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  *
            FROM    `banktransferdetail`
            WHERE   `bankTransferId` IN (" . $bankTransferId . ")
            ORDER BY `bankTransferId";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  *
            FROM    [bankTransferDetail]
            WHERE   [bankTransferId] IN (" . $bankTransferId . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  *
            FROM    BANKTRANSFERDETAIL
            WHERE   BANKTRANSFERID IN (" . $bankTransferId . ")";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            while (($row = $this->q->fetchArray($result)) == true) {
                $businessPartnerId = $row['businessPartnerId'];
                $chartOfAccountId = $row['chartOfAccountId'];
                $documentNumber = $row['documentNumber'];
                $documentDate = $row['bankTransferDate'];
                $localAmount = $row['bankTransferDetailAmount'];
                $description = $row['bankTransferDescription'];
                $module = 'CB';
                $tableName = 'bankTransfer';
                $tableNameDetail = 'bankTransferDetail';
                $tableNameId = 'bankTransferId';
                $tableNameDetailId = 'bankTransferDetailId';

                $bankTransferId = $row['bankTransferId'];
                $referenceTableNameId = $row['bankTransferId'];
                $referenceTableNameDetailId = $row['bankTransferDetailId'];
				$this->ledgerService->setGeneralLedger($leafId, $leafName, $businessPartnerId, $chartOfAccountId, $documentNumber, $journalNumber, $documentDate, $localAmount, $description, $module, $tableName, $tableNameDetail, $tableNameId, $tableNameDetailId,$referenceTableNameId,$referenceTableNameDetailId);
            }
        }
        $this->setBankTransferPosted($bankTransferId);
    }

    /**
     * SELECT / UPDATE / INSERT Procedure For CashBook Ledger
     * @param int $businessPartnerId Business Partner Primary Key
     * @param string $documentNumber Document Number
     * @param string $cashBookDate Date
     * @param double $cashBookAmount Amount
     * @param string $cashBookDescription Description
     * @param int $leafId Leaf Primary Key
     * @param int $bankTransferId Bank Transfer Primary Key
     * @return null
     * @throws \Exception
     */
    public function setCashBookLedger(
    $businessPartnerId, $documentNumber, $cashBookDate, $cashBookAmount, $cashBookDescription, $leafId, $bankTransferId
    ) {
        $sql = null;
        $total = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `bankTransferId`
            FROM    `cashbookledger`
            WHERE   `companyId`     =   '" . $this->getCompanyId() . "'
            AND     `bankTransferId`  =   '" . $bankTransferId . "'
            ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  [bankTransferId]
            FROM    [cashbookLedger]
            WHERE   [companyId]     =   '" . $this->getCompanyId() . "'
            AND     [bankTransferId]  =   '" . $bankTransferId . "'
            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  BANKTRANSFERID
            FROM    CASHBOOKLEDGER
            WHERE   COMPANYID       =   '" . $this->getCompanyId() . "'
            AND     BANKTRANSFERID    =   '" . $bankTransferId . "'
            ";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $total = $this->q->numberRows($result);
        }
        if (intval($total) > 0) {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
                UPDATE   `cashbookledger`
                SET      `businessPartnerId`    =   '" . $businessPartnerId . "',
                         `cashBookDate`         =   '" . $cashBookDate . "',
                         `cashBookAmount`       =   '" . $cashBookAmount . "',
                         `cashBookDescription`  =   '" . $cashBookDescription . "',
                         `executeBy`            =   '" . $this->getStaffId() . "',
                         `executeTime`          =   " . $this->getExecuteTime() . "
                WHERE    `bankTransferId`         =   '" . $bankTransferId . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                  UPDATE [cashbookLedger]
                  SET    [businessPartnerId]    =   '" . $businessPartnerId . "',
                         [cashBookDate]         =   '" . $cashBookDate . "',
                         [cashBookAmount]       =   '" . $cashBookAmount . "',
                         [cashBookDescription]  =   '" . $cashBookDescription . "',
                         [executeBy]            =   '" . $this->getStaffId() . "',
                         [executeTime]          =   " . $this->getExecuteTime() . "
                  WHERE  [bankTransferId]         =   '" . $bankTransferId . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE  CASHBOOKLEDGER
                SET     BUSINESSPARTNERID      =   '" . $businessPartnerId . "',
                        CASHBOOKDATE           =   '" . $cashBookDate . "',
                        CASHBOOKAMOUNT         =   '" . $cashBookAmount . "',
                        CASHBOOKDESCRIPTION    =   '" . $cashBookDescription . "',
                        EXECUTEBY              =   '" . $this->getStaffId() . "',
                        EXECUTETIME            =   " . $this->getExecuteTime() . "
                WHERE   BANKTRANSFERID           =   '" . $bankTransferId . "'";
            }
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        } else {

            if ($this->getVendor() == self::MYSQL) {
                $sql = "
            INSERT INTO `cashbookledger`(
            `cashBookLedgerId`,       `companyId`,          `businessPartnerId`,
            `bankTransferId`,           `paymentVoucherId`,   `documentNumber`,
            `cashBookDate`,           `cashBookAmount`,     `cashBookDescription`,
            `leafId`,                 `isDefault`,          `isNew`,
            `isDraft`,                `isUpdate`,           `isDelete`,
            `isActive`,               `isApproved`,         `isReview`,
            `isPost`,                 `executeBy`,          `executeTime`
        ) VALUES (
            null,                     '" . $this->getCompanyId() . "',  '" . $businessPartnerId . "',
            '" . $bankTransferId . "',      0,                            '" . $documentNumber . "',
            '" . $cashBookDate . "',      '" . $cashBookAmount . "',        '" . $cashBookDescription . "',
            '" . $leafId . "',            0,                            1,
            0,                        0,                            0,
            0,                        0,                            0,
            0,                        '" . $this->getStaffId() . "',    " . $this->getExecuteTime() . "
           )
        ";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
            INSERT INTO [cashbookLedger](
            [cashBookLedgerId[,       [companyId],                  [businessPartnerId],
            [bankTransferId],           [paymentVoucherId],           [documentNumber],
            [cashBookDate],           [cashBookAmount],             [cashBookDescription],
            [leafId],                 [isDefault],                  [isNew],
            [isDraft],                [isUpdate],                   [isDelete],
            [isActive],               [isApproved],                 [isReview],
            [isPost],                 [executeBy],                  [executeTime]
       ) VALUES (
            null,                     '" . $this->getCompanyId() . "',  '" . $businessPartnerId . "',
            '" . $bankTransferId . "',      0,                            '" . $documentNumber . "',
            '" . $cashBookDate . "',      '" . $cashBookAmount . "',        '" . $cashBookDescription . "',
            '" . $leafId . "',            0,                            1,
            0,                        0,                            0,
            0,                        0,                            0,
            0,                        '" . $this->getStaffId() . "',    " . $this->getExecuteTime() . "
           )
        ";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
            INSERT INTO CASHBOOKLEDGER(
            CASHBOOKLEDGERID,           COMPANYID,          BUSINESSPARTNERID,
            BANKTRANSFERID,               PAYMENTVOUCHERID,   DOCUMENTNUMBER,
            CASHBOOKDATE,               CASHBOOKAMOUNT,     CASHBOOKDESCRIPTION,
            LEAFID,                     ISDEFAULT,          ISNEW,
            ISDRAFT,                    ISUPDATE,           ISDELETE,
            ISACTIVE,                   ISAPPROVED,         ISREVIEW,
            ISPOST,                     EXECUTEBY,          EXECUTETIME
     ) VALUES (
            null,                     '" . $this->getCompanyId() . "',  '" . $businessPartnerId . "',
            '" . $bankTransferId . "',      0,                            '" . $documentNumber . "',
            '" . $cashBookDate . "',      '" . $cashBookAmount . "',        '" . $cashBookDescription . "',
            '" . $leafId . "',            0,                            1,
            0,                        0,                            0,
            0,                        0,                            0,
            0,                        '" . $this->getStaffId() . "',    " . $this->getExecuteTime() . "
           )
        ";
            }
            try {
                $this->q->create($sql);
            } catch (\Exception $e) {
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
    }

    /**
     * Set Bank Transfer Status
     * @param int $bankTransferId Bank Transfer Primary Key
     * @param int $bankTransferStatusId Bank Transfer Status Primary Key
     */
    public function setBankTransferStatusTracking($bankTransferId, $bankTransferStatusId) {
        $sql = null;
        $bankTransferTrackingDuration = 0;
        // check if exist previous payment voucher transaction and compare with the current day.
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  DATEDIFF(NOW(),`collectionTrackingDate`) AS `bankTransferTrackingDuration`
            FROM   `bankTransfertracking`
            WHERE  `bankTransferId` ='" . $bankTransferId . "'
			DESC	LIMIT 1
            ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT [executeTime]
            FROM   [bankTransfer]
            WHERE  [bankTransferId] ='" . $bankTransferId . "'
            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT EXECUTETIME
            FROM   BANKTRANSFER
            WHERE  BANKTRANSFERID ='" . $bankTransferId . "'
            ";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $bankTransferTrackingDuration = $row['bankTransferTrackingDuration'];
        }

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `businesspartnertracking`(
                `bankTransferTrackingId`,                   `companyId`,
                `bankTransferId`,                           `bankTransferStatusId`,
                `bankTransferTrackingDuration`,              `isDefault`,
                `isNew`,                                  `isDraft`,
                `isUpdate`,                               `isDelete`,
                `isActive`,                               `isApproved`,
                `isReview`,                               `isPost`,
                `executeBy`,                              `executeTime`
            ) VALUES (
                null,                                   " . $this->getCompanyId() . ",
                '" . $bankTransferId . "',                 " . $bankTransferStatusId . ",
                '" . $bankTransferTrackingDuration . "',           0,
                1,                                       0,
                0,                                       0,
                1,                                       0,
                0,                                       0,
                '" . $this->getStaffId() . "',               " . $this->getExecuteTime() . "
             )
            ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            INSERT INTO [bankTransferTracking](
                [bankTransferTrackingId],               [companyId],
                [bankTransferId],                       [bankTransferStatusId],
                [bankTransferTrackingDuration],                       [isDefault],
                [isNew],                                  [isDraft],
                [isUpdate],                               [isDelete],
                [isActive],                               [isApproved],
                [isReview],                               [isPost],
                [executeBy],                              [executeTime]
            ) VALUES (
                null,                                   " . $this->getCompanyId() . ",
                '" . $bankTransferId . "',                 " . $bankTransferStatusId . ",
                '" . $bankTransferTrackingDuration . "',           0,
                1,                                       0,
                0,                                       0,
                1,                                       0,
                0,                                       0,
                '" . $this->getStaffId() . "',               " . $this->getExecuteTime() . ")
            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            INSERT INTO BANKTRANSFERTRACKING (
                BANKTRANSFERTRACKINGID,                   COMPANYID,
                BANKTRANSFERID,                           BANKTRANSFERTATUSID,
                BANKTRANSFERTRACKINGDURATION,                     ISDEFAULT,
                ISNEW,                                  ISDRAFT,
                ISUPDATE,                               ISDELETE,
                ISACTIVE,                               ISAPPROVED,
                ISREVIEW,                               ISPOST,
                EXECUTEBY,                              EXECUTETIME
            ) VALUES (
                null,                                   " . $this->getCompanyId() . ",
                '" . $bankTransferId . "',                 " . $bankTransferStatusId . ",
                '" . $bankTransferTrackingDuration . "',           0,
                1,                                       0,
                0,                                       0,
                1,                                       0,
                0,                                       0,
                '" . $this->getStaffId() . "',               " . $this->getExecuteTime() . "
             )
            ";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Update Bank Transfer Posted Flag
     * @param int $bankTransferId Bank Transfer Primary Key
     */
    private function setBankTransferPosted($bankTransferId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE  `banktransfer`
            SET     `isPost`        =  1,
                    `executeBy`     =   '" . $this->getStaffId() . "',
                    `executeTime`   =   " . $this->getExecuteTime() . "
            WHERE   `bankTransferId` IN (" . $bankTransferId . ")";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE  [bankTransfer]
            SET     [isPost]        =  1,
                    [executeBy]     =   '" . $this->getStaffId() . "',
                    [executeTime]   =   " . $this->getExecuteTime() . "
            WHERE   [bankTransferId] IN (" . $bankTransferId . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE  BANKTRANSFER
            SET     ISPOST        =  1,
                    EXECUTEBY     =   '" . $this->getStaffId() . "',
                    EXECUTETIME   =   " . $this->getExecuteTime() . "
            WHERE   BANKTRANSFERID IN (" . $bankTransferId . ")";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Create
     * @see config::create()
     * @return void
     */
    public function create() {
        
    }

    /**
     * Read
     * @see config::read()
     * @return void
     */
    public function read() {
        
    }

    /**
     * Update
     * @see config::update()
     * @return void
     */
    public function update() {
        
    }

    /**
     * Update
     * @see config::delete()
     * @return void
     */
    public function delete() {
        
    }

    /**
     * Reporting
     * @see config::excel()
     * @return void
     */
    public function excel() {
        
    }

}

?>