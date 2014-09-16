<?php

namespace Core\System\Security\FolderAccess\Service;

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

/**
 * Class FolderAccessService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Security\FolderAccess\Service
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class FolderAccessService extends ConfigClass {

    /**
     * Connection to the database
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;

    /**
     * Translate Label
     * @var mixed
     */
    public $t;

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
                 WHERE       `isActive`=1
                 AND         `companyId`='" . $this->getCompanyId() . "'
                 ORDER BY    `isDefault`;";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
                 SELECT      [roleId],
                             [roleDescription]
                 FROM        [role]
                 WHERE       [isActive]=1
                 AND         [companyId]= '" . $this->getCompanyId() . "'
                 ORDER BY    [isDefault]";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
                 SELECT      ROLEID             AS  \"roleId\",
                             ROLEDESCRIPTION    AS  \"roleDescription\"
                 FROM        ROLE
                 WHERE       ISACTIVE   =   1
                 AND         COMPANYID  = '" . $this->getCompanyId() . "'
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
            $items = array();
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['roleId'] . "'>" . $d . " " . $row['roleDescription'] . "</option>";
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
                return "<option value=''>" . $this->t['pleaseSelectTextLabel'] . "</option>" . $str;
            } else {
                return "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
            }
        } else {
            if ($this->getServiceOutput() == 'html') {
                return $items;
            }
        }
        // fake return
        return $items;
    }

    /**
     * Return Application
     * @return string
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
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      [applicationId],
                        [applicationEnglish]
            FROM        [application]
            WHERE       [isActive]  =   1
            AND         [companyId] =   '" . $this->getCompanyId() . "'
            ORDER BY    [isDefault]";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      APPLICATIONID AS AS \"applicationId\",
                        APPLICATIONENGLISH AS \"applicationEnglish\"
            FROM        APPLICATION
            WHERE       ISACTIVE        =   1
            AND         COMPANYID       =   '" . $this->getCompanyId() . "'
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
            $items = array();
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['applicationId'] . "'>" . $d . " " . $row['applicationEnglish'] . "</option>";
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
                return "<option value=''>" . $this->t['pleaseSelectTextLabel'] . "</option>" . $str;
            } else {
                return "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
            }
        } else {
            if ($this->getServiceOutput() == 'html') {
                return $items;
            }
        }
        // fake return
        return $items;
    }

    /**
     * Return module data
     * @param int $applicationId Application Primary Key
     * @return mixed
     */
    public function getModule($applicationId = null) {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
            SELECT      `moduleId`,
                        `moduleEnglish`
            FROM        `module`
            WHERE       `isActive`  =   1
            AND         `companyId` =   '" . $this->getCompanyId() . "'";
            if ($applicationId) {
                $sql .= " AND `applicationId`='" . $this->strict($applicationId, 'numeric') . "'";
            }
            $sql .= " ORDER BY    `moduleSequence`,
                             `isDefault`;";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      [moduleId],
                        [moduleEnglish]
            FROM        [module]
            WHERE       [isActive]  =   1
            AND         [companyId] = '" . $this->getCompanyId() . "'";
            if ($applicationId) {
                $sql .= "  AND [applicationId]='" . $this->strict($applicationId, 'numeric') . "'";
            }
            $sql .= " ORDER BY    [moduleSequence],
                             [isDefault]";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      MODULEID        AS  \"moduleEnglish\",
                        MODULEENGLISH   AS  \"moduleEnglish\"
            FROM        MODULE
            WHERE       ISACTIVE    =   1
            AND         COMPANYID   =   '" . $this->getCompanyId() . "'";
            if ($applicationId) {
                $sql .= " AND  APPLICATIONID='" . $this->strict($applicationId, 'numeric') . "'";
            }
            $sql .= " ORDER BY    MODULESEQUENCE,
                             ISDEFAULT";
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
            $items = array();
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['moduleId'] . "'>" . $d . " " . $row['moduleEnglish'] . "</option>";
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
                return "<option value=''>Please Select</option>" . $str;
            } else {
                return "<option value=''>Not Available</option>";
            }
        } else {
            if ($this->getServiceOutput() == 'html') {
                return $items;
            } else {
                echo "<option value=''>System Error Contact Admin</option>";
            }
        }
        // fake return
        return $items;
    }

    /**
     * Return Folder
     * @param int $applicationId
     * @param int $moduleId
     * @return mixed
     */
    public function getFolder($applicationId = null, $moduleId = null) {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
            SELECT      `folderId`,
                        `folderEnglish`
            FROM        `folder`
            WHERE       `isActive`=1
            AND         `companyId`=  '" . $this->getCompanyId() . "'";
            if ($applicationId) {
                $sql .= " AND `applicationId`='" . $this->strict($applicationId, 'numeric') . "'";
            }
            if ($moduleId) {
                $sql .= " AND `moduleId`='" . $this->strict($moduleId, 'numeric') . "'";
            }
            $sql .= " ORDER BY    `folderSequence`,
                             `isDefault`;";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
                 SELECT      [folderId],
                             [folderEnglish]
                 FROM        [folder]
                 WHERE       [isActive]=1
                 AND         [companyId]  = '" . $this->getCompanyId() . "'";
            if ($applicationId) {
                $sql .= " AND [applicationId]='" . $this->strict($applicationId, 'numeric') . "'";
            }
            if ($moduleId) {
                $sql .= " AND [moduleId]='" . $this->strict($moduleId, 'numeric') . "'";
            }
            $sql .= " ORDER BY    [folderSequence],
                             [isDefault]";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
                 SELECT      FOLDERID AS \"folderId\",
                             FOLDERENGLISH  AS \"folderEnglish\"
                 FROM        FOLDER
                 WHERE       ISACTIVE=1
                 AND         COMPANYID  = '" . $this->getCompanyId() . "'
                 ";
            if ($applicationId) {
                $sql .= " AND APPLICATIONID='" . $this->strict($applicationId, 'numeric') . "'";
            }
            if ($moduleId) {
                $sql .= " AND MODULEID='" . $this->strict($moduleId, 'numeric') . "'";
            }
            $sql .= "  ORDER BY    FOLDERSEQUENCE,
                             ISDEFAULT";
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
            $items = array();
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['folderId'] . "'>" . $d . ".  " . $row['folderEnglish'] . "</option>";
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
                return "<option value=''>Please Select</option>" . $str;
            } else {
                return "<option value=''>Not Available</option>";
            }
        } else {
            if ($this->getServiceOutput() == 'html') {
                return $items;
            } else {
                echo "<option value=''>System Error Contact Admin</option>";
            }
        }
        // fake return
        return $items;
    }

    /**
     * Create / Update Role Access Based On Folder
     * @param int $folderId
     * @return void
     */
    public function setCreateRoleAccess($folderId) {
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
                $sqlString.="('" . $this->getCompanyId() . "','" . $folderId . "','" . $roleId . "',0,'" . $this->getStaffId() . "'," . $this->getExecuteTime() . "),";
            }
        }
        $sqlString.=substr($sqlString, 0, -1);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "INSERT INTO `folderaccess`( `companyId`, `folderId`, `roleId`, `moduleAccessValue`, `executeBy`, `executeTime`) VALUES " . $sqlString;
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "INSERT INTO [folderAccess]( [companyId], [folderId], [roleId], [moduleAccessValue], [executeBy], [executeTime]) VALUES " . $sqlString;
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "INSERT INTO FOLDERCCESS( COMPANYID,FOLDERID,ROLEID,MODULEACCESSVALUE,EXECUTEBY,EXECUTETIME) VALUES " . $sqlString;
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
     * Update Role Access Based On Folder 
     * @depreciated Not applicable
     * @param null|int $folderId
     * @param null|int $oldRoleId
     * @param null|int $newRoleId
     * @return void
     */
    public function setUpdateRoleAccess($folderId = null, $oldRoleId = null, $newRoleId = null) {
        
    }

    /**
     * Delete Role Access Based On Folder 
     * @param int $folderId
     * @return void
     */
    public function setDeleteRoleAccess($folderId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			DELETE 
			FROM 	`folderaccess` 
			WHERE 	`companyId`		=	'" . $this->getCompanyId() . "'
			AND		`folderId`		=	'" . $folderId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			DELETE 
			FROM 	[folderAccess]
			WHERE 	[companyId]		=	'" . $this->getCompanyId() . "'
			AND		[folderId]	=	'" . $folderId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			DELETE
			FROM 	FOLDERACCESS
			WHERE 	COMPANYID		=	'" . $this->getCompanyId() . "'
			AND		FOLDERID	=	'" . $folderId . "'";
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