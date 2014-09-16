<?php

namespace Core\System\Management\Staff\Service;

// using absolute path instead of relative path..
// start fake document root. it's absolute path

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
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot);
require_once($newFakeDocumentRoot . "library/class/classAbstract.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
require_once($newFakeDocumentRoot . "library/upload/server/php.php");

/**
 * Class StaffService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Management\Staff\Service
 * @subpackage Management
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class StaffService extends ConfigClass {

    /**
     * Connection to the database
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;

    /**
     * Translation
     * @var string
     */
    public $t;

    /**
     * Upload Staff Avatar
     * @var int
     */
    private $sizeLimit;

    /**
     * Upload Staff Avatar Type
     * @var string
     */
    private $allowedExtensions;

    /**
     * @var string
     */
    private $uploadPath;

    /**
     * Email Object
     * @var \PHPMailer()
     */
    private $mail;

    /**
     * @var string
     */
    private $mailHost;

    /**
     * Enables SMTP debug information  1 false 2 true
     * @var int
     */
    private $mailSMTPDebug;

    /**
     * @var string
     */
    private $mailSMTPAuth;

    /**
     * Port
     * @var int
     */
    private $mailPort;

    /**
     * Staff Username for sending email
     * @var string
     */
    private $staffName;

    /**
     * Password for sending email
     * @var string
     */
    private $Password;

    /**
     * TLS/SLS
     * @var string
     */
    private $SMTPSecure;

    /**
     * Administrator Email
     * @var string
     */
    private $administratorEmail;

    /**
     * Email Title
     * @var string
     */
    private $emailTitle;

    /**
     * Email Description
     * @var string
     */
    private $emailDescription;

    /**
     * System Website
     * @var string
     */
    private $systemWebsite;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();

        if (isset($_SESSION['companyId'])) {
            $this->setCompanyId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
        if (isset($_SESSION['staffId'])) {
            $this->setCompanyId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(7);
        }
        // list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $this->allowedExtensions = array("jpg", "jpeg", "xml", "bmp", "png");
        // max file size in bytes
        $this->setSizeLimit((8 * 1024 * 1024));
        // set upload path
        $this->setUploadPath($this->getFakeDocumentRoot() . "v3/system/management/images/");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->mail = new \PHPMailer(true);
        $this->mail->IsSMTP();
        $sql = null;
        /**
         * Get Basic Information email setting
         */
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT  *
			FROM    `emailsetting`
			WHERE   `companyId`			=	'" . $this->getCompanyId() . "'
			AND     `emailSettingId`	=	'1'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT  *
			FROM    [emailSetting]
			WHERE   [companyId]			=	'" . $this->getCompanyId() . "'
			AND     [emailSettingId]	=	'1'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT  *
			FROM    EMAILSETTING
			WHERE   COMPANYID			=	'" . $this->getCompanyId() . "'
			AND     EMAILSETTINGID		=	'1'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            if (is_array($row)) {
                // set property email
                $this->setMailHost($row['Host']);
                $this->setMailPort($row['Port']);
                $this->setStaffName($row['username']);
                $this->setPassword($row['password']);
                $this->setSMTPSecure($row['SMTPSecure']);
                $this->setAdministratorEmail($row['administratorEmail']);
                // set variable to email object
                $this->mail->Host = $this->getMailHost();
                $this->mail->Port = $this->getMailPort();
                $this->mail->Username = $this->getStaffName();
                $this->mail->Password = $this->getPassword();
                $this->mail->SetFrom($this->getAdministratorEmail());
            }
        }
        // testing email purpose
        $this->mail->SMTPAuth = true;
        //$this->mail->SMTPDebug=2;
        $this->getSystemWebsiteInformation();
    }

    /**
     * Return Branch
     * @return array|string
     */
    public function getBranch() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
         SELECT      `branchId`,
                     `branchName`
         FROM        `branch`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      [branchId],
                     [branchName]
         FROM        [branch]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
         SELECT      BRANCHID AS \"branchId\",
                     branchName AS \"branchName\"
         FROM        BRANCH  
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
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == TRUE) {
                if ($this->getServiceOutput() == 'option') {
                    $str.="<option value='" . $row['branchId'] . "'>" . $d . ". " . $row['branchName'] . "</option>";
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
        return false;
    }

    /**
     * Return Branch Default Value
     * @return int
     */
    public function getBranchDefaultValue() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $branchId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
         SELECT      `branchId`
         FROM        	`branch`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      TOP 1 [branchId],
         FROM        [branch]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
         SELECT      BRANCHID AS \"branchId\",
         FROM        BRANCH  
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
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $branchId = $row['branchId'];
        }
        return $branchId;
    }

    /**
     * Return Role
     * @return array|string
     */
    public function getRole() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `roleId`,
                     `roleDescription`
         FROM        `role`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [roleId],
                     [roleDescription]
         FROM        [role]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      ROLEID AS \"roleId\",
                     ROLEDESCRIPTION AS \"roleDescription\"
         FROM        ROLE
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         ORDER BY    ISDEFAULT";
        }

        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['roleId'] . "'>" . $d . ". " . $row['roleDescription'] . "</option>";
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
     * Return Role Default Value
     * @return int
     * @throws \Exception
     */
    public function getRoleDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $roleId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `roleId`
         FROM        	`role`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [roleId],
         FROM        [role]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      ROLEID AS \"roleId\",
         FROM        ROLE
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
            $roleId = $row['roleId'];
        }
        return $roleId;
    }

    /**
     * Return Language
     * @return array|string
     */
    public function getLanguage() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `languageId`,
                     `languageDescription`
         FROM        `language`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [languageId],
                     [languageDescription]
         FROM        [language]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } elseif ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      LANGUAGEID AS \"languageId\",
                     LANGUAGEDESCRIPTION AS \"languageDescription\"
         FROM        LANGUAGE
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         ORDER BY    ISDEFAULT";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['languageId'] . "'>" . $d . ". " . $row['languageDescription'] . "</option>";
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
     * Return Language Default Value
     * @return int
     * @throws \Exception
     */
    public function getLanguageDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $languageId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `languageId`
         FROM        	`language`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [languageId],
         FROM        [language]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      LANGUAGEID AS \"languageId\",
         FROM        LANGUAGE
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
            $languageId = $row['languageId'];
        }
        return $languageId;
    }
    /**
     * Return Department
     * @return array|string
     * @throws \Exception
     */
    public function getDepartment() {
    	//initialize dummy value.. no content header.pure html
    	$sql=null;
    	$str=null;
    	$items=array();
    	if($this->getVendor()==self::MYSQL) {
    		$sql ="
         SELECT      `departmentId`,
                     `departmentDescription`
         FROM        `department`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '".$this->getCompanyId()."'
         ORDER BY    `isDefault`;";
    	} else if ($this->getVendor()==self::MSSQL) {
    		$sql ="
         SELECT      [departmentId],
                     [departmentDescription]
         FROM        [department]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '".$this->getCompanyId()."'
         ORDER BY    [isDefault]";
    	} else if ($this->getVendor()==self::ORACLE) {
    		$sql ="
         SELECT      DEPARTMENTID AS \"departmentId\",
                     DEPARTMENTDESCRIPTION AS \"departmentDescription\"
         FROM        DEPARTMENT
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '".$this->getCompanyId()."'
         ORDER BY    ISDEFAULT";
    	}  else {
    		header('Content-Type:application/json; charset=utf-8');
    		echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
    		exit();
    	}
    	try {
    		$result =$this->q->fast($sql);
    	} catch (\Exception $e) {
    		echo json_encode(array("success" => false, "message" => $e->getMessage()));
    		exit();
    	}
    	if($result) {
    		$d=1;
    		while(($row = $this->q->fetchArray($result))==TRUE) {
    			if($this->getServiceOutput()=='option'){
    				$str.="<option value='".$row['departmentId']."'>".$d.". ".$row['departmentDescription']."</option>";
    			} else if ($this->getServiceOutput()=='html')  {
    				$items[] = $row;
    			}
    			$d++;
    		}
    		unset($d);
    	}
    	if($this->getServiceOutput()=='option'){
    		if (strlen($str) > 0) {
    			$str = "<option value=''>".$this->t['pleaseSelectTextLabel']."</option>" . $str;
    		} else {
    			$str= "<option value=''>".$this->t['notAvailableTextLabel']."</option>";
    		}
    		header('Content-Type:application/json; charset=utf-8');
    		echo json_encode(array("success"=>true,"message"=>"complete","data"=>$str));
    		exit();
    	} else if ($this->getServiceOutput()=='html')  {
    		return $items;
    	}
    	return false;
    }
    /**
     * Return Department Default Value
     * @return int
     * @throws \Exception
     */
    public function getDepartmentDefaultValue() {
    	//initialize dummy value.. no content header.pure html
    	$sql=null;
    	$str=null;
    	$departmentId=null;
    	if($this->getVendor()==self::MYSQL) {
    		$sql ="
         SELECT      `departmentId`
         FROM        	`department`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '".$this->getCompanyId()."'
         AND    	  `isDefault` =	  1
         LIMIT 1";
    	} else if ($this->getVendor()==self::MSSQL) {
    		$sql ="
         SELECT      TOP 1 [departmentId],
         FROM        [department]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '".$this->getCompanyId()."'
         AND    	  [isDefault] =   1";
    	} else if ($this->getVendor()==self::ORACLE) {
    		$sql ="
         SELECT      DEPARTMENTID AS \"departmentId\",
         FROM        DEPARTMENT
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '".$this->getCompanyId()."'
         AND    	  ISDEFAULT	  =	   1
         AND 		  ROWNUM	  =	   1";
    	}  else {
    		header('Content-Type:application/json; charset=utf-8');
    		echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
    		exit();
    	}
    	try {
    		$result =$this->q->fast($sql);
    	} catch (\Exception $e) {
    		echo json_encode(array("success" => false, "message" => $e->getMessage()));
    		exit();
    	}
    	if($result) {
    		$row = $this->q->fetchArray($result);
    		$departmentId = $row['departmentId'];
    	}
    	return $departmentId;
    }
    /**
     * Return Theme
     * @return array|string
     */
    public function getTheme() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `themeId`,
                     `themeDescription`
         FROM        `theme`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [themeId],
                     [themeDescription]
         FROM        [theme]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      THEMEID AS \"themeId\",
                     THEMEDESCRIPTION AS \"themeDescription\"
         FROM        THEME
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
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['themeId'] . "'>" . $d . ". " . $row['themeDescription'] . "</option>";
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
     * Return Theme Default Value
     * @return int
     * @throws \Exception
     */
    public function getThemeDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $themeId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `themeId`
         FROM        	`theme`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [themeId],
         FROM        [theme]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      THEMEID AS \"themeId\",
         FROM        THEME
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
            $themeId = $row['themeId'];
        }
        return $themeId;
    }

    /**
     * Create Staff/User Access Based On Role Access
     * @param int $staffId Staff Primary Key
     * @param int $roleId Role Primary Key
     * @return void
     * @throws \Exception
     */
    public function createAccess($staffId, $roleId) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  *
            FROM    `leafroleaccess`
            WHERE   `roleId`='" . $roleId . "'
            AND     `companyId`='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT	*
            FROM 	[leafRoleAccess]
            WHERE 	[roleId]	=	'" . $roleId . "'
            AND     [companyId] ='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT	LEAFROLEACCESS.LEAFROLEACCESSDRAFTVALUE AS \"leafRoleAccessDraftValue\",
                        LEAFROLEACCESS.LEAFROLEACCESSCREATEVALUE AS \"leafRoleAccessDraftValue\",
                        LEAFROLEACCESS.LEAFROLEACCESSREADVALUE AS \"leafRoleAccessReadValue\",
                        LEAFROLEACCESS.LEAFROLEACCESSUPDATEVALUE AS \"leafRoleAccessUpdateValue\",
                        LEAFROLEACCESS.LEAFROLEACCESSDELETEVALUE AS \"leafRoleAccessDeleteValue\",
                        LEAFROLEACCESS.LEAFROLEACCESSREVIEWVALUE AS \"leafRoleAccessReviewValue\",
                        LEAFROLEACCESS.LEAFROLEACCESSAPPROVEDVALUE AS \"leafRoleAccessApprovedValue\",
                        LEAFROLEACCESS.LEAFROLEACCESSPOSTVALUE AS \"leafRoleAccessPostValue\",
                        LEAFROLEACCESS.LEAFROLEACCESSPRINTVALUE AS \"leafRoleAccessPrintValue\"
            FROM 	LEAFROLEACCESS
            WHERE 	ROLE        =	'" . $roleId . "'
            AND		COMPANYID   =   '" . $this->getCompanyId() . "'";
        }
        try {
            $this->q->read($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($this->q->numberRows() > 0) {
            $data = $this->q->activeRecord();
            foreach ($data as $rowLeafGroupAccess) {
                if ($this->getVendor() == self::MYSQL) {
                    $sql = "
                    INSERT INTO	`leafaccess`
                    (
                        `companyId`,
			`leafId`,
                        `staffId`,
                        `leafAccessCreateValue`,
                        `leafAccessReadValue`,
                        `leafAccessUpdateValue`,
                        `leafAccessDeleteValue`,
                        `leafAccessPrintValue`,
                        `leafAccessPostValue`
                    )   VALUES(
			'" . $this->getCompanyId() . "',
                        '" . $rowLeafGroupAccess ['leafId'] . "',
                        '" . $staffId . "',
                        '" . $rowLeafGroupAccess ['leafAccessCreateValue'] . "',
                        '" . $rowLeafGroupAccess ['leafAccessReadValue'] . "',
                        '" . $rowLeafGroupAccess ['leafAccessUpdateValue'] . "',
                        '" . $rowLeafGroupAccess ['leafAccessDeleteValue'] . "',
                        '" . $rowLeafGroupAccess ['leafAccessPrintValue'] . "',
                        '" . $rowLeafGroupAccess ['leafAccessPostValue'] . "'
                    )	";
                } else if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                    INSERT INTO [leafAccess]
                    (
                        [companyId],
			[leafId],
                        [staffId],
                        [leafAccessCreateValue],
                        [leafAccessReadValue],
                        [leafAccessUpdateValue],
                        [leafAccessDeleteValue],
                        [leafAccessPrintValue],
                        [leafAccessPostValue]
                    )   VALUES(
			'" . $this->getCompanyId() . "',
                        '" . $rowLeafGroupAccess ['leafId'] . "',
                        '" . $staffId . "',
                        '" . $rowLeafGroupAccess ['leafAccessCreateValue'] . "',
                        '" . $rowLeafGroupAccess ['leafAccessReadValue'] . "',
                        '" . $rowLeafGroupAccess ['leafAccessUpdateValue'] . "',
                        '" . $rowLeafGroupAccess ['leafAccessDeleteValue'] . "',
                        '" . $rowLeafGroupAccess ['leafAccessPrintValue'] . "',
                        '" . $rowLeafGroupAccess ['leafAccessPostValue'] . "'
                    )	";
                } else if ($this->getVendor() == self::ORACLE) {
                    $sql = "
                                INSERT INTO	LEAFACCESS
                                (
                                    COMPANYID,
                                    LEAFID,
                                    STAFFID,
                                    LEAFACCESSCREATEVALUE,
                                    LEAFACCESSREADVALUE,
                                    LEAFACCESSUPDATEVALUE,
                                    LEAFACCESSDELETEVALUE,
                                    LEAFACCESSPRINTVALUE,
                                    LEAFACCESSPOSTVALUE
                                )VALUES(
                                    '" . $this->getCompanyId() . "',
                                    '" . $rowLeafGroupAccess ['leafId'] . "',
                                    '" . $staffId . "',
                                    '" . $rowLeafGroupAccess ['leafAccessCreateValue'] . "',
                                    '" . $rowLeafGroupAccess ['leafAccessReadValue'] . "',
                                    '" . $rowLeafGroupAccess ['leafAccessUpdateValue'] . "',
                                    '" . $rowLeafGroupAccess ['leafAccessDeleteValue'] . "',
                                    '" . $rowLeafGroupAccess ['leafAccessPrintValue'] . "',
                                    '" . $rowLeafGroupAccess ['leafAccessPostValue'] . "'
                                )";
                }
                try {
                    $this->q->create($sql);
                } catch (\Exception $e) {
                    header('Content-Type:application/json; charset=utf-8');
                    echo json_encode(array("success" => false, "message" => $e->getMessage()));
                    exit();
                }
                if ($this->q->getExecute() == 'fail') {
                    echo json_encode(array("success" => false, "message" => $this->q->getResponse()));
                    exit();
                }
            }
        }
    }

    /**
     * Update User / Staff Access Based On Roles
     * @param int $staffId Staff Primary Key
     * @param int $oldRoleId Old Role Primary Key
     * @param int $newRoleId New Role Primary Key
     * @throws \Exception
     */
    public function updateAccess($staffId, $oldRoleId, $newRoleId) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        // check change group or not
        if ($newRoleId != $oldRoleId) {
            /**
             * update  leaf group access
             * */
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
                SELECT	`leafId`
                FROM 	`leafroleaccess`
                WHERE 	`roleId`            =   '" . $newRoleId . "' ";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                SELECT	[leafId]
                FROM 	[leafRoleAccess]
                WHERE 	[roleId]            =   '" . $newRoleId . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                SELECT	LEAFID		AS 	leafId
                FROM 	LEAFROLEACCESS
                WHERE 	ROLEID          =   '" . $newRoleId . "' ";
            }
            try {
                $result = $this->q->fast($sql);
            } catch (\Exception $e) {
                header('Content-Type:application/json; charset=utf-8');
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
            $data = $this->q->activeRecord($result);
            foreach ($data as $rowLeafGroupAccess) {
                // check if exist record or not
                if ($this->getVendor() == self::MYSQL) {
                    $sql = "
                    SELECT  *
                    FROM    `leafaccess`
                    WHERE   `staffId`       =   '" . $staffId . "'
                    AND     `leafId`        =	'" . $rowLeafGroupAccess ['leafId'] . "' ";
                } else if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                    SELECT  *
                    FROM    [leafAccess]
                    WHERE   [staffId]       =	'" . $staffId . "'
                    AND     [leafId]        =   '" . $rowLeafGroupAccess ['leafId'] . "' ";
                } else if ($this->getVendor() == self::ORACLE) {
                    $sql = "
                    SELECT  LEAFACCESSCREATEVALUE	AS  \"leafAccessCreateValue\",
                            LEAFACCESSREADVALUE		AS  \"leafAccessDeleteValue\",
                            LEAFACCESSPOSTVALUE 	AS  \"leafAccessPostValue\",
                            LEAFACCESSPRINTVALUE 	AS  \"leafAccessPrintValue\",
                            lLEAFACCESSREADVALUE 	AS  \"leafAccessReadValue\",
                            LEAFACCESSUPDATEVALUE	AS  \"leafAccessUpdateValue\"
                    FROM    LEAFACCESS
                    WHERE   STAFFID			=	'" . $staffId . "'
                    AND     LEAFID			=	'" . $rowLeafGroupAccess ['leafId'] . "' ";
                }
                try {
                    $result = $this->q->fast($sql);
                } catch (\Exception $e) {
                    header('Content-Type:application/json; charset=utf-8');
                    echo json_encode(array("success" => false, "message" => $e->getMessage()));
                    exit();
                }
                if ($this->q->numberRows($result) > 0) {
                    if ($this->getVendor() == self::MYSQL) {
                        $sql = "
                        UPDATE 	`leafaccess`
                        SET     `leafAccessCreateValue`	=   '" . $rowLeafGroupAccess ['leafAccessCreateValue'] . "',
                                `leafAccessDeleteValue`	=   '" . $rowLeafGroupAccess ['leafAccessReadValue'] . "',
                                `leafAccessPostValue`	=   '" . $rowLeafGroupAccess ['leafAccessUpdateValue'] . "',
                                `leafAccessPrintValue`	=   '" . $rowLeafGroupAccess ['leafAccessDeleteValue'] . "',
                                `leafAccessReadValue`	=   '" . $rowLeafGroupAccess ['leafAccessPrintValue'] . "',
                                `leafAccessUpdateValue`	=   '" . $rowLeafGroupAccess ['leafAccessPostValue'] . "'
                        WHERE 	`staffId`               =   '" . $staffId . "'
                        AND     `leafId`                =   '" . $rowLeafGroupAccess ['leafId'] . "'";
                    } else if ($this->getVendor() == self::MSSQL) {
                        $sql = "
                        UPDATE  [leafAccess]
                        SET 	[leafAccessCreateValue] =   '" . $rowLeafGroupAccess ['leafAccessCreateValue'] . "',
                                [leafAccessDeleteValue] =   '" . $rowLeafGroupAccess ['leafAccessReadValue'] . "',
                                [leafAccessPostValue]   =   '" . $rowLeafGroupAccess ['leafAccessUpdateValue'] . "',
                                [leafAccessPrintValue]  =   '" . $rowLeafGroupAccess ['leafAccessDeleteValue'] . "',
                                [leafAccessReadValue]   =   '" . $rowLeafGroupAccess ['leafAccessPrintValue'] . "',
                                [leafAccessUpdateValue] =   '" . $rowLeafGroupAccess ['leafAccessPostValue'] . "'
                        WHERE 	[staffId]               =   '" . $staffId . "'
                        AND	[leafId]                =   '" . $rowLeafGroupAccess ['leafId'] . "'";
                    } else if ($this->getVendor() == self::ORACLE) {
                        $sql = "
                        UPDATE 	LEAFACCESS
                        SET 	LEAFACCESSCREATEVALUE	=   '" . $rowLeafGroupAccess ['leafAccessCreateValue'] . "',
                                LEAFACCESSREADVALUE		=   '" . $rowLeafGroupAccess ['leafAccessReadValue'] . "',
                                LEAFACCESSUPDATEVALUE	=   '" . $rowLeafGroupAccess ['leafAccessUpdateValue'] . "',
                                LEAFACCESSDELETEVALUE	=   '" . $rowLeafGroupAccess ['leafAccessDeleteValue'] . "',
                                LEAFACCESSPRINTVALUE	=   '" . $rowLeafGroupAccess ['leafAccessPrintValue'] . "',
                                LEAFACCESSPOSTVALUE		=   '" . $rowLeafGroupAccess ['leafAccessPostValue'] . "'
                        WHERE 	STAFFID					=   '" . $staffId . "'
                        AND		LEAFID                  =   '" . $rowLeafGroupAccess ['leafId'] . "'";
                    }
                    try {
                        $this->q->update($sql);
                    } catch (\Exception $e) {
                        header('Content-Type:application/json; charset=utf-8');
                        echo json_encode(array("success" => false, "message" => $e->getMessage()));
                        exit();
                    }
                } else {
                    if ($this->getVendor() == self::MYSQL) {
                        $sql = "
                        INSERT INTO `leafaccess`(
                            `companyId`,
							`leafId`,
                            `staffId`,
                            `leafAccessCreateValue`,
                            `leafAccessReadValue`,
                            `leafAccessUpdateValue`,
                            `leafAccessDeleteValue`,
                            `leafAccessPrintValue`,
                            `leafAccessPostValue`
                        ) VALUES (
                            '" . $this->getCompanyId() . "',
                            '" . $rowLeafGroupAccess ['leafId'] . "',
                            '" . $staffId . "',
                            '" . $rowLeafGroupAccess ['leafAccessCreateValue'] . "',
                            '" . $rowLeafGroupAccess ['leafAccessReadValue'] . "',
                            '" . $rowLeafGroupAccess ['leafAccessUpdateValue'] . "',
                            '" . $rowLeafGroupAccess ['leafAccessDeleteValue'] . "',
                            '" . $rowLeafGroupAccess ['leafAccessPrintValue'] . "',
                            '" . $rowLeafGroupAccess ['leafAccessPostValue'] . "'
                        )	";
                    } else if ($this->getVendor() == self::MSSQL) {
                        $sql = "
                        INSERT INTO [leafAccess]
                        (
                            [companyId],
			    [leafId],
                            [staffId],
                            [leafAccessCreateValue],
                            [leafAccessReadValue],
                            [leafAccessUpdateValue],
                            [leafAccessDeleteValue],
                            [leafAccessPrintValue],
                            [leafAccessPostValue]
                        )VALUES(
							'" . $this->getCompanyId() . "',
                            '" . $rowLeafGroupAccess ['leafId'] . "',
                            '" . $staffId . "',
                            '" . $rowLeafGroupAccess ['leafAccessCreateValue'] . "',
                            '" . $rowLeafGroupAccess ['leafAccessReadValue'] . "',
                            '" . $rowLeafGroupAccess ['leafAccessUpdateValue'] . "',
                            '" . $rowLeafGroupAccess ['leafAccessDeleteValue'] . "',
                            '" . $rowLeafGroupAccess ['leafAccessPrintValue'] . "',
                            '" . $rowLeafGroupAccess ['leafAccessPostValue'] . "'
                        )	";
                    } else if ($this->getVendor() == self::ORACLE) {
                        $sql = "
                        INSERT INTO	LEAFACCESS
                        (
                            COMPANYID
                            LEAFID,
                            STAFFID,
                            LEAFACCESSCREATEVALUE,
                            LEAFACCESSREADVALUE,
                            LEAFACCESSUPDATEVALUE,
                            LEAFACCESSDELETEVALUE,
                            LEAFACCESSPRINTVALUE,
                            LEAFACCESSPOSTVALUE
                        )VALUES(
							'" . $this->getCompanyId() . "',
                            '" . $rowLeafGroupAccess ['leafId'] . "',
                            '" . $staffId . "',
                            '" . $rowLeafGroupAccess ['leafAccessCreateValue'] . "',
                            '" . $rowLeafGroupAccess ['leafAccessReadValue'] . "',
                            '" . $rowLeafGroupAccess ['leafAccessUpdateValue'] . "',
                            '" . $rowLeafGroupAccess ['leafAccessDeleteValue'] . "',
                            '" . $rowLeafGroupAccess ['leafAccessPrintValue'] . "',
                            '" . $rowLeafGroupAccess ['leafAccessPostValue'] . "'
                        )";
                    }
                    try {
                        $this->q->create($sql);
                    } catch (\Exception $e) {
                        header('Content-Type:application/json; charset=utf-8');
                        echo json_encode(array("success" => false, "message" => $e->getMessage()));
                        exit();
                    }
                }
            }
        }
        // if change group .All access  before will deactivated
        // update leaf access to null
    }

    /**
     * Upload Avatar before submitting the form.
     * @throws \Exception
     * @return void
     */
    function setStaffAvatar() {
        header('Content-Type:application/json; charset=utf-8');
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        $qqfile = null;
        $uploader = new \qqFileUploader($this->getAllowedExtensions(), $this->getSizeLimit());
        $result = $uploader->handleUpload($this->getUploadPath());
        if (isset($_GET['qqfile'])) {
            $qqfile = $_GET['qqfile'];
        }
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
            120,
            '" . $this->strict($qqfile, 'w') . "',
            1,
            '" . $_SESSION['staffId'] . "',NOW())";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
        INSERT INTO [imageTemp](
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
            120,
            '" . $this->strict($_GET['qqfile'], 'w') . "',
            1,
            '" . $_SESSION['staffId'] . "',NOW())";
        }
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        exit();
    }

    /**
     * Take the last temp file
     * @param int $staffId Staff Primary Key
     * @throws \Exception
     */
    function transferAvatar($staffId) {
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT      * 
			FROM        `imagetemp` 
			WHERE       `isNew`=1
			AND         `staffId`='" . $staffId . "'
			ORDER BY    `imageTempId` DESC
			LIMIT        1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      * 
			FROM        [imageTemp]
			WHERE      [isNew]=1
			AND         [staffId]='" . $staffId . "'
			ORDER BY    [imageTempId] DESC
			LIMIT        1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT      * 
			FROM         IMAGETEMP
			WHERE       ISNEW=1
			AND            STAFFID='" . $staffId . "'
			ORDER BY    IMAGETEMPID DESC
			LIMIT        1";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
            UPDATE `staff`
            SET    `staffAvatar`    = '" . $row['imageTempName'] . "'
            WHERE  `staffId`        = '" . $staffId . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
            UPDATE `staff`
            SET    `staffAvatar`    = '" . $row['imageTempName'] . "'
            WHERE  `staffId`        = '" . $staffId . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
            UPDATE `STAFF`
            SET    `STAFFAVATAR`    = '" . $row['imageTempName'] . "'
            WHERE  `STAFFID`        = '" . $staffId . "'";
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
				SET    `isNew`    = '0'
				WHERE  `staffId`        = '" . $_SESSION['staffId'] . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
				UPDATE `imageTemp`
				SET    `isNew`    = '0'
				WHERE  `staffId`        = '" . $_SESSION['staffId'] . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
				UPDATE IMAGETEMP
				SET    ISNEW    = '0'
				WHERE  STAFFID        = '" . $_SESSION['staffId'] . "'";
            }
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
                header('Content-Type:application/json; charset=utf-8');
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
    }

    /**
     * Create Verification Code using sha1.
     * @return string
     */
    public function setVerificationCode() {
        return sha1(rand(1, 100000));
    }

    /**
     * Return Verification Code
     * @param int $userId User Primary Key
     * @return string
     */
    public function getVerificationCode($userId) {
        $sql = null;
        $verificationCode = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT    `verificationCode`
            FROM      `staff`
            WHERE     `staffId`            = '" . $userId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT [verificationCode]
            FROM   [staff]
            WHERE  [staffId] = '" . $userId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT VERIFICATIONCODE
            FROM   STAFF
            WHERE  STAFFID = '" . $userId . "'";
        }
        try {
            $result = $this->q->read($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $verificationCode = $row['verificationCode'];
        }
        return $verificationCode;
    }

    /**
     * User Email.Only check when user from portal
     * @param string $userEmail Email
     * @return int
     * @throws \Exception
     */
    public function checkDuplicateEmail($userEmail) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT `userEmail`
            FROM   `staff`
            WHERE  `staffEmail` = '" . $userEmail . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT [userEmail]
            FROM   [staff]
            WHERE  [staffEmail] = '" . $userEmail . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT USEREMAIL
            FROM   STAFF
            WHERE  STAFFEMAIL = '" . $userEmail . "'";
        }

        try {
            $result = $this->q->read($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($this->q->numberRows($result) > 0) {
            // the user have been registered
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Check Duplicate User Name .
     * @param string $userName Staff / User name
     * @return int
     * @throws \Exception
     */
    public function checkDuplicateStaffName($userName) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT `userName`
            FROM   `staff`
            WHERE  `companyId`='" . $this->getCompanyId() . "'
            AND    `staffName` = '" . $userName . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT [userName]
            FROM   [staff]
            WHERE  [companyId] = '" . $this->getCompanyId() . "'
            AND    [staffName] = '" . $userName . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT USERNAME
            FROM   STAFF
            WHERE  COMPANYID ='" . $this->getCompanyId() . "'
            AND    STAFFNAME = '" . $userName . "'";
        }

        try {
            $result = $this->q->read($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($this->q->numberRows($result) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Verify User
     * @param int $staffId User Primary Key
     * @param string $verificationCode Email Code Verification
     * @return int
     */
    public function getVerifyUser($staffId, $verificationCode) {

        $sql = null;
        if ($verificationCode == $this->getVerificationCode($staffId)) {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
                UPDATE `staff`
                SET    `isVerification`    = '1',
                       `isApproved`        = '1'
                WHERE  `staffId`            = '" . $staffId . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE [staff]
                SET    [isVerification]    = '1',
                       [isApproved]        = '1'
                WHERE  [staffId]            = '" . $staffId . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE STAFF
                SET    ISVERIFICATION    = '1',
                       ISAPPROVED        = '1'
                WHERE  STAFFID            = '" . $staffId . "'";
            }
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
                header('Content-Type:application/json; charset=utf-8');
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
            return 1;
        } else {

            return 0;
        }
    }

    /**
     * Register Business Partners
     * @param int $staffId User Primary Key
     * @param string $staffEmail User Email
     */
    public function registerBusinessPartners($staffId, $staffEmail) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO     `businesspartner`
            (
                            `companyId`,
                            `staffId`,
                            `businessPartnerCompany`,
                            `businessPartnerEmail`
                )VALUES(
                       '" . $this->getCompanyId() . "',
                       '" . $staffId . "',
                       '" . $staffEmail . "',
                       '" . $staffEmail . "'
                )";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            INSERT INTO    `businesspartner`
            (
		            [companyId],
                            [staffId],
                            [businessPartnerCompany],
                            [businessPartnerEmail]
            )VALUES(
                       '" . $this->getCompanyId() . "',
                       '" . $staffId . "',
                       '" . $staffEmail . "',
                       '" . $staffEmail . "'
            )";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                INSERT INTO     BUSINESSPARTNER
                (
                                COMPANYID,
                                STAFFID,
                                BUSINESSPARTNERCOMPANY,
                                BUSINESSPARTNEREMAIL
                ) VALUES (
                       '" . $this->getCompanyId() . "',
                       '" . $staffId . "',
                       '" . $staffEmail . "',
                       '" . $staffEmail . "'
                )";
        }
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $data['STAFF_NAME'] = $staffEmail;
        $data['STAFF_EMAIL'] = $staffEmail;
        $data['STAFF_PASSWORD'] = "hai hackers";
        // send email registration
        $data = $this->getStaffEmailInformation($staffEmail);
        $data['SYSTEM_EMAIL'] = $this->getAdministratorEmail();
        $data['SYSTEM_WEBSITE'] = $this->getSystemWebsite();
        $this->sendEmail('register', $data);
    }

    /**
     * Get Staff/ User Name Information
     * @param string $staffName Staff/User Name
     * @return mixed
     * @throws \Exception
     */
    private function getStaffNameInformation($staffName) {
        $sql = null;
        $row = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT    `staffName`     AS    `STAFF_NAME`,
                      `staffEmail`    AS    `STAFF_EMAIL`,
                      `staffPassword` AS    `STAFF_PASSWORD`
            FROM      `staff`
            WHERE     `staffName`            = '" . $staffName . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT    [staffName]     AS    [STAFF_NAME],
                      [staffEmail]    AS    [STAFF_EMAIL],
                      [staffPassword] AS    [STAFF_PASSWORD]
            FROM      [staff]
            WHERE     [staffName]            = '" . $staffName . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      STAFFNAME     AS    \"STAFF_NAME\",
                        STAFFEMAIL    AS    \"STAFF_EMAIL\",
                        STAFFPASSWORD AS    \"STAFF_PASSWORD\"
            FROM        STAFF
            WHERE       STAFFNAME            = '" . $staffName . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $total = ($this->q->numberRows($result));
            if ($total > 0) {
                $row = $this->q->fetchArray($result);
            } else {
                return false;
            }
        }
        return $row;
    }

    /**
     * Get Staff/User Email Information
     * @param string $staffEmail
     * @return mixed
     */
    private function getStaffEmailInformation($staffEmail) {
        $sql = null;
        $data = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      `staffName`     AS `STAFF_NAME`,
                        `staffEmail`    AS  `STAFF_EMAIL`,
                        `staffPassword` AS `STAFF_PASSWORD`
            FROM        `staff`
            WHERE       `staffEmail`            = '" . $staffEmail . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  [staffName]     AS [STAFF_NAME],
		    [staffEmail]    AS [STAFF_EMAIL],
                    [staffPassword] AS [STAFF_PASSWORD]
            FROM    [staff]
            WHERE   [staffEmail]            = '" . $staffEmail . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT    STAFFNAME     AS \"STAFF_NAME\",
		      STAFFEMAIL    AS  \"STAFF_EMAIL\",
		      STAFFPASSWORD AS \"STAFF_PASSWORD\"
            FROM      STAFF
            WHERE     STAFFEMAIL            = '" . $staffEmail . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $data = $row;
        }
        // fake return
        return $data;
    }

    /**
     *
     */
    private function getSystemWebsiteInformation() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT    `systemWebsite`
            FROM      `systemsetting`
            WHERE     `companyId`='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT    [systemWebsite]
            FROM      [systemSetting]
            WHERE     [companyId]='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT    SYSTEMWEBSITE
            FROM      SYSTEMSETTING
            WHERE     COMPANYID='" . $this->getCompanyId() . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $this->setSystemWebsite($row['systemWebsite']);
        }
    }

    /**
     * Send Email Via php function.Demo purpose
     * @param string $title Email Title
     * @param mixed $data Array Data contain information
     */
    private function sendEmail($title, $data) {
        $templateFile = "";
        $subject = "";

        switch ($title) {
            case 'register':
                $templateFile = 'registration.html';
                $subject = 'Your registration have been verified..';
                break;
            case 'forget':
                $templateFile = 'forget.html';
                $subject = 'Your  password';
                break;
        }
        // using tpl
        if (file_exists($this->getFakeDocumentRoot() . "v3/system/management/template/" . $templateFile)) {
            $templateFileContent = file_get_contents(
                    $this->getFakeDocumentRoot() . "v3/system/management/template/" . $templateFile
            );
            // replace
            $templateFileVariableContent = array(
                "[SYSTEM_EMAIL]",
                "[SYSTEM_WEBSITE]",
                "[STAFF_NAME]",
                "[STAFF_EMAIL]",
                "[STAFF_PASSWORD]"
            );
            $dataFileVariable = array(
                $data['SYSTEM_EMAIL'],
                $data['SYSTEM_WEBSITE'],
                $data['STAFF_NAME'],
                $data['STAFF_EMAIL'],
                $data['STAFF_PASSWORD']
            );
            $templateFileContent = str_replace($templateFileVariableContent, $dataFileVariable, $templateFileContent);
        } else {
            echo json_encode(
                    array("success" => false, "message" => "Template " . $title . " Cannot Find." . $templateFile)
            );
            exit();
        }

        // To send HTML mail, the Content-type header must be set
        //$headers  = 'MIME-Version: 1.0' . "\r\n";
        // $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        // Additional headers
        //$headers .= "To: ".$staffName." <".$staffEmail.">" . "\r\n";
        //$headers .= 'From: administrator <administrator@Visitor.rss>' . "\r\n";
        // mail($staffEmail,$subject,$templateFileContent,$headers);
        try {
            $this->mail->AddAddress($data['STAFF_EMAIL'], $data['STAFF_NAME']);
            $this->mail->Subject = ($subject);
            $this->mail->MsgHTML($templateFileContent);
            $this->mail->Send();
            $this->createNotification(
                    "Email have been sent. Subject : " . $subject . " Email : " . $data['STAFF_EMAIL'] . "  Contain : " . $templateFileContent
            );
        } catch (\phpmailerException $e) {
            $this->createNotification(
                    "Email Got Problem Sending" . $e->errorMessage() . " host " . $this->getMailHost(
                    ) . " Port" . $this->getMailPort() . " username" . $this->getUsername(
                    ) . " password" . $this->getPassword()
            );
        } catch (\Exception $e) {
            $this->createNotification("Unknown error" . $e->getMessage());
        }
        // sleep(210); // testing if server to fast
    }

    /**
     * Validate For User URL Token
     * @param $userId
     */
    public function validateEmail($userId) {
        
    }

    /**
     * Resend Password  either checking via email  or username
     * @params string $staffName User Name
     * @params string $staffEmail User Email
     * @params string $securityToken Security Token
     */
    public function getResendPassword($staffName, $staffEmail, $securityTokenWeb) {
        header('Content-Type:application/json; charset=utf-8');
        $start = microtime(true);
        $sql = "SET NAMES utf8";
        $this->q->fast($sql);
        $title = 'forget';
        if ($securityTokenWeb != $this->getSecurityToken()) {
            echo json_encode(
                    array("success" => false, "message" => "Hai Hacker.Need to bypass system security ???? .Nice Try:)")
            );
            exit();
        }

        if ($staffName && strlen($staffName) > 0) {
            $validateStaff = $this->checkDuplicateStaffname($staffName);
            if ($validateStaff == 0) {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "This username didn't exist in our system.Please email  xxx for verication purpose"
                        )
                );
                exit();
            } else {
                // send email forget password
                $data = $this->getStaffNameInformation($staffName);
                $data['SYSTEM_EMAIL'] = $this->getAdministratorEmail();
                $data['SYSTEM_WEBSITE'] = $this->getSystemWebsite();
                $this->sendEmail($title, $data);
                echo json_encode(array("success" => true, "message" => "Please Check in your mail box"));
                exit();
            }
        }
        if ($staffEmail && strlen($staffEmail) > 0) {
            $valEmail = $this->checkDuplicateEmail($staffEmail);
            if ($valEmail == 0) {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "This username/staff didn't exist in our system.Please email  xxx for verication purpose"
                        )
                );
                exit();
            } else {
                // send email forget password
                $data = $this->getStaffEmailInformation($staffEmail);
                $data['SYSTEM_EMAIL'] = $this->getAdministratorEmail();
                $data['SYSTEM_WEBSITE'] = $this->getSystemWebsite();
                $this->sendEmail($title, $data);
                echo json_encode(array("success" => true, "message" => "Please Check in your mail box"));
                exit();
            }
        }
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => false,
                    "message" => "Dear Sir / Madam .We got issue ..  we will check back",
                    "time" => $time
                )
        );
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
     * @return \Core\System\Management\Staff\Service\StaffService
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
     * @return \Core\System\Management\Staff\Service\StaffService
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
     * @return \Core\System\Management\Staff\Service\StaffService
     */
    public function setUploadPath($value) {
        $this->uploadPath = $value;
        return $this;
    }

    /**
     * @param string $Password
     * @return \Core\System\Management\Staff\Service\StaffService
     */
    public function setPassword($Password) {
        $this->Password = $Password;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword() {
        return $this->Password;
    }

    /**
     * @param string $SMTPSecure
     * @return \Core\System\Management\Staff\Service\StaffService
     */
    public function setSMTPSecure($SMTPSecure) {
        $this->SMTPSecure = $SMTPSecure;
        return $this;
    }

    /**
     * @return string
     */
    public function getSMTPSecure() {
        return $this->SMTPSecure;
    }

    /**
     * @param string $staffName
     * @return \Core\System\Management\Staff\Service\StaffService
     */
    public function setStaffName($staffName) {
        $this->staffName = $staffName;
        return $this;
    }

    /**
     * Return Staff/User Name
     * @return string
     */
    public function getStaffName() {
        return $this->staffName;
    }

    /**
     * @param string $emailDescription
     * @return \Core\System\Management\Staff\Service\StaffService
     */
    public function setEmailDescription($emailDescription) {
        $this->emailDescription = $emailDescription;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmailDescription() {
        return $this->emailDescription;
    }

    /**
     * @param string $emailTitle
     * @return \Core\System\Management\Staff\Service\StaffService
     */
    public function setEmailTitle($emailTitle) {
        $this->emailTitle = $emailTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmailTitle() {
        return $this->emailTitle;
    }

    /**
     * @param string $mailHost
     * @return \Core\System\Management\Staff\Service\StaffService
     */
    public function setMailHost($mailHost) {
        $this->mailHost = $mailHost;
        return $this;
    }

    /**
     * @return string
     */
    public function getMailHost() {
        return $this->mailHost;
    }

    /**
     * @param string $mailSMTPAuth
     * @return \Core\System\Management\Staff\Service\StaffService
     */
    public function setMailSMTPAuth($mailSMTPAuth) {
        $this->mailSMTPAuth = $mailSMTPAuth;
        return $this;
    }

    /**
     * @return string
     */
    public function getMailSMTPAuth() {
        return $this->mailSMTPAuth;
    }

    /**
     * @param string $administratorEmail
     * @return \Core\System\Management\Staff\Service\StaffService
     */
    public function setAdministratorEmail($administratorEmail) {
        $this->administratorEmail = $administratorEmail;
        return $this;
    }

    /**
     * @param int $mailPort
     * @return \Core\System\Management\Staff\Service\StaffService
     */
    public function setMailPort($mailPort) {
        $this->mailPort = $mailPort;
        return $this;
    }

    /**
     * @return int
     */
    public function getMailPort() {
        return $this->mailPort;
    }

    /**
     * @param int $mailSMTPDebug
     * @return \Core\System\Management\Staff\Service\StaffService
     */
    public function setMailSMTPDebug($mailSMTPDebug) {
        $this->mailSMTPDebug = $mailSMTPDebug;
        return $this;
    }

    /**
     * @return int
     */
    public function getMailSMTPDebug() {
        return $this->mailSMTPDebug;
    }

    /**
     * @return string
     */
    public function getAdministratorEmail() {
        return $this->administratorEmail;
    }

    /**
     * @param string $systemWebsite
     * @return \Core\System\Management\Staff\Service\StaffService
     */
    public function setSystemWebsite($systemWebsite) {
        $this->systemWebsite = $systemWebsite;
        return $this;
    }

    /**
     * @return string
     */
    public function getSystemWebsite() {
        return $this->systemWebsite;
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