<?php

namespace Core\Financial\Cashbook\PaymentVoucherDetail\Service;

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
 * Class PaymentVoucherDetailService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\Cashbook\PaymentVoucherDetail\Service
 * @subpackage Cashbook
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class PaymentVoucherDetailService extends ConfigClass {

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
            $this->setFinanceYearId($row['financeYearId']);
        }
    }

    /**
     * /**
     * Return Payment Voucher
     * @return array|string
     */
    public function getPaymentVoucher() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `paymentVoucherId`,
                     `paymentVoucherDescription`
         FROM        `paymentvoucher`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [paymentVoucherId],
                     [paymentVoucherDescription]
         FROM        [paymentVoucher]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else  if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      PAYMENTVOUCHERID AS \"paymentVoucherId\",
                     PAYMENTVOUCHERDESCRIPTION AS \"paymentVoucherDescription\"
         FROM        PAYMENTVOUCHER
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
                    $str .= "<option value='" . $row['paymentVoucherId'] . "'>" . $d . ". " . $row['paymentVoucherDescription'] . "</option>";
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
     * Return PaymentVoucher Default Value
     * @return int
     */
    public function getPaymentVoucherDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $paymentVoucherId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `paymentVoucherId`
         FROM        	`paymentvoucher`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [paymentVoucherId],
         FROM        [paymentVoucher]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else  if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      PAYMENTVOUCHERID AS \"paymentVoucherId\",
         FROM        PAYMENTVOUCHER
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
            $paymentVoucherId = $row['paymentVoucherId'];
        }
        return $paymentVoucherId;
    }

    /**
     * Return Business Partner
     * @return array|string
     */
    public function getBusinessPartner() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT      `businesspartner`.`businessPartnerId`,
						`businesspartner`.`businessPartnerCompany`,
						`businesspartnercategory`.`businessPartnerCategoryDescription`
			FROM        `businesspartner`
			JOIN		 `businesspartnercategory`
			USING		 (`companyId`,`businessPartnerCategoryId`)
			/*
			JOIN		 `purchaseinvoice`
			USING		 (`companyId`,`businessPartnerId`)
			*/
			WHERE       `businesspartner`.`isActive`  =   1
			AND         `businesspartner`.`companyId` =   '" . $this->getCompanyId() . "'
			/*
			AND		 	`purchaseinvoice`.`isPost`	  =	1
			AND			`purchaseinvoice`.`isActive`  =	1	
			*/
			ORDER BY    `businesspartnercategory`.`businessPartnerCategoryDescription`,
						`businesspartner`.`businessPartnerCompany`;";
        } else  if ($this->getVendor() == self::MSSQL) {
                $sql = "
			SELECT      [businessPartner].[businessPartnerId],
						[businessPartner].[businessPartnerCompany],
						[businessPartnerCategory].[businessPartnerCategoryDescription]
			FROM        [businessPartner]
			JOIN	    [businessPartnerCategory]
			ON			[businessPartnerCategory].[companyId] 					= 	[businessPartner].[companyId]
			AND		 	[businessPartnerCategory].[businessPartnerCategoryId] 	= 	[businessPartner].[businessPartnerCategoryId]
			WHERE       [businessPartner].[isActive]  							=	1
			AND         [businessPartner].[companyId] 							=   '" . $this->getCompanyId() . "'
			ORDER BY    [businessPartnerCategory].[businessPartnerCategoryDescription],
						[businessPartner].[businessPartnerCompany]	";
            } else  if ($this->getVendor() == self::ORACLE) {
                    $sql = "
			SELECT      BUSINESSPARTNER.BUSINESSPARTNERID AS \"businessPartnerId\",
						BUSINESSPARTNER.BUSINESSPARTNERCOMPANY AS \"businessPartnerCompany\",
						BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYDESCRIPTION AS \"businessPartnerCategoryDescription\"
			FROM        BUSINESSPARTNER
			JOIN	    BUSINESSPARTNERCATEGORY
			ON			BUSINESSPARTNERCATEGORY.COMPANYID 					= 	BUSINESSPARTNER.COMPANYID
			AND		 	BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYID 	= 	BUSINESSPARTNER.BUSINESSPARTNERCATEGORYIID
			WHERE       BUSINESSPARTNER.ISACTIVE    						=   1
			AND         BUSINESSPARTNER.COMPANYID   						=   '" . $this->getCompanyId() . "'
			ORDER BY    BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYDESCRIPTION ,
						BUSINESSPARTNER.BUSINESSPARTNERCOMPANY";
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
                    $str .= "<option value='" . $row['businessPartnerId'] . "'>" . $d . ". " . $row['businessPartnerCompany'] . "</option>";
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
     * Return BusinessPartner Default Value
     * @return int
     */
    public function getBusinessPartnerDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $businessPartnerId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `businessPartnerId`
         FROM        	`businesspartner`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [businessPartnerId],
         FROM        [businessPartner]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else  if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      BUSINESSPARTNERID AS \"businessPartnerId\",
         FROM        BUSINESSPARTNER
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
            $businessPartnerId = $row['businessPartnerId'];
        }
        return $businessPartnerId;
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
			JOIN		`chartofaccountcategory`
			USING		(`companyId`,`chartOfAccountCategoryId`)
			JOIN        `chartofaccounttype`
			USING       (`companyId`,`chartOfAccountCategoryId`,`chartOfAccountTypeId`)
			WHERE       `chartofaccount`.`isActive`  							=   1
			AND         `chartofaccount`.`companyId` 							=   '" . $this->getCompanyId() . "'
			ORDER BY    `chartofaccounttype`.`chartOfAccountTypeId`,
						`chartofaccount`.`chartOfAccountNumber`;";
        } else  if ($this->getVendor() == self::MSSQL) {
                $sql = "
			SELECT      [chartOfAccount].[chartOfAccountId],
						[chartOfAccount].[chartOfAccountNumber],
						[chartOfAccount].[chartOfAccountTitle],
						[chartOfAccountType].[chartOfAccountTypeDescription]
			FROM        [chartOfAccount]

			JOIN		[chartOfAccountCategory]
			ON          [chartOfAccount].[companyId]   							= 	[chartOfAccountCategory][companyId]
			AND         [chartOfAccount].[chartOfAccountCategoryId]   			= 	[chartOfAccountCategory][chartOfAccountCategoryId]

			JOIN		[chartOfAccountType]
			ON          [chartOfAccount].[companyId]   							= 	[chartOfAccountType].[companyId]
			AND         [chartOfAccount].[chartOfAccountTypeId]   				= 	[chartOfAccountType].[chartOfAccountTypeId]
			AND			[chartOfAccount].[chartOfAccountCategoryId]   			= 	[chartOfAccountType].[chartOfAccountCategoryId]

			WHERE       [chartOfAccount].[isActive]  							=   1
			AND         [chartOfAccount].[companyId] 							=   '" . $this->getCompanyId() . "'
			ORDER BY    [chartOfAccount].[chartOfAccountNumber]";
            } else  if ($this->getVendor() == self::ORACLE) {
                    $sql = "
			SELECT      CHARTOFACCOUNTID               AS  \"chartOfAccountId\",
						CHARTOFACCOUNTNUMBER           AS  \"chartOfAccountNumber\",
						CHARTOFACCOUNTTITLE            AS  \"chartOfAccountTitle\",
						CHARTOFACCOUNTTYPEDESCRIPTION  AS  \"chartOfAccountTypeDescription\"
			FROM        CHARTOFACCOUNT

			JOIN		CHARTOFACCOUNTCATEGORY
			ON          CHARTOFACCOUNT.COMPANYID   							= 	CHARTOFACCOUNTCATEGORY.COMPANYID
			AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID   			= 	CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

			JOIN        CHARTOFACCOUNTTYPE
			ON          CHARTOFACCOUNT.COMPANYID               				=   CHARTOFACCOUNTTYPE.COMPANYID
			AND         CHARTOFACCOUNT.CHARTOFACCOUNTTYPEID    				=   CHARTOFACCOUNTTYPE.CHARTOFACCOUNTTYPEID
			AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID    			=   CHARTOFACCOUNTTYPE.CHARTOFACCOUNTCATEGORYID

			WHERE       CHARTOFACCOUNT.ISACTIVE                				=   1
			AND         CHARTOFACCOUNT.COMPANYID               				=   '" . $this->getCompanyId() . "'
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
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['chartOfAccountId'] . "'>" . $row['chartOfAccountNumber'] . " - " . $row['chartOfAccountTitle'] . "</option>";
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
        } elseif ($this->getVendor() == self::MSSQL) {
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
            } else   if ($this->getVendor() == self::ORACLE) {
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
     * Return Transaction Type
     * @return array|string
     */
    public function getTransactionType() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `transactionTypeId`,
                     `transactionTypeDescription`
         FROM        `transactiontype`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [transactionTypeId],
                     [transactionTypeDescription]
         FROM        [transactionType]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      TRANSACTIONTYPEID AS \"transactionTypeId\",
                     TRANSACTIONTYPEDESCRIPTION AS \"transactionTypeDescription\"
         FROM        TRANSACTIONTYPE
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
                    $str .= "<option value='" . $row['transactionTypeId'] . "'>" . $d . ". " . $row['transactionTypeDescription'] . "</option>";
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
     * Return Transaction Type Default Value
     * @return int
     */
    public function getTransactionTypeDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $transactionTypeId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `transactionTypeId`
         FROM        	`transactiontype`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [transactionTypeId],
         FROM        [transactionType]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else  if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      TRANSACTIONTYPEID AS \"transactionTypeId\",
         FROM        TRANSACTIONTYPE
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
            $transactionTypeId = $row['transactionTypeId'];
        }
        return $transactionTypeId;
    }

    /**
     * Return Trial Balance Is correct or not before posting to journal . SUM ASSET  account - LIABILITY accounts + INCOME - Expenses + Return Earning Accounts
     * @param int $paymentVoucherId
     * @return string $trialBalance
     */
    private function getCheckTrialBalance($paymentVoucherId) {
        $trialBalance = 0;
        $sql = null;

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT (
                (
                    SELECT      SUM(`paymentVoucherDetailAmount`)
                    FROM        `paymentvoucherdetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `paymentvoucherdetail`.`companyId`                         	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `paymentvoucherdetail`.`paymentVoucherId`                         	IN   (" . $paymentVoucherId . ")
                    AND         `paymentvoucherdetail`.`isActive`                          	=   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode`	=   '" . self::ASSET . "'

                    GROUP BY    `paymentvoucherdetail`.`paymentVoucherDetailAmount`
                )
                -
                 (
                    SELECT      SUM(`paymentVoucherDetailAmount`)
                    FROM        `paymentvoucherdetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `paymentvoucherdetail`.`companyId`                        	 	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `paymentvoucherdetail`.`paymentVoucherId`                         	IN   (" . $paymentVoucherId . ")
                    AND         `paymentvoucherdetail`.`isActive`                          	=   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode`	=	'" . self::LIABILITY . "'
                    GROUP BY    `paymentvoucherdetail`.`paymentVoucherDetailAmount`
                )
                 +
                 (
                    SELECT      SUM(`paymentVoucherDetailAmount`)
                    FROM        `paymentvoucherdetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `paymentvoucherdetail`.`companyId`                         	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `paymentvoucherdetail`.`paymentVoucherId`                         	IN  (" . $paymentVoucherId . ")
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` 	=   '" . self::EQUITY . "'
                    AND         `paymentvoucherdetail`.`isActive`                          	=   1
                    GROUP BY    `paymentvoucherdetail`.`paymentVoucherDetailAmount`
                ) +
                 (
                    SELECT      SUM(`paymentVoucherDetailAmount`)
                    FROM        `paymentvoucher`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `paymentvoucherdetail`.`companyId`                         	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `paymentvoucherdetail`.`paymentVoucherId`                         	IN   (" . $paymentVoucherId . ")
                    AND         `paymentvoucherdetail`.`isActive`                          	=   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` 	=   '" . self::INCOME . "'
                    GROUP BY    `paymentvoucherdetail`.`paymentVoucherDetailAmount`
                )
                 -
                 (
                    SELECT      SUM(`paymentVoucherDetailAmount`)
                    FROM        `paymentvoucherdetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `paymentvoucherdetail`.`companyId`                         	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `paymentvoucherdetail`.`paymentVoucherId`                         	IN   (" . $paymentVoucherId . ")
                    AND         `paymentvoucherdetail`.`isActive`                          	=   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode`	=	'" . self::EXPENSES . "'
                    GROUP BY    `paymentvoucherdetail`.`paymentVoucherDetailAmount`
                )
            ) as `total`

            )";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT (
                (
                    SELECT      SUM([paymentVoucherDetailAmount])
                    FROM        [paymentVoucherDetail]

                    JOIN        [chartOfAccount]
                    ON          [paymentVoucherDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [paymentVoucherDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartofaccountcategory]
                    ON          [paymentVoucherDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [paymentVoucherDetail].[companyId]                         =   '" . $this->getCompanyId(
                        ) . "'
                    AND         [paymentVoucherDetail].[paymentVoucherId]                         IN  (" . $paymentVoucherId . ")
                    AND         [paymentVoucherDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::ASSET . "'
                    GROUP BY    [paymentVoucherDetail].[paymentVoucherDetailAmount]
                )
                -
                 (
                    SELECT      SUM([paymentVoucherDetailAmount])
                    FROM        [paymentVoucherDetail]

                    JOIN        [chartOfAccount]
                    ON          [paymentVoucherDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [paymentVoucherDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartofaccountcategory]
                    ON          [paymentVoucherDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [paymentVoucherDetail].[companyId]                         =   '" . $this->getCompanyId(
                        ) . "'
                    AND         [paymentVoucherDetail].[paymentVoucherId]                         IN   (" . $paymentVoucherId . ")
                    AND         [paymentVoucherDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::LIABILITY . "'
                    GROUP BY    [paymentVoucherDetail].[paymentVoucherDetailAmount]
                )
                 +
                 (
                    SELECT      SUM([paymentVoucherDetailAmount])
                    FROM        [paymentVoucherDetail]

                    JOIN        [chartOfAccount]
                    ON          [paymentVoucherDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [paymentVoucherDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartofaccountcategory]
                    ON          [paymentVoucherDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [paymentVoucherDetail].[companyId]                         =   '" . $this->getCompanyId(
                        ) . "'
                    AND         [paymentVoucherDetail].[paymentVoucherId]                         IN   (" . $paymentVoucherId . ")
                    AND         [paymentVoucherDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::EQUITY . "'
                    GROUP BY    [paymentVoucherDetail].[paymentVoucherDetailAmount]
                ) +
                 (
                    SELECT      SUM([paymentVoucherDetailAmount])
                    FROM        [paymentVoucherDetail]

                    JOIN        [chartOfAccount]
                    ON          [paymentVoucherDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [paymentVoucherDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartofaccountcategory]
                    ON          [paymentVoucherDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [paymentVoucherDetail].[companyId]                         =   '" . $this->getCompanyId(
                        ) . "'
                    AND         [paymentVoucherDetail].[paymentVoucherId]                         IN   (" . $paymentVoucherId . ")
                    AND         [paymentVoucherDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::INCOME . "'
                    GROUP BY    [paymentVoucherDetail].[paymentVoucherDetailAmount]
                )
                 -
                 (
                    SELECT      SUM([paymentVoucherDetailAmount])
                    FROM        [paymentVoucherDetail]

                    JOIN        [chartOfAccount]
                    ON          [paymentVoucherDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [paymentVoucherDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartofaccountcategory]
                    ON          [paymentVoucherDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [paymentVoucherDetail].[companyId]                         =   '" . $this->getCompanyId(
                        ) . "'
                    AND         [paymentVoucherDetail].[paymentVoucherId]                         IN   (" . $paymentVoucherId . ")
                    AND         [paymentVoucherDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::EXPENSES . "'
                    GROUP BY    [paymentVoucherDetail].[paymentVoucherDetailAmount]
                )
            ) as [trialBalance]

            )";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT (
                (
                    SELECT      SUM(JOURNALDETAILAMOUNT)
                    FROM        PAYMENTVOUCHERDETAIL
                    JOIN        CHARTOFACCOUNT
                    ON          PAYMENTVOUCHERDETAIL.COMPANYID                     	=   CHARTOFACCOUNT.COMPANYID
                    AND         PAYMENTVOUCHERDETAIL.CHARTOFACCOUNTID             	=   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          PAYMENTVOUCHERDETAIL.COMPANYID                    	=   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         	=   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       PAYMENTVOUCHERDETAIL.COMPANYID                    	=   '" . $this->getCompanyId() . "'
                    AND         PAYMENTVOUCHERDETAIL.JOURNALID                     	IN   (" . $paymentVoucherId . ")
                    AND         PAYMENTVOUCHERDETAIL.ISACTIVE                      	=   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE 	=   '" . self::INCOME . "'
                    GROUP BY    PAYMENTVOUCHERDETAIL.JOURNALDETAILAMOUNT
                )
                -
                 (
                    SELECT      SUM(PAYMENTVOUCHERDETAILAMOUNT)
                    FROM        PAYMENTVOUCHERDETAIL

                    JOIN        CHARTOFACCOUNT
                    ON          PAYMENTVOUCHERDETAIL.COMPANYID                     	=   CHARTOFACCOUNT.COMPANYID
                    AND         PAYMENTVOUCHERDETAIL.CHARTOFACCOUNTID          		=   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          PAYMENTVOUCHERDETAIL.COMPANYID                      =   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         	=	CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       PAYMENTVOUCHERDETAIL.COMPANYID                     	=   '" . $this->getCompanyId() . "'
                    AND         PAYMENTVOUCHERDETAIL.JOURNALID                     	IN   (" . $paymentVoucherId . ")
                    AND         PAYMENTVOUCHERDETAIL.ISACTIVE                       =   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE	=	'" . self::LIABILITY . "'
                    GROUP BY    PAYMENTVOUCHERDETAIL.JOURNALDETAILAMOUNT
                )
                 +
                 (
                    SELECT      SUM(PAYMENTVOUCHERDETAILAMOUNT)
                    FROM        PAYMENTVOUCHERDETAIL
                    JOIN        CHARTOFACCOUNT
                    ON          PAYMENTVOUCHERDETAIL.COMPANYID                     	=   CHARTOFACCOUNT.COMPANYID
                    AND         PAYMENTVOUCHERDETAIL.CHARTOFACCOUNTID              	=   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          PAYMENTVOUCHERDETAIL.COMPANYID                    	=   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         	=   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTID

                    WHERE       PAYMENTVOUCHERDETAIL.COMPANYID                    	=   '" . $this->getCompanyId() . "'
                    AND         PAYMENTVOUCHERDETAIL.JOURNALID                    	IN   (" . $paymentVoucherId . ")
                    AND         PAYMENTVOUCHERDETAIL.ISACTIVE                      	=   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE 	=   '" . self::EQUITY . "'
                    GROUP BY    PAYMENTVOUCHERDETAIL.PAYMENTVOUCHERDETAILAMOUNT
                ) +
                 (
                    SELECT      SUM(PAYMENTVOUCHERDETAILAMOUNT)
                    FROM        PAYMENTVOUCHER

                    JOIN        CHARTOFACCOUNT
                    ON          PAYMENTVOUCHERDETAIL.COMPANYID 						= 	CHARTOFACCOUNT.COMPANYID
                    AND         PAYMENTVOUCHERDETAIL.CHARTOFACCOUNTID 				= 	CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          PAYMENTVOUCHERDETAIL.COMPANYID                     	=   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         	=   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       PAYMENTVOUCHERDETAIL.COMPANYID                    	=   '" . $this->getCompanyId() . "'
                    AND         PAYMENTVOUCHERDETAIL.JOURNALID                    	IN   (" . $paymentVoucherId . ")
                    AND         PAYMENTVOUCHERDETAIL.ISACTIVE                      	=   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE	=   '" . self::INCOME . "'
                    GROUP BY    PAYMENTVOUCHERDETAIL.PAYMENTVOUCHERDETAILAMOUNT
                )
                 -
                 (
                    SELECT      SUM(PAYMENTVOUCHERDETAILAMOUNT)
                    FROM        PAYMENTVOUCHERDETAIL

                    JOIN        CHARTOFACCOUNT
                    ON          PAYMENTVOUCHERDETAIL.COMPANYID                     	=   CHARTOFACCOUNT.COMPANYID
                    AND         PAYMENTVOUCHERDETAIL.CHARTOFACCOUNTID             	=   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          PAYMENTVOUCHERDETAIL.COMPANYID                    	=   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID        		=   CHARTOFACCOUNTCATEGORY..CHARTOFACCOUNTCATEGORYID

                    WHERE       PAYMENTVOUCHERDETAIL.COMPANYID                      =   '" . $this->getCompanyId() . "'
                    AND         PAYMENTVOUCHERDETAIL.JOURNALID                    	IN   (" . $paymentVoucherId . ")
                    AND         PAYMENTVOUCHERDETAIL.ISACTIVE                     	=   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE	=	'" . self::EXPENSES . "'
                    GROUP BY    PAYMENTVOUCHERDETAIL.PAYMENTVOUCHERDETAILAMOUNT
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
     * Return Total Payment Voucher Amount
     * @param int $paymentVoucherId Main Table
     * @param string $type 1->debit,2->credit
     * @return double $total
     */
    public function getTotalPaymentVoucherAmount($paymentVoucherId, $type) {
        $total = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      SUM(`paymentVoucherDetailAmount`) AS `total`
            FROM        `paymentvoucherdetail`

            WHERE       `paymentvoucherdetail`.`companyId`			=   '" . $this->getCompanyId() . "'
            AND         `paymentvoucherdetail`.`paymentVoucherId`	IN   (" . $paymentVoucherId . ")
            AND         `paymentvoucherdetail`.`isActive`			=   1";
            if ($type == 1) {
                $sql .= "  AND `paymentVoucherDetailAmount` >0 ";
            } else {
                $sql .= "  AND `paymentVoucherDetailAmount` < 0 ";
            }
            // $sql .= "
            //  GROUP BY    `journaldetail`.`journalDetailAmount`
            //  ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql .= "
            SELECT      SUM([paymentVoucherDetailAmount]) AS [total]
            FROM        [paymentVoucherDetail]

            WHERE       [paymentVoucherDetail].[companyId]			=   '" . $this->getCompanyId() . "'
            AND         [paymentVoucherDetail].[paymentVoucherId]	IN   (" . $paymentVoucherId . ")
            AND         [paymentVoucherDetail].[isActive]			=   1";
                if ($type == 1) {
                    $sql .= "  AND [paymentVoucherDetailAmount] >0 ";
                } else {
                    $sql .= "  AND [paymentVoucherDetailAmount] < 0 ";
                }
                $sql .= "
            GROUP BY    [paymentVoucherDetail].[paymentVoucherDetailAmount]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT      SUM(JOURNALDETAILAMOUNT) AS \"total\"
            FROM        PAYMENTVOUCHERDETAIL

            WHERE       PAYMENTVOUCHERDETAIL.COMPANYID			=   '" . $this->getCompanyId() . "'
            AND         PAYMENTVOUCHERDETAIL.PAYMENTVOUCHERID	IN   (" . $paymentVoucherId . ")
            AND         PAYMENTVOUCHERDETAIL.ISACTIVE			=   1";
                    if ($type == 1) {
                        $sql .= "  AND PAYMENTVOUCHERDETAILAMOUNT >0 ";
                    } else {
                        $sql .= "  AND PAYMENTVOUCHERDETAILAMOUNT < 0 ";
                    }
                    $sql .= "
            GROUP BY    PAYMENTVOUCHERDETAIL.PAYMENTVOUCHERDETAILAMOUNT
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
     * Return Total debit,credit and trial balance
     * @param int $paymentVoucherId Payment Voucher Primary Key
     * @return array
     */
    public function getTotalPaymentVoucherDetail($paymentVoucherId) {
        header('Content-Type:application/json; charset=utf-8');
        $totalDebit = $this->getTotalPaymentVoucherAmount($paymentVoucherId, 1);
        $totalCredit = $this->getTotalPaymentVoucherAmount($paymentVoucherId, 2);
        $trialBalance = $this->getCheckTrialBalance($paymentVoucherId);
        return array(
            "success" => true,
            "totalDebit" => $totalDebit,
            "totalCredit" => $totalCredit,
            "trialBalance" => $trialBalance
        );
    }

    /**
     * @param string $countryDescription
     */
    public function setCountryDescription($countryDescription) {
        $this->countryDescription = $countryDescription;
    }

    /**
     * @return string
     */
    public function getCountryDescription() {
        return $this->countryDescription;
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
    public function getCountryId() {
        return $this->countryId;
    }


    /**
     * Set Financial Year Primary key
     * @param int $financeYearId Year
     * @return $this|ConfigClass
     */
    public function setFinanceYearId($financeYearId) {
        $this->financeYearId = $financeYearId;
        return $this;
    }

    /**
     * Return Finance Year Primary Key
     * @return int
     */
    public function getFinanceYearId() {
        return $this->financeYearId;
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
    public function getTransactionTypeCode() {
        return $this->transactionTypeCode;
    }

    /**
     * @param string $transactionTypeDescription
     */
    public function setTransactionTypeDescription($transactionTypeDescription) {
        $this->transactionTypeDescription = $transactionTypeDescription;
    }

    /**
     * @return string
     */
    public function getTransactionTypeDescription() {
        return $this->transactionTypeDescription;
    }

    /**
     * @param int $transactionTypeId
     */
    public function setTransactionTypeId($transactionTypeId) {
        $this->transactionTypeId = $transactionTypeId;
    }

    /**
     * @return int
     */
    public function getTransactionTypeId() {
        return $this->transactionTypeId;
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