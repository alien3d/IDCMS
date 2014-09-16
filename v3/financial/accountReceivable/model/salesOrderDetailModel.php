<?php

namespace Core\Financial\AccountReceivable\SalesOrderDetail\Model;

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
 * Class SalesOrderDetail
 * This is salesOrderDetail model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountReceivable\SalesOrderDetail\Model;
 * @subpackage AccountReceivable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class SalesOrderDetailModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $salesOrderDetailId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Sales Order
     * @var int
     */
    private $salesOrderId;

    /**
     * Country
     * @var int
     */
    private $countryId;

    /**
     * Product
     * @var int
     */
    private $productId;

    /**
     * Unit Measurement
     * @var int
     */
    private $unitOfMeasurementId;

    /**
     * Journal Number
     * @var string
     */
    private $journalNumber;

    /**
     * Quantity
     * @var double
     */
    private $salesOrderDetailQuantity;

    /**
     * Unit Price
     * @var double
     */
    private $salesOrderDetailUnitPrice;

    /**
     * Total Price
     * @var double
     */
    private $salesOrderDetailTotalPrice;

    /**
     * Description
     * @var string
     */
    private $salesOrderDetailDescription;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('salesOrderDetail');
        $this->setPrimaryKeyName('salesOrderDetailId');
        $this->setMasterForeignKeyName('salesOrderDetailId');
        $this->setFilterCharacter('salesOrderDetailDescription');
        //$this->setFilterCharacter('salesOrderDetailNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['salesOrderDetailId'])) {
            $this->setSalesOrderDetailId($this->strict($_POST ['salesOrderDetailId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['salesOrderId'])) {
            $this->setSalesOrderId($this->strict($_POST ['salesOrderId'], 'integer'));
        }
        if (isset($_POST ['countryId'])) {
            $this->setCountryId($this->strict($_POST ['countryId'], 'integer'));
        }
        if (isset($_POST ['productId'])) {
            $this->setProductId($this->strict($_POST ['productId'], 'integer'));
        }
        if (isset($_POST ['unitOfMeasurementId'])) {
            $this->setUnitOfMeasurementId($this->strict($_POST ['unitOfMeasurementId'], 'integer'));
        }
        if (isset($_POST ['journalNumber'])) {
            $this->setJournalNumber($this->strict($_POST ['journalNumber'], 'string'));
        }
        if (isset($_POST ['salesOrderDetailQuantity'])) {
            $this->setSalesOrderDetailQuantity($this->strict($_POST ['salesOrderDetailQuantity'], 'double'));
        }
        if (isset($_POST ['salesOrderDetailUnitPrice'])) {
            $this->setSalesOrderDetailUnitPrice($this->strict($_POST ['salesOrderDetailUnitPrice'], 'double'));
        }
        if (isset($_POST ['salesOrderDetailTotalPrice'])) {
            $this->setSalesOrderDetailTotalPrice($this->strict($_POST ['salesOrderDetailTotalPrice'], 'double'));
        }
        if (isset($_POST ['salesOrderDetailDescription'])) {
            $this->setSalesOrderDetailDescription($this->strict($_POST ['salesOrderDetailDescription'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['salesOrderDetailId'])) {
            $this->setSalesOrderDetailId($this->strict($_GET ['salesOrderDetailId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['salesOrderId'])) {
            $this->setSalesOrderId($this->strict($_GET ['salesOrderId'], 'integer'));
        }
        if (isset($_GET ['countryId'])) {
            $this->setCountryId($this->strict($_GET ['countryId'], 'integer'));
        }
        if (isset($_GET ['productId'])) {
            $this->setProductId($this->strict($_GET ['productId'], 'integer'));
        }
        if (isset($_GET ['unitOfMeasurementId'])) {
            $this->setUnitOfMeasurementId($this->strict($_GET ['unitOfMeasurementId'], 'integer'));
        }
        if (isset($_GET ['journalNumber'])) {
            $this->setJournalNumber($this->strict($_GET ['journalNumber'], 'string'));
        }
        if (isset($_GET ['salesOrderDetailQuantity'])) {
            $this->setSalesOrderDetailQuantity($this->strict($_GET ['salesOrderDetailQuantity'], 'double'));
        }
        if (isset($_GET ['salesOrderDetailUnitPrice'])) {
            $this->setSalesOrderDetailUnitPrice($this->strict($_GET ['salesOrderDetailUnitPrice'], 'double'));
        }
        if (isset($_GET ['salesOrderDetailTotalPrice'])) {
            $this->setSalesOrderDetailTotalPrice($this->strict($_GET ['salesOrderDetailTotalPrice'], 'double'));
        }
        if (isset($_GET ['salesOrderDetailDescription'])) {
            $this->setSalesOrderDetailDescription($this->strict($_GET ['salesOrderDetailDescription'], 'string'));
        }
        if (isset($_GET ['salesOrderDetailId'])) {
            $this->setTotal(count($_GET ['salesOrderDetailId']));
            if (is_array($_GET ['salesOrderDetailId'])) {
                $this->salesOrderDetailId = array();
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
            if (isset($_GET ['salesOrderDetailId'])) {
                $this->setSalesOrderDetailId($this->strict($_GET ['salesOrderDetailId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getSalesOrderDetailId($i, 'array') . ",";
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
    public function getSalesOrderDetailId($key, $type) {
        if ($type == 'single') {
            return $this->salesOrderDetailId;
        } else {
            if ($type == 'array') {
                return $this->salesOrderDetailId [$key];
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:getsalesOrderDetailId ?"
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
     * @return \Core\Financial\AccountReceivable\SalesOrderDetail\Model\SalesOrderDetailModel
     */
    public function setSalesOrderDetailId($value, $key, $type) {
        if ($type == 'single') {
            $this->salesOrderDetailId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->salesOrderDetailId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:setsalesOrderDetailId?"
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
     * @return \Core\Financial\AccountReceivable\SalesOrderDetail\Model\SalesOrderDetailModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Sales Order
     * @return int $salesOrderId
     */
    public function getSalesOrderId() {
        return $this->salesOrderId;
    }

    /**
     * To Set Sales Order
     * @param int $salesOrderId Sales Order
     * @return \Core\Financial\AccountReceivable\SalesOrderDetail\Model\SalesOrderDetailModel
     */
    public function setSalesOrderId($salesOrderId) {
        $this->salesOrderId = $salesOrderId;
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
     * @return \Core\Financial\AccountReceivable\SalesOrderDetail\Model\SalesOrderDetailModel
     */
    public function setCountryId($countryId) {
        $this->countryId = $countryId;
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
     * @return \Core\Financial\AccountReceivable\SalesOrderDetail\Model\SalesOrderDetailModel
     */
    public function setProductId($productId) {
        $this->productId = $productId;
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
     * @return \Core\Financial\AccountReceivable\SalesOrderDetail\Model\SalesOrderDetailModel
     */
    public function setUnitOfMeasurementId($unitOfMeasurementId) {
        $this->unitOfMeasurementId = $unitOfMeasurementId;
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
     * @return \Core\Financial\AccountReceivable\SalesOrderDetail\Model\SalesOrderDetailModel
     */
    public function setJournalNumber($journalNumber) {
        $this->journalNumber = $journalNumber;
        return $this;
    }

    /**
     * To Return Quantity
     * @return double $salesOrderDetailQuantity
     */
    public function getSalesOrderDetailQuantity() {
        return $this->salesOrderDetailQuantity;
    }

    /**
     * To Set Quantity
     * @param double $salesOrderDetailQuantity Quantity
     * @return \Core\Financial\AccountReceivable\SalesOrderDetail\Model\SalesOrderDetailModel
     */
    public function setSalesOrderDetailQuantity($salesOrderDetailQuantity) {
        $this->salesOrderDetailQuantity = $salesOrderDetailQuantity;
        return $this;
    }

    /**
     * To Return Unit Price
     * @return double $salesOrderDetailUnitPrice
     */
    public function getSalesOrderDetailUnitPrice() {
        return $this->salesOrderDetailUnitPrice;
    }

    /**
     * To Set Unit Price
     * @param double $salesOrderDetailUnitPrice Unit Price
     * @return \Core\Financial\AccountReceivable\SalesOrderDetail\Model\SalesOrderDetailModel
     */
    public function setSalesOrderDetailUnitPrice($salesOrderDetailUnitPrice) {
        $this->salesOrderDetailUnitPrice = $salesOrderDetailUnitPrice;
        return $this;
    }

    /**
     * To Return Total Price
     * @return double $salesOrderDetailTotalPrice
     */
    public function getSalesOrderDetailTotalPrice() {
        return $this->salesOrderDetailTotalPrice;
    }

    /**
     * To Set Total Price
     * @param double $salesOrderDetailTotalPrice Total Price
     * @return \Core\Financial\AccountReceivable\SalesOrderDetail\Model\SalesOrderDetailModel
     */
    public function setSalesOrderDetailTotalPrice($salesOrderDetailTotalPrice) {
        $this->salesOrderDetailTotalPrice = $salesOrderDetailTotalPrice;
        return $this;
    }

    /**
     * To Return Description
     * @return string $salesOrderDetailDescription
     */
    public function getSalesOrderDetailDescription() {
        return $this->salesOrderDetailDescription;
    }

    /**
     * To Set Description
     * @param string $salesOrderDetailDescription Description
     * @return \Core\Financial\AccountReceivable\SalesOrderDetail\Model\SalesOrderDetailModel
     */
    public function setSalesOrderDetailDescription($salesOrderDetailDescription) {
        $this->salesOrderDetailDescription = $salesOrderDetailDescription;
        return $this;
    }

}

?>