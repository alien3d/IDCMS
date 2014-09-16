<?php namespace Core\Financial\AccountReceivable\InvoiceTransaction\Controller; 
use Core\ConfigClass;
use Core\Financial\AccountReceivable\InvoiceTransaction\Model\InvoiceTransactionModel;
use Core\Financial\AccountReceivable\InvoiceTransaction\Service\InvoiceTransactionService;
use Core\Document\Trail\DocumentTrailClass;
use Core\RecordSet\RecordSet;
use Core\shared\SharedClass;
if (!isset($_SESSION)) { 
    session_start(); 
} 
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
require_once ($newFakeDocumentRoot."library/class/classAbstract.php"); 
require_once ($newFakeDocumentRoot."library/class/classRecordSet.php"); 
require_once ($newFakeDocumentRoot."library/class/classDate.php"); 
require_once ($newFakeDocumentRoot."library/class/classDocumentTrail.php"); 
require_once ($newFakeDocumentRoot."library/class/classShared.php"); 
require_once ($newFakeDocumentRoot."v3/system/document/model/documentModel.php"); 
require_once ($newFakeDocumentRoot."v3/financial/accountReceivable/model/invoiceTransactionModel.php"); 
require_once ($newFakeDocumentRoot."v3/financial/accountReceivable/service/invoiceTransactionService.php"); 
/** 
 * Class InvoiceTransaction
 * this is invoiceTransaction controller files. 
 * @name IDCMS 
 * @version 2 
 * @author hafizan 
 * @package  Core\Financial\AccountReceivable\InvoiceTransaction\Controller 
 * @subpackage AccountReceivable 
 * @link http://www.hafizan.com 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL 
 */ 
class InvoiceTransactionClass extends ConfigClass { 
	/** 
	 * Connection to the database 
	 * @var \Core\Database\Mysql\Vendor 
	 */ 
	public $q; 
	/** 
	 * Php Excel Generate Microsoft Excel 2007 Output.Format : xlsx/pdf 
	 * @var \PHPExcel 
	 */ 
	private $excel; 
	/** 
	 * Record Pagination 
	 * @var \Core\RecordSet\RecordSet 
	 */ 
	private $recordSet; 
	/** 
	 * Document Trail Audit. 
	 * @var \Core\Document\Trail\DocumentTrailClass 
	 */ 
	private $documentTrail; 
	/** 
	 * Model 
	 * @var \Core\Financial\AccountReceivable\InvoiceTransaction\Model\InvoiceTransactionModel 
	 */ 
	public $model; 
	/** 
	 * Service-Business Application Process or other ajax request 
	 * @var \Core\Financial\AccountReceivable\InvoiceTransaction\Service\InvoiceTransactionService 
	 */ 
	public $service; 
	/** 
	 * System Format 
	 * @var \Core\shared\SharedClass
	 */ 
	public $systemFormat; 
	/** 
	 * Translation Array 
	 * @var mixed 
	 */ 
	public $translate; 
	/** 
	 * Leaf Access  
	 * @var mixed 
	 */ 
	public $leafAccess; 
	/** 
	 * Translate Label 
	 * @var array
	 */ 
	public $t; 
	/** 
	 * System Format 
	 * @var array
	 */ 
	public $systemFormatArray; 
	/** 
	 * Constructor 
	 */ 
   function __construct() { 
       parent::__construct(); 
       if($_SESSION['companyId']){
           $this->setCompanyId($_SESSION['companyId']);
       }else{
           // fall back to default database if anything wrong
           $this->setCompanyId(1);
       }
       $this->translate=array();
       $this->t=array();
       $this->leafAccess=array();
       $this->systemFormat=array();
       $this->setViewPath("./v3/financial/accountReceivable/view/invoiceTransaction.php"); 
       $this->setControllerPath("./v3/financial/accountReceivable/controller/invoiceTransactionController.php");
       $this->setServicePath("./v3/financial/accountReceivable/service/invoiceTransactionService.php"); 
   } 
	/** 
	 * Class Loader 
	 */ 
	function execute() { 
       parent::__construct(); 
       $this->setAudit(1); 
       $this->setLog(1); 
       $this->model  = new InvoiceTransactionModel(); 
       $this->model->setVendor($this->getVendor()); 
       $this->model->execute(); 
       if ($this->getVendor() == self::MYSQL) { 
           $this->q = new \Core\Database\Mysql\Vendor(); 
       } else if ($this->getVendor() == self::MSSQL) { 
           $this->q = new \Core\Database\Mssql\Vendor(); 
       } else if ($this->getVendor() == self::ORACLE) { 
           $this->q = new \Core\Database\Oracle\Vendor(); 
       }
       $this->setVendor($this->getVendor()); 
       $this->q->setRequestDatabase($this->q->getCoreDatabase()); 
       // $this->q->setApplicationId($this->getApplicationId()); 
       // $this->q->setModuleId($this->getModuleId()); 
       // $this->q->setFolderId($this->getFolderId()); 
       $this->q->setLeafId($this->getLeafId()); 
       $this->q->setLog($this->getLog()); 
       $this->q->setAudit($this->getAudit()); 
       $this->q->connect($this->getConnection(), $this->getUsername(), $this->getDatabase(), $this->getPassword()); 

       $data = $this->q->getLeafLogData();
       if (is_array($data) && count($data)>0 ) {
           $this->q->getLog($data['isLog']);
           $this->q->setAudit($data['isAudit']);
       }
       if($this->getAudit()==1){
           $this->q->setAudit($this->getAudit());
           $this->q->setTableName($this->model->getTableName());
           $this->q->setPrimaryKeyName($this->model->getPrimaryKeyName());
       }
       $translator = new SharedClass();   
       $translator->setCurrentTable($this->model->getTableName()); 
       $translator->setLeafId($this->getLeafId()); 
       $translator->execute();

       $this->translate   = $translator->getLeafTranslation(); // short because code too long  
       $this->t           = $translator->getDefaultTranslation(); // short because code too long  

       $arrayInfo         =   $translator->getFileInfo(); 
       $applicationNative =   $arrayInfo['applicationNative']; 
       $folderNative      =   $arrayInfo['folderNative']; 
       $moduleNative      =   $arrayInfo['moduleNative']; 
       $leafNative        =   $arrayInfo['leafNative']; 
       $this->setApplicationId($arrayInfo['applicationId']); 
       $this->setModuleId($arrayInfo['moduleId']); 
       $this->setFolderId($arrayInfo['folderId']); 
       $this->setLeafId($arrayInfo['leafId']); 
       $this->setReportTitle($applicationNative." :: ".$moduleNative." :: ".$folderNative." :: ".$leafNative); 

       $this->service  = new InvoiceTransactionService(); 
       $this->service->q = $this->q; 
       $this->service->t = $this->t; 
       $this->service->setVendor($this->getVendor()); 
       $this->service->setServiceOutput($this->getServiceOutput()); 
       $this->service->execute(); 

       $this->recordSet = new RecordSet(); 
       $this->recordSet->q = $this->q; 
       $this->recordSet->setCurrentTable($this->model->getTableName()); 
       $this->recordSet->setPrimaryKeyName($this->model->getPrimaryKeyName()); 
       $this->recordSet->execute(); 

       $this->documentTrail = new DocumentTrailClass(); 
       $this->documentTrail->q = $this->q; 
       $this->documentTrail->setVendor($this->getVendor()); 
       $this->documentTrail->setStaffId($this->getStaffId()); 
       $this->documentTrail->setLanguageId($this->getLanguageId()); 
       $this->documentTrail->setApplicationId($this->getApplicationId());
       $this->documentTrail->setModuleId($this->getModuleId());
       $this->documentTrail->setFolderId($this->getFolderId());
       $this->documentTrail->setLeafId($this->getLeafId());
       $this->documentTrail->execute(); 

       $this->systemFormat = new SharedClass();  
       $this->systemFormat->q = $this->q;  
       $this->systemFormat->setCurrentTable($this->model->getTableName());  
       $this->systemFormat->execute();  

       $this->systemFormatArray  =   $this->systemFormat->getSystemFormat();  

       $this->excel = new \PHPExcel (); 
   } 
  
