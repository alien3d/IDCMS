<?php  namespace Core\Financial\AccountReceivable\InvoiceLedger\Model;
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
 * Class InvoiceLedger
 * This is invoiceLedger model file.This is to ensure strict setting enable for all variable enter to database 
 * 
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountReceivable\InvoiceLedger\Model;
 * @subpackage AccountReceivable 
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class InvoiceLedgerModel extends ValidationClass { 
 /**
  * Primary Key
  * @var int 
  */
  private $invoiceLedgerId; 
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
  * Chart Account
  * @var int 
  */
  private $chartOfAccountId; 
 /**
  * Invoice Project
  * @var int 
  */
  private $invoiceProjectId; 
 /**
  * Invoice
  * @var int 
  */
  private $invoiceId; 
 /**
  * Invoice Note
  * @var int 
  */
  private $invoiceDebitNoteId; 
 /**
  * Invoice Note
  * @var int 
  */
  private $invoiceCreditNoteId; 
 /**
  * Collection
  * @var int 
  */
  private $collectionId; 
 /**
  * Document Number
  * @var string 
  */
  private $documentNumber; 
 /**
  * Date
  * @var date 
  */
  private $invoiceLedgerDate; 
 /**
  * Invoice Date
  * @var date 
  */
  private $invoiceDueDate; 
 /**
  * Amount
  * @var int 
  */
  private $invoiceLedgerAmount; 
 /**
  * Description
  * @var string 
  */
  private $invoiceLedgerDescription; 
 /**
  * Leaf
  * @var int 
  */
  private $leafId; 
 /**
  * Leaf Name
  * @var string 
  */
  private $leafName; 
 /**
  * Class Loader
  * @see ValidationClass::execute()
  */
 public function execute() {
     /**
     *  Basic Information Table
     **/
     $this->setTableName('invoiceLedger');
     $this->setPrimaryKeyName('invoiceLedgerId');
     $this->setMasterForeignKeyName('invoiceLedgerId');
     $this->setFilterCharacter('invoiceLedgerDescription');
     //$this->setFilterCharacter('invoiceLedgerNote');
     $this->setFilterDate('invoiceLedgerDate');
     /**
     * All the $_POST Environment
     */ 
     if (isset($_POST ['invoiceLedgerId'])) { 
          $this->setInvoiceLedgerId($this->strict($_POST ['invoiceLedgerId'], 'int'), 0, 'single'); 
      } 
      if (isset($_POST ['companyId'])) { 
          $this->setCompanyId($this->strict($_POST ['companyId'], 'int')); 
      } 
      if (isset($_POST ['businessPartnerId'])) { 
          $this->setBusinessPartnerId($this->strict($_POST ['businessPartnerId'], 'int')); 
      } 
      if (isset($_POST ['chartOfAccountId'])) { 
          $this->setChartOfAccountId($this->strict($_POST ['chartOfAccountId'], 'int')); 
      } 
      if (isset($_POST ['invoiceProjectId'])) { 
          $this->setInvoiceProjectId($this->strict($_POST ['invoiceProjectId'], 'int')); 
      } 
      if (isset($_POST ['invoiceId'])) { 
          $this->setInvoiceId($this->strict($_POST ['invoiceId'], 'int')); 
      } 
      if (isset($_POST ['invoiceDebitNoteId'])) { 
          $this->setInvoiceDebitNoteId($this->strict($_POST ['invoiceDebitNoteId'], 'int')); 
      } 
      if (isset($_POST ['invoiceCreditNoteId'])) { 
          $this->setInvoiceCreditNoteId($this->strict($_POST ['invoiceCreditNoteId'], 'int')); 
      } 
      if (isset($_POST ['collectionId'])) { 
          $this->setCollectionId($this->strict($_POST ['collectionId'], 'int')); 
      } 
      if (isset($_POST ['documentNumber'])) { 
          $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string')); 
      } 
      if (isset($_POST ['invoiceLedgerDate'])) { 
          $this->setInvoiceLedgerDate($this->strict($_POST ['invoiceLedgerDate'], 'date')); 
      } 
      if (isset($_POST ['invoiceDueDate'])) { 
          $this->setInvoiceDueDate($this->strict($_POST ['invoiceDueDate'], 'date')); 
      } 
      if (isset($_POST ['invoiceLedgerAmount'])) { 
          $this->setInvoiceLedgerAmount($this->strict($_POST ['invoiceLedgerAmount'], 'int')); 
      } 
      if (isset($_POST ['invoiceLedgerDescription'])) { 
          $this->setInvoiceLedgerDescription($this->strict($_POST ['invoiceLedgerDescription'], 'string')); 
      } 
      if (isset($_POST ['leafId'])) { 
          $this->setLeafId($this->strict($_POST ['leafId'], 'int')); 
      } 
      if (isset($_POST ['leafName'])) { 
          $this->setLeafName($this->strict($_POST ['leafName'], 'string')); 
      } 
      /**
     * All the $_GET Environment
     */
     if (isset($_GET ['invoiceLedgerId'])) { 
          $this->setInvoiceLedgerId($this->strict($_GET ['invoiceLedgerId'], 'int'), 0, 'single'); 
      } 
      if (isset($_GET ['companyId'])) { 
          $this->setCompanyId($this->strict($_GET ['companyId'], 'int')); 
      } 
      if (isset($_GET ['businessPartnerId'])) { 
          $this->setBusinessPartnerId($this->strict($_GET ['businessPartnerId'], 'int')); 
      } 
      if (isset($_GET ['chartOfAccountId'])) { 
          $this->setChartOfAccountId($this->strict($_GET ['chartOfAccountId'], 'int')); 
      } 
      if (isset($_GET ['invoiceProjectId'])) { 
          $this->setInvoiceProjectId($this->strict($_GET ['invoiceProjectId'], 'int')); 
      } 
      if (isset($_GET ['invoiceId'])) { 
          $this->setInvoiceId($this->strict($_GET ['invoiceId'], 'int')); 
      } 
      if (isset($_GET ['invoiceDebitNoteId'])) { 
          $this->setInvoiceDebitNoteId($this->strict($_GET ['invoiceDebitNoteId'], 'int')); 
      } 
      if (isset($_GET ['invoiceCreditNoteId'])) { 
          $this->setInvoiceCreditNoteId($this->strict($_GET ['invoiceCreditNoteId'], 'int')); 
      } 
      if (isset($_GET ['collectionId'])) { 
          $this->setCollectionId($this->strict($_GET ['collectionId'], 'int')); 
      } 
      if (isset($_GET ['documentNumber'])) { 
          $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string')); 
      } 
      if (isset($_GET ['invoiceLedgerDate'])) { 
          $this->setInvoiceLedgerDate($this->strict($_GET ['invoiceLedgerDate'], 'date')); 
      } 
      if (isset($_GET ['invoiceDueDate'])) { 
          $this->setInvoiceDueDate($this->strict($_GET ['invoiceDueDate'], 'date')); 
      } 
      if (isset($_GET ['invoiceLedgerAmount'])) { 
          $this->setInvoiceLedgerAmount($this->strict($_GET ['invoiceLedgerAmount'], 'int')); 
      } 
      if (isset($_GET ['invoiceLedgerDescription'])) { 
          $this->setInvoiceLedgerDescription($this->strict($_GET ['invoiceLedgerDescription'], 'string')); 
      } 
      if (isset($_GET ['leafId'])) { 
          $this->setLeafId($this->strict($_GET ['leafId'], 'int')); 
      } 
      if (isset($_GET ['leafName'])) { 
          $this->setLeafName($this->strict($_GET ['leafName'], 'string')); 
      } 
      if (isset($_GET ['invoiceLedgerId'])) {
         $this->setTotal(count($_GET ['invoiceLedgerId']));
         if (is_array($_GET ['invoiceLedgerId'])) {
             $this->invoiceLedgerId = array();
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
         if (isset($_GET ['invoiceLedgerId'])) {
             $this->setInvoiceLedgerId($this->strict($_GET ['invoiceLedgerId'] [$i], 'numeric'), $i, 'array');
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
         $primaryKeyAll .= $this->getInvoiceLedgerId($i, 'array') . ",";
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
     * @return \Core\Financial\AccountReceivable\InvoiceLedger\Model\InvoiceLedgerModel
     */ 
     public function setInvoiceLedgerId($value, $key, $type) { 
        if ($type == 'single') { 
           $this->invoiceLedgerId = $value;
           return $this;
        } else if ($type == 'array') {
            $this->invoiceLedgerId[$key] = $value;
           return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:setinvoiceLedgerId?"));
            exit(); 
        }
    }
    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getInvoiceLedgerId($key, $type) {
        if ($type == 'single') {
            return $this->invoiceLedgerId;
        } else if ($type == 'array') {
            return $this->invoiceLedgerId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:getinvoiceLedgerId ?"));
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
	 * @return \Core\Financial\AccountReceivable\InvoiceLedger\Model\InvoiceLedgerModel
	 */
	public function setCompanyId($companyId)
	{
         $this->companyId = $companyId;
         return $this;
	} 
	/**
	 * To Return Business Partner 
	 * @return int $businessPartnerId
	 */ 
	public function getBusinessPartnerId()
	{
	    return $this->businessPartnerId;
	}
	/**
	 * To Set Business Partner 
	 * @param int $businessPartnerId Business Partner 
	 * @return \Core\Financial\AccountReceivable\InvoiceLedger\Model\InvoiceLedgerModel
	 */
	public function setBusinessPartnerId($businessPartnerId)
	{
         $this->businessPartnerId = $businessPartnerId;
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
	 * @return \Core\Financial\AccountReceivable\InvoiceLedger\Model\InvoiceLedgerModel
	 */
	public function setChartOfAccountId($chartOfAccountId)
	{
         $this->chartOfAccountId = $chartOfAccountId;
         return $this;
	} 
	/**
	 * To Return Invoice Project 
	 * @return int $invoiceProjectId
	 */ 
	public function getInvoiceProjectId()
	{
	    return $this->invoiceProjectId;
	}
	/**
	 * To Set Invoice Project 
	 * @param int $invoiceProjectId Invoice Project 
	 * @return \Core\Financial\AccountReceivable\InvoiceLedger\Model\InvoiceLedgerModel
	 */
	public function setInvoiceProjectId($invoiceProjectId)
	{
         $this->invoiceProjectId = $invoiceProjectId;
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
	 * @return \Core\Financial\AccountReceivable\InvoiceLedger\Model\InvoiceLedgerModel
	 */
	public function setInvoiceId($invoiceId)
	{
         $this->invoiceId = $invoiceId;
         return $this;
	} 
	/**
	 * To Return Invoice Note 
	 * @return int $invoiceDebitNoteId
	 */ 
	public function getInvoiceDebitNoteId()
	{
	    return $this->invoiceDebitNoteId;
	}
	/**
	 * To Set Invoice Note 
	 * @param int $invoiceDebitNoteId Invoice Note 
	 * @return \Core\Financial\AccountReceivable\InvoiceLedger\Model\InvoiceLedgerModel
	 */
	public function setInvoiceDebitNoteId($invoiceDebitNoteId)
	{
         $this->invoiceDebitNoteId = $invoiceDebitNoteId;
         return $this;
	} 
	/**
	 * To Return Invoice Note 
	 * @return int $invoiceCreditNoteId
	 */ 
	public function getInvoiceCreditNoteId()
	{
	    return $this->invoiceCreditNoteId;
	}
	/**
	 * To Set Invoice Note 
	 * @param int $invoiceCreditNoteId Invoice Note 
	 * @return \Core\Financial\AccountReceivable\InvoiceLedger\Model\InvoiceLedgerModel
	 */
	public function setInvoiceCreditNoteId($invoiceCreditNoteId)
	{
         $this->invoiceCreditNoteId = $invoiceCreditNoteId;
         return $this;
	} 
	/**
	 * To Return Collection 
	 * @return int $collectionId
	 */ 
	public function getCollectionId()
	{
	    return $this->collectionId;
	}
	/**
	 * To Set Collection 
	 * @param int $collectionId Collection 
	 * @return \Core\Financial\AccountReceivable\InvoiceLedger\Model\InvoiceLedgerModel
	 */
	public function setCollectionId($collectionId)
	{
         $this->collectionId = $collectionId;
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
	 * @return \Core\Financial\AccountReceivable\InvoiceLedger\Model\InvoiceLedgerModel
	 */
	public function setDocumentNumber($documentNumber)
	{
         $this->documentNumber = $documentNumber;
         return $this;
	} 
	/**
	 * To Return Date 
	 * @return date $invoiceLedgerDate
	 */ 
	public function getInvoiceLedgerDate()
	{
	    return $this->invoiceLedgerDate;
	}
	/**
	 * To Set Date 
	 * @param date $invoiceLedgerDate Date 
	 * @return \Core\Financial\AccountReceivable\InvoiceLedger\Model\InvoiceLedgerModel
	 */
	public function setInvoiceLedgerDate($invoiceLedgerDate)
	{
         $this->invoiceLedgerDate = $invoiceLedgerDate;
         return $this;
	} 
	/**
	 * To Return Invoice Date 
	 * @return date $invoiceDueDate
	 */ 
	public function getInvoiceDueDate()
	{
	    return $this->invoiceDueDate;
	}
	/**
	 * To Set Invoice Date 
	 * @param date $invoiceDueDate Invoice Date 
	 * @return \Core\Financial\AccountReceivable\InvoiceLedger\Model\InvoiceLedgerModel
	 */
	public function setInvoiceDueDate($invoiceDueDate)
	{
         $this->invoiceDueDate = $invoiceDueDate;
         return $this;
	} 
	/**
	 * To Return Amount 
	 * @return int $invoiceLedgerAmount
	 */ 
	public function getInvoiceLedgerAmount()
	{
	    return $this->invoiceLedgerAmount;
	}
	/**
	 * To Set Amount 
	 * @param int $invoiceLedgerAmount Amount 
	 * @return \Core\Financial\AccountReceivable\InvoiceLedger\Model\InvoiceLedgerModel
	 */
	public function setInvoiceLedgerAmount($invoiceLedgerAmount)
	{
         $this->invoiceLedgerAmount = $invoiceLedgerAmount;
         return $this;
	} 
	/**
	 * To Return Description 
	 * @return string $invoiceLedgerDescription
	 */ 
	public function getInvoiceLedgerDescription()
	{
	    return $this->invoiceLedgerDescription;
	}
	/**
	 * To Set Description 
	 * @param string $invoiceLedgerDescription Description 
	 * @return \Core\Financial\AccountReceivable\InvoiceLedger\Model\InvoiceLedgerModel
	 */
	public function setInvoiceLedgerDescription($invoiceLedgerDescription)
	{
         $this->invoiceLedgerDescription = $invoiceLedgerDescription;
         return $this;
	} 
	/**
	 * To Return Leaf 
	 * @return int $leafId
	 */ 
	public function getLeafId()
	{
	    return $this->leafId;
	}
	/**
	 * To Set Leaf 
	 * @param int $leafId Leaf 
	 * @return \Core\Financial\AccountReceivable\InvoiceLedger\Model\InvoiceLedgerModel
	 */
	public function setLeafId($leafId)
	{
         $this->leafId = $leafId;
         return $this;
	} 
	/**
	 * To Return Leaf Name 
	 * @return string $leafName
	 */ 
	public function getLeafName()
	{
	    return $this->leafName;
	}
	/**
	 * To Set Leaf Name 
	 * @param string $leafName Leaf Name 
	 * @return \Core\Financial\AccountReceivable\InvoiceLedger\Model\InvoiceLedgerModel
	 */
	public function setLeafName($leafName)
	{
         $this->leafName = $leafName;
         return $this;
	} 
}
?>