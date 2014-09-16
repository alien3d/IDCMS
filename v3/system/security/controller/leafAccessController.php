<?php

namespace Core\System\Security\LeafAccess\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\RecordSet\RecordSet;
use Core\shared\SharedClass;
use Core\System\Security\LeafAccess\Model\LeafAccessModel;
use Core\System\Security\LeafAccess\Service\LeafAccessService;

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
require_once($newFakeDocumentRoot . "v3/system/security/model/leafAccessModel.php");
require_once($newFakeDocumentRoot . "v3/system/security/service/leafAccessService.php");

/**
 * Class LeafAccessClass
 * this is leaf access setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Security\LeafAccess\Controller
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class LeafAccessClass extends ConfigClass {

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
     * @var \Core\System\Security\LeafAccess\Model\LeafAccessModel
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\System\Security\LeafAccess\Service\LeafAccessService
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
        $this->setViewPath("./v3/system/security/view/leafAccess.php");
        $this->setControllerPath("./v3/system/security/controller/leafAccessController.php");
        $this->setServicePath("./v3/system/security/service/leafAccessService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(0);
        $this->setLog(1);
        $this->model = new LeafAccessModel();
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
        $this->q->getAudit($this->getAudit());
        $this->q->getLog($this->getLog());
        if ($this->getAudit() == 1) {
            $this->q->setAudit($this->getAudit());
            $this->q->setTableName($this->model->getTableName());
            $this->q->setPrimaryKeyName($this->model->getPrimaryKeyName());
            $this->q->setMultiId(1);
        }
        $this->setVendor($this->getVendor());
        $this->q->setRequestDatabase($this->q->getCoreDatabase());
        $this->q->setCurrentDatabase($this->q->getCoreDatabase());
        // $this->q->setApplicationId($this->getApplicationId()); 
        // $this->q->setModuleId($this->getModuleId()); 
        // $this->q->setFolderId($this->getFolderId()); 
        $this->q->setLeafId($this->getLeafId());
        $this->q->connect($this->getConnection(), $this->getUsername(), $this->getDatabase(), $this->getPassword());

        $this->service = new LeafAccessService();
        $this->service->q = $this->q;
        $this->service->setVendor($this->getVendor());
        $this->service->setServiceOutput($this->getServiceOutput());
        $this->service->execute();

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
        $this->setStart(0);
        $this->setLimit(500);

        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] == 0) {
                if ($this->getVendor() == self::MYSQL) {
                    $this->setAuditFilter(
                            " `leafaccess`.`isActive` = 1  AND `leafaccess`.`companyId`='" . $this->getCompanyId() . "' "
                    );
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [leafAccess].[isActive] = 1 AND [leafAccess].[companyId]='" . $this->getCompanyId() . "' "
                        );
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(
                                    " LEAFACCESS.ISACTIVE = 1  AND LEAFACCESS.COMPANYID='" . $this->getCompanyId() . "'"
                            );
                        }
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter("   `leafaccess`.`companyId`='" . $this->getCompanyId() . "'	");
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(" [leafAccess].[companyId]='" . $this->getCompanyId() . "' ");
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(" LEAFACCESS.COMPANYID='" . $this->getCompanyId() . "' ");
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
			SELECT	`leafaccess`.`leafAccessId`,
                    `company`.`companyDescription`,
                    `leafaccess`.`companyId`,
                    `application`.`applicationEnglish`,
					`module`.`moduleEnglish`,
					`folder`.`folderEnglish`,
					`leaf`.`leafEnglish`,
                    `leafaccess`.`leafId`,
                    `staff`.`staffName`,
                    `leafaccess`.`staffId`,
                    `leafaccess`.`leafAccessDraftValue`,
                    `leafaccess`.`leafAccessCreateValue`,
                    `leafaccess`.`leafAccessReadValue`,
                    `leafaccess`.`leafAccessUpdateValue`,
                    `leafaccess`.`leafAccessDeleteValue`,
                    `leafaccess`.`leafAccessReviewValue`,
                    `leafaccess`.`leafAccessApprovedValue`,
                    `leafaccess`.`leafAccessPostValue`,
                    `leafaccess`.`leafAccessPrintValue`,
                    `leafaccess`.`executeBy`,
                    `leafaccess`.`executeTime`,
                    `staff`.`staffName`
					FROM      `leafaccess`
			JOIN	`company`
			ON		`company`.`companyId` = `leafaccess`.`companyId`
			JOIN	`leaf`
			ON		`leaf`.`leafId` = `leafaccess`.`leafId`
			JOIN	`staff`
			ON		`staff`.`staffId` = `leafaccess`.`staffId`
			JOIN	`application`
			ON		`leafaccess`.`companyId` 	= 	`application`.`companyId`
			AND		`leaf`.`applicationId`		= 	`application`.`applicationId`
			
			JOIN	`module`
			ON		`leafaccess`.`companyId`	=	`module`.`companyId`
			AND		`leaf`.`moduleId`			=	`module`.`moduleId`
			
			JOIN	`folder`
			ON		`leafaccess`.`companyId`	=	`folder`.`companyId`
			AND		`leaf`.`folderId`			=	`folder`.`folderId`
	
          WHERE     " . $this->getAuditFilter();
            if ($this->model->getLeafAccessId(0, 'single')) {
                $sql .= " AND `leafaccess`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getLeafAccessId(0, 'single') . "'";
            }
            if ($this->model->getApplicationId()) {
                $sql .= " AND `leaf`.`applicationId`   =   '" . $this->model->getApplicationId() . "'";
            }
            if ($this->model->getModuleId()) {
                $sql .= " AND `leaf`.`moduleId`   =   '" . $this->model->getModuleId() . "'";
            }
            if ($this->model->getFolderId()) {
                $sql .= " AND `leaf`.`folderId`  =   '" . $this->model->getFolderId() . "'";
            }
            if ($this->model->getLeafIdTemp()) {
                $sql .= " AND `leaf`.`leafId`='" . $this->model->getLeafIdTemp() . "'";
            }
            if ($this->model->getStaffIdTemp()) {
                $sql .= " AND `leafaccess`.`staffId`='" . $this->model->getStaffIdTemp() . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
          SELECT	[leafAccess].[leafAccessId],
                    [company].[companyDescription],
                    [leafAccess].[companyId],
					[application].[applicationEnglish],
					[module].[moduleEnglish],
					[folder].[folderEnglish],
                    [leaf].[leafEnglish],
                    [leafAccess].[leafId],
                    [staff].[staffName],
                    [leafAccess].[staffId],
                    [leafAccess].[leafAccessDraftValue],
                    [leafAccess].[leafAccessCreateValue],
                    [leafAccess].[leafAccessReadValue],
                    [leafAccess].[leafAccessUpdateValue],
                    [leafAccess].[leafAccessDeleteValue],
                    [leafAccess].[leafAccessReviewValue],
                    [leafAccess].[leafAccessApprovedValue],
                    [leafAccess].[leafAccessPostValue],
                    [leafAccess].[leafAccessPrintValue],
                    [leafAccess].[executeBy],
                    [leafAccess].[executeTime],
                    [staff].[staffName]
			FROM 	[leafAccess]

			JOIN	[staff]
			ON		[leafaccess].[executeBy]    =   [staff].[staffId]

			JOIN	[company]
			ON		[company].[companyId]       =   [leafaccess].[companyId]

			JOIN	[leaf]
			ON		[leaf].[leafId]             =   [leafaccess].[leafId]

			JOIN	[staff]
			ON		[staff].[staffId]           =   [leafaccess].[staffId]

			JOIN	[application]
			ON		[leafaccess].[companyId] 	= 	[application].[companyId]
			AND		[leaf].[applicationId]		= 	[application].[applicationId]

			JOIN	[module]
			ON		[leafaccess].[companyId]	=	[module].[companyId]
			AND		[leaf].[moduleId]			=	[module].[moduleId]

			JOIN	[folder]
			ON		[leafaccess].[companyId]	=	[folder].[companyId]
			AND		[leaf].[folderId]			=	[folder].[folderId]

			WHERE     " . $this->getAuditFilter();
                if ($this->model->getLeafAccessId(0, 'single')) {
                    $sql .= " AND [leafAccess].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getLeafAccessId(0, 'single') . "'";
                }
                if ($this->model->getApplicationId()) {
                    $sql .= " AND [leaf].[applicationId]   =   '" . $this->model->getApplicationId() . "'";
                }
                if ($this->model->getModuleId()) {
                    $sql .= " AND [leaf].[moduleId]   =   '" . $this->model->getModuleId() . "'";
                }
                if ($this->model->getFolderId()) {
                    $sql .= " AND [leaf].[folderId]  =   '" . $this->model->getFolderId() . "'";
                }
                if ($this->model->getLeafIdTemp()) {
                    $sql .= " AND [leafaccess].[leafId]='" . $this->model->getLeafIdTemp() . "'";
                }
                if ($this->model->getStaffIdTemp()) {
                    $sql .= " AND [leafaccess].[staffId]='" . $this->model->getStaffIdTemp() . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
            SELECT		LEAFACCESS.LEAFACCESSID 			AS	\"leafAccessId\",
						COMPANY.COMPANYDESCRIPTION 			AS  \"companyDescription\",
						LEAFACCESS.COMPANYID 				AS 	\"companyId\",
						APPLICATION.APPLICATIONENGLISH 		AS  \"applicationEnglish\",
						MODULE.MODULEENGLISH 				AS  \"moduleEnglish\",
						FOLDER.FOLDERENGLISH 				AS  \"folderEnglish\",
						LEAF.LEAFENGLISH 					AS  \"leafEnglish\",
						STAFF.STAFFNAME						AS  \"staffName\",
						LEAFACCESS.LEAFID 					AS 	\"leafId\",
						LEAFACCESS.STAFFID 					AS 	\"staffId\",
						LEAFACCESS.LEAFACCESSDRAFTVALUE 	AS 	\"leafAccessDraftValue\",
						LEAFACCESS.LEAFACCESSCREATEVALUE 	AS 	\"leafAccessCreateValue\",
						LEAFACCESS.LEAFACCESSREADVALUE 		AS 	\"leafAccessReadValue\",
						LEAFACCESS.LEAFACCESSUPDATEVALUE 	AS 	\"leafAccessUpdateValue\",
						LEAFACCESS.LEAFACCESSDELETEVALUE 	AS 	\"leafAccessDeleteValue\",
						LEAFACCESS.LEAFACCESSREVIEWVALUE 	AS 	\"leafAccessReviewValue\",
						LEAFACCESS.LEAFACCESSAPPROVEDVALUE	AS 	\"leafAccessApprovedValue\",
						LEAFACCESS.LEAFACCESSPOSTVALUE 		AS 	\"leafAccessPostValue\",
						LEAFACCESS.LEAFACCESSPRINTVALUE 	AS 	\"leafAccessPrintValue\",
						LEAFACCESS.EXECUTEBY 				AS 	\"executeBy\",
						LEAFACCESS.EXECUTETIME 				AS	\"executeTime\"
			FROM		LEAFACCESS

			JOIN        COMPANY
			ON          COMPANY.COMPANYID       =   LEAFACCESS.COMPANYID

            JOIN        LEAF
            ON          LEAF.LEAFID             =   LEAFACCESS.LEAFID
            AND         LEAF.COMPANYID          =   LEAFACCESS.COMPANYID

            JOIN        STAFF
            ON          STAFF.STAFFID           =   LEAFACCESS.STAFFID
            AND         STAFF.COMPANYID         =   LEAFACCESS.COMPANYID

            JOIN        APPLICATION
            ON          LEAFACCESS.COMPANYID    =   APPLICATION.COMPANYID
            AND         LEAF.APPLICATIONID      =   APPLICATION.APPLICATIONID

            JOIN        MODULE
            ON          LEAFACCESS.COMPANYID    =   MODULE.COMPANYID
            AND         LEAF.MODULEID           =   MODULE.MODULEID
            AND         LEAF.APPLICATIONID      =   MODULE.APPLICATIONID

            JOIN        FOLDER
            ON          LEAFACCESS.COMPANYID    =   FOLDER .COMPANYID
            AND         LEAF .FOLDERID          =   FOLDER .FOLDERID
            AND         LEAF.APPLICATIONID      =   FOLDER.APPLICATIONID
            AND         LEAF.MODULEID           =   FOLDER.MODULEID
            WHERE     " . $this->getAuditFilter();
                    if ($this->model->getLeafAccessId(0, 'single')) {
                        $sql .= " AND LEAFACCESS. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getLeafAccessId(0, 'single') . "'";
                    }
                    if ($this->model->getApplicationId()) {
                        $sql .= " AND LEAF.APPLICATIONID   =   '" . $this->model->getApplicationId() . "'";
                    }
                    if ($this->model->getModuleId()) {
                        $sql .= " AND LEAF.MODULEID   =   '" . $this->model->getModuleId() . "'";
                    }
                    if ($this->model->getFolderId()) {
                        $sql .= " AND LEAF.FOLDERID   =   '" . $this->model->getFolderId() . "'";
                    }
                    if ($this->model->getLeafIdTemp()) {
                        $sql .= " AND LEAFACCESS.LEAFID='" . $this->model->getLeafIdTemp() . "'";
                    }
                    if ($this->model->getStaffIdTemp()) {
                        $sql .= " AND LEAFACCESS.STAFFID='" . $this->model->getStaffIdTemp() . "'";
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
                $sql .= " AND `leafaccess`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [leafAccess].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND Initcap(LEAFACCESS." . strtoupper(
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
                        'leafaccess', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'leafAccess', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'LEAFACCESS', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
                "`leafaccess`.`leafAccessId`",
                "`staff`.`staffPassword`"
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $filterArray = array(
                    "[leafaccess].[leafAccessId]",
                    "[staff].[staffPassword]"
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $filterArray = array(
                        "LEAFACCESS.LEAFACCESSID",
                        "STAFF.STAFFPASSWORD"
                    );
                }
            }
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('staff', 'leafaccess', 'company', 'leaf', 'staff');
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array('staff', 'leafaccess', 'company', 'leaf', 'staff');
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array('STAFF', 'LEAFACCESS', 'COMPANY', 'LEAF', 'STAFF');
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
                        //@todo testing binder .fast ? unsure
                        // oracle don't have privillage using ? as bind parameter
                        $sqlDerived = "
                        SELECT *
                        FROM 	(
                                    SELECT	a.*,
                                            rownum r
                                    FROM ( " . $sql . "
                                ) a
                        WHERE 	rownum <= :maxRow )
                        WHERE 	r >=  :minRow";
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
        if (!($this->model->getLeafAccessId(0, 'single'))) {
            try {
                // @todo future will be used push instead
                /**
                 * Expirement
                 * $maxRow = $this->getStart() + $this->getLimit();
                 * $minRow = $this->getStart() + 1;
                 * $bindParamArrayName = array('maxRow', 'minRow');
                 * $bindParamArrayValue = array($maxRow, $minRow);
                 * $this->q->read($sqlDerived, $bindParamArrayName, $bindParamArrayValue);
                 */
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
            $row['counter'] = $this->getStart() + 12;
            /*
              if ($this->model->getLeafAccessId(0, 'single')) {
              $row['firstRecord'] = $this->firstRecord('value');
              $row['previousRecord'] = $this->previousRecord('value', $this->model->getLeafAccessId(0, 'single'));
              $row['nextRecord'] = $this->nextRecord('value', $this->model->getLeafAccessId(0, 'single'));
              $row['lastRecord'] = $this->lastRecord('value');
              }
             */
            $items [] = $row;
            $i++;
        }

        if ($this->getPageOutput() == 'html') {
            return $items;
        } else if ($this->getPageOutput() == 'table') {

            $i = 0;
            $str = null;
            foreach ($items as $data) {
                $i++;
                //  $str ."<tr> <td colspan=99999>".$this->exceptionMessage($sqlDerived)."</td></tr>";

                $str .= "<tr>
                            <td width=\"20px\"><div align=\"center\">" . $i . ". <input style='display:none;' type=\"checkbox\" name='leafAccessId[]' id='leafAccessId' value='" . $data['leafAccessId'] . "'></div></td>
                            <td>" . $data['applicationEnglish'] . "</td>
                            <td>" . $data['moduleEnglish'] . "</td>
                            <td>" . $data['folderEnglish'] . "</td>
                            <td>" . $data['leafEnglish'] . "</td>
                            <td>" . $data['staffName'] . "</td>";
                if ($data['leafAccessDraftValue'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = null;
                }
                $str .= "<td width=\"50px\"><input " . $checked . " type=\"checkbox\" name='leafAccessDraftValue[]' id='leafAccessDraftValue' value='" . $data['leafAccessDraftValue'] . "'></td>";

                if ($data['leafAccessCreateValue'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = null;
                }
                $str .= "<td><input " . $checked . " type=\"checkbox\" name='leafAccessCreateValue[]' id='leafAccessCreateValue' value='" . $data['leafAccessCreateValue'] . "'></td>";

                if ($data['leafAccessReadValue'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = null;
                }
                $str .= "<td><input " . $checked . " type=\"checkbox\" name='leafAccessReadValue[]' id='leafAccessReadValue' value='" . $data['leafAccessReadValue'] . "'></td>";

                if ($data['leafAccessUpdateValue'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = null;
                }
                $str .= "<td><input " . $checked . " type=\"checkbox\" name='leafAccessUpdateValue[]' id='leafAccessUpdateValue' value='" . $data['leafAccessUpdateValue'] . "'></td>";

                if ($data['leafAccessDeleteValue'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = null;
                }
                $str .= "<td><input " . $checked . " type=\"checkbox\" name='leafAccessDeleteValue[]' id='leafAccessDeleteValue' value='" . $data['leafAccessDeleteValue'] . "'></td>";

                if ($data['leafAccessReviewValue'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = null;
                }
                $str .= "<td><input " . $checked . " type=\"checkbox\" name='leafAccessReviewValue[]' id='leafAccessReviewValue' value='" . $data['leafAccessReviewValue'] . "'></td>";

                if ($data['leafAccessApprovedValue'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = null;
                }
                $str .= "<td><input " . $checked . " type=\"checkbox\" name='leafAccessApprovedValue[]' id='leafAccessApprovedValue' value='" . $data['leafAccessApprovedValue'] . "'></td>";

                if ($data['leafAccessPostValue'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = null;
                }
                $str .= "<td><input " . $checked . " type=\"checkbox\" name='leafAccessPostValue[]' id='leafAccessPostValue' value='" . $data['leafAccessPostValue'] . "'></td>";

                if ($data['leafAccessPrintValue'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = null;
                }
                $str .= "<td><input " . $checked . " type=\"checkbox\" name='leafAccessPrintValue[]' id='leafAccessPrintValue' value='" . $data['leafAccessPrintValue'] . "'></td>";

                $str .= "</tr>";
            }
            echo json_encode(array("success" => true, "message" => "complete", "data" => $str));
            exit();
        } else {
            if ($this->getPageOutput() == 'json') {
                if ($this->model->getLeafAccessId(0, 'single')) {
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
                                                'value', $this->model->getLeafAccessId(0, 'single')
                                        ),
                                        'nextRecord' => $this->nextRecord('value', $this->model->getLeafAccessId(0, 'single')),
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
                                        'value', $this->model->getLeafAccessId(0, 'single')
                                ),
                                'nextRecord' => $this->recordSet->nextRecord(
                                        'value', $this->model->getLeafAccessId(0, 'single')
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

            $sql = "SET NAMES \"utf8\"";
            $this->q->fast($sql);
        }
        $this->q->start();
        $loop = $this->model->getTotal();

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			UPDATE `" . $this->model->getTableName() . "`
			SET";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			UPDATE 	[" . $this->model->getTableName() . "]
			SET 	";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			UPDATE " . strtoupper($this->model->getTableName()) . "
			SET    ";
        } else {
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        $access = array(
            "leafAccessDraftValue",
            "leafAccessCreateValue",
            "leafAccessReadValue",
            "leafAccessUpdateValue",
            "leafAccessDeleteValue",
            "leafAccessReviewValue",
            "leafAccessApprovedValue",
            "leafAccessReviewValue",
            "leafAccessPostValue",
            "leafAccessPrintValue"
        );
        $sqlLooping = '';
        foreach ($access as $systemCheck) {

            switch ($systemCheck) {
                case 'leafAccessDraftValue' :
                    for ($i = 0; $i < $loop; $i++) {
                        if (strlen($this->model->getLeafAccessDraftValue($i, 'array')) > 0) {
                            if ($this->getVendor() == self::MYSQL) {
                                $sqlLooping .= " `" . $systemCheck . "` = CASE `" . $this->model->getTableName(
                                        ) . "`.`" . $this->model->getPrimaryKeyName() . "`";
                            } else if ($this->getVendor() == self::MSSQL) {
                                $sqlLooping .= "  [" . $systemCheck . "] = CASE [" . $this->model->getTableName(
                                        ) . "].[" . $this->model->getPrimaryKeyName() . "]";
                            } else if ($this->getVendor() == self::ORACLE) {
                                $sqlLooping .= "	" . strtoupper($systemCheck) . " = CASE " . strtoupper(
                                                $this->model->getTableName()
                                        ) . strtoupper($this->model->getPrimaryKeyName()) . " ";
                            } else {
                                echo json_encode(
                                        array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])
                                );
                                exit();
                            }
                            $sqlLooping .= "
							WHEN '" . $this->model->getLeafAccessId($i, 'array') . "'
							THEN '" . $this->model->getLeafAccessDraftValue($i, 'array') . "'";
                            $sqlLooping .= " ELSE " . strtoupper($systemCheck) . " END,";
                        }
                    }
                    break;
                case 'leafAccessCreateValue' :
                    for ($i = 0; $i < $loop; $i++) {
                        if (strlen($this->model->getLeafAccessCreateValue($i, 'array')) > 0) {
                            if ($this->getVendor() == self::MYSQL) {
                                $sqlLooping .= " `" . $systemCheck . "` = CASE `" . $this->model->getTableName(
                                        ) . "`.`" . $this->model->getPrimaryKeyName() . "`";
                            } else if ($this->getVendor() == self::MSSQL) {
                                $sqlLooping .= "  [" . $systemCheck . "] = CASE [" . $this->model->getTableName(
                                        ) . "].[" . $this->model->getPrimaryKeyName() . "]";
                            } else if ($this->getVendor() == self::ORACLE) {
                                $sqlLooping .= "	" . strtoupper($systemCheck) . " = CASE " . strtoupper(
                                                $this->model->getTableName()
                                        ) . strtoupper($this->model->getPrimaryKeyName()) . " ";
                            } else {
                                echo json_encode(
                                        array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])
                                );
                                exit();
                            }
                            $sqlLooping .= "
							WHEN '" . $this->model->getLeafAccessId($i, 'array') . "'
							THEN '" . $this->model->getLeafAccessCreateValue($i, 'array') . "'";
                            $sqlLooping .= " ELSE " . strtoupper($systemCheck) . " END,";
                        }
                    }
                    break;
                case 'leafAccessReadValue' :
                    for ($i = 0; $i < $loop; $i++) {
                        if (strlen($this->model->getLeafAccessReadValue($i, 'array')) > 0) {
                            if ($this->getVendor() == self::MYSQL) {
                                $sqlLooping .= " `" . $systemCheck . "` = CASE `" . $this->model->getTableName(
                                        ) . "`.`" . $this->model->getPrimaryKeyName() . "`";
                            } else if ($this->getVendor() == self::MSSQL) {
                                $sqlLooping .= "  [" . $systemCheck . "] = CASE [" . $this->model->getTableName(
                                        ) . "].[" . $this->model->getPrimaryKeyName() . "]";
                            } else if ($this->getVendor() == self::ORACLE) {
                                $sqlLooping .= "	" . strtoupper($systemCheck) . " = CASE " . strtoupper(
                                                $this->model->getTableName()
                                        ) . strtoupper($this->model->getPrimaryKeyName()) . " ";
                            } else {
                                echo json_encode(
                                        array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])
                                );
                                exit();
                            }
                            $sqlLooping .= "
							WHEN '" . $this->model->getLeafAccessId($i, 'array') . "'
							THEN '" . $this->model->getLeafAccessReadValue($i, 'array') . "'";
                            $sqlLooping .= " ELSE " . strtoupper($systemCheck) . " END,";
                        }
                    }
                    break;
                case 'leafAccessUpdateValue' :
                    for ($i = 0; $i < $loop; $i++) {
                        if (strlen($this->model->getLeafAccessUpdateValue($i, 'array')) > 0) {
                            if ($this->getVendor() == self::MYSQL) {
                                $sqlLooping .= " `" . $systemCheck . "` = CASE `" . $this->model->getTableName(
                                        ) . "`.`" . $this->model->getPrimaryKeyName() . "`";
                            } else if ($this->getVendor() == self::MSSQL) {
                                $sqlLooping .= "  [" . $systemCheck . "] = CASE [" . $this->model->getTableName(
                                        ) . "].[" . $this->model->getPrimaryKeyName() . "]";
                            } else if ($this->getVendor() == self::ORACLE) {
                                $sqlLooping .= "	" . strtoupper($systemCheck) . " = CASE " . strtoupper(
                                                $this->model->getTableName()
                                        ) . strtoupper($this->model->getPrimaryKeyName()) . " ";
                            } else {
                                echo json_encode(
                                        array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])
                                );
                                exit();
                            }
                            $sqlLooping .= "
							WHEN '" . $this->model->getLeafAccessId($i, 'array') . "'
							THEN '" . $this->model->getLeafAccessUpdateValue($i, 'array') . "'";
                            $sqlLooping .= " ELSE " . strtoupper($systemCheck) . " END,";
                        }
                    }
                    break;
                case 'leafAccessDeleteValue' :
                    for ($i = 0; $i < $loop; $i++) {
                        if (strlen($this->model->getLeafAccessDeleteValue($i, 'array')) > 0) {
                            if ($this->getVendor() == self::MYSQL) {
                                $sqlLooping .= " `" . $systemCheck . "` = CASE `" . $this->model->getTableName(
                                        ) . "`.`" . $this->model->getPrimaryKeyName() . "`";
                            } else if ($this->getVendor() == self::MSSQL) {
                                $sqlLooping .= "  [" . $systemCheck . "] = CASE [" . $this->model->getTableName(
                                        ) . "].[" . $this->model->getPrimaryKeyName() . "]";
                            } else if ($this->getVendor() == self::ORACLE) {
                                $sqlLooping .= "	" . strtoupper($systemCheck) . " = CASE " . strtoupper(
                                                $this->model->getTableName()
                                        ) . strtoupper($this->model->getPrimaryKeyName()) . " ";
                            } else {
                                echo json_encode(
                                        array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])
                                );
                                exit();
                            }
                            $sqlLooping .= "
							WHEN '" . $this->model->getLeafAccessId($i, 'array') . "'
							THEN '" . $this->model->getLeafAccessDeleteValue($i, 'array') . "'";
                            $sqlLooping .= " ELSE " . strtoupper($systemCheck) . " END,";
                        }
                    }
                    break;
                case 'leafAccessReviewValue' :
                    for ($i = 0; $i < $loop; $i++) {
                        if (strlen($this->model->getLeafAccessReviewValue($i, 'array')) > 0) {
                            if ($this->getVendor() == self::MYSQL) {
                                $sqlLooping .= " `" . $systemCheck . "` = CASE `" . $this->model->getTableName(
                                        ) . "`.`" . $this->model->getPrimaryKeyName() . "`";
                            } else if ($this->getVendor() == self::MSSQL) {
                                $sqlLooping .= "  [" . $systemCheck . "] = CASE [" . $this->model->getTableName(
                                        ) . "].[" . $this->model->getPrimaryKeyName() . "]";
                            } else if ($this->getVendor() == self::ORACLE) {
                                $sqlLooping .= "	" . strtoupper($systemCheck) . " = CASE " . strtoupper(
                                                $this->model->getTableName()
                                        ) . strtoupper($this->model->getPrimaryKeyName()) . " ";
                            } else {
                                echo json_encode(
                                        array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])
                                );
                                exit();
                            }
                            $sqlLooping .= "
							WHEN '" . $this->model->getLeafAccessId($i, 'array') . "'
							THEN '" . $this->model->getLeafAccessReviewValue($i, 'array') . "'";
                            $sqlLooping .= " ELSE " . strtoupper($systemCheck) . " END,";
                        }
                    }
                    break;
                case 'leafAccessApprovedValue' :
                    for ($i = 0; $i < $loop; $i++) {
                        if (strlen($this->model->getLeafAccessApprovedValue($i, 'array')) > 0) {
                            if ($this->getVendor() == self::MYSQL) {
                                $sqlLooping .= " `" . $systemCheck . "` = CASE `" . $this->model->getTableName(
                                        ) . "`.`" . $this->model->getPrimaryKeyName() . "`";
                            } else if ($this->getVendor() == self::MSSQL) {
                                $sqlLooping .= "  [" . $systemCheck . "] = CASE [" . $this->model->getTableName(
                                        ) . "].[" . $this->model->getPrimaryKeyName() . "]";
                            } else if ($this->getVendor() == self::ORACLE) {
                                $sqlLooping .= "	" . strtoupper($systemCheck) . " = CASE " . strtoupper(
                                                $this->model->getTableName()
                                        ) . strtoupper($this->model->getPrimaryKeyName()) . " ";
                            } else {
                                echo json_encode(
                                        array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])
                                );
                                exit();
                            }
                            $sqlLooping .= "
							WHEN '" . $this->model->getLeafAccessId($i, 'array') . "'
							THEN '" . $this->model->getLeafAccessApprovedValue($i, 'array') . "'";
                            $sqlLooping .= " ELSE " . strtoupper($systemCheck) . " END,";
                        }
                    }
                    break;

                case 'leafAccessPostValue' :
                    for ($i = 0; $i < $loop; $i++) {
                        if (strlen($this->model->getLeafAccessPostValue($i, 'array')) > 0) {
                            if ($this->getVendor() == self::MYSQL) {
                                $sqlLooping .= " `" . $systemCheck . "` = CASE `" . $this->model->getTableName(
                                        ) . "`.`" . $this->model->getPrimaryKeyName() . "`";
                            } else if ($this->getVendor() == self::MSSQL) {
                                $sqlLooping .= "  [" . $systemCheck . "] = CASE [" . $this->model->getTableName(
                                        ) . "].[" . $this->model->getPrimaryKeyName() . "]";
                            } else if ($this->getVendor() == self::ORACLE) {
                                $sqlLooping .= "	" . strtoupper($systemCheck) . " = " . strtoupper(
                                                $this->model->getTableName()
                                        ) . strtoupper($this->model->getPrimaryKeyName()) . " ";
                            } else {
                                echo json_encode(
                                        array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])
                                );
                                exit();
                            }
                            $sqlLooping .= "
                                WHEN '" . $this->model->getLeafAccessId($i, 'array') . "'
                                THEN '" . $this->model->getLeafAccessPostValue($i, 'array') . "'";
                            $sqlLooping .= " ELSE " . strtoupper($systemCheck) . " END,";
                        }
                    }
                    break;
                case 'leafAccessPrintValue' :
                    for ($i = 0; $i < $loop; $i++) {
                        if (strlen($this->model->getLeafAccessPrintValue($i, 'array')) > 0) {
                            if ($this->getVendor() == self::MYSQL) {
                                $sqlLooping .= " `" . $systemCheck . "` = CASE `" . $this->model->getTableName(
                                        ) . "`.`" . $this->model->getPrimaryKeyName() . "`";
                            } else if ($this->getVendor() == self::MSSQL) {
                                $sqlLooping .= "  [" . $systemCheck . "] = CASE [" . $this->model->getTableName(
                                        ) . "].[" . $this->model->getPrimaryKeyName() . "]";
                            } else if ($this->getVendor() == self::ORACLE) {
                                $sqlLooping .= "	" . strtoupper($systemCheck) . " = CASE " . strtoupper(
                                                $this->model->getTableName()
                                        ) . strtoupper($this->model->getPrimaryKeyName()) . " ";
                            } else {
                                echo json_encode(
                                        array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])
                                );
                                exit();
                            }
                            $sqlLooping .= "
                                WHEN '" . $this->model->getLeafAccessId($i, 'array') . "'
                                THEN '" . $this->model->getLeafAccessPrintValue($i, 'array') . "'";
                            $sqlLooping .= " ELSE " . strtoupper($systemCheck) . " END,";
                        }
                    }
                    break;
            }
        }
        $sql .= substr($sqlLooping, 0, -1);
        if ($this->getVendor() == self::MYSQL) {
            $sql .= "
			WHERE `" . $this->model->getPrimaryKeyName() . "` IN (" . $this->model->getPrimaryKeyAll() . ")";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql .= "
			WHERE [" . $this->model->getPrimaryKeyName() . "] IN (" . $this->model->getPrimaryKeyAll() . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql .= "
			WHERE " . strtoupper($this->model->getPrimaryKeyName()) . "  IN (" . $this->model->getPrimaryKeyAll() . ")";
        } else {
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        $this->q->setPrimaryKeyAll($this->model->getPrimaryKeyAll());
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        //@todo future  PHP5.5 only
        // finally {
        $this->q->commit();
        // }

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
     * Return Application Data
     * @return mixed
     */
    public function getApplication() {
        if (!$this->service->getServiceOutput()) {
            $this->service->setServiceOutput('html');
        }
        return $this->service->getApplication();
    }

    /**
     * Return Module Data
     * @return mixed
     */
    public function getModule() {
        // if filter mean outside variable
        if ($this->model->getApplicationId()) {
            $this->service->setServiceOutput('option');
            echo $this->service->getModule($this->model->getApplicationId());
        } else {
            $this->service->setServiceOutput('html');
            return $this->service->getModule();
        }
    }

    /**
     * Return Folder Data
     * @return mixed
     */
    public function getFolder() {
        // if filter mean outside variable
        if ($this->model->getApplicationId() && $this->model->getModuleId()) {
            $this->service->setServiceOutput('option');
            echo $this->service->getFolder($this->model->getApplicationId(), $this->model->getModuleId());
        } else if ($this->model->getApplicationId() && !$this->model->getModuleId()) {
            $this->service->setServiceOutput('option');
            echo $this->service->getFolder($this->model->getApplicationId());
        } else {
            $this->service->setServiceOutput('html');
            return $this->service->getFolder();
        }
    }

    /**
     * Return Leaf Data
     * @return mixed
     */
    public function getLeaf() {
        // if filter mean outside variable

        if ($this->model->getApplicationId() && $this->model->getModuleId() && $this->model->getFolderId()) {
            $this->service->setServiceOutput('option');
            echo $this->service->getLeafTemp(
                    $this->model->getApplicationId(), $this->model->getModuleId(), $this->model->getFolderId()
            );
        } else if ($this->model->getApplicationId() && $this->model->getModuleId() && !$this->model->getFolderId()) {
            $this->service->setServiceOutput('option');
            echo $this->service->getLeafTemp($this->model->getApplicationId(), $this->model->getModuleId());
        } else if ($this->model->getApplicationId() && !$this->model->getModuleId() && !$this->model->getFolderId()) {
            $this->service->setServiceOutput('option');
            echo $this->service->getLeafTemp($this->model->getApplicationId());
        } else {
            $this->service->setServiceOutput('html');
            return $this->service->getLeafTemp();
        }
    }

    /**
     * Return Staff Data
     * @return mixed
     */
    public function getStaff() {
        $this->service->setServiceOutput('html');

        return $this->service->getStaff();
    }

    /**
     * Reporting
     * @see config::excel()
     */
    function excel() {
        
    }

}

if (isset($_POST ['method'])) {
    if (isset($_POST['output'])) {
        $leafAccessObject = new LeafAccessClass ();
        if ($_POST['securityToken'] != $leafAccessObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $leafAccessObject->setLeafId($_POST ['leafId']);
        }
        $leafAccessObject->setPageOutput($_POST['output']);
        $leafAccessObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $leafAccessObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $leafAccessObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $leafAccessObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $leafAccessObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$leafAccessObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$leafAccessObject->delete(); 
        }
    }
}
if (isset($_GET ['method'])) {
    $leafAccessObject = new LeafAccessClass ();
    if ($_GET['securityToken'] != $leafAccessObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $leafAccessObject->setLeafId($_GET ['leafId']);
    }
    /*
     * Admin Only
     */
    if (isset($_GET ['isAdmin'])) {
        $leafAccessObject->setIsAdmin($_GET ['isAdmin']);
    }
    /**
     * Database Request
     */
    if (isset($_GET ['databaseRequest'])) {
        $leafAccessObject->setRequestDatabase($_GET ['databaseRequest']);
    }
    if (isset($_GET ['databaseRequest'])) {
        $leafAccessObject->setRequestDatabase($_GET ['databaseRequest']);
    }
    if (isset($_GET ['databaseRequest'])) {
        $leafAccessObject->setRequestDatabase($_GET ['databaseRequest']);
    }
    /*
     *  Load the dynamic value
     */
    $leafAccessObject->execute();

    /**
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'update') {
        $leafAccessObject->update();
    }
    /*
     *  Checking Any Duplication  Key 
     */
    if (isset($_GET ['leafaccessCode'])) {
        if (strlen($_GET ['leafaccessCode']) > 0) {
            $leafAccessObject->duplicate();
        }
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $leafAccessObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $leafAccessObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $leafAccessObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $leafAccessObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $leafAccessObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $leafAccessObject->excel();
        }
    }
    if (isset($_GET['applicationId'])) {
        $leafAccessObject->getApplication();
    }
    if (isset($_GET['staffId'])) {
        $leafAccessObject->getStaff();
    }
    if (isset($_GET['filter'])) {

        if ($_GET['filter'] == 'moduleId') {
            $leafAccessObject->getModule();
        }
        if ($_GET['filter'] == 'folderId') {

            $leafAccessObject->getFolder();
        }
        if ($_GET['filter'] == 'leafIdTemp') {
            $leafAccessObject->getLeaf();
        }
    }
}
?>