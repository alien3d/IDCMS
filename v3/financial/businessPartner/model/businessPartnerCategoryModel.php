<?php  namespace Core\Financial\BusinessPartner\BusinessPartnerCategory\Model;
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
 * Class BusinessPartnerCategory
 * This is businessPartnerCategory model file.This is to ensure strict setting enable for all variable enter to database 
 * 
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\Financial\BusinessPartner\BusinessPartnerCategory\Model;
 * @subpackage BusinessPartner 
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class BusinessPartnerCategoryModel extends ValidationClass { 
 /**
  * Primary Key
  * @var int 
  */
  private $businessPartnerCategoryId; 
 /**
  * Company
  * @var int 
  */
  private $companyId; 
 /**
  * Code
  * @var string 
  */
  private $businessPartnerCategoryCode; 
 /**
  * Description
  * @var string 
  */
  private $businessPartnerCategoryDescription; 
 /**
  * Is Creditor
  * @var bool 
  */
  private $isCreditor; 
 /**
  * Is Debtor
  * @var bool 
  */
  private $isDebtor; 
 /**
  * Is Global
  * @var bool 
  */
  private $isGlobal; 
 /**
  * Class Loader
  * @see ValidationClass::execute()
  */
 public function execute() {
     /**
     *  Basic Information Table
     **/
     $this->setTableName('businessPartnerCategory');
     $this->setPrimaryKeyName('businessPartnerCategoryId');
     $this->setMasterForeignKeyName('businessPartnerCategoryId');
     $this->setFilterCharacter('businessPartnerCategoryDescription');
     //$this->setFilterCharacter('businessPartnerCategoryNote');
     $this->setFilterDate('executeTime');
     /**
     * All the $_POST Environment
     */ 
     if (isset($_POST ['businessPartnerCategoryId'])) { 
          $this->setBusinessPartnerCategoryId($this->strict($_POST ['businessPartnerCategoryId'], 'int'), 0, 'single'); 
      } 
      if (isset($_POST ['companyId'])) { 
          $this->setCompanyId($this->strict($_POST ['companyId'], 'int')); 
      } 
      if (isset($_POST ['businessPartnerCategoryCode'])) { 
          $this->setBusinessPartnerCategoryCode($this->strict($_POST ['businessPartnerCategoryCode'], 'string')); 
      } 
      if (isset($_POST ['businessPartnerCategoryDescription'])) { 
          $this->setBusinessPartnerCategoryDescription($this->strict($_POST ['businessPartnerCategoryDescription'], 'string')); 
      } 
      if (isset($_POST ['isCreditor'])) { 
          $this->setIsCreditor($this->strict($_POST ['isCreditor'], 'bool')); 
      } 
      if (isset($_POST ['isDebtor'])) { 
          $this->setIsDebtor($this->strict($_POST ['isDebtor'], 'bool')); 
      } 
      if (isset($_POST ['isGlobal'])) { 
          $this->setIsGlobal($this->strict($_POST ['isGlobal'], 'bool')); 
      } 
      /**
     * All the $_GET Environment
     */
     if (isset($_GET ['businessPartnerCategoryId'])) { 
          $this->setBusinessPartnerCategoryId($this->strict($_GET ['businessPartnerCategoryId'], 'int'), 0, 'single'); 
      } 
      if (isset($_GET ['companyId'])) { 
          $this->setCompanyId($this->strict($_GET ['companyId'], 'int')); 
      } 
      if (isset($_GET ['businessPartnerCategoryCode'])) { 
          $this->setBusinessPartnerCategoryCode($this->strict($_GET ['businessPartnerCategoryCode'], 'string')); 
      } 
      if (isset($_GET ['businessPartnerCategoryDescription'])) { 
          $this->setBusinessPartnerCategoryDescription($this->strict($_GET ['businessPartnerCategoryDescription'], 'string')); 
      } 
      if (isset($_GET ['isCreditor'])) { 
          $this->setIsCreditor($this->strict($_GET ['isCreditor'], 'bool')); 
      } 
      if (isset($_GET ['isDebtor'])) { 
          $this->setIsDebtor($this->strict($_GET ['isDebtor'], 'bool')); 
      } 
      if (isset($_GET ['isGlobal'])) { 
          $this->setIsGlobal($this->strict($_GET ['isGlobal'], 'bool')); 
      } 
      if (isset($_GET ['businessPartnerCategoryId'])) {
         $this->setTotal(count($_GET ['businessPartnerCategoryId']));
         if (is_array($_GET ['businessPartnerCategoryId'])) {
             $this->businessPartnerCategoryId = array();
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
         if (isset($_GET ['businessPartnerCategoryId'])) {
             $this->setBusinessPartnerCategoryId($this->strict($_GET ['businessPartnerCategoryId'] [$i], 'numeric'), $i, 'array');
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
         $primaryKeyAll .= $this->getBusinessPartnerCategoryId($i, 'array') . ",";
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
     * @return \Core\Financial\BusinessPartner\BusinessPartnerCategory\Model\BusinessPartnerCategoryModel
     */ 
     public function setBusinessPartnerCategoryId($value, $key, $type) { 
        if ($type == 'single') { 
           $this->businessPartnerCategoryId = $value;
           return $this;
        } else if ($type == 'array') {
            $this->businessPartnerCategoryId[$key] = $value;
           return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:setbusinessPartnerCategoryId?"));
            exit(); 
        }
    }
    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getBusinessPartnerCategoryId($key, $type) {
        if ($type == 'single') {
            return $this->businessPartnerCategoryId;
        } else if ($type == 'array') {
            return $this->businessPartnerCategoryId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:getbusinessPartnerCategoryId ?"));
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
	 * @return \Core\Financial\BusinessPartner\BusinessPartnerCategory\Model\BusinessPartnerCategoryModel
	 */
	public function setCompanyId($companyId)
	{
         $this->companyId = $companyId;
         return $this;
	} 
	/**
	 * To Return Code 
	 * @return string $businessPartnerCategoryCode
	 */ 
	public function getBusinessPartnerCategoryCode()
	{
	    return $this->businessPartnerCategoryCode;
	}
	/**
	 * To Set Code 
	 * @param string $businessPartnerCategoryCode Code 
	 * @return \Core\Financial\BusinessPartner\BusinessPartnerCategory\Model\BusinessPartnerCategoryModel
	 */
	public function setBusinessPartnerCategoryCode($businessPartnerCategoryCode)
	{
         $this->businessPartnerCategoryCode = $businessPartnerCategoryCode;
         return $this;
	} 
	/**
	 * To Return Description 
	 * @return string $businessPartnerCategoryDescription
	 */ 
	public function getBusinessPartnerCategoryDescription()
	{
	    return $this->businessPartnerCategoryDescription;
	}
	/**
	 * To Set Description 
	 * @param string $businessPartnerCategoryDescription Description 
	 * @return \Core\Financial\BusinessPartner\BusinessPartnerCategory\Model\BusinessPartnerCategoryModel
	 */
	public function setBusinessPartnerCategoryDescription($businessPartnerCategoryDescription)
	{
         $this->businessPartnerCategoryDescription = $businessPartnerCategoryDescription;
         return $this;
	} 
	/**
	 * To Return Is Creditor 
	 * @return bool $isCreditor
	 */ 
	public function getIsCreditor()
	{
	    return $this->isCreditor;
	}
	/**
	 * To Set Is Creditor 
	 * @param bool $isCreditor Is Creditor 
	 * @return \Core\Financial\BusinessPartner\BusinessPartnerCategory\Model\BusinessPartnerCategoryModel
	 */
	public function setIsCreditor($isCreditor)
	{
         $this->isCreditor = $isCreditor;
         return $this;
	} 
	/**
	 * To Return Is Debtor 
	 * @return bool $isDebtor
	 */ 
	public function getIsDebtor()
	{
	    return $this->isDebtor;
	}
	/**
	 * To Set Is Debtor 
	 * @param bool $isDebtor Is Debtor 
	 * @return \Core\Financial\BusinessPartner\BusinessPartnerCategory\Model\BusinessPartnerCategoryModel
	 */
	public function setIsDebtor($isDebtor)
	{
         $this->isDebtor = $isDebtor;
         return $this;
	} 
	/**
	 * To Return Is Global 
	 * @return bool $isGlobal
	 */ 
	public function getIsGlobal()
	{
	    return $this->isGlobal;
	}
	/**
	 * To Set Is Global 
	 * @param bool $isGlobal Is Global 
	 * @return \Core\Financial\BusinessPartner\BusinessPartnerCategory\Model\BusinessPartnerCategoryModel
	 */
	public function setIsGlobal($isGlobal)
	{
         $this->isGlobal = $isGlobal;
         return $this;
	} 
}
?>