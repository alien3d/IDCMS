<?php

namespace Core\Portal\Controller;

use Core\ConfigClass;
use Core\Portal\Service\DefaultClass;
use Core\Portal\Service\MenuNavigationClass;
use Core\Portal\Service\SpotlightClass;
use Core\Portal\Service\StoryClass;
use Core\Portal\Service\TinyContentPortal;
use Core\Portal\Service\WallClass;
use Core\shared\SharedClass;
use Core\System\Management\Staff\Model\StaffModel;

if (!isset($_SESSION)) {
    session_start();
}
// start fake document root. it's absolute path
$x = addslashes(realpath(__FILE__));
// auto detect if \\ consider come from windows else / from Linux

$pos = strpos($x, "\\");
if ($pos !== false) {
    $d = explode("\\", $x);
} else {

    $d = explode("/", $x);
}
$newPath = null;
for ($i = 0; $i < count($d); $i++) {
    // if  find the library or package then stop
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
require_once($newFakeDocumentRoot . "/library/class/classAbstract.php");
require_once($newFakeDocumentRoot . "/v3/system/management/model/staffModel.php");
require_once($newFakeDocumentRoot . "/v3/portal/main/service/portalService.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");

/**
 * Class PortalControllerClass
 * Portal Controller
 *
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Controller
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class PortalControllerClass extends ConfigClass {

    /**
     * Connection DatabaseObject
     * Other database vendor also the same
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;

    /**
     * Translation
     * @var string
     */
    public $t;

    /**
     * Staff Model
     * @var \Core\System\Management\Staff\Model\StaffModel
     */
    public $model;

    /**
     * Staff User Access
     * @var \Core\System\Management\Staff\Model\StaffModel
     */
    public $staffWebAccess;

    /**
     * Portal Service
     * @var \Core\Portal\Service\DefaultClass
     */
    public $portalServiceDefault;

    /**
     * Menu Navigation
     * @var \Core\Portal\Service\MenuNavigationClass
     */
    public $portalServiceMenu;

    /**
     * Story
     * @var \Core\Portal\Service\StoryClass
     */
    public $portalServiceStory;

    /**
     * Mini Content Information
     * @var \Core\Portal\Service\TinyContentPortal
     */
    public $portalServiceTinyContentPortal;

    /**
     * Wall
     * @var \Core\Portal\Service\WallClass
     */
    public $portalServiceWall;

    /**
     * Spotlight -> Search Capability
     * @var \Core\Portal\Service\SpotlightClass
     */
    public $portalServiceSpotlight;

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
     * Folder Primary Key
     * @var int
     */
    private $folderId;

    /**
     * Leaf Primary Key
     * @var int
     */
    private $leafId;

    /**
     * Leaf Name
     *
     * @var int
     */
    private $leafName;

    /**
     * Portal Title
     *
     * @var string
     */
    private $portalTitle;

    /**
     * Spotlight Search alike
     * @var string
     */
    private $spotlightString;

    /**
     * Reference Table Name
     * @var string
     */
    private $referenceTableName;

    /**
     * Reference Table Name Primary Key Sequence
     * @var int
     */
    private $tableNameId;

    /**
     * Primary Key Name
     * @var string
     */
    private $chartOfAccountCategoryCode;

    /**
     * Primary Key Name
     * @var string
     */
    private $chartOfAccountTypeCode;

    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();

        // static information
        $this->setPortalTitle('IDCMS CORE');
        $this->setViewPath("./v3/portal/main/view/ticket.php");
        $this->setControllerPath("./v3/portal/main/controller/ticketController.php");
    }

    /**
     * Class Loader
     */
    public function execute() {

        // object declaration
        parent::__construct();
        if ($this->getVendor() == self::MYSQL) {
            $this->q = new \Core\Database\Mysql\Vendor();
        } else if ($this->getVendor() == self::MSSQL) {
            $this->q = new \Core\Database\Mssql\Vendor();
        } else if ($this->getVendor() == self::ORACLE) {
            $this->q = new \Core\Database\Oracle\Vendor();
        }


        $this->q->connect();

        $this->model = new StaffModel();
        $this->model->setVendor($this->getVendor());
        $this->model->execute();

        $this->systemFormat = new SharedClass();
        $this->systemFormat->q = $this->q;
        $this->systemFormat->setCurrentTable($this->model->getTableName());
        $this->systemFormat->execute();

        $this->systemFormatArray = $this->systemFormat->getSystemFormat();

        $this->portalServiceDefault = new DefaultClass();
        $this->portalServiceDefault->q = $this->q;
        $this->portalServiceDefault->setVendor($this->getVendor());
        $this->portalServiceDefault->execute();

        $this->portalServiceMenu = new MenuNavigationClass();
        $this->portalServiceMenu->q = $this->q;
        $this->portalServiceMenu->systemFormatArray = $this->systemFormatArray;
        $this->portalServiceMenu->setVendor($this->getVendor());
        $this->portalServiceMenu->execute();

        $this->portalServiceStory = new StoryClass();
        $this->portalServiceStory->q = $this->q;
        $this->portalServiceStory->setVendor($this->getVendor());
        $this->portalServiceStory->execute();

        $this->portalServiceTinyContentPortal = new TinyContentPortal();
        $this->portalServiceTinyContentPortal->q = $this->q;
        $this->portalServiceTinyContentPortal->setVendor($this->getVendor());
        $this->portalServiceTinyContentPortal->execute();

        $this->portalServiceWall = new WallClass();
        $this->portalServiceWall->q = $this->q;
        $this->portalServiceWall->setVendor($this->getVendor());
        $this->portalServiceWall->execute();

        $this->portalServiceSpotlight = new SpotlightClass();
        $this->portalServiceSpotlight->q = $this->q;
        $this->portalServiceSpotlight->setVendor($this->getVendor());
        $this->portalServiceSpotlight->execute();

        $translator = new SharedClass();
        $translator->setCurrentTable($this->model->getTableName());
        // $translator->setFilename('generalLedger.php');
        $translator->execute();

        $this->systemFormat = new SharedClass();
        $this->systemFormat->q = $this->q;
        $this->systemFormat->setCurrentTable($this->model->getTableName());
        $this->systemFormat->execute();

        $this->systemFormatArray = $this->systemFormat->getSystemFormat();

        $this->translate = $translator->getLeafTranslation(); // short because code too long
        $this->t = $translator->getDefaultTranslation(); // short because code too long

        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        }
        if (isset($_SESSION['roleDescription'])) {
            $this->setRoleDesc($_SESSION['roleDescription']);
        }
        if (isset($_SESSION['staffName'])) {
            $this->setStaffName($_SESSION['staffName']);
        }
        if (isset($_SESSION['staffName'])) {
            $this->setUsername($_SESSION['staffName']);
        }
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

    /**
     * Authentication User/Staff
     * return void
     */
    public function authentication() {
        $this->portalServiceDefault->authentication($this->model->getStaffName(), $this->model->getStaffPassword());
    }

    /**
     * Logout
     * return void
     */
    public function logout() {
        $this->portalServiceDefault->setLogout();
    }

    /**
     * Change Language
     * @param int $languageId Language Primary Key
     * @return void
     * @throws \Exception
     */
    public function setChangeLanguage($languageId) {
        $this->portalServiceDefault->setChangeLanguage($languageId);
    }

    /**
     * Route Application
     */
    public function routeApplication() {
        $this->portalServiceMenu->route($this->getPageId(), $this->getPageType());
    }

    /**
     * Route Module
     */
    public function routeModule() {
        $this->portalServiceMenu->route($this->getModuleId(), $this->getPageType());
    }

    /**
     * Return Module Primary Key
     * @return int $moduleId
     */
    public function getModuleId() {
        return $this->moduleId;
    }

    /**
     * Set Module Primary Key
     * @param int $value
     * @return \Core\Portal\Controller\PortalControllerClass
     */
    public function setModuleId($value) {
        $this->moduleId = $value;
        return $this;
    }

    /**
     * Route Folder
     */
    public function routeFolder() {
        $this->portalServiceMenu->route($this->getFolderId(), $this->getPageType());
    }

    /**
     * Return Folder Primary Key
     * @return int $folderId
     */
    public function getFolderId() {
        return $this->folderId;
    }

    /**
     * Set Folder Primary Key
     *
     * @param int $value
     * @return \Core\Portal\Controller\PortalControllerClass
     */
    public function setFolderId($value) {
        $this->folderId = $value;
        return $this;
    }

    /**
     * Route Leaf
     */
    public function routeLeaf() {
        // from dashboard
        if ($this->getChartOfAccountCategoryCode() && strlen(trim($this->getChartOfAccountCategoryCode())) > 0) {
            $this->portalServiceMenu->setChartOfAccountCategoryCode($this->getChartOfAccountCategoryCode());
        }
        if ($this->getChartOfAccountTypeCode() && strlen(trim($this->getChartOfAccountTypeCode())) > 0) {
            $this->portalServiceMenu->setChartOfAccountTypeCode($this->getChartOfAccountTypeCode());
        }
        // drill down grid
        if ($this->getReferenceTableName() && strlen(trim($this->getReferenceTableName())) > 0) {
            $this->portalServiceMenu->setReferenceTableName($this->getReferenceTableName());
        }
        if ($this->getTableNameId() && strlen(trim($this->getTableNameId())) > 0) {
            $this->portalServiceMenu->setTableNameId($this->getTableNameId());
        }
        if ($this->getLeafName() && strlen(trim($this->getLeafName())) > 0) {
            $this->portalServiceMenu->setLeafName($this->getLeafName());
        }
        $this->portalServiceMenu->route($this->getLeafId(), $this->getPageType());
    }

    /**
     * Return Chart Of Account Category Code
     * @return string $chartOfAccountCategoryCode
     */
    public function getChartOfAccountCategoryCode() {
        return $this->chartOfAccountCategoryCode;
    }

    /**
     * Set Chart Of Account Category Code
     * @param string $value
     * @return \Core\Portal\Controller\PortalControllerClass
     */
    public function setChartOfAccountCategoryCode($value) {
        $this->chartOfAccountCategoryCode = $value;
        return $this;
    }

    /**
     * Return Chart Of Account Type Code
     * @return string $chartOfAccountTypeCode
     */
    public function getChartOfAccountTypeCode() {
        return $this->chartOfAccountTypeCode;
    }

    /**
     * Set Chart Of Account Type Code
     * @param string $value
     * @return \Core\Portal\Controller\PortalControllerClass
     */
    public function setChartOfAccountTypeCode($value) {
        $this->chartOfAccountTypeCode = $value;
        return $this;
    }

    /**
     * Return Reference Table Name
     * @return string $referenceTableName
     */
    public function getReferenceTableName() {
        return $this->referenceTableName;
    }

    /**
     * Set Reference Table Name
     * @param string $value Name
     * @return \Core\Portal\Controller\PortalControllerClass
     */
    public function setReferenceTableName($value) {
        $this->referenceTableName = $value;
        return $this;
    }

    /**
     * Return Reference Table Primary Key / Sequence
     * @return int $tableNameId
     */
    public function getTableNameId() {
        return $this->tableNameId;
    }

    /**
     * Set Reference Table Primary Key / Sequence
     * @param string $value Name
     * @return \Core\Portal\Controller\PortalControllerClass
     */
    public function setTableNameId($value) {
        $this->tableNameId = $value;
        return $this;
    }

    /**
     * Return Leaf Name
     * @return string $leafName
     */
    public function getLeafName() {
        return $this->leafName;
    }

    /**
     * Set Leaf Name
     * @param string $value
     * @return \Core\Portal\Controller\PortalControllerClass
     */
    public function setLeafName($value) {
        $this->leafName = $value;
        return $this;
    }

    /**
     * Return Leaf Primary Key
     *
     * @return int $leafId
     */
    public function getLeafId() {
        return $this->leafId;
    }

    /**
     * Set Leaf Primary Key
     * @param int $value
     * @return \Core\Portal\Controller\PortalControllerClass
     */
    public function setLeafId($value) {
        $this->leafId = $value;
        return $this;
    }

    /**
     * Render Route Menu
     */
    public function routeMenu() {
        $data = $this->portalServiceMenu->application();
        $this->renderMenu($data);
    }

    /**
     * Render Menu
     * @param mixed $application
     */
    public function renderMenu($application) {
        $str = "";
        $str .= "<a class=\"btn-navbar\" data-toggle=\"collapse\" data-target=\".nav-collapse\">\n";
        $totalApplication = intval(count($application));
        for ($i = 0; $i < $totalApplication; $i++) {
            $str .= "<span class=\"i-bar\"></span>\n";
        }
        $str .= "</a><a class=\"brand\" href=\"index.php\">Core</a>\n
        <div class=\"nav-collapse\">\n
            <ul class=\"nav\">\n";

        // cms menu router
        if (isset($application) && is_array($application)) {
            $totalApplication = intval(count($application));
            for ($i = 0; $i < $totalApplication; $i++) {
                $totalModule = 0;
                if (isset($application[$i]['module'])) {
                    $totalModule = count($application[$i]['module']);
                }
                if ($totalModule == 0) {
                    $str .= "<li class=\"active\"><a href=\"javascript:void(0)\" onClick=\"loadBelow('" . intval(
                                    $application[$i]['applicationId']
                            ) . "','','','','application');\">";

                    if (isset($application[$i]['applicationNative'])) {
                        $str .= $application[$i]['applicationNative'];
                    }
                    $str .= "</a></li>\n";
                } else {
                    $str .= "<li class=\"dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"javascript:void(0)\">" . $application[$i]['applicationNative'] . "<b class=\"caret\"></b></a>\n";
                    $str .= "<ul class=\"dropdown-menu\">\n";
                    for ($j = 0; $j < $totalModule; $j++) {
                        if ($application[$i]['module'][$j]['isSingle'] == 1) {
                            $str .= "<li><a href=\"javascript:void(0)\"  onClick=\"loadBelow('" . intval(
                                            $application[$i]['applicationId']
                                    ) . "','" . intval(
                                            $application[$i]['module'][$j]['moduleId']
                                    ) . "','','','module')\">" . $application[$i]['module'][$j]['moduleNative'] . "</a></li>";
                        } else {
                            $totalFolder = count($application[$i]['module'][$j]['folder']);
                            $str .= "<li";
                            if ($totalFolder > 0) {
                                $str .= " class=\"dropdown submenu\" ";
                            }
                            $str .= "><a href=javascript:void(0) onClick=\"loadSidebar('" .
                                    intval($application[$i]['applicationId']) .
                                    "','" .
                                    $application[$i]['module'][$j]['moduleId'] .
                                    "')\">" .
                                    $application[$i]['module'][$j]['moduleNative'] .
                                    "</a>\n";

                            if ($totalFolder > 0) {

                                $str .= "<ul class=\"dropdown-menu submenu-show submenu-hide\">\n";
                            }
                            if ($totalFolder > 0) {
                                for ($n = 0; $n < $totalFolder; $n++) {
                                    $totalLeaf = count(
                                            $application[$i]['module'][$j]['folder'][$n]['leaf']
                                    );

                                    $str .= "<li";
                                    if ($totalLeaf > 0) {
                                        $str .= " class=\"dropdown submenu\" ";
                                    }
                                    $str .= "><a href=\"javascript:void(0)\">" .
                                            $application[$i]['module'][$j]['folder'][$n]['folderNative'] .
                                            "</a>\n";
                                    if ($totalLeaf > 0) {
                                        $str .= "<ul class=\"dropdown-menu submenu-show submenu-hide\">";
                                    }
                                    if ($totalLeaf > 0) {
                                        for ($h = 0; $h < $totalLeaf; $h++) {

                                            $str .= "<li><a href=\"javascript:void(0)\" onClick=\"loadLeft(" .
                                                    intval(
                                                            $application[$i]['module'][$j]['folder'][$n]['leaf'][$h]['leafId']
                                                    ) .
                                                    ",'" .
                                                    $this->getSecurityToken() .
                                                    "')\">" .
                                                    $application[$i]['module'][$j]['folder'][$n]['leaf'][$h]['leafNative'] .
                                                    "</a></li>";
                                        }
                                    }

                                    if ($totalLeaf > 0) {
                                        $str .= "</ul>";
                                    }
                                    $str .= "</li>";
                                }
                            }

                            if ($totalFolder > 0) {
                                $str .= "</ul>";
                            }

                            $str .= "</li>";
                        }
                    }
                    $str .= "</ul>";
                }
                $str .= "</li>";
            }
        }

        $str .= "</ul></div>
                    <div id=\"loginArea\" class=\"navbar-text pull-right\">
                        <p class=\"navbar-text pull-right\">Logged in as <a href=\"javascript:void(0)\"><i class=\"glyphicon glyphicon-user glyphicon-white\"></i>" .
                $_SESSION['username'] . "</a> | <a href=\"javascript:void(0)\"><i class=\"glyphicon glyphicon-fire glyphicon-white\"></i>Notification</a> | <a href=\"logout.php\"><i class=\"glyphicon glyphicon-home glyphicon-white\"></i>Logout</a></p>
                    </div>
               ";
        echo $str;
    }

    /**
     * Render Sidebar
     */
    public function routeSidebar() {

		/**
        // render the left viewport
        echo "<div id=\"leftViewport\" class=\"col-xs-3 col-sm-3 col-md-3\">\n";

        if ($this->getApplicationId()) {
            $data = $this->portalServiceMenu->folder($this->getApplicationId(), $this->getModuleId());
        } else {
            $this->setApplicationId($this->getApplicationIdFromCode('CORE'));
            $this->setModuleId($this->getModuleIdFromCode('SYSME'));

            $data = $this->portalServiceMenu->folder($this->getApplicationId(), $this->getModuleId());
        }
        $avatar = $this->portalServiceTinyContentPortal->getAvatar();
        if (!$avatar) {
            $avatar = './images/Blueticons_Win/PNGs/Devil.png';
        }else { 
			$avatar = "./v3/system/management/images/" . $avatar;
		}
        // echo print_r($data);
        //
        if ($this->getRoleId() == 4) {
            $this->renderSimpleButton($avatar);
        } else {
   //         $this->renderSidebar($avatar, $data);
   //         $this->renderTopTen($this->portalServiceTinyContentPortal->leftCellTopTen());
        }
        // @depreciate render right viewPort
        //if ($this->getRoleId() == 4) {
        //    $span = "9";
        //} else {
        //    $span = "12";
        //}
		*/
        echo "</div>";
        echo "<div id=\"rightViewport\" class=\"col-xs-12 col-sm-12 col-md-12\" onmouseover=\"looseFocusMenu();\" >";
        echo "<div class=\"col-xs-12 col-sm-12 col-md-12\">";
        if ($this->getRoleId() == 4) {
            $this->portalServiceMenu->route($this->getApplicationIdFromCode('DSHBRD'), 'application');
        } else {
            if ($this->getApplicationId() && !($this->getModuleId())) {
                $this->portalServiceMenu->route($this->getApplicationId(), 'application');
            } else {
                if ($this->getApplicationId() && $this->getModuleId()) {
                    $this->portalServiceMenu->route($this->getModuleId(), 'module');
                }
            }
        }

        echo "</div>";
        if ($this->getRoleId() == 4) {

            $incomeBudget = $this->portalServiceMenu->getTotalBudget('I');
            $expensesBudget = $this->portalServiceMenu->getTotalBudget('E');
            $incomeActual = $this->portalServiceMenu->getTotalActual('I');
            $expensesActual = $this->portalServiceMenu->getTotalActual('E');
            $pettyCashBalance = $this->portalServiceMenu->getTotalBank(1);
            $bankBalance = $this->portalServiceMenu->getTotalBank(2);
            //echo "aaa:[".$pettyCashBalance."]aaaa:[".$bankBalance."]<br>";
            if (class_exists('NumberFormatter')) {
                if (is_array($this->systemFormatArray) && $this->systemFormatArray['languageCode'] != '') {
                    $a = new \NumberFormatter($this->systemFormatArray['languageCode'], \NumberFormatter::CURRENCY);
                    $incomeBudgetFormatted = $a->format($incomeBudget);
                    $incomeActualFormatted = $a->format($incomeActual);
                    $expensesBudgetFormatted = $a->format($expensesBudget);
                    $expensesActualFormatted = $a->format($expensesActual);
                    $pettyCashBalanceFormatted = $a->format($pettyCashBalance);
                    $bankBalanceFormatted = $a->format($bankBalance);
                } else {
                    $incomeBudgetFormatted = number_format($incomeBudget) . " You can assign Currency Format ";
                    $incomeActualFormatted = number_format($incomeActual) . " You can assign Currency Format ";
                    $expensesBudgetFormatted = number_format($expensesBudget) . " You can assign Currency Format ";
                    $expensesActualFormatted = number_format($expensesActual) . " You can assign Currency Format ";
                    $pettyCashBalanceFormatted = number_format($pettyCashBalance) . " You can assign Currency Format ";
                    $bankBalanceFormatted = number_format($bankBalance) . " You can assign Currency Format ";
                }
            } else {
                $incomeBudgetFormatted = number_format($incomeBudget);
                $incomeActualFormatted = number_format($incomeActual);
                $expensesBudgetFormatted = number_format($expensesBudget);
                $expensesActualFormatted = number_format($expensesActual);
                $pettyCashBalanceFormatted = number_format($pettyCashBalance);
                $bankBalanceFormatted = number_format($bankBalance);
            }
            $incomePercent = @round(($incomeActual / $incomeBudget) * 100);
            $expensesPercent = @round(($expensesActual / $expensesBudget) * 100);
            echo "<div class=\"col-md-3\">
					<div class=\"row\">
						<div class=\"col-xs-12 col-sm-12 col-md-12\">
							<h4>" . $this->t['bankBalanceTextLabel'] . "</h4>
							" . $bankBalanceFormatted . "
						</div>
					</div>
					<div class=\"row\">
						<div class=\"col-xs-12 col-sm-12 col-md-12\">
							<h4>" . $this->t['cashBalanceTextLabel'] . "</h4>
							" . $pettyCashBalanceFormatted . "
						</div>
					</div>
				    <div class=\"row\">
					<div class=\"col-xs-12 col-sm-12 col-md-12\">
						<strong>" . $this->t['incomeTextLabel'] . "</strong>  <br>" . $incomePercent . "% FROM " . $incomeBudgetFormatted . "
						<div class=\"progress progress-success progress-striped\">
<div class=\"bar\" style=\"width: " . $incomePercent . "%\"></div>
</div><strong>" . $this->t['expensesTextLabel'] . "</strong><br> " . $expensesPercent . "% FROM " . $expensesBudgetFormatted . "
<div class=\"progress progress-danger progress-striped\">
<div class=\"bar\" style=\"width: " . $expensesPercent . "%\"></div>
</div>
					</div>
				  </div>
				</div>";
        }
        // extra for bank and cash
        echo "</div>";
    }

    /**
     * Return Application Primary Key
     * @return int $applicationId
     */
    public function getApplicationId() {
        return $this->applicationId;
    }

    /**
     * Set Application Primary Key
     * @param int $value
     * @return \Core\Portal\Controller\PortalControllerClass
     */
    public function setApplicationId($value) {
        $this->applicationId = $value;
        return $this;
    }

    /**
     * Return Application Primary Key From Code
     * @param string $applicationCode Code
     * @return int $applicationId
     * @throws \Exception
     */
    private function getApplicationIdFromCode($applicationCode) {
        return $this->portalServiceMenu->getApplicationIdFromCode($applicationCode);
    }

    /**
     * Return Module Primary Key From Code
     * @param string $moduleCode Code
     * @return int $moduleId
     * @throws \Exception
     */
    private function getModuleIdFromCode($moduleCode) {
        return $this->portalServiceMenu->getModuleIdFromCode($moduleCode);
    }

    /**
     * Render Simple Accounting Button
     * @param string $avatar
     * @return string
     * @depreciated
     */
    private function renderSimpleButton($avatar) {
        $str = "
        <div class=\"row\">\n
            <div class=\"panel panel-default\">\n
                <div class=\"panel-body\">\n
                    <div class=\"col-xs-5 col-sm-5 col-md-5\"><a href=\"javascript:void(0)\" class=\"img-thumbnail\"><img src=\"" . $avatar . "\" width=\"55\" height=\"55\"></a></div>\n
                        <div class=\"col-xs-7 col-sm-7 col-md-7\"><b><img src=\"./images/icons/computer.png\"> " . $this->getRoleDesc(
                ) . "</b><br><img src=\"./images/icons/user.png\"> " . $this->getUsername() . "
                        </div>\n
                    </div>\n
                </div>\n
            </div>\n
            <div class=\"row\">
                <div class=\"col-xs-12 col-sm-12 col-md-12\">\n
                    <button class=\"btn-large btn-block btn-primary\" onClick=\"showMeModal('budgetPreview',1)\" type=\"button\">" . $this->t['budgetTextLabel'] . "</button>\n
                </div>\n
            </div>\n
            <div class=\"row\">\n
                <div class=\"col-xs-12 col-sm-12 col-md-12\">\n
                    <button class=\"btn-success btn-block btn-large btn-primary\" onClick=\"showMeModal('incomePreview',1)\" type=\"button\">" . $this->t['moneyInTextLabel'] . "</button>\n
                </div>\n
            </div>\n
            <div class=\"row\">\n
                <div class=\"col-xs-12 col-sm-12 col-md-12\">\n
                    <button class=\"btn-danger btn-block btn-large btn-primary\" type=\"button\" onClick=\"showMeModal('expensesPreview',1)\">" . $this->t['moneyOutTextLabel'] . "</button>\n
                </div>\n
            </div>\n";
        /**
         * <div class=\"row\">
         * <div class=\"col-xs-12 col-sm-12 col-md-12\">
         * <button class=\"btn-inverse  btn-block btn-large btn-primary\" type=\"button\" onClick=\"showMeModal('bankPreview',1)\">" . $this->t['bankCashTextLabel'] . "</button>
         * </div>
         * </div>
         */
        echo $str;
    }

    /**
     * Render Side Bar
     * @param string $avatar
     * @param mixed $folder
     * @throws \Exception
     */
    public function renderSidebar($avatar, $folder) {
        $str = "";
        $d = 0;

        $str .= "
        <div class=\"row\">\n
            <div class=\"col-xs-12 col-sm-12 col-md-12\">\n
                <div class=\"panel panel-default\">\n
                    <div class=\"panel-body\">\n
                        <div class=\"col-xs-4 col-sm-4 col-md-4\">\n
                            <a href=\"javascript:void(0)\" class=\"thumbnail\"><img src=\"" . $avatar . "\" width=\"55\" height=\"55\"></a>\n
                        </div>\n
                        <div class=\"col-xs-8 col-sm-8 col-md-8\">\n
                            <img src=\"./images/icons/computer.png\"> " . $this->getRoleDesc() . "<br>\n
                            <img src=\"./images/icons/user.png\"> " . $this->getUsername() . "<br>\n
                            <a href=\"javascript:void(0)\" onClick=\"loadLeft('" . $this->getLeafIdFromCode(
                        'SYSME'
                ) . "','" . $this->getSecurityToken() . "');\">Setting</a> |\n
                            <a href=\"logout.php\">Logout</a>\n
                        </div>\n
                    </div>\n
                </div>\n
            </div>\n
        </div>\n
        <div class=\"row\">\n
            <div class=\"col-xs-12 col-sm-12 col-md-12\">\n
                <div class=\"panel panel-default\">\n
                    <div class=\"panel-body\">\n";
        $str .= "       <ul class=\"nav nav-tabs nav-pills nav-stacked\">\n";
        $totalFolder = count($folder);
        for ($i = 0; $i < $totalFolder; $i++) {
            $d++;
            if ($this->getFolderId() == intval($folder[$i]['folderId'])) {
                $str .= "<script type=\"text/javacript\">\n";
                // $str.="\$(document).ready(function(){\n";
                //
                // $str.=" showMeSideBar(" . $d . "," . intval($totalFolder) .
                // ");k\n";
                // $str.=" });\n";
                $str .= "</script>\n";
            }

            $str .= "
            <li ";
            if ($this->getFolderId() == intval($folder[$i]['folderId'])) {
                $str .= "  class=\"active\" ";
            }
            $str .= "
                onClick=\"showMeSideBar('" . $d . "','" . intval($totalFolder) . "');\" id=\"hoverMe" . $d . "\" class=\"\">\n
                <a href=\"javascript:void(0)\"><img alt=\"close folder\" id=\"imageFolder" . $d . "\" src=\"./images/icons/folder-horizontal.png\">&nbsp;&nbsp;" . $folder[$i]['folderNative'] . "</a>\n
            </li>\n";
            $totalLeaf = count($folder[$i]['leaf']);

            if ($totalLeaf > 0) {
                $str .= "<li id=\"common" . $d . "\" class=\"hide\"><ul class=\"nav nav-tabs nav-stacked\">\n";
                for ($j = 0; $j < $totalLeaf; $j++) {
                    $str .= "<li><a href=\"javascript:void(0)\" onClick=\"loadLeft('" . intval(
                                    $folder[$i]['leaf'][$j]['leafId']
                            ) . "','" . $this->getSecurityToken(
                            ) . "');\"><img src=\"images/icons/folder-open.png\" alt=\"application\">&nbsp;&nbsp;" . $folder[$i]['leaf'][$j]['leafNative'] . "</a></li>\n";
                }
                $str .= "</ul>\n
                </li>\n";
            }

            if ($totalLeaf == 0 || $totalLeaf == null) {
                $str .= "
                <li id=\"common" . $d . "\" class=\"hide\">\n
                    <ul class=\"nav nav-list\">\n
                        <li><img src=\"./images/icons/burn.png\" alt=\"not found\" title=\"not found\"> 404</li>\n
                    </ul>\n
                </li>\n";
            }
        }

        $str .= "       </ul>\n
                    </div>\n
                </div>\n
            </div>\n
        </div>\n";

        echo $str;
    }

    /**
     * Return Leaf Primary Key From Code
     * @param string $leafCode Code
     * @return int $leafId Leaf Primary Key
     * @throws \Exception
     */
    private function getLeafIdFromCode($leafCode) {
        return $this->portalServiceMenu->getLeafIdFromCode($leafCode);
    }

    /**
     * Render Top Ten
     * @param mixed $data
     */
    public function renderTopTen($data) {
        $str = null;
        $str .= "
        <div class=\"row\">\n
            <div class=\"col-xs-12 col-sm-12 col-md-12\">\n
                <div class=\"panel panel-default\">
                    <div class=\"panel-body\">\n";
        $str .= "       <ul class=\"nav nav-tabs nav-pills nav-stacked\">\n";
        $str .= "           <li class=\"active\"><a href=\"javascript:void(0)\"><img src=\"images/icons/clock-moon-phase.png\" alt=\"application\">History</a></li>";

        foreach ($data as $row) {
            $str .= "<li><a href=\"javascript:void(0)\" onClick=\"loadLeft(" . intval(
                            $row['leafId']
                    ) . ",'" . $this->getSecurityToken(
                    ) . "');\"><img src=\"images/icons/chocolate-milk.png\" alt=\"application\">&nbsp;&nbsp;" . $row['leafNative'] . "</a></li>";
        }
        $str .= "       </ul>\n
                    </div>\n
                </div>\n
            </div>\n
        </div>\n";
        echo $str;
    }

    /**
     * Return Language
     * @return mixed
     * @throws \Exception
     */
    public function getLanguage() {
        return $this->portalServiceDefault->getLanguage();
    }

    /**
     * Set new Session Language
     * @param int $languageId Language Primary Key
     * @return void
     * @throws \Exception
     */
    public function setLanguage($languageId) {
        $this->portalServiceDefault->setLanguage($languageId);
    }

    /**
     * Return Array of Theme
     * @return mixed
     * @throws \Exception
     */
    public function getTheme() {
        return $this->portalServiceDefault->getTheme();
    }

    /**
     * Set New Session Theme
     * @param int $theme theme fileName
     * @return void
     * @throws \Exception
     */
    public function setTheme($theme) {
        $this->portalServiceDefault->setTheme($theme);
    }

    /**
     * Return Application Array
     * @return mixed
     * @throws \Exception
     */
    public function getApplicationArray() {
        return $this->portalServiceMenu->application();
    }

    /**
     * Return Story
     * @return mixed
     */
    public function getStory() {
        return $this->portalServiceStory->bottomStory();
    }

    /**
     * Return Avatar
     * @return mixed
     * @depreciated
     */
    public function getAvatar() {
        // return $this->portalServiceTinyContentPortal->leftCellImage();
    }

    /**
     * Return Top Ten
     * @return mixed
     * @throws \Exception
     */
    public function getTopTen() {
        return $this->portalServiceTinyContentPortal->leftCellTopTen();
    }

    /**
     * Search Engine..
     * like spotlight.. Apple don't sue me.. :P
     * @return mixed
     * @throws \Exception
     */
    public function spotlight() {
        return $this->portalServiceSpotlight->spotlight(
                        $this->getSpotlightString()
        );
    }

    /**
     * Return Leaf Primary Key
     * @return int $spotlightString
     */
    public function getSpotlightString() {
        return $this->spotlightString;
    }

    /**
     * Set Leaf Primary Key
     * @param int $value
     * @return \Core\Portal\Controller\PortalControllerClass
     */
    public function setSpotlightString($value) {
        $this->spotlightString = $value;
        return $this;
    }

    /**
     * Return Spotlight Total
     * @return mixed
     */
    public function getSpotlightTotal() {
        return $this->portalServiceSpotlight->getSpotlightTotal();
    }

    /**
     * Return Notification
     * @return mixed
     * @throws \Exception
     */
    public function getNotification() {
        return $this->portalServiceWall->getNotification();
    }

    /**
     * Return Notification Reply
     * @param int $notificationId Notification Primary key
     * @return mixed
     * @throws \Exception
     */
    public function getNotificationReply($notificationId) {
        return $this->portalServiceWall->getNotificationReplied($notificationId);
    }

    /**
     * Return Ticket
     * @return mixed
     * @throws \Exception
     */
    public function getTicket() {
        return $this->portalServiceWall->getTicket();
    }

    /**
     * Return Ticket Replied
     * @param int $ticketId Ticket Primary Key
     * @return mixed
     * @throws \Exception
     */
    public function getTicketReply($ticketId) {
        return $this->portalServiceWall->getNotificationReplied($ticketId);
    }

    /**
     * Return Total Ticket
     * @return mixed
     * @throws \Exception
     */
    public function getTotalTicket() {
        return $this->portalServiceWall->getTotalTicket();
    }

    /**
     * Return Total Notification
     * @return mixed
     */
    public function getTotalNotification() {
        return $this->portalServiceWall->getTotalNotification();
    }

    /**
     * Return Total HeartBeat
     */
    public function getTotalHeartBeat() {
        header('Content-Type:application/json; charset=utf-8');

        $title = '';
        if ($this->portalServiceWall->getTotalTicket() > 0) {
            $title .= "( " . $this->portalServiceWall->getTotalTicket() .
                    " ) Message ";
        }
        if ($this->portalServiceWall->getTotalNotification() > 0) {
            $title .= "( " . $this->portalServiceWall->getTotalNotification() .
                    " ) Notification ";
        }
        // second option is add both like facebook
        $title .= "( " .
                ($this->portalServiceWall->getTotalTicket() +
                $this->portalServiceWall->getTotalNotification()) .
                " ) Message ";

        $title .= $this->getPortalTitle();
        echo json_encode(
                array(
                    "totalTicket" => $this->portalServiceWall->getTotalTicket(),
                    "totalNotification" => $this->portalServiceWall->getTotalNotification(),
                    "title" => $title
                )
        );
        exit();
    }

    /**
     * Return Portal Title
     * @return int $leafId
     */
    public function getPortalTitle() {
        return $this->portalTitle;
    }

    /**
     * Set Portal Title
     * @param int $value
     * @return \Core\Portal\Controller\PortalControllerClass
     */
    public function setPortalTitle($value) {
        $this->portalTitle = $value;
        return $this;
    }

    /**
     * Return Title And HeartBeat
     * @return mixed
     * @throws \Exception
     */
    public function getTitleAndHeartBeat() {
        $title = '';
        if ($this->portalServiceWall->getTotalTicket() > 0) {
            $title .= "( " . $this->portalServiceWall->getTotalTicket() .
                    " ) Message ";
        }
        if ($this->portalServiceWall->getTotalNotification() > 0) {
            $title .= "( " . $this->portalServiceWall->getTotalNotification() .
                    " ) Notification ";
        }
        // second option is add both like facebook
        $title .= "( " .
                ($this->portalServiceWall->getTotalTicket() +
                $this->portalServiceWall->getTotalNotification()) .
                " ) Message ";

        $title .= $this->getPortalTitle();
        return $title;
    }

    /**
     * Return Folder Primary Key From Code
     * @param string $folderCode Code
     * @return int $folderId Folder Primary Key
     * @throws \Exception
     */
    private function getFolderIdFromCode($folderCode) {
        return $this->portalServiceMenu->getFolderIdFromCode($folderCode);
    }

}

