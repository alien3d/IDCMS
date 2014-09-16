<?php

namespace Core\Financial\Inventory\Product\Model;

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
 * Class Product
 * This is product model file.This is to ensure strict setting enable for all variable enter to database 
 * 
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\Financial\Inventory\Product\Model;
 * @subpackage Inventory 
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ProductModel extends ValidationClass {

    /**
     * Primary Key
     * @var int 
     */
    private $productId;

    /**
     * Company
     * @var int 
     */
    private $companyId;

    /**
     * Branch
     * @var int 
     */
    private $branchId;

    /**
     * Department
     * @var int 
     */
    private $departmentId;

    /**
     * Warehouse
     * @var int 
     */
    private $warehouseId;

    /**
     * Item Category
     * @var int 
     */
    private $itemCategoryId;

    /**
     * Item Type
     * @var int 
     */
    private $itemTypeId;

    /**
     * Country
     * @var int 
     */
    private $countryId;

    /**
     * Code
     * @var string 
     */
    private $productCode;

    /**
     * Barcode
     * @var string 
     */
    private $productBarcode;

    /**
     * Selling Price
     * @var double 
     */
    private $productSellingPrice;

    /**
     * Cost Price
     * @var double 
     */
    private $productCostPrice;

    /**
     * Picture
     * @var string 
     */
    private $productPicture;

    /**
     * Description
     * @var string 
     */
    private $productDescription;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('product');
        $this->setPrimaryKeyName('productId');
        $this->setMasterForeignKeyName('productId');
        $this->setFilterCharacter('productDescription');
        //$this->setFilterCharacter('productNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['productId'])) {
            $this->setProductId($this->strict($_POST ['productId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['branchId'])) {
            $this->setBranchId($this->strict($_POST ['branchId'], 'int'));
        }
        if (isset($_POST ['departmentId'])) {
            $this->setDepartmentId($this->strict($_POST ['departmentId'], 'int'));
        }
        if (isset($_POST ['warehouseId'])) {
            $this->setWarehouseId($this->strict($_POST ['warehouseId'], 'int'));
        }
        if (isset($_POST ['itemCategoryId'])) {
            $this->setItemCategoryId($this->strict($_POST ['itemCategoryId'], 'int'));
        }
        if (isset($_POST ['itemTypeId'])) {
            $this->setItemTypeId($this->strict($_POST ['itemTypeId'], 'int'));
        }
        if (isset($_POST ['countryId'])) {
            $this->setCountryId($this->strict($_POST ['countryId'], 'int'));
        }
        if (isset($_POST ['productCode'])) {
            $this->setProductCode($this->strict($_POST ['productCode'], 'string'));
        }
        if (isset($_POST ['productBarcode'])) {
            $this->setProductBarcode($this->strict($_POST ['productBarcode'], 'string'));
        }
        if (isset($_POST ['productSellingPrice'])) {
            $this->setProductSellingPrice($this->strict($_POST ['productSellingPrice'], 'double'));
        }
        if (isset($_POST ['productCostPrice'])) {
            $this->setProductCostPrice($this->strict($_POST ['productCostPrice'], 'double'));
        }
        if (isset($_POST ['productPicture'])) {
            $this->setProductPicture($this->strict($_POST ['productPicture'], 'string'));
        }
        if (isset($_POST ['productDescription'])) {
            $this->setProductDescription($this->strict($_POST ['productDescription'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['productId'])) {
            $this->setProductId($this->strict($_GET ['productId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['branchId'])) {
            $this->setBranchId($this->strict($_GET ['branchId'], 'int'));
        }
        if (isset($_GET ['departmentId'])) {
            $this->setDepartmentId($this->strict($_GET ['departmentId'], 'int'));
        }
        if (isset($_GET ['warehouseId'])) {
            $this->setWarehouseId($this->strict($_GET ['warehouseId'], 'int'));
        }
        if (isset($_GET ['itemCategoryId'])) {
            $this->setItemCategoryId($this->strict($_GET ['itemCategoryId'], 'int'));
        }
        if (isset($_GET ['itemTypeId'])) {
            $this->setItemTypeId($this->strict($_GET ['itemTypeId'], 'int'));
        }
        if (isset($_GET ['countryId'])) {
            $this->setCountryId($this->strict($_GET ['countryId'], 'int'));
        }
        if (isset($_GET ['productCode'])) {
            $this->setProductCode($this->strict($_GET ['productCode'], 'string'));
        }
        if (isset($_GET ['productBarcode'])) {
            $this->setProductBarcode($this->strict($_GET ['productBarcode'], 'string'));
        }
        if (isset($_GET ['productSellingPrice'])) {
            $this->setProductSellingPrice($this->strict($_GET ['productSellingPrice'], 'double'));
        }
        if (isset($_GET ['productCostPrice'])) {
            $this->setProductCostPrice($this->strict($_GET ['productCostPrice'], 'double'));
        }
        if (isset($_GET ['productPicture'])) {
            $this->setProductPicture($this->strict($_GET ['productPicture'], 'string'));
        }
        if (isset($_GET ['productDescription'])) {
            $this->setProductDescription($this->strict($_GET ['productDescription'], 'string'));
        }
        if (isset($_GET ['productId'])) {
            $this->setTotal(count($_GET ['productId']));
            if (is_array($_GET ['productId'])) {
                $this->productId = array();
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
            if (isset($_GET ['productId'])) {
                $this->setProductId($this->strict($_GET ['productId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getProductId($i, 'array') . ",";
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
     * @return \Core\Financial\Inventory\Product\Model\ProductModel
     */
    public function setProductId($value, $key, $type) {
        if ($type == 'single') {
            $this->productId = $value;
            return $this;
        } else if ($type == 'array') {
            $this->productId[$key] = $value;
            return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:setproductId?"));
            exit();
        }
    }

    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getProductId($key, $type) {
        if ($type == 'single') {
            return $this->productId;
        } else if ($type == 'array') {
            return $this->productId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:getproductId ?"));
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
     * @return \Core\Financial\Inventory\Product\Model\ProductModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Branch 
     * @return int $branchId
     */
    public function getBranchId() {
        return $this->branchId;
    }

    /**
     * To Set Branch 
     * @param int $branchId Branch 
     * @return \Core\Financial\Inventory\Product\Model\ProductModel
     */
    public function setBranchId($branchId) {
        $this->branchId = $branchId;
        return $this;
    }

    /**
     * To Return Department 
     * @return int $departmentId
     */
    public function getDepartmentId() {
        return $this->departmentId;
    }

    /**
     * To Set Department 
     * @param int $departmentId Department 
     * @return \Core\Financial\Inventory\Product\Model\ProductModel
     */
    public function setDepartmentId($departmentId) {
        $this->departmentId = $departmentId;
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
     * @return \Core\Financial\Inventory\Product\Model\ProductModel
     */
    public function setWarehouseId($warehouseId) {
        $this->warehouseId = $warehouseId;
        return $this;
    }

    /**
     * To Return Item Category 
     * @return int $itemCategoryId
     */
    public function getItemCategoryId() {
        return $this->itemCategoryId;
    }

    /**
     * To Set Item Category 
     * @param int $itemCategoryId Item Category 
     * @return \Core\Financial\Inventory\Product\Model\ProductModel
     */
    public function setItemCategoryId($itemCategoryId) {
        $this->itemCategoryId = $itemCategoryId;
        return $this;
    }

    /**
     * To Return Item Type 
     * @return int $itemTypeId
     */
    public function getItemTypeId() {
        return $this->itemTypeId;
    }

    /**
     * To Set Item Type 
     * @param int $itemTypeId Item Type 
     * @return \Core\Financial\Inventory\Product\Model\ProductModel
     */
    public function setItemTypeId($itemTypeId) {
        $this->itemTypeId = $itemTypeId;
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
     * @return \Core\Financial\Inventory\Product\Model\ProductModel
     */
    public function setCountryId($countryId) {
        $this->countryId = $countryId;
        return $this;
    }

    /**
     * To Return Code 
     * @return string $productCode
     */
    public function getProductCode() {
        return $this->productCode;
    }

    /**
     * To Set Code 
     * @param string $productCode Code 
     * @return \Core\Financial\Inventory\Product\Model\ProductModel
     */
    public function setProductCode($productCode) {
        $this->productCode = $productCode;
        return $this;
    }

    /**
     * To Return Barcode 
     * @return string $productBarcode
     */
    public function getProductBarcode() {
        return $this->productBarcode;
    }

    /**
     * To Set Barcode 
     * @param string $productBarcode Barcode 
     * @return \Core\Financial\Inventory\Product\Model\ProductModel
     */
    public function setProductBarcode($productBarcode) {
        $this->productBarcode = $productBarcode;
        return $this;
    }

    /**
     * To Return Selling Price 
     * @return double $productSellingPrice
     */
    public function getProductSellingPrice() {
        return $this->productSellingPrice;
    }

    /**
     * To Set Selling Price 
     * @param double $productSellingPrice Selling Price 
     * @return \Core\Financial\Inventory\Product\Model\ProductModel
     */
    public function setProductSellingPrice($productSellingPrice) {
        $this->productSellingPrice = $productSellingPrice;
        return $this;
    }

    /**
     * To Return Cost Price 
     * @return double $productCostPrice
     */
    public function getProductCostPrice() {
        return $this->productCostPrice;
    }

    /**
     * To Set Cost Price 
     * @param double $productCostPrice Cost Price 
     * @return \Core\Financial\Inventory\Product\Model\ProductModel
     */
    public function setProductCostPrice($productCostPrice) {
        $this->productCostPrice = $productCostPrice;
        return $this;
    }

    /**
     * To Return Picture 
     * @return string $productPicture
     */
    public function getProductPicture() {
        return $this->productPicture;
    }

    /**
     * To Set Picture 
     * @param string $productPicture Picture 
     * @return \Core\Financial\Inventory\Product\Model\ProductModel
     */
    public function setProductPicture($productPicture) {
        $this->productPicture = $productPicture;
        return $this;
    }

    /**
     * To Return Description 
     * @return string $productDescription
     */
    public function getProductDescription() {
        return $this->productDescription;
    }

    /**
     * To Set Description 
     * @param string $productDescription Description 
     * @return \Core\Financial\Inventory\Product\Model\ProductModel
     */
    public function setProductDescription($productDescription) {
        $this->productDescription = $productDescription;
        return $this;
    }

}

?>