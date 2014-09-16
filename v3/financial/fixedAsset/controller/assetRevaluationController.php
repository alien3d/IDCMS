<?php

namespace Core\Financial\FixedAsset\AssetRevaluation\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\Financial\FixedAsset\AssetRevaluation\Model\AssetRevaluationModel;
use Core\Financial\FixedAsset\AssetRevaluation\Service\AssetRevaluationService;
use Core\RecordSet\RecordSet;
use Core\shared\SharedClass;

if (!isset($_SESSION)) {
    session_start();
}
// using Absolute path instead of relative path..
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
require_once($newFakeDocumentRoot . "v3/financial/fixedAsset/model/assetRevaluationModel.php");
require_once($newFakeDocumentRoot . "v3/financial/fixedAsset/service/assetRevaluationService.php");

/**
 * Class AssetRevaluationClass
 * Asset Revaluation Controller
 * a revaluation of fixed assets is a technique that may be required to accurately describe the true value of the capital goods a business owns. This should be distinguished from planned depreciation, where the recorded decline in value of an asset is tied to its age.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\FixedAsset\AssetRevaluation\Controller
 * @subpackage Asset
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class AssetRevaluationClass extends ConfigClass {

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
     * @var \Core\Financial\FixedAsset\AssetRevaluation\Model\AssetRevaluationModel
     */
    public $model;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     * @var \PHPPowerPoint
     */
    //private $powerPoint;
    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\Financial\FixedAsset\AssetRevaluation\Service\AssetRevaluationService
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
     * Translation
     * @var mixed
     */
    public $t;

    /**
     * Leaf Access
     * @var mixed
     */
    public $leafAccess;

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
     * @var \Core\Document\Trail\DocumentTrailClass
     */
    private $documentTrail;

    /**
     * Companyt
     * @var int
     */
    private $companyId;

    /**
     * itemCategoryIdt
     * @var int
     */
    private $itemCategoryId;

    /**
     * itemTypeIdt
     * @var int
     */
    private $itemTypeId;

    /**
     * assetIdt
     * @var int
     */
    private $assetId;

    /**
     * Constructor
     */
    function __construct() {
        $this->translate = array();
        $this->t = array();
        $this->leafAccess = array();
        $this->systemFormat = array();
        $this->setViewPath("./v3/financial/fixedAsset/view/assetRevaluation.php");
        $this->setControllerPath("./v3/financial/fixedAsset/controller/assetRevaluationController.php");
        $this->setServicePath("./v3/financial/fixedAsset/service/assetRevaluationService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(0);
        $this->setLog(1);
        $this->model = new AssetRevaluationModel();
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

        $this->service = new AssetRevaluationService();
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
            INSERT INTO `assetrevaluation` 
            (
                 `companyId`,
                 `itemCategoryId`,
                 `itemTypeId`,
                 `assetId`,
                 `documentNumber`,
                 `transactionDate`,
                 `financePeriod`,
                 `financeYear`,
                 `assetRevaluationReference`,
                 `assetRevaluationPrice`,
                 `assetRevaluationValue`,
                 `assetRevaluationNetBookBalue`,
                 `assetPrice`,
                 `yearToDate`,
                 `currentNetBookValue`,
                 `assetRevaluationComment`,
                 `assetRevaluationProfit`,
                 `depreciationPeriod`,
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
                 '" . $this->model->getCompanyId() . "',
                 '" . $this->model->getItemCategoryId() . "',
                 '" . $this->model->getItemTypeId() . "',
                 '" . $this->model->getAssetId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getTransactionDate() . "',
                 '" . $this->model->getFinancePeriod() . "',
                 '" . $this->model->getFinanceYear() . "',
                 '" . $this->model->getAssetRevaluationReference() . "',
                 '" . $this->model->getAssetRevaluationPrice() . "',
                 '" . $this->model->getAssetRevaluationValue() . "',
                 '" . $this->model->getAssetRevaluationNetBookValue() . "',
                 '" . $this->model->getAssetPrice() . "',
                 '" . $this->model->getYearToDate() . "',
                 '" . $this->model->getCurrentNetBookValue() . "',
                 '" . $this->model->getAssetRevaluationComment() . "',
                 '" . $this->model->getAssetRevaluationProfit() . "',
                 '" . $this->model->getDepreciationPeriod() . "',
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
            INSERT INTO [assetRevaluation]
            (
                 [assetRevaluationId],
                 [companyId],
                 [itemCategoryId],
                 [itemTypeId],
                 [assetId],
                 [documentNumber],
                 [transactionDate],
                 [financePeriod],
                 [financeYear],
                 [assetRevaluationReference],
                 [assetRevaluationPrice],
                 [assetRevaluationValue],
                 [assetRevaluationNetBookBalue],
                 [assetPrice],
                 [yearToDate],
                 [currentNetBookValue],
                 [assetRevaluationComment],
                 [assetRevaluationProfit],
                 [depreciationPeriod],
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
                 '" . $this->model->getCompanyId() . "',
                 '" . $this->model->getItemCategoryId() . "',
                 '" . $this->model->getItemTypeId() . "',
                 '" . $this->model->getAssetId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getTransactionDate() . "',
                 '" . $this->model->getFinancePeriod() . "',
                 '" . $this->model->getFinanceYear() . "',
                 '" . $this->model->getAssetRevaluationReference() . "',
                 '" . $this->model->getAssetRevaluationPrice() . "',
                 '" . $this->model->getAssetRevaluationValue() . "',
                 '" . $this->model->getAssetRevaluationNetBookValue() . "',
                 '" . $this->model->getAssetPrice() . "',
                 '" . $this->model->getYearToDate() . "',
                 '" . $this->model->getCurrentNetBookValue() . "',
                 '" . $this->model->getAssetRevaluationComment() . "',
                 '" . $this->model->getAssetRevaluationProfit() . "',
                 '" . $this->model->getDepreciationPeriod() . "',
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
            INSERT INTO ASSETREVALUATION
            (
                 COMPANYID,
                 ITEMCATEGORYID,
                 ITEMTYPEID,
                 ASSETID,
                 DOCUMENTNO,
                 TRANSACTIONDATE,
                 FINANCEPERIOD,
                 FINANCEYEAR,
                 ASSETREVALUATIONREFERENCE,
                 ASSETREVALUATIONPRICE,
                 ASSETREVALUATIONVALUE,
                 ASSETREVALUATIONNETBOOKBALUE,
                 ASSETPRICE,
                 YEARTODATE,
                 CURRENTNETBOOKVALUE,
                 ASSETREVALUATIONCOMMENT,
                 ASSETREVALUATIONPROFIT,
                 DEPRECIATIONPERIOD,
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
                 '" . $this->model->getCompanyId() . "',
                 '" . $this->model->getItemCategoryId() . "',
                 '" . $this->model->getItemTypeId() . "',
                 '" . $this->model->getAssetId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getTransactionDate() . "',
                 '" . $this->model->getFinancePeriod() . "',
                 '" . $this->model->getFinanceYear() . "',
                 '" . $this->model->getAssetRevaluationReference() . "',
                 '" . $this->model->getAssetRevaluationPrice() . "',
                 '" . $this->model->getAssetRevaluationValue() . "',
                 '" . $this->model->getAssetRevaluationNetBookValue() . "',
                 '" . $this->model->getAssetPrice() . "',
                 '" . $this->model->getYearToDate() . "',
                 '" . $this->model->getCurrentNetBookValue() . "',
                 '" . $this->model->getAssetRevaluationComment() . "',
                 '" . $this->model->getAssetRevaluationProfit() . "',
                 '" . $this->model->getDepreciationPeriod() . "',
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
        $assetRevaluationId = $this->q->lastInsertId();

        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => true,
                    "message" => $this->t['newRecordTextLabel'],
                    "assetRevaluationId" => $assetRevaluationId,
                    "time" => $time
                )
        );
        exit();
    }

    /**
     * Read
     * @see config::read()
     */
    public function read() {
        if ($this->getPageOutput() == 'json') {
            header('Content-Type:application/json; charset=utf-8');
        }
        $start = microtime(true);
        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] == 0) {
                if ($this->getVendor() == self::MYSQL) {
                    $this->setAuditFilter(" `assetrevaluation`.`isActive` = 1 ");
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(" [assetRevaluation].[isActive] = 1 ");
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(" ASSETREVALUATION.ISACTIVE = 1 ");
                        }
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter(" 1 = 1	");
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(" 1 = 1 ");
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(" 1 = 1 ");
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
		  SELECT                    `assetrevaluation`.`assetRevaluationId`,
                    `company`.`companyDescription`,
                    `itemcategory`.`itemCategoryDesc`,
                    `itemtype`.`itemTypeDesc`,
                    `asset`.`assetDesc`,
                    `assetrevaluation`.`documentNumber`,
                    `assetrevaluation`.`transactionDate`,
                    `assetrevaluation`.`financePeriod`,
                    `assetrevaluation`.`financeYear`,
                    `assetrevaluation`.`assetRevaluationReference`,
                    `assetrevaluation`.`assetRevaluationPrice`,
                    `assetrevaluation`.`assetRevaluationValue`,
                    `assetrevaluation`.`assetRevaluationNetBookBalue`,
                    `assetrevaluation`.`assetPrice`,
                    `assetrevaluation`.`yearToDate`,
                    `assetrevaluation`.`currentNetBookValue`,
                    `assetrevaluation`.`assetRevaluationComment`,
                    `assetrevaluation`.`assetRevaluationProfit`,
                    `assetrevaluation`.`depreciationPeriod`,
                    `assetrevaluation`.`isDefault`,
                    `assetrevaluation`.`isNew`,
                    `assetrevaluation`.`isDraft`,
                    `assetrevaluation`.`isUpdate`,
                    `assetrevaluation`.`isDelete`,
                    `assetrevaluation`.`isActive`,
                    `assetrevaluation`.`isApproved`,
                    `assetrevaluation`.`isReview`,
                    `assetrevaluation`.`isPost`,
                    `assetrevaluation`.`executeBy`,
                    `assetrevaluation`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `assetrevaluation`
		  JOIN      `staff`
		  ON        `assetrevaluation`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `assetrevaluation`.`companyId`
	JOIN	`itemcategory`
	ON		`itemcategory`.`itemCategoryId` = `assetrevaluation`.`itemCategoryId`
	JOIN	`itemtype`
	ON		`itemtype`.`itemTypeId` = `assetrevaluation`.`itemTypeId`
	JOIN	`asset`
	ON		`asset`.`assetId` = `assetrevaluation`.`assetId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getAssetRevaluationId(0, 'single')) {
                $sql .= " AND `assetrevaluation`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getAssetRevaluationId(0, 'single') . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
		  SELECT                    [assetRevaluation].[assetRevaluationId],
                    [company].[companyDescription],
                    [itemCategory].[itemCategoryDesc],
                    [itemType].[itemTypeDesc],
                    [asset].[assetDesc],
                    [assetRevaluation].[documentNumber],
                    [assetRevaluation].[transactionDate],
                    [assetRevaluation].[financePeriod],
                    [assetRevaluation].[financeYear],
                    [assetRevaluation].[assetRevaluationReference],
                    [assetRevaluation].[assetRevaluationPrice],
                    [assetRevaluation].[assetRevaluationValue],
                    [assetRevaluation].[assetRevaluationNetBookBalue],
                    [assetRevaluation].[assetPrice],
                    [assetRevaluation].[yearToDate],
                    [assetRevaluation].[currentNetBookValue],
                    [assetRevaluation].[assetRevaluationComment],
                    [assetRevaluation].[assetRevaluationProfit],
                    [assetRevaluation].[depreciationPeriod],
                    [assetRevaluation].[isDefault],
                    [assetRevaluation].[isNew],
                    [assetRevaluation].[isDraft],
                    [assetRevaluation].[isUpdate],
                    [assetRevaluation].[isDelete],
                    [assetRevaluation].[isActive],
                    [assetRevaluation].[isApproved],
                    [assetRevaluation].[isReview],
                    [assetRevaluation].[isPost],
                    [assetRevaluation].[executeBy],
                    [assetRevaluation].[executeTime],
                    [staff].[staffName]
		  FROM 	[assetRevaluation]
		  JOIN	[staff]
		  ON	[assetRevaluation].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [assetRevaluation].[companyId]
	JOIN	[itemCategory]
	ON		[itemCategory].[itemCategoryId] = [assetRevaluation].[itemCategoryId]
	JOIN	[itemType]
	ON		[itemType].[itemTypeId] = [assetRevaluation].[itemTypeId]
	JOIN	[asset]
	ON		[asset].[assetId] = [assetRevaluation].[assetId]
		  WHERE     " . $this->getAuditFilter();
                if ($this->model->getAssetRevaluationId(0, 'single')) {
                    $sql .= " AND [assetRevaluation].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getAssetRevaluationId(0, 'single') . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
		  SELECT                    ASSETREVALUATION.ASSETREVALUATIONID,
                    COMPANY.COMPANYID,
                    ITEMCATEGORY.ITEMCATEGORYID,
                    ITEMTYPE.ITEMTYPEID,
                    ASSET.ASSETID,
                    ASSETREVALUATION.DOCUMENTNO,
                    ASSETREVALUATION.TRANSACTIONDATE,
                    ASSETREVALUATION.FINANCEPERIOD,
                    ASSETREVALUATION.FINANCEYEAR,
                    ASSETREVALUATION.ASSETREVALUATIONREFERENCE,
                    ASSETREVALUATION.ASSETREVALUATIONPRICE,
                    ASSETREVALUATION.ASSETREVALUATIONVALUE,
                    ASSETREVALUATION.ASSETREVALUATIONNETBOOKBALUE,
                    ASSETREVALUATION.ASSETPRICE,
                    ASSETREVALUATION.YEARTODATE,
                    ASSETREVALUATION.CURRENTNETBOOKVALUE,
                    ASSETREVALUATION.ASSETREVALUATIONCOMMENT,
                    ASSETREVALUATION.ASSETREVALUATIONPROFIT,
                    ASSETREVALUATION.DEPRECIATIONPERIOD,
                    ASSETREVALUATION.ISDEFAULT,
                    ASSETREVALUATION.ISNEW,
                    ASSETREVALUATION.ISDRAFT,
                    ASSETREVALUATION.ISUPDATE,
                    ASSETREVALUATION.ISDELETE,
                    ASSETREVALUATION.ISACTIVE,
                    ASSETREVALUATION.ISAPPROVED,
                    ASSETREVALUATION.ISREVIEW,
                    ASSETREVALUATION.ISPOST,
                    ASSETREVALUATION.EXECUTEBY,
                    ASSETREVALUATION.EXECUTETIME,
                    STAFF.STAFFNAME
		  FROM 	ASSETREVALUATION
		  JOIN	STAFF
		  ON	ASSETREVALUATION.EXECUTEBY = STAFF.STAFFID
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = ASSETREVALUATION.COMPANYID
	JOIN	ITEMCATEGORY
	ON		ITEMCATEGORY.ITEMCATEGORYID = ASSETREVALUATION.ITEMCATEGORYID
	JOIN	ITEMTYPE
	ON		ITEMTYPE.ITEMTYPEID = ASSETREVALUATION.ITEMTYPEID
	JOIN	ASSET
	ON		ASSET.ASSETID = ASSETREVALUATION.ASSETID
         WHERE     " . $this->getAuditFilter();
                    if ($this->model->getAssetRevaluationId(0, 'single')) {
                        $sql .= " AND ASSETREVALUATION. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getAssetRevaluationId(0, 'single') . "'";
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
                $sql .= " AND `assetrevaluation`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [assetRevaluation].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND ASSETREVALUATION." . strtoupper(
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
                        'assetrevaluation', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'assetrevaluation', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'ASSETREVALUATION', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
        $filterArray = array('assetRevaluationId');
        /**
         * filter table
         * @variables $tableArray
         */
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('assetrevaluation');
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array('assetrevaluation');
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array('ASSETREVALUATION');
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

                    $sql = "WITH [assetrevaluationDerived] AS
							(
								SELECT 										[assetRevaluation].[assetRevaluationId],
										[company].[companyDescription],
										[itemCategory].[itemCategoryDesc],
										[itemType].[itemTypeDesc],
										[asset].[assetDesc],
										[assetRevaluation].[documentNumber],
										[assetRevaluation].[transactionDate],
										[assetRevaluation].[financePeriod],
										[assetRevaluation].[financeYear],
										[assetRevaluation].[assetRevaluationReference],
										[assetRevaluation].[assetRevaluationPrice],
										[assetRevaluation].[assetRevaluationValue],
										[assetRevaluation].[assetRevaluationNetBookBalue],
										[assetRevaluation].[assetPrice],
										[assetRevaluation].[yearToDate],
										[assetRevaluation].[currentNetBookValue],
										[assetRevaluation].[assetRevaluationComment],
										[assetRevaluation].[assetRevaluationProfit],
										[assetRevaluation].[depreciationPeriod],
										[assetRevaluation].[isDefault],
										[assetRevaluation].[isNew],
										[assetRevaluation].[isDraft],
										[assetRevaluation].[isUpdate],
										[assetRevaluation].[isDelete],
										[assetRevaluation].[isActive],
										[assetRevaluation].[isApproved],
										[assetRevaluation].[isReview],
										[assetRevaluation].[isPost],
										[assetRevaluation].[executeBy],
										[assetRevaluation].[executeTime],
										[staff].[staffName],
										ROW_NUMBER() OVER (ORDER BY [assetRevaluation].[assetRevaluationId]) AS 'RowNumber'
							     FROM 	[assetRevaluation]
							     JOIN	[staff]
							     ON	[assetRevaluation].[executeBy] = [staff].[staffId]
							     JOIN   [company]
							     ON     [company].[companyId] = [assetRevaluation].[companyId]
							     JOIN   [itemCategory]
							     ON     [itemCategory].[itemCategoryId] = [assetRevaluation].[itemCategoryId]
							     JOIN   [itemType]
							     ON     [itemType].[itemTypeId] = [assetRevaluation].[itemTypeId]
							     JOIN   [asset]
							     ON     [asset].[assetId] = [assetRevaluation].[assetId]
							     WHERE 		" . $this->getAuditFilter() . "

							)
							SELECT		*
							FROM 		[assetrevaluationDerived]
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
SELECT							     ASSETREVALUATION.ASSETREVALUATIONID,
							     COMPANY.COMPANYID,
							     ITEMCATEGORY.ITEMCATEGORYID,
							     ITEMTYPE.ITEMTYPEID,
							     ASSET.ASSETID,
							     ASSETREVALUATION.DOCUMENTNO,
							     ASSETREVALUATION.TRANSACTIONDATE,
							     ASSETREVALUATION.FINANCEPERIOD,
							     ASSETREVALUATION.FINANCEYEAR,
							     ASSETREVALUATION.ASSETREVALUATIONREFERENCE,
							     ASSETREVALUATION.ASSETREVALUATIONPRICE,
							     ASSETREVALUATION.ASSETREVALUATIONVALUE,
							     ASSETREVALUATION.ASSETREVALUATIONNETBOOKBALUE,
							     ASSETREVALUATION.ASSETPRICE,
							     ASSETREVALUATION.YEARTODATE,
							     ASSETREVALUATION.CURRENTNETBOOKVALUE,
							     ASSETREVALUATION.ASSETREVALUATIONCOMMENT,
							     ASSETREVALUATION.ASSETREVALUATIONPROFIT,
							     ASSETREVALUATION.DEPRECIATIONPERIOD,
							     ASSETREVALUATION.ISDEFAULT,
							     ASSETREVALUATION.ISNEW,
							     ASSETREVALUATION.ISDRAFT,
							     ASSETREVALUATION.ISUPDATE,
							     ASSETREVALUATION.ISDELETE,
							     ASSETREVALUATION.ISACTIVE,
							     ASSETREVALUATION.ISAPPROVED,
							     ASSETREVALUATION.ISREVIEW,
							     ASSETREVALUATION.ISPOST,
							     ASSETREVALUATION.EXECUTEBY,
							     ASSETREVALUATION.EXECUTETIME,
                                   STAFF.STAFFNAME
							     FROM 	ASSETREVALUATION
							     JOIN	  STAFF
							     ON		ASSETREVALUATION.EXECUTEBY = STAFF.STAFFID
							     JOIN   COMPANY
							     ON     COMPANY.COMPANYID = ASSETREVALUATION.COMPANYID
							     JOIN   ITEMCATEGORY
							     ON     ITEMCATEGORY.ITEMCATEGORYID = ASSETREVALUATION.ITEMCATEGORYID
							     JOIN   ITEMTYPE
							     ON     ITEMTYPE.ITEMTYPEID = ASSETREVALUATION.ITEMTYPEID
							     JOIN   ASSET
							     ON     ASSET.ASSETID = ASSETREVALUATION.ASSETID
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
        if (!($this->model->getAssetRevaluationId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 30;
            if ($this->model->getAssetRevaluationId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord(
                        'value', $this->model->getAssetRevaluationId(0, 'single')
                );
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getAssetRevaluationId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else {
            if ($this->getPageOutput() == 'json') {
                if ($this->model->getAssetRevaluationId(0, 'single')) {
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
                                                'value', $this->model->getAssetRevaluationId(0, 'single')
                                        ),
                                        'nextRecord' => $this->nextRecord(
                                                'value', $this->model->getAssetRevaluationId(0, 'single')
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
                                        'value', $this->model->getAssetRevaluationId(0, 'single')
                                ),
                                'nextRecord' => $this->recordSet->nextRecord(
                                        'value', $this->model->getAssetRevaluationId(0, 'single')
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
            $this->q->fast($sql);
        }
        $this->q->start();
        $this->model->update();
        // before updating check the id exist or not . if exist continue to update else warning the user
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`assetrevaluation`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getAssetRevaluationId(
                            0, 'single'
                    ) . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[assetRevaluation]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getAssetRevaluationId(
                                0, 'single'
                        ) . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	ASSETREVALUATION
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getAssetRevaluationId(
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
               UPDATE `assetrevaluation` SET
                       `companyId` = '" . $this->model->getCompanyId() . "',
                       `itemCategoryId` = '" . $this->model->getItemCategoryId() . "',
                       `itemTypeId` = '" . $this->model->getItemTypeId() . "',
                       `assetId` = '" . $this->model->getAssetId() . "',
                       `documentNumber` = '" . $this->model->getDocumentNumber() . "',
                       `transactionDate` = '" . $this->model->getTransactionDate() . "',
                       `financePeriod` = '" . $this->model->getFinancePeriod() . "',
                       `financeYear` = '" . $this->model->getFinanceYear() . "',
                       `assetRevaluationReference` = '" . $this->model->getAssetRevaluationReference() . "',
                       `assetRevaluationPrice` = '" . $this->model->getAssetRevaluationPrice() . "',
                       `assetRevaluationValue` = '" . $this->model->getAssetRevaluationValue() . "',
                       `assetRevaluationNetBookBalue` = '" . $this->model->getAssetRevaluationNetBookValue() . "',
                       `assetPrice` = '" . $this->model->getAssetPrice() . "',
                       `yearToDate` = '" . $this->model->getYearToDate() . "',
                       `currentNetBookValue` = '" . $this->model->getCurrentNetBookValue() . "',
                       `assetRevaluationComment` = '" . $this->model->getAssetRevaluationComment() . "',
                       `assetRevaluationProfit` = '" . $this->model->getAssetRevaluationProfit() . "',
                       `depreciationPeriod` = '" . $this->model->getDepreciationPeriod() . "',
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
               WHERE    `assetRevaluationId`='" . $this->model->getAssetRevaluationId('0', 'single') . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                UPDATE [assetRevaluation] SET
                       [companyId] = '" . $this->model->getCompanyId() . "',
                       [itemCategoryId] = '" . $this->model->getItemCategoryId() . "',
                       [itemTypeId] = '" . $this->model->getItemTypeId() . "',
                       [assetId] = '" . $this->model->getAssetId() . "',
                       [documentNumber] = '" . $this->model->getDocumentNumber() . "',
                       [transactionDate] = '" . $this->model->getTransactionDate() . "',
                       [financePeriod] = '" . $this->model->getFinancePeriod() . "',
                       [financeYear] = '" . $this->model->getFinanceYear() . "',
                       [assetRevaluationReference] = '" . $this->model->getAssetRevaluationReference() . "',
                       [assetRevaluationPrice] = '" . $this->model->getAssetRevaluationPrice() . "',
                       [assetRevaluationValue] = '" . $this->model->getAssetRevaluationValue() . "',
                       [assetRevaluationNetBookBalue] = '" . $this->model->getAssetRevaluationNetBookValue() . "',
                       [assetPrice] = '" . $this->model->getAssetPrice() . "',
                       [yearToDate] = '" . $this->model->getYearToDate() . "',
                       [currentNetBookValue] = '" . $this->model->getCurrentNetBookValue() . "',
                       [assetRevaluationComment] = '" . $this->model->getAssetRevaluationComment() . "',
                       [assetRevaluationProfit] = '" . $this->model->getAssetRevaluationProfit() . "',
                       [depreciationPeriod] = '" . $this->model->getDepreciationPeriod() . "',
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
                WHERE   [assetRevaluationId]='" . $this->model->getAssetRevaluationId('0', 'single') . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
                UPDATE ASSETREVALUATION SET
                       ITEMCATEGORYID = '" . $this->model->getItemCategoryId() . "',
                       ITEMTYPEID = '" . $this->model->getItemTypeId() . "',
                       ASSETID = '" . $this->model->getAssetId() . "',
                       DOCUMENTNO = '" . $this->model->getDocumentNumber() . "',
                       TRANSACTIONDATE = '" . $this->model->getTransactionDate() . "',
                       FINANCEPERIOD = '" . $this->model->getFinancePeriod() . "',
                       FINANCEYEAR = '" . $this->model->getFinanceYear() . "',
                       ASSETREVALUATIONREFERENCE = '" . $this->model->getAssetRevaluationReference() . "',
                       ASSETREVALUATIONPRICE = '" . $this->model->getAssetRevaluationPrice() . "',
                       ASSETREVALUATIONVALUE = '" . $this->model->getAssetRevaluationValue() . "',
                       ASSETREVALUATIONNETBOOKBALUE = '" . $this->model->getAssetRevaluationNetBookValue() . "',
                       ASSETPRICE = '" . $this->model->getAssetPrice() . "',
                       YEARTODATE = '" . $this->model->getYearToDate() . "',
                       CURRENTNETBOOKVALUE = '" . $this->model->getCurrentNetBookValue() . "',
                       ASSETREVALUATIONCOMMENT = '" . $this->model->getAssetRevaluationComment() . "',
                       ASSETREVALUATIONPROFIT = '" . $this->model->getAssetRevaluationProfit() . "',
                       DEPRECIATIONPERIOD = '" . $this->model->getDepreciationPeriod() . "',
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
                WHERE  ASSETREVALUATIONID='" . $this->model->getAssetRevaluationId('0', 'single') . "'";
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
           FROM 	`assetrevaluation`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getAssetRevaluationId(
                            0, 'single'
                    ) . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[assetRevaluation]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getAssetRevaluationId(
                                0, 'single'
                        ) . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	ASSETREVALUATION
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getAssetRevaluationId(
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
               UPDATE  `assetrevaluation SET
                       `isDefault`     =   '" . $this->model->getIsDefault(0, 'single') . "',
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
               WHERE   `assetRevaluationId`   =  '" . $this->model->getAssetRevaluationId(0, 'single') . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
               UPDATE  [assetRevaluation] SET
                       [isDefault]     =   '" . $this->model->getIsDefault(0, 'single') . "',
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
               WHERE   [assetRevaluationId]	=  '" . $this->model->getAssetRevaluationId(0, 'single') . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
               UPDATE  ASSETREVALUATION SET
                       ISDEFAULT       =   '" . $this->model->getIsDefault(0, 'single') . "',
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
               WHERE   ASSETREVALUATIONID	=  '" . $this->model->getAssetRevaluationId(0, 'single') . "'";
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
        
    }

    /**
     * Return  ItemCategory
     * @return mixed
     */
    public function getItemCategory() {
        return $this->service->getItemCategory();
    }

    /**
     * Return  ItemType
     * @return mixed
     */
    public function getItemType() {
        return $this->service->getItemType();
    }

    /**
     * Return  Asset
     * @return mixed
     */
    public function getAsset() {
        return $this->service->getAsset();
    }

    /**
     * Return Company Primary Key
     * @return int
     */
    public function getCompanyId() {
        return $this->companyId;
    }

    /**
     * Set Company Primary Key
     * @param int
     */
    public function setCompanyId($value) {
        $this->companyId = $value;
    }

    /**
     * Return ItemCategory Primary Key
     * @return int
     */
    public function getItemCategoryId() {
        return $this->itemCategoryId;
    }

    /**
     * Set ItemCategory Primary Key
     * @param int
     */
    public function setItemCategoryId($value) {
        $this->itemCategoryId = $value;
    }

    /**
     * Return ItemType Primary Key
     * @return int
     */
    public function getItemTypeId() {
        return $this->itemTypeId;
    }

    /**
     * Set ItemType Primary Key
     * @param int
     */
    public function setItemTypeId($value) {
        $this->itemTypeId = $value;
    }

    /**
     * Return Asset Primary Key
     * @return int
     */
    public function getAssetId() {
        return $this->assetId;
    }

    /**
     * Set Asset Primary Key
     * @param int
     */
    public function setAssetId($value) {
        $this->assetId = $value;
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
                ->setSubject('assetrevaluation')
                ->setDescription('Generated by PhpExcel an Idcms Generator')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('financial/asset');
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
        $this->excel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
        $this->excel->getActiveSheet()->setCellValue('B2', $this->getReportTitle());
        $this->excel->getActiveSheet()->setCellValue('V2', '');
        $this->excel->getActiveSheet()->mergeCells('B2:V2');
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.');
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['companyIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['itemCategoryIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['itemTypeIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['assetIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['documentNoLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['transactionDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['financePeriodLabel']);
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['financeYearLabel']);
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['assetRevaluationReferenceLabel']);
        $this->excel->getActiveSheet()->setCellValue('L3', $this->translate['assetRevaluationPriceLabel']);
        $this->excel->getActiveSheet()->setCellValue('M3', $this->translate['assetRevaluationValueLabel']);
        $this->excel->getActiveSheet()->setCellValue('N3', $this->translate['assetRevaluationNetBookBalueLabel']);
        $this->excel->getActiveSheet()->setCellValue('O3', $this->translate['assetPriceLabel']);
        $this->excel->getActiveSheet()->setCellValue('P3', $this->translate['yearToDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('Q3', $this->translate['currentNetBookValueLabel']);
        $this->excel->getActiveSheet()->setCellValue('R3', $this->translate['assetRevaluationCommentLabel']);
        $this->excel->getActiveSheet()->setCellValue('S3', $this->translate['assetRevaluationProfitLabel']);
        $this->excel->getActiveSheet()->setCellValue('T3', $this->translate['depreciationPeriodLabel']);
        $this->excel->getActiveSheet()->setCellValue('U3', $this->translate['executeByLabel']);
        $this->excel->getActiveSheet()->setCellValue('V3', $this->translate['executeTimeLabel']);
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
            $this->excel->getActiveSheet()->setCellValue('D' . $loopRow, $row ['itemCategoryId']);
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('E' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('E' . $loopRow, $row ['itemTypeId']);
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('F' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('F' . $loopRow, $row ['assetId']);
            $this->excel->getActiveSheet()
                    ->setCellValue('G' . $loopRow, $row ['documentNumber']);
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('H' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $this->excel->getActiveSheet()->setCellValue('H' . $loopRow, $row ['transactionDate']);
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('I' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('I' . $loopRow, $row ['financePeriod']);
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('J' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('J' . $loopRow, $row ['financeYear']);
            $this->excel->getActiveSheet()
                    ->setCellValue('K' . $loopRow, $row ['assetRevaluationReference']);
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('L' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('L' . $loopRow, $row ['assetRevaluationPrice']);
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('M' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('M' . $loopRow, $row ['assetRevaluationValue']);
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('N' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('N' . $loopRow, $row ['assetRevaluationNetBookBalue']);
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('O' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('O' . $loopRow, $row ['assetPrice']);
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('P' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('P' . $loopRow, $row ['yearToDate']);
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('Q' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('Q' . $loopRow, $row ['currentNetBookValue']);
            $this->excel->getActiveSheet()
                    ->setCellValue('R' . $loopRow, $row ['assetRevaluationComment']);
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('S' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('S' . $loopRow, $row ['assetRevaluationProfit']);
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('T' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('T' . $loopRow, $row ['depreciationPeriod']);
            $this->excel->getActiveSheet()
                    ->setCellValue('U' . $loopRow, $row ['staffName']);
            $this->excel->getActiveSheet()
                    ->setCellValue('V' . $loopRow, $row ['executeTime']);
            $this->excel->getActiveSheet()
                    ->getStyle()
                    ->getNumberFormat('V' . $loopRow)
                    ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $loopRow++;
            $lastRow = 'V' . $loopRow;
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
        $filename = "assetrevaluation" . rand(0, 10000000) . $extension;
        $path = $this->getFakeDocumentRoot() . "v3/financial/fixedAsset/document/" . $folder . "/" . $filename;
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
        $assetRevaluationObject = new AssetRevaluationClass ();
        if ($_POST['securityToken'] != $assetRevaluationObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $assetRevaluationObject->setLeafId($_POST ['leafId']);
        }
        $assetRevaluationObject->setPageOutput($_POST['output']);
        $assetRevaluationObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $assetRevaluationObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $assetRevaluationObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $assetRevaluationObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $assetRevaluationObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$assetRevaluationObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$assetRevaluationObject->delete(); 
        }
    }
}
if (isset($_GET ['method'])) {
    $assetRevaluationObject = new AssetRevaluationClass ();
    if ($_GET['securityToken'] != $assetRevaluationObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $assetRevaluationObject->setLeafId($_GET ['leafId']);
    }
    /*
     * Admin Only
     */
    if (isset($_GET ['isAdmin'])) {
        $assetRevaluationObject->setIsAdmin($_GET ['isAdmin']);
    }
    /**
     * Database Request
     */
    if (isset($_GET ['databaseRequest'])) {
        $assetRevaluationObject->setRequestDatabase($_GET ['databaseRequest']);
    }
    if (isset($_GET['companyId'])) {
        $assetRevaluationObject->setCompanyId($_GET['companyId']);
    }
    if (isset($_GET['itemCategoryId'])) {
        $assetRevaluationObject->setItemCategoryId($_GET['itemCategoryId']);
    }
    if (isset($_GET['itemTypeId'])) {
        $assetRevaluationObject->setItemTypeId($_GET['itemTypeId']);
    }
    if (isset($_GET['assetId'])) {
        $assetRevaluationObject->setAssetId($_GET['assetId']);
    }
    /*
     *  Load the dynamic value
     */
    $assetRevaluationObject->execute();

    /**
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'updateStatus') {
        $assetRevaluationObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key 
     */
    if (isset($_GET ['assetrevaluationCode'])) {
        if (strlen($_GET ['assetrevaluationCode']) > 0) {
            $assetRevaluationObject->duplicate();
        }
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $assetRevaluationObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $assetRevaluationObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $assetRevaluationObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $assetRevaluationObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $assetRevaluationObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $assetRevaluationObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        if (($_GET['filter'] == 'company')) {
            $assetRevaluationObject->getCompany();
        }
    }
    if (isset($_GET ['filter'])) {
        if (($_GET['filter'] == 'itemCategory')) {
            $assetRevaluationObject->getItemCategory();
        }
    }
    if (isset($_GET ['filter'])) {
        if (($_GET['filter'] == 'itemType')) {
            $assetRevaluationObject->getItemType();
        }
    }
    if (isset($_GET ['filter'])) {
        if (($_GET['filter'] == 'asset')) {
            $assetRevaluationObject->getAsset();
        }
    }
}
?>