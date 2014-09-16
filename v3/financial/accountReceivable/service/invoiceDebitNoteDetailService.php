<?php

namespace Core\Financial\AccountReceivable\InvoiceDebitNoteDetail\Service;

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
 * Class InvoiceDebitNoteDetailService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountReceivable\InvoiceDebitNoteDetail\Service
 * @subpackage AccountReceivable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class InvoiceDebitNoteDetailService extends ConfigClass {

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
     * Return Invoice
     * @param  null|int $businessPartnerId Business Partner 
     * @return array|string
     */
    public function getInvoice($businessPartnerId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      `invoiceId`,
                     `invoiceDescription`,
                     `invoiceProjectTitle`
            FROM        `invoice`
            JOIN        `invoiceProject`
            USING       (`companyId`,`invoiceProjectId`)
            WHERE       `invoice`.`isActive`  =   1
            AND         `invoice`.`companyId` =   '" . $this->getCompanyId() . "'";
            if ($businessPartnerId) {
                $sql.=" AND `invoice`.`businessPartnerCategoryId`='" . $businessPartnerId . "'";
            }
            $sql.="

            ORDER BY    `invoice`.`isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      [invoice].[invoiceId],
                        [invoice].[invoiceDescription],
                        [invoiceProject].[invoiceProjectTitle]
            FROM        [invoice]
            WHERE       [invoice].[isActive]  =   1
            AND         [invoice].[companyId] =   '" . $this->getCompanyId() . "'";
            if ($businessPartnerId) {
                $sql.=" AND [invoice].[businessPartnerId]='" . $businessPartnerId . "'";
            }
            $sql.="

            ORDER BY    [invoice].[isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      INVOICEID AS \"invoiceId\",
                        INVOICEDESCRIPTION AS \"invoiceDescription\",
                        INVOICEPROJECTTITLE AS  \"invoiceProjectTitle\"
            FROM        INVOICE
            WHERE       ISACTIVE    =   1
            AND         COMPANYID   =   '" . $this->getCompanyId() . "'";
            if ($businessPartnerId) {
                $sql.=" AND INVOICE.BUSINESSPARTNERCATEGORYID='" . $businessPartnerId . "'";
            }
            $sql.="
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
            $d = 0;
            $invoiceProjectTitle = null;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($d != 0) {
                    if ($invoiceProjectTitle != $row['invoiceProjectTitle']) {
                        $str .= "</optgroup><optgroup label=\"" . $row['invoiceProjectTitle'] . "\">";
                    }
                } else {
                    $str .= "<optgroup label=\"" . $row['invoiceProjectTitle'] . "\">";
                }
                $invoiceProjectTitle = $row['invoiceProjectTitle'];

                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['invoiceId'] . "'>" . $d . ". " . $row['invoiceDescription'] . "</option>";
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
        } else if ($this->getServiceOutput() == 'html') {
            return $items;
        }
        return $items;
    }

    /**
     * Return Invoice Default Value
     * @return int
     */
    public function getInvoiceDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $invoiceId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `invoiceId`
         FROM        	`invoice`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [invoiceId],
         FROM        [invoice]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      INVOICEID AS \"invoiceId\",
         FROM        INVOICE
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
            $invoiceId = $row['invoiceId'];
        }
        return $invoiceId;
    }

    /**
     * Return InvoiceDebitNote
     * @return array|string
     */
    public function getInvoiceDebitNote() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `invoiceDebitNoteId`,
                     `invoiceDebitNoteDescription`
         FROM        `invoicedebitnote`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [invoiceDebitNoteId],
                     [invoiceDebitNoteDescription]
         FROM        [invoiceDebitNote]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      INVOICEDEBITNOTEID AS \"invoiceDebitNoteId\",
                     INVOICEDEBITNOTEDESCRIPTION AS \"invoiceDebitNoteDescription\"
         FROM        INVOICEDEBITNOTE
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
                    $str.="<option value='" . $row['invoiceDebitNoteId'] . "'>" . $d . ". " . $row['invoiceDebitNoteDescription'] . "</option>";
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
        return $items;
    }

    /**
     * Return InvoiceDebitNote Default Value
     * @return int
     */
    public function getInvoiceDebitNoteDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $invoiceDebitNoteId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `invoiceDebitNoteId`
         FROM        	`invoicedebitnote`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [invoiceDebitNoteId],
         FROM        [invoiceDebitNote]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      INVOICEDEBITNOTEID AS \"invoiceDebitNoteId\",
         FROM        INVOICEDEBITNOTE
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
            $invoiceDebitNoteId = $row['invoiceDebitNoteId'];
        }
        return $invoiceDebitNoteId;
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
     * @param null|int $businessPartnerCategoryId Business Partner Category Primary Key
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
					`businesspartner`.`businessPartnerRegistrationNumber`,
					 `businesspartner`.`businessPartnerCompany`,
					 `businesspartnercategory`.`businessPartnerCategoryDescription`
			FROM        `businesspartner`
			JOIN		 `businesspartnercategory`
			USING		 (`companyId`,`businessPartnerCategoryId`)
			WHERE       `businesspartner`.`isActive`  =   1
			AND 		`isDebtor`=1
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
			AND 		[isDebtor]=1
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
			WHERE       BUSINESSPARTNER.COMPANYID ='" . $this->getCompanyId() . "'
			AND 		ISDEBTOR=1
			AND        BUSINESSPARTNER.ISACTIVE    						=   1";
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
     * Return Total Invoice Debit Note Amount
     * @param int $invoiceDebitNoteId Main Table
     * @param string $type 1->debit,2->credit
     * @return double $total
     */
    public function getTotalInvoiceDebitNoteAmount($invoiceDebitNoteId, $type) {
        $total = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      SUM(`invoiceCreditNoteDetailAmount`) AS `total`
            FROM        `invoicedebitnotedetail`

            WHERE       `invoicedebitnotedetail`.`companyId`                         =   '" . $this->getCompanyId() . "'
            AND         `invoicedebitnotedetail`.`invoiceDebitNoteId`                 IN   (" . $invoiceDebitNoteId . ")
            AND         `invoicedebitnotedetail`.`isActive`                          =   1";
            if ($type == 1) {
                $sql .= "  AND `invoiceCreditNoteDetailAmount` >0 ";
            } else {
                $sql .= "  AND `invoiceCreditNoteDetailAmount` < 0 ";
            }
            // $sql .= "
            //  GROUP BY    `invoicedebitnotedetail`.`invoiceCreditNoteDetailAmount`
            //  ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql .= "
            SELECT      SUM([invoiceCreditNoteDetailAmount]) AS [total]
            FROM        [invoiceDebitNoteDetail]

            WHERE       [invoiceDebitNoteDetail].[companyId]                         =   '" . $this->getCompanyId() . "'
            AND         [invoiceDebitNoteDetail].[purchaseInvoiceId]                         IN   (" . $invoiceDebitNoteId . ")
            AND         [invoiceDebitNoteDetail].[isActive]                          =   1";
            if ($type == 1) {
                $sql .= "  AND [invoiceCreditNoteDetailAmount] >0 ";
            } else {
                $sql .= "  AND [invoiceCreditNoteDetailAmount] < 0 ";
            }
            $sql .= "
            GROUP BY    [invoiceDebitNoteDetail].[INVOICEDEBITNOTEDETAILAmount]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      SUM(INVOICEDEBITNOTEDETAILAMOUNT) AS \"total\"
            FROM        INVOICEDEBITNOTEDETAIL

            WHERE       INVOICEDEBITNOTEDETAIL.COMPANYID                         =   '" . $this->getCompanyId() . "'
            AND         INVOICEDEBITNOTEDETAIL.invoiceDebitNoteId                         IN   (" . $invoiceDebitNoteId . ")
            AND         INVOICEDEBITNOTEDETAIL.ISACTIVE                          =   1";
            if ($type == 1) {
                $sql .= "  AND INVOICEDEBITNOTEDETAILAMOUNT >0 ";
            } else {
                $sql .= "  AND INVOICEDEBITNOTEDETAILAMOUNT < 0 ";
            }
            $sql .= "
            GROUP BY    INVOICEDEBITNOTEDETAIL.INVOICEDEBITNOTEDETAILAMOUNT
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
     * @param int $invoiceDebitNoteId Debit Note
     * @return array
     */
    public function getTotalInvoiceDebitNoteDetail($invoiceDebitNoteId) {
        header('Content-Type:application/json; charset=utf-8');
        $totalDebit = $this->getTotalInvoiceDebitNoteAmount($invoiceDebitNoteId, 1);
        $totalCredit = $this->getTotalInvoiceDebitNoteAmount($invoiceDebitNoteId, 2);
        $trialBalance = $this->getCheckTrialBalance($invoiceDebitNoteId);
        return array(
            "success" => true,
            "totalDebit" => $totalDebit,
            "totalCredit" => $totalCredit,
            "trialBalance" => $trialBalance
        );
    }

    /**
     * Return Trial Balance Is correct or not before posting to journal . SUM ASSET  account - LIABILITY accounts + INCOME - Expenses + Return Earning Accounts
     * @param int $invoiceDebitNoteId
     * @return string $trialBalance
     */
    private function getCheckTrialBalance($invoiceDebitNoteId) {
        $trialBalance = 0;
        $sql = null;

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT (
                (
                    SELECT      SUM(`invoiceDebitNoteDetailAmount`)
                    FROM        `invoicedebitnotedetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `invoicedebitnotedetail`.`companyId`                         	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `invoicedebitnotedetail`.`invoiceDebitNoteId`                         	IN   (" . $invoiceDebitNoteId . ")
                    AND         `invoicedebitnotedetail`.`isActive`                          	=   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` IN ('" . self::SAGA_ASSET . "','" . self::ASSET . "')

                    GROUP BY    `invoicedebitnotedetail`.`invoiceDebitNoteDetailAmount`
                )
                -
                 (
                    SELECT      SUM(`invoiceDebitNoteDetailAmount`)
                    FROM        `invoicedebitnotedetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `invoicedebitnotedetail`.`companyId`                        	 	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `invoicedebitnotedetail`.`invoiceDebitNoteId`                         	IN   (" . $invoiceDebitNoteId . ")
                    AND         `invoicedebitnotedetail`.`isActive`                          	=   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode`	IN	('" . self::SAGA_LIABILITY . "','" . self::LIABILITY . "')
                    GROUP BY    `invoicedebitnotedetail`.`invoiceDebitNoteDetailAmount`
                )
                 +
                 (
                    SELECT      SUM(`invoiceDebitNoteDetailAmount`)
                    FROM        `invoicedebitnotedetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `invoicedebitnotedetail`.`companyId`                         	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `invoicedebitnotedetail`.`invoiceDebitNoteId`                         	IN  (" . $invoiceDebitNoteId . ")
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` 	IN ('" . self::SAGA_EQUITY . "','" . self::EQUITY . "')
                    AND         `invoicedebitnotedetail`.`isActive`                          	=   1
                    GROUP BY    `invoicedebitnotedetail`.`invoiceDebitNoteDetailAmount`
                ) +
                 (
                    SELECT      SUM(`invoiceDebitNoteDetailAmount`)
                    FROM        `invoicedebitnotedetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `invoicedebitnotedetail`.`companyId`                         	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `invoicedebitnotedetail`.`invoiceDebitNoteId`                         	IN   (" . $invoiceDebitNoteId . ")
                    AND         `invoicedebitnotedetail`.`isActive`                          	=   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode` 	 IN ('" . self::SAGA_INCOME . "','" . self::INCOME . "')
                    GROUP BY    `invoicedebitnotedetail`.`invoiceDebitNoteDetailAmount`
                )
                 -
                 (
                    SELECT      SUM(`invoiceDebitNoteDetailAmount`)
                    FROM        `invoicedebitnotedetail`

                    JOIN        `chartofaccount`
                    USING       (`companyId`,`chartOfAccountId`)

                    JOIN        `chartofaccountcategory`
                    USING       (`companyId`,`chartOfAccountCategoryId`)

                    WHERE       `invoicedebitnotedetail`.`companyId`                         	=   '" . $this->getCompanyId(
                    ) . "'
                    AND         `invoicedebitnotedetail`.`invoiceDebitNoteId`                         	IN   (" . $invoiceDebitNoteId . ")
                    AND         `invoicedebitnotedetail`.`isActive`                          	=   1
                    AND         `chartofaccountcategory`.`chartOfAccountCategoryCode`	IN	('" . self::SAGA_EXPENSES . "','" . self::EXPENSES . "')
                    GROUP BY    `invoicedebitnotedetail`.`invoiceDebitNoteDetailAmount`
                )
            ) as `total`

            )";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT (
                (
                    SELECT      SUM([invoiceDebitNoteDetailAmount])
                    FROM        [invoiceDebitNoteDetail]

                    JOIN        [chartOfAccount]
                    ON          [invoiceDebitNoteDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [invoiceDebitNoteDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [invoiceDebitNoteDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [invoiceDebitNoteDetail].[companyId]                         =   '" . $this->getCompanyId(
                    ) . "'
                    AND         [invoiceDebitNoteDetail].[invoiceDebitNoteId]                         IN  (" . $invoiceDebitNoteId . ")
                    AND         [invoiceDebitNoteDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] IN ('" . self::SAGA_ASSET . "','" . self::ASSET . "')
                    GROUP BY    [invoiceDebitNoteDetail].[invoiceDebitNoteDetailAmount]
                )
                -
                 (
                    SELECT      SUM([invoiceDebitNoteDetailAmount])
                    FROM        [invoiceDebitNoteDetail]

                    JOIN        [chartOfAccount]
                    ON          [invoiceDebitNoteDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [invoiceDebitNoteDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [invoiceDebitNoteDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [invoiceDebitNoteDetail].[companyId]                         =   '" . $this->getCompanyId(
                    ) . "'
                    AND         [invoiceDebitNoteDetail].[invoiceDebitNoteId]                         IN   (" . $invoiceDebitNoteId . ")
                    AND         [invoiceDebitNoteDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] IN	('" . self::SAGA_LIABILITY . "','" . self::LIABILITY . "')
                    GROUP BY    [invoiceDebitNoteDetail].[invoiceDebitNoteDetailAmount]
                )
                 +
                 (
                    SELECT      SUM([invoiceDebitNoteDetailAmount])
                    FROM        [invoiceDebitNoteDetail]

                    JOIN        [chartOfAccount]
                    ON          [invoiceDebitNoteDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [invoiceDebitNoteDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [invoiceDebitNoteDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [invoiceDebitNoteDetail].[companyId]                         =   '" . $this->getCompanyId(
                    ) . "'
                    AND         [invoiceDebitNoteDetail].[invoiceDebitNoteId]                         IN   (" . $invoiceDebitNoteId . ")
                    AND         [invoiceDebitNoteDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] IN ('" . self::SAGA_EQUITY . "','" . self::EQUITY . "')
                    GROUP BY    [invoiceDebitNoteDetail].[invoiceDebitNoteDetailAmount]
                ) +
                 (
                    SELECT      SUM([invoiceDebitNoteDetailAmount])
                    FROM        [invoiceDebitNoteDetail]

                    JOIN        [chartOfAccount]
                    ON          [invoiceDebitNoteDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [invoiceDebitNoteDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [invoiceDebitNoteDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [invoiceDebitNoteDetail].[companyId]                         =   '" . $this->getCompanyId(
                    ) . "'
                    AND         [invoiceDebitNoteDetail].[invoiceDebitNoteId]                         IN   (" . $invoiceDebitNoteId . ")
                    AND         [invoiceDebitNoteDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode]  IN ('" . self::SAGA_INCOME . "','" . self::INCOME . "')
                    GROUP BY    [invoiceDebitNoteDetail].[invoiceDebitNoteDetailAmount]
                )
                 -
                 (
                    SELECT      SUM([invoiceDebitNoteDetailAmount])
                    FROM        [invoiceDebitNoteDetail]

                    JOIN        [chartOfAccount]
                    ON          [invoiceDebitNoteDetail].[companyId]                         =   [chartOfAccount].[companyId]
                    AND         [invoiceDebitNoteDetail].[chartOfAccountId]                  =   [chartOfAccount].[chartOfAccountId]

                    JOIN        [chartOfAccountCategory]
                    ON          [invoiceDebitNoteDetail].[companyId]                         =   [chartOfAccountCategory][companyId]
                    AND         [chartOfAccount].[chartOfAccountCategory]           =   [chartOfAccountCategory][chartOfAccountId]

                    WHERE       [invoiceDebitNoteDetail].[companyId]                         =   '" . $this->getCompanyId(
                    ) . "'
                    AND         [invoiceDebitNoteDetail].[invoiceDebitNoteId]                         IN   (" . $invoiceDebitNoteId . ")
                    AND         [invoiceDebitNoteDetail].[isActive]                          =   1
                    AND         [chartOfAccountCategory][chartOfAccountCategoryCode] IN	('" . self::SAGA_EXPENSES . "','" . self::EXPENSES . "')
                    GROUP BY    [invoiceDebitNoteDetail].[invoiceDebitNoteDetailAmount]
                )
            ) as [trialBalance]

            )";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT (
                (
                    SELECT       SUM(INVOICEDEBITNOTEDETAILAMOUNT)
                    FROM        INVOICEDEBITNOTEDETAIL
                    JOIN        CHARTOFACCOUNT
                    ON          INVOICEDEBITNOTEDETAIL.COMPANYID                     	=   CHARTOFACCOUNT.COMPANYID
                    AND         INVOICEDEBITNOTEDETAIL.CHARTOFACCOUNTID             	=   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          INVOICEDEBITNOTEDETAIL.COMPANYID                    	=   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         	=   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       INVOICEDEBITNOTEDETAIL.COMPANYID                    	=   '" . $this->getCompanyId() . "'
                    AND         INVOICEDEBITNOTEDETAIL.INVOICEID                     	IN   (" . $invoiceDebitNoteId . ")
                    AND         INVOICEDEBITNOTEDETAIL.ISACTIVE                      	=   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE 	IN ('" . self::SAGA_ASSET . "','" . self::ASSET . "')
                    GROUP BY    INVOICEDEBITNOTEDETAIL.INVOICEDEBITNOTEDETAILAMOUNT
                )
                -
                 (
                    SELECT      SUM(INVOICEDEBITNOTEDETAILAMOUNT)
                    FROM        INVOICEDEBITNOTEDETAIL

                    JOIN        CHARTOFACCOUNT
                    ON          INVOICEDEBITNOTEDETAIL.COMPANYID                     	=   CHARTOFACCOUNT.COMPANYID
                    AND         INVOICEDEBITNOTEDETAIL.CHARTOFACCOUNTID          		=   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          INVOICEDEBITNOTEDETAIL.COMPANYID                      =   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         	=	CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       INVOICEDEBITNOTEDETAIL.COMPANYID                     	=   '" . $this->getCompanyId() . "'
                    AND         INVOICEDEBITNOTEDETAIL.INVOICEID                     	IN   (" . $invoiceDebitNoteId . ")
                    AND         INVOICEDEBITNOTEDETAIL.ISACTIVE                       =   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE	IN	('" . self::SAGA_LIABILITY . "','" . self::LIABILITY . "')
                    GROUP BY    INVOICEDEBITNOTEDETAIL.INVOICEDEBITNOTEDETAILAMOUNT
                )
                 +
                 (
                    SELECT      SUM(INVOICEDEBITNOTEDETAILAMOUNT)
                    FROM        INVOICEDEBITNOTEDETAIL
                    JOIN        CHARTOFACCOUNT
                    ON          INVOICEDEBITNOTEDETAIL.COMPANYID                     	=   CHARTOFACCOUNT.COMPANYID
                    AND         INVOICEDEBITNOTEDETAIL.CHARTOFACCOUNTID              	=   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          INVOICEDEBITNOTEDETAIL.COMPANYID                    	=   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         	=   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTID

                    WHERE       INVOICEDEBITNOTEDETAIL.COMPANYID                    	=   '" . $this->getCompanyId() . "'
                    AND         INVOICEDEBITNOTEDETAIL.INVOICEID                    	IN   (" . $invoiceDebitNoteId . ")
                    AND         INVOICEDEBITNOTEDETAIL.ISACTIVE                      	=   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE 	IN ('" . self::SAGA_EQUITY . "','" . self::EQUITY . "')
                    GROUP BY    INVOICEDEBITNOTEDETAIL.INVOICEDEBITNOTEDETAILAMOUNT
                ) +
                 (
                    SELECT      SUM(INVOICEDEBITNOTEDETAILAMOUNT)
                    FROM         INVOICEDEBITNOTEDETAIL

                    JOIN        CHARTOFACCOUNT
                    ON          INVOICEDEBITNOTEDETAIL.COMPANYID 						= 	CHARTOFACCOUNT.COMPANYID
                    AND         INVOICEDEBITNOTEDETAIL.CHARTOFACCOUNTID 				= 	CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          INVOICEDEBITNOTEDETAIL.COMPANYID                     	=   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID         	=   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       INVOICEDEBITNOTEDETAIL.COMPANYID                    	=   '" . $this->getCompanyId() . "'
                    AND         INVOICEDEBITNOTEDETAIL.INVOICEID                    	IN   (" . $invoiceDebitNoteId . ")
                    AND         INVOICEDEBITNOTEDETAIL.ISACTIVE                      	=   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE  IN ('" . self::SAGA_INCOME . "','" . self::INCOME . "')
                    GROUP BY    INVOICEDEBITNOTEDETAIL.INVOICEDEBITNOTEDETAILAMOUNT
                )
                 -
                 (
                    SELECT      SUM(INVOICEDEBITNOTEDETAILAMOUNT)
                    FROM        INVOICEDEBITNOTEDETAIL

                    JOIN        CHARTOFACCOUNT
                    ON          INVOICEDEBITNOTEDETAIL.COMPANYID                     	=   CHARTOFACCOUNT.COMPANYID
                    AND         INVOICEDEBITNOTEDETAIL.CHARTOFACCOUNTID             	=   CHARTOFACCOUNT.CHARTOFACCOUNTID

                    JOIN        CHARTOFACCOUNTCATEGORY
                    ON          INVOICEDEBITNOTEDETAIL.COMPANYID                    	=   CHARTOFACCOUNTCATEGORY.COMPANYID
                    AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID        		=   CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYID

                    WHERE       INVOICEDEBITNOTEDETAIL.COMPANYID                      =   '" . $this->getCompanyId() . "'
                    AND         INVOICEDEBITNOTEDETAIL.INVOICEID                    	IN   (" . $invoiceDebitNoteId . ")
                    AND         INVOICEDEBITNOTEDETAIL.ISACTIVE                     	=   1
                    AND         CHARTOFACCOUNTCATEGORY.CHARTOFACCOUNTCATEGORYCODE IN	('" . self::SAGA_EXPENSES . "','" . self::EXPENSES . "')
                    GROUP BY    INVOICEDEBITNOTEDETAIL.INVOICEDEBITNOTEDETAILAMOUNT
                )
            ) as [trialBalance]

            )";
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