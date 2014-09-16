<?php

namespace Core\Financial\Inventory\ProductDimension\Model;

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
for ($i = 0; $i < count($d); $i ++) {
    // if find the library or package then stop
    if ($d[$i] == 'library' || $d[$i] == 'v2' || $d[$i] == 'v3') {
        break;
    }
    $newPath[] .= $d[$i] . "/";
}
$fakeDocumentRoot = null;
for ($z = 0; $z < count($newPath); $z ++) {
    $fakeDocumentRoot .= $newPath[$z];
}
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot); // start
require_once ($newFakeDocumentRoot . "library/class/classValidation.php");

/**
 * Class ProductDimension
 * This is productDimension model file.This is to ensure strict setting enable for all variable enter to database 
 * 
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\Financial\Inventory\ProductDimension\Model;
 * @subpackage Inventory 
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ProductDimensionModel extends ValidationClass {

    /**
     * Primary Key
     * @var int 
     */
    private $productDimensionId;

    /**
     * Company
     * @var int 
     */
    private $companyId;

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
     * Pack Size
     * @var double 
     */
    private $productDimensionPackSize;

    /**
     * Height
     * @var double 
     */
    private $productDimensionHeight;

    /**
     * Weight
     * @var double 
     */
    private $productDimensionWeight;

    /**
     * Depth
     * @var double 
     */
    private $productDimensionDepth;

    /**
     * Width
     * @var double 
     */
    private $productDimensionWidth;

    /**
     * Cubic Total
     * @var double 
     */
    private $productDimensionCubicTotal;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('productDimension');
        $this->setPrimaryKeyName('productDimensionId');
        $this->setMasterForeignKeyName('productDimensionId');
        $this->setFilterCharacter('productDimensionDescription');
        //$this->setFilterCharacter('productDimensionNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['productDimensionId'])) {
            $this->setProductDimensionId($this->strict($_POST ['productDimensionId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['unitOfMeasurementId'])) {
            $this->setUnitOfMeasurementId($this->strict($_POST ['unitOfMeasurementId'], 'int'));
        }
        if (isset($_POST ['productId'])) {
            $this->setProductId($this->strict($_POST ['productId'], 'int'));
        }
        if (isset($_POST ['productDimensionPackSize'])) {
            $this->setProductDimensionPackSize($this->strict($_POST ['productDimensionPackSize'], 'double'));
        }
        if (isset($_POST ['productDimensionHeight'])) {
            $this->setProductDimensionHeight($this->strict($_POST ['productDimensionHeight'], 'double'));
        }
        if (isset($_POST ['productDimensionWeight'])) {
            $this->setProductDimensionWeight($this->strict($_POST ['productDimensionWeight'], 'double'));
        }
        if (isset($_POST ['productDimensionDepth'])) {
            $this->setProductDimensionDepth($this->strict($_POST ['productDimensionDepth'], 'double'));
        }
        if (isset($_POST ['productDimensionWidth'])) {
            $this->setProductDimensionWidth($this->strict($_POST ['productDimensionWidth'], 'double'));
        }
        if (isset($_POST ['productDimensionCubicTotal'])) {
            $this->setProductDimensionCubicTotal($this->strict($_POST ['productDimensionCubicTotal'], 'double'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['productDimensionId'])) {
            $this->setProductDimensionId($this->strict($_GET ['productDimensionId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['unitOfMeasurementId'])) {
            $this->setUnitOfMeasurementId($this->strict($_GET ['unitOfMeasurementId'], 'int'));
        }
        if (isset($_GET ['productId'])) {
            $this->setProductId($this->strict($_GET ['productId'], 'int'));
        }
        if (isset($_GET ['productDimensionPackSize'])) {
            $this->setProductDimensionPackSize($this->strict($_GET ['productDimensionPackSize'], 'double'));
        }
        if (isset($_GET ['productDimensionHeight'])) {
            $this->setProductDimensionHeight($this->strict($_GET ['productDimensionHeight'], 'double'));
        }
        if (isset($_GET ['productDimensionWeight'])) {
            $this->setProductDimensionWeight($this->strict($_GET ['productDimensionWeight'], 'double'));
        }
        if (isset($_GET ['productDimensionDepth'])) {
            $this->setProductDimensionDepth($this->strict($_GET ['productDimensionDepth'], 'double'));
        }
        if (isset($_GET ['productDimensionWidth'])) {
            $this->setProductDimensionWidth($this->strict($_GET ['productDimensionWidth'], 'double'));
        }
        if (isset($_GET ['productDimensionCubicTotal'])) {
            $this->setProductDimensionCubicTotal($this->strict($_GET ['productDimensionCubicTotal'], 'double'));
        }
        if (isset($_GET ['productDimensionId'])) {
            $this->setTotal(count($_GET ['productDimensionId']));
            if (is_array($_GET ['productDimensionId'])) {
                $this->productDimensionId = array();
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
            if (isset($_GET ['productDimensionId'])) {
                $this->setProductDimensionId($this->strict($_GET ['productDimensionId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getProductDimensionId($i, 'array') . ",";
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
     * @return \Core\Financial\Inventory\ProductDimension\Model\ProductDimensionModel
     */
    public function setProductDimensionId($value, $key, $type) {
        if ($type == 'single') {
            $this->productDimensionId = $value;
            return $this;
        } else if ($type == 'array') {
            $this->productDimensionId[$key] = $value;
            return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:setproductDimensionId?"));
            exit();
        }
    }

    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getProductDimensionId($key, $type) {
        if ($type == 'single') {
            return $this->productDimensionId;
        } else if ($type == 'array') {
            return $this->productDimensionId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:getproductDimensionId ?"));
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
     * @return \Core\Financial\Inventory\ProductDimension\Model\ProductDimensionModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Unit Measurement 
     * @return int $unitOfMeasurementId
     */
    public function getUnitOfMeasurementId() {
        return $this->unitOfMeasurementId;
    }

    /**
     * To Set Unit Measurement 
     * @param int $unitOfMeasurementId Unit Measurement 
     * @return \Core\Financial\Inventory\ProductDimension\Model\ProductDimensionModel
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
     * @return \Core\Financial\Inventory\ProductDimension\Model\ProductDimensionModel
     */
    public function setProductId($productId) {
        $this->productId = $productId;
        return $this;
    }

    /**
     * To Return Pack Size 
     * @return double $productDimensionPackSize
     */
    public function getProductDimensionPackSize() {
        return $this->productDimensionPackSize;
    }

    /**
     * To Set Pack Size 
     * @param double $productDimensionPackSize Pack Size 
     * @return \Core\Financial\Inventory\ProductDimension\Model\ProductDimensionModel
     */
    public function setProductDimensionPackSize($productDimensionPackSize) {
        $this->productDimensionPackSize = $productDimensionPackSize;
        return $this;
    }

    /**
     * To Return Height 
     * @return double $productDimensionHeight
     */
    public function getProductDimensionHeight() {
        return $this->productDimensionHeight;
    }

    /**
     * To Set Height 
     * @param double $productDimensionHeight Height 
     * @return \Core\Financial\Inventory\ProductDimension\Model\ProductDimensionModel
     */
    public function setProductDimensionHeight($productDimensionHeight) {
        $this->productDimensionHeight = $productDimensionHeight;
        return $this;
    }

    /**
     * To Return Weight 
     * @return double $productDimensionWeight
     */
    public function getProductDimensionWeight() {
        return $this->productDimensionWeight;
    }

    /**
     * To Set Weight 
     * @param double $productDimensionWeight Weight 
     * @return \Core\Financial\Inventory\ProductDimension\Model\ProductDimensionModel
     */
    public function setProductDimensionWeight($productDimensionWeight) {
        $this->productDimensionWeight = $productDimensionWeight;
        return $this;
    }

    /**
     * To Return Depth 
     * @return double $productDimensionDepth
     */
    public function getProductDimensionDepth() {
        return $this->productDimensionDepth;
    }

    /**
     * To Set Depth 
     * @param double $productDimensionDepth Depth 
     * @return \Core\Financial\Inventory\ProductDimension\Model\ProductDimensionModel
     */
    public function setProductDimensionDepth($productDimensionDepth) {
        $this->productDimensionDepth = $productDimensionDepth;
        return $this;
    }

    /**
     * To Return Width 
     * @return double $productDimensionWidth
     */
    public function getProductDimensionWidth() {
        return $this->productDimensionWidth;
    }

    /**
     * To Set Width 
     * @param double $productDimensionWidth Width 
     * @return \Core\Financial\Inventory\ProductDimension\Model\ProductDimensionModel
     */
    public function setProductDimensionWidth($productDimensionWidth) {
        $this->productDimensionWidth = $productDimensionWidth;
        return $this;
    }

    /**
     * To Return Cubic Total 
     * @return double $productDimensionCubicTotal
     */
    public function getProductDimensionCubicTotal() {
        return $this->productDimensionCubicTotal;
    }

    /**
     * To Set Cubic Total 
     * @param double $productDimensionCubicTotal Cubic Total 
     * @return \Core\Financial\Inventory\ProductDimension\Model\ProductDimensionModel
     */
    public function setProductDimensionCubicTotal($productDimensionCubicTotal) {
        $this->productDimensionCubicTotal = $productDimensionCubicTotal;
        return $this;
    }

}

?>