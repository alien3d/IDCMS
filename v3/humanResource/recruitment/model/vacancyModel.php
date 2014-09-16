<?php

namespace Core\HumanResource\Recruitment\Vacancy\Model;

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
 * Class Vacancy
 * This is Vacancy Model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\HumanResource\Recruitment\Vacancy\Model;
 * @subpackage Recruitment
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class VacancyModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $vacancyId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Job
     * @var int
     */
    private $jobId;

    /**
     * Title
     * @var string
     */
    private $vacancyTitle;

    /**
     * Required Date
     * @var string
     */
    private $vacancyRequiredDate;

    /**
     * Required People
     * @var int
     */
    private $vacancyRequiredPeople;

    /**
     * Description
     * @var string
     */
    private $vacancyDescription;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('vacancy');
        $this->setPrimaryKeyName('vacancyId');
        $this->setMasterForeignKeyName('vacancyId');
        $this->setFilterCharacter('vacancyDescription');
        //$this->setFilterCharacter('vacancyNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['vacancyId'])) {
            $this->setVacancyId($this->strict($_POST ['vacancyId'], 'string'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'string'));
        }
        if (isset($_POST ['jobId'])) {
            $this->setJobId($this->strict($_POST ['jobId'], 'string'));
        }
        if (isset($_POST ['vacancyTitle'])) {
            $this->setVacancyTitle($this->strict($_POST ['vacancyTitle'], 'string'));
        }
        if (isset($_POST ['vacancyRequiredDate'])) {
            $this->setVacancyRequiredDate($this->strict($_POST ['vacancyRequiredDate'], 'date'));
        }
        if (isset($_POST ['vacancyRequiredPeople'])) {
            $this->setVacancyRequiredPeople($this->strict($_POST ['vacancyRequiredPeople'], 'string'));
        }
        if (isset($_POST ['vacancyDescription'])) {
            $this->setVacancyDescription($this->strict($_POST ['vacancyDescription'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['vacancyId'])) {
            $this->setVacancyId($this->strict($_GET ['vacancyId'], 'string'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'string'));
        }
        if (isset($_GET ['jobId'])) {
            $this->setJobId($this->strict($_GET ['jobId'], 'string'));
        }
        if (isset($_GET ['vacancyTitle'])) {
            $this->setVacancyTitle($this->strict($_GET ['vacancyTitle'], 'string'));
        }
        if (isset($_GET ['vacancyRequiredDate'])) {
            $this->setVacancyRequiredDate($this->strict($_GET ['vacancyRequiredDate'], 'date'));
        }
        if (isset($_GET ['vacancyRequiredPeople'])) {
            $this->setVacancyRequiredPeople($this->strict($_GET ['vacancyRequiredPeople'], 'string'));
        }
        if (isset($_GET ['vacancyDescription'])) {
            $this->setVacancyDescription($this->strict($_GET ['vacancyDescription'], 'string'));
        }
        if (isset($_GET ['vacancyId'])) {
            $this->setTotal(count($_GET ['vacancyId']));
            if (is_array($_GET ['vacancyId'])) {
                $this->vacancyId = array();
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
            if (isset($_GET ['vacancyId'])) {
                $this->setVacancyId($this->strict($_GET ['vacancyId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getVacancyId($i, 'array') . ",";
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
    public function getVacancyId($key, $type) {
        if ($type == 'single') {
            return $this->vacancyId;
        } else {
            if ($type == 'array') {
                return $this->vacancyId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getVacancyId ?")
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
     * @return \Core\HumanResource\Recruitment\Vacancy\Model\VacancyModel
     */
    public function setVacancyId($value, $key, $type) {
        if ($type == 'single') {
            $this->vacancyId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->vacancyId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setVacancyId?")
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
     * @return \Core\HumanResource\Recruitment\Vacancy\Model\VacancyModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
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
     * @return \Core\HumanResource\Recruitment\Vacancy\Model\VacancyModel
     */
    public function setJobId($jobId) {
        $this->jobId = $jobId;
        return $this;
    }

    /**
     * To Return Title
     * @return string $vacancyTitle
     */
    public function getVacancyTitle() {
        return $this->vacancyTitle;
    }

    /**
     * To Set Title
     * @param string $vacancyTitle Title
     * @return \Core\HumanResource\Recruitment\Vacancy\Model\VacancyModel
     */
    public function setVacancyTitle($vacancyTitle) {
        $this->vacancyTitle = $vacancyTitle;
        return $this;
    }

    /**
     * To Return Required Date
     * @return string $vacancyRequiredDate
     */
    public function getVacancyRequiredDate() {
        return $this->vacancyRequiredDate;
    }

    /**
     * To Set Required Date
     * @param string $vacancyRequiredDate Required Date
     * @return \Core\HumanResource\Recruitment\Vacancy\Model\VacancyModel
     */
    public function setVacancyRequiredDate($vacancyRequiredDate) {
        $this->vacancyRequiredDate = $vacancyRequiredDate;
        return $this;
    }

    /**
     * To Return Required People
     * @return int $vacancyRequiredPeople
     */
    public function getVacancyRequiredPeople() {
        return $this->vacancyRequiredPeople;
    }

    /**
     * To Set Required People
     * @param int $vacancyRequiredPeople Required People
     * @return \Core\HumanResource\Recruitment\Vacancy\Model\VacancyModel
     */
    public function setVacancyRequiredPeople($vacancyRequiredPeople) {
        $this->vacancyRequiredPeople = $vacancyRequiredPeople;
        return $this;
    }

    /**
     * To Return Description
     * @return string $vacancyDescription
     */
    public function getVacancyDescription() {
        return $this->vacancyDescription;
    }

    /**
     * To Set Description
     * @param string $vacancyDescription Description
     * @return \Core\HumanResource\Recruitment\Vacancy\Model\VacancyModel
     */
    public function setVacancyDescription($vacancyDescription) {
        $this->vacancyDescription = $vacancyDescription;
        return $this;
    }

}

?>