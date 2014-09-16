<?php

namespace Core\Financial\AccountPayable\PurchaseRequest\Controller;

use Core\ConfigClass;
use Core\Financial\AccountPayable\PurchaseRequest\Model\PurchaseRequestModel;
use Core\Financial\AccountPayable\PurchaseRequest\Service\PurchaseRequestService;
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
require_once ($newFakeDocumentRoot . "v3/financial/accountPayable/model/purchaseRequestModel.php");
require_once ($newFakeDocumentRoot . "v3/financial/accountPayable/service/purchaseRequestService.php");

/**
 * Class PurchaseRequest
 * this is purchaseRequest controller files. 
 * @name IDCMS 
 * @version 2 
 * @author hafizan 
 * @package  Core\Financial\AccountPayable\PurchaseRequest\Controller 
 * @subpackage AccountPayable 
 * @link http://www.hafizan.com 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL 
 */
class PurchaseRequestClass extends ConfigClass {

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
     * @var \Core\Financial\AccountPayable\PurchaseRequest\Model\PurchaseRequestModel 
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request 
     * @var \Core\Financial\AccountPayable\PurchaseRequest\Service\PurchaseRequestService 
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
        $this->setViewPath("./v3/financial/accountPayable/view/purchaseRequest.php");
        $this->setControllerPath("./v3/financial/accountPayable/controller/purchaseRequestController.php");
        $this->setServicePath("./v3/financial/accountPayable/service/purchaseRequestService.php");
    }

    /**
     * Class Loader 
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new PurchaseRequestModel();
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
        $this->setLeafId($arrayInfo['leafId']);
        $this->setReportTitle($applicationNative . " :: " . $moduleNative . " :: " . $folderNative . " :: " . $leafNative);

        $this->service = new PurchaseRequestService();
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
        if (!$this->model->getBranchId()) {
            $this->model->setBranchId($this->service->getBranchByEmployee($this->model->getEmployeeId()));
        }
        if (!$this->model->getDepartmentId()) {
            $this->model->setDepartmentId($this->service->getDepartmentByEmployee($this->model->getEmployeeId()));
        }
        if (!$this->model->getWarehouseId()) {
            $this->model->setWarehouseId($this->service->getWarehouseDefaultValue());
        }
        if (!$this->model->getProductResourcesId()) {
            $this->model->setProductResourcesId($this->service->getProductResourcesDefaultValue());
        }
        if (!$this->model->getEquipmentStatusId()) {
            $this->model->setEquipmentStatusId($this->service->getEquipmentStatusDefaultValue());
        }
        if (!$this->model->getEmployeeId()) {
            $this->model->setEmployeeId($this->service->getEmployeeDefaultValue());
        }
        $this->model->setDocumentNumber($this->getDocumentNumber());
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `purchaserequest` 
            (
                 `companyId`,
                 `branchId`,
                 `departmentId`,
                 `warehouseId`,
                 `productResourcesId`,
                 `equipmentStatusId`,
                 `employeeId`,
                 `documentNumber`,
                 `referenceNumber`,
                 `purchaseRequestDate`,
                 `purchaseRequestRequiredDate`,
                 `purchaseRequestValidStartDate`,
                 `purchaseRequestValidEndDate`,
                 `purchaseRequestDescription`,
                 `isDefault`,
                 `isNew`,
                 `isDraft`,
                 `isUpdate`,
                 `isDelete`,
                 `isActive`,
                 `isApproved`,
                 `isReview`,
                 `isPost`,
                 `isReject`,
                 `executeBy`,
                 `executeTime`
       ) VALUES ( 
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getBranchId() . "',
                 '" . $this->model->getDepartmentId() . "',
                 '" . $this->model->getWarehouseId() . "',
                 '" . $this->model->getProductResourcesId() . "',
                 '" . $this->model->getEquipmentStatusId() . "',
                 '" . $this->model->getEmployeeId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getReferenceNumber() . "',
                 '" . $this->model->getPurchaseRequestDate() . "',
                 '" . $this->model->getPurchaseRequestRequiredDate() . "',
                 '" . $this->model->getPurchaseRequestValidStartDate() . "',
                 '" . $this->model->getPurchaseRequestValidEndDate() . "',
                 '" . $this->model->getPurchaseRequestDescription() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getIsReject() . "',
                 '" . $this->model->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . "
       );";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            INSERT INTO [purchaseRequest] 
            (
                 [purchaseRequestId],
                 [companyId],
                 [branchId],
                 [departmentId],
                 [warehouseId],
                 [productResourcesId],
                 [equipmentStatusId],
                 [employeeId],
                 [documentNumber],
                 [referenceNumber],
                 [purchaseRequestDate],
                 [purchaseRequestRequiredDate],
                 [purchaseRequestValidStartDate],
                 [purchaseRequestValidEndDate],
                 [purchaseRequestDescription],
                 [isDefault],
                 [isNew],
                 [isDraft],
                 [isUpdate],
                 [isDelete],
                 [isActive],
                 [isApproved],
                 [isReview],
                 [isPost],
                 [isReject],
                 [executeBy],
                 [executeTime]
) VALUES ( 
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getBranchId() . "',
                 '" . $this->model->getDepartmentId() . "',
                 '" . $this->model->getWarehouseId() . "',
                 '" . $this->model->getProductResourcesId() . "',
                 '" . $this->model->getEquipmentStatusId() . "',
                 '" . $this->model->getEmployeeId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getReferenceNumber() . "',
                 '" . $this->model->getPurchaseRequestDate() . "',
                 '" . $this->model->getPurchaseRequestRequiredDate() . "',
                 '" . $this->model->getPurchaseRequestValidStartDate() . "',
                 '" . $this->model->getPurchaseRequestValidEndDate() . "',
                 '" . $this->model->getPurchaseRequestDescription() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getIsReject() . "',
                 '" . $this->model->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . "
            );";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            INSERT INTO PURCHASEREQUEST 
            (
                 COMPANYID,
                 BRANCHID,
                 DEPARTMENTID,
                 WAREHOUSEID,
                 PRODUCTRESOURCESID,
                 EQUIPMENTSTATUSID,
                 EMPLOYEEID,
                 DOCUMENTNUMBER,
                 REFERENCENUMBER,
                 PURCHASEREQUESTDATE,
                 PURCHASEREQUESTREQUIREDDATE,
                 PURCHASEREQUESTVALIDSTARTDATE,
                 PURCHASEREQUESTVALIDENDDATE,
                 PURCHASEREQUESTDESCRIPTION,
                 ISDEFAULT,
                 ISNEW,
                 ISDRAFT,
                 ISUPDATE,
                 ISDELETE,
                 ISACTIVE,
                 ISAPPROVED,
                 ISREVIEW,
                 ISPOST,
                 ISREJECT,
                 EXECUTEBY,
                 EXECUTETIME
            ) VALUES ( 
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getBranchId() . "',
                 '" . $this->model->getDepartmentId() . "',
                 '" . $this->model->getWarehouseId() . "',
                 '" . $this->model->getProductResourcesId() . "',
                 '" . $this->model->getEquipmentStatusId() . "',
                 '" . $this->model->getEmployeeId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getReferenceNumber() . "',
                 '" . $this->model->getPurchaseRequestDate() . "',
                 '" . $this->model->getPurchaseRequestRequiredDate() . "',
                 '" . $this->model->getPurchaseRequestValidStartDate() . "',
                 '" . $this->model->getPurchaseRequestValidEndDate() . "',
                 '" . $this->model->getPurchaseRequestDescription() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getIsReject() . "',
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
        $purchaseRequestId = $this->q->lastInsertId("purchaseRequest");
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array("success" => true,
                    "message" => $this->t['newRecordTextLabel'],
                    "staffName" => $_SESSION['staffName'],
                    "executeTime" => date('d-m-Y H:i:s'),
                    "totalRecord" => $this->getTotalRecord(),
                    "purchaseRequestId" => $purchaseRequestId,
                    "documentNumber" => $this->model->getDocumentNumber(),
                    "departmentId" => $this->model->getDepartmentId(),
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
                    $this->setAuditFilter(" `purchaserequest`.`isActive` = 1  AND `purchaserequest`.`companyId`='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::MSSQL) {
                    $this->setAuditFilter(" [purchaseRequest].[isActive] = 1 AND [purchaseRequest].[companyId]='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::ORACLE) {
                    $this->setAuditFilter(" PURCHASEREQUEST.ISACTIVE = 1  AND PURCHASEREQUEST.COMPANYID='" . $this->getCompanyId() . "'");
                }
            } else if ($_SESSION['isAdmin'] == 1) {
                if ($this->getVendor() == self::MYSQL) {
                    $this->setAuditFilter("   `purchaserequest`.`companyId`='" . $this->getCompanyId() . "'	");
                } else if ($this->getVendor() == self::MSSQL) {
                    $this->setAuditFilter(" [purchaseRequest].[companyId]='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::ORACLE) {
                    $this->setAuditFilter(" PURCHASEREQUEST.COMPANYID='" . $this->getCompanyId() . "' ");
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
       SELECT                    `purchaserequest`.`purchaseRequestId`,
                    `company`.`companyDescription`,
                    `purchaserequest`.`companyId`,
                    `branch`.`branchName`,
                    `purchaserequest`.`branchId`,
                    `department`.`departmentDescription`,
                    `purchaserequest`.`departmentId`,
                    `warehouse`.`warehouseDescription`,
                    `purchaserequest`.`warehouseId`,
                    `productresources`.`productResourcesDescription`,
                    `purchaserequest`.`productResourcesId`,
                    `equipmentstatus`.`equipmentStatusDescription`,
                    `purchaserequest`.`equipmentStatusId`,
                    `employee`.`employeeFirstName`,
                    `purchaserequest`.`employeeId`,
                    `purchaserequest`.`documentNumber`,
                    `purchaserequest`.`referenceNumber`,
                    `purchaserequest`.`purchaseRequestDate`,
                    `purchaserequest`.`purchaseRequestRequiredDate`,
                    `purchaserequest`.`purchaseRequestValidStartDate`,
                    `purchaserequest`.`purchaseRequestValidEndDate`,
                    `purchaserequest`.`purchaseRequestDescription`,
                    `purchaserequest`.`isDefault`,
                    `purchaserequest`.`isNew`,
                    `purchaserequest`.`isDraft`,
                    `purchaserequest`.`isUpdate`,
                    `purchaserequest`.`isDelete`,
                    `purchaserequest`.`isActive`,
                    `purchaserequest`.`isApproved`,
                    `purchaserequest`.`isReview`,
                    `purchaserequest`.`isPost`,
                    `purchaserequest`.`isReject`,
                    `purchaserequest`.`executeBy`,
                    `purchaserequest`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `purchaserequest`
		  JOIN      `staff`
		  ON        `purchaserequest`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `purchaserequest`.`companyId`
	JOIN	`branch`
	ON		`branch`.`branchId` = `purchaserequest`.`branchId`
	JOIN	`department`
	ON		`department`.`departmentId` = `purchaserequest`.`departmentId`
	JOIN	`warehouse`
	ON		`warehouse`.`warehouseId` = `purchaserequest`.`warehouseId`
	JOIN	`productresources`
	ON		`productresources`.`productResourcesId` = `purchaserequest`.`productResourcesId`
	JOIN	`equipmentstatus`
	ON		`equipmentstatus`.`equipmentStatusId` = `purchaserequest`.`equipmentStatusId`
	JOIN	`employee`
	ON		`employee`.`employeeId` = `purchaserequest`.`employeeId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getPurchaseRequestId(0, 'single')) {
                $sql .= " AND `purchaserequest`.`" . $this->model->getPrimaryKeyName() . "`='" . $this->model->getPurchaseRequestId(0, 'single') . "'";
            }
            if ($this->model->getBranchId()) {
                $sql .= " AND `purchaserequest`.`branchId`='" . $this->model->getBranchId() . "'";
            }
            if ($this->model->getDepartmentId()) {
                $sql .= " AND `purchaserequest`.`departmentId`='" . $this->model->getDepartmentId() . "'";
            }
            if ($this->model->getWarehouseId()) {
                $sql .= " AND `purchaserequest`.`warehouseId`='" . $this->model->getWarehouseId() . "'";
            }
            if ($this->model->getProductResourcesId()) {
                $sql .= " AND `purchaserequest`.`productResourcesId`='" . $this->model->getProductResourcesId() . "'";
            }
            if ($this->model->getEquipmentStatusId()) {
                $sql .= " AND `purchaserequest`.`equipmentStatusId`='" . $this->model->getEquipmentStatusId() . "'";
            }
            if ($this->model->getEmployeeId()) {
                $sql .= " AND `purchaserequest`.`employeeId`='" . $this->model->getEmployeeId() . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {

            $sql = "
		  SELECT                    [purchaseRequest].[purchaseRequestId],
                    [company].[companyDescription],
                    [purchaseRequest].[companyId],
                    [branch].[branchName],
                    [purchaseRequest].[branchId],
                    [department].[departmentDescription],
                    [purchaseRequest].[departmentId],
                    [warehouse].[warehouseDescription],
                    [purchaseRequest].[warehouseId],
                    [productResources].[productResourcesDescription],
                    [purchaseRequest].[productResourcesId],
                    [equipmentStatus].[equipmentStatusDescription],
                    [purchaseRequest].[equipmentStatusId],
                    [employee].[employeeFirstName],
                    [purchaseRequest].[employeeId],
                    [purchaseRequest].[documentNumber],
                    [purchaseRequest].[referenceNumber],
                    [purchaseRequest].[purchaseRequestDate],
                    [purchaseRequest].[purchaseRequestRequiredDate],
                    [purchaseRequest].[purchaseRequestValidStartDate],
                    [purchaseRequest].[purchaseRequestValidEndDate],
                    [purchaseRequest].[purchaseRequestDescription],
                    [purchaseRequest].[isDefault],
                    [purchaseRequest].[isNew],
                    [purchaseRequest].[isDraft],
                    [purchaseRequest].[isUpdate],
                    [purchaseRequest].[isDelete],
                    [purchaseRequest].[isActive],
                    [purchaseRequest].[isApproved],
                    [purchaseRequest].[isReview],
                    [purchaseRequest].[isPost],
                    [purchaseRequest].[isReject],
                    [purchaseRequest].[executeBy],
                    [purchaseRequest].[executeTime],
                    [staff].[staffName] 
		  FROM 	[purchaseRequest]
		  JOIN	[staff]
		  ON	[purchaseRequest].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [purchaseRequest].[companyId]
	JOIN	[branch]
	ON		[branch].[branchId] = [purchaseRequest].[branchId]
	JOIN	[department]
	ON		[department].[departmentId] = [purchaseRequest].[departmentId]
	JOIN	[warehouse]
	ON		[warehouse].[warehouseId] = [purchaseRequest].[warehouseId]
	JOIN	[productResources]
	ON		[productResources].[productResourcesId] = [purchaseRequest].[productResourcesId]
	JOIN	[equipmentStatus]
	ON		[equipmentStatus].[equipmentStatusId] = [purchaseRequest].[equipmentStatusId]
	JOIN	[employee]
	ON		[employee].[employeeId] = [purchaseRequest].[employeeId]
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getPurchaseRequestId(0, 'single')) {
                $sql .= " AND [purchaseRequest].[" . $this->model->getPrimaryKeyName() . "]		=	'" . $this->model->getPurchaseRequestId(0, 'single') . "'";
            }
            if ($this->model->getBranchId()) {
                $sql .= " AND [purchaserequest].[branchId]='" . $this->model->getBranchId() . "'";
            }
            if ($this->model->getDepartmentId()) {
                $sql .= " AND [purchaserequest].[departmentId]='" . $this->model->getDepartmentId() . "'";
            }
            if ($this->model->getWarehouseId()) {
                $sql .= " AND [purchaserequest].[warehouseId]='" . $this->model->getWarehouseId() . "'";
            }
            if ($this->model->getProductResourcesId()) {
                $sql .= " AND [purchaserequest].[productResourcesId]='" . $this->model->getProductResourcesId() . "'";
            }
            if ($this->model->getEquipmentStatusId()) {
                $sql .= " AND [purchaserequest].[equipmentStatusId]='" . $this->model->getEquipmentStatusId() . "'";
            }
            if ($this->model->getEmployeeId()) {
                $sql .= " AND [purchaserequest].[employeeId]='" . $this->model->getEmployeeId() . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {

            $sql = "
		  SELECT                    PURCHASEREQUEST.PURCHASEREQUESTID AS \"purchaseRequestId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    PURCHASEREQUEST.COMPANYID AS \"companyId\",
                    BRANCH.branchName AS  \"branchName\",
                    PURCHASEREQUEST.BRANCHID AS \"branchId\",
                    DEPARTMENT.DEPARTMENTDESCRIPTION AS  \"departmentDescription\",
                    PURCHASEREQUEST.DEPARTMENTID AS \"departmentId\",
                    WAREHOUSE.WAREHOUSEDESCRIPTION AS  \"warehouseDescription\",
                    PURCHASEREQUEST.WAREHOUSEID AS \"warehouseId\",
                    PRODUCTRESOURCES.PRODUCTRESOURCESDESCRIPTION AS  \"productResourcesDescription\",
                    PURCHASEREQUEST.PRODUCTRESOURCESID AS \"productResourcesId\",
                    EQUIPMENTSTATUS.EQUIPMENTSTATUSDESCRIPTION AS  \"equipmentStatusDescription\",
                    PURCHASEREQUEST.EQUIPMENTSTATUSID AS \"equipmentStatusId\",
                    EMPLOYEE.EMPLOYEEFIRSTNAME AS  \"employeeFirstName\",
                    PURCHASEREQUEST.EMPLOYEEID AS \"employeeId\",
                    PURCHASEREQUEST.DOCUMENTNUMBER AS \"documentNumber\",
                    PURCHASEREQUEST.REFERENCENUMBER AS \"referenceNumber\",
                    PURCHASEREQUEST.PURCHASEREQUESTDATE AS \"purchaseRequestDate\",
                    PURCHASEREQUEST.PURCHASEREQUESTREQUIREDDATE AS \"purchaseRequestRequiredDate\",
                    PURCHASEREQUEST.PURCHASEREQUESTVALIDSTARTDATE AS \"purchaseRequestValidStartDate\",
                    PURCHASEREQUEST.PURCHASEREQUESTVALIDENDDATE AS \"purchaseRequestValidEndDate\",
                    PURCHASEREQUEST.PURCHASEREQUESTDESCRIPTION AS \"purchaseRequestDescription\",
                    PURCHASEREQUEST.ISDEFAULT AS \"isDefault\",
                    PURCHASEREQUEST.ISNEW AS \"isNew\",
                    PURCHASEREQUEST.ISDRAFT AS \"isDraft\",
                    PURCHASEREQUEST.ISUPDATE AS \"isUpdate\",
                    PURCHASEREQUEST.ISDELETE AS \"isDelete\",
                    PURCHASEREQUEST.ISACTIVE AS \"isActive\",
                    PURCHASEREQUEST.ISAPPROVED AS \"isApproved\",
                    PURCHASEREQUEST.ISREVIEW AS \"isReview\",
                    PURCHASEREQUEST.ISPOST AS \"isPost\",
                    PURCHASEREQUEST.ISREJECT AS \"isReject\",
                    PURCHASEREQUEST.EXECUTEBY AS \"executeBy\",
                    PURCHASEREQUEST.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\" 
		  FROM 	PURCHASEREQUEST 
		  JOIN	STAFF 
		  ON	PURCHASEREQUEST.EXECUTEBY = STAFF.STAFFID 
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = PURCHASEREQUEST.COMPANYID
	JOIN	BRANCH
	ON		BRANCH.BRANCHID = PURCHASEREQUEST.BRANCHID
	JOIN	DEPARTMENT
	ON		DEPARTMENT.DEPARTMENTID = PURCHASEREQUEST.DEPARTMENTID
	JOIN	WAREHOUSE
	ON		WAREHOUSE.WAREHOUSEID = PURCHASEREQUEST.WAREHOUSEID
	JOIN	PRODUCTRESOURCES
	ON		PRODUCTRESOURCES.PRODUCTRESOURCESID = PURCHASEREQUEST.PRODUCTRESOURCESID
	JOIN	EQUIPMENTSTATUS
	ON		EQUIPMENTSTATUS.EQUIPMENTSTATUSID = PURCHASEREQUEST.EQUIPMENTSTATUSID
	JOIN	EMPLOYEE
	ON		EMPLOYEE.EMPLOYEEID = PURCHASEREQUEST.EMPLOYEEID
         WHERE     " . $this->getAuditFilter();
            if ($this->model->getPurchaseRequestId(0, 'single')) {
                $sql .= " AND PURCHASEREQUEST. " . strtoupper($this->model->getPrimaryKeyName()) . "='" . $this->model->getPurchaseRequestId(0, 'single') . "'";
            }
            if ($this->model->getBranchId()) {
                $sql .= " AND PURCHASEREQUEST.BRANCHID='" . $this->model->getBranchId() . "'";
            }
            if ($this->model->getDepartmentId()) {
                $sql .= " AND PURCHASEREQUEST.DEPARTMENTID='" . $this->model->getDepartmentId() . "'";
            }
            if ($this->model->getWarehouseId()) {
                $sql .= " AND PURCHASEREQUEST.WAREHOUSEID='" . $this->model->getWarehouseId() . "'";
            }
            if ($this->model->getProductResourcesId()) {
                $sql .= " AND PURCHASEREQUEST.PRODUCTRESOURCESID='" . $this->model->getProductResourcesId() . "'";
            }
            if ($this->model->getEquipmentStatusId()) {
                $sql .= " AND PURCHASEREQUEST.EQUIPMENTSTATUSID='" . $this->model->getEquipmentStatusId() . "'";
            }
            if ($this->model->getEmployeeId()) {
                $sql .= " AND PURCHASEREQUEST.EMPLOYEEID='" . $this->model->getEmployeeId() . "'";
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
                $sql.=" AND `purchaserequest`.`" . $this->model->getFilterCharacter() . "` like '" . $this->getCharacterQuery() . "%'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql.=" AND [purchaseRequest].[" . $this->model->getFilterCharacter() . "] like '" . $this->getCharacterQuery() . "%'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql.=" AND Initcap(PURCHASEREQUEST." . strtoupper($this->model->getFilterCharacter()) . ") LIKE Initcap('" . $this->getCharacterQuery() . "%')";
            }
        }
        /**
         * filter column based on Range Of Date 
         * Example Day,Week,Month,Year 
         */
        if ($this->getDateRangeStartQuery()) {
            if ($this->getVendor() == self::MYSQL) {
                $sql.=$this->q->dateFilter('purchaserequest', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery());
            } else if ($this->getVendor() == self::MSSQL) {
                $sql.=$this->q->dateFilter('purchaseRequest', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery());
            } else if ($this->getVendor() == self::ORACLE) {
                $sql.=$this->q->dateFilter('PURCHASEREQUEST', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery());
            }
        }
        /**
         * filter column don't want to filter.Example may contain  sensitive information or unwanted to be search. 
         * E.g  $filterArray=array('`leaf`.`leafId`'); 
         * @variables $filterArray; 
         */
        $filterArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $filterArray = array("`purchaserequest`.`purchaseRequestId`",
                "`staff`.`staffPassword`");
        } else if ($this->getVendor() == self::MSSQL) {
            $filterArray = array("[purchaserequest].[purchaseRequestId]",
                "[staff].[staffPassword]");
        } else if ($this->getVendor() == self::ORACLE) {
            $filterArray = array("PURCHASEREQUEST.PURCHASEREQUESTID",
                "STAFF.STAFFPASSWORD");
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('staff', 'purchaserequest', 'company', 'branch', 'department', 'warehouse', 'productresources', 'equipmentstatus', 'employee');
        } else if ($this->getVendor() == self::MSSQL) {
            $tableArray = array('staff', 'purchaserequest', 'company', 'branch', 'department', 'warehouse', 'productresources', 'equipmentstatus', 'employee');
        } else if ($this->getVendor() == self::ORACLE) {
            $tableArray = array('STAFF', 'PURCHASEREQUEST', 'COMPANY', 'BRANCH', 'DEPARTMENT', 'WAREHOUSE', 'PRODUCTRESOURCES', 'EQUIPMENTSTATUS', 'EMPLOYEE');
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
                 * */
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
        if (!($this->model->getPurchaseRequestId(0, 'single'))) {
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
            if ($this->model->getPurchaseRequestId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getPurchaseRequestId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getPurchaseRequestId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else if ($this->getPageOutput() == 'json') {
            if ($this->model->getPurchaseRequestId(0, 'single')) {
                $end = microtime(true);
                $time = $end - $start;
                echo str_replace(array("[", "]"), "", json_encode(array(
                    'success' => true,
                    'total' => $total,
                    'message' => $this->t['viewRecordMessageLabel'],
                    'time' => $time,
                    'firstRecord' => $this->firstRecord('value'),
                    'previousRecord' => $this->previousRecord('value', $this->model->getPurchaseRequestId(0, 'single')),
                    'nextRecord' => $this->nextRecord('value', $this->model->getPurchaseRequestId(0, 'single')),
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
                    'previousRecord' => $this->recordSet->previousRecord('value', $this->model->getPurchaseRequestId(0, 'single')),
                    'nextRecord' => $this->recordSet->nextRecord('value', $this->model->getPurchaseRequestId(0, 'single')),
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
        if (!$this->model->getBranchId()) {
            $this->model->setBranchId($this->service->getBranchDefaultValue());
        }
        if (!$this->model->getDepartmentId()) {
            $this->model->setDepartmentId($this->service->getDepartmentDefaultValue());
        }
        if (!$this->model->getWarehouseId()) {
            $this->model->setWarehouseId($this->service->getWarehouseDefaultValue());
        }
        if (!$this->model->getProductResourcesId()) {
            $this->model->setProductResourcesId($this->service->getProductResourcesDefaultValue());
        }
        if (!$this->model->getEquipmentStatusId()) {
            $this->model->setEquipmentStatusId($this->service->getEquipmentStatusDefaultValue());
        }
        if (!$this->model->getEmployeeId()) {
            $this->model->setEmployeeId($this->service->getEmployeeDefaultValue());
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = " 
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`purchaserequest`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getPurchaseRequestId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
           SELECT	[" . $this->model->getPrimaryKeyName() . "] 
           FROM 	[purchaseRequest] 
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getPurchaseRequestId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
           FROM 	PURCHASEREQUEST 
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getPurchaseRequestId(0, 'single') . "' ";
        }
        $result = $this->q->fast($sql);
        $total = $this->q->numberRows($result, $sql);
        if ($total == 0) {
            echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel']));
            exit();
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
               UPDATE `purchaserequest` SET 
                       `branchId` = '" . $this->model->getBranchId() . "',
                       `departmentId` = '" . $this->model->getDepartmentId() . "',
                       `warehouseId` = '" . $this->model->getWarehouseId() . "',
                       `productResourcesId` = '" . $this->model->getProductResourcesId() . "',
                       `equipmentStatusId` = '" . $this->model->getEquipmentStatusId() . "',
                       `employeeId` = '" . $this->model->getEmployeeId() . "',
                       `documentNumber` = '" . $this->model->getDocumentNumber() . "',
                       `referenceNumber` = '" . $this->model->getReferenceNumber() . "',
                       `purchaseRequestDate` = '" . $this->model->getPurchaseRequestDate() . "',
                       `purchaseRequestRequiredDate` = '" . $this->model->getPurchaseRequestRequiredDate() . "',
                       `purchaseRequestValidStartDate` = '" . $this->model->getPurchaseRequestValidStartDate() . "',
                       `purchaseRequestValidEndDate` = '" . $this->model->getPurchaseRequestValidEndDate() . "',
                       `purchaseRequestDescription` = '" . $this->model->getPurchaseRequestDescription() . "',
                       `isDefault` = '" . $this->model->getIsDefault('0', 'single') . "',
                       `isNew` = '" . $this->model->getIsNew('0', 'single') . "',
                       `isDraft` = '" . $this->model->getIsDraft('0', 'single') . "',
                       `isUpdate` = '" . $this->model->getIsUpdate('0', 'single') . "',
                       `isDelete` = '" . $this->model->getIsDelete('0', 'single') . "',
                       `isActive` = '" . $this->model->getIsActive('0', 'single') . "',
                       `isApproved` = '" . $this->model->getIsApproved('0', 'single') . "',
                       `isReview` = '" . $this->model->getIsReview('0', 'single') . "',
                       `isPost` = '" . $this->model->getIsPost('0', 'single') . "',
                       `isReject` = '" . $this->model->getIsReject() . "',
                       `executeBy` = '" . $this->model->getExecuteBy('0', 'single') . "',
                       `executeTime` = " . $this->model->getExecuteTime() . "
               WHERE    `purchaseRequestId`='" . $this->model->getPurchaseRequestId('0', 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE [purchaseRequest] SET 
                       [branchId] = '" . $this->model->getBranchId() . "',
                       [departmentId] = '" . $this->model->getDepartmentId() . "',
                       [warehouseId] = '" . $this->model->getWarehouseId() . "',
                       [productResourcesId] = '" . $this->model->getProductResourcesId() . "',
                       [equipmentStatusId] = '" . $this->model->getEquipmentStatusId() . "',
                       [employeeId] = '" . $this->model->getEmployeeId() . "',
                       [documentNumber] = '" . $this->model->getDocumentNumber() . "',
                       [referenceNumber] = '" . $this->model->getReferenceNumber() . "',
                       [purchaseRequestDate] = '" . $this->model->getPurchaseRequestDate() . "',
                       [purchaseRequestRequiredDate] = '" . $this->model->getPurchaseRequestRequiredDate() . "',
                       [purchaseRequestValidStartDate] = '" . $this->model->getPurchaseRequestValidStartDate() . "',
                       [purchaseRequestValidEndDate] = '" . $this->model->getPurchaseRequestValidEndDate() . "',
                       [purchaseRequestDescription] = '" . $this->model->getPurchaseRequestDescription() . "',
                       [isDefault] = '" . $this->model->getIsDefault(0, 'single') . "',
                       [isNew] = '" . $this->model->getIsNew(0, 'single') . "',
                       [isDraft] = '" . $this->model->getIsDraft(0, 'single') . "',
                       [isUpdate] = '" . $this->model->getIsUpdate(0, 'single') . "',
                       [isDelete] = '" . $this->model->getIsDelete(0, 'single') . "',
                       [isActive] = '" . $this->model->getIsActive(0, 'single') . "',
                       [isApproved] = '" . $this->model->getIsApproved(0, 'single') . "',
                       [isReview] = '" . $this->model->getIsReview(0, 'single') . "',
                       [isPost] = '" . $this->model->getIsPost(0, 'single') . "',
                       [isReject] = '" . $this->model->getIsReject() . "',
                       [executeBy] = '" . $this->model->getExecuteBy(0, 'single') . "',
                       [executeTime] = " . $this->model->getExecuteTime() . "
                WHERE   [purchaseRequestId]='" . $this->model->getPurchaseRequestId('0', 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE PURCHASEREQUEST SET
                        BRANCHID = '" . $this->model->getBranchId() . "',
                       DEPARTMENTID = '" . $this->model->getDepartmentId() . "',
                       WAREHOUSEID = '" . $this->model->getWarehouseId() . "',
                       PRODUCTRESOURCESID = '" . $this->model->getProductResourcesId() . "',
                       EQUIPMENTSTATUSID = '" . $this->model->getEquipmentStatusId() . "',
                       EMPLOYEEID = '" . $this->model->getEmployeeId() . "',
                       DOCUMENTNUMBER = '" . $this->model->getDocumentNumber() . "',
                       REFERENCENUMBER = '" . $this->model->getReferenceNumber() . "',
                       PURCHASEREQUESTDATE = '" . $this->model->getPurchaseRequestDate() . "',
                       PURCHASEREQUESTREQUIREDDATE = '" . $this->model->getPurchaseRequestRequiredDate() . "',
                       PURCHASEREQUESTVALIDSTARTDATE = '" . $this->model->getPurchaseRequestValidStartDate() . "',
                       PURCHASEREQUESTVALIDENDDATE = '" . $this->model->getPurchaseRequestValidEndDate() . "',
                       PURCHASEREQUESTDESCRIPTION = '" . $this->model->getPurchaseRequestDescription() . "',
                       ISDEFAULT = '" . $this->model->getIsDefault(0, 'single') . "',
                       ISNEW = '" . $this->model->getIsNew(0, 'single') . "',
                       ISDRAFT = '" . $this->model->getIsDraft(0, 'single') . "',
                       ISUPDATE = '" . $this->model->getIsUpdate(0, 'single') . "',
                       ISDELETE = '" . $this->model->getIsDelete(0, 'single') . "',
                       ISACTIVE = '" . $this->model->getIsActive(0, 'single') . "',
                       ISAPPROVED = '" . $this->model->getIsApproved(0, 'single') . "',
                       ISREVIEW = '" . $this->model->getIsReview(0, 'single') . "',
                       ISPOST = '" . $this->model->getIsPost(0, 'single') . "',
                       ISREJECT = '" . $this->model->getIsReject() . "',
                       EXECUTEBY = '" . $this->model->getExecuteBy(0, 'single') . "',
                       EXECUTETIME = " . $this->model->getExecuteTime() . "
                WHERE  PURCHASEREQUESTID='" . $this->model->getPurchaseRequestId('0', 'single') . "'";
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
           FROM 	`purchaserequest` 
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getPurchaseRequestId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
           SELECT	[" . $this->model->getPrimaryKeyName() . "]  
           FROM 	[purchaseRequest] 
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getPurchaseRequestId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
           FROM 	PURCHASEREQUEST 
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getPurchaseRequestId(0, 'single') . "' ";
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
               UPDATE  `purchaserequest` 
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
               WHERE   `purchaseRequestId`   =  '" . $this->model->getPurchaseRequestId(0, 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
               UPDATE  [purchaseRequest] 
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
               WHERE   [purchaseRequestId]	=  '" . $this->model->getPurchaseRequestId(0, 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
               UPDATE  PURCHASEREQUEST 
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
               WHERE   PURCHASEREQUESTID	=  '" . $this->model->getPurchaseRequestId(0, 'single') . "'";
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
           SELECT  `documentNumber`
           FROM    `purchaserequest` 
           WHERE   `documentNumber` 	= 	'" . $this->model->getDocumentNumber() . "'
           AND     `isActive`  =   1
           AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
           SELECT  [documentNumber]
           FROM    [purchaseRequest] 
           WHERE   [documentNumber] = 	'" . $this->model->getDocumentNumber() . "'
           AND     [isActive]  =   1 
           AND     [companyId] =	'" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
               SELECT  PURCHASEREQUESTCODE as \"documentNumber\"
               FROM    PURCHASEREQUEST 
               WHERE   PURCHASEREQUESTCODE	= 	'" . $this->model->getDocumentNumber() . "'
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
                        "documentNumber" => $row ['documentNumber'],
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
     */
    function setService($service) {
        return $this->service->setServiceOutput($service);
    }

    /**
     * Return Branch 
     * @return null|string
     */
    public function getBranch() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getBranch();
    }

    /**
     * Return Department 
     * @return null|string
     */
    public function getDepartment() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getDepartment($this->model->getCountryId(), $this->model->getBranchId(), $this->model->getEmployeeId());
    }

    /**
     * Return Warehouse 
     * @return null|string
     */
    public function getWarehouse() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getWarehouse($this->model->getCountryId(), $this->model->getBranchId(), $this->model->getDepartmentId());
    }

    /**
     * Return Product Resources 
     * @return null|string
     */
    public function getProductResources() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getProductResources();
    }

    /**
     * Return Equipment Status 
     * @return null|string
     */
    public function getEquipmentStatus() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getEquipmentStatus();
    }

    /**
     * Return Employee 
     * @return null|string
     */
    public function getEmployee() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getEmployee($this->model->getBranchId(), $this->model->getDepartmentId());
    }

    /**
     * Return Balance Budget
     * @return null|double
     */
    public function getBudget() {
        header('Content-Type:application/json; charset=utf-8');
        $budget = $this->service->getBudget($this->model->getChartOfAccountId(), $this->model->getPurchaseRequestDate());
        echo json_encode(array("success" => true, "budget" => $budget));
        exit();
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
     * Return Status Purchase Request/Order/Approval Value
     * @return void
     */
    public function getPurchaseInvoiceApprovalValue(){
        $this->service->getPurchaseInvoiceApprovalValue($this->model->getPurchaseRequestDetailId(), $this->model->getPurchseRequestDetailAmount());
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
         FROM    `purchaserequest`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT    COUNT(*) AS total 
         FROM      [purchaseRequest]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT    COUNT(*)    AS  \"total\" 
         FROM      PURCHASEREQUEST
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
                ->setSubject('purchaseRequest')
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
        $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('R')->setAutoSize(TRUE);
        $this->excel->getActiveSheet()->setCellValue('B2', $this->getReportTitle());
        $this->excel->getActiveSheet()->setCellValue('R2', '');
        $this->excel->getActiveSheet()->mergeCells('B2:R2');
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.');
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['branchIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['departmentIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['warehouseIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['productResourcesIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['equipmentStatusIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['employeeIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['documentNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['referenceNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['purchaseRequestDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('L3', $this->translate['purchaseRequestRequiredDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('M3', $this->translate['purchaseRequestValidStartDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('N3', $this->translate['purchaseRequestValidEndDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('O3', $this->translate['purchaseRequestDescriptionLabel']);
        $this->excel->getActiveSheet()->setCellValue('P3', $this->translate['isRejectLabel']);
        $this->excel->getActiveSheet()->setCellValue('Q3', $this->translate['executeByLabel']);
        $this->excel->getActiveSheet()->setCellValue('R3', $this->translate['executeTimeLabel']);
        // 
        $loopRow = 4;
        $i = 0;
        \PHPExcel_Cell::setValueBinder(new \PHPExcel_Cell_AdvancedValueBinder());
        $lastRow = null;
        while (($row = $this->q->fetchAssoc()) == TRUE) {
            //	echo print_r($row); 
            $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i);
            $this->excel->getActiveSheet()->setCellValue('C' . $loopRow, strip_tags($row ['branchName']));
            $this->excel->getActiveSheet()->setCellValue('D' . $loopRow, strip_tags($row ['departmentDescription']));
            $this->excel->getActiveSheet()->setCellValue('E' . $loopRow, strip_tags($row ['warehouseDescription']));
            $this->excel->getActiveSheet()->setCellValue('F' . $loopRow, strip_tags($row ['productResourcesDescription']));
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, strip_tags($row ['equipmentStatusDescription']));
            $this->excel->getActiveSheet()->setCellValue('H' . $loopRow, strip_tags($row ['employeeFirstName']));
            $this->excel->getActiveSheet()->setCellValue('I' . $loopRow, strip_tags($row ['documentNumber']));
            $this->excel->getActiveSheet()->setCellValue('J' . $loopRow, strip_tags($row ['referenceNumber']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('K' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $this->excel->getActiveSheet()->setCellValue('K' . $loopRow, strip_tags($row ['purchaseRequestDate']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('L' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $this->excel->getActiveSheet()->setCellValue('L' . $loopRow, strip_tags($row ['purchaseRequestRequiredDate']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('M' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $this->excel->getActiveSheet()->setCellValue('M' . $loopRow, strip_tags($row ['purchaseRequestValidStartDate']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('N' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $this->excel->getActiveSheet()->setCellValue('N' . $loopRow, strip_tags($row ['purchaseRequestValidEndDate']));
            $this->excel->getActiveSheet()->setCellValue('O' . $loopRow, strip_tags($row ['purchaseRequestDescription']));
            $this->excel->getActiveSheet()->setCellValue('P' . $loopRow, strip_tags($row ['isReject']));
            $this->excel->getActiveSheet()->setCellValue('Q' . $loopRow, strip_tags($row ['staffName']));
            $this->excel->getActiveSheet()->setCellValue('R' . $loopRow, strip_tags($row ['executeTime']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('R' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $loopRow++;
            $lastRow = 'R' . $loopRow;
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
                $filename = "purchaseRequest" . rand(0, 10000000) . $extension;
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
                $filename = "purchaseRequest" . rand(0, 10000000) . $extension;
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
                $filename = "purchaseRequest" . rand(0, 10000000) . $extension;
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
                $filename = "purchaseRequest" . rand(0, 10000000) . $extension;
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
        $purchaseRequestObject = new PurchaseRequestClass ();
        if ($_POST['securityToken'] != $purchaseRequestObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $purchaseRequestObject->setLeafId($_POST ['leafId']);
        }
        if (isset($_POST ['offset'])) {
            $purchaseRequestObject->setStart($_POST ['offset']);
        }
        if (isset($_POST ['limit'])) {
            $purchaseRequestObject->setLimit($_POST ['limit']);
        }
        $purchaseRequestObject->setPageOutput($_POST['output']);
        $purchaseRequestObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $purchaseRequestObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $purchaseRequestObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $purchaseRequestObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $purchaseRequestObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$purchaseRequestObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$purchaseRequestObject->delete(); 
        }
    }
}
if (isset($_GET ['method'])) {
    $purchaseRequestObject = new PurchaseRequestClass ();
    if ($_GET['securityToken'] != $purchaseRequestObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $purchaseRequestObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $purchaseRequestObject->execute();
    /*
     * Update Status of The Table. Admin Level Only 
     */
    if ($_GET ['method'] == 'updateStatus') {
        $purchaseRequestObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key 
     */
    if ($_GET['method'] == 'duplicate') {
        $purchaseRequestObject->duplicate();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $purchaseRequestObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $purchaseRequestObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $purchaseRequestObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $purchaseRequestObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $purchaseRequestObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml') {
            $purchaseRequestObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        $purchaseRequestObject->setServiceOutput('option');
        if (($_GET['filter'] == 'branch')) {
            $purchaseRequestObject->getBranch();
        }
        if (($_GET['filter'] == 'department')) {
            $purchaseRequestObject->getDepartment();
        }
        if (($_GET['filter'] == 'warehouse')) {
            $purchaseRequestObject->getWarehouse();
        }
        if (($_GET['filter'] == 'productResources')) {
            $purchaseRequestObject->getProductResources();
        }
        if (($_GET['filter'] == 'equipmentStatus')) {
            $purchaseRequestObject->getEquipmentStatus();
        }
        if (($_GET['filter'] == 'employee')) {
            $purchaseRequestObject->getEmployee();
        }
        if (($_GET['filter'] == 'budget')) {
            $purchaseRequestObject->getBudget();
        }
        if($_GET['filter']=='approval'){
            $purchaseRequestObject->getPurchaseInvoiceApprovalValue();
        }
    }
}
?>
