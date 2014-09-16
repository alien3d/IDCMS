<?php

namespace Core\HumanResource\Recruitment\Candidate\Model;

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
 * Class Candidate
 * This is Candidate Model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\HumanResource\Recruitment\Candidate\Model;
 * @subpackage Recruitment
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class CandidateModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $candidateId;

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
     * First Name
     * @var string
     */
    private $candidateFirstName;

    /**
     * Last Name
     * @var string
     */
    private $candidateLastName;

    /**
     * Email
     * @var string
     */
    private $candidateEmail;

    /**
     * Business Phone
     * @var string
     */
    private $candidateBusinessPhone;

    /**
     * Home Phone
     * @var string
     */
    private $candidateHomePhone;

    /**
     * Mobile Phone
     * @var string
     */
    private $candidateMobilePhone;

    /**
     * Fax Number
     * @var string
     */
    private $candidateFaxNumber;

    /**
     * Address
     * @var string
     */
    private $candidateAddress;

    /**
     * Post Code
     * @var string
     */
    private $candidatePostCode;

    /**
     * Web Page
     * @var string
     */
    private $candidateWebPage;

    /**
     * Facebook
     * @var string
     */
    private $candidateFacebook;

    /**
     * Twitter
     * @var string
     */
    private $candidateTwitter;

    /**
     * Linked In
     * @var string
     */
    private $candidateLinkedIn;

    /**
     * Notes
     * @var string
     */
    private $candidateNotes;

    /**
     * Picture
     * @var string
     */
    private $candidatePicture;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('candidate');
        $this->setPrimaryKeyName('candidateId');
        $this->setMasterForeignKeyName('candidateId');
        $this->setFilterCharacter('candidateFirstName');
        //$this->setFilterCharacter('candidateNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['candidateId'])) {
            $this->setCandidateId($this->strict($_POST ['candidateId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['cityId'])) {
            $this->setCityId($this->strict($_POST ['cityId'], 'integer'));
        }
        if (isset($_POST ['divisionId'])) {
            $this->setDivisionId($this->strict($_POST ['divisionId'], 'integer'));
        }
        if (isset($_POST ['districtId'])) {
            $this->setDistrictId($this->strict($_POST ['districtId'], 'integer'));
        }
        if (isset($_POST ['stateId'])) {
            $this->setStateId($this->strict($_POST ['stateId'], 'integer'));
        }
        if (isset($_POST ['countryId'])) {
            $this->setCountryId($this->strict($_POST ['countryId'], 'integer'));
        }
        if (isset($_POST ['genderId'])) {
            $this->setGenderId($this->strict($_POST ['genderId'], 'integer'));
        }
        if (isset($_POST ['marriageId'])) {
            $this->setMarriageId($this->strict($_POST ['marriageId'], 'integer'));
        }
        if (isset($_POST ['raceId'])) {
            $this->setRaceId($this->strict($_POST ['raceId'], 'integer'));
        }
        if (isset($_POST ['religionId'])) {
            $this->setReligionId($this->strict($_POST ['religionId'], 'integer'));
        }
        if (isset($_POST ['candidateFirstName'])) {
            $this->setCandidateFirstName($this->strict($_POST ['candidateFirstName'], 'string'));
        }
        if (isset($_POST ['candidateLastName'])) {
            $this->setCandidateLastName($this->strict($_POST ['candidateLastName'], 'string'));
        }
        if (isset($_POST ['candidateEmail'])) {
            $this->setCandidateEmail($this->strict($_POST ['candidateEmail'], 'string'));
        }
        if (isset($_POST ['candidateBusinessPhone'])) {
            $this->setCandidateBusinessPhone($this->strict($_POST ['candidateBusinessPhone'], 'string'));
        }
        if (isset($_POST ['candidateHomePhone'])) {
            $this->setCandidateHomePhone($this->strict($_POST ['candidateHomePhone'], 'string'));
        }
        if (isset($_POST ['candidateMobilePhone'])) {
            $this->setCandidateMobilePhone($this->strict($_POST ['candidateMobilePhone'], 'string'));
        }
        if (isset($_POST ['candidateFaxNumber'])) {
            $this->setCandidateFaxNumber($this->strict($_POST ['candidateFaxNumber'], 'string'));
        }
        if (isset($_POST ['candidateAddress'])) {
            $this->setCandidateAddress($this->strict($_POST ['candidateAddress'], 'string'));
        }
        if (isset($_POST ['candidatePostCode'])) {
            $this->setCandidatePostCode($this->strict($_POST ['candidatePostCode'], 'string'));
        }
        if (isset($_POST ['candidateWebPage'])) {
            $this->setCandidateWebPage($this->strict($_POST ['candidateWebPage'], 'string'));
        }
        if (isset($_POST ['candidateFacebook'])) {
            $this->setCandidateFacebook($this->strict($_POST ['candidateFacebook'], 'string'));
        }
        if (isset($_POST ['candidateTwitter'])) {
            $this->setCandidateTwitter($this->strict($_POST ['candidateTwitter'], 'string'));
        }
        if (isset($_POST ['candidateLinkedIn'])) {
            $this->setCandidateLinkedIn($this->strict($_POST ['candidateLinkedIn'], 'string'));
        }
        if (isset($_POST ['candidateNotes'])) {
            $this->setCandidateNotes($this->strict($_POST ['candidateNotes'], 'string'));
        }
        if (isset($_POST ['candidatePicture'])) {
            $this->setCandidatePicture($this->strict($_POST ['candidatePicture'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['candidateId'])) {
            $this->setCandidateId($this->strict($_GET ['candidateId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['cityId'])) {
            $this->setCityId($this->strict($_GET ['cityId'], 'integer'));
        }
        if (isset($_GET ['divisionId'])) {
            $this->setDivisionId($this->strict($_GET ['divisionId'], 'integer'));
        }
        if (isset($_GET ['districtId'])) {
            $this->setDistrictId($this->strict($_GET ['districtId'], 'integer'));
        }
        if (isset($_GET ['stateId'])) {
            $this->setStateId($this->strict($_GET ['stateId'], 'integer'));
        }
        if (isset($_GET ['countryId'])) {
            $this->setCountryId($this->strict($_GET ['countryId'], 'integer'));
        }
        if (isset($_GET ['genderId'])) {
            $this->setGenderId($this->strict($_GET ['genderId'], 'integer'));
        }
        if (isset($_GET ['marriageId'])) {
            $this->setMarriageId($this->strict($_GET ['marriageId'], 'integer'));
        }
        if (isset($_GET ['raceId'])) {
            $this->setRaceId($this->strict($_GET ['raceId'], 'integer'));
        }
        if (isset($_GET ['religionId'])) {
            $this->setReligionId($this->strict($_GET ['religionId'], 'integer'));
        }
        if (isset($_GET ['candidateFirstName'])) {
            $this->setCandidateFirstName($this->strict($_GET ['candidateFirstName'], 'string'));
        }
        if (isset($_GET ['candidateLastName'])) {
            $this->setCandidateLastName($this->strict($_GET ['candidateLastName'], 'string'));
        }
        if (isset($_GET ['candidateEmail'])) {
            $this->setCandidateEmail($this->strict($_GET ['candidateEmail'], 'string'));
        }
        if (isset($_GET ['candidateBusinessPhone'])) {
            $this->setCandidateBusinessPhone($this->strict($_GET ['candidateBusinessPhone'], 'string'));
        }
        if (isset($_GET ['candidateHomePhone'])) {
            $this->setCandidateHomePhone($this->strict($_GET ['candidateHomePhone'], 'string'));
        }
        if (isset($_GET ['candidateMobilePhone'])) {
            $this->setCandidateMobilePhone($this->strict($_GET ['candidateMobilePhone'], 'string'));
        }
        if (isset($_GET ['candidateFaxNumber'])) {
            $this->setCandidateFaxNumber($this->strict($_GET ['candidateFaxNumber'], 'string'));
        }
        if (isset($_GET ['candidateAddress'])) {
            $this->setCandidateAddress($this->strict($_GET ['candidateAddress'], 'string'));
        }
        if (isset($_GET ['candidatePostCode'])) {
            $this->setCandidatePostCode($this->strict($_GET ['candidatePostCode'], 'string'));
        }
        if (isset($_GET ['candidateWebPage'])) {
            $this->setCandidateWebPage($this->strict($_GET ['candidateWebPage'], 'string'));
        }
        if (isset($_GET ['candidateFacebook'])) {
            $this->setCandidateFacebook($this->strict($_GET ['candidateFacebook'], 'string'));
        }
        if (isset($_GET ['candidateTwitter'])) {
            $this->setCandidateTwitter($this->strict($_GET ['candidateTwitter'], 'string'));
        }
        if (isset($_GET ['candidateLinkedIn'])) {
            $this->setCandidateLinkedIn($this->strict($_GET ['candidateLinkedIn'], 'string'));
        }
        if (isset($_GET ['candidateNotes'])) {
            $this->setCandidateNotes($this->strict($_GET ['candidateNotes'], 'string'));
        }
        if (isset($_GET ['candidatePicture'])) {
            $this->setCandidatePicture($this->strict($_GET ['candidatePicture'], 'string'));
        }
        if (isset($_GET ['candidateId'])) {
            $this->setTotal(count($_GET ['candidateId']));
            if (is_array($_GET ['candidateId'])) {
                $this->candidateId = array();
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
            if (isset($_GET ['candidateId'])) {
                $this->setCandidateId($this->strict($_GET ['candidateId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getCandidateId($i, 'array') . ",";
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
    public function getCandidateId($key, $type) {
        if ($type == 'single') {
            return $this->candidateId;
        } else {
            if ($type == 'array') {
                return $this->candidateId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getCandidateId ?")
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
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
     */
    public function setCandidateId($value, $key, $type) {
        if ($type == 'single') {
            $this->candidateId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->candidateId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setCandidateId?")
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
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
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
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
     */
    public function setCityId($cityId) {
        $this->cityId = $cityId;
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
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
     */
    public function setDivisionId($divisionId) {
        $this->divisionId = $divisionId;
        return $this;
    }

    /**
     * To Return District
     * @return int $districtId
     */
    public function getDistrictId() {
        return $this->cityId;
    }

    /**
     * To Set District
     * @param int $districtId District
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
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
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
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
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
     */
    public function setCountryId($countryId) {
        $this->countryId = $countryId;
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
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
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
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
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
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
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
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
     */
    public function setReligionId($religionId) {
        $this->religionId = $religionId;
        return $this;
    }

    /**
     * To Return First Name
     * @return string $candidateFirstName
     */
    public function getCandidateFirstName() {
        return $this->candidateFirstName;
    }

    /**
     * To Set First Name
     * @param string $candidateFirstName First Name
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
     */
    public function setCandidateFirstName($candidateFirstName) {
        $this->candidateFirstName = $candidateFirstName;
        return $this;
    }

    /**
     * To Return Last Name
     * @return string $candidateLastName
     */
    public function getCandidateLastName() {
        return $this->candidateLastName;
    }

    /**
     * To Set Last Name
     * @param string $candidateLastName Last Name
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
     */
    public function setCandidateLastName($candidateLastName) {
        $this->candidateLastName = $candidateLastName;
        return $this;
    }

    /**
     * To Return Email
     * @return string $candidateEmail
     */
    public function getCandidateEmail() {
        return $this->candidateEmail;
    }

    /**
     * To Set Email
     * @param string $candidateEmail Email
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
     */
    public function setCandidateEmail($candidateEmail) {
        $this->candidateEmail = $candidateEmail;
        return $this;
    }

    /**
     * To Return Business Phone
     * @return string $candidateBusinessPhone
     */
    public function getCandidateBusinessPhone() {
        return $this->candidateBusinessPhone;
    }

    /**
     * To Set Business Phone
     * @param string $candidateBusinessPhone Business Phone
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
     */
    public function setCandidateBusinessPhone($candidateBusinessPhone) {
        $this->candidateBusinessPhone = $candidateBusinessPhone;
        return $this;
    }

    /**
     * To Return Home Phone
     * @return string $candidateHomePhone
     */
    public function getCandidateHomePhone() {
        return $this->candidateHomePhone;
    }

    /**
     * To Set Home Phone
     * @param string $candidateHomePhone Home Phone
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
     */
    public function setCandidateHomePhone($candidateHomePhone) {
        $this->candidateHomePhone = $candidateHomePhone;
        return $this;
    }

    /**
     * To Return Mobile Phone
     * @return string $candidateMobilePhone
     */
    public function getCandidateMobilePhone() {
        return $this->candidateMobilePhone;
    }

    /**
     * To Set Mobile Phone
     * @param string $candidateMobilePhone Mobile Phone
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
     */
    public function setCandidateMobilePhone($candidateMobilePhone) {
        $this->candidateMobilePhone = $candidateMobilePhone;
        return $this;
    }

    /**
     * To Return Fax Number
     * @return string $candidateFaxNumber
     */
    public function getCandidateFaxNumber() {
        return $this->candidateFaxNumber;
    }

    /**
     * To Set Fax Number
     * @param string $candidateFaxNumber Fax Number
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
     */
    public function setCandidateFaxNumber($candidateFaxNumber) {
        $this->candidateFaxNumber = $candidateFaxNumber;
        return $this;
    }

    /**
     * To Return Address
     * @return string $candidateAddress
     */
    public function getCandidateAddress() {
        return $this->candidateAddress;
    }

    /**
     * To Set Address
     * @param string $candidateAddress Address
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
     */
    public function setCandidateAddress($candidateAddress) {
        $this->candidateAddress = $candidateAddress;
        return $this;
    }

    /**
     * To Return PostCode
     * @return string $candidatePostCode
     */
    public function getCandidatePostCode() {
        return $this->candidatePostCode;
    }

    /**
     * To Set PostCode
     * @param string $candidatePostCode Post Code
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
     */
    public function setCandidatePostCode($candidatePostCode) {
        $this->candidatePostCode = $candidatePostCode;
        return $this;
    }

    /**
     * To Return WebPage
     * @return string $candidateWebPage
     */
    public function getCandidateWebPage() {
        return $this->candidateWebPage;
    }

    /**
     * To Set WebPage
     * @param string $candidateWebPage Web Page
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
     */
    public function setCandidateWebPage($candidateWebPage) {
        $this->candidateWebPage = $candidateWebPage;
        return $this;
    }

    /**
     * To Return Facebook
     * @return string $candidateFacebook
     */
    public function getCandidateFacebook() {
        return $this->candidateFacebook;
    }

    /**
     * To Set Facebook
     * @param string $candidateFacebook Facebook
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
     */
    public function setCandidateFacebook($candidateFacebook) {
        $this->candidateFacebook = $candidateFacebook;
        return $this;
    }

    /**
     * To Return Twitter
     * @return string $candidateTwitter
     */
    public function getCandidateTwitter() {
        return $this->candidateTwitter;
    }

    /**
     * To Set Twitter
     * @param string $candidateTwitter Twitter
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
     */
    public function setCandidateTwitter($candidateTwitter) {
        $this->candidateTwitter = $candidateTwitter;
        return $this;
    }

    /**
     * To Return LinkedIn
     * @return string $candidateLinkedIn
     */
    public function getCandidateLinkedIn() {
        return $this->candidateLinkedIn;
    }

    /**
     * To Set LinkedIn
     * @param string $candidateLinkedIn Linked In
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
     */
    public function setCandidateLinkedIn($candidateLinkedIn) {
        $this->candidateLinkedIn = $candidateLinkedIn;
        return $this;
    }

    /**
     * To Return Notes
     * @return string $candidateNotes
     */
    public function getCandidateNotes() {
        return $this->candidateNotes;
    }

    /**
     * To Set Notes
     * @param string $candidateNotes Notes
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
     */
    public function setCandidateNotes($candidateNotes) {
        $this->candidateNotes = $candidateNotes;
        return $this;
    }

    /**
     * To Return Picture
     * @return string $candidatePicture
     */
    public function getCandidatePicture() {
        return $this->candidatePicture;
    }

    /**
     * To Set Picture
     * @param string $candidatePicture Picture
     * @return \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
     */
    public function setCandidatePicture($candidatePicture) {
        $this->candidatePicture = $candidatePicture;
        return $this;
    }

}

?>