<?php

namespace Core\Portal\Service;

use Core\ConfigClass;
use Core\Portal\Main\StaffWebAccess\Model\StaffWebAccessModel;
use Core\shared\SharedClass;
use Core\System\Management\Staff\Model\StaffModel;

if (!isset($_SESSION)) {
    session_start();
}
// start fake document root. it's absolute path
$x = addslashes(realpath(__FILE__));
// auto detect if \\ consider come from windows else / from Linux

$pos = strpos($x, "\\");
if ($pos !== false) {
    $d = explode("\\", $x);
} else {

    $d = explode("/", $x);
}
$newPath = null;
for ($i = 0; $i < count($d); $i++) {
    // if  find the library or package then stop
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

require_once($newFakeDocumentRoot . "/library/class/classAbstract.php");
require_once($newFakeDocumentRoot . "/library/class/classShared.php");
require_once($newFakeDocumentRoot . "/v3/system/management/model/staffModel.php");
require_once($newFakeDocumentRoot . "/v3/portal/main/model/staffWebAccessModel.php");

/**
 * Class DefaultClassService
 * Default Class  For authentication,logout ,theme and language
 * @property mixed translate
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Service
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class DefaultClass extends ConfigClass {

    /**
     * Connection DatabaseObject
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;

    /**
     * Translation
     * @var string
     */
    public $t;

    /**
     * @var \Core\System\Management\Staff\Model\StaffModel
     */
    public $model;

    /**
     * @var \Core\Portal\Main\StaffWebAccess\Model\StaffWebAccessModel
     */
    public $staffWebAccess;

    /**
     * Constructor
     */
    public function __construct() {
        if (isset($_SESSION['companyId'])) {
            $this->setCompanyId($_SESSION['companyId']);
        }
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        }
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        }
    }

    /**
     * Class Loader
     */
    public function execute() {
        parent::__construct();

        $this->model = new StaffModel();
        $this->model->setVendor($this->getVendor());
        $this->model->execute();

        $this->staffWebAccess = new StaffWebAccessModel();
        $this->staffWebAccess->setVendor($this->getVendor());
        $this->staffWebAccess->execute();

        $translator = new SharedClass();
        $translator->execute();
        $this->translate = $translator->getDefaultTranslation();
        $record = $this->getInternetProtocolInformation();
        $ret = $this->getBrowserInformation();

        //browser information
		if(is_array($ret)) { 
			if(isset($ret['ua_type'])) {
				$this->staffWebAccess->setUa_type($ret['ua_type']);
			}
			$this->staffWebAccess->setUa_family($ret['ua_family']);
			$this->staffWebAccess->setUa_name($ret['ua_name']);
			$this->staffWebAccess->setUa_version($ret['ua_version']);
			$this->staffWebAccess->setUa_url($ret['ua_url']);
			$this->staffWebAccess->setUa_company($ret['ua_company']);
			$this->staffWebAccess->setUa_company_url($ret['ua_company_url']);
			$this->staffWebAccess->setUa_icon($ret['ua_icon']);
			$this->staffWebAccess->setUa_info_url($ret['ua_info_url']);
			// operating system information
			$this->staffWebAccess->setOs_family($ret['os_family']);
			$this->staffWebAccess->setOs_name($ret['os_name']);
			$this->staffWebAccess->setOs_url($ret['os_url']);
			$this->staffWebAccess->setOs_company($ret['os_company']);
			$this->staffWebAccess->setOs_company_url($ret['os_company_url']);
			$this->staffWebAccess->setOs_icon($ret['os_icon']);
		}

        // ip information
        if (isset($record)) {
            if (is_object($record)) {
                // preventing bugs on ip looping
                $this->staffWebAccess->setIp_country_code($record->country_code);
                $this->staffWebAccess->setIp_country_name($record->country_name);
                $this->staffWebAccess->setIp_region_name($record->region);
                $this->staffWebAccess->setIp_latitude($record->latitude);
                $this->staffWebAccess->setIp_longtitude($record->longtitude);
                $this->staffWebAccess->setIp_v4($this->getInternetProtocolAddress());
            }
        }
    }

    /**
     * Create
     * @see config::create()
     */
    public function create() {
        
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

    /**
     * Authentication Username and Password From Portal login
     * @access public
     * @param string $staffName Name
     * @param string $password Password
     * @throws \Exception
     */
    public function authentication($staffName, $password) {
        header('Content-Type:application/json; charset=utf-8');
        $returnArray = array();
        $start = microtime(true);
        $this->model->setStaffName($staffName);
        $this->model->setStaffPassword($password);

        if ($this->getVendor() == self::MYSQL) {
            try {
                $sql = "SET NAMES \"utf8\"";
                $this->q->fast($sql);
            } catch (\Exception $e) {
                header('Content-Type:application/json; charset=utf-8');
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        /**
         * Most Vendor don't much implement ansi 92 standard.Sql Statement Prefer Follow  Vendor Database Rule Standard.
         */
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT `staff`.`staffId`,
						`staff`.`staffName`,
						`staff`.`staffAvatar`,
						`staff`.`languageId`,
						`role`.`roleId`,
						`role`.`roleDescription`,
						`role`.`isAdmin`,
						`role`.`isCustomer`,
						`role`.`isHumanResource`,
						`role`.`isGeneralLedger`,
						`role`.`isAccountReceivable`,
						`role`.`isAccountPayable`,
						`role`.`isBranch`,
						`language`.`languageIcon`,
						`language`.`languageDescription`,
						`department`.`departmentDescription`,
						`company`.`companyId`,
						`company`.`companyDescription`,
						`staff`.`branchId`
			FROM    `staff`
			
			JOIN    `role`
			USING   (`roleId`,`companyId`)
			
			JOIN    `company`
			USING   (`companyId`)   
			
			JOIN    `department`
			USING   (`companyId`,`departmentId`)  

			JOIN    `language`
			USING   (`companyId`,`languageId`)  

			WHERE   `staff`.`staffName`	=   '" . $this->model->getStaffName() . "'
			AND     `staff`.`staffPassword`	=   '" . md5($this->model->getStaffPassword()) . "'
			AND     `staff`.`isActive`	=   1
			AND     `role`.`isActive`	=   1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
				SELECT	[staff].[staffId],
							[staff].[staffNumber],
							[staff].[staffName],
							[staff].[staffAvatar],
							[staff].[languageId],
							[role].[roleId],
							[role].[roleDescription],
							[role].[isAdmin],
							[role].[isBranch],
							[department].[departmentDescription],
							[language].[languageIcon],
							[language].[languageDescription],
							[company].[companyId],
							[company].[companyDescription],
							[staff].[branchId]
				FROM 	[staff]
				JOIN		[role]
				ON		[staff].[roleId]					=	[role].[roleId]
				AND		[staff].[companyId]			= 	[role].[companyId]
				
				JOIN		[company]
				ON		[company].[companyId]		= 	[staff].[companyId]
				
				JOIN		[department]
				ON		[staff].[departmentId]		=	[department].[departmentId]
				AND		[staff].[companyId]			= 	[department].[companyId]
				
				JOIN		[language]
				ON		[staff].[language]Id]			=	[language].[languageId]
				AND		[staff].[companyId]			= 	[language].[companyId]
				
				WHERE 	[staff].[staffName]				=	'" . $this->model->getStaffName() . "'
				AND		[staff].[staffPassword]		=	'" . md5($this->model->getStaffPassword()) . "'
				AND		[staff].[isActive]					=	1
				AND		[role].[isActive]					=	1
				AND		[department].[isActive]		=	1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
				SELECT      STAFF.COMPANYID             						AS  \"companyId\",
								STAFF.STAFFID 		        							AS  \"staffId\",
								STAFF.STAFFNAME 		    						AS  \"staffName\",
								STAFF.STAFFAVATAR 		    						AS  \"staffAvatar\",
								STAFF.LANGUAGEID 		    						AS  \"languageId\",
								ROLE.ROLEID											AS  \"roleId\",
								ROLE.ISADMIN											AS  \"isAdmin\",
								ROLE.ISBRANCH										AS  \"isBranch\",
								ROLE.ROLEDESCRIPTION							AS  \"roleDescription\",
								DEPARTMENT.DEPARTMENTID 					AS  \"departmentId\",
								DEPARTMENT.DEPARTMENTDESCRIPTION	AS  \"departmentDescription\",
								LANGUAGE.LANGUAGEICON						AS \"languageIcon\",
								LANGUAGE.LANGUAGEDESCRIPTION			AS \"languageIcon\",
								STAFF.BRANCHID										AS \"branchId\"
				FROM 	STAFF
				
				JOIN		ROLE
				ON		ROLE.ROLEID					=   STAFF.ROLEID
				AND     ROLE.COMPANYID      		=   STAFF.COMPANYID

				JOIN		COMPANY
				ON		COMPANY.COMPANYID		=   STAFF.COMPANYID

				JOIN    DEPARTMENT
				ON      STAFF.THEMEID					= THEME.DEPARTMENTID
				AND    STAFF.COMPANYID      		=   THEME.COMPANYID
				
				JOIN    LANGUAGE
				ON      STAFF.LANGUAGEID   			= LANGUAGE.LANGUAGEID
				AND    STAFF.COMPANYID      		=   LANGUAGE.COMPANYID
			
				WHERE   STAFF.STAFFNAME			=   '" . $this->model->getStaffName() . "'
				AND		STAFF.STAFFPASSWORD	=   '" . md5($this->model->getStaffPassword()) . "'
				AND		STAFF.ISACTIVE				=  1
				AND		ROLE.ISACTIVE 				=  1";
        } else {
            echo json_encode(
                    array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'], "sql2" => $sql)
            );
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
        if (intval($this->q->numberRows($result, $sql)) > 0) {

            $row = $this->q->fetchAssoc($result);

            $_SESSION ['staffId'] = $row ['staffId'];
//            $_SESSION ['staffNumber'] = $row ['staffNumber'];
            $_SESSION ['staffName'] = $row ['staffName'];
            $_SESSION['staffAvatar'] = $row['staffAvatar'];

            $_SESSION ['roleId'] = $row ['roleId'];
            $_SESSION ['roleDescription'] = $row ['roleDescription'];

            $_SESSION ['isAdmin'] = $row ['isAdmin'];

            // language
            $_SESSION ['languageId'] = $row ['languageId'];
            $_SESSION ['languageIcon'] = $row ['languageIcon'];
            $_SESSION ['languageDescription'] = $row ['languageDescription'];

            //special session . to filter the spotlight and others
            $_SESSION ['isCustomer'] = $row['isCustomer'];
            $_SESSION ['isHumanResource'] = $row['isHumanResource'];
            $_SESSION ['isGeneralLedger'] = $row['isGeneralLedger'];
            $_SESSION ['isAccountReceivable'] = $row['isAccountReceivable'];
            $_SESSION ['isAccountPayable'] = $row['isAccountPayable'];
            $_SESSION ['isBranch'] = $row['isBranch'];
            // end special session
            $_SESSION ['branchId'] = $row['branchId'];

            $_SESSION ['companyId'] = $row['companyId'];
           //$_SESSION ['database'] = $_POST ['database'];
            // $_SESSION ['vendor'] = $_POST ['vendor'];
            $this->setCompanyId($_SESSION['companyId']);
            $this->setStaffId($_SESSION['staffId']);
            $this->staffWebAccess->setStaffId($_SESSION ['staffId']);


            // audit Log Time In
            $sql = null;
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
                INSERT INTO `staffwebaccess`
                (
                    `companyId`,
                    `staffId`,
                    `staffWebAccessLogIn`,
                    `phpSession`,
                    `ua_type`,
                    `ua_family`,
                    `ua_name`,
                    `ua_version`,
                    `ua_url`,
                    `ua_company`,
                    `ua_company_url`,
                    `ua_icon`,
                    `ua_info_url`,
                    `os_family`,
                    `os_name`,
                    `os_url`,
                    `os_company`,
                    `os_company_url`,
                    `os_icon`,
                    `ip_v4`,
                    `ip_v6`,
                    `ip_country_code`,
                    `ip_country_name`,
                    `ip_region_name`,
                    `ip_latitude`,
                    `ip_longtitude`
                )VALUES (
                    '" . $this->getCompanyId() . "',
                    '" . $this->staffWebAccess->getStaffId() . "',
                    " . $this->staffWebAccess->getStaffWebAccessLogIn() . ",
                    '" . $this->staffWebAccess->getPhpSession() . "',
                     '" . $this->strict($this->staffWebAccess->getUa_Type(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_family(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_name(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_version(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_url(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_company(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_company_url(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_icon(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_info_url(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getOs_family(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getOs_name(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getOs_url(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getOs_company(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getOs_company_url(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getOs_icon(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getIp_v4(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getIp_v6(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getIp_country_code(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getIp_country_name(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getIp_region_name(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getIp_latitude(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getIp_longtitude(), 'string') . "'
                )";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                INSERT INTO [staffwebaccess]
                (
                    [companyId],
                    [staffId],
                    [staffWebAccessLogIn],
                    [phpSession],
                    [ua_type],
                    [ua_family],
                    [ua_name],
                    [ua_version],
                    [ua_url],
                    [ua_company],
                    [ua_company_url],
                    [ua_icon],
                    [ua_info_url],
                    [os_family],
                    [os_name],
                    [os_url],
                    [os_company],
                    [os_company_url],
                    [os_icon],
                    [ip_v4],
                    [ip_v6],
                    [ip_country_code],
                    [ip_country_name],
                    [ip_region_name],
                    [ip_latitude],
                    [ip_longtitude]
                )VALUES (
                    '" . $this->getCompanyId() . "',
                    '" . $this->staffWebAccess->getStaffId() . "',
                    " . $this->staffWebAccess->getStaffWebAccessLogIn() . ",
                    '" . $this->staffWebAccess->getPhpSession() . "',
                    '" . $this->strict($this->staffWebAccess->getUa_Type(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_family(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_name(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_version(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_url(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_company(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_company_url(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_icon(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_info_url(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getOs_family(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getOs_name(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getOs_url(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getOs_company(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getOs_company_url(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getOs_icon(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getIp_v4(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getIp_v6(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getIp_country_code(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getIp_country_name(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getIp_region_name(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getIp_latitude(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getIp_longtitude(), 'string') . "'
                )";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
		INSERT INTO STAFFWEBACCESS
		(
                    COMPANYID,
                    STAFFID,
                    STAFFWEBACCESSLOGIN,
                    PHPSESSION,
                    UA_TYPE,
                    UA_FAMILY,
                    UA_NAME,
                    UA_VERSION,
                    UA_URL,
                    UA_COMPANY,
                    UA_COMPANY_URL,
                    UA_ICON,
                    UA_INFO_URL,
                    OS_FAMILY,
                    OS_NAME,
                    OS_URL,
                    OS_COMPANY,
                    OS_COMPANY_URL,
                    OS_ICON,
                    IP_V4,
                    IP_V6,
                    IP_COUNTRY_CODE,
                    IP_COUNTRY_NAME,
                    IP_REGION_NAME,
                    IP_LATITUDE,
                    IP_Longtitude
		)VALUES (
                    '" . $this->getCompanyId() . "',
                    '" . intval($this->staffWebAccess->getStaffId()) . "',
                    " . $this->staffWebAccess->getStaffWebAccessLogIn() . ",
                    '" . $this->staffWebAccess->getPhpSession() . "',
                    '" . $this->strict($this->staffWebAccess->getUa_Type(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_family(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_name(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_version(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_url(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_company(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_company_url(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_icon(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getUa_info_url(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getOs_family(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getOs_name(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getOs_url(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getOs_company(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getOs_company_url(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getOs_icon(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getIp_v4(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getIp_v6(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getIp_country_code(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getIp_country_name(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getIp_region_name(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getIp_latitude(), 'string') . "',
                    '" . $this->strict($this->staffWebAccess->getIp_longtitude(), 'string') . "'
		)";
            }

            try {
                $this->q->create($sql);
            } catch (\Exception $e) {
                header('Content-Type:application/json; charset=utf-8');
                $this->q->rollback();
                echo json_encode(array("success" => false, "aa" => true, "message" => $e->getMessage()));
                exit();
            }
            $returnArray['success'] = true;
            $returnArray['message'] = $this->translate['accessGrantedTextLabel'];
            $returnArray['start'] = $start;
            $returnArray['staffName'] = $_SESSION ['staffName'];
            $returnArray['staffImage'] = '';
            echo json_encode($returnArray);
            exit();
        } else {
            $returnArray['success'] = false;
            $returnArray['message'] = $this->translate['accessDeniedTextLabel'];
            $returnArray['start'] = $start;
            $returnArray['password'] = $this->model->getStaffPassword();
            $returnArray['sql'] = $sql;
            echo json_encode($returnArray);
            exit();
        }
    }

    /**
     * Set Logout time
     */
    public function setLogout() {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT *
            FROM    `staffwebaccess`
            WHERE   `phpSession`='" . $this->staffWebAccess->getPhpSession() . "'";
        } elseif ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT *
            FROM    [staffWebAccess]
            WHERE   [phpSession]='" . $this->staffWebAccess->getPhpSession() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT *
            FROM    STAFFWEBACCESS
            WHERE   PHPSESSION='" . $this->staffWebAccess->getPhpSession() . "'";
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
            if ($this->q->numberRows($result, $sql) > 0) {
                // update session logout
                if ($this->getVendor() == self::MYSQL) {
                    $sql = "
                    UPDATE  `staffwebaccess`
                    SET     `staffWebAccessLogOut`=" . $this->staffWebAccess->getStaffWebAccessLogOut() . "
                    WHERE   `phpSession`='" . $this->staffWebAccess->getPhpSession() . "'";
                } else if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                    UPDATE  [staffWebAccess]
                    SET     [staffWebAccessLogOut]=" . $this->staffWebAccess->getStaffWebAccessLogOut() . "
                    WHERE   [phpSession]='" . $this->staffWebAccess->getPhpSession() . "'";
                } else if ($this->getVendor() == self::ORACLE) {
                    $sql = "
                    UPDATE  STAFFWEBACCESS
                    SET     STAFFWEBACCESSLOGOUT=" . $this->staffWebAccess->getStaffWebAccessLogOut() . "
                    WHERE   PHPSESSION='" . $this->staffWebAccess->getPhpSession() . "'";
                }
                try {
                    $this->q->fast($sql);
                } catch (\Exception $e) {
                    header('Content-Type:application/json; charset=utf-8');
                    $this->q->rollback();
                    echo json_encode(array("success" => false, "message" => $e->getMessage()));
                    exit();
                }
            }
        }
        session_unset();
        session_destroy();
        $_SESSION = array();
        session_start();
    }

    /**
     * Set New Language For Staff / User
     * @param int $languageId Language Primary Key
     * @throws \Exception
     * @return void
     */
    public function setChangeLanguage($languageId) {
        header('Content-Type:application/json; charset=utf-8');
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE	`staff`
			SET			`languageId` =	'" . $languageId . "'
            WHERE		`staffId`   	= '" . $this->getStaffId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE	[staff]
			SET			[languageId]	=	'" . $languageId . "'
            WHERE		[staffId]   		= '" . $this->getStaffId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE	STAFF
			SET			LANGUAGEID	=	'" . $languageId . "'
            WHERE		STAFFID   		= '" . $this->getStaffId() . "'";
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
            SELECT  *
            FROM    `language`
            WHERE   `isImportant` = 1
            AND     `companyId`   = '" . $this->getCompanyId() . "'
			AND		`languageId` = '" . $languageId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT  *
			FROM    [language]
			WHERE   [isImportant] = 1
			AND     [companyId] = '" . $this->getCompanyId() . "'
			AND		[languageId] = '" . $languageId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT	LANGUAGEID as \"languageId\",
						LANGUAGEICON as \"languageIcon\",
						LANGUAGEDESCRIPTION as \"languageDescription\"
			FROM    LANGUAGE
			WHERE	ISIMPORTANT = 1
			AND		COMPANYID = '" . $this->getCompanyId() . "'
			AND		LANGUAGEID = '" . $languageId . "'";
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
            if ($this->q->numberRows($result, $sql) > 0) {
                $row = $this->q->fetchArray($result);
            }
        }
        if (!empty($row)) {
            $_SESSION['languageId'] = $row['languageId'];
            $_SESSION['languageIcon'] = $row['languageIcon'];
            $_SESSION['languageDescription'] = $row['languageDescription'];
            echo json_encode(
                    array("success" => true, "session'" => $row, "message" => "update language completo", "sql" => $sql)
            );
            exit();
        }
    }

    /**
     * Return Language Array
     * @return array
     * @throws \Exception
     */
    public function getLanguage() {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }

        $data = array();
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  *
            FROM    `language`
            WHERE   `isImportant` = 1
            AND     `companyId`   = '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT  *
			FROM    [language]
			WHERE   [isImportant] = 1
			AND     [companyId] = '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT	LANGUAGEID as \"languageId\",
						LANGUAGEICON as \"languageIcon\",
						LANGUAGEDESCRIPTION as \"languageDescription\"
			FROM    LANGUAGE
			WHERE	ISIMPORTANT = 1
			AND		COMPANYID = '" . $this->getCompanyId() . "'";
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
            if ($this->q->numberRows($result, $sql) > 0) {
                while (($row = $this->q->fetchArray($result)) == true) {
                    $data[] = $row;
                }
            }
        }

        return $data;
    }

    /**
     * Return Theme Array
     * @return array
     * @throws \Exception
     */
    public function getTheme() {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }

        $sql = null;
        $data = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        SELECT  * 
        FROM    `theme` 
        WHERE   `isActive` = 1
        AND     `companyId`='" . $this->getCompanyId() . "'";
        } elseif ($this->getVendor() == self::MSSQL) {
            $sql = "
        SELECT  * 
        FROM    [theme] 
        WHERE   [isActive] = 1
         AND  [companyId]='" . $this->getCompanyId() . "'";
        } elseif ($this->getVendor() == self::ORACLE) {
            $sql = "
        SELECT  * 
        FROM    THEME 
        WHERE   ISACTIVE = 1
         AND    COMPANYID = '" . $this->getCompanyId() . "'";
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
            if ($this->q->numberRows($result, $sql) > 0) {
                while (($row = $this->q->fetchArray($result)) == true) {
                    $data[] = $row;
                }
            }
        }

        return $data;
    }

    /**
     * Set New Theme Session
     * @param string $theme theme
     * @throws \Exception
     * @return void
     */
    public function setTheme($theme) {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE `staff`
            SET    `themeId` = (
                                    SELECT  `themeId` 
                                    FROM    `theme`
                                    WHERE   `themePath`='" . $theme . "'
                                )
            WHERE   `staffId`='" . $this->getStaffId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE [staff]
            SET    [themeId] = (
                                    SELECT  TOP 1 [themeId]
                                    FROM    [theme]
                                    WHERE   [themePath]='" . $theme . "'
                                )
            WHERE   [staffId]='" . $this->getStaffId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE  STAFF
            SET     THEMEID = (
                                    SELECT  THEMEID
                                    FROM    THEME
                                    WHERE   THEMEPATH='" . $theme . "'
                                    LIMIT   1
                                )
            WHERE   STAFFID='" . $this->getStaffId() . "'";
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
     * Set New Language Session
     * @param int $languageId language Primary Key
     * @throws \Exception
     * @return void
     */
    public function setLanguage($languageId) {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE `staff`
            SET    `languageId` = '" . $this->strict($languageId, 'numeric') . "'
            WHERE   `staffId`='" . $_SESSION['staffId'] . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE [staff]
            SET    [languageId] = '" . $this->strict($languageId, 'numeric') . "'
            WHERE   [staffId]='" . $_SESSION['staffId'] . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE  STAFF
            SET     LANGUAGEID = '" . $this->strict($languageId, 'numeric') . "'
            WHERE   STAFFID='" . $_SESSION['staffId'] . "'";
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
 * Class MenuNavigationClass
 * Menu navigation and routing
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Service
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class MenuNavigationClass extends ConfigClass {

    /**
     * Connection DatabaseObject
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;

    /**
     * System Numbering format
     * var mixed
     */
    public $systemFormatArray;

    /**
     * @var string
     */
    public $model;

    /**
     * Application Primary Key
     * @var int
     */
    public $applicationId;

    /**
     * Module Primary Key
     * @var int
     */
    public $moduleId;

    /**
     * Folder Primary Key
     * @var int
     */
    public $folderId;

    /**
     * Leaf Primary Key
     * @var int
     */
    public $leafId;

    /**
     * Reference Table Name
     * @var string
     */
    private $referenceTableName;

    /**
     * Reference Table Name Primary Key / Sequence
     * @var int
     */
    private $tableNameId;

    /**
     * Primary Key Name
     * @var string
     */
    private $chartOfAccountCategoryCode;

    /**
     * Primary Key Name
     * @var string
     */
    private $chartOfAccountTypeCode;

    /**
     * Finance Year
     * @var int
     */
    private $financeYearId;

    /**
     * Portal Title
     * @var string
     */
    private $portalTitle;

    /**
     * Leaf Name
     * @var string
     */
    private $leafName;

    /**
     * Constructor
     */
    function __construct() {
        // default for portal visitor
        $this->translate = array();
        $this->systemFormatArray = array();
        if (isset($_SESSION['companyId'])) {
            $this->setRoleId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        } else {
            $this->setRoleId(7);
        }
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            $this->setStaffId(9);
        }
        if (isset($_SESSION['languageId'])) {
            $this->setLanguageId($_SESSION['languageId']);
        } else {
            $this->setLanguageId(21);
        }
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();

        $this->model = new StaffModel();
        $this->model->setVendor($this->getVendor());
        $this->model->execute();

        $translator = new SharedClass();
        $translator->execute();
        $this->translate = $translator->getDefaultTranslation(); // short because code too long

        if (isset($_SESSION['companyId'])) {
            $this->setRoleId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        } else {
            $this->setRoleId(7);
        }
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            $this->setStaffId(9);
        }
        if (isset($_SESSION['languageId'])) {
            $this->setLanguageId($_SESSION['languageId']);
        } else {
            $this->setLanguageId(21);
        }
        $this->getOverrideCountry();
    }

    /**
     * Get Default Company Country
     */
    public function getOverrideCountry() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT `financesetting`.`financeYearId`
            FROM   `financesetting`
            WHERE  `financesetting`.`companyId` ='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT [financeSetting].[financeYearId]
            FROM   [financeSetting]
            WHERE  [financeSetting].[companyId] ='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT FINANCESETTING.FINANCEYEARID AS \"financeYearId\"
            FROM   FINANCESETTING
            WHERE  FINANCESETTING.COMPANYID ='" . $this->getCompanyId() . "'";
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
            $this->setFinanceYearId($row['financeYearId']);
        }
    }

    /**
     * Create
     * @see config::read()
     */
    public function create() {
        
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
     * @return void
     */
    public function excel() {
        
    }

    /**
     * Return Application Primary Key From Code
     * @param string $applicationCode
     * @return null|string $applicationId Application Primary Key
     * @throws \Exception
     */
    function getApplicationIdFromCode($applicationCode) {
        $applicationId = null;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT `applicationId`
			FROM   `application`
			WHERE  `companyId`			=	'" . $this->getCompanyId() . "'
			AND	   `applicationCode`	=	'" . $applicationCode . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT [applicationId]
			FROM   [application]
			WHERE  [companyId]			=	'" . $this->getCompanyId() . "'
			AND	   [applicationCode]	=	'" . $applicationCode . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT APPLICATIONID
			FROM   APPLICATION
			WHERE  COMPANYID			=	'" . $this->getCompanyId() . "'
			AND	   APPLICATIONCODE		=	'" . $applicationCode . "'";
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
            $applicationId = $row['applicationId'];
        }
        return $applicationId;
    }

    /**
     * Return Module Primary Key From Code
     * @param string $moduleCode Code
     * @return null|int $moduleId Module Primary Key
     * @throws \Exception
     */
    function getModuleIdFromCode($moduleCode) {
        $moduleId = null;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT `moduleId`
			FROM   `module`
			WHERE  `companyId`			=	'" . $this->getCompanyId() . "'
			AND	   `moduleCode`			=	'" . $moduleCode . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
			SELECT [moduleId]
			FROM   [module]
			WHERE  [companyId]			=	'" . $this->getCompanyId() . "'
			AND	   [moduleCode]			=	'" . $moduleCode . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
			SELECT MODULEID
			FROM   MODULE
			WHERE  COMPANYID			=	'" . $this->getCompanyId() . "'
			AND	   MODULECODE			=	'" . $moduleCode . "'";
                }
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
            $row = $this->q->fetchArray($result);
            $moduleId = $row['moduleId'];
        }
        return $moduleId;
    }

    /**
     * Return Folder Primary Key From Code
     * @param string $folderCode Code
     * @return null|int $folderId Folder Primary Key
     * @throws \Exception
     */
    function getFolderIdFromCode($folderCode) {
        $folderId = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT `folderId`
			FROM   `folder`
			WHERE  `companyId`			=	'" . $this->getCompanyId() . "'
			AND	   `folderCode`			=	'" . $folderCode . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
			SELECT [folderId]
			FROM   [folder]
			WHERE  [companyId]			=	'" . $this->getCompanyId() . "'
			AND	   [folderCode]			=	'" . $folderCode . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
			SELECT FOLDERID
			FROM   FOLDER
			WHERE  COMPANYID			=	'" . $this->getCompanyId() . "'
			AND	   FOLDERCODE			=	'" . $folderCode . "'";
                }
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
            $row = $this->q->fetchArray($result);
            $folderId = $row['folderId'];
        }
        return $folderId;
    }

    /**
     * Return Leaf Primary Key From Code
     * @param string $leafCode Code
     * @return null|int $leafId Leaf Primary Key
     * @throws \Exception
     */
    function getLeafIdFromCode($leafCode) {
        $leafId = null;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT `leafId`
			FROM   `leaf`
			WHERE  `companyId`			=	'" . $this->getCompanyId() . "'
			AND	   `leafCode`			=	'" . $leafCode . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
			SELECT [leafId]
			FROM   [leaf]
			WHERE  [companyId]			=	'" . $this->getCompanyId() . "'
			AND	   [leafCode]			=	'" . $leafCode . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
			SELECT LEAFID
			FROM   LEAF
			WHERE  COMPANYID			=	'" . $this->getCompanyId() . "'
			AND	   LEAFCODE				=	'" . $leafCode . "'";
                }
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
            $row = $this->q->fetchArray($result);
            $leafId = $row['leafId'];
        }
        return $leafId;
    }

    /**
     * Reroute application.Warning It cannot detect PHP error file when included. Even though can be done but quite dangerous php gain access to file system.
     * @param int $pageId Application Primary Key or Lead Primary Key
     * @param mixed $pageType ('app','module','folder','leaf') type Application Or Module Or Folder Or Leaf
     * @throws \Exception
     */
    function route($pageId, $pageType) {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        // find xxxPosting if true then find xxxHistory.. Applicable for posting future only
        if ($this->getLeafName() && strlen(trim($this->getLeafName())) > 0) {
            $pos = strpos($this->getLeafName(), 'Post');
            if ($pos !== false) {
                $pageId = $this->getHistoryLeafId($this->getReferenceTableName());
            }
        } else {
            
        }
        $sql = null;
        $appendFile = null;
        $error = 0;
        switch ($pageType) {
            case 'application':
                $_POST['applicationId'] = $pageId;
                $_GET['applicationId'] = $pageId;
                if ($this->getVendor() == self::MYSQL) {

                    $sql = "
                    SELECT  `applicationFilename` as `filename`
                    FROM    `application`
                    JOIN    `company`
                    USING   (`companyId`)
                    JOIN    `applicationaccess`
                    USING   (`applicationId`,`companyId`)
                    WHERE   `applicationaccess`.`roleId`    =   '" . $this->getRoleId() . "'
                    AND     `application`.`applicationId`   =   '" . $pageId . "'
                    AND     `application`.`isActive`        =    1
                    AND     `application`.`companyId`       =   '" . $this->getCompanyId() . "'";
                } else if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                    SELECT  [applicationFilename] as [filename]
                    FROM    [application]

                    JOIN    [company]
                    ON      [application].[companyId]       =   [company].[companyId]

                    JOIN    [applicationaccess]
                    ON      [application].[companyId]       =  [applicationaccess].[companyId]
                    AND     [application].[applicationId]   =  [applicationaccess].[applicationId]

                    WHERE   [applicationaccess].[roleId]    =   '" . $this->getRoleId() . "'
                    AND     [application].[applicationId]   =   '" . $pageId . "'
                    AND     [application].[isActive]        =    1
                    AND     [application].[companyId]       =   '" . $this->getCompanyId() . "'";
                } else if ($this->getVendor() == self::ORACLE) {
                    $sql = "
                    SELECT  APPLICATIONFILENAME AS \"filename\"
                    FROM    APPLICATION

                    JOIN    COMPANY
                    ON      APPLICATION.COMPANYID       =   COMPANY.COMPANYID

                    JOIN    APPLICATIONACCESS
                    ON      APPLICATION.COMPANYID       =   APPLICATIONACCESS.COMPANYID
                    AND     APPLICATION.APPLICATIONID   =   APPLICATIONACCESS.APPLICATIONID

                    WHERE   APPLICATIONACCESS.ROLEID    =   '" . $this->getRoleId() . "'
                    AND     APPLICATION.APPLICATIONID   =   '" . $pageId . "'
                    AND     APPLICATION.ISACTIVE        =   1
                    AND     APPLICATION.COMPANYID       =   '" . $this->getCompanyId() . "'";
                }
                break;
            case 'module':
                $_POST['moduleId'] = $pageId;
                if ($this->getVendor() == self::MYSQL) {
                    $sql = "
                    SELECT  concat(`module`.`modulePath`,`module`.`moduleFilename`) as `filename`,
                            `moduleFilename` as `leafFilename`
                    FROM    `module`
                    JOIN    `moduleaccess`
                    USING   (`moduleId`)
                    WHERE   `moduleaccess`.`roleId` =   '" . $this->getRoleId() . "'
                    AND     `module`.`moduleId`     =   '" . $pageId . "'
                    AND     `module`.`isActive`     =    1
                    AND     `module`.`companyId`    =   '" . $this->getCompanyId() . "'";
                } else if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                    SELECT  concat([module].[modulePath],
                                    [module].[moduleFilename]) as [filename],
                            [moduleFilename] as [leafFilename]
                    FROM    [module]
                    JOIN    [moduleAccess]
                    ON      [module].[companyId]    =   [moduleAccess].[companyId]
                    AND     [module].[moduleId]     =   [moduleAccess].[moduleId]
                    WHERE   [moduleAccess].[roleId] =   '" . $this->getRoleId() . "'
                    AND     [module].[moduleId]     =   '" . $pageId . "'
                    AND     [module].[isActive]     =    1
                    AND     [module].[companyId]    = '" . $this->getCompanyId() . "'";
                } else if ($this->getVendor() == self::ORACLE) {
                    $sql = "
                    SELECT  CONCAT(MODULE.MODULEPATH,MODULE.MODULEFILENAME) AS \"filename\",
                            MODULEFILENAME AS   \"leafFilename\"
                    FROM    MODULE
                    JOIN    COMPANY
                    ON      MODULE.COMPANYID    =   COMPANY.COMPANYID

                    JOIN    MODULEACCESS
                    ON      MODULE.COMPANYID    =   MODULEACCESS.COMPANYID
                    AND     MODULE.MODULEID     =   MODULEACCESS.MODULEID

                    WHERE   MODULEACCESS.ROLEID =   '" . $this->getRoleId() . "'
                    AND     MODULE.MODULEID     =   '" . $pageId . "'
                    AND     MODULE.ISACTIVE     =    1
                    AND     MODULE.COMPANYID    = '" . $this->getCompanyId() . "'";
                }
                break;

            case 'folder':
                $_POST['folderId'] = $pageId;
                $_GET['folderId'] = $pageId;
                if ($this->getVendor() == self::MYSQL) {
                    $sql = "
                    SELECT  folderFilename` as `filename`
                    FROM    `folder`
                    JOIN    `folderaccess`
                    USING   (`companyId`,`folderId`)
                    WHERE   `folderaccess`.`roleId` =   '" . $this->getRoleId() . "'
                    AND     `folder`.`folderId`     =   '" . $pageId . "'
                    AND     `folder`.`isActive`     =    1
                    AND     `folder`.`companyId`    =   '" . $this->getCompanyId() . "'";
                } else if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                    SELECT  [folderFilename] as [filename]
                    FROM    [folder]
                    JOIN    [folderaccess]
                    ON      [application].[companyId]       =   [applicationaccess].[companyId]
                    AND     [application].[applicationId]   =   [applicationaccess].[applicationId]
                    WHERE   [folderaccess].[roleId]         =   '" . $this->getRoleId() . "'
                    AND     [folder].[folderId]             =   '" . $pageId . "'
                    AND     [folder].[isActive]             =    1
                    AND     [folder].[companyId]            =   '" . $this->getCompanyId() . "'";
                } else if ($this->getVendor() == self::ORACLE) {
                    $sql = "
                    SELECT  FOLDERFILENAME AS FILENAME
                    FROM    FOLDER
                    JOIN    COMPANY
                    ON      FOLDER.COMPANYID        =   COMPANY.COMPANYID
                    JOIN    FOLDERACCESS
                    ON      FOLDER.COMPANYID        =   FOLDERACCESS.COMPANYID
                    AND     FOLDER.FOLDERID         =   FOLDERACCESS.FOLDERID
                    WHERE   FOLDERACCESS.ROLEID     =   '" . $this->getRoleId() . "'
                    AND     FOLDER.FOLDERID         =   '" . $pageId . "'
                    AND     FOLDER.ISACTIVE         =    1
                    AND     FOLDER.COMPANYID        =   '" . $this->getCompanyId() . "'";
                }
                break;
            case 'leaf':
                $_POST['leafId'] = $pageId;
                $_GET['leafId'] = $pageId;
                if ($this->getVendor() == self::MYSQL) {
                    $sql = "
                    SELECT  concat(`folder`.`folderPath`,`leaf`.`leafFilename`) as `filename`,
                            `leafFilename`
                    FROM    `leaf`
                    JOIN    `leafaccess`
                    USING   (`companyId`,`leafId`)
                    JOIN    `folder`
                    USING   (`companyId`,`folderId`)
                    WHERE   `leaf`.`companyId`      =    '" . $this->getCompanyId() . "'
                    AND     `leafaccess`.`staffId`  =    '" . $this->getStaffId() . "'
                    AND     `leaf`.`leafId`         =    '" . $pageId . "'
                    AND     `leaf`.`isActive`       =    1
                    AND     `folder`.`isActive`     =    1 ";
                } else if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                    SELECT  CONCAT([folder].[folderPath],
                            [leaf].[leafFilename]) as [filename],
                            [leaf].[leafFilename]
                    FROM    [leaf]

                    JOIN    [leafAccess]
                    ON      [leaf].[companyId]      =   [leafAccess].[companyId]
                    AND     [leaf].[leafId]         =   [leafAccess].[leafId]

                    JOIN    [folder]
                    ON      [leaf].[companyId]      =   [folder].[companyId]
                    AND     [leaf].[folderId]       =   [folder].[folderId]

                    WHERE   [leaf].[companyId]      =    '" . $this->getCompanyId() . "'
                    AND     [leafAccess].[staffId]  =    '" . $this->getStaffId() . "'
                    AND     [leaf].[leafId]         =    '" . $pageId . "'
                    AND     [leaf].[isActive]       =    1
                    AND     [folder].[isActive]     =    1   ";
                } else if ($this->getVendor() == self::ORACLE) {
                    $sql = "
                    SELECT  CONCAT(FOLDER.FOLDERPATH,LEAF.LEAFFILENAME) AS \"filename\",
                            LEAF.LEAFFILENAME AS \"leafFilename\"
                    FROM    LEAF
                    JOIN    COMPANY
                    ON      LEAF.COMPANYID      =   COMPANY.COMPANYID

                    JOIN    LEAFACCESS
                    ON      LEAF.COMPANYID      =   LEAFACCESS.COMPANYID
                    AND     LEAF.LEAFID         =   LEAFACCESS.LEAFID

                    JOIN    FOLDER
                    ON      LEAF.COMPANYID      =   FOLDER.COMPANYID
                    AND     LEAF.FOLDERID       =   FOLDER.FOLDERID

                    WHERE   LEAFACCESS.STAFFID  =   '" . $this->getStaffId() . "'
                    AND     LEAF.LEAFID         =   '" . $pageId . "'
                    AND     LEAF.ISACTIVE       =   1
                    AND     FOLDER.ISACTIVE     =   1
                    AND     LEAF.COMPANYID      =   '" . $this->getCompanyId() . "'";
                }
                break;
            default:
                $error = 1;
        }
        if ($error == 1) {
            $errorImage = "./images/icons/smiley-eek.png";
            $this->exceptionMessage(" <img src=\"" . $errorImage . "\">Undefined Menu Type");
            return;
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
            $appendFile = $row['filename'];
            $leafFilename = $row['leafFilename'];
            // check if path exist file or not.
            if (is_file($this->getFakeDocumentRoot() . $this->getVersionCompability() . "/" . $appendFile)) {
                // if extra value treat as get figure
                if ($this->getTableNameId() && strlen($this->getTableNameId()) > 0) {
                    $_POST[$this->getReferenceTableName() . "Id"] = $this->getTableNameId();
                }
                if ($this->getChartOfAccountCategoryCode() && strlen($this->getChartOfAccountCategoryCode()) > 0) {
                    $_POST['chartOfAccountCategoryCode'] = $this->getChartOfAccountCategoryCode();
                }
                if ($this->getChartOfAccountTypeCode() && strlen($this->getChartOfAccountTypeCode()) > 0) {
                    $_POST['chartOfAccountTypeCode'] = $this->getChartOfAccountTypeCode();
                }
                // test  file is got word parse error.if non  then include.Quite slowish but wokay..assume if transfer file low then 100.sure unlogical mah.
                if (strpos(
                                file_get_contents(
                                        $this->getFakeDocumentRoot() . $this->getVersionCompability() . "/" . $appendFile
                                ), 'Parse error'
                        ) !== false || strlen(
                                file_get_contents(
                                        $this->getFakeDocumentRoot() . $this->getVersionCompability() . "/" . $appendFile
                                )
                        ) === 0 || strlen(
                                file_get_contents(
                                        $this->getFakeDocumentRoot() . $this->getVersionCompability() . "/" . $appendFile
                                )
                        ) < 100
                ) {
                    if ($_SESSION['isAdmin'] == 1) {
                        // pull out the contain error
                        $message = "
						<div id=\"content\" style=\"opacity: 1;\">
							<div class=\"row\">
								<div class=\"col-xs-12 col-sm-12 col-md-12 col-lg-12\">
									<div class=\"row\">
										<div class=\"col-sm-12\">
											<div class=\"text-center error-box\">
												<h1 class=\"error-text tada animated\">
													<i class=\"fa fa-times-circle text-danger error-icon-shadow\"></i>
													Error 500
												</h1>
												<h2 class=\"font-xl\">
													<strong>Oooops, Something went wrong!<br>Filename : " . $this->getFakeDocumentRoot(
                                ) . $this->getVersionCompability() . "/" . $appendFile . "</strong>
												</h2></div>
										</div>
									 </div>
								</div>
							</div>
						</div>";
                    } else {
                        // just put saying error.system wil mail message
                        $message = "
						<div id=\"content\" style=\"opacity: 1;\">
							<div class=\"row\">
								<div class=\"col-xs-12 col-sm-12 col-md-12 col-lg-12\">
									<div class=\"row\">
										<div class=\"col-sm-12\">
											<div class=\"text-center error-box\">
												<h1 class=\"error-text tada animated\">
													<i class=\"fa fa-times-circle text-danger error-icon-shadow\"></i>
													Error 500
												</h1>
												<h2 class=\"font-xl\">
													<strong>Oooops, Something went wrong! Contact administrator / IT Personel </strong>
												</h2>
											</div>
										</div>
									 </div>
								</div>
							</div>
						</div>";
                    }
                    $this->exceptionMessage($message);
                    $this->setNotification(3, $message, 2, 'NOW()');
                } else {
                    require_once($this->getFakeDocumentRoot() . $this->getVersionCompability() . "/" . $appendFile);
                }
            } else {
                if (isset($_SESSION['staffName'])) {
                    if ($_SESSION['isAdmin'] == 1) {
                        $message = " <table><tr><td><b>Path</b></td><td>:</td><td> " . $this->getFakeDocumentRoot(
                                ) . $this->getVersionCompability(
                                ) . "/" . $appendFile . "</td></tr><tr><td> <b>Filename</b></td><td>:</td><td> [" . $leafFilename . "] does not exist</td></tr></table>";
                        $this->exceptionMessage($message);
                        $this->setNotification(3, $message, 2, 'NOW()');
                    } else {
                        // here we can override to give other message  for non admin..
                        $message = "
						<style>
							.error-text-2 {
								text-align: center;
								font-size: 700%;
								font-weight: bold;
								font-weight: 100;
								color: #333;
								line-height: 1;
								letter-spacing: -.05em;
								background-image: -webkit-linear-gradient(92deg,#333,#ed1c24);
								-webkit-text-fill-color: transparent;
							}
							.particle {
								position: absolute;
								top: 50%;
								left: 50%;
								width: 1rem;
								height: 1rem;
								border-radius: 100%;
								background-color: #ed1c24;
								background-image: -webkit-linear-gradient(rgba(0,0,0,0),rgba(0,0,0,.3) 75%,rgba(0,0,0,0));
								box-shadow: inset 0 0 1px 1px rgba(0,0,0,.25);
							}
							.particle--a {
								-webkit-animation: particle-a 1.4s infinite linear;
								-moz-animation: particle-a 1.4s infinite linear;
								-o-animation: particle-a 1.4s infinite linear;
								animation: particle-a 1.4s infinite linear;
							}
							.particle--b {
								-webkit-animation: particle-b 1.3s infinite linear;
								-moz-animation: particle-b 1.3s infinite linear;
								-o-animation: particle-b 1.3s infinite linear;
								animation: particle-b 1.3s infinite linear;
								background-color: #00A300;
							}
							.particle--c {
								-webkit-animation: particle-c 1.5s infinite linear;
								-moz-animation: particle-c 1.5s infinite linear;
								-o-animation: particle-c 1.5s infinite linear;
								animation: particle-c 1.5s infinite linear;
								background-color: #57889C;
							}@-webkit-keyframes particle-a {
							0% {
							-webkit-transform: translate3D(-3rem,-3rem,0);
							z-index: 1;
							-webkit-animation-timing-function: ease-in-out;
							} 25% {
							width: 1.5rem;
							height: 1.5rem;
							}

							50% {
							-webkit-transform: translate3D(4rem, 3rem, 0);
							opacity: 1;
							z-index: 1;
							-webkit-animation-timing-function: ease-in-out;
							}

							55% {
							z-index: -1;
							}

							75% {
							width: .75rem;
							height: .75rem;
							opacity: .5;
							}

							100% {
							-webkit-transform: translate3D(-3rem,-3rem,0);
							z-index: -1;
							}
							}

							@-moz-keyframes particle-a {
							0% {
							-moz-transform: translate3D(-3rem,-3rem,0);
							z-index: 1;
							-moz-animation-timing-function: ease-in-out;
							}

							25% {
							width: 1.5rem;
							height: 1.5rem;
							}

							50% {
							-moz-transform: translate3D(4rem, 3rem, 0);
							opacity: 1;
							z-index: 1;
							-moz-animation-timing-function: ease-in-out;
							}

							55% {
							z-index: -1;
							}

							75% {
							width: .75rem;
							height: .75rem;
							opacity: .5;
							}

							100% {
							-moz-transform: translate3D(-3rem,-3rem,0);
							z-index: -1;
							}
							}

							@-o-keyframes particle-a {
							0% {
							-o-transform: translate3D(-3rem,-3rem,0);
							z-index: 1;
							-o-animation-timing-function: ease-in-out;
							}

							25% {
							width: 1.5rem;
							height: 1.5rem;
							}

							50% {
							-o-transform: translate3D(4rem, 3rem, 0);
							opacity: 1;
							z-index: 1;
							-o-animation-timing-function: ease-in-out;
							}

							55% {
							z-index: -1;
							}

							75% {
							width: .75rem;
							height: .75rem;
							opacity: .5;
							}

							100% {
							-o-transform: translate3D(-3rem,-3rem,0);
							z-index: -1;
							}
							}

							@keyframes particle-a {
							0% {
							transform: translate3D(-3rem,-3rem,0);
							z-index: 1;
							animation-timing-function: ease-in-out;
							}

							25% {
							width: 1.5rem;
							height: 1.5rem;
							}

							50% {
							transform: translate3D(4rem, 3rem, 0);
							opacity: 1;
							z-index: 1;
							animation-timing-function: ease-in-out;
							}

							55% {
							z-index: -1;
							}

							75% {
							width: .75rem;
							height: .75rem;
							opacity: .5;
							}

							100% {
							transform: translate3D(-3rem,-3rem,0);
							z-index: -1;
							}
							}

							@-webkit-keyframes particle-b {
							0% {
							-webkit-transform: translate3D(3rem,-3rem,0);
							z-index: 1;
							-webkit-animation-timing-function: ease-in-out;
							}

							25% {
							width: 1.5rem;
							height: 1.5rem;
							}

							50% {
							-webkit-transform: translate3D(-3rem, 3.5rem, 0);
							opacity: 1;
							z-index: 1;
							-webkit-animation-timing-function: ease-in-out;
							}

							55% {
							z-index: -1;
							}

							75% {
							width: .5rem;
							height: .5rem;
							opacity: .5;
							}

							100% {
							-webkit-transform: translate3D(3rem,-3rem,0);
							z-index: -1;
							}
							}

							@-moz-keyframes particle-b {
							0% {
							-moz-transform: translate3D(3rem,-3rem,0);
							z-index: 1;
							-moz-animation-timing-function: ease-in-out;
							}

							25% {
							width: 1.5rem;
							height: 1.5rem;
							}

							50% {
							-moz-transform: translate3D(-3rem, 3.5rem, 0);
							opacity: 1;
							z-index: 1;
							-moz-animation-timing-function: ease-in-out;
							}

							55% {
							z-index: -1;
							}

							75% {
							width: .5rem;
							height: .5rem;
							opacity: .5;
							}

							100% {
							-moz-transform: translate3D(3rem,-3rem,0);
							z-index: -1;
							}
							}

							@-o-keyframes particle-b {
							0% {
							-o-transform: translate3D(3rem,-3rem,0);
							z-index: 1;
							-o-animation-timing-function: ease-in-out;
							}

							25% {
							width: 1.5rem;
							height: 1.5rem;
							}

							50% {
							-o-transform: translate3D(-3rem, 3.5rem, 0);
							opacity: 1;
							z-index: 1;
							-o-animation-timing-function: ease-in-out;
							}

							55% {
							z-index: -1;
							}

							75% {
							width: .5rem;
							height: .5rem;
							opacity: .5;
							}

							100% {
							-o-transform: translate3D(3rem,-3rem,0);
							z-index: -1;
							}
							}

							@keyframes particle-b {
							0% {
							transform: translate3D(3rem,-3rem,0);
							z-index: 1;
							animation-timing-function: ease-in-out;
							}

							25% {
							width: 1.5rem;
							height: 1.5rem;
							}

							50% {
							transform: translate3D(-3rem, 3.5rem, 0);
							opacity: 1;
							z-index: 1;
							animation-timing-function: ease-in-out;
							}

							55% {
							z-index: -1;
							}

							75% {
							width: .5rem;
							height: .5rem;
							opacity: .5;
							}

							100% {
							transform: translate3D(3rem,-3rem,0);
							z-index: -1;
							}
							}

							@-webkit-keyframes particle-c {
							0% {
							-webkit-transform: translate3D(-1rem,-3rem,0);
							z-index: 1;
							-webkit-animation-timing-function: ease-in-out;
							}

							25% {
							width: 1.3rem;
							height: 1.3rem;
							}

							50% {
							-webkit-transform: translate3D(2rem, 2.5rem, 0);
							opacity: 1;
							z-index: 1;
							-webkit-animation-timing-function: ease-in-out;
							}

							55% {
							z-index: -1;
							}

							75% {
							width: .5rem;
							height: .5rem;
							opacity: .5;
							}

							100% {
							-webkit-transform: translate3D(-1rem,-3rem,0);
							z-index: -1;
							}
							}

							@-moz-keyframes particle-c {
							0% {
							-moz-transform: translate3D(-1rem,-3rem,0);
							z-index: 1;
							-moz-animation-timing-function: ease-in-out;
							}

							25% {
							width: 1.3rem;
							height: 1.3rem;
							}

							50% {
							-moz-transform: translate3D(2rem, 2.5rem, 0);
							opacity: 1;
							z-index: 1;
							-moz-animation-timing-function: ease-in-out;
							}

							55% {
							z-index: -1;
							}

							75% {
							width: .5rem;
							height: .5rem;
							opacity: .5;
							}

							100% {
							-moz-transform: translate3D(-1rem,-3rem,0);
							z-index: -1;
							}
							}

							@-o-keyframes particle-c {
							0% {
							-o-transform: translate3D(-1rem,-3rem,0);
							z-index: 1;
							-o-animation-timing-function: ease-in-out;
							}

							25% {
							width: 1.3rem;
							height: 1.3rem;
							}

							50% {
							-o-transform: translate3D(2rem, 2.5rem, 0);
							opacity: 1;
							z-index: 1;
							-o-animation-timing-function: ease-in-out;
							}

							55% {
							z-index: -1;
							}

							75% {
							width: .5rem;
							height: .5rem;
							opacity: .5;
							}

							100% {
							-o-transform: translate3D(-1rem,-3rem,0);
							z-index: -1;
							}
							}

							@keyframes particle-c {
							0% {
							transform: translate3D(-1rem,-3rem,0);
							z-index: 1;
							animation-timing-function: ease-in-out;
							}

							25% {
							width: 1.3rem;
							height: 1.3rem;
							}

							50% {
							transform: translate3D(2rem, 2.5rem, 0);
							opacity: 1;
							z-index: 1;
							animation-timing-function: ease-in-out;
							}

							55% {
							z-index: -1;
							}

							75% {
							width: .5rem;
							height: .5rem;
							opacity: .5;
							}

							100% {
							transform: translate3D(-1rem,-3rem,0);
							z-index: -1;
							}
							}
						</style>
						<div class=\"row\">
							<div class=\"col-xs-12 col-sm-12 col-md-12 col-lg-12\">
								<div class=\"row\">
									<div class=\"col-sm-12\">
										<div class=\"text-center error-box\">
											<h1 class=\"error-text-2 bounceInDown animated\"> Error 404 <span class=\"particle particle--c\"></span><span class=\"particle particle--a\"></span><span class=\"particle particle--b\"></span></h1>
											<h2 class=\"font-xl\"><strong><i class=\"fa fa-fw fa-warning fa-lg text-warning\"></i> Page <u>Not</u> Found</strong></h2>
											<br>
											<p class=\"lead\">
												The page you requested could not be found, either contact your webmaster or try again. 
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						";
                        $this->exceptionMessage($message);
                    }
                } else {

                }
            }
        } else {
            $this->exceptionMessage("error :" . $sql); //  debugging
        }
    }

    /**
     * Return Leaf Name
     * @return string
     */
    public function getLeafName() {
        return $this->leafName;
    }

    /**
     * Set Leaf Name
     * @param string $value
     * @return \Core\Portal\Controller\PortalControllerClass
     */
    public function setLeafName($value) {
        $this->leafName = $value;
        return $this;
    }

    /**
     * Return History Leaf
     * @param int $tableName Table Name
     * @return int $leafId
     */
    private function getHistoryLeafId($tableName) {
        $leafId = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT	`leafId`
			FROM 	`leaf`
			WHERE 	`leafFilename`	=	'" . $tableName . "History.php'
			AND		`companyId`		=	'" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT	[leafId]
			FROM 	[leaf]
			WHERE 	[leafFilename]	=	'" . $tableName . "History.php'
			AND		[companyId]		=	'" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT	LEAFID
			FROM 	LEAF
			WHERE 	LEAFFILENAME	=	'" . $tableName . "History.php'
			AND		COMPANYID		=	'" . $this->getCompanyId() . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        if (is_array($row)) {
            $leafId = intval($row['leafId']);
        }
        return $leafId;
    }

    /**
     * Return Reference Table Name
     * @return string
     */
    public function getReferenceTableName() {
        return $this->referenceTableName;
    }

    /**
     * Set Reference Table Name
     * @param string $value
     * @return \Core\Portal\Controller\PortalControllerClass
     */
    public function setReferenceTableName($value) {
        $this->referenceTableName = $value;
        return $this;
    }

    /**
     * Return Reference Table Name
     * @return string
     */
    public function getTableNameId() {
        return $this->tableNameId;
    }

    /**
     * Set Reference Table Name
     * @param string $value
     * @return \Core\Portal\Controller\PortalControllerClass
     */
    public function setTableNameId($value) {
        $this->tableNameId = $value;
        return $this;
    }

    /**
     * Return Chart Of Account Category Code
     * @return string
     */
    public function getChartOfAccountCategoryCode() {
        return $this->chartOfAccountCategoryCode;
    }

    /**
     * Set Chart Of Account Category Code
     * @param string $value
     * @return \Core\Portal\Controller\PortalControllerClass
     */
    public function setChartOfAccountCategoryCode($value) {
        $this->chartOfAccountCategoryCode = $value;
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
     * Set Chart Of Account Type Code
     * @param string $value
     * @return $this
     */
    public function setChartOfAccountTypeCode($value) {
        $this->chartOfAccountTypeCode = $value;
        return $this;
    }

    /**
     * Menu navigation application
     * @return array
     * @throws \Exception
     */
    public function application() {
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }

        $sql = null;
        $data = array();
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `applicationtranslate`.`applicationNative`,
							`application`.`applicationId`,
							`application`.`isSingle`,
							`application`.`applicationFontAweSome`
            FROM    `application`
            JOIN    `applicationaccess`
            USING   (`applicationId`,`companyId`)
            JOIN    `applicationtranslate`
            USING   (`applicationId`,`companyId`)
            WHERE   `applicationaccess`.`applicationAccessValue`    =   1
            AND     `applicationaccess`.`companyId` =   '" . $this->getCompanyId() . "'
            AND     `applicationaccess`.`roleId` =   '" . $this->getRoleId() . "'
            AND     `applicationtranslate`.`languageId`='" . $this->getLanguageId() . "'
            AND     `application`.`isActive`=1
            ORDER BY `application`.`applicationSequence`";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      [applicationTranslate].[applicationNative],
							[application].[applicationId],
							[application].[isSingle],
							[application].[applicationFontAweSome]
            FROM        [application]
            JOIN        [applicationAccess]
            ON          [application].[companyId] = [applicationAccess].[companyId]
            AND         [application].[applicationId] = [applicationAccess].[applicationId]
            JOIN        [applicationTranslate]
            ON          [application].[companyId] = [applicationTranslate].[companyId]
            AND         [application].[applicationId] = [applicationTranslate].[applicationId]
            WHERE       [applicationAccess].[applicationAccessValue]    =   1
            AND         [applicationAccess].[companyId] =   '" . $this->getCompanyId() . "'
            AND         [applicationAccess].[roleId] =   '" . $this->getRoleId() . "'
            AND         [applicationTranslate].[languageId]='" . $this->getLanguageId() . "'
            AND         [application].[isActive]=1
            ORDER BY    [application].[applicationSequence]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      APPLICATIONTRANSLATE.APPLICATIONNATIVE  AS  \"applicationNative\",
							APPLICATION.APPLICATIONID               AS  \"applicationId\",
							APPLICATION.ISSINGLE                    AS  \"isSingle\",
							APPLICATION.APPLICATIONFONTAWESOME AS \"applicationFontAweSome\"
            FROM        APPLICATION
            JOIN        	APPLICATIONACCESS
            ON          	APPLICATION.COMPANYID			=	APPLICATIONACCESS.COMPANYID
            AND         	APPLICATION.APPLICATIONID 	=	APPLICATIONACCESS.APPLICATIONID

            JOIN        APPLICATIONTRANSLATE
            ON          APPLICATION.COMPANYID  = APPLICATIONTRANSLATE.COMPANYID
            AND         APPLICATION.APPLICATIONID  = APPLICATIONTRANSLATE.APPLICATIONID

            JOIN        COMPANY
            ON          APPLICATION.COMPANYID  = COMPANY.COMPANYID

            WHERE       APPLICATIONACCESS.APPLICATIONACCESSVALUE    =   1
            AND         APPLICATIONACCESS.ROLEID =   '" . $this->getRoleId() . "'
            AND         APPLICATIONTRANSLATE.LANGUAGEID='" . $this->getLanguageId() . "'
            AND         APPLICATION.COMPANYID = '" . $this->getCompanyId() . "'
            AND         APPLICATION.ISACTIVE=1
            ORDER BY    APPLICATION.APPLICATIONSEQUENCE";
        }
        if (isset($_SESSION['isDebug']) == 1) {
            $this->exceptionMessage($sql);
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        while (($row = $this->q->fetchArray($result)) == true) {
            $row['module'] = $this->applicationAndModule($row['applicationId']);
            $data[] = $row;
        }
        unset($result);
        return $data;
    }

    /**
     * Menu Navigation application and module
     * @param int $applicationId Application Primary Key
     * @return array
     * @throws \Exception
     */
    public function applicationAndModule($applicationId) {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        $detail = array();
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
                SELECT  `moduletranslate`.`moduleNative`,
                        `module`.`applicationId`,
                        `module`.`moduleId`,
                        `module`.`isSingle`,
						`module`.`moduleFontAweSome`
                FROM     `module`
                JOIN     `moduleaccess`
                USING   (`moduleId`,`companyId`)
                JOIN     `moduletranslate`
                USING   (`moduleId`,`companyId`)
                WHERE   `moduleaccess`.`roleId`                         =   '" . $this->getRoleId() . "'
                AND     `moduleaccess`.`moduleAccessValue`              =   1
                AND     `module`.`applicationId`                   =   '" . $applicationId . "'
                AND     `moduletranslate`.`languageId`                  =   '" . $this->getLanguageId() . "'
                AND     `module`.`isActive`                             =   1
                ORDER BY `module`.`moduleSequence` ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                SELECT      [moduleTranslate].[moduleNative],
                            [module].[applicationId],
                            [module].[moduleId],
                            [module].[isSingle],
							[module].[moduleFontAweSome]
                FROM        [module]
                JOIN        [moduleAccess]
                ON          [module].[companyId] = [moduleAccess].[companyId]
                AND         [module].[moduleId] = [moduleAccess].[moduleId]
                JOIN        [moduleTranslate]
                ON          [module].[companyId] = [moduleTranslate].[companyId]
                AND         [module].[moduleId] = [moduleTranslate].[moduleId]
                WHERE       [moduleAccess].[roleId]                         =   '" . $this->getRoleId() . "'
                AND         [moduleAccess].[moduleaccessValue]              =   1
                AND         [module].[applicationId]                   =   '" . $applicationId . "'
                AND         [moduleTranslate].[languageId]                  =   '" . $this->getLanguageId() . "'
                AND         [module].[isActive]                             =   1
                ORDER BY    [module].[moduleSequence] ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                SELECT  MODULETRANSLATE.MODULENATIVE    AS  \"moduleNative\",
                        MODULE.APPLICATIONID            AS  \"applicationId\",
                        MODULE.MODULEID                 AS  \"moduleId\",
                        MODULE.ISSINGLE                 AS  \"isSingle\",
					    MODULE.MODULEFONTAWESOME	AS \"moduleFontAweSome\"
                FROM    MODULE
                JOIN    MODULEACCESS

                ON      MODULE.COMPANYID = MODULEACCESS.COMPANYID
                AND     MODULE.MODULEID = MODULEACCESS.MODULEID

                JOIN    MODULETRANSLATE
                ON      MODULE.COMPANYID = MODULETRANSLATE.COMPANYID
                AND     MODULE.MODULEID = MODULETRANSLATE.MODULEID

                WHERE   MODULEACCESS.ROLEID                         =   '" . $this->getRoleId() . "'
                AND     MODULEACCESS.MODULEACCESSVALUE              =   1
                AND     MODULE.APPLICATIONID                   =   '" . $applicationId . "'
                AND     MODULETRANSLATE.LANGUAGEID                  =   '" . $this->getLanguageId() . "'
                AND     MODULE.ISACTIVE                             =   1
                ORDER BY MODULE.MODULESEQUENCE ";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        while (($row = $this->q->fetchArray($result)) == true) {
            $row['folder'] = $this->folder($row['applicationId'], $row['moduleId']);
            $detail[] = $row;
        }
        unset($result);
        return $detail;
    }

    /**
     *  Generate Folder
     * @access public
     * @param null|int $applicationId Folder Primary Key
     * @param null|int $moduleId Module Primary Key
     * @return array
     * @throws \Exception
     */
    public function folder($applicationId = null, $moduleId = null) {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        $data = array();
        if (isset($_SESSION['roleId'])) {
            $this->getRoleId($_SESSION['roleId']);
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
                SELECT  `foldertranslate`.`folderNative`,
                        `folder`.`applicationId`,
                        `folder`.`moduleId`,
                        `folder`.`folderId`,
                        `folder`.`folderPath`,
                        `folder`.`isSingle`,
						`folder`.`folderFontAweSome`
                FROM    `folder`
                JOIN    `folderaccess`
                USING   (`folderId`,`companyId`)
                JOIN    `foldertranslate`
                USING   (`folderId`,`companyId`)
                WHERE   `folderaccess`.`folderAccessValue`  =   1
                AND     `folder`.`applicationId`            =   '" . $applicationId . "'
                AND     `folder`.`moduleId`                 =   '" . $moduleId . "'
                AND     `folderaccess`.`roleId`             =   '" . $this->getRoleId() . "'
                AND     `foldertranslate`.`languageId`      =   '" . $this->getLanguageId() . "'
                AND     `folder`.`companyId`                =   '" . $this->getCompanyId() . "'
                AND     `folder`.`isActive`                 =   1
                ORDER BY `folder`.`folderSequence`";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                SELECT      [folderTranslate].[folderNative],
                            [folder].[applicationId],
                            [folder].[moduleId],
                            [folder].[folderId],
                            [folder].[folderPath],
                            [folder].[isSingle],
							[folder].[folderFontAweSome]
                FROM        [folder]
                JOIN        [folderAccess]
                ON          [folder].[companyId] = [folderAccess].[companyId]
                AND         [folder].[folderId] = [folderAccess].[folderId]
                JOIN        [folderTranslate]
                ON          [folder].[companyId] = [folderTranslate].[companyId]
                AND         [folder].[folderId] = [folderTranslate].[folderId]
                WHERE       [folderAccess].[folderAccessValue]  =   1
                AND         [folder].[applicationId]            =   '" . $applicationId . "'
                AND         [folder].[moduleId]                 =   '" . $moduleId . "'
                AND         [folderAccess].[roleId]             =   '" . $this->getRoleId() . "'
                AND         [folderTranslate].[languageId]      =   '" . $this->getLanguageId() . "'
                AND         [folder].[companyId]                =   '" . $this->getCompanyId() . "'
                AND         [folder].[isActive]                 =   1
                ORDER BY    [folder].[folderSequence]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                SELECT  FOLDERTRANSLATE.FOLDERNATIVE    AS  \"folderNative\",
                        FOLDER.APPLICATIONID            AS  \"applicationId\",
                        FOLDER.MODULEID                 AS  \"moduleId\",
                        FOLDER.FOLDERID                 AS  \"folderId\",
                        FOLDER.FOLDERPATH               AS  \"folderPath\",
                        FOLDER.ISSINGLE                 AS  \"isSingle\",
						 FOLDER.FOLDERFONTAWESOME                 AS  \"folderFontAweSome\"
                FROM    FOLDER

                JOIN    FOLDERACCESS
                ON      FOLDER.COMPANYID = FOLDERACCESS.COMPANYID
                AND     FOLDER.FOLDERID = FOLDERACCESS.FOLDERID

                JOIN    FOLDERTRANSLATE
                ON      FOLDER.COMPANYID = FOLDERTRANSLATE.COMPANYID
                AND     FOLDER.FOLDERID = FOLDERTRANSLATE.FOLDERID

                WHERE   FOLDERACCESS.FOLDERACCESSVALUE  =   1
                AND     FOLDER.APPLICATIONID            =   '" . $applicationId . "'
                AND     FOLDER.MODULEID                 =   '" . $moduleId . "'
                AND     FOLDERACCESS.ROLEID             =   '" . $this->getRoleId() . "'
                AND     FOLDERTRANSLATE.LANGUAGEID      =   '" . $this->getLanguageId() . "'
                AND     FOLDER.COMPANYID                =   '" . $this->getCompanyId() . "'
                AND     FOLDER.ISACTIVE                 =   1
                ORDER BY FOLDER.FOLDERSEQUENCE";
        }
        if (isset($_SESSION['isDebug']) == 1) {
            $this->exceptionMessage($sql);
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        while (($row = $this->q->fetchArray($result)) == true) {
            $row['leaf'] = $this->folderAndLeaf($row['applicationId'], $row['moduleId'], $row['folderId']);

            $data[] = $row;
        }
        unset($result);
        return $data;
    }

    /**
     * Generate Leaf
     * @param null|int $applicationId Application Primary Key
     * @param null|int $moduleId Module Primary Key
     * @param null|int $folderId Folder Primary Key
     * @return array
     * @throws \Exception
     */
    public function folderAndLeaf($applicationId = null, $moduleId = null, $folderId = null) {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }

        $sql = null;
        $detail = array();

        $this->applicationId = $applicationId;
        $this->moduleId = $moduleId;
        $this->folderId = $folderId;

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
                SELECT  *
                FROM   `leaf`
                JOIN    `leafaccess`
                USING   (`leafId`,`companyId`)
                JOIN    `leaftranslate`
                USING   (`leafId`,`companyId`)
                WHERE   `leafaccess`.`staffId`              =   '" . $this->getStaffId() . "'
                AND     `leafaccess`.`leafAccessReadValue`  =   1
                AND     `leaf`.`applicationId`              =   '" . $this->applicationId . "'
                AND     `leaf`.`moduleId`                   =   '" . $this->moduleId . "'
                AND     `leaf`.`folderId`                   =   '" . $this->folderId . "'
                AND     `leaftranslate`.`languageId`        =   '" . $this->getLanguageId() . "'
                AND     `leaf`.`isActive`                   =   1
                ORDER BY `leaf`.`leafSequence`";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                SELECT  *
                FROM    [leaf]
                JOIN    [leafAccess]
                ON      [leaf].[companyId]= [leafAccess].[companyId]
                AND     [leaf].[leafId] =[leafAccess].[leafId]
                JOIN    [leafTranslate]
                ON      [leaf].[companyId]= [leafTranslate].[companyId]
                AND     [leaf].[leafId] =[leafTranslate].[leafId]
                WHERE   [leafAccess].[staffId]              =   '" . $this->getStaffId() . "'
                AND     [leafAccess].[leafaccessReadValue]  =   1
                AND     [leaf].[applicationId]              =   '" . $this->applicationId . "'
                AND     [leaf].[moduleId]                   =   '" . $this->moduleId . "'
                AND     [leaf].[folderId]                   =   '" . $this->folderId . "'
                AND     [leafTranslate].[languageId]        =   '" . $this->getLanguageId() . "'
                AND     [leaf].[isActive]                   =   1
                ORDER BY [leaf].[leafSequence]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                SELECT  LEAF.LEAFID                 AS  \"leafId\",
                        LEAF.COMPANYID              AS  \"companyId\",
                        LEAF.APPLICATIONID          AS  \"applicationId\",
                        LEAF.MODULEID               AS  \"moduleId\",
                        LEAF.FOLDERID               AS  \"folderId\",
                        LEAF.LEAFSEQUENCE           AS  \"leafSequence\",
                        LEAF.LEAFTITLE              AS  \"leafTitle\",
                        LEAF.LEAFDESCRIPTION        AS  \"leafDescription\",
                        LEAF.LEAFFILENAME           AS  \"leafFilename\",
                        LEAF.LEAFENGLISH            AS  \"leafEnglish\",
                        LEAF.EXECUTEBY              AS  \"executeBy\",
                        LEAF.EXECUTETIME            AS  \"executeTime\",
                        LEAFACCESS.LEAFACCESSID     AS  \"leafAccessId\",
                        LEAFTRANSLATE.LEAFNATIVE    AS  \"leafNative\",
						LEAF.LEAFFONTAWESOME    AS  \"leafFontAweSome\"
                FROM    LEAF

                JOIN    LEAFACCESS
                ON      LEAF.COMPANYID                  =   LEAFACCESS.COMPANYID
                AND     LEAF.LEAFID                     =   LEAFACCESS.LEAFID

                JOIN    LEAFTRANSLATE
                ON      LEAF.COMPANYID                  =   LEAFTRANSLATE.COMPANYID
                AND     LEAF.LEAFID                     =   LEAFTRANSLATE.LEAFID

                WHERE   LEAFACCESS.STAFFID              =   '" . $this->getStaffId() . "'
                AND     LEAFACCESS.LEAFACCESSREADVALUE  =   1
                AND     LEAF.APPLICATIONID              =   '" . $this->applicationId . "'
                AND     LEAF.MODULEID                   =   '" . $this->moduleId . "'
                AND     LEAF.FOLDERID                   =   '" . $this->folderId . "'
                AND     LEAFTRANSLATE.LANGUAGEID        =   '" . $this->getLanguageId() . "'
                AND     LEAF.ISACTIVE                   =   1
                ORDER BY LEAF.LEAFSEQUENCE";
        }
        if (isset($_SESSION['isDebug']) == 1) {
            $this->exceptionMessage($sql);
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        while (($row = $this->q->fetchArray($result)) == true) {
            $detail[] = $row;
        }
        unset($result);

        return $detail;
    }

    /**
     * Return First Active application
     * @return int
     * @throws \Exception
     */
    public function getFirstActiveApplicationId() {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        $applicationId = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
                SELECT  `application`.`applicationId`
                FROM    `application`
                JOIN    `applicationAccess`
                WHERE   `application`.`isActive`=1
                AND     `applicationaccess`.`roleId`='" . $this->getRoleId() . "'
                LIMIT   1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                SELECT  TOP 1 [applicationId]
                FROM    [application]
                JOIN    [applicationAccess]
                WHERE   [application].[isActive]=1
                AND     [applicationaccess].[roleId]='" . $this->getRoleId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                SELECT  APPLICATIONID
                FROM    APPLICATION
                JOIN    APPLICATIONACCESS
                WHERE   APPLICATION.ISACTIVE=1
                AND     APPLICATIONACCESS.ROLEID='" . $this->getRoleId() . "'
                AND     ROWNUM = 1";
        }
        if (isset($_SESSION['isDebug']) == 1) {
            $this->exceptionMessage($sql);
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
            $applicationId = intval($row['applicationId']);
        }
        return $applicationId;
    }

    /**
     * Return First Module Active
     * @param int $applicationId Application Primary Key
     * @return int $moduleId Module Primary Key
     * @throws \Exception
     */
    public function getFirstActiveModuleId($applicationId) {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        $moduleId = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
                SELECT  `module`.`moduleId`
                FROM    `module`
                JOIN    `moduleAccess`
                WHERE   `module`.`isActive`=1
                AND     `moduleaccess`.`roleId`='" . $this->getRoleId() . "'
                AND     `module`.`applicationId`='" . $applicationId . "'
                LIMIT   1 ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                SELECT  TOP 1 [moduleId]
                FROM    [module]
                JOIN    [moduleAccess]
                WHERE   [module].[isActive]=1
                AND     [moduleAccess].[roleId]='" . $this->getRoleId() . "'
                AND     [module].[applicationId]='" . $applicationId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                SELECT  MODULEID
                FROM    MODULE
                JOIN    MODULEACCESS
                WHERE   MODULE.ISACTIVE=1
                AND     MODULEACCESS.ROLEID='" . $this->getRoleId() . "'
                WHERE   MODULE.APPLICATIONID='" . $applicationId . "'
                AND     ROWNUM = 1";
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
            $moduleId = intval($row['moduleId']);
        }

        return $moduleId;
    }

    /**
     * Return Total Budget.Based On Financial Year.
     * @param int $type I -> Income E -> Expenses
     * @return double $budgetAmount
     */
    public function getTotalBudget($type) {
        $budgetAmount = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT sum(
						`budgetTargetMonthOne` 		+
						`budgetTargetMonthTwo` 		+
						`budgetTargetMonthThree`	+
						`budgetTargetMonthFourth`	+
						`budgetTargetMonthFifth`	+
						`budgetTargetMonthSix`		+
						`budgetTargetMonthSeven`	+
						`budgetTargetMonthEight`	+
						`budgetTargetMonthNine`		+
						`budgetTargetMonthTen`		+
						`budgetTargetMonthEleven`	+
						`budgetTargetMonthTwelve`	+
						`budgetTargetMonthThirteen`	+
						`budgetTargetMonthFourteen`	+
						`budgetTargetMonthFifteen`	+
						`budgetTargetMonthSixteen`	+
						`budgetTargetMonthSeventeen`+
						`budgetTargetMonthEighteen`
					) AS `budgetAmount`
			FROM	`budget`
			JOIN	`chartofaccount`
			USING	(`companyId`,`chartOfAccountId`)
			JOIN	`chartofaccountcategory`
			USING	(`companyId`,`chartOfAccountCategoryId`)
			WHERE	`budget`.`companyId`			=	'" . $this->getCompanyId() . "'
			AND		`budget`.`financeYearId`		=	'" . $this->getFinanceYearId() . "'
			AND		`chartOfAccountCategoryCode`	=	'" . $type . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT sum(
						[budgetTargetMonthOne] 		+
						[budgetTargetMonthTwo] 		+
						[budgetTargetMonthThree]	+
						[budgetTargetMonthFourth]	+
						[budgetTargetMonthFifth]	+
						[budgetTargetMonthSix]		+
						[budgetTargetMonthSeven]	+
						[budgetTargetMonthEight]	+
						[budgetTargetMonthNine]		+
						[budgetTargetMonthTen]		+
						[budgetTargetMonthEleven]	+
						[budgetTargetMonthTwelve]	+
						[budgetTargetMonthThirteen]	+
						[budgetTargetMonthFourteen]	+
						[budgetTargetMonthFifteen]	+
						[budgetTargetMonthSixteen]	+
						[budgetTargetMonthSeventeen]+
						[budgetTargetMonthEighteen]
					) AS [budgetAmount]
			FROM	[budget]
			JOIN	[chartOfAccount]
			ON		[chartOfAccount].[companyId] 						=	[budget].[companyId]
			AND		[chartOfAccount].[chartOfAccountId] 				= 	[budget].[chartOfAccountId]
			JOIN	[chartOfAccountCategory]
			ON		[chartOfAccountCategory][companyId] 				= 	[budget].[companyId]
			AND		[chartOfAccountCategory][chartOfAccountCategoryId] = 	[chartOfAccount].[chartOfAccountCategoryId]
			WHERE	[budget].[companyId]								=	'" . $this->getCompanyId() . "'
			AND		[budget].[financeYearId]							=	'" . $this->getFinanceYearId() . "'
			AND		[chartOfAccountCategory][chartOfAccountCategoryCode]	=	'" . $type . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT sum(
						BUDGETTARGETMONTHONE 		+
						BUDGETTARGETMONTHTWO 		+
						BUDGETTARGETMONTHTHREE		+
						BUDGETTARGETMONTHFOURTH		+
						BUDGETTARGETMONTHFIFTH		+
						BUDGETTARGETMONTHSIX		+
						BUDGETTARGETMONTHSEVEN		+
						BUDGETTARGETMONTHEIGHT		+
						BUDGETTARGETMONTHNINE		+
						BUDGETTARGETMONTHTEN		+
						BUDGETTARGETMONTHELEVEN		+
						BUDGETTARGETMONTHTWELVE		+
						BUDGETTARGETMONTHTHIRTEEN	+
						BUDGETTARGETMONTHFOURTEEN	+
						BUDGETTARGETMONTHFIFTEEN	+
						BUDGETTARGETMONTHSIXTEEN	+
						BUDGETTARGETMONTHSEVENTEEN  +
						BUDGETTARGETMONTHEIGHTEEN
					) AS \"budgetAmount\"
				FROM BUDGET
			JOIN	CHARTOFACCOUNT
			ON		CHARTOFACCOUNT.COMPANYID 						= 	BUDGET.COMPANYID
			AND		CHARTOFACCOUNT.CHARTOFACCOUNTID 				= 	BUDGET.CHARTOFACCOUNTID
			JOIN	CHARTOFACCOUNTCATEGORY
			ON		CHARTOFACCOUNTCATEGORY.COMPANYID 				=	BUDGET.COMPANYID
			AND		CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID = 	CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID
			WHERE	BUDGET.COMPANYID								=	'" . $this->getCompanyId() . "'
			AND		BUDGET.FINANCEYEARID							=	'" . $this->getFinanceYearId() . "''
			AND		CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE	=	'" . $type . "'";
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
            $budgetAmount = $row['budgetAmount'];
        }

        return $budgetAmount;
    }

    /**
     * Return Chart Of Account Type Code
     * @return string $chartOfAccountTypeCode
     */
    public function getFinanceYearId() {
        return $this->financeYearId;
    }

    /**
     * Set Finance Year
     * @param string $value
     * @return \Core\Portal\Controller\PortalControllerClass
     */
    public function setFinanceYearId($value) {
        $this->financeYearId = $value;
        return $this;
    }

    /**
     * Return Actual Figure
     * @param int $type I -> Income E -> Expenses
     * @return double $actualAmount
     */
    public function getTotalActual($type) {
        $actualAmount = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT SUM(
						`localAmount`
					) AS `actualAmount`
			FROM	`generalledger`
			WHERE		`chartOfAccountCategoryCode`	=	'" . $type . "'
			AND		`companyId`						=	'" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT SUM(
						`localAmount`
					) AS `actualAmount`
			FROM	`generalledger`
			WHERE		`chartOfAccountCategoryCode`	=	'" . $type . "'
			AND		`companyId`						=	'" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT SUM(
						LOCALAMOUNT
					) AS ACTUALAMOUNT
			FROM	GENERALLEDGER
			WHERE		CHARTOFACCOUNTCATEGORYCODE	=	'" . $type . "'
			AND		COMPANYID					=	'" . $this->getCompanyId() . "'";
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
            $actualAmount = $row['actualAmount'];
        }

        return $actualAmount;
    }

    /**
     * Return Balance Bank / Petty Cash Account
     * @param int $type 1->Petty Cash,2->Bank
     * @return double $bankBalance
     */
    public function getTotalBank($type) {
        $sql = null;
        $bankBalance = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT 	COALESCE(SUM(`localAmount`),0) AS \"bankBalance\"
			FROM 	`bank`
			JOIN	`generalledger`
			USING	(`companyId`,`chartOfAccountId`)
			WHERE	`bank`.`companyId`	=	'" . $this->getCompanyId() . "'
			";
            if ($type == 1) {
                $sql .= " AND `isPettyCash`=1";
            } else {
                $sql .= " AND `isPettyCash`=0";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT 	COALESCE(SUM([localAmount]),0) AS \"bankBalance\"
			FROM 	[bank]
			JOIN	[chartofaccount]
			ON		[generalLedger].[companyId] 		= 	[bank].[companyId]
			AND		[generalLedger].[chartOfAccountId]	=	[bank].[chartOfAccountId]
			WHERE	[bank].[companyId]					=	'" . $this->getCompanyId() . "'
			";
            if ($type == 1) {
                $sql .= " AND [isPettyCash]=1";
            } else {
                $sql .= " AND [isPettyCash]=0";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT 	COALESCE(SUM(LOCALAMOUNT),0) AS \"bankBalance\"
			FROM 	BANK
			JOIN	GENERALLEDGER
			ON		GENERALLEDGER.COMPANYID 		=	BANK.COMPANYID
			AND		GENERALLEDGER.CHARTOFACCOUNTID	=	BANK.CHARTOFACCOUNTID
			WHERE	BANK.COMPANYID					=	'" . $this->getCompanyId() . "'
			";
            if ($type == 1) {
                $sql .= " AND ISPETTYCASH=1";
            } else {
                $sql .= " AND ISPETTYCASH=0";
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $bankBalance = $row['bankBalance'];
        }
        return $bankBalance;
    }

    /**
     * Return Portal Title
     * @return string
     */
    public function getPortalTitle() {
        return $this->portalTitle;
    }

    /**
     * Set Portal Title
     * @param int $value
     * @return \Core\Portal\Controller\PortalControllerClass
     */
    public function setPortalTitle($value) {
        $this->portalTitle = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getApplicationId() {
        return $this->applicationId;
    }

    /**
     * @param int $applicationId
     */
    public function setApplicationId($applicationId) {
        $this->applicationId = $applicationId;
    }

    /**
     * @return int
     */
    public function getFolderId() {
        return $this->folderId;
    }

    /**
     * @param $folderId
     * @return $this
     */
    public function setFolderId($folderId) {
        $this->folderId = $folderId;
        return $this;
    }

    /**
     * @return int
     */
    public function getLeafId() {
        return $this->leafId;
    }

    /**
     * @param int $leafId
     * @return $this|ConfigClass
     */
    public function setLeafId($leafId) {
        $this->leafId = $leafId;
        return $this;
    }

    /**
     * @return int
     */
    public function getModuleId() {
        return $this->moduleId;
    }

    /**
     * @param int $moduleId
     * @return $this
     */
    public function setModuleId($moduleId) {
        $this->moduleId = $moduleId;
        return $this;
    }

}

/**
 * Class StoryClass
 * Story Class.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Service
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class StoryClass extends ConfigClass {

    /**
     * Connection DatabaseObject
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;

    /**
     * Translation
     * @var string
     */
    public $t;

    /**
     * Translation Array
     * @var string
     */
    public $translate;

    /**
     * Constructor
     */
    function __construct() {
        if (isset($_SESSION['companyId'])) {
            $this->setRoleId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        } else {
            $this->setRoleId(7);
        }
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            $this->setStaffId(9);
        }
        if (isset($_SESSION['languageId'])) {
            $this->setLanguageId($_SESSION['languageId']);
        } else {
            $this->setLanguageId(21);
        }
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->translate = array();

        // $this->model = new StaffModel();
        // $this->model->setVendor($this->getVendor());
        // $this->model->execute();
        //$translator = new \Core\shared\SharedClass();
        //
        //$translator->setCurrentTable($this->model->getTableName());
        //$translator->execute();
        //$this->translate = $translator->getDefaultTranslation(); // short because code too long
    }

    /**
     * Create
     * @see config::read()
     */
    public function create() {
        
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

    /**
     * Center Cell
     * @return mixed
     */
    function centerCell() {
        //return $this->centerDefault();
        // check database want customize mode and curousel mode
    }

    /**
     * Center Story
     * @return string
     */
    function centerStory() {
        $str = "";
        /**
         * $str .= "<div id=\"centerViewPort\" class=\"hero-unit\">
         * <h1>
         * <img alt=\"Wait Ya.\" height=\"100\" width=\"100\" src=\"./images/Blueticons_Win/PNGs/Devil.png\">";
         *
         * echo $firstHeader = "Welcome.. Core Light";
         *
         * $str .= "</h1><p>
         *
         * <br>
         * <p><a class=\"btn-primary btn-large\">Learn more &raquo;</a></p>
         * </div>";
         */
        return $str;
    }

    /**
     *
     */
    function centerCarousel() {
        
    }

    /*
     * Load the below image
     * @return void
     */

    function bottomStory() {

        $data = array();
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
          SELECT *
          FROM    `story`
          WHERE   `isActive`  =   1
          AND     `companyId` ='" . $this->getCompanyId() . "'
          LIMIT 3";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
          SELECT *
          FROM    [story]
          WHERE   [isActive]  =   1
          AND     [companyId]  =  '" . $this->getCompanyId() . "'
          LIMIT 3";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
          SELECT *
          FROM    STORY
          WHERE   ISACTIVE  =   1
          AND     COMPANYID = '" . $this->getCompanyId() . "'
          LIMIT 3";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        while (($row = $this->q->fetchArray($result)) == TRUE) {

            $data[] = $row;
        }
        return $data;
    }

}

/**
 * Class TinyContentPortal
 * @property mixed translate
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Service
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class TinyContentPortal extends ConfigClass {

    /**
     * Connection DatabaseObject
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;

    /**
     * @var string
     */
    public $model;

    /**
     * Constructor
     */
    function __construct() {
        if (isset($_SESSION['companyId'])) {
            $this->setRoleId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        } else {
            $this->setRoleId(7);
        }
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            $this->setStaffId(9);
        }
        if (isset($_SESSION['languageId'])) {
            $this->setLanguageId($_SESSION['languageId']);
        } else {
            $this->setLanguageId(21);
        }
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();

        $translator = new SharedClass();
        $translator->execute();
        $this->translate = $translator->getDefaultTranslation(); // short because code too long  ;

        if (isset($_SESSION['companyId'])) {
            $this->setRoleId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        } else {
            $this->setRoleId(7);
        }
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            $this->setStaffId(9);
        }
        if (isset($_SESSION['languageId'])) {
            $this->setLanguageId($_SESSION['languageId']);
        } else {
            $this->setLanguageId(21);
        }
    }

    /**
     * Create
     * @see config::read()
     */
    public function create() {
        
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

    /**
     * Return Staff/User Avatar
     * @return string
     * @throws \Exception
     */
    function getAvatar() {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }

        $sql = "
            SELECT  `staffAvatar`
            FROM    `staff`
            WHERE   `staffId`   =   '" . $this->getStaffId() . "'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }

        if ($this->q->numberRows($result, $sql) > 0) {
            $row = $this->q->fetchArray($result);
            if (!isset($row['staffAvatar'])) {
                $row['staffAvatar'] = 'mr.jpg';
            }
            return $row['staffAvatar'];
        } else {
            return 'mr.jpg';
        }
    }

    /**
     * function leftCellTopFive($errorType = null) {
     * $this->q->response = null;
     * $this->errorType = $errorType;
     * if (empty($this->errorType)) {
     * $exception = "html";
     * } else {
     * $exception = "json";
     * }
     * $data = array();
     * if ($this->getStaffId()) {
     * if ($this->getVendor() == self::MYSQL) {
     * $sql = "
     * SELECT      COUNT(*) AS `Rows` ,
     * `log`.`leafId`,
     * `leaftranslate`.`leafNative`
     * FROM        `" . $this->q->getLogDatabase() . "`.`log`
     * JOIN        `leaf`
     * USING       (`leafId`)
     * JOIN        `leafaccess`
     * USING       (`leafId`,`staffId`)
     * JOIN        `leaftranslate`
     * USING       (`leafId`)
     * WHERE       `log`.`staffId`='" . $this->getStaffId() . "'
     * GROUP BY    `leafId`
     * ORDER BY    `Rows` ASC
     * LIMIT       5";
     * } else if ($this->getVendor() == self::MSSQL) {
     *
     * } else if ($this->getVendor() == self::ORACLE) {
     *
     * }
     *
     * $result = $this->q->fast($sql);
     * if ($this->q->getExecute() == 'fail') {
     * echo json_encode(array("success" => false, "message" => $this->q->getResponse()));
     * exit();
     * }
     * if ($this->q->numberRows($result,$sql) > 0) {
     * while (($row = $this->q->fetchArray($result)) == TRUE) {
     * $data['leafId'] = $row['leafId'];
     * $data['leafNative'] = $row['leafNative'];
     * }
     *
     * return $data;
     * }
     * }
     * }
     */

    /**
     * Left Cell Top Ten
     * @return array
     * @throws \Exception
     */
    function leftCellTopTen() {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }

        $sql = null;
        $data = array();
        $result = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
                SELECT      COUNT( * ) AS total,
                            `leafstaffbookmark`.`leafId`,
                            `leaftranslate`.`leafNative`
                FROM        `leafstaffbookmark`
                JOIN        `leaf`
                USING       (`leafId`,`companyId`)
                JOIN        `leafaccess`
                USING       (`leafId`,`staffId`,`companyId`)
                JOIN        `leaftranslate`
                USING       (`leafId`,`companyId`)
                WHERE       `leafaccess`.`staffId`              =   '" . $this->getStaffId() . "'
                AND         `leaf`.`isActive`                   =   1
                AND         `leafaccess`.`leafAccessReadValue`  =   1
                AND         `leaftranslate`.`isActive`          =   1
                AND         `leaftranslate`.`languageId`        =   '" . $this->getLanguageId() . "'
                GROUP BY leafId
                ORDER BY `total` DESC
                LIMIT       10";
        } else if ($this->getVendor() == self::MSSQL) {

            $sql = "
                SELECT      TOP 10 COUNT( * ) AS [total],
                            [leafStaffBookmark].[leafId],
                            [leafTranslate].[leafNative]
                FROM        [leafStaffBookmark]
                JOIN        [leaf]
                ON          [leaf].[companyId] = [leafStaffBookmark].[companyId]
                AND         [leaf].[leafId] = [leafStaffBookmark].[leafId]
                JOIN        [leafAccess]
                ON          [leaf].[companyId] = [leafAccess].[companyId]
                AND         [leaf].[leafId] = [leafAccess].[leafId]
                AND         [leafStaffBookmark].[staffId] = [leafAccess].[staffId]
                JOIN        [leafTranslate]
                ON          [leaf].[companyId] = [leafAccess].[companyId]
                AND         [leaf].[leafId] = [leafAccess].[leafId]
                WHERE       [leafAccess].[staffId]              =   '" . $this->getStaffId() . "'
                AND         [leaf].[isActive]                   =   1
                AND         [leafAccess].[leafAccessReadValue]  =   1
                AND         [leafTranslate].[isActive]          =   1
                AND         [leafTranslate].[languageId]        =   '" . $this->getLanguageId() . "'
                GROUP BY    [leafStaffBookmark].[leafId],
                            [leafTranslate].[leafNative]
                ORDER BY    [total]
                DESC";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                SELECT *
						FROM ( SELECT	a.*,
												rownum r
						FROM (
                SELECT      COUNT( * ) AS \"total\",
                            LEAFSTAFFBOOKMARK.LEAFID as \"leafId\",
                            LEAFTRANSLATE.LEAFNATIVE AS \"leafNative\"
                FROM        LEAFSTAFFBOOKMARK

                JOIN        LEAF
                ON          LEAF.COMPANYID = LEAFSTAFFBOOKMARK.COMPANYID
                AND         LEAF.LEAFID = LEAFSTAFFBOOKMARK.LEAFID

                JOIN        LEAFACCESS
                ON          LEAFSTAFFBOOKMARK.COMPANYID = LEAFACCESS.COMPANYID
                AND         LEAFSTAFFBOOKMARK.LEAFID = LEAFACCESS.LEAFID

                JOIN        LEAFTRANSLATE
                ON          LEAFSTAFFBOOKMARK.COMPANYID = LEAFTRANSLATE.COMPANYID
                AND         LEAFSTAFFBOOKMARK.LEAFID = LEAFTRANSLATE.LEAFID

                WHERE       LEAFACCESS.STAFFID              =   '" . $this->getStaffId() . "'
                AND         LEAF.ISACTIVE                   =   1
                AND         LEAFACCESS.LEAFACCESSREADVALUE  =   1
                AND         LEAFTRANSLATE.ISACTIVE          =   1
                AND         LEAFTRANSLATE.LANGUAGEID        =   '" . $this->getLanguageId() . "'
				GROUP BY    LEAFSTAFFBOOKMARK.LEAFID,
				            LEAFTRANSLATE.LEAFNATIVE
                ORDER BY \"total\" DESC
                ) a
						WHERE rownum <= '10' )
						WHERE r >=  '1'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->exceptionMessage($e->getMessage());
        }
        if ($this->q->numberRows($result) > 0) {
            while (($row = $this->q->fetchArray($result)) == true) {
                $data[] = $row;
            }
        }
        return $data;
    }

}

/**
 * Class WallClass
 * Wall Class.Contain Notification  from system or  pre made notification
 * @property mixed translate
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Service
 * @subpackage main
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class WallClass extends ConfigClass {

    /**
     * Connection DatabaseObject
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;

    /**
     * @var string
     */
    public $model;

    /**
     * Constructor
     */
    function __construct() {
        if (isset($_SESSION['companyId'])) {
            $this->setRoleId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        } else {
            $this->setRoleId(7);
        }
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            $this->setStaffId(9);
        }
        if (isset($_SESSION['languageId'])) {
            $this->setLanguageId($_SESSION['languageId']);
        } else {
            $this->setLanguageId(21);
        }
    }

    /**
     * Class Loader
     * @access public
     */
    function execute() {
        parent::__construct();
        $translator = new SharedClass();
        $translator->execute();
        $this->translate = $translator->getDefaultTranslation(); // short because code too long
        if (isset($_SESSION['companyId'])) {
            $this->setCompanyId($_SESSION['companyId']);
        }
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        }
        if (isset($_SESSION['isAdmin'])) {
            $this->setIsAdmin($_SESSION['isAdmin']);
        } else {
            $this->setIsAdmin(0);
        }
    }

    /**
     * Notification come from system and also from group and also from admin
     * Auto link images and href and document.. hu.. superb complex
     * @param int $offset Offset
     * @param int $limit Limit
     * @return array|string Return Notification Array
     * @throws \Exception
     */
    function getNotification($offset = 0, $limit = 3) {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        $data = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
                SELECT  *,
                        `notification`.`executeTime`
                FROM    `notification`
                JOIN   	`staff`
                USING   (`staffId`,`companyId`)
                WHERE   `notification`.`isActive`  =   1
                AND    `notification`.`companyId`='" . $this->getCompanyId() . "'
                ";
            if ($this->getIsAdmin() == 0) {

                $sql .= " AND `staffId` = '" . $this->getStaffId() . "'";
            }
            $sql .= " ORDER BY `notificationId` DESC LIMIT " . $offset . "," . $limit . "  ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                SELECT  *,
                        [notification].[executeTime]
                FROM    [notification]
                JOIN   	[staff]
                ON      [notification].[companyId] = [staff].[companyId]
                AND     [notification].[staffId] = [staff].[staffId]
                WHERE   [notification].[isActive]  =   1
                AND     [notification].[companyId]='" . $this->getCompanyId() . "'
                ";

            if ($this->getIsAdmin() == 0) {

                $sql .= " AND [staff].[staffId] = '" . $this->getStaffId() . "'";
            }
            $sql .= " ORDER BY [notificationId] DESC
             OFFSET " . $offset . " ROWS
            FETCH NEXT " . $limit . " ROWS ONLY ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                SELECT NOTIFICATION.NOTIFICATIONID AS \"notificationId\",
NOTIFICATION.COMPANYID AS \"companyId\",
NOTIFICATION.NOTIFICATIONTYPEID AS \"notificationTypeId\",
NOTIFICATION.STAFFID AS \"staffId\",
NOTIFICATION.NOTIFICATIONFROM AS \"notificationFrom\",
NOTIFICATION.NOTIFICATIONMESSAGE AS \"notificationMessage\",
NOTIFICATION.EXECUTEBY AS \"executeBy\",
NOTIFICATION.EXECUTETIME AS \"executeTime\",
STAFF.STAFFID AS \"staffId\",
STAFF.COMPANYID AS \"companyId\",
STAFF.ROLEID AS \"roleId\",
STAFF.DEPARTMENTID AS \"departmentId\",
STAFF.LANGUAGEID AS \"languageId\",
STAFF.STAFFNAME AS \"staffName\"
FROM    NOTIFICATION
                JOIN   	STAFF
                ON      NOTIFICATION.COMPANYID = STAFF.COMPANYID
                AND     NOTIFICATION.STAFFID = STAFF.STAFFID
WHERE   NOTIFICATION.ISACTIVE  =   1

                AND     NOTIFICATION.COMPANYID='" . $this->getCompanyId() . "'
                AND     ROWNUM = " . $limit . "
                ";
            if ($this->getIsAdmin() == 0) {

                $sql .= " AND STAFF.STAFFID = '" . $this->getStaffId() . "'";
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

            if ($this->q->numberRows($result, $sql) > 0) {
                while (($row = $this->q->fetchArray($result)) == true) {
                    $data[] = $row;
                }
            }
        }
        return $data;
    }

    /**
     * Return Notification Replied
     * @param int $notificationId Notification Primary Key
     * @return array
     * @throws \Exception
     */
    function getNotificationReplied($notificationId) {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        $data = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
                SELECT  *
                FROM    `notificationReply`
                WHERE   `notificationId`='" . $this->strict($notificationId, 'n') . "'
                AND     `isActive`  =   1   ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                SELECT  *
                FROM    [notificationReply]
                WHERE   [notificationId]='" . $this->strict($notificationId, 'n') . "'
                AND     [isActive]  =   1   ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                SELECT  *
                FROM    NOTIFICATIONREPLY
                WHERE   NOTIFICATIONID='" . $this->strict($notificationId, 'n') . "'
                AND     ISACTIVE  =   1   ";
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

            if ($this->q->numberRows($result, $sql) > 0) {
                while (($row = $this->q->fetchArray($result)) == true) {
                    $data[] = $row;
                }
            }
        }
        return $data;
    }

    /**
     * Return Total Notification
     * @access public
     * @return int
     */
    function getTotalNotification() {
        /*
          if ($this->getVendor() == self::MYSQL) {

          $sql = "SET NAMES utf8";
          $this->q->fast($sql);
          }

          try {
          $sql = "
          SELECT  *,
          `notification`.`executeTime`
          FROM    `notification`
          JOIN    `staff`
          ON      `notification`.`notificationFrom` = `staff`.`staffId`
          WHERE   `notification`.`isActive`=1";
          $result = $this->q->fast($sql);
          if ($result) {
          return $this->q->numberRows($result,$sql);
          }
          } catch (\Exception $e) {
          echo json_encode(array("success" => false, "message" => $e->getMessage()));
          exit();
          }
         */
    }

    /**
     * Notification come from system and also from group and also from admin
     * Auto link images and href and document.. hu.. superb complex
     * @param int $offset Offset
     * @param int $limit Limit
     * @return array
     * @throws \Exception
     */
    function getTicket($offset = 0, $limit = 5) {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        $data = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      *,
                        `ticket`.`executeTime`
            FROM        `ticket`
            JOIN        `staff`
            ON          `ticket`.`staffIdFrom` = `staff`.`staffId`
            AND         `ticket`.`companyId`= `staff`.`companyId`
            WHERE       `ticket`.`staffIdTo`='" . $this->getStaffId() . "'
            AND         `ticket`.`isSolve`=0 
            AND         `ticket`.`isActive`=1
            ORDER BY    `ticketId` DESC 
            LIMIT       " . $offset . "," . $limit . "
         ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      *,
                        [ticket].[executeTime]
            FROM        [ticket]
            JOIN        [staff]
            ON          [ticket].[companyId] = [staff].[companyId]
            AND         [ticket].[staffIdFrom] = [staff].[staffId]
            WHERE       [ticket].[staffIdTo]='" . $this->getStaffId() . "'
            AND         [ticket].[isSolve]=0
            AND         [ticket].[isActive]=1
            ORDER BY    [ticketId] DESC
            OFFSET " . $offset . " ROWS
            FETCH NEXT " . $limit . " ROWS ONLY
         ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      STAFF.STAFFID AS \"staffId\",
                        STAFF.STAFFNAME AS \"staffName\",
                        STAFF.COMPANYID AS \"companyId\",
                        STAFF.ROLEID AS \"roleId\",
                        STAFF.DEPARTMENTID AS \"departmentId\",
                        STAFF.LANGUAGEID AS \"languageId\",
                        TICKET.TICKETID AS \"ticketId\",
                        TICKET.STAFFIDFROM AS \"staffIdFrom\",
                        TICKET.STAFFIDTO AS \"staffIdTo\",
                        TICKET.TICKETTEXT AS \"ticketText\",
                        TICKET.TICKETFILE AS \"ticketFile\",
                        TICKET.EXECUTEBY AS \"executeBy\",
                        TICKET.EXECUTETIME AS \"executeTime\"
            FROM        TICKET
            JOIN        STAFF
            ON          TICKET.COMPANYID = STAFF.COMPANYID
            AND         TICKET.STAFFIDFROM = STAFF.STAFFID
            WHERE       TICKET.STAFFIDTO='" . $this->getStaffId() . "'
            AND         TICKET.ISSOLVE=0
            AND         TICKET.ISACTIVE=1
            AND         ROWNUM = 10
            ORDER BY    TICKETID DESC
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
            if ($this->q->numberRows($result, $sql) > 0) {
                while (($row = $this->q->fetchArray($result)) == true) {
                    $data[] = $row;
                }
            }
        }

        return $data;
    }

    /**
     * Return Total Ticket
     * @return int
     * @throws \Exception
     */
    function getTotalTicket() {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $total = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  *,
                    `ticket`.`executeTime`
            FROM    `ticket`
            JOIN    `staff`
            ON      `ticket`.`staffIdFrom` = `staff`.`staffId`
            AND         `ticket`.`companyId`= `staff`.`companyId`
            WHERE   `ticket`.`isSolve`=0 
            AND     `ticket`.`isActive`=1
            AND     `ticket`.`companyId`='" . $this->getCompanyId() . "'";
            if ($this->getIsAdmin() == 1) {
                
            } else {
                $sql .= "
                AND `ticket`.`staffIdTo`='" . $this->getStaffId() . "'";
            }
            $sql .= "
            ORDER BY `ticketId` DESC  ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  *,
                    [ticket].[executeTime]
            FROM    [ticket]
            JOIN    [staff]
            ON      [ticket].[staffIdFrom] = [staff].[staffId]
            WHERE   [ticket].[isSolve]=0
            AND     [ticket].[isActive]=1
            AND     [ticket].[companyId]='" . $this->getCompanyId() . "'";
            if ($this->getIsAdmin() == 1) {
                
            } else {
                $sql .= "
                AND [ticket].[staffIdTo]='" . $this->getStaffId() . "'";
            }
            $sql .= "
            ORDER BY    [ticketId] DESC  ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  STAFF.STAFFID AS \"staffId\",
                        STAFF.STAFFNAME AS \"staffName\",
                        STAFF.COMPANYID AS \"companyId\",
                        STAFF.ROLEID AS \"roleId\",
                        STAFF.DEPARTMENTID AS \"departmentId\",
                        STAFF.LANGUAGEID AS \"languageId\",
                        TICKET.TICKETID AS \"ticketId\",
                        TICKET.STAFFIDFROM AS \"staffIdFrom\",
                        TICKET.STAFFIDTO AS \"staffIdTo\",
                        TICKET.TICKETTEXT AS \"ticketText\",
                        TICKET.TICKETFILE AS \"ticketFile\",
                        TICKET.EXECUTEBY AS \"executeBy\",
                        TICKET.EXECUTETIME AS \"executeTime\"
            FROM    TICKET
            JOIN    STAFF
            ON      TICKET.STAFFIDFROM = STAFF.STAFFID
            WHERE   TICKET.ISSOLVE=0
            AND     TICKET.ISACTIVE=1
            AND     TICKET.COMPANYID  ='" . $this->getCompanyId() . "'";
            if ($this->getIsAdmin() == 1) {
                
            } else {
                $sql .= "
                AND TICKET.STAFFIDTO =   '" . $this->getStaffId() . "'";
            }
            $sql .= "
            ORDER BY    TICKETID DESC  ";
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
            $total = intval($this->q->numberRows($result, $sql));
        }

        return $total;
    }

    /**
     * Return Ticket Replied
     * @param int $ticketId ticket Primary Key
     * @param int $staffIdTo staff
     * @return mixed
     * @throws \Exception
     */
    function getTicketReplied($ticketId, $staffIdTo) {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }

        $data = array();
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
                SELECT      * 
                FROM        `ticket` 
                WHERE       `isSolve`=0 
                AND         `isActive`=1
                AND         `ticketId`='" . $this->strict($ticketId, 'numeric') . "'
                AND         `staffIdTo`='" . $this->strict($staffIdTo, 'numeric') . "'
                ORDER BY    `ticketId` DESC ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                SELECT      *
                FROM        [ticket]
                WHERE       [isSolve]=0
                AND         [isActive]=1
                AND         [ticketId]='" . $this->strict($ticketId, 'numeric') . "'
                AND         [staffIdTo]='" . $this->strict($staffIdTo, 'numeric') . "'
                ORDER BY    [ticketId] DESC ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                SELECT      *
                FROM        TICKET
                WHERE       ISSOLVE=0
                AND         ISACTIVE=1
                AND         TICKETID='" . $this->strict($ticketId, 'numeric') . "'
                AND         STAFFIDTO='" . $this->strict($staffIdTo, 'numeric') . "'
                ORDER BY    TICKETID DESC ";
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
            if ($this->q->numberRows($result, $sql) > 0) {
                while (($row = $this->q->fetchArray($result)) == true) {
                    $data[] = $row;
                }
            }
        }
        return $data;
    }

    /**
     * Return Time Since like facebook future..
     * @param mixed $since
     * @return string
     */
    function time_since($since) {
        $count = 0;
        $name = null;
        $chunks = array(
            array(60 * 60 * 24 * 365, 'year'),
            array(60 * 60 * 24 * 30, 'month'),
            array(60 * 60 * 24 * 7, 'week'),
            array(60 * 60 * 24, 'day'),
            array(60 * 60, 'hour'),
            array(60, 'minute'),
            array(1, 'second')
        );

        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];
            if (($count = floor($since / $seconds)) != 0) {
                break;
            }
        }

        $print = ($count == 1) ? '1 ' . $name : "$count {$name}s";
        return $print;
    }

    /**
     * Create
     * @see config::read()
     */
    public function create() {
        
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

/**
 * Class SpotlightClass
 * Spotlight Class.Contain Notification  from system or  pre made notification
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Service
 * @subpackage main
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class SpotlightClass extends ConfigClass {

    const GENERAL_LEDGER = 'GLLDGR';
    const SPLIT_ACCOUNT = 'GLCOASLC';
    const MERGE_ACCOUNT = 'GLCOAMER';
    const VENDOR = 'VNDR';
    // Chart Of Account Constant
    const SUPPLIER = 'SPLR';
    const INSURANCE = 'ISRC';
    const DEBTOR = 'DBTS';
    // Business Partner Constant
    const CREDITOR = 'CBTS';
    const STAFF = 'STAFF';
    const SOLICITOR = 'SLTR';
    const FAST_CUSTOMER = 'FSCS';
    const SHIPPING_COMPANY = 'SPCP';
    const SALES_ORDER = 'SLINV';
    const PAYMENT_VOUCHER = 'CBPV';
    const COLLECTION = 'CBCL';
    const BUSINESS_PARTNER_LEDGER = '';
    // end business partner constant
    // cashbook constant
    const CASH_FLOW = 'CBCF';
    const INTER_BANK = 'CBBNKTF';
    const EMPLOYEE_INFORMATION = 'HREM';
    const EMPLOYEE_HOLIDAY = 'HRLV';
    // end cashbook constant
    // Bank Constant
    const EMPLOYEE_WORK_ORDER = 'HRWO';

    /**
     * Connection DatabaseObject
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;
    // Employee Constant
    /**
     * Translate Label
     * @var array
     */
    public $t;

    /**
     * @var string
     */
    public $model;

    /**
     * Spotlight Total
     * @var int
     */
    private $spotlightTotal;

    /**
     * Constructor
     */
    public function __construct() {
        if (isset($_SESSION['companyId'])) {
            $this->setRoleId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        } else {
            $this->setRoleId(7);
        }
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            $this->setStaffId(9);
        }
        if (isset($_SESSION['languageId'])) {
            $this->setLanguageId($_SESSION['languageId']);
        } else {
            $this->setLanguageId(21);
        }
    }

    /**
     * Class Loader
     */
    public function execute() {
        parent::__construct();
        if (isset($_SESSION['companyId'])) {
            $this->setCompanyId($_SESSION['companyId']);
        }
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        }
        if (isset($_SESSION['languageId'])) {
            $this->setLanguageId($_SESSION['languageId']);
        }
        $translator = new SharedClass();
        $translator->execute();

        $this->t = $translator->getDefaultTranslation(); // short because code too long
    }

    /**
     * Search Engine..  like spotlight.. Apple don't sue me.. :P
     * @param string $string Wild Card String Search
     * @todo search document also... fuh fuh.. inside document contain xxx xxx xx. real dms
     * @return string
     * @throws \Exception
     */
    public function spotlight($string) {
        // have to check  if the person available or not for the subscription of spotlight
        //message
        //leaf
        $dataLeaf = $this->searchLeaf($string);
        $str = "";
        if (is_array($dataLeaf)) {
            $totalLeaf = count($dataLeaf);
            $this->setSpotLightTotal($totalLeaf);
            if ($totalLeaf > 0) {
                //   $str .= "<li class=\"message-preview\">&nbsp;&nbsp;<span class=\"message\">&nbsp;&nbsp;<img src=\"./images/icons/leaf.png\">" . $this->t['applicationTextLabel'] . "</span></li>";
                $str .= "<li class=\"divider message-preview\">&nbsp;</li>
					";
                foreach ($dataLeaf as $rowLeaf) {
                    $str .= "
				<li class=\"message-preview\">&nbsp;&nbsp;<span class=\"message\">&nbsp;&nbsp;<img src=\"./images/icons/leaf.png\">&nbsp;&nbsp;<a href=\"javascript:void(0)\"
							onClick=\"loadLeft(" . intval($rowLeaf['leafId']) . ",'" . $this->getSecurityToken(
                            ) . "');hideSpotlight()\" style=\"color:black\">" . $rowLeaf['leafNative'] . "</a></span>
				</li><li class=\"divider message-preview\">&nbsp;</li>";
                }
                $str .= "
					<li class=\"divider message-preview\">&nbsp;</li>";
            }
        }
        if ($_SESSION['isGeneralLedger'] == 1) {
            $dataChartOfAccounts = $this->searchChartOfAccounts($string);

            if (is_array($dataChartOfAccounts)) {
                $totalChartOfAccounts = count($dataChartOfAccounts);
                $this->setSpotLightTotal($totalChartOfAccounts);
                if ($totalChartOfAccounts > 0) {
                    $str .= "
					<li class=\"message-preview\">
						<dl>
							<dt>&nbsp;&nbsp;<img src=\"./images/icons/chart.png\">" . $this->t['chartOfAccountTextLabel'] . "</dt>";

                    foreach ($dataChartOfAccounts as $row) {
                        $str .= "
						<dd>" . $row['chartOfAccountTitle'] . "&nbsp;<br>
							" . $row['chartOfAccountNumber'] . "
							<ol>
								<li class=\"message-preview\"><span class=\"message\">&nbsp;&nbsp;<a href=\"javascript:void(0)\"
										onClick=\"loadLeft(" . intval(
                                        $this->getLeafIdFromCode(self::GENERAL_LEDGER)
                                ) . ",'" . $this->getSecurityToken(
                                ) . "');hideSpotlight()\">" . $this->t['generalLedgerTextLabel'] . "</a></span></li>
								<li class=\"message-preview\"><span class=\"message\"><a href=\"javascript:void(0)\"
										onClick=\"loadLeft(" . intval(
                                        $this->getLeafIdFromCode(self::SPLIT_ACCOUNT)
                                ) . ",'" . $this->getSecurityToken(
                                ) . "');hideSpotlight()\">" . $this->t['splitAccountTextLabel'] . "</a></span></li>
								<li class=\"message-preview\"><span class=\"message\"><a href=\"javascript:void(0)\"
										onClick=\"loadLeft(" . intval(
                                        $this->getLeafIdFromCode(self::MERGE_ACCOUNT)
                                ) . ",'" . $this->getSecurityToken(
                                ) . "');hideSpotlight()\">" . $this->t['mergeAccountTextLabel'] . "</a></span></li>
							</ol>
						</dd> ";
                    }
                    $str .= "
						</dl>
					</li><li class=\"divider\"></li>";
                }
            }
        }
        // business partners
        if ($_SESSION['isAccountReceivable'] == 1 || $_SESSION['isAccountPayable'] == 1) {
            $dataBusinessPartners = $this->searchBusinessPartners($string);

            if (is_array($dataBusinessPartners)) {
                $totalBusinessPartners = count($dataBusinessPartners);
                $this->setSpotLightTotal($totalBusinessPartners);
                if ($totalBusinessPartners > 0) {
                    $str .= "
					<li class=\"message-preview\"><span class=\"message\">
						<dl>
							<dt>&nbsp;&nbsp;<img src=\"./images/icons/user-business.png\">&nbsp;" . $this->t['businessPartnerTextLabel'] . "</dt>";

                    foreach ($dataBusinessPartners as $rowBusinessPartner) {
                        $str .= "
						<dd><strong>" . strtoupper($rowBusinessPartner['businessPartnerCode']) . " - " . strtoupper(
                                        $rowBusinessPartner['businessPartnerCompany']
                                ) . " (" . strtoupper($rowBusinessPartner['businessPartnerRegistrationNumber']) . ")</strong>
								<ol>
								<li class=\"message-preview\"><span class=\"message\"><a href=\"javascript:void(0)\"
									   onClick=\"loadLeft(" . intval(
                                        $this->getLeafIdFromCode(self::SALES_ORDER)
                                ) . ",'" . $this->getSecurityToken(
                                ) . "');hideSpotlight()\">" . $this->t['newSalesOrderTextLabel'] . "</a></span></li>
								<li class=\"message-preview\"><span class=\"message\"><a href=\"javascript:void(0)\"
									   onClick=\"loadLeft(" . intval(
                                        $this->getLeafIdFromCode(self::COLLECTION)
                                ) . ",'" . $this->getSecurityToken(
                                ) . "');hideSpotlight()\">" . $this->t['newCollectionTextLabel'] . "</a></span></li>
								<li class=\"message-preview\"><span class=\"message\"><a href=\"javascript:void(0)\"
									   onClick=\"loadLeft(" . intval(
                                        $this->getLeafIdFromCode(self::PAYMENT_VOUCHER)
                                ) . ",'" . $this->getSecurityToken(
                                ) . "');hideSpotlight()\">" . $this->t['paymentVoucherTextLabel'] . "</a></span></li>
								<li class=\"message-preview\"><span class=\"message\"><a href=\"javascript:void(0)\"
									   onClick=\"loadLeft(" . intval(
                                        $this->getLeafIdFromCode(self::BUSINESS_PARTNER_LEDGER)
                                ) . ",'" . $this->getSecurityToken(
                                ) . "');hideSpotlight()\">" . $this->t['ledgerTextLabel'] . "</a></span></li>
							</ol>
						</dd>";
                    }
                    $str .= "
						</dl></span>
					</li><li class=\"divider\"></li>";
                }
            }
        }
        if ($_SESSION['isGeneralLedger'] == 1) {
            // bank
            $dataBank = $this->searchBank($string);

            if (is_array($dataBank)) {
                $totalBank = count($dataBank);
                $this->setSpotLightTotal($totalBank);
                if ($totalBank > 0) {
                    $str .= "
					<li class=\"message-preview\">
						<dl>
							<dt>&nbsp;&nbsp;<img src='./images/icons/bank.png'>" . $this->t['bankTextLabel'] . "</dt>";
                    foreach ($dataBank as $rowBank) {
                        $str .= "
						<dd>" . $rowBank['bankDescription'] . "
							<ol>
								<li class=\"message-preview\"><span class=\"message\"><a href=\"javascript:void(0)\"
										onClick=\"loadLeft(" . intval(
                                        $this->getLeafIdFromCode(self::CASH_FLOW)
                                ) . ",'" . $this->getSecurityToken(
                                ) . "');hideSpotlight()\">" . $this->t['cashFlowTextLabel'] . "</a></span></li>
								<li class=\"message-preview\"><span class=\"message\"><a href=\"javascript:void(0)\"
										onClick=\"loadLeft(" . intval(
                                        $this->getLeafIdFromCode(self::INTER_BANK)
                                ) . ",'" . $this->getSecurityToken(
                                ) . "');hideSpotlight()\">" . $this->t['interBankTransferTextLabel'] . "</a></span></li>
								<li class=\"divider\"></li>
							</ol>
						</dd>";
                    }
                    $str .= "
						</dl>
					</li><li class=\"divider\"></li>";
                }
            }
        }
        if ($_SESSION['isHumanResource'] == 1) {
            // Staff / Employee
            $dataEmployee = $this->searchStaff($string);

            if (is_array($dataEmployee)) {
                $totalEmployee = count($$dataEmployee);
                $this->setSpotLightTotal($totalEmployee);
                if ($totalEmployee > 0) {
                    $str .= "
					<li class=\"message-preview\">
						<dl>
							<dt>&nbsp;&nbsp;<img src='./images/icons/bank.png'>" . $this->t['bankTextLabel'] . "</dt>";
                    foreach ($dataEmployee as $rowEmployee) {
                        $str .= "
						<dd>" . $rowEmployee['employeeFirstName'] . "
							<ol>
								<li class=\"message-preview\"><span class=\"message\"><a href=\"javascript:void(0)\"
										onClick=\"loadLeft(" . intval(
                                        $this->getLeafIdFromCode(self::EMPLOYEE_INFORMATION)
                                ) . ",'" . $this->getSecurityToken(
                                ) . "');hideSpotlight()\">" . $this->t['employeeInformationTextLabel'] . "</a></span></li>
								<li class=\"message-preview\"><span class=\"message\"><a href=\"javascript:void(0)\"
										onClick=\"loadLeft(" . intval(
                                        $this->getLeafIdFromCode(self::EMPLOYEE_HOLIDAY)
                                ) . ",'" . $this->getSecurityToken(
                                ) . "');hideSpotlight()\">" . $this->t['employeeHolidayTextLabel'] . "</a></span></li>
								<li class=\"message-preview\"><span class=\"message\"><a href=\"javascript:void(0)\"
										onClick=\"loadLeft(" . intval(
                                        $this->getLeafIdFromCode(self::EMPLOYEE_WORK_ORDER)
                                ) . ",'" . $this->getSecurityToken(
                                ) . "');hideSpotlight()\">" . $this->t['employeeWorkOrderTextLabel'] . "</a></span></li>
							<li class=\"divider\"></li>
							</ol>
						</dd>";
                    }
                    $str .= "
						</dl>
					</li><li class=\"divider\"></li>";
                }
            }
        }
        return $str;
    }

    /**
     * Return Leaf Information.Limit 2 per search record.. scrolling like google mail quite annoying
     * @param string $string Wild Card String Search
     * @return array
     * @throws \Exception
     */
    private function searchLeaf($string) {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        $data = array();

        if ($this->getVendor() == self::MYSQL) {

            $sql = "
                SELECT  `leaf`.`leafId`,
                        `leaftranslate`.`leafNative`
                FROM    `leaf`
                JOIN    `leaftranslate`
                USING   (`companyId`,`leafId`)
                JOIN    `leafaccess`
                USING   (`companyId`,`leafId`)
				JOIN	`applicationaccess`
				USING	(`companyId`,`applicationId`)
                WHERE   `leaf`.`companyId`              				=   '" . $this->getCompanyId() . "'
                AND     `leafaccess`.`staffId`          				=   '" . $this->getStaffId() . "'
				AND     `leafaccess`.`leafAccessReadValue`				=   '1'
                AND     `leaf`.`isActive`               				=   1
                AND     `leaftranslate`.`languageId`    				=   '" . $this->getLanguageId() . "'
				AND     `applicationaccess`.`roleId`    				=   '" . $this->getRoleId() . "'
				AND     `applicationaccess`.`applicationAccessValue`    =   '1'
                AND     `leaftranslate`.`leafNative`
                LIKE    '%" . $this->strict($string, 'w') . "%'
        ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                SELECT  [leaf].[leafId],
                        [leafTranslate].[leafNative]
                FROM    [leaf]
                JOIN    [leafTranslate]
                ON      [leaf].[companyId]              =   [leafTranslate].[companyId]
                AND     [leaf].[leafId]                 =   [leafTranslate].[companyId]
                JOIN    [leafAccess]
                ON      [leaf].[companyId]              =   [leafAccess].[companyId]
                AND     [leaf].[leafId]                 =   [leafAccess].[companyId]
                WHERE   [leaf].[companyId]              =   '" . $this->getCompanyId() . "'
                AND     [leafAccess].[staffId]          =   '" . $this->getStaffId() . "'
                AND     [leaf].[isActive]               =   1
                AND     [leafTranslate].[languageId]    =   '" . $this->getLanguageId() . "'
                AND     [leafTranslate].[leafNative]
                LIKE    '%" . $this->strict($string, 'w') . "%'
        ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                SELECT  LEAFID,
                        LEAFNATIVE
                FROM    LEAF
                JOIN    LEAFTRANSLATE
                ON      LEAF.COMPANYID              =   LEAFTRANSLATE.COMPANYID
                AND     LEAF.LEAFID                 =   LEAFTRANSLATE.COMPANYID
                JOIN    LEAFACCESS
                ON      LEAF.COMPANYID              =   LEAFACCESS.COMPANYID
                AND     LEAF.LEAFID                 =   LEAFACCESS.COMPANYID
                WHERE   LEAF.COMPANYID              =   '" . $this->getCompanyId() . "'
                AND     LEAFACCESS.STAFFID          =   '" . $this->getStaffId() . "'
                AND     LEAF.ISACTIVE               =   1
                AND     LEAFTRANSLATE.LANGUAGEID    =   '" . $this->getLanguageId() . "'
                AND     LEAFTRANSLATE.LEAFNATIVE
                LIKE    '%" . $this->strict($string, 'w') . "%'";
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
            if ($this->q->numberRows($result, $sql) > 0) {
                while (($row = $this->q->fetchArray($result)) == true) {
                    $data[] = $row;
                }
            }
        }
        return $data;
    }

    /**
     * Search Chart Of Accounts.Only Active Accounts Appear.
     * @param string $string Wild Card String Search
     * @return array
     * @throws \Exception
     */
    private function searchChartOfAccounts($string) {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        $data = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
                SELECT  `chartOfAccountId`,
                        `chartOfAccountTitle`,
                        `chartOfAccountNumber`
                FROM    `chartofaccount`
                WHERE   `chartofaccount`.`companyId`    =   '" . $this->getCompanyId() . "'
                AND     `isActive`  =   1
                AND     `chartOfAccountTitle`
                LIKE    '%" . $this->strict($string, 'w') . "%'
                OR      `chartOfAccountNumber`
                LIKE    '%" . $this->strict($string, 'w') . "%'
				LIMIT 100
                ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                SELECT  TOP 100 [chartOfAccountId],
                        [chartOfAccountTitle],
                        [chartOfAccountNumber]
                FROM    [chartOfAccount]
                WHERE   [chartOfAccount].[companyId]    =   '" . $this->getCompanyId() . "'
                AND     [isActive]  =   1
                AND     [chartOfAccountTitle]
                LIKE    '%" . $this->strict($string, 'w') . "%'
                OR      [chartOfAccountNumber]
                LIKE    '%" . $this->strict($string, 'w') . "%'
                ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                SELECT  CHARTOFACCOUNTID,
                        CHARTOFACCOUNTTITLE,
                        CHARTOFACCOUNTNUMBER
                FROM    CHARTOFACCOUNT
                WHERE   CHARTOFACCOUNT.COMPANYID    =   '" . $this->getCompanyId() . "'
                AND     ISACTIVE  =   1
                AND     CHARTOFACCOUNTTITLE
                LIKE    '%" . $this->strict($string, 'w') . "%'
                OR      CHARTOFACCOUNTNUMBER
                LIKE    '%" . $this->strict($string, 'w') . "%'
				AND ROWNUM=100";
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
            if ($this->q->numberRows($result, $sql) > 0) {
                while (($row = $this->q->fetchArray($result)) == true) {
                    $data[] = $row;
                }
                return $data;
            }
        }
        return $data;
    }

    /**
     * Return Leaf Primary Key From Code
     * @param string $leafCode Code
     * @return int $leafId Leaf Primary Key
     * @throws \Exception
     */
    private function getLeafIdFromCode($leafCode) {
        $leafId = null;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT `leafId`
			FROM   `leaf`
			WHERE  `companyId`			=	'" . $this->getCompanyId() . "'
			AND	   `leafCode`			=	'" . $leafCode . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT [leafId]
			FROM   [leaf]
			WHERE  [companyId]			=	'" . $this->getCompanyId() . "'
			AND	   [leafCode]			=	'" . $leafCode . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT LEAFID
			FROM   LEAF
			WHERE  COMPANYID			=	'" . $this->getCompanyId() . "'
			AND	   LEAFCODE				=	'" . $leafCode . "'";
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
            $leafId = $row['leafId'];
        }
        return $leafId;
    }

    /**
     * Return Business Partner
     * @param string $string Wild Card String search
     * @return array
     * @throws \Exception
     */
    private function searchBusinessPartners($string) {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        $data = array();

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
                SELECT  `businessPartnerId`,
						`businessPartnerCode`,
						`businessPartnerRegistrationNumber`,
                        `businessPartnerCompany`
                FROM    `businesspartner`
                WHERE   `businesspartner`.`companyId`   =   '" . $this->getCompanyId() . "'
                AND    ( `businessPartnerCompany`
				LIKE    '%" . $this->strict($string, 'w') . "%'
				OR  	`businessPartnerCode`
				LIKE    '%" . $this->strict($string, 'w') . "%'
				OR    	`businessPartnerRegistrationNumber`
				LIKE    '%" . $this->strict($string, 'w') . "%')
				LIMIT 100
                ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                SELECT  TOP 100 [businessPartner].[businessPartnerId],
						[businessPartner].[businessPartnerCode],
						[businessPartner].[businessPartnerRegistrationNumber],
                        [businessPartner].[businessPartnerCompany]
                FROM    [businessPartner]
                WHERE   [businessPartner].[companyId]    =   '" . $this->getCompanyId() . "'
                AND     [businessPartner].[businessPartnerCompany]
				LIKE    '%" . $this->strict($string, 'w') . "%'
				OR     [businessPartner].[businessPartnerCode]
				LIKE    '%" . $this->strict($string, 'w') . "%'
				OR     [businessPartner].[businessPartnerRegistrationNumber]
				LIKE    '%" . $this->strict($string, 'w') . "%'
                ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                SELECT  BUSINESSPARTNERID,
						BUSINESSPARTNERCODE,
						BUSINESSPARTNERREGISTRTIONNUMBER,
                        BUSINESSPARTNERCOMPANY
                FROM    BUSINESSPARTNER
                WHERE   BUSINESSPARTNERCOMPANYID    =   '" . $this->getCompanyId() . "'
                AND     (BUSINESSPARTNERCOMPANY
                LIKE    '%" . $this->strict($string, 'w') . "%'
				OR     BUSINESSPARTNERCODE
                LIKE    '%" . $this->strict($string, 'w') . "%'
				OR     BUSINESSPARTNERREGISTRTIONNUMBER
                LIKE    '%" . $this->strict($string, 'w') . "%')
				AND ROWNUM=100
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
            $total = intval($this->q->numberRows($result, $sql));
            if ($total > 0) {
                while (($row = $this->q->fetchArray($result)) == true) {
                    $data[] = $row;
                }
            }
        }
        return $data;
    }

    /**
     * Search Bank
     * @param string $string Wild Card String Search
     * @return array
     * @throws \Exception
     */
    private function searchBank($string) {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        $data = array();

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
                SELECT  `bankId`,
                        `bankDescription`
                FROM    `bank`
                WHERE   `bank`.`companyId` 			= 		'" . $this->getCompanyId() . "'
                AND     `bank`.`bankCode` 			LIKE    '%" . $this->strict($string, 'w') . "%'
                OR      `bank`.`bankDescription`	LIKE	'%" . $this->strict($string, 'w') . "%'
				LIMIT 100
                ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                SELECT  TOP 100 [bankId],
                        [bankDescription]
                FROM    [bank]
                WHERE   [bank].[companyId] 			= 		'" . $this->getCompanyId() . "'
                AND     [bank].[bankCode] 			LIKE    '%" . $this->strict($string, 'w') . "%'
                OR      [bank].[bankDescription] 	LIKE    '%" . $this->strict($string, 'w') . "%'
                ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                SELECT  BANKID AS \"bankId\",
                        BANKDESCRIPTION  AS \"bankDescription\"
                FROM    BANK
                WHERE   BANK.COMPANYID 			= 		'" . $this->getCompanyId() . "'
                AND     BANK.BANKCODE 			LIKE    '%" . $this->strict($string, 'w') . "%'
                OR      BANK. BANKDESCRIPTION 	LIKE    '%" . $this->strict($string, 'w') . "%'
				AND ROWNUM=100
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
            if ($this->q->numberRows($result, $sql) > 0) {
                while (($row = $this->q->fetchArray($result)) == true) {
                    $data[] = $row;
                }
            }
        }
        return $data;
    }

    /**
     * Search Staff
     * @param string $string Wildcard string search
     * @return array
     * @throws \Exception
     */
    private function searchStaff($string) {
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        $data = array();
        if ($this->getVendor() == self::MYSQL) {

            $sql = "
                SELECT  `businesspartner`.`businessPartnerId`,
                        `businesspartner`.`businessPartnerCompany`
                FROM    `businesspartner`
                JOIN    `businesspartnercategory`
                USING   (`companyId`,`businessPartnerCategoryId`)
                WHERE   `businesspartner`.`companyId`                   =   '" . $this->getCompanyId() . "'
                AND     `businesspartnercategory`.`businessPartnerCategoryCode`   =  '" . self::STAFF . "'
                AND     `businessPartnerCompany`
                LIKE    '%" . $this->strict($string, 'w') . "%'
				LIMIT 100
                ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                SELECT  TOP 100 [businessPartner].[businessPartnerId],
                        [businessPartner].[businessPartnerCompany],
                FROM    [businessPartner]
                JOIN    [businessPartnerCategory]
                AND     [businessPartner].[companyId]                   =   [businessPartnerCategory].[companyId]
                AND     [businessPartner].[businessPartnerCategoryId]   =   [businessPartnerCategory].[businessPartnerCategoryId]
                WHERE   [businessPartner].[companyId]                   =   '" . $this->getCompanyId() . "'
                AND     [businessPartnerCategory].[businessPartnerCategoryCode] =   '" . self::STAFF . "'
                AND     [businessPartnerCompany]
                LIKE    '%" . $this->strict($string, 'w') . "%'
                ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                SELECT  BUSINESSPARTNER.BUSINESSPARTNERID,
                        BUSINESSPARTNER.BUSINESSPARTNERCOMPANY
                FROM    BUSINESSPARTNER
                JOIN    BUSINESSPARTNERCATEGORY
                ON      BUSINESSPARTNER.COMPANYID                   =   BUSINESSPARTNERCATEGORY.COMPANYID
                ON      BUSINESSPARTNER.BUSINESSPARTNERCATEGORYID           =   BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYID
                WHERE   BUSINESSPARTNER.COMPANYID                   =   '" . $this->getCompanyId() . "'
                AND     BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYCODE  =   '" . self::STAFF . "'
                AND     BUSINESSPARTNERCOMPANY
                LIKE    '%" . $this->strict($string, 'w') . "%'
				AND ROWNUM=100
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
            if ($this->q->numberRows($result, $sql) > 0) {
                while (($row = $this->q->fetchArray($result)) == true) {
                    $data[] = $row;
                }
            }
        }
        return $data;
    }

    /**
     * Return Spotlight total
     * @return int
     */
    public function getSpotlightTotal() {
        return $this->spotlightTotal;
    }

    /**
     * Set Spotlight Total
     * @param int $value Total
     * @return $this
     */
    public function setSpotLightTotal($value) {
        $this->spotlightTotal = $this->spotlightTotal + intval($value);
        return $this;
    }

    /**
     * Create
     * @see config::read()
     */
    public function create() {
        
    }

    /**
     * Reads
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

    /**
     * Search Customer
     * @param string $string Wild Card String Search
     * @return array
     * @throws \Exception
     * @deprecated
     */
    private function searchCustomers($string) {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        $data = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
                SELECT   *
                FROM    `businesspartner`
                JOIN    `businesspartnercategory`
                USING   (`companyId`,`businessPartnerId`)
                WHERE   `businesspartner`.`companyId`='" . $this->getCompanyId() . "'
                AND     `businesspartnercategory`.`businessPartnerCategoryCode` NOT IN ('STAFF')
                AND     `businessPartnerCompany`
                LIKE    '%" . $this->strict($string, 'w') . "%'
                ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                SELECT  *
                FROM    [businessPartner]
                JOIN    [businessPartnerCategory]
                AND     [businessPartner].[companyId]                   =   [businessPartnerCategory].[companyId]
                AND     [businessPartner].[businessPartnerId]           =   [businessPartnerCategory].[businessPartnerId]
                WHERE   [businessPartnerCategory].[businessPartnerCategoryCode]  NOT IN ('STAFF')
                AND     [businessPartnerCompany]
                LIKE    '%" . $this->strict($string, 'w') . "%'
                ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                SELECT  *
                FROM    BUSINESSPARTNER
                JOIN    BUSINESSPARTNERCATEGORY
                ON      BUSINESSPARTNER.COMPANYID                   =   BUSINESSPARTNERCATEGORY.COMPANYID
                ON      BUSINESSPARTNER.BUSINESSPARTNERID           =   BUSINESSPARTNERCATEGORY.BUSINESSPARTNERID
                WHERE   BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYCODE   NOT IN ('STAFF')
                AND     BUSINESSPARTNERCOMPANY
                LIKE    '%" . $this->strict($string, 'w') . "%'
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
            if ($this->q->numberRows($result, $sql) > 0) {
                while (($row = $this->q->fetchArray($result)) == true) {
                    $data[] = $row;
                }
            }
        }
        return $data;
    }

}

?>
