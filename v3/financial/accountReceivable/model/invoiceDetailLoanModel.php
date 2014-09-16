<?php

namespace Core\Financial\AccountReceivable\InvoiceDetailLoan\Model;

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
 * Class InvoiceDetailLoan
 * This is Invoice Detail Loan model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountReceivable\InvoiceDetailLoan\Model;
 * @subpackage AccountReceivable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class InvoiceDetailLoanModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $invoiceDetailLoanId;

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
     * Chart Of Account
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
     * Invoice Category
     * @var int
     */
    private $invoiceCategoryId;

    /**
     * Invoice Type
     * @var int
     */
    private $invoiceTypeId;

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
     * Principal Amount
     * @var double
     */
    private $invoiceDetailLoanPrincipalAmount;

    /**
     * Interest Amount
     * @var double
     */
    private $invoiceDetailLoanInterestAmount;

    /**
     * Amount
     * @var double
     */
    private $invoiceDetailLoanAmount;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('invoiceDetailLoan');
        $this->setPrimaryKeyName('invoiceDetailLoanId');
        $this->setMasterForeignKeyName('invoiceDetailLoanId');
        $this->setFilterCharacter('invoiceDetailLoanDescription');
        //$this->setFilterCharacter('invoiceDetailLoanNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['invoiceDetailLoanId'])) {
            $this->setInvoiceDetailLoanId($this->strict($_POST ['invoiceDetailLoanId'], 'integer'), 0, 'single');
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
        if (isset($_POST ['invoiceCategoryId'])) {
            $this->setInvoiceCategoryId($this->strict($_POST ['invoiceCategoryId'], 'integer'));
        }
        if (isset($_POST ['invoiceTypeId'])) {
            $this->setInvoiceTypeId($this->strict($_POST ['invoiceTypeId'], 'integer'));
        }
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string'));
        }
        if (isset($_POST ['journalNumber'])) {
            $this->setJournalNumber($this->strict($_POST ['journalNumber'], 'string'));
        }
        if (isset($_POST ['invoiceDetailLoanPrincipalAmount'])) {
            $this->setInvoiceDetailLoanPrincipalAmount(
                    $this->strict($_POST ['invoiceDetailLoanPrincipalAmount'], 'double')
            );
        }
        if (isset($_POST ['invoiceDetailLoanInterestAmount'])) {
            $this->setInvoiceDetailLoanInterestAmount(
                    $this->strict($_POST ['invoiceDetailLoanInterestAmount'], 'double')
            );
        }
        if (isset($_POST ['invoiceDetailLoanAmount'])) {
            $this->setInvoiceDetailLoanAmount($this->strict($_POST ['invoiceDetailLoanAmount'], 'double'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['invoiceDetailLoanId'])) {
            $this->setInvoiceDetailLoanId($this->strict($_GET ['invoiceDetailLoanId'], 'integer'), 0, 'single');
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
        if (isset($_GET ['invoiceCategoryId'])) {
            $this->setInvoiceCategoryId($this->strict($_GET ['invoiceCategoryId'], 'integer'));
        }
        if (isset($_GET ['invoiceTypeId'])) {
            $this->setInvoiceTypeId($this->strict($_GET ['invoiceTypeId'], 'integer'));
        }
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string'));
        }
        if (isset($_GET ['journalNumber'])) {
            $this->setJournalNumber($this->strict($_GET ['journalNumber'], 'string'));
        }
        if (isset($_GET ['invoiceDetailLoanPrincipalAmount'])) {
            $this->setInvoiceDetailLoanPrincipalAmount(
                    $this->strict($_GET ['invoiceDetailLoanPrincipalAmount'], 'double')
            );
        }
        if (isset($_GET ['invoiceDetailLoanInterestAmount'])) {
            $this->setInvoiceDetailLoanInterestAmount(
                    $this->strict($_GET ['invoiceDetailLoanInterestAmount'], 'double')
            );
        }
        if (isset($_GET ['invoiceDetailLoanAmount'])) {
            $this->setInvoiceDetailLoanAmount($this->strict($_GET ['invoiceDetailLoanAmount'], 'double'));
        }
        if (isset($_GET ['invoiceDetailLoanId'])) {
            $this->setTotal(count($_GET ['invoiceDetailLoanId']));
            if (is_array($_GET ['invoiceDetailLoanId'])) {
                $this->invoiceDetailLoanId = array();
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
            if (isset($_GET ['invoiceDetailLoanId'])) {
                $this->setInvoiceDetailLoanId(
                        $this->strict($_GET ['invoiceDetailLoanId'] [$i], 'numeric'), $i, 'array'
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
            $primaryKeyAll .= $this->getInvoiceDetailLoanId($i, 'array') . ",";
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
    public function getInvoiceDetailLoanId($key, $type) {
        if ($type == 'single') {
            return $this->invoiceDetailLoanId;
        } else {
            if ($type == 'array') {
                return $this->invoiceDetailLoanId [$key];
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:getInvoiceDetailLoanId ?"
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
     * @return \Core\Financial\AccountReceivable\InvoiceDetailLoan\Model\InvoiceDetailLoanModel
     */
    public function setInvoiceDetailLoanId($value, $key, $type) {
        if ($type == 'single') {
            $this->invoiceDetailLoanId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->invoiceDetailLoanId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:setInvoiceDetailLoanId?"
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
     * @return \Core\Financial\AccountReceivable\InvoiceDetailLoan\Model\InvoiceDetailLoanModel
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
     * @return \Core\Financial\AccountReceivable\InvoiceDetailLoan\Model\InvoiceDetailLoanModel
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
     * @return \Core\Financial\AccountReceivable\InvoiceDetailLoan\Model\InvoiceDetailLoanModel
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
     * @return \Core\Financial\AccountReceivable\InvoiceDetailLoan\Model\InvoiceDetailLoanModel
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
     * @return \Core\Financial\AccountReceivable\InvoiceDetailLoan\Model\InvoiceDetailLoanModel
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
     * @return \Core\Financial\AccountReceivable\InvoiceDetailLoan\Model\InvoiceDetailLoanModel
     */
    public function setTransactionTypeId($transactionTypeId) {
        $this->transactionTypeId = $transactionTypeId;
        return $this;
    }

    /**
     * To Return Invoice Category
     * @return int $invoiceCategoryId
     */
    public function getInvoiceCategoryId() {
        return $this->invoiceCategoryId;
    }

    /**
     * To Set Invoice Category
     * @param int $invoiceCategoryId Invoice Category
     * @return \Core\Financial\AccountReceivable\InvoiceDetailLoan\Model\InvoiceDetailLoanModel
     */
    public function setInvoiceCategoryId($invoiceCategoryId) {
        $this->invoiceCategoryId = $invoiceCategoryId;
        return $this;
    }

    /**
     * To Return Invoice Type
     * @return int $invoiceTypeId
     */
    public function getInvoiceTypeId() {
        return $this->invoiceTypeId;
    }

    /**
     * To Set Invoice Type
     * @param int $invoiceTypeId Invoice Type
     * @return \Core\Financial\AccountReceivable\InvoiceDetailLoan\Model\InvoiceDetailLoanModel
     */
    public function setInvoiceTypeId($invoiceTypeId) {
        $this->invoiceTypeId = $invoiceTypeId;
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
     * @return \Core\Financial\AccountReceivable\InvoiceDetailLoan\Model\InvoiceDetailLoanModel
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
     * @return \Core\Financial\AccountReceivable\InvoiceDetailLoan\Model\InvoiceDetailLoanModel
     */
    public function setJournalNumber($journalNumber) {
        $this->journalNumber = $journalNumber;
        return $this;
    }

    /**
     * To Return Principal Amount
     * @return double $invoiceDetailLoanPrincipalAmount
     */
    public function getInvoiceDetailLoanPrincipalAmount() {
        return $this->invoiceDetailLoanPrincipalAmount;
    }

    /**
     * To Set Principal Amount
     * @param double $invoiceDetailLoanPrincipalAmount Principal Amount
     * @return \Core\Financial\AccountReceivable\InvoiceDetailLoan\Model\InvoiceDetailLoanModel
     */
    public function setInvoiceDetailLoanPrincipalAmount($invoiceDetailLoanPrincipalAmount) {
        $this->invoiceDetailLoanPrincipalAmount = $invoiceDetailLoanPrincipalAmount;
        return $this;
    }

    /**
     * To Return Interest Amount
     * @return double $invoiceDetailLoanInterestAmount
     */
    public function getInvoiceDetailLoanInterestAmount() {
        return $this->invoiceDetailLoanInterestAmount;
    }

    /**
     * To Set Interest Amount
     * @param double $invoiceDetailLoanInterestAmount Interest Amount
     * @return \Core\Financial\AccountReceivable\InvoiceDetailLoan\Model\InvoiceDetailLoanModel
     */
    public function setInvoiceDetailLoanInterestAmount($invoiceDetailLoanInterestAmount) {
        $this->invoiceDetailLoanInterestAmount = $invoiceDetailLoanInterestAmount;
        return $this;
    }

    /**
     * To Return Amount
     * @return double $invoiceDetailLoanAmount
     */
    public function getInvoiceDetailLoanAmount() {
        return $this->invoiceDetailLoanAmount;
    }

    /**
     * To Set Amount
     * @param double $invoiceDetailLoanAmount Amount
     * @return \Core\Financial\AccountReceivable\InvoiceDetailLoan\Model\InvoiceDetailLoanModel
     */
    public function setInvoiceDetailLoanAmount($invoiceDetailLoanAmount) {
        $this->invoiceDetailLoanAmount = $invoiceDetailLoanAmount;
        return $this;
    }

}

?>