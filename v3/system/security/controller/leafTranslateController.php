<?php

namespace Core\System\Security\LeafTranslate\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\RecordSet\RecordSet;
use Core\shared\SharedClass;
use Core\System\Security\LeafTranslate\Model\LeafTranslateModel;
use Core\System\Security\LeafTranslate\Service\LeafTranslateService;

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
require_once($newFakeDocumentRoot . "v3/system/security/model/leafTranslateModel.php");
require_once($newFakeDocumentRoot . "v3/system/security/service/leafTranslateService.php");

/**
 * Class LeafTranslateClass
 * this is Leaf Translate setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Security\LeafTranslate\Controller
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class LeafTranslateClass extends ConfigClass {

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
     * @var \Core\System\Security\LeafTranslate\Model\LeafTranslateModel
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\System\Security\LeafTranslate\Service\LeafTranslateService
     */
    public $service;

    /**
     * Translation Table Column Array
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
        $this->setViewPath("./v3/system/security/view/leafTranslate.php");
        $this->setControllerPath("./v3/system/security/controller/leafTranslateController.php");
        $this->setServicePath("./v3/system/security/service/leafTranslateService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(0);
        $this->setLog(1);
        $this->model = new LeafTranslateModel();
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

        $this->service = new LeafTranslateService();
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
                            " `leaftranslate`.`isActive` = 1  AND `leaftranslate`.`companyId`='" . $this->getCompanyId(
                            ) . "' "
                    );
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [leafTranslate].[isActive] = 1 AND [leafTranslate].[companyId]='" . $this->getCompanyId(
                                ) . "' "
                        );
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(
                                    " LEAFTRANSLATE.ISACTIVE = 1  AND LEAFTRANSLATE.COMPANYID='" . $this->getCompanyId(
                                    ) . "'"
                            );
                        }
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter("   `leaftranslate`.`companyId`='" . $this->getCompanyId() . "'	");
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(" [leafTranslate].[companyId]='" . $this->getCompanyId() . "' ");
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(" LEAFTRANSLATE.COMPANYID='" . $this->getCompanyId() . "' ");
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
            SELECT  `leaftranslate`.`leafTranslateId`,
                    `application`.`applicationEnglish`,
                    `module`.`moduleEnglish`,
                    `folder`.`folderEnglish`,
                    `leaf`.`leafEnglish`,
                    `company`.`companyDescription`,
                    `leaftranslate`.`companyId`,
                    `leaf`.`leafDescription`,
                    `leaftranslate`.`leafId`,
                    `language`.`languageIcon`,
                    `language`.`languageDescription`,
                    `leaftranslate`.`languageId`,
                    `leaftranslate`.`leafNative`,
                    `leaftranslate`.`isDefault`,
                    `leaftranslate`.`isNew`,
                    `leaftranslate`.`isDraft`,
                    `leaftranslate`.`isUpdate`,
                    `leaftranslate`.`isDelete`,
                    `leaftranslate`.`isActive`,
                    `leaftranslate`.`isApproved`,
                    `leaftranslate`.`isReview`,
                    `leaftranslate`.`isPost`,
                    `leaftranslate`.`executeBy`,
                    `leaftranslate`.`executeTime`
            FROM    `leaftranslate`
            JOIN	`company`
            USING   (`companyId`)
            JOIN	`leaf`
            USING   (`companyId`,`leafId`)
            JOIN	`language`
            USING   (`companyId`,`languageId`)
            JOIN	`folder`
            USING   (`companyId`,`applicationId`,`moduleId`,`folderId`)
            JOIN	`module`
            USING   (`companyId`,`applicationId`,`moduleId`)
            JOIN	`application`
            USING   (`companyId`,`applicationId`)
            WHERE     " . $this->getAuditFilter();
            if ($this->model->getLeafTranslateId(0, 'single')) {
                $sql .= " AND `leaftranslate`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getLeafTranslateId(0, 'single') . "'";
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
                $sql .= " AND `leaftranslate`.`leafid`='" . $this->model->getLeafIdTemp() . "'";
            }
            if ($this->model->getLanguageId()) {
                $sql .= " AND `leaftranslate`.`languageid`='" . $this->model->getLanguageId() . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
          SELECT                    [leafTranslate].[leafTranslateId],
                    [company].[companyDescription],
                    [leafTranslate].[companyId],
                    [leaf].[leafDescription],
                    [leafTranslate].[leafId],
                    [language].[languageDescription],
                    [leafTranslate].[languageId],
                    [leafTranslate].[leafNative],
                    [leafTranslate].[isDefault],
                    [leafTranslate].[isNew],
                    [leafTranslate].[isDraft],
                    [leafTranslate].[isUpdate],
                    [leafTranslate].[isDelete],
                    [leafTranslate].[isActive],
                    [leafTranslate].[isApproved],
                    [leafTranslate].[isReview],
                    [leafTranslate].[isPost],
                    [leafTranslate].[executeBy],
                    [leafTranslate].[executeTime],
                    [staff].[staffName]
          FROM 	[leafTranslate]
          JOIN	[staff]
          ON	[leafTranslate].[executeBy] = [staff].[staffId]
    JOIN	[company]
    ON		[company].[companyId] = [leaftranslate].[companyId]
    JOIN	[leaf]
    ON		[leaf].[leafId] = [leaftranslate].[leafId]
    JOIN	[language]
    ON		[language].[languageId] = [leaftranslate].[languageId]
          WHERE     " . $this->getAuditFilter();
                if ($this->model->getLeafTranslateId(0, 'single')) {
                    $sql .= " AND [leafTranslate].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getLeafTranslateId(0, 'single') . "'";
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
                    $sql .= " AND [leaftranslate].[leafid]='" . $this->model->getLeafIdTemp() . "'";
                }
                if ($this->model->getLanguageId()) {
                    $sql .= " AND [leaftranslate].[languageid]='" . $this->model->getLanguageId() . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
          SELECT                    LEAFTRANSLATE.LEAFTRANSLATEID AS \"leafTranslateId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    LEAFTRANSLATE.COMPANYID AS \"companyId\",
                    LEAF.LEAFDESCRIPTION AS  \"leafDescription\",
                    LEAFTRANSLATE.LEAFID AS \"leafId\",
                    LANGUAGE.LANGUAGEDESCRIPTION AS  \"languageDescription\",
                    LEAFTRANSLATE.LANGUAGEID AS \"languageId\",
                    LEAFTRANSLATE.LEAFNATIVE AS \"leafNative\",
                    LEAFTRANSLATE.ISDEFAULT AS \"isDefault\",
                    LEAFTRANSLATE.ISNEW AS \"isNew\",
                    LEAFTRANSLATE.ISDRAFT AS \"isDraft\",
                    LEAFTRANSLATE.ISUPDATE AS \"isUpdate\",
                    LEAFTRANSLATE.ISDELETE AS \"isDelete\",
                    LEAFTRANSLATE.ISACTIVE AS \"isActive\",
                    LEAFTRANSLATE.ISAPPROVED AS \"isApproved\",
                    LEAFTRANSLATE.ISREVIEW AS \"isReview\",
                    LEAFTRANSLATE.ISPOST AS \"isPost\",
                    LEAFTRANSLATE.EXECUTEBY AS \"executeBy\",
                    LEAFTRANSLATE.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\"
          FROM 	LEAFTRANSLATE
          JOIN	STAFF
          ON	LEAFTRANSLATE.EXECUTEBY = STAFF.STAFFID
 	JOIN	COMPANY
    ON		COMPANY.COMPANYID = LEAFTRANSLATE.COMPANYID
    JOIN	LEAF
    ON		LEAF.LEAFID = LEAFTRANSLATE.LEAFID
    JOIN	LANGUAGE
    ON		LANGUAGE.LANGUAGEID = LEAFTRANSLATE.LANGUAGEID
         WHERE     " . $this->getAuditFilter();
                    if ($this->model->getLeafTranslateId(0, 'single')) {
                        $sql .= " AND LEAFTRANSLATE. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getLeafTranslateId(0, 'single') . "'";
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
                        $sql .= " AND LEAFTRANSLATE.LEAFID='" . $this->model->getLeafIdTemp() . "'";
                    }
                    if ($this->model->getLanguageId()) {
                        $sql .= " AND LEAFTRANSLATE.LANGUAGEID='" . $this->model->getLanguageId() . "'";
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
                $sql .= " AND `leaftranslate`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [leafTranslate].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND Initcap(LEAFTRANSLATE." . strtoupper(
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
                        'leaftranslate', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'leafTranslate', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'LEAFTRANSLATE', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
                "`leaftranslate`.`leafTranslateId`",
                "`staff`.`staffPassword`"
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $filterArray = array(
                    "[leaftranslate].[leafTranslateId]",
                    "[staff].[staffPassword]"
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $filterArray = array(
                        "LEAFTRANSLATE.LEAFTRANSLATEID",
                        "STAFF.STAFFPASSWORD"
                    );
                }
            }
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('staff', 'leaftranslate', 'company', 'leaf', 'language');
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array('staff', 'leaftranslate', 'company', 'leaf', 'language');
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array('STAFF', 'LEAFTRANSLATE', 'COMPANY', 'LEAF', 'LANGUAGE');
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
        if (!($this->model->getLeafTranslateId(0, 'single'))) {
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
            if ($this->model->getLeafTranslateId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getLeafTranslateId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getLeafTranslateId(0, 'single'));
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
                $str .= " <tr>\n";
                $str .= "     <td align=\"center\"><div align=\"center\">" . $i . ".</div></td>\n";
                $str .= "     <td vAlign=\"top\">" . $data['applicationEnglish'] . "</td>\n";
                $str .= "     <td vAlign=\"top\">" . $data['moduleEnglish'] . "</td>\n";
                $str .= "     <td vAlign=\"top\">" . $data['folderEnglish'] . "</td>\n";
                $str .= "		<td vAlign=\"top\">" . $data['leafEnglish'] . "</td>\n";
                $str .= "		<td align=\"left\"><div align=\"left\">";
                if (file_exists($this->getFakeDocumentRoot() . "images/country/" . $data['languageIcon'])) {
                    $str .= "<img src=\"./images/country/" . $data['languageIcon'] . "\"> " . $data['languageDescription'];
                } else {
                    $str .= "Image Country Not Available - " . $data['languageDescription'];
                }
                $str .= "</div></td>";
                if ($this->leafAccess['leafAccessUpdateValue'] == 0) {
                    $disabled = "disabled";
                } else {
                    $disabled = null;
                }
                $str .= "
					<td vAlign=\"top\">\n
						<div class=\"col-xs-12 col-sm-12 col-md-12\">\n
							<div class=\"input-group\"><input type=\"text\" class=\"form-control\" name=\"" . $data['leafTranslateId'] . "\" id=\"leafNative" . $data['leafTranslateId'] . "\" value=\"" . $data['leafNative'] . "\">\n";
                $str .= "          <span class=\"input-group-btn\"><button type=\"button\" class=\"btn btn-warning " . $disabled . "\" title=\"" . $this->t['saveButtonLabel'] . "\"";
                $str .= " " . $disabled . " ";
                $str .= " onClick=\"updateRecordInline(" . intval($this->getLeafId()) . ",'" . $this->getControllerPath() . "','" . $this->getSecurityToken() . "'," . intval($data['leafTranslateId']) . ")\">" . $this->t['saveButtonLabel'] . "</button></span></div></div><div id=\"infoPanelMini" . $data['leafTranslateId'] . "\"></div></td>\n";

                $str .= "</tr>";
            }

            echo json_encode(array("success" => true, "message" => "complete", "data" => $str));
            exit();
        } else {
            if ($this->getPageOutput() == 'json') {
                if ($this->model->getLeafTranslateId(0, 'single')) {
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
                                                'value', $this->model->getLeafTranslateId(0, 'single')
                                        ),
                                        'nextRecord' => $this->nextRecord(
                                                'value', $this->model->getLeafTranslateId(0, 'single')
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
                                        'value', $this->model->getLeafTranslateId(0, 'single')
                                ),
                                'nextRecord' => $this->recordSet->nextRecord(
                                        'value', $this->model->getLeafTranslateId(0, 'single')
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
		FROM 	`leaftranslate` 
		WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getLeafTranslateId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
			SELECT	[" . $this->model->getPrimaryKeyName() . "] 
			FROM 	[leaftranslate] 
			WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getLeafTranslateId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
			SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
			FROM 	LEAFTRANSLATE 
			WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getLeafTranslateId(
                            0, 'single'
                    ) . "' ";
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
                UPDATE `leaftranslate` 
                SET    `leafNative` = '" . $this->model->getLeafNative() . "',
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
                WHERE  `leafTranslateId`='" . $this->model->getLeafTranslateId('0', 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE  [leaftranslate] 
                SET     [leafNative] = '" . $this->model->getLeafNative() . "',
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
                WHERE   [leafTranslateId]='" . $this->model->getLeafTranslateId('0', 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE  LEAFTRANSLATE 
                SET     LEAFNATIVE = '" . $this->model->getLeafNative() . "',
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
                WHERE ` LEAFTRANSLATEID`='" . $this->model->getLeafTranslateId('0', 'single') . "'";
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
            //}
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
     * Return Language
     * @return string
     */
    public function getLanguage() {
        return $this->service->getLanguage();
    }

    /**
     * Reporting
     * @see config::excel()
     * @return void
     */
    function excel() {
        
    }

}

