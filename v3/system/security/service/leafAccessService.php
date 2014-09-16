<?php

namespace Core\System\Security\LeafAccess\Service;

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
 * Class LeafAccessService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Security\LeafAccess\Service
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class LeafAccessService extends ConfigClass {

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
            AND         [companyId] = '" . $this->getCompanyId() . "'
            ORDER BY    [isDefault]";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      APPLICATIONID As \"applicationId\",
                        APPLICATIONENGLISH  AS  \"applicationEnglish\"
            FROM        APPLICATION
            WHERE       ISACTIVE    =   1
            AND         COMPANYID   =   '" . $this->getCompanyId() . "'
            ORDER BY    APPLICATIONSEQUENCE,
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
                    $str .= "<option value='" . $row['applicationId'] . "'>" . $d . ".  " . $row['applicationEnglish'] . "</option>";
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
            AND         `companyId` = '" . $this->getCompanyId() . "'";
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
            SELECT      MODULEID        AS  \"moduleId\",
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
                    $str .= "<option value='" . $row['moduleId'] . "'>" . $d . ".  " . $row['moduleEnglish'] . "</option>";
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
            } else {
                echo "<option value=\"\">System Error Contact Admin</option>";
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
            WHERE       `isActive`  =   1
            AND         `companyId` = '" . $this->getCompanyId() . "'";
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
            WHERE       [isActive]  = 1
            AND         [companyId] = '" . $this->getCompanyId() . "'";
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
            SELECT      FOLDERID        AS  \"folderId\",
                        FOLDERENGLISH   AS  \"folderEnglish\"
            FROM        FOLDER
            WHERE       ISACTIVE  = 1
            AND         COMPANYID = '" . $this->getCompanyId() . "'";
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
                    $str .= "<option value='" . $row['folderId'] . "'>" . $d . ". " . $row['folderEnglish'] . "</option>";
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
            } else {
                echo "<option value=\"\">System Error Contact Admin</option>";
            }
        }
        // fake return
        return $items;
    }

    /**
     * Return Leaf Information
     * @param int $applicationId
     * @param  int $moduleId
     * @param int $folderId
     * @return mixed
     */
    public function getLeafTemp($applicationId = null, $moduleId = null, $folderId = null) {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
            SELECT      `leafId`,
                        `leafEnglish`
            FROM        `leaf`
            JOIN        `folder`
            USING       (`companyId`,`folderId`)
            WHERE       `leaf`.`isActive`      =   1
            AND         `leaf`.`companyId`     =   '" . $this->getCompanyId() . "'";
            if ($applicationId) {
                $sql .= " AND `leaf`.`applicationId`='" . $this->strict($applicationId, 'numeric') . "'";
            }
            if ($moduleId) {
                $sql .= " AND `leaf`.`moduleId`='" . $this->strict($moduleId, 'numeric') . "'";
            }
            if ($folderId) {
                $sql .= " AND `leaf`.`folderId`='" . $this->strict($folderId, 'numeric') . "'";
            }
            $sql .= "
            ORDER BY    `leaf`.`leafSequence`,
                        `leaf`.`isDefault`;";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      [leafId],
                        [leafEnglish]
            FROM        [leaf]
            JOIN        [folder]
            ON          [leaf].[companyId]  =   [folder].[companyId]
            AND         [leaf].[folderId]   =   [folder].[folderId]
            WHERE       [leaf].[isActive]   =   1
            AND         [folder].[isActive] =   1
            AND         [leaf].[companyId]  =   '" . $this->getCompanyId() . "'";
            if ($applicationId) {
                $sql .= " AND [leaf].[applicationId]='" . $this->strict($applicationId, 'numeric') . "'";
            }
            if ($moduleId) {
                $sql .= " AND [leaf].[moduleId]='" . $this->strict($moduleId, 'numeric') . "'";
            }
            if ($folderId) {
                $sql .= " AND [leaf].[folderId]='" . $this->strict($folderId, 'numeric') . "'";
            }
            $sql .= "
            ORDER BY    [leaf].[leafSequence],
                        [leaf].[isDefault]";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      LEAFID          AS  \"leafId\",
                        LEAFENGLISH     AS  \"leafEnglish\",
                        FOLDERENGLISH   AS  \"folderEnglish\"
            FROM        LEAF

            JOIN        FOLDER
            ON          lEAF.COMPANYID  =   FOLDER.COMPANYID
            AND         LEAF.FOLDERID   =   FOLDER.FOLDERID

            WHERE       LEAF.ISACTIVE   =   1
            AND         FOLDER.ISACTIVE =   1
            AND         LEAF.COMPANYID  =   '" . $this->getCompanyId() . "'";
            if ($applicationId) {
                $sql .= " AND LEAF.APPLICATIONID =   '" . $this->strict($applicationId, 'numeric') . "'";
            }
            if ($moduleId) {
                $sql .= " AND LEAF.MODULEID  =   '" . $this->strict($moduleId, 'numeric') . "'";
            }
            if ($folderId) {
                $sql .= " AND   LEAF.FOLDERID    =   '" . $this->strict($folderId, 'numeric') . "'";
            }
            $sql .= " ORDER BY    LEAF.LEAFSEQUENCE,
                             LEAF.ISDEFAULT";
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
                    $str .= "<option value='" . $row['leafId'] . "'>" . $d . ". " . $row['leafEnglish'] . "</option>";
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
            } else {
                echo "<option value=\"\">System Error Contact Admin</option>";
            }
        }
        // fake return
        return $items;
    }

    /**
     * Return Staff
     * @return string
     */
    public function getStaff() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
            SELECT      `staffId`,
                        `staffName`,
                        `roleDescription`
            FROM        `staff`
            JOIN        `role`
            USING       (`companyId`,`roleId`)
            WHERE       `staff`.`isActive`  =   1
            AND         `staff`.`companyId` =   '" . $this->getCompanyId() . "'
            ORDER BY    `staff`.`staffId`,
                        `staff`.`isDefault`;";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      [staffId],
                        [staffName],
                        [roleDescription]
            FROM        [staff]
            JOIN        [role]
            ON          [staff].[companyId] =  [role].[companyId]
            AND         [staff].[roleId]    =  [role].[roleId]
            WHERE       [staff].[isActive]  =   1
            AND         [staff].[companyId] =   '" . $this->getCompanyId() . "'
            ORDER BY    [staff].[staffId],
                        [staff].[isDefault]";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      STAFFID         AS  \"staffId\",
                        STAFFNAME       AS  \"staffName\",
                        ROLEDESCRIPTION AS  \"roleDescription\"
            FROM        STAFF
            JOIN        ROLE
            ON          STAFF.COMPANYID   =  ROLE.COMPANYID
            AND         STAFF.ROLEID      =  ROLE.ROLEID
            WHERE       STAFF.ISACTIVE    =   1
            AND         STAFF.COMPANYID   =   '" . $this->getCompanyId() . "'
            ORDER BY    STAFF.STAFFID,
                        STAFF.ISDEFAULT";
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
                    $str .= "<option value='" . $row['staffId'] . "'>" . $d . " " . $row['staffName'] . "</option>";
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
     * Create / Update Staff Access Based On Leaf
     * @param int $leafId
     * @return void
     */
    public function setCreateStaffAccess($leafId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT	`staffId`	 
			FROM 	`staff` 
			WHERE 	`companyId`='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT	[staffId] 
			FROM 	[staff]
			WHERE 	[companyId]='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT	STAFFID	AS \"staffId\"
			FROM 	STAFF
			WHERE 	COMPANYID='" . $this->getCompanyId() . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $sqlString = null;
        if ($result) {
            while (($row = $this->q->fetchArray($result)) == true) {
                $staffId = $row['staffId'];
                $sqlString.="('" . $this->getCompanyId() . "','" . $leafId . "','" . $staffId . "',0,'" . $this->getStaffId() . "'," . $this->getExecuteTime() . "),";
            }
        }
        $sqlString.=substr($sqlString, 0, -1);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "INSERT INTO `leafaccess`(`companyId`, `leafId`, `staffId`, `leafAccessDraftValue`, `leafAccessCreateValue`, `leafAccessReadValue`, `leafAccessUpdateValue`, `leafAccessDeleteValue`, `leafAccessReviewValue`, `leafAccessApprovedValue`, `leafAccessPostValue`, `leafAccessPrintValue`, `executeBy`, `executeTime`) VALUES " . $sqlString;
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "INSERT INTO [leafAccess]([companyId], [leafId], [staffId], [leafAccessDraftValue], [leafAccessCreateValue], [leafAccessReadValue],[leafAccessUpdateValue],[leafAccessDeleteValue],[leafAccessReviewValue],[leafAccessApprovedValue],[leafAccessPostValue],[leafAccessPrintValue],[executeBy],[executeTime]) VALUES " . $sqlString;
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "INSERT INTO LEAFCCESS(COMPANYID, LEAFID, STAFFID, LEAFACCESSDRAFTVALUE, LEAFACCESSCREATEVALUE, LEAFACCESSREADVALUE,LEAFACCESSUPDATEVALUE,LEAFACCESSDELETEVALUE,LEAFACCESSREVIEWVALUE,LEAFACCESSAPPROVEDVALUE,LEAFACCESSPOSTVALUE,LEAFACCESSPRINTVALUE,EXECUTEBY,EXECUTETIME) VALUES " . $sqlString;
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
     * Update Staff Access Based On Leaf
     * @depreciated Not applicable
     * @param int $folderId
     * @param int $oldStaffId
     * @param int $newStaffId
     * @return void
     */
    public function setUpdateStaffAccess($folderId, $oldStaffId, $newStaffId) {
        
    }

    /**
     * Delete Staff Access Based On Leaf
     * @param int $leafId
     * @return void
     */
    public function setDeleteStaffAccess($leafId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			DELETE 
			FROM 	`leafAccess` 
			WHERE 	`companyId`		=	'" . $this->getCompanyId() . "'
			AND		`leafId`		=	'" . $leafId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			DELETE 
			FROM 	[leafAccess]
			WHERE 	[companyId]		=	'" . $this->getCompanyId() . "'
			AND		[leafId]		=	'" . $leafId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			DELETE
			FROM 	LEAFACCESS
			WHERE 	COMPANYID		=	'" . $this->getCompanyId() . "'
			AND		LEAFID			=	'" . $leafId . "'";
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