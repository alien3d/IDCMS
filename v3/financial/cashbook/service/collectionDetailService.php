<?php

namespace Core\Financial\Cashbook\CollectionDetail\Service;

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
 * Class CollectionDetail
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\Cashbook\CollectionDetail\Service
 * @subpackage Cashbook
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class CollectionDetailService extends ConfigClass {

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
        } else  if ($this->getVendor() == self::MSSQL) {
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
            } else  if ($this->getVendor() == self::ORACLE) {
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
     * Return Collection
     * @return array|string
     */
    public function getCollection() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `collectionId`,
                     `collectionDescription`
         FROM        `collection`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else  if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [collectionId],
                     [collectionDescription]
         FROM        [collection]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      COLLECTIONID AS \"collectionId\",
                     COLLECTIONDESCRIPTION AS \"collectionDescription\"
         FROM        COLLECTION
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
                    $str .= "<option value='" . $row['collectionId'] . "'>" . $d . ". " . $row['collectionDescription'] . "</option>";
                } else  if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
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
     * Return Collection Default Value
     * @return int
     */
    public function getCollectionDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $collectionId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `collectionId`
         FROM        	`collection`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [collectionId],
         FROM        [collection]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else  if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      COLLECTIONID AS \"collectionId\",
         FROM        COLLECTION
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
            $collectionId = $row['collectionId'];
        }
        return $collectionId;
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
        } else if ($this->getVendor() == self::MSSQL) {
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
            } else if ($this->getVendor() == self::ORACLE) {
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
                } else if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
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
        } else if ($this->getVendor() == self::MSSQL) {
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
					 `countryCurrencyCode`,
                     `countryDescription`
         FROM        `country`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `countryDescription`;";
        } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [countryId],
					 [countryCurrencyCode],
                     [countryDescription]
         FROM        [country]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [countryDescription]";
            } else if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      COUNTRYID AS \"countryId\",
					 COUNTRYCURRENCYCODE AS \"countryCurrencyCode\",
                     COUNTRYDESCRIPTION AS \"countryDescription\"
         FROM        COUNTRY
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         ORDER BY    COUNTRYDESCRIPTION";
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
                    $str .= "<option value='" . $row['countryId'] . "'>" . $row['countryCurrencyCode'] . " - " . $row['countryDescription'] . "</option>";
                } else if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
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
        } else  if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [countryId],
         FROM        [country]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else  if ($this->getVendor() == self::ORACLE) {
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
         WHERE       `businesspartner`.`isActive`  =   1
         AND         `businesspartner`.`companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `businesspartnercategory`.`businessPartnerCategoryDescription`,
					 `businesspartner`.`businessPartnerCompany`;";
        } else  if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [businessPartner].[businessPartnerId],
                     [businessPartner].[businessPartnerCompany],
					 [businessPartnerCategory].[businessPartnerCategoryDescription]
         FROM        [businessPartner]
		 JOIN	     [businessPartnerCategory]
		 ON			 [businessPartnerCategory].[companyId] 					= 	[businessPartner].[companyId]
		 AND		 [businessPartnerCategory].[businessPartnerCategoryId] 	= 	[businessPartner].[businessPartnerCategoryId]
         WHERE       [businessPartner].[isActive]  							=	1
         AND         [businessPartner].[companyId] 							=   '" . $this->getCompanyId() . "'
         ORDER BY    [businessPartnerCategory].[businessPartnerCategoryDescription],
					 [businessPartner].[businessPartnerCompany]	";
            } else if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      BUSINESSPARTNER.BUSINESSPARTNERID AS \"businessPartnerId\",
                     BUSINESSPARTNER.BUSINESSPARTNERCOMPANY AS \"businessPartnerCompany\",
					 BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYDESCRIPTION AS \"businessPartnerCategoryDescription\"
         FROM        BUSINESSPARTNER
		 JOIN	     BUSINESSPARTNERCATEGORY
		 ON			 BUSINESSPARTNERCATEGORY.COMPANYID 					= 	BUSINESSPARTNER.COMPANYID
		 AND		 BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYID 	= 	BUSINESSPARTNER.BUSINESSPARTNERCATEGORYIID
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
     * Return TransactionType
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
        } else  if ($this->getVendor() == self::MSSQL) {
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
     * @param int $collectionId
     * @return string $trialBalance
     */
    private function getCheckTrialBalance($collectionId) {
        $trialBalance = 0;
        $sql = null;

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT (
                (
                    SELECT      SUM(`collectionDetailAmount`)
                    FROM        `collectiondetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `collectiondetail`.`companyId`                         	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `collectiondetail`.`collectionId`                         	IN   (" . $collectionId . ")
                    AND         `collectiondetail`.`isActive`                          	=   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode`	=   '" . self::ASSET . "'

                    GROUP BY    `collectiondetail`.`collectionDetailAmount`
                )
                -
                 (
                    SELECT      SUM(`collectionDetailAmount`)
                    FROM        `collectiondetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `collectiondetail`.`companyId`                        	 	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `collectiondetail`.`collectionId`                         	IN   (" . $collectionId . ")
                    AND         `collectiondetail`.`isActive`                          	=   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode`	=	'" . self::LIABILITY . "'
                    GROUP BY    `collectiondetail`.`collectionDetailAmount`
                )
                 +
                 (
                    SELECT      SUM(`collectionDetailAmount`)
                    FROM        `collectiondetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `collectiondetail`.`companyId`                         	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `collectiondetail`.`collectionId`                         	IN  (" . $collectionId . ")
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` 	=   '" . self::EQUITY . "'
                    AND         `collectiondetail`.`isActive`                          	=   1
                    GROUP BY    `collectiondetail`.`collectionDetailAmount`
                ) +
                 (
                    SELECT      SUM(`collectionDetailAmount`)
                    FROM        `paymentvoucher`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `collectiondetail`.`companyId`                         	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `collectiondetail`.`collectionId`                         	IN   (" . $collectionId . ")
                    AND         `collectiondetail`.`isActive`                          	=   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` 	=   '" . self::INCOME . "'
                    GROUP BY    `collectiondetail`.`collectionDetailAmount`
                )
                 -
                 (
                    SELECT      SUM(`collectionDetailAmount`)
                    FROM        `collectiondetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `collectiondetail`.`companyId`                         	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `collectiondetail`.`collectionId`                         	IN   (" . $collectionId . ")
                    AND         `collectiondetail`.`isActive`                          	=   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode`	=	'" . self::EXPENSES . "'
                    GROUP BY    `collectiondetail`.`collectionDetailAmount`
                )
            ) as `total`

            )";
        } else  if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT (
                (
                    SELECT      SUM([collectionDetailAmount])
                    FROM        [collectionDetail]

                    JOIN        [chartOfAccount]
                    ON          [collectionDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [collectionDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [collectionDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [collectionDetail].[companyId]                         =   '" . $this->getCompanyId() . "'
                    AND         [collectionDetail].[collectionId]                         IN  (" . $collectionId . ")
                    AND         [collectionDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::ASSET . "'
                    GROUP BY    [collectionDetail].[collectionDetailAmount]
                )
                -
                 (
                    SELECT      SUM([collectionDetailAmount])
                    FROM        [collectionDetail]

                    JOIN        [chartOfAccount]
                    ON          [collectionDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [collectionDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [collectionDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [collectionDetail].[companyId]                         =   '" . $this->getCompanyId() . "'
                    AND         [collectionDetail].[collectionId]                         IN   (" . $collectionId . ")
                    AND         [collectionDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::LIABILITY . "'
                    GROUP BY    [collectionDetail].[collectionDetailAmount]
                )
                 +
                 (
                    SELECT      SUM([collectionDetailAmount])
                    FROM        [collectionDetail]

                    JOIN        [chartOfAccount]
                    ON          [collectionDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [collectionDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [collectionDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [collectionDetail].[companyId]                         =   '" . $this->getCompanyId() . "'
                    AND         [collectionDetail].[collectionId]                         IN   (" . $collectionId . ")
                    AND         [collectionDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::EQUITY . "'
                    GROUP BY    [collectionDetail].[collectionDetailAmount]
                ) +
                 (
                    SELECT      SUM([collectionDetailAmount])
                    FROM        [collectionDetail]

                    JOIN        [chartOfAccount]
                    ON          [collectionDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [collectionDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartofaccountCategory]
                    ON          [collectionDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [collectionDetail].[companyId]                         =   '" . $this->getCompanyId() . "'
                    AND         [collectionDetail].[collectionId]                         IN   (" . $collectionId . ")
                    AND         [collectionDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::INCOME . "'
                    GROUP BY    [collectionDetail].[collectionDetailAmount]
                )
                 -
                 (
                    SELECT      SUM([collectionDetailAmount])
                    FROM        [collectionDetail]

                    JOIN        [chartOfAccount]
                    ON          [collectionDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [collectionDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [collectionDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [collectionDetail].[companyId]                         =   '" . $this->getCompanyId() . "'
                    AND         [collectionDetail].[collectionId]                         IN   (" . $collectionId . ")
                    AND         [collectionDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::EXPENSES . "'
                    GROUP BY    [collectionDetail].[collectionDetailAmount]
                )
            ) as [trialBalance]

            )";
            } else if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT (
                (
                    SELECT      SUM(JOURNALDETAILAMOUNT)
                    FROM        COLLECTIONDETAIL
                    JOIN        CHARTOFACCOUNT
                    ON          COLLECTIONDETAIL.COMPANYID                     	=   CHARTOFACCOUNT.COMPANYID
                    AND         COLLECTIONDETAIL.CHARTOFACCOUNTID             	=   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          COLLECTIONDETAIL.COMPANYID                    	=   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         	=   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       COLLECTIONDETAIL.COMPANYID                    	=   '" . $this->getCompanyId() . "'
                    AND         COLLECTIONDETAIL.COLLECTIONID                     	IN   (" . $collectionId . ")
                    AND         COLLECTIONDETAIL.ISACTIVE                      	=   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE 	=   '" . self::INCOME . "'
                    GROUP BY    COLLECTIONDETAIL.JOURNALDETAILAMOUNT
                )
                -
                 (
                    SELECT      SUM(COLLECTIONDETAILAMOUNT)
                    FROM        COLLECTIONDETAIL

                    JOIN        CHARTOFACCOUNT
                    ON          COLLECTIONDETAIL.COMPANYID                     	=   CHARTOFACCOUNT.COMPANYID
                    AND         COLLECTIONDETAIL.CHARTOFACCOUNTID          		=   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          COLLECTIONDETAIL.COMPANYID                      =   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         	=	CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       COLLECTIONDETAIL.COMPANYID                     	=   '" . $this->getCompanyId() . "'
                    AND         COLLECTIONDETAIL.COLLECTIONID                     	IN   (" . $collectionId . ")
                    AND         COLLECTIONDETAIL.ISACTIVE                       =   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE	=	'" . self::LIABILITY . "'
                    GROUP BY    COLLECTIONDETAIL.JOURNALDETAILAMOUNT
                )
                 +
                 (
                    SELECT      SUM(COLLECTIONDETAILAMOUNT)
                    FROM        COLLECTIONDETAIL
                    JOIN        CHARTOFACCOUNT
                    ON          COLLECTIONDETAIL.COMPANYID                     	=   CHARTOFACCOUNT.COMPANYID
                    AND         COLLECTIONDETAIL.CHARTOFACCOUNTID              	=   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          COLLECTIONDETAIL.COMPANYID                    	=   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         	=   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTID

                    WHERE       COLLECTIONDETAIL.COMPANYID                    	=   '" . $this->getCompanyId() . "'
                    AND         COLLECTIONDETAIL.COLLECTIONID                    	IN   (" . $collectionId . ")
                    AND         COLLECTIONDETAIL.ISACTIVE                      	=   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE 	=   '" . self::EQUITY . "'
                    GROUP BY    COLLECTIONDETAIL.COLLECTIONDETAILAMOUNT
                ) +
                 (
                    SELECT      SUM(COLLECTIONDETAILAMOUNT)
                    FROM        PAYMENTVOUCHER

                    JOIN        CHARTOFACCOUNT
                    ON          COLLECTIONDETAIL.COMPANYID 						= 	CHARTOFACCOUNT.COMPANYID
                    AND         COLLECTIONDETAIL.CHARTOFACCOUNTID 				= 	CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          COLLECTIONDETAIL.COMPANYID                     	=   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         	=   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       COLLECTIONDETAIL.COMPANYID                    	=   '" . $this->getCompanyId() . "'
                    AND         COLLECTIONDETAIL.COLLECTIONID                    	IN   (" . $collectionId . ")
                    AND         COLLECTIONDETAIL.ISACTIVE                      	=   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE	=   '" . self::INCOME . "'
                    GROUP BY    COLLECTIONDETAIL.COLLECTIONDETAILAMOUNT
                )
                 -
                 (
                    SELECT      SUM(COLLECTIONDETAILAMOUNT)
                    FROM        COLLECTIONDETAIL

                    JOIN        CHARTOFACCOUNT
                    ON          COLLECTIONDETAIL.COMPANYID                     	=   CHARTOFACCOUNT.COMPANYID
                    AND         COLLECTIONDETAIL.CHARTOFACCOUNTID             	=   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          COLLECTIONDETAIL.COMPANYID                    	=   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID        		=   CHARTOFACCOUNTCATEGORY..CHARTOFACCOUNTCATEGORYID

                    WHERE       COLLECTIONDETAIL.COMPANYID                      =   '" . $this->getCompanyId() . "'
                    AND         COLLECTIONDETAIL.COLLECTIONID                    	IN   (" . $collectionId . ")
                    AND         COLLECTIONDETAIL.ISACTIVE                     	=   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE	=	'" . self::EXPENSES . "'
                    GROUP BY    COLLECTIONDETAIL.COLLECTIONDETAILAMOUNT
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
     * @param int $collectionId Collection Primary key
     * @param string $type 1->debit,2->credit
     * @return double $total
     */
    public function getTotalCollectionAmount($collectionId, $type) {
        $total = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      SUM(`collectionDetailAmount`) AS `total`
            FROM        `collectiondetail`

            WHERE       `collectiondetail`.`companyId`		=   '" . $this->getCompanyId() . "'
            AND         `collectiondetail`.`collectionId`	IN   (" . $collectionId . ")
            AND         `collectiondetail`.`isActive`		=   1";
            if ($type == 1) {
                $sql .= "  AND `collectionDetailAmount` >0 ";
            } else {
                $sql .= "  AND `collectionDetailAmount` < 0 ";
            }
            // $sql .= "
            //  GROUP BY    `journaldetail`.`journalDetailAmount`
            //  ";
        } else if ($this->getVendor() == self::MSSQL) {
                $sql .= "
            SELECT      SUM([collectionDetailAmount]) AS [total]
            FROM        [collectionDetail]

            WHERE       [collectionDetail].[companyId]		=   '" . $this->getCompanyId() . "'
            AND         [collectionDetail].[collectionId]	IN   (" . $collectionId . ")
            AND         [collectionDetail].[isActive]		=   1";
                if ($type == 1) {
                    $sql .= "  AND [collectionDetailAmount] >0 ";
                } else {
                    $sql .= "  AND [collectionDetailAmount] < 0 ";
                }
                $sql .= "
            GROUP BY    [collectionDetail].[collectionDetailAmount]";
            } else  if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT      SUM(COLLECTIONDETAILAMOUNT) AS \"total\"
            FROM        COLLECTIONDETAIL

            WHERE       COLLECTIONDETAIL.COMPANYID		=   '" . $this->getCompanyId() . "'
            AND         COLLECTIONDETAIL.COLLECTIONID	IN   (" . $collectionId . ")
            AND         COLLECTIONDETAIL.ISACTIVE		=   1";
                    if ($type == 1) {
                        $sql .= "  AND COLLECTIONDETAILAMOUNT >0 ";
                    } else {
                        $sql .= "  AND COLLECTIONDETAILAMOUNT < 0 ";
                    }
                    $sql .= "
            GROUP BY    COLLECTIONDETAIL.COLLECTIONDETAILAMOUNT
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
     * @param int $collectionId Collection Primary Key
     * @return array
     */
    public function getTotalCollectionDetail($collectionId) {
        header('Content-Type:application/json; charset=utf-8');
        $totalDebit = $this->getTotalCollectionAmount($collectionId, 1);
        $totalCredit = $this->getTotalCollectionAmount($collectionId, 2);
        $trialBalance = $this->getCheckTrialBalance($collectionId);
        return array(
            "success" => true,
            "totalDebit" => $totalDebit,
            "totalCredit" => $totalCredit,
            "trialBalance" => $trialBalance
        );
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
     * @param int $financeYearId
     */
    public function setFinanceYearId($financeYearId) {
        $this->financeYearId = $financeYearId;
    }

    /**
     * @return int
     */
    public function getFinanceYearId() {
        return $this->financeYearId;
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