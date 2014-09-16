<?php

namespace Core\Financial\FixedAsset\AssetMaintenance\Model;

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
 * Class AssetMaintenance
 * This is Asset Maintenance model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\FixedAsset\AssetMaintenance\Model;
 * @subpackage FixedAsset
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class AssetMaintenanceModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $assetMaintenanceId;

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
     * Asset
     * @var int
     */
    private $assetId;

    /**
     * Employee
     * @var int
     */
    private $employeeId;

    /**
     * Code
     * @var int
     */
    private $assetMaintenanceCodeId;

    /**
     * Cost
     * @var double
     */
    private $assetMaintenanceCost;

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
     * Date Sent
     * @var string
     */
    private $assetMaintenanceDateSent;

    /**
     * Date Receive
     * @var string
     */
    private $assetMaintenanceDateReceive;

    /**
     * Description
     * @var string
     */
    private $assetMaintenanceDescription;

    /**
     * Is Chargeable
     * @var bool
     */
    private $isChargeable;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('assetMaintenance');
        $this->setPrimaryKeyName('assetMaintenanceId');
        $this->setMasterForeignKeyName('assetMaintenanceId');
        $this->setFilterCharacter('assetMaintenanceDescription');
        //$this->setFilterCharacter('assetMaintenanceNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['assetMaintenanceId'])) {
            $this->setAssetMaintenanceId($this->strict($_POST ['assetMaintenanceId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_POST ['businessPartnerId'], 'integer'));
        }
        if (isset($_POST ['assetId'])) {
            $this->setAssetId($this->strict($_POST ['assetId'], 'integer'));
        }
        if (isset($_POST ['employeeId'])) {
            $this->setEmployeeId($this->strict($_POST ['employeeId'], 'integer'));
        }
        if (isset($_POST ['assetMaintenanceCodeId'])) {
            $this->setAssetMaintenanceCodeId($this->strict($_POST ['assetMaintenanceCodeId'], 'integer'));
        }
        if (isset($_POST ['assetMaintenanceCost'])) {
            $this->setAssetMaintenanceCost($this->strict($_POST ['assetMaintenanceCost'], 'double'));
        }
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string'));
        }
        if (isset($_POST ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_POST ['referenceNumber'], 'string'));
        }
        if (isset($_POST ['assetMaintenanceDateSent'])) {
            $this->setAssetMaintenanceDateSent($this->strict($_POST ['assetMaintenanceDateSent'], 'date'));
        }
        if (isset($_POST ['assetMaintenanceDateReceive'])) {
            $this->setAssetMaintenanceDateReceive($this->strict($_POST ['assetMaintenanceDateReceive'], 'date'));
        }
        if (isset($_POST ['assetMaintenanceDescription'])) {
            $this->setAssetMaintenanceDescription($this->strict($_POST ['assetMaintenanceDescription'], 'string'));
        }
        if (isset($_POST ['isChargeable'])) {
            $this->setIsChargeable($this->strict($_POST ['isChargeable'], 'bool'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['assetMaintenanceId'])) {
            $this->setAssetMaintenanceId($this->strict($_GET ['assetMaintenanceId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_GET ['businessPartnerId'], 'integer'));
        }
        if (isset($_GET ['assetId'])) {
            $this->setAssetId($this->strict($_GET ['assetId'], 'integer'));
        }
        if (isset($_GET ['employeeId'])) {
            $this->setEmployeeId($this->strict($_GET ['employeeId'], 'integer'));
        }
        if (isset($_GET ['assetMaintenanceCodeId'])) {
            $this->setAssetMaintenanceCodeId($this->strict($_GET ['assetMaintenanceCodeId'], 'integer'));
        }
        if (isset($_GET ['assetMaintenanceCost'])) {
            $this->setAssetMaintenanceCost($this->strict($_GET ['assetMaintenanceCost'], 'double'));
        }
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string'));
        }
        if (isset($_GET ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_GET ['referenceNumber'], 'string'));
        }
        if (isset($_GET ['assetMaintenanceDateSent'])) {
            $this->setAssetMaintenanceDateSent($this->strict($_GET ['assetMaintenanceDateSent'], 'date'));
        }
        if (isset($_GET ['assetMaintenanceDateReceive'])) {
            $this->setAssetMaintenanceDateReceive($this->strict($_GET ['assetMaintenanceDateReceive'], 'date'));
        }
        if (isset($_GET ['assetMaintenanceDescription'])) {
            $this->setAssetMaintenanceDescription($this->strict($_GET ['assetMaintenanceDescription'], 'string'));
        }
        if (isset($_GET ['isChargeable'])) {
            $this->setIsChargeable($this->strict($_GET ['isChargeable'], 'bool'));
        }
        if (isset($_GET ['assetMaintenanceId'])) {
            $this->setTotal(count($_GET ['assetMaintenanceId']));
            if (is_array($_GET ['assetMaintenanceId'])) {
                $this->assetMaintenanceId = array();
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
            if (isset($_GET ['assetMaintenanceId'])) {
                $this->setAssetMaintenanceId($this->strict($_GET ['assetMaintenanceId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getAssetMaintenanceId($i, 'array') . ",";
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
    public function getAssetMaintenanceId($key, $type) {
        if ($type == 'single') {
            return $this->assetMaintenanceId;
        } else {
            if ($type == 'array') {
                return $this->assetMaintenanceId [$key];
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:getAssetMaintenanceId ?"
                        )
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
     * @return \Core\Financial\FixedAsset\AssetMaintenance\Model\AssetMaintenanceModel
     */
    public function setAssetMaintenanceId($value, $key, $type) {
        if ($type == 'single') {
            $this->assetMaintenanceId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->assetMaintenanceId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:setAssetMaintenanceId?"
                        )
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
     * @return \Core\Financial\FixedAsset\AssetMaintenance\Model\AssetMaintenanceModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Business Partner
     * @return int $businessPartnerId
     */
    public function getBusinessPartnerId() {
        return $this->businessPartnerId;
    }

    /**
     * To Set Business Partner
     * @param int $businessPartnerId Business Partner
     * @return \Core\Financial\FixedAsset\AssetMaintenance\Model\AssetMaintenanceModel
     */
    public function setBusinessPartnerId($businessPartnerId) {
        $this->businessPartnerId = $businessPartnerId;
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
     * @return \Core\Financial\FixedAsset\AssetMaintenance\Model\AssetMaintenanceModel
     */
    public function setAssetId($assetId) {
        $this->assetId = $assetId;
        return $this;
    }

    /**
     * To Return Employee
     * @return int $employeeId
     */
    public function getEmployeeId() {
        return $this->employeeId;
    }

    /**
     * To Set Employee
     * @param int $employeeId Employee
     * @return \Core\Financial\FixedAsset\AssetMaintenance\Model\AssetMaintenanceModel
     */
    public function setEmployeeId($employeeId) {
        $this->employeeId = $employeeId;
        return $this;
    }

    /**
     * To Return Code
     * @return int $assetMaintenanceCodeId
     */
    public function getAssetMaintenanceCodeId() {
        return $this->assetMaintenanceCodeId;
    }

    /**
     * To Set Code
     * @param int $assetMaintenanceCodeId Code
     * @return \Core\Financial\FixedAsset\AssetMaintenance\Model\AssetMaintenanceModel
     */
    public function setAssetMaintenanceCodeId($assetMaintenanceCodeId) {
        $this->assetMaintenanceCodeId = $assetMaintenanceCodeId;
        return $this;
    }

    /**
     * To Return Cost
     * @return double $assetMaintenanceCost
     */
    public function getAssetMaintenanceCost() {
        return $this->assetMaintenanceCost;
    }

    /**
     * To Set Cost
     * @param double $assetMaintenanceCost Cost
     * @return \Core\Financial\FixedAsset\AssetMaintenance\Model\AssetMaintenanceModel
     */
    public function setAssetMaintenanceCost($assetMaintenanceCost) {
        $this->assetMaintenanceCost = $assetMaintenanceCost;
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
     * @return \Core\Financial\FixedAsset\AssetMaintenance\Model\AssetMaintenanceModel
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
     * @return \Core\Financial\FixedAsset\AssetMaintenance\Model\AssetMaintenanceModel
     */
    public function setReferenceNumber($referenceNumber) {
        $this->referenceNumber = $referenceNumber;
        return $this;
    }

    /**
     * To Return Date Sent
     * @return string $assetMaintenanceDateSent
     */
    public function getAssetMaintenanceDateSent() {
        return $this->assetMaintenanceDateSent;
    }

    /**
     * To Set Date Sent
     * @param string $assetMaintenanceDateSent Date Sent
     * @return \Core\Financial\FixedAsset\AssetMaintenance\Model\AssetMaintenanceModel
     */
    public function setAssetMaintenanceDateSent($assetMaintenanceDateSent) {
        $this->assetMaintenanceDateSent = $assetMaintenanceDateSent;
        return $this;
    }

    /**
     * To Return Date Receive
     * @return string $assetMaintenanceDateReceive
     */
    public function getAssetMaintenanceDateReceive() {
        return $this->assetMaintenanceDateReceive;
    }

    /**
     * To Set Date Receive
     * @param string $assetMaintenanceDateReceive Date Receive
     * @return \Core\Financial\FixedAsset\AssetMaintenance\Model\AssetMaintenanceModel
     */
    public function setAssetMaintenanceDateReceive($assetMaintenanceDateReceive) {
        $this->assetMaintenanceDateReceive = $assetMaintenanceDateReceive;
        return $this;
    }

    /**
     * To Return Description
     * @return string $assetMaintenanceDescription
     */
    public function getAssetMaintenanceDescription() {
        return $this->assetMaintenanceDescription;
    }

    /**
     * To Set Description
     * @param string $assetMaintenanceDescription Description
     * @return \Core\Financial\FixedAsset\AssetMaintenance\Model\AssetMaintenanceModel
     */
    public function setAssetMaintenanceDescription($assetMaintenanceDescription) {
        $this->assetMaintenanceDescription = $assetMaintenanceDescription;
        return $this;
    }

    /**
     * To Return  isChargeable
     * @return bool $isChargeable
     */
    public function getIsChargeable() {
        return $this->isChargeable;
    }

    /**
     * To Set is Chargeable
     * @param bool $isChargeable Is Chargeable
     * @return \Core\Financial\FixedAsset\AssetMaintenance\Model\AssetMaintenanceModel
     */
    public function setIsChargeable($isChargeable) {
        $this->isChargeable = $isChargeable;
        return $this;
    }

}

?>