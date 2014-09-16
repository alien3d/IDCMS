<?php

namespace Core\Financial\Inventory\ProductResourcesEmployee\Model;

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
 * Class ProductResourcesEmployee
 * This is productResourcesEmployee model file.This is to ensure strict setting enable for all variable enter to database 
 * 
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\Financial\Inventory\ProductResourcesEmployee\Model;
 * @subpackage Inventory 
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ProductResourcesEmployeeModel extends ValidationClass {

    /**
     * Primary Key
     * @var int 
     */
    private $productResourcesEmployeeId;

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
     * Job
     * @var int 
     */
    private $jobId;

    /**
     * Employee
     * @var int 
     */
    private $employeeId;

    /**
     * Start Date
     * @var date 
     */
    private $productResourcesEmployeeStartDate;

    /**
     * End Date
     * @var date 
     */
    private $productResourcesEmployeeEndDate;

    /**
     * Cost
     * @var double 
     */
    private $productResourcesEmployeeCost;

    /**
     * Description
     * @var string 
     */
    private $productResourcesEmployeeDescription;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('productResourcesEmployee');
        $this->setPrimaryKeyName('productResourcesEmployeeId');
        $this->setMasterForeignKeyName('productResourcesEmployeeId');
        $this->setFilterCharacter('productResourcesEmployeeDescription');
        //$this->setFilterCharacter('productResourcesEmployeeNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['productResourcesEmployeeId'])) {
            $this->setProductResourcesEmployeeId($this->strict($_POST ['productResourcesEmployeeId'], 'int'), 0, 'single');
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
        if (isset($_POST ['jobId'])) {
            $this->setJobId($this->strict($_POST ['jobId'], 'int'));
        }
        if (isset($_POST ['employeeId'])) {
            $this->setEmployeeId($this->strict($_POST ['employeeId'], 'int'));
        }
        if (isset($_POST ['productResourcesEmployeeStartDate'])) {
            $this->setProductResourcesEmployeeStartDate($this->strict($_POST ['productResourcesEmployeeStartDate'], 'date'));
        }
        if (isset($_POST ['productResourcesEmployeeEndDate'])) {
            $this->setProductResourcesEmployeeEndDate($this->strict($_POST ['productResourcesEmployeeEndDate'], 'date'));
        }
        if (isset($_POST ['productResourcesEmployeeCost'])) {
            $this->setProductResourcesEmployeeCost($this->strict($_POST ['productResourcesEmployeeCost'], 'double'));
        }
        if (isset($_POST ['productResourcesEmployeeDescription'])) {
            $this->setProductResourcesEmployeeDescription($this->strict($_POST ['productResourcesEmployeeDescription'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['productResourcesEmployeeId'])) {
            $this->setProductResourcesEmployeeId($this->strict($_GET ['productResourcesEmployeeId'], 'int'), 0, 'single');
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
        if (isset($_GET ['jobId'])) {
            $this->setJobId($this->strict($_GET ['jobId'], 'int'));
        }
        if (isset($_GET ['employeeId'])) {
            $this->setEmployeeId($this->strict($_GET ['employeeId'], 'int'));
        }
        if (isset($_GET ['productResourcesEmployeeStartDate'])) {
            $this->setProductResourcesEmployeeStartDate($this->strict($_GET ['productResourcesEmployeeStartDate'], 'date'));
        }
        if (isset($_GET ['productResourcesEmployeeEndDate'])) {
            $this->setProductResourcesEmployeeEndDate($this->strict($_GET ['productResourcesEmployeeEndDate'], 'date'));
        }
        if (isset($_GET ['productResourcesEmployeeCost'])) {
            $this->setProductResourcesEmployeeCost($this->strict($_GET ['productResourcesEmployeeCost'], 'double'));
        }
        if (isset($_GET ['productResourcesEmployeeDescription'])) {
            $this->setProductResourcesEmployeeDescription($this->strict($_GET ['productResourcesEmployeeDescription'], 'string'));
        }
        if (isset($_GET ['productResourcesEmployeeId'])) {
            $this->setTotal(count($_GET ['productResourcesEmployeeId']));
            if (is_array($_GET ['productResourcesEmployeeId'])) {
                $this->productResourcesEmployeeId = array();
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
            if (isset($_GET ['productResourcesEmployeeId'])) {
                $this->setProductResourcesEmployeeId($this->strict($_GET ['productResourcesEmployeeId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getProductResourcesEmployeeId($i, 'array') . ",";
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
     * @return \Core\Financial\Inventory\ProductResourcesEmployee\Model\ProductResourcesEmployeeModel
     */
    public function setProductResourcesEmployeeId($value, $key, $type) {
        if ($type == 'single') {
            $this->productResourcesEmployeeId = $value;
            return $this;
        } else if ($type == 'array') {
            $this->productResourcesEmployeeId[$key] = $value;
            return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:setproductResourcesEmployeeId?"));
            exit();
        }
    }

    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getProductResourcesEmployeeId($key, $type) {
        if ($type == 'single') {
            return $this->productResourcesEmployeeId;
        } else if ($type == 'array') {
            return $this->productResourcesEmployeeId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:getproductResourcesEmployeeId ?"));
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
     * @return \Core\Financial\Inventory\ProductResourcesEmployee\Model\ProductResourcesEmployeeModel
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
     * @return \Core\Financial\Inventory\ProductResourcesEmployee\Model\ProductResourcesEmployeeModel
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
     * @return \Core\Financial\Inventory\ProductResourcesEmployee\Model\ProductResourcesEmployeeModel
     */
    public function setProductResourcesTypeId($productResourcesTypeId) {
        $this->productResourcesTypeId = $productResourcesTypeId;
        return $this;
    }

    /**
     * To Return Job 
     * @return int $jobId
     */
    public function getJobId() {
        return $this->jobId;
    }

    /**
     * To Set Job 
     * @param int $jobId Job 
     * @return \Core\Financial\Inventory\ProductResourcesEmployee\Model\ProductResourcesEmployeeModel
     */
    public function setJobId($jobId) {
        $this->jobId = $jobId;
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
     * @return \Core\Financial\Inventory\ProductResourcesEmployee\Model\ProductResourcesEmployeeModel
     */
    public function setEmployeeId($employeeId) {
        $this->employeeId = $employeeId;
        return $this;
    }

    /**
     * To Return Start Date 
     * @return date $productResourcesEmployeeStartDate
     */
    public function getProductResourcesEmployeeStartDate() {
        return $this->productResourcesEmployeeStartDate;
    }

    /**
     * To Set Start Date 
     * @param date $productResourcesEmployeeStartDate Start Date 
     * @return \Core\Financial\Inventory\ProductResourcesEmployee\Model\ProductResourcesEmployeeModel
     */
    public function setProductResourcesEmployeeStartDate($productResourcesEmployeeStartDate) {
        $this->productResourcesEmployeeStartDate = $productResourcesEmployeeStartDate;
        return $this;
    }

    /**
     * To Return End Date 
     * @return date $productResourcesEmployeeEndDate
     */
    public function getProductResourcesEmployeeEndDate() {
        return $this->productResourcesEmployeeEndDate;
    }

    /**
     * To Set End Date 
     * @param date $productResourcesEmployeeEndDate End Date 
     * @return \Core\Financial\Inventory\ProductResourcesEmployee\Model\ProductResourcesEmployeeModel
     */
    public function setProductResourcesEmployeeEndDate($productResourcesEmployeeEndDate) {
        $this->productResourcesEmployeeEndDate = $productResourcesEmployeeEndDate;
        return $this;
    }

    /**
     * To Return Cost 
     * @return double $productResourcesEmployeeCost
     */
    public function getProductResourcesEmployeeCost() {
        return $this->productResourcesEmployeeCost;
    }

    /**
     * To Set Cost 
     * @param double $productResourcesEmployeeCost Cost 
     * @return \Core\Financial\Inventory\ProductResourcesEmployee\Model\ProductResourcesEmployeeModel
     */
    public function setProductResourcesEmployeeCost($productResourcesEmployeeCost) {
        $this->productResourcesEmployeeCost = $productResourcesEmployeeCost;
        return $this;
    }

    /**
     * To Return Description 
     * @return string $productResourcesEmployeeDescription
     */
    public function getProductResourcesEmployeeDescription() {
        return $this->productResourcesEmployeeDescription;
    }

    /**
     * To Set Description 
     * @param string $productResourcesEmployeeDescription Description 
     * @return \Core\Financial\Inventory\ProductResourcesEmployee\Model\ProductResourcesEmployeeModel
     */
    public function setProductResourcesEmployeeDescription($productResourcesEmployeeDescription) {
        $this->productResourcesEmployeeDescription = $productResourcesEmployeeDescription;
        return $this;
    }

}

?>