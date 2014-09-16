<?php

namespace Core\System\Security\ApplicationTranslate\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\RecordSet\RecordSet;
use Core\shared\SharedClass;
use Core\System\Security\ApplicationTranslate\Model\ApplicationTranslateModel;
use Core\System\Security\ApplicationTranslate\Service\ApplicationTranslateService;

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
require_once($newFakeDocumentRoot . "v3/system/security/model/applicationTranslateModel.php");
require_once($newFakeDocumentRoot . "v3/system/security/service/applicationTranslateService.php");

/**
 * Class ApplicationTranslateClass
 * this is application translate setting files.This sample template file for
 * master record
 *
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Security\ApplicationTranslate\Controller
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ApplicationTranslateClass extends ConfigClass {

    /**
     * Connection to the database
     *
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;

    /**
     * Php Word Generate Microsoft Excel 2007 Output.Format : docxs
     *
     * @var \PHPWord
     */
    // private $word;
    /**
     * Model
     *
     * @var \Core\System\Security\ApplicationTranslate\Model\ApplicationTranslateModel
     */
    public $model;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     *
     * @var \PHPPowerPoint
     */
    // private $powerPoint;
    /**
     * Service-Business Application Process or other ajax request
     *
     * @var \Core\System\Security\ApplicationTranslate\Service\ApplicationTranslateService
     */
    public $service;

    /**
     * Translation Array
     *
     * @var mixed
     */
    public $translate;

    /**
     * Translation Label
     *
     * @var mixed
     */
    public $t;

    /**
     * Leaf Access
     *
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
     * Php Excel Generate Microsoft Excel 2007 Output.Format : xlsx/pdf
     *
     * @var \PHPExcel
     */
    private $excel;

    /**
     * Record Pagination
     *
     * @var \Core\RecordSet\RecordSet
     */
    private $recordSet;

    /**
     * Document Trail Audit.
     *
     * @var string
     */
    private $documentTrail;

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
        $this->setViewPath("./v3/system/security/view/applicationTranslate.php");
        $this->setControllerPath("./v3/system/security/controller/applicationTranslateController.php");
        $this->setServicePath("./v3/system/security/service/applicationTranslateService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(0);
        $this->setLog(1);
        $this->model = new ApplicationTranslateModel();
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
        $translator->setCurrentTable(
                array(
                    'applicationtranslate',
                    'application'
                )
        );
        $translator->setLeafId($this->getLeafId());
        $translator->execute();

        $this->translate = $translator->getLeafTranslation(); // short because
        // code too long
        $this->t = $translator->getDefaultTranslation(); // short because code
        // too long
        $this->leafAccess = $translator->getLeafAccess();

        $arrayInfo = $translator->getFileInfo();
        $applicationNative = $arrayInfo['applicationNative'];
        $folderNative = $arrayInfo['folderNative'];
        $moduleNative = $arrayInfo['moduleNative'];
        $leafNative = $arrayInfo['leafNative'];
        $this->setReportTitle(
                $applicationNative . " :: " . $moduleNative . " :: " .
                $folderNative . " :: " . $leafNative
        );

        $this->service = new ApplicationTranslateService();
        $this->service->q = $this->q;
        $this->service->t = $this->t;
        $this->service->setVendor($this->getVendor());
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
        $this->excel = new \PHPExcel();
    }

    /**
     * Create
     * @see \Core\ConfigClass::create()
     * @return void
     */
    public function create() {
        
    }

    /**
     * Read
     * @see config::read()
     * @return array
     */
    public function read() {
        if ($this->getPageOutput() == 'json' || $this->getPageOutput() == 'table') {
            header('Content-Type:application/json; charset=utf-8');
        }
        $start = microtime(true);
        // override
        $this->setStart(0);
        $this->setLimit(99999);
        // end override
        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] == 0) {
                if ($this->getVendor() == self::MYSQL) {
                    $this->setAuditFilter(
                            " `applicationtranslate`.`isActive` = 1 "
                    );
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [applicationtranslate].[isActive] = 1 "
                        );
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(
                                    " APPLICATIONTRANSLATE.ISACTIVE = 1 "
                            );
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
		  SELECT                    `applicationtranslate`.`applicationTranslateId`,
                    `application`.`applicationEnglish`,
                    `language`.`languageDescription`,
                    `language`.`languageIcon`,
                    `applicationtranslate`.`applicationNative`,
                    `applicationtranslate`.`isDefault`,
                    `applicationtranslate`.`isNew`,
                    `applicationtranslate`.`isDraft`,
                    `applicationtranslate`.`isUpdate`,
                    `applicationtranslate`.`isDelete`,
                    `applicationtranslate`.`isActive`,
                    `applicationtranslate`.`isApproved`,
                    `applicationtranslate`.`isReview`,
                    `applicationtranslate`.`isPost`,
                    `applicationtranslate`.`executeBy`,
                    `applicationtranslate`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `applicationtranslate`
		  JOIN      `staff`
		  ON        `applicationtranslate`.`executeBy` = `staff`.`staffId`
	JOIN	`application`
	ON		`application`.`applicationId` = `applicationtranslate`.`applicationId`
	JOIN	`language`
	ON		`language`.`languageId` = `applicationtranslate`.`languageId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getApplicationTranslateId(0, 'single')) {
                $sql .= " AND `applicationtranslate`.`" .
                        $this->model->getPrimaryKeyName() . "`='" .
                        $this->model->getApplicationTranslateId(0, 'single') .
                        "'";
            }
            if ($this->model->getApplicationId()) {
                $sql .= " AND `applicationtranslate`.`applicationId`='" .
                        $this->model->getApplicationId() . "'";
            }
            if ($this->model->getLanguageId()) {
                $sql .= " AND `applicationtranslate`.`languageId`='" .
                        $this->model->getLanguageId() . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
		  	SELECT	[applicationtranslate].[applicationTranslateId],
                    [application].[applicationEnglish],
                    [language].[languageDescription],
                    [applicationtranslate].[applicationNative],
                    [applicationtranslate].[isDefault],
                    [applicationtranslate].[isNew],
                    [applicationtranslate].[isDraft],
                    [applicationtranslate].[isUpdate],
                    [applicationtranslate].[isDelete],
                    [applicationtranslate].[isActive],
                    [applicationtranslate].[isApproved],
                    [applicationtranslate].[isReview],
                    [applicationtranslate].[isPost],
                    [applicationtranslate].[executeBy],
                    [applicationtranslate].[executeTime],
                    [staff].[staffName]
			FROM 	[applicationtranslate]
			JOIN	[staff]
			ON		[applicationtranslate].[executeBy] = [staff].[staffId]
			JOIN	[application]
			ON		[application].[applicationId] = [applicationtranslate].[applicationId]
			JOIN	[language]
			ON		[language].[languageId] = [applicationtranslate].[languageId]
		  WHERE     " . $this->getAuditFilter();
                if ($this->model->getApplicationTranslateId(0, 'single')) {
                    $sql .= " AND [applicationtranslate].[" .
                            $this->model->getPrimaryKeyName() . "]		=	'" . $this->model->getApplicationTranslateId(
                                    0, 'single'
                            ) . "'";
                }
                if ($this->model->getApplicationId()) {
                    $sql .= " AND [applicationtranslate].[applicationId]='" . $this->model->getApplicationId() . "'";
                }
                if ($this->model->getLanguageId()) {
                    $sql .= " AND [applicationtranslate].[languageId]='" . $this->model->getLanguageId() . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
		    SELECT  APPLICATIONTRANSLATE.APPLICATIONTRANSLATEID AS  \"applicationTranslateId\",
                    COMPANY.COMPANYDESCRIPTION                  AS  \"companyDescription\",
                    APPLICATIONTRANSLATE.COMPANYID              AS  \"companyId\",
                    APPLICATION.APPLICATIONDESCRIPTION          AS  \"applicationDescription\",
                    APPLICATION.APPLICATIONENGLISH              AS  \"applicationEnglish\",
                    APPLICATIONTRANSLATE.APPLICATIONID          AS  \"applicationId\",
                    LANGUAGE.LANGUAGEDESCRIPTION                AS  \"languageDescription\",
                    APPLICATIONTRANSLATE.LANGUAGEID             AS  \"languageId\",
                    APPLICATIONTRANSLATE.APPLICATIONNATIVE      AS  \"applicationNative\",
                    APPLICATIONTRANSLATE.ISDEFAULT              AS  \"isDefault\",
                    APPLICATIONTRANSLATE.ISNEW                  AS  \"isNew\",
                    APPLICATIONTRANSLATE.ISDRAFT                AS  \"isDraft\",
                    APPLICATIONTRANSLATE.ISUPDATE               AS  \"isUpdate\",
                    APPLICATIONTRANSLATE.ISDELETE               AS  \"isDelete\",
                    APPLICATIONTRANSLATE.ISACTIVE               AS  \"isActive\",
                    APPLICATIONTRANSLATE.ISAPPROVED             AS  \"isApproved\",
                    APPLICATIONTRANSLATE.ISREVIEW               AS  \"isReview\",
                    APPLICATIONTRANSLATE.ISPOST                 AS  \"isPost\",
                    APPLICATIONTRANSLATE.EXECUTEBY              AS  \"executeBy\",
                    APPLICATIONTRANSLATE.EXECUTETIME            AS  \"executeTime\",
                    STAFF.STAFFNAME                             AS  \"staffName\"
            FROM 	APPLICATIONTRANSLATE
            JOIN    STAFF
            ON	    APPLICATIONTRANSLATE.EXECUTEBY = STAFF.STAFFID

            JOIN	COMPANY
            ON		COMPANY.COMPANYID = APPLICATIONTRANSLATE.COMPANYID

            JOIN	APPLICATION
            ON		APPLICATION.APPLICATIONID = APPLICATIONTRANSLATE.APPLICATIONID

            JOIN	LANGUAGE
            ON		LANGUAGE.LANGUAGEID = APPLICATIONTRANSLATE.LANGUAGEID
				WHERE   " . $this->getAuditFilter();
                    if ($this->model->getApplicationTranslateId(0, 'single')) {
                        $sql .= " AND APPLICATIONTRANSLATE. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getApplicationTranslateId(0, 'single') . "'";
                    }
                    if ($this->model->getApplicationId()) {
                        $sql .= " AND APPLICATIONTRANSLATE.APPLICATIONID='" . $this->model->getApplicationId() . "'";
                    }
                    if ($this->model->getLanguageId()) {
                        $sql .= " AND APPLICATIONTRANSLATE.LANGUAGEID='" . $this->model->getLanguageId() . "'";
                    }
                } else {
                    header('Content-Type:application/json; charset=utf-8');
                    echo json_encode(
                            array(
                                "success" => false,
                                "message" => $this->t['databaseNotFoundMessageLabel']
                            )
                    );
                    exit();
                }
            }
        }
        /**
         * filter column based on first character
         */
        if ($this->getCharacterQuery()) {
            if ($this->getVendor() == self::MYSQL) {
                $sql .= " AND `applicationtranslate`.`" .
                        $this->model->getFilterCharacter() . "` like '" .
                        $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [applicationtranslate].[" .
                            $this->model->getFilterCharacter() . "] like '" .
                            $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND APPLICATIONTRANSLATE" .
                                strtoupper($this->model->getFilterCharacter()) .
                                " = '" . $this->getCharacterQuery() . "'";
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
                        'applicationtranslate', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'applicationtranslate', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'APPLICATIONTRANSLATE', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                        );
                    }
                }
            }
        }
        /**
         * filter column don't want to filter.Example may contain sensitive
         * information or unwanted to be search.
         *
         * E.g $filterArray=array('`leaf`.`leafId`');
         *
         * @var array $filterArray ;
         */
        $filterArray = array(
            'applicationTranslateId'
        );
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array(
                'applicationtranslate'
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array(
                    'applicationtranslate'
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array(
                        'APPLICATIONTRANSLATE'
                    );
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
        if ($this->q->getExecute() == 'fail') {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(
                    array(
                        "success" => false,
                        "message" => $this->q->getResponse()
                    )
            );
            exit();
        }
        $total = $this->q->numberRows();
        if ($this->getSortField()) {
            if ($this->getVendor() == self::MYSQL) {
                $sql .= "	ORDER BY `" . $this->getSortField() . "` " .
                        $this->getOrder() . " ";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= "	ORDER BY [" . $this->getSortField() . "] " .
                            $this->getOrder() . " ";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= "	ORDER BY " . strtoupper($this->getSortField()) .
                                " " . strtoupper($this->getOrder()) . " ";
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
        $_SESSION['sql'] = $sql; // push to session so can make report via
        // excel and pdf
        $_SESSION['start'] = $this->getStart();
        $_SESSION['limit'] = $this->getLimit();

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
                     */
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
        //   $this->exceptionMessage($this->q->getSql()); // debugging purpose only
        /*
         * Only Execute One Query
         */
        if (!($this->model->getApplicationTranslateId(0, 'single'))) {
            try {
                $this->q->read($sqlDerived);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
            if ($this->q->getExecute() == 'fail') {
                header('Content-Type:application/json; charset=utf-8');
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => $this->q->getResponse()
                        )
                );
                exit();
            }
        }
        //  $this->exceptionMessage($this->q->getSql());  // debugging purpose only
        $items = array();
        $i = 1;
        while (($row = $this->q->fetchAssoc()) == true) {
            $row['total'] = $total; // small override
            $row['counter'] = $this->getStart() + 15;
            if ($this->model->getApplicationTranslateId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord(
                        'value', $this->model->getApplicationTranslateId(0, 'single')
                );
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getApplicationTranslateId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items[] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else {
            if ($this->getPageOutput() == 'table') {

                $i = 0;
                $str = "";
                foreach ($items as $data) {
                    $i++;
                    $str .= "
						<tr>\n";
                    $str .= "
							<td vAlign=\"top\" align=\"center\">
								<div align=\"center\">" . $i . ". </div></td>\n";
                    $str .= "
							<td vAlign=\"top\">" . $data['applicationEnglish'] . "</td>\n";
                    $str .= "
							<td vAlign=\"top\" align=\"left\">\n
								<div align=\"left\">\n";
                    if (file_exists($this->getFakeDocumentRoot() . "images/country/" . $data['languageIcon'])) {
                        $str .= "<img src=\"./images/country/" . $data['languageIcon'] . "\"> " . $data['languageDescription'];
                    } else {
                        $str .= "Image Country Not Available - " . $data['languageDescription'];
                    }
                    $str .= "
								</div>
							</td>";
                    $str .= "
							<td vAlign=\"top\">
								<div class=\"col-xs-12 col-sm-12 col-md-12\">
									<div class=\"input-group\">
										<input type=\"text\" class=\"form-control\" name=\"applicationNative" . $data['applicationTranslateId'] . "\" id=\"applicationNative" . $data['applicationTranslateId'] . "\" value=\"" . $data['applicationNative'] . "\">\n";
                    if ($this->leafAccess['leafAccessUpdateValue'] == 0) {
                        $disabled = "disabled";
                    } else {
                        $disabled = null;
                    }
                    $str .= "
										<span class=\"input-group-btn\">\n
											<button type=\"button\" class=\"btn btn-warning " . $disabled . "\" title=\"" . $this->t['saveButtonLabel'] . "\"\n";
                    $str .= " " . $disabled . " ";
                    $str .= "				onClick=\"updateRecordInline(" . intval($this->getLeafId()) . ",'" . $this->getControllerPath() . "','" . $this->getSecurityToken() . "'," . intval($data['applicationTranslateId']) . ")\">" . $this->t['saveButtonLabel'] . "</button>
										</span>\n
									</div>\n
								</div>\n
								<div id=\"infoPanelMini" . $data['applicationTranslateId'] . "\"></div>\n
							</td>\n";
                    $str .= "
						</tr>\n";
                }
                echo json_encode(array("success" => true, "message" => "complete", "data" => $str));
                exit();
            } else {
                if ($this->getPageOutput() == 'json') {
                    if ($this->model->getApplicationTranslateId(0, 'single')) {
                        $end = microtime(true);
                        $time = $end - $start;
                        echo str_replace(
                                array(
                            "[",
                            "]"
                                ), "", json_encode(
                                        array(
                                            'success' => true,
                                            'total' => $total,
                                            'message' => $this->t['viewRecordMessageLabel'],
                                            'time' => $time,
                                            'firstRecord' => $this->firstRecord(
                                                    'value'
                                            ),
                                            'previousRecord' => $this->previousRecord(
                                                    'value', $this->model->getApplicationTranslateId(
                                                            0, 'single'
                                                    )
                                            ),
                                            'nextRecord' => $this->nextRecord(
                                                    'value', $this->model->getApplicationTranslateId(
                                                            0, 'single'
                                                    )
                                            ),
                                            'lastRecord' => $this->lastRecord(
                                                    'value'
                                            ),
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
                                    'firstRecord' => $this->recordSet->firstRecord(
                                            'value'
                                    ),
                                    'previousRecord' => $this->recordSet->previousRecord(
                                            'value', $this->model->getApplicationTranslateId(
                                                    0, 'single'
                                            )
                                    ),
                                    'nextRecord' => $this->recordSet->nextRecord(
                                            'value', $this->model->getApplicationTranslateId(
                                                    0, 'single'
                                            )
                                    ),
                                    'lastRecord' => $this->recordSet->lastRecord(
                                            'value'
                                    ),
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
     * @see \Core\ConfigClass::update()
     */
    function update() {
        header('Content-Type:application/json; charset=utf-8');
        $start = microtime(true);
        $baseMemory = intval(memory_get_usage());
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $this->q->start();
        $this->model->update();
        // before updating check the id exist or not . if exist continue to
        // update else warning the user
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
		SELECT	`" . $this->model->getPrimaryKeyName() . "`
		FROM 	`applicationtranslate`
		WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" .
                    $this->model->getApplicationTranslateId(0, 'single') . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
			SELECT	[" . $this->model->getPrimaryKeyName() . "]
			FROM 	[applicationtranslate]
			WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" .
                        $this->model->getApplicationTranslateId(0, 'single') .
                        "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
			SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
			FROM 	APPLICATIONTRANSLATE
			WHERE  	" . strtoupper(
                                    $this->model->getPrimaryKeyName()
                            ) . " = '" . $this->model->getApplicationTranslateId(
                                    0, 'single'
                            ) . "' ";
                }
            }
        }

        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(
                    array(
                        "success" => false,
                        "message" => $e->getMessage()
                    )
            );
            exit();
        }
        try {
            $total = $this->q->numberRows($result, $sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(
                    array(
                        "success" => false,
                        "message" => $e->getMessage()
                    )
            );
            exit();
        }
        if ($total == 0) {
            echo json_encode(
                    array(
                        "success" => false,
                        "message" => $this->t['recordNotFoundMessageLabel']
                    )
            );
            exit();
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
                UPDATE  `applicationtranslate`
                SET     `applicationNative` = '" .
                        $this->model->getApplicationNative() . "',
                        `isDefault` = '" .
                        $this->model->getIsDefault('0', 'single') . "',
                        `isNew` = '" . $this->model->getIsNew('0', 'single') . "',
                        `isDraft` = '" .
                        $this->model->getIsDraft('0', 'single') . "',
                        `isUpdate` = '" .
                        $this->model->getIsUpdate('0', 'single') . "',
                        `isDelete` = '" .
                        $this->model->getIsDelete('0', 'single') . "',
                        `isActive` = '" .
                        $this->model->getIsActive('0', 'single') . "',
                        `isApproved` = '" .
                        $this->model->getIsApproved('0', 'single') . "',
                        `isReview` = '" .
                        $this->model->getIsReview('0', 'single') . "',
                        `isPost` = '" . $this->model->getIsPost(0, 'single') . "',
                        `executeBy` = '" .
                        $this->model->getExecuteBy('0', 'single') . "',
                        `executeTime` = " . $this->model->getExecuteTime() . "
                WHERE   `applicationTranslateId`='" .
                        $this->model->getApplicationTranslateId('0', 'single') .
                        "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
		UPDATE  [applicationtranslate]
                SET     [applicationNative] = '" .
                            $this->model->getApplicationNative() . "',
                        [isDefault] = '" .
                            $this->model->getIsDefault(0, 'single') . "',
                        [isNew] = '" . $this->model->getIsNew(0, 'single') . "',
                        [isDraft] = '" .
                            $this->model->getIsDraft(0, 'single') . "',
                        [isUpdate] = '" .
                            $this->model->getIsUpdate(0, 'single') . "',
                        [isDelete] = '" .
                            $this->model->getIsDelete(0, 'single') . "',
                        [isActive] = '" .
                            $this->model->getIsActive(0, 'single') . "',
                        [isApproved] = '" .
                            $this->model->getIsApproved(0, 'single') . "',
                        [isReview] = '" .
                            $this->model->getIsReview(0, 'single') . "',
                        [isPost] = '" . $this->model->getIsPost(0, 'single') . "',
                        [executeBy] = '" .
                            $this->model->getExecuteBy(0, 'single') . "',
                        [executeTime] = " . $this->model->getExecuteTime() .
                            "
            WHERE   [applicationTranslateId]='" . $this->model->getApplicationTranslateId(
                                    '0', 'single'
                            ) . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
		UPDATE  APPLICATIONTRANSLATE
                SET     APPLICATIONNATIVE = '" .
                                $this->model->getApplicationNative() . "',
                        ISDEFAULT = '" .
                                $this->model->getIsDefault(0, 'single') . "',
                        ISNEW = '" . $this->model->getIsNew(0, 'single') . "',
                        ISDRAFT = '" . $this->model->getIsDraft(0, 'single') . "',
                        ISUPDATE = '" .
                                $this->model->getIsUpdate(0, 'single') . "',
                        ISDELETE = '" .
                                $this->model->getIsDelete(0, 'single') . "',
                        ISACTIVE = '" .
                                $this->model->getIsActive(0, 'single') . "',
                        ISAPPROVED = '" .
                                $this->model->getIsApproved(0, 'single') . "',
                        ISREVIEW = '" .
                                $this->model->getIsReview(0, 'single') . "',
                        ISPOST = '" . $this->model->getIsPost(0, 'single') . "',
                        EXECUTEBY = '" .
                                $this->model->getExecuteBy(0, 'single') . "',
                        EXECUTETIME = " . $this->model->getExecuteTime() .
                                "
                    WHERE APPLICATIONTRANSLATEID='" . $this->model->getApplicationTranslateId(
                                        '0', 'single'
                                ) . "'";
                    }
                }
            }

            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        //@todo future  PHP5.5 only
        //finally {
        $this->q->commit();
        //}
        $end = microtime(true);
        $time = $end - $start;
        $memoryUsage = intval(memory_get_usage()) - intval($baseMemory);

        //$stakeTrace = error_log(serialize(debug_backtrace()));
        echo json_encode(
                array(
                    "success" => true,
                    "message" => $this->t['updateRecordTextLabel'],
                    "time" => $time,
                    "memoryUsage" => $this->getFileSize($memoryUsage),
                    "sql" => $sql
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
     * Return Application Data
     * @return mixed
     */
    public function getApplication() {
        $this->service->setServiceOutput('html');
        return $this->service->getApplication();
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
     * @return void
     */
    function excel() {
        
    }

}

/**
 * crud -create,read,update,delete
 */
if (isset($_POST['method'])) {
    if (isset($_POST['output'])) {
        $applicationTranslateObject = new ApplicationTranslateClass();
        if ($_POST['securityToken'] != $applicationTranslateObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         * Load the dynamic value
         */
        if (isset($_POST['leafId'])) {
            $applicationTranslateObject->setLeafId($_POST['leafId']);
        }
        $applicationTranslateObject->setPageOutput($_POST['output']);
        $applicationTranslateObject->execute();
        /*
         * Crud Operation (Create Read Update Delete/Destroy)
         */
        if ($_POST['method'] == 'create') {
            $applicationTranslateObject->create();
        }
        if ($_POST['method'] == 'save') {
            $applicationTranslateObject->update();
        }
        if ($_POST['method'] == 'read') {
            $applicationTranslateObject->read();
        }
        if ($_POST['method'] == 'delete') {
            $applicationTranslateObject->delete();
        }
        if ($_POST['method'] == 'posting') {
            // $applicationTranslateObject->posting();
        }
        if ($_POST['method'] == 'reverse') {
            // $applicationTranslateObject->delete();
        }
    }
}
if (isset($_GET['method'])) {
    $applicationTranslateObject = new ApplicationTranslateClass();
    if ($_GET['securityToken'] != $applicationTranslateObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     * initialize Value before load in the loader
     */
    if (isset($_GET['leafId'])) {
        $applicationTranslateObject->setLeafId($_GET['leafId']);
    }
    /*
     * Admin Only
     */
    if (isset($_GET['isAdmin'])) {
        $applicationTranslateObject->setIsAdmin($_GET['isAdmin']);
    }
    /**
     * Database Request
     */
    if (isset($_GET['databaseRequest'])) {
        $applicationTranslateObject->setRequestDatabase(
                $_GET['databaseRequest']
        );
    }
    /*
     * Load the dynamic value
     */
    $applicationTranslateObject->execute();
    /*
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET['method'] == 'updateStatus') {
        $applicationTranslateObject->updateStatus();
    }
    if ($_GET['method'] == 'dataNavigationRequest') {
        if ($_GET['dataNavigation'] == 'firstRecord') {
            $applicationTranslateObject->firstRecord('json');
        }
        if ($_GET['dataNavigation'] == 'previousRecord') {
            $applicationTranslateObject->previousRecord('json', 0);
        }
        if ($_GET['dataNavigation'] == 'nextRecord') {
            $applicationTranslateObject->nextRecord('json', 0);
        }
        if ($_GET['dataNavigation'] == 'lastRecord') {
            $applicationTranslateObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting
     */
    if (isset($_GET['mode'])) {
        $applicationTranslateObject->setReportMode($_GET['mode']);
        if ($_GET['mode'] == 'excel' || $_GET['mode'] == 'pdf' ||
                $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' ||
                $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $applicationTranslateObject->excel();
        }
    }
    if (isset($_GET['applicationId'])) {
        $applicationTranslateObject->getApplication();
    }
    if (isset($_GET['languageId'])) {
        $applicationTranslateObject->getLanguage();
    }
}
?>