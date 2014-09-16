<?php

namespace Core\Financial\GeneralLedger\JournalRecurring\Model;

use Core\Validation\ValidationClass;

if (!isset($_SESSION)) {
    session_start();
}
// using absolute path instead of relative path..
// start fake document root. it's absolute path
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
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot);
require_once($newFakeDocumentRoot . "library/class/classValidation.php");

/**
 * Class JournalRecurringModel
 * this is Journal Recurring model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\GeneralLedger\JournalRecurring\Model
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class JournalRecurringModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $journalRecurringId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Type
     * @var int
     */
    private $journalRecurringTypeId;

    /**
     * Document Number
     * @var string
     */
    private $documentNo;

    /**
     * Title
     * @var string
     */
    private $journalTitle;

    /**
     * Description
     * @var string
     */
    private $journalDesc;

    /**
     * Date
     * @var string
     */
    private $journalDate;

    /**
     * Start Date
     * @var string
     */
    private $journalStartDate;

    /**
     * End Date
     * @var string
     */
    private $journalEndDate;

    /**
     * Amount
     * @var float
     */
    private $journalAmount;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('journalRecurring');
        $this->setPrimaryKeyName('journalRecurringId');
        $this->setMasterForeignKeyName('journalRecurringId');
        $this->setFilterCharacter('journalRecurringDescription');
        //$this->setFilterCharacter('journalRecurringNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['journalRecurringId'])) {
            $this->setJournalRecurringId($this->strict($_POST ['journalRecurringId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['journalRecurringTypeId'])) {
            $this->setJournalRecurringTypeId($this->strict($_POST ['journalRecurringTypeId'], 'integer'));
        }
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNo($this->strict($_POST ['documentNumber'], 'text'));
        }
        if (isset($_POST ['invoiceTitle'])) {
            $this->setJournalTitle($this->strict($_POST ['invoiceTitle'], 'text'));
        }
        if (isset($_POST ['journalDesc'])) {
            $this->setJournalDesc($this->strict($_POST ['journalDesc'], 'text'));
        }
        if (isset($_POST ['journalDate'])) {
            $this->setJournalDate($this->strict($_POST ['journalDate'], 'date'));
        }
        if (isset($_POST ['journalStartDate'])) {
            $this->setJournalStartDate($this->strict($_POST ['journalStartDate'], 'date'));
        }
        if (isset($_POST ['journalEndDate'])) {
            $this->setJournalEndDate($this->strict($_POST ['journalEndDate'], 'date'));
        }
        if (isset($_POST ['journalAmount'])) {
            $this->setJournalAmount($this->strict($_POST ['journalAmount'], 'float'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['journalRecurringId'])) {
            $this->setJournalRecurringId($this->strict($_GET ['journalRecurringId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['journalRecurringTypeId'])) {
            $this->setJournalRecurringTypeId($this->strict($_GET ['journalRecurringTypeId'], 'integer'));
        }
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNo($this->strict($_GET ['documentNumber'], 'text'));
        }
        if (isset($_GET ['invoiceTitle'])) {
            $this->setJournalTitle($this->strict($_GET ['invoiceTitle'], 'text'));
        }
        if (isset($_GET ['journalDesc'])) {
            $this->setJournalDesc($this->strict($_GET ['journalDesc'], 'text'));
        }
        if (isset($_GET ['journalDate'])) {
            $this->setJournalDate($this->strict($_GET ['journalDate'], 'date'));
        }
        if (isset($_GET ['journalStartDate'])) {
            $this->setJournalStartDate($this->strict($_GET ['journalStartDate'], 'date'));
        }
        if (isset($_GET ['journalEndDate'])) {
            $this->setJournalEndDate($this->strict($_GET ['journalEndDate'], 'date'));
        }
        if (isset($_GET ['journalAmount'])) {
            $this->setJournalAmount($this->strict($_GET ['journalAmount'], 'float'));
        }
        if (isset($_GET ['journalRecurringId'])) {
            $this->setTotal(count($_GET ['journalRecurringId']));
            if (is_array($_GET ['journalRecurringId'])) {
                $this->journalRecurringId = array();
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
            if (isset($_GET ['journalRecurringId'])) {
                $this->setJournalRecurringId($this->strict($_GET ['journalRecurringId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getJournalRecurringId($i, 'array') . ",";
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
     * @return bool|array
     */
    public function getJournalRecurringId($key, $type) {
        if ($type == 'single') {
            return $this->journalRecurringId;
        } else {
            if ($type == 'array') {
                return $this->journalRecurringId [$key];
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:getJournalRecurringId ?"
                        )
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
     * @return \Core\Financial\GeneralLedger\JournalRecurring\Model\JournalRecurringModel
     */
    public function setJournalRecurringId($value, $key, $type) {
        if ($type == 'single') {
            $this->journalRecurringId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->journalRecurringId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:setJournalRecurringId?"
                        )
                );
                exit();
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
     * To Return Company
     * @return int $companyId
     */
    public function getCompanyId() {
        return $this->companyId;
    }

    /**
     * To Set Company
     * @param int $companyId
     * @return \Core\Financial\GeneralLedger\JournalRecurring\Model\JournalRecurringModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return int journalRecurringTypeId
     * @return int $journalRecurringTypeId
     */
    public function getJournalRecurringTypeId() {
        return $this->journalRecurringTypeId;
    }

    /**
     * To Set Type
     * @param int $journalRecurringTypeId
     * @return \Core\Financial\GeneralLedger\JournalRecurring\Model\JournalRecurringModel
     */
    public function setJournalRecurringTypeId($journalRecurringTypeId) {
        $this->journalRecurringTypeId = $journalRecurringTypeId;
        return $this;
    }

    /**
     * To Return Document Number
     * @return string $documentNumber
     */
    public function getDocumentNo() {
        return $this->documentNo;
    }

    /**
     * To Set Document Number
     * @param string $documentNo
     * @return \Core\Financial\GeneralLedger\JournalRecurring\Model\JournalRecurringModel
     */
    public function setDocumentNo($documentNo) {
        $this->documentNo = $documentNo;
        return $this;
    }

    /**
     * To Return Title
     * @return string $invoiceTitle
     */
    public function getJournalTitle() {
        return $this->journalTitle;
    }

    /**
     * To Set Title
     * @param string $journalTitle
     * @return \Core\Financial\GeneralLedger\JournalRecurring\Model\JournalRecurringModel
     */
    public function setJournalTitle($journalTitle) {
        $this->journalTitle = $journalTitle;
        return $this;
    }

    /**
     * To Return Description
     * @return string $journalDesc
     */
    public function getJournalDesc() {
        return $this->journalDesc;
    }

    /**
     * To Set Description
     * @param string $journalDesc
     * @return \Core\Financial\GeneralLedger\JournalRecurring\Model\JournalRecurringModel
     */
    public function setJournalDesc($journalDesc) {
        $this->journalDesc = $journalDesc;
        return $this;
    }

    /**
     * To Return Date
     * @return string $journalDate
     */
    public function getJournalDate() {
        return $this->journalDate;
    }

    /**
     * To Set Date
     * @param string $journalDate
     * @return \Core\Financial\GeneralLedger\JournalRecurring\Model\JournalRecurringModel
     */
    public function setJournalDate($journalDate) {
        $this->journalDate = $journalDate;
        return $this;
    }

    /**
     * To Return Start Date
     * @return string $journalStartDate
     */
    public function getJournalStartDate() {
        return $this->journalStartDate;
    }

    /**
     * To Set Start Date
     * @param string $journalStartDate
     * @return \Core\Financial\GeneralLedger\JournalRecurring\Model\JournalRecurringModel
     */
    public function setJournalStartDate($journalStartDate) {
        $this->journalStartDate = $journalStartDate;
        return $this;
    }

    /**
     * To Return End Date
     * @return string $journalEndDate
     */
    public function getJournalEndDate() {
        return $this->journalEndDate;
    }

    /**
     * To Set End Date
     * @param string $journalEndDate
     * @return \Core\Financial\GeneralLedger\JournalRecurring\Model\JournalRecurringModel
     */
    public function setJournalEndDate($journalEndDate) {
        $this->journalEndDate = $journalEndDate;
        return $this;
    }

    /**
     * To Return Amount
     * @return float $journalAmount
     */
    public function getJournalAmount() {
        return $this->journalAmount;
    }

    /**
     * To Set Amount
     * @param float $journalAmount
     * @return \Core\Financial\GeneralLedger\JournalRecurring\Model\JournalRecurringModel
     */
    public function setJournalAmount($journalAmount) {
        $this->journalAmount = $journalAmount;
        return $this;
    }

}

?>