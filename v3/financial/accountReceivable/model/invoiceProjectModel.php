<?php

namespace Core\Financial\AccountReceivable\InvoiceProject\Model;

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
 * Class InvoiceProject
 * This is invoiceProject model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountReceivable\InvoiceProject\Model;
 * @subpackage AccountReceivable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class InvoiceProjectModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $invoiceProjectId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Document Number
     * @var string
     */
    private $documentNumber;

    /**
     * Reference Number
     * @var string
     */
    private $referenceNumber;

    /**
     * Title
     * @var string
     */
    private $invoiceProjectTitle;

    /**
     * Start Date
     * @var date
     */
    private $invoiceProjectStartDate;

    /**
     * End Date
     * @var date
     */
    private $invoiceProjectEndDate;

    /**
     * Current Stage
     * @var int
     */
    private $invoiceProjectCurrentStage;

    /**
     * Total Stage
     * @var int
     */
    private $invoiceProjectTotalStage;

    /**
     * Value
     * @var double
     */
    private $invoiceProjectValue;

    /**
     * Claim
     * @var double
     */
    private $invoiceProjectClaim;

    /**
     * Balance
     * @var double
     */
    private $invoiceProjectBalance;

    /**
     * Retention Value
     * @var double
     */
    private $invoiceProjectRetentionValue;

    /**
     * Retention Percent
     * @var double
     */
    private $invoiceProjectRetentionPercent;

    /**
     * Description
     * @var string
     */
    private $invoiceProjectDescription;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('invoiceProject');
        $this->setPrimaryKeyName('invoiceProjectId');
        $this->setMasterForeignKeyName('invoiceProjectId');
        $this->setFilterCharacter('invoiceProjectDescription');
        //$this->setFilterCharacter('invoiceProjectNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['invoiceProjectId'])) {
            $this->setInvoiceProjectId($this->strict($_POST ['invoiceProjectId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string'));
        }
        if (isset($_POST ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_POST ['referenceNumber'], 'string'));
        }
        if (isset($_POST ['invoiceProjectTitle'])) {
            $this->setInvoiceProjectTitle($this->strict($_POST ['invoiceProjectTitle'], 'string'));
        }
        if (isset($_POST ['invoiceProjectStartDate'])) {
            $this->setInvoiceProjectStartDate($this->strict($_POST ['invoiceProjectStartDate'], 'date'));
        }
        if (isset($_POST ['invoiceProjectEndDate'])) {
            $this->setInvoiceProjectEndDate($this->strict($_POST ['invoiceProjectEndDate'], 'date'));
        }
        if (isset($_POST ['invoiceProjectCurrentStage'])) {
            $this->setInvoiceProjectCurrentStage($this->strict($_POST ['invoiceProjectCurrentStage'], 'int'));
        }
        if (isset($_POST ['invoiceProjectTotalStage'])) {
            $this->setInvoiceProjectTotalStage($this->strict($_POST ['invoiceProjectTotalStage'], 'int'));
        }
        if (isset($_POST ['invoiceProjectValue'])) {
            $this->setInvoiceProjectValue($this->strict($_POST ['invoiceProjectValue'], 'double'));
        }
        if (isset($_POST ['invoiceProjectClaim'])) {
            $this->setInvoiceProjectClaim($this->strict($_POST ['invoiceProjectClaim'], 'double'));
        }
        if (isset($_POST ['invoiceProjectBalance'])) {
            $this->setInvoiceProjectBalance($this->strict($_POST ['invoiceProjectBalance'], 'double'));
        }
        if (isset($_POST ['invoiceProjectRetentionValue'])) {
            $this->setInvoiceProjectRetentionValue($this->strict($_POST ['invoiceProjectRetentionValue'], 'double'));
        }
        if (isset($_POST ['invoiceProjectRetentionPercent'])) {
            $this->setInvoiceProjectRetentionPercent(
                    $this->strict($_POST ['invoiceProjectRetentionPercent'], 'double')
            );
        }
        if (isset($_POST ['invoiceProjectDescription'])) {
            $this->setInvoiceProjectDescription($this->strict($_POST ['invoiceProjectDescription'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['invoiceProjectId'])) {
            $this->setInvoiceProjectId($this->strict($_GET ['invoiceProjectId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string'));
        }
        if (isset($_GET ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_GET ['referenceNumber'], 'string'));
        }
        if (isset($_GET ['invoiceProjectTitle'])) {
            $this->setInvoiceProjectTitle($this->strict($_GET ['invoiceProjectTitle'], 'string'));
        }
        if (isset($_GET ['invoiceProjectStartDate'])) {
            $this->setInvoiceProjectStartDate($this->strict($_GET ['invoiceProjectStartDate'], 'date'));
        }
        if (isset($_GET ['invoiceProjectEndDate'])) {
            $this->setInvoiceProjectEndDate($this->strict($_GET ['invoiceProjectEndDate'], 'date'));
        }
        if (isset($_GET ['invoiceProjectCurrentStage'])) {
            $this->setInvoiceProjectCurrentStage($this->strict($_GET ['invoiceProjectCurrentStage'], 'int'));
        }
        if (isset($_GET ['invoiceProjectTotalStage'])) {
            $this->setInvoiceProjectTotalStage($this->strict($_GET ['invoiceProjectTotalStage'], 'int'));
        }
        if (isset($_GET ['invoiceProjectValue'])) {
            $this->setInvoiceProjectValue($this->strict($_GET ['invoiceProjectValue'], 'double'));
        }
        if (isset($_GET ['invoiceProjectClaim'])) {
            $this->setInvoiceProjectClaim($this->strict($_GET ['invoiceProjectClaim'], 'double'));
        }
        if (isset($_GET ['invoiceProjectBalance'])) {
            $this->setInvoiceProjectBalance($this->strict($_GET ['invoiceProjectBalance'], 'double'));
        }
        if (isset($_GET ['invoiceProjectRetentionValue'])) {
            $this->setInvoiceProjectRetentionValue($this->strict($_GET ['invoiceProjectRetentionValue'], 'double'));
        }
        if (isset($_GET ['invoiceProjectRetentionPercent'])) {
            $this->setInvoiceProjectRetentionPercent($this->strict($_GET ['invoiceProjectRetentionPercent'], 'double'));
        }
        if (isset($_GET ['invoiceProjectDescription'])) {
            $this->setInvoiceProjectDescription($this->strict($_GET ['invoiceProjectDescription'], 'string'));
        }
        if (isset($_GET ['invoiceProjectId'])) {
            $this->setTotal(count($_GET ['invoiceProjectId']));
            if (is_array($_GET ['invoiceProjectId'])) {
                $this->invoiceProjectId = array();
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
            if (isset($_GET ['invoiceProjectId'])) {
                $this->setInvoiceProjectId($this->strict($_GET ['invoiceProjectId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getInvoiceProjectId($i, 'array') . ",";
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
    public function getInvoiceProjectId($key, $type) {
        if ($type == 'single') {
            return $this->invoiceProjectId;
        } else if ($type == 'array') {
            return $this->invoiceProjectId [$key];
        } else {
            echo json_encode(
                    array("success" => false, "message" => "Cannot Identify Type String Or Array:getinvoiceProjectId ?")
            );
            exit();
        }
    }

    /**
     * Set Primary Key Value
     * @param int|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return \Core\Financial\AccountReceivable\InvoiceProject\Model\InvoiceProjectModel
     */
    public function setInvoiceProjectId($value, $key, $type) {
        if ($type == 'single') {
            $this->invoiceProjectId = $value;
            return $this;
        } else if ($type == 'array') {
            $this->invoiceProjectId[$key] = $value;
            return $this;
        } else {
            echo json_encode(
                    array("success" => false, "message" => "Cannot Identify Type String Or Array:setinvoiceProjectId?")
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
     * @return \Core\Financial\AccountReceivable\InvoiceProject\Model\InvoiceProjectModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Document Number
     * @return string $documentNumber
     */
    public function getDocumentNumber() {
        return $this->documentNumber;
    }

    /**
     * To Set Document Number
     * @param string $documentNumber Document Number
     * @return \Core\Financial\AccountReceivable\InvoiceProject\Model\InvoiceProjectModel
     */
    public function setDocumentNumber($documentNumber) {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    /**
     * To Return Reference Number
     * @return string $referenceNumber
     */
    public function getReferenceNumber() {
        return $this->referenceNumber;
    }

    /**
     * To Set Reference Number
     * @param string $referenceNumber Reference Number
     * @return \Core\Financial\AccountReceivable\InvoiceProject\Model\InvoiceProjectModel
     */
    public function setReferenceNumber($referenceNumber) {
        $this->referenceNumber = $referenceNumber;
        return $this;
    }

    /**
     * To Return Title
     * @return string $invoiceProjectTitle
     */
    public function getInvoiceProjectTitle() {
        return $this->invoiceProjectTitle;
    }

    /**
     * To Set Title
     * @param string $invoiceProjectTitle Title
     * @return \Core\Financial\AccountReceivable\InvoiceProject\Model\InvoiceProjectModel
     */
    public function setInvoiceProjectTitle($invoiceProjectTitle) {
        $this->invoiceProjectTitle = $invoiceProjectTitle;
        return $this;
    }

    /**
     * To Return Start Date
     * @return date $invoiceProjectStartDate
     */
    public function getInvoiceProjectStartDate() {
        return $this->invoiceProjectStartDate;
    }

    /**
     * To Set Start Date
     * @param date $invoiceProjectStartDate Start Date
     * @return \Core\Financial\AccountReceivable\InvoiceProject\Model\InvoiceProjectModel
     */
    public function setInvoiceProjectStartDate($invoiceProjectStartDate) {
        $this->invoiceProjectStartDate = $invoiceProjectStartDate;
        return $this;
    }

    /**
     * To Return End Date
     * @return date $invoiceProjectEndDate
     */
    public function getInvoiceProjectEndDate() {
        return $this->invoiceProjectEndDate;
    }

    /**
     * To Set End Date
     * @param date $invoiceProjectEndDate End Date
     * @return \Core\Financial\AccountReceivable\InvoiceProject\Model\InvoiceProjectModel
     */
    public function setInvoiceProjectEndDate($invoiceProjectEndDate) {
        $this->invoiceProjectEndDate = $invoiceProjectEndDate;
        return $this;
    }

    /**
     * To Return Current Stage
     * @return int $invoiceProjectCurrentStage
     */
    public function getInvoiceProjectCurrentStage() {
        return $this->invoiceProjectCurrentStage;
    }

    /**
     * To Set Current Stage
     * @param int $invoiceProjectCurrentStage Current Stage
     * @return \Core\Financial\AccountReceivable\InvoiceProject\Model\InvoiceProjectModel
     */
    public function setInvoiceProjectCurrentStage($invoiceProjectCurrentStage) {
        $this->invoiceProjectCurrentStage = $invoiceProjectCurrentStage;
        return $this;
    }

    /**
     * To Return Total Stage
     * @return int $invoiceProjectTotalStage
     */
    public function getInvoiceProjectTotalStage() {
        return $this->invoiceProjectTotalStage;
    }

    /**
     * To Set Total Stage
     * @param int $invoiceProjectTotalStage Total Stage
     * @return \Core\Financial\AccountReceivable\InvoiceProject\Model\InvoiceProjectModel
     */
    public function setInvoiceProjectTotalStage($invoiceProjectTotalStage) {
        $this->invoiceProjectTotalStage = $invoiceProjectTotalStage;
        return $this;
    }

    /**
     * To Return Value
     * @return double $invoiceProjectValue
     */
    public function getInvoiceProjectValue() {
        return $this->invoiceProjectValue;
    }

    /**
     * To Set Value
     * @param double $invoiceProjectValue Value
     * @return \Core\Financial\AccountReceivable\InvoiceProject\Model\InvoiceProjectModel
     */
    public function setInvoiceProjectValue($invoiceProjectValue) {
        $this->invoiceProjectValue = $invoiceProjectValue;
        return $this;
    }

    /**
     * To Return Claim
     * @return double $invoiceProjectClaim
     */
    public function getInvoiceProjectClaim() {
        return $this->invoiceProjectClaim;
    }

    /**
     * To Set Claim
     * @param double $invoiceProjectClaim Claim
     * @return \Core\Financial\AccountReceivable\InvoiceProject\Model\InvoiceProjectModel
     */
    public function setInvoiceProjectClaim($invoiceProjectClaim) {
        $this->invoiceProjectClaim = $invoiceProjectClaim;
        return $this;
    }

    /**
     * To Return Balance
     * @return double $invoiceProjectBalance
     */
    public function getInvoiceProjectBalance() {
        return $this->invoiceProjectBalance;
    }

    /**
     * To Set Balance
     * @param double $invoiceProjectBalance Balance
     * @return \Core\Financial\AccountReceivable\InvoiceProject\Model\InvoiceProjectModel
     */
    public function setInvoiceProjectBalance($invoiceProjectBalance) {
        $this->invoiceProjectBalance = $invoiceProjectBalance;
        return $this;
    }

    /**
     * To Return Retention Value
     * @return double $invoiceProjectRetentionValue
     */
    public function getInvoiceProjectRetentionValue() {
        return $this->invoiceProjectRetentionValue;
    }

    /**
     * To Set Retention Value
     * @param double $invoiceProjectRetentionValue Retention Value
     * @return \Core\Financial\AccountReceivable\InvoiceProject\Model\InvoiceProjectModel
     */
    public function setInvoiceProjectRetentionValue($invoiceProjectRetentionValue) {
        $this->invoiceProjectRetentionValue = $invoiceProjectRetentionValue;
        return $this;
    }

    /**
     * To Return Retention Percent
     * @return double $invoiceProjectRetentionPercent
     */
    public function getInvoiceProjectRetentionPercent() {
        return $this->invoiceProjectRetentionPercent;
    }

    /**
     * To Set Retention Percent
     * @param double $invoiceProjectRetentionPercent Retention Percent
     * @return \Core\Financial\AccountReceivable\InvoiceProject\Model\InvoiceProjectModel
     */
    public function setInvoiceProjectRetentionPercent($invoiceProjectRetentionPercent) {
        $this->invoiceProjectRetentionPercent = $invoiceProjectRetentionPercent;
        return $this;
    }

    /**
     * To Return Description
     * @return string $invoiceProjectDescription
     */
    public function getInvoiceProjectDescription() {
        return $this->invoiceProjectDescription;
    }

    /**
     * To Set Description
     * @param string $invoiceProjectDescription Description
     * @return \Core\Financial\AccountReceivable\InvoiceProject\Model\InvoiceProjectModel
     */
    public function setInvoiceProjectDescription($invoiceProjectDescription) {
        $this->invoiceProjectDescription = $invoiceProjectDescription;
        return $this;
    }

}

?>