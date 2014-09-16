<?php

namespace Core\Financial\AccountReceivable\SalesOrder\Model;

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
 * Class SalesOrder
 * This is salesOrder model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountReceivable\SalesOrder\Model;
 * @subpackage AccountReceivable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class SalesOrderModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $salesOrderId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Type
     * @var int
     */
    private $salesOrderTypeId;

    /**
     * Collection Type
     * @var int
     */
    private $collectionTypeId;

    /**
     * Business Partner
     * @var int
     */
    private $businessPartnerId;

    /**
     * Warehouse
     * @var int
     */
    private $warehouseId;

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
     * Cheque Number
     * @var string
     */
    private $chequeNumber;

    /**
     * Bank In Slip   Number
     * @var string
     */
    private $salesOrderBankInSlipNumber;

    /**
     * Bank In Slip   Date
     * @var string
     */
    private $salesOrderBankInSlipDate;

    /**
     * Date
     * @var string
     */
    private $salesOrderDate;

    /**
     * Amount
     * @var double
     */
    private $salesOrderAmount;

    /**
     * Description
     * @var string
     */
    private $salesOrderDescription;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('salesOrder');
        $this->setPrimaryKeyName('salesOrderId');
        $this->setMasterForeignKeyName('salesOrderId');
        $this->setFilterCharacter('salesOrderDescription');
        //$this->setFilterCharacter('salesOrderNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['salesOrderId'])) {
            $this->setSalesOrderId($this->strict($_POST ['salesOrderId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['salesOrderTypeId'])) {
            $this->setSalesOrderTypeId($this->strict($_POST ['salesOrderTypeId'], 'integer'));
        }
        if (isset($_POST ['collectionTypeId'])) {
            $this->setCollectionTypeId($this->strict($_POST ['collectionTypeId'], 'integer'));
        }
        if (isset($_POST ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_POST ['businessPartnerId'], 'integer'));
        }
        if (isset($_POST ['warehouseId'])) {
            $this->setWarehouseId($this->strict($_POST ['warehouseId'], 'integer'));
        }
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string'));
        }
        if (isset($_POST ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_POST ['referenceNumber'], 'string'));
        }
        if (isset($_POST ['chequeNumber'])) {
            $this->setChequeNumber($this->strict($_POST ['chequeNumber'], 'string'));
        }
        if (isset($_POST ['salesOrderBankInSlipNumber'])) {
            $this->setSalesOrderBankInSlipNumber($this->strict($_POST ['salesOrderBankInSlipNumber'], 'string'));
        }
        if (isset($_POST ['salesOrderBankInSlipDate'])) {
            $this->setSalesOrderBankInSlipDate($this->strict($_POST ['salesOrderBankInSlipDate'], 'string'));
        }
        if (isset($_POST ['salesOrderDate'])) {
            $this->setSalesOrderDate($this->strict($_POST ['salesOrderDate'], 'date'));
        }
        if (isset($_POST ['salesOrderAmount'])) {
            $this->setSalesOrderAmount($this->strict($_POST ['salesOrderAmount'], 'double'));
        }
        if (isset($_POST ['salesOrderDescription'])) {
            $this->setSalesOrderDescription($this->strict($_POST ['salesOrderDescription'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['salesOrderId'])) {
            $this->setSalesOrderId($this->strict($_GET ['salesOrderId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['salesOrderTypeId'])) {
            $this->setSalesOrderTypeId($this->strict($_GET ['salesOrderTypeId'], 'integer'));
        }
        if (isset($_GET ['collectionTypeId'])) {
            $this->setCollectionTypeId($this->strict($_GET ['collectionTypeId'], 'integer'));
        }
        if (isset($_GET ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_GET ['businessPartnerId'], 'integer'));
        }
        if (isset($_GET ['warehouseId'])) {
            $this->setWarehouseId($this->strict($_GET ['warehouseId'], 'integer'));
        }
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string'));
        }
        if (isset($_GET ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_GET ['referenceNumber'], 'string'));
        }
        if (isset($_GET ['chequeNumber'])) {
            $this->setChequeNumber($this->strict($_GET ['chequeNumber'], 'string'));
        }
        if (isset($_GET ['salesOrderBankInSlipNumber'])) {
            $this->setSalesOrderBankInSlipNumber($this->strict($_GET ['salesOrderBankInSlipNumber'], 'string'));
        }
        if (isset($_GET ['salesOrderBankInSlipDate'])) {
            $this->setSalesOrderBankInSlipDate($this->strict($_GET ['salesOrderBankInSlipDate'], 'string'));
        }
        if (isset($_GET ['salesOrderDate'])) {
            $this->setSalesOrderDate($this->strict($_GET ['salesOrderDate'], 'date'));
        }
        if (isset($_GET ['salesOrderAmount'])) {
            $this->setSalesOrderAmount($this->strict($_GET ['salesOrderAmount'], 'double'));
        }
        if (isset($_GET ['salesOrderDescription'])) {
            $this->setSalesOrderDescription($this->strict($_GET ['salesOrderDescription'], 'string'));
        }
        if (isset($_GET ['salesOrderId'])) {
            $this->setTotal(count($_GET ['salesOrderId']));
            if (is_array($_GET ['salesOrderId'])) {
                $this->salesOrderId = array();
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
            if (isset($_GET ['salesOrderId'])) {
                $this->setSalesOrderId($this->strict($_GET ['salesOrderId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getSalesOrderId($i, 'array') . ",";
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
    public function getSalesOrderId($key, $type) {
        if ($type == 'single') {
            return $this->salesOrderId;
        } else {
            if ($type == 'array') {
                return $this->salesOrderId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getsalesOrderId ?")
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
     * @return \Core\Financial\AccountReceivable\SalesOrder\Model\SalesOrderModel
     */
    public function setSalesOrderId($value, $key, $type) {
        if ($type == 'single') {
            $this->salesOrderId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->salesOrderId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setsalesOrderId?")
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
     * @return \Core\Financial\AccountReceivable\SalesOrder\Model\SalesOrderModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Type
     * @return int $salesOrderTypeId
     */
    public function getSalesOrderTypeId() {
        return $this->salesOrderTypeId;
    }

    /**
     * To Set Type
     * @param int $salesOrderTypeId Type
     * @return \Core\Financial\AccountReceivable\SalesOrder\Model\SalesOrderModel
     */
    public function setSalesOrderTypeId($salesOrderTypeId) {
        $this->salesOrderTypeId = $salesOrderTypeId;
        return $this;
    }

    /**
     * To Return Collection Type
     * @return int $collectionTypeId
     */
    public function getCollectionTypeId() {
        return $this->collectionTypeId;
    }

    /**
     * To Set Collection Type
     * @param int $collectionTypeId Collection Type
     * @return \Core\Financial\AccountReceivable\SalesOrder\Model\SalesOrderModel
     */
    public function setCollectionTypeId($collectionTypeId) {
        $this->collectionTypeId = $collectionTypeId;
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
     * @return \Core\Financial\AccountReceivable\SalesOrder\Model\SalesOrderModel
     */
    public function setBusinessPartnerId($businessPartnerId) {
        $this->businessPartnerId = $businessPartnerId;
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
     * @return \Core\Financial\AccountReceivable\SalesOrder\Model\SalesOrderModel
     */
    public function setWarehouseId($warehouseId) {
        $this->warehouseId = $warehouseId;
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
     * @return \Core\Financial\AccountReceivable\SalesOrder\Model\SalesOrderModel
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
     * @return \Core\Financial\AccountReceivable\SalesOrder\Model\SalesOrderModel
     */
    public function setReferenceNumber($referenceNumber) {
        $this->referenceNumber = $referenceNumber;
        return $this;
    }

    /**
     * To Return Cheque Number
     * @return string $chequeNumber
     */
    public function getChequeNumber() {
        return $this->chequeNumber;
    }

    /**
     * To Set Cheque Number
     * @param string $chequeNumber Cheque Number
     * @return \Core\Financial\AccountReceivable\SalesOrder\Model\SalesOrderModel
     */
    public function setChequeNumber($chequeNumber) {
        $this->chequeNumber = $chequeNumber;
        return $this;
    }

    /**
     * To Return Bank In Slip Number
     * @return string $salesOrderBankInSlipNumber
     */
    public function getSalesOrderBankInSlipNumber() {
        return $this->salesOrderBankInSlipNumber;
    }

    /**
     * To Set Bank In Slip Number
     * @param string $salesOrderBankInSlipNumber Bank In Slip Number
     * @return \Core\Financial\AccountReceivable\SalesOrder\Model\SalesOrderModel
     */
    public function setSalesOrderBankInSlipNumber($salesOrderBankInSlipNumber) {
        $this->salesOrderBankInSlipNumber = $salesOrderBankInSlipNumber;
        return $this;
    }

    /**
     * To Return Bank In Slip Date
     * @return string $salesOrderBankInSlipDate
     */
    public function getSalesOrderBankInSlipDate() {
        return $this->salesOrderBankInSlipDate;
    }

    /**
     * To Set Bank In Slip Date
     * @param string $salesOrderBankInSlipDate Bank In Slip Date
     * @return \Core\Financial\AccountReceivable\SalesOrder\Model\SalesOrderModel
     */
    public function setSalesOrderBankInSlipDate($salesOrderBankInSlipDate) {
        $this->salesOrderBankInSlipDate = $salesOrderBankInSlipDate;
        return $this;
    }

    /**
     * To Return Date
     * @return string $salesOrderDate
     */
    public function getSalesOrderDate() {
        return $this->salesOrderDate;
    }

    /**
     * To Set Date
     * @param string $salesOrderDate Date
     * @return \Core\Financial\AccountReceivable\SalesOrder\Model\SalesOrderModel
     */
    public function setSalesOrderDate($salesOrderDate) {
        $this->salesOrderDate = $salesOrderDate;
        return $this;
    }

    /**
     * To Return Amount
     * @return double $salesOrderAmount
     */
    public function getSalesOrderAmount() {
        return $this->salesOrderAmount;
    }

    /**
     * To Set Amount
     * @param double $salesOrderAmount Amount
     * @return \Core\Financial\AccountReceivable\SalesOrder\Model\SalesOrderModel
     */
    public function setSalesOrderAmount($salesOrderAmount) {
        $this->salesOrderAmount = $salesOrderAmount;
        return $this;
    }

    /**
     * To Return Description
     * @return string $salesOrderDescription
     */
    public function getSalesOrderDescription() {
        return $this->salesOrderDescription;
    }

    /**
     * To Set Description
     * @param string $salesOrderDescription Description
     * @return \Core\Financial\AccountReceivable\SalesOrder\Model\SalesOrderModel
     */
    public function setSalesOrderDescription($salesOrderDescription) {
        $this->salesOrderDescription = $salesOrderDescription;
        return $this;
    }

}

?>