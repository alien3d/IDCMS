<?php

namespace Core\HumanResource\Setting\Membership\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\HumanResource\Setting\Membership\Model\MembershipModel;
use Core\HumanResource\Setting\Membership\Service\MembershipService;
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
require_once($newFakeDocumentRoot . "v3/humanResource/setting/model/membershipModel.php");
require_once($newFakeDocumentRoot . "v3/humanResource/setting/service/membershipService.php");

/**
 * Class Membership
 * this is membership controller files.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package  Core\HumanResource\Setting\Membership\Controller
 * @subpackage Setting
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class MembershipClass extends ConfigClass {

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
     * @var \Core\HumanResource\Setting\Membership\Model\MembershipModel
     */
    public $model;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     * @var \PHPPowerPoint
     */
    //private $powerPoint;
    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\HumanResource\Setting\Membership\Service\MembershipService
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
        $this->setViewPath("./v3/humanResource/setting/view/membership.php");
        $this->setControllerPath("./v3/humanResource/setting/controller/membershipController.php");
        $this->setServicePath("./v3/humanResource/setting/service/membershipService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new MembershipModel();
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

        $this->service = new MembershipService();
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
        //$this->model->setDocumentNumber($this->getDocumentNumber);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `membership`
            (
                 `companyId`,
                 `membershipTitle`,
                 `membershipCode`,
                 `membershipDescription`,
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
                 '" . $this->model->getMembershipTitle() . "',
                 '" . $this->model->getMembershipCode() . "',
                 '" . $this->model->getMembershipDescription() . "',
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
            INSERT INTO [membership]
            (
                 [membershipId],
                 [companyId],
                 [membershipTitle],
                 [membershipCode],
                 [membershipDescription],
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
                 '" . $this->model->getMembershipTitle() . "',
                 '" . $this->model->getMembershipCode() . "',
                 '" . $this->model->getMembershipDescription() . "',
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
            INSERT INTO MEMBERSHIP
            (
                 COMPANYID,
                 MEMBERSHIPTITLE,
                 MEMBERSHIPCODE,
                 MEMBERSHIPDESCRIPTION,
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
                 '" . $this->model->getMembershipTitle() . "',
                 '" . $this->model->getMembershipCode() . "',
                 '" . $this->model->getMembershipDescription() . "',
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
        $membershipId = $this->q->lastInsertId();
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
                    "membershipId" => $membershipId,
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
         FROM    `membership`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT    COUNT(*) AS total
         FROM      [membership]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT    COUNT(*)    AS  \"total\"
         FROM      MEMBERSHIP
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
                            " `membership`.`isActive` = 1  AND `membership`.`companyId`='" . $this->getCompanyId() . "' "
                    );
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [membership].[isActive] = 1 AND [membership].[companyId]='" . $this->getCompanyId() . "' "
                        );
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(
                                    " MEMBERSHIP.ISACTIVE = 1  AND MEMBERSHIP.COMPANYID='" . $this->getCompanyId() . "'"
                            );
                        }
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter("   `membership`.`companyId`='" . $this->getCompanyId() . "'	");
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(" [membership].[companyId]='" . $this->getCompanyId() . "' ");
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(" MEMBERSHIP.COMPANYID='" . $this->getCompanyId() . "' ");
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
       SELECT                    `membership`.`membershipId`,
                    `company`.`companyDescription`,
                    `membership`.`companyId`,
                    `membership`.`membershipTitle`,
                    `membership`.`membershipCode`,
                    `membership`.`membershipDescription`,
                    `membership`.`isDefault`,
                    `membership`.`isNew`,
                    `membership`.`isDraft`,
                    `membership`.`isUpdate`,
                    `membership`.`isDelete`,
                    `membership`.`isActive`,
                    `membership`.`isApproved`,
                    `membership`.`isReview`,
                    `membership`.`isPost`,
                    `membership`.`executeBy`,
                    `membership`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `membership`
		  JOIN      `staff`
		  ON        `membership`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `membership`.`companyId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getMembershipId(0, 'single')) {
                $sql .= " AND `membership`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getMembershipId(0, 'single') . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
		  SELECT                    [membership].[membershipId],
                    [company].[companyDescription],
                    [membership].[companyId],
                    [membership].[membershipTitle],
                    [membership].[membershipCode],
                    [membership].[membershipDescription],
                    [membership].[isDefault],
                    [membership].[isNew],
                    [membership].[isDraft],
                    [membership].[isUpdate],
                    [membership].[isDelete],
                    [membership].[isActive],
                    [membership].[isApproved],
                    [membership].[isReview],
                    [membership].[isPost],
                    [membership].[executeBy],
                    [membership].[executeTime],
                    [staff].[staffName]
		  FROM 	[membership]
		  JOIN	[staff]
		  ON	[membership].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [membership].[companyId]
		  WHERE     " . $this->getAuditFilter();
                if ($this->model->getMembershipId(0, 'single')) {
                    $sql .= " AND [membership].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getMembershipId(0, 'single') . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
		  SELECT                    MEMBERSHIP.MEMBERSHIPID AS \"membershipId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    MEMBERSHIP.COMPANYID AS \"companyId\",
                    MEMBERSHIP.MEMBERSHIPTITLE AS \"membershipTitle\",
                    MEMBERSHIP.MEMBERSHIPCODE AS \"membershipCode\",
                    MEMBERSHIP.MEMBERSHIPDESCRIPTION AS \"membershipDescription\",
                    MEMBERSHIP.ISDEFAULT AS \"isDefault\",
                    MEMBERSHIP.ISNEW AS \"isNew\",
                    MEMBERSHIP.ISDRAFT AS \"isDraft\",
                    MEMBERSHIP.ISUPDATE AS \"isUpdate\",
                    MEMBERSHIP.ISDELETE AS \"isDelete\",
                    MEMBERSHIP.ISACTIVE AS \"isActive\",
                    MEMBERSHIP.ISAPPROVED AS \"isApproved\",
                    MEMBERSHIP.ISREVIEW AS \"isReview\",
                    MEMBERSHIP.ISPOST AS \"isPost\",
                    MEMBERSHIP.EXECUTEBY AS \"executeBy\",
                    MEMBERSHIP.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\"
		  FROM 	MEMBERSHIP
		  JOIN	STAFF
		  ON	MEMBERSHIP.EXECUTEBY = STAFF.STAFFID
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = MEMBERSHIP.COMPANYID
         WHERE     " . $this->getAuditFilter();
                    if ($this->model->getMembershipId(0, 'single')) {
                        $sql .= " AND MEMBERSHIP. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getMembershipId(0, 'single') . "'";
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
                $sql .= " AND `membership`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [membership].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND Initcap(MEMBERSHIP." . strtoupper(
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
                        'membership', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'membership', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'MEMBERSHIP', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
                "`membership`.`membershipId`",
                "`staff`.`staffPassword`"
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $filterArray = array(
                    "[membership].[membershipId]",
                    "[staff].[staffPassword]"
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $filterArray = array(
                        "MEMBERSHIP.MEMBERSHIPID",
                        "STAFF.STAFFPASSWORD"
                    );
                }
            }
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('staff', 'membership');
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array('staff', 'membership');
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array('STAFF', 'MEMBERSHIP');
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
        if (!($this->model->getMembershipId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 16;
            if ($this->model->getMembershipId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getMembershipId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getMembershipId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else {
            if ($this->getPageOutput() == 'json') {
                if ($this->model->getMembershipId(0, 'single')) {
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
                                                'value', $this->model->getMembershipId(0, 'single')
                                        ),
                                        'nextRecord' => $this->nextRecord('value', $this->model->getMembershipId(0, 'single')),
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
                                        'value', $this->model->getMembershipId(0, 'single')
                                ),
                                'nextRecord' => $this->recordSet->nextRecord(
                                        'value', $this->model->getMembershipId(0, 'single')
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
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`membership`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getMembershipId(
                            0, 'single'
                    ) . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[membership]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getMembershipId(
                                0, 'single'
                        ) . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	MEMBERSHIP
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getMembershipId(
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
               UPDATE `membership` SET
                       `membershipTitle` = '" . $this->model->getMembershipTitle() . "',
                       `membershipCode` = '" . $this->model->getMembershipCode() . "',
                       `membershipDescription` = '" . $this->model->getMembershipDescription() . "',
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
               WHERE    `membershipId`='" . $this->model->getMembershipId('0', 'single') . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                UPDATE [membership] SET
                       [membershipTitle] = '" . $this->model->getMembershipTitle() . "',
                       [membershipCode] = '" . $this->model->getMembershipCode() . "',
                       [membershipDescription] = '" . $this->model->getMembershipDescription() . "',
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
                WHERE   [membershipId]='" . $this->model->getMembershipId('0', 'single') . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
                UPDATE MEMBERSHIP SET
                        MEMBERSHIPTITLE = '" . $this->model->getMembershipTitle() . "',
                       MEMBERSHIPCODE = '" . $this->model->getMembershipCode() . "',
                       MEMBERSHIPDESCRIPTION = '" . $this->model->getMembershipDescription() . "',
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
                WHERE  MEMBERSHIPID='" . $this->model->getMembershipId('0', 'single') . "'";
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
           FROM 	`membership`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getMembershipId(
                            0, 'single'
                    ) . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[membership]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getMembershipId(
                                0, 'single'
                        ) . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	MEMBERSHIP
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getMembershipId(
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
               UPDATE  `membership`
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
               WHERE   `membershipId`   =  '" . $this->model->getMembershipId(0, 'single') . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
               UPDATE  [membership]
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
               WHERE   [membershipId]	=  '" . $this->model->getMembershipId(0, 'single') . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
               UPDATE  MEMBERSHIP
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
               WHERE   MEMBERSHIPID	=  '" . $this->model->getMembershipId(0, 'single') . "'";
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
           SELECT  `membershipCode`
           FROM    `membership`
           WHERE   `membershipCode` 	= 	'" . $this->model->getMembershipCode() . "'
           AND     `isActive`  =   1
           AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT  [membershipCode]
           FROM    [membership]
           WHERE   [membershipCode] = 	'" . $this->model->getMembershipCode() . "'
           AND     [isActive]  =   1
           AND     [companyId] =	'" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
               SELECT  MEMBERSHIPCODE as \"membershipCode\"
               FROM    MEMBERSHIP
               WHERE   MEMBERSHIPCODE	= 	'" . $this->model->getMembershipCode() . "'
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
                ->setSubject('membership')
                ->setDescription('Generated by PhpExcel an Idcms Generator')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('humanResource/setting');
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
        $this->excel->getActiveSheet()->setCellValue('B2', $this->getReportTitle());
        $this->excel->getActiveSheet()->setCellValue('G2', '');
        $this->excel->getActiveSheet()->mergeCells('B2:G2');
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.');
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['membershipTitleLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['membershipCodeLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['membershipDescriptionLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['executeByLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['executeTimeLabel']);
        //
        $loopRow = 4;
        $i = 0;
        \PHPExcel_Cell::setValueBinder(new \PHPExcel_Cell_AdvancedValueBinder());
        $lastRow = null;
        while (($row = $this->q->fetchAssoc()) == true) {
            //	echo print_r($row);
            $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i);
            $this->excel->getActiveSheet()->setCellValue('C' . $loopRow, strip_tags($row ['membershipTitle']));
            $this->excel->getActiveSheet()->setCellValue('D' . $loopRow, strip_tags($row ['membershipCode']));
            $this->excel->getActiveSheet()->setCellValue('E' . $loopRow, strip_tags($row ['membershipDescription']));
            $this->excel->getActiveSheet()->setCellValue('F' . $loopRow, strip_tags($row ['staffName']));
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, strip_tags($row ['executeTime']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('G' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $loopRow++;
            $lastRow = 'G' . $loopRow;
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
                $filename = "membership" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/humanResource/setting/document/" . $folder . "/" . $filename;
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
                $filename = "membership" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/humanResource/setting/document/" . $folder . "/" . $filename;
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
                $filename = "membership" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/humanResource/setting/document/" . $folder . "/" . $filename;
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
                $filename = "membership" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/humanResource/setting/document/" . $folder . "/" . $filename;
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
        $membershipObject = new MembershipClass ();
        if ($_POST['securityToken'] != $membershipObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value
         */
        if (isset($_POST ['leafId'])) {
            $membershipObject->setLeafId($_POST ['leafId']);
        }
        $membershipObject->setPageOutput($_POST['output']);
        $membershipObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy)
         */
        if ($_POST ['method'] == 'create') {
            $membershipObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $membershipObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $membershipObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $membershipObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$membershipObject->posting();
        }
        if ($_POST ['method'] == 'reverse') {
            //	$membershipObject->delete();
        }
    }
}
if (isset($_GET ['method'])) {
    $membershipObject = new MembershipClass ();
    if ($_GET['securityToken'] != $membershipObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $membershipObject->setLeafId($_GET ['leafId']);
    }
    /*
     * Admin Only
     */
    if (isset($_GET ['isAdmin'])) {
        $membershipObject->setIsAdmin($_GET ['isAdmin']);
    }
    /**
     * Database Request
     */
    if (isset($_GET ['databaseRequest'])) {
        $membershipObject->setRequestDatabase($_GET ['databaseRequest']);
    }
    /*
     *  Load the dynamic value
     */
    $membershipObject->execute();
    /*
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'updateStatus') {
        $membershipObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key
     */
    if ($_GET['method'] == 'duplicate') {
        $membershipObject->duplicate();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $membershipObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $membershipObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $membershipObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $membershipObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting
     */
    if (isset($_GET ['mode'])) {
        $membershipObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $membershipObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        $membershipObject->setServiceOutput('option');
    }
}
?>