if (isset($_POST ['method'])) {
    if (isset($_POST['output'])) {
        $leafTranslateObject = new LeafTranslateClass();
        if ($_POST['securityToken'] != $leafTranslateObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $leafTranslateObject->setLeafId($_POST ['leafId']);
        }
        $leafTranslateObject->setPageOutput($_POST['output']);
        $leafTranslateObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $leafTranslateObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $leafTranslateObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $leafTranslateObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $leafTranslateObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$leafTranslateObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$leafTranslateObject->delete(); 
        }
    }
}
if (isset($_GET ['method'])) {
    $leafTranslateObject = new LeafTranslateClass ();
    if ($_GET['securityToken'] != $leafTranslateObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $leafTranslateObject->setLeafId($_GET ['leafId']);
    }
    /*
     * Admin Only
     */
    if (isset($_GET ['isAdmin'])) {
        $leafTranslateObject->setIsAdmin($_GET ['isAdmin']);
    }
    /**
     * Database Request
     */
    if (isset($_GET ['databaseRequest'])) {
        $leafTranslateObject->setRequestDatabase($_GET ['databaseRequest']);
    }
    if (isset($_GET ['databaseRequest'])) {
        $leafTranslateObject->setRequestDatabase($_GET ['databaseRequest']);
    }
    if (isset($_GET ['databaseRequest'])) {
        $leafTranslateObject->setRequestDatabase($_GET ['databaseRequest']);
    }

    /*
     *  Load the dynamic value
     */
    $leafTranslateObject->execute();
    /**
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'updateStatus') {
        $leafTranslateObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key 
     */
    if (isset($_GET ['leafTranslateCode'])) {
        if (strlen($_GET ['leafTranslateCode']) > 0) {
            $leafTranslateObject->duplicate();
        }
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $leafTranslateObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $leafTranslateObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $leafTranslateObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $leafTranslateObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $leafTranslateObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $leafTranslateObject->excel();
        }
    }
    if (isset($_GET['filter'])) {

        if ($_GET['filter'] == 'moduleId') {
            $leafTranslateObject->getModule();
        }
        if ($_GET['filter'] == 'folderId') {

            $leafTranslateObject->getFolder();
        }
        if ($_GET['filter'] == 'leafIdTemp') {
            $leafTranslateObject->getLeaf();
        }
    }
}
?>