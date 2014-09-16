<?php

namespace Core\Financial\Cashbook\PaymentVoucherAllocation\Model;

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
 * Class PaymentVoucherAllocation
 * This is paymentVoucherAllocation model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\Cashbook\PaymentVoucherAllocation\Model;
 * @subpackage Cashbook
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class PaymentVoucherAllocationModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $paymentVoucherAllocationId;

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
     * Country
     * @var int
     */
    private $countryId;

    /**
     * Purchase Invoice
     * @var int
     */
    private $purchaseInvoiceId;

    /**
     * Payment Voucher
     * @var int
     */
    private $paymentVoucherId;

    /**
     * Amount
     * @var double
     */
    private $paymentVoucherAllocationAmount;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('paymentVoucherAllocation');
        $this->setPrimaryKeyName('paymentVoucherAllocationId');
        $this->setMasterForeignKeyName('paymentVoucherAllocationId');
        $this->setFilterCharacter('paymentVoucherAllocationDescription');
        //$this->setFilterCharacter('paymentVoucherAllocationNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['paymentVoucherAllocationId'])) {
            $this->setPaymentVoucherAllocationId(
                    $this->strict($_POST ['paymentVoucherAllocationId'], 'integer'), 0, 'single'
            );
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_POST ['businessPartnerId'], 'integer'));
        }
        if (isset($_POST ['countryId'])) {
            $this->setCountryId($this->strict($_POST ['countryId'], 'integer'));
        }
        if (isset($_POST ['purchaseInvoiceId'])) {
            $this->setPurchaseInvoiceId($this->strict($_POST ['purchaseInvoiceId'], 'integer'));
        }
        if (isset($_POST ['paymentVoucherId'])) {
            $this->setPaymentVoucherId($this->strict($_POST ['paymentVoucherId'], 'integer'));
        }
        if (isset($_POST ['paymentVoucherAllocationAmount'])) {
            $this->setPaymentVoucherAllocationAmount(
                    $this->strict($_POST ['paymentVoucherAllocationAmount'], 'double')
            );
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['paymentVoucherAllocationId'])) {
            $this->setPaymentVoucherAllocationId(
                    $this->strict($_GET ['paymentVoucherAllocationId'], 'integer'), 0, 'single'
            );
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_GET ['businessPartnerId'], 'integer'));
        }
        if (isset($_GET ['countryId'])) {
            $this->setCountryId($this->strict($_GET ['countryId'], 'integer'));
        }
        if (isset($_GET ['purchaseInvoiceId'])) {
            $this->setPurchaseInvoiceId($this->strict($_GET ['purchaseInvoiceId'], 'integer'));
        }
        if (isset($_GET ['paymentVoucherId'])) {
            $this->setPaymentVoucherId($this->strict($_GET ['paymentVoucherId'], 'integer'));
        }
        if (isset($_GET ['paymentVoucherAllocationAmount'])) {
            $this->setPaymentVoucherAllocationAmount($this->strict($_GET ['paymentVoucherAllocationAmount'], 'double'));
        }
        if (isset($_GET ['paymentVoucherAllocationId'])) {
            $this->setTotal(count($_GET ['paymentVoucherAllocationId']));
            if (is_array($_GET ['paymentVoucherAllocationId'])) {
                $this->paymentVoucherAllocationId = array();
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
            if (isset($_GET ['paymentVoucherAllocationId'])) {
                $this->setPaymentVoucherAllocationId(
                        $this->strict($_GET ['paymentVoucherAllocationId'] [$i], 'numeric'), $i, 'array'
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
            $primaryKeyAll .= $this->getPaymentVoucherAllocationId($i, 'array') . ",";
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
     * @return \Core\Financial\Cashbook\PaymentVoucherAllocation\Model\PaymentVoucherAllocationModel
     */
    public function setPaymentVoucherAllocationId($value, $key, $type) {
        if ($type == 'single') {
            $this->paymentVoucherAllocationId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->paymentVoucherAllocationId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:setPaymentVoucherAllocationId?"
                        )
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
    public function getPaymentVoucherAllocationId($key, $type) {
        if ($type == 'single') {
            return $this->paymentVoucherAllocationId;
        } else {
            if ($type == 'array') {
                return $this->paymentVoucherAllocationId [$key];
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:getPaymentVoucherAllocationId ?"
                        )
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
     * @return \Core\Financial\Cashbook\PaymentVoucherAllocation\Model\PaymentVoucherAllocationModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
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
     * @return \Core\Financial\Cashbook\PaymentVoucherAllocation\Model\PaymentVoucherAllocationModel
     */
    public function setCountryId($countryId) {
        $this->countryId = $countryId;
        return $this;
    }

    /**
     * To Return Purchase Invoice
     * @return int $purchaseInvoiceId
     */
    public function getPurchaseInvoiceId() {
        return $this->purchaseInvoiceId;
    }

    /**
     * To Set Purchase Invoice
     * @param int $purchaseInvoiceId Purchase Invoice
     * @return \Core\Financial\Cashbook\PaymentVoucherAllocation\Model\PaymentVoucherAllocationModel
     */
    public function setPurchaseInvoiceId($purchaseInvoiceId) {
        $this->purchaseInvoiceId = $purchaseInvoiceId;
        return $this;
    }

    /**
     * To Return Payment Voucher
     * @return int $paymentVoucherId
     */
    public function getPaymentVoucherId() {
        return $this->paymentVoucherId;
    }

    /**
     * To Set Payment Voucher
     * @param int $paymentVoucherId Payment Voucher
     * @return \Core\Financial\Cashbook\PaymentVoucherAllocation\Model\PaymentVoucherAllocationModel
     */
    public function setPaymentVoucherId($paymentVoucherId) {
        $this->paymentVoucherId = $paymentVoucherId;
        return $this;
    }

    /**
     * To Return Amount
     * @return double $paymentVoucherAllocationAmount
     */
    public function getPaymentVoucherAllocationAmount() {
        return $this->paymentVoucherAllocationAmount;
    }

    /**
     * To Set Amount
     * @param double $paymentVoucherAllocationAmount Amount
     * @return \Core\Financial\Cashbook\PaymentVoucherAllocation\Model\PaymentVoucherAllocationModel
     */
    public function setPaymentVoucherAllocationAmount($paymentVoucherAllocationAmount) {
        $this->paymentVoucherAllocationAmount = $paymentVoucherAllocationAmount;
        return $this;
    }

    /**
	 * To Set Business Partner Primary Key
     * @param int $businessPartnerId
	 * @return \Core\Financial\Cashbook\PaymentVoucherAllocation\Model\PaymentVoucherAllocationModel
     */
    public function setBusinessPartnerId($businessPartnerId) {
        $this->businessPartnerId = $businessPartnerId;
		return $this;
    }

    /**
	 * To Return Business Partner Primary key
     * @return int
     */
    public function getBusinessPartnerId() {
        return $this->businessPartnerId;
    }

}

?>