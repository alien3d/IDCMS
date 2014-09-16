<?php

namespace Core\HumanResource\Employment\Employee\Model;

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
 * Class Employee
 * This is employee model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\HumanResource\Employment\Employee\Model;
 * @subpackage Employment
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class EmployeeModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $employeeId;

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
     * Division
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
     * Job
     * @var int
     */
    private $jobId;

    /**
     * Gender
     * @var int
     */
    private $genderId;

    /**
     * Marriage
     * @var int
     */
    private $marriageId;

    /**
     * Race
     * @var int
     */
    private $raceId;

    /**
     * Religion
     * @var int
     */
    private $religionId;

    /**
     * Employment Status
     * @var int
     */
    private $employmentStatusId;

    /**
     * National Number
     * @var string
     */
    private $nationalNumber;

    /**
     * License Number
     * @var string
     */
    private $licenseNumber;

    /**
     * Number
     * @var string
     */
    private $employeeNumber;

    /**
     * First Name
     * @var string
     */
    private $employeeFirstName;

    /**
     * Picture
     * @var string
     */
    private $employeePicture;

    /**
     * Last Name
     * @var string
     */
    private $employeeLastName;

    /**
     * Date Birth
     * @var string
     */
    private $employeeDateOfBirth;

    /**
     * Date Hired
     * @var string
     */
    private $employeeDateHired;

    /**
     * Date Retired
     * @var string
     */
    private $employeeDateRetired;

    /**
     * Business Phone
     * @var string
     */
    private $employeeBusinessPhone;

    /**
     * Home Phone
     * @var string
     */
    private $employeeHomePhone;

    /**
     * Mobile Phone
     * @var string
     */
    private $employeeMobilePhone;

    /**
     * Fax Number
     * @var string
     */
    private $employeeFaxNumber;

    /**
     * Address
     * @var string
     */
    private $employeeAddress;

    /**
     * Post Code
     * @var string
     */
    private $employeePostCode;

    /**
     * Email
     * @var string
     */
    private $employeeEmail;

    /**
     * Facebook
     * @var string
     */
    private $employeeFacebook;

    /**
     * Twitter
     * @var string
     */
    private $employeeTwitter;

    /**
     * Linked In
     * @var string
     */
    private $employeeLinkedIn;

    /**
     * Notes
     * @var string
     */
    private $employeeNotes;

    /**
     * Cheque Printing
     * @var string
     */
    private $employeeChequePrinting;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('employee');
        $this->setPrimaryKeyName('employeeId');
        $this->setMasterForeignKeyName('employeeId');
        $this->setFilterCharacter('employeeFirstName');
        //$this->setFilterCharacter('employeeNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['employeeId'])) {
            $this->setEmployeeId($this->strict($_POST ['employeeId'], 'numeric'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'numeric'));
        }
        if (isset($_POST ['cityId'])) {
            $this->setCityId($this->strict($_POST ['cityId'], 'numeric'));
        }
        if (isset($_POST ['divisionId'])) {
            $this->setDivisionId($this->strict($_POST ['divisionId'], 'numeric'));
        }
        if (isset($_POST ['cityId'])) {
            $this->setDistrictId($this->strict($_POST ['districtId'], 'numeric'));
        }
        if (isset($_POST ['stateId'])) {
            $this->setStateId($this->strict($_POST ['stateId'], 'numeric'));
        }
        if (isset($_POST ['countryId'])) {
            $this->setCountryId($this->strict($_POST ['countryId'], 'numeric'));
        }
        if (isset($_POST ['jobId'])) {
            $this->setJobId($this->strict($_POST ['jobId'], 'numeric'));
        }
        if (isset($_POST ['genderId'])) {
            $this->setGenderId($this->strict($_POST ['genderId'], 'numeric'));
        }
        if (isset($_POST ['marriageId'])) {
            $this->setMarriageId($this->strict($_POST ['marriageId'], 'numeric'));
        }
        if (isset($_POST ['raceId'])) {
            $this->setRaceId($this->strict($_POST ['raceId'], 'numeric'));
        }
        if (isset($_POST ['religionId'])) {
            $this->setReligionId($this->strict($_POST ['religionId'], 'numeric'));
        }
        if (isset($_POST ['employmentStatusId'])) {
            $this->setEmploymentStatusId($this->strict($_POST ['employmentStatusId'], 'numeric'));
        }
        if (isset($_POST ['nationalNumber'])) {
            $this->setNationalNumber($this->strict($_POST ['nationalNumber'], 'string'));
        }
        if (isset($_POST ['licenseNumber'])) {
            $this->setLicenseNumber($this->strict($_POST ['licenseNumber'], 'string'));
        }
        if (isset($_POST ['employeeNumber'])) {
            $this->setEmployeeNumber($this->strict($_POST ['employeeNumber'], 'string'));
        }
        if (isset($_POST ['employeeFirstName'])) {
            $this->setEmployeeFirstName($this->strict($_POST ['employeeFirstName'], 'string'));
        }
        if (isset($_POST ['employeePicture'])) {
            $this->setEmployeePicture($this->strict($_POST ['employeePicture'], 'string'));
        }
        if (isset($_POST ['employeeLastName'])) {
            $this->setEmployeeLastName($this->strict($_POST ['employeeLastName'], 'string'));
        }
        if (isset($_POST ['employeeDateOfBirth'])) {
            $this->setEmployeeDateOfBirth($this->strict($_POST ['employeeDateOfBirth'], 'date'));
        }
        if (isset($_POST ['employeeDateHired'])) {
            $this->setEmployeeDateHired($this->strict($_POST ['employeeDateHired'], 'date'));
        }
        if (isset($_POST ['employeeDateRetired'])) {
            $this->setEmployeeDateRetired($this->strict($_POST ['employeeDateRetired'], 'date'));
        }
        if (isset($_POST ['employeeBusinessPhone'])) {
            $this->setEmployeeBusinessPhone($this->strict($_POST ['employeeBusinessPhone'], 'string'));
        }
        if (isset($_POST ['employeeHomePhone'])) {
            $this->setEmployeeHomePhone($this->strict($_POST ['employeeHomePhone'], 'string'));
        }
        if (isset($_POST ['employeeMobilePhone'])) {
            $this->setEmployeeMobilePhone($this->strict($_POST ['employeeMobilePhone'], 'string'));
        }
        if (isset($_POST ['employeeFaxNumber'])) {
            $this->setEmployeeFaxNumber($this->strict($_POST ['employeeFaxNumber'], 'string'));
        }
        if (isset($_POST ['employeeAddress'])) {
            $this->setEmployeeAddress($this->strict($_POST ['employeeAddress'], 'string'));
        }
        if (isset($_POST ['employeePostCode'])) {
            $this->setEmployeePostCode($this->strict($_POST ['employeePostCode'], 'string'));
        }
        if (isset($_POST ['employeeEmail'])) {
            $this->setEmployeeEmail($this->strict($_POST ['employeeEmail'], 'string'));
        }
        if (isset($_POST ['employeeFacebook'])) {
            $this->setEmployeeFacebook($this->strict($_POST ['employeeFacebook'], 'string'));
        }
        if (isset($_POST ['employeeTwitter'])) {
            $this->setEmployeeTwitter($this->strict($_POST ['employeeTwitter'], 'string'));
        }
        if (isset($_POST ['employeeLinkedIn'])) {
            $this->setEmployeeLinkedIn($this->strict($_POST ['employeeLinkedIn'], 'string'));
        }
        if (isset($_POST ['employeeNotes'])) {
            $this->setEmployeeNotes($this->strict($_POST ['employeeNotes'], 'string'));
        }
        if (isset($_POST ['employeeChequePrinting'])) {
            $this->setEmployeeChequePrinting($this->strict($_POST ['employeeChequePrinting'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['employeeId'])) {
            $this->setEmployeeId($this->strict($_GET ['employeeId'], 'numeric'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'numeric'));
        }
        if (isset($_GET ['cityId'])) {
            $this->setCityId($this->strict($_GET ['cityId'], 'numeric'));
        }
        if (isset($_GET ['divisionId'])) {
            $this->setDivisionId($this->strict($_GET ['divisionId'], 'numeric'));
        }
        if (isset($_GET ['districtId'])) {
            $this->setDistrictId($this->strict($_GET ['districtId'], 'numeric'));
        }
        if (isset($_GET ['stateId'])) {
            $this->setStateId($this->strict($_GET ['stateId'], 'numeric'));
        }
        if (isset($_GET ['countryId'])) {
            $this->setCountryId($this->strict($_GET ['countryId'], 'numeric'));
        }
        if (isset($_GET ['jobId'])) {
            $this->setJobId($this->strict($_GET ['jobId'], 'numeric'));
        }
        if (isset($_GET ['genderId'])) {
            $this->setGenderId($this->strict($_GET ['genderId'], 'numeric'));
        }
        if (isset($_GET ['marriageId'])) {
            $this->setMarriageId($this->strict($_GET ['marriageId'], 'numeric'));
        }
        if (isset($_GET ['raceId'])) {
            $this->setRaceId($this->strict($_GET ['raceId'], 'numeric'));
        }
        if (isset($_GET ['religionId'])) {
            $this->setReligionId($this->strict($_GET ['religionId'], 'numeric'));
        }
        if (isset($_GET ['employmentStatusId'])) {
            $this->setEmploymentStatusId($this->strict($_GET ['employmentStatusId'], 'numeric'));
        }
        if (isset($_GET ['nationalNumber'])) {
            $this->setNationalNumber($this->strict($_GET ['nationalNumber'], 'string'));
        }
        if (isset($_GET ['licenseNumber'])) {
            $this->setLicenseNumber($this->strict($_GET ['licenseNumber'], 'string'));
        }
        if (isset($_GET ['employeeNumber'])) {
            $this->setEmployeeNumber($this->strict($_GET ['employeeNumber'], 'string'));
        }
        if (isset($_GET ['employeeFirstName'])) {
            $this->setEmployeeFirstName($this->strict($_GET ['employeeFirstName'], 'string'));
        }
        if (isset($_GET ['employeePicture'])) {
            $this->setEmployeePicture($this->strict($_GET ['employeePicture'], 'string'));
        }
        if (isset($_GET ['employeeLastName'])) {
            $this->setEmployeeLastName($this->strict($_GET ['employeeLastName'], 'string'));
        }
        if (isset($_GET ['employeeDateOfBirth'])) {
            $this->setEmployeeDateOfBirth($this->strict($_GET ['employeeDateOfBirth'], 'date'));
        }
        if (isset($_GET ['employeeDateHired'])) {
            $this->setEmployeeDateHired($this->strict($_GET ['employeeDateHired'], 'date'));
        }
        if (isset($_GET ['employeeDateRetired'])) {
            $this->setEmployeeDateRetired($this->strict($_GET ['employeeDateRetired'], 'date'));
        }
        if (isset($_GET ['employeeBusinessPhone'])) {
            $this->setEmployeeBusinessPhone($this->strict($_GET ['employeeBusinessPhone'], 'string'));
        }
        if (isset($_GET ['employeeHomePhone'])) {
            $this->setEmployeeHomePhone($this->strict($_GET ['employeeHomePhone'], 'string'));
        }
        if (isset($_GET ['employeeMobilePhone'])) {
            $this->setEmployeeMobilePhone($this->strict($_GET ['employeeMobilePhone'], 'string'));
        }
        if (isset($_GET ['employeeFaxNumber'])) {
            $this->setEmployeeFaxNumber($this->strict($_GET ['employeeFaxNumber'], 'string'));
        }
        if (isset($_GET ['employeeAddress'])) {
            $this->setEmployeeAddress($this->strict($_GET ['employeeAddress'], 'string'));
        }
        if (isset($_GET ['employeePostCode'])) {
            $this->setEmployeePostCode($this->strict($_GET ['employeePostCode'], 'string'));
        }
        if (isset($_GET ['employeeEmail'])) {
            $this->setEmployeeEmail($this->strict($_GET ['employeeEmail'], 'string'));
        }
        if (isset($_GET ['employeeFacebook'])) {
            $this->setEmployeeFacebook($this->strict($_GET ['employeeFacebook'], 'string'));
        }
        if (isset($_GET ['employeeTwitter'])) {
            $this->setEmployeeTwitter($this->strict($_GET ['employeeTwitter'], 'string'));
        }
        if (isset($_GET ['employeeLinkedIn'])) {
            $this->setEmployeeLinkedIn($this->strict($_GET ['employeeLinkedIn'], 'string'));
        }
        if (isset($_GET ['employeeNotes'])) {
            $this->setEmployeeNotes($this->strict($_GET ['employeeNotes'], 'string'));
        }
        if (isset($_GET ['employeeChequePrinting'])) {
            $this->setEmployeeChequePrinting($this->strict($_GET ['employeeChequePrinting'], 'string'));
        }
        if (isset($_GET ['employeeId'])) {
            $this->setTotal(count($_GET ['employeeId']));
            if (is_array($_GET ['employeeId'])) {
                $this->employeeId = array();
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
            if (isset($_GET ['employeeId'])) {
                $this->setEmployeeId($this->strict($_GET ['employeeId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getEmployeeId($i, 'array') . ",";
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
    public function getEmployeeId($key, $type) {
        if ($type == 'single') {
            return $this->employeeId;
        } else {
            if ($type == 'array') {
                return $this->employeeId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getEmployeeId ?")
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
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setEmployeeId($value, $key, $type) {
        if ($type == 'single') {
            $this->employeeId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->employeeId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setEmployeeId?")
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
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
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
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setCityId($cityId) {
        $this->cityId = $cityId;
        return $this;
    }

    /**
     * To Return Division
     * @return int $cityId
     */
    public function getDivisionId() {
        return $this->divisionId;
    }

    /**
     * To Set Division
     * @param int $cityId Division
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setDivisionId($divisionId) {
        $this->divisionId = $divisionId;
        return $this;
    }

    /**
     * To Return District
     * @return int $cityId
     */
    public function getDistrictId() {
        return $this->districtId;
    }

    /**
     * To Set District
     * @param int $cityId District
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setDistrictId($districtId) {
        $this->districtId = $districtId;
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
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
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
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setCountryId($countryId) {
        $this->countryId = $countryId;
        return $this;
    }

    /**
     * To Return Job
     * @return int $jobId
     */
    public function getJobId() {
        return $this->jobId;
    }

    /**
     * To Set Job
     * @param int $jobId Job
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setJobId($jobId) {
        $this->jobId = $jobId;
        return $this;
    }

    /**
     * To Return Gender
     * @return int $genderId
     */
    public function getGenderId() {
        return $this->genderId;
    }

    /**
     * To Set Gender
     * @param int $genderId Gender
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setGenderId($genderId) {
        $this->genderId = $genderId;
        return $this;
    }

    /**
     * To Return Marriage
     * @return int $marriageId
     */
    public function getMarriageId() {
        return $this->marriageId;
    }

    /**
     * To Set Marriage
     * @param int $marriageId Marriage
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setMarriageId($marriageId) {
        $this->marriageId = $marriageId;
        return $this;
    }

    /**
     * To Return Race
     * @return int $raceId
     */
    public function getRaceId() {
        return $this->raceId;
    }

    /**
     * To Set Race
     * @param int $raceId Race
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setRaceId($raceId) {
        $this->raceId = $raceId;
        return $this;
    }

    /**
     * To Return Religion
     * @return int $religionId
     */
    public function getReligionId() {
        return $this->religionId;
    }

    /**
     * To Set Religion
     * @param int $religionId Religion
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setReligionId($religionId) {
        $this->religionId = $religionId;
        return $this;
    }

    /**
     * To Return Employment Status
     * @return int $employmentStatusId
     */
    public function getEmploymentStatusId() {
        return $this->employmentStatusId;
    }

    /**
     * To Set Employment Status
     * @param int $employmentStatusId Employment Status
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setEmploymentStatusId($employmentStatusId) {
        $this->employmentStatusId = $employmentStatusId;
        return $this;
    }

    /**
     * To Return National Number
     * @return string $nationalNumber
     */
    public function getNationalNumber() {
        return $this->nationalNumber;
    }

    /**
     * To Set National Number
     * @param string $nationalNumber National Number
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setNationalNumber($nationalNumber) {
        $this->nationalNumber = $nationalNumber;
        return $this;
    }

    /**
     * To Return License Number
     * @return string $licenseNumber
     */
    public function getLicenseNumber() {
        return $this->licenseNumber;
    }

    /**
     * To Set License Number
     * @param string $licenseNumber License Number
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setLicenseNumber($licenseNumber) {
        $this->licenseNumber = $licenseNumber;
        return $this;
    }

    /**
     * To Return Number
     * @return string $employeeNumber
     */
    public function getEmployeeNumber() {
        return $this->employeeNumber;
    }

    /**
     * To Set Number
     * @param string $employeeNumber Number
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setEmployeeNumber($employeeNumber) {
        $this->employeeNumber = $employeeNumber;
        return $this;
    }

    /**
     * To Return First Name
     * @return string $employeeFirstName
     */
    public function getEmployeeFirstName() {
        return $this->employeeFirstName;
    }

    /**
     * To Set First Name
     * @param string $employeeFirstName First Name
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setEmployeeFirstName($employeeFirstName) {
        $this->employeeFirstName = $employeeFirstName;
        return $this;
    }

    /**
     * To Return Picture
     * @return string $employeePicture
     */
    public function getEmployeePicture() {
        return $this->employeePicture;
    }

    /**
     * To Set Picture
     * @param string $employeePicture Picture
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setEmployeePicture($employeePicture) {
        $this->employeePicture = $employeePicture;
        return $this;
    }

    /**
     * To Return Last Name
     * @return string $employeeLastName
     */
    public function getEmployeeLastName() {
        return $this->employeeLastName;
    }

    /**
     * To Set Last Name
     * @param string $employeeLastName Last Name
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setEmployeeLastName($employeeLastName) {
        $this->employeeLastName = $employeeLastName;
        return $this;
    }

    /**
     * To Return Date Of Birth
     * @return string $employeeDateOfBirth
     */
    public function getEmployeeDateOfBirth() {
        return $this->employeeDateOfBirth;
    }

    /**
     * To Set Date Of Birth
     * @param string $employeeDateOfBirth Date Birth
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setEmployeeDateOfBirth($employeeDateOfBirth) {
        $this->employeeDateOfBirth = $employeeDateOfBirth;
        return $this;
    }

    /**
     * To Return Date Hired
     * @return string $employeeDateHired
     */
    public function getEmployeeDateHired() {
        return $this->employeeDateHired;
    }

    /**
     * To Set Date Hired
     * @param string $employeeDateHired Date Hired
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setEmployeeDateHired($employeeDateHired) {
        $this->employeeDateHired = $employeeDateHired;
        return $this;
    }

    /**
     * To Return Date Retired
     * @return string $employeeDateRetired
     */
    public function getEmployeeDateRetired() {
        return $this->employeeDateRetired;
    }

    /**
     * To Set Date Retired
     * @param string $employeeDateRetired Date Retired
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setEmployeeDateRetired($employeeDateRetired) {
        $this->employeeDateRetired = $employeeDateRetired;
        return $this;
    }

    /**
     * To Return Business Phone
     * @return string $employeeBusinessPhone
     */
    public function getEmployeeBusinessPhone() {
        return $this->employeeBusinessPhone;
    }

    /**
     * To Set Business Phone
     * @param string $employeeBusinessPhone Business Phone
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setEmployeeBusinessPhone($employeeBusinessPhone) {
        $this->employeeBusinessPhone = $employeeBusinessPhone;
        return $this;
    }

    /**
     * To Return Home Phone
     * @return string $employeeHomePhone
     */
    public function getEmployeeHomePhone() {
        return $this->employeeHomePhone;
    }

    /**
     * To Set Home Phone
     * @param string $employeeHomePhone Home Phone
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setEmployeeHomePhone($employeeHomePhone) {
        $this->employeeHomePhone = $employeeHomePhone;
        return $this;
    }

    /**
     * To Return Mobile Phone
     * @return string $employeeMobilePhone
     */
    public function getEmployeeMobilePhone() {
        return $this->employeeMobilePhone;
    }

    /**
     * To Set Mobile Phone
     * @param string $employeeMobilePhone Mobile Phone
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setEmployeeMobilePhone($employeeMobilePhone) {
        $this->employeeMobilePhone = $employeeMobilePhone;
        return $this;
    }

    /**
     * To Return Fax Number
     * @return string $employeeFaxNumber
     */
    public function getEmployeeFaxNumber() {
        return $this->employeeFaxNumber;
    }

    /**
     * To Set Fax Number
     * @param string $employeeFaxNumber Fax Number
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setEmployeeFaxNumber($employeeFaxNumber) {
        $this->employeeFaxNumber = $employeeFaxNumber;
        return $this;
    }

    /**
     * To Return Address
     * @return string $employeeAddress
     */
    public function getEmployeeAddress() {
        return $this->employeeAddress;
    }

    /**
     * To Set Address
     * @param string $employeeAddress Address
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setEmployeeAddress($employeeAddress) {
        $this->employeeAddress = $employeeAddress;
        return $this;
    }

    /**
     * To Return PostCode
     * @return string $employeePostCode
     */
    public function getEmployeePostCode() {
        return $this->employeePostCode;
    }

    /**
     * To Set PostCode
     * @param string $employeePostCode Post Code
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setEmployeePostCode($employeePostCode) {
        $this->employeePostCode = $employeePostCode;
        return $this;
    }

    /**
     * To Return Email
     * @return string $employeeEmail
     */
    public function getEmployeeEmail() {
        return $this->employeeEmail;
    }

    /**
     * To Set Email
     * @param string $employeeEmail Email
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setEmployeeEmail($employeeEmail) {
        $this->employeeEmail = $employeeEmail;
        return $this;
    }

    /**
     * To Return Facebook
     * @return string $employeeFacebook
     */
    public function getEmployeeFacebook() {
        return $this->employeeFacebook;
    }

    /**
     * To Set Facebook
     * @param string $employeeFacebook Facebook
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setEmployeeFacebook($employeeFacebook) {
        $this->employeeFacebook = $employeeFacebook;
        return $this;
    }

    /**
     * To Return Twitter
     * @return string $employeeTwitter
     */
    public function getEmployeeTwitter() {
        return $this->employeeTwitter;
    }

    /**
     * To Set Twitter
     * @param string $employeeTwitter Twitter
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setEmployeeTwitter($employeeTwitter) {
        $this->employeeTwitter = $employeeTwitter;
        return $this;
    }

    /**
     * To Return LinkedIn
     * @return string $employeeLinkedIn
     */
    public function getEmployeeLinkedIn() {
        return $this->employeeLinkedIn;
    }

    /**
     * To Set LinkedIn
     * @param string $employeeLinkedIn Linked In
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setEmployeeLinkedIn($employeeLinkedIn) {
        $this->employeeLinkedIn = $employeeLinkedIn;
        return $this;
    }

    /**
     * To Return Notes
     * @return string $employeeNotes
     */
    public function getEmployeeNotes() {
        return $this->employeeNotes;
    }

    /**
     * To Set Notes
     * @param string $employeeNotes Notes
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setEmployeeNotes($employeeNotes) {
        $this->employeeNotes = $employeeNotes;
        return $this;
    }

    /**
     * To Return Cheque Printing
     * @return string $employeeChequePrinting
     */
    public function getEmployeeChequePrinting() {
        return $this->employeeChequePrinting;
    }

    /**
     * To Set Cheque Printing
     * @param string $employeeChequePrinting Cheque Printing
     * @return \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public function setEmployeeChequePrinting($employeeChequePrinting) {
        $this->employeeChequePrinting = $employeeChequePrinting;
        return $this;
    }

}

?>