<?php  namespace Core\Financial\AccountPayable\PurchaseRequest\Model;
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
    if ($d[$i] == 'library' || $d[$i] == 'v3') {
        break;
    }
    $newPath[] .= $d[$i] . "/";
}
$fakeDocumentRoot = null;
for ($z = 0; $z < count($newPath); $z ++) {
    $fakeDocumentRoot .= $newPath[$z];
}
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot); // start
require_once ($newFakeDocumentRoot."library/class/classValidation.php"); 
/** 
 * Class PurchaseRequest
 * This is purchaseRequest model file.This is to ensure strict setting enable for all variable enter to database 
 * 
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountPayable\PurchaseRequest\Model;
 * @subpackage AccountPayable 
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class PurchaseRequestModel extends ValidationClass {
 /**
  * Chart Of Account Primary Key
  * @var int
  */
 private $chartOfAccountId;
 /**
  * Primary Key
  * @var int 
  */
  private $purchaseRequestId; 
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
  * Country
  * @var int 
  */
  private $countryId; 
 /**
  * Branch
  * @var int 
  */
  private $branchId; 
 /**
  * Department
  * @var int 
  */
  private $departmentId; 
 /**
  * Warehouse
  * @var int 
  */
  private $warehouseId; 
 /**
  * Product Resources
  * @var int 
  */
  private $productResourcesId; 
 /**
  * Equipment Status
  * @var int 
  */
  private $equipmentStatusId; 
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
  * Reference Number
  * @var string 
  */
  private $referenceNumber; 
 /**
  * Date
  * @var string
  */
  private $purchaseRequestDate; 
 /**
  * Required Date
  * @var string
  */
  private $purchaseRequestRequiredDate; 
 /**
  * Valid Date
  * @var string
  */
  private $purchaseRequestValidStartDate; 
 /**
  * Valid Date
  * @var string
  */
  private $purchaseRequestValidEndDate; 
 /**
  * Description
  * @var string 
  */
  private $purchaseRequestDescription; 
 /**
  * Is Reject
  * @var bool 
  */
  private $isReject; 
 /**
  * Class Loader
  * @see ValidationClass::execute()
  */
 public function execute() {
     /**
     *  Basic Information Table
     **/
     $this->setTableName('purchaseRequest');
     $this->setPrimaryKeyName('purchaseRequestId');
     $this->setMasterForeignKeyName('purchaseRequestId');
     $this->setFilterCharacter('purchaseRequestDescription');
     //$this->setFilterCharacter('purchaseRequestNote');
     $this->setFilterDate('executeTime');
     /**
     * All the $_POST Environment
     */
     if (isset($_POST ['chartOfAccountId'])) {
         $this->setChartOfAccountId($this->strict($_POST ['chartOfAccountId'], 'int'));
     }
     if (isset($_POST ['purchaseRequestId'])) { 
          $this->setPurchaseRequestId($this->strict($_POST ['purchaseRequestId'], 'int'), 0, 'single'); 
      } 
      if (isset($_POST ['companyId'])) { 
          $this->setCompanyId($this->strict($_POST ['companyId'], 'int')); 
      } 
      if (isset($_POST ['businessPartnerId'])) { 
          $this->setBusinessPartnerId($this->strict($_POST ['businessPartnerId'], 'int')); 
      }
	   if (isset($_POST ['countryId'])) { 
          $this->setCountryId($this->strict($_POST ['countryId'], 'int')); 
      } 
      if (isset($_POST ['branchId'])) { 
          $this->setBranchId($this->strict($_POST ['branchId'], 'int')); 
      } 
      if (isset($_POST ['departmentId'])) { 
          $this->setDepartmentId($this->strict($_POST ['departmentId'], 'int')); 
      } 
      if (isset($_POST ['warehouseId'])) { 
          $this->setWarehouseId($this->strict($_POST ['warehouseId'], 'int')); 
      } 
      if (isset($_POST ['productResourcesId'])) { 
          $this->setProductResourcesId($this->strict($_POST ['productResourcesId'], 'int')); 
      } 
      if (isset($_POST ['equipmentStatusId'])) { 
          $this->setEquipmentStatusId($this->strict($_POST ['equipmentStatusId'], 'int')); 
      } 
      if (isset($_POST ['employeeId'])) { 
          $this->setEmployeeId($this->strict($_POST ['employeeId'], 'int')); 
      } 
      if (isset($_POST ['documentNumber'])) { 
          $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string')); 
      } 
      if (isset($_POST ['referenceNumber'])) { 
          $this->setReferenceNumber($this->strict($_POST ['referenceNumber'], 'string')); 
      } 
      if (isset($_POST ['purchaseRequestDate'])) { 
          $this->setPurchaseRequestDate($this->strict($_POST ['purchaseRequestDate'], 'date')); 
      } 
      if (isset($_POST ['purchaseRequestRequiredDate'])) { 
          $this->setPurchaseRequestRequiredDate($this->strict($_POST ['purchaseRequestRequiredDate'], 'date')); 
      } 
      if (isset($_POST ['purchaseRequestValidStartDate'])) { 
          $this->setPurchaseRequestValidStartDate($this->strict($_POST ['purchaseRequestValidStartDate'], 'date')); 
      } 
      if (isset($_POST ['purchaseRequestValidEndDate'])) { 
          $this->setPurchaseRequestValidEndDate($this->strict($_POST ['purchaseRequestValidEndDate'], 'date')); 
      } 
      if (isset($_POST ['purchaseRequestDescription'])) { 
          $this->setPurchaseRequestDescription($this->strict($_POST ['purchaseRequestDescription'], 'string')); 
      } 
      if (isset($_POST ['isReject'])) { 
          $this->setIsReject($this->strict($_POST ['isReject'], 'bool')); 
      } 
      /**
     * All the $_GET Environment
     */
     if (isset($_GET ['chartOfAccountId'])) {
         $this->setChartOfAccountId($this->strict($_GET ['chartOfAccountId'], 'int'));
     }
     if (isset($_GET ['purchaseRequestId'])) { 
          $this->setPurchaseRequestId($this->strict($_GET ['purchaseRequestId'], 'int'), 0, 'single'); 
      } 
      if (isset($_GET ['companyId'])) { 
          $this->setCompanyId($this->strict($_GET ['companyId'], 'int')); 
      } 
       if (isset($_GET ['businessPartnerId'])) { 
          $this->setBusinessPartnerId($this->strict($_GET ['businessPartnerId'], 'int')); 
      }
	   if (isset($_GET ['countryId'])) { 
          $this->setCountryId($this->strict($_GET ['countryId'], 'int')); 
      } 
      if (isset($_GET ['branchId'])) { 
          $this->setBranchId($this->strict($_GET ['branchId'], 'int')); 
      } 
      if (isset($_GET ['departmentId'])) { 
          $this->setDepartmentId($this->strict($_GET ['departmentId'], 'int')); 
      } 
      if (isset($_GET ['warehouseId'])) { 
          $this->setWarehouseId($this->strict($_GET ['warehouseId'], 'int')); 
      } 
      if (isset($_GET ['productResourcesId'])) { 
          $this->setProductResourcesId($this->strict($_GET ['productResourcesId'], 'int')); 
      } 
      if (isset($_GET ['equipmentStatusId'])) { 
          $this->setEquipmentStatusId($this->strict($_GET ['equipmentStatusId'], 'int')); 
      } 
      if (isset($_GET ['employeeId'])) { 
          $this->setEmployeeId($this->strict($_GET ['employeeId'], 'int')); 
      } 
      if (isset($_GET ['documentNumber'])) { 
          $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string')); 
      } 
      if (isset($_GET ['referenceNumber'])) { 
          $this->setReferenceNumber($this->strict($_GET ['referenceNumber'], 'string')); 
      } 
      if (isset($_GET ['purchaseRequestDate'])) { 
          $this->setPurchaseRequestDate($this->strict($_GET ['purchaseRequestDate'], 'date')); 
      } 
      if (isset($_GET ['purchaseRequestRequiredDate'])) { 
          $this->setPurchaseRequestRequiredDate($this->strict($_GET ['purchaseRequestRequiredDate'], 'date')); 
      } 
      if (isset($_GET ['purchaseRequestValidStartDate'])) { 
          $this->setPurchaseRequestValidStartDate($this->strict($_GET ['purchaseRequestValidStartDate'], 'date')); 
      } 
      if (isset($_GET ['purchaseRequestValidEndDate'])) { 
          $this->setPurchaseRequestValidEndDate($this->strict($_GET ['purchaseRequestValidEndDate'], 'date')); 
      } 
      if (isset($_GET ['purchaseRequestDescription'])) { 
          $this->setPurchaseRequestDescription($this->strict($_GET ['purchaseRequestDescription'], 'string')); 
      } 
      if (isset($_GET ['isReject'])) { 
          $this->setIsReject($this->strict($_GET ['isReject'], 'bool')); 
      } 
      if (isset($_GET ['purchaseRequestId'])) {
         $this->setTotal(count($_GET ['purchaseRequestId']));
         if (is_array($_GET ['purchaseRequestId'])) {
             $this->purchaseRequestId = array();
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
         if (isset($_GET ['purchaseRequestId'])) {
             $this->setPurchaseRequestId($this->strict($_GET ['purchaseRequestId'] [$i], 'numeric'), $i, 'array');
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
         $primaryKeyAll .= $this->getPurchaseRequestId($i, 'array') . ",";
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
     * @return \Core\Financial\AccountPayable\PurchaseRequest\Model\PurchaseRequestModel
     */ 
     public function setPurchaseRequestId($value, $key, $type) { 
        if ($type == 'single') { 
           $this->purchaseRequestId = $value;
           return $this;
        } else if ($type == 'array') {
            $this->purchaseRequestId[$key] = $value;
           return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:setpurchaseRequestId?"));
            exit(); 
        }
    }
    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getPurchaseRequestId($key, $type) {
        if ($type == 'single') {
            return $this->purchaseRequestId;
        } else if ($type == 'array') {
            return $this->purchaseRequestId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:getpurchaseRequestId ?"));
            exit();
        }
	}

    /**
     * To Return Chart Of Account Primary Key
     * @return int $chartOfAccountId
     */
    public function getChartOfAccountId()
    {
        return $this->chartOfAccountId;
    }
    /**
     * To Return Chart Of Account Primary Key
     * @param int $chartOfAccountId Chart Of Account Primary Key
     * @return \Core\Financial\AccountPayable\PurchaseRequest\Model\PurchaseRequestModel
     */
    public function setChartOfAccountId($chartOfAccountId)
    {
        $this->chartOfAccountId = $chartOfAccountId;
        return $this;
    }
    /**
	 * To Return Company 
	 * @return int $companyId
	 */ 
	public function getCompanyId()
	{
	    return $this->companyId;
	}
	/**
	 * To Set Company 
	 * @param int $companyId Company 
	 * @return \Core\Financial\AccountPayable\PurchaseRequest\Model\PurchaseRequestModel
	 */
	public function setCompanyId($companyId)
	{
         $this->companyId = $companyId;
         return $this;
	} 
        /**
         * To Return Busines Partner
	 * @return int $businessPartnerId
	 */ 
	public function getBusinessPartnerId()
	{
	    return $this->businessPartnerId;
	}
	/**
	 * To Set Business Partner
	 * @param int $businessPartnerId Company 
	 * @return \Core\Financial\AccountPayable\PurchaseRequest\Model\PurchaseRequestModel
	 */
	public function setBusinessPartnerId($businessPartnerId)
	{
         $this->businessPartnerId = $businessPartnerId;
         return $this;
	} 
		/**
	 * To Return Country
	 * @return int $countryId
	 */ 
	public function getCountryId()
	{
	    return $this->countryId;
	}
	/**
	 * To Set Country
	 * @param int $countryId Country
	 * @return \Core\Financial\AccountPayable\PurchaseRequest\Model\PurchaseRequestModel
	 */
	public function setCountryId($countryId)
	{
         $this->countryId = $countryId;
         return $this;
	} 
	/**
	 * To Return Branch 
	 * @return int $branchId
	 */ 
	public function getBranchId()
	{
	    return $this->branchId;
	}
	/**
	 * To Set Branch 
	 * @param int $branchId Branch 
	 * @return \Core\Financial\AccountPayable\PurchaseRequest\Model\PurchaseRequestModel
	 */
	public function setBranchId($branchId)
	{
         $this->branchId = $branchId;
         return $this;
	} 
	/**
	 * To Return Department 
	 * @return int $departmentId
	 */ 
	public function getDepartmentId()
	{
	    return $this->departmentId;
	}
	/**
	 * To Set Department 
	 * @param int $departmentId Department 
	 * @return \Core\Financial\AccountPayable\PurchaseRequest\Model\PurchaseRequestModel
	 */
	public function setDepartmentId($departmentId)
	{
         $this->departmentId = $departmentId;
         return $this;
	} 
	/**
	 * To Return Warehouse 
	 * @return int $warehouseId
	 */ 
	public function getWarehouseId()
	{
	    return $this->warehouseId;
	}
	/**
	 * To Set Warehouse 
	 * @param int $warehouseId Warehouse 
	 * @return \Core\Financial\AccountPayable\PurchaseRequest\Model\PurchaseRequestModel
	 */
	public function setWarehouseId($warehouseId)
	{
         $this->warehouseId = $warehouseId;
         return $this;
	} 
	/**
	 * To Return Product Resources 
	 * @return int $productResourcesId
	 */ 
	public function getProductResourcesId()
	{
	    return $this->productResourcesId;
	}
	/**
	 * To Set Product Resources 
	 * @param int $productResourcesId Product Resources 
	 * @return \Core\Financial\AccountPayable\PurchaseRequest\Model\PurchaseRequestModel
	 */
	public function setProductResourcesId($productResourcesId)
	{
         $this->productResourcesId = $productResourcesId;
         return $this;
	} 
	/**
	 * To Return Equipment Status 
	 * @return int $equipmentStatusId
	 */ 
	public function getEquipmentStatusId()
	{
	    return $this->equipmentStatusId;
	}
	/**
	 * To Set Equipment Status 
	 * @param int $equipmentStatusId Equipment Status 
	 * @return \Core\Financial\AccountPayable\PurchaseRequest\Model\PurchaseRequestModel
	 */
	public function setEquipmentStatusId($equipmentStatusId)
	{
         $this->equipmentStatusId = $equipmentStatusId;
         return $this;
	} 
	/**
	 * To Return Employee 
	 * @return int $employeeId
	 */ 
	public function getEmployeeId()
	{
	    return $this->employeeId;
	}
	/**
	 * To Set Employee 
	 * @param int $employeeId Employee 
	 * @return \Core\Financial\AccountPayable\PurchaseRequest\Model\PurchaseRequestModel
	 */
	public function setEmployeeId($employeeId)
	{
         $this->employeeId = $employeeId;
         return $this;
	} 
	/**
	 * To Return Document Number 
	 * @return string $documentNumber
	 */ 
	public function getDocumentNumber()
	{
	    return $this->documentNumber;
	}
	/**
	 * To Set Document Number 
	 * @param string $documentNumber Document Number 
	 * @return \Core\Financial\AccountPayable\PurchaseRequest\Model\PurchaseRequestModel
	 */
	public function setDocumentNumber($documentNumber)
	{
         $this->documentNumber = $documentNumber;
         return $this;
	} 
	/**
	 * To Return Reference Number 
	 * @return string $referenceNumber
	 */ 
	public function getReferenceNumber()
	{
	    return $this->referenceNumber;
	}
	/**
	 * To Set Reference Number 
	 * @param string $referenceNumber Reference Number 
	 * @return \Core\Financial\AccountPayable\PurchaseRequest\Model\PurchaseRequestModel
	 */
	public function setReferenceNumber($referenceNumber)
	{
         $this->referenceNumber = $referenceNumber;
         return $this;
	} 
	/**
	 * To Return Date 
	 * @return string $purchaseRequestDate
	 */ 
	public function getPurchaseRequestDate()
	{
	    return $this->purchaseRequestDate;
	}
	/**
	 * To Set Date 
	 * @param string $purchaseRequestDate Date
	 * @return \Core\Financial\AccountPayable\PurchaseRequest\Model\PurchaseRequestModel
	 */
	public function setPurchaseRequestDate($purchaseRequestDate)
	{
         $this->purchaseRequestDate = $purchaseRequestDate;
         return $this;
	} 
	/**
	 * To Return Required Date 
	 * @return string $purchaseRequestRequiredDate
	 */ 
	public function getPurchaseRequestRequiredDate()
	{
	    return $this->purchaseRequestRequiredDate;
	}
	/**
	 * To Set Required Date 
	 * @param string $purchaseRequestRequiredDate Required Date
	 * @return \Core\Financial\AccountPayable\PurchaseRequest\Model\PurchaseRequestModel
	 */
	public function setPurchaseRequestRequiredDate($purchaseRequestRequiredDate)
	{
         $this->purchaseRequestRequiredDate = $purchaseRequestRequiredDate;
         return $this;
	} 
	/**
	 * To Return Valid Date 
	 * @return string $purchaseRequestValidStartDate
	 */ 
	public function getPurchaseRequestValidStartDate()
	{
	    return $this->purchaseRequestValidStartDate;
	}
	/**
	 * To Set Valid Date 
	 * @param string $purchaseRequestValidStartDate Valid Date
	 * @return \Core\Financial\AccountPayable\PurchaseRequest\Model\PurchaseRequestModel
	 */
	public function setPurchaseRequestValidStartDate($purchaseRequestValidStartDate)
	{
         $this->purchaseRequestValidStartDate = $purchaseRequestValidStartDate;
         return $this;
	} 
	/**
	 * To Return Valid Date 
	 * @return string $purchaseRequestValidEndDate
	 */ 
	public function getPurchaseRequestValidEndDate()
	{
	    return $this->purchaseRequestValidEndDate;
	}
	/**
	 * To Set Valid Date 
	 * @param string $purchaseRequestValidEndDate Valid Date
	 * @return \Core\Financial\AccountPayable\PurchaseRequest\Model\PurchaseRequestModel
	 */
	public function setPurchaseRequestValidEndDate($purchaseRequestValidEndDate)
	{
         $this->purchaseRequestValidEndDate = $purchaseRequestValidEndDate;
         return $this;
	} 
	/**
	 * To Return Description 
	 * @return string $purchaseRequestDescription
	 */ 
	public function getPurchaseRequestDescription()
	{
	    return $this->purchaseRequestDescription;
	}
	/**
	 * To Set Description 
	 * @param string $purchaseRequestDescription Description 
	 * @return \Core\Financial\AccountPayable\PurchaseRequest\Model\PurchaseRequestModel
	 */
	public function setPurchaseRequestDescription($purchaseRequestDescription)
	{
         $this->purchaseRequestDescription = $purchaseRequestDescription;
         return $this;
	} 
	/**
	 * To Return Is Reject 
	 * @return bool $isReject
	 */ 
	public function getIsReject()
	{
	    return $this->isReject;
	}
	/**
	 * To Set Is Reject 
	 * @param bool $isReject Is Reject 
	 * @return \Core\Financial\AccountPayable\PurchaseRequest\Model\PurchaseRequestModel
	 */
	public function setIsReject($isReject)
	{
         $this->isReject = $isReject;
         return $this;
	} 
}
?>