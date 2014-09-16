<?php

namespace Core\Financial\GeneralLedger\ChartOfAccountAccess\Controller;

if (!isset($_SESSION)) {
    session_start();
}
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
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/model/chartOfAccountAccessModel.php");
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/service/chartOfAccountAccessService.php");
/**
 * this is chart of account access setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Financial
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */

/**
 * Class ChartOfAccountAccessClass
 * @package Core\Financial\GeneralLedger\ChartOfAccountAccess\Controller
 */
class ChartOfAccountAccessClass extends \Core\ConfigClass {

    /**
     * Connection to the database
     * @var string
     */
    public $q;

    /**
     * Php Word Generate Microsoft Excel 2007 Output.Format : docxs
     * @var string
     */
    //private $word;
    /**
     * Model
     * @var string
     */
    public $model;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     * @var string
     */
    //private $powerPoint;
    /**
     * Service-Business Application Process or other ajax request
     * @var string
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
     * @var mixed
     */
    public $t;

    /**
     * System Format
     * @var mixed
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
     * @var string
     */
    private $documentTrail;

    /**
     * Companyt
     * @var int
     */
    private $companyId;

    /**
     * chartOfAccountIdt
     * @var int
     */
    private $chartOfAccountId;

    /**
     * Constructor
     */
    function __construct() {
        $this->translate = array();
        $this->t = array();
        $this->leafAccess = array();
        $this->systemFormat = array();
        $this->setViewPath("./v3/financial/generalLedger/view/chartOfAccountAccess.php");
        $this->setControllerPath("./v3/financial/generalLedger/controller/chartOfAccountAccessController.php");
        $this->setServicePath("./v3/financial/generalLedger/service/chartOfAccountAccessService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(0);
        $this->setLog(1);
        $this->model = new \Core\Financial\GeneralLedger\ChartOfAccountAccess\Model\ChartOfAccountAccessModel();
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

        $translator = new \Core\shared\SharedClass();
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

        $this->service = new \Core\Financial\GeneralLedger\ChartOfAccountAccess\Service\ChartOfAccountAccessService();
        $this->service->q = $this->q;
        $this->service->t = $this->t;
        $this->service->setVendor($this->getVendor());
        $this->service->setServiceOutput($this->getServiceOutput());
        $this->service->execute();

        $this->recordSet = new \Core\RecordSet\RecordSet();
        $this->recordSet->q = $this->q;
        $this->recordSet->setCurrentTable($this->model->getTableName());
        $this->recordSet->setPrimaryKeyName($this->model->getPrimaryKeyName());
        $this->recordSet->execute();

        $this->documentTrail = new \Core\Document\Trail\DocumentTrailClass();
        $this->documentTrail->q = $this->q;
        $this->documentTrail->setVendor($this->getVendor());
        $this->documentTrail->setStaffId($this->getStaffId());
        $this->documentTrail->setLanguageId($this->getLanguageId());

        $this->documentTrail->setApplicationId($this->getApplicationId());
        $this->documentTrail->setModuleId($this->getModuleId());
        $this->documentTrail->setFolderId($this->getFolderId());
        $this->documentTrail->setLeafId($this->getLeafId());

        $this->documentTrail->execute();

        $this->systemFormat = new \Core\shared\SharedClass();
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
                            " `chartOfAccountAccess`.`isActive` = 1  AND `chartOfAccountAccess`.`companyId`='" . $this->getCompanyId(
                            ) . "' "
                    );
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [chartOfAccountAccess].[isActive] = 1 AND [chartOfAccountAccess].[companyId]='" . $this->getCompanyId(
                                ) . "' "
                        );
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(
                                    " ChartOfAccountAccess.ISACTIVE = 1  AND ChartOfAccountAccess.COMPANYID='" . $this->getCompanyId(
                                    ) . "'"
                            );
                        }
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter(
                                "   `chartOfAccountAccess`.`companyId`='" . $this->getCompanyId() . "'	"
                        );
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(
                                    " [chartOfAccountAccess].[companyId]='" . $this->getCompanyId() . "' "
                            );
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(
                                        " ChartOfAccountAccess.COMPANYID='" . $this->getCompanyId() . "' "
                                );
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
       SELECT                    `chartofaccountAccess`.`chartOfAccountAccessId`,
                    `company`.`companyDescription`,
                    `chartofaccountAccess`.`companyId`,
                    `chartofaccount`.`chartOfAccountTitle`,
                    `chartOfAccountAccess`.`chartOfAccountId`,
                    `staff`.`staffName`,
                    `chartofaccountAccess`.`staffId`,
                    `chartofaccountAccess`.`chartOfAccountAccessValue`,
                    `staff`.`staffName`
          FROM      `chartofaccountaccess`
    JOIN	`company`
    ON		`company`.`companyId` = `chartOfAccountAccess`.`companyId`
    JOIN	`chartofaccount`
    ON		`chartofaccount`.`chartOfAccountId` = `chartOfAccountAccess`.`chartOfAccountId`
    JOIN	`staff`
    ON		`staff`.`staffId` = `chartOfAccountAccess`.`staffId`
          WHERE     " . $this->getAuditFilter();
            if ($this->model->getChartOfAccountAccessId(0, 'single')) {
                $sql .= " AND `chartOfAccountAccess`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getChartOfAccountAccessId(0, 'single') . "'";
            }
            if ($this->model->getChartOfAccountId()) {
                $sql .= " AND `chartOfAccountAccess`.`chartOfAccountId`='" . $this->model->getChartOfAccountId() . "'";
            }
            if ($this->model->getStaffIdTemp()) {
                $sql .= " AND `chartOfAccountAccess`.`staffId`='" . $this->model->getStaffIdTemp() . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
          SELECT                    [chartOfAccountAccess].[chartOfAccountAccessId],
                    [company].[companyDescription],
                    [chartOfAccountAccess].[companyId],
                    [chartOfAccount].[chartOfAccountTitle],
                    [chartOfAccountAccess].[chartOfAccountId],
                    [staff].[staffName],
                    [chartOfAccountAccess].[staffId],
                    [chartOfAccountAccess].[chartOfAccountAccessValue],
                    [staff].[staffName]
          FROM 	[chartOfAccountAccess]
    JOIN	[company]
    ON		[company].[companyId] = [chartOfAccountAccess].[companyId]
    JOIN	[chartOfAccount]
    ON		[chartOfAccount].[chartOfAccountId] = [chartOfAccountAccess].[chartOfAccountId]
    JOIN	[staff]
    ON		[staff].[staffId] = [chartOfAccountAccess].[staffId]
          WHERE     " . $this->getAuditFilter();
                if ($this->model->getChartOfAccountAccessId(0, 'single')) {
                    $sql .= " AND [chartOfAccountAccess].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getChartOfAccountAccessId(0, 'single') . "'";
                }
                if ($this->model->getChartOfAccountId()) {
                    $sql .= " AND [chartOfAccountAccess].[chartOfAccountId]='" . $this->model->getChartOfAccountId(
                            ) . "'";
                }
                if ($this->model->getStaffIdTemp()) {
                    $sql .= " AND [chartOfAccountAccess].[staffId]='" . $this->model->getStaffIdTemp() . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
          SELECT                    CHARTOFACCOUNTACCESS.CHARTOFACCOUNTACCESSID AS \"chartOfAccountAccessId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    CHARTOFACCOUNTACCESS.COMPANYID AS \"companyId\",
                    CHARTOFACCOUNT.CHARTOFACCOUNTTITLE AS  \"chartOfAccountTitle\",
                    CHARTOFACCOUNTACCESS.CHARTOFACCOUNTID AS \"chartOfAccountId\",
                    STAFF.STAFFNAME AS  \"staffName\",
                    CHARTOFACCOUNTACCESS.STAFFID AS \"staffId\",
                    CHARTOFACCOUNTACCESS.CHARTOFACCOUNTACCESSVALUE AS \"chartOfAccountAccessValue\",
                    STAFF.STAFFNAME AS \"staffName\"
          FROM 	CHARTOFACCOUNTACCESS
 	JOIN	COMPANY
    ON		COMPANY.COMPANYID = CHARTOFACCOUNTACCESS.COMPANYID
    JOIN	CHARTOFACCOUNT
    ON		CHARTOFACCOUNT.CHARTOFACCOUNTID = CHARTOFACCOUNTACCESS.CHARTOFACCOUNTID
    JOIN	STAFF
    ON		STAFF.STAFFID = CHARTOFACCOUNTACCESS.STAFFID
         WHERE     " . $this->getAuditFilter();
                    if ($this->model->getChartOfAccountAccessId(0, 'single')) {
                        $sql .= " AND CHARTOFACCOUNTACCESS. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getChartOfAccountAccessId(0, 'single') . "'";
                    }
                    if ($this->model->getChartOfAccountId()) {
                        $sql .= " AND CHARTOFACCOUNTACCESS.CHARTOFACCOUNTID='" . $this->model->getChartOfAccountId(
                                ) . "'";
                    }
                    if ($this->model->getStaffIdTemp()) {
                        $sql .= " AND CHARTOFACCOUNTACCESS.STAFFID='" . $this->model->getStaffIdTemp() . "'";
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
                $sql .= " AND `chartOfAccountAccess`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [chartOfAccountAccess].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND Initcap(CHARTOFACCOUNTACCESS." . strtoupper(
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
                        'chartOfAccountAccess', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'chartOfAccountAccess', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'ChartOfAccountAccess', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
                "`chartOfAccountAccess`.`chartOfAccountAccessId`",
                "`staff`.`staffPassword`"
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $filterArray = array(
                    "[chartOfAccountAccess].[chartOfAccountAccessId]",
                    "[staff].[staffPassword]"
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $filterArray = array(
                        "CHARTOFACCOUNTACCESS.CHARTOFACCOUNTACCESSID",
                        "STAFF.STAFFPASSWORD"
                    );
                }
            }
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('staff', 'chartOfAccountAccess', 'chartofaccount', 'staff');
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array('staff', 'chartOfAccountAccess', 'chartofaccount', 'staff');
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array('STAFF', 'CHARTOFACCOUNTACCESS', 'CHARTOFACCOUNT', 'STAFF');
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
        if (!($this->model->getChartOfAccountAccessId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 5;
            if ($this->model->getChartOfAccountAccessId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord(
                        'value', $this->model->getChartOfAccountAccessId(0, 'single')
                );
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getChartOfAccountAccessId(0, 'single'));
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
                        $str .= "<td  align=center><div align=\"center\"><img src='./images/icons/burn.png'></div></td>";
                    }
                    $str .= "<td align=\"left\"><div align=\"left\"> " . $data['chartOfAccountTypeDescription'] . "</div></td>
                            ";
                    $oldRoleDescription = $data['roleDescription'];

                    if ($data['chartOfAccountAccessValue']) {
                        $checked = 'checked';
                    } else {
                        $checked = null;
                    }
                    $str .= "<td>
    <input style='display:none;' type=\"checkbox\" name='chartOfAccountAccessId[]' id='chartOfAccountAccessId' value='" . $data['chartOfAccountAccessId'] . "'>
    <input " . $checked . " type=\"checkbox\" name='chartOfAccountAccessValue[]' id='chartOfAccountAccessValue' value='" . $data['chartOfAccountAccessValue'] . "'>

</td>";
                    $str .= "</tr>";
                }
                echo json_encode(array("success" => true, "data" => $str, "message" => "success", "sql" => $sql));
                exit();
            } else {
                if ($this->getPageOutput() == 'json') {
                    if ($this->model->getChartOfAccountAccessId(0, 'single')) {
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
                                                    'value', $this->model->getChartOfAccountAccessId(0, 'single')
                                            ),
                                            'nextRecord' => $this->nextRecord(
                                                    'value', $this->model->getChartOfAccountAccessId(0, 'single')
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
                                            'value', $this->model->getChartOfAccountAccessId(0, 'single')
                                    ),
                                    'nextRecord' => $this->recordSet->nextRecord(
                                            'value', $this->model->getChartOfAccountAccessId(0, 'single')
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
            SET     `chartOfAccountAccessValue`    =   case `" . $this->model->getPrimaryKeyName() . "` ";
            for ($i = 0; $i < $loop; $i++) {
                $sql .= "
                WHEN '" . $this->model->getApplicationAccessId($i, 'array') . "'
                THEN '" . $this->model->getApplicationAccessValue($i, 'array') . "'";
            }
            $sql .= "	END ";
            $sql .= " WHERE 	`" . $this->model->getPrimaryKeyName(
                    ) . "`		IN	(" . $this->model->getPrimaryKeyAll() . ")";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            UPDATE  [" . $this->model->getTableName() . "]
            SET     [chartOfAccountAccessValue]    =   case [" . $this->model->getPrimaryKeyName() . "] ";
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
            SET     CHARTOFACCOUNTACCESSVALUE    =   case " . strtoupper($this->model->getPrimaryKeyName()) . " ";
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
        //@todo future  PHP5.5 only
        //finally {
        $this->q->commit();
        //}

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
     * To Update flag Status
     */
    function updateStatus() {
        
    }

    /**
     * To check if a key duplicate or not
     */
    function duplicate() {
        
    }

    /**  Set Service
     * @param string $service . Reset service either option,html,table
     * @return mixed
     */
    function setService($service) {
        return $this->service->setServiceOutput($service);
    }

    /**
     * Return  ChartOfAccount
     * @return mixed
     */
    public function getChartOfAccount() {
        return $this->service->getChartOfAccount();
    }

    /**
     * Return  Staff
     * @return mixed
     */
    public function getStaff() {
        return $this->service->getStaff();
    }

    /**
     * Reporting
     * @see config::excel()
     */
    function excel() {
        
    }

    /**
     * Return Total Record Of The
     * return int Total Record
     */
    private function getTotalRecord() {
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT  count(*) AS `total`
         FROM    `chartOfAccountAccess`
         WHERE   `isActive`=1
         AND     `companyId`=" . $_SESSION['companyId'] . " ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT  count(*) AS total
         FROM    [chartOfAccountAccess]
         WHERE   [isActive]=1
         AND    [companyId] =   " . $_SESSION['companyId'] . " ";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
         SELECT  count(*) AS    total
         FROM    ChartOfAccountAccess
         WHERE  ISACTIVE=1
         AND    COMPANYID=" . $_SESSION['companyId'] . " ";
                }
            }
        }
        $result = $this->q->fast($sql);
        if ($result) {
            if ($this->q->numberRows($result) > 0) {
                $row = $this->q->fetchArray($result);
                return $row['total'];
            }
        }
    }

}

if (isset($_POST ['method'])) {
    if (isset($_POST['output'])) {
        $chartOfAccountAccessObject = new ChartOfAccountAccessClass ();
        /*
         *  Load the dynamic value
         */
        if (isset($_POST ['leafId'])) {
            $chartOfAccountAccessObject->setLeafId($_POST ['leafId']);
        }
        $chartOfAccountAccessObject->setPageOutput($_POST['output']);
        $chartOfAccountAccessObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy)
         */
        if ($_POST ['method'] == 'create') {
            $chartOfAccountAccessObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $chartOfAccountAccessObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $chartOfAccountAccessObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $chartOfAccountAccessObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$chartOfAccountAccessObject->posting();
        }
        if ($_POST ['method'] == 'reverse') {
            //	$chartOfAccountAccessObject->delete();
        }
    }
}
if (isset($_GET ['method'])) {
    $chartOfAccountAccessObject = new ChartOfAccountAccessClass ();
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $chartOfAccountAccessObject->setLeafId($_GET ['leafId']);
    }
    /*
     * Admin Only
     */
    if (isset($_GET ['isAdmin'])) {
        $chartOfAccountAccessObject->setIsAdmin($_GET ['isAdmin']);
    }
    /**
     * Database Request
     */
    if (isset($_GET ['databaseRequest'])) {
        $chartOfAccountAccessObject->setRequestDatabase($_GET ['databaseRequest']);
    }
    if (isset($_GET['companyId'])) {
        $chartOfAccountAccessObject->setCompanyId($_GET['companyId']);
    }
    if (isset($_GET['chartOfAccountId'])) {
        $chartOfAccountAccessObject->setChartOfAccountId($_GET['chartOfAccountId']);
    }
    if (isset($_GET['staffId'])) {
        $chartOfAccountAccessObject->setStaffId($_GET['staffId']);
    }
    /*
     *  Load the dynamic value
     */
    $chartOfAccountAccessObject->execute();
    if (isset($_GET ['field'])) {
        if ($_GET ['field'] == 'staffId') {
            $chartOfAccountAccessObject->staff();
        }
    }
    /**
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'updateStatus') {
        $chartOfAccountAccessObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key
     */
    if (isset($_GET ['chartOfAccountAccessCode'])) {
        if (strlen($_GET ['chartOfAccountAccessCode']) > 0) {
            $chartOfAccountAccessObject->duplicate();
        }
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $chartOfAccountAccessObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $chartOfAccountAccessObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $chartOfAccountAccessObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $chartOfAccountAccessObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting
     */
    if (isset($_GET ['mode'])) {
        $chartOfAccountAccessObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $chartOfAccountAccessObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        if (($_GET['filter'] == 'chartOfAccount')) {
            $chartOfAccountAccessObject->getChartOfAccount();
        }
    }
    if (isset($_GET ['filter'])) {
        if (($_GET['filter'] == 'staff')) {
            $chartOfAccountAccessObject->getStaff();
        }
    }
}
?>