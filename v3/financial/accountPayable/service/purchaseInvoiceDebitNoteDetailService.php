<?php

namespace Core\Financial\AccountPayable\PurchaseInvoiceDebitNoteDetail\Service;

use Core\ConfigClass;

$x = addslashes(realpath(__FILE__));
// auto detect if \ consider come from windows else / from linux
$pos = strpos($x, "\\");
if ($pos !== false) {
    $d = explode("\\", $x);
} else {
    $d = explode("/", $x);
}
$newPath = null;
for ($i = 0; $i < count($d); $i ++) {
    // if find the library or package then stop
    if ($d[$i] == 'library' || $d[$i] == 'v3') {
        break;
    }
    $newPath[] .= $d[$i] . "/";
}
$fakeDocumentRoot = null;
for ($z = 0; $z < count($newPath); $z ++) {
    $fakeDocumentRoot .= $newPath[$z];
}
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot); // start
require_once ($newFakeDocumentRoot . "library/class/classAbstract.php");
require_once ($newFakeDocumentRoot . "library/class/classShared.php");

/**
 * Class PurchaseInvoiceDebitNoteDetailService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountPayable\PurchaseInvoiceDebitNoteDetail\Service
 * @subpackage AccountPayable
 * @link http://www.hafizan.com
 * @link http://en.wikipedia.org/wiki/Debit_note WikiPedia Debit Note
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class PurchaseInvoiceDebitNoteDetailService extends ConfigClass {

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
     * @var int
     */
    private $chartOfAccountCategoryCode;

    /**
     * Chart Of Account Category Description
     * @var int
     */
    private $chartOfAccountCategoryDescription;

    /**
     * Chart Of Account Type Code
     * @var int
     */
    private $chartOfAccountTypeCode;

    /**
     * Chart Of Account Type Description
     * @var int
     */
    private $chartOfAccountTypeDescription;

    /**
     * Chart Of Account Category Description
     * @var int
     */
    private $chartOfAccountDescription;

    /**
     * Chart Of Account Category Description
     * @var int
     */
    private $chartOfAccountNumber;

    /**
     * Company Name
     * @var int
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
    }

    /**
     * Return PurchaseInvoice
     * @return array|string
     */
    public function getPurchaseInvoice() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `purchaseInvoiceId`,
                     `purchaseInvoiceDescription`
         FROM        `purchaseinvoice`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [purchaseInvoiceId],
                     [purchaseInvoiceDescription]
         FROM        [purchaseInvoice]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      PURCHASEINVOICEID AS \"purchaseInvoiceId\",
                     PURCHASEINVOICEDESCRIPTION AS \"purchaseInvoiceDescription\"
         FROM        PURCHASEINVOICE
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
            while (($row = $this->q->fetchArray($result)) == TRUE) {
                if ($this->getServiceOutput() == 'option') {
                    $str.="<option value='" . $row['purchaseInvoiceId'] . "'>" . $d . ". " . $row['purchaseInvoiceDescription'] . "</option>";
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
        } else if ($this->getServiceOutput() == 'html') {
            return $items;
        }
        return false;
    }

    /**
     * Return PurchaseInvoice Default Value
     * @return int
     */
    public function getPurchaseInvoiceDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $purchaseInvoiceId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `purchaseInvoiceId`
         FROM        	`purchaseinvoice`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [purchaseInvoiceId],
         FROM        [purchaseInvoice]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      PURCHASEINVOICEID AS \"purchaseInvoiceId\",
         FROM        PURCHASEINVOICE
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
            $purchaseInvoiceId = $row['purchaseInvoiceId'];
        }
        return $purchaseInvoiceId;
    }

    /**
     * Return PurchaseInvoiceDebitNote
     * @return array|string
     */
    public function getPurchaseInvoiceDebitNote() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `purchaseInvoiceDebitNoteId`,
                     `purchaseInvoiceDebitNoteDescription`
         FROM        `purchaseinvoicedebitnote`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [purchaseInvoiceDebitNoteId],
                     [purchaseInvoiceDebitNoteDescription]
         FROM        [purchaseInvoiceDebitNote]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      PURCHASEINVOICEDEBITNOTEID AS \"purchaseInvoiceDebitNoteId\",
                     PURCHASEINVOICEDEBITNOTEDESCRIPTION AS \"purchaseInvoiceDebitNoteDescription\"
         FROM        PURCHASEINVOICEDEBITNOTE
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
            while (($row = $this->q->fetchArray($result)) == TRUE) {
                if ($this->getServiceOutput() == 'option') {
                    $str.="<option value='" . $row['purchaseInvoiceDebitNoteId'] . "'>" . $d . ". " . $row['purchaseInvoiceDebitNoteDescription'] . "</option>";
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
        } else if ($this->getServiceOutput() == 'html') {
            return $items;
        }
        return false;
    }

    /**
     * Return PurchaseInvoiceDebitNote Default Value
     * @return int
     */
    public function getPurchaseInvoiceDebitNoteDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $purchaseInvoiceDebitNoteId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `purchaseInvoiceDebitNoteId`
         FROM        	`purchaseinvoicedebitnote`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [purchaseInvoiceDebitNoteId],
         FROM        [purchaseInvoiceDebitNote]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      PURCHASEINVOICEDEBITNOTEID AS \"purchaseInvoiceDebitNoteId\",
         FROM        PURCHASEINVOICEDEBITNOTE
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
            $purchaseInvoiceDebitNoteId = $row['purchaseInvoiceDebitNoteId'];
        }
        return $purchaseInvoiceDebitNoteId;
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
     * Return Business Partner
     * @param null|int $businessPartnerCategoryId Business Partner Category
     * @return array|string
     */
    public function getBusinessPartner($businessPartnerCategoryId = null) {
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
			AND			`isCreditor`=1
			AND         `businesspartner`.`companyId` =   '" . $this->getCompanyId() . "'";
            if ($businessPartnerCategoryId) {
                $sql.=" AND `businesspartner`.`businessPartnerCategoryId`='" . $businessPartnerCategoryId . "'";
            }
            $sql.="
			ORDER BY    `businesspartnercategory`.`businessPartnerCategoryDescription`,
								`businesspartner`.`businessPartnerCompany`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      [businessPartner].[businessPartnerId],
				 [businessPartner].[businessPartnerCompany],
				 [businessPartnerCategory].[businessPartnerCategoryDescription]
			FROM        [businessPartner]
			JOIN	     [businessPartnerCategory]
			ON			 [businessPartnerCategory].[companyId] 					= 	[businessPartner].[companyId]
			AND		 [businessPartnerCategory].[businessPartnerCategoryId] 	= 	[businessPartner].[businessPartnerCategoryId]
			WHERE       [businessPartner].[isActive]  							=	1
			AND			[isCreditor]=1
			AND         [businessPartner].[companyId] 							=   '" . $this->getCompanyId() . "'";
            if ($businessPartnerCategoryId) {
                $sql.=" AND [businessPartner].[businessPartnerCategoryId]='" . $businessPartnerCategoryId . "'";
            }
            $sql.="
			ORDER BY    [businessPartnerCategory].[businessPartnerCategoryDescription],
			[businessPartner].[businessPartnerCompany]	";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT      BUSINESSPARTNER.BUSINESSPARTNERID AS \"businessPartnerId\",
							 BUSINESSPARTNER.BUSINESSPARTNERCOMPANY AS \"businessPartnerCompany\",
							 BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYDESCRIPTION AS \"businessPartnerCategoryDescription\"
			FROM        BUSINESSPARTNER
			JOIN	     	BUSINESSPARTNERCATEGORY
			ON			 BUSINESSPARTNERCATEGORY.COMPANYID 					= 	BUSINESSPARTNER.COMPANYID
			AND		 	BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYID 	= 	BUSINESSPARTNER.BUSINESSPARTNERCATEGORYIID
			AND			ISCREDITOR=1
			WHERE       BUSINESSPARTNER.ISACTIVE    						=   1";
            if ($businessPartnerCategoryId) {
                $sql.=" AND BUSINESSPARTNER.BUSINESSPARTNERCATEGORYID='" . $businessPartnerCategoryId . "'";
            }
            $sql.="
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
            $d = 0;
            $businessPartnerCategoryDescription = null;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($d != 0) {
                    if ($businessPartnerCategoryDescription != $row['businessPartnerCategoryDescription']) {
                        $str .= "</optgroup><optgroup label=\"" . $row['businessPartnerCategoryDescription'] . "\">";
                    }
                } else {
                    $str .= "<optgroup label=\"" . $row['businessPartnerCategoryDescription'] . "\">";
                }
                $businessPartnerCategoryDescription = $row['businessPartnerCategoryDescription'];

                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['businessPartnerId'] . "'>" . $d . ". " . $row['businessPartnerCompany'] . "</option>";
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
     * Return Business Partner Default Value
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
        } else if ($this->getVendor() == self::ORACLE) {
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
     * Return Total Purchase Invoice Amount
     * @param int $purchaseInvoiceDebitNoteId Main Table
     * @param string $type 1->debit,2->credit
     * @return double $total
     */
    public function getTotalPurchaseInvoiceAmount($purchaseInvoiceDebitNoteId, $type) {
        $total = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      SUM(`purchaseInvoiceDebitNoteDetailAmount`) AS `total`
            FROM        `puchaseinvoicedebitnotedetail`

            WHERE       `purchaseinvoicedebitnotedetail`.`companyId`                         =   '" . $this->getCompanyId() . "'
            AND         `purchaseinvoicedebitnotedetail`.`purchaseInvoiceDebitNoteId`                 IN   (" . $purchaseInvoiceDebitNoteId . ")
            AND         `purchaseinvoicedebitnotedetail`.`isActive`                          =   1";
            if ($type == 1) {
                $sql .= "  AND `purchaseInvoiceDebitNoteDetailAmount` >0 ";
            } else {
                $sql .= "  AND `purchaseInvoiceDebitNoteDetailAmount` < 0 ";
            }
            // $sql .= "
            //  GROUP BY    `purchaseinvoicedebitnotedetail`.`purchaseInvoiceDebitNoteDetailAmount`
            //  ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql .= "
            SELECT      SUM([purchaseInvoiceDebitNoteDetailAmount]) AS [total]
            FROM        [purchaseInvoiceDetail]

            WHERE       [purchaseInvoiceDebitNoteDetail].[companyId]                         =   '" . $this->getCompanyId() . "'
            AND         [purchaseInvoiceDebitNoteDetail].[purchaseInvoiceDebitNoteId]                         IN   (" . $purchaseInvoiceDebitNoteId . ")
            AND         [purchaseInvoiceDebitNoteDetail].[isActive]                          =   1";
                if ($type == 1) {
                    $sql .= "  AND [purchaseInvoiceDebitNoteDetailAmount] >0 ";
                } else {
                    $sql .= "  AND [purchaseInvoiceDebitNoteDetailAmount] < 0 ";
                }
                $sql .= "
            GROUP BY    [purchaseInvoiceDebitNoteDetail].[purchaseInvoiceDebitNoteDetailAmount]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT      SUM(PURCHASEINVOICEDEBITNOTEDETAILAMOUNT) AS \"total\"
            FROM        PURCHASEINVOICEDEBITNOTEDETAIL

            WHERE       PURCHASEINVOICEDEBITNOTEDETAIL.COMPANYID                         =   '" . $this->getCompanyId() . "'
            AND         PURCHASEINVOICEDEBITNOTEDETAIL.PURCHASEINVOICEDEBITNOTEID                         IN   (" . $purchaseInvoiceDebitNoteId . ")
            AND         PURCHASEINVOICEDEBITNOTEDETAIL.ISACTIVE                          =   1";
                    if ($type == 1) {
                        $sql .= "  AND PURCHASEINVOICEDEBITNOTEDETAILAMOUNT >0 ";
                    } else {
                        $sql .= "  AND PURCHASEINVOICEDEBITNOTEDETAILAMOUNT < 0 ";
                    }
                    $sql .= "
            GROUP BY    PURCHASEINVOICEDEBITNOTEDETAIL.PURCHASEINVOICEDEBITNOTEDETAILAMOUNT
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
     * Set Business Partner Company
     * @return string
     */
    public function getBusinessPartnerCompany() {
        return $this->businessPartnerCompany;
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
     * Return Chart Of Account Category Code
     * @return int
     */
    public function getChartOfAccountCategoryCode() {
        return $this->chartOfAccountCategoryCode;
    }

    /**
     * Return Chart Of Account Category Code
     * @param int $chartOfAccountCategoryCode
     * @return $this
     */
    public function setChartOfAccountCategoryCode($chartOfAccountCategoryCode) {
        $this->chartOfAccountCategoryCode = $chartOfAccountCategoryCode;
        return $this;
    }

    /**
     * Return Chart Of Account Category Description
     * @return int
     */
    public function getChartOfAccountCategoryDescription() {
        return $this->chartOfAccountCategoryDescription;
    }

    /**
     * @param int $chartOfAccountCategoryDescription
     */
    public function setChartOfAccountCategoryDescription($chartOfAccountCategoryDescription) {
        $this->chartOfAccountCategoryDescription = $chartOfAccountCategoryDescription;
    }

    /**
     * Return Chart Of Account Category
     * @return int
     */
    public function getChartOfAccountCategoryId() {
        return $this->chartOfAccountCategoryId;
    }

    /**
     * Set Chart Of Account Category
     * @param int $chartOfAccountCategoryId
     * @return $this
     */
    public function setChartOfAccountCategoryId($chartOfAccountCategoryId) {
        $this->chartOfAccountCategoryId = $chartOfAccountCategoryId;
        return $this;
    }

    /**
     * Return Chart Of Account Description
     * @return int
     */
    public function getChartOfAccountDescription() {
        return $this->chartOfAccountDescription;
    }

    /**
     * Set Chart Of Account Description
     * @param int $chartOfAccountDescription
     * @return $this
     */
    public function setChartOfAccountDescription($chartOfAccountDescription) {
        $this->chartOfAccountDescription = $chartOfAccountDescription;
        return $this;
    }

    /**
     * @return int
     */
    public function getChartOfAccountId() {
        return $this->chartOfAccountId;
    }

    /**
     * Set Chart Of Account
     * @param int $chartOfAccountId
     * @return $this
     */
    public function setChartOfAccountId($chartOfAccountId) {
        $this->chartOfAccountId = $chartOfAccountId;
        return $this;
    }

    /**
     * Return Chart Of Account Number
     * @return int
     */
    public function getChartOfAccountNumber() {
        return $this->chartOfAccountNumber;
    }

    /**
     * @param int $chartOfAccountNumber
     */
    public function setChartOfAccountNumber($chartOfAccountNumber) {
        $this->chartOfAccountNumber = $chartOfAccountNumber;
    }

    /**
     * @return int
     */
    public function getChartOfAccountTypeCode() {
        return $this->chartOfAccountTypeCode;
    }

    /**
     * Return Chart Of Account Type Code
     * @param int $chartOfAccountTypeCode
     * @return $this
     */
    public function setChartOfAccountTypeCode($chartOfAccountTypeCode) {
        $this->chartOfAccountTypeCode = $chartOfAccountTypeCode;
        return $this;
    }

    /**
     * Return Chart Of Account Type Description
     * @return int
     */
    public function getChartOfAccountTypeDescription() {
        return $this->chartOfAccountTypeDescription;
    }

    /**
     * Set Chart Of Type Description
     * @param int $chartOfAccountTypeDescription
     * @return $this
     */
    public function setChartOfAccountTypeDescription($chartOfAccountTypeDescription) {
        $this->chartOfAccountTypeDescription = $chartOfAccountTypeDescription;
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
     * @return $this
     */
    public function setCountryCurrencyCode($countryCurrencyCode) {
        $this->countryCurrencyCode = $countryCurrencyCode;
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
     * Set Chart Of Account Type Primary Key
     * @param int $chartOfAccountTypeId
     * @return $this
     */
    public function setChartOfAccountTypeId($chartOfAccountTypeId) {
        $this->chartOfAccountTypeId = $chartOfAccountTypeId;
        return $this;
    }

    /**
     * Return Country Description
     * @return string
     */
    public function getCountryDescription() {
        return $this->countryDescription;
    }

    /**
     * Set Country Description
     * @param string $countryDescription
     * @return $this
     */
    public function setCountryDescription($countryDescription) {
        $this->countryDescription = $countryDescription;
        return $this;
    }

    /**
     * Return Country
     * @return int
     */
    public function getCountryId() {
        return $this->countryId;
    }

    /**
     * set Country Primary Key
     * @param int $countryId
     * @return $this
     */
    public function setCountryId($countryId) {
        $this->countryId = $countryId;
        return $this;
    }

    /**
     * Return Financial Period
     * @return int
     */
    public function getFinancePeriodRangePeriod() {
        return $this->financePeriodRangePeriod;
    }

    /**
     * Set Finance Period
     * @param int $financePeriodRangePeriod
     * @return $this
     */
    public function setFinancePeriodRangePeriod($financePeriodRangePeriod) {
        $this->financePeriodRangePeriod = $financePeriodRangePeriod;
        return $this;
    }

    /**
     * Return Finance Period Range Primary Key
     * @return int
     */
    public function getFinancePeriodRangeId() {
        return $this->financePeriodRangeId;
    }

    /**
     * Return Financial Period Range Primary Key
     * @param int $financePeriodRangeId
     * @return $this
     */
    public function setFinancePeriodRangeId($financePeriodRangeId) {
        $this->financePeriodRangeId = $financePeriodRangeId;
        return $this;
    }

    /**
     * Return Finance Year
     * @return int
     */
    public function getFinanceYearId() {
        return $this->financeYearId;
    }

    /**
     * Set Finance Year Primary Key
     * @param int $financeYearId
     * @return $this|ConfigClass
     */
    public function setFinanceYearId($financeYearId) {
        $this->financeYearId = $financeYearId;
        return $this;
    }

    /**
     * Return Finance Year
     * @return int
     */
    public function getFinanceYearYear() {
        return $this->financeYearYear;
    }

    /**
     * Set Financial year
     * @param int $financeYearYear
     * @return $this
     */
    public function setFinanceYearYear($financeYearYear) {
        $this->financeYearYear = $financeYearYear;
        return $this;
    }

    /**
     * Return Transaction Code
     * @return string
     */
    public function getTransactionTypeCode() {
        return $this->transactionTypeCode;
    }

    /**
     * Set Transaction Code
     * @param string $transactionTypeCode
     * @return $this
     */
    public function setTransactionTypeCode($transactionTypeCode) {
        $this->transactionTypeCode = $transactionTypeCode;
        return $this;
    }

    /**
     * Set Transaction Type Description
     * @return string
     */
    public function getTransactionTypeDescription() {
        return $this->transactionTypeDescription;
    }

    /**
     * Set Transaction Type Description
     * @param string $transactionTypeDescription
     * @return $this
     */
    public function setTransactionTypeDescription($transactionTypeDescription) {
        $this->transactionTypeDescription = $transactionTypeDescription;
        return $this;
    }

    /**
     * Return Transaction Type
     * @return int
     */
    public function getTransactionTypeId() {
        return $this->transactionTypeId;
    }

    /**
     * Set Transaction Type
     * @param int $transactionTypeId
     * @return $this
     */
    public function setTransactionTypeId($transactionTypeId) {
        $this->transactionTypeId = $transactionTypeId;
        return $this;
    }

    /**
     * Return Total debit,credit and trial balance
     * @param int $purchaseInvoiceDebitNoteId
     * @return array
     */
    public function getTotalPurchaseInvoiceDebitNoteDetail($purchaseInvoiceDebitNoteId) {
        header('Content-Type:application/json; charset=utf-8');
        $totalDebit = $this->getTotalPurchaseInvoiceDebitNoteAmount($purchaseInvoiceDebitNoteId, 1);
        $totalCredit = $this->getTotalPurchaseInvoiceDebitNoteAmount($purchaseInvoiceDebitNoteId, 2);
        $trialBalance = $this->getCheckTrialBalance($purchaseInvoiceDebitNoteId);
        return array(
            "success" => true,
            "totalDebit" => $totalDebit,
            "totalCredit" => $totalCredit,
            "trialBalance" => $trialBalance
        );
    }

    /**
     * Return Trial Balance Is correct or not before posting to journal . SUM ASSET  account - LIABILITY accounts + INCOME - Expenses + Return Earning Accounts
     * @param int $purchaseInvoiceDebitNoteId
     * @return string $trialBalance
     */
    private function getCheckTrialBalance($purchaseInvoiceDebitNoteId) {
        $trialBalance = 0;
        $sql = null;

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT (
                (
                    SELECT      SUM(`purchaseInvoiceDebitNoteDetailAmount`)
                    FROM        `purchaseinvoicedetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `purchaseinvoicedetail`.`companyId`                         	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `purchaseinvoicedetail`.`paymentVoucherId`                         	IN   (" . $purchaseInvoiceDebitNoteId . ")
                    AND         `purchaseinvoicedetail`.`isActive`                          	=   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode`	=   '" . self::ASSET . "'

                    GROUP BY    `purchaseinvoicedetail`.`purchaseInvoiceDebitNoteDetailAmount`
                )
                -
                 (
                    SELECT      SUM(`purchaseInvoiceDebitNoteDetailAmount`)
                    FROM        `purchaseinvoicedetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `purchaseinvoicedetail`.`companyId`                        	 	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `purchaseinvoicedetail`.`paymentVoucherId`                         	IN   (" . $purchaseInvoiceDebitNoteId . ")
                    AND         `purchaseinvoicedetail`.`isActive`                          	=   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode`	=	'" . self::LIABILITY . "'
                    GROUP BY    `purchaseinvoicedetail`.`purchaseInvoiceDebitNoteDetailAmount`
                )
                 +
                 (
                    SELECT      SUM(`purchaseInvoiceDebitNoteDetailAmount`)
                    FROM        `purchaseinvoicedetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `purchaseinvoicedetail`.`companyId`                         	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `purchaseinvoicedetail`.`paymentVoucherId`                         	IN  (" . $purchaseInvoiceDebitNoteId . ")
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` 	=   '" . self::EQUITY . "'
                    AND         `purchaseinvoicedetail`.`isActive`                          	=   1
                    GROUP BY    `purchaseinvoicedetail`.`purchaseInvoiceDebitNoteDetailAmount`
                ) +
                 (
                    SELECT      SUM(`purchaseInvoiceDebitNoteDetailAmount`)
                    FROM        `paymentvoucher`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `purchaseinvoicedetail`.`companyId`                         	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `purchaseinvoicedetail`.`paymentVoucherId`                         	IN   (" . $purchaseInvoiceDebitNoteId . ")
                    AND         `purchaseinvoicedetail`.`isActive`                          	=   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` 	=   '" . self::INCOME . "'
                    GROUP BY    `purchaseinvoicedetail`.`purchaseInvoiceDebitNoteDetailAmount`
                )
                 -
                 (
                    SELECT      SUM(`purchaseInvoiceDebitNoteDetailAmount`)
                    FROM        `purchaseinvoicedetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `purchaseinvoicedetail`.`companyId`                         	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `purchaseinvoicedetail`.`paymentVoucherId`                         	IN   (" . $purchaseInvoiceDebitNoteId . ")
                    AND         `purchaseinvoicedetail`.`isActive`                          	=   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode`	=	'" . self::EXPENSES . "'
                    GROUP BY    `purchaseinvoicedetail`.`purchaseInvoiceDebitNoteDetailAmount`
                )
            ) as `total`

            )";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT (
                (
                    SELECT      SUM([purchaseInvoiceDebitNoteDetailAmount])
                    FROM        [purchaseInvoiceDetail]

                    JOIN        [chartOfAccount]
                    ON          [purchaseInvoiceDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [purchaseInvoiceDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [purchaseInvoiceDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [purchaseInvoiceDetail].[companyId]                         =   '" . $this->getCompanyId(
                        ) . "'
                    AND         [purchaseInvoiceDetail].[paymentVoucherId]                         IN  (" . $purchaseInvoiceDebitNoteId . ")
                    AND         [purchaseInvoiceDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::ASSET . "'
                    GROUP BY    [purchaseInvoiceDetail].[purchaseInvoiceDebitNoteDetailAmount]
                )
                -
                 (
                    SELECT      SUM([purchaseInvoiceDebitNoteDetailAmount])
                    FROM        [purchaseInvoiceDetail]

                    JOIN        [chartOfAccount]
                    ON          [purchaseInvoiceDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [purchaseInvoiceDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [purchaseInvoiceDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [purchaseInvoiceDetail].[companyId]                         =   '" . $this->getCompanyId(
                        ) . "'
                    AND         [purchaseInvoiceDetail].[paymentVoucherId]                         IN   (" . $purchaseInvoiceDebitNoteId . ")
                    AND         [purchaseInvoiceDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::LIABILITY . "'
                    GROUP BY    [purchaseInvoiceDetail].[purchaseInvoiceDebitNoteDetailAmount]
                )
                 +
                 (
                    SELECT      SUM([purchaseInvoiceDebitNoteDetailAmount])
                    FROM        [purchaseInvoiceDetail]

                    JOIN        [chartOfAccount]
                    ON          [purchaseInvoiceDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [purchaseInvoiceDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [purchaseInvoiceDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [purchaseInvoiceDetail].[companyId]                         =   '" . $this->getCompanyId(
                        ) . "'
                    AND         [purchaseInvoiceDetail].[paymentVoucherId]                         IN   (" . $purchaseInvoiceDebitNoteId . ")
                    AND         [purchaseInvoiceDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::EQUITY . "'
                    GROUP BY    [purchaseInvoiceDetail].[purchaseInvoiceDebitNoteDetailAmount]
                ) +
                 (
                    SELECT      SUM([purchaseInvoiceDebitNoteDetailAmount])
                    FROM        [purchaseInvoiceDetail]

                    JOIN        [chartOfAccount]
                    ON          [purchaseInvoiceDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [purchaseInvoiceDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [purchaseInvoiceDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [purchaseInvoiceDetail].[companyId]                         =   '" . $this->getCompanyId(
                        ) . "'
                    AND         [purchaseInvoiceDetail].[paymentVoucherId]                         IN   (" . $purchaseInvoiceDebitNoteId . ")
                    AND         [purchaseInvoiceDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::INCOME . "'
                    GROUP BY    [purchaseInvoiceDetail].[purchaseInvoiceDebitNoteDetailAmount]
                )
                 -
                 (
                    SELECT      SUM([purchaseInvoiceDebitNoteDetailAmount])
                    FROM        [purchaseInvoiceDetail]

                    JOIN        [chartOfAccount]
                    ON          [purchaseInvoiceDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [purchaseInvoiceDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [purchaseInvoiceDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [purchaseInvoiceDetail].[companyId]                         =   '" . $this->getCompanyId(
                        ) . "'
                    AND         [purchaseInvoiceDetail].[paymentVoucherId]                         IN   (" . $purchaseInvoiceDebitNoteId . ")
                    AND         [purchaseInvoiceDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] =   '" . self::EXPENSES . "'
                    GROUP BY    [purchaseInvoiceDetail].[purchaseInvoiceDebitNoteDetailAmount]
                )
            ) as [trialBalance]

            )";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT (
                (
                    SELECT      SUM(PURCHASEINVOICEDEBITNOTEDETAILAMOUNT)
                    FROM        PURCHASEINVOICEDEBITNOTEDETAIL
                    JOIN        CHARTOFACCOUNT
                    ON          PURCHASEINVOICEDEBITNOTEDETAIL.COMPANYID                     	=   CHARTOFACCOUNT.COMPANYID
                    AND         PURCHASEINVOICEDEBITNOTEDETAIL.CHARTOFACCOUNTID             	=   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          PURCHASEINVOICEDEBITNOTEDETAIL.COMPANYID                    	=   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         	=   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       PURCHASEINVOICEDEBITNOTEDETAIL.COMPANYID                    	=   '" . $this->getCompanyId() . "'
                    AND         PURCHASEINVOICEDEBITNOTEDETAIL.JOURNALID                     	IN   (" . $purchaseInvoiceDebitNoteId . ")
                    AND         PURCHASEINVOICEDEBITNOTEDETAIL.ISACTIVE                      	=   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE 	=   '" . self::INCOME . "'
                    GROUP BY    PURCHASEINVOICEDEBITNOTEDETAIL.PURCHASEINVOICEDEBITNOTEDETAILAMOUNT
                )
                -
                 (
                    SELECT      SUM(PURCHASEINVOICEDEBITNOTEDETAILAMOUNT)
                    FROM        PURCHASEINVOICEDEBITNOTEDETAIL

                    JOIN        CHARTOFACCOUNT
                    ON          PURCHASEINVOICEDEBITNOTEDETAIL.COMPANYID                     	=   CHARTOFACCOUNT.COMPANYID
                    AND         PURCHASEINVOICEDEBITNOTEDETAIL.CHARTOFACCOUNTID          		=   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          PURCHASEINVOICEDEBITNOTEDETAIL.COMPANYID                      =   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         	=	CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       PURCHASEINVOICEDEBITNOTEDETAIL.COMPANYID                     	=   '" . $this->getCompanyId() . "'
                    AND         PURCHASEINVOICEDEBITNOTEDETAIL.JOURNALID                     	IN   (" . $purchaseInvoiceDebitNoteId . ")
                    AND         PURCHASEINVOICEDEBITNOTEDETAIL.ISACTIVE                       =   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE	=	'" . self::LIABILITY . "'
                    GROUP BY    PURCHASEINVOICEDEBITNOTEDETAIL.PURCHASEINVOICEDEBITNOTEDETAILAMOUNT
                )
                 +
                 (
                    SELECT      SUM(PURCHASEINVOICEDEBITNOTEDETAILAMOUNT)
                    FROM        PURCHASEINVOICEDEBITNOTEDETAIL
                    JOIN        CHARTOFACCOUNT
                    ON          PURCHASEINVOICEDEBITNOTEDETAIL.COMPANYID                     	=   CHARTOFACCOUNT.COMPANYID
                    AND         PURCHASEINVOICEDEBITNOTEDETAIL.CHARTOFACCOUNTID              	=   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          PURCHASEINVOICEDEBITNOTEDETAIL.COMPANYID                    	=   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         	=   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTID

                    WHERE       PURCHASEINVOICEDEBITNOTEDETAIL.COMPANYID                    	=   '" . $this->getCompanyId() . "'
                    AND         PURCHASEINVOICEDEBITNOTEDETAIL.JOURNALID                    	IN   (" . $purchaseInvoiceDebitNoteId . ")
                    AND         PURCHASEINVOICEDEBITNOTEDETAIL.ISACTIVE                      	=   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE 	=   '" . self::EQUITY . "'
                    GROUP BY    PURCHASEINVOICEDEBITNOTEDETAIL.PURCHASEINVOICEDEBITNOTEDETAILAMOUNT
                ) +
                 (
                    SELECT      SUM(purchaseInvoiceDebitNoteDetailAmount)
                    FROM        PAYMENTVOUCHER

                    JOIN        CHARTOFACCOUNT
                    ON          PURCHASEINVOICEDEBITNOTEDETAIL.COMPANYID 						= 	CHARTOFACCOUNT.COMPANYID
                    AND         PURCHASEINVOICEDEBITNOTEDETAIL.CHARTOFACCOUNTID 				= 	CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          PURCHASEINVOICEDEBITNOTEDETAIL.COMPANYID                     	=   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         	=   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       PURCHASEINVOICEDEBITNOTEDETAIL.COMPANYID                    	=   '" . $this->getCompanyId() . "'
                    AND         PURCHASEINVOICEDEBITNOTEDETAIL.JOURNALID                    	IN   (" . $purchaseInvoiceDebitNoteId . ")
                    AND         PURCHASEINVOICEDEBITNOTEDETAIL.ISACTIVE                      	=   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE	=   '" . self::INCOME . "'
                    GROUP BY    PURCHASEINVOICEDEBITNOTEDETAIL.PURCHASEINVOICEDEBITNOTEDETAILAMOUNT
                )
                 -
                 (
                    SELECT      SUM(PURCHASEINVOICEDEBITNOTEDETAILAMOUNT)
                    FROM        PURCHASEINVOICEDEBITNOTEDETAIL

                    JOIN        CHARTOFACCOUNT
                    ON          PURCHASEINVOICEDEBITNOTEDETAIL.COMPANYID                     	=   CHARTOFACCOUNT.COMPANYID
                    AND         PURCHASEINVOICEDEBITNOTEDETAIL.CHARTOFACCOUNTID             	=   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          PURCHASEINVOICEDEBITNOTEDETAIL.COMPANYID                    	=   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID        		=   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       PURCHASEINVOICEDEBITNOTEDETAIL.COMPANYID                      =   '" . $this->getCompanyId() . "'
                    AND         PURCHASEINVOICEDEBITNOTEDETAIL.JOURNALID                    	IN   (" . $purchaseInvoiceDebitNoteId . ")
                    AND         PURCHASEINVOICEDEBITNOTEDETAIL.ISACTIVE                     	=   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE	=	'" . self::EXPENSES . "'
                    GROUP BY    PURCHASEINVOICEDEBITNOTEDETAIL.PAYMENTVOUCHERDETAILAMOUNT
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
      /* Create
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