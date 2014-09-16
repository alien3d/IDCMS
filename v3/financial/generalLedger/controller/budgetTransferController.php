<?php

namespace Core\Financial\GeneralLedger\BudgetTransfer\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\Financial\GeneralLedger\BudgetTransfer\Model\BudgetTransferModel;
use Core\Financial\GeneralLedger\BudgetTransfer\Service\BudgetTransferService;
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
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/model/budgetTransferModel.php");
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/service/budgetTransferService.php");

/**
 * Class BudgetTransfer
 * this is budgetTransfer controller files.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package  Core\Financial\GeneralLedger\BudgetTransfer\Controller
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class BudgetTransferClass extends ConfigClass {

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
     * @var \Core\Financial\GeneralLedger\BudgetTransfer\Model\BudgetTransferModel
     */
    public $model;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     * @var \PHPPowerPoint
     */
    //private $powerPoint;
    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\Financial\GeneralLedger\BudgetTransfer\Service\BudgetTransferService
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
        $this->setViewPath("./v3/financial/generalLedger/view/budgetTransfer.php");
        $this->setControllerPath("./v3/financial/generalLedger/controller/budgetTransferController.php");
        $this->setServicePath("./v3/financial/generalLedger/service/budgetTransferService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new BudgetTransferModel();
        $this->model->setVendor($this->getVendor());
        $this->model->execute();
        if ($this->getVendor() == self::MYSQL) {
            $this->q = new \Core\Database\Mysql\Vendor();
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $this->q = new \Core\Database\Mssql\Vendor();
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $this->q = new \Core\Database\Oracle\Vendor();
                }
            }
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
        $translator->setCurrentTable(array('budget', 'budgetTransfer', 'chartOfAccount', 'generalLedger'));
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

        $this->setReportTitle(
                $applicationNative . " :: " . $moduleNative . " :: " . $folderNative . " :: " . $leafNative
        );

        $this->systemFormat = new SharedClass();
        $this->systemFormat->q = $this->q;
        $this->systemFormat->setCurrentTable($this->model->getTableName());
        $this->systemFormat->execute();

        $this->systemFormatArray = $this->systemFormat->getSystemFormat();

        $this->service = new BudgetTransferService();
        $this->service->q = $this->q;
        $this->service->t = $this->t;
        $this->service->translate = $this->translate;
        $this->service->systemFormatArray = $this->systemFormatArray;
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
        if (!$this->model->getBudgetTransferFrom()) {
            $this->model->setBudgetTransferFrom($this->service->getBudgetTransferFromDefaultValue());
        }
        if (!$this->model->getBudgetTransferTo()) {
            $this->model->setBudgetTransferTo($this->service->getBudgetTransferToDefaultValue());
        }
        if (!$this->model->getFinanceYearId()) {
            $this->model->setFinanceYearId($this->service->getFinanceYearDefaultValue());
        }
        if (!$this->model->getFinancePeriodRangeId()) {
            $this->model->setFinancePeriodRangeId($this->service->getFinancePeriodRangeDefaultValue());
        }
        if (!$this->model->getDocumentNumber()) {
            $this->model->setDocumentNumber($this->getDocumentNumber());
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `budgettransfer`
            (
                 `companyId`,
                 `budgetTransferFrom`,
                 `budgetTransferTo`,
                 `financeYearId`,
                 `financePeriodRangeId`,
                 `documentNumber`,
                 `budgetTransferDate`,
                 `budgetTransferAmount`,
                 `budgetTransferComment`,
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
                 '" . $this->model->getBudgetTransferFrom() . "',
                 '" . $this->model->getBudgetTransferTo() . "',
                 '" . $this->model->getFinanceYearId() . "',
                 '" . $this->model->getFinancePeriodRangeId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getBudgetTransferDate() . "',
                 '" . $this->model->getBudgetTransferAmount() . "',
                 '" . $this->model->getBudgetTransferComment() . "',
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
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            INSERT INTO [budgetTransfer]
            (
                 [budgetTransferId],
                 [companyId],
                 [budgetTransferFrom],
                 [budgetTransferTo],
                 [financeYearId],
                 [financePeriodRangeId],
                 [documentNumber],
                 [budgetTransferDate],
                 [budgetTransferAmount],
                 [budgetTransferComment],
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
                 '" . $this->model->getBudgetTransferFrom() . "',
                 '" . $this->model->getBudgetTransferTo() . "',
                 '" . $this->model->getFinanceYearId() . "',
                 '" . $this->model->getFinancePeriodRangeId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getBudgetTransferDate() . "',
                 '" . $this->model->getBudgetTransferAmount() . "',
                 '" . $this->model->getBudgetTransferComment() . "',
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
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            INSERT INTO BUDGETTRANSFER
            (
                 COMPANYID,
                 BUDGETTRANSFERFROM,
                 BUDGETTRANSFERTO,
                 FINANCEYEARID,
                 FINANCEPERIODRANGEID,
                 DOCUMENTNUMBER,
                 BUDGETTRANSFERDATE,
                 BUDGETTRANSFERAMOUNT,
                 BUDGETTRANSFERCOMMENT,
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
                 '" . $this->model->getBudgetTransferFrom() . "',
                 '" . $this->model->getBudgetTransferTo() . "',
                 '" . $this->model->getFinanceYearId() . "',
                 '" . $this->model->getFinancePeriodRangeId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getBudgetTransferDate() . "',
                 '" . $this->model->getBudgetTransferAmount() . "',
                 '" . $this->model->getBudgetTransferComment() . "',
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
            }
        }
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $budgetTransferId = $this->q->lastInsertId();
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
                    "budgetTransferId" => $budgetTransferId,
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
         FROM    `budgettransfer`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT    COUNT(*) AS total
         FROM      [budgetTransfer]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT    COUNT(*)    AS  \"total\"
         FROM      BUDGETTRANSFER
         WHERE     ISACTIVE    =   1
         AND       COMPANYID   =   " . $this->getCompanyId() . " ";
                }
            }
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
                            " `bt`.`isActive` = 1  AND `budgettransfer`.`companyId`='" . $this->getCompanyId() . "' "
                    );
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [bt].[isActive] = 1 AND [budgetTransfer].[companyId]='" . $this->getCompanyId() . "' "
                        );
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(
                                    " bt.ISACTIVE = 1  AND BUDGETTRANSFER.COMPANYID='" . $this->getCompanyId() . "'"
                            );
                        }
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter("   `bt`.`companyId`='" . $this->getCompanyId() . "'	");
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(" [bt].[companyId]='" . $this->getCompanyId() . "' ");
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(" bt.COMPANYID='" . $this->getCompanyId() . "' ");
                            }
                        }
                    }
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
       SELECT       `bt`.`budgetTransferId`,
                    `company`.`companyDescription`,
                    `bt`.`companyId`,
                    (
                        SELECT concat(
                                        `chartOfAccountNumber`,
                                        concat(' - ',
                                            `chartOfAccountTitle`)
                                        )
                        FROM   `chartofaccount`
                        WHERE  `companyId`= '" . $this->getCompanyId() . "'
                        AND    `chartOfAccountId`   =   `bt`.`budgetTransferFrom`
                        LIMIT 1
                    )
                     AS `budgetTransferFromDescription`,
                    `bt`.`budgetTransferFrom`,
                    (
                        SELECT  concat(
                                        `chartOfAccountNumber`,
                                        concat(' - ',
                                            `chartOfAccountTitle`)
                                        )
                        FROM   `chartofaccount`
                        WHERE  `companyId`= '" . $this->getCompanyId() . "'
                        AND    `chartOfAccountId`   =   `bt`.`budgetTransferTo`
                        LIMIT  1
                    )
                     AS `budgetTransferToDescription`,
                    `bt`.`budgetTransferTo`,
                    `financeyear`.`financeYearYear`,
                    `bt`.`financeYearId`,
                    `financeperiodrange`.`financePeriodRangePeriod`,
                    `bt`.`financePeriodRangeId`,
                    `bt`.`documentNumber`,
                    `bt`.`budgetTransferDate`,
                    `bt`.`budgetTransferAmount`,
                    `bt`.`budgetTransferComment`,
                    `bt`.`isDefault`,
                    `bt`.`isNew`,
                    `bt`.`isDraft`,
                    `bt`.`isUpdate`,
                    `bt`.`isDelete`,
                    `bt`.`isActive`,
                    `bt`.`isApproved`,
                    `bt`.`isReview`,
                    `bt`.`isPost`,
                    `bt`.`executeBy`,
                    `bt`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `budgettransfer` AS `bt`
		  JOIN      `staff`
		  ON        `bt`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `bt`.`companyId`
	JOIN	`financeyear`
	ON		`financeyear`.`financeYearId` = `bt`.`financeYearId`
	JOIN	`financeperiodrange`
	ON		`financeperiodrange`.`financePeriodRangeId` = `bt`.`financePeriodRangeId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getBudgetTransferId(0, 'single')) {
                $sql .= " AND `bg`.`" . $this->model->getPrimaryKeyName() . "`='" . $this->model->getBudgetTransferId(
                                0, 'single'
                        ) . "'";
            }
            if ($this->model->getBudgetTransferFrom()) {
                $sql .= " AND `bg`.`budgetTransferFrom`='" . $this->model->getBudgetTransferFrom() . "'";
            }
            if ($this->model->getBudgetTransferTo()) {
                $sql .= " AND `bg`.`budgetTransferTo`='" . $this->model->getBudgetTransferTo() . "'";
            }
            if ($this->model->getFinanceYearId()) {
                $sql .= " AND `bg`.`financeYearId`='" . $this->model->getFinanceYearId() . "'";
            }
            if ($this->model->getFinancePeriodRangeId()) {
                $sql .= " AND `bg`.`financePeriodRangeId`='" . $this->model->getFinancePeriodRangeId() . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
		  SELECT                    [bt].[budgetTransferId],
                    [company].[companyDescription],
                    [bt].[companyId],
                    (
                        SELECT TOP 1 [chartOfAccountTitle]
                        FROM   [chartOfAccount]
                        WHERE  [companyId]          =   '" . $this->getCompanyId() . "'
                        AND    [chartOfAccountId]   =   [bt].[budgetTransferFrom]
                    )
                     AS [budgetTransferFromDescription],
                    [bt].[budgetTransferFrom],
                    (
                        SELECT TOP 1 [chartOfAccountTitle]
                        FROM   [chartOfAccount]
                        WHERE  [companyId]          =   '" . $this->getCompanyId() . "'
                        AND    [chartOfAccountId]   =   [bt].[budgetTransferTo]
                    )
                     AS [budgetTransferToDescription],
                    [bt].[budgetTransferTo],
                    [financeYear].[financeYearYear],
                    [bt].[financeYearId],
                     [financePeriodRange].[financePeriodRangePeriod],
                    [bt].[financePeriodRangeId],
                    [bt].[documentNumber],
                    [bt].[budgetTransferDate],
                    [bt].[budgetTransferAmount],
                    [bt].[budgetTransferComment],
                    [bt].[isDefault],
                    [bt].[isNew],
                    [bt].[isDraft],
                    [bt].[isUpdate],
                    [bt].[isDelete],
                    [bt].[isActive],
                    [bt].[isApproved],
                    [bt].[isReview],
                    [bt].[isPost],
                    [bt].[executeBy],
                    [bt].[executeTime],
                    [staff].[staffName]
		  FROM 	[budgetTransfer] AS[bt]
		  JOIN	[staff]
		  ON	[bt].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [bt].[companyId]
	JOIN	[financeYear]
	ON		[financeYear].[financeYearId] = [bt].[financeYearId]
	JOIN	 [financePeriodRange]
	ON		 [financePeriodRange].[financePeriodRangeId] = [bt].[financePeriodRangeId]
		  WHERE     " . $this->getAuditFilter();
                if ($this->model->getBudgetTransferId(0, 'single')) {
                    $sql .= " AND [bt].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getBudgetTransferId(0, 'single') . "'";
                }
                if ($this->model->getBudgetTransferFrom()) {
                    $sql .= " AND [bt].[budgetTransferFrom]='" . $this->model->getBudgetTransferFrom() . "'";
                }
                if ($this->model->getBudgetTransferTo()) {
                    $sql .= " AND [bt].[budgetTransferTo]='" . $this->model->getBudgetTransferTo() . "'";
                }
                if ($this->model->getFinanceYearId()) {
                    $sql .= " AND [bt].[financeYearId]='" . $this->model->getFinanceYearId() . "'";
                }
                if ($this->model->getFinancePeriodRangeId()) {
                    $sql .= " AND [bt].[financePeriodRangeId]='" . $this->model->getFinancePeriodRangeId() . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
		  SELECT                    bg.BUDGETTRANSFERID AS \"budgetTransferId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    bg.COMPANYID AS \"companyId\",
                    (

                        SELECT CHARTOFACCOUNTTITLE
                        FROM   CHARTOFACCOUNT
                        WHERE  COMPANYID            =   '" . $this->getCompanyId() . "'
                        AND    CHARTOFACCOUNTID     =   bt.BUDGETTRANSFERFROM

                    )
                     AS \"budgetTransferFromDescription\",
                    bg.BUDGETTRANSFERFROM AS \"budgetTransferFrom\",
                    (

                        SELECT CHARTOFACCOUNTTITLE
                        FROM   CHARTOFACCOUNT
                        WHERE  COMPANYID        =   '" . $this->getCompanyId() . "'
                        AND    CHARTOFACCOUNTID =   bt.BUDGETTRANSFERTO

                    )
                     AS \"budgetTransferFromDescription\",
                    bg.BUDGETTRANSFERTO AS \"budgetTransferTo\",
                    FINANCEYEAR.FINANCEYEARYEAR AS  \"financeYearYear\",
                    bg.FINANCEYEARID AS \"financeYearId\",
                    FINANCEPERIODRANGE.FINANCEPERIODRANGEPERIOD AS  \"financePeriodRangePeriod\",
                    bg.FINANCEPERIODRANGEID AS \"financePeriodRangeId\",
                    bg.DOCUMENTNUMBER AS \"documentNumber\",
                    bg.BUDGETTRANSFERDATE AS \"budgetTransferDate\",
                    bg.BUDGETTRANSFERAMOUNT AS \"budgetTransferAmount\",
                    bg.BUDGETTRANSFERCOMMENT AS \"budgetTransferComment\",
                    bg.ISDEFAULT AS \"isDefault\",
                    bg.ISNEW AS \"isNew\",
                    bg.ISDRAFT AS \"isDraft\",
                    bg.ISUPDATE AS \"isUpdate\",
                    bg.ISDELETE AS \"isDelete\",
                    bg.ISACTIVE AS \"isActive\",
                    bg.ISAPPROVED AS \"isApproved\",
                    bg.ISREVIEW AS \"isReview\",
                    bg.ISPOST AS \"isPost\",
                    bg.EXECUTEBY AS \"executeBy\",
                    bg.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\"
		  FROM 	BUDGETTRANSFER AS \"bg\"
		  JOIN	STAFF
		  ON	bg.EXECUTEBY = STAFF.STAFFID
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = bg.COMPANYID
	ON		FINANCEYEAR.FINANCEYEARID = bg.FINANCEYEARID
	JOIN	FINANCEPERIODRANGE
	ON		FINANCEPERIODRANGE.FINANCEPERIODRANGEID = bg.FINANCEPERIODRANGEID
         WHERE     " . $this->getAuditFilter();
                    if ($this->model->getBudgetTransferId(0, 'single')) {
                        $sql .= " AND bg. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getBudgetTransferId(0, 'single') . "'";
                    }
                    if ($this->model->getBudgetTransferFrom()) {
                        $sql .= " AND bg.BUDGETTRANSFERFROM='" . $this->model->getBudgetTransferFrom() . "'";
                    }
                    if ($this->model->getBudgetTransferTo()) {
                        $sql .= " AND bg.BUDGETTRANSFERTO='" . $this->model->getBudgetTransferTo() . "'";
                    }
                    if ($this->model->getFinanceYearId()) {
                        $sql .= " AND bg.FINANCEYEARID='" . $this->model->getFinanceYearId() . "'";
                    }
                    if ($this->model->getFinancePeriodRangeId()) {
                        $sql .= " AND bg.FINANCEPERIODRANGEID='" . $this->model->getFinancePeriodRangeId() . "'";
                    }
                } else {
                    header('Content-Type:application/json; charset=utf-8');
                    echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
                    exit();
                }
            }
        }
        /**
         * filter column based on first character
         */
        if ($this->getCharacterQuery()) {
            if ($this->getVendor() == self::MYSQL) {
                $sql .= " AND `bg`.`" . $this->model->getFilterCharacter() . "` like '" . $this->getCharacterQuery(
                        ) . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [bg].[" . $this->model->getFilterCharacter() . "] like '" . $this->getCharacterQuery(
                            ) . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND Initcap(bg." . strtoupper(
                                        $this->model->getFilterCharacter()
                                ) . ") LIKE Initcap('" . $this->getCharacterQuery() . "%');";
                    }
                }
            }
        }
        /**
         * filter column based on Range Of Date
         * Example Day,Week,Month,Year
         */
        if ($this->getDateRangeStartQuery()) {
            if ($this->getVendor() == self::MYSQL) {
                $sql .= $this->q->dateFilter(
                        'bg', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'bg', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'bg', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                        );
                    }
                }
            }
        }
        /**
         * filter column don't want to filter.Example may contain  sensitive information or unwanted to be search.
         * E.g  $filterArray=array('`leaf`.`leafId`');
         * @variables $filterArray;
         */
        $filterArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $filterArray = array(
                "`bg`.`budgetTransferId`",
                "`staff`.`staffPassword`"
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $filterArray = array(
                    "[bg].[budgetTransferId]",
                    "[staff].[staffPassword]"
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $filterArray = array(
                        "bg.BUDGETTRANSFERID",
                        "STAFF.STAFFPASSWORD"
                    );
                }
            }
        }
        $tableArray = null;
        // only filter what inside grid and  have relationship. multi relationship cannot
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('staff', array('budgettransfer' => 'bt'), 'financeyear', 'financeperiodrange');
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array('staff', 'budgettransfer' => 'bt', 'financeyear', 'financeperiodrange');
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array('STAFF', 'BUDGETTRANSFER' => 'bt', 'FINANCEYEAR', 'FINANCEPERIODRANGE');
                }
            }
        }

        $tempSql = null;
        if ($this->getFieldQuery()) {
            $this->q->setFieldQuery($this->getFieldQuery());
            if ($this->getVendor() == self::MYSQL) {

                //special
                $sql .= $this->q->quickSearch($tableArray, $filterArray);
                $sql .= " OR  (
                               (
                                SELECT `chartOfAccountNumber`
                                FROM   `chartofaccount`
                                WHERE  `companyId`= '" . $this->getCompanyId() . "'
                                AND    `chartOfAccountId`   =   `bt`.`budgetTransferFrom`
                                LIMIT 1
                                )  LIKE '%" . $this->getFieldQuery() . "%'
                              OR

                              (
                                SELECT `chartOfAccountTitle`
                                FROM   `chartofaccount`
                                WHERE  `companyId`= '" . $this->getCompanyId() . "'
                                AND    `chartOfAccountId`   =   `bt`.`budgetTransferFrom`
                                LIMIT 1
                                )  LIKE '%" . $this->getFieldQuery() . "%'
                              OR
                                    (
                                     SELECT `chartOfAccountNumber`
                                    FROM   `chartofaccount`
                                    WHERE  `companyId`= '" . $this->getCompanyId() . "'
                                    AND    `chartOfAccountId`   =   `bt`.`budgetTransferTo`
                                    LIMIT  1

                                    ) LIKE '%" . $this->getFieldQuery() . "%'
                                    OR
                                     (
                                     SELECT `chartOfAccountTitle`
                                    FROM   `chartofaccount`
                                    WHERE  `companyId`= '" . $this->getCompanyId() . "'
                                    AND    `chartOfAccountId`   =   `bt`.`budgetTransferTo`
                                    LIMIT  1

                                    ) LIKE '%" . $this->getFieldQuery() . "%'
                             )";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $tempSql = $this->q->quickSearch($tableArray, $filterArray);
                    $sql .= $tempSql;
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $tempSql = $this->q->quickSearch($tableArray, $filterArray);
                        $sql .= $tempSql;
                    }
                }
            }
        }
        $tempSql2 = null;
        if ($this->getGridQuery()) {
            $this->q->setGridQuery($this->getGridQuery());
            if ($this->getVendor() == self::MYSQL) {
                $sql .= $this->q->searching();
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $tempSql2 = $this->q->searching();
                    $sql .= $tempSql2;
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $tempSql2 = $this->q->searching();
                        $sql .= $tempSql2;
                    }
                }
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
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= "	ORDER BY [" . $this->getSortField() . "] " . $this->getOrder() . " ";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= "	ORDER BY " . strtoupper($this->getSortField()) . " " . strtoupper(
                                        $this->getOrder()
                                ) . " ";
                    }
                }
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
                
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    /**
                     * Sql Server  2012 format only.Row Number
                     * Parameter Query We don't support
                     **/
                    $sqlDerived = $sql . " 	OFFSET  	" . $this->getStart() . " ROWS
											FETCH NEXT 	" . $this->getLimit() . " ROWS ONLY ";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
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
                        echo json_encode(
                                array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])
                        );
                        exit();
                    }
                }
            }
        }
        /*
         *  Only Execute One Query
         */
        if (!($this->model->getBudgetTransferId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 21;
            if ($this->model->getBudgetTransferId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getBudgetTransferId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getBudgetTransferId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else {
            if ($this->getPageOutput() == 'json') {
                if ($this->model->getBudgetTransferId(0, 'single')) {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo str_replace(
                            array("[", "]"), "", json_encode(
                                    array(
                                        'success' => true,
                                        'total' => $total,
                                        'message' => $this->t['viewRecordMessageLabel'],
                                        'time' => $time,
                                        'firstRecord' => $this->firstRecord('value'),
                                        'previousRecord' => $this->previousRecord(
                                                'value', $this->model->getBudgetTransferId(0, 'single')
                                        ),
                                        'nextRecord' => $this->nextRecord(
                                                'value', $this->model->getBudgetTransferId(0, 'single')
                                        ),
                                        'lastRecord' => $this->lastRecord('value'),
                                        'data' => $items
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
                    echo json_encode(
                            array(
                                'success' => true,
                                'total' => $total,
                                'message' => $this->t['viewRecordMessageLabel'],
                                'time' => $time,
                                'firstRecord' => $this->recordSet->firstRecord('value'),
                                'previousRecord' => $this->recordSet->previousRecord(
                                        'value', $this->model->getBudgetTransferId(0, 'single')
                                ),
                                'nextRecord' => $this->recordSet->nextRecord(
                                        'value', $this->model->getBudgetTransferId(0, 'single')
                                ),
                                'lastRecord' => $this->recordSet->lastRecord('value'),
                                'data' => $items
                            )
                    );
                    exit();
                }
            }
        }
        return $items;
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
     * Last Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @return int
     * @throws \Exception
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
        if (!$this->model->getBudgetTransferFrom()) {
            $this->model->setBudgetTransferFrom($this->service->getBudgetTransferFromDefaultValue());
        }
        if (!$this->model->getBudgetTransferTo()) {
            $this->model->setBudgetTransferTo($this->service->getBudgetTransferToDefaultValue());
        }
        if (!$this->model->getFinanceYearId()) {
            $this->model->setFinanceYearId($this->service->getFinanceYearDefaultValue());
        }
        if (!$this->model->getFinancePeriodRangeId()) {
            $this->model->setFinancePeriodRangeId($this->service->getFinancePeriodRangeDefaultValue());
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`budgettransfer`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getBudgetTransferId(
                            0, 'single'
                    ) . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[budgetTransfer]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getBudgetTransferId(
                                0, 'single'
                        ) . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	BUDGETTRANSFER
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getBudgetTransferId(
                                    0, 'single'
                            ) . "' ";
                }
            }
        }
        $result = $this->q->fast($sql);
        $total = $this->q->numberRows($result, $sql);
        if ($total == 0) {
            echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel']));
            exit();
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
               UPDATE `budgettransfer` SET
                       `budgetTransferFrom` = '" . $this->model->getBudgetTransferFrom() . "',
                       `budgetTransferTo` = '" . $this->model->getBudgetTransferTo() . "',
                       `financeYearId` = '" . $this->model->getFinanceYearId() . "',
                       `financePeriodRangeId` = '" . $this->model->getFinancePeriodRangeId() . "',
                       `documentNumber` = '" . $this->model->getDocumentNumber() . "',
                       `budgetTransferDate` = '" . $this->model->getBudgetTransferDate() . "',
                       `budgetTransferAmount` = '" . $this->model->getBudgetTransferAmount() . "',
                       `budgetTransferComment` = '" . $this->model->getBudgetTransferComment() . "',
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
               WHERE    `budgetTransferId`='" . $this->model->getBudgetTransferId('0', 'single') . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                UPDATE [budgetTransfer] SET
                       [budgetTransferFrom] = '" . $this->model->getBudgetTransferFrom() . "',
                       [budgetTransferTo] = '" . $this->model->getBudgetTransferTo() . "',
                       [financeYearId] = '" . $this->model->getFinanceYearId() . "',
                       [financePeriodRangeId] = '" . $this->model->getFinancePeriodRangeId() . "',
                       [documentNumber] = '" . $this->model->getDocumentNumber() . "',
                       [budgetTransferDate] = '" . $this->model->getBudgetTransferDate() . "',
                       [budgetTransferAmount] = '" . $this->model->getBudgetTransferAmount() . "',
                       [budgetTransferComment] = '" . $this->model->getBudgetTransferComment() . "',
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
                WHERE   [budgetTransferId]='" . $this->model->getBudgetTransferId('0', 'single') . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
                UPDATE BUDGETTRANSFER SET
                        BUDGETTRANSFERFROM = '" . $this->model->getBudgetTransferFrom() . "',
                       BUDGETTRANSFERTO = '" . $this->model->getBudgetTransferTo() . "',
                       FINANCEYEARID = '" . $this->model->getFinanceYearId() . "',
                       FINANCEPERIODRANGEID = '" . $this->model->getFinancePeriodRangeId() . "',
                       DOCUMENTNUMBER = '" . $this->model->getDocumentNumber() . "',
                       BUDGETTRANSFERDATE = '" . $this->model->getBudgetTransferDate() . "',
                       BUDGETTRANSFERAMOUNT = '" . $this->model->getBudgetTransferAmount() . "',
                       BUDGETTRANSFERCOMMENT = '" . $this->model->getBudgetTransferComment() . "',
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
                WHERE  BUDGETTRANSFERID='" . $this->model->getBudgetTransferId('0', 'single') . "'";
                    }
                }
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
           FROM 	`budgettransfer`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getBudgetTransferId(
                            0, 'single'
                    ) . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[budgetTransfer]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getBudgetTransferId(
                                0, 'single'
                        ) . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	BUDGETTRANSFER
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getBudgetTransferId(
                                    0, 'single'
                            ) . "' ";
                }
            }
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
               UPDATE  `budgettransfer`
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
               WHERE   `budgetTransferId`   =  '" . $this->model->getBudgetTransferId(0, 'single') . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
               UPDATE  [budgetTransfer]
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
               WHERE   [budgetTransferId]	=  '" . $this->model->getBudgetTransferId(0, 'single') . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
               UPDATE  BUDGETTRANSFER
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
               WHERE   BUDGETTRANSFERID	=  '" . $this->model->getBudgetTransferId(0, 'single') . "'";
                    }
                }
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
     * Checking Current Financial Year
     * @return int
     */
    public function getFinanceYearId() {
        return $this->service->getFinanceYearId();
    }

    /**
     * Return Checking Odd Period or not
     * @return bool|int
     */
    public function getIsOddPeriod() {
        return $this->service->getIsOddPeriod();
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
           FROM    `budgettransfer`
           WHERE   `documentNumber` 	= 	'" . $this->model->getDocumentNumber() . "'
           AND     `isActive`  =   1
           AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT  [documentNumber]
           FROM    [budgetTransfer]
           WHERE   [documentNumber] = 	'" . $this->model->getDocumentNumber() . "'
           AND     [isActive]  =   1
           AND     [companyId] =	'" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
               SELECT  DOCUMENTNUMBER AS \"documentNumber\"
               FROM    BUDGETTRANSFER
               WHERE   DOCUMENTNUMBER	= 	'" . $this->model->getDocumentNumber() . "'
               AND     ISACTIVE    =   1
               AND     COMPANYID   =   '" . $this->getCompanyId() . "'";
                }
            }
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
                        "documentNumber" => $row ['documentNumber'],
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
     * Return Budget Transfer From
     * @return null|string
     */
    public function getBudgetTransferFrom() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getBudgetTransferFrom();
    }

    /**
     * Return Budget Transfer To
     * @return null|string
     */
    public function getBudgetTransferTo() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getBudgetTransferTo();
    }

    /**
     * Return Finance Year
     * @return null|string
     */
    public function getFinanceYear() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getFinanceYear();
    }

    /**
     * Return Finance Period Range
     * @return null|string
     */
    public function getFinancePeriodRange() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getFinancePeriodRange($this->model->getFinanceYearId());
    }

    /**
     * Post Button
     */
    public function posting() {
        $this->service->postBudgetTransfer($this->model->getBudgetTransferId(0, 'single'));
    }

    /**
     * Reverse Button
     */
    public function reverse() {
        $this->service->reverseBudgetTransfer($this->model->getBudgetTransferId(0, 'single'));
    }

    /**
     * Return Single Budget Amount
     * Return json
     * @return void
     */
    public function getBudgetAmount() {
        header('Content-Type:application/json; charset=utf-8');
        $chartOfAccountId = null;
        if ($this->model->getBudgetTransferFrom()) {
            $chartOfAccountId = $this->model->getBudgetTransferFrom();
        } else if ($this->model->getBudgetTransferTo()) {
            $chartOfAccountId = $this->model->getBudgetTransferTo();
        } else {
            if ($this->model->getChartOfAccountId()) {
                $chartOfAccountId = $this->model->getChartOfAccountId();
            }
        }
        $budgetAmount = $this->service->getBudgetAmount(
                $chartOfAccountId, $this->model->getFinanceYearId(), $this->model->getFinancePeriodRangeId()
        );
        echo json_encode(array("success" => true, "message" => "complete", "budgetAmount" => $budgetAmount));
        exit();
    }

    /**
     * Return Array Budget Amount Base,Mini Statement,Balance Budget On Current Financial Year
     * Return json
     * @return void
     */
    public function getBudgetAmountByYear() {
        header('Content-Type:application/json; charset=utf-8');
        $chartOfAccountId = null;
        if ($this->model->getBudgetTransferFrom()) {
            $chartOfAccountId = $this->model->getBudgetTransferFrom();
        } else if ($this->model->getBudgetTransferTo()) {
            $chartOfAccountId = $this->model->getBudgetTransferTo();
        } else if ($this->model->getChartOfAccountId()) {
            $chartOfAccountId = $this->model->getChartOfAccountId();
        }
        $data = $this->service->getBudgetAmountByYear($chartOfAccountId, $this->model->getFinanceYearId());
        $miniStatement = $this->service->getMiniStatement($chartOfAccountId, $this->model->getFinanceYearId());
        $balanceBudget = $this->service->getBalanceBudget($chartOfAccountId, $this->model->getFinanceYearId());
        if ($data === null) {
            $data = '';
        }
        echo json_encode(
                array(
                    "success" => true,
                    "message" => "complete",
                    "data" => $data,
                    "miniStatement" => $miniStatement,
                    "balanceBudget" => $balanceBudget
                )
        );
        exit();
    }

    /**
     * Return Single Budget Amount
     * Return json
     * @return void
     */
    public function getBudgetAmountByPeriod() {
        header('Content-Type:application/json; charset=utf-8');
        $chartOfAccountId = null;
        if ($this->model->getBudgetTransferFrom()) {
            $chartOfAccountId = $this->model->getBudgetTransferFrom();
        } else if ($this->model->getBudgetTransferTo()) {
            $chartOfAccountId = $this->model->getBudgetTransferTo();
        } else if ($this->model->getChartOfAccountId()) {
            $chartOfAccountId = $this->model->getChartOfAccountId();
        }
        $budgetAmount = $this->service->getBudgetAmountByPeriod(
                $chartOfAccountId, $this->model->getFinanceYearId(), $this->model->getFinancePeriodRangeId()
        );
        echo json_encode(array("success" => true, "message" => "complete", "budgetAmount" => $budgetAmount));
        exit();
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
                ->setSubject('budgetTransfer')
                ->setDescription('Generated by PhpExcel an Idcms Generator')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('financial/generalLedger');
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
        $this->excel->getActiveSheet()->setCellValue('B2', $this->getReportTitle());
        $this->excel->getActiveSheet()->setCellValue('L2', '');
        $this->excel->getActiveSheet()->mergeCells('B2:L2');
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.');
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['budgetTransferFromLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['budgetTransferToLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['financeYearIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['financePeriodRangeIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['documentNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['budgetTransferDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['budgetTransferAmountLabel']);
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['budgetTransferCommentLabel']);
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['executeByLabel']);
        $this->excel->getActiveSheet()->setCellValue('L3', $this->translate['executeTimeLabel']);
        //
        $loopRow = 4;
        $i = 0;
        \PHPExcel_Cell::setValueBinder(new \PHPExcel_Cell_AdvancedValueBinder());
        $lastRow = null;
        while (($row = $this->q->fetchAssoc()) == true) {
            //	echo print_r($row);
            $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i);
            $this->excel->getActiveSheet()->setCellValue('C' . $loopRow, strip_tags($row ['budgetTransferFrom']));
            $this->excel->getActiveSheet()->setCellValue('D' . $loopRow, strip_tags($row ['budgetTransferTo']));
            $this->excel->getActiveSheet()->setCellValue('E' . $loopRow, strip_tags($row ['financeYearDescription']));
            $this->excel->getActiveSheet()->setCellValue(
                    'F' . $loopRow, strip_tags($row ['financePeriodRangeDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, strip_tags($row ['documentNumber']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('H' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $this->excel->getActiveSheet()->setCellValue('H' . $loopRow, strip_tags($row ['budgetTransferDate']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('I' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('I' . $loopRow, strip_tags($row ['budgetTransferAmount']));
            $this->excel->getActiveSheet()->setCellValue('J' . $loopRow, strip_tags($row ['budgetTransferComment']));
            $this->excel->getActiveSheet()->setCellValue('K' . $loopRow, strip_tags($row ['staffName']));
            $this->excel->getActiveSheet()->setCellValue('L' . $loopRow, strip_tags($row ['executeTime']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('L' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $loopRow++;
            $lastRow = 'L' . $loopRow;
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
                $filename = "budgetTransfer" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/generalLedger/document/" . $folder . "/" . $filename;
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
                $filename = "budgetTransfer" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/generalLedger/document/" . $folder . "/" . $filename;
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
                $filename = "budgetTransfer" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/generalLedger/document/" . $folder . "/" . $filename;
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
                $filename = "budgetTransfer" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/generalLedger/document/" . $folder . "/" . $filename;
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
        $budgetTransferObject = new BudgetTransferClass ();
        if ($_POST['securityToken'] != $budgetTransferObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value
         */
        if (isset($_POST ['leafId'])) {
            $budgetTransferObject->setLeafId($_POST ['leafId']);
        }
        if (isset($_POST ['offset'])) {
            $budgetTransferObject->setStart($_POST ['offset']);
        }
        if (isset($_POST ['limit'])) {
            $budgetTransferObject->setLimit($_POST ['limit']);
        }
        $budgetTransferObject->setPageOutput($_POST['output']);
        $budgetTransferObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy)
         */
        if ($_POST ['method'] == 'create') {
            $budgetTransferObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $budgetTransferObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $budgetTransferObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $budgetTransferObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            $budgetTransferObject->posting();
        }
        if ($_POST ['method'] == 'reverse') {
            $budgetTransferObject->reverse();
        }
    }
}
if (isset($_GET ['method'])) {
    $budgetTransferObject = new BudgetTransferClass ();
    if ($_GET['securityToken'] != $budgetTransferObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $budgetTransferObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $budgetTransferObject->execute();
    /*
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'updateStatus') {
        $budgetTransferObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key
     */
    if ($_GET['method'] == 'duplicate') {
        $budgetTransferObject->duplicate();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $budgetTransferObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $budgetTransferObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $budgetTransferObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $budgetTransferObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting
     */
    if (isset($_GET ['mode'])) {
        $budgetTransferObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $budgetTransferObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        $budgetTransferObject->setServiceOutput('option');
        if (($_GET['filter'] == 'budgetTransferFrom')) {
            $budgetTransferObject->getBudgetTransferFrom();
        }
        if (($_GET['filter'] == 'budgetTransferTo')) {
            $budgetTransferObject->getBudgetTransferTo();
        }
        if (($_GET['filter'] == 'financeYear')) {
            $budgetTransferObject->getFinanceYear();
        }
        if (($_GET['filter'] == 'financePeriodRange')) {
            $budgetTransferObject->getFinancePeriodRange();
        }
        // filtering budget
        if (($_GET['filter'] == 'budgetAmount')) {
            $budgetTransferObject->getBudgetAmount();
        }
        if (($_GET['filter'] == 'budgetAmountByYear')) {

            $budgetTransferObject->getBudgetAmountByYear();
        }
        if (($_GET['filter'] == 'budgetAmountByPeriod')) {
            $budgetTransferObject->getBudgetAmountByPeriod();
        }
    }
}
?>
