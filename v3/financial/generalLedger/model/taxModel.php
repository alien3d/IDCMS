<?php  namespace Core\Financial\GeneralLedger\Tax\Model;
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
 * Class Tax
 * This is tax model file.This is to ensure strict setting enable for all variable enter to database 
 * 
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\Financial\GeneralLedger\Tax\Model;
 * @subpackage GeneralLedger 
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class TaxModel extends ValidationClass { 
 /**
  * Primary Key
  * @var int 
  */
  private $taxId; 
 /**
  * Company
  * @var int 
  */
  private $companyId; 
 /**
  * Category
  * @var int 
  */
  private $taxCategoryId; 
 /**
  * Type
  * @var int 
  */
  private $taxTypeId; 
 /**
  * Chart Account
  * @var int 
  */
  private $chartOfAccountId; 
 /**
  * Name
  * @var string 
  */
  private $taxName; 
 /**
  * Code
  * @var string 
  */
  private $taxCode; 
 /**
  * Rate
  * @var double 
  */
  private $taxRate; 
 /**
  * Start Date
  * @var date 
  */
  private $taxStartDate; 
 /**
  * End Date
  * @var date 
  */
  private $taxEndDate; 
 /**
  * Description
  * @var string 
  */
  private $taxDescription; 
 /**
  * Class Loader
  * @see ValidationClass::execute()
  */
 public function execute() {
     /**
     *  Basic Information Table
     **/
     $this->setTableName('tax');
     $this->setPrimaryKeyName('taxId');
     $this->setMasterForeignKeyName('taxId');
     $this->setFilterCharacter('taxDescription');
     //$this->setFilterCharacter('taxNote');
     $this->setFilterDate('executeTime');
     /**
     * All the $_POST Environment
     */ 
     if (isset($_POST ['taxId'])) { 
          $this->setTaxId($this->strict($_POST ['taxId'], 'int'), 0, 'single'); 
      } 
      if (isset($_POST ['companyId'])) { 
          $this->setCompanyId($this->strict($_POST ['companyId'], 'int')); 
      } 
      if (isset($_POST ['taxCategoryId'])) { 
          $this->setTaxCategoryId($this->strict($_POST ['taxCategoryId'], 'int')); 
      } 
      if (isset($_POST ['taxTypeId'])) { 
          $this->setTaxTypeId($this->strict($_POST ['taxTypeId'], 'int')); 
      } 
      if (isset($_POST ['chartOfAccountId'])) { 
          $this->setChartOfAccountId($this->strict($_POST ['chartOfAccountId'], 'int')); 
      } 
      if (isset($_POST ['taxName'])) { 
          $this->setTaxName($this->strict($_POST ['taxName'], 'string')); 
      } 
      if (isset($_POST ['taxCode'])) { 
          $this->setTaxCode($this->strict($_POST ['taxCode'], 'string')); 
      } 
      if (isset($_POST ['taxRate'])) { 
          $this->setTaxRate($this->strict($_POST ['taxRate'], 'double')); 
      } 
      if (isset($_POST ['taxStartDate'])) { 
          $this->setTaxStartDate($this->strict($_POST ['taxStartDate'], 'date')); 
      } 
      if (isset($_POST ['taxEndDate'])) { 
          $this->setTaxEndDate($this->strict($_POST ['taxEndDate'], 'date')); 
      } 
      if (isset($_POST ['taxDescription'])) { 
          $this->setTaxDescription($this->strict($_POST ['taxDescription'], 'string')); 
      } 
      /**
     * All the $_GET Environment
     */
     if (isset($_GET ['taxId'])) { 
          $this->setTaxId($this->strict($_GET ['taxId'], 'int'), 0, 'single'); 
      } 
      if (isset($_GET ['companyId'])) { 
          $this->setCompanyId($this->strict($_GET ['companyId'], 'int')); 
      } 
      if (isset($_GET ['taxCategoryId'])) { 
          $this->setTaxCategoryId($this->strict($_GET ['taxCategoryId'], 'int')); 
      } 
      if (isset($_GET ['taxTypeId'])) { 
          $this->setTaxTypeId($this->strict($_GET ['taxTypeId'], 'int')); 
      } 
      if (isset($_GET ['chartOfAccountId'])) { 
          $this->setChartOfAccountId($this->strict($_GET ['chartOfAccountId'], 'int')); 
      } 
      if (isset($_GET ['taxName'])) { 
          $this->setTaxName($this->strict($_GET ['taxName'], 'string')); 
      } 
      if (isset($_GET ['taxCode'])) { 
          $this->setTaxCode($this->strict($_GET ['taxCode'], 'string')); 
      } 
      if (isset($_GET ['taxRate'])) { 
          $this->setTaxRate($this->strict($_GET ['taxRate'], 'double')); 
      } 
      if (isset($_GET ['taxStartDate'])) { 
          $this->setTaxStartDate($this->strict($_GET ['taxStartDate'], 'date')); 
      } 
      if (isset($_GET ['taxEndDate'])) { 
          $this->setTaxEndDate($this->strict($_GET ['taxEndDate'], 'date')); 
      } 
      if (isset($_GET ['taxDescription'])) { 
          $this->setTaxDescription($this->strict($_GET ['taxDescription'], 'string')); 
      } 
      if (isset($_GET ['taxId'])) {
         $this->setTotal(count($_GET ['taxId']));
         if (is_array($_GET ['taxId'])) {
             $this->taxId = array();
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
         if (isset($_GET ['taxId'])) {
             $this->setTaxId($this->strict($_GET ['taxId'] [$i], 'numeric'), $i, 'array');
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
         $primaryKeyAll .= $this->getTaxId($i, 'array') . ",";
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
     * @return \Core\Financial\GeneralLedger\Tax\Model\TaxModel
     */ 
     public function setTaxId($value, $key, $type) { 
        if ($type == 'single') { 
           $this->taxId = $value;
           return $this;
        } else if ($type == 'array') {
            $this->taxId[$key] = $value;
           return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:settaxId?"));
            exit(); 
        }
    }
    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getTaxId($key, $type) {
        if ($type == 'single') {
            return $this->taxId;
        } else if ($type == 'array') {
            return $this->taxId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:gettaxId ?"));
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
	 * @return \Core\Financial\GeneralLedger\Tax\Model\TaxModel
	 */
	public function setCompanyId($companyId)
	{
         $this->companyId = $companyId;
         return $this;
	} 
	/**
	 * To Return Category 
	 * @return int $taxCategoryId
	 */ 
	public function getTaxCategoryId()
	{
	    return $this->taxCategoryId;
	}
	/**
	 * To Set Category 
	 * @param int $taxCategoryId Category 
	 * @return \Core\Financial\GeneralLedger\Tax\Model\TaxModel
	 */
	public function setTaxCategoryId($taxCategoryId)
	{
         $this->taxCategoryId = $taxCategoryId;
         return $this;
	} 
	/**
	 * To Return Type 
	 * @return int $taxTypeId
	 */ 
	public function getTaxTypeId()
	{
	    return $this->taxTypeId;
	}
	/**
	 * To Set Type 
	 * @param int $taxTypeId Type 
	 * @return \Core\Financial\GeneralLedger\Tax\Model\TaxModel
	 */
	public function setTaxTypeId($taxTypeId)
	{
         $this->taxTypeId = $taxTypeId;
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
	 * @return \Core\Financial\GeneralLedger\Tax\Model\TaxModel
	 */
	public function setChartOfAccountId($chartOfAccountId)
	{
         $this->chartOfAccountId = $chartOfAccountId;
         return $this;
	} 
	/**
	 * To Return Name 
	 * @return string $taxName
	 */ 
	public function getTaxName()
	{
	    return $this->taxName;
	}
	/**
	 * To Set Name 
	 * @param string $taxName Name 
	 * @return \Core\Financial\GeneralLedger\Tax\Model\TaxModel
	 */
	public function setTaxName($taxName)
	{
         $this->taxName = $taxName;
         return $this;
	} 
	/**
	 * To Return Code 
	 * @return string $taxCode
	 */ 
	public function getTaxCode()
	{
	    return $this->taxCode;
	}
	/**
	 * To Set Code 
	 * @param string $taxCode Code 
	 * @return \Core\Financial\GeneralLedger\Tax\Model\TaxModel
	 */
	public function setTaxCode($taxCode)
	{
         $this->taxCode = $taxCode;
         return $this;
	} 
	/**
	 * To Return Rate 
	 * @return double $taxRate
	 */ 
	public function getTaxRate()
	{
	    return $this->taxRate;
	}
	/**
	 * To Set Rate 
	 * @param double $taxRate Rate 
	 * @return \Core\Financial\GeneralLedger\Tax\Model\TaxModel
	 */
	public function setTaxRate($taxRate)
	{
         $this->taxRate = $taxRate;
         return $this;
	} 
	/**
	 * To Return Start Date 
	 * @return date $taxStartDate
	 */ 
	public function getTaxStartDate()
	{
	    return $this->taxStartDate;
	}
	/**
	 * To Set Start Date 
	 * @param date $taxStartDate Start Date 
	 * @return \Core\Financial\GeneralLedger\Tax\Model\TaxModel
	 */
	public function setTaxStartDate($taxStartDate)
	{
         $this->taxStartDate = $taxStartDate;
         return $this;
	} 
	/**
	 * To Return End Date 
	 * @return date $taxEndDate
	 */ 
	public function getTaxEndDate()
	{
	    return $this->taxEndDate;
	}
	/**
	 * To Set End Date 
	 * @param date $taxEndDate End Date 
	 * @return \Core\Financial\GeneralLedger\Tax\Model\TaxModel
	 */
	public function setTaxEndDate($taxEndDate)
	{
         $this->taxEndDate = $taxEndDate;
         return $this;
	} 
	/**
	 * To Return Description 
	 * @return string $taxDescription
	 */ 
	public function getTaxDescription()
	{
	    return $this->taxDescription;
	}
	/**
	 * To Set Description 
	 * @param string $taxDescription Description 
	 * @return \Core\Financial\GeneralLedger\Tax\Model\TaxModel
	 */
	public function setTaxDescription($taxDescription)
	{
         $this->taxDescription = $taxDescription;
         return $this;
	} 
}
?>