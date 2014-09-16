<?php

namespace Core\Financial\FixedAsset\Asset\Model;

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
 * Class Asset
 * This is asset model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\FixedAsset\Asset\Model;
 * @subpackage FixedAsset
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class AssetModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $assetId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Country
     * @var int
     */
    private $countryId;

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
     * Location
     * @var int
     */
    private $locationId;

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
     * Business Partner
     * @var int
     */
    private $businessPartnerId;

    /**
     * Unit Measurement
     * @var int
     */
    private $unitOfMeasurementId;

    /**
     * Purchase Invoice
     * @var int
     */
    private $purchaseInvoiceId;

    /**
     * Code
     * @var string
     */
    private $assetCode;

    /**
     * Serial Number
     * @var string
     */
    private $assetSerialNumber;

    /**
     * Name
     * @var string
     */
    private $assetName;

    /**
     * Description
     * @var string
     */
    private $assetDescription;

    /**
     * Model
     * @var string
     */
    private $assetModel;

    /**
     * Price
     * @var double
     */
    private $assetPrice;

    /**
     * Date
     * @var string
     */
    private $assetDate;

    /**
     * Warranty
     * @var string
     */
    private $assetWarranty;

    /**
     * Color
     * @var string
     */
    private $assetColor;

    /**
     * Quantity
     * @var int
     */
    private $assetQuantity;

    /**
     * Insurance Partner
     * @var int
     */
    private $assetInsuranceBusinessPartnerId;

    /**
     * Insurance Date
     * @var string
     */
    private $assetInsuranceStartDate;

    /**
     * Insurance Date
     * @var string
     */
    private $assetInsuranceExpiredDate;

    /**
     * Warranty Date
     * @var string
     */
    private $assetWarrantyStartDate;

    /**
     * Warranty Date
     * @var string
     */
    private $assetWarrantyEndDate;

    /**
     * Depreciation Rate
     * @var double
     */
    private $assetDepreciationRate;

    /**
     * Net Value
     * @var double
     */
    private $assetNetBookValue;

    /**
     * Picture
     * @var string
     */
    private $assetPicture;

    /**
     * Is Transfer As   Kit
     * @var bool
     */
    private $isTransferAsKit;

    /**
     * Is Depreciate
     * @var bool
     */
    private $isDepreciate;

    /**
     * Is Off
     * @var bool
     */
    private $isWriteOff;

    /**
     * Is Dispose
     * @var bool
     */
    private $isDispose;

    /**
     * Is Adjust
     * @var bool
     */
    private $isAdjust;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('asset');
        $this->setPrimaryKeyName('assetId');
        $this->setMasterForeignKeyName('assetId');
        $this->setFilterCharacter('assetDescription');
        //$this->setFilterCharacter('assetNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['assetId'])) {
            $this->setAssetId($this->strict($_POST ['assetId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['countryId'])) {
            $this->setCountryId($this->strict($_POST ['countryId'], 'integer'));
        }
        if (isset($_POST ['branchId'])) {
            $this->setBranchId($this->strict($_POST ['branchId'], 'integer'));
        }
        if (isset($_POST ['departmentId'])) {
            $this->setDepartmentId($this->strict($_POST ['departmentId'], 'integer'));
        }
        if (isset($_POST ['warehouseId'])) {
            $this->setWarehouseId($this->strict($_POST ['warehouseId'], 'integer'));
        }
        if (isset($_POST ['locationId'])) {
            $this->setLocationId($this->strict($_POST ['locationId'], 'integer'));
        }
        if (isset($_POST ['itemCategoryId'])) {
            $this->setItemCategoryId($this->strict($_POST ['itemCategoryId'], 'integer'));
        }
        if (isset($_POST ['itemTypeId'])) {
            $this->setItemTypeId($this->strict($_POST ['itemTypeId'], 'integer'));
        }
        if (isset($_POST ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_POST ['businessPartnerId'], 'integer'));
        }
        if (isset($_POST ['unitOfMeasurementId'])) {
            $this->setUnitOfMeasurementId($this->strict($_POST ['unitOfMeasurementId'], 'integer'));
        }
        if (isset($_POST ['purchaseInvoiceId'])) {
            $this->setPurchaseInvoiceId($this->strict($_POST ['purchaseInvoiceId'], 'integer'));
        }
        if (isset($_POST ['assetCode'])) {
            $this->setAssetCode($this->strict($_POST ['assetCode'], 'string'));
        }
        if (isset($_POST ['assetSerialNumber'])) {
            $this->setAssetSerialNumber($this->strict($_POST ['assetSerialNumber'], 'string'));
        }
        if (isset($_POST ['assetName'])) {
            $this->setAssetName($this->strict($_POST ['assetName'], 'string'));
        }
        if (isset($_POST ['assetDescription'])) {
            $this->setAssetDescription($this->strict($_POST ['assetDescription'], 'string'));
        }
        if (isset($_POST ['assetModel'])) {
            $this->setAssetModel($this->strict($_POST ['assetModel'], 'string'));
        }
        if (isset($_POST ['assetPrice'])) {
            $this->setAssetPrice($this->strict($_POST ['assetPrice'], 'double'));
        }
        if (isset($_POST ['assetDate'])) {
            $this->setAssetDate($this->strict($_POST ['assetDate'], 'date'));
        }
        if (isset($_POST ['assetWarranty'])) {
            $this->setAssetWarranty($this->strict($_POST ['assetWarranty'], 'string'));
        }
        if (isset($_POST ['assetColor'])) {
            $this->setAssetColor($this->strict($_POST ['assetColor'], 'string'));
        }
        if (isset($_POST ['assetQuantity'])) {
            $this->setAssetQuantity($this->strict($_POST ['assetQuantity'], 'integer'));
        }
        if (isset($_POST ['assetInsuranceBusinessPartnerId'])) {
            $this->setAssetInsuranceBusinessPartnerId(
                    $this->strict($_POST ['assetInsuranceBusinessPartnerId'], 'integer')
            );
        }
        if (isset($_POST ['assetInsuranceStartDate'])) {
            $this->setAssetInsuranceStartDate($this->strict($_POST ['assetInsuranceStartDate'], 'date'));
        }
        if (isset($_POST ['assetInsuranceExpiredDate'])) {
            $this->setAssetInsuranceExpiredDate($this->strict($_POST ['assetInsuranceExpiredDate'], 'date'));
        }
        if (isset($_POST ['assetWarrantyStartDate'])) {
            $this->setAssetWarrantyStartDate($this->strict($_POST ['assetWarrantyStartDate'], 'date'));
        }
        if (isset($_POST ['assetWarrantyEndDate'])) {
            $this->setAssetWarrantyEndDate($this->strict($_POST ['assetWarrantyEndDate'], 'date'));
        }
        if (isset($_POST ['assetDepreciationRate'])) {
            $this->setAssetDepreciationRate($this->strict($_POST ['assetDepreciationRate'], 'double'));
        }
        if (isset($_POST ['assetNetBookValue'])) {
            $this->setAssetNetBookValue($this->strict($_POST ['assetNetBookValue'], 'double'));
        }
        if (isset($_POST ['assetPicture'])) {
            $this->setAssetPicture($this->strict($_POST ['assetPicture'], 'string'));
        }
        if (isset($_POST ['isTransferAsKit'])) {
            $this->setIsTransferAsKit($this->strict($_POST ['isTransferAsKit'], 'bool'));
        }
        if (isset($_POST ['isDepreciate'])) {
            $this->setIsDepreciate($this->strict($_POST ['isDepreciate'], 'bool'));
        }
        if (isset($_POST ['isWriteOff'])) {
            $this->setIsWriteOff($this->strict($_POST ['isWriteOff'], 'bool'));
        }
        if (isset($_POST ['isDispose'])) {
            $this->setIsDispose($this->strict($_POST ['isDispose'], 'bool'));
        }
        if (isset($_POST ['isAdjust'])) {
            $this->setIsAdjust($this->strict($_POST ['isAdjust'], 'bool'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['assetId'])) {
            $this->setAssetId($this->strict($_GET ['assetId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['branchId'])) {
            $this->setBranchId($this->strict($_GET ['branchId'], 'integer'));
        }
        if (isset($_GET ['departmentId'])) {
            $this->setDepartmentId($this->strict($_GET ['departmentId'], 'integer'));
        }
        if (isset($_GET ['warehouseId'])) {
            $this->setWarehouseId($this->strict($_GET ['warehouseId'], 'integer'));
        }
        if (isset($_GET ['locationId'])) {
            $this->setLocationId($this->strict($_GET ['locationId'], 'integer'));
        }
        if (isset($_GET ['itemCategoryId'])) {
            $this->setItemCategoryId($this->strict($_GET ['itemCategoryId'], 'integer'));
        }
        if (isset($_GET ['itemTypeId'])) {
            $this->setItemTypeId($this->strict($_GET ['itemTypeId'], 'integer'));
        }
        if (isset($_GET ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_GET ['businessPartnerId'], 'integer'));
        }
        if (isset($_GET ['unitOfMeasurementId'])) {
            $this->setUnitOfMeasurementId($this->strict($_GET ['unitOfMeasurementId'], 'integer'));
        }
        if (isset($_GET ['purchaseInvoiceId'])) {
            $this->setPurchaseInvoiceId($this->strict($_GET ['purchaseInvoiceId'], 'integer'));
        }
        if (isset($_GET ['assetCode'])) {
            $this->setAssetCode($this->strict($_GET ['assetCode'], 'string'));
        }
        if (isset($_GET ['assetSerialNumber'])) {
            $this->setAssetSerialNumber($this->strict($_GET ['assetSerialNumber'], 'string'));
        }
        if (isset($_GET ['assetName'])) {
            $this->setAssetName($this->strict($_GET ['assetName'], 'string'));
        }
        if (isset($_GET ['assetDescription'])) {
            $this->setAssetDescription($this->strict($_GET ['assetDescription'], 'string'));
        }
        if (isset($_GET ['assetModel'])) {
            $this->setAssetModel($this->strict($_GET ['assetModel'], 'string'));
        }
        if (isset($_GET ['assetPrice'])) {
            $this->setAssetPrice($this->strict($_GET ['assetPrice'], 'double'));
        }
        if (isset($_GET ['assetDate'])) {
            $this->setAssetDate($this->strict($_GET ['assetDate'], 'date'));
        }
        if (isset($_GET ['assetWarranty'])) {
            $this->setAssetWarranty($this->strict($_GET ['assetWarranty'], 'string'));
        }
        if (isset($_GET ['assetColor'])) {
            $this->setAssetColor($this->strict($_GET ['assetColor'], 'string'));
        }
        if (isset($_GET ['assetQuantity'])) {
            $this->setAssetQuantity($this->strict($_GET ['assetQuantity'], 'integer'));
        }
        if (isset($_GET ['assetInsuranceBusinessPartnerId'])) {
            $this->setAssetInsuranceBusinessPartnerId(
                    $this->strict($_GET ['assetInsuranceBusinessPartnerId'], 'integer')
            );
        }
        if (isset($_GET ['assetInsuranceStartDate'])) {
            $this->setAssetInsuranceStartDate($this->strict($_GET ['assetInsuranceStartDate'], 'date'));
        }
        if (isset($_GET ['assetInsuranceExpiredDate'])) {
            $this->setAssetInsuranceExpiredDate($this->strict($_GET ['assetInsuranceExpiredDate'], 'date'));
        }
        if (isset($_GET ['assetWarrantyStartDate'])) {
            $this->setAssetWarrantyStartDate($this->strict($_GET ['assetWarrantyStartDate'], 'date'));
        }
        if (isset($_GET ['assetWarrantyEndDate'])) {
            $this->setAssetWarrantyEndDate($this->strict($_GET ['assetWarrantyEndDate'], 'date'));
        }
        if (isset($_GET ['assetDepreciationRate'])) {
            $this->setAssetDepreciationRate($this->strict($_GET ['assetDepreciationRate'], 'double'));
        }
        if (isset($_GET ['assetNetBookValue'])) {
            $this->setAssetNetBookValue($this->strict($_GET ['assetNetBookValue'], 'double'));
        }
        if (isset($_GET ['assetPicture'])) {
            $this->setAssetPicture($this->strict($_GET ['assetPicture'], 'string'));
        }
        if (isset($_GET ['isTransferAsKit'])) {
            $this->setIsTransferAsKit($this->strict($_GET ['isTransferAsKit'], 'bool'));
        }
        if (isset($_GET ['isDepreciate'])) {
            $this->setIsDepreciate($this->strict($_GET ['isDepreciate'], 'bool'));
        }
        if (isset($_GET ['isWriteOff'])) {
            $this->setIsWriteOff($this->strict($_GET ['isWriteOff'], 'bool'));
        }
        if (isset($_GET ['isDispose'])) {
            $this->setIsDispose($this->strict($_GET ['isDispose'], 'bool'));
        }
        if (isset($_GET ['isAdjust'])) {
            $this->setIsAdjust($this->strict($_GET ['isAdjust'], 'bool'));
        }
        if (isset($_GET ['assetId'])) {
            $this->setTotal(count($_GET ['assetId']));
            if (is_array($_GET ['assetId'])) {
                $this->assetId = array();
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
            if (isset($_GET ['assetId'])) {
                $this->setAssetId($this->strict($_GET ['assetId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getAssetId($i, 'array') . ",";
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
    public function getAssetId($key, $type) {
        if ($type == 'single') {
            return $this->assetId;
        } else {
            if ($type == 'array') {
                return $this->assetId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getassetId ?")
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
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setAssetId($value, $key, $type) {
        if ($type == 'single') {
            $this->assetId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->assetId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setassetId?")
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
     * To Return  Company
     * @return int $companyId
     */
    public function getCompanyId() {
        return $this->companyId;
    }

    /**
     * To Set Company
     * @param int $companyId Company
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return  Branch
     * @return int $branchId
     */
    public function getBranchId() {
        return $this->branchId;
    }

    /**
     * To Set Branch
     * @param int $branchId Branch
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setBranchId($branchId) {
        $this->branchId = $branchId;
        return $this;
    }

    /**
     * To Return  Department
     * @return int $departmentId
     */
    public function getDepartmentId() {
        return $this->departmentId;
    }

    /**
     * To Set Department
     * @param int $departmentId Department
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setDepartmentId($departmentId) {
        $this->departmentId = $departmentId;
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
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setWarehouseId($warehouseId) {
        $this->warehouseId = $warehouseId;
        return $this;
    }

    /**
     * To Return  Location
     * @return int $locationId
     */
    public function getLocationId() {
        return $this->locationId;
    }

    /**
     * To Set Location
     * @param int $locationId Location
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setLocationId($locationId) {
        $this->locationId = $locationId;
        return $this;
    }

    /**
     * To Return  ItemCategory
     * @return int $itemCategoryId
     */
    public function getItemCategoryId() {
        return $this->itemCategoryId;
    }

    /**
     * To Set ItemCategory
     * @param int $itemCategoryId Item Category
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setItemCategoryId($itemCategoryId) {
        $this->itemCategoryId = $itemCategoryId;
        return $this;
    }

    /**
     * To Return  ItemType
     * @return int $itemTypeId
     */
    public function getItemTypeId() {
        return $this->itemTypeId;
    }

    /**
     * To Set ItemType
     * @param int $itemTypeId Item Type
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setItemTypeId($itemTypeId) {
        $this->itemTypeId = $itemTypeId;
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
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setBusinessPartnerId($businessPartnerId) {
        $this->businessPartnerId = $businessPartnerId;
        return $this;
    }

    /**
     * To Return  UnitOfMeasurement
     * @return int $unitOfMeasurementId
     */
    public function getUnitOfMeasurementId() {
        return $this->unitOfMeasurementId;
    }

    /**
     * To Set UnitOfMeasurement
     * @param int $unitOfMeasurementId Unit Measurement
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setUnitOfMeasurementId($unitOfMeasurementId) {
        $this->unitOfMeasurementId = $unitOfMeasurementId;
        return $this;
    }

    /**
     * To Return  PurchaseInvoice
     * @return int $purchaseInvoiceId
     */
    public function getPurchaseInvoiceId() {
        return $this->purchaseInvoiceId;
    }

    /**
     * To Set PurchaseInvoice
     * @param int $purchaseInvoiceId Purchase Order
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setPurchaseInvoiceId($purchaseInvoiceId) {
        $this->purchaseInvoiceId = $purchaseInvoiceId;
        return $this;
    }

    /**
     * To Return  Code
     * @return string $assetCode
     */
    public function getAssetCode() {
        return $this->assetCode;
    }

    /**
     * To Set Code
     * @param string $assetCode Code
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setAssetCode($assetCode) {
        $this->assetCode = $assetCode;
        return $this;
    }

    /**
     * To Return  SerialNumber
     * @return string $assetSerialNumber
     */
    public function getAssetSerialNumber() {
        return $this->assetSerialNumber;
    }

    /**
     * To Set SerialNumber
     * @param string $assetSerialNumber Serial Number
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setAssetSerialNumber($assetSerialNumber) {
        $this->assetSerialNumber = $assetSerialNumber;
        return $this;
    }

    /**
     * To Return  Name
     * @return string $assetName
     */
    public function getAssetName() {
        return $this->assetName;
    }

    /**
     * To Set Name
     * @param string $assetName Name
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setAssetName($assetName) {
        $this->assetName = $assetName;
        return $this;
    }

    /**
     * To Return  Description
     * @return string $assetDescription
     */
    public function getAssetDescription() {
        return $this->assetDescription;
    }

    /**
     * To Set Description
     * @param string $assetDescription Description
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setAssetDescription($assetDescription) {
        $this->assetDescription = $assetDescription;
        return $this;
    }

    /**
     * To Return  Model
     * @return string $assetModel
     */
    public function getAssetModel() {
        return $this->assetModel;
    }

    /**
     * To Set Model
     * @param string $assetModel Model
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setAssetModel($assetModel) {
        $this->assetModel = $assetModel;
        return $this;
    }

    /**
     * To Return  Price
     * @return double $assetPrice
     */
    public function getAssetPrice() {
        return $this->assetPrice;
    }

    /**
     * To Set Price
     * @param double $assetPrice Price
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setAssetPrice($assetPrice) {
        $this->assetPrice = $assetPrice;
        return $this;
    }

    /**
     * To Return Date
     * @return string $assetDate
     */
    public function getAssetDate() {
        return $this->assetDate;
    }

    /**
     * To Set Date
     * @param string $assetDate Date
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setAssetDate($assetDate) {
        $this->assetDate = $assetDate;
        return $this;
    }

    /**
     * To Return  Warranty
     * @return string $assetWarranty
     */
    public function getAssetWarranty() {
        return $this->assetWarranty;
    }

    /**
     * To Set Warranty
     * @param string $assetWarranty Warranty
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setAssetWarranty($assetWarranty) {
        $this->assetWarranty = $assetWarranty;
        return $this;
    }

    /**
     * To Return  Color
     * @return string $assetColor
     */
    public function getAssetColor() {
        return $this->assetColor;
    }

    /**
     * To Set Color
     * @param string $assetColor Color
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setAssetColor($assetColor) {
        $this->assetColor = $assetColor;
        return $this;
    }

    /**
     * To Return  Quantity
     * @return int $assetQuantity
     */
    public function getAssetQuantity() {
        return $this->assetQuantity;
    }

    /**
     * To Set Quantity
     * @param int $assetQuantity Quantity
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setAssetQuantity($assetQuantity) {
        $this->assetQuantity = $assetQuantity;
        return $this;
    }

    /**
     * To Return  InsuranceBusinessPartner
     * @return int $assetInsuranceBusinessPartnerId
     */
    public function getAssetInsuranceBusinessPartnerId() {
        return $this->assetInsuranceBusinessPartnerId;
    }

    /**
     * To Set InsuranceBusinessPartner
     * @param int $assetInsuranceBusinessPartnerId Insurance Partner
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setAssetInsuranceBusinessPartnerId($assetInsuranceBusinessPartnerId) {
        $this->assetInsuranceBusinessPartnerId = $assetInsuranceBusinessPartnerId;
        return $this;
    }

    /**
     * To Return  InsuranceStartDate
     * @return string $assetInsuranceStartDate
     */
    public function getAssetInsuranceStartDate() {
        return $this->assetInsuranceStartDate;
    }

    /**
     * To Set InsuranceStartDate
     * @param string $assetInsuranceStartDate Insurance Date
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setAssetInsuranceStartDate($assetInsuranceStartDate) {
        $this->assetInsuranceStartDate = $assetInsuranceStartDate;
        return $this;
    }

    /**
     * To Return  InsuranceExpiredDate
     * @return string $assetInsuranceExpiredDate
     */
    public function getAssetInsuranceExpiredDate() {
        return $this->assetInsuranceExpiredDate;
    }

    /**
     * To Set InsuranceExpiredDate
     * @param string $assetInsuranceExpiredDate Insurance Date
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setAssetInsuranceExpiredDate($assetInsuranceExpiredDate) {
        $this->assetInsuranceExpiredDate = $assetInsuranceExpiredDate;
        return $this;
    }

    /**
     * To Return  WarrantyStartDate
     * @return string $assetWarrantyStartDate
     */
    public function getAssetWarrantyStartDate() {
        return $this->assetWarrantyStartDate;
    }

    /**
     * To Set WarrantyStartDate
     * @param string $assetWarrantyStartDate Warranty Date
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setAssetWarrantyStartDate($assetWarrantyStartDate) {
        $this->assetWarrantyStartDate = $assetWarrantyStartDate;
        return $this;
    }

    /**
     * To Return  WarrantyEndDate
     * @return string $assetWarrantyEndDate
     */
    public function getAssetWarrantyEndDate() {
        return $this->assetWarrantyEndDate;
    }

    /**
     * To Set WarrantyEndDate
     * @param string $assetWarrantyEndDate Warranty Date
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setAssetWarrantyEndDate($assetWarrantyEndDate) {
        $this->assetWarrantyEndDate = $assetWarrantyEndDate;
        return $this;
    }

    /**
     * To Return  DepreciationRate
     * @return double $assetDepreciationRate
     */
    public function getAssetDepreciationRate() {
        return $this->assetDepreciationRate;
    }

    /**
     * To Set DepreciationRate
     * @param double $assetDepreciationRate Depreciation Rate
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setAssetDepreciationRate($assetDepreciationRate) {
        $this->assetDepreciationRate = $assetDepreciationRate;
        return $this;
    }

    /**
     * To Return  NetBookValue
     * @return double $assetNetBookValue
     */
    public function getAssetNetBookValue() {
        return $this->assetNetBookValue;
    }

    /**
     * To Set NetBookValue
     * @param double $assetNetBookValue Net Value
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setAssetNetBookValue($assetNetBookValue) {
        $this->assetNetBookValue = $assetNetBookValue;
        return $this;
    }

    /**
     * To Return  Picture
     * @return string $assetPicture
     */
    public function getAssetPicture() {
        return $this->assetPicture;
    }

    /**
     * To Set Picture
     * @param string $assetPicture Picture
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setAssetPicture($assetPicture) {
        $this->assetPicture = $assetPicture;
        return $this;
    }

    /**
     * To Return  isTransferAsKit
     * @return bool $isTransferAsKit
     */
    public function getIsTransferAsKit() {
        return $this->isTransferAsKit;
    }

    /**
     * To Set isTransferAsKit
     * @param bool $isTransferAsKit Is Transfer As   Kit
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setIsTransferAsKit($isTransferAsKit) {
        $this->isTransferAsKit = $isTransferAsKit;
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
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setIsDepreciate($isDepreciate) {
        $this->isDepreciate = $isDepreciate;
        return $this;
    }

    /**
     * To Return  isWriteOff
     * @return bool $isWriteOff
     */
    public function getIsWriteOff() {
        return $this->isWriteOff;
    }

    /**
     * To Set isWriteOff
     * @param bool $isWriteOff Is Off
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setIsWriteOff($isWriteOff) {
        $this->isWriteOff = $isWriteOff;
        return $this;
    }

    /**
     * To Return isDispose
     * @return bool $isDispose
     */
    public function getIsDispose() {
        return $this->isDispose;
    }

    /**
     * To Set isDispose
     * @param bool $isDispose Is Dispose
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setIsDispose($isDispose) {
        $this->isDispose = $isDispose;
        return $this;
    }

    /**
     * To Return isAdjust
     * @return bool $isAdjust
     */
    public function getIsAdjust() {
        return $this->isAdjust;
    }

    /**
     * To Set isAdjust
     * @param bool $isAdjust Is Adjust
     * @return \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public function setIsAdjust($isAdjust) {
        $this->isAdjust = $isAdjust;
        return $this;
    }

}

?>