<?php

namespace Core\Financial\AccountPayable\PurchaseInvoice\Model;

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
 * Class PurchaseInvoice
 * This is Purchase Invoice model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountPayable\PurchaseInvoice\Model;
 * @subpackage AccountPayable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class PurchaseInvoiceModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $purchaseInvoiceId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Business Partner
     * @var int
     */
    private $businessPartnerId;
	
    /**
     * Business Address
     * @var string
     */
    private $businessPartnerCompany;

    /**
     * Business Address
     * @var string
     */
    private $businessPartnerAddress;

    /**
     * Business Contact
     * @var string
     */
    private $businessPartnerContactId;

    /**
     * Business Contact
     * @var string
     */
    private $businessPartnerContactName;

    /**
     * Business Contact
     * @var string
     */
    private $businessPartnerContactPhone;

    /**
     * Business Contact
     * @var string
     */
    private $businessPartnerContactEmail;

    /**
     * Project
     * @var int
     */
    private $purchaseInvoiceProjectId;

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
     * Amount
     * @var double
     */
    private $purchaseInvoiceAmount;

    /**
     * Amount Text
     * @var string
     */
    private $purchaseInvoiceAmountText;

    /**
     * Date
     * @var string
     */
    private $purchaseInvoiceDate;

    /**
     * Reminder Date. Calculate Date Based On Credit Term.
     * @var string
     */
    private $purchaseInvoiceReminderDate;

    /**
     * Credit Term
     * @var double
     */
    private $purchaseInvoiceCreditTerm;

    /**
     * Description
     * @var string
     */
    private $purchaseInvoiceDescription;

    /**
     * Is Allocated
     * @var bool
     */
    private $isAllocated;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('purchaseInvoice');
        $this->setPrimaryKeyName('purchaseInvoiceId');
        $this->setMasterForeignKeyName('purchaseInvoiceId');
        $this->setFilterCharacter('purchaseInvoiceDescription');
        //$this->setFilterCharacter('purchaseInvoiceNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['purchaseInvoiceId'])) {
            $this->setPurchaseInvoiceId($this->strict($_POST ['purchaseInvoiceId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_POST ['businessPartnerId'], 'int'));
        }
        if (isset($_POST ['businessPartnerContactId'])) {
            $this->setBusinessPartnerContactId($this->strict($_POST ['businessPartnerContactId'], 'integer'));
        }
        if (isset($_POST ['businessPartnerCompany'])) {
            $this->setBusinessPartnerCompany($this->strict($_POST ['businessPartnerCompany'], 'string'));
        }
        if (isset($_POST ['businessPartnerAddress'])) {
            $this->setBusinessPartnerAddress($this->strict($_POST ['businessPartnerAddress'], 'string'));
        }
        if (isset($_POST['businessPartnerContactName'])) {
            $this->setBusinessPartnerContactName($this->strict($_POST ['businessPartnerContactName'], 'string'));
        }
        if (isset($_POST ['businessPartnerContactPhone'])) {
            $this->setBusinessPartnerContactPhone($this->strict($_POST ['businessPartnerContactPhone'], 'string'));
        }
         if (isset($_POST ['businessPartnerContactEmail'])) {
            $this->setBusinessPartnerContactEmail($this->strict($_POST['businessPartnerContactEmail'], 'string'));
        }   
        if (isset($_POST ['purchaseInvoiceProjectId'])) {
            $this->setPurchaseInvoiceProjectId($this->strict($_POST ['purchaseInvoiceProjectId'], 'int'));
        }
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string'));
        }
        if (isset($_POST ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_POST ['referenceNumber'], 'string'));
        }
        if (isset($_POST ['purchaseInvoiceAmount'])) {
            $this->setPurchaseInvoiceAmount($this->strict($_POST ['purchaseInvoiceAmount'], 'double'));
        }
        if (isset($_POST ['purchaseInvoiceAmountText'])) {
            $this->setPurchaseInvoiceAmountText($this->strict($_POST ['purchaseInvoiceAmountText'], 'string'));
        }
        if (isset($_POST ['purchaseInvoiceDate'])) {
            $this->setPurchaseInvoiceDate($this->strict($_POST ['purchaseInvoiceDate'], 'date'));
        }
        if (isset($_POST ['purchaseInvoiceReminderDate'])) {
            $this->setPurchaseInvoiceReminderDate($this->strict($_POST ['purchaseInvoiceReminderDate'], 'date'));
        }
        if (isset($_POST ['purchaseInvoiceCreditTerm'])) {
            $this->setPurchaseInvoiceCreditTerm($this->strict($_POST ['purchaseInvoiceCreditTerm'], 'double'));
        }
        if (isset($_POST ['purchaseInvoiceDescription'])) {
            $this->setPurchaseInvoiceDescription($this->strict($_POST ['purchaseInvoiceDescription'], 'string'));
        }
        if (isset($_POST ['isAllocated'])) {
            $this->setIsAllocated($this->strict($_POST ['isAllocated'], 'bool'));
        }
        if (isset($_POST ['from'])) {
            $this->setFrom($this->strict($_POST ['from'], 'string'));
        }
		
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['purchaseInvoiceId'])) {
            $this->setPurchaseInvoiceId($this->strict($_GET ['purchaseInvoiceId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_GET ['businessPartnerId'], 'integer'));
        }
		

        if (isset($_GET ['businessPartnerCompany'])) {
            $this->setBusinessPartnerCompany($this->strict($_GET ['businessPartnerCompany'], 'string'));
        }
        if (isset($_GET ['businessPartnerAddress'])) {
            $this->setBusinessPartnerAddress($this->strict($_GET ['businessPartnerAddress'], 'string'));
        }
        if (isset($_GET ['businessPartnerContactId'])) {
            $this->setBusinessPartnerContactId($this->strict($_GET ['businessPartnerContactId'], 'integer'));
        }
        if (isset($_GET ['businessPartnerAddress'])) {
            $this->setBusinessPartnerAddress($this->strict($_GET ['businessPartnerAddress'], 'string'));
        }
        if (isset($_GET ['businessPartnerContactName'])) {
            $this->setBusinessPartnerContactName($this->strict($_GET ['businessPartnerContactName'], 'string'));
        }
         if (isset($_GET ['businessPartnerContactPhone'])) {
            $this->setBusinessPartnerContactPhone($this->strict($_GET ['businessPartnerContactPhone'], 'string'));
        }
        if (isset($_GET ['businessPartnerContactEmail'])) {
            $this->setBusinessPartnerContactEmail($this->strict($_GET ['businessPartnerContactEmail'], 'string'));
        } 
        if (isset($_GET ['purchaseInvoiceProjectId'])) {
            $this->setPurchaseInvoiceProjectId($this->strict($_GET ['purchaseInvoiceProjectId'], 'integer'));
        }
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string'));
        }
        if (isset($_GET ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_GET ['referenceNumber'], 'string'));
        }
        if (isset($_GET ['purchaseInvoiceAmount'])) {
            $this->setPurchaseInvoiceAmount($this->strict($_GET ['purchaseInvoiceAmount'], 'double'));
        }
        if (isset($_GET ['purchaseInvoiceAmountText'])) {
            $this->setPurchaseInvoiceAmountText($this->strict($_GET ['purchaseInvoiceAmountText'], 'string'));
        }
        if (isset($_GET ['purchaseInvoiceDate'])) {
            $this->setPurchaseInvoiceDate($this->strict($_GET ['purchaseInvoiceDate'], 'date'));
        }
        if (isset($_GET ['purchaseInvoiceReminderDate'])) {
            $this->setPurchaseInvoiceReminderDate($this->strict($_GET ['purchaseInvoiceReminderDate'], 'date'));
        }
        if (isset($_GET ['purchaseInvoiceCreditTerm'])) {
            $this->setPurchaseInvoiceCreditTerm($this->strict($_GET ['purchaseInvoiceCreditTerm'], 'double'));
        }
        if (isset($_GET ['purchaseInvoiceDescription'])) {
            $this->setPurchaseInvoiceDescription($this->strict($_GET ['purchaseInvoiceDescription'], 'string'));
        }
        if (isset($_GET ['isAllocated'])) {
            $this->setIsAllocated($this->strict($_GET ['isAllocated'], 'bool'));
        }
        if (isset($_GET ['from'])) {
            $this->setFrom($this->strict($_GET ['from'], 'string'));
        }
        if (isset($_GET ['purchaseInvoiceId'])) {
            $this->setTotal(count($_GET ['purchaseInvoiceId']));
            if (is_array($_GET ['purchaseInvoiceId'])) {
                $this->purchaseInvoiceId = array();
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
            if (isset($_GET ['purchaseInvoiceId'])) {
                $this->setPurchaseInvoiceId($this->strict($_GET ['purchaseInvoiceId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getPurchaseInvoiceId($i, 'array') . ",";
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
                    $this->setExecuteTime("to_date('" . date("Y-m-d H:i:s") . "','YYYY-MM-DD HH24:MI:SS');");
                }
            }
        

    /**
     * Return Primary Key Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getPurchaseInvoiceId($key, $type) {
        if ($type == 'single') {
            return $this->purchaseInvoiceId;
        } else {
            if ($type == 'array') {
                return $this->purchaseInvoiceId [$key];
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:getPurchaseInvoiceId ?"
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
     * @return \Core\Financial\AccountPayable\PurchaseInvoice\Model\PurchaseInvoiceModel
     */
    public function setPurchaseInvoiceId($value, $key, $type) {
        if ($type == 'single') {
            $this->purchaseInvoiceId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->purchaseInvoiceId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setPurchaseInvoiceId?")
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
     * @return \Core\Financial\AccountPayable\PurchaseInvoice\Model\PurchaseInvoiceModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
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
     * @return \Core\Financial\AccountPayable\PurchaseInvoice\Model\PurchaseInvoiceModel
     */
    public function setBusinessPartnerId($businessPartnerId) {
        $this->businessPartnerId = $businessPartnerId;
        return $this;
    }

    /**
     * To Return Project
     * @return int $purchaseInvoiceProjectId
     */
    public function getPurchaseInvoiceProjectId() {
        return $this->purchaseInvoiceProjectId;
    }

    /**
     * To Set Project
     * @param int $purchaseInvoiceProjectId Project
     * @return \Core\Financial\AccountPayable\PurchaseInvoice\Model\PurchaseInvoiceModel
     */
    public function setPurchaseInvoiceProjectId($purchaseInvoiceProjectId) {
        $this->purchaseInvoiceProjectId = $purchaseInvoiceProjectId;
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
     * @return \Core\Financial\AccountPayable\PurchaseInvoice\Model\PurchaseInvoiceModel
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
     * @return \Core\Financial\AccountPayable\PurchaseInvoice\Model\PurchaseInvoiceModel
     */
    public function setReferenceNumber($referenceNumber) {
        $this->referenceNumber = $referenceNumber;
        return $this;
    }

    /**
     * To Return Amount
     * @return double $purchaseInvoiceAmount
     */
    public function getPurchaseInvoiceAmount() {
        return $this->purchaseInvoiceAmount;
    }

    /**
     * To Set Amount
     * @param double $purchaseInvoiceAmount Amount
     * @return \Core\Financial\AccountPayable\PurchaseInvoice\Model\PurchaseInvoiceModel
     */
    public function setPurchaseInvoiceAmount($purchaseInvoiceAmount) {
        $this->purchaseInvoiceAmount = $purchaseInvoiceAmount;
        return $this;
    }

    /**
     * To Return Amount Text 
     * @return string $purchaseInvoiceAmountText 
     */
    public function getPurchaseInvoiceAmountText() {
        return $this->purchaseInvoiceAmountText;
    }

    /**
     * To Set Amount Text
     * @param string $purchaseInvoiceAmountText Amount Text
     * @return \Core\Financial\AccountPayable\PurchaseInvoice\Model\PurchaseInvoiceModel
     */
    public function setPurchaseInvoiceAmountText($purchaseInvoiceAmountText) {
        $this->purchaseInvoiceAmountText = $purchaseInvoiceAmountText;
        return $this;
    }

    /**
     * To Return Date
     * @return string $purchaseInvoiceDate
     */
    public function getPurchaseInvoiceDate() {
        return $this->purchaseInvoiceDate;
    }

    /**
     * To Set Date
     * @param string $purchaseInvoiceDate Date
     * @return \Core\Financial\AccountPayable\PurchaseInvoice\Model\PurchaseInvoiceModel
     */
    public function setPurchaseInvoiceDate($purchaseInvoiceDate) {
        $this->purchaseInvoiceDate = $purchaseInvoiceDate;
        return $this;
    }

    /**
     * To Return Reminder Date
     * @return string $purchaseInvoiceDate
     */
    public function getPurchaseInvoiceReminderDate() {
        return $this->purchaseInvoiceReminderDate;
    }

    /**
     * To Set Reminder Date
     * @param string $purchaseInvoiceReminderDate Date
     * @return \Core\Financial\AccountPayable\PurchaseInvoice\Model\PurchaseInvoiceModel
     */
    public function setPurchaseInvoiceReminderDate($purchaseInvoiceReminderDate) {
        $this->purchaseInvoiceReminderDate = $purchaseInvoiceReminderDate;
        return $this;
    }

    /**
     * To Return Credit Term
     * @return double $purchaseInvoiceCreditTerm
     */
    public function getPurchaseInvoiceCreditTerm() {
        return $this->purchaseInvoiceCreditTerm;
    }

    /**
     * To Set Credit Term
     * @param double $purchaseInvoiceCreditTerm Credit Term
     * @return \Core\Financial\AccountPayable\PurchaseInvoice\Model\PurchaseInvoiceModel
     */
    public function setPurchaseInvoiceCreditTerm($purchaseInvoiceCreditTerm) {
        $this->purchaseInvoiceCreditTerm = $purchaseInvoiceCreditTerm;
        return $this;
    }

    /**
     * To Return Description
     * @return string $purchaseInvoiceDescription
     */
    public function getPurchaseInvoiceDescription() {
        return $this->purchaseInvoiceDescription;
    }

    /**
     * To Set Description
     * @param string $purchaseInvoiceDescription Description
     * @return \Core\Financial\AccountPayable\PurchaseInvoice\Model\PurchaseInvoiceModel
     */
    public function setPurchaseInvoiceDescription($purchaseInvoiceDescription) {
        $this->purchaseInvoiceDescription = $purchaseInvoiceDescription;
        return $this;
    }

    /**
     * To Return Is Allocated
     * @return bool $isAllocated
     */
    public function getIsAllocated() {
        return $this->isAllocated;
    }

    /**
     * To Set Is Allocated
     * @param bool $isAllocated Is Allocated
     * @return \Core\Financial\AccountPayable\PurchaseInvoice\Model\PurchaseInvoiceModel
     */
    public function setIsAllocated($isAllocated) {
        $this->isAllocated = $isAllocated;
        return $this;
    }
	
	
    /**
     * Set Business Partner Contact Email
     * @param string $businessPartnerContactEmail Email
     * @return \Core\Financial\AccountPayable\PurchaseInvoice\Model\PurchaseInvoiceModel
     */
    public function setBusinessPartnerContactEmail($businessPartnerContactEmail) {
        $this->businessPartnerContactEmail = $businessPartnerContactEmail;
        return $this;
    }

    /**
     * Retrun Business Partner contact Name
     * @return string
     */
    public function getBusinessPartnerContactName() {
        return $this->businessPartnerContactName;
    }

    /**
     * Set Business Partner Contact Name
     * @param string $businessPartnerContactName Contact Name
      * @return \Core\Financial\AccountPayable\PurchaseInvoice\Model\PurchaseInvoiceModel
     */
    public function setBusinessPartnerContactName($businessPartnerContactName) {
        $this->businessPartnerContactName = $businessPartnerContactName;
        return $this;
    }

    /**
     * To Return Business Partner Address
     * @return string $businessPartnerAddress Address
     */
    public function getBusinessPartnerAddress() {
        return $this->businessPartnerAddress;
    }

    /**
     * To Set Business Partner Address
     * @param string $businessPartnerAddress Business Address
     * @return \Core\Financial\AccountPayable\PurchaseInvoice\Model\PurchaseInvoiceModel
     */
    public function setBusinessPartnerAddress($businessPartnerAddress) {
        $this->businessPartnerAddress = $businessPartnerAddress;
        return $this;
    }

    /**
     * Return Business Partner Contact Phone
     * @return string
     */
    public function getBusinessPartnerContactPhone() {
        return $this->businessPartnerContactPhone;
    }

    /**
     * Set Business Partner Contact Phone
     * @param int $businessPartnerContactPhone
      * @return \Core\Financial\AccountPayable\PurchaseInvoice\Model\PurchaseInvoiceModel
     */
    public function setBusinessPartnerContactPhone($businessPartnerContactPhone) {
        $this->businessPartnerContactPhone = $businessPartnerContactPhone;
        return $this;
    }

    /**
     * Return Business Partner Company
     * @return string
     */
    public function getBusinessPartnerCompany() {
        return $this->businessPartnerCompany;
    }

    /**
     * Set Business Partner Company
     * @param string $businessPartnerCompany Company/Name
     * @return $this
     */
    public function setBusinessPartnerCompany($businessPartnerCompany) {
        $this->businessPartnerCompany = $businessPartnerCompany;
        return $this;
    }

    /**
     * To Return Business Partner Contact
     * @return int $businessPartnerContactId
     */
    public function getBusinessPartnerContactId() {
        return $this->businessPartnerContactId;
    }

    /**
     * To Set Business Partner Contact
     * @param int $businessPartnerContactId Business Contact
     * @return \Core\Financial\AccountPayable\PurchaseInvoice\Model\PurchaseInvoiceModel
     */
    public function setBusinessPartnerContactId($businessPartnerContactId) {
        $this->businessPartnerContactId = $businessPartnerContactId;
        return $this;
    }

}

?>