<?php namespace Core\Financial\AccountReceivable\InvoiceLedger\Controller; 
use Core\ConfigClass;
use Core\Financial\AccountReceivable\InvoiceLedger\Model\InvoiceLedgerModel;
use Core\Financial\AccountReceivable\InvoiceLedger\Service\InvoiceLedgerService;
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
require_once ($newFakeDocumentRoot."v3/financial/accountReceivable/model/invoiceLedgerModel.php"); 
require_once ($newFakeDocumentRoot."v3/financial/accountReceivable/service/invoiceLedgerService.php"); 
/** 
 * Class InvoiceLedger
 * this is invoiceLedger controller files. 
 * @name IDCMS 
 * @version 2 
 * @author hafizan 
 * @package  Core\Financial\AccountReceivable\InvoiceLedger\Controller 
 * @subpackage AccountReceivable 
 * @link http://www.hafizan.com 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL 
 */ 
class InvoiceLedgerClass extends ConfigClass { 
	/** 
	 * Connection to the database 
	 * @var \Core\Database\Mysql\Vendor 
	 */ 
	public $q; 
	/** 
	 * Php Word Generate Microsoft Excel 2007 Output.Format : docxs 
	 * @var \PHPWord 
	 */ 
	//private $word; 
	/** 
	 * Php Excel Generate Microsoft Excel 2007 Output.Format : xlsx/pdf 
	 * @var \PHPExcel 
	 */ 
	private $excel; 
	/** 
	 * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx 
	 * @var \PHPPowerPoint 
	 */ 
	//private $powerPoint; 
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
	 * @var \Core\Financial\AccountReceivable\InvoiceLedger\Model\InvoiceLedgerModel 
	 */ 
	public $model; 
	/** 
	 * Service-Business Application Process or other ajax request 
	 * @var \Core\Financial\AccountReceivable\InvoiceLedger\Service\InvoiceLedgerService 
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
       $this->setViewPath("./v3/financial/accountReceivable/view/invoiceLedger.php"); 
       $this->setControllerPath("./v3/financial/accountReceivable/controller/invoiceLedgerController.php");
       $this->setServicePath("./v3/financial/accountReceivable/service/invoiceLedgerService.php"); 
   } 
	/** 
	 * Class Loader 
	 */ 
	function execute() { 
       parent::__construct(); 
       $this->setAudit(1); 
       $this->setLog(1); 
       $this->model  = new InvoiceLedgerModel(); 
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

       $this->service  = new InvoiceLedgerService(); 
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
       if(!$this->model->getBusinessPartnerId()){
           $this->model->setBusinessPartnerId($this->service->getBusinessPartnerDefaultValue());
       }
       if(!$this->model->getChartOfAccountId()){
           $this->model->setChartOfAccountId($this->service->getChartOfAccountDefaultValue());
       }
       if(!$this->model->getInvoiceProjectId()){
           $this->model->setInvoiceProjectId($this->service->getInvoiceProjectDefaultValue());
       }
       if(!$this->model->getInvoiceId()){
           $this->model->setInvoiceId($this->service->getInvoiceDefaultValue());
       }
       if(!$this->model->getInvoiceDebitNoteId()){
           $this->model->setInvoiceDebitNoteId($this->service->getInvoiceDebitNoteDefaultValue());
       }
       if(!$this->model->getInvoiceCreditNoteId()){
           $this->model->setInvoiceCreditNoteId($this->service->getInvoiceCreditNoteDefaultValue());
       }
       if(!$this->model->getCollectionId()){
           $this->model->setCollectionId($this->service->getCollectionDefaultValue());
       }
        //$this->model->setDocumentNumber($this->getDocumentNumber());
       if ($this->getVendor() == self::MYSQL) {  
       $sql="
            INSERT INTO `invoiceledger` 
            (
                 `companyId`,
                 `businessPartnerId`,
                 `chartOfAccountId`,
                 `invoiceProjectId`,
                 `invoiceId`,
                 `invoiceDebitNoteId`,
                 `invoiceCreditNoteId`,
                 `collectionId`,
                 `documentNumber`,
                 `invoiceLedgerDate`,
                 `invoiceDueDate`,
                 `invoiceLedgerAmount`,
                 `invoiceLedgerDescription`,
                 `leafId`,
                 `leafName`,
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
                 '".$this->model->getBusinessPartnerId()."',
                 '".$this->model->getChartOfAccountId()."',
                 '".$this->model->getInvoiceProjectId()."',
                 '".$this->model->getInvoiceId()."',
                 '".$this->model->getInvoiceDebitNoteId()."',
                 '".$this->model->getInvoiceCreditNoteId()."',
                 '".$this->model->getCollectionId()."',
                 '".$this->model->getDocumentNumber()."',
                 '".$this->model->getInvoiceLedgerDate()."',
                 '".$this->model->getInvoiceDueDate()."',
                 '".$this->model->getInvoiceLedgerAmount()."',
                 '".$this->model->getInvoiceLedgerDescription()."',
                 '".$this->model->getLeafId()."',
                 '".$this->model->getLeafName()."',
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
            INSERT INTO [invoiceLedger] 
            (
                 [invoiceLedgerId],
                 [companyId],
                 [businessPartnerId],
                 [chartOfAccountId],
                 [invoiceProjectId],
                 [invoiceId],
                 [invoiceDebitNoteId],
                 [invoiceCreditNoteId],
                 [collectionId],
                 [documentNumber],
                 [invoiceLedgerDate],
                 [invoiceDueDate],
                 [invoiceLedgerAmount],
                 [invoiceLedgerDescription],
                 [leafId],
                 [leafName],
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
                 '".$this->model->getBusinessPartnerId()."',
                 '".$this->model->getChartOfAccountId()."',
                 '".$this->model->getInvoiceProjectId()."',
                 '".$this->model->getInvoiceId()."',
                 '".$this->model->getInvoiceDebitNoteId()."',
                 '".$this->model->getInvoiceCreditNoteId()."',
                 '".$this->model->getCollectionId()."',
                 '".$this->model->getDocumentNumber()."',
                 '".$this->model->getInvoiceLedgerDate()."',
                 '".$this->model->getInvoiceDueDate()."',
                 '".$this->model->getInvoiceLedgerAmount()."',
                 '".$this->model->getInvoiceLedgerDescription()."',
                 '".$this->model->getLeafId()."',
                 '".$this->model->getLeafName()."',
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
            INSERT INTO INVOICELEDGER 
            (
                 COMPANYID,
                 BUSINESSPARTNERID,
                 CHARTOFACCOUNTID,
                 INVOICEPROJECTID,
                 INVOICEID,
                 INVOICEDEBITNOTEID,
                 INVOICECREDITNOTEID,
                 COLLECTIONID,
                 DOCUMENTNUMBER,
                 INVOICELEDGERDATE,
                 INVOICEDUEDATE,
                 INVOICELEDGERAMOUNT,
                 INVOICELEDGERDESCRIPTION,
                 LEAFID,
                 LEAFNAME,
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
                 '".$this->model->getBusinessPartnerId()."',
                 '".$this->model->getChartOfAccountId()."',
                 '".$this->model->getInvoiceProjectId()."',
                 '".$this->model->getInvoiceId()."',
                 '".$this->model->getInvoiceDebitNoteId()."',
                 '".$this->model->getInvoiceCreditNoteId()."',
                 '".$this->model->getCollectionId()."',
                 '".$this->model->getDocumentNumber()."',
                 '".$this->model->getInvoiceLedgerDate()."',
                 '".$this->model->getInvoiceDueDate()."',
                 '".$this->model->getInvoiceLedgerAmount()."',
                 '".$this->model->getInvoiceLedgerDescription()."',
                 '".$this->model->getLeafId()."',
                 '".$this->model->getLeafName()."',
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
       $invoiceLedgerId = $this->q->lastInsertId(); 
       $this->q->commit(); 
       $end = microtime(true); 
       $time = $end - $start; 
       echo json_encode( 
           array(	"success" => true, 
                   "message" => $this->t['newRecordTextLabel'],  
                   "staffName" => $_SESSION['staffName'],  
                   "executeTime" =>date('d-m-Y H:i:s'),  
                   "totalRecord"=>$this->getTotalRecord(),
                   "invoiceLedgerId" => $invoiceLedgerId,
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
                   $this->setAuditFilter(" `invoiceledger`.`isActive` = 1  AND `invoiceledger`.`companyId`='".$this->getCompanyId()."' "); 
               } else if ($this->getVendor() == self::MSSQL) { 
                   $this->setAuditFilter(" [invoiceLedger].[isActive] = 1 AND [invoiceLedger].[companyId]='".$this->getCompanyId()."' "); 
               } else if ($this->getVendor() == self::ORACLE) { 
                   $this->setAuditFilter(" INVOICELEDGER.ISACTIVE = 1  AND INVOICELEDGER.COMPANYID='".$this->getCompanyId()."'"); 
               } 
           } else if ($_SESSION['isAdmin'] == 1) { 
               if ($this->getVendor() == self::MYSQL) { 
                   $this->setAuditFilter("   `invoiceledger`.`companyId`='".$this->getCompanyId()."'	"); 
               } else if ($this->getVendor() == self::MSSQL) { 
                   $this->setAuditFilter(" [invoiceLedger].[companyId]='".$this->getCompanyId()."' "); 
               } else if ($this->getVendor() == self::ORACLE) { 
                   $this->setAuditFilter(" INVOICELEDGER.COMPANYID='".$this->getCompanyId()."' "); 
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
       SELECT                    `invoiceledger`.`invoiceLedgerId`,
                    `company`.`companyDescription`,
                    `invoiceledger`.`companyId`,
                    `businesspartner`.`businessPartnerCompany`,
                    `invoiceledger`.`businessPartnerId`,
					`chartofaccount`.`chartOfAccountNumber`,
                    `chartofaccount`.`chartOfAccountTitle`,
                    `invoiceledger`.`chartOfAccountId`,
                    `invoiceledger`.`invoiceProjectId`,
                    `invoiceledger`.`invoiceId`,
                    `invoiceledger`.`invoiceDebitNoteId`,
                    `invoiceledger`.`invoiceCreditNoteId`,
                    `invoiceledger`.`collectionId`,
                    `invoiceledger`.`documentNumber`,
                    `invoiceledger`.`invoiceLedgerDate`,
                    `invoiceledger`.`invoiceDueDate`,
                    `invoiceledger`.`invoiceLedgerAmount`,
                    `invoiceledger`.`invoiceLedgerDescription`,
                    `invoiceledger`.`leafId`,
                    `invoiceledger`.`leafName`,
                    `invoiceledger`.`isDefault`,
                    `invoiceledger`.`isNew`,
                    `invoiceledger`.`isDraft`,
                    `invoiceledger`.`isUpdate`,
                    `invoiceledger`.`isDelete`,
                    `invoiceledger`.`isActive`,
                    `invoiceledger`.`isApproved`,
                    `invoiceledger`.`isReview`,
                    `invoiceledger`.`isPost`,
                    `invoiceledger`.`executeBy`,
                    `invoiceledger`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `invoiceledger`
		  JOIN      `staff`
		  ON        `invoiceledger`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `invoiceledger`.`companyId`
	JOIN	`businesspartner`
	ON		`businesspartner`.`businessPartnerId` = `invoiceledger`.`businessPartnerId`
	JOIN	`chartofaccount`
	ON		`chartofaccount`.`chartOfAccountId` = `invoiceledger`.`chartOfAccountId`
	LEFT JOIN	`invoiceproject`
	ON		`invoiceproject`.`invoiceProjectId` = `invoiceledger`.`invoiceProjectId`
	LEFT JOIN	`invoice`
	ON		`invoice`.`invoiceId` = `invoiceledger`.`invoiceId`
	LEFT JOIN	`invoicedebitnote`
	ON		`invoicedebitnote`.`invoiceDebitNoteId` = `invoiceledger`.`invoiceDebitNoteId`
	LEFT JOIN	`invoicecreditnote`
	ON		`invoicecreditnote`.`invoiceCreditNoteId` = `invoiceledger`.`invoiceCreditNoteId`
	LEFT JOIN	`collection`
	ON		`collection`.`collectionId` = `invoiceledger`.`collectionId`
	
		  WHERE     " . $this->getAuditFilter(); 
       if ($this->model->getInvoiceLedgerId(0, 'single')) { 
           $sql .= " AND `invoiceledger`.`" . $this->model->getPrimaryKeyName() . "`='" . $this->model->getInvoiceLedgerId(0, 'single') . "'";  
       }
       if ($this->model->getBusinessPartnerId()) { 
           $sql .= " AND `invoiceledger`.`businessPartnerId`='".$this->model->getBusinessPartnerId()."'";  
       }
       if ($this->model->getChartOfAccountId()) { 
           $sql .= " AND `invoiceledger`.`chartOfAccountId`='".$this->model->getChartOfAccountId()."'";  
       }
	   // speciial filter bank
	   $sql.=" AND  `invoiceledger`.`chartOfAccountId` NOT IN (SELECT chartOfAccountId FROM bank WHERE companyId='".$this->getCompanyId()."')";
 } else if ($this->getVendor() == self::MSSQL) {  

		  $sql = "
		  SELECT                    [invoiceLedger].[invoiceLedgerId],
                    [company].[companyDescription],
                    [invoiceLedger].[companyId],
                    [businessPartner].[businessPartnerCompany],
                    [invoiceLedger].[businessPartnerId],
                    [chartOfAccount].[chartOfAccountTitle],
                    [invoiceLedger].[chartOfAccountId],
                    [invoiceLedger].[invoiceProjectId],
                    [invoiceLedger].[invoiceId],
                    [invoiceLedger].[invoiceDebitNoteId],
                    [invoiceLedger].[invoiceCreditNoteId],
                    [invoiceLedger].[collectionId],
                    [invoiceLedger].[documentNumber],
                    [invoiceLedger].[invoiceLedgerDate],
                    [invoiceLedger].[invoiceDueDate],
                    [invoiceLedger].[invoiceLedgerAmount],
                    [invoiceLedger].[invoiceLedgerDescription],
                    [invoiceLedger].[leafId],
                    [invoiceLedger].[leafName],
                    [invoiceLedger].[isDefault],
                    [invoiceLedger].[isNew],
                    [invoiceLedger].[isDraft],
                    [invoiceLedger].[isUpdate],
                    [invoiceLedger].[isDelete],
                    [invoiceLedger].[isActive],
                    [invoiceLedger].[isApproved],
                    [invoiceLedger].[isReview],
                    [invoiceLedger].[isPost],
                    [invoiceLedger].[executeBy],
                    [invoiceLedger].[executeTime],
                    [staff].[staffName] 
		  FROM 	[invoiceLedger]
		  JOIN	[staff]
		  ON	[invoiceLedger].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [invoiceLedger].[companyId]
	JOIN	[businessPartner]
	ON		[businessPartner].[businessPartnerId] = [invoiceLedger].[businessPartnerId]
	JOIN	[chartOfAccount]
	ON		[chartOfAccount].[chartOfAccountId] = [invoiceLedger].[chartOfAccountId]
		  WHERE     " . $this->getAuditFilter(); 
       if ($this->model->getInvoiceLedgerId(0, 'single')) { 
           $sql .= " AND [invoiceLedger].[" . $this->model->getPrimaryKeyName() . "]		=	'" . $this->model->getInvoiceLedgerId(0, 'single') . "'"; 
       } 
       if ($this->model->getBusinessPartnerId()) { 
           $sql .= " AND [invoiceLedger].[businessPartnerId]='".$this->model->getBusinessPartnerId()."'";  
       }
       if ($this->model->getChartOfAccountId()) { 
           $sql .= " AND [invoiceLedger].[chartOfAccountId]='".$this->model->getChartOfAccountId()."'";  
       }
		} else if ($this->getVendor() == self::ORACLE) {  

		  $sql = "
		  SELECT                    INVOICELEDGER.INVOICELEDGERID AS \"invoiceLedgerId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    INVOICELEDGER.COMPANYID AS \"companyId\",
                    BUSINESSPARTNER.BUSINESSPARTNERCOMPANY AS  \"businessPartnerCompany\",
                    INVOICELEDGER.BUSINESSPARTNERID AS \"businessPartnerId\",
                    CHARTOFACCOUNT.CHARTOFACCOUNTTITLE AS  \"chartOfAccountTitle\",
                    INVOICELEDGER.CHARTOFACCOUNTID AS \"chartOfAccountId\",
                    INVOICELEDGER.INVOICEPROJECTID AS \"invoiceProjectId\",
                    INVOICELEDGER.INVOICEID AS \"invoiceId\",
                    INVOICELEDGER.INVOICEDEBITNOTEID AS \"invoiceDebitNoteId\",
                    INVOICELEDGER.INVOICECREDITNOTEID AS \"invoiceCreditNoteId\",
                    INVOICELEDGER.COLLECTIONID AS \"collectionId\",
                    INVOICELEDGER.DOCUMENTNUMBER AS \"documentNumber\",
                    INVOICELEDGER.INVOICELEDGERDATE AS \"invoiceLedgerDate\",
                    INVOICELEDGER.INVOICEDUEDATE AS \"invoiceDueDate\",
                    INVOICELEDGER.INVOICELEDGERAMOUNT AS \"invoiceLedgerAmount\",
                    INVOICELEDGER.INVOICELEDGERDESCRIPTION AS \"invoiceLedgerDescription\",
                    INVOICELEDGER.LEAFID AS \"leafId\",
                    INVOICELEDGER.LEAFNAME AS \"leafName\",
                    INVOICELEDGER.ISDEFAULT AS \"isDefault\",
                    INVOICELEDGER.ISNEW AS \"isNew\",
                    INVOICELEDGER.ISDRAFT AS \"isDraft\",
                    INVOICELEDGER.ISUPDATE AS \"isUpdate\",
                    INVOICELEDGER.ISDELETE AS \"isDelete\",
                    INVOICELEDGER.ISACTIVE AS \"isActive\",
                    INVOICELEDGER.ISAPPROVED AS \"isApproved\",
                    INVOICELEDGER.ISREVIEW AS \"isReview\",
                    INVOICELEDGER.ISPOST AS \"isPost\",
                    INVOICELEDGER.EXECUTEBY AS \"executeBy\",
                    INVOICELEDGER.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\" 
		  FROM 	INVOICELEDGER 
		  JOIN	STAFF 
		  ON	INVOICELEDGER.EXECUTEBY = STAFF.STAFFID 
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = INVOICELEDGER.COMPANYID
	JOIN	BUSINESSPARTNER
	ON		BUSINESSPARTNER.BUSINESSPARTNERID = INVOICELEDGER.BUSINESSPARTNERID
	JOIN	CHARTOFACCOUNT
	ON		CHARTOFACCOUNT.CHARTOFACCOUNTID = INVOICELEDGER.CHARTOFACCOUNTID
         WHERE     " . $this->getAuditFilter(); 
           if ($this->model->getInvoiceLedgerId(0, 'single'))  {
               $sql .= " AND INVOICELEDGER. ".strtoupper($this->model->getPrimaryKeyName()) . "='" . $this->model->getInvoiceLedgerId(0, 'single') . "'"; 
           } 
       if ($this->model->getBusinessPartnerId()) { 
           $sql .= " AND INVOICELEDGER.BUSINESSPARTNERID='".$this->model->getBusinessPartnerId()."'";  
       }
       if ($this->model->getChartOfAccountId()) { 
           $sql .= " AND INVOICELEDGER.CHARTOFACCOUNTID='".$this->model->getChartOfAccountId()."'";  
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
                   $sql.=" AND `invoiceledger`.`".$this->model->getFilterCharacter()."` like '".$this->getCharacterQuery()."%'"; 
               } else if($this->getVendor()==self::MSSQL){ 
                   $sql.=" AND [invoiceLedger].[".$this->model->getFilterCharacter()."] like '".$this->getCharacterQuery()."%'"; 
               } else if ($this->getVendor()==self::ORACLE){ 
                   $sql.=" AND Initcap(INVOICELEDGER.".strtoupper($this->model->getFilterCharacter()).") LIKE Initcap('".$this->getCharacterQuery()."%')"; 
               }
		} 
		/** 
		 * filter column based on Range Of Date 
		 * Example Day,Week,Month,Year 
		 */ 
		if($this->getDateRangeStartQuery()){ 
               if($this->getVendor()==self::MYSQL){ 
                   $sql.=$this->q->dateFilter('invoiceledger',$this->model->getFilterDate(),$this->getDateRangeStartQuery(),$this->getDateRangeEndQuery(),$this->getDateRangeTypeQuery(),$this->getDateRangeExtraTypeQuery()); 
               } else if($this->getVendor()==self::MSSQL){ 
                   $sql.=$this->q->dateFilter('invoiceLedger',$this->model->getFilterDate(),$this->getDateRangeStartQuery(),$this->getDateRangeEndQuery(),$this->getDateRangeTypeQuery(),$this->getDateRangeExtraTypeQuery()); 
               } else if ($this->getVendor()==self::ORACLE){ 
                   $sql.=$this->q->dateFilter('INVOICELEDGER',$this->model->getFilterDate(),$this->getDateRangeStartQuery(),$this->getDateRangeEndQuery(),$this->getDateRangeTypeQuery(),$this->getDateRangeExtraTypeQuery()); 
               }
           } 
		/** 
		 * filter column don't want to filter.Example may contain  sensitive information or unwanted to be search. 
		 * E.g  $filterArray=array('`leaf`.`leafId`'); 
		 * @variables $filterArray; 
		 */  
        $filterArray =null;        if($this->getVendor() ==self::MYSQL) { 
		    $filterArray = array("`invoiceledger`.`invoiceLedgerId`",
                                              "`staff`.`staffPassword`"); 
        } else if ($this->getVendor() == self::MSSQL) {
 		    $filterArray = array("[invoiceledger].[invoiceLedgerId]",
                                              "[staff].[staffPassword]"); 
        } else if ($this->getVendor() == self::ORACLE) { 
		    $filterArray = array("INVOICELEDGER.INVOICELEDGERID",
                                              "STAFF.STAFFPASSWORD"); 
        }
		$tableArray = null; 
		if($this->getVendor()==self::MYSQL){ 
			$tableArray = array('staff','invoiceledger','company','businesspartner','chartofaccount'); 
		} else if($this->getVendor()==self::MSSQL){ 
			$tableArray = array('staff','invoiceledger','company','businesspartner','chartofaccount'); 
		} else if ($this->getVendor()==self::ORACLE){ 
			$tableArray = array('STAFF','INVOICELEDGER','COMPANY','BUSINESSPARTNER','CHARTOFACCOUNT'); 
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
				$sqlDerived  = $sql." LIMIT  " . $this->getStart() . "," . $this->getLimit() . " "; 
			if ($this->getVendor() == self::MYSQL) { 
			} else if ($this->getVendor() == self::MSSQL) { 
				/** 
				 * Sql Server  2012 format only.Row Number
				 * Parameter Query We don't support 
				 */; 
              $sqlDerived = $sql . " 	OFFSET  	" . $this->getStart() . " ROWS
											FETCH NEXT 	" . $this->getLimit() . " ROWS ONLY "; 
			 } else if ($this->getVendor() == self::ORACLE) { 
				/** 
				 * Oracle using derived table also 
				 **/ 

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
		if (!($this->model->getInvoiceLedgerId(0, 'single'))) { 
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
               $row['counter'] = $this->getStart() + 27; 
               if ($this->model->getInvoiceLedgerId(0, 'single')) { 
                   $row['firstRecord'] = $this->firstRecord('value'); 
                   $row['previousRecord'] = $this->previousRecord('value', $this->model->getInvoiceLedgerId(0, 'single')); 
                   $row['nextRecord'] = $this->nextRecord('value', $this->model->getInvoiceLedgerId(0, 'single')); 
                   $row['lastRecord'] = $this->lastRecord('value'); 
               }  
               $items [] = $row; 
               $i++; 
		}  
		if ($this->getPageOutput() == 'html') { 
               return $items; 
           } else if ($this->getPageOutput() == 'json') { 
           if ($this->model->getInvoiceLedgerId(0, 'single')) { 
               $end = microtime(true); 
               $time = $end - $start; 
               echo str_replace(array("[","]"),"",json_encode(array( 
                   'success' => true,  
                   'total' => $total,  
                   'message' => $this->t['viewRecordMessageLabel'],  
                   'time' => $time,  
                   'firstRecord' => $this->firstRecord('value'),  
                   'previousRecord' => $this->previousRecord('value', $this->model->getInvoiceLedgerId(0, 'single')),  
                   'nextRecord' => $this->nextRecord('value', $this->model->getInvoiceLedgerId(0, 'single')),  
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
                   'previousRecord' => $this->recordSet->previousRecord('value', $this->model->getInvoiceLedgerId(0, 'single')),  
                   'nextRecord' => $this->recordSet->nextRecord('value', $this->model->getInvoiceLedgerId(0, 'single')),  
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

   } 
	/** 
    * Delete
	 * @see config::delete() 
	 */ 
   function delete() { 

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
	 * Return  BusinessPartner 
    * @return null|string
	 */
	public function getBusinessPartner() { 
       $this->service->setServiceOutput($this->getServiceOutput());
		return $this->service->getBusinessPartner();  
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
                   ->setSubject('invoiceLedger')
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
        $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(TRUE); 
        $this->excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(TRUE); 
        $this->excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(TRUE); 
        $this->excel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(TRUE); 
        $this->excel->getActiveSheet()->getColumnDimension('R')->setAutoSize(TRUE); 
        $this->excel->getActiveSheet()->setCellValue('B2',$this->getReportTitle()); 
        $this->excel->getActiveSheet()->setCellValue('R2', ''); 
        $this->excel->getActiveSheet()->mergeCells('B2:R2'); 
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.'); 
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['businessPartnerIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['chartOfAccountIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['invoiceProjectIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['invoiceIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['invoiceDebitNoteIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['invoiceCreditNoteIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['collectionIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['documentNumberLabel']); 
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['invoiceLedgerDateLabel']); 
        $this->excel->getActiveSheet()->setCellValue('L3', $this->translate['invoiceDueDateLabel']); 
        $this->excel->getActiveSheet()->setCellValue('M3', $this->translate['invoiceLedgerAmountLabel']); 
        $this->excel->getActiveSheet()->setCellValue('N3', $this->translate['invoiceLedgerDescriptionLabel']); 
        $this->excel->getActiveSheet()->setCellValue('O3', $this->translate['leafIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('P3', $this->translate['leafNameLabel']); 
        $this->excel->getActiveSheet()->setCellValue('Q3', $this->translate['executeByLabel']); 
        $this->excel->getActiveSheet()->setCellValue('R3', $this->translate['executeTimeLabel']); 
		// 
        $loopRow = 4; 
        $i = 0; 
        \PHPExcel_Cell::setValueBinder( new \PHPExcel_Cell_AdvancedValueBinder() );
        $lastRow=null;
        while (($row = $this->q->fetchAssoc()) == TRUE) { 
           //	echo print_r($row); 
           $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i); 
           $this->excel->getActiveSheet()->setCellValue('C' . $loopRow,   strip_tags($row ['businessPartnerCompany'])); 
           $this->excel->getActiveSheet()->setCellValue('D' . $loopRow,   strip_tags($row ['chartOfAccountTitle'])); 
           $this->excel->getActiveSheet()->setCellValue('E' . $loopRow,   strip_tags($row ['invoiceProjectDescription'])); 
           $this->excel->getActiveSheet()->setCellValue('F' . $loopRow,   strip_tags($row ['invoiceDescription'])); 
           $this->excel->getActiveSheet()->setCellValue('G' . $loopRow,   strip_tags($row ['invoiceDebitNoteDescription'])); 
           $this->excel->getActiveSheet()->setCellValue('H' . $loopRow,   strip_tags($row ['invoiceCreditNoteDescription'])); 
           $this->excel->getActiveSheet()->setCellValue('I' . $loopRow,   strip_tags($row ['collectionDescription'])); 
           $this->excel->getActiveSheet()->setCellValue('J' . $loopRow,   strip_tags($row ['documentNumber'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('K' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);  
           $this->excel->getActiveSheet()->setCellValue('K' . $loopRow,   strip_tags($row ['invoiceLedgerDate'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('L' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);  
           $this->excel->getActiveSheet()->setCellValue('L' . $loopRow,   strip_tags($row ['invoiceDueDate'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('M' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('M' . $loopRow,   strip_tags($row ['invoiceLedgerAmount'])); 
           $this->excel->getActiveSheet()->setCellValue('N' . $loopRow,   strip_tags($row ['invoiceLedgerDescription'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('O' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('O' . $loopRow,   strip_tags($row ['leafId'])); 
           $this->excel->getActiveSheet()->setCellValue('P' . $loopRow,   strip_tags($row ['leafName'])); 
           $this->excel->getActiveSheet()->setCellValue('Q' . $loopRow,  strip_tags( $row ['staffName'])); 
           $this->excel->getActiveSheet()->setCellValue('R' . $loopRow,   strip_tags($row ['executeTime'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('R' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);  
           $loopRow++; 
           $lastRow = 'R' . $loopRow;
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
               $filename = "invoiceLedger" . rand(0, 10000000) . $extension;
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
               $filename = "invoiceLedger" . rand(0, 10000000) . $extension;
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
               $filename = "invoiceLedger" . rand(0, 10000000) . $extension;
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
               $filename = "invoiceLedger" . rand(0, 10000000) . $extension;
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
   $invoiceLedgerObject = new InvoiceLedgerClass (); 
	if($_POST['securityToken'] != $invoiceLedgerObject->getSecurityToken()) {
		header('Content-Type:application/json; charset=utf-8');
		echo json_encode(array("success"=>false,"message"=>"Something wrong with the system.Hola hackers"));
		exit();
	}
	/* 
	 *  Load the dynamic value 
	 */ 
	if (isset($_POST ['leafId'])) {
		$invoiceLedgerObject->setLeafId($_POST ['leafId']); 
	} 
	if (isset($_POST ['offset'])) {
		$invoiceLedgerObject->setStart($_POST ['offset']); 
	} 
	if (isset($_POST ['limit'])) {
		$invoiceLedgerObject->setLimit($_POST ['limit']); 
	} 
	$invoiceLedgerObject ->setPageOutput($_POST['output']);  
	$invoiceLedgerObject->execute(); 
	/* 
	 *  Crud Operation (Create Read Update Delete/Destroy) 
	 */ 
	if ($_POST ['method'] == 'create') { 
		$invoiceLedgerObject->create(); 
	} 
	if ($_POST ['method'] == 'save') { 
		$invoiceLedgerObject->update(); 
	} 
	if ($_POST ['method'] == 'read') { 
		$invoiceLedgerObject->read(); 
	} 
	if ($_POST ['method'] == 'delete') { 
		$invoiceLedgerObject->delete(); 
	} 
	if ($_POST ['method'] == 'posting') { 
	//	$invoiceLedgerObject->posting(); 
	} 
	if ($_POST ['method'] == 'reverse') { 
	//	$invoiceLedgerObject->delete(); 
	} 
} } 
if (isset($_GET ['method'])) {
   $invoiceLedgerObject = new InvoiceLedgerClass (); 
	if($_GET['securityToken'] != $invoiceLedgerObject->getSecurityToken()) {
		header('Content-Type:application/json; charset=utf-8');
		echo json_encode(array("success"=>false,"message"=>"Something wrong with the system.Hola hackers"));
		exit();
	}
	/* 
	 *  initialize Value before load in the loader
	 */ 
	if (isset($_GET ['leafId'])) {
       $invoiceLedgerObject->setLeafId($_GET ['leafId']); 
	} 
	/*
	 *  Load the dynamic value
	 */ 
	$invoiceLedgerObject->execute(); 
	/*
	 * Update Status of The Table. Admin Level Only 
	 */
	if ($_GET ['method'] == 'updateStatus') { 
       $invoiceLedgerObject->updateStatus(); 
	} 
	/* 
	 *  Checking Any Duplication  Key 
	 */ 
	if ($_GET['method'] == 'duplicate') { 
   	$invoiceLedgerObject->duplicate(); 
	} 
	if ($_GET ['method'] == 'dataNavigationRequest') { 
       if ($_GET ['dataNavigation'] == 'firstRecord') { 
           $invoiceLedgerObject->firstRecord('json'); 
       } 
       if ($_GET ['dataNavigation'] == 'previousRecord') { 
           $invoiceLedgerObject->previousRecord('json', 0); 
       } 
       if ($_GET ['dataNavigation'] == 'nextRecord') {
           $invoiceLedgerObject->nextRecord('json', 0); 
       } 
       if ($_GET ['dataNavigation'] == 'lastRecord') {
           $invoiceLedgerObject->lastRecord('json'); 
       } 
	} 
	/* 
	 * Excel Reporting  
	 */ 
	if (isset($_GET ['mode'])) { 
       $invoiceLedgerObject->setReportMode($_GET['mode']); 
       if ($_GET ['mode'] == 'excel'
            ||  $_GET ['mode'] == 'pdf'
			||  $_GET['mode']=='csv'
			||  $_GET['mode']=='html'
			||	$_GET['mode']=='excel5'
			||  $_GET['mode']=='xml') { 
			$invoiceLedgerObject->excel(); 
		} 
	} 
	if (isset($_GET ['filter'])) { 
       $invoiceLedgerObject->setServiceOutput('option');
       if(($_GET['filter']=='businessPartner')) { 
           $invoiceLedgerObject->getBusinessPartner(); 
       }
       if(($_GET['filter']=='chartOfAccount')) { 
           $invoiceLedgerObject->getChartOfAccount(); 
       }
   }
} 
?>
