<?php

namespace Core\Portal\Dashboard\Management\Controller;

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
require_once($newFakeDocumentRoot . "v3/system/security/model/leafModel.php");
require_once($newFakeDocumentRoot . "v3/portal/main/service/dashboardService.php");

/**
 * Class ManagementDashboardClass
 * Here will updated Figure Only.. the detailing not here
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Dashboard\Management\Controller
 * @subpackage Security
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ManagementDashboardClass extends \Core\ConfigClass {

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
     * @var string
     */
    public $model;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     * @var \PHPPowerPoint
     */
    //private $powerPoint; 
    /**
     * Service-Business Application Process or other ajax request
     * @var string
     */
    public $service;

    /**
     * Translation
     * @var mixed
     */
    public $t;

    /**
     * Translation Array
     * @var string
     */
    public $translate;

    /**
     * System Format
     * @var mixed
     */
    public $systemFormat;

    /**
     * Browser Dashboard
     * @var string
     */
    public $browserService;

    /**
     * Operating system Dashboard
     * @var string
     */
    public $osService;

    /**
     * Internet Protocol Dashboard
     * @var string
     */
    public $ipService;

    /**
     * Ticket Dashboard
     * @var string
     */
    public $ticketService;

    /**
     * Management Dashboard
     * @var string
     */
    public $managementService;

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
     * @var mixed
     */
    private $systemFormatArray;

    /**
     * Constructor
     */
    function __construct() {
        $this->translate = array();
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
    }

    /**
     * @return int
     */
    function getBusinessPartnerDebtorTotal() {
        return 1;
    }

    /**
     * @return int
     */
    function getBusinessPartnerCreditorTotal() {
        return 1;
    }

    /**
     * @return int
     */
    function getBusinessPartnerStaffTotal() {
        return 1;
    }

    /**
     * @return int
     */
    function getBusinessPartnerInsuranceTotal() {
        return 1;
    }

    /**
     * @return int
     */
    function getBusinessPartnerTotal() {
        return 1;
    }

    /**
     * @return int
     */
    function getBusinessPartnerReminderTotal() {
        return 1;
    }

    /**
     * @return int
     */
    function getBusinessPartnerAmountDueTotal() {
        return 1;
    }

    /**
     * @return int
     */
    function getFixedAssetTotal() {
        return 1;
    }

    /**
     * @return int
     */
    function getFixedAssetDepreciation() {
        return 1;
    }

    /**
     * @return int
     */
    function getFixedAssetWriteOff() {
        return 1;
    }

    /**
     * @return int
     */
    function getFixedAssetLoan() {
        return 1;
    }

    /**
     * @return int
     */
    function getFixedAssetWorkOrder() {
        return 1;
    }

    /**
     * Create
     * @see config::read()
     */
    public function create() {
        
    }

    /**
     * Read
     * @see config::read()
     */
    public function read() {
        
    }

    /**
     * Update
     * @see config::update()
     */
    public function update() {
        
    }

    /**
     * Delete
     * @see config::delete()
     */
    public function delete() {
        
    }

    /**
     * Reporting
     * @see config::excel()
     */
    public function excel() {
        
    }

}

?>