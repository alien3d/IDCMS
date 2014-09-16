<?php

namespace Core\Financial\FixedAsset\AssetSpecification\Model;

use Core\Validation\ValidationClass;

$x = addslashes(realpath(__FILE__));
// auto detect if \ consider come from windows else / from linux
$pos = strpos($x, "\\");
if ($pos !== false) {
    $d = explode("\\", $x);
} else {
    $d = explode("/", $x);
}
$newPath = null;
for ($i = 0; $i < count($d); $i ++) {
    // if find the library or package then stop
    if ($d[$i] == 'library' || $d[$i] == 'package') {
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
 * Class AssetSpecification
 * This is assetSpecification model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\Financial\FixedAsset\AssetSpecification\Model;
 * @subpackage FixedAsset
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class AssetSpecificationModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $assetSpecificationId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Asset Reason
     * @var int
     */
    private $assetDepreciationReasonId;

    /**
     * Cost Accounts
     * @var int
     */
    private $assetSpecificationCostAccounts;

    /**
     * Accumulative Accounts
     * @var int
     */
    private $assetSpecificationAccumulativeDepreciationAccounts;

    /**
     * Write Accounts
     * @var int
     */
    private $assetSpecificationWriteOffAccounts;

    /**
     * Depreciation Accounts
     * @var int
     */
    private $assetSpecificationDepreciationAccounts;

    /**
     * Revaluation Accounts
     * @var int
     */
    private $assetSpecificationRevaluationAccounts;

    /**
     * Gain And Loss   Accounts
     * @var int
     */
    private $assetSpecificationGainAndLossAccounts;

    /**
     * Clearing Accounts
     * @var int
     */
    private $assetSpecificationClearingAccounts;

    /**
     * Nominal Value
     * @var double
     */
    private $assetSpecificationNominalValue;

    /**
     * Minimum Order
     * @var int
     */
    private $assetSpecificationMinimumReOrder;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('assetSpecification');
        $this->setPrimaryKeyName('assetSpecificationId');
        $this->setMasterForeignKeyName('assetSpecificationId');
        $this->setFilterCharacter('assetSpecificationDescription');
        //$this->setFilterCharacter('assetSpecificationNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['assetSpecificationId'])) {
            $this->setAssetSpecificationId($this->strict($_POST ['assetSpecificationId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['assetDepreciationReasonId'])) {
            $this->setAssetDepreciationReasonId($this->strict($_POST ['assetDepreciationReasonId'], 'int'));
        }
        if (isset($_POST ['assetSpecificationCostAccounts'])) {
            $this->setAssetSpecificationCostAccounts($this->strict($_POST ['assetSpecificationCostAccounts'], 'int'));
        }
        if (isset($_POST ['assetSpecificationAccumulativeDepreciationAccounts'])) {
            $this->setAssetSpecificationAccumulativeDepreciationAccounts($this->strict($_POST ['assetSpecificationAccumulativeDepreciationAccounts'], 'int'));
        }
        if (isset($_POST ['assetSpecificationWriteOffAccounts'])) {
            $this->setAssetSpecificationWriteOffAccounts($this->strict($_POST ['assetSpecificationWriteOffAccounts'], 'int'));
        }
        if (isset($_POST ['assetSpecificationDepreciationAccounts'])) {
            $this->setAssetSpecificationDepreciationAccounts($this->strict($_POST ['assetSpecificationDepreciationAccounts'], 'int'));
        }
        if (isset($_POST ['assetSpecificationRevaluationAccounts'])) {
            $this->setAssetSpecificationRevaluationAccounts($this->strict($_POST ['assetSpecificationRevaluationAccounts'], 'int'));
        }
        if (isset($_POST ['assetSpecificationGainAndLossAccounts'])) {
            $this->setAssetSpecificationGainAndLossAccounts($this->strict($_POST ['assetSpecificationGainAndLossAccounts'], 'int'));
        }
        if (isset($_POST ['assetSpecificationClearingAccounts'])) {
            $this->setAssetSpecificationClearingAccounts($this->strict($_POST ['assetSpecificationClearingAccounts'], 'int'));
        }
        if (isset($_POST ['assetSpecificationNominalValue'])) {
            $this->setAssetSpecificationNominalValue($this->strict($_POST ['assetSpecificationNominalValue'], 'double'));
        }
        if (isset($_POST ['assetSpecificationMinimumReOrder'])) {
            $this->setAssetSpecificationMinimumReOrder($this->strict($_POST ['assetSpecificationMinimumReOrder'], 'int'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['assetSpecificationId'])) {
            $this->setAssetSpecificationId($this->strict($_GET ['assetSpecificationId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['assetDepreciationReasonId'])) {
            $this->setAssetDepreciationReasonId($this->strict($_GET ['assetDepreciationReasonId'], 'int'));
        }
        if (isset($_GET ['assetSpecificationCostAccounts'])) {
            $this->setAssetSpecificationCostAccounts($this->strict($_GET ['assetSpecificationCostAccounts'], 'int'));
        }
        if (isset($_GET ['assetSpecificationAccumulativeDepreciationAccounts'])) {
            $this->setAssetSpecificationAccumulativeDepreciationAccounts($this->strict($_GET ['assetSpecificationAccumulativeDepreciationAccounts'], 'int'));
        }
        if (isset($_GET ['assetSpecificationWriteOffAccounts'])) {
            $this->setAssetSpecificationWriteOffAccounts($this->strict($_GET ['assetSpecificationWriteOffAccounts'], 'int'));
        }
        if (isset($_GET ['assetSpecificationDepreciationAccounts'])) {
            $this->setAssetSpecificationDepreciationAccounts($this->strict($_GET ['assetSpecificationDepreciationAccounts'], 'int'));
        }
        if (isset($_GET ['assetSpecificationRevaluationAccounts'])) {
            $this->setAssetSpecificationRevaluationAccounts($this->strict($_GET ['assetSpecificationRevaluationAccounts'], 'int'));
        }
        if (isset($_GET ['assetSpecificationGainAndLossAccounts'])) {
            $this->setAssetSpecificationGainAndLossAccounts($this->strict($_GET ['assetSpecificationGainAndLossAccounts'], 'int'));
        }
        if (isset($_GET ['assetSpecificationClearingAccounts'])) {
            $this->setAssetSpecificationClearingAccounts($this->strict($_GET ['assetSpecificationClearingAccounts'], 'int'));
        }
        if (isset($_GET ['assetSpecificationNominalValue'])) {
            $this->setAssetSpecificationNominalValue($this->strict($_GET ['assetSpecificationNominalValue'], 'double'));
        }
        if (isset($_GET ['assetSpecificationMinimumReOrder'])) {
            $this->setAssetSpecificationMinimumReOrder($this->strict($_GET ['assetSpecificationMinimumReOrder'], 'int'));
        }
        if (isset($_GET ['assetSpecificationId'])) {
            $this->setTotal(count($_GET ['assetSpecificationId']));
            if (is_array($_GET ['assetSpecificationId'])) {
                $this->assetSpecificationId = array();
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
            if (isset($_GET ['assetSpecificationId'])) {
                $this->setAssetSpecificationId($this->strict($_GET ['assetSpecificationId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getAssetSpecificationId($i, 'array') . ",";
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
            $this->setExecuteTime("to_date('" . date("Y-m-d H:i:s") . "','YYYY-MM-DD HH24:MI:SS')");
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
     * @return \Core\Financial\FixedAsset\AssetSpecification\Model\AssetSpecificationModel
     */
    public function setAssetSpecificationId($value, $key, $type) {
        if ($type == 'single') {
            $this->assetSpecificationId = $value;
            return $this;
        } else if ($type == 'array') {
            $this->assetSpecificationId[$key] = $value;
            return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:setassetSpecificationId?"));
            exit();
        }
    }

    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getAssetSpecificationId($key, $type) {
        if ($type == 'single') {
            return $this->assetSpecificationId;
        } else if ($type == 'array') {
            return $this->assetSpecificationId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:getassetSpecificationId ?"));
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
     * @return \Core\Financial\FixedAsset\AssetSpecification\Model\AssetSpecificationModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Asset Reason
     * @return int $assetDepreciationReasonId
     */
    public function getAssetDepreciationReasonId() {
        return $this->assetDepreciationReasonId;
    }

    /**
     * To Set Asset Reason
     * @param int $assetDepreciationReasonId Asset Reason
     * @return \Core\Financial\FixedAsset\AssetSpecification\Model\AssetSpecificationModel
     */
    public function setAssetDepreciationReasonId($assetDepreciationReasonId) {
        $this->assetDepreciationReasonId = $assetDepreciationReasonId;
        return $this;
    }

    /**
     * To Return Cost Accounts
     * @return int $assetSpecificationCostAccounts
     */
    public function getAssetSpecificationCostAccounts() {
        return $this->assetSpecificationCostAccounts;
    }

    /**
     * To Set Cost Accounts
     * @param int $assetSpecificationCostAccounts Cost Accounts
     * @return \Core\Financial\FixedAsset\AssetSpecification\Model\AssetSpecificationModel
     */
    public function setAssetSpecificationCostAccounts($assetSpecificationCostAccounts) {
        $this->assetSpecificationCostAccounts = $assetSpecificationCostAccounts;
        return $this;
    }

    /**
     * To Return Accumulative Accounts
     * @return int $assetSpecificationAccumulativeDepreciationAccounts
     */
    public function getAssetSpecificationAccumulativeDepreciationAccounts() {
        return $this->assetSpecificationAccumulativeDepreciationAccounts;
    }

    /**
     * To Set Accumulative Accounts
     * @param int $assetSpecificationAccumulativeDepreciationAccounts Accumulative Accounts
     * @return \Core\Financial\FixedAsset\AssetSpecification\Model\AssetSpecificationModel
     */
    public function setAssetSpecificationAccumulativeDepreciationAccounts($assetSpecificationAccumulativeDepreciationAccounts) {
        $this->assetSpecificationAccumulativeDepreciationAccounts = $assetSpecificationAccumulativeDepreciationAccounts;
        return $this;
    }

    /**
     * To Return Write Accounts
     * @return int $assetSpecificationWriteOffAccounts
     */
    public function getAssetSpecificationWriteOffAccounts() {
        return $this->assetSpecificationWriteOffAccounts;
    }

    /**
     * To Set Write Accounts
     * @param int $assetSpecificationWriteOffAccounts Write Accounts
     * @return \Core\Financial\FixedAsset\AssetSpecification\Model\AssetSpecificationModel
     */
    public function setAssetSpecificationWriteOffAccounts($assetSpecificationWriteOffAccounts) {
        $this->assetSpecificationWriteOffAccounts = $assetSpecificationWriteOffAccounts;
        return $this;
    }

    /**
     * To Return Depreciation Accounts
     * @return int $assetSpecificationDepreciationAccounts
     */
    public function getAssetSpecificationDepreciationAccounts() {
        return $this->assetSpecificationDepreciationAccounts;
    }

    /**
     * To Set Depreciation Accounts
     * @param int $assetSpecificationDepreciationAccounts Depreciation Accounts
     * @return \Core\Financial\FixedAsset\AssetSpecification\Model\AssetSpecificationModel
     */
    public function setAssetSpecificationDepreciationAccounts($assetSpecificationDepreciationAccounts) {
        $this->assetSpecificationDepreciationAccounts = $assetSpecificationDepreciationAccounts;
        return $this;
    }

    /**
     * To Return Revaluation Accounts
     * @return int $assetSpecificationRevaluationAccounts
     */
    public function getAssetSpecificationRevaluationAccounts() {
        return $this->assetSpecificationRevaluationAccounts;
    }

    /**
     * To Set Revaluation Accounts
     * @param int $assetSpecificationRevaluationAccounts Revaluation Accounts
     * @return \Core\Financial\FixedAsset\AssetSpecification\Model\AssetSpecificationModel
     */
    public function setAssetSpecificationRevaluationAccounts($assetSpecificationRevaluationAccounts) {
        $this->assetSpecificationRevaluationAccounts = $assetSpecificationRevaluationAccounts;
        return $this;
    }

    /**
     * To Return Gain And Loss   Accounts
     * @return int $assetSpecificationGainAndLossAccounts
     */
    public function getAssetSpecificationGainAndLossAccounts() {
        return $this->assetSpecificationGainAndLossAccounts;
    }

    /**
     * To Set Gain And Loss   Accounts
     * @param int $assetSpecificationGainAndLossAccounts Gain And Loss   Accounts
     * @return \Core\Financial\FixedAsset\AssetSpecification\Model\AssetSpecificationModel
     */
    public function setAssetSpecificationGainAndLossAccounts($assetSpecificationGainAndLossAccounts) {
        $this->assetSpecificationGainAndLossAccounts = $assetSpecificationGainAndLossAccounts;
        return $this;
    }

    /**
     * To Return Clearing Accounts
     * @return int $assetSpecificationClearingAccounts
     */
    public function getAssetSpecificationClearingAccounts() {
        return $this->assetSpecificationClearingAccounts;
    }

    /**
     * To Set Clearing Accounts
     * @param int $assetSpecificationClearingAccounts Clearing Accounts
     * @return \Core\Financial\FixedAsset\AssetSpecification\Model\AssetSpecificationModel
     */
    public function setAssetSpecificationClearingAccounts($assetSpecificationClearingAccounts) {
        $this->assetSpecificationClearingAccounts = $assetSpecificationClearingAccounts;
        return $this;
    }

    /**
     * To Return Nominal Value
     * @return double $assetSpecificationNominalValue
     */
    public function getAssetSpecificationNominalValue() {
        return $this->assetSpecificationNominalValue;
    }

    /**
     * To Set Nominal Value
     * @param double $assetSpecificationNominalValue Nominal Value
     * @return \Core\Financial\FixedAsset\AssetSpecification\Model\AssetSpecificationModel
     */
    public function setAssetSpecificationNominalValue($assetSpecificationNominalValue) {
        $this->assetSpecificationNominalValue = $assetSpecificationNominalValue;
        return $this;
    }

    /**
     * To Return Minimum Order
     * @return int $assetSpecificationMinimumReOrder
     */
    public function getAssetSpecificationMinimumReOrder() {
        return $this->assetSpecificationMinimumReOrder;
    }

    /**
     * To Set Minimum Order
     * @param int $assetSpecificationMinimumReOrder Minimum Order
     * @return \Core\Financial\FixedAsset\AssetSpecification\Model\AssetSpecificationModel
     */
    public function setAssetSpecificationMinimumReOrder($assetSpecificationMinimumReOrder) {
        $this->assetSpecificationMinimumReOrder = $assetSpecificationMinimumReOrder;
        return $this;
    }

}

?>