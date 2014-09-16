<?php

namespace Core\Financial\Inventory\ProductResources\Controller;

use Core\ConfigClass;
use Core\Financial\Inventory\ProductResources\Model\ProductResourcesModel;
use Core\Financial\Inventory\ProductResources\Service\ProductResourcesService;
use Core\Document\Trail\DocumentTrailClass;
use Core\RecordSet\RecordSet;
use Core\shared\SharedClass;

if (!isset($_SESSION)) {
    session_start();
}
$x = addslashes(realpath(__FILE__));
// auto detect if \ consider come from windows else / from Linux
$pos = strpos($x, "\\");
if ($pos !== false) {
    $d = explode("\\", $x);
} else {
    $d = explode("/", $x);
}
$newPath = null;
for ($i = 0; $i < count($d); $i++) {
    // if find the library or package then stop
    if ($d[$i] == 'library' || $d[$i] == 'v2' || $d[$i] == 'v3') {
        break;
    }
    $newPath[] .= $d[$i] . "/";
}
$fakeDocumentRoot = null;
for ($z = 0; $z < count($newPath); $z++) {
    $fakeDocumentRoot .= $newPath[$z];
}
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot); // start
require_once ($newFakeDocumentRoot . "library/class/classAbstract.php");
require_once ($newFakeDocumentRoot . "library/class/classRecordSet.php");
require_once ($newFakeDocumentRoot . "library/class/classDate.php");
require_once ($newFakeDocumentRoot . "library/class/classDocumentTrail.php");
require_once ($newFakeDocumentRoot . "library/class/classShared.php");
require_once ($newFakeDocumentRoot . "v3/system/document/model/documentModel.php");
require_once ($newFakeDocumentRoot . "v3/financial/inventory/model/productResourcesModel.php");
require_once ($newFakeDocumentRoot . "v3/financial/inventory/service/productResourcesService.php");

/**
 * Class ProductResources
 * this is productResources controller files. 
 * @name IDCMS 
 * @version 2 
 * @author hafizan 
 * @package  Core\Financial\Inventory\ProductResources\Controller 
 * @subpackage Inventory 
 * @link http://www.hafizan.com 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL 
 */
