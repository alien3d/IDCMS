<?php

namespace Core\System\Management\Warehouse\Model;

// using absolute path instead of relative path..
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
 * Class Warehouse
 * This is warehouse model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\System\Management\Warehouse\Model;
 * @subpackage Management
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class WarehouseModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $warehouseId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Branch
     * @var int
     */
    private $branchId;

    /**
     * Code
     * @var string
     */
    private $warehouseCode;

    /**
     * Description
     * @var string
     */
    private $warehouseDescription;

    /**
     * Address
     * @var string
     */
    private $warehouseAddress;

    /**
     * Postcode
     * @var string
     */
    private $warehousePostcode;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('warehouse');
        $this->setPrimaryKeyName('warehouseId');
        $this->setMasterForeignKeyName('warehouseId');
        $this->setFilterCharacter('warehouseDescription');
        //$this->setFilterCharacter('warehouseNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['warehouseId'])) {
            $this->setWarehouseId($this->strict($_POST ['warehouseId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['branchId'])) {
            $this->setBranchId($this->strict($_POST ['branchId'], 'int'));
        }
        if (isset($_POST ['warehouseCode'])) {
            $this->setWarehouseCode($this->strict($_POST ['warehouseCode'], 'string'));
        }
        if (isset($_POST ['warehouseDescription'])) {
            $this->setWarehouseDescription($this->strict($_POST ['warehouseDescription'], 'string'));
        }
        if (isset($_POST ['warehouseAddress'])) {
            $this->setWarehouseAddress($this->strict($_POST ['warehouseAddress'], 'string'));
        }
        if (isset($_POST ['warehousePostcode'])) {
            $this->setWarehousePostcode($this->strict($_POST ['warehousePostcode'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['warehouseId'])) {
            $this->setWarehouseId($this->strict($_GET ['warehouseId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['branchId'])) {
            $this->setBranchId($this->strict($_GET ['branchId'], 'int'));
        }
        if (isset($_GET ['warehouseCode'])) {
            $this->setWarehouseCode($this->strict($_GET ['warehouseCode'], 'string'));
        }
        if (isset($_GET ['warehouseDescription'])) {
            $this->setWarehouseDescription($this->strict($_GET ['warehouseDescription'], 'string'));
        }
        if (isset($_GET ['warehouseAddress'])) {
            $this->setWarehouseAddress($this->strict($_GET ['warehouseAddress'], 'string'));
        }
        if (isset($_GET ['warehousePostcode'])) {
            $this->setWarehousePostcode($this->strict($_GET ['warehousePostcode'], 'string'));
        }
        if (isset($_GET ['warehouseId'])) {
            $this->setTotal(count($_GET ['warehouseId']));
            if (is_array($_GET ['warehouseId'])) {
                $this->warehouseId = array();
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
            if (isset($_GET ['warehouseId'])) {
                $this->setWarehouseId($this->strict($_GET ['warehouseId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getWarehouseId($i, 'array') . ",";
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
     * @return \Core\System\Management\Warehouse\Model\WarehouseModel
     */
    public function setWarehouseId($value, $key, $type) {
        if ($type == 'single') {
            $this->warehouseId = $value;
            return $this;
        } else if ($type == 'array') {
            $this->warehouseId[$key] = $value;
            return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:setwarehouseId?"));
            exit();
        }
    }

    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getWarehouseId($key, $type) {
        if ($type == 'single') {
            return $this->warehouseId;
        } else if ($type == 'array') {
            return $this->warehouseId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:getwarehouseId ?"));
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
     * @return \Core\System\Management\Warehouse\Model\WarehouseModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Branch
     * @return int $branchId
     */
    public function getBranchId() {
        return $this->branchId;
    }

    /**
     * To Set Branch
     * @param int $branchId Branch
     * @return \Core\System\Management\Warehouse\Model\WarehouseModel
     */
    public function setBranchId($branchId) {
        $this->branchId = $branchId;
        return $this;
    }

    /**
     * To Return Code
     * @return string $warehouseCode
     */
    public function getWarehouseCode() {
        return $this->warehouseCode;
    }

    /**
     * To Set Code
     * @param string $warehouseCode Code
     * @return \Core\System\Management\Warehouse\Model\WarehouseModel
     */
    public function setWarehouseCode($warehouseCode) {
        $this->warehouseCode = $warehouseCode;
        return $this;
    }

    /**
     * To Return Description
     * @return string $warehouseDescription
     */
    public function getWarehouseDescription() {
        return $this->warehouseDescription;
    }

    /**
     * To Set Description
     * @param string $warehouseDescription Description
     * @return \Core\System\Management\Warehouse\Model\WarehouseModel
     */
    public function setWarehouseDescription($warehouseDescription) {
        $this->warehouseDescription = $warehouseDescription;
        return $this;
    }

    /**
     * To Return Address
     * @return string $warehouseAddress
     */
    public function getWarehouseAddress() {
        return $this->warehouseAddress;
    }

    /**
     * To Set Address
     * @param string $warehouseAddress Address
     * @return \Core\System\Management\Warehouse\Model\WarehouseModel
     */
    public function setWarehouseAddress($warehouseAddress) {
        $this->warehouseAddress = $warehouseAddress;
        return $this;
    }

    /**
     * To Return Postcode
     * @return string $warehousePostcode
     */
    public function getWarehousePostcode() {
        return $this->warehousePostcode;
    }

    /**
     * To Set Postcode
     * @param string $warehousePostcode Postcode
     * @return \Core\System\Management\Warehouse\Model\WarehouseModel
     */
    public function setWarehousePostcode($warehousePostcode) {
        $this->warehousePostcode = $warehousePostcode;
        return $this;
    }

}

?>