<?php

namespace Core\HumanResource\Recruitment\Candidate\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\HumanResource\Recruitment\Candidate\Model\CandidateModel;
use Core\HumanResource\Recruitment\Candidate\Service\CandidateService;
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
require_once($newFakeDocumentRoot . "v3/humanResource/recruitment/model/candidateModel.php");
require_once($newFakeDocumentRoot . "v3/humanResource/recruitment/service/candidateService.php");

/**
 * Class Candidate
 * this is candidate controller files.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package  Core\HumanResource\Recruitment\Candidate\Controller
 * @subpackage Recruitment
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class CandidateClass extends ConfigClass {

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
     * @var \Core\HumanResource\Recruitment\Candidate\Model\CandidateModel
     */
    public $model;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     * @var \PHPPowerPoint
     */
    //private $powerPoint;
    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\HumanResource\Recruitment\Candidate\Service\CandidateService
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
        $this->setViewPath("./v3/humanResource/recruitment/view/candidate.php");
        $this->setControllerPath("./v3/humanResource/recruitment/controller/candidateController.php");
        $this->setServicePath("./v3/humanResource/recruitment/service/candidateService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new CandidateModel();
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

        $this->service = new CandidateService();
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
        //$this->model->setDocumentNumber($this->getDocumentNumber);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `candidate`
            (
                 `companyId`,
                 `cityId`,
                 `stateId`,
                 `countryId`,
                 `genderId`,
                 `marriageId`,
                 `raceId`,
                 `religionId`,
                 `candidateFirstName`,
                 `candidateLastName`,
                 `candidateEmail`,
                 `candidateBusinessPhone`,
                 `candidateHomePhone`,
                 `candidateMobilePhone`,
                 `candidateFaxNumber`,
                 `candidateAddress`,
                 `candidatePostCode`,
                 `candidateWebPage`,
                 `candidateFacebook`,
                 `candidateTwitter`,
                 `candidateLinkedIn`,
                 `candidateNotes`,
                 `candidatePicture`,
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
                 '" . $this->model->getGenderId() . "',
                 '" . $this->model->getMarriageId() . "',
                 '" . $this->model->getRaceId() . "',
                 '" . $this->model->getReligionId() . "',
                 '" . $this->model->getCandidateFirstName() . "',
                 '" . $this->model->getCandidateLastName() . "',
                 '" . $this->model->getCandidateEmail() . "',
                 '" . $this->model->getCandidateBusinessPhone() . "',
                 '" . $this->model->getCandidateHomePhone() . "',
                 '" . $this->model->getCandidateMobilePhone() . "',
                 '" . $this->model->getCandidateFaxNumber() . "',
                 '" . $this->model->getCandidateAddress() . "',
                 '" . $this->model->getCandidatePostCode() . "',
                 '" . $this->model->getCandidateWebPage() . "',
                 '" . $this->model->getCandidateFacebook() . "',
                 '" . $this->model->getCandidateTwitter() . "',
                 '" . $this->model->getCandidateLinkedIn() . "',
                 '" . $this->model->getCandidateNotes() . "',
                 '" . $this->model->getCandidatePicture() . "',
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
            INSERT INTO [candidate]
            (
                 [candidateId],
                 [companyId],
                 [cityId],
                 [stateId],
                 [countryId],
                 [genderId],
                 [marriageId],
                 [raceId],
                 [religionId],
                 [candidateFirstName],
                 [candidateLastName],
                 [candidateEmail],
                 [candidateBusinessPhone],
                 [candidateHomePhone],
                 [candidateMobilePhone],
                 [candidateFaxNumber],
                 [candidateAddress],
                 [candidatePostCode],
                 [candidateWebPage],
                 [candidateFacebook],
                 [candidateTwitter],
                 [candidateLinkedIn],
                 [candidateNotes],
                 [candidatePicture],
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
                 '" . $this->model->getGenderId() . "',
                 '" . $this->model->getMarriageId() . "',
                 '" . $this->model->getRaceId() . "',
                 '" . $this->model->getReligionId() . "',
                 '" . $this->model->getCandidateFirstName() . "',
                 '" . $this->model->getCandidateLastName() . "',
                 '" . $this->model->getCandidateEmail() . "',
                 '" . $this->model->getCandidateBusinessPhone() . "',
                 '" . $this->model->getCandidateHomePhone() . "',
                 '" . $this->model->getCandidateMobilePhone() . "',
                 '" . $this->model->getCandidateFaxNumber() . "',
                 '" . $this->model->getCandidateAddress() . "',
                 '" . $this->model->getCandidatePostCode() . "',
                 '" . $this->model->getCandidateWebPage() . "',
                 '" . $this->model->getCandidateFacebook() . "',
                 '" . $this->model->getCandidateTwitter() . "',
                 '" . $this->model->getCandidateLinkedIn() . "',
                 '" . $this->model->getCandidateNotes() . "',
                 '" . $this->model->getCandidatePicture() . "',
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
            INSERT INTO CANDIDATE
            (
                 COMPANYID,
                 CITYID,
                 STATEID,
                 COUNTRYID,
                 GENDERID,
                 MARRIAGEID,
                 RACEID,
                 RELIGIONID,
                 CANDIDATEFIRSTNAME,
                 CANDIDATELASTNAME,
                 CANDIDATEEMAIL,
                 CANDIDATEBUSINESSPHONE,
                 CANDIDATEHOMEPHONE,
                 CANDIDATEMOBILEPHONE,
                 CANDIDATEFAXNUMBER,
                 CANDIDATEADDRESS,
                 CANDIDATEPOSTCODE,
                 CANDIDATEWEBPAGE,
                 CANDIDATEFACEBOOK,
                 CANDIDATETWITTER,
                 CANDIDATELINKEDIN,
                 CANDIDATENOTES,
                 CANDIDATEPICTURE,
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
                 '" . $this->model->getGenderId() . "',
                 '" . $this->model->getMarriageId() . "',
                 '" . $this->model->getRaceId() . "',
                 '" . $this->model->getReligionId() . "',
                 '" . $this->model->getCandidateFirstName() . "',
                 '" . $this->model->getCandidateLastName() . "',
                 '" . $this->model->getCandidateEmail() . "',
                 '" . $this->model->getCandidateBusinessPhone() . "',
                 '" . $this->model->getCandidateHomePhone() . "',
                 '" . $this->model->getCandidateMobilePhone() . "',
                 '" . $this->model->getCandidateFaxNumber() . "',
                 '" . $this->model->getCandidateAddress() . "',
                 '" . $this->model->getCandidatePostCode() . "',
                 '" . $this->model->getCandidateWebPage() . "',
                 '" . $this->model->getCandidateFacebook() . "',
                 '" . $this->model->getCandidateTwitter() . "',
                 '" . $this->model->getCandidateLinkedIn() . "',
                 '" . $this->model->getCandidateNotes() . "',
                 '" . $this->model->getCandidatePicture() . "',
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
        $candidateId = $this->q->lastInsertId();
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
                    "candidateId" => $candidateId,
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
         FROM    `candidate`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT    COUNT(*) AS total
         FROM      [candidate]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT    COUNT(*)    AS  \"total\"
         FROM      CANDIDATE
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
                            " `candidate`.`isActive` = 1  AND `candidate`.`companyId`='" . $this->getCompanyId() . "' "
                    );
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [candidate].[isActive] = 1 AND [candidate].[companyId]='" . $this->getCompanyId() . "' "
                        );
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(
                                    " CANDIDATE.ISACTIVE = 1  AND CANDIDATE.COMPANYID='" . $this->getCompanyId() . "'"
                            );
                        }
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter("   `candidate`.`companyId`='" . $this->getCompanyId() . "'	");
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(" [candidate].[companyId]='" . $this->getCompanyId() . "' ");
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(" CANDIDATE.COMPANYID='" . $this->getCompanyId() . "' ");
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
       SELECT                    `candidate`.`candidateId`,
                    `company`.`companyDescription`,
                    `candidate`.`companyId`,
                    `city`.`cityDescription`,
                    `candidate`.`cityId`,
                    `state`.`stateDescription`,
                    `candidate`.`stateId`,
                    `country`.`countryDescription`,
                    `candidate`.`countryId`,
                    `gender`.`genderDescription`,
                    `candidate`.`genderId`,
                    `marriage`.`marriageDescription`,
                    `candidate`.`marriageId`,
                    `race`.`raceDescription`,
                    `candidate`.`raceId`,
                    `religion`.`religionDescription`,
                    `candidate`.`religionId`,
                    `candidate`.`candidateFirstName`,
                    `candidate`.`candidateLastName`,
                    `candidate`.`candidateEmail`,
                    `candidate`.`candidateBusinessPhone`,
                    `candidate`.`candidateHomePhone`,
                    `candidate`.`candidateMobilePhone`,
                    `candidate`.`candidateFaxNumber`,
                    `candidate`.`candidateAddress`,
                    `candidate`.`candidatePostCode`,
                    `candidate`.`candidateWebPage`,
                    `candidate`.`candidateFacebook`,
                    `candidate`.`candidateTwitter`,
                    `candidate`.`candidateLinkedIn`,
                    `candidate`.`candidateNotes`,
                    `candidate`.`candidatePicture`,
                    `candidate`.`isDefault`,
                    `candidate`.`isNew`,
                    `candidate`.`isDraft`,
                    `candidate`.`isUpdate`,
                    `candidate`.`isDelete`,
                    `candidate`.`isActive`,
                    `candidate`.`isApproved`,
                    `candidate`.`isReview`,
                    `candidate`.`isPost`,
                    `candidate`.`executeBy`,
                    `candidate`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `candidate`
		  JOIN      `staff`
		  ON        `candidate`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `candidate`.`companyId`
	JOIN	`city`
	ON		`city`.`cityId` = `candidate`.`cityId`
	JOIN	`state`
	ON		`state`.`stateId` = `candidate`.`stateId`
	JOIN	`country`
	ON		`country`.`countryId` = `candidate`.`countryId`
	JOIN	`gender`
	ON		`gender`.`genderId` = `candidate`.`genderId`
	JOIN	`marriage`
	ON		`marriage`.`marriageId` = `candidate`.`marriageId`
	JOIN	`race`
	ON		`race`.`raceId` = `candidate`.`raceId`
	JOIN	`religion`
	ON		`religion`.`religionId` = `candidate`.`religionId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getCandidateId(0, 'single')) {
                $sql .= " AND `candidate`.`" . $this->model->getPrimaryKeyName() . "`='" . $this->model->getCandidateId(
                                0, 'single'
                        ) . "'";
            }
            if ($this->model->getCityId()) {
                $sql .= " AND `candidate`.`cityId`='" . $this->model->getCityId() . "'";
            }
            if ($this->model->getStateId()) {
                $sql .= " AND `candidate`.`stateId`='" . $this->model->getStateId() . "'";
            }
            if ($this->model->getCountryId()) {
                $sql .= " AND `candidate`.`countryId`='" . $this->model->getCountryId() . "'";
            }
            if ($this->model->getGenderId()) {
                $sql .= " AND `candidate`.`genderId`='" . $this->model->getGenderId() . "'";
            }
            if ($this->model->getMarriageId()) {
                $sql .= " AND `candidate`.`marriageId`='" . $this->model->getMarriageId() . "'";
            }
            if ($this->model->getRaceId()) {
                $sql .= " AND `candidate`.`raceId`='" . $this->model->getRaceId() . "'";
            }
            if ($this->model->getReligionId()) {
                $sql .= " AND `candidate`.`religionId`='" . $this->model->getReligionId() . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
		  SELECT                    [candidate].[candidateId],
                    [company].[companyDescription],
                    [candidate].[companyId],
                    [city].[cityDescription],
                    [candidate].[cityId],
                    [state].[stateDescription],
                    [candidate].[stateId],
                    [country].[countryDescription],
                    [candidate].[countryId],
                    [gender].[genderDescription],
                    [candidate].[genderId],
                    [marriage].[marriageDescription],
                    [candidate].[marriageId],
                    [race].[raceDescription],
                    [candidate].[raceId],
                    [religion].[religionDescription],
                    [candidate].[religionId],
                    [candidate].[candidateFirstName],
                    [candidate].[candidateLastName],
                    [candidate].[candidateEmail],
                    [candidate].[candidateBusinessPhone],
                    [candidate].[candidateHomePhone],
                    [candidate].[candidateMobilePhone],
                    [candidate].[candidateFaxNumber],
                    [candidate].[candidateAddress],
                    [candidate].[candidatePostCode],
                    [candidate].[candidateWebPage],
                    [candidate].[candidateFacebook],
                    [candidate].[candidateTwitter],
                    [candidate].[candidateLinkedIn],
                    [candidate].[candidateNotes],
                    [candidate].[candidatePicture],
                    [candidate].[isDefault],
                    [candidate].[isNew],
                    [candidate].[isDraft],
                    [candidate].[isUpdate],
                    [candidate].[isDelete],
                    [candidate].[isActive],
                    [candidate].[isApproved],
                    [candidate].[isReview],
                    [candidate].[isPost],
                    [candidate].[executeBy],
                    [candidate].[executeTime],
                    [staff].[staffName]
		  FROM 	[candidate]
		  JOIN	[staff]
		  ON	[candidate].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [candidate].[companyId]
	JOIN	[city]
	ON		[city].[cityId] = [candidate].[cityId]
	JOIN	[state]
	ON		[state].[stateId] = [candidate].[stateId]
	JOIN	[country]
	ON		[country].[countryId] = [candidate].[countryId]
	JOIN	[gender]
	ON		[gender].[genderId] = [candidate].[genderId]
	JOIN	[marriage]
	ON		[marriage].[marriageId] = [candidate].[marriageId]
	JOIN	[race]
	ON		[race].[raceId] = [candidate].[raceId]
	JOIN	[religion]
	ON		[religion].[religionId] = [candidate].[religionId]
		  WHERE     " . $this->getAuditFilter();
                if ($this->model->getCandidateId(0, 'single')) {
                    $sql .= " AND [candidate].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getCandidateId(0, 'single') . "'";
                }
                if ($this->model->getCityId()) {
                    $sql .= " AND [candidate].[cityId]='" . $this->model->getCityId() . "'";
                }
                if ($this->model->getStateId()) {
                    $sql .= " AND [candidate].[stateId]='" . $this->model->getStateId() . "'";
                }
                if ($this->model->getCountryId()) {
                    $sql .= " AND [candidate].[countryId]='" . $this->model->getCountryId() . "'";
                }
                if ($this->model->getGenderId()) {
                    $sql .= " AND [candidate].[genderId]='" . $this->model->getGenderId() . "'";
                }
                if ($this->model->getMarriageId()) {
                    $sql .= " AND [candidate].[marriageId]='" . $this->model->getMarriageId() . "'";
                }
                if ($this->model->getRaceId()) {
                    $sql .= " AND [candidate].[raceId]='" . $this->model->getRaceId() . "'";
                }
                if ($this->model->getReligionId()) {
                    $sql .= " AND [candidate].[religionId]='" . $this->model->getReligionId() . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
		  SELECT                    CANDIDATE.CANDIDATEID AS \"candidateId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    CANDIDATE.COMPANYID AS \"companyId\",
                    CITY.CITYDESCRIPTION AS  \"cityDescription\",
                    CANDIDATE.CITYID AS \"cityId\",
                    STATE.STATEDESCRIPTION AS  \"stateDescription\",
                    CANDIDATE.STATEID AS \"stateId\",
                    COUNTRY.COUNTRYDESCRIPTION AS  \"countryDescription\",
                    CANDIDATE.COUNTRYID AS \"countryId\",
                    GENDER.GENDERDESCRIPTION AS  \"genderDescription\",
                    CANDIDATE.GENDERID AS \"genderId\",
                    MARRIAGE.MARRIAGEDESCRIPTION AS  \"marriageDescription\",
                    CANDIDATE.MARRIAGEID AS \"marriageId\",
                    RACE.RACEDESCRIPTION AS  \"raceDescription\",
                    CANDIDATE.RACEID AS \"raceId\",
                    RELIGION.RELIGIONDESCRIPTION AS  \"religionDescription\",
                    CANDIDATE.RELIGIONID AS \"religionId\",
                    CANDIDATE.CANDIDATEFIRSTNAME AS \"candidateFirstName\",
                    CANDIDATE.CANDIDATELASTNAME AS \"candidateLastName\",
                    CANDIDATE.CANDIDATEEMAIL AS \"candidateEmail\",
                    CANDIDATE.CANDIDATEBUSINESSPHONE AS \"candidateBusinessPhone\",
                    CANDIDATE.CANDIDATEHOMEPHONE AS \"candidateHomePhone\",
                    CANDIDATE.CANDIDATEMOBILEPHONE AS \"candidateMobilePhone\",
                    CANDIDATE.CANDIDATEFAXNUMBER AS \"candidateFaxNumber\",
                    CANDIDATE.CANDIDATEADDRESS AS \"candidateAddress\",
                    CANDIDATE.CANDIDATEPOSTCODE AS \"candidatePostCode\",
                    CANDIDATE.CANDIDATEWEBPAGE AS \"candidateWebPage\",
                    CANDIDATE.CANDIDATEFACEBOOK AS \"candidateFacebook\",
                    CANDIDATE.CANDIDATETWITTER AS \"candidateTwitter\",
                    CANDIDATE.CANDIDATELINKEDIN AS \"candidateLinkedIn\",
                    CANDIDATE.CANDIDATENOTES AS \"candidateNotes\",
                    CANDIDATE.CANDIDATEPICTURE AS \"candidatePicture\",
                    CANDIDATE.ISDEFAULT AS \"isDefault\",
                    CANDIDATE.ISNEW AS \"isNew\",
                    CANDIDATE.ISDRAFT AS \"isDraft\",
                    CANDIDATE.ISUPDATE AS \"isUpdate\",
                    CANDIDATE.ISDELETE AS \"isDelete\",
                    CANDIDATE.ISACTIVE AS \"isActive\",
                    CANDIDATE.ISAPPROVED AS \"isApproved\",
                    CANDIDATE.ISREVIEW AS \"isReview\",
                    CANDIDATE.ISPOST AS \"isPost\",
                    CANDIDATE.EXECUTEBY AS \"executeBy\",
                    CANDIDATE.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\"
		  FROM 	CANDIDATE
		  JOIN	STAFF
		  ON	CANDIDATE.EXECUTEBY = STAFF.STAFFID
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = CANDIDATE.COMPANYID
	JOIN	CITY
	ON		CITY.CITYID = CANDIDATE.CITYID
	JOIN	STATE
	ON		STATE.STATEID = CANDIDATE.STATEID
	JOIN	COUNTRY
	ON		COUNTRY.COUNTRYID = CANDIDATE.COUNTRYID
	JOIN	GENDER
	ON		GENDER.GENDERID = CANDIDATE.GENDERID
	JOIN	MARRIAGE
	ON		MARRIAGE.MARRIAGEID = CANDIDATE.MARRIAGEID
	JOIN	RACE
	ON		RACE.RACEID = CANDIDATE.RACEID
	JOIN	RELIGION
	ON		RELIGION.RELIGIONID = CANDIDATE.RELIGIONID
         WHERE     " . $this->getAuditFilter();
                    if ($this->model->getCandidateId(0, 'single')) {
                        $sql .= " AND CANDIDATE. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getCandidateId(0, 'single') . "'";
                    }
                    if ($this->model->getCityId()) {
                        $sql .= " AND CANDIDATE.CITYID='" . $this->model->getCityId() . "'";
                    }
                    if ($this->model->getStateId()) {
                        $sql .= " AND CANDIDATE.STATEID='" . $this->model->getStateId() . "'";
                    }
                    if ($this->model->getCountryId()) {
                        $sql .= " AND CANDIDATE.COUNTRYID='" . $this->model->getCountryId() . "'";
                    }
                    if ($this->model->getGenderId()) {
                        $sql .= " AND CANDIDATE.GENDERID='" . $this->model->getGenderId() . "'";
                    }
                    if ($this->model->getMarriageId()) {
                        $sql .= " AND CANDIDATE.MARRIAGEID='" . $this->model->getMarriageId() . "'";
                    }
                    if ($this->model->getRaceId()) {
                        $sql .= " AND CANDIDATE.RACEID='" . $this->model->getRaceId() . "'";
                    }
                    if ($this->model->getReligionId()) {
                        $sql .= " AND CANDIDATE.RELIGIONID='" . $this->model->getReligionId() . "'";
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
                $sql .= " AND `candidate`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [candidate].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND Initcap(CANDIDATE." . strtoupper(
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
                        'candidate', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'candidate', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'CANDIDATE', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
                "`candidate`.`candidateId`",
                "`staff`.`staffPassword`"
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $filterArray = array(
                    "[candidate].[candidateId]",
                    "[staff].[staffPassword]"
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $filterArray = array(
                        "CANDIDATE.CANDIDATEID",
                        "STAFF.STAFFPASSWORD"
                    );
                }
            }
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array(
                'staff',
                'candidate',
                'company',
                'city',
                'state',
                'country',
                'gender',
                'marriage',
                'race',
                'religion'
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array(
                    'staff',
                    'candidate',
                    'company',
                    'city',
                    'state',
                    'country',
                    'gender',
                    'marriage',
                    'race',
                    'religion'
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array(
                        'STAFF',
                        'CANDIDATE',
                        'COMPANY',
                        'CITY',
                        'STATE',
                        'COUNTRY',
                        'GENDER',
                        'MARRIAGE',
                        'RACE',
                        'RELIGION'
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
        if (!($this->model->getCandidateId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 35;
            if ($this->model->getCandidateId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getCandidateId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getCandidateId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else {
            if ($this->getPageOutput() == 'json') {
                if ($this->model->getCandidateId(0, 'single')) {
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
                                                'value', $this->model->getCandidateId(0, 'single')
                                        ),
                                        'nextRecord' => $this->nextRecord('value', $this->model->getCandidateId(0, 'single')),
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
                                        'value', $this->model->getCandidateId(0, 'single')
                                ),
                                'nextRecord' => $this->recordSet->nextRecord(
                                        'value', $this->model->getCandidateId(0, 'single')
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
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`candidate`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getCandidateId(
                            0, 'single'
                    ) . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[candidate]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getCandidateId(
                                0, 'single'
                        ) . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	CANDIDATE
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getCandidateId(
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
               UPDATE `candidate` SET
                       `cityId` = '" . $this->model->getCityId() . "',
                       `stateId` = '" . $this->model->getStateId() . "',
                       `countryId` = '" . $this->model->getCountryId() . "',
                       `genderId` = '" . $this->model->getGenderId() . "',
                       `marriageId` = '" . $this->model->getMarriageId() . "',
                       `raceId` = '" . $this->model->getRaceId() . "',
                       `religionId` = '" . $this->model->getReligionId() . "',
                       `candidateFirstName` = '" . $this->model->getCandidateFirstName() . "',
                       `candidateLastName` = '" . $this->model->getCandidateLastName() . "',
                       `candidateEmail` = '" . $this->model->getCandidateEmail() . "',
                       `candidateBusinessPhone` = '" . $this->model->getCandidateBusinessPhone() . "',
                       `candidateHomePhone` = '" . $this->model->getCandidateHomePhone() . "',
                       `candidateMobilePhone` = '" . $this->model->getCandidateMobilePhone() . "',
                       `candidateFaxNumber` = '" . $this->model->getCandidateFaxNumber() . "',
                       `candidateAddress` = '" . $this->model->getCandidateAddress() . "',
                       `candidatePostCode` = '" . $this->model->getCandidatePostCode() . "',
                       `candidateWebPage` = '" . $this->model->getCandidateWebPage() . "',
                       `candidateFacebook` = '" . $this->model->getCandidateFacebook() . "',
                       `candidateTwitter` = '" . $this->model->getCandidateTwitter() . "',
                       `candidateLinkedIn` = '" . $this->model->getCandidateLinkedIn() . "',
                       `candidateNotes` = '" . $this->model->getCandidateNotes() . "',
                       `candidatePicture` = '" . $this->model->getCandidatePicture() . "',
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
               WHERE    `candidateId`='" . $this->model->getCandidateId('0', 'single') . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                UPDATE [candidate] SET
                       [cityId] = '" . $this->model->getCityId() . "',
                       [stateId] = '" . $this->model->getStateId() . "',
                       [countryId] = '" . $this->model->getCountryId() . "',
                       [genderId] = '" . $this->model->getGenderId() . "',
                       [marriageId] = '" . $this->model->getMarriageId() . "',
                       [raceId] = '" . $this->model->getRaceId() . "',
                       [religionId] = '" . $this->model->getReligionId() . "',
                       [candidateFirstName] = '" . $this->model->getCandidateFirstName() . "',
                       [candidateLastName] = '" . $this->model->getCandidateLastName() . "',
                       [candidateEmail] = '" . $this->model->getCandidateEmail() . "',
                       [candidateBusinessPhone] = '" . $this->model->getCandidateBusinessPhone() . "',
                       [candidateHomePhone] = '" . $this->model->getCandidateHomePhone() . "',
                       [candidateMobilePhone] = '" . $this->model->getCandidateMobilePhone() . "',
                       [candidateFaxNumber] = '" . $this->model->getCandidateFaxNumber() . "',
                       [candidateAddress] = '" . $this->model->getCandidateAddress() . "',
                       [candidatePostCode] = '" . $this->model->getCandidatePostCode() . "',
                       [candidateWebPage] = '" . $this->model->getCandidateWebPage() . "',
                       [candidateFacebook] = '" . $this->model->getCandidateFacebook() . "',
                       [candidateTwitter] = '" . $this->model->getCandidateTwitter() . "',
                       [candidateLinkedIn] = '" . $this->model->getCandidateLinkedIn() . "',
                       [candidateNotes] = '" . $this->model->getCandidateNotes() . "',
                       [candidatePicture] = '" . $this->model->getCandidatePicture() . "',
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
                WHERE   [candidateId]='" . $this->model->getCandidateId('0', 'single') . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
                UPDATE CANDIDATE SET
                        CITYID = '" . $this->model->getCityId() . "',
                       STATEID = '" . $this->model->getStateId() . "',
                       COUNTRYID = '" . $this->model->getCountryId() . "',
                       GENDERID = '" . $this->model->getGenderId() . "',
                       MARRIAGEID = '" . $this->model->getMarriageId() . "',
                       RACEID = '" . $this->model->getRaceId() . "',
                       RELIGIONID = '" . $this->model->getReligionId() . "',
                       CANDIDATEFIRSTNAME = '" . $this->model->getCandidateFirstName() . "',
                       CANDIDATELASTNAME = '" . $this->model->getCandidateLastName() . "',
                       CANDIDATEEMAIL = '" . $this->model->getCandidateEmail() . "',
                       CANDIDATEBUSINESSPHONE = '" . $this->model->getCandidateBusinessPhone() . "',
                       CANDIDATEHOMEPHONE = '" . $this->model->getCandidateHomePhone() . "',
                       CANDIDATEMOBILEPHONE = '" . $this->model->getCandidateMobilePhone() . "',
                       CANDIDATEFAXNUMBER = '" . $this->model->getCandidateFaxNumber() . "',
                       CANDIDATEADDRESS = '" . $this->model->getCandidateAddress() . "',
                       CANDIDATEPOSTCODE = '" . $this->model->getCandidatePostCode() . "',
                       CANDIDATEWEBPAGE = '" . $this->model->getCandidateWebPage() . "',
                       CANDIDATEFACEBOOK = '" . $this->model->getCandidateFacebook() . "',
                       CANDIDATETWITTER = '" . $this->model->getCandidateTwitter() . "',
                       CANDIDATELINKEDIN = '" . $this->model->getCandidateLinkedIn() . "',
                       CANDIDATENOTES = '" . $this->model->getCandidateNotes() . "',
                       CANDIDATEPICTURE = '" . $this->model->getCandidatePicture() . "',
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
                WHERE  CANDIDATEID='" . $this->model->getCandidateId('0', 'single') . "'";
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
           FROM 	`candidate`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getCandidateId(
                            0, 'single'
                    ) . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[candidate]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getCandidateId(
                                0, 'single'
                        ) . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	CANDIDATE
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getCandidateId(
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
               UPDATE  `candidate`
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
               WHERE   `candidateId`   =  '" . $this->model->getCandidateId(0, 'single') . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
               UPDATE  [candidate]
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
               WHERE   [candidateId]	=  '" . $this->model->getCandidateId(0, 'single') . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
               UPDATE  CANDIDATE
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
               WHERE   CANDIDATEID	=  '" . $this->model->getCandidateId(0, 'single') . "'";
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
           SELECT  `documentNumber`
           FROM    `candidate`
           WHERE   `candidateCode` 	= 	'" . $this->model->getDocumentNumber() . "'
           AND     `isActive`  =   1
           AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT  [documentNumber]
           FROM    [candidate]
           WHERE   [documentNumber] = 	'" . $this->model->getDocumentNumber() . "'
           AND     [isActive]  =   1
           AND     [companyId] =	'" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
               SELECT  DOCUMENTNUMBER as \"candidateCode\"
               FROM    CANDIDATE
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
     * Return  City
     * @return null|string
     */
    public function getCity() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getCity();
    }

    /**
     * Return  State
     * @return null|string
     */
    public function getState() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getState();
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
                ->setSubject('candidate')
                ->setDescription('Generated by PhpExcel an Idcms Generator')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('humanResource/recruitment');
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
        $this->excel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
        $this->excel->getActiveSheet()->setCellValue('B2', $this->getReportTitle());
        $this->excel->getActiveSheet()->setCellValue('Z2', '');
        $this->excel->getActiveSheet()->mergeCells('B2:Z2');
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.');
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['cityIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['stateIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['countryIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['genderIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['marriageIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['raceIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['religionIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['candidateFirstNameLabel']);
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['candidateLastNameLabel']);
        $this->excel->getActiveSheet()->setCellValue('L3', $this->translate['candidateEmailLabel']);
        $this->excel->getActiveSheet()->setCellValue('M3', $this->translate['candidateBusinessPhoneLabel']);
        $this->excel->getActiveSheet()->setCellValue('N3', $this->translate['candidateHomePhoneLabel']);
        $this->excel->getActiveSheet()->setCellValue('O3', $this->translate['candidateMobilePhoneLabel']);
        $this->excel->getActiveSheet()->setCellValue('P3', $this->translate['candidateFaxNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('Q3', $this->translate['candidateAddressLabel']);
        $this->excel->getActiveSheet()->setCellValue('R3', $this->translate['candidatePostCodeLabel']);
        $this->excel->getActiveSheet()->setCellValue('S3', $this->translate['candidateWebPageLabel']);
        $this->excel->getActiveSheet()->setCellValue('T3', $this->translate['candidateFacebookLabel']);
        $this->excel->getActiveSheet()->setCellValue('U3', $this->translate['candidateTwitterLabel']);
        $this->excel->getActiveSheet()->setCellValue('V3', $this->translate['candidateLinkedInLabel']);
        $this->excel->getActiveSheet()->setCellValue('W3', $this->translate['candidateNotesLabel']);
        $this->excel->getActiveSheet()->setCellValue('X3', $this->translate['candidatePictureLabel']);
        $this->excel->getActiveSheet()->setCellValue('Y3', $this->translate['executeByLabel']);
        $this->excel->getActiveSheet()->setCellValue('Z3', $this->translate['executeTimeLabel']);
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
            $this->excel->getActiveSheet()->setCellValue('F' . $loopRow, strip_tags($row ['genderDescription']));
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, strip_tags($row ['marriageDescription']));
            $this->excel->getActiveSheet()->setCellValue('H' . $loopRow, strip_tags($row ['raceDescription']));
            $this->excel->getActiveSheet()->setCellValue('I' . $loopRow, strip_tags($row ['religionDescription']));
            $this->excel->getActiveSheet()->setCellValue('J' . $loopRow, strip_tags($row ['candidateFirstName']));
            $this->excel->getActiveSheet()->setCellValue('K' . $loopRow, strip_tags($row ['candidateLastName']));
            $this->excel->getActiveSheet()->setCellValue('L' . $loopRow, strip_tags($row ['candidateEmail']));
            $this->excel->getActiveSheet()->setCellValue('M' . $loopRow, strip_tags($row ['candidateBusinessPhone']));
            $this->excel->getActiveSheet()->setCellValue('N' . $loopRow, strip_tags($row ['candidateHomePhone']));
            $this->excel->getActiveSheet()->setCellValue('O' . $loopRow, strip_tags($row ['candidateMobilePhone']));
            $this->excel->getActiveSheet()->setCellValue('P' . $loopRow, strip_tags($row ['candidateFaxNumber']));
            $this->excel->getActiveSheet()->setCellValue('Q' . $loopRow, strip_tags($row ['candidateAddress']));
            $this->excel->getActiveSheet()->setCellValue('R' . $loopRow, strip_tags($row ['candidatePostCode']));
            $this->excel->getActiveSheet()->setCellValue('S' . $loopRow, strip_tags($row ['candidateWebPage']));
            $this->excel->getActiveSheet()->setCellValue('T' . $loopRow, strip_tags($row ['candidateFacebook']));
            $this->excel->getActiveSheet()->setCellValue('U' . $loopRow, strip_tags($row ['candidateTwitter']));
            $this->excel->getActiveSheet()->setCellValue('V' . $loopRow, strip_tags($row ['candidateLinkedIn']));
            $this->excel->getActiveSheet()->setCellValue('W' . $loopRow, strip_tags($row ['candidateNotes']));
            $this->excel->getActiveSheet()->setCellValue('X' . $loopRow, strip_tags($row ['candidatePicture']));
            $this->excel->getActiveSheet()->setCellValue('Y' . $loopRow, strip_tags($row ['staffName']));
            $this->excel->getActiveSheet()->setCellValue('Z' . $loopRow, strip_tags($row ['executeTime']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('Z' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $loopRow++;
            $lastRow = 'Z' . $loopRow;
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
                $filename = "candidate" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/humanResource/recruitment/document/" . $folder . "/" . $filename;
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
                $filename = "candidate" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/humanResource/recruitment/document/" . $folder . "/" . $filename;
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
                $filename = "candidate" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/humanResource/recruitment/document/" . $folder . "/" . $filename;
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
                $filename = "candidate" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/humanResource/recruitment/document/" . $folder . "/" . $filename;
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
        $candidateObject = new CandidateClass ();
        if ($_POST['securityToken'] != $candidateObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value
         */
        if (isset($_POST ['leafId'])) {
            $candidateObject->setLeafId($_POST ['leafId']);
        }
        if (isset($_POST ['offset'])) {
            $candidateObject->setStart($_POST ['offset']);
        }
        if (isset($_POST ['limit'])) {
            $candidateObject->setLimit($_POST ['limit']);
        }
        $candidateObject->setPageOutput($_POST['output']);
        $candidateObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy)
         */
        if ($_POST ['method'] == 'create') {
            $candidateObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $candidateObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $candidateObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $candidateObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$candidateObject->posting();
        }
        if ($_POST ['method'] == 'reverse') {
            //	$candidateObject->delete();
        }
    }
}
if (isset($_GET ['method'])) {
    $candidateObject = new CandidateClass ();
    if ($_GET['securityToken'] != $candidateObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $candidateObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $candidateObject->execute();
    /*
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'updateStatus') {
        $candidateObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key
     */
    if ($_GET['method'] == 'duplicate') {
        $candidateObject->duplicate();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $candidateObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $candidateObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $candidateObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $candidateObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting
     */
    if (isset($_GET ['mode'])) {
        $candidateObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $candidateObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        $candidateObject->setServiceOutput('option');
        if (($_GET['filter'] == 'city')) {
            $candidateObject->getCity();
        }
        if (($_GET['filter'] == 'state')) {
            $candidateObject->getState();
        }
        if (($_GET['filter'] == 'country')) {
            $candidateObject->getCountry();
        }
        if (($_GET['filter'] == 'gender')) {
            $candidateObject->getGender();
        }
        if (($_GET['filter'] == 'marriage')) {
            $candidateObject->getMarriage();
        }
        if (($_GET['filter'] == 'race')) {
            $candidateObject->getRace();
        }
        if (($_GET['filter'] == 'religion')) {
            $candidateObject->getReligion();
        }
    }
}
?>
