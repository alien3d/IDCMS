<?php

namespace Core\Financial\BusinessPartner\BusinessPartner\Service;

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
require_once($newFakeDocumentRoot . "library/upload/server/php.php");

/**
 * Class Business Partner Service
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\BusinessPartner\BusinessPartner\Service
 * @subpackage BusinessPartner
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class BusinessPartnerService extends ConfigClass {

    const UNAVAILABLE_CODE = 'UNBL';

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
     * Upload Size Limit
     * @var int
     */
    private $sizeLimit;

    /**
     * Upload Path
     */
    private $uploadPath;

    /**
     * Allowed Extension
     */
    private $allowedExtensions;

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
        // list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $this->allowedExtensions = array("jpg", "jpeg", "xml", "bmp", "png");
        // max file size in bytes
        $this->setSizeLimit((8 * 1024 * 1024));
        // set upload path
        $this->setUploadPath($this->getFakeDocumentRoot() . "v3/financial/businessPartner/images/");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
    }

    /**
     * Return BusinessPartnerCategory
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
			ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      [businessPartnerCategoryId],
							[businessPartnerCategoryDescription]
			FROM        [businessPartnerCategory]
			WHERE       [isActive]  =   1
			AND         [companyId] =   '" . $this->getCompanyId() . "'
			ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT      BUSINESSPARTNERCATEGORYID AS \"businessPartnerCategoryId\",
			BUSINESSPARTNERCATEGORYDESCRIPTION AS \"businessPartnerCategoryDescription\"
			FROM        BUSINESSPARTNERCATEGORY
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['businessPartnerCategoryId'] . "'>" . $d . ". " . $row['businessPartnerCategoryDescription'] . "</option>";
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
         FROM        `businesspartnercategory`
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
     * Upload avatar before submitting the form
     * @param int $leafId Leaf Primary Key
     * @return void
     * @throws \Exception
     */
    function setBusinessPartnerPicture($leafId) {
        header('Content-Type:application/json; charset=utf-8');
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        $uploader = new \qqFileUploader($this->getAllowedExtensions(), $this->getSizeLimit());
        $filename = $uploader->getName();
        $result = $uploader->handleUpload($this->getUploadPath());

// to pass data through iframe you will need to encode all html tags
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        INSERT INTO `imagetemp`(
             `companyId`,
             `staffId`,
             `leafId`,
             `imageTempName`,
             `isNew`,
             `executeBy`,
             `executeTime`
        ) VALUES (
            '" . $this->getCompanyId() . "',
            '" . $this->getStaffId() . "',
            '" . $leafId . "',
            '" . $filename . "',
            1,
            '" . $_SESSION['staffId'] . "',NOW())";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
        INSERT INTO [imagetemp](
             [companyId],
             [staffId],
             [leafId],
             [imageTempName],
             [isNew],
             [executeBy],
             [executeTime]
        ) VALUES (
            '" . $this->getCompanyId() . "',
            '" . $this->getStaffId() . "',
            '" . $leafId . "',
            '" . $filename . "',
            1,
            '" . $_SESSION['staffId'] . "',NOW())";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
           INSERT INTO IMAGETEMP(
             COMPANYID,
             STAFFID,
             LEAFID,
             IMAGETEMPNAME,
             ISNEW,
             EXECUTEBY,
             EXECUTETIME
        ) VALUES (
            '" . $this->getCompanyId() . "',
            '" . $this->getStaffId() . "',
             '" . $leafId . "',
            '" . $filename . "',
            1,
            '" . $_SESSION['staffId'] . "',NOW())";
        }
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        exit();
    }

    /**
     * Return Allowed Extension
     * @return array|string
     */
    public function getAllowedExtensions() {
        return $this->allowedExtensions;
    }

    /**
     * Set Allowed Extensions
     * @param $value
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Service\BusinessPartnerService
     */
    public function setAllowedExtensions($value) {
        $this->allowedExtensions = $value;
        return $this;
    }

    /**
     * Return size Limit Of Staff Upload File
     * @return int
     */
    public function getSizeLimit() {
        return $this->sizeLimit;
    }

    /**
     * Set size Limit Of BusinessPartner Upload File
     * @param int $value
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Service\BusinessPartnerService
     */
    public function setSizeLimit($value) {
        $this->sizeLimit = $value;
        return $this;
    }

    /**
     * Return Upload PAth
     * @return string
     */
    public function getUploadPath() {
        return $this->uploadPath;
    }

    /**
     * Set Upload Path
     * @param string $value
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Service\BusinessPartnerService
     */
    public function setUploadPath($value) {
        $this->uploadPath = $value;
        return $this;
    }

    /**
     * Return Business Partner Office Country
     * @return array|string
     */
    public function getBusinessPartnerOfficeCountry() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `countryId`,
                     `countryDescription`
         FROM        `country`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [countryId],
                     [countryDescription]
         FROM        [country]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      COUNTRYID AS \"countryId\",
                     COUNTRYDESCRIPTION AS \"countryDescription\"
         FROM        COUNTRY
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['countryId'] . "'>" . $d . ". " . $row['countryDescription'] . "</option>";
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
     * Return Business Partner Office Country Default Value
     * @return int
     */
    public function getBusinessPartnerOfficeCountryDefaultValue() {
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
     * Return Business Partner Office State
     * @param null|int $countryId
     * @return array|string
     */
    public function getBusinessPartnerOfficeState($countryId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "	(
						  SELECT	`stateId`,
									`stateDescription`
						  FROM	 	`state`
						  WHERE	 	`stateCode`	=	'" . self::UNAVAILABLE_CODE . "'
						  AND      	`companyId` =   '" . $this->getCompanyId() . "'
						  LIMIT 1
					  ) UNION ALL (
						SELECT      `stateId`,
								 `stateDescription`
						FROM        `state`
						WHERE       `isActive`  =   1
						AND         `companyId` =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql .= "
						AND	`countryId`	=	'" . $countryId . "'
						";
            } else {
                $sql .= "
						AND	`countryId`='" . $this->getOverrideCountry() . "'
						";
            }
            $sql .= "
						ORDER BY    `isDefault` )  ;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [stateId],
                     [stateDescription]
         FROM        [state]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql .= "
			AND	[countryId]	=	'" . $countryId . "'
			";
            } else {
                $sql .= "
			AND	[countryId]	=	'" . $this->getOverrideCountry() . "'
			";
            }
            $sql .= "
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      STATEID AS \"stateId\",
                     STATEDESCRIPTION AS \"stateDescription\"
         FROM        STATE
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql .= "
			AND	 COUNTRYID	=	'" . $countryId . "'
			";
            } else {
                $sql .= "
			AND	 COUNTRYID	='" . $this->getOverrideCountry() . "'
			";
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['stateId'] . "'>" . $d . ". " . $row['stateDescription'] . "</option>";
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
     * get master setting of the counter.. too heavy to render all city
     */
    private function getOverrideCountry() {
        $countryId = null;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT countryId FROM `systemsetting` WHERE `companyId`='" . $this->getCompanyId() . "'
			";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT [countryId] FROM [systemSetting] WHERE [companyId] ='" . $this->getCompanyId() . "'
			";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT COUNTRYID FROM SYSTEMSETTING WHERE COMPANYID='" . $this->getCompanyId() . "'
			";
        } else {
            echo "no vendor ????????????????????";
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
        } else {
            
        }
        return $countryId;
    }

    /**
     * Return Business Partner Office State Default Value
     * @return int
     */
    public function getBusinessPartnerOfficeStateDefaultValue() {
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
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
     * Return BusinessPartnerOfficeCity
     * @param null|int $countryId
     * @param null|int $stateId
     * @param null|int $divisionId Division.. Only Apply on Malaysia->Sarawak Sabah
     * @param null|int $districtId
     * @return array|string
     */
    public function getBusinessPartnerOfficeCity(
    $countryId = null, $stateId = null, $divisionId = null, $districtId = null
    ) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			(
				SELECT	`cityId`,
						`cityDescription`,
						`stateDescription`
				FROM	`city`
				JOIN	`state`
				USING	(`companyId`,`stateId`)
				WHERE	`city`.`isActive`  	=   1
				AND		`city`.`companyId` 	=   '" . $this->getCompanyId() . "'
				AND		`city`.`cityCode`	=	'" . self::UNAVAILABLE_CODE . "'
				LIMIT 1
			) UNION ALL (
				SELECT	`cityId`,
						 `cityDescription`,
						 `stateDescription`
				FROM	`city`
				JOIN		 `state`
				USING		 (`companyId`,`stateId`)
				WHERE       `city`.`isActive`  	=   1
				AND         `city`.`companyId` 	=   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql .= "
				AND `city`.`countryId`='" . $countryId . "'";
            } else {
                $sql .= "
				AND `city`.`countryId` = '" . $this->getOverrideCountry() . "'";
            }
            if ($stateId) {
                $sql .= "
				AND	`city`.`stateId`=	'" . $stateId . "'";
            }
            if ($divisionId) {
                $sql .= "
				AND	`city`.`divisionId` =	'" . $divisionId . "'";
            }
            if ($districtId) {
                $sql .= "
				AND	`city`.`districtId`	='" . $districtId . "'";
            }
            $sql .= "
				ORDER BY   `state`.`stateDescription`,
					`city`.`cityDescription` )  ;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [cityId],
                     [cityDescription],
					 [stateDescription]
         FROM        [city]
		 JOIN		 [state]
		 ON			 [city].[companyId] =  [state].[companyId]
		 AND		 [city].[stateId] 	=  [state].[stateId]
         WHERE       [city].[isActive]  =   1
         AND         [city].[companyId] =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql .= "
			AND [city].[countryId]='" . $countryId . "'";
            } else {
                $sql .= "
			AND [city].[countryId] = '" . $this->getOverrideCountry() . "'";
            }
            if ($stateId) {
                $sql .= "
			AND	[city].[stateId]=	'" . $stateId . "'";
            }
            if ($divisionId) {
                $sql .= "
			AND	[city].[divisionId] =	'" . $divisionId . "'";
            }
            if ($districtId) {
                $sql .= "
			AND	[city].[districtId]	='" . $districtId . "'";
            }
            $sql .= "
         ORDER BY    [state].[stateDescription],
					 [city].[cityDescription]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      CITYID 			AS 	\"cityId\",
                     CITYDESCRIPTION 	AS 	\"cityDescription\",
					 STATEDESCRIPTION	AS	\"stateDescription\"
         FROM        CITY
		 JOIN		 STATE
		 ON          CITY.COMPANYID	= STATE.COMPANYID
		 AND		 CITY.STATEID	= STATE.STATEID
         WHERE       CITY.ISACTIVE    =   1
         AND         CITY.COMPANYID   =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql .= "
			AND CITY.COUNTRYID	=	'" . $countryId . "'";
            } else {
                $sql .= "
			AND CITY.COUNTRYID 	= '" . $this->getOverrideCountry() . "'";
            }
            if ($stateId) {
                $sql .= "
			AND	CITY.STATEID	=	'" . $stateId . "'";
            }
            if ($divisionId) {
                $sql .= "
			AND	CITY.DIVISIONID =	'" . $divisionId . "'";
            }
            if ($districtId) {
                $sql .= "
			AND	CITY.DISTRICTID	='" . $districtId . "'";
            }
            $sql .= "
         ORDER BY    STATE.DESCRIPTION,
					 CITY.DESCRIPTION";
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
                    $str .= "<option value='" . $row['cityId'] . "'>" . $d . ". " . $row['cityDescription'] . "</option>";
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
     * Return Business Partner Office City Default Value
     * @return int
     */
    public function getBusinessPartnerOfficeCityDefaultValue() {
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
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
     * Return Business Partner Shipping Country
     * @return array|string
     */
    public function getBusinessPartnerShippingCountry() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `countryId`,
                     `countryDescription`
         FROM        `country`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [countryId],
                     [countryDescription]
         FROM        [country]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      COUNTRYID AS \"countryId\",
                     COUNTRYDESCRIPTION AS \"countryDescription\"
         FROM        COUNTRY
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['countryId'] . "'>" . $d . ". " . $row['countryDescription'] . "</option>";
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
     * Return Business Partner Shipping State
     * @param null|int $countryId Country Primary Key
     * @return array|string
     */
    public function getBusinessPartnerShippingState($countryId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "(
						  SELECT	`stateId`,
									`stateDescription`
						  FROM	 	`state`
						  WHERE	 	`stateCode`	=	'" . self::UNAVAILABLE_CODE . "'
						  AND      	`companyId` =   '" . $this->getCompanyId() . "'
						  LIMIT 1
					  ) UNION ALL (
         SELECT      `stateId`,
                     `stateDescription`
         FROM        `state`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql .= "
			AND	`countryId`	=	'" . $countryId . "'
			";
            } else {
                $sql .= "
			AND	`countryId`='" . $this->getOverrideCountry() . "'
			";
            }
            $sql .= "
		 ORDER BY    `isDefault`) ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [stateId],
                     [stateDescription]
         FROM        [state]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql .= "
			AND	[countryId]	=	'" . $countryId . "'
			";
            } else {
                $sql .= "
			AND	[countryId]	=	'" . $this->getOverrideCountry() . "'
			";
            }
            $sql .= "
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      STATEID AS \"stateId\",
                     STATEDESCRIPTION AS \"stateDescription\"
         FROM        STATE
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql .= "
			AND	 COUNTRYID	=	'" . $countryId . "'
			";
            } else {
                $sql .= "
			AND	 COUNTRYID	='" . $this->getOverrideCountry() . "'
			";
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['stateId'] . "'>" . $d . ". " . $row['stateDescription'] . "</option>";
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
     * Return BusinessPartnerShippingCity
     * @param null|int $countryId Country Primary Key
     * @param null|int $stateId State Primary Key
     * @param null|int $divisionId Division.. Only Apply on Malaysia->Sarawak Sabah
     * @param null|int $districtId District Primary Key
     * @return array|string
     */
    public function getBusinessPartnerShippingCity(
    $countryId = null, $stateId = null, $divisionId = null, $districtId = null
    ) {
        $str = null;
        $sql = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			(
				SELECT	`cityId`,
						`cityDescription`,
						`stateDescription`
				FROM	`city`
				JOIN	`state`
				USING	(`companyId`,`stateId`)
				WHERE	`city`.`isActive`  	=   1
				AND		`city`.`companyId` 	=   '" . $this->getCompanyId() . "'
				AND	 	`city`.`cityCode`	=	'" . self::UNAVAILABLE_CODE . "'
				LIMIT 1
			) UNION ALL (
				SELECT      `cityId`,
						 `cityDescription`,
						 `stateDescription`
				FROM        `city`
				JOIN		 `state`
				USING		 (`companyId`,`stateId`)
				WHERE       `city`.`isActive`  	=   1
				AND         `city`.`companyId` 	=   '" . $this->getCompanyId() . "'
		 ";
            if ($countryId) {
                $sql .= "
			AND `city`.`countryId`='" . $countryId . "'";
            } else {
                $sql .= "
			AND `city`.`countryId` = '" . $this->getOverrideCountry() . "'";
            }
            if ($stateId) {
                $sql .= "
			AND	`city`.`stateId`=	'" . $stateId . "'";
            }
            if ($divisionId) {
                $sql .= "
			AND	`city`.`divisionId` =	'" . $divisionId . "'";
            }
            if ($districtId) {
                $sql .= "
			AND	`city`.`districtId`	='" . $districtId . "'";
            }
            $sql .= "
		 ORDER BY   `state`.`stateDescription`,
					`city`.`cityDescription`);";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [cityId],
                     [cityDescription],
					 [stateDescription]
         FROM        [city]
		 JOIN		 [state]
		 ON			 [city].[companyId] =  [state].[companyId]
		 AND		 [city].[stateId] 	=  [state].[stateId]
         WHERE       [city].[isActive]  =   1
         AND         [city].[companyId] =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql .= "
			AND [city].[countryId]='" . $countryId . "'";
            } else {
                $sql .= "
			AND [city].[countryId] = '" . $this->getOverrideCountry() . "'";
            }
            if ($stateId) {
                $sql .= "
			AND	[city].[stateId]=	'" . $stateId . "'";
            }
            if ($divisionId) {
                $sql .= "
			AND	[city].[divisionId] =	'" . $divisionId . "'";
            }
            if ($districtId) {
                $sql .= "
			AND	[city].[districtId]	='" . $districtId . "'";
            }
            $sql .= "
         ORDER BY    [state].[stateDescription],
					 [city].[cityDescription]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      CITYID 			AS 	\"cityId\",
                     CITYDESCRIPTION 	AS 	\"cityDescription\",
					 STATEDESCRIPTION	AS	\"stateDescription\"
         FROM        CITY
		 JOIN		 STATE
		 ON          CITY.COMPANYID	= STATE.COMPANYID
		 AND		 CITY.STATEID	= STATE.STATEID
         WHERE       CITY.ISACTIVE    =   1
         AND         CITY.COMPANYID   =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql .= "
			AND CITY.COUNTRYID	=	'" . $countryId . "'";
            } else {
                $sql .= "
			AND CITY.COUNTRYID 	= '" . $this->getOverrideCountry() . "'";
            }
            if ($stateId) {
                $sql .= "
			AND	CITY.STATEID	=	'" . $stateId . "'";
            }
            if ($divisionId) {
                $sql .= "
			AND	CITY.DIVISIONID =	'" . $divisionId . "'";
            }
            if ($districtId) {
                $sql .= "
			AND	CITY.DISTRICTID	='" . $districtId . "'";
            }
            $sql .= "
         ORDER BY    STATE.DESCRIPTION,
					 CITY.DESCRIPTION";
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
                    $str .= "<option value='" . $row['cityId'] . "'>" . $d . ". " . $row['cityDescription'] . "</option>";
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
     * Take the last temp file
     * @param int $leafId Leaf Primary Key
     * @param int $businessPartnerId Business Primary Key
     */
    function setTransferBusinessPartnerPicture($leafId, $businessPartnerId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT      `imageTempName`
			FROM        `imagetemp`
			WHERE       `isNew`		=	1
			AND         `staffId`	=	'" . $this->getStaffId() . "'
			AND			`leafId`	=	'" . $leafId . "'
			ORDER BY    `imageTempId`
						DESC
			LIMIT        1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      TOP 1 [imageTempName]
			FROM        [imageTemp]
			WHERE       [isNew]		=	1
			AND         [staffId]	=	'" . $this->getStaffId() . "'
			AND			[leafId]	=	'" . $leafId . "'
			ORDER BY    [imageTempId]
						DESC";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT      IMAGETEMPNAME AS \"imageTempName\"
			FROM        IMAGETEMP
			WHERE       ISNEW		=	1
			AND         STAFFID		=	'" . $this->getStaffId() . "'
			AND			LEAFID		=	'" . $leafId . "'
			AND			ROWNUM =1
			ORDER BY    IMAGETEMPID
						DESC";
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
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
				UPDATE `businesspartner`
				SET    `businessPartnerPicture`    	=	'" . $row['imageTempName'] . "'
				WHERE  `businessPartnerId`			= 	'" . $businessPartnerId . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
				UPDATE [businessPartner]
				SET    [businessPartnerPicture]    	=	'" . $row['imageTempName'] . "'
				WHERE  [businessPartnerId]			= 	'" . $businessPartnerId . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
				UPDATE BUSINESSPARTNER
				SET    BUSINESSPARTNERPICTURE    		=	'" . $row['imageTempName'] . "'
				WHERE  BUSINESSPARTNERID			= 	'" . $businessPartnerId . "'";
            }
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
            // update back  the last image file to 0 preventing update the same thing again
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
				UPDATE `imagetemp`
				SET    `isNew`    	=	'0'
				WHERE  `staffId`    = 	'" . $this->getStaffId() . "'
				AND	   `leafId`		=	'" . $leafId . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
				UPDATE [imageTemp]
				SET    [isNew]    	=	'0'
				WHERE  [staffId]    = 	'" . $this->getStaffId() . "'
				AND	   [leafId]		=	'" . $leafId . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
				UPDATE IMAGETEMP
				SET    ISNEW    	=	'0'
				WHERE  STAFFID    	= 	'" . $this->getStaffId() . "'
				AND	   LEAFID		=	'" . $leafId . "'";
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
     * Return Business Partner Contact
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
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
     * @param string $businessPartnerCompany Company Name
     * @param string $businessPartnerAddress Address
     * @return int $businessPartnerId Business Partner Primary Key
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
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
        $countryId = null;
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
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
     * @param int $businessPartnerId Business Partner Primary Key
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
     * Return FollowUp
     * @return array|string
     */
    public function getFollowUp() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `followUpId`,
                     `followUpDescription`
         FROM        `followup`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [followUpId],
                     [followUpDescription]
         FROM        [followUp]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      FOLLOWUPID AS \"followUpId\",
                     FOLLOWUPDESCRIPTION AS \"followUpDescription\"
         FROM        FOLLOWUP
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['followUpId'] . "'>" . $d . ". " . $row['followUpDescription'] . "</option>";
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
        } else if ($this->getServiceOutput() == 'html') {
            return $items;
        }
        return $items;
    }

    /**
     * Return Invoice
     * @return array|string
     */
    public function getInvoice() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `invoiceId`,
                     `invoiceDescription`
         FROM        `invoice`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [invoiceId],
                     [invoiceDescription]
         FROM        [invoice]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      INVOICEID AS \"invoiceId\",
                     INVOICEDESCRIPTION AS \"invoiceDescription\"
         FROM        INVOICE
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['invoiceId'] . "'>" . $d . ". " . $row['invoiceDescription'] . "</option>";
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
        } else if ($this->getServiceOutput() == 'html') {
            return $items;
        }
        return $items;
    }

    /**
     * Return Purchase Invoice
     * @return array|string
     */
    public function getPurchaseInvoice() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `purchaseInvoiceId`,
                     `purchaseInvoiceDescription`
         FROM        `purchaseinvoice`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [purchaseInvoiceId],
                     [purchaseInvoiceDescription]
         FROM        [purchaseInvoice]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      PURCHASEINVOICEID AS \"purchaseInvoiceId\",
                     PURCHASEINVOICEDESCRIPTION AS \"purchaseInvoiceDescription\"
         FROM        PURCHASEINVOICE
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['purchaseInvoiceId'] . "'>" . $d . ". " . $row['purchaseInvoiceDescription'] . "</option>";
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
        } else if ($this->getServiceOutput() == 'html') {
            return $items;
        }
        return $items;
    }

    /**
     * Return Invoice Follow Up
     * @param int $invoiceId Invoice Primary Key
     * @return array|string
     */
    public function getInvoiceFollowUp($invoiceId) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      *
            FROM        `invoicefollowup`
            WHERE       `isActive`  =   1
            AND         `companyId` =   '" . $this->getCompanyId() . "'
            AND        `invoiceId`='" . $invoiceId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      [invoiceId],
                        [invoiceDescription]
            FROM        [invoice]
            WHERE       [isActive]  =   1
            AND         [companyId] =   '" . $this->getCompanyId() . "'
            AND        [invoiceId]='" . $invoiceId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      INVOICEFOLLOWUPID           AS  \"invoiceFollowUpId\",
                        INVOICEFOLLOWUPDATE         AS  \"invoiceFollowUpDate\",
                        INVOICEFOLLOWUPDESCRIPTION  AS  \"invoiceFollowUpDescription\"
            FROM        INVOICEFOLLOWUP
            WHERE       ISACTIVE    =   1
            AND         COMPANYID   =   '" . $this->getCompanyId() . "'
            AND        INVOICEID='" . $invoiceId . "'";
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
                    $str .= "<option value='" . $row['invoiceFollowUpId'] . "'>" . $d . ". " . $row['invoiceFollowUpDescription'] . "</option>";
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
        } else if ($this->getServiceOutput() == 'html') {
            return $items;
        }
        return $items;
    }

    /**
     * Return Purchase Invoice Follow Up
     * @param int $purchaseInvoiceId Purchase Invoice Follow Up
     * @return array
     */
    public function getPurchaseInvoiceFollowUp($purchaseInvoiceId) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      *
            FROM        `purchaseinvoicefollowup`
            WHERE       `isActive`  =   1
            AND         `companyId` =   '" . $this->getCompanyId() . "'
            AND         `purchaseInvoiceId`    =   '" . $purchaseInvoiceId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      *
            FROM        [purchaseInvoiceFollowUp]
            WHERE       [isActive]  =   1
            AND         [companyId] =   '" . $this->getCompanyId() . "'
            AND         [purchaseInvoiceId]   ='" . $purchaseInvoiceId . "'   ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      PURCHASEINVOICEFOLLOWUPID           AS  \"purchaseInvoiceFollowUpId\",
                        PURCHASEINVOICEFOLLOWUPDATE         AS  \"purchaseInvoiceFollowUpDate\",
                        PURCHASEINVOICEFOLLOWUPDESCRIPTION  AS  \"purchaseInvoiceFollowUpDescription\"
            FROM        PURCHASEINVOICEFOLLOWUP
            WHERE       ISACTIVE    =   1
            AND         COMPANYID   =   '" . $this->getCompanyId() . "'
            AND         PURCHASEINVOICEID='" . $purchaseInvoiceId . "'";
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
                    $str .= "<option value='" . $row['purchaseInvoiceFollowUpId'] . "'>" . $d . ". " . $row['purchaseInvoiceFollowUpDescription'] . "</option>";
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
        } else if ($this->getServiceOutput() == 'html') {
            return $items;
        }
        return $items;
    }

    /**
     * Normalize Business Partner Data
     */
    public function normalizeDataBusinessPartner() {
        
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

/**
 * Class Business Partner Cashbook Service
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\BusinessPartner\BusinessPartner\Service
 * @subpackage BusinessPartner
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class BusinessPartnerCashBookStatisticsService extends ConfigClass {

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
     * Day
     * @var int
     */
    private $day;

    /**
     * Week
     * @var int
     */
    private $week;

    /**
     * Month
     * @var int
     */
    private $month;

    /**
     * Year
     * @var int
     */
    private $year;

    /**
     * Total Day In Month
     * @var int
     */
    private $totalDayInMonth;

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
     *
     * @param string $date
     * @param null|int $businessPartnerId
     * @return mixed
     * @throws \Exception
     */
    function getBusinessPartnerCashBookTime($date, $businessPartnerId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT  ";
            $strInside = null;
            $hour = null;
            while ($hour++ < 24) {
                $strInside .= "
                IFNULL(SUM(IF(`cashBookDate`  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                                $hour
                        ) . "%',1,0)) ,0)as `" . $hour . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    `cashbookledger`
            JOIN    `businessPartner`
            ON      `cashbookledger`.`companyId` = `businessPartner`.`companyId`
            AND     `cashbookledger`.`businessPartnerId`   =   `businessPartner`.`businessPartnerId`
            JOIN    `chartofaccount`
            ON      `cashbookledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `cashbookledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId` 
            WHERE   `cashbookledger`.`companyId`	=	'" . $this->getCompanyId() . "'";
            if ($businessPartnerId) {
                $sql .= " AND `cashbookledger`.`businessPartnerId` = '" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT  ";
            $strInside = null;
            $hour = null;
            while ($hour++ < 24) {
                $strInside .= "IFNULL(SUM(IF([cashBookDate]  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                                $hour
                        ) . "%',1,0)) ,0) AS `" . $hour . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    [cashBookLedger]
            JOIN    [businessPartner]
            ON      [cashBookLedger].[companyId]        =   [businessPartner].[companyId]
            AND     [cashBookLedger].[businessPartnerId]           =   [businessPartner].[businessPartnerId]
            JOIN    [chartOfAccount] 
            ON      [cashBookLedger].[companyId]        =   [chartOfAccount].[companyId]
            AND     [cashBookLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
            WHERE   [cashBookLedger].[companyId]	=	'" . $this->getCompanyId() . "'";
            if ($businessPartnerId) {
                $sql .= " AND [cashBookLedger].[businessPartnerId] = '" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT  ";
            $strInside = null;
            $hour = null;
            while ($hour++ < 24) {
                $strInside .= "IFNULL(SUM(IF(CASHBOOKDATE  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                                $hour
                        ) . "%',1,0)) ,0) AS `" . $hour . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    CASHBOOKLEDGER
			JOIN    BUSINESSPARTNER
            ON      CASHBOOKLEDGER.COMPANYID        =   BUSINESSPARTNER.COMPANYID
            AND     CASHBOOKLEDGER.BUSINESSPARTNERID           =   BUSINESSPARTNER.BUSINESSPARTNERID
            JOIN    CHARTOFACCOUNT 
            ON      CASHBOOKLEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     CASHBOOKLEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
			WHERE   CASHBOOKLEDGER.COMPANYID	=	'" . $this->getCompanyId() . "'";
            if ($businessPartnerId) {
                $sql .= " AND BUSINESSPARTNERID = '" . $businessPartnerId . "'";
            }
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
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Get Month
     * @return int
     */
    function getMonth() {
        return $this->month;
    }

    /**
     * Set Month
     * @param int $value
     */
    function setMonth($value) {
        $this->month = $value;
    }

    /**
     *
     * @return int
     */
    function getDay() {
        return $this->day;
    }

    /**
     *
     * @param int $value
     */
    function setDay($value) {
        $this->day = $value;
    }

    /**
     * Return Year
     * @return int
     */
    function getYear() {
        return $this->year;
    }

    /**
     * Set Year
     * @param int $value
     */
    function setYear($value) {
        $this->year = $value;
    }

    /**
     *
     * @param string $dateInfo
     * @return string
     */
    function changeZero($dateInfo) {
        if (strlen($dateInfo) == 1) {
            $dateInfo = '0' . $dateInfo;
        }
        return ($dateInfo);
    }

    /**
     *
     * @param string $date
     * @param null|int $businessPartnerId
     * @return mixed
     * @throws \Exception
     */
    function getBusinessPartnerCashBookWeekly($date, $businessPartnerId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $d = new \DateTime(date("Y-m-d", mktime(0, 0, 0, $this->getMonth(), $this->getDay(), $this->getYear())));
        $weekday = $d->format('w');
        $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
        $d->modify("-$diff day");
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT ";
            $strInside = null;
            for ($i = 0; $i < 8; $i++) {
                $strInside .= "
                IFNULL(SUM(IF(`cashBookDate` like '" . $d->format('Y-m-d') . "%',1,0) ) ,0)as `" . $i . "`,";
                $d->modify('+1 day');
            }

            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    `cashbookledger`
            JOIN    `businessPartner`
            ON      `cashbookledger`.`companyId` = `businessPartner`.`companyId`
            AND     `cashbookledger`.`businessPartnerId`   =   `businessPartner`.`businessPartnerId`
            JOIN    `chartofaccount`
            ON      `cashbookledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `cashbookledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId`
            WHERE   `cashbookledger`.`companyId`	=	'" . $this->getCompanyId() . "'
            ";
            if ($businessPartnerId) {
                $sql .= " AND `cashbookledger`.`businessPartnerId`	=	'" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self ::MSSQL) {
            $sql = "
			SELECT ";
            $strInside = null;
            for ($i = 0; $i < 8; $i++) {

                $strInside .= "IFNULL(SUM(IF(`cashBookDate` like '" . $d->format(
                                'Y-m-d'
                        ) . "%',1,0) ) ,0)as `" . $i . "`,";
                $d->modify('+1 day');
            }

            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    [cashBookLedger]
            JOIN    [businessPartner]
            ON      [cashBookLedger].[companyId]        =   [businessPartner].[companyId]
            AND     [cashBookLedger].[businessPartnerId]           =   [businessPartner].[businessPartnerId]
            JOIN    [chartOfAccount]
            ON      [cashBookLedger].[companyId]        =   [chartOfAccount].[companyId]
            AND     [cashBookLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
            WHERE   [cashBookLedger].[companyId]	=	'" . $this->getCompanyId() . "'
            ";
            if ($businessPartnerId) {
                $sql .= " AND [cashBookLedger].[businessPartnerId]	=	'" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT ";
            $strInside = null;
            for ($i = 0; $i < 8; $i++) {

                $strInside .= "IFNULL(SUM(IF(`cashBookDate` like '" . $d->format(
                                'Y-m-d'
                        ) . "%',1,0) ) ,0)as `" . $i . "`,";
                $d->modify('+1 day');
            }

            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    CASHBOOKLEDGER
            JOIN	`BUSINESSPARTNER`
            USING	(`COMPANYID`,`BUSINESSPARTNERID`)
            WHERE   CASHBOOKLEDGER.COMPANYID	=	'" . $this->getCompanyId() . "'
            ";
            if ($businessPartnerId) {
                $sql .= " AND CASHBOOKLEDGER.BUSINESSPARTNERID	=	'" . $businessPartnerId . "'";
            }
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
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     *
     * @param string $date
     * @param null|int $businessPartnerId
     * @return mixed
     * @throws \Exception
     */
    function getBusinessPartnerCashBookDaily($date, $businessPartnerId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  ";
            $strInside = null;
            for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
                $strInside .= "IFNULL(SUM(IF(`cashBookDate`  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($i) . "%',1,0)) ,0)as `" . $i . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    `cashbookledger`
            JOIN    `businessPartner`
            ON      `cashbookledger`.`companyId` = `businessPartner`.`companyId`
            AND     `cashbookledger`.`businessPartnerId`   =   `businessPartner`.`businessPartnerId`
            JOIN    `chartofaccount`
            ON      `cashbookledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `cashbookledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId`
            WHERE   `cashbookledger`.`companyId`	=	'" . $this->getCompanyId() . "'
            AND     MONTH(`cashBookDate`) = '" . $this->getMonth() . "'
            AND     YEAR(`cashBookDate`) = '" . $this->getYear() . "';";
            if ($businessPartnerId) {
                $sql .= " AND `businessPartnerId`	=	'" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  ";
            $strInside = null;
            for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
                $strInside .= "
                IFNULL(SUM(IF([cashBookDate]  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($i) . "%',1,0)) ,0)as `" . $i . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    [cashBookLedger]
            JOIN    [businessPartner]
            ON      [cashBookLedger].[companyId]        =   [businessPartner].[companyId]
            AND     [cashBookLedger].[businessPartnerId]           =   [businessPartner].[businessPartnerId]
            JOIN    [chartOfAccount]
            ON      [cashBookLedger].[companyId]        =   [chartOfAccount].[companyId]
            AND     [cashBookLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
            WHERE   [cashBookLedger].[companyId]	=	'" . $this->getCompanyId() . "'
            AND     MONTH([cashBookDate]) 			= 	'" . $this->getMonth() . "'
            AND     YEAR([cashBookDate]) 			= 	'" . $this->getYear() . "';";
            if ($businessPartnerId) {
                $sql .= " AND [businessPartnerId]	=	'" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  ";
            $strInside = null;
            for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
                $strInside .= "IFNULL(SUM(IF(CASHBOOKDATE  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($i) . "%',1,0)) ,0) AS \"" . $i . "\",";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    CASHBOOKLEDGER
            JOIN    BUSINESSPARTNER
            ON      CASHBOOKLEDGER.COMPANYID        =   BUSINESSPARTNER.COMPANYID
            AND     CASHBOOKLEDGER.BUSINESSPARTNERID           =   BUSINESSPARTNER.BUSINESSPARTNERID
            JOIN    CHARTOFACCOUNT
            ON      CASHBOOKLEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     CASHBOOKLEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
            WHERE   CASHBOOKLEDGER.COMPANYID	=	'" . $this->getCompanyId() . "'
            AND     MONTH(CASHBOOKDATE) = '" . $this->getMonth() . "'
            AND     YEAR(CASHBOOKDATE) = '" . $this->getYear() . "';";
            if ($businessPartnerId) {
                $sql .= " AND BUSINESSPARTNERID	=	'" . $businessPartnerId . "'";
            }
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
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Total Day In month
     * @return int
     */
    function getTotalDayInMonth() {
        return $this->totalDayInMonth;
    }

    /**
     * Set Total Day In Month
     * @param int $value
     */
    function setTotalDayInMonth($value) {
        $this->totalDayInMonth = $value;
    }

    /**
     *
     * @param string $date
     * @param null|int $businessPartnerId
     * @return mixed
     * @throws \Exception
     */
    function getBusinessPartnerCashBookMonthly($date, $businessPartnerId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  IFNULL(SUM(IF(month(`cashBookDate`) = 1,1,0)),0)as `jan`,
                    IFNULL(SUM(IF(month(`cashBookDate`) = 2,1,0)),0)as `feb`,
                    IFNULL(SUM(IF(month(`cashBookDate`) = 3,1,0)),0)as `mac`,
                    IFNULL(SUM(IF(month(`cashBookDate`) = 4,1,0)),0)as `apr`,
                    IFNULL(SUM(IF(month(`cashBookDate`) = 5,1,0)),0)as `may`,
                    IFNULL(SUM(IF(month(`cashBookDate`) = 6,1,0)),0)as `jun`,
                    IFNULL(SUM(IF(month(`cashBookDate`) = 7,1,0)),0)as `jul`,
                    IFNULL(SUM(IF(month(`cashBookDate`) = 8,1,0)),0)as `aug`,
                    IFNULL(SUM(IF(month(`cashBookDate`) = 9,1,0)),0) as `sep`,
                    IFNULL(SUM(IF(month(`cashBookDate`) = 10,1,0)),0) as `oct`,
                    IFNULL(SUM(IF(month(`cashBookDate`) = 11,1,0)),0)as `nov`,
                    IFNULL(SUM(IF(month(`cashBookDate`) = 12,1,0)),0)as `dec`
            FROM    `cashbookledger`
                JOIN    `businessPartner`
            ON      `cashbookledger`.`companyId` = `businessPartner`.`companyId`
            AND     `cashbookledger`.`businessPartnerId`   =   `businessPartner`.`businessPartnerId`
            JOIN    `chartofaccount`
            ON      `cashbookledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `cashbookledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId`
            WHERE   `cashbookledger`.`companyId`	=	'" . $this->getCompanyId() . "'
            AND		YEAR(`cashBookDate`) = '" . $this->getYear() . "'";
            if ($businessPartnerId) {
                $sql .= " AND `cashbookledger`.`businessPartnerId`	=	'" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  IFNULL(SUM(IF(month(`cashBookDate`) = 1,1,0)),0)    AS  [jan],
                    IFNULL(SUM(IF(month(`cashBookDate`) = 2,1,0)),0)    AS  [feb],
                    IFNULL(SUM(IF(month(`cashBookDate`) = 3,1,0)),0)    AS  [mac],
                    IFNULL(SUM(IF(month(`cashBookDate`) = 4,1,0)),0)    AS  [apr],
                    IFNULL(SUM(IF(month(`cashBookDate`) = 5,1,0)),0)    AS  [may],
                    IFNULL(SUM(IF(month(`cashBookDate`) = 6,1,0)),0)    AS  [jun],
                    IFNULL(SUM(IF(month(`cashBookDate`) = 7,1,0)),0)    AS  [jul],
                    IFNULL(SUM(IF(month(`cashBookDate`) = 8,1,0)),0)    AS  [aug],
                    IFNULL(SUM(IF(month(`cashBookDate`) = 9,1,0)),0)    AS  [sep],
                    IFNULL(SUM(IF(month(`cashBookDate`) = 10,1,0)),0)    AS  [oct],
                    IFNULL(SUM(IF(month(`cashBookDate`) = 11,1,0)),0)    AS  [nov],
                    IFNULL(SUM(IF(month(`cashBookDate`) = 12,1,0)),0)    AS  [dec]
            FROM    [cashBookLedger]
            JOIN	[businessPartner]
            ON      [cashBookLedger].[companyId]        =   [businessPartner].[companyId]
            AND     [cashBookLedger].[businessPartnerId]           =   [businessPartner].[businessPartnerId]
            JOIN    [chartOfAccount]
            ON      [cashBookLedger].[companyId]        =   [chartOfAccount].[companyId]
            AND     [cashBookLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
            WHERE   [cashBookLedger].[companyId]	=	'" . $this->getCompanyId() . "'
            AND		YEAR([cashBookDate]) = '" . $this->getYear() . "'";
            if ($businessPartnerId) {
                $sql .= " AND [cashBookLedger].[businessPartnerId]	=	'" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 1,1,0)),0)	AS	\"jan\",
                    IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 2,1,0)),0)	AS	\"feb\",
                    IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 3,1,0)),0)	AS	\"mac\",
                    IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 4,1,0)),0)	AS	\"apr\",
                    IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 5,1,0)),0)	AS	\"may\",
                    IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 6,1,0)),0)	AS	\"jun\",
                    IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 7,1,0)),0)	AS	\"jul\",
                    IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 8,1,0)),0)	AS	\"aug\",
                    IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 9,1,0)),0)	AS	\"sep\",
                    IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 10,1,0)),0)	AS	\"oct\",
                    IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 11,1,0)),0)	AS	\"nov\",
                    IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 12,1,0)),0)	AS	\"dec\"
            FROM    CASHBOOKLEDGER
            JOIN    BUSINESSPARTNER
            ON      CASHBOOKLEDGER.COMPANYID        =   BUSINESSPARTNER.COMPANYID
            AND     CASHBOOKLEDGER.BUSINESSPARTNERID           =   BUSINESSPARTNER.BUSINESSPARTNERID
            JOIN    CHARTOFACCOUNT
            ON      CASHBOOKLEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     CASHBOOKLEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
            WHERE   CASHBOOKLEDGER.COMPANYID	=	'" . $this->getCompanyId() . "'
            AND		YEAR(CASHBOOKDATE) = '" . $this->getYear() . "'";
            if ($businessPartnerId) {
                $sql .= " AND CASHBOOKLEDGER.BUSINESSPARTNERID	=	'" . $businessPartnerId . "'";
            }
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
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     *
     * @param string $date
     * @param null|int $businessPartnerId
     * @return mixed
     * @throws \Exception
     */
    function getBusinessPartnerCashBookYearly($date, $businessPartnerId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  ABS(SUM(`cashbookledger`.`cashBookAmount`)) as `totalCashBookAmount`
            FROM    `cashbookledger`
                JOIN    `businessPartner`
            ON      `cashbookledger`.`companyId` = `businessPartner`.`companyId`
            AND     `cashbookledger`.`businessPartnerId`   =   `businessPartner`.`businessPartnerId`
            JOIN    `chartofaccount`
            ON      `cashbookledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `cashbookledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId`
            WHERE   `cashbookledger`.`companyId`	='" . $this->getCompanyId() . "'
            AND		YEAR(`cashbookledger`.`cashBookDate`) =  '" . $this->getYear() . "'";
            if ($businessPartnerId) {
                $sql .= "  AND    `cashbookledger`.`businessPartnerId`='" . $businessPartnerId . "'";
            }
            $sql .= "
            AND     YEAR(`cashbookledger`.`cashBookDate`)
            BETWEEN YEAR(DATE_SUB(NOW(), INTERVAL 3 YEAR)) AND YEAR(NOW())
            ORDER 	BY `cashbookledger`.`businessPartnerId`
            GROUP	BY (`cashbookledger`.`businessPartnerId`)";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  ABS(SUM([cashBookAmount])) as [totalCashBookAmount]
            FROM    [cashBookLedger]
            JOIN    [businessPartner]
            ON      [cashBookLedger].[companyId]        =   [businessPartner].[companyId]
            AND     [cashBookLedger].[businessPartnerId]           =   [businessPartner].[businessPartnerId]
            JOIN    [chartOfAccount]
            ON      [cashBookLedger].[companyId]        =   [chartOfAccount].[companyId]
            AND     [cashBookLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
            WHERE   [companyId]	='" . $this->getCompanyId() . "'
            AND     YEAR([cashBookDate]) =  '" . $this->getYear() . "'";
            if ($businessPartnerId) {
                $sql .= "  AND    [cashBookLedger].[businessPartnerId]='" . $businessPartnerId . "'";
            }
            $sql .= "
            AND     YEAR([cashBookDate])
            BETWEEN YEAR(DATE_SUB(NOW(), INTERVAL 3 YEAR)) AND YEAR(NOW())
            ORDER 	BY [businessPartnerId]
            GROUP	BY ([businessPartnerId])";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  ABS(SUM(CASHBOOKAMOUNT)) AS \"totalCashBookAmount\"
            FROM    CASHBOOKLEDGER
            JOIN    BUSINESSPARTNER
            ON      CASHBOOKLEDGER.COMPANYID        =   BUSINESSPARTNER.COMPANYID
            AND     CASHBOOKLEDGER.BUSINESSPARTNERID           =   BUSINESSPARTNER.BUSINESSPARTNERID
            JOIN    CHARTOFACCOUNT
            ON      CASHBOOKLEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     CASHBOOKLEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
            WHERE   COMPANYID	='" . $this->getCompanyId() . "'
            AND		YEAR(CASHBOOKDATE) =  '" . $this->getYear() . "'";
            if ($businessPartnerId) {
                $sql .= "  AND    CASHBOOKLEDGER.BUSINESSPARTNERID='" . $businessPartnerId . "'";
            }
            $sql .= "
            AND     YEAR(CASHBOOKDATE)
            BETWEEN YEAR(DATE_SUB(NOW(), INTERVAL 3 YEAR)) AND YEAR(NOW())
            ORDER 	BY  BUSINESSPARTNERID
            GROUP	BY (BUSINESSPARTNERID)";
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
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     *
     * @param string $value
     */
    function setWeek($value) {
        $this->week = $value;
    }

    /**
     * Set Week
     * @return string
     */
    function getWeek() {
        return $this->week;
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
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

/**
 * Class Business Partner Account Receivable Service
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\BusinessPartner\BusinessPartner\Service
 * @subpackage BusinessPartner
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class BusinessPartnerAccountReceivableStatisticsService extends ConfigClass {

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
     * Day
     * @var int
     */
    private $day;

    /**
     * Week
     * @var int
     */
    private $week;

    /**
     * Month
     * @var int
     */
    private $month;

    /**
     * Year
     * @var int
     */
    private $year;

    /**
     * Total Day In Month
     * @var int
     */
    private $totalDayInMonth;

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
     * Return Business Partner Receivable Account via time/hour Cross Tab
     * @param string $date Transaction Date
     * @param null|int $businessPartnerId Business Partner Primary Key
     * @return mixed
     * @throws \Exception
     */
    function getBusinessPartnerReceivableTime($date, $businessPartnerId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT  ";
            $strInside = null;
            $hour = null;
            while ($hour++ < 24) {
                $strInside .= "
                IFNULL(SUM(IF(`invoiceDate`  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                                $hour
                        ) . "%',1,0)) ,0)as `" . $hour . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    `invoiceledger`
            JOIN    `businessPartner`
            ON      `invoiceledger`.`companyId` = `businessPartner`.`companyId`
            AND     `invoiceledger`.`businessPartnerId`   =   `businessPartner`.`businessPartnerId`
            JOIN    `chartofaccount`
            ON      `invoiceledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `invoiceledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId` 
            WHERE   `invoiceledger`.`companyId`	=	'" . $this->getCompanyId() . "'";
            if ($businessPartnerId) {
                $sql .= " AND `invoiceledger`.`businessPartnerId` = '" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  ";
            $strInside = null;
            $hour = null;
            while ($hour++ < 24) {
                $strInside .= "
                IFNULL(SUM(IF([invoiceDate]  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                                $hour
                        ) . "%',1,0)) ,0) AS `" . $hour . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    [invoiceLedger]
            JOIN    [businessPartner]
            ON      [invoiceLedger].[companyId]        	=   [businessPartner].[companyId]
            AND     [invoiceLedger].[businessPartnerId]	=   [businessPartner].[businessPartnerId]
            JOIN    [chartOfAccount] 
            ON      [invoiceLedger].[companyId]        	=   [chartOfAccount].[companyId]
            AND     [invoiceLedger].[chartOfAccountId]	=   [chartOfAccount].[chartOfAccountId]
            WHERE   [invoiceLedger].[companyId]			=	'" . $this->getCompanyId() . "'";
            if ($businessPartnerId) {
                $sql .= " AND [invoiceLedger].[businessPartnerId] = '" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  ";
            $strInside = null;
            $hour = null;
            while ($hour++ < 24) {
                $strInside .= "
                IFNULL(SUM(IF(INVOICEDATE  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                                $hour
                        ) . "%',1,0)) ,0) AS `" . $hour . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    INVOICELEDGER
            JOIN    BUSINESSPARTNER
            ON      INVOICELEDGER.COMPANYID			=   BUSINESSPARTNER.COMPANYID
            AND     INVOICELEDGER.BUSINESSPARTNERID	=   BUSINESSPARTNER.BUSINESSPARTNERID
            JOIN    CHARTOFACCOUNT 
            ON      INVOICELEDGER.COMPANYID        	=   CHARTOFACCOUNT.COMPANYID
            AND     INVOICELEDGER.CHARTOFACCOUNTID	=   CHARTOFACCOUNT.CHARTOFACCOUNTID
            WHERE   INVOICELEDGER.COMPANYID			=	'" . $this->getCompanyId() . "'";
            if ($businessPartnerId) {
                $sql .= " AND INVOICELEDGER.BUSINESSPARTNERID = '" . $businessPartnerId . "'";
            }
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
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Get Month
     * @return int
     */
    function getMonth() {
        return $this->month;
    }

    /**
     * Set Month
     * @param int $value Value
     * @return $this
     */
    function setMonth($value) {
        $this->month = $value;
        return $this;
    }

    /**
     *
     * @return int
     */
    function getDay() {
        return $this->day;
    }

    /**
     *
     * @param int $value
     */
    function setDay($value) {
        $this->day = $value;
    }

    /**
     * Return Year
     * @return int
     */
    function getYear() {
        return $this->year;
    }

    /**
     * Set Year
     * @param int $value Value
     * @return $this
     */
    function setYear($value) {
        $this->year = $value;
        return $this;
    }

    /**
     * Add Zero to date
     * @param string $dateInfo
     * @return string
     */
    function changeZero($dateInfo) {
        if (strlen($dateInfo) == 1) {
            $dateInfo = '0' . $dateInfo;
        }
        return ($dateInfo);
    }

    /**
     * Return Business Partner Receivable Account via weekly Cross Tab
     * @param string $date Transaction Date
     * @param null|int $businessPartnerId Business Partner Primary Key
     * @return mixed
     * @throws \Exception
     */
    function getBusinessPartnerReceivableWeekly($date, $businessPartnerId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $d = new \DateTime(date("Y-m-d", mktime(0, 0, 0, $this->getMonth(), $this->getDay(), $this->getYear())));
        $weekday = $d->format('w');
        $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
        $d->modify("-$diff day");
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT ";
            $strInside = null;
            for ($i = 0; $i < 8; $i++) {
                $strInside .= "
                IFNULL(SUM(IF(`invoiceDate` like '" . $d->format('Y-m-d') . "%',1,0) ) ,0)as `" . $i . "`,";
                $d->modify('+1 day');
            }

            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    `invoiceledger`
            JOIN    `businessPartner`
            ON      `invoiceledger`.`companyId` 		=	`businessPartner`.`companyId`
            AND     `invoiceledger`.`businessPartnerId`	=   `businessPartner`.`businessPartnerId`
            JOIN    `chartofaccount`
            ON      `invoiceledger`.`companyId`			=	`chartofaccount`.`companyId`
            AND     `invoiceledger`.`chartOfAccountId`	=   `chartofaccount`.`chartOfAccountId`
            WHERE   `invoiceledger`.`companyId`			=	'" . $this->getCompanyId() . "'
            ";
            if ($businessPartnerId) {
                $sql .= " AND `invoiceledger`.`businessPartnerId`	=	'" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self ::MSSQL) {
            $sql = "
            SELECT ";
            $strInside = null;
            for ($i = 0; $i < 8; $i++) {
                $strInside .= "
                IFNULL(SUM(IF(`invoiceDate` like '" . $d->format('Y-m-d') . "%',1,0) ) ,0)as `" . $i . "`,";
                $d->modify('+1 day');
            }

            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    [invoiceLedger]
            JOIN	[businessPartner]
            ON      [invoiceLedger].[companyId]			=   [businessPartner].[companyId]
            AND     [invoiceLedger].[businessPartnerId]	=   [businessPartner].[businessPartnerId]
            JOIN    [chartOfAccount]
            ON      [invoiceLedger].[companyId]        	=   [chartOfAccount].[companyId]
            AND     [invoiceLedger].[chartOfAccountId] 	=   [chartOfAccount].[chartOfAccountId]
            WHERE   [invoiceLedger].[companyId]			=	'" . $this->getCompanyId() . "'
			";
            if ($businessPartnerId) {
                $sql .= " AND [invoiceLedger].[businessPartnerId]	=	'" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT ";
            $strInside = null;
            for ($i = 0; $i < 8; $i++) {
                $strInside .= "IFNULL(SUM(IF(`invoiceDate` like '" . $d->format(
                                'Y-m-d'
                        ) . "%',1,0) ) ,0)as `" . $i . "`,";
                $d->modify('+1 day');
            }

            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    INVOICELEDGER
            JOIN    BUSINESSPARTNER
            ON      INVOICELEDGER.COMPANYID        =   BUSINESSPARTNER.COMPANYID
            AND     INVOICELEDGER.BUSINESSPARTNERID           =   BUSINESSPARTNER.BUSINESSPARTNERID
            JOIN    CHARTOFACCOUNT
            ON      INVOICELEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     INVOICELEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
            WHERE   INVOICELEDGER.COMPANYID			=	'" . $this->getCompanyId() . "'
			";
            if ($businessPartnerId) {
                $sql .= " AND INVOICELEDGER.BUSINESSPARTNERID	=	'" . $businessPartnerId . "'";
            }
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
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Business Partner Receivable Account via daily Cross Tab
     * @param string $date Transaction Date
     * @param null|int $businessPartnerId Business Partner Primary Key
     * @return mixed
     * @throws \Exception
     */
    function getBusinessPartnerReceivableDaily($date, $businessPartnerId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  ";
            $strInside = null;
            for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
                $strInside .= "
                IFNULL(SUM(IF(`invoiceDate`  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($i) . "%',1,0)) ,0)as `" . $i . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    `invoiceledger`
            JOIN    `businessPartner`
            ON      `invoiceledger`.`companyId` = `businessPartner`.`companyId`
            AND     `invoiceledger`.`businessPartnerId`   =   `businessPartner`.`businessPartnerId`
            JOIN    `chartofaccount`
            ON      `invoiceledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `invoiceledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId`
            WHERE   `invoiceledger`.`companyId`	=	'" . $this->getCompanyId() . "'
            AND     MONTH(`invoiceDate`) = '" . $this->getMonth() . "'
            AND     YEAR(`invoiceDate`) = '" . $this->getYear() . "';";
            if ($businessPartnerId) {
                $sql .= " AND `businessPartnerId`	=	'" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  ";
            $strInside = null;
            for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
                $strInside .= "
				IFNULL(SUM(IF([invoiceDate]  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($i) . "%',1,0)) ,0)as `" . $i . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    [invoiceLedger]
            JOIN    [businessPartner]
            ON      [invoiceLedger].[companyId]        =   [businessPartner].[companyId]
            AND     [invoiceLedger].[businessPartnerId]           =   [businessPartner].[businessPartnerId]
            JOIN    [chartOfAccount]
            ON      [invoiceLedger].[companyId]        =   [chartOfAccount].[companyId]
            AND     [invoiceLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
            WHERE   [invoiceLedger].[companyId]	=	'" . $this->getCompanyId() . "'
            AND     MONTH([invoiceDate]) 			= 	'" . $this->getMonth() . "'
            AND     YEAR([invoiceDate]) 			= 	'" . $this->getYear() . "';";
            if ($businessPartnerId) {
                $sql .= " AND [invoiceLedger].[businessPartnerId]	=	'" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  ";
            $strInside = null;
            for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
                $strInside .= "
                IFNULL(SUM(IF(INVOICEDATE  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($i) . "%',1,0)) ,0)as `" . $i . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    INVOICELEDGER
            JOIN    BUSINESSPARTNER
            ON      INVOICELEDGER.COMPANYID        =   BUSINESSPARTNER.COMPANYID
            AND     INVOICELEDGER.BUSINESSPARTNERID           =   BUSINESSPARTNER.BUSINESSPARTNERID
            JOIN    CHARTOFACCOUNT
            ON      INVOICELEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     INVOICELEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
            WHERE   INVOICELEDGER.COMPANYID	=	'" . $this->getCompanyId() . "'
            AND     to_number(to_char(INVOICEDATE,'MM')) = '" . $this->getMonth() . "'
            AND     to_number(to_char(INVOICEDATE,'SYYYY')) = '" . $this->getYear() . "';";
            if ($businessPartnerId) {
                $sql .= " AND INVOICELEDGER.BUSINESSPARTNERID	=	'" . $businessPartnerId . "'";
            }
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
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Total Day In month
     * @return int
     */
    function getTotalDayInMonth() {
        return $this->totalDayInMonth;
    }

    /**
     * Set Total Day In Month
     * @param int $value
     * @return $this
     */
    function setTotalDayInMonth($value) {
        $this->totalDayInMonth = $value;
        return $this;
    }

    /**
     * Return Business Partner Receivable Account via month /period Cross Tab
     * @param string $date Transaction Date
     * @param null|int $businessPartnerId Business Partner Primary Key
     * @return mixed
     * @throws \Exception
     */
    function getBusinessPartnerReceivableMonthly($date, $businessPartnerId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  IFNULL(SUM(IF(month(`invoiceDate`) = 1,1,0)),0)    AS  `jan`,
                    IFNULL(SUM(IF(month(`invoiceDate`) = 2,1,0)),0)    AS  `feb`,
                    IFNULL(SUM(IF(month(`invoiceDate`) = 3,1,0)),0)    AS  `mac`,
                    IFNULL(SUM(IF(month(`invoiceDate`) = 4,1,0)),0)    AS  `apr`,
                    IFNULL(SUM(IF(month(`invoiceDate`) = 5,1,0)),0)    AS  `may`,
                    IFNULL(SUM(IF(month(`invoiceDate`) = 6,1,0)),0)    AS  `jun`,
                    IFNULL(SUM(IF(month(`invoiceDate`) = 7,1,0)),0)    AS  `jul`,
                    IFNULL(SUM(IF(month(`invoiceDate`) = 8,1,0)),0)    AS  `aug`,
                    IFNULL(SUM(IF(month(`invoiceDate`) = 9,1,0)),0)    AS  `sep`,
                    IFNULL(SUM(IF(month(`invoiceDate`) = 10,1,0)),0)   AS  `oct`,
                    IFNULL(SUM(IF(month(`invoiceDate`) = 11,1,0)),0)   AS  `nov`,
                    IFNULL(SUM(IF(month(`invoiceDate`) = 12,1,0)),0)   AS  `dec`
            FROM    `invoiceledger`
            JOIN    `businessPartner`
            ON      `invoiceledger`.`companyId` = `businessPartner`.`companyId`
            AND     `invoiceledger`.`businessPartnerId`   =   `businessPartner`.`businessPartnerId`
            JOIN    `chartofaccount`
            ON      `invoiceledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `invoiceledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId`
            WHERE   `invoiceledger`.`companyId`	=	'" . $this->getCompanyId() . "'
            AND YEAR(`invoiceDate`) = '" . $this->getYear() . "'";
            if ($businessPartnerId) {
                $sql .= " AND `invoiceledger`.`businessPartnerId`	=	'" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  IFNULL(SUM(IF(month([invoiceDate]) = 1,1,0)),0)	AS	[jan],
                    IFNULL(SUM(IF(month([invoiceDate]) = 2,1,0)),0)	AS	[feb],
                    IFNULL(SUM(IF(month([invoiceDate]) = 3,1,0)),0)	AS	[mac],
                    IFNULL(SUM(IF(month([invoiceDate]) = 4,1,0)),0)	AS	[apr],
                    IFNULL(SUM(IF(month([invoiceDate]) = 5,1,0)),0)	AS	[may],
                    IFNULL(SUM(IF(month([invoiceDate]) = 6,1,0)),0)	AS	[jun],
                    IFNULL(SUM(IF(month([invoiceDate]) = 7,1,0)),0)	AS	[jul],
                    IFNULL(SUM(IF(month([invoiceDate]) = 8,1,0)),0)	AS	[aug],
                    IFNULL(SUM(IF(month([invoiceDate]) = 9,1,0)),0)	AS	[sep],
                    IFNULL(SUM(IF(month([invoiceDate]) = 10,1,0)),0)	AS	[oct],
                    IFNULL(SUM(IF(month([invoiceDate]) = 11,1,0)),0)	AS	[nov],
                    IFNULL(SUM(IF(month([invoiceDate]) = 12,1,0)),0)	AS	[dec]
            FROM    [invoiceLedger]
            JOIN    [businessPartner]
            ON      [invoiceLedger].[companyId]        =   [businessPartner].[companyId]
            AND     [invoiceLedger].[businessPartnerId]           =   [businessPartner].[businessPartnerId]
            JOIN    [chartOfAccount]
            ON      [invoiceLedger].[companyId]        =   [chartOfAccount].[companyId]
            AND     [invoiceLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
            WHERE   [invoiceLedger].[companyId]	=	'" . $this->getCompanyId() . "'
            AND     YEAR([invoiceDate]) = '" . $this->getYear() . "'";
            if ($businessPartnerId) {
                $sql .= " AND [invoiceLedger].[businessPartnerId]	=	'" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  IFNULL(SUM(IF(to_number(to_char(INVOICEDATE,'MM')) = 1,1,0)),0)	AS	\"jan\",
                    IFNULL(SUM(IF(to_number(to_char(INVOICEDATE,'MM')) = 2,1,0)),0)	AS	\"feb\",
                    IFNULL(SUM(IF(to_number(to_char(INVOICEDATE,'MM')) = 3,1,0)),0)	AS	\"mac\",
                    IFNULL(SUM(IF(to_number(to_char(INVOICEDATE,'MM')) = 4,1,0)),0)	AS	\"apr\",
                    IFNULL(SUM(IF(to_number(to_char(INVOICEDATE,'MM')) = 5,1,0)),0)	AS	\"may\",
                    IFNULL(SUM(IF(to_number(to_char(INVOICEDATE,'MM')) = 6,1,0)),0)	AS	\"jun\",
                    IFNULL(SUM(IF(to_number(to_char(INVOICEDATE,'MM')) = 7,1,0)),0)	AS	\"jul\",
                    IFNULL(SUM(IF(to_number(to_char(INVOICEDATE,'MM')) = 8,1,0)),0)	AS	\"aug\",
                    IFNULL(SUM(IF(to_number(to_char(INVOICEDATE,'MM')) = 9,1,0)),0)	AS	\"sep\",
                    IFNULL(SUM(IF(to_number(to_char(INVOICEDATE,'MM')) = 10,1,0)),0)	AS	\"oct\",
                    IFNULL(SUM(IF(to_number(to_char(INVOICEDATE,'MM')) = 11,1,0)),0)	AS	\"nov\",
                    IFNULL(SUM(IF(to_number(to_char(INVOICEDATE,'MM')) = 12,1,0)),0)	AS	\"dec\"
            FROM    INVOICELEDGER
            JOIN    BUSINESSPARTNER
            ON      INVOICELEDGER.COMPANYID                 =   BUSINESSPARTNER.COMPANYID
            AND     INVOICELEDGER.BUSINESSPARTNERID         =   BUSINESSPARTNER.BUSINESSPARTNERID
            JOIN    CHARTOFACCOUNT
            ON      INVOICELEDGER.COMPANYID                 =   CHARTOFACCOUNT.COMPANYID
            AND     INVOICELEDGER.CHARTOFACCOUNTID          =   CHARTOFACCOUNT.CHARTOFACCOUNTID
            WHERE   INVOICELEDGER.COMPANYID                 =   '" . $this->getCompanyId() . "'
            AND     to_number(to_char(INVOICEDATE,'SYYYY')) =   '" . $this->getYear() . "'";
            if ($businessPartnerId) {
                $sql .= " AND INVOICELEDGER.BUSINESSPARTNERID =	'" . $businessPartnerId . "'";
            }
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
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Business Partner Receivable Account via yearly Cross Tab
     * @param string $date Transaction Date
     * @param null|int $businessPartnerId Business Partner Primary Key
     * @return mixed
     * @throws \Exception
     */
    function getBusinessPartnerReceivableYearly($date, $businessPartnerId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  ABS(SUM(`invoiceledger`.`cashBookAmount`)) as `totalInvoiceAmount`
            FROM    `invoiceledger`
            JOIN    `businessPartner`
            ON      `invoiceledger`.`companyId` = `businessPartner`.`companyId`
            AND     `invoiceledger`.`businessPartnerId`   =   `businessPartner`.`businessPartnerId`
            JOIN    `chartofaccount`
            ON      `invoiceledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `invoiceledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId`
            WHERE   `invoiceledger`.`companyId`	='" . $this->getCompanyId() . "'
            AND		YEAR(`invoiceledger`.`invoiceDate`) =  '" . $this->getYear() . "'";
            if ($businessPartnerId) {
                $sql .= "  AND    `invoiceledger`.`businessPartnerId`='" . $businessPartnerId . "'";
            }
            $sql .= "
            AND     YEAR(`invoiceledger`.`invoiceDate`)
            BETWEEN YEAR(DATE_SUB(NOW(), INTERVAL 3 YEAR)) AND YEAR(NOW())
            ORDER 	BY `invoiceledger`.`businessPartnerId`
            GROUP	BY (`invoiceledger`.`businessPartnerId`)";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  ABS(SUM([invoiceAmount])) as [totalInvoiceAmount]
            FROM    [invoiceLedger]
            JOIN    [businessPartner]
            ON      [invoiceLedger].[companyId]        =   [businessPartner].[companyId]
            AND     [invoiceLedger].[businessPartnerId]           =   [businessPartner].[businessPartnerId]
            JOIN    [chartOfAccount]
            ON      [invoiceLedger].[companyId]        =   [chartOfAccount].[companyId]
            AND     [invoiceLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
            WHERE   [companyId]	='" . $this->getCompanyId() . "'
            AND		YEAR([invoiceDate]) =  '" . $this->getYear() . "'";
            if ($businessPartnerId) {
                $sql .= "  AND    [invoiceLedger].[businessPartnerId]='" . $businessPartnerId . "'";
            }
            $sql .= "
            AND     YEAR([invoiceDate])
            BETWEEN YEAR(DATE_SUB(NOW(), INTERVAL 3 YEAR)) AND YEAR(NOW())
            ORDER 	BY [businessPartnerId]
            GROUP	BY ([businessPartnerId])";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  ABS(SUM(INVOICEAMOUNT)) AS \"totalInvoiceAmount\"
            FROM    INVOICELEDGER
            JOIN    BUSINESSPARTNER
            ON      INVOICELEDGER.COMPANYID        =   BUSINESSPARTNER.COMPANYID
            AND     INVOICELEDGER.BUSINESSPARTNERID           =   BUSINESSPARTNER.BUSINESSPARTNERID
            JOIN    CHARTOFACCOUNT
            ON      INVOICELEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     INVOICELEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
            WHERE   COMPANYID	='" . $this->getCompanyId() . "'
            AND     to_number(to_char(INVOICEDATE,'SYYYY')) =  '" . $this->getYear() . "'";
            if ($businessPartnerId) {
                $sql .= "  AND    INVOICELEDGER.BUSINESSPARTNERID='" . $businessPartnerId . "'";
            }
            $sql .= "
            AND     to_number(to_char(INVOICEDATE,'SYYYY'))
            BETWEEN YEAR(DATE_SUB(NOW(), INTERVAL 3 YEAR)) AND YEAR(NOW())
            ORDER 	BY  BUSINESSPARTNERID
            GROUP	BY (BUSINESSPARTNERID)";
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
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     *
     * @param string $value
     */
    function setWeek($value) {
        $this->week = $value;
    }

    /**
     * Set Week
     * @return string
     */
    function getWeek() {
        return $this->week;
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
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

/**
 * Class Business Partner Payable Statistic Service
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\BusinessPartner\BusinessPartner\Service
 * @subpackage BusinessPartner
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class BusinessPartnerAccountPayableStatisticsService extends ConfigClass {

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
     * Day
     * @var int
     */
    private $day;

    /**
     * Week
     * @var int
     */
    private $week;

    /**
     * Month
     * @var int
     */
    private $month;

    /**
     * Year
     * @var int
     */
    private $year;

    /**
     * Total Day In Month
     * @var int
     */
    private $totalDayInMonth;

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
     * Return Business Partner Payable Account via time/hour Cross Tab
     * @param string $date Transaction Date
     * @param null|int $businessPartnerId Business Partner Primary Key
     * @return mixed
     * @throws \Exception
     */
    function getBusinessPartnerPayableTime($date, $businessPartnerId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  ";
            $strInside = null;
            $hour = null;
            while ($hour++ < 24) {
                $strInside .= "
                IFNULL(SUM(IF(`purchaseInvoiceDate`  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                                $hour
                        ) . "%',1,0)) ,0)as `" . $hour . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    `purchaseinvoiceledger`
            JOIN    `businesspartner`
            ON      `purchaseinvoiceledger`.`companyId` = `businesspartner`.`companyId`
            AND     `purchaseinvoiceledger`.`businessPartnerId`   =   `businesspartner`.`businesPartnerId`
            JOIN    `chartofaccount`
            ON      `purchaseinvoiceledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `purchaseinvoiceledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId` 
            WHERE   `purchaseinvoiceledger`.`companyId`	=	'" . $this->getCompanyId() . "'";
            if ($businessPartnerId) {
                $sql .= " AND `purchaseinvoiceledger`.`businessPartnerId` = '" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  ";
            $strInside = null;
            $hour = null;
            while ($hour++ < 24) {
                $strInside .= "
                IFNULL(SUM(IF([purchaseInvoiceDate]  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                                $hour
                        ) . "%',1,0)) ,0) AS `" . $hour . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    [purchaseInvoiceLedger]
            JOIN    [businessPartner]
            ON      [purchaseInvoiceLedger].[companyId]        =   [businessPartner].[companyId]
            AND     [purchaseInvoiceLedger].[businessPartnerId]           =   [businessPartner].[businessPartnerId]
            JOIN    [chartOfAccount] 
            ON      [purchaseInvoiceLedger].[companyId]        =   [chartOfAccount].[companyId]
            AND     [purchaseInvoiceLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
            WHERE   [purchaseInvoiceLedger].[companyId]	=	'" . $this->getCompanyId() . "'";
            if ($businessPartnerId) {
                $sql .= " AND [purchaseInvoiceLedger].[businessPartnerId] = '" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  ";
            $strInside = null;
            $hour = null;
            while ($hour++ < 24) {
                $strInside .= "IFNULL(SUM(IF(CASHBOOKDATE  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                                $hour
                        ) . "%',1,0)) ,0) AS `" . $hour . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    PURCHASEINVOICELEDGER
            JOIN    BUSINESSPARTNER
            ON      PURCHASEINVOICELEDGER.COMPANYID        =   BUSINESSPARTNER.COMPANYID
            AND     PURCHASEINVOICELEDGER.BUSINESSPARTNERID           =   BUSINESSPARTNER.BUSINESSPARTNERID
            JOIN    CHARTOFACCOUNT 
            ON      PURCHASEINVOICELEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     PURCHASEINVOICELEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
            WHERE   PURCHASEINVOICELEDGER.COMPANYID	=	'" . $this->getCompanyId() . "'";
            if ($businessPartnerId) {
                $sql .= " AND BUSINESSPARTNERID = '" . $businessPartnerId . "'";
            }
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
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Get Month
     * @return int
     */
    function getMonth() {
        return $this->month;
    }

    /**
     * Set Month
     * @param int $value Value
     * @return $this;
     */
    function setMonth($value) {
        $this->month = $value;
        return $this;
    }

    /**
     * Set Day
     * @return int
     */
    function getDay() {
        return $this->day;
    }

    /**
     * Set Day
     * @param int $value Value
     * @return $this
     */
    function setDay($value) {
        $this->day = $value;
        return $this;
    }

    /**
     * Return Year
     * @return int
     */
    function getYear() {
        return $this->year;
    }

    /**
     * Set Year
     * @param int $value
     * @return $this
     */
    function setYear($value) {
        $this->year = $value;
        return $this;
    }

    /**
     * Add Zero to date
     * @param string $dateInfo Date
     * @return string
     */
    function changeZero($dateInfo) {
        if (strlen($dateInfo) == 1) {
            $dateInfo = '0' . $dateInfo;
        }
        return ($dateInfo);
    }

    /**
     * Return Business Partner Payable Account via weekly Cross Tab
     * @param string $date Transaction Date
     * @param null|int $businessPartnerId Business Partner Primary Key
     * @return mixed
     * @throws \Exception
     */
    function getBusinessPartnerPayableWeekly($date, $businessPartnerId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $d = new \DateTime(date("Y-m-d", mktime(0, 0, 0, $this->getMonth(), $this->getDay(), $this->getYear())));
        $weekday = $d->format('w');
        $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
        $d->modify("-$diff day");
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT ";
            $strInside = null;
            for ($i = 0; $i < 8; $i++) {
                $strInside .= "IFNULL(SUM(IF(`purchaseInvoiceDate` like '" . $d->format(
                                'Y-m-d'
                        ) . "%',1,0) ) ,0)as `" . $i . "`,";
                $d->modify('+1 day');
            }

            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    `purchaseinvoiceledger`
            JOIN    `businessPartner`
            ON      `purchaseinvoiceledger`.`companyId` = `businessPartner`.`companyId`
            AND     `purchaseinvoiceledger`.`businessPartnerId`   =   `businessPartner`.`businessPartnerId`
            JOIN    `chartofaccount`
            ON      `purchaseinvoiceledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `purchaseinvoiceledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId`
            WHERE   `purchaseinvoiceledger`.`companyId`	=	'" . $this->getCompanyId() . "'
            ";
            if ($businessPartnerId) {
                $sql .= " AND `purchaseinvoiceledger`.`businessPartnerId`	=	'" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self ::MSSQL) {
            $sql = "
            SELECT ";
            $strInside = null;
            for ($i = 0; $i < 8; $i++) {
                $strInside .= "IFNULL(SUM(IF(`purchaseInvoiceDate` like '" . $d->format(
                                'Y-m-d'
                        ) . "%',1,0) ) ,0)as `" . $i . "`,";
                $d->modify('+1 day');
            }

            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    [purchaseInvoiceLedger]
            JOIN    [businessPartner]
            ON      [purchaseInvoiceLedger].[companyId]        =   [businessPartner].[companyId]
            AND     [purchaseInvoiceLedger].[businessPartnerId]           =   [businessPartner].[businessPartnerId]
            JOIN    [chartOfAccount]
            ON      [purchaseInvoiceLedger].[companyId]        =   [chartOfAccount].[companyId]
            AND     [purchaseInvoiceLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
            WHERE   [purchaseInvoiceLedger].[companyId]	=	'" . $this->getCompanyId() . "'
            ";
            if ($businessPartnerId) {
                $sql .= " AND [purchaseInvoiceLedger].[businessPartnerId]	=	'" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT ";
            $strInside = null;
            for ($i = 0; $i < 8; $i++) {
                $strInside .= "IFNULL(SUM(IF(`purchaseInvoiceDate` like '" . $d->format(
                                'Y-m-d'
                        ) . "%',1,0) ) ,0)as `" . $i . "`,";
                $d->modify('+1 day');
            }

            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    PURCHASEINVOICELEDGER
            JOIN    BUSINESSPARTNER
            ON      PURCHASEINVOICELEDGER.COMPANYID        =   BUSINESSPARTNER.COMPANYID
            AND     PURCHASEINVOICELEDGER.BUSINESSPARTNERID           =   BUSINESSPARTNER.BUSINESSPARTNERID
            JOIN    CHARTOFACCOUNT
            ON      PURCHASEINVOICELEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     PURCHASEINVOICELEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
            WHERE   PURCHASEINVOICELEDGER.COMPANYID	=	'" . $this->getCompanyId() . "'";
            if ($businessPartnerId) {
                $sql .= " AND PURCHASEINVOICELEDGER.BUSINESSPARTNERID	=	'" . $businessPartnerId . "'";
            }
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
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Business Partner Payable Account via daily Cross Tab
     * @param string $date Transaction Date
     * @param null|int $businessPartnerId Business Partner Primary Key
     * @return mixed
     * @throws \Exception
     */
    function getBusinessPartnerPayableDaily($date, $businessPartnerId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  ";
            $strInside = null;
            for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
                $strInside .= "IFNULL(SUM(IF(`purchaseInvoiceDate`  like '" . $this->getYear(
                        ) . "-" . $this->changeZero($this->getMonth()) . "-" . $this->changeZero(
                                $i
                        ) . "%',1,0)) ,0)as `" . $i . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    `purchaseinvoiceledger`
            JOIN    `businessPartner`
            ON      `purchaseinvoiceledger`.`companyId` = `businessPartner`.`companyId`
            AND     `purchaseinvoiceledger`.`businessPartnerId`   =   `businessPartner`.`businessPartnerId`
            JOIN    `chartofaccount`
            ON      `purchaseinvoiceledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `purchaseinvoiceledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId`
            WHERE   `purchaseinvoiceledger`.`companyId`	=	'" . $this->getCompanyId() . "'
            AND     MONTH(`purchaseInvoiceDate`) = '" . $this->getMonth() . "'
            AND     YEAR(`purchaseInvoiceDate`) = '" . $this->getYear() . "';";
            if ($businessPartnerId) {
                $sql .= " AND `businessPartnerId`	=	'" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  ";
            $strInside = null;
            for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
                $strInside .= "IFNULL(SUM(IF([purchaseInvoiceDate]  like '" . $this->getYear(
                        ) . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($i) . "%',1,0)) ,0)as `" . $i . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    [purchaseInvoiceLedger]
            JOIN    [businessPartner]
            ON      [purchaseInvoiceLedger].[companyId]         =   [businessPartner].[companyId]
            AND     [purchaseInvoiceLedger].[businessPartnerId] =   [businessPartner].[businessPartnerId]
            JOIN    [chartOfAccount]
            ON      [purchaseInvoiceLedger].[companyId]         =   [chartOfAccount].[companyId]
            AND     [purchaseInvoiceLedger].[chartOfAccountId]  =   [chartOfAccount].[chartOfAccountId]
            WHERE   [purchaseInvoiceLedger].[companyId]         =   '" . $this->getCompanyId() . "'
            AND     MONTH([purchaseInvoiceDate])                =   '" . $this->getMonth() . "'
            AND     YEAR([purchaseInvoiceDate])                 =   '" . $this->getYear() . "';";
            if ($businessPartnerId) {
                $sql .= " AND [purchaseInvoiceLedger].[businessPartnerId]	=	'" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  ";
            $strInside = null;
            for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
                $strInside .= "
                IFNULL(SUM(IF(trunc(to_date(CASHBOOKDATE,'YYYY-MM-DD hh24:mi:ss'))  like '" . $this->getYear(
                        ) . "-" . $this->changeZero($this->getMonth()) . "-" . $this->changeZero(
                                $i
                        ) . "%',1,0)) ,0)as `" . $i . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    PURCHASEINVOICELEDGER
            JOIN    BUSINESSPARTNER
            ON      PURCHASEINVOICELEDGER.COMPANYID        =   BUSINESSPARTNER.COMPANYID
            AND     PURCHASEINVOICELEDGER.BUSINESSPARTNERID           =   BUSINESSPARTNER.BUSINESSPARTNERID
            JOIN    CHARTOFACCOUNT
            ON      PURCHASEINVOICELEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     PURCHASEINVOICELEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
            WHERE   PURCHASEINVOICELEDGER.COMPANYID	=	'" . $this->getCompanyId() . "'
            AND     MONTH(CASHBOOKDATE) = '" . $this->getMonth() . "'
            AND     YEAR(CASHBOOKDATE) = '" . $this->getYear() . "';";
            if ($businessPartnerId) {
                $sql .= " AND PURCHASEINVOICELEDGER.BUSINESSPARTNERID	=	'" . $businessPartnerId . "'";
            }
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
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Total Day In month
     * @return int
     */
    function getTotalDayInMonth() {
        return $this->totalDayInMonth;
    }

    /**
     * Set Total Day In Month
     * @param int $value
     * @return $this
     */
    function setTotalDayInMonth($value) {
        $this->totalDayInMonth = $value;
        return $this;
    }

    /**
     * Return Business Partner Payable Account via monthly/period Cross Tab
     * @param string $date Transaction Date
     * @param null|int $businessPartnerId Business Partner Primary Key
     * @return array
     * @throws \Exception
     */
    function getBusinessPartnerPayableMonthly($date, $businessPartnerId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 1,1,0)),0)    AS `jan`,
                    IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 2,1,0)),0)    AS `feb`,
                    IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 3,1,0)),0)    AS `mac`,
                    IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 4,1,0)),0)    AS `apr`,
                    IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 5,1,0)),0)    AS `may`,
                    IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 6,1,0)),0)    AS `jun`,
                    IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 7,1,0)),0)    AS `jul`,
                    IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 8,1,0)),0)    AS `aug`,
                    IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 9,1,0)),0)    AS `sep`,
                    IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 10,1,0)),0)    AS `oct`,
                    IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 11,1,0)),0)    AS `nov`,
                    IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 12,1,0)),0)    AS  `dec`
            FROM    `purchaseinvoiceledger`
            JOIN    `businessPartner`
            ON      `purchaseinvoiceledger`.`companyId` = `businessPartner`.`companyId`
            AND     `purchaseinvoiceledger`.`businessPartnerId`   =   `businessPartner`.`businessPartnerId`
            JOIN    `chartofaccount`
            ON      `purchaseinvoiceledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `purchaseinvoiceledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId`
            WHERE   `purchaseinvoiceledger`.`companyId`	=	'" . $this->getCompanyId() . "'
            AND     YEAR(`purchaseInvoiceDate`) = '" . $this->getYear() . "'";
            if ($businessPartnerId) {
                $sql .= " AND `purchaseinvoiceledger`.`businessPartnerId`	=	'" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 1,1,0)),0)as `jan`,
                    IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 2,1,0)),0)as `feb`,
                    IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 3,1,0)),0)as `mac`,
                    IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 4,1,0)),0)as `apr`,
                    IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 5,1,0)),0)as `may`,
                    IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 6,1,0)),0)as `jun`,
                    IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 7,1,0)),0)as `jul`,
                    IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 8,1,0)),0)as `aug`,
                    IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 9,1,0)),0) as `sep`,
                    IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 10,1,0)),0) as `oct`,
                    IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 11,1,0)),0)as `nov`,
                    IFNULL(SUM(IF(month(`purchaseInvoiceDate`) = 12,1,0)),0)as `dec`
            FROM    [purchaseinvoiceLedger]
            JOIN    [businessPartner]
            ON      [purchaseInvoiceLedger].[companyId]        =   [businessPartner].[companyId]
            AND     [purchaseInvoiceLedger].[businessPartnerId]           =   [businessPartner].[businessPartnerId]
            JOIN    [chartOfAccount]
            ON      [purchaseInvoiceLedger].[companyId]        =   [chartOfAccount].[companyId]
            AND     [purchaseInvoiceLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
            WHERE   [purchaseinvoiceLedger].[companyId]	=	'" . $this->getCompanyId() . "'
            AND     YEAR(`purchaseInvoiceDate`) = '" . $this->getYear() . "'";
            if ($businessPartnerId) {
                $sql .= " AND [purchaseinvoiceLedger].[businessPartnerId]	=	'" . $businessPartnerId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  IFNULL(SUM(IF(to_number(to_char(PURCHASEINVOICEDATE,'MM'))) = 1,1,0)),0)AS \"jan\",
                    IFNULL(SUM(IF(to_number(to_char(PURCHASEINVOICEDATE,'MM'))) = 2,1,0)),0)AS \"feb\",
                    IFNULL(SUM(IF(to_number(to_char(PURCHASEINVOICEDATE,'MM'))) = 3,1,0)),0)AS \"mac\",
                    IFNULL(SUM(IF(to_number(to_char(PURCHASEINVOICEDATE,'MM'))) = 4,1,0)),0)AS \"apr\",
                    IFNULL(SUM(IF(to_number(to_char(PURCHASEINVOICEDATE,'MM'))) = 5,1,0)),0)AS \"may\",
                    IFNULL(SUM(IF(to_number(to_char(PURCHASEINVOICEDATE,'MM'))) = 6,1,0)),0)AS \"jun\",
                    IFNULL(SUM(IF(to_number(to_char(PURCHASEINVOICEDATE,'MM'))) = 7,1,0)),0)AS \"jul\",
                    IFNULL(SUM(IF(to_number(to_char(PURCHASEINVOICEDATE,'MM'))) = 8,1,0)),0)AS \"aug\",
                    IFNULL(SUM(IF(to_number(to_char(PURCHASEINVOICEDATE,'MM'))) = 9,1,0)),0) AS \"sep\",
                    IFNULL(SUM(IF(to_number(to_char(PURCHASEINVOICEDATE,'MM'))) = 10,1,0)),0) AS \"oct\",
                    IFNULL(SUM(IF(to_number(to_char(PURCHASEINVOICEDATE,'MM'))) = 11,1,0)),0)AS \"nov\",
                    IFNULL(SUM(IF(to_number(to_char(PURCHASEINVOICEDATE,'MM'))) = 12,1,0)),0)AS \"dec\"
            FROM    PURCHASEINVOICELEDGER
            JOIN    BUSINESSPARTNER
            ON      PURCHASEINVOICELEDGER.COMPANYID         =   BUSINESSPARTNER.COMPANYID
            AND     PURCHASEINVOICELEDGER.BUSINESSPARTNERID =   BUSINESSPARTNER.BUSINESSPARTNERID
            JOIN    CHARTOFACCOUNT
            ON      PURCHASEINVOICELEDGER.COMPANYID         =   CHARTOFACCOUNT.COMPANYID
            AND     PURCHASEINVOICELEDGER.CHARTOFACCOUNTID  =   CHARTOFACCOUNT.CHARTOFACCOUNTID
            WHERE   PURCHASEINVOICELEDGER.COMPANYID         =	'" . $this->getCompanyId() . "'
            AND     to_number(to_char(PURCHASEINVOICEDATE,'SYYYY'))               =   '" . $this->getYear() . "'";
            if ($businessPartnerId) {
                $sql .= " AND PURCHASEINVOICELEDGER.BUSINESSPARTNERID	=	'" . $businessPartnerId . "'";
            }
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
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Business Partner Payable Account via yearly Cross Tab
     * @param string $date Transaction Date
     * @param null|int $businessPartnerId Business Partner Primary Key
     * @return mixed
     * @throws \Exception
     */
    function getBusinessPartnerPayableYearly($date, $businessPartnerId = null) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  ABS(SUM(`purchaseinvoiceledger`.`purchaseInvoiceAmount`)) as `totalPayableAmount`
            FROM    `purchaseinvoiceledger`
            JOIN    `businessPartner`
            ON      `purchaseinvoiceledger`.`companyId` = `businessPartner`.`companyId`
            AND     `purchaseinvoiceledger`.`businessPartnerId`   =   `businessPartner`.`businessPartnerId`
            JOIN    `chartofaccount`
            ON      `purchaseinvoiceledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `purchaseinvoiceledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId`
            WHERE   `purchaseinvoiceledger`.`companyId`	='" . $this->getCompanyId() . "'
            AND		YEAR(`purchaseinvoiceledger`.`purchaseInvoiceDate`) =  '" . $this->getYear() . "'";
            if ($businessPartnerId) {
                $sql .= "  AND    `purchaseinvoiceledger`.`businessPartnerId`='" . $businessPartnerId . "'";
            }
            $sql .= "
            AND     YEAR(`purchaseinvoiceledger`.`purchaseInvoiceDate`)
            BETWEEN YEAR(DATE_SUB(NOW(), INTERVAL 3 YEAR)) AND YEAR(NOW())
            ORDER 	BY `purchaseinvoiceledger`.`businessPartnerId`
            GROUP	BY (`purchaseinvoiceledger`.`businessPartnerId`)";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  ABS(SUM([purchaseInvoiceAmount])) as [totalPayableAmount]
            FROM    [purchaseInvoiceLedger]
            JOIN    [businessPartner]
            ON      [purchaseInvoiceLedger].[companyId]        =   [businessPartner].[companyId]
            AND     [purchaseInvoiceLedger].[businessPartnerId]           =   [businessPartner].[businessPartnerId]
            JOIN    [chartOfAccount]
            ON      [purchaseInvoiceLedger].[companyId]        =   [chartOfAccount].[companyId]
            AND     [purchaseInvoiceLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
            WHERE   [companyId]	='" . $this->getCompanyId() . "'
            AND     YEAR([purchaseInvoiceDate]) =  '" . $this->getYear() . "'";
            if ($businessPartnerId) {
                $sql .= "  AND    [purchaseInvoiceLedger].[businessPartnerId]='" . $businessPartnerId . "'";
            }
            $sql .= "
            AND     YEAR([purchaseInvoiceDate])
            BETWEEN YEAR(DATE_SUB(NOW(), INTERVAL 3 YEAR)) AND YEAR(NOW())
            ORDER 	BY [businessPartnerId]
            GROUP	BY ([businessPartnerId])";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  ABS(SUM(CASHBOOKAMOUNT)) AS \"totalPayableAmount\"
            FROM    PURCHASEINVOICELEDGER
            JOIN    BUSINESSPARTNER
            ON      PURCHASEINVOICELEDGER.COMPANYID        =   BUSINESSPARTNER.COMPANYID
            AND     PURCHASEINVOICELEDGER.BUSINESSPARTNERID           =   BUSINESSPARTNER.BUSINESSPARTNERID
            JOIN    CHARTOFACCOUNT
            ON      PURCHASEINVOICELEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     PURCHASEINVOICELEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
            WHERE   COMPANYID	='" . $this->getCompanyId() . "'
            AND     to_number(to_char(CASHBOOKDATE,'SYYYY')) =  '" . $this->getYear() . "'";
            if ($businessPartnerId) {
                $sql .= "  AND    PURCHASEINVOICELEDGER.BUSINESSPARTNERID='" . $businessPartnerId . "'";
            }
            $sql .= "
            AND     to_number(to_char(CASHBOOKDATE,'SYYYY'))
            BETWEEN to_number(to_char(DATE_SUB(NOW(), INTERVAL 3 YEAR)) AND to_number(to_char(NOW(),'SYYYY'))
            ORDER 	BY  BUSINESSPARTNERID
            GROUP	BY (BUSINESSPARTNERID)";
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
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Week
     * @param string $value Value
     * @return $this
     */
    function setWeek($value) {
        $this->week = $value;
        return $this;
    }

    /**
     * Set Week
     * @return string
     */
    function getWeek() {
        return $this->week;
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
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