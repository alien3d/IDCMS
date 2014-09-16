<?php

namespace Core\Financial\BusinessPartner\BusinessPartner\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel;
use Core\Financial\BusinessPartner\BusinessPartner\Service\BusinessPartnerAccountPayableStatisticsService;
use Core\Financial\BusinessPartner\BusinessPartner\Service\BusinessPartnerAccountReceivableStatisticsService;
use Core\Financial\BusinessPartner\BusinessPartner\Service\BusinessPartnerCashBookStatisticsService;
use Core\Financial\BusinessPartner\BusinessPartner\Service\BusinessPartnerService;
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
require_once($newFakeDocumentRoot . "v3/financial/businessPartner/model/businessPartnerModel.php");
require_once($newFakeDocumentRoot . "v3/financial/businessPartner/service/businessPartnerService.php");

/**
 * Class BusinessPartner
 * this is businessPartner controller files.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package  Core\Financial\BusinessPartner\BusinessPartner\Controller
 * @subpackage BusinessPartner
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class BusinessPartnerClass extends ConfigClass {

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
     * @var \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public $model;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     * @var \PHPPowerPoint
     */
    //private $powerPoint; 
    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\Financial\BusinessPartner\BusinessPartner\Service\BusinessPartnerService
     */
    public $service;

    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\Financial\BusinessPartner\BusinessPartner\Service\BusinessPartnerCashBookStatisticsService
     */
    public $serviceCashBook;

    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\Financial\BusinessPartner\BusinessPartner\Service\BusinessPartnerAccountReceivableStatisticsService
     */
    public $serviceAccountReceivable;

    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\Financial\BusinessPartner\BusinessPartner\Service\BusinessPartnerAccountPayableStatisticsService
     */
    public $serviceAccountPayable;

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
        $this->setViewPath("./v3/financial/businessPartner/view/businessPartner.php");
        $this->setControllerPath("./v3/financial/businessPartner/controller/businessPartnerController.php");
        $this->setServicePath("./v3/financial/businessPartner/service/businessPartnerService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new BusinessPartnerModel();
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

        $this->service = new BusinessPartnerService();
        $this->service->q = $this->q;
        $this->service->t = $this->t;
        $this->service->setVendor($this->getVendor());
        $this->service->setServiceOutput($this->getServiceOutput());
        $this->service->execute();

        $this->serviceCashBook = new BusinessPartnerCashBookStatisticsService();
        $this->serviceCashBook->q = $this->q;
        $this->serviceCashBook->t = $this->t;
        $this->serviceCashBook->setVendor($this->getVendor());
        $this->serviceCashBook->setServiceOutput($this->getServiceOutput());
        $this->serviceCashBook->execute();

        $this->serviceAccountReceivable = new BusinessPartnerAccountReceivableStatisticsService();
        $this->serviceAccountReceivable->q = $this->q;
        $this->serviceAccountReceivable->t = $this->t;
        $this->serviceAccountReceivable->setVendor($this->getVendor());
        $this->serviceAccountReceivable->setServiceOutput($this->getServiceOutput());
        $this->serviceAccountReceivable->execute();

        $this->serviceAccountPayable = new BusinessPartnerAccountPayableStatisticsService();
        $this->serviceAccountPayable->q = $this->q;
        $this->serviceAccountPayable->t = $this->t;
        $this->serviceAccountReceivable->setVendor($this->getVendor());
        $this->serviceAccountPayable->setServiceOutput($this->getServiceOutput());
        $this->serviceAccountPayable->execute();

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
        if (!$this->model->getBusinessPartnerCategoryId()) {
            $this->model->setBusinessPartnerCategoryId($this->service->getBusinessPartnerCategoryDefaultValue());
        }
        if (!$this->model->getBusinessPartnerOfficeCountryId()) {
            $this->model->setBusinessPartnerOfficeCountryId(
                    $this->service->getBusinessPartnerOfficeCountryDefaultValue()
            );
        }
        if (!$this->model->getBusinessPartnerOfficeStateId()) {
            $this->model->setBusinessPartnerOfficeStateId($this->service->getBusinessPartnerOfficeStateDefaultValue());
        }
        if (!$this->model->getBusinessPartnerOfficeCityId()) {
            $this->model->setBusinessPartnerOfficeCityId($this->service->getBusinessPartnerOfficeCityDefaultValue());
        }
        if (!$this->model->getBusinessPartnerShippingCountryId()) {
            $this->model->setBusinessPartnerShippingCountryId(
                    $this->service->getBusinessPartnerShippingCountryDefaultValue()
            );
        }
        if (!$this->model->getBusinessPartnerShippingStateId()) {
            $this->model->setBusinessPartnerShippingStateId(
                    $this->service->getBusinessPartnerShippingStateDefaultValue()
            );
        }
        if (!$this->model->getBusinessPartnerShippingCityId()) {
            $this->model->setBusinessPartnerShippingCityId(
                    $this->service->getBusinessPartnerShippingCityDefaultValue()
            );
        }
        //$this->model->setDocumentNumber($this->getDocumentNumber);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `businesspartner` 
            (
                 `companyId`,
                 `businessPartnerCategoryId`,
                 `businessPartnerOfficeCountryId`,
                 `businessPartnerOfficeStateId`,
                 `businessPartnerOfficeCityId`,
                 `businessPartnerShippingCountryId`,
                 `businessPartnerShippingStateId`,
                 `businessPartnerShippingCityId`,
                 `businessPartnerCode`,
                 `businessPartnerRegistrationNumber`,
                 `businessPartnerTaxNumber`,
                 `businessPartnerCompany`,
                 `businessPartnerBusinessPhone`,
                 `businessPartnerMobilePhone`,
                 `businessPartnerFaxNumber`,
                 `businessPartnerOfficeAddress`,
                 `businessPartnerShippingAddress`,
                 `businessPartnerOfficePostCode`,
                 `businessPartnerShippingPostCode`,
                 `businessPartnerEmail`,
                 `businessPartnerWebPage`,
                 `businessPartnerFacebook`,
                 `businessPartnerTwitter`,
                 `businessPartnerNotes`,
                 `businessPartnerDate`,
                 `businessPartnerChequePrinting`,
                 `businessPartnerCreditTerm`,
                 `businessPartnerCreditLimit`,
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
                 '" . $this->model->getBusinessPartnerCategoryId() . "',
                 '" . $this->model->getBusinessPartnerOfficeCountryId() . "',
                 '" . $this->model->getBusinessPartnerOfficeStateId() . "',
                 '" . $this->model->getBusinessPartnerOfficeCityId() . "',
                 '" . $this->model->getBusinessPartnerShippingCountryId() . "',
                 '" . $this->model->getBusinessPartnerShippingStateId() . "',
                 '" . $this->model->getBusinessPartnerShippingCityId() . "',
                 '" . $this->model->getBusinessPartnerCode() . "',
                 '" . $this->model->getBusinessPartnerRegistrationNumber() . "',
                 '" . $this->model->getBusinessPartnerTaxNumber() . "',
                 '" . $this->model->getBusinessPartnerCompany() . "',
                 '" . $this->model->getBusinessPartnerBusinessPhone() . "',
                 '" . $this->model->getBusinessPartnerMobilePhone() . "',
                 '" . $this->model->getBusinessPartnerFaxNumber() . "',
                 '" . $this->model->getBusinessPartnerOfficeAddress() . "',
                 '" . $this->model->getBusinessPartnerShippingAddress() . "',
                 '" . $this->model->getBusinessPartnerOfficePostCode() . "',
                 '" . $this->model->getBusinessPartnerShippingPostCode() . "',
                 '" . $this->model->getBusinessPartnerEmail() . "',
                 '" . $this->model->getBusinessPartnerWebPage() . "',
                 '" . $this->model->getBusinessPartnerFacebook() . "',
                 '" . $this->model->getBusinessPartnerTwitter() . "',
                 '" . $this->model->getBusinessPartnerNotes() . "',
                 '" . $this->model->getBusinessPartnerDate() . "',
                 '" . $this->model->getBusinessPartnerChequePrinting() . "',
                 '" . $this->model->getBusinessPartnerCreditTerm() . "',
                 '" . $this->model->getBusinessPartnerCreditLimit() . "',
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
            INSERT INTO [businessPartner]
            (
                 [businessPartnerId],
                 [companyId],
                 [businessPartnerCategoryId],
                 [businessPartnerOfficeCountryId],
                 [businessPartnerOfficeStateId],
                 [businessPartnerOfficeCityId],
                 [businessPartnerShippingCountryId],
                 [businessPartnerShippingStateId],
                 [businessPartnerShippingCityId],
                 [businessPartnerCode],
                 [businessPartnerRegistrationNumber],
                 [businessPartnerTaxNumber],
                 [businessPartnerCompany],
                 [businessPartnerBusinessPhone],
                 [businessPartnerMobilePhone],
                 [businessPartnerFaxNumber],
                 [businessPartnerOfficeAddress],
                 [businessPartnerShippingAddress],
                 [businessPartnerOfficePostCode],
                 [businessPartnerShippingPostCode],
                 [businessPartnerEmail],
                 [businessPartnerWebPage],
                 [businessPartnerFacebook],
                 [businessPartnerTwitter],
                 [businessPartnerNotes],
                 [businessPartnerDate],
                 [businessPartnerChequePrinting],
                 [businessPartnerCreditTerm],
                 [businessPartnerCreditLimit],
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
                 '" . $this->model->getBusinessPartnerCategoryId() . "',
                 '" . $this->model->getBusinessPartnerOfficeCountryId() . "',
                 '" . $this->model->getBusinessPartnerOfficeStateId() . "',
                 '" . $this->model->getBusinessPartnerOfficeCityId() . "',
                 '" . $this->model->getBusinessPartnerShippingCountryId() . "',
                 '" . $this->model->getBusinessPartnerShippingStateId() . "',
                 '" . $this->model->getBusinessPartnerShippingCityId() . "',
                 '" . $this->model->getBusinessPartnerCode() . "',
                 '" . $this->model->getBusinessPartnerRegistrationNumber() . "',
                 '" . $this->model->getBusinessPartnerTaxNumber() . "',
                 '" . $this->model->getBusinessPartnerCompany() . "',
                 '" . $this->model->getBusinessPartnerBusinessPhone() . "',
                 '" . $this->model->getBusinessPartnerMobilePhone() . "',
                 '" . $this->model->getBusinessPartnerFaxNumber() . "',
                 '" . $this->model->getBusinessPartnerOfficeAddress() . "',
                 '" . $this->model->getBusinessPartnerShippingAddress() . "',
                 '" . $this->model->getBusinessPartnerOfficePostCode() . "',
                 '" . $this->model->getBusinessPartnerShippingPostCode() . "',
                 '" . $this->model->getBusinessPartnerEmail() . "',
                 '" . $this->model->getBusinessPartnerWebPage() . "',
                 '" . $this->model->getBusinessPartnerFacebook() . "',
                 '" . $this->model->getBusinessPartnerTwitter() . "',
                 '" . $this->model->getBusinessPartnerNotes() . "',
                 '" . $this->model->getBusinessPartnerDate() . "',
                 '" . $this->model->getBusinessPartnerChequePrinting() . "',
                 '" . $this->model->getBusinessPartnerCreditTerm() . "',
                 '" . $this->model->getBusinessPartnerCreditLimit() . "',
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
            INSERT INTO BUSINESSPARTNER
            (
                 COMPANYID,
                 BUSINESSPARTNERCATEGORYID,
                 BUSINESSPARTNEROFFICECOUNTRYID,
                 BUSINESSPARTNEROFFICESTATEID,
                 BUSINESSPARTNEROFFICECITYID,
                 BUSINESSPARTNERSHIPPINGCOUNTRYID,
                 BUSINESSPARTNERSHIPPINGSTATEID,
                 BUSINESSPARTNERSHIPPINGCITYID,
                 BUSINESSPARTNERCODE,
                 BUSINESSPARTNERREGISTRATIONNUMBER,
                 BUSINESSPARTNERTAXNUMBER,
                 BUSINESSPARTNERCOMPANY,
                 BUSINESSPARTNERBUSINESSPHONE,
                 BUSINESSPARTNERMOBILEPHONE,
                 BUSINESSPARTNERFAXNUM,
                 BUSINESSPARTNEROFFICEADDRESS,
                 BUSINESSPARTNERSHIPPINGADDRESS,
                 BUSINESSPARTNEROFFICEPOSTCODE,
                 BUSINESSPARTNERSHIPPINGPOSTCODE,
                 BUSINESSPARTNEREMAIL,
                 BUSINESSPARTNERWEBPAGE,
                 BUSINESSPARTNERFACEBOOK,
                 BUSINESSPARTNERTWITTER,
                 BUSINESSPARTNERNOTES,
                 BUSINESSPARTNERDATE,
                 BUSINESSPARTNERCHEQUEPRINTING,
                 BUSINESSPARTNERCREDITTERM,
                 BUSINESSPARTNERCREDITLIMIT,
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
                 '" . $this->model->getBusinessPartnerCategoryId() . "',
                 '" . $this->model->getBusinessPartnerOfficeCountryId() . "',
                 '" . $this->model->getBusinessPartnerOfficeStateId() . "',
                 '" . $this->model->getBusinessPartnerOfficeCityId() . "',
                 '" . $this->model->getBusinessPartnerShippingCountryId() . "',
                 '" . $this->model->getBusinessPartnerShippingStateId() . "',
                 '" . $this->model->getBusinessPartnerShippingCityId() . "',
                 '" . $this->model->getBusinessPartnerCode() . "',
                 '" . $this->model->getBusinessPartnerRegistrationNumber() . "',
                 '" . $this->model->getBusinessPartnerTaxNumber() . "',
                 '" . $this->model->getBusinessPartnerCompany() . "',
                 '" . $this->model->getBusinessPartnerBusinessPhone() . "',
                 '" . $this->model->getBusinessPartnerMobilePhone() . "',
                 '" . $this->model->getBusinessPartnerFaxNumber() . "',
                 '" . $this->model->getBusinessPartnerOfficeAddress() . "',
                 '" . $this->model->getBusinessPartnerShippingAddress() . "',
                 '" . $this->model->getBusinessPartnerOfficePostCode() . "',
                 '" . $this->model->getBusinessPartnerShippingPostCode() . "',
                 '" . $this->model->getBusinessPartnerEmail() . "',
                 '" . $this->model->getBusinessPartnerWebPage() . "',
                 '" . $this->model->getBusinessPartnerFacebook() . "',
                 '" . $this->model->getBusinessPartnerTwitter() . "',
                 '" . $this->model->getBusinessPartnerNotes() . "',
                 '" . $this->model->getBusinessPartnerDate() . "',
                 '" . $this->model->getBusinessPartnerChequePrinting() . "',
                 '" . $this->model->getBusinessPartnerCreditTerm() . "',
                 '" . $this->model->getBusinessPartnerCreditLimit() . "',
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
        $businessPartnerId = $this->q->lastInsertId();
        // additional update image
        $this->service->setTransferBusinessPartnerPicture($this->getLeafId(), $businessPartnerId);
        // end additional update image
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
                    "businessPartnerId" => $businessPartnerId,
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
         FROM    `businesspartner`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT    COUNT(*) AS total
         FROM      [businessPartner]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT    COUNT(*)    AS  \"total\"
         FROM      BUSINESSPARTNER
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
                            " `bp`.`isActive` = 1  AND `bp`.`companyId`='" . $this->getCompanyId() . "' "
                    );
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [bp].[isActive] = 1 AND [bp].[companyId]='" . $this->getCompanyId() . "' "
                        );
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(" BP.ISACTIVE = 1  AND BP.COMPANYID='" . $this->getCompanyId() . "'");
                        }
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter("   `bp`.`companyId`='" . $this->getCompanyId() . "'	");
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(" [bp].[companyId]='" . $this->getCompanyId() . "' ");
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(" BP.COMPANYID='" . $this->getCompanyId() . "' ");
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
       SELECT       `bp`.`businessPartnerId`,
                    `company`.`companyDescription`,
                    `bp`.`companyId`,
                    `businesspartnercategory`.`businessPartnerCategoryDescription`,
                    `bp`.`businessPartnerCategoryId`,

                    (
						SELECT	`countryDescription`
						FROM 	`country`
						WHERE	`country`.`countryId` = `bp`.`businessPartnerOfficeCountryId`
					) AS `businessPartnerOfficeCountryDescription`,
                    `bp`.`businessPartnerOfficeCountryId`,

                     (
						SELECT	`stateDescription`
						FROM 	`state`
						WHERE	`state`.`stateId` = `bp`.`businessPartnerOfficeStateId`
					) AS `businessPartnerOfficeStateDescription`,
                    `bp`.`businessPartnerOfficeStateId`,

                     (
						SELECT	`cityDescription`
						FROM 	`city`
						WHERE	`city`.`cityId` = `bp`.`businessPartnerOfficeCityId`
					) AS `businessPartnerOfficeCityDescription`,
                    `bp`.`businessPartnerOfficeCityId`,

					 (
						SELECT	`countryDescription`
						FROM 	`country`
						WHERE	`country`.`countryId` = `bp`.`businessPartnerShippingCountryId`
					) AS `businessPartnerShippingCountryDescription`,
                    `bp`.`businessPartnerShippingCountryId`,

					 (
						SELECT	`stateDescription`
						FROM 	`state`
						WHERE	`state`.`stateId` = `bp`.`businessPartnerShippingStateId`
					) AS `businessPartnerShippingStateDescription`,
                    `bp`.`businessPartnerShippingStateId`,

					(
						SELECT	`cityDescription`
						FROM 	`city`
						WHERE	`city`.`cityId` = `bp`.`businessPartnerShippingCityId`
					) AS `businessPartnerShippingCityDescription`,
                    `bp`.`businessPartnerShippingCityId`,

                    `bp`.`businessPartnerCode`,
                    `bp`.`businessPartnerRegistrationNumber`,
                    `bp`.`businessPartnerTaxNumber`,
                    `bp`.`businessPartnerCompany`,
                    `bp`.`businessPartnerPicture`,
                    `bp`.`businessPartnerBusinessPhone`,
                    `bp`.`businessPartnerMobilePhone`,
                    `bp`.`businessPartnerFaxNumber`,
                    `bp`.`businessPartnerOfficeAddress`,
                    `bp`.`businessPartnerShippingAddress`,
                    `bp`.`businessPartnerOfficePostCode`,
                    `bp`.`businessPartnerShippingPostCode`,
                    `bp`.`businessPartnerEmail`,
                    `bp`.`businessPartnerWebPage`,
                    `bp`.`businessPartnerFacebook`,
                    `bp`.`businessPartnerTwitter`,
                    `bp`.`businessPartnerNotes`,
                    `bp`.`businessPartnerDate`,
                    `bp`.`businessPartnerChequePrinting`,
                    `bp`.`businessPartnerCreditTerm`,
                    `bp`.`businessPartnerCreditLimit`,
                    `bp`.`isDefault`,
                    `bp`.`isNew`,
                    `bp`.`isDraft`,
                    `bp`.`isUpdate`,
                    `bp`.`isDelete`,
                    `bp`.`isActive`,
                    `bp`.`isApproved`,
                    `bp`.`isReview`,
                    `bp`.`isPost`,
                    `bp`.`executeBy`,
                    `bp`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `businesspartner` AS `bp`
		  JOIN      `staff`
		  ON        `bp`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `bp`.`companyId`
	JOIN	`businesspartnercategory`
	ON		`businesspartnercategory`.`businessPartnerCategoryId` = `bp`.`businessPartnerCategoryId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getBusinessPartnerId(0, 'single')) {
                $sql .= " AND `bp`.`" . $this->model->getPrimaryKeyName() . "`='" . $this->model->getBusinessPartnerId(
                                0, 'single'
                        ) . "'";
            }
            if ($this->model->getBusinessPartnerCategoryId()) {
                $sql .= " AND `bp`.`businessPartnerCategoryId`='" . $this->model->getBusinessPartnerCategoryId() . "'";
            }
            if ($this->model->getBusinessPartnerOfficeCountryId()) {
                $sql .= " AND `bp`.`businessPartnerOfficeCountryId`='" . $this->model->getBusinessPartnerOfficeCountryId(
                        ) . "'";
            }
            if ($this->model->getBusinessPartnerOfficeStateId()) {
                $sql .= " AND `bp`.`businessPartnerOfficeStateId`='" . $this->model->getBusinessPartnerOfficeStateId(
                        ) . "'";
            }
            if ($this->model->getBusinessPartnerOfficeCityId()) {
                $sql .= " AND `bp`.`businessPartnerOfficeCityId`='" . $this->model->getBusinessPartnerOfficeCityId(
                        ) . "'";
            }
            if ($this->model->getBusinessPartnerShippingCountryId()) {
                $sql .= " AND `bp`.`businessPartnerShippingCountryId`='" . $this->model->getBusinessPartnerShippingCountryId(
                        ) . "'";
            }
            if ($this->model->getBusinessPartnerShippingStateId()) {
                $sql .= " AND `bp`.`businessPartnerShippingStateId`='" . $this->model->getBusinessPartnerShippingStateId(
                        ) . "'";
            }
            if ($this->model->getBusinessPartnerShippingCityId()) {
                $sql .= " AND `bp`.`businessPartnerShippingCityId`='" . $this->model->getBusinessPartnerShippingCityId(
                        ) . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
		  SELECT    [bp].[businessPartnerId],
                    [company].[companyDescription],
                    [bp].[companyId],
                    [businessPartnerCategory].[businessPartnerCategoryDescription],
                    [bp].[businessPartnerCategoryId],
                    [businessPartnerOfficeCountry].[businessPartnerOfficeCountryDescription],
                    [bp].[businessPartnerOfficeCountryId],
					(
						SELECT  [countryDescription]
						FROM	[country]
						WHERE	[country].[countryId] = [bp].[businessPartnerOfficeCountryId]
					)
                    AS [businessPartnerOfficeStateDescription],
                    [bp].[businessPartnerOfficeStateId],
					(
						SELECT  [cityDescription]
						FROM	[city]
						WHERE	[city].[cityId] = [bp].[businessPartnerOfficeCityId]
					)
                    AS [businessPartnerOfficeCityDescription],
                    [bp].[businessPartnerOfficeCityId],
                    (
						SELECT  [countryDescription]
						FROM	[country]
						WHERE	[country].[countryId = [bp].[businessPartnerShippingStateId]
					)
					AS [businessPartnerShippingCountryDescription],
                    [bp].[businessPartnerShippingCountryId],
                    (
						SELECT  [stateDescription]
						FROM	[state]
						WHERE	[state].[stateId] = [bp].[businessPartnerShippingStateId]
					)
					AS [businessPartnerShippingStateDescription],
                    [bp].[businessPartnerShippingStateId],
                     (
						SELECT  [cityDescription]
						FROM	[city]
						WHERE	[city].[cityId] = [bp].[businessPartnerShippingCityId]
					)
					 AS [businessPartnerShippingCityDescription],
                    [bp].[businessPartnerShippingCityId],
                    [bp].[businessPartnerCode],
                    [bp].[businessPartnerRegistrationNumber],
                    [bp].[businessPartnerTaxNumber],
                    [bp].[businessPartnerCompany],
                    [bp].[businessPartnerPicture],
                    [bp].[businessPartnerBusinessPhone],
                    [bp].[businessPartnerMobilePhone],
                    [bp].[businessPartnerFaxNumber],
                    [bp].[businessPartnerOfficeAddress],
                    [bp].[businessPartnerShippingAddress],
                    [bp].[businessPartnerOfficePostCode],
                    [bp].[businessPartnerShippingPostCode],
                    [bp].[businessPartnerEmail],
                    [bp].[businessPartnerWebPage],
                    [bp].[businessPartnerFacebook],
                    [bp].[businessPartnerTwitter],
                    [bp].[businessPartnerNotes],
                    [bp].[businessPartnerDate],
                    [bp].[businessPartnerChequePrinting],
                    [bp].[businessPartnerCreditTerm],
                    [bp].[businessPartnerCreditLimit],
                    [bp].[isDefault],
                    [bp].[isNew],
                    [bp].[isDraft],
                    [bp].[isUpdate],
                    [bp].[isDelete],
                    [bp].[isActive],
                    [bp].[isApproved],
                    [bp].[isReview],
                    [bp].[isPost],
                    [bp].[executeBy],
                    [bp].[executeTime],
                    [staff].[staffName]
		  FROM 	[businessPartner] AS [bp]
		  JOIN	[staff]
		  ON	[businessPartner].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [bp].[companyId]
	JOIN	[businessPartnerCategory]
	ON		[businessPartnerCategory].[businessPartnerCategoryId] = [bp].[businessPartnerCategoryId]
		  WHERE     " . $this->getAuditFilter();
                if ($this->model->getBusinessPartnerId(0, 'single')) {
                    $sql .= " AND [bp].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getBusinessPartnerId(0, 'single') . "'";
                }
                if ($this->model->getBusinessPartnerCategoryId()) {
                    $sql .= " AND [bp].[businessPartnerCategoryId]='" . $this->model->getBusinessPartnerCategoryId(
                            ) . "'";
                }
                if ($this->model->getBusinessPartnerOfficeCountryId()) {
                    $sql .= " AND [bp].[businessPartnerOfficeCountryId]='" . $this->model->getBusinessPartnerOfficeCountryId(
                            ) . "'";
                }
                if ($this->model->getBusinessPartnerOfficeStateId()) {
                    $sql .= " AND [bp].[businessPartnerOfficeStateId]='" . $this->model->getBusinessPartnerOfficeStateId(
                            ) . "'";
                }
                if ($this->model->getBusinessPartnerOfficeCityId()) {
                    $sql .= " AND [bp].[businessPartnerOfficeCityId]='" . $this->model->getBusinessPartnerOfficeCityId(
                            ) . "'";
                }
                if ($this->model->getBusinessPartnerShippingCountryId()) {
                    $sql .= " AND [bp].[businessPartnerShippingCountryId]='" . $this->model->getBusinessPartnerShippingCountryId(
                            ) . "'";
                }
                if ($this->model->getBusinessPartnerShippingStateId()) {
                    $sql .= " AND [bp].[businessPartnerShippingStateId]='" . $this->model->getBusinessPartnerShippingStateId(
                            ) . "'";
                }
                if ($this->model->getBusinessPartnerShippingCityId()) {
                    $sql .= " AND [bp].[businessPartnerShippingCityId]='" . $this->model->getBusinessPartnerShippingCityId(
                            ) . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
		  SELECT    BP.BUSINESSPARTNERID AS \"businessPartnerId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    BP.COMPANYID AS \"companyId\",
                    BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYDESCRIPTION AS  \"businessPartnerCategoryDescription\",
                    bp.BUSINESSPARTNERCATEGORYID AS \"businessPartnerCategoryId\",

                    (
						SELECT 	COUNTRYDESCRIPTION
						FROM	COUNTRY
						WHERE   COUNTRY.COUNTRYID = BP.BUSINESSPARTNEROFFICECOUNTRYID
					)
					AS  \"businessPartnerOfficeCountryDescription\",
                    BP.BUSINESSPARTNEROFFICECOUNTRYID AS \"businessPartnerOfficeCountryId\",

					(
						SELECT 	STATEDESCRIPTION
						FROM	STATE
						WHERE   STATE.STATEID = BP.BUSINESSPARTNEROFFICESTATEID
					)
                    AS  \"businessPartnerOfficeStateDescription\",
                    BP.BUSINESSPARTNEROFFICESTATEID AS \"businessPartnerOfficeStateId\",

                    (
						SELECT 	CITYDESCRIPTION
						FROM	CITY
						WHERE   CITY.CITYID = BP.BUSINESSPARTNEROFFICECITYID
					)
					AS  \"businessPartnerOfficeCityDescription\",
                    BP.BUSINESSPARTNEROFFICECITYID AS \"businessPartnerOfficeCityId\",

					(
						SELECT 	COUNTRYDESCRIPTION
						FROM	COUNTRY
						WHERE   COUNTRY.COUNTRYID = BP.BUSINESSPARTNERSHIPPINGCOUNTRYID
					)
                    AS  \"businessPartnerShippingCountryDescription\",
                    BP.BUSINESSPARTNERSHIPPINGCOUNTRYID AS \"businessPartnerShippingCountryId\",
                    (
						SELECT 	STATEDESCRIPTION
						FROM	STATE
						WHERE   STATE.STATEID = BP.BUSINESSPARTNERSHIPPINGSTATEID
					)
					AS  \"businessPartnerShippingStateDescription\",
                    BUSINESSPARTNER.BUSINESSPARTNERSHIPPINGSTATEID AS \"businessPartnerShippingStateId\",

                    (
						SELECT 	CITYDESCRIPTION
						FROM	CITY
						WHERE   CITY.COUNTRYID = BP.BUSINESSPARTNERSHIPPINGCITYID
					)
					AS  \"businessPartnerShippingCityDescription\",
                    BP.BUSINESSPARTNERSHIPPINGCITYID AS \"businessPartnerShippingCityId\",

                    BP.BUSINESSPARTNERCODE AS \"businessPartnerCode\",
                    BP.BUSINESSPARTNERREGISTRATIONNUMBER AS \"businessPartnerRegistrationNumber\",
                    BP.BUSINESSPARTNERTAXNUMBER AS \"businessPartnerTaxNumber\",
                    BP.BUSINESSPARTNERCOMPANY AS \"businessPartnerCompany\",
                    BP.BUSINESSPARTNERPICTURE AS \"businessPartnerPicture\",
                    BP.BUSINESSPARTNERBUSINESSPHONE AS \"businessPartnerBusinessPhone\",
                    BP.BUSINESSPARTNERMOBILEPHONE AS \"businessPartnerMobilePhone\",
                    BP.BUSINESSPARTNERFAXNUM AS \"businessPartnerFaxNumber\",
                    BP.BUSINESSPARTNEROFFICEADDRESS AS \"businessPartnerOfficeAddress\",
                    BP.BUSINESSPARTNERSHIPPINGADDRESS AS \"businessPartnerShippingAddress\",
                    BP.BUSINESSPARTNEROFFICEPOSTCODE AS \"businessPartnerOfficePostCode\",
                    BP.BUSINESSPARTNERSHIPPINGPOSTCODE AS \"businessPartnerShippingPostCode\",
                    BP.BUSINESSPARTNEREMAIL AS \"businessPartnerEmail\",
                    BP.BUSINESSPARTNERWEBPAGE AS \"businessPartnerWebPage\",
                    BP.BUSINESSPARTNERFACEBOOK AS \"businessPartnerFacebook\",
                    BP.BUSINESSPARTNERTWITTER AS \"businessPartnerTwitter\",
                    BP.BUSINESSPARTNERNOTES AS \"businessPartnerNotes\",
                    BP.BUSINESSPARTNERDATE AS \"businessPartnerDate\",
                    BP.BUSINESSPARTNERCHEQUEPRINTING AS \"businessPartnerChequePrinting\",
                    BP.BUSINESSPARTNERCREDITTERM AS \"businessPartnerCreditTerm\",
                    BP.BUSINESSPARTNERCREDITLIMIT AS \"businessPartnerCreditLimit\",
                    BP.ISDEFAULT AS \"isDefault\",
                    BP.ISNEW AS \"isNew\",
                    BP.ISDRAFT AS \"isDraft\",
                    BP.ISUPDATE AS \"isUpdate\",
                    BP.ISDELETE AS \"isDelete\",
                    BP.ISACTIVE AS \"isActive\",
                    BP.ISAPPROVED AS \"isApproved\",
                    BP.ISREVIEW AS \"isReview\",
                    bp.ISPOST AS \"isPost\",
                    BP.EXECUTEBY AS \"executeBy\",
                    BP.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\"
		  FROM 	BUSINESSPARTNER AS \"BP\"
		  JOIN	STAFF
		  ON	bp.EXECUTEBY = STAFF.STAFFID
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = BP.COMPANYID
	JOIN	BUSINESSPARTNERCATEGORY
	ON		BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYID = BP.BUSINESSPARTNERCATEGORYID
         WHERE     " . $this->getAuditFilter();
                    if ($this->model->getBusinessPartnerId(0, 'single')) {
                        $sql .= " AND bp. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getBusinessPartnerId(0, 'single') . "'";
                    }
                    if ($this->model->getBusinessPartnerCategoryId()) {
                        $sql .= " AND bp.BUSINESSPARTNERCATEGORYID='" . $this->model->getBusinessPartnerCategoryId(
                                ) . "'";
                    }
                    if ($this->model->getBusinessPartnerOfficeCountryId()) {
                        $sql .= " AND bp.BUSINESSPARTNEROFFICECOUNTRYID='" . $this->model->getBusinessPartnerOfficeCountryId(
                                ) . "'";
                    }
                    if ($this->model->getBusinessPartnerOfficeStateId()) {
                        $sql .= " AND bp.BUSINESSPARTNEROFFICESTATEID='" . $this->model->getBusinessPartnerOfficeStateId(
                                ) . "'";
                    }
                    if ($this->model->getBusinessPartnerOfficeCityId()) {
                        $sql .= " AND bp.BUSINESSPARTNEROFFICECITYID='" . $this->model->getBusinessPartnerOfficeCityId(
                                ) . "'";
                    }
                    if ($this->model->getBusinessPartnerShippingCountryId()) {
                        $sql .= " AND bp.BUSINESSPARTNERSHIPPINGCOUNTRYID='" . $this->model->getBusinessPartnerShippingCountryId(
                                ) . "'";
                    }
                    if ($this->model->getBusinessPartnerShippingStateId()) {
                        $sql .= " AND bp.BUSINESSPARTNERSHIPPINGSTATEID='" . $this->model->getBusinessPartnerShippingStateId(
                                ) . "'";
                    }
                    if ($this->model->getBusinessPartnerShippingCityId()) {
                        $sql .= " AND bp.BUSINESSPARTNERSHIPPINGCITYID='" . $this->model->getBusinessPartnerShippingCityId(
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
                $sql .= " AND `bp`.`" . $this->model->getFilterCharacter() . "` like '" . $this->getCharacterQuery(
                        ) . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [bp].[" . $this->model->getFilterCharacter() . "] like '" . $this->getCharacterQuery(
                            ) . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND Initcap(BP." . strtoupper(
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
                        'bp', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'bp', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'BP', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
                "`bp`.`businessPartnerId`",
                "`staff`.`staffPassword`"
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $filterArray = array(
                    "[bp].[businessPartnerId]",
                    "[staff].[staffPassword]"
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $filterArray = array(
                        "BP.BUSINESSPARTNERID",
                        "STAFF.STAFFPASSWORD"
                    );
                }
            }
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array(
                array('businesspartner' => 'bp'),
                'businesspartnercategory'
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array(
                    array('businesspartner' => 'bp'),
                    'businesspartnercategory'
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array(
                        array('BUSINESSPARTNER' => 'bp'),
                        'BUSINESSPARTNERCATEGORY'
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
		// experiment push..

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
                     */
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
        if (!($this->model->getBusinessPartnerId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 41;
            if ($this->model->getBusinessPartnerId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord(
                        'value', $this->model->getBusinessPartnerId(0, 'single')
                );
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getBusinessPartnerId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else {
            if ($this->getPageOutput() == 'json') {
                if ($this->model->getBusinessPartnerId(0, 'single')) {
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
                                                'value', $this->model->getBusinessPartnerId(0, 'single')
                                        ),
                                        'nextRecord' => $this->nextRecord(
                                                'value', $this->model->getBusinessPartnerId(0, 'single')
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
                                        'value', $this->model->getBusinessPartnerId(0, 'single')
                                ),
                                'nextRecord' => $this->recordSet->nextRecord(
                                        'value', $this->model->getBusinessPartnerId(0, 'single')
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
        if (!$this->model->getBusinessPartnerCategoryId()) {
            $this->model->setBusinessPartnerCategoryId($this->service->getBusinessPartnerCategoryDefaultValue());
        }
        if (!$this->model->getBusinessPartnerOfficeCountryId()) {
            $this->model->setBusinessPartnerOfficeCountryId(
                    $this->service->getBusinessPartnerOfficeCountryDefaultValue()
            );
        }
        if (!$this->model->getBusinessPartnerOfficeStateId()) {
            $this->model->setBusinessPartnerOfficeStateId($this->service->getBusinessPartnerOfficeStateDefaultValue());
        }
        if (!$this->model->getBusinessPartnerOfficeCityId()) {
            $this->model->setBusinessPartnerOfficeCityId($this->service->getBusinessPartnerOfficeCityDefaultValue());
        }
        if (!$this->model->getBusinessPartnerShippingCountryId()) {
            $this->model->setBusinessPartnerShippingCountryId(
                    $this->service->getBusinessPartnerShippingCountryDefaultValue()
            );
        }
        if (!$this->model->getBusinessPartnerShippingStateId()) {
            $this->model->setBusinessPartnerShippingStateId(
                    $this->service->getBusinessPartnerShippingStateDefaultValue()
            );
        }
        if (!$this->model->getBusinessPartnerShippingCityId()) {
            $this->model->setBusinessPartnerShippingCityId(
                    $this->service->getBusinessPartnerShippingCityDefaultValue()
            );
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`,
                    `businessPartnerPicture`
           FROM 	`businesspartner`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getBusinessPartnerId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "],
                    [businessPartnerPicture]
           FROM 	[businessPartner]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getBusinessPartnerId(
                            0, 'single'
                    ) . "' ";
        } else {
            if ($this->getVendor() == self::ORACLE) {
                $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . ",
                    BUSINESSPARTNERPICTURE AS \"businessPartnerPicture\"
           FROM 	BUSINESSPARTNER
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getBusinessPartnerId(
                                0, 'single'
                        ) . "' ";
            }
        }
        $result = $this->q->fast($sql);
        $total = $this->q->numberRows($result, $sql);
        if ($total == 0) {
            echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel']));
            exit();
        } else {
            $row = $this->q->fetchArray($result);
            $oldBusinessPartnerPicture = $row['businessPartnerPicture'];
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
               UPDATE `businesspartner` SET
                       `businessPartnerCategoryId` = '" . $this->model->getBusinessPartnerCategoryId() . "',
                       `businessPartnerOfficeCountryId` = '" . $this->model->getBusinessPartnerOfficeCountryId() . "',
                       `businessPartnerOfficeStateId` = '" . $this->model->getBusinessPartnerOfficeStateId() . "',
                       `businessPartnerOfficeCityId` = '" . $this->model->getBusinessPartnerOfficeCityId() . "',
                       `businessPartnerShippingCountryId` = '" . $this->model->getBusinessPartnerShippingCountryId() . "',
                       `businessPartnerShippingStateId` = '" . $this->model->getBusinessPartnerShippingStateId() . "',
                       `businessPartnerShippingCityId` = '" . $this->model->getBusinessPartnerShippingCityId() . "',
                       `businessPartnerCode` = '" . $this->model->getBusinessPartnerCode() . "',
                       `businessPartnerRegistrationNumber` = '" . $this->model->getBusinessPartnerRegistrationNumber() . "',
                       `businessPartnerTaxNumber` = '" . $this->model->getBusinessPartnerTaxNumber() . "',
                       `businessPartnerCompany` = '" . $this->model->getBusinessPartnerCompany() . "',
                       `businessPartnerBusinessPhone` = '" . $this->model->getBusinessPartnerBusinessPhone() . "',
                       `businessPartnerMobilePhone` = '" . $this->model->getBusinessPartnerMobilePhone() . "',
                       `businessPartnerFaxNumber` = '" . $this->model->getBusinessPartnerFaxNumber() . "',
                       `businessPartnerOfficeAddress` = '" . $this->model->getBusinessPartnerOfficeAddress() . "',
                       `businessPartnerShippingAddress` = '" . $this->model->getBusinessPartnerShippingAddress() . "',
                       `businessPartnerOfficePostCode` = '" . $this->model->getBusinessPartnerOfficePostCode() . "',
                       `businessPartnerShippingPostCode` = '" . $this->model->getBusinessPartnerShippingPostCode() . "',
                       `businessPartnerEmail` = '" . $this->model->getBusinessPartnerEmail() . "',
                       `businessPartnerWebPage` = '" . $this->model->getBusinessPartnerWebPage() . "',
                       `businessPartnerFacebook` = '" . $this->model->getBusinessPartnerFacebook() . "',
                       `businessPartnerTwitter` = '" . $this->model->getBusinessPartnerTwitter() . "',
                       `businessPartnerNotes` = '" . $this->model->getBusinessPartnerNotes() . "',
                       `businessPartnerDate` = '" . $this->model->getBusinessPartnerDate() . "',
                       `businessPartnerChequePrinting` = '" . $this->model->getBusinessPartnerChequePrinting() . "',
                       `businessPartnerCreditTerm` = '" . $this->model->getBusinessPartnerCreditTerm() . "',
                       `businessPartnerCreditLimit` = '" . $this->model->getBusinessPartnerCreditLimit() . "',
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
               WHERE    `businessPartnerId`='" . $this->model->getBusinessPartnerId('0', 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE [businessPartner] SET
                       [businessPartnerCategoryId] = '" . $this->model->getBusinessPartnerCategoryId() . "',
                       [businessPartnerOfficeCountryId] = '" . $this->model->getBusinessPartnerOfficeCountryId() . "',
                       [businessPartnerOfficeStateId] = '" . $this->model->getBusinessPartnerOfficeStateId() . "',
                       [businessPartnerOfficeCityId] = '" . $this->model->getBusinessPartnerOfficeCityId() . "',
                       [businessPartnerShippingCountryId] = '" . $this->model->getBusinessPartnerShippingCountryId() . "',
                       [businessPartnerShippingStateId] = '" . $this->model->getBusinessPartnerShippingStateId() . "',
                       [businessPartnerShippingCityId] = '" . $this->model->getBusinessPartnerShippingCityId() . "',
                       [businessPartnerCode] = '" . $this->model->getBusinessPartnerCode() . "',
                       [businessPartnerRegistrationNumber] = '" . $this->model->getBusinessPartnerRegistrationNumber() . "',
                       [businessPartnerTaxNumber] = '" . $this->model->getBusinessPartnerTaxNumber() . "',
                       [businessPartnerCompany] = '" . $this->model->getBusinessPartnerCompany() . "',
                       [businessPartnerBusinessPhone] = '" . $this->model->getBusinessPartnerBusinessPhone() . "',
                       [businessPartnerMobilePhone] = '" . $this->model->getBusinessPartnerMobilePhone() . "',
                       [businessPartnerFaxNumber] = '" . $this->model->getBusinessPartnerFaxNumber() . "',
                       [businessPartnerOfficeAddress] = '" . $this->model->getBusinessPartnerOfficeAddress() . "',
                       [businessPartnerShippingAddress] = '" . $this->model->getBusinessPartnerShippingAddress() . "',
                       [businessPartnerOfficePostCode] = '" . $this->model->getBusinessPartnerOfficePostCode() . "',
                       [businessPartnerShippingPostCode] = '" . $this->model->getBusinessPartnerShippingPostCode() . "',
                       [businessPartnerEmail] = '" . $this->model->getBusinessPartnerEmail() . "',
                       [businessPartnerWebPage] = '" . $this->model->getBusinessPartnerWebPage() . "',
                       [businessPartnerFacebook] = '" . $this->model->getBusinessPartnerFacebook() . "',
                       [businessPartnerTwitter] = '" . $this->model->getBusinessPartnerTwitter() . "',
                       [businessPartnerNotes] = '" . $this->model->getBusinessPartnerNotes() . "',
                       [businessPartnerDate] = '" . $this->model->getBusinessPartnerDate() . "',
                       [businessPartnerChequePrinting] = '" . $this->model->getBusinessPartnerChequePrinting() . "',
                       [businessPartnerCreditTerm] = '" . $this->model->getBusinessPartnerCreditTerm() . "',
                       [businessPartnerCreditLimit] = '" . $this->model->getBusinessPartnerCreditLimit() . "',
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
                WHERE   [businessPartnerId]='" . $this->model->getBusinessPartnerId('0', 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE BUSINESSPARTNER SET
                        BUSINESSPARTNERCATEGORYID = '" . $this->model->getBusinessPartnerCategoryId() . "',
                       BUSINESSPARTNEROFFICECOUNTRYID = '" . $this->model->getBusinessPartnerOfficeCountryId() . "',
                       BUSINESSPARTNEROFFICESTATEID = '" . $this->model->getBusinessPartnerOfficeStateId() . "',
                       BUSINESSPARTNEROFFICECITYID = '" . $this->model->getBusinessPartnerOfficeCityId() . "',
                       BUSINESSPARTNERSHIPPINGCOUNTRYID = '" . $this->model->getBusinessPartnerShippingCountryId() . "',
                       BUSINESSPARTNERSHIPPINGSTATEID = '" . $this->model->getBusinessPartnerShippingStateId() . "',
                       BUSINESSPARTNERSHIPPINGCITYID = '" . $this->model->getBusinessPartnerShippingCityId() . "',
                       BUSINESSPARTNERCODE = '" . $this->model->getBusinessPartnerCode() . "',
                       BUSINESSPARTNERREGISTRATIONNUMBER = '" . $this->model->getBusinessPartnerRegistrationNumber() . "',
                       BUSINESSPARTNERTAXNUMBER = '" . $this->model->getBusinessPartnerTaxNumber() . "',
                       BUSINESSPARTNERCOMPANY = '" . $this->model->getBusinessPartnerCompany() . "',
                       BUSINESSPARTNERBUSINESSPHONE = '" . $this->model->getBusinessPartnerBusinessPhone() . "',
                       BUSINESSPARTNERMOBILEPHONE = '" . $this->model->getBusinessPartnerMobilePhone() . "',
                       BUSINESSPARTNERFAXNUM = '" . $this->model->getBusinessPartnerFaxNumber() . "',
                       BUSINESSPARTNEROFFICEADDRESS = '" . $this->model->getBusinessPartnerOfficeAddress() . "',
                       BUSINESSPARTNERSHIPPINGADDRESS = '" . $this->model->getBusinessPartnerShippingAddress() . "',
                       BUSINESSPARTNEROFFICEPOSTCODE = '" . $this->model->getBusinessPartnerOfficePostCode() . "',
                       BUSINESSPARTNERSHIPPINGPOSTCODE = '" . $this->model->getBusinessPartnerShippingPostCode() . "',
                       BUSINESSPARTNEREMAIL = '" . $this->model->getBusinessPartnerEmail() . "',
                       BUSINESSPARTNERWEBPAGE = '" . $this->model->getBusinessPartnerWebPage() . "',
                       BUSINESSPARTNERFACEBOOK = '" . $this->model->getBusinessPartnerFacebook() . "',
                       BUSINESSPARTNERTWITTER = '" . $this->model->getBusinessPartnerTwitter() . "',
                       BUSINESSPARTNERNOTES = '" . $this->model->getBusinessPartnerNotes() . "',
                       BUSINESSPARTNERDATE = '" . $this->model->getBusinessPartnerDate() . "',
                       BUSINESSPARTNERCHEQUEPRINTING = '" . $this->model->getBusinessPartnerChequePrinting() . "',
                       BUSINESSPARTNERCREDITTERM = '" . $this->model->getBusinessPartnerCreditTerm() . "',
                       BUSINESSPARTNERCREDITLIMIT = '" . $this->model->getBusinessPartnerCreditLimit() . "',
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
                WHERE  BUSINESSPARTNERID='" . $this->model->getBusinessPartnerId('0', 'single') . "'";
            }
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        // additional update image
        $this->service->setTransferBusinessPartnerPicture(
                $this->getLeafId(), $this->model->getBusinessPartnerId('0', 'single'), $oldBusinessPartnerPicture
        );

        // end additional update image
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
           FROM 	`businesspartner`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getBusinessPartnerId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[businessPartner]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getBusinessPartnerId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	BUSINESSPARTNER
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getBusinessPartnerId(
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
               UPDATE  `businesspartner`
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
               WHERE   `businessPartnerId`   =  '" . $this->model->getBusinessPartnerId(0, 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
               UPDATE  [businessPartner]
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
               WHERE   [businessPartnerId]	=  '" . $this->model->getBusinessPartnerId(0, 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
               UPDATE  BUSINESSPARTNER
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
               WHERE   BUSINESSPARTNERID	=  '" . $this->model->getBusinessPartnerId(0, 'single') . "'";
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
           SELECT  `businessPartnerCode`
           FROM    `businesspartner`
           WHERE   `businessPartnerCode` 	= 	'" . $this->model->getBusinessPartnerCode() . "'
           AND     `isActive`  =   1
           AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT  [businessPartnerCode]
           FROM    [businessPartner]
           WHERE   [businessPartnerCode] = 	'" . $this->model->getBusinessPartnerCode() . "'
           AND     [isActive]  =   1
           AND     [companyId] =	'" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
               SELECT  BUSINESSPARTNERCODE as \"businessPartnerCode\"
               FROM    BUSINESSPARTNER
               WHERE   BUSINESSPARTNERCODE	= 	'" . $this->model->getBusinessPartnerCode() . "'
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
                        "businessPsrtnerCode" => $row ['businessPartnerCode'],
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
     * Return Business Partner
     * @return null|string
     */
    public function getBusinessPartner() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getBusinessPartner();
    }

    /**
     * Return Business Partner Contact
     * @return null|string
     */
    public function getBusinessPartnerContact() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getBusinessPartnerContact();
    }

    /**
     * Set New Fast Business Partner.Company Address And shipping address will be same as defaulted.
     * @return null|string
     */
    public function setNewBusinessPartner() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->setNewBusinessPartner(
                        $this->model->getBusinessPartnerCompany(), $this->model->getBusinessPartnerOfficeAddress()
        );
    }

    /**
     * Set New Fast Business Partner Contact Name
     * @return void
     */
    public function setNewBusinessPartnerContact() {
        $this->service->setServiceOutput($this->getServiceOutput());
        $this->service->setNewBusinessPartnerContact(
                $this->model->getBusinessPartnerId(0, 'string'), $this->model->getBusinessPartnerContactName(), $this->model->getBusinessPartnerContactPhone(), $this->model->getBusinessPartnerContactEmail()
        );
    }

    /**
     * Return FollowUp
     * @return null|string
     */
    public function getFollowUp() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getFollowUp();
    }

    /**
     * Return Business Partner Category
     * @return null|string
     */
    public function getBusinessPartnerCategory() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getBusinessPartnerCategory();
    }

    /**
     * Return Invoice
     * @return null|string
     */
    public function getInvoice() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getInvoice();
    }

    /**
     * Return Purchase Invoice
     * @return null|string
     */
    public function getPurchaseInvoice() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getPurchaseInvoice();
    }

    /**
     * Return Business Partner Office Country
     * @return null|string
     */
    public function getBusinessPartnerOfficeCountry() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getBusinessPartnerOfficeCountry();
    }

    /**
     * Return Business Partner Office State
     * @return null|string
     */
    public function getBusinessPartnerOfficeState() {
        if ($this->model->getBusinessPartnerOfficeCountryId()) {
            $this->service->setServiceOutput('option');
            return $this->service->getBusinessPartnerOfficeState($this->model->getBusinessPartnerOfficeCountryId());
        } else {
            $this->service->setServiceOutput($this->getServiceOutput());
            return $this->service->getBusinessPartnerOfficeState();
        }
    }

    /**
     * Return Business Partner Office City
     * @return null|string
     */
    public function getBusinessPartnerOfficeCity() {
        if ($this->model->getBusinessPartnerOfficeCountryId() && $this->model->getBusinessPartnerOfficeStateId()) {
            $this->service->setServiceOutput('option');
            return $this->service->getBusinessPartnerOfficeCity(
                            $this->model->getBusinessPartnerOfficeCountryId(), $this->model->getBusinessPartnerOfficeStateId()
            );
        } else if ($this->model->getBusinessPartnerOfficeCountryId() && !$this->model->getBusinessPartnerOfficeStateId()
        ) {
            $this->service->setServiceOutput('option');
            return $this->service->getBusinessPartnerOfficeCity($this->model->getBusinessPartnerOfficeCountryId());
        } else {
            $this->service->setServiceOutput($this->getServiceOutput());
            return $this->service->getBusinessPartnerOfficeCity();
        }
    }

    /**
     * Return Business Partner Shipping Country
     * @return null|string
     */
    public function getBusinessPartnerShippingCountry() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getBusinessPartnerShippingCountry();
    }

    /**
     * Return Business Partner Shipping State
     * @return null|string
     */
    public function getBusinessPartnerShippingState() {
        if ($this->model->getBusinessPartnerShippingCountryId()) {
            $this->service->setServiceOutput('option');
            return $this->service->getBusinessPartnerShippingState($this->model->getBusinessPartnerShippingCountryId());
        } else {
            $this->service->setServiceOutput($this->getServiceOutput());
            return $this->service->getBusinessPartnerShippingState();
        }
    }

    /**
     * Return Business Partner Shipping City
     * @return null|string
     */
    public function getBusinessPartnerShippingCity() {
        if ($this->model->getBusinessPartnerShippingCountryId() && $this->model->getBusinessPartnerShippingCountryId()
        ) {
            $this->service->setServiceOutput('option');
            return $this->service->getBusinessPartnerShippingCity(
                            $this->model->getBusinessPartnerShippingCountryId(), $this->model->getBusinessPartnerShippingCountryId()
            );
        } else if ($this->model->getBusinessPartnerShippingCountryId(
                ) && !$this->model->getBusinessPartnerShippingCountryId()
        ) {
            $this->service->setServiceOutput('option');
            return $this->service->getBusinessPartnerShippingCity($this->model->getBusinessPartnerShippingCountryId());
        } else {
            $this->service->setServiceOutput($this->getServiceOutput());
            return $this->service->getBusinessPartnerShippingCity();
        }
    }

    /**
     * Set Business Partner Picture
     * @return void
     */
    public function setBusinessPartnerPicture() {
        $this->service->setBusinessPartnerPicture($this->getLeafId());
    }

    /**
     * Analysis Business Partner Cashbook Transaction Daily
     */
    public function getBusinessPartnerCashBookDaily() {
        $this->serviceCashBook->getBusinessPartnerCashBookDaily($this->model->getAnalysisDate());
    }

    /**
     * Analysis Business Partner Cashbook Transaction Weekly
     */
    public function getBusinessPartnerCashBookWeekly() {
        $this->serviceCashBook->getBusinessPartnerCashBookWeekly($this->model->getAnalysisDate());
    }

    /**
     * Analysis Business Partner Cashbook Transaction Monthly
     */
    public function getBusinessPartnerCashBookMonthly() {
        $this->serviceCashBook->getBusinessPartnerCashBookMonthly($this->model->getAnalysisDate());
    }

    /**
     * Analysis Business Partner Cashbook Transaction Yearly
     */
    public function getBusinessPartnerCashBookYearly() {
        $this->serviceCashBook->getBusinessPartnerCashBookYearly($this->model->getAnalysisDate());
    }

    /**
     * Analysis Business Partner Receivable Transaction Daily
     */
    public function getBusinessPartnerReceivableDaily() {
        $this->serviceAccountReceivable->getBusinessPartnerReceivableDaily($this->model->getAnalysisDate());
    }

    /**
     * Analysis Business Partner Receivable Transaction Weekly
     */
    public function getBusinessPartnerReceivableWeekly() {
        $this->serviceAccountReceivable->getBusinessPartnerReceivableWeekly($this->model->getAnalysisDate());
    }

    /**
     * Analysis Business Partner Receivable Transaction Monthly
     */
    public function getBusinessPartnerReceivableMonthly() {
        $this->serviceAccountReceivable->getBusinessPartnerReceivableMonthly($this->model->getAnalysisDate());
    }

    /**
     * Analysis Business Partner Receivable Transaction Yearly
     */
    public function getBusinessPartnerReceivableYearly() {
        $this->serviceAccountReceivable->getBusinessPartnerReceivableYearly($this->model->getAnalysisDate());
    }

    /**
     * Analysis Business Partner Payable Transaction Daily
     */
    public function getBusinessPartnerPayableDaily() {
        $this->serviceAccountPayable->getBusinessPartnerPayableDaily($this->model->getAnalysisDate());
    }

    /**
     * Analysis Business Partner Payable Transaction Weekly
     */
    public function getBusinessPartnerPayableWeekly() {
        $this->serviceAccountPayable->getBusinessPartnerPayableWeekly($this->model->getAnalysisDate());
    }

    /**
     * Analysis Business Partner Payable Transaction Monthly
     */
    public function getBusinessPartnerPayableMonthly() {
        $this->serviceAccountPayable->getBusinessPartnerPayableMonthly($this->model->getAnalysisDate());
    }

    /**
     * Analysis Business Partner Payable Transaction Yearly
     */
    public function getBusinessPartnerPayableYearly() {
        $this->serviceAccountPayable->getBusinessPartnerPayableYearly($this->model->getAnalysisDate());
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
                ->setSubject('businessPartner')
                ->setDescription('Generated by PhpExcel an Idcms Generator')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('financial/businessPartner');
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
        $this->excel->getActiveSheet()->setCellValue('AF2', '');
        $this->excel->getActiveSheet()->mergeCells('B2:AF2');
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.');
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['businessPartnerCategoryIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['businessPartnerOfficeCountryIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['businessPartnerOfficeStateIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['businessPartnerOfficeCityIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['businessPartnerShippingCountryIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['businessPartnerShippingStateIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['businessPartnerShippingCityIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['businessPartnerCodeLabel']);
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['businessPartnerRegistrationNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('L3', $this->translate['businessPartnerTaxNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('M3', $this->translate['businessPartnerCompanyLabel']);
        $this->excel->getActiveSheet()->setCellValue('N3', $this->translate['businessPartnerImageLabel']);
        $this->excel->getActiveSheet()->setCellValue('O3', $this->translate['businessPartnerBusinessPhoneLabel']);
        $this->excel->getActiveSheet()->setCellValue('P3', $this->translate['businessPartnerMobilePhoneLabel']);
        $this->excel->getActiveSheet()->setCellValue('Q3', $this->translate['businessPartnerFaxNumLabel']);
        $this->excel->getActiveSheet()->setCellValue('R3', $this->translate['businessPartnerOfficeAddressLabel']);
        $this->excel->getActiveSheet()->setCellValue('S3', $this->translate['businessPartnerShippingAddressLabel']);
        $this->excel->getActiveSheet()->setCellValue('T3', $this->translate['businessPartnerOfficePostCodeLabel']);
        $this->excel->getActiveSheet()->setCellValue('U3', $this->translate['businessPartnerShippingPostCodeLabel']);
        $this->excel->getActiveSheet()->setCellValue('V3', $this->translate['businessPartnerEmailLabel']);
        $this->excel->getActiveSheet()->setCellValue('W3', $this->translate['businessPartnerWebPageLabel']);
        $this->excel->getActiveSheet()->setCellValue('X3', $this->translate['businessPartnerFacebookLabel']);
        $this->excel->getActiveSheet()->setCellValue('Y3', $this->translate['businessPartnerTwitterLabel']);
        $this->excel->getActiveSheet()->setCellValue('Z3', $this->translate['businessPartnerNotesLabel']);
        $this->excel->getActiveSheet()->setCellValue('AA3', $this->translate['businessPartnerDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('AB3', $this->translate['businessPartnerChequePrintingLabel']);
        $this->excel->getActiveSheet()->setCellValue('AC3', $this->translate['businessPartnerCreditTermLabel']);
        $this->excel->getActiveSheet()->setCellValue('AD3', $this->translate['businessPartnerCreditLimitLabel']);
        $this->excel->getActiveSheet()->setCellValue('AE3', $this->translate['executeByLabel']);
        $this->excel->getActiveSheet()->setCellValue('AF3', $this->translate['executeTimeLabel']);
        // 
        $loopRow = 4;
        $i = 0;
        \PHPExcel_Cell::setValueBinder(new \PHPExcel_Cell_AdvancedValueBinder());
        $lastRow = null;
        while (($row = $this->q->fetchAssoc()) == true) {
            //	echo print_r($row); 
            $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i);
            $this->excel->getActiveSheet()->setCellValue(
                    'C' . $loopRow, strip_tags($row ['businessPartnerCategoryDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue(
                    'D' . $loopRow, strip_tags($row ['businessPartnerOfficeCountryDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue(
                    'E' . $loopRow, strip_tags($row ['businessPartnerOfficeStateDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue(
                    'F' . $loopRow, strip_tags($row ['businessPartnerOfficeCityDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue(
                    'G' . $loopRow, strip_tags($row ['businessPartnerShippingCountryDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue(
                    'H' . $loopRow, strip_tags($row ['businessPartnerShippingStateDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue(
                    'I' . $loopRow, strip_tags($row ['businessPartnerShippingCityDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue('J' . $loopRow, strip_tags($row ['businessPartnerCode']));
            $this->excel->getActiveSheet()->setCellValue(
                    'K' . $loopRow, strip_tags($row ['businessPartnerRegistrationNumber'])
            );
            $this->excel->getActiveSheet()->setCellValue('L' . $loopRow, strip_tags($row ['businessPartnerTaxNumber']));
            $this->excel->getActiveSheet()->setCellValue('M' . $loopRow, strip_tags($row ['businessPartnerCompany']));
            $this->excel->getActiveSheet()->setCellValue('N' . $loopRow, strip_tags($row ['businessPartnerPicture']));
            $this->excel->getActiveSheet()->setCellValue(
                    'O' . $loopRow, strip_tags($row ['businessPartnerBusinessPhone'])
            );
            $this->excel->getActiveSheet()->setCellValue(
                    'P' . $loopRow, strip_tags($row ['businessPartnerMobilePhone'])
            );
            $this->excel->getActiveSheet()->setCellValue('Q' . $loopRow, strip_tags($row ['businessPartnerFaxNumber']));
            $this->excel->getActiveSheet()->setCellValue(
                    'R' . $loopRow, strip_tags($row ['businessPartnerOfficeAddress'])
            );
            $this->excel->getActiveSheet()->setCellValue(
                    'S' . $loopRow, strip_tags($row ['businessPartnerShippingAddress'])
            );
            $this->excel->getActiveSheet()->setCellValue(
                    'T' . $loopRow, strip_tags($row ['businessPartnerOfficePostCode'])
            );
            $this->excel->getActiveSheet()->setCellValue(
                    'U' . $loopRow, strip_tags($row ['businessPartnerShippingPostCode'])
            );
            $this->excel->getActiveSheet()->setCellValue('V' . $loopRow, strip_tags($row ['businessPartnerEmail']));
            $this->excel->getActiveSheet()->setCellValue('W' . $loopRow, strip_tags($row ['businessPartnerWebPage']));
            $this->excel->getActiveSheet()->setCellValue('X' . $loopRow, strip_tags($row ['businessPartnerFacebook']));
            $this->excel->getActiveSheet()->setCellValue('Y' . $loopRow, strip_tags($row ['businessPartnerTwitter']));
            $this->excel->getActiveSheet()->setCellValue('Z' . $loopRow, strip_tags($row ['businessPartnerNotes']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $this->excel->getActiveSheet()->setCellValue('AA' . $loopRow, strip_tags($row ['businessPartnerDate']));
            $this->excel->getActiveSheet()->setCellValue(
                    'AB' . $loopRow, strip_tags($row ['businessPartnerChequePrinting'])
            );
            $this->excel->getActiveSheet()->setCellValue('AC' . $loopRow, strip_tags($row ['businessPartnerCreditTerm']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('AC' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue(
                    'AD' . $loopRow, strip_tags($row ['businessPartnerCreditLimit'])
            );
            $this->excel->getActiveSheet()->setCellValue('AE' . $loopRow, strip_tags($row ['staffName']));
            $this->excel->getActiveSheet()->setCellValue('AF' . $loopRow, strip_tags($row ['executeTime']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $loopRow++;
            $lastRow = 'AF' . $loopRow;
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
                $filename = "businessPartner" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/businessPartner/document/" . $folder . "/" . $filename;
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
                $filename = "businessPartner" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/businessPartner/document/" . $folder . "/" . $filename;
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
                $filename = "businessPartner" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/businessPartner/document/" . $folder . "/" . $filename;
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
                $filename = "businessPartner" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/businessPartner/document/" . $folder . "/" . $filename;
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
        $businessPartnerObject = new BusinessPartnerClass ();
        if ($_POST['securityToken'] != $businessPartnerObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */

        if (isset($_POST ['leafId'])) {

            $businessPartnerObject->setLeafId($_POST ['leafId']);
        }
        if (isset($_POST ['offset'])) {
            $businessPartnerObject->setStart($_POST ['offset']);
        }
        if (isset($_POST ['limit'])) {
            $businessPartnerObject->setLimit($_POST ['limit']);
        }
        $businessPartnerObject->setPageOutput($_POST['output']);
        $businessPartnerObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $businessPartnerObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $businessPartnerObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $businessPartnerObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $businessPartnerObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$businessPartnerObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$businessPartnerObject->delete(); 
        }
        if ($_POST ['method'] == 'upload') {
            $businessPartnerObject->setBusinessPartnerPicture();
        }
        /**
         * Additional Fast Request
         */
        if ($_POST['method'] == 'fastBusinessPartner') {
            $businessPartnerObject->setNewBusinessPartner();
        }
        if ($_POST['method'] == 'fastBusinessPartnerContact') {
            $businessPartnerObject->setNewBusinessPartnerContact();
        }
    }
}
if (isset($_GET ['method'])) {
    $businessPartnerObject = new BusinessPartnerClass ();
    if ($_GET['securityToken'] != $businessPartnerObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $businessPartnerObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $businessPartnerObject->execute();
    /*
     * Update Status of The Table. Admin Level Only 
     */
    if ($_GET ['method'] == 'updateStatus') {
        $businessPartnerObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key 
     */
    if ($_GET['method'] == 'duplicate') {
        $businessPartnerObject->duplicate();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $businessPartnerObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $businessPartnerObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $businessPartnerObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $businessPartnerObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $businessPartnerObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $businessPartnerObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        $businessPartnerObject->setServiceOutput('option');
        if (($_GET['filter'] == 'businessPartnerCategory')) {
            $businessPartnerObject->getBusinessPartnerCategory();
        }
        if (($_GET['filter'] == 'businessPartnerOfficeCountry')) {
            $businessPartnerObject->getBusinessPartnerOfficeCountry();
        }
        if (($_GET['filter'] == 'businessPartnerOfficeState')) {
            $businessPartnerObject->getBusinessPartnerOfficeState();
        }
        if (($_GET['filter'] == 'businessPartnerOfficeCity')) {
            $businessPartnerObject->getBusinessPartnerOfficeCity();
        }
        if (($_GET['filter'] == 'businessPartnerShippingCountry')) {
            $businessPartnerObject->getBusinessPartnerShippingCountry();
        }
        if (($_GET['filter'] == 'businessPartnerShippingState')) {
            $businessPartnerObject->getBusinessPartnerShippingState();
        }
        if (($_GET['filter'] == 'businessPartnerShippingCity')) {
            $businessPartnerObject->getBusinessPartnerShippingCity();
        }
        if ($_GET ['method'] == 'upload') {
            $businessPartnerObject->setBusinessPartnerPicture();
        }
        if (($_GET['filter'] == 'businessPartner')) {
            $businessPartnerObject->getBusinessPartner();
        }
        if (($_GET['filter'] == 'invoice')) {
            $businessPartnerObject->getInvoice();
        }
        if (($_GET['filter'] == 'purchaseInvoice')) {
            $businessPartnerObject->getPurchaseInvoice();
        }
    }
}
?>
