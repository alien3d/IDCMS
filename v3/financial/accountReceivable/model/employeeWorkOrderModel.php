<?php

namespace Core\Financial\AccountReceivable\EmployeeWorkOrder\Model;

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
 * Class EmployeeWorkOrder
 * This is employeeWorkOrder model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountReceivable\EmployeeWorkOrder\Model;
 * @subpackage AccountReceivable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class EmployeeWorkOrderModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $employeeWorkOrderId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Employee
     * @var int
     */
    private $employeeId;

    /**
     * Shift
     * @var int
     */
    private $shiftId;

    /**
     * Invoice Project
     * @var int
     */
    private $invoiceProjectId;

    /**
     * Invoice
     * @var int
     */
    private $invoiceId;

    /**
     * Milestone
     * @var int
     */
    private $milestoneId;

    /**
     * Branch
     * @var int
     */
    private $branchId;

    /**
     * Location
     * @var int
     */
    private $locationId;

    /**
     * Color
     * @var int
     */
    private $employeeWorkOrderColorId;

    /**
     * Document Number
     * @var string
     */
    private $documentNumber;

    /**
     * Date
     * @var date
     */
    private $employeeWorkOrderDate;

    /**
     * Start Date
     * @var time
     */
    private $employeeWorkOrderStartDate;

    /**
     * End Date
     * @var time
     */
    private $employeeWorkOrderEndDate;

    /**
     * Due Date
     * @var date
     */
    private $employeeWorkOrderDueDate;

    /**
     * Rate
     * @var double
     */
    private $employeeWorkOrderRate;

    /**
     * Description
     * @var string
     */
    private $employeeWorkOrderDescription;

    /**
     * Is Viewable
     * @var bool
     */
    private $isClientViewable;

    /**
     * Is All Day   Event
     * @var bool
     */
    private $isAllDayEvent;

    /**
     * Is Complete
     * @var bool
     */
    private $isComplete;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('employeeWorkOrder');
        $this->setPrimaryKeyName('employeeWorkOrderId');
        $this->setMasterForeignKeyName('employeeWorkOrderId');
        $this->setFilterCharacter('employeeWorkOrderDescription');
        //$this->setFilterCharacter('employeeWorkOrderNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['employeeWorkOrderId'])) {
            $this->setEmployeeWorkOrderId($this->strict($_POST ['employeeWorkOrderId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['employeeId'])) {
            $this->setEmployeeId($this->strict($_POST ['employeeId'], 'int'));
        }
        if (isset($_POST ['shiftId'])) {
            $this->setShiftId($this->strict($_POST ['shiftId'], 'int'));
        }
        if (isset($_POST ['invoiceProjectId'])) {
            $this->setInvoiceProjectId($this->strict($_POST ['invoiceProjectId'], 'int'));
        }
        if (isset($_POST ['invoiceId'])) {
            $this->setInvoiceId($this->strict($_POST ['invoiceId'], 'int'));
        }
        if (isset($_POST ['milestoneId'])) {
            $this->setMilestoneId($this->strict($_POST ['milestoneId'], 'int'));
        }
        if (isset($_POST ['branchId'])) {
            $this->setBranchId($this->strict($_POST ['branchId'], 'int'));
        }
        if (isset($_POST ['locationId'])) {
            $this->setLocationId($this->strict($_POST ['locationId'], 'int'));
        }
        if (isset($_POST ['employeeWorkOrderColorId'])) {
            $this->setEmployeeWorkOrderColorId($this->strict($_POST ['employeeWorkOrderColorId'], 'int'));
        }
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string'));
        }
        if (isset($_POST ['employeeWorkOrderDate'])) {
            $this->setEmployeeWorkOrderDate($this->strict($_POST ['employeeWorkOrderDate'], 'date'));
        }
        if (isset($_POST ['employeeWorkOrderStartDate'])) {
            $this->setEmployeeWorkOrderStartDate($this->strict($_POST ['employeeWorkOrderStartDate'], 'time'));
        }
        if (isset($_POST ['employeeWorkOrderEndDate'])) {
            $this->setEmployeeWorkOrderEndDate($this->strict($_POST ['employeeWorkOrderEndDate'], 'time'));
        }
        if (isset($_POST ['employeeWorkOrderDueDate'])) {
            $this->setEmployeeWorkOrderDueDate($this->strict($_POST ['employeeWorkOrderDueDate'], 'date'));
        }
        if (isset($_POST ['employeeWorkOrderRate'])) {
            $this->setEmployeeWorkOrderRate($this->strict($_POST ['employeeWorkOrderRate'], 'double'));
        }
        if (isset($_POST ['employeeWorkOrderDescription'])) {
            $this->setEmployeeWorkOrderDescription($this->strict($_POST ['employeeWorkOrderDescription'], 'string'));
        }
        if (isset($_POST ['isClientViewable'])) {
            $this->setIsClientViewable($this->strict($_POST ['isClientViewable'], 'bool'));
        }
        if (isset($_POST ['isAllDayEvent'])) {
            $this->setIsAllDayEvent($this->strict($_POST ['isAllDayEvent'], 'bool'));
        }
        if (isset($_POST ['isComplete'])) {
            $this->setIsComplete($this->strict($_POST ['isComplete'], 'bool'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['employeeWorkOrderId'])) {
            $this->setEmployeeWorkOrderId($this->strict($_GET ['employeeWorkOrderId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['employeeId'])) {
            $this->setEmployeeId($this->strict($_GET ['employeeId'], 'int'));
        }
        if (isset($_GET ['shiftId'])) {
            $this->setShiftId($this->strict($_GET ['shiftId'], 'int'));
        }
        if (isset($_GET ['invoiceProjectId'])) {
            $this->setInvoiceProjectId($this->strict($_GET ['invoiceProjectId'], 'int'));
        }
        if (isset($_GET ['invoiceId'])) {
            $this->setInvoiceId($this->strict($_GET ['invoiceId'], 'int'));
        }
        if (isset($_GET ['milestoneId'])) {
            $this->setMilestoneId($this->strict($_GET ['milestoneId'], 'int'));
        }
        if (isset($_GET ['branchId'])) {
            $this->setBranchId($this->strict($_GET ['branchId'], 'int'));
        }
        if (isset($_GET ['locationId'])) {
            $this->setLocationId($this->strict($_GET ['locationId'], 'int'));
        }
        if (isset($_GET ['employeeWorkOrderColorId'])) {
            $this->setEmployeeWorkOrderColorId($this->strict($_GET ['employeeWorkOrderColorId'], 'int'));
        }
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string'));
        }
        if (isset($_GET ['employeeWorkOrderDate'])) {
            $this->setEmployeeWorkOrderDate($this->strict($_GET ['employeeWorkOrderDate'], 'date'));
        }
        if (isset($_GET ['employeeWorkOrderStartDate'])) {
            $this->setEmployeeWorkOrderStartDate($this->strict($_GET ['employeeWorkOrderStartDate'], 'time'));
        }
        if (isset($_GET ['employeeWorkOrderEndDate'])) {
            $this->setEmployeeWorkOrderEndDate($this->strict($_GET ['employeeWorkOrderEndDate'], 'time'));
        }
        if (isset($_GET ['employeeWorkOrderDueDate'])) {
            $this->setEmployeeWorkOrderDueDate($this->strict($_GET ['employeeWorkOrderDueDate'], 'date'));
        }
        if (isset($_GET ['employeeWorkOrderRate'])) {
            $this->setEmployeeWorkOrderRate($this->strict($_GET ['employeeWorkOrderRate'], 'double'));
        }
        if (isset($_GET ['employeeWorkOrderDescription'])) {
            $this->setEmployeeWorkOrderDescription($this->strict($_GET ['employeeWorkOrderDescription'], 'string'));
        }
        if (isset($_GET ['isClientViewable'])) {
            $this->setIsClientViewable($this->strict($_GET ['isClientViewable'], 'bool'));
        }
        if (isset($_GET ['isAllDayEvent'])) {
            $this->setIsAllDayEvent($this->strict($_GET ['isAllDayEvent'], 'bool'));
        }
        if (isset($_GET ['isComplete'])) {
            $this->setIsComplete($this->strict($_GET ['isComplete'], 'bool'));
        }
        if (isset($_GET ['employeeWorkOrderId'])) {
            $this->setTotal(count($_GET ['employeeWorkOrderId']));
            if (is_array($_GET ['employeeWorkOrderId'])) {
                $this->employeeWorkOrderId = array();
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
            if (isset($_GET ['employeeWorkOrderId'])) {
                $this->setEmployeeWorkOrderId(
                        $this->strict($_GET ['employeeWorkOrderId'] [$i], 'numeric'), $i, 'array'
                );
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
            $primaryKeyAll .= $this->getEmployeeWorkOrderId($i, 'array') . ",";
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
    public function getEmployeeWorkOrderId($key, $type) {
        if ($type == 'single') {
            return $this->employeeWorkOrderId;
        } else if ($type == 'array') {
            return $this->employeeWorkOrderId [$key];
        } else {
            echo json_encode(
                    array("success" => false, "message" => "Cannot Identify Type String Or Array:getemployeeWorkOrderId ?")
            );
            exit();
        }
    }

    /**
     * Set Primary Key Value
     * @param int|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return \Core\Financial\AccountReceivable\EmployeeWorkOrder\Model\EmployeeWorkOrderModel
     */
    public function setEmployeeWorkOrderId($value, $key, $type) {
        if ($type == 'single') {
            $this->employeeWorkOrderId = $value;
            return $this;
        } else if ($type == 'array') {
            $this->employeeWorkOrderId[$key] = $value;
            return $this;
        } else {
            echo json_encode(
                    array("success" => false, "message" => "Cannot Identify Type String Or Array:setemployeeWorkOrderId?")
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
     * @return \Core\Financial\AccountReceivable\EmployeeWorkOrder\Model\EmployeeWorkOrderModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
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
     * @return \Core\Financial\AccountReceivable\EmployeeWorkOrder\Model\EmployeeWorkOrderModel
     */
    public function setEmployeeId($employeeId) {
        $this->employeeId = $employeeId;
        return $this;
    }

    /**
     * To Return Shift
     * @return int $shiftId
     */
    public function getShiftId() {
        return $this->shiftId;
    }

    /**
     * To Set Shift
     * @param int $shiftId Shift
     * @return \Core\Financial\AccountReceivable\EmployeeWorkOrder\Model\EmployeeWorkOrderModel
     */
    public function setShiftId($shiftId) {
        $this->shiftId = $shiftId;
        return $this;
    }

    /**
     * To Return Invoice Project
     * @return int $invoiceProjectId
     */
    public function getInvoiceProjectId() {
        return $this->invoiceProjectId;
    }

    /**
     * To Set Invoice Project
     * @param int $invoiceProjectId Invoice Project
     * @return \Core\Financial\AccountReceivable\EmployeeWorkOrder\Model\EmployeeWorkOrderModel
     */
    public function setInvoiceProjectId($invoiceProjectId) {
        $this->invoiceProjectId = $invoiceProjectId;
        return $this;
    }

    /**
     * To Return Invoice
     * @return int $invoiceId
     */
    public function getInvoiceId() {
        return $this->invoiceId;
    }

    /**
     * To Set Invoice
     * @param int $invoiceId Invoice
     * @return \Core\Financial\AccountReceivable\EmployeeWorkOrder\Model\EmployeeWorkOrderModel
     */
    public function setInvoiceId($invoiceId) {
        $this->invoiceId = $invoiceId;
        return $this;
    }

    /**
     * To Return Milestone
     * @return int $milestoneId
     */
    public function getMilestoneId() {
        return $this->milestoneId;
    }

    /**
     * To Set Milestone
     * @param int $milestoneId Milestone
     * @return \Core\Financial\AccountReceivable\EmployeeWorkOrder\Model\EmployeeWorkOrderModel
     */
    public function setMilestoneId($milestoneId) {
        $this->milestoneId = $milestoneId;
        return $this;
    }

    /**
     * To Return Branch
     * @return int $branchId
     */
    public function getBranchId() {
        return $this->branchId;
    }

    /**
     * To Set Branch
     * @param int $branchId Branch
     * @return \Core\Financial\AccountReceivable\EmployeeWorkOrder\Model\EmployeeWorkOrderModel
     */
    public function setBranchId($branchId) {
        $this->branchId = $branchId;
        return $this;
    }

    /**
     * To Return Location
     * @return int $locationId
     */
    public function getLocationId() {
        return $this->locationId;
    }

    /**
     * To Set Location
     * @param int $locationId Location
     * @return \Core\Financial\AccountReceivable\EmployeeWorkOrder\Model\EmployeeWorkOrderModel
     */
    public function setLocationId($locationId) {
        $this->locationId = $locationId;
        return $this;
    }

    /**
     * To Return Color
     * @return int $employeeWorkOrderColorId
     */
    public function getEmployeeWorkOrderColorId() {
        return $this->employeeWorkOrderColorId;
    }

    /**
     * To Set Color
     * @param int $employeeWorkOrderColorId Color
     * @return \Core\Financial\AccountReceivable\EmployeeWorkOrder\Model\EmployeeWorkOrderModel
     */
    public function setEmployeeWorkOrderColorId($employeeWorkOrderColorId) {
        $this->employeeWorkOrderColorId = $employeeWorkOrderColorId;
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
     * @return \Core\Financial\AccountReceivable\EmployeeWorkOrder\Model\EmployeeWorkOrderModel
     */
    public function setDocumentNumber($documentNumber) {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    /**
     * To Return Date
     * @return date $employeeWorkOrderDate
     */
    public function getEmployeeWorkOrderDate() {
        return $this->employeeWorkOrderDate;
    }

    /**
     * To Set Date
     * @param date $employeeWorkOrderDate Date
     * @return \Core\Financial\AccountReceivable\EmployeeWorkOrder\Model\EmployeeWorkOrderModel
     */
    public function setEmployeeWorkOrderDate($employeeWorkOrderDate) {
        $this->employeeWorkOrderDate = $employeeWorkOrderDate;
        return $this;
    }

    /**
     * To Return Start Date
     * @return time $employeeWorkOrderStartDate
     */
    public function getEmployeeWorkOrderStartDate() {
        return $this->employeeWorkOrderStartDate;
    }

    /**
     * To Set Start Date
     * @param time $employeeWorkOrderStartDate Start Date
     * @return \Core\Financial\AccountReceivable\EmployeeWorkOrder\Model\EmployeeWorkOrderModel
     */
    public function setEmployeeWorkOrderStartDate($employeeWorkOrderStartDate) {
        $this->employeeWorkOrderStartDate = $employeeWorkOrderStartDate;
        return $this;
    }

    /**
     * To Return End Date
     * @return time $employeeWorkOrderEndDate
     */
    public function getEmployeeWorkOrderEndDate() {
        return $this->employeeWorkOrderEndDate;
    }

    /**
     * To Set End Date
     * @param time $employeeWorkOrderEndDate End Date
     * @return \Core\Financial\AccountReceivable\EmployeeWorkOrder\Model\EmployeeWorkOrderModel
     */
    public function setEmployeeWorkOrderEndDate($employeeWorkOrderEndDate) {
        $this->employeeWorkOrderEndDate = $employeeWorkOrderEndDate;
        return $this;
    }

    /**
     * To Return Due Date
     * @return date $employeeWorkOrderDueDate
     */
    public function getEmployeeWorkOrderDueDate() {
        return $this->employeeWorkOrderDueDate;
    }

    /**
     * To Set Due Date
     * @param date $employeeWorkOrderDueDate Due Date
     * @return \Core\Financial\AccountReceivable\EmployeeWorkOrder\Model\EmployeeWorkOrderModel
     */
    public function setEmployeeWorkOrderDueDate($employeeWorkOrderDueDate) {
        $this->employeeWorkOrderDueDate = $employeeWorkOrderDueDate;
        return $this;
    }

    /**
     * To Return Rate
     * @return double $employeeWorkOrderRate
     */
    public function getEmployeeWorkOrderRate() {
        return $this->employeeWorkOrderRate;
    }

    /**
     * To Set Rate
     * @param double $employeeWorkOrderRate Rate
     * @return \Core\Financial\AccountReceivable\EmployeeWorkOrder\Model\EmployeeWorkOrderModel
     */
    public function setEmployeeWorkOrderRate($employeeWorkOrderRate) {
        $this->employeeWorkOrderRate = $employeeWorkOrderRate;
        return $this;
    }

    /**
     * To Return Description
     * @return string $employeeWorkOrderDescription
     */
    public function getEmployeeWorkOrderDescription() {
        return $this->employeeWorkOrderDescription;
    }

    /**
     * To Set Description
     * @param string $employeeWorkOrderDescription Description
     * @return \Core\Financial\AccountReceivable\EmployeeWorkOrder\Model\EmployeeWorkOrderModel
     */
    public function setEmployeeWorkOrderDescription($employeeWorkOrderDescription) {
        $this->employeeWorkOrderDescription = $employeeWorkOrderDescription;
        return $this;
    }

    /**
     * To Return Is Viewable
     * @return bool $isClientViewable
     */
    public function getIsClientViewable() {
        return $this->isClientViewable;
    }

    /**
     * To Set Is Viewable
     * @param bool $isClientViewable Is Viewable
     * @return \Core\Financial\AccountReceivable\EmployeeWorkOrder\Model\EmployeeWorkOrderModel
     */
    public function setIsClientViewable($isClientViewable) {
        $this->isClientViewable = $isClientViewable;
        return $this;
    }

    /**
     * To Return Is All Day   Event
     * @return bool $isAllDayEvent
     */
    public function getIsAllDayEvent() {
        return $this->isAllDayEvent;
    }

    /**
     * To Set Is All Day   Event
     * @param bool $isAllDayEvent Is All Day   Event
     * @return \Core\Financial\AccountReceivable\EmployeeWorkOrder\Model\EmployeeWorkOrderModel
     */
    public function setIsAllDayEvent($isAllDayEvent) {
        $this->isAllDayEvent = $isAllDayEvent;
        return $this;
    }

    /**
     * To Return Is Complete
     * @return bool $isComplete
     */
    public function getIsComplete() {
        return $this->isComplete;
    }

    /**
     * To Set Is Complete
     * @param bool $isComplete Is Complete
     * @return \Core\Financial\AccountReceivable\EmployeeWorkOrder\Model\EmployeeWorkOrderModel
     */
    public function setIsComplete($isComplete) {
        $this->isComplete = $isComplete;
        return $this;
    }

}

?>