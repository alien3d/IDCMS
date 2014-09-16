<?php

namespace Core\Financial\GeneralLedger\ChartOfAccountReportType\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\Financial\GeneralLedger\ChartOfAccountReportType\Model\ChartOfAccountReportTypeModel;
use Core\Financial\GeneralLedger\ChartOfAccountReportType\Service\ChartOfAccountReportTypeService;
use Core\RecordSet\RecordSet;
use Core\shared\SharedClass;

if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION)) {
    session_start();
}
// using absolute path instead of relative path..
// start fake document root. it's absolute path
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
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot);
require_once($newFakeDocumentRoot . "library/class/classAbstract.php");
require_once($newFakeDocumentRoot . "library/class/classRecordSet.php");
require_once($newFakeDocumentRoot . "library/class/classDate.php");
require_once($newFakeDocumentRoot . "library/class/classDocumentTrail.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
require_once($newFakeDocumentRoot . "v3/system/document/model/documentModel.php");
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/model/chartOfAccountReportTypeModel.php");
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/service/chartOfAccountReportTypeService.php");

/**
 * Class ChartOfAccountReportTypeClass
 * this is chart of account report type setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\GeneralLedger\ChartOfAccountReportType\Controller
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ChartOfAccountReportTypeClass extends ConfigClass {

    /**
     * Connection to the database
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;

    /**
     * Php Word Generate Microsoft Excel 2007 Output.Format : docxs
     * @var string
     */
    //private $word;
    /**
     * Model
     * @var \Core\Financial\GeneralLedger\ChartOfAccountReportType\Model\ChartOfAccountReportTypeModel
     */
    public $model;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     * @var string
     */
    //private $powerPoint;
    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\Financial\GeneralLedger\ChartOfAccountReportType\Service\ChartOfAccountReportTypeService
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
     * @var mixed
     */
    public $t;

    /**
     * System Format
     * @var mixed
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
     * @var string
     */
    private $documentTrail;

    /**
     * Constructor
     */
    function __construct() {
        $this->translate = array();
        $this->t = array();
        $this->leafAccess = array();
        $this->systemFormat = array();
        $this->setViewPath("./v3/financial/generalLedger/view/chartOfAccountReportType.php");
        $this->setControllerPath("./v3/financial/generalLedger/controller/chartOfAccountReportTypeController.php");
        $this->setServicePath("./v3/financial/generalLedger/service/chartOfAccountReportTypeService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(0);
        $this->setLog(1);
        $this->model = new ChartOfAccountReportTypeModel();
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

        $this->service = new ChartOfAccountReportTypeService();
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
            $this->q->fast($sql);
        }
        $this->q->start();
        $this->model->create();
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `chartofaccountreporttype` 
            (
                 `companyId`,
                 `chartOfAccountReportTypeSequence`,
                 `chartOfAccountReportTypeCode`,
                 `chartOfAccountReportTypeDescription`,
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
                 '" . $_SESSION['companyId'] . "',
                 '" . $this->model->getChartOfAccountReportTypeSequence() . "',
                 '" . $this->model->getChartOfAccountReportTypeCode() . "',
                 '" . $this->model->getChartOfAccountReportTypeDesc() . "',
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
            INSERT INTO [chartofaccountreporttype]
            (
                 [chartOfAccountReportTypeId],
                 [companyId],
                 [chartOfAccountReportTypeSequence],
                 [chartOfAccountReportTypeCode],
                 [chartOfAccountReportTypeDesc],
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
                 '" . $_SESSION['companyId'] . "',
                 '" . $this->model->getChartOfAccountReportTypeSequence() . "',
                 '" . $this->model->getChartOfAccountReportTypeCode() . "',
                 '" . $this->model->getChartOfAccountReportTypeDesc() . "',
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
            INSERT INTO CHARTOFACCOUNTREPORTTYPE
            (
                 COMPANYID,
                 CHARTOFACCOUNTREPORTTYPESEQUENCE,
                 CHARTOFACCOUNTREPORTTYPECODE,
                 CHARTOFACCOUNTREPORTTYPEDESC,
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
                 '" . $_SESSION['companyId'] . "',
                 '" . $this->model->getChartOfAccountReportTypeSequence() . "',
                 '" . $this->model->getChartOfAccountReportTypeCode() . "',
                 '" . $this->model->getChartOfAccountReportTypeDesc() . "',
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
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $chartOfAccountReportTypeId = $this->q->lastInsertId();

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
                    "chartOfAccountReportTypeId" => $chartOfAccountReportTypeId,
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
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT  count(*) AS `total`
         FROM    `chartofaccountreporttype`
         WHERE   `isActive`=1
         AND     `companyId`=" . $_SESSION['companyId'] . " ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT  count(*) AS total
         FROM    [chartofaccountreporttype]
         WHERE   [isActive]=1
         AND    [companyId] =   " . $_SESSION['companyId'] . " ";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
         SELECT  count(*) AS    total
         FROM    CHARTOFACCOUNTREPORTTYPE
         WHERE  ISACTIVE=1
         AND    COMPANYID=" . $_SESSION['companyId'] . " ";
                }
            }
        }
        $result = $this->q->fast($sql);
        if ($result) {
            if ($this->q->numberRows($result) > 0) {
                $row = $this->q->fetchArray($result);
                return $row['total'];
            }
        }
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
                            " `chartofaccountreporttype`.`isActive` = 1  AND `chartofaccountreporttype`.`companyId`='" . $_SESSION['companyId'] . "' "
                    );
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [chartOfAccountReportType].[isActive] = 1 AND [chartOfAccountReportType].[companyId]='" . $_SESSION['companyId'] . "' "
                        );
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(
                                    " CHARTOFACCOUNTREPORTTYPE.ISACTIVE = 1  AND CHARTOFACCOUNTREPORTTYPE.COMPANYID='" . $_SESSION['companyId'] . "'"
                            );
                        }
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter(
                                "   `chartofaccountreporttype`.`companyId`='" . $_SESSION['companyId'] . "'	"
                        );
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(
                                    " [chartOfAccountReportType].[companyId]='" . $_SESSION['companyId'] . "' "
                            );
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(
                                        " CHARTOFACCOUNTREPORTTYPE.COMPANYID='" . $_SESSION['companyId'] . "' "
                                );
                            }
                        }
                    }
                }
            }
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {

            $sql = "
       SELECT                    `chartofaccountreporttype`.`chartOfAccountReportTypeId`,
                    `company`.`companyDescription`,
                    `chartofaccountreporttype`.`companyId`,
                    `chartofaccountreporttype`.`chartOfAccountReportTypeSequence`,
                    `chartofaccountreporttype`.`chartOfAccountReportTypeCode`,
                    `chartofaccountreporttype`.`chartOfAccountReportTypeDescription`,
                    `chartofaccountreporttype`.`isDefault`,
                    `chartofaccountreporttype`.`isNew`,
                    `chartofaccountreporttype`.`isDraft`,
                    `chartofaccountreporttype`.`isUpdate`,
                    `chartofaccountreporttype`.`isDelete`,
                    `chartofaccountreporttype`.`isActive`,
                    `chartofaccountreporttype`.`isApproved`,
                    `chartofaccountreporttype`.`isReview`,
                    `chartofaccountreporttype`.`isPost`,
                    `chartofaccountreporttype`.`executeBy`,
                    `chartofaccountreporttype`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `chartofaccountreporttype`
		  JOIN      `staff`
		  ON        `chartofaccountreporttype`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `chartofaccountreporttype`.`companyId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getChartOfAccountReportTypeId(0, 'single')) {
                $sql .= " AND `chartofaccountreporttype`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getChartOfAccountReportTypeId(0, 'single') . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
		  SELECT                    [chartOfAccountReportType].[chartOfAccountReportTypeId],
                    [company].[companyDescription],
                    [chartOfAccountReportType].[companyId],
                    [chartOfAccountReportType].[chartOfAccountReportTypeSequence],
                    [chartOfAccountReportType].[chartOfAccountReportTypeCode],
                    [chartOfAccountReportType].[chartOfAccountReportTypeDesc],
                    [chartOfAccountReportType].[isDefault],
                    [chartOfAccountReportType].[isNew],
                    [chartOfAccountReportType].[isDraft],
                    [chartOfAccountReportType].[isUpdate],
                    [chartOfAccountReportType].[isDelete],
                    [chartOfAccountReportType].[isActive],
                    [chartOfAccountReportType].[isApproved],
                    [chartOfAccountReportType].[isReview],
                    [chartOfAccountReportType].[isPost],
                    [chartOfAccountReportType].[executeBy],
                    [chartOfAccountReportType].[executeTime],
                    [staff].[staffName]
		  FROM 	[chartOfAccountReportType]
		  JOIN	[staff]
		  ON	[chartOfAccountReportType].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [chartOfAccountReportType].[companyId]
		  WHERE     " . $this->getAuditFilter();
                if ($this->model->getChartOfAccountReportTypeId(0, 'single')) {
                    $sql .= " AND [chartOfAccountReportType].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getChartOfAccountReportTypeId(0, 'single') . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
		  SELECT                    CHARTOFACCOUNTREPORTTYPE.CHARTOFACCOUNTREPORTTYPEID,
                    COMPANY.COMPANYID,
                    CHARTOFACCOUNTREPORTTYPE.COMPANYID,
                    CHARTOFACCOUNTREPORTTYPE.CHARTOFACCOUNTREPORTTYPESEQUENCE,
                    CHARTOFACCOUNTREPORTTYPE.CHARTOFACCOUNTREPORTTYPECODE,
                    CHARTOFACCOUNTREPORTTYPE.CHARTOFACCOUNTREPORTTYPEDESC,
                    CHARTOFACCOUNTREPORTTYPE.ISDEFAULT,
                    CHARTOFACCOUNTREPORTTYPE.ISNEW,
                    CHARTOFACCOUNTREPORTTYPE.ISDRAFT,
                    CHARTOFACCOUNTREPORTTYPE.ISUPDATE,
                    CHARTOFACCOUNTREPORTTYPE.ISDELETE,
                    CHARTOFACCOUNTREPORTTYPE.ISACTIVE,
                    CHARTOFACCOUNTREPORTTYPE.ISAPPROVED,
                    CHARTOFACCOUNTREPORTTYPE.ISREVIEW,
                    CHARTOFACCOUNTREPORTTYPE.ISPOST,
                    CHARTOFACCOUNTREPORTTYPE.EXECUTEBY,
                    CHARTOFACCOUNTREPORTTYPE.EXECUTETIME,
                    STAFF.STAFFNAME
		  FROM 	CHARTOFACCOUNTREPORTTYPE
		  JOIN	STAFF
		  ON	CHARTOFACCOUNTREPORTTYPE.EXECUTEBY = STAFF.STAFFID
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = CHARTOFACCOUNTREPORTTYPE.COMPANYID
         WHERE     " . $this->getAuditFilter();
                    if ($this->model->getChartOfAccountReportTypeId(0, 'single')) {
                        $sql .= " AND CHARTOFACCOUNTREPORTTYPE. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getChartOfAccountReportTypeId(0, 'single') . "'";
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
                $sql .= " AND `chartofaccountreporttype`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [chartOfAccountReportType].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND CHARTOFACCOUNTREPORTTYPE." . strtoupper(
                                        $this->model->getFilterCharacter()
                                ) . " = '" . $this->getCharacterQuery() . "'";
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
                        'chartofaccountreporttype', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'chartofaccountreporttype', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'CHARTOFACCOUNTREPORTTYPE', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
        $filterArray = array('chartOfAccountReportTypeId');
        /**
         * filter table
         * @variables $tableArray
         */
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('chartofaccountreporttype');
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array('chartofaccountreporttype');
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array('CHARTOFACCOUNTREPORTTYPE');
                }
            }
        }
        $tempSql = null;
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
        // optional debugger.uncomment if wanted to used
        //if ($this->q->getExecute() == 'fail') {
        //	echo json_encode(array(
        //   "success" => false,
        //   "message" => $this->q->realEscapeString($sql)
        //	));
        //	exit();
        //}
        // end of optional debugger
        try {
            $this->q->read($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }

        $total = $this->q->numberRows();
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
        }
        $_SESSION ['sql'] = $sql; // push to session so can make report via excel and pdf
        $_SESSION ['start'] = $this->getStart();
        $_SESSION ['limit'] = $this->getLimit();
        if ($this->getLimit()) {
            // only mysql have limit
            if ($this->getVendor() == self::MYSQL) {
                $sql .= " LIMIT  " . $this->getStart() . "," . $this->getLimit() . " ";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    /**
                     * Sql Server and Oracle used row_number
                     * Parameterize Query We don't support
                     * **/

                    $sql = "WITH [chartofaccountreporttypeDerived] AS
							(
								SELECT 										[chartOfAccountReportType].[chartOfAccountReportTypeId],
										[company].[companyDescription],
										[chartOfAccountReportType].[companyId],
										[chartOfAccountReportType].[chartOfAccountReportTypeSequence],
										[chartOfAccountReportType].[chartOfAccountReportTypeCode],
										[chartOfAccountReportType].[chartOfAccountReportTypeDesc],
										[chartOfAccountReportType].[isDefault],
										[chartOfAccountReportType].[isNew],
										[chartOfAccountReportType].[isDraft],
										[chartOfAccountReportType].[isUpdate],
										[chartOfAccountReportType].[isDelete],
										[chartOfAccountReportType].[isActive],
										[chartOfAccountReportType].[isApproved],
										[chartOfAccountReportType].[isReview],
										[chartOfAccountReportType].[isPost],
										[chartOfAccountReportType].[executeBy],
										[chartOfAccountReportType].[executeTime],
										[staff].[staffName],
										ROW_NUMBER() OVER (ORDER BY [chartOfAccountReportType].[chartOfAccountReportTypeId]) AS 'RowNumber'
							     FROM 	[chartofaccountreporttype]
							     JOIN	[staff]
							     ON         [chartOfAccountReportType].[executeBy] = [staff].[staffId]
							     JOIN   [company]
							     ON     [company].[companyId] = [chartOfAccountReportType].[companyId]
							     WHERE 		" . $this->getAuditFilter() . "

							)
							SELECT		*
							FROM 		[chartofaccountreporttypeDerived]
							WHERE 		[RowNumber]
							BETWEEN	" . ($this->getStart() + 1) . "
							AND 			" . ($this->getStart() + $this->getLimit()) . " ;";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        /**
                         * Oracle using derived table also
                         * */
                        $sql = "
						SELECT *
						FROM ( SELECT	a.*,
												rownum r
						FROM (
SELECT							     CHARTOFACCOUNTREPORTTYPE.CHARTOFACCOUNTREPORTTYPEID,
							     COMPANY.COMPANYID,
							     CHARTOFACCOUNTREPORTTYPE.COMPANYID,
							     CHARTOFACCOUNTREPORTTYPE.CHARTOFACCOUNTREPORTTYPESEQUENCE,
							     CHARTOFACCOUNTREPORTTYPE.CHARTOFACCOUNTREPORTTYPECODE,
							     CHARTOFACCOUNTREPORTTYPE.CHARTOFACCOUNTREPORTTYPEDESC,
							     CHARTOFACCOUNTREPORTTYPE.ISDEFAULT,
							     CHARTOFACCOUNTREPORTTYPE.ISNEW,
							     CHARTOFACCOUNTREPORTTYPE.ISDRAFT,
							     CHARTOFACCOUNTREPORTTYPE.ISUPDATE,
							     CHARTOFACCOUNTREPORTTYPE.ISDELETE,
							     CHARTOFACCOUNTREPORTTYPE.ISACTIVE,
							     CHARTOFACCOUNTREPORTTYPE.ISAPPROVED,
							     CHARTOFACCOUNTREPORTTYPE.ISREVIEW,
							     CHARTOFACCOUNTREPORTTYPE.ISPOST,
							     CHARTOFACCOUNTREPORTTYPE.EXECUTEBY,
							     CHARTOFACCOUNTREPORTTYPE.EXECUTETIME,
                                   STAFF.STAFFNAME
							     FROM 	CHARTOFACCOUNTREPORTTYPE
							     JOIN	  STAFF
							     ON		CHARTOFACCOUNTREPORTTYPE.EXECUTEBY = STAFF.STAFFID
							     JOIN   COMPANY
							     ON     COMPANY.COMPANYID = CHARTOFACCOUNTREPORTTYPE.COMPANYID
							     WHERE 		" . $this->getAuditFilter() . $tempSql . $tempSql2 . "
								 ) a
						WHERE rownum <= '" . ($this->getStart() + $this->getLimit()) . "' )
						WHERE r >=  '" . ($this->getStart() + 1) . "'";
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
        if (!($this->model->getChartOfAccountReportTypeId(0, 'single'))) {
            try {
                $this->q->read($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        $items = array();
        $i = 1;
        while (($row = $this->q->fetchAssoc()) == true) {
            $row['total'] = $total; // small override
            $row['counter'] = $this->getStart() + 16;
            if ($this->model->getChartOfAccountReportTypeId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord(
                        'value', $this->model->getChartOfAccountReportTypeId(0, 'single')
                );
                $row['nextRecord'] = $this->nextRecord(
                        'value', $this->model->getChartOfAccountReportTypeId(0, 'single')
                );
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else {
            if ($this->getPageOutput() == 'json') {
                if ($this->model->getChartOfAccountReportTypeId(0, 'single')) {
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
                                                'value', $this->model->getChartOfAccountReportTypeId(0, 'single')
                                        ),
                                        'nextRecord' => $this->nextRecord(
                                                'value', $this->model->getChartOfAccountReportTypeId(0, 'single')
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
                                        'value', $this->model->getChartOfAccountReportTypeId(0, 'single')
                                ),
                                'nextRecord' => $this->recordSet->nextRecord(
                                        'value', $this->model->getChartOfAccountReportTypeId(0, 'single')
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
     * @return 
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
            $this->q->fast($sql);
        }
        $this->q->start();
        $this->model->update();
        // before updating check the id exist or not . if exist continue to update else warning the user
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`chartofaccountreporttype`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getChartOfAccountReportTypeId(
                            0, 'single'
                    ) . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[chartofaccountreporttype]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getChartOfAccountReportTypeId(
                                0, 'single'
                        ) . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	CHARTOFACCOUNTREPORTTYPE
           WHERE  	" . strtoupper(
                                    $this->model->getPrimaryKeyName()
                            ) . " = '" . $this->model->getChartOfAccountReportTypeId(0, 'single') . "' ";
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
               UPDATE `chartofaccountreporttype` SET
                       `companyId` = '" . $this->model->getCompanyId() . "',
                       `chartOfAccountReportTypeSequence` = '" . $this->model->getChartOfAccountReportTypeSequence() . "',
                       `chartOfAccountReportTypeCode` = '" . $this->model->getChartOfAccountReportTypeCode() . "',
                       `chartOfAccountReportTypeDescription` = '" . $this->model->getChartOfAccountReportTypeDesc() . "',
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
               WHERE    `chartOfAccountReportTypeId`='" . $this->model->getChartOfAccountReportTypeId(
                                '0', 'single'
                        ) . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                UPDATE [chartofaccountreporttype] SET
                       [companyId] = '" . $this->model->getCompanyId() . "',
                       [chartOfAccountReportTypeSequence] = '" . $this->model->getChartOfAccountReportTypeSequence() . "',
                       [chartOfAccountReportTypeCode] = '" . $this->model->getChartOfAccountReportTypeCode() . "',
                       [chartOfAccountReportTypeDesc] = '" . $this->model->getChartOfAccountReportTypeDesc() . "',
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
                WHERE   [chartOfAccountReportTypeId]='" . $this->model->getChartOfAccountReportTypeId(
                                    '0', 'single'
                            ) . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
                UPDATE CHARTOFACCOUNTREPORTTYPE SET
                       CHARTOFACCOUNTREPORTTYPESEQUENCE = '" . $this->model->getChartOfAccountReportTypeSequence() . "',
                       CHARTOFACCOUNTREPORTTYPECODE = '" . $this->model->getChartOfAccountReportTypeCode() . "',
                       CHARTOFACCOUNTREPORTTYPEDESC = '" . $this->model->getChartOfAccountReportTypeDesc() . "',
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
                WHERE  CHARTOFACCOUNTREPORTTYPEID='" . $this->model->getChartOfAccountReportTypeId('0', 'single') . "'";
                    }
                }
            }
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
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
            $this->q->fast($sql);
        }
        $this->q->start();
        $this->model->delete();
        // before updating check the id exist or not . if exist continue to update else warning the user
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`chartofaccountreporttype`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getChartOfAccountReportTypeId(
                            0, 'single'
                    ) . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[chartofaccountreporttype]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getChartOfAccountReportTypeId(
                                0, 'single'
                        ) . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	CHARTOFACCOUNTREPORTTYPE
           WHERE  	" . strtoupper(
                                    $this->model->getPrimaryKeyName()
                            ) . " = '" . $this->model->getChartOfAccountReportTypeId(0, 'single') . "' ";
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
               UPDATE  `chartofaccountreporttype`
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
               WHERE   `chartOfAccountReportTypeId`   =  '" . $this->model->getChartOfAccountReportTypeId(
                                0, 'single'
                        ) . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
               UPDATE  [chartofaccountreporttype]
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
               WHERE   [chartOfAccountReportTypeId]	=  '" . $this->model->getChartOfAccountReportTypeId(
                                    0, 'single'
                            ) . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
               UPDATE  CHARTOFACCOUNTREPORTTYPE
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
               WHERE   CHARTOFACCOUNTREPORTTYPEID	=  '" . $this->model->getChartOfAccountReportTypeId(
                                        0, 'single'
                                ) . "'";
                    }
                }
            }
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
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
            $this->q->fast($sql);
        }
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT  `chartofaccountreporttypeCode`
           FROM    `chartofaccountreporttype`
           WHERE   `chartofaccountreporttypeCode` 	= 	'" . $this->model->getChartofaccountreporttypeCode() . "'
           AND     `isActive`  =   1
           AND     `companyId` =   '" . $_SESSION['companyId'] . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT  [referenceNo]
           FROM    [chartofaccountreporttype]
           WHERE   [chartofaccountreporttypeCode] = 	'" . $this->model->getChartofaccountreporttypeCode() . "'
           AND     [isActive]  =   1
           AND     [companyId] =	'" . $_SESSION['companyId'] . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
               SELECT  REFERENCENO
               FROM    CHARTOFACCOUNTREPORTTYPE
               WHERE   CHARTOFACCOUNTREPORTTYPECODE	= 	'" . $this->model->getChartofaccountreporttypeCode() . "'
               AND     ISACTIVE    =   1
               AND     COMPANYID   =   '" . $_SESSION['companyId'] . "'";
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
            $sql = str_replace("LIMIT", "", $_SESSION ['sql']);
            $sql = str_replace($_SESSION ['start'] . "," . $_SESSION ['limit'], "", $sql);
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
                ->setSubject('chartofaccountreporttype')
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
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['companyIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['chartOfAccountReportTypeSequenceLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['chartOfAccountReportTypeCodeLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['chartOfAccountReportTypeDescLabel']);
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
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('C' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('C' . $loopRow, $row ['companyId']);
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('D' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('D' . $loopRow, $row ['chartOfAccountReportTypeSequence']);
            $this->excel->getActiveSheet()
                    ->setCellValue('E' . $loopRow, $row ['chartOfAccountReportTypeCode']);
            $this->excel->getActiveSheet()
                    ->setCellValue('F' . $loopRow, $row ['chartOfAccountReportTypeDescription']);
            $this->excel->getActiveSheet()
                    ->setCellValue('G' . $loopRow, $row ['staffName']);
            $this->excel->getActiveSheet()
                    ->setCellValue('H' . $loopRow, $row ['executeTime']);
            $this->excel->getActiveSheet()
                    ->getStyle()
                    ->getNumberFormat('H' . $loopRow)
                    ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
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
                break;
            case 'excel5':
                $objWriter = new \PHPExcel_Writer_Excel5($this->excel);
                $extension = '.xls';
                $folder = 'excel';
                break;
            case 'pdf':
                $objWriter = new \PHPExcel_Writer_PDF($this->excel);
                $objWriter->writeAllSheets();
                $extension = '.pdf';
                $folder = 'pdf';
                break;
            case 'html':
                $objWriter = new \PHPExcel_Writer_HTML($this->excel);
                // $objWriter->setUseBOM(true);
                $extension = '.html';
                //$objWriter->setPreCalculateFormulas(false); //calculation off
                $folder = 'html';
                break;
            case 'csv':
                $objWriter = new \PHPExcel_Writer_CSV($this->excel);
                // $objWriter->setUseBOM(true);
                // $objWriter->setPreCalculateFormulas(false); //calculation off
                $extension = '.csv';
                $folder = 'excel';
                break;
        }
        $filename = "chartofaccountreporttype" . rand(0, 10000000) . $extension;
        $path = $_SERVER ['DOCUMENT_ROOT'] . "/APPS/package/financial/generalLedger/document/" . $folder . "/" . $filename;
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
    }

}

if (isset($_POST ['method'])) {
    if (isset($_POST['output'])) {
        $chartOfAccountReportTypeObject = new ChartOfAccountReportTypeClass ();
        /*
         *  Load the dynamic value
         */
        if (isset($_POST ['leafId'])) {
            $chartOfAccountReportTypeObject->setLeafId($_POST ['leafId']);
        }
        $chartOfAccountReportTypeObject->setPageOutput($_POST['output']);
        $chartOfAccountReportTypeObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy)
         */
        if ($_POST ['method'] == 'create') {
            $chartOfAccountReportTypeObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $chartOfAccountReportTypeObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $chartOfAccountReportTypeObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $chartOfAccountReportTypeObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$chartOfAccountReportTypeObject->posting();
        }
        if ($_POST ['method'] == 'reverse') {
            //	$chartOfAccountReportTypeObject->delete();
        }
    }
}
if (isset($_GET ['method'])) {
    $chartOfAccountReportTypeObject = new ChartOfAccountReportTypeClass ();
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $chartOfAccountReportTypeObject->setLeafId($_GET ['leafId']);
    }
    /*
     * Admin Only
     */
    if (isset($_GET ['isAdmin'])) {
        $chartOfAccountReportTypeObject->setIsAdmin($_GET ['isAdmin']);
    }
    /**
     * Database Request
     */
    if (isset($_GET ['databaseRequest'])) {
        $chartOfAccountReportTypeObject->setRequestDatabase($_GET ['databaseRequest']);
    }
    if (isset($_GET['companyId'])) {
        $chartOfAccountReportTypeObject->setCompanyId($_GET['companyId']);
    }
    /*
     *  Load the dynamic value
     */
    $chartOfAccountReportTypeObject->execute();
    /**
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'updateStatus') {
        $chartOfAccountReportTypeObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key
     */
    if (isset($_GET ['chartofaccountreporttypeCode'])) {
        if (strlen($_GET ['chartofaccountreporttypeCode']) > 0) {
            $chartOfAccountReportTypeObject->duplicate();
        }
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $chartOfAccountReportTypeObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $chartOfAccountReportTypeObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $chartOfAccountReportTypeObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $chartOfAccountReportTypeObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting
     */
    if (isset($_GET ['mode'])) {
        $chartOfAccountReportTypeObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $chartOfAccountReportTypeObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        
    }
}
?>