<?php

namespace Core\Portal\Main\LogError\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\Portal\Main\LogError\Model\LogErrorModel;
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
require_once($newFakeDocumentRoot . "v3/portal/main/model/logErrorModel.php");

/**
 * Class LogError
 * this is logError controller files.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package  Core\Portal\Main\LogError\Controller
 * @subpackage Main
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class LogErrorClass extends ConfigClass {

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
     * @var \Core\Portal\Main\LogError\Model\LogErrorModel
     */
    public $model;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     * @var \PHPPowerPoint
     */
    //private $powerPoint; 
    /**
     * System Format
     * @var string
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
        $this->setViewPath("./v3/portal/main/view/logError.php");
        $this->setControllerPath("./v3/portal/main/controller/logErrorController.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new LogErrorModel();
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
        $this->setReportTitle(
                $applicationNative . " :: " . $moduleNative . " :: " . $folderNative . " :: " . $leafNative
        );

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
        //$this->model->setDocumentNumber($this->getDocumentNumber());
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `logerror` 
            (
                 `companyId`,
                 `applicationId`,
                 `moduleId`,
                 `folderId`,
                 `leafId`,
                 `roleId`,
                 `staffId`,
                 `logErrorOperation`,
                 `logErrorsql`,
                 `logErrordate`,
                 `logErrorAccess`,
                 `logError`,
                 `logErrorguid`
       ) VALUES ( 
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getApplicationId() . "',
                 '" . $this->model->getModuleId() . "',
                 '" . $this->model->getFolderId() . "',
                 '" . $this->model->getLeafId() . "',
                 '" . $this->model->getRoleId() . "',
                 '" . $this->model->getStaffId() . "',
                 '" . $this->model->getLogErrorOperation() . "',
                 '" . $this->model->getLogErrorsql() . "',
                 '" . $this->model->getLogErrordate() . "',
                 '" . $this->model->getLogErrorAccess() . "',
                 '" . $this->model->getLogError() . "',
                 '" . $this->model->getLogErrorguid() . "'
       );";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            INSERT INTO [logError] 
            (
                 [logErrorId],
                 [companyId],
                 [applicationId],
                 [moduleId],
                 [folderId],
                 [leafId],
                 [roleId],
                 [staffId],
                 [logErrorOperation],
                 [logErrorsql],
                 [logErrordate],
                 [logErrorAccess],
                 [logError],
                 [logErrorguid]
) VALUES ( 
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getApplicationId() . "',
                 '" . $this->model->getModuleId() . "',
                 '" . $this->model->getFolderId() . "',
                 '" . $this->model->getLeafId() . "',
                 '" . $this->model->getRoleId() . "',
                 '" . $this->model->getStaffId() . "',
                 '" . $this->model->getLogErrorOperation() . "',
                 '" . $this->model->getLogErrorsql() . "',
                 '" . $this->model->getLogErrordate() . "',
                 '" . $this->model->getLogErrorAccess() . "',
                 '" . $this->model->getLogError() . "',
                 '" . $this->model->getLogErrorguid() . "'
            );";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            INSERT INTO LOGERROR 
            (
                 COMPANYID,
                 APPLICATIONID,
                 MODULEID,
                 FOLDERID,
                 LEAFID,
                 ROLEID,
                 STAFFID,
                 LOGERROROPERATION,
                 LOGERRORSQL,
                 LOGERRORDATE,
                 LOGERRORACCESS,
                 LOGERROR,
                 LOGERRORGUID
            ) VALUES ( 
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getApplicationId() . "',
                 '" . $this->model->getModuleId() . "',
                 '" . $this->model->getFolderId() . "',
                 '" . $this->model->getLeafId() . "',
                 '" . $this->model->getRoleId() . "',
                 '" . $this->model->getStaffId() . "',
                 '" . $this->model->getLogErrorOperation() . "',
                 '" . $this->model->getLogErrorsql() . "',
                 '" . $this->model->getLogErrordate() . "',
                 '" . $this->model->getLogErrorAccess() . "',
                 '" . $this->model->getLogError() . "',
                 '" . $this->model->getLogErrorguid() . "'
            );";
        }
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $logErrorId = $this->q->lastInsertId();
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
                    "logErrorId" => $logErrorId,
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
         FROM    `logError`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT    COUNT(*) AS total
         FROM      [logError]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT    COUNT(*)    AS  \"total\"
         FROM      LOGERROR
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
                            " `logerror`.`isActive` = 1  AND `logerror`.`companyId`='" . $this->getCompanyId() . "' "
                    );
                } else if ($this->getVendor() == self::MSSQL) {
                    $this->setAuditFilter(
                            " [logError].[isActive] = 1 AND [logError].[companyId]='" . $this->getCompanyId() . "' "
                    );
                } else if ($this->getVendor() == self::ORACLE) {
                    $this->setAuditFilter(
                            " LOGERROR.ISACTIVE = 1  AND LOGERROR.COMPANYID='" . $this->getCompanyId() . "'"
                    );
                }
            } else if ($_SESSION['isAdmin'] == 1) {
                if ($this->getVendor() == self::MYSQL) {
                    $this->setAuditFilter("   `logerror`.`companyId`='" . $this->getCompanyId() . "'	");
                } else if ($this->getVendor() == self::MSSQL) {
                    $this->setAuditFilter(" [logError].[companyId]='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::ORACLE) {
                    $this->setAuditFilter(" LOGERROR.COMPANYID='" . $this->getCompanyId() . "' ");
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
       SELECT                    `logerror`.`logErrorId`,
                    `logerror`.`companyId`,
                    `logerror`.`applicationId`,
                    `logerror`.`moduleId`,
                    `logerror`.`folderId`,
                    `logerror`.`leafId`,
                    `logerror`.`roleId`,
                    `logerror`.`staffId`,
                    `logerror`.`logErrorOperation`,
                    `logerror`.`logErrorsql`,
                    `logerror`.`logErrordate`,
                    `logerror`.`logErrorAccess`,
                    `logerror`.`logError`,
                    `logerror`.`logErrorguid`,
                    `staff`.`staffName`
		  FROM      `logerror`
		  JOIN      `staff`
		  ON        `logerror`.`executeBy` = `staff`.`staffId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getLogErrorId(0, 'single')) {
                $sql .= " AND `logerror`.`" . $this->model->getPrimaryKeyName() . "`='" . $this->model->getLogErrorId(
                                0, 'single'
                        ) . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {

            $sql = "
		  SELECT                    [logError].[logErrorId],
                    [logError].[companyId],
                    [logError].[applicationId],
                    [logError].[moduleId],
                    [logError].[folderId],
                    [logError].[leafId],
                    [logError].[roleId],
                    [logError].[staffId],
                    [logError].[logErrorOperation],
                    [logError].[logErrorsql],
                    [logError].[logErrordate],
                    [logError].[logErrorAccess],
                    [logError].[logError],
                    [logError].[logErrorguid],
                    [staff].[staffName]
		  FROM 	[logError]
		  JOIN	[staff]
		  ON	[logError].[executeBy] = [staff].[staffId]
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getLogErrorId(0, 'single')) {
                $sql .= " AND [logError].[" . $this->model->getPrimaryKeyName(
                        ) . "]		=	'" . $this->model->getLogErrorId(0, 'single') . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {

            $sql = "
		  SELECT                    LOGERROR.LOGERRORID AS \"logErrorId\",
                    LOGERROR.COMPANYID AS \"companyId\",
                    LOGERROR.APPLICATIONID AS \"applicationId\",
                    LOGERROR.MODULEID AS \"moduleId\",
                    LOGERROR.FOLDERID AS \"folderId\",
                    LOGERROR.LEAFID AS \"leafId\",
                    LOGERROR.ROLEID AS \"roleId\",
                    LOGERROR.STAFFID AS \"staffId\",
                    LOGERROR.LOGERROROPERATION AS \"logErrorOperation\",
                    LOGERROR.LOGERRORSQL AS \"logErrorsql\",
                    LOGERROR.LOGERRORDATE AS \"logErrordate\",
                    LOGERROR.LOGERRORACCESS AS \"logErrorAccess\",
                    LOGERROR.LOGERROR AS \"logError\",
                    LOGERROR.LOGERRORGUID AS \"logErrorguid\",
                    STAFF.STAFFNAME AS \"staffName\"
		  FROM 	LOGERROR
		  JOIN	STAFF
		  ON	LOGERROR.EXECUTEBY = STAFF.STAFFID
          WHERE     " . $this->getAuditFilter();
            if ($this->model->getLogErrorId(0, 'single')) {
                $sql .= " AND LOGERROR. " . strtoupper(
                                $this->model->getPrimaryKeyName()
                        ) . "='" . $this->model->getLogErrorId(0, 'single') . "'";
            }
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        /**
         * filter column based on first character
         */
        if ($this->getCharacterQuery()) {
            if ($this->getVendor() == self::MYSQL) {
                $sql .= " AND `logerror`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql .= " AND [logError].[" . $this->model->getFilterCharacter(
                        ) . "] like '" . $this->getCharacterQuery() . "%'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql .= " AND Initcap(LOGERROR." . strtoupper(
                                $this->model->getFilterCharacter()
                        ) . ") LIKE Initcap('" . $this->getCharacterQuery() . "%')";
            }
        }
        /**
         * filter column based on Range Of Date
         * Example Day,Week,Month,Year
         */
        if ($this->getDateRangeStartQuery()) {
            if ($this->getVendor() == self::MYSQL) {
                $sql .= $this->q->dateFilter(
                        'logerror', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else if ($this->getVendor() == self::MSSQL) {
                $sql .= $this->q->dateFilter(
                        'logError', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else if ($this->getVendor() == self::ORACLE) {
                $sql .= $this->q->dateFilter(
                        'LOGERROR', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
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
                "`logerror`.`logErrorId`",
                "`staff`.`staffPassword`"
            );
        } else if ($this->getVendor() == self::MSSQL) {
            $filterArray = array(
                "[logerror].[logErrorId]",
                "[staff].[staffPassword]"
            );
        } else if ($this->getVendor() == self::ORACLE) {
            $filterArray = array(
                "LOGERROR.LOGERRORID",
                "STAFF.STAFFPASSWORD"
            );
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('staff', 'logerror',);
        } else if ($this->getVendor() == self::MSSQL) {
            $tableArray = array('staff', 'logerror',);
        } else if ($this->getVendor() == self::ORACLE) {
            $tableArray = array('STAFF', 'LOGERROR',);
        }
        $tempSql = null;
        if ($this->getFieldQuery()) {
            $this->q->setFieldQuery($this->getFieldQuery());
            if ($this->getVendor() == self::MYSQL) {
                $sql .= $this->q->quickSearch($tableArray, $filterArray);
            } else if ($this->getVendor() == self::MSSQL) {
                $tempSql = $this->q->quickSearch($tableArray, $filterArray);
                $sql .= $tempSql;
            } else if ($this->getVendor() == self::ORACLE) {
                $tempSql = $this->q->quickSearch($tableArray, $filterArray);
                $sql .= $tempSql;
            }
        }
        $tempSql2 = null;
        if ($this->getGridQuery()) {
            $this->q->setGridQuery($this->getGridQuery());
            if ($this->getVendor() == self::MYSQL) {
                $sql .= $this->q->searching();
            } else if ($this->getVendor() == self::MSSQL) {
                $tempSql2 = $this->q->searching();
                $sql .= $tempSql2;
            } else if ($this->getVendor() == self::ORACLE) {
                $tempSql2 = $this->q->searching();
                $sql .= $tempSql2;
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
            } else if ($this->getVendor() == self::MSSQL) {
                $sql .= "	ORDER BY [" . $this->getSortField() . "] " . $this->getOrder() . " ";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql .= "	ORDER BY " . strtoupper($this->getSortField()) . " " . strtoupper($this->getOrder()) . " ";
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
                
            } else if ($this->getVendor() == self::MSSQL) {
                /**
                 * Sql Server  2012 format only.Row Number
                 * Parameter Query We don't support
                 **/
                $sqlDerived = $sql . " 	OFFSET  	" . $this->getStart() . " ROWS
											FETCH NEXT 	" . $this->getLimit() . " ROWS ONLY ";
            } else if ($this->getVendor() == self::ORACLE) {
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
                echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
                exit();
            }
        }
        /*
         *  Only Execute One Query
         */
        if (!($this->model->getLogErrorId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 14;
            if ($this->model->getLogErrorId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getLogErrorId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getLogErrorId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else if ($this->getPageOutput() == 'json') {
            if ($this->model->getLogErrorId(0, 'single')) {
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
                                            'value', $this->model->getLogErrorId(0, 'single')
                                    ),
                                    'nextRecord' => $this->nextRecord('value', $this->model->getLogErrorId(0, 'single')),
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
                                    'value', $this->model->getLogErrorId(0, 'single')
                            ),
                            'nextRecord' => $this->recordSet->nextRecord('value', $this->model->getLogErrorId(0, 'single')),
                            'lastRecord' => $this->recordSet->lastRecord('value'),
                            'data' => $items
                        )
                );
                exit();
            }
        }
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
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`logError`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getLogErrorId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[logError]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getLogErrorId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	LOGERROR
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getLogErrorId(
                            0, 'single'
                    ) . "' ";
        }
        $result = $this->q->fast($sql);
        $total = $this->q->numberRows($result, $sql);
        if ($total == 0) {
            echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel']));
            exit();
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
               UPDATE `logerror` SET
                       `applicationId` = '" . $this->model->getApplicationId() . "',
                       `moduleId` = '" . $this->model->getModuleId() . "',
                       `folderId` = '" . $this->model->getFolderId() . "',
                       `leafId` = '" . $this->model->getLeafId() . "',
                       `roleId` = '" . $this->model->getRoleId() . "',
                       `staffId` = '" . $this->model->getStaffId() . "',
                       `logErrorOperation` = '" . $this->model->getLogErrorOperation() . "',
                       `logErrorsql` = '" . $this->model->getLogErrorsql() . "',
                       `logErrordate` = '" . $this->model->getLogErrordate() . "',
                       `logErrorAccess` = '" . $this->model->getLogErrorAccess() . "',
                       `logError` = '" . $this->model->getLogError() . "',
                       `logErrorguid` = '" . $this->model->getLogErrorguid() . "'
               WHERE    `logErrorId`='" . $this->model->getLogErrorId('0', 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE [logError] SET
                       [applicationId] = '" . $this->model->getApplicationId() . "',
                       [moduleId] = '" . $this->model->getModuleId() . "',
                       [folderId] = '" . $this->model->getFolderId() . "',
                       [leafId] = '" . $this->model->getLeafId() . "',
                       [roleId] = '" . $this->model->getRoleId() . "',
                       [staffId] = '" . $this->model->getStaffId() . "',
                       [logErrorOperation] = '" . $this->model->getLogErrorOperation() . "',
                       [logErrorsql] = '" . $this->model->getLogErrorsql() . "',
                       [logErrordate] = '" . $this->model->getLogErrordate() . "',
                       [logErrorAccess] = '" . $this->model->getLogErrorAccess() . "',
                       [logError] = '" . $this->model->getLogError() . "',
                       [logErrorguid] = '" . $this->model->getLogErrorguid() . "'
                WHERE   [logErrorId]='" . $this->model->getLogErrorId('0', 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE LOGERROR SET
                        APPLICATIONID = '" . $this->model->getApplicationId() . "',
                       MODULEID = '" . $this->model->getModuleId() . "',
                       FOLDERID = '" . $this->model->getFolderId() . "',
                       LEAFID = '" . $this->model->getLeafId() . "',
                       ROLEID = '" . $this->model->getRoleId() . "',
                       STAFFID = '" . $this->model->getStaffId() . "',
                       LOGERROROPERATION = '" . $this->model->getLogErrorOperation() . "',
                       LOGERRORSQL = '" . $this->model->getLogErrorsql() . "',
                       LOGERRORDATE = '" . $this->model->getLogErrordate() . "',
                       LOGERRORACCESS = '" . $this->model->getLogErrorAccess() . "',
                       LOGERROR = '" . $this->model->getLogError() . "',
                       LOGERRORGUID = '" . $this->model->getLogErrorguid() . "'
                WHERE  LOGERRORID='" . $this->model->getLogErrorId('0', 'single') . "'";
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
           FROM 	`logerror`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getLogErrorId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[logError]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getLogErrorId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	LOGERROR
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getLogErrorId(
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
               UPDATE  `logerror`
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
               WHERE   `logErrorId`   =  '" . $this->model->getLogErrorId(0, 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
               UPDATE  [logError]
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
               WHERE   [logErrorId]	=  '" . $this->model->getLogErrorId(0, 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
               UPDATE  LOGERROR
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
               WHERE   LOGERRORID	=  '" . $this->model->getLogErrorId(0, 'single') . "'";
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
           SELECT  `logErrorCode`
           FROM    `logerror`
           WHERE   `logErrorCode` 	= 	'" . $this->model->getLogErrorCode() . "'
           AND     `isActive`  =   1
           AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT  [logErrorCode]
           FROM    [logError]
           WHERE   [logErrorCode] = 	'" . $this->model->getLogErrorCode() . "'
           AND     [isActive]  =   1
           AND     [companyId] =	'" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
               SELECT  LOGERRORCODE as \"logErrorCode\"
               FROM    LOGERROR
               WHERE   LOGERRORCODE	= 	'" . $this->model->getLogErrorCode() . "'
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
                ->setSubject('logError')
                ->setDescription('Generated by PhpExcel an Idcms Generator')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('portal/main');
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
        $this->excel->getActiveSheet()->setCellValue('B2', $this->getReportTitle());
        $this->excel->getActiveSheet()->setCellValue('N2', '');
        $this->excel->getActiveSheet()->mergeCells('B2:N2');
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.');
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['applicationIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['moduleIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['folderIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['leafIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['roleIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['staffIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['logErrorOperationLabel']);
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['logErrorsqlLabel']);
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['logErrordateLabel']);
        $this->excel->getActiveSheet()->setCellValue('L3', $this->translate['logErrorAccessLabel']);
        $this->excel->getActiveSheet()->setCellValue('M3', $this->translate['logErrorLabel']);
        $this->excel->getActiveSheet()->setCellValue('N3', $this->translate['logErrorguidLabel']);
        // 
        $loopRow = 4;
        $i = 0;
        \PHPExcel_Cell::setValueBinder(new \PHPExcel_Cell_AdvancedValueBinder());
        $lastRow = null;
        while (($row = $this->q->fetchAssoc()) == true) {
            //	echo print_r($row); 
            $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i);
            $this->excel->getActiveSheet()->setCellValue('C' . $loopRow, strip_tags($row ['applicationDescription']));
            $this->excel->getActiveSheet()->setCellValue('D' . $loopRow, strip_tags($row ['moduleDescription']));
            $this->excel->getActiveSheet()->setCellValue('E' . $loopRow, strip_tags($row ['folderDescription']));
            $this->excel->getActiveSheet()->setCellValue('F' . $loopRow, strip_tags($row ['leafDescription']));
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, strip_tags($row ['roleDescription']));
            $this->excel->getActiveSheet()->setCellValue('H' . $loopRow, strip_tags($row ['staffName']));
            $this->excel->getActiveSheet()->setCellValue('I' . $loopRow, strip_tags($row ['logErrorOperation']));
            $this->excel->getActiveSheet()->setCellValue('J' . $loopRow, strip_tags($row ['logErrorsql']));
            $this->excel->getActiveSheet()->setCellValue('K' . $loopRow, strip_tags($row ['logErrordate']));
            $this->excel->getActiveSheet()->setCellValue('L' . $loopRow, strip_tags($row ['logErrorAccess']));
            $this->excel->getActiveSheet()->setCellValue('M' . $loopRow, strip_tags($row ['logError']));
            $this->excel->getActiveSheet()->setCellValue('N' . $loopRow, strip_tags($row ['logErrorguid']));
            $loopRow++;
            $lastRow = 'N' . $loopRow;
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
                $filename = "logError" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot() . "v3/portal/main/document/" . $folder . "/" . $filename;
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
                $filename = "logError" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot() . "v3/portal/main/document/" . $folder . "/" . $filename;
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
                $filename = "logError" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot() . "v3/portal/main/document/" . $folder . "/" . $filename;
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
                $filename = "logError" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot() . "v3/portal/main/document/" . $folder . "/" . $filename;
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
        $logErrorObject = new LogErrorClass ();
        if ($_POST['securityToken'] != $logErrorObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $logErrorObject->setLeafId($_POST ['leafId']);
        }
        if (isset($_POST ['offset'])) {
            $logErrorObject->setStart($_POST ['offset']);
        }
        if (isset($_POST ['limit'])) {
            $logErrorObject->setLimit($_POST ['limit']);
        }
        $logErrorObject->setPageOutput($_POST['output']);
        $logErrorObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $logErrorObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $logErrorObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $logErrorObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $logErrorObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$logErrorObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$logErrorObject->delete(); 
        }
    }
}
if (isset($_GET ['method'])) {
    $logErrorObject = new LogErrorClass ();
    if ($_GET['securityToken'] != $logErrorObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $logErrorObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $logErrorObject->execute();
    /*
     * Update Status of The Table. Admin Level Only 
     */
    if ($_GET ['method'] == 'updateStatus') {
        $logErrorObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key 
     */
    if ($_GET['method'] == 'duplicate') {
        $logErrorObject->duplicate();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $logErrorObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $logErrorObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $logErrorObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $logErrorObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $logErrorObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml') {
            $logErrorObject->excel();
        }
    }
}
?>
