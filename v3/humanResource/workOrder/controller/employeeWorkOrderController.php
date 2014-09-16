<?php

namespace Core\HumanResource\WorkOrder\EmployeeWorkOrder\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\HumanResource\WorkOrder\EmployeeWorkOrder\Model\EmployeeWorkOrderModel;
use Core\HumanResource\WorkOrder\EmployeeWorkOrder\Service\EmployeeWorkOrderService;
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
require_once($newFakeDocumentRoot . "library/class/classAbstract.php");
require_once($newFakeDocumentRoot . "library/class/classRecordSet.php");
require_once($newFakeDocumentRoot . "library/class/classDate.php");
require_once($newFakeDocumentRoot . "library/class/classDocumentTrail.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
require_once($newFakeDocumentRoot . "v3/system/document/model/documentModel.php");
require_once($newFakeDocumentRoot . "v3/humanResource/workOrder/model/employeeWorkOrderModel.php");
require_once($newFakeDocumentRoot . "v3/humanResource/workOrder/service/employeeWorkOrderService.php");

/**
 * Class EmployeeWorkOrder
 * this is employeeWorkOrder controller files.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package  Core\HumanResource\WorkOrder\EmployeeWorkOrder\Controller
 * @subpackage WorkOrder
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class EmployeeWorkOrderClass extends ConfigClass {

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
     * Model
     * @var \Core\HumanResource\WorkOrder\EmployeeWorkOrder\Model\EmployeeWorkOrderModel
     */
    public $model;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     * @var \PHPPowerPoint
     */
    //private $powerPoint; 
    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\HumanResource\WorkOrder\EmployeeWorkOrder\Service\EmployeeWorkOrderService
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
        $this->setViewPath("./v3/humanResource/workOrder/view/employeeWorkOrder.php");
        $this->setControllerPath("./v3/humanResource/workOrder/controller/employeeWorkOrderController.php");
        $this->setServicePath("./v3/humanResource/workOrder/service/employeeWorkOrderService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new EmployeeWorkOrderModel();
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
        $this->setReportTitle(
                $applicationNative . " :: " . $moduleNative . " :: " . $folderNative . " :: " . $leafNative
        );

        $this->service = new EmployeeWorkOrderService();
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
        $this->documentTrail->execute();

        $this->systemFormat = new SharedClass();
        $this->systemFormat->q = $this->q;
        $this->systemFormat->setCurrentTable($this->model->getTableName());
        $this->systemFormat->execute();

        $this->systemFormatArray = $this->systemFormat->getSystemFormat();

        $this->excel = new \PHPExcel ();

        $this->d = new \Core\Date\DateClass();
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
        if (!$this->model->getEmployeeId()) {
            $this->model->setEmployeeId($this->service->getEmployeeDefaultValue());
        }
        if (!$this->model->getShiftId()) {
            $this->model->setShiftId($this->service->getShiftDefaultValue());
        }
        if (!$this->model->getInvoiceProjectId()) {
            $this->model->setInvoiceProjectId($this->service->getInvoiceProjectDefaultValue());
        }
        if (!$this->model->getInvoiceId()) {
            $this->model->setInvoiceId($this->service->getInvoiceDefaultValue());
        }
        if (!$this->model->getMilestoneId()) {
            $this->model->setMilestoneId($this->service->getMilestoneDefaultValue());
        }
        //$this->model->setDocumentNumber($this->getDocumentNumber());
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `employeeworkorder` 
            (
                 `companyId`,
                 `employeeId`,
                 `shiftId`,
                 `invoiceProjectId`,
				 `invoiceId`,
                 `milestoneId`,
                 `documentNumber`,
                 `employeeWorkOrderDate`,
                 `employeeWorkOrderStartDate`,
                 `employeeWorkOrderEndDate`,
                 `employeeWorkOrderDueDate`,
                 `employeeWorkOrderRate`,
                 `employeeWorkOrderDescription`,
                 `isDefault`,
                 `isNew`,
                 `isDraft`,
                 `isUpdate`,
                 `isDelete`,
                 `isActive`,
                 `isApproved`,
                 `isReview`,
                 `isPost`,
                 `isClientViewable`,
                 `isAllDayEvent`,
                 `isComplete`,
                 `executeBy`,
                 `executeTime`
       ) VALUES ( 
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getEmployeeId() . "',
                 '" . $this->model->getShiftId() . "',
                 '" . $this->model->getInvoiceProjectId() . "',
				 '" . $this->model->getInvoiceId() . "',
                 '" . $this->model->getMilestoneId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getEmployeeWorkOrderDate() . "',
                 '" . $this->model->getEmployeeWorkOrderStartDate() . "',
                 '" . $this->model->getEmployeeWorkOrderEndDate() . "',
                 '" . $this->model->getEmployeeWorkOrderDueDate() . "',
                 '" . $this->model->getEmployeeWorkOrderRate() . "',
                 '" . $this->model->getEmployeeWorkOrderDescription() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getIsClientViewable() . "',
                 '" . $this->model->getIsAllDayEvent() . "',
                 '" . $this->model->getIsComplete() . "',
                 '" . $this->model->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . "
       );";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            INSERT INTO [employeeWorkOrder] 
            (
                 [employeeWorkOrderId],
                 [companyId],
                 [employeeId],
                 [shiftId],
                 [invoiceProjectId],
				 [invoiceId],
                 [milestoneId],
                 [documentNumber],
                 [employeeWorkOrderDate],
                 [employeeWorkOrderStartDate],
                 [employeeWorkOrderEndDate],
                 [employeeWorkOrderDueDate],
                 [employeeWorkOrderRate],
                 [employeeWorkOrderDescription],
                 [isDefault],
                 [isNew],
                 [isDraft],
                 [isUpdate],
                 [isDelete],
                 [isActive],
                 [isApproved],
                 [isReview],
                 [isPost],
                 [isClientViewable],
                 [isAllDayEvent],
                 [isComplete],
                 [executeBy],
                 [executeTime]
) VALUES ( 
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getEmployeeId() . "',
                 '" . $this->model->getShiftId() . "',
                 '" . $this->model->getInvoiceProjectId() . "',
				 '" . $this->model->getInvoiceId() . "',
                 '" . $this->model->getMilestoneId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getEmployeeWorkOrderDate() . "',
                 '" . $this->model->getEmployeeWorkOrderStartDate() . "',
                 '" . $this->model->getEmployeeWorkOrderEndDate() . "',
                 '" . $this->model->getEmployeeWorkOrderDueDate() . "',
                 '" . $this->model->getEmployeeWorkOrderRate() . "',
                 '" . $this->model->getEmployeeWorkOrderDescription() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getIsClientViewable() . "',
                 '" . $this->model->getIsAllDayEvent() . "',
                 '" . $this->model->getIsComplete() . "',
                 '" . $this->model->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . "
            );";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            INSERT INTO EMPLOYEEWORKORDER 
            (
                 COMPANYID,
                 EMPLOYEEID,
                 SHIFTID,
                 INVOICEPROJECTID,
				 INVOICEID,
                 MILESTONEID,
                 DOCUMENTNUMBER,
                 EMPLOYEEWORKORDERDATE,
                 EMPLOYEEWORKORDERSTARTDATE,
                 EMPLOYEEWORKORDERENDDATE,
                 EMPLOYEEWORKORDERDUEDATE,
                 EMPLOYEEWORKORDERRATE,
                 EMPLOYEEWORKORDERDESCRIPTION,
                 ISDEFAULT,
                 ISNEW,
                 ISDRAFT,
                 ISUPDATE,
                 ISDELETE,
                 ISACTIVE,
                 ISAPPROVED,
                 ISREVIEW,
                 ISPOST,
                 ISCLIENTVIEWABLE,
                 ISALLDAYEVENT,
                 ISCOMPLETE,
                 EXECUTEBY,
                 EXECUTETIME
            ) VALUES ( 
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getEmployeeId() . "',
                 '" . $this->model->getShiftId() . "',
                 '" . $this->model->getInvoiceProjectId() . "',
				 '" . $this->model->getInvoiceId() . "',
                 '" . $this->model->getMilestoneId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getEmployeeWorkOrderDate() . "',
                 '" . $this->model->getEmployeeWorkOrderStartDate() . "',
                 '" . $this->model->getEmployeeWorkOrderEndDate() . "',
                 '" . $this->model->getEmployeeWorkOrderDueDate() . "',
                 '" . $this->model->getEmployeeWorkOrderRate() . "',
                 '" . $this->model->getEmployeeWorkOrderDescription() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getIsClientViewable() . "',
                 '" . $this->model->getIsAllDayEvent() . "',
                 '" . $this->model->getIsComplete() . "',
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
        $employeeWorkOrderId = $this->q->lastInsertId();
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => true,
                    "message" => $this->t['newRecordTextLabel'],
                    "staffName" => $_SESSION['staffName'],
                    "executeTime" => date('d-m-Y H:i:s'),
                    "totalRecord" => $this->getTotalRecord(),
                    "employeeWorkOrderId" => $employeeWorkOrderId,
                    "time" => $time
                )
        );
        exit();
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
         FROM    `employeeWorkOrder`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT    COUNT(*) AS total
         FROM      [employeeWorkOrder]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT    COUNT(*)    AS  \"total\"
         FROM      EMPLOYEEWORKORDER
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
        }
        return $total;
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
                    $this->setAuditFilter(
                            " `employeeworkorder`.`isActive` = 1  AND `employeeworkorder`.`companyId`='" . $this->getCompanyId(
                            ) . "' "
                    );
                } else if ($this->getVendor() == self::MSSQL) {
                    $this->setAuditFilter(
                            " [employeeWorkOrder].[isActive] = 1 AND [employeeWorkOrder].[companyId]='" . $this->getCompanyId(
                            ) . "' "
                    );
                } else if ($this->getVendor() == self::ORACLE) {
                    $this->setAuditFilter(
                            " EMPLOYEEWORKORDER.ISACTIVE = 1  AND EMPLOYEEWORKORDER.COMPANYID='" . $this->getCompanyId(
                            ) . "'"
                    );
                }
            } else if ($_SESSION['isAdmin'] == 1) {
                if ($this->getVendor() == self::MYSQL) {
                    $this->setAuditFilter("   `employeeworkorder`.`companyId`='" . $this->getCompanyId() . "'	");
                } else if ($this->getVendor() == self::MSSQL) {
                    $this->setAuditFilter(" [employeeWorkOrder].[companyId]='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::ORACLE) {
                    $this->setAuditFilter(" EMPLOYEEWORKORDER.COMPANYID='" . $this->getCompanyId() . "' ");
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
       SELECT                    `employeeworkorder`.`employeeWorkOrderId`,
                    `company`.`companyDescription`,
                    `employeeworkorder`.`companyId`,
                    `employee`.`employeeFirstName`,
                    `employeeworkorder`.`employeeId`,
                    `shift`.`shiftDescription`,
                    `employeeworkorder`.`shiftId`,
                    `invoiceproject`.`invoiceProjectDescription`,
                    `employeeworkorder`.`invoiceProjectId`,
					`employeeworkorder`.`invoiceId`,
                    `milestone`.`milestoneDescription`,
                    `employeeworkorder`.`milestoneId`,
                    `employeeworkorder`.`documentNumber`,
                    `employeeworkorder`.`employeeWorkOrderDate`,
                    `employeeworkorder`.`employeeWorkOrderStartDate`,
                    `employeeworkorder`.`employeeWorkOrderEndDate`,
                    `employeeworkorder`.`employeeWorkOrderDueDate`,
                    `employeeworkorder`.`employeeWorkOrderRate`,
                    `employeeworkorder`.`employeeWorkOrderDescription`,
                    `employeeworkorder`.`isDefault`,
                    `employeeworkorder`.`isNew`,
                    `employeeworkorder`.`isDraft`,
                    `employeeworkorder`.`isUpdate`,
                    `employeeworkorder`.`isDelete`,
                    `employeeworkorder`.`isActive`,
                    `employeeworkorder`.`isApproved`,
                    `employeeworkorder`.`isReview`,
                    `employeeworkorder`.`isPost`,
                    `employeeworkorder`.`isClientViewable`,
                    `employeeworkorder`.`isAllDayEvent`,
                    `employeeworkorder`.`isComplete`,
                    `employeeworkorder`.`executeBy`,
                    `employeeworkorder`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `employeeworkorder`
		  JOIN      `staff`
		  ON        `employeeworkorder`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `employeeworkorder`.`companyId`
	JOIN	`employee`
	ON		`employee`.`employeeId` = `employeeworkorder`.`employeeId`
	JOIN	`shift`
	ON		`shift`.`shiftId` = `employeeworkorder`.`shiftId`
	JOIN	`invoiceproject`
	ON		`invoiceproject`.`invoiceProjectId` = `employeeworkorder`.`invoiceProjectId`
	JOIN	`invoice`
	ON		`invoice`.`invoiceId` = `employeeworkorder`.`invoiceId`
	JOIN	`milestone`
	ON		`milestone`.`milestoneId` = `employeeworkorder`.`milestoneId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getEmployeeWorkOrderId(0, 'single')) {
                $sql .= " AND `employeeworkorder`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getEmployeeWorkOrderId(0, 'single') . "'";
            }
            if ($this->model->getEmployeeId()) {
                $sql .= " AND `employeeworkorder`.`employeeId`='" . $this->model->getEmployeeId() . "'";
            }
            if ($this->model->getShiftId()) {
                $sql .= " AND `employeeworkorder`.`shiftId`='" . $this->model->getShiftId() . "'";
            }
            if ($this->model->getInvoiceProjectId()) {
                $sql .= " AND `employeeworkorder`.`invoiceProjectId`='" . $this->model->getInvoiceProjectId() . "'";
            }
            if ($this->model->getInvoiceId()) {
                $sql .= " AND `employeeworkorder`.`invoiceId`='" . $this->model->getInvoiceId() . "'";
            }
            if ($this->model->getMilestoneId()) {
                $sql .= " AND `employeeworkorder`.`milestoneId`='" . $this->model->getMilestoneId() . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {

            $sql = "
		  SELECT                    [employeeWorkOrder].[employeeWorkOrderId],
                    [company].[companyDescription],
                    [employeeWorkOrder].[companyId],
                    [employee].[employeeFirstName],
                    [employeeWorkOrder].[employeeId],
                    [shift].[shiftDescription],
                    [employeeWorkOrder].[shiftId],
                    [invoiceProject].[invoiceProjectDescription],
                    [employeeWorkOrder].[invoiceProjectId],
					[employeeWorkOrder].[invoiceId],
                    [milestone].[milestoneDescription],
                    [employeeWorkOrder].[milestoneId],
                    [employeeWorkOrder].[documentNumber],
                    [employeeWorkOrder].[employeeWorkOrderDate],
                    [employeeWorkOrder].[employeeWorkOrderStartDate],
                    [employeeWorkOrder].[employeeWorkOrderEndDate],
                    [employeeWorkOrder].[employeeWorkOrderDueDate],
                    [employeeWorkOrder].[employeeWorkOrderRate],
                    [employeeWorkOrder].[employeeWorkOrderDescription],
                    [employeeWorkOrder].[isDefault],
                    [employeeWorkOrder].[isNew],
                    [employeeWorkOrder].[isDraft],
                    [employeeWorkOrder].[isUpdate],
                    [employeeWorkOrder].[isDelete],
                    [employeeWorkOrder].[isActive],
                    [employeeWorkOrder].[isApproved],
                    [employeeWorkOrder].[isReview],
                    [employeeWorkOrder].[isPost],
                    [employeeWorkOrder].[isClientViewable],
                    [employeeWorkOrder].[isAllDayEvent],
                    [employeeWorkOrder].[isComplete],
                    [employeeWorkOrder].[executeBy],
                    [employeeWorkOrder].[executeTime],
                    [staff].[staffName]
		  FROM 	[employeeWorkOrder]
		  JOIN	[staff]
		  ON	[employeeWorkOrder].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [employeeWorkOrder].[companyId]
	JOIN	[employee]
	ON		[employee].[employeeId] = [employeeWorkOrder].[employeeId]
	JOIN	[shift]
	ON		[shift].[shiftId] = [employeeWorkOrder].[shiftId]
	JOIN	[invoiceProject]
	ON		[invoiceProject].[invoiceProjectId] = [employeeWorkOrder].[invoiceProjectId]
	JOIN	[invoice]
	ON		[invoice].[invoiceId] = [employeeWorkOrder].[invoiceId]
	JOIN	[milestone]
	ON		[milestone].[milestoneId] = [employeeWorkOrder].[milestoneId]
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getEmployeeWorkOrderId(0, 'single')) {
                $sql .= " AND [employeeWorkOrder].[" . $this->model->getPrimaryKeyName(
                        ) . "]		=	'" . $this->model->getEmployeeWorkOrderId(0, 'single') . "'";
            }
            if ($this->model->getEmployeeId()) {
                $sql .= " AND [employeeWorkOrder].[employeeId]='" . $this->model->getEmployeeId() . "'";
            }
            if ($this->model->getShiftId()) {
                $sql .= " AND [employeeWorkOrder].[shiftId]='" . $this->model->getShiftId() . "'";
            }
            if ($this->model->getInvoiceProjectId()) {
                $sql .= " AND [employeeWorkOrder].[invoiceProjectId]='" . $this->model->getInvoiceProjectId() . "'";
            }
            if ($this->model->getInvoiceId()) {
                $sql .= " AND [employeeWorkOrder].[invoiceId]='" . $this->model->getInvoiceId() . "'";
            }
            if ($this->model->getMilestoneId()) {
                $sql .= " AND [employeeWorkOrder].[milestoneId]='" . $this->model->getMilestoneId() . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {

            $sql = "
		  SELECT                    EMPLOYEEWORKORDER.EMPLOYEEWORKORDERID AS \"employeeWorkOrderId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    EMPLOYEEWORKORDER.COMPANYID AS \"companyId\",
                    EMPLOYEE.EMPLOYEEFIRSTNAME AS  \"employeeFirstName\",
                    EMPLOYEEWORKORDER.EMPLOYEEID AS \"employeeId\",
                    SHIFT.SHIFTDESCRIPTION AS  \"shiftDescription\",
                    EMPLOYEEWORKORDER.SHIFTID AS \"shiftId\",
                    INVOICEPROJECT.INVOICEPROJECTDESCRIPTION AS  \"invoiceProjectDescription\",
                    EMPLOYEEWORKORDER.INVOICEPROJECTID AS \"invoiceProjectId\",
					EMPLOYEEWORKORDER.INVOICEID AS \"invoiceId\",
                    MILESTONE.MILESTONEDESCRIPTION AS  \"milestoneDescription\",
                    EMPLOYEEWORKORDER.MILESTONEID AS \"milestoneId\",
                    EMPLOYEEWORKORDER.DOCUMENTNUMBER AS \"documentNumber\",
                    EMPLOYEEWORKORDER.EMPLOYEEWORKORDERDATE AS \"employeeWorkOrderDate\",
                    EMPLOYEEWORKORDER.EMPLOYEEWORKORDERSTARTDATE AS \"employeeWorkOrderStartDate\",
                    EMPLOYEEWORKORDER.EMPLOYEEWORKORDERENDDATE AS \"employeeWorkOrderEndDate\",
                    EMPLOYEEWORKORDER.EMPLOYEEWORKORDERDUEDATE AS \"employeeWorkOrderDueDate\",
                    EMPLOYEEWORKORDER.EMPLOYEEWORKORDERRATE AS \"employeeWorkOrderRate\",
                    EMPLOYEEWORKORDER.EMPLOYEEWORKORDERDESCRIPTION AS \"employeeWorkOrderDescription\",
                    EMPLOYEEWORKORDER.ISDEFAULT AS \"isDefault\",
                    EMPLOYEEWORKORDER.ISNEW AS \"isNew\",
                    EMPLOYEEWORKORDER.ISDRAFT AS \"isDraft\",
                    EMPLOYEEWORKORDER.ISUPDATE AS \"isUpdate\",
                    EMPLOYEEWORKORDER.ISDELETE AS \"isDelete\",
                    EMPLOYEEWORKORDER.ISACTIVE AS \"isActive\",
                    EMPLOYEEWORKORDER.ISAPPROVED AS \"isApproved\",
                    EMPLOYEEWORKORDER.ISREVIEW AS \"isReview\",
                    EMPLOYEEWORKORDER.ISPOST AS \"isPost\",
                    EMPLOYEEWORKORDER.ISCLIENTVIEWABLE AS \"isClientViewable\",
                    EMPLOYEEWORKORDER.ISALLDAYEVENT AS \"isAllDayEvent\",
                    EMPLOYEEWORKORDER.ISCOMPLETE AS \"isComplete\",
                    EMPLOYEEWORKORDER.EXECUTEBY AS \"executeBy\",
                    EMPLOYEEWORKORDER.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\"
		  FROM 	EMPLOYEEWORKORDER
		  JOIN	STAFF
		  ON	EMPLOYEEWORKORDER.EXECUTEBY = STAFF.STAFFID
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = EMPLOYEEWORKORDER.COMPANYID
	JOIN	EMPLOYEE
	ON		EMPLOYEE.EMPLOYEEID = EMPLOYEEWORKORDER.EMPLOYEEID
	JOIN	SHIFT
	ON		SHIFT.SHIFTID = EMPLOYEEWORKORDER.SHIFTID
	JOIN	INVOICEPROJECT
	ON		INVOICEPROJECT.INVOICEPROJECTID = EMPLOYEEWORKORDER.INVOICEPROJECTID
	JOIN	INVOICE
	ON		INVOICE.INVOICEID = EMPLOYEEWORKORDER.INVOICEID
	JOIN	MILESTONE
	ON		MILESTONE.MILESTONEID = EMPLOYEEWORKORDER.MILESTONEID
         WHERE     " . $this->getAuditFilter();
            if ($this->model->getEmployeeWorkOrderId(0, 'single')) {
                $sql .= " AND EMPLOYEEWORKORDER. " . strtoupper(
                                $this->model->getPrimaryKeyName()
                        ) . "='" . $this->model->getEmployeeWorkOrderId(0, 'single') . "'";
            }
            if ($this->model->getEmployeeId()) {
                $sql .= " AND EMPLOYEEWORKORDER.EMPLOYEEID='" . $this->model->getEmployeeId() . "'";
            }
            if ($this->model->getShiftId()) {
                $sql .= " AND EMPLOYEEWORKORDER.SHIFTID='" . $this->model->getShiftId() . "'";
            }
            if ($this->model->getInvoiceProjectId()) {
                $sql .= " AND EMPLOYEEWORKORDER.INVOICEPROJECTID='" . $this->model->getInvoiceProjectId() . "'";
            }
            if ($this->model->getInvoiceId()) {
                $sql .= " AND EMPLOYEEWORKORDER.INVOICEID='" . $this->model->getInvoiceId() . "'";
            }
            if ($this->model->getMilestoneId()) {
                $sql .= " AND EMPLOYEEWORKORDER.MILESTONEID='" . $this->model->getMilestoneId() . "'";
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
                $sql .= " AND `employeeworkorder`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql .= " AND [employeeWorkOrder].[" . $this->model->getFilterCharacter(
                        ) . "] like '" . $this->getCharacterQuery() . "%'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql .= " AND Initcap(EMPLOYEEWORKORDER." . strtoupper(
                                $this->model->getFilterCharacter()
                        ) . ") LIKE Initcap('" . $this->getCharacterQuery() . "%');";
            }
        }
        /**
         * filter column based on Range Of Date
         * Example Day,Week,Month,Year
         */
        if ($this->getDateRangeStartQuery()) {
            if ($this->getVendor() == self::MYSQL) {
                $sql .= $this->q->dateFilter(
                        'employeeworkorder', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else if ($this->getVendor() == self::MSSQL) {
                $sql .= $this->q->dateFilter(
                        'employeeWorkOrder', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else if ($this->getVendor() == self::ORACLE) {
                $sql .= $this->q->dateFilter(
                        'EMPLOYEEWORKORDER', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            }
        }
        /**
         * filter column don't want to filter.Example may contain  sensitive information or unwanted to be search.
         * E.g  $filterArray=array('`leaf`.`leafId`');
         * @variables $filterArray;
         */
        $filterArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $filterArray = array("`employeeworkorder`.`employeeWorkOrderId`");
        } else if ($this->getVendor() == self::MSSQL) {
            $filterArray = array("[employeeWorkOrder].[employeeWorkOrderId]");
        } else if ($this->getVendor() == self::ORACLE) {
            $filterArray = array("EMPLOYEEWORKORDER.EMPLOYEEWORKORDERID");
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('employeeworkorder', 'employee', 'shift', 'invoiceProject', 'invoice', 'milestone');
        } else if ($this->getVendor() == self::MSSQL) {
            $tableArray = array('employeeworkorder', 'employee', 'shift', 'invoiceProject', 'invoice', 'milestone');
        } else if ($this->getVendor() == self::ORACLE) {
            $tableArray = array('EMPLOYEEWORKORDER', 'EMPLOYEE', 'SHIFT', 'INVOICEPROJECT', 'INVOICE', 'MILESTONE');
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
                $sql .= "	ORDER BY [" . $this->model->getTableName() . "].[" . $this->model->getPrimaryKeyName(
                        ) . "] ASC ";
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
        if (!($this->model->getEmployeeWorkOrderId(0, 'single'))) {
            try {
                $this->q->read($sqlDerived);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        $items = array();
        $i = 1;
        while (($row = $this->q->fetchAssoc()) == true) {
            $row['total'] = $total; // small override
            $row['counter'] = $this->getStart() + 27;
            if ($this->model->getEmployeeWorkOrderId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord(
                        'value', $this->model->getEmployeeWorkOrderId(0, 'single')
                );
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getEmployeeWorkOrderId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            // trial testing to match with calendar system

            $events[] = array(
                $row['employeeWorkOrderId'],
                $row['employeeWorkOrderDescription'],
                $this->d->php2JsTime($this->d->mySql2PhpTime($row['employeeWorkOrderStartDate'])),
                $this->d->php2JsTime($this->d->mySql2PhpTime($row['employeeWorkOrderEndDate'])),
                $row['isAllDayEvent'],
                ($row['isAllDayEvent'] != 1 && date(
                        "Y-m-d", $this->d->mySql2PhpTime($row['employeeWorkOrderEndDate'])
                ) > date("Y-m-d", $this->d->mySql2PhpTime($row['employeeWorkOrderStartDate'])) ? 1 : 0),
                //more than one day event
                $row['RecurringRule'],
                //Recurring event,
                $row['employeeWorkOrderColor'],
                1,
                //editable
                $row['locationDescription'] . "-" . $row['branchName'],
                ''
                    //$attends
            );
            // trial testing to match with calendar system
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else if ($this->getPageOutput() == 'json') {
            if ($this->model->getEmployeeWorkOrderId(0, 'single')) {
                $end = microtime(true);
                $time = $end - $start;
                echo str_replace(
                        array("[", "]"), "", json_encode(
                                array(
                                    'success' => true,
                                    'total' => $total,
                                    'message' => $this->t['viewRecordMessageLabel'],
                                    'time' => $time,
                                    'start' => $start,
                                    'end' => $end,
                                    'issort' => true,
                                    'firstRecord' => $this->firstRecord('value'),
                                    'previousRecord' => $this->previousRecord(
                                            'value', $this->model->getEmployeeWorkOrderId(0, 'single')
                                    ),
                                    'nextRecord' => $this->nextRecord(
                                            'value', $this->model->getEmployeeWorkOrderId(0, 'single')
                                    ),
                                    'lastRecord' => $this->lastRecord('value'),
                                    'data' => $items,
                                    'events' => $events
                                )
                        )
                );
                exit();
            } else {
                if (count($items) == 0) {
                    $items = '';
                }
                $end = microtime(true);
                $time = $end - $start;
                if ($this->model->getViewType() == 'week') {
                    $startDate = $this->model->getShowDate();
                    $dateStartArray = explode('-', $startDate);
                    $dayStart = $this->setZero($dateStartArray[2]);
                    $monthStart = $this->setZero($dateStartArray[1]);
                    $yearStart = $dateStartArray[0];
                    $d = new \DateTime(date('Y-m-d', mktime(0, 0, 0, $monthStart, ($dayStart), $yearStart)));
                    $weekday = $d->format('w');
                    $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
                    $d->modify("-$diff day");
                    $d->modify('+6 day');
                    $endDate = ($d->format('Y-m-d'));
                }
                echo json_encode(
                        array(
                            'success' => true,
                            'total' => $total,
                            'message' => $this->t['viewRecordMessageLabel'],
                            'time' => $time,
                            'start' => $startDate,
                            'end' => $endDate,
                            'firstRecord' => $this->recordSet->firstRecord('value'),
                            'previousRecord' => $this->recordSet->previousRecord(
                                    'value', $this->model->getEmployeeWorkOrderId(0, 'single')
                            ),
                            'nextRecord' => $this->recordSet->nextRecord(
                                    'value', $this->model->getEmployeeWorkOrderId(0, 'single')
                            ),
                            'lastRecord' => $this->recordSet->lastRecord('value'),
                            'data' => $items,
                            'events' => $events
                        )
                );
                exit();
            }
        }
    }

    /**
     * First Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @return int
     */
    function firstRecord($value) {
        return $this->recordSet->firstRecord($value);
    }

    /**
     * Previous Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @param int $primaryKeyValue
     * @return int
     */
    function previousRecord($value, $primaryKeyValue) {
        return $this->recordSet->previousRecord($value, $primaryKeyValue);
    }

    /**
     * Next Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @param int $primaryKeyValue Current  Primary Key Value
     * @return int
     */
    function nextRecord($value, $primaryKeyValue) {
        return $this->recordSet->nextRecord($value, $primaryKeyValue);
    }

    /**
     * Last Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @return int
     */
    function lastRecord($value) {
        return $this->recordSet->lastRecord($value);
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
        if (!$this->model->getEmployeeId()) {
            $this->model->setEmployeeId($this->service->getEmployeeDefaultValue());
        }
        if (!$this->model->getShiftId()) {
            $this->model->setShiftId($this->service->getShiftDefaultValue());
        }
        if (!$this->model->getInvoiceProjectId()) {
            $this->model->setInvoiceProjectId($this->service->getInvoiceProjectDefaultValue());
        }
        if (!$this->model->getInvoiceId()) {
            $this->model->setInvoiceId($this->service->getInvoiceDefaultValue());
        }
        if (!$this->model->getMilestoneId()) {
            $this->model->setMilestoneId($this->service->getMilestoneDefaultValue());
        }
        if (!$this->model->getLocationId()) {
            $this->model->setLocationId($this->service->getLocationDefaultValue());
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`employeeWorkOrder`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getEmployeeWorkOrderId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[employeeWorkOrder]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getEmployeeWorkOrderId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	EMPLOYEEWORKORDER
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getEmployeeWorkOrderId(
                            0, 'single'
                    ) . "' ";
        }
        $result = $this->q->fast($sql);
        $total = $this->q->numberRows($result, $sql);
        if ($total == 0) {
            echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel']));
            exit();
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
               UPDATE `employeeworkorder` SET
                       `employeeId` = '" . $this->model->getEmployeeId() . "',
                       `shiftId` = '" . $this->model->getShiftId() . "',
                       `invoiceProjectId` = '" . $this->model->getInvoiceProjectId() . "',
					   `invoiceId` = '" . $this->model->getInvoiceId() . "',
                       `milestoneId` = '" . $this->model->getMilestoneId() . "',
					   `locationId` = '" . $this->model->getLocationId() . "',
                       `documentNumber` = '" . $this->model->getDocumentNumber() . "',
                       `employeeWorkOrderDate` = '" . $this->model->getEmployeeWorkOrderDate() . "',
                       `employeeWorkOrderStartDate` = '" . $this->model->getEmployeeWorkOrderStartDate() . "',
                       `employeeWorkOrderEndDate` = '" . $this->model->getEmployeeWorkOrderEndDate() . "',
                       `employeeWorkOrderDueDate` = '" . $this->model->getEmployeeWorkOrderDueDate() . "',
                       `employeeWorkOrderRate` = '" . $this->model->getEmployeeWorkOrderRate() . "',
					   `employeeWorkOrderColor` = '" . $this->model->getEmployeeWorkOrderColor() . "',
                       `employeeWorkOrderDescription` = '" . $this->model->getEmployeeWorkOrderDescription() . "',
                       `isDefault` = '" . $this->model->getIsDefault('0', 'single') . "',
                       `isNew` = '" . $this->model->getIsNew('0', 'single') . "',
                       `isDraft` = '" . $this->model->getIsDraft('0', 'single') . "',
                       `isUpdate` = '" . $this->model->getIsUpdate('0', 'single') . "',
                       `isDelete` = '" . $this->model->getIsDelete('0', 'single') . "',
                       `isActive` = '" . $this->model->getIsActive('0', 'single') . "',
                       `isApproved` = '" . $this->model->getIsApproved('0', 'single') . "',
                       `isReview` = '" . $this->model->getIsReview('0', 'single') . "',
                       `isPost` = '" . $this->model->getIsPost('0', 'single') . "',
                       `isClientViewable` = '" . $this->model->getIsClientViewable() . "',
                       `isAllDayEvent` = '" . $this->model->getIsAllDayEvent() . "',
                       `isComplete` = '" . $this->model->getIsComplete() . "',
                       `executeBy` = '" . $this->model->getExecuteBy('0', 'single') . "',
                       `executeTime` = " . $this->model->getExecuteTime() . "
               WHERE    `employeeWorkOrderId`='" . $this->model->getEmployeeWorkOrderId('0', 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE [employeeWorkOrder] SET
                       [employeeId] = '" . $this->model->getEmployeeId() . "',
                       [shiftId] = '" . $this->model->getShiftId() . "',
                       [invoiceProjectId] = '" . $this->model->getInvoiceProjectId() . "',
					   [invoiceId] = '" . $this->model->getInvoiceId() . "',
                       [milestoneId] = '" . $this->model->getMilestoneId() . "',
                       [documentNumber] = '" . $this->model->getDocumentNumber() . "',
                       [employeeWorkOrderDate] = '" . $this->model->getEmployeeWorkOrderDate() . "',
                       [employeeWorkOrderStartDate] = '" . $this->model->getEmployeeWorkOrderStartDate() . "',
                       [employeeWorkOrderEndDate] = '" . $this->model->getEmployeeWorkOrderEndDate() . "',
                       [employeeWorkOrderDueDate] = '" . $this->model->getEmployeeWorkOrderDueDate() . "',
                       [employeeWorkOrderRate] = '" . $this->model->getEmployeeWorkOrderRate() . "',
					   [employeeWorkOrderColor] = '" . $this->model->getEmployeeWorkOrderColor() . "',
                       [employeeWorkOrderDescription] = '" . $this->model->getEmployeeWorkOrderDescription() . "',
                       [isDefault] = '" . $this->model->getIsDefault(0, 'single') . "',
                       [isNew] = '" . $this->model->getIsNew(0, 'single') . "',
                       [isDraft] = '" . $this->model->getIsDraft(0, 'single') . "',
                       [isUpdate] = '" . $this->model->getIsUpdate(0, 'single') . "',
                       [isDelete] = '" . $this->model->getIsDelete(0, 'single') . "',
                       [isActive] = '" . $this->model->getIsActive(0, 'single') . "',
                       [isApproved] = '" . $this->model->getIsApproved(0, 'single') . "',
                       [isReview] = '" . $this->model->getIsReview(0, 'single') . "',
                       [isPost] = '" . $this->model->getIsPost(0, 'single') . "',
                       [isClientViewable] = '" . $this->model->getIsClientViewable() . "',
                       [isAllDayEvent] = '" . $this->model->getIsAllDayEvent() . "',
                       [isComplete] = '" . $this->model->getIsComplete() . "',
                       [executeBy] = '" . $this->model->getExecuteBy(0, 'single') . "',
                       [executeTime] = " . $this->model->getExecuteTime() . "
                WHERE   [employeeWorkOrderId]='" . $this->model->getEmployeeWorkOrderId('0', 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE EMPLOYEEWORKORDER SET
                        EMPLOYEEID = '" . $this->model->getEmployeeId() . "',
                       SHIFTID = '" . $this->model->getShiftId() . "',
                       INVOICEPROJECTID = '" . $this->model->getInvoiceProjectId() . "',
					   INVOICEID = '" . $this->model->getInvoiceId() . "',
                       MILESTONEID = '" . $this->model->getMilestoneId() . "',
					   LOCATIONID = '" . $this->model->getLocationId() . "',
                       DOCUMENTNUMBER = '" . $this->model->getDocumentNumber() . "',
                       EMPLOYEEWORKORDERDATE = '" . $this->model->getEmployeeWorkOrderDate() . "',
                       EMPLOYEEWORKORDERSTARTDATE = '" . $this->model->getEmployeeWorkOrderStartDate() . "',
                       EMPLOYEEWORKORDERENDDATE = '" . $this->model->getEmployeeWorkOrderEndDate() . "',
                       EMPLOYEEWORKORDERDUEDATE = '" . $this->model->getEmployeeWorkOrderDueDate() . "',
                       EMPLOYEEWORKORDERRATE = '" . $this->model->getEmployeeWorkOrderRate() . "',
					   EMPLOYEEWORKORDERCOLOR = '" . $this->model->getEmployeeWorkOrderColor() . "',
                       EMPLOYEEWORKORDERDESCRIPTION = '" . $this->model->getEmployeeWorkOrderDescription() . "',
                       ISDEFAULT = '" . $this->model->getIsDefault(0, 'single') . "',
                       ISNEW = '" . $this->model->getIsNew(0, 'single') . "',
                       ISDRAFT = '" . $this->model->getIsDraft(0, 'single') . "',
                       ISUPDATE = '" . $this->model->getIsUpdate(0, 'single') . "',
                       ISDELETE = '" . $this->model->getIsDelete(0, 'single') . "',
                       ISACTIVE = '" . $this->model->getIsActive(0, 'single') . "',
                       ISAPPROVED = '" . $this->model->getIsApproved(0, 'single') . "',
                       ISREVIEW = '" . $this->model->getIsReview(0, 'single') . "',
                       ISPOST = '" . $this->model->getIsPost(0, 'single') . "',
                       ISCLIENTVIEWABLE = '" . $this->model->getIsClientViewable() . "',
                       ISALLDAYEVENT = '" . $this->model->getIsAllDayEvent() . "',
                       ISCOMPLETE = '" . $this->model->getIsComplete() . "',
                       EXECUTEBY = '" . $this->model->getExecuteBy(0, 'single') . "',
                       EXECUTETIME = " . $this->model->getExecuteTime() . "
                WHERE  EMPLOYEEWORKORDERID='" . $this->model->getEmployeeWorkOrderId('0', 'single') . "'";
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
                array(
                    "success" => true,
                    "message" => $this->t['updateRecordTextLabel'],
                    "time" => $time
                )
        );
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
           FROM 	`employeeworkorder`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getEmployeeWorkOrderId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[employeeWorkOrder]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getEmployeeWorkOrderId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	EMPLOYEEWORKORDER
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getEmployeeWorkOrderId(
                            0, 'single'
                    ) . "' ";
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
               UPDATE  `employeeworkorder`
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
               WHERE   `employeeWorkOrderId`   =  '" . $this->model->getEmployeeWorkOrderId(0, 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
               UPDATE  [employeeWorkOrder]
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
               WHERE   [employeeWorkOrderId]	=  '" . $this->model->getEmployeeWorkOrderId(0, 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
               UPDATE  EMPLOYEEWORKORDER
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
               WHERE   EMPLOYEEWORKORDERID	=  '" . $this->model->getEmployeeWorkOrderId(0, 'single') . "'";
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
                array(
                    "success" => true,
                    "message" => $this->t['deleteRecordTextLabel'],
                    "time" => $time
                )
        );
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
           SELECT  `employeeWorkOrderCode`
           FROM    `employeeworkorder`
           WHERE   `employeeWorkOrderCode` 	= 	'" . $this->model->getEmployeeWorkOrderCode() . "'
           AND     `isActive`  =   1
           AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT  [employeeWorkOrderCode]
           FROM    [employeeWorkOrder]
           WHERE   [employeeWorkOrderCode] = 	'" . $this->model->getEmployeeWorkOrderCode() . "'
           AND     [isActive]  =   1
           AND     [companyId] =	'" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
               SELECT  EMPLOYEEWORKORDERCODE as \"employeeWorkOrderCode\"
               FROM    EMPLOYEEWORKORDER
               WHERE   EMPLOYEEWORKORDERCODE	= 	'" . $this->model->getEmployeeWorkOrderCode() . "'
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
                    array(
                        "success" => true,
                        "total" => $total,
                        "message" => $this->t['duplicateMessageLabel'],
                        "referenceNo" => $row ['referenceNo'],
                        "time" => $time
                    )
            );
            exit();
        } else {
            $end = microtime(true);
            $time = $end - $start;
            echo json_encode(
                    array(
                        "success" => true,
                        "total" => $total,
                        "message" => $this->t['duplicateNotMessageLabel'],
                        "time" => $time
                    )
            );
            exit();
        }
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
     * Return  Employee
     * @return null|string
     */
    public function getEmployee() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getEmployee();
    }

    /**
     * Return  Shift
     * @return null|string
     */
    public function getShift() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getShift();
    }

    /**
     * Return  InvoiceProject
     * @return null|string
     */
    public function getInvoiceProject() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getInvoiceProject();
    }

    /**
     * Return  Milestone
     * @return null|string
     */
    public function getMilestone() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getMilestone();
    }

    /**
     * Return Calendar Mode option ('time','day','month','week','year')
     * @return void
     */
    function getCalendarView() {
        return $this->model->getCalendarView();
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
            $sql = str_replace(
                    $_SESSION ['start'] . "," . $_SESSION ['limit'], "", str_replace("LIMIT", "", $_SESSION ['sql'])
            );
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
                ->setSubject('employeeWorkOrder')
                ->setDescription('Generated by PhpExcel an Idcms Generator')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('humanResource/workOrder');
        $this->excel->setActiveSheetIndex(0);
        // check file exist or not and return response 
        $styleThinBlackBorderOutline = array(
            'borders' => array(
                'inside' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '000000')
                ),
                'outline' => array('style' => \PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => '000000'))
            )
        );
        // header all using  3 line  starting b 
        $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $this->excel->getActiveSheet()->setCellValue('B2', $this->getReportTitle());
        $this->excel->getActiveSheet()->setCellValue('R2', '');
        $this->excel->getActiveSheet()->mergeCells('B2:R2');
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.');
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['employeeIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['shiftIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['invoiceProjectIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['milestoneIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['documentNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['employeeWorkOrderDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['employeeWorkOrderStartDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['employeeWorkOrderEndDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['employeeWorkOrderDueDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('L3', $this->translate['employeeWorkOrderRateLabel']);
        $this->excel->getActiveSheet()->setCellValue('M3', $this->translate['employeeWorkOrderDescriptionLabel']);
        $this->excel->getActiveSheet()->setCellValue('N3', $this->translate['isClientViewableLabel']);
        $this->excel->getActiveSheet()->setCellValue('O3', $this->translate['isAllDayEventLabel']);
        $this->excel->getActiveSheet()->setCellValue('P3', $this->translate['isCompleteLabel']);
        $this->excel->getActiveSheet()->setCellValue('Q3', $this->translate['executeByLabel']);
        $this->excel->getActiveSheet()->setCellValue('R3', $this->translate['executeTimeLabel']);
        // 
        $loopRow = 4;
        $i = 0;
        \PHPExcel_Cell::setValueBinder(new \PHPExcel_Cell_AdvancedValueBinder());
        $lastRow = null;
        while (($row = $this->q->fetchAssoc()) == true) {
            //	echo print_r($row); 
            $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i);
            $this->excel->getActiveSheet()->setCellValue('C' . $loopRow, strip_tags($row ['employeeFirstName']));
            $this->excel->getActiveSheet()->setCellValue('D' . $loopRow, strip_tags($row ['shiftDescription']));
            $this->excel->getActiveSheet()->setCellValue(
                    'E' . $loopRow, strip_tags($row ['invoiceProjectDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue('F' . $loopRow, strip_tags($row ['milestoneDescription']));
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, strip_tags($row ['documentNumber']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('H' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $this->excel->getActiveSheet()->setCellValue('H' . $loopRow, strip_tags($row ['employeeWorkOrderDate']));
            $this->excel->getActiveSheet()->setCellValue(
                    'I' . $loopRow, strip_tags($row ['employeeWorkOrderStartDate'])
            );
            $this->excel->getActiveSheet()->setCellValue('J' . $loopRow, strip_tags($row ['employeeWorkOrderEndDate']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('K' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $this->excel->getActiveSheet()->setCellValue('K' . $loopRow, strip_tags($row ['employeeWorkOrderDueDate']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('L' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('L' . $loopRow, strip_tags($row ['employeeWorkOrderRate']));
            $this->excel->getActiveSheet()->setCellValue(
                    'M' . $loopRow, strip_tags($row ['employeeWorkOrderDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue('N' . $loopRow, strip_tags($row ['isClientViewable']));
            $this->excel->getActiveSheet()->setCellValue('O' . $loopRow, strip_tags($row ['isAllDayEvent']));
            $this->excel->getActiveSheet()->setCellValue('P' . $loopRow, strip_tags($row ['isComplete']));
            $this->excel->getActiveSheet()->setCellValue('Q' . $loopRow, strip_tags($row ['staffName']));
            $this->excel->getActiveSheet()->setCellValue('R' . $loopRow, strip_tags($row ['executeTime']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('R' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
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
                $filename = "employeeWorkOrder" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/humanResource/workOrder/document/" . $folder . "/" . $filename;
                $this->documentTrail->createTrail($this->getLeafId(), $path, $filename);
                $objWriter->save($path);
                $file = fopen($path, 'r');
                if ($file) {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array(
                                "success" => true,
                                "message" => $this->t['fileGenerateMessageLabel'],
                                "filename" => $filename,
                                "folder" => $folder,
                                "time" => $time
                            )
                    );
                    exit();
                } else {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array(
                                "success" => false,
                                "message" => $this->t['fileNotGenerateMessageLabel'],
                                "time" => $time
                            )
                    );
                    exit();
                }
                break;
            case 'excel5':
                $objWriter = new \PHPExcel_Writer_Excel5($this->excel);
                $extension = '.xls';
                $folder = 'excel';
                $filename = "employeeWorkOrder" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/humanResource/workOrder/document/" . $folder . "/" . $filename;
                $this->documentTrail->createTrail($this->getLeafId(), $path, $filename);
                $objWriter->save($path);
                $file = fopen($path, 'r');
                if ($file) {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array(
                                "success" => true,
                                "message" => $this->t['fileGenerateMessageLabel'],
                                "filename" => $filename,
                                "folder" => $folder,
                                "time" => $time
                            )
                    );
                    exit();
                } else {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array(
                                "success" => false,
                                "message" => $this->t['fileNotGenerateMessageLabel'],
                                "time" => $time
                            )
                    );
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
                $filename = "employeeWorkOrder" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/humanResource/workOrder/document/" . $folder . "/" . $filename;
                $this->documentTrail->createTrail($this->getLeafId(), $path, $filename);
                $objWriter->save($path);
                $file = fopen($path, 'r');
                if ($file) {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array(
                                "success" => true,
                                "message" => $this->t['fileGenerateMessageLabel'],
                                "filename" => $filename,
                                "folder" => $folder,
                                "time" => $time
                            )
                    );
                    exit();
                } else {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array(
                                "success" => false,
                                "message" => $this->t['fileNotGenerateMessageLabel'],
                                "time" => $time
                            )
                    );
                    exit();
                }
                break;
            case 'csv':
                $objWriter = new \PHPExcel_Writer_CSV($this->excel);
                // $objWriter->setUseBOM(true); 
                // $objWriter->setPreCalculateFormulas(false); //calculation off 
                $extension = '.csv';
                $folder = 'excel';
                $filename = "employeeWorkOrder" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/humanResource/workOrder/document/" . $folder . "/" . $filename;
                $this->documentTrail->createTrail($this->getLeafId(), $path, $filename);
                $objWriter->save($path);
                $file = fopen($path, 'r');
                if ($file) {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array(
                                "success" => true,
                                "message" => $this->t['fileGenerateMessageLabel'],
                                "filename" => $filename,
                                "folder" => $folder,
                                "time" => $time
                            )
                    );
                    exit();
                } else {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array(
                                "success" => false,
                                "message" => $this->t['fileNotGenerateMessageLabel'],
                                "time" => $time
                            )
                    );
                    exit();
                }
                break;
        }
    }

}

if (isset($_POST ['method'])) {
    if (isset($_POST['output'])) {
        $employeeWorkOrderObject = new EmployeeWorkOrderClass ();
        if ($_POST['securityToken'] != $employeeWorkOrderObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $employeeWorkOrderObject->setLeafId($_POST ['leafId']);
        }
        if (isset($_POST ['offset'])) {
            $employeeWorkOrderObject->setStart($_POST ['offset']);
        }
        if (isset($_POST ['limit'])) {
            $employeeWorkOrderObject->setLimit($_POST ['limit']);
        }
        $employeeWorkOrderObject->setPageOutput($_POST['output']);
        $employeeWorkOrderObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $employeeWorkOrderObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $employeeWorkOrderObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $employeeWorkOrderObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $employeeWorkOrderObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$employeeWorkOrderObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$employeeWorkOrderObject->delete(); 
        }
        // calendar mode view
        if ($_POST['method'] == 'calendar') {
            if (isset($_POST['viewtype'])) {
                switch ($_POST['viewtype']) {
                    case "add":
                        $employeeWorkOrderObject->create();
                        break;
                    case "list":
                    case "time":
                    case "day":
                    case "week":
                    case "month":
                    case "year":
                        $employeeWorkOrderObject->read();
                        break;
                    case "update":
                        $employeeWorkOrderObject->update();
                        break;
                    case "remove":
                        $employeeWorkOrderObject->delete();
                        break;
                    case "adddetails":
                        // will no used for now.
                        break;
                    default:
                        header('Content-Type:application/json; charset=utf-8');
                        echo json_encode(array("success" => false, "message" => "May i knew who are you"));
                        exit();
                }
            }
        }
    }
}
if (isset($_GET ['method'])) {
    $employeeWorkOrderObject = new EmployeeWorkOrderClass ();
    if ($_GET['securityToken'] != $employeeWorkOrderObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $employeeWorkOrderObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $employeeWorkOrderObject->execute();
    /*
     * Update Status of The Table. Admin Level Only 
     */
    if ($_GET ['method'] == 'updateStatus') {
        $employeeWorkOrderObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key 
     */
    if ($_GET['method'] == 'duplicate') {
        $employeeWorkOrderObject->duplicate();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $employeeWorkOrderObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $employeeWorkOrderObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $employeeWorkOrderObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $employeeWorkOrderObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $employeeWorkOrderObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml') {
            $employeeWorkOrderObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        $employeeWorkOrderObject->setServiceOutput('option');
        if (($_GET['filter'] == 'employee')) {
            $employeeWorkOrderObject->getEmployee();
        }
        if (($_GET['filter'] == 'shift')) {
            $employeeWorkOrderObject->getShift();
        }
        if (($_GET['filter'] == 'invoiceProject')) {
            $employeeWorkOrderObject->getInvoiceProject();
        }
        if (($_GET['filter'] == 'milestone')) {
            $employeeWorkOrderObject->getMilestone();
        }
    }
}
?>
