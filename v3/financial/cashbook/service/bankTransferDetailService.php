<?php

namespace Core\Financial\Cashbook\BankTransferDetail\Service;

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
 * Class BankTransferDetailService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\Cashbook\BankTransferDetail\Service
 * @subpackage Cashbook
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class BankTransferDetailService extends ConfigClass {

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
     * Financial Year Id
     * @var int
     */
    private $financeYearId;

    /**
     * Financial Year
     * @var int
     */
    private $financeYearYear;

    /**
     * Financial Period Id
     * @var int
     */
    private $financePeriodRangeId;

    /**
     * Financial Period
     * @var int
     */
    private $financePeriodRangePeriod;

    /**
     * Chart Of Account Category Id
     * @var int
     */
    private $chartOfAccountCategoryId;

    /**
     * Chart Of Account Type Id
     * @var int
     */
    private $chartOfAccountTypeId;

    /**
     * Chart Of Account Id
     * @var int
     */
    private $chartOfAccountId;

    /**
     * Chart Of Account Category Description
     * @var string
     */
    private $chartOfAccountCategoryCode;

    /**
     * Chart Of Account Category Description
     * @var string
     */
    private $chartOfAccountCategoryDescription;

    /**
     * Chart Of Account Type Code
     * @var string
     */
    private $chartOfAccountTypeCode;

    /**
     * Chart Of Account Type Description
     * @var string
     */
    private $chartOfAccountTypeDescription;

    /**
     * Chart Of Account Category Description
     * @var string
     */
    private $chartOfAccountDescription;

    /**
     * Chart Of Account Description
     * @var string
     */
    private $chartOfAccountNumber;

    /**
     * Company Name
     * @var string
     */
    private $businessPartnerCompany;

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
        } else if ($this->getVendor() == self::MSSQL) {
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
        } else if ($this->getVendor() == self::ORACLE) {
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
            $this->setFinanceYearId($row['financeYearId']);
        }
    }

    /**
     * Return Bank Transfer
     * @return array|string
     */
    public function getBankTransfer() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `bankTransferId`,
                     `bankTransferDescription`
         FROM        `banktransfer`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [bankTransferId],
                     [bankTransferDescription]
         FROM        [bankTransfer]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      BANKTRANSFERID AS \"bankTransferId\",
                     BANKTRANSFERDESCRIPTION AS \"bankTransferDescription\"
         FROM        BANKTRANSFER
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
                    $str .= "<option value='" . $row['bankTransferId'] . "'>" . $d . ". " . $row['bankTransferDescription'] . "</option>";
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
     * Return Bank Transfer Default Value
     * @return int
     */
    public function getBankTransferDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $bankTransferId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `bankTransferId`
         FROM        	`banktransfer`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [bankTransferId],
         FROM        [bankTransfer]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      BANKTRANSFERID AS \"bankTransferId\",
         FROM        BANKTRANSFER
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
            $bankTransferId = $row['bankTransferId'];
        }
        return $bankTransferId;
    }

    /**
     * Return Bank
     * @return array|string
     */
    public function getBank() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `bankId`,
                     `bankDescription`
         FROM        `bank`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [bankId],
                     [bankDescription]
         FROM        [bank]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      BANKID AS \"bankId\",
                     BANKDESCRIPTION AS \"bankDescription\"
         FROM        BANK
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
                    $str .= "<option value='" . $row['bankId'] . "'>" . $d . ". " . $row['bankDescription'] . "</option>";
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
     * Return Bank Default Value
     * @return int
     */
    public function getBankDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $bankId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `bankId`
         FROM        	`bank`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [bankId],
         FROM        [bank]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      BANKID AS \"bankId\",
         FROM        BANK
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
            $bankId = $row['bankId'];
        }
        return $bankId;
    }

    /**
     * Return Country
     * @return array|string
     */
    public function getCountry() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `countryId`,
                     `countryDescription`
         FROM        `country`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [countryId],
                     [countryDescription]
         FROM        [country]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      COUNTRYID AS \"countryId\",
                     COUNTRYDESCRIPTION AS \"countryDescription\"
         FROM        COUNTRY
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
                    $str .= "<option value='" . $row['countryId'] . "'>" . $d . ". " . $row['countryDescription'] . "</option>";
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
     * Return Country Default Value
     * @return int
     */
    public function getCountryDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $countryId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `countryId`
         FROM        	`country`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [countryId],
         FROM        [country]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      COUNTRYID AS \"countryId\",
         FROM        COUNTRY
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
            $countryId = $row['countryId'];
        }
        return $countryId;
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
            } else  if ($this->getVendor() == self::ORACLE) {
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
        } else  if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [chartOfAccountId],
         FROM        [chartOfAccount]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else  if ($this->getVendor() == self::ORACLE) {
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
     * Return Trial Balance Is correct or not before posting to journal . SUM ASSET  account - LIABILITY accounts + INCOME - Expenses + Return Earning Accounts
     * @param int $bankTransferId Bank Transfer Primary Key
     * @return string $trialBalance
     */
    private function getCheckTrialBalance($bankTransferId) {
        $trialBalance = 0;
        $sql = null;

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT (
                (
                    SELECT      SUM(`bankTransferDetailAmount`)
                    FROM        `banktransferdetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `banktransferdetail`.`companyId`                         	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `banktransferdetail`.`collectionId`                         	IN   (" . $bankTransferId . ")
                    AND         `banktransferdetail`.`isActive`                          	=   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode`	=   '" . self::ASSET . "'

                    GROUP BY    `banktransferdetail`.`bankTransferDetailAmount`
                )
                -
                 (
                    SELECT      SUM(`bankTransferDetailAmount`)
                    FROM        `banktransferdetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `banktransferdetail`.`companyId`                        	 	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `banktransferdetail`.`collectionId`                         	IN   (" . $bankTransferId . ")
                    AND         `banktransferdetail`.`isActive`                          	=   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode`	=	'" . self::LIABILITY . "'
                    GROUP BY    `banktransferdetail`.`bankTransferDetailAmount`
                )
                 +
                 (
                    SELECT      SUM(`bankTransferDetailAmount`)
                    FROM        `banktransferdetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `banktransferdetail`.`companyId`                         	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `banktransferdetail`.`collectionId`                         	IN  (" . $bankTransferId . ")
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` 	=   '" . self::EQUITY . "'
                    AND         `banktransferdetail`.`isActive`                          	=   1
                    GROUP BY    `banktransferdetail`.`bankTransferDetailAmount`
                ) +
                 (
                    SELECT      SUM(`bankTransferDetailAmount`)
                    FROM        `paymentvoucher`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `banktransferdetail`.`companyId`                         	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `banktransferdetail`.`collectionId`                         	IN   (" . $bankTransferId . ")
                    AND         `banktransferdetail`.`isActive`                          	=   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` 	=   '" . self::INCOME . "'
                    GROUP BY    `banktransferdetail`.`bankTransferDetailAmount`
                )
                 -
                 (
                    SELECT      SUM(`bankTransferDetailAmount`)
                    FROM        `banktransferdetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `banktransferdetail`.`companyId`                         	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `banktransferdetail`.`collectionId`                         	IN   (" . $bankTransferId . ")
                    AND         `banktransferdetail`.`isActive`                          	=   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode`	=	'" . self::EXPENSES . "'
                    GROUP BY    `banktransferdetail`.`bankTransferDetailAmount`
                )
            ) as `total`

            )";
        } else  if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT (
                (
                    SELECT      SUM([bankTransferDetailAmount])
                    FROM        [bankTransferDetail]

                    JOIN        [chartOfAccount]
                    ON          [bankTransferDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [bankTransferDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [bankTransferDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [bankTransferDetail].[companyId]                         =   '" . $this->getCompanyId(
                        ) . "'
                    AND         [bankTransferDetail].[collectionId]                         IN  (" . $bankTransferId . ")
                    AND         [bankTransferDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::ASSET . "'
                    GROUP BY    [bankTransferDetail].[bankTransferDetailAmount]
                )
                -
                 (
                    SELECT      SUM([bankTransferDetailAmount])
                    FROM        [bankTransferDetail]

                    JOIN        [chartOfAccount]
                    ON          [bankTransferDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [bankTransferDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [bankTransferDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [bankTransferDetail].[companyId]                         =   '" . $this->getCompanyId(
                        ) . "'
                    AND         [bankTransferDetail].[collectionId]                         IN   (" . $bankTransferId . ")
                    AND         [bankTransferDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::LIABILITY . "'
                    GROUP BY    [bankTransferDetail].[bankTransferDetailAmount]
                )
                 +
                 (
                    SELECT      SUM([bankTransferDetailAmount])
                    FROM        [bankTransferDetail]

                    JOIN        [chartOfAccount]
                    ON          [bankTransferDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [bankTransferDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [bankTransferDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [bankTransferDetail].[companyId]                         =   '" . $this->getCompanyId(
                        ) . "'
                    AND         [bankTransferDetail].[collectionId]                         IN   (" . $bankTransferId . ")
                    AND         [bankTransferDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::EQUITY . "'
                    GROUP BY    [bankTransferDetail].[bankTransferDetailAmount]
                ) +
                 (
                    SELECT      SUM([bankTransferDetailAmount])
                    FROM        [bankTransferDetail]

                    JOIN        [chartOfAccount]
                    ON          [bankTransferDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [bankTransferDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartofaccountCategory]
                    ON          [bankTransferDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [bankTransferDetail].[companyId]                         =   '" . $this->getCompanyId(
                        ) . "'
                    AND         [bankTransferDetail].[collectionId]                         IN   (" . $bankTransferId . ")
                    AND         [bankTransferDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::INCOME . "'
                    GROUP BY    [bankTransferDetail].[bankTransferDetailAmount]
                )
                 -
                 (
                    SELECT      SUM([bankTransferDetailAmount])
                    FROM        [bankTransferDetail]

                    JOIN        [chartOfAccount]
                    ON          [bankTransferDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [bankTransferDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [bankTransferDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [bankTransferDetail].[companyId]                         =   '" . $this->getCompanyId(
                        ) . "'
                    AND         [bankTransferDetail].[collectionId]                         IN   (" . $bankTransferId . ")
                    AND         [bankTransferDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::EXPENSES . "'
                    GROUP BY    [bankTransferDetail].[bankTransferDetailAmount]
                )
            ) as [trialBalance]

            )";
            } else if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT (
                (
                    SELECT      SUM(JOURNALDETAILAMOUNT)
                    FROM        BANKTRANSFERDETAIL
                    JOIN        CHARTOFACCOUNT
                    ON          BANKTRANSFERDETAIL.COMPANYID                     	=   CHARTOFACCOUNT.COMPANYID
                    AND         BANKTRANSFERDETAIL.CHARTOFACCOUNTID             	=   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          BANKTRANSFERDETAIL.COMPANYID                    	=   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         	=   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       BANKTRANSFERDETAIL.COMPANYID                    	=   '" . $this->getCompanyId() . "'
                    AND         BANKTRANSFERDETAIL.BANKTRANSFERID                     	IN   (" . $bankTransferId . ")
                    AND         BANKTRANSFERDETAIL.ISACTIVE                      	=   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE 	=   '" . self::INCOME . "'
                    GROUP BY    BANKTRANSFERDETAIL.JOURNALDETAILAMOUNT
                )
                -
                 (
                    SELECT      SUM(BANKTRANSFERDETAILAMOUNT)
                    FROM        BANKTRANSFERDETAIL

                    JOIN        CHARTOFACCOUNT
                    ON          BANKTRANSFERDETAIL.COMPANYID                     	=   CHARTOFACCOUNT.COMPANYID
                    AND         BANKTRANSFERDETAIL.CHARTOFACCOUNTID          		=   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          BANKTRANSFERDETAIL.COMPANYID                      =   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         	=	CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       BANKTRANSFERDETAIL.COMPANYID                     	=   '" . $this->getCompanyId() . "'
                    AND         BANKTRANSFERDETAIL.BANKTRANSFERID                     	IN   (" . $bankTransferId . ")
                    AND         BANKTRANSFERDETAIL.ISACTIVE                       =   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE	=	'" . self::LIABILITY . "'
                    GROUP BY    BANKTRANSFERDETAIL.JOURNALDETAILAMOUNT
                )
                 +
                 (
                    SELECT      SUM(BANKTRANSFERDETAILAMOUNT)
                    FROM        BANKTRANSFERDETAIL
                    JOIN        CHARTOFACCOUNT
                    ON          BANKTRANSFERDETAIL.COMPANYID                     	=   CHARTOFACCOUNT.COMPANYID
                    AND         BANKTRANSFERDETAIL.CHARTOFACCOUNTID              	=   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          BANKTRANSFERDETAIL.COMPANYID                    	=   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         	=   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTID

                    WHERE       BANKTRANSFERDETAIL.COMPANYID                    	=   '" . $this->getCompanyId() . "'
                    AND         BANKTRANSFERDETAIL.BANKTRANSFERID                    	IN   (" . $bankTransferId . ")
                    AND         BANKTRANSFERDETAIL.ISACTIVE                      	=   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE 	=   '" . self::EQUITY . "'
                    GROUP BY    BANKTRANSFERDETAIL.BANKTRANSFERDETAILAMOUNT
                ) +
                 (
                    SELECT      SUM(BANKTRANSFERDETAILAMOUNT)
                    FROM        PAYMENTVOUCHER

                    JOIN        CHARTOFACCOUNT
                    ON          BANKTRANSFERDETAIL.COMPANYID 						= 	CHARTOFACCOUNT.COMPANYID
                    AND         BANKTRANSFERDETAIL.CHARTOFACCOUNTID 				= 	CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          BANKTRANSFERDETAIL.COMPANYID                     	=   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         	=   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       BANKTRANSFERDETAIL.COMPANYID                    	=   '" . $this->getCompanyId() . "'
                    AND         BANKTRANSFERDETAIL.BANKTRANSFERID                    	IN   (" . $bankTransferId . ")
                    AND         BANKTRANSFERDETAIL.ISACTIVE                      	=   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE	=   '" . self::INCOME . "'
                    GROUP BY    BANKTRANSFERDETAIL.BANKTRANSFERDETAILAMOUNT
                )
                 -
                 (
                    SELECT      SUM(BANKTRANSFERDETAILAMOUNT)
                    FROM        BANKTRANSFERDETAIL

                    JOIN        CHARTOFACCOUNT
                    ON          BANKTRANSFERDETAIL.COMPANYID                     	=   CHARTOFACCOUNT.COMPANYID
                    AND         BANKTRANSFERDETAIL.CHARTOFACCOUNTID             	=   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          BANKTRANSFERDETAIL.COMPANYID                    	=   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID        		=   CHARTOFACCOUNTCATEGORY..CHARTOFACCOUNTCATEGORYID

                    WHERE       BANKTRANSFERDETAIL.COMPANYID                      =   '" . $this->getCompanyId() . "'
                    AND         BANKTRANSFERDETAIL.BANKTRANSFERID                    	IN   (" . $bankTransferId . ")
                    AND         BANKTRANSFERDETAIL.ISACTIVE                     	=   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE	=	'" . self::EXPENSES . "'
                    GROUP BY    BANKTRANSFERDETAIL.BANKTRANSFERDETAILAMOUNT
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
     * Return Total Payment Voucher Amount
     * @param int $bankTransferId Bank Transfer Primary Key
     * @param string $type 1->debit,2->credit
     * @return double $total
     */
    public function getTotalBankTransferAmount($bankTransferId, $type) {
        $total = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      SUM(`bankTransferDetailAmount`) AS `total`
            FROM        `banktransferdetail`

            WHERE       `banktransferdetail`.`companyId`		=   '" . $this->getCompanyId() . "'
            AND         `banktransferdetail`.`bankTransferId`	IN   (" . $bankTransferId . ")
            AND         `banktransferdetail`.`isActive`			=   1";
            if ($type == 1) {
                $sql .= "  AND `bankTransferDetailAmount` >0 ";
            } else {
                $sql .= "  AND `bankTransferDetailAmount` < 0 ";
            }
            // $sql .= "
            //  GROUP BY    `journaldetail`.`journalDetailAmount`
            //  ";
        } else  if ($this->getVendor() == self::MSSQL) {
                $sql .= "
            SELECT      SUM([bankTransferDetailAmount]) AS [total]
            FROM        [bankTransferDetail]

            WHERE       [bankTransferDetail].[companyId]                         =   '" . $this->getCompanyId() . "'
            AND         [bankTransferDetail].[bankTransferId]                         IN   (" . $bankTransferId . ")
            AND         [bankTransferDetail].[isActive]                          =   1";
                if ($type == 1) {
                    $sql .= "  AND [bankTransferDetailAmount] >0 ";
                } else {
                    $sql .= "  AND [bankTransferDetailAmount] < 0 ";
                }
                $sql .= "
            GROUP BY    [bankTransferDetail].[bankTransferDetailAmount]";
            } else  if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT      SUM(BANKTRANSFERDETAILAMOUNT) AS \"total\"
            FROM        BANKTRANSFERDETAIL

            WHERE       BANKTRANSFERDETAIL.COMPANYID		=   '" . $this->getCompanyId() . "'
            AND         BANKTRANSFERDETAIL.BANKTRANSFERID	IN   (" . $bankTransferId . ")
            AND         BANKTRANSFERDETAIL.ISACTIVE			=   1";
                    if ($type == 1) {
                        $sql .= "  AND BANKTRANSFERDETAILAMOUNT >0 ";
                    } else {
                        $sql .= "  AND BANKTRANSFERDETAILAMOUNT < 0 ";
                    }
                    $sql .= "
            GROUP BY    BANKTRANSFERDETAIL.BANKTRANSFERDETAILAMOUNT
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
     * Return Total debit,credit and trial balance
     * @param int $bankTransferId Bank Transfer Primary Key
     * @return array
     */
    public function getTotalBankTransferDetail($bankTransferId) {
        header('Content-Type:application/json; charset=utf-8');
        $totalDebit = $this->getTotalBankTransferAmount($bankTransferId, 1);
        $totalCredit = $this->getTotalBankTransferAmount($bankTransferId, 2);
        $trialBalance = $this->getCheckTrialBalance($bankTransferId);
        return array(
            "success" => true,
            "totalDebit" => $totalDebit,
            "totalCredit" => $totalCredit,
            "trialBalance" => $trialBalance
        );
    }

    /**
     * Set Business Partner Company
     * @param string $businessPartnerCompany
     * @return $this
     */
    public function setBusinessPartnerCompany($businessPartnerCompany) {
        $this->businessPartnerCompany = $businessPartnerCompany;
        return $this;
    }

    /**
     * Set Business Partner Company
     * @return string
     */
    public function getBusinessPartnerCompany() {
        return $this->businessPartnerCompany;
    }

    /**
     * Set Chart Of Account Category Code
     * @param string $chartOfAccountCategoryCode
     * @return $this
     */
    public function setChartOfAccountCategoryCode($chartOfAccountCategoryCode) {
        $this->chartOfAccountCategoryCode = $chartOfAccountCategoryCode;
        return $this;
    }

    /**
     * Return Chart Of Account Category Code
     * @return string
     */
    public function getChartOfAccountCategoryCode() {
        return $this->chartOfAccountCategoryCode;
    }

    /**
     * Return Chart Of Account Category Description
     * @param string $chartOfAccountCategoryDescription
     * @return $this
     */
    public function setChartOfAccountCategoryDescription($chartOfAccountCategoryDescription) {
        $this->chartOfAccountCategoryDescription = $chartOfAccountCategoryDescription;
        return $this;
    }

    /**
     * Set Chart Of Account Category Description
     * @return string
     */
    public function getChartOfAccountCategoryDescription() {
        return $this->chartOfAccountCategoryDescription;
    }

    /**
     * Return Chart Of Account Category Primary Key
     * @param int $chartOfAccountCategoryId
     * @return $this
     */
    public function setChartOfAccountCategoryId($chartOfAccountCategoryId) {
        $this->chartOfAccountCategoryId = $chartOfAccountCategoryId;
        return $this;
    }

    /**
     * Set Chart Of Account Category Primary Key
     * @return int
     */
    public function getChartOfAccountCategoryId() {
        return $this->chartOfAccountCategoryId;
    }

    /**
     * Return Chart Chart Of Account Category Description
     * @param string $chartOfAccountDescription
     * @return $this
     */
    public function setChartOfAccountDescription($chartOfAccountDescription) {
        $this->chartOfAccountDescription = $chartOfAccountDescription;
        return $this;
    }

    /**
     * Return Chart Of Account Description
     * @return string
     */
    public function getChartOfAccountDescription() {
        return $this->chartOfAccountDescription;
    }

    /**
     * Set Chart Of Account Primary Key
     * @param int $chartOfAccountId
     * @return $this
     */
    public function setChartOfAccountId($chartOfAccountId) {
        $this->chartOfAccountId = $chartOfAccountId;
        return $this;
    }

    /**
     * Return Chart Of Account Primary Key
     * @return int
     */
    public function getChartOfAccountId() {
        return $this->chartOfAccountId;
    }

    /**
     * Return Chart Of Account Number
     * @param string $chartOfAccountNumber
     * @return $this
     */
    public function setChartOfAccountNumber($chartOfAccountNumber) {
        $this->chartOfAccountNumber = $chartOfAccountNumber;
        return $this;
    }

    /**
     * Set Chart Of Account Number
     * @return string
     */
    public function getChartOfAccountNumber() {
        return $this->chartOfAccountNumber;
    }

    /**
     * Return Chart Of Account Type Code
     * @param string $chartOfAccountTypeCode
     * @return $this
     */
    public function setChartOfAccountTypeCode($chartOfAccountTypeCode) {
        $this->chartOfAccountTypeCode = $chartOfAccountTypeCode;
        return $this;
    }

    /**
     * Set Chart Of Account Type Code
     * @return string
     */
    public function getChartOfAccountTypeCode() {
        return $this->chartOfAccountTypeCode;
    }

    /**
     * Set Chart Of Account Type Description
     * @param string $chartOfAccountTypeDescription
     * @return $this
     */
    public function setChartOfAccountTypeDescription($chartOfAccountTypeDescription) {
        $this->chartOfAccountTypeDescription = $chartOfAccountTypeDescription;
        return $this;
    }

    /**
     * Set Chart Of Account Type Description
     * @return string
     */
    public function getChartOfAccountTypeDescription() {
        return $this->chartOfAccountTypeDescription;
    }

    /**
     * Return Chart Of Account Type Primary Key
     * @param int $chartOfAccountTypeId
     * @return $this
     */
    public function setChartOfAccountTypeId($chartOfAccountTypeId) {
        $this->chartOfAccountTypeId = $chartOfAccountTypeId;
        return $this;
    }

    /**
     * Set Chart Of Account Type Primary Key
     * @return int
     */
    public function getChartOfAccountTypeId() {
        return $this->chartOfAccountTypeId;
    }

    /**
     * Return Country Currency Code
     * @param string $countryCurrencyCode
     * @return $this
     */
    public function setCountryCurrencyCode($countryCurrencyCode) {
        $this->countryCurrencyCode = $countryCurrencyCode;
        return $this;
    }

    /**
     * Set Country Currency Code
     * @return string
     */
    public function getCountryCurrencyCode() {
        return $this->countryCurrencyCode;
    }

    /**
     * Return Country Description
     * @param string $countryDescription
     * @return $this
     */
    public function setCountryDescription($countryDescription) {
        $this->countryDescription = $countryDescription;
        return $this;
    }

    /**
     * Set Country Description
     * @return string
     */
    public function getCountryDescription() {
        return $this->countryDescription;
    }

    /**
     * Return Country Primary Key
     * @param int $countryId
     * @return $this
     */
    public function setCountryId($countryId) {
        $this->countryId = $countryId;
        return $this;
    }

    /**
     * Set Country Primary Key
     * @return int
     */
    public function getCountryId() {
        return $this->countryId;
    }

    /**
     * Return Finance Period Range Primary Key
     * @param int $financePeriodRangeId
     * @return $this
     */
    public function setFinancePeriodRangeId($financePeriodRangeId) {
        $this->financePeriodRangeId = $financePeriodRangeId;
        return $this;
    }

    /**
     * Set Finance Period Range Primary Key
     * @return int
     */
    public function getFinancePeriodRangeId() {
        return $this->financePeriodRangeId;
    }

    /**
     * Return Finance Period
     * @param int $financePeriodRangePeriod
     * @return $this
     */
    public function setFinancePeriodRangePeriod($financePeriodRangePeriod) {
        $this->financePeriodRangePeriod = $financePeriodRangePeriod;
        return $this;
    }

    /**
     * Set Finance Period
     * @return int
     */
    public function getFinancePeriodRangePeriod() {
        return $this->financePeriodRangePeriod;
    }

    /**
     * Return Chart Of Account Category Code
     * @param int $financeYearId
     * @return $this
     */
    public function setFinanceYearId($financeYearId) {
        $this->financeYearId = $financeYearId;
        return $this;
    }

    /**
     * Set Finance Year Primary Key
     * @return int
     */
    public function getFinanceYearId() {
        return $this->financeYearId;
    }

    /**
     * Return Finance Year Primary Key
     * @param int $financeYearYear
     * @return $this
     */
    public function setFinanceYearYear($financeYearYear) {
        $this->financeYearYear = $financeYearYear;
        return $this;
    }

    /**
     * Set Finance Year
     * @return int
     */
    public function getFinanceYearYear() {
        return $this->financeYearYear;
    }

    /**
     * Return Transaction Type Code . E.g D->debit,C->credit
     * @param string $transactionTypeCode
     * @return $this
     */
    public function setTransactionTypeCode($transactionTypeCode) {
        $this->transactionTypeCode = $transactionTypeCode;
        return $this;
    }

    /**
     * Set Transaction Type Code . E.g D->debit,C->credit
     * @return string
     */
    public function getTransactionTypeCode() {
        return $this->transactionTypeCode;
    }

    /**
     * Return Transaction Type Description . E.g Debit,Credit,Debit Note Outward
     * @param string $transactionTypeDescription
     * @return $this
     */
    public function setTransactionTypeDescription($transactionTypeDescription) {
        $this->transactionTypeDescription = $transactionTypeDescription;
        return $this;
    }

    /**
     * Set Transaction Type Description . E.g Debit,Credit,Debit Note Outward
     * @return string
     */
    public function getTransactionTypeDescription() {
        return $this->transactionTypeDescription;
    }

    /**
     * Return Transaction Type
     * @param int $transactionTypeId
     * @return $this
     */
    public function setTransactionTypeId($transactionTypeId) {
        $this->transactionTypeId = $transactionTypeId;
        return $this;
    }

    /**
     * Set Transaction Type
     * @return int
     */
    public function getTransactionTypeId() {
        return $this->transactionTypeId;
    }

    /**
     * Create
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