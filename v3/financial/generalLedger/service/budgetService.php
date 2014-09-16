<?php

namespace Core\Financial\GeneralLedger\Budget\Service;

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
 * Class BudgetService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\GeneralLedger\Budget\Service
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class BudgetService extends ConfigClass {

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
     * Leaf Label
     * @var string
     */
    public $translate;

    /**
     * System Format
     * @var array
     */
    public $systemFormatArray;

    /**
     * Odd Period
     * @var string
     */
    public $isOddPeriod;

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
    }

    /**
     * Return Chart Of Account Category (Filtering)
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
         AND         `companyId` =   '" . $this->getCompanyId() . "'
		 ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [chartOfAccountCategoryId],
                     [chartOfAccountCategoryTitle]
         FROM        [chartOfAccountCategory]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
		 ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      CHARTOFACCOUNTCATEGORYID AS \"chartOfAccountCategoryId\",
                        CHARTOFACCOUNTCATEGORYDESCRIPTION AS \"chartOfAccountCategoryTitle\"
            FROM        CHARTOFACCOUNTCATEGORY
            WHERE       ISACTIVE    =   1
            AND         COMPANYID   =   '" . $this->getCompanyId() . "'
            ORDER BY    CHARTOFACCOUNTNUMBER";
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
     * Return Chart Of Account Type
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
						`chartofaccountcategory`.`chartOfAccountCategoryTitle`
			FROM        `chartofaccounttype`
			JOIN		`chartofaccountcategory`
			USING		(`companyId`,`chartOfAccountCategoryId`)
			WHERE       `chartofaccounttype`.`isActive`  =   1
			AND         `chartofaccounttype`.`companyId` =   '" . $this->getCompanyId() . "'";
            if ($chartOfAccountCategoryId) {
                $sql .= "
				AND `chartofaccounttype`.`chartOfAccountCategoryId`='" . $chartOfAccountCategoryId . "'";
            }
            $sql .= "
			ORDER BY    `chartofaccounttype`.`isDefault` DESC;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      [chartOfAccountType].[chartOfAccountTypeId],
						[chartOfAccountType].[chartOfAccountTypeDescription],
			FROM        [chartOfAccountCategory][chartOfAccountCategoryTitle]
			join		[chartofaccountcategory]
			on			[chartOfAccountType].[companyId]				=	[chartOfAccountCategory][companyId]
			and			[chartOfAccountType].[chartOfAccountCategoryId]	=	[chartOfAccountCategory][chartOfAccountCategoryId]
			WHERE       [chartOfAccountType].[isActive] 	 			=   1
			AND         [chartOfAccountType].[companyId] 				=   '" . $this->getCompanyId() . "'";
            if ($chartOfAccountCategoryId) {
                $sql .= "
			AND [chartOfAccountType].[chartOfAccountCategoryId]	=	'" . $chartOfAccountCategoryId . "'";
            }
            $sql .= "
			ORDER BY    [chartOfAccountType].[isDefault] DESC";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT      CHARTOFACCOUNTTYPE.CHARTOFACCOUNTTYPEID AS \"chartOfAccountTypeId\",
						CHARTOFACCOUNTTYPE.CHARTOFACCOUNTTYPEDESCRIPTION AS \"chartOfAccountTypeDescription\",
						CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYDESCRIPTION AS \"chartOfAccountCategoryTitle\"
			FROM        CHARTOFACCOUNTTYPE
			JOIN		CHARTOFACCOUNTCATEGORY
			ON			CHARTOFACCOUNTTYPE.COMPANYID				=	CHARTOFACCOUNTCATEGORY.COMPANYID
			AND			CHARTOFACCOUNTTYPE.CHARTOFACCOUNTCATEGORYID	=	CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID
			WHERE       CHARTOFACCOUNTTYPE.ISACTIVE    				=   1
			AND         CHARTOFACCOUNTTYPE.COMPANYID   				=   '" . $this->getCompanyId() . "'";
            if ($chartOfAccountCategoryId) {
                $sql .= "
				AND 	CHARTOFACCOUNTTYPE.CHARTOFACCOUNTCATEGORYID	=	'" . $chartOfAccountCategoryId . "'";
            }
            $sql .= "
			ORDER BY    CHARTOFACCOUNTTYPE.ISDEFAULT";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 1;
            $chartOfAccountCategoryTitle = null;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($d != 0) {
                    if ($chartOfAccountCategoryTitle != $row['chartOfAccountCategoryTitle']) {
                        $str .= "</optgroup><optgroup label=\"" . $row['chartOfAccountCategoryTitle'] . "\">";
                    }
                } else {
                    $str .= "<optgroup label=\"" . $row['chartOfAccountCategoryTitle'] . "\">";
                }
                $chartOfAccountCategoryTitle = $row['chartOfAccountCategoryTitle'];
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['chartOfAccountTypeId'] . "'>" . $d . ". " . $row['chartOfAccountTypeDescription'] . "</option>";
                } else {
                    if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
                    }
                }
                $str .= "</optgroup>";
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
     * @param null|int $chartOfAccountTypeId
     * @return array|string
     */
    public function getChartOfAccount($chartOfAccountCategoryId = null, $chartOfAccountTypeId = null) {
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
            AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` IN ('" . self::INCOME . "','" . self::EXPENSES . "') ";
            if ($chartOfAccountCategoryId && $chartOfAccountTypeId) {
                $sql .= "
				AND	`chartOfaccount`.`chartOfAccountCategoryId`	=	'" . $chartOfAccountCategoryId . "'
				AND	`chartofaccount`.`chartOfAccountTypeId`		=	'" . $chartOfAccountTypeId . "'";
            } else if ($chartOfAccountCategoryId) {
                $sql .= "
				AND	`chartOfaccount`.`chartOfAccountCategoryId`	=	'" . $chartOfAccountCategoryId . "'";
            }
            $sql .= "
			ORDER BY    `chartofaccount`.`chartOfAccountNumber`;";
        } else if ($this->getVendor() == self::MSSQL) {
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
            AND         [chartOfAccountCategory][chartOfAccountCategoryCode] IN ('" . self::INCOME . "','" . self::EXPENSES . "') ";
            if ($chartOfAccountCategoryId && $chartOfAccountTypeId) {
                $sql .= "
				AND	[chartOfaccount].[chartOfAccountCategoryId]	=	'" . $chartOfAccountCategoryId . "'
				AND	[chartofaccount].[chartOfAccountTypeId]		=	'" . $chartOfAccountTypeId . "'";
            } else if ($chartOfAccountCategoryId) {
                $sql .= "
				AND	[chartOfaccount].[chartOfAccountCategoryId]	=	'" . $chartOfAccountCategoryId . "'";
            }
            $sql .= "
			ORDER BY    [chartOfAccount].[chartOfAccountNumber];";
        } else if ($this->getVendor() == self::ORACLE) {
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
            AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE IN ('" . self::INCOME . "','" . self::EXPENSES . "')";
            if ($chartOfAccountCategoryId && $chartOfAccountTypeId) {
                $sql .= "
				AND	CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID	=	'" . $chartOfAccountCategoryId . "'
				AND	CHARTOFACCOUNT.CHARTOFACCOUNTTYPEID		=	'" . $chartOfAccountTypeId . "'";
            } else if ($chartOfAccountCategoryId) {
                $sql .= "
				AND	CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID	=	'" . $chartOfAccountCategoryId . "'";
            }
            $sql .= "
			ORDER BY    CHARTOFACCOUNT.CHARTOFACCOUNTNUMBER;";
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
			SELECT	TOP 1
					[chartOfAccountId],
			FROM	[chartOfAccount]
			WHERE	[isActive]  =   1
			AND		[companyId] =   '" . $this->getCompanyId() . "'
			AND		[isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT	CHARTOFACCOUNTID AS \"chartOfAccountId\",
			FROM	CHARTOFACCOUNT
			WHERE	ISACTIVE    =   1
			AND		COMPANYID   =   '" . $this->getCompanyId() . "'
			AND		ISDEFAULT	  =	   1
			AND		ROWNUM	  =	   1";
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
     * Return FinanceYear
     * @return array|string
     */
    public function getFinanceYear() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT      `financeYearId`,
					 `financeYearYear`
			FROM        `financeyear`
			WHERE       `isActive`  =   1
			AND         `companyId` =   '" . $this->getCompanyId() . "'
			ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      [financeYearId],
						[financeYearYear]
			FROM        [financeYear]
			WHERE       [isActive]  =   1
			AND         [companyId] =   '" . $this->getCompanyId() . "'
			ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT      FINANCEYEARID AS \"financeYearId\",
						FINANCEYEARYEAR AS \"financeYearYear\"
			FROM        FINANCEYEAR
			WHERE       ISACTIVE    =   1
			AND         COMPANYID   =   '" . $this->getCompanyId() . "'
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
			FROM        	`financeyear`
			WHERE       `isActive`  =   1
			AND         `companyId` =   '" . $this->getCompanyId() . "'
			AND    	  `isDefault` =	  1
			LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      TOP 1 [financeYearId],
			FROM        [financeYear]
			WHERE       [isActive]  =   1
			AND         [companyId] =   '" . $this->getCompanyId() . "'
			AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT      FINANCEYEARID AS \"financeYearId\",
			FROM        FINANCEYEAR
			WHERE       ISACTIVE    =   1
			AND         COMPANYID   =   '" . $this->getCompanyId() . "'
			AND    	  ISDEFAULT	  =	   1
			AND 		  ROWNUM	  =	   1";
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
     * Return Chart Of Account Segment / Deep / Level
     * @return array|string
     */
    public function getChartOfAccountSegment() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT      `chartOfAccountSegmentId`,
						`chartOfAccountSegmentNumber`,
						`chartOfAccountSegmentTitle`,
						`chartOfAccountSegmentDescription`
			FROM        `chartofaccountsegment`
			WHERE       `isActive`  =   1
			AND         `companyId` =   '" . $this->getCompanyId() . "'
			ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      [chartOfAccountSegmentId],
						[chartOfAccountSegmentNumber],
						[chartOfAccountSegmentTitle],
						[chartOfAccountSegmentDescription]
			FROM        [chartOfAccountSegment]
			WHERE       [isActive]  =   1
			AND         [companyId] =   '" . $this->getCompanyId() . "'
			ORDER BY    [isDefault];";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT      CHARTOFACCOUNTSEGMENTID,
						CHARTOFACCOUNTSEGMENTNUMBER,
						CHARTOFACCOUNTSEGMENTTITLE,
						CHARTOFACCOUNTSEGMENTDESCRIPTION
			FROM        CHARTOFACCOUNTSEGMENT
			WHERE       ISACTIVE  =   1
			AND         COMPANYID =   '" . $this->getCompanyId() . "'
			ORDER BY    ISDEFAULT;";
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
                    $str .= "<option value='" . $row['chartOfAccountSegmentId'] . "'>" . $d . ". " . $row['chartOfAccountSegmentTitle'] . "</option>";
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
     * Return Finance Period Range.
     * @param null|int $financeYearId Financial Year E.g 2012
     * @return array
     */
    public function getFinanciaPeriodRange($financeYearId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
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
     * Get Financial Period Range Based On Conceptual Period / Month
     * @param int $financeYear Conceptual Finance Year
     * @param int $financePeriod Conceptual Finance Period
     * @return null|string $financeRange  Date output will be  like 01-01-2011 ~ 01-01-2011 .Using tilde as range method.
     */
    public function getFinancialPeriodRange($financeYear, $financePeriod) {
        $sql = null;
        $financeRange = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `financePeriodRangeStart`,
                    `financePeriodRangeEnd`
            FROM    `financeyear`
            JOIN    `financeperiodrange`
            USING   (`companyId`,`financeYearId`)
            WHERE   `financeyear`.`companyId`='" . $this->getCompanyId() . "'
            AND     `financeyear`.`financeYearYear`='" . $financeYear . "'
            AND     `financeperiodrange`.`finacePeriodRangePeriod`='" . $financePeriod . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT  [financePeriodRangeStart],
			[financePeriodRangeEnd]
			FROM    [financeYear]
			JOIN     [financePeriodRange]
			ON      [financeYear].[companyId]                       =    [financePeriodRange].[companyId]
			AND     [financeYear].[financeYearId]                   =    [financePeriodRange].[financeYearId]
			WHERE   [financeYear].[companyId]                       =   '" . $this->getCompanyId() . "'
			AND     [financeYear].[financeYearYear]                 =   '" . $financeYear . "'
			AND      [financePeriodRange].[finacePeriodRangePeriod]  =   '" . $financePeriod . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT  FINANCEPERIODRANGESTART AS \"financePeriodRangeStart\",
			FINANCEPERIODRANGEEND   AS \"financePeriodRangeEnd\"
			FROM    FINANCEYEAR
			JOIN    FINANCEPERIODRANGE
			ON      FINANCEYEAR.COMPANYID                       =   FINANCEPERIODRANGE.COMPANYID
			AND     FINANCEYEAR.FINANCEYEARID                   =   FINANCEPERIODRANGE.FINANCEYEARID
			WHERE   FINANCEYEAR.COMPANYID                       =   '" . $this->getCompanyId() . "'
			AND     FINANCEYEAR.FINANCEYEARYEAR                 =   '" . $financeYear . "'
			AND     FINANCEPERIODRANGE.FINACEPERIODRANGEPERIOD  =   '" . $financePeriod . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $financeRange = $row['financePeriodRangeStart'] . " ~ " . $row['financePeriodRangeStart'];
        }
        return $financeRange;
    }

    /**
     * Return Budget Record Via Table Body.Is Lock Is the final version.
     * @param int $leafId Leaf Primary Key
     * @param string $controllerPath Controller Path
     * @param null|int $financeYearId Finance Year Primary Key
     * @param null|int $financePeriodRangeId Finance Period Range Primary Key
     * @param null|int $chartOfAccountCategoryId Chart Of Account Category Primary Key
     * @param null|int $chartOfAccountTypeId Chart Of Account Type Primary Key
     * @return string
     * @throws \Exception
     */
    function getBudgetList(
    $leafId, $controllerPath, $financeYearId, $financePeriodRangeId, $chartOfAccountCategoryId = null, $chartOfAccountTypeId = null
    ) {
        $sql = null;
        $string = null;
        $fieldBudget = $this->getBudgetFieldName($this->getFinanciaPeriod($financePeriodRangeId));
        if ($this->getVendor() == self::MYSQL) {

            $sql = "
			SELECT  *
			FROM    `chartofaccount`
			WHERE   `chartofaccount`.`companyId`    =   '" . $this->getCompanyId() . "'";

            if ($chartOfAccountCategoryId) {
                $sql .= "
				AND	`chartofaccount`.`chartOfAccountCategoryId`	=	'" . $chartOfAccountCategoryId . "'";
            }
            if ($chartOfAccountTypeId) {
                $sql .= "
				AND	`chartofaccount`.`chartOfAccountTypeId`	=	'" . $chartOfAccountTypeId . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {

            $sql = "
			SELECT  *
			FROM    [chartOfAccount]
			WHERE   [chartOfAccount].[companyId]    	=   '" . $this->getCompanyId() . "'";

            if ($chartOfAccountCategoryId) {
                $sql .= "
				AND	[chartOfAccount].[chartOfAccountCategoryId]	=	'" . $chartOfAccountCategoryId . "'";
            }
            if ($chartOfAccountTypeId) {
                $sql .= "
				AND	[chartOfAccount].[chartOfAccountTypeId]	=	'" . $chartOfAccountTypeId . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {

            $sql = "
			SELECT  CHARTOFACCOUNT.CHARTOFACCOUNTNUMBER AS  \"chartOfAccountNumber\",
					CHARTOFACCOUNT.CHARTOFACCOUNTTITLE AS  \"chartOfAccountTitle\",
					CHARTOFACCOUNT.CHARTOFACCOUNTID AS \"chartOfAccountId\"
			FROM    CHARTOFACCOUNT
			WHERE   CHARTOFACCOUNT.COMPANYID   	=   '" . $this->getCompanyId() . "'";

            if ($chartOfAccountCategoryId) {
                $sql .= "
				AND	CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID	=	'" . $chartOfAccountCategoryId . "'";
            }
            if ($chartOfAccountTypeId) {
                $sql .= "
				AND	CHARTOFACCOUNT.CHARTOFACCOUNTTYPEID	=	'" . $chartOfAccountTypeId . "'";
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
            $i = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                $string .= "<tr>\n";
                $string .= "  <td vAlign=\"top\" align=\"center\"><div align=\"center\">" . $i . "</div></td>\n";
                $string .= "  <td vAlign=\"top\" align=\"center\"><div align=\"center\">" . $row['chartOfAccountNumber'] . "</div></td>\n";
                $string .= "  <td vAlign=\"top\" ><div class=\"pull-left\">" . $row['chartOfAccountTitle'] . "</div></td>\n";
                $budgetData = $this->getBudgetData($row['chartOfAccountId'], $financeYearId);
                // this will open windows which can update the figure
                if (empty($financePeriodRangeId)) {

                    $string .= "  <td vAlign=\"top\"><div class=\"col-md-12\"><div class=\"input-group\">\n";
                    $string .= "  <input type=\"text\" class=\"form-control\" name=\"budgetTargetActualYear_" . $budgetData['budgetId'] . "\" id=\"budgetTargetActualYear_" . $budgetData['budgetId'] . "\" value=\"" . floatval(
                                    ($budgetData['budgetTargetTotalYear'] * 1)
                            ) . "\">\n";
                    $string .= "  <span class=\"input-group-btn\"><button type=\"button\" class=\"btn btn-warning\" onClick=\"previewBudgetTransfer(" . $leafId . ",'" . $controllerPath . "','" . $this->getSecurityToken(
                            ) . "'," . $row['chartOfAccountId'] . ")\">" . $this->t['previewButtonLabel'] . "</button>\n";
                    $string .= "	</span></div></td>\n";
                } else {
                    // this will update directly the period / month

                    $string .= "  <td vAlign=\"top\"><div class=\"col-md-12\"><div class=\"input-group\">\n";
                    $string .= "	<input type=\"text\" class=\"form-control\" name=\"" . $fieldBudget . "_" . $budgetData['budgetId'] . "\" id=\"" . $fieldBudget . "_" . $budgetData['budgetId'] . "\" value=\"" . $budgetData[$fieldBudget] . "\">\n";
                    $string .= "  <span class=\"input-group-btn\"><button type=\"button\" class=\"btn btn-warning\"";
                    $string .= "  onClick=\"updateRecordInline(
                                " . $leafId . ",
                                '" . $controllerPath . "',
                                '" . $this->getSecurityToken() . "',
                                '" . $budgetData['budgetId'] . "',
                                '" . $fieldBudget . "',
								'" . $row['chartOfAccountId'] . "')\">" . $this->t['saveButtonLabel'] .
                            "</button></span></div></td>";
                }
                $i++;
            }
        }
        return $string;
    }

    /**
     * Return Budget Field Name
     * @param int $period Period
     * @return string $fieldName Field name
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
    public function getFinanciaPeriod($financePeriodRangeId) {
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
     * Return Budget According To Year.
     * @param int $chartOfAccountId Chart Of Account Primary Key
     * @param int $financeYearId Financial Year Primary Key
     * @return bool $exist
     * @throws \Exception
     */
    public function getBudgetData($chartOfAccountId, $financeYearId) {
        $data = array();
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT 	*
			FROM	`budget`
			WHERE	`companyId`			=	'" . $this->getCompanyId() . "'
			AND		`chartOfAccountId`	=	'" . $chartOfAccountId . "'
			AND		`financeYearId`		=	'" . $financeYearId . "'
			AND		`isLock`			=	1
			LIMIT	1
			";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT 	TOP 1 *
			FROM	[budget]
			WHERE	[companyId]			=	'" . $this->getCompanyId() . "'
			AND		[chartOfAccountId]	=	'" . $chartOfAccountId . "'
			AND		[financeYearId]		=	'" . $financeYearId . "'
			AND		[isLock]			= 	1
			";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT 	*
			FROM	BUDGET
			WHERE	COMPANYID			=	'" . $this->getCompanyId() . "'
			AND		CHARTOFACCOUNTID	=	'" . $chartOfAccountId . "'
			AND		FINANCEYEARID		=	'" . $financeYearId . "'
			AND		ISLOCK				=	1
			AND		ROWNUM				= 	1";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $total = intval($this->q->numberRows($result));
            if ($total > 0) {
                $data = $this->q->fetchAssoc($result);
            }
        }
        return $data;
    }

    /**
     * Return Budget Exist Or Not
     * @param int $chartOfAccountId Chart Of Account Primary Key
     * @param int $financeYearId Financial Year Primary Key
     * @return bool $exist
     * @throws \Exception
     */
    public function getBudgetExist($chartOfAccountId, $financeYearId) {
        $sql = null;
        $exist = false;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT 	`isActive`
			FROM	`budget`
			WHERE	`companyId`			=	'" . $this->getCompanyId() . "'
			AND		`chartOfAccountId`	=	'" . $chartOfAccountId . "'
			AND		`financeYearId`		=	'" . $financeYearId . "'
			";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT 	[isActive]
			FROM	[budget]
			WHERE	[companyId]			=	'" . $this->getCompanyId() . "'
			AND		[chartOfAccountId]	=	'" . $chartOfAccountId . "'
			AND		[financeYearId]		=	'" . $financeYearId . "'
			";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT 	ISACTIVE
			FROM	BUDGET
			WHERE	COMPANYID			=	'" . $this->getCompanyId() . "'
			AND		CHARTOFACCOUNTID	=	'" . $chartOfAccountId . "'
			AND		FINANCEYEARID		=	'" . $financeYearId . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $total = intval($this->q->numberRows($result));
            if ($total == 0) {
                $exist = false;
            } else {
                $exist = true;
            }
        }
        return $exist;
    }

    /**
     * Return Current Budget Version / Counter
     * @param int $chartOfAccountId Chart Of Account
     * @param int $financeYearId Financial Year
     * @return int $budgetVersion
     */
    public function getBudgetCounter($chartOfAccountId, $financeYearId) {
        $sql = null;
        $budgetVersion = 1;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT	`budgetVersion`
			FROM	`budget`
			WHERE	`companyId`			=	'" . $this->getCompanyId() . "'
			AND		`chartOfAccountId`	=	'" . $chartOfAccountId . "'
			AND		`financeYearId`		=	'" . $financeYearId . "'
			";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT	[budgetVersion]
			FROM	[budget]
			WHERE	[companyId]			=	'" . $this->getCompanyId() . "'
			AND		[chartOfAccountId]	=	'" . $chartOfAccountId . "'
			AND		[financeYearId]		=	'" . $financeYearId . "'
			";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT	BUDGETVERSION
			FROM	BUDGET
			WHERE	COMPANYID			=	'" . $this->getCompanyId() . "'
			AND		CHARTOFACCOUNTID	=	'" . $chartOfAccountId . "'
			AND		FINANCEYEARID		=	'" . $financeYearId . "'
			";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchAssoc($result);
            $budgetVersion = $row['budgetVersion'];
        }
        return $budgetVersion;
    }

    /**
     * Return Budget Amount / value
     * @param int $chartOfAccountId Chart Of Account Primary Key / Primary Key
     * @param int $financeYearId Financial Year E.g 1992 Or based On Date Ranged(futured version)
     * @return mixed
     */
    public function getBudgetAmountByYear($chartOfAccountId, $financeYearId) {
        $row = array();
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT	*
			FROM	`budget`
			JOIN	`chartofaccount`
			USING	(`companyId`,`chartOfAccountId`)
			JOIN	`financeyear`
			USING	(`companyId`,`financeYearId`)
			WHERE 	`budget`.`companyId`		=	'" . $this->getCompanyId() . "'
			AND		`budget`.`financeYearId`	=	'" . $financeYearId . "'
			AND		`budget`.`chartOfAccountId`	=	'" . $chartOfAccountId . "'
			";
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
     * @param int $financeYearId Financial Year E.g 1992 Or based On Date Ranged(future version)
     * @param int $financePeriodRangeId Financial Period E.g 1->January or Based On Date Range
     * @return float $budgetAmount
     */
    public function getBudgetAmountByPeriod($chartOfAccountId, $financeYearId, $financePeriodRangeId) {
        $budgetAmount = 0;
        $sql = null;
        $fieldBudget = $this->getBudgetFieldName($this->getFinanciaPeriod($financePeriodRangeId));
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
        $fieldBudget = $this->getBudgetFieldName($this->getFinanciaPeriod($financePeriodRangeId));
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
            $string .= "<tr>\n";
            $string .= "<td><div align=\"center\">" . $this->translate['generalLedgerDateLabel'] . "</div></td>\n";
            $string .= "<td><div class=\"pull-left\">" . $this->translate['generalLedgerDescriptionLabel'] . "</div></td>\n";
            $string .= "<td><div class=\"pull-right\">" . $this->translate['localAmountLabel'] . "</div></td>\n";
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
            $string .= "<tr>\n";
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
     * Update Record By Field
     * @param int $budgetId Budget
     * @param string $budgetFieldName Name
     * @param float $budgetFieldValue Value
     * @return void
     */
    public function updateByLineField($budgetId, $budgetFieldName, $budgetFieldValue) {
        header('Content-Type:application/json; charset=utf-8');
        $this->q->start();
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			UPDATE	`budget`
			SET		`" . $budgetFieldName . "`	=	'" . $budgetFieldValue . "'
			WHERE	`budgetId`				=	'" . $budgetId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			UPDATE	[budget]
			SET		[" . $budgetFieldName . "]	=	'" . $budgetFieldValue . "'
			WHERE	[budgetId]				=	'" . $budgetId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			UPDATE	BUDGET
			SET		" . strtoupper($budgetFieldName) . "	=	'" . $budgetFieldValue . "'
			WHERE	BUDGETID							=	'" . $budgetId . "'";
        }
        try {
            $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $this->setSumBudgetPerYearAccount($budgetId);
        $this->q->commit();
        echo json_encode(array("success" => true, "message" => "complete"));
        exit();
    }

    /**
     * Sum All Chart Of Account based on Year And Chart Of Account
     * @param int $budgetId
     * @return void
     */
    public function setSumBudgetPerYearAccount($budgetId) {
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

            WHERE  `budgetId`  = '" . $budgetId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE  [budget]
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

            WHERE  [budgetId]  	= '" . $budgetId . "'";
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

            WHERE  BUDGETID  = '" . $budgetId . "'";
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
     * Create Record By Field
     * @param int $chartOfAccountId Chart Of Account
     * @param int $financeYearId Finance Year Primary Key
     * @param string $budgetFieldName Name
     * @param float $budgetFieldValue Value
     * @return void
     * @throws \Exception
     */
    public function createByLineField($chartOfAccountId, $financeYearId, $budgetFieldName, $budgetFieldValue) {
        header('Content-Type:application/json; charset=utf-8');
        $this->q->start();
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			INSERT INTO `budget` (
						`companyId`,
						`chartOfAccountId`,
						`financeYearId`,
						`" . $budgetFieldName . "`,
						`isNew`,
						`isActive`,
						`executeBy`,
						`executeTime`
			) VALUES (
						'" . $this->getCompanyId() . "',
						'" . $chartOfAccountId . "',
						'" . $financeYearId . "',
						'" . $budgetFieldValue . "',
						1,
						1,
						'" . $this->getStaffId() . "',
						" . $this->getExecuteTime() . "
			)";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			INSERT INTO [budget] (
						[companyId],
						[chartOfAccountId],
						[financeYearId],
						[" . $budgetFieldName . "],
						[isNew],
						[isActive],
						[executeBy],
						[executeTime]
			) VALUES (
						'" . $this->getCompanyId() . "',
						'" . $chartOfAccountId . "',
						'" . $financeYearId . "',
						'" . $budgetFieldValue . "',
						1,
						1,
						'" . $this->getStaffId() . "',
						" . $this->getExecuteTime() . "
			)";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			INSERT INTO  BUDGET (
						 COMPANYID,
						 CHARTOFACCOUNTID,
						 FINANCEYEARID,
						 " . $budgetFieldName . ",
						 ISNEW,
						 ISACTIVE,
						 EXECUTEBY,
						 EXECUTETIME
			) VALUES (
						'" . $this->getCompanyId() . "',
						'" . $chartOfAccountId . "',
						'" . $financeYearId . "',
						'" . $budgetFieldValue . "',
						1,
						1,
						'" . $this->getStaffId() . "',
						" . $this->getExecuteTime() . "
			)";
        }
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $budgetId = $this->q->lastInsertId('budget');
        $this->setSumBudgetPerYearAccount($budgetId);
        $this->q->commit();
        echo json_encode(array("success" => true, "message" => "complete"));
        exit();
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