if (isset($_POST) && count($_POST) > 0) {
    $portal = new PortalControllerClass();
    if (isset($_POST['pageId']) && strlen($_POST['pageId']) > 0) {
        $portal->setPageId($_POST['pageId']);
    } else {
        $portal->setPageId(' ');
    }
    if (isset($_POST['moduleId']) && strlen($_POST['moduleId']) > 0) {
        $portal->setModuleId($_POST['moduleId']);
    } else {
        $portal->setModuleId(' ');
    }
    if (isset($_POST['folderId']) && strlen($_POST['folderId']) > 0) {
        $portal->setFolderId($_POST['folderId']);
    } else {
        $portal->setFolderId(' ');
    }
    if (isset($_POST['leafId']) && strlen($_POST['leafId']) > 0) {
        $portal->setLeafId($_POST['leafId']);
    } else {
        $portal->setLeafId(' ');
    }
    if (isset($_POST['pageType']) && strlen($_POST['pageType']) > 0) {
        $portal->setPageType($_POST['pageType']);
    } else {
        $portal->setPageType(' ');
    }
    if (isset($_POST['spotlightString']) && strlen($_POST['spotlightString']) > 0) {
        $portal->setSpotlightString($_POST['spotlightString']);
    } else {
        $portal->setSpotlightString(' ');
    }
    $portal->execute();
    if (isset($_POST['username']) && isset($_POST['password'])) {

        $portal->authentication();
    }
    if (isset($_POST['method'])) {

        if (isset($_POST['pageType'])) {
            if ($_POST['method'] == 'read' && $_POST['pageType'] == 'application') {
                $portal->routeApplication();
            }
            if ($_POST['method'] == 'read' && $_POST['pageType'] == 'module') {
                $portal->routeModule();
            }
            if ($_POST['method'] == 'read' && $_POST['pageType'] == 'folder') {
                $portal->routeFolder();
            }
            if ($_POST['method'] == 'read' && $_POST['pageType'] == 'leaf') {
                // drill down from dashboard
                if (isset($_POST['chartOfAccountCategoryCode'])) {
                    $portal->setChartOfAccountCategoryCode($_POST['chartOfAccountCategoryCode']);
                } else {
                    $portal->setChartOfAccountCategoryCode(' ');
                }
                if (isset($_POST['chartOfAccountTypeCode'])) {
                    $portal->setChartOfAccountTypeCode($_POST['chartOfAccountTypeCode']);
                } else {
                    $portal->setChartOfAccountTypeCode(' ');
                }
                if (isset($_POST['bankId'])) {
                    // $portal->setBankId($_POST['bankId']);
                }
                // drill down from dashboard
                // drill down from grid
                if (isset($_POST['tableName'])) {
                    $portal->setReferenceTableName($_POST['tableName']);
                } else {
                    $portal->setReferenceTableName(' ');
                }
                if (isset($_POST['tableNameId'])) {
                    $portal->setTableNameId($_POST['tableNameId']);
                } else {
                    $portal->setTableNameId(' ');
                }
                if (isset($_POST['leafName'])) {
                    $portal->setLeafName($_POST['leafName']);
                } else {
                    $portal->setLeafName(' ');
                }
                // drill down
                $portal->routeLeaf();
            }
            if ($_POST['method'] == 'read' && $_POST['pageType'] == 'menu') {
                if (isset($_POST['leafId'])) {
                    $portal->setLeafId($_POST['leafId']);
                } else {
                    $portal->setLeafId(' ');
                }
                $portal->routeMenu();
            }
            if ($_POST['method'] == 'read' && $_POST['pageType'] == 'sidebar') {
                if (isset($_POST['applicationId'])) {
                    $portal->setApplicationId($_POST['applicationId']);
                }
                if (isset($_POST['moduleId'])) {
                    $portal->setModuleId($_POST['moduleId']);
                }
                $portal->routeSidebar();
            }
        }
    }
}
?>
