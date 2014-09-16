<?php

namespace Core\System\Security\LeafAccess\Model;

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
 * Class LeafAccessModel
 * this is leaf access model file.
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\System\Security\LeafAccess\Model
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class LeafAccessModel extends ValidationClass {

    /**
     * leafAccessId
     * @var int
     */
    private $leafAccessId;

    /**
     * Group Primary Key (** For Filtering Only)
     * @var int
     */
    private $staffIdTemp;

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
     * Draft Value
     * @var int
     */
    private $leafAccessDraftValue;

    /**
     * Create Value
     * @var int
     */
    private $leafAccessCreateValue;

    /**
     * Read
     * @var int
     */
    private $leafAccessReadValue;

    /**
     * Update
     * @var int
     */
    private $leafAccessUpdateValue;

    /**
     * Delete
     * @var int
     */
    private $leafAccessDeleteValue;

    /**
     * Review
     * @var int
     */
    private $leafAccessReviewValue;

    /**
     * Approved
     * @var int
     */
    private $leafAccessApprovedValue;

    /**
     * Post
     * @var int
     */
    private $leafAccessPostValue;

    /**
     * Print
     * @var int
     */
    private $leafAccessPrintValue;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('leafAccess');
        $this->setPrimaryKeyName('leafAccessId');
        $this->setMasterForeignKeyName('leafAccessId');
        $this->setFilterCharacter('leafAccessDescription');
        //$this->setFilterCharacter('leafAccessNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['leafAccessId'])) {
            $this->setLeafAccessId($this->strict($_POST ['leafAccessId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['staffIdTemp'])) {
            $this->setStaffIdTemp($this->strict($_POST ['staffIdTemp'], 'numeric'));
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
        if (isset($_POST ['staffIdTemp'])) {
            $this->setStaffIdTemp($this->strict($_POST ['staffIdTemp'], 'integer'));
        }
        if (isset($_POST ['leafAccessDraftValue'])) {
            $this->setLeafAccessDraftValue($this->strict($_POST ['leafAccessDraftValue'], 'integer'), 'array');
        }
        if (isset($_POST ['leafAccessCreateValue'])) {
            $this->setLeafAccessCreateValue($this->strict($_POST ['leafAccessCreateValue'], 'integer'), 'array');
        }
        if (isset($_POST ['leafAccessReadValue'])) {
            $this->setLeafAccessReadValue($this->strict($_POST ['leafAccessReadValue'], 'integer'), 'array');
        }
        if (isset($_POST ['leafAccessUpdateValue'])) {
            $this->setLeafAccessUpdateValue($this->strict($_POST ['leafAccessUpdateValue'], 'integer'), 'array');
        }
        if (isset($_POST ['leafAccessDeleteValue'])) {
            $this->setLeafAccessDeleteValue($this->strict($_POST ['leafAccessDeleteValue'], 'integer'), 'array');
        }
        if (isset($_POST ['leafAccessReviewValue'])) {
            $this->setLeafAccessReviewValue($this->strict($_POST ['leafAccessReviewValue'], 'integer'), 'array');
        }
        if (isset($_POST ['leafAccessApprovedValue'])) {
            $this->setLeafAccessApprovedValue($this->strict($_POST ['leafAccessApprovedValue'], 'integer'), 'array');
        }
        if (isset($_POST ['leafAccessPostValue'])) {
            $this->setLeafAccessPostValue($this->strict($_POST ['leafAccessPostValue'], 'integer'), 'array');
        }
        if (isset($_POST ['leafAccessPrintValue'])) {
            $this->setLeafAccessPrintValue($this->strict($_POST ['leafAccessPrintValue'], 'integer'), 'array');
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['leafAccessId'])) {
            $this->setLeafAccessId($this->strict($_GET ['leafAccessId'], 'integer'), 0, 'single');
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
        if (isset($_GET ['leafAccessDraftValue'])) {
            if (is_array($_GET ['leafAccessDraftValue'])) {
                $this->leafAccessDraftValue = array();
            }
        }
        if (isset($_GET ['leafAccessCreateValue'])) {
            if (is_array($_GET ['leafAccessCreateValue'])) {
                $this->leafAccessCreateValue = array();
            }
        }
        if (isset($_GET ['leafAccessReadValue'])) {
            if (is_array($_GET ['leafAccessReadValue'])) {
                $this->leafAccessReadValue = array();
            }
        }
        if (isset($_GET ['leafAccessUpdateValue'])) {
            if (is_array($_GET ['leafAccessUpdateValue'])) {
                $this->leafAccessUpdateValue = array();
            }
        }
        if (isset($_GET ['leafAccessDeleteValue'])) {
            if (is_array($_GET ['leafAccessDeleteValue'])) {
                $this->leafAccessDeleteValue = array();
            }
        }
        if (isset($_GET ['leafAccessReviewValue'])) {
            if (is_array($_GET ['leafAccessReviewValue'])) {
                $this->leafAccessReviewValue = array();
            }
        }
        if (isset($_GET ['leafAccessApprovedValue'])) {
            if (is_array($_GET ['leafAccessApprovedValue'])) {
                $this->leafAccessApprovedValue = array();
            }
        }
        if (isset($_GET ['leafAccessPostValue'])) {
            if (is_array($_GET ['leafAccessPostValue'])) {
                $this->leafAccessPostValue = array();
            }
        }
        if (isset($_GET ['leafAccessPrintValue'])) {
            if (is_array($_GET ['leafAccessPrintValue'])) {
                $this->leafAccessPrintValue = array();
            }
        }
        if (isset($_GET ['leafAccessId'])) {
            $this->setTotal(count($_GET ['leafAccessId']));
            if (is_array($_GET ['leafAccessId'])) {
                $this->leafAccessId = array();
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
            if (isset($_GET ['leafAccessId'])) {
                $this->setLeafAccessId($this->strict($_GET ['leafAccessId'] [$i], 'numeric'), $i, 'array');
            }

            if (isset($_GET ['leafAccessCreateValue'])) {
                if ($_GET ['leafAccessCreateValue'] [$i] == 'true') {
                    $this->setLeafAccessCreateValue(1, $i, 'array');
                } else {
                    if ($_GET ['leafAccessCreateValue'] [$i] == 'false') {
                        $this->setLeafAccessCreateValue(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['leafAccessDraftValue'])) {
                if ($_GET ['leafAccessDraftValue'] [$i] == 'true') {
                    $this->setLeafAccessDraftValue(1, $i, 'array');
                } else {
                    if ($_GET ['leafAccessDraftValue'] [$i] == 'false') {
                        $this->setLeafAccessDraftValue(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['leafAccessReadValue'])) {
                if ($_GET ['leafAccessReadValue'] [$i] == 'true') {
                    $this->setLeafAccessReadValue(1, $i, 'array');
                }
                if ($_GET ['leafAccessReadValue'] [$i] == 'false') {
                    $this->setLeafAccessReadValue(0, $i, 'array');
                }
            }
            if (isset($_GET ['leafAccessUpdateValue'])) {
                if ($_GET ['leafAccessUpdateValue'] [$i] == 'true') {
                    $this->setLeafAccessUpdateValue(1, $i, 'array');
                }
                if ($_GET ['leafAccessUpdateValue'] [$i] == 'false') {
                    $this->setLeafAccessUpdateValue(0, $i, 'array');
                }
            }
            if (isset($_GET ['leafAccessDeleteValue'])) {
                if ($_GET ['leafAccessDeleteValue'] [$i] == 'true') {
                    $this->setLeafAccessDeleteValue(1, $i, 'array');
                } else {
                    if ($_GET ['leafAccessDeleteValue'] [$i] == 'false') {
                        $this->setLeafAccessDeleteValue(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['leafAccessReviewValue'])) {
                if ($_GET ['leafAccessReviewValue'] [$i] == 'true') {
                    $this->setLeafAccessReviewValue(1, $i, 'array');
                } else {
                    if ($_GET ['leafAccessReviewValue'] [$i] == 'false') {
                        $this->setLeafAccessReviewValue(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['leafAccessApprovedValue'])) {
                if ($_GET ['leafAccessApprovedValue'] [$i] == 'true') {
                    $this->setLeafAccessApprovedValue(1, $i, 'array');
                } else {
                    if ($_GET ['leafAccessApprovedValue'] [$i] == 'false') {
                        $this->setLeafAccessApprovedValue(0, $i, 'array');
                    }
                }
            }

            if (isset($_GET ['leafAccessPostValue'])) {
                if ($_GET ['leafAccessPostValue'] [$i] == 'true') {
                    $this->setLeafAccessPostValue(1, $i, 'array');
                } else {
                    if ($_GET ['leafAccessPostValue'] [$i] == 'false') {
                        $this->setLeafAccessPostValue(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['leafAccessPrintValue'])) {
                if ($_GET ['leafAccessPrintValue'] [$i] == 'true') {
                    $this->setLeafAccessPrintValue(1, $i, 'array');
                } else {
                    if ($_GET ['leafAccessPostValue'] [$i] == 'false') {
                        $this->setLeafAccessPrintValue(0, $i, 'array');
                    }
                }
            }
            $primaryKeyAll .= $this->getLeafAccessId($i, 'array') . ",";
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
     * Set Leaf Access Primary Key Value
     * @param int|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return \Core\System\Security\LeafAccess\Model\LeafAccessModel
     */
    public function setLeafAccessId($value, $key, $type) {
        if ($type == 'single') {
            $this->leafAccessId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->leafAccessId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setLeafAccessId?")
                );
                exit();
            }
        }
    }

    /**
     * Return Leaf Access Primary Key Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     */
    public function getLeafAccessId($key, $type) {
        if ($type == 'single') {
            return $this->leafAccessId;
        } else {
            if ($type == 'array') {
                return $this->leafAccessId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getLeafAccessId ?")
                );
                exit();
            }
        }
    }

    /**
     * Set Application Primary Key
     * @param  int $value Application Primary Key
     * @return \Core\System\Security\LeafAccess\Model\LeafAccessModel
     */
    public function setApplicationId($value) {
        $this->applicationId = $value;
        return $this;
    }

    /**
     * Return Application Primary Key
     * @return int $applicationId Application Primary Key
     */
    public function getApplicationId() {
        return $this->applicationId;
    }

    /**
     * Set Module Primary Key
     * @param  int $value Module Primary Key
     * @return \Core\System\Security\LeafAccess\Model\LeafAccessModel
     */
    public function setModuleId($value) {
        $this->moduleId = $value;
        return $this;
    }

    /**
     * Return Module Primary Key
     * @return int Module Primary Key
     */
    public function getModuleId() {
        return $this->moduleId;
    }

    /**
     * Set Folder Primary Key
     * @param  int $value Folder Primary Key
     * @return \Core\System\Security\LeafAccess\Model\LeafAccessModel
     */
    public function setFolderId($value) {
        $this->folderId = $value;
        return $this;
    }

    /**
     * Return Folder Primary Key
     * @return int $folderId Folder Primary Key
     */
    public function getFolderId() {
        return $this->folderId;
    }

    /**
     * To Return Leaf Primary Key
     * @return int $leafId Leaf Primary Key
     */
    public function getLeafIdTemp() {

        return $this->leafIdTemp;
    }

    /**
     * To Set Leaf Primary Key
     * @param int $value Leaf Primary Key
     * @return \Core\System\Security\LeafAccess\Model\LeafAccessModel
     */
    public function setLeafIdTemp($value) {
        $this->leafIdTemp = $value;
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
     * @param int $value Staff Primary Key
     * @return \Core\System\Security\LeafAccess\Model\LeafAccessModel
     */
    public function setStaffIdTemp($value) {
        $this->staffIdTemp = $value;
        return $this;
    }

    /**
     * Set Leaf Draft Access Value
     * @param bool|array $value
     * @param array|int $key List Of Primary Key.
     * @return \Core\System\Security\LeafAccess\Model\LeafAccessModel
     */
    public function setLeafAccessDraftValue($value, $key) {
        $this->leafAccessDraftValue [$key] = $value;
        return $this;
    }

    /**
     * Return Leaf Draft Access Value
     * @param array|int $key List Of Primary Key.
     * @return bool|array
     */
    public function getLeafAccessDraftValue($key) {
        return $this->leafAccessDraftValue [$key];
    }

    /**
     * Set Leaf Create Access Value
     * @param bool|array $value
     * @param array|int $key List Of Primary Key.
     * @return \Core\System\Security\LeafAccess\Model\LeafAccessModel
     */
    public function setLeafAccessCreateValue($value, $key) {
        $this->leafAccessCreateValue [$key] = $value;
        return $this;
    }

    /**
     * Return Leaf Create Access Value
     * @param array|int $key List Of Primary Key.
     * @return bool|array
     */
    public function getLeafAccessCreateValue($key) {
        return $this->leafAccessCreateValue [$key];
    }

    /**
     * Set Leaf Read Access Value
     * @param bool|array $value
     * @param array|int $key List Of Primary Key.
     * @return \Core\System\Security\LeafAccess\Model\LeafAccessModel
     */
    public function setLeafAccessReadValue($value, $key) {
        $this->leafAccessReadValue [$key] = $value;
        return $this;
    }

    /**
     * Return Leaf Read Access Value
     * @param array|int $key List Of Primary Key.
     * @return bool|array
     */
    public function getLeafAccessReadValue($key) {
        return $this->leafAccessReadValue [$key];
    }

    /**
     * Set Leaf Update Access Value
     * @param bool|array $value
     * @param array|int $key List Of Primary Key.
     * @return \Core\System\Security\LeafAccess\Model\LeafAccessModel
     */
    public function setLeafAccessUpdateValue($value, $key) {
        $this->leafAccessUpdateValue [$key] = $value;
        return $this;
    }

    /**
     * Return Leaf Update Access Value
     * @param array|int $key List Of Primary Key.
     * @return bool|array
     */
    public function getLeafAccessUpdateValue($key) {
        return $this->leafAccessUpdateValue [$key];
    }

    /**
     * Set Leaf Update Access Value
     * @param bool|array $value
     * @param array|int $key List Of Primary Key.
     * @return \Core\System\Security\LeafAccess\Model\LeafAccessModel
     */
    public function setLeafAccessDeleteValue($value, $key) {
        $this->leafAccessDeleteValue [$key] = $value;
        return $this;
    }

    /**
     * Return Leaf Delete Access Value
     * @param array|int $key List Of Primary Key.
     * @return bool|array
     */
    public function getLeafAccessDeleteValue($key) {
        return $this->leafAccessDeleteValue [$key];
    }

    /**
     * Set Leaf Approved Access Value
     * @param bool|array $value
     * @param array|int $key List Of Primary Key.
     * @return \Core\System\Security\LeafAccess\Model\LeafAccessModel
     */
    public function setLeafAccessApprovedValue($value, $key) {
        $this->leafAccessApprovedValue [$key] = $value;
        return $this;
    }

    /**
     * Return Leaf Approved Access Value
     * @param array|int $key List Of Primary Key.
     * @return bool|array
     */
    public function getLeafAccessApprovedValue($key) {
        return $this->leafAccessApprovedValue [$key];
    }

    /**
     * Set Leaf Review Access Value
     * @param bool|array $value
     * @param array|int $key List Of Primary Key.
     * @return \Core\System\Security\LeafAccess\Model\LeafAccessModel
     */
    public function setLeafAccessReviewValue($value, $key) {
        $this->leafAccessReviewValue [$key] = $value;
        return $this;
    }

    /**
     * Return Leaf Review Access Value
     * @param array|int $key List Of Primary Key.
     * @return bool|array
     */
    public function getLeafAccessReviewValue($key) {
        return $this->leafAccessReviewValue [$key];
    }

    /**
     * Set Leaf Print Access Value
     * @param bool|array $value
     * @param array|int $key List Of Primary Key.
     * @return \Core\System\Security\LeafAccess\Model\LeafAccessModel
     */
    public function setLeafAccessPrintValue($value, $key) {
        $this->leafAccessPrintValue [$key] = $value;
        return $this;
    }

    /**
     * Return Leaf Print Access Value
     * @param array|int $key List Of Primary Key.
     * @return bool|array
     */
    public function getLeafAccessPrintValue($key) {
        return $this->leafAccessPrintValue [$key];
    }

    /**
     * Set Leaf Post Access Value
     * @param bool|array $value
     * @param array|int $key List Of Primary Key.
     * @return \Core\System\Security\LeafAccess\Model\LeafAccessModel
     */
    public function setLeafAccessPostValue($value, $key) {
        $this->leafAccessPostValue [$key] = $value;
        return $this;
    }

    /**
     * Return Leaf Post Access Value
     * @param array|int $key List Of Primary Key.
     * @return bool|array
     */
    public function getLeafAccessPostValue($key) {
        return $this->leafAccessPostValue [$key];
    }

}

?>