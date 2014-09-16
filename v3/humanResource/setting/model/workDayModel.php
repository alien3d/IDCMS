<?php

namespace Core\HumanResource\Setting\WorkDay\Model;

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
 * Class WorkDay
 * This is workDay model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\HumanResource\Setting\WorkDay\Model;
 * @subpackage Setting
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class WorkDayModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $workDayId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Day
     * @var int
     */
    private $dayId;

    /**
     * Shift
     * @var int
     */
    private $shiftId;

    /**
     * Type
     * @var int
     */
    private $workDayTypeId;

    /**
     * Start
     * @var string time start
     */
    private $workDayStart;

    /**
     * End
     * @var string time end
     */
    private $workDayEnd;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('workDay');
        $this->setPrimaryKeyName('workDayId');
        $this->setMasterForeignKeyName('workDayId');
        $this->setFilterCharacter('workDayDescription');
        //$this->setFilterCharacter('workDayNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['workDayId'])) {
            $this->setWorkDayId($this->strict($_POST ['workDayId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['dayId'])) {
            $this->setDayId($this->strict($_POST ['dayId'], 'int'));
        }
        if (isset($_POST ['shiftId'])) {
            $this->setShiftId($this->strict($_POST ['shiftId'], 'int'));
        }
        if (isset($_POST ['workDayTypeId'])) {
            $this->setWorkDayTypeId($this->strict($_POST ['workDayTypeId'], 'int'));
        }
        if (isset($_POST ['workDayStart'])) {
            $this->setWorkDayStart($this->strict($_POST ['workDayStart'], 'time'));
        }
        if (isset($_POST ['workDayEnd'])) {
            $this->setWorkDayEnd($this->strict($_POST ['workDayEnd'], 'time'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['workDayId'])) {
            $this->setWorkDayId($this->strict($_GET ['workDayId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['dayId'])) {
            $this->setDayId($this->strict($_GET ['dayId'], 'int'));
        }
        if (isset($_GET ['shiftId'])) {
            $this->setShiftId($this->strict($_GET ['shiftId'], 'int'));
        }
        if (isset($_GET ['workDayTypeId'])) {
            $this->setWorkDayTypeId($this->strict($_GET ['workDayTypeId'], 'int'));
        }
        if (isset($_GET ['workDayStart'])) {
            $this->setWorkDayStart($this->strict($_GET ['workDayStart'], 'time'));
        }
        if (isset($_GET ['workDayEnd'])) {
            $this->setWorkDayEnd($this->strict($_GET ['workDayEnd'], 'time'));
        }
        if (isset($_GET ['workDayId'])) {
            $this->setTotal(count($_GET ['workDayId']));
            if (is_array($_GET ['workDayId'])) {
                $this->workDayId = array();
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
            if (isset($_GET ['workDayId'])) {
                $this->setWorkDayId($this->strict($_GET ['workDayId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getWorkDayId($i, 'array') . ",";
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
    public function getWorkDayId($key, $type) {
        if ($type == 'single') {
            return $this->workDayId;
        } else if ($type == 'array') {
            return $this->workDayId [$key];
        } else {
            echo json_encode(
                    array("success" => false, "message" => "Cannot Identify Type String Or Array:getworkDayId ?")
            );
            exit();
        }
    }

    /**
     * Set Primary Key Value
     * @param int|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return \Core\HumanResource\Setting\WorkDay\Model\WorkDayModel
     */
    public function setWorkDayId($value, $key, $type) {
        if ($type == 'single') {
            $this->workDayId = $value;
            return $this;
        } else if ($type == 'array') {
            $this->workDayId[$key] = $value;
            return $this;
        } else {
            echo json_encode(
                    array("success" => false, "message" => "Cannot Identify Type String Or Array:setworkDayId?")
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
     * @return \Core\HumanResource\Setting\WorkDay\Model\WorkDayModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Day
     * @return int $dayId
     */
    public function getDayId() {
        return $this->dayId;
    }

    /**
     * To Set Day
     * @param int $dayId Day
     * @return \Core\HumanResource\Setting\WorkDay\Model\WorkDayModel
     */
    public function setDayId($dayId) {
        $this->dayId = $dayId;
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
     * @return \Core\HumanResource\Setting\WorkDay\Model\WorkDayModel
     */
    public function setShiftId($shiftId) {
        $this->shiftId = $shiftId;
        return $this;
    }

    /**
     * To Return Type
     * @return int $workDayTypeId
     */
    public function getWorkDayTypeId() {
        return $this->workDayTypeId;
    }

    /**
     * To Set Type
     * @param int $workDayTypeId Type
     * @return \Core\HumanResource\Setting\WorkDay\Model\WorkDayModel
     */
    public function setWorkDayTypeId($workDayTypeId) {
        $this->workDayTypeId = $workDayTypeId;
        return $this;
    }

    /**
     * To Return Start
     * @return string $workDayStart
     */
    public function getWorkDayStart() {
        return $this->workDayStart;
    }

    /**
     * To Set Start
     * @param string $workDayStart Start
     * @return \Core\HumanResource\Setting\WorkDay\Model\WorkDayModel
     */
    public function setWorkDayStart($workDayStart) {
        $this->workDayStart = $workDayStart;
        return $this;
    }

    /**
     * To Return End
     * @return string $workDayEnd
     */
    public function getWorkDayEnd() {
        return $this->workDayEnd;
    }

    /**
     * To Set End
     * @param string $workDayEnd End
     * @return \Core\HumanResource\Setting\WorkDay\Model\WorkDayModel
     */
    public function setWorkDayEnd($workDayEnd) {
        $this->workDayEnd = $workDayEnd;
        return $this;
    }

}

?>