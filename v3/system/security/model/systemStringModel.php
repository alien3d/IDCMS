<?php

namespace Core\System\Security\SystemSetting\Model;

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
 * Class SystemString
 * This is System String model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\System\Security\SystemSetting\Model;
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class SystemSettingModel extends ValidationClass {

    /**
     * Id
     * @var int
     */
    private $systemSettingId;

    /**
     * companyId
     * @var int
     */
    private $companyId;

    /**
     * countryId
     * @var int
     */
    private $countryId;

    /**
     * systemSettingDateFormat
     * @var string
     */
    private $systemSettingDateFormat;

    /**
     * systemSettingTimeFormat
     * @var string
     */
    private $systemSettingTimeFormat;

    /**
     * systemSettingWeekStart
     * @var int
     */
    private $systemSettingWeekStart;

    /**
     * systemSettingNumberFormat
     * @var string
     */
    private $systemSettingNumberFormat;

    /**
     * systemSettingDecimalSeparator
     * @var string
     */
    private $systemSettingDecimalSeparator;

    /**
     * systemSettingDecimalThousandsSeparator
     * @var string
     */
    private $systemSettingDecimalThousandsSeparator;

    /**
     * systemSettingCurrencyFormat
     * @var string
     */
    private $systemSettingCurrencyFormat;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('systemString');
        $this->setPrimaryKeyName('systemSettingId');
        $this->setMasterForeignKeyName('systemSettingId');
        $this->setFilterCharacter('systemStringDescription');
        //$this->setFilterCharacter('systemStringNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['systemSettingId'])) {
            $this->setSystemSettingId($this->strict($_POST ['systemSettingId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['countryId'])) {
            $this->setCountryId($this->strict($_POST ['countryId'], 'integer'));
        }
        if (isset($_POST ['systemSettingDateFormat'])) {
            $this->setSystemSettingDateFormat($this->strict($_POST ['systemSettingDateFormat'], 'string'));
        }
        if (isset($_POST ['systemSettingTimeFormat'])) {
            $this->setSystemSettingTimeFormat($this->strict($_POST ['systemSettingTimeFormat'], 'string'));
        }
        if (isset($_POST ['systemSettingWeekStart'])) {
            $this->setSystemSettingWeekStart($this->strict($_POST ['systemSettingWeekStart'], 'integer'));
        }
        if (isset($_POST ['systemSettingNumberFormat'])) {
            $this->setSystemSettingNumberFormat($this->strict($_POST ['systemSettingNumberFormat'], 'string'));
        }
        if (isset($_POST ['systemSettingDecimalSeparator'])) {
            $this->setSystemSettingDecimalSeparator($this->strict($_POST ['systemSettingDecimalSeparator'], 'string'));
        }
        if (isset($_POST ['systemSettingDecimalThousandsSeparator'])) {
            $this->setSystemSettingDecimalThousandsSeparator(
                    $this->strict($_POST ['systemSettingDecimalThousandsSeparator'], 'string')
            );
        }
        if (isset($_POST ['systemSettingCurrencyFormat'])) {
            $this->setSystemSettingCurrencyFormat($this->strict($_POST ['systemSettingCurrencyFormat'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['systemSettingId'])) {
            $this->setSystemSettingId($this->strict($_GET ['systemSettingId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['countryId'])) {
            $this->setCountryId($this->strict($_GET ['countryId'], 'integer'));
        }
        if (isset($_GET ['systemSettingDateFormat'])) {
            $this->setSystemSettingDateFormat($this->strict($_GET ['systemSettingDateFormat'], 'string'));
        }
        if (isset($_GET ['systemSettingTimeFormat'])) {
            $this->setSystemSettingTimeFormat($this->strict($_GET ['systemSettingTimeFormat'], 'string'));
        }
        if (isset($_GET ['systemSettingWeekStart'])) {
            $this->setSystemSettingWeekStart($this->strict($_GET ['systemSettingWeekStart'], 'integer'));
        }
        if (isset($_GET ['systemSettingNumberFormat'])) {
            $this->setSystemSettingNumberFormat($this->strict($_GET ['systemSettingNumberFormat'], 'string'));
        }
        if (isset($_GET ['systemSettingDecimalSeparator'])) {
            $this->setSystemSettingDecimalSeparator($this->strict($_GET ['systemSettingDecimalSeparator'], 'string'));
        }
        if (isset($_GET ['systemSettingDecimalThousandsSeparator'])) {
            $this->setSystemSettingDecimalThousandsSeparator(
                    $this->strict($_GET ['systemSettingDecimalThousandsSeparator'], 'string')
            );
        }
        if (isset($_GET ['systemSettingCurrencyFormat'])) {
            $this->setSystemSettingCurrencyFormat($this->strict($_GET ['systemSettingCurrencyFormat'], 'string'));
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
     * @return \Core\System\Security\SystemSetting\Model\SystemSettingModel
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
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setSystemStringId?")
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
    public function getSystemSettingId($key, $type) {
        if ($type == 'single') {
            return $this->systemSettingId;
        } else {
            if ($type == 'array') {
                return $this->systemSettingId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getSystemStringId ?")
                );
                exit();
            }
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
     * @return \Core\System\Security\SystemSetting\Model\SystemSettingModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Country
     * @return int $countryId
     */
    public function getCountryId() {
        return $this->countryId;
    }

    /**
     * To Set Country
     * @param int $countryId Country
     * @return \Core\System\Security\SystemSetting\Model\SystemSettingModel
     */
    public function setCountryId($countryId) {
        $this->countryId = $countryId;
        return $this;
    }

    /**
     * To Return Date Format
     * @return string $systemSettingDateFormat
     */
    public function getSystemSettingDateFormat() {
        return $this->systemSettingDateFormat;
    }

    /**
     * To Set Date Format
     * @param string $systemSettingDateFormat System Setting Date   Format
     * @return \Core\System\Security\SystemSetting\Model\SystemSettingModel
     */
    public function setSystemSettingDateFormat($systemSettingDateFormat) {
        $this->systemSettingDateFormat = $systemSettingDateFormat;
        return $this;
    }

    /**
     * To Return Time Format
     * @return string $systemSettingTimeFormat
     */
    public function getSystemSettingTimeFormat() {
        return $this->systemSettingTimeFormat;
    }

    /**
     * To Set Time Format
     * @param string $systemSettingTimeFormat System Setting Time   Format
     * @return \Core\System\Security\SystemSetting\Model\SystemSettingModel
     */
    public function setSystemSettingTimeFormat($systemSettingTimeFormat) {
        $this->systemSettingTimeFormat = $systemSettingTimeFormat;
        return $this;
    }

    /**
     * To Return Week Start
     * @return int $systemSettingWeekStart
     */
    public function getSystemSettingWeekStart() {
        return $this->systemSettingWeekStart;
    }

    /**
     * To Set Week Start
     * @param int $systemSettingWeekStart System Setting Week   Start
     * @return \Core\System\Security\SystemSetting\Model\SystemSettingModel
     */
    public function setSystemSettingWeekStart($systemSettingWeekStart) {
        $this->systemSettingWeekStart = $systemSettingWeekStart;
        return $this;
    }

    /**
     * To Return Number Format
     * @return string $systemSettingNumberFormat
     */
    public function getSystemSettingNumberFormat() {
        return $this->systemSettingNumberFormat;
    }

    /**
     * To Set Number Format
     * @param string $systemSettingNumberFormat System Setting Number   Format
     * @return \Core\System\Security\SystemSetting\Model\SystemSettingModel
     */
    public function setSystemSettingNumberFormat($systemSettingNumberFormat) {
        $this->systemSettingNumberFormat = $systemSettingNumberFormat;
        return $this;
    }

    /**
     * To Return Decimal Separator
     * @return string $systemSettingDecimalSeparator
     */
    public function getSystemSettingDecimalSeparator() {
        return $this->systemSettingDecimalSeparator;
    }

    /**
     * To Set Decimal Separator
     * @param string $systemSettingDecimalSeparator System Setting Decimal   Separator
     * @return \Core\System\Security\SystemSetting\Model\SystemSettingModel
     */
    public function setSystemSettingDecimalSeparator($systemSettingDecimalSeparator) {
        $this->systemSettingDecimalSeparator = $systemSettingDecimalSeparator;
        return $this;
    }

    /**
     * To Return Thousands Separator
     * @return string $systemSettingDecimalThousandsSeparator
     */
    public function getSystemSettingDecimalThousandsSeparator() {
        return $this->systemSettingDecimalThousandsSeparator;
    }

    /**
     * To Set Thousands Separator
     * @param string $systemDecimalThousandsSeparator System Decimal Thousands   Separator
     * @return \Core\System\Security\SystemSetting\Model\SystemSettingModel
     */
    public function setSystemSettingDecimalThousandsSeparator($systemDecimalThousandsSeparator) {
        $this->systemSettingDecimalThousandsSeparator = $systemDecimalThousandsSeparator;
        return $this;
    }

    /**
     * To Return Currency Format
     * @return string $systemSettingCurrencyFormat
     */
    public function getSystemSettingCurrencyFormat() {
        return $this->systemSettingCurrencyFormat;
    }

    /**
     * To Set Currency Format
     * @param string $systemSettingCurrencyFormat System Setting Currency   Format
     * @return \Core\System\Security\SystemSetting\Model\SystemSettingModel
     */
    public function setSystemSettingCurrencyFormat($systemSettingCurrencyFormat) {
        $this->systemSettingCurrencyFormat = $systemSettingCurrencyFormat;
        return $this;
    }

}

?>