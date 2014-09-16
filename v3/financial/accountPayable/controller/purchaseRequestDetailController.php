<?php

namespace Core\Financial\AccountPayable\PurchaseRequestDetail\Controller;

use Core\ConfigClass;
use Core\Financial\AccountPayable\PurchaseRequestDetail\Model\PurchaseRequestDetailModel;
use Core\Financial\AccountPayable\PurchaseRequestDetail\Service\PurchaseRequestDetailService;
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
require_once ($newFakeDocumentRoot . "library/class/classAbstract.php");
require_once ($newFakeDocumentRoot . "library/class/classRecordSet.php");
require_once ($newFakeDocumentRoot . "library/class/classDate.php");
require_once ($newFakeDocumentRoot . "library/class/classDocumentTrail.php");
require_once ($newFakeDocumentRoot . "library/class/classShared.php");
require_once ($newFakeDocumentRoot . "v3/system/document/model/documentModel.php");
require_once ($newFakeDocumentRoot . "v3/financial/accountPayable/model/purchaseRequestDetailModel.php");
require_once ($newFakeDocumentRoot . "v3/financial/accountPayable/service/purchaseRequestDetailService.php");

/**
 * Class PurchaseRequestDetail
 * this is purchaseRequestDetail controller files. 
 * @name IDCMS 
 * @version 2 
 * @author hafizan 
 * @package  Core\Financial\AccountPayable\PurchaseRequestDetail\Controller 
 * @subpackage AccountPayable 
 * @link http://www.hafizan.com 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL 
 */
