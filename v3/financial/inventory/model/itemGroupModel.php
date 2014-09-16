<?php

namespace Core\Financial\Inventory\ItemGroup\Model;

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
 * Class ItemGroup
 * This is itemGroup model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\Inventory\ItemGroup\Model;
 * @subpackage Inventory
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ItemGroupModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $itemGroupId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Accumulative Accounts
     * @var int
     */
    private $itemGroupAccumulativeDepreciationAccounts;

    /**
     * Code
     * @var string
     */
    private $itemGroupCode;

    /**
     * Depreciation Rate
     * @var double
     */
    private $itemGroupDepreciationRate;

    /**
     * Life
     * @var double
     */
    private $itemGroupLife;

    /**
     * Minimum Order
     * @var int
     */
    private $itemGroupMinimumReOrder;

    /**
     * Minimum Value
     * @var int
     */
    private $itemGroupMinimumValue;

    /**
     * Description
     * @var string
     */
    private $itemGroupDescription;

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
        $this->setTableName('itemGroup');
        $this->setPrimaryKeyName('itemGroupId');
        $this->setMasterForeignKeyName('itemGroupId');
        $this->setFilterCharacter('itemGroupDescription');
        //$this->setFilterCharacter('itemGroupNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['itemGroupId'])) {
            $this->setItemGroupId($this->strict($_POST ['itemGroupId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['itemGroupAccumulativeDepreciationAccounts'])) {
            $this->setItemGroupAccumulativeDepreciationAccounts(
                    $this->strict($_POST ['itemGroupAccumulativeDepreciationAccounts'], 'integer')
            );
        }
        if (isset($_POST ['itemGroupCode'])) {
            $this->setItemGroupCode($this->strict($_POST ['itemGroupCode'], 'string'));
        }
        if (isset($_POST ['itemGroupDepreciationRate'])) {
            $this->setItemGroupDepreciationRate($this->strict($_POST ['itemGroupDepreciationRate'], 'double'));
        }
        if (isset($_POST ['itemGroupLife'])) {
            $this->setItemGroupLife($this->strict($_POST ['itemGroupLife'], 'double'));
        }
        if (isset($_POST ['itemGroupMinimumReOrder'])) {
            $this->setItemGroupMinimumReOrder($this->strict($_POST ['itemGroupMinimumReOrder'], 'integer'));
        }
        if (isset($_POST ['itemGroupMinimumValue'])) {
            $this->setItemGroupMinimumValue($this->strict($_POST ['itemGroupMinimumValue'], 'integer'));
        }
        if (isset($_POST ['itemGroupDescription'])) {
            $this->setItemGroupDescription($this->strict($_POST ['itemGroupDescription'], 'string'));
        }
        if (isset($_POST ['isDepreciate'])) {
            $this->setIsDepreciate($this->strict($_POST ['isDepreciate'], 'bool'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['itemGroupId'])) {
            $this->setItemGroupId($this->strict($_GET ['itemGroupId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['itemGroupAccumulativeDepreciationAccounts'])) {
            $this->setItemGroupAccumulativeDepreciationAccounts(
                    $this->strict($_GET ['itemGroupAccumulativeDepreciationAccounts'], 'integer')
            );
        }
        if (isset($_GET ['itemGroupCode'])) {
            $this->setItemGroupCode($this->strict($_GET ['itemGroupCode'], 'string'));
        }
        if (isset($_GET ['itemGroupDepreciationRate'])) {
            $this->setItemGroupDepreciationRate($this->strict($_GET ['itemGroupDepreciationRate'], 'double'));
        }
        if (isset($_GET ['itemGroupLife'])) {
            $this->setItemGroupLife($this->strict($_GET ['itemGroupLife'], 'double'));
        }
        if (isset($_GET ['itemGroupMinimumReOrder'])) {
            $this->setItemGroupMinimumReOrder($this->strict($_GET ['itemGroupMinimumReOrder'], 'integer'));
        }
        if (isset($_GET ['itemGroupMinimumValue'])) {
            $this->setItemGroupMinimumValue($this->strict($_GET ['itemGroupMinimumValue'], 'integer'));
        }
        if (isset($_GET ['itemGroupDescription'])) {
            $this->setItemGroupDescription($this->strict($_GET ['itemGroupDescription'], 'string'));
        }
        if (isset($_GET ['isDepreciate'])) {
            $this->setIsDepreciate($this->strict($_GET ['isDepreciate'], 'bool'));
        }
        if (isset($_GET ['itemGroupId'])) {
            $this->setTotal(count($_GET ['itemGroupId']));
            if (is_array($_GET ['itemGroupId'])) {
                $this->itemGroupId = array();
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
            if (isset($_GET ['itemGroupId'])) {
                $this->setItemGroupId($this->strict($_GET ['itemGroupId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getItemGroupId($i, 'array') . ",";
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
     * @return \Core\Financial\Inventory\ItemGroup\Model\ItemGroupModel
     */
    public function setItemGroupId($value, $key, $type) {
        if ($type == 'single') {
            $this->itemGroupId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->itemGroupId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setitemGroupId?")
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
    public function getItemGroupId($key, $type) {
        if ($type == 'single') {
            return $this->itemGroupId;
        } else {
            if ($type == 'array') {
                return $this->itemGroupId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getitemGroupId ?")
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
     * @return \Core\Financial\Inventory\ItemGroup\Model\ItemGroupModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return  AccumulativeDepreciationAccounts
     * @return int $itemGroupAccumulativeDepreciationAccounts
     */
    public function getItemGroupAccumulativeDepreciationAccounts() {
        return $this->itemGroupAccumulativeDepreciationAccounts;
    }

    /**
     * To Set AccumulativeDepreciationAccounts
     * @param int $itemGroupAccumulativeDepreciationAccounts Accumulative Accounts
     * @return \Core\Financial\Inventory\ItemGroup\Model\ItemGroupModel
     */
    public function setItemGroupAccumulativeDepreciationAccounts($itemGroupAccumulativeDepreciationAccounts) {
        $this->itemGroupAccumulativeDepreciationAccounts = $itemGroupAccumulativeDepreciationAccounts;
        return $this;
    }

    /**
     * To Return  Code
     * @return string $itemGroupCode
     */
    public function getItemGroupCode() {
        return $this->itemGroupCode;
    }

    /**
     * To Set Code
     * @param string $itemGroupCode Code
     * @return \Core\Financial\Inventory\ItemGroup\Model\ItemGroupModel
     */
    public function setItemGroupCode($itemGroupCode) {
        $this->itemGroupCode = $itemGroupCode;
        return $this;
    }

    /**
     * To Return  DepreciationRate
     * @return double $itemGroupDepreciationRate
     */
    public function getItemGroupDepreciationRate() {
        return $this->itemGroupDepreciationRate;
    }

    /**
     * To Set DepreciationRate
     * @param double $itemGroupDepreciationRate Depreciation Rate
     * @return \Core\Financial\Inventory\ItemGroup\Model\ItemGroupModel
     */
    public function setItemGroupDepreciationRate($itemGroupDepreciationRate) {
        $this->itemGroupDepreciationRate = $itemGroupDepreciationRate;
        return $this;
    }

    /**
     * To Return  Life
     * @return double $itemGroupLife
     */
    public function getItemGroupLife() {
        return $this->itemGroupLife;
    }

    /**
     * To Set Life
     * @param double $itemGroupLife Life
     * @return \Core\Financial\Inventory\ItemGroup\Model\ItemGroupModel
     */
    public function setItemGroupLife($itemGroupLife) {
        $this->itemGroupLife = $itemGroupLife;
        return $this;
    }

    /**
     * To Return  MinimumReOrder
     * @return int $itemGroupMinimumReOrder
     */
    public function getItemGroupMinimumReOrder() {
        return $this->itemGroupMinimumReOrder;
    }

    /**
     * To Set MinimumReOrder
     * @param int $itemGroupMinimumReOrder Minimum Order
     * @return \Core\Financial\Inventory\ItemGroup\Model\ItemGroupModel
     */
    public function setItemGroupMinimumReOrder($itemGroupMinimumReOrder) {
        $this->itemGroupMinimumReOrder = $itemGroupMinimumReOrder;
        return $this;
    }

    /**
     * To Return  MinimumValue
     * @return int $itemGroupMinimumValue
     */
    public function getItemGroupMinimumValue() {
        return $this->itemGroupMinimumValue;
    }

    /**
     * To Set MinimumValue
     * @param int $itemGroupMinimumValue Minimum Value
     * @return \Core\Financial\Inventory\ItemGroup\Model\ItemGroupModel
     */
    public function setItemGroupMinimumValue($itemGroupMinimumValue) {
        $this->itemGroupMinimumValue = $itemGroupMinimumValue;
        return $this;
    }

    /**
     * To Return  Description
     * @return string $itemGroupDescription
     */
    public function getItemGroupDescription() {
        return $this->itemGroupDescription;
    }

    /**
     * To Set Description
     * @param string $itemGroupDescription Description
     * @return \Core\Financial\Inventory\ItemGroup\Model\ItemGroupModel
     */
    public function setItemGroupDescription($itemGroupDescription) {
        $this->itemGroupDescription = $itemGroupDescription;
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
     * @return \Core\Financial\Inventory\ItemGroup\Model\ItemGroupModel
     */
    public function setIsDepreciate($isDepreciate) {
        $this->isDepreciate = $isDepreciate;
        return $this;
    }

}

?>