<?php

namespace Core\HumanResource\TimeSheet\TimeSheet\Model;

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
 * Class TimeSheet
 * This is Time Sheet model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\HumanResource\TimeSheet\TimeSheet\Model;
 * @subpackage TimeSheet
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class TimeSheetModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $timeSheetId;

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
     * Document Number
     * @var string
     */
    private $documentNumber;

    /**
     * Reference Number
     * @var string
     */
    private $referenceNumber;

    /**
     * Start Date
     * @var string
     */
    private $timeSheetStartDate;

    /**
     * End Date
     * @var string
     */
    private $timeSheetEndDate;

    /**
     * Start Time
     * @var string
     */
    private $timeSheetStartTime;

    /**
     * End Time
     * @var string
     */
    private $timeSheetEndTime;

    /**
     * Note
     * @var string
     */
    private $timeSheetNote;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('timeSheet');
        $this->setPrimaryKeyName('timeSheetId');
        $this->setMasterForeignKeyName('timeSheetId');
        //$this->setFilterCharacter('timeSheetDescription');
        $this->setFilterCharacter('timeSheetNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['timeSheetId'])) {
            $this->setTimeSheetId($this->strict($_POST ['timeSheetId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['employeeId'])) {
            $this->setEmployeeId($this->strict($_POST ['employeeId'], 'integer'));
        }
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string'));
        }
        if (isset($_POST ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_POST ['referenceNumber'], 'string'));
        }
        if (isset($_POST ['timeSheetStartDate'])) {
            $this->setTimeSheetStartDate($this->strict($_POST ['timeSheetStartDate'], 'date'));
        }
        if (isset($_POST ['timeSheetEndDate'])) {
            $this->setTimeSheetEndDate($this->strict($_POST ['timeSheetEndDate'], 'date'));
        }
        if (isset($_POST ['timeSheetStartTime'])) {
            $this->setTimeSheetStartTime($this->strict($_POST ['timeSheetStartTime'], 'time'));
        }
        if (isset($_POST ['timeSheetEndTime'])) {
            $this->setTimeSheetEndTime($this->strict($_POST ['timeSheetEndTime'], 'time'));
        }
        if (isset($_POST ['timeSheetNote'])) {
            $this->setTimeSheetNote($this->strict($_POST ['timeSheetNote'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['timeSheetId'])) {
            $this->setTimeSheetId($this->strict($_GET ['timeSheetId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['employeeId'])) {
            $this->setEmployeeId($this->strict($_GET ['employeeId'], 'integer'));
        }
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string'));
        }
        if (isset($_GET ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_GET ['referenceNumber'], 'string'));
        }
        if (isset($_GET ['timeSheetStartDate'])) {
            $this->setTimeSheetStartDate($this->strict($_GET ['timeSheetStartDate'], 'date'));
        }
        if (isset($_GET ['timeSheetEndDate'])) {
            $this->setTimeSheetEndDate($this->strict($_GET ['timeSheetEndDate'], 'date'));
        }
        if (isset($_GET ['timeSheetStartTime'])) {
            $this->setTimeSheetStartTime($this->strict($_GET ['timeSheetStartTime'], 'time'));
        }
        if (isset($_GET ['timeSheetEndTime'])) {
            $this->setTimeSheetEndTime($this->strict($_GET ['timeSheetEndTime'], 'time'));
        }
        if (isset($_GET ['timeSheetNote'])) {
            $this->setTimeSheetNote($this->strict($_GET ['timeSheetNote'], 'string'));
        }
        if (isset($_GET ['timeSheetId'])) {
            $this->setTotal(count($_GET ['timeSheetId']));
            if (is_array($_GET ['timeSheetId'])) {
                $this->timeSheetId = array();
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
            if (isset($_GET ['timeSheetId'])) {
                $this->setTimeSheetId($this->strict($_GET ['timeSheetId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getTimeSheetId($i, 'array') . ",";
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
    public function getTimeSheetId($key, $type) {
        if ($type == 'single') {
            return $this->timeSheetId;
        } else {
            if ($type == 'array') {
                return $this->timeSheetId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getTimeSheetId ?")
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
     * @return \Core\HumanResource\TimeSheet\TimeSheet\Model\TimeSheetModel
     */
    public function setTimeSheetId($value, $key, $type) {
        if ($type == 'single') {
            $this->timeSheetId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->timeSheetId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setTimeSheetId?")
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
     * @return \Core\HumanResource\TimeSheet\TimeSheet\Model\TimeSheetModel
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
     * @return \Core\HumanResource\TimeSheet\TimeSheet\Model\TimeSheetModel
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
     * @return \Core\HumanResource\TimeSheet\TimeSheet\Model\TimeSheetModel
     */
    public function setDocumentNumber($documentNumber) {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    /**
     * To Return Reference Number
     * @return string $referenceNumber
     */
    public function getReferenceNumber() {
        return $this->referenceNumber;
    }

    /**
     * To Set Reference Number
     * @param string $referenceNumber Reference Number
     * @return \Core\HumanResource\TimeSheet\TimeSheet\Model\TimeSheetModel
     */
    public function setReferenceNumber($referenceNumber) {
        $this->referenceNumber = $referenceNumber;
        return $this;
    }

    /**
     * To Return Start Date
     * @return string $timeSheetStartDate
     */
    public function getTimeSheetStartDate() {
        return $this->timeSheetStartDate;
    }

    /**
     * To Set Start Date
     * @param string $timeSheetStartDate Start Date
     * @return \Core\HumanResource\TimeSheet\TimeSheet\Model\TimeSheetModel
     */
    public function setTimeSheetStartDate($timeSheetStartDate) {
        $this->timeSheetStartDate = $timeSheetStartDate;
        return $this;
    }

    /**
     * To Return End Date
     * @return string $timeSheetEndDate
     */
    public function getTimeSheetEndDate() {
        return $this->timeSheetEndDate;
    }

    /**
     * To Set End Date
     * @param string $timeSheetEndDate End Date
     * @return \Core\HumanResource\TimeSheet\TimeSheet\Model\TimeSheetModel
     */
    public function setTimeSheetEndDate($timeSheetEndDate) {
        $this->timeSheetEndDate = $timeSheetEndDate;
        return $this;
    }

    /**
     * To Return Start Time
     * @return string $timeSheetStartTime
     */
    public function getTimeSheetStartTime() {
        return $this->timeSheetStartTime;
    }

    /**
     * To Set Start Time
     * @param string $timeSheetStartTime Start Time
     * @return \Core\HumanResource\TimeSheet\TimeSheet\Model\TimeSheetModel
     */
    public function setTimeSheetStartTime($timeSheetStartTime) {
        $this->timeSheetStartTime = $timeSheetStartTime;
        return $this;
    }

    /**
     * To Return End Time
     * @return string $timeSheetEndTime
     */
    public function getTimeSheetEndTime() {
        return $this->timeSheetEndTime;
    }

    /**
     * To Set End Time
     * @param string $timeSheetEndTime End Time
     * @return \Core\HumanResource\TimeSheet\TimeSheet\Model\TimeSheetModel
     */
    public function setTimeSheetEndTime($timeSheetEndTime) {
        $this->timeSheetEndTime = $timeSheetEndTime;
        return $this;
    }

    /**
     * To Return Note
     * @return string $timeSheetNote
     */
    public function getTimeSheetNote() {
        return $this->timeSheetNote;
    }

    /**
     * To Set Note
     * @param string $timeSheetNote Note
     * @return \Core\HumanResource\TimeSheet\TimeSheet\Model\TimeSheetModel
     */
    public function setTimeSheetNote($timeSheetNote) {
        $this->timeSheetNote = $timeSheetNote;
        return $this;
    }

}

?>