<?php

namespace Core\Financial\AccountReceivable\InvoiceServiceDetail\Model;

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
 * Class InvoiceServiceDetail
 * This is invoiceServiceDetail model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountReceivable\InvoiceServiceDetail\Model;
 * @subpackage AccountReceivable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class InvoiceServiceDetailModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $invoiceServiceDetailId;

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
     * Business Partner
     * @var int
     */
    private $businessPartnerId;

    /**
     * Description
     * @var int
     */
    private $invoiceServiceDetailDescription;

    /**
     * Discount Percent
     * @var double
     */
    private $invoiceServiceDetailDiscountPercent;

    /**
     * Discount Amount
     * @var double
     */
    private $invoiceServiceDetailDiscountAmount;

    /**
     * Cost Amount
     * @var double
     */
    private $invoiceServiceDetailCostAmount;

    /**
     * Amount
     * @var double
     */
    private $invoiceServiceDetailAmount;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('invoiceServiceDetail');
        $this->setPrimaryKeyName('invoiceServiceDetailId');
        $this->setMasterForeignKeyName('invoiceServiceDetailId');
        $this->setFilterCharacter('invoiceServiceDetailDescription');
        //$this->setFilterCharacter('invoiceServiceDetailNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['invoiceServiceDetailId'])) {
            $this->setInvoiceServiceDetailId($this->strict($_POST ['invoiceServiceDetailId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['invoiceId'])) {
            $this->setInvoiceId($this->strict($_POST ['invoiceId'], 'int'));
        }
        if (isset($_POST ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_POST ['businessPartnerId'], 'int'));
        }
        if (isset($_POST ['invoiceServiceDetailDescription'])) {
            $this->setInvoiceServiceDetailDescription($this->strict($_POST ['invoiceServiceDetailDescription'], 'int'));
        }
        if (isset($_POST ['invoiceServiceDetailDiscountPercent'])) {
            $this->setInvoiceServiceDetailDiscountPercent(
                    $this->strict($_POST ['invoiceServiceDetailDiscountPercent'], 'double')
            );
        }
        if (isset($_POST ['invoiceServiceDetailDiscountAmount'])) {
            $this->setInvoiceServiceDetailDiscountAmount(
                    $this->strict($_POST ['invoiceServiceDetailDiscountAmount'], 'double')
            );
        }
        if (isset($_POST ['invoiceServiceDetailCostAmount'])) {
            $this->setInvoiceServiceDetailCostAmount(
                    $this->strict($_POST ['invoiceServiceDetailCostAmount'], 'double')
            );
        }
        if (isset($_POST ['invoiceServiceDetailAmount'])) {
            $this->setInvoiceServiceDetailAmount($this->strict($_POST ['invoiceServiceDetailAmount'], 'double'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['invoiceServiceDetailId'])) {
            $this->setInvoiceServiceDetailId($this->strict($_GET ['invoiceServiceDetailId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['invoiceId'])) {
            $this->setInvoiceId($this->strict($_GET ['invoiceId'], 'int'));
        }
        if (isset($_GET ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_GET ['businessPartnerId'], 'int'));
        }
        if (isset($_GET ['invoiceServiceDetailDescription'])) {
            $this->setInvoiceServiceDetailDescription($this->strict($_GET ['invoiceServiceDetailDescription'], 'int'));
        }
        if (isset($_GET ['invoiceServiceDetailDiscountPercent'])) {
            $this->setInvoiceServiceDetailDiscountPercent(
                    $this->strict($_GET ['invoiceServiceDetailDiscountPercent'], 'double')
            );
        }
        if (isset($_GET ['invoiceServiceDetailDiscountAmount'])) {
            $this->setInvoiceServiceDetailDiscountAmount(
                    $this->strict($_GET ['invoiceServiceDetailDiscountAmount'], 'double')
            );
        }
        if (isset($_GET ['invoiceServiceDetailCostAmount'])) {
            $this->setInvoiceServiceDetailCostAmount($this->strict($_GET ['invoiceServiceDetailCostAmount'], 'double'));
        }
        if (isset($_GET ['invoiceServiceDetailAmount'])) {
            $this->setInvoiceServiceDetailAmount($this->strict($_GET ['invoiceServiceDetailAmount'], 'double'));
        }
        if (isset($_GET ['invoiceServiceDetailId'])) {
            $this->setTotal(count($_GET ['invoiceServiceDetailId']));
            if (is_array($_GET ['invoiceServiceDetailId'])) {
                $this->invoiceServiceDetailId = array();
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
            if (isset($_GET ['invoiceServiceDetailId'])) {
                $this->setInvoiceServiceDetailId(
                        $this->strict($_GET ['invoiceServiceDetailId'] [$i], 'numeric'), $i, 'array'
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
            $primaryKeyAll .= $this->getInvoiceServiceDetailId($i, 'array') . ",";
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
    public function getInvoiceServiceDetailId($key, $type) {
        if ($type == 'single') {
            return $this->invoiceServiceDetailId;
        } else {
            if ($type == 'array') {
                return $this->invoiceServiceDetailId [$key];
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:getinvoiceServiceDetailId ?"
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
     * @return \Core\Financial\AccountReceivable\InvoiceServiceDetail\Model\InvoiceServiceDetailModel
     */
    public function setInvoiceServiceDetailId($value, $key, $type) {
        if ($type == 'single') {
            $this->invoiceServiceDetailId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->invoiceServiceDetailId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:setinvoiceServiceDetailId?"
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
     * @return \Core\Financial\AccountReceivable\InvoiceServiceDetail\Model\InvoiceServiceDetailModel
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
     * @return \Core\Financial\AccountReceivable\InvoiceServiceDetail\Model\InvoiceServiceDetailModel
     */
    public function setInvoiceId($invoiceId) {
        $this->invoiceId = $invoiceId;
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
     * @return \Core\Financial\AccountReceivable\InvoiceServiceDetail\Model\InvoiceServiceDetailModel
     */
    public function setBusinessPartnerId($businessPartnerId) {
        $this->businessPartnerId = $businessPartnerId;
        return $this;
    }

    /**
     * To Return Description
     * @return int $invoiceServiceDetailDescription
     */
    public function getInvoiceServiceDetailDescription() {
        return $this->invoiceServiceDetailDescription;
    }

    /**
     * To Set Description
     * @param int $invoiceServiceDetailDescription Description
     * @return \Core\Financial\AccountReceivable\InvoiceServiceDetail\Model\InvoiceServiceDetailModel
     */
    public function setInvoiceServiceDetailDescription($invoiceServiceDetailDescription) {
        $this->invoiceServiceDetailDescription = $invoiceServiceDetailDescription;
        return $this;
    }

    /**
     * To Return Discount Percent
     * @return double $invoiceServiceDetailDiscountPercent
     */
    public function getInvoiceServiceDetailDiscountPercent() {
        return $this->invoiceServiceDetailDiscountPercent;
    }

    /**
     * To Set Discount Percent
     * @param double $invoiceServiceDetailDiscountPercent Discount Percent
     * @return \Core\Financial\AccountReceivable\InvoiceServiceDetail\Model\InvoiceServiceDetailModel
     */
    public function setInvoiceServiceDetailDiscountPercent($invoiceServiceDetailDiscountPercent) {
        $this->invoiceServiceDetailDiscountPercent = $invoiceServiceDetailDiscountPercent;
        return $this;
    }

    /**
     * To Return Discount Amount
     * @return double $invoiceServiceDetailDiscountAmount
     */
    public function getInvoiceServiceDetailDiscountAmount() {
        return $this->invoiceServiceDetailDiscountAmount;
    }

    /**
     * To Set Discount Amount
     * @param double $invoiceServiceDetailDiscountAmount Discount Amount
     * @return \Core\Financial\AccountReceivable\InvoiceServiceDetail\Model\InvoiceServiceDetailModel
     */
    public function setInvoiceServiceDetailDiscountAmount($invoiceServiceDetailDiscountAmount) {
        $this->invoiceServiceDetailDiscountAmount = $invoiceServiceDetailDiscountAmount;
        return $this;
    }

    /**
     * To Return Cost Amount
     * @return double $invoiceServiceDetailCostAmount
     */
    public function getInvoiceServiceDetailCostAmount() {
        return $this->invoiceServiceDetailCostAmount;
    }

    /**
     * To Set Cost Amount
     * @param double $invoiceServiceDetailCostAmount Cost Amount
     * @return \Core\Financial\AccountReceivable\InvoiceServiceDetail\Model\InvoiceServiceDetailModel
     */
    public function setInvoiceServiceDetailCostAmount($invoiceServiceDetailCostAmount) {
        $this->invoiceServiceDetailCostAmount = $invoiceServiceDetailCostAmount;
        return $this;
    }

    /**
     * To Return Amount
     * @return double $invoiceServiceDetailAmount
     */
    public function getInvoiceServiceDetailAmount() {
        return $this->invoiceServiceDetailAmount;
    }

    /**
     * To Set Amount
     * @param double $invoiceServiceDetailAmount Amount
     * @return \Core\Financial\AccountReceivable\InvoiceServiceDetail\Model\InvoiceServiceDetailModel
     */
    public function setInvoiceServiceDetailAmount($invoiceServiceDetailAmount) {
        $this->invoiceServiceDetailAmount = $invoiceServiceDetailAmount;
        return $this;
    }

}

?>