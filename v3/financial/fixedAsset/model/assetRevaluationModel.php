<?php

namespace Core\Financial\FixedAsset\AssetRevaluation\Model;

// using Absolute path instead of relative path..
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
 * Class AssetRevaluationModel
 * this is Asset Revaluation Model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\FixedAsset\AssetRevaluation\Model
 * @subpackage Asset
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class AssetRevaluationModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $assetRevaluationId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Category
     * @var int
     */
    private $itemCategoryId;

    /**
     * Type
     * @var int
     */
    private $itemTypeId;

    /**
     * Asset
     * @var int
     */
    private $assetId;

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
     * Reference Number
     * @var string
     */
    private $assetRevaluationReference;

    /**
     * Price
     * @var
     */
    private $assetRevaluationPrice;

    /**
     * Value
     * @var float
     */
    private $assetRevaluationValue;

    /**
     * Net Book Value
     * @var float
     */
    private $assetRevaluationNetBookValue;

    /**
     * Price
     * @var float
     */
    private $assetPrice;

    /**
     * Year To Date
     * @var float
     */
    private $yearToDate;

    /**
     * Current Net Book Value
     * @var float
     */
    private $currentNetBookValue;

    /**
     * Comment
     * @var string
     */
    private $assetRevaluationComment;

    /**
     * Revaluation Profit
     * @var float
     */
    private $assetRevaluationProfit;

    /**
     * Depreciation Period
     * @var int
     */
    private $depreciationPeriod;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         */
        $this->setTableName('assetrevaluation');
        $this->setPrimaryKeyName('assetRevaluationId');
        $this->setMasterForeignKeyName('assetRevaluationId');
        $this->setFilterCharacter('assetrevaluationDescription');
        //$this->setFilterCharacter('assetrevaluationNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['assetRevaluationId'])) {
            $this->setAssetRevaluationId($this->strict($_POST ['assetRevaluationId'], 'integer'), 0, 'single');
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
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'text'));
        }
        if (isset($_POST ['transactionDate'])) {
            $this->setTransactionDate($this->strict($_POST ['transactionDate'], 'date'));
        }
        if (isset($_POST ['financePeriod'])) {
            $this->setFinancePeriod($this->strict($_POST ['financePeriod'], 'integer'));
        }
        if (isset($_POST ['financeYear'])) {
            $this->setFinanceYear($this->strict($_POST ['financeYear'], 'integer'));
        }
        if (isset($_POST ['assetRevaluationReference'])) {
            $this->setAssetRevaluationReference($this->strict($_POST ['assetRevaluationReference'], 'text'));
        }
        if (isset($_POST ['assetRevaluationPrice'])) {
            $this->setAssetRevaluationPrice($this->strict($_POST ['assetRevaluationPrice'], ''));
        }
        if (isset($_POST ['assetRevaluationValue'])) {
            $this->setAssetRevaluationValue($this->strict($_POST ['assetRevaluationValue'], 'float'));
        }
        if (isset($_POST ['assetRevaluationNetBookValue'])) {
            $this->setAssetRevaluationNetBookValue($this->strict($_POST ['assetRevaluationNetBookValue'], 'float'));
        }
        if (isset($_POST ['assetPrice'])) {
            $this->setAssetPrice($this->strict($_POST ['assetPrice'], 'float'));
        }
        if (isset($_POST ['yearToDate'])) {
            $this->setYearToDate($this->strict($_POST ['yearToDate'], 'float'));
        }
        if (isset($_POST ['currentNetBookValue'])) {
            $this->setCurrentNetBookValue($this->strict($_POST ['currentNetBookValue'], 'float'));
        }
        if (isset($_POST ['assetRevaluationComment'])) {
            $this->setAssetRevaluationComment($this->strict($_POST ['assetRevaluationComment'], 'text'));
        }
        if (isset($_POST ['assetRevaluationProfit'])) {
            $this->setAssetRevaluationProfit($this->strict($_POST ['assetRevaluationProfit'], 'float'));
        }
        if (isset($_POST ['depreciationPeriod'])) {
            $this->setDepreciationPeriod($this->strict($_POST ['depreciationPeriod'], 'integer'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['assetRevaluationId'])) {
            $this->setAssetRevaluationId($this->strict($_GET ['assetRevaluationId'], 'integer'), 0, 'single');
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
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'text'));
        }
        if (isset($_GET ['transactionDate'])) {
            $this->setTransactionDate($this->strict($_GET ['transactionDate'], 'date'));
        }
        if (isset($_GET ['financePeriod'])) {
            $this->setFinancePeriod($this->strict($_GET ['financePeriod'], 'integer'));
        }
        if (isset($_GET ['financeYear'])) {
            $this->setFinanceYear($this->strict($_GET ['financeYear'], 'integer'));
        }
        if (isset($_GET ['assetRevaluationReference'])) {
            $this->setAssetRevaluationReference($this->strict($_GET ['assetRevaluationReference'], 'text'));
        }
        if (isset($_GET ['assetRevaluationPrice'])) {
            $this->setAssetRevaluationPrice($this->strict($_GET ['assetRevaluationPrice'], ''));
        }
        if (isset($_GET ['assetRevaluationValue'])) {
            $this->setAssetRevaluationValue($this->strict($_GET ['assetRevaluationValue'], 'float'));
        }
        if (isset($_GET ['assetRevaluationNetBookValue'])) {
            $this->setAssetRevaluationNetBookValue($this->strict($_GET ['assetRevaluationNetBookValue'], 'float'));
        }
        if (isset($_GET ['assetPrice'])) {
            $this->setAssetPrice($this->strict($_GET ['assetPrice'], 'float'));
        }
        if (isset($_GET ['yearToDate'])) {
            $this->setYearToDate($this->strict($_GET ['yearToDate'], 'float'));
        }
        if (isset($_GET ['currentNetBookValue'])) {
            $this->setCurrentNetBookValue($this->strict($_GET ['currentNetBookValue'], 'float'));
        }
        if (isset($_GET ['assetRevaluationComment'])) {
            $this->setAssetRevaluationComment($this->strict($_GET ['assetRevaluationComment'], 'text'));
        }
        if (isset($_GET ['assetRevaluationProfit'])) {
            $this->setAssetRevaluationProfit($this->strict($_GET ['assetRevaluationProfit'], 'float'));
        }
        if (isset($_GET ['depreciationPeriod'])) {
            $this->setDepreciationPeriod($this->strict($_GET ['depreciationPeriod'], 'integer'));
        }
        if (isset($_GET ['assetRevaluationId'])) {
            $this->setTotal(count($_GET ['assetRevaluationId']));
            if (is_array($_GET ['assetRevaluationId'])) {
                $this->assetRevaluationId = array();
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
            if (isset($_GET ['assetRevaluationId'])) {
                $this->setAssetRevaluationId($this->strict($_GET ['assetRevaluationId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getAssetRevaluationId($i, 'array') . ",";
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
     * Return assetRevaluation Primary Key  Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     */
    public function getAssetRevaluationId($key, $type) {
        if ($type == 'single') {
            return $this->assetRevaluationId;
        } else {
            if ($type == 'array') {
                return $this->assetRevaluationId [$key];
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:getAssetRevaluationId ?"
                        )
                );
                exit();
            }
        }
    }

    /**
     * Set assetRevaluation Primary Key  Value
     * @param int|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     */
    public function setAssetRevaluationId($value, $key, $type) {
        if ($type == 'single') {
            $this->assetRevaluationId = $value;
        } else {
            if ($type == 'array') {
                $this->assetRevaluationId[$key] = $value;
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:setAssetRevaluationId?"
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
     * @return \Core\Financial\FixedAsset\AssetRevaluation\Model\AssetRevaluationModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Category
     * @return int $itemCategoryId
     */
    public function getItemCategoryId() {
        return $this->itemCategoryId;
    }

    /**
     * To Set Category
     * @param int $itemCategoryId
     * @return \Core\Financial\FixedAsset\AssetRevaluation\Model\AssetRevaluationModel
     */
    public function setItemCategoryId($itemCategoryId) {
        $this->itemCategoryId = $itemCategoryId;
        return $this;
    }

    /**
     * To Return Type
     * @return int $itemTypeId
     */
    public function getItemTypeId() {
        return $this->itemTypeId;
    }

    /**
     * To Set Type
     * @param int $itemTypeId
     * @return \Core\Financial\FixedAsset\AssetRevaluation\Model\AssetRevaluationModel
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
     * @return \Core\Financial\FixedAsset\AssetRevaluation\Model\AssetRevaluationModel
     */
    public function setAssetId($assetId) {
        $this->assetId = $assetId;
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
     * @return \Core\Financial\FixedAsset\AssetRevaluation\Model\AssetRevaluationModel
     */
    public function setDocumentNumber($documentNumber) {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    /**
     * To Return transactionDate
     * @return string $transactionDate
     */
    public function getTransactionDate() {
        return $this->transactionDate;
    }

    /**
     * To Set transaction Date
     * @param string $transactionDate
     * @return \Core\Financial\FixedAsset\AssetRevaluation\Model\AssetRevaluationModel
     */
    public function setTransactionDate($transactionDate) {
        $this->transactionDate = $transactionDate;
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
     * @return \Core\Financial\FixedAsset\AssetRevaluation\Model\AssetRevaluationModel
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
     * @return \Core\Financial\FixedAsset\AssetRevaluation\Model\AssetRevaluationModel
     */
    public function setFinanceYear($financeYear) {
        $this->financeYear = $financeYear;
        return $this;
    }

    /**
     * To Return Reference Number
     * @return string $assetRevaluationReference
     */
    public function getAssetRevaluationReference() {
        return $this->assetRevaluationReference;
    }

    /**
     * To Set Reference Number
     * @param string $assetRevaluationReference
     * @return \Core\Financial\FixedAsset\AssetRevaluation\Model\AssetRevaluationModel
     */
    public function setAssetRevaluationReference($assetRevaluationReference) {
        $this->assetRevaluationReference = $assetRevaluationReference;
        return $this;
    }

    /**
     * To Return Price
     * @return float $assetRevaluationPrice
     */
    public function getAssetRevaluationPrice() {
        return $this->assetRevaluationPrice;
    }

    /**
     * To Set Price
     * @param  float $assetRevaluationPrice
     * @return \Core\Financial\FixedAsset\AssetRevaluation\Model\AssetRevaluationModel
     */
    public function setAssetRevaluationPrice($assetRevaluationPrice) {
        $this->assetRevaluationPrice = $assetRevaluationPrice;
        return $this;
    }

    /**
     * To Return Value
     * @return float $assetRevaluationValue
     */
    public function getAssetRevaluationValue() {
        return $this->assetRevaluationValue;
    }

    /**
     * To Set Value
     * @param float $assetRevaluationValue
     * @return \Core\Financial\FixedAsset\AssetRevaluation\Model\AssetRevaluationModel
     */
    public function setAssetRevaluationValue($assetRevaluationValue) {
        $this->assetRevaluationValue = $assetRevaluationValue;
        return $this;
    }

    /**
     * To Return Net Book Value
     * @return float $assetRevaluationNetBookValue
     */
    public function getAssetRevaluationNetBookValue() {
        return $this->assetRevaluationNetBookValue;
    }

    /**
     * To Set Net Book Value
     * @param float $assetRevaluationNetBookValue
     * @return \Core\Financial\FixedAsset\AssetRevaluation\Model\AssetRevaluationModel
     */
    public function setAssetRevaluationNetBookValue($assetRevaluationNetBookValue) {
        $this->assetRevaluationNetBookValue = $assetRevaluationNetBookValue;
        return $this;
    }

    /**
     * To Return Price
     * @return float $assetPrice
     */
    public function getAssetPrice() {
        return $this->assetPrice;
    }

    /**
     * To Set Price
     * @param float $assetPrice
     * @return \Core\Financial\FixedAsset\AssetRevaluation\Model\AssetRevaluationModel
     */
    public function setAssetPrice($assetPrice) {
        $this->assetPrice = $assetPrice;
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
     * @return \Core\Financial\FixedAsset\AssetRevaluation\Model\AssetRevaluationModel
     */
    public function setYearToDate($yearToDate) {
        $this->yearToDate = $yearToDate;
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
     * @return \Core\Financial\FixedAsset\AssetRevaluation\Model\AssetRevaluationModel
     */
    public function setCurrentNetBookValue($currentNetBookValue) {
        $this->currentNetBookValue = $currentNetBookValue;
        return $this;
    }

    /**
     * To Return Comment
     * @return string $assetRevaluationComment
     */
    public function getAssetRevaluationComment() {
        return $this->assetRevaluationComment;
    }

    /**
     * To Set Comment
     * @param string $assetRevaluationComment
     * @return \Core\Financial\FixedAsset\AssetRevaluation\Model\AssetRevaluationModel
     */
    public function setAssetRevaluationComment($assetRevaluationComment) {
        $this->assetRevaluationComment = $assetRevaluationComment;
        return $this;
    }

    /**
     * To Return Revaluation Profit
     * @return float $assetRevaluationProfit
     */
    public function getAssetRevaluationProfit() {
        return $this->assetRevaluationProfit;
    }

    /**
     * To Set Revaluation Profit
     * @param float $assetRevaluationProfit
     * @return \Core\Financial\FixedAsset\AssetRevaluation\Model\AssetRevaluationModel
     */
    public function setAssetRevaluationProfit($assetRevaluationProfit) {
        $this->assetRevaluationProfit = $assetRevaluationProfit;
        return $this;
    }

    /**
     * To Return Depreciation Period
     * @return int $depreciationPeriod
     */
    public function getDepreciationPeriod() {
        return $this->depreciationPeriod;
    }

    /**
     * To Set Depreciation Period
     * @param int $depreciationPeriod
     * @return \Core\Financial\FixedAsset\AssetRevaluation\Model\AssetRevaluationModel
     */
    public function setDepreciationPeriod($depreciationPeriod) {
        $this->depreciationPeriod = $depreciationPeriod;
        return $this;
    }

}

?>