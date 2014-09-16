<?php

namespace Core\Financial\Ledger\Service;

use Core\ConfigClass;

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

/**
 * Check In The System required valid date for posting or not.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Financial
 * @subpackage Valid Date
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class LedgerService extends ConfigClass {

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
     * Financial Year Id
     * @var int
     */
    private $financeYearId;

    /**
     * Financial Year
     * @var int
     */
    private $financeYearYear;

    /**
     * Financial Period Id
     * @var int
     */
    private $financePeriodRangeId;

    /**
     * Financial Period
     * @var int
     */
    private $financePeriodRangePeriod;

    /**
     * Chart Of Account Category Id
     * @var int
     */
    private $chartOfAccountCategoryId;

    /**
     * Chart Of Account Type Id
     * @var int
     */
    private $chartOfAccountTypeId;

    /**
     * Chart Of Account Id
     * @var int
     */
    private $chartOfAccountId;

    /**
     * Chart Of Account Category Description
     * @var int
     */
    private $chartOfAccountCategoryCode;

    /**
     * Chart Of Account Category Description
     * @var int
     */
    private $chartOfAccountCategoryDescription;

    /**
     * Chart Of Account Type Code
     * @var int
     */
    private $chartOfAccountTypeCode;

    /**
     * Chart Of Account Type Description
     * @var int
     */
    private $chartOfAccountTypeDescription;

    /**
     * Chart Of Account Category Description
     * @var int
     */
    private $chartOfAccountDescription;

    /**
     * Chart Of Account Category Description
     * @var int
     */
    private $chartOfAccountNumber;

    /**
     * Company Name
     * @var int
     */
    private $businessPartnerCompany;

    /**
     * Country
     * @var int
     */
    private $countryId;

    /**
     * Country Currency Code. E.g Malaysia-> MYR
     * @var string
     */
    private $countryCurrencyCode;

    /**
     * Transaction Type
     * @var int
     */
    private $transactionTypeId;

    /**
     * Transaction Type Code. E.g D->Debit,C->Debit
     * @var string
     */
    private $transactionTypeCode;

    /**
     * Transaction Type Description E.g Debit,Debit,Debit Note ,Debit Note
     * @var string
     */
    private $transactionTypeDescription;

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->getOverrideCountry();
    }

    /**
     * Get Default Company Country
     */
    public function getOverrideCountry() {
        $sql = null;
        if ($this->getVendor() === self::MYSQL) {
            $sql = "
            SELECT `countryId`,
                   `countryCurrencyLocale`,
                   `isPosting`,
                   `financeYearId`
            FROM   `financesetting`
            WHERE  `companyId` ='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() === self::MSSQL) {
            $sql = "
            SELECT [financeSetting].[countryId],
                   [financeSetting].[countryCurrencyLocale],
                   [financeSetting].[isPosting],
                   [financeSetting].[financeYearId]
            FROM   [financeSetting]
            JOIN   [country]
            ON     [financeSetting].[companyId] = [company].[companyId]
            AND    [financeSetting].[companyId] = [country].[countryId]
            WHERE  [financeSetting].[companyId] ='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() === self::ORACLE) {
            $sql = "
            SELECT COUNTRYID AS \"countryId\",
                   COUNTRYCURRENCYLOCALE AS \"countryCurrencyLocale,
                   ISPOSTING AS \"isPosting\",
                   FINANCEYEARID AS \"financeYearId\"
            FROM   FINANCESETTING
            WHERE  COMPANYID ='" . $this->getCompanyId() . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $this->setCountryId($row['countryId']);
            $this->setCountryCurrencyLocale($row['countryCurrencyLocale']);
            $this->setFinanceYearId($row['financeYearId']);
        }
    }

    /**
     * Return Country Currency Code
     * @return string
     */
    public function getCountryCurrencyCode() {
        return $this->countryCurrencyCode;
    }

    /**
     * Set Country Currency Code
     * @param string $countryCurrencyCode Country Currency Code
     * @return $this
     */
    public function setCountryCurrencyCode($countryCurrencyCode) {
        $this->countryCurrencyCode = $countryCurrencyCode;
        return $this;
    }

    /**
     * Return Financial Year
     * @return int
     */
    public function getFinanceYearId() {
        return $this->financeYearId;
    }

    /**
     * Set Financial Year
     * @param int $financeYearId Financial Year Primary Key
     * @return $this|ConfigClass
     */
    public function setFinanceYearId($financeYearId) {
        $this->financeYearId = $financeYearId;
        return $this;
    }

    /**
     * Return Country Primary Key
     * @return int
     */
    public function getCountryId() {
        return $this->countryId;
    }

    /**
     * SELECT / UPDATE / INSERT Procedure For Purchase Invoice Ledger
     * @param int $businessPartnerId Business Partner Primary Key
     * @param int $chartOfAccountId Chart Of Account Primary Key
     * @param string $documentNumber Document Number
     * @param string $purchaseInvoiceDate Date
     * @param string $purchaseInvoiceDueDate Due Date
     * @param double $purchaseInvoiceAmount Amount
     * @param string $purchaseInvoiceDescription Description
     * @param int $leafId Leaf Primary Key
     * @param null|int $purchaseInvoiceId Purchase Invoice Primary Key
     * @param null|int $purchaseInvoiceProjectId Purchase Invoice Project Primary Key
     * @param null|int $purchaseInvoiceAdjustmentId Payment Voucher Adjustment Primary Key
     * @param null|int $purchaseInvoiceDebitNoteId Payment Voucher Adjustment Primary Key
     * @param null|int $purchaseInvoiceCreditNoteId Payment Voucher Adjustment Primary Key
     * @param null|int $paymentVoucherId Payment Voucher Adjustment Primary Key
     * @return void
     */
    public function setPurchaseInvoiceLedger(
    $businessPartnerId, $chartOfAccountId, $documentNumber, $purchaseInvoiceDate, $purchaseInvoiceDueDate, $purchaseInvoiceAmount, $purchaseInvoiceDescription, $leafId, $purchaseInvoiceId, $purchaseInvoiceProjectId = null, $purchaseInvoiceAdjustmentId = null, $purchaseInvoiceDebitNoteId = null, $purchaseInvoiceCreditNoteId = null, $paymentVoucherId = null
    ) {
        $sql = null;
        $total = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `purchaseInvoiceId`
            FROM    `purchaseinvoiceledger`
            WHERE   `companyId`                     =   '" . $this->getCompanyId() . "'
            AND     `purchaseInvoiceId`             =   '" . $purchaseInvoiceId . "'
            AND     `documentNumber`                =   '" . $documentNumber . "'
            ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  [purchaseInvoiceId]
            FROM    [purchaseInvoiceLedger]
            WHERE   [companyId]                     =   '" . $this->getCompanyId() . "'
            AND     [purchaseInvoiceId]             =   '" . $purchaseInvoiceId . "'
            AND     [documentNumber]                =   '" . $documentNumber . "'
            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  PURCHASEINVOICEID
            FROM    PURCHASEINVOICELEDGER
            WHERE   COMPANYID                       =   '" . $this->getCompanyId() . "'
            AND     PURCHASEINVOICEID               =   '" . $purchaseInvoiceId . "'
            AND     DOCUMENTNUMBER                  =   '" . $documentNumber . "'
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
            $total = $this->q->numberRows($result);
        }
        if (intval($total) > 0) {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
                UPDATE  `purchaseinvoiceledger`
                SET     `businessPartnerId`            =   '" . $businessPartnerId . "',
                        `purchaseInvoiceDate`          =   '" . $purchaseInvoiceDate . "',
                        `purchaseInvoiceDueDate`       =   '" . $purchaseInvoiceDueDate . "',
                        `purchaseInvoiceAmount`        =   '" . $purchaseInvoiceAmount . "',
                        `purchaseInvoiceDescription`   =   '" . $purchaseInvoiceDescription . "',
                        `executeBy`                    =   '" . $this->getStaffId() . "',
                        `executeTime`                  =   " . $this->getExecuteTime() . "
                WHERE   `purchaseInvoiceId`            =   '" . $purchaseInvoiceId . "'
                AND     `documentNumber`               =   '" . $documentNumber . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE  [purchaseInvoiceLedger]
                SET     [businessPartnerId]        =   '" . $businessPartnerId . "',
                        [purchaseInvoiceDate]      =   '" . $purchaseInvoiceDate . "',
                        [purchaseInvoiceDueDate]   =   '" . $purchaseInvoiceDueDate . "',
                        [purchaseInvoiceAmount]           =   '" . $purchaseInvoiceAmount . "',
                        [purchaseInvoiceDescription]      =   '" . $purchaseInvoiceDescription . "',
                        [executeBy]                =   '" . $this->getStaffId() . "',
                        [executeTime]              =    " . $this->getExecuteTime() . "
                WHERE   [purchaseInvoiceId]        =   '" . $purchaseInvoiceId . "'
                AND     [documentNumber]           =   '" . $documentNumber . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
              UPDATE PURCHASEINVOICELEDGER
              SET    BUSINESSPARTNERID              =   '" . $businessPartnerId . "',
                     PURCHASEINVOICEDATE            =   '" . $purchaseInvoiceDate . "',
                     PURCHASEINVOICEMOUNT           =   '" . $purchaseInvoiceAmount . "',
                     PURCHASEINVOICEDESCRIPTION     =   '" . $purchaseInvoiceDescription . "',
                     EXECUTEBY                      =   '" . $this->getStaffId() . "',
                     EXECUTETIME                    =   " . $this->getExecuteTime() . "
              WHERE  PURCHASEINVOICEID              =   '" . $purchaseInvoiceId . "'";
            }
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
                INSERT INTO `purchaseinvoiceledger`(
                    `purchaseInvoiceLedgerId`,                  `companyId`,                            `businessPartnerId`,
                    `purchaseInvoiceProjectId`,                 `purchaseInvoiceId`,                    `purchaseInvoiceAdjustmentId`,
                    `purchaseInvoiceDebitNoteId`,                `purchaseInvoiceCreditNoteId`,          `paymentVoucherId`,
                    `documentNumber`,                           `purchaseInvoiceDate`,                  `purchaseInvoiceDueDate`,
                    `purchaseInvoiceAmount`,                    `purchaseInvoiceDescription`,
                    `purchaseInvoiceDebitNoteId`,               `purchaseInvoiceCreditNoteId`,          `paymentVoucherId`,
                    `leafId`,                                   `isDefault`,                            `isNew`,
                    `isDraft`,                                  `isUpdate`,                             `isDelete`,
                    `isActive`,                                 `isApproved`,                           `isReview`,
                    `isPost`,                                   `executeBy`,                            `executeTime`,
                    `chartOfAccountId`
                ) VALUES (
                    null,                                       '" . $this->getCompanyId(
                    ) . "',        '" . $businessPartnerId . "',
                    '" . $purchaseInvoiceProjectId . "',        '" . $purchaseInvoiceId . "',           '" . $purchaseInvoiceAdjustmentId . "',
                    '" . $documentNumber . "',                  '" . $purchaseInvoiceDate . "',         '" . $purchaseInvoiceDueDate . "',
                    '" . $purchaseInvoiceDebitNoteId . "',       '" . $purchaseInvoiceCreditNoteId . "',  '" . $paymentVoucherId . "',
                    '" . $purchaseInvoiceAmount . "',           '" . $purchaseInvoiceDescription . "',
                    '" . $purchaseInvoiceDebitNoteId . "',      '" . $purchaseInvoiceCreditNoteId . "', '" . $paymentVoucherId . "',
                    '" . $leafId . "',                          0,                                      1,
                    0,                                          0,                                      0,
                    0,                                          0,                                      0,
                    0,                                          '" . $this->getStaffId() . "',          " . $this->getExecuteTime() . ",
                    '" . $chartOfAccountId . "'
                )
                ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                INSERT INTO [purchaseInvoiceLedger](
                    [purchaseInvoiceLedgerId],                         [companyId],                            [businessPartnerId],
                    [purchaseInvoiceProjectId],                 [purchaseInvoiceId],                    [purchaseInvoiceAdjustmentId],
                    [purchaseInvoiceDebitNoteId],               [purchaseInvoiceCreditNoteId],          [paymentVoucherId],
                    [documentNumber],                           [purchaseInvoiceDate],                  [purchaseInvoiceDueDate],
                    [purchaseInvoiceAmount],                    [purchaseInvoiceDescription],
                    [purchaseInvoiceDebitNoteId],               [purchaseInvoiceCreditNoteId],          [paymentVoucherId],
                    [leafId],                                   [isDefault],                            [isNew],
                    [isDraft],                                  [isUpdate],                             [isDelete],
                    [isActive],                                 [isApproved],                           [isReview],
                    [isPost],                                   [executeBy],                            [executeTime],
                    [chartOfAccountId]
                ) VALUES (
                    null,                                       '" . $this->getCompanyId(
                    ) . "',        '" . $businessPartnerId . "',
                    '" . $purchaseInvoiceProjectId . "',        '" . $purchaseInvoiceId . "',           '" . $purchaseInvoiceAdjustmentId . "',
                    '" . $documentNumber . "',                  '" . $purchaseInvoiceDate . "',         '" . $purchaseInvoiceDueDate . "',
                    '" . $purchaseInvoiceDebitNoteId . "',       '" . $purchaseInvoiceCreditNoteId . "',  '" . $paymentVoucherId . "',
                    '" . $purchaseInvoiceAmount . "',           '" . $purchaseInvoiceDescription . "',
                    '" . $purchaseInvoiceDebitNoteId . "',      '" . $purchaseInvoiceCreditNoteId . "', '" . $paymentVoucherId . "',
                    '" . $leafId . "',                          0,                                      1,
                    0,                                          0,                                      0,
                    0,                                          0,                                      0,
                    0,                                          '" . $this->getStaffId() . "',          " . $this->getExecuteTime() . ",
                    '" . $chartOfAccountId . "'
                )
                ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            INSERT INTO PURCHASEINVOICELEDGER(
                    PURCHASEINVOICELEDGERID,                    COMPANYID,                            BUSINESSPARTNERID,
                    PURCHASEINVOICEPROJECTID,                 PURCHASEINVOICEID,                    PURCHASEINVOICEADJUSTMENTID,
                    DOCUMENTNUMBER,                           PURCHASEINVOICEDATE,                  PURCHASEINVOICEDUEDATE,
                    PURCHASEINVOICEAMOUNT,                    PURCHASEINVOICEDESCRIPTION,
                    PURCHASEINVOICEDEBITNOTEID,               PURCHASEINVOICECREDITNOTEID,          PAYMENTVOUCHERID,
                    LEAFID,                                   ISDEFAULT,                            ISNEW,
                    ISDRAFT,                                  ISUPDATE,                             ISDELETE,
                    ISACTIVE,                                 ISAPPROVED,                           ISREVIEW,
                    ISPOST,                                   EXECUTEBY,                            EXECUTETIME,
                    CHARTOFACCOUNTID
     ) VALUES (
                    null,                                       '" . $this->getCompanyId(
                    ) . "',        '" . $businessPartnerId . "',
                    '" . $purchaseInvoiceProjectId . "',        '" . $purchaseInvoiceId . "',           '" . $purchaseInvoiceAdjustmentId . "',
                    '" . $documentNumber . "',                  '" . $purchaseInvoiceDate . "',         '" . $purchaseInvoiceDueDate . "',
                    '" . $purchaseInvoiceDebitNoteId . "',       '" . $purchaseInvoiceCreditNoteId . "',  '" . $paymentVoucherId . "',
                    '" . $purchaseInvoiceAmount . "',           '" . $purchaseInvoiceDescription . "',
                    '" . $purchaseInvoiceDebitNoteId . "',      '" . $purchaseInvoiceCreditNoteId . "', '" . $paymentVoucherId . "',
                    '" . $leafId . "',                          0,                                      1,
                    0,                                          0,                                      0,
                    0,                                          0,                                      0,
                    0,                                          '" . $this->getStaffId() . "',          " . $this->getExecuteTime() . ",
                    '" . $chartOfAccountId . "'
                )
        ";
        }
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Set Country Primary Key
     * @param int $countryId Country Primary Key
     * @return $this
     */
    public function setCountryId($countryId) {
        $this->countryId = $countryId;
        return $this;
    }

    /**
     * SELECT / UPDATE / INSERT Procedure For CashBook Ledger
     * @param int $businessPartnerId Business Partner Primary Key
     * @param int $chartOfAccountId Chart Of Account Primary Key
     * @param string $documentNumber Document Number
     * @param string $cashBookDate Date
     * @param double $cashBookAmount Amount
     * @param string $cashBookDescription Description
     * @param int $leafId Leaf Primary Key
     * @param null|int $collectionId Collection Primary Key
     * @param null|int $paymentVoucherId Payment Voucher Primary Key
     * @param null|int $bankTransferId Bank Transfer Primary Key
     * @param null|int $collectionCancellationId Collection Cancellation Primary Key
     * @param null|int $paymentVoucherCancellationId Payment Voucher Cancellation Primary Key
     * @throws \Exception
     */
    public function setCashBookLedger(
    $businessPartnerId, $chartOfAccountId, $documentNumber, $cashBookDate, $cashBookAmount, $cashBookDescription, $leafId, $collectionId = null, $paymentVoucherId = null, $bankTransferId = null, $collectionCancellationId = null, $paymentVoucherCancellationId = null
    ) {
        $sql = null;
        $total = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `documentNumber`
            FROM    `cashbookledger`
            WHERE   `companyId`     =   '" . $this->getCompanyId() . "'
            AND     `documentNumber`  =   '" . $documentNumber . "'
            ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  [documentNumber]
            FROM    [cashbookLedger]
            WHERE   [companyId]     =   '" . $this->getCompanyId() . "'
            AND     [documentNumber]  =   '" . $documentNumber . "'
            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  DOCUMENTNUMBER AS   \"documentNumber\"
            FROM    CASHBOOKLEDGER
            WHERE   COMPANYID       =   '" . $this->getCompanyId() . "'
            AND     DOCUMENTNUMBER   =   '" . $documentNumber . "'
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
                WHERE    `documentNumber`       =   '" . $documentNumber . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                  UPDATE [cashbookLedger]
                  SET    [businessPartnerId]    =   '" . $businessPartnerId . "',
                         [cashBookDate]         =   '" . $cashBookDate . "',
                         [cashBookAmount]       =   '" . $cashBookAmount . "',
                         [cashBookDescription]  =   '" . $cashBookDescription . "',
                         [executeBy]            =   '" . $this->getStaffId() . "',
                         [executeTime]          =   " . $this->getExecuteTime() . "
                  WHERE  [documentNumber]       =   '" . $documentNumber . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE  CASHBOOKLEDGER
                SET     BUSINESSPARTNERID      =   '" . $businessPartnerId . "',
                        CASHBOOKDATE           =   '" . $cashBookDate . "',
                        CASHBOOKAMOUNT         =   '" . $cashBookAmount . "',
                        CASHBOOKDESCRIPTION    =   '" . $cashBookDescription . "',
                        EXECUTEBY              =   '" . $this->getStaffId() . "',
                        EXECUTETIME            =   " . $this->getExecuteTime() . "
                WHERE   DOCUMENTNUMBER         =   '" . $documentNumber . "'";
            }
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
                header('Content-Type:application/json; charset=utf-8');
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        } else {

            if ($this->getVendor() == self::MYSQL) {
                $sql = "
            INSERT INTO `cashbookledger`(
            `cashBookLedgerId`,       `companyId`,          `businessPartnerId`,
            `collectionId`,           `paymentVoucherId`,   `documentNumber`,
            `cashBookDate`,           `cashBookAmount`,     `cashBookDescription`,
            `leafId`,                 `isDefault`,          `isNew`,
            `isDraft`,                `isUpdate`,           `isDelete`,
            `isActive`,               `isApproved`,         `isReview`,
            `isPost`,                 `executeBy`,          `executeTime`,
            `bankTransferId`,         `collectionCancellationId`,   `paymentVoucherCancellationId`,
            `chartOfAccountId`
        ) VALUES (
            null,                     '" . $this->getCompanyId() . "',  '" . $businessPartnerId . "',
            '" . $collectionId . "',      '" . $paymentVoucherId . "',                            '" . $documentNumber . "',
            '" . $cashBookDate . "',      '" . $cashBookAmount . "',        '" . $cashBookDescription . "',
            '" . $leafId . "',            0,                            1,
            0,                        0,                            0,
            0,                        0,                            0,
            0,                        '" . $this->getStaffId() . "',    " . $this->getExecuteTime() . ",
            '" . $bankTransferId . "',    '" . $collectionCancellationId . "',  '" . $paymentVoucherCancellationId . "',
            '" . $chartOfAccountId . "'
           )
        ";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
            INSERT INTO [cashbookLedger](
            [cashBookLedgerId[,       [companyId],                  [businessPartnerId],
            [collectionId],           [paymentVoucherId],           [documentNumber],
            [cashBookDate],           [cashBookAmount],             [cashBookDescription],
            [leafId],                 [isDefault],                  [isNew],
            [isDraft],                [isUpdate],                   [isDelete],
            [isActive],               [isApproved],                 [isReview],
            [isPost],                 [executeBy],                  [executeTime],
            [bankTransferId],         [collectionCancellationId],   [paymentVoucherCancellationId],
            [chartOfAccountId]
      ) VALUES (
            null,                     '" . $this->getCompanyId() . "',  '" . $businessPartnerId . "',
            '" . $collectionId . "',      '" . $paymentVoucherId . "',                            '" . $documentNumber . "',
            '" . $cashBookDate . "',      '" . $cashBookAmount . "',        '" . $cashBookDescription . "',
            '" . $leafId . "',            0,                            1,
            0,                        0,                            0,
            0,                        0,                            0,
            0,                        '" . $this->getStaffId() . "',    " . $this->getExecuteTime() . ",
            '" . $bankTransferId . "',    '" . $collectionCancellationId . "',  '" . $paymentVoucherCancellationId . "',
            '" . $chartOfAccountId . "'
           )
        ";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
            INSERT INTO CASHBOOKLEDGER(
            CASHBOOKLEDGERID,           COMPANYID,          BUSINESSPARTNERID,
            COLLECTIONID,               PAYMENTVOUCHERID,   DOCUMENTNUMBER,
            CASHBOOKDATE,               CASHBOOKAMOUNT,     CASHBOOKDESCRIPTION,
            LEAFID,                     ISDEFAULT,          ISNEW,
            ISDRAFT,                    ISUPDATE,           ISDELETE,
            ISACTIVE,                   ISAPPROVED,         ISREVIEW,
            ISPOST,                     EXECUTEBY,          EXECUTETIME,
            BANKTRANSFERID,            COLLECTIONCANCELLATIONID,   PAYMENTVOUCHERCANCELLATIONID,
            CHARTOFCCOUNTID
    ) VALUES (
            null,                     '" . $this->getCompanyId() . "',  '" . $businessPartnerId . "',
            '" . $collectionId . "',      '" . $paymentVoucherId . "',                            '" . $documentNumber . "',
            '" . $cashBookDate . "',      '" . $cashBookAmount . "',        '" . $cashBookDescription . "',
            '" . $leafId . "',            0,                            1,
            0,                        0,                            0,
            0,                        0,                            0,
            0,                        '" . $this->getStaffId() . "',    " . $this->getExecuteTime() . ",
            '" . $bankTransferId . "',    '" . $collectionCancellationId . "',  '" . $paymentVoucherCancellationId . "',
            '" . $chartOfAccountId . "'
           )
        ";
            }
            try {
                $this->q->create($sql);
            } catch (\Exception $e) {
                header('Content-Type:application/json; charset=utf-8');
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
    }

    /**
     * UPDATE / INSERT Procedure For General Ledger
     * @param int $leafId Leaf Primary Key
     * @param string $leafName Leaf Name
     * @param int $businessPartnerId Business Partner Primary Key
     * @param int $chartOfAccountId Chart Of Account Primary Key
     * @param string $journalNumber Journal Number
     * @param string $documentNumber Document Number
     * @param string $documentDate Date
     * @param double $localAmount Amount
     * @param string $description Description
     * @param null|string $module Module
     * @param null|string $tableName Table Name
     * @param null|string $tableNameDetail Table Name Detail
     * @param null|int $tableNameId Table Name Primary key
     * @param null|int $tableNameDetailId Table Name Detail Primary Key
     * @param null|int $referenceTableNameId Reference Table Primary Key
     * @param null|int $referenceTableNameDetailId Reference Table Detail Primary Key
     * @param null|int $generalLedgerId General Ledger Primary Key
     * @return int|null
     */
    public function setGeneralLedger(
    $leafId, $leafName, $businessPartnerId, $chartOfAccountId, $journalNumber, $documentNumber, $documentDate, $localAmount, $description, $module = null, $tableName = null, $tableNameDetail = null, $tableNameId = null, $tableNameDetailId = null, $referenceTableNameId = null, $referenceTableNameDetailId = null, $generalLedgerId = null
    ) {
        // foreign amount. in the future
        $foreignAmount = 0;

        $sql = null;
        if ($localAmount > 0) {
            $this->setTransactionTypeId(1);
            $this->setTransactionTypeCode('D');
            $this->setTransactionTypeDescription('Debit');
        } else {
            $this->setTransactionTypeId(2);
            $this->setTransactionTypeCode('C');
            $this->setTransactionTypeDescription('Debit');
        }
        // return date information

        $this->setFinancePeriodInformation($documentDate);
        // return information chart of account
        $this->setChartOfAccountInformation($chartOfAccountId);
        // return information business partner
        $this->setBusinessPartnerInformation($businessPartnerId);
        $generalLedgerTitle = $description;
        $generalLedgerDescription = $description;
        $generalLedgerDate = date('Y-m-d');
        if (intval($generalLedgerId + 0) > 0) {

            if ($this->getVendor() == self::MYSQL) {
                $sql = "
                UPDATE  `generalledger`
                SET     `financeYearId`                     =   '" . $this->getFinanceYearId() . "',
                        `financeYearYear`                   =   '" . $this->getFinanceYearYear() . "',
                        `financePeriodRangeId`              =   '" . $this->getFinancePeriodRangeId() . "',
                        `financePeriodRangePeriod`          =   '" . $this->getFinancePeriodRangePeriod() . "',
                        `documentDate`                      =   '" . $documentDate . "',
                        `generalLedgerTitle`                =   '" . $generalLedgerTitle . "',
                        `generalLedgerDescription`          =   '" . $generalLedgerDescription . "',
                        `generalLedgerDate`                 =   '" . $generalLedgerDate . "',
                        `transactionTypeId`                 =   '" . $this->getTransactionTypeId() . "',
                        `transactionTypeCode`               =   '" . $this->getTransactionTypeCode() . "',
                        `transactionTypeDescription`        =   '" . $this->getTransactionTypeDescription() . "',
                        `localAmount`                       =   '" . $localAmount . "',
                        `chartOfAccountCategoryId`          =   '" . $this->getChartOfAccountCategoryId() . "',
                        `chartOfAccountCategoryDescription` =   '" . $this->getChartOfAccountCategoryDescription() . "',
                        `chartOfAccountTypeId`              =   '" . $this->getChartOfAccountTypeId() . "',
                        `chartOfAccountTypeDescription`     =   '" . $this->getChartOfAccountTypeDescription() . "',
                        `chartOfAccountId`                  =   '" . $chartOfAccountId . "',
                        `chartOfAccountNumber`              =   '" . $this->getChartOfAccountNumber() . "',
                        `chartOfAccountDescription`         =   '" . $this->getChartOfAccountDescription() . "',
                        `businessPartnerId`                 =   '" . $businessPartnerId . "',
                        `businessPartnerDescription`        =   '" . $this->getBusinessPartnerCompany() . "',
                        `isNew`                             =   0,
                        `isUpdate`                          =   1,
                        `executeBy`                         =   '" . $this->getStaffName() . "',
                        `executeName`                       =   '" . $this->getStaffName() . "',
                        `executeTime`                       =   " . $this->getExecuteTime() . "
                WHERE   `generalLedgerId`                   =   '" . $generalLedgerId . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE  [generalLedger]
                SET     [financeYearId]                     =   '" . $this->getFinanceYearId() . "',
                        [financeYearYear]                   =   '" . $this->getfinanceYearYear() . "',
                        [financePeriodRangeId]              =   '" . $this->getfinancePeriodRangeId() . "',
                        [financePeriodRangePeriod]          =   '" . $this->getFinancePeriodRangePeriod() . "',
                        [documentDate]                      =   '" . $documentDate . "',
                        [generalLedgerTitle]                =   '" . $generalLedgerTitle . "',
                        [generalLedgerDescription]          =   '" . $generalLedgerDescription . "',
                        [generalLedgerDate]                 =   '" . $generalLedgerDate . "',
                        [transactionTypeId]                 =   '" . $this->getTransactionTypeId() . "',
                        [transactionTypeCode]               =   '" . $this->getTransactionTypeCode() . "',
                        [transactionTypeDescription]        =   '" . $this->getTransactionTypeDescription() . "',
                        [localAmount]                       =   '" . $localAmount . "',
                        [chartOfAccountCategoryId]          =   '" . $this->getChartOfAccountCategoryId() . "',
                        [chartOfAccountCategoryDescription] =   '" . $this->getChartOfAccountCategoryDescription() . "',
                        [chartOfAccountTypeId]              =   '" . $this->getChartOfAccountTypeId() . "',
                        [chartOfAccountTypeDescription]     =   '" . $this->getChartOfAccountDescription() . "',
                        [chartOfAccountId]                  =   '" . $chartOfAccountId . "',
                        [chartOfAccountNumber]              =   '" . $this->getChartOfAccountNumber() . "',
                        [chartOfAccountDescription]         =   '" . $this->getChartOfAccountDescription() . "',
                        [businessPartnerId]                 =   '" . $businessPartnerId . "',
                        [businessPartnerDescription]        =   '" . $this->getBusinessPartnerCompany() . "',
                        [isNew]                             =   0,
                        [isUpdate]                          =   1,
                        [executeBy]                         =   '" . $this->getStaffName() . "',
                        [executeName]                       =   '" . $this->getStaffName() . "',
                        [executeTime]                       =   " . $this->getExecuteTime() . "
                WHERE   [generalLedgerId]                   =   '" . $generalLedgerId . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE  GENERALLEDGER
                SET     FINANCEYEARID                     =   '" . $this->getFinanceYearId() . "',
                        FINANCEYEARYEAR                   =   '" . $this->getFinanceYearYear() . "',
                        FINANCEPERIODRANGEID              =   '" . $this->getFinancePeriodRangeId() . "',
                        FINANCEPERIODRANGEPERIOD          =   '" . $this->getFinancePeriodRangePeriod() . "',
                        DOCUMENTDATE                      =   '" . $documentDate . "',
                        GENERALLEDGERTITLE                =   '" . $generalLedgerTitle . "',
                        GENERALLEDGERDESCRIPTION          =   '" . $generalLedgerDescription . "',
                        GENERALLEDGERDATE                 =   '" . $generalLedgerDate . "',
                        TRANSACTIONTYPEID                 =   '" . $this->getTransactionTypeId() . "',
                        TRANSACTIONTYPECODE               =   '" . $this->getTransactionTypeCode() . "',
                        TRANSACTIONTYPEDESCRIPTION        =   '" . $this->getTransactionTypeDescription() . "',
                        LOCALAMOUNT                       =   '" . $localAmount . "',
                        CHARTOFACCOUNTCATEGORYID          =   '" . $this->getChartOfAccountCategoryId() . "',
                        CHARTOFACCOUNTCATEGORYDESCRIPTION =   '" . $this->getChartOfAccountCategoryDescription() . "',
                        CHARTOFACCOUNTTYPEID              =   '" . $this->getChartOfAccountTypeId() . "',
                        CHARTOFACCOUNTTYPEDESCRIPTION     =   '" . $this->getChartOfAccountDescription() . "',
                        CHARTOFACCOUNTID                  =   '" . $chartOfAccountId . "',
                        CHARTOFACCOUNTNUMBER              =   '" . $this->getChartOfAccountNumber() . "',
                        CHARTOFACCOUNTDESCRIPTION         =   '" . $this->getChartOfAccountDescription() . "',
                        BUSINESSPARTNERID                 =   '" . $businessPartnerId . "',
                        BUSINESSPARTNERDESCRIPTION        =   '" . $this->getBusinessPartnerCompany() . "',
                        ISNEW                             =   0,
                        ISUPDATE                          =   1,
                        EXECUTEBY                         =   '" . $this->getStaffName() . "',
                        EXECUTENAME                       =   '" . $this->getStaffName() . "',
                        EXECUTETIME                       =   " . $this->getExecuteTime() . "
                WHERE   GENERALLEDGERID                   =   '" . $generalLedgerId . "'";
            }
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
                header('Content-Type:application/json; charset=utf-8');
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
                INSERT INTO `generalledger`(
                            `generalLedgerId`,                              `companyId`,                                    `financeYearId`,
                            `financeYearYear`,                              `financePeriodRangeId`,                         `financePeriodRangePeriod`,
                            `journalNumber`,                                `documentNumber`,                               `documentDate`,
                            `generalLedgerTitle`,                           `generalLedgerDescription`,                     `generalLedgerDate`,
                            `countryId`,                                    `countryCurrencyCode`,                          `transactionTypeId`,
                            `transactionTypeCode`,                          `transactionTypeDescription`,                   `foreignAmount`,
                            `localAmount`,                                  `chartOfAccountCategoryId`,                     `chartOfAccountCategoryDescription`,
                            `chartOfAccountTypeId`,                         `referenceTableNameId`,                         `referenceTableNameDetailId`,
                            `chartOfAccountTypeDescription`,                `chartOfAccountId`,                             `chartOfAccountNumber`,
                            `chartOfAccountDescription`,                    `businessPartnerId`,                            `businessPartnerDescription`,
                            `module`,                                       `tableName`,                                    `tableNameId`,
                            `tableNameDetail`,                              `tableNameDetailId`,                            `leafId`,
                            `isDefault`,                                    `isNew`,                                        `isDraft`,
                            `isUpdate`,                                     `isDelete`,                                     `isActive`,
                            `isApproved`,                                   `isReview`,                                     `isPost`,
                            `isMerge`,                                      `isSlice`,                                      `isAuthorized`,
                            `executeBy`,                                    `executeName`,                                  `executeTime`,
							`leafName`,                                     `chartOfAccountCategoryCode`,			        `chartOfAccountTypeCode`
                    ) VALUES (
                                null,                                       '" . $this->getCompanyId() . "',                '" . $this->getFinanceYearId() . "',
                                '" . $this->getFinanceYearYear() . "',      '" . $this->getFinancePeriodRangeId() . "',     '" . $this->getFinancePeriodRangePeriod() . "',
                                '" . $journalNumber . "',                   '" . $documentNumber . "',                      '" . $documentDate . "',
                                '" . $generalLedgerTitle . "',              '" . $generalLedgerDescription . "',            '" . $generalLedgerDate . "',
                                '" . $this->getCountryId() . "',            '" . $this->getCountryCurrencyCode() . "',      '" . $this->getTransactionTypeId() . "',
                                '" . $this->getTransactionTypeCode() . "',  '" . $this->getTransactionTypeDescription() . "',  '" . $foreignAmount . "',
                                '" . $localAmount . "',                     '" . $this->getChartOfAccountCategoryId() . "', '" . $this->getChartOfAccountDescription() . "',
                                '" . $this->getChartOfAccountTypeId() . "', '" . $referenceTableNameId . ",                 '" . $referenceTableNameDetailId . "',
                                '" . $this->getChartOfAccountTypeDescription() . "',    '" . $chartOfAccountId . "',        '" . $this->getChartOfAccountNumber() . "',
                                '" . $this->getChartOfAccountDescription() . "',   '" . $businessPartnerId . "',            '" . $this->getBusinessPartnerCompany() . "',
                                '" . $module . "',                          '" . $tableName . "',                           '" . $tableNameId . "',
                                '" . $tableNameDetail . "',             '" . $tableNameDetailId . "',                       '" . $leafId . "',
                                0,                                           1,                                             0,
                                0,                                           0,                                             1,
                                0,                                           0,                                             0,
                                0,                                           0,                                             0,
                                '" . $this->getStaffId() . "',               '" . $this->getStaffName() . "',               " . $this->getExecuteTime() . ",
								'" . $leafName . "',                         '" . $this->getChartOfAccountCategoryCode() . "','" . $this->getChartOfAccountTypeCode() . "'
					);";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                INSERT INTO [generalLedger](
                            [generalLedgerId],                              [companyId],                                    [financeYearId],
                            [financeYearYear],                              [financePeriodRangeId],                         [financePeriodRangePeriod],
                            [journalNumber],                                [documentNumber],                               [documentDate],
                            [generalLedgerTitle],                           [generalLedgerDescription],                     [generalLedgerDate],
                            [countryId],                                    [countryCurrencyCode],                          [transactionTypeId],
                            [transactionTypeCode],                          [transactionTypeDescription],                   [foreignAmount],
                            [localAmount],                                  [chartOfAccountCategoryId],                     [chartOfAccountCategoryDescription],
                            [chartOfAccountTypeId],                         [referenceTableNameId],                         [referenceTableNameDetailId],
                            [chartOfAccountTypeDescription],                [chartOfAccountId],                             [chartOfAccountNumber],
                            [chartOfAccountDescription],                    [businessPartnerId],                            [businessPartnerDescription],
                            [module],                                       [tableName],                                    [tableNameId],
                            [tableNameDetail],                              [tableNameDetailId],                            [leafId],
                            [isDefault],                                    [isNew],                                        [isDraft],
                            [isUpdate],                                     [isDelete],                                     [isActive],
                            [isApproved],                                   [isReview],                                     [isPost],
                            [isMerge],                                      [isSlice],                                      [isAuthorized],
                            [executeBy],                                    [executeName],                                  [executeTime],
							[leafName],                                     [chartOfAccountCategoryCode],			        [chartOfAccountTypeCode]
                    ) VALUES (
                                null,                                       '" . $this->getCompanyId() . "',                '" . $this->getFinanceYearId() . "',
                                '" . $this->getFinanceYearYear() . "',      '" . $this->getFinancePeriodRangeId() . "',     '" . $this->getFinancePeriodRangePeriod() . "',
                                '" . $journalNumber . "',                   '" . $documentNumber . "',                      '" . $documentDate . "',
                                '" . $generalLedgerTitle . "',              '" . $generalLedgerDescription . "',            '" . $generalLedgerDate . "',
                                '" . $this->getCountryId() . "',            '" . $this->getCountryCurrencyCode() . "',      '" . $this->getTransactionTypeId() . "',
                                '" . $this->getTransactionTypeCode() . "',  '" . $this->getTransactionTypeDescription() . "',  '" . $foreignAmount . "',
                                '" . $localAmount . "',                     '" . $this->getChartOfAccountCategoryId() . "', '" . $this->getChartOfAccountDescription() . "',
                                '" . $this->getChartOfAccountTypeId() . "', '" . $referenceTableNameId . ",                 '" . $referenceTableNameDetailId . "',
                                '" . $this->getChartOfAccountTypeDescription() . "',    '" . $chartOfAccountId . "',        '" . $this->getChartOfAccountNumber() . "',
                                '" . $this->getChartOfAccountDescription() . "',   '" . $businessPartnerId . "',            '" . $this->getBusinessPartnerCompany() . "',
                                '" . $module . "',                          '" . $tableName . "',                           '" . $tableNameId . "',
                                '" . $tableNameDetail . "',             '" . $tableNameDetailId . "',                       '" . $leafId . "',
                                0,                                           1,                                             0,
                                0,                                           0,                                             1,
                                0,                                           0,                                             0,
                                0,                                           0,                                             0,
                                '" . $this->getStaffId() . "',               '" . $this->getStaffName() . "',               " . $this->getExecuteTime() . ",
								'" . $leafName . "',                         '" . $this->getChartOfAccountCategoryCode() . "','" . $this->getChartOfAccountTypeCode() . "'
					);";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                INSERT INTO GENERALLEDGER(
                            GENERALLEDGERID,                              COMPANYID,                                    FINANCEYEARID,
                            FINANCEYEARYEAR,                              FINANCEPERIODRANGEID,                         FINANCEPERIODRANGEPERIOD,
                            JOURNALNUMBER,                                DOCUMENTNUMBER,                               DOCUMENTDATE,
                            GENERALLEDGERTITLE,                           GENERALLEDGERDESCRIPTION,                     GENERALLEDGERDATE,
                            COUNTRYID,                                    COUNTRYCURRENCYCODE,                          TRANSACTIONTYPEID,
                            TRANSACTIONTYPECODE,                          TRANSACTIONTYPEDESCRIPTION,                   FOREIGNAMOUNT,
                            LOCALAMOUNT,                                  CHARTOFACCOUNTCATEGORYID,                     CHARTOFACCOUNTCATEGORYDESCRIPTION,
                            CHARTOFACCOUNTTYPEID,                         REFERENCETABLENAMEID,                         REFERENCETABLENAMEDETAILID,
                            CHARTOFACCOUNTTYPEDESCRIPTION,                CHARTOFACCOUNTID,                             CHARTOFACCOUNTNUMBER,
                            CHARTOFACCOUNTDESCRIPTION,                    BUSINESSPARTNERID,                            BUSINESSPARTNERDESCRIPTION,
                            MODULE,                                       TABLENAME,                                    TABLENAMEID,
                            TABLENAMEDETAIL,                              TABLENAMEDETAILID,                            LEAFID,
                            ISDEFAULT,                                    ISNEW,                                        ISDRAFT,
                            ISUPDATE,                                     ISDELETE,                                     ISACTIVE,
                            ISAPPROVED,                                   ISREVIEW,                                     ISPOST,
                            ISMERGE,                                      ISSLICE,                                      ISAUTHORIZED,
                            EXECUTEBY,                                    EXECUTENAME,                                  EXECUTETIME,
							LEAFNAME,                                     CHARTOFACCOUNTCATEGORYCODE,			        CHARTOFACCOUNTTYPECODE
                    ) VALUES (
                                null,                                       '" . $this->getCompanyId() . "',                '" . $this->getFinanceYearId() . "',
                                '" . $this->getFinanceYearYear() . "',      '" . $this->getFinancePeriodRangeId() . "',     '" . $this->getFinancePeriodRangePeriod() . "',
                                '" . $journalNumber . "',                   '" . $documentNumber . "',                      '" . $documentDate . "',
                                '" . $generalLedgerTitle . "',              '" . $generalLedgerDescription . "',            '" . $generalLedgerDate . "',
                                '" . $this->getCountryId() . "',            '" . $this->getCountryCurrencyCode() . "',      '" . $this->getTransactionTypeId() . "',
                                '" . $this->getTransactionTypeCode() . "',  '" . $this->getTransactionTypeDescription() . "',  '" . $foreignAmount . "',
                                '" . $localAmount . "',                     '" . $this->getChartOfAccountCategoryId() . "', '" . $this->getChartOfAccountDescription() . "',
                                '" . $this->getChartOfAccountTypeId() . "', '" . $referenceTableNameId . ",                 '" . $referenceTableNameDetailId . "',
                                '" . $this->getChartOfAccountTypeDescription() . "',    '" . $chartOfAccountId . "',        '" . $this->getChartOfAccountNumber() . "',
                                '" . $this->getChartOfAccountDescription() . "',   '" . $businessPartnerId . "',            '" . $this->getBusinessPartnerCompany() . "',
                                '" . $module . "',                          '" . $tableName . "',                           '" . $tableNameId . "',
                                '" . $tableNameDetail . "',             '" . $tableNameDetailId . "',                       '" . $leafId . "',
                                0,                                           1,                                             0,
                                0,                                           0,                                             1,
                                0,                                           0,                                             0,
                                0,                                           0,                                             0,
                                '" . $this->getStaffId() . "',               '" . $this->getStaffName() . "',               " . $this->getExecuteTime() . ",
								'" . $leafName . "',                         '" . $this->getChartOfAccountCategoryCode() . "','" . $this->getChartOfAccountTypeCode() . "'
					);";
            }
            try {
                $this->q->create($sql);
            } catch (\Exception $e) {
                header('Content-Type:application/json; charset=utf-8');
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
            $generalLedgerId = $this->q->lastInsertId('generalLedger');
        }
        return $generalLedgerId;
    }

    /**
     * SELECT / UPDATE / INSERT Procedure For Invoice Ledger
     * @param int $businessPartnerId Business Partner Primary Key
     * @param int $chartOfAccountId Chart Of Account Primary key
     * @param string $documentNumber Document Number
     * @param string $invoiceDate Date
     * @param string $invoiceDueDate Due Date
     * @param string $invoiceDescription Description
     * @param float $invoiceAmount Amount
     * @param int $leafId Leaf Primary Key
     * @param null|int $invoiceId Invoice Primary Key
     * @param null|int $invoiceProjectId Purchase Invoice Project Primary Key
     * @param null|int $invoiceAdjustmentId Payment Voucher Adjustment Primary Key
     * @param null|int $invoiceDebitNoteId Payment Voucher Adjustment Primary Key
     * @param null|int $invoiceCreditNoteId Payment Voucher Adjustment Primary Key
     * @param null|int $collectionId Payment Voucher Adjustment Primary Key
     * @return int
     * @throws \Exception
     */
    public function setInvoiceLedger(
    $businessPartnerId, $chartOfAccountId, $documentNumber, $invoiceDate, $invoiceDueDate, $invoiceDescription, $invoiceAmount, $leafId, $invoiceId = null, $invoiceProjectId = null, $invoiceAdjustmentId = null, $invoiceDebitNoteId = null, $invoiceCreditNoteId = null, $collectionId = null
    ) {
        $invoiceLedgerId = null;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT	`invoiceId`
			FROM	`invoiceledger`
			WHERE	`invoiceId`	=	'" . $invoiceId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT	[invoiceId]
			FROM	[invoiceledger]
			WHERE	[invoiceId]	=	'" . $invoiceId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT	INVOICEID AS \"invoiceId\"
			FROM	INVOICELEDGER
			WHERE	INVOICEID	=	'" . $invoiceId . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        $invoiceId = $row['invoiceId'];
        if ((intval($invoiceId) + 0) > 0) {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
				UPDATE	`invoiceledger`
				SET 	`businessPartnerId`		=	'" . $businessPartnerId . "',
						`invoiceDate`			=	'" . $invoiceDate . "',
						`invoiceDueDate`		=	'" . $invoiceDueDate . "',
						`invoiceAmount`			=	'" . $invoiceAmount . "',
						`invoiceDescription`	=	'" . $invoiceDescription . "',
						`isNew`					=	0,
						`isUpdate`				=	1,
						`executeBy`				=	'" . $this->getStaffId() . "',
						`executeTime`			=	'" . $this->getExecuteTime() . "'
				WHERE 	`invoiceId`				=	'" . $invoiceId . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
				UPDATE	[invoiceLedger]
				SET 	[businessPartnerId]		=	'" . $businessPartnerId . "',
						[invoiceDate]			=	'" . $invoiceDate . "',
						[invoiceDueDate]		=	'" . $invoiceDueDate . "',
						[invoiceAmount]			=	'" . $invoiceAmount . "',
						[invoiceDescription]	=	'" . $invoiceDescription . "',
						[isNew]					=	0,
						[isUpdate]				=	1,
						[executeBy]				=	'" . $this->getStaffId() . "',
						[executeTime]			=	'" . $this->getExecuteTime() . "'
				WHERE 	[invoiceId]				=	'" . $invoiceId . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
				UPDATE	INVOICELEDGER
				SET 	BUSINESSPARTNERID		=	'" . $businessPartnerId . "',
						INVOICEDATE				=	'" . $invoiceDate . "',
						INVOICEDUEDATE			=	'" . $invoiceDueDate . "',
						INVOICEAMOUNT			=	'" . $invoiceAmount . "',
						INVOICEDESCRIPTION		=	'" . $invoiceDescription . "',
						ISNEW					=	0,
						ISUPDATE				=	1,
						EXECUTEBY				=	'" . $this->getStaffId() . "',
						EXECUTETIME				=	'" . $this->getExecuteTime() . "'
				WHERE 	INVOICEID				=	'" . $invoiceId . "'";
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
                INSERT INTO `invoiceledger`(
                    `invoiceLedgerId`, 					`companyId`,            `businessPartnerId`,
                    `chartOfAccountId`,                 `invoiceProjectId`,     `invoiceId`,
                    `invoiceAdjustmentId`,              `invoiceDebitNoteId`,   `invoiceCreditNoteId`,
                    `collectionId`,                     `documentNumber`,       `invoiceDate`,
                    `invoiceDueDate`,                   `invoiceAmount`,        `invoiceDescription`,
                    `leafId`,                           `isDefault`,            `isNew`,
                    `isDraft`,                          `isUpdate`, 		    `isDelete`,
                    `isActive`,                         `isApproved`,           `isReview`,
                    `isPost`,                           `executeBy`,            `executeTime`
                ) VALUES (
                    null,								'" . $this->getcompanyId() . "',    '" . $businessPartnerId . "',
                    '" . $chartOfAccountId . "',           '" . $invoiceProjectId . "',        '" . $invoiceId . "',
                    '" . $invoiceAdjustmentId . "'         '" . $invoiceDebitNoteId . "',          '" . $invoiceCreditNoteId . "',
                    '" . $collectionId . "',               '" . $documentNumber . "',               '" . $invoiceDate . "',
                    '" . $invoiceDueDate . "',          '" . $invoiceAmount . "',			'" . $invoiceDescription . "',
                    '" . $leafId . "',                  0,                                 1,
                    1,                                  0,								    0,
                    1,                                  0,                                 0,
                    0,                                  '" . $this->getStaffId() . "',			" . $this->getExecuteTime() . "
                )
                ";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                INSERT INTO [invoiceLedger](
                    [invoiceLedgerId], 					[companyId],            [businessPartnerId],
                    [chartOfAccountId],                 [invoiceProjectId],     [invoiceId],
                    [invoiceAdjustmentId],              [invoiceDebitNoteId],   [invoiceCreditNoteId],
                    [collectionId],                     [documentNumber],       [invoiceDate],
                    [invoiceDueDate],                   [invoiceAmount],        [invoiceDescription],
                    [leafId],                           [isDefault],            [isNew],
                    [isDraft],                          [isUpdate], 		    [isDelete],
                    [isActive],                         [isApproved],           [isReview],
                    [isPost],                           [executeBy],            [executeTime]
                ) VALUES (
                    null,								'" . $this->getcompanyId() . "',    '" . $businessPartnerId . "',
                    '" . $chartOfAccountId . "',           '" . $invoiceProjectId . "',        '" . $invoiceId . "',
                    '" . $invoiceAdjustmentId . "'         '" . $invoiceDebitNoteId . "',          '" . $invoiceCreditNoteId . "',
                    '" . $collectionId . "',               '" . $documentNumber . "',               '" . $invoiceDate . "',
                    '" . $invoiceDueDate . "',          '" . $invoiceAmount . "',			'" . $invoiceDescription . "',
                    '" . $leafId . "',                  0,                                 1,
                    1,                                  0,								    0,
                    1,                                  0,                                 0,
                    0,                                  '" . $this->getStaffId() . "',			" . $this->getExecuteTime() . "
                )
                ";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
               INSERT INTO INVOICELEDGER(
                    INVOICELEDGERID, 					COMPANYID,            BUSINESSPARTNERID,
                    CHARTOFACCOUNTID,                 INVOICEPROJECTID,     INVOICEID,
                    INVOICEADJUSTMENTID,              INVOICEDEBITNOTEID,   INVOICECREDITNOTEID,
                    COLLECTIONID,                     DOCUMENTNUMBER,       INVOICEDATE,
                    INVOICEDUEDATE,                   INVOICEAMOUNT,        INVOICEDESCRIPTION,
                    LEAFID,                           ISDEFAULT,            ISNEW,
                    ISDRAFT,                          ISUPDATE, 		    ISDELETE,
                    ISACTIVE,                         ISAPPROVED,           ISREVIEW,
                    ISPOST,                           EXECUTEBY,            EXECUTETIME
                ) VALUES (
                    null,								'" . $this->getcompanyId() . "',    '" . $businessPartnerId . "',
                    '" . $chartOfAccountId . "',           '" . $invoiceProjectId . "',        '" . $invoiceId . "',
                    '" . $invoiceAdjustmentId . "'         '" . $invoiceDebitNoteId . "',          '" . $invoiceCreditNoteId . "',
                    '" . $collectionId . "',               '" . $documentNumber . "',               '" . $invoiceDate . "',
                    '" . $invoiceDueDate . "',          '" . $invoiceAmount . "',			'" . $invoiceDescription . "',
                    '" . $leafId . "',                  0,                                 1,
                    1,                                  0,								    0,
                    1,                                  0,                                 0,
                    0,                                  '" . $this->getStaffId() . "',			" . $this->getExecuteTime() . "
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
            $invoiceLedgerId = $this->q->lastInsertId('invoiceLedger');
        }
        return $invoiceLedgerId;
    }

    /**
     * Testing Closing Period
     * @param string $date Document Date
     * @param int $type 1 -> normal journal,2 month end journal ,3 year end journal. For month end and year end ,it's more on adjustment after  closing
     * @return bool
     */
    public function getFinancialSetting($date, $type) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
                SELECT  `isOddFinancePeriod`,
                        `isMonthEndClosing`,
                        `isYearEndClosing`
                FROM    `financialsetting`
                WHERE   `companyId` =   '" . $_SESSION['companyId'] . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                SELECT  [isOddFinancePeriod],
                        [isMonthEndClosing],
                        [isYearEndClosing]
                FROM    [financialSetting]
                WHERE   [companyId] =   '" . $_SESSION['companyId'] . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                SELECT  ISODDFINANCEPERIOD  AS \"isOddFinancePeriod\",
                        ISMONTHENDCLOSING   AS \"isMonthEndClosing\",
                        ISYEARENDCLOSING    AS  \"isYearEndClosing\"
                FROM    FINANCIALSETTING
                WHERE   COMPANYID   =   '" . $_SESSION['companyId'] . "'";
        }
        $result = $this->q->fast($sql);
        $total = $this->q->numberRows();
        if ($total > 0) {
            $row = $this->q->fetchArray($result);

            $isOddFinancePeriod = (bool) $row['isOddFinancePeriod'];
            $isMonthEndClosing = (bool) $row['isMonthEndClosing'];
            $isYearEndClosing = (bool) $row['isYearEndClosing'];
            if ($type == 1) {
                if ($isMonthEndClosing == true) {
                    if ($isOddFinancePeriod == true) {
                        // check table financial period

                        if ($this->getVendor() == self::MYSQL) {
                            $sql = "
                                    SELECT  `isClose`
                                    FROM    `financePeriodRange`
                                    WHERE   `companyId`                     =   '" . $_SESSION['companyId'] . "'
                                    AND     `financePeriodRangeStartDate`   <=  '" . $date . "' 
                                    AND     `financePeriodRangeEndDate`     >=  '" . $date . "'";
                        } else if ($this->getVendor() == self::MSSQL) {
                            $sql = "
                                    SELECT  [isClose]
                                    FROM     [financePeriodRange]
                                    WHERE   [companyId]                     =   '" . $_SESSION['companyId'] . "'
                                    AND     [financePeriodRangeStartDate]   <=  '" . $date . "'
                                    AND     [financePeriodRangeEndDate]     >=  '" . $date . "'";
                        } else if ($this->getVendor() == self::ORACLE) {
                            $sql = "
                                    SELECT  ISCLOSE AS isClose
                                    FROM    FINANCEPERIODRANGE
                                    WHERE   COMPANYID                     =   '" . $_SESSION['companyId'] . "'
                                    AND     FINANCEPERIODRANGESTARTDATE   <=  '" . $date . "'
                                    AND     FINANCEPERIODRANGEENDDATE     >=  '" . $date . "'";
                            ;
                        }
                        $resultFinancePeriodRange = $this->q->fast($sql);
                        $total = $this->q->numberRows($resultFinancePeriodRange);
                        if ($total > 0) {
                            $row = $this->q->fetchArray($resultFinancePeriodRange);
                            if ($row['isClose'] == 1) {
                                return false;
                            } else {
                                return true;
                            }
                        } else {
                            return false;
                        }
                    } else {
                        // just check  closing or not only

                        $dateArray = explode("-", $date);
                        $period = $dateArray[1];
                        $year = $dateArray[0];
                        if ($this->getVendor() == self::MYSQL) {
                            $sql = "
                                    SELECT  `financePeriod`.`isClose`
                                    FROM    `financePeriod`
                                    JOIN    `financeYear`
                                    USING   (`companyId`,`financeYearId`)
                                    WHERE   `financePeriod`.`companyId`     =   '" . $_SESSION['companyId'] . "'
                                    AND     `financePeriod`.`financePeriod` =   '" . $period . "'
                                    AND     `financeYear`.`financeYear`     =   '" . $year . "'";
                        } else if ($this->getVendor() == self::MSSQL) {
                            $sql = "
                                    SELECT  [financePeriod].[isClose]
                                    FROM    [financePeriod]
                                    JOIN    [financeYear]
                                    ON      [financePeriod].[companyId]     =   [financeYear].[companyId]
                                    AND     [financePeriod].[financeYearId] =   [financeYear].[financeYearId]
                                    WHERE   [financePeriod].[companyId]     =   '" . $_SESSION['companyId'] . "'
                                    AND     [financePeriod].[financePeriod] =   '" . $period . "'";
                        } else if ($this->getVendor() == self::ORACLE) {
                            $sql = "
                                    SELECT  FINANCEPERIOD.ISCLOSE AS isClose
                                    FROM    FINANCEPERIOD
                                    JOIN    FINANCEYEAR
                                    WHERE   FINANCEPERIOD.COMPANYID         =   '" . $_SESSION['companyId'] . "'
                                    AND     FINANCEPERIOD.FINANCEPERIOD     =   '" . $period . "'
                                    AND     FINANCEYEAR.FINANCEYEAR         =   '" . $year . "'";
                            ;
                        }
                        $resultFinancePeriodRange = $this->q->fast($sql);
                        $total = $this->q->numberRows($resultFinancePeriodRange);
                        if ($total > 0) {
                            $row = $this->q->fetchArray($resultFinancePeriodRange);
                            if ($row['isClose'] == 1) {
                                return false;
                            } else {
                                return true;
                            }
                        } else {
                            return false;
                        }
                    }
                }
            } else {
                if ($type == 2) {
                    if ($isYearEndClosing == true) {
                        // check table financial period


                        $dateArray = explode("-", $date);
                        $year = $dateArray[0];
                        if ($this->getVendor() == self::MYSQL) {
                            $sql = "
                                    SELECT  `isClose`
                                    FROM    `financeYear`
                                    WHERE   `companyId`     =   '" . $_SESSION['companyId'] . "'
                                    AND     `financeYear`   =   '" . $year . "'";
                        } else if ($this->getVendor() == self::MSSQL) {
                            $sql = "
                                    SELECT  [isClose]
                                    FROM    [financeYear]
                                    WHERE   [companyId]     =   '" . $_SESSION['companyId'] . "'
                                    AND     [financeYear]   =  '" . $year . "' ";
                        } else if ($this->getVendor() == self::ORACLE) {
                            $sql = "
                                    SELECT  ISCLOSE AS isClose
                                    FROM    FINANCEYEAR
                                    WHERE   COMPANYID   =   '" . $_SESSION['companyId'] . "'
                                    AND     FINANCEYEAR =   '" . $year . "' ";
                            ;
                        }
                        $resultFinancePeriodRange = $this->q->fast($sql);
                        $total = $this->q->numberRows($resultFinancePeriodRange);
                        if ($total > 0) {
                            $row = $this->q->fetchArray($resultFinancePeriodRange);
                            if ($row['isClose'] == 1) {
                                return false;
                            } else {
                                return true;
                            }
                        } else {
                            return false;
                        }
                    } else {
                        // message user have to reopen first then can put transaction
                        return false;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Return Financial Date Information
     * @param string $documentDate Document Date
     */
    public function setFinancePeriodInformation($documentDate) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `financeyear`.`financeYearYear`,
                     `financeyear`.`financeYearId`,
                     `financeperiodrange`.`financePeriodRangeId`,
                     `financeperiodrange`.`financePeriodRangePeriod`
         FROM        `financeperiodrange`
         JOIN        `financeyear`
         USING       (`companyId`,`financeYearId`)
         WHERE       `financeperiodrange`.`isActive`  =   1
         AND         `financeperiodrange`.`companyId` =   '" . $this->getCompanyId() . "'
         AND         '" . $documentDate . "' between `financeperiodrange`.`financePeriodRangeStartDate` AND  `financeperiodrange`.`financePeriodRangeEndDate`
         ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [financeYear].[financeYearYear],
                     [financeYear].[financeYearId],
                      [financePeriodRange].[financePeriodRangeId],
                      [financePeriodRange].[financePeriodRangePeriod]
         FROM         [financePeriodRange]
         JOIN        [financeYear]
         ON           [financePeriodRange].[companyId]       =  [financeYear].[companyId]
         AND          [financePeriodRange].[financeYearId]   =  [financeYear].[financeYearId]
         WHERE        [financePeriodRange].[isActive]        =   1
         AND          [financePeriodRange].[companyId]       =   '" . $this->getCompanyId() . "'
            AND       '" . $documentDate . "' between  [financePeriodRange].[financePeriodRangeStartDate] AND   [financePeriodRange].[financePeriodRangeEndDate]
         ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      FINANCEYEAR.FINANCEYEARYEAR                    AS  \"financeYearYear\",
                     FINANCEYEAR.FINANCEYEARID                      AS  \"financeYearId\",
                     FINANCEPERIODRANGE.FINANCEPERIODRANGEID        AS  \"financePeriodRangeId\",
                     FINANCEPERIODRANGE.FINANCEPERIODRANGEPERIOD    AS  \"financePeriodRangePeriod\"
         FROM        FINANCEPERIODRANGE
         JOIN        FINANCEYEAR
         ON          FINANCEPERIODRANGE.COMPANYID       =  FINANCEYEAR.COMPANYID
         AND         FINANCEPERIODRANGE.FINANCEYEARID   =  FINANCEYEAR.FINANCEYEARID
         WHERE       FINANCEPERIODRANGE.ISACTIVE        =   1
         AND         FINANCEPERIODRANGE.COMPANYID       =   '" . $this->getCompanyId() . "'
            AND      '" . $documentDate . "' BETWEEN FINANCEPERIODRANGE.FINANCEPERIODRANGESTARTDATE AND  FINANCEPERIODRANGE.FINANCEPERIODRANGEENDDATE
         ";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
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
            $this->setFinanceYearYear($row['financeYearYear']);
            $this->setFinanceYearId($row['financeYearId']);
            $this->setFinancePeriodRangeId($row['financePeriodRangeId']);
            $this->setFinancePeriodRangePeriod($row['financePeriodRangePeriod']);
        }
    }

    /**
     * Set Chart Of Account Information
     * @param int $chartOfAccountId Chart Of Account Primary Key
     * @return null
     */
    private function setChartOfAccountInformation($chartOfAccountId) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `chartofaccountcategory`.`chartOfAccountCategoryTitle` as `chartOfAccountCategoryDescription`,
                     `chartofaccountcategory`.`chartOfAccountCategoryId`,
					 `chartofaccountcategory`.`chartOfAccountCategoryCode`,
                     `chartofaccounttype`.`chartOfAccountTypeDescription`,
                     `chartofaccounttype`.`chartOfAccountTypeId`,
					 `chartofaccounttype`.`chartOfAccountTypeCode`,
                     `chartofaccount`.`chartOfAccountId`,
                     `chartofaccount`.`chartOfAccountNumber`,
                     `chartofaccount`.`chartOfAccountDescription`
         FROM        `chartofaccount`
         JOIN        `chartofaccountcategory`
         USING       (`companyId`,`chartOfAccountCategoryId`)
         JOIN        `chartofaccounttype`
         USING       (`companyId`,`chartOfAccountCategoryId`,`chartOfAccountTypeId`)
         WHERE       `chartofaccount`.`isActive`            =   1
         AND         `chartofaccount`.`companyId`           =   '" . $this->getCompanyId() . "'
         AND    	 `chartofaccount`.`chartOfAccountId`    =   '" . $chartOfAccountId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [chartOfAccountCategory][chartOfAccountCategoryTitle] as [chartOfAccountCategoryDescription],
                     [chartOfAccountCategory][chartOfAccountCategoryId],
					 [chartOfAccountCategory][chartOfAccountCategoryCode],
                     [chartOfAccountType].[chartOfAccountTypeDescription],
                     [chartOfAccountType].[chartOfAccountTypeId],
					 [chartOfAccountType].[chartOfAccountTypeCode],
                     [chartOfAccount].[chartOfAccountId],
                     [chartOfAccount].[chartOfAccountNumber],
                     [chartOfAccount].[chartOfAccountDescription]
         FROM        [chartOfAccount]
         JOIN        [chartOfAccountCategory]
         ON          [chartOfAccount].[companyId]                   =   [chartOfAccountCategory][companyId]
         AND         [chartOfAccount].[chartOfAccountCategoryId]    =   [chartOfAccountCategory][chartOfAccountCategoryId]
         JOIN        [chartOfAccountType]
         ON          [chartOfAccount].[companyId]                   =   [chartOfAccountType].[companyId]
         AND         [chartOfAccount].[chartOfAccountTypeId]        =   [chartOfAccountType].[chartOfAccountTypeId]
         AND         [chartOfAccount].[chartOfAccountCategoryId]    =   [chartOfAccountType].[chartOfAccountCategoryId]
         WHERE       [chartOfAccount].[isActive]                    =   1
         AND         [chartOfAccount].[companyId]                   =   '" . $this->getCompanyId() . "'
         AND    	 [chartOfAccount].[chartOfAccountId]            =   '" . $chartOfAccountId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYTITLE         AS  \"chartOfAccountCategoryDescription\",
                     CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID    AS  \"chartOfAccountCategoryId\",
					 CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE    AS  \"chartOfAccountCategoryCode\",
                     CHARTOFACCOUNTTYPE.CHARTOFACCOUNTTYPEDESCRIPTION       AS  \"chartOfAccountTypeDescription\",
                     CHARTOFACCOUNTTYPE.CHARTOFACCOUNTTYPEID            AS  \"chartOfAccountTypeId\",
                     CHARTOFACCOUNTTYPE.CHARTOFACCOUNTTYPECODE            AS  \"chartOfAccountTypeCode\",
					 CHARTOFACCOUNT.CHARTOFACCOUNTID                    AS  \"chartOfAccountId\",
                     CHARTOFACCOUNT.CHARTOFACCOUNTNUMBER                AS  \"chartOfAccountNumber\",
                     CHARTOFACCOUNT.CHARTOFACCOUNTDESCRIPTION           AS  \"chartOfAccountDescription\",
         FROM        CHARTOFACCOUNT
         JOIN        CHARTOFACCOUNTCATEGORY
         ON          CHARTOFACCOUNT.COMPANYID                   =   CHARTOFACCOUNTCATEGORY.COMPANYID
         AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID    =   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID
         JOIN        CHARTOFACCOUNTTYPE
         ON          CHARTOFACCOUNT.COMPANYID                   =   CHARTOFACCOUNTTYPE.COMPANYID
         AND         CHARTOFACCOUNT.CHARTOFACCOUNTTYPEID        =   CHARTOFACCOUNTTYPE.CHARTOFACCOUNTTYPEID
         AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID    =   CHARTOFACCOUNTTYPE.CHARTOFACCOUNTCATEGORYID
         WHERE       CHARTOFACCOUNT.ISACTIVE                    =   1
         AND         CHARTOFACCOUNT.COMPANYID                   =   '" . $this->getCompanyId() . "'
         AND    	 CHARTOFACCOUNT.CHARTOFACCOUNTID            =   '" . $chartOfAccountId . "'";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
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

            $this->setChartOfAccountId($row['chartOfAccountId']);
            $this->setChartOfAccountNumber($row['chartOfAccountNumber']);
            $this->setChartOfAccountDescription($row['chartOfAccountDescription']);
            $this->setChartOfAccountTypeId($row['chartOfAccountTypeId']);
            $this->setChartOfAccountTypeCode($row['chartOfAccountTypeCode']);
            $this->setChartOfAccountCategoryId($row['chartOfAccountCategoryId']);
            $this->setChartOfAccountCategoryCode($row['chartOfAccountCategoryCode']);
            $this->setChartOfAccountTypeDescription($row['chartOfAccountTypeDescription']);
            $this->setChartOfAccountCategoryDescription($row['chartOfAccountCategoryDescription']);
        }
    }

    /**
     * Return Chart Of Account
     * @return int
     */
    public function getChartOfAccountId() {
        return $this->chartOfAccountId;
    }

    /**
     * Return Chart Of Account
     * @param int $chartOfAccountId Chart Of Account Primary Key
     * @return $this
     */
    public function setChartOfAccountId($chartOfAccountId) {
        $this->chartOfAccountId = $chartOfAccountId;
        return $this;
    }

    /**
     * Set Business Partner Primary Key Information
     * @param int $businessPartnerId Business Partner Primary Key
     * @return void
     */
    private function setBusinessPartnerInformation($businessPartnerId) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `businessPartnerCompany`
         FROM        `businesspartner`
         WHERE       `isActive`         =   1
         AND         `companyId`        =   '" . $this->getCompanyId() . "'
         AND    	 `businessPartnerId`  =   '" . $businessPartnerId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [businessPartnerCompany]
         FROM        [businessPartner]
         WHERE       [isActive]         =   1
         AND         [companyId]        =   '" . $this->getCompanyId() . "'
         AND    	 [businessPartnerId]  =   '" . $businessPartnerId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
             SELECT      BUSINESSPARTNERCOMPANY AS \"businessPartnerCompany\"
             FROM        BUSINESSPARTNER
             WHERE       ISACTIVE         =   1
             AND         COMPANYID        =   '" . $this->getCompanyId() . "'
             and    	 BUSINESSPARTNERID  =   '" . $businessPartnerId . "'";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
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
            $this->setBusinessPartnerCompany($row['businessPartnerCompany']);
        }
    }

    /**
     * Create
     * @see config::create()
     */
    public function create() {
        
    }

    /**
     * Return Balance Budget. Might be Balance - Actual Transaction.
     * @param int $chartOfAccountId Chart Of Account Primary Key / Primary Key
     * @param null|int $financeYearId Financial Year E.g 1992 Or based On Date Ranged(future version)
     * @param null|int $financePeriodRangeId Financial Period E.g 1->January or Based On Date Range
     * @return float
     */
    public function getBalanceBudget($chartOfAccountId, $financeYearId = null, $financePeriodRangeId = null) {
        return floatval(
                $this->getBudgetAmount(
                        $chartOfAccountId, $financeYearId, $financePeriodRangeId
                ) - $this->getTransactionAmount($chartOfAccountId, $financeYearId, $financePeriodRangeId)
        );
    }

    /**
     * Return Actual Transaction Based On General Ledger
     * @param int $chartOfAccountId Chart Of Account Primary Key / Primary Key
     * @param null|int $financeYearId Financial Period E.g 1->January or Based On Date Range
     * @param null|int $financePeriodRangeId
     * @return float
     */
    public function getTransactionAmount($chartOfAccountId = 0, $financeYearId = null, $financePeriodRangeId = null) {

        $transactionAmount = floatval("0.00");
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT 	SUM(`localAmount`) AS `total`
			FROM	`generalledger`
			WHERE 	`companyId`			=	'" . $this->getCompanyId() . "'
			AND		`chartOfAccountId`	=	'" . $chartOfAccountId . "'";
            if ($financeYearId && $financePeriodRangeId) {
                $sql .= "
				AND	`financeYearId`			=	'" . $financeYearId . "'
				AND	`financePeriodRangeId`	=	'" . $financePeriodRangeId . "'";
            } else {
                $sql .= "
				AND	`financeYearId`			=	'" . $financeYearId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT 	SUM([localAmount]) AS [total]
			FROM	[generalLedger]
			WHERE 	[companyId]			=	'" . $this->getCompanyId() . "'
			AND		[chartOfAccountId]	=	'" . $chartOfAccountId . "'";
            if ($financeYearId && $financePeriodRangeId) {
                $sql .= "
				AND	[financeYearId]			=	'" . $financeYearId . "'
				AND	[financePeriodRangeId]	=	'" . $financePeriodRangeId . "'";
            } else {
                $sql .= "
				AND	[financeYearId]			=	'" . $financeYearId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT 	SUM([LOCALAMOUNT]) AS [TOTAL]
			FROM	GENERALLEDGER
			WHERE 	COMPANYID			=	'" . $this->getCompanyId() . "'
			AND		CHARTOFACCOUNTID	=	'" . $chartOfAccountId . "'";
            if ($financeYearId && $financePeriodRangeId) {
                $sql .= "
				AND	FINANCEYEARID				=	'" . $financeYearId . "'
				AND	FINANCEPERIODRANGEPERIOD	=	'" . $financePeriodRangeId . "'";
            } else {
                $sql .= "
				AND	FINANCEYEARYID				=	'" . $financeYearId . "'";
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $transactionAmount = $row['total'];
        }
        return floatval($transactionAmount);
    }

    /**
     * Return Budget Amount / value
     * @param int $chartOfAccountId Chart Of Account Primary Key / Primary Key
     * @param null|int $financeYearId Financial Year E.g 1992 Or based On Date Ranged(future version)
     * @param null|int $financePeriodRangeId Financial Period E.g 1->January or Based On Date Range
     * @param null|int $isLock Financial Lock
     * @return float $budgetAmount
     */
    public function getBudgetAmount(
    $chartOfAccountId, $financeYearId = null, $financePeriodRangeId = null, $isLock = null
    ) {
        $budgetAmount = 0;
        $sql = null;
        $fieldBudget = $this->getBudgetFieldName($this->getFinancePeriodRangePeriod());
        if ($this->getVendor() == self::MYSQL) {
            if ($financeYearId && $financePeriodRangeId) {
                $sql = "
				SELECT 	`" . strtolower($fieldBudget) . "` AS `total`
				FROM	`budget`
				JOIN	`chartofaccount`
				USING	(`companyId`,`chartOfAccountId`)
				WHERE 	`budget`.`companyId`			=	'" . $this->getCompanyId() . "'
				AND		`budget`.`financeYearId`	    =	'" . $financeYearId . "'
				AND		`budget`.`chartOfAccountId`		=	'" . $chartOfAccountId . "'
				";
            } else {
                $sql = "
				SELECT	(
							`budgetTargetMonthOne` +
							`budgetTargetMonthTwo` +
							`budgetTargetMonthThree` +
							`budgetTargetMonthFourth` +
							`budgetTargetMonthFifth` +
							`budgetTargetMonthSix` +
							`budgetTargetMonthSeven` +
							`budgetTargetMonthEight` +
							`budgetTargetMonthNine` +
							`budgetTargetMonthTen` +
							`budgetTargetMonthEleven` +
							`budgetTargetMonthTwelve` +
							`budgetTargetMonthThirteen` +
							`budgetTargetMonthFourteen` +
							`budgetTargetMonthFifteen` +
							`budgetTargetMonthSixteen` +
							`budgetTargetMonthSeventeen` +
							`budgetTargetMonthEighteen`
						) AS `total`
				FROM	`budget`
				JOIN	`financeyear`
				USING	(`companyId`,`financeYearId`)
				JOIN	`chartofaccount`
				USING	(`companyId`,`chartOfAccountId`)
				WHERE 	`budget`.`companyId`			=	'" . $this->getCompanyId() . "'
				AND		`budget`.`financeYearId`	    =	'" . $financeYearId . "'
				AND		`budget`.`chartOfAccountId`		=	'" . $chartOfAccountId . "'
				";
            }
            if ($isLock) {
                $sql .= " AND `budget`.`isLock` = 1 ";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            if ($financeYearId && $financePeriodRangeId) {
                $sql = "
				SELECT 	[" . strtolower($fieldBudget) . "] AS [total]
				FROM	[budget]
				JOIN	[financeYear]
				ON		[budget].[companyId] 			= 	[financeYear].[companyId]
				AND		[budget].[financeYearId] 		= 	[financeYear].[financeYearId]
				JOIN	[chartOfAccount]
				ON		[budget].[companyId]			= 	[chartOfAccount].[companyId]
				AND		[budget].[chartOfAccountId]		= 	[chartOfAccount].[chartOfAccountId]
				WHERE 	[budget].[companyId]			=	'" . $this->getCompanyId() . "'
				AND		[budget].[financeYearId]	    =	'" . $financeYearId . "'
				AND		[budget].[chartOfAccountId]		=	'" . $chartOfAccountId . "'
				";
            } else {
                $sql = "
				SELECT	(
							[budgetTargetMonthOne] +
							[budgetTargetMonthTwo] +
							[budgetTargetMonthThree] +
							[budgetTargetMonthFourth] +
							[budgetTargetMonthFifth] +
							[budgetTargetMonthSix] +
							[budgetTargetMonthSeven] +
							[budgetTargetMonthEight] +
							[budgetTargetMonthNine] +
							[budgetTargetMonthTen] +
							[budgetTargetMonthEleven] +
							[budgetTargetMonthTwelve] +
							[budgetTargetMonthThirteen] +
							[budgetTargetMonthFourteen] +
							[budgetTargetMonthFifteen] +
							[budgetTargetMonthSixteen] +
							[budgetTargetMonthSeventeen] +
						    [budgetTargetMonthEighteen]
						) AS [total]
				FROM	[budget]
				JOIN	[financeYear]
				ON		[budget].[companyId] 			= [financeYear].[companyId]
				AND		[budget].[financeYearId] 		= [financeYear].[financeYearId]
				JOIN	[chartOfAccount]
				ON		[budget].[companyId]			= 	[chartOfAccount].[companyId]
				AND		[budget].[chartOfAccountId]		= 	[chartOfAccount].[chartOfAccountId]
				WHERE 	[budget].[companyId]			=	'" . $this->getCompanyId() . "'
				AND		[budget].[financeYearId]	    =	'" . $financeYearId . "'
				AND		[budget].[chartOfAccountId]		=	'" . $chartOfAccountId . "'
				";
            }
            if ($isLock) {
                $sql .= " AND [budget].[isLock] = 1 ";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            if ($financeYearId && $financePeriodRangeId) {
                $sql = "
				SELECT 	" . strtoupper($fieldBudget) . " AS \"total\"
				FROM	BUDGET
				JOIN	FINANCEYEAR
				ON		BUDGET.COMPANYID 			= 	FINANCEYEAR.COMPANYID
				AND		BUDGET.FINANCEYEARID 		= 	FINANCEYEAR.FINANCEYEARID
				JOIN	CHARTOFACCOUNT
				ON		BUDGET.COMPANYID			= 	CHARTOFACCOUNT.COMPANYID
				AND		BUDGET.CHARTOFACCOUNTID		= 	CHARTOFACCOUNT.CHARTOFACCOUNTID
				WHERE 	BUDGET.COMPANYID			=	'" . $this->getCompanyId() . "'
				AND		BUDGET.FINANCEYEARID	    =	'" . $financeYearId . "'
				AND		BUDGET.CHARTOFACCOUNTID		=	'" . $chartOfAccountId . "'
				";
            } else {
                $sql = "
				SELECT	(
							BUDGETTARGETMONTHONE +
							BUDGETTARGETMONTHTWO +
							BUDGETTARGETMONTHTHREE +
							BUDGETTARGETMONTHFOURTH +
							BUDGETTARGETMONTHFIFTH +
							BUDGETTARGETMONTHSIX +
							BUDGETTARGETMONTHSEVEN +
							BUDGETTARGETMONTHEIGHT +
							BUDGETTARGETMONTHNINE +
							BUDGETTARGETMONTHTEN +
							BUDGETTARGETMONTHELEVEN +
							BUDGETTARGETMONTHTWELVE +
							BUDGETTARGETMONTHTHIRTEEN +
							BUDGETTARGETMONTHFOURTEEN +
							BUDGETTARGETMONTHFIFTEEN +
							BUDGETTARGETMONTHSIXTEEN +
							BUDGETTARGETMONTHSEVENTEEN +
						    BUDGETTARGETMONTHEIGHTEEN
						) AS TOTAL
				FROM	BUDGET
				JOIN	FINANCEYEAR
				ON		BUDGET.COMPANYID 			= 	FINANCEYEAR.COMPANYID
				AND		BUDGET.FINANCEYEARID 		= 	FINANCEYEAR.FINANCEYEARID
				JOIN	CHARTOFACCOUNT
				ON		BUDGET.COMPANYID			= 	CHARTOFACCOUNT.COMPANYID
				AND		BUDGET.CHARTOFACCOUNTID		= 	CHARTOFACCOUNT.CHARTOFACCOUNTID
				WHERE 	BUDGET.COMPANYID			=	'" . $this->getCompanyId() . "'
				AND		BUDGET.FINANCEYEARID	    =	'" . $financeYearId . "'
				AND		BUDGET.CHARTOFACCOUNTID		=	'" . $chartOfAccountId . "'
				";
            }
            if ($isLock) {
                $sql .= " AND BUDGET.ISLOCK = 1 ";
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $budgetAmount = $row['total'];
        }
        return $budgetAmount;
    }

    /**
     * Return Budget Field Name
     * @param int $period Period
     * @return string $fieldName Field name
     */
    public function getBudgetFieldName($period) {
        $fieldName = null;
        switch ($period) {
            case 1:
                $fieldName = "budgetTargetMonthOne";
                break;
            case 2:
                $fieldName = "budgetTargetMonthTwo";
                break;
            case 3:
                $fieldName = "budgetTargetMonthThree";
                break;
            case 4:
                $fieldName = "budgetTargetMonthFourth";
                break;
            case 5:
                $fieldName = "budgetTargetMonthFifth";
                break;
            case 6:
                $fieldName = "budgetTargetMonthSix";
                break;
            case 7:
                $fieldName = "budgetTargetMonthSeven";
                break;
            case 8:
                $fieldName = "budgetTargetMonthEight";
                break;
            case 9:
                $fieldName = "budgetTargetMonthNine";
                break;
            case 10:
                $fieldName = "budgetTargetMonthTen";
                break;
            case 11:
                $fieldName = "budgetTargetMonthEleven";
                break;
            case 12:
                $fieldName = "budgetTargetMonthTwelve";
                break;
            case 13:
                $fieldName = "budgetTargetMonthThirteen";
                break;
            case 14:
                $fieldName = "budgetTargetMonthFourteen";
                break;
            case 15:
                $fieldName = "budgetTargetMonthFifteen";
                break;
            case 16:
                $fieldName = "budgetTargetMonthSixteen";
                break;
            case 17:
                $fieldName = "budgetTargetMonthSeventeen";
                break;
            case 18:
                $fieldName = "budgetTargetMonthEighteen";
                break;
        }
        return $fieldName;
    }

    /**
     * Set Finance Year
     * @return int
     */
    public function getFinanceYearYear() {
        return $this->financeYearYear;
    }

    /**
     * Set Finance Year
     * @param int $financeYearYear Finance Year
     * @return $this
     */
    public function setFinanceYearYear($financeYearYear) {
        $this->financeYearYear = $financeYearYear;
        return $this;
    }

    /**
     * Set Finance Period Range
     * @return int
     */
    public function getFinancePeriodRangeId() {
        return $this->financePeriodRangeId;
    }

    /**
     * Set Finance Period Range
     * @param int $financePeriodRangeId Finance Period Range Primary Key
     * @return $this
     */
    public function setFinancePeriodRangeId($financePeriodRangeId) {
        $this->financePeriodRangeId = $financePeriodRangeId;
        return $this;
    }

    /**
     * Return Finance Period Range Period .E.g Date1 ~ Date2
     * @return int
     */
    public function getFinancePeriodRangePeriod() {
        return $this->financePeriodRangePeriod;
    }

    /**
     * Set Finance Period Range Period .E.g Date1 ~ Date2
     * @param int $financePeriodRangePeriod
     * @return $this
     */
    public function setFinancePeriodRangePeriod($financePeriodRangePeriod) {
        $this->financePeriodRangePeriod = $financePeriodRangePeriod;
        return $this;
    }

    /**
     * Return Chart Of Account Category.E.g Asset ,Liability,Equity,Income Expenses
     * @return int
     */
    public function getChartOfAccountCategoryId() {
        return $this->chartOfAccountCategoryId;
    }

    /**
     * Return Chart Of Account Category.E.g Asset,Liability,Equity,Income,Expenses
     * @param int $chartOfAccountCategoryId Chart Of Account Category Primary Key
     * @return $this
     */
    public function setChartOfAccountCategoryId($chartOfAccountCategoryId) {
        $this->chartOfAccountCategoryId = $chartOfAccountCategoryId;
        return $this;
    }

    /**
     * Set Chart Of Account Category Code
     * @return string
     */
    public function getChartOfAccountCategoryDescription() {
        return $this->chartOfAccountCategoryDescription;
    }

    /**
     * Set Chart Of Account Category Description
     * @param string $chartOfAccountCategoryDescription Chart Of Account Description
     * @return $this
     */
    public function setChartOfAccountCategoryDescription($chartOfAccountCategoryDescription) {
        $this->chartOfAccountCategoryDescription = $chartOfAccountCategoryDescription;
        return $this;
    }

    /**
     * Return Chart Of Account Type
     * @return int
     */
    public function getChartOfAccountTypeId() {
        return $this->chartOfAccountTypeId;
    }

    /**
     * Return Chart Of Account Type
     * @param int $chartOfAccountTypeId Chart Of Account Type Primary Key
     * @return $this
     */
    public function setChartOfAccountTypeId($chartOfAccountTypeId) {
        $this->chartOfAccountTypeId = $chartOfAccountTypeId;
        return $this;
    }

    /**
     * Return Chart Of Account Type E.g Current Asset,Other Asset
     * @return string
     */
    public function getChartOfAccountTypeDescription() {
        return $this->chartOfAccountTypeDescription;
    }

    /**
     * Return Chart Of Account Type Description
     * @param string $chartOfAccountTypeDescription Description
     * @return $this
     */
    public function setChartOfAccountTypeDescription($chartOfAccountTypeDescription) {
        $this->chartOfAccountTypeDescription = $chartOfAccountTypeDescription;
        return $this;
    }

    /**
     * Return Chart Of Account Number
     * @return string
     */
    public function getChartOfAccountNumber() {
        return $this->chartOfAccountNumber;
    }

    /**
     * Set Chart Of Account Number
     * @param string $chartOfAccountNumber Number
     * @return $this
     */
    public function setChartOfAccountNumber($chartOfAccountNumber) {
        $this->chartOfAccountNumber = $chartOfAccountNumber;
        return $this;
    }

    /**
     * Return Chart Of Account Description
     * @return string
     */
    public function getChartOfAccountDescription() {
        return $this->chartOfAccountDescription;
    }

    /**
     * Return Chart Of Account Description
     * @param string $chartOfAccountDescription Description
     * @return $this
     */
    public function setChartOfAccountDescription($chartOfAccountDescription) {
        $this->chartOfAccountDescription = $chartOfAccountDescription;
        return $this;
    }

    /**
     * Return Business Partner Company Name
     * @return string
     */
    public function getBusinessPartnerCompany() {
        return $this->businessPartnerCompany;
    }

    /**
     * Return Business Partner Company Name
     * @param string $businessPartnerCompany
     * @return $this
     */
    public function setBusinessPartnerCompany($businessPartnerCompany) {
        $this->businessPartnerCompany = $businessPartnerCompany;
        return $this;
    }

    /**
     * Return Chart Of Account Category Code.E.g Asset->A,Liability->L,Equity->OE,Income->I,Expenses->E
     * @return string
     */
    public function getChartOfAccountCategoryCode() {
        return $this->chartOfAccountCategoryCode;
    }

    /**
     * Set Chart Of Account Category Code
     * @param string $chartOfAccountCategoryCode Code
     * @return $this
     */
    public function setChartOfAccountCategoryCode($chartOfAccountCategoryCode) {
        $this->chartOfAccountCategoryCode = $chartOfAccountCategoryCode;
        return $this;
    }

    /**
     * Return Chart Of Account Type Code
     * @return string
     */
    public function getChartOfAccountTypeCode() {
        return $this->chartOfAccountTypeCode;
    }

    /**
     * Return Chart Of Account Type E.g Current Asset,Other Asset
     * @param string $chartOfAccountTypeCode Code
     * @return $this
     */
    public function setChartOfAccountTypeCode($chartOfAccountTypeCode) {
        $this->chartOfAccountTypeCode = $chartOfAccountTypeCode;
        return $this;
    }

    /**
     * Return Transaction Type Primary Key
     * @return int
     */
    public function getTransactionTypeId() {
        return $this->transactionTypeId;
    }

    /**
     * Set Transaction Type Primary Key
     * @param int $transactionTypeId Transaction Type Primary Key
     * @return $this
     */
    public function setTransactionTypeId($transactionTypeId) {
        $this->transactionTypeId = $transactionTypeId;
        return $this;
    }

    /**
     * Return Transaction Type Description
     * @return string
     */
    public function getTransactionTypeDescription() {
        return $this->transactionTypeDescription;
    }

    /**
     * Return Transaction Type Description
     * @param string $transactionTypeDescription Transaction Type Description
     * @return $this
     */
    public function setTransactionTypeDescription($transactionTypeDescription) {
        $this->transactionTypeDescription = $transactionTypeDescription;
        return $this;
    }

    /**
     * Return Transaction Type Code
     * @return string
     */
    public function getTransactionTypeCode() {
        return $this->transactionTypeCode;
    }

    /**
     * Set Transaction Type Code
     * @param string $transactionTypeCode Transaction Type Code
     * @return $this
     */
    public function setTransactionTypeCode($transactionTypeCode) {
        $this->transactionTypeCode = $transactionTypeCode;
        return $this;
    }

    /**
     * Read
     * @see config::read()
     */
    public function read() {
        
    }

    /**
     * Update
     * @see config::update()
     */
    public function update() {
        
    }

    /**
     * Delete
     * @see config::delete()
     */
    public function delete() {
        
    }

    /**
     * Reporting
     * @see config::excel()
     */
    public function excel() {
        
    }

}

?>