<?php

namespace Core\System\Security\StaffWebAccess\Model;

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
 * Class StaffWebAccessModel
 * this is Staff Web Access model file.
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\System\Security\StaffWebAccess\Model
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class StaffWebAccessModel extends ValidationClass {

    /**
     * staffWebAccessId
     * @var int
     */
    private $staffWebAccessId;

    /**
     * staffId
     * @var int
     */
    private $staffId;

    /**
     * staffWebAccessLogIn
     * @var string
     */
    private $staffWebAccessLogIn;

    /**
     * staffWebAccessLogOut
     * @var string
     */
    private $staffWebAccessLogOut;

    /**
     * phpSession
     * @var string
     */
    private $phpSession;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         */
        $this->setTableName('staffwebaccess');
        $this->setPrimaryKeyName('staffWebAccessId');
        $this->setMasterForeignKeyName('staffWebAccessId');
        $this->setFilterCharacter('staffwebaccessDescription');
        //$this->setFilterCharacter('staffwebaccessNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['staffWebAccessId'])) {
            $this->setStaffWebAccessId($this->strict($_POST ['staffWebAccessId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['staffId'])) {
            $this->setStaffId($this->strict($_POST ['staffId'], 'integer'));
        }
        if (isset($_POST ['staffWebAccessLogIn'])) {
            $this->setStaffWebAccessLogIn($this->strict($_POST ['staffWebAccessLogIn'], 'string'));
        }
        if (isset($_POST ['staffWebAccessLogOut'])) {
            $this->setStaffWebAccessLogOut($this->strict($_POST ['staffWebAccessLogOut'], 'string'));
        }
        if (isset($_POST ['phpSession'])) {
            $this->setPhpSession($this->strict($_POST ['phpSession'], 'text'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['staffWebAccessId'])) {
            $this->setStaffWebAccessId($this->strict($_GET ['staffWebAccessId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['staffId'])) {
            $this->setStaffId($this->strict($_GET ['staffId'], 'integer'));
        }
        if (isset($_GET ['staffWebAccessLogIn'])) {
            $this->setStaffWebAccessLogIn($this->strict($_GET ['staffWebAccessLogIn'], 'string'));
        }
        if (isset($_GET ['staffWebAccessLogOut'])) {
            $this->setStaffWebAccessLogOut($this->strict($_GET ['staffWebAccessLogOut'], 'string'));
        }
        if (isset($_GET ['phpSession'])) {
            $this->setPhpSession($this->strict($_GET ['phpSession'], 'text'));
        }
        if (isset($_GET ['staffWebAccessId'])) {
            $this->setTotal(count($_GET ['staffWebAccessId']));
            if (is_array($_GET ['staffWebAccessId'])) {
                $this->staffWebAccessId = array();
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
            if (isset($_GET ['staffWebAccessId'])) {
                $this->setStaffWebAccessId($this->strict($_GET ['staffWebAccessId'] [$i], 'numeric'), $i, 'array');
            }
            if (isset($_GET ['isDefault'])) {
                if ($_GET ['isDefault'] [$i] == 'true') {
                    $this->setIsDefault(1, $i, 'array');
                } else {
                    if ($_GET ['isDefault'] [$i] == 'false') {
                        $this->setIsDefault(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isNew'])) {
                if ($_GET ['isNew'] [$i] == 'true') {
                    $this->setIsNew(1, $i, 'array');
                } else {
                    if ($_GET ['isNew'] [$i] == 'false') {
                        $this->setIsNew(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isDraft'])) {
                if ($_GET ['isDraft'] [$i] == 'true') {
                    $this->setIsDraft(1, $i, 'array');
                } else {
                    if ($_GET ['isDraft'] [$i] == 'false') {
                        $this->setIsDraft(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isUpdate'])) {
                if ($_GET ['isUpdate'] [$i] == 'true') {
                    $this->setIsUpdate(1, $i, 'array');
                }
                if ($_GET ['isUpdate'] [$i] == 'false') {
                    $this->setIsUpdate(0, $i, 'array');
                }
            }
            if (isset($_GET ['isDelete'])) {
                if ($_GET ['isDelete'] [$i] == 'true') {
                    $this->setIsDelete(1, $i, 'array');
                } else {
                    if ($_GET ['isDelete'] [$i] == 'false') {
                        $this->setIsDelete(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isActive'])) {
                if ($_GET ['isActive'] [$i] == 'true') {
                    $this->setIsActive(1, $i, 'array');
                } else {
                    if ($_GET ['isActive'] [$i] == 'false') {
                        $this->setIsActive(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isApproved'])) {
                if ($_GET ['isApproved'] [$i] == 'true') {
                    $this->setIsApproved(1, $i, 'array');
                } else {
                    if ($_GET ['isApproved'] [$i] == 'false') {
                        $this->setIsApproved(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isReview'])) {
                if ($_GET ['isReview'] [$i] == 'true') {
                    $this->setIsReview(1, $i, 'array');
                } else {
                    if ($_GET ['isReview'] [$i] == 'false') {
                        $this->setIsReview(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isPost'])) {
                if ($_GET ['isPost'] [$i] == 'true') {
                    $this->setIsPost(1, $i, 'array');
                } else {
                    if ($_GET ['isPost'] [$i] == 'false') {
                        $this->setIsPost(0, $i, 'array');
                    }
                }
            }
            $primaryKeyAll .= $this->getStaffWebAccessId($i, 'array') . ",";
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
     * Create
     * @see ValidationClass::create()
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
     * Update
     * @see ValidationClass::update()
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
     * Delete
     * @see ValidationClass::delete()
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
     * Draft
     * @see ValidationClass::draft()
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
     * Approved
     * @see ValidationClass::approved()
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
     * Review
     * @see ValidationClass::review()
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
     * Post
     * @see ValidationClass::post()
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
     */
    public function setStaffWebAccessId($value, $key, $type) {
        if ($type == 'single') {
            $this->staffWebAccessId = $value;
        } else {
            if ($type == 'array') {
                $this->staffWebAccessId[$key] = $value;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setStaffWebAccessId?")
                );
                exit();
            }
        }
    }

    /**
     * Return Primary Key Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     */
    public function getStaffWebAccessId($key, $type) {
        if ($type == 'single') {
            return $this->staffWebAccessId;
        } else {
            if ($type == 'array') {
                return $this->staffWebAccessId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getStaffWebAccessId ?")
                );
                exit();
            }
        }
    }

    /**
     * To Return Staff Primary Key
     * @return int $staffId Staff Primary Key
     */
    public function getStaffId() {
        return $this->staffId;
    }

    /**
     * To Set Staff Primary Key
     * @param int $staffId Staff Primary Key
     * @return \Core\System\Security\StaffWebAccess\Model\StaffWebAccessModel
     */
    public function setStaffId($staffId) {
        $this->staffId = $staffId;
        return $this;
    }

    /**
     * To Return Login Time
     * @return string $staffWebAccessLogIn Login Time
     */
    public function getStaffWebAccessLogIn() {
        return $this->staffWebAccessLogIn;
    }

    /**
     * To Set Login Time
     * @param string $staffWebAccessLogIn Login Time
     * @return \Core\System\Security\StaffWebAccess\Model\StaffWebAccessModel
     */
    public function setStaffWebAccessLogIn($staffWebAccessLogIn) {
        $this->staffWebAccessLogIn = $staffWebAccessLogIn;
        return $this;
    }

    /**
     * To Return Logout time
     * @return string $staffWebAccessLogOut Logout Time
     */
    public function getStaffWebAccessLogOut() {
        return $this->staffWebAccessLogOut;
    }

    /**
     * To Set Logout Time
     * @param string $staffWebAccessLogOut Logout Time
     * @return \Core\System\Security\StaffWebAccess\Model\StaffWebAccessModel
     */
    public function setStaffWebAccessLogOut($staffWebAccessLogOut) {
        $this->staffWebAccessLogOut = $staffWebAccessLogOut;
        return $this;
    }

    /**
     * To Return Session
     * @return string $phpSession Session
     */
    public function getPhpSession() {
        return $this->phpSession;
    }

    /**
     * To Set Session
     * @param string $phpSession Session
     * @return \Core\System\Security\StaffWebAccess\Model\StaffWebAccessModel
     */
    public function setPhpSession($phpSession) {
        $this->phpSession = $phpSession;
        return $this;
    }

}

?>