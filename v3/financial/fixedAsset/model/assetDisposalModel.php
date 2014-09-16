<?php

namespace Core\Financial\FixedAsset\AssetDisposal\Model;

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
 * Class AssetDisposal
 * This is assetDisposal model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\FixedAsset\AssetDisposal\Model;
 * @subpackage FixedAsset
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class AssetDisposalModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $assetDisposalId;

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
     * Item Type
     * @var int
     */
    private $itemTypeId;

    /**
     * Asset
     * @var int
     */
    private $assetId;

    /**
     * Reason
     * @var int
     */
    private $assetDisposalReasonId;

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
     * Journal Number
     * @var string
     */
    private $journalNumber;

    /**
     * Sales Value
     * @var double
     */
    private $assetDisposalSalesValue;

    /**
     * Date
     * @var string
     */
    private $assetDisposalDate;

    /**
     * Asset Price
     * @var double
     */
    private $assetPrice;

    /**
     * Year To Date
     * @var double
     */
    private $yearToDate;

    /**
     * Asset Net Book   Value
     * @var double
     */
    private $assetNetBookValue;

    /**
     * Gain Loss
     * @var double
     */
    private $assetDisposalGainAndLoss;

    /**
     * Description
     * @var string
     */
    private $assetDisposalDescription;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('assetDisposal');
        $this->setPrimaryKeyName('assetDisposalId');
        $this->setMasterForeignKeyName('assetDisposalId');
        $this->setFilterCharacter('assetDisposalDescription');
        //$this->setFilterCharacter('assetDisposalNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['assetDisposalId'])) {
            $this->setAssetDisposalId($this->strict($_POST ['assetDisposalId'], 'integer'), 0, 'single');
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
        if (isset($_POST ['assetDisposalReasonId'])) {
            $this->setAssetDisposalReasonId($this->strict($_POST ['assetDisposalReasonId'], 'integer'));
        }
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string'));
        }
        if (isset($_POST ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_POST ['referenceNumber'], 'string'));
        }
        if (isset($_POST ['journalNumber'])) {
            $this->setJournalNumber($this->strict($_POST ['journalNumber'], 'string'));
        }
        if (isset($_POST ['assetDisposalSalesValue'])) {
            $this->setAssetDisposalSalesValue($this->strict($_POST ['assetDisposalSalesValue'], 'double'));
        }
        if (isset($_POST ['assetDisposalDate'])) {
            $this->setAssetDisposalDate($this->strict($_POST ['assetDisposalDate'], 'date'));
        }
        if (isset($_POST ['assetPrice'])) {
            $this->setAssetPrice($this->strict($_POST ['assetPrice'], 'double'));
        }
        if (isset($_POST ['yearTodate'])) {
            $this->setYearToDate($this->strict($_POST ['yearTodate'], 'double'));
        }
        if (isset($_POST ['assetNetBookValue'])) {
            $this->setAssetNetBookValue($this->strict($_POST ['assetNetBookValue'], 'double'));
        }
        if (isset($_POST ['assetDisposalGainAndLoss'])) {
            $this->setAssetDisposalGainAndLoss($this->strict($_POST ['assetDisposalGainAndLoss'], 'double'));
        }
        if (isset($_POST ['assetDisposalDescription'])) {
            $this->setAssetDisposalDescription($this->strict($_POST ['assetDisposalDescription'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['assetDisposalId'])) {
            $this->setAssetDisposalId($this->strict($_GET ['assetDisposalId'], 'integer'), 0, 'single');
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
        if (isset($_GET ['assetDisposalReasonId'])) {
            $this->setAssetDisposalReasonId($this->strict($_GET ['assetDisposalReasonId'], 'integer'));
        }
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string'));
        }
        if (isset($_GET ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_GET ['referenceNumber'], 'string'));
        }
        if (isset($_GET ['journalNumber'])) {
            $this->setJournalNumber($this->strict($_GET ['journalNumber'], 'string'));
        }
        if (isset($_GET ['assetDisposalSalesValue'])) {
            $this->setAssetDisposalSalesValue($this->strict($_GET ['assetDisposalSalesValue'], 'double'));
        }
        if (isset($_GET ['assetDisposalDate'])) {
            $this->setAssetDisposalDate($this->strict($_GET ['assetDisposalDate'], 'date'));
        }
        if (isset($_GET ['assetPrice'])) {
            $this->setAssetPrice($this->strict($_GET ['assetPrice'], 'double'));
        }
        if (isset($_GET ['yearTodate'])) {
            $this->setYearToDate($this->strict($_GET ['yearTodate'], 'double'));
        }
        if (isset($_GET ['assetNetBookValue'])) {
            $this->setAssetNetBookValue($this->strict($_GET ['assetNetBookValue'], 'double'));
        }
        if (isset($_GET ['assetDisposalGainAndLoss'])) {
            $this->setAssetDisposalGainAndLoss($this->strict($_GET ['assetDisposalGainAndLoss'], 'double'));
        }
        if (isset($_GET ['assetDisposalDescription'])) {
            $this->setAssetDisposalDescription($this->strict($_GET ['assetDisposalDescription'], 'string'));
        }
        if (isset($_GET ['assetDisposalId'])) {
            $this->setTotal(count($_GET ['assetDisposalId']));
            if (is_array($_GET ['assetDisposalId'])) {
                $this->assetDisposalId = array();
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
            if (isset($_GET ['assetDisposalId'])) {
                $this->setAssetDisposalId($this->strict($_GET ['assetDisposalId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getAssetDisposalId($i, 'array') . ",";
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
    public function getAssetDisposalId($key, $type) {
        if ($type == 'single') {
            return $this->assetDisposalId;
        } else {
            if ($type == 'array') {
                return $this->assetDisposalId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getAssetDisposalId ?")
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
     * @return \Core\Financial\FixedAsset\AssetDisposal\Model\AssetDisposalModel
     */
    public function setAssetDisposalId($value, $key, $type) {
        if ($type == 'single') {
            $this->assetDisposalId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->assetDisposalId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setAssetDisposalId?")
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
     * @return \Core\Financial\FixedAsset\AssetDisposal\Model\AssetDisposalModel
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
     * @return \Core\Financial\FixedAsset\AssetDisposal\Model\AssetDisposalModel
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
     * @param int $itemTypeId Item Type
     * @return \Core\Financial\FixedAsset\AssetDisposal\Model\AssetDisposalModel
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
     * @param int $assetId Asset
     * @return \Core\Financial\FixedAsset\AssetDisposal\Model\AssetDisposalModel
     */
    public function setAssetId($assetId) {
        $this->assetId = $assetId;
        return $this;
    }

    /**
     * To Return Reason
     * @return int $assetDisposalReasonId
     */
    public function getAssetDisposalReasonId() {
        return $this->assetDisposalReasonId;
    }

    /**
     * To Set Reason
     * @param int $assetDisposalReasonId Reason
     * @return \Core\Financial\FixedAsset\AssetDisposal\Model\AssetDisposalModel
     */
    public function setAssetDisposalReasonId($assetDisposalReasonId) {
        $this->assetDisposalReasonId = $assetDisposalReasonId;
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
     * @return \Core\Financial\FixedAsset\AssetDisposal\Model\AssetDisposalModel
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
     * @return \Core\Financial\FixedAsset\AssetDisposal\Model\AssetDisposalModel
     */
    public function setReferenceNumber($referenceNumber) {
        $this->referenceNumber = $referenceNumber;
        return $this;
    }

    /**
     * To Return Journal Number
     * @return string $journalNumber
     */
    public function getJournalNumber() {
        return $this->journalNumber;
    }

    /**
     * To Set Journal Number
     * @param string $journalNumber Journal Number
     * @return \Core\Financial\FixedAsset\AssetDisposal\Model\AssetDisposalModel
     */
    public function setJournalNumber($journalNumber) {
        $this->journalNumber = $journalNumber;
        return $this;
    }

    /**
     * To Return Sales Value
     * @return double $assetDisposalSalesValue
     */
    public function getAssetDisposalSalesValue() {
        return $this->assetDisposalSalesValue;
    }

    /**
     * To Set Sales Value
     * @param double $assetDisposalSalesValue Sales Value
     * @return \Core\Financial\FixedAsset\AssetDisposal\Model\AssetDisposalModel
     */
    public function setAssetDisposalSalesValue($assetDisposalSalesValue) {
        $this->assetDisposalSalesValue = $assetDisposalSalesValue;
        return $this;
    }

    /**
     * To Return Date
     * @return string $assetDisposalDate
     */
    public function getAssetDisposalDate() {
        return $this->assetDisposalDate;
    }

    /**
     * To Set Date
     * @param string $assetDisposalDate Date
     * @return \Core\Financial\FixedAsset\AssetDisposal\Model\AssetDisposalModel
     */
    public function setAssetDisposalDate($assetDisposalDate) {
        $this->assetDisposalDate = $assetDisposalDate;
        return $this;
    }

    /**
     * To Return Asset Price
     * @return double $assetPrice
     */
    public function getAssetPrice() {
        return $this->assetPrice;
    }

    /**
     * To Set Asset Price
     * @param double $assetPrice Asset Price
     * @return \Core\Financial\FixedAsset\AssetDisposal\Model\AssetDisposalModel
     */
    public function setAssetPrice($assetPrice) {
        $this->assetPrice = $assetPrice;
        return $this;
    }

    /**
     * To Return Year To Date
     * @return double $yearToDate
     */
    public function getYearToDate() {
        return $this->yearToDate;
    }

    /**
     * To Set Year To Date
     * @param double $yearToDate Year To Date
     * @return \Core\Financial\FixedAsset\AssetDisposal\Model\AssetDisposalModel
     */
    public function setYearToDate($yearToDate) {
        $this->yearToDate = $yearToDate;
        return $this;
    }

    /**
     * To Return Asset Net Book Value
     * @return double $assetNetBookValue
     */
    public function getAssetNetBookValue() {
        return $this->assetNetBookValue;
    }

    /**
     * To Set Asset Net Book Value
     * @param double $assetNetBookValue Asset Net Book Value
     * @return \Core\Financial\FixedAsset\AssetDisposal\Model\AssetDisposalModel
     */
    public function setAssetNetBookValue($assetNetBookValue) {
        $this->assetNetBookValue = $assetNetBookValue;
        return $this;
    }

    /**
     * To Return Gain And Loss
     * @return double $assetDisposalGainAndLoss
     */
    public function getAssetDisposalGainAndLoss() {
        return $this->assetDisposalGainAndLoss;
    }

    /**
     * To Set Gain And Loss
     * @param double $assetDisposalGainAndLoss Gain Loss
     * @return \Core\Financial\FixedAsset\AssetDisposal\Model\AssetDisposalModel
     */
    public function setAssetDisposalGainAndLoss($assetDisposalGainAndLoss) {
        $this->assetDisposalGainAndLoss = $assetDisposalGainAndLoss;
        return $this;
    }

    /**
     * To Return Description
     * @return string $assetDisposalDescription
     */
    public function getAssetDisposalDescription() {
        return $this->assetDisposalDescription;
    }

    /**
     * To Set Description
     * @param string $assetDisposalDescription Description
     * @return \Core\Financial\FixedAsset\AssetDisposal\Model\AssetDisposalModel
     */
    public function setAssetDisposalDescription($assetDisposalDescription) {
        $this->assetDisposalDescription = $assetDisposalDescription;
        return $this;
    }

}

?>