<?php

namespace Core\Financial\FixedAsset\ItemType\Model;

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
 * Class ItemType
 * This is itemType model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\FixedAsset\ItemType\Model;
 * @subpackage FixedAsset
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ItemTypeModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $itemTypeId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Item Category
     * @var int
     */
    private $itemCategoryId;

    /**
     * Code
     * @var string
     */
    private $itemTypeCode;

    /**
     * Depreciation Rate
     * @var double
     */
    private $itemTypeDepreciationRate;

    /**
     * Life
     * @var double
     */
    private $itemTypeLife;

    /**
     * Minimum ReOrder
     * @var int
     */
    private $itemTypeMinimumReOrder;

    /**
     * Description
     * @var string
     */
    private $itemTypeDescription;

    /**
     * Is Depreciate
     * @var bool
     */
    private $isDepreciate;

    /**
     * Is Fixed Asset
     * @var bool
     */
    private $isFixedAsset;

    /**
     * Is Inventory
     * @var bool
     */
    private $isInventory;

    /**
     * Is Sales Item
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
        $this->setTableName('itemType');
        $this->setPrimaryKeyName('itemTypeId');
        $this->setMasterForeignKeyName('itemTypeId');
        $this->setFilterCharacter('itemTypeDescription');
        //$this->setFilterCharacter('itemTypeNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['itemTypeId'])) {
            $this->setItemTypeId($this->strict($_POST ['itemTypeId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['itemCategoryId'])) {
            $this->setItemCategoryId($this->strict($_POST ['itemCategoryId'], 'int'));
        }
        if (isset($_POST ['itemTypeCode'])) {
            $this->setItemTypeCode($this->strict($_POST ['itemTypeCode'], 'string'));
        }
        if (isset($_POST ['itemTypeDepreciationRate'])) {
            $this->setItemTypeDepreciationRate($this->strict($_POST ['itemTypeDepreciationRate'], 'double'));
        }
        if (isset($_POST ['itemTypeLife'])) {
            $this->setItemTypeLife($this->strict($_POST ['itemTypeLife'], 'double'));
        }
        if (isset($_POST ['itemTypeMinimumReOrder'])) {
            $this->setItemTypeMinimumReOrder($this->strict($_POST ['itemTypeMinimumReOrder'], 'int'));
        }
        if (isset($_POST ['itemTypeDescription'])) {
            $this->setItemTypeDescription($this->strict($_POST ['itemTypeDescription'], 'string'));
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
        if (isset($_GET ['itemTypeId'])) {
            $this->setItemTypeId($this->strict($_GET ['itemTypeId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['itemCategoryId'])) {
            $this->setItemCategoryId($this->strict($_GET ['itemCategoryId'], 'int'));
        }
        if (isset($_GET ['itemTypeCode'])) {
            $this->setItemTypeCode($this->strict($_GET ['itemTypeCode'], 'string'));
        }
        if (isset($_GET ['itemTypeDepreciationRate'])) {
            $this->setItemTypeDepreciationRate($this->strict($_GET ['itemTypeDepreciationRate'], 'double'));
        }
        if (isset($_GET ['itemTypeLife'])) {
            $this->setItemTypeLife($this->strict($_GET ['itemTypeLife'], 'double'));
        }
        if (isset($_GET ['itemTypeMinimumReOrder'])) {
            $this->setItemTypeMinimumReOrder($this->strict($_GET ['itemTypeMinimumReOrder'], 'int'));
        }
        if (isset($_GET ['itemTypeDescription'])) {
            $this->setItemTypeDescription($this->strict($_GET ['itemTypeDescription'], 'string'));
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
        if (isset($_GET ['itemTypeId'])) {
            $this->setTotal(count($_GET ['itemTypeId']));
            if (is_array($_GET ['itemTypeId'])) {
                $this->itemTypeId = array();
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
            if (isset($_GET ['itemTypeId'])) {
                $this->setItemTypeId($this->strict($_GET ['itemTypeId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getItemTypeId($i, 'array') . ",";
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
    public function getItemTypeId($key, $type) {
        if ($type == 'single') {
            return $this->itemTypeId;
        } else {
            if ($type == 'array') {
                return $this->itemTypeId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getItemTypeId ?")
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
     * @return \Core\Financial\FixedAsset\ItemType\Model\ItemTypeModel
     */
    public function setItemTypeId($value, $key, $type) {
        if ($type == 'single') {
            $this->itemTypeId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->itemTypeId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setItemTypeId?")
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
     * @return \Core\Financial\FixedAsset\ItemType\Model\ItemTypeModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
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
     * @return \Core\Financial\FixedAsset\ItemType\Model\ItemTypeModel
     */
    public function setItemCategoryId($itemCategoryId) {
        $this->itemCategoryId = $itemCategoryId;
        return $this;
    }

    /**
     * To Return Code
     * @return string $itemTypeCode
     */
    public function getItemTypeCode() {
        return $this->itemTypeCode;
    }

    /**
     * To Set Code
     * @param string $itemTypeCode Code
     * @return \Core\Financial\FixedAsset\ItemType\Model\ItemTypeModel
     */
    public function setItemTypeCode($itemTypeCode) {
        $this->itemTypeCode = $itemTypeCode;
        return $this;
    }

    /**
     * To Return Depreciation Rate
     * @return double $itemTypeDepreciationRate
     */
    public function getItemTypeDepreciationRate() {
        return $this->itemTypeDepreciationRate;
    }

    /**
     * To Set Depreciation Rate
     * @param double $itemTypeDepreciationRate Depreciation Rate
     * @return \Core\Financial\FixedAsset\ItemType\Model\ItemTypeModel
     */
    public function setItemTypeDepreciationRate($itemTypeDepreciationRate) {
        $this->itemTypeDepreciationRate = $itemTypeDepreciationRate;
        return $this;
    }

    /**
     * To Return Life
     * @return double $itemTypeLife
     */
    public function getItemTypeLife() {
        return $this->itemTypeLife;
    }

    /**
     * To Set Life
     * @param double $itemTypeLife Life
     * @return \Core\Financial\FixedAsset\ItemType\Model\ItemTypeModel
     */
    public function setItemTypeLife($itemTypeLife) {
        $this->itemTypeLife = $itemTypeLife;
        return $this;
    }

    /**
     * To Return Minimum ReOrder
     * @return int $itemTypeMinimumReOrder
     */
    public function getItemTypeMinimumReOrder() {
        return $this->itemTypeMinimumReOrder;
    }

    /**
     * To Set Minimum ReOrder
     * @param int $itemTypeMinimumReOrder Minimum Order
     * @return \Core\Financial\FixedAsset\ItemType\Model\ItemTypeModel
     */
    public function setItemTypeMinimumReOrder($itemTypeMinimumReOrder) {
        $this->itemTypeMinimumReOrder = $itemTypeMinimumReOrder;
        return $this;
    }

    /**
     * To Return Description
     * @return string $itemTypeDescription
     */
    public function getItemTypeDescription() {
        return $this->itemTypeDescription;
    }

    /**
     * To Set Description
     * @param string $itemTypeDescription Description
     * @return \Core\Financial\FixedAsset\ItemType\Model\ItemTypeModel
     */
    public function setItemTypeDescription($itemTypeDescription) {
        $this->itemTypeDescription = $itemTypeDescription;
        return $this;
    }

    /**
     * To Return Is Depreciate
     * @return bool $isDepreciate
     */
    public function getIsDepreciate() {
        return $this->isDepreciate;
    }

    /**
     * To Set Is Depreciate
     * @param bool $isDepreciate Is Depreciate
     * @return \Core\Financial\FixedAsset\ItemType\Model\ItemTypeModel
     */
    public function setIsDepreciate($isDepreciate) {
        $this->isDepreciate = $isDepreciate;
        return $this;
    }

    /**
     * To Return is Fixed Asset
     * @return bool $isFixedAsset
     */
    public function getIsFixedAsset() {
        return $this->isFixedAsset;
    }

    /**
     * To Set Is Fixed Asset
     * @param bool $isFixedAsset Is Asset
     * @return \Core\Financial\FixedAsset\ItemType\Model\ItemTypeModel
     */
    public function setIsFixedAsset($isFixedAsset) {
        $this->isFixedAsset = $isFixedAsset;
        return $this;
    }

    /**
     * To Return is Inventory
     * @return bool $isInventory
     */
    public function getIsInventory() {
        return $this->isInventory;
    }

    /**
     * To Set is Inventory
     * @param bool $isInventory Is Inventory
     * @return \Core\Financial\FixedAsset\ItemType\Model\ItemTypeModel
     */
    public function setIsInventory($isInventory) {
        $this->isInventory = $isInventory;
        return $this;
    }

    /**
     * To Return is Sales Item
     * @return bool $isSalesItem
     */
    public function getIsSalesItem() {
        return $this->isSalesItem;
    }

    /**
     * To Set is Sales Item
     * @param bool $isSalesItem Is Item
     * @return \Core\Financial\FixedAsset\ItemType\Model\ItemTypeModel
     */
    public function setIsSalesItem($isSalesItem) {
        $this->isSalesItem = $isSalesItem;
        return $this;
    }

}

?>