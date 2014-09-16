<?php

namespace Core\Financial\Inventory\ProductRecount\Model;

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
 * Class ProductRecount
 * This is productRecount model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\Inventory\ProductRecount\Model;
 * @subpackage Inventory
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ProductRecountModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $productRecountId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Warehouse
     * @var int
     */
    private $warehouseId;

    /**
     * Product Code
     * @var string
     */
    private $productCode;

    /**
     * Product Description
     * @var string
     */
    private $productDescription;

    /**
     * Date
     * @var string
     */
    private $productRecountDate;

    /**
     * System Quantity
     * @var int
     */
    private $productRecountSystemQuantity;

    /**
     * Physical Quantity
     * @var int
     */
    private $productRecountPhysicalQuantity;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('productRecount');
        $this->setPrimaryKeyName('productRecountId');
        $this->setMasterForeignKeyName('productRecountId');
        $this->setFilterCharacter('productRecountDescription');
        //$this->setFilterCharacter('productRecountNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['productRecountId'])) {
            $this->setProductRecountId($this->strict($_POST ['productRecountId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['warehouseId'])) {
            $this->setWarehouseId($this->strict($_POST ['warehouseId'], 'integer'));
        }
        if (isset($_POST ['productCode'])) {
            $this->setProductCode($this->strict($_POST ['productCode'], 'string'));
        }
        if (isset($_POST ['productDescription'])) {
            $this->setProductDescription($this->strict($_POST ['productDescription'], 'string'));
        }
        if (isset($_POST ['productRecountDate'])) {
            $this->setProductRecountDate($this->strict($_POST ['productRecountDate'], 'date'));
        }
        if (isset($_POST ['productRecountSystemQuantity'])) {
            $this->setProductRecountSystemQuantity($this->strict($_POST ['productRecountSystemQuantity'], 'integer'));
        }
        if (isset($_POST ['productRecountPhysicalQuantity'])) {
            $this->setProductRecountPhysicalQuantity(
                    $this->strict($_POST ['productRecountPhysicalQuantity'], 'integer')
            );
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['productRecountId'])) {
            $this->setProductRecountId($this->strict($_GET ['productRecountId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['warehouseId'])) {
            $this->setWarehouseId($this->strict($_GET ['warehouseId'], 'integer'));
        }
        if (isset($_GET ['productCode'])) {
            $this->setProductCode($this->strict($_GET ['productCode'], 'string'));
        }
        if (isset($_GET ['productDescription'])) {
            $this->setProductDescription($this->strict($_GET ['productDescription'], 'string'));
        }
        if (isset($_GET ['productRecountDate'])) {
            $this->setProductRecountDate($this->strict($_GET ['productRecountDate'], 'date'));
        }
        if (isset($_GET ['productRecountSystemQuantity'])) {
            $this->setProductRecountSystemQuantity($this->strict($_GET ['productRecountSystemQuantity'], 'integer'));
        }
        if (isset($_GET ['productRecountPhysicalQuantity'])) {
            $this->setProductRecountPhysicalQuantity(
                    $this->strict($_GET ['productRecountPhysicalQuantity'], 'integer')
            );
        }
        if (isset($_GET ['productRecountId'])) {
            $this->setTotal(count($_GET ['productRecountId']));
            if (is_array($_GET ['productRecountId'])) {
                $this->productRecountId = array();
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
            if (isset($_GET ['productRecountId'])) {
                $this->setProductRecountId($this->strict($_GET ['productRecountId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getProductRecountId($i, 'array') . ",";
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
     * @return \Core\Financial\Inventory\ProductRecount\Model\ProductRecountModel
     */
    public function setProductRecountId($value, $key, $type) {
        if ($type == 'single') {
            $this->productRecountId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->productRecountId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setproductRecountId?")
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
    public function getProductRecountId($key, $type) {
        if ($type == 'single') {
            return $this->productRecountId;
        } else {
            if ($type == 'array') {
                return $this->productRecountId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getproductRecountId ?")
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
     * @return \Core\Financial\Inventory\ProductRecount\Model\ProductRecountModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return  Warehouse
     * @return int $warehouseId
     */
    public function getWarehouseId() {
        return $this->warehouseId;
    }

    /**
     * To Set Warehouse
     * @param int $warehouseId Warehouse
     * @return \Core\Financial\Inventory\ProductRecount\Model\ProductRecountModel
     */
    public function setWarehouseId($warehouseId) {
        $this->warehouseId = $warehouseId;
        return $this;
    }

    /**
     * To Return  productCode
     * @return string $productCode
     */
    public function getProductCode() {
        return $this->productCode;
    }

    /**
     * To Set productCode
     * @param string $productCode Product Code
     * @return \Core\Financial\Inventory\ProductRecount\Model\ProductRecountModel
     */
    public function setProductCode($productCode) {
        $this->productCode = $productCode;
        return $this;
    }

    /**
     * To Return  productDescription
     * @return string $productDescription
     */
    public function getProductDescription() {
        return $this->productDescription;
    }

    /**
     * To Set productDescription
     * @param string $productDescription Product Description
     * @return \Core\Financial\Inventory\ProductRecount\Model\ProductRecountModel
     */
    public function setProductDescription($productDescription) {
        $this->productDescription = $productDescription;
        return $this;
    }

    /**
     * To Return  Date
     * @return string $productRecountDate
     */
    public function getProductRecountDate() {
        return $this->productRecountDate;
    }

    /**
     * To Set Date
     * @param string $productRecountDate Date
     * @return \Core\Financial\Inventory\ProductRecount\Model\ProductRecountModel
     */
    public function setProductRecountDate($productRecountDate) {
        $this->productRecountDate = $productRecountDate;
        return $this;
    }

    /**
     * To Return  SystemQuantity
     * @return int $productRecountSystemQuantity
     */
    public function getProductRecountSystemQuantity() {
        return $this->productRecountSystemQuantity;
    }

    /**
     * To Set SystemQuantity
     * @param int $productRecountSystemQuantity System Quantity
     * @return \Core\Financial\Inventory\ProductRecount\Model\ProductRecountModel
     */
    public function setProductRecountSystemQuantity($productRecountSystemQuantity) {
        $this->productRecountSystemQuantity = $productRecountSystemQuantity;
        return $this;
    }

    /**
     * To Return  PhysicalQuantity
     * @return int $productRecountPhysicalQuantity
     */
    public function getProductRecountPhysicalQuantity() {
        return $this->productRecountPhysicalQuantity;
    }

    /**
     * To Set PhysicalQuantity
     * @param int $productRecountPhysicalQuantity Physical Quantity
     * @return \Core\Financial\Inventory\ProductRecount\Model\ProductRecountModel
     */
    public function setProductRecountPhysicalQuantity($productRecountPhysicalQuantity) {
        $this->productRecountPhysicalQuantity = $productRecountPhysicalQuantity;
        return $this;
    }

}

?>