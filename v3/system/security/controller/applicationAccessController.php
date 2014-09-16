<?php

namespace Core\System\Security\ApplicationAccess\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\RecordSet\RecordSet;
use Core\shared\SharedClass;
use Core\System\Security\ApplicationAccess\Model\ApplicationAccessModel;
use Core\System\Security\ApplicationAccess\Service\ApplicationAccessService;

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
if (!isset($_SESSION)) {
    session_start();
}
require_once($newFakeDocumentRoot . "library/class/classAbstract.php");
require_once($newFakeDocumentRoot . "library/class/classRecordSet.php");
require_once($newFakeDocumentRoot . "library/class/classDate.php");
require_once($newFakeDocumentRoot . "library/class/classDocumentTrail.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
require_once($newFakeDocumentRoot . "v3/system/document/model/documentModel.php");
require_once($newFakeDocumentRoot . "v3/system/security/model/applicationAccessModel.php");
require_once($newFakeDocumentRoot . "v3/system/security/service/applicationAccessService.php");

/**
 * Class ApplicationAccessClass
 * this is Application Access Controller files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Security\ApplicationAccess\Controller
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ApplicationAccessClass extends ConfigClass {

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
     * @var  \Core\System\Security\ApplicationAccess\Model\ApplicationAccessModel
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request
     * @var  \Core\System\Security\ApplicationAccess\Service\ApplicationAccessService
     */
    public $service;

    /**
     * Translation Array
     * @var string
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
        $this->setViewPath("./v3/system/security/view/applicationAccess.php");
        $this->setControllerPath("./v3/system/security/controller/applicationAccessController.php");
        $this->setServicePath("./v3/system/security/service/applicationAccessService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(0);
        $this->setLog(1);
        $this->model = new ApplicationAccessModel();
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

        $this->service = new ApplicationAccessService();
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
                    $this->setAuditFilter(
                            " `applicationaccess`.`isActive` = 1 AND `applicationaccess`.`companyId`	= '" . $this->getCompanyId(
                            ) . "'"
                    );
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [applicationAccess].[isActive] = 1 AND [applicationAccess].[companyId]	= '" . $this->getCompanyId(
                                ) . "'"
                        );
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(
                                    " APPLICATIONACCESS.ISACTIVE = 1 AND APPLICATIONACCESS.COMPANYID	= '" . $this->getCompanyId(
                                    ) . "'"
                            );
                        }
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter(
                                " `applicationaccess`.`companyId`	= '" . $this->getCompanyId() . "'	"
                        );
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(
                                    " [applicationAccess].[companyId]	= '" . $this->getCompanyId() . "' "
                            );
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(
                                        " APPLICATIONACCESS.COMPANYID	= '" . $this->getCompanyId() . "' "
                                );
                            }
                        }
                    }
                }
            }
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "something wrong with system"));
            exit();
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {

            $sql = "
            SELECT  `applicationaccess`.`applicationAccessId`,
                    `application`.`applicationEnglish`,
                    `role`.`roleDescription`,
                    `applicationaccess`.`applicationAccessValue`
            FROM    `applicationaccess`
            JOIN    `application`
            ON      `application`.`applicationId` 	= 	`applicationaccess`.`applicationId`
			AND		`application`.`companyId`		=	`applicationaccess`.`companyId`
            JOIN    `role`
            ON      `role`.`roleId` 				= 	`applicationaccess`.`roleId`
			AND		`role`.`companyId`				=	`applicationaccess`.`companyId`
            WHERE   " . $this->getAuditFilter();
            if ($this->model->getApplicationAccessId(0, 'single')) {
                $sql .= " AND `applicationaccess`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getApplicationAccessId(0, 'single') . "'";
            }
            if ($this->model->getRoleId()) {
                $sql .= " AND `role`.`roleId`='" . $this->model->getRoleId() . "'";
            }
            if ($this->model->getApplicationId()) {
                $sql .= " AND `application`.`applicationId`='" . $this->model->getApplicationId() . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
            SELECT  [applicationAccess].[applicationAccessId],
                    [application].[applicationEnglish],
                    [role].[roleDescription],
                    [applicationAccess].[applicationAccessValue]
            FROM    [applicationAccess]
			AND		[applicationAccess].[companyId]	= 	[staff].[companyId]
            JOIN    [application]
            ON      [application].[applicationId] 	= 	[applicationAccess].[applicationId]
			AND		[application].[companyId]	  	=	[applicationAccess].[companyId]
            JOIN    [role]
            ON      [role].[roleId] 				= 	[applicationAccess].[roleId]
			AND		[role].[companyId] 			  	=	[applicationAccess].[companyId]
            WHERE   " . $this->getAuditFilter();
                if ($this->model->getApplicationAccessId(0, 'single')) {
                    $sql .= " AND [applicationAccess].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getApplicationAccessId(0, 'single') . "'";
                }
                if ($this->model->getRoleId()) {
                    $sql .= " AND [role].[roleId]		=	'" . $this->model->getRoleId() . "'";
                }
                if ($this->model->getApplicationId()) {
                    $sql .= " AND [application].[applicationId]='" . $this->model->getApplicationId() . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
            SELECT  APPLICATIONACCESS.APPLICATIONACCESSID 		AS 	\"applicationAccessId\",
                    APPLICATION.APPLICATIONID 					AS 	\"applicationId\",
                    APPLICATION.APPLICATIONENGLISH              AS  \"applicationEnglish\",
                    ROLE.ROLEDESCRIPTION 						AS 	\"roleDescription\",
                    APPLICATIONACCESS.ROLEID 					AS 	\"roleId\",
                    APPLICATIONACCESS.APPLICATIONACCESSVALUE	AS	\"applicationAccessValue\"
            FROM    APPLICATIONACCESS
			JOIN	COMPANY
			ON		APPLICATIONACCESS.COMPANYID	= 	COMPANY.COMPANYID
            JOIN    APPLICATION
            ON      APPLICATION.APPLICATIONID 	= 	APPLICATIONACCESS.APPLICATIONID
			AND		APPLICATION.COMPANYID		=	APPLICATIONACCESS.COMPANYID
            JOIN    ROLE
            ON      ROLE.ROLEID 				=	APPLICATIONACCESS.ROLEID
			AND		ROLE.COMPANYID				=	APPLICATIONACCESS.COMPANYID
            WHERE   " . $this->getAuditFilter();
                    if ($this->model->getApplicationAccessId(0, 'single')) {
                        $sql .= " AND APPLICATIONACCESS. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getApplicationAccessId(0, 'single') . "'";
                    }
                    if ($this->model->getRoleId()) {
                        $sql .= " AND	ROLE.ROLEID	=	'" . $this->model->getRoleId() . "'";
                    }
                    if ($this->model->getApplicationId()) {
                        $sql .= " AND	APPLICATION.APPLICATIONID='" . $this->model->getApplicationId() . "'";
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
                $sql .= " AND `applicationaccess`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [applicationAccess].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND Initcap(APPLICATIONACCESS." . strtoupper(
                                        $this->model->getFilterCharacter()
                                ) . ") like Initcap('" . $this->getCharacterQuery() . "%');";
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
                        'applicationaccess', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'applicationaccess', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'APPLICATIONACCESS', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
        $filterArray = array('applicationAccessId');
        /**
         * filter table
         * @variables $tableArray
         */
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('applicationAccess');
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array('applicationAccess');
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array('APPLICATIONACCESS');
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
                $sqlDerived = $sql . "
                LIMIT   " . $this->getStart() . ",
                        " . $this->getLimit() . " ";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sqlDerived = $sql . "
                OFFSET          " . $this->getStart() . "   ROWS
                FETCH NEXT      " . $this->getLimit() . "   ROWS ONLY";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        /**
                         * Oracle using derived table also
                         * */
                        $sqlDerived = "
                SELECT *
                FROM    (
                            SELECT  a.*,
                                    rownum r
                            FROM (
                                    " . $sql . "
                            ) a
                            WHERE   rownum <= '" . ($this->getStart() + $this->getLimit()) . "'
                        )
                WHERE   r >=  '" . ($this->getStart() + 1) . "'";
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
        if (!($this->model->getApplicationAccessId(0, 'single'))) {
            try {
                $this->q->read($sqlDerived);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        if (isset($_SESSION['isDebug']) == 1) {
            $this->exceptionMessage($sqlDerived);
        }
        $items = array();
        $i = 1;
        while (($row = $this->q->fetchAssoc()) == true) {
            $row['total'] = $total; // small override 
            $row['counter'] = $this->getStart() + 4;
            if ($this->model->getApplicationAccessId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord(
                        'value', $this->model->getApplicationAccessId(0, 'single')
                );
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getApplicationAccessId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else {
            if ($this->getPageOutput() == 'table') {
                $str = null;
                $i = 0;
                $oldRoleDescription = null;
                $roleDescription = null;
                foreach ($items as $data) {
                    $i++;
                    if ($oldRoleDescription != $data['roleDescription']) {
                        $roleDescription = $data['roleDescription'];
                    } else {
                        $roleDescription = "&nbsp;";
                    }
                    $str .= "<tr>
                            <td align=\"center\"><div align=\"center\">" . $i . "</div></td>
                            <td align=\"left\"><div align=\"left\">" . $roleDescription . "</div></td>";
                    if ($data['applicationAccessValue'] == 1) {
                        $str .= "<td align=center><div align=\"center\"><img src='./images/icons/tick.png'></div></td>";
                    } else {
                        $str .= "<td align=center><div align=\"center\"><img src='./images/icons/burn.png'></div></td>";
                    }
                    $str .= "<td align=\"left\"><div align=\"left\"> " . $data['applicationEnglish'] . "</div></td>
                            ";
                    $oldRoleDescription = $data['roleDescription'];

                    if ($data['applicationAccessValue']) {
                        $checked = 'checked';
                    } else {
                        $checked = null;
                    }
                    $str .= "<td>
    <input style='display:none;' type=\"checkbox\" name='applicationAccessId[]' id='applicationAccessId' value='" . $data['applicationAccessId'] . "'>
    <input " . $checked . " type=\"checkbox\" name='applicationAccessValue[]' id='applicationAccessValue' value='" . $data['applicationAccessValue'] . "'>

</td>";
                    $str .= "</tr>";
                }
                echo json_encode(array("success" => true, "data" => $str, "message" => "success", "sql" => $sql));
                exit();
            } else {
                if ($this->getPageOutput() == 'json') {
                    if ($this->model->getApplicationAccessId(0, 'single')) {
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
                                                    'value', $this->model->getApplicationAccessId(0, 'single')
                                            ),
                                            'nextRecord' => $this->nextRecord(
                                                    'value', $this->model->getApplicationAccessId(0, 'single')
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
                                            'value', $this->model->getApplicationAccessId(0, 'single')
                                    ),
                                    'nextRecord' => $this->recordSet->nextRecord(
                                            'value', $this->model->getApplicationAccessId(0, 'single')
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
            UPDATE  `" . $this->model->getTableName() . "`
            SET     `applicationAccessValue`    =   case `" . $this->model->getPrimaryKeyName() . "` ";
            for ($i = 0; $i < $loop; $i++) {
                $sql .= "
                WHEN '" . $this->model->getApplicationAccessId($i, 'array') . "'
                THEN '" . $this->model->getApplicationAccessValue($i, 'array') . "'";
            }
            $sql .= "	END ";
            $sql .= " WHERE 	`" . $this->model->getPrimaryKeyName(
                    ) . "`		IN	(" . $this->model->getPrimaryKeyAll() . ")";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE  [" . $this->model->getTableName() . "]
            SET     [applicationAccessValue]    =   case [" . $this->model->getPrimaryKeyName() . "] ";
            for ($i = 0; $i < $loop; $i++) {
                $sql .= "
                WHEN '" . $this->model->getApplicationAccessId($i, 'array') . "'
                THEN '" . $this->model->getApplicationAccessValue($i, 'array') . "'";
            }
            $sql .= "	END ";
            $sql .= " WHERE 	[" . $this->model->getPrimaryKeyName(
                    ) . "]		IN	(" . $this->model->getPrimaryKeyAll() . ")";
        } elseif ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE  " . strtoupper($this->model->getTableName()) . "
            SET     APPLICATIONACCESSVALUE    =   case " . strtoupper($this->model->getPrimaryKeyName()) . " ";
            for ($i = 0; $i < $loop; $i++) {
                $sql .= "
                WHEN " . $this->strict($this->model->getApplicationAccessId($i, 'array'), 'numeric') . "
                THEN " . $this->strict($this->model->getApplicationAccessValue($i, 'array'), 'numeric') . "";
            }
            $sql .= "	END ";
            $sql .= " WHERE 	" . strtoupper(
                            $this->model->getPrimaryKeyName()
                    ) . "		IN	(" . $this->model->getPrimaryKeyAll() . ")";
        }
        $this->q->setPrimaryKeyAll($this->model->getPrimaryKeyAll());
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            $this->q->close();
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
                    "time" => $time,
                    "sql" => $sql
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
     * Return Application Data
     * @return mixed
     */
    public function getApplication() {
        return $this->service->getApplication();
    }

    /**
     * Return Role Data
     * @return mixed
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

/**
 * crud -create,read,update,delete
 * */
if (isset($_POST ['method'])) {
    if (isset($_POST['output'])) {
        $applicationAccessObject = new ApplicationAccessClass ();
        if ($_POST['securityToken'] != $applicationAccessObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $applicationAccessObject->setLeafId($_POST ['leafId']);
        }

        $applicationAccessObject->setPageOutput($_POST['output']);
        $applicationAccessObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $applicationAccessObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $applicationAccessObject->update();
        }
        if ($_POST ['method'] == 'read') {

            $applicationAccessObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $applicationAccessObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$applicationAccessObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$applicationAccessObject->delete(); 
        }
    }
}
if (isset($_GET ['method'])) {
    $applicationAccessObject = new ApplicationAccessClass();
    if ($_GET['securityToken'] != $applicationAccessObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $applicationAccessObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $applicationAccessObject->execute();
    /**
     * Update Status of The Table. Administrator Level Only
     */
    if ($_GET ['method'] == 'update') {
        $applicationAccessObject->update();
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $applicationAccessObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $applicationAccessObject->excel();
        }
    }
    if (isset($_GET['applicationId'])) {
        $applicationAccessObject->getApplication();
    }
    if (isset($_GET['roleId'])) {
        $applicationAccessObject->getRole();
    }
}
?>