<?php namespace Core\Financial\Cashbook\CashBookLedger\Controller; 
use Core\ConfigClass;
use Core\Financial\Cashbook\CashBookLedger\Model\CashBookLedgerModel;
use Core\Financial\Cashbook\CashBookLedger\Service\CashBookLedgerService;
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
require_once ($newFakeDocumentRoot."v3/financial/cashbook/model/cashBookLedgerModel.php"); 
require_once ($newFakeDocumentRoot."v3/financial/cashbook/service/cashBookLedgerService.php"); 
/** 
 * Class CashBookLedger
 * this is cashBookLedger controller files. 
 * @name IDCMS 
 * @version 2 
 * @author hafizan 
 * @package  Core\Financial\Cashbook\CashBookLedger\Controller 
 * @subpackage Cashbook 
 * @link http://www.hafizan.com 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL 
 */ 
class CashBookLedgerClass extends ConfigClass { 
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
	 * @var \Core\Financial\Cashbook\CashBookLedger\Model\CashBookLedgerModel 
	 */ 
	public $model; 
	/** 
	 * Service-Business Application Process or other ajax request 
	 * @var \Core\Financial\Cashbook\CashBookLedger\Service\CashBookLedgerService 
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
       $this->setViewPath("./v3/financial/cashbook/view/cashBookLedger.php"); 
       $this->setControllerPath("./v3/financial/cashbook/controller/cashBookLedgerController.php");
       $this->setServicePath("./v3/financial/cashbook/service/cashBookLedgerService.php"); 
   } 
	/** 
	 * Class Loader 
	 */ 
	function execute() { 
       parent::__construct(); 
       $this->setAudit(1); 
       $this->setLog(1); 
       $this->model  = new CashBookLedgerModel(); 
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

       $this->service  = new CashBookLedgerService(); 
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
                   $this->setAuditFilter(" `cashbookledger`.`isActive` = 1  AND `cashbookledger`.`companyId`='".$this->getCompanyId()."' "); 
               } else if ($this->getVendor() == self::MSSQL) { 
                   $this->setAuditFilter(" [cashBookLedger].[isActive] = 1 AND [cashBookLedger].[companyId]='".$this->getCompanyId()."' "); 
               } else if ($this->getVendor() == self::ORACLE) { 
                   $this->setAuditFilter(" CASHBOOKLEDGER.ISACTIVE = 1  AND CASHBOOKLEDGER.COMPANYID='".$this->getCompanyId()."'"); 
               } 
           } else if ($_SESSION['isAdmin'] == 1) { 
               if ($this->getVendor() == self::MYSQL) { 
                   $this->setAuditFilter("   `cashbookledger`.`companyId`='".$this->getCompanyId()."'	"); 
               } else if ($this->getVendor() == self::MSSQL) { 
                   $this->setAuditFilter(" [cashBookLedger].[companyId]='".$this->getCompanyId()."' "); 
               } else if ($this->getVendor() == self::ORACLE) { 
                   $this->setAuditFilter(" CASHBOOKLEDGER.COMPANYID='".$this->getCompanyId()."' "); 
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
       SELECT                    `cashbookledger`.`cashBookLedgerId`,
                    `company`.`companyDescription`,
                    `cashbookledger`.`companyId`,
                    `businesspartner`.`businessPartnerCompany`,
                    `cashbookledger`.`businessPartnerId`,
                    `bank`.`bankDescription`,
                    `cashbookledger`.`bankId`,
                    `chartofaccount`.`chartOfAccountTitle`,
                    `cashbookledger`.`chartOfAccountId`,
                    `cashbookledger`.`collectionId`,
                    `cashbookledger`.`paymentVoucherId`,
                    `cashbookledger`.`bankTransferId`,
                    `cashbookledger`.`documentNumber`,
                    `cashbookledger`.`cashBookDate`,
                    `cashbookledger`.`cashBookAmount`,
                    `cashbookledger`.`cashBookDescription`,
                    `cashbookledger`.`leafId`,
                    `cashbookledger`.`isDefault`,
                    `cashbookledger`.`isNew`,
                    `cashbookledger`.`isDraft`,
                    `cashbookledger`.`isUpdate`,
                    `cashbookledger`.`isDelete`,
                    `cashbookledger`.`isActive`,
                    `cashbookledger`.`isApproved`,
                    `cashbookledger`.`isReview`,
                    `cashbookledger`.`isPost`,
                    `cashbookledger`.`executeBy`,
                    `cashbookledger`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `cashbookledger`
		  JOIN      `staff`
		  ON        `cashbookledger`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `cashbookledger`.`companyId`
	JOIN	`businesspartner`
	ON		`businesspartner`.`businessPartnerId` = `cashbookledger`.`businessPartnerId`
	JOIN	`bank`
	ON		`bank`.`bankId` = `cashbookledger`.`bankId`
	JOIN	`chartofaccount`
	ON		`chartofaccount`.`chartOfAccountId` = `cashbookledger`.`chartOfAccountId`
	LEFT JOIN	`collection`
	ON		`collection`.`collectionId` = `cashbookledger`.`collectionId`
	LEFT JOIN	`paymentvoucher`
	ON		`paymentvoucher`.`paymentVoucherId` = `cashbookledger`.`paymentVoucherId`
	LEFT JOIN	`banktransfer`
	ON		`banktransfer`.`bankTransferId` = `cashbookledger`.`bankTransferId`
		  WHERE     " . $this->getAuditFilter(); 
       if ($this->model->getCashBookLedgerId(0, 'single')) { 
           $sql .= " AND `cashbookledger`.`" . $this->model->getPrimaryKeyName() . "`='" . $this->model->getCashBookLedgerId(0, 'single') . "'";  
       }
       if ($this->model->getBusinessPartnerId()) { 
           $sql .= " AND `cashbookledger`.`businessPartnerId`='".$this->model->getBusinessPartnerId()."'";  
       }
       if ($this->model->getBankId()) { 
           $sql .= " AND `cashbookledger`.`bankId`='".$this->model->getBankId()."'";  
       }
       if ($this->model->getChartOfAccountId()) { 
           $sql .= " AND `cashbookledger`.`chartOfAccountId`='".$this->model->getChartOfAccountId()."'";  
       }
 } else if ($this->getVendor() == self::MSSQL) {  

		  $sql = "
		  SELECT                    [cashBookLedger].[cashBookLedgerId],
                    [company].[companyDescription],
                    [cashBookLedger].[companyId],
                    [businessPartner].[businessPartnerCompany],
                    [cashBookLedger].[businessPartnerId],
                    [bank].[bankDescription],
                    [cashBookLedger].[bankId],
                    [chartOfAccount].[chartOfAccountTitle],
                    [cashBookLedger].[chartOfAccountId],
                    [cashBookLedger].[collectionId],
                    [cashBookLedger].[paymentVoucherId],
                    [cashBookLedger].[bankTransferId],
                    [cashBookLedger].[documentNumber],
                    [cashBookLedger].[cashBookDate],
                    [cashBookLedger].[cashBookAmount],
                    [cashBookLedger].[cashBookDescription],
                    [cashBookLedger].[leafId],
                    [cashBookLedger].[isDefault],
                    [cashBookLedger].[isNew],
                    [cashBookLedger].[isDraft],
                    [cashBookLedger].[isUpdate],
                    [cashBookLedger].[isDelete],
                    [cashBookLedger].[isActive],
                    [cashBookLedger].[isApproved],
                    [cashBookLedger].[isReview],
                    [cashBookLedger].[isPost],
                    [cashBookLedger].[executeBy],
                    [cashBookLedger].[executeTime],
                    [staff].[staffName] 
		  FROM 	[cashBookLedger]
		  JOIN	[staff]
		  ON	[cashBookLedger].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [cashBookLedger].[companyId]
	JOIN	[businessPartner]
	ON		[businessPartner].[businessPartnerId] = [cashBookLedger].[businessPartnerId]
	JOIN	[bank]
	ON		[bank].[bankId] = [cashBookLedger].[bankId]
	JOIN	[chartOfAccount]
	ON		[chartOfAccount].[chartOfAccountId] = [cashBookLedger].[chartOfAccountId]
		  WHERE     " . $this->getAuditFilter(); 
       if ($this->model->getCashBookLedgerId(0, 'single')) { 
           $sql .= " AND [cashBookLedger].[" . $this->model->getPrimaryKeyName() . "]		=	'" . $this->model->getCashBookLedgerId(0, 'single') . "'"; 
       } 
       if ($this->model->getBusinessPartnerId()) { 
           $sql .= " AND [cashBookLedger].[businessPartnerId]='".$this->model->getBusinessPartnerId()."'";  
       }
       if ($this->model->getBankId()) { 
           $sql .= " AND [cashBookLedger].[bankId]='".$this->model->getBankId()."'";  
       }
       if ($this->model->getChartOfAccountId()) { 
           $sql .= " AND [cashBookLedger].[chartOfAccountId]='".$this->model->getChartOfAccountId()."'";  
       }
		} else if ($this->getVendor() == self::ORACLE) {  

		  $sql = "
		  SELECT                    CASHBOOKLEDGER.CASHBOOKLEDGERID AS \"cashBookLedgerId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    CASHBOOKLEDGER.COMPANYID AS \"companyId\",
                    BUSINESSPARTNER.BUSINESSPARTNERCOMPANY AS  \"businessPartnerCompany\",
                    CASHBOOKLEDGER.BUSINESSPARTNERID AS \"businessPartnerId\",
                    BANK.BANKDESCRIPTION AS  \"bankDescription\",
                    CASHBOOKLEDGER.BANKID AS \"bankId\",
                    CHARTOFACCOUNT.CHARTOFACCOUNTTITLE AS  \"chartOfAccountTitle\",
                    CASHBOOKLEDGER.CHARTOFACCOUNTID AS \"chartOfAccountId\",
                    CASHBOOKLEDGER.COLLECTIONID AS \"collectionId\",
                    CASHBOOKLEDGER.PAYMENTVOUCHERID AS \"paymentVoucherId\",
                    CASHBOOKLEDGER.BANKTRANSFERID AS \"bankTransferId\",
                    CASHBOOKLEDGER.DOCUMENTNUMBER AS \"documentNumber\",
                    CASHBOOKLEDGER.CASHBOOKDATE AS \"cashBookDate\",
                    CASHBOOKLEDGER.CASHBOOKAMOUNT AS \"cashBookAmount\",
                    CASHBOOKLEDGER.CASHBOOKDESCRIPTION AS \"cashBookDescription\",
                    CASHBOOKLEDGER.LEAFID AS \"leafId\",
                    CASHBOOKLEDGER.ISDEFAULT AS \"isDefault\",
                    CASHBOOKLEDGER.ISNEW AS \"isNew\",
                    CASHBOOKLEDGER.ISDRAFT AS \"isDraft\",
                    CASHBOOKLEDGER.ISUPDATE AS \"isUpdate\",
                    CASHBOOKLEDGER.ISDELETE AS \"isDelete\",
                    CASHBOOKLEDGER.ISACTIVE AS \"isActive\",
                    CASHBOOKLEDGER.ISAPPROVED AS \"isApproved\",
                    CASHBOOKLEDGER.ISREVIEW AS \"isReview\",
                    CASHBOOKLEDGER.ISPOST AS \"isPost\",
                    CASHBOOKLEDGER.EXECUTEBY AS \"executeBy\",
                    CASHBOOKLEDGER.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\" 
		  FROM 	CASHBOOKLEDGER 
		  JOIN	STAFF 
		  ON	CASHBOOKLEDGER.EXECUTEBY = STAFF.STAFFID 
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = CASHBOOKLEDGER.COMPANYID
	JOIN	BUSINESSPARTNER
	ON		BUSINESSPARTNER.BUSINESSPARTNERID = CASHBOOKLEDGER.BUSINESSPARTNERID
	JOIN	BANK
	ON		BANK.BANKID = CASHBOOKLEDGER.BANKID
	JOIN	CHARTOFACCOUNT
	ON		CHARTOFACCOUNT.CHARTOFACCOUNTID = CASHBOOKLEDGER.CHARTOFACCOUNTID
         WHERE     " . $this->getAuditFilter(); 
           if ($this->model->getCashBookLedgerId(0, 'single'))  {
               $sql .= " AND CASHBOOKLEDGER. ".strtoupper($this->model->getPrimaryKeyName()) . "='" . $this->model->getCashBookLedgerId(0, 'single') . "'"; 
           } 
       if ($this->model->getBusinessPartnerId()) { 
           $sql .= " AND CASHBOOKLEDGER.BUSINESSPARTNERID='".$this->model->getBusinessPartnerId()."'";  
       }
       if ($this->model->getBankId()) { 
           $sql .= " AND CASHBOOKLEDGER.BANKID='".$this->model->getBankId()."'";  
       }
       if ($this->model->getChartOfAccountId()) { 
           $sql .= " AND CASHBOOKLEDGER.CHARTOFACCOUNTID='".$this->model->getChartOfAccountId()."'";  
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
                   $sql.=" AND `cashbookledger`.`".$this->model->getFilterCharacter()."` like '".$this->getCharacterQuery()."%'"; 
               } else if($this->getVendor()==self::MSSQL){ 
                   $sql.=" AND [cashBookLedger].[".$this->model->getFilterCharacter()."] like '".$this->getCharacterQuery()."%'"; 
               } else if ($this->getVendor()==self::ORACLE){ 
                   $sql.=" AND Initcap(CASHBOOKLEDGER.".strtoupper($this->model->getFilterCharacter()).") LIKE Initcap('".$this->getCharacterQuery()."%')"; 
               }
		} 
		/** 
		 * filter column based on Range Of Date 
		 * Example Day,Week,Month,Year 
		 */ 
		if($this->getDateRangeStartQuery()){ 
               if($this->getVendor()==self::MYSQL){ 
                   $sql.=$this->q->dateFilter('cashbookledger',$this->model->getFilterDate(),$this->getDateRangeStartQuery(),$this->getDateRangeEndQuery(),$this->getDateRangeTypeQuery(),$this->getDateRangeExtraTypeQuery()); 
               } else if($this->getVendor()==self::MSSQL){ 
                   $sql.=$this->q->dateFilter('cashBookLedger',$this->model->getFilterDate(),$this->getDateRangeStartQuery(),$this->getDateRangeEndQuery(),$this->getDateRangeTypeQuery(),$this->getDateRangeExtraTypeQuery()); 
               } else if ($this->getVendor()==self::ORACLE){ 
                   $sql.=$this->q->dateFilter('CASHBOOKLEDGER',$this->model->getFilterDate(),$this->getDateRangeStartQuery(),$this->getDateRangeEndQuery(),$this->getDateRangeTypeQuery(),$this->getDateRangeExtraTypeQuery()); 
               }
           } 
		/** 
		 * filter column don't want to filter.Example may contain  sensitive information or unwanted to be search. 
		 * E.g  $filterArray=array('`leaf`.`leafId`'); 
		 * @variables $filterArray; 
		 */  
        $filterArray =null;        if($this->getVendor() ==self::MYSQL) { 
		    $filterArray = array("`cashbookledger`.`cashBookLedgerId`",
                                              "`staff`.`staffPassword`"); 
        } else if ($this->getVendor() == self::MSSQL) {
 		    $filterArray = array("[cashbookledger].[cashBookLedgerId]",
                                              "[staff].[staffPassword]"); 
        } else if ($this->getVendor() == self::ORACLE) { 
		    $filterArray = array("CASHBOOKLEDGER.CASHBOOKLEDGERID",
                                              "STAFF.STAFFPASSWORD"); 
        }
		$tableArray = null; 
		if($this->getVendor()==self::MYSQL){ 
			$tableArray = array('staff','cashbookledger','company','businesspartner','bank','chartofaccount'); 
		} else if($this->getVendor()==self::MSSQL){ 
			$tableArray = array('staff','cashbookledger','company','businesspartner','bank','chartofaccount'); 
		} else if ($this->getVendor()==self::ORACLE){ 
			$tableArray = array('STAFF','CASHBOOKLEDGER','COMPANY','BUSINESSPARTNER','BANK','CHARTOFACCOUNT'); 
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
		if (!($this->model->getCashBookLedgerId(0, 'single'))) { 
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
               $row['counter'] = $this->getStart() + 24; 
               if ($this->model->getCashBookLedgerId(0, 'single')) { 
                   $row['firstRecord'] = $this->firstRecord('value'); 
                   $row['previousRecord'] = $this->previousRecord('value', $this->model->getCashBookLedgerId(0, 'single')); 
                   $row['nextRecord'] = $this->nextRecord('value', $this->model->getCashBookLedgerId(0, 'single')); 
                   $row['lastRecord'] = $this->lastRecord('value'); 
               }  
               $items [] = $row; 
               $i++; 
		}  
		if ($this->getPageOutput() == 'html') { 
               return $items; 
           } else if ($this->getPageOutput() == 'json') { 
           if ($this->model->getCashBookLedgerId(0, 'single')) { 
               $end = microtime(true); 
               $time = $end - $start; 
               echo str_replace(array("[","]"),"",json_encode(array( 
                   'success' => true,  
                   'total' => $total,  
                   'message' => $this->t['viewRecordMessageLabel'],  
                   'time' => $time,  
                   'firstRecord' => $this->firstRecord('value'),  
                   'previousRecord' => $this->previousRecord('value', $this->model->getCashBookLedgerId(0, 'single')),  
                   'nextRecord' => $this->nextRecord('value', $this->model->getCashBookLedgerId(0, 'single')),  
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
                   'previousRecord' => $this->recordSet->previousRecord('value', $this->model->getCashBookLedgerId(0, 'single')),  
                   'nextRecord' => $this->recordSet->nextRecord('value', $this->model->getCashBookLedgerId(0, 'single')),  
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
	 * Return  Bank 
    * @return null|string
	 */
	public function getBank() { 
       $this->service->setServiceOutput($this->getServiceOutput());
		return $this->service->getBank();  
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
         FROM    `cashBookLedger`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
     } else if ($this->getVendor()==self::MSSQL){ 
         $sql="
         SELECT    COUNT(*) AS total 
         FROM      [cashBookLedger]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId(). " ";
     } else if ($this->getVendor()==self::ORACLE){ 
         $sql="
         SELECT    COUNT(*)    AS  \"total\" 
         FROM      CASHBOOKLEDGER
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
                   ->setSubject('cashBookLedger')
                   ->setDescription('Generated by PhpExcel an Idcms Generator') 
                   ->setKeywords('office 2007 openxml php') 
                   ->setCategory('financial/cashbook'); 
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
        $this->excel->getActiveSheet()->setCellValue('B2',$this->getReportTitle()); 
        $this->excel->getActiveSheet()->setCellValue('O2', ''); 
        $this->excel->getActiveSheet()->mergeCells('B2:O2'); 
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.'); 
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['businessPartnerIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['bankIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['chartOfAccountIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['collectionIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['paymentVoucherIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['bankTransferIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['documentNumberLabel']); 
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['cashBookDateLabel']); 
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['cashBookAmountLabel']); 
        $this->excel->getActiveSheet()->setCellValue('L3', $this->translate['cashBookDescriptionLabel']); 
        $this->excel->getActiveSheet()->setCellValue('M3', $this->translate['leafIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('N3', $this->translate['executeByLabel']); 
        $this->excel->getActiveSheet()->setCellValue('O3', $this->translate['executeTimeLabel']); 
		// 
        $loopRow = 4; 
        $i = 0; 
        \PHPExcel_Cell::setValueBinder( new \PHPExcel_Cell_AdvancedValueBinder() );
        $lastRow=null;
        while (($row = $this->q->fetchAssoc()) == TRUE) { 
           //	echo print_r($row); 
           $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i); 
           $this->excel->getActiveSheet()->setCellValue('C' . $loopRow,   strip_tags($row ['businessPartnerCompany'])); 
           $this->excel->getActiveSheet()->setCellValue('D' . $loopRow,   strip_tags($row ['bankDescription'])); 
           $this->excel->getActiveSheet()->setCellValue('E' . $loopRow,   strip_tags($row ['chartOfAccountTitle'])); 
           $this->excel->getActiveSheet()->setCellValue('F' . $loopRow,   strip_tags($row ['collectionDescription'])); 
           $this->excel->getActiveSheet()->setCellValue('G' . $loopRow,   strip_tags($row ['paymentVoucherDescription'])); 
           $this->excel->getActiveSheet()->setCellValue('H' . $loopRow,   strip_tags($row ['bankTransferDescription'])); 
           $this->excel->getActiveSheet()->setCellValue('I' . $loopRow,   strip_tags($row ['documentNumber'])); 
           $this->excel->getActiveSheet()->setCellValue('J' . $loopRow,   strip_tags($row ['cashBookDate'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('K' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('K' . $loopRow,   strip_tags($row ['cashBookAmount'])); 
           $this->excel->getActiveSheet()->setCellValue('L' . $loopRow,   strip_tags($row ['cashBookDescription'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('M' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('M' . $loopRow,   strip_tags($row ['leafId'])); 
           $this->excel->getActiveSheet()->setCellValue('N' . $loopRow,  strip_tags( $row ['staffName'])); 
           $this->excel->getActiveSheet()->setCellValue('O' . $loopRow,   strip_tags($row ['executeTime'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('O' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);  
           $loopRow++; 
           $lastRow = 'O' . $loopRow;
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
               $filename = "cashBookLedger" . rand(0, 10000000) . $extension;
               $path = $this->getFakeDocumentRoot() . "v3/financial/cashbook/document/".$folder."/" . $filename;
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
               $filename = "cashBookLedger" . rand(0, 10000000) . $extension;
               $path = $this->getFakeDocumentRoot() . "v3/financial/cashbook/document/".$folder."/" . $filename;
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
               $filename = "cashBookLedger" . rand(0, 10000000) . $extension;
               $path = $this->getFakeDocumentRoot() . "v3/financial/cashbook/document/".$folder."/" . $filename;
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
               $filename = "cashBookLedger" . rand(0, 10000000) . $extension;
               $path = $this->getFakeDocumentRoot() . "v3/financial/cashbook/document/".$folder."/" . $filename;
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
   $cashBookLedgerObject = new CashBookLedgerClass (); 
	if($_POST['securityToken'] != $cashBookLedgerObject->getSecurityToken()) {
		header('Content-Type:application/json; charset=utf-8');
		echo json_encode(array("success"=>false,"message"=>"Something wrong with the system.Hola hackers"));
		exit();
	}
	/* 
	 *  Load the dynamic value 
	 */ 
	if (isset($_POST ['leafId'])) {
		$cashBookLedgerObject->setLeafId($_POST ['leafId']); 
	} 
	if (isset($_POST ['offset'])) {
		$cashBookLedgerObject->setStart($_POST ['offset']); 
	} 
	if (isset($_POST ['limit'])) {
		$cashBookLedgerObject->setLimit($_POST ['limit']); 
	} 
	$cashBookLedgerObject ->setPageOutput($_POST['output']);  
	$cashBookLedgerObject->execute(); 
	/* 
	 *  Crud Operation (Create Read Update Delete/Destroy) 
	 */ 
	if ($_POST ['method'] == 'create') { 
		$cashBookLedgerObject->create(); 
	} 
	if ($_POST ['method'] == 'save') { 
		$cashBookLedgerObject->update(); 
	} 
	if ($_POST ['method'] == 'read') { 
		$cashBookLedgerObject->read(); 
	} 
	if ($_POST ['method'] == 'delete') { 
		$cashBookLedgerObject->delete(); 
	} 
	if ($_POST ['method'] == 'posting') { 
	//	$cashBookLedgerObject->posting(); 
	} 
	if ($_POST ['method'] == 'reverse') { 
	//	$cashBookLedgerObject->delete(); 
	} 
} } 
if (isset($_GET ['method'])) {
   $cashBookLedgerObject = new CashBookLedgerClass (); 
	if($_GET['securityToken'] != $cashBookLedgerObject->getSecurityToken()) {
		header('Content-Type:application/json; charset=utf-8');
		echo json_encode(array("success"=>false,"message"=>"Something wrong with the system.Hola hackers"));
		exit();
	}
	/* 
	 *  initialize Value before load in the loader
	 */ 
	if (isset($_GET ['leafId'])) {
       $cashBookLedgerObject->setLeafId($_GET ['leafId']); 
	} 
	/*
	 *  Load the dynamic value
	 */ 
	$cashBookLedgerObject->execute(); 
	/*
	 * Update Status of The Table. Admin Level Only 
	 */
	if ($_GET ['method'] == 'updateStatus') { 
       $cashBookLedgerObject->updateStatus(); 
	} 
	/* 
	 *  Checking Any Duplication  Key 
	 */ 
	if ($_GET['method'] == 'duplicate') { 
   	$cashBookLedgerObject->duplicate(); 
	} 
	if ($_GET ['method'] == 'dataNavigationRequest') { 
       if ($_GET ['dataNavigation'] == 'firstRecord') { 
           $cashBookLedgerObject->firstRecord('json'); 
       } 
       if ($_GET ['dataNavigation'] == 'previousRecord') { 
           $cashBookLedgerObject->previousRecord('json', 0); 
       } 
       if ($_GET ['dataNavigation'] == 'nextRecord') {
           $cashBookLedgerObject->nextRecord('json', 0); 
       } 
       if ($_GET ['dataNavigation'] == 'lastRecord') {
           $cashBookLedgerObject->lastRecord('json'); 
       } 
	} 
	/* 
	 * Excel Reporting  
	 */ 
	if (isset($_GET ['mode'])) { 
       $cashBookLedgerObject->setReportMode($_GET['mode']); 
       if ($_GET ['mode'] == 'excel'
            ||  $_GET ['mode'] == 'pdf'
			||  $_GET['mode']=='csv'
			||  $_GET['mode']=='html'
			||	$_GET['mode']=='excel5'
			||  $_GET['mode']=='xml') { 
			$cashBookLedgerObject->excel(); 
		} 
	} 
	if (isset($_GET ['filter'])) { 
       $cashBookLedgerObject->setServiceOutput('option');
       if(($_GET['filter']=='businessPartner')) { 
           $cashBookLedgerObject->getBusinessPartner(); 
       }
       if(($_GET['filter']=='bank')) { 
           $cashBookLedgerObject->getBank(); 
       }
       if(($_GET['filter']=='chartOfAccount')) { 
           $cashBookLedgerObject->getChartOfAccount(); 
       }
   }
} 
?>
