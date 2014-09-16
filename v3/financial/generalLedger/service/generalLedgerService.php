<?php

namespace Core\Financial\GeneralLedger\GeneralLedger\Service;

use Core\ConfigClass;

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
require_once($newFakeDocumentRoot . "library/class/classShared.php");

/**
 * Class GeneralLedgerService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\GeneralLedger\GeneralLedger\Service
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class GeneralLedgerService extends ConfigClass {

    /**
     * Connection to the database
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;

    /**
     * Translate Label
     * @var string
     */
    public $t;

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
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        if ($_SESSION['companyId']) {
            $this->setCompanyId($_SESSION['companyId']);
        } else {
            // fall back to default database if anything wrong
            $this->setCompanyId(1);
        }
        if ($_SESSION['staffId']) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            // fall back to default database if anything wrong
            $this->setStaffId(1);
        }
    }

    /**
     *  Create
     * @see config::create()
     * @return void
     */
    public function create() {
        
    }

    /**
     * Read
     * @see config::read()
     * @return void
     */
    public function read() {
        
    }

    /**
     * Update
     * @see config::update()
     * @return void
     */
    public function update() {
        
    }

    /**
     * Update
     * @see config::delete()
     * @return void
     */
    public function delete() {
        
    }

    /**
     * Reporting
     * @see config::excel()
     * @return void
     */
    public function excel() {
        
    }

}

class chartOfAccountCategoryCrossTabService extends ConfigClass {

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

    /**
     * Connection to the database
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;

    /**
     * Translate Label
     * @var string
     */
    public $t;

    /**
     * Day
     * @var int
     */
    private $day;

    /**
     * Week
     * @var int
     */
    private $week;

    /**
     * Month
     * @var int
     */
    private $month;

    /**
     * Year
     * @var int
     */
    private $year;

    /**
     * Total Day In Month
     * @var int
     */
    private $totalDayInMonth;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        if ($_SESSION['companyId']) {
            $this->setCompanyId($_SESSION['companyId']);
        } else {
            // fall back to default database if anything wrong
            $this->setCompanyId(1);
        }
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        // default for portal visitor

