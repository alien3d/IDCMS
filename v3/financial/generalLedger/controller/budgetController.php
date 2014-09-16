<?php

namespace Core\Financial\GeneralLedger\Budget\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\Financial\GeneralLedger\Budget\Model\BudgetModel;
use Core\Financial\GeneralLedger\Budget\Service\BudgetService;
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
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/model/budgetModel.php");
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/service/budgetService.php");

/**
 * Class Budget
 * Budget Don't have update futured.It will create new record and the final consider as lock /finalize
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package  Core\Financial\GeneralLedger\Budget\Controller
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class BudgetClass extends ConfigClass {

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
     * @var \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public $model;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     * @var \PHPPowerPoint
     */
    //private $powerPoint; 
    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\Financial\GeneralLedger\Budget\Service\BudgetService
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
        $this->setViewPath("./v3/financial/generalLedger/view/budget.php");
        $this->setControllerPath("./v3/financial/generalLedger/controller/budgetController.php");
        $this->setServicePath("./v3/financial/generalLedger/service/budgetService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new BudgetModel();
        $this->model->setVendor($this->getVendor());
        $this->model->execute();
        $this->setViewPath("./v3/financial/generalLedger/view/" . $this->model->getFrom());
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
        $translator->setCurrentTable(array($this->model->getTableName(), 'chartOfAccount', 'generalLedger'));
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

        $this->service = new BudgetService();
        $this->service->q = $this->q;
        $this->service->t = $this->t;
        $this->service->systemFormatArray = $this->systemFormatArray;
        $this->service->translate = $this->translate;
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
        if (!$this->model->getChartOfAccountId()) {
            $this->model->setChartOfAccountId($this->service->getChartOfAccountDefaultValue());
        }
        if (!$this->model->getFinanceYearId()) {
            $this->model->setFinanceYearId($this->service->getFinanceYearDefaultValue());
        }
        //$this->model->setDocumentNumber($this->getDocumentNumber());
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `budget` 
            (
                 `companyId`,
                 `chartOfAccountId`,
                 `financeYearId`,
                 `budgetTargetMonthOne`,
                 `budgetActualMonthOne`,
                 `budgetTargetMonthTwo`,
                 `budgetActualMonthTwo`,
                 `budgetTargetMonthThree`,
                 `budgetActualMonthThree`,
                 `budgetTargetMonthFourth`,
                 `budgetActualMonthFourth`,
                 `budgetTargetMonthFifth`,
                 `budgetActualMonthFifth`,
                 `budgetTargetMonthSix`,
                 `budgetActualMonthSix`,
                 `budgetTargetMonthSeven`,
                 `budgetActualMonthSeven`,
                 `budgetTargetMonthEight`,
                 `budgetActualMonthEight`,
                 `budgetTargetMonthNine`,
                 `budgetActualMonthNine`,
                 `budgetTargetMonthTen`,
                 `budgetActualMonthTen`,
                 `budgetTargetMonthEleven`,
                 `budgetActualMonthEleven`,
                 `budgetTargetMonthTwelve`,
                 `budgetActualMonthTwelve`,
                 `budgetTargetMonthThirteen`,
                 `budgetActualMonthThirteen`,
                 `budgetTargetMonthFourteen`,
                 `budgetActualMonthFourteen`,
                 `budgetTargetMonthFifteen`,
                 `budgetActualMonthFifteen`,
                 `budgetTargetMonthSixteen`,
                 `budgetActualMonthSixteen`,
                 `budgetTargetMonthSeventeen`,
                 `budgetActualMonthSeventeen`,
                 `budgetTargetMonthEighteen`,
                 `budgetActualMonthEighteen`,
                 `budgetTargetTotalYear`,
                 `budgetActualTotalYear`,
                 `budgetVersion`,
                 `isDefault`,
                 `isNew`,
                 `isDraft`,
                 `isUpdate`,
                 `isDelete`,
                 `isActive`,
                 `isApproved`,
                 `isReview`,
                 `isPost`,
                 `isLock`,
                 `executeBy`,
                 `executeTime`
       ) VALUES ( 
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getFinanceYearId() . "',
                 '" . $this->model->getBudgetTargetMonthOne() . "',
                 '" . $this->model->getBudgetActualMonthOne() . "',
                 '" . $this->model->getBudgetTargetMonthTwo() . "',
                 '" . $this->model->getBudgetActualMonthTwo() . "',
                 '" . $this->model->getBudgetTargetMonthThree() . "',
                 '" . $this->model->getBudgetActualMonthThree() . "',
                 '" . $this->model->getBudgetTargetMonthFourth() . "',
                 '" . $this->model->getBudgetActualMonthFourth() . "',
                 '" . $this->model->getBudgetTargetMonthFifth() . "',
                 '" . $this->model->getBudgetActualMonthFifth() . "',
                 '" . $this->model->getBudgetTargetMonthSix() . "',
                 '" . $this->model->getBudgetActualMonthSix() . "',
                 '" . $this->model->getBudgetTargetMonthSeven() . "',
                 '" . $this->model->getBudgetActualMonthSeven() . "',
                 '" . $this->model->getBudgetTargetMonthEight() . "',
                 '" . $this->model->getBudgetActualMonthEight() . "',
                 '" . $this->model->getBudgetTargetMonthNine() . "',
                 '" . $this->model->getBudgetActualMonthNine() . "',
                 '" . $this->model->getBudgetTargetMonthTen() . "',
                 '" . $this->model->getBudgetActualMonthTen() . "',
                 '" . $this->model->getBudgetTargetMonthEleven() . "',
                 '" . $this->model->getBudgetActualMonthEleven() . "',
                 '" . $this->model->getBudgetTargetMonthTwelve() . "',
                 '" . $this->model->getBudgetActualMonthTwelve() . "',
                 '" . $this->model->getBudgetTargetMonthThirteen() . "',
                 '" . $this->model->getBudgetActualMonthThirteen() . "',
                 '" . $this->model->getBudgetTargetMonthFourteen() . "',
                 '" . $this->model->getBudgetActualMonthFourteen() . "',
                 '" . $this->model->getBudgetTargetMonthFifteen() . "',
                 '" . $this->model->getBudgetActualMonthFifteen() . "',
                 '" . $this->model->getBudgetTargetMonthSixteen() . "',
                 '" . $this->model->getBudgetActualMonthSixteen() . "',
                 '" . $this->model->getBudgetTargetMonthSeventeen() . "',
                 '" . $this->model->getBudgetActualMonthSeventeen() . "',
                 '" . $this->model->getBudgetTargetMonthEighteen() . "',
                 '" . $this->model->getBudgetActualMonthEighteen() . "',
                 '" . $this->model->getBudgetTargetTotalYear() . "',
                 '" . $this->model->getBudgetActualTotalYear() . "',
                 '" . $this->model->getBudgetVersion() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getIsLock() . "',
                 '" . $this->model->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . "
       );";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            INSERT INTO [budget]
            (
                 [budgetId],
                 [companyId],
                 [chartOfAccountId],
                 [financeYearId],
                 [budgetTargetMonthOne],
                 [budgetActualMonthOne],
                 [budgetTargetMonthTwo],
                 [budgetActualMonthTwo],
                 [budgetTargetMonthThree],
                 [budgetActualMonthThree],
                 [budgetTargetMonthFourth],
                 [budgetActualMonthFourth],
                 [budgetTargetMonthFifth],
                 [budgetActualMonthFifth],
                 [budgetTargetMonthSix],
                 [budgetActualMonthSix],
                 [budgetTargetMonthSeven],
                 [budgetActualMonthSeven],
                 [budgetTargetMonthEight],
                 [budgetActualMonthEight],
                 [budgetTargetMonthNine],
                 [budgetActualMonthNine],
                 [budgetTargetMonthTen],
                 [budgetActualMonthTen],
                 [budgetTargetMonthEleven],
                 [budgetActualMonthEleven],
                 [budgetTargetMonthTwelve],
                 [budgetActualMonthTwelve],
                 [budgetTargetMonthThirteen],
                 [budgetActualMonthThirteen],
                 [budgetTargetMonthFourteen],
                 [budgetActualMonthFourteen],
                 [budgetTargetMonthFifteen],
                 [budgetActualMonthFifteen],
                 [budgetTargetMonthSixteen],
                 [budgetActualMonthSixteen],
                 [budgetTargetMonthSeventeen],
                 [budgetActualMonthSeventeen],
                 [budgetTargetMonthEighteen],
                 [budgetActualMonthEighteen],
                 [budgetTargetTotalYear],
                 [budgetActualTotalYear],
                 [budgetVersion],
                 [isDefault],
                 [isNew],
                 [isDraft],
                 [isUpdate],
                 [isDelete],
                 [isActive],
                 [isApproved],
                 [isReview],
                 [isPost],
                 [isLock],
                 [executeBy],
                 [executeTime]
) VALUES (
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getFinanceYearId() . "',
                 '" . $this->model->getBudgetTargetMonthOne() . "',
                 '" . $this->model->getBudgetActualMonthOne() . "',
                 '" . $this->model->getBudgetTargetMonthTwo() . "',
                 '" . $this->model->getBudgetActualMonthTwo() . "',
                 '" . $this->model->getBudgetTargetMonthThree() . "',
                 '" . $this->model->getBudgetActualMonthThree() . "',
                 '" . $this->model->getBudgetTargetMonthFourth() . "',
                 '" . $this->model->getBudgetActualMonthFourth() . "',
                 '" . $this->model->getBudgetTargetMonthFifth() . "',
                 '" . $this->model->getBudgetActualMonthFifth() . "',
                 '" . $this->model->getBudgetTargetMonthSix() . "',
                 '" . $this->model->getBudgetActualMonthSix() . "',
                 '" . $this->model->getBudgetTargetMonthSeven() . "',
                 '" . $this->model->getBudgetActualMonthSeven() . "',
                 '" . $this->model->getBudgetTargetMonthEight() . "',
                 '" . $this->model->getBudgetActualMonthEight() . "',
                 '" . $this->model->getBudgetTargetMonthNine() . "',
                 '" . $this->model->getBudgetActualMonthNine() . "',
                 '" . $this->model->getBudgetTargetMonthTen() . "',
                 '" . $this->model->getBudgetActualMonthTen() . "',
                 '" . $this->model->getBudgetTargetMonthEleven() . "',
                 '" . $this->model->getBudgetActualMonthEleven() . "',
                 '" . $this->model->getBudgetTargetMonthTwelve() . "',
                 '" . $this->model->getBudgetActualMonthTwelve() . "',
                 '" . $this->model->getBudgetTargetMonthThirteen() . "',
                 '" . $this->model->getBudgetActualMonthThirteen() . "',
                 '" . $this->model->getBudgetTargetMonthFourteen() . "',
                 '" . $this->model->getBudgetActualMonthFourteen() . "',
                 '" . $this->model->getBudgetTargetMonthFifteen() . "',
                 '" . $this->model->getBudgetActualMonthFifteen() . "',
                 '" . $this->model->getBudgetTargetMonthSixteen() . "',
                 '" . $this->model->getBudgetActualMonthSixteen() . "',
                 '" . $this->model->getBudgetTargetMonthSeventeen() . "',
                 '" . $this->model->getBudgetActualMonthSeventeen() . "',
                 '" . $this->model->getBudgetTargetMonthEighteen() . "',
                 '" . $this->model->getBudgetActualMonthEighteen() . "',
                 '" . $this->model->getBudgetTargetTotalYear() . "',
                 '" . $this->model->getBudgetActualTotalYear() . "',
                 '" . $this->model->getBudgetVersion() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getIsLock() . "',
                 '" . $this->model->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . "
            );";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            INSERT INTO BUDGET
            (
                 COMPANYID,
                 CHARTOFACCOUNTID,
                 FINANCEYEARID,
                 BUDGETTARGETMONTHONE,
                 BUDGETACTUALMONTHONE,
                 BUDGETTARGETMONTHTWO,
                 BUDGETACTUALMONTHTWO,
                 BUDGETTARGETMONTHTHREE,
                 BUDGETACTUALMONTHTHREE,
                 BUDGETTARGETMONTHFOURTH,
                 BUDGETACTUALMONTHFOURTH,
                 BUDGETTARGETMONTHFIFTH,
                 BUDGETACTUALMONTHFIFTH,
                 BUDGETTARGETMONTHSIX,
                 BUDGETACTUALMONTHSIX,
                 BUDGETTARGETMONTHSEVEN,
                 BUDGETACTUALMONTHSEVEN,
                 BUDGETTARGETMONTHEIGHT,
                 BUDGETACTUALMONTHEIGHT,
                 BUDGETTARGETMONTHNINE,
                 BUDGETACTUALMONTHNINE,
                 BUDGETTARGETMONTHTEN,
                 BUDGETACTUALMONTHTEN,
                 BUDGETTARGETMONTHELEVEN,
                 BUDGETACTUALMONTHELEVEN,
                 BUDGETTARGETMONTHTWELVE,
                 BUDGETACTUALMONTHTWELVE,
                 BUDGETTARGETMONTHTHIRTEEN,
                 BUDGETACTUALMONTHTHIRTEEN,
                 BUDGETTARGETMONTHFOURTEEN,
                 BUDGETACTUALMONTHFOURTEEN,
                 BUDGETTARGETMONTHFIFTEEN,
                 BUDGETACTUALMONTHFIFTEEN,
                 BUDGETTARGETMONTHSIXTEEN,
                 BUDGETACTUALMONTHSIXTEEN,
                 BUDGETTARGETMONTHSEVENTEEN,
                 BUDGETACTUALMONTHSEVENTEEN,
                 BUDGETTARGETMONTHEIGHTEEN,
                 BUDGETACTUALMONTHEIGHTEEN,
                 BUDGETTARGETTOTALYEAR,
                 BUDGETACTUALTOTALYEAR,
                 BUDGETVERSION,
                 ISDEFAULT,
                 ISNEW,
                 ISDRAFT,
                 ISUPDATE,
                 ISDELETE,
                 ISACTIVE,
                 ISAPPROVED,
                 ISREVIEW,
                 ISPOST,
                 ISLOCK,
                 EXECUTEBY,
                 EXECUTETIME
            ) VALUES (
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getFinanceYearId() . "',
                 '" . $this->model->getBudgetTargetMonthOne() . "',
                 '" . $this->model->getBudgetActualMonthOne() . "',
                 '" . $this->model->getBudgetTargetMonthTwo() . "',
                 '" . $this->model->getBudgetActualMonthTwo() . "',
                 '" . $this->model->getBudgetTargetMonthThree() . "',
                 '" . $this->model->getBudgetActualMonthThree() . "',
                 '" . $this->model->getBudgetTargetMonthFourth() . "',
                 '" . $this->model->getBudgetActualMonthFourth() . "',
                 '" . $this->model->getBudgetTargetMonthFifth() . "',
                 '" . $this->model->getBudgetActualMonthFifth() . "',
                 '" . $this->model->getBudgetTargetMonthSix() . "',
                 '" . $this->model->getBudgetActualMonthSix() . "',
                 '" . $this->model->getBudgetTargetMonthSeven() . "',
                 '" . $this->model->getBudgetActualMonthSeven() . "',
                 '" . $this->model->getBudgetTargetMonthEight() . "',
                 '" . $this->model->getBudgetActualMonthEight() . "',
                 '" . $this->model->getBudgetTargetMonthNine() . "',
                 '" . $this->model->getBudgetActualMonthNine() . "',
                 '" . $this->model->getBudgetTargetMonthTen() . "',
                 '" . $this->model->getBudgetActualMonthTen() . "',
                 '" . $this->model->getBudgetTargetMonthEleven() . "',
                 '" . $this->model->getBudgetActualMonthEleven() . "',
                 '" . $this->model->getBudgetTargetMonthTwelve() . "',
                 '" . $this->model->getBudgetActualMonthTwelve() . "',
                 '" . $this->model->getBudgetTargetMonthThirteen() . "',
                 '" . $this->model->getBudgetActualMonthThirteen() . "',
                 '" . $this->model->getBudgetTargetMonthFourteen() . "',
                 '" . $this->model->getBudgetActualMonthFourteen() . "',
                 '" . $this->model->getBudgetTargetMonthFifteen() . "',
                 '" . $this->model->getBudgetActualMonthFifteen() . "',
                 '" . $this->model->getBudgetTargetMonthSixteen() . "',
                 '" . $this->model->getBudgetActualMonthSixteen() . "',
                 '" . $this->model->getBudgetTargetMonthSeventeen() . "',
                 '" . $this->model->getBudgetActualMonthSeventeen() . "',
                 '" . $this->model->getBudgetTargetMonthEighteen() . "',
                 '" . $this->model->getBudgetActualMonthEighteen() . "',
                 '" . $this->model->getBudgetTargetTotalYear() . "',
                 '" . $this->model->getBudgetActualTotalYear() . "',
                 '" . $this->model->getBudgetVersion() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getIsLock() . "',
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
        $budgetId = $this->q->lastInsertId('budget');
        $this->service->setSumBudgetPerYearAccount(
                $budgetId
        );
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
                    "budgetId" => $budgetId,
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
         FROM    `budget`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT    COUNT(*) AS total
         FROM      [budget]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT    COUNT(*)    AS  \"total\"
         FROM      BUDGET
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
                            " `budget`.`isActive` = 1  AND `budget`.`companyId`='" . $this->getCompanyId() . "' "
                    );
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [budget].[isActive] = 1 AND [budget].[companyId]='" . $this->getCompanyId() . "' "
                        );
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(
                                    " BUDGET.ISACTIVE = 1  AND BUDGET.COMPANYID='" . $this->getCompanyId() . "'"
                            );
                        }
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter("   `budget`.`companyId`='" . $this->getCompanyId() . "'	");
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(" [budget].[companyId]='" . $this->getCompanyId() . "' ");
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(" BUDGET.COMPANYID='" . $this->getCompanyId() . "' ");
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
       SELECT                    `budget`.`budgetId`,
                    `company`.`companyDescription`,
                    `budget`.`companyId`,
                    `chartofaccount`.`chartOfAccountNumber`,
                    `chartofaccount`.`chartOfAccountTitle`,
                    `budget`.`chartOfAccountId`,
                    `financeyear`.`financeYearYear`,
                    `budget`.`financeYearId`,
                    `budget`.`budgetTargetMonthOne`,
                    `budget`.`budgetActualMonthOne`,
                    `budget`.`budgetTargetMonthTwo`,
                    `budget`.`budgetActualMonthTwo`,
                    `budget`.`budgetTargetMonthThree`,
                    `budget`.`budgetActualMonthThree`,
                    `budget`.`budgetTargetMonthFourth`,
                    `budget`.`budgetActualMonthFourth`,
                    `budget`.`budgetTargetMonthFifth`,
                    `budget`.`budgetActualMonthFifth`,
                    `budget`.`budgetTargetMonthSix`,
                    `budget`.`budgetActualMonthSix`,
                    `budget`.`budgetTargetMonthSeven`,
                    `budget`.`budgetActualMonthSeven`,
                    `budget`.`budgetTargetMonthEight`,
                    `budget`.`budgetActualMonthEight`,
                    `budget`.`budgetTargetMonthNine`,
                    `budget`.`budgetActualMonthNine`,
                    `budget`.`budgetTargetMonthTen`,
                    `budget`.`budgetActualMonthTen`,
                    `budget`.`budgetTargetMonthEleven`,
                    `budget`.`budgetActualMonthEleven`,
                    `budget`.`budgetTargetMonthTwelve`,
                    `budget`.`budgetActualMonthTwelve`,
                    `budget`.`budgetTargetMonthThirteen`,
                    `budget`.`budgetActualMonthThirteen`,
                    `budget`.`budgetTargetMonthFourteen`,
                    `budget`.`budgetActualMonthFourteen`,
                    `budget`.`budgetTargetMonthFifteen`,
                    `budget`.`budgetActualMonthFifteen`,
                    `budget`.`budgetTargetMonthSixteen`,
                    `budget`.`budgetActualMonthSixteen`,
                    `budget`.`budgetTargetMonthSeventeen`,
                    `budget`.`budgetActualMonthSeventeen`,
                    `budget`.`budgetTargetMonthEighteen`,
                    `budget`.`budgetActualMonthEighteen`,
                    `budget`.`budgetTargetTotalYear`,
                    `budget`.`budgetActualTotalYear`,
                    `budget`.`budgetVersion`,
                    `budget`.`isDefault`,
                    `budget`.`isNew`,
                    `budget`.`isDraft`,
                    `budget`.`isUpdate`,
                    `budget`.`isDelete`,
                    `budget`.`isActive`,
                    `budget`.`isApproved`,
                    `budget`.`isReview`,
                    `budget`.`isPost`,
                    `budget`.`isLock`,
                    `budget`.`executeBy`,
                    `budget`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `budget`
		  JOIN      `staff`
		  ON        `budget`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `budget`.`companyId`
	JOIN	`chartofaccount`
	ON		`chartofaccount`.`chartOfAccountId` = `budget`.`chartOfAccountId`
	JOIN	`financeyear`
	ON		`financeyear`.`financeYearId` = `budget`.`financeYearId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getBudgetId(0, 'single')) {
                $sql .= " AND `budget`.`" . $this->model->getPrimaryKeyName() . "`='" . $this->model->getBudgetId(
                                0, 'single'
                        ) . "'";
            }
            if ($this->model->getChartOfAccountId()) {
                $sql .= " AND `budget`.`chartOfAccountId`='" . $this->model->getChartOfAccountId() . "'";
            }
            if ($this->model->getFinanceYearId()) {
                $sql .= " AND `budget`.`financeYearId`='" . $this->model->getFinanceYearId() . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
		  SELECT                    [budget].[budgetId],
                    [company].[companyDescription],
                    [budget].[companyId],
                    [chartOfAccount].[chartOfAccountNumber],
                    [chartOfAccount].[chartOfAccountTitle],
                    [budget].[chartOfAccountId],
                    [financeYear].[financeYearYear],
                    [budget].[financeYearId],
                    [budget].[budgetTargetMonthOne],
                    [budget].[budgetActualMonthOne],
                    [budget].[budgetTargetMonthTwo],
                    [budget].[budgetActualMonthTwo],
                    [budget].[budgetTargetMonthThree],
                    [budget].[budgetActualMonthThree],
                    [budget].[budgetTargetMonthFourth],
                    [budget].[budgetActualMonthFourth],
                    [budget].[budgetTargetMonthFifth],
                    [budget].[budgetActualMonthFifth],
                    [budget].[budgetTargetMonthSix],
                    [budget].[budgetActualMonthSix],
                    [budget].[budgetTargetMonthSeven],
                    [budget].[budgetActualMonthSeven],
                    [budget].[budgetTargetMonthEight],
                    [budget].[budgetActualMonthEight],
                    [budget].[budgetTargetMonthNine],
                    [budget].[budgetActualMonthNine],
                    [budget].[budgetTargetMonthTen],
                    [budget].[budgetActualMonthTen],
                    [budget].[budgetTargetMonthEleven],
                    [budget].[budgetActualMonthEleven],
                    [budget].[budgetTargetMonthTwelve],
                    [budget].[budgetActualMonthTwelve],
                    [budget].[budgetTargetMonthThirteen],
                    [budget].[budgetActualMonthThirteen],
                    [budget].[budgetTargetMonthFourteen],
                    [budget].[budgetActualMonthFourteen],
                    [budget].[budgetTargetMonthFifteen],
                    [budget].[budgetActualMonthFifteen],
                    [budget].[budgetTargetMonthSixteen],
                    [budget].[budgetActualMonthSixteen],
                    [budget].[budgetTargetMonthSeventeen],
                    [budget].[budgetActualMonthSeventeen],
                    [budget].[budgetTargetMonthEighteen],
                    [budget].[budgetActualMonthEighteen],
                    [budget].[budgetTargetTotalYear],
                    [budget].[budgetActualTotalYear],
                    [budget].[budgetVersion],
                    [budget].[isDefault],
                    [budget].[isNew],
                    [budget].[isDraft],
                    [budget].[isUpdate],
                    [budget].[isDelete],
                    [budget].[isActive],
                    [budget].[isApproved],
                    [budget].[isReview],
                    [budget].[isPost],
                    [budget].[isLock],
                    [budget].[executeBy],
                    [budget].[executeTime],
                    [staff].[staffName]
		  FROM 	[budget]
		  JOIN	[staff]
		  ON	[budget].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [budget].[companyId]
	JOIN	[chartOfAccount]
	ON		[chartOfAccount].[chartOfAccountId] = [budget].[chartOfAccountId]
	JOIN	[financeYear]
	ON		[financeYear].[financeYearId] = [budget].[financeYearId]
		  WHERE     " . $this->getAuditFilter();
                if ($this->model->getBudgetId(0, 'single')) {
                    $sql .= " AND [budget].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getBudgetId(0, 'single') . "'";
                }
                if ($this->model->getChartOfAccountId()) {
                    $sql .= " AND [budget].[chartOfAccountId]='" . $this->model->getChartOfAccountId() . "'";
                }
                if ($this->model->getFinanceYearId()) {
                    $sql .= " AND [budget].[financeYearId]='" . $this->model->getFinanceYearId() . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
		  SELECT                    BUDGET.BUDGETID AS \"budgetId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    BUDGET.COMPANYID AS \"companyId\",
                    CHARTOFACCOUNT.CHARTOFACCOUNTNUMBER AS  \"chartOfAccountNumber\",
                    CHARTOFACCOUNT.CHARTOFACCOUNTTITLE AS  \"chartOfAccountTitle\",
                    BUDGET.CHARTOFACCOUNTID AS \"chartOfAccountId\",
                    FINANCEYEAR.FINANCEYEARYEAR AS  \"financeYearYear\",
                    BUDGET.FINANCEYEARID AS \"financeYearId\",
                    BUDGET.BUDGETTARGETMONTHONE AS \"budgetTargetMonthOne\",
                    BUDGET.BUDGETACTUALMONTHONE AS \"budgetActualMonthOne\",
                    BUDGET.BUDGETTARGETMONTHTWO AS \"budgetTargetMonthTwo\",
                    BUDGET.BUDGETACTUALMONTHTWO AS \"budgetActualMonthTwo\",
                    BUDGET.BUDGETTARGETMONTHTHREE AS \"budgetTargetMonthThree\",
                    BUDGET.BUDGETACTUALMONTHTHREE AS \"budgetActualMonthThree\",
                    BUDGET.BUDGETTARGETMONTHFOURTH AS \"budgetTargetMonthFourth\",
                    BUDGET.BUDGETACTUALMONTHFOURTH AS \"budgetActualMonthFourth\",
                    BUDGET.BUDGETTARGETMONTHFIFTH AS \"budgetTargetMonthFifth\",
                    BUDGET.BUDGETACTUALMONTHFIFTH AS \"budgetActualMonthFifth\",
                    BUDGET.BUDGETTARGETMONTHSIX AS \"budgetTargetMonthSix\",
                    BUDGET.BUDGETACTUALMONTHSIX AS \"budgetActualMonthSix\",
                    BUDGET.BUDGETTARGETMONTHSEVEN AS \"budgetTargetMonthSeven\",
                    BUDGET.BUDGETACTUALMONTHSEVEN AS \"budgetActualMonthSeven\",
                    BUDGET.BUDGETTARGETMONTHEIGHT AS \"budgetTargetMonthEight\",
                    BUDGET.BUDGETACTUALMONTHEIGHT AS \"budgetActualMonthEight\",
                    BUDGET.BUDGETTARGETMONTHNINE AS \"budgetTargetMonthNine\",
                    BUDGET.BUDGETACTUALMONTHNINE AS \"budgetActualMonthNine\",
                    BUDGET.BUDGETTARGETMONTHTEN AS \"budgetTargetMonthTen\",
                    BUDGET.BUDGETACTUALMONTHTEN AS \"budgetActualMonthTen\",
                    BUDGET.BUDGETTARGETMONTHELEVEN AS \"budgetTargetMonthEleven\",
                    BUDGET.BUDGETACTUALMONTHELEVEN AS \"budgetActualMonthEleven\",
                    BUDGET.BUDGETTARGETMONTHTWELVE AS \"budgetTargetMonthTwelve\",
                    BUDGET.BUDGETACTUALMONTHTWELVE AS \"budgetActualMonthTwelve\",
                    BUDGET.BUDGETTARGETMONTHTHIRTEEN AS \"budgetTargetMonthThirteen\",
                    BUDGET.BUDGETACTUALMONTHTHIRTEEN AS \"budgetActualMonthThirteen\",
                    BUDGET.BUDGETTARGETMONTHFOURTEEN AS \"budgetTargetMonthFourteen\",
                    BUDGET.BUDGETACTUALMONTHFOURTEEN AS \"budgetActualMonthFourteen\",
                    BUDGET.BUDGETTARGETMONTHFIFTEEN AS \"budgetTargetMonthFifteen\",
                    BUDGET.BUDGETACTUALMONTHFIFTEEN AS \"budgetActualMonthFifteen\",
                    BUDGET.BUDGETTARGETMONTHSIXTEEN AS \"budgetTargetMonthSixteen\",
                    BUDGET.BUDGETACTUALMONTHSIXTEEN AS \"budgetActualMonthSixteen\",
                    BUDGET.BUDGETTARGETMONTHSEVENTEEN AS \"budgetTargetMonthSeventeen\",
                    BUDGET.BUDGETACTUALMONTHSEVENTEEN AS \"budgetActualMonthSeventeen\",
                    BUDGET.BUDGETTARGETMONTHEIGHTEEN AS \"budgetTargetMonthEighteen\",
                    BUDGET.BUDGETACTUALMONTHEIGHTEEN AS \"budgetActualMonthEighteen\",
                    BUDGET.BUDGETTARGETTOTALYEAR AS \"budgetTargetTotalYear\",
                    BUDGET.BUDGETACTUALTOTALYEAR AS \"budgetActualTotalYear\",
                    BUDGET.BUDGETVERSION AS \"budgetVersion\",
                    BUDGET.ISDEFAULT AS \"isDefault\",
                    BUDGET.ISNEW AS \"isNew\",
                    BUDGET.ISDRAFT AS \"isDraft\",
                    BUDGET.ISUPDATE AS \"isUpdate\",
                    BUDGET.ISDELETE AS \"isDelete\",
                    BUDGET.ISACTIVE AS \"isActive\",
                    BUDGET.ISAPPROVED AS \"isApproved\",
                    BUDGET.ISREVIEW AS \"isReview\",
                    BUDGET.ISPOST AS \"isPost\",
                    BUDGET.ISLOCK AS \"isLock\",
                    BUDGET.EXECUTEBY AS \"executeBy\",
                    BUDGET.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\"
		  FROM 	BUDGET
		  JOIN	STAFF
		  ON	BUDGET.EXECUTEBY = STAFF.STAFFID
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = BUDGET.COMPANYID
	JOIN	CHARTOFACCOUNT
	ON		CHARTOFACCOUNT.CHARTOFACCOUNTID = BUDGET.CHARTOFACCOUNTID
	JOIN	FINANCEYEAR
	ON		FINANCEYEAR.FINANCEYEARID = BUDGET.FINANCEYEARID
         WHERE     " . $this->getAuditFilter();
                    if ($this->model->getBudgetId(0, 'single')) {
                        $sql .= " AND BUDGET. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getBudgetId(0, 'single') . "'";
                    }
                    if ($this->model->getChartOfAccountId()) {
                        $sql .= " AND BUDGET.CHARTOFACCOUNTID='" . $this->model->getChartOfAccountId() . "'";
                    }
                    if ($this->model->getFinanceYearId()) {
                        $sql .= " AND BUDGET.FINANCEYEARID='" . $this->model->getFinanceYearId() . "'";
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
                $sql .= " AND `budget`.`" . $this->model->getFilterCharacter() . "` like '" . $this->getCharacterQuery(
                        ) . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [budget].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND Initcap(BUDGET." . strtoupper(
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
                        'budget', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'budget', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'BUDGET', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
                "`budget`.`budgetId`",
                "`staff`.`staffPassword`"
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $filterArray = array(
                    "[budget].[budgetId]",
                    "[staff].[staffPassword]"
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $filterArray = array(
                        "BUDGET.BUDGETID",
                        "STAFF.STAFFPASSWORD"
                    );
                }
            }
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('staff', 'budget', 'chartofaccount', 'financeyear');
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array('staff', 'budget', 'chartofaccount', 'financeyear');
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array('STAFF', 'BUDGET', 'CHARTOFACCOUNT', 'FINANCEYEAR');
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
        if (!($this->model->getBudgetId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 55;
            if ($this->model->getBudgetId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getBudgetId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getBudgetId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else {
            if ($this->getPageOutput() == 'json') {
                if ($this->model->getBudgetId(0, 'single')) {
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
                                                'value', $this->model->getBudgetId(0, 'single')
                                        ),
                                        'nextRecord' => $this->nextRecord('value', $this->model->getBudgetId(0, 'single')),
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
                                        'value', $this->model->getBudgetId(0, 'single')
                                ),
                                'nextRecord' => $this->recordSet->nextRecord(
                                        'value', $this->model->getBudgetId(0, 'single')
                                ),
                                'lastRecord' => $this->recordSet->lastRecord('value'),
                                'data' => $items
                            )
                    );
                    exit();
                }
            }
        }
        return "";
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
        if (!$this->model->getChartOfAccountId()) {
            $this->model->setChartOfAccountId($this->service->getChartOfAccountDefaultValue());
        }
        if (!$this->model->getFinanceYearId()) {
            $this->model->setFinanceYearId($this->service->getFinanceYearDefaultValue());
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`budget`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getBudgetId(0, 'single') . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[budget]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getBudgetId(0, 'single') . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	BUDGET
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getBudgetId(
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
               UPDATE `budget` SET
                       `chartOfAccountId` = '" . $this->model->getChartOfAccountId() . "',
                       `financeYearId` = '" . $this->model->getFinanceYearId() . "',
                       `budgetTargetMonthOne` = '" . $this->model->getBudgetTargetMonthOne() . "',
                       `budgetActualMonthOne` = '" . $this->model->getBudgetActualMonthOne() . "',
                       `budgetTargetMonthTwo` = '" . $this->model->getBudgetTargetMonthTwo() . "',
                       `budgetActualMonthTwo` = '" . $this->model->getBudgetActualMonthTwo() . "',
                       `budgetTargetMonthThree` = '" . $this->model->getBudgetTargetMonthThree() . "',
                       `budgetActualMonthThree` = '" . $this->model->getBudgetActualMonthThree() . "',
                       `budgetTargetMonthFourth` = '" . $this->model->getBudgetTargetMonthFourth() . "',
                       `budgetActualMonthFourth` = '" . $this->model->getBudgetActualMonthFourth() . "',
                       `budgetTargetMonthFifth` = '" . $this->model->getBudgetTargetMonthFifth() . "',
                       `budgetActualMonthFifth` = '" . $this->model->getBudgetActualMonthFifth() . "',
                       `budgetTargetMonthSix` = '" . $this->model->getBudgetTargetMonthSix() . "',
                       `budgetActualMonthSix` = '" . $this->model->getBudgetActualMonthSix() . "',
                       `budgetTargetMonthSeven` = '" . $this->model->getBudgetTargetMonthSeven() . "',
                       `budgetActualMonthSeven` = '" . $this->model->getBudgetActualMonthSeven() . "',
                       `budgetTargetMonthEight` = '" . $this->model->getBudgetTargetMonthEight() . "',
                       `budgetActualMonthEight` = '" . $this->model->getBudgetActualMonthEight() . "',
                       `budgetTargetMonthNine` = '" . $this->model->getBudgetTargetMonthNine() . "',
                       `budgetActualMonthNine` = '" . $this->model->getBudgetActualMonthNine() . "',
                       `budgetTargetMonthTen` = '" . $this->model->getBudgetTargetMonthTen() . "',
                       `budgetActualMonthTen` = '" . $this->model->getBudgetActualMonthTen() . "',
                       `budgetTargetMonthEleven` = '" . $this->model->getBudgetTargetMonthEleven() . "',
                       `budgetActualMonthEleven` = '" . $this->model->getBudgetActualMonthEleven() . "',
                       `budgetTargetMonthTwelve` = '" . $this->model->getBudgetTargetMonthTwelve() . "',
                       `budgetActualMonthTwelve` = '" . $this->model->getBudgetActualMonthTwelve() . "',
                       `budgetTargetMonthThirteen` = '" . $this->model->getBudgetTargetMonthThirteen() . "',
                       `budgetActualMonthThirteen` = '" . $this->model->getBudgetActualMonthThirteen() . "',
                       `budgetTargetMonthFourteen` = '" . $this->model->getBudgetTargetMonthFourteen() . "',
                       `budgetActualMonthFourteen` = '" . $this->model->getBudgetActualMonthFourteen() . "',
                       `budgetTargetMonthFifteen` = '" . $this->model->getBudgetTargetMonthFifteen() . "',
                       `budgetActualMonthFifteen` = '" . $this->model->getBudgetActualMonthFifteen() . "',
                       `budgetTargetMonthSixteen` = '" . $this->model->getBudgetTargetMonthSixteen() . "',
                       `budgetActualMonthSixteen` = '" . $this->model->getBudgetActualMonthSixteen() . "',
                       `budgetTargetMonthSeventeen` = '" . $this->model->getBudgetTargetMonthSeventeen() . "',
                       `budgetActualMonthSeventeen` = '" . $this->model->getBudgetActualMonthSeventeen() . "',
                       `budgetTargetMonthEighteen` = '" . $this->model->getBudgetTargetMonthEighteen() . "',
                       `budgetActualMonthEighteen` = '" . $this->model->getBudgetActualMonthEighteen() . "',
                       `budgetTargetTotalYear` = '" . $this->model->getBudgetTargetTotalYear() . "',
                       `budgetActualTotalYear` = '" . $this->model->getBudgetActualTotalYear() . "',
                       `budgetVersion` = '" . $this->model->getBudgetVersion() . "',
                       `isDefault` = '" . $this->model->getIsDefault('0', 'single') . "',
                       `isNew` = '" . $this->model->getIsNew('0', 'single') . "',
                       `isDraft` = '" . $this->model->getIsDraft('0', 'single') . "',
                       `isUpdate` = '" . $this->model->getIsUpdate('0', 'single') . "',
                       `isDelete` = '" . $this->model->getIsDelete('0', 'single') . "',
                       `isActive` = '" . $this->model->getIsActive('0', 'single') . "',
                       `isApproved` = '" . $this->model->getIsApproved('0', 'single') . "',
                       `isReview` = '" . $this->model->getIsReview('0', 'single') . "',
                       `isPost` = '" . $this->model->getIsPost('0', 'single') . "',
                       `isLock` = '" . $this->model->getIsLock() . "',
                       `executeBy` = '" . $this->model->getExecuteBy('0', 'single') . "',
                       `executeTime` = " . $this->model->getExecuteTime() . "
               WHERE    `budgetId`='" . $this->model->getBudgetId('0', 'single') . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                UPDATE [budget] SET
                       [chartOfAccountId] = '" . $this->model->getChartOfAccountId() . "',
                       [financeYearId] = '" . $this->model->getFinanceYearId() . "',
                       [budgetTargetMonthOne] = '" . $this->model->getBudgetTargetMonthOne() . "',
                       [budgetActualMonthOne] = '" . $this->model->getBudgetActualMonthOne() . "',
                       [budgetTargetMonthTwo] = '" . $this->model->getBudgetTargetMonthTwo() . "',
                       [budgetActualMonthTwo] = '" . $this->model->getBudgetActualMonthTwo() . "',
                       [budgetTargetMonthThree] = '" . $this->model->getBudgetTargetMonthThree() . "',
                       [budgetActualMonthThree] = '" . $this->model->getBudgetActualMonthThree() . "',
                       [budgetTargetMonthFourth] = '" . $this->model->getBudgetTargetMonthFourth() . "',
                       [budgetActualMonthFourth] = '" . $this->model->getBudgetActualMonthFourth() . "',
                       [budgetTargetMonthFifth] = '" . $this->model->getBudgetTargetMonthFifth() . "',
                       [budgetActualMonthFifth] = '" . $this->model->getBudgetActualMonthFifth() . "',
                       [budgetTargetMonthSix] = '" . $this->model->getBudgetTargetMonthSix() . "',
                       [budgetActualMonthSix] = '" . $this->model->getBudgetActualMonthSix() . "',
                       [budgetTargetMonthSeven] = '" . $this->model->getBudgetTargetMonthSeven() . "',
                       [budgetActualMonthSeven] = '" . $this->model->getBudgetActualMonthSeven() . "',
                       [budgetTargetMonthEight] = '" . $this->model->getBudgetTargetMonthEight() . "',
                       [budgetActualMonthEight] = '" . $this->model->getBudgetActualMonthEight() . "',
                       [budgetTargetMonthNine] = '" . $this->model->getBudgetTargetMonthNine() . "',
                       [budgetActualMonthNine] = '" . $this->model->getBudgetActualMonthNine() . "',
                       [budgetTargetMonthTen] = '" . $this->model->getBudgetTargetMonthTen() . "',
                       [budgetActualMonthTen] = '" . $this->model->getBudgetActualMonthTen() . "',
                       [budgetTargetMonthEleven] = '" . $this->model->getBudgetTargetMonthEleven() . "',
                       [budgetActualMonthEleven] = '" . $this->model->getBudgetActualMonthEleven() . "',
                       [budgetTargetMonthTwelve] = '" . $this->model->getBudgetTargetMonthTwelve() . "',
                       [budgetActualMonthTwelve] = '" . $this->model->getBudgetActualMonthTwelve() . "',
                       [budgetTargetMonthThirteen] = '" . $this->model->getBudgetTargetMonthThirteen() . "',
                       [budgetActualMonthThirteen] = '" . $this->model->getBudgetActualMonthThirteen() . "',
                       [budgetTargetMonthFourteen] = '" . $this->model->getBudgetTargetMonthFourteen() . "',
                       [budgetActualMonthFourteen] = '" . $this->model->getBudgetActualMonthFourteen() . "',
                       [budgetTargetMonthFifteen] = '" . $this->model->getBudgetTargetMonthFifteen() . "',
                       [budgetActualMonthFifteen] = '" . $this->model->getBudgetActualMonthFifteen() . "',
                       [budgetTargetMonthSixteen] = '" . $this->model->getBudgetTargetMonthSixteen() . "',
                       [budgetActualMonthSixteen] = '" . $this->model->getBudgetActualMonthSixteen() . "',
                       [budgetTargetMonthSeventeen] = '" . $this->model->getBudgetTargetMonthSeventeen() . "',
                       [budgetActualMonthSeventeen] = '" . $this->model->getBudgetActualMonthSeventeen() . "',
                       [budgetTargetMonthEighteen] = '" . $this->model->getBudgetTargetMonthEighteen() . "',
                       [budgetActualMonthEighteen] = '" . $this->model->getBudgetActualMonthEighteen() . "',
                       [budgetTargetTotalYear] = '" . $this->model->getBudgetTargetTotalYear() . "',
                       [budgetActualTotalYear] = '" . $this->model->getBudgetActualTotalYear() . "',
                       [budgetVersion] = '" . $this->model->getBudgetVersion() . "',
                       [isDefault] = '" . $this->model->getIsDefault(0, 'single') . "',
                       [isNew] = '" . $this->model->getIsNew(0, 'single') . "',
                       [isDraft] = '" . $this->model->getIsDraft(0, 'single') . "',
                       [isUpdate] = '" . $this->model->getIsUpdate(0, 'single') . "',
                       [isDelete] = '" . $this->model->getIsDelete(0, 'single') . "',
                       [isActive] = '" . $this->model->getIsActive(0, 'single') . "',
                       [isApproved] = '" . $this->model->getIsApproved(0, 'single') . "',
                       [isReview] = '" . $this->model->getIsReview(0, 'single') . "',
                       [isPost] = '" . $this->model->getIsPost(0, 'single') . "',
                       [isLock] = '" . $this->model->getIsLock() . "',
                       [executeBy] = '" . $this->model->getExecuteBy(0, 'single') . "',
                       [executeTime] = " . $this->model->getExecuteTime() . "
                WHERE   [budgetId]='" . $this->model->getBudgetId('0', 'single') . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
                UPDATE BUDGET SET
                        CHARTOFACCOUNTID = '" . $this->model->getChartOfAccountId() . "',
                       FINANCEYEARID = '" . $this->model->getFinanceYearId() . "',
                       BUDGETTARGETMONTHONE = '" . $this->model->getBudgetTargetMonthOne() . "',
                       BUDGETACTUALMONTHONE = '" . $this->model->getBudgetActualMonthOne() . "',
                       BUDGETTARGETMONTHTWO = '" . $this->model->getBudgetTargetMonthTwo() . "',
                       BUDGETACTUALMONTHTWO = '" . $this->model->getBudgetActualMonthTwo() . "',
                       BUDGETTARGETMONTHTHREE = '" . $this->model->getBudgetTargetMonthThree() . "',
                       BUDGETACTUALMONTHTHREE = '" . $this->model->getBudgetActualMonthThree() . "',
                       BUDGETTARGETMONTHFOURTH = '" . $this->model->getBudgetTargetMonthFourth() . "',
                       BUDGETACTUALMONTHFOURTH = '" . $this->model->getBudgetActualMonthFourth() . "',
                       BUDGETTARGETMONTHFIFTH = '" . $this->model->getBudgetTargetMonthFifth() . "',
                       BUDGETACTUALMONTHFIFTH = '" . $this->model->getBudgetActualMonthFifth() . "',
                       BUDGETTARGETMONTHSIX = '" . $this->model->getBudgetTargetMonthSix() . "',
                       BUDGETACTUALMONTHSIX = '" . $this->model->getBudgetActualMonthSix() . "',
                       BUDGETTARGETMONTHSEVEN = '" . $this->model->getBudgetTargetMonthSeven() . "',
                       BUDGETACTUALMONTHSEVEN = '" . $this->model->getBudgetActualMonthSeven() . "',
                       BUDGETTARGETMONTHEIGHT = '" . $this->model->getBudgetTargetMonthEight() . "',
                       BUDGETACTUALMONTHEIGHT = '" . $this->model->getBudgetActualMonthEight() . "',
                       BUDGETTARGETMONTHNINE = '" . $this->model->getBudgetTargetMonthNine() . "',
                       BUDGETACTUALMONTHNINE = '" . $this->model->getBudgetActualMonthNine() . "',
                       BUDGETTARGETMONTHTEN = '" . $this->model->getBudgetTargetMonthTen() . "',
                       BUDGETACTUALMONTHTEN = '" . $this->model->getBudgetActualMonthTen() . "',
                       BUDGETTARGETMONTHELEVEN = '" . $this->model->getBudgetTargetMonthEleven() . "',
                       BUDGETACTUALMONTHELEVEN = '" . $this->model->getBudgetActualMonthEleven() . "',
                       BUDGETTARGETMONTHTWELVE = '" . $this->model->getBudgetTargetMonthTwelve() . "',
                       BUDGETACTUALMONTHTWELVE = '" . $this->model->getBudgetActualMonthTwelve() . "',
                       BUDGETTARGETMONTHTHIRTEEN = '" . $this->model->getBudgetTargetMonthThirteen() . "',
                       BUDGETACTUALMONTHTHIRTEEN = '" . $this->model->getBudgetActualMonthThirteen() . "',
                       BUDGETTARGETMONTHFOURTEEN = '" . $this->model->getBudgetTargetMonthFourteen() . "',
                       BUDGETACTUALMONTHFOURTEEN = '" . $this->model->getBudgetActualMonthFourteen() . "',
                       BUDGETTARGETMONTHFIFTEEN = '" . $this->model->getBudgetTargetMonthFifteen() . "',
                       BUDGETACTUALMONTHFIFTEEN = '" . $this->model->getBudgetActualMonthFifteen() . "',
                       BUDGETTARGETMONTHSIXTEEN = '" . $this->model->getBudgetTargetMonthSixteen() . "',
                       BUDGETACTUALMONTHSIXTEEN = '" . $this->model->getBudgetActualMonthSixteen() . "',
                       BUDGETTARGETMONTHSEVENTEEN = '" . $this->model->getBudgetTargetMonthSeventeen() . "',
                       BUDGETACTUALMONTHSEVENTEEN = '" . $this->model->getBudgetActualMonthSeventeen() . "',
                       BUDGETTARGETMONTHEIGHTEEN = '" . $this->model->getBudgetTargetMonthEighteen() . "',
                       BUDGETACTUALMONTHEIGHTEEN = '" . $this->model->getBudgetActualMonthEighteen() . "',
                       BUDGETTARGETTOTALYEAR = '" . $this->model->getBudgetTargetTotalYear() . "',
                       BUDGETACTUALTOTALYEAR = '" . $this->model->getBudgetActualTotalYear() . "',
                       BUDGETVERSION = '" . $this->model->getBudgetVersion() . "',
                       ISDEFAULT = '" . $this->model->getIsDefault(0, 'single') . "',
                       ISNEW = '" . $this->model->getIsNew(0, 'single') . "',
                       ISDRAFT = '" . $this->model->getIsDraft(0, 'single') . "',
                       ISUPDATE = '" . $this->model->getIsUpdate(0, 'single') . "',
                       ISDELETE = '" . $this->model->getIsDelete(0, 'single') . "',
                       ISACTIVE = '" . $this->model->getIsActive(0, 'single') . "',
                       ISAPPROVED = '" . $this->model->getIsApproved(0, 'single') . "',
                       ISREVIEW = '" . $this->model->getIsReview(0, 'single') . "',
                       ISPOST = '" . $this->model->getIsPost(0, 'single') . "',
                       ISLOCK = '" . $this->model->getIsLock() . "',
                       EXECUTEBY = '" . $this->model->getExecuteBy(0, 'single') . "',
                       EXECUTETIME = " . $this->model->getExecuteTime() . "
                WHERE  BUDGETID='" . $this->model->getBudgetId('0', 'single') . "'";
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
        $this->service->setSumBudgetPerYearAccount($this->model->getBudgetId('0', 'single'));
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
           FROM 	`budget`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getBudgetId(0, 'single') . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[budget]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getBudgetId(0, 'single') . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	BUDGET
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getBudgetId(
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
               UPDATE  `budget`
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
               WHERE   `budgetId`   =  '" . $this->model->getBudgetId(0, 'single') . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
               UPDATE  [budget]
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
               WHERE   [budgetId]	=  '" . $this->model->getBudgetId(0, 'single') . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
               UPDATE  BUDGET
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
               WHERE   BUDGETID	=  '" . $this->model->getBudgetId(0, 'single') . "'";
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
     * To check if a key duplicate or not
     */
    function duplicate() {
        
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
     * Return Chart Of Account
     * @return null|string
     */
    public function getChartOfAccount() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getChartOfAccount(
                        $this->model->getChartOfAccountCategoryId(), $this->model->getChartOfAccountTypeId(), $this->model->getChartOfAccountLevel()
        );
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
     * Return Chart Of Account Category
     * @return null|string
     */
    public function getChartOfAccountCategory() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getChartOfAccountCategory();
    }

    /**
     * Return Chart Of Account Type
     * @return null|string
     */
    public function getChartOfAccountType() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getChartOfAccountType($this->model->getChartOfAccountCategoryId());
    }

    /**
     * Return Chart Of Account Segment
     * @return null|string
     */
    public function getChartOfAccountSegment() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getChartOfAccountSegment();
    }

    /**
     * Return Chart Of Account Budget List
     * @return void
     */
    public function getBudgetList() {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(
                array(
                    "success" => true,
                    "data" => $this->service->getBudgetList(
                            $this->getLeafId(), $this->getControllerPath(), $this->model->getFinanceYearId(), $this->model->getFinancePeriodRangeId(), $this->model->getChartOfAccountCategoryId(), $this->model->getChartOfAccountTypeId(), $this->model->getChartOfAccountId()
                    )
                )
        );
        $this->service->setServiceOutput($this->getServiceOutput());
        exit();
    }

    /**
     * Return Single Budget Amount
     * Return json
     * @return void
     */
    public function getBudgetAmount() {
        header('Content-Type:application/json; charset=utf-8');
        $chartOfAccountId = null;
        if ($this->model->getChartOfAccountId()) {
            $chartOfAccountId = $this->model->getChartOfAccountId();
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
        if ($this->model->getChartOfAccountId()) {
            $chartOfAccountId = $this->model->getChartOfAccountId();
        }
        $data = $this->service->getBudgetAmountByYear($chartOfAccountId, $this->model->getFinanceYearId());
        $miniStatement = $this->service->getMiniStatement($chartOfAccountId, $this->model->getFinanceYearId());
        $balanceBudget = $this->service->getBalanceBudget($chartOfAccountId, $this->model->getFinanceYearId());
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
        $budgetAmount = $this->service->getBudgetAmountByPeriod(
                $this->model->getChartOfAccountId(), $this->model->getFinanceYearId(), $this->model->getFinancePeriodRangeId()
        );
        echo json_encode(array("success" => true, "message" => "complete", "budgetAmount" => $budgetAmount));
        exit();
    }

    /**
     * This For Inline Create / Update
     */
    public function checkFirst() {
        if ($this->model->getBudgetId(0, 'single')) {
            $this->service->updateByLineField(
                    $this->model->getBudgetId(0, 'single'), $this->model->getBudgetFieldName(), $this->model->getBudgetFieldValue()
            );
        } else {
            $this->service->createByLineField(
                    $this->model->getChartOfAccountId(), $this->model->getFinanceYearId(), $this->model->getBudgetFieldName(), $this->model->getBudgetFieldValue()
            );
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
                ->setSubject('budget')
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
        $this->excel->getActiveSheet()->getColumnDimension('0')->setAutoSize(true);
        $this->excel->getActiveSheet()->setCellValue('B2', $this->getReportTitle());
        $this->excel->getActiveSheet()->setCellValue('2', '');
        $this->excel->getActiveSheet()->mergeCells('B2:2');
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.');
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['chartOfAccountIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['financeYearIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['budgetTargetMonthOneLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['budgetActualMonthOneLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['budgetTargetMonthTwoLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['budgetActualMonthTwoLabel']);
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['budgetTargetMonthThreeLabel']);
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['budgetActualMonthThreeLabel']);
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['budgetTargetMonthFourthLabel']);
        $this->excel->getActiveSheet()->setCellValue('L3', $this->translate['budgetActualMonthFourthLabel']);
        $this->excel->getActiveSheet()->setCellValue('M3', $this->translate['budgetTargetMonthFifthLabel']);
        $this->excel->getActiveSheet()->setCellValue('N3', $this->translate['budgetActualMonthFifthLabel']);
        $this->excel->getActiveSheet()->setCellValue('O3', $this->translate['budgetTargetMonthSixLabel']);
        $this->excel->getActiveSheet()->setCellValue('P3', $this->translate['budgetActualMonthSixLabel']);
        $this->excel->getActiveSheet()->setCellValue('Q3', $this->translate['budgetTargetMonthSevenLabel']);
        $this->excel->getActiveSheet()->setCellValue('R3', $this->translate['budgetActualMonthSevenLabel']);
        $this->excel->getActiveSheet()->setCellValue('S3', $this->translate['budgetTargetMonthEightLabel']);
        $this->excel->getActiveSheet()->setCellValue('T3', $this->translate['budgetActualMonthEightLabel']);
        $this->excel->getActiveSheet()->setCellValue('U3', $this->translate['budgetTargetMonthNineLabel']);
        $this->excel->getActiveSheet()->setCellValue('V3', $this->translate['budgetActualMonthNineLabel']);
        $this->excel->getActiveSheet()->setCellValue('W3', $this->translate['budgetTargetMonthTenLabel']);
        $this->excel->getActiveSheet()->setCellValue('X3', $this->translate['budgetActualMonthTenLabel']);
        $this->excel->getActiveSheet()->setCellValue('Y3', $this->translate['budgetTargetMonthElevenLabel']);
        $this->excel->getActiveSheet()->setCellValue('Z3', $this->translate['budgetActualMonthElevenLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['budgetTargetMonthTwelveLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['budgetActualMonthTwelveLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['budgetTargetMonthThirteenLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['budgetActualMonthThirteenLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['budgetTargetMonthFourteenLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['budgetActualMonthFourteenLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['budgetTargetMonthFifteenLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['budgetActualMonthFifteenLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['budgetTargetMonthSixteenLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['budgetActualMonthSixteenLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['budgetTargetMonthSeventeenLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['budgetActualMonthSeventeenLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['budgetTargetMonthEighteenLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['budgetActualMonthEighteenLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['budgetTargetTotalYearLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['budgetActualTotalYearLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['budgetVersionLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['isLockLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['executeByLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['executeTimeLabel']);
        // 
        $loopRow = 4;
        $i = 0;
        \PHPExcel_Cell::setValueBinder(new \PHPExcel_Cell_AdvancedValueBinder());
        $lastRow = null;
        while (($row = $this->q->fetchAssoc()) == true) {
            //	echo print_r($row); 
            $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i);
            $this->excel->getActiveSheet()->setCellValue(
                    'C' . $loopRow, strip_tags($row ['chartOfAccountDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue('D' . $loopRow, strip_tags($row ['financeYearDescription']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('E' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('E' . $loopRow, strip_tags($row ['budgetTargetMonthOne']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('F' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('F' . $loopRow, strip_tags($row ['budgetActualMonthOne']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('G' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, strip_tags($row ['budgetTargetMonthTwo']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('H' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('H' . $loopRow, strip_tags($row ['budgetActualMonthTwo']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('I' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('I' . $loopRow, strip_tags($row ['budgetTargetMonthThree']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('J' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('J' . $loopRow, strip_tags($row ['budgetActualMonthThree']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('K' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('K' . $loopRow, strip_tags($row ['budgetTargetMonthFourth']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('L' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('L' . $loopRow, strip_tags($row ['budgetActualMonthFourth']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('M' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('M' . $loopRow, strip_tags($row ['budgetTargetMonthFifth']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('N' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('N' . $loopRow, strip_tags($row ['budgetActualMonthFifth']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('O' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('O' . $loopRow, strip_tags($row ['budgetTargetMonthSix']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('P' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('P' . $loopRow, strip_tags($row ['budgetActualMonthSix']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('Q' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('Q' . $loopRow, strip_tags($row ['budgetTargetMonthSeven']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('R' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('R' . $loopRow, strip_tags($row ['budgetActualMonthSeven']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('S' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('S' . $loopRow, strip_tags($row ['budgetTargetMonthEight']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('T' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('T' . $loopRow, strip_tags($row ['budgetActualMonthEight']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('U' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('U' . $loopRow, strip_tags($row ['budgetTargetMonthNine']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('V' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('V' . $loopRow, strip_tags($row ['budgetActualMonthNine']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('W' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('W' . $loopRow, strip_tags($row ['budgetTargetMonthTen']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('X' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('X' . $loopRow, strip_tags($row ['budgetActualMonthTen']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('Y' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('Y' . $loopRow, strip_tags($row ['budgetTargetMonthEleven']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('Z' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('Z' . $loopRow, strip_tags($row ['budgetActualMonthEleven']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['budgetTargetMonthTwelve']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['budgetActualMonthTwelve']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['budgetTargetMonthThirteen']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['budgetActualMonthThirteen']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['budgetTargetMonthFourteen']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['budgetActualMonthFourteen']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['budgetTargetMonthFifteen']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['budgetActualMonthFifteen']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['budgetTargetMonthSixteen']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['budgetActualMonthSixteen']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue(
                    '' . $loopRow, strip_tags($row ['budgetTargetMonthSeventeen'])
            );
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue(
                    '' . $loopRow, strip_tags($row ['budgetActualMonthSeventeen'])
            );
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['budgetTargetMonthEighteen']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['budgetActualMonthEighteen']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['budgetTargetTotalYear']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['budgetActualTotalYear']));
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['budgetVersion']));
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['isLock']));
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['staffName']));
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['executeTime']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $loopRow++;
            $lastRow = '' . $loopRow;
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
                $filename = "budget" . rand(0, 10000000) . $extension;
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
                $filename = "budget" . rand(0, 10000000) . $extension;
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
                $filename = "budget" . rand(0, 10000000) . $extension;
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
                $filename = "budget" . rand(0, 10000000) . $extension;
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
        $budgetObject = new BudgetClass ();
        if ($_POST['securityToken'] != $budgetObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $budgetObject->setLeafId($_POST ['leafId']);
        }
        if (isset($_POST ['offset'])) {
            $budgetObject->setStart($_POST ['offset']);
        }
        if (isset($_POST ['limit'])) {
            $budgetObject->setLimit($_POST ['limit']);
        }
        $budgetObject->setPageOutput($_POST['output']);
        $budgetObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $budgetObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $budgetObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $budgetObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $budgetObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$budgetObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$budgetObject->delete(); 
        }
        if ($_POST['method'] == 'checkFirst') {
            $budgetObject->checkFirst();
        }
    }
}
if (isset($_GET ['method'])) {
    $budgetObject = new BudgetClass ();
    if ($_GET['securityToken'] != $budgetObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $budgetObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $budgetObject->execute();
    /*
     * Update Status of The Table. Admin Level Only 
     */
    if ($_GET ['method'] == 'updateStatus') {
        $budgetObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key 
     */
    if ($_GET['method'] == 'duplicate') {
        $budgetObject->duplicate();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $budgetObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $budgetObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $budgetObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $budgetObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $budgetObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $budgetObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        $budgetObject->setServiceOutput('option');
        if (($_GET['filter'] == 'chartOfAccount')) {
            $budgetObject->getChartOfAccount();
        }
        if (($_GET['filter'] == 'chartOfAccountCategory')) {
            $budgetObject->getChartOfAccountCategory();
        }
        if (($_GET['filter'] == 'chartOfAccountType')) {
            $budgetObject->getChartOfAccountType();
        }
        if (($_GET['filter'] == 'chartOfAccountSegment')) {
            $budgetObject->getChartOfAccountSegment();
        }
        if (($_GET['filter'] == 'budgetList')) {
            $budgetObject->getBudgetList();
        }
        if (($_GET['filter'] == 'financeYear')) {
            $budgetObject->getFinanceYear();
        }
        if (($_GET['filter'] == 'financePeriodRange')) {
            $budgetObject->getFinancePeriodRange();
        }
        // filtering budget
        if (($_GET['filter'] == 'budgetAmount')) {
            $budgetObject->getBudgetAmount();
        }
        if (($_GET['filter'] == 'budgetAmountByYear')) {

            $budgetObject->getBudgetAmountByYear();
        }
        if (($_GET['filter'] == 'budgetAmountByPeriod')) {
            $budgetObject->getBudgetAmountByPeriod();
        }
    }
}
?>
