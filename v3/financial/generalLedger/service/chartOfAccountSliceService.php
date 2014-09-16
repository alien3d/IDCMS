<?php

namespace Core\Financial\GeneralLedger\ChartOfAccountSlice\Service;

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
 * Class ChartOfAccountSliceService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\GeneralLedger\ChartOfAccountSlice\Service
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ChartOfAccountSliceService extends ConfigClass {

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
     * Return Chart Of Account
     * @return array|string
     */
    public function getChartOfAccount() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `chartofaccount`.`chartOfAccountId`,
					 `chartofaccount`.`chartOfAccountNumber`,
                     `chartofaccount`.`chartOfAccountTitle`,
                     `chartofaccounttype`.`chartOfAccountTypeDescription`
         FROM        `chartofaccount`
         JOIN        `chartofaccounttype`
         USING       (`companyId`,`chartOfAccountTypeId`)
         WHERE       `chartofaccount`.`isActive`  =   1
         AND         `chartofaccount`.`companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `chartofaccounttype`.`chartOfAccountTypeId`,
                     `chartofaccount`.`chartOfAccountNumber`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [chartOfAccount].[chartOfAccountId],
					 [chartOfAccount].[chartOfAccountNumber],
                     [chartOfAccount].[chartOfAccountTitle],
                     [chartOfAccountType].[chartOfAccountTypeDescription]
         FROM        [chartOfAccount]
         ON          [chartOfAccount].[companyId]   = [chartOfAccountType].[companyId]
         AND         [chartOfAccount].[chartOfAccountTypeId]   = [chartOfAccountType].[chartOfAccountTypeId]
         WHERE       [chartOfAccount].[isActive]  =   1
         AND         [chartOfAccount].[companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [chartOfAccount].[chartOfAccountNumber]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      CHARTOFACCOUNTID               AS  \"chartOfAccountId\",
					 CHARTOFACCOUNTNUMBER           AS  \"chartOfAccountNumber\",
                     CHARTOFACCOUNTTITLE            AS  \"chartOfAccountTitle\",
                     CHARTOFACCOUNTTYPEDESCRIPTION  AS  \"chartOfAccountTypeDescription\"
         FROM        CHARTOFACCOUNT
         JOIN        CHARTOFACCOUNTTYPE
         ON          CHARTOFACCOUNT.COMPANYID               =   CHARTOFACCOUNTTYPE.COMPANYID
         AND         CHARTOFACCOUNT.CHARTOFACCOUNTTYPEID    =   CHARTOFACCOUNTTYPE.CHARTOFACCOUNTTYPEID
         WHERE       CHARTOFACCOUNT.ISACTIVE                =   1
         AND         CHARTOFACCOUNT.COMPANYID               =   '" . $this->getCompanyId() . "'
         ORDER BY    CHARTOFACCOUNT.CHARTOFACCOUNTNUMBER";
                } else {
                    header('Content-Type:application/json; charset=utf-8');
                    echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
                    exit();
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['chartOfAccountId'] . "'>" . $row['chartOfAccountNumber'] . ". " . $row['chartOfAccountTitle'] . "</option>";
                } else {
                    if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
                    }
                }
                $d++;
            }
            unset($d);
        }
        if ($this->getServiceOutput() == 'option') {
            if (strlen($str) > 0) {
                $str = "<option value=''>" . $this->t['pleaseSelectTextLabel'] . "</option>" . $str;
            } else {
                $str = "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
            }
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => true, "message" => "complete", "data" => $str));
            exit();
        } else {
            if ($this->getServiceOutput() == 'html') {
                // return $items;
            }
        }
    }

    /**
     * Return ChartOfAccount Default Value
     * @return int
     */
    public function getChartOfAccountDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $chartOfAccountId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `chartOfAccountId`
         FROM        	`chartofaccount`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [chartOfAccountId],
         FROM        [chartOfAccount]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      CHARTOFACCOUNTID AS \"chartOfAccountId\",
         FROM        CHARTOFACCOUNT
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         AND    	  ISDEFAULT	  =	   1
         AND 		  ROWNUM	  =	   1";
                } else {
                    header('Content-Type:application/json; charset=utf-8');
                    echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
                    exit();
                }
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
            $chartOfAccountId = $row['chartOfAccountId'];
        }
        return $chartOfAccountId;
    }

    /**
     * Return Total Chart Of Account Figure
     * @param int $chartOfAccountId
     * @return void
     */
    public function getChartOfAccountTotalTransaction($chartOfAccountId) {
        header('Content-Type:application/json; charset=utf-8');
        $start = microtime(true);
        $totalFigure = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT SUM(	`localAmount`) as `totalFigure`
			FROM		`generalledger`
			WHERE		`companyId`         =	'" . $this->getCompanyId() . "'
			AND			`chartOfAccountId`	=   '" . $chartOfAccountId . "'
			AND         `financeYearId`     =   '" . $this->getFinanceYearId() . "'
			";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT SUM(	[localAmount]) AS [totalFigure]
			FROM		[generalLedger]
			WHERE		[companyId]			=	'" . $this->getCompanyId() . "'
			AND			[chartOfAccountId]	=	'" . $chartOfAccountId . "'
			AND         [financeYearId]     =   '" . $this->getFinanceYearId() . "'
			GROUP BY	[localAmount]
			";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT SUM(	LOCALAMOUNT) as \"totalFigure\"
			FROM		GENERALLEDGER
			WHERE		COMPANYID			=	'" . $this->getCompanyId() . "'
			AND			CHARTOFACCOUNTID	=	'" . $chartOfAccountId . "'
			AND         FINANCEYEARID       =   '" . $this->getFinanceYearId() . "'
			GROUP BY	LOCALAMOUNT
			";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $totalFigure = floatval($row['totalFigure']);
        }
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => true,
                    "totalFigure" => $totalFigure,
                    "time" => $time
                )
        );
        exit();
    }

    /**
     * Transfer Chart Of Account Slice To Journal Entry. The reason is much easier to auto reverse and everybody see
     * @param int $chartOfAccountSliceId Chart Of Account Slice Primary Key
     */
    function setTransferJournal($chartOfAccountSliceId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            
        } else
        if ($this->getVendor() == self::MSSQL) {
            
        } else
        if ($this->getVendor() == self::ORACLE) {
            
        }

        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Post Chart Of Account Slice To General Ledger
     * @param int $chartOfAccountSliceId Chart Of Account Slice Primry Key
     * @param int $leafId Leaf Primary Key
     * @param string $leafName Leaf Name
     *
     */
    public function setPosting($chartAccountSliceId, $leafId, $leafName) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  *
            FROM    `chartofaccountslice`
            WHERE   `invoiceDebitNoteId` IN (" . $chartAccountSliceId . ")
			AND		`isActive`= 	1
            AND     `isPost`    =   0
            AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  *
            FROM    [chartAccountSliceId]
            WHERE   [chartAccountSliceId] IN (" . $chartAccountSliceId . ")
			AND		[isActive]= 	1
            AND     [isPost] =0
            AND     [companyId] =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  *
            FROM    INVOICEDEBITNOTE
            WHERE   INVOICEDEBITNOTEID IN (" . $chartAccountSliceId . ")
			AND		ISACTIVE= 	1
            AND     ISPOST      =  0
            AND     COMPANYID   =   '" . $this->getCompanyId() . "'";
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
            while (($row = $this->q->fetchArray($result)) == true) {
                $invoiceDebitNoteId = $row['invoiceDebitNoteId'];
                $invoiceId = $row['invoiceId'];
                $this->setInvoiceStatusTracking(
                        $invoiceDebitNoteId, $invoiceId, $this->getInvoiceStatusId(self::TRANSFER_TO_GENERAL_LEDGER)
                );
            }
        }
        $journalNumber = $this->getDocumentNumber('GLPT');
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  *
            FROM    `invoicedebitnotedetail`
            JOIN    `invoiceDebitNote`
            USING   (`companyId`,`invoiceId`)
            WHERE   `invoiceDebitNote`.`invoiceDebitNoteId` IN (" . $invoiceDebitNoteId . ")
            ORDER BY `invoiceId";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  *
            FROM    [invoiceDebitNoteDetail]
            JOIN    [invoiceDebitNote]
            ON      [invoiceDebitNoteDetail].[companyId]         =   [invoiceDebitNote].[companyId]
            AND     [invoiceDebitNoteDetail].[invoiceId] =   [invoiceDebitNote].[invoiceDebitNoteId]
            WHERE   [invoiceDebitNoteId] IN (" . $invoiceDebitNoteId . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  *
            FROM    INVOICEDEBITNOTEDETAIL
            JOIN    INVOICEDEBITNOTE
            ON      INVOICEDEBITNOTEDETAIL.COMPANYID         =   INVOICEDEBITNOTE.COMPANYID
            AND     INVOICEDEBITNOTEDETAIL.INVOICEID =   INVOICEDEBITNOTE.INVOICEDEBITNOTEID
            WHERE   INVOICEDEBITNOTE.INVOICEID IN (" . $invoiceDebitNoteId . ")";
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
            while (($row = $this->q->fetchArray($result)) == true) {
                $invoiceId = $row['invoiceId'];
                $invoiceDebitNoteId = $row['invoiceDebitNoteId'];
                $businessPartnerId = $row['businessPartnerId'];
                $chartOfAccountId = $row['chartOfAccountId'];
                $documentNumber = $row['documentNumber'];
                $documentDate = $row['collectionDate'];
                $localAmount = $row['collectionDetailAmount'];
                $description = $row['collectionDescription'];
                $module = 'CB';

                $tableName = 'collection';
                $tableNameDetail = 'collectionDetail';
                $tableNameId = 'collectionId';
                $tableNameDetailId = 'collectionDetailId';

                $invoiceDueDate = null;
                $invoiceDebitNoteId = $row['invoiceDebitNoteId'];
                $referenceTableNameId = $row['collectionId'];
                $referenceTableNameDetailId = $row['collectionDetailId'];

                // null value
                $invoiceAdjustmentId = null;
                $invoiceCreditNoteId = null;
                $invoiceProjectId = null;
                $collectionId = null;

                $this->ledgerService->setGeneralLedger($leafId, $leafName, $businessPartnerId, $chartOfAccountId, $documentNumber, $journalNumber, $documentDate, $localAmount, $description, $module, $tableName, $tableNameDetail, $tableNameId, $tableNameDetailId, $referenceTableNameId, $referenceTableNameDetailId);
            }
        }
        // make second batch for detail.. no more loop in loop
        $this->setChartOfAccountSlicePosted($invoiceDebitNoteId);
    }

    /**
     * Update Main Table Chart Of AccountId
     * @param int $chartOfAccountId
     * @return void
     */
    private function updateChartOfAccount($chartOfAccountId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			UPDATE	`chartofaccount`
			SET 	`isActive`			=	0
					`isSlice`			=	1,
					`executeBy`			=	'" . $this->getStaffId() . "',
					`executeTime`		=	" . $this->getExecuteTime() . "
			WHERE	`chartOfAccountId`	=	'" . $chartOfAccountId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			UPDATE	[chartofaccount]
			SET 	[isActive]			=	0
					[isSlice]			=	1,
					[executeBy]			=	'" . $this->getStaffId() . "',
					[executeTime]		=	" . $this->getExecuteTime() . "
			WHERE	[chartOfAccountId]	=	'" . $chartOfAccountId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			UPDATE	CHARTOFACCOUNT
			SET 	ISACTIVE			=	0
					ISSLICE				=	1,
					EXECUTEBY			=	'" . $this->getStaffId() . "',
					EXECUTETIME			=   " . $this->getExecuteTime() . "
			WHERE	CHARTOFACCOUNTID	=	'" . $chartOfAccountId . "'";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Update Chart Of Account
     * @param int $chartOfAccountSliceId
     * @return void
     */
    private function updateChartOfAccountSliceTable($chartOfAccountSliceId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			UPDATE	`chartofaccountslice`
			SET 	`isPost`				=	1,
					`executeBy`				=	'" . $this->getStaffId() . "',
					`executeTime`			=	" . $this->getExecuteTime() . "
			WHERE	`chartOfAccountSliceId`	=	'" . $chartOfAccountSliceId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			UPDATE	[chartOfAccountSlice]
			SET 	[isPost]				=	1,
					[executeBy]				=	'" . $this->getStaffId() . "',
					[executeTime]			=	" . $this->getExecuteTime() . "
			WHERE	[chartOfAccountSliceId]	=	'" . $chartOfAccountSliceId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			UPDATE	CHARTOFACCOUNTSLICE
			SET 	ISPOST					=	1,
					EXECUTEBY				=	'" . $this->getStaffId() . "',
					EXECUTETIME				=   " . $this->getExecuteTime() . "
			WHERE	CHARTOFACCOUNTSLICEID	=	'" . $chartOfAccountSliceId . "'";
        }
        try {
            $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Update General Ledger The Transaction Which have been Slice /Split deactivated
     * @param int $chartOfAccountId
     * @return void
     */
    private function setChartOfAccountSliceStatusToGeneralLedger($chartOfAccountId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			UPDATE	`generalledger`
			SET 	`isActive`			=	0,
					`isSlice`			=	1
			WHERE	`chartOfAccountId`	=	'" . $chartOfAccountId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			UPDATE	[generalLedger]
			SET 	[isActive]			=	0,
					[isSlice]			=	1
			WHERE	[chartOfAccountId]	=	'" . $chartOfAccountId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			UPDATE	GENERALLEDGER
			SET 	ISACTIVE			=	0,
					ISSLICE				=	1,
					EXECUTEBY			=	'" . $this->getStaffId() . "',
					EXECUTETIME			=   " . $this->getExecuteTime() . "
			WHERE	CHARTOFACCOUNTID	=	'" . $chartOfAccountId . "'";
        }
        try {
            $this->q->fast($sql);
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

    /**
     * Update Journal Number
     * @param string $documentNumber
     * @param string $journalNumber
     */
    private function updateJournal($documentNumber, $journalNumber) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			UPDATE	`chartofaccountslice` SET ";
        } else if ($this->getVendor() == self::MSSQL) {
            
        } else if ($this->getVendor() == self::ORACLE) {
            
        }
        try {
            $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

}

?>