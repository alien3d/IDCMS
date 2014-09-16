<?php

namespace Core\System\Security\ApplicationAccess\Model;

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
 * Class ApplicationAccessModel
 * this is application access model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\System\Security\ApplicationAccess\Model
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ApplicationAccessModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $applicationAccessId;

    /**
     * Application
     * @var int
     */
    private $applicationId;

    /**
     * Role
     * @var int
     */
    private $roleId;

    /**
     * Value
     * @var int
     */
    private $applicationAccessValue;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         */
        $this->setTableName(strtolower('applicationAccess'));
        $this->setPrimaryKeyName('applicationAccessId');
        $this->setMasterForeignKeyName('applicationAccessId');
        // $this->setFilterCharacter('applicationAccessDescription');
        //$this->setFilterCharacter('applicationAccessNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['applicationAccessId'])) {
            $this->setApplicationAccessId($this->strict($_POST ['applicationAccessId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['applicationId'])) {
            $this->setApplicationId($this->strict($_POST ['applicationId'], 'integer'));
        }
        if (isset($_POST ['roleId'])) {
            $this->setRoleId($this->strict($_POST ['roleId'], 'integer'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['applicationAccessId'])) {
            $this->setApplicationAccessId($this->strict($_GET ['applicationAccessId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['applicationId'])) {
            $this->setApplicationId($this->strict($_GET ['applicationId'], 'integer'));
        }
        if (isset($_GET ['roleId'])) {
            $this->setRoleId($this->strict($_GET ['roleId'], 'integer'));
        }
        if (isset($_GET ['applicationAccessValue'])) {
            if (is_array($_GET ['applicationAccessValue'])) {
                $this->applicationAccessValue = array();
            }
        }
        if (isset($_GET ['applicationAccessId'])) {
            $this->setTotal(count($_GET ['applicationAccessId']));
            if (is_array($_GET ['applicationAccessId'])) {
                $this->applicationAccessId = array();
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
            if (isset($_GET ['applicationAccessId'])) {
                $this->setApplicationAccessId(
                        $this->strict($_GET ['applicationAccessId'] [$i], 'numeric'), $i, 'array'
                );
            }

            if (isset($_GET ['applicationAccessValue'])) {
                if ($_GET ['applicationAccessValue'] [$i] == 'true' || $_GET ['applicationAccessValue'] [$i] == 1) {
                    $this->setApplicationAccessValue(1, $i, 'array');
                } else {
                    if ($_GET ['applicationAccessValue'] [$i] == 'false' || $_GET ['isPost'] [$i] == 0) {
                        $this->setApplicationAccessValue(0, $i, 'array');
                    }
                }
            }
            $primaryKeyAll .= $this->getApplicationAccessId($i, 'array') . ",";
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
     * Review Seting
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
     * Set Application Access Primary Key Value
     * @param int|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return $this
     */
    public function setApplicationAccessId($value, $key, $type) {
        if ($type == 'single') {
            $this->applicationAccessId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->applicationAccessId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:setApplicationAccessId?"
                        )
                );
                exit();
            }
        }
    }

    /**
     * Return application Access Primary Key Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     */
    public function getApplicationAccessId($key, $type) {
        if ($type == 'single') {
            return $this->applicationAccessId;
        } else {
            if ($type == 'array') {
                return $this->applicationAccessId [$key];
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:getApplicationAccessId ?"
                        )
                );
                exit();
            }
        }
    }

    /**
     * To Return Application Primary Key
     * @return int $applicationId Application Primary Key
     */
    public function getApplicationId() {
        return $this->applicationId;
    }

    /**
     * To Set Application Primary Key
     * @param int $applicationId Application Primary Key
     * @return \Core\System\Security\Applicationaccess\Model\ApplicationaccessModel
     */
    public function setApplicationId($applicationId) {
        $this->applicationId = $applicationId;
        return $this;
    }

    /**
     * To Return Role Primary Key
     * @return int $roleId Role Primary Key
     */
    public function getRoleId() {
        return $this->roleId;
    }

    /**
     * To Set Role Primary Key
     * @param int $roleId Role Primary Key
     * @return \Core\System\Security\Applicationaccess\Model\ApplicationaccessModel
     */
    public function setRoleId($roleId) {
        $this->roleId = $roleId;
        return $this;
    }

    /**
     * Set Application Access Value
     * @param bool|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     */
    public function setApplicationAccessValue($value, $key, $type) {
        if ($type == 'single') {
            $this->applicationAccessValue = $value;
        } else {
            if ($type == 'array') {
                $this->applicationAccessValue [$key] = $value;
            }
        }
    }

    /**
     * Return Application Access Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     */
    public function getApplicationAccessValue($key, $type) {
        if ($type == 'single') {
            return $this->applicationAccessValue;
        } else {
            if ($type == 'array') {
                return $this->applicationAccessValue [$key];
            }
        }
    }

}

?>