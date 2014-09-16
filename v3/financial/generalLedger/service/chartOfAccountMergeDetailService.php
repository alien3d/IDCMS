<?php

namespace Core\Financial\GeneralLedger\ChartOfAccountMergeDetail\Service;

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
 * Class ChartOfAccountMergeDetailService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\GeneralLedger\ChartOfAccountMergeDetail\Service
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ChartOfAccountMergeDetailService extends ConfigClass {

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
     * Return ChartOfAccountMerge
     * @return array|string
     */
    public function getChartOfAccountMerge() {
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
        } else {
            if ($this->getVendor() == self::MSSQL) {
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
            } else {
                if ($this->getVendor() == self::ORACLE) {
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
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $chartOfAccountTypeDescription = null;
        if ($result) {
            $d = 0;
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
                    $str .= "<option value='" . $row['chartOfAccountId'] . "'>" . $row['chartOfAccountNumber'] . ". " . $row['chartOfAccountTitle'] . "</option>";
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
     * Return ChartOfAccountMerge Default Value
     * @return int
     */
    public function getChartOfAccountMergeDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $chartOfAccountMergeId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `chartOfAccountMergeId`
         FROM        	`chartofaccountmerge`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [chartOfAccountMergeId],
         FROM        [chartOfAccountMerge]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      CHARTOFACCOUNTMERGEID AS \"chartOfAccountMergeId\",
         FROM        CHARTOFACCOUNTMERGE
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
            $chartOfAccountMergeId = $row['chartOfAccountMergeId'];
        }
        return $chartOfAccountMergeId;
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
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [chartOfAccountId],
         FROM        [chartOfAccount]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
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
            $chartOfAccountId = $row['chartOfAccountId'];
        }
        return $chartOfAccountId;
    }

    /**
     * Return Total debit,credit and trial balance
     * @param int $chartOfAccountMergeId
     * @return array
     */
    public function getTotalChartOfAccountMergeDetail($chartOfAccountMergeId) {
        header('Content-Type:application/json; charset=utf-8');
        $totalDebit = $this->getTotalChartOfAccountMergeAmount($chartOfAccountMergeId, 1);
        $totalCredit = $this->getTotalChartOfAccountMergeAmount($chartOfAccountMergeId, 2);
        $trialBalance = $this->getCheckTrialBalance($chartOfAccountMergeId);
        if ($trialBalance == 0 || $trialBalance == '0.00') {
            $this->setUpdateTrialBalance($chartOfAccountMergeId, 1);
        } else {
            $this->setUpdateTrialBalance($chartOfAccountMergeId, 0);
        }
        return array(
            "success" => true,
            "totalDebit" => $totalDebit,
            "totalCredit" => $totalCredit,
            "trialBalance" => $trialBalance
        );
    }

    /**
     * Return Total Journal Amount
     * @param int $chartOfAccountMergeId Main Table
     * @param string $type 1->debit,2->credit
     * @return double $total
     */
    public function getTotalChartOfAccountMergeAmount($chartOfAccountMergeId, $type) {
        $total = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      SUM(`chartOfAccountMergeDetailAmount`) AS `total`
            FROM        `chartofaccountmergedetail`

            WHERE       `chartofaccountmergedetail`.`companyId`                         =   '" . $this->getCompanyId() . "'
            AND         `chartofaccountmergedetail`.`chartOfAccountMergeId`                         IN   (" . $chartOfAccountMergeId . ")
            AND         `chartofaccountmergedetail`.`isActive`                          =   1";
            if ($type == 1) {
                $sql .= "  AND `chartOfAccountMergeDetailAmount` >0 ";
            } else {
                $sql .= "  AND `chartOfAccountMergeDetailAmount` < 0 ";
            }
            //  $sql .= "
            //  GROUP BY    `chartofaccountmergedetail`.`chartOfAccountMergeDetailAmount`
            //  ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql .= "
            SELECT      SUM([chartOfAccountMergeDetailAmount]) AS [total]
            FROM        [chartOfAccountMergeDetail]

            WHERE       [chartOfAccountMergeDetail].[companyId]                         =   '" . $this->getCompanyId() . "'
            AND         [chartOfAccountMergeDetail].[chartOfAccountMergeId]                         IN   (" . $chartOfAccountMergeId . ")
            AND         [chartOfAccountMergeDetail].[isActive]                          =   1";
                if ($type == 1) {
                    $sql .= "  AND [chartOfAccountMergeDetailAmount] >0 ";
                } else {
                    $sql .= "  AND [chartOfAccountMergeDetailAmount] < 0 ";
                }
                $sql .= "
            GROUP BY    [chartOfAccountMergeDetail].[chartOfAccountMergeDetailAmount]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT      SUM(JOURNALDETAILAMOUNT) AS \"total\"
            FROM        CHARTOFACCOUNTMERGEDETAIL

            WHERE       CHARTOFACCOUNTMERGEDETAIL.COMPANYID                         =   '" . $this->getCompanyId() . "'
            AND         CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTMERGEID                         IN   (" . $chartOfAccountMergeId . ")
            AND         CHARTOFACCOUNTMERGEDETAIL.ISACTIVE                          =   1";
                    if ($type == 1) {
                        $sql .= "  AND CHARTOFACCOUNTMERGEDETAILAMOUNT >0 ";
                    } else {
                        $sql .= "  AND CHARTOFACCOUNTMERGEDETAILAMOUNT < 0 ";
                    }
                    $sql .= "
            GROUP BY    CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTMERGEDETAILAMOUNT
            ";
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
            $total = $row['total'];
        }
        return $total;
    }

    /**
     * Return Trial Balance Is correct or not before posting to chartOfAccountMerge . SUM ASSET  account - LIABILITY accounts + INCOME - Expenses + Return Earning Accounts
     * @param int $chartOfAccountMergeId
     * @return string $trialBalance
     */
    private function getCheckTrialBalance($chartOfAccountMergeId) {
        $trialBalance = 0;
        $sql = null;

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT (
                (
                    SELECT      COALESCE(SUM(`chartOfAccountMergeDetailAmount`),0)
                    FROM        `chartofaccountmergedetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `chartofaccountmergedetail`.`companyId`                         =   '" . $this->getCompanyId(
                    ) . "'
                    AND         `chartofaccountmergedetail`.`chartOfAccountMergeId`                         IN   (" . $chartOfAccountMergeId . ")
                    AND         `chartofaccountmergedetail`.`isActive`                          =   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryId` =   '" . self::ASSET . "'

                    /* GROUP BY    `chartofaccountmergedetail`.`chartOfAccountMergeDetailAmount` */
                )
                +
                 (
                    SELECT      COALESCE(SUM(`chartOfAccountMergeDetailAmount`),0)
                    FROM        `chartofaccountmergedetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `chartofaccountmergedetail`.`companyId`                         =   '" . $this->getCompanyId(
                    ) . "'
                    AND         `chartofaccountmergedetail`.`chartOfAccountMergeId`                         IN   (" . $chartOfAccountMergeId . ")
                    AND         `chartofaccountmergedetail`.`isActive`                          =   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryId` =   '" . self::LIABILITY . "'
                    /* GROUP BY    `chartofaccountmergedetail`.`chartOfAccountMergeDetailAmount` */
                )
                  +
                 (
                    SELECT      COALESCE(SUM(`chartOfAccountMergeDetailAmount`),0)
                    FROM        `chartofaccountmergedetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `chartofaccountmergedetail`.`companyId`                         =   '" . $this->getCompanyId(
                    ) . "'
                    AND         `chartofaccountmergedetail`.`chartOfAccountMergeId`                         IN   (" . $chartOfAccountMergeId . ")
                    AND         `chartofaccountmergedetail`.`isActive`                          =   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryId` =   '" . self::EQUITY . "'
                    /* GROUP BY    `chartofaccountmergedetail`.`chartOfAccountMergeDetailAmount` */
                )
                 +
                 (
                    SELECT      COALESCE(SUM(`chartOfAccountMergeDetailAmount`),0)
                    FROM        `chartofaccountmergedetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `chartofaccountmergedetail`.`companyId`                         =   '" . $this->getCompanyId(
                    ) . "'
                    AND         `chartofaccountmergedetail`.`chartOfAccountMergeId`                         IN   (" . $chartOfAccountMergeId . ")
                    AND         `chartofaccountmergedetail`.`isActive`                          =   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryId` =   '" . self::INCOME . "'
                    /* GROUP BY    `chartofaccountmergedetail`.`chartOfAccountMergeDetailAmount` */
                )
                 +
                 (
                    SELECT      COALESCE(SUM(`chartOfAccountMergeDetailAmount`),0)
                    FROM        `chartofaccountmergedetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `chartofaccountmergedetail`.`companyId`                         =   '" . $this->getCompanyId(
                    ) . "'
                    AND         `chartofaccountmergedetail`.`chartOfAccountMergeId`                         IN   (" . $chartOfAccountMergeId . ")
                    AND         `chartofaccountmergedetail`.`isActive`                          =   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryId` =   '" . self::EXPENSES . "'
                    /* GROUP BY    `chartofaccountmergedetail`.`chartOfAccountMergeDetailAmount` */
                )
            ) as `trialBalance`

            ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT (
                (
                    SELECT      SUM([chartOfAccountMergeDetailAmount])
                    FROM        [chartOfAccountMergeDetail]

                    JOIN        [chartOfAccount]
                    ON          [chartOfAccountMergeDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [chartOfAccountMergeDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [chartOfAccountMergeDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [chartOfAccountMergeDetail].[companyId]                         =   '" . $this->getCompanyId(
                        ) . "'
                    AND         [chartOfAccountMergeDetail].[chartOfAccountMergeId]                         IN  (" . $chartOfAccountMergeId . ")
                    AND         [chartOfAccountMergeDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryId] =   '" . self::ASSET . "'
                    GROUP BY    [chartOfAccountMergeDetail].[chartOfAccountMergeDetailAmount]
                )
                -
                 (
                    SELECT      SUM([chartOfAccountMergeDetailAmount])
                    FROM        [chartOfAccountMergeDetail]

                    JOIN        [chartOfAccount]
                    ON          [chartOfAccountMergeDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [chartOfAccountMergeDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [chartOfAccountMergeDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [chartOfAccountMergeDetail].[companyId]                         =   '" . $this->getCompanyId(
                        ) . "'
                    AND         [chartOfAccountMergeDetail].[chartOfAccountMergeId]                         IN   (" . $chartOfAccountMergeId . ")
                    AND         [chartOfAccountMergeDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryId] =   '" . self::LIABILITY . "'
                    GROUP BY    [chartOfAccountMergeDetail].[chartOfAccountMergeDetailAmount]
                )
                 +
                 (
                    SELECT      SUM([chartOfAccountMergeDetailAmount])
                    FROM        [chartOfAccountMergeDetail]

                    JOIN        [chartOfAccount]
                    ON          [chartOfAccountMergeDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [chartOfAccountMergeDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [chartOfAccountMergeDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [chartOfAccountMergeDetail].[companyId]                         =   '" . $this->getCompanyId(
                        ) . "'
                    AND         [chartOfAccountMergeDetail].[chartOfAccountMergeId]                         IN   (" . $chartOfAccountMergeId . ")
                    AND         [chartOfAccountMergeDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryId] =   '" . self::EQUITY . "'
                    GROUP BY    [chartOfAccountMergeDetail].[chartOfAccountMergeDetailAmount]
                ) +
                 (
                    SELECT      SUM([chartOfAccountMergeDetailAmount])
                    FROM        [chartOfAccountMergeDetail]

                    JOIN        [chartOfAccount]
                    ON          [chartOfAccountMergeDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [chartOfAccountMergeDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [chartOfAccountMergeDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [chartOfAccountMergeDetail].[companyId]                         =   '" . $this->getCompanyId(
                        ) . "'
                    AND         [chartOfAccountMergeDetail].[chartOfAccountMergeId]                         IN   (" . $chartOfAccountMergeId . ")
                    AND         [chartOfAccountMergeDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryId] =   '" . self::INCOME . "'
                    GROUP BY    [chartOfAccountMergeDetail].[chartOfAccountMergeDetailAmount]
                )
                 -
                 (
                    SELECT      SUM([chartOfAccountMergeDetailAmount])
                    FROM        [chartOfAccountMergeDetail]

                    JOIN        [chartOfAccount]
                    ON          [chartOfAccountMergeDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [chartOfAccountMergeDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [chartOfAccountMergeDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [chartOfAccountMergeDetail].[companyId]                         =   '" . $this->getCompanyId(
                        ) . "'
                    AND         [chartOfAccountMergeDetail].[chartOfAccountMergeId]                         IN   (" . $chartOfAccountMergeId . ")
                    AND         [chartOfAccountMergeDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryId] =   '" . self::EXPENSES . "'
                    GROUP BY    [chartOfAccountMergeDetail].[chartOfAccountMergeDetailAmount]
                )
            ) as [trialBalance]

            ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT (
                (
                    SELECT      SUM(CHARTOFACCOUNTMERGEDETAILAMOUNT)
                    FROM        CHARTOFACCOUNTMERGEDETAIL
                    JOIN        CHARTOFACCOUNT
                    ON          CHARTOFACCOUNTMERGEDETAIL.COMPANYID                         =   CHARTOFACCOUNT.COMPANYID
                    AND         CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTID                  =   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          CHARTOFACCOUNTMERGEDETAIL.COMPANYID                         =   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         =   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       CHARTOFACCOUNTMERGEDETAIL.COMPANYID                         =   '" . $this->getCompanyId(
                            ) . "'
                    AND         CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTMERGEID                         IN   (" . $chartOfAccountMergeId . ")
                    AND         CHARTOFACCOUNTMERGEDETAIL.ISACTIVE                          =   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID =   '" . self::INCOME . "'
                    GROUP BY    CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTMERGEDETAILAMOUNT
                )
                -
                 (
                    SELECT      SUM(CHARTOFACCOUNTMERGEDETAILAMOUNT)
                    FROM        CHARTOFACCOUNTMERGEDETAIL

                    JOIN        CHARTOFACCOUNT
                    ON          CHARTOFACCOUNTMERGEDETAIL.COMPANYID                         =   CHARTOFACCOUNT.COMPANYID
                    AND         CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTID                  =   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          CHARTOFACCOUNTMERGEDETAIL.COMPANYID                         =   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         =   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       CHARTOFACCOUNTMERGEDETAIL.COMPANYID                         =   '" . $this->getCompanyId(
                            ) . "'
                    AND         CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTMERGEID                         IN   (" . $chartOfAccountMergeId . ")
                    AND         CHARTOFACCOUNTMERGEDETAIL.ISACTIVE                          =   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID =   '" . self::LIABILITY . "'
                    GROUP BY    CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTMERGEDETAILAMOUNT
                )
                 +
                 (
                    SELECT      SUM(CHARTOFACCOUNTMERGEDETAILAMOUNT)
                    FROM        CHARTOFACCOUNTMERGEDETAIL
                    JOIN        CHARTOFACCOUNT
                    ON          CHARTOFACCOUNTMERGEDETAIL.COMPANYID                         =   CHARTOFACCOUNT.COMPANYID
                    AND         CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTID                  =   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          CHARTOFACCOUNTMERGEDETAIL.COMPANYID                         =   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         =   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTID

                    WHERE       CHARTOFACCOUNTMERGEDETAIL.COMPANYID                         =   '" . $this->getCompanyId(
                            ) . "'
                    AND         CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTMERGEID                         IN   (" . $chartOfAccountMergeId . ")
                    AND         CHARTOFACCOUNTMERGEDETAIL.ISACTIVE                          =   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID =   '" . self::EQUITY . "'
                    GROUP BY    CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTMERGEDETAILAMOUNT
                ) +
                 (
                    SELECT      SUM(CHARTOFACCOUNTMERGEDETAILAMOUNT)
                    FROM        CHARTOFACCOUNTMERGEDETAIL

                    JOIN        CHARTOFACCOUNT
                    ON          CHARTOFACCOUNTMERGEDETAIL.COMPANYID = CHARTOFACCOUNT.COMPANYID
                    AND         CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTID = CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          CHARTOFACCOUNTMERGEDETAIL.COMPANYID                         =   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         =   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       CHARTOFACCOUNTMERGEDETAIL.COMPANYID                         =   '" . $this->getCompanyId(
                            ) . "'
                    AND         CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTMERGEID                         IN   (" . $chartOfAccountMergeId . ")
                    AND         CHARTOFACCOUNTMERGEDETAIL.ISACTIVE                          =   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID =   '" . self::INCOME . "'
                    GROUP BY    CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTMERGEDETAILAMOUNT
                )
                 -
                 (
                    SELECT      SUM(CHARTOFACCOUNTMERGEDETAILAMOUNT)
                    FROM        CHARTOFACCOUNTMERGEDETAIL

                    JOIN        CHARTOFACCOUNT
                    ON          CHARTOFACCOUNTMERGEDETAIL.COMPANYID                         =   CHARTOFACCOUNT.COMPANYID
                    AND         CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTID                  =   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          CHARTOFACCOUNTMERGEDETAIL.COMPANYID                         =   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT..CHARTOFACCOUNTCATEGORYID        =   CHARTOFACCOUNTCATEGORY..CHARTOFACCOUNTCATEGORYID

                    WHERE       CHARTOFACCOUNTMERGEDETAIL.COMPANYID                         =   '" . $this->getCompanyId(
                            ) . "'
                    AND         CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTMERGEID                         IN   (" . $chartOfAccountMergeId . ")
                    AND         CHARTOFACCOUNTMERGEDETAIL.ISACTIVE                          =   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID =   '" . self::EXPENSES . "'
                    GROUP BY    CHARTOFACCOUNTMERGEDETAIL.CHARTOFACCOUNTMERGEDETAILAMOUNT
                )
            ) as [trialBalance]

            )";
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
            $trialBalance = $row['trialBalance'];
        }

        return $trialBalance;
    }

    /**
     * Update Trial Balance So  can be view In Journal Posting area/application/leaf
     * @param int $chartOfAccountMergeId
     * @param bool $value
     */
    private function setUpdateTrialBalance($chartOfAccountMergeId, $value) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE `chartofaccountmerge`
            SET    `isBalance`='" . $value . "'
            WHERE  `chartOfAccountMergeId`='" . $chartOfAccountMergeId . "'
            ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            UPDATE [chartOfAccountMerge]
            SET    [isBalance]='" . $value . "'
            WHERE  [chartOfAccountMergeId]='" . $chartOfAccountMergeId . "'
            ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            UPDATE  CHARTOFACCOUNTMERGE
            SET     ISBALANCE='" . $value . "'
            WHERE   CHARTOFACCOUNTMERGEID='" . $chartOfAccountMergeId . "'
            ";
                }
            }
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
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
     * @return int
     */
    public function getIsPosting() {
        return $this->isPosting;
    }

    /**
     * @param int $isPosting
     */
    public function setIsPosting($isPosting) {
        $this->isPosting = $isPosting;
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