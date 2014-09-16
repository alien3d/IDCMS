<?php

namespace Core\Financial\FixedAsset\AssetDepreciationDetail\Model;

// using obsolute path instead of relative path..
// start fake document root. it's absolute path

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
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot);
require_once($newFakeDocumentRoot . "library/class/classValidation.php");

/**
 * Class AssetDepreciationDetailModel
 * this is Asset Depreciation Detail Model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\FixedAsset\AssetDepreciationDetail\Model
 * @subpackage Asset
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class AssetDepreciationDetailModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $assetDepreciationDetailId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Category Primary Key
     * @var int
     */
    private $itemCategoryId;

    /**
     * Type Primary Key
     * @var int
     */
    private $itemTypeId;

    /**
     * assetId
     * @var int
     */
    private $assetId;

    /**
     * Price
     * @var float
     */
    private $assetPrice;

    /**
     * Document Number
     * @var string
     */
    private $documentNumber;

    /**
     * Transaction Date
     * @var string
     */
    private $transactionDate;

    /**
     * Month To Date
     * @var float
     */
    private $monthToDate;

    /**
     * Year To Date
     * @var float
     */
    private $yearToDate;

    /**
     * Finance Period
     * @var int
     */
    private $financePeriod;

    /**
     * Finance Year
     * @var int
     */
    private $financeYear;

    /**
     * Current Net Book Value
     * @var float
     */
    private $currentNetBookValue;

    /**
     * Depreciation Rate
     * @var float
     * @var float
     */
    private $assetDepreciationRate;

    /**
     * Life
     * @var int
     */
    private $assetLife;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         */
        $this->setTableName('assetDepreciationDetail');
        $this->setPrimaryKeyName('assetDepreciationDetailId');
        $this->setMasterForeignKeyName('assetDepreciationDetailId');
        $this->setFilterCharacter('assetDepreciationDetailDescription');
        //$this->setFilterCharacter('assetDepreciationDetailNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['assetDepreciationDetailId'])) {
            $this->setAssetDepreciationDetailId(
                    $this->strict($_POST ['assetDepreciationDetailId'], 'integer'), 0, 'single'
            );
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['itemCategoryId'])) {
            $this->setItemCategoryId($this->strict($_POST ['itemCategoryId'], 'integer'));
        }
        if (isset($_POST ['itemTypeId'])) {
            $this->setItemTypeId($this->strict($_POST ['itemTypeId'], 'integer'));
        }
        if (isset($_POST ['assetId'])) {
            $this->setAssetId($this->strict($_POST ['assetId'], 'integer'));
        }
        if (isset($_POST ['assetPrice'])) {
            $this->setAssetPrice($this->strict($_POST ['assetPrice'], 'float'));
        }
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'text'));
        }
        if (isset($_POST ['transactionDate'])) {
            $this->setTransactionDate($this->strict($_POST ['transactionDate'], 'date'));
        }
        if (isset($_POST ['monthToDate'])) {
            $this->setMonthToDate($this->strict($_POST ['monthToDate'], 'float'));
        }
        if (isset($_POST ['yearToDate'])) {
            $this->setYearToDate($this->strict($_POST ['yearToDate'], 'float'));
        }
        if (isset($_POST ['financePeriod'])) {
            $this->setFinancePeriod($this->strict($_POST ['financePeriod'], 'integer'));
        }
        if (isset($_POST ['financeYear'])) {
            $this->setFinanceYear($this->strict($_POST ['financeYear'], 'integer'));
        }
        if (isset($_POST ['currentNetBookValue'])) {
            $this->setCurrentNetBookValue($this->strict($_POST ['currentNetBookValue'], 'float'));
        }
        if (isset($_POST ['assetDepreciationRate'])) {
            $this->setAssetDepreciationRate($this->strict($_POST ['assetDepreciationRate'], 'float'));
        }
        if (isset($_POST ['assetLife'])) {
            $this->setAssetLife($this->strict($_POST ['assetLife'], 'integer'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['assetDepreciationDetailId'])) {
            $this->setAssetDepreciationDetailId(
                    $this->strict($_GET ['assetDepreciationDetailId'], 'integer'), 0, 'single'
            );
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['itemCategoryId'])) {
            $this->setItemCategoryId($this->strict($_GET ['itemCategoryId'], 'integer'));
        }
        if (isset($_GET ['itemTypeId'])) {
            $this->setItemTypeId($this->strict($_GET ['itemTypeId'], 'integer'));
        }
        if (isset($_GET ['assetId'])) {
            $this->setAssetId($this->strict($_GET ['assetId'], 'integer'));
        }
        if (isset($_GET ['assetPrice'])) {
            $this->setAssetPrice($this->strict($_GET ['assetPrice'], 'float'));
        }
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'text'));
        }
        if (isset($_GET ['transactionDate'])) {
            $this->setTransactionDate($this->strict($_GET ['transactionDate'], 'date'));
        }
        if (isset($_GET ['monthToDate'])) {
            $this->setMonthToDate($this->strict($_GET ['monthToDate'], 'float'));
        }
        if (isset($_GET ['yearToDate'])) {
            $this->setYearToDate($this->strict($_GET ['yearToDate'], 'float'));
        }
        if (isset($_GET ['financePeriod'])) {
            $this->setFinancePeriod($this->strict($_GET ['financePeriod'], 'integer'));
        }
        if (isset($_GET ['financeYear'])) {
            $this->setFinanceYear($this->strict($_GET ['financeYear'], 'integer'));
        }
        if (isset($_GET ['currentNetBookValue'])) {
            $this->setCurrentNetBookValue($this->strict($_GET ['currentNetBookValue'], 'float'));
        }
        if (isset($_GET ['assetDepreciationRate'])) {
            $this->setAssetDepreciationRate($this->strict($_GET ['assetDepreciationRate'], 'float'));
        }
        if (isset($_GET ['assetLife'])) {
            $this->setAssetLife($this->strict($_GET ['assetLife'], 'integer'));
        }
        if (isset($_GET ['assetDepreciationDetailId'])) {
            $this->setTotal(count($_GET ['assetDepreciationDetailId']));
            if (is_array($_GET ['assetDepreciationDetailId'])) {
                $this->assetDepreciationDetailId = array();
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
            if (isset($_GET ['assetDepreciationDetailId'])) {
                $this->setAssetDepreciationDetailId(
                        $this->strict($_GET ['assetDepreciationDetailId'] [$i], 'numeric'), $i, 'array'
                );
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
            $primaryKeyAll .= $this->getAssetDepreciationDetailId($i, 'array') . ",";
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
     * Return Primary Key Primary Key  Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     */
    public function getAssetDepreciationDetailId($key, $type) {
        if ($type == 'single') {
            return $this->assetDepreciationDetailId;
        } else {
            if ($type == 'array') {
                return $this->assetDepreciationDetailId [$key];
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:getAssetDepreciationDetailId ?"
                        )
                );
                exit();
            }
        }
    }

    /**
     * Set Primary Key Primary Key  Value
     * @param int|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return \Core\Financial\FixedAsset\AssetDepreciationDetail\Model\AssetDepreciationDetailModel
     */
    public function setAssetDepreciationDetailId($value, $key, $type) {
        if ($type == 'single') {
            $this->assetDepreciationDetailId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->assetDepreciationDetailId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:setAssetDepreciationDetailId?"
                        )
                );
                exit();
            }
        }
    }

    /**
     * Create
     * @see ValidationClass::create()
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
     * @param int $companyId
     * @return \Core\Financial\FixedAsset\AssetDepreciationDetail\Model\AssetDepreciationDetailModel
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
     * @param int $itemCategoryId
     * @return \Core\Financial\FixedAsset\AssetDepreciationDetail\Model\AssetDepreciationDetailModel
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
     * @param int $itemTypeId
     * @return \Core\Financial\FixedAsset\AssetDepreciationDetail\Model\AssetDepreciationDetailModel
     */
    public function setItemTypeId($itemTypeId) {
        $this->itemTypeId = $itemTypeId;
        return $this;
    }

    /**
     * To Return Asset
     * @return int $assetId
     */
    public function getAssetId() {
        return $this->assetId;
    }

    /**
     * To Set Asset
     * @param int $assetId
     * @return \Core\Financial\FixedAsset\AssetDepreciationDetail\Model\AssetDepreciationDetailModel
     */
    public function setAssetId($assetId) {
        $this->assetId = $assetId;
        return $this;
    }

    /**
     * To Return Asset Price
     * @return float $assetPrice
     */
    public function getAssetPrice() {
        return $this->assetPrice;
    }

    /**
     * To Set Asset Price
     * @param float $assetPrice
     * @return \Core\Financial\FixedAsset\AssetDepreciationDetail\Model\AssetDepreciationDetailModel
     */
    public function setAssetPrice($assetPrice) {
        $this->assetPrice = $assetPrice;
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
     * @param string $documentNumber
     * @return \Core\Financial\FixedAsset\AssetDepreciationDetail\Model\AssetDepreciationDetailModel
     */
    public function setDocumentNumber($documentNumber) {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    /**
     * To Return Transaction Date
     * @return string $transactionDate
     */
    public function getTransactionDate() {
        return $this->transactionDate;
    }

    /**
     * To Set Transaction Date
     * @param string $transactionDate
     * @return \Core\Financial\FixedAsset\AssetDepreciationDetail\Model\AssetDepreciationDetailModel
     */
    public function setTransactionDate($transactionDate) {
        $this->transactionDate = $transactionDate;
        return $this;
    }

    /**
     * To Return Month To Date
     * @return float $monthToDate
     */
    public function getMonthToDate() {
        return $this->monthToDate;
    }

    /**
     * To Set Month To Date
     * @param float $monthToDate
     * @return \Core\Financial\FixedAsset\AssetDepreciationDetail\Model\AssetDepreciationDetailModel
     */
    public function setMonthToDate($monthToDate) {
        $this->monthToDate = $monthToDate;
        return $this;
    }

    /**
     * To Return Year To Date
     * @return float $yearToDate
     */
    public function getYearToDate() {
        return $this->yearToDate;
    }

    /**
     * To Set Year To Date
     * @param float $yearToDate
     * @return \Core\Financial\FixedAsset\AssetDepreciationDetail\Model\AssetDepreciationDetailModel
     */
    public function setYearToDate($yearToDate) {
        $this->yearToDate = $yearToDate;
        return $this;
    }

    /**
     * To Return Finance Period
     * @return int $financePeriod
     */
    public function getFinancePeriod() {
        return $this->financePeriod;
    }

    /**
     * To Set Finance Period
     * @param int $financePeriod
     * @return \Core\Financial\FixedAsset\AssetDepreciationDetail\Model\AssetDepreciationDetailModel
     */
    public function setFinancePeriod($financePeriod) {
        $this->financePeriod = $financePeriod;
        return $this;
    }

    /**
     * To Return Finance Year
     * @return int $financeYear
     */
    public function getFinanceYear() {
        return $this->financeYear;
    }

    /**
     * To Set Finance Year
     * @param int $financeYear
     * @return \Core\Financial\FixedAsset\AssetDepreciationDetail\Model\AssetDepreciationDetailModel
     */
    public function setFinanceYear($financeYear) {
        $this->financeYear = $financeYear;
        return $this;
    }

    /**
     * To Return Current Net Book Value
     * @return float $currentNetBookValue
     */
    public function getCurrentNetBookValue() {
        return $this->currentNetBookValue;
    }

    /**
     * To Set Current Net Book Value
     * @param float $currentNetBookValue
     * @return \Core\Financial\FixedAsset\AssetDepreciationDetail\Model\AssetDepreciationDetailModel
     */
    public function setCurrentNetBookValue($currentNetBookValue) {
        $this->currentNetBookValue = $currentNetBookValue;
        return $this;
    }

    /**
     * To Return Depreciation Rate
     * @return float $assetDepreciationRate
     */
    public function getAssetDepreciationRate() {
        return $this->assetDepreciationRate;
    }

    /**
     * To Set Depreciation Rate
     * @param float $assetDepreciationRate
     * @return \Core\Financial\FixedAsset\AssetDepreciationDetail\Model\AssetDepreciationDetailModel
     */
    public function setAssetDepreciationRate($assetDepreciationRate) {
        $this->assetDepreciationRate = $assetDepreciationRate;
        return $this;
    }

    /**
     * To Return Life
     * @return int $assetLife
     */
    public function getAssetLife() {
        return $this->assetLife;
    }

    /**
     * To Set Life
     * @param int $assetLife
     * @return \Core\Financial\FixedAsset\AssetDepreciationDetail\Model\AssetDepreciationDetailModel
     */
    public function setAssetLife($assetLife) {
        $this->assetLife = $assetLife;
        return $this;
    }

}

?>