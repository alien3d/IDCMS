<?php

namespace Core\Financial\GeneralLedger\Journal\Service;

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
 * Class JournalService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\GeneralLedger\Journal\Service
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class JournalService extends ConfigClass {

    /**
     * Asset->Balance Sheet Item
     */
    const ASSET = 'A';

    /**
     * Liability->Balance Sheet Item
     */
    const LIABILITY = 'L';

    /**
     * Equity->Balance Sheet Item
     */
    const EQUITY = 'OE';

    /**
     * Income->Profit And Loss
     */
    const INCOME = 'I';

    /**
     * Expenses->Profit And Loss
     */
    const EXPENSES = 'E';

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
     * Return Flag either wanted automatic posting or batch
     * @var int
     */
    private $isPosting;

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
        if ($_SESSION['staffId']) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            // fall back to default database if anything wrong
            $this->setStaffId(1);
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
     * Return Business Partner
     * @param null|int $businessPartnerCategoryId Business Partner category
     * @return array|string
     */
    public function getBusinessPartner($businessPartnerCategoryId = null) {
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
			AND         `businesspartner`.`companyId` =   '" . $this->getCompanyId() . "'";
            if ($businessPartnerCategoryId) {
                $sql.=" AND `businesspartner`.`businessPartnerCategoryId`='" . $businessPartnerCategoryId . "'";
            }
            $sql.="
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
			AND         [businessPartner].[companyId] 							=   '" . $this->getCompanyId() . "'";
            if ($businessPartnerCategoryId) {
                $sql.=" AND [businessPartner].[businessPartnerCategoryId]='" . $businessPartnerCategoryId . "'";
            }
            $sql.="
			ORDER BY    [businessPartnerCategory].[businessPartnerCategoryDescription],
			[businessPartner].[businessPartnerCompany]	";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT      BUSINESSPARTNER.BUSINESSPARTNERID AS \"businessPartnerId\",
							 BUSINESSPARTNER.BUSINESSPARTNERCOMPANY AS \"businessPartnerCompany\",
							 BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYDESCRIPTION AS \"businessPartnerCategoryDescription\"
			FROM        BUSINESSPARTNER
			JOIN	     	BUSINESSPARTNERCATEGORY
			ON			 BUSINESSPARTNERCATEGORY.COMPANYID 					= 	BUSINESSPARTNER.COMPANYID
			AND		 	BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYID 	= 	BUSINESSPARTNER.BUSINESSPARTNERCATEGORYIID
			WHERE       BUSINESSPARTNER.ISACTIVE    						=   1";
            if ($businessPartnerCategoryId) {
                $sql.=" AND BUSINESSPARTNER.BUSINESSPARTNERCATEGORYID='" . $businessPartnerCategoryId . "'";
            }
            $sql.="
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
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 0;
            $businessPartnerCategoryDescription = null;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($d != 0) {
                    if ($businessPartnerCategoryDescription != $row['businessPartnerCategoryDescription']) {
                        $str .= "</optgroup><optgroup label=\"" . $row['businessPartnerCategoryDescription'] . "\">";
                    }
                } else {
                    $str .= "<optgroup label=\"" . $row['businessPartnerCategoryDescription'] . "\">";
                }
                $businessPartnerCategoryDescription = $row['businessPartnerCategoryDescription'];

                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['businessPartnerId'] . "'>" . $d . ". " . $row['businessPartnerCompany'] . "</option>";
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
     * Return BusinessPartner Default Value
     * @return int
     */
    public function getBusinessPartnerDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $businessPartnerId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT      `businessPartnerId`
			FROM        	`businesspartner`
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
     * Return BusinessPartnerContact
     * @return array|string
     */
    public function getBusinessPartnerContact() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT      `businessPartnerContactId`,
			 `businessPartnerContactName`
			FROM        `businesspartnercontact`
			WHERE       `isActive`  =   1
			AND         `companyId` =   '" . $this->getCompanyId() . "'
			ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      [businessPartnerContactId],
			 [businessPartnerContactName]
			FROM        [businessPartnerContact]
			WHERE       [isActive]  =   1
			AND         [companyId] =   '" . $this->getCompanyId() . "'
			ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT      BUSINESSPARTNERCONTACTID AS \"businessPartnerContactId\",
			BUSINESSPARTNERCONTACTNAME AS \"businessPartnerContactName\"
			FROM        BUSINESSPARTNERCONTACT
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
                    $str .= "<option value='" . $row['businessPartnerContactId'] . "'>" . $d . ". " . $row['businessPartnerContactName'] . "</option>";
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
     * Set New Fast Business Partner.Company Address And shipping address will be same as defaulted.
     * @param string $businessPartnerCompany
     * @param string $businessPartnerAddress
     * return int $businessPartnerId
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
        } else
        if ($this->getVendor() == self::MSSQL) {
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
        } else
        if ($this->getVendor() == self::ORACLE) {
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
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      TOP 1 [businessPartnerCategoryId]
			FROM        [businessPartnerCategory]
			WHERE       [isActive]  			        =   1
			AND         [companyId] 			        =   '" . $this->getCompanyId() . "'
			AND		 	[businessPartnerCategoryCode]	=	'" . $businessPartnerCategoryCode . "'";
        } else
        if ($this->getVendor() == self::ORACLE) {
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
     * Return BusinessPartnerShippingCountry Default Value
     * @return int
     */
    public function getBusinessPartnerShippingCountryDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $countryId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `countryId`
         FROM        `country`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
          LIMIT 1";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [countryId],
         FROM        [country]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else
        if ($this->getVendor() == self::ORACLE) {
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
     * Return BusinessPartnerShippingState Default Value
     * @return int
     */
    public function getBusinessPartnerShippingStateDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $stateId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `stateId`
         FROM        `state`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [stateId],
         FROM        [state]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else
        if ($this->getVendor() == self::ORACLE) {
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
     * Return BusinessPartnerShippingCity Default Value
     * @return int
     */
    public function getBusinessPartnerShippingCityDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $cityId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `cityId`
         FROM        `city`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [cityId],
         FROM        [city]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else
        if ($this->getVendor() == self::ORACLE) {
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
     * @param int $businessPartnerId
     * @param null|string $businessPartnerContactName
     * @param null|string $businessPartnerContactPhone
     * @param null|string $businessPartnerContactEmail
     * return @void
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
        } else
        if ($this->getVendor() == self::MSSQL) {
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
        } else
        if ($this->getVendor() == self::ORACLE) {
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
        $businessPartnerContactId = $this->q->lastInsertId();
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
     * Return BusinessPartnerContact Default Value
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
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [businessPartnerContactId],
         FROM        [businessPartnerContact]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else
        if ($this->getVendor() == self::ORACLE) {
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
     * Return Journal Type.Will Be not filter all if Financial set-up month end closing is not chosen.
     * @return array|string
     */
    public function getJournalType() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      `journalTypeId`,
                        `journalTypeDescription`
            FROM        `journaltype`
            WHERE       `isActive`  =   1
            AND         `companyId` =   '" . $this->getCompanyId() . "'";
            if (!$this->getFinancialClosingValid()) {
                $sql .= " AND `journalTypeId` IN (1,5)";
            }
            $sql .= "
		    ORDER BY    `isDefault`;";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      [journalTypeId],
                        [journalTypeDescription]
            FROM        [journalType]
            WHERE       [isActive]  =   1
            AND         [companyId] =   '" . $this->getCompanyId() . "'";
            if (!$this->getFinancialClosingValid()) {
                $sql .= " AND [journalTypeId] IN (1,5)";
            }
            $sql .= "
            ORDER BY    [isDefault]";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      JOURNALTYPEID           AS  \"journalTypeId\",
                        JOURNALTYPEDESCRIPTION  AS  \"journalTypeDescription\"
            FROM        JOURNALTYPE
            WHERE       ISACTIVE    =   1
            AND         COMPANYID   =   '" . $this->getCompanyId() . "'";
            if (!$this->getFinancialClosingValid()) {
                $sql .= " AND JOURNALTYPEID IN (1,5)";
            }
            $sql .= "
            ORDER BY    ISDEFAULT";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }

        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['journalTypeId'] . "'>" . $row['journalTypeDescription'] . "</option>";
                } else {
                    if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
                    }
                }
            }
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
     *  Return is it valid the financial closing
     * @return bool $valid
     */
    public function getFinancialClosingValid() {
        $valid = false;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `isClosing`
            FROM    `financesetting`
            WHERE   `companyId`='" . $this->getCompanyId() . "'
            ";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  [isClosing]
            FROM    [financeSetting]
            WHERE   [companyId]     =   '" . $this->getCompanyId() . "'
            ";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  ISCLOSING AS \"isClosing\"
            FROM    FINANCESETTING
            WHERE   COMPANYID='" . $this->getCompanyId() . "'
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
            $valid = $row['isClosing'];
            if ($valid == 1) {
                $valid = true;
            } else {
                $valid = false;
            }
        }
        return $valid;
    }

    /**
     * Return Journal Type Default Value
     * @return int
     */
    public function getJournalTypeDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $journalTypeId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      `journalTypeId`
            FROM        `journaltype`
            WHERE       `isActive`  =   1
            AND         `companyId` =   '" . $this->getCompanyId() . "'
            AND         `isDefault` =	  1
            LIMIT 1";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      TOP 1 [journalTypeId],
            FROM        [journalType]
            WHERE       [isActive]  =   1
            AND         [companyId] =   '" . $this->getCompanyId() . "'
            AND    	    [isDefault] =   1";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      JOURNALTYPEID AS \"journalTypeId\",
            FROM        JOURNALTYPE
            WHERE       ISACTIVE    =   1
            AND         COMPANYID   =   '" . $this->getCompanyId() . "'
            AND    	    ISDEFAULT	  =	   1
            AND 	    ROWNUM	  =	   1";
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
            $journalTypeId = $row['journalTypeId'];
        }
        return $journalTypeId;
    }

    /**
     * Return Compare Master And Detail.Total Will be converted to local amount upon comparing
     * if figure  are equal then can process to posting
     * @param int $journalId Journal Primary Key
     * @return int
     */
    function getCompareMasterDetail($journalId) {
        if (floatval($this->getMasterTotal($journalId)) != floatval($this->getDetailTotal($journalId))) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Return Master Total Amount .Return based on local currency
     * @param int $journalId Journal Primary Key
     * @return mixed
     */
    function getMasterTotal($journalId) {
        $sql = null;
        $journalAmount = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `journalAmount`
            FROM    `journal`
            WHERE   `companyId`='" . $this->getCompanyId() . "'
            AND     `journalId`='" . $this->strict($journalId, 'numeric') . "'";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  [journalAmount]
            FROM    [journal]
            WHERE   [companyId]='" . $this->getCompanyId() . "'
            AND     [journalId]='" . $this->strict($journalId, 'numeric') . "'";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  JOURNALAMOUNT AS journalAmount
            FROM    JOURNAL
            WHERE   COMPANYID='" . $this->getCompanyId() . "'
            AND     JOURNALID='" . $this->strict($journalId, 'numeric') . "'";
        }

        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result, $sql) > 0) {
                $row = $this->q->fetchArray($result);
                $journalAmount = $row['journalAmount'];
            }
        }
        return $journalAmount;
    }

    /**
     * Return Detail Total Amount.Foreign Currency will be converted to local currency.
     * @param int $journalId Journal Primary Key
     * @return mixed
     */
    function getDetailTotal($journalId) {
        $sql = null;
        $total = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT (`journalDetail`.`journalDetailAmount` * `exchange`.`exchangeRate`) AS `total`
            FROM   `journalDetail`
            JOIN   `exchange`
            USING   (`companyId`,`currencyId`)
            WHERE  `journalDetail`.`companyId`  =   '" . $this->getCompanyId() . "'
            AND    `journalDetail`.`journalId`  =   '" . $journalId . "'
            AND    `journalDetail`.`isActive`   =   1";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  ([journalDetail].[journalDetailAmount] * [exchange].[exchangeRate]) AS [total]
            FROM   [journalDetail]
            JOIN    [exchange]
            AND    [journalDetail].[companyId] =   [exchange].[companyId]
            AND    [journalDetail].[countryId] =   [exchange].[countryId]
            WHERE  [journalDetail].[companyId] =   '" . $this->getCompanyId() . "'
            AND    [journalDetail].[journalId] =   '" . $journalId . "'";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  (JOURNALDETAIL.JOURNALDETAILAMOUNT * EXCHANGE.EXCHANGERATE) AS total
            FROM    JOURNALDETAIL
            JOIN    EXCHANGE
            ON      JOURNALDETAIL.COMPANYID = EXCHANGE.COMPANYID
            AND     JOURNALDETAIL.COUNTRYID = EXCHANGE.COUNTRYID
            WHERE   JOURNALDETAIL.COMPANYID ='" . $this->getCompanyId() . "'
            AND     JOURNALDETAIL.JOURNALID ='" . $journalId . "'";
        }

        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result, $sql) > 0) {
                $row = $this->q->fetchArray($result);
                $total = $row['total'];
            }
        }
        return $total;
    }

    /**
     * Post Record  To General Ledger
     * @param int|string $journalId Journal Primary Key
     * @param int $leafId Leaf Or Source Of The Application. Might either go to form  or go to history page
     * @param string $leafName
     * @return void|string
     */
    function postGeneralLedger($journalId, $leafId, $leafName) {
        header('Content-Type:application/json; charset=utf-8');
        $start = microtime(true);
        $this->q->start();
        // get Data From Detail Table
        $sqlJournalDetail = null;
        $sqlGeneralLedger = null;
        $checkStatus = $this->getIsPostJournal($journalId);
        if ($checkStatus == 1) {
            $end = microtime(true);
            $time = $end - $start;
            echo json_encode(
                    array(
                        "success" => false,
                        "message" => "Somebody have posted it lol",
                        "time" => $time
                    )
            );
            exit();
        }
        $journalNumber = $this->getDocumentNumber('GLPT');
        if ($this->getVendor() == self::MYSQL) {
            $sqlJournalDetail = "
            SELECT *
            FROM    `journaldetail`

            JOIN    `journal`
            ON      `journaldetail`.`companyId`                     =   `journal`.`companyId`
            AND     `journaldetail`.`journalId`                     =   `journal`.`journalId`

            JOIN    `chartofaccount`
            ON      `journal`.`companyId`                           = `chartofaccount`.`companyId`
            AND     `journaldetail`.`chartOfAccountId`              = `chartofaccount`.`chartOfAccountId`

            JOIN    `chartofaccountcategory`
            ON      `journaldetail`.`companyId`                     =   `chartofaccountcategory`.`companyId`
            AND     `chartofaccount`.`chartOfAccountCategoryId`     =   `chartofaccountcategory`.`chartOfAccountCategoryId`

            JOIN    `chartofaccounttype`
            ON      `journaldetail`.`companyId`                     = `chartofaccounttype`.`companyId`
            AND     `chartofaccounttype`.`chartOfAccountCategoryId` = `chartofaccountcategory`.`chartOfAccountCategoryId`
            AND     `chartofaccounttype`.`chartOfAccountTypeId`     = `chartofaccount`.`chartOfAccountTypeId`

            WHERE   `journaldetail`.`companyId`						=	'" . $this->getCompanyId() . "'
			AND		`journaldetail`.`journalId`                     IN  (" . $journalId . ")
            AND     `journaldetail`.`isActive`                      =   1";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sqlJournalDetail = "
            SELECT  *
            FROM   [journalDetail]

            JOIN    [journal]
            ON     [journalDetail].[companyId] =   [journal].[companyId]
            AND    [journalDetail].[journalId] =   [journal].[journalId]

            JOIN    [chartOfAccount]
            ON      [journal].[companyId] = [chartOfAccount].[companyId]
            AND    [journalDetail].[chartOfAccountId] = [chartOfAccount].[chartOfAccountId]

            JOIN    [chartOfAccountCategory]
            ON     [journalDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
            AND     [chartOfAccountCategory][chartOfAccountCategoryId] =   [chartOfAccount].[chartOfAccountCategoryId]

            JOIN    [chartOfAccountType]
            ON     [journalDetail].[companyId]                         =   [chartOfAccountType].[companyId]
            AND     [chartOfAccountType].[chartOfAccountCategoryId]     =   [chartOfAccountCategory][chartOfAccountCategoryId]
            AND     [chartOfAccountType].[chartOfAccountTypeId]         =   [chartOfAccount].[chartOfAccountTypeId]

            WHERE  [journalDetail].[companyId]							=	'" . $this->getCompanyId() . "'
			AND		[journalDetail].[journalId]                         IN  (" . $journalId . ")
            AND    [journalDetail].[isActive]                          =   1";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sqlJournalDetail = "
            SELECT  *
            FROM    JOURNALDETAIL

            JOIN    JOURNAL
            ON      JOURNALDETAIL.COMPANYID =   JOURNAL.COMPANYID
            AND     JOURNALDETAIL.JOURNALID =   JOURNAL.JOURNALID

            JOIN    CHARTOFACCOUNT
            ON      JOURNAL.COMPANYID = CHARTOFACCOUNT.COMPANYID
            AND     JOURNALDETAIL.CHARTOFACCOUNTID = CHARTOFACCOUNT.CHARTOFACCOUNTID

            JOIN    CHARTOFACCOUNTCATEGORY
            ON      JOURNALDETAIL.COMPANYID                         =   CHARTOFACCOUNTCATEGORY.COMPANYID
            AND     CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID =   CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID

            JOIN    CHARTOFACCOUNTTYPE
            ON      JOURNALDETAIL.COMPANYID                         =   CHARTOFACCOUNTTYPE.COMPANYID
            AND     CHARTOFACCOUNTTYPE.CHARTOFACCOUNTCATEGORYID     =   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID
            AND     CHARTOFACCOUNTTYPE.CHARTOFACCOUNTTYPEID         =   CHARTOFACCOUNT.CHARTOFACCOUNTTYPEID

            WHERE   JOURNALDETAIL.COMPANYID							=	'" . $this->getCompanyId() . "'
			AND		JOURNALDETAIL.JOURNALID                         =   (" . $journalId . ")
            AND     JOURNALDETAIL.ISACTIVE                          =   1";
        }

        try {
            $resultJournalDetail = $this->q->fast($sqlJournalDetail);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($resultJournalDetail) {
            while (($row = $this->q->fetchArray($resultJournalDetail)) == true) {
                // insert into general Ledger
                if ($row['journalDetailAmount'] > 0) {
                    $this->setTransactionTypeId(1);
                    $this->setTransactionTypeCode('D');
                    $this->setTransactionTypeDescription('Debit');
                } else {
                    $this->setTransactionTypeId(2);
                    $this->setTransactionTypeCode('C');
                    $this->setTransactionTypeDescription('Credit');
                }
                if ($this->getVendor() == self::MYSQL) {
                    $sqlGeneralLedger = "
                        INSERT INTO `generalledger`(
                        `journalNumber`,
                        `companyId`,                                            `documentNumber`,                                       `generalLedgerTitle`,
                        `generalLedgerDescription`,                             `generalLedgerDate`,                                    `countryId`,

                        `countryCurrencyCode`,                                  `transactionTypeId`,                                    `transactionTypeCode`,
                        `transactionTypeDescription`,                           `foreignAmount`,                                        `localAmount`,

                        `chartOfAccountCategoryId`,                             `chartOfAccountCategoryDescription`,                    `chartOfAccountTypeId`,
                        `chartOfAccountTypeDescription`,                        `chartOfAccountId`,                                     `chartOfAccountNumber`,
                        `chartOfAccountDescription`,                            `businessPartnerId`,                                    `businessPartnerDescription`,

                        `isDefault`,                                            `isNew`,                                                `isDraft`,
                        `isUpdate`,                                             `isDelete`,                                             `isActive`,

                        `isApproved`,                                           `isReview`,                                             `isPost`,
                        `isAuthorized`,                                         `isMerge`,                                              `isSlice`,

                        `executeBy`,                                            `executeName`,                                          `executeTime`,
                        `module`,                                               `tableName`,                                            `tableNameId`,

                        `tableNameDetail`,                                      `tableNameDetailId`,									`leafId`,
                        `leafName`,                                             `chartOfAccountCategoryCode`,                           `chartOfAccountTypeCode`
                        ) VALUES (
                            '" . $journalNumber . "',
                            '" . $_SESSION['companyId'] . "',                   '" . $row['documentNumber'] . "',                       '" . $row['journalDescription'] . "',
                            '" . $row['journalDescription'] . "',               '" . $row['journalDate'] . "',                          '" . $this->getCountryId(
                            ) . "',

                            '" . $this->getCountryCurrencyCode() . "',          '" . $this->getTransactionTypeId(
                            ) . "',                '" . $this->getTransactionTypeCode() . "',
                            '" . $this->getTransactionTypeDescription(
                            ) . "',   '" . $row['journalDetailForeignAmount'] . "',           '" . $row['journalDetailAmount'] . "',

                            '" . $row['chartOfAccountCategoryId'] . "',         \"" . addslashes(
                                    $row['chartOfAccountCategoryTitle']
                            ) . "\",    '" . $row['chartOfAccountTypeId'] . "',
                            '" . $row['chartOfAccountTypeDescription'] . "',    '" . $row['chartOfAccountId'] . "',                     '" . $row['chartOfAccountNumber'] . "',
                            '" . $row['chartOfAccountTitle'] . "',              '" . $row['businessPartnerId'] . "',                    '" . $this->getBusinessPartnerInformation(
                                    $row['businessPartnerId']
                            ) . "',

                            0,                                                  1,                                                      0,
                            0,                                                  0,                                                      0,

                            0,                                                  0,                                                      0,
                            0,                                                  0,                                                      0,

                            '" . $this->getStaffId(
                            ) . "',                      '" . $_SESSION['staffName'] . "',                       " . $this->getExecuteTime(
                            ) . ",
                            'GL',                                               'journal',                                              '" . $row['journalId'] . "',

                            'journaldetail',                                    '" . $row['journalDetailId'] . "',						'" . $leafId . "',
                            '" . $leafName . "',									'" . $row['chartOfAccountCategoryCode'] . "',               '" . $row['chartOfAccountTypeCode'] . "');";
                } else
                if ($this->getVendor() == self::MSSQL) {
                    $sqlGeneralLedger = "
                        INSERT INTO [generalLedger](
                            [journalNumber],
                            [companyId],                                        [documentNumber],                                       [generalLedgerTitle],
                            [generalLedgerDescription],                         [generalLedgerDate],                                    [countryId],
                            [countryCurrencyCode],                              [transactionTypeId],                                    [transactionTypeCode],
                            [transactionTypeDescription],                       [foreignAmount],                                        [localAmount],
                            [chartOfAccountCategoryId],                         [chartOfAccountCategoryDescription],                    [chartOfAccountTypeId],
                            [chartOfAccountTypeDescription],                    [chartOfAccountId],                                     [chartOfAccountNumber],
                            [chartOfAccountDescription],                        [businessPartnerId],                                    [businessPartnerDescription],
                            [isDefault],                                        [isNew],                                                [isDraft],
                            [isUpdate],                                         [isDelete],                                             [isActive],
                            [isApproved],                                       [isReview],                                             [isPost],
                            [isAuthorized],                                     [isMerge],                                              [isSlice],
                            [executeBy],                                        [executeName],                                          [executeTime],
                            [module],                                           [tableName],                                            [tableNameId],
                            [tableNameDetail],                                  [tableNameDetailId],									[leafId]
						    [leafName],                                         [chartOfAccountCategoryCode],                           [chartOfAccountTypeCode]
                       ) VALUES (
                            '" . $journalNumber . "',
                            '" . $_SESSION['companyId'] . "',                   '" . $row['documentNumber'] . "',                       '" . $row['journalDescription'] . "',
                            '" . $row['journalDescription'] . "',               '" . $row['journalDate'] . "',                          '" . $this->getCountryId(
                            ) . "',

                            '" . $this->getCountryCurrencyCode() . "',          '" . $this->getTransactionTypeId(
                            ) . "',                '" . $this->getTransactionTypeCode() . "',
                            '" . $this->getTransactionTypeDescription(
                            ) . "',   '" . $row['journalDetailForeignAmount'] . "',           '" . $row['journalDetailAmount'] . "',

                            '" . $row['chartOfAccountCategoryId'] . "',         \"" . addslashes(
                                    $row['chartOfAccountCategoryTitle']
                            ) . "\",    '" . $row['chartOfAccountTypeId'] . "',
                            '" . $row['chartOfAccountTypeDescription'] . "',    '" . $row['chartOfAccountId'] . "',                     '" . $row['chartOfAccountNumber'] . "',
                            '" . $row['chartOfAccountTitle'] . "',              '" . $row['businessPartnerId'] . "',                    '" . $this->getBusinessPartnerInformation(
                                    $row['businessPartnerId']
                            ) . "',

                            0,                                                  1,                                                      0,
                            0,                                                  0,                                                      0,

                            0,                                                  0,                                                      0,
                            0,                                                  0,                                                      0,

                            '" . $this->getStaffId(
                            ) . "',                      '" . $_SESSION['staffName'] . "',                       " . $this->getExecuteTime(
                            ) . ",
                            'GL',                                               'journal',                                              '" . $row['journalId'] . "',

                            'journaldetail',                                    '" . $row['journalDetailId'] . "',						'" . $leafId . "',
                             '" . $leafName . "',									'" . $row['chartOfAccountCategoryCode'] . "',               '" . $row['chartOfAccountTypeCode'] . "');";
                } else
                if ($this->getVendor() == self::ORACLE) {
                    $sqlGeneralLedger = "
                            INSERT INTO GENERALLEDGER(
                        JOURNALNUMBER,
                        COMPANYID,                                            DOCUMENTNUMBER,                                       GENERALLEDGERTITLE,
                        GENERALLEDGERDESCRIPTION,                             GENERALLEDGERDATE,                                    COUNTRYID,

                        COUNTRYCURRENCYCODE,                                  TRANSACTIONTYPEID,                                    TRANSACTIONTYPECODE,
                        TRANSACTIONTYPEDESCRIPTION,                           FOREIGNAMOUNT,                                        LOCALAMOUNT,

                        CHARTOFACCOUNTCATEGORYID,                             CHARTOFACCOUNTCATEGORYDESCRIPTION,                    CHARTOFACCOUNTTYPEID,
                        CHARTOFACCOUNTTYPEDESCRIPTION,                        CHARTOFACCOUNTID,                                     CHARTOFACCOUNTNUMBER,
                        CHARTOFACCOUNTDESCRIPTION,                            BUSINESSPARTNERID,                                    BUSINESSPARTNERDESCRIPTION,

                        ISDEFAULT,                                            ISNEW,                                                ISDRAFT,
                        ISUPDATE,                                             ISDELETE,                                             ISACTIVE,

                        ISAPPROVED,                                           ISREVIEW,                                             ISPOST,
                        ISAUTHORIZED,                                         ISMERGE,                                              ISSLICE,

                        EXECUTEBY,                                            EXECUTENAME,                                          EXECUTETIME,
                        MODULE,                                               TABLENAME,                                            TABLENAMEID,

                        TABLENAMEDETAIL,                                      TABLENAMEDETAILID,									LEAFID,
                        LEAFNAME,                                             CHARTOFACCOUNTCATEGORYCODE,                           CHARTOFACCOUNTTYPECODE
                        ) VALUES (
                          ) VALUES (
                            '" . $journalNumber . "',
                            '" . $_SESSION['companyId'] . "',                   '" . $row['documentNumber'] . "',                       '" . $row['journalDescription'] . "',
                            '" . $row['journalDescription'] . "',               '" . $row['journalDate'] . "',                          '" . $this->getCountryId(
                            ) . "',

                            '" . $this->getCountryCurrencyCode() . "',          '" . $this->getTransactionTypeId(
                            ) . "',                '" . $this->getTransactionTypeCode() . "',
                            '" . $this->getTransactionTypeDescription(
                            ) . "',   '" . $row['journalDetailForeignAmount'] . "',           '" . $row['journalDetailAmount'] . "',

                            '" . $row['chartOfAccountCategoryId'] . "',         \"" . addslashes(
                                    $row['chartOfAccountCategoryTitle']
                            ) . "\",    '" . $row['chartOfAccountTypeId'] . "',
                            '" . $row['chartOfAccountTypeDescription'] . "',    '" . $row['chartOfAccountId'] . "',                     '" . $row['chartOfAccountNumber'] . "',
                            '" . $row['chartOfAccountTitle'] . "',              '" . $row['businessPartnerId'] . "',                    '" . $this->getBusinessPartnerInformation(
                                    $row['businessPartnerId']
                            ) . "',

                            0,                                                  1,                                                      0,
                            0,                                                  0,                                                      0,

                            0,                                                  0,                                                      0,
                            0,                                                  0,                                                      0,

                            '" . $this->getStaffId(
                            ) . "',                      '" . $_SESSION['staffName'] . "',                       " . $this->getExecuteTime(
                            ) . ",
                            'GL',                                               'journal',                                              '" . $row['journalId'] . "',

                            'journaldetail',                                    '" . $row['journalDetailId'] . "',						'" . $leafId . "',
                             '" . $leafName . "',									'" . $row['chartOfAccountCategoryCode'] . "',               '" . $row['chartOfAccountTypeCode'] . "');";
                }

                try {
                    $this->q->create($sqlGeneralLedger);
                } catch (\Exception $e) {
                    $this->q->rollback();
                    echo json_encode(array("success" => false, "message" => $e->getMessage()));
                    exit();
                }
            }
        }

        // update main detail transaction post
        $this->updateJournalPosted($journalId);
        $this->setSumActualTransaction($journalId);
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => true,
                    "time" => $time
                )
        );
        exit();
    }

    /**
     * Check Status Posted Or Not.To prevent user posted twice..
     * @param int $journalId
     * @return int $isPost
     */
    public function getIsPostJournal($journalId) {
        $isPost = 0;
        $sql = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `isPost`
            FROM    `journal`
            WHERE   `journalId` =   '" . $journalId . "'";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  [isPost]
            FROM    [journal]
            WHERE   [journalId] =   '" . $journalId . "'";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  ISPOST
            FROM    JOURNAL
            WHERE   JOURNALID   =   '" . $journalId . "'";
        }

        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $isPost = $row['isPost'];
        }
        return $isPost;
    }

    /**
     * Update Flag Journal /Journal Detail
     * @param int|string $journalId
     * @return void
     */
    public function updateJournalPosted($journalId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
                UPDATE  `journal`
                SET     `isPost`        =   1
                WHERE   `journalId`     IN   (" . $journalId . ")
                AND     `companyId`     =   '" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE  [journal]
                SET     [isPost]     =  1
                WHERE   [journalId]  IN (" . $journalId . ")
                AND     [companyId]=    '" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
                UPDATE  JOURNAL
                SET     ISPOST      =   1
                WHERE   JOURNALID   IN   (" . $this->strict($journalId, 'numeric') . ")
                AND     COMPANYID   =   '" . $this->getCompanyId() . "'";
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
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
                UPDATE  `journaldetail`
                SET     `isPost`        =   1
                WHERE   `journalId`     IN   (" . $journalId . ")
                AND     `companyId`     =   '" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE [journalDetail]
                SET     [isPost]     =  1
                WHERE   [journalId]  IN (" . $journalId . ")
                AND     [companyId]=    '" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
                UPDATE  JOURNALDETAIL
                SET     ISPOST      =   1
                WHERE   JOURNALID   IN   (" . $this->strict($journalId, 'numeric') . ")
                AND     COMPANYID   =   '" . $this->getCompanyId() . "'";
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
    }

    /**
     * Distinct Chart Of Account From Journal Posted and update the budget
     * @param  int|string $journalId
     */
    public function setSumActualTransaction($journalId) {
        $sql = null;
        $sqlDetail = null;
        $sqlUpdate = null;
        $fieldActual = null;
        // choose from finance period
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `financePeriodRangePeriod`,
                    `financePeriodRangeStartDate`,
                    `financePeriodRangeEndDate`
            FROM    `financeperiodrange`
            WHERE   `financeYearId` = '" . $this->getFinanceYearId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT [financePeriodRangePeriod],
                   [financePeriodRangeStartDate] ,
                   [financePeriodRangeEndDate]
            FROM    [financePeriodRange]
            WHERE  [financeYearId] =  '" . $this->getFinanceYearId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT  FINANCEPERIODRANGEPERIOD AS  \"financePeriodRangePeriod\" ,
                    FINANCEPERIODRANGESTARTDATE AS  \"financePeriodRangeStartDate\" ,
                    FINANCEPERIODRANGEENDATE    AS  \"financePeriodRangeEndDate\"
            FROM    FINANCEPERIODRANGE
            WHERE   FINANCEYEARID  = '" . $this->getFinanceYearId() . "'";
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
            while (($row = $this->q->fetchArray($result)) == true) {
                $startFinancialDate = $row['financePeriodRangeStartDate'];
                $endFinancialDate = $row['financePeriodRangeEndDate'];
                $financePeriodRangePeriod = $row['financePeriodRangePeriod'];
                switch ($financePeriodRangePeriod) {
                    case 1:
                        $fieldActual = "budgetActualMonthOne";
                        break;
                    case 2:
                        $fieldActual = "budgetActualMonthTwo";
                        break;
                    case 3:
                        $fieldActual = "budgetActualMonthThree";
                        break;
                    case 4:
                        $fieldActual = "budgetActualMonthFourth";
                        break;
                    case 5:
                        $fieldActual = "budgetActualMonthFifth";
                        break;
                    case 6:
                        $fieldActual = "budgetActualMonthSix";
                        break;
                    case 7:
                        $fieldActual = "budgetActualMonthSeven";
                        break;
                    case 8:
                        $fieldActual = "budgetActualMonthEight";
                        break;
                    case 9:
                        $fieldActual = "budgetActualMonthNine";
                        break;
                    case 10:
                        $fieldActual = "budgetActualMonthTen";
                        break;
                    case 11:
                        $fieldActual = "budgetActualMonthEleven";
                        break;
                    case 12:
                        $fieldActual = "budgetActualMonthTwelve";
                        break;
                    case 13:
                        $fieldActual = "budgetActualMonthThirteen";
                        break;
                    case 14:
                        $fieldActual = "budgetActualMonthFourteen";
                        break;
                    case 15:
                        $fieldActual = "budgetActualMonthFifteen";
                        break;
                    case 16:
                        $fieldActual = "budgetActualMonthSixteen";
                        break;
                    case 17:
                        $fieldActual = "budgetActualMonthSeventeen";
                        break;
                    case 18:
                        $fieldActual = "budgetActualMonthEighteen";
                        break;
                }
                // select distinct  journal id
                // choose from finance period
                if ($this->getVendor() == self::MYSQL) {
                    $sqlDetail = "
                    SELECT  DISTINCT    `chartOfAccountId`
                    FROM   `journaldetail`
                    WHERE  `journalId` IN (" . $journalId . ") ";
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $sqlDetail = "
                    SELECT DISTINCT    [chartOfAccountId]
                    FROM  [journalDetail]
                    WHERE  [journalId]  IN  (" . $journalId . ")
                    ";
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $sqlDetail = "
                    SELECT  DISTINCT CHARTOFACCOUNTID AS \"chartOfAccountId\"
                    FROM    JOURNALDETAIL
                    WHERE   JOURNALID IN (" . $journalId . ")
            ";
                        }
                    }
                }
                try {
                    $resultDetail = $this->q->fast($sqlDetail);
                } catch (\Exception $e) {
                    echo json_encode(array("success" => false, "message" => $e->getMessage()));
                    exit();
                }
                if ($resultDetail) {
                    while (($rowDetail = $this->q->fetchArray($resultDetail)) == true) {
                        $chartOfAccountId = $rowDetail['chartOfAccountId'];
                        // UPDATE budget based on SUM range period
                        if ($this->getVendor() == self::MYSQL) {
                            $sqlUpdate = "
                            UPDATE `budget`
                            SET    `" . $fieldActual . "` = (
                                SELECT  SUM(`localAmount`)
                                FROM    `generalledger`
                                WHERE   `chartOfAccountId`  	=   '" . $chartOfAccountId . "'
                                AND     `companyId`         	=   '" . $this->getCompanyId() . "'
                                AND     (`generalLedgerDate` BETWEEN
                                                                        '" . $startFinancialDate . "'
                                                            AND
                                                                        '" . $endFinancialDate . "')
                            )
                            WHERE   `chartOfAccountId`          =   '" . $chartOfAccountId . "'
                            AND     `financeYearId`  			=   '" . $this->getFinanceYearId() . "'
                            AND     `companyId`                 =   '" . $this->getCompanyId() . "'

                            ";
                        } elseif ($this->getVendor() == self::MSSQL) {
                            $sqlUpdate = "
                            UPDATE [financebudget]
                            SET    [" . $fieldActual . "] = (
                            SELECT  SUM([localAmount])
                            FROM    [generalLedger]
                            WHERE   [chartOfAccountId]			=	'" . $chartOfAccountId . "'
                            AND     [companyId] 				=	'" . $this->getCompanyId() . "'
                            AND     [generalLedgerDate] between '" . $startFinancialDate . "' AND '" . $endFinancialDate . "'

                            )
                            WHERE   [chartOfAccountId]			=	'" . $chartOfAccountId . "'
                            AND     [financeYearId]  			=	'" . $this->getFinanceYearId() . "'
                            AND     [companyId]     			=	'" . $this->getCompanyId() . "'

                            ";
                        } elseif ($this->getVendor() == self::ORACLE) {
                            $sqlUpdate = "
                            UPDATE  BUDGET
                            SET     " . $fieldActual . " = (
                            SELECT  SUM(LOCALAMOUNT)
                            FROM    GENERALLEDGER
                            WHERE   CHARTOFACCOUNTID			=	'" . $chartOfAccountId . "'
                            AND     COMPANYID 					=	'" . $this->getCompanyId() . "'
                            AND     GENERALLEDGERDATE BETWEEN '" . $startFinancialDate . "' AND '" . $endFinancialDate . "'
                            )
                            WHERE   CHARTOFACCOUNTID			=	'" . $chartOfAccountId . "'
                            AND     FINANCEYEARID  				=	'" . $this->getFinanceYearId() . "'
                            AND     COMPANYID     				=	'" . $this->getCompanyId() . "'

                            ";
                        }
                        try {
                            $this->q->update($sqlUpdate);
                        } catch (\Exception $e) {
                            $this->q->rollback();
                            echo json_encode(array("success" => false, "message" => $e->getMessage()));
                            exit();
                        }
                    }
                }
            }
        }
    }

    /**
     * Find The Previous Transaction Journal and reverse it
     * @param int $journalId
     */
    public function reverseTransaction($journalId) {
        
    }

    /**
     * If The User Tick The Cash Basis,Journal Figure Will Be Posted To Cashbook And General Ledger.
     * @param int $journalId
     */
    public function transferJournalToCashBook($journalId) {
        
    }

    /**
     * Only Preview Next Number
     */
    public function getNextDocumentNumber() {
        
    }

    /**
     * Return Country Information
     * @return string
     */
    public function getCountryDefault() {
        $countryInfo = array();
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `financesetting`.`countryId`
                    `financesetting`.`countryCurrencyCode`,
            FROM    `financesetting`
            WHERE   `companyId`   =   '" . $this->getCompanyId() . "'
            ";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  [financeSetting].[countryId]
                    [financeSetting].[countryCurrencyCode],
            FROM    [financeSetting]
            WHERE   [companyId]   =   '" . $this->getCompanyId() . "'
            ";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  [financeSetting].[countryId]
                    [financeSetting].[countryCurrencyCode],
            FROM    [financeSetting]
            WHERE   [companyId]   =   '" . $this->getCompanyId() . "'
            ";
        }

        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $countryInfo = $this->q->fetchArray($result);
        }
        return $$countryInfo;
    }

    /**
     *  Return is it valid the financial period or  not
     * @return bool $valid
     */
    public function getFinancialPeriodValid() {
        $valid = false;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `isMonthEndClosing`
            FROM    `financesetting`
            WHERE   `companyId`='" . $this->getCompanyId() . "'
            ";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  [isMonthEndClosing]
            FROM    [financeSetting]
            WHERE   [companyId]     =   '" . $this->getCompanyId() . "'
            ";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  ISMONTHENDCLOSING AS \"isMonthEndClosing\"
            FROM    FINANCESETTING
            WHERE   COMPANYID='" . $this->getCompanyId() . "'
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
            $valid = $row['isMonthEndClosing'];
            if ($valid == 1) {
                $valid = true;
            } else {
                $valid = false;
            }
        }
        return $valid;
    }

    /**
     * Return Testing date are valid date to save /post.
     * @param string $journalDate
     * @return bool $isClose;
     */
    public function getTestFinancialPeriodDate($journalDate) {
        $isClose = false;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `isClose`
            FROM    `financeperiodrange`
            WHERE   `companyId`   =   '" . $this->getCompanyId() . "'
            AND     '" . $journalDate . "'
            BETWEEN `financePeriodRangeStartDate`
            AND     `financePeriodRangeEndDate`
            LIMIT   1
            ";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  TOP 1 `isClose`
            FROM     [financePeriodRange]
            WHERE   [companyId]   =   '" . $this->getCompanyId() . "'
            AND     '" . $journalDate . "'
            BETWEEN [financePeriodRangeStartDate]
            AND     [financePeriodRangeEndDate]
            ";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  `isClose`
            FROM    `financeperiodrange`
            WHERE   `companyId`   =   '" . $this->getCompanyId() . "'
            AND     '" . $journalDate . "'
            BETWEEN `financePeriodRangeStartDate`
            AND     `financePeriodRangeEndDate`
            AND     ROWNUM = 1
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
            $isClose = $row['isMonthEndClosing'];
            if ($isClose == 1) {
                $isClose = true;
            } else {
                $isClose = false;
            }
        }
        return $isClose;
    }

    /**
     *  Return is it valid the financial period or  not
     * @return bool $valid
     */
    public function getTestFinancialYearDate() {
        $valid = false;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `isClose`
            FROM    `financeyear`
            WHERE   `companyId`='" . $this->getCompanyId() . "'
            ";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  [isClose]
            FROM    [financeYear]
            WHERE   [companyId]     =   '" . $this->getCompanyId() . "'
            ";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  ISCLOSE AS \"isClose\"
            FROM    FINANCEYEAR
            WHERE   COMPANYID='" . $this->getCompanyId() . "'
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
            $valid = $row['isClose'];
            if ($valid == 1) {
                $valid = true;
            } else {
                $valid = false;
            }
        }
        return $valid;
    }

    /**
     * Return Total debit,credit and trial balance
     * @param int $journalId
     * @return array
     */
    public function getTotalJournalDetail($journalId) {
        header('Content-Type:application/json; charset=utf-8');
        $this->q->start();
        $totalDebit = $this->getTotalJournalAmount($journalId, 1);
        $totalCredit = $this->getTotalJournalAmount($journalId, 2);
        $trialBalance = $this->getCheckTrialBalance($journalId);
        $this->q->commit();
        return array(
            "success" => true,
            "totalDebit" => $totalDebit,
            "totalCredit" => $totalCredit,
            "trialBalance" => $trialBalance
        );
    }

    /**
     * Return Total Journal Amount
     * @param int $journalId Main Table
     * @param string $type 1->debit,2->credit
     * @return double $total
     */
    public function getTotalJournalAmount($journalId, $type) {
        $total = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      SUM(`journalDetailAmount`) AS `total`
            FROM        `journaldetail`

            WHERE       `journaldetail`.`companyId`                         =   '" . $this->getCompanyId() . "'
            AND         `journaldetail`.`journalId`                         IN   (" . $journalId . ")
            AND         `journaldetail`.`isActive`                          =   1";
            if ($type == 1) {
                $sql .= "  AND `journalDetailAmount` >0 ";
            } else {
                $sql .= "  AND `journalDetailAmount` < 0 ";
            }
            // $sql .= "
            //  GROUP BY    `journaldetail`.`journalDetailAmount`
            //  ";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql .= "
            SELECT      SUM([journalDetailAmount]) AS [total]
            FROM       [journalDetail]

            WHERE      [journalDetail].[companyId]                         =   '" . $this->getCompanyId() . "'
            AND        [journalDetail].[journalId]                         IN   (" . $journalId . ")
            AND        [journalDetail].[isActive]                          =   1";
            if ($type == 1) {
                $sql .= "  AND [journalDetailAmount] >0 ";
            } else {
                $sql .= "  AND [journalDetailAmount] < 0 ";
            }
            $sql .= "
            GROUP BY   [journalDetail].[journalDetailAmount]";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      SUM(JOURNALDETAILAMOUNT) AS \"total\"
            FROM        JOURNALDETAIL

            WHERE       JOURNALDETAIL.COMPANYID                         =   '" . $this->getCompanyId() . "'
            AND         JOURNALDETAIL.JOURNALID                         IN   (" . $journalId . ")
            AND         JOURNALDETAIL.ISACTIVE                          =   1";
            if ($type == 1) {
                $sql .= "  AND JOURNALDETAILAMOUNT >0 ";
            } else {
                $sql .= "  AND JOURNALDETAILAMOUNT < 0 ";
            }
            $sql .= "
            GROUP BY    JOURNALDETAIL.JOURNALDETAILAMOUNT
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
            $total = $row['total'];
        }
        return $total;
    }

    /**
     * Return Trial Balance Is correct or not before posting to journal . SUM ASSET  account - LIABILITY accounts + INCOME - Expenses + Return Earning Accounts
     * @param int $journalId
     * @return string $trialBalance
     */
    private function getCheckTrialBalance($journalId) {
        $trialBalance = 0;
        $sql = null;

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT (
                (
                    SELECT      SUM(`journalDetailAmount`)
                    FROM        `journaldetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `journaldetail`.`companyId`                         =   '" . $this->getCompanyId() . "'
                    AND         `journaldetail`.`journalId`                         IN   (" . $journalId . ")
                    AND         `journaldetail`.`isActive`                          =   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` =   '" . self::ASSET . "'
                    GROUP BY    `journaldetail`.`journalDetailAmount`
                )
                -
                 (
                    SELECT      SUM(`journalDetailAmount`)
                    FROM        `journaldetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `journaldetail`.`companyId`                         =   '" . $this->getCompanyId() . "'
                    AND         `journaldetail`.`journalId`                         IN   (" . $journalId . ")
                    AND         `journaldetail`.`isActive`                          =   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` =   '" . self::LIABILITY . "'
                    GROUP BY    `journaldetail`.`journalDetailAmount`
                )
                 +
                 (
                    SELECT      SUM(`journalDetailAmount`)
                    FROM        `journaldetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `journaldetail`.`companyId`                         =   '" . $this->getCompanyId() . "'
                    AND         `journaldetail`.`journalId`                         IN  (" . $journalId . ")
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` =   '" . self::EQUITY . "'
                    AND         `journaldetail`.`isActive`                          =   1
                    GROUP BY    `journaldetail`.`journalDetailAmount`
                ) +
                 (
                    SELECT      SUM(`journalDetailAmount`)
                    FROM        `journaldetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `journaldetail`.`companyId`                         =   '" . $this->getCompanyId() . "'
                    AND         `journaldetail`.`journalId`                         IN   (" . $journalId . ")
                    AND         `journaldetail`.`isActive`                          =   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` =   '" . self::INCOME . "'
                    GROUP BY    `journaldetail`.`journalDetailAmount`
                )
                 -
                 (
                    SELECT      SUM(`journalDetailAmount`)
                    FROM        `journaldetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `journaldetail`.`companyId`                         =   '" . $this->getCompanyId() . "'
                    AND         `journaldetail`.`journalId`                         IN   (" . $journalId . ")
                    AND         `journaldetail`.`isActive`                          =   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` =   '" . self::EXPENSES . "'
                    GROUP BY    `journaldetail`.`journalDetailAmount`
                )
            ) as `total`

            )";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT (
                (
                    SELECT      SUM([journalDetailAmount])
                    FROM       [journalDetail]

                    JOIN        [chartOfAccount]
                    ON         [journalDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND        [journalDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON         [journalDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE      [journalDetail].[companyId]                         =   '" . $this->getCompanyId() . "'
                    AND        [journalDetail].[journalId]                         IN  (" . $journalId . ")
                    AND        [journalDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::ASSET . "'
                    GROUP BY   [journalDetail].[journalDetailAmount]
                )
                -
                 (
                    SELECT      SUM([journalDetailAmount])
                    FROM       [journalDetail]

                    JOIN        [chartOfAccount]
                    ON         [journalDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND        [journalDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON         [journalDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE      [journalDetail].[companyId]                         =   '" . $this->getCompanyId() . "'
                    AND        [journalDetail].[journalId]                         IN   (" . $journalId . ")
                    AND        [journalDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::LIABILITY . "'
                    GROUP BY   [journalDetail].[journalDetailAmount]
                )
                 +
                 (
                    SELECT      SUM([journalDetailAmount])
                    FROM       [journalDetail]

                    JOIN        [chartOfAccount]
                    ON         [journalDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND        [journalDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON         [journalDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE      [journalDetail].[companyId]                         =   '" . $this->getCompanyId() . "'
                    AND        [journalDetail].[journalId]                         IN   (" . $journalId . ")
                    AND        [journalDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::EQUITY . "'
                    GROUP BY   [journalDetail].[journalDetailAmount]
                ) +
                 (
                    SELECT      SUM([journalDetailAmount])
                    FROM       [journalDetail]

                    JOIN        [chartOfAccount]
                    ON         [journalDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND        [journalDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON         [journalDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE      [journalDetail].[companyId]                         =   '" . $this->getCompanyId() . "'
                    AND        [journalDetail].[journalId]                         IN   (" . $journalId . ")
                    AND        [journalDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::INCOME . "'
                    GROUP BY   [journalDetail].[journalDetailAmount]
                )
                 -
                 (
                    SELECT      SUM([journalDetailAmount])
                    FROM       [journalDetail]

                    JOIN        [chartOfAccount]
                    ON         [journalDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND        [journalDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON         [journalDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE      [journalDetail].[companyId]                         =   '" . $this->getCompanyId() . "'
                    AND        [journalDetail].[journalId]                         IN   (" . $journalId . ")
                    AND        [journalDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::EXPENSES . "'
                    GROUP BY   [journalDetail].[journalDetailAmount]
                )
            ) as [trialBalance]

            )";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT (
                (
                    SELECT      SUM(JOURNALDETAILAMOUNT)
                    FROM        JOURNALDETAIL
                    JOIN        CHARTOFACCOUNT
                    ON          JOURNALDETAIL.COMPANYID                         =   CHARTOFACCOUNT.COMPANYID
                    AND         JOURNALDETAIL.CHARTOFACCOUNTID                  =   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          JOURNALDETAIL.COMPANYID                         =   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         =   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       JOURNALDETAIL.COMPANYID                         =   '" . $this->getCompanyId() . "'
                    AND         JOURNALDETAIL.JOURNALID                         IN   (" . $journalId . ")
                    AND         JOURNALDETAIL.ISACTIVE                          =   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE =   '" . self::INCOME . "'
                    GROUP BY    JOURNALDETAIL.JOURNALDETAILAMOUNT
                )
                -
                 (
                    SELECT      SUM(JOURNALDETAILAMOUNT)
                    FROM        JOURNALDETAIL

                    JOIN        CHARTOFACCOUNT
                    ON          JOURNALDETAIL.COMPANYID                         =   CHARTOFACCOUNT.COMPANYID
                    AND         JOURNALDETAIL.CHARTOFACCOUNTID                  =   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          JOURNALDETAIL.COMPANYID                         =   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         =   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       JOURNALDETAIL.COMPANYID                         =   '" . $this->getCompanyId() . "'
                    AND         JOURNALDETAIL.JOURNALID                         IN   (" . $journalId . ")
                    AND         JOURNALDETAIL.ISACTIVE                          =   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE =   '" . self::LIABILITY . "'
                    GROUP BY    JOURNALDETAIL.JOURNALDETAILAMOUNT
                )
                 +
                 (
                    SELECT      SUM(JOURNALDETAILAMOUNT)
                    FROM        JOURNALDETAIL
                    JOIN        CHARTOFACCOUNT
                    ON          JOURNALDETAIL.COMPANYID                         =   CHARTOFACCOUNT.COMPANYID
                    AND         JOURNALDETAIL.CHARTOFACCOUNTID                  =   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          JOURNALDETAIL.COMPANYID                         =   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         =   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTID

                    WHERE       JOURNALDETAIL.COMPANYID                         =   '" . $this->getCompanyId() . "'
                    AND         JOURNALDETAIL.JOURNALID                         IN   (" . $journalId . ")
                    AND         JOURNALDETAIL.ISACTIVE                          =   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE=   '" . self::EQUITY . "'
                    GROUP BY    JOURNALDETAIL.JOURNALDETAILAMOUNT
                ) +
                 (
                    SELECT      SUM(JOURNALDETAILAMOUNT)
                    FROM        JOURNALDETAIL

                    JOIN        CHARTOFACCOUNT
                    ON          JOURNALDETAIL.COMPANYID = CHARTOFACCOUNT.COMPANYID
                    AND         JOURNALDETAIL.CHARTOFACCOUNTID = CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          JOURNALDETAIL.COMPANYID                         =   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         =   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       JOURNALDETAIL.COMPANYID                         =   '" . $this->getCompanyId() . "'
                    AND         JOURNALDETAIL.JOURNALID                         IN   (" . $journalId . ")
                    AND         JOURNALDETAIL.ISACTIVE                          =   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE =   '" . self::INCOME . "'
                    GROUP BY    JOURNALDETAIL.JOURNALDETAILAMOUNT
                )
                 -
                 (
                    SELECT      SUM(JOURNALDETAILAMOUNT)
                    FROM        JOURNALDETAIL

                    JOIN        CHARTOFACCOUNT
                    ON          JOURNALDETAIL.COMPANYID                         =   CHARTOFACCOUNT.COMPANYID
                    AND         JOURNALDETAIL.CHARTOFACCOUNTID                  =   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          JOURNALDETAIL.COMPANYID                         =   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT..CHARTOFACCOUNTCATEGORYID        =   CHARTOFACCOUNTCATEGORY..CHARTOFACCOUNTCATEGORYID

                    WHERE       JOURNALDETAIL.COMPANYID                         =   '" . $this->getCompanyId() . "'
                    AND         JOURNALDETAIL.JOURNALID                         IN   (" . $journalId . ")
                    AND         JOURNALDETAIL.ISACTIVE                          =   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE=   '" . self::EXPENSES . "'
                    GROUP BY    JOURNALDETAIL.JOURNALDETAILAMOUNT
                )
            ) as [trialBalance]

            )";
        }

        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $trialBalance = $row['trialBalance'];
        }
        return $trialBalance;
    }

    /**
     * Set Posting Flag
     * @return int
     */
    public function getIsPosting() {
        return $this->isPosting;
    }

    /**
     * Set Posting Flag
     * @param int $isPosting
     * @return $this;
     */
    public function setIsPosting($isPosting) {
        $this->isPosting = $isPosting;
        return $this;
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