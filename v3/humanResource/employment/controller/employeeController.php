<?php

namespace Core\HumanResource\Employment\Employee\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\HumanResource\Employment\Employee\Model\EmployeeModel;
use Core\HumanResource\Employment\Employee\Service\EmployeeService;
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
require_once($newFakeDocumentRoot . "v3/humanResource/employment/model/employeeModel.php");
require_once($newFakeDocumentRoot . "v3/humanResource/employment/service/employeeService.php");

/**
 * Class Employee
 * this is employee controller files.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package  Core\HumanResource\Employment\Employee\Controller
 * @subpackage Employment
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class EmployeeClass extends ConfigClass {

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
     * @var \Core\HumanResource\Employment\Employee\Model\EmployeeModel
     */
    public $model;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     * @var \PHPPowerPoint
     */
    //private $powerPoint; 
    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\HumanResource\Employment\Employee\Service\EmployeeService
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
        if ($_SESSION['branchId']) {
            $this->setBranchId($_SESSION['branchId']);
        } else {
            // fall back to default database if anything wrong
            $this->setBranchId(1);
        }
        $this->translate = array();
        $this->t = array();
        $this->leafAccess = array();
        $this->systemFormat = array();
        $this->setViewPath("./v3/humanResource/employment/view/employee.php");
        $this->setControllerPath("./v3/humanResource/employment/controller/employeeController.php");
        $this->setServicePath("./v3/humanResource/employment/service/employeeService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new EmployeeModel();
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

        $this->setReportTitle(
                $applicationNative . " :: " . $moduleNative . " :: " . $folderNative . " :: " . $leafNative
        );

        $this->service = new EmployeeService();
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
        if (!$this->model->getCityId()) {
            $this->model->setCityId($this->service->getCityDefaultValue());
        }
        if (!$this->model->getStateId()) {
            $this->model->setStateId($this->service->getStateDefaultValue());
        }
        if (!$this->model->getCountryId()) {
            $this->model->setCountryId($this->service->getCountryDefaultValue());
        }
        if (!$this->model->getJobId()) {
            $this->model->setJobId($this->service->getJobDefaultValue());
        }
        if (!$this->model->getGenderId()) {
            $this->model->setGenderId($this->service->getGenderDefaultValue());
        }
        if (!$this->model->getMarriageId()) {
            $this->model->setMarriageId($this->service->getMarriageDefaultValue());
        }
        if (!$this->model->getRaceId()) {
            $this->model->setRaceId($this->service->getRaceDefaultValue());
        }
        if (!$this->model->getReligionId()) {
            $this->model->setReligionId($this->service->getReligionDefaultValue());
        }
        if (!$this->model->getEmploymentStatusId()) {
            $this->model->setEmploymentStatusId($this->service->getEmploymentStatusDefaultValue());
        }
        //$this->model->setDocumentNumber($this->getDocumentNumber);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `employee` 
            (
                 `companyId`,
                 `cityId`,
                 `stateId`,
                 `countryId`,
                 `jobId`,
                 `genderId`,
                 `marriageId`,
                 `raceId`,
                 `religionId`,
                 `employmentStatusId`,
                 `nationalNumber`,
                 `licenseNumber`,
                 `employeeNumber`,
                 `employeeFirstName`,
                 `employeePicture`,
                 `employeeLastName`,
                 `employeeDateOfBirth`,
                 `employeeDateHired`,
                 `employeeDateRetired`,
                 `employeeBusinessPhone`,
                 `employeeHomePhone`,
                 `employeeMobilePhone`,
                 `employeeFaxNumber`,
                 `employeeAddress`,
                 `employeePostCode`,
                 `employeeEmail`,
                 `employeeFacebook`,
                 `employeeTwitter`,
                 `employeeLinkedIn`,
                 `employeeNotes`,
                 `employeeChequePrinting`,
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
                 '" . $this->model->getCityId() . "',
                 '" . $this->model->getStateId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getJobId() . "',
                 '" . $this->model->getGenderId() . "',
                 '" . $this->model->getMarriageId() . "',
                 '" . $this->model->getRaceId() . "',
                 '" . $this->model->getReligionId() . "',
                 '" . $this->model->getEmploymentStatusId() . "',
                 '" . $this->model->getNationalNumber() . "',
                 '" . $this->model->getLicenseNumber() . "',
                 '" . $this->model->getEmployeeNumber() . "',
                 '" . $this->model->getEmployeeFirstName() . "',
                 '" . $this->model->getEmployeePicture() . "',
                 '" . $this->model->getEmployeeLastName() . "',
                 '" . $this->model->getEmployeeDateOfBirth() . "',
                 '" . $this->model->getEmployeeDateHired() . "',
                 '" . $this->model->getEmployeeDateRetired() . "',
                 '" . $this->model->getEmployeeBusinessPhone() . "',
                 '" . $this->model->getEmployeeHomePhone() . "',
                 '" . $this->model->getEmployeeMobilePhone() . "',
                 '" . $this->model->getEmployeeFaxNumber() . "',
                 '" . $this->model->getEmployeeAddress() . "',
                 '" . $this->model->getEmployeePostCode() . "',
                 '" . $this->model->getEmployeeEmail() . "',
                 '" . $this->model->getEmployeeFacebook() . "',
                 '" . $this->model->getEmployeeTwitter() . "',
                 '" . $this->model->getEmployeeLinkedIn() . "',
                 '" . $this->model->getEmployeeNotes() . "',
                 '" . $this->model->getEmployeeChequePrinting() . "',
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
            INSERT INTO [employee]
            (
                 [employeeId],
                 [companyId],
                 [cityId],
                 [stateId],
                 [countryId],
                 [jobId],
                 [genderId],
                 [marriageId],
                 [raceId],
                 [religionId],
                 [employmentStatusId],
                 [nationalNumber],
                 [licenseNumber],
                 [employeeNumber],
                 [employeeFirstName],
                 [employeePicture],
                 [employeeLastName],
                 [employeeDateOfBirth],
                 [employeeDateHired],
                 [employeeDateRetired],
                 [employeeBusinessPhone],
                 [employeeHomePhone],
                 [employeeMobilePhone],
                 [employeeFaxNumber],
                 [employeeAddress],
                 [employeePostCode],
                 [employeeEmail],
                 [employeeFacebook],
                 [employeeTwitter],
                 [employeeLinkedIn],
                 [employeeNotes],
                 [employeeChequePrinting],
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
                 '" . $this->model->getCityId() . "',
                 '" . $this->model->getStateId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getJobId() . "',
                 '" . $this->model->getGenderId() . "',
                 '" . $this->model->getMarriageId() . "',
                 '" . $this->model->getRaceId() . "',
                 '" . $this->model->getReligionId() . "',
                 '" . $this->model->getEmploymentStatusId() . "',
                 '" . $this->model->getNationalNumber() . "',
                 '" . $this->model->getLicenseNumber() . "',
                 '" . $this->model->getEmployeeNumber() . "',
                 '" . $this->model->getEmployeeFirstName() . "',
                 '" . $this->model->getEmployeePicture() . "',
                 '" . $this->model->getEmployeeLastName() . "',
                 '" . $this->model->getEmployeeDateOfBirth() . "',
                 '" . $this->model->getEmployeeDateHired() . "',
                 '" . $this->model->getEmployeeDateRetired() . "',
                 '" . $this->model->getEmployeeBusinessPhone() . "',
                 '" . $this->model->getEmployeeHomePhone() . "',
                 '" . $this->model->getEmployeeMobilePhone() . "',
                 '" . $this->model->getEmployeeFaxNumber() . "',
                 '" . $this->model->getEmployeeAddress() . "',
                 '" . $this->model->getEmployeePostCode() . "',
                 '" . $this->model->getEmployeeEmail() . "',
                 '" . $this->model->getEmployeeFacebook() . "',
                 '" . $this->model->getEmployeeTwitter() . "',
                 '" . $this->model->getEmployeeLinkedIn() . "',
                 '" . $this->model->getEmployeeNotes() . "',
                 '" . $this->model->getEmployeeChequePrinting() . "',
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
            INSERT INTO EMPLOYEE
            (
                 COMPANYID,
                 CITYID,
                 STATEID,
                 COUNTRYID,
                 JOBID,
                 GENDERID,
                 MARRIAGEID,
                 RACEID,
                 RELIGIONID,
                 EMPLOYMENTSTATUSID,
                 NATIONALNUMBER,
                 LICENSENUMBER,
                 EMPLOYEENUMBER,
                 EMPLOYEEFIRSTNAME,
                 EMPLOYEEPICTURE,
                 EMPLOYEELASTNAME,
                 EMPLOYEEDATEOFBIRTH,
                 EMPLOYEEDATEHIRED,
                 EMPLOYEEDATERETIRED,
                 EMPLOYEEBUSINESSPHONE,
                 EMPLOYEEHOMEPHONE,
                 EMPLOYEEMOBILEPHONE,
                 EMPLOYEEFAXNUMBER,
                 EMPLOYEEADDRESS,
                 EMPLOYEEPOSTCODE,
                 EMPLOYEEEMAIL,
                 EMPLOYEEFACEBOOK,
                 EMPLOYEETWITTER,
                 EMPLOYEELINKEDIN,
                 EMPLOYEENOTES,
                 EMPLOYEECHEQUEPRINTING,
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
                 '" . $this->model->getCityId() . "',
                 '" . $this->model->getStateId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getJobId() . "',
                 '" . $this->model->getGenderId() . "',
                 '" . $this->model->getMarriageId() . "',
                 '" . $this->model->getRaceId() . "',
                 '" . $this->model->getReligionId() . "',
                 '" . $this->model->getEmploymentStatusId() . "',
                 '" . $this->model->getNationalNumber() . "',
                 '" . $this->model->getLicenseNumber() . "',
                 '" . $this->model->getEmployeeNumber() . "',
                 '" . $this->model->getEmployeeFirstName() . "',
                 '" . $this->model->getEmployeePicture() . "',
                 '" . $this->model->getEmployeeLastName() . "',
                 '" . $this->model->getEmployeeDateOfBirth() . "',
                 '" . $this->model->getEmployeeDateHired() . "',
                 '" . $this->model->getEmployeeDateRetired() . "',
                 '" . $this->model->getEmployeeBusinessPhone() . "',
                 '" . $this->model->getEmployeeHomePhone() . "',
                 '" . $this->model->getEmployeeMobilePhone() . "',
                 '" . $this->model->getEmployeeFaxNumber() . "',
                 '" . $this->model->getEmployeeAddress() . "',
                 '" . $this->model->getEmployeePostCode() . "',
                 '" . $this->model->getEmployeeEmail() . "',
                 '" . $this->model->getEmployeeFacebook() . "',
                 '" . $this->model->getEmployeeTwitter() . "',
                 '" . $this->model->getEmployeeLinkedIn() . "',
                 '" . $this->model->getEmployeeNotes() . "',
                 '" . $this->model->getEmployeeChequePrinting() . "',
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
        $employeeId = $this->q->lastInsertId();
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
                    "employeeId" => $employeeId,
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
         FROM    `employee`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT    COUNT(*) AS total
         FROM      [employee]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT    COUNT(*)    AS  \"total\"
         FROM      EMPLOYEE
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
                    if ($_SESSION['isBranch'] == 0) {
                        $this->setAuditFilter(
                                " `employee`.`isActive` = 1  AND `employee`.`companyId`='" . $this->getCompanyId() . "' "
                        );
                    } else if ($_SESSION['isBranch'] == 1) {
                        $this->setAuditFilter(
                                " `employee`.`isActive` = 1  AND `employee`.`companyId`='" . $this->getCompanyId(
                                ) . "' AND `employee`.`isBranch` = '" . $this->getBranchId() . "' "
                        );
                    }
                } else if ($this->getVendor() == self::MSSQL) {
                    if ($_SESSION['isBranch'] == 0) {
                        $this->setAuditFilter(
                                " [employee].[isActive] = 1 AND [employee].[companyId]='" . $this->getCompanyId() . "' "
                        );
                    } else if ($_SESSION['isBranch'] == 1) {
                        $this->setAuditFilter(
                                " [employee].[isActive] = 1 AND [employee].[companyId]='" . $this->getCompanyId(
                                ) . "' AND [employee].[branchId]	='" . $this->getBranchId() . "' "
                        );
                    }
                } else if ($this->getVendor() == self::ORACLE) {
                    if ($_SESSION['isBranch'] == 0) {
                        $this->setAuditFilter(
                                " EMPLOYEE.ISACTIVE = 1  AND EMPLOYEE.COMPANYID='" . $this->getCompanyId() . "'"
                        );
                    } else if ($_SESSION['isBranch'] == 1) {
                        $this->setAuditFilter(
                                " EMPLOYEE.ISACTIVE = 1  AND EMPLOYEE.COMPANYID='" . $this->getCompanyId(
                                ) . "' AND EMPLOYEE.BRANCHID='" . $this->getBranchId() . "' "
                        );
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter("   `employee`.`companyId`='" . $this->getCompanyId() . "'	");
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(" [employee].[companyId]='" . $this->getCompanyId() . "' ");
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(" EMPLOYEE.COMPANYID='" . $this->getCompanyId() . "' ");
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
       SELECT                    `employee`.`employeeId`,
                    `company`.`companyDescription`,
                    `employee`.`companyId`,
                    `city`.`cityDescription`,
                    `employee`.`cityId`,
                    `state`.`stateDescription`,
                    `employee`.`stateId`,
                    `country`.`countryDescription`,
                    `employee`.`countryId`,
                    `job`.`jobDescription`,
                    `employee`.`jobId`,
                    `gender`.`genderDescription`,
                    `employee`.`genderId`,
                    `marriage`.`marriageDescription`,
                    `employee`.`marriageId`,
                    `race`.`raceDescription`,
                    `employee`.`raceId`,
                    `religion`.`religionDescription`,
                    `employee`.`religionId`,
                    `employmentstatus`.`employmentStatusDescription`,
                    `employee`.`employmentStatusId`,
                    `employee`.`nationalNumber`,
                    `employee`.`licenseNumber`,
                    `employee`.`employeeNumber`,
                    `employee`.`employeeFirstName`,
                    `employee`.`employeePicture`,
                    `employee`.`employeeLastName`,
                    `employee`.`employeeDateOfBirth`,
                    `employee`.`employeeDateHired`,
                    `employee`.`employeeDateRetired`,
                    `employee`.`employeeBusinessPhone`,
                    `employee`.`employeeHomePhone`,
                    `employee`.`employeeMobilePhone`,
                    `employee`.`employeeFaxNumber`,
                    `employee`.`employeeAddress`,
                    `employee`.`employeePostCode`,
                    `employee`.`employeeEmail`,
                    `employee`.`employeeFacebook`,
                    `employee`.`employeeTwitter`,
                    `employee`.`employeeLinkedIn`,
                    `employee`.`employeeNotes`,
                    `employee`.`employeeChequePrinting`,
                    `employee`.`isDefault`,
                    `employee`.`isNew`,
                    `employee`.`isDraft`,
                    `employee`.`isUpdate`,
                    `employee`.`isDelete`,
                    `employee`.`isActive`,
                    `employee`.`isApproved`,
                    `employee`.`isReview`,
                    `employee`.`isPost`,
                    `employee`.`executeBy`,
                    `employee`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `employee`
		  JOIN      `staff`
		  ON        `employee`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `employee`.`companyId`
	JOIN	`city`
	ON		`city`.`cityId` = `employee`.`cityId`
	JOIN	`state`
	ON		`state`.`stateId` = `employee`.`stateId`
	JOIN	`country`
	ON		`country`.`countryId` = `employee`.`countryId`
	JOIN	`job`
	ON		`job`.`jobId` = `employee`.`jobId`
	JOIN	`gender`
	ON		`gender`.`genderId` = `employee`.`genderId`
	JOIN	`marriage`
	ON		`marriage`.`marriageId` = `employee`.`marriageId`
	JOIN	`race`
	ON		`race`.`raceId` = `employee`.`raceId`
	JOIN	`religion`
	ON		`religion`.`religionId` = `employee`.`religionId`
	JOIN	`employmentstatus`
	ON		`employmentstatus`.`employmentStatusId` = `employee`.`employmentStatusId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getEmployeeId(0, 'single')) {
                $sql .= " AND `employee`.`" . $this->model->getPrimaryKeyName() . "`='" . $this->model->getEmployeeId(
                                0, 'single'
                        ) . "'";
            }
            if ($this->model->getCityId()) {
                $sql .= " AND `employee`.`cityId`='" . $this->model->getCityId() . "'";
            }
            if ($this->model->getStateId()) {
                $sql .= " AND `employee`.`stateId`='" . $this->model->getStateId() . "'";
            }
            if ($this->model->getCountryId()) {
                $sql .= " AND `employee`.`countryId`='" . $this->model->getCountryId() . "'";
            }
            if ($this->model->getJobId()) {
                $sql .= " AND `employee`.`jobId`='" . $this->model->getJobId() . "'";
            }
            if ($this->model->getGenderId()) {
                $sql .= " AND `employee`.`genderId`='" . $this->model->getGenderId() . "'";
            }
            if ($this->model->getMarriageId()) {
                $sql .= " AND `employee`.`marriageId`='" . $this->model->getMarriageId() . "'";
            }
            if ($this->model->getRaceId()) {
                $sql .= " AND `employee`.`raceId`='" . $this->model->getRaceId() . "'";
            }
            if ($this->model->getReligionId()) {
                $sql .= " AND `employee`.`religionId`='" . $this->model->getReligionId() . "'";
            }
            if ($this->model->getEmploymentStatusId()) {
                $sql .= " AND `employee`.`employmentStatusId`='" . $this->model->getEmploymentStatusId() . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
		  SELECT                    [employee].[employeeId],
                    [company].[companyDescription],
                    [employee].[companyId],
                    [city].[cityDescription],
                    [employee].[cityId],
                    [state].[stateDescription],
                    [employee].[stateId],
                    [country].[countryDescription],
                    [employee].[countryId],
                    [job].[jobDescription],
                    [employee].[jobId],
                    [gender].[genderDescription],
                    [employee].[genderId],
                    [marriage].[marriageDescription],
                    [employee].[marriageId],
                    [race].[raceDescription],
                    [employee].[raceId],
                    [religion].[religionDescription],
                    [employee].[religionId],
                    [employmentStatus].[employmentStatusDescription],
                    [employee].[employmentStatusId],
                    [employee].[nationalNumber],
                    [employee].[licenseNumber],
                    [employee].[employeeNumber],
                    [employee].[employeeFirstName],
                    [employee].[employeePicture],
                    [employee].[employeeLastName],
                    [employee].[employeeDateOfBirth],
                    [employee].[employeeDateHired],
                    [employee].[employeeDateRetired],
                    [employee].[employeeBusinessPhone],
                    [employee].[employeeHomePhone],
                    [employee].[employeeMobilePhone],
                    [employee].[employeeFaxNumber],
                    [employee].[employeeAddress],
                    [employee].[employeePostCode],
                    [employee].[employeeEmail],
                    [employee].[employeeFacebook],
                    [employee].[employeeTwitter],
                    [employee].[employeeLinkedIn],
                    [employee].[employeeNotes],
                    [employee].[employeeChequePrinting],
                    [employee].[isDefault],
                    [employee].[isNew],
                    [employee].[isDraft],
                    [employee].[isUpdate],
                    [employee].[isDelete],
                    [employee].[isActive],
                    [employee].[isApproved],
                    [employee].[isReview],
                    [employee].[isPost],
                    [employee].[executeBy],
                    [employee].[executeTime],
                    [staff].[staffName]
		  FROM 	[employee]
		  JOIN	[staff]
		  ON	[employee].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [employee].[companyId]
	JOIN	[city]
	ON		[city].[cityId] = [employee].[cityId]
	JOIN	[state]
	ON		[state].[stateId] = [employee].[stateId]
	JOIN	[country]
	ON		[country].[countryId] = [employee].[countryId]
	JOIN	[job]
	ON		[job].[jobId] = [employee].[jobId]
	JOIN	[gender]
	ON		[gender].[genderId] = [employee].[genderId]
	JOIN	[marriage]
	ON		[marriage].[marriageId] = [employee].[marriageId]
	JOIN	[race]
	ON		[race].[raceId] = [employee].[raceId]
	JOIN	[religion]
	ON		[religion].[religionId] = [employee].[religionId]
	JOIN	[employmentStatus]
	ON		[employmentStatus].[employmentStatusId] = [employee].[employmentStatusId]
		  WHERE     " . $this->getAuditFilter();
                if ($this->model->getEmployeeId(0, 'single')) {
                    $sql .= " AND [employee].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getEmployeeId(0, 'single') . "'";
                }
                if ($this->model->getCityId()) {
                    $sql .= " AND [employee].[cityId]='" . $this->model->getCityId() . "'";
                }
                if ($this->model->getStateId()) {
                    $sql .= " AND [employee].[stateId]='" . $this->model->getStateId() . "'";
                }
                if ($this->model->getCountryId()) {
                    $sql .= " AND [employee].[countryId]='" . $this->model->getCountryId() . "'";
                }
                if ($this->model->getJobId()) {
                    $sql .= " AND [employee].[jobId]='" . $this->model->getJobId() . "'";
                }
                if ($this->model->getGenderId()) {
                    $sql .= " AND [employee].[genderId]='" . $this->model->getGenderId() . "'";
                }
                if ($this->model->getMarriageId()) {
                    $sql .= " AND [employee].[marriageId]='" . $this->model->getMarriageId() . "'";
                }
                if ($this->model->getRaceId()) {
                    $sql .= " AND [employee].[raceId]='" . $this->model->getRaceId() . "'";
                }
                if ($this->model->getReligionId()) {
                    $sql .= " AND [employee].[religionId]='" . $this->model->getReligionId() . "'";
                }
                if ($this->model->getEmploymentStatusId()) {
                    $sql .= " AND [employee].[employmentStatusId]='" . $this->model->getEmploymentStatusId() . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
		  SELECT                    EMPLOYEE.EMPLOYEEID AS \"employeeId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    EMPLOYEE.COMPANYID AS \"companyId\",
                    CITY.CITYDESCRIPTION AS  \"cityDescription\",
                    EMPLOYEE.CITYID AS \"cityId\",
                    STATE.STATEDESCRIPTION AS  \"stateDescription\",
                    EMPLOYEE.STATEID AS \"stateId\",
                    COUNTRY.COUNTRYDESCRIPTION AS  \"countryDescription\",
                    EMPLOYEE.COUNTRYID AS \"countryId\",
                    JOB.JOBDESCRIPTION AS  \"jobDescription\",
                    EMPLOYEE.JOBID AS \"jobId\",
                    GENDER.GENDERDESCRIPTION AS  \"genderDescription\",
                    EMPLOYEE.GENDERID AS \"genderId\",
                    MARRIAGE.MARRIAGEDESCRIPTION AS  \"marriageDescription\",
                    EMPLOYEE.MARRIAGEID AS \"marriageId\",
                    RACE.RACEDESCRIPTION AS  \"raceDescription\",
                    EMPLOYEE.RACEID AS \"raceId\",
                    RELIGION.RELIGIONDESCRIPTION AS  \"religionDescription\",
                    EMPLOYEE.RELIGIONID AS \"religionId\",
                    EMPLOYMENTSTATUS.EMPLOYMENTSTATUSDESCRIPTION AS  \"employmentStatusDescription\",
                    EMPLOYEE.EMPLOYMENTSTATUSID AS \"employmentStatusId\",
                    EMPLOYEE.NATIONALNUMBER AS \"nationalNumber\",
                    EMPLOYEE.LICENSENUMBER AS \"licenseNumber\",
                    EMPLOYEE.EMPLOYEENUMBER AS \"employeeNumber\",
                    EMPLOYEE.EMPLOYEEFIRSTNAME AS \"employeeFirstName\",
                    EMPLOYEE.EMPLOYEEPICTURE AS \"employeePicture\",
                    EMPLOYEE.EMPLOYEELASTNAME AS \"employeeLastName\",
                    EMPLOYEE.EMPLOYEEDATEOFBIRTH AS \"employeeDateOfBirth\",
                    EMPLOYEE.EMPLOYEEDATEHIRED AS \"employeeDateHired\",
                    EMPLOYEE.EMPLOYEEDATERETIRED AS \"employeeDateRetired\",
                    EMPLOYEE.EMPLOYEEBUSINESSPHONE AS \"employeeBusinessPhone\",
                    EMPLOYEE.EMPLOYEEHOMEPHONE AS \"employeeHomePhone\",
                    EMPLOYEE.EMPLOYEEMOBILEPHONE AS \"employeeMobilePhone\",
                    EMPLOYEE.EMPLOYEEFAXNUMBER AS \"employeeFaxNumber\",
                    EMPLOYEE.EMPLOYEEADDRESS AS \"employeeAddress\",
                    EMPLOYEE.EMPLOYEEPOSTCODE AS \"employeePostCode\",
                    EMPLOYEE.EMPLOYEEEMAIL AS \"employeeEmail\",
                    EMPLOYEE.EMPLOYEEFACEBOOK AS \"employeeFacebook\",
                    EMPLOYEE.EMPLOYEETWITTER AS \"employeeTwitter\",
                    EMPLOYEE.EMPLOYEELINKEDIN AS \"employeeLinkedIn\",
                    EMPLOYEE.EMPLOYEENOTES AS \"employeeNotes\",
                    EMPLOYEE.EMPLOYEECHEQUEPRINTING AS \"employeeChequePrinting\",
                    EMPLOYEE.ISDEFAULT AS \"isDefault\",
                    EMPLOYEE.ISNEW AS \"isNew\",
                    EMPLOYEE.ISDRAFT AS \"isDraft\",
                    EMPLOYEE.ISUPDATE AS \"isUpdate\",
                    EMPLOYEE.ISDELETE AS \"isDelete\",
                    EMPLOYEE.ISACTIVE AS \"isActive\",
                    EMPLOYEE.ISAPPROVED AS \"isApproved\",
                    EMPLOYEE.ISREVIEW AS \"isReview\",
                    EMPLOYEE.ISPOST AS \"isPost\",
                    EMPLOYEE.EXECUTEBY AS \"executeBy\",
                    EMPLOYEE.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\"
		  FROM 	EMPLOYEE
		  JOIN	STAFF
		  ON	EMPLOYEE.EXECUTEBY = STAFF.STAFFID
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = EMPLOYEE.COMPANYID
	JOIN	CITY
	ON		CITY.CITYID = EMPLOYEE.CITYID
	JOIN	STATE
	ON		STATE.STATEID = EMPLOYEE.STATEID
	JOIN	COUNTRY
	ON		COUNTRY.COUNTRYID = EMPLOYEE.COUNTRYID
	JOIN	JOB
	ON		JOB.JOBID = EMPLOYEE.JOBID
	JOIN	GENDER
	ON		GENDER.GENDERID = EMPLOYEE.GENDERID
	JOIN	MARRIAGE
	ON		MARRIAGE.MARRIAGEID = EMPLOYEE.MARRIAGEID
	JOIN	RACE
	ON		RACE.RACEID = EMPLOYEE.RACEID
	JOIN	RELIGION
	ON		RELIGION.RELIGIONID = EMPLOYEE.RELIGIONID
	JOIN	EMPLOYMENTSTATUS
	ON		EMPLOYMENTSTATUS.EMPLOYMENTSTATUSID = EMPLOYEE.EMPLOYMENTSTATUSID
         WHERE     " . $this->getAuditFilter();
                    if ($this->model->getEmployeeId(0, 'single')) {
                        $sql .= " AND EMPLOYEE. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getEmployeeId(0, 'single') . "'";
                    }
                    if ($this->model->getCityId()) {
                        $sql .= " AND EMPLOYEE.CITYID='" . $this->model->getCityId() . "'";
                    }
                    if ($this->model->getStateId()) {
                        $sql .= " AND EMPLOYEE.STATEID='" . $this->model->getStateId() . "'";
                    }
                    if ($this->model->getCountryId()) {
                        $sql .= " AND EMPLOYEE.COUNTRYID='" . $this->model->getCountryId() . "'";
                    }
                    if ($this->model->getJobId()) {
                        $sql .= " AND EMPLOYEE.JOBID='" . $this->model->getJobId() . "'";
                    }
                    if ($this->model->getGenderId()) {
                        $sql .= " AND EMPLOYEE.GENDERID='" . $this->model->getGenderId() . "'";
                    }
                    if ($this->model->getMarriageId()) {
                        $sql .= " AND EMPLOYEE.MARRIAGEID='" . $this->model->getMarriageId() . "'";
                    }
                    if ($this->model->getRaceId()) {
                        $sql .= " AND EMPLOYEE.RACEID='" . $this->model->getRaceId() . "'";
                    }
                    if ($this->model->getReligionId()) {
                        $sql .= " AND EMPLOYEE.RELIGIONID='" . $this->model->getReligionId() . "'";
                    }
                    if ($this->model->getEmploymentStatusId()) {
                        $sql .= " AND EMPLOYEE.EMPLOYMENTSTATUSID='" . $this->model->getEmploymentStatusId() . "'";
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
                $sql .= " AND `employee`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [employee].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND Initcap(EMPLOYEE." . strtoupper(
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
                        'employee', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'employee', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'EMPLOYEE', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
                "`employee`.`employeeId`",
                "`staff`.`staffPassword`"
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $filterArray = array(
                    "[employee].[employeeId]",
                    "[staff].[staffPassword]"
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $filterArray = array(
                        "EMPLOYEE.EMPLOYEEID",
                        "STAFF.STAFFPASSWORD"
                    );
                }
            }
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array(
                'staff',
                'employee',
                'company',
                'city',
                'state',
                'country',
                'job',
                'gender',
                'marriage',
                'race',
                'religion',
                'employmentstatus'
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array(
                    'staff',
                    'employee',
                    'company',
                    'city',
                    'state',
                    'country',
                    'job',
                    'gender',
                    'marriage',
                    'race',
                    'religion',
                    'employmentstatus'
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array(
                        'STAFF',
                        'EMPLOYEE',
                        'COMPANY',
                        'CITY',
                        'STATE',
                        'COUNTRY',
                        'JOB',
                        'GENDER',
                        'MARRIAGE',
                        'RACE',
                        'RELIGION',
                        'EMPLOYMENTSTATUS'
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
        if (!($this->model->getEmployeeId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 43;
            if ($this->model->getEmployeeId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getEmployeeId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getEmployeeId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else {
            if ($this->getPageOutput() == 'json') {
                if ($this->model->getEmployeeId(0, 'single')) {
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
                                                'value', $this->model->getEmployeeId(0, 'single')
                                        ),
                                        'nextRecord' => $this->nextRecord('value', $this->model->getEmployeeId(0, 'single')),
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
                                        'value', $this->model->getEmployeeId(0, 'single')
                                ),
                                'nextRecord' => $this->recordSet->nextRecord(
                                        'value', $this->model->getEmployeeId(0, 'single')
                                ),
                                'lastRecord' => $this->recordSet->lastRecord('value'),
                                'data' => $items
                            )
                    );
                    exit();
                }
            }
        }
        // fake return
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
        if (!$this->model->getCityId()) {
            $this->model->setCityId($this->service->getCityDefaultValue());
        }
        if (!$this->model->getStateId()) {
            $this->model->setStateId($this->service->getStateDefaultValue());
        }
        if (!$this->model->getCountryId()) {
            $this->model->setCountryId($this->service->getCountryDefaultValue());
        }
        if (!$this->model->getJobId()) {
            $this->model->setJobId($this->service->getJobDefaultValue());
        }
        if (!$this->model->getGenderId()) {
            $this->model->setGenderId($this->service->getGenderDefaultValue());
        }
        if (!$this->model->getMarriageId()) {
            $this->model->setMarriageId($this->service->getMarriageDefaultValue());
        }
        if (!$this->model->getRaceId()) {
            $this->model->setRaceId($this->service->getRaceDefaultValue());
        }
        if (!$this->model->getReligionId()) {
            $this->model->setReligionId($this->service->getReligionDefaultValue());
        }
        if (!$this->model->getEmploymentStatusId()) {
            $this->model->setEmploymentStatusId($this->service->getEmploymentStatusDefaultValue());
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`employee`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getEmployeeId(
                            0, 'single'
                    ) . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[employee]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getEmployeeId(
                                0, 'single'
                        ) . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	EMPLOYEE
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getEmployeeId(
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
               UPDATE `employee` SET
                       `cityId` = '" . $this->model->getCityId() . "',
                       `stateId` = '" . $this->model->getStateId() . "',
                       `countryId` = '" . $this->model->getCountryId() . "',
                       `jobId` = '" . $this->model->getJobId() . "',
                       `genderId` = '" . $this->model->getGenderId() . "',
                       `marriageId` = '" . $this->model->getMarriageId() . "',
                       `raceId` = '" . $this->model->getRaceId() . "',
                       `religionId` = '" . $this->model->getReligionId() . "',
                       `employmentStatusId` = '" . $this->model->getEmploymentStatusId() . "',
                       `nationalNumber` = '" . $this->model->getNationalNumber() . "',
                       `licenseNumber` = '" . $this->model->getLicenseNumber() . "',
                       `employeeNumber` = '" . $this->model->getEmployeeNumber() . "',
                       `employeeFirstName` = '" . $this->model->getEmployeeFirstName() . "',
                       `employeePicture` = '" . $this->model->getEmployeePicture() . "',
                       `employeeLastName` = '" . $this->model->getEmployeeLastName() . "',
                       `employeeDateOfBirth` = '" . $this->model->getEmployeeDateOfBirth() . "',
                       `employeeDateHired` = '" . $this->model->getEmployeeDateHired() . "',
                       `employeeDateRetired` = '" . $this->model->getEmployeeDateRetired() . "',
                       `employeeBusinessPhone` = '" . $this->model->getEmployeeBusinessPhone() . "',
                       `employeeHomePhone` = '" . $this->model->getEmployeeHomePhone() . "',
                       `employeeMobilePhone` = '" . $this->model->getEmployeeMobilePhone() . "',
                       `employeeFaxNumber` = '" . $this->model->getEmployeeFaxNumber() . "',
                       `employeeAddress` = '" . $this->model->getEmployeeAddress() . "',
                       `employeePostCode` = '" . $this->model->getEmployeePostCode() . "',
                       `employeeEmail` = '" . $this->model->getEmployeeEmail() . "',
                       `employeeFacebook` = '" . $this->model->getEmployeeFacebook() . "',
                       `employeeTwitter` = '" . $this->model->getEmployeeTwitter() . "',
                       `employeeLinkedIn` = '" . $this->model->getEmployeeLinkedIn() . "',
                       `employeeNotes` = '" . $this->model->getEmployeeNotes() . "',
                       `employeeChequePrinting` = '" . $this->model->getEmployeeChequePrinting() . "',
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
               WHERE    `employeeId`='" . $this->model->getEmployeeId('0', 'single') . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                UPDATE [employee] SET
                       [cityId] = '" . $this->model->getCityId() . "',
                       [stateId] = '" . $this->model->getStateId() . "',
                       [countryId] = '" . $this->model->getCountryId() . "',
                       [jobId] = '" . $this->model->getJobId() . "',
                       [genderId] = '" . $this->model->getGenderId() . "',
                       [marriageId] = '" . $this->model->getMarriageId() . "',
                       [raceId] = '" . $this->model->getRaceId() . "',
                       [religionId] = '" . $this->model->getReligionId() . "',
                       [employmentStatusId] = '" . $this->model->getEmploymentStatusId() . "',
                       [nationalNumber] = '" . $this->model->getNationalNumber() . "',
                       [licenseNumber] = '" . $this->model->getLicenseNumber() . "',
                       [employeeNumber] = '" . $this->model->getEmployeeNumber() . "',
                       [employeeFirstName] = '" . $this->model->getEmployeeFirstName() . "',
                       [employeePicture] = '" . $this->model->getEmployeePicture() . "',
                       [employeeLastName] = '" . $this->model->getEmployeeLastName() . "',
                       [employeeDateOfBirth] = '" . $this->model->getEmployeeDateOfBirth() . "',
                       [employeeDateHired] = '" . $this->model->getEmployeeDateHired() . "',
                       [employeeDateRetired] = '" . $this->model->getEmployeeDateRetired() . "',
                       [employeeBusinessPhone] = '" . $this->model->getEmployeeBusinessPhone() . "',
                       [employeeHomePhone] = '" . $this->model->getEmployeeHomePhone() . "',
                       [employeeMobilePhone] = '" . $this->model->getEmployeeMobilePhone() . "',
                       [employeeFaxNumber] = '" . $this->model->getEmployeeFaxNumber() . "',
                       [employeeAddress] = '" . $this->model->getEmployeeAddress() . "',
                       [employeePostCode] = '" . $this->model->getEmployeePostCode() . "',
                       [employeeEmail] = '" . $this->model->getEmployeeEmail() . "',
                       [employeeFacebook] = '" . $this->model->getEmployeeFacebook() . "',
                       [employeeTwitter] = '" . $this->model->getEmployeeTwitter() . "',
                       [employeeLinkedIn] = '" . $this->model->getEmployeeLinkedIn() . "',
                       [employeeNotes] = '" . $this->model->getEmployeeNotes() . "',
                       [employeeChequePrinting] = '" . $this->model->getEmployeeChequePrinting() . "',
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
                WHERE   [employeeId]='" . $this->model->getEmployeeId('0', 'single') . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
                UPDATE EMPLOYEE SET
                        CITYID = '" . $this->model->getCityId() . "',
                       STATEID = '" . $this->model->getStateId() . "',
                       COUNTRYID = '" . $this->model->getCountryId() . "',
                       JOBID = '" . $this->model->getJobId() . "',
                       GENDERID = '" . $this->model->getGenderId() . "',
                       MARRIAGEID = '" . $this->model->getMarriageId() . "',
                       RACEID = '" . $this->model->getRaceId() . "',
                       RELIGIONID = '" . $this->model->getReligionId() . "',
                       EMPLOYMENTSTATUSID = '" . $this->model->getEmploymentStatusId() . "',
                       NATIONALNUMBER = '" . $this->model->getNationalNumber() . "',
                       LICENSENUMBER = '" . $this->model->getLicenseNumber() . "',
                       EMPLOYEENUMBER = '" . $this->model->getEmployeeNumber() . "',
                       EMPLOYEEFIRSTNAME = '" . $this->model->getEmployeeFirstName() . "',
                       EMPLOYEEPICTURE = '" . $this->model->getEmployeePicture() . "',
                       EMPLOYEELASTNAME = '" . $this->model->getEmployeeLastName() . "',
                       EMPLOYEEDATEOFBIRTH = '" . $this->model->getEmployeeDateOfBirth() . "',
                       EMPLOYEEDATEHIRED = '" . $this->model->getEmployeeDateHired() . "',
                       EMPLOYEEDATERETIRED = '" . $this->model->getEmployeeDateRetired() . "',
                       EMPLOYEEBUSINESSPHONE = '" . $this->model->getEmployeeBusinessPhone() . "',
                       EMPLOYEEHOMEPHONE = '" . $this->model->getEmployeeHomePhone() . "',
                       EMPLOYEEMOBILEPHONE = '" . $this->model->getEmployeeMobilePhone() . "',
                       EMPLOYEEFAXNUMBER = '" . $this->model->getEmployeeFaxNumber() . "',
                       EMPLOYEEADDRESS = '" . $this->model->getEmployeeAddress() . "',
                       EMPLOYEEPOSTCODE = '" . $this->model->getEmployeePostCode() . "',
                       EMPLOYEEEMAIL = '" . $this->model->getEmployeeEmail() . "',
                       EMPLOYEEFACEBOOK = '" . $this->model->getEmployeeFacebook() . "',
                       EMPLOYEETWITTER = '" . $this->model->getEmployeeTwitter() . "',
                       EMPLOYEELINKEDIN = '" . $this->model->getEmployeeLinkedIn() . "',
                       EMPLOYEENOTES = '" . $this->model->getEmployeeNotes() . "',
                       EMPLOYEECHEQUEPRINTING = '" . $this->model->getEmployeeChequePrinting() . "',
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
                WHERE  EMPLOYEEID='" . $this->model->getEmployeeId('0', 'single') . "'";
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
           FROM 	`employee`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getEmployeeId(
                            0, 'single'
                    ) . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[employee]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getEmployeeId(
                                0, 'single'
                        ) . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	EMPLOYEE
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getEmployeeId(
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
               UPDATE  `employee`
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
               WHERE   `employeeId`   =  '" . $this->model->getEmployeeId(0, 'single') . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
               UPDATE  [employee]
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
               WHERE   [employeeId]	=  '" . $this->model->getEmployeeId(0, 'single') . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
               UPDATE  EMPLOYEE
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
               WHERE   EMPLOYEEID	=  '" . $this->model->getEmployeeId(0, 'single') . "'";
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
           SELECT  `employeeNumber`
           FROM    `employee`
           WHERE   `employeeCode` 	= 	'" . $this->model->getEmployeeNumber() . "'
           AND     `isActive`  =   1
           AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT  [employeeNumber]
           FROM    [employee]
           WHERE   [employeeNumber] = 	'" . $this->model->getEmployeeNumber() . "'
           AND     [isActive]  =   1
           AND     [companyId] =	'" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
               SELECT  EMPLOYEENUMBER as \"employeeNumber\"
               FROM    EMPLOYEE
               WHERE   EMPLOYEENUMBER	= 	'" . $this->model->getEmployeeNumber() . "'
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
                        "employeeNumber" => $row ['employeeNumber'],
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
     * Return  City
     * @return null|string
     */
    public function getCity() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getCity(
                        $this->model->getcountryId(), $this->model->getStateId(), $this->model->getDivisionId(), $this->model->getDistrictId()
        );
    }

    /**
     * Return  State
     * @return null|string
     */
    public function getState() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getState($this->model->getCountryId());
    }

    /**
     * Return  Country
     * @return null|string
     */
    public function getCountry() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getCountry();
    }

    /**
     * Return  Job
     * @return null|string
     */
    public function getJob() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getJob();
    }

    /**
     * Return  Gender
     * @return null|string
     */
    public function getGender() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getGender();
    }

    /**
     * Return  Marriage
     * @return null|string
     */
    public function getMarriage() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getMarriage();
    }

    /**
     * Return  Race
     * @return null|string
     */
    public function getRace() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getRace();
    }

    /**
     * Return  Religion
     * @return null|string
     */
    public function getReligion() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getReligion();
    }

    /**
     * Return  EmploymentStatus
     * @return null|string
     */
    public function getEmploymentStatus() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getEmploymentStatus();
    }

    /**
     * Set Employee Picture
     * @return void
     */
    public function setEmployeePicture() {
        $this->service->setEmployeePicture();
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
                ->setSubject('employee')
                ->setDescription('Generated by PhpExcel an Idcms Generator')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('humanResource/employment');
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
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['cityIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['stateIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['countryIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['jobIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['genderIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['marriageIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['raceIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['religionIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['employmentStatusIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('L3', $this->translate['nationalNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('M3', $this->translate['licenseNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('N3', $this->translate['employeeNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('O3', $this->translate['employeeFirstNameLabel']);
        $this->excel->getActiveSheet()->setCellValue('P3', $this->translate['employeePictureLabel']);
        $this->excel->getActiveSheet()->setCellValue('Q3', $this->translate['employeeLastNameLabel']);
        $this->excel->getActiveSheet()->setCellValue('R3', $this->translate['employeeDateOfBirthLabel']);
        $this->excel->getActiveSheet()->setCellValue('S3', $this->translate['employeeDateHiredLabel']);
        $this->excel->getActiveSheet()->setCellValue('T3', $this->translate['employeeDateRetiredLabel']);
        $this->excel->getActiveSheet()->setCellValue('U3', $this->translate['employeeBusinessPhoneLabel']);
        $this->excel->getActiveSheet()->setCellValue('V3', $this->translate['employeeHomePhoneLabel']);
        $this->excel->getActiveSheet()->setCellValue('W3', $this->translate['employeeMobilePhoneLabel']);
        $this->excel->getActiveSheet()->setCellValue('X3', $this->translate['employeeFaxNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('Y3', $this->translate['employeeAddressLabel']);
        $this->excel->getActiveSheet()->setCellValue('Z3', $this->translate['employeePostCodeLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['employeeEmailLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['employeeFacebookLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['employeeTwitterLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['employeeLinkedInLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['employeeNotesLabel']);
        $this->excel->getActiveSheet()->setCellValue('3', $this->translate['employeeChequePrintingLabel']);
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
            $this->excel->getActiveSheet()->setCellValue('C' . $loopRow, strip_tags($row ['cityDescription']));
            $this->excel->getActiveSheet()->setCellValue('D' . $loopRow, strip_tags($row ['stateDescription']));
            $this->excel->getActiveSheet()->setCellValue('E' . $loopRow, strip_tags($row ['countryDescription']));
            $this->excel->getActiveSheet()->setCellValue('F' . $loopRow, strip_tags($row ['jobDescription']));
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, strip_tags($row ['genderDescription']));
            $this->excel->getActiveSheet()->setCellValue('H' . $loopRow, strip_tags($row ['marriageDescription']));
            $this->excel->getActiveSheet()->setCellValue('I' . $loopRow, strip_tags($row ['raceDescription']));
            $this->excel->getActiveSheet()->setCellValue('J' . $loopRow, strip_tags($row ['religionDescription']));
            $this->excel->getActiveSheet()->setCellValue(
                    'K' . $loopRow, strip_tags($row ['employmentStatusDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue('L' . $loopRow, strip_tags($row ['nationalNumber']));
            $this->excel->getActiveSheet()->setCellValue('M' . $loopRow, strip_tags($row ['licenseNumber']));
            $this->excel->getActiveSheet()->setCellValue('N' . $loopRow, strip_tags($row ['employeeNumber']));
            $this->excel->getActiveSheet()->setCellValue('O' . $loopRow, strip_tags($row ['employeeFirstName']));
            $this->excel->getActiveSheet()->setCellValue('P' . $loopRow, strip_tags($row ['employeePicture']));
            $this->excel->getActiveSheet()->setCellValue('Q' . $loopRow, strip_tags($row ['employeeLastName']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('R' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $this->excel->getActiveSheet()->setCellValue('R' . $loopRow, strip_tags($row ['employeeDateOfBirth']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('S' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $this->excel->getActiveSheet()->setCellValue('S' . $loopRow, strip_tags($row ['employeeDateHired']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('T' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $this->excel->getActiveSheet()->setCellValue('T' . $loopRow, strip_tags($row ['employeeDateRetired']));
            $this->excel->getActiveSheet()->setCellValue('U' . $loopRow, strip_tags($row ['employeeBusinessPhone']));
            $this->excel->getActiveSheet()->setCellValue('V' . $loopRow, strip_tags($row ['employeeHomePhone']));
            $this->excel->getActiveSheet()->setCellValue('W' . $loopRow, strip_tags($row ['employeeMobilePhone']));
            $this->excel->getActiveSheet()->setCellValue('X' . $loopRow, strip_tags($row ['employeeFaxNumber']));
            $this->excel->getActiveSheet()->setCellValue('Y' . $loopRow, strip_tags($row ['employeeAddress']));
            $this->excel->getActiveSheet()->setCellValue('Z' . $loopRow, strip_tags($row ['employeePostCode']));
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['employeeEmail']));
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['employeeFacebook']));
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['employeeTwitter']));
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['employeeLinkedIn']));
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['employeeNotes']));
            $this->excel->getActiveSheet()->setCellValue('' . $loopRow, strip_tags($row ['employeeChequePrinting']));
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
                $filename = "employee" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/humanResource/employment/document/" . $folder . "/" . $filename;
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
                $filename = "employee" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/humanResource/employment/document/" . $folder . "/" . $filename;
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
                $filename = "employee" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/humanResource/employment/document/" . $folder . "/" . $filename;
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
                $filename = "employee" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/humanResource/employment/document/" . $folder . "/" . $filename;
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
        $employeeObject = new EmployeeClass ();
        if ($_POST['securityToken'] != $employeeObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $employeeObject->setLeafId($_POST ['leafId']);
        }
        if (isset($_POST ['offset'])) {
            $employeeObject->setStart($_POST ['offset']);
        }
        if (isset($_POST ['leafId'])) {
            $employeeObject->setLimit($_POST ['limit']);
        }
        $employeeObject->setPageOutput($_POST['output']);
        $employeeObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $employeeObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $employeeObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $employeeObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $employeeObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$employeeObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$employeeObject->delete(); 
        }
        if ($_POST ['method'] == 'upload') {
            $employeeObject->setEmployeePicture();
        }
    }
}
if (isset($_GET ['method'])) {
    $employeeObject = new EmployeeClass ();
    if ($_GET['securityToken'] != $employeeObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $employeeObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $employeeObject->execute();
    /*
     * Update Status of The Table. Admin Level Only 
     */
    if ($_GET ['method'] == 'updateStatus') {
        $employeeObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key 
     */
    if ($_GET['method'] == 'duplicate') {
        $employeeObject->duplicate();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $employeeObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $employeeObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $employeeObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $employeeObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $employeeObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $employeeObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        $employeeObject->setServiceOutput('option');
        if (($_GET['filter'] == 'city')) {
            $employeeObject->getCity();
        }
        if (($_GET['filter'] == 'state')) {
            $employeeObject->getState();
        }
        if (($_GET['filter'] == 'country')) {
            $employeeObject->getCountry();
        }
        if (($_GET['filter'] == 'job')) {
            $employeeObject->getJob();
        }
        if (($_GET['filter'] == 'gender')) {
            $employeeObject->getGender();
        }
        if (($_GET['filter'] == 'marriage')) {
            $employeeObject->getMarriage();
        }
        if (($_GET['filter'] == 'race')) {
            $employeeObject->getRace();
        }
        if (($_GET['filter'] == 'religion')) {
            $employeeObject->getReligion();
        }
        if (($_GET['filter'] == 'employmentStatus')) {
            $employeeObject->getEmploymentStatus();
        }
        if ($_GET ['method'] == 'upload') {
            $employeeObject->setEmployeePicture();
        }
    }
}
?>
