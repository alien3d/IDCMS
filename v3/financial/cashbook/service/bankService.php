<?php

namespace Core\Financial\Cashbook\Bank\Service;

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
 * Class BankService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\Cashbook\Bank\Service
 * @subpackage Cashbook
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class BankService extends ConfigClass {

    /**
     * Asset->Balance Sheet Item
     */
    const ASSET = 'A';

    /**
     * Asset->Balance Sheet Item (SAGA Only)
     */
    const SAGA_ASSET = 'A00000';

    /**
     * Liability->Balance Sheet Item
     */
    const LIABILITY = 'I';

    /**
     * Liability->Balance Sheet Item(SAGA Only)
     */
    const SAGA_LIABILITY = 'L00000';

    /**
     * Equity->Balance Sheet Item
     */
    const EQUITY = 'OE';

    /**
     * Equity->Balance Sheet Item(SAGA only)
     */
    const SAGA_EQUITY = 'E00000';

    /**
     * Income->Profit And Loss
     */
    const INCOME = 'I';

    /**
     * Income->Profit And Loss
     */
    const SAGA_INCOME = 'B00000';

    /**
     * Expenses->Profit And Loss
     */
    const EXPENSES = 'E';

    /**
     * Expenses->Profit And Loss(SAGA only)
     */
    const SAGA_EXPENSES = 'H00000';

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
     * Return Chart Of Account.Filter Liability Account. Deposit Account
     * @return array|string
     */
    public function getChartOfAccount() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT		`chartofaccount`.`chartOfAccountId`,
							`chartofaccount`.`chartOfAccountNumber`,
							`chartofaccount`.`chartOfAccountTitle`,
							`chartofaccounttype`.`chartOfAccountTypeDescription`
			FROM        `chartofaccount`
			JOIN        `chartofaccounttype`
			USING       (`companyId`,`chartOfAccountCategoryId`,`chartOfAccountTypeId`)
			JOIN        `chartofaccountcategory`
			USING       (`companyId`,`chartOfAccountCategoryId`)
			WHERE       `chartofaccount`.`isActive`  					=   1
			AND            `chartofaccount`.`companyId` 					=   '" . $this->getCompanyId() . "'
			AND		 	  `chartofaccountcategory`.`chartOfAccountCategoryCode`	=	'" . self::INCOME . "'
			ORDER BY    `chartofaccounttype`.`chartOfAccountTypeId`,
			`chartofaccount`.`chartOfAccountNumber`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      [chartOfAccount].[chartOfAccountId],
							[chartOfAccount].[chartOfAccountNumber],
							[chartOfAccount].[chartOfAccountTitle],
							[chartOfAccountType].[chartOfAccountTypeDescription]
			FROM        [chartOfAccount]

			JOIN			[chartOfAccountCategory]
			ON          	[chartOfAccount].[companyId]   				= 	[chartOfAccountType].[companyId]
			AND         	[chartOfAccount].[chartOfAccountCategoryId]   		= 	[chartOfAccountType].[chartOfAccountCategoryId]

			JOIN			[chartOfAccountType]
			ON          	[chartOfAccount].[companyId]   				= 	[chartOfAccountType].[companyId]
			AND         	[chartOfAccount].[chartOfAccountTypeId]   		= 	[chartOfAccountType].[chartOfAccountTypeId]
			AND         	[chartOfAccount].[chartOfAccountCategoryId]   		= 	[chartOfAccountType].[chartOfAccountCategoryId]

			WHERE       [chartOfAccount].[isActive]  					=   1
			AND         [chartOfAccount].[companyId] 					=   '" . $this->getCompanyId() . "'
			AND		 [chartOfAccount].[chartOfAccountCategoryCode]	=	'" . self::INCOME . "'
			ORDER BY    [chartOfAccount].[chartOfAccountNumber]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
				SELECT      CHARTOFACCOUNTID               AS  \"chartOfAccountId\",
				CHARTOFACCOUNTNUMBER           AS  \"chartOfAccountNumber\",
				CHARTOFACCOUNTTITLE            AS  \"chartOfAccountTitle\",
				CHARTOFACCOUNTTYPEDESCRIPTION  AS  \"chartOfAccountTypeDescription\"
				FROM        CHARTOFACCOUNT

				JOIN        CHARTOFACCOUNTCATEGORY
				ON          CHARTOFACCOUNT.COMPANYID               	=   CHARTOFACCOUNTTYPE.COMPANYID
				AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID    	=   CHARTOFACCOUNTTYPE.CHARTOFACCOUNTCATEGORYID

				JOIN        CHARTOFACCOUNTTYPE
				ON          CHARTOFACCOUNT.COMPANYID               	=   CHARTOFACCOUNTTYPE.COMPANYID
				AND         CHARTOFACCOUNT.CHARTOFACCOUNTTYPEID    	=   CHARTOFACCOUNTTYPE.CHARTOFACCOUNTTYPEID
				AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID    	=   CHARTOFACCOUNTTYPE.CHARTOFACCOUNTCATEGORYID

				WHERE       CHARTOFACCOUNT.ISACTIVE                	=   1
				AND         CHARTOFACCOUNT.COMPANYID               	=   '" . $this->getCompanyId() . "'
				AND		 CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYCODE	=	'" . self::INCOME . "'
				ORDER BY    CHARTOFACCOUNT.CHARTOFACCOUNTNUMBER";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['chartOfAccountId'] . "'>" . $row['chartOfAccountNumber'] . " - "
                            . $row['chartOfAccountTitle'] . "</option>";
                } else {
                    if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
                    }
                }
                $d++;
            }
            unset($d);
        }
        if ($this->getServiceOutput() == 'option') {
            if (strlen($str) > 0) {
                $str = "<option value=''>" . $this->t['pleaseSelectTextLabel'] . "</option>" . $str;
            } else {
                $str = "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
            }
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => true, "message" => "complete", "data" => $str));
            exit();
        } else {
            if ($this->getServiceOutput() == 'html') {
                return $items;
            }
        }
        // fake return
        return $items;
    }

    /**
     * Return ChartOfAccount Default Value
     * @return int
     */
    public function getChartOfAccountDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $chartOfAccountId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `chartOfAccountId`
         FROM        	`chartofaccount`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [chartOfAccountId],
         FROM        [chartOfAccount]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      CHARTOFACCOUNTID AS \"chartOfAccountId\",
         FROM        CHARTOFACCOUNT
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         AND    	  ISDEFAULT	  =	   1
         AND 		  ROWNUM	  =	   1";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $chartOfAccountId = $row['chartOfAccountId'];
        }
        return $chartOfAccountId;
    }

    /**
     * Return Incoming Money From Bank via time/hour Cross Tab
     * @param string $date Transaction Date
     * @param null|int $bankId Bank Primary Key
     * @return array|mixed
     * @throws \Exception
     */
    function getBankInFlowTime($date, $bankId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear())));
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  ";
            $strInside = null;
            $hour = null;
            while ($hour++ < 24) {
                $strInside .= "
                IFNULL(SUM(IF(`cashBookDate`  like '" . $this->getYear() . "-" . $this->changeZero($this->getMonth()) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero($hour) . "%',1,0)) ,0) AS `" . $hour . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    `cashbookledger`
            JOIN    `bank`
            ON      `cashbookledger`.`companyId` = `bank`.`companyId`
            AND     `cashbookledger`.`bankId`   =   `bank`.`bankId`
            JOIN    `chartofaccount`
            ON      `cashbookledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `cashbookledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId`     `
            WHERE   `cashbookledger`.`companyId`	=	'" . $this->getCompanyId() . "'";
            if ($bankId) {
                $sql.=" AND `cashbookledger`.`bankId` = '" . $bankId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  ";
            $strInside = null;
            $hour = null;
            while ($hour++ < 24) {
                $strInside .= "
                IFNULL(SUM(IF([cashBookDate]  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                                $hour
                        ) . "%',1,0)) ,0) AS `" . $hour . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    [cashBookLedger]
			JOIN	[bank]
			ON      [cashBookLedger].[companyId]        =   [bank].[companyId]
			AND     [cashBookLedger].[bankId]           =   [bank].[bankId]
			JOIN    [chartOfAccount] 
			ON      [cashBookLedger].[companyId]        =   [chartOfAccount].[companyId]
			AND     [cashBookLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
			WHERE   [cashBookLedger].[companyId]	=	'" . $this->getCompanyId() . "'";
            if ($bankId) {
                $sql.=" AND [cashBookLedger].[bankId] = '" . $bankId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  ";
            $strInside = null;
            $hour = null;
            while ($hour++ < 24) {
                $strInside .= "IFNULL(SUM(IF(CASHBOOKDATE  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                                $hour
                        ) . "%',1,0)) ,0) AS `" . $hour . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    CASHBOOKLEDGER
                        JOIN    BANK
            ON      CASHBOOKLEDGER.COMPANYID        =   BANK.COMPANYID
            AND     CASHBOOKLEDGER.BANKID           =   BANK.BANKID
            JOIN    CHARTOFACCOUNT 
            ON      CASHBOOKLEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     CASHBOOKLEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
			WHERE   CASHBOOKLEDGER.COMPANYID	=	'" . $this->getCompanyId() . "'";
            if ($bankId) {
                $sql.=" AND BANKID = '" . $bankId . "'";
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
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
     * Return Outgoing Money From Bank via time/hour Cross Tab
     * @param string $date Transaction Date
     * @param null|int $bankId Bank Primary Key
     * @return array|mixed
     * @throws \Exception
     */
    function getBankOutFlowTime($date, $bankId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT  ";
            $strInside = null;
            $hour = null;
            while ($hour++ < 24) {
                $strInside .= "IFNULL(SUM(IF(`cashBookDate`  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                                $hour
                        ) . "%',1,0)) ,0) AS `" . $hour . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    `cashbookledger`
                            JOIN    `bank`
            ON      `cashbookledger`.`companyId` = `bank`.`companyId`
            AND     `cashbookledger`.`bankId`   =   `bank`.`bankId`
            JOIN    `chartofaccount`
            ON      `cashbookledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `cashbookledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId` 
			WHERE   `cashbookledger`.`companyId`	=	'" . $this->getCompanyId() . "'";
            if ($bankId) {
                $sql.=" AND `cashbookledger`.`bankId` = '" . $bankId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT  ";
            $strInside = null;
            $hour = null;
            while ($hour++ < 24) {
                $strInside .= "IFNULL(SUM(IF([cashBookDate]  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                                $hour
                        ) . "%',1,0)) ,0) AS `" . $hour . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    [cashBookLedger]
			JOIN	[bank]
			ON      [cashBookLedger].[companyId]        =   [bank].[companyId]
			AND     [cashBookLedger].[bankId]           =   [bank].[bankId]
			JOIN    [chartOfAccount] 
			ON      [cashBookLedger].[companyId]        =   [chartOfAccount].[companyId]
			AND     [cashBookLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
			WHERE   [cashBookLedger].[companyId]	=	'" . $this->getCompanyId() . "'";
            if ($bankId) {
                $sql.=" AND [cashBookLedger].[bankId] = '" . $bankId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT  ";
            $strInside = null;
            $hour = null;
            while ($hour++ < 24) {
                $strInside .= "IFNULL(SUM(IF(CASHBOOKDATE  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                                $hour
                        ) . "%',1,0)) ,0) AS `" . $hour . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    CASHBOOKLEDGER
                        JOIN    BANK
            ON      CASHBOOKLEDGER.COMPANYID        =   BANK.COMPANYID
            AND     CASHBOOKLEDGER.BANKID           =   BANK.BANKID
            JOIN    CHARTOFACCOUNT 
            ON      CASHBOOKLEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     CASHBOOKLEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
			WHERE   CASHBOOKLEDGER.COMPANYID	=	'" . $this->getCompanyId() . "'";
            if ($bankId) {
                $sql.=" AND BANKID = '" . $bankId . "'";
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
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
     * Return Money Bank via time/hour Cross Tab
     * @param string $date Transaction Date
     * @param null|int $bankId Bank Primary Key
     * @return array|mixed
     * @throws \Exception
     */
    function getBankTime($date, $bankId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT  ";
            $strInside = null;
            $hour = null;
            while ($hour++ < 24) {
                $strInside .= "IFNULL(SUM(IF(`cashBookDate`  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                                $hour
                        ) . "%',1,0)) ,0) AS `" . $hour . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    `cashbookledger`
                            JOIN    `bank`
            ON      `cashbookledger`.`companyId` = `bank`.`companyId`
            AND     `cashbookledger`.`bankId`   =   `bank`.`bankId`
            JOIN    `chartofaccount`
            ON      `cashbookledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `cashbookledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId` 
			WHERE   `cashbookledger`.`companyId`	=	'" . $this->getCompanyId() . "'";
            if ($bankId) {
                $sql.=" AND `cashbookledger`.`bankId` = '" . $bankId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT  ";
            $strInside = null;
            $hour = null;
            while ($hour++ < 24) {
                $strInside .= "IFNULL(SUM(IF([cashBookDate]  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                                $hour
                        ) . "%',1,0)) ,0) AS `" . $hour . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    [cashBookLedger]
			JOIN	[bank]
			ON      [cashBookLedger].[companyId]        =   [bank].[companyId]
			AND     [cashBookLedger].[bankId]           =   [bank].[bankId]
			JOIN    [chartOfAccount] 
			ON      [cashBookLedger].[companyId]        =   [chartOfAccount].[companyId]
			AND     [cashBookLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
			WHERE   [cashBookLedger].[companyId]	=	'" . $this->getCompanyId() . "'";
            if ($bankId) {
                $sql.=" AND [cashBookLedger].[bankId] = '" . $bankId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT  ";
            $strInside = null;
            $hour = null;
            while ($hour++ < 24) {
                $strInside .= "IFNULL(SUM(IF(CASHBOOKDATE  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                                $hour
                        ) . "%',1,0)) ,0) AS `" . $hour . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    CASHBOOKLEDGER
			JOIN    BANK
            ON      CASHBOOKLEDGER.COMPANYID        =   BANK.COMPANYID
            AND     CASHBOOKLEDGER.BANKID           =   BANK.BANKID
            JOIN    CHARTOFACCOUNT 
            ON      CASHBOOKLEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     CASHBOOKLEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
			WHERE   CASHBOOKLEDGER.COMPANYID	=	'" . $this->getCompanyId() . "'";
            if ($bankId) {
                $sql.=" AND BANKID = '" . $bankId . "'";
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
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
     * Return Incoming Money From Bank via weekly Cross Tab
     * @param string $date Transaction Date
     * @param null|int $bankId Bank Primary Key
     * @return array|mixed
     * @throws \Exception
     */
    function getBankInFlowWeekly($date, $bankId = null) {
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
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT ";
            $strInside = null;
            for ($i = 0; $i < 8; $i++) {

                $strInside .= "IFNULL(SUM(IF(`cashBookDate` like '" . $d->format(
                                'Y-m-d'
                        ) . "%',1,0) ) ,0) AS `" . $i . "`,";
                $d->modify('+1 day');
            }

            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    `cashbookledger`
			    JOIN    `bank`
            ON      `cashbookledger`.`companyId` = `bank`.`companyId`
            AND     `cashbookledger`.`bankId`   =   `bank`.`bankId`
            JOIN    `chartofaccount`
            ON      `cashbookledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `cashbookledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId` 
			WHERE   `cashbookledger`.`companyId`	=	'" . $this->getCompanyId() . "'
			AND	    `cashbookledger`.`cashBookAmount` < 0";
            if ($bankId) {
                $sql.=" AND `cashbookledger`.`bankId`	=	'" . $bankId . "'";
            }
        } else if ($this->getVendor() == self ::MSSQL) {
            $sql = "
			SELECT ";
            $strInside = null;
            for ($i = 0; $i < 8; $i++) {

                $strInside .= "IFNULL(SUM(IF([cashBookDate] like '" . $d->format(
                                'Y-m-d'
                        ) . "%',1,0) ) ,0) AS `" . $i . "`,";
                $d->modify('+1 day');
            }

            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    [cashBookLedger]
			JOIN	[bank]
			ON      [cashBookLedger].[companyId]        =   [bank].[companyId]
			AND     [cashBookLedger].[bankId]           =   [bank].[bankId]
			JOIN    [chartOfAccount] 
			ON      [cashBookLedger].[companyId]        =   [chartOfAccount].[companyId]
			AND     [cashBookLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
			WHERE   [cashBookLedger].[companyId]	=	'" . $this->getCompanyId() . "'
			AND	    [cashBookLedger].[cashBookAmount] < 0";
            if ($bankId) {
                $sql.=" AND [cashBookLedger].[bankId]	=	'" . $bankId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT ";
            $strInside = null;
            for ($i = 0; $i < 8; $i++) {

                $strInside .= "IFNULL(SUM(IF(CASHBOOKDATE like '" . $d->format(
                                'Y-m-d'
                        ) . "%',1,0) ) ,0) AS `" . $i . "`,";
                $d->modify('+1 day');
            }

            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    CASHBOOKLEDGER
			JOIN    BANK
            ON      CASHBOOKLEDGER.COMPANYID        =   BANK.COMPANYID
            AND     CASHBOOKLEDGER.BANKID           =   BANK.BANKID
            JOIN    CHARTOFACCOUNT 
            ON      CASHBOOKLEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     CASHBOOKLEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
			WHERE   CASHBOOKLEDGER.COMPANYID	=	'" . $this->getCompanyId() . "'
			AND	    CASHBOOKLEDGER.CASHBOOKAMOUNT < 0";
            if ($bankId) {
                $sql.=" AND CASHBOOKLEDGER.BANKID	=	'" . $bankId . "'";
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
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
     * Return Outgoing Money From Bank via weekly Cross Tab
     * @param string $date Transaction Date
     * @param null|int $bankId Bank Primary Key
     * @return array|mixed
     * @throws \Exception
     */
    function getBankOutFlowWeekly($date, $bankId = null) {
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
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT ";
            $strInside = null;
            for ($i = 0; $i < 8; $i++) {

                $strInside .= "IFNULL(SUM(IF(`cashBookDate` like '" . $d->format(
                                'Y-m-d'
                        ) . "%',1,0) ) ,0) AS `" . $i . "`,";
                $d->modify('+1 day');
            }

            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    `cashbookledger`
			    JOIN    `bank`
            ON      `cashbookledger`.`companyId` = `bank`.`companyId`
            AND     `cashbookledger`.`bankId`   =   `bank`.`bankId`
            JOIN    `chartofaccount`
            ON      `cashbookledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `cashbookledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId` 
			WHERE  	`cashbookledger`.`companyId`	=	'" . $this->getCompanyId() . "'
			AND	    `cashbookledger`.`cashBookAmount` > 0";
            if ($bankId) {
                $sql.=" AND `cashbookledger`.`bankId`	=	'" . $bankId . "'";
            }
        } else if ($this->getVendor() == self ::MSSQL) {
            $sql = "
			SELECT ";
            $strInside = null;
            for ($i = 0; $i < 8; $i++) {

                $strInside .= "IFNULL(SUM(IF([cashBookDate] like '" . $d->format(
                                'Y-m-d'
                        ) . "%',1,0) ) ,0) AS `" . $i . "`,";
                $d->modify('+1 day');
            }

            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    [cashBookLedger]
			JOIN	[bank]
			ON      [cashBookLedger].[companyId]        =   [bank].[companyId]
			AND     [cashBookLedger].[bankId]           =   [bank].[bankId]
			JOIN    [chartOfAccount] 
			ON      [cashBookLedger].[companyId]        =   [chartOfAccount].[companyId]
			AND     [cashBookLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
			WHERE  	[cashBookLedger].[companyId]	=	'" . $this->getCompanyId() . "'
			AND	    [cashBookLedger].[cashBookAmount] > 0";
            if ($bankId) {
                $sql.=" AND [cashBookLedger].[bankId]	=	'" . $bankId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT ";
            $strInside = null;
            for ($i = 0; $i < 8; $i++) {

                $strInside .= "IFNULL(SUM(IF(CASHBOOKDATE like '" . $d->format(
                                'Y-m-d'
                        ) . "%',1,0) ) ,0) AS `" . $i . "`,";
                $d->modify('+1 day');
            }

            $sql .= substr($strInside, 0, -1);
            $sql .= "
            FROM    CASHBOOKLEDGER
            JOIN    BANK
            ON      CASHBOOKLEDGER.COMPANYID        =   BANK.COMPANYID
            AND     CASHBOOKLEDGER.BANKID           =   BANK.BANKID
            JOIN    CHARTOFACCOUNT 
            ON      CASHBOOKLEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     CASHBOOKLEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
            WHERE   CASHBOOKLEDGER.COMPANYID        =   '" . $this->getCompanyId() . "'
            AND	    CASHBOOKLEDGER.CASHBOOKAMOUNT > 0";
            if ($bankId) {
                $sql.=" AND CASHBOOKLEDGER.BANKID	=	'" . $bankId . "'";
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
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
     * Return Money From Bank via weekly Cross Tab
     * @param string $date Transaction Date
     * @param null|int $bankId Bank Primary Key
     * @return array|mixed
     * @throws \Exception
     */
    function getBankWeekly($date, $bankId = null) {
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
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT ";
            $strInside = null;
            for ($i = 0; $i < 8; $i++) {

                $strInside .= "IFNULL(SUM(IF(`cashBookDate` like '" . $d->format(
                                'Y-m-d'
                        ) . "%',1,0) ) ,0) AS `" . $i . "`,";
                $d->modify('+1 day');
            }

            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    `cashbookledger`
			    JOIN    `bank`
            ON      `cashbookledger`.`companyId` = `bank`.`companyId`
            AND     `cashbookledger`.`bankId`   =   `bank`.`bankId`
            JOIN    `chartofaccount`
            ON      `cashbookledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `cashbookledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId` 
			WHERE   `cashbookledger`.`companyId`	=	'" . $this->getCompanyId() . "'
			";
            if ($bankId) {
                $sql.=" AND `cashbookledger`.`bankId`	=	'" . $bankId . "'";
            }
        } else if ($this->getVendor() == self ::MSSQL) {
            $sql = "
			SELECT ";
            $strInside = null;
            for ($i = 0; $i < 8; $i++) {

                $strInside .= "IFNULL(SUM(IF(`cashBookDate` like '" . $d->format(
                                'Y-m-d'
                        ) . "%',1,0) ) ,0) AS `" . $i . "`,";
                $d->modify('+1 day');
            }

            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    [cashBookLedger]
			JOIN	[bank]
			ON      [cashBookLedger].[companyId]        =   [bank].[companyId]
			AND     [cashBookLedger].[bankId]           =   [bank].[bankId]
			JOIN    [chartOfAccount] 
			ON      [cashBookLedger].[companyId]        =   [chartOfAccount].[companyId]
			AND     [cashBookLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
			WHERE   [cashBookLedger].[companyId]	=	'" . $this->getCompanyId() . "'
			";
            if ($bankId) {
                $sql.=" AND [cashBookLedger].[bankId]	=	'" . $bankId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT ";
            $strInside = null;
            for ($i = 0; $i < 8; $i++) {

                $strInside .= "IFNULL(SUM(IF(`cashBookDate` like '" . $d->format(
                                'Y-m-d'
                        ) . "%',1,0) ) ,0) AS `" . $i . "`,";
                $d->modify('+1 day');
            }

            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    `CASHBOOKLEDGER`
			JOIN	`BANK`
			USING	(`COMPANYID`,`BANKID`)
			WHERE   CASHBOOKLEDGER.COMPANYID	=	'" . $this->getCompanyId() . "'
			";
            if ($bankId) {
                $sql.=" AND CASHBOOKLEDGER.BANKID	=	'" . $bankId . "'";
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
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
     * Return Incoming Money From Bank via daily Cross Tab
     * @param string $date Transaction Date
     * @param null|int $bankId Bank Primary Key
     * @return array|mixed
     * @throws \Exception
     */
    function getBankInFlowDaily($date, $bankId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT  ";
            $strInside = null;
            for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
                $strInside .= "IFNULL(SUM(IF(`cashBookDate`  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($i) . "%',1,0)) ,0) AS `" . $i . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    `cashbookledger`
			    JOIN    `bank`
            ON      `cashbookledger`.`companyId` = `bank`.`companyId`
            AND     `cashbookledger`.`bankId`   =   `bank`.`bankId`
            JOIN    `chartofaccount`
            ON      `cashbookledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `cashbookledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId` 
			WHERE   `cashbookledger`.`companyId`	=	'" . $this->getCompanyId() . "'
			AND		MONTH(`cashBookDate`) = '" . $this->getMonth() . "'
			AND     YEAR(`cashBookDate`) = '" . $this->getYear() . "'
			AND		`cashbookledger`.`cashBookAmount` < 0 ;";
            if ($bankId) {
                $sql.=" AND `bankId`	=	'" . $bankId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT  ";
            $strInside = null;
            for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
                $strInside .= "IFNULL(SUM(IF([cashBookDate]  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($i) . "%',1,0)) ,0) AS `" . $i . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    [cashBookLedger]
			JOIN	[bank]
			ON      [cashBookLedger].[companyId]        =   [bank].[companyId]
			AND     [cashBookLedger].[bankId]           =   [bank].[bankId]
			JOIN    [chartOfAccount] 
			ON      [cashBookLedger].[companyId]        =   [chartOfAccount].[companyId]
			AND     [cashBookLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
			WHERE   [cashBookLedger].[companyId]	=	'" . $this->getCompanyId() . "'
			AND		MONTH([cashBookDate]) 			= 	'" . $this->getMonth() . "'
			AND     YEAR([cashBookDate]) 			= 	'" . $this->getYear() . "'
			AND		[cashBookLedger].[cashBookAmount] < 0 ;";
            if ($bankId) {
                $sql.=" AND [bankId]	=	'" . $bankId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT  ";
            $strInside = null;
            for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
                $strInside .= "IFNULL(SUM(IF(CASHBOOKDATE  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($i) . "%',1,0)) ,0) AS `" . $i . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    CASHBOOKLEDGER
			JOIN    BANK
            ON      CASHBOOKLEDGER.COMPANYID        =   BANK.COMPANYID
            AND     CASHBOOKLEDGER.BANKID           =   BANK.BANKID
            JOIN    CHARTOFACCOUNT 
            ON      CASHBOOKLEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     CASHBOOKLEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
			WHERE   CASHBOOKLEDGER.COMPANYID	=	'" . $this->getCompanyId() . "'
			AND		MONTH(CASHBOOKDATE) = '" . $this->getMonth() . "'
			AND     YEAR(CASHBOOKDATE) = '" . $this->getYear() . "'
			AND		CASHBOOKLEDGER.CASHBOOKAMOUNT < 0 ;";
            if ($bankId) {
                $sql.=" AND BANKID	=	'" . $bankId . "'";
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
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
     * Return Outgoing Money From Bank via daily Cross Tab
     * @param string $date Transaction Date
     * @param null|int $bankId Bank Primary Key
     * @return array|mixed
     * @throws \Exception
     */
    function getBankOutFlowDaily($date, $bankId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT  ";
            $strInside = null;
            for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
                $strInside .= "IFNULL(SUM(IF(`cashBookDate`  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($i) . "%',1,0)) ,0) AS `" . $i . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    `cashbookledger`
			    JOIN    `bank`
            ON      `cashbookledger`.`companyId` = `bank`.`companyId`
            AND     `cashbookledger`.`bankId`   =   `bank`.`bankId`
            JOIN    `chartofaccount`
            ON      `cashbookledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `cashbookledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId` 
			WHERE   `cashbookledger`.`companyId`	=	'" . $this->getCompanyId() . "'
			AND		MONTH(`cashBookDate`) = '" . $this->getMonth() . "'
			AND     YEAR(`cashBookDate`) = '" . $this->getYear() . "'
			AND		`cashbookledger`.`cashBookAmount` > 0 ;";
            if ($bankId) {
                $sql.=" AND `bankId`	=	'" . $bankId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT  ";
            $strInside = null;
            for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
                $strInside .= "IFNULL(SUM(IF([cashBookDate]  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($i) . "%',1,0)) ,0) AS `" . $i . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    [cashBookLedger]
			JOIN	[bank]
			ON      [cashBookLedger].[companyId]        =   [bank].[companyId]
			AND     [cashBookLedger].[bankId]           =   [bank].[bankId]
			JOIN    [chartOfAccount] 
			ON      [cashBookLedger].[companyId]        =   [chartOfAccount].[companyId]
			AND     [cashBookLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
			WHERE   [cashBookLedger].[companyId]	=	'" . $this->getCompanyId() . "'
			AND		MONTH([cashBookDate]) 			= 	'" . $this->getMonth() . "'
			AND     YEAR([cashBookDate]) 			= 	'" . $this->getYear() . "'
			AND		[cashBookLedger].[cashBookAmount] > 0 ;";
            if ($bankId) {
                $sql.=" AND [bankId]	=	'" . $bankId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT  ";
            $strInside = null;
            for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
                $strInside .= "IFNULL(SUM(IF(CASHBOOKDATE  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($i) . "%',1,0)) ,0) AS `" . $i . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    CASHBOOKLEDGER
			JOIN    BANK
            ON      CASHBOOKLEDGER.COMPANYID        =   BANK.COMPANYID
            AND     CASHBOOKLEDGER.BANKID           =   BANK.BANKID
            JOIN    CHARTOFACCOUNT 
            ON      CASHBOOKLEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     CASHBOOKLEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
			WHERE   CASHBOOKLEDGER.COMPANYID	=	'" . $this->getCompanyId() . "'
			AND		MONTH(CASHBOOKDATE) 		= 	'" . $this->getMonth() . "'
			AND     YEAR(CASHBOOKDATE) 			=	'" . $this->getYear() . "'
			AND		CASHBOOKLEDGER.CASHBOOKAMOUNT < 0 ;";
            if ($bankId) {
                $sql.=" AND BANKID	=	'" . $bankId . "'";
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
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
     * Return Money From Bank via daily Cross Tab
     * @param string $date Transaction Date
     * @param null|int $bankId Bank Primary Key
     * @return array|mixed
     * @throws \Exception
     */
    function getBankDaily($date, $bankId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT  ";
            $strInside = null;
            for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
                $strInside .= "IFNULL(SUM(IF(`cashBookDate`  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($i) . "%',1,0)) ,0) AS `" . $i . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    `cashbookledger`
			    JOIN    `bank`
            ON      `cashbookledger`.`companyId` = `bank`.`companyId`
            AND     `cashbookledger`.`bankId`   =   `bank`.`bankId`
            JOIN    `chartofaccount`
            ON      `cashbookledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `cashbookledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId` 
			WHERE   `cashbookledger`.`companyId`	=	'" . $this->getCompanyId() . "'
			AND		MONTH(`cashBookDate`) = '" . $this->getMonth() . "'
			AND     YEAR(`cashBookDate`) = '" . $this->getYear() . "';";
            if ($bankId) {
                $sql.=" AND `bankId`	=	'" . $bankId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT  ";
            $strInside = null;
            for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
                $strInside .= "IFNULL(SUM(IF([cashBookDate]  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($i) . "%',1,0)) ,0) AS `" . $i . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    [cashBookLedger]
			JOIN	[bank]
			ON      [cashBookLedger].[companyId]        =   [bank].[companyId]
			AND     [cashBookLedger].[bankId]           =   [bank].[bankId]
			JOIN    [chartOfAccount] 
			ON      [cashBookLedger].[companyId]        =   [chartOfAccount].[companyId]
			AND     [cashBookLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
			WHERE   [cashBookLedger].[companyId]	=	'" . $this->getCompanyId() . "'
			AND		MONTH([cashBookDate]) 			= 	'" . $this->getMonth() . "'
			AND     YEAR([cashBookDate]) 			= 	'" . $this->getYear() . "';";
            if ($bankId) {
                $sql.=" AND [bankId]	=	'" . $bankId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT  ";
            $strInside = null;
            for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
                $strInside .= "IFNULL(SUM(IF(CASHBOOKDATE  like '" . $this->getYear() . "-" . $this->changeZero(
                                $this->getMonth()
                        ) . "-" . $this->changeZero($i) . "%',1,0)) ,0) AS `" . $i . "`,";
            }
            $sql .= substr($strInside, 0, -1);
            $sql .= "
			FROM    CASHBOOKLEDGER
			JOIN    BANK
            ON      CASHBOOKLEDGER.COMPANYID        =   BANK.COMPANYID
            AND     CASHBOOKLEDGER.BANKID           =   BANK.BANKID
            JOIN    CHARTOFACCOUNT 
            ON      CASHBOOKLEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     CASHBOOKLEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
			WHERE   CASHBOOKLEDGER.COMPANYID	=	'" . $this->getCompanyId() . "'
			AND		MONTH(CASHBOOKDATE) = '" . $this->getMonth() . "'
			AND     YEAR(CASHBOOKDATE) = '" . $this->getYear() . "';";
            if ($bankId) {
                $sql.=" AND BANKID	=	'" . $bankId . "'";
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
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
     * Return Incoming Money From Bank via monthly/period Cross Tab
     * @param string $date Transaction Date
     * @param null|int $bankId Bank Primary Key
     * @return array|mixed
     * @throws \Exception
     * @todo Futured should be based by financial period
     */
    function getBankInFlowMonthly($date, $bankId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT  IFNULL(SUM(IF(month(`cashBookDate`) = 1,1,0)),0) AS `jan`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 2,1,0)),0) AS `feb`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 3,1,0)),0) AS `mac`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 4,1,0)),0) AS `apr`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 5,1,0)),0) AS `may`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 6,1,0)),0) AS `jun`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 7,1,0)),0) AS `jul`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 8,1,0)),0) AS `aug`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 9,1,0)),0) as `sep`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 10,1,0)),0) as `oct`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 11,1,0)),0) AS `nov`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 12,1,0)),0) AS `dec`
			FROM    `cashbookledger`
			JOIN    `bank`
			ON      `cashbookledger`.`companyId` = `bank`.`companyId`
			AND     `cashbookledger`.`bankId`   =   `bank`.`bankId`
			JOIN    `chartofaccount`
			ON      `cashbookledger`.`companyId` = `chartofaccount`.`companyId`
			AND     `cashbookledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId` 
			WHERE   `cashbookledger`.`companyId`	=	'" . $this->getCompanyId() . "'
			AND		YEAR(`cashBookDate`) = '" . $this->getYear() . "'
			AND		`cashBookAmount` < 0";
            if ($bankId) {
                $sql.=" AND `cashbookledger`.`bankId`	=	'" . $bankId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT  IFNULL(SUM(IF(month([cashBookDate]) = 1,1,0)),0) AS [jan],
						IFNULL(SUM(IF(month([cashBookDate]) = 2,1,0)),0) AS [feb],
						IFNULL(SUM(IF(month([cashBookDate]) = 3,1,0)),0) AS [mac],
						IFNULL(SUM(IF(month([cashBookDate]) = 4,1,0)),0) AS [apr],
						IFNULL(SUM(IF(month([cashBookDate]) = 5,1,0)),0) AS [may],
						IFNULL(SUM(IF(month([cashBookDate]) = 6,1,0)),0) AS [jun],
						IFNULL(SUM(IF(month([cashBookDate]) = 7,1,0)),0) AS [jul],
						IFNULL(SUM(IF(month([cashBookDate]) = 8,1,0)),0) AS [aug],
						IFNULL(SUM(IF(month([cashBookDate]) = 9,1,0)),0) as [sep],
						IFNULL(SUM(IF(month([cashBookDate]) = 10,1,0)),0) as [oct],
						IFNULL(SUM(IF(month([cashBookDate]) = 11,1,0)),0) AS [nov],
						IFNULL(SUM(IF(month([cashBookDate]) = 12,1,0)),0) AS [dec]
			FROM    [cashBookLedger]
			JOIN	[bank]
			ON      [cashBookLedger].[companyId]        =   [bank].[companyId]
			AND     [cashBookLedger].[bankId]           =   [bank].[bankId]
			JOIN    [chartOfAccount] 
			ON      [cashBookLedger].[companyId]        =   [chartOfAccount].[companyId]
			AND     [cashBookLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
			WHERE   [cashBookLedger].[companyId]	=	'" . $this->getCompanyId() . "'
			AND		YEAR([cashBookDate]) = '" . $this->getYear() . "'
			AND		[cashBookAmount] < 0";
            if ($bankId) {
                $sql.=" AND [cashBookLedger].[bankId]	=	'" . $bankId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT  IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 1,1,0)),0) AS `JAN`,
						IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 2,1,0)),0) AS `FEB`,
						IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 3,1,0)),0) AS `MAC`,
						IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 4,1,0)),0) AS `APR`,
						IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 5,1,0)),0) AS `MAY`,
						IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 6,1,0)),0) AS `JUN`,
						IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 7,1,0)),0) AS `JUL`,
						IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 8,1,0)),0) AS `AUG`,
						IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 9,1,0)),0) AS `SEP`,
						IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 10,1,0)),0) AS `OCT`,
						IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 11,1,0)),0) AS `NOV`,
						IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 12,1,0)),0) AS `DEC`
			FROM    CASHBOOKLEDGER
			JOIN	`BANK`
			USING	(`COMPANYID`,`BANKID`)
			WHERE   CASHBOOKLEDGER.COMPANYID	=	'" . $this->getCompanyId() . "'
			AND		YEAR(CASHBOOKDATE) = '" . $this->getYear() . "'
			AND		CASHBOOKAMOUNT < 0";
            if ($bankId) {
                $sql.=" AND CASHBOOKLEDGER.BANKID	=	'" . $bankId . "'";
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
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
     * Return Outgoing Money From Bank via monthly/period Cross Tab
     * @param string $date Transaction Date
     * @param null|int $bankId Bank Primary Key
     * @return array|mixed
     * @throws \Exception
     */
    function getBankOutFlowMonthly($date, $bankId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT  IFNULL(SUM(IF(month(`cashBookDate`) = 1,1,0)),0) AS `jan`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 2,1,0)),0) AS `feb`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 3,1,0)),0) AS `mac`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 4,1,0)),0) AS `apr`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 5,1,0)),0) AS `may`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 6,1,0)),0) AS `jun`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 7,1,0)),0) AS `jul`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 8,1,0)),0) AS `aug`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 9,1,0)),0) as `sep`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 10,1,0)),0) as `oct`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 11,1,0)),0) AS `nov`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 12,1,0)),0) AS `dec`
			FROM    `cashbookledger`
			JOIN    `bank`
			ON      `cashbookledger`.`companyId` = `bank`.`companyId`
			AND     `cashbookledger`.`bankId`   =   `bank`.`bankId`
			JOIN    `chartofaccount`
			ON      `cashbookledger`.`companyId` = `chartofaccount`.`companyId`
			AND     `cashbookledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId` 
			WHERE   `cashbookledger`.`companyId`	=	'" . $this->getCompanyId() . "'
			AND		YEAR(`cashBookDate`) = '" . $this->getYear() . "'
			AND		`cashBookAmount` > 0 ";
            if ($bankId) {
                $sql.=" AND `cashbookledger`.`bankId`	=	'" . $bankId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT  IFNULL(SUM(IF(month([cashBookDate]) = 1,1,0)),0) AS [jan],
						IFNULL(SUM(IF(month([cashBookDate]) = 2,1,0)),0) AS [feb],
						IFNULL(SUM(IF(month([cashBookDate]) = 3,1,0)),0) AS [mac],
						IFNULL(SUM(IF(month([cashBookDate]) = 4,1,0)),0) AS [apr],
						IFNULL(SUM(IF(month([cashBookDate]) = 5,1,0)),0) AS [may],
						IFNULL(SUM(IF(month([cashBookDate]) = 6,1,0)),0) AS [jun],
						IFNULL(SUM(IF(month([cashBookDate]) = 7,1,0)),0) AS [jul],
						IFNULL(SUM(IF(month([cashBookDate]) = 8,1,0)),0) AS [aug],
						IFNULL(SUM(IF(month([cashBookDate]) = 9,1,0)),0) as [sep],
						IFNULL(SUM(IF(month([cashBookDate]) = 10,1,0)),0) as [oct],
						IFNULL(SUM(IF(month([cashBookDate]) = 11,1,0)),0) AS [nov],
						IFNULL(SUM(IF(month([cashBookDate]) = 12,1,0)),0) AS [dec]
			FROM    [cashBookLedger]
			JOIN	[bank]
			ON      [cashBookLedger].[companyId]        =   [bank].[companyId]
			AND     [cashBookLedger].[bankId]           =   [bank].[bankId]
			JOIN    [chartOfAccount] 
			ON      [cashBookLedger].[companyId]        =   [chartOfAccount].[companyId]
			AND     [cashBookLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
			WHERE   [cashBookLedger].[companyId]	=	'" . $this->getCompanyId() . "'
			AND		YEAR([cashBookDate]) = '" . $this->getYear() . "'
			AND		[cashBookAmount] > 0 ";
            if ($bankId) {
                $sql.=" AND [cashBookLedger].[bankId]	=	'" . $bankId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT  IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 1,1,0)),0) AS \"jan\",
						IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 2,1,0)),0) AS \"feb\",
						IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 3,1,0)),0) AS \"mac\",
						IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 4,1,0)),0) AS \"apr\",
						IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 5,1,0)),0) AS \"may\",
						IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 6,1,0)),0) AS \"jun\",
						IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 7,1,0)),0) AS \"jul\",
						IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 8,1,0)),0) AS \"aug\",
						IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 9,1,0)),0) AS \"sep\",
						IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 10,1,0)),0) AS \"oct\",
						IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 11,1,0)),0) AS \"nov\",
						IFNULL(SUM(IF(MONTH(CASHBOOKDATE) = 12,1,0)),0) AS \"dec\"
			FROM    CASHBOOKLEDGER
			JOIN    BANK
			ON      CASHBOOKLEDGER.COMPANYID        =   BANK.COMPANYID
			AND     CASHBOOKLEDGER.BANKID           =   BANK.BANKID
			JOIN    CHARTOFACCOUNT 
			ON      CASHBOOKLEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
			AND     CASHBOOKLEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
			WHERE   CASHBOOKLEDGER.COMPANYID	=	'" . $this->getCompanyId() . "'
			AND		YEAR(CASHBOOKDATE) = '" . $this->getYear() . "'
			AND		CASHBOOKAMOUNT > 0 ";
            if ($bankId) {
                $sql.=" AND CASHBOOKLEDGER.BANKID	=	'" . $bankId . "'";
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
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
     * Return Money From Bank via monthly/period Cross Tab
     * @param string $date Transaction Date
     * @param null|int $bankId Bank Primary Key
     * @return array|mixed
     * @throws \Exception
     */
    function getBankMonthly($date, $bankId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT  IFNULL(SUM(IF(month(`cashBookDate`) = 1,1,0)),0) AS `jan`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 2,1,0)),0) AS `feb`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 3,1,0)),0) AS `mac`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 4,1,0)),0) AS `apr`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 5,1,0)),0) AS `may`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 6,1,0)),0) AS `jun`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 7,1,0)),0) AS `jul`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 8,1,0)),0) AS `aug`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 9,1,0)),0) as `sep`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 10,1,0)),0) as `oct`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 11,1,0)),0) AS `nov`,
						IFNULL(SUM(IF(month(`cashBookDate`) = 12,1,0)),0) AS `dec`
			FROM    `cashbookledger`
			JOIN    `bank`
			ON      `cashbookledger`.`companyId` = `bank`.`companyId`
			AND     `cashbookledger`.`bankId`   =   `bank`.`bankId`
			JOIN    `chartofaccount`
			ON      `cashbookledger`.`companyId` = `chartofaccount`.`companyId`
			AND     `cashbookledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId` 
			WHERE   `cashbookledger`.`companyId`	=	'" . $this->getCompanyId() . "'
			AND		YEAR(`cashBookDate`) = '" . $this->getYear() . "'";
            if ($bankId) {
                $sql.=" AND `cashbookledger`.`bankId`	=	'" . $bankId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT  IFNULL(SUM(IF(month(`cashBookDate`) = 1,1,0)),0) AS [jan],
						IFNULL(SUM(IF(month(`cashBookDate`) = 2,1,0)),0) AS [feb],
						IFNULL(SUM(IF(month(`cashBookDate`) = 3,1,0)),0) AS [mac],
						IFNULL(SUM(IF(month(`cashBookDate`) = 4,1,0)),0) AS [apr],
						IFNULL(SUM(IF(month(`cashBookDate`) = 5,1,0)),0) AS [may],
						IFNULL(SUM(IF(month(`cashBookDate`) = 6,1,0)),0) AS [jun],
						IFNULL(SUM(IF(month(`cashBookDate`) = 7,1,0)),0) AS [jul],
						IFNULL(SUM(IF(month(`cashBookDate`) = 8,1,0)),0) AS [aug],
						IFNULL(SUM(IF(month(`cashBookDate`) = 9,1,0)),0) as [sep],
						IFNULL(SUM(IF(month(`cashBookDate`) = 10,1,0)),0) as [oct],
						IFNULL(SUM(IF(month(`cashBookDate`) = 11,1,0)),0) AS [nov],
						IFNULL(SUM(IF(month(`cashBookDate`) = 12,1,0)),0) AS [dec]
			FROM    [cashBookLedger]
			JOIN	[bank]
			ON      [cashBookLedger].[companyId]        =   [bank].[companyId]
			AND     [cashBookLedger].[bankId]           =   [bank].[bankId]
			JOIN    [chartOfAccount] 
			ON      [cashBookLedger].[companyId]        =   [chartOfAccount].[companyId]
			AND     [cashBookLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
			WHERE   [cashBookLedger].[companyId]	=	'" . $this->getCompanyId() . "'
			AND		YEAR([cashBookDate]) = '" . $this->getYear() . "'";
            if ($bankId) {
                $sql.=" AND [cashBookLedger].[bankId]	=	'" . $bankId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT IFNULL(SUM(IF(MONTH(`CASHBOOKDATE`) = 1,1,0)),0) AS `JAN`,
						IFNULL(SUM(IF(MONTH(`CASHBOOKDATE`) = 2,1,0)),0) AS `FEB`,
						IFNULL(SUM(IF(MONTH(`CASHBOOKDATE`) = 3,1,0)),0) AS `MAC`,
						IFNULL(SUM(IF(MONTH(`CASHBOOKDATE`) = 4,1,0)),0) AS `APR`,
						IFNULL(SUM(IF(MONTH(`CASHBOOKDATE`) = 5,1,0)),0) AS `MAY`,
						IFNULL(SUM(IF(MONTH(`CASHBOOKDATE`) = 6,1,0)),0) AS `JUN`,
						IFNULL(SUM(IF(MONTH(`CASHBOOKDATE`) = 7,1,0)),0) AS `JUL`,
						IFNULL(SUM(IF(MONTH(`CASHBOOKDATE`) = 8,1,0)),0) AS `AUG`,
						IFNULL(SUM(IF(MONTH(`CASHBOOKDATE`) = 9,1,0)),0) AS `SEP`,
						IFNULL(SUM(IF(MONTH(`CASHBOOKDATE`) = 10,1,0)),0) AS `OCT`,
						IFNULL(SUM(IF(MONTH(`CASHBOOKDATE`) = 11,1,0)),0) AS `NOV`,
						IFNULL(SUM(IF(MONTH(`CASHBOOKDATE`) = 12,1,0)),0) AS `DEC`
			FROM    CASHBOOKLEDGER
			JOIN    BANK
            ON      CASHBOOKLEDGER.COMPANYID        =   BANK.COMPANYID
            AND     CASHBOOKLEDGER.BANKID           =   BANK.BANKID
            JOIN    CHARTOFACCOUNT 
            ON      CASHBOOKLEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     CASHBOOKLEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
			WHERE   CASHBOOKLEDGER.COMPANYID	=	'" . $this->getCompanyId() . "'
			AND		YEAR(CASHBOOKDATE) = '" . $this->getYear() . "'";
            if ($bankId) {
                $sql.=" AND CASHBOOKLEDGER.BANKID	=	'" . $bankId . "'";
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
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
     * Return Incoming Money From Bank via yearly Cross Tab
     * @param string $date Transaction Date
     * @param null|int $bankId Bank Primary Key
     * @return array|mixed
     * @throws \Exception
     */
    function getBankInFlowYearly($date, $bankId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT  ABS(SUM(`cashBookAmount`)) as `totalCashBookAmount`
			FROM    `cashbookledger`
			JOIN    `bank`
			ON      `cashbookledger`.`companyId` = `bank`.`companyId`
			AND     `cashbookledger`.`bankId`   =   `bank`.`bankId`
			JOIN    `chartofaccount`
			ON      `cashbookledger`.`companyId` = `chartofaccount`.`companyId`
			AND     `cashbookledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId` 
			WHERE   `cashbookledger`.`companyId`	='" . $this->getCompanyId() . "'
			AND		`cashBookAmount` < 0 
			AND		`cashbookledger`.`cashBookAmount` > 0
			AND		YEAR(`cashbookledger`.`cashBookDate`) =  '" . $this->getYear() . "'";
            if ($bankId) {
                $sql.="  AND    `cashbookledger`.`bankId`='" . $bankId . "'";
            }
            $sql.="
            AND     YEAR(`cashbookledger`.`cashBookDate`)
            BETWEEN YEAR(DATE_SUB(NOW(), INTERVAL 3 YEAR)) AND YEAR(NOW())
            ORDER 	BY `cashbookledger`.`bankId`
            GROUP	BY (`cashbookledger`.`bankId`)";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT  ABS(SUM([cashBookAmount])) as [totalCashBookAmount]
			FROM    [cashBookLedger]
			JOIN	[bank]
			ON      [cashBookLedger].[companyId]        =   [bank].[companyId]
			AND     [cashBookLedger].[bankId]           =   [bank].[bankId]
			JOIN    [chartOfAccount] 
			ON      [cashBookLedger].[companyId]        =   [chartOfAccount].[companyId]
			AND     [cashBookLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
			WHERE   [companyId]	='" . $this->getCompanyId() . "'
			AND		[cashBookAmount] < 0 
			AND		YEAR([cashBookDate]) =  '" . $this->getYear() . "'";
            if ($bankId) {
                $sql.="
                AND    [cashBookLedger].[bankId]    =   '" . $bankId . "'";
            }
            $sql.="
            AND     YEAR([cashBookDate])
            BETWEEN YEAR(DATE_SUB(NOW(), INTERVAL 3 YEAR)) AND YEAR(NOW())
            ORDER 	BY [bankId]
            GROUP	BY ([bankId])";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  ABS(SUM(CASHBOOKAMOUNT)) AS \"totalCashBookAmount\"
            FROM    CASHBOOKLEDGER
            JOIN    BANK
            ON      CASHBOOKLEDGER.COMPANYID        =   BANK.COMPANYID
            AND     CASHBOOKLEDGER.BANKID           =   BANK.BANKID
            JOIN    CHARTOFACCOUNT 
            ON      CASHBOOKLEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     CASHBOOKLEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
            WHERE   COMPANYID	='" . $this->getCompanyId() . "'
            AND		CASHBOOKAMOUNT < 0 ";
            if ($bankId) {
                $sql.="  AND    CASHBOOKLEDGER.BANKID='" . $bankId . "'";
            }
            $sql.="AND		YEAR(CASHBOOKDATE) =  '" . $this->getYear() . "'
            AND     YEAR(CASHBOOKDATE)
            BETWEEN YEAR(DATE_SUB(NOW(), INTERVAL 3 YEAR)) AND YEAR(NOW())
            ORDER 	BY  BANKID
            GROUP	BY (BANKID)";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
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
     * Return Outgoing Money From Bank via time/hour Cross Tab
     * @param string $date Transaction Date
     * @param null|int $bankId Bank Primary Key
     * @return array|mixed
     * @throws \Exception
     */
    function getBankOutFlowYearly($date, $bankId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT  ABS(SUM(`cashBookAmount`)) as `totalCashBookAmount`
			FROM    `cashbookledger`
			JOIN    `bank`
			ON      `cashbookledger`.`companyId` = `bank`.`companyId`
			AND     `cashbookledger`.`bankId`   =   `bank`.`bankId`
			JOIN    `chartofaccount`
			ON      `cashbookledger`.`companyId` = `chartofaccount`.`companyId`
			AND     `cashbookledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId` 
			WHERE   `cashbookledger`.`companyId`	='" . $this->getCompanyId() . "'
			AND		`cashBookAmount` > 0 
			AND		`cashbookledger`.`cashBookAmount` > 0
			AND		YEAR(`cashbookledger`.`cashBookDate`) =  '" . $this->getYear() . "'";
            if ($bankId) {
                $sql.="  AND    `cashbookledger`.`bankId`='" . $bankId . "'";
            }
            $sql.="
                        AND     YEAR(`cashbookledger`.`cashBookDate`)
			BETWEEN YEAR(DATE_SUB(NOW(), INTERVAL 3 YEAR)) AND YEAR(NOW())
			ORDER 	BY `cashbookledger`.`bankId`
			GROUP	BY (`cashbookledger`.`bankId`)";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT  ABS(SUM([cashBookAmount])) as [totalCashBookAmount]
			FROM    [cashBookLedger]
			JOIN	[bank]
			ON      [cashBookLedger].[companyId]        =   [bank].[companyId]
			AND     [cashBookLedger].[bankId]           =   [bank].[bankId]
			JOIN    [chartOfAccount] 
			ON      [cashBookLedger].[companyId]        =   [chartOfAccount].[companyId]
			AND     [cashBookLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
			WHERE   [companyId]	='" . $this->getCompanyId() . "'
			AND		[cashBookAmount] > 0 
			AND		YEAR([cashBookDate]) =  '" . $this->getYear() . "'";
            if ($bankId) {
                $sql.="  AND    [cashBookLedger].[bankId]='" . $bankId . "'";
            }
            $sql.="
            AND     YEAR([cashBookDate])
             BETWEEN YEAR(DATE_SUB(NOW(), INTERVAL 3 YEAR)) AND YEAR(NOW())
             ORDER 	BY [bankId]
             GROUP	BY ([bankId])";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  ABS(SUM(CASHBOOKAMOUNT)) AS \"totalCashBookAmount\"
            FROM    CASHBOOKLEDGER
            JOIN    BANK
            ON      CASHBOOKLEDGER.COMPANYID        =   BANK.COMPANYID
            AND     CASHBOOKLEDGER.BANKID           =   BANK.BANKID
            JOIN    CHARTOFACCOUNT 
            ON      CASHBOOKLEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     CASHBOOKLEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
            WHERE   COMPANYID	='" . $this->getCompanyId() . "'
            AND		CASHBOOKAMOUNT > 0 
            AND		YEAR(CASHBOOKDATE) =  '" . $this->getYear() . "'";
            if ($bankId) {
                $sql.="  AND    CASHBOOKLEDGER.BANKID='" . $bankId . "'";
            }
            $sql.="
            AND     YEAR(CASHBOOKDATE)
            BETWEEN YEAR(DATE_SUB(NOW(), INTERVAL 3 YEAR)) AND YEAR(NOW())
            ORDER 	BY  BANKID
            GROUP	BY (BANKID)";
        }

        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
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
     * Return Money From Bank via yearly Cross Tab
     * @param string $date Transaction Date
     * @param null|int $bankId Bank Primary Key
     * @return array|mixed
     * @throws \Exception
     */
    function getBankYearly($date, $bankId = null) {
        $sql = null;
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  ABS(SUM(`cashbookledger`.`cashBookAmount`)) as `totalCashBookAmount`
            FROM    `cashbookledger`
                JOIN    `bank`
            ON      `cashbookledger`.`companyId` = `bank`.`companyId`
            AND     `cashbookledger`.`bankId`   =   `bank`.`bankId`
            JOIN    `chartofaccount`
            ON      `cashbookledger`.`companyId` = `chartofaccount`.`companyId`
            AND     `cashbookledger`.`chartOfAccountId`   =   `chartofaccount`.`chartOfAccountId` 
            WHERE   `cashbookledger`.`companyId`	='" . $this->getCompanyId() . "'
            AND		YEAR(`cashbookledger`.`cashBookDate`) =  '" . $this->getYear() . "'";
            if ($bankId) {
                $sql.="  AND    `cashbookledger`.`bankId`='" . $bankId . "'";
            }
            $sql.="
            AND     YEAR(`cashbookledger`.`cashBookDate`)
            BETWEEN YEAR(DATE_SUB(NOW(), INTERVAL 3 YEAR)) AND YEAR(NOW())
            ORDER 	BY `cashbookledger`.`bankId`
            GROUP	BY (`cashbookledger`.`bankId`)";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT  ABS(SUM([cashBookAmount])) as [totalCashBookAmount]
			FROM    [cashBookLedger]
			JOIN	[bank]
			ON      [cashBookLedger].[companyId]        =   [bank].[companyId]
			AND     [cashBookLedger].[bankId]           =   [bank].[bankId]
			JOIN    [chartOfAccount] 
			ON      [cashBookLedger].[companyId]        =   [chartOfAccount].[companyId]
			AND     [cashBookLedger].[chartOfAccountId] =   [chartOfAccount].[chartOfAccountId]
			WHERE   [companyId]	='" . $this->getCompanyId() . "'
			AND		YEAR([cashBookDate]) =  '" . $this->getYear() . "'";
            if ($bankId) {
                $sql.="  AND    [cashBookLedger].[bankId]='" . $bankId . "'";
            }
            $sql.="
            AND     YEAR([cashBookDate])
            BETWEEN YEAR(DATE_SUB(NOW(), INTERVAL 3 YEAR)) AND YEAR(NOW())
            ORDER 	BY [bankId]
            GROUP	BY ([bankId])";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  ABS(SUM(CASHBOOKAMOUNT)) AS \"totalCashBookAmount\"
            FROM    CASHBOOKLEDGER
            JOIN    BANK
            ON      CASHBOOKLEDGER.COMPANYID        =   BANK.COMPANYID
            AND     CASHBOOKLEDGER.BANKID           =   BANK.BANKID
            JOIN    CHARTOFACCOUNT 
            ON      CASHBOOKLEDGER.COMPANYID        =   CHARTOFACCOUNT.COMPANYID
            AND     CASHBOOKLEDGER.CHARTOFACCOUNTID =   CHARTOFACCOUNT.CHARTOFACCOUNTID
            WHERE   COMPANYID	='" . $this->getCompanyId() . "'
            AND		YEAR(CASHBOOKDATE) =  '" . $this->getYear() . "'";
            if ($bankId) {
                $sql.="  AND    CASHBOOKLEDGER.BANKID='" . $bankId . "'";
            }
            $sql.="
            AND     YEAR(CASHBOOKDATE)
            BETWEEN YEAR(DATE_SUB(NOW(), INTERVAL 3 YEAR)) AND YEAR(NOW())
            ORDER 	BY  BANKID
            GROUP	BY (BANKID)";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
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
     * Change zerofiled
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
     * Set Day
     * @param int $value
     * @return $this
     */
    function setDay($value) {
        $this->day = $value;
        return $this;
    }

    /**
     *
     * @return int
     */
    function getDay() {
        return $this->day;
    }

    /**
     * Set Week
     * @param string $value
     * @return $this
     */
    function setWeek($value) {
        $this->week = $value;
        return $this;
    }

    /**
     * Set Week
     * @return string
     */
    function getWeek() {
        return $this->week;
    }

    /**
     * Set Month
     * @param int $value
     * @return $this
     */
    function setMonth($value) {
        $this->month = $value;
        return $this;
    }

    /**
     * Get Month
     * @return int
     */
    function getMonth() {
        return $this->month;
    }

    /**
     * Set Year
     * @param int $value
     */
    function setYear($value) {
        $this->year = $value;
        return $this;
    }

    /**
     * Return Year
     * @return int
     */
    function getYear() {
        return $this->year;
    }

    /**
     * Set Total Day In Month
     * @param int $value
     * @return $this
     */
    function setTotalDayInMonth($value) {
        $this->totalDayInMonth = $value;
        return $this;
    }

    /**
     * Total Day In month
     * @return int
     */
    function getTotalDayInMonth() {
        return $this->totalDayInMonth;
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
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

?>