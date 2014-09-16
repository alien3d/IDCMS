<?php  namespace Core\Financial\GeneralLedger\TaxCategory\Model;
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
 * Class TaxCategory
 * This is taxCategory model file.This is to ensure strict setting enable for all variable enter to database 
 * 
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\Financial\GeneralLedger\TaxCategory\Model;
 * @subpackage GeneralLedger 
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class TaxCategoryModel extends ValidationClass { 
 /**
  * Primary Key
  * @var int 
  */
  private $taxCategoryId; 
 /**
  * Company
  * @var int 
  */
  private $companyId; 
 /**
  * Code
  * @var string 
  */
  private $taxCategoryCode; 
 /**
  * Description
  * @var string 
  */
  private $taxCategoryDescription; 
 /**
  * Class Loader
  * @see ValidationClass::execute()
  */
 public function execute() {
     /**
     *  Basic Information Table
     **/
     $this->setTableName('taxCategory');
     $this->setPrimaryKeyName('taxCategoryId');
     $this->setMasterForeignKeyName('taxCategoryId');
     $this->setFilterCharacter('taxCategoryDescription');
     //$this->setFilterCharacter('taxCategoryNote');
     $this->setFilterDate('executeTime');
     /**
     * All the $_POST Environment
     */ 
     if (isset($_POST ['taxCategoryId'])) { 
          $this->setTaxCategoryId($this->strict($_POST ['taxCategoryId'], 'int'), 0, 'single'); 
      } 
      if (isset($_POST ['companyId'])) { 
          $this->setCompanyId($this->strict($_POST ['companyId'], 'int')); 
      } 
      if (isset($_POST ['taxCategoryCode'])) { 
          $this->setTaxCategoryCode($this->strict($_POST ['taxCategoryCode'], 'string')); 
      } 
      if (isset($_POST ['taxCategoryDescription'])) { 
          $this->setTaxCategoryDescription($this->strict($_POST ['taxCategoryDescription'], 'string')); 
      } 
      /**
     * All the $_GET Environment
     */
     if (isset($_GET ['taxCategoryId'])) { 
          $this->setTaxCategoryId($this->strict($_GET ['taxCategoryId'], 'int'), 0, 'single'); 
      } 
      if (isset($_GET ['companyId'])) { 
          $this->setCompanyId($this->strict($_GET ['companyId'], 'int')); 
      } 
      if (isset($_GET ['taxCategoryCode'])) { 
          $this->setTaxCategoryCode($this->strict($_GET ['taxCategoryCode'], 'string')); 
      } 
      if (isset($_GET ['taxCategoryDescription'])) { 
          $this->setTaxCategoryDescription($this->strict($_GET ['taxCategoryDescription'], 'string')); 
      } 
      if (isset($_GET ['taxCategoryId'])) {
         $this->setTotal(count($_GET ['taxCategoryId']));
         if (is_array($_GET ['taxCategoryId'])) {
             $this->taxCategoryId = array();
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
         if (isset($_GET ['taxCategoryId'])) {
             $this->setTaxCategoryId($this->strict($_GET ['taxCategoryId'] [$i], 'numeric'), $i, 'array');
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
         $primaryKeyAll .= $this->getTaxCategoryId($i, 'array') . ",";
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
     * @return \Core\Financial\GeneralLedger\TaxCategory\Model\TaxCategoryModel
     */ 
     public function setTaxCategoryId($value, $key, $type) { 
        if ($type == 'single') { 
           $this->taxCategoryId = $value;
           return $this;
        } else if ($type == 'array') {
            $this->taxCategoryId[$key] = $value;
           return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:settaxCategoryId?"));
            exit(); 
        }
    }
    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getTaxCategoryId($key, $type) {
        if ($type == 'single') {
            return $this->taxCategoryId;
        } else if ($type == 'array') {
            return $this->taxCategoryId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:gettaxCategoryId ?"));
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
	 * @return \Core\Financial\GeneralLedger\TaxCategory\Model\TaxCategoryModel
	 */
	public function setCompanyId($companyId)
	{
         $this->companyId = $companyId;
         return $this;
	} 
	/**
	 * To Return Code 
	 * @return string $taxCategoryCode
	 */ 
	public function getTaxCategoryCode()
	{
	    return $this->taxCategoryCode;
	}
	/**
	 * To Set Code 
	 * @param string $taxCategoryCode Code 
	 * @return \Core\Financial\GeneralLedger\TaxCategory\Model\TaxCategoryModel
	 */
	public function setTaxCategoryCode($taxCategoryCode)
	{
         $this->taxCategoryCode = $taxCategoryCode;
         return $this;
	} 
	/**
	 * To Return Description 
	 * @return string $taxCategoryDescription
	 */ 
	public function getTaxCategoryDescription()
	{
	    return $this->taxCategoryDescription;
	}
	/**
	 * To Set Description 
	 * @param string $taxCategoryDescription Description 
	 * @return \Core\Financial\GeneralLedger\TaxCategory\Model\TaxCategoryModel
	 */
	public function setTaxCategoryDescription($taxCategoryDescription)
	{
         $this->taxCategoryDescription = $taxCategoryDescription;
         return $this;
	} 
}
?>