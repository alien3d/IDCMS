<?php namespace Core\Financial\GeneralLedger\GeneralLedger\Controller; 
use Core\ConfigClass;
use Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel;
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
require_once ($newFakeDocumentRoot."v3/financial/generalLedger/model/generalLedgerModel.php"); 
/** 
 * Class GeneralLedger
 * this is generalLedger controller files. 
 * @name IDCMS 
 * @version 2 
 * @author hafizan 
 * @package  Core\Financial\GeneralLedger\GeneralLedger\Controller 
 * @subpackage GeneralLedger 
 * @link http://www.hafizan.com 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL 
 */ 
class GeneralLedgerClass extends ConfigClass { 
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
	 * @var \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel 
	 */ 
	public $model; 
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
       $this->setViewPath("./v3/financial/generalLedger/view/generalLedger.php"); 
       $this->setControllerPath("./v3/financial/generalLedger/controller/generalLedgerController.php");
   } 
	/** 
	 * Class Loader 
	 */ 
	function execute() { 
       parent::__construct(); 
       $this->setAudit(1); 
       $this->setLog(1); 
       $this->model  = new GeneralLedgerModel(); 
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
        //$this->model->setDocumentNumber($this->getDocumentNumber());
       if ($this->getVendor() == self::MYSQL) {  
       $sql="
            INSERT INTO `generalledger` 
            (
                 `companyId`,
                 `financeYearId`,
                 `financeYearYear`,
                 `financePeriodRangeId`,
                 `financePeriodRangePeriod`,
                 `journalNumber`,
                 `documentNumber`,
                 `documentDate`,
                 `generalLedgerTitle`,
                 `generalLedgerDescription`,
                 `generalLedgerDate`,
                 `countryId`,
                 `countryCurrencyCode`,
                 `transactionTypeId`,
                 `transactionTypeCode`,
                 `transactionTypeDescription`,
                 `foreignAmount`,
                 `localAmount`,
                 `chartOfAccountCategoryId`,
                 `chartOfAccountCategoryCode`,
                 `chartOfAccountCategoryDescription`,
                 `chartOfAccountTypeId`,
                 `chartOfAccountTypeCode`,
                 `chartOfAccountTypeDescription`,
                 `chartOfAccountId`,
                 `chartOfAccountNumber`,
                 `chartOfAccountDescription`,
                 `businessPartnerId`,
                 `businessPartnerDescription`,
                 `module`,
                 `tableName`,
                 `tableNameId`,
                 `tableNameDetail`,
                 `tableNameDetailId`,
                 `leafId`,
                 `leafName`,
                 `from`,
                 `isDefault`,
                 `isNew`,
                 `isDraft`,
                 `isUpdate`,
                 `isDelete`,
                 `isActive`,
                 `isApproved`,
                 `isReview`,
                 `isPost`,
                 `isMerge`,
                 `isSlice`,
                 `isAuthorized`,
                 `executeBy`,
                 `executeName`,
                 `executeTime`
       ) VALUES ( 
                 '".$this->getCompanyId()."',
                 '".$this->model->getFinanceYearId()."',
                 '".$this->model->getFinanceYearYear()."',
                 '".$this->model->getFinancePeriodRangeId()."',
                 '".$this->model->getFinancePeriodRangePeriod()."',
                 '".$this->model->getJournalNumber()."',
                 '".$this->model->getDocumentNumber()."',
                 '".$this->model->getDocumentDate()."',
                 '".$this->model->getGeneralLedgerTitle()."',
                 '".$this->model->getGeneralLedgerDescription()."',
                 '".$this->model->getGeneralLedgerDate()."',
                 '".$this->model->getCountryId()."',
                 '".$this->model->getCountryCurrencyCode()."',
                 '".$this->model->getTransactionTypeId()."',
                 '".$this->model->getTransactionTypeCode()."',
                 '".$this->model->getTransactionTypeDescription()."',
                 '".$this->model->getForeignAmount()."',
                 '".$this->model->getLocalAmount()."',
                 '".$this->model->getChartOfAccountCategoryId()."',
                 '".$this->model->getChartOfAccountCategoryCode()."',
                 '".$this->model->getChartOfAccountCategoryDescription()."',
                 '".$this->model->getChartOfAccountTypeId()."',
                 '".$this->model->getChartOfAccountTypeCode()."',
                 '".$this->model->getChartOfAccountTypeDescription()."',
                 '".$this->model->getChartOfAccountId()."',
                 '".$this->model->getChartOfAccountNumber()."',
                 '".$this->model->getChartOfAccountDescription()."',
                 '".$this->model->getBusinessPartnerId()."',
                 '".$this->model->getBusinessPartnerDescription()."',
                 '".$this->model->getModule()."',
                 '".$this->model->getTableName()."',
                 '".$this->model->getTableNameId()."',
                 '".$this->model->getTableNameDetail()."',
                 '".$this->model->getTableNameDetailId()."',
                 '".$this->model->getLeafId()."',
                 '".$this->model->getLeafName()."',
                 '".$this->model->getFrom()."',
                 '".$this->model->getIsDefault(0, 'single')."',
                 '".$this->model->getIsNew(0, 'single')."',
                 '".$this->model->getIsDraft(0, 'single')."',
                 '".$this->model->getIsUpdate(0, 'single')."',
                 '".$this->model->getIsDelete(0, 'single')."',
                 '".$this->model->getIsActive(0, 'single')."',
                 '".$this->model->getIsApproved(0, 'single')."',
                 '".$this->model->getIsReview(0, 'single')."',
                 '".$this->model->getIsPost(0, 'single')."',
                 '".$this->model->getIsMerge()."',
                 '".$this->model->getIsSlice(0, 'single')."',
                 '".$this->model->getIsAuthorized()."',
                 '".$this->model->getExecuteBy()."',
                 '".$this->model->getExecuteName()."',
                 ".$this->model->getExecuteTime()."
       );";
		 } else if ($this->getVendor() == self::MSSQL) {  
       $sql="
            INSERT INTO [generalLedger] 
            (
                 [generalLedgerId],
                 [companyId],
                 [financeYearId],
                 [financeYearYear],
                 [financePeriodRangeId],
                 [financePeriodRangePeriod],
                 [journalNumber],
                 [documentNumber],
                 [documentDate],
                 [generalLedgerTitle],
                 [generalLedgerDescription],
                 [generalLedgerDate],
                 [countryId],
                 [countryCurrencyCode],
                 [transactionTypeId],
                 [transactionTypeCode],
                 [transactionTypeDescription],
                 [foreignAmount],
                 [localAmount],
                 [chartOfAccountCategoryId],
                 [chartOfAccountCategoryCode],
                 [chartOfAccountCategoryDescription],
                 [chartOfAccountTypeId],
                 [chartOfAccountTypeCode],
                 [chartOfAccountTypeDescription],
                 [chartOfAccountId],
                 [chartOfAccountNumber],
                 [chartOfAccountDescription],
                 [businessPartnerId],
                 [businessPartnerDescription],
                 [module],
                 [tableName],
                 [tableNameId],
                 [tableNameDetail],
                 [tableNameDetailId],
                 [leafId],
                 [leafName],
                 [from],
                 [isDefault],
                 [isNew],
                 [isDraft],
                 [isUpdate],
                 [isDelete],
                 [isActive],
                 [isApproved],
                 [isReview],
                 [isPost],
                 [isMerge],
                 [isSlice],
                 [isAuthorized],
                 [executeBy],
                 [executeName],
                 [executeTime]
) VALUES ( 
                 '".$this->getCompanyId()."',
                 '".$this->model->getFinanceYearId()."',
                 '".$this->model->getFinanceYearYear()."',
                 '".$this->model->getFinancePeriodRangeId()."',
                 '".$this->model->getFinancePeriodRangePeriod()."',
                 '".$this->model->getJournalNumber()."',
                 '".$this->model->getDocumentNumber()."',
                 '".$this->model->getDocumentDate()."',
                 '".$this->model->getGeneralLedgerTitle()."',
                 '".$this->model->getGeneralLedgerDescription()."',
                 '".$this->model->getGeneralLedgerDate()."',
                 '".$this->model->getCountryId()."',
                 '".$this->model->getCountryCurrencyCode()."',
                 '".$this->model->getTransactionTypeId()."',
                 '".$this->model->getTransactionTypeCode()."',
                 '".$this->model->getTransactionTypeDescription()."',
                 '".$this->model->getForeignAmount()."',
                 '".$this->model->getLocalAmount()."',
                 '".$this->model->getChartOfAccountCategoryId()."',
                 '".$this->model->getChartOfAccountCategoryCode()."',
                 '".$this->model->getChartOfAccountCategoryDescription()."',
                 '".$this->model->getChartOfAccountTypeId()."',
                 '".$this->model->getChartOfAccountTypeCode()."',
                 '".$this->model->getChartOfAccountTypeDescription()."',
                 '".$this->model->getChartOfAccountId()."',
                 '".$this->model->getChartOfAccountNumber()."',
                 '".$this->model->getChartOfAccountDescription()."',
                 '".$this->model->getBusinessPartnerId()."',
                 '".$this->model->getBusinessPartnerDescription()."',
                 '".$this->model->getModule()."',
                 '".$this->model->getTableName()."',
                 '".$this->model->getTableNameId()."',
                 '".$this->model->getTableNameDetail()."',
                 '".$this->model->getTableNameDetailId()."',
                 '".$this->model->getLeafId()."',
                 '".$this->model->getLeafName()."',
                 '".$this->model->getFrom()."',
                 '".$this->model->getIsDefault(0, 'single')."',
                 '".$this->model->getIsNew(0, 'single')."',
                 '".$this->model->getIsDraft(0, 'single')."',
                 '".$this->model->getIsUpdate(0, 'single')."',
                 '".$this->model->getIsDelete(0, 'single')."',
                 '".$this->model->getIsActive(0, 'single')."',
                 '".$this->model->getIsApproved(0, 'single')."',
                 '".$this->model->getIsReview(0, 'single')."',
                 '".$this->model->getIsPost(0, 'single')."',
                 '".$this->model->getIsMerge()."',
                 '".$this->model->getIsSlice(0, 'single')."',
                 '".$this->model->getIsAuthorized()."',
                 '".$this->model->getExecuteBy()."',
                 '".$this->model->getExecuteName()."',
                 ".$this->model->getExecuteTime()."
            );";
       } else if ($this->getVendor() == self::ORACLE) {  
            $sql="
            INSERT INTO GENERALLEDGER 
            (
                 COMPANYID,
                 FINANCEYEARID,
                 FINANCEYEARYEAR,
                 FINANCEPERIODRANGEID,
                 FINANCEPERIODRANGEPERIOD,
                 JOURNALNUMBER,
                 DOCUMENTNUMBER,
                 DOCUMENTDATE,
                 GENERALLEDGERTITLE,
                 GENERALLEDGERDESCRIPTION,
                 GENERALLEDGERDATE,
                 COUNTRYID,
                 COUNTRYCURRENCYCODE,
                 TRANSACTIONTYPEID,
                 TRANSACTIONTYPECODE,
                 TRANSACTIONTYPEDESCRIPTION,
                 FOREIGNAMOUNT,
                 LOCALAMOUNT,
                 CHARTOFACCOUNTCATEGORYID,
                 CHARTOFACCOUNTCATEGORYCODE,
                 CHARTOFACCOUNTCATEGORYDESCRIPTION,
                 CHARTOFACCOUNTTYPEID,
                 CHARTOFACCOUNTTYPECODE,
                 CHARTOFACCOUNTTYPEDESCRIPTION,
                 CHARTOFACCOUNTID,
                 CHARTOFACCOUNTNUMBER,
                 CHARTOFACCOUNTDESCRIPTION,
                 BUSINESSPARTNERID,
                 BUSINESSPARTNERDESCRIPTION,
                 MODULE,
                 TABLENAME,
                 TABLENAMEID,
                 TABLENAMEDETAIL,
                 TABLENAMEDETAILID,
                 LEAFID,
                 LEAFNAME,
                 FROM,
                 ISDEFAULT,
                 ISNEW,
                 ISDRAFT,
                 ISUPDATE,
                 ISDELETE,
                 ISACTIVE,
                 ISAPPROVED,
                 ISREVIEW,
                 ISPOST,
                 ISMERGE,
                 ISSLICE,
                 ISAUTHORIZED,
                 EXECUTEBY,
                 EXECUTENAME,
                 EXECUTETIME
            ) VALUES ( 
                 '".$this->getCompanyId()."',
                 '".$this->model->getFinanceYearId()."',
                 '".$this->model->getFinanceYearYear()."',
                 '".$this->model->getFinancePeriodRangeId()."',
                 '".$this->model->getFinancePeriodRangePeriod()."',
                 '".$this->model->getJournalNumber()."',
                 '".$this->model->getDocumentNumber()."',
                 '".$this->model->getDocumentDate()."',
                 '".$this->model->getGeneralLedgerTitle()."',
                 '".$this->model->getGeneralLedgerDescription()."',
                 '".$this->model->getGeneralLedgerDate()."',
                 '".$this->model->getCountryId()."',
                 '".$this->model->getCountryCurrencyCode()."',
                 '".$this->model->getTransactionTypeId()."',
                 '".$this->model->getTransactionTypeCode()."',
                 '".$this->model->getTransactionTypeDescription()."',
                 '".$this->model->getForeignAmount()."',
                 '".$this->model->getLocalAmount()."',
                 '".$this->model->getChartOfAccountCategoryId()."',
                 '".$this->model->getChartOfAccountCategoryCode()."',
                 '".$this->model->getChartOfAccountCategoryDescription()."',
                 '".$this->model->getChartOfAccountTypeId()."',
                 '".$this->model->getChartOfAccountTypeCode()."',
                 '".$this->model->getChartOfAccountTypeDescription()."',
                 '".$this->model->getChartOfAccountId()."',
                 '".$this->model->getChartOfAccountNumber()."',
                 '".$this->model->getChartOfAccountDescription()."',
                 '".$this->model->getBusinessPartnerId()."',
                 '".$this->model->getBusinessPartnerDescription()."',
                 '".$this->model->getModule()."',
                 '".$this->model->getTableName()."',
                 '".$this->model->getTableNameId()."',
                 '".$this->model->getTableNameDetail()."',
                 '".$this->model->getTableNameDetailId()."',
                 '".$this->model->getLeafId()."',
                 '".$this->model->getLeafName()."',
                 '".$this->model->getFrom()."',
                 '".$this->model->getIsDefault(0, 'single')."',
                 '".$this->model->getIsNew(0, 'single')."',
                 '".$this->model->getIsDraft(0, 'single')."',
                 '".$this->model->getIsUpdate(0, 'single')."',
                 '".$this->model->getIsDelete(0, 'single')."',
                 '".$this->model->getIsActive(0, 'single')."',
                 '".$this->model->getIsApproved(0, 'single')."',
                 '".$this->model->getIsReview(0, 'single')."',
                 '".$this->model->getIsPost(0, 'single')."',
                 '".$this->model->getIsMerge()."',
                 '".$this->model->getIsSlice(0, 'single')."',
                 '".$this->model->getIsAuthorized()."',
                 '".$this->model->getExecuteBy()."',
                 '".$this->model->getExecuteName()."',
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
       $generalLedgerId = $this->q->lastInsertId(); 
       $this->q->commit(); 
       $end = microtime(true); 
       $time = $end - $start; 
       echo json_encode( 
           array(	"success" => true, 
                   "message" => $this->t['newRecordTextLabel'],  
                   "staffName" => $_SESSION['staffName'],  
                   "executeTime" =>date('d-m-Y H:i:s'),  
                   "totalRecord"=>$this->getTotalRecord(),
                   "generalLedgerId" => $generalLedgerId,
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
                   $this->setAuditFilter(" `generalledger`.`isActive` = 1  AND `generalledger`.`companyId`='".$this->getCompanyId()."' "); 
               } else if ($this->getVendor() == self::MSSQL) { 
                   $this->setAuditFilter(" [generalLedger].[isActive] = 1 AND [generalLedger].[companyId]='".$this->getCompanyId()."' "); 
               } else if ($this->getVendor() == self::ORACLE) { 
                   $this->setAuditFilter(" GENERALLEDGER.ISACTIVE = 1  AND GENERALLEDGER.COMPANYID='".$this->getCompanyId()."'"); 
               } 
           } else if ($_SESSION['isAdmin'] == 1) { 
               if ($this->getVendor() == self::MYSQL) { 
                   $this->setAuditFilter("   `generalledger`.`companyId`='".$this->getCompanyId()."'	"); 
               } else if ($this->getVendor() == self::MSSQL) { 
                   $this->setAuditFilter(" [generalLedger].[companyId]='".$this->getCompanyId()."' "); 
               } else if ($this->getVendor() == self::ORACLE) { 
                   $this->setAuditFilter(" GENERALLEDGER.COMPANYID='".$this->getCompanyId()."' "); 
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
       SELECT                    `generalledger`.`generalLedgerId`,
                    `generalledger`.`companyId`,
                    `generalledger`.`financeYearId`,
                    `generalledger`.`financeYearYear`,
                    `generalledger`.`financePeriodRangeId`,
                    `generalledger`.`financePeriodRangePeriod`,
                    `generalledger`.`journalNumber`,
                    `generalledger`.`documentNumber`,
                    `generalledger`.`documentDate`,
                    `generalledger`.`generalLedgerTitle`,
                    `generalledger`.`generalLedgerDescription`,
                    `generalledger`.`generalLedgerDate`,
                    `generalledger`.`countryId`,
                    `generalledger`.`countryCurrencyCode`,
                    `generalledger`.`transactionTypeId`,
                    `generalledger`.`transactionTypeCode`,
                    `generalledger`.`transactionTypeDescription`,
                    `generalledger`.`foreignAmount`,
                    `generalledger`.`localAmount`,
                    `generalledger`.`chartOfAccountCategoryId`,
                    `generalledger`.`chartOfAccountCategoryCode`,
                    `generalledger`.`chartOfAccountCategoryDescription`,
                    `generalledger`.`chartOfAccountTypeId`,
                    `generalledger`.`chartOfAccountTypeCode`,
                    `generalledger`.`chartOfAccountTypeDescription`,
                    `generalledger`.`chartOfAccountId`,
                    `generalledger`.`chartOfAccountNumber`,
                    `generalledger`.`chartOfAccountDescription`,
                    `generalledger`.`businessPartnerId`,
                    `generalledger`.`businessPartnerDescription`,
                    `generalledger`.`module`,
                    `generalledger`.`tableName`,
                    `generalledger`.`tableNameId`,
                    `generalledger`.`tableNameDetail`,
                    `generalledger`.`tableNameDetailId`,
                    `generalledger`.`leafId`,
                    `generalledger`.`leafName`,
                    `generalledger`.`from`,
                    `generalledger`.`isDefault`,
                    `generalledger`.`isNew`,
                    `generalledger`.`isDraft`,
                    `generalledger`.`isUpdate`,
                    `generalledger`.`isDelete`,
                    `generalledger`.`isActive`,
                    `generalledger`.`isApproved`,
                    `generalledger`.`isReview`,
                    `generalledger`.`isPost`,
                    `generalledger`.`isMerge`,
                    `generalledger`.`isSlice`,
                    `generalledger`.`isAuthorized`,
                    `generalledger`.`executeBy`,
                    `generalledger`.`executeName`,
                    `generalledger`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `generalledger`
		  JOIN      `staff`
		  ON        `generalledger`.`executeBy` = `staff`.`staffId`
		  WHERE     " . $this->getAuditFilter(); 
       if ($this->model->getGeneralLedgerId(0, 'single')) { 
           $sql .= " AND `generalledger`.`" . $this->model->getPrimaryKeyName() . "`='" . $this->model->getGeneralLedgerId(0, 'single') . "'";  
       }
 } else if ($this->getVendor() == self::MSSQL) {  

		  $sql = "
		  SELECT                    [generalLedger].[generalLedgerId],
                    [generalLedger].[companyId],
                    [generalLedger].[financeYearId],
                    [generalLedger].[financeYearYear],
                    [generalLedger].[financePeriodRangeId],
                    [generalLedger].[financePeriodRangePeriod],
                    [generalLedger].[journalNumber],
                    [generalLedger].[documentNumber],
                    [generalLedger].[documentDate],
                    [generalLedger].[generalLedgerTitle],
                    [generalLedger].[generalLedgerDescription],
                    [generalLedger].[generalLedgerDate],
                    [generalLedger].[countryId],
                    [generalLedger].[countryCurrencyCode],
                    [generalLedger].[transactionTypeId],
                    [generalLedger].[transactionTypeCode],
                    [generalLedger].[transactionTypeDescription],
                    [generalLedger].[foreignAmount],
                    [generalLedger].[localAmount],
                    [generalLedger].[chartOfAccountCategoryId],
                    [generalLedger].[chartOfAccountCategoryCode],
                    [generalLedger].[chartOfAccountCategoryDescription],
                    [generalLedger].[chartOfAccountTypeId],
                    [generalLedger].[chartOfAccountTypeCode],
                    [generalLedger].[chartOfAccountTypeDescription],
                    [generalLedger].[chartOfAccountId],
                    [generalLedger].[chartOfAccountNumber],
                    [generalLedger].[chartOfAccountDescription],
                    [generalLedger].[businessPartnerId],
                    [generalLedger].[businessPartnerDescription],
                    [generalLedger].[module],
                    [generalLedger].[tableName],
                    [generalLedger].[tableNameId],
                    [generalLedger].[tableNameDetail],
                    [generalLedger].[tableNameDetailId],
                    [generalLedger].[leafId],
                    [generalLedger].[leafName],
                    [generalLedger].[from],
                    [generalLedger].[isDefault],
                    [generalLedger].[isNew],
                    [generalLedger].[isDraft],
                    [generalLedger].[isUpdate],
                    [generalLedger].[isDelete],
                    [generalLedger].[isActive],
                    [generalLedger].[isApproved],
                    [generalLedger].[isReview],
                    [generalLedger].[isPost],
                    [generalLedger].[isMerge],
                    [generalLedger].[isSlice],
                    [generalLedger].[isAuthorized],
                    [generalLedger].[executeBy],
                    [generalLedger].[executeName],
                    [generalLedger].[executeTime],
                    [staff].[staffName] 
		  FROM 	[generalLedger]
		  JOIN	[staff]
		  ON	[generalLedger].[executeBy] = [staff].[staffId]
		  WHERE     " . $this->getAuditFilter(); 
       if ($this->model->getGeneralLedgerId(0, 'single')) { 
           $sql .= " AND [generalLedger].[" . $this->model->getPrimaryKeyName() . "]		=	'" . $this->model->getGeneralLedgerId(0, 'single') . "'"; 
       } 
		} else if ($this->getVendor() == self::ORACLE) {  

		  $sql = "
		  SELECT                    GENERALLEDGER.GENERALLEDGERID AS \"generalLedgerId\",
                    GENERALLEDGER.COMPANYID AS \"companyId\",
                    GENERALLEDGER.FINANCEYEARID AS \"financeYearId\",
                    GENERALLEDGER.FINANCEYEARYEAR AS \"financeYearYear\",
                    GENERALLEDGER.FINANCEPERIODRANGEID AS \"financePeriodRangeId\",
                    GENERALLEDGER.FINANCEPERIODRANGEPERIOD AS \"financePeriodRangePeriod\",
                    GENERALLEDGER.JOURNALNUMBER AS \"journalNumber\",
                    GENERALLEDGER.DOCUMENTNUMBER AS \"documentNumber\",
                    GENERALLEDGER.DOCUMENTDATE AS \"documentDate\",
                    GENERALLEDGER.GENERALLEDGERTITLE AS \"generalLedgerTitle\",
                    GENERALLEDGER.GENERALLEDGERDESCRIPTION AS \"generalLedgerDescription\",
                    GENERALLEDGER.GENERALLEDGERDATE AS \"generalLedgerDate\",
                    GENERALLEDGER.COUNTRYID AS \"countryId\",
                    GENERALLEDGER.COUNTRYCURRENCYCODE AS \"countryCurrencyCode\",
                    GENERALLEDGER.TRANSACTIONTYPEID AS \"transactionTypeId\",
                    GENERALLEDGER.TRANSACTIONTYPECODE AS \"transactionTypeCode\",
                    GENERALLEDGER.TRANSACTIONTYPEDESCRIPTION AS \"transactionTypeDescription\",
                    GENERALLEDGER.FOREIGNAMOUNT AS \"foreignAmount\",
                    GENERALLEDGER.LOCALAMOUNT AS \"localAmount\",
                    GENERALLEDGER.CHARTOFACCOUNTCATEGORYID AS \"chartOfAccountCategoryId\",
                    GENERALLEDGER.CHARTOFACCOUNTCATEGORYCODE AS \"chartOfAccountCategoryCode\",
                    GENERALLEDGER.CHARTOFACCOUNTCATEGORYDESCRIPTION AS \"chartOfAccountCategoryDescription\",
                    GENERALLEDGER.CHARTOFACCOUNTTYPEID AS \"chartOfAccountTypeId\",
                    GENERALLEDGER.CHARTOFACCOUNTTYPECODE AS \"chartOfAccountTypeCode\",
                    GENERALLEDGER.CHARTOFACCOUNTTYPEDESCRIPTION AS \"chartOfAccountTypeDescription\",
                    GENERALLEDGER.CHARTOFACCOUNTID AS \"chartOfAccountId\",
                    GENERALLEDGER.CHARTOFACCOUNTNUMBER AS \"chartOfAccountNumber\",
                    GENERALLEDGER.CHARTOFACCOUNTDESCRIPTION AS \"chartOfAccountDescription\",
                    GENERALLEDGER.BUSINESSPARTNERID AS \"businessPartnerId\",
                    GENERALLEDGER.BUSINESSPARTNERDESCRIPTION AS \"businessPartnerDescription\",
                    GENERALLEDGER.MODULE AS \"module\",
                    GENERALLEDGER.TABLENAME AS \"tableName\",
                    GENERALLEDGER.TABLENAMEID AS \"tableNameId\",
                    GENERALLEDGER.TABLENAMEDETAIL AS \"tableNameDetail\",
                    GENERALLEDGER.TABLENAMEDETAILID AS \"tableNameDetailId\",
                    GENERALLEDGER.LEAFID AS \"leafId\",
                    GENERALLEDGER.LEAFNAME AS \"leafName\",
                    GENERALLEDGER.FROM AS \"from\",
                    GENERALLEDGER.ISDEFAULT AS \"isDefault\",
                    GENERALLEDGER.ISNEW AS \"isNew\",
                    GENERALLEDGER.ISDRAFT AS \"isDraft\",
                    GENERALLEDGER.ISUPDATE AS \"isUpdate\",
                    GENERALLEDGER.ISDELETE AS \"isDelete\",
                    GENERALLEDGER.ISACTIVE AS \"isActive\",
                    GENERALLEDGER.ISAPPROVED AS \"isApproved\",
                    GENERALLEDGER.ISREVIEW AS \"isReview\",
                    GENERALLEDGER.ISPOST AS \"isPost\",
                    GENERALLEDGER.ISMERGE AS \"isMerge\",
                    GENERALLEDGER.ISSLICE AS \"isSlice\",
                    GENERALLEDGER.ISAUTHORIZED AS \"isAuthorized\",
                    GENERALLEDGER.EXECUTEBY AS \"executeBy\",
                    GENERALLEDGER.EXECUTENAME AS \"executeName\",
                    GENERALLEDGER.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\" 
		  FROM 	GENERALLEDGER 
		  JOIN	STAFF 
		  ON	GENERALLEDGER.EXECUTEBY = STAFF.STAFFID 
          WHERE     " . $this->getAuditFilter(); 
           if ($this->model->getGeneralLedgerId(0, 'single'))  {
               $sql .= " AND GENERALLEDGER. ".strtoupper($this->model->getPrimaryKeyName()) . "='" . $this->model->getGeneralLedgerId(0, 'single') . "'"; 
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
                   $sql.=" AND `generalledger`.`".$this->model->getFilterCharacter()."` like '".$this->getCharacterQuery()."%'"; 
               } else if($this->getVendor()==self::MSSQL){ 
                   $sql.=" AND [generalLedger].[".$this->model->getFilterCharacter()."] like '".$this->getCharacterQuery()."%'"; 
               } else if ($this->getVendor()==self::ORACLE){ 
                   $sql.=" AND Initcap(GENERALLEDGER.".strtoupper($this->model->getFilterCharacter()).") LIKE Initcap('".$this->getCharacterQuery()."%')"; 
               }
		} 
		/** 
		 * filter column based on Range Of Date 
		 * Example Day,Week,Month,Year 
		 */ 
		if($this->getDateRangeStartQuery()){ 
               if($this->getVendor()==self::MYSQL){ 
                   $sql.=$this->q->dateFilter('generalledger',$this->model->getFilterDate(),$this->getDateRangeStartQuery(),$this->getDateRangeEndQuery(),$this->getDateRangeTypeQuery(),$this->getDateRangeExtraTypeQuery()); 
               } else if($this->getVendor()==self::MSSQL){ 
                   $sql.=$this->q->dateFilter('generalLedger',$this->model->getFilterDate(),$this->getDateRangeStartQuery(),$this->getDateRangeEndQuery(),$this->getDateRangeTypeQuery(),$this->getDateRangeExtraTypeQuery()); 
               } else if ($this->getVendor()==self::ORACLE){ 
                   $sql.=$this->q->dateFilter('GENERALLEDGER',$this->model->getFilterDate(),$this->getDateRangeStartQuery(),$this->getDateRangeEndQuery(),$this->getDateRangeTypeQuery(),$this->getDateRangeExtraTypeQuery()); 
               }
           } 
		/** 
		 * filter column don't want to filter.Example may contain  sensitive information or unwanted to be search. 
		 * E.g  $filterArray=array('`leaf`.`leafId`'); 
		 * @variables $filterArray; 
		 */  
        $filterArray =null;        if($this->getVendor() ==self::MYSQL) { 
		    $filterArray = array("`generalledger`.`generalLedgerId`",
                                              "`staff`.`staffPassword`"); 
        } else if ($this->getVendor() == self::MSSQL) {
 		    $filterArray = array("[generalledger].[generalLedgerId]",
                                              "[staff].[staffPassword]"); 
        } else if ($this->getVendor() == self::ORACLE) { 
		    $filterArray = array("GENERALLEDGER.GENERALLEDGERID",
                                              "STAFF.STAFFPASSWORD"); 
        }
		$tableArray = null; 
		if($this->getVendor()==self::MYSQL){ 
			$tableArray = array('staff','generalledger',); 
		} else if($this->getVendor()==self::MSSQL){ 
			$tableArray = array('staff','generalledger',); 
		} else if ($this->getVendor()==self::ORACLE){ 
			$tableArray = array('STAFF','GENERALLEDGER',); 
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
		if (!($this->model->getGeneralLedgerId(0, 'single'))) { 
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
               $row['counter'] = $this->getStart() + 53; 
               if ($this->model->getGeneralLedgerId(0, 'single')) { 
                   $row['firstRecord'] = $this->firstRecord('value'); 
                   $row['previousRecord'] = $this->previousRecord('value', $this->model->getGeneralLedgerId(0, 'single')); 
                   $row['nextRecord'] = $this->nextRecord('value', $this->model->getGeneralLedgerId(0, 'single')); 
                   $row['lastRecord'] = $this->lastRecord('value'); 
               }  
               $items [] = $row; 
               $i++; 
		}  
		if ($this->getPageOutput() == 'html') { 
               return $items; 
           } else if ($this->getPageOutput() == 'json') { 
           if ($this->model->getGeneralLedgerId(0, 'single')) { 
               $end = microtime(true); 
               $time = $end - $start; 
               echo str_replace(array("[","]"),"",json_encode(array( 
                   'success' => true,  
                   'total' => $total,  
                   'message' => $this->t['viewRecordMessageLabel'],  
                   'time' => $time,  
                   'firstRecord' => $this->firstRecord('value'),  
                   'previousRecord' => $this->previousRecord('value', $this->model->getGeneralLedgerId(0, 'single')),  
                   'nextRecord' => $this->nextRecord('value', $this->model->getGeneralLedgerId(0, 'single')),  
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
                   'previousRecord' => $this->recordSet->previousRecord('value', $this->model->getGeneralLedgerId(0, 'single')),  
                   'nextRecord' => $this->recordSet->nextRecord('value', $this->model->getGeneralLedgerId(0, 'single')),  
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
   * Return Total Record Of The  
   * return int Total Record
   */
 private function  getTotalRecord() {
   $sql=null;
   $total=0;
     if($this->getVendor()==self::MYSQL) { 
         $sql="
         SELECT  count(*) AS `total` 
         FROM    `generalLedger`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
     } else if ($this->getVendor()==self::MSSQL){ 
         $sql="
         SELECT    COUNT(*) AS total 
         FROM      [generalLedger]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId(). " ";
     } else if ($this->getVendor()==self::ORACLE){ 
         $sql="
         SELECT    COUNT(*)    AS  \"total\" 
         FROM      GENERALLEDGER
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
                   ->setSubject('generalLedger')
                   ->setDescription('Generated by PhpExcel an Idcms Generator') 
                   ->setKeywords('office 2007 openxml php') 
                   ->setCategory('financial/generalLedger'); 
        $this->excel->setActiveSheetIndex(0); 
       // check file exist or not and return response 
       $styleThinBlackBorderOutline = array('borders' => array('inside' => array('style' => \PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => '000000')), 'outline' => array('style' => \PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => '000000')))); 
       // header all using  3 line  starting b 
        $this->excel->getActiveSheet()->getColumnDimension('0')->setAutoSize(TRUE); 
        $this->excel->getActiveSheet()->setCellValue('B2',$this->getReportTitle()); 
        $this->excel->getActiveSheet()->setCellValue('2', ''); 
        $this->excel->getActiveSheet()->mergeCells('B2:2'); 
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.'); 
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['financeYearIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['financeYearYearLabel']); 
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['financePeriodRangeIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['financePeriodRangePeriodLabel']); 
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['journalNumberLabel']); 
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['documentNumberLabel']); 
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['documentDateLabel']); 
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['generalLedgerTitleLabel']); 
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['generalLedgerDescriptionLabel']); 
        $this->excel->getActiveSheet()->setCellValue('L3', $this->translate['generalLedgerDateLabel']); 
        $this->excel->getActiveSheet()->setCellValue('M3', $this->translate['countryIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('N3', $this->translate['countryCurrencyCodeLabel']); 
        $this->excel->getActiveSheet()->setCellValue('O3', $this->translate['transactionTypeIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('P3', $this->translate['transactionTypeCodeLabel']); 
        $this->excel->getActiveSheet()->setCellValue('Q3', $this->translate['transactionTypeDescriptionLabel']); 
        $this->excel->getActiveSheet()->setCellValue('R3', $this->translate['foreignAmountLabel']); 
        $this->excel->getActiveSheet()->setCellValue('S3', $this->translate['localAmountLabel']); 
        $this->excel->getActiveSheet()->setCellValue('T3', $this->translate['chartOfAccountCategoryIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('U3', $this->translate['chartOfAccountCategoryCodeLabel']); 
        $this->excel->getActiveSheet()->setCellValue('V3', $this->translate['chartOfAccountCategoryDescriptionLabel']); 
        $this->excel->getActiveSheet()->setCellValue('W3', $this->translate['chartOfAccountTypeIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('X3', $this->translate['chartOfAccountTypeCodeLabel']); 
        $this->excel->getActiveSheet()->setCellValue('Y3', $this->translate['chartOfAccountTypeDescriptionLabel']); 
        $this->excel->getActiveSheet()->setCellValue('Z3', $this->translate['chartOfAccountIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['chartOfAccountNumberLabel']); 
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['chartOfAccountDescriptionLabel']); 
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['businessPartnerIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['businessPartnerDescriptionLabel']); 
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['moduleLabel']); 
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['tableNameLabel']); 
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['tableNameIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['tableNameDetailLabel']); 
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['tableNameDetailIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['leafIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['leafNameLabel']); 
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['fromLabel']); 
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['isMergeLabel']); 
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['isAuthorizedLabel']); 
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['executeByLabel']); 
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['executeNameLabel']); 
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['executeTimeLabel']); 
		// 
        $loopRow = 4; 
        $i = 0; 
        \PHPExcel_Cell::setValueBinder( new \PHPExcel_Cell_AdvancedValueBinder() );
        $lastRow=null;
        while (($row = $this->q->fetchAssoc()) == TRUE) { 
           //	echo print_r($row); 
           $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('C' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('C' . $loopRow,   strip_tags($row ['financeYearId'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('D' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('D' . $loopRow,   strip_tags($row ['financeYearYear'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('E' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('E' . $loopRow,   strip_tags($row ['financePeriodRangeId'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('F' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('F' . $loopRow,   strip_tags($row ['financePeriodRangePeriod'])); 
           $this->excel->getActiveSheet()->setCellValue('G' . $loopRow,   strip_tags($row ['journalNumber'])); 
           $this->excel->getActiveSheet()->setCellValue('H' . $loopRow,   strip_tags($row ['documentNumber'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('I' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);  
           $this->excel->getActiveSheet()->setCellValue('I' . $loopRow,   strip_tags($row ['documentDate'])); 
           $this->excel->getActiveSheet()->setCellValue('J' . $loopRow,   strip_tags($row ['generalLedgerTitle'])); 
           $this->excel->getActiveSheet()->setCellValue('K' . $loopRow,   strip_tags($row ['generalLedgerDescription'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('L' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);  
           $this->excel->getActiveSheet()->setCellValue('L' . $loopRow,   strip_tags($row ['generalLedgerDate'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('M' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('M' . $loopRow,   strip_tags($row ['countryId'])); 
           $this->excel->getActiveSheet()->setCellValue('N' . $loopRow,   strip_tags($row ['countryCurrencyCode'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('O' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('O' . $loopRow,   strip_tags($row ['transactionTypeId'])); 
           $this->excel->getActiveSheet()->setCellValue('P' . $loopRow,   strip_tags($row ['transactionTypeCode'])); 
           $this->excel->getActiveSheet()->setCellValue('Q' . $loopRow,   strip_tags($row ['transactionTypeDescription'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('R' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('R' . $loopRow,   strip_tags($row ['foreignAmount'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('S' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('S' . $loopRow,   strip_tags($row ['localAmount'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('T' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('T' . $loopRow,   strip_tags($row ['chartOfAccountCategoryId'])); 
           $this->excel->getActiveSheet()->setCellValue('U' . $loopRow,   strip_tags($row ['chartOfAccountCategoryCode'])); 
           $this->excel->getActiveSheet()->setCellValue('V' . $loopRow,   strip_tags($row ['chartOfAccountCategoryDescription'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('W' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('W' . $loopRow,   strip_tags($row ['chartOfAccountTypeId'])); 
           $this->excel->getActiveSheet()->setCellValue('X' . $loopRow,   strip_tags($row ['chartOfAccountTypeCode'])); 
           $this->excel->getActiveSheet()->setCellValue('Y' . $loopRow,   strip_tags($row ['chartOfAccountTypeDescription'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('Z' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('Z' . $loopRow,   strip_tags($row ['chartOfAccountId'])); 
           $this->excel->getActiveSheet()->setCellValue('' . $loopRow,   strip_tags($row ['chartOfAccountNumber'])); 
           $this->excel->getActiveSheet()->setCellValue('' . $loopRow,   strip_tags($row ['chartOfAccountDescription'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('' . $loopRow,   strip_tags($row ['businessPartnerId'])); 
           $this->excel->getActiveSheet()->setCellValue('' . $loopRow,   strip_tags($row ['businessPartnerDescription'])); 
           $this->excel->getActiveSheet()->setCellValue('' . $loopRow,   strip_tags($row ['module'])); 
           $this->excel->getActiveSheet()->setCellValue('' . $loopRow,   strip_tags($row ['tableName'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('' . $loopRow,   strip_tags($row ['tableNameId'])); 
           $this->excel->getActiveSheet()->setCellValue('' . $loopRow,   strip_tags($row ['tableNameDetail'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('' . $loopRow,   strip_tags($row ['tableNameDetailId'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('' . $loopRow,   strip_tags($row ['leafId'])); 
           $this->excel->getActiveSheet()->setCellValue('' . $loopRow,   strip_tags($row ['leafName'])); 
           $this->excel->getActiveSheet()->setCellValue('' . $loopRow,   strip_tags($row ['from'])); 
           $this->excel->getActiveSheet()->setCellValue('' . $loopRow,   strip_tags($row ['isMerge'])); 
           $this->excel->getActiveSheet()->setCellValue('' . $loopRow,   strip_tags($row ['isAuthorized'])); 
           $this->excel->getActiveSheet()->setCellValue('' . $loopRow,  strip_tags( $row ['staffName'])); 
           $this->excel->getActiveSheet()->setCellValue('' . $loopRow,   strip_tags($row ['executeName'])); 
           $this->excel->getActiveSheet()->setCellValue('' . $loopRow,   strip_tags($row ['executeTime'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);  
           $loopRow++; 
           $lastRow = '' . $loopRow;
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
               $filename = "generalLedger" . rand(0, 10000000) . $extension;
               $path = $this->getFakeDocumentRoot() . "v3/financial/generalLedger/document/".$folder."/" . $filename;
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
               $filename = "generalLedger" . rand(0, 10000000) . $extension;
               $path = $this->getFakeDocumentRoot() . "v3/financial/generalLedger/document/".$folder."/" . $filename;
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
               $filename = "generalLedger" . rand(0, 10000000) . $extension;
               $path = $this->getFakeDocumentRoot() . "v3/financial/generalLedger/document/".$folder."/" . $filename;
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
               $filename = "generalLedger" . rand(0, 10000000) . $extension;
               $path = $this->getFakeDocumentRoot() . "v3/financial/generalLedger/document/".$folder."/" . $filename;
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
   $generalLedgerObject = new GeneralLedgerClass (); 
	if($_POST['securityToken'] != $generalLedgerObject->getSecurityToken()) {
		header('Content-Type:application/json; charset=utf-8');
		echo json_encode(array("success"=>false,"message"=>"Something wrong with the system.Hola hackers"));
		exit();
	}
	/* 
	 *  Load the dynamic value 
	 */ 
	if (isset($_POST ['leafId'])) {
		$generalLedgerObject->setLeafId($_POST ['leafId']); 
	} 
	if (isset($_POST ['offset'])) {
		$generalLedgerObject->setStart($_POST ['offset']); 
	} 
	if (isset($_POST ['limit'])) {
		$generalLedgerObject->setLimit($_POST ['limit']); 
	} 
	$generalLedgerObject ->setPageOutput($_POST['output']);  
	$generalLedgerObject->execute(); 
	/* 
	 *  Crud Operation (Create Read Update Delete/Destroy) 
	 */ 
	if ($_POST ['method'] == 'create') { 
		$generalLedgerObject->create(); 
	} 
	if ($_POST ['method'] == 'save') { 
		$generalLedgerObject->update(); 
	} 
	if ($_POST ['method'] == 'read') { 
		$generalLedgerObject->read(); 
	} 
	if ($_POST ['method'] == 'delete') { 
		$generalLedgerObject->delete(); 
	} 
	if ($_POST ['method'] == 'posting') { 
	//	$generalLedgerObject->posting(); 
	} 
	if ($_POST ['method'] == 'reverse') { 
	//	$generalLedgerObject->delete(); 
	} 
} } 
if (isset($_GET ['method'])) {
   $generalLedgerObject = new GeneralLedgerClass (); 
	if($_GET['securityToken'] != $generalLedgerObject->getSecurityToken()) {
		header('Content-Type:application/json; charset=utf-8');
		echo json_encode(array("success"=>false,"message"=>"Something wrong with the system.Hola hackers"));
		exit();
	}
	/* 
	 *  initialize Value before load in the loader
	 */ 
	if (isset($_GET ['leafId'])) {
       $generalLedgerObject->setLeafId($_GET ['leafId']); 
	} 
	/*
	 *  Load the dynamic value
	 */ 
	$generalLedgerObject->execute(); 
	/*
	 * Update Status of The Table. Admin Level Only 
	 */
	if ($_GET ['method'] == 'updateStatus') { 
       $generalLedgerObject->updateStatus(); 
	} 
	/* 
	 *  Checking Any Duplication  Key 
	 */ 
	if ($_GET['method'] == 'duplicate') { 
   	$generalLedgerObject->duplicate(); 
	} 
	if ($_GET ['method'] == 'dataNavigationRequest') { 
       if ($_GET ['dataNavigation'] == 'firstRecord') { 
           $generalLedgerObject->firstRecord('json'); 
       } 
       if ($_GET ['dataNavigation'] == 'previousRecord') { 
           $generalLedgerObject->previousRecord('json', 0); 
       } 
       if ($_GET ['dataNavigation'] == 'nextRecord') {
           $generalLedgerObject->nextRecord('json', 0); 
       } 
       if ($_GET ['dataNavigation'] == 'lastRecord') {
           $generalLedgerObject->lastRecord('json'); 
       } 
	} 
	/* 
	 * Excel Reporting  
	 */ 
	if (isset($_GET ['mode'])) { 
       $generalLedgerObject->setReportMode($_GET['mode']); 
       if ($_GET ['mode'] == 'excel'
            ||  $_GET ['mode'] == 'pdf'
			||  $_GET['mode']=='csv'
			||  $_GET['mode']=='html'
			||	$_GET['mode']=='excel5'
			||  $_GET['mode']=='xml') { 
			$generalLedgerObject->excel(); 
		} 
	} 
} 
?>
