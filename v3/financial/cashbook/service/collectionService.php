<?php

namespace Core\Financial\Cashbook\Collection\Service;

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
 * Class CollectionService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version    2
 * @author     hafizan
 * @package    Core\Financial\Cashbook\Collection\Service
 * @subpackage Cashbook
 * @link       http://www.hafizan.com
 * @license    http://www.gnu.org/copyleft/lesser.html LGPL
 */
class CollectionService extends ConfigClass {

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
     * Quotation / Pre Sales
     */
    const QUOTATION = 'QOTE';

    /**
     * Purchase Order
     */
    const PURCHASE_ORDER = 'PCOD';

    /**
     * Transfer To General Ledger
     */
    const TRANSFER_TO_GL = 'TSGL';

    /**
     * Sales Order
     */
    const SALES_ORDER = 'SLOD';

    /**
     * Sales Invoice
     */
    const SALES_INVOICE = 'SLINV';

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
     * Return Collection Type
     * @return array|string
     */
    public function getCollectionType() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `collectionTypeId`,
                     `collectionTypeDescription`
         FROM        `collectiontype`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `collectionTypeDescription`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [collectionTypeId],
                     [collectionTypeDescription]
         FROM        [collectionType]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [collectionTypeDescription]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      COLLECTIONTYPEID AS \"collectionTypeId\",
                     COLLECTIONTYPEDESCRIPTION AS \"collectionTypeDescription\"
         FROM        COLLECTIONTYPE
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         ORDER BY    COLLECTIONTYPEDESCRIPTION ";
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
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['collectionTypeId'] . "'>" . $d . ". "
                            . $row['collectionTypeDescription'] . "</option>";
                } else if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
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
     * Return Collection Type Default Value
     * @return int
     */
    public function getCollectionTypeDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $collectionTypeId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `collectionTypeId`
         FROM        	`collectiontype`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [collectionTypeId],
         FROM        [collectionType]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      COLLECTIONTYPEID AS \"collectionTypeId\",
         FROM        COLLECTIONTYPE
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $collectionTypeId = $row['collectionTypeId'];
        }
        return $collectionTypeId;
    }

    /**
     * Return Business Partner Category
     * @return array|string
     */
    public function getBusinessPartnerCategory() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `businessPartnerCategoryId`,
                     `businessPartnerCategoryDescription`
         FROM        `businesspartnercategory`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `businessPartnerCategoryDescription`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [businessPartnerCategoryId],
                     [businessPartnerCategoryDescription]
         FROM        [businessPartnerCategory]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [businessPartnerCategoryDescription]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      BUSINESSPARTNERCATEGORYID AS \"businessPartnerCategoryId\",
                     BUSINESSPARTNERCATEGORYDESCRIPTION AS \"businessPartnerCategoryDescription\"
         FROM        BUSINESSPARTNERCATEGORY
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         ORDER BY     BUSINESSPARTNERCATEGORYDESCRIPTION ";
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
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['businessPartnerCategoryId'] . "'>" . $d . ". "
                            . $row['businessPartnerCategoryDescription'] . "</option>";
                } else   if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
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
     * Return Business Partner Category Default Value
     * @return int
     */
    public function getBusinessPartnerCategoryDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $businessPartnerCategoryId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `businessPartnerCategoryId`
         FROM        	`businesspartnercategory`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [businessPartnerCategoryId],
         FROM        [businessPartnerCategory]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      BUSINESSPARTNERCATEGORYID AS \"businessPartnerCategoryId\",
         FROM        BUSINESSPARTNERCATEGORY
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $businessPartnerCategoryId = $row['businessPartnerCategoryId'];
        }
        return $businessPartnerCategoryId;
    }

    /**
     * Return Business Partner
     * @return array|string
     */
    public function getBusinessPartner() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `businesspartner`.`businessPartnerId`,
                     `businesspartner`.`businessPartnerCompany`,
					 `businesspartnercategory`.`businessPartnerCategoryDescription`
         FROM        `businesspartner`
		 JOIN		 `businesspartnercategory`
		 USING		 (`companyId`,`businessPartnerCategoryId`)
         WHERE       `businesspartner`.`isActive`  =   1
         AND         `businesspartner`.`companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `businesspartnercategory`.`businessPartnerCategoryDescription`,
					 `businesspartner`.`businessPartnerCompany`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [businessPartner].[businessPartnerId],
                     [businessPartner].[businessPartnerCompany],
					 [businessPartnerCategory].[businessPartnerCategoryDescription]
         FROM        [businessPartner]
		 JOIN	     [businessPartnerCategory]
		 ON			 [businessPartnerCategory].[companyId] 					= 	[businessPartner].[companyId]
		 AND		 [businessPartnerCategory].[businessPartnerCategoryId] 	= 	[businessPartner].[businessPartnerCategoryId]
         WHERE       [businessPartner].[isActive]  							=	1
         AND         [businessPartner].[companyId] 							=   '" . $this->getCompanyId() . "'
         ORDER BY    [businessPartnerCategory].[businessPartnerCategoryDescription],
					 [businessPartner].[businessPartnerCompany]	";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      BUSINESSPARTNER.BUSINESSPARTNERID AS \"businessPartnerId\",
                     BUSINESSPARTNER.BUSINESSPARTNERCOMPANY AS \"businessPartnerCompany\",
					 BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYDESCRIPTION AS \"businessPartnerCategoryDescription\"
         FROM        BUSINESSPARTNER
		 JOIN	     BUSINESSPARTNERCATEGORY
		 ON			 BUSINESSPARTNERCATEGORY.COMPANYID 					= 	BUSINESSPARTNER.COMPANYID
		 AND		 BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYID 	= 	BUSINESSPARTNER.BUSINESSPARTNERCATEGORYIID
         WHERE       BUSINESSPARTNER.ISACTIVE    						=   1
         AND         BUSINESSPARTNER.COMPANYID   						=   '" . $this->getCompanyId() . "'
         ORDER BY    BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYDESCRIPTION ,
					 BUSINESSPARTNER.BUSINESSPARTNERCOMPANY";
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
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['businessPartnerId'] . "'>" . $d . ". "
                            . $row['businessPartnerCompany'] . "</option>";
                } else  if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
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
     * Return Business Partner Default Value
     * @return int
     */
    public function getBusinessPartnerDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $businessPartnerId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `businessPartnerId`
         FROM        `businesspartner`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [businessPartnerId],
         FROM        [businessPartner]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      BUSINESSPARTNERID AS \"businessPartnerId\",
         FROM        BUSINESSPARTNER
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $businessPartnerId = $row['businessPartnerId'];
        }
        return $businessPartnerId;
    }

    /**
     * Return Country
     * @return array|string
     */
    public function getCountry() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `countryId`,
					 `countryCurrencyCode`,
                     `countryDescription`
         FROM        `country`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `countryDescription`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [countryId],
					 [countryCurrencyCode],
                     [countryDescription]
         FROM        [country]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [countryDescription]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      COUNTRYID AS \"countryId\",
					 COUNTRYCURRENCYCODE AS \"countryCurrencyCode\",
                     COUNTRYDESCRIPTION AS \"countryDescription\"
         FROM        COUNTRY
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         ORDER BY    COUNTRYDESCRIPTION";
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
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['countryId'] . "'>" . $row['countryCurrencyCode'] . " - "
                            . $row['countryDescription'] . "</option>";
                } else  if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
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
     * Return Country Default Value
     * @return int
     */
    public function getCountryDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $countryId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `countryId`
         FROM        	`country`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [countryId],
         FROM        [country]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      COUNTRYID AS \"countryId\",
         FROM        COUNTRY
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $countryId = $row['countryId'];
        }
        return $countryId;
    }

    /**
     * Return Bank
     * @return array|string
     */
    public function getBank() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `bankId`,
                     `bankDescription`
         FROM        `bank`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
		 AND         `isCollection`	=   1
         ORDER BY    `bankDescription`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [bankId],
                     [bankDescription]
         FROM        [bank]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
		  AND		 [isCollection]	=   1
         ORDER BY    [bankDescription]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      BANKID AS \"bankId\",
                     BANKDESCRIPTION AS \"bankDescription\"
         FROM        BANK
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
		  AND		 ISCOLLECTION	=   1
         ORDER BY    BANKDESCRIPTION ";
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
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str
                            .=
                            "<option value='" . $row['bankId'] . "'>" . $d . ". " . $row['bankDescription'] . "</option>";
                } else if ($this->getServiceOutput() == 'html') {
                    $items[] = $row;
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
     * Return Bank Default Value
     * @return int
     */
    public function getBankDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $bankId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `bankId`
         FROM        	`bank`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [bankId],
         FROM        [bank]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      BANKID AS \"bankId\",
         FROM        BANK
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $bankId = $row['bankId'];
        }
        return $bankId;
    }

    /**
     * Return Payment Type
     * @return array|string
     */
    public function getPaymentType() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `paymentTypeId`,
                     `paymentTypeDescription`
         FROM        `paymenttype`
         WHERE       `isActive`  	=   1
         AND         `companyId` 	=   '" . $this->getCompanyId() . "'
		 AND         `isCollection`	=   1
         ORDER BY    `paymentTypeDescription`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [paymentTypeId],
                     [paymentTypeDescription]
         FROM        [paymentType]
         WHERE       [isActive]  	=   1
         AND         [companyId] 	=   '" . $this->getCompanyId() . "'
		 AND		 [isCollection]	=   1
         ORDER BY    [paymentTypeDescription]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      PAYMENTTYPEID AS \"paymentTypeId\",
                     PAYMENTTYPEDESCRIPTION AS \"paymentTypeDescription\"
         FROM        PAYMENTTYPE
         WHERE       ISACTIVE    	=   1
         AND         COMPANYID   	=   '" . $this->getCompanyId() . "'
		 AND		 ISCOLLECTION	=   1
         ORDER BY    PAYMENTTYPEDESCRIPTION";
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
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['paymentTypeId'] . "'>" . $d . ". "
                            . $row['paymentTypeDescription'] . "</option>";
                } else if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
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
     * Return Payment Type Default Value
     * @return int
     */
    public function getPaymentTypeDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $paymentTypeId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `paymentTypeId`
         FROM        	`paymenttype`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [paymentTypeId],
         FROM        [paymentType]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      PAYMENTTYPEID AS \"paymentTypeId\",
         FROM        PAYMENTTYPE
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $paymentTypeId = $row['paymentTypeId'];
        }
        return $paymentTypeId;
    }

    /**
     * Return Chart Of Account.Filter Liability Account. Deposit Account
     * @return array|string
     */
    public function getChartOfAccount() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT		`chartofaccount`.`chartOfAccountId`,
							`chartofaccount`.`chartOfAccountNumber`,
							`chartofaccount`.`chartOfAccountTitle`,
							`chartofaccounttype`.`chartOfAccountTypeDescription`
			FROM        `chartofaccount`
			JOIN        `chartofaccounttype`
			USING       (`companyId`,`chartOfAccountCategoryId`,`chartOfAccountTypeId`)
			JOIN        `chartofaccountcategory`
			USING       (`companyId`,`chartOfAccountCategoryId`)
			WHERE       `chartofaccount`.`isActive`  					=   1
			AND            `chartofaccount`.`companyId` 					=   '" . $this->getCompanyId() . "'
			AND		 	  `chartofaccountcategory`.`chartOfAccountCategoryCode`	=	'" . self::INCOME . "'
			ORDER BY    `chartofaccounttype`.`chartOfAccountTypeId`,
			`chartofaccount`.`chartOfAccountNumber`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      [chartOfAccount].[chartOfAccountId],
							[chartOfAccount].[chartOfAccountNumber],
							[chartOfAccount].[chartOfAccountTitle],
							[chartOfAccountType].[chartOfAccountTypeDescription]
			FROM        [chartOfAccount]
			
			JOIN			[chartOfAccountCategory]
			ON          	[chartOfAccount].[companyId]   				= 	[chartOfAccountType].[companyId]
			AND         	[chartOfAccount].[chartOfAccountCategoryId]   		= 	[chartOfAccountType].[chartOfAccountCategoryId]
			
			JOIN			[chartOfAccountType]
			ON          	[chartOfAccount].[companyId]   				= 	[chartOfAccountType].[companyId]
			AND         	[chartOfAccount].[chartOfAccountTypeId]   		= 	[chartOfAccountType].[chartOfAccountTypeId]
			AND         	[chartOfAccount].[chartOfAccountCategoryId]   		= 	[chartOfAccountType].[chartOfAccountCategoryId]
			
			WHERE       [chartOfAccount].[isActive]  					=   1
			AND         [chartOfAccount].[companyId] 					=   '" . $this->getCompanyId() . "'
			AND		 [chartOfAccount].[chartOfAccountCategoryCode]	=	'" . self::INCOME . "'
			ORDER BY    [chartOfAccount].[chartOfAccountNumber]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
				SELECT      CHARTOFACCOUNTID               AS  \"chartOfAccountId\",
				CHARTOFACCOUNTNUMBER           AS  \"chartOfAccountNumber\",
				CHARTOFACCOUNTTITLE            AS  \"chartOfAccountTitle\",
				CHARTOFACCOUNTTYPEDESCRIPTION  AS  \"chartOfAccountTypeDescription\"
				FROM        CHARTOFACCOUNT
				
				JOIN        CHARTOFACCOUNTCATEGORY
				ON          CHARTOFACCOUNT.COMPANYID               	=   CHARTOFACCOUNTTYPE.COMPANYID
				AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID    	=   CHARTOFACCOUNTTYPE.CHARTOFACCOUNTCATEGORYID
				
				JOIN        CHARTOFACCOUNTTYPE
				ON          CHARTOFACCOUNT.COMPANYID               	=   CHARTOFACCOUNTTYPE.COMPANYID
				AND         CHARTOFACCOUNT.CHARTOFACCOUNTTYPEID    	=   CHARTOFACCOUNTTYPE.CHARTOFACCOUNTTYPEID
				AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID    	=   CHARTOFACCOUNTTYPE.CHARTOFACCOUNTCATEGORYID
				
				WHERE       CHARTOFACCOUNT.ISACTIVE                	=   1
				AND         CHARTOFACCOUNT.COMPANYID               	=   '" . $this->getCompanyId() . "'
				AND		 CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYCODE	=	'" . self::INCOME . "'
				ORDER BY    CHARTOFACCOUNT.CHARTOFACCOUNTNUMBER";
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
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['chartOfAccountId'] . "'>" . $row['chartOfAccountNumber'] . " - "
                            . $row['chartOfAccountTitle'] . "</option>";
                } else  if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
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
     * Return Chart Of Account Default Value
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
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
     * Set Invoice
     * @param int      $businessPartnerId  Business Partner / Customer / Vendor / Shipper Primary Key
     * @param int      $chartOfAccountId   Chart of Account Primary Key
     * @param string   $documentNumber	   Document Number
     * @param string   $invoiceDate        Date
     * @param string   $invoiceDescription Description
     * @param float    $invoiceAmount      Amount / Figure / Value
     * @param null|int $invoiceId
     * @return int|null
     * @throws \Exception
     */
    public function setInvoice($businessPartnerId,$chartOfAccountId,$documentNumber, $invoiceDate, $invoiceDescription, $invoiceAmount, $invoiceId = null) {
        $sql = null;
        if (intval($invoiceId) > 0) {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
				UPDATE 	`invoice` 
				SET 	`businessPartnerId`		=	'" . $businessPartnerId . "',
						`invoiceTotalAmount`	=	'" . $invoiceAmount . "',
						`invoiceDate`			=	'" . $invoiceDate . "',
						`invoiceDescription`	=	'" . $invoiceDescription . "',
						`isNew`					=	0,
						`isUpdate`				=	1,
						`executeBy`				=	'" . $this->getStaffId() . "',
						`executeTime`			=	" . $this->getExecuteTime() . "
				WHERE   `invoiceId`				=	'" . $invoiceId . "'
				";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
				UPDATE 	[invoice]
				SET 	[businessPartnerId`		=	'" . $businessPartnerId . "',
						[invoiceTotalAmount]	=	'" . $invoiceAmount . "',
						[invoiceDate]			=	'" . $invoiceDate . "',
						[invoiceDescription]	=	'" . $invoiceDescription . "',
						[isNew]					=	0,
						[isUpdate]				=	1,
						[executeBy]				=	'" . $this->getStaffId() . "',
						[executeTime]			=	" . $this->getExecuteTime() . "
				WHERE   [invoiceId]				=	'" . $invoiceId . "'
				";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
				UPDATE 	INVOICE
				SET 	BUSINESSPARTNERID		=	'" . $businessPartnerId . "',
						INVOICETOTALAMOUNT		=	'" . $invoiceAmount . "',
						INVOICEDATE				=	'" . $invoiceDate . "',
						INVOICEDESCRIPTION		=	'" . $invoiceDescription . "',
						ISNEW					=	0,
						ISUPDATE				=	1,
						EXECUTEBY				=	'" . $this->getStaffId() . "',
						EXECUTETIME				=	" . $this->getExecuteTime() . "
				WHERE   INVOICEID				=	'" . $invoiceId . "'
				";
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
		INSERT INTO `invoice`(
					`invoiceId`,								`companyId`,									`invoiceCategoryId`, 
					`invoiceTypeId`, 							`businessPartnerId`,							`businessPartnerContactId`, 
					
					`countryId`, 								`invoiceProjectId`,	 							`paymentTermsId`, 
					`warehouseId`, 								`invoiceProcessId`, 							`taxId`, 
					
					`businessPartnerAddress`, 					`documentNumber`, 								`referenceNumber`, 
					`invoiceQuotationNumber`, 					`purchaseOrderNumber`, 							`invoiceTotalAmount`, 
					
					`invoiceTaxAmount`, 						`invoiceDiscountAmount`,						`invoiceShippingAmount`, 
					`invoiceDate`, 								`invoiceDueDate`, 								`invoiceDescription`, 
					
					`isDefault`, 								`isNew`, 										`isDraft`, 
					`isUpdate`, 								`isDelete`, 									`isActive`, 
					
					`isApproved`, 								`isReview`, 									`isPost`, 
					`executeBy`, 								`executeTime`,                                  `chartOfAccountId`
		) VALUES (
					null,										'" . $this->getCompanyId() . "', 					'"
                        . $this->getInvoiceCategoryDefaultValue() . "',
					'" . $this->getInvoiceTypeDefaultValue() . "',	'" . $businessPartnerId . "',	 					'"
                        . $this->getBusinessPartnerContactDefaultValue() . "',
					
					'" . $this->ledgerService->getCountryId() . "',				'" . $this->getInvoiceProjectDefaultValue()
                        . "', 	'" . $this->getPaymentTermsDefaultValue() . "',
					'" . $this->getWarehouseDefaultValue() . "',	'1',						 					'"
                        . $this->getTaxDefaultValue() . "',
					
					' ',										'" . $documentNumber . "',							' ',
					'' ,										' ',											0,
					0,											0,												0,
					'" . $invoiceDate . "',							'0000-00-00',									0,
					0,											1,												0,
					
					0,											0,												1,					
					0,											0,												0,
					
					'" . $this->getStaffId() . "',					" . $this->getExecuteTime() . ",            '".$chartOfAccountId."')";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
		INSERT INTO [invoice](
					[invoiceId],								[companyId],					[invoiceCategoryId],
					[invoiceTypeId], 							[businessPartnerId],			[businessPartnerContactId],
					[countryId], 								[invoiceProjectId],	 			[paymentTermsId],
					[warehouseId], 								[invoiceProcessId], 			[taxId],
					[businessPartnerAddress], 					[documentNumber], 				[referenceNumber],
					[invoiceQuotationNumber], 					[purchaseOrderNumber], 			[invoiceTotalAmount],
					[invoiceTaxAmount], 						[invoiceDiscountAmount],		[invoiceShippingAmount],
					[invoiceDate], 								[invoiceDueDate], 				[invoiceDescription],
					[isDefault], 								[isNew], 						[isDraft],
					[isUpdate], 								[isDelete], 					[isActive],
					[isApproved], 								[isReview], 					[isPost],
					[executeBy], 								[executeTime],                  [chartOfAccountId]
		) VALUES (
					null,										'" . $this->getCompanyId() . "', 	'"
                        . $this->getInvoiceCategoryDefaultValue() . "',
					'" . $this->getInvoiceTypeDefaultValue() . "',	'" . $businessPartnerId . "',	 	'"
                        . $this->getBusinessPartnerContactDefaultValue() . "',
					'" . $this->ledgerService->getCountryId() . "',				'" . $this->getInvoiceProjectDefaultValue() . "', '"
                        . $this->getPaymentTermsDefaultValue() . "',
					'" . $this->getWarehouseDefaultValue() . "',	'1',						 	'"
                        . $this->getTaxDefaultValue() . "',
					' ',										'" . $documentNumber . "',			' ',
					'' ,									' ',							0,
					0,											0,												0,
					'" . $invoiceDate . "',									'0000-00-00',					0,
					0,									1,								0,
					0,									0,								1,
					0,											0,								0,
					'" . $this->getStaffId() . "',					" . $this->getExecuteTime() . ",            '".$chartOfAccountId."')";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
		INSERT INTO INVOICE(
					INVOICEID,								COMPANYID,					INVOICECATEGORYID,
					INVOICETYPEID, 							BUSINESSPARTNERID,			BUSINESSPARTNERCONTACTID,
					COUNTRYID, 								INVOICEPROJECTID,	 			PAYMENTTERMSID,
					WAREHOUSEID, 								INVOICEPROCESSID, 			TAXID,
					BUSINESSPARTNERADDRESS, 					DOCUMENTNUMBER, 				REFERENCENUMBER,
					INVOICEQUOTATIONNUMBER, 					PURCHASEORDERNUMBER, 			INVOICETOTALAMOUNT,
					INVOICETAXAMOUNT, 						INVOICEDISCOUNTAMOUNT,		INVOICESHIPPINGAMOUNT,
					INVOICEDATE, 								INVOICEDUEDATE, 				INVOICEDESCRIPTION,
					ISDEFAULT, 								ISNEW, 						ISDRAFT,
					ISUPDATE, 								ISDELETE, 					ISACTIVE,
					ISAPPROVED, 								ISREVIEW, 					ISPOST,
					EXECUTEBY, 								EXECUTETIME,                CHARTOFACCOUNTID
		) VALUES (
					null,										'" . $this->getCompanyId() . "', 	'"
                        . $this->getInvoiceCategoryDefaultValue() . "',
					'" . $this->getInvoiceTypeDefaultValue() . "',	'" . $businessPartnerId . "',	 	'"
                        . $this->getBusinessPartnerContactDefaultValue() . "',
					'" . $this->ledgerService->getCountryId() . "',				'" . $this->getInvoiceProjectDefaultValue() . "', '"
                        . $this->getPaymentTermsDefaultValue() . "',
					'" . $this->getWarehouseDefaultValue() . "',	'1',						 	'"
                        . $this->getTaxDefaultValue() . "',
					' ',										'" . $documentNumber . "',			' ',
					'' ,									' ',							0,
					0,											0,												0,
					'" . $invoiceDate . "',									'0000-00-00',					0,
					0,									1,								0,
					0,									0,								1,
					0,											0,								0,
					'" . $this->getStaffId() . "',					" . $this->getExecuteTime() . ",            '".$chartOfAccountId."')";
            }
            try {
                $this->q->create($sql);
            } catch (\Exception $e) {
                header('Content-Type:application/json; charset=utf-8');
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
            $invoiceId = $this->q->lastInsertId('invoice');
        }
        return $invoiceId;
    }

    /**
     * Return Invoice Category Default Value
     * @return int
     */
    public function getInvoiceCategoryDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $invoiceCategoryId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `invoiceCategoryId`
         FROM        	`invoicecategory`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [invoiceCategoryId],
         FROM        [invoiceCategory]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      INVOICECATEGORYID AS \"invoiceCategoryId\",
         FROM        INVOICECATEGORY
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $invoiceCategoryId = $row['invoiceCategoryId'];
        }
        return $invoiceCategoryId;
    }

    /**
     * Return Invoice Type Default Value
     * @return int
     */
    public function getInvoiceTypeDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $invoiceTypeId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `invoiceTypeId`
         FROM        	`invoicetype`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [invoiceTypeId],
         FROM        [invoiceType]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      INVOICETYPEID AS \"invoiceTypeId\",
         FROM        INVOICETYPE
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $invoiceTypeId = $row['invoiceTypeId'];
        }
        return $invoiceTypeId;
    }

    /**
     * Return Business Partner Contact Default Value
     * @return int
     */
    public function getBusinessPartnerContactDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $businessPartnerContactId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `businessPartnerContactId`
         FROM        	`businesspartnercontact`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [businessPartnerContactId],
         FROM        [businessPartnerContact]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      BUSINESSPARTNERCONTACTID AS \"businessPartnerContactId\",
         FROM        BUSINESSPARTNERCONTACT
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $businessPartnerContactId = $row['businessPartnerContactId'];
        }
        return $businessPartnerContactId;
    }

    /**
     * Return Invoice Project Default Value
     * @return int
     */
    public function getInvoiceProjectDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $invoiceProjectId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `invoiceProjectId`
         FROM        	`invoiceproject`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [invoiceProjectId],
         FROM        [invoiceProject]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      INVOICEPROJECTID AS \"invoiceProjectId\",
         FROM        INVOICEPROJECT
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $invoiceProjectId = $row['invoiceProjectId'];
        }
        return $invoiceProjectId;
    }

    /**
     * Return Payment Terms Default Value
     * @return int
     */
    public function getPaymentTermsDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $paymentTermsId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `paymentTermsId`
         FROM        	`paymentterms`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [paymentTermsId],
         FROM        [paymentTerms]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      PAYMENTTERMSID AS \"paymentTermsId\",
         FROM        PAYMENTTERMS
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $paymentTermsId = $row['paymentTermsId'];
        }
        return $paymentTermsId;
    }

    /**
     * Return Warehouse Default Value
     * @return int
     */
    public function getWarehouseDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $warehouseId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `warehouseId`
         FROM        	`warehouse`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [warehouseId],
         FROM        [warehouse]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      WAREHOUSEID AS \"warehouseId\",
         FROM        WAREHOUSE
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $warehouseId = $row['warehouseId'];
        }
        return $warehouseId;
    }

    /**
     * Return Tax Default Value
     * @return int
     */
    public function getTaxDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $taxId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `taxId`
         FROM        	`tax`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [taxId],
         FROM        [tax]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      TAXID AS \"taxId\",
         FROM        TAX
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $taxId = $row['taxId'];
        }
        return $taxId;
    }

    /**
     * Set Invoice Detail
     * @param int         $invoiceId           Invoice
     * @param int         $businessPartnerId   Business Partner / Customer / Vendor/Shipper
     * @param int         $chartOfAccountId    Chart Of Account
     * @param float       $invoiceDetailAmount Amount / Figure
     * @param string      $documentNumber      Document Number
     * @param string      $journalNumber       Journal / Posting Number
     * @param null|int    $invoiceDetailId
     * @param null|string $invoiceDetailDescription
     * @return int|null
     * @throws \Exception
     */
    public function setInvoiceDetail(
    $invoiceId, $businessPartnerId, $chartOfAccountId, $invoiceDetailAmount, $documentNumber, $journalNumber, $invoiceDetailId = null, $invoiceDetailDescription = null
    ) {
        $sql = null;
        if (intval($invoiceDetailId) > 0) {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
				UPDATE 	`invoicedetail`
				SET 	`businessPartnerId`			=	'" . $businessPartnerId . "',
						`chartOfAccountId`			=	'" . $chartOfAccountId . "',
						`invoiceDetailDescription`	=	'" . $invoiceDetailDescription . "',
						`invoiceDetailAmount`		=	'" . $invoiceDetailAmount . "',
						`isNew`						=	0,
						`isUpdate`					=	1,
						`executeBy`					=	'" . $this->getStaffId() . "',
						`executeTime`				=	" . $this->getExecuteTime() . "
				WHERE 	`invoiceDetailId`			=	'" . $invoiceDetailId . "'
				";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
				UPDATE 	[invoicedetail]
				SET 	[businessPartnerId]			=	'" . $businessPartnerId . "',
						[chartOfAccountId]			=	'" . $chartOfAccountId . "',
						[invoiceDetailDescription]	=	'" . $invoiceDetailDescription . "',
						[invoiceDetailAmount]		=	'" . $invoiceDetailAmount . "',
						[isNew]						=	0,
						[isUpdate]					=	1,
						[executeBy]					=	'" . $this->getStaffId() . "',
						[executeTime]				=	" . $this->getExecuteTime() . "
				WHERE 	[invoiceDetailId]			=	'" . $invoiceDetailId . "'
				";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
				UPDATE 	INVOICEDETAIL
				SET 	BUSINESSPARTNERID			=	'" . $businessPartnerId . "',
						CHARTOFACCOUNTID			=	'" . $chartOfAccountId . "',
						INVOICEDETAILDESCRIPTION	=	'" . $invoiceDetailDescription . "',
						INVOICEDETAILAMOUNT			=	'" . $invoiceDetailAmount . "',
						ISNEW						=	0,
						ISUPDATE					=	1,
						EXECUTEBY					=	'" . $this->getStaffId() . "',
						EXECUTETIME					=	" . $this->getExecuteTime() . "
				WHERE 	INVOICEDETAILID				=	'" . $invoiceDetailId . "'
				";
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
		INSERT INTO `invoicedetail`(
					`companyId`, 											
					`invoiceId`, 							
					`chartOfAccountId`, 				
					`invoiceDetailDescription`,				
					`invoiceDetailAmount`,
					`isDefault`, 							
					`isNew`,
					`isDraft`,
					`isUpdate`,
					`isDelete`,
					`isActive`,				
					`isApproved`,
					`isReview`,
					`isPost`,
					`executeBy`,
					`executeTime`,
					`businessPartnerId`,
					`documentNumber`,
					`journalNumber`
		) VALUES (
					'" . $this->getCompanyId() . "',
					'" . $invoiceId . "',						
					'" . $chartOfAccountId . "',
					'" . $invoiceDetailDescription . "',
					'" . $invoiceDetailAmount . "',
					0,										1,									0,
					0,										0,									1,
					0,										0,									0,
					'" . $this->getStaffId() . "',				" . $this->getExecuteTime() . ",		'"
                        . $businessPartnerId . "',
					'" . $documentNumber . "','" . $journalNumber . "');";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
		INSERT INTO [invoicedetail](
					[invoiceDetailId], 						[companyId], 						[invoiceProjectId],
					[invoiceId], 							[chartOfAccountId], 				[taxId],
					[discountId], 							[invoiceDetailDescription],			[invoiceDetailPrincipalAmount],
					[invoiceDetailInterestAmount], 			[invoiceDetailDiscountAmount], 		[invoiceDetailAmount],
					[isDefault], 							[isNew], 							[isDraft],
					[isUpdate], 							[isDelete], 						[isActive],
					[isApproved], 							[isReview], 						[isPost],
					[executeBy], 							[executeTime],						[businessPartnerId],
                    [documentNumber],	                   [journalNumber]
		) VALUES (
					null,									'" . $this->getCompanyId() . "',		'"
                        . $this->getInvoiceProjectDefaultValue() . "',
					'" . $invoiceId . "',						'" . $chartOfAccountId . "',			'"
                        . $this->getTaxDefaultValue() . "',
					'" . $this->getDiscountDefaultValue() . "',	'" . $invoiceDetailDescription . "',	0,
					0,										0,									'"
                        . $invoiceDetailAmount . "',
					0,										1,									0,
					0,										0,									1,
					0,										0,									0,
					'" . $this->getStaffId() . "',				" . $this->getExecuteTime() . ",		'"
                        . $businessPartnerId . "',
                    '" . $documentNumber . "',	'" . $journalNumber . "');";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
		INSERT INTO INVOICEDETAIL(
					INVOICEDETAILID, 						COMPANYID, 						INVOICEPROJECTID,
					INVOICEID, 							CHARTOFACCOUNTID, 				TAXID,
					DISCOUNTID, 							INVOICEDETAILDESCRIPTION,			INVOICEDETAILPRINCIPALAMOUNT,
					INVOICEDETAILINTERESTAMOUNT, 			INVOICEDETAILDISCOUNTAMOUNT, 		INVOICEDETAILAMOUNT,
					ISDEFAULT, 							ISNEW, 							ISDRAFT,
					ISUPDATE, 							ISDELETE, 						ISACTIVE,
					ISAPPROVED, 							ISREVIEW, 						ISPOST,
					EXECUTEBY, 							     EXECUTETIME,					BUSINESSPARTNERID,
                                        JOURNALNUMBER
		) VALUES (
					null,									'" . $this->getCompanyId() . "',		'"
                        . $this->getInvoiceProjectDefaultValue() . "',
					'" . $invoiceId . "',						'" . $chartOfAccountId . "',			'"
                        . $this->getTaxDefaultValue() . "',
					'" . $this->getDiscountDefaultValue() . "',	'" . $invoiceDetailDescription . "',	0,
					0,										0,									'"
                        . $invoiceDetailAmount . "',
					0,										1,									0,
					0,										0,									1,
					0,										0,									0,
					'" . $this->getStaffId() . "',				" . $this->getExecuteTime() . ",		'"
                        . $businessPartnerId . "',
                    '" . $documentNumber . "','" . $journalNumber . "');";
            }
            try {
                $this->q->create($sql);
            } catch (\Exception $e) {
                header('Content-Type:application/json; charset=utf-8');
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
            $invoiceDetailId = $this->q->lastInsertId('invoiceDetail');
        }
        return $invoiceDetailId;
    }

    /**
     * Return Discount Default Value
     * @return int
     */
    public function getDiscountDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $discountId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `discountId`
         FROM        	`discount`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [discountId],
         FROM        [discount]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      DISCOUNTID AS \"discountId\",
         FROM        DISCOUNT
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $discountId = $row['discountId'];
        }
        return $discountId;
    }

    /**
     * Set Collection Detail
     * @param int    $collectionId           Collection
     * @param int    $businessPartnerId      Business Partner / Customer / Vendor / Shipper
     * @param int    $chartOfAccountId       Chart Of Account
     * @param float  $collectionDetailAmount Amount
     * @param string $documentNumber         Document Number
     * @param string $journalNumber          Journal/Posting Number
     * @param null   $collectionDetailId
     * @return int|null
     * @throws \Exception
     */
    public function setCollectionDetail(
    $collectionId, $businessPartnerId, $chartOfAccountId, $collectionDetailAmount, $documentNumber, $journalNumber, $collectionDetailId = null
    ) {
        $sql = null;
        if (intval($collectionDetailId) > 0) {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
				UPDATE 	`collectiondetail`
				SET 	`businessPartnerId`				=	'" . $businessPartnerId . "',
						`chartOfAccountId`				=	'" . $chartOfAccountId . "',
						`collectionDetailAmount`		=	'" . $collectionDetailAmount . "',
						`isNew`							=	0,
						`isUpdate`						=	1,
						`executeBy`						=	'" . $this->getStaffId() . "',
						`executeTime`					=	" . $this->getExecuteTime() . "
				WHERE 	`collectionDetailId`			=	'" . $collectionDetailId . "'
				";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
				UPDATE 	[collectionDetail]
				SET 	[businessPartnerId]				=	'" . $businessPartnerId . "',
						[chartOfAccountId]				=	'" . $chartOfAccountId . "',
						[collectionDetailAmount]		=	'" . $collectionDetailAmount . "',
						[isNew]							=	0,
						[isUpdate]						=	1,
						[executeBy]						=	'" . $this->getStaffId() . "',
						[executeTime]					=	" . $this->getExecuteTime() . "
				WHERE 	[collectionDetailId]			=	'" . $collectionDetailId . "'
				";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
				UPDATE 	COLLECTIONDETAIL
				SET 	BUSINESSPARTNERID				=	'" . $businessPartnerId . "',
						CHARTOFACCOUNTID				=	'" . $chartOfAccountId . "',
						COLLECTIONDETAILAMOUNT			=	'" . $collectionDetailAmount . "',
						ISNEW							=	0,
						ISUPDATE						=	1,
						EXECUTEBY						=	'" . $this->getStaffId() . "',
						EXECUTETIME						=	" . $this->getExecuteTime() . "
				WHERE 	COLLECTIONDETAILID				=	'" . $collectionDetailId . "'
				";
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
		INSERT INTO `collectiondetail`(
					`collectionDetailId`, 			`companyId`, 						`collectionId`,
					`chartOfAccountId`, 			`countryId`, 						`businessPartnerId`,
					`documentNumber`, 				`journalNumber`, 					`collectionDetailAmount`,
					`isDefault`, 					`isNew`, 							`isDraft`,
					`isUpdate`, 					`isDelete`, 						`isActive`,
					`isApproved`, 					`isReview`, 						`isPost`,
					`executeBy`, 					`executeTime`
		) VALUES (
					null,							'" . $this->getCompanyId() . "',		'" . $collectionId . "',
					'" . $chartOfAccountId . "',		'" . $this->ledgerService->getCountryId() . "',		'" . $businessPartnerId
                        . "',
					'" . $documentNumber . "',			'" . $journalNumber . "',				'"
                        . $collectionDetailAmount . "',
					0,								1,									0,
					0,								0,									1,
					0,								0,									0,
					'" . $this->getStaffId() . "',		" . $this->getExecuteTime() . ")";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
		INSERT INTO [collectionDetail](
					[collectionDetailId], 			[companyId], 						[collectionId],
					[chartOfAccountId], 			[countryId], 						[businessPartnerId],
					[documentNumber], 				[journalNumber], 					[collectionDetailAmount],
					[isDefault], 					[isNew], 							[isDraft],
					[isUpdate], 					[isDelete], 						[isActive],
					[isApproved], 					[isReview], 						[isPost],
					[executeBy], 					[executeTime]
		) VALUES (
					null,							'" . $this->getCompanyId() . "',		'" . $collectionId . "',
					'" . $chartOfAccountId . "',		'" . $this->ledgerService->getCountryId() . "',		'" . $businessPartnerId
                        . "',
					'" . $documentNumber . "',			'" . $journalNumber . "',				'"
                        . $collectionDetailAmount . "',
					0,								1,									0,
					0,								0,									1,
					0,								0,									0,
					'" . $this->getStaffId() . "',		" . $this->getExecuteTime() . ")";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
		INSERT INTO COLLECTIONDETAIL(
					COLLECTIONDETAILID, 			COMPANYID, 						COLLECTIONID,
					CHARTOFACCOUNTID, 			COUNTRYID, 						BUSINESSPARTNERID,
					DOCUMENTNUMBER, 				JOURNALNUMBER, 					COLLECTIONDETAILAMOUNT,
					ISDEFAULT, 					ISNEW, 							ISDRAFT,
					ISUPDATE, 					ISDELETE, 						ISACTIVE,
					ISAPPROVED, 					ISREVIEW, 						ISPOST,
					EXECUTEBY, 					EXECUTETIME
		) VALUES (
					null,							'" . $this->getCompanyId() . "',		'" . $collectionId . "',
					'" . $chartOfAccountId . "',		'" . $this->ledgerService->getCountryId() . "',		'" . $businessPartnerId
                        . "',
					'" . $documentNumber . "',			'" . $journalNumber . "',				'"
                        . $collectionDetailAmount . "',
					0,								1,									0,
					0,								0,									1,
					0,								0,									0,
					'" . $this->getStaffId() . "',	" . $this->getExecuteTime() . ")";
            }
            try {
                $this->q->create($sql);
            } catch (\Exception $e) {
                header('Content-Type:application/json; charset=utf-8');
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
            $collectionDetailId = $this->q->lastInsertId('collectionDetail');
        }
        return $collectionDetailId;
    }

    /**
     * Return Bank Default Value
     * @return int
     */
    public function getPettyCashDefaultAccount() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $chartOfAccountId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `chartOfAccountId`
         FROM        	`bank`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isPettyCash` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [chartOfAccountId],
         FROM        [bank]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isPettyCash] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      CHARTOFACCOUNTID AS \"chartOfAccountId\",
         FROM        BANK
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         AND    	  	  ISPETTYCASH=	   1
         AND 		  ROWNUM	  =	   1";
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
            $chartOfAccountId = $row['chartOfAccountId'];
        }
        return $chartOfAccountId;
    }

    /**
     * Return Bank Default Value
     * @return int
     */
    public function getIncomeDefaultAccount() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $chartOfAccountId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `chartOfAccountId`
         FROM        `chartOfAccount`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	 `isDefaultIncome` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      TOP 1 [chartOfAccountId],
			FROM        [chartOfAccount]
			WHERE       [isActive]  			=   1
			AND			[companyId] 			=	'" . $this->getCompanyId() . "'
			AND			isDefaultIncome]	=	1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT	CHARTOFACCOUNTID
			FROM	CHARTOFACCOUNT
			WHERE	ISACTIVE`  =   1
			AND		COMPANYID` =  '" . $this->getCompanyId() . "'
			AND		ISDEFAULTINCOME	=	   1
			AND		ROWNUM	  			=	   1";
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
            $chartOfAccountId = $row['chartOfAccountId'];
        }
        return $chartOfAccountId;
    }

    /**
     * Return Bank Default Value
     * @return int
     */
    public function getDebtorDefaultAccount() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $chartOfAccountId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT  `chartOfAccountId`
			FROM	`chartOfAccount`
			WHERE	`isActive`  =   1
			AND		`companyId` =   '" . $this->getCompanyId() . "'
			AND		`isDebtor` =	  1
			LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT  TOP 1
					[chartOfAccountId],
			FROM	[chartOfAccount]
			WHERE	[isActive]		=   1
			AND		[companyId]		=	'" . $this->getCompanyId() . "'
			AND		[isDebtor]	=	1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT	CHARTOFACCOUNTID
			FROM	CHARTOFACCOUNT
			WHERE	ISACTIVE`  	=   1
			AND		COMPANYID` 	=  	'" . $this->getCompanyId() . "'
			AND		ISDEBTOR	=	1
			AND		ROWNUM	  	=	1";
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
            $chartOfAccountId = $row['chartOfAccountId'];
        }
        return $chartOfAccountId;
    }

    /**
     * Set Collection Allocation
     * @param int      $collectionId               Collection
     * @param int      $invoiceId                  Invoice Primary Key
     * @param int      $businessPartnerId          Business Partner / Customer / Vendor / Shipper Primart Key
     * @param int      $collectionAllocationAmount Amount / Value
     * @param null|int $collectionAllocationId     Primary Key
     * @return null|void
     * @throws \Exception
     */
    public function setCollectionAllocation(
    $collectionId, $invoiceId, $businessPartnerId, $collectionAllocationAmount, $collectionAllocationId = null
    ) {
        $sql = null;
        if (intval($collectionAllocationId) > 0) {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
				UPDATE 	`collectionallocation`
				SET    	`collectionAllocationAmount`		=	'" . $collectionAllocationAmount . "',
						`isNew`								=	0,
						`isUpdate`							=	1,
						`executeBy`							=	'" . $this->getStaffId() . "',
						`executeTime`						=	" . $this->getExecuteTime() . "
				WHERE	`collectionallocationId`			=	'" . $collectionAllocationId . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
				UPDATE 	[collectionAllocation]
				SET    	[collectionAllocationAmount]		=	'" . $collectionAllocationAmount . "',
						[isNew]								=	0,
						[isUpdate]							=	1,
						[executeBy]							=	'" . $this->getStaffId() . "',
						[executeTime]						=	" . $this->getExecuteTime() . "
				WHERE	[collectionAllocationId]			=	'" . $collectionAllocationId . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
				UPDATE 	COLLECTIONALLOCATION
				SET    	COLLECTIONALLOCATIONAMOUNT		=	'" . $collectionAllocationAmount . "',
						ISNEW								=	0,
						ISUPDATE							=	1,
						EXECUTEBY							=	'" . $this->getStaffId() . "',
						EXECUTETIME							=	" . $this->getExecuteTime() . "
				WHERE	COLLECTIONALLOCATIONID			=	'" . $collectionAllocationId . "'";
            }
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
				INSERT INTO `collectionallocation`(
					`collectionAllocationId`, 			`companyId`,
					`businessPartnerId`, 					`countryId`,
					`invoiceId`, 					`collectionId`,
					`collectionAllocationAmount`, 		`isDefault`,
					`isNew`, 								`isDraft`,
					`isUpdate`, 							`isDelete`,
					`isActive`, 							`isApproved`,
					`isReview`, 							`isPost`,
					`executeBy`, 							`executeTime`
				) VALUES (
					null,									'" . $this->getCompanyId() . "',
					'" . $businessPartnerId . "',				'" . $this->ledgerService->getCountryId() . "',
					'" . $invoiceId . "',				'" . $collectionId . "',
					'" . $collectionAllocationAmount . "',	0,
					1,										0,
					0,										0,
					1,										0,
					0,										0,
					'" . $this->getStaffId() . "',				" . $this->getExecuteTime() . ")";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
				INSERT INTO [collectionAllocation](
					[collectionAllocationId], 			[companyId],
					[businessPartnerId], 					[countryId],
					[invoiceId], 					[collectionId],
					[collectionAllocationAmount], 		[isDefault],
					[isNew], 								[isDraft],
					[isUpdate], 							[isDelete],
					[isActive], 							[isApproved],
					[isReview], 							[isPost],
					[executeBy], 							[executeTime]
				) VALUES (
					null,									'" . $this->getCompanyId() . "',
					'" . $businessPartnerId . "',				'" . $this->ledgerService->getCountryId() . "',
					'" . $invoiceId . "',				'" . $collectionId . "',
					'" . $collectionAllocationAmount . "',	0,
					1,										0,
					0,										0,
					1,										0,
					0,										0,
					'" . $this->getStaffId() . "',				" . $this->getExecuteTime() . ")";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
				INSERT INTO PAYMENTVOUCHERALLOCATION(
					PAYMENTVOUCHERALLOCATIONID, 			COMPANYID,
					BUSINESSPARTNERID, 						COUNTRYID,
					INVOICEID, 						COLLECTIONID,
					COLLECTIONALLOCATIONAMOUNT, 		ISDEFAULT,
					ISNEW, 									ISDRAFT,
					ISUPDATE, 								ISDELETE,
					ISACTIVE, 								ISAPPROVED,
					ISREVIEW, 								ISPOST,
					EXECUTEBY, 								EXECUTETIME
				) VALUES (
					null,									'" . $this->getCompanyId() . "',
					'" . $businessPartnerId . "',				'" . $this->ledgerService->getCountryId() . "',
					'" . $invoiceId . "',				'" . $collectionId . "',
					'" . $collectionAllocationAmount . "',	0,
					1,										0,
					0,										0,
					1,										0,
					0,										0,
					'" . $this->getStaffId() . "',				" . $this->getExecuteTime() . ")";
            }
            try {
                $this->q->create($sql);
            } catch (\Exception $e) {
                header('Content-Type:application/json; charset=utf-8');
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
            $collectionAllocationId = $this->q->lastInsertId('collectionAllocation');
        }
        return $collectionAllocationId;
    }

    /**
     * Return Invoice Process Default Value
     * @return int
     */
    public function getInvoiceProcessDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $invoiceProcessId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `invoiceProcessId`
         FROM        	`invoiceprocess`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [invoiceProcessId],
         FROM        [invoiceProcess]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      INVOICEPROCESSID AS \"invoiceProcessId\",
         FROM        INVOICEPROCESS
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $invoiceProcessId = $row['invoiceProcessId'];
        }
        return $invoiceProcessId;
    }

    /**
     * Post Collection To General Ledger
     * @param int    $collectionId Collection Primary Key
     * @param int    $leafId Leaf Primary key
     * @param string $leafName Leaf Name
     * @throws \Exception
     */
    public function setPosting($collectionId, $leafId, $leafName) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  *
            FROM    `collection`
            WHERE   `collectionId` IN (" . $collectionId . ")
            AND     `isPost`    =   0
            AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  *
            FROM    [collection]
            WHERE   [collectionId] IN (" . $collectionId . ")
            AND     [isPost] =0
            AND     [companyId] =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  *
            FROM    COLLECTION
            WHERE   COLLECTIONID IN (" . $collectionId . ")
            AND     ISPOST      =   1
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
                $collectionId = $row['collectionId'];
                $this->setCollectionStatusTracking($collectionId, $this->getCollectionStatusId(self::TRANSFER_TO_GL));
            }
        }
        $journalNumber = $this->getDocumentNumber('GLPT');
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  *
            FROM    `collectiondetail`
            JOIN    `collection`
            USING   (`companyId`,`collectionId`)
            WHERE   `collectionId` IN (" . $collectionId . ")
            ORDER BY `collectionId";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  *
            FROM    [collectionDetail]
            WHERE   [collectionId] IN (" . $collectionId . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  *
            FROM    COLLECTIONDETAIL
            WHERE   COLLECTIONID IN (" . $collectionId . ")";
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

                $invoiceDueDate=null;
                $collectionId = $row['collectionId'];
                $referenceTableNameId = $row['collectionId'];
                $referenceTableNameDetailId = $row['collectionDetailId'];
                $this->ledgerService->setCashBookLedger($businessPartnerId, $chartOfAccountId,$documentNumber,$documentDate, $localAmount, $description, $leafId, $collectionId);

                $this->ledgerService->setInvoiceLedger($businessPartnerId,$chartOfAccountId,$documentNumber,$documentDate, $invoiceDueDate, $description, $localAmount, $leafId,0,0,0,0,0,$collectionId );

                $this->ledgerService->setGeneralLedger($leafId, $leafName, $businessPartnerId, $chartOfAccountId, $documentNumber, $journalNumber, $documentDate, $localAmount, $description, $module, $tableName, $tableNameDetail, $tableNameId, $tableNameDetailId,$referenceTableNameId,$referenceTableNameDetailId);
            }
        }
        $this->setCollectionPosted($collectionId);
    }
    /**
     * Set Collection Status
     * @param int $collectionId Collection Primary Key
     * @param int $collectionStatusId Collection Status Primary Key
     * @return void
     */
    public function setCollectionStatusTracking($collectionId, $collectionStatusId) {
        $sql = null;
        $collectionTrackingDuration = 0;
        // check if exist previous payment voucher transaction and compare with the current day.
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  DATEDIFF(NOW(),`collectionTrackingDate`) AS `collectionTrackingDuration`
            FROM   `collectiontracking`
            WHERE  `collectionId` ='" . $collectionId . "'
			DESC	LIMIT 1
            ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT [executeTime]
            FROM   [collection]
            WHERE  [collectionId] ='" . $collectionId . "'
            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT EXECUTETIME
            FROM   COLLECTION
            WHERE  COLLECTIONID ='" . $collectionId . "'
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
            $collectionTrackingDuration = $row['collectionTrackingDuration'];
        }

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `collectiontracking`(
                `collectionTrackingId`,                   `companyId`,
                `collectionId`,                           `collectionStatusId`,
                `collectionTrackingDuration`,              `isDefault`,
                `isNew`,                                  `isDraft`,
                `isUpdate`,                               `isDelete`,
                `isActive`,                               `isApproved`,
                `isReview`,                               `isPost`,
                `executeBy`,                              `executeTime`,
				`collectionTrackingDate`
            ) VALUES (
                null,                                   " . $this->getCompanyId() . ",
                '" . $collectionId . "',                 " . $collectionStatusId . ",
                '" . $collectionTrackingDuration . "',           0,
                1,                                       0,
                0,                                       0,
                1,                                       0,
                0,                                       0,
                '" . $this->getStaffId() . "',               " . $this->getExecuteTime() . ",
				NOW()
             )
            ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            INSERT INTO [collectionTracking](
                [collectionTrackingId],               [companyId],
                [collectionId],                       [collectionStatusId],
                [collectionTrackingDuration],                       [isDefault],
                [isNew],                                  [isDraft],
                [isUpdate],                               [isDelete],
                [isActive],                               [isApproved],
                [isReview],                               [isPost],
                [executeBy],                              [executeTime]
            ) VALUES (
                null,                                   " . $this->getCompanyId() . ",
                '" . $collectionId . "',                 " . $collectionStatusId . ",
                '" . $collectionTrackingDuration . "',           0,
                1,                                       0,
                0,                                       0,
                1,                                       0,
                0,                                       0,
                '" . $this->getStaffId() . "',               " . $this->getExecuteTime() . ")
            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            INSERT INTO COLLECTIONTRACKING (
                COLLECTIONTRACKINGID,                   COMPANYID,
                COLLECTIONID,                           COLLECTIONTATUSID,
                COLLECTIONTRACKINGDURATION,                     ISDEFAULT,
                ISNEW,                                  ISDRAFT,
                ISUPDATE,                               ISDELETE,
                ISACTIVE,                               ISAPPROVED,
                ISREVIEW,                               ISPOST,
                EXECUTEBY,                              EXECUTETIME
            ) VALUES (
                null,                                   " . $this->getCompanyId() . ",
                '" . $collectionId . "',                 " . $collectionStatusId . ",
                '" . $collectionTrackingDuration . "',           0,
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Internal Collection Tracking System
     * @param string $collectionStatusCode
     * @return int
     */
    private function getCollectionStatusId($collectionStatusCode) {
        $sql = null;
        $collectionStatusId = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `collectionStatusId`
            FROM    `collectionstatus`
            WHERE   `collectionStatusCode`  =   '" . $collectionStatusCode . "'
            AND     `companyId`             =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  [collectionStatusId]
            FROM    [collectionStatus]
            WHERE   [collectionStatusCode]  =   '" . $collectionStatusCode . "'
            AND     [companyId]             =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  COLLECTIONSTATUSID
            FROM    COLLECTIONSTATUS
            WHERE   COLLECTIONSTATUSCODE    =   '" . $collectionStatusCode . "'
            AND     COMPANYID               =   '" . $this->getCompanyId() . "'";
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
            $collectionStatusId = $row['collectionStatusId'];
        }
        return $collectionStatusId;
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
     * Update Collection Posted Flag
     * @param int $collectionId Collection Primary Key
     */
    private function setCollectionPosted($collectionId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE  `collection`
            SET     `isPost`        =  1,
                    `executeBy`     =   '" . $this->getStaffId() . "',
                    `executeTime`   =   " . $this->getExecuteTime() . "
            WHERE   `collectionId` IN (" . $collectionId . ")";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE  [collection]
            SET     [isPost]        =  1,
                    [executeBy]     =   '" . $this->getStaffId() . "',
                    [executeTime]   =   " . $this->getExecuteTime() . "
            WHERE   [collectionId] IN (" . $collectionId . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE  COLLECTION
            SET     ISPOST        =  1,
                    EXECUTEBY     =   '" . $this->getStaffId() . "',
                    EXECUTETIME   =   " . $this->getExecuteTime() . "
            WHERE   COLLECTIONID IN (" . $collectionId . ")";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Set New Fast Business Partner.Company Address And shipping address will be same as defaulted.
     * @param string $businessPartnerCompany Company
     * @param string $businessPartnerAddress Address
     * return int $businessPartnerId Business Partner Primary Key
     * @throws \Exception
     */
    public function setNewBusinessPartner($businessPartnerCompany, $businessPartnerAddress) {

        header('Content-Type:application/json; charset=utf-8');
        $start = microtime(true);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            try {
                $this->q->fast($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			INSERT INTO `businesspartner`
			 (
                `companyId`,
                `businessPartnerCategoryId`,
                `businessPartnerOfficeCountryId`,
                `businessPartnerOfficeStateId`,
                `businessPartnerOfficeCityId`,
                `businessPartnerShippingCountryId`,
                `businessPartnerShippingStateId`,
                `businessPartnerShippingCityId`,
                `businessPartnerCompany`,
                `businessPartnerOfficeAddress`,
                `businessPartnerShippingAddress`,
                `isDefault`,
                `isNew`,
                `isDraft`,
                `isUpdate`,
                `isDelete`,
                `isActive`,
                `isApproved`,
                `isReview`,
                `isPost`,
                `executeBy`,
                `executeTime`
 			  ) VALUES (
 			    '" . $this->getCompanyId() . "',
 			    '" . $this->getFastBusinessPartnerCategory() . "',

 			    '" . $this->getBusinessPartnerShippingCountryDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingStateDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingCityDefaultValue() . "',

 			    '" . $this->getBusinessPartnerShippingCountryDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingStateDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingCityDefaultValue() . "',

				'" . $businessPartnerCompany . "',
				'" . $businessPartnerAddress . "',
				'" . $businessPartnerAddress . "',

				 0,
                 1,
                 0,
                 0,
                 0,
                 1,
                 0,
                 0,
                 0,
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
			 )
			";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			INSERT INTO [businessPartner]
			 (
                [companyId],
                [businessPartnerCategoryId],
                [businessPartnerOfficeCountryId],
                [businessPartnerOfficeStateId],
                [businessPartnerOfficeCityId],
                [businessPartnerShippingCountryId],
                [businessPartnerShippingStateId],
                [businessPartnerShippingCityId],
                [businessPartnerCompany],
                [businessPartnerOfficeAddress],
                [businessPartnerShippingAddress],
                [isDefault],
                [isNew],
                [isDraft],
                [isUpdate],
                [isDelete],
                [isActive],
                [isApproved],
                [isReview],
                [isPost],
                [executeBy],
                [executeTime]
 			  ) VALUES (
 			    '" . $this->getCompanyId() . "',
 			    '" . $this->getFastBusinessPartnerCategory() . "',

 			    '" . $this->getBusinessPartnerShippingCountryDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingStateDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingCityDefaultValue() . "',

 			    '" . $this->getBusinessPartnerShippingCountryDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingStateDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingCityDefaultValue() . "',

				'" . $businessPartnerCompany . "',
				'" . $businessPartnerAddress . "',
				'" . $businessPartnerAddress . "',

				 0,
                 1,
                 0,
                 0,
                 0,
                 1,
                 0,
                 0,
                 0,
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
			 )
			";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			INSERT INTO BUSINESSPARTNER
			 (
                COMPANYID,
                BUSINESSPARTNERCATEGORYID,
                BUSINESSPARTNEROFFICECOUNTRYID,
                BUSINESSPARTNEROFFICESTATEID,
                BUSINESSPARTNEROFFICECITYID,
                BUSINESSPARTNERSHIPPINGCOUNTRYID,
                BUSINESSPARTNERSHIPPINGSTATEID,
                BUSINESSPARTNERSHIPPINGCITYID,
                BUSINESSPARTNERCOMPANY,
                BUSINESSPARTNEROFFICEADDRESS,
                BUSINESSPARTNERSHIPPINGADDRESS,
                ISDEFAULT,
                ISNEW,
                ISDRAFT,
                ISUPDATE,
                ISDELETE,
                ISACTIVE,
                ISAPPROVED,
                ISREVIEW,
                ISPOST,
                EXECUTEBY,
                EXECUTETIME
 			  ) VALUES (
 			    '" . $this->getCompanyId() . "',
 			    '" . $this->getFastBusinessPartnerCategory() . "',

 			    '" . $this->getBusinessPartnerShippingCountryDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingStateDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingCityDefaultValue() . "',

 			    '" . $this->getBusinessPartnerShippingCountryDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingStateDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingCityDefaultValue() . "',

				'" . $businessPartnerCompany . "',
				'" . $businessPartnerAddress . "',
				'" . $businessPartnerAddress . "',

				 0,
                 1,
                 0,
                 0,
                 0,
                 1,
                 0,
                 0,
                 0,
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
			 )
			";
        }

        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $businessPartnerId = $this->q->lastInsertId('businessPartner');
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => true,
                    "message" => $this->t['newRecordTextLabel'],
                    "staffName" => $_SESSION['staffName'],
                    "executeTime" => date('d-m-Y H:i:s'),
                    "businessPartnerId" => $businessPartnerId,
                    "time" => $time
                )
        );
        exit();
    }

    /**
     * Return Business Partner Category
     * @return int
     */
    private function getFastBusinessPartnerCategory() {
        $businessPartnerCategoryCode = 'FSCS';
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $businessPartnerCategoryId = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT		`businessPartnerCategoryId`
			FROM        `businesspartnercategory`
			WHERE       `isActive`  			        =   1
			AND         `companyId` 			        =   '" . $this->getCompanyId() . "'
			AND		 	`businessPartnerCategoryCode`	=	'" . $businessPartnerCategoryCode . "'
			LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      TOP 1 [businessPartnerCategoryId]
			FROM        [businessPartnerCategory]
			WHERE       [isActive]  			        =   1
			AND         [companyId] 			        =   '" . $this->getCompanyId() . "'
			AND		 	[businessPartnerCategoryCode]	=	'" . $businessPartnerCategoryCode . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT		BUSINESSPARTNERCATEGORYID AS \"businessPartnerCategoryId\"
			FROM        BUSINESSPARTNERCATEGORY
			WHERE       ISACTIVE  			        =   1
			AND         COMPANYID 			        =   '" . $this->getCompanyId() . "'
			AND		 	BUSINESSPARTNERCATEGORYCODE	=	'" . $businessPartnerCategoryCode . "'
			AND 		ROWNUM	  			=	1";
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
            $businessPartnerCategoryId = $row['businessPartnerCategoryId'];
        }
        return $businessPartnerCategoryId;
    }

    /**
     * Return Business Partner Shipping Country Default Value
     * @return int
     */
    public function getBusinessPartnerShippingCountryDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $countryId = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `countryId`
         FROM        `country`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
          LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [countryId],
         FROM        [country]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      COUNTRYID AS \"countryId\",
         FROM        COUNTRY
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
            $countryId = $row['countryId'];
        }
        return $countryId;
    }

    /**
     * Return Business Partner Shipping State Default Value
     * @return int
     */
    public function getBusinessPartnerShippingStateDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $stateId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `stateId`
         FROM        `state`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [stateId],
         FROM        [state]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      STATEID AS \"stateId\",
         FROM        STATE
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
            $stateId = $row['stateId'];
        }
        return $stateId;
    }

    /**
     * Return Business Partner Shipping City Default Value
     * @return int
     */
    public function getBusinessPartnerShippingCityDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $cityId = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `cityId`
         FROM        `city`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [cityId],
         FROM        [city]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      CITYID AS \"cityId\",
         FROM        CITY
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
            $cityId = $row['cityId'];
        }
        return $cityId;
    }

    /**
     * Set New Fast Business Partner Contact Name
     * @param int         $businessPartnerId Business Partner Primary Key
     * @param null|string $businessPartnerContactName Name
     * @param null|string $businessPartnerContactPhone Phone
     * @param null|string $businessPartnerContactEmail Email
     * return @void
     * @throws \Exception
     */
    public function setNewBusinessPartnerContact(
    $businessPartnerId, $businessPartnerContactName, $businessPartnerContactPhone = null, $businessPartnerContactEmail = null
    ) {
        header('Content-Type:application/json; charset=utf-8');
        $start = microtime(true);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            try {
                $this->q->fast($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        // check back if business partner id exist or not
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			INSERT INTO `businesspartnercontact`
			(
				`companyId`,
				`businessPartnerId`,
				`businessPartnerContactName`,
				`businessPartnerContactPhone`,
				`businessPartnerContactEmail`,
                `isDefault`,
                `isNew`,
                `isDraft`,
                `isUpdate`,
                `isDelete`,
                `isActive`,
                `isApproved`,
                `isReview`,
                `isPost`,
                `executeBy`,
                `executeTime`
			) VALUES(
			    '" . $this->getCompanyId() . "',
				'" . $businessPartnerId . "',
				'" . $businessPartnerContactName . "',
				'" . $businessPartnerContactPhone . "',
				'" . $businessPartnerContactEmail . "',
				0,
                 1,
                 0,
                 0,
                 0,
                 1,
                 0,
                 0,
                 0,
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
			)
			";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			INSERT INTO [businessPartnerContact]
			(
				[companyId],
				[businessPartnerId],
				[businessPartnerContactName],
				[businessPartnerContactPhone],
				[businessPartnerContactEmail],
                [isDefault],
                [isNew],
                [isDraft],
                [isUpdate],
                [isDelete],
                [isActive],
                [isApproved],
                [isReview],
                [isPost],
                [executeBy],
                [executeTime]
			) VALUES(
				'" . $this->getCompanyId() . "',
				'" . $businessPartnerId . "',
				'" . $businessPartnerContactName . "',
				'" . $businessPartnerContactPhone . "',
				'" . $businessPartnerContactEmail . "',
				 0,
                 1,
                 0,
                 0,
                 0,
                 1,
                 0,
                 0,
                 0,
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
			)
			";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			INSERT INTO BUSINESSPARTNERCONTACT
			(
				COMPANYID,
				BUSINESSPARTNERID,
				BUSINESSPARTNERCONTACTNAME,
				BUSINESSPARTNERCONTACTPHONE,
				BUSINESSPARTNERCONTACTEMAIL,
                ISDEFAULT,
                ISNEW,
                ISDRAFT,
                ISUPDATE,
                ISDELETE,
                ISACTIVE,
                ISAPPROVED,
                ISREVIEW,
                ISPOST,
                EXECUTEBY,
                EXECUTETIME
			) VALUES(
				'" . $this->getCompanyId() . "',
				'" . $businessPartnerId . "',
				'" . $businessPartnerContactName . "',
				'" . $businessPartnerContactPhone . "',
				'" . $businessPartnerContactEmail . "',
			    0,
                 1,
                 0,
                 0,
                 0,
                 1,
                 0,
                 0,
                 0,
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
			)
			";
        }

        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $businessPartnerContactId = $this->q->lastInsertId('businessPartnerContact');
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => true,
                    "message" => $this->t['newRecordTextLabel'],
                    "staffName" => $_SESSION['staffName'],
                    "executeTime" => date('d-m-Y H:i:s'),
                    "businessPartnerContactId" => $businessPartnerContactId,
                    "time" => $time
                )
        );
        exit();
    }

    /**
     * Return Cheque Character
     * @param double $amount Amount
     * @return string $amountText Amount Character
     */
    public function chequeCharacter($amount) {
        $f = new \NumberFormatter($this->getCountryCurrencyLocale(),\NumberFormatter::SPELLOUT);
        //@ explode problem if not cast to string.
        $amount = (string) $amount;
        $amountArray = explode(".", $amount);
        if (is_array($amountArray) && intval($amountArray[1]) > 0) {
            $centDecimal = $f->format($amountArray[1]);
            $amount = (float) $amount;
            if($this->getCountryCurrencyLocale()=='ms-MY') {
                $amountTextTemp = explode('TITIK', $f->format($amount));
                $amountText = str_replace("TITIK", " ", strtoupper($amountTextTemp[0] . " DAN SEN " . $centDecimal . " SAHAJA "));
            } else {
                $amountTextTemp = explode("DOT", $f->format($amount));
                $amountText = str_replace("DOT", " ", strtoupper($amountTextTemp[0] . " DAN SEN " . $centDecimal . " ONLY "));
            }
        } else {
            if($this->getCountryCurrencyLocale()=='ms-MY') {
                $amount = (float)$amount;
                $amountText = strtoupper($f->format($amount) . " SAHAJA ");
            } else {
                $amount = (float)$amount;
                $amountText = strtoupper($f->format($amount) . " ONLY ");
            }
        }
        return $amountText;
    }

    /**
     * Return Total Collection Day.
     * @param int $collectionId Collection Primary Key
     * @return int $totalCollectionTrackingDay Total Day
     * @throw exception
     */
    private function getTotalCollectionTrackingDay($collectionId){
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $totalCollectionTrackingDay = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      SUM(`collectionTrackingDurationDay`) AS `totalCollectionTrackingDay`
            FROM        `collectiontracking`
            WHERE       `isActive`  =   1
            AND         `companyId` =   '" . $this->getCompanyId() . "'
            AND    	  `collectionId` =	  '".$collectionId."'
            GROUP BY   `collectionId`";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      SUM([collectionTrackingDurationDay]) AS [totalCollectionTrackingDay]
            FROM        [collectionTracking]
            WHERE       [isActive]  =   1
            AND         [companyId] =   '" . $this->getCompanyId() . "'
            AND    	    [collectionId] =	  '".$collectionId."'
            GROUP BY    [collectionId] ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      SUM(COLLECTIONTRACKINGDURATIONDAY) AS \"totalCollectionTrackingDay\"
            FROM         COLLECTIONTRACKING
            WHERE       ISACTIVE  =   1
            AND          COMPANYID =   '" . $this->getCompanyId() . "'
            AND    	    COLLECTIONID =	  '".$collectionId."'
            GROUP BY    COLLECTIONID ";
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
            $totalCollectionTrackingDay = (int) $row['totalCollectionTrackingDay'];
        }
        return $totalCollectionTrackingDay;
    }
    /**
     * Return Total Collection Hour.
     * @param int $collectionId Collection Primary Key
     * @return int $totalCollectionTrackingHour Total Day
     * @depreciated  Save it as emengency
     * @throw exception
     */
    private function getTotalCollectionTrackingHour($collectionId){
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $totalCollectionTrackingHour = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      SUM(`collectionTrackingDurationHour`) AS `totalCollectionTrackingHour`
            FROM        `collectiontracking`
            WHERE       `isActive`  =   1
            AND         `companyId` =   '" . $this->getCompanyId() . "'
            AND    	  `collectionId` =	  '".$collectionId."'
            GROUP BY   `collectionId`";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      SUM([collectionTrackingDurationDay]) AS [totalCollectionTrackingHour]
            FROM        [collectionTracking]
            WHERE       [isActive]  =   1
            AND         [companyId] =   '" . $this->getCompanyId() . "'
            AND    	    [collectionId] =	  '".$collectionId."'
            GROUP BY    [collectionId] ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      SUM(COLLECTIONTRACKINGDURATIONHOUR) AS \"totalCollectionTrackingHour\"
            FROM         COLLECTIONTRACKING
            WHERE       ISACTIVE  =   1
            AND          COMPANYID =   '" . $this->getCompanyId() . "'
            AND    	    COLLECTIONID =	  '".$collectionId."'
            GROUP BY    COLLECTIONID ";
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
            $totalCollectionTrackingHour = $row['totalCollectionTrackingHour'];
        }
        return $totalCollectionTrackingHour;
    }

    /**
     * Return Total Tracking Holiday
     * @param int $collectionId Collection Primary Key
     * @return int $totalHoliday Total Holiday
     */
    private function getTotalTrackingHolidayCollection($collectionId){
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $totalHoliday = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      count(*) AS total
            FROM        `leaveholidays`
            WHERE       `isActive`  =   1
            AND         `companyId` =   '" . $this->getCompanyId() . "'
            AND    	    `collectionId` =	  '".$collectionId."'
            AND        `leaveHolidaysDate` BETWEEN (
                                                           (
                                                               SELECT MIN(collectionTrackingDate)
                                                               FROM   `collectiontracking`
                                                               WHERE  `companyId`   =   '".$this->getCompanyId()."'
                                                               AND    `collectionId`=   '".$collectionId."'
                                                           )
                                                        AND
                                                           (
                                                               SELECT MAX(collectionTrackingDate)
                                                               FROM   `collectiontracking`
                                                               WHERE  `companyId`   =   '".$this->getCompanyId()."'
                                                               AND    `collectionId`=   '".$collectionId."'
                                                           )
                                                    )
            AND        `isNational` =   1
            AND        `isState`    =   1
            AND        `isWeekend`  =   1

            GROUP BY   `collectionId`";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      count(*) AS [totalHoliday]
            FROM       [leaveHolidays]
            WHERE      [isActive]  =   1
            AND        [companyId] =   '" . $this->getCompanyId() . "'
            AND    	   [collectionId] =	  '".$collectionId."'
            AND        [leaveHolidaysDate` BETWEEN (
                                                           (
                                                               SELECT MIN(collectionTrackingDate)
                                                               FROM   [collectionTracking]
                                                               WHERE  [companyId]   =   '".$this->getCompanyId()."'
                                                               AND    [collectionId]=   '".$collectionId."'
                                                           )
                                                        AND
                                                           (
                                                               SELECT MAX([collectionTrackingDate])
                                                               FROM   [collectionTracking]
                                                               WHERE  [companyId]   =   '".$this->getCompanyId()."'
                                                               AND    [collectionId]=   '".$collectionId."'
                                                           )
                                                    )
            AND        [isNational] =   1
            AND        [isState]    =   1
            AND        [isWeekend]  =   1

            GROUP BY   [collectionId]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      COUNT(*) AS \"totalHoliday\"
            FROM       LEAVEHOLIDAYS
            WHERE      ISACTIVE  =   1
            AND        COMPANYID =   '" . $this->getCompanyId() . "'
            AND    	   COLLECTIONID =	  '".$collectionId."'
            AND        LEAVEHOLIDAYSDATE (BETWEEN
                                                           (
                                                               SELECT MIN(COLLECTIONTRACKINGDATE)
                                                               FROM   COLLECTIONTRACKING
                                                               WHERE  COMPANYID     =   '".$this->getCompanyId()."'
                                                               AND    COLLECTIONID  =   '".$collectionId."'
                                                           )
                                                        AND
                                                           (
                                                               SELECT MAX(COLLECTIONTRACKINGDATE)
                                                               FROM   COLLECTIONTRACKING
                                                               WHERE  COMPANYID     =   '".$this->getCompanyId()."'
                                                               AND    COLLECTIONID  =   '".$collectionId."'
                                                           )
                                                    )
            AND        ISNATIONAL =   1
            AND        ISSTATE    =   1
            AND        ISWEEKEND  =   1

            GROUP BY   COLLECTIONID";
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
            $totalHoliday = $row['totalHoliday'];
        }
        return $totalHoliday;
    }

    /**
     * Return Setup Tracking Collection Warning Day
     * @return int $collectionTrackingWarningDay Setup Tracking Collection Warning Day
     */
    private function getTrackingCollectionWarningDay() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $collectionTrackingWarningDay = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `collectionTrackingWarningDay`
            FROM `tracking`
            WHERE `companyId`='".$this->getCompanyId()."'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  [collectionTrackingWarningDay]
            FROM [tracking]
            WHERE [companyId]='".$this->getCompanyId()."'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  COLLECTIONTRACKINGWARNINGDAY
            FROM TRACKING
            WHERE COMPANYID='".$this->getCompanyId()."'";
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
            $collectionTrackingWarningDay = $row['collectionTrackingWarningDay'];
        }
        return $collectionTrackingWarningDay;
    }

    /**
     * Return Setup Tracking Collection Warning Hour
     * @return int $collectionTrackingWarningHour Setup Tracking Collection Warning Hour
     */
    private function getTrackingCollectionWarningHour() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $collectionTrackingWarningHour = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `collectionTrackingWarningHour`
            FROM `tracking`
            WHERE `companyId`='".$this->getCompanyId()."'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  `collectionTrackingWarningHour`
            FROM `tracking `
            WHERE `companyId`='".$this->getCompanyId()."'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  COLLECTIONTRACKINGWARNINGHOUR
            FROM    TRACKING
            WHERE  COMPANYID    ='".$this->getCompanyId()."'";
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
            $collectionTrackingWarningHour = $row['collectionTrackingWarningHour'];
        }
        return $collectionTrackingWarningHour;
    }

    /**
     * Return Tracking Collection By Day
     * @param int $collectionId Collection Primary Key
     * @return int|bool
     */
    public function getTrackingWarningStatusCollectionByDay($collectionId){
        if($this->getTotalCollectionTrackingDay($collectionId)-$this->getTotalTrackingHolidayCollection($collectionId) > $this->getTrackingCollectionWarningDay()){
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Return Tracking Collection By Day
     * @param int $collectionId Collection Primary Key
     * @return int|bool
     */
    public function getTrackingWarningStatusCollectionByHour($collectionId){
        if($this->getTotalCollectionTrackingHour($collectionId)-($this->getTotalTrackingHolidayCollection($collectionId)*24) > $this->getTrackingCollectionWarningHour()){
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Return Tracking Collection By Day
     * @param int $collectionId Collection Primary Key
     * @return int
     */
    public function getTrackingCollectionByDay($collectionId){
        return (int) $this->getTotalCollectionTrackingDay($collectionId)-$this->getTotalTrackingHolidayCollection($collectionId);
    }
    /**
     * Return Tracking Collection By Day
     * @param int $collectionId Collection Primary Key
     * @return int
     */
    public function getTrackingCollectionByHour($collectionId){
        return (int) $this->getTotalCollectionTrackingHour($collectionId)-($this->getTotalTrackingHolidayCollection($collectionId)*24);
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
