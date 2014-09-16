<?php

namespace Core\Financial\Inventory\ProductSellingPrice\Model;

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
 * Class ProductSellingPrice
 * This is productSellingPrice model file.This is to ensure strict setting enable for all variable enter to database 
 * 
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\Financial\Inventory\ProductSellingPrice\Model;
 * @subpackage Inventory 
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ProductSellingPriceModel extends ValidationClass {

    /**
     * Primary Key
     * @var int 
     */
    private $productSellingPriceId;

    /**
     * Company
     * @var int 
     */
    private $companyId;

    /**
     * Product
     * @var int 
     */
    private $productId;

    /**
     * Country
     * @var int 
     */
    private $countryId;

    /**
     * State
     * @var int 
     */
    private $stateId;

    /**
     * Unit Measurement
     * @var int 
     */
    private $unitOfMeasurementId;

    /**
     * Quantity
     * @var int 
     */
    private $productSellingPriceQuantity;

    /**
     * Price
     * @var double 
     */
    private $productSellingPricePrice;

    /**
     * Start Date
     * @var date 
     */
    private $productSellingPriceStartDate;

    /**
     * End Date
     * @var date 
     */
    private $productSellingPriceEndDate;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('productSellingPrice');
        $this->setPrimaryKeyName('productSellingPriceId');
        $this->setMasterForeignKeyName('productSellingPriceId');
        $this->setFilterCharacter('productDescription');
        //$this->setFilterCharacter('productSellingPriceNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['productSellingPriceId'])) {
            $this->setProductSellingPriceId($this->strict($_POST ['productSellingPriceId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['productId'])) {
            $this->setProductId($this->strict($_POST ['productId'], 'int'));
        }
        if (isset($_POST ['countryId'])) {
            $this->setCountryId($this->strict($_POST ['countryId'], 'int'));
        }
        if (isset($_POST ['stateId'])) {
            $this->setStateId($this->strict($_POST ['stateId'], 'int'));
        }
        if (isset($_POST ['unitOfMeasurementId'])) {
            $this->setUnitOfMeasurementId($this->strict($_POST ['unitOfMeasurementId'], 'int'));
        }
        if (isset($_POST ['productSellingPriceQuantity'])) {
            $this->setProductSellingPriceQuantity($this->strict($_POST ['productSellingPriceQuantity'], 'int'));
        }
        if (isset($_POST ['productSellingPricePrice'])) {
            $this->setProductSellingPricePrice($this->strict($_POST ['productSellingPricePrice'], 'double'));
        }
        if (isset($_POST ['productSellingPriceStartDate'])) {
            $this->setProductSellingPriceStartDate($this->strict($_POST ['productSellingPriceStartDate'], 'date'));
        }
        if (isset($_POST ['productSellingPriceEndDate'])) {
            $this->setProductSellingPriceEndDate($this->strict($_POST ['productSellingPriceEndDate'], 'date'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['productSellingPriceId'])) {
            $this->setProductSellingPriceId($this->strict($_GET ['productSellingPriceId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['productId'])) {
            $this->setProductId($this->strict($_GET ['productId'], 'int'));
        }
        if (isset($_GET ['countryId'])) {
            $this->setCountryId($this->strict($_GET ['countryId'], 'int'));
        }
        if (isset($_GET ['stateId'])) {
            $this->setStateId($this->strict($_GET ['stateId'], 'int'));
        }
        if (isset($_GET ['unitOfMeasurementId'])) {
            $this->setUnitOfMeasurementId($this->strict($_GET ['unitOfMeasurementId'], 'int'));
        }
        if (isset($_GET ['productSellingPriceQuantity'])) {
            $this->setProductSellingPriceQuantity($this->strict($_GET ['productSellingPriceQuantity'], 'int'));
        }
        if (isset($_GET ['productSellingPricePrice'])) {
            $this->setProductSellingPricePrice($this->strict($_GET ['productSellingPricePrice'], 'double'));
        }
        if (isset($_GET ['productSellingPriceStartDate'])) {
            $this->setProductSellingPriceStartDate($this->strict($_GET ['productSellingPriceStartDate'], 'date'));
        }
        if (isset($_GET ['productSellingPriceEndDate'])) {
            $this->setProductSellingPriceEndDate($this->strict($_GET ['productSellingPriceEndDate'], 'date'));
        }
        if (isset($_GET ['productSellingPriceId'])) {
            $this->setTotal(count($_GET ['productSellingPriceId']));
            if (is_array($_GET ['productSellingPriceId'])) {
                $this->productSellingPriceId = array();
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
            if (isset($_GET ['productSellingPriceId'])) {
                $this->setProductSellingPriceId($this->strict($_GET ['productSellingPriceId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getProductSellingPriceId($i, 'array') . ",";
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
     * @return \Core\Financial\Inventory\ProductSellingPrice\Model\ProductSellingPriceModel
     */
    public function setProductSellingPriceId($value, $key, $type) {
        if ($type == 'single') {
            $this->productSellingPriceId = $value;
            return $this;
        } else if ($type == 'array') {
            $this->productSellingPriceId[$key] = $value;
            return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:setproductSellingPriceId?"));
            exit();
        }
    }

    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getProductSellingPriceId($key, $type) {
        if ($type == 'single') {
            return $this->productSellingPriceId;
        } else if ($type == 'array') {
            return $this->productSellingPriceId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:getproductSellingPriceId ?"));
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
     * @return \Core\Financial\Inventory\ProductSellingPrice\Model\ProductSellingPriceModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
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
     * @return \Core\Financial\Inventory\ProductSellingPrice\Model\ProductSellingPriceModel
     */
    public function setProductId($productId) {
        $this->productId = $productId;
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
     * @return \Core\Financial\Inventory\ProductSellingPrice\Model\ProductSellingPriceModel
     */
    public function setCountryId($countryId) {
        $this->countryId = $countryId;
        return $this;
    }

    /**
     * To Return State 
     * @return int $stateId
     */
    public function getStateId() {
        return $this->stateId;
    }

    /**
     * To Set State 
     * @param int $stateId State 
     * @return \Core\Financial\Inventory\ProductSellingPrice\Model\ProductSellingPriceModel
     */
    public function setStateId($stateId) {
        $this->stateId = $stateId;
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
     * @return \Core\Financial\Inventory\ProductSellingPrice\Model\ProductSellingPriceModel
     */
    public function setUnitOfMeasurementId($unitOfMeasurementId) {
        $this->unitOfMeasurementId = $unitOfMeasurementId;
        return $this;
    }

    /**
     * To Return Quantity 
     * @return int $productSellingPriceQuantity
     */
    public function getProductSellingPriceQuantity() {
        return $this->productSellingPriceQuantity;
    }

    /**
     * To Set Quantity 
     * @param int $productSellingPriceQuantity Quantity 
     * @return \Core\Financial\Inventory\ProductSellingPrice\Model\ProductSellingPriceModel
     */
    public function setProductSellingPriceQuantity($productSellingPriceQuantity) {
        $this->productSellingPriceQuantity = $productSellingPriceQuantity;
        return $this;
    }

    /**
     * To Return Price 
     * @return double $productSellingPricePrice
     */
    public function getProductSellingPricePrice() {
        return $this->productSellingPricePrice;
    }

    /**
     * To Set Price 
     * @param double $productSellingPricePrice Price 
     * @return \Core\Financial\Inventory\ProductSellingPrice\Model\ProductSellingPriceModel
     */
    public function setProductSellingPricePrice($productSellingPricePrice) {
        $this->productSellingPricePrice = $productSellingPricePrice;
        return $this;
    }

    /**
     * To Return Start Date 
     * @return string $productSellingPriceStartDate
     */
    public function getProductSellingPriceStartDate() {
        return $this->productSellingPriceStartDate;
    }

    /**
     * To Set Start Date 
     * @param string $productSellingPriceStartDate Start Date 
     * @return \Core\Financial\Inventory\ProductSellingPrice\Model\ProductSellingPriceModel
     */
    public function setProductSellingPriceStartDate($productSellingPriceStartDate) {
        $this->productSellingPriceStartDate = $productSellingPriceStartDate;
        return $this;
    }

    /**
     * To Return End Date 
     * @return string $productSellingPriceEndDate
     */
    public function getProductSellingPriceEndDate() {
        return $this->productSellingPriceEndDate;
    }

    /**
     * To Set End Date 
     * @param string $productSellingPriceEndDate End Date 
     * @return \Core\Financial\Inventory\ProductSellingPrice\Model\ProductSellingPriceModel
     */
    public function setProductSellingPriceEndDate($productSellingPriceEndDate) {
        $this->productSellingPriceEndDate = $productSellingPriceEndDate;
        return $this;
    }

}

?>