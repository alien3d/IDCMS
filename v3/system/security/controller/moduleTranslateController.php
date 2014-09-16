<?php

namespace Core\System\Security\ModuleTranslate\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\RecordSet\RecordSet;
use Core\shared\SharedClass;
use Core\System\Security\ModuleTranslate\Model\ModuleTranslateModel;
use Core\System\Security\ModuleTranslate\Service\ModuleTranslateService;

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
require_once($newFakeDocumentRoot . "v3/system/security/model/moduleTranslateModel.php");
require_once($newFakeDocumentRoot . "v3/system/security/service/moduleTranslateService.php");

/**
 * Class ModuleTranslateClass
 * this is module translate setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Security\ModuleTranslate\Controller
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ModuleTranslateClass extends ConfigClass {

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
     * @var \Core\System\Security\ModuleTranslate\Model\ModuleTranslateModel
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\System\Security\ModuleTranslate\Service\ModuleTranslateService
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
        $this->setViewPath("./v3/system/security/view/moduleTranslate.php");
        $this->setControllerPath("./v3/system/security/controller/moduleTranslateController.php");
        $this->setServicePath("./v3/system/security/service/moduleTranslateService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(0);
        $this->setLog(1);
        $this->model = new ModuleTranslateModel();
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

        $arrayInfo = $translator->getFileInfo('moduleTranslate.php');
        $applicationNative = $arrayInfo['applicationNative'];
        $folderNative = $arrayInfo['folderNative'];
        $moduleNative = $arrayInfo['moduleNative'];
        $leafNative = $arrayInfo['leafNative'];
        $this->setReportTitle(
                $applicationNative . " :: " . $moduleNative . " :: " . $folderNative . " :: " . $leafNative
        );

        $this->service = new ModuleTranslateService();
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
                    $this->setAuditFilter(" `moduletranslate`.`isActive` = 1 ");
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(" [moduletranslate].[isActive] = 1 ");
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(" MODULETRANSLATE.ISACTIVE = 1 ");
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
            SELECT  `moduletranslate`.`moduleTranslateId`,
                    `application`.`applicationEnglish`,
                    `module`.`moduleEnglish`,
                    `language`.`languageDescription`,
                    `language`.`languageIcon`,
                    `moduletranslate`.`moduleNative`,
                    `moduletranslate`.`isDefault`,
                    `moduletranslate`.`isNew`,
                    `moduletranslate`.`isDraft`,
                    `moduletranslate`.`isUpdate`,
                    `moduletranslate`.`isDelete`,
                    `moduletranslate`.`isActive`,
                    `moduletranslate`.`isApproved`,
                    `moduletranslate`.`isReview`,
                    `moduletranslate`.`ispost`,
                    `moduletranslate`.`executeBy`,
                    `moduletranslate`.`executeTime`,
                    `staff`.`staffName`
            FROM    `moduletranslate`
            JOIN    `staff`
            ON      `moduletranslate`.`executeBy` = `staff`.`staffId`            
            JOIN    `module`
            ON      `module`.`moduleId` = `moduletranslate`.`moduleId`
            JOIN    `application`
            ON      `module`.`applicationId` = `application`.`applicationId`
            JOIN    `language`
            ON      `language`.`languageId` = `moduletranslate`.`languageId`
            WHERE     " . $this->getAuditFilter();
            $sql .= " AND `language`.`isImportant` =1";
            if ($this->model->getModuleTranslateId(0, 'single')) {
                $sql .= " AND `moduletranslate`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getModuleTranslateId(0, 'single') . "'";
            }
            if ($this->model->getApplicationId()) {
                $sql .= " AND `application`.`applicationId`='" . $this->model->getApplicationId() . "'";
            }
            if ($this->model->getModuleId()) {
                $sql .= " AND `module`.`moduleId`='" . $this->model->getModuleId() . "'";
            }
            if ($this->model->getLanguageId()) {
                $sql .= " AND `language`.`languageId`='" . $this->model->getLanguageId() . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
            SELECT      [moduletranslate].[moduleTranslateId],
                        [application].[applicationEnglish],
                        [module].[moduleEnglish],
                        [language].[languageDescription],
                        [language].[languageIcon],
                        [moduletranslate].[moduleNative],
                        [moduletranslate].[isDefault],
                        [moduletranslate].[isNew],
                        [moduletranslate].[isDraft],
                        [moduletranslate].[isUpdate],
                        [moduletranslate].[isDelete],
                        [moduletranslate].[isActive],
                        [moduletranslate].[isApproved],
                        [moduletranslate].[isReview],
                        [moduletranslate].[ispost],
                        [moduletranslate].[executeBy],
                        [moduletranslate].[executeTime],
                        [staff].[staffName]
            FROM 	[moduletranslate]
            JOIN	[staff]
            ON          [moduletranslate].[executeBy] = [staff].[staffId]
            JOIN	[module]
            ON		[module].[moduleId] = [moduletranslate].[moduleId]
            JOIN        [application]
            ON          [module].[applicationId] = [application].[applicationId]
            JOIN	[language]
            ON		[language].[languageId] = [moduletranslate].[languageId]
            WHERE  " . $this->getAuditFilter();
                $sql .= " AND [language].[isImportant] =1 ";
                if ($this->model->getModuleTranslateId(0, 'single')) {
                    $sql .= " AND [moduletranslate].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getModuleTranslateId(0, 'single') . "'";
                }
                if ($this->model->getApplicationId()) {
                    $sql .= " AND [application].[applicationId]='" . $this->model->getApplicationId() . "'";
                }
                if ($this->model->getModuleId()) {
                    $sql .= " AND [module].[moduleId]='" . $this->model->getModuleId() . "'";
                }
                if ($this->model->getLanguageId()) {
                    $sql .= " AND [language].[languageId]='" . $this->model->getLanguageId() . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
            SELECT  MODULETRANSLATE.MODULETRANSLATEID,
                    APPLICATION.APPLICATIONENGLISH,
                    MODULE.MODULEENGLISH,
                    LANGUAGE.LANGUAGEDESC,
                    LANGUAGE.LANGUAGEICON,
                    MODULETRANSLATE.MODULENATIVE,
                    MODULETRANSLATE.ISDEFAULT,
                    MODULETRANSLATE.ISNEW,
                    MODULETRANSLATE.ISDRAFT,
                    MODULETRANSLATE.ISUPDATE,
                    MODULETRANSLATE.ISDELETE,
                    MODULETRANSLATE.ISACTIVE,
                    MODULETRANSLATE.ISAPPROVED,
                    MODULETRANSLATE.ISREVIEW,
                    MODULETRANSLATE.ISPOST,
                    MODULETRANSLATE.EXECUTEBY,
                    MODULETRANSLATE.EXECUTETIME,
                    STAFF.STAFFNAME
            FROM    MODULETRANSLATE
            JOIN    STAFF
            ON      MODULETRANSLATE.EXECUTEBY = STAFF.STAFFID
            JOIN    MODULE
            ON      MODULE.MODULEID = MODULETRANSLATE.MODULEID
            JOIN    APPLICATION
            ON      MODULE.APPLICATIONID = APPLICATON.APPLICATIONID
            JOIN    LANGUAGE
            ON      LANGUAGE.LANGUAGEID = MODULETRANSLATE.LANGUAGEID
            WHERE   " . $this->getAuditFilter();
                    $sql .= " AND LANGUAGE.ISIMPORTANT =1";
                    if ($this->model->getModuleTranslateId(0, 'single')) {
                        $sql .= " AND MODULETRANSLATE. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getModuleTranslateId(0, 'single') . "'";
                    }
                    if ($this->model->getApplicationId()) {
                        $sql .= " AND APPLICATION.APPLICATIONID='" . $this->model->getApplicationId() . "'";
                    }
                    if ($this->model->getModuleId()) {
                        $sql .= " AND MODULE.MODULEID='" . $this->model->getModuleId() . "'";
                    }
                    if ($this->model->getLanguageId()) {
                        $sql .= " AND LANGUAGE.LANGUAGEID='" . $this->model->getLanguageId() . "'";
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
                $sql .= " AND `moduletranslate`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [moduletranslate].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND MODULETRANSLATE." . strtoupper(
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
                        'moduletranslate', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'moduletranslate', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'MODULETRANSLATE', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
        $filterArray = array('moduleTranslateId');
        /**
         * filter table
         * @variables $tableArray
         */
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('moduletranslate');
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array('moduletranslate');
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array('MODULETRANSLATE');
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
                SELECT  *
                FROM    (
                            SELECT  a.*,
                                    rownum r
                            FROM (
                                    SELECT  MODULETRANSLATE.MODULETRANSLATEID,
                                            APPLICATION.APPLICATIONENGLISH,
                                            MODULE.MODULEENGLISH,
                                            LANGUAGE.LANGUAGEID,
                                            MODULETRANSLATE.MODULENATIVE,
                                            MODULETRANSLATE.ISDEFAULT,
                                            MODULETRANSLATE.ISNEW,
                                            MODULETRANSLATE.ISDRAFT,
                                            MODULETRANSLATE.ISUPDATE,
                                            MODULETRANSLATE.ISDELETE,
                                            MODULETRANSLATE.ISACTIVE,
                                            MODULETRANSLATE.ISAPPROVED,
                                            MODULETRANSLATE.ISREVIEW,
                                            MODULETRANSLATE.ISPOST,
                                            MODULETRANSLATE.EXECUTEBY,
                                            MODULETRANSLATE.EXECUTETIME,
                                            STAFF.STAFFNAME
                                    FROM    MODULETRANSLATE
                                    JOIN    STAFF
                                    ON      MODULETRANSLATE.EXECUTEBY = STAFF.STAFFID
                                    JOIN    MODULE
                                    ON      MODULE.MODULEID = MODULETRANSLATE.MODULEID
                                    JOIN    APPLICATION
                                    ON      MODULE.APPLICATIONID = APPLICATON.APPLICATIONID
                                    JOIN    LANGUAGE
                                    ON      LANGUAGE.LANGUAGEID = MODULETRANSLATE.LANGUAGEID
                                    WHERE   " . $this->getAuditFilter() . $tempSql . $tempSql2 . "
                                ) a
                        WHERE rownum <= '" . ($this->getStart() + $this->getLimit()) . "'
                    )
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
        if (!($this->model->getModuleTranslateId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 15;
            if ($this->model->getModuleTranslateId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord(
                        'value', $this->model->getModuleTranslateId(0, 'single')
                );
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getModuleTranslateId(0, 'single'));
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
                $disabled = null;
                foreach ($items as $data) {
                    $i++;
                    $str .= "<tr>\n";
                    $str .= "<td vAlign=\"top\">" . $i . "</td>\n";
                    $str .= "<td vAlign=\"top\">" . $data['applicationEnglish'] . "</td>\n";
                    $str .= "<td vAlign=\"top\">" . $data['moduleEnglish'] . "</td>\n";
                    $str .= "<td vAlign=\"top\"><img src=\"./images/country/" . $data['languageIcon'] . "\"> " . $data['languageDescription'] . "</td>\n";
                    $str .= "<td vAlign=\"top\"><div class=\"col-xs-12 col-sm-12 col-md-12\"><div class=\"input-group\"><input type=\"text\" class=\"form-control\" name=\"moduleNative" . $data['moduleTranslateId'] . "\" id=\"moduleNative" . $data['moduleTranslateId'] . "\" value=\"" . $data['moduleNative'] . "\">";
                    if ($this->leafAccess['leafAccessUpdateValue'] == 0) {
                        $disabled = "disabled";
                    } else {
                        $disabled = null;
                    }
                    $str .= "<span class=\"input-group-btn\"><button type=\"button\" class=\"btn btn-warning\" title=\"" . $this->t['saveButtonLabel'] . "\" " . $disabled;

                    $str .= "onClick=\"updateRecordInline(" . intval($this->getLeafId()) . ",'" . $this->getControllerPath() . "','" . $this->getSecurityToken() . "'," . intval($data['moduleTranslateId']) . ")\">" . $this->t['saveButtonLabel'] . "</button></span></div></div><div id=\"infoPanelMini" . $data['moduleTranslateId'] . "\"></div></td>";

                    $str .= "</tr>\n";
                }

                echo json_encode(array("success" => true, "message" => "complete", "data" => $str));
                exit();
            } else {
                if ($this->getPageOutput() == 'json') {
                    if ($this->model->getModuleTranslateId(0, 'single')) {
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
                                                    'value', $this->model->getModuleTranslateId(0, 'single')
                                            ),
                                            'nextRecord' => $this->nextRecord(
                                                    'value', $this->model->getModuleTranslateId(0, 'single')
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
                                            'value', $this->model->getModuleTranslateId(0, 'single')
                                    ),
                                    'nextRecord' => $this->recordSet->nextRecord(
                                            'value', $this->model->getModuleTranslateId(0, 'single')
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
		FROM 	`moduletranslate` 
		WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getModuleTranslateId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
			SELECT	[" . $this->model->getPrimaryKeyName() . "] 
			FROM 	[moduletranslate] 
			WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getModuleTranslateId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
			SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
			FROM 	MODULETRANSLATE 
			WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getModuleTranslateId(
                            0, 'single'
                    ) . "' ";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
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
                UPDATE  `moduletranslate` 
                SET     `moduleNative` = '" . $this->model->getModuleNative() . "',
                        `isDefault` = '" . $this->model->getIsDefault('0', 'single') . "',
                        `isNew` = '" . $this->model->getIsNew('0', 'single') . "',
                        `isDraft` = '" . $this->model->getIsDraft('0', 'single') . "',
                        `isUpdate` = '" . $this->model->getIsUpdate('0', 'single') . "',
                        `isDelete` = '" . $this->model->getIsDelete('0', 'single') . "',
                        `isActive` = '" . $this->model->getIsActive('0', 'single') . "',
                        `isApproved` = '" . $this->model->getIsApproved('0', 'single') . "',
                        `isReview` = '" . $this->model->getIsReview('0', 'single') . "',
                        `ispost` = '" . $this->model->getIsPost(0, 'single') . "',
                        `executeBy` = '" . $this->model->getExecuteBy('0', 'single') . "',
                        `executeTime` = " . $this->model->getExecuteTime() . " 
                WHERE   `moduleTranslateId`='" . $this->model->getModuleTranslateId('0', 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE  [moduletranslate] 
                SET     [moduleNative] = '" . $this->model->getModuleNative() . "',
                        [isDefault] = '" . $this->model->getIsDefault(0, 'single') . "',
                        [isNew] = '" . $this->model->getIsNew(0, 'single') . "',
                        [isDraft] = '" . $this->model->getIsDraft(0, 'single') . "',
                        [isUpdate] = '" . $this->model->getIsUpdate(0, 'single') . "',
                        [isDelete] = '" . $this->model->getIsDelete(0, 'single') . "',
                        [isActive] = '" . $this->model->getIsActive(0, 'single') . "',
                        [isApproved] = '" . $this->model->getIsApproved(0, 'single') . "',
                        [isReview] = '" . $this->model->getIsReview(0, 'single') . "',
                        [ispost] = '" . $this->model->getIsPost(0, 'single') . "',
                        [executeBy] = '" . $this->model->getExecuteBy(0, 'single') . "',
                        [executeTime] = " . $this->model->getExecuteTime() . " 
                WHERE   [moduleTranslateId]='" . $this->model->getModuleTranslateId('0', 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE  MODULETRANSLATE 
                SET     MODULENATIVE = '" . $this->model->getModuleNative() . "',
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
                WHERE ` MODULETRANSLATEID`='" . $this->model->getModuleTranslateId('0', 'single') . "'";
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
            echo $this->service->getModule($this->model->getApplicationId());
        } else {

            $this->service->setServiceOutput('html');
            return $this->service->getModule();
        }
        return false;
    }

    /**
     * Return Language DAta
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

/**
 * crud -create,read,update,delete
 * */
if (isset($_POST ['method'])) {
    if (isset($_POST['output'])) {
        $moduleTranslateObject = new ModuleTranslateClass ();
        if ($_POST['securityToken'] != $moduleTranslateObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $moduleTranslateObject->setLeafId($_POST ['leafId']);
        }
        $moduleTranslateObject->setPageOutput($_POST['output']);
        $moduleTranslateObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $moduleTranslateObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $moduleTranslateObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $moduleTranslateObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $moduleTranslateObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$moduleTranslateObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$moduleTranslateObject->delete(); 
        }
    }
}
if (isset($_GET ['method'])) {
    $moduleTranslateObject = new ModuleTranslateClass ();
    if ($_GET['securityToken'] != $moduleTranslateObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $moduleTranslateObject->setLeafId($_GET ['leafId']);
    }
    /*
     * Admin Only
     */
    if (isset($_GET ['isAdmin'])) {
        $moduleTranslateObject->setIsAdmin($_GET ['isAdmin']);
    }
    /*
     *  Load the dynamic value
     */
    $moduleTranslateObject->execute();

    /**
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'updateStatus') {
        $moduleTranslateObject->updateStatus();
    }

    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $moduleTranslateObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $moduleTranslateObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $moduleTranslateObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $moduleTranslateObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $moduleTranslateObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $moduleTranslateObject->excel();
        }
    }
    if (isset($_GET['filter'])) {

        if ($_GET['filter'] == 'moduleId') {
            $moduleTranslateObject->getModule();
        }
    }
}
?>