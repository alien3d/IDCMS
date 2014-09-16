<?php

namespace Core\Financial\AccountReceivable\InvoiceRecurring\Model;

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
 * Class InvoiceRecurring
 * This is invoiceRecurring model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountReceivable\InvoiceRecurring\Model;
 * @subpackage AccountReceivable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class InvoiceRecurringModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $invoiceRecurringId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Type
     * @var int
     */
    private $invoiceRecurringTypeId;

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
     * Journal Title
     * @var string
     */
    private $journalRecurringTitle;

    /**
     * Description
     * @var string
     */
    private $invoiceRecurringDescription;

    /**
     * Date
     * @var string
     */
    private $invoiceRecurringDate;

    /**
     * Start Date
     * @var string
     */
    private $invoiceRecurringStartDate;

    /**
     * End Date
     * @var string
     */
    private $invoiceRecurringEndDate;

    /**
     * Amount
     * @var double
     */
    private $invoiceRecurringAmount;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('invoiceRecurring');
        $this->setPrimaryKeyName('invoiceRecurringId');
        $this->setMasterForeignKeyName('invoiceRecurringId');
        $this->setFilterCharacter('invoiceRecurringDescription');
        //$this->setFilterCharacter('invoiceRecurringNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['invoiceRecurringId'])) {
            $this->setInvoiceRecurringId($this->strict($_POST ['invoiceRecurringId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['invoiceRecurringTypeId'])) {
            $this->setInvoiceRecurringTypeId($this->strict($_POST ['invoiceRecurringTypeId'], 'integer'));
        }
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string'));
        }
        if (isset($_POST ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_POST ['referenceNumber'], 'string'));
        }
        if (isset($_POST ['journalRecurringTitle'])) {
            $this->setJournalRecurringTitle($this->strict($_POST ['journalRecurringTitle'], 'string'));
        }
        if (isset($_POST ['invoiceRecurringDescription'])) {
            $this->setInvoiceRecurringDescription($this->strict($_POST ['invoiceRecurringDescription'], 'string'));
        }
        if (isset($_POST ['invoiceRecurringDate'])) {
            $this->setInvoiceRecurringDate($this->strict($_POST ['invoiceRecurringDate'], 'date'));
        }
        if (isset($_POST ['invoiceRecurringStartDate'])) {
            $this->setInvoiceRecurringStartDate($this->strict($_POST ['invoiceRecurringStartDate'], 'date'));
        }
        if (isset($_POST ['invoiceRecurringEndDate'])) {
            $this->setInvoiceRecurringEndDate($this->strict($_POST ['invoiceRecurringEndDate'], 'date'));
        }
        if (isset($_POST ['invoiceRecurringAmount'])) {
            $this->setInvoiceRecurringAmount($this->strict($_POST ['invoiceRecurringAmount'], 'double'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['invoiceRecurringId'])) {
            $this->setInvoiceRecurringId($this->strict($_GET ['invoiceRecurringId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['invoiceRecurringTypeId'])) {
            $this->setInvoiceRecurringTypeId($this->strict($_GET ['invoiceRecurringTypeId'], 'integer'));
        }
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string'));
        }
        if (isset($_GET ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_GET ['referenceNumber'], 'string'));
        }
        if (isset($_GET ['journalRecurringTitle'])) {
            $this->setJournalRecurringTitle($this->strict($_GET ['journalRecurringTitle'], 'string'));
        }
        if (isset($_GET ['invoiceRecurringDescription'])) {
            $this->setInvoiceRecurringDescription($this->strict($_GET ['invoiceRecurringDescription'], 'string'));
        }
        if (isset($_GET ['invoiceRecurringDate'])) {
            $this->setInvoiceRecurringDate($this->strict($_GET ['invoiceRecurringDate'], 'date'));
        }
        if (isset($_GET ['invoiceRecurringStartDate'])) {
            $this->setInvoiceRecurringStartDate($this->strict($_GET ['invoiceRecurringStartDate'], 'date'));
        }
        if (isset($_GET ['invoiceRecurringEndDate'])) {
            $this->setInvoiceRecurringEndDate($this->strict($_GET ['invoiceRecurringEndDate'], 'date'));
        }
        if (isset($_GET ['invoiceRecurringAmount'])) {
            $this->setInvoiceRecurringAmount($this->strict($_GET ['invoiceRecurringAmount'], 'double'));
        }
        if (isset($_GET ['invoiceRecurringId'])) {
            $this->setTotal(count($_GET ['invoiceRecurringId']));
            if (is_array($_GET ['invoiceRecurringId'])) {
                $this->invoiceRecurringId = array();
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
            if (isset($_GET ['invoiceRecurringId'])) {
                $this->setInvoiceRecurringId($this->strict($_GET ['invoiceRecurringId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getInvoiceRecurringId($i, 'array') . ",";
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
    public function getInvoiceRecurringId($key, $type) {
        if ($type == 'single') {
            return $this->invoiceRecurringId;
        } else {
            if ($type == 'array') {
                return $this->invoiceRecurringId [$key];
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:getinvoiceRecurringId ?"
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
     * @return \Core\Financial\AccountReceivable\InvoiceRecurring\Model\InvoiceRecurringModel
     */
    public function setInvoiceRecurringId($value, $key, $type) {
        if ($type == 'single') {
            $this->invoiceRecurringId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->invoiceRecurringId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:setinvoiceRecurringId?"
                        )
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
     * @return \Core\Financial\AccountReceivable\InvoiceRecurring\Model\InvoiceRecurringModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Type
     * @return int $invoiceRecurringTypeId
     */
    public function getInvoiceRecurringTypeId() {
        return $this->invoiceRecurringTypeId;
    }

    /**
     * To Set Type
     * @param int $invoiceRecurringTypeId Type
     * @return \Core\Financial\AccountReceivable\InvoiceRecurring\Model\InvoiceRecurringModel
     */
    public function setInvoiceRecurringTypeId($invoiceRecurringTypeId) {
        $this->invoiceRecurringTypeId = $invoiceRecurringTypeId;
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
     * @return \Core\Financial\AccountReceivable\InvoiceRecurring\Model\InvoiceRecurringModel
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
     * @return \Core\Financial\AccountReceivable\InvoiceRecurring\Model\InvoiceRecurringModel
     */
    public function setReferenceNumber($referenceNumber) {
        $this->referenceNumber = $referenceNumber;
        return $this;
    }

    /**
     * To Return Title
     * @return string $journalRecurringTitle
     */
    public function getJournalRecurringTitle() {
        return $this->journalRecurringTitle;
    }

    /**
     * To Set Title
     * @param string $journalRecurringTitle Journal Title
     * @return \Core\Financial\AccountReceivable\InvoiceRecurring\Model\InvoiceRecurringModel
     */
    public function setJournalRecurringTitle($journalRecurringTitle) {
        $this->journalRecurringTitle = $journalRecurringTitle;
        return $this;
    }

    /**
     * To Return Description
     * @return string $invoiceRecurringDescription
     */
    public function getInvoiceRecurringDescription() {
        return $this->invoiceRecurringDescription;
    }

    /**
     * To Set Description
     * @param string $invoiceRecurringDescription Description
     * @return \Core\Financial\AccountReceivable\InvoiceRecurring\Model\InvoiceRecurringModel
     */
    public function setInvoiceRecurringDescription($invoiceRecurringDescription) {
        $this->invoiceRecurringDescription = $invoiceRecurringDescription;
        return $this;
    }

    /**
     * To Return Date
     * @return string $invoiceRecurringDate
     */
    public function getInvoiceRecurringDate() {
        return $this->invoiceRecurringDate;
    }

    /**
     * To Set Date
     * @param string $invoiceRecurringDate Date
     * @return \Core\Financial\AccountReceivable\InvoiceRecurring\Model\InvoiceRecurringModel
     */
    public function setInvoiceRecurringDate($invoiceRecurringDate) {
        $this->invoiceRecurringDate = $invoiceRecurringDate;
        return $this;
    }

    /**
     * To Return Start Date
     * @return string $invoiceRecurringStartDate
     */
    public function getInvoiceRecurringStartDate() {
        return $this->invoiceRecurringStartDate;
    }

    /**
     * To Set Start Date
     * @param string $invoiceRecurringStartDate Start Date
     * @return \Core\Financial\AccountReceivable\InvoiceRecurring\Model\InvoiceRecurringModel
     */
    public function setInvoiceRecurringStartDate($invoiceRecurringStartDate) {
        $this->invoiceRecurringStartDate = $invoiceRecurringStartDate;
        return $this;
    }

    /**
     * To Return End Date
     * @return string $invoiceRecurringEndDate
     */
    public function getInvoiceRecurringEndDate() {
        return $this->invoiceRecurringEndDate;
    }

    /**
     * To Set End Date
     * @param string $invoiceRecurringEndDate End Date
     * @return \Core\Financial\AccountReceivable\InvoiceRecurring\Model\InvoiceRecurringModel
     */
    public function setInvoiceRecurringEndDate($invoiceRecurringEndDate) {
        $this->invoiceRecurringEndDate = $invoiceRecurringEndDate;
        return $this;
    }

    /**
     * To Return Amount
     * @return double $invoiceRecurringAmount
     */
    public function getInvoiceRecurringAmount() {
        return $this->invoiceRecurringAmount;
    }

    /**
     * To Set Amount
     * @param double $invoiceRecurringAmount Amount
     * @return \Core\Financial\AccountReceivable\InvoiceRecurring\Model\InvoiceRecurringModel
     */
    public function setInvoiceRecurringAmount($invoiceRecurringAmount) {
        $this->invoiceRecurringAmount = $invoiceRecurringAmount;
        return $this;
    }

}

?>