        if (isset($_SESSION['companyId'])) {
            $this->setRoleId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        } else {
            $this->setRoleId(7);
        }
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            $this->setStaffId(9);
        }
        if (isset($_SESSION['languageId'])) {
            $this->setLanguageId($_SESSION['languageId']);
        } else {
            $this->setLanguageId(21);
        }
        $this->getOverrideFinanceYear();
    }

    /**
     * Get Default Company Country
     */
    public function getOverrideFinanceYear() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT `financeyear`.`financeYearYear`
            FROM   `financesetting`
            JOIN   `financeyear`
            USING   (`companyId`,`financeYearId`)
            WHERE  `financesetting`.`companyId`     =   '" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT [financeSetting].[financeYearId]
            FROM   [financeSetting]
            JOIN   [financeYear]
            ON     [financeSetting].[companyId]     =   [financeYear].[companyId]
            AND    [financeSetting].[financeYearId] =   [financeYear].[financeYearId]
            WHERE  [financeSetting].[companyId]     =   '" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT FINANCESETTING.FINANCEYEARID AS \"financeYearId\"
            FROM   FINANCESETTING
            JOIN   FINANCEYEAR
            ON     FINANCESETTING.COMPANYID     =   FINANCEYEAR.COMPANYID
            AND    FINANCESETTING.FINANCEYEARID =   FINANCEYEAR.FINANCEYEARID
            WHERE  FINANCESETTING.COMPANYID     =   '" . $this->getCompanyId() . "'";
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $this->setYear($row['financeYearYear']);
        }
    }

    /*
     * Return Cross Tab Time All Chart Of Account Category
     * @param string $chartOfAccountCategoryCode
     * @param null|int $mode 1-figure,2-total
     * @return mixed
     */

    function getCrossTabTimeAllChartOfAccountCategory($date, $chartOfAccountCategoryCode = null, $mode = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        if ($mode == 1) {
            $filterBy = "`localAmount`";
        } else {
            $filterBy = "1";
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        SELECT  ";
            $strInside = null;
            $hour = null;
            while ($hour++ < 23) {
                $strInside .= "IFNULL(SUM(IF(`generalLedgerDate`  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                                $hour
                        ) . "%'," . $filterBy . ",0)) ,0)as `" . $hour . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
        FROM    `generalledger`
		WHERE	`companyId`='" . $this->getCompanyId() . "'";
            if ($chartOfAccountCategoryCode) {
                $sql .= " AND `chartOfAccountCategoryCode`	=	'" . $chartOfAccountCategoryCode . "";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Get Month
     * @return int
     */
    function getMonth() {
        return $this->month;
    }

    /**
     * Set Month
     * @param int $value
     */
    function setMonth($value) {
        $this->month = $value;
    }

    /**
     *
     * @return int
     */
    function getDay() {
        return $this->day;
    }

    /**
     *
     * @param int $value
     */
    function setDay($value) {
        $this->day = $value;
    }

    /**
     * Return Year
     * @return int
     */
    function getYear() {
        return $this->year;
    }

    /**
     * Set Year
     * @param int $value
     */
    function setYear($value) {
        $this->year = $value;
    }

    /**
     *
     * @param string $dateInfo
     * @return string
     */
    function changeZero($dateInfo) {
        if (strlen($dateInfo) == 1) {
            $dateInfo = '0' . $dateInfo;
        }
        return ($dateInfo);
    }

    /**
     * Return Cross Tab Daily All Browser
     * @param string $date
     * @param null|string $chartOfAccountCategoryCode
     * @param null|int $mode 1-figure,2-total
     * @return mixed
     */
    function getCrossTabDailyChartOfAccountCategory($date, $chartOfAccountCategoryCode = null, $mode = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        if ($mode == 1) {
            $filterBy = "`localAmount`";
        } else {
            $filterBy = "1";
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        SELECT  ";
            $strInside = null;
            for ($i = 0; $i < $this->getTotalDayInMonth(); $i++) {
                $strInside .= "IFNULL(SUM(IF(`generalLedgerDate`  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($i) . "%'," . $filterBy . ",0)) ,0)as `" . $i . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
        FROM    `generalledger`
        WHERE   MONTH(`generalLedgerDate`) = '" . $this->getMonth() . "'
        AND     YEAR(`generalLedgerDate`) = '" . $this->getYear() . "'
		AND		`companyId`='" . $this->getCompanyId() . "'";
            if ($chartOfAccountCategoryCode) {
                $sql .= " AND `chartOfAccountCategoryCode`	=	'" . $chartOfAccountCategoryCode . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Total Day In month
     * @return int
     */
    function getTotalDayInMonth() {
        return $this->totalDayInMonth;
    }

    /**
     * Set Total Day In Month
     * @param int $value
     */
    function setTotalDayInMonth($value) {
        $this->totalDayInMonth = $value;
    }

    /**
     * Return Cross Tab Weekly Others Operating System
     * @param string $date
     * @param null|string $chartOfAccountCategoryCode
     * @param null|int $mode 1-figure,2-total
     * @return mixed
     */
    function getCrossTabWeeklyChartOfAccountCategory($date, $chartOfAccountCategoryCode = null, $mode = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $d = new \DateTime(date("Y-m-d", mktime(0, 0, 0, $this->getMonth(), $this->getDay(), $this->getYear())));
        $weekday = $d->format('w');
        $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
        $d->modify("-$diff day");
        if ($mode == 1) {
            $filterBy = "`localAmount`";
        } else {
            $filterBy = "1";
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        SELECT ";
            $strInside = null;
            for ($i = 0; $i < 8; $i++) {

                $strInside .= "IFNULL(SUM(IF(`generalLedgerDate` like '" . $d->format(
                                'Y-m-d'
                        ) . "%'," . $filterBy . ",0) ),0) AS `" . $i . "`,";
                $d->modify('+1 day');
            }

            $sql .= substr($strInside, 0, -1);
            $sql .= "
		FROM    `generalledger`
        WHERE   `companyId`='" . $this->getCompanyId() . "'";
            if ($chartOfAccountCategoryCode) {
                $sql .= " AND `chartOfAccountCategoryCode`	=	'" . $chartOfAccountCategoryCode . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Monthly Chart Of Account Category
     * @param string $date
     * @param string $chartOfAccountCategoryCode
     * @param null|int $mode 1-figure,2-total
     * @return mixed
     */
    function getCrossTabMonthlyChartOfAccountCategory($date, $chartOfAccountCategoryCode, $mode = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);
        if ($mode == 1) {
            $filterBy = "`localAmount`";
        } else {
            $filterBy = "1";
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        SELECT  IFNULL(SUM(IF(month(`generalLedgerDate`) = 1," . $filterBy . ",0)),0)  as `jan`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 2," . $filterBy . ",0)),0) as `feb`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 3," . $filterBy . ",0)),0) as `mac`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 4," . $filterBy . ",0)),0) as `apr`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 5," . $filterBy . ",0)),0) as `may`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 6," . $filterBy . ",0)),0) as `jun`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 7," . $filterBy . ",0)),0) as `jul`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 8," . $filterBy . ",0)),0) as `aug`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 9," . $filterBy . ",0)),0) as `sep`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 10," . $filterBy . ",0)),0) as `oct`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 11," . $filterBy . ",0)),0) as `nov`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 12," . $filterBy . ",0)),0) as `dec`
        FROM    `generalledger`
        WHERE   YEAR(`generalLedgerDate`) = '" . $this->getYear() . "'
		AND		`companyId`='" . $this->getCompanyId() . "'";
            if ($chartOfAccountCategoryCode) {
                $sql .= " AND `chartOfAccountCategoryCode`	=	'" . $chartOfAccountCategoryCode . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Yearly All Chart Of Account Category
     * @param string $date
     * @param null|int $mode 1-figure,2-total
     * @return mixed
     */
    function 
    getCrossTabYearlyAllChartOfAccountCategory($date, $mode = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);
        if ($mode == 1) {
            $filterBy = "`localAmount`";
        } else {
            $filterBy = "1";
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        SELECT  IFNULL(SUM(IF(`chartOfAccountCategoryCode` 	=	'I'," . $filterBy . ",0)),0) as `Asset`,
                IFNULL(SUM(IF(`chartOfAccountCategoryCode`	=	'L'," . $filterBy . ",0)),0) as `Income`,
                IFNULL(SUM(IF(`chartOfAccountCategoryCode` 	=	'OE'," . $filterBy . ",0)),0) as  `Equity`,
                IFNULL(SUM(IF(`chartOfAccountCategoryCode`	=	'I'," . $filterBy . ",0)),0) as  `Income`,
                IFNULL(SUM(IF(`chartOfAccountCategoryCode`	=	'E'," . $filterBy . ",0)),0) as  `Expenses,
        FROM    `generalledger`
        WHERE   `companyId`='" . $this->getCompanyId() . "'
		AND		YEAR(`generalLedgerDate`) =  '" . $this->getYear() . "'
        AND     YEAR(`generalLedgerDate`)
        BETWEEN YEAR(DATE_SUB(NOW(), INTERVAL 3 YEAR)) AND YEAR(NOW())";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Date Range All Chart Of Account Category
     * @param string $dateStart
     * @param string $dateEnd
     * @param null|int $mode 1-figure,2-total
     * @return mixed
     */
    function getCrossTabRangeAllChartOfAccountCategory($dateStart, $dateEnd, $mode = null) {
        $sql = null;
        $row = array();
        $dateStartArray = explode("-", $dateStart);

        $dayStart = $dateStartArray[0];
        $monthStart = $dateStartArray[1];
        $yearStart = $dateStartArray[2];

        $dateEndArray = explode("-", $dateEnd);

        $dayEnd = $dateEndArray[0];
        $monthEnd = $dateEndArray[1];
        $yearEnd = $dateEndArray[2];
        if ($mode == 1) {
            $filterBy = "`localAmount`";
        } else {
            $filterBy = "1";
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        SELECT  IFNULL(SUM(IF(`chartOfAccountCategoryCode`  IN( 'A','A00000')	    ," . $filterBy . ",0)),0) as `Asset`,
                IFNULL(SUM(IF(`chartOfAccountCategoryCode`	IN('L','L00000')	    ," . $filterBy . ",0)),0) as `Income`,
                IFNULL(SUM(IF(`chartOfAccountCategoryCode`	IN('OE','E00000')    ," . $filterBy . ",0)),0) as `Equity`,
                IFNULL(SUM(IF(`chartOfAccountCategoryCode`	IN('I','E00000')	    ," . $filterBy . ",0)),0) as `Expenses`,
                IFNULL(SUM(IF(`chartOfAccountCategoryCode`	IN('E','B00000')	    ," . $filterBy . ",0)),0) as `Income`
        FROM    `generalledger`
        WHERE   `companyId`='" . $this->getCompanyId() . "'
		AND		(`generalLedgerDate` BETWEEN '" . $yearStart . "-" . $monthStart . "-" . $dayStart . "'
		AND 		'" . $yearEnd . "-" . $monthEnd . "-" . $dayEnd . "');";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }

        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Set Week
     * @return string
     */
    function getWeek() {
        return $this->week;
    }

    /**
     *
     * @param string $value
     */
    function setWeek($value) {
        $this->week = $value;
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

class chartOfAccountTypeCrossTabService extends ConfigClass {

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
    ////////////////////////////// chart of account type
    const BANK = 'BK';
    const ACCOUNT_RECEIVABLE = 'AR';
    const CURRENT_LIABILITIES = 'G';
    const CAPITAL_OR_RETURN_EARNING = 'A';
    const SALES_OR_REVENUE = 'H';
    const COST_OF_GOOD_SOLD = 'J';
    const OTHER_INCOME = 'K';
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

    /**
     * Connection to the database
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;

    /**
     * Translate Label
     * @var string
     */
    public $t;

    /**
     * Week
     * @var int
     */
    private $week;

    /**
     * Day
     * @var int
     */
    private $day;

    /**
     * Month
     * @var int
     */
    private $month;

    /**
     * Year
     * @var int
     */
    private $year;

    /**
     * Total Day In Month
     * @var int
     */
    private $totalDayInMonth;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        if ($_SESSION['companyId']) {
            $this->setCompanyId($_SESSION['companyId']);
        } else {
            // fall back to default database if anything wrong
            $this->setCompanyId(1);
        }
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        // default for portal visitor
        $this->translate = array();

        if (isset($_SESSION['companyId'])) {
            $this->setRoleId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        } else {
            $this->setRoleId(7);
        }
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            $this->setStaffId(9);
        }
        if (isset($_SESSION['languageId'])) {
            $this->setLanguageId($_SESSION['languageId']);
        } else {
            $this->setLanguageId(21);
        }
        $this->getOverrideFinanceYear();
    }

    /**
     * Get Default Company Country
     */
    public function getOverrideFinanceYear() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT `financeyear`.`financeYearYear`
            FROM   `financesetting`
            JOIN   `financeyear`
            USING   (`companyId`,`financeYearId`)
            WHERE  `financesetting`.`companyId`     =   '" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT [financeSetting].[financeYearId]
            FROM   [financeSetting]
            JOIN   [financeYear]
            ON     [financeSetting].[companyId]     =   [financeYear].[companyId]
            AND    [financeSetting].[financeYearId] =   [financeYear].[financeYearId]
            WHERE  [financeSetting].[companyId]     =   '" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT FINANCESETTING.FINANCEYEARID AS \"financeYearId\"
            FROM   FINANCESETTING
            JOIN   FINANCEYEAR
            ON     FINANCESETTING.COMPANYID     =   FINANCEYEAR.COMPANYID
            AND    FINANCESETTING.FINANCEYEARID =   FINANCEYEAR.FINANCEYEARID
            WHERE  FINANCESETTING.COMPANYID     =   '" . $this->getCompanyId() . "'";
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $this->setYear($row['financeYearYear']);
        }
    }

    /*
     * Return Cross Tab Time All Chart Of Account Type
     * @param null|string $chartOfAccountTypeCode
     * @param null|int $mode 1-figure,2-total
     * @return mixed
     */

    function getCrossTabTimeChartOfAccountType($date, $chartOfAccountTypeCode = null, $mode = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        if ($mode == 1) {
            $filterBy = "`localAmount`";
        } else {
            $filterBy = "1";
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        SELECT  ";
            $strInside = null;
            $hour = null;
            while ($hour++ < 23) {
                $strInside .= "IFNULL(SUM(IF(`generalLedgerDate`  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                                $hour
                        ) . "%'," . $filterBy . ",0)) ,0)as `" . $hour . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
        FROM    `generalledger`
		WHERE	`companyId`='" . $this->getCompanyId() . "'";
            if ($chartOfAccountTypeCode) {
                $sql .= " AND `chartOfAccountTypeCode`	=	'" . $chartOfAccountTypeCode . "";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Get Month
     * @return int
     */
    function getMonth() {
        return $this->month;
    }

    /**
     * Set Month
     * @param int $value
     */
    function setMonth($value) {
        $this->month = $value;
    }

    /**
     *
     * @return int
     */
    function getDay() {
        return $this->day;
    }

    /**
     *
     * @param int $value
     */
    function setDay($value) {
        $this->day = $value;
    }

    /**
     * Return Year
     * @return int
     */
    function getYear() {
        return $this->year;
    }

    /**
     * Set Year
     * @param int $value
     */
    function setYear($value) {
        $this->year = $value;
    }

    /**
     *
     * @param string $dateInfo
     * @return string
     */
    function changeZero($dateInfo) {
        if (strlen($dateInfo) == 1) {
            $dateInfo = '0' . $dateInfo;
        }
        return ($dateInfo);
    }

    /**
     * Return Cross Tab Daily All Browser
     * @param string $date
     * @param null|string $chartOfAccountTypeCode
     * @param null|int $mode 1-figure,2-total
     * @return mixed
     */
    function getCrossTabDailyChartOfAccountType($date, $chartOfAccountTypeCode = null, $mode = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        if ($mode == 1) {
            $filterBy = "`localAmount`";
        } else {
            $filterBy = "1";
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        SELECT  ";
            $strInside = null;
            for ($i = 0; $i < $this->getTotalDayInMonth(); $i++) {
                $strInside .= "IFNULL(SUM(IF(`generalLedgerDate`  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($i) . "%'," . $filterBy . ",0)) ,0)as `" . $i . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
        FROM    `generalledger`
        WHERE   MONTH(`generalLedgerDate`) = '" . $this->getMonth() . "'
        AND     YEAR(`generalLedgerDate`) = '" . $this->getYear() . "'
		AND		`companyId`='" . $this->getCompanyId() . "'";
            if ($chartOfAccountTypeCode) {
                $sql .= " AND `chartOfAccountTypeCode`	=	'" . $chartOfAccountTypeCode . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Total Day In month
     * @return int
     */
    function getTotalDayInMonth() {
        return $this->totalDayInMonth;
    }

    /**
     * Set Total Day In Month
     * @param int $value
     */
    function setTotalDayInMonth($value) {
        $this->totalDayInMonth = $value;
    }

    /**
     * Return Cross Tab Weekly Others Operating System
     * @param string $date
     * @param null|string $chartOfAccountTypeCode
     * @param null|int $mode 1-figure,2-total
     * @return mixed
     */
    function getCrossTabWeeklyChartOfAccountType($date, $chartOfAccountTypeCode = null, $mode = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $d = new \DateTime(date("Y-m-d", mktime(0, 0, 0, $this->getMonth(), $this->getDay(), $this->getYear())));
        $weekday = $d->format('w');
        $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
        $d->modify("-$diff day");
        if ($mode == 1) {
            $filterBy = "`localAmount`";
        } else {
            $filterBy = "1";
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        SELECT ";
            $strInside = null;
            for ($i = 0; $i < 8; $i++) {

                $strInside .= "IFNULL(SUM(IF(`generalLedgerDate` like '" . $d->format(
                                'Y-m-d'
                        ) . "%'," . $filterBy . ",0) ),0) AS `" . $i . "`,";
                $d->modify('+1 day');
            }

            $sql .= substr($strInside, 0, -1);
            $sql .= "
		FROM    `generalledger`
        WHERE   `companyId`='" . $this->getCompanyId() . "'";
            if ($chartOfAccountTypeCode) {
                $sql .= " AND `chartOfAccountTypeCode`	=	'" . $chartOfAccountTypeCode . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Monthly Chart Of Account Type
     * @param string $date
     * @param string $chartOfAccountTypeCode
     * @param null|int $mode 1-figure,2-total
     * @return mixed
     */
    function getCrossTabMonthlyChartOfAccountType($date, $chartOfAccountTypeCode, $mode = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);
        if ($mode == 1) {
            $filterBy = "`localAmount`";
        } else {
            $filterBy = "1";
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        SELECT  IFNULL(SUM(IF(month(`generalLedgerDate`) = 1," . $filterBy . ",0)),0)  as `jan`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 2," . $filterBy . ",0)),0) as `feb`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 3," . $filterBy . ",0)),0) as `mac`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 4," . $filterBy . ",0)),0) as `apr`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 5," . $filterBy . ",0)),0) as `may`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 6," . $filterBy . ",0)),0) as `jun`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 7," . $filterBy . ",0)),0) as `jul`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 8," . $filterBy . ",0)),0) as `aug`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 9," . $filterBy . ",0)),0) as `sep`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 10," . $filterBy . ",0)),0) as `oct`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 11," . $filterBy . ",0)),0) as `nov`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 12," . $filterBy . ",0)),0) as `dec`
        FROM    `generalledger`
        WHERE   YEAR(`generalLedgerDate`) = '" . $this->getYear() . "'
		AND		`companyId`='" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        if ($chartOfAccountTypeCode) {
            $sql .= " AND `chartOfAccountTypeCode`	=	'" . $chartOfAccountTypeCode . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Yearly All Chart Of Account Category
     * @param string $date
     * @param int $type 1->Balance Sheet . 2->Profit And Loss
     * @param null|int $mode 1-figure,2-total
     * @return mixed
     */
    function getCrossTabYearlyChartOfAccountType($date, $type, $mode = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);
        if ($mode == 1) {
            $filterBy = "`localAmount`";
        } else {
            $filterBy = "1";
        }
        if ($this->getVendor() == self::MYSQL) {
            if ($type == 1) {
                $sql = "
        SELECT  IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'A'," . $filterBy . ",0)),0) as `capitalReturnEarning`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'B'," . $filterBy . ",0)),0) as `longTermLiabilities`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'C'," . $filterBy . ",0)),0) as  `otherLiabilities`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'D'," . $filterBy . ",0)),0) as  `fixedAssets`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'E'," . $filterBy . ",0)),0) as  `otherAssets`,
				IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'F'," . $filterBy . ",0)),0) as `currentAssets`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'G'," . $filterBy . ",0)),0) as `currentLiabilities`
        FROM    `generalledger`
        WHERE   `companyId`='" . $this->getCompanyId() . "'
		AND		YEAR(`generalLedgerDate`) =  '" . $this->getYear() . "'";
            } else {
                $sql = "
        SELECT  IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'H'," . $filterBy . ",0)),0) as `sales`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'I'," . $filterBy . ",0)),0) as `salesAdjustment`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'J'," . $filterBy . ",0)),0) as  `otherLiabilities`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'K'," . $filterBy . ",0)),0) as  `costOfGoodSolds`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'L'," . $filterBy . ",0)),0) as  `otherIncome`,
				IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'M'," . $filterBy . ",0)),0) as `expenses`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'P'," . $filterBy . ",0)),0) as `taxation`,
				IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'S'," . $filterBy . ",0)),0) as `ordinary`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'T'," . $filterBy . ",0)),0) as `appropriation`
        FROM    `generalledger`
        WHERE   `companyId`='" . $this->getCompanyId() . "'
		AND		YEAR(`generalLedgerDate`) =  '" . $this->getYear() . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Date Range All Chart Of Account Category
     * @param string $dateStart
     * @param string $dateEnd
     * @param int $type 1->Balance Sheet ,2->Profit And loss
     * @param null|int $mode 1-figure,2-total
     * @return mixed
     */
    function getCrossTabRangeAllChartOfAccountType($dateStart, $dateEnd, $type, $mode = null) {
        $sql = null;
        $row = array();
        $dateStartArray = explode("-", $dateStart);

        $dayStart = $dateStartArray[0];
        $monthStart = $dateStartArray[1];
        $yearStart = $dateStartArray[2];

        $dateEndArray = explode("-", $dateEnd);

        $dayEnd = $dateEndArray[0];
        $monthEnd = $dateEndArray[1];
        $yearEnd = $dateEndArray[2];
        if ($mode == 1) {
            $filterBy = "`localAmount`";
        } else {
            $filterBy = "1";
        }
        if ($this->getVendor() == self::MYSQL) {
            if ($type == 1) {
                $sql = "
			SELECT   IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'A'," . $filterBy . ",0)),0) as `capitalReturnEarning`,
					IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'B'," . $filterBy . ",0)),0) as `longTermLiabilities`,
					IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'C'," . $filterBy . ",0)),0) as  `otherLiabilities`,
					IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'D'," . $filterBy . ",0)),0) as  `fixedAssets`,
					IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'E'," . $filterBy . ",0)),0) as  `otherAssets`,
					IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'F'," . $filterBy . ",0)),0) as `currentAssets`,
					IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'G'," . $filterBy . ",0)),0) as `currentLiabilities`
			FROM    `generalledger`
			WHERE   `companyId`='" . $this->getCompanyId() . "'
			AND		(`generalLedgerDate` BETWEEN '" . $yearStart . "-" . $monthStart . "-" . $dayStart . "'
			AND 		'" . $yearEnd . "-" . $monthEnd . "-" . $dayEnd . "');";
            } else {
                $sql = "
			   SELECT  IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'H'," . $filterBy . ",0)),0) as `sales`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'I'," . $filterBy . ",0)),0) as `salesAdjustment`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'J'," . $filterBy . ",0)),0) as  `otherLiabilities`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'K'," . $filterBy . ",0)),0) as  `costOfGoodSolds`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'L'," . $filterBy . ",0)),0) as  `otherIncome`,
				IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'M'," . $filterBy . ",0)),0) as `expenses`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'P'," . $filterBy . ",0)),0) as `taxation`,
				IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'S'," . $filterBy . ",0)),0) as `ordinary`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'T'," . $filterBy . ",0)),0) as `appropriation`
			FROM    `generalledger`
			WHERE   `companyId`='" . $this->getCompanyId() . "'
			AND		(`generalLedgerDate` BETWEEN '" . $yearStart . "-" . $monthStart . "-" . $dayStart . "'
			AND 		'" . $yearEnd . "-" . $monthEnd . "-" . $dayEnd . "');";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Set Week
     * @return string
     */
    function getWeek() {
        return $this->week;
    }

    /**
     *
     * @param string $value
     */
    function setWeek($value) {
        $this->week = $value;
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
    class chartOfAccountCrossTabService extends ConfigClass {

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
    ////////////////////////////// chart of account type
    const BANK = 'BK';
    const ACCOUNT_RECEIVABLE = 'AR';
    const CURRENT_LIABILITIES = 'G';
    const CAPITAL_OR_RETURN_EARNING = 'A';
    const SALES_OR_REVENUE = 'H';
    const COST_OF_GOOD_SOLD = 'J';
    const OTHER_INCOME = 'K';
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

    /**
     * Connection to the database
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;

    /**
     * Translate Label
     * @var string
     */
    public $t;

    /**
     * Week
     * @var int
     */
    private $week;

    /**
     * Day
     * @var int
     */
    private $day;

    /**
     * Month
     * @var int
     */
    private $month;

    /**
     * Year
     * @var int
     */
    private $year;

    /**
     * Total Day In Month
     * @var int
     */
    private $totalDayInMonth;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        if ($_SESSION['companyId']) {
            $this->setCompanyId($_SESSION['companyId']);
        } else {
            // fall back to default database if anything wrong
            $this->setCompanyId(1);
        }
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        // default for portal visitor
        $this->translate = array();

        if (isset($_SESSION['companyId'])) {
            $this->setRoleId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        } else {
            $this->setRoleId(7);
        }
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            $this->setStaffId(9);
        }
        if (isset($_SESSION['languageId'])) {
            $this->setLanguageId($_SESSION['languageId']);
        } else {
            $this->setLanguageId(21);
        }
        $this->getOverrideFinanceYear();
    }

    /**
     * Get Default Company Country
     */
    public function getOverrideFinanceYear() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT `financeyear`.`financeYearYear`
            FROM   `financesetting`
            JOIN   `financeyear`
            USING   (`companyId`,`financeYearId`)
            WHERE  `financesetting`.`companyId`     =   '" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT [financeSetting].[financeYearId]
            FROM   [financeSetting]
            JOIN   [financeYear]
            ON     [financeSetting].[companyId]     =   [financeYear].[companyId]
            AND    [financeSetting].[financeYearId] =   [financeYear].[financeYearId]
            WHERE  [financeSetting].[companyId]     =   '" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT FINANCESETTING.FINANCEYEARID AS \"financeYearId\"
            FROM   FINANCESETTING
            JOIN   FINANCEYEAR
            ON     FINANCESETTING.COMPANYID     =   FINANCEYEAR.COMPANYID
            AND    FINANCESETTING.FINANCEYEARID =   FINANCEYEAR.FINANCEYEARID
            WHERE  FINANCESETTING.COMPANYID     =   '" . $this->getCompanyId() . "'";
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $this->setYear($row['financeYearYear']);
        }
    }

    /*
     * Return Cross Tab Time All Chart Of Account Type
     * @param null|string $chartOfAccountTypeCode
     * @param null|int $mode 1-figure,2-total
     * @return mixed
     */

    function getCrossTabTimeChartOfAccountType($date, $chartOfAccountTypeCode = null, $mode = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        if ($mode == 1) {
            $filterBy = "`localAmount`";
        } else {
            $filterBy = "1";
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        SELECT  ";
            $strInside = null;
            $hour = null;
            while ($hour++ < 23) {
                $strInside .= "IFNULL(SUM(IF(`generalLedgerDate`  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                                $hour
                        ) . "%'," . $filterBy . ",0)) ,0)as `" . $hour . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
        FROM    `generalledger`
		WHERE	`companyId`='" . $this->getCompanyId() . "'";
            if ($chartOfAccountTypeCode) {
                $sql .= " AND `chartOfAccountTypeCode`	=	'" . $chartOfAccountTypeCode . "";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Get Month
     * @return int
     */
    function getMonth() {
        return $this->month;
    }

    /**
     * Set Month
     * @param int $value
     */
    function setMonth($value) {
        $this->month = $value;
    }

    /**
     *
     * @return int
     */
    function getDay() {
        return $this->day;
    }

    /**
     *
     * @param int $value
     */
    function setDay($value) {
        $this->day = $value;
    }

    /**
     * Return Year
     * @return int
     */
    function getYear() {
        return $this->year;
    }

    /**
     * Set Year
     * @param int $value
     */
    function setYear($value) {
        $this->year = $value;
    }

    /**
     *
     * @param string $dateInfo
     * @return string
     */
    function changeZero($dateInfo) {
        if (strlen($dateInfo) == 1) {
            $dateInfo = '0' . $dateInfo;
        }
        return ($dateInfo);
    }

    /**
     * Return Cross Tab Daily All Browser
     * @param string $date
     * @param null|string $chartOfAccountTypeCode
     * @param null|int $mode 1-figure,2-total
     * @return mixed
     */
    function getCrossTabDailyChartOfAccountType($date, $chartOfAccountTypeCode = null, $mode = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        if ($mode == 1) {
            $filterBy = "`localAmount`";
        } else {
            $filterBy = "1";
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        SELECT  ";
            $strInside = null;
            for ($i = 0; $i < $this->getTotalDayInMonth(); $i++) {
                $strInside .= "IFNULL(SUM(IF(`generalLedgerDate`  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($i) . "%'," . $filterBy . ",0)) ,0)as `" . $i . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
        FROM    `generalledger`
        WHERE   MONTH(`generalLedgerDate`) = '" . $this->getMonth() . "'
        AND     YEAR(`generalLedgerDate`) = '" . $this->getYear() . "'
		AND		`companyId`='" . $this->getCompanyId() . "'";
            if ($chartOfAccountTypeCode) {
                $sql .= " AND `chartOfAccountTypeCode`	=	'" . $chartOfAccountTypeCode . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Total Day In month
     * @return int
     */
    function getTotalDayInMonth() {
        return $this->totalDayInMonth;
    }

    /**
     * Set Total Day In Month
     * @param int $value
     */
    function setTotalDayInMonth($value) {
        $this->totalDayInMonth = $value;
    }

    /**
     * Return Cross Tab Weekly Others Operating System
     * @param string $date
     * @param null|string $chartOfAccountTypeCode
     * @param null|int $mode 1-figure,2-total
     * @return mixed
     */
    function getCrossTabWeeklyChartOfAccountType($date, $chartOfAccountTypeCode = null, $mode = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $d = new \DateTime(date("Y-m-d", mktime(0, 0, 0, $this->getMonth(), $this->getDay(), $this->getYear())));
        $weekday = $d->format('w');
        $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
        $d->modify("-$diff day");
        if ($mode == 1) {
            $filterBy = "`localAmount`";
        } else {
            $filterBy = "1";
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        SELECT ";
            $strInside = null;
            for ($i = 0; $i < 8; $i++) {

                $strInside .= "IFNULL(SUM(IF(`generalLedgerDate` like '" . $d->format(
                                'Y-m-d'
                        ) . "%'," . $filterBy . ",0) ),0) AS `" . $i . "`,";
                $d->modify('+1 day');
            }

            $sql .= substr($strInside, 0, -1);
            $sql .= "
		FROM    `generalledger`
        WHERE   `companyId`='" . $this->getCompanyId() . "'";
            if ($chartOfAccountTypeCode) {
                $sql .= " AND `chartOfAccountTypeCode`	=	'" . $chartOfAccountTypeCode . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Monthly Chart Of Account Type
     * @param string $date
     * @param string $chartOfAccountTypeCode
     * @param null|int $mode 1-figure,2-total
     * @return mixed
     */
    function getCrossTabMonthlyChartOfAccountType($date, $chartOfAccountTypeCode, $mode = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);
        if ($mode == 1) {
            $filterBy = "`localAmount`";
        } else {
            $filterBy = "1";
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        SELECT  IFNULL(SUM(IF(month(`generalLedgerDate`) = 1," . $filterBy . ",0)),0)  as `jan`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 2," . $filterBy . ",0)),0) as `feb`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 3," . $filterBy . ",0)),0) as `mac`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 4," . $filterBy . ",0)),0) as `apr`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 5," . $filterBy . ",0)),0) as `may`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 6," . $filterBy . ",0)),0) as `jun`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 7," . $filterBy . ",0)),0) as `jul`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 8," . $filterBy . ",0)),0) as `aug`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 9," . $filterBy . ",0)),0) as `sep`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 10," . $filterBy . ",0)),0) as `oct`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 11," . $filterBy . ",0)),0) as `nov`,
                IFNULL(SUM(IF(month(`generalLedgerDate`) = 12," . $filterBy . ",0)),0) as `dec`
        FROM    `generalledger`
        WHERE   YEAR(`generalLedgerDate`) = '" . $this->getYear() . "'
		AND		`companyId`='" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        if ($chartOfAccountTypeCode) {
            $sql .= " AND `chartOfAccountTypeCode`	=	'" . $chartOfAccountTypeCode . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Yearly All Chart Of Account Category
     * @param string $date
     * @param int $type 1->Balance Sheet . 2->Profit And Loss
     * @param null|int $mode 1-figure,2-total
     * @return mixed
     */
    function getCrossTabYearlyChartOfAccountType($date, $type, $mode = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);
        if ($mode == 1) {
            $filterBy = "`localAmount`";
        } else {
            $filterBy = "1";
        }
        if ($this->getVendor() == self::MYSQL) {
            if ($type == 1) {
                $sql = "
        SELECT  IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'A'," . $filterBy . ",0)),0) as `capitalReturnEarning`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'B'," . $filterBy . ",0)),0) as `longTermLiabilities`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'C'," . $filterBy . ",0)),0) as  `otherLiabilities`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'D'," . $filterBy . ",0)),0) as  `fixedAssets`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'E'," . $filterBy . ",0)),0) as  `otherAssets`,
				IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'F'," . $filterBy . ",0)),0) as `currentAssets`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'G'," . $filterBy . ",0)),0) as `currentLiabilities`
        FROM    `generalledger`
        WHERE   `companyId`='" . $this->getCompanyId() . "'
		AND		YEAR(`generalLedgerDate`) =  '" . $this->getYear() . "'";
            } else {
                $sql = "
        SELECT  IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'H'," . $filterBy . ",0)),0) as `sales`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'I'," . $filterBy . ",0)),0) as `salesAdjustment`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'J'," . $filterBy . ",0)),0) as  `otherLiabilities`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'K'," . $filterBy . ",0)),0) as  `costOfGoodSolds`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'L'," . $filterBy . ",0)),0) as  `otherIncome`,
				IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'M'," . $filterBy . ",0)),0) as `expenses`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'P'," . $filterBy . ",0)),0) as `taxation`,
				IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'S'," . $filterBy . ",0)),0) as `ordinary`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'T'," . $filterBy . ",0)),0) as `appropriation`
        FROM    `generalledger`
        WHERE   `companyId`='" . $this->getCompanyId() . "'
		AND		YEAR(`generalLedgerDate`) =  '" . $this->getYear() . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Date Range All Chart Of Account Category
     * @param string $dateStart
     * @param string $dateEnd
     * @param int $type 1->Balance Sheet ,2->Profit And loss
     * @param null|int $mode 1-figure,2-total
     * @return mixed
     */
    function getCrossTabRangeAllChartOfAccountType($dateStart, $dateEnd, $type, $mode = null) {
        $sql = null;
        $row = array();
        $dateStartArray = explode("-", $dateStart);

        $dayStart = $dateStartArray[0];
        $monthStart = $dateStartArray[1];
        $yearStart = $dateStartArray[2];

        $dateEndArray = explode("-", $dateEnd);

        $dayEnd = $dateEndArray[0];
        $monthEnd = $dateEndArray[1];
        $yearEnd = $dateEndArray[2];
        if ($mode == 1) {
            $filterBy = "`localAmount`";
        } else {
            $filterBy = "1";
        }
        if ($this->getVendor() == self::MYSQL) {
            if ($type == 1) {
                $sql = "
			SELECT   IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'A'," . $filterBy . ",0)),0) as `capitalReturnEarning`,
					IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'B'," . $filterBy . ",0)),0) as `longTermLiabilities`,
					IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'C'," . $filterBy . ",0)),0) as  `otherLiabilities`,
					IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'D'," . $filterBy . ",0)),0) as  `fixedAssets`,
					IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'E'," . $filterBy . ",0)),0) as  `otherAssets`,
					IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'F'," . $filterBy . ",0)),0) as `currentAssets`,
					IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'G'," . $filterBy . ",0)),0) as `currentLiabilities`
			FROM    `generalledger`
			WHERE   `companyId`='" . $this->getCompanyId() . "'
			AND		(`generalLedgerDate` BETWEEN '" . $yearStart . "-" . $monthStart . "-" . $dayStart . "'
			AND 		'" . $yearEnd . "-" . $monthEnd . "-" . $dayEnd . "');";
            } else {
                $sql = "
			   SELECT  IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'H'," . $filterBy . ",0)),0) as `sales`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'I'," . $filterBy . ",0)),0) as `salesAdjustment`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'J'," . $filterBy . ",0)),0) as  `otherLiabilities`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'K'," . $filterBy . ",0)),0) as  `costOfGoodSolds`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'L'," . $filterBy . ",0)),0) as  `otherIncome`,
				IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'M'," . $filterBy . ",0)),0) as `expenses`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'P'," . $filterBy . ",0)),0) as `taxation`,
				IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'S'," . $filterBy . ",0)),0) as `ordinary`,
                IFNULL(SUM(IF(`chartOfAccountTypeCode`	=	'T'," . $filterBy . ",0)),0) as `appropriation`
			FROM    `generalledger`
			WHERE   `companyId`='" . $this->getCompanyId() . "'
			AND		(`generalLedgerDate` BETWEEN '" . $yearStart . "-" . $monthStart . "-" . $dayStart . "'
			AND 		'" . $yearEnd . "-" . $monthEnd . "-" . $dayEnd . "');";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Set Week
     * @return string
     */
    function getWeek() {
        return $this->week;
    }

    /**
     *
     * @param string $value
     */
    function setWeek($value) {
        $this->week = $value;
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