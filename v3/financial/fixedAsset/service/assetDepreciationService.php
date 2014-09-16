<?php

namespace Core\Financial\FixedAsset\AssetDepreciation\Service;

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
 * Class AssetDepreciationService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\FixedAsset\AssetDepreciation\Service
 * @subpackage FixedAsset
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class AssetDepreciationService extends ConfigClass {

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
     * Return ItemCategory
     * @return array|string
     */
    public function getItemCategory() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `itemCategoryId`,
                     `itemCategoryDescription`
         FROM        `itemcategory`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [itemCategoryId],
                     [itemCategoryDescription]
         FROM        [itemCategory]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      ITEMCATEGORYID AS \"itemCategoryId\",
                     ITEMCATEGORYDESCRIPTION AS \"itemCategoryDescription\"
         FROM        ITEMCATEGORY
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         ORDER BY    ISDEFAULT";
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
                    $str .= "<option value='" . $row['itemCategoryId'] . "'>" . $d . ". " . $row['itemCategoryDescription'] . "</option>";
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
                return $items;
            }
        }
        // fake return
        return $items;
    }

    /**
     * Return ItemCategory Default Value
     * @return int
     */
    public function getItemCategoryDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $itemCategoryId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `itemCategoryId`
         FROM        	`itemcategory`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [itemCategoryId],
         FROM        [itemCategory]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      ITEMCATEGORYID AS \"itemCategoryId\",
         FROM        ITEMCATEGORY
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
            $itemCategoryId = $row['itemCategoryId'];
        }
        return $itemCategoryId;
    }

    /**
     * Return AssetDepreciationTime
     * @return array|string
     */
    public function getAssetDepreciationTime() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `assetDepreciationTimeId`,
                     `assetDepreciationTimeDescription`
         FROM        `assetdepreciationtime`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [assetDepreciationTimeId],
                     [assetDepreciationTimeDescription]
         FROM        [assetDepreciationTime]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      ASSETDEPRECIATIONTIMEID AS \"assetDepreciationTimeId\",
                     ASSETDEPRECIATIONTIMEDESCRIPTION AS \"assetDepreciationTimeDescription\"
         FROM        ASSETDEPRECIATIONTIME
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         ORDER BY    ISDEFAULT";
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
                    $str .= "<option value='" . $row['assetDepreciationTimeId'] . "'>" . $d . ". " . $row['assetDepreciationTimeDescription'] . "</option>";
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
                return $items;
            }
        }
        // fake return
        return $items;
    }

    /**
     * Return AssetDepreciationTime Default Value
     * @return int
     */
    public function getAssetDepreciationTimeDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $assetDepreciationTimeId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `assetDepreciationTimeId`
         FROM        	`assetdepreciationtime`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [assetDepreciationTimeId],
         FROM        [assetDepreciationTime]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      ASSETDEPRECIATIONTIMEID AS \"assetDepreciationTimeId\",
         FROM        ASSETDEPRECIATIONTIME
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
            $assetDepreciationTimeId = $row['assetDepreciationTimeId'];
        }
        return $assetDepreciationTimeId;
    }

    /**
     * Posting Disposal
     * @param string|int $rangeId
     * @param int $leafId Leaf Primary key
     * @param string $leafName Leaf Name
     * @return void
     */
    function setDisposal($rangeId, $leafId, $leafName) {
        $sql = null;
        $assetSpecificationAccumulativeDepreciationAccounts = null;
        $assetSpecificationDepreciationAccounts = null;
        $journalNumber = $this->getDocumentNumber('GLPT');
        //$rangeAssetId=0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT * 
			FROM `assetspecification` 
			WHERE `companyId`	=	'" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT * 
			FROM 	[assetSpecification]
			WHERE	[companyId]	=	'" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT ASSETSPECIFICATIONCOSTACCOUNTS  									AS 	\"assetSpecificationCostAccounts\", 
						ASSETSPECIFICATIONACCUMULATIVEDEPRECIATIONACCOUNTS	AS	\"assetSpecificationAccumulativeDepreciationAccounts\", 
						ASSETSPECIFICATIONWRITEOFFACCOUNTS 								AS	\"assetSpecificationWriteOffAccounts\", 
						ASSETSPECIFICATIONDEPRECIATIONACCOUNTS  						AS	\"assetSpecificationDepreciationAccounts\", 
						ASSETSPECIFICATIONREVALUATIONACCOUNTS 							AS	\"assetSpecificationRevaluationAccounts\", 
						ASSETSPECIFICATIONGAINANDLOSSACCOUNTS 						AS	\"assetSpecificationGainAndLossAccounts\",
						ASSETSPECIFICATIONCLEARINGACCOUNTS 								AS	\"assetSpecificationClearingAccounts\", 
						ASSETSPECIFICATIONNOMINALVALUE 										AS	\"assetSpecificationNominalValue\", 
						ASSETSPECIFICATIONMINIMUMREORDER 									AS	\"assetSpecificationMinimumReOrder\"
			FROM 	ASSETSPECIFICATION 
			WHERE  COMPANYID='" . $this->getCompanyId() . "";
        }

        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $total = $this->q->numberRows($result);
        if ($total > 0) {
            $row = $this->q->fetchArray($result);
            // $assetSpecificationCostAccounts = $row['assetSpecificationCostAccounts'];
            $assetSpecificationAccumulativeDepreciationAccounts = $row['assetSpecificationAccumulativeDepreciationAccounts'];
            // $assetSpecificationWriteOffAccounts = $row['assetSpecificationWriteOffAccounts'];
            $assetSpecificationDepreciationAccounts = $row['assetSpecificationDepreciationAccounts'];
            //$assetSpecificationGainAndLossAccounts = $row['assetSpecificationGainAndLossAccounts'];
            //$assetSpecificationClearingAccounts = $row['assetSpecificationClearingAccounts'];
            //$assetSpecificationNominalValue = $row['assetSpecificationNominalValue'];
            //$assetSpecificationMinimumReOrder = $row['assetSpecificationMinimumReOrder'];
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			UPDATE	`assetdepreciation`
			SET			`isPost`= 1
			WHERE		`assetDepreciationId`	IN	 (" . $rangeId . ")";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			UPDATE	[assetDepreciation]
			SET			[isPost]= 1
			WHERE		[assetDepreciationId]	IN	 (" . $rangeId . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			UPDATE	ASSETDEPRECIATION
			SET			ISPOST= 1
			WHERE		DEPRECIATIONID	IN	 (" . $rangeId . ")";
        }

        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        // only filter which valid to depreciate only. this to avoid mistaken depreciate such as land,building.land and building more on revaluation/adjustment net book value.
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT *
			FROM	`assetdepreciation`
			JOIN		`asset`
			USING  (`companyId`,`itemCategoryId`,`assetId`)
			WHERE	`assetDisposalId` IN (" . $rangeId . ")
			AND		`isDepreciate`  = 1 ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT *
			FROM	[assetDepreciation]
			JOIN		[asset]
			ON		[assetDepreciation].[companyId] = [asset].[companyId]
			AND		[assetDepreciation].[itemCategoryId] = [asset].[itemCategoryId]
			AND		[assetDepreciation].[assetId] = [asset].[assetId]
			WHERE	[assetDepreciation] IN (" . $rangeId . ")
			AND		[isDepreciate]  = 1 ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT  ASSET.ASSETID AS \"assetId\",
						 ASSET.ASSETACCUMULATEDEPRECIATE AS \"assetAccumulateDepreciate\",
						 ASSET.ASSETNETBOOKVALUE AS \"assetNetBookValue\",
						 ASSET.ASSETDEPRECIATIONYEAR AS \"assetDepreciationYear\",
						 ASSET.ASSETDEPRECIATIONPERIOD AS \"assetDepreciationPeriod\",
			            ASSET.BUSINESSPARTNERID AS \"businessPartnerId\",
						ASSETDEPRECIATION.DOCUMENTNUMBER  AS \"documentNumber\",
						ASSETDEPRECIATION.ASSETDEPRECIATIONDATE AS \"assetDepreciationDate\",
						ASSETDEPRECIATION.ASSETDEPRECIATIONYEAR AS \"assetDepreciationYear\",
						ASSETDEPRECIATION.ASSETDEPRECIATIONPERIOD AS \"assetDepreciationPeriod\",
						ASSETDEPRECIATION.ASSETDEPRECIATIONMONTHTODATE AS \"assetDepreciationMonthToDate\",
						ASSETDEPRECIATION.ASSETDISPOSALDESCRIPTION	 AS \"assetDepreciationDescription\"
			FROM	ASSETDEPRECIATION
			JOIN		ASSET
			ON		ASSETDEPRECIATION.COMPANYID = ASSET.COMPANYID
			AND		ASSETDEPRECIATION.ITEMCATEGORYID= ASSET.ITEMCATEGORYID
			AND		ASSETDEPRECIATION.ITEMTYPEID = ASSET.ITEMTYPEID
			AND		ASSETDEPRECIATION.ASSETID = ASSET.ASSETID
			WHERE	ASSETASSETDEPRECIATIONID IN (" . $rangeId . ")
			AND		ISDEPRECIATE] = 1";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        while (($rowAsset = $this->q->fetchArray($result)) == TRUE) {
            $businessPartnerId = $rowAsset['businessPartnerId'];
            $documentNumber = $rowAsset['documentNumber'];
            $documentDate = $rowAsset['assetDepreciationDate'];
            $description = $rowAsset['assetDepreciationDescription'];


            $module = 'AS';
            $tableName = 'assetDepreciation';
            $tableNameDetail = 'assetDepreciationDetail';
            $tableNameId = 'assetDepreciationId';
            $tableNameDetailId = 'assetDepreciationDetailId';
            $referenceTableNameId = $row['assetDepreciationId'];
            $referenceTableNameDetailId = $row['assetDepreciationDetailId'];

            $chartOfAccountId = $assetSpecificationAccumulativeDepreciationAccounts;
            $localAmount = $rowAsset['assetDepreciationMonthToDate'];
            $this->ledgerService->setGeneralLedger($leafId, $leafName, $businessPartnerId, $chartOfAccountId, $documentNumber, $journalNumber, $documentDate, $localAmount, $description, $module, $tableName, $tableNameDetail, $tableNameId, $tableNameDetailId, $referenceTableNameId, $referenceTableNameDetailId);

            $chartOfAccountId = $assetSpecificationDepreciationAccounts;
            $localAmount = $rowAsset['assetDepreciationMonthToDate'] * -1;
            $this->ledgerService->setGeneralLedger($leafId, $leafName, $businessPartnerId, $chartOfAccountId, $documentNumber, $journalNumber, $documentDate, $localAmount, $description, $module, $tableName, $tableNameDetail, $tableNameId, $tableNameDetailId, $referenceTableNameId, $referenceTableNameDetailId);


            if ($this->getVendor() == self::MYSQL) {
                $sql = "
				UPDATE 	`asset`
				SET 			`assetAccumulateDepreciate`		=	`assetAccumulateDepreciate` +'" . $rowAsset['assetDepreciationMonthToDate'] . "',
								`assetNetBookValue`					=	'" . $rowAsset['assetDepreciationMonthToDate'] . "' - `assetNetBookValue`,
								`assetCurrentDepreciateYear`	=	'" . $rowAsset['assetDepreciationYear'] . "',
								`assetCurrentDepreciatePeriod`	=	'" . $rowAsset['assetDepreciationPeriod'] . "'
				WHERE		`assetId`									=	'" . $rowAsset['assetId'] . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
				UPDATE 	[asset]
				SET 			[assetAccumulateDepreciate]		=	[assetAccumulateDepreciate] +'" . $rowAsset['assetDepreciationMonthToDate'] . "',
								[assetNetBookValue]					=	'" . $rowAsset['assetDepreciationMonthToDate'] . "' - [assetNetBookValue],
								[assetCurrentDepreciateYear]		=	'" . $rowAsset['assetDepreciationYear'] . "',
								[assetCurrentDepreciatePeriod]	=	'" . $rowAsset['assetDepreciationPeriod'] . "'
				WHERE		[assetId]									=	'" . $rowAsset['assetId'] . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
				UPDATE 	ASSET
				SET 			ASSETACCUMULATEDEPRECIATE		=	ASSETACCUMULATEDEPRECIATE +'" . $rowAsset['assetDepreciationMonthToDate'] . "',
								ASSETNETBOOKVALUE						=	'" . $rowAsset['assetDepreciationMonthToDate'] . "' - ASSETNETBOOKVALUE,
								ASSETCURRENTDEPRECIATEYEAR		=	'" . $rowAsset['assetDepreciationYear'] . "',
								ASSETCURRENTDEPRECIATEPERIOD	=	'" . $rowAsset['assetDepreciationPeriod'] . "'
				WHERE		ASSETID											=	'" . $rowAsset['assetId'] . "'";
            }
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
    }

    /**
     * Return Finance Year Primary Key
     * @return int
     */
    public function getFinanceYearId() {
        return $this->financeYearId;
    }

    /**
     * Set Finance Year Primary Key
     * @param int $financeYearId
     * @return $this|void
     */
    public function setFinanceYearId($financeYearId) {
        $this->financeYearId = $financeYearId;
        return $this;
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
     * @param int $financeYearYear
     * @return $this
     */
    public function setFinanceYearYear($financeYearYear) {
        $this->financeYearYear = $financeYearYear;
        return $this;
    }

    /**
     * Set Finance Period Range Primary Key
     * @return int
     */
    public function getFinancePeriodRangeId() {
        return $this->financePeriodRangeId;
    }

    /**
     * Set Finance Period Range Primary Key
     * @param int $financePeriodRangeId
     * @return $this
     */
    public function setFinancePeriodRangeId($financePeriodRangeId) {
        $this->financePeriodRangeId = $financePeriodRangeId;
        return $this;
    }

    /**
     * Set Finance Period Range Period E.g Date1 ~ Date2
     * @return int
     */
    public function getFinancePeriodRangePeriod() {
        return $this->financePeriodRangePeriod;
    }

    /**
     * Set Finance Period Range Period E.g Date1 ~ Date2
     * @param int $financePeriodRangePeriod
     * @return $this
     */
    public function setFinancePeriodRangePeriod($financePeriodRangePeriod) {
        $this->financePeriodRangePeriod = $financePeriodRangePeriod;
        return $this;
    }

    /**
     * Return Transaction Type
     * @return int
     */
    public function getTransactionTypeId() {
        return $this->transactionTypeId;
    }

    /**
     * Set Transaction Type
     * @param int $transactionTypeId Type
     * @return $this
     */
    public function setTransactionTypeId($transactionTypeId) {
        $this->transactionTypeId = $transactionTypeId;
        return $this;
    }

    /**
     * Return Transaction Type Code . E.g D->debit,C->credit
     * @return string
     */
    public function getTransactionTypeCode() {
        return $this->transactionTypeCode;
    }

    /**
     * Set Transaction Type Code . E.g D->debit,C->credit
     * @param string $transactionTypeCode
     * @return $this
     */
    public function setTransactionTypeCode($transactionTypeCode) {
        $this->transactionTypeCode = $transactionTypeCode;
        return $this;
    }

    /**
     * Return Transaction Type Description . E.g Debit,Credit,Debit Note Outward
     * @return string
     */
    public function getTransactionTypeDescription() {
        return $this->transactionTypeDescription;
    }

    /**
     * Set Transaction Type Description . E.g Debit,Credit,Debit Note Outward
     * @param string $transactionTypeDescription
     * @return $this
     */
    public function setTransactionTypeDescription($transactionTypeDescription) {
        $this->transactionTypeDescription = $transactionTypeDescription;
        return $this;
    }

    /**
     * @return int
     */
    public function getChartOfAccountCategoryId() {
        return $this->chartOfAccountCategoryId;
    }

    /**
     * @param int $chartOfAccountCategoryId
     * @return $this
     */
    public function setChartOfAccountCategoryId($chartOfAccountCategoryId) {
        $this->chartOfAccountCategoryId = $chartOfAccountCategoryId;
        return $this;
    }

    /**
     * Return Chart Of Account Description
     * @return string
     */
    public function getChartOfAccountCategoryDescription() {
        return $this->chartOfAccountCategoryDescription;
    }

    /**
     * Set Chart Of Account Description
     * @param string $chartOfAccountCategoryDescription
     * @return $this
     */
    public function setChartOfAccountCategoryDescription($chartOfAccountCategoryDescription) {
        $this->chartOfAccountCategoryDescription = $chartOfAccountCategoryDescription;
        return $this;
    }

    /**
     * Set Chart Of Account Type Primary Key
     * @return int
     */
    public function getChartOfAccountTypeId() {
        return $this->chartOfAccountTypeId;
    }

    /**
     * Set Chart Of Account Type Primary Key
     * @param int $chartOfAccountTypeId
     * @return $this
     */
    public function setChartOfAccountTypeId($chartOfAccountTypeId) {
        $this->chartOfAccountTypeId = $chartOfAccountTypeId;
        return $this;
    }

    /**
     * Return Chart Of Account Type Description
     * @return string
     */
    public function getChartOfAccountTypeDescription() {
        return $this->chartOfAccountTypeDescription;
    }

    /**
     * Set Chart Of Account Type Description
     * @param string $chartOfAccountTypeDescription
     * @return $this
     */
    public function setChartOfAccountTypeDescription($chartOfAccountTypeDescription) {
        $this->chartOfAccountTypeDescription = $chartOfAccountTypeDescription;
        return $this;
    }

    /**
     * Set Chart Of Account Number
     * @return string
     */
    public function getChartOfAccountNumber() {
        return $this->chartOfAccountNumber;
    }

    /**
     * Set Chart Of Account Number
     * @param string $chartOfAccountNumber
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
     * @param string $chartOfAccountDescription
     * @return $this
     */
    public function setChartOfAccountDescription($chartOfAccountDescription) {
        $this->chartOfAccountDescription = $chartOfAccountDescription;
        return $this;
    }

    /**
     * Return Business Company / Name
     * @return string
     */
    public function getBusinessPartnerCompany() {
        return $this->businessPartnerCompany;
    }

    /**
     * Set Business Company / Name
     * @param string $businessPartnerCompany
     * @return $this
     */
    public function setBusinessPartnerCompany($businessPartnerCompany) {
        $this->businessPartnerCompany = $businessPartnerCompany;
        return $this;
    }

    /**
     * Return  Country Currency Code .E.g RM,USD
     * @return string
     */
    public function getCountryCurrencyCode() {
        return $this->countryCurrencyCode;
    }

    /**
     * Set Country Currency Code .E.g RM,USD
     * @param string $countryCurrencyCode
     * @return $this
     */
    public function setCountryCurrencyCode($countryCurrencyCode) {
        $this->countryCurrencyCode = $countryCurrencyCode;
        return $this;
    }

    /**
     * Return Chart Of Account Category Code
     * @return string $chartOfAccountCategoryCode
     */
    public function getChartOfAccountCategoryCode() {
        return $this->chartOfAccountCategoryCode;
    }

    /**
     * Set Chart Of Account Category Code
     * @param string $value
     * @return $this
     */
    public function setChartOfAccountCategoryCode($value) {
        $this->chartOfAccountCategoryCode = $value;
        return $this;
    }

    /**
     * Return Chart Of Account Type Code
     * @return string $chartOfAccountTypeCode
     */
    public function getChartOfAccountTypeCode() {
        return $this->chartOfAccountTypeCode;
    }

    /**
     * Set Chart Of Account Type Code
     * @param string $value
     * @return $this
     */
    public function setChartOfAccountTypeCode($value) {
        $this->chartOfAccountTypeCode = $value;
        return $this;
    }

    /**
     * Return Country Description
     * @return string
     */
    public function getCountryDescription() {
        return $this->countryDescription;
    }

    /**
     * Set Country Description
     * @param string $countryDescription
     * @return $this
     */
    public function setCountryDescription($countryDescription) {
        $this->countryDescription = $countryDescription;
        return $this;
    }

    /**
     * Return Chart Of Account Primary Key
     * @return int
     */
    public function getChartOfAccountId() {
        return $this->chartOfAccountId;
    }

    /**
     * Set Chart Of Account Primary Key
     * @param int $chartOfAccountId
     * @return $this
     */
    public function setChartOfAccountId($chartOfAccountId) {
        $this->chartOfAccountId = $chartOfAccountId;
        return $this;
    }

    /**
     * Return Country
     * @return int
     */
    public function getCountryId() {
        return $this->countryId;
    }

    /**
     * Set Country
     * @param int $countryId
     * @return $this;
     */
    public function setCountryId($countryId) {
        $this->countryId = $countryId;
        return $this;
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