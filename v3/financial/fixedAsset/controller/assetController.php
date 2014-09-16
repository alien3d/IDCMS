<?php

namespace Core\Financial\FixedAsset\Asset\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\Financial\FixedAsset\Asset\Model\AssetModel;
use Core\Financial\FixedAsset\Asset\Service\AssetService;
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
require_once($newFakeDocumentRoot . "v3/financial/fixedAsset/model/assetModel.php");
require_once($newFakeDocumentRoot . "v3/financial/fixedAsset/service/assetService.php");

/**
 * Class Asset
 * this is asset controller files.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package  Core\Financial\FixedAsset\Asset\Controller
 * @subpackage FixedAsset
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class AssetClass extends ConfigClass {

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
     * @var \Core\Financial\FixedAsset\Asset\Model\AssetModel
     */
    public $model;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     * @var \PHPPowerPoint
     */
    //private $powerPoint; 
    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\Financial\FixedAsset\Asset\Service\AssetService
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
        $this->setViewPath("./v3/financial/fixedAsset/view/asset.php");
        $this->setControllerPath("./v3/financial/fixedAsset/controller/assetController.php");
        $this->setServicePath("./v3/financial/fixedAsset/service/assetService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new AssetModel();
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

        $this->service = new AssetService();
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
            $this->model->setBranchId($this->service->getBranchDefaultValue());
        }
        if (!$this->model->getDepartmentId()) {
            $this->model->setDepartmentId($this->service->getDepartmentDefaultValue());
        }
        if (!$this->model->getWarehouseId()) {
            $this->model->setWarehouseId($this->service->getWarehouseDefaultValue());
        }
        if (!$this->model->getLocationId()) {
            $this->model->setLocationId($this->service->getLocationDefaultValue());
        }
        if (!$this->model->getItemCategoryId()) {
            $this->model->setItemCategoryId($this->service->getItemCategoryDefaultValue());
        }
        if (!$this->model->getItemTypeId()) {
            $this->model->setItemTypeId($this->service->getItemTypeDefaultValue());
        }
        if (!$this->model->getBusinessPartnerId()) {
            $this->model->setBusinessPartnerId($this->service->getBusinessPartnerDefaultValue());
        }
        if (!$this->model->getUnitOfMeasurementId()) {
            $this->model->setUnitOfMeasurementId($this->service->getUnitOfMeasurementDefaultValue());
        }
        if (!$this->model->getPurchaseInvoiceId()) {
            $this->model->setPurchaseInvoiceId($this->service->getPurchaseInvoiceDefaultValue());
        }
        //$this->model->setDocumentNumber($this->getDocumentNumber());
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `asset` 
            (
                 `companyId`,
                 `branchId`,
                 `departmentId`,
                 `warehouseId`,
                 `locationId`,
                 `itemCategoryId`,
                 `itemTypeId`,
                 `businessPartnerId`,
                 `unitOfMeasurementId`,
                 `purchaseInvoiceId`,
                 `assetCode`,
                 `assetSerialNumber`,
                 `assetName`,
                 `assetDescription`,
                 `assetModel`,
                 `assetPrice`,
                 `assetDate`,
                 `assetWarranty`,
                 `assetColor`,
                 `assetQuantity`,
                 `assetInsuranceBusinessPartnerId`,
                 `assetInsuranceStartDate`,
                 `assetInsuranceExpiredDate`,
                 `assetWarrantyStartDate`,
                 `assetWarrantyEndDate`,
                 `assetDepreciationRate`,
                 `assetNetBookValue`,
                 `assetPicture`,
                 `isDefault`,
                 `isNew`,
                 `isDraft`,
                 `isUpdate`,
                 `isDelete`,
                 `isActive`,
                 `isApproved`,
                 `isReview`,
                 `isPost`,
                 `isTransferAsKit`,
                 `isDepreciate`,
                 `isWriteOff`,
                 `isDispose`,
                 `isAdjust`,
                 `executeBy`,
                 `executeTime`
       ) VALUES ( 
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getBranchId() . "',
                 '" . $this->model->getDepartmentId() . "',
                 '" . $this->model->getWarehouseId() . "',
                 '" . $this->model->getLocationId() . "',
                 '" . $this->model->getItemCategoryId() . "',
                 '" . $this->model->getItemTypeId() . "',
                 '" . $this->model->getBusinessPartnerId() . "',
                 '" . $this->model->getUnitOfMeasurementId() . "',
                 '" . $this->model->getPurchaseInvoiceId() . "',
                 '" . $this->model->getAssetCode() . "',
                 '" . $this->model->getAssetSerialNumber() . "',
                 '" . $this->model->getAssetName() . "',
                 '" . $this->model->getAssetDescription() . "',
                 '" . $this->model->getAssetModel() . "',
                 '" . $this->model->getAssetPrice() . "',
                 '" . $this->model->getAssetDate() . "',
                 '" . $this->model->getAssetWarranty() . "',
                 '" . $this->model->getAssetColor() . "',
                 '" . $this->model->getAssetQuantity() . "',
                 '" . $this->model->getAssetInsuranceBusinessPartnerId() . "',
                 '" . $this->model->getAssetInsuranceStartDate() . "',
                 '" . $this->model->getAssetInsuranceExpiredDate() . "',
                 '" . $this->model->getAssetWarrantyStartDate() . "',
                 '" . $this->model->getAssetWarrantyEndDate() . "',
                 '" . $this->model->getAssetDepreciationRate() . "',
                 '" . $this->model->getAssetNetBookValue() . "',
                 '" . $this->model->getAssetPicture() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getIsTransferAsKit() . "',
                 '" . $this->model->getIsDepreciate() . "',
                 '" . $this->model->getIsWriteOff() . "',
                 '" . $this->model->getIsDispose() . "',
                 '" . $this->model->getIsAdjust() . "',
                 '" . $this->model->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . "
       );";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            INSERT INTO [asset]
            (
                 [assetId],
                 [companyId],
                 [branchId],
                 [departmentId],
                 [warehouseId],
                 [locationId],
                 [itemCategoryId],
                 [itemTypeId],
                 [businessPartnerId],
                 [unitOfMeasurementId],
                 [purchaseInvoiceId],
                 [assetCode],
                 [assetSerialNumber],
                 [assetName],
                 [assetDescription],
                 [assetModel],
                 [assetPrice],
                 [assetDate],
                 [assetWarranty],
                 [assetColor],
                 [assetQuantity],
                 [assetInsuranceBusinessPartnerId],
                 [assetInsuranceStartDate],
                 [assetInsuranceExpiredDate],
                 [assetWarrantyStartDate],
                 [assetWarrantyEndDate],
                 [assetDepreciationRate],
                 [assetNetBookValue],
                 [assetPicture],
                 [isDefault],
                 [isNew],
                 [isDraft],
                 [isUpdate],
                 [isDelete],
                 [isActive],
                 [isApproved],
                 [isReview],
                 [isPost],
                 [isTransferAsKit],
                 [isDepreciate],
                 [isWriteOff],
                 [isDispose],
                 [isAdjust],
                 [executeBy],
                 [executeTime]
) VALUES (
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getBranchId() . "',
                 '" . $this->model->getDepartmentId() . "',
                 '" . $this->model->getWarehouseId() . "',
                 '" . $this->model->getLocationId() . "',
                 '" . $this->model->getItemCategoryId() . "',
                 '" . $this->model->getItemTypeId() . "',
                 '" . $this->model->getBusinessPartnerId() . "',
                 '" . $this->model->getUnitOfMeasurementId() . "',
                 '" . $this->model->getPurchaseInvoiceId() . "',
                 '" . $this->model->getAssetCode() . "',
                 '" . $this->model->getAssetSerialNumber() . "',
                 '" . $this->model->getAssetName() . "',
                 '" . $this->model->getAssetDescription() . "',
                 '" . $this->model->getAssetModel() . "',
                 '" . $this->model->getAssetPrice() . "',
                 '" . $this->model->getAssetDate() . "',
                 '" . $this->model->getAssetWarranty() . "',
                 '" . $this->model->getAssetColor() . "',
                 '" . $this->model->getAssetQuantity() . "',
                 '" . $this->model->getAssetInsuranceBusinessPartnerId() . "',
                 '" . $this->model->getAssetInsuranceStartDate() . "',
                 '" . $this->model->getAssetInsuranceExpiredDate() . "',
                 '" . $this->model->getAssetWarrantyStartDate() . "',
                 '" . $this->model->getAssetWarrantyEndDate() . "',
                 '" . $this->model->getAssetDepreciationRate() . "',
                 '" . $this->model->getAssetNetBookValue() . "',
                 '" . $this->model->getAssetPicture() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getIsTransferAsKit() . "',
                 '" . $this->model->getIsDepreciate() . "',
                 '" . $this->model->getIsWriteOff() . "',
                 '" . $this->model->getIsDispose() . "',
                 '" . $this->model->getIsAdjust() . "',
                 '" . $this->model->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . "
            );";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            INSERT INTO ASSET
            (
                 COMPANYID,
                 BRANCHID,
                 DEPARTMENTID,
                 WAREHOUSEID,
                 LOCATIONID,
                 ITEMCATEGORYID,
                 ITEMTYPEID,
                 BUSINESSPARTNERID,
                 UNITOFMEASUREMENTID,
                 PURCHASEINVOICEID,
                 ASSETCODE,
                 ASSETSERIALNUMBER,
                 ASSETNAME,
                 ASSETDESCRIPTION,
                 ASSETMODEL,
                 ASSETPRICE,
                 ASSETDATE,
                 ASSETWARRANTY,
                 ASSETCOLOR,
                 ASSETQUANTITY,
                 ASSETINSURANCEBUSINESSPARTNERID,
                 ASSETINSURANCESTARTDATE,
                 ASSETINSURANCEEXPIREDDATE,
                 ASSETWARRANTYSTARTDATE,
                 ASSETWARRANTYENDDATE,
                 ASSETDEPRECIATIONRATE,
                 ASSETNETBOOKVALUE,
                 ASSETPICTURE,
                 ISDEFAULT,
                 ISNEW,
                 ISDRAFT,
                 ISUPDATE,
                 ISDELETE,
                 ISACTIVE,
                 ISAPPROVED,
                 ISREVIEW,
                 ISPOST,
                 ISTRANSFERASKIT,
                 ISDEPRECIATE,
                 ISWRITEOFF,
                 ISDISPOSE,
                 ISADJUST,
                 EXECUTEBY,
                 EXECUTETIME
            ) VALUES (
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getBranchId() . "',
                 '" . $this->model->getDepartmentId() . "',
                 '" . $this->model->getWarehouseId() . "',
                 '" . $this->model->getLocationId() . "',
                 '" . $this->model->getItemCategoryId() . "',
                 '" . $this->model->getItemTypeId() . "',
                 '" . $this->model->getBusinessPartnerId() . "',
                 '" . $this->model->getUnitOfMeasurementId() . "',
                 '" . $this->model->getPurchaseInvoiceId() . "',
                 '" . $this->model->getAssetCode() . "',
                 '" . $this->model->getAssetSerialNumber() . "',
                 '" . $this->model->getAssetName() . "',
                 '" . $this->model->getAssetDescription() . "',
                 '" . $this->model->getAssetModel() . "',
                 '" . $this->model->getAssetPrice() . "',
                 '" . $this->model->getAssetDate() . "',
                 '" . $this->model->getAssetWarranty() . "',
                 '" . $this->model->getAssetColor() . "',
                 '" . $this->model->getAssetQuantity() . "',
                 '" . $this->model->getAssetInsuranceBusinessPartnerId() . "',
                 '" . $this->model->getAssetInsuranceStartDate() . "',
                 '" . $this->model->getAssetInsuranceExpiredDate() . "',
                 '" . $this->model->getAssetWarrantyStartDate() . "',
                 '" . $this->model->getAssetWarrantyEndDate() . "',
                 '" . $this->model->getAssetDepreciationRate() . "',
                 '" . $this->model->getAssetNetBookValue() . "',
                 '" . $this->model->getAssetPicture() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getIsTransferAsKit() . "',
                 '" . $this->model->getIsDepreciate() . "',
                 '" . $this->model->getIsWriteOff() . "',
                 '" . $this->model->getIsDispose() . "',
                 '" . $this->model->getIsAdjust() . "',
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
        $assetId = $this->q->lastInsertId();
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
                    "assetId" => $assetId,
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
         FROM    `asset`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT    COUNT(*) AS total
         FROM      [asset]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT    COUNT(*)    AS  \"total\"
         FROM      ASSET
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
                            " `asset`.`isActive` = 1  AND `asset`.`companyId`='" . $this->getCompanyId() . "' "
                    );
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [asset].[isActive] = 1 AND [asset].[companyId]='" . $this->getCompanyId() . "' "
                        );
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(
                                    " ASSET.ISACTIVE = 1  AND ASSET.COMPANYID='" . $this->getCompanyId() . "'"
                            );
                        }
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter("   `asset`.`companyId`='" . $this->getCompanyId() . "'	");
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(" [asset].[companyId]='" . $this->getCompanyId() . "' ");
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(" ASSET.COMPANYID='" . $this->getCompanyId() . "' ");
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
       SELECT                    `asset`.`assetId`,
                    `company`.`companyDescription`,
                    `asset`.`companyId`,
                    `branch`.`branchName`,
                    `asset`.`branchId`,
                    `department`.`departmentDescription`,
                    `asset`.`departmentId`,
                    `warehouse`.`warehouseDescription`,
                    `asset`.`warehouseId`,
                    `location`.`locationDescription`,
                    `asset`.`locationId`,
                    `itemcategory`.`itemCategoryDescription`,
                    `asset`.`itemCategoryId`,
                    `itemtype`.`itemTypeDescription`,
                    `asset`.`itemTypeId`,
                    `businesspartner`.`businessPartnerCompany`,
                    `asset`.`businessPartnerId`,
                    `unitofmeasurement`.`unitOfMeasurementDescription`,
                    `asset`.`unitOfMeasurementId`,
                    `purchaseinvoice`.`purchaseInvoiceDescription`,
                    `asset`.`purchaseInvoiceId`,
                    `asset`.`assetCode`,
                    `asset`.`assetSerialNumber`,
                    `asset`.`assetName`,
                    `asset`.`assetDescription`,
                    `asset`.`assetModel`,
                    `asset`.`assetPrice`,
                    `asset`.`assetDate`,
                    `asset`.`assetWarranty`,
                    `asset`.`assetColor`,
                    `asset`.`assetQuantity`,
                    `asset`.`assetInsuranceBusinessPartnerId`,
                    `asset`.`assetInsuranceStartDate`,
                    `asset`.`assetInsuranceExpiredDate`,
                    `asset`.`assetWarrantyStartDate`,
                    `asset`.`assetWarrantyEndDate`,
                    `asset`.`assetDepreciationRate`,
                    `asset`.`assetNetBookValue`,
                    `asset`.`assetPicture`,
                    `asset`.`isDefault`,
                    `asset`.`isNew`,
                    `asset`.`isDraft`,
                    `asset`.`isUpdate`,
                    `asset`.`isDelete`,
                    `asset`.`isActive`,
                    `asset`.`isApproved`,
                    `asset`.`isReview`,
                    `asset`.`isPost`,
                    `asset`.`isTransferAsKit`,
                    `asset`.`isDepreciate`,
                    `asset`.`isWriteOff`,
                    `asset`.`isDispose`,
                    `asset`.`isAdjust`,
                    `asset`.`executeBy`,
                    `asset`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `asset`
		  JOIN      `staff`
		  ON        `asset`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `asset`.`companyId`
	JOIN	`branch`
	ON		`branch`.`branchId` = `asset`.`branchId`
	JOIN	`department`
	ON		`department`.`departmentId` = `asset`.`departmentId`
	JOIN	`warehouse`
	ON		`warehouse`.`warehouseId` = `asset`.`warehouseId`
	JOIN	`location`
	ON		`location`.`locationId` = `asset`.`locationId`
	JOIN	`itemcategory`
	ON		`itemcategory`.`itemCategoryId` = `asset`.`itemCategoryId`
	JOIN	`itemtype`
	ON		`itemtype`.`itemTypeId` = `asset`.`itemTypeId`
	JOIN	`businesspartner`
	ON		`businesspartner`.`businessPartnerId` = `asset`.`businessPartnerId`
	JOIN	`unitofmeasurement`
	ON		`unitofmeasurement`.`unitOfMeasurementId` = `asset`.`unitOfMeasurementId`
	JOIN	`purchaseinvoice`
	ON		`purchaseinvoice`.`purchaseInvoiceId` = `asset`.`purchaseInvoiceId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getAssetId(0, 'single')) {
                $sql .= " AND `asset`.`" . $this->model->getPrimaryKeyName() . "`='" . $this->model->getAssetId(
                                0, 'single'
                        ) . "'";
            }
            if ($this->model->getBranchId()) {
                $sql .= " AND `asset`.`branchId`='" . $this->model->getBranchId() . "'";
            }
            if ($this->model->getDepartmentId()) {
                $sql .= " AND `asset`.`departmentId`='" . $this->model->getDepartmentId() . "'";
            }
            if ($this->model->getWarehouseId()) {
                $sql .= " AND `asset`.`warehouseId`='" . $this->model->getWarehouseId() . "'";
            }
            if ($this->model->getLocationId()) {
                $sql .= " AND `asset`.`locationId`='" . $this->model->getLocationId() . "'";
            }
            if ($this->model->getItemCategoryId()) {
                $sql .= " AND `asset`.`itemCategoryId`='" . $this->model->getItemCategoryId() . "'";
            }
            if ($this->model->getItemTypeId()) {
                $sql .= " AND `asset`.`itemTypeId`='" . $this->model->getItemTypeId() . "'";
            }
            if ($this->model->getBusinessPartnerId()) {
                $sql .= " AND `asset`.`businessPartnerId`='" . $this->model->getBusinessPartnerId() . "'";
            }
            if ($this->model->getUnitOfMeasurementId()) {
                $sql .= " AND `asset`.`unitOfMeasurementId`='" . $this->model->getUnitOfMeasurementId() . "'";
            }
            if ($this->model->getPurchaseInvoiceId()) {
                $sql .= " AND `asset`.`purchaseInvoiceId`='" . $this->model->getPurchaseInvoiceId() . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
		  SELECT                    [asset].[assetId],
                    [company].[companyDescription],
                    [asset].[companyId],
                    [branch].[branchName],
                    [asset].[branchId],
                    [department].[departmentDescription],
                    [asset].[departmentId],
                    [warehouse].[warehouseDescription],
                    [asset].[warehouseId],
                    [location].[locationDescription],
                    [asset].[locationId],
                    [itemCategory].[itemCategoryDescription],
                    [asset].[itemCategoryId],
                    [itemType].[itemTypeDescription],
                    [asset].[itemTypeId],
                    [businessPartner].[businessPartnerCompany],
                    [asset].[businessPartnerId],
                    [unitOfMeasurement].[unitOfMeasurementDescription],
                    [asset].[unitOfMeasurementId],
                    [purchaseInvoice].[purchaseInvoiceDescription],
                    [asset].[purchaseInvoiceId],
                    [asset].[assetCode],
                    [asset].[assetSerialNumber],
                    [asset].[assetName],
                    [asset].[assetDescription],
                    [asset].[assetModel],
                    [asset].[assetPrice],
                    [asset].[assetDate],
                    [asset].[assetWarranty],
                    [asset].[assetColor],
                    [asset].[assetQuantity],
                    [asset].[assetInsuranceBusinessPartnerId],
                    [asset].[assetInsuranceStartDate],
                    [asset].[assetInsuranceExpiredDate],
                    [asset].[assetWarrantyStartDate],
                    [asset].[assetWarrantyEndDate],
                    [asset].[assetDepreciationRate],
                    [asset].[assetNetBookValue],
                    [asset].[assetPicture],
                    [asset].[isDefault],
                    [asset].[isNew],
                    [asset].[isDraft],
                    [asset].[isUpdate],
                    [asset].[isDelete],
                    [asset].[isActive],
                    [asset].[isApproved],
                    [asset].[isReview],
                    [asset].[isPost],
                    [asset].[isTransferAsKit],
                    [asset].[isDepreciate],
                    [asset].[isWriteOff],
                    [asset].[isDispose],
                    [asset].[isAdjust],
                    [asset].[executeBy],
                    [asset].[executeTime],
                    [staff].[staffName]
		  FROM 	[asset]
		  JOIN	[staff]
		  ON	[asset].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [asset].[companyId]
	JOIN	[branch]
	ON		[branch].[branchId] = [asset].[branchId]
	JOIN	[department]
	ON		[department].[departmentId] = [asset].[departmentId]
	JOIN	[warehouse]
	ON		[warehouse].[warehouseId] = [asset].[warehouseId]
	JOIN	[location]
	ON		[location].[locationId] = [asset].[locationId]
	JOIN	[itemCategory]
	ON		[itemCategory].[itemCategoryId] = [asset].[itemCategoryId]
	JOIN	[itemType]
	ON		[itemType].[itemTypeId] = [asset].[itemTypeId]
	JOIN	[businessPartner]
	ON		[businessPartner].[businessPartnerId] = [asset].[businessPartnerId]
	JOIN	[unitOfMeasurement]
	ON		[unitOfMeasurement].[unitOfMeasurementId] = [asset].[unitOfMeasurementId]
	JOIN	[purchaseInvoice]
	ON		[purchaseInvoice].[purchaseInvoiceId] = [asset].[purchaseInvoiceId]
		  WHERE     " . $this->getAuditFilter();
                if ($this->model->getAssetId(0, 'single')) {
                    $sql .= " AND [asset].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getAssetId(0, 'single') . "'";
                }
                if ($this->model->getBranchId()) {
                    $sql .= " AND [asset].[branchId]='" . $this->model->getBranchId() . "'";
                }
                if ($this->model->getDepartmentId()) {
                    $sql .= " AND [asset].[departmentId]='" . $this->model->getDepartmentId() . "'";
                }
                if ($this->model->getWarehouseId()) {
                    $sql .= " AND [asset].[warehouseId]='" . $this->model->getWarehouseId() . "'";
                }
                if ($this->model->getLocationId()) {
                    $sql .= " AND [asset].[locationId]='" . $this->model->getLocationId() . "'";
                }
                if ($this->model->getItemCategoryId()) {
                    $sql .= " AND [asset].[itemCategoryId]='" . $this->model->getItemCategoryId() . "'";
                }
                if ($this->model->getItemTypeId()) {
                    $sql .= " AND [asset].[itemTypeId]='" . $this->model->getItemTypeId() . "'";
                }
                if ($this->model->getBusinessPartnerId()) {
                    $sql .= " AND [asset].[businessPartnerId]='" . $this->model->getBusinessPartnerId() . "'";
                }
                if ($this->model->getUnitOfMeasurementId()) {
                    $sql .= " AND [asset].[unitOfMeasurementId]='" . $this->model->getUnitOfMeasurementId() . "'";
                }
                if ($this->model->getPurchaseInvoiceId()) {
                    $sql .= " AND [asset].[purchaseInvoiceId]='" . $this->model->getPurchaseInvoiceId() . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
		  SELECT                    ASSET.ASSETID AS \"assetId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    ASSET.COMPANYID AS \"companyId\",
                    BRANCH.branchName AS  \"branchName\",
                    ASSET.BRANCHID AS \"branchId\",
                    DEPARTMENT.DEPARTMENTDESCRIPTIONRIPTION AS  \"departmentDescription\",
                    ASSET.DEPARTMENTID AS \"departmentId\",
                    WAREHOUSE.WAREHOUSEDESCRIPTION AS  \"warehouseDescription\",
                    ASSET.WAREHOUSEID AS \"warehouseId\",
                    LOCATION.LOCATIONDESCRIPTION AS  \"locationDescription\",
                    ASSET.LOCATIONID AS \"locationId\",
                    ITEMCATEGORY.ITEMCATEGORYDESCRIPTION AS  \"itemCategoryDescription\",
                    ASSET.ITEMCATEGORYID AS \"itemCategoryId\",
                    ITEMTYPE.ITEMTYPEDESCRIPTION AS  \"itemTypeDescription\",
                    ASSET.ITEMTYPEID AS \"itemTypeId\",
                    BUSINESSPARTNER.BUSINESSPARTNERCOMPANY AS  \"businessPartnerCompany\",
                    ASSET.BUSINESSPARTNERID AS \"businessPartnerId\",
                    UNITOFMEASUREMENT.UNITOFMEASUREMENTDESCRIPTION AS  \"unitOfMeasurementDescription\",
                    ASSET.UNITOFMEASUREMENTID AS \"unitOfMeasurementId\",
                    PURCHASEORDER.PURCHASEORDERDESCRIPTION AS  \"purchaseInvoiceDescription\",
                    ASSET.PURCHASEINVOICEID AS \"purchaseInvoiceId\",
                    ASSET.ASSETCODE AS \"assetCode\",
                    ASSET.ASSETSERIALNUMBER AS \"assetSerialNumber\",
                    ASSET.ASSETNAME AS \"assetName\",
                    ASSET.ASSETDESCRIPTION AS \"assetDescription\",
                    ASSET.ASSETMODEL AS \"assetModel\",
                    ASSET.ASSETPRICE AS \"assetPrice\",
                    ASSET.ASSETDATE AS \"assetDate\",
                    ASSET.ASSETWARRANTY AS \"assetWarranty\",
                    ASSET.ASSETCOLOR AS \"assetColor\",
                    ASSET.ASSETQUANTITY AS \"assetQuantity\",
                    ASSET.ASSETINSURANCEBUSINESSPARTNERID AS \"assetInsuranceBusinessPartnerId\",
                    ASSET.ASSETINSURANCESTARTDATE AS \"assetInsuranceStartDate\",
                    ASSET.ASSETINSURANCEEXPIREDDATE AS \"assetInsuranceExpiredDate\",
                    ASSET.ASSETWARRANTYSTARTDATE AS \"assetWarrantyStartDate\",
                    ASSET.ASSETWARRANTYENDDATE AS \"assetWarrantyEndDate\",
                    ASSET.ASSETDEPRECIATIONRATE AS \"assetDepreciationRate\",
                    ASSET.ASSETNETBOOKVALUE AS \"assetNetBookValue\",
                    ASSET.ASSETPICTURE AS \"assetPicture\",
                    ASSET.ISDEFAULT AS \"isDefault\",
                    ASSET.ISNEW AS \"isNew\",
                    ASSET.ISDRAFT AS \"isDraft\",
                    ASSET.ISUPDATE AS \"isUpdate\",
                    ASSET.ISDELETE AS \"isDelete\",
                    ASSET.ISACTIVE AS \"isActive\",
                    ASSET.ISAPPROVED AS \"isApproved\",
                    ASSET.ISREVIEW AS \"isReview\",
                    ASSET.ISPOST AS \"isPost\",
                    ASSET.ISTRANSFERASKIT AS \"isTransferAsKit\",
                    ASSET.ISDEPRECIATE AS \"isDepreciate\",
                    ASSET.ISWRITEOFF AS \"isWriteOff\",
                    ASSET.ISDISPOSE AS \"isDispose\",
                    ASSET.ISADJUST AS \"isAdjust\",
                    ASSET.EXECUTEBY AS \"executeBy\",
                    ASSET.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\"
		  FROM 	ASSET
		  JOIN	STAFF
		  ON	ASSET.EXECUTEBY = STAFF.STAFFID
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = ASSET.COMPANYID
	JOIN	BRANCH
	ON		BRANCH.BRANCHID = ASSET.BRANCHID
	JOIN	DEPARTMENT
	ON		DEPARTMENT.DEPARTMENTID = ASSET.DEPARTMENTID
	JOIN	WAREHOUSE
	ON		WAREHOUSE.WAREHOUSEID = ASSET.WAREHOUSEID
	JOIN	LOCATION
	ON		LOCATION.LOCATIONID = ASSET.LOCATIONID
	JOIN	ITEMCATEGORY
	ON		ITEMCATEGORY.ITEMCATEGORYID = ASSET.ITEMCATEGORYID
	JOIN	ITEMTYPE
	ON		ITEMTYPE.ITEMTYPEID = ASSET.ITEMTYPEID
	JOIN	BUSINESSPARTNER
	ON		BUSINESSPARTNER.BUSINESSPARTNERID = ASSET.BUSINESSPARTNERID
	JOIN	UNITOFMEASUREMENT
	ON		UNITOFMEASUREMENT.UNITOFMEASUREMENTID = ASSET.UNITOFMEASUREMENTID
	JOIN	PURCHASEORDER
	ON		PURCHASEORDER.PURCHASEINVOICEID = ASSET.PURCHASEINVOICEID
         WHERE     " . $this->getAuditFilter();
                    if ($this->model->getAssetId(0, 'single')) {
                        $sql .= " AND ASSET. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getAssetId(0, 'single') . "'";
                    }
                    if ($this->model->getBranchId()) {
                        $sql .= " AND ASSET.BRANCHID='" . $this->model->getBranchId() . "'";
                    }
                    if ($this->model->getDepartmentId()) {
                        $sql .= " AND ASSET.DEPARTMENTID='" . $this->model->getDepartmentId() . "'";
                    }
                    if ($this->model->getWarehouseId()) {
                        $sql .= " AND ASSET.WAREHOUSEID='" . $this->model->getWarehouseId() . "'";
                    }
                    if ($this->model->getLocationId()) {
                        $sql .= " AND ASSET.LOCATIONID='" . $this->model->getLocationId() . "'";
                    }
                    if ($this->model->getItemCategoryId()) {
                        $sql .= " AND ASSET.ITEMCATEGORYID='" . $this->model->getItemCategoryId() . "'";
                    }
                    if ($this->model->getItemTypeId()) {
                        $sql .= " AND ASSET.ITEMTYPEID='" . $this->model->getItemTypeId() . "'";
                    }
                    if ($this->model->getBusinessPartnerId()) {
                        $sql .= " AND ASSET.BUSINESSPARTNERID='" . $this->model->getBusinessPartnerId() . "'";
                    }
                    if ($this->model->getUnitOfMeasurementId()) {
                        $sql .= " AND ASSET.UNITOFMEASUREMENTID='" . $this->model->getUnitOfMeasurementId() . "'";
                    }
                    if ($this->model->getPurchaseInvoiceId()) {
                        $sql .= " AND ASSET.PURCHASEINVOICEID='" . $this->model->getPurchaseInvoiceId() . "'";
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
                $sql .= " AND `asset`.`" . $this->model->getFilterCharacter() . "` like '" . $this->getCharacterQuery(
                        ) . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [asset].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND Initcap(ASSET." . strtoupper(
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
                        'asset', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'asset', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'ASSET', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
                "`asset`.`assetId`",
                "`staff`.`staffPassword`"
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $filterArray = array(
                    "[asset].[assetId]",
                    "[staff].[staffPassword]"
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $filterArray = array(
                        "ASSET.ASSETID",
                        "STAFF.STAFFPASSWORD"
                    );
                }
            }
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array(
                'staff',
                'asset',
                'company',
                'branch',
                'department',
                'warehouse',
                'location',
                'itemcategory',
                'itemtype',
                'businesspartner',
                'unitofmeasurement',
                'purchaseorder'
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array(
                    'staff',
                    'asset',
                    'company',
                    'branch',
                    'department',
                    'warehouse',
                    'location',
                    'itemcategory',
                    'itemtype',
                    'businesspartner',
                    'unitofmeasurement',
                    'purchaseorder'
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array(
                        'STAFF',
                        'ASSET',
                        'COMPANY',
                        'BRANCH',
                        'DEPARTMENT',
                        'WAREHOUSE',
                        'LOCATION',
                        'ITEMCATEGORY',
                        'ITEMTYPE',
                        'BUSINESSPARTNER',
                        'UNITOFMEASUREMENT',
                        'PURCHASEORDER'
                    );
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
        if (!($this->model->getAssetId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 45;
            if ($this->model->getAssetId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getAssetId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getAssetId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else {
            if ($this->getPageOutput() == 'json') {
                if ($this->model->getAssetId(0, 'single')) {
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
                                                'value', $this->model->getAssetId(0, 'single')
                                        ),
                                        'nextRecord' => $this->nextRecord('value', $this->model->getAssetId(0, 'single')),
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
                                        'value', $this->model->getAssetId(0, 'single')
                                ),
                                'nextRecord' => $this->recordSet->nextRecord(
                                        'value', $this->model->getAssetId(0, 'single')
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
        if (!$this->model->getLocationId()) {
            $this->model->setLocationId($this->service->getLocationDefaultValue());
        }
        if (!$this->model->getItemCategoryId()) {
            $this->model->setItemCategoryId($this->service->getItemCategoryDefaultValue());
        }
        if (!$this->model->getItemTypeId()) {
            $this->model->setItemTypeId($this->service->getItemTypeDefaultValue());
        }
        if (!$this->model->getBusinessPartnerId()) {
            $this->model->setBusinessPartnerId($this->service->getBusinessPartnerDefaultValue());
        }
        if (!$this->model->getUnitOfMeasurementId()) {
            $this->model->setUnitOfMeasurementId($this->service->getUnitOfMeasurementDefaultValue());
        }
        if (!$this->model->getPurchaseInvoiceId()) {
            $this->model->setPurchaseInvoiceId($this->service->getPurchaseInvoiceDefaultValue());
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`asset`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getAssetId(0, 'single') . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[asset]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getAssetId(0, 'single') . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	ASSET
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getAssetId(
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
               UPDATE `asset` SET
                       `branchId` = '" . $this->model->getBranchId() . "',
                       `departmentId` = '" . $this->model->getDepartmentId() . "',
                       `warehouseId` = '" . $this->model->getWarehouseId() . "',
                       `locationId` = '" . $this->model->getLocationId() . "',
                       `itemCategoryId` = '" . $this->model->getItemCategoryId() . "',
                       `itemTypeId` = '" . $this->model->getItemTypeId() . "',
                       `businessPartnerId` = '" . $this->model->getBusinessPartnerId() . "',
                       `unitOfMeasurementId` = '" . $this->model->getUnitOfMeasurementId() . "',
                       `purchaseInvoiceId` = '" . $this->model->getPurchaseInvoiceId() . "',
                       `assetCode` = '" . $this->model->getAssetCode() . "',
                       `assetSerialNumber` = '" . $this->model->getAssetSerialNumber() . "',
                       `assetName` = '" . $this->model->getAssetName() . "',
                       `assetDescription` = '" . $this->model->getAssetDescription() . "',
                       `assetModel` = '" . $this->model->getAssetModel() . "',
                       `assetPrice` = '" . $this->model->getAssetPrice() . "',
                       `assetDate` = '" . $this->model->getAssetDate() . "',
                       `assetWarranty` = '" . $this->model->getAssetWarranty() . "',
                       `assetColor` = '" . $this->model->getAssetColor() . "',
                       `assetQuantity` = '" . $this->model->getAssetQuantity() . "',
                       `assetInsuranceBusinessPartnerId` = '" . $this->model->getAssetInsuranceBusinessPartnerId() . "',
                       `assetInsuranceStartDate` = '" . $this->model->getAssetInsuranceStartDate() . "',
                       `assetInsuranceExpiredDate` = '" . $this->model->getAssetInsuranceExpiredDate() . "',
                       `assetWarrantyStartDate` = '" . $this->model->getAssetWarrantyStartDate() . "',
                       `assetWarrantyEndDate` = '" . $this->model->getAssetWarrantyEndDate() . "',
                       `assetDepreciationRate` = '" . $this->model->getAssetDepreciationRate() . "',
                       `assetNetBookValue` = '" . $this->model->getAssetNetBookValue() . "',
                       `assetPicture` = '" . $this->model->getAssetPicture() . "',
                       `isDefault` = '" . $this->model->getIsDefault('0', 'single') . "',
                       `isNew` = '" . $this->model->getIsNew('0', 'single') . "',
                       `isDraft` = '" . $this->model->getIsDraft('0', 'single') . "',
                       `isUpdate` = '" . $this->model->getIsUpdate('0', 'single') . "',
                       `isDelete` = '" . $this->model->getIsDelete('0', 'single') . "',
                       `isActive` = '" . $this->model->getIsActive('0', 'single') . "',
                       `isApproved` = '" . $this->model->getIsApproved('0', 'single') . "',
                       `isReview` = '" . $this->model->getIsReview('0', 'single') . "',
                       `isPost` = '" . $this->model->getIsPost('0', 'single') . "',
                       `isTransferAsKit` = '" . $this->model->getIsTransferAsKit() . "',
                       `isDepreciate` = '" . $this->model->getIsDepreciate() . "',
                       `isWriteOff` = '" . $this->model->getIsWriteOff() . "',
                       `isDispose` = '" . $this->model->getIsDispose() . "',
                       `isAdjust` = '" . $this->model->getIsAdjust() . "',
                       `executeBy` = '" . $this->model->getExecuteBy('0', 'single') . "',
                       `executeTime` = " . $this->model->getExecuteTime() . "
               WHERE    `assetId`='" . $this->model->getAssetId('0', 'single') . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                UPDATE [asset] SET
                       [branchId] = '" . $this->model->getBranchId() . "',
                       [departmentId] = '" . $this->model->getDepartmentId() . "',
                       [warehouseId] = '" . $this->model->getWarehouseId() . "',
                       [locationId] = '" . $this->model->getLocationId() . "',
                       [itemCategoryId] = '" . $this->model->getItemCategoryId() . "',
                       [itemTypeId] = '" . $this->model->getItemTypeId() . "',
                       [businessPartnerId] = '" . $this->model->getBusinessPartnerId() . "',
                       [unitOfMeasurementId] = '" . $this->model->getUnitOfMeasurementId() . "',
                       [purchaseInvoiceId] = '" . $this->model->getPurchaseInvoiceId() . "',
                       [assetCode] = '" . $this->model->getAssetCode() . "',
                       [assetSerialNumber] = '" . $this->model->getAssetSerialNumber() . "',
                       [assetName] = '" . $this->model->getAssetName() . "',
                       [assetDescription] = '" . $this->model->getAssetDescription() . "',
                       [assetModel] = '" . $this->model->getAssetModel() . "',
                       [assetPrice] = '" . $this->model->getAssetPrice() . "',
                       [assetDate] = '" . $this->model->getAssetDate() . "',
                       [assetWarranty] = '" . $this->model->getAssetWarranty() . "',
                       [assetColor] = '" . $this->model->getAssetColor() . "',
                       [assetQuantity] = '" . $this->model->getAssetQuantity() . "',
                       [assetInsuranceBusinessPartnerId] = '" . $this->model->getAssetInsuranceBusinessPartnerId() . "',
                       [assetInsuranceStartDate] = '" . $this->model->getAssetInsuranceStartDate() . "',
                       [assetInsuranceExpiredDate] = '" . $this->model->getAssetInsuranceExpiredDate() . "',
                       [assetWarrantyStartDate] = '" . $this->model->getAssetWarrantyStartDate() . "',
                       [assetWarrantyEndDate] = '" . $this->model->getAssetWarrantyEndDate() . "',
                       [assetDepreciationRate] = '" . $this->model->getAssetDepreciationRate() . "',
                       [assetNetBookValue] = '" . $this->model->getAssetNetBookValue() . "',
                       [assetPicture] = '" . $this->model->getAssetPicture() . "',
                       [isDefault] = '" . $this->model->getIsDefault(0, 'single') . "',
                       [isNew] = '" . $this->model->getIsNew(0, 'single') . "',
                       [isDraft] = '" . $this->model->getIsDraft(0, 'single') . "',
                       [isUpdate] = '" . $this->model->getIsUpdate(0, 'single') . "',
                       [isDelete] = '" . $this->model->getIsDelete(0, 'single') . "',
                       [isActive] = '" . $this->model->getIsActive(0, 'single') . "',
                       [isApproved] = '" . $this->model->getIsApproved(0, 'single') . "',
                       [isReview] = '" . $this->model->getIsReview(0, 'single') . "',
                       [isPost] = '" . $this->model->getIsPost(0, 'single') . "',
                       [isTransferAsKit] = '" . $this->model->getIsTransferAsKit() . "',
                       [isDepreciate] = '" . $this->model->getIsDepreciate() . "',
                       [isWriteOff] = '" . $this->model->getIsWriteOff() . "',
                       [isDispose] = '" . $this->model->getIsDispose() . "',
                       [isAdjust] = '" . $this->model->getIsAdjust() . "',
                       [executeBy] = '" . $this->model->getExecuteBy(0, 'single') . "',
                       [executeTime] = " . $this->model->getExecuteTime() . "
                WHERE   [assetId]='" . $this->model->getAssetId('0', 'single') . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
                UPDATE ASSET SET
                        BRANCHID = '" . $this->model->getBranchId() . "',
                       DEPARTMENTID = '" . $this->model->getDepartmentId() . "',
                       WAREHOUSEID = '" . $this->model->getWarehouseId() . "',
                       LOCATIONID = '" . $this->model->getLocationId() . "',
                       ITEMCATEGORYID = '" . $this->model->getItemCategoryId() . "',
                       ITEMTYPEID = '" . $this->model->getItemTypeId() . "',
                       BUSINESSPARTNERID = '" . $this->model->getBusinessPartnerId() . "',
                       UNITOFMEASUREMENTID = '" . $this->model->getUnitOfMeasurementId() . "',
                       PURCHASEINVOICEID = '" . $this->model->getPurchaseInvoiceId() . "',
                       ASSETCODE = '" . $this->model->getAssetCode() . "',
                       ASSETSERIALNUMBER = '" . $this->model->getAssetSerialNumber() . "',
                       ASSETNAME = '" . $this->model->getAssetName() . "',
                       ASSETDESCRIPTION = '" . $this->model->getAssetDescription() . "',
                       ASSETMODEL = '" . $this->model->getAssetModel() . "',
                       ASSETPRICE = '" . $this->model->getAssetPrice() . "',
                       ASSETDATE = '" . $this->model->getAssetDate() . "',
                       ASSETWARRANTY = '" . $this->model->getAssetWarranty() . "',
                       ASSETCOLOR = '" . $this->model->getAssetColor() . "',
                       ASSETQUANTITY = '" . $this->model->getAssetQuantity() . "',
                       ASSETINSURANCEBUSINESSPARTNERID = '" . $this->model->getAssetInsuranceBusinessPartnerId() . "',
                       ASSETINSURANCESTARTDATE = '" . $this->model->getAssetInsuranceStartDate() . "',
                       ASSETINSURANCEEXPIREDDATE = '" . $this->model->getAssetInsuranceExpiredDate() . "',
                       ASSETWARRANTYSTARTDATE = '" . $this->model->getAssetWarrantyStartDate() . "',
                       ASSETWARRANTYENDDATE = '" . $this->model->getAssetWarrantyEndDate() . "',
                       ASSETDEPRECIATIONRATE = '" . $this->model->getAssetDepreciationRate() . "',
                       ASSETNETBOOKVALUE = '" . $this->model->getAssetNetBookValue() . "',
                       ASSETPICTURE = '" . $this->model->getAssetPicture() . "',
                       ISDEFAULT = '" . $this->model->getIsDefault(0, 'single') . "',
                       ISNEW = '" . $this->model->getIsNew(0, 'single') . "',
                       ISDRAFT = '" . $this->model->getIsDraft(0, 'single') . "',
                       ISUPDATE = '" . $this->model->getIsUpdate(0, 'single') . "',
                       ISDELETE = '" . $this->model->getIsDelete(0, 'single') . "',
                       ISACTIVE = '" . $this->model->getIsActive(0, 'single') . "',
                       ISAPPROVED = '" . $this->model->getIsApproved(0, 'single') . "',
                       ISREVIEW = '" . $this->model->getIsReview(0, 'single') . "',
                       ISPOST = '" . $this->model->getIsPost(0, 'single') . "',
                       ISTRANSFERASKIT = '" . $this->model->getIsTransferAsKit() . "',
                       ISDEPRECIATE = '" . $this->model->getIsDepreciate() . "',
                       ISWRITEOFF = '" . $this->model->getIsWriteOff() . "',
                       ISDISPOSE = '" . $this->model->getIsDispose() . "',
                       ISADJUST = '" . $this->model->getIsAdjust() . "',
                       EXECUTEBY = '" . $this->model->getExecuteBy(0, 'single') . "',
                       EXECUTETIME = " . $this->model->getExecuteTime() . "
                WHERE  ASSETID='" . $this->model->getAssetId('0', 'single') . "'";
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
           FROM 	`asset`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getAssetId(0, 'single') . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[asset]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getAssetId(0, 'single') . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	ASSET
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getAssetId(
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
               UPDATE  `asset`
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
               WHERE   `assetId`   =  '" . $this->model->getAssetId(0, 'single') . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
               UPDATE  [asset]
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
               WHERE   [assetId]	=  '" . $this->model->getAssetId(0, 'single') . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
               UPDATE  ASSET
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
               WHERE   ASSETID	=  '" . $this->model->getAssetId(0, 'single') . "'";
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
           SELECT  `assetCode`
           FROM    `asset`
           WHERE   `assetCode` 	= 	'" . $this->model->getAssetCode() . "'
           AND     `isActive`  =   1
           AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT  [assetCode]
           FROM    [asset]
           WHERE   [assetCode] = 	'" . $this->model->getAssetCode() . "'
           AND     [isActive]  =   1
           AND     [companyId] =	'" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
               SELECT  ASSETCODE as \"assetCode\"
               FROM    ASSET
               WHERE   ASSETCODE	= 	'" . $this->model->getAssetCode() . "'
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
     * Return  Branch
     * @return null|string
     */
    public function getBranch() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getBranch();
    }

    /**
     * Return  Department
     * @return null|string
     */
    public function getDepartment() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getDepartment();
    }

    /**
     * Return  Warehouse
     * @return null|string
     */
    public function getWarehouse() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getWarehouse();
    }

    /**
     * Return  Location
     * @return null|string
     */
    public function getLocation() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getLocation();
    }

    /**
     * Return  ItemCategory
     * @return null|string
     */
    public function getItemCategory() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getItemCategory();
    }

    /**
     * Return  ItemType
     * @return null|string
     */
    public function getItemType() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getItemType();
    }

    /**
     * Return  BusinessPartner
     * @return null|string
     */
    public function getBusinessPartner() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getBusinessPartner();
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
     * Return  PurchaseInvoice
     * @return null|string
     */
    public function getPurchaseInvoice() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getPurchaseInvoice();
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
                ->setSubject('asset')
                ->setDescription('Generated by PhpExcel an Idcms Generator')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('financial/fixedAsset');
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
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['branchIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['departmentIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['warehouseIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['locationIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['itemCategoryIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['itemTypeIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['businessPartnerIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['unitOfMeasurementIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['purchaseInvoiceIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('L3', $this->translate['assetCodeLabel']);
        $this->excel->getActiveSheet()->setCellValue('M3', $this->translate['assetSerialNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('N3', $this->translate['assetNameLabel']);
        $this->excel->getActiveSheet()->setCellValue('O3', $this->translate['assetDescriptionLabel']);
        $this->excel->getActiveSheet()->setCellValue('P3', $this->translate['assetModelLabel']);
        $this->excel->getActiveSheet()->setCellValue('Q3', $this->translate['assetPriceLabel']);
        $this->excel->getActiveSheet()->setCellValue('R3', $this->translate['assetDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('S3', $this->translate['assetWarrantyLabel']);
        $this->excel->getActiveSheet()->setCellValue('T3', $this->translate['assetColorLabel']);
        $this->excel->getActiveSheet()->setCellValue('U3', $this->translate['assetQuantityLabel']);
        $this->excel->getActiveSheet()->setCellValue('V3', $this->translate['assetInsuranceBusinessPartnerIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('W3', $this->translate['assetInsuranceStartDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('X3', $this->translate['assetInsuranceExpiredDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('Y3', $this->translate['assetWarrantyStartDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('Z3', $this->translate['assetWarrantyEndDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['assetDepreciationRateLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['assetNetBookValueLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['assetPictureLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['isTransferAsKitLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['isDepreciateLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['isWriteOffLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['isDisposeLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['isAdjustLabel']);
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
            $this->excel->getActiveSheet()->setCellValue('C' . $loopRow, strip_tags($row ['branchName']));
            $this->excel->getActiveSheet()->setCellValue('D' . $loopRow, strip_tags($row ['departmentDescription']));
            $this->excel->getActiveSheet()->setCellValue('E' . $loopRow, strip_tags($row ['warehouseDescription']));
            $this->excel->getActiveSheet()->setCellValue('F' . $loopRow, strip_tags($row ['locationDescription']));
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, strip_tags($row ['itemCategoryDescription']));
            $this->excel->getActiveSheet()->setCellValue('H' . $loopRow, strip_tags($row ['itemTypeDescription']));
            $this->excel->getActiveSheet()->setCellValue(
                    'I' . $loopRow, strip_tags($row ['businessPartnerDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue(
                    'J' . $loopRow, strip_tags($row ['unitOfMeasurementDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue(
                    'K' . $loopRow, strip_tags($row ['purchaseInvoiceDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue('L' . $loopRow, strip_tags($row ['assetCode']));
            $this->excel->getActiveSheet()->setCellValue('M' . $loopRow, strip_tags($row ['assetSerialNumber']));
            $this->excel->getActiveSheet()->setCellValue('N' . $loopRow, strip_tags($row ['assetName']));
            $this->excel->getActiveSheet()->setCellValue('O' . $loopRow, strip_tags($row ['assetDescription']));
            $this->excel->getActiveSheet()->setCellValue('P' . $loopRow, strip_tags($row ['assetModel']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('Q' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('Q' . $loopRow, strip_tags($row ['assetPrice']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('R' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $this->excel->getActiveSheet()->setCellValue('R' . $loopRow, strip_tags($row ['assetDate']));
            $this->excel->getActiveSheet()->setCellValue('S' . $loopRow, strip_tags($row ['assetWarranty']));
            $this->excel->getActiveSheet()->setCellValue('T' . $loopRow, strip_tags($row ['assetColor']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('U' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('U' . $loopRow, strip_tags($row ['assetQuantity']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('V' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue(
                    'V' . $loopRow, strip_tags($row ['assetInsuranceBusinessPartnerId'])
            );
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('W' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $this->excel->getActiveSheet()->setCellValue('W' . $loopRow, strip_tags($row ['assetInsuranceStartDate']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('X' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $this->excel->getActiveSheet()->setCellValue(
                    'X' . $loopRow, strip_tags($row ['assetInsuranceExpiredDate'])
            );
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('Y' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $this->excel->getActiveSheet()->setCellValue('Y' . $loopRow, strip_tags($row ['assetWarrantyStartDate']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('Z' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $this->excel->getActiveSheet()->setCellValue('Z' . $loopRow, strip_tags($row ['assetWarrantyEndDate']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['assetDepreciationRate']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['assetNetBookValue']));
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['assetPicture']));
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['isTransferAsKit']));
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['isDepreciate']));
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['isWriteOff']));
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['isDispose']));
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['isAdjust']));
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
                $filename = "asset" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/fixedAsset/document/" . $folder . "/" . $filename;
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
                $filename = "asset" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/fixedAsset/document/" . $folder . "/" . $filename;
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
                $filename = "asset" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/fixedAsset/document/" . $folder . "/" . $filename;
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
                $filename = "asset" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/fixedAsset/document/" . $folder . "/" . $filename;
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
        $assetObject = new AssetClass ();
        if ($_POST['securityToken'] != $assetObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $assetObject->setLeafId($_POST ['leafId']);
        }
        if (isset($_POST ['offset'])) {
            $assetObject->setStart($_POST ['offset']);
        }
        if (isset($_POST ['limit'])) {
            $assetObject->setLimit($_POST ['limit']);
        }
        $assetObject->setPageOutput($_POST['output']);
        $assetObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $assetObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $assetObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $assetObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $assetObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$assetObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$assetObject->delete(); 
        }
    }
}
if (isset($_GET ['method'])) {
    $assetObject = new AssetClass ();
    if ($_GET['securityToken'] != $assetObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $assetObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $assetObject->execute();
    /*
     * Update Status of The Table. Admin Level Only 
     */
    if ($_GET ['method'] == 'updateStatus') {
        $assetObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key 
     */
    if ($_GET['method'] == 'duplicate') {
        $assetObject->duplicate();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $assetObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $assetObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $assetObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $assetObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $assetObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $assetObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        $assetObject->setServiceOutput('option');
        if (($_GET['filter'] == 'branch')) {
            $assetObject->getBranch();
        }
        if (($_GET['filter'] == 'department')) {
            $assetObject->getDepartment();
        }
        if (($_GET['filter'] == 'warehouse')) {
            $assetObject->getWarehouse();
        }
        if (($_GET['filter'] == 'location')) {
            $assetObject->getLocation();
        }
        if (($_GET['filter'] == 'itemCategory')) {
            $assetObject->getItemCategory();
        }
        if (($_GET['filter'] == 'itemType')) {
            $assetObject->getItemType();
        }
        if (($_GET['filter'] == 'businessPartner')) {
            $assetObject->getBusinessPartner();
        }
        if (($_GET['filter'] == 'unitOfMeasurement')) {
            $assetObject->getUnitOfMeasurement();
        }
        if (($_GET['filter'] == 'purchaseInvoice')) {
            $assetObject->getPurchaseInvoice();
        }
    }
}
?>
