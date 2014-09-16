<?php

namespace Core\System\Security\DefaultLabelTranslate\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\RecordSet\RecordSet;
use Core\shared\SharedClass;
use Core\System\Security\DefaultLabelTranslate\Model\DefaultLabelTranslateModel;
use Core\System\Security\DefaultLabelTranslate\Service\DefaultLabelTranslateService;

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
require_once($newFakeDocumentRoot . "v3/system/security/model/defaultLabelTranslateModel.php");
require_once($newFakeDocumentRoot . "v3/system/security/service/defaultLabelTranslateService.php");

/**
 * Class DefaultLabelTranslateClass
 * this is default label translate setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Security\DefaultLabelTranslate\Controller
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class DefaultLabelTranslateClass extends ConfigClass {

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
     * @var \Core\System\Security\DefaultLabelTranslate\Model\DefaultLabelTranslateModel
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\System\Security\DefaultLabelTranslate\Service\DefaultLabelTranslateService
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
        $this->setViewPath("./v3/system/security/view/defaultlabeltranslate.php");
        $this->setControllerPath("./v3/system/security/controller/defaultlabeltranslateController.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(0);
        $this->setLog(1);
        $this->model = new DefaultLabelTranslateModel();
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

        $translator = new SharedClass();
        $translator->setCurrentDatabase($this->q->getCoreDatabase());
        $translator->setCurrentTable($this->model->getTableName());
        $translator->setLeafId($this->getLeafId());
        $translator->execute();

        $this->translate = $translator->getLeafTranslation(); // short because code too long
        $this->t = $translator->getDefaultTranslation(); // short because code too long
        $this->leafAccess = $translator->getLeafAccess();

        $arrayInfo = $translator->getFileInfo();
        $applicationNative = $arrayInfo['applicationNative'];
        $folderNative = $arrayInfo['folderNative'];
        $moduleNative = $arrayInfo['moduleNative'];
        $leafNative = $arrayInfo['leafNative'];
        $this->setReportTitle(
                $applicationNative . " :: " . $moduleNative . " :: " . $folderNative . " :: " . $leafNative
        );

        $this->service = new DefaultLabelTranslateService();
        $this->service->q = $this->q;
        $this->service->t = $this->t;
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
     * @return void
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
        $this->setLimit(500);
        // end override
        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] == 0) {
                if ($this->getVendor() == self::MYSQL) {
                    $this->setAuditFilter(" `defaultlabeltranslate`.`isActive` = 1 ");
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(" [defaultlabeltranslate].[isActive] = 1 ");
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(" DEFAULTLABELTRANSLATE.ISACTIVE = 1 ");
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
            SELECT  `defaultlabeltranslate`.`languageId`,
                    `language`.`languageDescription`,
                    `language`.`languageIcon`,
                    `defaultLabel`.`defaultLabelEnglish`,
                    `defaultlabeltranslate`.`defaultLabelTranslateId`,
                    `defaultlabeltranslate`.`defaultLabelNative`,
                    `defaultlabeltranslate`.`defaultLabelId`,
                    `staff`.`staffName`
            FROM    `defaultlabeltranslate`
            JOIN    `staff`
            ON      `defaultlabeltranslate`.`executeBy` = `staff`.`staffId`
            JOIN    `defaultLabel`
            ON      `defaultLabel`.`defaultLabelId` = `defaultLabelTranslate`.`defaultLabelId`
            JOIN    `language`
            ON      `defaultlabeltranslate`.`languageId` = `language`.`languageId`
            WHERE     " . $this->getAuditFilter();
            $sql .= "
            AND     `isBing`=1 
            AND     `isImportant`=1 "; // bypass
            if ($this->model->getDefaultLabelTranslateId(0, 'single')) {
                $sql .= " AND `defaultlabeltranslate`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getDefaultLabelTranslateId(0, 'single') . "'";
            }
            if ($this->model->getDefaultLabelId()) {
                $sql .= " AND `defaultLabel`.`defaultLabelId`		=	'" . $this->model->getDefaultLabelId() . "'";
            }
            if ($this->model->getLanguageId()) {
                $sql .= " AND `defaultLabelTranslate`.`languageId`='" . $this->model->getLanguageId() . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
		  SELECT                    [defaultlabeltranslate].[languageId],
                    [defaultlabeltranslate].[defaultLabelTranslateId],
                    [defaultlabeltranslate].[defaultLabelNative],
                    [defaultlabeltranslate].[defaultLabelId],
                    [staff].[staffName]
		  FROM 	[defaultlabeltranslate]
		  JOIN	[staff]
		  ON	[defaultlabeltranslate].[executeBy] = [staff].[staffId]
                      JOIN    [defaultLabel]
            ON      [defaultLabel].[defaultLabelId] = [defaultLabelTranslate].[defaultLabelId]

                   JOIN    [language]
            ON      [defaultlabeltranslate].[languageId] = [language].[languageId]
		  WHERE     " . $this->getAuditFilter();
                if ($this->model->getDefaultLabelTranslateId(0, 'single')) {
                    $sql .= " AND [defaultlabeltranslate].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getDefaultLabelTranslateId(0, 'single') . "'";
                }
                if ($this->model->getDefaultLabelId()) {
                    $sql .= " AND [defaultLabel].[defaultLabelId]		=	'" . $this->model->getDefaultLabelId(
                            ) . "'";
                }
                if ($this->model->getLanguageId()) {
                    $sql .= " AND [defaultLabelTranslate].[languageId]='" . $this->model->getLanguageId() . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
		  SELECT                    DEFAULTLABELTRANSLATE.LANGUAGEID,
                    DEFAULTLABELTRANSLATE.DEFAULTLABELTRANSLATEID,
                    DEFAULTLABELTRANSLATE.DEFAULTLABELNATIVE,
                    DEFAULTLABELTRANSLATE.DEFAULTLABELID,
                    STAFF.STAFFNAME
		  FROM 	DEFAULTLABELTRANSLATE
		  JOIN	STAFF
		  ON	DEFAULTLABELTRANSLATE.EXECUTEBY = STAFF.STAFFID
          WHERE     " . $this->getAuditFilter();
                    if ($this->model->getDefaultLabelTranslateId(0, 'single')) {
                        $sql .= " AND DEFAULTLABELTRANSLATE. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getDefaultLabelTranslateId(0, 'single') . "'";
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
                $sql .= " AND `defaultlabeltranslate`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [defaultlabeltranslate].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND DEFAULTLABELTRANSLATE." . strtoupper(
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
                        'defaultlabeltranslate', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'defaultlabeltranslate', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'DEFAULTLABELTRANSLATE', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
        $filterArray = array('');
        /**
         * filter table
         * @variables $tableArray
         */
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('defaultlabeltranslate');
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array('defaultlabeltranslate');
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array('DEFAULTLABELTRANSLATE');
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
						FROM ( SELECT	a.*,
												rownum r
						FROM (
SELECT							     DEFAULTLABELTRANSLATE.LANGUAGEID,
							     DEFAULTLABELTRANSLATE.DEFAULTLABELTRANSLATEID,
							     DEFAULTLABELTRANSLATE.DEFAULTLABELNATIVE,
							     DEFAULTLABELTRANSLATE.DEFAULTLABELID,
                                   STAFF.STAFFNAME
							     FROM 	DEFAULTLABELTRANSLATE
							     JOIN	  STAFF
							     ON		DEFAULTLABELTRANSLATE.EXECUTEBY = STAFF.STAFFID
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

        if (!($this->model->getDefaultLabelTranslateId(0, 'single'))) {
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
            if ($this->model->getDefaultLabelTranslateId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord(
                        'value', $this->model->getDefaultLabelTranslateId(0, 'single')
                );
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getDefaultLabelTranslateId(0, 'single'));
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
                    $str .= "
						<tr>\n";
                    $str .= "
							<td align=\"center\" >" . $i . "</td>\n";
                    $str .= "
							<td vAlign=\"top\" align=\"left\">" . $data['defaultLabelEnglish'] . "</td>\n";
                    $str .= "
							<td align=\"left\"><img src=\"./images/country/" . $data['languageIcon'] . "\"> " . $data['languageDescription'] . "</td>\n";

                    $str .= "     <td vAlign=\"top\" vAlign=\"top\">
										<div class=\"col-xs-12 col-sm-12 col-md-12\">
											<div class=\"input-group\"><input type=\"text\"  class=\"form-control\" name=\"defaultLabelNative" . $data['defaultLabelTranslateId'] . "\" id=\"defaultLabelNative" . $data['defaultLabelTranslateId'] . "\" value=\"" . $data['defaultLabelNative'] . "\">\n";
                    if ($this->leafAccess['leafAccessUpdateValue'] == 0) {
                        $disabled = " disabled ";
                    } else {
                        $disabled = null;
                    }
                    $str .= "     <span class=\"input-group-btn\"><button type=\"button\" class=\"btn btn-warning " . $disabled . "\" title=\"" . $this->t['saveButtonLabel'] . "\"";
                    $str .= " " . $disabled . " ";
                    $str .= " onClick=\"updateRecordInline(" . $this->getLeafId() . ",'" . $this->getControllerPath() . "','" . $this->getSecurityToken() . "'," . $data['defaultLabelTranslateId'] . ")\">" . $this->t['saveButtonLabel'] . "</button></span></div></div><div id=\"infoPanelMini" . $data['defaultLabelTranslateId'] . "\"></div></td>";

                    $str .= "</tr>\n";
                }
                echo json_encode(array("success" => true, "message" => "complete", "data" => $str));
                exit();
            } else {
                if ($this->getPageOutput() == 'json') {
                    if ($this->model->getDefaultLabelTranslateId(0, 'single')) {
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
                                                    'value', $this->model->getDefaultLabelTranslateId(0, 'single')
                                            ),
                                            'nextRecord' => $this->nextRecord(
                                                    'value', $this->model->getDefaultLabelTranslateId(0, 'single')
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
                                            'value', $this->model->getDefaultLabelTranslateId(0, 'single')
                                    ),
                                    'nextRecord' => $this->recordSet->nextRecord(
                                            'value', $this->model->getDefaultLabelTranslateId(0, 'single')
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
		FROM 	`defaultlabeltranslate` 
		WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getDefaultLabelTranslateId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
			SELECT	[" . $this->model->getPrimaryKeyName() . "] 
			FROM 	[defaultlabeltranslate] 
			WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getDefaultLabelTranslateId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
			SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
			FROM 	DEFAULTLABELTRANSLATE 
			WHERE  	" . strtoupper(
                            $this->model->getPrimaryKeyName()
                    ) . " = '" . $this->model->getDefaultLabelTranslateId(0, 'single') . "' ";
        }
        try {
            $result = $this->q->fast($sql);
            $total = $this->q->numberRows($result, $sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($total == 0) {
            echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel']));
            exit();
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
		UPDATE  `defaultlabeltranslate` 
                SET     `defaultLabelNative` = '" . $this->model->getDefaultLabelNative() . "'
                WHERE   `defaultLabelTranslateId`='" . $this->model->getDefaultLabelTranslateId('0', 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
		UPDATE  [defaultlabeltranslate] 
                SET     [defaultLabelNative] = '" . $this->model->getDefaultLabelNative() . "'
                WHERE   [defaultLabelTranslateId]='" . $this->model->getDefaultLabelTranslateId('0', 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
		UPDATE  DEFAULTLABELTRANSLATE 
                SET     DEFAULTLABELNATIVE = '" . $this->model->getDefaultLabelNative() . "'
                WHERE   DEFAULTLABELTRANSLATEID='" . $this->model->getDefaultLabelTranslateId('0', 'single') . "'";
            }
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
        }
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
     * Return Default Label Data
     * @return mixed
     */
    public function getDefaultLabel() {
        $this->service->setServiceOutput('html');
        return $this->service->getDefaultLabel();
    }

    /**
     * Return Language Data
     * @return mixed
     */
    public function getLanguage() {
        $this->service->setServiceOutput('html');
        return $this->service->getLanguage();
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
        $defaultLabelTranslateObject = new DefaultLabelTranslateClass ();
        if ($_POST['securityToken'] != $defaultLabelTranslateObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $defaultLabelTranslateObject->setLeafId($_POST ['leafId']);
        }
        $defaultLabelTranslateObject->setPageOutput($_POST['output']);
        $defaultLabelTranslateObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $defaultLabelTranslateObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $defaultLabelTranslateObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $defaultLabelTranslateObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $defaultLabelTranslateObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$defaultLabelTranslateObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$defaultLabelTranslateObject->delete(); 
        }
    }
}
if (isset($_GET ['method'])) {
    $defaultLabelTranslateObject = new DefaultLabelTranslateClass ();
    if ($_GET['securityToken'] != $defaultLabelTranslateObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $defaultLabelTranslateObject->setLeafId($_GET ['leafId']);
    }
    /*
     * Admin Only
     */
    if (isset($_GET ['isAdmin'])) {
        $defaultLabelTranslateObject->setIsAdmin($_GET ['isAdmin']);
    }
    /**
     * Database Request
     */
    if (isset($_GET ['databaseRequest'])) {
        $defaultLabelTranslateObject->setRequestDatabase($_GET ['databaseRequest']);
    }
    /*
     *  Load the dynamic value
     */
    $defaultLabelTranslateObject->execute();
    /**
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'updateStatus') {
        $defaultLabelTranslateObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key 
     */
    if (isset($_GET ['defaultlabeltranslateCode'])) {
        if (strlen($_GET ['defaultlabeltranslateCode']) > 0) {
            $defaultLabelTranslateObject->duplicate();
        }
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $defaultLabelTranslateObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $defaultLabelTranslateObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $defaultLabelTranslateObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $defaultLabelTranslateObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $defaultLabelTranslateObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $defaultLabelTranslateObject->excel();
        }
    }
}
?>