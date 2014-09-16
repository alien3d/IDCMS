<?php

namespace Core\Financial\GeneralLedger\JournalDetail\Service;

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
 * Class JournalDetailService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\GeneralLedger\JournalDetail\Service
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class JournalDetailService extends ConfigClass {

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
     * Return Flag either wanted automatic posting or batch
     * @var int
     */
    private $isPosting;

    /**
     * Financial Year
     * @var int
     */
    private $financeYearId;

    /**
     * Country
     * @var int
     */
    private $countryId;

    /**
     * Country Currency Code. E.g Malaysia-> MYR
     * @var string
     */
    private $countryCurrencyCode;

    /**
     * Country Description
     * @var string
     */
    private $countryDescription;

    /**
     * Transaction Type
     * @var int
     */
    private $transactionTypeId;

    /**
     * Transaction Type Code. E.g D->Debit,C->Credit
     * @var string
     */
    private $transactionTypeCode;

    /**
     * Transaction Type Description E.g Debit,Credit,Credit Note ,Debit Note
     * @var string
     */
    private $transactionTypeDescription;

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
        $this->getOverrideCountry();
    }

    /**
     * Get Default Company Country
     */
    public function getOverrideCountry() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT `country`.`countryId`,
                   `country`.`countryCurrencyCode`,
                   `country`.`countryDescription`,
                   `financesetting`.`isPosting`,
                   `financesetting`.`financeYearId`
            FROM   `financesetting`
            JOIN   `country`
            USING  (`companyId`,`countryId`)
            WHERE  `financesetting`.`companyId` ='" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT [country].[countryId],
                   [country].[countryCurrencyCode],
                   [country].[countryDescription],
                   [financeSetting].[isPosting],
                   [financeSetting].[financeYearId]
            FROM   [financeSetting]
            JOIN   [country]
            ON     [financeSetting].[companyId] = [company].[companyId]
            AND    [financeSetting].[companyId] = [country].[countryId]
            WHERE  [financeSetting].[companyId] ='" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT COUNTRY.COUNTRYID AS \"countryId\",
                   COUNTRY.COUNTRYCURRENCYCODE AS \"countryCurrencyCode,
                   COUNTRY.COUNTRYDESCRIPTION AS \"isPosting\",
                   FINANCESETTING.ISPOSTING AS \"isPosting\",
                   FINANCESETTING.FINANCEYEARID AS \"financeYearId\"
            FROM   FINANCESETTING
            JOIN   COUNTRY
            ON     FINANCESETTING.COMPANYID = COMPANY.COMPANYID
            AND    FINANCESETTING.COUNTRYID = COUNTRY.COUNTRYID
            WHERE  FINANCESETTING.COMPANYID ='" . $this->getCompanyId() . "'";
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
            $this->setCountryId($row['countryId']);
            $this->setCountryCurrencyCode($row['countryCurrencyCode']);
            $this->setCountryDescription($row['countryDescription']);
            $this->setIsPosting($row['isPosting']);
            $this->setFinanceYearId($row['financeYearId']);
        }
    }

    /**
     * Return Journal
     * @return array|string
     */
    public function getJournal() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `journalId`,
                     `journalDescription`
         FROM        `journal`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [journalId],
                     [journalDescription]
         FROM        [journal]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      JOURNALID AS \"journalId\",
                     JOURNALDESCRIPTION AS \"journalDescription\"
         FROM        JOURNAL
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         ORDER BY    ISDEFAULT";
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
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['journalId'] . "'>" . $d . ". " . $row['journalDescription'] . "</option>";
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
     * Return Journal Default Value
     * @return int
     */
    public function getJournalDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $journalId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `journalId`
         FROM        	`journal`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [journalId],
         FROM        [journal]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      JOURNALID AS \"journalId\",
         FROM        JOURNAL
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
            $journalId = $row['journalId'];
        }
        return $journalId;
    }

    /**
     * Return Chart Of Account
     * @return array|string
     */
    public function getChartOfAccount() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `chartofaccount`.`chartOfAccountId`,
					 `chartofaccount`.`chartOfAccountNumber`,
                     `chartofaccount`.`chartOfAccountTitle`,
                     `chartofaccounttype`.`chartOfAccountTypeDescription`
         FROM        `chartofaccount`
         JOIN        `chartofaccounttype`
         USING       (`companyId`,`chartOfAccountTypeId`)
         WHERE       `chartofaccount`.`isActive`  =   1
         AND         `chartofaccount`.`companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `chartofaccounttype`.`chartOfAccountTypeId`,
                     `chartofaccount`.`chartOfAccountNumber`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [chartOfAccount].[chartOfAccountId],
					 [chartOfAccount].[chartOfAccountNumber],
                     [chartOfAccount].[chartOfAccountTitle],
                     [chartOfAccountType].[chartOfAccountTypeDescription]
         FROM        [chartOfAccount]
         ON          [chartOfAccount].[companyId]   = [chartOfAccountType].[companyId]
         AND         [chartOfAccount].[chartOfAccountTypeId]   = [chartOfAccountType].[chartOfAccountTypeId]
         WHERE       [chartOfAccount].[isActive]  =   1
         AND         [chartOfAccount].[companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [chartOfAccount].[chartOfAccountNumber]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      CHARTOFACCOUNTID               AS  \"chartOfAccountId\",
					 CHARTOFACCOUNTNUMBER           AS  \"chartOfAccountNumber\",
                     CHARTOFACCOUNTTITLE            AS  \"chartOfAccountTitle\",
                     CHARTOFACCOUNTTYPEDESCRIPTION  AS  \"chartOfAccountTypeDescription\"
         FROM        CHARTOFACCOUNT
         JOIN        CHARTOFACCOUNTTYPE
         ON          CHARTOFACCOUNT.COMPANYID               =   CHARTOFACCOUNTTYPE.COMPANYID
         AND         CHARTOFACCOUNT.CHARTOFACCOUNTTYPEID    =   CHARTOFACCOUNTTYPE.CHARTOFACCOUNTTYPEID
         WHERE       CHARTOFACCOUNT.ISACTIVE                =   1
         AND         CHARTOFACCOUNT.COMPANYID               =   '" . $this->getCompanyId() . "'
         ORDER BY    CHARTOFACCOUNT.CHARTOFACCOUNTNUMBER";
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
            $d = 0;
            $chartOfAccountTypeDescription = null;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($d != 0) {
                    if ($chartOfAccountTypeDescription != $row['chartOfAccountTypeDescription']) {
                        $str .= "</optgroup><optgroup label=\"" . $row['chartOfAccountTypeDescription'] . "\">";
                    }
                } else {
                    $str .= "<optgroup label=\"" . $row['chartOfAccountTypeDescription'] . "\">";
                }
                $chartOfAccountTypeDescription = $row['chartOfAccountTypeDescription'];

                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['chartOfAccountId'] . "'>" . $row['chartOfAccountNumber'] . " -  " . $row['chartOfAccountTitle'] . "</option>";
                } else {
                    if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
                    }
                }
                $d++;
            }
            $str .= "</optgroup>";
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
     * Return Chart Of Account Default Value
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
     * Sum Total Debit As Journal Amount
     * @param int $journalId Journal Primary Key
     */
    public function sumDebitJournal($journalId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE 		`journal`
			SET			`journalAmount` =
			(
				SELECT      SUM(`journalDetailAmount`)
				FROM        `journaldetail`

				WHERE       `journaldetail`.`companyId`     		=   '" . $this->getCompanyId() . "'
				AND         `journaldetail`.`journalId`				IN   (" . $journalId . ")
				AND         `journaldetail`.`isActive`				=	1
				AND 		`journaldetail`.`journalDetailAmount` 	> 0
			) 
			WHERE		`journalId`	='" . $journalId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql .= "
			UPDATE 		[journal]
			SET			[journalAmount] =
			(
				SELECT      SUM([journalDetailAmount]) AS [total]
				FROM       [journalDetail]

				WHERE      [journalDetail].[companyId]     		=   '" . $this->getCompanyId() . "'
				AND        [journalDetail].[journalId]     		IN   (" . $journalId . ")
				AND        [journalDetail].[isActive]				=   1
				AND 		[journalDetail].[journalDetailAmount]	>	0
			)
			WHERE		[journalId]	=	'" . $journalId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE 		JOURNAL
			SET			JOURNALAMOUNT = (
				SELECT  SUM(JOURNALDETAILAMOUNT) AS \"total\"
				FROM    JOURNALDETAIL

				WHERE   JOURNALDETAIL.COMPANYID     =   '" . $this->getCompanyId() . "'
				AND     JOURNALDETAIL.JOURNALID     IN   (" . $journalId . ")
				AND     JOURNALDETAIL.ISACTIVE		=   1
				AND 	JOURNALDETAILAMOUNT 		>	0
			)
			WHERE		JOURNALID	=	'" . $journalId . "'";
        }

        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Return Total debit,credit and trial balance
     * @param int $journalId
     * @return array
     */
    public function getTotalJournalDetail($journalId) {
        header('Content-Type:application/json; charset=utf-8');
        $this->q->start();
        $totalDebit = $this->getTotalJournalAmount($journalId, 1);
        $totalCredit = $this->getTotalJournalAmount($journalId, 2);
        $trialBalance = $this->getCheckTrialBalance($journalId);
        if ($trialBalance == 0 || $trialBalance == '0.00') {
            $this->setUpdateTrialBalance($journalId, 1);
        } else {
            $this->setUpdateTrialBalance($journalId, 0);
        }
        $this->q->commit();
        return array(
            "success" => true,
            "totalDebit" => $totalDebit,
            "totalCredit" => $totalCredit,
            "trialBalance" => $trialBalance
        );
    }

    /**
     * Return Total Journal Amount
     * @param int $journalId Journal Primary Key
     * @param string $type 1->debit,2->credit
     * @return double $total
     */
    public function getTotalJournalAmount($journalId, $type) {
        $total = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      SUM(`journalDetailAmount`) AS `total`
            FROM        `journaldetail`

            WHERE       `journaldetail`.`companyId`                         =   '" . $this->getCompanyId() . "'
            AND         `journaldetail`.`journalId`                         IN   (" . $journalId . ")
            AND         `journaldetail`.`isActive`                          =   1";
            if ($type == 1) {
                $sql .= "  AND `journalDetailAmount` >0 ";
            } else {
                $sql .= "  AND `journalDetailAmount` < 0 ";
            }
            //  $sql .= "
            //  GROUP BY    `journaldetail`.`journalDetailAmount`
            //  ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql .= "
            SELECT      SUM([journalDetailAmount]) AS [total]
            FROM       [journalDetail]

            WHERE      [journalDetail].[companyId]                         =   '" . $this->getCompanyId() . "'
            AND        [journalDetail].[journalId]                         IN   (" . $journalId . ")
            AND        [journalDetail].[isActive]                          =   1";
            if ($type == 1) {
                $sql .= "  AND [journalDetailAmount] >0 ";
            } else {
                $sql .= "  AND [journalDetailAmount] < 0 ";
            }
            $sql .= "
            GROUP BY   [journalDetail].[journalDetailAmount]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      SUM(JOURNALDETAILAMOUNT) AS \"total\"
            FROM        JOURNALDETAIL

            WHERE       JOURNALDETAIL.COMPANYID                         =   '" . $this->getCompanyId() . "'
            AND         JOURNALDETAIL.JOURNALID                         IN   (" . $journalId . ")
            AND         JOURNALDETAIL.ISACTIVE                          =   1";
            if ($type == 1) {
                $sql .= "  AND JOURNALDETAILAMOUNT >0 ";
            } else {
                $sql .= "  AND JOURNALDETAILAMOUNT < 0 ";
            }
            $sql .= "
            GROUP BY    JOURNALDETAIL.JOURNALDETAILAMOUNT
            ";
        }

        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $total = $row['total'];
        }
        return $total;
    }

    /**
     * Return Trial Balance Is correct or not before posting to journal . SUM ASSET  account - LIABILITY accounts + INCOME - Expenses + Return Earning Accounts
     * @param int $journalId
     * @return string $trialBalance
     */
    private function getCheckTrialBalance($journalId) {
        $trialBalance = 0;
        $sql = null;

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT (
                (
                    SELECT      COALESCE(SUM(`journalDetailAmount`),0)
                    FROM        `journaldetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `journaldetail`.`companyId`                         =   '" . $this->getCompanyId() . "'
                    AND         `journaldetail`.`journalId`                         IN   (" . $journalId . ")
                    AND         `journaldetail`.`isActive`                          =   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` =   '" . self::ASSET . "'

                    /* GROUP BY    `journaldetail`.`journalDetailAmount` */
                )
                +
                 (
                    SELECT      COALESCE(SUM(`journalDetailAmount`),0)
                    FROM        `journaldetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `journaldetail`.`companyId`                         =   '" . $this->getCompanyId() . "'
                    AND         `journaldetail`.`journalId`                         IN   (" . $journalId . ")
                    AND         `journaldetail`.`isActive`                          =   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` IN   ('" . self::LIABILITY . "','" . self::EQUITY . "')
                    /* GROUP BY    `journaldetail`.`journalDetailAmount` */
                )
                 +
                 (
                    SELECT      COALESCE(SUM(`journalDetailAmount`),0)
                    FROM        `journaldetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `journaldetail`.`companyId`                         =   '" . $this->getCompanyId() . "'
                    AND         `journaldetail`.`journalId`                         IN   (" . $journalId . ")
                    AND         `journaldetail`.`isActive`                          =   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` =   '" . self::INCOME . "'
                    /* GROUP BY    `journaldetail`.`journalDetailAmount` */
                )
                 +
                 (
                    SELECT      COALESCE(SUM(`journalDetailAmount`),0)
                    FROM        `journaldetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `journaldetail`.`companyId`                         =   '" . $this->getCompanyId() . "'
                    AND         `journaldetail`.`journalId`                         IN   (" . $journalId . ")
                    AND         `journaldetail`.`isActive`                          =   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` =   '" . self::EXPENSES . "'
                    /* GROUP BY    `journaldetail`.`journalDetailAmount` */
                )
            ) as `trialBalance`

            ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT (
                (
                    SELECT      SUM([journalDetailAmount])
                    FROM       [journalDetail]

                    JOIN        [chartOfAccount]
                    ON         [journalDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND        [journalDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON         [journalDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE      [journalDetail].[companyId]                         =   '" . $this->getCompanyId() . "'
                    AND        [journalDetail].[journalId]                         IN  (" . $journalId . ")
                    AND        [journalDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::ASSET . "'
                    GROUP BY   [journalDetail].[journalDetailAmount]
                )
                -
                 (
                    SELECT      SUM([journalDetailAmount])
                    FROM       [journalDetail]

                    JOIN        [chartOfAccount]
                    ON         [journalDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND        [journalDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON         [journalDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE      [journalDetail].[companyId]                         =   '" . $this->getCompanyId() . "'
                    AND        [journalDetail].[journalId]                         IN   (" . $journalId . ")
                    AND        [journalDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] IN   ('" . self::LIABILITY . "','" . self::EQUITY . "')
                    GROUP BY   [journalDetail].[journalDetailAmount]
                )
                 +
                 (
                    SELECT      SUM([journalDetailAmount])
                    FROM       [journalDetail]

                    JOIN        [chartOfAccount]
                    ON         [journalDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND        [journalDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON         [journalDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE      [journalDetail].[companyId]                         =   '" . $this->getCompanyId() . "'
                    AND        [journalDetail].[journalId]                         IN   (" . $journalId . ")
                    AND        [journalDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::EQUITY . "'
                    GROUP BY   [journalDetail].[journalDetailAmount]
                ) +
                 (
                    SELECT      SUM([journalDetailAmount])
                    FROM       [journalDetail]

                    JOIN        [chartOfAccount]
                    ON         [journalDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND        [journalDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON         [journalDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE      [journalDetail].[companyId]                         =   '" . $this->getCompanyId() . "'
                    AND        [journalDetail].[journalId]                         IN   (" . $journalId . ")
                    AND        [journalDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::INCOME . "'
                    GROUP BY   [journalDetail].[journalDetailAmount]
                )
                 -
                 (
                    SELECT      SUM([journalDetailAmount])
                    FROM       [journalDetail]

                    JOIN        [chartOfAccount]
                    ON         [journalDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND        [journalDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON         [journalDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE      [journalDetail].[companyId]                         =   '" . $this->getCompanyId() . "'
                    AND        [journalDetail].[journalId]                         IN   (" . $journalId . ")
                    AND        [journalDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::EXPENSES . "'
                    GROUP BY   [journalDetail].[journalDetailAmount]
                )
            ) as [trialBalance]

            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT (
                (
                    SELECT      SUM(JOURNALDETAILAMOUNT)
                    FROM        JOURNALDETAIL
                    JOIN        CHARTOFACCOUNT
                    ON          JOURNALDETAIL.COMPANYID                         =   CHARTOFACCOUNT.COMPANYID
                    AND         JOURNALDETAIL.CHARTOFACCOUNTID                  =   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          JOURNALDETAIL.COMPANYID                         =   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         =   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       JOURNALDETAIL.COMPANYID                         =   '" . $this->getCompanyId() . "'
                    AND         JOURNALDETAIL.JOURNALID                         IN   (" . $journalId . ")
                    AND         JOURNALDETAIL.ISACTIVE                          =   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE =   '" . self::ASSET . "'
                    GROUP BY    JOURNALDETAIL.JOURNALDETAILAMOUNT
                )
                -
                 (
                    SELECT      SUM(JOURNALDETAILAMOUNT)
                    FROM        JOURNALDETAIL

                    JOIN        CHARTOFACCOUNT
                    ON          JOURNALDETAIL.COMPANYID                         =   CHARTOFACCOUNT.COMPANYID
                    AND         JOURNALDETAIL.CHARTOFACCOUNTID                  =   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          JOURNALDETAIL.COMPANYID                         =   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         =   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       JOURNALDETAIL.COMPANYID                         =   '" . $this->getCompanyId() . "'
                    AND         JOURNALDETAIL.JOURNALID                         IN   (" . $journalId . ")
                    AND         JOURNALDETAIL.ISACTIVE                          =   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE =   '" . self::LIABILITY . "'
                    GROUP BY    JOURNALDETAIL.JOURNALDETAILAMOUNT
                )
                 +
                 (
                    SELECT      SUM(JOURNALDETAILAMOUNT)
                    FROM        JOURNALDETAIL
                    JOIN        CHARTOFACCOUNT
                    ON          JOURNALDETAIL.COMPANYID                         =   CHARTOFACCOUNT.COMPANYID
                    AND         JOURNALDETAIL.CHARTOFACCOUNTID                  =   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          JOURNALDETAIL.COMPANYID                         =   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         =   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTID

                    WHERE       JOURNALDETAIL.COMPANYID                         =   '" . $this->getCompanyId() . "'
                    AND         JOURNALDETAIL.JOURNALID                         IN   (" . $journalId . ")
                    AND         JOURNALDETAIL.ISACTIVE                          =   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE =   '" . self::EQUITY . "'
                    GROUP BY    JOURNALDETAIL.JOURNALDETAILAMOUNT
                ) +
                 (
                    SELECT      SUM(JOURNALDETAILAMOUNT)
                    FROM        JOURNALDETAIL

                    JOIN        CHARTOFACCOUNT
                    ON          JOURNALDETAIL.COMPANYID = CHARTOFACCOUNT.COMPANYID
                    AND         JOURNALDETAIL.CHARTOFACCOUNTID = CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          JOURNALDETAIL.COMPANYID                         =   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         =   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       JOURNALDETAIL.COMPANYID                         =   '" . $this->getCompanyId() . "'
                    AND         JOURNALDETAIL.JOURNALID                         IN   (" . $journalId . ")
                    AND         JOURNALDETAIL.ISACTIVE                          =   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE =   '" . self::INCOME . "'
                    GROUP BY    JOURNALDETAIL.JOURNALDETAILAMOUNT
                )
                 -
                 (
                    SELECT      SUM(JOURNALDETAILAMOUNT)
                    FROM        JOURNALDETAIL

                    JOIN        CHARTOFACCOUNT
                    ON          JOURNALDETAIL.COMPANYID                         =   CHARTOFACCOUNT.COMPANYID
                    AND         JOURNALDETAIL.CHARTOFACCOUNTID                  =   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          JOURNALDETAIL.COMPANYID                         =   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT..CHARTOFACCOUNTCATEGORYID        =   CHARTOFACCOUNTCATEGORY..CHARTOFACCOUNTCATEGORYID

                    WHERE       JOURNALDETAIL.COMPANYID                         =   '" . $this->getCompanyId() . "'
                    AND         JOURNALDETAIL.JOURNALID                         IN   (" . $journalId . ")
                    AND         JOURNALDETAIL.ISACTIVE                          =   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE =   '" . self::EXPENSES . "'
                    GROUP BY    JOURNALDETAIL.JOURNALDETAILAMOUNT
                )
            ) as [trialBalance]

            )";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $trialBalance = $row['trialBalance'];
        }

        return $trialBalance;
    }

    /**
     * Update Trial Balance So  can be view In Journal Posting area/application/leaf
     * @param int $journalId
     * @param bool $value
     */
    private function setUpdateTrialBalance($journalId, $value) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE `journal`
            SET    `isBalance`='" . $value . "'
            WHERE  `journalId`='" . $journalId . "'
            ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE [journal]
            SET    [isBalance]='" . $value . "'
            WHERE  [journalId]='" . $journalId . "'
            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE  JOURNAL
            SET     ISBALANCE='" . $value . "'
            WHERE   JOURNALID='" . $journalId . "'
            ";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Distinct Chart Of Account From Journal Posted and update the budget
     * @param  int|string $journalId Journal Primary Key
     */
    public function setSumActualTransaction($journalId) {
        $sql = null;
        $sqlDetail = null;
        $sqlUpdate = null;
        $fieldActual = null;
        // choose from finance period
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `financePeriodRangePeriod`,
                    `financePeriodRangeStartDate`,
                    `financePeriodRangeEndDate`
            FROM    `financeperiodrange`
            WHERE   `financeYearId` = '" . $this->getFinanceYearId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT [financePeriodRangePeriod],
                   [financePeriodRangeStartDate] ,
                   [financePeriodRangeEndDate]
            FROM    [financePeriodRange]
            WHERE  [financeYearId] =  '" . $this->getFinanceYearId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT  FINANCEPERIODRANGEPERIOD AS  \"financePeriodRangePeriod\" ,
                    FINANCEPERIODRANGESTARTDATE AS  \"financePeriodRangeStartDate\" ,
                    FINANCEPERIODRANGEENDATE    AS  \"financePeriodRangeEndDate\"
            FROM    FINANCEPERIODRANGE
            WHERE   FINANCEYEARID  = '" . $this->getFinanceYearId() . "'";
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
            while (($row = $this->q->fetchArray($result)) == true) {
                $startFinancialDate = $row['financePeriodRangeStartDate'];
                $endFinancialDate = $row['financePeriodRangeEndDate'];
                $financePeriodRangePeriod = $row['financePeriodRangePeriod'];
                switch ($financePeriodRangePeriod) {
                    case 1:
                        $fieldActual = "actualTargetMonthOne";
                        break;
                    case 2:
                        $fieldActual = "actualTargetMonthTwo";
                        break;
                    case 3:
                        $fieldActual = "actualTargetMonthThree";
                        break;
                    case 4:
                        $fieldActual = "actualTargetMonthFourth";
                        break;
                    case 5:
                        $fieldActual = "actualTargetMonthFifth";
                        break;
                    case 6:
                        $fieldActual = "actualTargetMonthSix";
                        break;
                    case 7:
                        $fieldActual = "actualTargetMonthSeven";
                        break;
                    case 8:
                        $fieldActual = "actualTargetMonthEight";
                        break;
                    case 9:
                        $fieldActual = "actualTargetMonthNine";
                        break;
                    case 10:
                        $fieldActual = "actualTargetMonthTen";
                        break;
                    case 11:
                        $fieldActual = "actualTargetMonthEleven";
                        break;
                    case 12:
                        $fieldActual = "actualTargetMonthTwelve";
                        break;
                    case 13:
                        $fieldActual = "actualTargetMonthThirteen";
                        break;
                    case 14:
                        $fieldActual = "actualTargetMonthFourteen";
                        break;
                    case 15:
                        $fieldActual = "actualTargetMonthFifteen";
                        break;
                    case 16:
                        $fieldActual = "actualTargetMonthSixteen";
                        break;
                    case 17:
                        $fieldActual = "actualTargetMonthSeventeen";
                        break;
                    case 18:
                        $fieldActual = "actualTargetMonthEighteen";
                        break;
                }
                // select distinct  journal id
                // choose from finance period
                if ($this->getVendor() == self::MYSQL) {
                    $sqlDetail = "
                    SELECT  DISTINCT    `chartOfAccountId`
                    FROM   `journaldetail`
                    WHERE  `journalId` IN (" . $journalId . ") ";
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $sqlDetail = "
                    SELECT DISTINCT    [chartOfAccountId]
                    FROM  [journalDetail]
                    WHERE  [journalId]  IN  (" . $journalId . ")
                    ";
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $sqlDetail = "
                    SELECT  DISTINCT CHARTOFACCOUNTID AS \"chartOfAccountId\"
                    FROM    JOURNALDETAIL
                    WHERE   JOURNALID IN (" . $journalId . ")
            ";
                        }
                    }
                }
                try {
                    $resultDetail = $this->q->fast($sqlDetail);
                } catch (\Exception $e) {
                    echo json_encode(array("success" => false, "message" => $e->getMessage()));
                    exit();
                }
                if ($resultDetail) {
                    while (($rowDetail = $this->q->fetchArray($resultDetail)) == true) {
                        $chartOfAccountId = $rowDetail['chartOfAccountId'];
                        // UPDATE budget based on SUM range period
                        if ($this->getVendor() == self::MYSQL) {
                            $sqlUpdate = "
                            UPDATE `budget`
                            SET    `" . $fieldActual . "` = (
                                SELECT  SUM(`generalLedgerAmount`)
                                FROM    `generalledger`
                                WHERE   `chartOfAccountId`  =   '" . $chartOfAccountId . "'
                                AND     `companyId`         =   '" . $this->getCompanyId() . "'
                                AND     (`generalLedgerDate` BETWEEN
                                                                        '" . $startFinancialDate . "'
                                                            AND
                                                                        '" . $endFinancialDate . "')
                            )
                            WHERE   `chartOfAccountId`          =   '" . $chartOfAccountId . "'
                            AND     `financePeriodRangePeriod`  =   '" . $financePeriodRangePeriod . "'
                            AND     `companyId`                 =   '" . $this->getCompanyId() . "'

                            ";
                        } elseif ($this->getVendor() == self::MSSQL) {
                            $sqlUpdate = "
                            UPDATE [financeBudget]
                            SET    [" . $fieldActual . "] = (
                            SELECT  SUM([generalLedgerAmount])
                            FROM    [generalLedger]
                            WHERE   [chartOfAccountId]='" . $chartOfAccountId . "'
                            AND     [companyId] ='" . $this->getCompanyId() . "'
                            AND     [generalLedgerDate] between '" . $startFinancialDate . "' AND '" . $endFinancialDate . "'

                            )
                            WHERE   [chartOfAccountId]			=	'" . $chartOfAccountId . "'
                            AND     [financePeriodRangePeriod]  =	'" . $financePeriodRangePeriod . "'
                            AND     [companyId]     			=	'" . $this->getCompanyId() . "'

                            ";
                        } elseif ($this->getVendor() == self::ORACLE) {
                            $sqlUpdate = "
                            UPDATE  BUDGET
                            SET     " . $fieldActual . " = (
                            SELECT  SUM(GENERALLEDGERAMOUNT)
                            FROM    GENERALLEDGER
                            WHERE   CHARTOFACCOUNTID			=	'" . $chartOfAccountId . "'
                            AND     COMPANYID 					=	'" . $this->getCompanyId() . "'
                            AND     GENERALLEDGERDATE BETWEEN '" . $startFinancialDate . "' AND '" . $endFinancialDate . "'
                            )
                            WHERE   CHARTOFACCOUNTID			=	'" . $chartOfAccountId . "'
                            AND     FINANCEPERIODRANGEPERIOD  	=	'" . $financePeriodRangePeriod . "'
                            AND     COMPANYID     				=	'" . $this->getCompanyId() . "'

                            ";
                        }
                        try {
                            $this->q->update($sqlUpdate);
                        } catch (\Exception $e) {
                            $this->q->rollback();
                            echo json_encode(array("success" => false, "message" => $e->getMessage()));
                            exit();
                        }
                    }
                }
            }
        }
    }

    /**
     * Return Financial Year Primary Key
     * @return int
     */
    public function getFinanceYearId() {
        return $this->financeYearId;
    }

    /**
     * Set Financial Year Primary Key
     * @param int $financeYearId
     * @return $this|ConfigClass
     */
    public function setFinanceYearId($financeYearId) {
        $this->financeYearId = $financeYearId;
        return $this;
    }

    /**
     * Set New Fast Chart Of Account
     * @param string $chartOfAccountNumber Number
     * @param string $chartOfAccountTitle Title
     * @param null|int $chartOfAccountCategoryId Chart Of Account Category Primary Key
     * @param null|int $chartOfAccountTypeId Chart Of Account Type Primary Key
     * return @void
     */
    public function setNewChartOfAccount(
    $chartOfAccountNumber, $chartOfAccountTitle, $chartOfAccountCategoryId = null, $chartOfAccountTypeId = null
    ) {
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
        if (empty($chartOfAccountCategoryId)) {
            $chartOfAccountCategoryId = $this->getChartOfAccountCategoryDefaultValue();
        }
        if (empty($chartOfAccountTypeId)) {
            $chartOfAccountTypeId = $this->getChartOfAccountTypeDefaultValue();
        }
        //testing duplicate chart of account number
        $this->duplicateChartOfAccountNumber($chartOfAccountNumber);
        // check back if business partner id exist or not
        $sql = null;
        $chartOfAccountDescription = $chartOfAccountTitle;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `chartofaccount` 
            (
                 `companyId`,
                 `chartOfAccountCategoryId`,
                 `chartOfAccountTypeId`,
                 `chartOfAccountNumber`,
                 `chartOfAccountTitle`,
                 `chartOfAccountDescription`,
                 `isDefault`,
                 `isNew`,
                 `isDraft`,
                 `isUpdate`,
                 `isDelete`,
                 `isActive`,
                 `isApproved`,
                 `isReview`,
                 `isPost`,
                 `isConsolidation`,
                 `isSlice`,
                 `executeBy`,
                 `executeTime`
       ) VALUES ( 
                 '" . $this->getCompanyId() . "',
                 '" . $chartOfAccountCategoryId . "',
                 '" . $chartOfAccountTypeId . "',
                 '" . $chartOfAccountNumber . "',
                 '" . $chartOfAccountTitle . "',
                 '" . $chartOfAccountDescription . "',
                 '0',
                 '1',
                 '0',
                 '0',
                 '0',
                 '1',
                 '0',
                 '0',
                 '0',
                 '0',
                 '0',
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
       );";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            INSERT INTO [chartOfAccount]
            (
                 [chartOfAccountId],
                 [companyId],
                 [chartOfAccountCategoryId],
                 [chartOfAccountTypeId],
                 [chartOfAccountNumber],
                 [chartOfAccountTitle],
                 [chartOfAccountDescription],
                 [isDefault],
                 [isNew],
                 [isDraft],
                 [isUpdate],
                 [isDelete],
                 [isActive],
                 [isApproved],
                 [isReview],
                 [isPost],
                 [isConsolidation],
                 [isSlice],
                 [executeBy],
                 [executeTime]
) VALUES (
                     '" . $this->getCompanyId() . "',
                 '" . $chartOfAccountCategoryId . "',
                 '" . $chartOfAccountTypeId . "',
                 '" . $chartOfAccountNumber . "',
                 '" . $chartOfAccountTitle . "',
                 '" . $chartOfAccountDescription . "',
                 '0',
                 '1',
                 '0',
                 '0',
                 '0',
                 '1',
                 '0',
                 '0',
                 '0',
                 '0',
                 '0',
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
            );";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            INSERT INTO CHARTOFACCOUNT
            (
                 COMPANYID,
                 CHARTOFACCOUNTCATEGORYID,
                 CHARTOFACCOUNTTYPEID,
                 CHARTOFACCOUNTNUMBER,
                 CHARTOFACCOUNTTITLE,
                 CHARTOFACCOUNTDESCRIPTION,
                 ISDEFAULT,
                 ISNEW,
                 ISDRAFT,
                 ISUPDATE,
                 ISDELETE,
                 ISACTIVE,
                 ISAPPROVED,
                 ISREVIEW,
                 ISPOST,
                 ISCONSOLIDATION,
                 ISSLICE,
                 EXECUTEBY,
                 EXECUTETIME
            ) VALUES (
                '" . $this->getCompanyId() . "',
                 '" . $chartOfAccountCategoryId . "',
                 '" . $chartOfAccountTypeId . "',
                 '" . $chartOfAccountNumber . "',
                 '" . $chartOfAccountTitle . "',
                 '" . $chartOfAccountDescription . "',
                 '0',
                 '1',
                 '0',
                 '0',
                 '0',
                 '1',
                 '0',
                 '0',
                 '0',
                 '0',
                 '0',
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
            );";
        }

        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $chartOfAccountId = $this->q->lastInsertId();
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => true,
                    "message" => $this->t['newRecordTextLabel'],
                    "staffName" => $_SESSION['staffName'],
                    "executeTime" => date('d-m-Y H:i:s'),
                    "chartOfAccountId" => $chartOfAccountId,
                    "time" => $time
                )
        );
        exit();
    }

    /**
     * Return Chart Of Account Category Default Value
     * @return int
     */
    private function getChartOfAccountCategoryDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $chartOfAccountCategoryId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `chartOfAccountCategoryId`
         FROM        	`chartofaccountcategory`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [chartOfAccountCategoryId],
         FROM        [chartOfAccountCategory]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      CHARTOFACCOUNTCATEGORYID AS \"chartOfAccountCategoryId\",
         FROM        CHARTOFACCOUNTCATEGORY
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
            $chartOfAccountCategoryId = $row['chartOfAccountCategoryId'];
        }
        return $chartOfAccountCategoryId;
    }

    /**
     * Return ChartOfAccountType Default Value
     * @return int
     */
    private function getChartOfAccountTypeDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $chartOfAccountTypeId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `chartOfAccountTypeId`
         FROM        	`chartofaccounttype`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [chartOfAccountTypeId],
         FROM        [chartOfAccountType]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      CHARTOFACCOUNTTYPEID AS \"chartOfAccountTypeId\",
         FROM        CHARTOFACCOUNTTYPE
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         AND    	  ISDEFAULT	  =	   1
         AND 		  ROWNUM	  =	   1";
                } else {
                    header('Content-Type:application/json; charset=utf-8');
                    echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
                    exit();
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
            $chartOfAccountTypeId = $row['chartOfAccountTypeId'];
        }
        return $chartOfAccountTypeId;
    }

    /**
     * @param $chartOfAccountNumber
     * @return int
     */
    private function duplicateChartOfAccountNumber($chartOfAccountNumber) {

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
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT  `chartOfAccountNumber` 
           FROM    `chartofaccount` 
           WHERE   `chartOfAccountNumber` 	= 	'" . $chartOfAccountNumber . "' 
           AND     `isActive`  =   1
           AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT  [chartOfAccountNumber]
           FROM    [chartOfAccount]
           WHERE   [chartOfAccountNumber] = 	'" . $chartOfAccountNumber . "'
           AND     [isActive]  =   1
           AND     [companyId] =	'" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
               SELECT  CHARTOFACCOUNTNUMBER as \"chartOfAccountCode\"
               FROM    CHARTOFACCOUNT
               WHERE   CHARTOFACCOUNTNUMBER	= 	'" . $chartOfAccountNumber . "'
               AND     ISACTIVE    =   1
               AND     COMPANYID   =   '" . $this->getCompanyId() . "'";
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
        if ($total > 0) {
            $row = $this->q->fetchArray();
            $end = microtime(true);
            $time = $end - $start;
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(
                    array(
                        "success" => false,
                        "total" => $total,
                        "message" => $this->t['duplicateMessageLabel'],
                        "chartOfAccountNumber" => $row ['chartOfAccountNumber'],
                        "time" => $time
                    )
            );
            exit();
        } else {
            return 0;
        }
    }

    /**
     * Return Chart Of Account Category
     * @return array
     */
    public function getChartOfAccountCategory() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `chartOfAccountCategoryId`,
                     `chartOfAccountCategoryTitle`
         FROM        `chartofaccountcategory`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "';";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [chartOfAccountCategoryId],
                     [chartOfAccountCategoryTitle]
         FROM        [chartOfAccountCategory]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      CHARTOFACCOUNTCATEGORYID AS \"chartOfAccountCategoryId\",
                     CHARTOFACCOUNTCATEGORYDESCRIPTION AS \"chartOfAccountCategoryTitle\"
         FROM        CHARTOFACCOUNTCATEGORY
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'";
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
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['chartOfAccountCategoryId'] . "'>" . $d . ". " . $row['chartOfAccountCategoryTitle'] . "</option>";
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
     * Return Chart Of Account
     * @param null|int $chartOfAccountCategoryId
     * @return array|string
     */
    public function getChartOfAccountType($chartOfAccountCategoryId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `chartofaccounttype`.`chartOfAccountTypeId`,
                     `chartofaccounttype`.`chartOfAccountTypeDescription`,
                     `chartofaccountcategory`.`chartOfAccountCategoryDescription`
         FROM        `chartofaccounttype`
         JOIN        `chartofaccountcategory`
         USING       (`companyId`,`chartOfAccountCategoryId`)
         WHERE       `chartofaccounttype`.`isActive`  =   1
         AND         `chartofaccounttype`.`companyId` =   '" . $this->getCompanyId() . "'";
            if ($chartOfAccountCategoryId) {
                $sql .= "
			AND `chartofaccounttype`.`chartOfAccountCategoryId`='" . $chartOfAccountCategoryId . "'";
            }
            $sql .= "

         ORDER BY    `chartofaccounttype`.`chartOfAccountTypeDescription`,
                     `chartofaccountcategory`.`chartOfAccountCategoryDescription`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [chartOfAccountType].[chartOfAccountTypeId],
                     [chartOfAccountType].[chartOfAccountTypeDescription],
                     [chartOfAccountCategory][chartOfAccountCategoryDescription]
         FROM        [chartOfAccountType]
         JOIN        [chartOfAccountCategory]
         ON          [chartOfAccountType].[companyId] = [chartOfAccountCategory][companyId]
         AND         [chartOfAccountType].[chartOfAccountCategoryId] = [chartOfAccountCategory][chartOfAccountCategoryId]
         WHERE       [chartOfAccountType].[isActive]  =   1
         AND         [chartOfAccountType].[companyId] =   '" . $this->getCompanyId() . "'";
                if ($chartOfAccountCategoryId) {
                    $sql .= "
			AND [chartOfAccountType].[chartOfAccountCategoryId]	=	'" . $chartOfAccountCategoryId . "'";
                }
                $sql .= "

         ORDER BY     [chartOfAccountType].[chartOfAccountTypeDescription],
                      [chartOfAccountCategory][chartOfAccountCategoryDescription]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      CHARTOFACCOUNTTYPE.CHARTOFACCOUNTTYPEID AS \"chartOfAccountTypeId\",
                     CHARTOFACCOUNTTYPE.CHARTOFACCOUNTTYPEDESCRIPTION AS \"chartOfAccountTypeDescription\",
                     CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYDESCRIPTION AS \"chartOfAccountCategoryDescription\"
         FROM        CHARTOFACCOUNTTYPE
         WHERE       CHARTOFACCOUNTTYPE.ISACTIVE    =   1
         AND         CHARTOFACCOUNTTYPE.COMPANYID   =   '" . $this->getCompanyId() . "'";
                    if ($chartOfAccountCategoryId) {
                        $sql .= "
			AND 	CHARTOFACCOUNTTYPE.CHARTOFACCOUNTCATEGORYID	=	'" . $chartOfAccountCategoryId . "'";
                    }
                    $sql .= "

         ORDER BY    CHARTOFACCOUNTTYPE.CHARTOFACCOUNTTYPEDESCRIPTION,
                     CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYDESCRIPTION  ";
                } else {
                    header('Content-Type:application/json; charset=utf-8');
                    echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
                    exit();
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
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['chartOfAccountTypeId'] . "'>" . $d . ". " . $row['chartOfAccountTypeDescription'] . "</option>";
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
     * Return Flag either wanted automatic posting or batch
     * @return int
     */
    public function getIsPosting() {
        return $this->isPosting;
    }

    /**
     * Set Flag either wanted automatic posting or batch
     * @param int $isPosting
     * @return $this
     */
    public function setIsPosting($isPosting) {
        $this->isPosting = $isPosting;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCurrencyCode() {
        return $this->countryCurrencyCode;
    }

    /**
     * @param string $countryCurrencyCode
     */
    public function setCountryCurrencyCode($countryCurrencyCode) {
        $this->countryCurrencyCode = $countryCurrencyCode;
    }

    /**
     * @return string
     */
    public function getCountryDescription() {
        return $this->countryDescription;
    }

    /**
     * @param string $countryDescription
     */
    public function setCountryDescription($countryDescription) {
        $this->countryDescription = $countryDescription;
    }

    /**
     * @return int
     */
    public function getCountryId() {
        return $this->countryId;
    }

    /**
     * @param int $countryId
     */
    public function setCountryId($countryId) {
        $this->countryId = $countryId;
    }

    /**
     * @return string
     */
    public function getTransactionTypeCode() {
        return $this->transactionTypeCode;
    }

    /**
     * @param string $transactionTypeCode
     */
    public function setTransactionTypeCode($transactionTypeCode) {
        $this->transactionTypeCode = $transactionTypeCode;
    }

    /**
     * @return string
     */
    public function getTransactionTypeDescription() {
        return $this->transactionTypeDescription;
    }

    /**
     * @param string $transactionTypeDescription
     */
    public function setTransactionTypeDescription($transactionTypeDescription) {
        $this->transactionTypeDescription = $transactionTypeDescription;
    }

    /**
     * @return int
     */
    public function getTransactionTypeId() {
        return $this->transactionTypeId;
    }

    /**
     * @param int $transactionTypeId
     */
    public function setTransactionTypeId($transactionTypeId) {
        $this->transactionTypeId = $transactionTypeId;
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