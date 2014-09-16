<?php

namespace Core\Financial\Cashbook\PaymentVoucher\Model;

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
 * Class PaymentVoucher
 * This is paymentVoucher model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\Cashbook\PaymentVoucher\Model;
 * @subpackage Cashbook
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class PaymentVoucherModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $paymentVoucherId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Bank
     * @var int
     */
    private $bankId;

    /**
     * Business Category
     * @var int
     */
    private $businessPartnerCategoryId;

    /**
     * Business Partner
     * @var int
     */
    private $businessPartnerId;

    /**
     * Payment Type
     * @var int
     */
    private $paymentTypeId;

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
     * Description
     * @var string
     */
    private $paymentVoucherDescription;

    /**
     * Date
     * @var string
     */
    private $paymentVoucherDate;

    /**
     * Amount
     * @var double
     */
    private $paymentVoucherAmount;

    /**
     * Cheque Date
     * @var string
     */
    private $paymentVoucherChequeDate;

    /**
     * Cheque Number
     * @var string
     */
    private $paymentVoucherChequeNumber;

    /**
     * Payee
     * @var string
     */
    private $paymentVoucherPayee;

    /**
     * Is Printed
     * @var bool
     */
    private $isPrinted;

    /**
     * Is Conform
     * @var bool
     */
    private $isConform;

    /**
     * Is Printed
     * @var bool
     */
    private $isChequePrinted;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('paymentVoucher');
        $this->setPrimaryKeyName('paymentVoucherId');
        $this->setMasterForeignKeyName('paymentVoucherId');
        $this->setFilterCharacter('paymentVoucherDescription');
        //$this->setFilterCharacter('paymentVoucherNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['paymentVoucherId'])) {
            $this->setPaymentVoucherId($this->strict($_POST ['paymentVoucherId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['bankId'])) {
            $this->setBankId($this->strict($_POST ['bankId'], 'integer'));
        }
        if (isset($_POST ['businessPartnerCategoryId'])) {
            $this->setBusinessPartnerCategoryId($this->strict($_POST ['businessPartnerCategoryId'], 'integer'));
        }
        if (isset($_POST ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_POST ['businessPartnerId'], 'integer'));
        }
        if (isset($_POST ['paymentTypeId'])) {
            $this->setPaymentTypeId($this->strict($_POST ['paymentTypeId'], 'integer'));
        }
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string'));
        }
        if (isset($_POST ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_POST ['referenceNumber'], 'string'));
        }
        if (isset($_POST ['paymentVoucherDescription'])) {
            $this->setPaymentVoucherDescription($this->strict($_POST ['paymentVoucherDescription'], 'string'));
        }
        if (isset($_POST ['paymentVoucherDate'])) {
            $this->setPaymentVoucherDate($this->strict($_POST ['paymentVoucherDate'], 'date'));
        }
        if (isset($_POST ['paymentVoucherChequeDate'])) {
            $this->setPaymentVoucherChequeDate($this->strict($_POST ['paymentVoucherChequeDate'], 'date'));
        }
        if (isset($_POST ['paymentVoucherAmount'])) {
            $this->setPaymentVoucherAmount($this->strict($_POST ['paymentVoucherAmount'], 'double'));
        }
        if (isset($_POST ['paymentVoucherChequeNumber'])) {
            $this->setPaymentVoucherChequeNumber($this->strict($_POST ['paymentVoucherChequeNumber'], 'string'));
        }
        if (isset($_POST ['paymentVoucherPayee'])) {
            $this->setPaymentVoucherPayee($this->strict($_POST ['paymentVoucherPayee'], 'string'));
        }
        if (isset($_POST ['from'])) {
            $this->setFrom($this->strict($_POST ['from'], 'string'));
        }
        if (isset($_POST ['isPrinted'])) {
            $this->setIsPrinted($this->strict($_POST ['isPrinted'], 'bool'));
        }
        if (isset($_POST ['isConform'])) {
            $this->setIsConform($this->strict($_POST ['isConform'], 'bool'));
        }
        if (isset($_POST ['isChequePrinted'])) {
            $this->setIsChequePrinted($this->strict($_POST ['isChequePrinted'], 'bool'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['paymentVoucherId'])) {
            $this->setPaymentVoucherId($this->strict($_GET ['paymentVoucherId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['bankId'])) {
            $this->setBankId($this->strict($_GET ['bankId'], 'integer'));
        }
        if (isset($_GET ['businessPartnerCategoryId'])) {
            $this->setBusinessPartnerCategoryId($this->strict($_GET ['businessPartnerCategoryId'], 'integer'));
        }
        if (isset($_GET ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_GET ['businessPartnerId'], 'integer'));
        }
        if (isset($_GET ['paymentTypeId'])) {
            $this->setPaymentTypeId($this->strict($_GET ['paymentTypeId'], 'integer'));
        }
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string'));
        }
        if (isset($_GET ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_GET ['referenceNumber'], 'string'));
        }
        if (isset($_GET ['paymentVoucherDescription'])) {
            $this->setPaymentVoucherDescription($this->strict($_GET ['paymentVoucherDescription'], 'string'));
        }
        if (isset($_GET ['paymentVoucherDate'])) {
            $this->setPaymentVoucherDate($this->strict($_GET ['paymentVoucherDate'], 'date'));
        }
        if (isset($_GET ['paymentVoucherChequeDate'])) {
            $this->setPaymentVoucherChequeDate($this->strict($_GET ['paymentVoucherChequeDate'], 'date'));
        }
        if (isset($_GET ['paymentVoucherAmount'])) {
            $this->setPaymentVoucherAmount($this->strict($_GET ['paymentVoucherAmount'], 'double'));
        }
        if (isset($_GET ['paymentVoucherChequeNumber'])) {
            $this->setPaymentVoucherChequeNumber($this->strict($_GET ['paymentVoucherChequeNumber'], 'string'));
        }
        if (isset($_GET ['paymentVoucherPayee'])) {
            $this->setPaymentVoucherPayee($this->strict($_GET ['paymentVoucherPayee'], 'string'));
        }
        if (isset($_GET ['from'])) {
            $this->setFrom($this->strict($_GET ['from'], 'string'));
        }
        if (isset($_GET ['isPrinted'])) {
            $this->setIsPrinted($this->strict($_GET ['isPrinted'], 'bool'));
        }
        if (isset($_GET ['isConform'])) {
            $this->setIsConform($this->strict($_GET ['isConform'], 'bool'));
        }
        if (isset($_GET ['isChequePrinted'])) {
            $this->setIsChequePrinted($this->strict($_GET ['isChequePrinted'], 'bool'));
        }
        if (isset($_GET ['paymentVoucherId'])) {
            $this->setTotal(count($_GET ['paymentVoucherId']));
            if (is_array($_GET ['paymentVoucherId'])) {
                $this->paymentVoucherId = array();
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
            if (isset($_GET ['paymentVoucherId'])) {
                $this->setPaymentVoucherId($this->strict($_GET ['paymentVoucherId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getPaymentVoucherId($i, 'array') . ",";
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
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return \Core\Financial\Cashbook\PaymentVoucher\Model\PaymentVoucherModel
     */
    public function setPaymentVoucherId($value, $key, $type) {
        if ($type == 'single') {
            $this->paymentVoucherId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->paymentVoucherId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setPaymentVoucherId?")
                );
                exit();
            }
        }
    }

    /**
     * Return Primary Key Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getPaymentVoucherId($key, $type) {
        if ($type == 'single') {
            return $this->paymentVoucherId;
        } else {
            if ($type == 'array') {
                return $this->paymentVoucherId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getPaymentVoucherId ?")
                );
                exit();
            }
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
     * @return \Core\Financial\Cashbook\PaymentVoucher\Model\PaymentVoucherModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Bank
     * @return int $bankId
     */
    public function getBankId() {
        return $this->bankId;
    }

    /**
     * To Set Bank
     * @param int $bankId Bank
     * @return \Core\Financial\Cashbook\PaymentVoucher\Model\PaymentVoucherModel
     */
    public function setBankId($bankId) {
        $this->bankId = $bankId;
        return $this;
    }

    /**
     * To Return Business Partner Category
     * @return int $businessPartnerCategoryId
     */
    public function getBusinessPartnerCategoryId() {
        return $this->businessPartnerCategoryId;
    }

    /**
     * To Set Business Partner Category
     * @param int $businessPartnerCategoryId Business Category
     * @return \Core\Financial\Cashbook\PaymentVoucher\Model\PaymentVoucherModel
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
     * @return \Core\Financial\Cashbook\PaymentVoucher\Model\PaymentVoucherModel
     */
    public function setBusinessPartnerId($businessPartnerId) {
        $this->businessPartnerId = $businessPartnerId;
        return $this;
    }

    /**
     * To Return Payment Type
     * @return int $paymentTypeId
     */
    public function getPaymentTypeId() {
        return $this->paymentTypeId;
    }

    /**
     * To Set Payment Type
     * @param int $paymentTypeId Payment Type
     * @return \Core\Financial\Cashbook\PaymentVoucher\Model\PaymentVoucherModel
     */
    public function setPaymentTypeId($paymentTypeId) {
        $this->paymentTypeId = $paymentTypeId;
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
     * @return \Core\Financial\Cashbook\PaymentVoucher\Model\PaymentVoucherModel
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
     * @return \Core\Financial\Cashbook\PaymentVoucher\Model\PaymentVoucherModel
     */
    public function setReferenceNumber($referenceNumber) {
        $this->referenceNumber = $referenceNumber;
        return $this;
    }

    /**
     * To Return Description
     * @return string $paymentVoucherDescription
     */
    public function getPaymentVoucherDescription() {
        return $this->paymentVoucherDescription;
    }

    /**
     * To Set Description
     * @param string $paymentVoucherDescription Description
     * @return \Core\Financial\Cashbook\PaymentVoucher\Model\PaymentVoucherModel
     */
    public function setPaymentVoucherDescription($paymentVoucherDescription) {
        $this->paymentVoucherDescription = $paymentVoucherDescription;
        return $this;
    }

    /**
     * To Return Date
     * @return string $paymentVoucherDate
     */
    public function getPaymentVoucherDate() {
        return $this->paymentVoucherDate;
    }

    /**
     * To Set Date
     * @param string $paymentVoucherDate Date
     * @return \Core\Financial\Cashbook\PaymentVoucher\Model\PaymentVoucherModel
     */
    public function setPaymentVoucherDate($paymentVoucherDate) {
        $this->paymentVoucherDate = $paymentVoucherDate;
        return $this;
    }

    /**
     * To Return Cheque Date
     * @return string $paymentVoucherDate
     */
    public function getPaymentVoucherChequeDate() {
        return $this->paymentVoucherChequeDate;
    }

    /**
     * To Set Cheque Date
     * @param string $paymentVoucherChequeDate Date
     * @return \Core\Financial\Cashbook\PaymentVoucher\Model\PaymentVoucherModel
     */
    public function setPaymentVoucherChequeDate($paymentVoucherChequeDate) {
        $this->paymentVoucherChequeDate = $paymentVoucherChequeDate;
        return $this;
    }

    /**
     * To Return Amount
     * @return double $paymentVoucherAmount
     */
    public function getPaymentVoucherAmount() {
        return $this->paymentVoucherAmount;
    }

    /**
     * To Set Amount
     * @param double $paymentVoucherAmount Amount
     * @return \Core\Financial\Cashbook\PaymentVoucher\Model\PaymentVoucherModel
     */
    public function setPaymentVoucherAmount($paymentVoucherAmount) {
        $this->paymentVoucherAmount = $paymentVoucherAmount;
        return $this;
    }

    /**
     * To Return Cheque Number
     * @return string $paymentVoucherChequeNumber
     */
    public function getPaymentVoucherChequeNumber() {
        return $this->paymentVoucherChequeNumber;
    }

    /**
     * To Set Cheque Number
     * @param string $paymentVoucherChequeNumber Cheque Number
     * @return \Core\Financial\Cashbook\PaymentVoucher\Model\PaymentVoucherModel
     */
    public function setPaymentVoucherChequeNumber($paymentVoucherChequeNumber) {
        $this->paymentVoucherChequeNumber = $paymentVoucherChequeNumber;
        return $this;
    }

    /**
     * To Return Payee
     * @return string $paymentVoucherPayee
     */
    public function getPaymentVoucherPayee() {
        return $this->paymentVoucherPayee;
    }

    /**
     * To Set Payee
     * @param string $paymentVoucherPayee Payee
     * @return \Core\Financial\Cashbook\PaymentVoucher\Model\PaymentVoucherModel
     */
    public function setPaymentVoucherPayee($paymentVoucherPayee) {
        $this->paymentVoucherPayee = $paymentVoucherPayee;
        return $this;
    }

    /**
     * To Return Is Printed
     * @return bool $isPrinted
     */
    public function getIsPrinted() {
        return $this->isPrinted;
    }

    /**
     * To Set Is Printed
     * @param bool $isPrinted Is Printed
     * @return \Core\Financial\Cashbook\PaymentVoucher\Model\PaymentVoucherModel
     */
    public function setIsPrinted($isPrinted) {
        $this->isPrinted = $isPrinted;
        return $this;
    }

    /**
     * To Return iIs Conform
     * @return bool $isConform
     */
    public function getIsConform() {
        return $this->isConform;
    }

    /**
     * To Set Is Conform
     * @param bool $isConform Is Conform
     * @return \Core\Financial\Cashbook\PaymentVoucher\Model\PaymentVoucherModel
     */
    public function setIsConform($isConform) {
        $this->isConform = $isConform;
        return $this;
    }

    /**
     * To Return Is Cheque Printed
     * @return bool $isChequePrinted
     */
    public function getIsChequePrinted() {
        return $this->isChequePrinted;
    }

    /**
     * To Set Is Cheque Printed
     * @param bool $isChequePrinted Is Printed
     * @return \Core\Financial\Cashbook\PaymentVoucher\Model\PaymentVoucherModel
     */
    public function setIsChequePrinted($isChequePrinted) {
        $this->isChequePrinted = $isChequePrinted;
        return $this;
    }

}

?>