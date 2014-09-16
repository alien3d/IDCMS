<?php

namespace Core\HumanResource\Employment\Employee\Service;

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
 * Class EmployeeService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\HumanResource\Employment\Employee\Service
 * @subpackage Employment
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class EmployeeService extends ConfigClass {

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
        $this->setUploadPath($this->getFakeDocumentRoot() . "v3/humanResource/employment/images/");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
    }

    /**
     * Return City
     * @param null|int $countryId
     * @param null|int $stateId
     * @param null|int $divisionId Division.. Only Apply on Malaysia->Sarawak Sabah
     * @param null|int $districtId
     * @return array|string
     */
    public function getCity($countryId = null, $stateId = null, $divisionId = null, $districtId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
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
					`city`.`cityDescription`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
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
            } else {
                if ($this->getVendor() == self::ORACLE) {
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
     * get master setting of the counter.. too heavy to render all city
     */
    private function getOverrideCountry() {
        $sql = null;
        $countryId = null;
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
        } else {
            
        }
        return $countryId;
    }

    /**
     * Return City Default Value
     * @return int
     */
    public function getCityDefaultValue() {
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
         AND		  LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [cityId],
         FROM        [city]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
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
            $cityId = $row['cityId'];
        }
        return $cityId;
    }

    /**
     * Return State
     * @param null|int $countryId
     * @return array|string
     */
    public function getState($countryId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
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
		 ORDER BY    `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
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
            } else {
                if ($this->getVendor() == self::ORACLE) {
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
     * Return State Default Value
     * @return int
     */
    public function getStateDefaultValue() {
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
         AND		  LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [stateId],
         FROM        [state]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
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
            $stateId = $row['stateId'];
        }
        return $stateId;
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
						`countryDescription`
			FROM        `country`
			WHERE       `isActive`  =   1
			AND         `companyId` =   '" . $this->getCompanyId() . "'
			AND		 	`countryId` =	'" . $this->getOverrideCountry() . "'
			ORDER BY    `isDefault` DESC ;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      [countryId],
						[countryDescription]
			FROM        [country]
			WHERE       [isActive]  =   1
			AND         [companyId] =   '" . $this->getCompanyId() . "'
			AND		 	[countryId] =	'" . $this->getOverrideCountry() . "'
			ORDER BY    [isDefault] DESC";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT      COUNTRYID AS \"countryId\",
						COUNTRYDESCRIPTION AS \"countryDescription\"
			FROM        COUNTRY
			WHERE       ISACTIVE    =   1
			AND         COMPANYID   =	'" . $this->getCompanyId() . "'
			AND		 	COUNTRYID	 =	'" . $this->getOverrideCountry() . "'
			ORDER BY    ISDEFAULT DESC";
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
			FROM        `country`
			WHERE       `isActive`  =   1
			AND         `companyId` =   '" . $this->getCompanyId() . "'
			AND    	  `isDefault` =	  1
			AND		  LIMIT 1";
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
     * Return Job
     * @param null|int $countryId
     * @return array|string
     */
    public function getJob($countryId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT	`job`.`jobId`,
					`job`.`jobTitle`,
					`jobcategory`.`jobCategoryDescription` 
			FROM	`job`
			JOIN	`jobcategory`
			USING	(`companyId`,`jobCategoryId`)
			WHERE	`job`.`isActive`  =   1
			AND		`job`.`companyId` =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql .= "
				AND	`job`.`countryId`	=	'" . $countryId . "'";
            } else {
                $sql .= "
				AND	`job`.`countryId`='" . $this->getOverrideCountry() . "'";
            }
            $sql .= "
			ORDER BY    `job`.`isDefault` DESC;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT	[job].[jobId],
					[job].[jobTitle],
					[jobCategory].[jobCategoryDescription]
			FROM	[job]
			JOIN	[jobCategory]
			ON		[job].[companyId] 	= [jobCategory].[companyId]
			AND		[job].[jobId] 		= [jobCategory].[jobId]
			WHERE	[job].[isActive]  =   1
			AND		[job].[companyId] =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql .= "
				AND	[job].[countryId	=	'" . $countryId . "'";
            } else {
                $sql .= "
				AND	[job].[countryId]='" . $this->getOverrideCountry() . "'";
            }
            $sql .= "
			ORDER BY    [job].[isDefault] DESC";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT      JOBID AS \"jobId\",
						JOBTITLE AS \"jobTitle\",
						JOBCATEGORY.JOBCATEGORYDESCRIPTION AS \"jobCategoryDescription\"
			FROM        JOB
			JOIN		JOBCATEGORY
			ON			JOB.COMPANYID 	= JOBCATEGORY.COMPANYID
			AND			JOB.JOBID 		= JOBCATEGORY.JOBID
			WHERE       JOB.ISACTIVE    =   1
			AND         JOB.COMPANYID   =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql .= "
				AND	 JOB.COUNTRYID	=	'" . $countryId . "'";
            } else {
                $sql .= "
				AND	JOB.COUNTRYID	=	'" . $this->getOverrideCountry() . "'";
            }
            $sql .= "
			ORDER BY    JOB.ISDEFAULT DESC";
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
        $jobCategoryDescription = null;
        if ($result) {
            $d = 0;
            $i = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($d != 0) {
                    if ($jobCategoryDescription != $row['jobCategoryDescription']) {
                        $str .= "</optgroup><optgroup label=\"" . $row['jobCategoryDescription'] . "\">";
                    }
                } else {
                    $str .= "<optgroup label=\"" . $row['jobCategoryDescription'] . "\">";
                }
                $jobCategoryDescription = $row['jobCategoryDescription'];
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['jobId'] . "'>" . $i . ". " . $row['jobTitle'] . "</option>";
                } else {
                    if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
                    }
                }
                $d++;
                $i++;
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
     * Return Job Default Value
     * @return int
     */
    public function getJobDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $jobId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `jobId`
         FROM        `job`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         AND		  LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [jobId],
         FROM        [job]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      JOBID AS \"jobId\",
         FROM        JOB
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
            $jobId = $row['jobId'];
        }
        return $jobId;
    }

    /**
     * Return Gender
     * @return array|string
     */
    public function getGender() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `genderId`,
                     `genderDescription`
         FROM        `gender`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [genderId],
                     [genderDescription]
         FROM        [gender]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      GENDERID AS \"genderId\",
                     GENDERDESCRIPTION AS \"genderDescription\"
         FROM        GENDER
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
                    $str .= "<option value='" . $row['genderId'] . "'>" . $d . ". " . $row['genderDescription'] . "</option>";
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
     * Return Gender Default Value
     * @return int
     */
    public function getGenderDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $genderId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `genderId`
         FROM        `gender`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         AND		  LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [genderId],
         FROM        [gender]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      GENDERID AS \"genderId\",
         FROM        GENDER
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
            $genderId = $row['genderId'];
        }
        return $genderId;
    }

    /**
     * Return Marriage
     * @return array|string
     */
    public function getMarriage() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `marriageId`,
                     `marriageDescription`
         FROM        `marriage`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [marriageId],
                     [marriageDescription]
         FROM        [marriage]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      MARRIAGEID AS \"marriageId\",
                     MARRIAGEDESCRIPTION AS \"marriageDescription\"
         FROM        MARRIAGE
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
                    $str .= "<option value='" . $row['marriageId'] . "'>" . $d . ". " . $row['marriageDescription'] . "</option>";
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
     * Return Marriage Default Value
     * @return int
     */
    public function getMarriageDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $marriageId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `marriageId`
         FROM        `marriage`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         AND		  LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [marriageId],
         FROM        [marriage]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      MARRIAGEID AS \"marriageId\",
         FROM        MARRIAGE
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
            $marriageId = $row['marriageId'];
        }
        return $marriageId;
    }

    /**
     * Return Race
     * @return array|string
     */
    public function getRace() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `raceId`,
                     `raceDescription`
         FROM        `race`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [raceId],
                     [raceDescription]
         FROM        [race]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      RACEID AS \"raceId\",
                     RACEDESCRIPTION AS \"raceDescription\"
         FROM        RACE
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
                    $str .= "<option value='" . $row['raceId'] . "'>" . $d . ". " . $row['raceDescription'] . "</option>";
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
     * Return Race Default Value
     * @return int
     */
    public function getRaceDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $raceId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `raceId`
         FROM        `race`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         AND		  LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [raceId],
         FROM        [race]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      RACEID AS \"raceId\",
         FROM        RACE
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
            $raceId = $row['raceId'];
        }
        return $raceId;
    }

    /**
     * Return Religion
     * @return array|string
     */
    public function getReligion() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `religionId`,
                     `religionDescription`
         FROM        `religion`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [religionId],
                     [religionDescription]
         FROM        [religion]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      RELIGIONID AS \"religionId\",
                     RELIGIONDESCRIPTION AS \"religionDescription\"
         FROM        RELIGION
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
                    $str .= "<option value='" . $row['religionId'] . "'>" . $d . ". " . $row['religionDescription'] . "</option>";
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
     * Return Religion Default Value
     * @return int
     */
    public function getReligionDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $religionId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `religionId`
         FROM        `religion`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         AND		  LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [religionId],
         FROM        [religion]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      RELIGIONID AS \"religionId\",
         FROM        RELIGION
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
            $religionId = $row['religionId'];
        }
        return $religionId;
    }

    /**
     * Return EmploymentStatus
     * @return array|string
     */
    public function getEmploymentStatus() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `employmentStatusId`,
					 `isPaidSalary`,
                     `employmentStatusDescription`
         FROM        `employmentstatus`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isPaidSalary` DESC;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [employmentStatusId],
					 [isPaidSalary],
                     [employmentStatusDescription]
         FROM        [employmentStatus]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault] DESC";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      EMPLOYMENTSTATUSID AS \"employmentStatusId\",
					 ISPAIDSALARY AS \"isPaidSalary\"
                     EMPLOYMENTSTATUSDESCRIPTION AS \"employmentStatusDescription\"
         FROM        EMPLOYMENTSTATUS
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         ORDER BY    ISDEFAULT DESC";
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
            $d = 0;
            $isPaidSalary = null;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($d != 0) {
                    if ($isPaidSalary != $row['isPaidSalary']) {
                        $str .= "</optgroup><optgroup label=\"" . $this->t['noTextlabel'] . "\">";
                    }
                } else {
                    $str .= "<optgroup label =\"" . $this->t['noTextlabel'] . "\">";
                }
                $isPaidSalary = $row['isPaidSalary'];
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['employmentStatusId'] . "'>" . $d . ". " . $row['employmentStatusDescription'] . "</option>";
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
     * Return EmploymentStatus Default Value
     * @return int
     */
    public function getEmploymentStatusDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $employmentStatusId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `employmentStatusId`
         FROM        `employmentstatus`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         AND		  LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [employmentStatusId],
         FROM        [employmentStatus]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      EMPLOYMENTSTATUSID AS \"employmentStatusId\",
         FROM        EMPLOYMENTSTATUS
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
            $employmentStatusId = $row['employmentStatusId'];
        }
        return $employmentStatusId;
    }

    /**
     * Upload avatar before submitting the form
     * @return void
     */
    function setEmployeePicture() {
        header('Content-Type:application/json; charset=utf-8');
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        $uploader = new \qqFileUploader($this->getAllowedExtensions(), $this->getSizeLimit());
        $result = $uploader->handleUpload($this->getUploadPath());
// to pass data through iframe you will need to encode all html tags
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        INSERT INTO `imageemployeetemp`(
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
            120,
            '" . $this->strict($_GET['qqfile'], 'w') . "',
            1,
            '" . $_SESSION['staffId'] . "',NOW())";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
        INSERT INTO [imageEmployeeTemp](
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
            120,
            '" . $this->strict($_GET['qqfile'], 'w') . "',
            1,
            '" . $_SESSION['staffId'] . "',NOW())";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           INSERT INTO IMAGEEMPLOYEETEMP(
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
            120,
            '" . $this->strict($_GET['qqfile'], 'w') . "',
            1,
            '" . $_SESSION['staffId'] . "',NOW())";
                }
            }
        }
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
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
     * @return $this
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
     * Set size Limit Of Staff Upload File
     * @param int $value
     * @return $this
     */
    public function setSizeLimit($value) {
        $this->sizeLimit = $value;
        return $this;
    }

    /**
     * Return Upload Path
     * @return string
     */
    public function getUploadPath() {
        return $this->uploadPath;
    }

    /**
     * Set Upload Path
     * @param string $value
     * @return $this
     */
    public function setUploadPath($value) {
        $this->uploadPath = $value;
        return $this;
    }

    /**
     * Take the last temp file
     * @param int $staffId
     */
    function transferAvatar($staffId) {
        $sql = "
        SELECT      *
        FROM        `imageTemp`
        WHERE       `isNew`=1
        AND         `staffId`='" . $staffId . "'
        ORDER BY    `imageTempId` DESC
        LIMIT        1";
        $result = $this->q->fast($sql);
        if ($result) {
            $row = $this->q->fetchArray($result);
            $sql = "
            UPDATE `staff`
            SET    `staffAvatar`    = '" . $row['imageTempName'] . "'
            WHERE  `staffId`        = '" . $staffId . "'";
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
            // update back  the last image file to 0 preventing update the same thing again
            $sql = "
            UPDATE `imageTemp`
            SET    `isNew`    = '0'
            WHERE  `staffId`        = '" . $_SESSION['staffId'] . "'";
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
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