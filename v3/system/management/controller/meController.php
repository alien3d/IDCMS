<?php

namespace Core\System\Management\Me\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\RecordSet\RecordSet;
use Core\shared\SharedClass;
use Core\System\Management\Staff\Model\StaffModel;
use Core\System\Management\Staff\Service\StaffService;

if (!isset($_SESSION)) {
    session_start();
}
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
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot); // start
require_once($newFakeDocumentRoot . "library/class/classAbstract.php");
require_once($newFakeDocumentRoot . "library/class/classRecordSet.php");
require_once($newFakeDocumentRoot . "library/class/classDate.php");
require_once($newFakeDocumentRoot . "library/class/classDocumentTrail.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
require_once($newFakeDocumentRoot . "v3/system/document/model/documentModel.php");
require_once($newFakeDocumentRoot . "v3/system/management/model/staffModel.php");
require_once($newFakeDocumentRoot . "v3/system/management/service/staffService.php");

/**
 * Class Staff
 * this is staff controller files.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package  Core\System\Management\Staff\Controller
 * @subpackage Management
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class MeClass extends ConfigClass {

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
     * @var \Core\System\Management\Staff\Model\StaffModel
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\System\Management\Staff\Service\StaffService
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
     * @var array
     */
    public $t;

    /**
     * System Format
     * @var array
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
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            $this->setStaffId(0);
        }
        $this->translate = array();
        $this->t = array();
        $this->leafAccess = array();
        $this->systemFormat = array();
        $this->setViewPath("./v3/system/management/view/me.php");
        $this->setControllerPath("./v3/system/management/controller/meController.php");
        $this->setServicePath("./v3/system/management/service/staffService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new StaffModel();
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

        $data = $this->q->getLeafLogData();
        if (is_array($data) && count($data) > 0) {
            $this->q->getLog($data['isLog']);
            $this->q->setAudit($data['isAudit']);
        }
        if ($this->getAudit() == 1) {
            $this->q->setAudit($this->getAudit());
            $this->q->setTableName($this->model->getTableName());
            $this->q->setPrimaryKeyName($this->model->getPrimaryKeyName());
        }
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

        $this->setApplicationId($arrayInfo['applicationId']);
        $this->setModuleId($arrayInfo['moduleId']);
        $this->setFolderId($arrayInfo['folderId']);

        $this->setReportTitle(
                $applicationNative . " :: " . $moduleNative . " :: " . $folderNative . " :: " . $leafNative
        );

        $this->service = new StaffService();
        $this->service->q = $this->q;
        $this->service->t = $this->t;
        $this->service->setVendor($this->getVendor());
        $this->service->setServiceOutput($this->getServiceOutput());
        $this->service->execute();

        $this->recordSet = new RecordSet();
        $this->recordSet->q = $this->q;
        $this->recordSet->setCurrentTable($this->model->getTableName());
        $this->recordSet->setPrimaryKeyName($this->model->getPrimaryKeyName());
        $this->recordSet->execute();

        $this->documentTrail = new DocumentTrailClass();
        $this->documentTrail->q = $this->q;
        $this->documentTrail->setVendor($this->getVendor());
        $this->documentTrail->setStaffId($this->getStaffId());
        $this->documentTrail->setLanguageId($this->getLanguageId());

        $this->documentTrail->setApplicationId($this->getApplicationId());
        $this->documentTrail->setModuleId($this->getModuleId());
        $this->documentTrail->setFolderId($this->getFolderId());
        $this->documentTrail->setLeafId($this->getLeafId());

        $this->documentTrail->execute();

        $this->systemFormat = new SharedClass();
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
        //override
        $this->setStart(0);
        $this->setLimit(1);
        // end override
        $start = microtime(true);
        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] == 0) {
                if ($this->getVendor() == self::MYSQL) {
                    $this->setAuditFilter(
                            " `staff`.`isActive` = 1  AND `staff`.`companyId`='" . $this->getCompanyId() . "' "
                    );
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [staff].[isActive] = 1 AND [staff].[companyId]='" . $this->getCompanyId() . "' "
                        );
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(
                                    " STAFF.ISACTIVE = 1  AND STAFF.COMPANYID='" . $this->getCompanyId() . "'"
                            );
                        }
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter("   `staff`.`companyId`='" . $this->getCompanyId() . "'	");
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(" [staff].[companyId]='" . $this->getCompanyId() . "' ");
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(" STAFF.COMPANYID='" . $this->getCompanyId() . "' ");
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
       SELECT                    `staff`.`staffId`,
                    `company`.`companyDescription`,
                    `staff`.`companyId`,
                    `role`.`roleDescription`,
                    `staff`.`roleId`,
                    `language`.`languageDescription`,
                    `staff`.`languageId`,
                    `theme`.`themeDescription`,
                    `staff`.`themeId`,
                    `staff`.`staffName`,
                    `staff`.`staffPassword`,
                     `staff`.`staffEmail`,
                    `staff`.`staffAvatar`,
                    `staff`.`isDefault`,
                    `staff`.`isNew`,
                    `staff`.`isDraft`,
                    `staff`.`isUpdate`,
                    `staff`.`isDelete`,
                    `staff`.`isActive`,
                    `staff`.`isApproved`,
                    `staff`.`isReview`,
                    `staff`.`isPost`,
                    `staff`.`executeBy`,
                    `staff`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `staff`
	JOIN	`company`
	ON		`company`.`companyId` = `staff`.`companyId`
	JOIN	`role`
	ON		`role`.`roleId` = `staff`.`roleId`
	JOIN	`language`
	ON		`language`.`languageId` = `staff`.`languageId`
	JOIN	`theme`
	ON		`theme`.`themeId` = `staff`.`themeId`
		  WHERE     " . $this->getAuditFilter();
            $sql .= " AND `staffId` =  '" . $this->getStaffId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
		  SELECT                    [staff].[staffId],
                    [company].[companyDescription],
                    [staff].[companyId],
                    [role].[roleDescription],
                    [staff].[roleId],
                    [language].[languageDescription],
                    [staff].[languageId],
                    [theme].[themeDescription],
                    [staff].[themeId],
                    [staff].[staffName],
                    [staff].[staffPassword],
                    [staff].[staffEmail],
                    [staff].[staffAvatar],
                    [staff].[isDefault],
                    [staff].[isNew],
                    [staff].[isDraft],
                    [staff].[isUpdate],
                    [staff].[isDelete],
                    [staff].[isActive],
                    [staff].[isApproved],
                    [staff].[isReview],
                    [staff].[isPost],
                    [staff].[executeBy],
                    [staff].[executeTime],
                    [staff].[staffName]
		  FROM 	[staff]
	JOIN	[company]
	ON		[company].[companyId] = [staff].[companyId]
	JOIN	[role]
	ON		[role].[roleId] = [staff].[roleId]
	JOIN	[language]
	ON		[language].[languageId] = [staff].[languageId]
	JOIN	[theme]
	ON		[theme].[themeId] = [staff].[themeId]
		  WHERE    " . $this->getAuditFilter();
                $sql .= " AND [staffId] = '" . $this->getStaffId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
		  SELECT                    STAFF.STAFFID AS \"staffId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    STAFF.COMPANYID AS \"companyId\",
                    ROLE.ROLEDESCRIPTION AS  \"roleDescription\",
                    STAFF.ROLEID AS \"roleId\",
                    LANGUAGE.LANGUAGEDESCRIPTION AS  \"languageDescription\",
                    STAFF.LANGUAGEID AS \"languageId\",
                    THEME.THEMEDESCRIPTION AS  \"themeDescription\",
                    STAFF.THEMEID AS \"themeId\",
                    STAFF.STAFFNAME AS \"staffName\",
                    STAFF.STAFFPASSWORD AS \"staffPassword\",
                    STAFF.STAFFEMAIL AS \"staffEmail\",
                    STAFF.STAFFAVATAR AS \"staffAvatar\",
                    STAFF.ISDEFAULT AS \"isDefault\",
                    STAFF.ISNEW AS \"isNew\",
                    STAFF.ISDRAFT AS \"isDraft\",
                    STAFF.ISUPDATE AS \"isUpdate\",
                    STAFF.ISDELETE AS \"isDelete\",
                    STAFF.ISACTIVE AS \"isActive\",
                    STAFF.ISAPPROVED AS \"isApproved\",
                    STAFF.ISREVIEW AS \"isReview\",
                    STAFF.ISPOST AS \"isPost\",
                    STAFF.EXECUTEBY AS \"executeBy\",
                    STAFF.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\"
		  FROM 	STAFF
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = STAFF.COMPANYID
	JOIN	ROLE
	ON		ROLE.ROLEID = STAFF.ROLEID
	JOIN	LANGUAGE
	ON		LANGUAGE.LANGUAGEID = STAFF.LANGUAGEID
	JOIN	THEME
	ON		THEME.THEMEID = STAFF.THEMEID
         WHERE     " . $this->getAuditFilter();
                    $sql .= " AND STAFFID ='" . $this->getStaffId() . "'";
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
                $sql .= " AND `staff`.`" . $this->model->getFilterCharacter() . "` like '" . $this->getCharacterQuery(
                        ) . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [staff].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND Initcap(STAFF." . strtoupper(
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
                        'staff', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'staff', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'STAFF', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
                "`staff`.`staffId`",
                "`staff`.`staffPassword`"
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $filterArray = array(
                    "[staff].[staffId]",
                    "[staff].[staffPassword]"
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $filterArray = array(
                        "STAFF.STAFFID",
                        "STAFF.STAFFPASSWORD"
                    );
                }
            }
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('staff', 'staff', 'company', 'role', 'language', 'theme');
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array('staff', 'staff', 'company', 'role', 'language', 'theme');
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array('STAFF', 'STAFF', 'COMPANY', 'ROLE', 'LANGUAGE', 'THEME');
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

        try {
            $this->q->read($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }

        $items = array();
        $i = 1;
        while (($row = $this->q->fetchAssoc()) == true) {
            $row['total'] = $total; // small override
            $row['counter'] = $this->getStart() + 19;
            if ($this->model->getStaffId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getStaffId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getStaffId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else {
            if ($this->getPageOutput() == 'json') {
                if ($this->model->getStaffId(0, 'single')) {
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
                                                'value', $this->model->getStaffId(0, 'single')
                                        ),
                                        'nextRecord' => $this->nextRecord('value', $this->model->getStaffId(0, 'single')),
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
                                        'value', $this->model->getStaffId(0, 'single')
                                ),
                                'nextRecord' => $this->recordSet->nextRecord(
                                        'value', $this->model->getStaffId(0, 'single')
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

    /**
     * Update
     * @see config::update()
     */
    function update() {
        header('Content-Type:application/json; charset=utf-8');
        $start = microtime(true);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            try {
                $this->q->fast($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        $this->q->start();
        $this->model->update();
        // before updating check the id exist or not . if exist continue to update else warning the user
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`,
                    `roleId`,
					`themeId`,
					`languageId`,
                    `staffPassword`
           FROM 	`staff`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->getStaffId() . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "],
                    [roleId],
					[themeId],
					[languageId],
                    [staffPassword]
           FROM 	[staff]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->getStaffId() . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . ",
                    ROLEID AS \"roleId\",
					THEMEID AS \"themeId\",
					LANGUAGEID AS \"languageId\",
                    STAFFPASSWORD AS \"staffPassword\"
           FROM 	STAFF
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->getStaffId() . "' ";
                }
            }
        }
        $result = $this->q->fast($sql);
        $total = $this->q->numberRows($result, $sql);
        if ($total == 0) {
            echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel']));
            exit();
        } else {
            $row = $this->q->fetchArray($result);

            $oldStaffPassword = $row['staffPassword'];
            $oldRoleId = $row['roleId'];
            $oldThemeId = $row['themeId'];
            $oldLanguageId = $row['languageId'];
            if ($oldThemeId != $this->model->getThemeId()) {
                $_SESSION['theme'] = $this->service->getThemePath($this->model->getThemeId());
            }
            if ($oldLanguageId != $this->model->getLanguageId()) {
                $_SESSION['languageId'] = $this->model->getLanguageId();
            }
            // update access if role changes
            $this->service->updateAccess($this->getStaffId(), $oldRoleId, $this->model->getRoleId());
            // if diff with the old  update new password
            if ($oldStaffPassword != ($this->model->getStaffPassword())) {
                $this->model->setStaffPassword(md5(trim($this->model->getStaffPassword())));
            }

            if ($this->getVendor() == self::MYSQL) {
                $sql = "
               UPDATE `staff` SET
                       `roleId` = '" . $this->model->getRoleId() . "',
                       `languageId` = '" . $this->model->getLanguageId() . "',
                       `themeId` = '" . $this->model->getThemeId() . "',
                       `staffName` = '" . $this->model->getStaffName() . "',
                       `staffPassword` = '" . $this->model->getStaffPassword() . "',
                       `staffAvatar` = '" . $this->model->getStaffAvatar() . "',
                       `staffEmail` =   '" . $this->model->getStaffEmail() . "',
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
               WHERE    `staffId`='" . $this->getStaffId() . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                UPDATE [staff] SET
                       [roleId] = '" . $this->model->getRoleId() . "',
                       [languageId] = '" . $this->model->getLanguageId() . "',
                       [themeId] = '" . $this->model->getThemeId() . "',
                       [staffName] = '" . $this->model->getStaffName() . "',
                       [staffPassword] = '" . $this->model->getStaffPassword() . "',
                       [staffAvatar] = '" . $this->model->getStaffAvatar() . "',
                       [staffEmail] =   '" . $this->model->getStaffEmail() . "',
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
                WHERE   [staffId]='" . $this->getStaffId() . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
                UPDATE STAFF SET
                        ROLEID = '" . $this->model->getRoleId() . "',
                       LANGUAGEID = '" . $this->model->getLanguageId() . "',
                       THEMEID = '" . $this->model->getThemeId() . "',
                       STAFFNAME = '" . $this->model->getStaffName() . "',
                       STAFFPASSWORD = '" . $this->model->getStaffPassword() . "',
                       STAFFAVATAR = '" . $this->model->getStaffAvatar() . "',
                       STAFFEMAIL =   '" . $this->model->getStaffEmail() . "',
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
                WHERE  STAFFID='" . $this->getStaffId() . "'";
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
     * Update Password Only..
     * @see config::update()
     */
    function updatePassword() {
        header('Content-Type:application/json; charset=utf-8');
        $start = microtime(true);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            try {
                $this->q->fast($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        $this->q->start();
        $this->model->update();
        // before updating check the id exist or not . if exist continue to update else warning the user
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`,
                    `staffPassword`
           FROM 	`staff`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->getStaffId() . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "],
                    [staffPassword]
           FROM 	[staff]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->getStaffId() . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . ",
                    STAFFPASSWORD AS \"staffPassword\"
           FROM 	STAFF
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->getStaffId() . "' ";
                }
            }
        }
        $result = $this->q->fast($sql);
        $total = $this->q->numberRows($result, $sql);
        if ($total == 0) {
            echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel']));
            exit();
        } else {
            $row = $this->q->fetchArray($result);

            $oldStaffPassword = $row['staffPassword'];
            // if diff with the old  update new password
            if ($oldStaffPassword != ($this->model->getStaffPassword())) {
                $this->model->setStaffPassword(md5(trim($this->model->getStaffPassword())));
            }
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
				UPDATE 	`staff` 
				SET		`staffPassword` = '" . $this->model->getStaffPassword() . "',
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
               WHERE    `staffId`='" . $this->getStaffId() . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                UPDATE 	[staff]
				SET	   	[staffPassword] = '" . $this->model->getStaffPassword() . "',
						[staffAvatar] = '" . $this->model->getStaffAvatar() . "',
						[staffEmail] =   '" . $this->model->getStaffEmail() . "',
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
                WHERE   [staffId]='" . $this->getStaffId() . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
                UPDATE 	STAFF
				SET		STAFFPASSWORD = '" . $this->model->getStaffPassword() . "',
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
                WHERE  	STAFFID='" . $this->getStaffId() . "'";
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
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => true,
                    "message" => $this->t['updateRecordTextLabel'],
                    "aa" => $this->model->getStaffPassword(),
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
     * Set Service
     * @param string $service . Reset service either option,html,table
     * @return mixed
     */
    function setService($service) {
        return $this->service->setServiceOutput($service);
    }

    /**
     * Return  Role
     * @return null|string
     */
    public function getRole() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getRole();
    }

    /**
     * Return  Language
     * @return null|string
     */
    public function getLanguage() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getLanguage();
    }

    /**
     * Return  Theme
     * @return null|string
     */
    public function getTheme() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getTheme();
    }

    /**
     * Set User Avatar
     * @return mixed
     */
    public function setStaffAvatar() {
        $this->service->setStaffAvatar();
    }

    /**
     * Get User Avatar
     */
    public function getStaffAvatar() {
        // if can stream back image uploaded nice
    }

    /**
     * Return Verification
     */
    function verification() {
        if ($this->model->getFrom() == 'userVerify') {
            if ($this->model->getVerificationCode() == $this->service->getVerificationCode(
                            $this->model->getStaffId(0, 'string')
                    )
            ) {
                $this->service->verifyUser($this->model->getStaffId(0, 'string'), $this->model->getVerificationCode());
                $this->service->registerBusinessPartners(
                        $this->model->getStaffId(0, 'string'), $this->model->getStaffEmail()
                );
            } else {
                echo json_encode(
                        array("success" => false, "message" => "You have send false verification.. You're Hackers ?????")
                );
                exit();
            }
        }
    }

    /**
     * Resend Password  either checking via email  or username
     */
    function resendPassword() {
        $this->service->resendPassword(
                $this->model->getStaffName(), $this->model->getStaffEmail(), $this->getSecurityToken()
        );
        exit();
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
        $meObject = new MeClass ();
        if ($_POST['securityToken'] != $meObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value
         */
        if (isset($_POST ['leafId'])) {
            $meObject->setLeafId($_POST ['leafId']);
        }
        $meObject->setPageOutput($_POST['output']);
        $meObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy)
         */

        if ($_POST ['method'] == 'update') {
            $meObject->update();
        }
        if ($_POST['method'] == 'updatePassword') {
            $meObject->updatePassword();
        }
        if ($_POST ['method'] == 'read') {
            $meObject->read();
        }
        if ($_POST ['method'] == 'upload') {
            $meObject->setStaffAvatar();
        }
    }
}
if (isset($_GET ['method'])) {
    $meObject = new MeClass ();
    if ($_GET['securityToken'] != $meObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $meObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $meObject->execute();
    if (isset($_GET ['filter'])) {
        $meObject->setServiceOutput('option');
        if (($_GET['filter'] == 'role')) {
            $meObject->getRole();
        }
        if (($_GET['filter'] == 'language')) {
            $meObject->getLanguage();
        }
        if (($_GET['filter'] == 'theme')) {
            $meObject->getTheme();
        }
    }
    if ($_GET ['method'] == 'upload') {

        $meObject->setStaffAvatar();
    }
}
?>
