<?php

namespace Core\Financial\FixedAsset\AssetWorkOrder\Model;

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
 * Class AssetWorkOrder
 * This is Assset Work Order model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\FixedAsset\AssetWorkOrder\Model;
 * @subpackage FixedAsset
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class AssetWorkOrderModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $assetWorkOrderId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Asset
     * @var int
     */
    private $assetId;

    /**
     * Recurring Type
     * @var int
     */
    private $assetWorkOrderRecurringTypeId;

    /**
     * Employee
     * @var int
     */
    private $employeeId;

    /**
     * Document Number
     * @var string
     */
    private $documentNumber;

    /**
     * Description
     * @var string
     */
    private $assetWorkOrderDescription;

    /**
     * Start Hour
     * @var string
     */
    private $assetWorkOrderStartHour;

    /**
     * End Hour
     * @var string
     */
    private $assetWorkOrderEndHour;

    /**
     * Start Day
     * @var string
     */
    private $assetWorkOrderStartDay;

    /**
     * End Day
     * @var string
     */
    private $assetWorkOrderEndDay;

    /**
     * Date
     * @var string
     */
    private $assetWorkOrderDate;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('assetWorkOrder');
        $this->setPrimaryKeyName('assetWorkOrderId');
        $this->setMasterForeignKeyName('assetWorkOrderId');
        $this->setFilterCharacter('assetWorkOrderDescription');
        //$this->setFilterCharacter('assetWorkOrderNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['assetWorkOrderId'])) {
            $this->setAssetWorkOrderId($this->strict($_POST ['assetWorkOrderId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['assetId'])) {
            $this->setAssetId($this->strict($_POST ['assetId'], 'integer'));
        }
        if (isset($_POST ['assetWorkOrderRecurringTypeId'])) {
            $this->setAssetWorkOrderRecurringTypeId($this->strict($_POST ['assetWorkOrderRecurringTypeId'], 'integer'));
        }
        if (isset($_POST ['employeeId'])) {
            $this->setEmployeeId($this->strict($_POST ['employeeId'], 'integer'));
        }
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string'));
        }
        if (isset($_POST ['assetWorkOrderDescription'])) {
            $this->setAssetWorkOrderDescription($this->strict($_POST ['assetWorkOrderDescription'], 'string'));
        }
        if (isset($_POST ['assetWorkOrderStartHour'])) {
            $this->setAssetWorkOrderStartHour($this->strict($_POST ['assetWorkOrderStartHour'], 'time'));
        }
        if (isset($_POST ['assetWorkOrderEndHour'])) {
            $this->setAssetWorkOrderEndHour($this->strict($_POST ['assetWorkOrderEndHour'], 'time'));
        }
        if (isset($_POST ['assetWorkOrderStartDay'])) {
            $this->setAssetWorkOrderStartDay($this->strict($_POST ['assetWorkOrderStartDay'], 'date'));
        }
        if (isset($_POST ['assetWorkOrderEndDay'])) {
            $this->setAssetWorkOrderEndDay($this->strict($_POST ['assetWorkOrderEndDay'], 'date'));
        }
        if (isset($_POST ['assetWorkOrderDate'])) {
            $this->setAssetWorkOrderDate($this->strict($_POST ['assetWorkOrderDate'], 'date'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['assetWorkOrderId'])) {
            $this->setAssetWorkOrderId($this->strict($_GET ['assetWorkOrderId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['assetId'])) {
            $this->setAssetId($this->strict($_GET ['assetId'], 'integer'));
        }
        if (isset($_GET ['assetWorkOrderRecurringTypeId'])) {
            $this->setAssetWorkOrderRecurringTypeId($this->strict($_GET ['assetWorkOrderRecurringTypeId'], 'integer'));
        }
        if (isset($_GET ['employeeId'])) {
            $this->setEmployeeId($this->strict($_GET ['employeeId'], 'integer'));
        }
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string'));
        }
        if (isset($_GET ['assetWorkOrderDescription'])) {
            $this->setAssetWorkOrderDescription($this->strict($_GET ['assetWorkOrderDescription'], 'string'));
        }
        if (isset($_GET ['assetWorkOrderStartHour'])) {
            $this->setAssetWorkOrderStartHour($this->strict($_GET ['assetWorkOrderStartHour'], 'time'));
        }
        if (isset($_GET ['assetWorkOrderEndHour'])) {
            $this->setAssetWorkOrderEndHour($this->strict($_GET ['assetWorkOrderEndHour'], 'time'));
        }
        if (isset($_GET ['assetWorkOrderStartDay'])) {
            $this->setAssetWorkOrderStartDay($this->strict($_GET ['assetWorkOrderStartDay'], 'date'));
        }
        if (isset($_GET ['assetWorkOrderEndDay'])) {
            $this->setAssetWorkOrderEndDay($this->strict($_GET ['assetWorkOrderEndDay'], 'date'));
        }
        if (isset($_GET ['assetWorkOrderDate'])) {
            $this->setAssetWorkOrderDate($this->strict($_GET ['assetWorkOrderDate'], 'date'));
        }
        if (isset($_GET ['assetWorkOrderId'])) {
            $this->setTotal(count($_GET ['assetWorkOrderId']));
            if (is_array($_GET ['assetWorkOrderId'])) {
                $this->assetWorkOrderId = array();
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
            if (isset($_GET ['assetWorkOrderId'])) {
                $this->setAssetWorkOrderId($this->strict($_GET ['assetWorkOrderId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getAssetWorkOrderId($i, 'array') . ",";
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
    public function getAssetWorkOrderId($key, $type) {
        if ($type == 'single') {
            return $this->assetWorkOrderId;
        } else {
            if ($type == 'array') {
                return $this->assetWorkOrderId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getAssetWorkOrderId ?")
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
     * @return \Core\Financial\FixedAsset\AssetWorkOrder\Model\AssetWorkOrderModel
     */
    public function setAssetWorkOrderId($value, $key, $type) {
        if ($type == 'single') {
            $this->assetWorkOrderId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->assetWorkOrderId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setAssetWorkOrderId?")
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
     * @return \Core\Financial\FixedAsset\AssetWorkOrder\Model\AssetWorkOrderModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
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
     * @return \Core\Financial\FixedAsset\AssetWorkOrder\Model\AssetWorkOrderModel
     */
    public function setAssetId($assetId) {
        $this->assetId = $assetId;
        return $this;
    }

    /**
     * To Return Recurring Type
     * @return int $assetWorkOrderRecurringTypeId
     */
    public function getAssetWorkOrderRecurringTypeId() {
        return $this->assetWorkOrderRecurringTypeId;
    }

    /**
     * To Set Recurring Type
     * @param int $assetWorkOrderRecurringTypeId Recurring Type
     * @return \Core\Financial\FixedAsset\AssetWorkOrder\Model\AssetWorkOrderModel
     */
    public function setAssetWorkOrderRecurringTypeId($assetWorkOrderRecurringTypeId) {
        $this->assetWorkOrderRecurringTypeId = $assetWorkOrderRecurringTypeId;
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
     * @return \Core\Financial\FixedAsset\AssetWorkOrder\Model\AssetWorkOrderModel
     */
    public function setEmployeeId($employeeId) {
        $this->employeeId = $employeeId;
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
     * @return \Core\Financial\FixedAsset\AssetWorkOrder\Model\AssetWorkOrderModel
     */
    public function setDocumentNumber($documentNumber) {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    /**
     * To Return Description
     * @return string $assetWorkOrderDescription
     */
    public function getAssetWorkOrderDescription() {
        return $this->assetWorkOrderDescription;
    }

    /**
     * To Set Description
     * @param string $assetWorkOrderDescription Description
     * @return \Core\Financial\FixedAsset\AssetWorkOrder\Model\AssetWorkOrderModel
     */
    public function setAssetWorkOrderDescription($assetWorkOrderDescription) {
        $this->assetWorkOrderDescription = $assetWorkOrderDescription;
        return $this;
    }

    /**
     * To Return Start Hour
     * @return string $assetWorkOrderStartHour
     */
    public function getAssetWorkOrderStartHour() {
        return $this->assetWorkOrderStartHour;
    }

    /**
     * To Set Start Hour
     * @param string $assetWorkOrderStartHour Start Hour
     * @return \Core\Financial\FixedAsset\AssetWorkOrder\Model\AssetWorkOrderModel
     */
    public function setAssetWorkOrderStartHour($assetWorkOrderStartHour) {
        $this->assetWorkOrderStartHour = $assetWorkOrderStartHour;
        return $this;
    }

    /**
     * To Return End Hour
     * @return string $assetWorkOrderEndHour
     */
    public function getAssetWorkOrderEndHour() {
        return $this->assetWorkOrderEndHour;
    }

    /**
     * To Set End Hour
     * @param string $assetWorkOrderEndHour End Hour
     * @return \Core\Financial\FixedAsset\AssetWorkOrder\Model\AssetWorkOrderModel
     */
    public function setAssetWorkOrderEndHour($assetWorkOrderEndHour) {
        $this->assetWorkOrderEndHour = $assetWorkOrderEndHour;
        return $this;
    }

    /**
     * To Return Start Day
     * @return string $assetWorkOrderStartDay
     */
    public function getAssetWorkOrderStartDay() {
        return $this->assetWorkOrderStartDay;
    }

    /**
     * To Set Start Day
     * @param string $assetWorkOrderStartDay Start Day
     * @return \Core\Financial\FixedAsset\AssetWorkOrder\Model\AssetWorkOrderModel
     */
    public function setAssetWorkOrderStartDay($assetWorkOrderStartDay) {
        $this->assetWorkOrderStartDay = $assetWorkOrderStartDay;
        return $this;
    }

    /**
     * To Return End Day
     * @return string $assetWorkOrderEndDay
     */
    public function getAssetWorkOrderEndDay() {
        return $this->assetWorkOrderEndDay;
    }

    /**
     * To Set End Day
     * @param string $assetWorkOrderEndDay End Day
     * @return \Core\Financial\FixedAsset\AssetWorkOrder\Model\AssetWorkOrderModel
     */
    public function setAssetWorkOrderEndDay($assetWorkOrderEndDay) {
        $this->assetWorkOrderEndDay = $assetWorkOrderEndDay;
        return $this;
    }

    /**
     * To Return Date
     * @return string $assetWorkOrderDate
     */
    public function getAssetWorkOrderDate() {
        return $this->assetWorkOrderDate;
    }

    /**
     * To Set Date
     * @param string $assetWorkOrderDate Date
     * @return \Core\Financial\FixedAsset\AssetWorkOrder\Model\AssetWorkOrderModel
     */
    public function setAssetWorkOrderDate($assetWorkOrderDate) {
        $this->assetWorkOrderDate = $assetWorkOrderDate;
        return $this;
    }

}

?>