<?php

namespace Core\Portal\Main\Dashboard\Finance\Controller;

use Core\ConfigClass;
use Core\Portal\Main\Dashboard\Finance\Service\chartOfAccountCategoryDashboardService;
use Core\Portal\Main\Dashboard\Finance\Service\chartOfAccountTypeDashboardService;
use Core\Portal\Main\Dashboard\Finance\Service\generalLedgerLinkService;

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
require_once($newFakeDocumentRoot . "v3/portal/main/service/financeDashboardService.php");

/**
 * Class FinanceDashboardClass
 * Here will updated Figure Only.. the detailing not here
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Management\Dashboard\Finance\Controller
 * @subpackage Security
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class FinanceDashboardClass extends ConfigClass {

    /**
     * Asset (Code)->Balance Sheet
     */
    const ASSET = 'A';

    /**
     * Asset (Code)->Balance Sheet
     */
    const LIABILITY = 'L';

    /**
     * Equity (Code)->Balance Sheet
     */
    const EQUITY = 'OE';

    /**
     * Income (Code)->Profit And Loss
     */
    const INCOME = 'I';

    /**
     * Expenses (Code)->Profit And Loss
     */
    const EXPENSES = 'E';
    const LONG_TERM_LIABILITIES = 'B';
    const OTHER_LIABILITIES = 'C';
    const FIXED_ASSETS = 'D';
    const OTHER_ASSETS = 'E';
    const CURRENT_ASSETS = 'F';
    const CASH_AND_BANKS = 'CB';
    const PETTY_CASH = 'PC';
    const BANK = 'BK';
    const ACCOUNT_RECEIVABLE = 'AR';
    const CURRENT_LIABILITIES = 'G';
    const CAPITAL_OR_RETURN_EARNING = 'A';
    const SALES_OR_REVENUE = 'H';
    const COST_OF_GOOD_SOLD = 'J';
    const OTHER_INCOME = 'K';
    ////////////////////////////// chart of account type
    const OPERATING_EXPENSES = 'M';
    const TAXATION = 'P';
    const POTION_OF_DEBT = 'POD';
    const GENERAL_AND_ADMINISTRATIVE = 'GA';
    const INCOME_OPERATION = 'IO';
    const INCOME_TAX = 'IT';
    const INCOME_TAX_PAYMENT = 'IT';
    const NET_INCOME = 'NI';
    const ACCOUNT_PAYABLE = 'AP';
    const INVENTORY = 'IV';
    const CAPITAL_STOCK = 'CS';
    const RESEARCH_AND_DEVELOPMENT = 'RAD';
    const SHAREHOLDER_EQUITY = 'SE';
    const SALES_AND_MARKETING = 'SM';
    const GROSS_MARGIN = 'GM';
    const NET_SALES = 'NS';
    const GENERAL_LEDGER_LEAF_CODE = 'GLLDGR';
    const BALANCE_SHEET_LEAF_CODE = 'GLBLSHT';
    const PROFIT_AND_LOSS_LEAF_CODE = 'GLPL';

    /**
     * Connection to the database
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;

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
     * General Ledger Dashboard
     * @var \Core\Portal\Main\Dashboard\Finance\Service\generalLedgerLinkService
     */
    public $generalLedgerLinkService;

    /**
     * General Ledger Dashboard
     * @var \Core\Portal\Main\Dashboard\Finance\Service\chartOfAccountCategoryDashboardService
     */
    public $generalLedgerDashboardService;

    /**
     * General Ledger Dashboard
     * @var \Core\Portal\Main\Dashboard\Finance\Service\chartOfAccountTypeDashboardService
     */
    public $generalLedgerDetailDashboardService;

    /**
     * Current Asset Dashboard
     * @var string
     */
    public $currentAssetService;

    /**
     * Fixed Asset Dashboard
     * @var string
     */
    public $fixedAssetService;

    /**
     * Current Liabilities Dashboard
     * @var string
     */
    public $currentLiabilitiesService;

    /**
     *  Share  Holder Equity Dashboard
     * @var string
     */
    public $shareHolderEquityService;
    // leaf code
    /**
     * Gross Margin Dashboard
     * @var string
     */
    public $grossMarginService;

    /**
     * Operating Expenses Dashboard
     * @var string
     */
    public $operatingExpensesService;

    /**
     * Net Income Dashboard
     * @var string
     */
    public $netIncomeService;

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
        $this->q->setLeafId($this->getLeafId());
        $this->q->setLog($this->getLog());
        $this->q->setAudit($this->getAudit());
        $this->q->connect();

        $this->generalLedgerLinkService = new generalLedgerLinkService();
        $this->generalLedgerLinkService->q = $this->q;
        $this->generalLedgerLinkService->t = $this->t;
        $this->generalLedgerLinkService->setVendor($this->getVendor());
        $this->generalLedgerLinkService->execute();

        $this->generalLedgerDashboardService = new chartOfAccountCategoryDashboardService();
        $this->generalLedgerDashboardService->q = $this->q;
        $this->generalLedgerDashboardService->t = $this->t;
        $this->generalLedgerDashboardService->setVendor($this->getVendor());
        $this->generalLedgerDashboardService->execute();

        $this->generalLedgerDetailDashboardService = new chartOfAccountTypeDashboardService();
        $this->generalLedgerDetailDashboardService->q = $this->q;
        $this->generalLedgerDetailDashboardService->t = $this->t;
        $this->generalLedgerDetailDashboardService->setVendor($this->getVendor());
        $this->generalLedgerDetailDashboardService->execute();
    }

    /**
     * Return General Ledger Grid  Leaf Primary Key
     * @return int
     */
    function getGeneralLedgerLeafId() {
        return $this->generalLedgerLinkService->getGridLeafCode(self::GENERAL_LEDGER_LEAF_CODE);
    }

    /**
     * Return Balance Sheet Grid  Leaf Primary Key
     * @return int
     */
    function getBalanceSheetLeafId() {
        return $this->generalLedgerLinkService->getGridLeafCode(self::BALANCE_SHEET_LEAF_CODE);
    }

    /**
     * Return Balance Sheet Grid  Leaf Primary Key
     * @return int
     */
    function getProfitAndLossLeafId() {
        return $this->generalLedgerLinkService->getGridLeafCode(self::PROFIT_AND_LOSS_LEAF_CODE);
    }

    /**
     * Return Asset Based On Current Year
     * @param null|string $mode
     * @return int|double
     */
    function getAsset($mode = null) {
        return $this->generalLedgerDashboardService->getChartOfAccountCategory(self::ASSET, $mode);
    }

    /**
     * Return Liabilities Based On Current Year
     * @param null|string $mode
     * @return int|double
     */
    function getLiabilities($mode = null) {
        return $this->generalLedgerDashboardService->getChartOfAccountCategory(self::LIABILITY, $mode);
    }

    /**
     * Return Income Based On Current Year
     * @param null|string $mode
     * @return int|double
     */
    function getIncome($mode = null) {
        return $this->generalLedgerDashboardService->getChartOfAccountCategory(self::INCOME, $mode);
    }

    /**
     * Return Expenses Based On Current Year
     * @param null|string $mode
     * @return int|double
     */
    function getExpenses($mode = null) {
        return $this->generalLedgerDashboardService->getChartOfAccountCategory(self::EXPENSES, $mode);
    }

    /**
     * Return Equity Based On Current Year
     * @param null|string $mode
     * @return int|double
     */
    function getEquity($mode = null) {
        return $this->generalLedgerDashboardService->getChartOfAccountCategory(self::EQUITY, $mode);
    }

    /**
     * @param null|string $mode
     * @return int|double
     */
    function getBank($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::BANK, $mode);
    }

    /**
     * @param null|string $mode
     * @return int|double
     */
    function getPettyCash($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::PETTY_CASH, $mode);
    }

    /**
     * @param null|string $mode
     * @return int|double
     */
    function getAccountReceivable($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::ACCOUNT_RECEIVABLE, $mode);
    }

    /**
     * @param null|string $mode
     * @return int|double
     */
    function getInventory($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::INVENTORY, $mode);
    }

    /**
     * @param null|string $mode
     * @return int|double
     */
    function getOtherAsset($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::OTHER_ASSETS, $mode);
    }

    /**
     * @param null $mode
     * @return int|double
     */
    function getCurrentAsset($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::CURRENT_ASSETS, $mode);
    }

    /**
     * @param null $mode
     * @return int|double
     */
    function getFixedAsset($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::FIXED_ASSETS, $mode);
    }

    /**
     * @param null $mode
     * @return int|double
     */
    function getAccumulatedDepreciation($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::FIXED_ASSETS, $mode);
    }

    /**
     * @param null $mode
     * @return int|double
     */
    function getNetFixedAsset($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::FIXED_ASSETS, $mode);
    }

    /**
     * @param null $mode
     * @return float|int
     */
    function getAccountPayable($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::ACCOUNT_PAYABLE, $mode);
    }

    /**
     * @param null $mode
     * @return int|double
     */
    function getAccruedExpenses($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::EQUITY, $mode);
    }

    /**
     * @param null|string $mode
     * @return int|double
     */
    function getCurrentPotionOfDebt($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::POTION_OF_DEBT, $mode);
    }

    /**
     * @param null|string $mode
     * @return int|double
     */
    function getIncomeTaxPayment($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::INCOME_TAX_PAYMENT, $mode);
    }

    /**
     * @param null $mode
     * @return int|double
     */
    function getCurrentLiabilities($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::LONG_TERM_LIABILITIES, $mode);
    }

    /**
     * @param null $mode
     * @return float|int
     */
    function getCapitalStock($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::CAPITAL_STOCK, $mode);
    }

    /**
     * @param null $mode
     * @return int
     */
    function getRetainEarning($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(
                        self::CAPITAL_OR_RETURN_EARNING, $mode
        );
    }

    /**
     * @param null $mode
     * @return float|int
     */
    function getShareHolderEquity($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::SHAREHOLDER_EQUITY, $mode);
    }

    /**
     * @param null $mode
     * @return float|int
     */
    function getNetSales($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::NET_SALES, $mode);
    }

    /**
     * @param null $mode
     * @return int|double
     */
    function getCostOfGoodSold($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::COST_OF_GOOD_SOLD, $mode);
    }

    /**
     * @param null $mode
     * @return float|int
     */
    function getGrossMargin($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::GROSS_MARGIN, $mode);
    }

    /**
     * @param null|string $mode
     * @return int|double
     */
    function getSalesMarketing($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::SALES_AND_MARKETING, $mode);
    }

    /**
     * @param null $mode
     * @return float|int
     */
    function getResearchAndDevelopment($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::RESEARCH_AND_DEVELOPMENT, $mode);
    }

    /**
     * @param null|string $mode
     * @return int|double
     */
    function getGeneralAndAdministrative($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(
                        self::GENERAL_AND_ADMINISTRATIVE, $mode
        );
    }

    /**
     * @param null $mode
     * @return float|int
     */
    function getOperatingExpenses($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::OPERATING_EXPENSES, $mode);
    }

    /**
     * @param null $mode
     * @return float|int
     */
    function getIncomeFromOperation($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::INCOME_OPERATION, $mode);
    }

    /**
     * @param null $mode
     * @return float|int
     */
    function getInterestIncome($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::OTHER_INCOME, $mode);
    }

    /**
     * @param null $mode
     * @return float|int
     */
    function getIncomeTax($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::INCOME_TAX, $mode);
    }

    /**
     * @param null $mode
     * @return float|int
     */
    function getNetIncome($mode = null) {
        return $this->generalLedgerDetailDashboardService->getChartOfAccountType(self::NET_INCOME, $mode);
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
