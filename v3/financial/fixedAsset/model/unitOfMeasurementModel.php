<?php

namespace Core\Financial\FixedAsset\UnitOfMeasurement\Model;

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
 * Class UnitOfMeasurement
 * This is Unit Of Measurement model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\FixedAsset\UnitOfMeasurement\Model;
 * @subpackage FixedAsset
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class UnitOfMeasurementModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $unitOfMeasurementId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Warehouse
     * @var int
     */
    private $warehouseId;

    /**
     * Category
     * @var int
     */
    private $unitOfMeasurementCategoryId;

    /**
     * Code
     * @var string
     */
    private $unitOfMeasurementCode;

    /**
     * Description
     * @var string
     */
    private $unitOfMeasurementDescription;

    /**
     * Base Value
     * @var int
     */
    private $unitOfMeasurementBaseValue;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('unitOfMeasurement');
        $this->setPrimaryKeyName('unitOfMeasurementId');
        $this->setMasterForeignKeyName('unitOfMeasurementId');
        $this->setFilterCharacter('unitOfMeasurementDescription');
        //$this->setFilterCharacter('unitOfMeasurementNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['unitOfMeasurementId'])) {
            $this->setUnitOfMeasurementId($this->strict($_POST ['unitOfMeasurementId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['warehouseId'])) {
            $this->setWarehouseId($this->strict($_POST ['warehouseId'], 'integer'));
        }
        if (isset($_POST ['unitOfMeasurementCategoryId'])) {
            $this->setUnitOfMeasurementCategoryId($this->strict($_POST ['unitOfMeasurementCategoryId'], 'integer'));
        }
        if (isset($_POST ['unitOfMeasurementCode'])) {
            $this->setUnitOfMeasurementCode($this->strict($_POST ['unitOfMeasurementCode'], 'string'));
        }
        if (isset($_POST ['unitOfMeasurementDescription'])) {
            $this->setUnitOfMeasurementDescription($this->strict($_POST ['unitOfMeasurementDescription'], 'string'));
        }
        if (isset($_POST ['unitOfMeasurementBaseValue'])) {
            $this->setUnitOfMeasurementBaseValue($this->strict($_POST ['unitOfMeasurementBaseValue'], 'integer'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['unitOfMeasurementId'])) {
            $this->setUnitOfMeasurementId($this->strict($_GET ['unitOfMeasurementId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['warehouseId'])) {
            $this->setWarehouseId($this->strict($_GET ['warehouseId'], 'integer'));
        }
        if (isset($_GET ['unitOfMeasurementCategoryId'])) {
            $this->setUnitOfMeasurementCategoryId($this->strict($_GET ['unitOfMeasurementCategoryId'], 'integer'));
        }
        if (isset($_GET ['unitOfMeasurementCode'])) {
            $this->setUnitOfMeasurementCode($this->strict($_GET ['unitOfMeasurementCode'], 'string'));
        }
        if (isset($_GET ['unitOfMeasurementDescription'])) {
            $this->setUnitOfMeasurementDescription($this->strict($_GET ['unitOfMeasurementDescription'], 'string'));
        }
        if (isset($_GET ['unitOfMeasurementBaseValue'])) {
            $this->setUnitOfMeasurementBaseValue($this->strict($_GET ['unitOfMeasurementBaseValue'], 'integer'));
        }
        if (isset($_GET ['unitOfMeasurementId'])) {
            $this->setTotal(count($_GET ['unitOfMeasurementId']));
            if (is_array($_GET ['unitOfMeasurementId'])) {
                $this->unitOfMeasurementId = array();
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
            if (isset($_GET ['unitOfMeasurementId'])) {
                $this->setUnitOfMeasurementId(
                        $this->strict($_GET ['unitOfMeasurementId'] [$i], 'numeric'), $i, 'array'
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
            $primaryKeyAll .= $this->getUnitOfMeasurementId($i, 'array') . ",";
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
    public function getUnitOfMeasurementId($key, $type) {
        if ($type == 'single') {
            return $this->unitOfMeasurementId;
        } else {
            if ($type == 'array') {
                return $this->unitOfMeasurementId [$key];
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:getUnitOfMeasurementId ?"
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
     * @return \Core\Financial\FixedAsset\UnitOfMeasurement\Model\UnitOfMeasurementModel
     */
    public function setUnitOfMeasurementId($value, $key, $type) {
        if ($type == 'single') {
            $this->unitOfMeasurementId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->unitOfMeasurementId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:setUnitOfMeasurementId?"
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
     * @return \Core\Financial\FixedAsset\UnitOfMeasurement\Model\UnitOfMeasurementModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Warehouse
     * @return int $warehouseId
     */
    public function getWarehouseId() {
        return $this->warehouseId;
    }

    /**
     * To Set Warehouse
     * @param int $warehouseId Warehouse
     * @return \Core\Financial\FixedAsset\UnitOfMeasurement\Model\UnitOfMeasurementModel
     */
    public function setWarehouseId($warehouseId) {
        $this->warehouseId = $warehouseId;
        return $this;
    }

    /**
     * To Return Category
     * @return int $unitOfMeasurementCategoryId
     */
    public function getUnitOfMeasurementCategoryId() {
        return $this->unitOfMeasurementCategoryId;
    }

    /**
     * To Set Category
     * @param int $unitOfMeasurementCategoryId Category
     * @return \Core\Financial\FixedAsset\UnitOfMeasurement\Model\UnitOfMeasurementModel
     */
    public function setUnitOfMeasurementCategoryId($unitOfMeasurementCategoryId) {
        $this->unitOfMeasurementCategoryId = $unitOfMeasurementCategoryId;
        return $this;
    }

    /**
     * To Return Code
     * @return string $unitOfMeasurementCode
     */
    public function getUnitOfMeasurementCode() {
        return $this->unitOfMeasurementCode;
    }

    /**
     * To Set Code
     * @param string $unitOfMeasurementCode Code
     * @return \Core\Financial\FixedAsset\UnitOfMeasurement\Model\UnitOfMeasurementModel
     */
    public function setUnitOfMeasurementCode($unitOfMeasurementCode) {
        $this->unitOfMeasurementCode = $unitOfMeasurementCode;
        return $this;
    }

    /**
     * To Return Description
     * @return string $unitOfMeasurementDescription
     */
    public function getUnitOfMeasurementDescription() {
        return $this->unitOfMeasurementDescription;
    }

    /**
     * To Set Description
     * @param string $unitOfMeasurementDescription Description
     * @return \Core\Financial\FixedAsset\UnitOfMeasurement\Model\UnitOfMeasurementModel
     */
    public function setUnitOfMeasurementDescription($unitOfMeasurementDescription) {
        $this->unitOfMeasurementDescription = $unitOfMeasurementDescription;
        return $this;
    }

    /**
     * To Return Base Value
     * @return int $unitOfMeasurementBaseValue
     */
    public function getUnitOfMeasurementBaseValue() {
        return $this->unitOfMeasurementBaseValue;
    }

    /**
     * To Set Base Value
     * @param int $unitOfMeasurementBaseValue Base Value
     * @return \Core\Financial\FixedAsset\UnitOfMeasurement\Model\UnitOfMeasurementModel
     */
    public function setUnitOfMeasurementBaseValue($unitOfMeasurementBaseValue) {
        $this->unitOfMeasurementBaseValue = $unitOfMeasurementBaseValue;
        return $this;
    }

}

?>