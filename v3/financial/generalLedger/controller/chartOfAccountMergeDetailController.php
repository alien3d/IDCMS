<?php

namespace Core\Financial\GeneralLedger\ChartOfAccountMergeDetail\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\Financial\GeneralLedger\ChartOfAccountMergeDetail\Model\ChartOfAccountMergeDetailModel;
use Core\Financial\GeneralLedger\ChartOfAccountMergeDetail\Service\ChartOfAccountMergeDetailService;
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
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/model/chartOfAccountMergeDetailModel.php");
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/service/chartOfAccountMergeDetailService.php");

/**
 * Class ChartOfAccountMergeDetail
 * this is chartOfAccountMergeDetail controller files.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package  Core\Financial\GeneralLedger\ChartOfAccountMergeDetail\Controller
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ChartOfAccountMergeDetailClass extends ConfigClass {

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
     * @var \Core\Financial\GeneralLedger\ChartOfAccountMergeDetail\Model\ChartOfAccountMergeDetailModel
     */
    public $model;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     * @var \PHPPowerPoint
     */
    //private $powerPoint;
    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\Financial\GeneralLedger\ChartOfAccountMergeDetail\Service\ChartOfAccountMergeDetailService
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
        $this->setViewPath("./v3/financial/generalLedger/view/chartOfAccountMergeDetail.php");
        $this->setControllerPath(
                "./v3/financial/generalLedger/controller/chartOfAccountMergeDetailController.php"
        );
        $this->setServicePath("./v3/financial/generalLedger/service/chartOfAccountMergeDetailService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new ChartOfAccountMergeDetailModel();
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

        $this->setReportTitle(
                $applicationNative . " :: " . $moduleNative . " :: " . $folderNative . " :: " . $leafNative
        );

        $this->service = new ChartOfAccountMergeDetailService();
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
        if (!$this->model->getChartOfAccountMergeId()) {
            $this->model->setChartOfAccountMergeId($this->service->getChartOfAccountMergeDefaultValue());
        }
        if (!$this->model->getChartOfAccountId()) {
            $this->model->setChartOfAccountId($this->service->getChartOfAccountDefaultValue());
        }

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `chartofaccountmergedetail` 
            (
                 `companyId`,
                 `chartOfAccountMergeId`,
                 `chartOfAccountId`,
                 `journalNumber`,
                 `chartOfAccountMergeDetailAmount`,
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
                 '" . $this->model->getChartOfAccountMergeId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getJournalNumber() . "',
                 '" . $this->model->getChartOfAccountMergeDetailAmount() . "',
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
            INSERT INTO [chartOfAccountMergeDetail]
            (
                 [chartOfAccountMergeDetailId],
                 [companyId],
                 [chartOfAccountMergeId],
                 [chartOfAccountId],
                 [journalNumber],
                 [chartOfAccountMergeDetailAmount],
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
                 '" . $this->model->getChartOfAccountMergeId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getJournalNumber() . "',
                 '" . $this->model->getChartOfAccountMergeDetailAmount() . "',
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
            INSERT INTO CHARTOFACCOUNTMERGEDETAIL
            (
                 COMPANYID,
                 CHARTOFACCOUNTMERGEID,
                 CHARTOFACCOUNTID,
                 JOURNALNUMBER,
                 CHARTOFACCOUNTMERGEDETAILAMOUNT,
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
                 '" . $this->model->getChartOfAccountMergeId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getJournalNumber() . "',
                 '" . $this->model->getChartOfAccountMergeDetailAmount() . "',
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
        $chartOfAccountMergeDetailId = $this->q->lastInsertId();
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
                    "chartOfAccountMergeDetailId" => $chartOfAccountMergeDetailId,
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
         FROM    `chartofaccountmergedetail`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
            $sql .= "AND     `chartOfAccountMergeId` = " . $this->model->getChartOfAccountMergeId() . " ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT    COUNT(*) AS total
         FROM      [chartOfAccountMergeDetail]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
                $sql .= "AND     [chartOfAccountMergeId] = " . $this->model->getChartOfAccountMergeId() . " ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT    COUNT(*)    AS  \"total\"
         FROM      CHARTOFACCOUNTMERGEDETAIL
         WHERE     ISACTIVE    =   1
         AND       COMPANYID   =   " . $this->getCompanyId() . " ";
                    $sql .= "AND     CHARTOFACCOUNTMERGEID = " . $this->model->getChartOfAccountMergeId() . " ";
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
                            " `chartofaccountmergedetail`.`isActive` = 1  AND `chartofaccountmergedetail`.`companyId`='" . $this->getCompanyId(
                            ) . "' "
                    );
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [chartOfAccountMergeDetail].[isActive] = 1 AND [chartOfAccountMergeDetail].[companyId]='" . $this->getCompanyId(
                                ) . "' "
                        );
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(
                                    " CHARTOFACCOUNTMERGEDETAIL.ISACTIVE = 1  AND CHARTOFACCOUNTMERGEDETAIL.COMPANYID='" . $this->getCompanyId(
                                    ) . "'"
                            );
                        }
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter(
                                "   `chartofaccountmergedetail`.`companyId`='" . $this->getCompanyId() . "'	"
                        );
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(
                                    " [chartOfAccountMergeDetail].[companyId]='" . $this->getCompanyId() . "' "
                            );
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(
                                        " CHARTOFACCOUNTMERGEDETAIL.COMPANYID='" . $this->getCompanyId() . "' "
                                );
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
       SELECT                    `chartofaccountmergedetail`.`chartOfAccountMergeDetailId`,
                    `company`.`companyDescription`,
                    `chartofaccountmergedetail`.`companyId`,
                    `chartofaccountmerge`.`chartOfAccountMergeDescription`,
                    `chartofaccountmergedetail`.`chartOfAccountMergeId`,
                    `chartofaccount`.`chartOfAccountDescription`,
                    `chartofaccountmergedetail`.`chartOfAccountId`,
                    `chartofaccountmergedetail`.`journalNumber`,
                    `chartofaccountmergedetail`.`chartOfAccountMergeDetailAmount`,
                    `chartofaccountmergedetail`.`isDefault`,
                    `chartofaccountmergedetail`.`isNew`,
                    `chartofaccountmergedetail`.`isDraft`,
                    `chartofaccountmergedetail`.`isUpdate`,
                    `chartofaccountmergedetail`.`isDelete`,
                    `chartofaccountmergedetail`.`isActive`,
                    `chartofaccountmergedetail`.`isApproved`,
                    `chartofaccountmergedetail`.`isReview`,
                    `chartofaccountmergedetail`.`isPost`,
                    `chartofaccountmergedetail`.`executeBy`,
                    `chartofaccountmergedetail`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `chartofaccountmergedetail`
		  JOIN      `staff`
		  ON        `chartofaccountmergedetail`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `chartofaccountmergedetail`.`companyId`
	JOIN	`chartofaccountmerge`
	ON		`chartofaccountmerge`.`chartOfAccountMergeId` = `chartofaccountmergedetail`.`chartOfAccountMergeId`
	JOIN	`chartofaccount`
	ON		`chartofaccount`.`chartOfAccountId` = `chartofaccountmergedetail`.`chartOfAccountId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getChartOfAccountMergeDetailId(0, 'single')) {
                $sql .= " AND `chartofaccountmergedetail`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getChartOfAccountMergeDetailId(0, 'single') . "'";
            }
            if ($this->model->getChartOfAccountMergeId()) {
                $sql .= " AND `chartofaccountmergedetail`.`chartOfAccountMergeId`='" . $this->model->getChartOfAccountMergeId(
                        ) . "'";
            }
            if ($this->model->getChartOfAccountId()) {
                $sql .= " AND `chartofaccountmergedetail`.`chartOfAccountId`='" . $this->model->getChartOfAccountId(
                        ) . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
		  SELECT                    [chartOfAccountMergeDetail].[chartOfAccountMergeDetailId],
                    [company].[companyDescription],
                    [chartOfAccountMergeDetail].[companyId],
                    [chartOfAccountMerge].[chartOfAccountMergeDescription],
                    [chartOfAccountMergeDetail].[chartOfAccountMergeId],
                    [chartOfAccount].[chartOfAccountDescription],
                    [chartOfAccountMergeDetail].[chartOfAccountId],
                    [chartOfAccountMergeDetail].[journalNumber],
                    [chartOfAccountMergeDetail].[chartOfAccountMergeDetailAmount],
                    [chartOfAccountMergeDetail].[isDefault],
                    [chartOfAccountMergeDetail].[isNew],
                    [chartOfAccountMergeDetail].[isDraft],
                    [chartOfAccountMergeDetail].[isUpdate],
                    [chartOfAccountMergeDetail].[isDelete],
                    [chartOfAccountMergeDetail].[isActive],
                    [chartOfAccountMergeDetail].[isApproved],
                    [chartOfAccountMergeDetail].[isReview],
                    [chartOfAccountMergeDetail].[isPost],
                    [chartOfAccountMergeDetail].[executeBy],
                    [chartOfAccountMergeDetail].[executeTime],
                    [staff].[staffName]
		  FROM 	[chartOfAccountMergeDetail]
		  JOIN	[staff]
		  ON	[chartOfAccountMergeDetail].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [chartOfAccountMergeDetail].[companyId]
	JOIN	[chartOfAccountMerge]
	ON		[chartOfAccountMerge].[chartOfAccountMergeId] = [chartOfAccountMergeDetail].[chartOfAccountMergeId]
	JOIN	[chartOfAccount]
	ON		[chartOfAccount].[chartOfAccountId] = [chartOfAccountMergeDetail].[chartOfAccountId]
		  WHERE     " . $this->getAuditFilter();
                if ($this->model->getChartOfAccountMergeDetailId(0, 'single')) {
                    $sql .= " AND [chartOfAccountMergeDetail].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getChartOfAccountMergeDetailId(0, 'single') . "'";
                }
                if ($this->model->getChartOfAccountMergeId()) {
                    $sql .= " AND [chartOfAccountMergeDetail].[chartOfAccountMergeId]='" . $this->model->getChartOfAccountMergeId(
                            ) . "'";
                }
                if ($this->model->getChartOfAccountId()) {
                    $sql .= " AND [chartOfAccountMergeDetail].[chartOfAccountId]='" . $this->model->getChartOfAccountId(
                            ) . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
		  SELECT                    CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTMERGEDETAILID AS \"chartOfAccountMergeDetailId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    CHARTOFACCOUNTMERGEDETAIL.COMPANYID AS \"companyId\",
                    CHARTOFACCOUNTMERGE.CHARTOFACCOUNTMERGEDESCRIPTION AS  \"chartOfAccountMergeDescription\",
                    CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTMERGEID AS \"chartOfAccountMergeId\",
                    CHARTOFACCOUNT.CHARTOFACCOUNTDESCRIPTION AS  \"chartOfAccountDescription\",
                    CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTID AS \"chartOfAccountId\",
                    CHARTOFACCOUNTMERGEDETAIL.JOURNALNUMBER AS \"journalNumber\",
                    CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTMERGEDETAILAMOUNT AS \"chartOfAccountMergeDetailAmount\",
                    CHARTOFACCOUNTMERGEDETAIL.ISDEFAULT AS \"isDefault\",
                    CHARTOFACCOUNTMERGEDETAIL.ISNEW AS \"isNew\",
                    CHARTOFACCOUNTMERGEDETAIL.ISDRAFT AS \"isDraft\",
                    CHARTOFACCOUNTMERGEDETAIL.ISUPDATE AS \"isUpdate\",
                    CHARTOFACCOUNTMERGEDETAIL.ISDELETE AS \"isDelete\",
                    CHARTOFACCOUNTMERGEDETAIL.ISACTIVE AS \"isActive\",
                    CHARTOFACCOUNTMERGEDETAIL.ISAPPROVED AS \"isApproved\",
                    CHARTOFACCOUNTMERGEDETAIL.ISREVIEW AS \"isReview\",
                    CHARTOFACCOUNTMERGEDETAIL.ISPOST AS \"isPost\",
                    CHARTOFACCOUNTMERGEDETAIL.EXECUTEBY AS \"executeBy\",
                    CHARTOFACCOUNTMERGEDETAIL.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\"
		  FROM 	CHARTOFACCOUNTMERGEDETAIL
		  JOIN	STAFF
		  ON	CHARTOFACCOUNTMERGEDETAIL.EXECUTEBY = STAFF.STAFFID
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = CHARTOFACCOUNTMERGEDETAIL.COMPANYID
	JOIN	CHARTOFACCOUNTMERGE
	ON		CHARTOFACCOUNTMERGE.CHARTOFACCOUNTMERGEID = CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTMERGEID
	JOIN	CHARTOFACCOUNT
	ON		CHARTOFACCOUNT.CHARTOFACCOUNTID = CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTID
         WHERE     " . $this->getAuditFilter();
                    if ($this->model->getChartOfAccountMergeDetailId(0, 'single')) {
                        $sql .= " AND CHARTOFACCOUNTMERGEDETAIL. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getChartOfAccountMergeDetailId(0, 'single') . "'";
                    }
                    if ($this->model->getChartOfAccountMergeId()) {
                        $sql .= " AND CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTMERGEID='" . $this->model->getChartOfAccountMergeId(
                                ) . "'";
                    }
                    if ($this->model->getChartOfAccountId()) {
                        $sql .= " AND CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTID='" . $this->model->getChartOfAccountId(
                                ) . "'";
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
                $sql .= " AND `chartofaccountmergedetail`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [chartOfAccountMergeDetail].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND Initcap(CHARTOFACCOUNTMERGEDETAIL." . strtoupper(
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
                        'chartofaccountmergedetail', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'chartOfAccountMergeDetail', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'CHARTOFACCOUNTMERGEDETAIL', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
                "`chartofaccountmergedetail`.`chartOfAccountMergeDetailId`",
                "`staff`.`staffPassword`"
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $filterArray = array(
                    "[chartOfAccountMergeDetail].[chartOfAccountMergeDetailId]",
                    "[staff].[staffPassword]"
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $filterArray = array(
                        "CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTMERGEDETAILID",
                        "STAFF.STAFFPASSWORD"
                    );
                }
            }
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('staff', 'chartofaccountmergedetail', 'chartofaccountmerge', 'chartofaccount');
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array('staff', 'chartofaccountmergedetail', 'chartofaccountmerge', 'chartofaccount');
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array('STAFF', 'CHARTOFACCOUNTMERGEDETAIL', 'CHARTOFACCOUNTMERGE', 'CHARTOFACCOUNT');
                }
            }
        }
        $tempSql = null;
        if ($this->getFieldQuery()) {
            $this->q->setFieldQuery($this->getFieldQuery());
            if ($this->getVendor() == self::MYSQL) {
                $sql .= $this->q->quickSearch($tableArray, $filterArray);
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
        if (!($this->model->getChartOfAccountMergeDetailId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 17;
            if ($this->model->getChartOfAccountMergeDetailId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord(
                        'value', $this->model->getChartOfAccountMergeDetailId(0, 'single')
                );
                $row['nextRecord'] = $this->nextRecord(
                        'value', $this->model->getChartOfAccountMergeDetailId(0, 'single')
                );
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else {
            if ($this->getPageOutput() == 'table') {
                $this->setService('html');
                $str = null;
                if (is_array($items)) {
                    $this->setServiceOutput('html');
                    $totalRecordDetail = intval(count($items));
                    if ($totalRecordDetail > 0) {
                        $counter = 0;
                        $totalDebit = 0;
                        $totalCredit = 0;
                        for ($j = 0; $j < $totalRecordDetail; $j++) {
                            $counter++;

                            $str .= "<tr id='" . $items[$j]['chartOfAccountMergeDetailId'] . "'>";
                            $str .= "<td vAlign=\"center\"><div align=\"center\">" . ($counter) . "</div>
		</td>";
                            $str .= "<td><div class='btn-group'>";
                            $str .= "<input type=\"hidden\" name='chartOfAccountMergeDetailId[]'     id='chartOfAccountMergeDetailId" . $items[$j]['chartOfAccountMergeDetailId'] . "'  value='" . $items[$j]['chartOfAccountMergeDetailId'] . "'>";
                            $str .= "<input type=\"hidden\" name='chartOfAccountMergeId[]'
                    id='chartOfAccountMergeDetailId" . $items[$j]['chartOfAccountMergeId'] . "'
                        value='" . $items[$j]['chartOfAccountMergeId'] . "'>";
                            $str .= "<a class=' btn-warning btn-xs' title='Edit' onClick=showFormUpdateDetail('" . $this->getLeafId(
                                    ) . "','" . $this->getControllerPath() . "','" . $this->getSecurityToken(
                                    ) . "','" . $items[$j]['chartOfAccountMergeDetailId'] . "')><i class='glyphicon glyphicon-edit glyphicon-white'></i></a>";
                            $str .= "<a class=' btn-danger btn-xs' title='Delete' onClick=showModalDeleteDetail('" . $items[$j]['chartOfAccountMergeDetailId'] . "')><i class='glyphicontrash  glyphicon-white'></i></a><div id=miniInfoPanel" . $items[$j]['chartOfAccountMergeDetailId'] . "></div></td>";
                            $chartOfAccountArray = $this->getChartOfAccount();
                            $str .= "<td><div class='form-group col-md-12' id='chartOfAccountId" . $items[$j]['chartOfAccountMergeDetailId'] . "Detail'>";
                            $str .= "<div class='input-group'><select name='chartOfAccountId[]' id='chartOfAccountId" . $items[$j]['chartOfAccountMergeDetailId'] . "' class='chzn-select' onChange=removeMeErrorDetail('chartOfAccountId" . $items[$j]['chartOfAccountMergeDetailId'] . "') >";
                            $str .= "<option value=''>" . $this->t['pleaseSelectTextLabel'] . "</option>";
                            if (is_array($chartOfAccountArray)) {
                                $totalRecord = intval(count($chartOfAccountArray));
                                $d = 0;
                                $currentChartOfAccountTypeDescription = null;
                                if ($totalRecord > 0) {

                                    for ($i = 0; $i < $totalRecord; $i++) {
                                        $d++;
                                        if ($i != 0) {
                                            if ($currentChartOfAccountTypeDescription != $chartOfAccountArray[$i]['chartOfAccountTypeDescription']) {
                                                $str .= "</optgroup><optgroup label=\"" . $chartOfAccountArray[$i]['chartOfAccountTypeDescription'] . "\">";
                                            }
                                        } else {
                                            $str .= "<optgroup label=\"" . $chartOfAccountArray[$i]['chartOfAccountTypeDescription'] . "\">";
                                        }
                                        $currentChartOfAccountTypeDescription = $chartOfAccountArray[$i]['chartOfAccountTypeDescription'];

                                        if ($items[$j]['chartOfAccountId'] == $chartOfAccountArray[$i]['chartOfAccountId']) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = null;
                                        }
                                        $str .= "<option value='" . $chartOfAccountArray[$i]['chartOfAccountId'] . "' " . $selected . ">" . $chartOfAccountArray[$i]['chartOfAccountNumber'] . " - " . $chartOfAccountArray[$i]['chartOfAccountTitle'] . "</option>";
                                    }
                                    $str .= "</optgroup>";
                                } else {
                                    $str .= "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
                                }
                            } else {
                                $str .= "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
                            }
                            $str .= "</select></div></div>";
                            $str .= "</td>";
                            $str .= "<td><input class='form-control'  type='text' name='chartOfAccountMergeDetailAmount[]' id='chartOfAccountMergeDetailAmount" . $items[$j]['chartOfAccountMergeDetailId'] . "'   value='" . $items[$j]['chartOfAccountMergeDetailAmount'] . "'></td>";
                            $debit = 0;
                            $credit = 0;
                            $x = 0;
                            $y = 0;
                            $d = $items[$j]['chartOfAccountMergeDetailAmount'];
                            if ($d > 0) {
                                $x = $d;
                            } else {
                                $y = $d;
                            }
                            if (class_exists('NumberFormatter')) {
                                if ($this->systemFormatArray['languageCode'] != '') {
                                    $a = new \NumberFormatter(
                                            $this->systemFormatArray['languageCode'], \NumberFormatter::CURRENCY
                                    );
                                    if ($d > 0) {
                                        $debit = $a->format($d);
                                    } else {
                                        $credit = $a->format($d);
                                    }
                                } else {
                                    if ($d > 0) {
                                        $debit = number_format($d) . " You can assign Currency Format ";
                                    } else {
                                        $credit = number_format($d) . " You can assign Currency Format ";
                                    }
                                }
                            } else {
                                if ($d > 0) {
                                    $debit = number_format($d);
                                } else {
                                    $credit = number_format($d);
                                }
                            }
                            $totalDebit += $x;
                            $totalCredit += $y;

                            $str .= "<td><div id=\"debit_" . $items[$j]['chartOfAccountMergeDetailId'] . "\" align=\"right\">" . $debit . "</div></td>";
                            $str .= "<td><div id=\"credit_" . $items[$j]['chartOfAccountMergeDetailId'] . "\" align=\"right\">" . $credit . "</div></td>\n";
                            $str .= "</tr>";
                        }
                        if ($totalDebit == abs($totalCredit)) {
                            $balanceColor = 'success';
                        } else {
                            $balanceColor = 'warning';
                        }
                        if (class_exists('NumberFormatter')) {
                            if ($this->systemFormatArray['languageCode'] != '') {
                                $a = new \NumberFormatter(
                                        $this->systemFormatArray['languageCode'], \NumberFormatter::CURRENCY
                                );
                                $totalDebit = $a->format($totalDebit);
                                $totalCredit = $a->format($totalCredit);
                            } else {
                                $totalDebit = number_format($totalDebit) . " You can assign Currency Format ";
                                $totalCredit = number_format($totalCredit) . " You can assign Currency Format ";
                            }
                        } else {
                            $totalDebit = number_format($totalDebit);
                            $totalCredit = number_format($totalCredit);
                        }
                        $str .= "<tr class=\"" . $balanceColor . "\">";
                        $str .= "<td colspan=\"4\">&nbsp;</td>";
                        $str .= "<td align=\"right\"><div id=\"totalDebit\" align=\"right\">" . $totalDebit . "</div></td>";
                        $str .= "<td align=\"right\"><div id=\"totalCredit\" align=\"right\">" . $totalCredit . "</div></td></tr>";
                    } else {
                        $str .= "<tr>";
                        $str .= "<td colspan=\"6\" vAlign=\"top\" align=\"center\">" . $this->exceptionMessageReturn(
                                        $this->t['recordNotFoundLabel']
                                ) . "</td>";
                        $str .= "</tr>";
                    }
                } else {
                    $str .= "<tr>";
                    $str .= "<td colspan=\"6\" vAlign=\"top\" align=\"center\">" . $this->exceptionMessageReturn(
                                    $this->t['recordNotFoundLabel']
                            ) . "</td>";
                    $str .= "</tr>";
                }
                echo json_encode(array('success' => true, 'tableData' => $str));
                exit();
            } else {
                if ($this->getPageOutput() == 'json') {
                    if ($this->model->getChartOfAccountMergeDetailId(0, 'single')) {
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
                                                    'value', $this->model->getChartOfAccountMergeDetailId(0, 'single')
                                            ),
                                            'nextRecord' => $this->nextRecord(
                                                    'value', $this->model->getChartOfAccountMergeDetailId(0, 'single')
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
                                            'value', $this->model->getChartOfAccountMergeDetailId(0, 'single')
                                    ),
                                    'nextRecord' => $this->recordSet->nextRecord(
                                            'value', $this->model->getChartOfAccountMergeDetailId(0, 'single')
                                    ),
                                    'lastRecord' => $this->recordSet->lastRecord('value'),
                                    'data' => $items
                                )
                        );
                        exit();
                    }
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
     * Set Service
     * @param string $service . Reset service either option,html,table
     * @return mixed
     */
    function setService($service) {
        return $this->service->setServiceOutput($service);
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
        if (!$this->model->getChartOfAccountMergeId()) {
            $this->model->setChartOfAccountMergeId($this->service->getChartOfAccountMergeDefaultValue());
        }
        if (!$this->model->getChartOfAccountId()) {
            $this->model->setChartOfAccountId($this->service->getChartOfAccountDefaultValue());
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`chartofaccountmergedetail`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getChartOfAccountMergeDetailId(
                            0, 'single'
                    ) . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[chartOfAccountMergeDetail]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getChartOfAccountMergeDetailId(
                                0, 'single'
                        ) . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	CHARTOFACCOUNTMERGEDETAIL
           WHERE  	" . strtoupper(
                                    $this->model->getPrimaryKeyName()
                            ) . " = '" . $this->model->getChartOfAccountMergeDetailId(0, 'single') . "' ";
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
               UPDATE `chartofaccountmergedetail` SET
                       `chartOfAccountMergeId` = '" . $this->model->getChartOfAccountMergeId() . "',
                       `chartOfAccountId` = '" . $this->model->getChartOfAccountId() . "',
                       `journalNumber` = '" . $this->model->getJournalNumber() . "',
                       `chartOfAccountMergeDetailAmount` = '" . $this->model->getChartOfAccountMergeDetailAmount() . "',
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
               WHERE    `chartOfAccountMergeDetailId`='" . $this->model->getChartOfAccountMergeDetailId(
                                '0', 'single'
                        ) . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                UPDATE [chartOfAccountMergeDetail] SET
                       [chartOfAccountMergeId] = '" . $this->model->getChartOfAccountMergeId() . "',
                       [chartOfAccountId] = '" . $this->model->getChartOfAccountId() . "',
                       [journalNumber] = '" . $this->model->getJournalNumber() . "',
                       [chartOfAccountMergeDetailAmount] = '" . $this->model->getChartOfAccountMergeDetailAmount() . "',
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
                WHERE   [chartOfAccountMergeDetailId]='" . $this->model->getChartOfAccountMergeDetailId(
                                    '0', 'single'
                            ) . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
                UPDATE CHARTOFACCOUNTMERGEDETAIL SET
                        CHARTOFACCOUNTMERGEID = '" . $this->model->getChartOfAccountMergeId() . "',
                       CHARTOFACCOUNTID = '" . $this->model->getChartOfAccountId() . "',
                       JOURNALNUMBER = '" . $this->model->getJournalNumber() . "',
                       CHARTOFACCOUNTMERGEDETAILAMOUNT = '" . $this->model->getChartOfAccountMergeDetailAmount() . "',
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
                WHERE  CHARTOFACCOUNTMERGEDETAILID='" . $this->model->getChartOfAccountMergeDetailId(
                                        '0', 'single'
                                ) . "'";
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
           FROM 	`chartofaccountmergedetail`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getChartOfAccountMergeDetailId(
                            0, 'single'
                    ) . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[chartOfAccountMergeDetail]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getChartOfAccountMergeDetailId(
                                0, 'single'
                        ) . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	CHARTOFACCOUNTMERGEDETAIL
           WHERE  	" . strtoupper(
                                    $this->model->getPrimaryKeyName()
                            ) . " = '" . $this->model->getChartOfAccountMergeDetailId(0, 'single') . "' ";
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
               UPDATE  `chartofaccountmergedetail`
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
               WHERE   `chartOfAccountMergeDetailId`   =  '" . $this->model->getChartOfAccountMergeDetailId(
                                0, 'single'
                        ) . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
               UPDATE  [chartOfAccountMergeDetail]
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
               WHERE   [chartOfAccountMergeDetailId]	=  '" . $this->model->getChartOfAccountMergeDetailId(
                                    0, 'single'
                            ) . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
               UPDATE  CHARTOFACCOUNTMERGEDETAIL
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
               WHERE   CHARTOFACCOUNTMERGEDETAILID	=  '" . $this->model->getChartOfAccountMergeDetailId(
                                        0, 'single'
                                ) . "'";
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
     * Return  ChartOfAccountMerge
     * @return null|string
     */
    public function getChartOfAccountMerge() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getChartOfAccountMerge();
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
                ->setSubject('chartOfAccountMergeDetail')
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
        $this->excel->getActiveSheet()->setCellValue('B2', $this->getReportTitle());
        $this->excel->getActiveSheet()->setCellValue('H2', '');
        $this->excel->getActiveSheet()->mergeCells('B2:H2');
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.');
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['chartOfAccountMergeIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['chartOfAccountIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['journalNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['chartOfAccountMergeDetailAmountLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['executeByLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['executeTimeLabel']);
        //
        $loopRow = 4;
        $i = 0;
        \PHPExcel_Cell::setValueBinder(new \PHPExcel_Cell_AdvancedValueBinder());
        $lastRow = null;
        while (($row = $this->q->fetchAssoc()) == true) {
            //	echo print_r($row);
            $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i);
            $this->excel->getActiveSheet()->setCellValue(
                    'C' . $loopRow, strip_tags($row ['chartOfAccountMergeDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue(
                    'D' . $loopRow, strip_tags($row ['chartOfAccountDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue('E' . $loopRow, strip_tags($row ['journalNumber']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('F' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue(
                    'F' . $loopRow, strip_tags($row ['chartOfAccountMergeDetailAmount'])
            );
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, strip_tags($row ['staffName']));
            $this->excel->getActiveSheet()->setCellValue('H' . $loopRow, strip_tags($row ['executeTime']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('H' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $loopRow++;
            $lastRow = 'H' . $loopRow;
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
                $filename = "chartOfAccountMergeDetail" . rand(0, 10000000) . $extension;
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
                $filename = "chartOfAccountMergeDetail" . rand(0, 10000000) . $extension;
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
                $filename = "chartOfAccountMergeDetail" . rand(0, 10000000) . $extension;
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
                $filename = "chartOfAccountMergeDetail" . rand(0, 10000000) . $extension;
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
        $chartOfAccountMergeDetailObject = new ChartOfAccountMergeDetailClass ();
        if ($_POST['securityToken'] != $chartOfAccountMergeDetailObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value
         */
        if (isset($_POST ['leafId'])) {
            $chartOfAccountMergeDetailObject->setLeafId($_POST ['leafId']);
        }
        if (isset($_POST ['offset'])) {
            $chartOfAccountMergeDetailObject->setStart($_POST ['offset']);
        }
        if (isset($_POST ['limit'])) {
            $chartOfAccountMergeDetailObject->setLimit($_POST ['limit']);
        }
        $chartOfAccountMergeDetailObject->setPageOutput($_POST['output']);
        $chartOfAccountMergeDetailObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy)
         */
        if ($_POST ['method'] == 'create') {
            $chartOfAccountMergeDetailObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $chartOfAccountMergeDetailObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $chartOfAccountMergeDetailObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $chartOfAccountMergeDetailObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$chartOfAccountMergeDetailObject->posting();
        }
        if ($_POST ['method'] == 'reverse') {
            //	$chartOfAccountMergeDetailObject->delete();
        }
    }
}
if (isset($_GET ['method'])) {
    $chartOfAccountMergeDetailObject = new ChartOfAccountMergeDetailClass ();
    if ($_GET['securityToken'] != $chartOfAccountMergeDetailObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $chartOfAccountMergeDetailObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $chartOfAccountMergeDetailObject->execute();
    /*
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'updateStatus') {
        $chartOfAccountMergeDetailObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key
     */
    if ($_GET['method'] == 'duplicate') {
        $chartOfAccountMergeDetailObject->duplicate();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $chartOfAccountMergeDetailObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $chartOfAccountMergeDetailObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $chartOfAccountMergeDetailObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $chartOfAccountMergeDetailObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting
     */
    if (isset($_GET ['mode'])) {
        $chartOfAccountMergeDetailObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $chartOfAccountMergeDetailObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        $chartOfAccountMergeDetailObject->setServiceOutput('option');
        if (($_GET['filter'] == 'chartOfAccountMerge')) {
            $chartOfAccountMergeDetailObject->getChartOfAccountMerge();
        }
        if (($_GET['filter'] == 'chartOfAccount')) {
            $chartOfAccountMergeDetailObject->getChartOfAccount();
        }
    }
}
?>
