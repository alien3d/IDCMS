<?php

namespace Core\System\Security\ApplicationAccess\Service;

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

/**
 * Class ApplicationAccessService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Security\ApplicationAccess\Service
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ApplicationAccessService extends ConfigClass {

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
        if (isset($_SESSION['companyId'])) {
            $this->setCompanyId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
    }

    /**
     * Return Application
     * @return array|string
     */
    public function getApplication() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `applicationId`,
                     `applicationEnglish`
         FROM        `application`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [applicationId],
                     [applicationEnglish]
         FROM        [application]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      APPLICATIONID AS \"applicationId\",
                     APPLICATIONENGLISH AS \"applicationEnglish\"
         FROM        APPLICATION
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
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['applicationId'] . "'>" . $row['applicationEnglish'] . "</option>";
                } else if ($this->getServiceOutput() == 'html') {
                    $items[] = $row;
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
        return $items;
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
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [roleId],
                     [roleDescription]
         FROM        [role]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      ROLEID AS \"roleId\",
                     ROLEDESCRIPTION AS \"roleDescription\"
         FROM        ROLE
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
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['roleId'] . "'>" . $row['roleDescription'] . "</option>";
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
        return $items;
    }

    /**
     * Create / Update Role Access Based On Application 
     * @param int $applicationId
     * @return void
     */
    public function setCreateRoleAccess($applicationId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT	`roleId`	 
			FROM 	`role` 
			WHERE 	`companyId`='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT	[roleId] 
			FROM 	[role]
			WHERE 	[companyId]='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT	ROLEID	AS \"roleId\"
			FROM 	ROLE
			WHERE 	COMPANYID='" . $this->getCompanyId() . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $sqlString = null;
        if ($result) {
            while (($row = $this->q->fetchArray($result)) == true) {
                $roleId = $row['roleId'];
                $sqlString.="('" . $this->getCompanyId() . "','" . $applicationId . "','" . $roleId . "',0,'" . $this->getStaffId() . "'," . $this->getExecuteTime() . "),";
            }
        }
        $sqlString.=substr($sqlString, 0, -1);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "INSERT INTO `applicationaccess`( `companyId`, `applicationId`, `roleId`, `applicationAccessValue`, `executeBy`, `executeTime`) VALUES " . $sqlString;
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "INSERT INTO [applicationAccess]([companyId], [applicationId],[roleId],[applicationAccessValue],[executeBy],[executeTime]) VALUES " . $sqlString;
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "INSERT INTO APPLICATIONACCESS( COMPANYID,APPLICATIONID,ROLEID,APPLICATIONACCESSVALUE, EXECUTEBY, EXECUTETIME) VALUES " . $sqlString;
        }
        // insert sql statement by batch
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Delete Role Access Based On Application 
     * @param int $applicationId
     * @return void
     */
    public function setDeleteRoleAccess($applicationId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            DELETE 
            FROM 	`applicationaccess` 
            WHERE 	`companyId`		=	'" . $this->getCompanyId() . "'
            AND		`applicationId`	=	'" . $applicationId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            DELETE 
            FROM 	[applicationAccess]
            WHERE 	[companyId]		=	'" . $this->getCompanyId() . "'
            AND		[applicationId]	=	'" . $applicationId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            DELETE
            FROM 	APPLICATIONACCESS
            WHERE 	COMPANYID		=	'" . $this->getCompanyId() . "'
            AND		APPLICATIONID	=	'" . $applicationId . "'";
        }
        try {
            $this->q->update($sql);
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