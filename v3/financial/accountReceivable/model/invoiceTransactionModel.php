<?php  namespace Core\Financial\AccountReceivable\InvoiceTransaction\Model;
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
 * Class InvoiceTransaction
 * This is invoiceTransaction model file.This is to ensure strict setting enable for all variable enter to database 
 * 
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountReceivable\InvoiceTransaction\Model;
 * @subpackage AccountReceivable 
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class InvoiceTransactionModel extends ValidationClass { 
 /**
  * Primary Key
  * @var int 
  */
  private $invoiceTransactionId; 
 /**
  * Company
  * @var int 
  */
  private $companyId; 
 /**
  * Invoice
  * @var int 
  */
  private $invoiceId; 
 /**
  * Chart Account
  * @var int 
  */
  private $chartOfAccountId; 
 /**
  * Journal Number
  * @var string 
  */
  private $journalNumber; 
 /**
  * Start Date
  * @var date 
  */
  private $invoiceTransactionStartDate; 
 /**
  * End Date
  * @var date 
  */
  private $invoiceTransactionEndDate; 
 /**
  * Principal Amount
  * @var double 
  */
  private $invoiceTransactionPrincipalAmount; 
 /**
  * Interest Amount
  * @var double 
  */
  private $invoiceTransactionInterestAmount; 
 /**
  * Coupun Amount
  * @var double 
  */
  private $invoiceTransactionCoupunRateAmount; 
 /**
  * Tax Amount
  * @var double 
  */
  private $invoiceTransactionTaxAmount; 
 /**
  * Amount
  * @var double 
  */
  private $invoiceTransactionAmount; 
 /**
  * Class Loader
  * @see ValidationClass::execute()
  */
 public function execute() {
     /**
     *  Basic Information Table
     **/
     $this->setTableName('invoiceTransaction');
     $this->setPrimaryKeyName('invoiceTransactionId');
     $this->setMasterForeignKeyName('invoiceTransactionId');
     $this->setFilterCharacter('invoiceTransactionDescription');
     //$this->setFilterCharacter('invoiceTransactionNote');
     $this->setFilterDate('executeTime');
     /**
     * All the $_POST Environment
     */ 
     if (isset($_POST ['invoiceTransactionId'])) { 
          $this->setInvoiceTransactionId($this->strict($_POST ['invoiceTransactionId'], 'int'), 0, 'single'); 
      } 
      if (isset($_POST ['companyId'])) { 
          $this->setCompanyId($this->strict($_POST ['companyId'], 'int')); 
      } 
      if (isset($_POST ['invoiceId'])) { 
          $this->setInvoiceId($this->strict($_POST ['invoiceId'], 'int')); 
      } 
      if (isset($_POST ['chartOfAccountId'])) { 
          $this->setChartOfAccountId($this->strict($_POST ['chartOfAccountId'], 'int')); 
      } 
      if (isset($_POST ['journalNumber'])) { 
          $this->setJournalNumber($this->strict($_POST ['journalNumber'], 'string')); 
      } 
      if (isset($_POST ['invoiceTransactionStartDate'])) { 
          $this->setInvoiceTransactionStartDate($this->strict($_POST ['invoiceTransactionStartDate'], 'date')); 
      } 
      if (isset($_POST ['invoiceTransactionEndDate'])) { 
          $this->setInvoiceTransactionEndDate($this->strict($_POST ['invoiceTransactionEndDate'], 'date')); 
      } 
      if (isset($_POST ['invoiceTransactionPrincipalAmount'])) { 
          $this->setInvoiceTransactionPrincipalAmount($this->strict($_POST ['invoiceTransactionPrincipalAmount'], 'double')); 
      } 
      if (isset($_POST ['invoiceTransactionInterestAmount'])) { 
          $this->setInvoiceTransactionInterestAmount($this->strict($_POST ['invoiceTransactionInterestAmount'], 'double')); 
      } 
      if (isset($_POST ['invoiceTransactionCoupunRateAmount'])) { 
          $this->setInvoiceTransactionCoupunRateAmount($this->strict($_POST ['invoiceTransactionCoupunRateAmount'], 'double')); 
      } 
      if (isset($_POST ['invoiceTransactionTaxAmount'])) { 
          $this->setInvoiceTransactionTaxAmount($this->strict($_POST ['invoiceTransactionTaxAmount'], 'double')); 
      } 
      if (isset($_POST ['invoiceTransactionAmount'])) { 
          $this->setInvoiceTransactionAmount($this->strict($_POST ['invoiceTransactionAmount'], 'double')); 
      } 
      /**
     * All the $_GET Environment
     */
     if (isset($_GET ['invoiceTransactionId'])) { 
          $this->setInvoiceTransactionId($this->strict($_GET ['invoiceTransactionId'], 'int'), 0, 'single'); 
      } 
      if (isset($_GET ['companyId'])) { 
          $this->setCompanyId($this->strict($_GET ['companyId'], 'int')); 
      } 
      if (isset($_GET ['invoiceId'])) { 
          $this->setInvoiceId($this->strict($_GET ['invoiceId'], 'int')); 
      } 
      if (isset($_GET ['chartOfAccountId'])) { 
          $this->setChartOfAccountId($this->strict($_GET ['chartOfAccountId'], 'int')); 
      } 
      if (isset($_GET ['journalNumber'])) { 
          $this->setJournalNumber($this->strict($_GET ['journalNumber'], 'string')); 
      } 
      if (isset($_GET ['invoiceTransactionStartDate'])) { 
          $this->setInvoiceTransactionStartDate($this->strict($_GET ['invoiceTransactionStartDate'], 'date')); 
      } 
      if (isset($_GET ['invoiceTransactionEndDate'])) { 
          $this->setInvoiceTransactionEndDate($this->strict($_GET ['invoiceTransactionEndDate'], 'date')); 
      } 
      if (isset($_GET ['invoiceTransactionPrincipalAmount'])) { 
          $this->setInvoiceTransactionPrincipalAmount($this->strict($_GET ['invoiceTransactionPrincipalAmount'], 'double')); 
      } 
      if (isset($_GET ['invoiceTransactionInterestAmount'])) { 
          $this->setInvoiceTransactionInterestAmount($this->strict($_GET ['invoiceTransactionInterestAmount'], 'double')); 
      } 
      if (isset($_GET ['invoiceTransactionCoupunRateAmount'])) { 
          $this->setInvoiceTransactionCoupunRateAmount($this->strict($_GET ['invoiceTransactionCoupunRateAmount'], 'double')); 
      } 
      if (isset($_GET ['invoiceTransactionTaxAmount'])) { 
          $this->setInvoiceTransactionTaxAmount($this->strict($_GET ['invoiceTransactionTaxAmount'], 'double')); 
      } 
      if (isset($_GET ['invoiceTransactionAmount'])) { 
          $this->setInvoiceTransactionAmount($this->strict($_GET ['invoiceTransactionAmount'], 'double')); 
      } 
      if (isset($_GET ['invoiceTransactionId'])) {
         $this->setTotal(count($_GET ['invoiceTransactionId']));
         if (is_array($_GET ['invoiceTransactionId'])) {
             $this->invoiceTransactionId = array();
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
         if (isset($_GET ['invoiceTransactionId'])) {
             $this->setInvoiceTransactionId($this->strict($_GET ['invoiceTransactionId'] [$i], 'numeric'), $i, 'array');
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
         $primaryKeyAll .= $this->getInvoiceTransactionId($i, 'array') . ",";
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
     * @return \Core\Financial\AccountReceivable\InvoiceTransaction\Model\InvoiceTransactionModel
     */ 
     public function setInvoiceTransactionId($value, $key, $type) { 
        if ($type == 'single') { 
           $this->invoiceTransactionId = $value;
           return $this;
        } else if ($type == 'array') {
            $this->invoiceTransactionId[$key] = $value;
           return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:setinvoiceTransactionId?"));
            exit(); 
        }
    }
    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getInvoiceTransactionId($key, $type) {
        if ($type == 'single') {
            return $this->invoiceTransactionId;
        } else if ($type == 'array') {
            return $this->invoiceTransactionId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:getinvoiceTransactionId ?"));
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
	 * @return \Core\Financial\AccountReceivable\InvoiceTransaction\Model\InvoiceTransactionModel
	 */
	public function setCompanyId($companyId)
	{
         $this->companyId = $companyId;
         return $this;
	} 
	/**
	 * To Return Invoice 
	 * @return int $invoiceId
	 */ 
	public function getInvoiceId()
	{
	    return $this->invoiceId;
	}
	/**
	 * To Set Invoice 
	 * @param int $invoiceId Invoice 
	 * @return \Core\Financial\AccountReceivable\InvoiceTransaction\Model\InvoiceTransactionModel
	 */
	public function setInvoiceId($invoiceId)
	{
         $this->invoiceId = $invoiceId;
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
	 * @return \Core\Financial\AccountReceivable\InvoiceTransaction\Model\InvoiceTransactionModel
	 */
	public function setChartOfAccountId($chartOfAccountId)
	{
         $this->chartOfAccountId = $chartOfAccountId;
         return $this;
	} 
	/**
	 * To Return Journal Number 
	 * @return string $journalNumber
	 */ 
	public function getJournalNumber()
	{
	    return $this->journalNumber;
	}
	/**
	 * To Set Journal Number 
	 * @param string $journalNumber Journal Number 
	 * @return \Core\Financial\AccountReceivable\InvoiceTransaction\Model\InvoiceTransactionModel
	 */
	public function setJournalNumber($journalNumber)
	{
         $this->journalNumber = $journalNumber;
         return $this;
	} 
	/**
	 * To Return Start Date 
	 * @return date $invoiceTransactionStartDate
	 */ 
	public function getInvoiceTransactionStartDate()
	{
	    return $this->invoiceTransactionStartDate;
	}
	/**
	 * To Set Start Date 
	 * @param date $invoiceTransactionStartDate Start Date 
	 * @return \Core\Financial\AccountReceivable\InvoiceTransaction\Model\InvoiceTransactionModel
	 */
	public function setInvoiceTransactionStartDate($invoiceTransactionStartDate)
	{
         $this->invoiceTransactionStartDate = $invoiceTransactionStartDate;
         return $this;
	} 
	/**
	 * To Return End Date 
	 * @return date $invoiceTransactionEndDate
	 */ 
	public function getInvoiceTransactionEndDate()
	{
	    return $this->invoiceTransactionEndDate;
	}
	/**
	 * To Set End Date 
	 * @param date $invoiceTransactionEndDate End Date 
	 * @return \Core\Financial\AccountReceivable\InvoiceTransaction\Model\InvoiceTransactionModel
	 */
	public function setInvoiceTransactionEndDate($invoiceTransactionEndDate)
	{
         $this->invoiceTransactionEndDate = $invoiceTransactionEndDate;
         return $this;
	} 
	/**
	 * To Return Principal Amount 
	 * @return double $invoiceTransactionPrincipalAmount
	 */ 
	public function getInvoiceTransactionPrincipalAmount()
	{
	    return $this->invoiceTransactionPrincipalAmount;
	}
	/**
	 * To Set Principal Amount 
	 * @param double $invoiceTransactionPrincipalAmount Principal Amount 
	 * @return \Core\Financial\AccountReceivable\InvoiceTransaction\Model\InvoiceTransactionModel
	 */
	public function setInvoiceTransactionPrincipalAmount($invoiceTransactionPrincipalAmount)
	{
         $this->invoiceTransactionPrincipalAmount = $invoiceTransactionPrincipalAmount;
         return $this;
	} 
	/**
	 * To Return Interest Amount 
	 * @return double $invoiceTransactionInterestAmount
	 */ 
	public function getInvoiceTransactionInterestAmount()
	{
	    return $this->invoiceTransactionInterestAmount;
	}
	/**
	 * To Set Interest Amount 
	 * @param double $invoiceTransactionInterestAmount Interest Amount 
	 * @return \Core\Financial\AccountReceivable\InvoiceTransaction\Model\InvoiceTransactionModel
	 */
	public function setInvoiceTransactionInterestAmount($invoiceTransactionInterestAmount)
	{
         $this->invoiceTransactionInterestAmount = $invoiceTransactionInterestAmount;
         return $this;
	} 
	/**
	 * To Return Coupun Amount 
	 * @return double $invoiceTransactionCoupunRateAmount
	 */ 
	public function getInvoiceTransactionCoupunRateAmount()
	{
	    return $this->invoiceTransactionCoupunRateAmount;
	}
	/**
	 * To Set Coupun Amount 
	 * @param double $invoiceTransactionCoupunRateAmount Coupun Amount 
	 * @return \Core\Financial\AccountReceivable\InvoiceTransaction\Model\InvoiceTransactionModel
	 */
	public function setInvoiceTransactionCoupunRateAmount($invoiceTransactionCoupunRateAmount)
	{
         $this->invoiceTransactionCoupunRateAmount = $invoiceTransactionCoupunRateAmount;
         return $this;
	} 
	/**
	 * To Return Tax Amount 
	 * @return double $invoiceTransactionTaxAmount
	 */ 
	public function getInvoiceTransactionTaxAmount()
	{
	    return $this->invoiceTransactionTaxAmount;
	}
	/**
	 * To Set Tax Amount 
	 * @param double $invoiceTransactionTaxAmount Tax Amount 
	 * @return \Core\Financial\AccountReceivable\InvoiceTransaction\Model\InvoiceTransactionModel
	 */
	public function setInvoiceTransactionTaxAmount($invoiceTransactionTaxAmount)
	{
         $this->invoiceTransactionTaxAmount = $invoiceTransactionTaxAmount;
         return $this;
	} 
	/**
	 * To Return Amount 
	 * @return double $invoiceTransactionAmount
	 */ 
	public function getInvoiceTransactionAmount()
	{
	    return $this->invoiceTransactionAmount;
	}
	/**
	 * To Set Amount 
	 * @param double $invoiceTransactionAmount Amount 
	 * @return \Core\Financial\AccountReceivable\InvoiceTransaction\Model\InvoiceTransactionModel
	 */
	public function setInvoiceTransactionAmount($invoiceTransactionAmount)
	{
         $this->invoiceTransactionAmount = $invoiceTransactionAmount;
         return $this;
	} 
}
?>