class PurchaseRequestDetailClass extends ConfigClass {

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
     * @var \Core\Financial\AccountPayable\PurchaseRequestDetail\Model\PurchaseRequestDetailModel 
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request 
     * @var \Core\Financial\AccountPayable\PurchaseRequestDetail\Service\PurchaseRequestDetailService 
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
        if ($_SESSION['companyId']) {
            $this->setCompanyId($_SESSION['companyId']);
        } else {
            // fall back to default database if anything wrong
            $this->setCompanyId(1);
        }
        $this->translate = array();
        $this->t = array();
        $this->leafAccess = array();
        $this->systemFormat = array();
        $this->setViewPath("./v3/financial/accountPayable/view/purchaseRequestDetail.php");
        $this->setControllerPath("./v3/financial/accountPayable/controller/purchaseRequestDetailController.php");
        $this->setServicePath("./v3/financial/accountPayable/service/purchaseRequestDetailService.php");
    }

    /**
     * Class Loader 
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new PurchaseRequestDetailModel();
        $this->model->setVendor($this->getVendor());
        $this->model->execute();
		$this->setViewPath("./v3/financial/accountPayable/view/" . $this->model->getFrom());
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
        if (is_array($data) && count($data) > 0) {
            $this->q->getLog($data['isLog']);
            $this->q->setAudit($data['isAudit']);
        }
        if ($this->getAudit() == 1) {
            $this->q->setAudit($this->getAudit());
            $this->q->setTableName($this->model->getTableName());
            $this->q->setPrimaryKeyName($this->model->getPrimaryKeyName());
        }
        $translator = new SharedClass();
        $translator->setCurrentTable($this->model->getTableName());
        $translator->setLeafId($this->getLeafId());
        $translator->execute();

        $this->translate = $translator->getLeafTranslation(); // short because code too long  
        $this->t = $translator->getDefaultTranslation(); // short because code too long  

        $arrayInfo = $translator->getFileInfo();
        $applicationNative = $arrayInfo['applicationNative'];
        $folderNative = $arrayInfo['folderNative'];
        $moduleNative = $arrayInfo['moduleNative'];
        $leafNative = $arrayInfo['leafNative'];
        $this->setApplicationId($arrayInfo['applicationId']);
        $this->setModuleId($arrayInfo['moduleId']);
        $this->setFolderId($arrayInfo['folderId']);
        $this->setLeafId($arrayInfo['leafId']);
        $this->setReportTitle($applicationNative . " :: " . $moduleNative . " :: " . $folderNative . " :: " . $leafNative);

        $this->service = new PurchaseRequestDetailService();
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

        $this->systemFormatArray = $this->systemFormat->getSystemFormat();

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
        $sql = null;
        if (!$this->model->getPurchaseRequestId()) {
            $this->model->setPurchaseRequestId($this->service->getPurchaseRequestDefaultValue());
        }
        if (!$this->model->getProductId()) {
            $this->model->setProductId($this->service->getProductDefaultValue());
        }
        if (!$this->model->getUnitOfMeasurementId()) {
            $this->model->setUnitOfMeasurementId($this->service->getUnitOfMeasurementDefaultValue());
        }
        if (!$this->model->getChartOfAccountId()) {
            $this->model->setChartOfAccountId($this->service->getChartOfAccountDefaultValue());
        }
        //$this->model->setDocumentNumber($this->getDocumentNumber());
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `purchaserequestdetail` 
            (
                 `companyId`,
                 `purchaseRequestId`,
                 `productId`,
                 `purchaseRequestDetailDescription`,
                 `purchaseRequestDetailQuantity`,
                 `unitOfMeasurementId`,
                 `chartOfAccountId`,
                 `purchaseRequestDetailBudget`,
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
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getPurchaseRequestId() . "',
                 '" . $this->model->getProductId() . "',
                 '" . $this->model->getPurchaseRequestDetailDescription() . "',
                 '" . $this->model->getPurchaseRequestDetailQuantity() . "',
                 '" . $this->model->getUnitOfMeasurementId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getPurchaseRequestDetailBudget() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . "
       );";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            INSERT INTO [purchaseRequestDetail] 
            (
                 [purchaseRequestDetailId],
                 [companyId],
                 [purchaseRequestId],
                 [productId],
                 [purchaseRequestDetailDescription],
                 [purchaseRequestDetailQuantity],
                 [unitOfMeasurementId],
                 [chartOfAccountId],
                 [purchaseRequestDetailBudget],
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
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getPurchaseRequestId() . "',
                 '" . $this->model->getProductId() . "',
                 '" . $this->model->getPurchaseRequestDetailDescription() . "',
                 '" . $this->model->getPurchaseRequestDetailQuantity() . "',
                 '" . $this->model->getUnitOfMeasurementId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getPurchaseRequestDetailBudget() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . "
            );";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            INSERT INTO PURCHASEREQUESTDETAIL 
            (
                 COMPANYID,
                 PURCHASEREQUESTID,
                 PRODUCTID,
                 PURCHASEREQUESTDETAILDESCRIPTION,
                 PURCHASEREQUESTDETAILQUANTITY,
                 UNITOFMEASUREMENTID,
                 CHARTOFACCOUNTID,
                 PURCHASEREQUESTDETAILBUDGET,
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
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getPurchaseRequestId() . "',
                 '" . $this->model->getProductId() . "',
                 '" . $this->model->getPurchaseRequestDetailDescription() . "',
                 '" . $this->model->getPurchaseRequestDetailQuantity() . "',
                 '" . $this->model->getUnitOfMeasurementId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getPurchaseRequestDetailBudget() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . "
            );";
        }
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $purchaseRequestDetailId = $this->q->lastInsertId("purchaseRequestDetail");
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array("success" => true,
                    "message" => $this->t['newRecordTextLabel'],
                    "staffName" => $_SESSION['staffName'],
                    "executeTime" => date('d-m-Y H:i:s'),
                    "totalRecord" => $this->getTotalRecord(),
                    "purchaseRequestDetailId" => $purchaseRequestDetailId,
                    "time" => $time));
        exit();
    }

    /**
     * Read
     * @see config::read() 
     */
    public function read() {
        if ($this->getPageOutput() == 'json' || $this->getPageOutput() == 'table') {
            header('Content-Type:application/json; charset=utf-8');
        }
        $start = microtime(true);
        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] == 0) {
                if ($this->getVendor() == self::MYSQL) {
                    $this->setAuditFilter(" `purchaserequestdetail`.`isActive` = 1  AND `purchaserequestdetail`.`companyId`='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::MSSQL) {
                    $this->setAuditFilter(" [purchaseRequestDetail].[isActive] = 1 AND [purchaseRequestDetail].[companyId]='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::ORACLE) {
                    $this->setAuditFilter(" PURCHASEREQUESTDETAIL.ISACTIVE = 1  AND PURCHASEREQUESTDETAIL.COMPANYID='" . $this->getCompanyId() . "'");
                }
            } else if ($_SESSION['isAdmin'] == 1) {
                if ($this->getVendor() == self::MYSQL) {
                    $this->setAuditFilter("   `purchaserequestdetail`.`companyId`='" . $this->getCompanyId() . "'	");
                } else if ($this->getVendor() == self::MSSQL) {
                    $this->setAuditFilter(" [purchaseRequestDetail].[companyId]='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::ORACLE) {
                    $this->setAuditFilter(" PURCHASEREQUESTDETAIL.COMPANYID='" . $this->getCompanyId() . "' ");
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
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {

            $sql = "
			SELECT                    
							`purchaserequestdetail`.`purchaseRequestDetailId`,
							`company`.`companyDescription`,
							`purchaserequestdetail`.`companyId`,
							`purchaserequest`.`purchaseRequestDescription`,
							`purchaserequestdetail`.`purchaseRequestId`,
							`product`.`productDescription`,
							`purchaserequestdetail`.`productId`,
							`purchaserequestdetail`.`purchaseRequestDetailDescription`,
							`purchaserequestdetail`.`purchaseRequestDetailQuantity`,
							`unitofmeasurement`.`unitOfMeasurementDescription`,
							`purchaserequestdetail`.`unitOfMeasurementId`,
							`chartofaccount`.`chartOfAccountTitle`,
							`purchaserequestdetail`.`chartOfAccountId`,
							`purchaserequestdetail`.`purchaseRequestDetailBudget`,
							
							`purchaserequestdetail`.`isDefault`,
							`purchaserequestdetail`.`isNew`,
							`purchaserequestdetail`.`isDraft`,
							`purchaserequestdetail`.`isUpdate`,
							`purchaserequestdetail`.`isDelete`,
							`purchaserequestdetail`.`isActive`,
							`purchaserequestdetail`.`isApproved`,
							`purchaserequestdetail`.`isReview`,
							`purchaserequestdetail`.`isPost`,
							`purchaserequestdetail`.`executeBy`,
							`purchaserequestdetail`.`executeTime`,
							`staff`.`staffName`
			FROM      `purchaserequestdetail`
			JOIN      `staff`
			ON        `purchaserequestdetail`.`executeBy` = `staff`.`staffId`
			JOIN	`company`
			ON		`company`.`companyId` = `purchaserequestdetail`.`companyId`
			JOIN	`purchaserequest`
			ON		`purchaserequest`.`purchaseRequestId` = `purchaserequestdetail`.`purchaseRequestId`
			JOIN	`product`
			ON		`product`.`productId` = `purchaserequestdetail`.`productId`
			JOIN	`unitofmeasurement`
			ON		`unitofmeasurement`.`unitOfMeasurementId` = `purchaserequestdetail`.`unitOfMeasurementId`
			LEFT 	JOIN	`chartofaccount`
			ON		`chartofaccount`.`chartOfAccountId` = `purchaserequestdetail`.`chartOfAccountId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getPurchaseRequestDetailId(0, 'single')) {
                $sql .= " AND `purchaserequestdetail`.`" . $this->model->getPrimaryKeyName() . "`='" . $this->model->getPurchaseRequestDetailId(0, 'single') . "'";
            }
            if ($this->model->getPurchaseRequestId()) {
                $sql .= " AND `purchaserequestdetail`.`purchaseRequestId`='" . $this->model->getPurchaseRequestId() . "'";
            }
            if ($this->model->getProductId()) {
                $sql .= " AND `purchaserequestdetail`.`productId`='" . $this->model->getProductId() . "'";
            }
            if ($this->model->getUnitOfMeasurementId()) {
                $sql .= " AND `purchaserequestdetail`.`unitOfMeasurementId`='" . $this->model->getUnitOfMeasurementId() . "'";
            }
            if ($this->model->getChartOfAccountId()) {
                $sql .= " AND `purchaserequestdetail`.`chartOfAccountId`='" . $this->model->getChartOfAccountId() . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {

            $sql = "
		  SELECT                    [purchaseRequestDetail].[purchaseRequestDetailId],
                    [company].[companyDescription],
                    [purchaseRequestDetail].[companyId],
                    [purchaseRequest].[purchaseRequestDetailDescription],
                    [purchaseRequestDetail].[purchaseRequestId],
                    [product].[productDescription],
                    [purchaseRequestDetail].[productId],
                    [purchaseRequestDetail].[purchaseRequestDetailDescription],
                    [purchaseRequestDetail].[purchaseRequestDetailQuantity],
                    [unitOfMeasurement].[unitOfMeasurementDescription],
                    [purchaseRequestDetail].[unitOfMeasurementId],
                    [chartOfAccount].[chartOfAccountTitle],
                    [purchaseRequestDetail].[chartOfAccountId],
                    [purchaseRequestDetail].[purchaseRequestDetailBudget],
                    [purchaseRequestDetail].[isDefault],
                    [purchaseRequestDetail].[isNew],
                    [purchaseRequestDetail].[isDraft],
                    [purchaseRequestDetail].[isUpdate],
                    [purchaseRequestDetail].[isDelete],
                    [purchaseRequestDetail].[isActive],
                    [purchaseRequestDetail].[isApproved],
                    [purchaseRequestDetail].[isReview],
                    [purchaseRequestDetail].[isPost],
                    [purchaseRequestDetail].[executeBy],
                    [purchaseRequestDetail].[executeTime],
                    [staff].[staffName] 
		  FROM 	[purchaseRequestDetail]
		  JOIN	[staff]
		  ON	[purchaseRequestDetail].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [purchaseRequestDetail].[companyId]
	JOIN	[purchaseRequest]
	ON		[purchaseRequest].[purchaseRequestId] = [purchaseRequestDetail].[purchaseRequestId]
	JOIN	[product]
	ON		[product].[productId] = [purchaseRequestDetail].[productId]
	JOIN	[unitOfMeasurement]
	ON		[unitOfMeasurement].[unitOfMeasurementId] = [purchaseRequestDetail].[unitOfMeasurementId]
	JOIN	[chartOfAccount]
	ON		[chartOfAccount].[chartOfAccountId] = [purchaseRequestDetail].[chartOfAccountId]
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getPurchaseRequestDetailId(0, 'single')) {
                $sql .= " AND [purchaseRequestDetail].[" . $this->model->getPrimaryKeyName() . "]		=	'" . $this->model->getPurchaseRequestDetailId(0, 'single') . "'";
            }
            if ($this->model->getPurchaseRequestId()) {
                $sql .= " AND [purchaseRequestDetail].[purchaseRequestId]='" . $this->model->getPurchaseRequestId() . "'";
            }
            if ($this->model->getProductId()) {
                $sql .= " AND [purchaseRequestDetail].[productId]='" . $this->model->getProductId() . "'";
            }
            if ($this->model->getUnitOfMeasurementId()) {
                $sql .= " AND [purchaseRequestDetail].[unitOfMeasurementId]='" . $this->model->getUnitOfMeasurementId() . "'";
            }
            if ($this->model->getChartOfAccountId()) {
                $sql .= " AND [purchaseRequestDetail].[chartOfAccountId]='" . $this->model->getChartOfAccountId() . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {

            $sql = "
		  SELECT                    PURCHASEREQUESTDETAIL.PURCHASEREQUESTDETAILID AS \"purchaseRequestDetailId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    PURCHASEREQUESTDETAIL.COMPANYID AS \"companyId\",
                    PURCHASEREQUEST.PURCHASEREQUESTDESCRIPTION AS  \"purchaseRequestDetailDescription\",
                    PURCHASEREQUESTDETAIL.PURCHASEREQUESTID AS \"purchaseRequestId\",
                    PRODUCT.PRODUCTDESCRIPTION AS  \"productDescription\",
                    PURCHASEREQUESTDETAIL.PRODUCTID AS \"productId\",
                    PURCHASEREQUESTDETAIL.purchaseRequestDetailDescription AS \"purchaseRequestDetailDescription\",
                    PURCHASEREQUESTDETAIL.PURCHASEREQUESTDETAILQUANTITY AS \"purchaseRequestDetailQuantity\",
                    UNITOFMEASUREMENT.UNITOFMEASUREMENTDESCRIPTION AS  \"unitOfMeasurementDescription\",
                    PURCHASEREQUESTDETAIL.UNITOFMEASUREMENTID AS \"unitOfMeasurementId\",
                    CHARTOFACCOUNT.CHARTOFACCOUNTTITLE AS  \"chartOfAccountTitle\",
                    PURCHASEREQUESTDETAIL.CHARTOFACCOUNTID AS \"chartOfAccountId\",
                    PURCHASEREQUESTDETAIL.PURCHASEREQUESTDETAILBUDGET AS \"purchaseRequestDetailBudget\",
                    PURCHASEREQUESTDETAIL.ISDEFAULT AS \"isDefault\",
                    PURCHASEREQUESTDETAIL.ISNEW AS \"isNew\",
                    PURCHASEREQUESTDETAIL.ISDRAFT AS \"isDraft\",
                    PURCHASEREQUESTDETAIL.ISUPDATE AS \"isUpdate\",
                    PURCHASEREQUESTDETAIL.ISDELETE AS \"isDelete\",
                    PURCHASEREQUESTDETAIL.ISACTIVE AS \"isActive\",
                    PURCHASEREQUESTDETAIL.ISAPPROVED AS \"isApproved\",
                    PURCHASEREQUESTDETAIL.ISREVIEW AS \"isReview\",
                    PURCHASEREQUESTDETAIL.ISPOST AS \"isPost\",
                    PURCHASEREQUESTDETAIL.EXECUTEBY AS \"executeBy\",
                    PURCHASEREQUESTDETAIL.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\" 
		  FROM 	PURCHASEREQUESTDETAIL 
		  JOIN	STAFF 
		  ON	PURCHASEREQUESTDETAIL.EXECUTEBY = STAFF.STAFFID 
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = PURCHASEREQUESTDETAIL.COMPANYID
	JOIN	PURCHASEREQUEST
	ON		PURCHASEREQUEST.PURCHASEREQUESTID = PURCHASEREQUESTDETAIL.PURCHASEREQUESTID
	JOIN	PRODUCT
	ON		PRODUCT.PRODUCTID = PURCHASEREQUESTDETAIL.PRODUCTID
	JOIN	UNITOFMEASUREMENT
	ON		UNITOFMEASUREMENT.UNITOFMEASUREMENTID = PURCHASEREQUESTDETAIL.UNITOFMEASUREMENTID
	JOIN	CHARTOFACCOUNT
	ON		CHARTOFACCOUNT.CHARTOFACCOUNTID = PURCHASEREQUESTDETAIL.CHARTOFACCOUNTID
         WHERE     " . $this->getAuditFilter();
            if ($this->model->getPurchaseRequestDetailId(0, 'single')) {
                $sql .= " AND PURCHASEREQUESTDETAIL. " . strtoupper($this->model->getPrimaryKeyName()) . "='" . $this->model->getPurchaseRequestDetailId(0, 'single') . "'";
            }
            if ($this->model->getPurchaseRequestId()) {
                $sql .= " AND PURCHASEREQUESTDETAIL.PURCHASEREQUESTID='" . $this->model->getPurchaseRequestId() . "'";
            }
            if ($this->model->getProductId()) {
                $sql .= " AND PURCHASEREQUESTDETAIL.PRODUCTID='" . $this->model->getProductId() . "'";
            }
            if ($this->model->getUnitOfMeasurementId()) {
                $sql .= " AND PURCHASEREQUESTDETAIL.UNITOFMEASUREMENTID='" . $this->model->getUnitOfMeasurementId() . "'";
            }
            if ($this->model->getChartOfAccountId()) {
                $sql .= " AND PURCHASEREQUESTDETAIL.CHARTOFACCOUNTID='" . $this->model->getChartOfAccountId() . "'";
            }
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        /**
         * filter column based on first character 
         */
        if ($this->getCharacterQuery()) {
            if ($this->getVendor() == self::MYSQL) {
                $sql.=" AND `purchaserequestdetail`.`" . $this->model->getFilterCharacter() . "` like '" . $this->getCharacterQuery() . "%'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql.=" AND [purchaseRequestDetail].[" . $this->model->getFilterCharacter() . "] like '" . $this->getCharacterQuery() . "%'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql.=" AND Initcap(PURCHASEREQUESTDETAIL." . strtoupper($this->model->getFilterCharacter()) . ") LIKE Initcap('" . $this->getCharacterQuery() . "%')";
            }
        }
        /**
         * filter column based on Range Of Date 
         * Example Day,Week,Month,Year 
         */
        if ($this->getDateRangeStartQuery()) {
            if ($this->getVendor() == self::MYSQL) {
                $sql.=$this->q->dateFilter('purchaserequestdetail', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery());
            } else if ($this->getVendor() == self::MSSQL) {
                $sql.=$this->q->dateFilter('purchaseRequestDetail', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery());
            } else if ($this->getVendor() == self::ORACLE) {
                $sql.=$this->q->dateFilter('PURCHASEREQUESTDETAIL', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery());
            }
        }
        /**
         * filter column don't want to filter.Example may contain  sensitive information or unwanted to be search. 
         * E.g  $filterArray=array('`leaf`.`leafId`'); 
         * @variables $filterArray; 
         */
        $filterArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $filterArray = array("`purchaserequestdetail`.`purchaseRequestDetailId`",
                "`staff`.`staffPassword`");
        } else if ($this->getVendor() == self::MSSQL) {
            $filterArray = array("[purchaseRequestDetail].[purchaseRequestDetailId]",
                "[staff].[staffPassword]");
        } else if ($this->getVendor() == self::ORACLE) {
            $filterArray = array("PURCHASEREQUESTDETAIL.PURCHASEREQUESTDETAILID",
                "STAFF.STAFFPASSWORD");
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('staff', 'purchaserequestdetail', 'company', 'purchaserequest', 'product', 'unitofmeasurement', 'chartofaccount');
        } else if ($this->getVendor() == self::MSSQL) {
            $tableArray = array('staff', 'purchaserequestdetail', 'company', 'purchaserequest', 'product', 'unitofmeasurement', 'chartofaccount');
        } else if ($this->getVendor() == self::ORACLE) {
            $tableArray = array('STAFF', 'PURCHASEREQUESTDETAIL', 'COMPANY', 'PURCHASEREQUEST', 'PRODUCT', 'UNITOFMEASUREMENT', 'CHARTOFACCOUNT');
        }
        $tempSql = null;
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
        $tempSql2 = null;
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
        if ($this->getSortField()) {
            if ($this->getVendor() == self::MYSQL) {
                $sql .= "	ORDER BY `" . $this->getSortField() . "` " . $this->getOrder() . " ";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql .= "	ORDER BY [" . $this->getSortField() . "] " . $this->getOrder() . " ";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql .= "	ORDER BY " . strtoupper($this->getSortField()) . " " . strtoupper($this->getOrder()) . " ";
            }
        } else {
            // @note sql server 2012 must order by first then offset ??
            if ($this->getVendor() == self::MSSQL) {
                $sql .= "	ORDER BY [" . $this->model->getTableName() . "].[" . $this->model->getPrimaryKeyName() . "] ASC ";
            }
        }
        $_SESSION ['sql'] = $sql; // push to session so can make report via excel and pdf 
        $_SESSION ['start'] = $this->getStart();
        $_SESSION ['limit'] = $this->getLimit();
        $sqlDerived = null;
        if ($this->getLimit()) {
            // only mysql have limit 
            $sqlDerived = $sql . " LIMIT  " . $this->getStart() . "," . $this->getLimit() . " ";
            if ($this->getVendor() == self::MYSQL) {
                
            } else if ($this->getVendor() == self::MSSQL) {
                /**
                 * Sql Server  2012 format only.Row Number
                 * Parameter Query We don't support 
                 **/
                $sqlDerived = $sql . " 	OFFSET  	" . $this->getStart() . " ROWS
											FETCH NEXT 	" . $this->getLimit() . " ROWS ONLY ";
            } else if ($this->getVendor() == self::ORACLE) {
                /**
                 * Oracle using derived table also 
                 * */
                $sqlDerived = "

						SELECT *

						FROM 	(
 	
									SELECT	a.*,

											rownum r

									FROM ( " . $sql . "
 
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
        if (!($this->model->getPurchaseRequestDetailId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 20;
            if ($this->model->getPurchaseRequestDetailId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getPurchaseRequestDetailId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getPurchaseRequestDetailId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else if ($this->getPageOutput() == 'table') {
            $this->setService('html');
            $str = null;
            if (is_array($items)) {
                $this->setServiceOutput('html');
                $totalRecordDetail = intval(count($items));
                if ($totalRecordDetail > 0) {
                    $counter = 0;
                    for ($j = 0; $j < $totalRecordDetail; $j++) {
                        $counter++;
                        $str.="<tr id='" . $items[$j]['purchaseRequestDetailId'] . "'>";
                    
                        $str.="<input type=\"hidden\" name=\"purchaseRequestId[]\" id=\"purchaseRequestId" . $items[$j]['purchaseRequestDetailId'] . "\" value=\"" . $items[$j]['purchaseRequestId'] . "\">";
                        $productArray = $this->getProduct();
                        $str.="<td class=\"form-group\" id=\"productId" . $items[$j]['purchaseRequestDetailId'] . "Detail\">\n";
                        $str.="<select name=\"productId[]\" id=\"productId" . $items[$j]['purchaseRequestDetailId'] . "\" class=\"chzn-select form-control\">\n";
                        $str.="<option value=\"\">" . $this->t['pleaseSelectTextLabel'] . "</option>";
                        if (is_array($productArray)) {
                            $totalRecord = intval(count($productArray));
                            if ($totalRecord > 0) {
                                for ($i = 0; $i < $totalRecord; $i++) {
                                    if ($items[$j]['productId'] == $productArray[$i]['productId']) {
                                        $selected = 'selected';
                                    } else {
                                        $selected = NULL;
                                    }
                                    $str.="<option value='" . $productArray[$i]['productId'] . "' " . $selected . ">" . $productArray[$i]['productDescription'] . "</option>\n";
                                }
                            } else {
                                $str.="<option value=\"\">" . $this->t['notAvailableTextLabel'] . "</option>\n";
                            }
                        } else {
                            $str.="<option value=\"\">" . $this->t['notAvailableTextLabel'] . "</option>\n";
                        }
                        $str.="</select></td>\n";
                        $str.="<td valign=\"top\" class=\"form-group\" id=\"purchaseRequestDetailQuantityDetail" . $items[$j]['purchaseRequestDetailId'] . "\" style=\"width:75px\"><input class=\"form-control\" type=\"text\" name=\"purchaseRequestDetailQuantity[]\" id=\"purchaseRequestDetailQuantity" . $items[$j]['purchaseRequestDetailId'] . "\" value=\"" . $items[$j]['purchaseRequestDetailQuantity'] . "\"></td>";
                        $unitOfMeasurementArray = $this->getUnitOfMeasurement();
                        $str.="<td valign=\"top\" class=\"form-group\" id=\"unitOfMeasurementIdDetail" . $items[$j]['purchaseRequestDetailId'] . "\" style=\"width:100px\">\n";
                        $str.="<select name=\"unitOfMeasurementId[]\" id=\"unitOfMeasurementId" . $items[$j]['purchaseRequestDetailId'] . "\" class=\"chzn-select form-control\">\n";
                        $str.="<option value=\"\">" . $this->t['pleaseSelectTextLabel'] . "</option>\n";
                        if (is_array($unitOfMeasurementArray)) {
                            $totalRecord = intval(count($unitOfMeasurementArray));
                            if ($totalRecord > 0) {
                                for ($i = 0; $i < $totalRecord; $i++) {
                                    if ($items[$j]['unitOfMeasurementId'] == $unitOfMeasurementArray[$i]['unitOfMeasurementId']) {
                                        $selected = 'selected';
                                    } else {
                                        $selected = NULL;
                                    }
                                    $str.="<option value='" . $unitOfMeasurementArray[$i]['unitOfMeasurementId'] . "' " . $selected . ">" . $unitOfMeasurementArray[$i]['unitOfMeasurementTitle'] . " " . $unitOfMeasurementArray[$i]['unitOfMeasurementDescription'] . "</option>\n";
                                }
                            } else {
                                $str.="<option value=\"\">" . $this->t['notAvailableTextLabel'] . "</option>\n";
                            }
                        } else {
                            $str.="<option value=\"\">" . $this->t['notAvailableTextLabel'] . "</option>\n";
                        }
                        $str.="</select></td>\n";
                        $chartOfAccountArray = $this->getChartOfAccount();
                        $str.="<td valign=\"top\" class=\"form-group\" id=\"chartOfAccountIdDetail9999\">\n";
                        $str.="<select name=\"chartOfAccountId[]\" id=\"chartOfAccountId" . $items[$j]['purchaseRequestDetailId'] . "\" class=\"chzn-select form-control\" onChange=\"getBudget(".$this->getLeafId().", '".$this->getControllerPath()."', '".$this->getSecurityToken()."'," . $items[$j]['purchaseRequestDetailId'] . ");\">\n";
                        $str.="<option value=\"\">" . $this->t['pleaseSelectTextLabel'] . "</option>";
                        $currentChartOfAccountTypeDescription=null;
                        if (is_array($chartOfAccountArray)) {
                            $totalRecord = intval(count($chartOfAccountArray));
                            if ($totalRecord > 0) {
                                for ($i = 0; $i < $totalRecord; $i++) {
                                    if ($i != 0) {
                                        if ($currentChartOfAccountTypeDescription != $chartOfAccountArray[$i]['chartOfAccountTypeDescription']) {
                                            $str.= "</optgroup><optgroup label=\"" . $chartOfAccountArray[$i]['chartOfAccountTypeDescription'] . "\">\n";
                                        }
                                    } else {
                                        $str.= "<optgroup label=\"" . $chartOfAccountArray[$i]['chartOfAccountTypeDescription'] . "\">\n";
                                    }
                                    $currentChartOfAccountTypeDescription = $chartOfAccountArray[$i]['chartOfAccountTypeDescription'];
                                    if ($items[$j]['chartOfAccountId'] == $chartOfAccountArray[$i]['chartOfAccountId']) {
                                        $selected = 'selected';
                                    } else {
                                        $selected = NULL;
                                    }
                                    $str.="<option value='" . $chartOfAccountArray[$i]['chartOfAccountId'] . "' " . $selected . ">" . $chartOfAccountArray[$i]['chartOfAccountNumber'] . " " . $chartOfAccountArray[$i]['chartOfAccountTitle'] . "</option>\n";
                                }
                                $str.="</optgroup>\n";
                            } else {
                                $str.="<option value=\"\">" . $this->t['notAvailableTextLabel'] . "</option>\n";
                            }
                        } else {
                            $str.="<option value=\"\">" . $this->t['notAvailableTextLabel'] . "</option>\n";
                        }
                        $str.="</select></td>\n";
                        $str.="<td vAlign=\"top\" align=\"center\"><input class=\"form-control\" type=\"text\" name=\"purchaseRequestDetailBudget[]\" id=\"purchaseRequestDetailBudget" . $items[$j]['purchaseRequestDetailId'] . "\" value=\"" . $items[$j]['purchaseRequestDetailBudget'] . "\"></td>\n";
                        
						$str.="</tr>\n";
						$str.=" <tr>
     													<td colspan=\"4\"><textarea name=\"purchaseRequestDetailDescription".$items[$j]['purchaseRequestDetailId']."\" id=\"purchaseRequestDetailDescription".$items[$j]['purchaseRequestDetailId']."\" class=\"form-control\">\n";
																	$str.=$items[$j]['purchaseRequestDetailDescription']; 
						$str.="</textarea></td>\n";
						$str.="<td align=\"center\"><div class=\"btn-group\" align=\"center\">";
                        $str.="<input type='hidden' name='purchaseRequestDetailId[]' id='purchaseRequestDetailId" . $items[$j]['purchaseRequestDetailId'] . "'  value='" . $items[$j]['purchaseRequestDetailId'] . "'>";
                        $str.="<input type='hidden' name='purchaseRequestId[]'
                    id='purchaseRequestDetailId" . $items[$j]['purchaseRequestId'] . "'
                        value='" . $items[$j]['purchaseRequestId'] . "'>";
                        $str.="<button type=\"button\" class=\"btn btn-warning btn-mini\" title=\"Edit\" onClick=\"showFormUpdateDetail('" . $this->getLeafId() . "','" . $this->getControllerPath() . "','" . $this->getSecurityToken() . "','" . $items[$j]['purchaseRequestDetailId'] . "')\"><i class=\"glyphicon glyphicon-edit glyphicon-white\"></i></button>";
                        $str.="<button type=\"button\" class=\"btn btn-danger btn-mini\" title=\"Delete\" onClick=\"showModalDeleteDetail('" . $items[$j]['purchaseRequestDetailId'] . "')\"><i class=\"glyphicon glyphicon-trash  glyphicon-white\"></i></button><div id=\"miniInfoPanel" . $items[$j]['purchaseRequestDetailId'] . "\"></div></td>";
                    }
                } else {
                    $str.="<tr>";
                    $str.="<td colspan=\"6\" align=\"center\">" . $this->exceptionMessageReturn($this->t['recordNotFoundLabel']) . "</td>";
                    $str.="</tr>";
                }
            } else {
                $str.="<tr>";
                $str.="<td colspan=\"6\" align=\"center\">" . $this->exceptionMessageReturn($this->t['recordNotFoundLabel']) . "</td>";
                $str.="</tr>";
            }
            echo json_encode(array('success' => true, 'tableData' => $str));
            exit();
        } else if ($this->getPageOutput() == 'json') {
            if ($this->model->getPurchaseRequestDetailId(0, 'single')) {
                $end = microtime(true);
                $time = $end - $start;
                echo str_replace(array("[", "]"), "", json_encode(array(
                    'success' => true,
                    'total' => $total,
                    'message' => $this->t['viewRecordMessageLabel'],
                    'time' => $time,
                    'firstRecord' => $this->firstRecord('value'),
                    'previousRecord' => $this->previousRecord('value', $this->model->getPurchaseRequestDetailId(0, 'single')),
                    'nextRecord' => $this->nextRecord('value', $this->model->getPurchaseRequestDetailId(0, 'single')),
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
                    'success' => true,
                    'total' => $total,
                    'message' => $this->t['viewRecordMessageLabel'],
                    'time' => $time,
                    'firstRecord' => $this->recordSet->firstRecord('value'),
                    'previousRecord' => $this->recordSet->previousRecord('value', $this->model->getPurchaseRequestDetailId(0, 'single')),
                    'nextRecord' => $this->recordSet->nextRecord('value', $this->model->getPurchaseRequestDetailId(0, 'single')),
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
        $sql = null;
        if (!$this->model->getPurchaseRequestId()) {
            $this->model->setPurchaseRequestId($this->service->getPurchaseRequestDefaultValue());
        }
        if (!$this->model->getProductId()) {
            $this->model->setProductId($this->service->getProductDefaultValue());
        }
        if (!$this->model->getUnitOfMeasurementId()) {
            $this->model->setUnitOfMeasurementId($this->service->getUnitOfMeasurementDefaultValue());
        }
        if (!$this->model->getChartOfAccountId()) {
            $this->model->setChartOfAccountId($this->service->getChartOfAccountDefaultValue());
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = " 
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`purchaseRequestDetail`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getPurchaseRequestDetailId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
           SELECT	[" . $this->model->getPrimaryKeyName() . "] 
           FROM 	[purchaseRequestDetail] 
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getPurchaseRequestDetailId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
           FROM 	PURCHASEREQUESTDETAIL 
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getPurchaseRequestDetailId(0, 'single') . "' ";
        }
        $result = $this->q->fast($sql);
        $total = $this->q->numberRows($result, $sql);
        if ($total == 0) {
            echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel']));
            exit();
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
               UPDATE `purchaserequestdetail` SET 
                       `purchaseRequestId` = '" . $this->model->getPurchaseRequestId() . "',
                       `productId` = '" . $this->model->getProductId() . "',
                       `purchaseRequestDetailDescription` = '" . $this->model->getPurchaseRequestDetailDescription() . "',
                       `purchaseRequestDetailQuantity` = '" . $this->model->getPurchaseRequestDetailQuantity() . "',
                       `unitOfMeasurementId` = '" . $this->model->getUnitOfMeasurementId() . "',
                       `chartOfAccountId` = '" . $this->model->getChartOfAccountId() . "',
                       `purchaseRequestDetailBudget` = '" . $this->model->getPurchaseRequestDetailBudget() . "',
                       `isDefault` = '" . $this->model->getIsDefault('0', 'single') . "',
                       `isNew` = '" . $this->model->getIsNew('0', 'single') . "',
                       `isDraft` = '" . $this->model->getIsDraft('0', 'single') . "',
                       `isUpdate` = '" . $this->model->getIsUpdate('0', 'single') . "',
                       `isDelete` = '" . $this->model->getIsDelete('0', 'single') . "',
                       `isActive` = '" . $this->model->getIsActive('0', 'single') . "',
                       `isApproved` = '" . $this->model->getIsApproved('0', 'single') . "',
                       `isReview` = '" . $this->model->getIsReview('0', 'single') . "',
                       `isPost` = '" . $this->model->getIsPost('0', 'single') . "',
                       `executeBy` = '" . $this->model->getExecuteBy('0', 'single') . "',
                       `executeTime` = " . $this->model->getExecuteTime() . "
               WHERE    `purchaseRequestDetailId`='" . $this->model->getPurchaseRequestDetailId('0', 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE [purchaseRequestDetail] SET 
                       [purchaseRequestId] = '" . $this->model->getPurchaseRequestId() . "',
                       [productId] = '" . $this->model->getProductId() . "',
                       [purchaseRequestDetailDescription] = '" . $this->model->getPurchaseRequestDetailDescription() . "',
                       [purchaseRequestDetailQuantity] = '" . $this->model->getPurchaseRequestDetailQuantity() . "',
                       [unitOfMeasurementId] = '" . $this->model->getUnitOfMeasurementId() . "',
                       [chartOfAccountId] = '" . $this->model->getChartOfAccountId() . "',
                       [purchaseRequestDetailBudget] = '" . $this->model->getPurchaseRequestDetailBudget() . "',
                       [isDefault] = '" . $this->model->getIsDefault(0, 'single') . "',
                       [isNew] = '" . $this->model->getIsNew(0, 'single') . "',
                       [isDraft] = '" . $this->model->getIsDraft(0, 'single') . "',
                       [isUpdate] = '" . $this->model->getIsUpdate(0, 'single') . "',
                       [isDelete] = '" . $this->model->getIsDelete(0, 'single') . "',
                       [isActive] = '" . $this->model->getIsActive(0, 'single') . "',
                       [isApproved] = '" . $this->model->getIsApproved(0, 'single') . "',
                       [isReview] = '" . $this->model->getIsReview(0, 'single') . "',
                       [isPost] = '" . $this->model->getIsPost(0, 'single') . "',
                       [executeBy] = '" . $this->model->getExecuteBy(0, 'single') . "',
                       [executeTime] = " . $this->model->getExecuteTime() . "
                WHERE   [purchaseRequestDetailId]='" . $this->model->getPurchaseRequestDetailId('0', 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE PURCHASEREQUESTDETAIL SET
                        PURCHASEREQUESTID = '" . $this->model->getPurchaseRequestId() . "',
                       PRODUCTID = '" . $this->model->getProductId() . "',
                       purchaseRequestDetailDescription = '" . $this->model->getPurchaseRequestDetailDescription() . "',
                       PURCHASEREQUESTDETAILQUANTITY = '" . $this->model->getPurchaseRequestDetailQuantity() . "',
                       UNITOFMEASUREMENTID = '" . $this->model->getUnitOfMeasurementId() . "',
                       CHARTOFACCOUNTID = '" . $this->model->getChartOfAccountId() . "',
                       PURCHASEREQUESTDETAILBUDGET = '" . $this->model->getPurchaseRequestDetailBudget() . "',
                       ISDEFAULT = '" . $this->model->getIsDefault(0, 'single') . "',
                       ISNEW = '" . $this->model->getIsNew(0, 'single') . "',
                       ISDRAFT = '" . $this->model->getIsDraft(0, 'single') . "',
                       ISUPDATE = '" . $this->model->getIsUpdate(0, 'single') . "',
                       ISDELETE = '" . $this->model->getIsDelete(0, 'single') . "',
                       ISACTIVE = '" . $this->model->getIsActive(0, 'single') . "',
                       ISAPPROVED = '" . $this->model->getIsApproved(0, 'single') . "',
                       ISREVIEW = '" . $this->model->getIsReview(0, 'single') . "',
                       ISPOST = '" . $this->model->getIsPost(0, 'single') . "',
                       EXECUTEBY = '" . $this->model->getExecuteBy(0, 'single') . "',
                       EXECUTETIME = " . $this->model->getExecuteTime() . "
                WHERE  PURCHASEREQUESTDETAILID='" . $this->model->getPurchaseRequestDetailId('0', 'single') . "'";
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
                array("success" => true,
                    "message" => $this->t['updateRecordTextLabel'],
                    "time" => $time));
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
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = " 
           SELECT	`" . $this->model->getPrimaryKeyName() . "` 
           FROM 	`purchaserequestdetail` 
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getPurchaseRequestDetailId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
           SELECT	[" . $this->model->getPrimaryKeyName() . "]  
           FROM 	[purchaseRequestDetail] 
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getPurchaseRequestDetailId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
           FROM 	PURCHASEREQUESTDETAIL 
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getPurchaseRequestDetailId(0, 'single') . "' ";
        }
        try {
            $result = $this->q->fast($sql);
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
                $sql = "
               UPDATE  `purchaserequestdetail` 
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
               WHERE   `purchaseRequestDetailId`   =  '" . $this->model->getPurchaseRequestDetailId(0, 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
               UPDATE  [purchaseRequestDetail] 
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
               WHERE   [purchaseRequestDetailId]	=  '" . $this->model->getPurchaseRequestDetailId(0, 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
               UPDATE  PURCHASEREQUESTDETAIL 
               SET     ISDEFAULT       =   '" . $this->model->getIsDefault(0, 'single') . "',
                       ISNEW           =   '" . $this->model->getIsNew(0, 'single') . "',
                       ISDRAFT         =   '" . $this->model->getIsDraft(0, 'single') . "',
                       ISUPDATE        =   '" . $this->model->getIsUpdate(0, 'single') . "',
                       ISDELETE        =   '" . $this->model->getIsDelete(0, 'single') . "',
                       ISACTIVE        =   '" . $this->model->getIsActive(0, 'single') . "',
                       ISAPPROVED      =   '" . $this->model->getIsApproved(0, 'single') . "',
                       ISREVIEW        =   '" . $this->model->getIsReview(0, 'single') . "',
                       ISPOST          =   '" . $this->model->getIsPost(0, 'single') . "',
                       EXECUTEBY       =   '" . $this->model->getExecuteBy() . "',
                       EXECUTETIME     =   " . $this->model->getExecuteTime() . "
               WHERE   PURCHASEREQUESTDETAILID	=  '" . $this->model->getPurchaseRequestDetailId(0, 'single') . "'";
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
                array("success" => true,
                    "message" => $this->t['deleteRecordTextLabel'],
                    "time" => $time));
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
               UPDATE `".strtolower($this->model->getTableName())."` 
               SET	   `executeBy`		=	'".$this->model->getExecuteBy()."',
                       `executeTime`	=	".$this->model->getExecuteTime().",";
        } else if ($this->getVendor() == self::MSSQL) { 
               $sql = " 
               UPDATE 	[".$this->model->getTableName()."] 
               SET	   [executeBy]		=	'".$this->model->getExecuteBy()."',
                       [executeTime]	=	".$this->model->getExecuteTime().",";
        } else if ($this->getVendor() == self::ORACLE) { 
               $sql = " 
               UPDATE ".strtoupper($this->model->getTableName())." 
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
                         $sqlLooping .= " `isDefault` = CASE `".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isDefault] = CASE [" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISDEFAULT = CASE ".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getDiscountTypeId($i, 'array') . "
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
                         $sqlLooping .= " `isDraft` = CASE `".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isDraft] = CASE [" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISDRAFT = CASE ".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getDiscountTypeId($i, 'array') . "
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
                         $sqlLooping .= " `isNew` = CASE `".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isNew] = CASE [" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISNEW = CASE ".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getDiscountTypeId($i, 'array') . "
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
                         $sqlLooping .= " `isActive` = CASE `".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isActive] = CASE [" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISACTIVE = CASE ".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getDiscountTypeId($i, 'array') . "
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
                         $sqlLooping .= " `isUpdate` = CASE `".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isUpdate] = CASE [" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISUPDATE = CASE ".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getDiscountTypeId($i, 'array') . "
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
                         $sqlLooping .= " `isDelete` = CASE `".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isDelete] = CASE [" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISDELETE = CASE ".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getDiscountTypeId($i, 'array') . "
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
                         $sqlLooping .= " `isReview` = CASE `".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isReview] = CASE [" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISREVIEW = CASE ".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getDiscountTypeId($i, 'array') . "
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
                         $sqlLooping .= " `isPost` = CASE `".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isPost] = CASE [" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISPOST = CASE ".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getDiscountTypeId($i, 'array') . "
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
                         $sqlLooping .= " `isApproved` = CASE `".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isApproved] = CASE [" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISAPPROVED = CASE ".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getDiscountTypeId($i, 'array') . "
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
                         $sqlLooping .=" `isDelete` = CASE `" . $this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isDelete] = CASE [" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISDELETE = CASE ".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     }else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getDiscountTypeId($i, 'array') . "
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
                         $sqlLooping .=" `isActive` = CASE `" . $this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isActive] = CASE [" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISACTIVE = CASE ".strtoupper($this->model->getPrimaryKeyName()) . " "; 
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
                         WHEN " . $this->model->getDiscountTypeId($i, 'array') . "
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
     * @return void
     */
    function duplicate() {
        
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
     * Return  Purchase Request 
     * @return null|string
     */
    public function getPurchaseRequest() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getPurchaseRequest();
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
     * Return  Unit Of Measurement 
     * @return null|string
     */
    public function getUnitOfMeasurement() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getUnitOfMeasurement();
    }

    /**
     * Return  Chart Of Account 
     * @return null|string
     */
    public function getChartOfAccount() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getChartOfAccount();
    }
	/**
     * Return  Business Partner
     * @return null|string
     */
    public function getBusinessPartner() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getBusinessPartner();
    }

    /**
     * Return Total Record Of The  
     * return int Total Record
     */
    private function getTotalRecord() {
        $sql = null;
        $total = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT  count(*) AS `total` 
         FROM    `purchaserequestdetail`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
            $sql.="AND     `purchaseRequestId` = " . $this->model->getPurchaseRequestId() . " ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT    COUNT(*) AS total 
         FROM      [purchaseRequestDetail]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
            $sql.="AND     [purchaseRequestId] = " . $this->model->getPurchaseRequestId() . " ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT    COUNT(*)    AS  \"total\" 
         FROM      PURCHASEREQUESTDETAIL
         WHERE     ISACTIVE    =   1
         AND       COMPANYID   =   " . $this->getCompanyId() . " ";
            $sql.="AND     PURCHASEREQUESTID = " . $this->model->getPurchaseRequestId() . " ";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result) > 0) {
                $row = $this->q->fetchArray($result);
                $total = $row['total'];
            }
        } return $total;
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
        $username = null;
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
        } else {
            $username = 'Who the fuck are you';
        }
        $this->excel->getProperties()
                ->setCreator($username)
                ->setLastModifiedBy($username)
                ->setTitle($this->getReportTitle())
                ->setSubject('purchaseRequestDetail')
                ->setDescription('Generated by PhpExcel an Idcms Generator')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('financial/accountPayable');
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
        $this->excel->getActiveSheet()->setCellValue('B2', $this->getReportTitle());
        $this->excel->getActiveSheet()->setCellValue('K2', '');
        $this->excel->getActiveSheet()->mergeCells('B2:K2');
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.');
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['purchaseRequestIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['productIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['purchaseRequestDetailDescriptionLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['purchaseRequestDetailQuantityLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['unitOfMeasurementIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['chartOfAccountIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['purchaseRequestDetailBudgetLabel']);
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['executeByLabel']);
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['executeTimeLabel']);
        // 
        $loopRow = 4;
        $i = 0;
        \PHPExcel_Cell::setValueBinder(new \PHPExcel_Cell_AdvancedValueBinder());
        $lastRow = null;
        while (($row = $this->q->fetchAssoc()) == TRUE) {
            //	echo print_r($row); 
            $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i);
            $this->excel->getActiveSheet()->setCellValue('C' . $loopRow, strip_tags($row ['purchaseRequestDetailDescription']));
            $this->excel->getActiveSheet()->setCellValue('D' . $loopRow, strip_tags($row ['productDescription']));
            $this->excel->getActiveSheet()->setCellValue('E' . $loopRow, strip_tags($row ['purchaseRequestDetailDescription']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('F' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->excel->getActiveSheet()->setCellValue('F' . $loopRow, strip_tags($row ['purchaseRequestDetailQuantity']));
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, strip_tags($row ['unitOfMeasurementDescription']));
            $this->excel->getActiveSheet()->setCellValue('H' . $loopRow, strip_tags($row ['chartOfAccountTitle']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('I' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->excel->getActiveSheet()->setCellValue('I' . $loopRow, strip_tags($row ['purchaseRequestDetailBudget']));
            $this->excel->getActiveSheet()->setCellValue('J' . $loopRow, strip_tags($row ['staffName']));
            $this->excel->getActiveSheet()->setCellValue('K' . $loopRow, strip_tags($row ['executeTime']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('K' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $loopRow++;
            $lastRow = 'K' . $loopRow;
        }
        $from = 'B2';
        $to = $lastRow;
        $formula = $from . ":" . $to;
        $this->excel->getActiveSheet()->getStyle($formula)->applyFromArray($styleThinBlackBorderOutline);
        $extension = null;
        $folder = null;
        switch ($this->getReportMode()) {
            case 'excel':
                //	$objWriter = \PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
                //optional lock.on request only
                // $objPHPExcel->getSecurity()->setLockWindows(true);
                // $objPHPExcel->getSecurity()->setLockStructure(true);
                // $objPHPExcel->getSecurity()->setWorkbookPassword('PHPExcel');
                $objWriter = new \PHPExcel_Writer_Excel2007($this->excel);
                $extension = '.xlsx';
                $folder = 'excel';
                $filename = "purchaseRequestDetail" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot() . "v3/financial/accountPayable/document/" . $folder . "/" . $filename;
                $this->documentTrail->createTrail($this->getLeafId(), $path, $filename);
                $objWriter->save($path);
                $file = fopen($path, 'r');
                if ($file) {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array("success" => true,
                                "message" => $this->t['fileGenerateMessageLabel'],
                                "filename" => $filename,
                                "folder" => $folder,
                                "time" => $time));
                    exit();
                } else {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array("success" => false,
                                "message" => $this->t['fileNotGenerateMessageLabel'],
                                "time" => $time));
                    exit();
                }
                break;
            case 'excel5':
                $objWriter = new \PHPExcel_Writer_Excel5($this->excel);
                $extension = '.xls';
                $folder = 'excel';
                $filename = "purchaseRequestDetail" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot() . "v3/financial/accountPayable/document/" . $folder . "/" . $filename;
                $this->documentTrail->createTrail($this->getLeafId(), $path, $filename);
                $objWriter->save($path);
                $file = fopen($path, 'r');
                if ($file) {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array("success" => true,
                                "message" => $this->t['fileGenerateMessageLabel'],
                                "filename" => $filename,
                                "folder" => $folder,
                                "time" => $time));
                    exit();
                } else {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array("success" => false,
                                "message" => $this->t['fileNotGenerateMessageLabel'],
                                "time" => $time));
                    exit();
                }
                break;
            case 'pdf':
                break;
            case 'html':
                $objWriter = new \PHPExcel_Writer_HTML($this->excel);
                // $objWriter->setUseBOM(true); 
                $extension = '.html';
                //$objWriter->setPreCalculateFormulas(false); //calculation off 
                $folder = 'html';
                $filename = "purchaseRequestDetail" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot() . "v3/financial/accountPayable/document/" . $folder . "/" . $filename;
                $this->documentTrail->createTrail($this->getLeafId(), $path, $filename);
                $objWriter->save($path);
                $file = fopen($path, 'r');
                if ($file) {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array("success" => true,
                                "message" => $this->t['fileGenerateMessageLabel'],
                                "filename" => $filename,
                                "folder" => $folder,
                                "time" => $time));
                    exit();
                } else {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array("success" => false,
                                "message" => $this->t['fileNotGenerateMessageLabel'],
                                "time" => $time));
                    exit();
                }
                break;
            case 'csv':
                $objWriter = new \PHPExcel_Writer_CSV($this->excel);
                // $objWriter->setUseBOM(true); 
                // $objWriter->setPreCalculateFormulas(false); //calculation off 
                $extension = '.csv';
                $folder = 'excel';
                $filename = "purchaseRequestDetail" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot() . "v3/financial/accountPayable/document/" . $folder . "/" . $filename;
                $this->documentTrail->createTrail($this->getLeafId(), $path, $filename);
                $objWriter->save($path);
                $file = fopen($path, 'r');
                if ($file) {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array("success" => true,
                                "message" => $this->t['fileGenerateMessageLabel'],
                                "filename" => $filename,
                                "folder" => $folder,
                                "time" => $time));
                    exit();
                } else {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array("success" => false,
                                "message" => $this->t['fileNotGenerateMessageLabel'],
                                "time" => $time));
                    exit();
                }
                break;
        }
    }

}

