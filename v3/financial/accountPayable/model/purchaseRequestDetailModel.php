<?php  namespace Core\Financial\AccountPayable\PurchaseRequestDetail\Model;
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
 * Class PurchaseRequestDetail
 * This is purchaseRequestDetail model file.This is to ensure strict setting enable for all variable enter to database 
 * 
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountPayable\PurchaseRequestDetail\Model;
 * @subpackage AccountPayable 
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class PurchaseRequestDetailModel extends ValidationClass { 
 /**
  * Primary Key
  * @var int 
  */
  private $purchaseRequestDetailId; 
 /**
  * Company
  * @var int 
  */
  private $companyId; 
 /**
  * Purchase Request
  * @var int 
  */
  private $purchaseRequestId; 
 /**
  * Product
  * @var int 
  */
  private $productId; 
 /**
  * Product Description
  * @var string 
  */
  private $purchaseRequestDescription; 
 /**
  * Quantity
  * @var double 
  */
  private $purchaseRequestDetailQuantity; 
 /**
  * Unit Of Measurement
  * @var int 
  */
  private $unitOfMeasurementId; 
 /**
  * Chart Of Account
  * @var int 
  */
  private $chartOfAccountId; 
 /**
  * Budget
  * @var double 
  */
  private $purchaseRequestDetailBudget; 
 /**
  * Class Loader
  * @see ValidationClass::execute()
  */
 public function execute() {
     /**
     *  Basic Information Table
     **/
     $this->setTableName('purchaseRequestDetail');
     $this->setPrimaryKeyName('purchaseRequestDetailId');
     $this->setMasterForeignKeyName('purchaseRequestDetailId');
     $this->setFilterCharacter('purchaseRequestDetailDescription');
     //$this->setFilterCharacter('purchaseRequestDetailNote');
     $this->setFilterDate('executeTime');
     /**
     * All the $_POST Environment
     */ 
     if (isset($_POST ['purchaseRequestDetailId'])) { 
          $this->setPurchaseRequestDetailId($this->strict($_POST ['purchaseRequestDetailId'], 'int'), 0, 'single'); 
      } 
      if (isset($_POST ['companyId'])) { 
          $this->setCompanyId($this->strict($_POST ['companyId'], 'int')); 
      } 
      if (isset($_POST ['purchaseRequestId'])) { 
          $this->setPurchaseRequestId($this->strict($_POST ['purchaseRequestId'], 'int')); 
      } 
      if (isset($_POST ['productId'])) { 
          $this->setProductId($this->strict($_POST ['productId'], 'int')); 
      } 
      if (isset($_POST ['purchaseRequestDetailDescription'])) { 
          $this->setPurchaseRequestDetailDescription($this->strict($_POST ['purchaseRequestDetailDescription'], 'string')); 
      } 
      if (isset($_POST ['purchaseRequestDetailQuantity'])) { 
          $this->setPurchaseRequestDetailQuantity($this->strict($_POST ['purchaseRequestDetailQuantity'], 'double')); 
      } 
      if (isset($_POST ['unitOfMeasurementId'])) { 
          $this->setUnitOfMeasurementId($this->strict($_POST ['unitOfMeasurementId'], 'int')); 
      } 
      if (isset($_POST ['chartOfAccountId'])) { 
          $this->setChartOfAccountId($this->strict($_POST ['chartOfAccountId'], 'int')); 
      } 
      if (isset($_POST ['purchaseRequestDetailBudget'])) { 
          $this->setPurchaseRequestDetailBudget($this->strict($_POST ['purchaseRequestDetailBudget'], 'double')); 
      } 
	  if (isset($_POST ['from'])) {
            $this->setFrom($this->strict($_POST ['from'], 'string'));
        }
      /**
     * All the $_GET Environment
     */
     if (isset($_GET ['purchaseRequestDetailId'])) { 
          $this->setPurchaseRequestDetailId($this->strict($_GET ['purchaseRequestDetailId'], 'int'), 0, 'single'); 
      } 
      if (isset($_GET ['companyId'])) { 
          $this->setCompanyId($this->strict($_GET ['companyId'], 'int')); 
      } 
      if (isset($_GET ['purchaseRequestId'])) { 
          $this->setPurchaseRequestId($this->strict($_GET ['purchaseRequestId'], 'int')); 
      } 
      if (isset($_GET ['productId'])) { 
          $this->setProductId($this->strict($_GET ['productId'], 'int')); 
      } 
      if (isset($_GET ['purchaseRequestDetailDescription'])) { 
          $this->setPurchaseRequestDetailDescription($this->strict($_GET ['purchaseRequestDetailDescription'], 'string')); 
      } 
      if (isset($_GET ['purchaseRequestDetailQuantity'])) { 
          $this->setPurchaseRequestDetailQuantity($this->strict($_GET ['purchaseRequestDetailQuantity'], 'double')); 
      } 
      if (isset($_GET ['unitOfMeasurementId'])) { 
          $this->setUnitOfMeasurementId($this->strict($_GET ['unitOfMeasurementId'], 'int')); 
      } 
      if (isset($_GET ['chartOfAccountId'])) { 
          $this->setChartOfAccountId($this->strict($_GET ['chartOfAccountId'], 'int')); 
      } 
      if (isset($_GET ['purchaseRequestDetailBudget'])) { 
          $this->setPurchaseRequestDetailBudget($this->strict($_GET ['purchaseRequestDetailBudget'], 'double')); 
      } 
	  if (isset($_GET ['from'])) {
            $this->setFrom($this->strict($_GET ['from'], 'string'));
        }
      if (isset($_GET ['purchaseRequestDetailId'])) {
         $this->setTotal(count($_GET ['purchaseRequestDetailId']));
         if (is_array($_GET ['purchaseRequestDetailId'])) {
             $this->purchaseRequestDetailId = array();
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
         if (isset($_GET ['purchaseRequestDetailId'])) {
             $this->setPurchaseRequestDetailId($this->strict($_GET ['purchaseRequestDetailId'] [$i], 'numeric'), $i, 'array');
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
         $primaryKeyAll .= $this->getPurchaseRequestDetailId($i, 'array') . ",";
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
     * @return \Core\Financial\AccountPayable\PurchaseRequestDetail\Model\PurchaseRequestDetailModel
     */ 
     public function setPurchaseRequestDetailId($value, $key, $type) { 
        if ($type == 'single') { 
           $this->purchaseRequestDetailId = $value;
           return $this;
        } else if ($type == 'array') {
            $this->purchaseRequestDetailId[$key] = $value;
           return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:setpurchaseRequestDetailId?"));
            exit(); 
        }
    }
    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getPurchaseRequestDetailId($key, $type) {
        if ($type == 'single') {
            return $this->purchaseRequestDetailId;
        } else if ($type == 'array') {
            return $this->purchaseRequestDetailId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:getpurchaseRequestDetailId ?"));
            exit();
        }
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
	 * @return \Core\Financial\AccountPayable\PurchaseRequestDetail\Model\PurchaseRequestDetailModel
	 */
	public function setCompanyId($companyId)
	{
         $this->companyId = $companyId;
         return $this;
	} 
	/**
	 * To Return Purchase Request 
	 * @return int $purchaseRequestId
	 */ 
	public function getPurchaseRequestId()
	{
	    return $this->purchaseRequestId;
	}
	/**
	 * To Set Purchase Request 
	 * @param int $purchaseRequestId Purchase Request 
	 * @return \Core\Financial\AccountPayable\PurchaseRequestDetail\Model\PurchaseRequestDetailModel
	 */
	public function setPurchaseRequestId($purchaseRequestId)
	{
         $this->purchaseRequestId = $purchaseRequestId;
         return $this;
	} 
	/**
	 * To Return Product 
	 * @return int $productId
	 */ 
	public function getProductId()
	{
	    return $this->productId;
	}
	/**
	 * To Set Product 
	 * @param int $productId Product 
	 * @return \Core\Financial\AccountPayable\PurchaseRequestDetail\Model\PurchaseRequestDetailModel
	 */
	public function setProductId($productId)
	{
         $this->productId = $productId;
         return $this;
	} 
	/**
	 * To Return Product Description 
	 * @return string $purchaseRequestDetailDescription
	 */ 
	public function getPurchaseRequestDetailDescription()
	{
	    return $this->purchaseRequestDetailDescription;
	}
	/**
	 * To Set Product Description 
	 * @param string $purchaseRequestDetailDescription Product Description 
	 * @return \Core\Financial\AccountPayable\PurchaseRequestDetail\Model\PurchaseRequestDetailModel
	 */
	public function setPurchaseRequestDetailDescription($purchaseRequestDetailDescription)
	{
         $this->purchaseRequestDetailDescription = $purchaseRequestDetailDescription;
         return $this;
	} 
	/**
	 * To Return Quantity 
	 * @return double $purchaseRequestDetailQuantity
	 */ 
	public function getPurchaseRequestDetailQuantity()
	{
	    return $this->purchaseRequestDetailQuantity;
	}
	/**
	 * To Set Quantity 
	 * @param double $purchaseRequestDetailQuantity Quantity 
	 * @return \Core\Financial\AccountPayable\PurchaseRequestDetail\Model\PurchaseRequestDetailModel
	 */
	public function setPurchaseRequestDetailQuantity($purchaseRequestDetailQuantity)
	{
         $this->purchaseRequestDetailQuantity = $purchaseRequestDetailQuantity;
         return $this;
	} 
	/**
	 * To Return Unit Measurement 
	 * @return int $unitOfMeasurementId
	 */ 
	public function getUnitOfMeasurementId()
	{
	    return $this->unitOfMeasurementId;
	}
	/**
	 * To Set Unit Measurement 
	 * @param int $unitOfMeasurementId Unit Measurement 
	 * @return \Core\Financial\AccountPayable\PurchaseRequestDetail\Model\PurchaseRequestDetailModel
	 */
	public function setUnitOfMeasurementId($unitOfMeasurementId)
	{
         $this->unitOfMeasurementId = $unitOfMeasurementId;
         return $this;
	} 
	/**
	 * To Return Chart Account 
	 * @return int $chartOfAccountId
	 */ 
	public function getChartOfAccountId()
	{
	    return $this->chartOfAccountId;
	}
	/**
	 * To Set Chart Account 
	 * @param int $chartOfAccountId Chart Account 
	 * @return \Core\Financial\AccountPayable\PurchaseRequestDetail\Model\PurchaseRequestDetailModel
	 */
	public function setChartOfAccountId($chartOfAccountId)
	{
         $this->chartOfAccountId = $chartOfAccountId;
         return $this;
	} 
	/**
	 * To Return Budget 
	 * @return double $purchaseRequestDetailBudget
	 */ 
	public function getPurchaseRequestDetailBudget()
	{
	    return $this->purchaseRequestDetailBudget;
	}
	/**
	 * To Set Budget 
	 * @param double $purchaseRequestDetailBudget Budget 
	 * @return \Core\Financial\AccountPayable\PurchaseRequestDetail\Model\PurchaseRequestDetailModel
	 */
	public function setPurchaseRequestDetailBudget($purchaseRequestDetailBudget)
	{
         $this->purchaseRequestDetailBudget = $purchaseRequestDetailBudget;
         return $this;
	} 
}
?>