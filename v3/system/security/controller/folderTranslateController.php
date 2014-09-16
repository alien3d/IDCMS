<?php

namespace Core\System\Security\FolderTranslate\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\RecordSet\RecordSet;
use Core\shared\SharedClass;
use Core\System\Security\FolderTranslate\Model\FolderTranslateModel;
use Core\System\Security\FolderTranslate\Service\FolderTranslateService;

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
require_once($newFakeDocumentRoot . "v3/system/security/model/folderTranslateModel.php");
require_once($newFakeDocumentRoot . "v3/system/security/service/folderTranslateService.php");

/**
 * Class FolderTranslateClass
 * this is folder translate setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Security\FolderTranslate\Controller
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class FolderTranslateClass extends ConfigClass {

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
     * @var \Core\System\Security\FolderTranslate\Model\FolderTranslateModel
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\System\Security\FolderTranslate\Service\FolderTranslateService
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
        $this->setViewPath("./v3/system/security/view/folderTranslate.php");
        $this->setControllerPath("./v3/system/security/controller/folderTranslateController.php");
        $this->setServicePath("./v3/system/security/service/folderTranslateService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(0);
        $this->setLog(1);
        $this->model = new FolderTranslateModel();
        $this->model->setVendor($this->getVendor());
        $this->model->execute();
        if ($this->getVendor() == self::MYSQL) {
            $this->q = new \Core\Database\Mysql\Vendor();
        } else if ($this->getVendor() == self::MSSQL) {
            $this->q = new \Core\Database\Mssql\Vendor();
        } else if ($this->getVendor() == self::ORACLE) {
            $this->q = new \Core\Database\Oracle\Vendor();
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

        $arrayInfo = $translator->getFileInfo('foldertranslate.php');
        $applicationNative = $arrayInfo['applicationNative'];
        $folderNative = $arrayInfo['folderNative'];
        $moduleNative = $arrayInfo['moduleNative'];
        $leafNative = $arrayInfo['leafNative'];
        $this->setReportTitle(
                $applicationNative . " :: " . $moduleNative . " :: " . $folderNative . " :: " . $leafNative
        );

        $this->service = new FolderTranslateService();
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
                    $this->setAuditFilter(" `foldertranslate`.`isActive` = 1 ");
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(" [foldertranslate].[isActive] = 1 ");
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(" FOLDERTRANSLATE.ISACTIVE = 1 ");
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
            SELECT  `foldertranslate`.`folderTranslateId`,
                    `application`.`applicationEnglish`,
                    `module`.`moduleEnglish`,
                    `folder`.`folderEnglish`,
                    `language`.`languageDescription`,
                    `language`.`languageIcon`,
                    `foldertranslate`.`folderNative`,
                    `foldertranslate`.`isDefault`,
                    `foldertranslate`.`isNew`,
                    `foldertranslate`.`isDraft`,
                    `foldertranslate`.`isUpdate`,
                    `foldertranslate`.`isDelete`,
                    `foldertranslate`.`isActive`,
                    `foldertranslate`.`isApproved`,
                    `foldertranslate`.`isReview`,
                    `foldertranslate`.`isPost`,
                    `foldertranslate`.`executeBy`,
                    `foldertranslate`.`executeTime`,
                    `staff`.`staffName`
            FROM    `foldertranslate`
            JOIN    `staff`
            ON      `foldertranslate`.`executeBy` = `staff`.`staffId`
            JOIN    `folder`
            ON      `folder`.`folderId` = `foldertranslate`.`folderId`
            JOIN    `module`
            ON      `folder`.`moduleId` = `module`.`moduleId`
            JOIN    `application`
            ON      `folder`.`applicationId` = `application`.`applicationId`
            JOIN    `language`
            ON      `language`.`languageId` = `foldertranslate`.`languageId`
            WHERE   " . $this->getAuditFilter();
            $sql .= " AND `language`.`isImportant` =1";
            if ($this->model->getFolderTranslateId(0, 'single')) {
                $sql .= " AND `foldertranslate`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getFolderTranslateId(0, 'single') . "'";
            }
            if ($this->model->getApplicationId()) {
                $sql .= " AND `folder`.`applicationId`		=	'" . $this->model->getApplicationId() . "'";
            }
            if ($this->model->getModuleId()) {
                $sql .= " AND `folder`.`moduleId`		=	'" . $this->model->getModuleId() . "'";
            }
            if ($this->model->getFolderId()) {
                $sql .= " AND `folder`.`folderId`		=	'" . $this->model->getFolderId() . "'";
            }
            if ($this->model->getLanguageId()) {
                $sql .= " AND `folderTranslate`.`languageId`	=	'" . $this->model->getLanguageId() . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
            SELECT  [foldertranslate].[folderTranslateId],
                    [application].[applicationEnglish],
                    [module].[moduleEnglish],
                    [folder].[folderEnglish],
                    [language].[languageDescription],
                    [language].[languageIcon],
                    [foldertranslate].[folderNative],
                    [foldertranslate].[isDefault],
                    [foldertranslate].[isNew],
                    [foldertranslate].[isDraft],
                    [foldertranslate].[isUpdate],
                    [foldertranslate].[isDelete],
                    [foldertranslate].[isActive],
                    [foldertranslate].[isApproved],
                    [foldertranslate].[isReview],
                    [foldertranslate].[isPost],
                    [foldertranslate].[executeBy],
                    [foldertranslate].[executeTime],
                    [staff].[staffName]
            FROM    [foldertranslate]
            JOIN    [staff]
            ON      [foldertranslate].[executeBy] = [staff].[staffId]
            JOIN    [folder]
            ON      [folder].[folderId] = [foldertranslate].[folderId]
            JOIN    [module]
            ON      [folder].[moduleId] = [module].[moduleId]
            JOIN    [" . $this->q->getCoreDatabase() . "`.`application]
            ON      [folder].[applicationId] = [application].[applicationId]
            JOIN    [language]
            ON      [language].[languageId] = [foldertranslate].[languageId]
            WHERE   " . $this->getAuditFilter();
                $sql .= " AND [language].[isImportant] =1 ";
                if ($this->model->getFolderTranslateId(0, 'single')) {
                    $sql .= " AND [foldertranslate].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getFolderTranslateId(0, 'single') . "'";
                }
                if ($this->model->getModuleId()) {
                    $sql .= " AND [folder].[moduleId]		=	'" . $this->model->getModuleId() . "'";
                }
                if ($this->model->getFolderId()) {
                    $sql .= " AND [folder].[folderId]		=	'" . $this->model->getFolderId() . "'";
                }
                if ($this->model->getLanguageId()) {
                    $sql .= " AND [folderTranslate].[languageId]	=	'" . $this->model->getLanguageId() . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
		  SELECT                    FOLDERTRANSLATE.FOLDERTRANSLATEID,
                    FOLDER.FOLDERID,
                    LANGUAGE.LANGUAGEID,
                    FOLDERTRANSLATE.FOLDERNATIVE,
                    FOLDERTRANSLATE.ISDEFAULT,
                    FOLDERTRANSLATE.ISNEW,
                    FOLDERTRANSLATE.ISDRAFT,
                    FOLDERTRANSLATE.ISUPDATE,
                    FOLDERTRANSLATE.ISDELETE,
                    FOLDERTRANSLATE.ISACTIVE,
                    FOLDERTRANSLATE.ISAPPROVED,
                    FOLDERTRANSLATE.ISREVIEW,
                    FOLDERTRANSLATE.ISPOST,
                    FOLDERTRANSLATE.EXECUTEBY,
                    FOLDERTRANSLATE.EXECUTETIME,
                    STAFF.STAFFNAME
		  FROM 	FOLDERTRANSLATE
		  JOIN	STAFF
		  ON	FOLDERTRANSLATE.EXECUTEBY = STAFF.STAFFID
 	JOIN	FOLDER
	ON		FOLDER.FOLDERID = FOLDERTRANSLATE.FOLDERID
	JOIN	LANGUAGE
	ON		LANGUAGE.LANGUAGEID = FOLDERTRANSLATE.LANGUAGEID
         WHERE     " . $this->getAuditFilter();
                    $sql .= " AND LANGUAGE.ISIMPORTANT =1";
                    if ($this->model->getFolderTranslateId(0, 'single')) {
                        $sql .= " AND FOLDERTRANSLATE. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getFolderTranslateId(0, 'single') . "'";
                    }
                    if ($this->model->getModuleId()) {
                        $sql .= " AND FOLDER.MODULEID		=	'" . $this->model->getModuleId() . "'";
                    }
                    if ($this->model->getFolderId()) {
                        $sql .= " AND FOLDER.FOLDERID		=	'" . $this->model->getFolderId() . "'";
                    }
                    if ($this->model->getLanguageId()) {
                        $sql .= " AND FOLDERTRANSLATE.LANGUAGEID	=	'" . $this->model->getLanguageId() . "'";
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
                $sql .= " AND `foldertranslate`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [foldertranslate].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND Initcap(FOLDERTRANSLATE." . strtoupper(
                                        $this->model->getFilterCharacter()
                                ) . ") = Initcap('" . $this->getCharacterQuery() . "%');";
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
                        'foldertranslate', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'foldertranslate', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'FOLDERTRANSLATE', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
        $filterArray = array('folderTranslateId');
        /**
         * filter table
         * @variables $tableArray
         */
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('foldertranslate');
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array('foldertranslate');
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array('FOLDERTRANSLATE');
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
						SELECT *
						FROM ( SELECT	a.*,
												rownum r
						FROM (

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
        if (!($this->model->getFolderTranslateId(0, 'single'))) {
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
            if ($this->model->getFolderTranslateId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord(
                        'value', $this->model->getFolderTranslateId(0, 'single')
                );
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getFolderTranslateId(0, 'single'));
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
                    $str .= "<td vAlign=\"top\">" . $i . "</td>\n";
                    $str .= "<td vAlign=\"top\">" . $data['applicationEnglish'] . "</td>\n";
                    $str .= "<td vAlign=\"top\">" . $data['moduleEnglish'] . "</td>\n";
                    $str .= "<td vAlign=\"top\">" . $data['folderEnglish'] . "</td>\n";
                    $str .= "<td vAlign=\"top\" align=\"left\"><div align=\"left\">";
                    if (file_exists($this->getFakeDocumentRoot() . "images/country/" . $data['languageIcon'])) {
                        $str .= "<img src=\"./images/country/" . $data['languageIcon'] . "\"> " . $data['languageDescription'];
                    } else {
                        $str .= "Image Country Not Available - " . $data['languageDescription'];
                    }
                    $str .= "</div></td>";
                    $str .= "<td vAlign=\"top\"><div class=\"col-xs-12 col-sm-12 col-md-12\"><div class=\"input-group\"><input type=\"text\" class=\"form-control\" name=\"folderNative" . $data['folderTranslateId'] . "\" id=\"folderNative" . $data['folderTranslateId'] . "\" value=\"" . $data['folderNative'] . "\">\n";

                    if ($this->leafAccess['leafAccessUpdateValue'] == 0) {
                        $disabled = "disabled";
                    } else {
                        $disabled = null;
                    }
                    $str .= "<span class=\"input-group-btn\"><button type=\"button\" class=\"btn btn-warning " . $disabled . "\" title=\"" . $this->t['saveButtonLabel'] . "\"";
                    $str .= " " . $disabled . " ";
                    $str .= "	onClick=\"updateRecordInline(" . intval($this->getLeafId()) . ",'" . $this->getControllerPath() . "','" . $this->getSecurityToken() . "'," . intval($data['folderTranslateId']) . ")\">" . $this->t['saveButtonLabel'] . "</button></span></div></div><div id=\"infoPanelMini" . $data['folderTranslateId'] . "\"></div></td>\n";

                    $str .= "</tr>";
                }

                echo json_encode(array("success" => "true", "message" => "complete", "data" => $str));
                exit();
            } else {
                if ($this->getPageOutput() == 'json') {
                    if ($this->model->getFolderTranslateId(0, 'single')) {
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
                                                    'value', $this->model->getFolderTranslateId(0, 'single')
                                            ),
                                            'nextRecord' => $this->nextRecord(
                                                    'value', $this->model->getFolderTranslateId(0, 'single')
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
                                            'value', $this->model->getFolderTranslateId(0, 'single')
                                    ),
                                    'nextRecord' => $this->recordSet->nextRecord(
                                            'value', $this->model->getFolderTranslateId(0, 'single')
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
		FROM 	`foldertranslate` 
		WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getFolderTranslateId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
			SELECT	[" . $this->model->getPrimaryKeyName() . "] 
			FROM 	[foldertranslate] 
			WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getFolderTranslateId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
			SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
			FROM 	FOLDERTRANSLATE 
			WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getFolderTranslateId(
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
		UPDATE  `foldertranslate` 
                SET     `folderNative` = '" . $this->model->getFolderNative() . "',
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
                WHERE   `folderTranslateId`='" . $this->model->getFolderTranslateId('0', 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
		UPDATE  [foldertranslate] 
                SET     [folderNative] = '" . $this->model->getFolderNative() . "',
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
                WHERE   [folderTranslateId]='" . $this->model->getFolderTranslateId('0', 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE  FOLDERTRANSLATE 
                SET     FOLDERNATIVE = '" . $this->model->getFolderNative() . "',
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
                WHERE   FOLDERTRANSLATEID='" . $this->model->getFolderTranslateId('0', 'single') . "'";
            }
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
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
        if ($this->getApplicationId()) {
            $this->service->setServiceOutput('option');
            echo $this->service->getModule($this->getApplicationId());
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
        if ($this->getApplicationId() && $this->getModuleId()) {
            $this->service->setServiceOutput('option');
            echo $this->service->getFolder($this->getApplicationId(), $this->getModuleId());
        } else if ($this->getApplicationId() && !$this->getModuleId()) {
            $this->service->setServiceOutput('option');
            echo $this->service->getFolder($this->getApplicationId());
        } else {
            $this->service->setServiceOutput('html');
            return $this->service->getFolder();
        }
    }

    /**
     * Return Language Data
     * @return mixed
     */
    public function getLanguage() {
        return $this->service->getLanguage();
    }

    /**
     * Return Application Primary Key
     * @return int
     */
    public function getApplicationId() {
        return $this->applicationId;
    }

    /**
     * Set Appplication Primary Key
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
        $folderTranslateObject = new FolderTranslateClass ();
        if ($_POST['securityToken'] != $folderTranslateObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $folderTranslateObject->setLeafId($_POST ['leafId']);
        }
        $folderTranslateObject->setPageOutput($_POST['output']);
        $folderTranslateObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $folderTranslateObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $folderTranslateObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $folderTranslateObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $folderTranslateObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$folderTranslateObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$folderTranslateObject->delete(); 
        }
    }
}
if (isset($_GET ['method'])) {
    $folderTranslateObject = new FolderTranslateClass ();
    if ($_GET['securityToken'] != $folderTranslateObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $folderTranslateObject->setLeafId($_GET ['leafId']);
    }
    /*
     * Admin Only
     */
    if (isset($_GET ['isAdmin'])) {
        $folderTranslateObject->setIsAdmin($_GET ['isAdmin']);
    }
    /**
     * Database Request
     */
    if (isset($_GET ['databaseRequest'])) {
        $folderTranslateObject->setRequestDatabase($_GET ['databaseRequest']);
    }
    if (isset($_GET['applicationId'])) {
        $folderTranslateObject->setApplicationId($_GET['applicationId']);
    }
    if (isset($_GET['moduleId'])) {
        $folderTranslateObject->setModuleId($_GET['moduleId']);
    }
    /*
     *  Load the dynamic value
     */
    $folderTranslateObject->execute();

    /**
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'updateStatus') {
        $folderTranslateObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key 
     */
    if (isset($_GET ['foldertranslateCode'])) {
        if (strlen($_GET ['foldertranslateCode']) > 0) {
            $folderTranslateObject->duplicate();
        }
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $folderTranslateObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $folderTranslateObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $folderTranslateObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $folderTranslateObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $folderTranslateObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $folderTranslateObject->excel();
        }
    }
    if (isset($_GET['filter'])) {

        if ($_GET['filter'] == 'moduleId') {
            $folderTranslateObject->getModule();
        }
        if ($_GET['filter'] == 'folderId') {
            $folderTranslateObject->getFolder();
        }
    }
}
?>