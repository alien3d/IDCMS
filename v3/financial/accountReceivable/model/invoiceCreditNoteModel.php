<?php

namespace Core\Financial\AccountReceivable\InvoiceCreditNote\Model;

use Core\Validation\ValidationClass;

$x = addslashes(realpath(__FILE__));
// auto detect if \ consider come from windows else / from linux
$pos = strpos($x, "\\");
if ($pos !== false) {
    $d = explode("\\", $x);
} else {
    $d = explode("/", $x);
}
$newPath = null;
for ($i = 0; $i < count($d); $i ++) {
    // if find the library or package then stop
    if ($d[$i] == 'library' || $d[$i] == 'v3') {
        break;
    }
    $newPath[] .= $d[$i] . "/";
}
$fakeDocumentRoot = null;
for ($z = 0; $z < count($newPath); $z ++) {
    $fakeDocumentRoot .= $newPath[$z];
}
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot); // start
require_once ($newFakeDocumentRoot . "library/class/classValidation.php");

/**
 * Class InvoiceCreditNote
 * This is invoiceCreditNote model file.This is to ensure strict setting enable for all variable enter to database 
 * 
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountReceivable\InvoiceCreditNote\Model;
 * @subpackage AccountReceivable 
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class InvoiceCreditNoteModel extends ValidationClass {

    /**
     * Primary Key
     * @var int 
     */
    private $invoiceCreditNoteId;

    /**
     * Company
     * @var int 
     */
    private $companyId;

    /**
     * Business Partner Category
     * @var int 
     */
    private $businessPartnerCategoryId;

    /**
     * Business Partner
     * @var int 
     */
    private $businessPartnerId;

    /**
     * Invoice
     * @var int 
     */
    private $invoiceId;

    /**
     * Code
     * @var string 
     */
    private $invoiceCreditNoteCode;

    /**
     * Title
     * @var string 
     */
    private $invoiceCreditNoteTitle;

    /**
     * Document Number
     * @var string 
     */
    private $documentNumber;

    /**
     * Amount
     * @var double 
     */
    private $invoiceCreditNoteAmount;

    /**
     * Reference Number
     * @var string 
     */
    private $referenceNumber;

    /**
     * Date
     * @var date 
     */
    private $invoiceCreditNoteDate;

    /**
     * Description
     * @var string 
     */
    private $invoiceCreditNoteDescription;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('invoiceCreditNote');
        $this->setPrimaryKeyName('invoiceCreditNoteId');
        $this->setMasterForeignKeyName('invoiceCreditNoteId');
        $this->setFilterCharacter('invoiceCreditNoteDescription');
        //$this->setFilterCharacter('invoiceCreditNoteNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['invoiceCreditNoteId'])) {
            $this->setInvoiceCreditNoteId($this->strict($_POST ['invoiceCreditNoteId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_POST ['businessPartnerId'], 'int'));
        }
        if (isset($_POST ['invoiceId'])) {
            $this->setInvoiceId($this->strict($_POST ['invoiceId'], 'int'));
        }
        if (isset($_POST ['invoiceCreditNoteCode'])) {
            $this->setInvoiceCreditNoteCode($this->strict($_POST ['invoiceCreditNoteCode'], 'string'));
        }
        if (isset($_POST ['invoiceCreditNoteTitle'])) {
            $this->setInvoiceCreditNoteTitle($this->strict($_POST ['invoiceCreditNoteTitle'], 'string'));
        }
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string'));
        }
        if (isset($_POST ['invoiceCreditNoteAmount'])) {
            $this->setInvoiceCreditNoteAmount($this->strict($_POST ['invoiceCreditNoteAmount'], 'double'));
        }
        if (isset($_POST ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_POST ['referenceNumber'], 'string'));
        }
        if (isset($_POST ['invoiceCreditNoteDate'])) {
            $this->setInvoiceCreditNoteDate($this->strict($_POST ['invoiceCreditNoteDate'], 'date'));
        }
        if (isset($_POST ['invoiceCreditNoteDescription'])) {
            $this->setInvoiceCreditNoteDescription($this->strict($_POST ['invoiceCreditNoteDescription'], 'string'));
        }
        if (isset($_POST ['from'])) {
            $this->setFrom($this->strict($_POST ['from'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['invoiceCreditNoteId'])) {
            $this->setInvoiceCreditNoteId($this->strict($_GET ['invoiceCreditNoteId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['businessPartnerCategoryId'])) {
            $this->setBusinessPartnerCategoryId($this->strict($_GET ['businessPartnerCategoryId'], 'int'));
        }
        if (isset($_GET ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_GET ['businessPartnerId'], 'int'));
        }
        if (isset($_GET ['invoiceId'])) {
            $this->setInvoiceId($this->strict($_GET ['invoiceId'], 'int'));
        }
        if (isset($_GET ['invoiceCreditNoteCode'])) {
            $this->setInvoiceCreditNoteCode($this->strict($_GET ['invoiceCreditNoteCode'], 'string'));
        }
        if (isset($_GET ['invoiceCreditNoteTitle'])) {
            $this->setInvoiceCreditNoteTitle($this->strict($_GET ['invoiceCreditNoteTitle'], 'string'));
        }
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string'));
        }
        if (isset($_GET ['invoiceCreditNoteAmount'])) {
            $this->setInvoiceCreditNoteAmount($this->strict($_GET ['invoiceCreditNoteAmount'], 'double'));
        }
        if (isset($_GET ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_GET ['referenceNumber'], 'string'));
        }
        if (isset($_GET ['invoiceCreditNoteDate'])) {
            $this->setInvoiceCreditNoteDate($this->strict($_GET ['invoiceCreditNoteDate'], 'date'));
        }
        if (isset($_GET ['invoiceCreditNoteDescription'])) {
            $this->setInvoiceCreditNoteDescription($this->strict($_GET ['invoiceCreditNoteDescription'], 'string'));
        }
        if (isset($_GET ['from'])) {
            $this->setFrom($this->strict($_GET ['from'], 'string'));
        }
        if (isset($_GET ['invoiceCreditNoteId'])) {
            $this->setTotal(count($_GET ['invoiceCreditNoteId']));
            if (is_array($_GET ['invoiceCreditNoteId'])) {
                $this->invoiceCreditNoteId = array();
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
            if (isset($_GET ['invoiceCreditNoteId'])) {
                $this->setInvoiceCreditNoteId($this->strict($_GET ['invoiceCreditNoteId'] [$i], 'numeric'), $i, 'array');
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
                } if ($_GET ['isUpdate'] [$i] == 'false') {
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
            $primaryKeyAll .= $this->getInvoiceCreditNoteId($i, 'array') . ",";
        }
        $this->setPrimaryKeyAll((substr($primaryKeyAll, 0, - 1)));
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
     * @param array[int]int $key List Of Primary Key. 
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array' 
     * @return \Core\Financial\AccountReceivable\InvoiceCreditNote\Model\InvoiceCreditNoteModel
     */
    public function setInvoiceCreditNoteId($value, $key, $type) {
        if ($type == 'single') {
            $this->invoiceCreditNoteId = $value;
            return $this;
        } else if ($type == 'array') {
            $this->invoiceCreditNoteId[$key] = $value;
            return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:setinvoiceCreditNoteId?"));
            exit();
        }
    }

    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getInvoiceCreditNoteId($key, $type) {
        if ($type == 'single') {
            return $this->invoiceCreditNoteId;
        } else if ($type == 'array') {
            return $this->invoiceCreditNoteId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:getinvoiceCreditNoteId ?"));
            exit();
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
     * @return \Core\Financial\AccountReceivable\InvoiceCreditNote\Model\InvoiceCreditNoteModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Business Partner Category
     * @return int $businessPartnerId
     */
    public function getBusinessPartnerCategoryId() {
        return $this->businessPartnerCategoryId;
    }

    /**
     * To Set Business Partner Category
     * @param int $businessPartnerCategoryId Business Partner Category
     * @return \Core\Financial\AccountReceivable\InvoiceCreditNote\Model\InvoiceCreditNoteModel
     */
    public function setBusinessPartnerCategoryId($businessPartnerCategoryId) {
        $this->businessPartnerCategoryId = $businessPartnerCategoryId;
        return $this;
    }

    /**
     * To Return Business Partner 
     * @return int $businessPartnerId
     */
    public function getBusinessPartnerId() {
        return $this->businessPartnerId;
    }

    /**
     * To Set Business Partner 
     * @param int $businessPartnerId Business Partner 
     * @return \Core\Financial\AccountReceivable\InvoiceCreditNote\Model\InvoiceCreditNoteModel
     */
    public function setBusinessPartnerId($businessPartnerId) {
        $this->businessPartnerId = $businessPartnerId;
        return $this;
    }

    /**
     * To Return Invoice 
     * @return int $invoiceId
     */
    public function getInvoiceId() {
        return $this->invoiceId;
    }

    /**
     * To Set Invoice 
     * @param int $invoiceId Invoice 
     * @return \Core\Financial\AccountReceivable\InvoiceCreditNote\Model\InvoiceCreditNoteModel
     */
    public function setInvoiceId($invoiceId) {
        $this->invoiceId = $invoiceId;
        return $this;
    }

    /**
     * To Return Code
     * @return string $invoiceCreditNoteCode
     */
    public function getInvoiceCreditNoteCode() {
        return $this->invoiceCreditNoteCode;
    }

    /**
     * To Set Code 
     * @param int $invoiceCreditNoteCode Code
     * @return \Core\Financial\AccountReceivable\InvoiceCreditNote\Model\InvoiceCreditNoteModel
     */
    public function setInvoiceCreditNoteCode($invoiceCreditNoteCode) {
        $this->invoiceCreditNoteCode = $invoiceCreditNoteCode;
        return $this;
    }

    /**
     * To Return Title 
     * @return string $invoiceCreditNoteTitle
     */
    public function getInvoiceCreditNoteTitle() {
        return $this->invoiceCreditNoteTitle;
    }

    /**
     * To Set Title 
     * @param string $invoiceCreditNoteTitle Title 
     * @return \Core\Financial\AccountReceivable\InvoiceCreditNote\Model\InvoiceCreditNoteModel
     */
    public function setInvoiceCreditNoteTitle($invoiceCreditNoteTitle) {
        $this->invoiceCreditNoteTitle = $invoiceCreditNoteTitle;
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
     * @return \Core\Financial\AccountReceivable\InvoiceCreditNote\Model\InvoiceCreditNoteModel
     */
    public function setDocumentNumber($documentNumber) {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    /**
     * To Return Amount 
     * @return double $invoiceCreditNoteAmount
     */
    public function getInvoiceCreditNoteAmount() {
        return $this->invoiceCreditNoteAmount;
    }

    /**
     * To Set Amount 
     * @param double $invoiceCreditNoteAmount Amount 
     * @return \Core\Financial\AccountReceivable\InvoiceCreditNote\Model\InvoiceCreditNoteModel
     */
    public function setInvoiceCreditNoteAmount($invoiceCreditNoteAmount) {
        $this->invoiceCreditNoteAmount = $invoiceCreditNoteAmount;
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
     * @return \Core\Financial\AccountReceivable\InvoiceCreditNote\Model\InvoiceCreditNoteModel
     */
    public function setReferenceNumber($referenceNumber) {
        $this->referenceNumber = $referenceNumber;
        return $this;
    }

    /**
     * To Return Date 
     * @return date $invoiceCreditNoteDate
     */
    public function getInvoiceCreditNoteDate() {
        return $this->invoiceCreditNoteDate;
    }

    /**
     * To Set Date 
     * @param date $invoiceCreditNoteDate Date 
     * @return \Core\Financial\AccountReceivable\InvoiceCreditNote\Model\InvoiceCreditNoteModel
     */
    public function setInvoiceCreditNoteDate($invoiceCreditNoteDate) {
        $this->invoiceCreditNoteDate = $invoiceCreditNoteDate;
        return $this;
    }

    /**
     * To Return Description 
     * @return string $invoiceCreditNoteDescription
     */
    public function getInvoiceCreditNoteDescription() {
        return $this->invoiceCreditNoteDescription;
    }

    /**
     * To Set Description 
     * @param string $invoiceCreditNoteDescription Description 
     * @return \Core\Financial\AccountReceivable\InvoiceCreditNote\Model\InvoiceCreditNoteModel
     */
    public function setInvoiceCreditNoteDescription($invoiceCreditNoteDescription) {
        $this->invoiceCreditNoteDescription = $invoiceCreditNoteDescription;
        return $this;
    }

}

?>