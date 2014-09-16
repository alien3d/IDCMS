<?php

namespace Core\System\Management\SystemSetting\Model;

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
 * Class SystemSetting
 * This is systemSetting model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\System\Management\SystemSetting\Model;
 * @subpackage Management
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class SystemSettingModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $systemSettingId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Country
     * @var int
     */
    private $countryId;

    /**
     * Language
     * @var int
     */
    private $languageId;

    /**
     * Language Code
     * @var string
     */
    private $languageCode;

    /**
     * Date Format
     * @var string
     */
    private $systemSettingDateFormat;

    /**
     * Time Format
     * @var string
     */
    private $systemSettingTimeFormat;

    /**
     * Week Start
     * @var int
     */
    private $systemSettingWeekStart;

    /**
     * System Website
     * @var string
     */
    private $systemWebsite;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('systemSetting');
        $this->setPrimaryKeyName('systemSettingId');
        $this->setMasterForeignKeyName('systemSettingId');
        $this->setFilterCharacter('systemSettingDescription');
        //$this->setFilterCharacter('systemSettingNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['systemSettingId'])) {
            $this->setSystemSettingId($this->strict($_POST ['systemSettingId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['countryId'])) {
            $this->setCountryId($this->strict($_POST ['countryId'], 'int'));
        }
        if (isset($_POST ['languageId'])) {
            $this->setLanguageId($this->strict($_POST ['languageId'], 'int'));
        }
        if (isset($_POST ['languageCode'])) {
            $this->setLanguageCode($this->strict($_POST ['languageCode'], 'string'));
        }
        if (isset($_POST ['systemSettingDateFormat'])) {
            $this->setSystemSettingDateFormat($this->strict($_POST ['systemSettingDateFormat'], 'string'));
        }
        if (isset($_POST ['systemSettingTimeFormat'])) {
            $this->setSystemSettingTimeFormat($this->strict($_POST ['systemSettingTimeFormat'], 'string'));
        }
        if (isset($_POST ['systemSettingWeekStart'])) {
            $this->setSystemSettingWeekStart($this->strict($_POST ['systemSettingWeekStart'], 'int'));
        }
        if (isset($_POST ['systemWebsite'])) {
            $this->setSystemWebsite($this->strict($_POST ['systemWebsite'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['systemSettingId'])) {
            $this->setSystemSettingId($this->strict($_GET ['systemSettingId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['countryId'])) {
            $this->setCountryId($this->strict($_GET ['countryId'], 'int'));
        }
        if (isset($_GET ['languageId'])) {
            $this->setLanguageId($this->strict($_GET ['languageId'], 'int'));
        }
        if (isset($_GET ['languageCode'])) {
            $this->setLanguageCode($this->strict($_GET ['languageCode'], 'string'));
        }
        if (isset($_GET ['systemSettingDateFormat'])) {
            $this->setSystemSettingDateFormat($this->strict($_GET ['systemSettingDateFormat'], 'string'));
        }
        if (isset($_GET ['systemSettingTimeFormat'])) {
            $this->setSystemSettingTimeFormat($this->strict($_GET ['systemSettingTimeFormat'], 'string'));
        }
        if (isset($_GET ['systemSettingWeekStart'])) {
            $this->setSystemSettingWeekStart($this->strict($_GET ['systemSettingWeekStart'], 'int'));
        }
        if (isset($_GET ['systemWebsite'])) {
            $this->setSystemWebsite($this->strict($_GET ['systemWebsite'], 'string'));
        }
        if (isset($_GET ['systemSettingId'])) {
            $this->setTotal(count($_GET ['systemSettingId']));
            if (is_array($_GET ['systemSettingId'])) {
                $this->systemSettingId = array();
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
            if (isset($_GET ['systemSettingId'])) {
                $this->setSystemSettingId($this->strict($_GET ['systemSettingId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getSystemSettingId($i, 'array') . ",";
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
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return \Core\System\Management\SystemSetting\Model\SystemSettingModel
     */
    public function setSystemSettingId($value, $key, $type) {
        if ($type == 'single') {
            $this->systemSettingId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->systemSettingId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setsystemSettingId?")
                );
                exit();
            }
        }
    }

    /**
     * Return Primary Key Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getSystemSettingId($key, $type) {
        if ($type == 'single') {
            return $this->systemSettingId;
        } else {
            if ($type == 'array') {
                return $this->systemSettingId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getsystemSettingId ?")
                );
                exit();
            }
        }
    }

    /**
     * To Return  Company
     * @return int $companyId
     */
    public function getCompanyId() {
        return $this->companyId;
    }

    /**
     * To Set Company
     * @param int $companyId Company
     * @return \Core\System\Management\SystemSetting\Model\SystemSettingModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return  Country
     * @return int $countryId
     */
    public function getCountryId() {
        return $this->countryId;
    }

    /**
     * To Set Country
     * @param int $countryId Country
     * @return \Core\System\Management\SystemSetting\Model\SystemSettingModel
     */
    public function setCountryId($countryId) {
        $this->countryId = $countryId;
        return $this;
    }

    /**
     * To Return  Language
     * @return int $languageId
     */
    public function getLanguageId() {
        return $this->languageId;
    }

    /**
     * To Set Language
     * @param int $languageId Language
     * @return \Core\System\Management\SystemSetting\Model\SystemSettingModel
     */
    public function setLanguageId($languageId) {
        $this->languageId = $languageId;
        return $this;
    }

    /**
     * To Return  languageCode
     * @return string $languageCode
     */
    public function getLanguageCode() {
        return $this->languageCode;
    }

    /**
     * To Set languageCode
     * @param string $languageCode Language Code
     * @return \Core\System\Management\SystemSetting\Model\SystemSettingModel
     */
    public function setLanguageCode($languageCode) {
        $this->languageCode = $languageCode;
        return $this;
    }

    /**
     * To Return  DateFormat
     * @return string $systemSettingDateFormat
     */
    public function getSystemSettingDateFormat() {
        return $this->systemSettingDateFormat;
    }

    /**
     * To Set DateFormat
     * @param string $systemSettingDateFormat Date Format
     * @return \Core\System\Management\SystemSetting\Model\SystemSettingModel
     */
    public function setSystemSettingDateFormat($systemSettingDateFormat) {
        $this->systemSettingDateFormat = $systemSettingDateFormat;
        return $this;
    }

    /**
     * To Return  TimeFormat
     * @return string $systemSettingTimeFormat
     */
    public function getSystemSettingTimeFormat() {
        return $this->systemSettingTimeFormat;
    }

    /**
     * To Set TimeFormat
     * @param string $systemSettingTimeFormat Time Format
     * @return \Core\System\Management\SystemSetting\Model\SystemSettingModel
     */
    public function setSystemSettingTimeFormat($systemSettingTimeFormat) {
        $this->systemSettingTimeFormat = $systemSettingTimeFormat;
        return $this;
    }

    /**
     * To Return  WeekStart
     * @return int $systemSettingWeekStart
     */
    public function getSystemSettingWeekStart() {
        return $this->systemSettingWeekStart;
    }

    /**
     * To Set WeekStart
     * @param int $systemSettingWeekStart Week Start
     * @return \Core\System\Management\SystemSetting\Model\SystemSettingModel
     */
    public function setSystemSettingWeekStart($systemSettingWeekStart) {
        $this->systemSettingWeekStart = $systemSettingWeekStart;
        return $this;
    }

    /**
     * To Return  systemWebsite
     * @return string $systemWebsite
     */
    public function getSystemWebsite() {
        return $this->systemWebsite;
    }

    /**
     * To Set systemWebsite
     * @param string $systemWebsite System Website
     * @return \Core\System\Management\SystemSetting\Model\SystemSettingModel
     */
    public function setSystemWebsite($systemWebsite) {
        $this->systemWebsite = $systemWebsite;
        return $this;
    }

}

?>