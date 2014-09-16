<?php

namespace Core\Financial\Inventory\Item\Model;

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
 * Class Item
 * This is item model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\Inventory\Item\Model;
 * @subpackage Inventory
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ItemModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $itemId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Group
     * @var int
     */
    private $itemGroupId;

    /**
     * Code
     * @var string
     */
    private $itemCode;

    /**
     * Description
     * @var string
     */
    private $itemDescription;

    /**
     * Depreciation Rate
     * @var double
     */
    private $itemDepreciationRate;

    /**
     * Life
     * @var double
     */
    private $itemLife;

    /**
     * Minimum Order
     * @var int
     */
    private $itemMinimumReOrder;

    /**
     * Is Depreciate
     * @var bool
     */
    private $isDepreciate;

    /**
     * Is Asset
     * @var bool
     */
    private $isFixedAsset;

    /**
     * Is Inventory
     * @var bool
     */
    private $isInventory;

    /**
     * Is Item
     * @var bool
     */
    private $isSalesItem;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('item');
        $this->setPrimaryKeyName('itemId');
        $this->setMasterForeignKeyName('itemId');
        $this->setFilterCharacter('itemDescription');
        //$this->setFilterCharacter('itemNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['itemId'])) {
            $this->setItemId($this->strict($_POST ['itemId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['itemGroupId'])) {
            $this->setItemGroupId($this->strict($_POST ['itemGroupId'], 'integer'));
        }
        if (isset($_POST ['itemCode'])) {
            $this->setItemCode($this->strict($_POST ['itemCode'], 'string'));
        }
        if (isset($_POST ['itemDescription'])) {
            $this->setItemDescription($this->strict($_POST ['itemDescription'], 'string'));
        }
        if (isset($_POST ['itemDepreciationRate'])) {
            $this->setItemDepreciationRate($this->strict($_POST ['itemDepreciationRate'], 'double'));
        }
        if (isset($_POST ['itemLife'])) {
            $this->setItemLife($this->strict($_POST ['itemLife'], 'double'));
        }
        if (isset($_POST ['itemMinimumReOrder'])) {
            $this->setItemMinimumReOrder($this->strict($_POST ['itemMinimumReOrder'], 'integer'));
        }
        if (isset($_POST ['isDepreciate'])) {
            $this->setIsDepreciate($this->strict($_POST ['isDepreciate'], 'bool'));
        }
        if (isset($_POST ['isFixedAsset'])) {
            $this->setIsFixedAsset($this->strict($_POST ['isFixedAsset'], 'bool'));
        }
        if (isset($_POST ['isInventory'])) {
            $this->setIsInventory($this->strict($_POST ['isInventory'], 'bool'));
        }
        if (isset($_POST ['isSalesItem'])) {
            $this->setIsSalesItem($this->strict($_POST ['isSalesItem'], 'bool'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['itemId'])) {
            $this->setItemId($this->strict($_GET ['itemId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['itemGroupId'])) {
            $this->setItemGroupId($this->strict($_GET ['itemGroupId'], 'integer'));
        }
        if (isset($_GET ['itemCode'])) {
            $this->setItemCode($this->strict($_GET ['itemCode'], 'string'));
        }
        if (isset($_GET ['itemDescription'])) {
            $this->setItemDescription($this->strict($_GET ['itemDescription'], 'string'));
        }
        if (isset($_GET ['itemDepreciationRate'])) {
            $this->setItemDepreciationRate($this->strict($_GET ['itemDepreciationRate'], 'double'));
        }
        if (isset($_GET ['itemLife'])) {
            $this->setItemLife($this->strict($_GET ['itemLife'], 'double'));
        }
        if (isset($_GET ['itemMinimumReOrder'])) {
            $this->setItemMinimumReOrder($this->strict($_GET ['itemMinimumReOrder'], 'integer'));
        }
        if (isset($_GET ['isDepreciate'])) {
            $this->setIsDepreciate($this->strict($_GET ['isDepreciate'], 'bool'));
        }
        if (isset($_GET ['isFixedAsset'])) {
            $this->setIsFixedAsset($this->strict($_GET ['isFixedAsset'], 'bool'));
        }
        if (isset($_GET ['isInventory'])) {
            $this->setIsInventory($this->strict($_GET ['isInventory'], 'bool'));
        }
        if (isset($_GET ['isSalesItem'])) {
            $this->setIsSalesItem($this->strict($_GET ['isSalesItem'], 'bool'));
        }
        if (isset($_GET ['itemId'])) {
            $this->setTotal(count($_GET ['itemId']));
            if (is_array($_GET ['itemId'])) {
                $this->itemId = array();
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
            if (isset($_GET ['itemId'])) {
                $this->setItemId($this->strict($_GET ['itemId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getItemId($i, 'array') . ",";
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
     * @return \Core\Financial\Inventory\Item\Model\ItemModel
     */
    public function setItemId($value, $key, $type) {
        if ($type == 'single') {
            $this->itemId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->itemId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setitemId?")
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
    public function getItemId($key, $type) {
        if ($type == 'single') {
            return $this->itemId;
        } else {
            if ($type == 'array') {
                return $this->itemId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getitemId ?")
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
     * @return \Core\Financial\Inventory\Item\Model\ItemModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return  Group
     * @return int $itemGroupId
     */
    public function getItemGroupId() {
        return $this->itemGroupId;
    }

    /**
     * To Set Group
     * @param int $itemGroupId Group
     * @return \Core\Financial\Inventory\Item\Model\ItemModel
     */
    public function setItemGroupId($itemGroupId) {
        $this->itemGroupId = $itemGroupId;
        return $this;
    }

    /**
     * To Return  Code
     * @return string $itemCode
     */
    public function getItemCode() {
        return $this->itemCode;
    }

    /**
     * To Set Code
     * @param string $itemCode Code
     * @return \Core\Financial\Inventory\Item\Model\ItemModel
     */
    public function setItemCode($itemCode) {
        $this->itemCode = $itemCode;
        return $this;
    }

    /**
     * To Return  Description
     * @return string $itemDescription
     */
    public function getItemDescription() {
        return $this->itemDescription;
    }

    /**
     * To Set Description
     * @param string $itemDescription Description
     * @return \Core\Financial\Inventory\Item\Model\ItemModel
     */
    public function setItemDescription($itemDescription) {
        $this->itemDescription = $itemDescription;
        return $this;
    }

    /**
     * To Return  DepreciationRate
     * @return double $itemDepreciationRate
     */
    public function getItemDepreciationRate() {
        return $this->itemDepreciationRate;
    }

    /**
     * To Set DepreciationRate
     * @param double $itemDepreciationRate Depreciation Rate
     * @return \Core\Financial\Inventory\Item\Model\ItemModel
     */
    public function setItemDepreciationRate($itemDepreciationRate) {
        $this->itemDepreciationRate = $itemDepreciationRate;
        return $this;
    }

    /**
     * To Return  Life
     * @return double $itemLife
     */
    public function getItemLife() {
        return $this->itemLife;
    }

    /**
     * To Set Life
     * @param double $itemLife Life
     * @return \Core\Financial\Inventory\Item\Model\ItemModel
     */
    public function setItemLife($itemLife) {
        $this->itemLife = $itemLife;
        return $this;
    }

    /**
     * To Return  MinimumReOrder
     * @return int $itemMinimumReOrder
     */
    public function getItemMinimumReOrder() {
        return $this->itemMinimumReOrder;
    }

    /**
     * To Set MinimumReOrder
     * @param int $itemMinimumReOrder Minimum Order
     * @return \Core\Financial\Inventory\Item\Model\ItemModel
     */
    public function setItemMinimumReOrder($itemMinimumReOrder) {
        $this->itemMinimumReOrder = $itemMinimumReOrder;
        return $this;
    }

    /**
     * To Return  isDepreciate
     * @return bool $isDepreciate
     */
    public function getIsDepreciate() {
        return $this->isDepreciate;
    }

    /**
     * To Set isDepreciate
     * @param bool $isDepreciate Is Depreciate
     * @return \Core\Financial\Inventory\Item\Model\ItemModel
     */
    public function setIsDepreciate($isDepreciate) {
        $this->isDepreciate = $isDepreciate;
        return $this;
    }

    /**
     * To Return  isFixedAsset
     * @return bool $isFixedAsset
     */
    public function getIsFixedAsset() {
        return $this->isFixedAsset;
    }

    /**
     * To Set isFixedAsset
     * @param bool $isFixedAsset Is Asset
     * @return \Core\Financial\Inventory\Item\Model\ItemModel
     */
    public function setIsFixedAsset($isFixedAsset) {
        $this->isFixedAsset = $isFixedAsset;
        return $this;
    }

    /**
     * To Return  isInventory
     * @return bool $isInventory
     */
    public function getIsInventory() {
        return $this->isInventory;
    }

    /**
     * To Set isInventory
     * @param bool $isInventory Is Inventory
     * @return \Core\Financial\Inventory\Item\Model\ItemModel
     */
    public function setIsInventory($isInventory) {
        $this->isInventory = $isInventory;
        return $this;
    }

    /**
     * To Return  isSalesItem
     * @return bool $isSalesItem
     */
    public function getIsSalesItem() {
        return $this->isSalesItem;
    }

    /**
     * To Set isSalesItem
     * @param bool $isSalesItem Is Item
     * @return \Core\Financial\Inventory\Item\Model\ItemModel
     */
    public function setIsSalesItem($isSalesItem) {
        $this->isSalesItem = $isSalesItem;
        return $this;
    }

}

?>