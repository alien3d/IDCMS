<?php

namespace Core\Financial\AccountReceivable\Invoice\Model;

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
 * Class Invoice
 * This is invoice model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountReceivable\Invoice\Model;
 * @subpackage AccountReceivable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class InvoiceModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $invoiceId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Invoice Quotation
     * @var int
     */
    private $invoiceQuotationId;

    /**
     * Category
     * @var int
     */
    private $invoiceCategoryId;

    /**
     * Type
     * @var int
     */
    private $invoiceTypeId;

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
     * @var int
     */
    private $businessPartnerContactId;

    /**
     * Business Contact
     * @var int
     */
    private $businessPartnerContactName;

    /**
     * Business Contact
     * @var int
     */
    private $businessPartnerContactPhone;

    /**
     * Business Contact
     * @var int
     */
    private $businessPartnerContactEmail;

    /**
     * Country
     * @var int
     */
    private $countryId;

    /**
     * Country E
     * @var int
     */
    private $countryCurrencyTo;

    /**
     * Project
     * @var int
     */
    private $invoiceProjectId;

    /**
     * Payment Terms
     * @var int
     */
    private $paymentTermId;

    /**
     * Warehouse
     * @var int
     */
    private $warehouseId;

    /**
     * Process
     * @var int
     */
    private $invoiceProcessId;

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
     * Quotation Number
     * @var string
     */
    private $invoiceQuotationNumber;

    /**
     * Purchase Number
     * @var string
     */
    private $purchaseOrderNumber;

    /**
     * Total Amount
     * @var double
     */
    private $invoiceTotalAmount;

    /**
     * Amount Text
     * @var double
     */
    private $invoiceAmountText;

    /**
     * Tax Amount
     * @var double
     */
    private $invoiceTaxAmount;

    /**
     * Discount Amount
     * @var double
     */
    private $invoiceDiscountAmount;

    /**
     * Shipping Amount
     * @var double
     */
    private $invoiceShippingAmount;

    /**
     * Interest Rate ->Loan system
     * @var double
     */
    private $invoiceInterestRate;

    /**
     * Date
     * @var string
     */
    private $invoiceDate;

    /**
     * Start Date -> Loan System,Rental
     * @var string
     */
    private $invoiceStartDate;

    /**
     * End Date -> Loan System,Rental 
     * @var string
     */
    private $invoiceEndDate;

    /**
     * Due Date
     * @var string
     */
    private $invoiceDueDate;

    /**
     * Due Date
     * @var string
     */
    private $invoicePromiseDate;

    /**
     * Duration Month From Start Date and End Date
     * @var string
     */
    private $invoicePeriod;

    /**
     * Description
     * @var string
     */
    private $invoiceDescription;
	
	/**
     * Product
     * @var int 
     */
    private $productId;
	/**
     * Price
     * @var double 
     */
    private $productSellingPricePrice;

    /**
     * Start Date
     * @var date 
     */
    private $productSellingPriceStartDate;

    /**
     * End Date
     * @var date 
     */
    private $productSellingPriceEndDate;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('invoice');
        $this->setPrimaryKeyName('invoiceId');
        $this->setMasterForeignKeyName('invoiceId');
        $this->setFilterCharacter('invoiceDescription');
        //$this->setFilterCharacter('invoiceNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['invoiceId'])) {
            $this->setInvoiceId($this->strict($_POST ['invoiceId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['invoiceCategoryId'])) {
            $this->setInvoiceCategoryId($this->strict($_POST ['invoiceCategoryId'], 'integer'));
        }
        if (isset($_POST ['invoiceTypeId'])) {
            $this->setInvoiceTypeId($this->strict($_POST ['invoiceTypeId'], 'integer'));
        }
        if (isset($_POST ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_POST ['businessPartnerId'], 'integer'));
        }
        if (isset($_POST ['businessPartnerContactId'])) {
            $this->setBusinessPartnerContactId($this->strict($_POST ['businessPartnerContactId'], 'integer'));
        }
        if (isset($_POST ['countryId'])) {
            // some modification
            if (strlen($_POST['countryId']) > 0) {
                $data = explode("|", $_POST['countryId']);
                $this->setCountryId($this->strict($data[0], 'integer'));
            }
        }
        if (isset($_POST ['countryCurrencyTo'])) {
            $this->setCountryCurrencyTo($this->strict($_POST ['countryCurrencyTo'], 'string'));
        }
        if (isset($_POST ['invoiceProjectId'])) {
            $this->setInvoiceProjectId($this->strict($_POST ['invoiceProjectId'], 'integer'));
        }
        if (isset($_POST ['paymentTermId'])) {
            // some modification
            if (strlen($_POST['paymentTermId']) > 0) {
                $data = explode("|", $_POST['paymentTermId']);
                $this->setPaymentTermsId($this->strict($data[0], 'integer'));
            }
        }
        if (isset($_POST ['warehouseId'])) {
            $this->setWarehouseId($this->strict($_POST ['warehouseId'], 'integer'));
        }
        if (isset($_POST ['invoiceQuotationId'])) {
            $this->setInvoiceQuotationId($this->strict($_POST ['invoiceQuotationId'], 'integer'));
        }
        if (isset($_POST ['invoiceProcessId'])) {
            $this->setInvoiceProcessId($this->strict($_POST ['invoiceProcessId'], 'integer'));
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
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string'));
        }
        if (isset($_POST ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_POST ['referenceNumber'], 'string'));
        }
        if (isset($_POST ['invoiceQuotationNumber'])) {
            $this->setInvoiceQuotationNumber($this->strict($_POST ['invoiceQuotationNumber'], 'string'));
        }
        if (isset($_POST ['purchaseOrderNumber'])) {
            $this->setPurchaseOrderNumber($this->strict($_POST ['purchaseOrderNumber'], 'string'));
        }
        if (isset($_POST ['invoiceTotalAmount'])) {
            $this->setInvoiceTotalAmount($this->strict($_POST ['invoiceTotalAmount'], 'double'));
        }
        if (isset($_POST ['invoiceAmountText'])) {
            $this->setInvoiceAmountText($this->strict($_POST ['invoiceAmountText'], 'string'));
        }
        if (isset($_POST ['invoiceTaxAmount'])) {
            $this->setInvoiceTaxAmount($this->strict($_POST ['invoiceTaxAmount'], 'double'));
        }
        if (isset($_POST ['invoiceDiscountAmount'])) {
            $this->setInvoiceDiscountAmount($this->strict($_POST ['invoiceDiscountAmount'], 'double'));
        }
        if (isset($_POST ['invoiceShippingAmount'])) {
            $this->setInvoiceShippingAmount($this->strict($_POST ['invoiceShippingAmount'], 'double'));
        }
        if (isset($_POST ['invoiceDate'])) {
            $this->setInvoiceDate($this->strict($_POST ['invoiceDate'], 'date'));
        }
        if (isset($_POST ['invoiceStartDate'])) {
            $this->setInvoiceDate($this->strict($_POST ['invoiceStartDate'], 'date'));
        }
        if (isset($_POST ['invoiceEndDate'])) {
            $this->setInvoiceEndDate($this->strict($_POST ['invoiceEndDate'], 'date'));
        }
        if (isset($_POST ['invoiceDueDate'])) {
            $this->setInvoiceDueDate($this->strict($_POST ['invoiceDueDate'], 'date'));
        }
        if (isset($_POST ['invoicePeriod'])) {
            $this->setInvoicePeriod($this->strict($_POST ['invoicePeriod'], 'int'));
        }
        if (isset($_POST ['invoiceDescription'])) {
            $this->setInvoiceDescription($this->strict($_POST ['invoiceDescription'], 'string'));
        }
        if (isset($_POST ['from'])) {
            $this->setFrom($this->strict($_POST ['from'], 'string'));
        }
        if (isset($_POST ['invoicePromiseDate'])) {
            $this->setInvoicePromiseDate($this->strict($_POST ['invoicePromiseDate'], 'date'));
        }
		
		if (isset($_POST ['productId'])) {
            $this->setProductId($this->strict($_POST ['productId'], 'int'));
        }
		if (isset($_POST ['productSellingPricePrice'])) {
            $this->setProductSellingPricePrice($this->strict($_POST ['productSellingPricePrice'], 'double'));
        }
        if (isset($_POST ['productSellingPriceStartDate'])) {
            $this->setProductSellingPriceStartDate($this->strict($_POST ['productSellingPriceStartDate'], 'date'));
        }
        if (isset($_POST ['productSellingPriceEndDate'])) {
            $this->setProductSellingPriceEndDate($this->strict($_POST ['productSellingPriceEndDate'], 'date'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['invoiceId'])) {
            $this->setInvoiceId($this->strict($_GET ['invoiceId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['invoiceCategoryId'])) {
            $this->setInvoiceCategoryId($this->strict($_GET ['invoiceCategoryId'], 'integer'));
        }
        if (isset($_GET ['invoiceTypeId'])) {
            $this->setInvoiceTypeId($this->strict($_GET ['invoiceTypeId'], 'integer'));
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
        if (isset($_GET ['countryId'])) {
            // some modification
            if (strlen($_GET['countryId']) > 0) {
                $data = explode("|", $_GET['countryId']);
                $this->setCountryId($this->strict($data[0], 'integer'));
            }
        }
        if (isset($_GET ['countryCurrencyTo'])) {
            $this->setCountryCurrencyTo($this->strict($_GET ['countryCurrencyTo'], 'string'));
        }
        if (isset($_GET ['invoiceProjectId'])) {
            $this->setInvoiceProjectId($this->strict($_GET ['invoiceProjectId'], 'integer'));
        }
        if (isset($_GET ['paymentTermId'])) {
            if (strlen($_GET['paymentTermId']) > 0) {
                $data = explode("|", $_GET['paymentTermId']);
                $this->setPaymentTermId($this->strict($data[0], 'integer'));
            }
        }
        if (isset($_GET ['warehouseId'])) {
            $this->setWarehouseId($this->strict($_GET ['warehouseId'], 'integer'));
        }
        if (isset($_GET ['invoiceQuotationId'])) {
            $this->setInvoiceQuotationId($this->strict($_GET ['invoiceQuotationId'], 'integer'));
        }
        if (isset($_GET ['invoiceProcessId'])) {
            $this->setInvoiceProcessId($this->strict($_GET ['invoiceProcessId'], 'integer'));
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
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string'));
        }
        if (isset($_GET ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_GET ['referenceNumber'], 'string'));
        }
        if (isset($_GET ['invoiceQuotationNumber'])) {
            $this->setInvoiceQuotationNumber($this->strict($_GET ['invoiceQuotationNumber'], 'string'));
        }
        if (isset($_GET ['purchaseOrderNumber'])) {
            $this->setPurchaseOrderNumber($this->strict($_GET ['purchaseOrderNumber'], 'string'));
        }
        if (isset($_GET ['invoiceTotalAmount'])) {
            $this->setInvoiceTotalAmount($this->strict($_GET ['invoiceTotalAmount'], 'double'));
        }
        if (isset($_GET ['invoiceAmountText'])) {
            $this->setInvoiceAmountText($this->strict($_GET ['invoiceAmountText'], 'double'));
        }
        if (isset($_GET ['invoiceTaxAmount'])) {
            $this->setInvoiceTaxAmount($this->strict($_GET ['invoiceTaxAmount'], 'double'));
        }
        if (isset($_GET ['invoiceDiscountAmount'])) {
            $this->setInvoiceDiscountAmount($this->strict($_GET ['invoiceDiscountAmount'], 'double'));
        }
        if (isset($_GET ['invoiceShippingAmount'])) {
            $this->setInvoiceShippingAmount($this->strict($_GET ['invoiceShippingAmount'], 'double'));
        }
        if (isset($_GET ['invoiceDate'])) {
            $this->setInvoiceDate($this->strict($_GET ['invoiceDate'], 'date'));
        }
        if (isset($_GET ['invoiceStartDate'])) {
            $this->setInvoiceStartDate($this->strict($_GET ['invoiceStartDate'], 'date'));
        }
        if (isset($_GET ['invoiceEndDate'])) {
            $this->setInvoiceEndDate($this->strict($_GET ['invoiceEndDate'], 'date'));
        }
        if (isset($_GET ['invoiceDueDate'])) {
            $this->setInvoiceDueDate($this->strict($_GET ['invoiceDueDate'], 'date'));
        }
        if (isset($_GET ['invoicePeriod'])) {
            $this->setInvoicePeriod($this->strict($_GET ['invoicePeriod'], 'int'));
        }
        if (isset($_GET ['invoiceDescription'])) {
            $this->setInvoiceDescription($this->strict($_GET ['invoiceDescription'], 'string'));
        }
        if (isset($_GET ['from'])) {
            $this->setFrom($this->strict($_GET ['from'], 'string'));
        }
        if (isset($_GET ['invoicePromiseDate'])) {
            $this->setInvoicePromiseDate($this->strict($_GET ['invoicePromiseDate'], 'date'));
        }
		if (isset($_GET ['productId'])) {
            $this->setProductId($this->strict($_GET['productId'], 'int'));
        }
		if (isset($_GET['productSellingPricePrice'])) {
            $this->setProductSellingPricePrice($this->strict($_GET ['productSellingPricePrice'], 'double'));
        }
        if (isset($_GET ['productSellingPriceStartDate'])) {
            $this->setProductSellingPriceStartDate($this->strict($_GET ['productSellingPriceStartDate'], 'date'));
        }
        if (isset($_GET ['productSellingPriceEndDate'])) {
            $this->setProductSellingPriceEndDate($this->strict($_GET['productSellingPriceEndDate'], 'date'));
        }
        if (isset($_GET ['invoiceId'])) {
            $this->setTotal(count($_GET ['invoiceId']));
            if (is_array($_GET ['invoiceId'])) {
                $this->invoiceId = array();
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
            if (isset($_GET ['invoiceId'])) {
                $this->setInvoiceId($this->strict($_GET ['invoiceId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getInvoiceId($i, 'array') . ",";
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
    public function getInvoiceId($key, $type) {
        if ($type == 'single') {
            return $this->invoiceId;
        } else {
            if ($type == 'array') {
                return $this->invoiceId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getinvoiceId ?")
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
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setInvoiceId($value, $key, $type) {
        if ($type == 'single') {
            $this->invoiceId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->invoiceId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setinvoiceId?")
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
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Category
     * @return int $invoiceCategoryId
     */
    public function getInvoiceCategoryId() {
        return $this->invoiceCategoryId;
    }

    /**
     * To Set Category
     * @param int $invoiceCategoryId Category
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setInvoiceCategoryId($invoiceCategoryId) {
        $this->invoiceCategoryId = $invoiceCategoryId;
        return $this;
    }

    /**
     * To Return Type
     * @return int $invoiceTypeId
     */
    public function getInvoiceTypeId() {
        return $this->invoiceTypeId;
    }

    /**
     * To Set Type
     * @param int $invoiceTypeId Type
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setInvoiceTypeId($invoiceTypeId) {
        $this->invoiceTypeId = $invoiceTypeId;
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
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setBusinessPartnerId($businessPartnerId) {
        $this->businessPartnerId = $businessPartnerId;
        return $this;
    }

    /**
     * To Return  BusinessPartnerContact
     * @return int $businessPartnerContactId
     */
    public function getBusinessPartnerContactId() {
        return $this->businessPartnerContactId;
    }

    /**
     * To Set Business Partner Contact
     * @param int $businessPartnerContactId Business Parnter Contact
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setBusinessPartnerContactId($businessPartnerContactId) {
        $this->businessPartnerContactId = $businessPartnerContactId;
        return $this;
    }

    /**
     * To Return  Country
     * @return int $countryId
     */
    public function getCountryId() {
        return $this->countryId;
    }

    /**
     * To Set Country
     * @param int $countryId Country
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setCountryId($countryId) {
        $this->countryId = $countryId;
        return $this;
    }

    /**
     * To Return Project
     * @return int $invoiceProjectId
     */
    public function getInvoiceProjectId() {
        return $this->invoiceProjectId;
    }

    /**
     * To Set Project
     * @param int $invoiceProjectId Project
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setInvoiceProjectId($invoiceProjectId) {
        $this->invoiceProjectId = $invoiceProjectId;
        return $this;
    }

    /**
     * To Return Payment Terms
     * @return int $paymentTermId
     */
    public function getPaymentTermId() {
        return $this->paymentTermId;
    }

    /**
     * To Set Payment Terms
     * @param int $paymentTermId Payment Terms
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setPaymentTermId($paymentTermId) {
        $this->paymentTermId = $paymentTermId;
        return $this;
    }

    /**
     * To Return Warehouse
     * @return int $warehouseId
     */
    public function getWarehouseId() {
        return $this->warehouseId;
    }

    /**
     * To Set Warehouse
     * @param int $warehouseId Warehouse
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setWarehouseId($warehouseId) {
        $this->warehouseId = $warehouseId;
        return $this;
    }

    /**
     * To Return Invoice Process
     * @return int $invoiceProcessId
     */
    public function getInvoiceProcessId() {
        return $this->invoiceProcessId;
    }

    /**
     * To Set Invoice Process
     * @param int $invoiceProcessId Process
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setInvoiceProcessId($invoiceProcessId) {
        $this->invoiceProcessId = $invoiceProcessId;
        return $this;
    }

    /**
     * To Return  businessPartnerAddress
     * @return string $businessPartnerAddress
     */
    public function getBusinessPartnerAddress() {
        return $this->businessPartnerAddress;
    }

    /**
     * To Set businessPartnerAddress
     * @param string $businessPartnerAddress Business Address
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setBusinessPartnerAddress($businessPartnerAddress) {
        $this->businessPartnerAddress = $businessPartnerAddress;
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
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
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
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setReferenceNumber($referenceNumber) {
        $this->referenceNumber = $referenceNumber;
        return $this;
    }

    /**
     * To Return Quotation Number
     * @return string $invoiceQuotationNumber
     */
    public function getInvoiceQuotationNumber() {
        return $this->invoiceQuotationNumber;
    }

    /**
     * To Set Quotation Number
     * @param string $invoiceQuotationNumber Quotation Number
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setInvoiceQuotationNumber($invoiceQuotationNumber) {
        $this->invoiceQuotationNumber = $invoiceQuotationNumber;
        return $this;
    }

    /**
     * To Return Purchase Order Number
     * @return string $purchaseOrderNumber
     */
    public function getPurchaseOrderNumber() {
        return $this->purchaseOrderNumber;
    }

    /**
     * To Set Purchase Order Number
     * @param string $purchaseOrderNumber Purchase Order Number
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setPurchaseOrderNumber($purchaseOrderNumber) {
        $this->purchaseOrderNumber = $purchaseOrderNumber;
        return $this;
    }

    /**
     * To Return Total Amount
     * @return double $invoiceTotalAmount
     */
    public function getInvoiceTotalAmount() {
        return $this->invoiceTotalAmount;
    }

    /**
     * To Set Total Amount
     * @param double $invoiceTotalAmount Total Amount
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setInvoiceTotalAmount($invoiceTotalAmount) {
        $this->invoiceTotalAmount = $invoiceTotalAmount;
        return $this;
    }

    /**
     * To Return Amount Text
     * @return double $invoiceAmountText
     */
    public function getInvoiceAmountText() {
        return $this->invoiceAmountText;
    }

    /**
     * To Set Amount Text
     * @param double $invoiceAmountText Amount Text
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setInvoiceTextAmount($invoiceAmountText) {
        $this->invoiceAmountText = $invoiceAmountText;
        return $this;
    }

    /**
     * To Return Tax Amount
     * @return double $invoiceTaxAmount
     */
    public function getInvoiceTaxAmount() {
        return $this->invoiceTaxAmount;
    }

    /**
     * To Set Tax Amount
     * @param double $invoiceTaxAmount Tax Amount
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setInvoiceTaxAmount($invoiceTaxAmount) {
        $this->invoiceTaxAmount = $invoiceTaxAmount;
        return $this;
    }

    /**
     * To Return Discount Amount
     * @return double $invoiceDiscountAmount
     */
    public function getInvoiceDiscountAmount() {
        return $this->invoiceDiscountAmount;
    }

    /**
     * To Set Discount Amount
     * @param double $invoiceDiscountAmount Discount Amount
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setInvoiceDiscountAmount($invoiceDiscountAmount) {
        $this->invoiceDiscountAmount = $invoiceDiscountAmount;
        return $this;
    }

    /**
     * To Return Shipping Amount
     * @return double $invoiceShippingAmount
     */
    public function getInvoiceShippingAmount() {
        return $this->invoiceShippingAmount;
    }

    /**
     * To Set Shipping Amount
     * @param double $invoiceShippingAmount Shipping Amount
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setInvoiceShippingAmount($invoiceShippingAmount) {
        $this->invoiceShippingAmount = $invoiceShippingAmount;
        return $this;
    }

    /**
     * To Return Date
     * @return string $invoiceDate
     */
    public function getInvoiceDate() {
        return $this->invoiceDate;
    }

    /**
     * To Set Date
     * @param string $invoiceDate Date
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setInvoiceDate($invoiceDate) {
        $this->invoiceDate = $invoiceDate;
        return $this;
    }

    /**
     * To Return Start Date
     * @return string $invoiceDate
     */
    public function getInvoiceStartDate() {
        return $this->invoiceStartDate;
    }

    /**
     * To Set Start Date
     * @param string $invoiceStartDate Date
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setInvoiceStartDate($invoiceStartDate) {
        $this->invoiceStartDate = $invoiceStartDate;
        return $this;
    }

    /**
     * To Return End Date
     * @return string $invoiceDate
     */
    public function getInvoiceEndDate() {
        return $this->invoiceEndDate;
    }

    /**
     * To Set End Date
     * @param string $invoiceEndDate Date
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setInvoiceEndDate($invoiceEndDate) {
        $this->invoiceEndDate = $invoiceEndDate;
        return $this;
    }

    /**
     * To Return Due Date
     * @return string $invoiceDueDate
     */
    public function getInvoiceDueDate() {
        return $this->invoiceDueDate;
    }

    /**
     * To Set Due Date
     * @param string $invoiceDueDate Due Date
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setInvoiceDueDate($invoiceDueDate) {
        $this->invoiceDueDate = $invoiceDueDate;
        return $this;
    }

    /**
     * To Return Period
     * @return string $invoiceDescription
     */
    public function getInvoicePeriod() {
        return $this->invoicePeriod;
    }

    /**
     * To Set Period
     * @param string $invoicePeriod Period
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setInvoicePeriod($invoicePeriod) {
        $this->invoicePeriod = $invoicePeriod;
        return $this;
    }

    /**
     * To Return Description
     * @return string $invoiceDescription
     */
    public function getInvoiceDescription() {
        return $this->invoiceDescription;
    }

    /**
     * To Set Description
     * @param string $invoiceDescription Description
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setInvoiceDescription($invoiceDescription) {
        $this->invoiceDescription = $invoiceDescription;
        return $this;
    }

    /**
     * Return Business Partner Contact Email
     * @return int
     */
    public function getBusinessPartnerContactEmail() {
        return $this->businessPartnerContactEmail;
    }

    /**
     * Set Business Partner Contact Email
     * @param int $businessPartnerContactEmail
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setBusinessPartnerContactEmail($businessPartnerContactEmail) {
        $this->businessPartnerContactEmail = $businessPartnerContactEmail;
        return $this;
    }

    /**
     * Set Business Partner Contact Name
     * @return int
     */
    public function getBusinessPartnerContactName() {
        return $this->businessPartnerContactName;
    }

    /**
     * Set Business Partner Contact Name
     * @param int $businessPartnerContactName
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setBusinessPartnerContactName($businessPartnerContactName) {
        $this->businessPartnerContactName = $businessPartnerContactName;
        return $this;
    }

    /**
     * Return Business Partner Contact Phone
     * @return int
     */
    public function getBusinessPartnerContactPhone() {
        return $this->businessPartnerContactPhone;
    }

    /**
     * Set Business Partner Contact Phone
     * @param int $businessPartnerContactPhone
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setBusinessPartnerContactPhone($businessPartnerContactPhone) {
        $this->businessPartnerContactPhone = $businessPartnerContactPhone;
        return $this;
    }

    /**
     * Set Business Partner Company
     * @return string
     */
    public function getBusinessPartnerCompany() {
        return $this->businessPartnerCompany;
    }

    /**
     * Set Business Partner Company
     * @param string $businessPartnerCompany
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setBusinessPartnerCompany($businessPartnerCompany) {
        $this->businessPartnerCompany = $businessPartnerCompany;
        return $this;
    }

    /**
     * Return Country Currency To
     * @return int
     */
    public function getCountryCurrencyTo() {
        return $this->countryCurrencyTo;
    }

    /**
     * Set Country Currency To
     * @param int $countryCurrencyTo
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setCountryCurrencyTo($countryCurrencyTo) {
        $this->countryCurrencyTo = $countryCurrencyTo;
        return $this;
    }

    /**
     * Set Invoice Promise Date
     * @return string
     */
    public function getInvoicePromiseDate() {
        return $this->invoicePromiseDate;
    }

    /**
     * Set Invoice Promise Date
     * @param string $invoicePromiseDate
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setInvoicePromiseDate($invoicePromiseDate) {
        $this->invoicePromiseDate = $invoicePromiseDate;
        return $this;
    }

    /**
     * Return Invoice Quotation
     * @return int
     */
    public function getInvoiceQuotationId() {
        return $this->invoiceQuotationId;
    }

    /**
     * Set Invoice Quotation
     * @param int $invoiceQuotationId
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setInvoiceQuotationId($invoiceQuotationId) {
        $this->invoiceQuotationId = $invoiceQuotationId;
        return $this;
    }
	
	/**
     * To Return Product 
     * @return int $productId
     */
    public function getProductId() {
        return $this->productId;
    }

    /**
     * To Set Product 
     * @param int $productId Product 
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setProductId($productId) {
        $this->productId = $productId;
        return $this;
    }
	
	  /**
     * To Return Price 
     * @return double $productSellingPricePrice
     */
    public function getProductSellingPricePrice() {
        return $this->productSellingPricePrice;
    }

    /**
     * To Set Price 
     * @param double $productSellingPricePrice Price 
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setProductSellingPricePrice($productSellingPricePrice) {
        $this->productSellingPricePrice = $productSellingPricePrice;
        return $this;
    }

    /**
     * To Return Start Date 
     * @return string $productSellingPriceStartDate
     */
    public function getProductSellingPriceStartDate() {
        return $this->productSellingPriceStartDate;
    }

    /**
     * To Set Start Date 
     * @param string $productSellingPriceStartDate Start Date 
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setProductSellingPriceStartDate($productSellingPriceStartDate) {
        $this->productSellingPriceStartDate = $productSellingPriceStartDate;
        return $this;
    }

    /**
     * To Return End Date 
     * @return string $productSellingPriceEndDate
     */
    public function getProductSellingPriceEndDate() {
        return $this->productSellingPriceEndDate;
    }

    /**
     * To Set End Date 
     * @param string $productSellingPriceEndDate End Date 
     * @return \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public function setProductSellingPriceEndDate($productSellingPriceEndDate) {
        $this->productSellingPriceEndDate = $productSellingPriceEndDate;
        return $this;
    }

}

?>