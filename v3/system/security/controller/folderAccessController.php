<?php

namespace Core\System\Security\FolderAccess\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\RecordSet\RecordSet;
use Core\shared\SharedClass;
use Core\System\Security\FolderAccess\Model\FolderAccessModel;
use Core\System\Security\FolderAccess\Service\FolderAccessService;

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
require_once($newFakeDocumentRoot . "v3/system/security/model/folderAccessModel.php");
require_once($newFakeDocumentRoot . "v3/system/security/service/folderAccessService.php");

/**
 * Class FolderAccessClass
 * this is folderAccess setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Security\FolderAccess\Controller
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class FolderAccessClass extends ConfigClass {

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
     * @var \Core\System\Security\FolderAccess\Model\FolderAccessModel
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\System\Security\FolderAccess\Service\FolderAccessService
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
     * System Format  Array
     * @var mixed
     */
    public $systemFormatArray;

    /**
     * Application Primary Key
     * @var int
     */
    private $applicationId;

    /**
     * Module Primary Key
     * @var int
     */
    private $moduleId;

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
        $this->setViewPath("./v3/system/security/view/folderAccess.php");
        $this->setControllerPath("./v3/system/security/controller/folderAccessController.php");
        $this->setServicePath("./v3/system/security/service/folderAccessService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new FolderAccessModel();
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
        $this->q->setLog($this->getLog());
        if ($this->getAudit() == 1) {
            $this->q->setAudit($this->getAudit());
            $this->q->setTableName($this->model->getTableName());
            $this->q->setPrimaryKeyName($this->model->getPrimaryKeyName());
            $this->q->setMultiId(1);
        }
        $this->q->setRequestDatabase($this->q->getCoreDatabase());
        $this->q->setCurrentDatabase($this->q->getCoreDatabase());
        // $this->q->setApplicationId($this->getApplicationId()); 
        // $this->q->setModuleId($this->getModuleId()); 
        // $this->q->setFolderId($this->getFolderId()); 
        $this->q->setLeafId($this->getLeafId());
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

        $this->setReportTitle(
                $applicationNative . " :: " . $moduleNative . " :: " . $folderNative . " :: " . $leafNative
        );

        $this->service = new FolderAccessService();
        $this->service->q = $this->q;
        $this->service->setVendor($this->getVendor());
        $this->service->setServiceOutput($this->getServiceOutput());
        $this->service->execute();

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
        $this->setLimit(99999);

        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] == 0) {
                if ($this->getVendor() == self::MYSQL) {
                    $this->setAuditFilter(
                            " `folderaccess`.`isActive` = 1  AND `folderaccess`.`companyId`='" . $this->getCompanyId(
                            ) . "' "
                    );
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [folderAccess].[isActive] = 1 AND [folderAccess].[companyId]='" . $this->getCompanyId(
                                ) . "' "
                        );
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(
                                    " FOLDERACCESS.ISACTIVE = 1  AND FOLDERACCESS.COMPANYID='" . $this->getCompanyId() . "'"
                            );
                        }
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter("   `folderaccess`.`companyId`='" . $this->getCompanyId() . "'	");
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(" [folderAccess].[companyId]='" . $this->getCompanyId() . "' ");
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(" FOLDERACCESS.COMPANYID='" . $this->getCompanyId() . "' ");
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
            SELECT  `folderaccess`.`folderAccessId`,
                    `company`.`companyDescription`,
                    `folderaccess`.`companyId`,
                    `role`.`roleDescription`,
                    `folderaccess`.`roleId`,
                    `application`.`applicationEnglish`,
                    `module`.`moduleEnglish`,
                    `folder`.`folderEnglish`,
                    `folderaccess`.`folderId`,
                    `folderaccess`.`folderAccessValue`
            FROM    `folderaccess`

            JOIN	`company`
            USING   (`companyId`)

            JOIN	`role`
            USING   (`companyId`,`roleId`)

            JOIN	`folder`
            USING   (`companyId`,`folderId`)

            JOIN    `module`
            USING   (`companyId`,`moduleId`,`applicationId`)

            JOIN    `application`
            USING   (`companyId`,`applicationId`)

            WHERE   " . $this->getAuditFilter();
            if ($this->model->getFolderAccessId(0, 'single')) {
                $sql .= " AND `folderaccess`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getFolderAccessId(0, 'single') . "'";
            }
            if ($this->model->getRoleId()) {
                $sql .= " AND `folderaccess`.`roleId`='" . $this->model->getRoleId() . "'";
            }
            if ($this->model->getApplicationId()) {
                $sql .= " AND `folder`.`applicationId`='" . $this->model->getApplicationId() . "'";
            }
            if ($this->model->getModuleId()) {
                $sql .= " AND `folder`.`moduleId`='" . $this->model->getModuleId() . "'";
            }
            if ($this->model->getFolderId()) {
                $sql .= " AND `folderaccess`.`folderId`='" . $this->model->getFolderId() . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
            SELECT  [folderaccess].[folderAccessId],
                    [company].[companyDescription],
                    [folderaccess].[companyId],
                    [role].[roleDescription],
                    [folderAccess].[roleId],
                    [application].[applicationEnglish],
                    [module].[moduleEnglish],
                    [folder].[folderEnglish],
                    [folderaccess].[folderId],
                    [folderaccess].[folderAccessValue]
            FROM 	[folderAccess]

            JOIN	[company]
            ON		[company].[companyId]           =   [folderaccess].[companyId]

            JOIN	[role]
            ON		[role].[roleId]                 =   [folderaccess].[roleId]
            AND     [role].[companyId]              =   [folderaccess].[companyId]

            JOIN	[folder]
            ON		[folder].[folderId]             =   [folderaccess].[folderId]
            AND     [folder].[companyId]            =   [folderaccess].[companyId]

            JOIN    [module]
            ON      [module].[companyId]            =   [folderaccess].[companyId]
            AND     [module].[moduleId]             =   [folder].[moduleId]

            JOIN    [application]
            ON      [application].[companyId]       =   [folderaccess].[companyId]
            AND     [application].[applicationId]   =   [folder].[applicationId]


            WHERE     " . $this->getAuditFilter();
                if ($this->model->getFolderAccessId(0, 'single')) {
                    $sql .= " AND [folderAccess].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getFolderAccessId(0, 'single') . "'";
                }
                if ($this->model->getRoleId()) {
                    $sql .= " AND [folderaccess].[roleId]='" . $this->model->getRoleId() . "'";
                }
                if ($this->model->getApplicationId()) {
                    $sql .= " AND [folder].[applicationId]='" . $this->model->getApplicationId() . "'";
                }
                if ($this->model->getModuleId()) {
                    $sql .= " AND [folder].[moduleId]='" . $this->model->getModuleId() . "'";
                }
                if ($this->model->getFolderId()) {
                    $sql .= " AND [folderaccess].[folderId]='" . $this->model->getFolderId() . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
            SELECT  FOLDERACCESS.FOLDERACCESSID     AS  \"folderAccessId\",
                    COMPANY.COMPANYDESCRIPTION      AS  \"companyDescription\",
                    FOLDERACCESS.COMPANYID          AS  \"companyId\",
                    ROLE.ROLEDESCRIPTION            AS  \"roleDescription\",
                    FOLDERACCESS.ROLEID             AS  \"roleId\",
                    APPLICATION.APPLICATIONENGLISH  AS  \"applicationEnglish\",
                    MODULE.MODULEENGLISH            AS  \"moduleEnglish\",
                    FOLDER.FOLDERENGLISH            AS  \"folderEnglish\",
                    FOLDERACCESS.FOLDERID           AS  \"folderId\",
                    FOLDERACCESS.FOLDERACCESSVALUE  AS  \"folderAccessValue\"
            FROM 	FOLDERACCESS

            JOIN	COMPANY
            ON		COMPANY.COMPANYID           =   FOLDERACCESS.COMPANYID

            JOIN	ROLE
            ON		ROLE.ROLEID                 =   FOLDERACCESS.ROLEID
            AND     ROLE.COMPANYID              =   FOLDERACCESS.COMPANYID

            JOIN	FOLDER
            ON		FOLDER.FOLDERID             =   FOLDERACCESS.FOLDERID
            AND     FOLDER.COMPANYID            =   FOLDERACCESS.COMPANYID

            JOIN    MODULE
            ON		MODULE.MODULEID             =   FOLDER.MODULEID
            AND     MODULE.COMPANYID            =   FOLDERACCESS.COMPANYID

            JOIN    APPLICATION
            ON		APPLICATION.APPLICATIONID   =   FOLDER.APPLICATIONID
            AND     APPLICATION.COMPANYID       =   FOLDERACCESS.COMPANYID


            WHERE     " . $this->getAuditFilter();
                    if ($this->model->getFolderAccessId(0, 'single')) {
                        $sql .= " AND FOLDERACCESS. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getFolderAccessId(0, 'single') . "'";
                    }
                    if ($this->model->getRoleId()) {
                        $sql .= " AND FOLDERACCESS.ROLEID='" . $this->model->getRoleId() . "'";
                    }
                    if ($this->model->getApplicationId()) {
                        $sql .= " AND FOLDER.APPLICATIONID='" . $this->model->getApplicationId() . "'";
                    }
                    if ($this->model->getModuleId()) {
                        $sql .= " AND FOLDER.MODULEID='" . $this->model->getModuleId() . "'";
                    }
                    if ($this->model->getFolderId()) {
                        $sql .= " AND FOLDERACCESS.FOLDERID='" . $this->model->getFolderId() . "'";
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
                $sql .= " AND `folderaccess`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [folderAccess].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND Initcap(FOLDERACCESS." . strtoupper(
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
                        'folderaccess', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'folderAccess', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'FOLDERACCESS', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
                "`folderaccess`.`folderAccessId`",
                "`staff`.`staffPassword`"
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $filterArray = array(
                    "[folderaccess].[folderAccessId]",
                    "[staff].[staffPassword]"
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $filterArray = array(
                        "FOLDERACCESS.FOLDERACCESSID",
                        "STAFF.STAFFPASSWORD"
                    );
                }
            }
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('staff', 'folderaccess', 'company', 'role', 'folder');
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array('staff', 'folderaccess', 'company', 'role', 'folder');
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array('STAFF', 'FOLDERACCESS', 'COMPANY', 'ROLE', 'FOLDER');
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
        if (!($this->model->getFolderAccessId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 4;
            if ($this->model->getFolderAccessId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getFolderAccessId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getFolderAccessId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else {
            if ($this->getPageOutput() == 'table') {

                $i = 0;
                $str = null;
                foreach ($items as $data) {
                    $i++;
                    $str .= "<tr>
                            <td width=\"50px\">" . $i . "</td>
                            <td>" . $data['roleDescription'] . "</td>
                            <td>" . $data['applicationEnglish'] . "</td>
                            <td>" . $data['moduleEnglish'] . "</td>
                            <td>" . $data['folderEnglish'] . "</td>
                            ";
                    if ($data['folderAccessValue'] == 1) {
                        $str .= "<td align=\"left\"><img src='./images/icons/tick.png'></td>";
                    } else {
                        $str .= "<td align=\"left\"><img src='./images/icons/burn.png'></td>";
                    }
                    if ($data['folderAccessValue']) {
                        $checked = 'checked';
                    } else {
                        $checked = null;
                    }
                    $str .= "<td>
    <input style='display:none;' type=\"checkbox\" name='folderAccessId[]' id='folderAccessId' value='" . $data['folderAccessId'] . "'>
    <input " . $checked . " type=\"checkbox\" name='folderAccessValue[]' id='folderAccessValue' value='" . $data['folderAccessValue'] . "'>

</td>";
                    $str .= "</tr>";
                }
                echo json_encode(array("success" => true, "data" => $str, "message" => "complete"));
                exit();
            } else {
                if ($this->getPageOutput() == 'json') {
                    if ($this->model->getFolderAccessId(0, 'single')) {
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
                                                    'value', $this->model->getFolderAccessId(0, 'single')
                                            ),
                                            'nextRecord' => $this->nextRecord(
                                                    'value', $this->model->getFolderAccessId(0, 'single')
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
                                            'value', $this->model->getFolderAccessId(0, 'single')
                                    ),
                                    'nextRecord' => $this->recordSet->nextRecord(
                                            'value', $this->model->getFolderAccessId(0, 'single')
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
        $this->model->update();

        $loop = $this->model->getTotal();
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			UPDATE 	`" . $this->model->getTableName() . "`
			SET     `folderAccessValue`			=	case `" . $this->model->getPrimaryKeyName() . "` ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			UPDATE 	[" . $this->model->getTableName() . "]
			SET     [folderAccessValue]			=	case [" . $this->model->getPrimaryKeyName() . "] ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			UPDATE 	" . strtoupper($this->model->getTableName()) . "
			SET     FOLDERACCESSVALUE			=	case " . strtoupper($this->model->getPrimaryKeyName()) . " ";
        }
        for ($i = 0; $i < $loop; $i++) {
            $sql .= "
				WHEN " . $this->model->getFolderAccessId($i, 'array') . "
				THEN " . $this->model->getFolderAccessValue($i, 'array') . "";
        }
        $sql .= "	END ";
        if ($this->getVendor() == self::MYSQL) {
            $sql .= " WHERE 	`" . $this->model->getPrimaryKeyName(
                    ) . "`		IN	(" . $this->model->getPrimaryKeyAll() . ")";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql .= " WHERE 	[" . $this->model->getPrimaryKeyName(
                    ) . "]		IN	(" . $this->model->getPrimaryKeyAll() . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql .= " WHERE 	" . strtoupper(
                            $this->model->getPrimaryKeyName()
                    ) . "		IN	(" . $this->model->getPrimaryKeyAll() . ")";
        } else {
            
        }
        $this->q->setPrimaryKeyAll($this->model->getPrimaryKeyAll());
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
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
     * Return Role Data
     * @return array|string
     */
    public function getRole() {
        $this->service->setServiceOutput('html');
        return $this->service->getRole();
    }

    /**
     * Return Application Data
     * @return string
     */
    public function getApplication() {
        $this->service->setServiceOutput('html');
        return $this->service->getApplication();
    }

    /**
     * Return Module Data
     * @return mixed
     */
    public function getModule() {
        // if filter mean outside variable
        if ($this->getApplicationId()) {
            $this->service->setServiceOutput('option');
            echo $this->service->getModule($this->getApplicationId());
        } else {
            $this->service->setServiceOutput('html');
            return $this->service->getModule();
        }
        return false;
    }

    /**
     * Return Folder Data
     * @return mixed
     */
    public function getFolder() {
        // if filter mean outside variable
        if ($this->getApplicationId() && $this->getModuleId()) {
            $this->service->setServiceOutput('option');
            echo $this->service->getFolder($this->getApplicationId(), $this->getModuleId());
        } else {
            $this->service->setServiceOutput('html');
            return $this->service->getFolder();
        }
        return false;
    }

    /**
     * Return Application Primary Key
     * @return int
     */
    public function getApplicationId() {
        return $this->applicationId;
    }

    /**
     * Set Application Primary Key
     * @param int $value
     */
    public function setApplicationId($value) {
        $this->applicationId = $value;
    }

    /**
     * Return Module Primary Key
     * @return int
     */
    public function getModuleId() {
        return $this->moduleId;
    }

    /**
     * Set Module Primary Key
     * @param int $value
     */
    public function setModuleId($value) {
        $this->moduleId = $value;
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
        $folderAccessObject = new FolderAccessClass ();
        if ($_POST['securityToken'] != $folderAccessObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $folderAccessObject->setLeafId($_POST ['leafId']);
        }
        $folderAccessObject->setPageOutput($_POST['output']);
        $folderAccessObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $folderAccessObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $folderAccessObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $folderAccessObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $folderAccessObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$folderAccessObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$folderAccessObject->delete(); 
        }
    }
}
if (isset($_GET ['method'])) {
    $folderAccessObject = new FolderAccessClass ();
    if ($_GET['securityToken'] != $folderAccessObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $folderAccessObject->setLeafId($_GET ['leafId']);
    }
    /*
     * Admin Only
     */
    if (isset($_GET ['isAdmin'])) {
        $folderAccessObject->setIsAdmin($_GET ['isAdmin']);
    }
    /**
     * Database Request
     */
    if (isset($_GET ['databaseRequest'])) {
        $folderAccessObject->setRequestDatabase($_GET ['databaseRequest']);
    }
    if (isset($_GET['applicationId'])) {
        $folderAccessObject->setApplicationId($_GET['applicationId']);
    }
    if (isset($_GET['moduleId'])) {
        $folderAccessObject->setModuleId($_GET['moduleId']);
    }
    /*
     *  Load the dynamic value
     */
    $folderAccessObject->execute();
    /**
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'update') {
        $folderAccessObject->update();
    }
    /*
     *  Checking Any Duplication  Key 
     */
    if (isset($_GET ['folderaccessCode'])) {
        if (strlen($_GET ['folderaccessCode']) > 0) {
            $folderAccessObject->duplicate();
        }
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $folderAccessObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $folderAccessObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $folderAccessObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $folderAccessObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $folderAccessObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $folderAccessObject->excel();
        }
    }
    if (isset($_GET['roleId'])) {
        $folderAccessObject->getRole();
    }
    if (isset($_GET['applicationId'])) {
        $folderAccessObject->getApplication();
    }
    if (isset($_GET['filter'])) {

        if ($_GET['filter'] == 'moduleId') {
            $folderAccessObject->getModule();
        }
        if ($_GET['filter'] == 'folderId') {
            $folderAccessObject->getFolder();
        }
    }
}
?>