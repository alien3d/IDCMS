<?php

namespace Core\Portal\Main\StaffWebAccess\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\Portal\Main\StaffWebAccess\Model\StaffWebAccessModel;
use Core\Portal\Main\StaffWebAccess\Service\StaffWebAccessBrowserService;
use Core\Portal\Main\StaffWebAccess\Service\StaffWebAccessInternetProtocolService;
use Core\Portal\Main\StaffWebAccess\Service\StaffWebAccessOperatingSystemService;
use Core\Portal\Main\StaffWebAccess\Service\StaffWebAccessCrossTabBrowserService;
use Core\Portal\Main\StaffWebAccess\Service\StaffWebAccessCrossTabOperatingSystemService;
use Core\RecordSet\RecordSet;
use Core\shared\SharedClass;

if (!isset($_SESSION)) {
    session_start();
}
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
require_once($newFakeDocumentRoot . "v3/portal/main/model/staffWebAccessModel.php");
require_once($newFakeDocumentRoot . "v3/portal/main/service/staffWebAccessService.php");

/**
 * Class StaffWebAccessClass
 * Analytical Report For Staff Access
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Main\StaffWebAccess\Controller
 * @subpackage Main
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class StaffWebAccessClass extends ConfigClass {

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
     * Model
     * @var \Core\Portal\Main\StaffWebAccess\Model\StaffWebAccessModel
     */
    public $model;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     * @var \PHPPowerPoint
     */
    //private $powerPoint; 
    /**
     * Service-Business Application Process or other ajax request Web Browser
     * @var \Core\Portal\Main\StaffWebAccess\Service\StaffWebAccessBrowserService
     */
    public $browserService;

    /**
     * Service-Business Application Process or other ajax request Operating System
     * @var \Core\Portal\Main\StaffWebAccess\Service\StaffWebAccessOperatingSystemService
     */
    public $osService;

    /**
     * Service-Business Application Process or other ajax request Internet Protocol
     * @var \Core\Portal\Main\StaffWebAccess\Service\StaffWebAccessInternetProtocolService
     */
    public $ipService;

    /**
     * Service-Business Application Process or other ajax request Web Browser
     * @var \Core\Portal\Main\StaffWebAccess\Service\StaffWebAccessCrossTabBrowserService
     */
    public $browserDashboardService;

    /**
     * Service-Business Application Process or other ajax request Operating System
     * @var \Core\Portal\Main\StaffWebAccess\Service\StaffWebAccessCrossTabOperatingSystemService
     */
    public $osDashboardService;

    /**
     * Service-Business Application Process or other ajax request Internet Protocol
     * @var \Core\Portal\Main\StaffWebAccess\Service\StaffWebAccessInternetProtocolService
     */
    public $ipDashboardService;

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
     * @var \Core\Document\Trail\DocumentTrailClass
     */
    private $documentTrail;

    /**
     *
     * @var string
     */
    private $browserViewPath;

    /**
     *
     * @var string
     */
    private $browserControllerPath;

    /**
     *
     * @var string
     */
    private $operatingSystemViewPath;

    /**
     *
     * @var string
     */
    private $operatingSystemControllerPath;

    /**
     *
     * @var string
     */
    private $internetProtocolViewPath;

    /**
     *
     * @var string
     */
    private $internetProtocolControllerPath;

    /**
     * Constructor
     */
    function __construct() {
        $this->translate = array();
        $this->t = array();
        $this->leafAccess = array();
        $this->systemFormat = array();

        $this->setBrowserViewPath("./v3/portal/main/view/browser.php");
        $this->setBrowserControllerPath("./v3/portal/main/controller/staffWebAccessController.php");

        $this->setOperatingSystemViewPath("./v3/portal/main/view/os.php");
        $this->setControllerPath("./v3/portal/main/controller/staffWebAccessController.php");

        $this->setInternetProtocolViewPath("./v3/portal/main/view/ip.php");
        $this->setInternetProtocolControllerPath("./v3/portal/main/controller/staffWebAccessController.php");

        $this->setServicePath("./v3/portal/main/service/staffWebAccessService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(0);
        $this->setLog(1);
        $this->model = new StaffWebAccessModel();
        $this->model->setVendor($this->getVendor());
        $this->model->execute();
        $this->q = new \Core\Database\Mysql\Vendor();
        $this->setVendor($this->getVendor());
        $this->q->setRequestDatabase($this->q->getCoreDatabase());
        $this->q->setCurrentDatabase($this->q->getCoreDatabase());
        // $this->q->setApplicationId($this->getApplicationId()); 
        // $this->q->setModuleId($this->getModuleId()); 
        // $this->q->setFolderId($this->getFolderId()); 
        $this->q->setLeafId($this->getLeafId());
        $this->q->setLog($this->getLog());
        $this->q->setAudit($this->getAudit());
        $this->q->connect($this->getConnection(), $this->getUsername(), $this->getDatabase(), $this->getPassword());

        $this->browserService = new StaffWebAccessBrowserService();
        $this->browserService->q = $this->q;
        $this->browserService->t = $this->t;
        $this->browserService->setVendor($this->getVendor());
        $this->browserService->setServiceOutput($this->getServiceOutput());
        $this->browserService->execute();

        $this->osService = new StaffWebAccessOperatingSystemService();
        $this->osService->q = $this->q;
        $this->osService->t = $this->t;
        $this->osService->setVendor($this->getVendor());
        $this->osService->setServiceOutput($this->getServiceOutput());
        $this->osService->execute();

        $this->ipService = new StaffWebAccessInternetProtocolService();
        $this->ipService->q = $this->q;
        $this->ipService->t = $this->t;
        $this->ipService->setVendor($this->getVendor());
        $this->ipService->setServiceOutput($this->getServiceOutput());
        $this->ipService->execute();

        $this->browserDashboardService = new StaffWebAccessCrossTabBrowserService();
        $this->browserDashboardService->q = $this->q;
        $this->browserDashboardService->t = $this->t;
        $this->browserDashboardService->setVendor($this->getVendor());
        $this->browserDashboardService->setServiceOutput($this->getServiceOutput());
        $this->browserDashboardService->execute();

        $this->osDashboardService = new StaffWebAccessCrossTabOperatingSystemService();
        $this->osDashboardService->q = $this->q;
        $this->osDashboardService->t = $this->t;
        $this->osDashboardService->setVendor($this->getVendor());
        $this->osDashboardService->setServiceOutput($this->getServiceOutput());
        $this->osDashboardService->execute();

        //   $this->ipDashboardService = new StaffWebAccessInternetProtocolService();
        //   $this->ipDashboardService->q = $this->q;
        //   $this->ipDashboardService->t = $this->t;
        //    $this->ipDashboardService->setVendor($this->getVendor());
        //    $this->ipDashboardService->setServiceOutput($this->getServiceOutput());
        //    $this->ipDashboardService->execute();

        $translator = new SharedClass();
        $translator->setCurrentDatabase($this->q->getCoreDatabase());
        $translator->setCurrentTable($this->model->getTableName());
        $translator->setFilename('staffWebAccess.php');
        $translator->execute();

        $this->translate = $translator->getLeafTranslation(); // short because code too long  
        $this->t = $translator->getDefaultTranslation(); // short because code too long  

        $arrayInfo = $translator->getFileInfo();
        $applicationNative = $arrayInfo['applicationNative'];
        $moduleNative = $arrayInfo['moduleNative'];
        $leafNative = $arrayInfo['leafNative'];
        $this->setReportTitle($applicationNative . " :: " . $moduleNative . " :: " . $leafNative);

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
        if ($this->getPageOutput() == 'json') {
            header('Content-Type:application/json; charset=utf-8');
        }
        $start = microtime(true);
        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] == 0) {
                $this->setAuditFilter(" `staffWebAccess`.`isActive` = 1 ");
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    $this->setAuditFilter(" 1 = 1 ");
                }
            }
        }
        $sql = "SET NAMES utf8";
        $this->q->fast($sql);
        $sql = "
       SELECT                    `staffwebaccess`.`staffWebAccessId`,
                    `staffwebaccess`.`staffId`,
                    `staffwebaccess`.`staffWebAccessLogIn`,
                    `staffwebaccess`.`staffWebAccessLogOut`,
                    `staffwebaccess`.`phpSession`,
                    `staffwebaccess`.`ua_type`,
                    `staffwebaccess`.`ua_family`,
                    `staffwebaccess`.`ua_name`,
                    `staffwebaccess`.`ua_version`,
                    `staffwebaccess`.`ua_url`,
                    `staffwebaccess`.`ua_company`,
                    `staffwebaccess`.`ua_company_url`,
                    `staffwebaccess`.`ua_icon`,
                    `staffwebaccess`.`ua_info_url`,
                    `staffwebaccess`.`os_family`,
                    `staffwebaccess`.`os_name`,
                    `staffwebaccess`.`os_url`,
                    `staffwebaccess`.`os_company`,
                    `staffwebaccess`.`os_company_url`,
                    `staffwebaccess`.`os_icon`,
                    `staffwebaccess`.`ip_v4`,
                    `staffwebaccess`.`ip_v6`,
                    `staffwebaccess`.`ip_country_code`,
                    `staffwebaccess`.`ip_country_name`,
                    `staffwebaccess`.`ip_region_name`,
                    `staffwebaccess`.`ip_latitude`,
                    `staffwebaccess`.`ip_longtitude`,
                    `staff`.`staffName`
		  FROM      `staffwebaccess`
		  JOIN      `staff`
		  ON        `staffwebaccess`.`staffId` = `staff`.`staffId`
		  WHERE     " . $this->getAuditFilter();
        if ($this->model->getStaffWebAccessId(0, 'single')) {
            $sql .= " AND `staffwebaccess`.`" . $this->model->getPrimaryKeyName(
                    ) . "`='" . $this->model->getStaffWebAccessId(0, 'single') . "'";
        }

        /**
         * filter column based on first character
         */
        if ($this->getCharacterQuery()) {
            $sql .= " AND `staffwebaccess`.`" . $this->model->getFilterCharacter(
                    ) . "` like '" . $this->getCharacterQuery() . "%'";
        }
        /**
         * filter column based on Range Of Date
         * Example Day,Week,Between or Range,Month,Year
         */
        if ($this->getDateRangeStartQuery()) {
            $sql .= $this->q->dateFilter(
                    'staffWebAccess', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
            );
        }
        /**
         * filter column don't want to filter.Example may contain  sensitive information or unwanted to be search.
         * E.g  $filterArray=array('`leaf`.`leafId`');
         * @variables $filterArray;
         */
        $filterArray = array('staffWebAccessId');
        /**
         * filter table
         * @variables $tableArray
         */
        $tableArray = array('staffWebAccess');
        if ($this->getFieldQuery()) {
            $this->q->setFieldQuery($this->getFieldQuery());
            $sql .= $this->q->quickSearch($tableArray, $filterArray);
        }
        if ($this->getGridQuery()) {
            $this->q->setGridQuery($this->getGridQuery());
            $sql .= $this->q->searching();
        }
        try {
            $this->q->read($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }

        $total = $this->q->numberRows();

        $sql .= "	ORDER BY `staffwebaccess`.`staffWebAccessId` DESC ";

        $_SESSION ['sql'] = $sql; // push to session so can make report via excel and pdf 
        $_SESSION ['start'] = $this->getStart();
        $_SESSION ['limit'] = $this->getLimit();
        if ($this->getLimit()) {
            // only mysql have limit 
            $sql .= " LIMIT  " . $this->getStart() . "," . $this->getLimit() . " ";
        }
        /*
         *  Only Execute One Query 
         */
        if (!($this->model->getStaffWebAccessId(0, 'single'))) {
            try {
                $this->q->read($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        $items = array();
        $i = 1;
        while (($row = $this->q->fetchAssoc()) == true) {
            $row['total'] = $total; // small override 
            $row['counter'] = $this->getStart() + 27;
            if ($this->model->getStaffWebAccessId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getStaffWebAccessId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getStaffWebAccessId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }

        if ($this->getPageOutput() == 'html') {
            return $items;
        } else {
            if ($this->getPageOutput() == 'json') {
                if ($this->model->getStaffWebAccessId(0, 'single')) {
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
                                                'value', $this->model->getStaffWebAccessId(0, 'single')
                                        ),
                                        'nextRecord' => $this->nextRecord(
                                                'value', $this->model->getStaffWebAccessId(0, 'single')
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
                                        'value', $this->model->getStaffWebAccessId(0, 'single')
                                ),
                                'nextRecord' => $this->recordSet->nextRecord(
                                        'value', $this->model->getStaffWebAccessId(0, 'single')
                                ),
                                'lastRecord' => $this->recordSet->lastRecord('value'),
                                'data' => $items
                            )
                    );
                    exit();
                }
            }
        }
        return false;
    }

    /**
     * First Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @return int
     * @throws \Exception
     */
    function firstRecord($value) {
        return $this->recordSet->firstRecord($value);
    }

    /**
     * Previous Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @param int $primaryKeyValue
     * @return int
     * @throws \Exception
     */
    function previousRecord($value, $primaryKeyValue) {
        return $this->recordSet->previousRecord($value, $primaryKeyValue);
    }

    /**
     * Next Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @param int $primaryKeyValue Current  Primary Key Value
     * @return int
     * @throws \Exception
     */
    function nextRecord($value, $primaryKeyValue) {
        return $this->recordSet->nextRecord($value, $primaryKeyValue);
    }

    /**
     * Last Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @return int
     * @throws \Exception
     */
    function lastRecord($value) {
        return $this->recordSet->lastRecord($value);
    }

    /**
     * Update
     * @see config::update()
     */
    function update() {
        
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
     * Return Browser View Path
     * @return string
     */
    public function getBrowserViewPath() {
        return $this->browserViewPath;
    }

    /**
     * Set Browser View Path
     * @param string $browserViewPath
     * @return $this
     */
    public function setBrowserViewPath($browserViewPath) {
        $this->browserViewPath = $browserViewPath;
        return $this;
    }

    /**
     * Return Browser Controller Path
     * @return string
     */
    public function getBrowserControllerPath() {
        return $this->browserControllerPath;
    }

    /**
     * Set Browser Controller Path
     * @param string $browserControllerPath
     * @return $this
     */
    public function setBrowserControllerPath($browserControllerPath) {
        $this->browserControllerPath = $browserControllerPath;
        return $this;
    }

    /**
     * Return Operating System View Path
     * @return string
     */
    public function getOperatingSystemViewPath() {
        return $this->operatingSystemViewPath;
    }

    /**
     * Set Operating System View Path
     * @param string $operatingSystemViewPath
     * @return $this
     */
    public function setOperatingSystemViewPath($operatingSystemViewPath) {
        $this->operatingSystemViewPath = $operatingSystemViewPath;
        return $this;
    }

    /**
     * Return Operating System Controller Path
     * @return string
     */
    public function getOperatingSystemControllerPath() {
        return $this->operatingSystemControllerPath;
    }

    /**
     * Set Operating System Controller Path
     * @param string $operatingSystemControllerPath
     * @return $this
     */
    public function setOperatingSystemControllerPath($operatingSystemControllerPath) {
        $this->operatingSystemControllerPath = $operatingSystemControllerPath;
        return $this;
    }

    /**
     * Return Internet Protocol View Path
     * @return string
     */
    public function getInternetProtocolViewPath() {
        return $this->internetProtocolViewPath;
    }

    /**
     * Set Internet  Protocol View Path
     * @param string $internetProtocolViewPath
     * @return $this
     */
    public function setInternetProtocolViewPath($internetProtocolViewPath) {
        $this->internetProtocolViewPath = $internetProtocolViewPath;
        return $this;
    }

    /**
     * Return Internet Protocol Controller Path
     * @return string
     */
    public function getInternetProtocolControllerPath() {
        return $this->internetProtocolControllerPath;
    }

    /**
     * Set Internet Protocol Controller Path
     * @param string $internetProtocolControllerPath
     * @return $this
     */
    public function setInternetProtocolControllerPath($internetProtocolControllerPath) {
        $this->internetProtocolControllerPath = $internetProtocolControllerPath;
        return $this;
    }

    /**
     * Return Distinct Browser Type
     * @return mixed
     */
    function getDistinctBrowserType() {
        return $this->browserService->getDistinctBrowserType();
    }

    /**
     * Get Total Browser(Pc or Laptop)
     * @return mixed
     */
    function getBrowser() {
        return $this->browserService->getBrowser();
    }

    /**
     * Get Total Robot
     * @return mixed
     */
    function getRobot() {
        return $this->browserService->getRobot();
    }

    /**
     * Get Total Mobile Browser
     * @return mixed
     */
    function getMobileBrowser() {
        return $this->browserService->getMobileBrowser();
    }

    /**
     * Get Total Email Client Browser
     * @return mixed
     */
    function getEmailClient() {
        return $this->browserService->getEmailClient();
    }

    /**
     * Get Total Wap Browser Client Browser
     * @return mixed
     */
    function getWapBrowser() {
        return $this->browserService->getWapBrowser();
    }

    /**
     * Get Total Offline Browser
     * @return mixed
     */
    function getOfflineBrowser() {
        return $this->browserService->getOfflineBrowser();
    }

    /**
     * Get Total Ua Anonymizer..????
     * @return mixed
     */
    function getUaAnonymizer() {
        return $this->browserService->getUaAnonymizer();
    }

    /**
     * Get Total Library Browser
     * @return mixed
     */
    function getLibraryBrowser() {
        return $this->browserService->getLibraryBrowser();
    }

    /**
     * Get Total Others Browser
     * @return mixed
     */
    function getOthersBrowserType() {
        return $this->browserService->getOthersBrowserType();
    }

    /**
     * Get Total Common Type vs unknown above such as library,unknown anoymizer ,offline browser
     * @return mixed
     */
    function getOthersBrowserSpecialType() {
        return $this->browserService->getOthersBrowserSpecialType();
    }

    /**
     * Return Popular Browser
     * IE,FireFox,Chrome,Safari,Opera...
     * return mixed
     */
    function getPopularBrowser() {
        return $this->browserService->getPopularBrowser();
    }

    /**
     * Get Total Record Internet Explorer
     * return mixed
     */
    function getInternetExplorer() {
        return $this->browserService->getInternetExplorer();
    }

    /**
     * Get Total Record FireFox
     * return mixed
     */
    function getFireFox() {
        return $this->browserService->getFireFox();
    }

    /**
     * Get Total Record Google Chrome
     * return mixed
     */
    function getChrome() {
        return $this->browserService->getChrome();
    }

    /**
     * Get Total Record Opera Browser
     */
    function getOpera() {
        return $this->browserService->getOpera();
    }

    /**
     *  Get Total Record Apple Safari
     * return mixed
     */
    function getSafari() {
        return $this->browserService->getSafari();
    }

    /**
     * Get  Total Record Other Browser
     * return mixed
     */
    function getOtherBrowser() {
        return $this->browserService->getSafari();
    }

    /**
     * Get total Operating System
     * @return mixed
     */
    function getPopularOperatingSystem() {
        return $this->osService->getPopularOperatingSystem();
    }

    /**
     * Get Total Microsoft Operating System including mobile
     * @return mixed
     */
    public function getMicrosoftWindows() {
        return $this->osService->getMicrosoftWindows();
    }

    /**
     * Get Total Operating System Mac Os
     * @return mixed
     */
    public function getMacOs() {
        return $this->osService->getMacOs();
    }

    /**
     * Get Total Apple Operating System Max Os x
     * @return mixed
     */
    public function getMacOsx() {
        return $this->osService->getMacOsx();
    }

    /**
     * Get Total Apple Operating System  8x 9x x
     * @return mixed
     */
    public function getAppleMac() {
        return $this->osService->getAppleMac();
    }

    /**
     * Get Total Linux
     * @return mixed
     */
    public function getLinux() {
        return $this->osService->getLinux();
    }

    /**
     * Get Total Android..
     * @return mixed
     */
    public function getAndroid() {
        return $this->osService->getAndroid();
    }

    /**
     * Get Total Ios.. Iphone,Ipad,itouch
     * @return mixed
     */
    public function getiOS() {
        return $this->osService->getiOS();
    }

    /**
     * Get Total Bsd
     * @return mixed
     */
    public function getBsd() {
        return $this->osService->getBsd();
    }

    /**
     * Get Total Solaris
     * @return mixed
     * @throws \Exception
     */
    public function getSolaris() {
        return $this->osService->getSolaris();
    }

    /**
     * Return Cross Tab Time All Browser
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeAllBrowser() {
        return $this->browserDashboardService->getCrossTabTimeAllBrowser($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Time Internet Explorer
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeInternetExplorer() {
        return $this->browserDashboardService->getCrossTabTimeInternetExplorer($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Time FireFox
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeFireFox() {
        return $this->browserDashboardService->getCrossTabTimeFireFox($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Time Chrome
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeChrome() {
        return $this->browserDashboardService->getCrossTabTimeChrome($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Time Safari
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeSafari() {
        return $this->browserDashboardService->getCrossTabTimeSafari($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Time Safari
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeOtherBrowser() {
        return $this->browserDashboardService->getCrossTabTimeOtherBrowser($this->getDateRangeStartQuery());
    }

    /**
     * Other Operating system
     * @return mixed
     * @throws \Exception
     */
    public function getOtherOperatingSystem() {
        return $this->osService->getOtherOperatingSystem();
    }

    /**
     * Return Cross Tab Daily All Browser
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyAllBrowser() {
        return $this->browserDashboardService->getCrossTabDailyAllBrowser($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Daily Internet Explorer
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyInternetExplorer() {
        return $this->browserDashboardService->getCrossTabDailyInternetExplorer($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Daily FireFox
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyFireFox() {
        return $this->browserDashboardService->getCrossTabDailyFireFox($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Daily Chome
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyChrome() {
        return $this->browserDashboardService->getCrossTabDailyChrome($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Daily Safari
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailySafari() {
        return $this->browserDashboardService->getCrossTabDailySafari($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Daily Safari
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyOtherBrowser() {
        return $this->browserDashboardService->getCrossTabDailyOtherBrowser($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Weekly All Browser
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyAllBrowser() {
        return $this->browserDashboardService->getCrossTabWeeklyAllBrowser($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Weekly Internet Explorer
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyInternetExplorer() {
        return $this->browserDashboardService->getCrossTabWeeklyInternetExplorer($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Weekly FireFox
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyFireFox() {
        return $this->browserDashboardService->getCrossTabWeeklyFireFox($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Weekly Chrome
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyChrome() {
        return $this->browserDashboardService->getCrossTabWeeklyChrome($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Weekly Safari
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklySafari() {
        return $this->browserDashboardService->getCrossTabWeeklySafari($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Weekly Safari
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyOtherBrowser() {
        return $this->browserDashboardService->getCrossTabWeeklyOtherBrowser($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Monthly Internet Explorer
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyInternetExplorer() {

        return $this->browserDashboardService->getCrossTabMonthlyInternetExplorer($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Monthly FireFox
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyFireFox() {
        return $this->browserDashboardService->getCrossTabMonthlyFireFox($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Monthly Chrome
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyChrome() {
        return $this->browserDashboardService->getCrossTabMonthlyChrome($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Monthly Safari
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlySafari() {
        return $this->browserDashboardService->getCrossTabMonthlySafari($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Monthly Safari
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyOtherBrowser() {
        return $this->browserDashboardService->getCrossTabMonthlyOtherBrowser($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Yearly All Browser
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabYearlyAllBrowser() {
        return $this->browserDashboardService->getCrossTabYearlyAllBrowser($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Date Range All Browser
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabRangeAllBrowser() {
        return $this->browserDashboardService->getCrossTabRangeAllBrowser(
                        $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery()
        );
    }

    /**
     * Return Cross Tab All Operating System
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeAllOperatingSystem() {
        return $this->osDashboardService->getCrossTabTimeAllOperatingSystem($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tabb Time Microsoft Windows Operating System including mobile
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeWindows() {
        return $this->osDashboardService->getCrossTabTimeWindows($this->getDateRangeStartQuery());
    }

    /**
     * Return Linux Operating System
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeLinux() {
        return $this->osDashboardService->getCrossTabTimeLinux($this->getDateRangeStartQuery());
    }

    /**
     * Return Apple Mac Os Operating System.. Including 8.x,9.x Os X.. excluding iOs
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeMac() {
        return $this->osDashboardService->getCrossTabTimeMac($this->getDateRangeStartQuery());
    }

    /**
     * Return Android Operating System(mobile)
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeAndroid() {
        return $this->osDashboardService->getCrossTabTimeAndroid($this->getDateRangeStartQuery());
    }

    /**
     * Return iOS Operating System(mobile)
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeiOS() {
        return $this->osDashboardService->getCrossTabTimeiOS($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Time Other Browser
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeOtherOperatingSystem() {
        return $this->osDashboardService->getCrossTabTimeOtherOperatingSystem($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab All Operating System
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyAllOperatingSystem() {
        return $this->osDashboardService->getCrossTabDailyAllOperatingSystem($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tabb Daily Microsoft Windows Operating System including mobile
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyWindows() {
        return $this->osDashboardService->getCrossTabDailyWindows($this->getDateRangeStartQuery());
    }

    /**
     * Return Linux Operating System
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyLinux() {
        return $this->osDashboardService->getCrossTabDailyLinux($this->getDateRangeStartQuery());
    }

    /**
     * Return Apple Mac Os Operating System.. Including 8.x,9.x Os X.. excluding iOs
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyMac() {
        return $this->osDashboardService->getCrossTabDailyMac($this->getDateRangeStartQuery());
    }

    /**
     * Return Android Operating System(mobile)
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyAndroid() {
        return $this->osDashboardService->getCrossTabDailyAndroid($this->getDateRangeStartQuery());
    }

    /**
     * Return iOS Operating System(mobile)
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyiOS() {
        return $this->osDashboardService->getCrossTabDailyiOS($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Daily Other Browser
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyOtherOperatingSystem() {
        return $this->osDashboardService->getCrossTabDailyOtherOperatingSystem($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab All Operating System
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyAllOperatingSystem() {
        return $this->osDashboardService->getCrossTabWeeklyAllOperatingSystem($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tabb Weekly Microsoft Windows Operating System including mobile
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyWindows() {
        return $this->osDashboardService->getCrossTabWeeklyWindows($this->getDateRangeStartQuery());
    }

    /**
     * Return Linux Operating System
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyLinux() {
        return $this->osDashboardService->getCrossTabWeeklyLinux($this->getDateRangeStartQuery());
    }

    /**
     * Return Apple Mac Os Operating System.. Including 8.x,9.x Os X.. excluding iOs
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyMac() {
        return $this->osDashboardService->getCrossTabWeeklyMac($this->getDateRangeStartQuery());
    }

    /**
     * Return Android Operating System(mobile)
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyAndroid() {
        return $this->osDashboardService->getCrossTabWeeklyAndroid($this->getDateRangeStartQuery());
    }

    /**
     * Return iOS Operating System(mobile)
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyiOS() {
        return $this->osDashboardService->getCrossTabWeeklyiOS($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Weekly Other Browser
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyOtherOperatingSystem() {
        return $this->osDashboardService->getCrossTabWeeklyOtherOperatingSystem($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab All Operating System
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyAllOperatingSystem() {
        return $this->osDashboardService->getCrossTabMonthlyAllOperatingSystem($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tabb Monthly Microsoft Windows Operating System including mobile
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyWindows() {

        return $this->osDashboardService->getCrossTabMonthlyWindows($this->getDateRangeStartQuery());
    }

    /**
     * Return Linux Operating System
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyLinux() {
        return $this->osDashboardService->getCrossTabMonthlyLinux($this->getDateRangeStartQuery());
    }

    /**
     * Return Apple Mac Os Operating System.. Including 8.x,9.x Os X.. excluding iOs
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyMac() {
        return $this->osDashboardService->getCrossTabMonthlyMac($this->getDateRangeStartQuery());
    }

    /**
     * Return Android Operating System(mobile)
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyAndroid() {
        return $this->osDashboardService->getCrossTabMonthlyAndroid($this->getDateRangeStartQuery());
    }

    /**
     * Return iOS Operating System(mobile)
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyiOS() {
        return $this->osDashboardService->getCrossTabMonthlyiOS($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Monthly Other Browser
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyOtherOperatingSystem() {
        return $this->osDashboardService->getCrossTabMonthlyOtherOperatingSystem($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Yearly All Operating System
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabYearlyAllOperatingSystem() {
        return $this->osDashboardService->getCrossTabYearlyAllOperatingSystem($this->getDateRangeStartQuery());
    }

    /**
     * Return Cross Tab Date Range All Operating System
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabRangeAllOperatingSystem() {
        return $this->osDashboardService->getCrossTabRangeAllOperatingSystem(
                        $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery()
        );
    }

    /**
     * Reporting
     * @see config::excel()
     */
    function excel() {
        
    }

}

$staffWebAccessObject = new staffWebAccessClass ();
/**
 * crud -create,read,update,delete
 * */
if (isset($_POST ['method'])) {
    if (isset($_POST['output'])) {
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $staffWebAccessObject->setLeafId($_POST ['leafId']);
        }
        $staffWebAccessObject->setPageOutput($_POST['output']);
        $staffWebAccessObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $staffWebAccessObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $staffWebAccessObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $staffWebAccessObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $staffWebAccessObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$staffWebAccessObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$staffWebAccessObject->delete(); 
        }
    }
}
if (isset($_GET ['method'])) {
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $staffWebAccessObject->setLeafId($_GET ['leafId']);
    }
    /*
     * Admin Only
     */
    if (isset($_GET ['isAdmin'])) {
        $staffWebAccessObject->setIsAdmin($_GET ['isAdmin']);
    }
    /**
     * Database Request
     */
    if (isset($_GET ['databaseRequest'])) {
        $staffWebAccessObject->setRequestDatabase($_GET ['databaseRequest']);
    }
    /*
     *  Load the dynamic value
     */
    $staffWebAccessObject->execute();
    /**
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'updateStatus') {
        $staffWebAccessObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key 
     */
    if (isset($_GET ['staffWebAccessCode'])) {
        if (strlen($_GET ['staffWebAccessCode']) > 0) {
            $staffWebAccessObject->duplicate();
        }
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $staffWebAccessObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $staffWebAccessObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $staffWebAccessObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $staffWebAccessObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $staffWebAccessObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $staffWebAccessObject->excel();
        }
    }
}
?>