<?php

namespace Core\Financial\AccountReceivable\InvoiceProductDetail\Model;

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
 * Class InvoiceProductDetail
 * This is invoiceProductDetail model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountReceivable\InvoiceProductDetail\Model;
 * @subpackage AccountReceivable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class InvoiceProductDetailModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $invoiceProductDetailId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Invoice
     * @var int
     */
    private $invoiceId;

    /**
     * Chart Account
     * @var int
     */
    private $chartOfAccountId;

    /**
     * Country
     * @var int
     */
    private $countryId;

    /**
     * Business Partner
     * @var int
     */
    private $businessPartnerId;

    /**
     * Transaction Type
     * @var int
     */
    private $transactionTypeId;

    /**
     * Unit Measurement
     * @var int
     */
    private $unitOfMeasurementId;

    /**
     * Product
     * @var int
     */
    private $productId;

    /**
     * Tax
     * @var int
     */
    private $taxId;

    /**
     * Discount
     * @var int
     */
    private $discountId;

    /**
     * Document Number
     * @var string
     */
    private $documentNumber;

    /**
     * Journal Number
     * @var string
     */
    private $journalNumber;

    /**
     * Quantity
     * @var int
     */
    private $invoiceProductDetailQuantity;

    /**
     * Price
     * @var double
     */
    private $invoiceProductDetailPrice;

    /**
     * Discount
     * @var double
     */
    private $invoiceProductDetailDiscount;

    /**
     * Discount Percent
     * @var double
     */
    private $invoiceProductDetailDiscountPercent;

    /**
     * Total Price
     * @var double
     */
    private $invoiceProductDetailTotalPrice;

    /**
     * Promise Date
     * @var string
     */
    private $invoiceProductDetailPromiseDate;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('invoiceProductDetail');
        $this->setPrimaryKeyName('invoiceProductDetailId');
        $this->setMasterForeignKeyName('invoiceProductDetailId');
        $this->setFilterCharacter('invoiceProductDetailDescription');
        //$this->setFilterCharacter('invoiceProductDetailNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['invoiceProductDetailId'])) {
            $this->setInvoiceProductDetailId($this->strict($_POST ['invoiceProductDetailId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['invoiceId'])) {
            $this->setInvoiceId($this->strict($_POST ['invoiceId'], 'integer'));
        }
        if (isset($_POST ['chartOfAccountId'])) {
            $this->setChartOfAccountId($this->strict($_POST ['chartOfAccountId'], 'integer'));
        }
        if (isset($_POST ['countryId'])) {
            $this->setCountryId($this->strict($_POST ['countryId'], 'integer'));
        }
        if (isset($_POST ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_POST ['businessPartnerId'], 'integer'));
        }
        if (isset($_POST ['transactionTypeId'])) {
            $this->setTransactionTypeId($this->strict($_POST ['transactionTypeId'], 'integer'));
        }
        if (isset($_POST ['unitOfMeasurementId'])) {
            $this->setUnitOfMeasurementId($this->strict($_POST ['unitOfMeasurementId'], 'integer'));
        }
        if (isset($_POST ['productId'])) {
            $this->setProductId($this->strict($_POST ['productId'], 'integer'));
        }
        if (isset($_POST ['taxId'])) {
            $this->setTaxId($this->strict($_POST ['taxId'], 'integer'));
        }
        if (isset($_POST ['discountId'])) {
            $this->setDiscountId($this->strict($_POST ['discountId'], 'integer'));
        }
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string'));
        }
        if (isset($_POST ['journalNumber'])) {
            $this->setJournalNumber($this->strict($_POST ['journalNumber'], 'string'));
        }
        if (isset($_POST ['invoiceProductDetailQuantity'])) {
            $this->setInvoiceProductDetailQuantity($this->strict($_POST ['invoiceProductDetailQuantity'], 'integer'));
        }
        if (isset($_POST ['invoiceProductDetailPrice'])) {
            $this->setInvoiceProductDetailPrice($this->strict($_POST ['invoiceProductDetailPrice'], 'double'));
        }
        if (isset($_POST ['invoiceProductDetailDiscount'])) {
            $this->setInvoiceProductDetailDiscount($this->strict($_POST ['invoiceProductDetailDiscount'], 'double'));
        }
        if (isset($_POST ['invoiceProductDetailDiscountPercent'])) {
            $this->setInvoiceProductDetailDiscountPercent(
                    $this->strict($_POST ['invoiceProductDetailDiscountPercent'], 'double')
            );
        }
        if (isset($_POST ['invoiceProductDetailTotalPrice'])) {
            $this->setInvoiceProductDetailTotalPrice(
                    $this->strict($_POST ['invoiceProductDetailTotalPrice'], 'double')
            );
        }
        if (isset($_POST ['invoiceProductPromiseDate'])) {
            $this->setInvoiceProductDetailPromiseDate($this->strict($_POST ['invoiceProductPromiseDate'], 'date'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['invoiceProductDetailId'])) {
            $this->setInvoiceProductDetailId($this->strict($_GET ['invoiceProductDetailId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['invoiceId'])) {
            $this->setInvoiceId($this->strict($_GET ['invoiceId'], 'integer'));
        }
        if (isset($_GET ['chartOfAccountId'])) {
            $this->setChartOfAccountId($this->strict($_GET ['chartOfAccountId'], 'integer'));
        }
        if (isset($_GET ['countryId'])) {
            $this->setCountryId($this->strict($_GET ['countryId'], 'integer'));
        }
        if (isset($_GET ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_GET ['businessPartnerId'], 'integer'));
        }
        if (isset($_GET ['transactionTypeId'])) {
            $this->setTransactionTypeId($this->strict($_GET ['transactionTypeId'], 'integer'));
        }
        if (isset($_GET ['unitOfMeasurementId'])) {
            $this->setUnitOfMeasurementId($this->strict($_GET ['unitOfMeasurementId'], 'integer'));
        }
        if (isset($_GET ['productId'])) {
            $this->setProductId($this->strict($_GET ['productId'], 'integer'));
        }
        if (isset($_GET ['taxId'])) {
            $this->setTaxId($this->strict($_GET ['taxId'], 'integer'));
        }
        if (isset($_GET ['discountId'])) {
            $this->setDiscountId($this->strict($_GET ['discountId'], 'integer'));
        }
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string'));
        }
        if (isset($_GET ['journalNumber'])) {
            $this->setJournalNumber($this->strict($_GET ['journalNumber'], 'string'));
        }
        if (isset($_GET ['invoiceProductDetailQuantity'])) {
            $this->setInvoiceProductDetailQuantity($this->strict($_GET ['invoiceProductDetailQuantity'], 'integer'));
        }
        if (isset($_GET ['invoiceProductDetailPrice'])) {
            $this->setInvoiceProductDetailPrice($this->strict($_GET ['invoiceProductDetailPrice'], 'double'));
        }
        if (isset($_GET ['invoiceProductDetailDiscount'])) {
            $this->setInvoiceProductDetailDiscount($this->strict($_GET ['invoiceProductDetailDiscount'], 'double'));
        }
        if (isset($_GET ['invoiceProductDetailDiscountPercent'])) {
            $this->setInvoiceProductDetailDiscountPercent(
                    $this->strict($_GET ['invoiceProductDetailDiscountPercent'], 'double')
            );
        }
        if (isset($_GET ['invoiceProductDetailTotalPrice'])) {
            $this->setInvoiceProductDetailTotalPrice($this->strict($_GET ['invoiceProductDetailTotalPrice'], 'double'));
        }
        if (isset($_GET ['invoiceProductPromiseDate'])) {
            $this->setInvoiceProductDetailPromiseDate($this->strict($_GET ['invoiceProductPromiseDate'], 'date'));
        }
        if (isset($_GET ['invoiceProductDetailId'])) {
            $this->setTotal(count($_GET ['invoiceProductDetailId']));
            if (is_array($_GET ['invoiceProductDetailId'])) {
                $this->invoiceProductDetailId = array();
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
            if (isset($_GET ['invoiceProductDetailId'])) {
                $this->setInvoiceProductDetailId(
                        $this->strict($_GET ['invoiceProductDetailId'] [$i], 'numeric'), $i, 'array'
                );
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
            $primaryKeyAll .= $this->getInvoiceProductDetailId($i, 'array') . ",";
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
    public function getInvoiceProductDetailId($key, $type) {
        if ($type == 'single') {
            return $this->invoiceProductDetailId;
        } else {
            if ($type == 'array') {
                return $this->invoiceProductDetailId [$key];
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:getinvoiceProductDetailId ?"
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
     * @return \Core\Financial\AccountReceivable\InvoiceProductDetail\Model\InvoiceProductDetailModel
     */
    public function setInvoiceProductDetailId($value, $key, $type) {
        if ($type == 'single') {
            $this->invoiceProductDetailId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->invoiceProductDetailId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:setinvoiceProductDetailId?"
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
     * @return \Core\Financial\AccountReceivable\InvoiceProductDetail\Model\InvoiceProductDetailModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
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
     * @return \Core\Financial\AccountReceivable\InvoiceProductDetail\Model\InvoiceProductDetailModel
     */
    public function setInvoiceId($invoiceId) {
        $this->invoiceId = $invoiceId;
        return $this;
    }

    /**
     * To Return Chart Of Account
     * @return int $chartOfAccountId
     */
    public function getChartOfAccountId() {
        return $this->chartOfAccountId;
    }

    /**
     * To Set Chart Of Account
     * @param int $chartOfAccountId Chart Account
     * @return \Core\Financial\AccountReceivable\InvoiceProductDetail\Model\InvoiceProductDetailModel
     */
    public function setChartOfAccountId($chartOfAccountId) {
        $this->chartOfAccountId = $chartOfAccountId;
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
     * @return \Core\Financial\AccountReceivable\InvoiceProductDetail\Model\InvoiceProductDetailModel
     */
    public function setCountryId($countryId) {
        $this->countryId = $countryId;
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
     * @return \Core\Financial\AccountReceivable\InvoiceProductDetail\Model\InvoiceProductDetailModel
     */
    public function setBusinessPartnerId($businessPartnerId) {
        $this->businessPartnerId = $businessPartnerId;
        return $this;
    }

    /**
     * To Return Transaction Type
     * @return int $transactionTypeId
     */
    public function getTransactionTypeId() {
        return $this->transactionTypeId;
    }

    /**
     * To Set Transaction Type
     * @param int $transactionTypeId Transaction Type
     * @return \Core\Financial\AccountReceivable\InvoiceProductDetail\Model\InvoiceProductDetailModel
     */
    public function setTransactionTypeId($transactionTypeId) {
        $this->transactionTypeId = $transactionTypeId;
        return $this;
    }

    /**
     * To Return Unit Of Measurement
     * @return int $unitOfMeasurementId
     */
    public function getUnitOfMeasurementId() {
        return $this->unitOfMeasurementId;
    }

    /**
     * To Set Unit Of Measurement
     * @param int $unitOfMeasurementId Unit Measurement
     * @return \Core\Financial\AccountReceivable\InvoiceProductDetail\Model\InvoiceProductDetailModel
     */
    public function setUnitOfMeasurementId($unitOfMeasurementId) {
        $this->unitOfMeasurementId = $unitOfMeasurementId;
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
     * @return \Core\Financial\AccountReceivable\InvoiceProductDetail\Model\InvoiceProductDetailModel
     */
    public function setProductId($productId) {
        $this->productId = $productId;
        return $this;
    }

    /**
     * To Return Tax
     * @return int $taxId
     */
    public function getTaxId() {
        return $this->taxId;
    }

    /**
     * To Set Tax
     * @param int $taxId Tax
     * @return \Core\Financial\AccountReceivable\InvoiceProductDetail\Model\InvoiceProductDetailModel
     */
    public function setTaxId($taxId) {
        $this->taxId = $taxId;
        return $this;
    }

    /**
     * To Return Discount
     * @return int $discountId
     */
    public function getDiscountId() {
        return $this->discountId;
    }

    /**
     * To Set Discount
     * @param int $discountId Discount
     * @return \Core\Financial\AccountReceivable\InvoiceProductDetail\Model\InvoiceProductDetailModel
     */
    public function setDiscountId($discountId) {
        $this->discountId = $discountId;
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
     * @return \Core\Financial\AccountReceivable\InvoiceProductDetail\Model\InvoiceProductDetailModel
     */
    public function setDocumentNumber($documentNumber) {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    /**
     * To Return Journal Number
     * @return string $journalNumber
     */
    public function getJournalNumber() {
        return $this->journalNumber;
    }

    /**
     * To Set Journal Number
     * @param string $journalNumber Journal Number
     * @return \Core\Financial\AccountReceivable\InvoiceProductDetail\Model\InvoiceProductDetailModel
     */
    public function setJournalNumber($journalNumber) {
        $this->journalNumber = $journalNumber;
        return $this;
    }

    /**
     * To Return Quantity
     * @return int $invoiceProductDetailQuantity
     */
    public function getInvoiceProductDetailQuantity() {
        return $this->invoiceProductDetailQuantity;
    }

    /**
     * To Set Quantity
     * @param int $invoiceProductDetailQuantity Quantity
     * @return \Core\Financial\AccountReceivable\InvoiceProductDetail\Model\InvoiceProductDetailModel
     */
    public function setInvoiceProductDetailQuantity($invoiceProductDetailQuantity) {
        $this->invoiceProductDetailQuantity = $invoiceProductDetailQuantity;
        return $this;
    }

    /**
     * To Return Price
     * @return double $invoiceProductDetailPrice
     */
    public function getInvoiceProductDetailPrice() {
        return $this->invoiceProductDetailPrice;
    }

    /**
     * To Set Price
     * @param double $invoiceProductDetailPrice Price
     * @return \Core\Financial\AccountReceivable\InvoiceProductDetail\Model\InvoiceProductDetailModel
     */
    public function setInvoiceProductDetailPrice($invoiceProductDetailPrice) {
        $this->invoiceProductDetailPrice = $invoiceProductDetailPrice;
        return $this;
    }

    /**
     * To Return Discount
     * @return double $invoiceProductDetailDiscount
     */
    public function getInvoiceProductDetailDiscount() {
        return $this->invoiceProductDetailDiscount;
    }

    /**
     * To Set Discount
     * @param double $invoiceProductDetailDiscount Discount
     * @return \Core\Financial\AccountReceivable\InvoiceProductDetail\Model\InvoiceProductDetailModel
     */
    public function setInvoiceProductDetailDiscount($invoiceProductDetailDiscount) {
        $this->invoiceProductDetailDiscount = $invoiceProductDetailDiscount;
        return $this;
    }

    /**
     * To Return Total Price
     * @return double $invoiceProductDetailTotalPrice
     */
    public function getInvoiceProductDetailTotalPrice() {
        return $this->invoiceProductDetailTotalPrice;
    }

    /**
     * To Set Total Price
     * @param double $invoiceProductDetailTotalPrice Total Price
     * @return \Core\Financial\AccountReceivable\InvoiceProductDetail\Model\InvoiceProductDetailModel
     */
    public function setInvoiceProductDetailTotalPrice($invoiceProductDetailTotalPrice) {
        $this->invoiceProductDetailTotalPrice = $invoiceProductDetailTotalPrice;
        return $this;
    }

    /**
     * Set Invoice Product Detail Promise Date
     * @return string
     */
    public function getInvoiceProductDetailPromiseDate() {
        return $this->invoiceProductDetailPromiseDate;
    }

    /**
     * Set Invoice Product Detail Promise Date
     * @param string $invoiceProductDetailPromiseDate
     * @return \Core\Financial\AccountReceivable\InvoiceProductDetail\Model\InvoiceProductDetailModel
     */
    public function setInvoiceProductDetailPromiseDate($invoiceProductDetailPromiseDate) {
        $this->invoiceProductDetailPromiseDate = $invoiceProductDetailPromiseDate;
        return $this;
    }

    /**
     * Return Invoice Product Detail Discount Percent
     * @return string
     */
    public function getInvoiceProductDetailDiscountPercent() {
        return $this->invoiceProductDetailDiscountPercent;
    }

    /**
     * Set Invoice Product Detail Discount Percent
     * @param string $invoiceProductDetailDiscountPercent
     * @return \Core\Financial\AccountReceivable\InvoiceProductDetail\Model\InvoiceProductDetailModel
     */
    public function setInvoiceProductDetailDiscountPercent($invoiceProductDetailDiscountPercent) {
        $this->invoiceProductDetailDiscountPercent = $invoiceProductDetailDiscountPercent;
        return $this;
    }

}

?>