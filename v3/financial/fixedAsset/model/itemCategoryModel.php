<?php

namespace Core\Financial\FixedAsset\ItemCategory\Model;

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
 * Class ItemCategory
 * This is itemCategory model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\FixedAsset\ItemCategory\Model;
 * @subpackage FixedAsset
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ItemCategoryModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $itemCategoryId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Accumulative Accounts
     * @var int
     */
    private $itemCategoryAccumulativeDepreciationAccounts;

    /**
     * Code
     * @var string
     */
    private $itemCategoryCode;

    /**
     * Depreciation Rate
     * @var double
     */
    private $itemCategoryDepreciationRate;

    /**
     * Life
     * @var double
     */
    private $itemCategoryLife;

    /**
     * Minimum Order
     * @var int
     */
    private $itemCategoryMinimumReOrder;

    /**
     * Minimum Value
     * @var int
     */
    private $itemCategoryMinimumValue;

    /**
     * Description
     * @var string
     */
    private $itemCategoryDescription;

    /**
     * Is Depreciate
     * @var bool
     */
    private $isDepreciate;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('itemCategory');
        $this->setPrimaryKeyName('itemCategoryId');
        $this->setMasterForeignKeyName('itemCategoryId');
        $this->setFilterCharacter('itemCategoryDescription');
        //$this->setFilterCharacter('itemCategoryNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['itemCategoryId'])) {
            $this->setItemCategoryId($this->strict($_POST ['itemCategoryId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['itemCategoryAccumulativeDepreciationAccounts'])) {
            $this->setItemCategoryAccumulativeDepreciationAccounts(
                    $this->strict($_POST ['itemCategoryAccumulativeDepreciationAccounts'], 'integer')
            );
        }
        if (isset($_POST ['itemCategoryCode'])) {
            $this->setItemCategoryCode($this->strict($_POST ['itemCategoryCode'], 'string'));
        }
        if (isset($_POST ['itemCategoryDepreciationRate'])) {
            $this->setItemCategoryDepreciationRate($this->strict($_POST ['itemCategoryDepreciationRate'], 'double'));
        }
        if (isset($_POST ['itemCategoryLife'])) {
            $this->setItemCategoryLife($this->strict($_POST ['itemCategoryLife'], 'double'));
        }
        if (isset($_POST ['itemCategoryMinimumReOrder'])) {
            $this->setItemCategoryMinimumReOrder($this->strict($_POST ['itemCategoryMinimumReOrder'], 'integer'));
        }
        if (isset($_POST ['itemCategoryMinimumValue'])) {
            $this->setItemCategoryMinimumValue($this->strict($_POST ['itemCategoryMinimumValue'], 'integer'));
        }
        if (isset($_POST ['itemCategoryDescription'])) {
            $this->setItemCategoryDescription($this->strict($_POST ['itemCategoryDescription'], 'string'));
        }
        if (isset($_POST ['isDepreciate'])) {
            $this->setIsDepreciate($this->strict($_POST ['isDepreciate'], 'bool'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['itemCategoryId'])) {
            $this->setItemCategoryId($this->strict($_GET ['itemCategoryId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['itemCategoryAccumulativeDepreciationAccounts'])) {
            $this->setItemCategoryAccumulativeDepreciationAccounts(
                    $this->strict($_GET ['itemCategoryAccumulativeDepreciationAccounts'], 'integer')
            );
        }
        if (isset($_GET ['itemCategoryCode'])) {
            $this->setItemCategoryCode($this->strict($_GET ['itemCategoryCode'], 'string'));
        }
        if (isset($_GET ['itemCategoryDepreciationRate'])) {
            $this->setItemCategoryDepreciationRate($this->strict($_GET ['itemCategoryDepreciationRate'], 'double'));
        }
        if (isset($_GET ['itemCategoryLife'])) {
            $this->setItemCategoryLife($this->strict($_GET ['itemCategoryLife'], 'double'));
        }
        if (isset($_GET ['itemCategoryMinimumReOrder'])) {
            $this->setItemCategoryMinimumReOrder($this->strict($_GET ['itemCategoryMinimumReOrder'], 'integer'));
        }
        if (isset($_GET ['itemCategoryMinimumValue'])) {
            $this->setItemCategoryMinimumValue($this->strict($_GET ['itemCategoryMinimumValue'], 'integer'));
        }
        if (isset($_GET ['itemCategoryDescription'])) {
            $this->setItemCategoryDescription($this->strict($_GET ['itemCategoryDescription'], 'string'));
        }
        if (isset($_GET ['isDepreciate'])) {
            $this->setIsDepreciate($this->strict($_GET ['isDepreciate'], 'bool'));
        }
        if (isset($_GET ['itemCategoryId'])) {
            $this->setTotal(count($_GET ['itemCategoryId']));
            if (is_array($_GET ['itemCategoryId'])) {
                $this->itemCategoryId = array();
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
            if (isset($_GET ['itemCategoryId'])) {
                $this->setItemCategoryId($this->strict($_GET ['itemCategoryId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getItemCategoryId($i, 'array') . ",";
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
    public function getItemCategoryId($key, $type) {
        if ($type == 'single') {
            return $this->itemCategoryId;
        } else {
            if ($type == 'array') {
                return $this->itemCategoryId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getItemCategoryId ?")
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
     * @return \Core\Financial\FixedAsset\ItemCategory\Model\ItemCategoryModel
     */
    public function setItemCategoryId($value, $key, $type) {
        if ($type == 'single') {
            $this->itemCategoryId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->itemCategoryId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setItemCategoryId?")
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
     * @return \Core\Financial\FixedAsset\ItemCategory\Model\ItemCategoryModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return  Accumulative Depreciation Accounts
     * @return int $itemCategoryAccumulativeDepreciationAccounts
     */
    public function getItemCategoryAccumulativeDepreciationAccounts() {
        return $this->itemCategoryAccumulativeDepreciationAccounts;
    }

    /**
     * To Set Accumulative Depreciation Accounts
     * @param int $itemCategoryAccumulativeDepreciationAccounts Accumulative Accounts
     * @return \Core\Financial\FixedAsset\ItemCategory\Model\ItemCategoryModel
     */
    public function setItemCategoryAccumulativeDepreciationAccounts($itemCategoryAccumulativeDepreciationAccounts) {
        $this->itemCategoryAccumulativeDepreciationAccounts = $itemCategoryAccumulativeDepreciationAccounts;
        return $this;
    }

    /**
     * To Return Code
     * @return string $itemCategoryCode
     */
    public function getItemCategoryCode() {
        return $this->itemCategoryCode;
    }

    /**
     * To Set Code
     * @param string $itemCategoryCode Code
     * @return \Core\Financial\FixedAsset\ItemCategory\Model\ItemCategoryModel
     */
    public function setItemCategoryCode($itemCategoryCode) {
        $this->itemCategoryCode = $itemCategoryCode;
        return $this;
    }

    /**
     * To Return Depreciation Rate
     * @return double $itemCategoryDepreciationRate
     */
    public function getItemCategoryDepreciationRate() {
        return $this->itemCategoryDepreciationRate;
    }

    /**
     * To Set Depreciation Rate
     * @param double $itemCategoryDepreciationRate Depreciation Rate
     * @return \Core\Financial\FixedAsset\ItemCategory\Model\ItemCategoryModel
     */
    public function setItemCategoryDepreciationRate($itemCategoryDepreciationRate) {
        $this->itemCategoryDepreciationRate = $itemCategoryDepreciationRate;
        return $this;
    }

    /**
     * To Return Life
     * @return double $itemCategoryLife
     */
    public function getItemCategoryLife() {
        return $this->itemCategoryLife;
    }

    /**
     * To Set Life
     * @param double $itemCategoryLife Life
     * @return \Core\Financial\FixedAsset\ItemCategory\Model\ItemCategoryModel
     */
    public function setItemCategoryLife($itemCategoryLife) {
        $this->itemCategoryLife = $itemCategoryLife;
        return $this;
    }

    /**
     * To Return Minimum ReOrder
     * @return int $itemCategoryMinimumReOrder
     */
    public function getItemCategoryMinimumReOrder() {
        return $this->itemCategoryMinimumReOrder;
    }

    /**
     * To Set Minimum ReOrder
     * @param int $itemCategoryMinimumReOrder Minimum Order
     * @return \Core\Financial\FixedAsset\ItemCategory\Model\ItemCategoryModel
     */
    public function setItemCategoryMinimumReOrder($itemCategoryMinimumReOrder) {
        $this->itemCategoryMinimumReOrder = $itemCategoryMinimumReOrder;
        return $this;
    }

    /**
     * To Return Minimum Value
     * @return int $itemCategoryMinimumValue
     */
    public function getItemCategoryMinimumValue() {
        return $this->itemCategoryMinimumValue;
    }

    /**
     * To Set Minimum Value
     * @param int $itemCategoryMinimumValue Minimum Value
     * @return \Core\Financial\FixedAsset\ItemCategory\Model\ItemCategoryModel
     */
    public function setItemCategoryMinimumValue($itemCategoryMinimumValue) {
        $this->itemCategoryMinimumValue = $itemCategoryMinimumValue;
        return $this;
    }

    /**
     * To Return Description
     * @return string $itemCategoryDescription
     */
    public function getItemCategoryDescription() {
        return $this->itemCategoryDescription;
    }

    /**
     * To Set Description
     * @param string $itemCategoryDescription Description
     * @return \Core\Financial\FixedAsset\ItemCategory\Model\ItemCategoryModel
     */
    public function setItemCategoryDescription($itemCategoryDescription) {
        $this->itemCategoryDescription = $itemCategoryDescription;
        return $this;
    }

    /**
     * To Return  is Depreciate
     * @return bool $isDepreciate
     */
    public function getIsDepreciate() {
        return $this->isDepreciate;
    }

    /**
     * To Set is Depreciate
     * @param bool $isDepreciate Is Depreciate
     * @return \Core\Financial\FixedAsset\ItemCategory\Model\ItemCategoryModel
     */
    public function setIsDepreciate($isDepreciate) {
        $this->isDepreciate = $isDepreciate;
        return $this;
    }

}

?>