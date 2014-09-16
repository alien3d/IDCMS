<?php

namespace Core\System\Security\LeafRoleAccess\Model;

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
 * Class LeafRoleAccessModel
 * this is Leaf Role Access model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\System\Security\LeafRoleAccess\Model
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class LeafRoleAccessModel extends ValidationClass {

    /**
     * leafRoleAccessId
     * @var int
     */
    private $leafRoleAccessId;

    /**
     * Group Primary Key (** For Filtering Only)
     * @var int
     */
    private $roleId;

    /**
     * Application Primary Key (** For Filtering Only)
     * @var int
     */
    private $applicationId;

    /**
     * Module   Primary Key (** For Filtering  Only)
     * @var  int
     */
    private $moduleId;

    /**
     * Folder   Primary Key (** For Filtering Only)
     * @var int
     */
    private $folderId;

    /**
     * Leaf  Primary Key(** For Filtering only)
     * @var int
     */
    private $leafIdTemp;

    /**
     * Staff
     * @var int
     */
    private $staffIdTemp;

    /**
     * leafRoleAccessDraftValue
     * @var int
     */
    private $leafRoleAccessDraftValue;

    /**
     * Create
     * @var int
     */
    private $leafRoleAccessCreateValue;

    /**
     * Read
     * @var int
     */
    private $leafRoleAccessReadValue;

    /**
     * Update
     * @var int
     */
    private $leafRoleAccessUpdateValue;

    /**
     * Delete
     * @var int
     */
    private $leafRoleAccessDeleteValue;

    /**
     * Review
     * @var int
     */
    private $leafRoleAccessReviewValue;

    /**
     * Approved
     * @var int
     */
    private $leafRoleAccessApprovedValue;

    /**
     * Post
     * @var int
     */
    private $leafRoleAccessPostValue;

    /**
     * Print
     * @var int
     */
    private $leafRoleAccessPrintValue;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('leafRoleAccess');
        $this->setPrimaryKeyName('leafRoleAccessId');
        $this->setMasterForeignKeyName('leafRoleAccessId');
        $this->setFilterCharacter('leafRoleAccessDescription');
        //$this->setFilterCharacter('leafRoleAccessNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['leafRoleAccessId'])) {
            $this->setLeafRoleAccessId($this->strict($_POST ['leafRoleAccessId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['roleId'])) {
            $this->setRoleId($this->strict($_POST ['roleId'], 'numeric'));
        }
        if (isset($_POST ['applicationId'])) {
            $this->setApplicationId($this->strict($_POST ['applicationId'], 'numeric'));
        }
        if (isset($_POST ['moduleId'])) {
            $this->setModuleId($this->strict($_POST ['moduleId'], 'numeric'));
        }
        if (isset($_POST ['folderId'])) {
            $this->setFolderId($this->strict($_POST['folderId'], 'numeric'));
        }
        if (isset($_POST ['leafIdTemp'])) {
            $this->setLeafIdTemp($this->strict($_POST ['leafIdTemp'], 'integer'));
        }
        if (isset($_POST ['staffId'])) {
            $this->setStaffIdTemp($this->strict($_POST ['staffId'], 'integer'));
        }

        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['leafRoleAccessId'])) {
            $this->setLeafRoleAccessId($this->strict($_GET ['leafRoleAccessId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['staffIdTemp'])) {
            $this->setStaffIdTemp($this->strict($_GET ['staffIdTemp'], 'numeric'));
        }
        if (isset($_GET ['applicationId'])) {
            $this->setApplicationId($this->strict($_GET ['applicationId'], 'numeric'));
        }
        if (isset($_GET ['moduleId'])) {
            $this->setModuleId($this->strict($_GET ['moduleId'], 'numeric'));
        }
        if (isset($_GET ['folderId'])) {
            $this->setFolderId($this->strict($_GET ['folderId'], 'numeric'));
        }

        if (isset($_GET ['leafIdTemp'])) {
            $this->setLeafIdTemp($this->strict($_GET ['leafIdTemp'], 'integer'));
        }
        if (isset($_GET ['staffIdTemp'])) {
            $this->setStaffIdTemp($this->strict($_GET ['staffIdTemp'], 'integer'));
        }
        if (isset($_GET ['leafRoleAccessDraftValue'])) {
            if (is_array($_GET ['leafRoleAccessDraftValue'])) {
                $this->leafRoleAccessDraftValue = array();
            }
        }
        if (isset($_GET ['leafRoleAccessCreateValue'])) {
            if (is_array($_GET ['leafRoleAccessCreateValue'])) {
                $this->leafRoleAccessCreateValue = array();
            }
        }
        if (isset($_GET ['leafRoleAccessReadValue'])) {
            if (is_array($_GET ['leafRoleAccessReadValue'])) {
                $this->leafRoleAccessReadValue = array();
            }
        }
        if (isset($_GET ['leafRoleAccessUpdateValue'])) {
            if (is_array($_GET ['leafRoleAccessUpdateValue'])) {
                $this->leafRoleAccessUpdateValue = array();
            }
        }
        if (isset($_GET ['leafRoleAccessDeleteValue'])) {
            if (is_array($_GET ['leafRoleAccessDeleteValue'])) {
                $this->leafRoleAccessDeleteValue = array();
            }
        }
        if (isset($_GET ['leafRoleAccessReviewValue'])) {
            if (is_array($_GET ['leafRoleAccessReviewValue'])) {
                $this->leafRoleAccessReviewValue = array();
            }
        }
        if (isset($_GET ['leafRoleAccessApprovedValue'])) {
            if (is_array($_GET ['leafRoleAccessApprovedValue'])) {
                $this->leafRoleAccessApprovedValue = array();
            }
        }
        if (isset($_GET ['leafRoleAccessPostValue'])) {
            if (is_array($_GET ['leafRoleAccessPostValue'])) {
                $this->leafRoleAccessPostValue = array();
            }
        }
        if (isset($_GET ['leafRoleAccessPrintValue'])) {
            if (is_array($_GET ['leafRoleAccessPrintValue'])) {
                $this->leafRoleAccessPrintValue = array();
            }
        }
        if (isset($_GET ['leafRoleAccessId'])) {
            $this->setTotal(count($_GET ['leafRoleAccessId']));
            if (is_array($_GET ['leafRoleAccessId'])) {
                $this->leafRoleAccessId = array();
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
            if (isset($_GET ['leafRoleAccessId'])) {
                $this->setLeafRoleAccessId($this->strict($_GET ['leafRoleAccessId'] [$i], 'numeric'), $i, 'array');
            }

            if (isset($_GET ['leafRoleAccessCreateValue'])) {
                if ($_GET ['leafRoleAccessCreateValue'] [$i] == 'true') {
                    $this->setLeafRoleAccessCreateValue(1, $i, 'array');
                } else {
                    if ($_GET ['leafRoleAccessCreateValue'] [$i] == 'false') {
                        $this->setLeafRoleAccessCreateValue(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['leafRoleAccessDraftValue'])) {
                if ($_GET ['leafRoleAccessDraftValue'] [$i] == 'true') {
                    $this->setLeafRoleAccessDraftValue(1, $i, 'array');
                } else {
                    if ($_GET ['leafRoleAccessDraftValue'] [$i] == 'false') {
                        $this->setLeafRoleAccessDraftValue(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['leafRoleAccessReadValue'])) {
                if ($_GET ['leafRoleAccessReadValue'] [$i] == 'true') {
                    $this->setLeafRoleAccessReadValue(1, $i, 'array');
                }
                if ($_GET ['leafRoleAccessReadValue'] [$i] == 'false') {
                    $this->setLeafRoleAccessReadValue(0, $i, 'array');
                }
            }
            if (isset($_GET ['leafRoleAccessUpdateValue'])) {
                if ($_GET ['leafRoleAccessUpdateValue'] [$i] == 'true') {
                    $this->setLeafRoleAccessUpdateValue(1, $i, 'array');
                }
                if ($_GET ['leafRoleAccessUpdateValue'] [$i] == 'false') {
                    $this->setLeafRoleAccessUpdateValue(0, $i, 'array');
                }
            }
            if (isset($_GET ['leafRoleAccessDeleteValue'])) {
                if ($_GET ['leafRoleAccessDeleteValue'] [$i] == 'true') {
                    $this->setLeafRoleAccessDeleteValue(1, $i, 'array');
                } else {
                    if ($_GET ['leafRoleAccessDeleteValue'] [$i] == 'false') {
                        $this->setLeafRoleAccessDeleteValue(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['leafRoleAccessReviewValue'])) {
                if ($_GET ['leafRoleAccessReviewValue'] [$i] == 'true') {
                    $this->setLeafRoleAccessReviewValue(1, $i, 'array');
                } else {
                    if ($_GET ['leafRoleAccessReviewValue'] [$i] == 'false') {
                        $this->setLeafRoleAccessReviewValue(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['leafRoleAccessApprovedValue'])) {
                if ($_GET ['leafRoleAccessApprovedValue'] [$i] == 'true') {
                    $this->setLeafRoleAccessApprovedValue(1, $i, 'array');
                } else {
                    if ($_GET ['leafRoleAccessApprovedValue'] [$i] == 'false') {
                        $this->setLeafRoleAccessApprovedValue(0, $i, 'array');
                    }
                }
            }

            if (isset($_GET ['leafRoleAccessPostValue'])) {
                if ($_GET ['leafRoleAccessPostValue'] [$i] == 'true') {
                    $this->setLeafRoleAccessPostValue(1, $i, 'array');
                } else {
                    if ($_GET ['leafRoleAccessPostValue'] [$i] == 'false') {
                        $this->setLeafRoleAccessPostValue(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['leafRoleAccessPrintValue'])) {
                if ($_GET ['leafRoleAccessPrintValue'] [$i] == 'true') {
                    $this->setLeafRoleAccessPrintValue(1, $i, 'array');
                } else {
                    if ($_GET ['leafRoleAccessPostValue'] [$i] == 'false') {
                        $this->setLeafRoleAccessPrintValue(0, $i, 'array');
                    }
                }
            }
            $primaryKeyAll .= $this->getLeafRoleAccessId($i, 'array') . ",";
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
     * Set Primary Key Value
     * @param int|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return \Core\System\Security\LeafRoleAccess\Model\LeafRoleAccessModel
     */
    public function setLeafRoleAccessId($value, $key, $type) {
        if ($type == 'single') {
            $this->leafRoleAccessId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->leafRoleAccessId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setLeafRoleAccessId?")
                );
                exit();
            }
        }
    }

    /**
     * Return Primary Key Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return int|array|mixed
     */
    public function getLeafRoleAccessId($key, $type) {
        if ($type == 'single') {
            return $this->leafRoleAccessId;
        } else {
            if ($type == 'array') {
                return $this->leafRoleAccessId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getLeafRoleAccessId ?")
                );
                exit();
            }
        }
    }

    /**
     * Set Role
     * @param int $value
     * @return \Core\System\Security\LeafRoleAccess\Model\LeafRoleAccessModel
     */
    public function setRoleId($value) {
        $this->roleId = $value;
        return $this;
    }

    /**
     * Return Role
     * @return int $roleId Role Primary Key
     */
    public function getRoleId() {
        return $this->roleId;
    }

    /**
     * Set Application
     * @param int $value Application
     * @return \Core\System\Security\LeafRoleAccess\Model\LeafRoleAccessModel
     */
    public function setApplicationId($value) {
        $this->applicationId = $value;
        return $this;
    }

    /**
     * Return Application
     * @return int $applicationId Application Primary Key Value
     */
    public function getApplicationId() {
        return $this->applicationId;
    }

    /**
     * Set Module
     * @param  int $value Module
     * @return \Core\System\Security\LeafRoleAccess\Model\LeafRoleAccessModel
     */
    public function setModuleId($value) {
        $this->moduleId = $value;
        return $this;
    }

    /**
     * Return Module
     * @return int $moduleId Module
     */
    public function getModuleId() {
        return $this->moduleId;
    }

    /**
     * Set Folder
     * @param  int $value Folder
     * @return \Core\System\Security\LeafRoleAccess\Model\LeafRoleAccessModel
     */
    public function setFolderId($value) {
        $this->folderId = $value;
        return $this;
    }

    /**
     * Return Folder
     * @return int $folderId Folder
     */
    public function getFolderId() {
        return $this->folderId;
    }

    /**
     * To Return Leaf
     * @return int $leafIdTemp Leaf
     */
    public function getLeafIdTemp() {
        return $this->leafIdTemp;
    }

    /**
     * To Set Leaf Primary Key
     * @param int $leafIdTemp Leaf Primary Key
     * @return \Core\System\Security\LeafRoleAccess\Model\LeafRoleAccessModel
     */
    public function setLeafIdTemp($leafIdTemp) {
        $this->leafIdTemp = $leafIdTemp;
        return $this;
    }

    /**
     * To Return Staff Primary Key
     * @return int $staffId Staff Primary Key
     */
    public function getStaffIdTemp() {
        return $this->staffIdTemp;
    }

    /**
     * To Set Staff Primary Key
     * @param int $staffIdTemp Staff Primary Key
     * @return \Core\System\Security\LeafRoleAccess\Model\LeafRoleAccessModel
     */
    public function setStaffIdTemp($staffIdTemp) {
        $this->staffIdTemp = $staffIdTemp;
        return $this;
    }

    /**
     * Set Leaf Draft Access  Value
     * @param bool|array $value
     * @param array|int $key List Of Primary Key.
     * @return \Core\System\Security\LeafRoleAccess\Model\LeafRoleAccessModel
     */
    public function setLeafRoleAccessDraftValue($value, $key) {
        $this->leafRoleAccessDraftValue [$key] = $value;
        return $this;
    }

    /**
     * Return Leaf Draft Access Value
     * @param array|int $key List Of Primary Key.
     * @return bool|array
     */
    public function getLeafRoleAccessDraftValue($key) {
        return $this->leafRoleAccessDraftValue [$key];
    }

    /**
     * Set Leaf Create Access  Value
     * @param bool|array $value
     * @param array|int $key List Of Primary Key.
     * @return \Core\System\Security\LeafRoleAccess\Model\LeafRoleAccessModel
     */
    public function setLeafRoleAccessCreateValue($value, $key) {
        $this->leafRoleAccessCreateValue [$key] = $value;
        return $this;
    }

    /**
     * Return Leaf Create Access Value
     * @param array|int $key List Of Primary Key.
     * @return bool|array
     */
    public function getLeafRoleAccessCreateValue($key) {
        return $this->leafRoleAccessCreateValue [$key];
    }

    /**
     * Set Leaf Read Access  Value
     * @param bool|array $value
     * @param array|int $key List Of Primary Key.
     * @return \Core\System\Security\LeafRoleAccess\Model\LeafRoleAccessModel
     */
    public function setLeafRoleAccessReadValue($value, $key) {
        $this->leafRoleAccessReadValue [$key] = $value;
        return $this;
    }

    /**
     * Return Leaf Read Access Value
     * @param array|int $key List Of Primary Key.
     * @return bool|array
     */
    public function getLeafRoleAccessReadValue($key) {
        return $this->leafRoleAccessReadValue [$key];
    }

    /**
     * Set Leaf Update Access  Value
     * @param bool|array $value
     * @param array|int $key List Of Primary Key.
     * @return \Core\System\Security\LeafRoleAccess\Model\LeafRoleAccessModel
     */
    public function setLeafRoleAccessUpdateValue($value, $key) {
        $this->leafRoleAccessUpdateValue [$key] = $value;
        return $this;
    }

    /**
     * Return Leaf Update Access Value
     * @param array|int $key List Of Primary Key.
     * @return bool|array
     */
    public function getLeafRoleAccessUpdateValue($key) {
        return $this->leafRoleAccessUpdateValue [$key];
    }

    /**
     * Set Leaf Update Access  Value
     * @param bool|array $value
     * @param array|int $key List Of Primary Key.
     * @return \Core\System\Security\LeafRoleAccess\Model\LeafRoleAccessModel
     */
    public function setLeafRoleAccessDeleteValue($value, $key) {
        $this->leafRoleAccessDeleteValue [$key] = $value;
        return $this;
    }

    /**
     * Return Leaf Delete Access Value
     * @param array|int $key List Of Primary Key.
     * @return bool|array
     */
    public function getLeafRoleAccessDeleteValue($key) {
        return $this->leafRoleAccessDeleteValue [$key];
    }

    /**
     * Set Leaf Approved Access  Value
     * @param bool|array $value
     * @param array|int $key List Of Primary Key.
     * @return \Core\System\Security\LeafRoleAccess\Model\LeafRoleAccessModel
     */
    public function setLeafRoleAccessApprovedValue($value, $key) {
        $this->leafRoleAccessApprovedValue [$key] = $value;
        return $this;
    }

    /**
     * Return Leaf Approved Access Value
     * @param array|int $key List Of Primary Key.
     * @return bool|array
     */
    public function getLeafRoleAccessApprovedValue($key) {
        return $this->leafRoleAccessApprovedValue [$key];
    }

    /**
     * Set Leaf Review Access  Value
     * @param bool|array $value
     * @param array|int $key List Of Primary Key.
     * @return \Core\System\Security\LeafRoleAccess\Model\LeafRoleAccessModel
     */
    public function setLeafRoleAccessReviewValue($value, $key) {
        $this->leafRoleAccessReviewValue [$key] = $value;
        return $this;
    }

    /**
     * Return Leaf Review Access Value
     * @param array|int $key List Of Primary Key.
     * @return bool|array
     */
    public function getLeafRoleAccessReviewValue($key) {
        return $this->leafRoleAccessReviewValue [$key];
    }

    /**
     * Set Leaf Print Access  Value
     * @param bool|array $value
     * @param array|int $key List Of Primary Key.
     * @return \Core\System\Security\LeafRoleAccess\Model\LeafRoleAccessModel
     */
    public function setLeafRoleAccessPrintValue($value, $key) {
        $this->leafRoleAccessPrintValue [$key] = $value;
        return $this;
    }

    /**
     * Return Leaf Print Access Value
     * @param array|int $key List Of Primary Key.
     * @return bool|array
     */
    public function getLeafRoleAccessPrintValue($key) {
        return $this->leafRoleAccessPrintValue [$key];
    }

    /**
     * Set Leaf Post Access  Value
     * @param bool|array $value
     * @param array|int $key List Of Primary Key.
     * @return \Core\System\Security\LeafRoleAccess\Model\LeafRoleAccessModel
     */
    public function setLeafRoleAccessPostValue($value, $key) {
        $this->leafRoleAccessPostValue [$key] = $value;
        return $this;
    }

    /**
     * Return Leaf Post  Access Value
     * @param array|int $key List Of Primary Key.
     * @return bool|array
     */
    public function getLeafRoleAccessPostValue($key) {
        return $this->leafRoleAccessPostValue [$key];
    }

}

?>