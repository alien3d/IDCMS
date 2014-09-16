<?php

namespace Core\System\Management\Branch\Model;

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
 * Class Branch
 * This is branch model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\System\Management\Branch\Model;
 * @subpackage Management
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class BranchModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $branchId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * City
     * @var int
     */
    private $cityId;

    /**
     * Division->location
     * @var int
     */
    private $divisionId;

    /**
     * District
     * @var int
     */
    private $districtId;

    /**
     * State
     * @var int
     */
    private $stateId;

    /**
     * Country
     * @var int
     */
    private $countryId;

    /**
     * Code
     * @var string
     */
    private $branchCode;

    /**
     * Logo
     * @var string
     */
    private $branchLogo;

    /**
     * Registration Number
     * @var string
     */
    private $branchRegistrationNumber;

    /**
     * Registration Date
     * @var string
     */
    private $branchRegistrationDate;

    /**
     * Name
     * @var string
     */
    private $branchName;

    /**
     * Contact Person
     * @var string
     */
    private $branchContactPerson;

    /**
     * Email
     * @var string
     */
    private $branchEmail;

    /**
     * Fax
     * @var string
     */
    private $branchFaxNumber;

    /**
     * Office Phone
     * @var string
     */
    private $branchOfficePhone;

    /**
     * Office Secondary
     * @var string
     */
    private $branchOfficePhoneSecondary;

    /**
     * Mobile Phone
     * @var string
     */
    private $branchMobilePhone;

    /**
     * Post Code
     * @var string
     */
    private $branchPostCode;

    /**
     * Address
     * @var string
     */
    private $branchAddress;

    /**
     * Maps
     * @var string
     */
    private $branchMaps;

    /**
     * Description
     * @var string
     */
    private $branchDescription;

    /**
     * Web Page
     * @var string
     */
    private $branchWebPage;

    /**
     * Facebook
     * @var string
     */
    private $branchFacebook;

    /**
     * Twitter
     * @var string
     */
    private $branchTwitter;

    /**
     * Linked In
     * @var string
     */
    private $branchLinkedIn;

    /**
     * Is Franchisee
     * @var bool
     */
    private $isFranchisee;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('branch');
        $this->setPrimaryKeyName('branchId');
        $this->setMasterForeignKeyName('branchId');
        $this->setFilterCharacter('branchName');
        //$this->setFilterCharacter('branchNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['branchId'])) {
            $this->setBranchId($this->strict($_POST ['branchId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['cityId'])) {
            $this->setCityId($this->strict($_POST ['cityId'], 'int'));
        }
        if (isset($_POST ['stateId'])) {
            $this->setStateId($this->strict($_POST ['stateId'], 'int'));
        }
        if (isset($_POST ['countryId'])) {
            $this->setCountryId($this->strict($_POST ['countryId'], 'int'));
        }
        if (isset($_POST ['branchCode'])) {
            $this->setBranchCode($this->strict($_POST ['branchCode'], 'string'));
        }
        if (isset($_POST ['branchLogo'])) {
            $this->setBranchLogo($this->strict($_POST ['branchLogo'], 'string'));
        }
        if (isset($_POST ['branchRegistrationNumber'])) {
            $this->setBranchRegistrationNumber($this->strict($_POST ['branchRegistrationNumber'], 'string'));
        }
        if (isset($_POST ['branchRegistrationDate'])) {
            $this->setBranchRegistrationDate($this->strict($_POST ['branchRegistrationDate'], 'date'));
        }
        if (isset($_POST ['branchName'])) {
            $this->setBranchName($this->strict($_POST ['branchName'], 'string'));
        }
        if (isset($_POST ['branchContactPerson'])) {
            $this->setBranchContactPerson($this->strict($_POST ['branchContactPerson'], 'string'));
        }
        if (isset($_POST ['branchEmail'])) {
            $this->setBranchEmail($this->strict($_POST ['branchEmail'], 'string'));
        }
        if (isset($_POST ['branchFaxNumber'])) {
            $this->setBranchFaxNumber($this->strict($_POST ['branchFaxNumber'], 'string'));
        }
        if (isset($_POST ['branchOfficePhone'])) {
            $this->setBranchOfficePhone($this->strict($_POST ['branchOfficePhone'], 'string'));
        }
        if (isset($_POST ['branchOfficePhoneSecondary'])) {
            $this->setBranchOfficePhoneSecondary($this->strict($_POST ['branchOfficePhoneSecondary'], 'string'));
        }
        if (isset($_POST ['branchMobilePhone'])) {
            $this->setBranchMobilePhone($this->strict($_POST ['branchMobilePhone'], 'string'));
        }
        if (isset($_POST ['branchPostCode'])) {
            $this->setBranchPostCode($this->strict($_POST ['branchPostCode'], 'string'));
        }
        if (isset($_POST ['branchAddress'])) {
            $this->setBranchAddress($this->strict($_POST ['branchAddress'], 'string'));
        }
        if (isset($_POST ['branchMaps'])) {
            $this->setBranchMaps($this->strict($_POST ['branchMaps'], 'string'));
        }
        if (isset($_POST ['branchDescription'])) {
            $this->setbranchDescription($this->strict($_POST ['branchDescription'], 'string'));
        }
        if (isset($_POST ['branchWebPage'])) {
            $this->setBranchWebPage($this->strict($_POST ['branchWebPage'], 'string'));
        }
        if (isset($_POST ['branchFacebook'])) {
            $this->setBranchFacebook($this->strict($_POST ['branchFacebook'], 'string'));
        }
        if (isset($_POST ['branchTwitter'])) {
            $this->setBranchTwitter($this->strict($_POST ['branchTwitter'], 'string'));
        }
        if (isset($_POST ['branchLinkedIn'])) {
            $this->setBranchLinkedIn($this->strict($_POST ['branchLinkedIn'], 'string'));
        }
        if (isset($_POST ['isFranchisee'])) {
            $this->setIsFranchisee($this->strict($_POST ['isFranchisee'], 'bool'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['branchId'])) {
            $this->setBranchId($this->strict($_GET ['branchId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['cityId'])) {
            $this->setCityId($this->strict($_GET ['cityId'], 'int'));
        }
        if (isset($_GET ['stateId'])) {
            $this->setStateId($this->strict($_GET ['stateId'], 'int'));
        }
        if (isset($_GET ['countryId'])) {
            $this->setCountryId($this->strict($_GET ['countryId'], 'int'));
        }
        if (isset($_GET ['branchCode'])) {
            $this->setBranchCode($this->strict($_GET ['branchCode'], 'string'));
        }
        if (isset($_GET ['branchLogo'])) {
            $this->setBranchLogo($this->strict($_GET ['branchLogo'], 'string'));
        }
        if (isset($_GET ['branchRegistrationNumber'])) {
            $this->setBranchRegistrationNumber($this->strict($_GET ['branchRegistrationNumber'], 'string'));
        }
        if (isset($_GET ['branchRegistrationDate'])) {
            $this->setBranchRegistrationDate($this->strict($_GET ['branchRegistrationDate'], 'date'));
        }
        if (isset($_GET ['branchName'])) {
            $this->setBranchName($this->strict($_GET ['branchName'], 'string'));
        }
        if (isset($_GET ['branchContactPerson'])) {
            $this->setBranchContactPerson($this->strict($_GET ['branchContactPerson'], 'string'));
        }
        if (isset($_GET ['branchEmail'])) {
            $this->setBranchEmail($this->strict($_GET ['branchEmail'], 'string'));
        }
        if (isset($_GET ['branchFaxNumber'])) {
            $this->setBranchFaxNumber($this->strict($_GET ['branchFaxNumber'], 'string'));
        }
        if (isset($_GET ['branchOfficePhone'])) {
            $this->setBranchOfficePhone($this->strict($_GET ['branchOfficePhone'], 'string'));
        }
        if (isset($_GET ['branchOfficePhoneSecondary'])) {
            $this->setBranchOfficePhoneSecondary($this->strict($_GET ['branchOfficePhoneSecondary'], 'string'));
        }
        if (isset($_GET ['branchMobilePhone'])) {
            $this->setBranchMobilePhone($this->strict($_GET ['branchMobilePhone'], 'string'));
        }
        if (isset($_GET ['branchPostCode'])) {
            $this->setBranchPostCode($this->strict($_GET ['branchPostCode'], 'string'));
        }
        if (isset($_GET ['branchAddress'])) {
            $this->setBranchAddress($this->strict($_GET ['branchAddress'], 'string'));
        }
        if (isset($_GET ['branchMaps'])) {
            $this->setBranchMaps($this->strict($_GET ['branchMaps'], 'string'));
        }
        if (isset($_GET ['branchDescription'])) {
            $this->setbranchDescription($this->strict($_GET ['branchDescription'], 'string'));
        }
        if (isset($_GET ['branchWebPage'])) {
            $this->setBranchWebPage($this->strict($_GET ['branchWebPage'], 'string'));
        }
        if (isset($_GET ['branchFacebook'])) {
            $this->setBranchFacebook($this->strict($_GET ['branchFacebook'], 'string'));
        }
        if (isset($_GET ['branchTwitter'])) {
            $this->setBranchTwitter($this->strict($_GET ['branchTwitter'], 'string'));
        }
        if (isset($_GET ['branchLinkedIn'])) {
            $this->setBranchLinkedIn($this->strict($_GET ['branchLinkedIn'], 'string'));
        }
        if (isset($_GET ['isFranchisee'])) {
            $this->setIsFranchisee($this->strict($_GET ['isFranchisee'], 'bool'));
        }
        if (isset($_GET ['branchId'])) {
            $this->setTotal(count($_GET ['branchId']));
            if (is_array($_GET ['branchId'])) {
                $this->branchId = array();
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
            if (isset($_GET ['branchId'])) {
                $this->setBranchId($this->strict($_GET ['branchId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getBranchId($i, 'array') . ",";
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
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setBranchId($value, $key, $type) {
        if ($type == 'single') {
            $this->branchId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->branchId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setbranchId?")
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
    public function getBranchId($key, $type) {
        if ($type == 'single') {
            return $this->branchId;
        } else {
            if ($type == 'array') {
                return $this->branchId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getbranchId ?")
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
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return City
     * @return int $cityId
     */
    public function getCityId() {
        return $this->cityId;
    }

    /**
     * To Set City
     * @param int $cityId City
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setCityId($cityId) {
        $this->cityId = $cityId;
        return $this;
    }

    /**
     * To Return District
     * @return int $districtId
     */
    public function getDistrictId() {
        return $this->districtId;
    }

    /**
     * To Set District
     * @param int $districtId District
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setDistrictId($districtId) {
        $this->districtId = $districtId;
        return $this;
    }

    /**
     * To Return Division
     * @return int $divisionId
     */
    public function getDivisionId() {
        return $this->divisionId;
    }

    /**
     * To Set Division
     * @param int $divisionId Division
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setDivisionId($divisionId) {
        $this->divisionId = $divisionId;
        return $this;
    }

    /**
     * To Return State
     * @return int $stateId
     */
    public function getStateId() {
        return $this->stateId;
    }

    /**
     * To Set State
     * @param int $stateId State
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setStateId($stateId) {
        $this->stateId = $stateId;
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
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setCountryId($countryId) {
        $this->countryId = $countryId;
        return $this;
    }

    /**
     * To Return Code
     * @return string $branchCode
     */
    public function getBranchCode() {
        return $this->branchCode;
    }

    /**
     * To Set Code
     * @param string $branchCode Code
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setBranchCode($branchCode) {
        $this->branchCode = $branchCode;
        return $this;
    }

    /**
     * To Return Logo
     * @return string $branchLogo
     */
    public function getBranchLogo() {
        return $this->branchLogo;
    }

    /**
     * To Set Logo
     * @param string $branchLogo Logo
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setBranchLogo($branchLogo) {
        $this->branchLogo = $branchLogo;
        return $this;
    }

    /**
     * To Return Registration Number
     * @return string $branchRegistrationNumber
     */
    public function getBranchRegistrationNumber() {
        return $this->branchRegistrationNumber;
    }

    /**
     * To Set Registration Number
     * @param string $branchRegistrationNumber Registration Number
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setBranchRegistrationNumber($branchRegistrationNumber) {
        $this->branchRegistrationNumber = $branchRegistrationNumber;
        return $this;
    }

    /**
     * To Return Registration Date
     * @return string $branchRegistrationDate
     */
    public function getBranchRegistrationDate() {
        return $this->branchRegistrationDate;
    }

    /**
     * To Set Registration Date
     * @param string $branchRegistrationDate Registration Date
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setBranchRegistrationDate($branchRegistrationDate) {
        $this->branchRegistrationDate = $branchRegistrationDate;
        return $this;
    }

    /**
     * To Return Name
     * @return string $branchName
     */
    public function getBranchName() {
        return $this->branchName;
    }

    /**
     * To Set Name
     * @param string $branchName Name
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setBranchName($branchName) {
        $this->branchName = $branchName;
        return $this;
    }

    /**
     * To Return Contact Person
     * @return string $branchContactPerson
     */
    public function getBranchContactPerson() {
        return $this->branchContactPerson;
    }

    /**
     * To Set Contact Person
     * @param string $branchContactPerson Contact Person
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setBranchContactPerson($branchContactPerson) {
        $this->branchContactPerson = $branchContactPerson;
        return $this;
    }

    /**
     * To Return Email
     * @return string $branchEmail
     */
    public function getBranchEmail() {
        return $this->branchEmail;
    }

    /**
     * To Set Email
     * @param string $branchEmail Email
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setBranchEmail($branchEmail) {
        $this->branchEmail = $branchEmail;
        return $this;
    }

    /**
     * To Return Fax Number
     * @return string $branchFaxNumber
     */
    public function getBranchFaxNumber() {
        return $this->branchFaxNumber;
    }

    /**
     * To Set Fax Number
     * @param string $branchFaxNumber Fax Number
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setBranchFaxNumber($branchFaxNumber) {
        $this->branchFaxNumber = $branchFaxNumber;
        return $this;
    }

    /**
     * To Return Office Phone
     * @return string $branchOfficePhone
     */
    public function getBranchOfficePhone() {
        return $this->branchOfficePhone;
    }

    /**
     * To Set Office Phone
     * @param string $branchOfficePhone Office Phone
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setBranchOfficePhone($branchOfficePhone) {
        $this->branchOfficePhone = $branchOfficePhone;
        return $this;
    }

    /**
     * To Return Office Secondary
     * @return string $branchOfficePhoneSecondary
     */
    public function getBranchOfficePhoneSecondary() {
        return $this->branchOfficePhoneSecondary;
    }

    /**
     * To Set Office Secondary
     * @param string $branchOfficePhoneSecondary Office Secondary
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setBranchOfficePhoneSecondary($branchOfficePhoneSecondary) {
        $this->branchOfficePhoneSecondary = $branchOfficePhoneSecondary;
        return $this;
    }

    /**
     * To Return Mobile Phone
     * @return string $branchMobilePhone
     */
    public function getBranchMobilePhone() {
        return $this->branchMobilePhone;
    }

    /**
     * To Set Mobile Phone
     * @param string $branchMobilePhone Mobile Phone
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setBranchMobilePhone($branchMobilePhone) {
        $this->branchMobilePhone = $branchMobilePhone;
        return $this;
    }

    /**
     * To Return Post Code
     * @return string $branchPostCode
     */
    public function getBranchPostCode() {
        return $this->branchPostCode;
    }

    /**
     * To Set Post Code
     * @param string $branchPostCode Post Code
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setBranchPostCode($branchPostCode) {
        $this->branchPostCode = $branchPostCode;
        return $this;
    }

    /**
     * To Return Address
     * @return string $branchAddress
     */
    public function getBranchAddress() {
        return $this->branchAddress;
    }

    /**
     * To Set Address
     * @param string $branchAddress Address
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setBranchAddress($branchAddress) {
        $this->branchAddress = $branchAddress;
        return $this;
    }

    /**
     * To Return Maps
     * @return string $branchMaps
     */
    public function getBranchMaps() {
        return $this->branchMaps;
    }

    /**
     * To Set Maps
     * @param string $branchMaps Maps
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setBranchMaps($branchMaps) {
        $this->branchMaps = $branchMaps;
        return $this;
    }

    /**
     * To Return Description
     * @return string $branchDescription
     */
    public function getBranchDescription() {
        return $this->branchName;
    }

    /**
     * To Set Description
     * @param string $branchDescription Description
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setBranchDescription($branchDescription) {
        $this->branchDescription = $branchDescription;
        return $this;
    }

    /**
     * To Return Web Page
     * @return string $branchWebPage
     */
    public function getBranchWebPage() {
        return $this->branchWebPage;
    }

    /**
     * To Set Web Page
     * @param string $branchWebPage Web Page
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setBranchWebPage($branchWebPage) {
        $this->branchWebPage = $branchWebPage;
        return $this;
    }

    /**
     * To Return Facebook
     * @return string $branchFacebook
     */
    public function getBranchFacebook() {
        return $this->branchFacebook;
    }

    /**
     * To Set Facebook
     * @param string $branchFacebook Facebook
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setBranchFacebook($branchFacebook) {
        $this->branchFacebook = $branchFacebook;
        return $this;
    }

    /**
     * To Return Twitter
     * @return string $branchTwitter
     */
    public function getBranchTwitter() {
        return $this->branchTwitter;
    }

    /**
     * To Set Twitter
     * @param string $branchTwitter Twitter
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setBranchTwitter($branchTwitter) {
        $this->branchTwitter = $branchTwitter;
        return $this;
    }

    /**
     * To Set Linked In
     * @param string $branchLinkedIn Linked In
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setBranchLinkedIn($branchLinkedIn) {
        $this->branchLinkedIn = $branchLinkedIn;
        return $this;
    }

    /**
     * To Return Is Franchisee
     * @return bool $isFranchisee
     */
    public function getIsFranchisee() {
        return $this->isFranchisee;
    }

    /**
     * To Set Is Franchisee
     * @param bool $isFranchisee Is Franchisee
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setIsFranchisee($isFranchisee) {
        $this->isFranchisee = $isFranchisee;
        return $this;
    }

}

?>