<?php

namespace Core\System\Security\TableMappingTranslate\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\RecordSet\RecordSet;
use Core\shared\SharedClass;
use Core\System\Security\TableMappingTranslate\Model\TableMappingTranslateModel;
use Core\System\Security\TableMappingTranslate\Service\TableMappingTranslateService;

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
require_once($newFakeDocumentRoot . "v3/system/security/model/tableMappingTranslateModel.php");
require_once($newFakeDocumentRoot . "v3/system/security/service/tableMappingTranslateService.php");

/**
 * Class TableMappingTranslateClass
 * this is tablemapping translate setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Security\TableMappingTranslate\Controller
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class TableMappingTranslateClass extends ConfigClass {

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
     * @var \Core\System\Security\TableMappingTranslate\Model\TableMappingTranslateModel
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\System\Security\TableMappingTranslate\Service\TableMappingTranslateService
     */
    public $service;

    /**
     * Translation Array
     * @var string
     */
    public $translate;

    /**
     * Translation Label
     * @var string
     */
    public $t;

    /**
     * Leaf Access
     * @var string
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
     * Database Name
     * @var string
     */
    private $tableMappingDatabaseName;

    /**
     * Table Name
     * @var string
     */
    private $tableMappingName;

    /**
     * Column Name
     * @var int
     */
    private $tableMappingId;

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
        $this->leafAcess = array();
        $this->setViewPath("./v3/system/security/view/tableMappingTranslate.php");
        $this->setControllerPath("./v3/system/security/controller/tableMappingTranslateController.php");
        $this->setServicePath("./v3/system/security/service/tableMappingTranslateService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(0);
        $this->setLog(0);
        $this->model = new TableMappingTranslateModel();
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
        $translator->setCurrentTable(array($this->model->getTableName(), 'tableMapping'));
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

        $this->service = new TableMappingTranslateService();
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
                    $this->setAuditFilter(
                            " `tablemappingtranslate`.`isActive` = 1  AND `tablemappingtranslate`.`companyId`='" . $this->getCompanyId(
                            ) . "' "
                    );
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [tableMappingTranslate].[isActive] = 1 AND [tableMappingTranslate].[companyId]='" . $this->getCompanyId(
                                ) . "' "
                        );
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(
                                    " TABLEMAPPINGTRANSLATE.ISACTIVE = 1  AND TABLEMAPPINGTRANSLATE.COMPANYID='" . $this->getCompanyId(
                                    ) . "'"
                            );
                        }
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter(
                                "   `tablemappingtranslate`.`companyId`='" . $this->getCompanyId() . "'	"
                        );
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(
                                    " [tableMappingTranslate].[companyId]='" . $this->getCompanyId() . "' "
                            );
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(
                                        " TABLEMAPPINGTRANSLATE.COMPANYID='" . $this->getCompanyId() . "' "
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
       SELECT                    `tablemappingtranslate`.`tableMappingTranslateId`,
                    `tablemapping`.`tableMappingName`,
					`tablemapping`.`tableMappingColumnName`,
					`tablemapping`.`tableMappingEnglish`,
                    `tablemappingtranslate`.`tableMappingId`,
                    `language`.`languageIcon`,
					`language`.`languageDescription`,
                    `tablemappingtranslate`.`languageId`,
                    `tablemappingtranslate`.`tableMappingNative`,
                    `tablemappingtranslate`.`isDefault`,
                    `tablemappingtranslate`.`isNew`,
                    `tablemappingtranslate`.`isDraft`,
                    `tablemappingtranslate`.`isUpdate`,
                    `tablemappingtranslate`.`isDelete`,
                    `tablemappingtranslate`.`isActive`,
                    `tablemappingtranslate`.`isApproved`,
                    `tablemappingtranslate`.`isReview`,
                    `tablemappingtranslate`.`isPost`,
                    `tablemappingtranslate`.`executeBy`,
                    `tablemappingtranslate`.`executeTime`,
					
                    `staff`.`staffName`
          FROM      `tablemappingtranslate`
          JOIN      `staff`
          ON        `tablemappingtranslate`.`executeBy` = `staff`.`staffId`
    JOIN	`tablemapping`
    ON		`tablemapping`.`tableMappingId` = `tablemappingtranslate`.`tableMappingId`
    JOIN	`language`
    ON		`language`.`languageId` = `tablemappingtranslate`.`languageId`
          WHERE     " . $this->getAuditFilter();
            if ($this->model->getTableMappingTranslateId(0, 'single')) {
                $sql .= " AND `tablemappingtranslate`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getTableMappingTranslateId(0, 'single') . "'";
            }
            if ($this->model->getTableMappingId()) {
                $sql .= " AND `tablemappingtranslate`.`tableMappingId`='" . $this->model->getTableMappingId() . "'";
            }
            if ($this->model->getTableMappingName()) {
                $sql .= " AND `tablemapping`.`tableMappingName`='" . $this->model->getTableMappingName() . "'";
            }
            if ($this->model->getLanguageId()) {
                $sql .= " AND `tablemappingtranslate`.`languageId`='" . $this->model->getLanguageId() . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
          SELECT                    [tableMappingTranslate].[tableMappingTranslateId],
                    [tableMapping].[tableMappingName],
					[tableMapping].[tableMappingEnglish],
                    [tablemapping].[tableMappingColumnName],
					[tableMappingTranslate].[tableMappingId],
                    [language].[languageIcon],
					[language].[languageDescription],
                    [tableMappingTranslate].[languageId],
                    [tableMappingTranslate].[tableMappingNative],
                    [tableMappingTranslate].[isDefault],
                    [tableMappingTranslate].[isNew],
                    [tableMappingTranslate].[isDraft],
                    [tableMappingTranslate].[isUpdate],
                    [tableMappingTranslate].[isDelete],
                    [tableMappingTranslate].[isActive],
                    [tableMappingTranslate].[isApproved],
                    [tableMappingTranslate].[isReview],
                    [tableMappingTranslate].[isPost],
                    [tableMappingTranslate].[executeBy],
                    [tableMappingTranslate].[executeTime],
                    [staff].[staffName]
          FROM 	[tableMappingTranslate]
          JOIN	[staff]
          ON	[tableMappingTranslate].[executeBy] = [staff].[staffId]
    JOIN	[tableMapping]
    ON		[tableMapping].[tableMappingId] = [tablemappingtranslate].[tableMappingId]
    JOIN	[language]
    ON		[language].[languageId] = [tablemappingtranslate].[languageId]
          WHERE     " . $this->getAuditFilter();
                if ($this->model->getTableMappingTranslateId(0, 'single')) {
                    $sql .= " AND [tableMappingTranslate].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getTableMappingTranslateId(0, 'single') . "'";
                }
                if ($this->model->getTableMappingId()) {
                    $sql .= " AND [tablemappingtranslate].[tableMappingId]='" . $this->model->getTableMappingId() . "'";
                }
                if ($this->model->getTableMappingName()) {
                    $sql .= " AND [tablemapping].[tableMappingName]='" . $this->model->getTableMappingName() . "'";
                }
                if ($this->model->getLanguageId()) {
                    $sql .= " AND [tablemappingtranslate].[languageId]='" . $this->model->getLanguageId() . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
          SELECT                    TABLEMAPPINGTRANSLATE.TABLEMAPPINGTRANSLATEID AS \"tableMappingTranslateId\",
                    TABLEMAPPING.TABLEMAPPINGNAME AS \"tableMappingName\",
					TABLEMAPPING.TABLEMAPPINGCOLUMNNAME AS \"tableMappingColumnName\",
					TABLEMAPPING.TABLEMAPPINGENGLISH AS  \"tableMappingEnglish\",
                    TABLEMAPPINGTRANSLATE.TABLEMAPPINGID AS \"tableMappingId\",
                    LANGUAGE.LANGUAGEICON AS  \"languageIcon\",
					LANGUAGE.LANGUAGEDESCRIPTION AS  \"languageDescription\",
                    TABLEMAPPINGTRANSLATE.LANGUAGEID AS \"languageId\",
                    TABLEMAPPINGTRANSLATE.TABLEMAPPINGNATIVE AS \"tableMappingNative\",
                    TABLEMAPPINGTRANSLATE.ISDEFAULT AS \"isDefault\",
                    TABLEMAPPINGTRANSLATE.ISNEW AS \"isNew\",
                    TABLEMAPPINGTRANSLATE.ISDRAFT AS \"isDraft\",
                    TABLEMAPPINGTRANSLATE.ISUPDATE AS \"isUpdate\",
                    TABLEMAPPINGTRANSLATE.ISDELETE AS \"isDelete\",
                    TABLEMAPPINGTRANSLATE.ISACTIVE AS \"isActive\",
                    TABLEMAPPINGTRANSLATE.ISAPPROVED AS \"isApproved\",
                    TABLEMAPPINGTRANSLATE.ISREVIEW AS \"isReview\",
                    TABLEMAPPINGTRANSLATE.ISPOST AS \"isPost\",
                    TABLEMAPPINGTRANSLATE.EXECUTEBY AS \"executeBy\",
                    TABLEMAPPINGTRANSLATE.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\"
          FROM 	TABLEMAPPINGTRANSLATE
          JOIN	STAFF
          ON	TABLEMAPPINGTRANSLATE.EXECUTEBY = STAFF.STAFFID
 	JOIN	TABLEMAPPING
    ON		TABLEMAPPING.TABLEMAPPINGID = TABLEMAPPINGTRANSLATE.TABLEMAPPINGID
    JOIN	LANGUAGE
    ON		LANGUAGE.LANGUAGEID = TABLEMAPPINGTRANSLATE.LANGUAGEID
         WHERE     " . $this->getAuditFilter();
                    if ($this->model->getTableMappingTranslateId(0, 'single')) {
                        $sql .= " AND TABLEMAPPINGTRANSLATE. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getTableMappingTranslateId(0, 'single') . "'";
                    }
                    if ($this->model->getTableMappingId()) {
                        $sql .= " AND TABLEMAPPINGTRANSLATE.TABLEMAPPINGID='" . $this->model->getTableMappingId() . "'";
                    }
                    if ($this->model->getTableMappingName()) {
                        $sql .= " AND TABLEMAPPING.TABLEMAPPINGNAME='" . $this->model->getTableMappingName() . "'";
                    }
                    if ($this->model->getLanguageId()) {
                        $sql .= " AND TABLEMAPPINGTRANSLATE.LANGUAGEID='" . $this->model->getLanguageId() . "'";
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
                $sql .= " AND `tablemappingtranslate`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [tableMappingTranslate].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND Initcap(TABLEMAPPINGTRANSLATE." . strtoupper(
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
                        'tablemappingtranslate', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'tableMappingTranslate', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'TABLEMAPPINGTRANSLATE', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
                "`tablemappingtranslate`.`tableMappingTranslateId`",
                "`staff`.`staffPassword`"
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $filterArray = array(
                    "[tablemappingtranslate].[tableMappingTranslateId]",
                    "[staff].[staffPassword]"
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $filterArray = array(
                        "TABLEMAPPINGTRANSLATE.TABLEMAPPINGTRANSLATEID",
                        "STAFF.STAFFPASSWORD"
                    );
                }
            }
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('staff', 'tablemappingtranslate', 'tablemapping', 'language');
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array('staff', 'tablemappingtranslate', 'tablemapping', 'language');
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array('STAFF', 'TABLEMAPPINGTRANSLATE', 'TABLEMAPPING', 'LANGUAGE');
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
        if (!($this->model->getTableMappingTranslateId(0, 'single'))) {
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
            if ($this->model->getTableMappingTranslateId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord(
                        'value', $this->model->getTableMappingTranslateId(0, 'single')
                );
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getTableMappingTranslateId(0, 'single'));
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
                    $str .= "<tr>\n";
                    $str .= " <td vAlign=\"top\" align=\"center\">" . $i . ".</td>\n";
                    $str .= " <td vAlign=\"top\" align=\"left\">" . $data['tableMappingName'] . "</td>\n";
                    $str .= " <td vAlign=\"top\" align=\"left\">" . $data['tableMappingEnglish'] . "</td>\n";
                    $str .= " <td vAlign=\"top\" align=\"left\">" . $data['tableMappingColumnName'] . "</td>\n";
                    $str .= " <td vAlign=\"top\"><img src='./images/country/" . $data['languageIcon'] . "'> " . $data['languageDescription'] . "</td>";
                    $str .= " <td vAlign=\"top\">
							<div class=\"col-md-12\">
								<div class=\"input-group\">
									<input type=\"text\" class=\"form-control\" name='tableMappingNative" . $data['tableMappingTranslateId'] . "' id='tableMappingNative" . $data['tableMappingTranslateId'] . "' value='" . $data['tableMappingNative'] . "'>";
                    if ($this->leafAccess['leafAccessUpdateValue'] == 0) {
                        $disabled = "disabled";
                    } else {
                        $disabled = null;
                    }
                    $str .= "			<span class=\"input-group-btn\">\n";
                    $str .= "     			<button type=\"button\" class='btn btn-warning " . $disabled . "' title='" . $this->t['saveButtonLabel'] . "'";
                    $str .= " " . $disabled . " ";
                    $str .= "onClick='updateRecordInline(\"" . $this->getLeafId() . "\",\"" . $this->getControllerPath(
                            ) . "\",\"" . $this->getSecurityToken(
                            ) . "\",\"" . $data['tableMappingTranslateId'] . "\")'>" . $this->t['saveButtonLabel'] . "</button>
									</span>
								</div>
							</div>
							<div id='infoPanelMini" . $data['tableMappingTranslateId'] . "'>
						</div></td>";
                    $str .= "</tr>";
                }
                echo json_encode(array("success" => true, "message" => "complete", "data" => $str));
                exit();
            } else {
                if ($this->getPageOutput() == 'json') {
                    if ($this->model->getTableMappingTranslateId(0, 'single')) {
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
                                                    'value', $this->model->getTableMappingTranslateId(0, 'single')
                                            ),
                                            'nextRecord' => $this->nextRecord(
                                                    'value', $this->model->getTableMappingTranslateId(0, 'single')
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
                                            'value', $this->model->getTableMappingTranslateId(0, 'single')
                                    ),
                                    'nextRecord' => $this->recordSet->nextRecord(
                                            'value', $this->model->getTableMappingTranslateId(0, 'single')
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
        // fake return
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
			FROM 	`tablemappingtranslate`
			WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getTableMappingTranslateId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
			SELECT	[" . $this->model->getPrimaryKeyName() . "] 
			FROM 	[tablemappingtranslate] 
			WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getTableMappingTranslateId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
			SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
			FROM 	TABLEMAPPINGTRANSLATE 
			WHERE  	" . strtoupper(
                            $this->model->getPrimaryKeyName()
                    ) . " = '" . $this->model->getTableMappingTranslateId(0, 'single') . "' ";
        }
        try {
            $result = $this->q->fast($sql);
            $total = intval($this->q->numberRows($result, $sql));
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
                UPDATE  `tablemappingtranslate` 
                SET     `tableMappingNative` = '" . $this->model->getTableMappingNative() . "',
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
                WHERE   `tableMappingTranslateId`='" . $this->model->getTableMappingTranslateId('0', 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE  [tablemappingtranslate] 
                SET     [tableMappingNative] = '" . $this->model->getTableMappingNative() . "',
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
                WHERE   [tableMappingTranslateId]='" . $this->model->getTableMappingTranslateId('0', 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
				UPDATE  TABLEMAPPINGTRANSLATE
                SET     TABLEMAPPINGNATIVE = '" . $this->model->getTableMappingNative() . "',
                        LANGUAGEID = '" . $this->model->getLanguageId() . "',
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
                WHERE   TABLEMAPPINGTRANSLATEID='" . $this->model->getTableMappingTranslateId('0', 'single') . "'";
            }
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
            $this->q->commit();
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
     * Return Table Mapping
     * @return mixed
     */
    public function getTableMapping() {

        $this->service->setServiceOutput('html');
        return $this->service->getTableMapping();
    }

    /**
     * Return Table Mapping
     * @return mixed
     */
    public function getTableMappingColumn() {
        if ($this->model->getTableMappingName()) {
            $this->service->setServiceOutput('option');

            echo $this->service->getTableMappingColumn($this->model->getTableMappingName());
        } else {

            $this->service->setServiceOutput('html');
            return $this->service->getTableMappingColumn();
        }
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
     * Return Database Name
     * @return mixed
     */
    public function getTableMappingDatabaseName() {
        return $this->tableMappingDatabaseName;
    }

    /**
     * Set Database Name
     * @param string $value
     */
    public function setTableMappingDatabaseName($value) {
        $this->tableMappingDatabaseName = $value;
    }

    /**
     * Return Column Name
     * @return int
     */
    public function getTableMappingId() {
        return $this->tableMappingId;
    }

    /**
     * Set Column Name
     * @param string $value
     */
    public function setTableMappingId($value) {
        $this->tableMappingId = $value;
    }

    /**
     * Return Table Name
     * @return string
     */
    public function getTableMappingName() {
        return $this->tableMappingName;
    }

    /**
     * Set Table Name
     * @param string $value
     */
    public function setTableMappingName($value) {
        $this->tableMappingName = $value;
    }

    /**
     * Reporting
     * @see config::excel()
     * @return void
     */
    function excel() {
        
    }

}

/**
 * crud -create,read,update,delete
 * */
if (isset($_POST ['method'])) {
    if (isset($_POST['output'])) {
        $tableMappingTranslateObject = new TableMappingTranslateClass ();
        if ($_POST['securityToken'] != $tableMappingTranslateObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $tableMappingTranslateObject->setLeafId($_POST ['leafId']);
        }
        $tableMappingTranslateObject->setPageOutput($_POST['output']);
        $tableMappingTranslateObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $tableMappingTranslateObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $tableMappingTranslateObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $tableMappingTranslateObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $tableMappingTranslateObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$tableMappingTranslateObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$tableMappingTranslateObject->delete(); 
        }
    }
}
if (isset($_GET ['method'])) {
    $tableMappingTranslateObject = new TableMappingTranslateClass ();
    if ($_GET['securityToken'] != $tableMappingTranslateObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $tableMappingTranslateObject->setLeafId($_GET ['leafId']);
    }
    /**
     * Database Request
     */
    if (isset($_GET ['databaseRequest'])) {
        $tableMappingTranslateObject->setRequestDatabase($_GET ['databaseRequest']);
    }
    if (isset($_GET['databaseName'])) {
        $tableMappingTranslateObject->setTableMappingDatabaseName($_GET['databaseName']);
    }
    if (isset($_GET['tableMappingName'])) {
        $tableMappingTranslateObject->setTableMappingName($_GET['tableMappingName']);
    }
    if (isset($_GET['tableMappingId'])) {
        $tableMappingTranslateObject->setTableMappingId($_GET['tableMappingId']);
    }
    /*
     *  Load the dynamic value
     */
    $tableMappingTranslateObject->execute();

    /**
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'updateStatus') {
        $tableMappingTranslateObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key 
     */
    if (isset($_GET ['tablemappingtranslateCode'])) {
        if (strlen($_GET ['tablemappingtranslateCode']) > 0) {
            $tableMappingTranslateObject->duplicate();
        }
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $tableMappingTranslateObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $tableMappingTranslateObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $tableMappingTranslateObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $tableMappingTranslateObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $tableMappingTranslateObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $tableMappingTranslateObject->excel();
        }
    }

    if (isset($_GET['filter'])) {

        if ($_GET['filter'] == 'tableMappingName') {

            $tableMappingTranslateObject->getTableMapping();
        }
        if ($_GET['filter'] == 'tableMappingId') {
            $tableMappingTranslateObject->getTableMappingColumn();
        }
    }
}
?>