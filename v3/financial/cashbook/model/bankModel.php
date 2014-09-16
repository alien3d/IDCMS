<?php

namespace Core\Financial\Cashbook\Bank\Model;

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
 * Class Bank
 * This is bank model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\Cashbook\Bank\Model;
 * @subpackage Cash Book
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class BankModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $bankId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Bank Chart Of Account
     * @var int
     */
    private $chartOfAccountId;

    /**
     * Bank Code
     * @var string
     */
    private $bankCode;

    /**
     * Bank Account
     * @var string
     */
    private $bankAccount;

    /**
     * Bank Cheque Sequence
     * @var string
     */
    private $bankChequeSequence;

    /**
     * Minimum Bank Value
     * @var double
     */
    private $bankMinimumValue;

    /**
     * OverDraft
     * @var double
     */
    private $bankOverDraft;

    /**
     * Description
     * @var string
     */
    private $bankDescription;

    /**
     * Is Collection
     * @var bool
     */
    private $isCollection;

    /**
     * Is Payment Voucher
     * @var bool
     */
    private $isPaymentVoucher;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('bank');
        $this->setPrimaryKeyName('bankId');
        $this->setMasterForeignKeyName('bankId');
        $this->setFilterCharacter('bankDescription');
        //$this->setFilterCharacter('bankNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['bankId'])) {
            $this->setBankId($this->strict($_POST ['bankId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['chartOfAccountId'])) {
            $this->setChartOfAccountId($this->strict($_POST ['chartOfAccountId'], 'integer'));
        }
        if (isset($_POST ['bankCode'])) {
            $this->setBankCode($this->strict($_POST ['bankCode'], 'string'));
        }
        if (isset($_POST ['bankAccount'])) {
            $this->setBankAccount($this->strict($_POST ['bankAccount'], 'string'));
        }
        if (isset($_POST ['bankChequeSequence'])) {
            $this->setBankChequeSequence($this->strict($_POST ['bankChequeSequence'], 'string'));
        }
        if (isset($_POST ['bankMinimumValue'])) {
            $this->setBankMinimumValue($this->strict($_POST ['bankMinimumValue'], 'double'));
        }
        if (isset($_POST ['bankOverDraft'])) {
            $this->setBankOverDraft($this->strict($_POST ['bankOverDraft'], 'double'));
        }
        if (isset($_POST ['bankDescription'])) {
            $this->setBankDescription($this->strict($_POST ['bankDescription'], 'string'));
        }
        if (isset($_POST ['isCollection'])) {
            $this->setIsCollection($this->strict($_POST ['isCollection'], 'bool'));
        }
        if (isset($_POST ['isPaymentVoucher'])) {
            $this->setIsPaymentVoucher($this->strict($_POST ['isPaymentVoucher'], 'bool'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['bankId'])) {
            $this->setBankId($this->strict($_GET ['bankId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['chartOfAccountId'])) {
            $this->setChartOfAccountId($this->strict($_GET ['chartOfAccountId'], 'integer'));
        }
        if (isset($_GET ['bankCode'])) {
            $this->setBankCode($this->strict($_GET ['bankCode'], 'string'));
        }
        if (isset($_GET ['bankAccount'])) {
            $this->setBankAccount($this->strict($_GET ['bankAccount'], 'string'));
        }
        if (isset($_GET ['bankChequeSequence'])) {
            $this->setBankChequeSequence($this->strict($_GET ['bankChequeSequence'], 'string'));
        }
        if (isset($_GET ['bankMinimumValue'])) {
            $this->setBankMinimumValue($this->strict($_GET ['bankMinimumValue'], 'double'));
        }
        if (isset($_GET ['bankOverDraft'])) {
            $this->setBankOverDraft($this->strict($_GET ['bankOverDraft'], 'double'));
        }
        if (isset($_GET ['bankDescription'])) {
            $this->setBankDescription($this->strict($_GET ['bankDescription'], 'string'));
        }
        if (isset($_GET ['isCollection'])) {
            $this->setIsCollection($this->strict($_GET ['isCollection'], 'bool'));
        }
        if (isset($_GET ['isPaymentVoucher'])) {
            $this->setIsPaymentVoucher($this->strict($_GET ['isPaymentVoucher'], 'bool'));
        }
        if (isset($_GET ['bankId'])) {
            $this->setTotal(count($_GET ['bankId']));
            if (is_array($_GET ['bankId'])) {
                $this->bankId = array();
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
            if (isset($_GET ['bankId'])) {
                $this->setBankId($this->strict($_GET ['bankId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getBankId($i, 'array') . ",";
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
     * @return \Core\Financial\Cashbook\Bank\Model\BankModel
     */
    public function setBankId($value, $key, $type) {
        if ($type == 'single') {
            $this->bankId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->bankId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setBankId?")
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
    public function getBankId($key, $type) {
        if ($type == 'single') {
            return $this->bankId;
        } else {
            if ($type == 'array') {
                return $this->bankId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getBankId ?")
                );
                exit();
            }
        }
    }

    /**
     * To Return  Company
     * @return int $companyId
     */
    public function getCompanyId() {
        return $this->companyId;
    }

    /**
     * To Set Company
     * @param int $companyId Company
     * @return \Core\Financial\Cashbook\Bank\Model\BankModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Bank Chart Of Account
     * @return int $chartOfAccountId
     */
    public function getChartOfAccountId() {
        return $this->chartOfAccountId;
    }

    /**
     * To Set Bank Chart Of Account
     * @param int $chartOfAccountId Chart Account
     * @return \Core\Financial\Cashbook\Bank\Model\BankModel
     */
    public function setChartOfAccountId($chartOfAccountId) {
        $this->chartOfAccountId = $chartOfAccountId;
        return $this;
    }

    /**
     * To Return Bank Code
     * @return string $bankCode
     */
    public function getBankCode() {
        return $this->bankCode;
    }

    /**
     * To Set Bank Code
     * @param string $bankCode Code
     * @return \Core\Financial\Cashbook\Bank\Model\BankModel
     */
    public function setBankCode($bankCode) {
        $this->bankCode = $bankCode;
        return $this;
    }

    /**
     * To Return Bank Account
     * @return string $bankAccount
     */
    public function getBankAccount() {
        return $this->bankAccount;
    }

    /**
     * To Set Bank Account
     * @param string $bankAccount Account
     * @return \Core\Financial\Cashbook\Bank\Model\BankModel
     */
    public function setBankAccount($bankAccount) {
        $this->bankAccount = $bankAccount;
        return $this;
    }

    /**
     * To Return Bank Cheque Sequence
     * @return string $bankChequeSequence
     */
    public function getBankChequeSequence() {
        return $this->bankChequeSequence;
    }

    /**
     * To Set Bank Cheque Sequence
     * @param string $bankChequeAccount Account
     * @return \Core\Financial\Cashbook\Bank\Model\BankModel
     */
    public function setBankChequeAccount($bankChequeSequence) {
        $this->bankChequeSequence = $bankChequeSequence;
        return $this;
    }

    /**
     * To Return Minimum Bank Value Validation
     * @return double $bankMinimumValue
     */
    public function getBankMinimumValue() {
        return $this->bankMinimumValue;
    }

    /**
     * To Set Minimum Bank Value Validation
     * @param double $bankMinimumValue Minimum Value
     * @return \Core\Financial\Cashbook\Bank\Model\BankModel
     */
    public function setBankMinimumValue($bankMinimumValue) {
        $this->bankMinimumValue = $bankMinimumValue;
        return $this;
    }

    /**
     * To Return OverDraft
     * @return double $bankOverDraft
     */
    public function getBankOverDraft() {
        return $this->bankOverDraft;
    }

    /**
     * To Set OverDraft
     * @param double $bankOverDraft Over Draft
     * @return \Core\Financial\Cashbook\Bank\Model\BankModel
     */
    public function setBankOverDraft($bankOverDraft) {
        $this->bankOverDraft = $bankOverDraft;
        return $this;
    }

    /**
     * To Return Description
     * @return string $bankDescription
     */
    public function getBankDescription() {
        return $this->bankDescription;
    }

    /**
     * To Set Description
     * @param string $bankDescription Description
     * @return \Core\Financial\Cashbook\Bank\Model\BankModel
     */
    public function setBankDescription($bankDescription) {
        $this->bankDescription = $bankDescription;
        return $this;
    }

    /**
     * To Return is Collection
     * @return bool $isCollection
     */
    public function getIsCollection() {
        return $this->isCollection;
    }

    /**
     * To Set is Collection
     * @param bool $isCollection Is Collection
     * @return \Core\Financial\Cashbook\Bank\Model\BankModel
     */
    public function setIsCollection($isCollection) {
        $this->isCollection = $isCollection;
        return $this;
    }

    /**
     * To Return is Payment Voucher
     * @return bool $isPaymentVoucher
     */
    public function getIsPaymentVoucher() {
        return $this->isPaymentVoucher;
    }

    /**
     * To Set is Payment Voucher
     * @param bool $isPaymentVoucher Is Voucher
     * @return \Core\Financial\Cashbook\Bank\Model\BankModel
     */
    public function setIsPaymentVoucher($isPaymentVoucher) {
        $this->isPaymentVoucher = $isPaymentVoucher;
        return $this;
    }

}

?>