	/**  
	 * Create
	 * @see config::create()  
	 */  
	public function create() {  
       header('Content-Type:application/json; charset=utf-8');  
       $start = microtime(true);  
       if ($this->getVendor() == self::MYSQL) {  
           $sql = "SET NAMES utf8";  
           try {
               $this->q->fast($sql);
           } catch (\Exception $e) {
               echo json_encode(array("success" => false, "message" => $e->getMessage()));
               exit();
           }
       } 
       $this->q->start();  
       $this->model->create();  
       $sql=null;
       if(!$this->model->getCountryId()){
           $this->model->setCountryId($this->service->getCountryDefaultValue());
       }
       if(!$this->model->getInvoiceId()){
           $this->model->setInvoiceId($this->service->getInvoiceDefaultValue());
       }
       if(!$this->model->getChartOfAccountId()){
           $this->model->setChartOfAccountId($this->service->getChartOfAccountDefaultValue());
       }
        //$this->model->setDocumentNumber($this->getDocumentNumber());
       if ($this->getVendor() == self::MYSQL) {  
       $sql="
            INSERT INTO `invoicetransaction` 
            (
                 `companyId`,
                 `countryId`,
                 `invoiceId`,
                 `chartOfAccountId`,
                 `journalNumber`,
                 `invoiceTransactionPrincipalAmount`,
                 `invoiceTransactionInterestAmount`,
                 `invoiceTransactionCoupunRateAmount`,
                 `invoiceTransactionTaxAmount`,
                 `invoiceTransactionAmount`,
                 `isDefault`,
                 `isNew`,
                 `isDraft`,
                 `isUpdate`,
                 `isDelete`,
                 `isActive`,
                 `isApproved`,
                 `isReview`,
                 `isPost`,
                 `executeBy`,
                 `executeTime`
       ) VALUES ( 
                 '".$this->getCompanyId()."',
                 '".$this->model->getCountryId()."',
                 '".$this->model->getInvoiceId()."',
                 '".$this->model->getChartOfAccountId()."',
                 '".$this->model->getJournalNumber()."',
                 '".$this->model->getInvoiceTransactionPrincipalAmount()."',
                 '".$this->model->getInvoiceTransactionInterestAmount()."',
                 '".$this->model->getInvoiceTransactionCoupunRateAmount()."',
                 '".$this->model->getInvoiceTransactionTaxAmount()."',
                 '".$this->model->getInvoiceTransactionAmount()."',
                 '".$this->model->getIsDefault(0, 'single')."',
                 '".$this->model->getIsNew(0, 'single')."',
                 '".$this->model->getIsDraft(0, 'single')."',
                 '".$this->model->getIsUpdate(0, 'single')."',
                 '".$this->model->getIsDelete(0, 'single')."',
                 '".$this->model->getIsActive(0, 'single')."',
                 '".$this->model->getIsApproved(0, 'single')."',
                 '".$this->model->getIsReview(0, 'single')."',
                 '".$this->model->getIsPost(0, 'single')."',
                 '".$this->model->getExecuteBy()."',
                 ".$this->model->getExecuteTime()."
       );";
		 } else if ($this->getVendor() == self::MSSQL) {  
       $sql="
            INSERT INTO [invoiceTransaction] 
            (
                 [invoiceTransactionId],
                 [companyId],
                 [countryId],
                 [invoiceId],
                 [chartOfAccountId],
                 [journalNumber],
                 [invoiceTransactionPrincipalAmount],
                 [invoiceTransactionInterestAmount],
                 [invoiceTransactionCoupunRateAmount],
                 [invoiceTransactionTaxAmount],
                 [invoiceTransactionAmount],
                 [isDefault],
                 [isNew],
                 [isDraft],
                 [isUpdate],
                 [isDelete],
                 [isActive],
                 [isApproved],
                 [isReview],
                 [isPost],
                 [executeBy],
                 [executeTime]
) VALUES ( 
                 '".$this->getCompanyId()."',
                 '".$this->model->getCountryId()."',
                 '".$this->model->getInvoiceId()."',
                 '".$this->model->getChartOfAccountId()."',
                 '".$this->model->getJournalNumber()."',
                 '".$this->model->getInvoiceTransactionPrincipalAmount()."',
                 '".$this->model->getInvoiceTransactionInterestAmount()."',
                 '".$this->model->getInvoiceTransactionCoupunRateAmount()."',
                 '".$this->model->getInvoiceTransactionTaxAmount()."',
                 '".$this->model->getInvoiceTransactionAmount()."',
                 '".$this->model->getIsDefault(0, 'single')."',
                 '".$this->model->getIsNew(0, 'single')."',
                 '".$this->model->getIsDraft(0, 'single')."',
                 '".$this->model->getIsUpdate(0, 'single')."',
                 '".$this->model->getIsDelete(0, 'single')."',
                 '".$this->model->getIsActive(0, 'single')."',
                 '".$this->model->getIsApproved(0, 'single')."',
                 '".$this->model->getIsReview(0, 'single')."',
                 '".$this->model->getIsPost(0, 'single')."',
                 '".$this->model->getExecuteBy()."',
                 ".$this->model->getExecuteTime()."
            );";
       } else if ($this->getVendor() == self::ORACLE) {  
            $sql="
            INSERT INTO INVOICETRANSACTION 
            (
                 COMPANYID,
                 COUNTRYID,
                 INVOICEID,
                 CHARTOFACCOUNTID,
                 JOURNALNUMBER,
                 INVOICETRANSACTIONPRINCIPALAMOUNT,
                 INVOICETRANSACTIONINTERESTAMOUNT,
                 INVOICETRANSACTIONCOUPUNRATEAMOUNT,
                 INVOICETRANSACTIONTAXAMOUNT,
                 INVOICETRANSACTIONAMOUNT,
                 ISDEFAULT,
                 ISNEW,
                 ISDRAFT,
                 ISUPDATE,
                 ISDELETE,
                 ISACTIVE,
                 ISAPPROVED,
                 ISREVIEW,
                 ISPOST,
                 EXECUTEBY,
                 EXECUTETIME
            ) VALUES ( 
                 '".$this->getCompanyId()."',
                 '".$this->model->getCountryId()."',
                 '".$this->model->getInvoiceId()."',
                 '".$this->model->getChartOfAccountId()."',
                 '".$this->model->getJournalNumber()."',
                 '".$this->model->getInvoiceTransactionPrincipalAmount()."',
                 '".$this->model->getInvoiceTransactionInterestAmount()."',
                 '".$this->model->getInvoiceTransactionCoupunRateAmount()."',
                 '".$this->model->getInvoiceTransactionTaxAmount()."',
                 '".$this->model->getInvoiceTransactionAmount()."',
                 '".$this->model->getIsDefault(0, 'single')."',
                 '".$this->model->getIsNew(0, 'single')."',
                 '".$this->model->getIsDraft(0, 'single')."',
                 '".$this->model->getIsUpdate(0, 'single')."',
                 '".$this->model->getIsDelete(0, 'single')."',
                 '".$this->model->getIsActive(0, 'single')."',
                 '".$this->model->getIsApproved(0, 'single')."',
                 '".$this->model->getIsReview(0, 'single')."',
                 '".$this->model->getIsPost(0, 'single')."',
                 '".$this->model->getExecuteBy()."',
                 ".$this->model->getExecuteTime()."
            );";
       }  
       try {
           $this->q->create($sql);
       } catch (\Exception $e) {
           $this->q->rollback();
           echo json_encode(array("success" => false, "message" => $e->getMessage()));
           exit();
       }
       $invoiceTransactionId = $this->q->lastInsertId(); 
       $this->q->commit(); 
       $end = microtime(true); 
       $time = $end - $start; 
       echo json_encode( 
           array(	"success" => true, 
                   "message" => $this->t['newRecordTextLabel'],  
                   "staffName" => $_SESSION['staffName'],  
                   "executeTime" =>date('d-m-Y H:i:s'),  
                   "totalRecord"=>$this->getTotalRecord(),
                   "invoiceTransactionId" => $invoiceTransactionId,
                   "time"=>$time)); 
       exit(); 
	} 
	/** 
    * Read
	 * @see config::read() 
	 */ 
	public function read() { 
       if ($this->getPageOutput() == 'json' ||  $this->getPageOutput() =='table') { 
           header('Content-Type:application/json; charset=utf-8'); 
       } 
       $start = microtime(true); 
       if(isset($_SESSION['isAdmin'])) { 
           if ($_SESSION['isAdmin'] == 0) { 
               if ($this->getVendor() == self::MYSQL) { 
                   $this->setAuditFilter(" `invoicetransaction`.`isActive` = 1  AND `invoicetransaction`.`companyId`='".$this->getCompanyId()."' "); 
               } else if ($this->getVendor() == self::MSSQL) { 
                   $this->setAuditFilter(" [invoiceTransaction].[isActive] = 1 AND [invoiceTransaction].[companyId]='".$this->getCompanyId()."' "); 
               } else if ($this->getVendor() == self::ORACLE) { 
                   $this->setAuditFilter(" INVOICETRANSACTION.ISACTIVE = 1  AND INVOICETRANSACTION.COMPANYID='".$this->getCompanyId()."'"); 
               } 
           } else if ($_SESSION['isAdmin'] == 1) { 
               if ($this->getVendor() == self::MYSQL) { 
                   $this->setAuditFilter("   `invoicetransaction`.`companyId`='".$this->getCompanyId()."'	"); 
               } else if ($this->getVendor() == self::MSSQL) { 
                   $this->setAuditFilter(" [invoiceTransaction].[companyId]='".$this->getCompanyId()."' "); 
               } else if ($this->getVendor() == self::ORACLE) { 
                   $this->setAuditFilter(" INVOICETRANSACTION.COMPANYID='".$this->getCompanyId()."' "); 
               } 
           } 
       } 
       if ($this->getVendor() == self::MYSQL) { 
           $sql = "SET NAMES utf8"; 
     try {
       $this->q->fast($sql);
     } catch (\Exception $e) {
       echo json_encode(array("success" => false, "message" => $e->getMessage()));
               exit();
           }
       }  
       $sql=null;
       if ($this->getVendor() == self::MYSQL) { 

      $sql = "
       SELECT                    `invoicetransaction`.`invoiceTransactionId`,
                    `company`.`companyDescription`,
                    `invoicetransaction`.`companyId`,
                    `country`.`countryDescription`,
                    `invoicetransaction`.`countryId`,
                    `invoice`.`invoiceDescription`,
                    `invoicetransaction`.`invoiceId`,
                    `chartofaccount`.`chartOfAccountTitle`,
                    `invoicetransaction`.`chartOfAccountId`,
                    `invoicetransaction`.`journalNumber`,
                    `invoicetransaction`.`invoiceTransactionPrincipalAmount`,
                    `invoicetransaction`.`invoiceTransactionInterestAmount`,
                    `invoicetransaction`.`invoiceTransactionCoupunRateAmount`,
                    `invoicetransaction`.`invoiceTransactionTaxAmount`,
                    `invoicetransaction`.`invoiceTransactionAmount`,
                    `invoicetransaction`.`isDefault`,
                    `invoicetransaction`.`isNew`,
                    `invoicetransaction`.`isDraft`,
                    `invoicetransaction`.`isUpdate`,
                    `invoicetransaction`.`isDelete`,
                    `invoicetransaction`.`isActive`,
                    `invoicetransaction`.`isApproved`,
                    `invoicetransaction`.`isReview`,
                    `invoicetransaction`.`isPost`,
                    `invoicetransaction`.`executeBy`,
                    `invoicetransaction`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `invoicetransaction`
		  JOIN      `staff`
		  ON        `invoicetransaction`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `invoicetransaction`.`companyId`
	JOIN	`country`
	ON		`country`.`countryId` = `invoicetransaction`.`countryId`
	JOIN	`invoice`
	ON		`invoice`.`invoiceId` = `invoicetransaction`.`invoiceId`
	JOIN	`chartofaccount`
	ON		`chartofaccount`.`chartOfAccountId` = `invoicetransaction`.`chartOfAccountId`
		  WHERE     " . $this->getAuditFilter(); 
       if ($this->model->getInvoiceTransactionId(0, 'single')) { 
           $sql .= " AND `invoicetransaction`.`" . $this->model->getPrimaryKeyName() . "`='" . $this->model->getInvoiceTransactionId(0, 'single') . "'";  
       }
       if ($this->model->getCountryId()) { 
           $sql .= " AND `invoicetransaction`.`countryId`='".$this->model->getCountryId()."'";  
       }
       if ($this->model->getInvoiceId()) { 
           $sql .= " AND `invoicetransaction`.`invoiceId`='".$this->model->getInvoiceId()."'";  
       }
       if ($this->model->getChartOfAccountId()) { 
           $sql .= " AND `invoicetransaction`.`chartOfAccountId`='".$this->model->getChartOfAccountId()."'";  
       }
 } else if ($this->getVendor() == self::MSSQL) {  

		  $sql = "
		  SELECT                    [invoiceTransaction].[invoiceTransactionId],
                    [company].[companyDescription],
                    [invoiceTransaction].[companyId],
                    [country].[countryDescription],
                    [invoiceTransaction].[countryId],
                    [invoice].[invoiceDescription],
                    [invoiceTransaction].[invoiceId],
                    [chartOfAccount].[chartOfAccountTitle],
                    [invoiceTransaction].[chartOfAccountId],
                    [invoiceTransaction].[journalNumber],
                    [invoiceTransaction].[invoiceTransactionPrincipalAmount],
                    [invoiceTransaction].[invoiceTransactionInterestAmount],
                    [invoiceTransaction].[invoiceTransactionCoupunRateAmount],
                    [invoiceTransaction].[invoiceTransactionTaxAmount],
                    [invoiceTransaction].[invoiceTransactionAmount],
                    [invoiceTransaction].[isDefault],
                    [invoiceTransaction].[isNew],
                    [invoiceTransaction].[isDraft],
                    [invoiceTransaction].[isUpdate],
                    [invoiceTransaction].[isDelete],
                    [invoiceTransaction].[isActive],
                    [invoiceTransaction].[isApproved],
                    [invoiceTransaction].[isReview],
                    [invoiceTransaction].[isPost],
                    [invoiceTransaction].[executeBy],
                    [invoiceTransaction].[executeTime],
                    [staff].[staffName] 
		  FROM 	[invoiceTransaction]
		  JOIN	[staff]
		  ON	[invoiceTransaction].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [invoiceTransaction].[companyId]
	JOIN	[country]
	ON		[country].[countryId] = [invoiceTransaction].[countryId]
	JOIN	[invoice]
	ON		[invoice].[invoiceId] = [invoiceTransaction].[invoiceId]
	JOIN	[chartOfAccount]
	ON		[chartOfAccount].[chartOfAccountId] = [invoiceTransaction].[chartOfAccountId]
		  WHERE     " . $this->getAuditFilter(); 
       if ($this->model->getInvoiceTransactionId(0, 'single')) { 
           $sql .= " AND [invoiceTransaction].[" . $this->model->getPrimaryKeyName() . "]		=	'" . $this->model->getInvoiceTransactionId(0, 'single') . "'"; 
       } 
       if ($this->model->getCountryId()) { 
           $sql .= " AND [invoiceTransaction].[countryId]='".$this->model->getCountryId()."'";  
       }
       if ($this->model->getInvoiceId()) { 
           $sql .= " AND [invoiceTransaction].[invoiceId]='".$this->model->getInvoiceId()."'";  
       }
       if ($this->model->getChartOfAccountId()) { 
           $sql .= " AND [invoiceTransaction].[chartOfAccountId]='".$this->model->getChartOfAccountId()."'";  
       }
		} else if ($this->getVendor() == self::ORACLE) {  

		  $sql = "
		  SELECT                    INVOICETRANSACTION.INVOICETRANSACTIONID AS \"invoiceTransactionId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    INVOICETRANSACTION.COMPANYID AS \"companyId\",
                    COUNTRY.COUNTRYDESCRIPTION AS  \"countryDescription\",
                    INVOICETRANSACTION.COUNTRYID AS \"countryId\",
                    INVOICE.INVOICEDESCRIPTION AS  \"invoiceDescription\",
                    INVOICETRANSACTION.INVOICEID AS \"invoiceId\",
                    CHARTOFACCOUNT.CHARTOFACCOUNTTITLE AS  \"chartOfAccountTitle\",
                    INVOICETRANSACTION.CHARTOFACCOUNTID AS \"chartOfAccountId\",
                    INVOICETRANSACTION.JOURNALNUMBER AS \"journalNumber\",
                    INVOICETRANSACTION.INVOICETRANSACTIONPRINCIPALAMOUNT AS \"invoiceTransactionPrincipalAmount\",
                    INVOICETRANSACTION.INVOICETRANSACTIONINTERESTAMOUNT AS \"invoiceTransactionInterestAmount\",
                    INVOICETRANSACTION.INVOICETRANSACTIONCOUPUNRATEAMOUNT AS \"invoiceTransactionCoupunRateAmount\",
                    INVOICETRANSACTION.INVOICETRANSACTIONTAXAMOUNT AS \"invoiceTransactionTaxAmount\",
                    INVOICETRANSACTION.INVOICETRANSACTIONAMOUNT AS \"invoiceTransactionAmount\",
                    INVOICETRANSACTION.ISDEFAULT AS \"isDefault\",
                    INVOICETRANSACTION.ISNEW AS \"isNew\",
                    INVOICETRANSACTION.ISDRAFT AS \"isDraft\",
                    INVOICETRANSACTION.ISUPDATE AS \"isUpdate\",
                    INVOICETRANSACTION.ISDELETE AS \"isDelete\",
                    INVOICETRANSACTION.ISACTIVE AS \"isActive\",
                    INVOICETRANSACTION.ISAPPROVED AS \"isApproved\",
                    INVOICETRANSACTION.ISREVIEW AS \"isReview\",
                    INVOICETRANSACTION.ISPOST AS \"isPost\",
                    INVOICETRANSACTION.EXECUTEBY AS \"executeBy\",
                    INVOICETRANSACTION.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\" 
		  FROM 	INVOICETRANSACTION 
		  JOIN	STAFF 
		  ON	INVOICETRANSACTION.EXECUTEBY = STAFF.STAFFID 
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = INVOICETRANSACTION.COMPANYID
	JOIN	COUNTRY
	ON		COUNTRY.COUNTRYID = INVOICETRANSACTION.COUNTRYID
	JOIN	INVOICE
	ON		INVOICE.INVOICEID = INVOICETRANSACTION.INVOICEID
	JOIN	CHARTOFACCOUNT
	ON		CHARTOFACCOUNT.CHARTOFACCOUNTID = INVOICETRANSACTION.CHARTOFACCOUNTID
         WHERE     " . $this->getAuditFilter(); 
           if ($this->model->getInvoiceTransactionId(0, 'single'))  {
               $sql .= " AND INVOICETRANSACTION. ".strtoupper($this->model->getPrimaryKeyName()) . "='" . $this->model->getInvoiceTransactionId(0, 'single') . "'"; 
           } 
       if ($this->model->getCountryId()) { 
           $sql .= " AND INVOICETRANSACTION.COUNTRYID='".$this->model->getCountryId()."'";  
       }
       if ($this->model->getInvoiceId()) { 
           $sql .= " AND INVOICETRANSACTION.INVOICEID='".$this->model->getInvoiceId()."'";  
       }
       if ($this->model->getChartOfAccountId()) { 
           $sql .= " AND INVOICETRANSACTION.CHARTOFACCOUNTID='".$this->model->getChartOfAccountId()."'";  
       }
           }else { 
               header('Content-Type:application/json; charset=utf-8');  
               echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
               exit(); 
		} 
		/** 
		 * filter column based on first character 
		 */ 
		if($this->getCharacterQuery()){ 
               if($this->getVendor()==self::MYSQL){ 
                   $sql.=" AND `invoicetransaction`.`".$this->model->getFilterCharacter()."` like '".$this->getCharacterQuery()."%'"; 
               } else if($this->getVendor()==self::MSSQL){ 
                   $sql.=" AND [invoiceTransaction].[".$this->model->getFilterCharacter()."] like '".$this->getCharacterQuery()."%'"; 
               } else if ($this->getVendor()==self::ORACLE){ 
                   $sql.=" AND Initcap(INVOICETRANSACTION.".strtoupper($this->model->getFilterCharacter()).") LIKE Initcap('".$this->getCharacterQuery()."%')"; 
               }
		} 
		/** 
		 * filter column based on Range Of Date 
		 * Example Day,Week,Month,Year 
		 */ 
		if($this->getDateRangeStartQuery()){ 
               if($this->getVendor()==self::MYSQL){ 
                   $sql.=$this->q->dateFilter('invoicetransaction',$this->model->getFilterDate(),$this->getDateRangeStartQuery(),$this->getDateRangeEndQuery(),$this->getDateRangeTypeQuery(),$this->getDateRangeExtraTypeQuery()); 
               } else if($this->getVendor()==self::MSSQL){ 
                   $sql.=$this->q->dateFilter('invoiceTransaction',$this->model->getFilterDate(),$this->getDateRangeStartQuery(),$this->getDateRangeEndQuery(),$this->getDateRangeTypeQuery(),$this->getDateRangeExtraTypeQuery()); 
               } else if ($this->getVendor()==self::ORACLE){ 
                   $sql.=$this->q->dateFilter('INVOICETRANSACTION',$this->model->getFilterDate(),$this->getDateRangeStartQuery(),$this->getDateRangeEndQuery(),$this->getDateRangeTypeQuery(),$this->getDateRangeExtraTypeQuery()); 
               }
           } 
		/** 
		 * filter column don't want to filter.Example may contain  sensitive information or unwanted to be search. 
		 * E.g  $filterArray=array('`leaf`.`leafId`'); 
		 * @variables $filterArray; 
		 */  
        $filterArray =null;
        if($this->getVendor() ==self::MYSQL) { 
		    $filterArray = array("`invoicetransaction`.`invoiceTransactionId`",
                                              "`staff`.`staffPassword`"); 
        } else if ($this->getVendor() == self::MSSQL) {
 		    $filterArray = array("[invoicetransaction].[invoiceTransactionId]",
                                              "[staff].[staffPassword]"); 
        } else if ($this->getVendor() == self::ORACLE) { 
		    $filterArray = array("INVOICETRANSACTION.INVOICETRANSACTIONID",
                                              "STAFF.STAFFPASSWORD"); 
        }
		$tableArray = null; 
		if($this->getVendor()==self::MYSQL){ 
			$tableArray = array('staff','invoicetransaction','country','invoice','chartofaccount'); 
		} else if($this->getVendor()==self::MSSQL){ 
			$tableArray = array('staff','invoicetransaction','country','invoice','chartofaccount'); 
		} else if ($this->getVendor()==self::ORACLE){ 
			$tableArray = array('STAFF','INVOICETRANSACTION','COUNTRY','INVOICE','CHARTOFACCOUNT'); 
		}   
       $tempSql=null;
		if ($this->getFieldQuery()) { 
               $this->q->setFieldQuery($this->getFieldQuery()); 
               if ($this->getVendor() == self::MYSQL) { 
                   $sql .= $this->q->quickSearch($tableArray, $filterArray); 
               } else if ($this->getVendor() == self::MSSQL) { 
                   $tempSql = $this->q->quickSearch($tableArray, $filterArray); 
                   $sql .= $tempSql; 
               } else if ($this->getVendor() == self::ORACLE) { 
                   $tempSql = $this->q->quickSearch($tableArray, $filterArray); 
                   $sql .= $tempSql; 
               } 
		} 
       $tempSql2=null;
		if ($this->getGridQuery()) { 
               $this->q->setGridQuery($this->getGridQuery()); 
               if ($this->getVendor() == self::MYSQL) { 
                   $sql .= $this->q->searching(); 
               } else if ($this->getVendor() == self::MSSQL) { 
                   $tempSql2 = $this->q->searching(); 
                   $sql .= $tempSql2; 
               } else if ($this->getVendor() == self::ORACLE) { 
                   $tempSql2 = $this->q->searching(); 
                   $sql .= $tempSql2; 
               } 
		} 
       try {
           $this->q->read($sql);
       } catch (\Exception $e) {
           echo json_encode(array("success" => false, "message" => $e->getMessage()));
           exit();
       }
		$total = intval($this->q->numberRows()); 
		if ( $this->getSortField()) { 
               if ($this->getVendor() == self::MYSQL) { 
                   $sql .= "	ORDER BY `" . $this->getSortField() . "` " . $this->getOrder() . " "; 
               } else if ($this->getVendor() == self::MSSQL) { 
                   $sql .= "	ORDER BY [" . $this->getSortField() . "] " . $this->getOrder() . " "; 
               } else if ($this->getVendor() == self::ORACLE) { 
                   $sql .= "	ORDER BY " . strtoupper($this->getSortField()) . " " . strtoupper($this->getOrder()) . " "; 
               } 
		} else {
       	// @note sql server 2012 must order by first then offset ??
        if($this->getVendor() == self::MSSQL){
            $sql .= "	ORDER BY [" . $this->model->getTableName() . "].[" . $this->model->getPrimaryKeyName() . "] ASC ";
        }
    }
		$_SESSION ['sql'] = $sql; // push to session so can make report via excel and pdf 
		$_SESSION ['start'] = $this->getStart(); 
		$_SESSION ['limit'] = $this->getLimit(); 
       $sqlDerived = null;
		if ( $this->getLimit()) { 
			// only mysql have limit 
			if ($this->getVendor() == self::MYSQL) { 
				$sqlDerived  = $sql." LIMIT  " . $this->getStart() . "," . $this->getLimit() . " "; 
			} else if ($this->getVendor() == self::MSSQL) { 
              $sqlDerived =
              $sql . " OFFSET " . $this->getStart() . " ROWS
              FETCH NEXT 	" . $this->getLimit() . " ROWS ONLY "; 
			 } else if ($this->getVendor() == self::ORACLE) { 

						$sqlDerived = "

						SELECT *

						FROM 	(
 	
									SELECT	a.*,

											rownum r

									FROM ( ".$sql."
 
								) a

						WHERE 	rownum <= '" . ($this->getStart() + $this->getLimit()) . "' )
						WHERE 	r >=  '" . ($this->getStart() + 1) . "'";
			 } else { 
				echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
				exit(); 
			} 
		} 
		/* 
		 *  Only Execute One Query 
		 */ 
		if (!($this->model->getInvoiceTransactionId(0, 'single'))) { 
           try {
               $this->q->read($sqlDerived);
           } catch (\Exception $e) {
               echo json_encode(array("success" => false, "message" => $e->getMessage()));
               exit();
           }
		} 
		$items = array(); 
           $i = 1; 
		while (($row = $this->q->fetchAssoc()) == TRUE) { 
               $row['total'] = $total; // small override 
               $row['counter'] = $this->getStart() + 22; 
               if ($this->model->getInvoiceTransactionId(0, 'single')) { 
                   $row['firstRecord'] = $this->firstRecord('value'); 
                   $row['previousRecord'] = $this->previousRecord('value', $this->model->getInvoiceTransactionId(0, 'single')); 
                   $row['nextRecord'] = $this->nextRecord('value', $this->model->getInvoiceTransactionId(0, 'single')); 
                   $row['lastRecord'] = $this->lastRecord('value'); 
               }  
               $items [] = $row; 
               $i++; 
		}  
		if ($this->getPageOutput() == 'html') { 
               return $items; 
           } else if ($this->getPageOutput() == 'json') { 
           if ($this->model->getInvoiceTransactionId(0, 'single')) { 
               $end = microtime(true); 
               $time = $end - $start; 
               echo str_replace(array("[","]"),"",json_encode(array( 
                   'success' => true,  
                   'total' => $total,  
                   'message' => $this->t['viewRecordMessageLabel'],  
                   'time' => $time,  
                   'firstRecord' => $this->firstRecord('value'),  
                   'previousRecord' => $this->previousRecord('value', $this->model->getInvoiceTransactionId(0, 'single')),  
                   'nextRecord' => $this->nextRecord('value', $this->model->getInvoiceTransactionId(0, 'single')),  
                   'lastRecord' => $this->lastRecord('value'), 
                   'data' => $items))); 
               exit(); 
           } else { 
               if (count($items) == 0) { 
                   $items = ''; 
               } 
               $end = microtime(true); 
               $time = $end - $start; 
               echo json_encode(array( 
                   'success' =>true,  
                   'total' => $total,  
                   'message' => $this->t['viewRecordMessageLabel'], 
                   'time' => $time,  
                   'firstRecord' => $this->recordSet->firstRecord('value'),  
                   'previousRecord' => $this->recordSet->previousRecord('value', $this->model->getInvoiceTransactionId(0, 'single')),  
                   'nextRecord' => $this->recordSet->nextRecord('value', $this->model->getInvoiceTransactionId(0, 'single')),  
                   'lastRecord' => $this->recordSet->lastRecord('value'), 
                   'data' => $items)); 
               exit();  
           } 
       }	 
       return false;
   } 
	/**
    * Update
	 * @see config::update() 
	 */ 
   function update() { 
       header('Content-Type:application/json; charset=utf-8'); 
       $start = microtime(true); 
       if ($this->getVendor() == self::MYSQL) { 
           $sql = "SET NAMES utf8"; 
           try {
               $this->q->fast($sql);
           } catch (\Exception $e) {
               echo json_encode(array("success" => false, "message" => $e->getMessage()));
               exit();
           }
       } 
       $this->q->start(); 
       $this->model->update(); 
       // before updating check the id exist or not . if exist continue to update else warning the user 
       $sql=null;
       if(!$this->model->getCountryId()){
           $this->model->setCountryId($this->service->getCountryDefaultValue());
       }
       if(!$this->model->getInvoiceId()){
           $this->model->setInvoiceId($this->service->getInvoiceDefaultValue());
       }
       if(!$this->model->getChartOfAccountId()){
           $this->model->setChartOfAccountId($this->service->getChartOfAccountDefaultValue());
       }
       if ($this->getVendor() == self::MYSQL) {  
           $sql = " 
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`invoicetransaction` 
           WHERE  `companyId`='".$this->getCompanyId."'            AND  	   `" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getInvoiceTransactionId(0, 'single') . "' "; 
       } else if ($this->getVendor() == self::MSSQL) { 
           $sql = " 
           SELECT	[" . $this->model->getPrimaryKeyName() . "] 
           FROM 	[invoiceTransaction] 
           WHERE  [companyId]='".$this->getCompanyId."'            AND  	   [" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getInvoiceTransactionId(0, 'single') . "' "; 
       } else if ($this->getVendor() == self::ORACLE) { 
           $sql = " 
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
           FROM 	INVOICETRANSACTION 
           WHERE  COMPANYID='".$this->getCompanyId."'            AND  	   " . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getInvoiceTransactionId(0, 'single') . "' "; 
       }
       $result = $this->q->fast($sql); 
       $total = $this->q->numberRows($result, $sql); 
       if ($total == 0) { 
           echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel'])); 
           exit(); 
       } else { 
           if ($this->getVendor() == self::MYSQL) { 
               $sql="
               UPDATE `invoicetransaction` SET 
                       `countryId` = '".$this->model->getCountryId()."',
                       `invoiceId` = '".$this->model->getInvoiceId()."',
                       `chartOfAccountId` = '".$this->model->getChartOfAccountId()."',
                       `journalNumber` = '".$this->model->getJournalNumber()."',
                       `invoiceTransactionPrincipalAmount` = '".$this->model->getInvoiceTransactionPrincipalAmount()."',
                       `invoiceTransactionInterestAmount` = '".$this->model->getInvoiceTransactionInterestAmount()."',
                       `invoiceTransactionCoupunRateAmount` = '".$this->model->getInvoiceTransactionCoupunRateAmount()."',
                       `invoiceTransactionTaxAmount` = '".$this->model->getInvoiceTransactionTaxAmount()."',
                       `invoiceTransactionAmount` = '".$this->model->getInvoiceTransactionAmount()."',
                       `isDefault` = '".$this->model->getIsDefault('0','single')."',
                       `isNew` = '".$this->model->getIsNew('0','single')."',
                       `isDraft` = '".$this->model->getIsDraft('0','single')."',
                       `isUpdate` = '".$this->model->getIsUpdate('0','single')."',
                       `isDelete` = '".$this->model->getIsDelete('0','single')."',
                       `isActive` = '".$this->model->getIsActive('0','single')."',
                       `isApproved` = '".$this->model->getIsApproved('0','single')."',
                       `isReview` = '".$this->model->getIsReview('0','single')."',
                       `isPost` = '".$this->model->getIsPost('0','single')."',
                       `executeBy` = '".$this->model->getExecuteBy('0','single')."',
                       `executeTime` = ".$this->model->getExecuteTime()."
               WHERE    `invoiceTransactionId`='".$this->model->getInvoiceTransactionId('0','single')."'";

           } else if ($this->getVendor() == self::MSSQL) {  
                $sql="
                UPDATE [invoiceTransaction] SET 
                       [countryId] = '".$this->model->getCountryId()."',
                       [invoiceId] = '".$this->model->getInvoiceId()."',
                       [chartOfAccountId] = '".$this->model->getChartOfAccountId()."',
                       [journalNumber] = '".$this->model->getJournalNumber()."',
                       [invoiceTransactionPrincipalAmount] = '".$this->model->getInvoiceTransactionPrincipalAmount()."',
                       [invoiceTransactionInterestAmount] = '".$this->model->getInvoiceTransactionInterestAmount()."',
                       [invoiceTransactionCoupunRateAmount] = '".$this->model->getInvoiceTransactionCoupunRateAmount()."',
                       [invoiceTransactionTaxAmount] = '".$this->model->getInvoiceTransactionTaxAmount()."',
                       [invoiceTransactionAmount] = '".$this->model->getInvoiceTransactionAmount()."',
                       [isDefault] = '".$this->model->getIsDefault(0, 'single')."',
                       [isNew] = '".$this->model->getIsNew(0, 'single')."',
                       [isDraft] = '".$this->model->getIsDraft(0, 'single')."',
                       [isUpdate] = '".$this->model->getIsUpdate(0, 'single')."',
                       [isDelete] = '".$this->model->getIsDelete(0, 'single')."',
                       [isActive] = '".$this->model->getIsActive(0, 'single')."',
                       [isApproved] = '".$this->model->getIsApproved(0, 'single')."',
                       [isReview] = '".$this->model->getIsReview(0, 'single')."',
                       [isPost] = '".$this->model->getIsPost(0, 'single')."',
                       [executeBy] = '".$this->model->getExecuteBy(0, 'single')."',
                       [executeTime] = ".$this->model->getExecuteTime()."
                WHERE   [invoiceTransactionId]='".$this->model->getInvoiceTransactionId('0','single')."'";

           } else if ($this->getVendor() == self::ORACLE) {  
                $sql="
                UPDATE INVOICETRANSACTION SET
                        COUNTRYID = '".$this->model->getCountryId()."',
                       INVOICEID = '".$this->model->getInvoiceId()."',
                       CHARTOFACCOUNTID = '".$this->model->getChartOfAccountId()."',
                       JOURNALNUMBER = '".$this->model->getJournalNumber()."',
                       INVOICETRANSACTIONPRINCIPALAMOUNT = '".$this->model->getInvoiceTransactionPrincipalAmount()."',
                       INVOICETRANSACTIONINTERESTAMOUNT = '".$this->model->getInvoiceTransactionInterestAmount()."',
                       INVOICETRANSACTIONCOUPUNRATEAMOUNT = '".$this->model->getInvoiceTransactionCoupunRateAmount()."',
                       INVOICETRANSACTIONTAXAMOUNT = '".$this->model->getInvoiceTransactionTaxAmount()."',
                       INVOICETRANSACTIONAMOUNT = '".$this->model->getInvoiceTransactionAmount()."',
                       ISDEFAULT = '".$this->model->getIsDefault(0, 'single')."',
                       ISNEW = '".$this->model->getIsNew(0, 'single')."',
                       ISDRAFT = '".$this->model->getIsDraft(0, 'single')."',
                       ISUPDATE = '".$this->model->getIsUpdate(0, 'single')."',
                       ISDELETE = '".$this->model->getIsDelete(0, 'single')."',
                       ISACTIVE = '".$this->model->getIsActive(0, 'single')."',
                       ISAPPROVED = '".$this->model->getIsApproved(0, 'single')."',
                       ISREVIEW = '".$this->model->getIsReview(0, 'single')."',
                       ISPOST = '".$this->model->getIsPost(0, 'single')."',
                       EXECUTEBY = '".$this->model->getExecuteBy(0, 'single')."',
                       EXECUTETIME = ".$this->model->getExecuteTime()."
                WHERE  INVOICETRANSACTIONID='".$this->model->getInvoiceTransactionId('0','single')."'";

           } 
           try {
               $this->q->update($sql);
           } catch (\Exception $e) {
               $this->q->rollback();
               echo json_encode(array("success" => false, "message" => $e->getMessage()));
               exit();
           }
       } 
       $this->q->commit(); 
       $end = microtime(true); 
       $time = $end - $start; 
       echo json_encode( 
           array(  "success" =>true, 
               "message" => $this->t['updateRecordTextLabel'], 
               "time"=>$time)); 
               exit(); 
   } 
	/** 
    * Delete
	 * @see config::delete() 
	 */ 
   function delete() { 
       header('Content-Type:application/json; charset=utf-8'); 
       $start = microtime(true); 
       if ($this->getVendor() == self::MYSQL) { 
           $sql = "SET NAMES utf8"; 
           try {
               $this->q->fast($sql);
           } catch (\Exception $e) {
               echo json_encode(array("success" => false, "message" => $e->getMessage()));
               exit();
           }
       } 
       $this->q->start(); 
       $this->model->delete(); 
       // before updating check the id exist or not . if exist continue to update else warning the user 
       $sql=null;
       if ($this->getVendor() == self::MYSQL) { 
           $sql = " 
           SELECT	`" . $this->model->getPrimaryKeyName() . "` 
           FROM 	`invoicetransaction` 
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getInvoiceTransactionId(0, 'single') . "' ";  
       } else if ($this->getVendor() == self::MSSQL) { 
           $sql = " 
           SELECT	[" . $this->model->getPrimaryKeyName() . "]  
           FROM 	[invoiceTransaction] 
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getInvoiceTransactionId(0, 'single') . "' "; 
       } else if ($this->getVendor() == self::ORACLE) { 
           $sql = " 
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
           FROM 	INVOICETRANSACTION 
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getInvoiceTransactionId(0, 'single') . "' "; 
       }  
       try {
           $result    =   $this->q->fast($sql);
       } catch (\Exception $e) {
           echo json_encode(array("success" => false, "message" => $e->getMessage()));
           exit();
       }
       $total = $this->q->numberRows($result, $sql); 
       if ($total == 0) { 
           echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel'])); 
           exit(); 
       } else { 
           if ($this->getVendor() == self::MYSQL) { 
               $sql="
               UPDATE  `invoicetransaction` 
               SET     `isDefault`     =   '" . $this->model->getIsDefault(0, 'single') . "',
                       `isNew`         =   '" . $this->model->getIsNew(0, 'single') . "',
                       `isDraft`       =   '" . $this->model->getIsDraft(0, 'single') . "',
                       `isUpdate`      =   '" . $this->model->getIsUpdate(0, 'single') . "',
                       `isDelete`      =   '" . $this->model->getIsDelete(0, 'single') . "',
                       `isActive`      =   '" . $this->model->getIsActive(0, 'single') . "',
                       `isApproved`    =   '" . $this->model->getIsApproved(0, 'single') . "',
                       `isReview`      =   '" . $this->model->getIsReview(0, 'single') . "',
                       `isPost`        =   '" . $this->model->getIsPost(0, 'single') . "',
                       `executeBy`     =   '" . $this->model->getExecuteBy() . "',
                       `executeTime`   =   " . $this->model->getExecuteTime() . "
               WHERE   `invoiceTransactionId`   =  '" . $this->model->getInvoiceTransactionId(0, 'single') . "'";
           } else if ($this->getVendor() == self::MSSQL) {  
               $sql="
               UPDATE  [invoiceTransaction] 
               SET     [isDefault]     =   '" . $this->model->getIsDefault(0, 'single') . "',
                       [isNew]         =   '" . $this->model->getIsNew(0, 'single') . "',
                       [isDraft]       =   '" . $this->model->getIsDraft(0, 'single') . "',
                       [isUpdate]      =   '" . $this->model->getIsUpdate(0, 'single') . "',
                       [isDelete]      =   '" . $this->model->getIsDelete(0, 'single') . "',
                       [isActive]      =   '" . $this->model->getIsActive(0, 'single') . "',
                       [isApproved]    =   '" . $this->model->getIsApproved(0, 'single') . "',
                       [isReview]      =   '" . $this->model->getIsReview(0, 'single') . "',
                       [isPost]        =   '" . $this->model->getIsPost(0, 'single') . "',
                       [executeBy]     =   '" . $this->model->getExecuteBy() . "',
                       [executeTime]   =   " . $this->model->getExecuteTime() . "
               WHERE   [invoiceTransactionId]	=  '" . $this->model->getInvoiceTransactionId(0, 'single') . "'";
           } else if ($this->getVendor() == self::ORACLE) {  
               $sql="
               UPDATE  INVOICETRANSACTION 
               SET     ISDEFAULT       =   '" . $this->model->getIsDefault(0, 'single') . "',
                       ISNEW           =   '" . $this->model->getIsNew(0, 'single') . "',
                       ISDRAFT         =   '" . $this->model->getIsDraft(0, 'single') ."',
                       ISUPDATE        =   '" . $this->model->getIsUpdate(0, 'single') . "',
                       ISDELETE        =   '" . $this->model->getIsDelete(0, 'single') . "',
                       ISACTIVE        =   '" . $this->model->getIsActive(0, 'single') . "',
                       ISAPPROVED      =   '" . $this->model->getIsApproved(0, 'single') ."',
                       ISREVIEW        =   '" .$this->model->getIsReview(0, 'single') . "',
                       ISPOST          =   '" . $this->model->getIsPost(0, 'single') ."',
                       EXECUTEBY       =   '" . $this->model->getExecuteBy() ."',
                       EXECUTETIME     =   " . $this->model->getExecuteTime() . "
               WHERE   INVOICETRANSACTIONID	=  '" . $this->model->getInvoiceTransactionId(0, 'single') . "'";
           }  
           try {
               $this->q->update($sql);
           } catch (\Exception $e) {
               $this->q->rollback();
               echo json_encode(array("success" => false, "message" => $e->getMessage()));
               exit();
           }
       } 
       $this->q->commit(); 
       $end = microtime(true); 
       $time = $end - $start; 
       echo json_encode( 
           array(  "success" => true, 
                   "message" => $this->t['deleteRecordTextLabel'], 
                   "time"=>$time)); 
       exit(); 
	} 
     /** 
     * To Update flag Status 
     */ 
     function updateStatus() { 
           header('Content-Type:application/json; charset=utf-8'); 
		$start = microtime(true); 
           $sqlLooping=null;
		if ($this->getVendor() == self::MYSQL) { 
               $sql = "SET NAMES utf8"; 
           try {
               $this->q->fast($sql);
           } catch (\Exception $e) {
               echo json_encode(array("success" => false, "message" => $e->getMessage()));
               exit();
           }
		} 
		$this->q->start(); 
		$loop = intval($this->model->getTotal()); 
       $sql=null;
		if ($this->getVendor() == self::MYSQL) { 
               $sql = " 
               UPDATE `invoicetransaction` 
               SET	   `executeBy`		=	'".$this->model->getExecuteBy()."',
					   `executeTime`	=	".$this->model->getExecuteTime().",";
		} else if ($this->getVendor() == self::MSSQL) { 
               $sql = " 
               UPDATE 	[invoiceTransaction] 
               SET	   [executeBy]		=	'".$this->model->getExecuteBy()."',
					   [executeTime]	=	".$this->model->getExecuteTime().",";
		} else if ($this->getVendor() == self::ORACLE) { 
               $sql = " 
               UPDATE INVOICETRANSACTION 
               SET	   EXECUTEBY		=	'".$this->model->getExecuteBy()."',
					   EXECUTETIME		=	".$this->model->getExecuteTime().",";
		}  else { 
               echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
               exit(); 
		} 
       if($_SESSION) { 
           if($_SESSION['isAdmin']==1) { 
                 if ($this->model->getIsDefaultTotal() > 0) {
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " `isDefault` = CASE `invoicetransaction`.`".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isDefault] = CASE [invoiceTransaction].[" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISDEFAULT = CASE INVOICETRANSACTION.".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getInvoiceTransactionId($i, 'array') . "
                         THEN " . $this->model->getIsDefault($i, 'array') . "";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " ELSE `isDefault` END,";
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= " ELSE [isDefault] END,";
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ELSE ISDEFAULT END,";
                     }
			} 
                 if ($this->model->getIsDraftTotal() > 0) {
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " `isDraft` = CASE `invoicetransaction`.`".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isDraft] = CASE [invoiceTransaction].[" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISDRAFT = CASE INVOICETRANSACTION.".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getInvoiceTransactionId($i, 'array') . "
                         THEN " . $this->model->getIsDraft($i, 'array') . "";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " ELSE `isDraft` END,";
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= " ELSE [isDraft] END,";
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ELSE ISDRAFT END,";
                     }
			} 
                 if ($this->model->getIsNewTotal() > 0) {
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " `isNew` = CASE `invoicetransaction`.`".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isNew] = CASE [invoiceTransaction].[" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISNEW = CASE INVOICETRANSACTION.".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getInvoiceTransactionId($i, 'array') . "
                         THEN " . $this->model->getIsNew($i, 'array') . "";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " ELSE `isNew` END,";
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= " ELSE [isNew] END,";
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ELSE ISNEW END,";
                     }
			} 
                 if ($this->model->getIsActiveTotal() > 0) {
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " `isActive` = CASE `invoicetransaction`.`".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isActive] = CASE [invoiceTransaction].[" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISACTIVE = CASE INVOICETRANSACTION.".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getInvoiceTransactionId($i, 'array') . "
                         THEN " . $this->model->getIsActive($i, 'array') . "";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " ELSE `isActive` END,";
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= " ELSE [isActive] END,";
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ELSE ISACTIVE END,";
                     }
			} 
                 if ($this->model->getIsUpdateTotal() > 0) {
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " `isUpdate` = CASE `invoicetransaction`.`".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isUpdate] = CASE [invoiceTransaction].[" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISUPDATE = CASE INVOICETRANSACTION.".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getInvoiceTransactionId($i, 'array') . "
                         THEN " . $this->model->getIsUpdate($i, 'array') . "";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " ELSE `isUpdate` END,";
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= " ELSE [isUpdate] END,";
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ELSE ISUPDATE END,";
                     }
			} 
                 if ($this->model->getIsDeleteTotal() > 0) {
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " `isDelete` = CASE `invoicetransaction`.`".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isDelete] = CASE [invoiceTransaction].[" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISDELETE = CASE INVOICETRANSACTION.".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getInvoiceTransactionId($i, 'array') . "
                         THEN " . $this->model->getIsDelete($i, 'array') . "";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " ELSE `isDelete` END,";
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= " ELSE [isDelete] END,";
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ELSE ISDELETE END,";
                     }
			} 
                 if ($this->model->getIsReviewTotal() > 0) {
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " `isReview` = CASE `invoicetransaction`.`".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isReview] = CASE [invoiceTransaction].[" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISREVIEW = CASE INVOICETRANSACTION.".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getInvoiceTransactionId($i, 'array') . "
                         THEN " . $this->model->getIsReview($i, 'array') . "";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " ELSE `isReview` END,";
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= " ELSE [isReview] END,";
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ELSE ISREVIEW END,";
                     }
			} 
                 if ($this->model->getIsPostTotal() > 0) {
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " `isPost` = CASE `invoicetransaction`.`".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isPost] = CASE [invoiceTransaction].[" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISPOST = CASE INVOICETRANSACTION.".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getInvoiceTransactionId($i, 'array') . "
                         THEN " . $this->model->getIsPost($i, 'array') . "";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " ELSE `isPost` END,";
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= " ELSE [isPost] END,";
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ELSE ISPOST END,";
                     }
			} 
                 if ($this->model->getIsApprovedTotal() > 0) {
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " `isApproved` = CASE `invoicetransaction`.`".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isApproved] = CASE [invoiceTransaction].[" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISAPPROVED = CASE INVOICETRANSACTION.".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getInvoiceTransactionId($i, 'array') . "
                         THEN " . $this->model->getIsApproved($i, 'array') . "";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " ELSE `isApproved` END,";
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= " ELSE [isApproved] END,";
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ELSE ISAPPROVED END,";
                     }
			} 
             } else { 
                 if ($this->model->getIsDeleteTotal() > 0) {
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .=" `isDelete` = CASE `invoicetransaction`.`" . $this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isDelete] = CASE [INVOICETRANSACTION].[" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISDELETE = CASE INVOICETRANSACTION.".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     }else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getInvoiceTransactionId($i, 'array') . "
                         THEN " . $this->model->getIsDelete($i, 'array') . " ";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " ELSE `isDelete` END,";
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= " ELSE [isDelete] END,";
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ELSE ISDELETE END,";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .=" `isActive` = CASE `invoicetransaction`.`" . $this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isActive] = CASE [INVOICETRANSACTION].[" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISACTIVE = CASE INVOICETRANSACTION.".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     }else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         if($this->model->getIsDelete($i, 'array') ==0 || $this->model->getIsDelete($i, 'array')==false) {
                         	$isActive=1;
                         } else {
                         	$isActive=0;
                         } 
                         $sqlLooping .= "
                         WHEN " . $this->model->getInvoiceTransactionId($i, 'array') . "
                         THEN " . $isActive . " ";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " ELSE `isDelete` END,";
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= " ELSE [isDelete] END,";
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ELSE ISDELETE END,";
                     }
				} 
               }
           }
           $sql .= substr($sqlLooping, 0, - 1);
		if ($this->getVendor() == self::MYSQL) {
               $sql .= " 
               WHERE `" . $this->model->getPrimaryKeyName() . "` IN (" . $this->model->getPrimaryKeyAll() . ")"; 
		} else if ($this->getVendor() == self::MSSQL) {
               $sql .= " 
               WHERE [" . $this->model->getPrimaryKeyName() . "] IN (" . $this->model->getPrimaryKeyAll() . ")"; 
		} else if ($this->getVendor() == self::ORACLE) {
               $sql .= " 
               WHERE " . strtoupper($this->model->getPrimaryKeyName()) . "  IN (" . $this->model->getPrimaryKeyAll() . ")"; 
		}  else { 
               echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
               exit(); 
		} 
       $this->q->setPrimaryKeyAll($this->model->getPrimaryKeyAll());
       $this->q->setMultiId(1);
       try {
           $this->q->update($sql);
       } catch (\Exception $e) {
           $this->q->rollback();
           echo json_encode(array("success" => false, "message" => $e->getMessage()));
           exit();
       }
		$this->q->commit(); 
		if ($this->getIsAdmin()) { 
               $message = $this->t['updateRecordTextLabel']; 
		} else {
               $message = $this->t['deleteRecordTextLabel']; 
		} 
		$end = microtime(true); 
		$time = $end - $start; 
		echo json_encode( 
               array(  "success" =>  true, 
                       "message" =>  $message, 
                       "time"    =>  $time) 
           ); 
       exit(); 
	} 
	/** 
	 * To check if a key duplicate or not 
	 */ 
	function duplicate() {
       header('Content-Type:application/json; charset=utf-8'); 
       $start = microtime(true);
       if ($this->getVendor() == self::MYSQL) { 
           $sql = "SET NAMES utf8"; 
           try {
               $this->q->fast($sql);
           } catch (\Exception $e) {
               echo json_encode(array("success" => false, "message" => $e->getMessage()));
               exit();
           }
       } 
       $sql=null;
       if ($this->getVendor() == self::MYSQL) { 
           $sql = " 
           SELECT  `invoiceTransactionCode` 
           FROM    `invoicetransaction` 
           WHERE   `invoiceTransactionCode` 	= 	'" . $this->model->getInvoiceTransactionCode() . "' 
           AND     `isActive`  =   1
           AND     `companyId` =   '".$this->getCompanyId()."'"; 
       } else if ($this->getVendor() == self::MSSQL) { 
           $sql = " 
           SELECT  [invoiceTransactionCode] 
           FROM    [invoiceTransaction] 
           WHERE   [invoiceTransactionCode] = 	'" . $this->model->getInvoiceTransactionCode() . "' 
           AND     [isActive]  =   1 
           AND     [companyId] =	'".$this->getCompanyId()."'"; 
       } else if ($this->getVendor() == self::ORACLE) { 
           $sql = " 
               SELECT  INVOICETRANSACTIONCODE as \"invoiceTransactionCode\" 
               FROM    INVOICETRANSACTION 
               WHERE   INVOICETRANSACTIONCODE	= 	'" . $this->model->getInvoiceTransactionCode() . "' 
               AND     ISACTIVE    =   1 
               AND     COMPANYID   =   '".$this->getCompanyId()."'"; 
       } 
       try {
           $this->q->read($sql);
       } catch (\Exception $e) {
           echo json_encode(array("success" => false, "message" => $e->getMessage()));
           exit();
       }
       $total = intval($this->q->numberRows()); 
       if ($total > 0) { 
           $row = $this->q->fetchArray(); 
           $end = microtime(true); 
           $time = $end - $start; 
           echo json_encode(
               array(  "success" =>true, 
                       "total" => $total, 
                       "message" => $this->t['duplicateMessageLabel'],  
                       "referenceNo" => $row ['referenceNo'], 
                       "time"=>$time)); 
           exit(); 
       } else { 
           $end = microtime(true); 
           $time = $end - $start; 
           echo json_encode( 
               array(  "success" => true, 
                       "total" => $total,  
                       "message" => $this->t['duplicateNotMessageLabel'], 
                       "time"=>$time)); 
           exit(); 
       } 
	} 
   /**
   * First Record
   * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
   * @return int
   * @throws \Exception
   */
   function firstRecord($value) {
       return $this->recordSet->firstRecord($value);
   }
   /**
   * Next Record
   * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
   * @param int $primaryKeyValue Current  Primary Key Value
   * @return int
   * @throws \Exception
   */
   function nextRecord($value, $primaryKeyValue) {
       return $this->recordSet->nextRecord($value, $primaryKeyValue);
   }
   /**
   * Previous Record
   * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
   * @param int $primaryKeyValue
   * @return int
   * @throws \Exception
   */
   function previousRecord($value, $primaryKeyValue) {
       return $this->recordSet->previousRecord($value, $primaryKeyValue);
   }
   /**
   * Last Record
   * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
   * @return int
   * @throws \Exception
   */
   function lastRecord($value) {
       return $this->recordSet->lastRecord($value);
   }
  /**
   * Set Service
   * @param string $service . Reset service either option,html,table
   * @return mixed
   */
   function setService($service) {
       return $this->service->setServiceOutput($service);
   }
	/** 
	 * Return  Country 
    * @return null|string
	 */
	public function getCountry() { 
       $this->service->setServiceOutput($this->getServiceOutput());
		return $this->service->getCountry();  
	}
	/** 
	 * Return  Invoice 
    * @return null|string
	 */
	public function getInvoice() { 
       $this->service->setServiceOutput($this->getServiceOutput());
		return $this->service->getInvoice();  
	}
	/** 
	 * Return  ChartOfAccount 
    * @return null|string
	 */
	public function getChartOfAccount() { 
       $this->service->setServiceOutput($this->getServiceOutput());
		return $this->service->getChartOfAccount();  
	}
  /**
   * Return Total Record Of The  
   * return int Total Record
   */
 private function  getTotalRecord() {
   $sql=null;
   $total=0;
     if($this->getVendor()==self::MYSQL) { 
         $sql="
         SELECT  count(*) AS `total` 
         FROM    `invoiceTransaction`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
     } else if ($this->getVendor()==self::MSSQL){ 
         $sql="
         SELECT    COUNT(*) AS total 
         FROM      [invoiceTransaction]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId(). " ";
     } else if ($this->getVendor()==self::ORACLE){ 
         $sql="
         SELECT    COUNT(*)    AS  \"total\" 
         FROM      INVOICETRANSACTION
         WHERE     ISACTIVE    =   1
         AND       COMPANYID   =   " . $this->getCompanyId() . " ";
     }
       try {
           $result= $this->q->fast($sql);
       } catch (\Exception $e) {
           echo json_encode(array("success" => false, "message" => $e->getMessage()));
           exit();
       }
         if($result) {
             if($this->q->numberRows($result) > 0 ) {
             $row = $this->q->fetchArray($result); 
                 $total =$row['total'];
             }
         }          return $total;
   }
	/** 
	 * Reporting
	 * @see config::excel() 
	 */
	function excel() { 
       header('Content-Type:application/json; charset=utf-8'); 
       $start = microtime(true); 
       if ($this->getVendor() == self::MYSQL) { 
           $sql = "SET NAMES utf8"; 
           $this->q->fast($sql); 
       } 
       if ($_SESSION ['start'] == 0) { 
           $sql = str_replace($_SESSION ['start'] . "," . $_SESSION ['limit'], "", str_replace("LIMIT", "", $_SESSION ['sql'])); 
       } else { 
           $sql = $_SESSION ['sql']; 
       } 
       try {
           $this->q->read($sql);
       } catch (\Exception $e) {
           echo json_encode(array("success" => false, "message" => $e->getMessage()));
           exit();
       }
       $username =null; 
       if(isset($_SESSION['username'])) { 
           $username=$_SESSION['username']; 
       } else {  
           $username='Who the fuck are you'; 
       } 
       $this->excel->getProperties() 
                   ->setCreator($username) 
                   ->setLastModifiedBy($username) 
                   ->setTitle($this->getReportTitle()) 
                   ->setSubject('invoiceTransaction')
                   ->setDescription('Generated by PhpExcel an Idcms Generator') 
                   ->setKeywords('office 2007 openxml php') 
                   ->setCategory('financial/accountReceivable'); 
        $this->excel->setActiveSheetIndex(0); 
       // check file exist or not and return response 
       $styleThinBlackBorderOutline = array('borders' => array('inside' => array('style' => \PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => '000000')), 'outline' => array('style' => \PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => '000000')))); 
       // header all using  3 line  starting b 
        $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(TRUE); 
        $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(TRUE); 
        $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(TRUE); 
        $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(TRUE); 
        $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(TRUE); 
        $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(TRUE); 
        $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(TRUE); 
        $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(TRUE); 
        $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(TRUE); 
        $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(TRUE); 
        $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(TRUE); 
        $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(TRUE); 
        $this->excel->getActiveSheet()->setCellValue('B2',$this->getReportTitle()); 
        $this->excel->getActiveSheet()->setCellValue('M2', ''); 
        $this->excel->getActiveSheet()->mergeCells('B2:M2'); 
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.'); 
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['countryIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['invoiceIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['chartOfAccountIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['journalNumberLabel']); 
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['invoiceTransactionPrincipalAmountLabel']); 
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['invoiceTransactionInterestAmountLabel']); 
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['invoiceTransactionCoupunRateAmountLabel']); 
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['invoiceTransactionTaxAmountLabel']); 
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['invoiceTransactionAmountLabel']); 
        $this->excel->getActiveSheet()->setCellValue('L3', $this->translate['executeByLabel']); 
        $this->excel->getActiveSheet()->setCellValue('M3', $this->translate['executeTimeLabel']); 
		// 
        $loopRow = 4; 
        $i = 0; 
        \PHPExcel_Cell::setValueBinder( new \PHPExcel_Cell_AdvancedValueBinder() );
        $lastRow=null;
        while (($row = $this->q->fetchAssoc()) == TRUE) { 
           //	echo print_r($row); 
           $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i); 
           $this->excel->getActiveSheet()->setCellValue('C' . $loopRow,   strip_tags($row ['countryDescription'])); 
           $this->excel->getActiveSheet()->setCellValue('D' . $loopRow,   strip_tags($row ['invoiceDescription'])); 
           $this->excel->getActiveSheet()->setCellValue('E' . $loopRow,   strip_tags($row ['chartOfAccountTitle'])); 
           $this->excel->getActiveSheet()->setCellValue('F' . $loopRow,   strip_tags($row ['journalNumber'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('G' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('G' . $loopRow,   strip_tags($row ['invoiceTransactionPrincipalAmount'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('H' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('H' . $loopRow,   strip_tags($row ['invoiceTransactionInterestAmount'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('I' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('I' . $loopRow,   strip_tags($row ['invoiceTransactionCoupunRateAmount'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('J' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('J' . $loopRow,   strip_tags($row ['invoiceTransactionTaxAmount'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('K' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('K' . $loopRow,   strip_tags($row ['invoiceTransactionAmount'])); 
           $this->excel->getActiveSheet()->setCellValue('L' . $loopRow,  strip_tags( $row ['staffName'])); 
           $this->excel->getActiveSheet()->setCellValue('M' . $loopRow,   strip_tags($row ['executeTime'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('M' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);  
           $loopRow++; 
           $lastRow = 'M' . $loopRow;
        } 
        $from = 'B2'; 
        $to = $lastRow; 
        $formula = $from . ":" . $to;
        $this->excel->getActiveSheet()->getStyle($formula)->applyFromArray($styleThinBlackBorderOutline);
        $extension=null;         $folder=null;         switch($this->getReportMode()) { 
           case 'excel':
               //	$objWriter = \PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
               //optional lock.on request only
               // $objPHPExcel->getSecurity()->setLockWindows(true);
               // $objPHPExcel->getSecurity()->setLockStructure(true);
               // $objPHPExcel->getSecurity()->setWorkbookPassword('PHPExcel');
               $objWriter = new \PHPExcel_Writer_Excel2007($this->excel); 
               $extension='.xlsx';
               $folder='excel';
               $filename = "invoiceTransaction" . rand(0, 10000000) . $extension;
               $path = $this->getFakeDocumentRoot() . "v3/financial/accountReceivable/document/".$folder."/" . $filename;
               $this->documentTrail->createTrail($this->getLeafId(), $path,$filename);
               $objWriter->save($path);
               $file = fopen($path, 'r');
               if ($file) { 
                   $end = microtime(true);
                   $time = $end - $start;
                   echo json_encode(
                       array(  "success" => true, 
                               "message" => $this->t['fileGenerateMessageLabel'], 
                               "filename" => $filename,
                               "folder" => $folder,
                               "time"=>$time));
			            exit(); 
               } else { 
                   $end = microtime(true);
                   $time = $end - $start;
                   echo json_encode(
                   array(	"success" => false,
                           "message" => $this->t['fileNotGenerateMessageLabel'],
                           "time"=>$time));
			        exit(); 
               } 
           break;
           case 'excel5':
               $objWriter = new \PHPExcel_Writer_Excel5($this->excel); 
               $extension='.xls';
               $folder='excel';
               $filename = "invoiceTransaction" . rand(0, 10000000) . $extension;
               $path = $this->getFakeDocumentRoot() . "v3/financial/accountReceivable/document/".$folder."/" . $filename;
               $this->documentTrail->createTrail($this->getLeafId(), $path,$filename);
               $objWriter->save($path);
               $file = fopen($path, 'r');
               if ($file) { 
                   $end = microtime(true);
                   $time = $end - $start;
                   echo json_encode(
                       array(  "success" => true, 
                               "message" => $this->t['fileGenerateMessageLabel'], 
                               "filename" => $filename,
                               "folder" => $folder,
                               "time"=>$time));
			        exit(); 
               } else { 
                   $end = microtime(true);
                   $time = $end - $start;
                   echo json_encode(
                       array(	"success" => false,
                               "message" => $this->t['fileNotGenerateMessageLabel'],
                               "time"=>$time));
			            exit(); 
               } 
           break;
           case 'pdf':
           break;
           case 'html':
               $objWriter = new \PHPExcel_Writer_HTML($this->excel); 
               // $objWriter->setUseBOM(true); 
               $extension='.html';
               //$objWriter->setPreCalculateFormulas(false); //calculation off 
               $folder='html';
               $filename = "invoiceTransaction" . rand(0, 10000000) . $extension;
               $path = $this->getFakeDocumentRoot() . "v3/financial/accountReceivable/document/".$folder."/" . $filename;
               $this->documentTrail->createTrail($this->getLeafId(), $path,$filename);
               $objWriter->save($path);
               $file = fopen($path, 'r');
               if ($file) { 
                   $end = microtime(true);
                   $time = $end - $start;
                   echo json_encode(
                    array( "success" => true, 
                            "message" => $this->t['fileGenerateMessageLabel'], 
                           "filename" => $filename,
                           "folder" => $folder,
                           "time"=>$time));
			        exit(); 
               } else { 
                   $end = microtime(true);
                   $time = $end - $start;
                   echo json_encode(
                       array(	"success" => false,
                               "message" => $this->t['fileNotGenerateMessageLabel'],
                               "time"=>$time));
			            exit(); 
               } 
           break;
           case 'csv': 
               $objWriter = new \PHPExcel_Writer_CSV($this->excel); 
               // $objWriter->setUseBOM(true); 
               // $objWriter->setPreCalculateFormulas(false); //calculation off 
               $extension='.csv';
               $folder='excel';
               $filename = "invoiceTransaction" . rand(0, 10000000) . $extension;
               $path = $this->getFakeDocumentRoot() . "v3/financial/accountReceivable/document/".$folder."/" . $filename;
               $this->documentTrail->createTrail($this->getLeafId(), $path,$filename);
               $objWriter->save($path);
               $file = fopen($path, 'r');
               if ($file) { 
                   $end = microtime(true);
                   $time = $end - $start;
                   echo json_encode(
                       array(  "success" => true, 
                               "message" => $this->t['fileGenerateMessageLabel'], 
                               "filename" => $filename,
                               "folder" => $folder,
                               "time"=>$time));
			        exit(); 
               } else { 
                   $end = microtime(true);
                   $time = $end - $start;
                   echo json_encode(
                       array(	"success" => false,
                               "message" => $this->t['fileNotGenerateMessageLabel'],
                               "time"=>$time));
			                exit(); 
               } 
           break;
       } 
     } 
} 
if (isset($_POST ['method'])) { 
    if(isset($_POST['output'])) {  
   $invoiceTransactionObject = new InvoiceTransactionClass (); 
	if($_POST['securityToken'] != $invoiceTransactionObject->getSecurityToken()) {
		header('Content-Type:application/json; charset=utf-8');
		echo json_encode(array("success"=>false,"message"=>"Something wrong with the system.Hola hackers"));
		exit();
	}
	/* 
	 *  Load the dynamic value 
	 */ 
	if (isset($_POST ['leafId'])) {
		$invoiceTransactionObject->setLeafId($_POST ['leafId']); 
	} 
	if (isset($_POST ['offset'])) {
		$invoiceTransactionObject->setStart($_POST ['offset']); 
	} 
	if (isset($_POST ['limit'])) {
		$invoiceTransactionObject->setLimit($_POST ['limit']); 
	} 
	$invoiceTransactionObject ->setPageOutput($_POST['output']);  
	$invoiceTransactionObject->execute(); 
	/* 
	 *  Crud Operation (Create Read Update Delete/Destroy) 
	 */ 
	if ($_POST ['method'] == 'create') { 
		$invoiceTransactionObject->create(); 
	} 
	if ($_POST ['method'] == 'save') { 
		$invoiceTransactionObject->update(); 
	} 
	if ($_POST ['method'] == 'read') { 
		$invoiceTransactionObject->read(); 
	} 
	if ($_POST ['method'] == 'delete') { 
		$invoiceTransactionObject->delete(); 
	} 
	if ($_POST ['method'] == 'posting') { 
	//	$invoiceTransactionObject->posting(); 
	} 
	if ($_POST ['method'] == 'reverse') { 
	//	$invoiceTransactionObject->delete(); 
	} 
} } 
if (isset($_GET ['method'])) {
   $invoiceTransactionObject = new InvoiceTransactionClass (); 
	if($_GET['securityToken'] != $invoiceTransactionObject->getSecurityToken()) {
		header('Content-Type:application/json; charset=utf-8');
		echo json_encode(array("success"=>false,"message"=>"Something wrong with the system.Hola hackers"));
		exit();
	}
	/* 
	 *  initialize Value before load in the loader
	 */ 
	if (isset($_GET ['leafId'])) {
       $invoiceTransactionObject->setLeafId($_GET ['leafId']); 
	} 
	/*
	 *  Load the dynamic value
	 */ 
	$invoiceTransactionObject->execute(); 
	/*
	 * Update Status of The Table. Admin Level Only 
	 */
	if ($_GET ['method'] == 'updateStatus') { 
       $invoiceTransactionObject->updateStatus(); 
	} 
	/* 
	 *  Checking Any Duplication  Key 
	 */ 
	if ($_GET['method'] == 'duplicate') { 
   	$invoiceTransactionObject->duplicate(); 
	} 
	if ($_GET ['method'] == 'dataNavigationRequest') { 
       if ($_GET ['dataNavigation'] == 'firstRecord') { 
           $invoiceTransactionObject->firstRecord('json'); 
       } 
       if ($_GET ['dataNavigation'] == 'previousRecord') { 
           $invoiceTransactionObject->previousRecord('json', 0); 
       } 
       if ($_GET ['dataNavigation'] == 'nextRecord') {
           $invoiceTransactionObject->nextRecord('json', 0); 
       } 
       if ($_GET ['dataNavigation'] == 'lastRecord') {
           $invoiceTransactionObject->lastRecord('json'); 
       } 
	} 
	/* 
	 * Excel Reporting  
	 */ 
	if (isset($_GET ['mode'])) { 
       $invoiceTransactionObject->setReportMode($_GET['mode']); 
       if ($_GET ['mode'] == 'excel'
            ||  $_GET ['mode'] == 'pdf'
			||  $_GET['mode']=='csv'
			||  $_GET['mode']=='html'
			||	$_GET['mode']=='excel5'
			||  $_GET['mode']=='xml') { 
			$invoiceTransactionObject->excel(); 
		} 
	} 
	if (isset($_GET ['filter'])) { 
       $invoiceTransactionObject->setServiceOutput('option');
       if(($_GET['filter']=='country')) { 
           $invoiceTransactionObject->getCountry(); 
       }
       if(($_GET['filter']=='invoice')) { 
           $invoiceTransactionObject->getInvoice(); 
       }
       if(($_GET['filter']=='chartOfAccount')) { 
           $invoiceTransactionObject->getChartOfAccount(); 
       }
   }
} 
?>