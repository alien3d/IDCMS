<?php

namespace Core\System\Security\FolderAccess\Model;

// using absolute path instead of relative path..
// start fake document root. it's absolute path

use Core\Validation\ValidationClass;

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
require_once($newFakeDocumentRoot . "library/class/classValidation.php");

/**
 * Class FolderAccessModel
 * this is Folder Access model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\System\Security\FolderAccess\Model
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class FolderAccessModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $folderAccessId;

    /**
     * Role
     * @var int
     */
    private $roleId;

    /**
     * Application (** For Filtering Only)
     * @var int
     */
    private $applicationId;

    /**
     * Module (** For Filtering  Only)
     * @var  int
     */
    private $moduleId;

    /**
     * Folder
     * @var int
     */
    private $folderId;

    /**
     * Value
     * @var int
     */
    private $folderAccessValue;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('folderaccess');
        $this->setPrimaryKeyName('folderAccessId');
        $this->setMasterForeignKeyName('folderAccessId');
        $this->setFilterCharacter('folderaccessDescription');
        //$this->setFilterCharacter('folderaccessNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['folderAccessId'])) {
            $this->setFolderAccessId($this->strict($_POST ['folderAccessId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['roleId'])) {
            $this->setRoleId($this->strict($_POST ['roleId'], 'integer'));
        }
        if (isset($_POST ['applicationId'])) {
            $this->setApplicationId($this->strict($_POST ['applicationId'], 'integer'));
        }
        if (isset($_POST ['moduleId'])) {
            $this->setModuleId($this->strict($_POST ['moduleId'], 'integer'));
        }
        if (isset($_POST ['folderId'])) {
            $this->setFolderId($this->strict($_POST ['folderId'], 'integer'));
        }
        if (isset($_POST ['folderAccessValue'])) {
            $this->setFolderAccessValue($this->strict($_POST ['folderAccessValue'], 'integer'), 0, 'single');
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['folderAccessId'])) {
            $this->setFolderAccessId($this->strict($_GET ['folderAccessId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['roleId'])) {
            $this->setRoleId($this->strict($_GET ['roleId'], 'integer'));
        }
        if (isset($_GET ['applicationId'])) {
            $this->setApplicationId($this->strict($_GET ['applicationId'], 'integer'));
        }
        if (isset($_GET ['moduleId'])) {
            $this->setModuleId($this->strict($_GET ['moduleId'], 'integer'));
        }
        if (isset($_GET ['folderId'])) {
            $this->setFolderId($this->strict($_GET ['folderId'], 'integer'));
        }
        if (isset($_GET ['folderAccessValue'])) {
            if (is_array($_GET ['folderAccessValue'])) {
                $this->folderAccessValue = array();
            }
        }
        if (isset($_GET ['folderAccessId'])) {
            $this->setTotal(count($_GET ['folderAccessId']));
            if (is_array($_GET ['folderAccessId'])) {
                $this->folderAccessId = array();
            }
        }
        if (isset($_GET ['isDefault'])) {
            $this->setIsDefaultTotal(count($_GET['isDefault']));
            if (is_array($_GET ['isDefault'])) {
                $this->isDefault = array();
            }
        }
        if (isset($_GET ['isNew'])) {
            $this->setIsNewTotal(count($_GET['isNew']));
            if (is_array($_GET ['isNew'])) {
                $this->isNew = array();
            }
        }
        if (isset($_GET ['isDraft'])) {
            $this->setIsDraftTotal(count($_GET['isDraft']));
            if (is_array($_GET ['isDraft'])) {
                $this->isDraft = array();
            }
        }
        if (isset($_GET ['isUpdate'])) {
            $this->setIsUpdateTotal(count($_GET['isUpdate']));
            if (is_array($_GET ['isUpdate'])) {
                $this->isUpdate = array();
            }
        }
        if (isset($_GET ['isDelete'])) {
            $this->setIsDeleteTotal(count($_GET['isDelete']));
            if (is_array($_GET ['isDelete'])) {
                $this->isDelete = array();
            }
        }
        if (isset($_GET ['isActive'])) {
            $this->setIsActiveTotal(count($_GET['isActive']));
            if (is_array($_GET ['isActive'])) {
                $this->isActive = array();
            }
        }
        if (isset($_GET ['isApproved'])) {
            $this->setIsApprovedTotal(count($_GET['isApproved']));
            if (is_array($_GET ['isApproved'])) {
                $this->isApproved = array();
            }
        }
        if (isset($_GET ['isReview'])) {
            $this->setIsReviewTotal(count($_GET['isReview']));
            if (is_array($_GET ['isReview'])) {
                $this->isReview = array();
            }
        }
        if (isset($_GET ['isPost'])) {
            $this->setIsPostTotal(count($_GET['isPost']));
            if (is_array($_GET ['isPost'])) {
                $this->isPost = array();
            }
        }
        $primaryKeyAll = '';
        for ($i = 0; $i < $this->getTotal(); $i++) {
            if (isset($_GET ['folderAccessId'])) {
                $this->setFolderAccessId($this->strict($_GET ['folderAccessId'] [$i], 'numeric'), $i, 'array');
            }

            if (isset($_GET ['folderAccessValue'])) {
                if ($_GET ['folderAccessValue'] [$i] == 'true') {
                    $this->setFolderAccessValue(1, $i, 'array');
                } else {
                    if ($_GET ['folderAccessValue'] [$i] == 'false') {
                        $this->setFolderAccessValue(0, $i, 'array');
                    }
                }
            }
            $primaryKeyAll .= $this->getFolderAccessId($i, 'array') . ",";
        }
        $this->setPrimaryKeyAll((substr($primaryKeyAll, 0, -1)));
        /**
         * All the $_SESSION Environment
         */
        if (isset($_SESSION ['staffId'])) {
            $this->setExecuteBy($_SESSION ['staffId']);
        }
        /**
         * TimeStamp Value.
         */
        if ($this->getVendor() == self::MYSQL) {
            $this->setExecuteTime("'" . date("Y-m-d H:i:s") . "'");
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $this->setExecuteTime("'" . date("Y-m-d H:i:s.u") . "'");
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $this->setExecuteTime("to_date('" . date("Y-m-d H:i:s") . "','YYYY-MM-DD HH24:MI:SS');");
                }
            }
        }
    }

    /**
     * Create Setting
     */
    public function create() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(1, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(1, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(0, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Update Setting
     */
    public function update() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(0, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(1, '', 'single');
        $this->setIsActive(1, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(0, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Delete Setting
     */
    public function delete() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(0, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(0, '', 'single');
        $this->setIsDelete(1, '', 'single');
        $this->setIsApproved(0, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Draft Setting
     */
    public function draft() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(1, 0, 'single');
        $this->setIsDraft(1, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(0, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(0, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Approved Setting
     */
    public function approved() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(1, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(0, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(1, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Review Setting
     */
    public function review() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(1, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(0, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(0, 0, 'single');
        $this->setIsReview(1, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Post Setting
     */
    public function post() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(1, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(0, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(1, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(1, 0, 'single');
    }

    /**
     * Set Folder Access Primary Key Value
     * @param int|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return \Core\System\Security\FolderAccess\Model\FolderAccessModel
     */
    public function setFolderAccessId($value, $key, $type) {
        if ($type == 'single') {
            $this->folderAccessId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->folderAccessId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setFolderAccessId?")
                );
                exit();
            }
        }
    }

    /**
     * Return Folder Access Primary Key Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     * */
    public function getFolderAccessId($key, $type) {
        if ($type == 'single') {
            return $this->folderAccessId;
        } else {
            if ($type == 'array') {
                return $this->folderAccessId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getFolderAccessId ?")
                );
                exit();
            }
        }
    }

    /**
     * To Return Role Primary Key
     * @return $roleId Role Primary Key
     */
    public function getRoleId() {
        return $this->roleId;
    }

    /**
     * To Set Role Primary Key
     * @param int $roleId Role Primary Key
     * @return \Core\System\Security\FolderAccess\Model\FolderAccessModel
     */
    public function setRoleId($roleId) {
        $this->roleId = $roleId;
        return $this;
    }

    /**
     * Set Application Primary Key
     * @param  int $value Application Primary Key
     */
    public function setApplicationId($value) {
        $this->applicationId = $value;
        return $this;
    }

    /**
     * Return Application Primary Key
     * @return int $application Application Primary Key
     */
    public function getApplicationId() {
        return $this->applicationId;
    }

    /**
     * Set Module Primary Key Value
     * @param  int $value Module Primary Key Value
     * @return \Core\System\Security\FolderAccess\Model\FolderAccessModel
     */
    public function setModuleId($value) {
        $this->moduleId = $value;
        return $this;
    }

    /**
     * Return Module Primary Key
     * @return int $moduleId Module Primary Key
     */
    public function getModuleId() {
        return $this->moduleId;
    }

    /**
     * To Return Folder Primary Key
     * @return int $folderId Folder Primary Key
     */
    public function getFolderId() {
        return $this->folderId;
    }

    /**
     * To Set Folder Primary Key
     * @param int $folderId Folder Primary Key
     * @return \Core\System\Security\FolderAccess\Model\FolderAccessModel
     */
    public function setFolderId($folderId) {
        $this->folderId = $folderId;
        return $this;
    }

    /**
     * Set Folder Access Value
     * @param bool|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return \Core\System\Security\FolderAccess\Model\FolderAccessModel
     */
    public function setFolderAccessValue($value, $key, $type) {
        if ($type == 'single') {
            $this->folderAccessValue = $value;
        } else {
            if ($type == 'array') {
                $this->folderAccessValue [$key] = $value;
            }
        }
        return $this;
    }

    /**
     * Return Folder Access Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     */
    public function getFolderAccessValue($key, $type) {
        if ($type == 'single') {
            return $this->folderAccessValue = $key;
        } else {
            if ($type == 'array') {
                return $this->folderAccessValue [$key];
            }
        }
    }

}

?>