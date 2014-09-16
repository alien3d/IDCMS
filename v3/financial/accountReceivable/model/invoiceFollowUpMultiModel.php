<?php  namespace Core\Financial\AccountReceivable\InvoiceFollowUp\MultiModel;
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
 * Class InvoiceFollowUp
 * This is invoiceFollowUp model file.This is to ensure strict setting enable for all variable enter to database 
 * 
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountReceivable\InvoiceFollowUp\Model;
 * @subpackage AccountReceivable 
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class InvoiceFollowUpModel extends ValidationClass { 
 /**
  * Primary   Key
  * @var int 
  */
  private $invoiceFollowUpId; 
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
  * Follow Up
  * @var int 
  */
  private $followUpId; 
 /**
  * Line Number
  * @var int 
  */
  private $invoiceFollowUpLineNumber; 
 /**
  * Document Number
  * @var string 
  */
  private $documentNumber; 
 /**
  * Date
  * @var date 
  */
  private $invoiceFollowUpDate; 
 /**
  * Description
  * @var string 
  */
  private $invoiceFollowUpDescription; 
 /**
  * Class Loader
  * @see ValidationClass::execute()
  */
 public function execute() {
     /**
     *  Basic Information Table
     **/
     $this->setTableName('invoiceFollowUp');
for ($i = 1; $i <= 5;$i++) {
         if (isset($_GET ['invoiceFollowUpId_'.$i])) {
             $this->setInvoiceFollowUpId($this->strict($_GET ['invoiceFollowUpId_'.$i] , 'numeric'), $i);
         }
 	}
for ($i = 1; $i <= 5;$i++) {
         if (isset($_GET ['invoiceId_'.$i])) {
             $this->setInvoiceId($this->strict($_GET ['invoiceId_'.$i] , 'numeric'), $i);
         }
 	}
for ($i = 1; $i <= 5;$i++) {
         if (isset($_GET ['followUpId_'.$i])) {
             $this->setFollowUpId($this->strict($_GET ['followUpId_'.$i] , 'numeric'), $i);
         }
 	}
for ($i = 1; $i <= 5;$i++) {
         if (isset($_GET ['invoiceFollowUpLineNumber_'.$i])) {
             $this->setInvoiceFollowUpLineNumber($this->strict($_GET ['invoiceFollowUpLineNumber_'.$i] , 'numeric'), $i);
         }
 	}
for ($i = 1; $i <= 5;$i++) {
         if (isset($_GET ['documentNumber_'.$i])) {
             $this->setDocumentNumber($this->strict($_GET ['documentNumber_'.$i] , 'numeric'), $i);
         }
 	}
for ($i = 1; $i <= 5;$i++) {
         if (isset($_GET ['invoiceFollowUpDate_'.$i])) {
             $this->setInvoiceFollowUpDate($this->strict($_GET ['invoiceFollowUpDate_'.$i] , 'numeric'), $i);
         }
 	}
for ($i = 1; $i <= 5;$i++) {
         if (isset($_GET ['invoiceFollowUpDescription_'.$i])) {
             $this->setInvoiceFollowUpDescription($this->strict($_GET ['invoiceFollowUpDescription_'.$i] , 'numeric'), $i);
         }
 	}
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
     * @param bool|int|string $value 
     * @param int $key List. 
     * @return \Core\Financial\AccountReceivable\InvoiceFollowUp\Model\InvoiceFollowUpModel
     */ 
     public function setInvoiceFollowUpId($value, $key) { 
            $this->invoiceFollowUpId[$key] = $value;
           return $this;
    }
    /**
     * Return Primary Key Value
     * @param int $key List.
     * @return bool|int|string
     */
    public function getInvoiceFollowUpId($key) {
            return $this->invoiceFollowUpId [$key];
	}
     /** 
	 * To Set Company 
     * @param bool|int|string $value 
     * @param int $key List. 
     * @return \Core\Financial\AccountReceivable\InvoiceFollowUp\Model\InvoiceFollowUpModel
     */ 
     public function setCompanyId($value, $key) { 
            $this->companyId[$key] = $value;
           return $this;
    }
    /**
	 * To Return Company 
     * @param int $key List.
     * @return bool|int|string
     */
    public function getCompanyId($key) {
            return $this->companyId [$key];
	}
     /** 
	 * To Set Invoice 
     * @param bool|int|string $value 
     * @param int $key List. 
     * @return \Core\Financial\AccountReceivable\InvoiceFollowUp\Model\InvoiceFollowUpModel
     */ 
     public function setInvoiceId($value, $key) { 
            $this->invoiceId[$key] = $value;
           return $this;
    }
    /**
	 * To Return Invoice 
     * @param int $key List.
     * @return bool|int|string
     */
    public function getInvoiceId($key) {
            return $this->invoiceId [$key];
	}
     /** 
	 * To Set Follow Up 
     * @param bool|int|string $value 
     * @param int $key List. 
     * @return \Core\Financial\AccountReceivable\InvoiceFollowUp\Model\InvoiceFollowUpModel
     */ 
     public function setFollowUpId($value, $key) { 
            $this->followUpId[$key] = $value;
           return $this;
    }
    /**
	 * To Return Follow Up 
     * @param int $key List.
     * @return bool|int|string
     */
    public function getFollowUpId($key) {
            return $this->followUpId [$key];
	}
     /** 
	 * To Set Line Number 
     * @param bool|int|string $value 
     * @param int $key List. 
     * @return \Core\Financial\AccountReceivable\InvoiceFollowUp\Model\InvoiceFollowUpModel
     */ 
     public function setInvoiceFollowUpLineNumber($value, $key) { 
            $this->invoiceFollowUpLineNumber[$key] = $value;
           return $this;
    }
    /**
	 * To Return Line Number 
     * @param int $key List.
     * @return bool|int|string
     */
    public function getInvoiceFollowUpLineNumber($key) {
            return $this->invoiceFollowUpLineNumber [$key];
	}
     /** 
	 * To Set Document Number 
     * @param bool|int|string $value 
     * @param int $key List. 
     * @return \Core\Financial\AccountReceivable\InvoiceFollowUp\Model\InvoiceFollowUpModel
     */ 
     public function setDocumentNumber($value, $key) { 
            $this->documentNumber[$key] = $value;
           return $this;
    }
    /**
	 * To Return Document Number 
     * @param int $key List.
     * @return bool|int|string
     */
    public function getDocumentNumber($key) {
            return $this->documentNumber [$key];
	}
     /** 
	 * To Set Date 
     * @param bool|int|string $value 
     * @param int $key List. 
     * @return \Core\Financial\AccountReceivable\InvoiceFollowUp\Model\InvoiceFollowUpModel
     */ 
     public function setInvoiceFollowUpDate($value, $key) { 
            $this->invoiceFollowUpDate[$key] = $value;
           return $this;
    }
    /**
	 * To Return Date 
     * @param int $key List.
     * @return bool|int|string
     */
    public function getInvoiceFollowUpDate($key) {
            return $this->invoiceFollowUpDate [$key];
	}
     /** 
	 * To Set Description 
     * @param bool|int|string $value 
     * @param int $key List. 
     * @return \Core\Financial\AccountReceivable\InvoiceFollowUp\Model\InvoiceFollowUpModel
     */ 
     public function setInvoiceFollowUpDescription($value, $key) { 
            $this->invoiceFollowUpDescription[$key] = $value;
           return $this;
    }
    /**
	 * To Return Description 
     * @param int $key List.
     * @return bool|int|string
     */
    public function getInvoiceFollowUpDescription($key) {
            return $this->invoiceFollowUpDescription [$key];
	}
}
?>