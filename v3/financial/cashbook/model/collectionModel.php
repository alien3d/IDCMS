<?php  namespace Core\Financial\Cashbook\Collection\Model;
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
 * Class Collection
 * This is collection model file.This is to ensure strict setting enable for all variable enter to database 
 * 
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\Financial\Cashbook\Collection\Model;
 * @subpackage Cashbook 
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class CollectionModel extends ValidationClass { 
 /**
  * Primary Key
  * @var int 
  */
  private $collectionId; 
 /**
  * Company
  * @var int 
  */
  private $companyId; 
 /**
  * Type
  * @var int 
  */
  private $collectionTypeId; 
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
  * Bank
  * @var int 
  */
  private $bankId; 
 /**
  * Payment Type
  * @var int 
  */
  private $paymentTypeId; 
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
  * Cheque Number
  * @var string 
  */
  private $chequeNumber; 
 /**
  * Amount
  * @var double 
  */
  private $collectionAmount; 
 /**
  * Date
  * @var date 
  */
  private $collectionDate; 
 /**
  * Bank In Slip   Number
  * @var string 
  */
  private $collectionBankInSlipNumber; 
 /**
  * Bank In Slip   Date
  * @var date 
  */
  private $collectionBankInSlipDate; 
 /**
  * Text Amount
  * @var string 
  */
  private $collectionTextAmount; 
 /**
  * Description
  * @var string 
  */
  private $collectionDescription; 
 /**
  * Is Balance
  * @var bool 
  */
  private $isBalance; 
 /**
  * Class Loader
  * @see ValidationClass::execute()
  */
 public function execute() {
     /**
     *  Basic Information Table
     **/
     $this->setTableName('collection');
     $this->setPrimaryKeyName('collectionId');
     $this->setMasterForeignKeyName('collectionId');
     $this->setFilterCharacter('collectionDescription');
     //$this->setFilterCharacter('collectionNote');
     $this->setFilterDate('executeTime');
     /**
     * All the $_POST Environment
     */ 
     if (isset($_POST ['collectionId'])) { 
          $this->setCollectionId($this->strict($_POST ['collectionId'], 'int'), 0, 'single'); 
      } 
      if (isset($_POST ['companyId'])) { 
          $this->setCompanyId($this->strict($_POST ['companyId'], 'int')); 
      } 
      if (isset($_POST ['collectionTypeId'])) { 
          $this->setCollectionTypeId($this->strict($_POST ['collectionTypeId'], 'int')); 
      } 
      if (isset($_POST ['businessPartnerId'])) { 
          $this->setBusinessPartnerId($this->strict($_POST ['businessPartnerId'], 'int')); 
      } 
      if (isset($_POST ['countryId'])) { 
          $this->setCountryId($this->strict($_POST ['countryId'], 'int')); 
      } 
      if (isset($_POST ['bankId'])) { 
          $this->setBankId($this->strict($_POST ['bankId'], 'int')); 
      } 
      if (isset($_POST ['paymentTypeId'])) { 
          $this->setPaymentTypeId($this->strict($_POST ['paymentTypeId'], 'int')); 
      } 
      if (isset($_POST ['documentNumber'])) { 
          $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string')); 
      } 
      if (isset($_POST ['referenceNumber'])) { 
          $this->setReferenceNumber($this->strict($_POST ['referenceNumber'], 'string')); 
      } 
      if (isset($_POST ['chequeNumber'])) { 
          $this->setChequeNumber($this->strict($_POST ['chequeNumber'], 'string')); 
      } 
      if (isset($_POST ['collectionAmount'])) { 
          $this->setCollectionAmount($this->strict($_POST ['collectionAmount'], 'double')); 
      } 
      if (isset($_POST ['collectionDate'])) { 
          $this->setCollectionDate($this->strict($_POST ['collectionDate'], 'date')); 
      } 
      if (isset($_POST ['collectionBankInSlipNumber'])) { 
          $this->setCollectionBankInSlipNumber($this->strict($_POST ['collectionBankInSlipNumber'], 'string')); 
      } 
      if (isset($_POST ['collectionBankInSlipDate'])) { 
          $this->setCollectionBankInSlipDate($this->strict($_POST ['collectionBankInSlipDate'], 'date')); 
      } 
      if (isset($_POST ['collectionTextAmount'])) { 
          $this->setCollectionTextAmount($this->strict($_POST ['collectionTextAmount'], 'string')); 
      } 
      if (isset($_POST ['collectionDescription'])) { 
          $this->setCollectionDescription($this->strict($_POST ['collectionDescription'], 'string')); 
      } 
      if (isset($_POST ['isBalance'])) { 
          $this->setIsBalance($this->strict($_POST ['isBalance'], 'bool')); 
      } 
      /**
     * All the $_GET Environment
     */
     if (isset($_GET ['collectionId'])) { 
          $this->setCollectionId($this->strict($_GET ['collectionId'], 'int'), 0, 'single'); 
      } 
      if (isset($_GET ['companyId'])) { 
          $this->setCompanyId($this->strict($_GET ['companyId'], 'int')); 
      } 
      if (isset($_GET ['collectionTypeId'])) { 
          $this->setCollectionTypeId($this->strict($_GET ['collectionTypeId'], 'int')); 
      } 
      if (isset($_GET ['businessPartnerId'])) { 
          $this->setBusinessPartnerId($this->strict($_GET ['businessPartnerId'], 'int')); 
      } 
      if (isset($_GET ['countryId'])) { 
          $this->setCountryId($this->strict($_GET ['countryId'], 'int')); 
      } 
      if (isset($_GET ['bankId'])) { 
          $this->setBankId($this->strict($_GET ['bankId'], 'int')); 
      } 
      if (isset($_GET ['paymentTypeId'])) { 
          $this->setPaymentTypeId($this->strict($_GET ['paymentTypeId'], 'int')); 
      } 
      if (isset($_GET ['documentNumber'])) { 
          $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string')); 
      } 
      if (isset($_GET ['referenceNumber'])) { 
          $this->setReferenceNumber($this->strict($_GET ['referenceNumber'], 'string')); 
      } 
      if (isset($_GET ['chequeNumber'])) { 
          $this->setChequeNumber($this->strict($_GET ['chequeNumber'], 'string')); 
      } 
      if (isset($_GET ['collectionAmount'])) { 
          $this->setCollectionAmount($this->strict($_GET ['collectionAmount'], 'double')); 
      } 
      if (isset($_GET ['collectionDate'])) { 
          $this->setCollectionDate($this->strict($_GET ['collectionDate'], 'date')); 
      } 
      if (isset($_GET ['collectionBankInSlipNumber'])) { 
          $this->setCollectionBankInSlipNumber($this->strict($_GET ['collectionBankInSlipNumber'], 'string')); 
      } 
      if (isset($_GET ['collectionBankInSlipDate'])) { 
          $this->setCollectionBankInSlipDate($this->strict($_GET ['collectionBankInSlipDate'], 'date')); 
      } 
      if (isset($_GET ['collectionTextAmount'])) { 
          $this->setCollectionTextAmount($this->strict($_GET ['collectionTextAmount'], 'string')); 
      } 
      if (isset($_GET ['collectionDescription'])) { 
          $this->setCollectionDescription($this->strict($_GET ['collectionDescription'], 'string')); 
      } 
      if (isset($_GET ['isBalance'])) { 
          $this->setIsBalance($this->strict($_GET ['isBalance'], 'bool')); 
      } 
      if (isset($_GET ['collectionId'])) {
         $this->setTotal(count($_GET ['collectionId']));
         if (is_array($_GET ['collectionId'])) {
             $this->collectionId = array();
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
         if (isset($_GET ['collectionId'])) {
             $this->setCollectionId($this->strict($_GET ['collectionId'] [$i], 'numeric'), $i, 'array');
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
         $primaryKeyAll .= $this->getCollectionId($i, 'array') . ",";
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
     * @return \Core\Financial\Cashbook\Collection\Model\CollectionModel
     */ 
     public function setCollectionId($value, $key, $type) { 
        if ($type == 'single') { 
           $this->collectionId = $value;
           return $this;
        } else if ($type == 'array') {
            $this->collectionId[$key] = $value;
           return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:setcollectionId?"));
            exit(); 
        }
    }
    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getCollectionId($key, $type) {
        if ($type == 'single') {
            return $this->collectionId;
        } else if ($type == 'array') {
            return $this->collectionId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:getcollectionId ?"));
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
	 * @return \Core\Financial\Cashbook\Collection\Model\CollectionModel
	 */
	public function setCompanyId($companyId)
	{
         $this->companyId = $companyId;
         return $this;
	} 
	/**
	 * To Return Type 
	 * @return int $collectionTypeId
	 */ 
	public function getCollectionTypeId()
	{
	    return $this->collectionTypeId;
	}
	/**
	 * To Set Type 
	 * @param int $collectionTypeId Type 
	 * @return \Core\Financial\Cashbook\Collection\Model\CollectionModel
	 */
	public function setCollectionTypeId($collectionTypeId)
	{
         $this->collectionTypeId = $collectionTypeId;
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
	 * @return \Core\Financial\Cashbook\Collection\Model\CollectionModel
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
	 * @return \Core\Financial\Cashbook\Collection\Model\CollectionModel
	 */
	public function setCountryId($countryId)
	{
         $this->countryId = $countryId;
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
	 * @return \Core\Financial\Cashbook\Collection\Model\CollectionModel
	 */
	public function setBankId($bankId)
	{
         $this->bankId = $bankId;
         return $this;
	} 
	/**
	 * To Return Payment Type 
	 * @return int $paymentTypeId
	 */ 
	public function getPaymentTypeId()
	{
	    return $this->paymentTypeId;
	}
	/**
	 * To Set Payment Type 
	 * @param int $paymentTypeId Payment Type 
	 * @return \Core\Financial\Cashbook\Collection\Model\CollectionModel
	 */
	public function setPaymentTypeId($paymentTypeId)
	{
         $this->paymentTypeId = $paymentTypeId;
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
	 * @return \Core\Financial\Cashbook\Collection\Model\CollectionModel
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
	 * @return \Core\Financial\Cashbook\Collection\Model\CollectionModel
	 */
	public function setReferenceNumber($referenceNumber)
	{
         $this->referenceNumber = $referenceNumber;
         return $this;
	} 
	/**
	 * To Return Cheque Number 
	 * @return string $chequeNumber
	 */ 
	public function getChequeNumber()
	{
	    return $this->chequeNumber;
	}
	/**
	 * To Set Cheque Number 
	 * @param string $chequeNumber Cheque Number 
	 * @return \Core\Financial\Cashbook\Collection\Model\CollectionModel
	 */
	public function setChequeNumber($chequeNumber)
	{
         $this->chequeNumber = $chequeNumber;
         return $this;
	} 
	/**
	 * To Return Amount 
	 * @return double $collectionAmount
	 */ 
	public function getCollectionAmount()
	{
	    return $this->collectionAmount;
	}
	/**
	 * To Set Amount 
	 * @param double $collectionAmount Amount 
	 * @return \Core\Financial\Cashbook\Collection\Model\CollectionModel
	 */
	public function setCollectionAmount($collectionAmount)
	{
         $this->collectionAmount = $collectionAmount;
         return $this;
	} 
	/**
	 * To Return Date 
	 * @return date $collectionDate
	 */ 
	public function getCollectionDate()
	{
	    return $this->collectionDate;
	}
	/**
	 * To Set Date 
	 * @param date $collectionDate Date 
	 * @return \Core\Financial\Cashbook\Collection\Model\CollectionModel
	 */
	public function setCollectionDate($collectionDate)
	{
         $this->collectionDate = $collectionDate;
         return $this;
	} 
	/**
	 * To Return Bank In Slip   Number 
	 * @return string $collectionBankInSlipNumber
	 */ 
	public function getCollectionBankInSlipNumber()
	{
	    return $this->collectionBankInSlipNumber;
	}
	/**
	 * To Set Bank In Slip   Number 
	 * @param string $collectionBankInSlipNumber Bank In Slip   Number 
	 * @return \Core\Financial\Cashbook\Collection\Model\CollectionModel
	 */
	public function setCollectionBankInSlipNumber($collectionBankInSlipNumber)
	{
         $this->collectionBankInSlipNumber = $collectionBankInSlipNumber;
         return $this;
	} 
	/**
	 * To Return Bank In Slip   Date 
	 * @return date $collectionBankInSlipDate
	 */ 
	public function getCollectionBankInSlipDate()
	{
	    return $this->collectionBankInSlipDate;
	}
	/**
	 * To Set Bank In Slip   Date 
	 * @param date $collectionBankInSlipDate Bank In Slip   Date 
	 * @return \Core\Financial\Cashbook\Collection\Model\CollectionModel
	 */
	public function setCollectionBankInSlipDate($collectionBankInSlipDate)
	{
         $this->collectionBankInSlipDate = $collectionBankInSlipDate;
         return $this;
	} 
	/**
	 * To Return Text Amount 
	 * @return string $collectionTextAmount
	 */ 
	public function getCollectionTextAmount()
	{
	    return $this->collectionTextAmount;
	}
	/**
	 * To Set Text Amount 
	 * @param string $collectionTextAmount Text Amount 
	 * @return \Core\Financial\Cashbook\Collection\Model\CollectionModel
	 */
	public function setCollectionTextAmount($collectionTextAmount)
	{
         $this->collectionTextAmount = $collectionTextAmount;
         return $this;
	} 
	/**
	 * To Return Description 
	 * @return string $collectionDescription
	 */ 
	public function getCollectionDescription()
	{
	    return $this->collectionDescription;
	}
	/**
	 * To Set Description 
	 * @param string $collectionDescription Description 
	 * @return \Core\Financial\Cashbook\Collection\Model\CollectionModel
	 */
	public function setCollectionDescription($collectionDescription)
	{
         $this->collectionDescription = $collectionDescription;
         return $this;
	} 
	/**
	 * To Return Is Balance 
	 * @return bool $isBalance
	 */ 
	public function getIsBalance()
	{
	    return $this->isBalance;
	}
	/**
	 * To Set Is Balance 
	 * @param bool $isBalance Is Balance 
	 * @return \Core\Financial\Cashbook\Collection\Model\CollectionModel
	 */
	public function setIsBalance($isBalance)
	{
         $this->isBalance = $isBalance;
         return $this;
	} 
}
?>