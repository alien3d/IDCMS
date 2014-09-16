<?php

namespace Core\System\Security\LeafRoleAccess\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\RecordSet\RecordSet;
use Core\shared\SharedClass;
use Core\System\Security\LeafRoleAccess\Model\LeafRoleAccessModel;
use Core\System\Security\LeafRoleAccess\Service\LeafRoleAccessService;

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
require_once($newFakeDocumentRoot . "v3/system/security/model/leafRoleAccessModel.php");
require_once($newFakeDocumentRoot . "v3/system/security/service/leafRoleAccessService.php");

/**
 * Class LeafRoleAccessClass
 * this is leaf role access setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Security\LeafRoleAccess\Controller
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class LeafRoleAccessClass extends ConfigClass {

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
     * @var  \Core\System\Security\LeafRoleAccess\Model\LeafRoleAccessModel
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request
     * @var  \Core\System\Security\LeafRoleAccess\Service\LeafRoleAccessService
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
        $this->setViewPath("./v3/system/security/view/leafRoleAccess.php");
        $this->setControllerPath("./v3/system/security/controller/leafRoleAccessController.php");
        $this->setServicePath("./v3/system/security/service/leafRoleAccessService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(0);
        $this->setLog(1);
        $this->model = new LeafRoleAccessModel();
        $this->model->setVendor($this->getVendor());
        $this->model->execute();
        if ($this->getAudit() == 1) {
            $this->q->setAudit($this->getAudit());
            $this->q->setTableName($this->model->getTableName());
            $this->q->setPrimaryKeyName($this->model->getPrimaryKeyName());
            $this->q->setMultiId(1);
        }
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

        $this->service = new LeafRoleAccessService();
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

        //override
        $this->setStart(0);
        $this->setLimit(99999);
        // end override
        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] == 0) {
                if ($this->getVendor() == self::MYSQL) {
                    $this->setAuditFilter(" `leafroleaccess`.`isActive` = 1 ");
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(" [leafroleaccess].[isActive] = 1 ");
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(" LEAFROLEACCESS.ISACTIVE = 1 ");
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
		    SELECT  `leafroleaccess`.`leafRoleAccessId`,
                    `application`.`applicationEnglish`,
                    `module`.`moduleEnglish`,
                    `folder`.`folderEnglish`,
                    `leaf`.`leafEnglish`,
                    `role`.`roleDescription`,
                    `role`.`roleId`,
                    `leafroleaccess`.`leafRoleAccessDraftValue`,
                    `leafroleaccess`.`leafRoleAccessCreateValue`,
                    `leafroleaccess`.`leafRoleAccessReadValue`,
                    `leafroleaccess`.`leafRoleAccessUpdateValue`,
                    `leafroleaccess`.`leafRoleAccessDeleteValue`,
                    `leafroleaccess`.`leafRoleAccessReviewValue`,
                    `leafroleaccess`.`leafRoleAccessApprovedValue`,
                    `leafroleaccess`.`leafRoleAccessPostValue`,
                    `leafroleaccess`.`leafRoleAccessPrintValue`
		    FROM    `leafroleaccess`
		    JOIN	`leaf`
	        ON		`leaf`.`leafId` = `leafroleaccess`.`leafId`
            JOIN	`folder`
	        ON	    `leaf`.`folderId` = `folder`.`folderId`
            AND     `leaf`.`moduleId` = `folder`.`moduleId`
            AND     `leaf`.`applicationId`=`folder`.`applicationId`
            JOIN	`module`
            ON	    `leaf`.`moduleId` = `module`.`moduleId`
            AND     `leaf`.`applicationId`=`module`.`applicationId`
            JOIN	`application`
            ON		`leaf`.`applicationId` = `application`.`applicationId`
            JOIN	`role`
            ON		`role`.`roleId` = `leafroleaccess`.`roleId`
            WHERE     " . $this->getAuditFilter();
            if ($this->model->getleafRoleAccessId(0, 'single')) {
                $sql .= " AND `leafroleaccess`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getleafRoleAccessId(0, 'single') . "'";
            }
            if ($this->model->getApplicationId()) {
                $sql .= " AND `leaf`.`applicationId`		=	'" . $this->model->getApplicationId() . "'";
            }
            if ($this->model->getModuleId()) {
                $sql .= " AND `leaf`.`moduleId`		=	'" . $this->model->getModuleId() . "'";
            }
            if ($this->model->getFolderId()) {
                $sql .= " AND `leaf`.`folderId`		=	'" . $this->model->getFolderId() . "'";
            }
            if ($this->model->getRoleId()) {
                $sql .= " AND `leafroleAccess`.`roleId`	=	'" . $this->model->getRoleId() . "'";
            }
            if ($this->model->getLeafIdTemp()) {
                $sql .= " AND `leafroleAccess`.`leafId`	=	'" . $this->model->getLeafIdTemp() . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
		    SELECT  [leafroleaccess].[leafRoleAccessId],
                    [company].[companyDescription],
                    [leafroleAccess].[companyId],
					[application].[leafEnglish],
					[module].[moduleEnglish],
					[folder].[folderEnglish],
                    [leaf].[leafEnglish],
                    [leafroleaccess].[leafId],
                    [staff].[staffName],
                    [role].[roleDescription],
                    [leafroleaccess].[leafRoleAccessDraftValue],
                    [leafroleaccess].[leafRoleAccessCreateValue],
                    [leafroleaccess].[leafRoleAccessReadValue],
                    [leafroleaccess].[leafRoleAccessUpdateValue],
                    [leafroleaccess].[leafRoleAccessDeleteValue],
                    [leafroleaccess].[leafRoleAccessReviewValue],
                    [leafroleaccess].[leafRoleAccessApprovedValue],
                    [leafroleaccess].[leafRoleAccessPostValue],
                    [leafroleaccess].[leafRoleAccessPrintValue]
		    FROM 	[leafroleaccess]

	        JOIN	[staff]
			ON		[leafroleaccess].[executeBy]    =   [staff].[staffId]
			AND     [leafroleaccess].[companyId]    =   [staff].[companyId]

			JOIN	[company]
			ON		[company].[companyId]           =   [leafroleaccess].[companyId]

			JOIN	[leaf]
			ON		[leaf].[leafId]                 =   [leafroleaccess].[leafId]
			AND     [leaf].[companyId]              =   [leafroleaccess].[companyId]

			JOIN	[role]
			ON		[role].[roleId]                 =   [leafroleaccess].[roleId]
			AND     [role].[companyId]              =   [leafroleaccess].[companyId]

			JOIN	[application]
			ON		[leafroleaccess].[companyId] 	= 	[application].[companyId]
			AND		[leaf].[applicationId]		    = 	[application].[applicationId]

			JOIN	[module]
			ON		[leafroleaccess].[companyId]	=	[module].[companyId]
			AND		[leaf].[moduleId]			    =	[module].[moduleId]

			JOIN	[folder]
			ON		[leafroleaccess].[companyId]	=	[folder].[companyId]
			AND		[leaf].[folderId]			    =   [folder].[folderId]

		    WHERE     " . $this->getAuditFilter();
                if ($this->model->getleafRoleAccessId(0, 'single')) {
                    $sql .= " AND [leafroleaccess].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getleafRoleAccessId(0, 'single') . "'";
                }
                if ($this->model->getApplicationId()) {
                    $sql .= " AND [leaf].[applicationId]		=	'" . $this->strict(
                                    $this->model->getApplicationId(), 'numeric'
                            ) . "'";
                }
                if ($this->model->getModuleId()) {
                    $sql .= " AND [leaf].[moduleId]		=	'" . $this->strict(
                                    $this->model->getModuleId(), 'numeric'
                            ) . "'";
                }
                if ($this->model->getFolderId()) {
                    $sql .= " AND [leaf].[folderId]		=	'" . $this->strict(
                                    $this->model->getFolderId(), 'numeric'
                            ) . "'";
                }
                if ($this->model->getStaffIdTemp()) {
                    $sql .= " AND [leafRoleAccess`.[roleId]	=	'" . $this->strict(
                                    $this->model->getStaffIdTemp(), 'numeric'
                            ) . "'";
                }
                if ($this->model->getLeafIdTemp()) {
                    $sql .= " AND `leafRoleAccess`.`leafId`	=	'" . $this->model->getLeafIdTemp() . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
		    SELECT  LEAFROLEACCESS.LEAFROLEACCESSID 			AS	\"leafRoleAccessId\",
                    COMPANY.COMPANYDESCRIPTION 			        AS  \"companyDescription\",
                    LEAFROLEACCESS.COMPANYID 				    AS 	\"companyId\",
                    APPLICATION.APPLICATIONENGLISH 		        AS  \"applicationEnglish\",
                    MODULE.MODULEENGLISH 				        AS  \"moduleEnglish\",
                    FOLDER.FOLDERENGLISH 				        AS  \"folderEnglish\",
                    LEAF.LEAFENGLISH 					        AS  \"leafEnglish\",
                    LEAFROLEACCESS.LEAFID 					    AS 	\"leafId\",
                    LEAFROLEACCESS.ROLEID					    AS 	\"roled\",
                    ROLE.ROLEDESCRIPTION                        AS  \"roleDescription\",
                    STAFF.STAFFNAME                             AS  \"staffName\",
                    LEAFROLEACCESS.LEAFROLEACCESSDRAFTVALUE     AS  \"leafRoleAccessDraftValue\",
                    LEAFROLEACCESS.LEAFROLEACCESSCREATEVALUE    AS  \"leafRoleAccessCreateValue\",
                    LEAFROLEACCESS.LEAFROLEACCESSREADVALUE      AS  \"leafRoleAccessReadValue\",
                    LEAFROLEACCESS.LEAFROLEACCESSUPDATEVALUE    AS  \"leafRoleAccessUpdateValue\",
                    LEAFROLEACCESS.LEAFROLEACCESSDELETEVALUE    AS  \"leafRoleAccessDeleteValue\",
                    LEAFROLEACCESS.LEAFROLEACCESSREVIEWVALUE    AS  \"leafRoleAccessReviewValue\",
                    LEAFROLEACCESS.LEAFROLEACCESSAPPROVEDVALUE  AS  \"leafRoleAccessApprovedValue\",
                    LEAFROLEACCESS.LEAFROLEACCESSPOSTVALUE      AS  \"leafRoleAccessPostValue\",
                    LEAFROLEACCESS.LEAFROLEACCESSPRINTVALUE     AS  \"leafRoleAccessPrintValue\"
		    FROM 	LEAFROLEACCESS

		    JOIN	STAFF
			ON		LEAFROLEACCESS.EXECUTEBY    =   STAFF.STAFFID
			AND     LEAFROLEACCESS.COMPANYID    =   STAFF.COMPANYID

            JOIN    COMPANY
			ON      COMPANY.COMPANYID       =   LEAFROLEACCESS.COMPANYID

            JOIN    LEAF
            ON      LEAF.LEAFID             =   LEAFROLEACCESS.LEAFID
            AND     LEAF.COMPANYID          =   LEAFROLEACCESS.COMPANYID

            JOIN    ROLE
            ON      ROLE.ROLEID             =   LEAFROLEACCESS.ROLEID
            AND     ROLE.COMPANYID          =   LEAFROLEACCESS.COMPANYID

            JOIN    APPLICATION
            ON      LEAFROLEACCESS.COMPANYID    =   APPLICATION.COMPANYID
            AND     LEAF.APPLICATIONID      =   APPLICATION.APPLICATIONID

            JOIN    MODULE
            ON      LEAFROLEACCESS.COMPANYID    =   MODULE.COMPANYID
            AND     LEAF.MODULEID           =   MODULE.MODULEID
            AND     LEAF.APPLICATIONID      =   MODULE.APPLICATIONID

            JOIN    FOLDER
            ON      LEAFROLEACCESS.COMPANYID    =   FOLDER .COMPANYID
            AND     LEAF .FOLDERID          =   FOLDER .FOLDERID
            AND     LEAF.APPLICATIONID      =   FOLDER.APPLICATIONID
            AND     LEAF.MODULEID           =   FOLDER.MODULEID
            WHERE   " . $this->getAuditFilter();
                    if ($this->model->getleafRoleAccessId(0, 'single')) {
                        $sql .= " AND LEAFROLEACCESS. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getleafRoleAccessId(0, 'single') . "'";
                    }

                    if ($this->model->getApplicationId()) {
                        $sql .= " AND LEAFROLEACCESS.APPLICATIONID='" . $this->model->getApplicationId() . "'";
                    }
                    if ($this->model->getModuleId()) {
                        $sql .= " AND LEAFROLEACCESS.MODULEID='" . $this->model->getModuleId() . "'";
                    }
                    if ($this->model->getFolderId()) {
                        $sql .= " AND LEAFROLEACCESS.FOLDERID='" . $this->model->getFolderId() . "'";
                    }
                    if ($this->model->getStaffIdTemp()) {
                        $sql .= " AND LEAFROLEACCESS.ROLEID='" . $this->model->getStaffIdTemp() . "'";
                    }
                    if ($this->model->getLeafIdTemp() && $this->model->getLeafIdTemp()) {
                        $sql .= " AND LEAFROLEACESS.LEAFID	=	'" . $this->model->getLeafIdTemp() . "'";
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
                $sql .= " AND `leafroleaccess`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [leafroleaccess].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND Initcap(LEAFROLEACCESS." . strtoupper(
                                        $this->model->getFilterCharacter()
                                ) . ") like  Initcap('" . $this->getCharacterQuery() . "%');";
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
                        'leafroleaccess', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'leafroleaccess', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'LEAFROLEACCESS', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
        $filterArray = array('leafRoleAccessId');
        /**
         * filter table
         * @variables $tableArray
         */
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('leafroleaccess');
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array('leafroleaccess');
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array('LEAFROLEACCESS');
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

        $total = $this->q->numberRows();
        if ($this->getSortField()) {
            if ($this->getVendor() == self::MYSQL) {
                $sql .= "	ORDER BY `application`.`applicationSequence`,`module`.`moduleSequence`,`folder`.`folderSequence`,`leaf`.`leafSequence` ";
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
            if ($this->getVendor() == self::MYSQL) {
                $sqlDerived = $sql . " LIMIT  " . $this->getStart() . "," . $this->getLimit() . " ";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sqlDerived = $sql . "  OFFSET " . $this->getLimit() . " ROWS  FETCH NEXT   " . $this->getStart(
                            ) . " ROWS ONLY";
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
        if (!($this->model->getleafRoleAccessId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 12;
            if ($this->model->getleafRoleAccessId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getleafRoleAccessId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getleafRoleAccessId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
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
                $str .= "<tr>
                            <td width\"25px\" align=\"center\"><div align=\"center\">" . $i . ". <input style='display:none;' type=\"checkbox\" name='leafRoleAccessId[]' id='leafRoleAccessId' value='" . $data['leafRoleAccessId'] . "'>
    </div></td>
    <td>" . $data['applicationEnglish'] . "</td>
        <td>" . $data['moduleEnglish'] . "</td>
            <td>" . $data['folderEnglish'] . "</td>
                            <td>" . $data['leafEnglish'] . "</td>
                            <td>" . $data['roleDescription'] . "</td>";
                if ($data['leafRoleAccessDraftValue'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = null;
                }
                $str .= "<td><input " . $checked . " type=\"checkbox\" name='leafRoleAccessDraftValue[]' id='leafRoleAccessDraftValue' value='" . $data['leafRoleAccessDraftValue'] . "'></td>";

                if ($data['leafRoleAccessCreateValue'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = null;
                }
                $str .= "<td><input " . $checked . " type=\"checkbox\" name='leafRoleAccessCreateValue[]' id='leafRoleAccessCreateValue' value='" . $data['leafRoleAccessCreateValue'] . "'></td>";

                if ($data['leafRoleAccessReadValue'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = null;
                }
                $str .= "<td><input " . $checked . " type=\"checkbox\" name='leafRoleAccessReadValue[]' id='leafRoleAccessReadValue' value='" . $data['leafRoleAccessReadValue'] . "'></td>";

                if ($data['leafRoleAccessUpdateValue'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = null;
                }
                $str .= "<td><input " . $checked . " type=\"checkbox\" name='leafRoleAccessUpdateValue[]' id='leafRoleAccessUpdateValue' value='" . $data['leafRoleAccessUpdateValue'] . "'></td>";

                if ($data['leafRoleAccessDeleteValue'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = null;
                }
                $str .= "<td><input " . $checked . " type=\"checkbox\" name='leafRoleAccessDeleteValue[]' id='leafRoleAccessDeleteValue' value='" . $data['leafRoleAccessDeleteValue'] . "'></td>";

                if ($data['leafRoleAccessReviewValue'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = null;
                }
                $str .= "<td><input " . $checked . " type=\"checkbox\" name='leafRoleAccessReviewValue[]' id='leafRoleAccessReviewValue' value='" . $data['leafRoleAccessReviewValue'] . "'></td>";

                if ($data['leafRoleAccessApprovedValue'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = null;
                }
                $str .= "<td><input " . $checked . " type=\"checkbox\" name='leafRoleAccessApprovedValue[]' id='leafRoleAccessApprovedValue' value='" . $data['leafRoleAccessApprovedValue'] . "'></td>";

                if ($data['leafRoleAccessPostValue'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = null;
                }
                $str .= "<td><input " . $checked . " type=\"checkbox\" name='leafRoleAccessPostValue[]' id='leafRoleAccessPostValue' value='" . $data['leafRoleAccessPostValue'] . "'></td>";

                if ($data['leafRoleAccessPrintValue'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = null;
                }
                $str .= "<td><input " . $checked . " type=\"checkbox\" name='leafRoleAccessPrintValue[]' id='leafRoleAccessPrintValue' value='" . $data['leafRoleAccessPrintValue'] . "'></td>";

                $str .= "</tr>";
            }
            echo json_encode(array("success" => true, "data" => $str, "message" => "complete"));
            exit();
        } else {
            if ($this->getPageOutput() == 'json') {
                if ($this->model->getleafRoleAccessId(0, 'single')) {
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
                                                'value', $this->model->getleafRoleAccessId(0, 'single')
                                        ),
                                        'nextRecord' => $this->nextRecord(
                                                'value', $this->model->getleafRoleAccessId(0, 'single')
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
                                        'value', $this->model->getleafRoleAccessId(0, 'single')
                                ),
                                'nextRecord' => $this->recordSet->nextRecord(
                                        'value', $this->model->getleafRoleAccessId(0, 'single')
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
            "leafRoleAccessDraftValue",
            "leafRoleAccessCreateValue",
            "leafRoleAccessReadValue",
            "leafRoleAccessUpdateValue",
            "leafRoleAccessDeleteValue",
            "leafRoleAccessReviewValue",
            "leafRoleAccessApprovedValue",
            "leafRoleAccessReviewValue",
            "leafRoleAccessPostValue",
            "leafRoleAccessPrintValue"
        );
        $sqlLooping = '';
        foreach ($access as $systemCheck) {

            switch ($systemCheck) {
                case 'leafRoleAccessDraftValue' :
                    for ($i = 0; $i < $loop; $i++) {
                        if (strlen($this->model->getLeafRoleAccessDraftValue($i, 'array')) > 0) {
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
							WHEN " . $this->model->getleafRoleAccessId($i, 'array') . "
							THEN " . $this->model->getLeafRoleAccessDraftValue($i, 'array') . "";
                            $sqlLooping .= " ELSE " . strtoupper($systemCheck) . " END,";
                        }
                    }
                    break;
                case 'leafRoleAccessCreateValue' :
                    for ($i = 0; $i < $loop; $i++) {
                        if (strlen($this->model->getLeafRoleAccessCreateValue($i, 'array')) > 0) {
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
							WHEN " . $this->model->getleafRoleAccessId($i, 'array') . "
							THEN " . $this->model->getLeafRoleAccessCreateValue($i, 'array') . "";
                            $sqlLooping .= " ELSE " . strtoupper($systemCheck) . " END,";
                        }
                    }
                    break;
                case 'leafRoleAccessReadValue' :
                    for ($i = 0; $i < $loop; $i++) {
                        if (strlen($this->model->getLeafRoleAccessReadValue($i, 'array')) > 0) {
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
							WHEN " . $this->model->getleafRoleAccessId($i, 'array') . "
							THEN " . $this->model->getLeafRoleAccessReadValue($i, 'array') . "";
                            $sqlLooping .= " ELSE " . strtoupper($systemCheck) . " END,";
                        }
                    }
                    break;
                case 'leafRoleAccessUpdateValue' :
                    for ($i = 0; $i < $loop; $i++) {
                        if (strlen($this->model->getLeafRoleAccessUpdateValue($i, 'array')) > 0) {
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
							WHEN " . $this->model->getleafRoleAccessId($i, 'array') . "
							THEN " . $this->model->getLeafRoleAccessUpdateValue($i, 'array') . "";
                            $sqlLooping .= " ELSE " . strtoupper($systemCheck) . " END,";
                        }
                    }
                    break;
                case 'leafRoleAccessDeleteValue' :
                    for ($i = 0; $i < $loop; $i++) {
                        if (strlen($this->model->getLeafRoleAccessDeleteValue($i, 'array')) > 0) {
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
							WHEN " . $this->model->getleafRoleAccessId($i, 'array') . "
							THEN " . $this->model->getLeafRoleAccessDeleteValue($i, 'array') . "";
                            $sqlLooping .= " ELSE " . strtoupper($systemCheck) . " END,";
                        }
                    }
                    break;
                case 'leafRoleAccessReviewValue' :
                    for ($i = 0; $i < $loop; $i++) {
                        if (strlen($this->model->getLeafRoleAccessReviewValue($i, 'array')) > 0) {
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
							WHEN " . $this->model->getleafRoleAccessId($i, 'array') . "
							THEN " . $this->model->getLeafRoleAccessReviewValue($i, 'array') . "";
                            $sqlLooping .= " ELSE " . strtoupper($systemCheck) . " END,";
                        }
                    }
                    break;
                case 'leafRoleAccessApprovedValue' :
                    for ($i = 0; $i < $loop; $i++) {
                        if (strlen($this->model->getLeafRoleAccessApprovedValue($i, 'array')) > 0) {
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
							WHEN " . $this->model->getleafRoleAccessId($i, 'array') . "
							THEN " . $this->model->getLeafRoleAccessApprovedValue($i, 'array') . "";
                            $sqlLooping .= " ELSE " . strtoupper($systemCheck) . " END,";
                        }
                    }
                    break;

                case 'leafRoleAccessPostValue' :
                    for ($i = 0; $i < $loop; $i++) {
                        if (strlen($this->model->getLeafRoleAccessPostValue($i, 'array')) > 0) {
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
                                WHEN " . $this->model->getleafRoleAccessId($i, 'array') . "
                                THEN " . $this->model->getLeafRoleAccessPostValue($i, 'array') . "";
                            $sqlLooping .= " ELSE " . strtoupper($systemCheck) . " END,";
                        }
                    }
                    break;
                case 'leafRoleAccessPrintValue' :
                    for ($i = 0; $i < $loop; $i++) {
                        if (strlen($this->model->getLeafRoleAccessPrintValue($i, 'array')) > 0) {
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
                                WHEN '" . $this->model->getleafRoleAccessId($i, 'array') . "'
                                THEN '" . $this->model->getLeafRoleAccessPrintValue($i, 'array') . "'";
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
     * Return Role
     */
    public function getRole() {
        return $this->service->getRole();
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
        $leafRoleAccessObject = new LeafRoleAccessClass ();
        if ($_POST['securityToken'] != $leafRoleAccessObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $leafRoleAccessObject->setLeafId($_POST ['leafId']);
        }
        $leafRoleAccessObject->setPageOutput($_POST['output']);
        $leafRoleAccessObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $leafRoleAccessObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $leafRoleAccessObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $leafRoleAccessObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $leafRoleAccessObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$leafRoleAccessObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$leafRoleAccessObject->delete(); 
        }
    }
}
if (isset($_GET ['method'])) {
    $leafRoleAccessObject = new LeafRoleAccessClass ();
    if ($_GET['securityToken'] != $leafRoleAccessObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $leafRoleAccessObject->setLeafId($_GET ['leafId']);
    }
    /*
     * Admin Only
     */
    if (isset($_GET ['isAdmin'])) {
        $leafRoleAccessObject->setIsAdmin($_GET ['isAdmin']);
    }

    /*
     *  Load the dynamic value
     */
    $leafRoleAccessObject->execute();

    /**
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'update') {
        $leafRoleAccessObject->update();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $leafRoleAccessObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $leafRoleAccessObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $leafRoleAccessObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $leafRoleAccessObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $leafRoleAccessObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $leafRoleAccessObject->excel();
        }
    }
    if (isset($_GET['applicationId'])) {
        $leafRoleAccessObject->getApplication();
    }
    if (isset($_GET['roleId'])) {
        $leafRoleAccessObject->getRole();
    }
    if (isset($_GET['filter'])) {

        if ($_GET['filter'] == 'moduleId') {
            $leafRoleAccessObject->getModule();
        }
        if ($_GET['filter'] == 'folderId') {

            $leafRoleAccessObject->getFolder();
        }
        if ($_GET['filter'] == 'leafIdTemp') {
            $leafRoleAccessObject->getLeaf();
        }
    }
}
?>