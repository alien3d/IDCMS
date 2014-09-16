<?php

namespace Core\System\Security\ModuleAccess\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\RecordSet\RecordSet;
use Core\shared\SharedClass;
use Core\System\Security\ModuleAccess\Model\ModuleAccessModel;
use Core\System\Security\ModuleAccess\Service\ModuleAccessService;

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
require_once($newFakeDocumentRoot . "v3/system/security/model/moduleAccessModel.php");
require_once($newFakeDocumentRoot . "v3/system/security/service/moduleAccessService.php");

/**
 * Class ModuleAccessClass
 * this is module access setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Security\ModuleAccess\Controller
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ModuleAccessClass extends ConfigClass {

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
     * @var \Core\System\Security\ModuleAccess\Model\ModuleAccessModel
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\System\Security\ModuleAccess\Service\ModuleAccessService
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
        $this->setViewPath("./v3/system/security/view/moduleAccess.php");
        $this->setControllerPath("./v3/system/security/controller/moduleAccessController.php");
        $this->setServicePath("./v3/system/security/service/moduleAccessService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(0);
        $this->setLog(1);
        $this->model = new ModuleAccessModel();
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

        $this->service = new ModuleAccessService();
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
        $this->setLimit(99999);

        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] == 0) {
                if ($this->getVendor() == self::MYSQL) {
                    $this->setAuditFilter(
                            " `moduleaccess`.`isActive` = 1  AND `moduleaccess`.`companyId`='" . $this->getCompanyId(
                            ) . "' "
                    );
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [moduleAccess].[isActive] = 1 AND [moduleAccess].[companyId]='" . $this->getCompanyId(
                                ) . "' "
                        );
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(
                                    " MODULEACCESS.ISACTIVE = 1  AND MODULEACCESS.COMPANYID='" . $this->getCompanyId() . "'"
                            );
                        }
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter("   `moduleaccess`.`companyId`='" . $this->getCompanyId() . "'	");
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(" [moduleAccess].[companyId]='" . $this->getCompanyId() . "' ");
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(" MODULEACCESS.COMPANYID='" . $this->getCompanyId() . "' ");
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
            SELECT  `moduleaccess`.`moduleAccessId`,
                    `company`.`companyDescription`,
                    `moduleaccess`.`companyId`,
                    `application`.`applicationEnglish`,
                    `module`.`moduleEnglish`,
                    `moduleaccess`.`moduleId`,
                    `role`.`roleDescription`,
                    `moduleaccess`.`roleId`,
                    `moduleaccess`.`moduleAccessValue`
            FROM    `moduleaccess`

            JOIN	`company`
            USING   (`companyId`)

            JOIN	`module`
            USING   (`companyId`,`moduleId`)

            JOIN	`role`
            USING   (`companyId`,`roleId`)

            JOIN    `application`
            ON      `moduleaccess`.`companyId` = `application`.`companyId`
            AND     `module`.`moduleId`        = `application`.`applicationId`

            WHERE     " . $this->getAuditFilter();
            if ($this->model->getModuleAccessId(0, 'single')) {
                $sql .= " AND `moduleaccess`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getModuleAccessId(0, 'single') . "'";
            }
            if ($this->model->getApplicationId()) {
                $sql .= " AND `module`.`applicationId`='" . $this->model->getApplicationId() . "'";
            }
            if ($this->model->getModuleId()) {
                $sql .= " AND `module`.`moduleId`='" . $this->model->getModuleId() . "'";
            }
            if ($this->model->getRoleId()) {
                $sql .= " AND `moduleaccess`.`roleId`='" . $this->model->getRoleId() . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {

            $sql = "
            SELECT  [moduleAccess].[moduleAccessId],
                    [company].[companyDescription],
                    [moduleAccess].[companyId],
                    [application].[applicationEnglish],
                    [module].[moduleEnglish],
                    [moduleAccess].[moduleId],
                    [role].[roleDescription],
                    [moduleAccess].[roleId],
                    [moduleAccess].[moduleAccessValue]

            FROM 	[moduleAccess]

            JOIN	[company]
            ON		[company].[companyId]       =   [moduleAccess].[companyId]

            JOIN	[module]
            ON		[module].[moduleId]         =   [moduleAccess].[moduleId]
            AND     [module].[companyId]        =   [moduleAccess].[companyId]

            JOIN	[role]
            ON		[role].[roleId]             =   [moduleAccess].[roleId]
            AND     [role].[companyId]          =   [moduleAccess].[companyId]

            JOIN    [application]
            ON      [moduleAccess].[companyId]  =   [application].[companyId]
            AND     [module].[applicationId]    =   [application].[applicationId]

            WHERE     " . $this->getAuditFilter();
            if ($this->model->getModuleAccessId(0, 'single')) {
                $sql .= " AND [moduleAccess].[" . $this->model->getPrimaryKeyName(
                        ) . "]		=	'" . $this->model->getModuleAccessId(0, 'single') . "'";
            }
            if ($this->model->getApplicationId()) {
                $sql .= " AND [module].[applicationId]='" . $this->model->getApplicationId() . "'";
            }
            if ($this->model->getModuleId()) {
                $sql .= " AND [module].[moduleId]='" . $this->model->getModuleId() . "'";
            }
            if ($this->model->getRoleId()) {
                $sql .= " AND [moduleAccess].[roleId]='" . $this->model->getRoleId() . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {

            $sql = "
				SELECT  MODULEACCESS.MODULEACCESSID     AS  \"moduleAccessId\",
						COMPANY.COMPANYDESCRIPTION      AS  \"companyDescription\",
						MODULEACCESS.COMPANYID          AS  \"companyId\",
						APPLICATION.APPLICATIONENGLISH  AS  \"applicationEnglish\",
						MODULE.MODULEENGLISH            AS  \"moduleEnglish\",
						MODULEACCESS.MODULEID           AS  \"moduleId\",
						ROLE.ROLEDESCRIPTION            AS  \"roleDescription\",
						MODULEACCESS.ROLEID             AS  \"roleId\",
						MODULEACCESS.MODULEACCESSVALUE  AS  \"moduleAccessValue\"
				FROM 	MODULEACCESS
				JOIN	COMPANY
				ON		COMPANY.COMPANYID       =   MODULEACCESS.COMPANYID

				JOIN	MODULE
				ON		MODULE.MODULEID         =   MODULEACCESS.MODULEID
				AND     MODULE.COMPANYID        =   MODULEACCESS.COMPANYID

				JOIN	ROLE
				ON		ROLE.ROLEID             =   MODULEACCESS.ROLEID
				AND     ROLE.COMPANYID          =   MODULEACCESS.COMPANYID

				JOIN    APPLICATION
				ON      APPLICATION.COMPANYID   = MODULEACCESS.COMPANYID
				AND     APPLICATION.APPLICATIONID = MODULE.APPLICATIONID

				WHERE	" . $this->getAuditFilter();
            if ($this->model->getModuleAccessId(0, 'single')) {
                $sql .= " 
					AND MODULEACCESS. " . strtoupper(
                                $this->model->getPrimaryKeyName()
                        ) . "='" . $this->model->getModuleAccessId(0, 'single') . "'";
            }
            if ($this->model->getApplicationId()) {
                $sql .= " 
					AND MODULE.APPLICATIONID='" . $this->model->getApplicationId() . "'";
            }
            if ($this->model->getModuleId()) {
                $sql .= " 
					AND MODULE.MODULEID='" . $this->model->getModuleId() . "'";
            }
            if ($this->model->getRoleId()) {
                $sql .= " 
					AND MODULEACCESS.ROLEID='" . $this->model->getRoleId() . "'";
            }
        }
        /**
         * filter column based on first character
         */
        if ($this->getCharacterQuery()) {
            if ($this->getVendor() == self::MYSQL) {
                $sql .= " AND `moduleaccess`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [moduleAccess].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND Initcap(MODULEACCESS." . strtoupper(
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
                        'moduleaccess', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'moduleAccess', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'MODULEACCESS', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
                "`moduleaccess`.`moduleAccessId`",
                "`staff`.`staffPassword`"
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $filterArray = array(
                    "[moduleAccess].[moduleAccessId]",
                    "[staff].[staffPassword]"
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $filterArray = array(
                        "MODULEACCESS.MODULEACCESSID",
                        "STAFF.STAFFPASSWORD"
                    );
                }
            }
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('staff', 'moduleaccess', 'company', 'module', 'role');
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array('staff', 'moduleaccess', 'company', 'module', 'role');
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array('STAFF', 'MODULEACCESS', 'COMPANY', 'MODULE', 'ROLE');
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
        //$this->exceptionMessage($sql);
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

            if ($this->getVendor() == self::MYSQL) {

                $sqlDerived = $sql . " LIMIT  " . $this->getStart() . "," . $this->getLimit() . " ";
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
        if (!($this->model->getModuleAccessId(0, 'single'))) {
            try {
                $this->q->read($sqlDerived);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage(), "sqlDerived" => $sqlDerived));
                exit();
            }
        }
        $items = array();
        $i = 1;
        while (($row = $this->q->fetchAssoc()) == true) {
            $row['total'] = $total; // small override 
            $row['counter'] = $this->getStart() + 4;
            if ($this->model->getModuleAccessId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getModuleAccessId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getModuleAccessId(0, 'single'));
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
                            <td>" . $i . "</td>
                            <td>" . $data['roleDescription'] . "</td>
                            <td>" . $data['applicationEnglish'] . "</td>
                            <td>" . $data['moduleEnglish'] . "</td>";
                    if ($data['moduleAccessValue'] == 1) {
                        $str .= "<td align=\"left\"><img src='./images/icons/tick.png'></td>";
                    } else {
                        $str .= "<td align=\"left\"><img src='./images/icons/burn.png'></td>";
                    }
                    if ($data['moduleAccessValue']) {
                        $checked = 'checked';
                    } else {
                        $checked = null;
                    }
                    $str .= "<td>
    <input style='display:none;' type=\"checkbox\" name='moduleAccessId[]' id='moduleAccessId' value='" . $data['moduleAccessId'] . "'>
    <input " . $checked . " type=\"checkbox\" name='moduleAccessValue[]' id='moduleAccessValue' value='" . $data['moduleAccessValue'] . "'>

</td>";
                    $str .= "</tr>";
                }
                echo json_encode(array("success" => true, "data" => $str, "message" => "complete"));
                exit();
            } else {
                if ($this->getPageOutput() == 'json') {
                    if ($this->model->getModuleAccessId(0, 'single')) {
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
                                                    'value', $this->model->getModuleAccessId(0, 'single')
                                            ),
                                            'nextRecord' => $this->nextRecord(
                                                    'value', $this->model->getModuleAccessId(0, 'single')
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
                                            'value', $this->model->getModuleAccessId(0, 'single')
                                    ),
                                    'nextRecord' => $this->recordSet->nextRecord(
                                            'value', $this->model->getModuleAccessId(0, 'single')
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
        header('Content-Type:module/json; charset=utf-8');
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
			SET 	";
            $sql .= "	   `moduleAccessValue`			=	case `" . $this->model->getPrimaryKeyName() . "` ";
            for ($i = 0; $i < $loop; $i++) {
                $sql .= "
				WHEN '" . $this->model->getModuleAccessId($i, 'array') . "'
				THEN '" . $this->model->getModuleAccessValue($i, 'array') . "'";
            }
            $sql .= "	END ";
            $sql .= " WHERE 	`" . $this->model->getPrimaryKeyName(
                    ) . "`		IN	(" . $this->model->getPrimaryKeyAll() . ")";
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
     * @return void
     */
    function delete() {
        
    }

    /**
     * To Update flag Status
     * @return void
     */
    function updateStatus() {
        
    }

    /**
     * To check if a key duplicate or not
     * @return void
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
        $this->service->setServiceOutput('html');
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
            $this->service->getModule($this->model->getApplicationId());
        } else {
            $this->service->setServiceOutput('html');
            return $this->service->getModule();
        }
    }

    /**
     * Return Role Data
     * @return mixed
     */
    public function getRole() {
        $this->service->setServiceOutput('html');
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
        $moduleAccessObject = new ModuleAccessClass ();
        if ($_POST['securityToken'] != $moduleAccessObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $moduleAccessObject->setLeafId($_POST ['leafId']);
        }
        $moduleAccessObject->setPageOutput($_POST['output']);
        $moduleAccessObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $moduleAccessObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $moduleAccessObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $moduleAccessObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $moduleAccessObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$moduleAccessObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$moduleAccessObject->delete(); 
        }
    }
}
if (isset($_GET ['method'])) {
    $moduleAccessObject = new ModuleAccessClass ();
    if ($_GET['securityToken'] != $moduleAccessObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $moduleAccessObject->setLeafId($_GET ['leafId']);
    }
    /**
     * Database Request
     */
    if (isset($_GET ['databaseRequest'])) {
        $moduleAccessObject->setRequestDatabase($_GET ['databaseRequest']);
    }
    /*
     *  Load the dynamic value
     */
    $moduleAccessObject->execute();

    /**
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'update') {

        $moduleAccessObject->update();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $moduleAccessObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $moduleAccessObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $moduleAccessObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $moduleAccessObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $moduleAccessObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $moduleAccessObject->excel();
        }
    }
    if (isset($_GET['filter'])) {
        $moduleAccessObject->setServiceOutput('option');
        if ($_GET['filter'] == 'applicationId') {

            $moduleAccessObject->getApplication();
        }
        if ($_GET['filter'] == 'moduleId') {
            $moduleAccessObject->getModule();
        }
    }
}
?>