class ProductResourcesClass extends ConfigClass {

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
     * @var \Core\Financial\Inventory\ProductResources\Model\ProductResourcesModel 
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request 
     * @var \Core\Financial\Inventory\ProductResources\Service\ProductResourcesService 
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
        $this->setViewPath("./v3/financial/inventory/view/productResources.php");
        $this->setControllerPath("./v3/financial/inventory/controller/productResourcesController.php");
        $this->setServicePath("./v3/financial/inventory/service/productResourcesService.php");
    }

    /**
     * Class Loader 
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new ProductResourcesModel();
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

        $this->setReportTitle($applicationNative . " :: " . $moduleNative . " :: " . $folderNative . " :: " . $leafNative);

        $this->service = new ProductResourcesService();
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
        if (!$this->model->getProductBatchId()) {
            $this->model->setProductBatchId($this->service->getProductBatchDefaultValue());
        }
        if (!$this->model->getInvoiceId()) {
            $this->model->setInvoiceId($this->service->getInvoiceDefaultValue());
        }
        //$this->model->setDocumentNumber($this->getDocumentNumber());
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `productresources` 
            (
                 `companyId`,
                 `productBatchId`,
                 `invoiceId`,
                 `productResourcesTask`,
                 `productResourcesEstimatedDate`,
                 `productResourcesActualDate`,
                 `productResourcesEstimatedEmployeeCost`,
                 `productResourcesActualEmployeeCost`,
                 `productResourcesEstimatedMachineCost`,
                 `productResourcesActualMachineCost`,
                 `productResourcesEstimatedAdditionalCost`,
                 `productResourcesActualAdditionalCost`,
                 `productResourcesEstimatedBillOfMaterialCost`,
                 `productResourcesActualBillOfMaterialCost`,
                 `productResourcesEstimatedTotalCost`,
                 `productResourcesActualTotalCost`,
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
                 '" . $this->model->getProductBatchId() . "',
                 '" . $this->model->getInvoiceId() . "',
                 '" . $this->model->getProductResourcesTask() . "',
                 '" . $this->model->getProductResourcesEstimatedDate() . "',
                 '" . $this->model->getProductResourcesActualDate() . "',
                 '" . $this->model->getProductResourcesEstimatedEmployeeCost() . "',
                 '" . $this->model->getProductResourcesActualEmployeeCost() . "',
                 '" . $this->model->getProductResourcesEstimatedMachineCost() . "',
                 '" . $this->model->getProductResourcesActualMachineCost() . "',
                 '" . $this->model->getProductResourcesEstimatedAdditionalCost() . "',
                 '" . $this->model->getProductResourcesActualAdditionalCost() . "',
                 '" . $this->model->getProductResourcesEstimatedBillOfMaterialCost() . "',
                 '" . $this->model->getProductResourcesActualBillOfMaterialCost() . "',
                 '" . $this->model->getProductResourcesEstimatedTotalCost() . "',
                 '" . $this->model->getProductResourcesActualTotalCost() . "',
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
            INSERT INTO [productResources] 
            (
                 [productResourcesId],
                 [companyId],
                 [productBatchId],
                 [invoiceId],
                 [productResourcesTask],
                 [productResourcesEstimatedDate],
                 [productResourcesActualDate],
                 [productResourcesEstimatedEmployeeCost],
                 [productResourcesActualEmployeeCost],
                 [productResourcesEstimatedMachineCost],
                 [productResourcesActualMachineCost],
                 [productResourcesEstimatedAdditionalCost],
                 [productResourcesActualAdditionalCost],
                 [productResourcesEstimatedBillOfMaterialCost],
                 [productResourcesActualBillOfMaterialCost],
                 [productResourcesEstimatedTotalCost],
                 [productResourcesActualTotalCost],
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
                 '" . $this->model->getProductBatchId() . "',
                 '" . $this->model->getInvoiceId() . "',
                 '" . $this->model->getProductResourcesTask() . "',
                 '" . $this->model->getProductResourcesEstimatedDate() . "',
                 '" . $this->model->getProductResourcesActualDate() . "',
                 '" . $this->model->getProductResourcesEstimatedEmployeeCost() . "',
                 '" . $this->model->getProductResourcesActualEmployeeCost() . "',
                 '" . $this->model->getProductResourcesEstimatedMachineCost() . "',
                 '" . $this->model->getProductResourcesActualMachineCost() . "',
                 '" . $this->model->getProductResourcesEstimatedAdditionalCost() . "',
                 '" . $this->model->getProductResourcesActualAdditionalCost() . "',
                 '" . $this->model->getProductResourcesEstimatedBillOfMaterialCost() . "',
                 '" . $this->model->getProductResourcesActualBillOfMaterialCost() . "',
                 '" . $this->model->getProductResourcesEstimatedTotalCost() . "',
                 '" . $this->model->getProductResourcesActualTotalCost() . "',
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
            INSERT INTO PRODUCTRESOURCES 
            (
                 COMPANYID,
                 PRODUCTBATCHID,
                 INVOICEID,
                 PRODUCTRESOURCESTASK,
                 PRODUCTRESOURCESESTIMATEDDATE,
                 PRODUCTRESOURCESACTUALDATE,
                 PRODUCTRESOURCESESTIMATEDEMPLOYEECOST,
                 PRODUCTRESOURCESACTUALEMPLOYEECOST,
                 PRODUCTRESOURCESESTIMATEDMACHINECOST,
                 PRODUCTRESOURCESACTUALMACHINECOST,
                 PRODUCTRESOURCESESTIMATEDADDITIONALCOST,
                 PRODUCTRESOURCESACTUALADDITIONALCOST,
                 PRODUCTRESOURCESESTIMATEDBILLOFMATERIALCOST,
                 PRODUCTRESOURCESACTUALBILLOFMATERIALCOST,
                 PRODUCTRESOURCESESTIMATEDTOTALCOST,
                 PRODUCTRESOURCESACTUALTOTALCOST,
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
                 '" . $this->model->getProductBatchId() . "',
                 '" . $this->model->getInvoiceId() . "',
                 '" . $this->model->getProductResourcesTask() . "',
                 '" . $this->model->getProductResourcesEstimatedDate() . "',
                 '" . $this->model->getProductResourcesActualDate() . "',
                 '" . $this->model->getProductResourcesEstimatedEmployeeCost() . "',
                 '" . $this->model->getProductResourcesActualEmployeeCost() . "',
                 '" . $this->model->getProductResourcesEstimatedMachineCost() . "',
                 '" . $this->model->getProductResourcesActualMachineCost() . "',
                 '" . $this->model->getProductResourcesEstimatedAdditionalCost() . "',
                 '" . $this->model->getProductResourcesActualAdditionalCost() . "',
                 '" . $this->model->getProductResourcesEstimatedBillOfMaterialCost() . "',
                 '" . $this->model->getProductResourcesActualBillOfMaterialCost() . "',
                 '" . $this->model->getProductResourcesEstimatedTotalCost() . "',
                 '" . $this->model->getProductResourcesActualTotalCost() . "',
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
        $productResourcesId = $this->q->lastInsertId();
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array("success" => true,
                    "message" => $this->t['newRecordTextLabel'],
                    "staffName" => $_SESSION['staffName'],
                    "executeTime" => date('d-m-Y H:i:s'),
                    "totalRecord" => $this->getTotalRecord(),
                    "productResourcesId" => $productResourcesId,
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
                    $this->setAuditFilter(" `productresources`.`isActive` = 1  AND `productresources`.`companyId`='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::MSSQL) {
                    $this->setAuditFilter(" [productResources].[isActive] = 1 AND [productResources].[companyId]='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::ORACLE) {
                    $this->setAuditFilter(" PRODUCTRESOURCES.ISACTIVE = 1  AND PRODUCTRESOURCES.COMPANYID='" . $this->getCompanyId() . "'");
                }
            } else if ($_SESSION['isAdmin'] == 1) {
                if ($this->getVendor() == self::MYSQL) {
                    $this->setAuditFilter("   `productresources`.`companyId`='" . $this->getCompanyId() . "'	");
                } else if ($this->getVendor() == self::MSSQL) {
                    $this->setAuditFilter(" [productResources].[companyId]='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::ORACLE) {
                    $this->setAuditFilter(" PRODUCTRESOURCES.COMPANYID='" . $this->getCompanyId() . "' ");
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
       SELECT                    `productresources`.`productResourcesId`,
                    `company`.`companyDescription`,
                    `productresources`.`companyId`,
                    `productbatch`.`productBatchDescription`,
                    `productresources`.`productBatchId`,
                    `invoice`.`invoiceDescription`,
                    `productresources`.`invoiceId`,
                    `productresources`.`productResourcesTask`,
                    `productresources`.`productResourcesEstimatedDate`,
                    `productresources`.`productResourcesActualDate`,
                    `productresources`.`productResourcesEstimatedEmployeeCost`,
                    `productresources`.`productResourcesActualEmployeeCost`,
                    `productresources`.`productResourcesEstimatedMachineCost`,
                    `productresources`.`productResourcesActualMachineCost`,
                    `productresources`.`productResourcesEstimatedAdditionalCost`,
                    `productresources`.`productResourcesActualAdditionalCost`,
                    `productresources`.`productResourcesEstimatedBillOfMaterialCost`,
                    `productresources`.`productResourcesActualBillOfMaterialCost`,
                    `productresources`.`productResourcesEstimatedTotalCost`,
                    `productresources`.`productResourcesActualTotalCost`,
                    `productresources`.`isDefault`,
                    `productresources`.`isNew`,
                    `productresources`.`isDraft`,
                    `productresources`.`isUpdate`,
                    `productresources`.`isDelete`,
                    `productresources`.`isActive`,
                    `productresources`.`isApproved`,
                    `productresources`.`isReview`,
                    `productresources`.`isPost`,
                    `productresources`.`executeBy`,
                    `productresources`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `productresources`
		  JOIN      `staff`
		  ON        `productresources`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `productresources`.`companyId`
	JOIN	`productbatch`
	ON		`productbatch`.`productBatchId` = `productresources`.`productBatchId`
	JOIN	`invoice`
	ON		`invoice`.`invoiceId` = `productresources`.`invoiceId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getProductResourcesId(0, 'single')) {
                $sql .= " AND `productresources`.`" . $this->model->getPrimaryKeyName() . "`='" . $this->model->getProductResourcesId(0, 'single') . "'";
            }
            if ($this->model->getProductBatchId()) {
                $sql .= " AND `productresources`.`productBatchId`='" . $this->model->getProductBatchId() . "'";
            }
            if ($this->model->getInvoiceId()) {
                $sql .= " AND `productresources`.`invoiceId`='" . $this->model->getInvoiceId() . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {

            $sql = "
		  SELECT                    [productResources].[productResourcesId],
                    [company].[companyDescription],
                    [productResources].[companyId],
                    [productBatch].[productBatchDescription],
                    [productResources].[productBatchId],
                    [invoice].[invoiceDescription],
                    [productResources].[invoiceId],
                    [productResources].[productResourcesTask],
                    [productResources].[productResourcesEstimatedDate],
                    [productResources].[productResourcesActualDate],
                    [productResources].[productResourcesEstimatedEmployeeCost],
                    [productResources].[productResourcesActualEmployeeCost],
                    [productResources].[productResourcesEstimatedMachineCost],
                    [productResources].[productResourcesActualMachineCost],
                    [productResources].[productResourcesEstimatedAdditionalCost],
                    [productResources].[productResourcesActualAdditionalCost],
                    [productResources].[productResourcesEstimatedBillOfMaterialCost],
                    [productResources].[productResourcesActualBillOfMaterialCost],
                    [productResources].[productResourcesEstimatedTotalCost],
                    [productResources].[productResourcesActualTotalCost],
                    [productResources].[isDefault],
                    [productResources].[isNew],
                    [productResources].[isDraft],
                    [productResources].[isUpdate],
                    [productResources].[isDelete],
                    [productResources].[isActive],
                    [productResources].[isApproved],
                    [productResources].[isReview],
                    [productResources].[isPost],
                    [productResources].[executeBy],
                    [productResources].[executeTime],
                    [staff].[staffName] 
		  FROM 	[productResources]
		  JOIN	[staff]
		  ON	[productResources].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [productResources].[companyId]
	JOIN	[productBatch]
	ON		[productBatch].[productBatchId] = [productResources].[productBatchId]
	JOIN	[invoice]
	ON		[invoice].[invoiceId] = [productResources].[invoiceId]
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getProductResourcesId(0, 'single')) {
                $sql .= " AND [productResources].[" . $this->model->getPrimaryKeyName() . "]		=	'" . $this->model->getProductResourcesId(0, 'single') . "'";
            }
            if ($this->model->getProductBatchId()) {
                $sql .= " AND [productResources].[productBatchId]='" . $this->model->getProductBatchId() . "'";
            }
            if ($this->model->getInvoiceId()) {
                $sql .= " AND [productResources].[invoiceId]='" . $this->model->getInvoiceId() . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {

            $sql = "
		  SELECT                    PRODUCTRESOURCES.PRODUCTRESOURCESID AS \"productResourcesId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    PRODUCTRESOURCES.COMPANYID AS \"companyId\",
                    PRODUCTBATCH.PRODUCTBATCHDESCRIPTION AS  \"productBatchDescription\",
                    PRODUCTRESOURCES.PRODUCTBATCHID AS \"productBatchId\",
                    INVOICE.INVOICEDESCRIPTION AS  \"invoiceDescription\",
                    PRODUCTRESOURCES.INVOICEID AS \"invoiceId\",
                    PRODUCTRESOURCES.PRODUCTRESOURCESTASK AS \"productResourcesTask\",
                    PRODUCTRESOURCES.PRODUCTRESOURCESESTIMATEDDATE AS \"productResourcesEstimatedDate\",
                    PRODUCTRESOURCES.PRODUCTRESOURCESACTUALDATE AS \"productResourcesActualDate\",
                    PRODUCTRESOURCES.PRODUCTRESOURCESESTIMATEDEMPLOYEECOST AS \"productResourcesEstimatedEmployeeCost\",
                    PRODUCTRESOURCES.PRODUCTRESOURCESACTUALEMPLOYEECOST AS \"productResourcesActualEmployeeCost\",
                    PRODUCTRESOURCES.PRODUCTRESOURCESESTIMATEDMACHINECOST AS \"productResourcesEstimatedMachineCost\",
                    PRODUCTRESOURCES.PRODUCTRESOURCESACTUALMACHINECOST AS \"productResourcesActualMachineCost\",
                    PRODUCTRESOURCES.PRODUCTRESOURCESESTIMATEDADDITIONALCOST AS \"productResourcesEstimatedAdditionalCost\",
                    PRODUCTRESOURCES.PRODUCTRESOURCESACTUALADDITIONALCOST AS \"productResourcesActualAdditionalCost\",
                    PRODUCTRESOURCES.PRODUCTRESOURCESESTIMATEDBILLOFMATERIALCOST AS \"productResourcesEstimatedBillOfMaterialCost\",
                    PRODUCTRESOURCES.PRODUCTRESOURCESACTUALBILLOFMATERIALCOST AS \"productResourcesActualBillOfMaterialCost\",
                    PRODUCTRESOURCES.PRODUCTRESOURCESESTIMATEDTOTALCOST AS \"productResourcesEstimatedTotalCost\",
                    PRODUCTRESOURCES.PRODUCTRESOURCESACTUALTOTALCOST AS \"productResourcesActualTotalCost\",
                    PRODUCTRESOURCES.ISDEFAULT AS \"isDefault\",
                    PRODUCTRESOURCES.ISNEW AS \"isNew\",
                    PRODUCTRESOURCES.ISDRAFT AS \"isDraft\",
                    PRODUCTRESOURCES.ISUPDATE AS \"isUpdate\",
                    PRODUCTRESOURCES.ISDELETE AS \"isDelete\",
                    PRODUCTRESOURCES.ISACTIVE AS \"isActive\",
                    PRODUCTRESOURCES.ISAPPROVED AS \"isApproved\",
                    PRODUCTRESOURCES.ISREVIEW AS \"isReview\",
                    PRODUCTRESOURCES.ISPOST AS \"isPost\",
                    PRODUCTRESOURCES.EXECUTEBY AS \"executeBy\",
                    PRODUCTRESOURCES.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\" 
		  FROM 	PRODUCTRESOURCES 
		  JOIN	STAFF 
		  ON	PRODUCTRESOURCES.EXECUTEBY = STAFF.STAFFID 
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = PRODUCTRESOURCES.COMPANYID
	JOIN	PRODUCTBATCH
	ON		PRODUCTBATCH.PRODUCTBATCHID = PRODUCTRESOURCES.PRODUCTBATCHID
	JOIN	INVOICE
	ON		INVOICE.INVOICEID = PRODUCTRESOURCES.INVOICEID
         WHERE     " . $this->getAuditFilter();
            if ($this->model->getProductResourcesId(0, 'single')) {
                $sql .= " AND PRODUCTRESOURCES. " . strtoupper($this->model->getPrimaryKeyName()) . "='" . $this->model->getProductResourcesId(0, 'single') . "'";
            }
            if ($this->model->getProductBatchId()) {
                $sql .= " AND PRODUCTRESOURCES.PRODUCTBATCHID='" . $this->model->getProductBatchId() . "'";
            }
            if ($this->model->getInvoiceId()) {
                $sql .= " AND PRODUCTRESOURCES.INVOICEID='" . $this->model->getInvoiceId() . "'";
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
                $sql.=" AND `productresources`.`" . $this->model->getFilterCharacter() . "` like '" . $this->getCharacterQuery() . "%'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql.=" AND [productResources].[" . $this->model->getFilterCharacter() . "] like '" . $this->getCharacterQuery() . "%'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql.=" AND Initcap(PRODUCTRESOURCES." . strtoupper($this->model->getFilterCharacter()) . ") LIKE Initcap('" . $this->getCharacterQuery() . "%');";
            }
        }
        /**
         * filter column based on Range Of Date 
         * Example Day,Week,Month,Year 
         */
        if ($this->getDateRangeStartQuery()) {
            if ($this->getVendor() == self::MYSQL) {
                $sql.=$this->q->dateFilter('productresources', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery());
            } else if ($this->getVendor() == self::MSSQL) {
                $sql.=$this->q->dateFilter('productResources', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery());
            } else if ($this->getVendor() == self::ORACLE) {
                $sql.=$this->q->dateFilter('PRODUCTRESOURCES', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery());
            }
        }
        /**
         * filter column don't want to filter.Example may contain  sensitive information or unwanted to be search. 
         * E.g  $filterArray=array('`leaf`.`leafId`'); 
         * @variables $filterArray; 
         */
        $filterArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $filterArray = array("`productresources`.`productResourcesId`",
                "`staff`.`staffPassword`");
        } else if ($this->getVendor() == self::MSSQL) {
            $filterArray = array("[productResources].[productResourcesId]",
                "[staff].[staffPassword]");
        } else if ($this->getVendor() == self::ORACLE) {
            $filterArray = array("PRODUCTRESOURCES.PRODUCTRESOURCESID",
                "STAFF.STAFFPASSWORD");
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('staff', 'productresources', 'company', 'productbatch', 'invoice');
        } else if ($this->getVendor() == self::MSSQL) {
            $tableArray = array('staff', 'productresources', 'company', 'productbatch', 'invoice');
        } else if ($this->getVendor() == self::ORACLE) {
            $tableArray = array('STAFF', 'PRODUCTRESOURCES', 'COMPANY', 'PRODUCTBATCH', 'INVOICE');
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
        if (!($this->model->getProductResourcesId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 28;
            if ($this->model->getProductResourcesId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getProductResourcesId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getProductResourcesId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else if ($this->getPageOutput() == 'json') {
            if ($this->model->getProductResourcesId(0, 'single')) {
                $end = microtime(true);
                $time = $end - $start;
                echo str_replace(array("[", "]"), "", json_encode(array(
                    'success' => true,
                    'total' => $total,
                    'message' => $this->t['viewRecordMessageLabel'],
                    'time' => $time,
                    'firstRecord' => $this->firstRecord('value'),
                    'previousRecord' => $this->previousRecord('value', $this->model->getProductResourcesId(0, 'single')),
                    'nextRecord' => $this->nextRecord('value', $this->model->getProductResourcesId(0, 'single')),
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
                    'previousRecord' => $this->recordSet->previousRecord('value', $this->model->getProductResourcesId(0, 'single')),
                    'nextRecord' => $this->recordSet->nextRecord('value', $this->model->getProductResourcesId(0, 'single')),
                    'lastRecord' => $this->recordSet->lastRecord('value'),
                    'data' => $items));
                exit();
            }
        }
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
        if (!$this->model->getProductBatchId()) {
            $this->model->setProductBatchId($this->service->getProductBatchDefaultValue());
        }
        if (!$this->model->getInvoiceId()) {
            $this->model->setInvoiceId($this->service->getInvoiceDefaultValue());
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = " 
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`productResources`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getProductResourcesId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
           SELECT	[" . $this->model->getPrimaryKeyName() . "] 
           FROM 	[productResources] 
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getProductResourcesId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
           FROM 	PRODUCTRESOURCES 
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getProductResourcesId(0, 'single') . "' ";
        }
        $result = $this->q->fast($sql);
        $total = $this->q->numberRows($result, $sql);
        if ($total == 0) {
            echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel']));
            exit();
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
               UPDATE `productresources` SET 
                       `productBatchId` = '" . $this->model->getProductBatchId() . "',
                       `invoiceId` = '" . $this->model->getInvoiceId() . "',
                       `productResourcesTask` = '" . $this->model->getProductResourcesTask() . "',
                       `productResourcesEstimatedDate` = '" . $this->model->getProductResourcesEstimatedDate() . "',
                       `productResourcesActualDate` = '" . $this->model->getProductResourcesActualDate() . "',
                       `productResourcesEstimatedEmployeeCost` = '" . $this->model->getProductResourcesEstimatedEmployeeCost() . "',
                       `productResourcesActualEmployeeCost` = '" . $this->model->getProductResourcesActualEmployeeCost() . "',
                       `productResourcesEstimatedMachineCost` = '" . $this->model->getProductResourcesEstimatedMachineCost() . "',
                       `productResourcesActualMachineCost` = '" . $this->model->getProductResourcesActualMachineCost() . "',
                       `productResourcesEstimatedAdditionalCost` = '" . $this->model->getProductResourcesEstimatedAdditionalCost() . "',
                       `productResourcesActualAdditionalCost` = '" . $this->model->getProductResourcesActualAdditionalCost() . "',
                       `productResourcesEstimatedBillOfMaterialCost` = '" . $this->model->getProductResourcesEstimatedBillOfMaterialCost() . "',
                       `productResourcesActualBillOfMaterialCost` = '" . $this->model->getProductResourcesActualBillOfMaterialCost() . "',
                       `productResourcesEstimatedTotalCost` = '" . $this->model->getProductResourcesEstimatedTotalCost() . "',
                       `productResourcesActualTotalCost` = '" . $this->model->getProductResourcesActualTotalCost() . "',
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
               WHERE    `productResourcesId`='" . $this->model->getProductResourcesId('0', 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE [productResources] SET 
                       [productBatchId] = '" . $this->model->getProductBatchId() . "',
                       [invoiceId] = '" . $this->model->getInvoiceId() . "',
                       [productResourcesTask] = '" . $this->model->getProductResourcesTask() . "',
                       [productResourcesEstimatedDate] = '" . $this->model->getProductResourcesEstimatedDate() . "',
                       [productResourcesActualDate] = '" . $this->model->getProductResourcesActualDate() . "',
                       [productResourcesEstimatedEmployeeCost] = '" . $this->model->getProductResourcesEstimatedEmployeeCost() . "',
                       [productResourcesActualEmployeeCost] = '" . $this->model->getProductResourcesActualEmployeeCost() . "',
                       [productResourcesEstimatedMachineCost] = '" . $this->model->getProductResourcesEstimatedMachineCost() . "',
                       [productResourcesActualMachineCost] = '" . $this->model->getProductResourcesActualMachineCost() . "',
                       [productResourcesEstimatedAdditionalCost] = '" . $this->model->getProductResourcesEstimatedAdditionalCost() . "',
                       [productResourcesActualAdditionalCost] = '" . $this->model->getProductResourcesActualAdditionalCost() . "',
                       [productResourcesEstimatedBillOfMaterialCost] = '" . $this->model->getProductResourcesEstimatedBillOfMaterialCost() . "',
                       [productResourcesActualBillOfMaterialCost] = '" . $this->model->getProductResourcesActualBillOfMaterialCost() . "',
                       [productResourcesEstimatedTotalCost] = '" . $this->model->getProductResourcesEstimatedTotalCost() . "',
                       [productResourcesActualTotalCost] = '" . $this->model->getProductResourcesActualTotalCost() . "',
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
                WHERE   [productResourcesId]='" . $this->model->getProductResourcesId('0', 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE PRODUCTRESOURCES SET
                        PRODUCTBATCHID = '" . $this->model->getProductBatchId() . "',
                       INVOICEID = '" . $this->model->getInvoiceId() . "',
                       PRODUCTRESOURCESTASK = '" . $this->model->getProductResourcesTask() . "',
                       PRODUCTRESOURCESESTIMATEDDATE = '" . $this->model->getProductResourcesEstimatedDate() . "',
                       PRODUCTRESOURCESACTUALDATE = '" . $this->model->getProductResourcesActualDate() . "',
                       PRODUCTRESOURCESESTIMATEDEMPLOYEECOST = '" . $this->model->getProductResourcesEstimatedEmployeeCost() . "',
                       PRODUCTRESOURCESACTUALEMPLOYEECOST = '" . $this->model->getProductResourcesActualEmployeeCost() . "',
                       PRODUCTRESOURCESESTIMATEDMACHINECOST = '" . $this->model->getProductResourcesEstimatedMachineCost() . "',
                       PRODUCTRESOURCESACTUALMACHINECOST = '" . $this->model->getProductResourcesActualMachineCost() . "',
                       PRODUCTRESOURCESESTIMATEDADDITIONALCOST = '" . $this->model->getProductResourcesEstimatedAdditionalCost() . "',
                       PRODUCTRESOURCESACTUALADDITIONALCOST = '" . $this->model->getProductResourcesActualAdditionalCost() . "',
                       PRODUCTRESOURCESESTIMATEDBILLOFMATERIALCOST = '" . $this->model->getProductResourcesEstimatedBillOfMaterialCost() . "',
                       PRODUCTRESOURCESACTUALBILLOFMATERIALCOST = '" . $this->model->getProductResourcesActualBillOfMaterialCost() . "',
                       PRODUCTRESOURCESESTIMATEDTOTALCOST = '" . $this->model->getProductResourcesEstimatedTotalCost() . "',
                       PRODUCTRESOURCESACTUALTOTALCOST = '" . $this->model->getProductResourcesActualTotalCost() . "',
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
                WHERE  PRODUCTRESOURCESID='" . $this->model->getProductResourcesId('0', 'single') . "'";
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
           FROM 	`productresources` 
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getProductResourcesId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
           SELECT	[" . $this->model->getPrimaryKeyName() . "]  
           FROM 	[productResources] 
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getProductResourcesId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
           FROM 	PRODUCTRESOURCES 
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getProductResourcesId(0, 'single') . "' ";
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
               UPDATE  `productresources` 
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
               WHERE   `productResourcesId`   =  '" . $this->model->getProductResourcesId(0, 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
               UPDATE  [productResources] 
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
               WHERE   [productResourcesId]	=  '" . $this->model->getProductResourcesId(0, 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
               UPDATE  PRODUCTRESOURCES 
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
               WHERE   PRODUCTRESOURCESID	=  '" . $this->model->getProductResourcesId(0, 'single') . "'";
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
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = " 
           SELECT  `productResourcesCode` 
           FROM    `productresources` 
           WHERE   `productResourcesCode` 	= 	'" . $this->model->getProductResourcesCode() . "' 
           AND     `isActive`  =   1
           AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
           SELECT  [productResourcesCode] 
           FROM    [productResources] 
           WHERE   [productResourcesCode] = 	'" . $this->model->getProductResourcesCode() . "' 
           AND     [isActive]  =   1 
           AND     [companyId] =	'" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
               SELECT  PRODUCTRESOURCESCODE as \"productResourcesCode\" 
               FROM    PRODUCTRESOURCES 
               WHERE   PRODUCTRESOURCESCODE	= 	'" . $this->model->getProductResourcesCode() . "' 
               AND     ISACTIVE    =   1 
               AND     COMPANYID   =   '" . $this->getCompanyId() . "'";
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
                    array("success" => true,
                        "total" => $total,
                        "message" => $this->t['duplicateMessageLabel'],
                        "referenceNo" => $row ['referenceNo'],
                        "time" => $time));
            exit();
        } else {
            $end = microtime(true);
            $time = $end - $start;
            echo json_encode(
                    array("success" => true,
                        "total" => $total,
                        "message" => $this->t['duplicateNotMessageLabel'],
                        "time" => $time));
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
     * @throws \Exception
     */
    function setService($service) {
        return $this->service->setServiceOutput($service);
    }

    /**
     * Return  ProductBatch 
     * @return null|string
     */
    public function getProductBatch() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getProductBatch();
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
     * Return Total Record Of The  
     * return int Total Record
     */
    private function getTotalRecord() {
        $sql = null;
        $total = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT  count(*) AS `total` 
         FROM    `productResources`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT    COUNT(*) AS total 
         FROM      [productResources]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT    COUNT(*)    AS  \"total\" 
         FROM      PRODUCTRESOURCES
         WHERE     ISACTIVE    =   1
         AND       COMPANYID   =   " . $this->getCompanyId() . " ";
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
                ->setSubject('productResources')
                ->setDescription('Generated by PhpExcel an Idcms Generator')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('financial/inventory');
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
        $this->excel->getActiveSheet()->getColumnDimension('S')->setAutoSize(TRUE);
        $this->excel->getActiveSheet()->setCellValue('B2', $this->getReportTitle());
        $this->excel->getActiveSheet()->setCellValue('S2', '');
        $this->excel->getActiveSheet()->mergeCells('B2:S2');
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.');
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['productBatchIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['invoiceIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['productResourcesTaskLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['productResourcesEstimatedDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['productResourcesActualDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['productResourcesEstimatedEmployeeCostLabel']);
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['productResourcesActualEmployeeCostLabel']);
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['productResourcesEstimatedMachineCostLabel']);
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['productResourcesActualMachineCostLabel']);
        $this->excel->getActiveSheet()->setCellValue('L3', $this->translate['productResourcesEstimatedAdditionalCostLabel']);
        $this->excel->getActiveSheet()->setCellValue('M3', $this->translate['productResourcesActualAdditionalCostLabel']);
        $this->excel->getActiveSheet()->setCellValue('N3', $this->translate['productResourcesEstimatedBillOfMaterialCostLabel']);
        $this->excel->getActiveSheet()->setCellValue('O3', $this->translate['productResourcesActualBillOfMaterialCostLabel']);
        $this->excel->getActiveSheet()->setCellValue('P3', $this->translate['productResourcesEstimatedTotalCostLabel']);
        $this->excel->getActiveSheet()->setCellValue('Q3', $this->translate['productResourcesActualTotalCostLabel']);
        $this->excel->getActiveSheet()->setCellValue('R3', $this->translate['executeByLabel']);
        $this->excel->getActiveSheet()->setCellValue('S3', $this->translate['executeTimeLabel']);
        // 
        $loopRow = 4;
        $i = 0;
        \PHPExcel_Cell::setValueBinder(new \PHPExcel_Cell_AdvancedValueBinder());
        $lastRow = null;
        while (($row = $this->q->fetchAssoc()) == TRUE) {
            //	echo print_r($row); 
            $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i);
            $this->excel->getActiveSheet()->setCellValue('C' . $loopRow, strip_tags($row ['productBatchDescription']));
            $this->excel->getActiveSheet()->setCellValue('D' . $loopRow, strip_tags($row ['invoiceDescription']));
            $this->excel->getActiveSheet()->setCellValue('E' . $loopRow, strip_tags($row ['productResourcesTask']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('F' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $this->excel->getActiveSheet()->setCellValue('F' . $loopRow, strip_tags($row ['productResourcesEstimatedDate']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('G' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, strip_tags($row ['productResourcesActualDate']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('H' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->excel->getActiveSheet()->setCellValue('H' . $loopRow, strip_tags($row ['productResourcesEstimatedEmployeeCost']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('I' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->excel->getActiveSheet()->setCellValue('I' . $loopRow, strip_tags($row ['productResourcesActualEmployeeCost']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('J' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->excel->getActiveSheet()->setCellValue('J' . $loopRow, strip_tags($row ['productResourcesEstimatedMachineCost']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('K' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->excel->getActiveSheet()->setCellValue('K' . $loopRow, strip_tags($row ['productResourcesActualMachineCost']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('L' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->excel->getActiveSheet()->setCellValue('L' . $loopRow, strip_tags($row ['productResourcesEstimatedAdditionalCost']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('M' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->excel->getActiveSheet()->setCellValue('M' . $loopRow, strip_tags($row ['productResourcesActualAdditionalCost']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('N' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->excel->getActiveSheet()->setCellValue('N' . $loopRow, strip_tags($row ['productResourcesEstimatedBillOfMaterialCost']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('O' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->excel->getActiveSheet()->setCellValue('O' . $loopRow, strip_tags($row ['productResourcesActualBillOfMaterialCost']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('P' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->excel->getActiveSheet()->setCellValue('P' . $loopRow, strip_tags($row ['productResourcesEstimatedTotalCost']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('Q' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->excel->getActiveSheet()->setCellValue('Q' . $loopRow, strip_tags($row ['productResourcesActualTotalCost']));
            $this->excel->getActiveSheet()->setCellValue('R' . $loopRow, strip_tags($row ['staffName']));
            $this->excel->getActiveSheet()->setCellValue('S' . $loopRow, strip_tags($row ['executeTime']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('S' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $loopRow++;
            $lastRow = 'S' . $loopRow;
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
                $filename = "productResources" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot() . "v3/financial/inventory/document/" . $folder . "/" . $filename;
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
                $filename = "productResources" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot() . "v3/financial/inventory/document/" . $folder . "/" . $filename;
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
                $filename = "productResources" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot() . "v3/financial/inventory/document/" . $folder . "/" . $filename;
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
                $filename = "productResources" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot() . "v3/financial/inventory/document/" . $folder . "/" . $filename;
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
        $productResourcesObject = new ProductResourcesClass ();
        if ($_POST['securityToken'] != $productResourcesObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $productResourcesObject->setLeafId($_POST ['leafId']);
        }
        if (isset($_POST ['offset'])) {
            $productResourcesObject->setStart($_POST ['offset']);
        }
        if (isset($_POST ['limit'])) {
            $productResourcesObject->setLimit($_POST ['limit']);
        }
        $productResourcesObject->setPageOutput($_POST['output']);
        $productResourcesObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $productResourcesObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $productResourcesObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $productResourcesObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $productResourcesObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$productResourcesObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$productResourcesObject->delete(); 
        }
    }
}
if (isset($_GET ['method'])) {
    $productResourcesObject = new ProductResourcesClass ();
    if ($_GET['securityToken'] != $productResourcesObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $productResourcesObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $productResourcesObject->execute();
    /*
     * Update Status of The Table. Admin Level Only 
     */
    if ($_GET ['method'] == 'updateStatus') {
        $productResourcesObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key 
     */
    if ($_GET['method'] == 'duplicate') {
        $productResourcesObject->duplicate();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $productResourcesObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $productResourcesObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $productResourcesObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $productResourcesObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $productResourcesObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml') {
            $productResourcesObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        $productResourcesObject->setServiceOutput('option');
        if (($_GET['filter'] == 'productBatch')) {
            $productResourcesObject->getProductBatch();
        }
        if (($_GET['filter'] == 'invoice')) {
            $productResourcesObject->getInvoice();
        }
    }
}
?>
