<?php

namespace Core\Financial\AccountReceivable\InvoiceType\Model;

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
 * Class InvoiceType
 * This is invoiceType model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountReceivable\InvoiceType\Model;
 * @subpackage AccountReceivable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class InvoiceTypeModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $invoiceTypeId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Invoice Category
     * @var int
     */
    private $invoiceCategoryId;

    /**
     * Late Interest
     * @var int
     */
    private $lateInterestId;

    /**
     * Code
     * @var string
     */
    private $invoiceTypeCode;

    /**
     * Description
     * @var string
     */
    private $invoiceTypeDescription;

    /**
     * Credit Limit
     * @var float
     */
    private $invoiceTypeCreditLimit;

    /**
     * Interest Rate
     * @var float
     */
    private $invoiceTypeInterestRate;

    /**
     * Minimum Deposit
     * @var float
     */
    private $invoiceTypeMinimumDeposit;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('invoiceType');
        $this->setPrimaryKeyName('invoiceTypeId');
        $this->setMasterForeignKeyName('invoiceTypeId');
        $this->setFilterCharacter('invoiceTypeDescription');
        //$this->setFilterCharacter('invoiceTypeNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['invoiceTypeId'])) {
            $this->setInvoiceTypeId($this->strict($_POST ['invoiceTypeId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['invoiceCategoryId'])) {
            $this->setInvoiceCategoryId($this->strict($_POST ['invoiceCategoryId'], 'integer'));
        }
        if (isset($_POST ['lateInterestId'])) {
            $this->setLateInterestId($this->strict($_POST ['lateInterestId'], 'integer'));
        }
        if (isset($_POST ['invoiceTypeCode'])) {
            $this->setInvoiceTypeCode($this->strict($_POST ['invoiceTypeCode'], 'string'));
        }
        if (isset($_POST ['invoiceTypeDescription'])) {
            $this->setInvoiceTypeDescription($this->strict($_POST ['invoiceTypeDescription'], 'string'));
        }
        if (isset($_POST ['invoiceTypeCreditLimit'])) {
            $this->setInvoiceTypeCreditLimit($this->strict($_POST ['invoiceTypeCreditLimit'], 'float'));
        }
        if (isset($_POST ['invoiceTypeInterestRate'])) {
            $this->setInvoiceTypeInterestRate($this->strict($_POST ['invoiceTypeInterestRate'], 'float'));
        }
        if (isset($_POST ['invoiceTypeMinimumDeposit'])) {
            $this->setInvoiceTypeMinimumDeposit($this->strict($_POST ['invoiceTypeMinimumDeposit'], 'float'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['invoiceTypeId'])) {
            $this->setInvoiceTypeId($this->strict($_GET ['invoiceTypeId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['invoiceCategoryId'])) {
            $this->setInvoiceCategoryId($this->strict($_GET ['invoiceCategoryId'], 'integer'));
        }
        if (isset($_GET ['lateInterestId'])) {
            $this->setLateInterestId($this->strict($_GET ['lateInterestId'], 'integer'));
        }
        if (isset($_GET ['invoiceTypeCode'])) {
            $this->setInvoiceTypeCode($this->strict($_GET ['invoiceTypeCode'], 'string'));
        }
        if (isset($_GET ['invoiceTypeDescription'])) {
            $this->setInvoiceTypeDescription($this->strict($_GET ['invoiceTypeDescription'], 'string'));
        }
        if (isset($_GET ['invoiceTypeCreditLimit'])) {
            $this->setInvoiceTypeCreditLimit($this->strict($_GET ['invoiceTypeCreditLimit'], 'float'));
        }
        if (isset($_GET ['invoiceTypeInterestRate'])) {
            $this->setInvoiceTypeInterestRate($this->strict($_GET ['invoiceTypeInterestRate'], 'float'));
        }
        if (isset($_GET ['invoiceTypeMinimumDeposit'])) {
            $this->setInvoiceTypeMinimumDeposit($this->strict($_GET ['invoiceTypeMinimumDeposit'], 'float'));
        }
        if (isset($_GET ['invoiceTypeId'])) {
            $this->setTotal(count($_GET ['invoiceTypeId']));
            if (is_array($_GET ['invoiceTypeId'])) {
                $this->invoiceTypeId = array();
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
            if (isset($_GET ['invoiceTypeId'])) {
                $this->setInvoiceTypeId($this->strict($_GET ['invoiceTypeId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getInvoiceTypeId($i, 'array') . ",";
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
    public function getInvoiceTypeId($key, $type) {
        if ($type == 'single') {
            return $this->invoiceTypeId;
        } else {
            if ($type == 'array') {
                return $this->invoiceTypeId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getinvoiceTypeId ?")
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
     * @return \Core\Financial\AccountReceivable\InvoiceType\Model\InvoiceTypeModel
     */
    public function setInvoiceTypeId($value, $key, $type) {
        if ($type == 'single') {
            $this->invoiceTypeId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->invoiceTypeId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setinvoiceTypeId?")
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
     * @return \Core\Financial\AccountReceivable\InvoiceType\Model\InvoiceTypeModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
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
     * @return \Core\Financial\AccountReceivable\InvoiceType\Model\InvoiceTypeModel
     */
    public function setInvoiceCategoryId($invoiceCategoryId) {
        $this->invoiceCategoryId = $invoiceCategoryId;
        return $this;
    }

    /**
     * To Return Late Interest
     * @return int $lateInterestId
     */
    public function getLateInterestId() {
        return $this->lateInterestId;
    }

    /**
     * To Set Late Interest
     * @param int $lateInterestId Late Interest
     * @return \Core\Financial\AccountReceivable\InvoiceType\Model\InvoiceTypeModel
     */
    public function setLateInterestId($lateInterestId) {
        $this->lateInterestId = $lateInterestId;
        return $this;
    }

    /**
     * To Return Code
     * @return string $invoiceTypeCode
     */
    public function getInvoiceTypeCode() {
        return $this->invoiceTypeCode;
    }

    /**
     * To Set Code
     * @param string $invoiceTypeCode Code
     * @return \Core\Financial\AccountReceivable\InvoiceType\Model\InvoiceTypeModel
     */
    public function setInvoiceTypeCode($invoiceTypeCode) {
        $this->invoiceTypeCode = $invoiceTypeCode;
        return $this;
    }

    /**
     * To Return Description
     * @return string $invoiceTypeDescription
     */
    public function getInvoiceTypeDescription() {
        return $this->invoiceTypeDescription;
    }

    /**
     * To Set Description
     * @param string $invoiceTypeDescription Description
     * @return \Core\Financial\AccountReceivable\InvoiceType\Model\InvoiceTypeModel
     */
    public function setInvoiceTypeDescription($invoiceTypeDescription) {
        $this->invoiceTypeDescription = $invoiceTypeDescription;
        return $this;
    }

    /**
     * To Return  Credit Limit
     * @return float $invoiceTypeCreditLimit
     */
    public function getInvoiceTypeCreditLimit() {
        return $this->invoiceTypeCreditLimit;
    }

    /**
     * To Set Credit Limit
     * @param float $invoiceTypeCreditLimit Credit Limit
     * @return \Core\Financial\AccountReceivable\InvoiceType\Model\InvoiceTypeModel
     */
    public function setInvoiceTypeCreditLimit($invoiceTypeCreditLimit) {
        $this->invoiceTypeCreditLimit = $invoiceTypeCreditLimit;
        return $this;
    }

    /**
     * To Return  Interest Rate
     * @return float $invoiceTypeInterestRate
     */
    public function getInvoiceTypeInterestRate() {
        return $this->invoiceTypeInterestRate;
    }

    /**
     * To Set Interest Rate
     * @param float $invoiceTypeInterestRate Interest Rate
     * @return \Core\Financial\AccountReceivable\InvoiceType\Model\InvoiceTypeModel
     */
    public function setInvoiceTypeInterestRate($invoiceTypeInterestRate) {
        $this->invoiceTypeInterestRate = $invoiceTypeInterestRate;
        return $this;
    }

    /**
     * To Return  Minimum Deposit
     * @return float $invoiceTypeMinimumDeposit
     */
    public function getInvoiceTypeMinimumDeposit() {
        return $this->invoiceTypeMinimumDeposit;
    }

    /**
     * To Set Minimum Deposit
     * @param float $invoiceTypeMinimumDeposit Minimum Deposit
     * @return \Core\Financial\AccountReceivable\InvoiceType\Model\InvoiceTypeModel
     */
    public function setInvoiceTypeMinimumDeposit($invoiceTypeMinimumDeposit) {
        $this->invoiceTypeMinimumDeposit = $invoiceTypeMinimumDeposit;
        return $this;
    }

}

?>