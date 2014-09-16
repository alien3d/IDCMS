<?php

namespace Core\Financial\FixedAsset\AssetWriteOff\Service;

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
 * Class AssetWriteOffService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\FixedAsset\AssetWriteOff\Service
 * @subpackage FixedAsset
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class AssetWriteOffService extends ConfigClass {

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
     * Return Item Category
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
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [itemCategoryId],
                     [itemCategoryDescription]
         FROM        [itemCategory]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
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
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [itemCategoryId],
         FROM        [itemCategory]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
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
     * Return ItemType
     * @return array|string
     */
    public function getItemType() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `itemTypeId`,
                     `itemTypeDescription`
         FROM        `itemtype`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [itemTypeId],
                     [itemTypeDescription]
         FROM        [itemType]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      ITEMTYPEID AS \"itemTypeId\",
                     ITEMTYPEDESCRIPTION AS \"itemTypeDescription\"
         FROM        ITEMTYPE
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         ORDER BY    ISDEFAULT";
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
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['itemTypeId'] . "'>" . $d . ". " . $row['itemTypeDescription'] . "</option>";
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
     * Return ItemType Default Value
     * @return int
     */
    public function getItemTypeDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $itemTypeId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `itemTypeId`
         FROM        	`itemtype`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [itemTypeId],
         FROM        [itemType]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      ITEMTYPEID AS \"itemTypeId\",
         FROM        ITEMTYPE
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
            $itemTypeId = $row['itemTypeId'];
        }
        return $itemTypeId;
    }

    /**
     * Return Asset
     * @return array|string
     */
    public function getAsset() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `assetId`,
                     `assetDescription`
         FROM        `asset`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [assetId],
                     [assetDescription]
         FROM        [asset]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      ASSETID AS \"assetId\",
                     ASSETDESCRIPTION AS \"assetDescription\"
         FROM        ASSET
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         ORDER BY    ISDEFAULT";
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
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['assetId'] . "'>" . $d . ". " . $row['assetDescription'] . "</option>";
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
     * Return Asset Default Value
     * @return int
     */
    public function getAssetDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $assetId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `assetId`
         FROM        	`asset`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [assetId],
         FROM        [asset]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      ASSETID AS \"assetId\",
         FROM        ASSET
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
            $assetId = $row['assetId'];
        }
        return $assetId;
    }

    /**
     * Return AssetWriteOffReason
     * @return array|string
     */
    public function getAssetWriteOffReason() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `assetWriteOffReasonId`,
                     `assetWriteOffReasonDescription`
         FROM        `assetwriteoffreason`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [assetWriteOffReasonId],
                     [assetWriteOffReasonDescription]
         FROM         [assetWriteOffReason]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      ASSETWRITEOFFREASONID AS \"assetWriteOffReasonId\",
                     ASSETWRITEOFFREASONDESCRIPTION AS \"assetWriteOffReasonDescription\"
         FROM        ASSETWRITEOFFREASON
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         ORDER BY    ISDEFAULT";
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
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['assetWriteOffReasonId'] . "'>" . $d . ". " . $row['assetWriteOffReasonDescription'] . "</option>";
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
     * Return AssetWriteOffReason Default Value
     * @return int
     */
    public function getAssetWriteOffReasonDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $assetWriteOffReasonId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `assetWriteOffReasonId`
         FROM        	`assetwriteoffreason`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [assetWriteOffReasonId],
         FROM         [assetWriteOffReason]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      ASSETWRITEOFFREASONID AS \"assetWriteOffReasonId\",
         FROM        ASSETWRITEOFFREASON
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
            $assetWriteOffReasonId = $row['assetWriteOffReasonId'];
        }
        return $assetWriteOffReasonId;
    }

    /**
     * Set Asset Write Off
     * @param string|int $rangeId
      * @param int $leafId Leaf Primary key
     * @param string $leafName Leaf Name
     * @return void
     */
    public function setWriteOffPosting($rangeId, $leafId, $leafName) {
        $sql = null;
        $assetSpecificationWriteOffAccounts = null;
        $assetSpecificationCostAccounts = null;
        $journalNumber = $this->getDocumentNumber('GLPT');
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
            $assetSpecificationCostAccounts = $row['assetSpecificationCostAccounts'];
            $assetSpecificationAccumulativeDepreciationAccounts = $row['assetSpecificationAccumulativeDepreciationAccounts'];
            $assetSpecificationWriteOffAccounts = $row['assetSpecificationWriteOffAccounts'];
            //$assetSpecificationDepreciationAccounts = $row['assetSpecificationDepreciationAccounts'];
            //$assetSpecificationGainAndLossAccounts = $row['assetSpecificationGainAndLossAccounts'];
            //$assetSpecificationClearingAccounts = $row['assetSpecificationClearingAccounts'];
            //$assetSpecificationNominalValue = $row['assetSpecificationNominalValue'];
            //$assetSpecificationMinimumReOrder = $row['assetSpecificationMinimumReOrder'];
        }

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			UPDATE	`assetwriteoff`
			SET			`isPost`= 1
			WHERE		`assetWriteOffId`	IN	 (" . $rangeId . ")";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			UPDATE	[assetWriteOff]
			SET			[isPost]= 1
			WHERE		[assetWriteOffId]	IN	 (" . $rangeId . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			UPDATE	ASSETWRITEOFF
			SET			ISPOST= 1
			WHERE		ASSETWRITEOFFID	IN	 (" . $rangeId . ")";
        }

        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT *
			FROM	`assetwriteoff`
			JOIN		`asset`
			USING  (`companyId`,`itemCategoryId`,`itemTypeId`,`assetId`)
			WHERE	`assetWriteOffId` IN (" . $rangeId . ")";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT *
			FROM	[assetWriteOff]
			JOIN		[asset]
			ON		[assetWriteOff].[companyId] = [asset].[companyId]
			AND		[assetWriteOff].[itemCategoryId] = [asset].[itemCategoryId]
			AND		[assetWriteOff].[itemTypeId] = [asset].[itemTypeId]
			AND		[assetWriteOff].[assetId] = [asset].[assetId]
			WHERE	[assetWriteOffId] IN (" . $rangeId . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT  ASSET.BUSINESSPARTNERID AS \"businessPartnerId\",
						DOCUMENTNUMBER  AS \"documentNumber\",
						ASSETWRITEOFFDATE AS \"assetWriteOffDate\",
						YEARTODATE	 AS \"yearToDate\",
						CURRENTNETBOOKVALUE	AS \"currentNetBookValue\",
						ASSETWRITEOFFDESCRIPTION	 AS \"assetWriteOffDescription\"
			FROM	ASSETWRITEOFF
			JOIN		ASSET
			ON		ASSETWRITEOFF.COMPANYID = ASSET.COMPANYID
			AND		ASSETWRITEOFF.ITEMCATEGORYID= ASSET.ITEMCATEGORYID
			AND		ASSETWRITEOFF.ITEMTYPEID = ASSET.ITEMTYPEID
			AND		ASSETWRITEOFF.ASSETID = ASSET.ASSETID
			WHERE	ASSETWRITEOFFID IN (" . $rangeId . ")";
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
            $documentDate = $rowAsset['assetWriteOffDate'];
            $description = $rowAsset['assetWriteOffDescription'];
            $module = 'AS';
                $tableName = 'assetWriteOff';
                $tableNameDetail = 'assetWriteOffDetail';
                $tableNameId = 'assetWriteOffId';
                $tableNameDetailId = 'assetWriteOffDetailId';
                $referenceTableNameId = $row['assetWriteOffId'];
                $referenceTableNameDetailId = $row['assetWriteOffDetailId'];
            /**********************************************************************
             * Example                                                                                                          *
             * 0050    Motor Vehicles                    Write Off Asset                            10,000.00    *
             * 0051    Motor Vehicle Depreciation    Write Off Asset          9,900.00                      *
             * 8005    Fixed Asset Write Off            Write Off Asset             100.00                      *
             * ******************************************************************* */

            $chartOfAccountId = $assetSpecificationCostAccounts;
            $localAmount = $rowAsset['assetPrice'];
            $this->ledgerService->setGeneralLedger($leafId, $leafName, $businessPartnerId, $chartOfAccountId, $documentNumber, $journalNumber, $documentDate, $localAmount, $description, $module, $tableName, $tableNameDetail, $tableNameId, $tableNameDetailId, $referenceTableNameId, $referenceTableNameDetailId);

            $chartOfAccountId = $assetSpecificationAccumulativeDepreciationAccounts;
            // this is for checking purpose
            $localAmount = $rowAsset['assetPrice'] - $rowAsset['assetNetBookValue'];
            // some call year to date depreciation
            $localAmount = $rowAsset['yearToDate'];
            $this->ledgerService->setGeneralLedger($leafId, $leafName, $businessPartnerId, $chartOfAccountId, $documentNumber, $journalNumber, $documentDate, $localAmount, $description, $module, $tableName, $tableNameDetail, $tableNameId, $tableNameDetailId, $referenceTableNameId, $referenceTableNameDetailId);

            $chartOfAccountId = $assetSpecificationWriteOffAccounts;
            $localAmount = $rowAsset['assetNetBookValue'];
            $this->ledgerService->setGeneralLedger($leafId, $leafName, $businessPartnerId, $chartOfAccountId, $documentNumber, $journalNumber, $documentDate, $localAmount, $description, $module, $tableName, $tableNameDetail, $tableNameId, $tableNameDetailId, $referenceTableNameId, $referenceTableNameDetailId);
            $rangeAssetId .= $rowAsset['assetId'] . ",";
        }
        $rangeAssetId = substr($rangeAssetId, 0, -1);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			UPDATE 	`asset`
			SET    		`isWriteOff`	= 1
			WHERE 		`assetId` IN  (" . $rangeAssetId . ")";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			UPDATE [asset]
			SET		[isWriteOff]	= 1
			WHERE  [assetId] IN  (" . $rangeAssetId . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			UPDATE	ASSET
			SET			ISWRITEOFF	= 1
			WHERE		ASSETID 	IN  (" . $rangeAssetId . ")";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * UPDATE / INSERT Procedure For General Ledger
     * @param int $leafId
     * @param string $leafName
     * @param int $businessPartnerId
     * @param int $chartOfAccountId
     * @param string $documentNumber
     * @param string $journalNumber
     * @param string $documentDate
     * @param double $localAmount
     * @param string $description
     * @param null|string $module
     * @param null|string $tableName
     * @param null|string $tableNameDetail
     * @param null|int $tableNameId
     * @param null|int $tableNameDetailId
     * @param null|int $generalLedgerId
     * @return int|null
     */
    public function setGeneralLedger(
    $leafId, $leafName, $businessPartnerId, $chartOfAccountId, $documentNumber, $journalNumber, $documentDate, $localAmount, $description, $module = null, $tableName = null, $tableNameDetail = null, $tableNameId = null, $tableNameDetailId = null, $generalLedgerId = null
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
            $this->setTransactionTypeDescription('Credit');
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
            } else {
                if ($this->getVendor() == self::MSSQL) {
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
                } else {
                    if ($this->getVendor() == self::ORACLE) {
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
                }
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
                INSERT INTO `generalledger`(
                            `generalLedgerId`,                      `companyId`,
                            `financeYearId`,                        `financeYearYear`,
                            `financePeriodRangeId`,                 `financePeriodRangePeriod`,
                            `journalNumber`,                        `documentNumber`,
                            `documentDate`,                         `generalLedgerTitle`,
                            `generalLedgerDescription`,             `generalLedgerDate`,
                            `countryId`,                            `countryCurrencyCode`,
                            `transactionTypeId`,                    `transactionTypeCode`,
                            `transactionTypeDescription`,           `foreignAmount`,
                            `localAmount`,                          `chartOfAccountCategoryId`,
                            `chartOfAccountCategoryDescription`,    `chartOfAccountTypeId`,
                                                                    `chartOfAccountTypeDescription`,
                            `chartOfAccountId`,                     `chartOfAccountNumber`,
                            `chartOfAccountDescription`,            `businessPartnerId`,
                            `businessPartnerDescription`,           `module`,
                            `tableName`,                            `tableNameId`,
                            `tableNameDetail`,                      `tableNameDetailId`,
                            `leafId`,                               `isDefault`,
                            `isNew`,                                `isDraft`,
                            `isUpdate`,                             `isDelete`,
                            `isActive`,                             `isApproved`,
                            `isReview`,                             `isPost`,
                            `isMerge`,                              `isSlice`,
                            `isAuthorized`,                         `executeBy`,
                            `executeName`,                          `executeTime`,
																`leafName`,
							`chartOfAccountCategoryCode`,			`chartOfAccountTypeCode`
                    ) VALUES (
                                null,                               '" . $this->getCompanyId() . "',
                                '" . $this->getFinanceYearId() . "',               '" . $this->getFinanceYearYear() . "',
                                '" . $this->getFinancePeriodRangeId() . "',        '"
                        . $this->getFinancePeriodRangePeriod() . "',
                                '" . $journalNumber . "',               '" . $documentNumber . "',
                                '" . $documentDate . "',                '" . $generalLedgerTitle . "',
                                '" . $generalLedgerDescription . "',    '" . $generalLedgerDate . "',
                                '" . $this->getCountryId() . "'    ,               '" . $this->getCountryCurrencyCode()
                        . "',
                                '" . $this->getTransactionTypeId() . "',           '" . $this->getTransactionTypeCode()
                        . "',
                                '" . $this->getTransactionTypeDescription() . "',  '" . $foreignAmount . "',
                                '" . $localAmount . "',                 '" . $this->getChartOfAccountCategoryId() . "',
                                '" . $this->getChartOfAccountDescription() . "',   '" . $this->getChartOfAccountTypeId()
                        . "',
                                        '" . $this->getChartOfAccountTypeDescription() . "',
                                '" . $chartOfAccountId . "',            '" . $this->getChartOfAccountNumber() . "',
                                '" . $this->getChartOfAccountDescription() . "',   '" . $businessPartnerId . "',
                                '" . $this->getBusinessPartnerCompany() . "',       '" . $module . "',
                                '" . $tableName . "',                   '" . $tableNameId . "',
                                '" . $tableNameDetail . "',             '" . $tableNameDetailId . "',
                                '" . $leafId . "',                      0,
                                1,                                  0,
                                0,                                  0,
                                1,                                  0,
                                0,                                  0,
                                0,                                  0,
                                0,                                  '" . $this->getStaffId() . "',
                                '" . $this->getStaffName() . "',        " . $this->getExecuteTime() . ",
																		'" . $leafName . "',
								'" . $this->getChartOfAccountCategoryCode() . "','" . $this->getChartOfAccountTypeCode()
                        . "');";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                INSERT INTO [generalLedger](
                            [generalLedgerId],                      [companyId],
                            [financeYearId],                        [financeYearYear],
                            [financePeriodRangeId],                 [financePeriodRangePeriod],
                            [journalNumber],                        [documentNumber],
                            [documentDate],                         [generalLedgerTitle],
                            [generalLedgerDescription],             [generalLedgerDate],
                            [countryId],                            [countryCurrencyCode],
                            [transactionTypeId],                    [transactionTypeCode],
                            [transactionTypeDescription],           [foreignAmount],
                            [localAmount],                          [chartOfAccountCategoryId],
                            [chartOfAccountCategoryDescription],    [chartOfAccountTypeId],
                                                                    [chartOfAccountTypeDescription],
                            [chartOfAccountId],                     [chartOfAccountNumber],
                            [chartOfAccountDescription],            [businessPartnerId],
                            [businessPartnerDescription],           [module],
                            [tableName],                            [tableNameId],
                            [tableNameDetail],                      [tableNameDetailId],
                            [leafId],                               [isDefault],
                            [isNew],                                [isDraft],
                            [isUpdate],                             [isDelete],
                            [isActive],                             [isApproved],
                            [isReview],                             [isPost],
                            [isMerge],                              [isSlice],
                            [isAuthorized],                         [executeBy],
                            [executeName],                          [executeTime],
															[leafName],
							[chartOfAccountCategoryCode],			[chartOfAccountTypeCode]
                    ) VALUES (
                                  null,                               '" . $this->getCompanyId() . "',
                                '" . $this->getFinanceYearId() . "',               '" . $this->getFinanceYearYear() . "',
                                '" . $this->getFinancePeriodRangeId() . "',        '"
                            . $this->getFinancePeriodRangePeriod() . "',
                                '" . $journalNumber . "',               '" . $documentNumber . "',
                                '" . $documentDate . "',                '" . $generalLedgerTitle . "',
                                '" . $generalLedgerDescription . "',    '" . $generalLedgerDate . "',
                                '" . $this->getCountryId() . "'    ,               '" . $this->getCountryCurrencyCode()
                            . "',
                                '" . $this->getTransactionTypeId() . "',           '" . $this->getTransactionTypeCode()
                            . "',
                                '" . $this->getTransactionTypeDescription() . "',  '" . $foreignAmount . "',
                                '" . $localAmount . "',                 '" . $this->getChartOfAccountCategoryId() . "',
                                '" . $this->getChartOfAccountDescription() . "',   '" . $this->getChartOfAccountTypeId()
                            . "',
                                      '" . $this->getChartOfAccountTypeDescription() . "',
                                '" . $chartOfAccountId . "',            '" . $this->getChartOfAccountNumber() . "',
                                '" . $this->getChartOfAccountDescription() . "',   '" . $businessPartnerId . "',
                                '" . $this->getBusinessPartnerCompany() . "',       '" . $module . "',
                                '" . $tableName . "',                   '" . $tableNameId . "',
                                '" . $tableNameDetail . "',             '" . $tableNameDetailId . "',
                                '" . $leafId . "',                      0,
                                1,                                  0,
                                0,                                  0,
                                1,                                  0,
                                0,                                  0,
                                0,                                  0,
                                0,                                  '" . $this->getStaffId() . "',
                                '" . $this->getStaffName() . "',        " . $this->getExecuteTime() . ",
														'" . $leafName . "',
								'" . $this->getChartOfAccountCategoryCode() . "','" . $this->getChartOfAccountTypeCode()
                            . "');";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
                INSERT INTO GENERALLEDGER(
                            GENERALLEDGERID,                      COMPANYID,
                            FINANCEYEARID,                        FINANCEYEARYEAR,
                            FINANCEPERIODRANGEID,                 FINANCEPERIODRANGEPERIOD,
                            JOURNALNUMBER,                        DOCUMENTNUMBER,
                            DOCUMENTDATE,                         GENERALLEDGERTITLE,
                            GENERALLEDGERDESCRIPTION,             GENERALLEDGERDATE,
                            COUNTRYID,                            COUNTRYCURRENCYCODE,
                            TRANSACTIONTYPEID,                    TRANSACTIONTYPECODE,
                            TRANSACTIONTYPEDESCRIPTION,           FOREIGNAMOUNT,
                            LOCALAMOUNT,                          CHARTOFACCOUNTCATEGORYID,
                            CHARTOFACCOUNTCATEGORYDESCRIPTION,    CHARTOFACCOUNTTYPEID,
                                                                CHARTOFACCOUNTTYPEDESCRIPTION,
                            CHARTOFACCOUNTID,                     CHARTOFACCOUNTNUMBER,
                            CHARTOFACCOUNTDESCRIPTION,            BUSINESSPARTNERID,
                            BUSINESSPARTNERDESCRIPTION,           MODULE,
                            TABLENAME,                            TABLENAMEID,
                            TABLENAMEDETAIL,                      TABLENAMEDETAILID,
                            LEAFID,                               ISDEFAULT,
                            ISNEW,                                ISDRAFT,
                            ISUPDATE,                             ISDELETE,
                            ISACTIVE,                             ISAPPROVED,
                            ISREVIEW,                             ISPOST,
                            ISMERGE,                              ISSLICE,
                            ISAUTHORIZED,                         EXECUTEBY,
                            EXECUTENAME,                          EXECUTETIME,
															  LEAFNAME,
							CHARTOFACCOUNTCATEGORYCODE,			  CHARTOFACCOUNTTYPECODE
                    ) VALUES (
                                   null,                               '" . $this->getCompanyId() . "',
                                '" . $this->getFinanceYearId() . "',               '" . $this->getFinanceYearYear() . "',
                                '" . $this->getFinancePeriodRangeId() . "',        '"
                                . $this->getFinancePeriodRangePeriod() . "',
                                '" . $journalNumber . "',               '" . $documentNumber . "',
                                '" . $documentDate . "',                '" . $generalLedgerTitle . "',
                                '" . $generalLedgerDescription . "',    '" . $generalLedgerDate . "',
                                '" . $this->getCountryId() . "'    ,               '" . $this->getCountryCurrencyCode()
                                . "',
                                '" . $this->getTransactionTypeId() . "',           '" . $this->getTransactionTypeCode()
                                . "',
                                '" . $this->getTransactionTypeDescription() . "',  '" . $foreignAmount . "',
                                '" . $localAmount . "',                 '" . $this->getChartOfAccountCategoryId() . "',
                                '" . $this->getChartOfAccountDescription() . "',   '" . $this->getChartOfAccountTypeId()
                                . "',
                                       '" . $this->getChartOfAccountTypeDescription() . "',
                                '" . $chartOfAccountId . "',            '" . $this->getChartOfAccountNumber() . "',
                                '" . $this->getChartOfAccountDescription() . "',   '" . $businessPartnerId . "',
                                '" . $this->getBusinessPartnerCompany() . "',       '" . $module . "',
                                '" . $tableName . "',                   '" . $tableNameId . "',
                                '" . $tableNameDetail . "',             '" . $tableNameDetailId . "',
                                '" . $leafId . "',                      0,
                                1,                                  0,
                                0,                                  0,
                                1,                                  0,
                                0,                                  0,
                                0,                                  0,
                                0,                                  '" . $this->getStaffId() . "',
                                '" . $this->getStaffName() . "',        " . $this->getExecuteTime() . ",
														'" . $leafName . "',
								'" . $this->getChartOfAccountCategoryCode() . "','" . $this->getChartOfAccountTypeCode()
                                . "');";
                    }
                }
            }
            try {
                $this->q->create($sql);
            } catch (\Exception $e) {
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
            $generalLedgerId = $this->q->lastInsertId('generalLedger');
        }
        return $generalLedgerId;
    }

    /**
     * Return Financial Date Information
     * @param string $documentDate
     */
    private function setFinancePeriodInformation($documentDate) {
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
         AND         " . $documentDate . " between `financeperiodrange`.`financePeriodRangeStartDate` AND  `financeperiodrange`.`financePeriodRangeEndDate`
         ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
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
            AND         " . $documentDate . " between  [financePeriodRange].[financePeriodRangeStartDate] AND   [financePeriodRange].[financePeriodRangeEndDate]
         ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
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
            AND         " . $documentDate . " BETWEEN FINANCEPERIODRANGE.FINANCEPERIODRANGESTARTDATE AND  FINANCEPERIODRANGE.FINANCEPERIODRANGEENDDATE
         ";
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
            $this->setFinanceYearYear($row['financeYearYear']);
            $this->setFinanceYearId($row['financeYearId']);
            $this->setFinancePeriodRangeId($row['financePeriodRangeId']);
            $this->setFinancePeriodRangePeriod($row['financePeriodRangePeriod']);
        }
    }

    /**
     * Set Chart Of Account Information
     * @param int $chartOfAccountId
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
        } else {
            if ($this->getVendor() == self::MSSQL) {
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
            } else {
                if ($this->getVendor() == self::ORACLE) {
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
     * Set Business Partner Information
     * @param int $businessPartnerId
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
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [businessPartnerCompany]
         FROM        [businessPartner]
         WHERE       [isActive]         =   1
         AND         [companyId]        =   '" . $this->getCompanyId() . "'
         AND    	 [businessPartnerId]  =   '" . $businessPartnerId . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
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
            $this->setBusinessPartnerCompany($row['businessPartnerCompany']);
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