if (isset($_POST ['method'])) {
    if (isset($_POST['output'])) {
        $purchaseRequestDetailObject = new PurchaseRequestDetailClass ();
        if ($_POST['securityToken'] != $purchaseRequestDetailObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $purchaseRequestDetailObject->setLeafId($_POST ['leafId']);
        }
        if (isset($_POST ['offset'])) {
            $purchaseRequestDetailObject->setStart($_POST ['offset']);
        }
        if (isset($_POST ['limit'])) {
            $purchaseRequestDetailObject->setLimit($_POST ['limit']);
        }
        $purchaseRequestDetailObject->setPageOutput($_POST['output']);
        $purchaseRequestDetailObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $purchaseRequestDetailObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $purchaseRequestDetailObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $purchaseRequestDetailObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $purchaseRequestDetailObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$purchaseRequestDetailObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$purchaseRequestDetailObject->delete(); 
        }
    }
}
if (isset($_GET ['method'])) {
    $purchaseRequestDetailObject = new PurchaseRequestDetailClass ();
    if ($_GET['securityToken'] != $purchaseRequestDetailObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $purchaseRequestDetailObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $purchaseRequestDetailObject->execute();
    /*
     * Update Status of The Table. Admin Level Only 
     */
    if ($_GET ['method'] == 'updateStatus') {
        $purchaseRequestDetailObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key 
     */
    if ($_GET['method'] == 'duplicate') {
        $purchaseRequestDetailObject->duplicate();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $purchaseRequestDetailObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $purchaseRequestDetailObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $purchaseRequestDetailObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $purchaseRequestDetailObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $purchaseRequestDetailObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml') {
            $purchaseRequestDetailObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        $purchaseRequestDetailObject->setServiceOutput('option');
        if (($_GET['filter'] == 'purchaseRequest')) {
            $purchaseRequestDetailObject->getPurchaseRequest();
        }
        if (($_GET['filter'] == 'product')) {
            $purchaseRequestDetailObject->getProduct();
        }
        if (($_GET['filter'] == 'unitOfMeasurement')) {
            $purchaseRequestDetailObject->getUnitOfMeasurement();
        }
        if (($_GET['filter'] == 'chartOfAccount')) {
            $purchaseRequestDetailObject->getChartOfAccount();
        }
    }
}
?>
