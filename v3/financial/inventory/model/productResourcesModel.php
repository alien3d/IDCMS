<?php

namespace Core\Financial\Inventory\ProductResources\Model;

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
require_once ($newFakeDocumentRoot . "library/class/classValidation.php");

/**
 * Class ProductResources
 * This is productResources model file.This is to ensure strict setting enable for all variable enter to database 
 * 
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\Financial\Inventory\ProductResources\Model;
 * @subpackage Inventory 
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ProductResourcesModel extends ValidationClass {

    /**
     * Primary Key
     * @var int 
     */
    private $productResourcesId;

    /**
     * Company
     * @var int 
     */
    private $companyId;

    /**
     * Product Batch
     * @var int 
     */
    private $productBatchId;

    /**
     * Invoice
     * @var int 
     */
    private $invoiceId;

    /**
     * Task
     * @var string 
     */
    private $productResourcesTask;

    /**
     * Estimated Date
     * @var date 
     */
    private $productResourcesEstimatedDate;

    /**
     * Actual Date
     * @var date 
     */
    private $productResourcesActualDate;

    /**
     * Estimated Cost
     * @var double 
     */
    private $productResourcesEstimatedEmployeeCost;

    /**
     * Actual Cost
     * @var double 
     */
    private $productResourcesActualEmployeeCost;

    /**
     * Estimated Cost
     * @var double 
     */
    private $productResourcesEstimatedMachineCost;

    /**
     * Actual Cost
     * @var double 
     */
    private $productResourcesActualMachineCost;

    /**
     * Estimated Cost
     * @var double 
     */
    private $productResourcesEstimatedAdditionalCost;

    /**
     * Actual Cost
     * @var double 
     */
    private $productResourcesActualAdditionalCost;

    /**
     * Estimated Bill Of Material   Cost
     * @var double 
     */
    private $productResourcesEstimatedBillOfMaterialCost;

    /**
     * Actual Bill Of Material   Cost
     * @var double 
     */
    private $productResourcesActualBillOfMaterialCost;

    /**
     * Estimated Cost
     * @var double 
     */
    private $productResourcesEstimatedTotalCost;

    /**
     * Actual Cost
     * @var double 
     */
    private $productResourcesActualTotalCost;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('productResources');
        $this->setPrimaryKeyName('productResourcesId');
        $this->setMasterForeignKeyName('productResourcesId');
        $this->setFilterCharacter('productResourcesDescription');
        //$this->setFilterCharacter('productResourcesNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['productResourcesId'])) {
            $this->setProductResourcesId($this->strict($_POST ['productResourcesId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['productBatchId'])) {
            $this->setProductBatchId($this->strict($_POST ['productBatchId'], 'int'));
        }
        if (isset($_POST ['invoiceId'])) {
            $this->setInvoiceId($this->strict($_POST ['invoiceId'], 'int'));
        }
        if (isset($_POST ['productResourcesTask'])) {
            $this->setProductResourcesTask($this->strict($_POST ['productResourcesTask'], 'string'));
        }
        if (isset($_POST ['productResourcesEstimatedDate'])) {
            $this->setProductResourcesEstimatedDate($this->strict($_POST ['productResourcesEstimatedDate'], 'date'));
        }
        if (isset($_POST ['productResourcesActualDate'])) {
            $this->setProductResourcesActualDate($this->strict($_POST ['productResourcesActualDate'], 'date'));
        }
        if (isset($_POST ['productResourcesEstimatedEmployeeCost'])) {
            $this->setProductResourcesEstimatedEmployeeCost($this->strict($_POST ['productResourcesEstimatedEmployeeCost'], 'double'));
        }
        if (isset($_POST ['productResourcesActualEmployeeCost'])) {
            $this->setProductResourcesActualEmployeeCost($this->strict($_POST ['productResourcesActualEmployeeCost'], 'double'));
        }
        if (isset($_POST ['productResourcesEstimatedMachineCost'])) {
            $this->setProductResourcesEstimatedMachineCost($this->strict($_POST ['productResourcesEstimatedMachineCost'], 'double'));
        }
        if (isset($_POST ['productResourcesActualMachineCost'])) {
            $this->setProductResourcesActualMachineCost($this->strict($_POST ['productResourcesActualMachineCost'], 'double'));
        }
        if (isset($_POST ['productResourcesEstimatedAdditionalCost'])) {
            $this->setProductResourcesEstimatedAdditionalCost($this->strict($_POST ['productResourcesEstimatedAdditionalCost'], 'double'));
        }
        if (isset($_POST ['productResourcesActualAdditionalCost'])) {
            $this->setProductResourcesActualAdditionalCost($this->strict($_POST ['productResourcesActualAdditionalCost'], 'double'));
        }
        if (isset($_POST ['productResourcesEstimatedBillOfMaterialCost'])) {
            $this->setProductResourcesEstimatedBillOfMaterialCost($this->strict($_POST ['productResourcesEstimatedBillOfMaterialCost'], 'double'));
        }
        if (isset($_POST ['productResourcesActualBillOfMaterialCost'])) {
            $this->setProductResourcesActualBillOfMaterialCost($this->strict($_POST ['productResourcesActualBillOfMaterialCost'], 'double'));
        }
        if (isset($_POST ['productResourcesEstimatedTotalCost'])) {
            $this->setProductResourcesEstimatedTotalCost($this->strict($_POST ['productResourcesEstimatedTotalCost'], 'double'));
        }
        if (isset($_POST ['productResourcesActualTotalCost'])) {
            $this->setProductResourcesActualTotalCost($this->strict($_POST ['productResourcesActualTotalCost'], 'double'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['productResourcesId'])) {
            $this->setProductResourcesId($this->strict($_GET ['productResourcesId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['productBatchId'])) {
            $this->setProductBatchId($this->strict($_GET ['productBatchId'], 'int'));
        }
        if (isset($_GET ['invoiceId'])) {
            $this->setInvoiceId($this->strict($_GET ['invoiceId'], 'int'));
        }
        if (isset($_GET ['productResourcesTask'])) {
            $this->setProductResourcesTask($this->strict($_GET ['productResourcesTask'], 'string'));
        }
        if (isset($_GET ['productResourcesEstimatedDate'])) {
            $this->setProductResourcesEstimatedDate($this->strict($_GET ['productResourcesEstimatedDate'], 'date'));
        }
        if (isset($_GET ['productResourcesActualDate'])) {
            $this->setProductResourcesActualDate($this->strict($_GET ['productResourcesActualDate'], 'date'));
        }
        if (isset($_GET ['productResourcesEstimatedEmployeeCost'])) {
            $this->setProductResourcesEstimatedEmployeeCost($this->strict($_GET ['productResourcesEstimatedEmployeeCost'], 'double'));
        }
        if (isset($_GET ['productResourcesActualEmployeeCost'])) {
            $this->setProductResourcesActualEmployeeCost($this->strict($_GET ['productResourcesActualEmployeeCost'], 'double'));
        }
        if (isset($_GET ['productResourcesEstimatedMachineCost'])) {
            $this->setProductResourcesEstimatedMachineCost($this->strict($_GET ['productResourcesEstimatedMachineCost'], 'double'));
        }
        if (isset($_GET ['productResourcesActualMachineCost'])) {
            $this->setProductResourcesActualMachineCost($this->strict($_GET ['productResourcesActualMachineCost'], 'double'));
        }
        if (isset($_GET ['productResourcesEstimatedAdditionalCost'])) {
            $this->setProductResourcesEstimatedAdditionalCost($this->strict($_GET ['productResourcesEstimatedAdditionalCost'], 'double'));
        }
        if (isset($_GET ['productResourcesActualAdditionalCost'])) {
            $this->setProductResourcesActualAdditionalCost($this->strict($_GET ['productResourcesActualAdditionalCost'], 'double'));
        }
        if (isset($_GET ['productResourcesEstimatedBillOfMaterialCost'])) {
            $this->setProductResourcesEstimatedBillOfMaterialCost($this->strict($_GET ['productResourcesEstimatedBillOfMaterialCost'], 'double'));
        }
        if (isset($_GET ['productResourcesActualBillOfMaterialCost'])) {
            $this->setProductResourcesActualBillOfMaterialCost($this->strict($_GET ['productResourcesActualBillOfMaterialCost'], 'double'));
        }
        if (isset($_GET ['productResourcesEstimatedTotalCost'])) {
            $this->setProductResourcesEstimatedTotalCost($this->strict($_GET ['productResourcesEstimatedTotalCost'], 'double'));
        }
        if (isset($_GET ['productResourcesActualTotalCost'])) {
            $this->setProductResourcesActualTotalCost($this->strict($_GET ['productResourcesActualTotalCost'], 'double'));
        }
        if (isset($_GET ['productResourcesId'])) {
            $this->setTotal(count($_GET ['productResourcesId']));
            if (is_array($_GET ['productResourcesId'])) {
                $this->productResourcesId = array();
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
            if (isset($_GET ['productResourcesId'])) {
                $this->setProductResourcesId($this->strict($_GET ['productResourcesId'] [$i], 'numeric'), $i, 'array');
            }
            if (isset($_GET ['isDefault'])) {
                if ($_GET ['isDefault'] [$i] == 'true') {
                    $this->setIsDefault(1, $i, 'array');
                } else if ($_GET ['isDefault'] [$i] == 'false') {
                    $this->setIsDefault(0, $i, 'array');
                }
            }
            if (isset($_GET ['isNew'])) {
                if ($_GET ['isNew'] [$i] == 'true') {
                    $this->setIsNew(1, $i, 'array');
                } else if ($_GET ['isNew'] [$i] == 'false') {
                    $this->setIsNew(0, $i, 'array');
                }
            }
            if (isset($_GET ['isDraft'])) {
                if ($_GET ['isDraft'] [$i] == 'true') {
                    $this->setIsDraft(1, $i, 'array');
                } else if ($_GET ['isDraft'] [$i] == 'false') {
                    $this->setIsDraft(0, $i, 'array');
                }
            }
            if (isset($_GET ['isUpdate'])) {
                if ($_GET ['isUpdate'] [$i] == 'true') {
                    $this->setIsUpdate(1, $i, 'array');
                } if ($_GET ['isUpdate'] [$i] == 'false') {
                    $this->setIsUpdate(0, $i, 'array');
                }
            }
            if (isset($_GET ['isDelete'])) {
                if ($_GET ['isDelete'] [$i] == 'true') {
                    $this->setIsDelete(1, $i, 'array');
                } else if ($_GET ['isDelete'] [$i] == 'false') {
                    $this->setIsDelete(0, $i, 'array');
                }
            }
            if (isset($_GET ['isActive'])) {
                if ($_GET ['isActive'] [$i] == 'true') {
                    $this->setIsActive(1, $i, 'array');
                } else if ($_GET ['isActive'] [$i] == 'false') {
                    $this->setIsActive(0, $i, 'array');
                }
            }
            if (isset($_GET ['isApproved'])) {
                if ($_GET ['isApproved'] [$i] == 'true') {
                    $this->setIsApproved(1, $i, 'array');
                } else if ($_GET ['isApproved'] [$i] == 'false') {
                    $this->setIsApproved(0, $i, 'array');
                }
            }
            if (isset($_GET ['isReview'])) {
                if ($_GET ['isReview'] [$i] == 'true') {
                    $this->setIsReview(1, $i, 'array');
                } else if ($_GET ['isReview'] [$i] == 'false') {
                    $this->setIsReview(0, $i, 'array');
                }
            }
            if (isset($_GET ['isPost'])) {
                if ($_GET ['isPost'] [$i] == 'true') {
                    $this->setIsPost(1, $i, 'array');
                } else if ($_GET ['isPost'] [$i] == 'false') {
                    $this->setIsPost(0, $i, 'array');
                }
            }
            $primaryKeyAll .= $this->getProductResourcesId($i, 'array') . ",";
        }
        $this->setPrimaryKeyAll((substr($primaryKeyAll, 0, - 1)));
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
        } else if ($this->getVendor() == self::MSSQL) {
            $this->setExecuteTime("'" . date("Y-m-d H:i:s.u") . "'");
        } else if ($this->getVendor() == self::ORACLE) {
            $this->setExecuteTime("to_date('" . date("Y-m-d H:i:s") . "','YYYY-MM-DD HH24:MI:SS');");
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
     * @param array[int]int $key List Of Primary Key. 
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array' 
     * @return \Core\Financial\Inventory\ProductResources\Model\ProductResourcesModel
     */
    public function setProductResourcesId($value, $key, $type) {
        if ($type == 'single') {
            $this->productResourcesId = $value;
            return $this;
        } else if ($type == 'array') {
            $this->productResourcesId[$key] = $value;
            return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:setproductResourcesId?"));
            exit();
        }
    }

    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getProductResourcesId($key, $type) {
        if ($type == 'single') {
            return $this->productResourcesId;
        } else if ($type == 'array') {
            return $this->productResourcesId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:getproductResourcesId ?"));
            exit();
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
     * @return \Core\Financial\Inventory\ProductResources\Model\ProductResourcesModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Product Batch 
     * @return int $productBatchId
     */
    public function getProductBatchId() {
        return $this->productBatchId;
    }

    /**
     * To Set Product Batch 
     * @param int $productBatchId Product Batch 
     * @return \Core\Financial\Inventory\ProductResources\Model\ProductResourcesModel
     */
    public function setProductBatchId($productBatchId) {
        $this->productBatchId = $productBatchId;
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
     * @return \Core\Financial\Inventory\ProductResources\Model\ProductResourcesModel
     */
    public function setInvoiceId($invoiceId) {
        $this->invoiceId = $invoiceId;
        return $this;
    }

    /**
     * To Return Task 
     * @return string $productResourcesTask
     */
    public function getProductResourcesTask() {
        return $this->productResourcesTask;
    }

    /**
     * To Set Task 
     * @param string $productResourcesTask Task 
     * @return \Core\Financial\Inventory\ProductResources\Model\ProductResourcesModel
     */
    public function setProductResourcesTask($productResourcesTask) {
        $this->productResourcesTask = $productResourcesTask;
        return $this;
    }

    /**
     * To Return Estimated Date 
     * @return date $productResourcesEstimatedDate
     */
    public function getProductResourcesEstimatedDate() {
        return $this->productResourcesEstimatedDate;
    }

    /**
     * To Set Estimated Date 
     * @param date $productResourcesEstimatedDate Estimated Date 
     * @return \Core\Financial\Inventory\ProductResources\Model\ProductResourcesModel
     */
    public function setProductResourcesEstimatedDate($productResourcesEstimatedDate) {
        $this->productResourcesEstimatedDate = $productResourcesEstimatedDate;
        return $this;
    }

    /**
     * To Return Actual Date 
     * @return date $productResourcesActualDate
     */
    public function getProductResourcesActualDate() {
        return $this->productResourcesActualDate;
    }

    /**
     * To Set Actual Date 
     * @param date $productResourcesActualDate Actual Date 
     * @return \Core\Financial\Inventory\ProductResources\Model\ProductResourcesModel
     */
    public function setProductResourcesActualDate($productResourcesActualDate) {
        $this->productResourcesActualDate = $productResourcesActualDate;
        return $this;
    }

    /**
     * To Return Estimated Cost 
     * @return double $productResourcesEstimatedEmployeeCost
     */
    public function getProductResourcesEstimatedEmployeeCost() {
        return $this->productResourcesEstimatedEmployeeCost;
    }

    /**
     * To Set Estimated Cost 
     * @param double $productResourcesEstimatedEmployeeCost Estimated Cost 
     * @return \Core\Financial\Inventory\ProductResources\Model\ProductResourcesModel
     */
    public function setProductResourcesEstimatedEmployeeCost($productResourcesEstimatedEmployeeCost) {
        $this->productResourcesEstimatedEmployeeCost = $productResourcesEstimatedEmployeeCost;
        return $this;
    }

    /**
     * To Return Actual Cost 
     * @return double $productResourcesActualEmployeeCost
     */
    public function getProductResourcesActualEmployeeCost() {
        return $this->productResourcesActualEmployeeCost;
    }

    /**
     * To Set Actual Cost 
     * @param double $productResourcesActualEmployeeCost Actual Cost 
     * @return \Core\Financial\Inventory\ProductResources\Model\ProductResourcesModel
     */
    public function setProductResourcesActualEmployeeCost($productResourcesActualEmployeeCost) {
        $this->productResourcesActualEmployeeCost = $productResourcesActualEmployeeCost;
        return $this;
    }

    /**
     * To Return Estimated Cost 
     * @return double $productResourcesEstimatedMachineCost
     */
    public function getProductResourcesEstimatedMachineCost() {
        return $this->productResourcesEstimatedMachineCost;
    }

    /**
     * To Set Estimated Cost 
     * @param double $productResourcesEstimatedMachineCost Estimated Cost 
     * @return \Core\Financial\Inventory\ProductResources\Model\ProductResourcesModel
     */
    public function setProductResourcesEstimatedMachineCost($productResourcesEstimatedMachineCost) {
        $this->productResourcesEstimatedMachineCost = $productResourcesEstimatedMachineCost;
        return $this;
    }

    /**
     * To Return Actual Cost 
     * @return double $productResourcesActualMachineCost
     */
    public function getProductResourcesActualMachineCost() {
        return $this->productResourcesActualMachineCost;
    }

    /**
     * To Set Actual Cost 
     * @param double $productResourcesActualMachineCost Actual Cost 
     * @return \Core\Financial\Inventory\ProductResources\Model\ProductResourcesModel
     */
    public function setProductResourcesActualMachineCost($productResourcesActualMachineCost) {
        $this->productResourcesActualMachineCost = $productResourcesActualMachineCost;
        return $this;
    }

    /**
     * To Return Estimated Cost 
     * @return double $productResourcesEstimatedAdditionalCost
     */
    public function getProductResourcesEstimatedAdditionalCost() {
        return $this->productResourcesEstimatedAdditionalCost;
    }

    /**
     * To Set Estimated Cost 
     * @param double $productResourcesEstimatedAdditionalCost Estimated Cost 
     * @return \Core\Financial\Inventory\ProductResources\Model\ProductResourcesModel
     */
    public function setProductResourcesEstimatedAdditionalCost($productResourcesEstimatedAdditionalCost) {
        $this->productResourcesEstimatedAdditionalCost = $productResourcesEstimatedAdditionalCost;
        return $this;
    }

    /**
     * To Return Actual Cost 
     * @return double $productResourcesActualAdditionalCost
     */
    public function getProductResourcesActualAdditionalCost() {
        return $this->productResourcesActualAdditionalCost;
    }

    /**
     * To Set Actual Cost 
     * @param double $productResourcesActualAdditionalCost Actual Cost 
     * @return \Core\Financial\Inventory\ProductResources\Model\ProductResourcesModel
     */
    public function setProductResourcesActualAdditionalCost($productResourcesActualAdditionalCost) {
        $this->productResourcesActualAdditionalCost = $productResourcesActualAdditionalCost;
        return $this;
    }

    /**
     * To Return Estimated Bill Of Material   Cost 
     * @return double $productResourcesEstimatedBillOfMaterialCost
     */
    public function getProductResourcesEstimatedBillOfMaterialCost() {
        return $this->productResourcesEstimatedBillOfMaterialCost;
    }

    /**
     * To Set Estimated Bill Of Material   Cost 
     * @param double $productResourcesEstimatedBillOfMaterialCost Estimated Bill Of Material   Cost 
     * @return \Core\Financial\Inventory\ProductResources\Model\ProductResourcesModel
     */
    public function setProductResourcesEstimatedBillOfMaterialCost($productResourcesEstimatedBillOfMaterialCost) {
        $this->productResourcesEstimatedBillOfMaterialCost = $productResourcesEstimatedBillOfMaterialCost;
        return $this;
    }

    /**
     * To Return Actual Bill Of Material   Cost 
     * @return double $productResourcesActualBillOfMaterialCost
     */
    public function getProductResourcesActualBillOfMaterialCost() {
        return $this->productResourcesActualBillOfMaterialCost;
    }

    /**
     * To Set Actual Bill Of Material   Cost 
     * @param double $productResourcesActualBillOfMaterialCost Actual Bill Of Material   Cost 
     * @return \Core\Financial\Inventory\ProductResources\Model\ProductResourcesModel
     */
    public function setProductResourcesActualBillOfMaterialCost($productResourcesActualBillOfMaterialCost) {
        $this->productResourcesActualBillOfMaterialCost = $productResourcesActualBillOfMaterialCost;
        return $this;
    }

    /**
     * To Return Estimated Cost 
     * @return double $productResourcesEstimatedTotalCost
     */
    public function getProductResourcesEstimatedTotalCost() {
        return $this->productResourcesEstimatedTotalCost;
    }

    /**
     * To Set Estimated Cost 
     * @param double $productResourcesEstimatedTotalCost Estimated Cost 
     * @return \Core\Financial\Inventory\ProductResources\Model\ProductResourcesModel
     */
    public function setProductResourcesEstimatedTotalCost($productResourcesEstimatedTotalCost) {
        $this->productResourcesEstimatedTotalCost = $productResourcesEstimatedTotalCost;
        return $this;
    }

    /**
     * To Return Actual Cost 
     * @return double $productResourcesActualTotalCost
     */
    public function getProductResourcesActualTotalCost() {
        return $this->productResourcesActualTotalCost;
    }

    /**
     * To Set Actual Cost 
     * @param double $productResourcesActualTotalCost Actual Cost 
     * @return \Core\Financial\Inventory\ProductResources\Model\ProductResourcesModel
     */
    public function setProductResourcesActualTotalCost($productResourcesActualTotalCost) {
        $this->productResourcesActualTotalCost = $productResourcesActualTotalCost;
        return $this;
    }

}

?>