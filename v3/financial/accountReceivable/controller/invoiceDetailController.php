<?php namespace Core\Financial\AccountReceivable\InvoiceDetail\Controller; 
use Core\ConfigClass;
use Core\Financial\AccountReceivable\InvoiceDetail\Model\InvoiceDetailModel;
use Core\Financial\AccountReceivable\InvoiceDetail\Service\InvoiceDetailService;
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
require_once ($newFakeDocumentRoot."v3/financial/accountReceivable/model/invoiceDetailModel.php"); 
require_once ($newFakeDocumentRoot."v3/financial/accountReceivable/service/invoiceDetailService.php"); 
/** 
 * Class InvoiceDetail
 * this is invoiceDetail controller files. 
 * @name IDCMS 
 * @version 2 
 * @author hafizan 
 * @package  Core\Financial\AccountReceivable\InvoiceDetail\Controller 
 * @subpackage AccountReceivable 
 * @link http://www.hafizan.com 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL 
 */ 
class InvoiceDetailClass extends ConfigClass { 
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
	 * @var \Core\Financial\AccountReceivable\InvoiceDetail\Model\InvoiceDetailModel 
	 */ 
	public $model; 
	/** 
	 * Service-Business Application Process or other ajax request 
	 * @var \Core\Financial\AccountReceivable\InvoiceDetail\Service\InvoiceDetailService 
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
       $this->setViewPath("./v3/financial/accountReceivable/view/invoiceDetail.php"); 
       $this->setControllerPath("./v3/financial/accountReceivable/controller/invoiceDetailController.php");
       $this->setServicePath("./v3/financial/accountReceivable/service/invoiceDetailService.php"); 
   } 
	/** 
	 * Class Loader 
	 */ 
	function execute() { 
       parent::__construct(); 
       $this->setAudit(1); 
       $this->setLog(1); 
       $this->model  = new InvoiceDetailModel(); 
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

       $this->service  = new InvoiceDetailService(); 
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
       if(!$this->model->getInvoiceId()){
           $this->model->setInvoiceId($this->service->getInvoiceDefaultValue());
       }
       if(!$this->model->getProductId()){
           $this->model->setProductId($this->service->getProductDefaultValue());
       }
       if(!$this->model->getUnitOfMeasurementId()){
           $this->model->setUnitOfMeasurementId($this->service->getUnitOfMeasurementDefaultValue());
       }
       if(!$this->model->getDiscountId()){
           $this->model->setDiscountId($this->service->getDiscountDefaultValue());
       }
       if(!$this->model->getTaxId()){
           $this->model->setTaxId($this->service->getTaxDefaultValue());
       }
        //$this->model->setDocumentNumber($this->getDocumentNumber());
       if ($this->getVendor() == self::MYSQL) {  
       $sql="
            INSERT INTO `invoicedetail` 
            (
                 `companyId`,
                 `invoiceId`,
                 `productId`,
                 `unitOfMeasurementId`,
                 `discountId`,
                 `taxId`,
                 `invoiceDetailLineNumber`,
                 `invoiceDetailQuantity`,
                 `invoiceDetailDescription`,
                 `invoiceDetailPrice`,
                 `invoiceDetailDiscount`,
                 `invoiceDetailTax`,
                 `invoiceDetailTotalPrice`,
                 `isDefault`,
                 `isNew`,
                 `isDraft`,
                 `isUpdate`,
                 `isDelete`,
                 `isActive`,
                 `isApproved`,
                 `isReview`,
                 `isPost`,
                 `isRule78`,
                 `executeBy`,
                 `executeTime`
       ) VALUES ( 
                 '".$this->getCompanyId()."',
                 '".$this->model->getInvoiceId()."',
                 '".$this->model->getProductId()."',
                 '".$this->model->getUnitOfMeasurementId()."',
                 '".$this->model->getDiscountId()."',
                 '".$this->model->getTaxId()."',
                 '".$this->model->getInvoiceDetailLineNumber()."',
                 '".$this->model->getInvoiceDetailQuantity()."',
                 '".$this->model->getInvoiceDetailDescription()."',
                 '".$this->model->getInvoiceDetailPrice()."',
                 '".$this->model->getInvoiceDetailDiscount()."',
                 '".$this->model->getInvoiceDetailTax()."',
                 '".$this->model->getInvoiceDetailTotalPrice()."',
                 '".$this->model->getIsDefault(0, 'single')."',
                 '".$this->model->getIsNew(0, 'single')."',
                 '".$this->model->getIsDraft(0, 'single')."',
                 '".$this->model->getIsUpdate(0, 'single')."',
                 '".$this->model->getIsDelete(0, 'single')."',
                 '".$this->model->getIsActive(0, 'single')."',
                 '".$this->model->getIsApproved(0, 'single')."',
                 '".$this->model->getIsReview(0, 'single')."',
                 '".$this->model->getIsPost(0, 'single')."',
                 '".$this->model->getIsRule78()."',
                 '".$this->model->getExecuteBy()."',
                 ".$this->model->getExecuteTime()."
       );";
		 } else if ($this->getVendor() == self::MSSQL) {  
       $sql="
            INSERT INTO [invoiceDetail] 
            (
                 [invoiceDetailId],
                 [companyId],
                 [invoiceId],
                 [productId],
                 [unitOfMeasurementId],
                 [discountId],
                 [taxId],
                 [invoiceDetailLineNumber],
                 [invoiceDetailQuantity],
                 [invoiceDetailDescription],
                 [invoiceDetailPrice],
                 [invoiceDetailDiscount],
                 [invoiceDetailTax],
                 [invoiceDetailTotalPrice],
                 [isDefault],
                 [isNew],
                 [isDraft],
                 [isUpdate],
                 [isDelete],
                 [isActive],
                 [isApproved],
                 [isReview],
                 [isPost],
                 [isRule78],
                 [executeBy],
                 [executeTime]
) VALUES ( 
                 '".$this->getCompanyId()."',
                 '".$this->model->getInvoiceId()."',
                 '".$this->model->getProductId()."',
                 '".$this->model->getUnitOfMeasurementId()."',
                 '".$this->model->getDiscountId()."',
                 '".$this->model->getTaxId()."',
                 '".$this->model->getInvoiceDetailLineNumber()."',
                 '".$this->model->getInvoiceDetailQuantity()."',
                 '".$this->model->getInvoiceDetailDescription()."',
                 '".$this->model->getInvoiceDetailPrice()."',
                 '".$this->model->getInvoiceDetailDiscount()."',
                 '".$this->model->getInvoiceDetailTax()."',
                 '".$this->model->getInvoiceDetailTotalPrice()."',
                 '".$this->model->getIsDefault(0, 'single')."',
                 '".$this->model->getIsNew(0, 'single')."',
                 '".$this->model->getIsDraft(0, 'single')."',
                 '".$this->model->getIsUpdate(0, 'single')."',
                 '".$this->model->getIsDelete(0, 'single')."',
                 '".$this->model->getIsActive(0, 'single')."',
                 '".$this->model->getIsApproved(0, 'single')."',
                 '".$this->model->getIsReview(0, 'single')."',
                 '".$this->model->getIsPost(0, 'single')."',
                 '".$this->model->getIsRule78()."',
                 '".$this->model->getExecuteBy()."',
                 ".$this->model->getExecuteTime()."
            );";
       } else if ($this->getVendor() == self::ORACLE) {  
            $sql="
            INSERT INTO INVOICEDETAIL 
            (
                 COMPANYID,
                 INVOICEID,
                 PRODUCTID,
                 UNITOFMEASUREMENTID,
                 DISCOUNTID,
                 TAXID,
                 INVOICEDETAILLINENUMBER,
                 INVOICEDETAILQUANTITY,
                 INVOICEDETAILDESCRIPTION,
                 INVOICEDETAILPRICE,
                 INVOICEDETAILDISCOUNT,
                 INVOICEDETAILTAX,
                 INVOICEDETAILTOTALPRICE,
                 ISDEFAULT,
                 ISNEW,
                 ISDRAFT,
                 ISUPDATE,
                 ISDELETE,
                 ISACTIVE,
                 ISAPPROVED,
                 ISREVIEW,
                 ISPOST,
                 ISRULE78,
                 EXECUTEBY,
                 EXECUTETIME
            ) VALUES ( 
                 '".$this->getCompanyId()."',
                 '".$this->model->getInvoiceId()."',
                 '".$this->model->getProductId()."',
                 '".$this->model->getUnitOfMeasurementId()."',
                 '".$this->model->getDiscountId()."',
                 '".$this->model->getTaxId()."',
                 '".$this->model->getInvoiceDetailLineNumber()."',
                 '".$this->model->getInvoiceDetailQuantity()."',
                 '".$this->model->getInvoiceDetailDescription()."',
                 '".$this->model->getInvoiceDetailPrice()."',
                 '".$this->model->getInvoiceDetailDiscount()."',
                 '".$this->model->getInvoiceDetailTax()."',
                 '".$this->model->getInvoiceDetailTotalPrice()."',
                 '".$this->model->getIsDefault(0, 'single')."',
                 '".$this->model->getIsNew(0, 'single')."',
                 '".$this->model->getIsDraft(0, 'single')."',
                 '".$this->model->getIsUpdate(0, 'single')."',
                 '".$this->model->getIsDelete(0, 'single')."',
                 '".$this->model->getIsActive(0, 'single')."',
                 '".$this->model->getIsApproved(0, 'single')."',
                 '".$this->model->getIsReview(0, 'single')."',
                 '".$this->model->getIsPost(0, 'single')."',
                 '".$this->model->getIsRule78()."',
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
       $invoiceDetailId = $this->q->lastInsertId(); 
       $this->q->commit(); 
       $end = microtime(true); 
       $time = $end - $start; 
       echo json_encode( 
           array(	"success" => true, 
                   "message" => $this->t['newRecordTextLabel'],  
                   "staffName" => $_SESSION['staffName'],  
                   "executeTime" =>date('d-m-Y H:i:s'),  
                   "totalRecord"=>$this->getTotalRecord(),
                   "invoiceDetailId" => $invoiceDetailId,
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
                   $this->setAuditFilter(" `invoicedetail`.`isActive` = 1  AND `invoicedetail`.`companyId`='".$this->getCompanyId()."' "); 
               } else if ($this->getVendor() == self::MSSQL) { 
                   $this->setAuditFilter(" [invoiceDetail].[isActive] = 1 AND [invoiceDetail].[companyId]='".$this->getCompanyId()."' "); 
               } else if ($this->getVendor() == self::ORACLE) { 
                   $this->setAuditFilter(" INVOICEDETAIL.ISACTIVE = 1  AND INVOICEDETAIL.COMPANYID='".$this->getCompanyId()."'"); 
               } 
           } else if ($_SESSION['isAdmin'] == 1) { 
               if ($this->getVendor() == self::MYSQL) { 
                   $this->setAuditFilter("   `invoicedetail`.`companyId`='".$this->getCompanyId()."'	"); 
               } else if ($this->getVendor() == self::MSSQL) { 
                   $this->setAuditFilter(" [invoiceDetail].[companyId]='".$this->getCompanyId()."' "); 
               } else if ($this->getVendor() == self::ORACLE) { 
                   $this->setAuditFilter(" INVOICEDETAIL.COMPANYID='".$this->getCompanyId()."' "); 
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
       SELECT                    `invoicedetail`.`invoiceDetailId`,
                    `company`.`companyDescription`,
                    `invoicedetail`.`companyId`,
                    `invoice`.`invoiceDescription`,
                    `invoicedetail`.`invoiceId`,
                    `product`.`productDescription`,
                    `invoicedetail`.`productId`,
                    `unitofmeasurement`.`unitOfMeasurementDescription`,
                    `invoicedetail`.`unitOfMeasurementId`,
                    `discount`.`discountDescription`,
                    `invoicedetail`.`discountId`,
                    `tax`.`taxDescription`,
                    `invoicedetail`.`taxId`,
                    `invoicedetail`.`invoiceDetailLineNumber`,
                    `invoicedetail`.`invoiceDetailQuantity`,
                    `invoicedetail`.`invoiceDetailDescription`,
                    `invoicedetail`.`invoiceDetailPrice`,
                    `invoicedetail`.`invoiceDetailDiscount`,
                    `invoicedetail`.`invoiceDetailTax`,
                    `invoicedetail`.`invoiceDetailTotalPrice`,
                    `invoicedetail`.`isDefault`,
                    `invoicedetail`.`isNew`,
                    `invoicedetail`.`isDraft`,
                    `invoicedetail`.`isUpdate`,
                    `invoicedetail`.`isDelete`,
                    `invoicedetail`.`isActive`,
                    `invoicedetail`.`isApproved`,
                    `invoicedetail`.`isReview`,
                    `invoicedetail`.`isPost`,
                    `invoicedetail`.`isRule78`,
                    `invoicedetail`.`executeBy`,
                    `invoicedetail`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `invoicedetail`
		  JOIN      `staff`
		  ON        `invoicedetail`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `invoicedetail`.`companyId`
	JOIN	`invoice`
	ON		`invoice`.`invoiceId` = `invoicedetail`.`invoiceId`
	JOIN	`product`
	ON		`product`.`productId` = `invoicedetail`.`productId`
	JOIN	`unitofmeasurement`
	ON		`unitofmeasurement`.`unitOfMeasurementId` = `invoicedetail`.`unitOfMeasurementId`
	JOIN	`discount`
	ON		`discount`.`discountId` = `invoicedetail`.`discountId`
	JOIN	`tax`
	ON		`tax`.`taxId` = `invoicedetail`.`taxId`
		  WHERE     " . $this->getAuditFilter(); 
       if ($this->model->getInvoiceDetailId(0, 'single')) { 
           $sql .= " AND `invoicedetail`.`" . $this->model->getPrimaryKeyName() . "`='" . $this->model->getInvoiceDetailId(0, 'single') . "'";  
       }
       if ($this->model->getInvoiceId()) { 
           $sql .= " AND `invoicedetail`.`invoiceId`='".$this->model->getInvoiceId()."'";  
       }
       if ($this->model->getProductId()) { 
           $sql .= " AND `invoicedetail`.`productId`='".$this->model->getProductId()."'";  
       }
       if ($this->model->getUnitOfMeasurementId()) { 
           $sql .= " AND `invoicedetail`.`unitOfMeasurementId`='".$this->model->getUnitOfMeasurementId()."'";  
       }
       if ($this->model->getDiscountId()) { 
           $sql .= " AND `invoicedetail`.`discountId`='".$this->model->getDiscountId()."'";  
       }
       if ($this->model->getTaxId()) { 
           $sql .= " AND `invoicedetail`.`taxId`='".$this->model->getTaxId()."'";  
       }
 } else if ($this->getVendor() == self::MSSQL) {  

		  $sql = "
		  SELECT                    [invoiceDetail].[invoiceDetailId],
                    [company].[companyDescription],
                    [invoiceDetail].[companyId],
                    [invoice].[invoiceDescription],
                    [invoiceDetail].[invoiceId],
                    [product].[productDescription],
                    [invoiceDetail].[productId],
                    [unitOfMeasurement].[unitOfMeasurementDescription],
                    [invoiceDetail].[unitOfMeasurementId],
                    [discount].[discountDescription],
                    [invoiceDetail].[discountId],
                    [tax].[taxDescription],
                    [invoiceDetail].[taxId],
                    [invoiceDetail].[invoiceDetailLineNumber],
                    [invoiceDetail].[invoiceDetailQuantity],
                    [invoiceDetail].[invoiceDetailDescription],
                    [invoiceDetail].[invoiceDetailPrice],
                    [invoiceDetail].[invoiceDetailDiscount],
                    [invoiceDetail].[invoiceDetailTax],
                    [invoiceDetail].[invoiceDetailTotalPrice],
                    [invoiceDetail].[isDefault],
                    [invoiceDetail].[isNew],
                    [invoiceDetail].[isDraft],
                    [invoiceDetail].[isUpdate],
                    [invoiceDetail].[isDelete],
                    [invoiceDetail].[isActive],
                    [invoiceDetail].[isApproved],
                    [invoiceDetail].[isReview],
                    [invoiceDetail].[isPost],
                    [invoiceDetail].[isRule78],
                    [invoiceDetail].[executeBy],
                    [invoiceDetail].[executeTime],
                    [staff].[staffName] 
		  FROM 	[invoiceDetail]
		  JOIN	[staff]
		  ON	[invoiceDetail].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [invoiceDetail].[companyId]
	JOIN	[invoice]
	ON		[invoice].[invoiceId] = [invoiceDetail].[invoiceId]
	JOIN	[product]
	ON		[product].[productId] = [invoiceDetail].[productId]
	JOIN	[unitOfMeasurement]
	ON		[unitOfMeasurement].[unitOfMeasurementId] = [invoiceDetail].[unitOfMeasurementId]
	JOIN	[discount]
	ON		[discount].[discountId] = [invoiceDetail].[discountId]
	JOIN	[tax]
	ON		[tax].[taxId] = [invoiceDetail].[taxId]
		  WHERE     " . $this->getAuditFilter(); 
       if ($this->model->getInvoiceDetailId(0, 'single')) { 
           $sql .= " AND [invoiceDetail].[" . $this->model->getPrimaryKeyName() . "]		=	'" . $this->model->getInvoiceDetailId(0, 'single') . "'"; 
       } 
       if ($this->model->getInvoiceId()) { 
           $sql .= " AND [invoiceDetail].[invoiceId]='".$this->model->getInvoiceId()."'";  
       }
       if ($this->model->getProductId()) { 
           $sql .= " AND [invoiceDetail].[productId]='".$this->model->getProductId()."'";  
       }
       if ($this->model->getUnitOfMeasurementId()) { 
           $sql .= " AND [invoiceDetail].[unitOfMeasurementId]='".$this->model->getUnitOfMeasurementId()."'";  
       }
       if ($this->model->getDiscountId()) { 
           $sql .= " AND [invoiceDetail].[discountId]='".$this->model->getDiscountId()."'";  
       }
       if ($this->model->getTaxId()) { 
           $sql .= " AND [invoiceDetail].[taxId]='".$this->model->getTaxId()."'";  
       }
		} else if ($this->getVendor() == self::ORACLE) {  

		  $sql = "
		  SELECT                    INVOICEDETAIL.INVOICEDETAILID AS \"invoiceDetailId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    INVOICEDETAIL.COMPANYID AS \"companyId\",
                    INVOICE.INVOICEDESCRIPTION AS  \"invoiceDescription\",
                    INVOICEDETAIL.INVOICEID AS \"invoiceId\",
                    PRODUCT.PRODUCTDESCRIPTION AS  \"productDescription\",
                    INVOICEDETAIL.PRODUCTID AS \"productId\",
                    UNITOFMEASUREMENT.UNITOFMEASUREMENTDESCRIPTION AS  \"unitOfMeasurementDescription\",
                    INVOICEDETAIL.UNITOFMEASUREMENTID AS \"unitOfMeasurementId\",
                    DISCOUNT.DISCOUNTDESCRIPTION AS  \"discountDescription\",
                    INVOICEDETAIL.DISCOUNTID AS \"discountId\",
                    TAX.TAXDESCRIPTION AS  \"taxDescription\",
                    INVOICEDETAIL.TAXID AS \"taxId\",
                    INVOICEDETAIL.INVOICEDETAILLINENUMBER AS \"invoiceDetailLineNumber\",
                    INVOICEDETAIL.INVOICEDETAILQUANTITY AS \"invoiceDetailQuantity\",
                    INVOICEDETAIL.INVOICEDETAILDESCRIPTION AS \"invoiceDetailDescription\",
                    INVOICEDETAIL.INVOICEDETAILPRICE AS \"invoiceDetailPrice\",
                    INVOICEDETAIL.INVOICEDETAILDISCOUNT AS \"invoiceDetailDiscount\",
                    INVOICEDETAIL.INVOICEDETAILTAX AS \"invoiceDetailTax\",
                    INVOICEDETAIL.INVOICEDETAILTOTALPRICE AS \"invoiceDetailTotalPrice\",
                    INVOICEDETAIL.ISDEFAULT AS \"isDefault\",
                    INVOICEDETAIL.ISNEW AS \"isNew\",
                    INVOICEDETAIL.ISDRAFT AS \"isDraft\",
                    INVOICEDETAIL.ISUPDATE AS \"isUpdate\",
                    INVOICEDETAIL.ISDELETE AS \"isDelete\",
                    INVOICEDETAIL.ISACTIVE AS \"isActive\",
                    INVOICEDETAIL.ISAPPROVED AS \"isApproved\",
                    INVOICEDETAIL.ISREVIEW AS \"isReview\",
                    INVOICEDETAIL.ISPOST AS \"isPost\",
                    INVOICEDETAIL.ISRULE78 AS \"isRule78\",
                    INVOICEDETAIL.EXECUTEBY AS \"executeBy\",
                    INVOICEDETAIL.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\" 
		  FROM 	INVOICEDETAIL 
		  JOIN	STAFF 
		  ON	INVOICEDETAIL.EXECUTEBY = STAFF.STAFFID 
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = INVOICEDETAIL.COMPANYID
	JOIN	INVOICE
	ON		INVOICE.INVOICEID = INVOICEDETAIL.INVOICEID
	JOIN	PRODUCT
	ON		PRODUCT.PRODUCTID = INVOICEDETAIL.PRODUCTID
	JOIN	UNITOFMEASUREMENT
	ON		UNITOFMEASUREMENT.UNITOFMEASUREMENTID = INVOICEDETAIL.UNITOFMEASUREMENTID
	JOIN	DISCOUNT
	ON		DISCOUNT.DISCOUNTID = INVOICEDETAIL.DISCOUNTID
	JOIN	TAX
	ON		TAX.TAXID = INVOICEDETAIL.TAXID
         WHERE     " . $this->getAuditFilter(); 
           if ($this->model->getInvoiceDetailId(0, 'single'))  {
               $sql .= " AND INVOICEDETAIL. ".strtoupper($this->model->getPrimaryKeyName()) . "='" . $this->model->getInvoiceDetailId(0, 'single') . "'"; 
           } 
       if ($this->model->getInvoiceId()) { 
           $sql .= " AND INVOICEDETAIL.INVOICEID='".$this->model->getInvoiceId()."'";  
       }
       if ($this->model->getProductId()) { 
           $sql .= " AND INVOICEDETAIL.PRODUCTID='".$this->model->getProductId()."'";  
       }
       if ($this->model->getUnitOfMeasurementId()) { 
           $sql .= " AND INVOICEDETAIL.UNITOFMEASUREMENTID='".$this->model->getUnitOfMeasurementId()."'";  
       }
       if ($this->model->getDiscountId()) { 
           $sql .= " AND INVOICEDETAIL.DISCOUNTID='".$this->model->getDiscountId()."'";  
       }
       if ($this->model->getTaxId()) { 
           $sql .= " AND INVOICEDETAIL.TAXID='".$this->model->getTaxId()."'";  
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
                   $sql.=" AND `invoicedetail`.`".$this->model->getFilterCharacter()."` like '".$this->getCharacterQuery()."%'"; 
               } else if($this->getVendor()==self::MSSQL){ 
                   $sql.=" AND [invoiceDetail].[".$this->model->getFilterCharacter()."] like '".$this->getCharacterQuery()."%'"; 
               } else if ($this->getVendor()==self::ORACLE){ 
                   $sql.=" AND Initcap(INVOICEDETAIL.".strtoupper($this->model->getFilterCharacter()).") LIKE Initcap('".$this->getCharacterQuery()."%')"; 
               }
		} 
		/** 
		 * filter column based on Range Of Date 
		 * Example Day,Week,Month,Year 
		 */ 
		if($this->getDateRangeStartQuery()){ 
               if($this->getVendor()==self::MYSQL){ 
                   $sql.=$this->q->dateFilter('invoicedetail',$this->model->getFilterDate(),$this->getDateRangeStartQuery(),$this->getDateRangeEndQuery(),$this->getDateRangeTypeQuery(),$this->getDateRangeExtraTypeQuery()); 
               } else if($this->getVendor()==self::MSSQL){ 
                   $sql.=$this->q->dateFilter('invoiceDetail',$this->model->getFilterDate(),$this->getDateRangeStartQuery(),$this->getDateRangeEndQuery(),$this->getDateRangeTypeQuery(),$this->getDateRangeExtraTypeQuery()); 
               } else if ($this->getVendor()==self::ORACLE){ 
                   $sql.=$this->q->dateFilter('INVOICEDETAIL',$this->model->getFilterDate(),$this->getDateRangeStartQuery(),$this->getDateRangeEndQuery(),$this->getDateRangeTypeQuery(),$this->getDateRangeExtraTypeQuery()); 
               }
           } 
		/** 
		 * filter column don't want to filter.Example may contain  sensitive information or unwanted to be search. 
		 * E.g  $filterArray=array('`leaf`.`leafId`'); 
		 * @variables $filterArray; 
		 */  
        $filterArray =null;        if($this->getVendor() ==self::MYSQL) { 
		    $filterArray = array("`invoicedetail`.`invoiceDetailId`",
                                              "`staff`.`staffPassword`"); 
        } else if ($this->getVendor() == self::MSSQL) {
 		    $filterArray = array("[invoicedetail].[invoiceDetailId]",
                                              "[staff].[staffPassword]"); 
        } else if ($this->getVendor() == self::ORACLE) { 
		    $filterArray = array("INVOICEDETAIL.INVOICEDETAILID",
                                              "STAFF.STAFFPASSWORD"); 
        }
		$tableArray = null; 
		if($this->getVendor()==self::MYSQL){ 
			$tableArray = array('staff','invoicedetail','company','invoice','product','unitofmeasurement','discount','tax'); 
		} else if($this->getVendor()==self::MSSQL){ 
			$tableArray = array('staff','invoicedetail','company','invoice','product','unitofmeasurement','discount','tax'); 
		} else if ($this->getVendor()==self::ORACLE){ 
			$tableArray = array('STAFF','INVOICEDETAIL','COMPANY','INVOICE','PRODUCT','UNITOFMEASUREMENT','DISCOUNT','TAX'); 
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
		if (!($this->model->getInvoiceDetailId(0, 'single'))) { 
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
               $row['counter'] = $this->getStart() + 26; 
               if ($this->model->getInvoiceDetailId(0, 'single')) { 
                   $row['firstRecord'] = $this->firstRecord('value'); 
                   $row['previousRecord'] = $this->previousRecord('value', $this->model->getInvoiceDetailId(0, 'single')); 
                   $row['nextRecord'] = $this->nextRecord('value', $this->model->getInvoiceDetailId(0, 'single')); 
                   $row['lastRecord'] = $this->lastRecord('value'); 
               }  
               $items [] = $row; 
               $i++; 
		}  
		if ($this->getPageOutput() == 'html') { 
               return $items; 
 } else if ($this->getPageOutput()=='table') {
  $this->setService('html');
  $str= null;
             if (is_array($items)) { 
				 $this->setServiceOutput('html');
                 $totalRecordDetail = intval(count($items)); 
                 if ($totalRecordDetail > 0) { 
                     $counter=0; 
                     for ($j = 0; $j < $totalRecordDetail; $j++) { 
                         $counter++;
                         $str.="<tr id='".$items[$j]['invoiceDetailId']."'>"; 
                             $str.="<td align=\"center\"><div class=\"btn-group\" align=\"center\">";
                             $str.="<input type='hidden' name='invoiceDetailId[]'     id='invoiceDetailId".$items[$j]['invoiceDetailId']."'  value='".$items[$j]['invoiceDetailId']."'>";
                             $str.="<input type='hidden' name='invoiceId[]'
                    id='invoiceDetailId".$items[$j]['invoiceId']."'
                        value='".$items[$j]['invoiceId']."'>";
                                 $str.="<button type=\"button\" class=\"btn btn-warning btn-mini\" title=\"Edit\" onClick=\"showFormUpdateDetail('".$this->getLeafId()."','".$this->getControllerPath()."','".$this->getSecurityToken()."','".$items[$j]['invoiceDetailId']."')\"><i class=\"glyphicon glyphicon-edit glyphicon-white\"></i></button>";
                                 $str.="<button type=\"button\" class=\"btn btn-danger btn-mini\" title=\"Delete\" onClick=\"showModalDeleteDetail('".$items[$j]['invoiceDetailId']."')\"><i class=\"glyphicon glyphicon-trash  glyphicon-white\"></i></button><div id=\"miniInfoPanel".$items[$j]['invoiceDetailId']."\"></div></td>";
$str.="<input type=\"hidden\" name=\"invoiceId[]\" id=\"invoiceId".$items[$j]['invoiceDetailId']."\" value=\"".$items[$j]['invoiceId']."\">";
   $productArray = $this->getProduct();
$str.="<td id='productId".$items[$j]['invoiceDetailId']."DetailForm'>";
$str.="	<select name=\"productId[]\" id=\"productId".$items[$j]['invoiceDetailId']."\" class=\"chzn-select form-control\" onchange=\"removeMeErrorDetail('productId".$items[$j]['invoiceDetailId']."')\">";
         $str.="<option value=\"\">".$this->t['pleaseSelectTextLabel']."</option>";
                        if (is_array($productArray)) {
                                 $totalRecord = intval(count($productArray));
                                 if($totalRecord > 0 ){ 
                                     for ($i = 0; $i < $totalRecord; $i++) {
                                         if($items[$j]['productId']==$productArray[$i]['productId']){
                                             $selected='selected';
                                         } else {
                                             $selected=NULL;
                                         } 
                                         $str.="<option value='".$productArray[$i]['productId']."' ".$selected.">".$productArray[$i]['productDescription']."</option>";
                                    }
                                 }   else {
                                    $str.="<option value=\"\">".$this->t['notAvailableTextLabel']."</option>";
                                }                             } else {
                                 $str.="<option value=\"\">".$this->t['notAvailableTextLabel']."</option>";
                        }
                     $str.="</select></td>";
   $unitOfMeasurementArray = $this->getUnitOfMeasurement();
$str.="<td id='unitOfMeasurementId".$items[$j]['invoiceDetailId']."DetailForm'>";
$str.="	<select name=\"unitOfMeasurementId[]\" id=\"unitOfMeasurementId".$items[$j]['invoiceDetailId']."\" class=\"chzn-select form-control\" onchange=\"removeMeErrorDetail('unitOfMeasurementId".$items[$j]['invoiceDetailId']."')\">";
         $str.="<option value=\"\">".$this->t['pleaseSelectTextLabel']."</option>";
                        if (is_array($unitOfMeasurementArray)) {
                                 $totalRecord = intval(count($unitOfMeasurementArray));
                                 if($totalRecord > 0 ){ 
                                     for ($i = 0; $i < $totalRecord; $i++) {
                                         if($items[$j]['unitOfMeasurementId']==$unitOfMeasurementArray[$i]['unitOfMeasurementId']){
                                             $selected='selected';
                                         } else {
                                             $selected=NULL;
                                         } 
                                         $str.="<option value='".$unitOfMeasurementArray[$i]['unitOfMeasurementId']."' ".$selected.">".$unitOfMeasurementArray[$i]['unitOfMeasurementDescription']."</option>";
                                    }
                                 }   else {
                                    $str.="<option value=\"\">".$this->t['notAvailableTextLabel']."</option>";
                                }                             } else {
                                 $str.="<option value=\"\">".$this->t['notAvailableTextLabel']."</option>";
                        }
                     $str.="</select></td>";
$str.="<input type=\"hidden\" name=\"discountId[]\" id=\"discountId".$items[$j]['invoiceDetailId']."\" value=\"".$items[$j]['discountId']."\">";
   $taxArray = $this->getTax();
$str.="<td id='taxId".$items[$j]['invoiceDetailId']."DetailForm'>";
$str.="	<select name=\"taxId[]\" id=\"taxId".$items[$j]['invoiceDetailId']."\" class=\"chzn-select form-control\" onchange=\"removeMeErrorDetail('taxId".$items[$j]['invoiceDetailId']."')\">";
         $str.="<option value=\"\">".$this->t['pleaseSelectTextLabel']."</option>";
                        if (is_array($taxArray)) {
                                 $totalRecord = intval(count($taxArray));
                                 if($totalRecord > 0 ){ 
                                     for ($i = 0; $i < $totalRecord; $i++) {
                                         if($items[$j]['taxId']==$taxArray[$i]['taxId']){
                                             $selected='selected';
                                         } else {
                                             $selected=NULL;
                                         } 
                                         $str.="<option value='".$taxArray[$i]['taxId']."' ".$selected.">".$taxArray[$i]['taxDescription']."</option>";
                                    }
                                 }   else {
                                    $str.="<option value=\"\">".$this->t['notAvailableTextLabel']."</option>";
                                }                             } else {
                                 $str.="<option value=\"\">".$this->t['notAvailableTextLabel']."</option>";
                        }
                     $str.="</select></td>";
$str.="<td vAlign=\"top\" align=\"center\"><input class=\"form-control\" type=\"text\" name=\"invoiceDetailQuantity[]\" id=\"invoiceDetailQuantity".$items[$j]['invoiceDetailId']."\" value=\"".$items[$j]['invoiceDetailQuantity']."\"></td>";
$str.="<td vAlign=\"top\" align=\"center\"><input class=\"form-control\"  type=\"text\" name=\"invoiceDetailDescription[]\" id=\"invoiceDetailDescription".$items[$j]['invoiceDetailId']."\" value=\"".$items[$j]['invoiceDetailDescription']."\"></td>";
$str.="<td vAlign=\"top\" align=\"center\"><input class=\"form-control\" type=\"text\" name=\"invoiceDetailPrice[]\" id=\"invoiceDetailPrice".$items[$j]['invoiceDetailId']."\" value=\"".$items[$j]['invoiceDetailPrice']."\"></td>";
$str.="<td vAlign=\"top\" align=\"center\"><input class=\"form-control\" type=\"text\" name=\"invoiceDetailDiscount[]\" id=\"invoiceDetailDiscount".$items[$j]['invoiceDetailId']."\" value=\"".$items[$j]['invoiceDetailDiscount']."\"></td>";
$str.="<td vAlign=\"top\" align=\"center\"><input class=\"form-control\" type=\"text\" name=\"invoiceDetailTax[]\" id=\"invoiceDetailTax".$items[$j]['invoiceDetailId']."\" value=\"".$items[$j]['invoiceDetailTax']."\"></td>";
$str.="<td vAlign=\"top\" align=\"center\"><input class=\"form-control\" type=\"text\" name=\"invoiceDetailTotalPrice[]\" id=\"invoiceDetailTotalPrice".$items[$j]['invoiceDetailId']."\" value=\"".$items[$j]['invoiceDetailTotalPrice']."\"></td>";
                     $str.="</tr>"; 
                  } 
 } else { 
                    $str.="<tr>"; 
                        $str.="<td colspan=\"6\" align=\"center\">".$this->exceptionMessageReturn($this->t['recordNotFoundLabel'])."</td>"; 
                    $str.="</tr>"; 
                 }
 }  else { 
                    $str.="<tr>"; 
                    $str.="<td colspan=\"6\" align=\"center\">".$this->exceptionMessageReturn($this->t['recordNotFoundLabel'])."</td>"; 
                    $str.="</tr>"; 
                } 
             echo json_encode(array('success'=>true,'tableData'=>$str)); 
             exit();           } else if ($this->getPageOutput() == 'json') { 
           if ($this->model->getInvoiceDetailId(0, 'single')) { 
               $end = microtime(true); 
               $time = $end - $start; 
               echo str_replace(array("[","]"),"",json_encode(array( 
                   'success' => true,  
                   'total' => $total,  
                   'message' => $this->t['viewRecordMessageLabel'],  
                   'time' => $time,  
                   'firstRecord' => $this->firstRecord('value'),  
                   'previousRecord' => $this->previousRecord('value', $this->model->getInvoiceDetailId(0, 'single')),  
                   'nextRecord' => $this->nextRecord('value', $this->model->getInvoiceDetailId(0, 'single')),  
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
                   'previousRecord' => $this->recordSet->previousRecord('value', $this->model->getInvoiceDetailId(0, 'single')),  
                   'nextRecord' => $this->recordSet->nextRecord('value', $this->model->getInvoiceDetailId(0, 'single')),  
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
       if(!$this->model->getInvoiceId()){
           $this->model->setInvoiceId($this->service->getInvoiceDefaultValue());
       }
       if(!$this->model->getProductId()){
           $this->model->setProductId($this->service->getProductDefaultValue());
       }
       if(!$this->model->getUnitOfMeasurementId()){
           $this->model->setUnitOfMeasurementId($this->service->getUnitOfMeasurementDefaultValue());
       }
       if(!$this->model->getDiscountId()){
           $this->model->setDiscountId($this->service->getDiscountDefaultValue());
       }
       if(!$this->model->getTaxId()){
           $this->model->setTaxId($this->service->getTaxDefaultValue());
       }
       if ($this->getVendor() == self::MYSQL) {  
           $sql = " 
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`invoicedetail`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getInvoiceDetailId(0, 'single') . "' "; 
       } else if ($this->getVendor() == self::MSSQL) { 
           $sql = " 
           SELECT	[" . $this->model->getPrimaryKeyName() . "] 
           FROM 	[invoiceDetail] 
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getInvoiceDetailId(0, 'single') . "' "; 
       } else if ($this->getVendor() == self::ORACLE) { 
           $sql = " 
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
           FROM 	INVOICEDETAIL 
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getInvoiceDetailId(0, 'single') . "' "; 
       }
       $result = $this->q->fast($sql); 
       $total = $this->q->numberRows($result, $sql); 
       if ($total == 0) { 
           echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel'])); 
           exit(); 
       } else { 
           if ($this->getVendor() == self::MYSQL) { 
               $sql="
               UPDATE `invoicedetail` SET 
                       `invoiceId` = '".$this->model->getInvoiceId()."',
                       `productId` = '".$this->model->getProductId()."',
                       `unitOfMeasurementId` = '".$this->model->getUnitOfMeasurementId()."',
                       `discountId` = '".$this->model->getDiscountId()."',
                       `taxId` = '".$this->model->getTaxId()."',
                       `invoiceDetailLineNumber` = '".$this->model->getInvoiceDetailLineNumber()."',
                       `invoiceDetailQuantity` = '".$this->model->getInvoiceDetailQuantity()."',
                       `invoiceDetailDescription` = '".$this->model->getInvoiceDetailDescription()."',
                       `invoiceDetailPrice` = '".$this->model->getInvoiceDetailPrice()."',
                       `invoiceDetailDiscount` = '".$this->model->getInvoiceDetailDiscount()."',
                       `invoiceDetailTax` = '".$this->model->getInvoiceDetailTax()."',
                       `invoiceDetailTotalPrice` = '".$this->model->getInvoiceDetailTotalPrice()."',
                       `isDefault` = '".$this->model->getIsDefault('0','single')."',
                       `isNew` = '".$this->model->getIsNew('0','single')."',
                       `isDraft` = '".$this->model->getIsDraft('0','single')."',
                       `isUpdate` = '".$this->model->getIsUpdate('0','single')."',
                       `isDelete` = '".$this->model->getIsDelete('0','single')."',
                       `isActive` = '".$this->model->getIsActive('0','single')."',
                       `isApproved` = '".$this->model->getIsApproved('0','single')."',
                       `isReview` = '".$this->model->getIsReview('0','single')."',
                       `isPost` = '".$this->model->getIsPost('0','single')."',
                       `isRule78` = '".$this->model->getIsRule78()."',
                       `executeBy` = '".$this->model->getExecuteBy('0','single')."',
                       `executeTime` = ".$this->model->getExecuteTime()."
               WHERE    `invoiceDetailId`='".$this->model->getInvoiceDetailId('0','single')."'";

           } else if ($this->getVendor() == self::MSSQL) {  
                $sql="
                UPDATE [invoiceDetail] SET 
                       [invoiceId] = '".$this->model->getInvoiceId()."',
                       [productId] = '".$this->model->getProductId()."',
                       [unitOfMeasurementId] = '".$this->model->getUnitOfMeasurementId()."',
                       [discountId] = '".$this->model->getDiscountId()."',
                       [taxId] = '".$this->model->getTaxId()."',
                       [invoiceDetailLineNumber] = '".$this->model->getInvoiceDetailLineNumber()."',
                       [invoiceDetailQuantity] = '".$this->model->getInvoiceDetailQuantity()."',
                       [invoiceDetailDescription] = '".$this->model->getInvoiceDetailDescription()."',
                       [invoiceDetailPrice] = '".$this->model->getInvoiceDetailPrice()."',
                       [invoiceDetailDiscount] = '".$this->model->getInvoiceDetailDiscount()."',
                       [invoiceDetailTax] = '".$this->model->getInvoiceDetailTax()."',
                       [invoiceDetailTotalPrice] = '".$this->model->getInvoiceDetailTotalPrice()."',
                       [isDefault] = '".$this->model->getIsDefault(0, 'single')."',
                       [isNew] = '".$this->model->getIsNew(0, 'single')."',
                       [isDraft] = '".$this->model->getIsDraft(0, 'single')."',
                       [isUpdate] = '".$this->model->getIsUpdate(0, 'single')."',
                       [isDelete] = '".$this->model->getIsDelete(0, 'single')."',
                       [isActive] = '".$this->model->getIsActive(0, 'single')."',
                       [isApproved] = '".$this->model->getIsApproved(0, 'single')."',
                       [isReview] = '".$this->model->getIsReview(0, 'single')."',
                       [isPost] = '".$this->model->getIsPost(0, 'single')."',
                       [isRule78] = '".$this->model->getIsRule78()."',
                       [executeBy] = '".$this->model->getExecuteBy(0, 'single')."',
                       [executeTime] = ".$this->model->getExecuteTime()."
                WHERE   [invoiceDetailId]='".$this->model->getInvoiceDetailId('0','single')."'";

           } else if ($this->getVendor() == self::ORACLE) {  
                $sql="
                UPDATE INVOICEDETAIL SET
                        INVOICEID = '".$this->model->getInvoiceId()."',
                       PRODUCTID = '".$this->model->getProductId()."',
                       UNITOFMEASUREMENTID = '".$this->model->getUnitOfMeasurementId()."',
                       DISCOUNTID = '".$this->model->getDiscountId()."',
                       TAXID = '".$this->model->getTaxId()."',
                       INVOICEDETAILLINENUMBER = '".$this->model->getInvoiceDetailLineNumber()."',
                       INVOICEDETAILQUANTITY = '".$this->model->getInvoiceDetailQuantity()."',
                       INVOICEDETAILDESCRIPTION = '".$this->model->getInvoiceDetailDescription()."',
                       INVOICEDETAILPRICE = '".$this->model->getInvoiceDetailPrice()."',
                       INVOICEDETAILDISCOUNT = '".$this->model->getInvoiceDetailDiscount()."',
                       INVOICEDETAILTAX = '".$this->model->getInvoiceDetailTax()."',
                       INVOICEDETAILTOTALPRICE = '".$this->model->getInvoiceDetailTotalPrice()."',
                       ISDEFAULT = '".$this->model->getIsDefault(0, 'single')."',
                       ISNEW = '".$this->model->getIsNew(0, 'single')."',
                       ISDRAFT = '".$this->model->getIsDraft(0, 'single')."',
                       ISUPDATE = '".$this->model->getIsUpdate(0, 'single')."',
                       ISDELETE = '".$this->model->getIsDelete(0, 'single')."',
                       ISACTIVE = '".$this->model->getIsActive(0, 'single')."',
                       ISAPPROVED = '".$this->model->getIsApproved(0, 'single')."',
                       ISREVIEW = '".$this->model->getIsReview(0, 'single')."',
                       ISPOST = '".$this->model->getIsPost(0, 'single')."',
                       ISRULE78 = '".$this->model->getIsRule78()."',
                       EXECUTEBY = '".$this->model->getExecuteBy(0, 'single')."',
                       EXECUTETIME = ".$this->model->getExecuteTime()."
                WHERE  INVOICEDETAILID='".$this->model->getInvoiceDetailId('0','single')."'";

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
           FROM 	`invoicedetail` 
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getInvoiceDetailId(0, 'single') . "' ";  
       } else if ($this->getVendor() == self::MSSQL) { 
           $sql = " 
           SELECT	[" . $this->model->getPrimaryKeyName() . "]  
           FROM 	[invoiceDetail] 
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getInvoiceDetailId(0, 'single') . "' "; 
       } else if ($this->getVendor() == self::ORACLE) { 
           $sql = " 
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
           FROM 	INVOICEDETAIL 
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getInvoiceDetailId(0, 'single') . "' "; 
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
               UPDATE  `invoicedetail` 
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
               WHERE   `invoiceDetailId`   =  '" . $this->model->getInvoiceDetailId(0, 'single') . "'";
           } else if ($this->getVendor() == self::MSSQL) {  
               $sql="
               UPDATE  [invoiceDetail] 
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
               WHERE   [invoiceDetailId]	=  '" . $this->model->getInvoiceDetailId(0, 'single') . "'";
           } else if ($this->getVendor() == self::ORACLE) {  
               $sql="
               UPDATE  INVOICEDETAIL 
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
               WHERE   INVOICEDETAILID	=  '" . $this->model->getInvoiceDetailId(0, 'single') . "'";
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
               UPDATE `invoicedetail` 
               SET	   `executeBy`		=	'".$this->model->getExecuteBy()."',
					   `executeTime`	=	".$this->model->getExecuteTime().",";
		} else if ($this->getVendor() == self::MSSQL) { 
               $sql = " 
               UPDATE 	[invoiceDetail] 
               SET	   [executeBy]		=	'".$this->model->getExecuteBy()."',
					   [executeTime]	=	".$this->model->getExecuteTime().",";
		} else if ($this->getVendor() == self::ORACLE) { 
               $sql = " 
               UPDATE INVOICEDETAIL 
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
                         $sqlLooping .= " `isDefault` = CASE `invoicedetail`.`".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isDefault] = CASE [invoiceDetail].[" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISDEFAULT = CASE INVOICEDETAIL.".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getInvoiceDetailId($i, 'array') . "
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
                         $sqlLooping .= " `isDraft` = CASE `invoicedetail`.`".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isDraft] = CASE [invoiceDetail].[" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISDRAFT = CASE INVOICEDETAIL.".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getInvoiceDetailId($i, 'array') . "
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
                         $sqlLooping .= " `isNew` = CASE `invoicedetail`.`".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isNew] = CASE [invoiceDetail].[" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISNEW = CASE INVOICEDETAIL.".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getInvoiceDetailId($i, 'array') . "
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
                         $sqlLooping .= " `isActive` = CASE `invoicedetail`.`".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isActive] = CASE [invoiceDetail].[" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISACTIVE = CASE INVOICEDETAIL.".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getInvoiceDetailId($i, 'array') . "
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
                         $sqlLooping .= " `isUpdate` = CASE `invoicedetail`.`".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isUpdate] = CASE [invoiceDetail].[" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISUPDATE = CASE INVOICEDETAIL.".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getInvoiceDetailId($i, 'array') . "
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
                         $sqlLooping .= " `isDelete` = CASE `invoicedetail`.`".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isDelete] = CASE [invoiceDetail].[" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISDELETE = CASE INVOICEDETAIL.".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getInvoiceDetailId($i, 'array') . "
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
                         $sqlLooping .= " `isReview` = CASE `invoicedetail`.`".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isReview] = CASE [invoiceDetail].[" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISREVIEW = CASE INVOICEDETAIL.".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getInvoiceDetailId($i, 'array') . "
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
                         $sqlLooping .= " `isPost` = CASE `invoicedetail`.`".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isPost] = CASE [invoiceDetail].[" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISPOST = CASE INVOICEDETAIL.".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getInvoiceDetailId($i, 'array') . "
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
                         $sqlLooping .= " `isApproved` = CASE `invoicedetail`.`".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isApproved] = CASE [invoiceDetail].[" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISAPPROVED = CASE INVOICEDETAIL.".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getInvoiceDetailId($i, 'array') . "
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
                         $sqlLooping .=" `isDelete` = CASE `invoicedetail`.`" . $this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isDelete] = CASE [INVOICEDETAIL].[" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISDELETE = CASE INVOICEDETAIL.".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     }else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getInvoiceDetailId($i, 'array') . "
                         THEN " . $this->model->getIsDelete($i, 'array') . " ";
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
           SELECT  `invoiceDetailCode` 
           FROM    `invoicedetail` 
           WHERE   `invoiceDetailCode` 	= 	'" . $this->model->getInvoiceDetailCode() . "' 
           AND     `isActive`  =   1
           AND     `companyId` =   '".$this->getCompanyId()."'"; 
       } else if ($this->getVendor() == self::MSSQL) { 
           $sql = " 
           SELECT  [invoiceDetailCode] 
           FROM    [invoiceDetail] 
           WHERE   [invoiceDetailCode] = 	'" . $this->model->getInvoiceDetailCode() . "' 
           AND     [isActive]  =   1 
           AND     [companyId] =	'".$this->getCompanyId()."'"; 
       } else if ($this->getVendor() == self::ORACLE) { 
           $sql = " 
               SELECT  INVOICEDETAILCODE as \"invoiceDetailCode\" 
               FROM    INVOICEDETAIL 
               WHERE   INVOICEDETAILCODE	= 	'" . $this->model->getInvoiceDetailCode() . "' 
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
	 * Return  Invoice 
    * @return null|string
	 */
	public function getInvoice() { 
       $this->service->setServiceOutput($this->getServiceOutput());
		return $this->service->getInvoice();  
	}
	/** 
	 * Return  Product 
    * @return null|string
	 */
	public function getProduct() { 
       $this->service->setServiceOutput($this->getServiceOutput());
		return $this->service->getProduct();  
	}
	/** 
	 * Return  UnitOfMeasurement 
    * @return null|string
	 */
	public function getUnitOfMeasurement() { 
       $this->service->setServiceOutput($this->getServiceOutput());
		return $this->service->getUnitOfMeasurement();  
	}
	/** 
	 * Return  Discount 
    * @return null|string
	 */
	public function getDiscount() { 
       $this->service->setServiceOutput($this->getServiceOutput());
		return $this->service->getDiscount();  
	}
	/** 
	 * Return  Tax 
    * @return null|string
	 */
	public function getTax() { 
       $this->service->setServiceOutput($this->getServiceOutput());
		return $this->service->getTax();  
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
         FROM    `invoiceDetail`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
 $sql.="AND     `invoiceId` = ".$this->model->getInvoiceId()." ";
     } else if ($this->getVendor()==self::MSSQL){ 
         $sql="
         SELECT    COUNT(*) AS total 
         FROM      [invoiceDetail]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId(). " ";
 $sql.="AND     [invoiceId] = ".$this->model->getInvoiceId()." ";
     } else if ($this->getVendor()==self::ORACLE){ 
         $sql="
         SELECT    COUNT(*)    AS  \"total\" 
         FROM      INVOICEDETAIL
         WHERE     ISACTIVE    =   1
         AND       COMPANYID   =   " . $this->getCompanyId() . " ";
 $sql.="AND     INVOICEID = ".$this->model->getInvoiceId()." "; 
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
                   ->setSubject('invoiceDetail')
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
        $this->excel->getActiveSheet()->setCellValue('B2',$this->getReportTitle()); 
        $this->excel->getActiveSheet()->setCellValue('Q2', ''); 
        $this->excel->getActiveSheet()->mergeCells('B2:Q2'); 
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.'); 
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['invoiceIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['productIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['unitOfMeasurementIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['discountIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['taxIdLabel']); 
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['invoiceDetailLineNumberLabel']); 
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['invoiceDetailQuantityLabel']); 
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['invoiceDetailDescriptionLabel']); 
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['invoiceDetailPriceLabel']); 
        $this->excel->getActiveSheet()->setCellValue('L3', $this->translate['invoiceDetailDiscountLabel']); 
        $this->excel->getActiveSheet()->setCellValue('M3', $this->translate['invoiceDetailTaxLabel']); 
        $this->excel->getActiveSheet()->setCellValue('N3', $this->translate['invoiceDetailTotalPriceLabel']); 
        $this->excel->getActiveSheet()->setCellValue('O3', $this->translate['isRule78Label']); 
        $this->excel->getActiveSheet()->setCellValue('P3', $this->translate['executeByLabel']); 
        $this->excel->getActiveSheet()->setCellValue('Q3', $this->translate['executeTimeLabel']); 
		// 
        $loopRow = 4; 
        $i = 0; 
        \PHPExcel_Cell::setValueBinder( new \PHPExcel_Cell_AdvancedValueBinder() );
        $lastRow=null;
        while (($row = $this->q->fetchAssoc()) == TRUE) { 
           //	echo print_r($row); 
           $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i); 
           $this->excel->getActiveSheet()->setCellValue('C' . $loopRow,   strip_tags($row ['invoiceDescription'])); 
           $this->excel->getActiveSheet()->setCellValue('D' . $loopRow,   strip_tags($row ['productDescription'])); 
           $this->excel->getActiveSheet()->setCellValue('E' . $loopRow,   strip_tags($row ['unitOfMeasurementDescription'])); 
           $this->excel->getActiveSheet()->setCellValue('F' . $loopRow,   strip_tags($row ['discountDescription'])); 
           $this->excel->getActiveSheet()->setCellValue('G' . $loopRow,   strip_tags($row ['taxDescription'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('H' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('H' . $loopRow,   strip_tags($row ['invoiceDetailLineNumber'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('I' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('I' . $loopRow,   strip_tags($row ['invoiceDetailQuantity'])); 
           $this->excel->getActiveSheet()->setCellValue('J' . $loopRow,   strip_tags($row ['invoiceDetailDescription'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('K' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('K' . $loopRow,   strip_tags($row ['invoiceDetailPrice'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('L' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('L' . $loopRow,   strip_tags($row ['invoiceDetailDiscount'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('M' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('M' . $loopRow,   strip_tags($row ['invoiceDetailTax'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('N' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
           $this->excel->getActiveSheet()->setCellValue('N' . $loopRow,   strip_tags($row ['invoiceDetailTotalPrice'])); 
           $this->excel->getActiveSheet()->setCellValue('O' . $loopRow,   strip_tags($row ['isRule78'])); 
           $this->excel->getActiveSheet()->setCellValue('P' . $loopRow,  strip_tags( $row ['staffName'])); 
           $this->excel->getActiveSheet()->setCellValue('Q' . $loopRow,   strip_tags($row ['executeTime'])); 
           $this->excel->getActiveSheet()->getStyle()->getNumberFormat('Q' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);  
           $loopRow++; 
           $lastRow = 'Q' . $loopRow;
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
               $filename = "invoiceDetail" . rand(0, 10000000) . $extension;
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
               $filename = "invoiceDetail" . rand(0, 10000000) . $extension;
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
               $filename = "invoiceDetail" . rand(0, 10000000) . $extension;
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
               $filename = "invoiceDetail" . rand(0, 10000000) . $extension;
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
   $invoiceDetailObject = new InvoiceDetailClass (); 
	if($_POST['securityToken'] != $invoiceDetailObject->getSecurityToken()) {
		header('Content-Type:application/json; charset=utf-8');
		echo json_encode(array("success"=>false,"message"=>"Something wrong with the system.Hola hackers"));
		exit();
	}
	/* 
	 *  Load the dynamic value 
	 */ 
	if (isset($_POST ['leafId'])) {
		$invoiceDetailObject->setLeafId($_POST ['leafId']); 
	} 
	if (isset($_POST ['offset'])) {
		$invoiceDetailObject->setStart($_POST ['offset']); 
	} 
	if (isset($_POST ['limit'])) {
		$invoiceDetailObject->setLimit($_POST ['limit']); 
	} 
	$invoiceDetailObject ->setPageOutput($_POST['output']);  
	$invoiceDetailObject->execute(); 
	/* 
	 *  Crud Operation (Create Read Update Delete/Destroy) 
	 */ 
	if ($_POST ['method'] == 'create') { 
		$invoiceDetailObject->create(); 
	} 
	if ($_POST ['method'] == 'save') { 
		$invoiceDetailObject->update(); 
	} 
	if ($_POST ['method'] == 'read') { 
		$invoiceDetailObject->read(); 
	} 
	if ($_POST ['method'] == 'delete') { 
		$invoiceDetailObject->delete(); 
	} 
	if ($_POST ['method'] == 'posting') { 
	//	$invoiceDetailObject->posting(); 
	} 
	if ($_POST ['method'] == 'reverse') { 
	//	$invoiceDetailObject->delete(); 
	} 
} } 
if (isset($_GET ['method'])) {
   $invoiceDetailObject = new InvoiceDetailClass (); 
	if($_GET['securityToken'] != $invoiceDetailObject->getSecurityToken()) {
		header('Content-Type:application/json; charset=utf-8');
		echo json_encode(array("success"=>false,"message"=>"Something wrong with the system.Hola hackers"));
		exit();
	}
	/* 
	 *  initialize Value before load in the loader
	 */ 
	if (isset($_GET ['leafId'])) {
       $invoiceDetailObject->setLeafId($_GET ['leafId']); 
	} 
	/*
	 *  Load the dynamic value
	 */ 
	$invoiceDetailObject->execute(); 
	/*
	 * Update Status of The Table. Admin Level Only 
	 */
	if ($_GET ['method'] == 'updateStatus') { 
       $invoiceDetailObject->updateStatus(); 
	} 
	/* 
	 *  Checking Any Duplication  Key 
	 */ 
	if ($_GET['method'] == 'duplicate') { 
   	$invoiceDetailObject->duplicate(); 
	} 
	if ($_GET ['method'] == 'dataNavigationRequest') { 
       if ($_GET ['dataNavigation'] == 'firstRecord') { 
           $invoiceDetailObject->firstRecord('json'); 
       } 
       if ($_GET ['dataNavigation'] == 'previousRecord') { 
           $invoiceDetailObject->previousRecord('json', 0); 
       } 
       if ($_GET ['dataNavigation'] == 'nextRecord') {
           $invoiceDetailObject->nextRecord('json', 0); 
       } 
       if ($_GET ['dataNavigation'] == 'lastRecord') {
           $invoiceDetailObject->lastRecord('json'); 
       } 
	} 
	/* 
	 * Excel Reporting  
	 */ 
	if (isset($_GET ['mode'])) { 
       $invoiceDetailObject->setReportMode($_GET['mode']); 
       if ($_GET ['mode'] == 'excel'
            ||  $_GET ['mode'] == 'pdf'
			||  $_GET['mode']=='csv'
			||  $_GET['mode']=='html'
			||	$_GET['mode']=='excel5'
			||  $_GET['mode']=='xml') { 
			$invoiceDetailObject->excel(); 
		} 
	} 
	if (isset($_GET ['filter'])) { 
       $invoiceDetailObject->setServiceOutput('option');
       if(($_GET['filter']=='invoice')) { 
           $invoiceDetailObject->getInvoice(); 
       }
       if(($_GET['filter']=='product')) { 
           $invoiceDetailObject->getProduct(); 
       }
       if(($_GET['filter']=='unitOfMeasurement')) { 
           $invoiceDetailObject->getUnitOfMeasurement(); 
       }
       if(($_GET['filter']=='discount')) { 
           $invoiceDetailObject->getDiscount(); 
       }
       if(($_GET['filter']=='tax')) { 
           $invoiceDetailObject->getTax(); 
       }
   }
} 
?>
