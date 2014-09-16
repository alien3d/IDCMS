<?php

namespace Core\System\Management\Calendar\Model;

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
for ($i = 0; $i < count($d); $i ++) {
    // if find the library or package then stop
    if ($d[$i] == 'library' || $d[$i] == 'v2' || $d[$i] == 'v3') {
        break;
    }
    $newPath[] .= $d[$i] . "/";
}
$fakeDocumentRoot = null;
for ($z = 0; $z < count($newPath); $z ++) {
    $fakeDocumentRoot .= $newPath[$z];
}
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot); // start
require_once ($newFakeDocumentRoot . "library/class/classValidation.php");

/**
 * Class Calendar
 * This is calendar model file.This is to ensure strict setting enable for all variable enter to database 
 * 
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\System\Management\Calendar\Model;
 * @subpackage Management 
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class CalendarModel extends ValidationClass {

    /**
     * Primary Key
     * @var int 
     */
    private $calendarId;

    /**
     * Company
     * @var int 
     */
    private $companyId;

    /**
     * Staff
     * @var int 
     */
    private $staffId;

    /**
     * Color
     * @var int 
     */
    private $colorId;

    /**
     * Title
     * @var string 
     */
    private $calendarTitle;

    /**
     * Icon
     * @var string 
     */
    private $calendarIcon;

    /**
     * Calendar Description
     * @var string 
     */
    private $CalendarDescription;

    /**
     * Is All Day   Event
     * @var bool 
     */
    private $calendarIsAllDayEvent;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('calendar');
        $this->setPrimaryKeyName('calendarId');
        $this->setMasterForeignKeyName('calendarId');
        $this->setFilterCharacter('calendarDescription');
        //$this->setFilterCharacter('calendarNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['calendarId'])) {
            $this->setCalendarId($this->strict($_POST ['calendarId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['staffId'])) {
            $this->setStaffId($this->strict($_POST ['staffId'], 'int'));
        }
        if (isset($_POST ['colorId'])) {
            $this->setColorId($this->strict($_POST ['colorId'], 'int'));
        }
        if (isset($_POST ['calendarTitle'])) {
            $this->setCalendarTitle($this->strict($_POST ['calendarTitle'], 'string'));
        }
        if (isset($_POST ['calendarIcon'])) {
            $this->setCalendarIcon($this->strict($_POST ['calendarIcon'], 'string'));
        }
        if (isset($_POST ['CalendarDescription'])) {
            $this->setCalendarDescription($this->strict($_POST ['CalendarDescription'], 'string'));
        }
        if (isset($_POST ['calendarIsAllDayEvent'])) {
            $this->setCalendarIsAllDayEvent($this->strict($_POST ['calendarIsAllDayEvent'], 'bool'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['calendarId'])) {
            $this->setCalendarId($this->strict($_GET ['calendarId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['staffId'])) {
            $this->setStaffId($this->strict($_GET ['staffId'], 'int'));
        }
        if (isset($_GET ['colorId'])) {
            $this->setColorId($this->strict($_GET ['colorId'], 'int'));
        }
        if (isset($_GET ['calendarTitle'])) {
            $this->setCalendarTitle($this->strict($_GET ['calendarTitle'], 'string'));
        }
        if (isset($_GET ['calendarIcon'])) {
            $this->setCalendarIcon($this->strict($_GET ['calendarIcon'], 'string'));
        }
        if (isset($_GET ['CalendarDescription'])) {
            $this->setCalendarDescription($this->strict($_GET ['CalendarDescription'], 'string'));
        }
        if (isset($_GET ['calendarIsAllDayEvent'])) {
            $this->setCalendarIsAllDayEvent($this->strict($_GET ['calendarIsAllDayEvent'], 'bool'));
        }
        if (isset($_GET ['calendarId'])) {
            $this->setTotal(count($_GET ['calendarId']));
            if (is_array($_GET ['calendarId'])) {
                $this->calendarId = array();
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
            if (isset($_GET ['calendarId'])) {
                $this->setCalendarId($this->strict($_GET ['calendarId'] [$i], 'numeric'), $i, 'array');
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
                } if ($_GET ['isUpdate'] [$i] == 'false') {
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
            $primaryKeyAll .= $this->getCalendarId($i, 'array') . ",";
        }
        $this->setPrimaryKeyAll((substr($primaryKeyAll, 0, - 1)));
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
     * Set Primary Key Value 
     * @param int|array $value 
     * @param array[int]int $key List Of Primary Key. 
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array' 
     * @return \Core\System\Management\Calendar\Model\CalendarModel
     */
    public function setCalendarId($value, $key, $type) {
        if ($type == 'single') {
            $this->calendarId = $value;
            return $this;
        } else if ($type == 'array') {
            $this->calendarId[$key] = $value;
            return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:setcalendarId?"));
            exit();
        }
    }

    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getCalendarId($key, $type) {
        if ($type == 'single') {
            return $this->calendarId;
        } else if ($type == 'array') {
            return $this->calendarId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:getcalendarId ?"));
            exit();
        }
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
     * @return \Core\System\Management\Calendar\Model\CalendarModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Staff 
     * @return int $staffId
     */
    public function getStaffId() {
        return $this->staffId;
    }

    /**
     * To Set Staff 
     * @param int $staffId Staff 
     * @return \Core\System\Management\Calendar\Model\CalendarModel
     */
    public function setStaffId($staffId) {
        $this->staffId = $staffId;
        return $this;
    }

    /**
     * To Return Color 
     * @return int $colorId
     */
    public function getColorId() {
        return $this->colorId;
    }

    /**
     * To Set Color 
     * @param int $colorId Color 
     * @return \Core\System\Management\Calendar\Model\CalendarModel
     */
    public function setColorId($colorId) {
        $this->colorId = $colorId;
        return $this;
    }

    /**
     * To Return Title 
     * @return string $calendarTitle
     */
    public function getCalendarTitle() {
        return $this->calendarTitle;
    }

    /**
     * To Set Title 
     * @param string $calendarTitle Title 
     * @return \Core\System\Management\Calendar\Model\CalendarModel
     */
    public function setCalendarTitle($calendarTitle) {
        $this->calendarTitle = $calendarTitle;
        return $this;
    }

    /**
     * To Return Icon 
     * @return string $calendarIcon
     */
    public function getCalendarIcon() {
        return $this->calendarIcon;
    }

    /**
     * To Set Icon 
     * @param string $calendarIcon Icon 
     * @return \Core\System\Management\Calendar\Model\CalendarModel
     */
    public function setCalendarIcon($calendarIcon) {
        $this->calendarIcon = $calendarIcon;
        return $this;
    }

    /**
     * To Return Calendar Description 
     * @return string $CalendarDescription
     */
    public function getCalendarDescription() {
        return $this->CalendarDescription;
    }

    /**
     * To Set Calendar Description 
     * @param string $CalendarDescription Calendar Description 
     * @return \Core\System\Management\Calendar\Model\CalendarModel
     */
    public function setCalendarDescription($CalendarDescription) {
        $this->CalendarDescription = $CalendarDescription;
        return $this;
    }

    /**
     * To Return Is All Day   Event 
     * @return bool $calendarIsAllDayEvent
     */
    public function getCalendarIsAllDayEvent() {
        return $this->calendarIsAllDayEvent;
    }

    /**
     * To Set Is All Day   Event 
     * @param bool $calendarIsAllDayEvent Is All Day   Event 
     * @return \Core\System\Management\Calendar\Model\CalendarModel
     */
    public function setCalendarIsAllDayEvent($calendarIsAllDayEvent) {
        $this->calendarIsAllDayEvent = $calendarIsAllDayEvent;
        return $this;
    }

}

?>