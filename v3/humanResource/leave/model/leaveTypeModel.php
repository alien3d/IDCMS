<?php

namespace Core\HumanResource\Leave\LeaveType\Model;

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
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot); // start
require_once($newFakeDocumentRoot . "library/class/classValidation.php");

/**
 * Class LeaveType
 * This is Leave Type model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\HumanResource\Leave\LeaveType\Model;
 * @subpackage Leave
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class LeaveTypeModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $leaveTypeId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Name
     * @var string
     */
    private $leaveTypeName;

    /**
     * Code
     * @var string
     */
    private $leaveCode;

    /**
     * Per Year
     * @var string
     */
    private $leaveTypePerYear;

    /**
     * Description
     * @var string
     */
    private $leaveTypeDescription;

    /**
     * Is Unpaid Leave
     * @var bool
     */
    private $isUnpaidLeave;

    /**
     * Is Carried Forward Leave
     * @var bool
     */
    private $isCarriedForward;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('leaveType');
        $this->setPrimaryKeyName('leaveTypeId');
        $this->setMasterForeignKeyName('leaveTypeId');
        $this->setFilterCharacter('leaveTypeDescription');
        //$this->setFilterCharacter('leaveTypeNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['leaveTypeId'])) {
            $this->setLeaveTypeId($this->strict($_POST ['leaveTypeId'], 'string'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'string'));
        }
        if (isset($_POST ['leaveTypeName'])) {
            $this->setLeaveTypeName($this->strict($_POST ['leaveTypeName'], 'string'));
        }
        if (isset($_POST ['leaveCode'])) {
            $this->setLeaveCode($this->strict($_POST ['leaveCode'], 'string'));
        }
        if (isset($_POST ['leaveTypePerYear'])) {
            $this->setLeaveTypePerYear($this->strict($_POST ['leaveTypePerYear'], 'string'));
        }
        if (isset($_POST ['leaveTypeDescription'])) {
            $this->setLeaveTypeDescription($this->strict($_POST ['leaveTypeDescription'], 'string'));
        }
        if (isset($_POST ['isUnpaidLeave'])) {
            $this->setIsUnpaidLeave($this->strict($_POST ['isUnpaidLeave'], 'bool'));
        }
        if (isset($_POST ['isCarriedForward'])) {
            $this->setIsCarriedForward($this->strict($_POST ['isCarriedForward'], 'bool'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['leaveTypeId'])) {
            $this->setLeaveTypeId($this->strict($_GET ['leaveTypeId'], 'string'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'string'));
        }
        if (isset($_GET ['leaveTypeName'])) {
            $this->setLeaveTypeName($this->strict($_GET ['leaveTypeName'], 'string'));
        }
        if (isset($_GET ['leaveCode'])) {
            $this->setLeaveCode($this->strict($_GET ['leaveCode'], 'string'));
        }
        if (isset($_GET ['leaveTypePerYear'])) {
            $this->setLeaveTypePerYear($this->strict($_GET ['leaveTypePerYear'], 'string'));
        }
        if (isset($_GET ['leaveTypeDescription'])) {
            $this->setLeaveTypeDescription($this->strict($_GET ['leaveTypeDescription'], 'string'));
        }
        if (isset($_GET ['isUnpaidLeave'])) {
            $this->setIsUnpaidLeave($this->strict($_GET ['isUnpaidLeave'], 'bool'));
        }
        if (isset($_GET ['isCarriedForward'])) {
            $this->setIsCarriedForward($this->strict($_GET ['isCarriedForward'], 'bool'));
        }
        if (isset($_GET ['leaveTypeId'])) {
            $this->setTotal(count($_GET ['leaveTypeId']));
            if (is_array($_GET ['leaveTypeId'])) {
                $this->leaveTypeId = array();
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
            if (isset($_GET ['leaveTypeId'])) {
                $this->setLeaveTypeId($this->strict($_GET ['leaveTypeId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getLeaveTypeId($i, 'array') . ",";
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
     * Return Primary Key Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getLeaveTypeId($key, $type) {
        if ($type == 'single') {
            return $this->leaveTypeId;
        } else {
            if ($type == 'array') {
                return $this->leaveTypeId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getLeaveTypeId ?")
                );
                exit();
            }
        }
    }

    /**
     * Set Primary Key Value
     * @param int|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return \Core\HumanResource\Leave\LeaveType\Model\LeaveTypeModel
     */
    public function setLeaveTypeId($value, $key, $type) {
        if ($type == 'single') {
            $this->leaveTypeId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->leaveTypeId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setLeaveTypeId?")
                );
                exit();
            }
        }
    }

    /**
     * Create
     * @see ValidationClass::create()
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * To Return Company
     * @return int $companyId
     */
    public function getCompanyId() {
        return $this->companyId;
    }

    /**
     * To Set Company
     * @param int $companyId Company
     * @return \Core\HumanResource\Leave\LeaveType\Model\LeaveTypeModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Name
     * @return string $leaveTypeName
     */
    public function getLeaveTypeName() {
        return $this->leaveTypeName;
    }

    /**
     * To Set Name
     * @param string $leaveTypeName Name
     * @return \Core\HumanResource\Leave\LeaveType\Model\LeaveTypeModel
     */
    public function setLeaveTypeName($leaveTypeName) {
        $this->leaveTypeName = $leaveTypeName;
        return $this;
    }

    /**
     * To Return Code
     * @return string $leaveCode
     */
    public function getLeaveCode() {
        return $this->leaveCode;
    }

    /**
     * To Set Code
     * @param string $leaveCode Leave Code
     * @return \Core\HumanResource\Leave\LeaveType\Model\LeaveTypeModel
     */
    public function setLeaveCode($leaveCode) {
        $this->leaveCode = $leaveCode;
        return $this;
    }

    /**
     * To Return Per Year
     * @return string $leaveTypePerYear
     */
    public function getLeaveTypePerYear() {
        return $this->leaveTypePerYear;
    }

    /**
     * To Set Per Year
     * @param string $leaveTypePerYear Per Year
     * @return \Core\HumanResource\Leave\LeaveType\Model\LeaveTypeModel
     */
    public function setLeaveTypePerYear($leaveTypePerYear) {
        $this->leaveTypePerYear = $leaveTypePerYear;
        return $this;
    }

    /**
     * To Return Description
     * @return string $leaveTypeDescription
     */
    public function getLeaveTypeDescription() {
        return $this->leaveTypeDescription;
    }

    /**
     * To Set Description
     * @param string $leaveTypeDescription Description
     * @return \Core\HumanResource\Leave\LeaveType\Model\LeaveTypeModel
     */
    public function setLeaveTypeDescription($leaveTypeDescription) {
        $this->leaveTypeDescription = $leaveTypeDescription;
        return $this;
    }

    /**
     * To Return Is Unpaid Leave
     * @return bool $isUnpaidLeave
     */
    public function getIsUnpaidLeave() {
        return $this->isUnpaidLeave;
    }

    /**
     * To Set Is Unpaid Leave
     * @param bool $isUnpaidLeave Is Leave
     * @return \Core\HumanResource\Leave\LeaveType\Model\LeaveTypeModel
     */
    public function setIsUnpaidLeave($isUnpaidLeave) {
        $this->isUnpaidLeave = $isUnpaidLeave;
        return $this;
    }

    /**
     * To Return Is Carried Forward
     * @return bool $isCarriedForward
     */
    public function getIsCarriedForward() {
        return $this->isCarriedForward;
    }

    /**
     * To Set Is Carried Forward
     * @param bool $isCarriedForward Is Forward
     * @return \Core\HumanResource\Leave\LeaveType\Model\LeaveTypeModel
     */
    public function setIsCarriedForward($isCarriedForward) {
        $this->isCarriedForward = $isCarriedForward;
        return $this;
    }

}

?>