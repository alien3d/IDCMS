<?php

namespace Core\HumanResource\Leave\Leave\Model;

use Core\Validation\ValidationClass;

$x = addslashes(realpath(__FILE__));
// auto detect if \ consider come from windows else / from linux
$pos = strpos($x, "\\");
if ($pos !== false) {
    $d = explode("\\", $x);
} else {
    $d = explode("/", $x);
}
$newPath = null;
for ($i = 0; $i < count($d); $i++) {
    // if find the library or package then stop
    if ($d[$i] == 'library' || $d[$i] == 'package') {
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
 * Class Leave
 * This is leave model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\HumanResource\Leave\Leave\Model;
 * @subpackage Leave
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class LeaveModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $leaveId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Type
     * @var int
     */
    private $leaveTypeId;

    /**
     * Employee
     * @var int
     */
    private $employeeId;

    /**
     * Document Number
     * @var string
     */
    private $documentNumber;

    /**
     * Start Date
     * @var string
     */
    private $leaveStartDate;

    /**
     * End Date
     * @var string
     */
    private $leaveEndDate;

    /**
     * Description
     * @var string
     */
    private $leaveDescription;

    /**
     * Is Rejected
     * @var bool
     */
    private $isRejected;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('leave');
        $this->setPrimaryKeyName('leaveId');
        $this->setMasterForeignKeyName('leaveId');
        $this->setFilterCharacter('leaveDescription');
        //$this->setFilterCharacter('leaveNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['leaveId'])) {
            $this->setLeaveId($this->strict($_POST ['leaveId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['leaveTypeId'])) {
            $this->setLeaveTypeId($this->strict($_POST ['leaveTypeId'], 'int'));
        }
        if (isset($_POST ['employeeId'])) {
            $this->setEmployeeId($this->strict($_POST ['employeeId'], 'int'));
        }
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string'));
        }
        if (isset($_POST ['leaveStartDate'])) {
            $this->setLeaveStartDate($this->strict($_POST ['leaveStartDate'], 'date'));
        }
        if (isset($_POST ['leaveEndDate'])) {
            $this->setLeaveEndDate($this->strict($_POST ['leaveEndDate'], 'date'));
        }
        if (isset($_POST ['leaveDescription'])) {
            $this->setLeaveDescription($this->strict($_POST ['leaveDescription'], 'string'));
        }
        if (isset($_POST ['isRejected'])) {
            $this->setIsRejected($this->strict($_POST ['isRejected'], 'bool'));
        }
        if (isset($_POST ['from'])) {
            $this->setFrom($this->strict($_POST ['from'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['leaveId'])) {
            $this->setLeaveId($this->strict($_GET ['leaveId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['leaveTypeId'])) {
            $this->setLeaveTypeId($this->strict($_GET ['leaveTypeId'], 'int'));
        }
        if (isset($_GET ['employeeId'])) {
            $this->setEmployeeId($this->strict($_GET ['employeeId'], 'int'));
        }
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string'));
        }
        if (isset($_GET ['leaveStartDate'])) {
            $this->setLeaveStartDate($this->strict($_GET ['leaveStartDate'], 'date'));
        }
        if (isset($_GET ['leaveEndDate'])) {
            $this->setLeaveEndDate($this->strict($_GET ['leaveEndDate'], 'date'));
        }
        if (isset($_GET ['leaveDescription'])) {
            $this->setLeaveDescription($this->strict($_GET ['leaveDescription'], 'string'));
        }
        if (isset($_GET ['isRejected'])) {
            $this->setIsRejected($this->strict($_GET ['isRejected'], 'bool'));
        }
        if (isset($_GET ['from'])) {
            $this->setFrom($this->strict($_GET ['from'], 'string'));
        }
        if (isset($_GET ['leaveId'])) {
            $this->setTotal(count($_GET ['leaveId']));
            if (is_array($_GET ['leaveId'])) {
                $this->leaveId = array();
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
            if (isset($_GET ['leaveId'])) {
                $this->setLeaveId($this->strict($_GET ['leaveId'] [$i], 'numeric'), $i, 'array');
            }
            if (isset($_GET ['isDefault'])) {
                if ($_GET ['isDefault'] [$i] == 'true') {
                    $this->setIsDefault(1, $i, 'array');
                } else if ($_GET ['isDefault'] [$i] == 'false') {
                    $this->setIsDefault(0, $i, 'array');
                }
            }
            if (isset($_GET ['isNew'])) {
                if ($_GET ['isNew'] [$i] == 'true') {
                    $this->setIsNew(1, $i, 'array');
                } else if ($_GET ['isNew'] [$i] == 'false') {
                    $this->setIsNew(0, $i, 'array');
                }
            }
            if (isset($_GET ['isDraft'])) {
                if ($_GET ['isDraft'] [$i] == 'true') {
                    $this->setIsDraft(1, $i, 'array');
                } else if ($_GET ['isDraft'] [$i] == 'false') {
                    $this->setIsDraft(0, $i, 'array');
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
                } else if ($_GET ['isDelete'] [$i] == 'false') {
                    $this->setIsDelete(0, $i, 'array');
                }
            }
            if (isset($_GET ['isActive'])) {
                if ($_GET ['isActive'] [$i] == 'true') {
                    $this->setIsActive(1, $i, 'array');
                } else if ($_GET ['isActive'] [$i] == 'false') {
                    $this->setIsActive(0, $i, 'array');
                }
            }
            if (isset($_GET ['isApproved'])) {
                if ($_GET ['isApproved'] [$i] == 'true') {
                    $this->setIsApproved(1, $i, 'array');
                } else if ($_GET ['isApproved'] [$i] == 'false') {
                    $this->setIsApproved(0, $i, 'array');
                }
            }
            if (isset($_GET ['isReview'])) {
                if ($_GET ['isReview'] [$i] == 'true') {
                    $this->setIsReview(1, $i, 'array');
                } else if ($_GET ['isReview'] [$i] == 'false') {
                    $this->setIsReview(0, $i, 'array');
                }
            }
            if (isset($_GET ['isPost'])) {
                if ($_GET ['isPost'] [$i] == 'true') {
                    $this->setIsPost(1, $i, 'array');
                } else if ($_GET ['isPost'] [$i] == 'false') {
                    $this->setIsPost(0, $i, 'array');
                }
            }
            $primaryKeyAll .= $this->getLeaveId($i, 'array') . ",";
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
        } else if ($this->getVendor() == self::MSSQL) {
            $this->setExecuteTime("'" . date("Y-m-d H:i:s.u") . "'");
        } else if ($this->getVendor() == self::ORACLE) {
            $this->setExecuteTime("to_date('" . date("Y-m-d H:i:s") . "','YYYY-MM-DD HH24:MI:SS')");
        }
    }

    /**
     * Return Primary Key Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getLeaveId($key, $type) {
        if ($type == 'single') {
            return $this->leaveId;
        } else if ($type == 'array') {
            return $this->leaveId [$key];
        } else {
            echo json_encode(
                    array("success" => false, "message" => "Cannot Identify Type String Or Array:getleaveId ?")
            );
            exit();
        }
    }

    /**
     * Set Primary Key Value
     * @param int|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return \Core\HumanResource\Leave\Leave\Model\LeaveModel
     */
    public function setLeaveId($value, $key, $type) {
        if ($type == 'single') {
            $this->leaveId = $value;
            return $this;
        } else if ($type == 'array') {
            $this->leaveId[$key] = $value;
            return $this;
        } else {
            echo json_encode(
                    array("success" => false, "message" => "Cannot Identify Type String Or Array:setleaveId?")
            );
            exit();
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
     * @return \Core\HumanResource\Leave\Leave\Model\LeaveModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Type
     * @return int $leaveTypeId
     */
    public function getLeaveTypeId() {
        return $this->leaveTypeId;
    }

    /**
     * To Set Type
     * @param int $leaveTypeId Type
     * @return \Core\HumanResource\Leave\Leave\Model\LeaveModel
     */
    public function setLeaveTypeId($leaveTypeId) {
        $this->leaveTypeId = $leaveTypeId;
        return $this;
    }

    /**
     * To Return Employee
     * @return int $employeeId
     */
    public function getEmployeeId() {
        return $this->employeeId;
    }

    /**
     * To Set Employee
     * @param int $employeeId Employee
     * @return \Core\HumanResource\Leave\Leave\Model\LeaveModel
     */
    public function setEmployeeId($employeeId) {
        $this->employeeId = $employeeId;
        return $this;
    }

    /**
     * To Return Document Number
     * @return string $documentNumber
     */
    public function getDocumentNumber() {
        return $this->documentNumber;
    }

    /**
     * To Set Document Number
     * @param string $documentNumber Document Number
     * @return \Core\HumanResource\Leave\Leave\Model\LeaveModel
     */
    public function setDocumentNumber($documentNumber) {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    /**
     * To Return Start Date
     * @return string $leaveStartDate
     */
    public function getLeaveStartDate() {
        return $this->leaveStartDate;
    }

    /**
     * To Set Start Date
     * @param string $leaveStartDate Start Date
     * @return \Core\HumanResource\Leave\Leave\Model\LeaveModel
     */
    public function setLeaveStartDate($leaveStartDate) {
        $this->leaveStartDate = $leaveStartDate;
        return $this;
    }

    /**
     * To Return End Date
     * @return string $leaveEndDate
     */
    public function getLeaveEndDate() {
        return $this->leaveEndDate;
    }

    /**
     * To Set End Date
     * @param string $leaveEndDate End Date
     * @return \Core\HumanResource\Leave\Leave\Model\LeaveModel
     */
    public function setLeaveEndDate($leaveEndDate) {
        $this->leaveEndDate = $leaveEndDate;
        return $this;
    }

    /**
     * To Return Description
     * @return string $leaveDescription
     */
    public function getLeaveDescription() {
        return $this->leaveDescription;
    }

    /**
     * To Set Description
     * @param string $leaveDescription Description
     * @return \Core\HumanResource\Leave\Leave\Model\LeaveModel
     */
    public function setLeaveDescription($leaveDescription) {
        $this->leaveDescription = $leaveDescription;
        return $this;
    }

}

?>