<?php

namespace Core\Financial\GeneralLedger\ChartOfAccountMerge\Service;

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
 * Class ChartOfAccountMergeService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\GeneralLedger\ChartOfAccountMerge\Service
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ChartOfAccountMergeService extends ConfigClass {

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
        } else if ($this->getVendor() == self::MSSQL) {
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
        } else if ($this->getVendor() == self::ORACLE) {
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
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 0;
            $chartOfAccountTypeDescription = null;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($d != 0) {
                    if ($chartOfAccountTypeDescription != $row['chartOfAccountTypeDescription']) {
                        $str .= "</optgroup><optgroup label=\"" . $row['chartOfAccountTypeDescription'] . "\">";
                    }
                } else {
                    $str .= "<optgroup label=\"" . $row['chartOfAccountTypeDescription'] . "\">";
                }
                $chartOfAccountTypeDescription = $row['chartOfAccountTypeDescription'];

                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['chartOfAccountId'] . "'>" . $row['chartOfAccountNumber'] . " -  " . $row['chartOfAccountTitle'] . "</option>";
                } else {
                    if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
                    }
                }
                $d++;
            }
            $str .= "</optgroup>";
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
                return $items;
            }
        }
        // fake return
        return $items;
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
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [chartOfAccountId],
         FROM        [chartOfAccount]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
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
     * Post Chart Of Account Merge
     * @param int|string $chartOfAccountId Chart Of Account Primary Key
     */
    public function postChartOfAccountMerge($chartOfAccountId) {
        $sql = null;
        // check is balance first.User seem green but trial balance is wrong
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT	`isBalance`
			FROM	`chartofaccountmerge`
			WHERE	`chartOfAccountMergeId` IN (" . $chartOfAccountId . ")";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT	[isBalance`]
			FROM	[chartOfAccountMerge]
			WHERE	[chartOfAccountMergeId] IN (" . $chartOfAccountId . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT	ISBALANCE AS \"isBalance\"
			FROM	CHARTOFACCOUNTMERGE
			WHERE	CHARTOFACCOUNTMERGEID IN (" . $chartOfAccountId . ")";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        // get document number for journal
        $error = 0;
        if ($result) {
            while (($row = $this->q->fetchArray($result)) == true) {
                $isBalance = $row['isBalance'];
                $chartOfAccountId = $row['chartOfAccountId'];
                $chartOfAccountMergeId = $row['chartOfAccountMergeId'];

                if ($isBalance == 0) {
                    $error = 1;
                    break;
                } else {
                    // update chart of account
                    $this->updateChartOfAccount($chartOfAccountId);
                    // deActived the chosen account
                    $this->updateChartOfAccountMergeTable($chartOfAccountMergeId);
                    // update general ledger . only update current year transaction only.
                    $this->updateGeneralLedger($chartOfAccountId);
                }
            }
        }
        // just to check if any other user touch and save the document. might not the owner creating the document
        if ($error == 1) {
            echo json_encode(array("success" => false, "message" => $this->t['unBalanceDetail']));
            exit();
        }

        // transfer to journal
        if ($this->getVendor() == self::MYSQL) {
            $sql = "";
        } else if ($this->getVendor() == self::MSSQL) {
            
        } else if ($this->getVendor() == self::ORACLE) {
            
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            while (($row = $this->q->fetchArray($result)) == true) {
                $isBalance = $row['isBalance'];
                if ($isBalance == 0) {
                    $error = 1;
                    break;
                }
            }
        }

        // loop the detail  and transfer to journal detail and general ledger
        echo json_encode(array("success" => true, "message" => $this->t['completeSlicingTextLabel']));
        exit();
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
					`isMerge`			=	1,
					`executeBy`			=	'" . $this->getStaffId() . "',
					`executeTime`		=	" . $this->getExecuteTime() . "
			WHERE	`chartOfAccountId`	=	'" . $chartOfAccountId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			UPDATE	[chartofaccount]
			SET 	[isActive]			=	0
					[isMerge]			=	1,
					[executeBy]			=	'" . $this->getStaffId() . "',
					[executeTime]		=	" . $this->getExecuteTime() . "
			WHERE	[chartOfAccountId]	=	'" . $chartOfAccountId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			UPDATE	CHARTOFACCOUNT
			SET 	ISACTIVE			=	0
					ISMERGE				=	1,
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
     * @param int $chartOfAccountMergeId
     * @return void
     */
    private function updateChartOfAccountMergeTable($chartOfAccountMergeId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			UPDATE	`chartofaccountmerge`
			SET 	`isPost`				=	1,
					`executeBy`			=	'" . $this->getStaffId() . "',
					`executeTime`		=	" . $this->getExecuteTime() . "
			WHERE	`chartOfAccountMergeId`	=	'" . $chartOfAccountMergeId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			UPDATE	[chartOfAccountMerge]
			SET 	[isPost]				=	1,
					[executeBy]			=	'" . $this->getStaffId() . "',
					[executeTime]		=	" . $this->getExecuteTime() . "
			WHERE	[chartOfAccountMergeId]	=	'" . $chartOfAccountMergeId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			UPDATE	CHARTOFACCOUNTMERGE
			SET 	ISPOST					=	1,
					EXECUTEBY			=	'" . $this->getStaffId() . "',
					EXECUTETIME			=   " . $this->getExecuteTime() . "
			WHERE	CHARTOFACCOUNTMERGEID	=	'" . $chartOfAccountMergeId . "'";
        }
        try {
            $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Update General Ledger The Transaction Which have been Merge /Split deactivated
     * @param int $chartOfAccountId Chart Of Account Primary Key
     * @return void
     */
    private function setChartOfAccountMergeStatusToGeneralLedger($chartOfAccountId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			UPDATE	`generalledger`
			SET 	`isActive`			=	0,
					`isMerge`			=	1
			WHERE	`chartOfAccountId`	IN	(" . $chartOfAccountId . ")";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			UPDATE	[generalLedger]
			SET 	[isActive]			=	0,
					[isMerge]			=	1
			WHERE	[chartOfAccountId]	IN	(" . $chartOfAccountId . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			UPDATE	GENERALLEDGER
			SET 	ISACTIVE			=	0,
					ISMERGE				=	1,
					EXECUTEBY			=	'" . $this->getStaffId() . "',
					EXECUTETIME			=   " . $this->getExecuteTime() . "
			WHERE	CHARTOFACCOUNTID	IN	(" . $chartOfAccountId . ")";
        }
        try {
            $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     *  Create
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