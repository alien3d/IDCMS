<?php  namespace Core\Financial\Cashbook\CashBookLedger\Model;
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
 * Class CashBookLedger
 * This is cashBookLedger model file.This is to ensure strict setting enable for all variable enter to database 
 * 
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\Financial\Cashbook\CashBookLedger\Model;
 * @subpackage Cashbook 
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class CashBookLedgerModel extends ValidationClass { 
 /**
  * Primary Key
  * @var int 
  */
  private $cashBookLedgerId; 
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
  * Bank
  * @var int 
  */
  private $bankId; 
 /**
  * Chart Account
  * @var int 
  */
  private $chartOfAccountId; 
 /**
  * Collection
  * @var int 
  */
  private $collectionId; 
 /**
  * Payment Voucher
  * @var int 
  */
  private $paymentVoucherId; 
 /**
  * Bank Transfer
  * @var int 
  */
  private $bankTransferId; 
 /**
  * Document Number
  * @var string 
  */
  private $documentNumber; 
 /**
  * Cash Date
  * @var time 
  */
  private $cashBookDate; 
 /**
  * Cash Amount
  * @var int 
  */
  private $cashBookAmount; 
 /**
  * Cash Description
  * @var string 
  */
  private $cashBookDescription; 
 /**
  * Leaf
  * @var int 
  */
  private $leafId; 
 /**
  * Class Loader
  * @see ValidationClass::execute()
  */
 public function execute() {
     /**
     *  Basic Information Table
     **/
     $this->setTableName('cashBookLedger');
     $this->setPrimaryKeyName('cashBookLedgerId');
     $this->setMasterForeignKeyName('cashBookLedgerId');
     $this->setFilterCharacter('cashBookLedgerDescription');
     //$this->setFilterCharacter('cashBookLedgerNote');
     $this->setFilterDate('executeTime');
     /**
     * All the $_POST Environment
     */ 
     if (isset($_POST ['cashBookLedgerId'])) { 
          $this->setCashBookLedgerId($this->strict($_POST ['cashBookLedgerId'], 'int'), 0, 'single'); 
      } 
      if (isset($_POST ['companyId'])) { 
          $this->setCompanyId($this->strict($_POST ['companyId'], 'int')); 
      } 
      if (isset($_POST ['businessPartnerId'])) { 
          $this->setBusinessPartnerId($this->strict($_POST ['businessPartnerId'], 'int')); 
      } 
      if (isset($_POST ['bankId'])) { 
          $this->setBankId($this->strict($_POST ['bankId'], 'int')); 
      } 
      if (isset($_POST ['chartOfAccountId'])) { 
          $this->setChartOfAccountId($this->strict($_POST ['chartOfAccountId'], 'int')); 
      } 
      if (isset($_POST ['collectionId'])) { 
          $this->setCollectionId($this->strict($_POST ['collectionId'], 'int')); 
      } 
      if (isset($_POST ['paymentVoucherId'])) { 
          $this->setPaymentVoucherId($this->strict($_POST ['paymentVoucherId'], 'int')); 
      } 
      if (isset($_POST ['bankTransferId'])) { 
          $this->setBankTransferId($this->strict($_POST ['bankTransferId'], 'int')); 
      } 
      if (isset($_POST ['documentNumber'])) { 
          $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string')); 
      } 
      if (isset($_POST ['cashBookDate'])) { 
          $this->setCashBookDate($this->strict($_POST ['cashBookDate'], 'time')); 
      } 
      if (isset($_POST ['cashBookAmount'])) { 
          $this->setCashBookAmount($this->strict($_POST ['cashBookAmount'], 'int')); 
      } 
      if (isset($_POST ['cashBookDescription'])) { 
          $this->setCashBookDescription($this->strict($_POST ['cashBookDescription'], 'string')); 
      } 
      if (isset($_POST ['leafId'])) { 
          $this->setLeafId($this->strict($_POST ['leafId'], 'int')); 
      } 
      /**
     * All the $_GET Environment
     */
     if (isset($_GET ['cashBookLedgerId'])) { 
          $this->setCashBookLedgerId($this->strict($_GET ['cashBookLedgerId'], 'int'), 0, 'single'); 
      } 
      if (isset($_GET ['companyId'])) { 
          $this->setCompanyId($this->strict($_GET ['companyId'], 'int')); 
      } 
      if (isset($_GET ['businessPartnerId'])) { 
          $this->setBusinessPartnerId($this->strict($_GET ['businessPartnerId'], 'int')); 
      } 
      if (isset($_GET ['bankId'])) { 
          $this->setBankId($this->strict($_GET ['bankId'], 'int')); 
      } 
      if (isset($_GET ['chartOfAccountId'])) { 
          $this->setChartOfAccountId($this->strict($_GET ['chartOfAccountId'], 'int')); 
      } 
      if (isset($_GET ['collectionId'])) { 
          $this->setCollectionId($this->strict($_GET ['collectionId'], 'int')); 
      } 
      if (isset($_GET ['paymentVoucherId'])) { 
          $this->setPaymentVoucherId($this->strict($_GET ['paymentVoucherId'], 'int')); 
      } 
      if (isset($_GET ['bankTransferId'])) { 
          $this->setBankTransferId($this->strict($_GET ['bankTransferId'], 'int')); 
      } 
      if (isset($_GET ['documentNumber'])) { 
          $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string')); 
      } 
      if (isset($_GET ['cashBookDate'])) { 
          $this->setCashBookDate($this->strict($_GET ['cashBookDate'], 'time')); 
      } 
      if (isset($_GET ['cashBookAmount'])) { 
          $this->setCashBookAmount($this->strict($_GET ['cashBookAmount'], 'int')); 
      } 
      if (isset($_GET ['cashBookDescription'])) { 
          $this->setCashBookDescription($this->strict($_GET ['cashBookDescription'], 'string')); 
      } 
      if (isset($_GET ['leafId'])) { 
          $this->setLeafId($this->strict($_GET ['leafId'], 'int')); 
      } 
      if (isset($_GET ['cashBookLedgerId'])) {
         $this->setTotal(count($_GET ['cashBookLedgerId']));
         if (is_array($_GET ['cashBookLedgerId'])) {
             $this->cashBookLedgerId = array();
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
         if (isset($_GET ['cashBookLedgerId'])) {
             $this->setCashBookLedgerId($this->strict($_GET ['cashBookLedgerId'] [$i], 'numeric'), $i, 'array');
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
         $primaryKeyAll .= $this->getCashBookLedgerId($i, 'array') . ",";
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
     * @return \Core\Financial\Cashbook\CashBookLedger\Model\CashBookLedgerModel
     */ 
     public function setCashBookLedgerId($value, $key, $type) { 
        if ($type == 'single') { 
           $this->cashBookLedgerId = $value;
           return $this;
        } else if ($type == 'array') {
            $this->cashBookLedgerId[$key] = $value;
           return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:setcashBookLedgerId?"));
            exit(); 
        }
    }
    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getCashBookLedgerId($key, $type) {
        if ($type == 'single') {
            return $this->cashBookLedgerId;
        } else if ($type == 'array') {
            return $this->cashBookLedgerId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:getcashBookLedgerId ?"));
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
	 * @return \Core\Financial\Cashbook\CashBookLedger\Model\CashBookLedgerModel
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
	 * @return \Core\Financial\Cashbook\CashBookLedger\Model\CashBookLedgerModel
	 */
	public function setBusinessPartnerId($businessPartnerId)
	{
         $this->businessPartnerId = $businessPartnerId;
         return $this;
	} 
	/**
	 * To Return Bank 
	 * @return int $bankId
	 */ 
	public function getBankId()
	{
	    return $this->bankId;
	}
	/**
	 * To Set Bank 
	 * @param int $bankId Bank 
	 * @return \Core\Financial\Cashbook\CashBookLedger\Model\CashBookLedgerModel
	 */
	public function setBankId($bankId)
	{
         $this->bankId = $bankId;
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
	 * @return \Core\Financial\Cashbook\CashBookLedger\Model\CashBookLedgerModel
	 */
	public function setChartOfAccountId($chartOfAccountId)
	{
         $this->chartOfAccountId = $chartOfAccountId;
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
	 * @return \Core\Financial\Cashbook\CashBookLedger\Model\CashBookLedgerModel
	 */
	public function setCollectionId($collectionId)
	{
         $this->collectionId = $collectionId;
         return $this;
	} 
	/**
	 * To Return Payment Voucher 
	 * @return int $paymentVoucherId
	 */ 
	public function getPaymentVoucherId()
	{
	    return $this->paymentVoucherId;
	}
	/**
	 * To Set Payment Voucher 
	 * @param int $paymentVoucherId Payment Voucher 
	 * @return \Core\Financial\Cashbook\CashBookLedger\Model\CashBookLedgerModel
	 */
	public function setPaymentVoucherId($paymentVoucherId)
	{
         $this->paymentVoucherId = $paymentVoucherId;
         return $this;
	} 
	/**
	 * To Return Bank Transfer 
	 * @return int $bankTransferId
	 */ 
	public function getBankTransferId()
	{
	    return $this->bankTransferId;
	}
	/**
	 * To Set Bank Transfer 
	 * @param int $bankTransferId Bank Transfer 
	 * @return \Core\Financial\Cashbook\CashBookLedger\Model\CashBookLedgerModel
	 */
	public function setBankTransferId($bankTransferId)
	{
         $this->bankTransferId = $bankTransferId;
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
	 * @return \Core\Financial\Cashbook\CashBookLedger\Model\CashBookLedgerModel
	 */
	public function setDocumentNumber($documentNumber)
	{
         $this->documentNumber = $documentNumber;
         return $this;
	} 
	/**
	 * To Return Cash Date 
	 * @return time $cashBookDate
	 */ 
	public function getCashBookDate()
	{
	    return $this->cashBookDate;
	}
	/**
	 * To Set Cash Date 
	 * @param time $cashBookDate Cash Date 
	 * @return \Core\Financial\Cashbook\CashBookLedger\Model\CashBookLedgerModel
	 */
	public function setCashBookDate($cashBookDate)
	{
         $this->cashBookDate = $cashBookDate;
         return $this;
	} 
	/**
	 * To Return Cash Amount 
	 * @return int $cashBookAmount
	 */ 
	public function getCashBookAmount()
	{
	    return $this->cashBookAmount;
	}
	/**
	 * To Set Cash Amount 
	 * @param int $cashBookAmount Cash Amount 
	 * @return \Core\Financial\Cashbook\CashBookLedger\Model\CashBookLedgerModel
	 */
	public function setCashBookAmount($cashBookAmount)
	{
         $this->cashBookAmount = $cashBookAmount;
         return $this;
	} 
	/**
	 * To Return Cash Description 
	 * @return string $cashBookDescription
	 */ 
	public function getCashBookDescription()
	{
	    return $this->cashBookDescription;
	}
	/**
	 * To Set Cash Description 
	 * @param string $cashBookDescription Cash Description 
	 * @return \Core\Financial\Cashbook\CashBookLedger\Model\CashBookLedgerModel
	 */
	public function setCashBookDescription($cashBookDescription)
	{
         $this->cashBookDescription = $cashBookDescription;
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
	 * @return \Core\Financial\Cashbook\CashBookLedger\Model\CashBookLedgerModel
	 */
	public function setLeafId($leafId)
	{
         $this->leafId = $leafId;
         return $this;
	} 
}
?>