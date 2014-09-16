<?php

namespace Core\System\Security\SystemSetting\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\RecordSet\RecordSet;
use Core\shared\SharedClass;
use Core\System\Security\SystemSetting\Model\SystemSettingModel;
use Core\System\Security\SystemSetting\Service\SystemSettingService;

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
require_once($newFakeDocumentRoot . "v3/system/security/model/systemSettingModel.php");
require_once($newFakeDocumentRoot . "v3/system/security/service/systemSettingService.php");

/**
 * Class SystemSettingClass
 * this is systemSetting setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Security\SystemSetting\Controller
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class SystemSettingClass extends ConfigClass {

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
     * Php Excel Generate Microsoft Excel 2007 Output.Format : xlsx/pdf
     * @var \PHPExcel
     */
    private $excel;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     * @var \PHPPowerPoint
     */
    //private $powerPoint; 
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
     * Model
     * @var \Core\System\Security\SystemSetting\Model\SystemSettingModel
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\System\Security\SystemSetting\Service\SystemSettingService
     */
    public $service;

    /**
     * Translation Array
     * @var mixed
     */
    public $translate;

    /**
     * Translation Label
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
     * @var \Core\shared\SharedClass
     */
    public $systemFormat;

    /**
     * System Format Array
     * @var string
     */
    public $systemFormatArray;

    /**
     * Constructor
     */
    function __construct() {
        $this->translate = array();
        $this->t = array();
        $this->leafAccess = array();
        $this->setViewPath("./v3/system/security/view/systemSetting.php");
        $this->setControllerPath("./v3/system/security/controller/systemSettingController.php");
        $this->setServicePath("./v3/system/security/service/systemSettingService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(0);
        $this->setLog(1);
        $this->model = new SystemSettingModel();
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
        $this->q->setCurrentDatabase($this->q->getCoreDatabase());
        // $this->q->setApplicationId($this->getApplicationId()); 
        // $this->q->setModuleId($this->getModuleId()); 
        // $this->q->setFolderId($this->getFolderId()); 
        $this->q->setLeafId($this->getLeafId());
        $this->q->connect($this->getConnection(), $this->getUsername(), $this->getDatabase(), $this->getPassword());

        $this->service = new SystemSettingService();
        $this->service->q = $this->q;
        $this->service->setVendor($this->getVendor());
        $this->service->execute();

        $translator = new SharedClass();
        $translator->setCurrentDatabase($this->q->getCoreDatabase());
        $translator->setCurrentTable($this->model->getTableName());
        $translator->execute();

        $this->translate = $translator->getLeafTranslation(); // short because code too long  
        $translator->setLeafId($this->getLeafId());

        $this->translate = $translator->getLeafTranslation(); // short because code too long
        $this->t = $translator->getDefaultTranslation(); // short because code too long
        $this->leafAccess = $translator->getLeafAccess();
        $arrayInfo = $translator->getFileInfo('moduleTranslate.php');

        $applicationNative = $arrayInfo['applicationNative'];
        $folderNative = $arrayInfo['folderNative'];
        $moduleNative = $arrayInfo['moduleNative'];
        $leafNative = $arrayInfo['leafNative'];
        $this->setReportTitle(
                $applicationNative . " :: " . $moduleNative . " :: " . $folderNative . " :: " . $leafNative
        );
        $this->recordSet = new RecordSet();
        $this->recordSet->q = $this->q;
        $this->recordSet->setCurrentDatabase($this->q->getCoreDatabase());
        $this->recordSet->setCurrentTable($this->model->getTableName());
        $this->recordSet->setPrimaryKeyName($this->model->getPrimaryKeyName());
        $this->recordSet->execute();

        $this->documentTrail = new DocumentTrailClass();
        $this->documentTrail->q = $this->q;
        $this->documentTrail->setVendor($this->getVendor());
        $this->documentTrail->setStaffId($this->getStaffId());
        $this->documentTrail->setLanguageId($this->getLanguageId());

        $this->systemFormat = new SharedClass();
        $this->systemFormat->q = $this->q;
        $this->systemFormat->setCurrentDatabase($this->q->getCoreDatabase());
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
            INSERT INTO `systemsetting` 
            (
                 `systemSettingDateFormat`,
                 `systemSettingTimeFormat`,
                 `systemSettingWeekStart`,
                 `systemSettingNumberFormat`,
                 `systemSettingDecimalSeparator`,
                 `systemSettingDecimalThousandsSeparator`,
                 `countryId`,
                 `systemSettingCurrencyFormat`,
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
                 '" . $this->model->getSystemSettingDateFormat() . "',
                 '" . $this->model->getSystemSettingTimeFormat() . "',
                 '" . $this->model->getSystemSettingWeekStart() . "',
                 '" . $this->model->getSystemSettingNumberFormat() . "',
                 '" . $this->model->getSystemSettingDecimalSeparator() . "',
                 '" . $this->model->getSystemSettingDecimalThousandsSeparator() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getSystemSettingCurrencyFormat() . "',
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
            INSERT INTO [systemSetting]
            (
                 [systemSettingId],
                 [systemSettingDateFormat],
                 [systemSettingTimeFormat],
                 [systemSettingWeekStart],
                 [systemSettingNumberFormat],
                 [systemSettingDecimalSeparator],
                 [systemSettingDecimalThousandsSeparator],
                 [countryId],
                 [systemSettingCurrencyFormat],
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
                 '" . $this->model->getSystemSettingDateFormat() . "',
                 '" . $this->model->getSystemSettingTimeFormat() . "',
                 '" . $this->model->getSystemSettingWeekStart() . "',
                 '" . $this->model->getSystemSettingNumberFormat() . "',
                 '" . $this->model->getSystemSettingDecimalSeparator() . "',
                 '" . $this->model->getSystemSettingDecimalThousandsSeparator() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getSystemSettingCurrencyFormat() . "',
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
            INSERT INTO SYSTEMSETTING
            (
                 SYSTEMSETTINGDATEFORMAT,
                 SYSTEMSETTINGTIMEFORMAT,
                 SYSTEMSETTINGWEEKSTART,
                 SYSTEMSETTINGNUMBERFORMAT,
                 SYSTEMSETTINGDecimalSEPARATOR,
                 SYSTEMDecimalTHOUSANDSSEPARATOR,
                 COUNTRYID,
                 SYSTEMSETTINGCURRENCYFORMAT,
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
                 '" . $this->model->getSystemSettingDateFormat() . "',
                 '" . $this->model->getSystemSettingTimeFormat() . "',
                 '" . $this->model->getSystemSettingWeekStart() . "',
                 '" . $this->model->getSystemSettingNumberFormat() . "',
                 '" . $this->model->getSystemSettingDecimalSeparator() . "',
                 '" . $this->model->getSystemSettingDecimalThousandsSeparator() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getSystemSettingCurrencyFormat() . "',
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
        $systemStringId = $this->q->lastInsertId();

        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => true,
                    "message" => $this->t['newRecordTextLabel'],
                    "systemSettingId" => $systemStringId,
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
                    $this->setAuditFilter(" `systemsetting`.`isActive` = 1 ");
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(" [systemSetting].[isActive] = 1 ");
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(" SYSTEMSETTING.ISACTIVE = 1 ");
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
		  SELECT                    `systemsetting`.`systemSettingId`,
                    `systemsetting`.`systemSettingDateFormat`,
                    `systemsetting`.`systemSettingTimeFormat`,
                    `systemsetting`.`systemSettingWeekStart`,
                    `systemsetting`.`systemSettingNumberFormat`,
                    `systemsetting`.`systemSettingDecimalSeparator`,
                    `systemsetting`.`systemSettingDecimalThousandsSeparator`,
                    `country`.`countryDesc`,
                    `systemsetting`.`systemSettingCurrencyFormat`,
                    `systemsetting`.`isDefault`,
                    `systemsetting`.`isNew`,
                    `systemsetting`.`isDraft`,
                    `systemsetting`.`isUpdate`,
                    `systemsetting`.`isDelete`,
                    `systemsetting`.`isActive`,
                    `systemsetting`.`isApproved`,
                    `systemsetting`.`isReview`,
                    `systemsetting`.`isPost`,
                    `systemsetting`.`executeBy`,
                    `systemsetting`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `systemsetting`
		  JOIN      `staff`
		  ON        `systemsetting`.`executeBy` = `staff`.`staffId`
	JOIN	`country`
	ON		`country`.`countryId` = `systemsetting`.`countryId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getSystemSettingId(0, 'single')) {
                $sql .= " AND `systemsetting`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getSystemSettingId(0, 'single') . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
		  SELECT                    [systemSetting].[systemSettingId],
                    [systemSetting].[systemSettingDateFormat],
                    [systemSetting].[systemSettingTimeFormat],
                    [systemSetting].[systemSettingWeekStart],
                    [systemSetting].[systemSettingNumberFormat],
                    [systemSetting].[systemSettingDecimalSeparator],
                    [systemSetting].[systemSettingDecimalThousandsSeparator],
                    [country].[countryDesc],
                    [systemSetting].[systemSettingCurrencyFormat],
                    [systemSetting].[isDefault],
                    [systemSetting].[isNew],
                    [systemSetting].[isDraft],
                    [systemSetting].[isUpdate],
                    [systemSetting].[isDelete],
                    [systemSetting].[isActive],
                    [systemSetting].[isApproved],
                    [systemSetting].[isReview],
                    [systemSetting].[isPost],
                    [systemSetting].[executeBy],
                    [systemSetting].[executeTime],
                    [staff].[staffName]
		  FROM 	[systemSetting]
		  JOIN	[staff]
		  ON	[systemSetting].[executeBy] = [staff].[staffId]
	JOIN	[country]
	ON		[country].[countryId] = [systemSetting].[countryId]
		  WHERE     " . $this->getAuditFilter();
                if ($this->model->getSystemSettingId(0, 'single')) {
                    $sql .= " AND [systemSetting].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getSystemSettingId(0, 'single') . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
		  SELECT                    SYSTEMSETTING.SYSTEMSTRINGID,
                    SYSTEMSETTING.SYSTEMSETTINGDATEFORMAT,
                    SYSTEMSETTING.SYSTEMSETTINGTIMEFORMAT,
                    SYSTEMSETTING.SYSTEMSETTINGWEEKSTART,
                    SYSTEMSETTING.SYSTEMSETTINGNUMBERFORMAT,
                    SYSTEMSETTING.SYSTEMSETTINGDecimalSEPARATOR,
                    SYSTEMSETTING.SYSTEMDecimalTHOUSANDSSEPARATOR,
                    COUNTRY.COUNTRYID,
                    SYSTEMSETTING.SYSTEMSETTINGCURRENCYFORMAT,
                    SYSTEMSETTING.ISDEFAULT,
                    SYSTEMSETTING.ISNEW,
                    SYSTEMSETTING.ISDRAFT,
                    SYSTEMSETTING.ISUPDATE,
                    SYSTEMSETTING.ISDELETE,
                    SYSTEMSETTING.ISACTIVE,
                    SYSTEMSETTING.ISAPPROVED,
                    SYSTEMSETTING.ISREVIEW,
                    SYSTEMSETTING.ISPOST,
                    SYSTEMSETTING.EXECUTEBY,
                    SYSTEMSETTING.EXECUTETIME,
                    STAFF.STAFFNAME
		  FROM 	SYSTEMSETTING
		  JOIN	STAFF
		  ON	SYSTEMSETTING.EXECUTEBY = STAFF.STAFFID
 	JOIN	COUNTRY
	ON		COUNTRY.COUNTRYID = SYSTEMSETTING.COUNTRYID
         WHERE     " . $this->getAuditFilter();
                    if ($this->model->getSystemSettingId(0, 'single')) {
                        $sql .= " AND SYSTEMSETTING. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getSystemSettingId(0, 'single') . "'";
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
                $sql .= " AND `systemsetting`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [systemSetting].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND SYSTEMSETTING" . strtoupper(
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
                        'systemsetting', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'systemsetting', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'SYSTEMSETTING', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
        $filterArray = array('systemSettingId');
        /**
         * filter table
         * @variables $tableArray
         */
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('systemsetting');
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array('systemsetting');
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array('SYSTEMSETTING');
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

                    $sql = "WITH [systemsettingDerived] AS
							(
								SELECT 										[systemSetting].[systemSettingId],
										[systemSetting].[systemSettingDateFormat],
										[systemSetting].[systemSettingTimeFormat],
										[systemSetting].[systemSettingWeekStart],
										[systemSetting].[systemSettingNumberFormat],
										[systemSetting].[systemSettingDecimalSeparator],
										[systemSetting].[systemSettingDecimalThousandsSeparator],
										[country].[countryDesc],
										[systemSetting].[systemSettingCurrencyFormat],
										[systemSetting].[isDefault],
										[systemSetting].[isNew],
										[systemSetting].[isDraft],
										[systemSetting].[isUpdate],
										[systemSetting].[isDelete],
										[systemSetting].[isActive],
										[systemSetting].[isApproved],
										[systemSetting].[isReview],
										[systemSetting].[isPost],
										[systemSetting].[executeBy],
										[systemSetting].[executeTime],
										[staff].[staffName],
										ROW_NUMBER() OVER (ORDER BY [systemSetting].[systemSettingId]) AS 'RowNumber'
							     FROM 	[systemSetting]
							     JOIN	[staff]
							     ON	[systemSetting].[executeBy] = [staff].[staffId]
							     JOIN	[country]
							     OON		[country].[countryId] = [systemSetting].[countryId]
							     WHERE 		" . $this->getAuditFilter() . "

							)
							SELECT		*
							FROM 		[systemsettingDerived]
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
SELECT							     SYSTEMSETTING.SYSTEMSTRINGID,
							     SYSTEMSETTING.SYSTEMSETTINGDATEFORMAT,
							     SYSTEMSETTING.SYSTEMSETTINGTIMEFORMAT,
							     SYSTEMSETTING.SYSTEMSETTINGWEEKSTART,
							     SYSTEMSETTING.SYSTEMSETTINGNUMBERFORMAT,
							     SYSTEMSETTING.SYSTEMSETTINGDecimalSEPARATOR,
							     SYSTEMSETTING.SYSTEMDecimalTHOUSANDSSEPARATOR,
							     COUNTRY.COUNTRYID,
							     SYSTEMSETTING.SYSTEMSETTINGCURRENCYFORMAT,
							     SYSTEMSETTING.ISDEFAULT,
							     SYSTEMSETTING.ISNEW,
							     SYSTEMSETTING.ISDRAFT,
							     SYSTEMSETTING.ISUPDATE,
							     SYSTEMSETTING.ISDELETE,
							     SYSTEMSETTING.ISACTIVE,
							     SYSTEMSETTING.ISAPPROVED,
							     SYSTEMSETTING.ISREVIEW,
							     SYSTEMSETTING.ISPOST,
							     SYSTEMSETTING.EXECUTEBY,
							     SYSTEMSETTING.EXECUTETIME,
                                   STAFF.STAFFNAME
							     FROM 	SYSTEMSETTING
							     JOIN	  STAFF
							     ON		SYSTEMSETTING.EXECUTEBY = STAFF.STAFFID
							     JOIN	COUNTRY
								     ON		COUNTRY.COUNTRYID = SYSTEMSETTING.COUNTRYID
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
        if (!($this->model->getSystemSettingId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 20;
            if ($this->model->getSystemSettingId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getSystemSettingId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getSystemSettingId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else {
            if ($this->getPageOutput() == 'json') {
                if ($this->model->getSystemSettingId(0, 'single')) {
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
                                                'value', $this->model->getSystemSettingId(0, 'single')
                                        ),
                                        'nextRecord' => $this->nextRecord(
                                                'value', $this->model->getSystemSettingId(0, 'single')
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
                                        'value', $this->model->getSystemSettingId(0, 'single')
                                ),
                                'nextRecord' => $this->recordSet->nextRecord(
                                        'value', $this->model->getSystemSettingId(0, 'single')
                                ),
                                'lastRecord' => $this->recordSet->lastRecord('value'),
                                'data' => $items
                            )
                    );
                    exit();
                }
            }
        }
        //fake return
        return $items;
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
		FROM 	`systemsetting` 
		WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getSystemSettingId(
                            0, 'single'
                    ) . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
			SELECT	[" . $this->model->getPrimaryKeyName() . "]
			FROM 	[systemSetting]
			WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getSystemSettingId(
                                0, 'single'
                        ) . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
			SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
			FROM 	SYSTEMSETTING
			WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getSystemSettingId(
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
				UPDATE `systemsetting` SET 
				       `systemSettingDateFormat` = '" . $this->model->getSystemSettingDateFormat() . "',
				       `systemSettingTimeFormat` = '" . $this->model->getSystemSettingTimeFormat() . "',
				       `systemSettingWeekStart` = '" . $this->model->getSystemSettingWeekStart() . "',
				       `systemSettingNumberFormat` = '" . $this->model->getSystemSettingNumberFormat() . "',
				       `systemSettingDecimalSeparator` = '" . $this->model->getSystemSettingDecimalSeparator() . "',
				       `systemSettingDecimalThousandsSeparator` = '" . $this->model->getSystemSettingDecimalThousandsSeparator(
                        ) . "',
				       `countryId` = '" . $this->model->getCountryId() . "',
				       `systemSettingCurrencyFormat` = '" . $this->model->getSystemSettingCurrencyFormat() . "',
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
                WHERE  `systemSettingId`='" . $this->model->getSystemSettingId('0', 'single') . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
				UPDATE [systemSetting] SET
				       [systemSettingDateFormat] = '" . $this->model->getSystemSettingDateFormat() . "',
				       [systemSettingTimeFormat] = '" . $this->model->getSystemSettingTimeFormat() . "',
				       [systemSettingWeekStart] = '" . $this->model->getSystemSettingWeekStart() . "',
				       [systemSettingNumberFormat] = '" . $this->model->getSystemSettingNumberFormat() . "',
				       [systemSettingDecimalSeparator] = '" . $this->model->getSystemSettingDecimalSeparator() . "',
				       [systemSettingDecimalThousandsSeparator] = '" . $this->model->getSystemSettingDecimalThousandsSeparator(
                            ) . "',
				       [countryId] = '" . $this->model->getCountryId() . "',
				       [systemSettingCurrencyFormat] = '" . $this->model->getSystemSettingCurrencyFormat() . "',
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
                WHERE [systemSettingId]='" . $this->model->getSystemSettingId('0', 'single') . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
				UPDATE SYSTEMSETTING SET
				       SYSTEMSETTINGTIMEFORMAT = '" . $this->model->getSystemSettingTimeFormat() . "',
				       SYSTEMSETTINGWEEKSTART = '" . $this->model->getSystemSettingWeekStart() . "',
				       SYSTEMSETTINGNUMBERFORMAT = '" . $this->model->getSystemSettingNumberFormat() . "',
				       SYSTEMSETTINGDecimalSEPARATOR = '" . $this->model->getSystemSettingDecimalSeparator() . "',
				       SYSTEMDecimalTHOUSANDSSEPARATOR = '" . $this->model->getSystemSettingDecimalThousandsSeparator(
                                ) . "',
				       COUNTRYID = '" . $this->model->getCountryId() . "',
				       SYSTEMSETTINGCURRENCYFORMAT = '" . $this->model->getSystemSettingCurrencyFormat() . "',
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
                WHERE `SYSTEMSTRINGID`='" . $this->model->getSystemSettingId('0', 'single') . "'";
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
		FROM 	`systemsetting` 
		WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getSystemSettingId(
                            0, 'single'
                    ) . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
			SELECT	[" . $this->model->getPrimaryKeyName() . "]
			FROM 	[systemSetting]
			WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getSystemSettingId(
                                0, 'single'
                        ) . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
		SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
		FROM 	SYSTEMSETTING
		WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getSystemSettingId(
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
                $sql = "  	UPDATE 	`systemsetting`

					SET 	`isDefault`				=	'" . $this->model->getIsDefault(0, 'single') . "',
							`isNew`					=	'" . $this->model->getIsNew(0, 'single') . "',
							`isDraft`				=	'" . $this->model->getIsDraft(0, 'single') . "',
							`isUpdate`				=	'" . $this->model->getIsUpdate(0, 'single') . "',
							`isDelete`				=	'" . $this->model->getIsDelete(0, 'single') . "',
							`isActive`				=	'" . $this->model->getIsActive(0, 'single') . "',
							`isApproved`			=	'" . $this->model->getIsApproved(0, 'single') . "',
							`isReview`				=	'" . $this->model->getIsReview(0, 'single') . "',
							`isPost`				=	'" . $this->model->getIsPost(0, 'single') . "',
							`executeBy`				=	'" . $this->model->getExecuteBy() . "',
							`executeTime`			=	" . $this->model->getExecuteTime() . "
				WHERE 	`systemSettingId`	=  '" . $this->model->getSystemSettingId(0, 'single') . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {

                    $sql = "
				UPDATE 	[systemSetting]
				SET 			[isDefault]			=	'" . $this->model->getIsDefault(0, 'single') . "',
								[isNew]				=	'" . $this->model->getIsNew(0, 'single') . "',
								[isDraft]			=	'" . $this->model->getIsDraft(0, 'single') . "',
								[isUpdate]			=	'" . $this->model->getIsUpdate(0, 'single') . "',
								[isDelete]			=	'" . $this->model->getIsDelete(0, 'single') . "',
								[isActive]			=	'" . $this->model->getIsActive(0, 'single') . "',
								[isApproved]		=	'" . $this->model->getIsApproved(0, 'single') . "',
								[isReview]			=	'" . $this->model->getIsReview(0, 'single') . "',
								[isPost]			=	'" . $this->model->getIsPost(0, 'single') . "',
								[executeBy]			=	'" . $this->model->getExecuteBy() . "',
								[executeTime]		=	" . $this->model->getExecuteTime() . "
				WHERE 		[systemSettingId]	=  '" . $this->model->getSystemSettingId(0, 'single') . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {

                        $sql = "
				UPDATE 	SYSTEMSETTING
				SET 	ISDEFAULT		=	'" . $this->model->getIsDefault(0, 'single') . "',
						ISNEW			=	'" . $this->model->getIsNew(0, 'single') . "',
						ISDRAFT			=	'" . $this->model->getIsDraft(0, 'single') . "',
						ISUPDATE		=	'" . $this->model->getIsUpdate(0, 'single') . "',
						ISDELETE		=	'" . $this->model->getIsDelete(0, 'single') . "',
						ISACTIVE		=	'" . $this->model->getIsActive(0, 'single') . "',
						ISAPPROVED		=	'" . $this->model->getIsApproved(0, 'single') . "',
						ISREVIEW		=	'" . $this->model->getIsReview(0, 'single') . "',
						ISPOST			=	'" . $this->model->getIsPost(0, 'single') . "',
						EXECUTEBY		=	'" . $this->model->getExecuteBy() . "',
						EXECUTETIME		=	" . $this->model->getExecuteTime() . "
				WHERE 	SYSTEMSTRINGID	=  '" . $this->model->getSystemSettingId(0, 'single') . "'";
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
     * First Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @return int
     */
    function firstRecord($value) {
        return $this->recordSet->firstRecord($value);
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
     * Previous Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @param int $primaryKeyValue
     * @return int
     */
    function previousRecord($value, $primaryKeyValue) {
        return $this->recordSet->previousRecord($value, $primaryKeyValue);
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
     * Return Country
     * @return array|string
     */
    public function getCountry() {
        return $this->service->getCountry();
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
                ->setSubject('systemsetting')
                ->setDescription('Generated by PhpExcel an Idcms Generator')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('system/security');
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
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['systemSettingDateFormatLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['systemSettingTimeFormatLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['systemSettingWeekStartLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['systemSettingNumberFormatLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['systemSettingDecimalSeparatorLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['systemDecimalThousandsSeparatorLabel']);
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['countryIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['systemSettingCurrencyFormatLabel']);
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
            $this->excel->getActiveSheet()
                    ->setCellValue('C' . $loopRow, $row ['systemSettingDateFormat']);
            $this->excel->getActiveSheet()
                    ->setCellValue('D' . $loopRow, $row ['systemSettingTimeFormat']);
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('E' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('E' . $loopRow, $row ['systemSettingWeekStart']);
            $this->excel->getActiveSheet()
                    ->setCellValue('F' . $loopRow, $row ['systemSettingNumberFormat']);
            $this->excel->getActiveSheet()
                    ->setCellValue('G' . $loopRow, $row ['systemSettingDecimalSeparator']);
            $this->excel->getActiveSheet()
                    ->setCellValue('H' . $loopRow, $row ['systemSettingDecimalThousandsSeparator']);
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('I' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('I' . $loopRow, $row ['countryId']);
            $this->excel->getActiveSheet()
                    ->setCellValue('J' . $loopRow, $row ['systemSettingCurrencyFormat']);
            $this->excel->getActiveSheet()
                    ->setCellValue('K' . $loopRow, $row ['staffName']);
            $this->excel->getActiveSheet()
                    ->setCellValue('L' . $loopRow, $row ['executeTime']);
            $this->excel->getActiveSheet()
                    ->getStyle()
                    ->getNumberFormat('L' . $loopRow)
                    ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
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
        $filename = "systemsetting" . rand(0, 10000000) . $extension;
        $path = $this->getFakeDocumentRoot() . "v3/system/security/document/" . $folder . "/" . $filename;
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

/**
 * crud -create,read,update,delete
 * */
if (isset($_POST ['method'])) {
    if (isset($_POST['output'])) {
        $systemSettingObject = new SystemSettingClass ();
        if ($_POST['securityToken'] != $systemSettingObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $systemSettingObject->setLeafId($_POST ['leafId']);
        }
        $systemSettingObject->setPageOutput($_POST['output']);
        $systemSettingObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $systemSettingObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $systemSettingObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $systemSettingObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $systemSettingObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$systemSettingObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$systemSettingObject->delete(); 
        }
    }
}
if (isset($_GET ['method'])) {
    $systemSettingObject = new SystemSettingClass ();
    if ($_GET['securityToken'] != $systemSettingObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $systemSettingObject->setLeafId($_GET ['leafId']);
    }
    /*
     * Admin Only
     */
    if (isset($_GET ['isAdmin'])) {
        $systemSettingObject->setIsAdmin($_GET ['isAdmin']);
    }
    /**
     * Database Request
     */
    if (isset($_GET ['databaseRequest'])) {
        $systemSettingObject->setRequestDatabase($_GET ['databaseRequest']);
    }
    /*
     *  Load the dynamic value
     */
    $systemSettingObject->execute();

    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $systemSettingObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $systemSettingObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $systemSettingObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $systemSettingObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $systemSettingObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $systemSettingObject->excel();
        }
    }
    if (isset($_GET['countryId'])) {
        $systemSettingObject->getCountry();
    }
}
?>