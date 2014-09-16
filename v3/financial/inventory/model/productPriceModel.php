<?php

namespace Core\Financial\Inventory\ProductPrice\Model;

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
 * Class ProductPrice
 * This is productPrice model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\Inventory\ProductPrice\Model;
 * @subpackage Inventory
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ProductPriceModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $productPriceId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Business Partner
     * @var int
     */
    private $businessPartnerId;

    /**
     * Item Group
     * @var int
     */
    private $itemGroupId;

    /**
     * Item
     * @var int
     */
    private $itemId;

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
     * Date
     * @var string
     */
    private $productPriceDate;

    /**
     * Valid Start
     * @var string
     */
    private $productPriceValidStart;

    /**
     * Valid End
     * @var string
     */
    private $productPriceValidEnd;

    /**
     *
     * @var double
     */
    private $productPrice;

    /**
     * Product Price
     * @var double
     */
    private $productSellingPrice;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('productPrice');
        $this->setPrimaryKeyName('productPriceId');
        $this->setMasterForeignKeyName('productPriceId');
        $this->setFilterCharacter('productPriceDescription');
        //$this->setFilterCharacter('productPriceNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['productPriceId'])) {
            $this->setProductPriceId($this->strict($_POST ['productPriceId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_POST ['businessPartnerId'], 'integer'));
        }
        if (isset($_POST ['itemGroupId'])) {
            $this->setItemGroupId($this->strict($_POST ['itemGroupId'], 'integer'));
        }
        if (isset($_POST ['itemId'])) {
            $this->setItemId($this->strict($_POST ['itemId'], 'integer'));
        }
        if (isset($_POST ['countryId'])) {
            $this->setCountryId($this->strict($_POST ['countryId'], 'integer'));
        }
        if (isset($_POST ['productId'])) {
            $this->setProductId($this->strict($_POST ['productId'], 'integer'));
        }
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string'));
        }
        if (isset($_POST ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_POST ['referenceNumber'], 'string'));
        }
        if (isset($_POST ['productPriceDate'])) {
            $this->setProductPriceDate($this->strict($_POST ['productPriceDate'], 'date'));
        }
        if (isset($_POST ['productPriceValidStart'])) {
            $this->setProductPriceValidStart($this->strict($_POST ['productPriceValidStart'], 'date'));
        }
        if (isset($_POST ['productPriceValidEnd'])) {
            $this->setProductPriceValidEnd($this->strict($_POST ['productPriceValidEnd'], 'date'));
        }
        if (isset($_POST ['productPrice'])) {
            $this->setProductPrice($this->strict($_POST ['productPrice'], 'double'));
        }
        if (isset($_POST ['productSellingPrice'])) {
            $this->setProductSellingPrice($this->strict($_POST ['productSellingPrice'], 'double'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['productPriceId'])) {
            $this->setProductPriceId($this->strict($_GET ['productPriceId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_GET ['businessPartnerId'], 'integer'));
        }
        if (isset($_GET ['itemGroupId'])) {
            $this->setItemGroupId($this->strict($_GET ['itemGroupId'], 'integer'));
        }
        if (isset($_GET ['itemId'])) {
            $this->setItemId($this->strict($_GET ['itemId'], 'integer'));
        }
        if (isset($_GET ['countryId'])) {
            $this->setCountryId($this->strict($_GET ['countryId'], 'integer'));
        }
        if (isset($_GET ['productId'])) {
            $this->setProductId($this->strict($_GET ['productId'], 'integer'));
        }
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string'));
        }
        if (isset($_GET ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_GET ['referenceNumber'], 'string'));
        }
        if (isset($_GET ['productPriceDate'])) {
            $this->setProductPriceDate($this->strict($_GET ['productPriceDate'], 'date'));
        }
        if (isset($_GET ['productPriceValidStart'])) {
            $this->setProductPriceValidStart($this->strict($_GET ['productPriceValidStart'], 'date'));
        }
        if (isset($_GET ['productPriceValidEnd'])) {
            $this->setProductPriceValidEnd($this->strict($_GET ['productPriceValidEnd'], 'date'));
        }
        if (isset($_GET ['productPrice'])) {
            $this->setProductPrice($this->strict($_GET ['productPrice'], 'double'));
        }
        if (isset($_GET ['productSellingPrice'])) {
            $this->setProductSellingPrice($this->strict($_GET ['productSellingPrice'], 'double'));
        }
        if (isset($_GET ['productPriceId'])) {
            $this->setTotal(count($_GET ['productPriceId']));
            if (is_array($_GET ['productPriceId'])) {
                $this->productPriceId = array();
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
            if (isset($_GET ['productPriceId'])) {
                $this->setProductPriceId($this->strict($_GET ['productPriceId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getProductPriceId($i, 'array') . ",";
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
     * @return \Core\Financial\Inventory\ProductPrice\Model\ProductPriceModel
     */
    public function setProductPriceId($value, $key, $type) {
        if ($type == 'single') {
            $this->productPriceId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->productPriceId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setproductPriceId?")
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
    public function getProductPriceId($key, $type) {
        if ($type == 'single') {
            return $this->productPriceId;
        } else {
            if ($type == 'array') {
                return $this->productPriceId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getproductPriceId ?")
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
     * @return \Core\Financial\Inventory\ProductPrice\Model\ProductPriceModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return  BusinessPartner
     * @return int $businessPartnerId
     */
    public function getBusinessPartnerId() {
        return $this->businessPartnerId;
    }

    /**
     * To Set BusinessPartner
     * @param int $businessPartnerId Business Partner
     * @return \Core\Financial\Inventory\ProductPrice\Model\ProductPriceModel
     */
    public function setBusinessPartnerId($businessPartnerId) {
        $this->businessPartnerId = $businessPartnerId;
        return $this;
    }

    /**
     * To Return  ItemGroup
     * @return int $itemGroupId
     */
    public function getItemGroupId() {
        return $this->itemGroupId;
    }

    /**
     * To Set ItemGroup
     * @param int $itemGroupId Item Group
     * @return \Core\Financial\Inventory\ProductPrice\Model\ProductPriceModel
     */
    public function setItemGroupId($itemGroupId) {
        $this->itemGroupId = $itemGroupId;
        return $this;
    }

    /**
     * To Return  Item
     * @return int $itemId
     */
    public function getItemId() {
        return $this->itemId;
    }

    /**
     * To Set Item
     * @param int $itemId Item
     * @return \Core\Financial\Inventory\ProductPrice\Model\ProductPriceModel
     */
    public function setItemId($itemId) {
        $this->itemId = $itemId;
        return $this;
    }

    /**
     * To Return  Country
     * @return int $countryId
     */
    public function getCountryId() {
        return $this->countryId;
    }

    /**
     * To Set Country
     * @param int $countryId Country
     * @return \Core\Financial\Inventory\ProductPrice\Model\ProductPriceModel
     */
    public function setCountryId($countryId) {
        $this->countryId = $countryId;
        return $this;
    }

    /**
     * To Return  Product
     * @return int $productId
     */
    public function getProductId() {
        return $this->productId;
    }

    /**
     * To Set Product
     * @param int $productId Product
     * @return \Core\Financial\Inventory\ProductPrice\Model\ProductPriceModel
     */
    public function setProductId($productId) {
        $this->productId = $productId;
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
     * @return \Core\Financial\Inventory\ProductPrice\Model\ProductPriceModel
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
     * @return \Core\Financial\Inventory\ProductPrice\Model\ProductPriceModel
     */
    public function setReferenceNumber($referenceNumber) {
        $this->referenceNumber = $referenceNumber;
        return $this;
    }

    /**
     * To Return  Product Date
     * @return string $productPriceDate
     */
    public function getProductPriceDate() {
        return $this->productPriceDate;
    }

    /**
     * To Set Date
     * @param string $productPriceDate Date
     * @return \Core\Financial\Inventory\ProductPrice\Model\ProductPriceModel
     */
    public function setProductPriceDate($productPriceDate) {
        $this->productPriceDate = $productPriceDate;
        return $this;
    }

    /**
     * To Return  ValidStart
     * @return string $productPriceValidStart
     */
    public function getProductPriceValidStart() {
        return $this->productPriceValidStart;
    }

    /**
     * To Set ValidStart
     * @param string $productPriceValidStart Valid Start
     * @return \Core\Financial\Inventory\ProductPrice\Model\ProductPriceModel
     */
    public function setProductPriceValidStart($productPriceValidStart) {
        $this->productPriceValidStart = $productPriceValidStart;
        return $this;
    }

    /**
     * To Return  ValidEnd
     * @return date $productPriceValidEnd
     */
    public function getProductPriceValidEnd() {
        return $this->productPriceValidEnd;
    }

    /**
     * To Set ValidEnd
     * @param date $productPriceValidEnd Valid End
     * @return \Core\Financial\Inventory\ProductPrice\Model\ProductPriceModel
     */
    public function setProductPriceValidEnd($productPriceValidEnd) {
        $this->productPriceValidEnd = $productPriceValidEnd;
        return $this;
    }

    /**
     * To Return
     * @return double $productPrice
     */
    public function getProductPrice() {
        return $this->productPrice;
    }

    /**
     * To Set
     * @param double $productPrice
     * @return \Core\Financial\Inventory\ProductPrice\Model\ProductPriceModel
     */
    public function setProductPrice($productPrice) {
        $this->productPrice = $productPrice;
        return $this;
    }

    /**
     * To Return  productSellingPrice
     * @return double $productSellingPrice
     */
    public function getProductSellingPrice() {
        return $this->productSellingPrice;
    }

    /**
     * To Set productSellingPrice
     * @param double $productSellingPrice Product Price
     * @return \Core\Financial\Inventory\ProductPrice\Model\ProductPriceModel
     */
    public function setProductSellingPrice($productSellingPrice) {
        $this->productSellingPrice = $productSellingPrice;
        return $this;
    }

}

?>