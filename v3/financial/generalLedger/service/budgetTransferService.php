<?php

namespace Core\Financial\GeneralLedger\BudgetTransfer\Service;

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
 * Class BudgetTransferService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\GeneralLedger\BudgetTransfer\Service
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class BudgetTransferService extends ConfigClass {

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
     * Translation Array
     * @var mixed
     */
    public $translate;

    /**
     * System Format
     * @var array
     */
    public $systemFormatArray;

    /**
     * FinanceYearId
     * @var int
     */
    private $financeYearId;

    /**
     * Odd Month,don't follow roman month based on range
     * @var bool|int
     */
    private $isOddPeriod;

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
        $this->overrideFinancialYear();
    }

    /**
     * Return Default Financial Year Based On Financial Year Setting
     * @return void
     */
    public function overrideFinancialYear() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `financeYearId`,
                    `isOddPeriod`
            FROM    `financesetting`
            WHERE   `companyId`='" . $this->getCompanyId() . "'
            ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT  `financeYearId`,
                    `isOddPeriod`
            FROM    `financeSetting`
            WHERE   `companyId`='" . $this->getCompanyId() . "'
            ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT  FINANCEYEARID AS \"financeYearId\",
                    ISODDPERIOD AS \"isOddPeriod\"
            FROM    FINANCESETTING
            WHERE   COMPANYID='" . $this->getCompanyId() . "'
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
            $this->setFinanceYearId($row['financeYearId']);
            $this->setIsOddPeriod($row['isOddPeriod']);
        }
    }

    /**
     * Return Budget Transfer From.Budget Only Filter Profit And Loss. Income and Expenses only
     * @return array|string
     */
    public function getBudgetTransferFrom() {
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
			JOIN        `chartofaccountcategory`
            USING       (`companyId`,`chartOfAccountCategoryId`)
            JOIN        `chartofaccounttype`
            USING       (`companyId`,`chartOfAccountCategoryId`,`chartOfAccountTypeId`)
            WHERE       `chartofaccount`.`isActive`  =   1
            AND         `chartofaccount`.`companyId` =   '" . $this->getCompanyId() . "'
            AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` IN ('" . self::INCOME . "','" . self::EXPENSES . "')
			ORDER BY    `chartofaccount`.`chartOfAccountNumber`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT      [chartOfAccount].[chartOfAccountId],
                        [chartOfAccount].[chartOfAccountNumber],
                        [chartOfAccount].[chartOfAccountTitle],
                        [chartOfAccountType].[chartOfAccountTypeDescription]
            FROM        [chartOfAccount]

			JOIN        [chartOfAccountCategory]
            ON         [journalDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
            AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

			JOIN	    [chartOfAccountType]
            ON          [chartOfAccount].[companyId]   						= 	[chartOfAccountType].[companyId]
			AND         [chartOfAccount].[chartOfAccountCategoryId]   		=	[chartOfAccountType].[chartOfAccountCategoryId]
            AND         [chartOfAccount].[chartOfAccountTypeId]   			=	[chartOfAccountType].[chartOfAccountTypeId]

			WHERE       [chartOfAccount].[isActive]  =   1
            AND         [chartOfAccount].[companyId] =   '" . $this->getCompanyId() . "'
            AND         [chartOfAccountCategory][chartOfAccountCategoryCode] IN ('" . self::INCOME . "','" . self::EXPENSES . "')
			ORDER BY    [chartOfAccount].[chartOfAccountNumber]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT      CHARTOFACCOUNT.CHARTOFACCOUNTID AS \"chartOfAccountId\",
                        CHARTOFACCOUNT.CHARTOFACCOUNTNUMBER AS \"chartOfAccountNumber\",
                        CHARTOFACCOUNT.CHARTOFACCOUNTTITLE AS \"chartOfAccountTitle\",
                        CHARTOFACCOUNTTYPEDESCRIPTION  AS  \"chartOfAccountTypeDescription\"
            FROM        CHARTOFACCOUNT

			JOIN		CHARTOFACCOUNTCATEGORY
            ON          CHARTOFACCOUNT.COMPANYID               		=   CHARTOFACCOUNTCATEGORY.COMPANYID
			AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID    	=   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

			JOIN		CHARTOFACCOUNTTYPE
            ON          CHARTOFACCOUNT.COMPANYID               		=   CHARTOFACCOUNTTYPE.COMPANYID
			AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID    	=   CHARTOFACCOUNTTYPE.CHARTOFACCOUNTCATEGORYID
            AND         CHARTOFACCOUNT.CHARTOFACCOUNTTYPEID    		=   CHARTOFACCOUNTTYPE.CHARTOFACCOUNTTYPEID

			WHERE       CHARTOFACCOUNT.ISACTIVE    =   1
            AND         CHARTOFACCOUNT.COMPANYID   =   '" . $this->getCompanyId() . "'
            AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE IN ('" . self::INCOME . "','" . self::EXPENSES . "')
            ORDER BY    CHARTOFACCOUNT.CHARTOFACCOUNTNUMBER ";
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
     * Return BudgetTransferFrom Default Value.Budget Only Filter Profit And Loss. Income and Expenses only And Also filter budget which have been locked only
     * @return int
     */
    public function getBudgetTransferFromDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $chartOfAccountId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `chartOfAccountId`
         FROM        `chartofaccount`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	 `isDefault` =	  1
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
     * Return Budget Transfer To.Budget Only Filter Profit And Loss. Income and Expenses only  And Also filter budget which have been locked only
     * @return array|string
     */
    public function getBudgetTransferTo() {
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
			JOIN        `chartofaccountcategory`
            USING       (`companyId`,`chartOfAccountCategoryId`)
            JOIN        `chartofaccounttype`
            USING       (`companyId`,`chartOfAccountCategoryId`,`chartOfAccountTypeId`)
            WHERE       `chartofaccount`.`isActive`  =   1
            AND         `chartofaccount`.`companyId` =   '" . $this->getCompanyId() . "'
            AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` IN ('" . self::INCOME . "','" . self::EXPENSES . "')
			ORDER BY    `chartofaccount`.`chartOfAccountNumber`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT      [chartOfAccount].[chartOfAccountId],
                        [chartOfAccount].[chartOfAccountNumber],
                        [chartOfAccount].[chartOfAccountTitle],
                        [chartOfAccountType].[chartOfAccountTypeDescription]
            FROM        [chartOfAccount]

			JOIN        [chartOfAccountCategory]
            ON         [journalDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
            AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

			JOIN	    [chartOfAccountType]
            ON          [chartOfAccount].[companyId]   						= 	[chartOfAccountType].[companyId]
			AND         [chartOfAccount].[chartOfAccountCategoryId]   		=	[chartOfAccountType].[chartOfAccountCategoryId]
            AND         [chartOfAccount].[chartOfAccountTypeId]   			=	[chartOfAccountType].[chartOfAccountTypeId]

			WHERE       [chartOfAccount].[isActive]  =   1
            AND         [chartOfAccount].[companyId] =   '" . $this->getCompanyId() . "'
            AND         [chartOfAccountCategory][chartOfAccountCategoryCode] IN ('" . self::INCOME . "','" . self::EXPENSES . "')
			ORDER BY    [chartOfAccount].[chartOfAccountNumber]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT      CHARTOFACCOUNT.CHARTOFACCOUNTID AS \"chartOfAccountId\",
                        CHARTOFACCOUNT.CHARTOFACCOUNTNUMBER AS \"chartOfAccountNumber\",
                        CHARTOFACCOUNT.CHARTOFACCOUNTTITLE AS \"chartOfAccountTitle\",
                         CHARTOFACCOUNTTYPEDESCRIPTION  AS  \"chartOfAccountTypeDescription\"
            FROM        CHARTOFACCOUNT

			JOIN		CHARTOFACCOUNTCATEGORY
            ON          CHARTOFACCOUNT.COMPANYID               		=   CHARTOFACCOUNTCATEGORY.COMPANYID
			AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID    	=   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

			JOIN		CHARTOFACCOUNTTYPE
            ON          CHARTOFACCOUNT.COMPANYID               		=   CHARTOFACCOUNTTYPE.COMPANYID
			AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID    	=   CHARTOFACCOUNTTYPE.CHARTOFACCOUNTCATEGORYID
            AND         CHARTOFACCOUNT.CHARTOFACCOUNTTYPEID    		=   CHARTOFACCOUNTTYPE.CHARTOFACCOUNTTYPEID

			WHERE       CHARTOFACCOUNT.ISACTIVE    =   1
            AND         CHARTOFACCOUNT.COMPANYID   =   '" . $this->getCompanyId() . "'
            AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE IN ('" . self::INCOME . "','" . self::EXPENSES . "')
            ORDER BY    CHARTOFACCOUNT.CHARTOFACCOUNTNUMBER ";
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
     * Return BudgetTransferTo Default Value
     * @return int
     */
    public function getBudgetTransferToDefaultValue() {
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
     * Return Finance Year.Here transfer/virement only for current year
     * @return array|string
     */
    public function getFinanceYear() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        $financeYearId = $this->getFinanceYearId();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `financeYearId`,
                     `financeYearYear`
         FROM        `financeyear`
         WHERE       `isActive`  		=   1
         AND         `companyId` 		=   '" . $this->getCompanyId() . "'
		 AND		 `financeYearId`	=	'" . $financeYearId . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [financeYearId],
                     [financeYearYear]
         FROM        [financeYear]
         WHERE       [isActive]  		=   1
         AND         [companyId] 		=   '" . $this->getCompanyId() . "'
         AND		 [financeYearId]	=	'" . $financeYearId . "'
		 ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      FINANCEYEARID AS \"financeYearId\",
                     FINANCEYEARYEAR AS \"financeYearYear\"
         FROM        FINANCEYEAR
         WHERE       ISACTIVE    		=   1
         AND         COMPANYID   		=   '" . $this->getCompanyId() . "'
		 AND		 FINANCEYEARID		=	'" . $financeYearId . "'
         ORDER BY    ISDEFAULT";
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
                    $str .= "<option value='" . $row['financeYearId'] . "'>" . $d . ". " . $row['financeYearYear'] . "</option>";
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
     * Return Finance Year Primary Key
     * @return int
     */
    public function getFinanceYearId() {
        return $this->financeYearId;
    }

    /**
     * set Finance Year Primary Key
     * @param int $financeYearId
     * @return $this|ConfigClass
     */
    public function setFinanceYearId($financeYearId) {
        $this->financeYearId = $financeYearId;
        return $this;
    }

    /**
     * Return FinanceYear Default Value
     * @return int
     */
    public function getFinanceYearDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $financeYearId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `financeYearId`
         FROM        `financeyear`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [financeYearId],
         FROM        [financeYear]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      FINANCEYEARID AS \"financeYearId\",
         FROM        FINANCEYEAR
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
            $financeYearId = $row['financeYearId'];
        }
        return $financeYearId;
    }

    /**
     * Return Current Financial Year
     * @return int $financeYear
     */
    public function getFinancialYear() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $financeYear = 0;
        $financeYearId = $this->getFinanceYearId();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `financeYearId`,
                     `financeYearYear`
         FROM        `financeyear`
         WHERE       `isActive`  		=   1
         AND         `companyId` 		=   '" . $this->getCompanyId() . "'
         AND         `financeYearId`    =   '" . $financeYearId . "'
         ORDER BY    `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [financeYearId],
                     [financeYearYear]
         FROM        [financeYear]
         WHERE       [isActive]  		=   1
         AND         [companyId] 		=   '" . $this->getCompanyId() . "'
         AND         [financeYearId]    =   '" . $financeYearId . "'
         ORDER BY    [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      FINANCEYEARID AS \"financeYearId\",
                     FINANCEYEARYEAR AS \"financeYearYear\"
         FROM        FINANCEYEAR
         WHERE       ISACTIVE    		=   1
         AND         COMPANYID   		=   '" . $this->getCompanyId() . "'
         AND         FINANCEYEARID    	=   '" . $financeYearId . "'
         ORDER BY    ISDEFAULT";
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
            $financeYear = $row['financeYearYear'];
        }
        // fake return
        return $financeYear;
    }

    /**
     * Return Finance Period Range.
     * There are two type of transfer / virement. One upon current year .another year transfer upon next year or futured year.
     * @param null|int $financeYearId Financial Year E.g 2012
     * @return array
     */
    public function getFinancePeriodRange($financeYearId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if (!$financeYearId) {
            $financeYearId = $this->getFinanceYearId();
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      `financeperiodrange`.`financePeriodRangeId`,
                        `financeperiodrange`.`financePeriodRangePeriod`,
                        `financeperiodrange`.`financePeriodRangeStartDate`,
                        `financeperiodrange`.`financePeriodRangeEndDate`,
                        `financeyear`.`financeYearYear`
            FROM        `financeperiodrange`

            JOIN        `financeyear`
            USING       (`companyId`,`financeYearId`)

            WHERE       `financeperiodrange`.`isActive`         =   1
            AND         `financeperiodrange`.`companyId`        =   '" . $this->getCompanyId() . "'
            AND         `financeperiodrange`.`financeYearId`    =   '" . $financeYearId . "'
            ORDER BY    `financeyear`.`financeYearYear` ASC ,
                     `financeperiodrange`.`financePeriodRangePeriod` ASC;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT       [financePeriodRange].[financePeriodRangeId],
                         [financePeriodRange].[financePeriodRangePeriod],
                         [financePeriodRange].[financePeriodRangeStartDate],
                         [financePeriodRange].[financePeriodRangeEndDate],
                        [financeYear].[financeYearYear]
            FROM         [financePeriodRange]
            JOIN        [financeYear]
            ON          [financeYear].[companyId]           =    [financePeriodRange].[companyId]
            AND         [financeYear].[financeYearId]       =    [financePeriodRange].[financeYearId]
            WHERE        [financePeriodRange].[isActive]     =   1
            AND          [financePeriodRange].[companyId]    =   '" . $this->getCompanyId() . "'
            AND          [financePeriodRange].[financeYearId]    =   '" . $financeYearId . "'
            ORDER BY    [financeYear].[financeYearYear] ASC,
                         [financePeriodRange].[financePeriodRangePeriod] ASC ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT      FINANCEPERIODRANGE.FINANCEPERIODRANGEID AS \"financePeriodRangeId\",
                        FINANCEPERIODRANGE.FINANCEPERIODRANGEPERIOD AS \"financePeriodRangePeriod\",
                        FINANCEPERIODRANGE.FINANCEPERIODRANGESTARTDATE AS \"financePeriodRangeStartDate\",
                        FINANCEPERIODRANGE.FINANCEPERIODRANGEENDDATE AS \"financePeriodRangeEndDate\",
                        FINANCEYEAR.FINANCEYEARYEAR AS \"financeYearYear\"
            FROM        FINANCEPERIODRANGE
            JOIN        FINANCEYEAR
            ON          FINANCEYEAR.COMPANYID           =   FINANCEPERIODRANGE.COMPANYID
            AND         FINANCEYEAR.FINANCEYEARID       =   FINANCEPERIODRANGE.FINANCEYEARID
            WHERE       FINANCEPERIODRANGE.ISACTIVE     =   1
            AND         FINANCEPERIODRANGE.COMPANYID    =   '" . $this->getCompanyId() . "'
            AND         FINANCEPERIODRANGE.FINANCEYEARID    =   '" . $financeYearId . "'
            ORDER BY    FINANCEYEAR.FINANCEYEARYEAR ASC,
                        FINANCEPERIODRANGE.FINANCEPERIODRANGEPERIOD ASC";
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
                    if ($this->getIsOddPeriod() == 1) {
                        // appear range based on range period
                        $str .= "<option value='" . $row['financePeriodRangeId'] . "'>" . $d . ". [" . $row['financePeriodRangePeriod'] . "] -> [" . $row['financePeriodStartDate'] . " ~ " . $row['financePeriodEndDate'] . "]</option>";
                    } else {
                        $str .= "<option value='" . $row['financePeriodRangeId'] . "'>" . $d . ". [" . $row['financePeriodRangePeriod'] . "] -> [" . date(
                                        'F', mktime(0, 0, 0, $row['financePeriodRangePeriod'], 1, $row['financeYearYear'])
                                ) . "]</option>";
                    }
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
     * Return Is Odd Period.odd Period not 12 month period
     * @return bool|int
     */
    public function getIsOddPeriod() {
        return $this->isOddPeriod;
    }

    /**
     * Set Is Odd Period . odd Period not 12 month period
     * @param bool|int $isOddPeriod
     */
    public function setIsOddPeriod($isOddPeriod) {
        $this->isOddPeriod = $isOddPeriod;
    }

    /**
     * Return FinancePeriodRange Default Value
     * @return int
     */
    public function getFinancePeriodRangeDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $financePeriodRangeId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT      `financePeriodRangeId`
			FROM        	`financeperiodrange`
			WHERE       `isActive`  =   1
			AND         `companyId` =   '" . $this->getCompanyId() . "'
			AND    	  `isDefault` =	  1
			LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      TOP 1 [financePeriodRangeId],
			FROM         [financePeriodRange]
			WHERE       [isActive]  =   1
			AND         [companyId] =   '" . $this->getCompanyId() . "'
			AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT      FINANCEPERIODRANGEID AS \"financePeriodRangeId\",
			FROM        FINANCEPERIODRANGE
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
            $financePeriodRangeId = $row['financePeriodRangeId'];
        }
        return $financePeriodRangeId;
    }

    /**
     * Return Budget Amount / value
     * @param int $chartOfAccountId Chart Of Account Primary Key / Primary Key
     * @param null|int $financeYearId Financial Year E.g 1992 Or based On Date Ranged(futured version)
     * @return mixed
     */
    public function getBudgetAmountByYear($chartOfAccountId, $financeYearId = null) {
        $row = array();
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT	*
			FROM	`budget`
			LEFT OUTER JOIN	`chartofaccount`
			USING	(`companyId`,`chartOfAccountId`)
			JOIN	`financeyear`
			USING	(`companyId`,`financeYearId`)
			WHERE 	`budget`.`companyId`		=	'" . $this->getCompanyId() . "'
			AND		`budget`.`chartOfAccountId`	=	'" . $chartOfAccountId . "'
			";
            if ($financeYearId) {
                $sql .= " AND		`budget`.`financeYearId`	=	'" . $financeYearId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT	*
			FROM	[budget]
			JOIN	[chartOfAccount]
			ON		[budget].[companyId]			= 	[chartOfAccount].[companyId]
			AND		[budget].[chartOfAccountId]		= 	[chartOfAccount].[chartOfAccountId]
			JOIN	[financeYear]
			ON		[budget].[companyId]			= 	[financeYear].[companyId]
			AND		[budget].[financeYearId]		= 	[financeYear].[financeYearId]
			WHERE 	[budget].[companyId]			=	'" . $this->getCompanyId() . "'
			AND		[budget].[financeYearId]	    =	'" . $financeYearId . "'
			AND		[budget].[chartOfAccountId]		=	'" . $chartOfAccountId . "'
			";
            if ($financeYearId) {
                $sql .= " AND		[budget].[financeYearId]	=	'" . $financeYearId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT	*
			FROM	BUDGET
			JOIN	CHARTOFACCOUNT
			ON		BUDGET.COMPANYID			= 	CHARTOFACCOUNT.COMPANYID
			AND		BUDGET.CHARTOFACCOUNTID		= 	CHARTOFACCOUNT.CHARTOFACCOUNTID
			JOIN	FINANCEYEAR
			ON		BUDGET.COMPANYID			= 	FINANCEYEAR.COMPANYID
			AND		BUDGET.FINANCEYEARID		= 	FINANCEYEAR.FINANCEYEARID
			WHERE 	BUDGET.COMPANYID			=	'" . $this->getCompanyId() . "'
			AND		BUDGET.FINANCEYEARID	    =	'" . $financeYearId . "'
			AND		BUDGET.CHARTOFACCOUNTID		=	'" . $chartOfAccountId . "'
			";
            if ($financeYearId) {
                $sql .= " AND		BUDGET.FINANCEYEARID	=	'" . $financeYearId . "'";
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchAssoc($result);
        }
        return $row;
    }

    /**
     * Return Budget Amount / value
     * @param int $chartOfAccountId Chart Of Account Primary Key / Primary Key
     * @param int $financeYearId Financial Year E.g 1992 Or based On Date Ranged(futured version)
     * @param int $financePeriodRangeId Financial Period E.g 1->January or Based On Date Range
     * @return float $budgetAmount
     */
    public function getBudgetAmountByPeriod($chartOfAccountId, $financeYearId, $financePeriodRangeId) {
        $budgetAmount = 0;
        $sql = null;
        $fieldBudget = $this->getBudgetFieldName($this->getFinancialPeriod($financePeriodRangeId));
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT 	`" . strtolower($fieldBudget) . "` AS `total`
			FROM	`budget`
			JOIN	`chartofaccount`
			USING	(`companyId`,`chartOfAccountId`)
			WHERE 	`budget`.`companyId`			=	'" . $this->getCompanyId() . "'
			AND		`budget`.`financeYearId`	=	'" . $financeYearId . "'
			AND		`budget`.`chartOfAccountId`		=	'" . $chartOfAccountId . "'
			";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT 	[" . strtolower($fieldBudget) . "] AS [total]
			FROM	[budget]
			JOIN	[financeYear]
			ON		[budget].[companyId] 			= 	[financeYear].[companyId]
			AND		[budget].[financeYearId] 		= 	[financeYear].[financeYearId]
			JOIN	[chartOfAccount]
			ON		[budget].[companyId]			= 	[chartOfAccount].[companyId]
			AND		[budget].[chartOfAccountId]		= 	[chartOfAccount].[chartOfAccountId]
			WHERE 	[budget].[companyId]			=	'" . $this->getCompanyId() . "'
			AND		[financeYear].[financeYearYear]	=	'" . $financeYearId . "'
			AND		[budget].[chartOfAccountId]		=	'" . $chartOfAccountId . "'
			";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT 	" . strtoupper($fieldBudget) . " AS \"total\"
			FROM	BUDGET
			JOIN	FINANCEYEAR
			ON		BUDGET.COMPANYID 			= 	FINANCEYEAR.COMPANYID
			AND		BUDGET.FINANCEYEARID 		= 	FINANCEYEAR.FINANCEYEARID
			JOIN	CHARTOFACCOUNT
			ON		BUDGET.COMPANYID			= 	CHARTOFACCOUNT.COMPANYID
			AND		BUDGET.CHARTOFACCOUNTID		= 	CHARTOFACCOUNT.CHARTOFACCOUNTID
			WHERE 	BUDGET.COMPANYID			=	'" . $this->getCompanyId() . "'
			AND		FINANCEYEAR.FINANCEYEARYEAR	=	'" . $financeYearId . "'
			AND		BUDGET.CHARTOFACCOUNTID		=	'" . $chartOfAccountId . "'
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
            $budgetAmount = $row['total'];
        }
        return $budgetAmount;
    }

    /**
     * Return Budget Field Name
     * @param int $period
     * @return string $fieldName
     */
    public function getBudgetFieldName($period) {
        $fieldName = null;
        switch ($period) {
            case 1:
                $fieldName = "budgetTargetMonthOne";
                break;
            case 2:
                $fieldName = "budgetTargetMonthTwo";
                break;
            case 3:
                $fieldName = "budgetTargetMonthThree";
                break;
            case 4:
                $fieldName = "budgetTargetMonthFourth";
                break;
            case 5:
                $fieldName = "budgetTargetMonthFifth";
                break;
            case 6:
                $fieldName = "budgetTargetMonthSix";
                break;
            case 7:
                $fieldName = "budgetTargetMonthSeven";
                break;
            case 8:
                $fieldName = "budgetTargetMonthEight";
                break;
            case 9:
                $fieldName = "budgetTargetMonthNine";
                break;
            case 10:
                $fieldName = "budgetTargetMonthTen";
                break;
            case 11:
                $fieldName = "budgetTargetMonthEleven";
                break;
            case 12:
                $fieldName = "budgetTargetMonthTwelve";
                break;
            case 13:
                $fieldName = "budgetTargetMonthThirteen";
                break;
            case 14:
                $fieldName = "budgetTargetMonthFourteen";
                break;
            case 15:
                $fieldName = "budgetTargetMonthFifteen";
                break;
            case 16:
                $fieldName = "budgetTargetMonthSixteen";
                break;
            case 17:
                $fieldName = "budgetTargetMonthSeventeen";
                break;
            case 18:
                $fieldName = "budgetTargetMonthEighteen";
                break;
        }
        return $fieldName;
    }

    /**
     * Return Actual Finance Period based On Finance Period Primary Key
     * @param int $financePeriodRangeId Finance Period Primary Key
     * @return int $financePeriod
     */
    public function getFinancialPeriod($financePeriodRangeId) {
        $financePeriod = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT 	`financePeriodRangePeriod`
			FROM	`financeperiodrange`
			WHERE 	`companyId`				=	'" . $this->getCompanyId() . "'
			AND		`financePeriodRangeId`	=	'" . $financePeriodRangeId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT 	[financePeriodRangePeriod]
			FROM	 [financePeriodRange]
			WHERE 	[companyId]				=	'" . $this->getCompanyId() . "'
			AND		[financePeriodRangeId]	=	'" . $financePeriodRangeId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT 	FINANCEPERIODRANGEPERIOD
			FROM	FINANCEPERIODRANGE
			WHERE 	COMPANYID				=	'" . $this->getCompanyId() . "'
			AND		FINANCEPERIODRANGEID	=	'" . $financePeriodRangeId . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchAssoc($result);
            $financePeriod = $row['financePeriodRangePeriod'];
        }
        return $financePeriod;
    }

    /**
     * Return Balance Budget. Might be Balance - Actual Transaction.
     * @param int $chartOfAccountId Chart Of Account Primary Key / Primary Key
     * @param null|int $financeYearId Financial Year E.g 1992 Or based On Date Ranged(futured version)
     * @param null|int $financePeriodRangeId Financial Period E.g 1->January or Based On Date Range
     * @return float
     */
    public function getBalanceBudget($chartOfAccountId, $financeYearId = null, $financePeriodRangeId = null) {
        return floatval(
                $this->getBudgetAmount(
                        $chartOfAccountId, $financeYearId, $financePeriodRangeId
                ) - $this->getTransactionAmount($chartOfAccountId, $financeYearId, $financePeriodRangeId)
        );
    }

    /**
     * Return Budget Amount / value
     * @param int $chartOfAccountId Chart Of Account Primary Key / Primary Key
     * @param null|int $financeYearId Financial Year E.g 1992 Or based On Date Ranged(future version)
     * @param null|int $financePeriodRangeId Financial Period E.g 1->January or Based On Date Range
     * @param null|int $isLock Financial Lock
     * @return float $budgetAmount
     */
    public function getBudgetAmount(
    $chartOfAccountId, $financeYearId = null, $financePeriodRangeId = null, $isLock = null
    ) {
        $budgetAmount = 0;
        $sql = null;
        $fieldBudget = $this->getBudgetFieldName($this->getFinancialPeriod($financePeriodRangeId));
        if ($this->getVendor() == self::MYSQL) {
            if ($financeYearId && $financePeriodRangeId) {
                $sql = "
				SELECT 	`" . strtolower($fieldBudget) . "` AS `total`
				FROM	`budget`
				JOIN	`chartofaccount`
				USING	(`companyId`,`chartOfAccountId`)
				WHERE 	`budget`.`companyId`			=	'" . $this->getCompanyId() . "'
				AND		`budget`.`financeYearId`	    =	'" . $financeYearId . "'
				AND		`budget`.`chartOfAccountId`		=	'" . $chartOfAccountId . "'
				";
            } else {
                $sql = "
				SELECT	(
							`budgetTargetMonthOne` +
							`budgetTargetMonthTwo` +
							`budgetTargetMonthThree` +
							`budgetTargetMonthFourth` +
							`budgetTargetMonthFifth` +
							`budgetTargetMonthSix` +
							`budgetTargetMonthSeven` +
							`budgetTargetMonthEight` +
							`budgetTargetMonthNine` +
							`budgetTargetMonthTen` +
							`budgetTargetMonthEleven` +
							`budgetTargetMonthTwelve` +
							`budgetTargetMonthThirteen` +
							`budgetTargetMonthFourteen` +
							`budgetTargetMonthFifteen` +
							`budgetTargetMonthSixteen` +
							`budgetTargetMonthSeventeen` +
							`budgetTargetMonthEighteen`
						) AS `total`
				FROM	`budget`
				JOIN	`financeyear`
				USING	(`companyId`,`financeYearId`)
				JOIN	`chartofaccount`
				USING	(`companyId`,`chartOfAccountId`)
				WHERE 	`budget`.`companyId`			=	'" . $this->getCompanyId() . "'
				AND		`budget`.`financeYearId`	    =	'" . $financeYearId . "'
				AND		`budget`.`chartOfAccountId`		=	'" . $chartOfAccountId . "'
				";
            }
            if ($isLock) {
                $sql .= " AND `budget`.`isLock` = 1 ";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            if ($financeYearId && $financePeriodRangeId) {
                $sql = "
				SELECT 	[" . strtolower($fieldBudget) . "] AS [total]
				FROM	[budget]
				JOIN	[financeYear]
				ON		[budget].[companyId] 			= 	[financeYear].[companyId]
				AND		[budget].[financeYearId] 		= 	[financeYear].[financeYearId]
				JOIN	[chartOfAccount]
				ON		[budget].[companyId]			= 	[chartOfAccount].[companyId]
				AND		[budget].[chartOfAccountId]		= 	[chartOfAccount].[chartOfAccountId]
				WHERE 	[budget].[companyId]			=	'" . $this->getCompanyId() . "'
				AND		[budget].[financeYearId]	    =	'" . $financeYearId . "'
				AND		[budget].[chartOfAccountId]		=	'" . $chartOfAccountId . "'
				";
            } else {
                $sql = "
				SELECT	(
							[budgetTargetMonthOne] +
							[budgetTargetMonthTwo] +
							[budgetTargetMonthThree] +
							[budgetTargetMonthFourth] +
							[budgetTargetMonthFifth] +
							[budgetTargetMonthSix] +
							[budgetTargetMonthSeven] +
							[budgetTargetMonthEight] +
							[budgetTargetMonthNine] +
							[budgetTargetMonthTen] +
							[budgetTargetMonthEleven] +
							[budgetTargetMonthTwelve] +
							[budgetTargetMonthThirteen] +
							[budgetTargetMonthFourteen] +
							[budgetTargetMonthFifteen] +
							[budgetTargetMonthSixteen] +
							[budgetTargetMonthSeventeen] +
						    [budgetTargetMonthEighteen]
						) AS [total]
				FROM	[budget]
				JOIN	[financeYear]
				ON		[budget].[companyId] 			= [financeYear].[companyId]
				AND		[budget].[financeYearId] 		= [financeYear].[financeYearId]
				JOIN	[chartOfAccount]
				ON		[budget].[companyId]			= 	[chartOfAccount].[companyId]
				AND		[budget].[chartOfAccountId]		= 	[chartOfAccount].[chartOfAccountId]
				WHERE 	[budget].[companyId]			=	'" . $this->getCompanyId() . "'
				AND		[budget].[financeYearId]	    =	'" . $financeYearId . "'
				AND		[budget].[chartOfAccountId]		=	'" . $chartOfAccountId . "'
				";
            }
            if ($isLock) {
                $sql .= " AND [budget].[isLock] = 1 ";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            if ($financeYearId && $financePeriodRangeId) {
                $sql = "
				SELECT 	" . strtoupper($fieldBudget) . " AS \"total\"
				FROM	BUDGET
				JOIN	FINANCEYEAR
				ON		BUDGET.COMPANYID 			= 	FINANCEYEAR.COMPANYID
				AND		BUDGET.FINANCEYEARID 		= 	FINANCEYEAR.FINANCEYEARID
				JOIN	CHARTOFACCOUNT
				ON		BUDGET.COMPANYID			= 	CHARTOFACCOUNT.COMPANYID
				AND		BUDGET.CHARTOFACCOUNTID		= 	CHARTOFACCOUNT.CHARTOFACCOUNTID
				WHERE 	BUDGET.COMPANYID			=	'" . $this->getCompanyId() . "'
				AND		BUDGET.FINANCEYEARID	    =	'" . $financeYearId . "'
				AND		BUDGET.CHARTOFACCOUNTID		=	'" . $chartOfAccountId . "'
				";
            } else {
                $sql = "
				SELECT	(
							BUDGETTARGETMONTHONE +
							BUDGETTARGETMONTHTWO +
							BUDGETTARGETMONTHTHREE +
							BUDGETTARGETMONTHFOURTH +
							BUDGETTARGETMONTHFIFTH +
							BUDGETTARGETMONTHSIX +
							BUDGETTARGETMONTHSEVEN +
							BUDGETTARGETMONTHEIGHT +
							BUDGETTARGETMONTHNINE +
							BUDGETTARGETMONTHTEN +
							BUDGETTARGETMONTHELEVEN +
							BUDGETTARGETMONTHTWELVE +
							BUDGETTARGETMONTHTHIRTEEN +
							BUDGETTARGETMONTHFOURTEEN +
							BUDGETTARGETMONTHFIFTEEN +
							BUDGETTARGETMONTHSIXTEEN +
							BUDGETTARGETMONTHSEVENTEEN +
						    BUDGETTARGETMONTHEIGHTEEN
						) AS TOTAL
				FROM	BUDGET
				JOIN	FINANCEYEAR
				ON		BUDGET.COMPANYID 			= 	FINANCEYEAR.COMPANYID
				AND		BUDGET.FINANCEYEARID 		= 	FINANCEYEAR.FINANCEYEARID
				JOIN	CHARTOFACCOUNT
				ON		BUDGET.COMPANYID			= 	CHARTOFACCOUNT.COMPANYID
				AND		BUDGET.CHARTOFACCOUNTID		= 	CHARTOFACCOUNT.CHARTOFACCOUNTID
				WHERE 	BUDGET.COMPANYID			=	'" . $this->getCompanyId() . "'
				AND		BUDGET.FINANCEYEARID	    =	'" . $financeYearId . "'
				AND		BUDGET.CHARTOFACCOUNTID		=	'" . $chartOfAccountId . "'
				";
            }
            if ($isLock) {
                $sql .= " AND BUDGET.ISLOCK = 1 ";
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
            $budgetAmount = $row['total'];
        }
        return $budgetAmount;
    }

    /**
     * Return Actual Transaction Based On General Ledger
     * @param int $chartOfAccountId Chart Of Account Primary Key / Primary Key
     * @param null|int $financeYearId Financial Period E.g 1->January or Based On Date Range
     * @param null|int $financePeriodRangeId
     * @return float
     */
    public function getTransactionAmount($chartOfAccountId = 0, $financeYearId = null, $financePeriodRangeId = null) {

        $transactionAmount = floatval("0.00");
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT 	SUM(`localAmount`) AS `total`
			FROM	`generalledger`
			WHERE 	`companyId`			=	'" . $this->getCompanyId() . "'
			AND		`chartOfAccountId`	=	'" . $chartOfAccountId . "'";
            if ($financeYearId && $financePeriodRangeId) {
                $sql .= "
				AND	`financeYearId`			=	'" . $financeYearId . "'
				AND	`financePeriodRangeId`	=	'" . $financePeriodRangeId . "'";
            } else {
                $sql .= "
				AND	`financeYearId`			=	'" . $financeYearId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT 	SUM([localAmount]) AS [total]
			FROM	[generalLedger]
			WHERE 	[companyId]			=	'" . $this->getCompanyId() . "'
			AND		[chartOfAccountId]	=	'" . $chartOfAccountId . "'";
            if ($financeYearId && $financePeriodRangeId) {
                $sql .= "
				AND	[financeYearId]			=	'" . $financeYearId . "'
				AND	[financePeriodRangeId]	=	'" . $financePeriodRangeId . "'";
            } else {
                $sql .= "
				AND	[financeYearId]			=	'" . $financeYearId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT 	SUM([LOCALAMOUNT]) AS [TOTAL]
			FROM	GENERALLEDGER
			WHERE 	COMPANYID			=	'" . $this->getCompanyId() . "'
			AND		CHARTOFACCOUNTID	=	'" . $chartOfAccountId . "'";
            if ($financeYearId && $financePeriodRangeId) {
                $sql .= "
				AND	FINANCEYEARID				=	'" . $financeYearId . "'
				AND	FINANCEPERIODRANGEPERIOD	=	'" . $financePeriodRangeId . "'";
            } else {
                $sql .= "
				AND	FINANCEYEARYID				=	'" . $financeYearId . "'";
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
            $transactionAmount = $row['total'];
        }
        return floatval($transactionAmount);
    }

    /**
     * Return Balance Budget. Might be Balance - Actual Transaction.
     * @param int $chartOfAccountId Chart Of Account Primary Key / Primary Key
     * @param null|int $financeYearId Financial Year E.g 1992 Or based On Date Ranged(futured version)
     * @return string $string
     */
    public function getMiniStatement($chartOfAccountId, $financeYearId = null) {
        $string = " ";
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  *
            FROM    `generalledger`
            WHERE   `companyId`='" . $this->getCompanyId() . "'
            AND     `chartOfAccountId`  =   '" . $chartOfAccountId . "'";
            if ($financeYearId) {
                $sql .= " AND `financeYearId`='" . $financeYearId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  *
            FROM    [generalLedger]
            WHERE   [companyId]='" . $this->getCompanyId() . "'
            AND     [chartOfAccountId]  =   '" . $chartOfAccountId . "'";
            if ($financeYearId) {
                $sql .= " AND `financeYearId`='" . $financeYearId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  *
            FROM    GENERALLEDGER
            WHERE   COMPANYID           =   '" . $this->getCompanyId() . "'
            AND     CHARTOFACCOUNTID    =   '" . $chartOfAccountId . "'";
            if ($financeYearId) {
                $sql .= " AND `financeYearId`='" . $financeYearId . "'";
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $totalAmount = 0;
            $string .= "<table class=\"table table table-bordered table-striped table-condensed table-hover smart-form has-tickbox\" id=\"tableData\">\n";
            $string .= "<tr class=\"success\">\n";
            $string .= "<th><div align=\"center\">" . $this->translate['generalLedgerDateLabel'] . "</div></th>\n";
            $string .= "<th><div class=\"pull-left\">" . $this->translate['generalLedgerDescriptionLabel'] . "</div></th>\n";
            $string .= "<th><div class=\"pull-right\">" . $this->translate['localAmountLabel'] . "</div></th>\n";
            $string .= "</tr>";
            while (($row = $this->q->fetchArray($result)) == true) {
                $totalAmount += $row['localAmount'];
                $string .= "<tr>\n";
                $string .= "<td><div align=\"center\">" . $row['generalLedgerDate'] . "</div></td>\n";
                $string .= "<td><div class=\"pull-left\">" . $row['generalLedgerDescription'] . "</div></td>\n";
                if (class_exists('NumberFormatter')) {
                    $a = new \NumberFormatter($this->systemFormatArray['languageCode'], \NumberFormatter::CURRENCY);
                    $localAmount = $a->format($row['localAmount']);
                } else {
                    $localAmount = number_format($row['localAmount'], 2);
                }
                $string .= "<td><div class=\"pull-right\">" . $localAmount . "</div></td>\n";
                $string .= "</tr>";
            }
            $string .= "<tr class=\"success\">\n";
            $string .= "<td colspan=\"2\"><div align=\"pull-right\">" . $this->t['totalTextLabel'] . "</div></td>\n";
            if (class_exists('NumberFormatter')) {
                $a = new \NumberFormatter($this->systemFormatArray['languageCode'], \NumberFormatter::CURRENCY);
                $totalAmount = $a->format($totalAmount);
            } else {
                $totalAmount = number_format($totalAmount, 2);
            }
            $string .= "<td><div class=\"pull-right\">" . $totalAmount . "</div></td>\n";
            $string .= "</tr>\n";
            $string .= "</table>";
        }
        return $string;
    }

    /**
     * Post Budget Transfer
     * @param int $budgetTransferId Budget Transfer Primary Key
     * @return void
     */
    public function postBudgetTransfer($budgetTransferId) {
        // get information of the budget
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
        $sql = null;
        $budgetTransferFrom = 0;
        $budgetTransferTo = 0;
        $financeYearId = 0;
        $financePeriodRangeId = 0;
        $budgetTransferAmount = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT `budgetTransferFrom`,
                   `budgetTransferTo`,
                   `financeYearId`,
                   `financePeriodRangeId`,
                   `budgetTransferAmount`
            FROM   `budgettransfer`
            WHERE  `budgetTransferId` ='" . $budgetTransferId . "'
            ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT [budgetTransferFrom],
                   [budgetTransferTo],
                   [financeYearId],
                   [financePeriodRangeId],
                   [budgetTransferAmount]
            FROM   [budgettransfer]
            WHERE  [budgetTransfer].[budgetTransferId]	=	'" . $budgetTransferId . "'
            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
				SELECT BUDGETTRANSFERFROM       AS  \"budgetTransferFrom\",
					   BUDGETTRANSFERTO         AS  \"budgetTransferTo\",
					   FINANCEYEARID            AS  \"financeYearId\",
					   FINANCEPERIODRANGEID 	AS  \"financePeriodRangeId\",
					   BUDGETTRANSFERAMOUNT     AS  \"budgetTransferAmount\"
				FROM   BUDGETTRANSFER
				WHERE  BUDGETTRANSFERID                     =   '" . $budgetTransferId . "'
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

            $budgetTransferFrom = $row['budgetTransferFrom'];
            $budgetTransferTo = $row['budgetTransferTo'];
            $budgetTransferAmount = $row['budgetTransferAmount'];

            $financeYearId = $row['financeYearId'];
            $financePeriodRangeId = $row['financePeriodRangePeriod'];
        }
        // since it was vertical data. a bit complicate here
        $fieldBudget = $this->getBudgetFieldName($this->getFinancialPeriod($financePeriodRangeId));
        // first minus back the original budget
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE `budget`
            SET    `" . $fieldBudget . "`   =   `" . $fieldBudget . "` - '" . $budgetTransferAmount . "'
            WHERE  `financeYearId`      =   '" . $financeYearId . "'
            AND    `chartOfAccountId`   =   '" . $budgetTransferFrom . "'
            ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE `budget`
            SET    `" . $fieldBudget . "`   =   `" . $fieldBudget . "` - '" . $budgetTransferAmount . "'
            WHERE  `financeYearId`      =   '" . $financeYearId . "'
            AND    `chartOfAccountId`   =   '" . $budgetTransferFrom . "'
            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE `budget`
            SET    `" . $fieldBudget . "`   =   `" . $fieldBudget . "` - '" . $budgetTransferAmount . "'
            WHERE  `financeYearId`      =   '" . $financeYearId . "'
            AND    `chartOfAccountId`   =   '" . $budgetTransferFrom . "'
            ";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        // add up to the target budget.
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE `budget`
            SET    `" . $fieldBudget . "`   =   `" . $fieldBudget . "` + '" . $budgetTransferAmount . "'
            WHERE  `financeYearId`      =   '" . $financeYearId . "'
            AND    `chartOfAccountId`   =   '" . $budgetTransferTo . "'
            ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE `budget`
            SET    `" . $fieldBudget . "`   =   `" . $fieldBudget . "` + '" . $budgetTransferAmount . "'
            WHERE  `financeYearId`      =   '" . $financeYearId . "'
            AND    `chartOfAccountId`   =   '" . $budgetTransferTo . "'
            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE `budget`
            SET    `" . $fieldBudget . "`   =   `" . $fieldBudget . "` + '" . $budgetTransferAmount . "'
            WHERE  `financeYearId`      =   '" . $financeYearId . "'
            AND    `chartOfAccountId`   =   '" . $budgetTransferTo . "'
            ";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $this->setSumBudgetPerYearAccount($financeYearId, $budgetTransferFrom);
        $this->setSumBudgetPerYearAccount($financeYearId, $budgetTransferFrom);
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => true,
                    "message" => $this->t['postRecordTextLabel'],
                    "time" => $time
                )
        );
        exit();
    }

    /**
     * Sum All Chart Of Account based on Year And Chart Of Account
     * @param int $financeYearId
     * @param int $chartOfAccountId
     */
    private function setSumBudgetPerYearAccount($financeYearId, $chartOfAccountId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE `budget`
            SET     `budgetTargetTotalYear`  =
                    `budgetTargetMonthOne` +
                    `budgetTargetMonthTwo` +
                    `budgetTargetMonthThree` +
                    `budgetTargetMonthFourth` +
                    `budgetTargetMonthFifth` +
                    `budgetTargetMonthSix` +
                    `budgetTargetMonthSeven` +
                    `budgetTargetMonthEight` +
                    `budgetTargetMonthNine` +
                    `budgetTargetMonthTen` +
                    `budgetTargetMonthEleven` +
                    `budgetTargetMonthTwelve` +
                    `budgetTargetMonthThirteen` +
                    `budgetTargetMonthFourteen` +
                    `budgetTargetMonthFifteen` +
                    `budgetTargetMonthSixteen` +
					`budgetTargetMonthSeventeen` +
				    `budgetTargetMonthEighteen`

            WHERE  `financeYearId`  = '" . $financeYearId . "'
            AND    `chartOfAccountId`   =   '" . $chartOfAccountId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE  [budget`
            SET     [budgetTargetTotalYear]  =
                    [budgetTargetMonthOne] +
                    [budgetTargetMonthTwo] +
                    [budgetTargetMonthThree] +
                    [budgetTargetMonthFourth] +
                    [budgetTargetMonthFifth] +
                    [budgetTargetMonthSix] +
                    [budgetTargetMonthSeven] +
                    [budgetTargetMonthEight] +
                    [budgetTargetMonthNine] +
                    [budgetTargetMonthTen] +
                    [budgetTargetMonthEleven] +
                    [budgetTargetMonthTwelve] +
                    [budgetTargetMonthThirteen] +
                    [budgetTargetMonthFourteen] +
                    [budgetTargetMonthFifteen] +
                    [budgetTargetMonthSixteen] +
					[budgetTargetMonthSeventeen] +
				    [budgetTargetMonthEighteen]

            WHERE  [financeYearId`  = '" . $financeYearId . "'
            AND    [chartOfAccountId`   =   '" . $chartOfAccountId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE  BUDGET
            SET     BUDGETTARGETTOTALYEAR  =
                    BUDGETTARGETMONTHONE +
                    BUDGETTARGETMONTHTWO +
                    BUDGETTARGETMONTHTHREE +
                    BUDGETTARGETMONTHFOURTH +
                    BUDGETTARGETMONTHFIFTH +
                    BUDGETTARGETMONTHSIX +
                    BUDGETTARGETMONTHSEVEN +
                    BUDGETTARGETMONTHEIGHT +
                    BUDGETTARGETMONTHNINE +
                    BUDGETTARGETMONTHTEN +
                    BUDGETTARGETMONTHELEVEN +
                    BUDGETTARGETMONTHTWELVE +
                    BUDGETTARGETMONTHTHIRTEEN +
                    BUDGETTARGETMONTHFOURTEEN +
                    BUDGETTARGETMONTHFIFTEEN +
                    BUDGETTARGETMONTHSIXTEEN +
					BUDGETTARGETMONTHSEVENTEEN +
				    BUDGETTARGETMONTHEIGHTEEN

            WHERE  FINANCEYEARID  = '" . $financeYearId . "'
            AND    CHARTOFACCOUNTID   =   '" . $chartOfAccountId . "'";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Reverse the posted budget transfer. Reason  User loved to click click. ooops.
     * @param int $budgetTransferId
     * @return void
     */
    public function reverseBudgetTransfer($budgetTransferId) {
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
        $sql = null;
        $budgetTransferFrom = 0;
        $budgetTransferTo = 0;
        $financeYearId = 0;
        $financePeriodRangeId = 0;
        $budgetTransferAmount = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT `budgetTransferFrom`,
                   `budgetTransferTo`,
                   `financeYearId`,
                   `financePeriodRangePeriod`,
                   `budgetTransferAmount`
            FROM   `budgettransfer`
            JOIN   `financeperiodrange`
            USING   (`companyId`,`financePeriodRangeId`)
            WHERE  `budgetTransferId` ='" . $budgetTransferId . "'
            ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT [budgetTransferFrom],
                   [budgetTransferTo],
                   [financeYearId],
                   [financePeriodRangePeriod],
                   [budgetTransferAmount]
            FROM   [budgetTransfer]
            JOIN    [financePeriodRange]
            ON     [budgetTransfer].[companyId]             =    [financePeriodRange].[companyId]
            AND    [budgetTransfer].[financePeriodRangeId]  =    [financePeriodRange].[financePeriodRangeId]
            WHERE  [budgetTransfer].[budgetTransferId]      =   '" . $budgetTransferId . "'
            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT BUDGETTRANSFERFROM       AS  \"budgetTransferFrom\",
                   BUDGETTRANSFERTO         AS  \"budgetTransferTo\",
                   FINANCEYEARID            AS  \"financeYearId\",
                   FINANCEPERIODRANGEPERIOD AS  \"financePeriodRangePeriod\",
                   BUDGETTRANSFERAMOUNT     AS  \"budgetTransferAmount\"
            FROM   BUDGETTRANSFER
            ON     BUDGETTRANSFER.COMPANYID             =   FINANCEPERIODRANGE.COMPANYID
            AND    BUDGETTRANSFER.FINANCEPERIODRANGEID  =   FINANCEPERIODRANGE.FINANCEPERIODRANGEID
            WHERE  BUDGETTRANSFERID                     =   '" . $budgetTransferId . "'
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
            $budgetTransferFrom = $row['budgetTransferFrom'];
            $budgetTransferTo = $row['budgetTransferTo'];
            $financeYearId = $row['financeYearId'];
            $financePeriodRangeId = $row['financePeriodRangeId'];
            $budgetTransferAmount = $row['financePeriodRangeId'];
        }
        $fieldBudget = $this->getBudgetFieldName($financePeriodRangeId);
        // first minus back the original budget
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE `budget`
            SET    `" . $fieldBudget . "`   =   `" . $fieldBudget . "` + '" . $budgetTransferAmount . "'
            WHERE  `financeYearId`      =   '" . $financeYearId . "'
            AND    `chartOfAccountId`   =   '" . $budgetTransferFrom . "'
            ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE `budget`
            SET    `" . $fieldBudget . "`   =   `" . $fieldBudget . "` + '" . $budgetTransferAmount . "'
            WHERE  `financeYearId`      =   '" . $financeYearId . "'
            AND    `chartOfAccountId`   =   '" . $budgetTransferFrom . "'
            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE `budget`
            SET    `" . $fieldBudget . "`   =   `" . $fieldBudget . "` + '" . $budgetTransferAmount . "'
            WHERE  `financeYearId`      =   '" . $financeYearId . "'
            AND    `chartOfAccountId`   =   '" . $budgetTransferFrom . "'
            ";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        // add up to the target budget.
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE `budget`
            SET    `" . $fieldBudget . "`   =   `" . $fieldBudget . "` - '" . $budgetTransferAmount . "'
            WHERE  `financeYearId`      =   '" . $financeYearId . "'
            AND    `chartOfAccountId`   =   '" . $budgetTransferTo . "'
            ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE `budget`
            SET    `" . $fieldBudget . "`   =   `" . $fieldBudget . "` - '" . $budgetTransferAmount . "'
            WHERE  `financeYearId`      =   '" . $financeYearId . "'
            AND    `chartOfAccountId`   =   '" . $budgetTransferTo . "'
            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE `budget`
            SET    `" . $fieldBudget . "`   =   `" . $fieldBudget . "` - '" . $budgetTransferAmount . "'
            WHERE  `financeYearId`      =   '" . $financeYearId . "'
            AND    `chartOfAccountId`   =   '" . $budgetTransferTo . "'
            ";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $this->setSumBudgetPerYearAccount($financeYearId, $budgetTransferFrom);
        $this->setSumBudgetPerYearAccount($financeYearId, $budgetTransferFrom);
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => true,
                    "message" => $this->t['postRecordTextLabel'],
                    "time" => $time
                )
        );
        exit();
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