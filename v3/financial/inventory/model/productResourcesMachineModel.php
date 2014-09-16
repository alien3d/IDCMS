<?php

namespace Core\Financial\Inventory\ProductResourcesMachine\Model;

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
require_once ($newFakeDocumentRoot . "library/class/classValidation.php");

/**
 * Class ProductResourcesMachine
 * This is productResourcesMachine model file.This is to ensure strict setting enable for all variable enter to database 
 * 
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\Financial\Inventory\ProductResourcesMachine\Model;
 * @subpackage Inventory 
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ProductResourcesMachineModel extends ValidationClass {

    /**
     * Primary Key
     * @var int 
     */
    private $productResourcesMachineId;

    /**
     * Company
     * @var int 
     */
    private $companyId;

    /**
     * Product Resources
     * @var int 
     */
    private $productResourcesId;

    /**
     * Product Type
     * @var int 
     */
    private $productResourcesTypeId;

    /**
     * Asset
     * @var int 
     */
    private $assetId;

    /**
     * Cost
     * @var double 
     */
    private $productResourcesMachineCost;

    /**
     * Description
     * @var string 
     */
    private $productResourcesMachineDescription;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('productResourcesMachine');
        $this->setPrimaryKeyName('productResourcesMachineId');
        $this->setMasterForeignKeyName('productResourcesMachineId');
        $this->setFilterCharacter('productResourcesMachineDescription');
        //$this->setFilterCharacter('productResourcesMachineNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['productResourcesMachineId'])) {
            $this->setProductResourcesMachineId($this->strict($_POST ['productResourcesMachineId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['productResourcesId'])) {
            $this->setProductResourcesId($this->strict($_POST ['productResourcesId'], 'int'));
        }
        if (isset($_POST ['productResourcesTypeId'])) {
            $this->setProductResourcesTypeId($this->strict($_POST ['productResourcesTypeId'], 'int'));
        }
        if (isset($_POST ['assetId'])) {
            $this->setAssetId($this->strict($_POST ['assetId'], 'int'));
        }
        if (isset($_POST ['productResourcesMachineCost'])) {
            $this->setProductResourcesMachineCost($this->strict($_POST ['productResourcesMachineCost'], 'double'));
        }
        if (isset($_POST ['productResourcesMachineDescription'])) {
            $this->setProductResourcesMachineDescription($this->strict($_POST ['productResourcesMachineDescription'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['productResourcesMachineId'])) {
            $this->setProductResourcesMachineId($this->strict($_GET ['productResourcesMachineId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['productResourcesId'])) {
            $this->setProductResourcesId($this->strict($_GET ['productResourcesId'], 'int'));
        }
        if (isset($_GET ['productResourcesTypeId'])) {
            $this->setProductResourcesTypeId($this->strict($_GET ['productResourcesTypeId'], 'int'));
        }
        if (isset($_GET ['assetId'])) {
            $this->setAssetId($this->strict($_GET ['assetId'], 'int'));
        }
        if (isset($_GET ['productResourcesMachineCost'])) {
            $this->setProductResourcesMachineCost($this->strict($_GET ['productResourcesMachineCost'], 'double'));
        }
        if (isset($_GET ['productResourcesMachineDescription'])) {
            $this->setProductResourcesMachineDescription($this->strict($_GET ['productResourcesMachineDescription'], 'string'));
        }
        if (isset($_GET ['productResourcesMachineId'])) {
            $this->setTotal(count($_GET ['productResourcesMachineId']));
            if (is_array($_GET ['productResourcesMachineId'])) {
                $this->productResourcesMachineId = array();
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
            if (isset($_GET ['productResourcesMachineId'])) {
                $this->setProductResourcesMachineId($this->strict($_GET ['productResourcesMachineId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getProductResourcesMachineId($i, 'array') . ",";
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
            $this->setExecuteTime("to_date('" . date("Y-m-d H:i:s") . "','YYYY-MM-DD HH24:MI:SS');");
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
     * @return \Core\Financial\Inventory\ProductResourcesMachine\Model\ProductResourcesMachineModel
     */
    public function setProductResourcesMachineId($value, $key, $type) {
        if ($type == 'single') {
            $this->productResourcesMachineId = $value;
            return $this;
        } else if ($type == 'array') {
            $this->productResourcesMachineId[$key] = $value;
            return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:setproductResourcesMachineId?"));
            exit();
        }
    }

    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getProductResourcesMachineId($key, $type) {
        if ($type == 'single') {
            return $this->productResourcesMachineId;
        } else if ($type == 'array') {
            return $this->productResourcesMachineId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:getproductResourcesMachineId ?"));
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
     * @return \Core\Financial\Inventory\ProductResourcesMachine\Model\ProductResourcesMachineModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Product Resources 
     * @return int $productResourcesId
     */
    public function getProductResourcesId() {
        return $this->productResourcesId;
    }

    /**
     * To Set Product Resources 
     * @param int $productResourcesId Product Resources 
     * @return \Core\Financial\Inventory\ProductResourcesMachine\Model\ProductResourcesMachineModel
     */
    public function setProductResourcesId($productResourcesId) {
        $this->productResourcesId = $productResourcesId;
        return $this;
    }

    /**
     * To Return Product Type 
     * @return int $productResourcesTypeId
     */
    public function getProductResourcesTypeId() {
        return $this->productResourcesTypeId;
    }

    /**
     * To Set Product Type 
     * @param int $productResourcesTypeId Product Type 
     * @return \Core\Financial\Inventory\ProductResourcesMachine\Model\ProductResourcesMachineModel
     */
    public function setProductResourcesTypeId($productResourcesTypeId) {
        $this->productResourcesTypeId = $productResourcesTypeId;
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
     * @return \Core\Financial\Inventory\ProductResourcesMachine\Model\ProductResourcesMachineModel
     */
    public function setAssetId($assetId) {
        $this->assetId = $assetId;
        return $this;
    }

    /**
     * To Return Cost 
     * @return double $productResourcesMachineCost
     */
    public function getProductResourcesMachineCost() {
        return $this->productResourcesMachineCost;
    }

    /**
     * To Set Cost 
     * @param double $productResourcesMachineCost Cost 
     * @return \Core\Financial\Inventory\ProductResourcesMachine\Model\ProductResourcesMachineModel
     */
    public function setProductResourcesMachineCost($productResourcesMachineCost) {
        $this->productResourcesMachineCost = $productResourcesMachineCost;
        return $this;
    }

    /**
     * To Return Description 
     * @return string $productResourcesMachineDescription
     */
    public function getProductResourcesMachineDescription() {
        return $this->productResourcesMachineDescription;
    }

    /**
     * To Set Description 
     * @param string $productResourcesMachineDescription Description 
     * @return \Core\Financial\Inventory\ProductResourcesMachine\Model\ProductResourcesMachineModel
     */
    public function setProductResourcesMachineDescription($productResourcesMachineDescription) {
        $this->productResourcesMachineDescription = $productResourcesMachineDescription;
        return $this;
    }

}

?>