<?php

namespace Core\System\Common\Country\Model;

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
 * Class Country
 * This is country model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\System\Common\Country\Model;
 * @subpackage Common
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class CountryModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $countryId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Code
     * @var string
     */
    private $countryCode;

    /**
     * Currency Code
     * @var string
     */
    private $countryCurrencyCode;

    /**
     * Currency Description
     * @var string
     */
    private $countryCurrencyCodeDescription;

    /**
     * Locale One
     * @var string
     */
    private $localeOne;

    /**
     * Locale Two
     * @var string
     */
    private $localeTwo;

    /**
     * Locale Three
     * @var string
     */
    private $localeThree;

    /**
     * Locale Four
     * @var string
     */
    private $localeFour;

    /**
     * Locale Five
     * @var string
     */
    private $localeFive;

    /**
     * Description
     * @var string
     */
    private $countryDescription;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('country');
        $this->setPrimaryKeyName('countryId');
        $this->setMasterForeignKeyName('countryId');
        $this->setFilterCharacter('countryDescription');
        //$this->setFilterCharacter('countryNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['countryId'])) {
            $this->setCountryId($this->strict($_POST ['countryId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['countryCode'])) {
            $this->setCountryCode($this->strict($_POST ['countryCode'], 'string'));
        }
        if (isset($_POST ['countryCurrencyCode'])) {
            $this->setCountryCurrencyCode($this->strict($_POST ['countryCurrencyCode'], 'string'));
        }
        if (isset($_POST ['countryCurrencyCodeDescription'])) {
            $this->setCountryCurrencyCodeDescription(
                    $this->strict($_POST ['countryCurrencyCodeDescription'], 'string')
            );
        }
        if (isset($_POST ['localeOne'])) {
            $this->setLocaleOne($this->strict($_POST ['localeOne'], 'string'));
        }
        if (isset($_POST ['localeTwo'])) {
            $this->setLocaleTwo($this->strict($_POST ['localeTwo'], 'string'));
        }
        if (isset($_POST ['localeThree'])) {
            $this->setLocaleThree($this->strict($_POST ['localeThree'], 'string'));
        }
        if (isset($_POST ['localeFour'])) {
            $this->setLocaleFour($this->strict($_POST ['localeFour'], 'string'));
        }
        if (isset($_POST ['localeFive'])) {
            $this->setLocaleFive($this->strict($_POST ['localeFive'], 'string'));
        }
        if (isset($_POST ['countryDescription'])) {
            $this->setCountryDescription($this->strict($_POST ['countryDescription'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['countryId'])) {
            $this->setCountryId($this->strict($_GET ['countryId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['countryCode'])) {
            $this->setCountryCode($this->strict($_GET ['countryCode'], 'string'));
        }
        if (isset($_GET ['countryCurrencyCode'])) {
            $this->setCountryCurrencyCode($this->strict($_GET ['countryCurrencyCode'], 'string'));
        }
        if (isset($_GET ['countryCurrencyCodeDescription'])) {
            $this->setCountryCurrencyCodeDescription($this->strict($_GET ['countryCurrencyCodeDescription'], 'string'));
        }
        if (isset($_GET ['localeOne'])) {
            $this->setLocaleOne($this->strict($_GET ['localeOne'], 'string'));
        }
        if (isset($_GET ['localeTwo'])) {
            $this->setLocaleTwo($this->strict($_GET ['localeTwo'], 'string'));
        }
        if (isset($_GET ['localeThree'])) {
            $this->setLocaleThree($this->strict($_GET ['localeThree'], 'string'));
        }
        if (isset($_GET ['localeFour'])) {
            $this->setLocaleFour($this->strict($_GET ['localeFour'], 'string'));
        }
        if (isset($_GET ['localeFive'])) {
            $this->setLocaleFive($this->strict($_GET ['localeFive'], 'string'));
        }
        if (isset($_GET ['countryDescription'])) {
            $this->setCountryDescription($this->strict($_GET ['countryDescription'], 'string'));
        }
        if (isset($_GET ['countryId'])) {
            $this->setTotal(count($_GET ['countryId']));
            if (is_array($_GET ['countryId'])) {
                $this->countryId = array();
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
            if (isset($_GET ['countryId'])) {
                $this->setCountryId($this->strict($_GET ['countryId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getCountryId($i, 'array') . ",";
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
     * @return \Core\System\Common\Country\Model\CountryModel
     */
    public function setCountryId($value, $key, $type) {
        if ($type == 'single') {
            $this->countryId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->countryId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setCountryId?")
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
    public function getCountryId($key, $type) {
        if ($type == 'single') {
            return $this->countryId;
        } else {
            if ($type == 'array') {
                return $this->countryId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getCountryId ?")
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
     * @return \Core\System\Common\Country\Model\CountryModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return  Code
     * @return string $countryCode
     */
    public function getCountryCode() {
        return $this->countryCode;
    }

    /**
     * To Set Code
     * @param string $countryCode Code
     * @return \Core\System\Common\Country\Model\CountryModel
     */
    public function setCountryCode($countryCode) {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * To Return Currency Code
     * @return string $countryCurrencyCode
     */
    public function getCountryCurrencyCode() {
        return $this->countryCurrencyCode;
    }

    /**
     * To Set Currency Code
     * @param string $countryCurrencyCode Currency Code
     * @return \Core\System\Common\Country\Model\CountryModel
     */
    public function setCountryCurrencyCode($countryCurrencyCode) {
        $this->countryCurrencyCode = $countryCurrencyCode;
        return $this;
    }

    /**
     * To Return Currency Code Description
     * @return string $countryCurrencyCodeDescription
     */
    public function getCountryCurrencyCodeDescription() {
        return $this->countryCurrencyCodeDescription;
    }

    /**
     * To Set Currency Code Description
     * @param string $countryCurrencyCodeDescription Currency Description
     * @return \Core\System\Common\Country\Model\CountryModel
     */
    public function setCountryCurrencyCodeDescription($countryCurrencyCodeDescription) {
        $this->countryCurrencyCodeDescription = $countryCurrencyCodeDescription;
        return $this;
    }

    /**
     * To Return Locale One
     * @return string $localeOne
     */
    public function getLocaleOne() {
        return $this->localeOne;
    }

    /**
     * To Set Locale One
     * @param string $localeOne Locale One
     * @return \Core\System\Common\Country\Model\CountryModel
     */
    public function setLocaleOne($localeOne) {
        $this->localeOne = $localeOne;
        return $this;
    }

    /**
     * To Return Locale Two
     * @return string $localeTwo
     */
    public function getLocaleTwo() {
        return $this->localeTwo;
    }

    /**
     * To Set Locale Two
     * @param string $localeTwo Locale Two
     * @return \Core\System\Common\Country\Model\CountryModel
     */
    public function setLocaleTwo($localeTwo) {
        $this->localeTwo = $localeTwo;
        return $this;
    }

    /**
     * To Return Locale Three
     * @return string $localeThree
     */
    public function getLocaleThree() {
        return $this->localeThree;
    }

    /**
     * To Set Locale Three
     * @param string $localeThree Locale Three
     * @return \Core\System\Common\Country\Model\CountryModel
     */
    public function setLocaleThree($localeThree) {
        $this->localeThree = $localeThree;
        return $this;
    }

    /**
     * To Return Locale Four
     * @return string $localeFour
     */
    public function getLocaleFour() {
        return $this->localeFour;
    }

    /**
     * To Set Locale Four
     * @param string $localeFour Locale Four
     * @return \Core\System\Common\Country\Model\CountryModel
     */
    public function setLocaleFour($localeFour) {
        $this->localeFour = $localeFour;
        return $this;
    }

    /**
     * To Return Locale Five
     * @return string $localeFive
     */
    public function getLocaleFive() {
        return $this->localeFive;
    }

    /**
     * To Set Locale Five
     * @param string $localeFive Locale Five
     * @return \Core\System\Common\Country\Model\CountryModel
     */
    public function setLocaleFive($localeFive) {
        $this->localeFive = $localeFive;
        return $this;
    }

    /**
     * To Return Description
     * @return string $countryDescription
     */
    public function getCountryDescription() {
        return $this->countryDescription;
    }

    /**
     * To Set Description
     * @param string $countryDescription Description
     * @return \Core\System\Common\Country\Model\CountryModel
     */
    public function setCountryDescription($countryDescription) {
        $this->countryDescription = $countryDescription;
        return $this;
    }

}

?>