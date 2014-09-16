<?php

namespace Core\Financial\AccountReceivable\Invoice\Service;

use Core\ConfigClass;
use Core\Financial\Ledger\Service\LedgerService;

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
require_once($newFakeDocumentRoot . "v3/financial/shared/service/sharedService.php");

/**
 * Class InvoiceService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountReceivable\Invoice\Service
 * @subpackage AccountReceivable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class InvoiceService extends ConfigClass {

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
     * Cash Sales
     */
    const CASH_SALES = 'CHSL';

    /**
     * Sales Quotation
     */
    const SALES_QUOTATION = 'SLQT';

    /**
     * Sales Invoice
     */
    const SALES_INVOICE = 'SLOR';

    /**
     * Sales Other
     */
    const SALES_OTHER = 'SLOT';

    /**
     * Transfer To General Ledger
     */
    const TRANSFER_TO_GENERAL_LEDGER = 'TSGL';

    /**
     * Attachment Code
     * */
    const ATTACHMENT_CODE = 'ATFL';

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
     * Upload Invoice Attachment
     * @var int
     */
    private $sizeLimit;

    /**
     * Upload Staff Avatar Type
     * @var string
     */
    private $allowedExtensions;

    /**
     * @var string
     */
    private $uploadPath;

    /**
     * Financial Shared Service
     * @var \Core\Financial\Ledger\Service\LedgerService
     */
    public $ledgerService;

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

        // images  / microsoft office file / open office  only
        $this->allowedExtensions = array("jpg", "jpeg", "xml", "bmp", "png", "doc", "docx", "wpd", "wps", "txt", "rtf", "odw", "ods", "xls", "xlsx", "ott", "ods", "ppt", "pptx");
        // max file size in bytes
        $this->setSizeLimit((8 * 1024 * 1024));
        // set upload path
        $this->setUploadPath($this->getFakeDocumentRoot() . "v3/financial/accountReceivable/attachment/");
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
        $this->ledgerService = new LedgerService();
        $this->ledgerService->q = $this->q;
        $this->ledgerService->t = $this->t;
        $this->ledgerService->execute();
    }

    /**
     * Return Invoice Category
     * @return array|string
     */
    public function getInvoiceCategory() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() === self::MYSQL) {
            $sql = "
         SELECT      `invoiceCategoryId`,
                     `invoiceCategoryDescription`
         FROM        `invoicecategory`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() === self::MSSQL) {
            $sql = "
         SELECT      [invoiceCategoryId],
                     [invoiceCategoryDescription]
         FROM        [invoiceCategory]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() === self::ORACLE) {
            $sql = "
         SELECT      INVOICECATEGORYID AS \"invoiceCategoryId\",
                     INVOICECATEGORYDESCRIPTION AS \"invoiceCategoryDescription\"
         FROM        INVOICECATEGORY
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
                    $str .= "<option value='" . $row['invoiceCategoryId'] . "'>" . $d . ". " . $row['invoiceCategoryDescription'] . "</option>";
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
     * Return Invoice Category Default Value
     * @return int
     */
    public function getInvoiceCategoryDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $invoiceCategoryId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `invoiceCategoryId`
         FROM        	`invoicecategory`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [invoiceCategoryId],
         FROM        [invoiceCategory]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      INVOICECATEGORYID AS \"invoiceCategoryId\",
         FROM        INVOICECATEGORY
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
            $invoiceCategoryId = $row['invoiceCategoryId'];
        }
        return $invoiceCategoryId;
    }

    /**
     * Return Invoice Type
     * @return array|string
     */
    public function getInvoiceType() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `invoiceTypeId`,
                     `invoiceTypeDescription`
         FROM        `invoicetype`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [invoiceTypeId],
                     [invoiceTypeDescription]
         FROM        [invoiceType]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      INVOICETYPEID AS \"invoiceTypeId\",
                     INVOICETYPEDESCRIPTION AS \"invoiceTypeDescription\"
         FROM        INVOICETYPE
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
                    $str .= "<option value='" . $row['invoiceTypeId'] . "'>" . $d . ". " . $row['invoiceTypeDescription'] . "</option>";
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
     * Return Invoice Type Default Value
     * @return int
     */
    public function getInvoiceTypeDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $invoiceTypeId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `invoiceTypeId`
         FROM        	`invoicetype`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [invoiceTypeId],
         FROM        [invoiceType]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      INVOICETYPEID AS \"invoiceTypeId\",
         FROM        INVOICETYPE
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
            $invoiceTypeId = $row['invoiceTypeId'];
        }
        return $invoiceTypeId;
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
     * Return Business Partner Contact
     * @param null|int $businessPartnerContactId Business Partner Contact Primary Key
     * @return array|string
     */
    public function getBusinessPartnerContact($businessPartnerContactId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `businessPartnerContactId`,
                     `businessPartnerContactName`
         FROM        `businesspartnercontact`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [businessPartnerContactId],
                     [businessPartnerContactName]
         FROM        [businessPartnerContact]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      BUSINESSPARTNERCONTACTID AS \"businessPartnerContactId\",
                     BUSINESSPARTNERCONTACTNAME AS \"businessPartnerContactName\"
         FROM        BUSINESSPARTNERCONTACT
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
                if (intval($businessPartnerContactId) > 0) {
                    if ($businessPartnerContactId == $row['businessPartnerContactId']) {
                        $selected = "selected";
                    } else {
                        $selected = null;
                    }
                } else {
                    $selected = null;
                }
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['businessPartnerContactId'] . "' " . $selected . ">" . $d . ". " . $row['businessPartnerContactName'] . "</option>";
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
     * Return BusinessPartnerContact Default Value
     * @return int
     */
    public function getBusinessPartnerContactDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $businessPartnerContactId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `businessPartnerContactId`
         FROM        	`businesspartnercontact`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [businessPartnerContactId],
         FROM        [businessPartnerContact]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      BUSINESSPARTNERCONTACTID AS \"businessPartnerContactId\",
         FROM        BUSINESSPARTNERCONTACT
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
            $businessPartnerContactId = $row['businessPartnerContactId'];
        }
        return $businessPartnerContactId;
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
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [countryId],
                     [countryCurrencyCode],
                     [countryDescription]
         FROM        [country]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      COUNTRYID AS \"countryId\",
                     COUNTRYCURRENCYCODE AS \"countryCurrencyCode\",
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
                    $str .= "<option value='" . $row['countryId'] . "|" . $row['countryCurrencyCode'] . "'>" . $row['countryCurrencyCode'] . " -" . $row['countryDescription'] . "</option>";
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
     * Return State Default Value
     * @return int
     */
    public function getStateDefaultValue() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $stateId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
         SELECT      `stateId`
         FROM        	`state`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      TOP 1 [stateId],
         FROM        [state]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
         SELECT      STATEID AS \"stateId\",
         FROM        STATE  
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
            $stateId = $row['stateId'];
        }
        return $stateId;
    }

    /**
     * Return InvoiceProject
     * @return array|string
     */
    public function getInvoiceProject() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `invoiceProjectId`,
                     `invoiceProjectDescription`
         FROM        `invoiceproject`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [invoiceProjectId],
                     [invoiceProjectDescription]
         FROM        [invoiceProject]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      INVOICEPROJECTID AS \"invoiceProjectId\",
                     INVOICEPROJECTDESCRIPTION AS \"invoiceProjectDescription\"
         FROM        INVOICEPROJECT
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
                    $str .= "<option value='" . $row['invoiceProjectId'] . "'>" . $d . ". " . $row['invoiceProjectDescription'] . "</option>";
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
     * Return InvoiceProject Default Value
     * @return int
     */
    public function getInvoiceProjectDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $invoiceProjectId = null;
        if ($this->getVendor() === self::MYSQL) {
            $sql = "
         SELECT      `invoiceProjectId`
         FROM        	`invoiceproject`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() === self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [invoiceProjectId],
         FROM        [invoiceProject]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() === self::ORACLE) {
            $sql = "
         SELECT      INVOICEPROJECTID AS \"invoiceProjectId\",
         FROM        INVOICEPROJECT
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
            $invoiceProjectId = $row['invoiceProjectId'];
        }
        return $invoiceProjectId;
    }

    /**
     * Return Payment Terms
     * @return array|string
     */
    public function getPaymentTerm() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `paymentTermId`,
                     `paymentTermDueDays`, 
                     `paymentTermDescription`
         FROM        `paymentterm`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [paymentTermId],
		     [paymentTermDueDays],
                     [paymentTermDescription]
         FROM        [paymentTerm]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      PAYMENTTERMID AS \"paymentTermId\",
					 PAYMENTTERMDUEDAYS AS \"paymentTermDueDays\",
                     PAYMENTTERMDESCRIPTION AS \"paymentTermDescription\"
         FROM        PAYMENTTERM
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
                    $str .= "<option value='" . $row['paymentTermsId'] . "|" . $row['paymentTermsDueDays'] . "'>" . $d . ". " . $row['paymentTermsDescription'] . "</option>";
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
     * Return PaymentTerms Default Value
     * @return int
     */
    public function getPaymentTermsDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $paymentTermsId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `paymentTermsId`
         FROM        	`paymentterms`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [paymentTermsId],
         FROM        [paymentTerms]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      PAYMENTTERMSID AS \"paymentTermsId\",
         FROM        PAYMENTTERMS
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
            $paymentTermsId = $row['paymentTermsId'];
        }
        return $paymentTermsId;
    }

    /**
     * Return Warehouse
     * @return array|string
     */
    public function getWarehouse() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `warehouseId`,
                     `warehouseDescription`
         FROM        `warehouse`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [warehouseId],
                     [warehouseDescription]
         FROM        [warehouse]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      WAREHOUSEID AS \"warehouseId\",
                     WAREHOUSEDESCRIPTION AS \"warehouseDescription\"
         FROM        WAREHOUSE
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
                    $str .= "<option value='" . $row['warehouseId'] . "'>" . $d . ". " . $row['warehouseDescription'] . "</option>";
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
     * Return Warehouse Default Value
     * @return int
     */
    public function getWarehouseDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $warehouseId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `warehouseId`
         FROM        	`warehouse`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [warehouseId],
         FROM        [warehouse]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      WAREHOUSEID AS \"warehouseId\",
         FROM        WAREHOUSE
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
            $warehouseId = $row['warehouseId'];
        }
        return $warehouseId;
    }

    /**
     * Return InvoiceProcess
     * @return array|string
     */
    public function getInvoiceProcess() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `invoiceProcessId`,
                     `invoiceProcessDescription`
         FROM        `invoiceprocess`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [invoiceProcessId],
                     [invoiceProcessDescription]
         FROM        [invoiceProcess]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      INVOICEPROCESSID AS \"invoiceProcessId\",
                     INVOICEPROCESSDESCRIPTION AS \"invoiceProcessDescription\"
         FROM        INVOICEPROCESS
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
                    $str .= "<option value='" . $row['invoiceProcessId'] . "'>" . $d . ". " . $row['invoiceProcessDescription'] . "</option>";
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

        // fake return
        return $items;
    }

    /**
     * Return InvoiceProcess Default Value
     * @return int
     */
    public function getInvoiceProcessDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $invoiceProcessId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `invoiceProcessId`
         FROM        	`invoiceprocess`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [invoiceProcessId],
         FROM        [invoiceProcess]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      INVOICEPROCESSID AS \"invoiceProcessId\",
         FROM        INVOICEPROCESS
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
            $invoiceProcessId = $row['invoiceProcessId'];
        }
        return $invoiceProcessId;
    }

    /**
     * Return Invoice Process Id based on Company And Code
     * @param string $invoiceProcessCode
     * @return int $invoiceProcessId
     */
    public function getInvoiceProcessId($invoiceProcessCode) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $invoiceProcessId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT		`invoiceProcessId`
			FROM        `invoiceprocess`
			WHERE       `isActive`  			=   1
			AND         `companyId` 			=   '" . $this->getCompanyId() . "'
			AND		 	`invoiceProcessCode`	=	'" . $invoiceProcessCode . "'
			LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      TOP 1 [invoiceProcessId],
			FROM        [invoiceProcess]
			WHERE       [isActive]  			=   1
			AND         [companyId] 			=   '" . $this->getCompanyId() . "'
			AND		 	[invoiceProcessCode]	=	'" . $invoiceProcessCode . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT      INVOICEPROCESSID AS \"invoiceProcessId\",
			FROM        INVOICEPROCESS
			WHERE       ISACTIVE    		=   1
			AND         COMPANYID   		=   '" . $this->getCompanyId() . "'
			AND		 	INVOICEPROCESSCODE	=	'" . $invoiceProcessCode . "'
			AND 		ROWNUM	  			=	1";
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
            $invoiceProcessId = $row['invoiceProcessId'];
        }
        return $invoiceProcessId;
    }

    /**
     * Return Tax
     * @return array|string
     */
    public function getTax() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `taxId`,
                     `taxDescription`
         FROM        `tax`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [taxId],
                     [taxDescription]
         FROM        [tax]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      TAXID AS \"taxId\",
                     TAXDESCRIPTION AS \"taxDescription\"
         FROM        TAX
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
                    $str .= "<option value='" . $row['taxId'] . "'>" . $d . ". " . $row['taxDescription'] . "</option>";
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
     * Return Tax Default Value
     * @return int
     */
    public function getTaxDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $taxId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `taxId`
         FROM        	`tax`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [taxId],
         FROM        [tax]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      TAXID AS \"taxId\",
         FROM        TAX
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
            $taxId = $row['taxId'];
        }
        return $taxId;
    }

    /**
     * Return House For Sales
     * @return array|string
     */
    public function getHouseForSales() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT     *
			FROM		 `product`
			JOIN		     `itemtype`
			USING		(`companyId`,`itemTypeId`)
			WHERE		 `isSales`=1
			AND			isInventory`=1
			AND			isItem=0
			AND			`product`.`isActive`  =   0
			AND         `product`.`companyId` =   '" . $this->getCompanyId() . "';";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT     *
			FROM		 [product]
			JOIN		[itemType]
			USING		([companyId],[itemTypeId])
			WHERE		 [isSales]=1
			AND			[isInventory]=1
			AND			isItem=0
			AND			[product].[isActive]  =   0
			AND         [product].[companyId] =   '" . $this->getCompanyId() . "';";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT     *
			FROM		 PRODUCT
			JOIN		ITEMTYPE
			USING		(COMPANYID,ITEMTYPEID)
			WHERE		ISSALES=1
			AND			ISINVENTORY=1
			AND			ISITEM=0
			AND			PRODUCT.ISACTIVE  =   0
			AND          PRODUCT.COMPANYID =   '" . $this->getCompanyId() . "';";
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
                    $str .= "<option value='" . $row['productId'] . "'>" . $d . ". " . $row['productName'] . "</option>";
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
     * Return Tax
     * @return array|string
     */
    public function getHouseForRent() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT     *
			FROM		 `product`
			JOIN		`itemtype`
			USING		(`companyId`,`itemTypeId`)
			WHERE		 `isRent`=1
			AND			isFixedAsset`=1
			AND			isItem=0
			AND			`product`.`isActive`  =   0
			AND         `product`.`companyId` =   '" . $this->getCompanyId() . "';";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT     *
			FROM		 [product]
			JOIN		[itemType]
			USING		([companyId],[itemTypeId])
			WHERE		 [isRent]=1
			AND			[isFixedAsset]=1
			AND			isItem=0
			AND			[product].[isActive]  =   0
			AND         [product].[companyId] =   '" . $this->getCompanyId() . "';";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT     *
			FROM		 PRODUCT
			JOIN		ITEMTYPE
			USING		(COMPANYID,ITEMTYPEID)
			WHERE		ISRENT=1
			AND			ISFIXEDASSET=1
			AND			ISITEM=0
			AND			PRODUCT.ISACTIVE  =   0
			AND          PRODUCT.COMPANYID =   '" . $this->getCompanyId() . "';";
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
                    $str .= "<option value='" . $row['productId'] . "'>" . $d . ". " . $row['productName'] . "</option>";
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
     * Return List Invoice Status Quotation
     * @param null $businessPartnerId
     * @return mixed
     */
    public function getInvoiceQuotation($businessPartnerId = null) {
        $str = null;
        $sql = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT	`invoice`.`documentNumber`,
					`invoice`.`invoiceDescription`,
					`businessPartner`.`businessPartnerCompany`
			FROM	`invoice`
			JOIN	`invoiceprocess`
			USING	(`companyId`,`invoiceProcessId`)
			JOIN	`businessPartner`
			USING	(`companyId`,`businessPartnerId`)
			WHERE	`invoice`.`companyId`					=	'" . $this->getCompanyId() . "'
			AND		`invoiceprocess`.`invoiceProcessCode`	=	'" . self::SALES_QUOTATION . "'";
            if ($businessPartnerId) {
                $sql .= "
				AND `invoice`.`businessPartnerId`			=	'" . $businessPartnerId . "'";
            }
            $sql .= "
			ORDER BY `invoice`.`businessPartnerId`
			";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT	[invoice].[documentNumber],
					[invoice].[invoiceDescription],
					[businessPartner].[businessPartnerCompany]
			FROM	[invoice]
			JOIN	[invoiceProcess]
			ON		[invoiceProcess].[companyId] 			= 	[invoice].[companyId]
			AND		[invoiceProcess].[invoiceProcessId]		=	[invoice].[invoiceProcessId]

			JOIN	[businessPartner]
			ON		[businessPartner].[companyId] 			= 	[invoice].[companyId]
			AND		[businessPartner].[businessPartnerId]	=	[invoice].[ibusinessPartnerId]

			WHERE	[invoice].[companyId]					=	'" . $this->getCompanyId() . "'
			AND		[invoiceprocess].[invoiceProcessCode]	=	'" . self::SALES_QUOTATION . "'";
            if ($businessPartnerId) {
                $sql .= "
				AND [invoice].[businessPartnerId]			=	'" . $businessPartnerId . "'";
            }
            $sql .= "
			ORDER BY [invoice].[businessPartnerId]
			";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT	INVOICE.DOCUMENTNUMBER,
					INVOICE.DESCRIPTION,
					BUSINESSPARTNER.BUSINESPARTNERCOMPANY
			FROM	INVOICE

			JOIN	INVOICEPROCESS
			ON		INVOICEPROCESS.COMPANYID 			= 	INVOICE.COMPANYID
			AND		INVOICEPROCESS.INVOICEPROCESSID		=	INVOICE.INVOICEPROCESSID

			JOIN	BUSINESSPARTNER
			ON		BUSINESSPARTNER.COMPANYID 			= 	INVOICE.COMPANYID
			AND		BUSINESSPARTNER.BUSINESSPARTNERID	=	INVOICE.BUSINESSPARTNERID

			WHERE	INVOICE.COMPANYID					=	'" . $this->getCompanyId() . "'

			AND		INVOICEPROCESS.INVOICEPROCESSCODE	=	'" . self::SALES_QUOTATION . "'";
            if ($businessPartnerId) {
                $sql .= " AND BUSINESSPARTNERID			=	'" . $businessPartnerId . "'";
            }
            $sql .= "
			ORDER BY INVOICE.BUSINESSPARTNERID
			";
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
                    $str .= "<option value='" . $row['invoiceProcessId'] . "'>" . $d . ". " . $row['invoiceProcessDescription'] . "</option>";
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
        return $items;
    }

    /**
     * Update Invoice Quotation Number
     * @param int $invoiceId Invoice Primary Key
     * @param int $type 4->Update to Sales Order ,3->Revert To Sales Quotation
     * @return null
     */
    public function updateInvoiceQuotation($invoiceId, $type) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			UPDATE	`invoice` 
			SET 	`invoiceProcessId`	=	'" . $type . "'
			WHERE	`invoiceId`			=	'" . $invoiceId . "'
			";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			UPDATE	[invoice]
			SET 	[invoiceProcessId]	=	'" . $type . "'
			WHERE	[invoiceId]			=	'" . $invoiceId . "'
			";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			UPDATE	INVOICE
			SET 	INVOICEPROCESSID	=	'" . $type . "'
			WHERE	invoiceId			=	'" . $invoiceId . "'
			";
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
     * Return Invoice Id Based Document Number
     * @param string $documentNumber
     * @return int $invoiceId
     */
    public function getInvoiceIdFromDocumentNumber($documentNumber) {
        $sql = null;
        $invoiceId = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT 	`invoiceId` 
			FROM	`invoice`
			WHERE	`companyId`			=	'" . $this->getCompanyId() . "'
			AND		`documentNumber`	=	'" . $documentNumber . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
			SELECT 	[invoiceId]
			FROM	[invoice]
			WHERE	[companyId]			=	'" . $this->getCompanyId() . "'
			AND		[documentNumber]	=	'" . $documentNumber . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
			SELECT 	INVOICEID AS \"invoiceId\"
			FROM	INVOICE
			WHERE	COMPANYID			=	'" . $this->getCompanyId() . "'
			AND		DOCUMENTNUMBER		=	'" . $documentNumber . "'";
                }
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
            $row = $this->q->fetchArray($result);
            if (is_array($row)) {
                $invoiceId = $row['invoiceId'];
            }
        }
        return $invoiceId;
    }

    /**
     * Create New Selling Price 
     * @param int $productId Product
     * @param double $productSellingPrice Selling Price
     * @param string $startDate Start Date
     * @param string $endDate End Date
     * @return void
     * */
    public function setNewProductSellingPrice($productId, $productSellingPrice, $startDate, $endDate) {

        $countryId = $this->getCountryDefaultValue();
        $stateId = $this->getStateDefaultValue();
        $unitOfMeasurementId = $this->getUnitOfMeasurementDefaultValue();
        $productSellingPriceQuantity = 1;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `productsellingprice` 
            (
                 `companyId`,
                 `productId`,
                 `countryId`,
                 `stateId`,
                 `unitOfMeasurementId`,
                 `productSellingPriceQuantity`,
                 `productSellingPricePrice`,
                 `productSellingPriceStartDate`,
                 `productSellingPriceEndDate`,
                 `isDefault`,
                 `isNew`,
                 `isDraft`,
                 `isUpdate`,
                 `isDelete`,
                 `isActive`,
                 `isApproved`,
                 `isReview`,
                 `isPost`,
                 `executeBy`,
                 `executeTime`
       ) VALUES ( 
                 '" . $this->getCompanyId() . "',
                 '" . $productId . "',
                 '" . $countryId . "',
                 '" . $stateId . "',
                 '" . $unitOfMeasurementId . "',
                 '" . $productSellingPriceQuantity . "',
                 '" . $productSellingPrice . "',
                 '" . $startDate . "',
                 '" . $endDate . "',
                 0,
                 1,
                 0,
                 0,
                 0,
                 1,
                 0,
                 0,
                 0,
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
       );";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            INSERT INTO [productSellingPrice] 
            (
                 [productSellingPriceId],
                 [companyId],
                 [productId],
                 [countryId],
                 [stateId],
                 [unitOfMeasurementId],
                 [productSellingPriceQuantity],
                 [productSellingPricePrice],
                 [productSellingPriceStartDate],
                 [productSellingPriceEndDate],
                 [isDefault],
                 [isNew],
                 [isDraft],
                 [isUpdate],
                 [isDelete],
                 [isActive],
                 [isApproved],
                 [isReview],
                 [isPost],
                 [executeBy],
                 [executeTime]
) VALUES ( 
                     '" . $this->getCompanyId() . "',
                 '" . $productId . "',
                 '" . $countryId . "',
                 '" . $stateId . "',
                 '" . $unitOfMeasurementId . "',
                 '" . $productSellingPriceQuantity . "',
                 '" . $productSellingPrice . "',
                 '" . $startDate . "',
                 '" . $endDate . "',
                 0,
                 1,
                 0,
                 0,
                 0,
                 1,
                 0,
                 0,
                 0,
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
            );";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            INSERT INTO PRODUCTSELLINGPRICE 
            (
                 COMPANYID,
                 PRODUCTID,
                 COUNTRYID,
                 STATEID,
                 UNITOFMEASUREMENTID,
                 PRODUCTSELLINGPRICEQUANTITY,
                 PRODUCTSELLINGPRICEPRICE,
                 PRODUCTSELLINGPRICESTARTDATE,
                 PRODUCTSELLINGPRICEENDDATE,
                 ISDEFAULT,
                 ISNEW,
                 ISDRAFT,
                 ISUPDATE,
                 ISDELETE,
                 ISACTIVE,
                 ISAPPROVED,
                 ISREVIEW,
                 ISPOST,
                 EXECUTEBY,
                 EXECUTETIME
            ) VALUES ( 
                    '" . $this->getCompanyId() . "',
                 '" . $productId . "',
                 '" . $countryId . "',
                 '" . $stateId . "',
                 '" . $unitOfMeasurementId . "',
                 '" . $productSellingPriceQuantity . "',
                 '" . $productSellingPrice . "',
                 '" . $startDate . "',
                 '" . $endDate . "',
                 0,
                 1,
                 0,
                 0,
                 0,
                 1,
                 0,
                 0,
                 0,
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
            );";
        }
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Set New Fast Business Partner.Company Address And shipping address will be same as defaulted.
     * @param string $businessPartnerCompany Company
     * @param string $businessPartnerAddress Address
     * return int $businessPartnerId Business Partner Primary Key
     */
    public function setNewBusinessPartner($businessPartnerCompany, $businessPartnerAddress) {

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
        if ($this->getVendor() === self::MYSQL) {
            $sql = "
			INSERT INTO `businesspartner`
			 (
                `companyId`,
                `businessPartnerCategoryId`,
                `businessPartnerOfficeCountryId`,
                `businessPartnerOfficeStateId`,
                `businessPartnerOfficeCityId`,
                `businessPartnerShippingCountryId`,
                `businessPartnerShippingStateId`,
                `businessPartnerShippingCityId`,
                `businessPartnerCompany`,
                `businessPartnerOfficeAddress`,
                `businessPartnerShippingAddress`,
                `isDefault`,
                `isNew`,
                `isDraft`,
                `isUpdate`,
                `isDelete`,
                `isActive`,
                `isApproved`,
                `isReview`,
                `isPost`,
                `executeBy`,
                `executeTime`
 			  ) VALUES (
 			    '" . $this->getCompanyId() . "',
 			    '" . $this->getFastBusinessPartnerCategory() . "',

 			    '" . $this->getBusinessPartnerShippingCountryDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingStateDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingCityDefaultValue() . "',

 			    '" . $this->getBusinessPartnerShippingCountryDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingStateDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingCityDefaultValue() . "',

				'" . $businessPartnerCompany . "',
				'" . $businessPartnerAddress . "',
				'" . $businessPartnerAddress . "',

				 0,
                 1,
                 0,
                 0,
                 0,
                 1,
                 0,
                 0,
                 0,
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
			 )			
			";
        } else if ($this->getVendor() === self::MSSQL) {
            $sql = "
			INSERT INTO [businessPartner]
			 (
                [companyId],
                [businessPartnerCategoryId],
                [businessPartnerOfficeCountryId],
                [businessPartnerOfficeStateId],
                [businessPartnerOfficeCityId],
                [businessPartnerShippingCountryId],
                [businessPartnerShippingStateId],
                [businessPartnerShippingCityId],
                [businessPartnerCompany],
                [businessPartnerOfficeAddress],
                [businessPartnerShippingAddress],
                [isDefault],
                [isNew],
                [isDraft],
                [isUpdate],
                [isDelete],
                [isActive],
                [isApproved],
                [isReview],
                [isPost],
                [executeBy],
                [executeTime]
 			  ) VALUES (
 			    '" . $this->getCompanyId() . "',
 			    '" . $this->getFastBusinessPartnerCategory() . "',

 			    '" . $this->getBusinessPartnerShippingCountryDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingStateDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingCityDefaultValue() . "',

 			    '" . $this->getBusinessPartnerShippingCountryDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingStateDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingCityDefaultValue() . "',

				'" . $businessPartnerCompany . "',
				'" . $businessPartnerAddress . "',
				'" . $businessPartnerAddress . "',

				 0,
                 1,
                 0,
                 0,
                 0,
                 1,
                 0,
                 0,
                 0,
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
			 )
			";
        } else if ($this->getVendor() === self::ORACLE) {
            $sql = "
			INSERT INTO BUSINESSPARTNER
			 (
                COMPANYID,
                BUSINESSPARTNERCATEGORYID,
                BUSINESSPARTNEROFFICECOUNTRYID,
                BUSINESSPARTNEROFFICESTATEID,
                BUSINESSPARTNEROFFICECITYID,
                BUSINESSPARTNERSHIPPINGCOUNTRYID,
                BUSINESSPARTNERSHIPPINGSTATEID,
                BUSINESSPARTNERSHIPPINGCITYID,
                BUSINESSPARTNERCOMPANY,
                BUSINESSPARTNEROFFICEADDRESS,
                BUSINESSPARTNERSHIPPINGADDRESS,
                ISDEFAULT,
                ISNEW,
                ISDRAFT,
                ISUPDATE,
                ISDELETE,
                ISACTIVE,
                ISAPPROVED,
                ISREVIEW,
                ISPOST,
                EXECUTEBY,
                EXECUTETIME
 			  ) VALUES (
 			    '" . $this->getCompanyId() . "',
 			    '" . $this->getFastBusinessPartnerCategory() . "',

 			    '" . $this->getBusinessPartnerShippingCountryDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingStateDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingCityDefaultValue() . "',

 			    '" . $this->getBusinessPartnerShippingCountryDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingStateDefaultValue() . "',
 			    '" . $this->getBusinessPartnerShippingCityDefaultValue() . "',

				'" . $businessPartnerCompany . "',
				'" . $businessPartnerAddress . "',
				'" . $businessPartnerAddress . "',

				 0,
                 1,
                 0,
                 0,
                 0,
                 1,
                 0,
                 0,
                 0,
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
			 )
			";
        }

        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $businessPartnerId = $this->q->lastInsertId('businessPartner');
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => true,
                    "message" => $this->t['newRecordTextLabel'],
                    "staffName" => $_SESSION['staffName'],
                    "executeTime" => date('d-m-Y H:i:s'),
                    "businessPartnerId" => $businessPartnerId,
                    "time" => $time
                )
        );
        exit();
    }

    /**
     * Return Business Partner Category
     * @return int
     */
    private function getFastBusinessPartnerCategory() {
        $businessPartnerCategoryCode = 'FSCS';
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $businessPartnerCategoryId = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT		`businessPartnerCategoryId`
			FROM        `businesspartnercategory`
			WHERE       `isActive`  			        =   1
			AND         `companyId` 			        =   '" . $this->getCompanyId() . "'
			AND		 	`businessPartnerCategoryCode`	=	'" . $businessPartnerCategoryCode . "'
			LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      TOP 1 [businessPartnerCategoryId]
			FROM        [businessPartnerCategory]
			WHERE       [isActive]  			        =   1
			AND         [companyId] 			        =   '" . $this->getCompanyId() . "'
			AND		 	[businessPartnerCategoryCode]	=	'" . $businessPartnerCategoryCode . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT		BUSINESSPARTNERCATEGORYID AS \"businessPartnerCategoryId\"
			FROM        BUSINESSPARTNERCATEGORY
			WHERE       ISACTIVE  			        =   1
			AND         COMPANYID 			        =   '" . $this->getCompanyId() . "'
			AND		 	BUSINESSPARTNERCATEGORYCODE	=	'" . $businessPartnerCategoryCode . "'
			AND 		ROWNUM	  			=	1";
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
            $businessPartnerCategoryId = $row['businessPartnerCategoryId'];
        }
        return $businessPartnerCategoryId;
    }

    /**
     * Return Business Partner Shipping Country Default Value
     * @return int
     */
    public function getBusinessPartnerShippingCountryDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $countryId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `countryId`
         FROM        `country`
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
     * Return Business Partner Shipping State Default Value
     * @return int
     */
    public function getBusinessPartnerShippingStateDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $stateId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `stateId`
         FROM        `state`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [stateId],
         FROM        [state]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() === self::ORACLE) {
            $sql = "
         SELECT      STATEID AS \"stateId\",
         FROM        STATE
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
            $stateId = $row['stateId'];
        }
        return $stateId;
    }

    /**
     * Return BusinessPartnerShippingCity Default Value
     * @return int
     */
    public function getBusinessPartnerShippingCityDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $cityId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `cityId`
         FROM        `city`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [cityId],
         FROM        [city]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      CITYID AS \"cityId\",
         FROM        CITY
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
            $cityId = $row['cityId'];
        }
        return $cityId;
    }

    /**
     * Set New Fast Business Partner Contact Name
     * @param int $businessPartnerId Business Partner Primary Key
     * @param null|string $businessPartnerContactName Name
     * @param null|string $businessPartnerContactPhone Phone
     * @param null|string $businessPartnerContactEmail Email
     * return @void
     */
    public function setNewBusinessPartnerContact(
    $businessPartnerId, $businessPartnerContactName, $businessPartnerContactPhone = null, $businessPartnerContactEmail = null
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
        // check back if business partner id exist or not
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			INSERT INTO `businesspartnercontact`
			(
				`companyId`,
				`businessPartnerId`,
				`businessPartnerContactName`,
				`businessPartnerContactPhone`,
				`businessPartnerContactEmail`,
                `isDefault`,
                `isNew`,
                `isDraft`,
                `isUpdate`,
                `isDelete`,
                `isActive`,
                `isApproved`,
                `isReview`,
                `isPost`,
                `executeBy`,
                `executeTime`
			) VALUES(
			    '" . $this->getCompanyId() . "',
				'" . $businessPartnerId . "',
				'" . $businessPartnerContactName . "',
				'" . $businessPartnerContactPhone . "',
				'" . $businessPartnerContactEmail . "',
				0,
                 1,
                 0,
                 0,
                 0,
                 1,
                 0,
                 0,
                 0,
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
			)
			";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			INSERT INTO [businessPartnerContact]
			(
				[companyId],
				[businessPartnerId],
				[businessPartnerContactName],
				[businessPartnerContactPhone],
				[businessPartnerContactEmail],
                [isDefault],
                [isNew],
                [isDraft],
                [isUpdate],
                [isDelete],
                [isActive],
                [isApproved],
                [isReview],
                [isPost],
                [executeBy],
                [executeTime]
			) VALUES(
				'" . $this->getCompanyId() . "',
				'" . $businessPartnerId . "',
				'" . $businessPartnerContactName . "',
				'" . $businessPartnerContactPhone . "',
				'" . $businessPartnerContactEmail . "',
				 0,
                 1,
                 0,
                 0,
                 0,
                 1,
                 0,
                 0,
                 0,
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
			)
			";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			INSERT INTO BUSINESSPARTNERCONTACT
			(
				COMPANYID,
				BUSINESSPARTNERID,
				BUSINESSPARTNERCONTACTNAME,
				BUSINESSPARTNERCONTACTPHONE,
				BUSINESSPARTNERCONTACTEMAIL,
                ISDEFAULT,
                ISNEW,
                ISDRAFT,
                ISUPDATE,
                ISDELETE,
                ISACTIVE,
                ISAPPROVED,
                ISREVIEW,
                ISPOST,
                EXECUTEBY,
                EXECUTETIME
			) VALUES(
				'" . $this->getCompanyId() . "',
				'" . $businessPartnerId . "',
				'" . $businessPartnerContactName . "',
				'" . $businessPartnerContactPhone . "',
				'" . $businessPartnerContactEmail . "',
			    0,
                 1,
                 0,
                 0,
                 0,
                 1,
                 0,
                 0,
                 0,
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
			)
			";
        }

        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $businessPartnerContactId = $this->q->lastInsertId('businessPartnerContact');
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => true,
                    "message" => $this->t['newRecordTextLabel'],
                    "staffName" => $_SESSION['staffName'],
                    "executeTime" => date('d-m-Y H:i:s'),
                    "businessPartnerContactId" => $businessPartnerContactId,
                    "time" => $time
                )
        );
        exit();
    }

    /**
     * Post Invoice To General Ledger
     * @param int $invoiceId Invoice Debit Note Primary Key
     * @param int $leafId Leaf Primary Key
     * @param string $leafName Leaf Name
     *
     */
    public function setPosting($invoiceId, $leafId, $leafName) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  *
            FROM    `invoice`
            WHERE   `invoiceId` IN (" . $invoiceId . ")
            AND     `isActive`= 	1
            AND     `isPost`    =   0
            AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  *
            FROM    [invoice]
            WHERE   [invoiceId] IN (" . $invoiceId . ")
            AND     [isActive]= 	1
            AND     [isPost] =0
            AND     [companyId] =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  *
            FROM    INVOICE
            WHERE   INVOICEID IN (" . $invoiceId . ")
            AND     ISACTIVE= 	1
            AND     ISPOST      =  0
            AND     COMPANYID   =   '" . $this->getCompanyId() . "'";
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
            while (($row = $this->q->fetchArray($result)) == true) {
                $invoiceId = $row['invoiceId'];
                $this->setInvoiceStatusTracking($invoiceId, $this->getInvoiceStatusId(self::TRANSFER_TO_GENERAL_LEDGER));
            }
        }
        $journalNumber = $this->getDocumentNumber('GLPT');
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  *
            FROM    `invoicedetail`
            JOIN    `invoice`
            USING   (`companyId`,`invoiceId`)
            WHERE   `invoice`.`invoiceId` IN (" . $invoiceId . ")
            ORDER BY `invoiceId";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  *
            FROM    [invoiceDetail]
            JOIN    [invoiceDebit]
            ON      [invoiceDebitDetail].[companyId]         =   [invoiceDebit].[companyId]
            AND     [invoiceDebitDetail].[invoiceId] =   [invoiceDebit].[invoiceDebitId]
            WHERE   [invoiceDebitId] IN (" . $invoiceId . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  *
            FROM    INVOICEDETAIL
            JOIN    INVOICE
            ON      INVOICEDETAIL.COMPANYID         =   INVOICE.COMPANYID
            AND     INVOICEDETAIL.INVOICEID =   INVOICE.INVOICEID
            WHERE   INVOICE.INVOICEID IN (" . $invoiceId . ")";
        }
        try {
            $resultDetail = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($resultDetail) {
            while (($rowDetail = $this->q->fetchArray($resultDetail)) == true) {
                $businessPartnerId = $rowDetail['businessPartnerId'];
                $chartOfAccountId = $rowDetail['chartOfAccountId'];
                $documentNumber = $rowDetail['documentNumber'];
                $documentDate = $rowDetail['invoiceDate'];
                $localAmount = $rowDetail['invoiceDetailAmount'];
                $description = $rowDetail['invoiceDescription'];
                $module = 'AP';
                $tableName = 'purchase';
                $tableNameDetail = 'invoiceDetail';
                $tableNameId = 'invoiceId';
                $tableNameDetailId = 'invoiceDetailId';
                $referenceTableNameId = $row['invoiceId'];
                $referenceTableNameDetailId = $row['invoiceDetailId'];
                $invoiceDueDate = $row['invoiceDueDate'];
                $this->ledgerService->setInvoiceLedger($businessPartnerId, $chartOfAccountId, $documentNumber, $documentDate, $invoiceDueDate, $description, $localAmount, $leafId, $invoiceId);
                $this->ledgerService->setGeneralLedger($leafId, $leafName, $businessPartnerId, $chartOfAccountId, $documentNumber, $journalNumber, $documentDate, $localAmount, $description, $module, $tableName, $tableNameDetail, $tableNameId, $tableNameDetailId, $referenceTableNameId, $referenceTableNameDetailId);
            }
        }
        // make second batch for detail.. no more loop in loop
        $this->setInvoicePosted($invoiceId);
    }

    /**
     * Set Invoice Tracking
     * @param int $invoiceId Invoice Primary Key
     * @param int $invoiceStatusId Status Primary Key
     */
    public function setInvoiceStatusTracking($invoiceId, $invoiceStatusId) {
        $sql = null;
        $invoiceTrackingDurationDay = 0;
        $invoiceTrackingDurationHour = 0;
        // check if exist previous payment voucher transaction and compare with the current day.
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT DATEDIFF(current_date(),`executeTime`) AS `invoiceTrackingDurationDay`,
                   (time_to_sec(timediff(current_date(),executeTime)) / 3600) as `invoiceTrackingDurationHour`
            FROM   `invoice`
            WHERE  `invoiceId` ='" . $invoiceId . "'
            ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT DATEDIFF(DAY,getDate(),[executeTime]) as [invoiceTrackingDurationDay],
                   DATEDIFF(HOUR,getDate(),[executeTime]) as [invoiceTrackingDurationHour]
            FROM   [invoice]
            WHERE  [invoiceId] ='" . $invoiceId . "'
            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT TRUNC(SYSDATE - to_date(EXECUTETIME,'dd-mon-yyyy')) AS \"invoiceTrackingDurationDay\",
                   TRUNC((SYSDATE - to_date(EXECUTETIME,'dd-mon-yyyy hh24:mi')) / 24) AS \"invoiceTrackingDurationHour\"
            FROM   INVOICE
            WHERE  INVOICEID ='" . $invoiceId . "'
            ";
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
            if (is_array($row)) {
                $invoiceTrackingDurationDay = $row['invoiceTrackingDurationDay'];
                $invoiceTrackingDurationHour = $row['invoiceTrackingDurationHour'];
            }
        }

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `invoicetracking`(
                `invoiceTrackingId`,                    `companyId`,
                `invoiceId`,                            `invoiceStatusId`,
                `invoiceTrackingDurationDay`,           `invoiceTrackingHour`,
                `isDefault`,
                `isNew`,                                        `isDraft`,
                `isUpdate`,                                     `isDelete`,
                `isActive`,                                     `isApproved`,
                `isReview`,                                     `isPost`,
                `executeBy`,                                    `executeTime`
            ) VALUES (
                null,                                           " . $this->getCompanyId() . ",
                '" . $invoiceId . "',                   " . $invoiceStatusId . ",
                '" . $invoiceTrackingDurationDay . "',  '" . $invoiceTrackingDurationHour . "',
                0,
                1,                                          0,
                0,                                          0,
                1,                                          0,
                0,                                          0,
                '" . $this->getStaffId() . "',               " . $this->getExecuteTime() . "
             )
            ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            INSERT INTO [invoiceTracking](
                [invoiceTrackingId],                [companyId],
                [invoiceId],                        [invoiceStatusId],
                [invoiceTrackingDurationDay],       [invoiceTrackingDurationHour],
                [isDefault],
                [isNew],                                    [isDraft],
                [isUpdate],                                 [isDelete],
                [isActive],                                 [isApproved],
                [isReview],                                 [isPost],
                [executeBy],                                [executeTime]
            ) VALUES (
                null,                                       " . $this->getCompanyId() . ",
                '" . $invoiceId . "',               " . $invoiceStatusId . ",
                '" . $invoiceTrackingDurationDay . "', '" . $invoiceTrackingDurationHour . "',
                0,
                1,                                          0,
                0,                                          0,
                1,                                          0,
                0,                                          0,
                '" . $this->getStaffId() . "',              " . $this->getExecuteTime() . ")
            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            INSERT INTO iNVOICETRACKING (
                iNVOICETRACKINGID,                  COMPANYID,
                iNVOICEID,                          iNVOICETATUSID,
                iNVOICETRACKINGDURATIONDAY,         iNVOICETRACKINGDURATIONHOUR,
                ISDEFAULT,
                ISNEW,                                      ISDRAFT,
                ISUPDATE,                                   ISDELETE,
                ISACTIVE,                                   ISAPPROVED,
                ISREVIEW,                                   ISPOST,
                EXECUTEBY,                                  EXECUTETIME
            ) VALUES (
                null,                                       " . $this->getCompanyId() . ",
                '" . $invoiceId . "',               " . $invoiceStatusId . ",
                '" . $invoiceTrackingDurationDay . "', '" . $invoiceTrackingDurationHour . "',
                0,
                1,                                          0,
                0,                                          0,
                1,                                          0,
                0,                                          0,
                '" . $this->getStaffId() . "',               " . $this->getExecuteTime() . "
             )
            ";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     *  Internal Invoice Tracking System
     * @param string $invoiceStatusCode
     * @return int
     */
    private function getInvoiceStatusId($invoiceStatusCode) {
        $sql = null;
        $invoiceStatusId = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `invoiceStatusId`
            FROM    `invoicestatus`
            WHERE   `invoiceStatusCode`  =   '" . $invoiceStatusCode . "'
            AND     `companyId`             =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  [invoiceStatusId]
            FROM    [invoiceStatus]
            WHERE   [invoiceStatusCode]  =   '" . $invoiceStatusCode . "'
            AND     [companyId]             =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  INVOICESTATUSID
            FROM    INVOICESTATUS
            WHERE   INVOICESTATUSCODE    =   '" . $invoiceStatusCode . "'
            AND     COMPANYID               =   '" . $this->getCompanyId() . "'";
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
            $invoiceStatusId = $row['invoiceStatusId'];
        }
        return $invoiceStatusId;
    }

    /**
     * Create Loan Principal And Interest based on rule 78.It Based On Period
     * @param int $invoiceId Invoice Primary Key
     * @return void
     */
    function setRuleSeventyEight($invoiceId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT	* 
            FROM   `invoice`
            WHERE  `invoiceId` = '" . $invoiceId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT	*
            FROM   [invoice]
            WHERE  [invoiceId] = '" . $invoiceId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT	*
            FROM   INVOICE
            WHERE  INVOICEID = '" . $invoiceId . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        // declare variable
        $j = 0;
        $totalPrincipalAmount = 0;
        $totalInterestDeduct = 0;
        $period = 0;
        $invoiceAmount = 0;
        $interest = 0;
        $totalPayable = 0;
        if ($result) {

            $row = $this->q->fetchArray($result);
            $period = $row['invoicePeriod'];
            $interestRate = $row['invoiceInterestRate'];
            $invoiceAmount = $row['invoiceAmount'];
            $invoiceStartDate = $row['invoiceStartDate'];
            $interest = $invoiceAmount * ($interestRate / 100) * ($period / 12);
            $totalPayable = $invoiceAmount + $interest;
            $monthlyInstallment = round($totalPayable / $period, 2);
            $differencesInterest = $interest * (1 * (1 + 1)) / ($period * ($period + 1));

            for ($i = 0; $i < $period; $i++) {
                $interestDeduct = $differencesInterest * $period;
                $principalAmount = $monthlyInstallment - $interestDeduct;
                $totalPrincipalAmount += $principalAmount;
                $totalInterestDeduct += $interestDeduct;
                //$totalMonthlyInstallment += $monthlyInstallment;
                $j++;
                $sql = "
				(	
					'" . $j . "',
					'" . $principalAmount . "',
					'" . $interestDeduct . "',
					'" . $monthlyInstallment . "',
					(SELECT DATE_FORMAT(ADDDATE('" . $invoiceStartDate . "', INTERVAL " . ($j - 1) . " MONTH)  ,'%Y-%m-01')),
					0,
					1,									
					1,
					0,								    
					0,
					1,									
					0,
					0,									
					0,
					'" . $this->getStaffId() . "',			
					" . $this->getExecuteTime() . "
				),";
            }
        }
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        // there was possibility last schedule sum incorrect
        if ($totalPrincipalAmount + $totalInterestDeduct != $totalPayable) {
            // check total principal amount
            if ($totalPrincipalAmount != $invoiceAmount) {
                if ($totalPrincipalAmount > $invoiceAmount) {
                    $differences = $totalPrincipalAmount - $invoiceAmount;
                    if ($this->getVendor() == self::MYSQL) {
                        $sql = "
						UPDATE `invoiceloandetail`
						SET	   `invoiceLoanDetailPrincipalAmount` 	=	`invoiceLoanDetailPrincipalAmount` - '" . $differences . "'
						WHERE  `invoiceLoanDetailPeriod`		  	=	'" . $period . "'
						AND	   `invoiceId`							=	'" . $invoiceId . "'
						";
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $sql = "
						UPDATE [invoiceLoanDetail]
						SET	   [invoiceLoanDetailPrincipalAmount] 	=	[invoiceLoanDetailPrincipalAmount] - '" . $differences . "'
						WHERE  [invoiceLoanDetailPeriod]		  	=	'" . $period . "'
						AND	   [invoiceId]							=	'" . $invoiceId . "'
						";
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $sql = "
						UPDATE 	INVOICELOANDETAIL
						SET	  	INVOICELOANDETAILPRINCIPALAMOUNT 	=	INVOICELOANDETAILPRINCIPALAMOUNT - '" . $differences . "'
						WHERE  	INVOICELOANDETAILPERIOD		  		=	'" . $period . "'
						AND	   	INVOICEID							=	'" . $invoiceId . "'
						";
                            }
                        }
                    }
                    try {
                        $this->q->create($sql);
                    } catch (\Exception $e) {
                        $this->q->rollback();
                        echo json_encode(array("success" => false, "message" => $e->getMessage()));
                        exit();
                    }
                }
                if ($totalPrincipalAmount < $invoiceAmount) {
                    $differences = $invoiceAmount - $totalPrincipalAmount;
                    if ($this->getVendor() == self::MYSQL) {
                        $sql = "
						UPDATE `invoiceloandetail`
						SET	   `invoiceLoanDetailPrincipalAmount` 	=	`invoiceLoanDetailPrincipalAmount` + '" . $differences . "'
						WHERE  `invoiceLoanDetailPeriod`		  	=	'" . $period . "'
						AND	   `invoiceId`							=	'" . $invoiceId . "'
						";
                    } else if ($this->getVendor() == self::MSSQL) {
                        $sql = "
						UPDATE [invoiceLoanDetail]
						SET	   [invoiceLoanDetailPrincipalAmount] 	=	[invoiceLoanDetailPrincipalAmount] + '" . $differences . "'
						WHERE  [invoiceLoanDetailPeriod]		  	=	'" . $period . "'
						AND	   [invoiceId]							=	'" . $invoiceId . "'
						";
                    } else if ($this->getVendor() == self::ORACLE) {
                        $sql = "
						UPDATE 	INVOICELOANDETAIL
						SET	  	INVOICELOANDETAILPRINCIPALAMOUNT 	=	INVOICELOANDETAILPRINCIPALAMOUNT + '" . $differences . "'
						WHERE  	INVOICELOANDETAILPERIOD		  		=	'" . $period . "'
						AND	   	INVOICEID							=	'" . $invoiceId . "'
						";
                    }
                    try {
                        $this->q->create($sql);
                    } catch (\Exception $e) {
                        $this->q->rollback();
                        echo json_encode(array("success" => false, "message" => $e->getMessage()));
                        exit();
                    }
                }
            }
            // check total interest amount 
            if ($totalInterestDeduct != $interest) {
                if ($totalPrincipalAmount > $interest) {
                    $differences = $totalInterestDeduct - $interest;
                    if ($this->getVendor() == self::MYSQL) {
                        $sql = "
						UPDATE `invoiceloandetail`
						SET	   `invoiceLoanDetailInterestAmount` 	=	`invoiceLoanDetailInterestAmount` - '" . $differences . "'
						WHERE  `invoiceLoanDetailPeriod`		  	=	'" . $period . "'
						AND	   `invoiceId`							=	'" . $invoiceId . "'
						";
                    } else if ($this->getVendor() == self::MSSQL) {
                        $sql = "
						UPDATE [invoiceLoanDetail]
						SET	   [invoiceLoanDetailInterestAmount] 	=	[invoiceLoanDetailInterestAmount] - '" . $differences . "'
						WHERE  [invoiceLoanDetailPeriod]		  	=	'" . $period . "'
						AND	   [invoiceId]							=	'" . $invoiceId . "'
						";
                    } else if ($this->getVendor() == self::ORACLE) {
                        $sql = "
						UPDATE 	INVOICELOANDETAIL
						SET	  	INVOICELOANDETAILPINTERESTAMOUNT 	=	INVOICELOANDETAILINTERESTAMOUNT - '" . $differences . "'
						WHERE  	INVOICELOANDETAILPERIOD		  		=	'" . $period . "'
						AND	   	INVOICEID							=	'" . $invoiceId . "'
						";
                    }
                    try {
                        $this->q->create($sql);
                    } catch (\Exception $e) {
                        $this->q->rollback();
                        echo json_encode(array("success" => false, "message" => $e->getMessage()));
                        exit();
                    }
                }
                if ($totalPrincipalAmount < $interest) {
                    $differences = $interest - $totalInterestDeduct;
                    if ($this->getVendor() == self::MYSQL) {
                        $sql = "
						UPDATE `invoiceloandetail`
						SET	   `invoiceLoanDetailInterestAmount` 	=	`invoiceLoanDetailInterestAmount` + '" . $differences . "'
						WHERE  `invoiceLoanDetailPeriod`		  	=	'" . $period . "'
						AND	   `invoiceId`							=	'" . $invoiceId . "'
						";
                    } else if ($this->getVendor() == self::MSSQL) {
                        $sql = "
						UPDATE [invoiceLoanDetail]
						SET	   [invoiceLoanDetailInterestAmount] 	=	[invoiceLoanDetailInterestAmount] + '" . $differences . "'
						WHERE  [invoiceLoanDetailPeriod]		  	=	'" . $period . "'
						AND	   [invoiceId]							=	'" . $invoiceId . "'
						";
                    } else if ($this->getVendor() == self::ORACLE) {
                        $sql = "
						UPDATE 	INVOICELOANDETAIL
						SET	  	INVOICELOANDETAILINTERESTAMOUNT 	=	INVOICELOANDETAILINTERESTAMOUNT + '" . $differences . "'
						WHERE  	INVOICELOANDETAILPERIOD		  		=	'" . $period . "'
						AND	   	INVOICEID							=	'" . $invoiceId . "'
						";
                    }
                    try {
                        $this->q->create($sql);
                    } catch (\Exception $e) {
                        $this->q->rollback();
                        echo json_encode(array("success" => false, "message" => $e->getMessage()));
                        exit();
                    }
                }
            }
        }
    }

    /**
     * Update Invoice Posted Flag
     * @param int $invoiceId Invoice Primary Key
     */
    private function setInvoicePosted($invoiceId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE  `invoice`
            SET     `isPost`        =  1,
                    `executeBy`     =   '" . $this->getStaffId() . "',
                    `executeTime`   =   " . $this->getExecuteTime() . "
            WHERE   `invoiceId` IN (" . $invoiceId . ")";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE  [invoice]
            SET     [isPost]        =  1,
                    [executeBy]     =   '" . $this->getStaffId() . "',
                    [executeTime]   =   " . $this->getExecuteTime() . "
            WHERE   [invoiceId] IN (" . $invoiceId . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE  INVOICE
            SET     ISPOST              =  1,
                    EXECUTEBY           =   '" . $this->getStaffId() . "',
                    EXECUTETIME         =   " . $this->getExecuteTime() . "
            WHERE   INVOICEID IN (" . $invoiceId . ")";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Upload Invoice Attachment before submitting the form.
     * @throws \Exception
     * @return void
     */
    function setInvoiceAttachment() {
        header('Content-Type:application/json; charset=utf-8');
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        $qqfile = null;
        $uploader = new \qqFileUploader($this->getAllowedExtensions(), $this->getSizeLimit());
        $result = $uploader->handleUpload($this->getUploadPath());
        if (isset($_GET['qqfile'])) {
            $qqfile = $_GET['qqfile'];
        }
// to pass data through iframe you will need to encode all html tags
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        INSERT INTO `invoicetemp`(
             `companyId`,
             `staffId`,
             `leafId`,
             `invoiceTempName`, 
             `isNew`, 
             `executeBy`, 
             `executeTime`
        ) VALUES (
            '" . $this->getCompanyId() . "',
            '" . $this->getStaffId() . "',
            120,
            '" . $this->strict($qqfile, 'w') . "',
            1,
            '" . $_SESSION['staffId'] . "',NOW())";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
        INSERT INTO [invoiceTemp](
             [companyId],
             [staffId],
             [leafId],
             [invoiceTempName],
             [isNew],
             [executeBy],
             [executeTime]
        ) VALUES (
            '" . $this->getCompanyId() . "',
            '" . $this->getStaffId() . "',
            120,
            '" . $this->strict($_GET['qqfile'], 'w') . "',
            1,
            '" . $_SESSION['staffId'] . "',NOW())";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
           INSERT INTO INVOICETEMP(
             COMPANYID,
             STAFFID,
             LEAFID,
             INVOICETEMPNAME,
             ISNEW,
             EXECUTEBY,
             EXECUTETIME
        ) VALUES (
            '" . $this->getCompanyId() . "',
            '" . $this->getStaffId() . "',
            120,
            '" . $this->strict($_GET['qqfile'], 'w') . "',
            1,
            '" . $_SESSION['staffId'] . "',NOW())";
        }
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        exit();
    }

    /**
     * Take the last temp file
     * @param int $staffId Staff Primary Key
     * @param int $invoiceId Invoice   Primary Key
     * @throws \Exception
     */
    function transferAttachment($staffId, $invoiceId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT      * 
			FROM        `invoicetemp`
			WHERE       `isNew`=1
			AND         `staffId`='" . $staffId . "'
			ORDER BY    `imageTempId` DESC
			LIMIT        1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      * 
			FROM        [invoiceTemp]
			WHERE      [isNew]=1
			AND         [staffId]='" . $staffId . "'
			ORDER BY    [imageTempId] DESC
			LIMIT        1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT      * 
			FROM         INVOICETEMP
			WHERE       ISNEW=1
			AND            STAFFID='" . $staffId . "'
			ORDER BY    IMAGETEMPID DESC
			LIMIT        1";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $documentCategoryId = $this->getDocumentCategory(self::ATTACHMENT_CODE);
            while (($row = $this->q->fetchArray($result)) == TRUE) {
                //  insert into document attachment/
                $documentPath = $this->getFakeDocumentRoot() . "v3/financial/accountReceivable/attachment/" . $row['invoiceTempName'];
                //  insert into document attachment/
                if ($this->getVendor() == self::MYSQL) {
                    $sql = "
				INSERT INTO `document`(
									`documentId`, 
									`companyId`, 
									`documentCategoryId`, 
									
									`applicationId`, 
									`moduleId`, 
									`folderId`, 
									
									`leafId`, 
									`documentTitle`, 
									`documentDescription`, 
									
									`documentPath`, 
									`documentFilename`, 
									`isDefault`, 
									
									`isNew`, 
									`isDraft`, 
									`isUpdate`, 
									
									`isDelete`, 
									`isActive`, 
									`isApproved`, 
									
									`isReview`, 
									`isPost`, 
									`executeBy`, 
									
									`executeTime`
									
								) VALUES (
									null,
									'" . $this->getCompanyId() . "',
									'" . $documentCategoryId . "',
									
									'" . $this->getApplicationId() . "',
									'" . $this->getModuleId() . "',
									'" . $this->getFolderId() . "',
									
									'" . $this->getLeafId() . "',
									'" . $row['invoiceTempName'] . "',
									'" . $row['invoiceTempName'] . "',
									
									'" . $documentPath . "',
									'" . $row['invoiceTempName'] . "',
									0,
									
									1,
									0,
									0,
									
									0,
									1,
									0,
									
									0,
									0,
									'" . $this->getStaffId() . "',
									
									" . $this->getExecuteTime() . "
								)";
                } else if ($this->getVendor() == self::MSSQL) {
                    $sql = "
				INSERT INTO [document](
									[documentId], 
									[companyId], 
									[documentCategoryId], 
									
									[applicationId], 
									[moduleId], 
									[folderId], 
									
									[leafId], 
									[documentTitle], 
									[documentDescription], 
									
									[documentPath], 
									[documentFilename], 
									[isDefault`, 
									
									[isNew], 
									[isDraft], 
									[isUpdate], 
									
									[isDelete], 
									[isActive], 
									[isApproved`, 
									
									[isReview], 
									[isPost], 
									[executeBy], 
									
									[executeTime]
									
								) VALUES (
									null,
									'" . $this->getCompanyId() . "',
									'" . $documentCategoryId . "',
									
									'" . $this->getApplicationId() . "',
									'" . $this->getModuleId() . "',
									'" . $this->getFolderId() . "',
									
									'" . $this->getLeafId() . "',
									'" . $row['invoiceTempName'] . "',
									'" . $row['invoiceTempName'] . "',
									
									'" . $documentPath . "',
									'" . $row['invoiceTempName'] . "',
									0,
									
									1,
									0,
									0,
									
									0,
									1,
									0,
									
									0,
									0,
									'" . $this->getStaffId() . "',
									
									" . $this->getExecuteTime() . "
								)";
                } else if ($this->getVendor() == self::ORACLE) {
                    $sql = "
				INSERT INTO DOCUMENT(
									DOCUMENTID, 
									COMPANYID, 
									DOCUMENTCATEGORYID, 
									
									APPLICATIONID, 
									MODULEID, 
									FOLDERID, 
									
									LEAFID, 
									DOCUMENTTITLE, 
									DOCUMENTDESCRIPTION, 
									
									DOCUMENTPATH, 
									DOCUMENTFILENAME, 
									ISDEFAULT, 
									
									ISNEW, 
									ISDRAFT, 
									ISUPDATE, 
									
									ISDELETE, 
									ISACTIVE, 
									ISAPPROVED, 
									
									ISREVIEW, 
									ISPOST, 
									EXECUTEBY, 
									
									EXECUTETIME
									
								) VALUES (
									null,
									'" . $this->getCompanyId() . "',
									'" . $documentCategoryId . "',
									
									'" . $this->getApplicationId() . "',
									'" . $this->getModuleId() . "',
									'" . $this->getFolderId() . "',
									
									'" . $this->getLeafId() . "',
									'" . $row['invoiceTempName'] . "',
									'" . $row['invoiceTempName'] . "',
									
									'" . $documentPath . "',
									'" . $row['invoiceTempName'] . "',
									0,
									
									1,
									0,
									0,
									
									0,
									1,
									0,
									
									0,
									0,
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
                //  get last primary key
                $documentId = $this->q->lastInsertId("document");
                // insert into invoice attachment image table
                if ($this->getVendor() == self::MYSQL) {
                    $sql = "
				INSERT INTO `invoiceattachment`(
									`invoiceAttachmentId`,
									`companyId`, 
									`invoiceId`, 
									
									`documentId`, 
									`isDefault`, 
									`isNew`, 
									
									`isDraft`, 
									`isUpdate`, 
									`isDelete`, 
									
									`isActive`, 
									`isApproved`, 
									`isReview`, 
									
									`isPost`, 
									`executeBy`, 
									`executeTime`
								) VALUES (
									null,
									'" . $this->getCompanyId() . "',
									'" . $invoiceId . "',
									
									'" . $documentId . "',
									0,
									1,
									
									0,
									0,
									0,
									
									1,
									0,
									0,
									
									0,
									'" . $this->getStaffId() . "',
									" . $this->getExecuteTime() . "";
                } else if ($this->getVendor() == self::MSSQL) {
                    $sql = "
				INSERT INTO [invoiceAttachment](
									[invoiceAttachmentId],
									[companyId], 
									[invoiceId], 
									
									[documentId], 
									[isDefault], 
									[isNew], 
									
									[isDraft], 
									[isUpdate], 
									[isDelete], 
									
									[isActive], 
									[isApproved], 
									[isReview], 
									
									[isPost], 
									[executeBy], 
									[executeTime]
								) VALUES (
									null,
									'" . $this->getCompanyId() . "',
									'" . $invoiceId . "',
									
									'" . $documentId . "',
									0,
									1,
									
									0,
									0,
									0,
									
									1,
									0,
									0,
									
									0,
									'" . $this->getStaffId() . "',
									" . $this->getExecuteTime() . "";
                } else if ($this->getVendor() == self::ORACLE) {
                    $sql = "
				INSERT INTO INVOICEATTACHMENT(
									INVOICEATTACHMENTID,
									COMPANYID, 
									INVOICEID, 
									
									DOCUMENTID, 
									ISDEFAULT, 
									ISNEW, 
									
									ISDRAFT, 
									ISUPDATE, 
									ISDELETE, 
									
									ISACTIVE, 
									ISAPPROVED, 
									ISREVIEW, 
									
									ISPOST, 
									EXECUTEBY, 
									EXECUTETIME
								) VALUES (
									null,
									'" . $this->getCompanyId() . "',
									'" . $invoiceId . "',
									
									'" . $documentId . "',
									0,
									1,
									
									0,
									0,
									0,
									
									1,
									0,
									0,
									
									0,
									'" . $this->getStaffId() . "',
									" . $this->getExecuteTime() . "";
                }
                try {
                    $this->q->create($sql);
                } catch (\Exception $e) {
                    echo json_encode(array("success" => false, "message" => $e->getMessage()));
                    exit();
                }
            }
        }
        // update back  the last image file to 0 preventing update the same thing again
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			UPDATE `invoicetemp`
			SET    `isNew`    = '0'
			WHERE  `staffId`        = '" . $_SESSION['staffId'] . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			UPDATE `invoiceTemp`
			SET    `isNew`    = '0'
			WHERE  `staffId`        = '" . $_SESSION['staffId'] . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			UPDATE INVOICETEMP
			SET       ISNEW`    = '0'
			WHERE  STAFFID        = '" . $_SESSION['staffId'] . "'";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Return Document Attachment Category Primary Key
     * @param string $attachmentCode
     * @return null
     */
    private function getDocumentCategory($attachmentCode) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $documentCategoryId = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT	`documentCategoryId`
			FROM	`documentcategory`
			WHERE	`companyId`		 =   '" . $this->getCompanyId() . "'
			AND		`documentCategoryCode`    =   '" . $attachmentCode . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT	[documentCategoryId]
			FROM	[documentCategory]
			WHERE	[companyId]	 =   '" . $this->getCompanyId() . "'
			AND		[documentCategoryCode]    =   '" . $attachmentCode . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT	DOCUMENTCATEGORYID AS  \"documentCategoryId\"
			FROM	DOCUMENTCATEGORY
			WHERE	COMPANYID		 =   '" . $this->getCompanyId() . "'
			AND		DOCUMENTCATEGORYCODE    =   '" . $attachmentCode . "'";
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
            $row = $this->q->fetchArray($result);
            if (is_array($row)) {
                $documentCategoryId = $row['documentCategoryId'];
            }
        }
        return $documentCategoryId;
    }

    /**
     * Return Total Invoice Day.
     * @param int $invoiceId Invoice Primary Key
     * @return int $totalInvoiceTrackingDay Total Day
     * @throw exception
     */
    private function getTotalInvoiceTrackingDay($invoiceId) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $totalInvoiceTrackingDay = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      SUM(`invoiceTrackingDurationDay`) AS `totalInvoiceTrackingDay`
            FROM        `invoicetracking`
            WHERE       `isActive`  =   1
            AND         `companyId` =   '" . $this->getCompanyId() . "'
            AND    	  `invoiceId` =	  '" . $invoiceId . "'
            GROUP BY   `invoiceId`";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      SUM([invoiceTrackingDurationDay]) AS [totalInvoiceTrackingDay]
            FROM        [invoiceTracking]
            WHERE       [isActive]  =   1
            AND         [companyId] =   '" . $this->getCompanyId() . "'
            AND    	    [invoiceId] =	  '" . $invoiceId . "'
            GROUP BY    [invoiceId] ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      SUM(INVOICETRACKINGDURATIONDAY) AS \"totalInvoiceTrackingDay\"
            FROM         INVOICETRACKING
            WHERE       ISACTIVE  =   1
            AND          COMPANYID =   '" . $this->getCompanyId() . "'
            AND    	    INVOICEID =	  '" . $invoiceId . "'
            GROUP BY    INVOICEID ";
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
            $totalInvoiceTrackingDay = (int) $row['totalInvoiceTrackingDay'];
        }
        return $totalInvoiceTrackingDay;
    }

    /**
     * Return Total Invoice Hour.
     * @param int $invoiceId Invoice Primary Key
     * @return int $totalInvoiceTrackingHour Total Day
     * @depreciated  Save it as emengency
     * @throw exception
     */
    private function getTotalInvoiceTrackingHour($invoiceId) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $totalInvoiceTrackingHour = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      SUM(`invoiceTrackingDurationHour`) AS `totalInvoiceTrackingHour`
            FROM        `invoicetracking`
            WHERE       `isActive`  =   1
            AND         `companyId` =   '" . $this->getCompanyId() . "'
            AND    	  `invoiceId` =	  '" . $invoiceId . "'
            GROUP BY   `invoiceId`";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      SUM([invoiceTrackingDurationDay]) AS [totalInvoiceTrackingHour]
            FROM        [invoiceTracking]
            WHERE       [isActive]  =   1
            AND         [companyId] =   '" . $this->getCompanyId() . "'
            AND    	    [invoiceId] =	  '" . $invoiceId . "'
            GROUP BY    [invoiceId] ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      SUM(INVOICETRACKINGDURATIONHOUR) AS \"totalInvoiceTrackingHour\"
            FROM         INVOICETRACKING
            WHERE       ISACTIVE  =   1
            AND          COMPANYID =   '" . $this->getCompanyId() . "'
            AND    	    INVOICEID =	  '" . $invoiceId . "'
            GROUP BY    INVOICEID ";
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
            $totalInvoiceTrackingHour = $row['totalInvoiceTrackingHour'];
        }
        return $totalInvoiceTrackingHour;
    }

    /**
     * Return Total Tracking Holiday
     * @param int $invoiceId Invoice Primary Key
     * @return int $totalHoliday Total Holiday
     */
    private function getTotalTrackingHolidayInvoice($invoiceId) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $totalHoliday = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      count(*) AS total
            FROM        `leaveholidays`
            WHERE       `isActive`  =   1
            AND         `companyId` =   '" . $this->getCompanyId() . "'
            AND    	    `invoiceId` =	  '" . $invoiceId . "'
            AND        `leaveHolidaysDate` BETWEEN (
                                                           (
                                                               SELECT MIN(invoiceTrackingDate)
                                                               FROM   `invoicetracking`
                                                               WHERE  `companyId`   =   '" . $this->getCompanyId() . "'
                                                               AND    `invoiceId`=   '" . $invoiceId . "'
                                                           )
                                                        AND
                                                           (
                                                               SELECT MAX(invoiceTrackingDate)
                                                               FROM   `invoicetracking`
                                                               WHERE  `companyId`   =   '" . $this->getCompanyId() . "'
                                                               AND    `invoiceId`=   '" . $invoiceId . "'
                                                           )
                                                    )
            AND        `isNational` =   1
            AND        `isState`    =   1
            AND        `isWeekend`  =   1

            GROUP BY   `invoiceId`";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      count(*) AS [totalHoliday]
            FROM       [leaveHolidays]
            WHERE      [isActive]  =   1
            AND        [companyId] =   '" . $this->getCompanyId() . "'
            AND    	   [invoiceId] =	  '" . $invoiceId . "'
            AND        [leaveHolidaysDate` BETWEEN (
                                                           (
                                                               SELECT MIN(invoiceTrackingDate)
                                                               FROM   [invoiceTracking]
                                                               WHERE  [companyId]   =   '" . $this->getCompanyId() . "'
                                                               AND    [invoiceId]=   '" . $invoiceId . "'
                                                           )
                                                        AND
                                                           (
                                                               SELECT MAX([invoiceTrackingDate])
                                                               FROM   [invoiceTracking]
                                                               WHERE  [companyId]   =   '" . $this->getCompanyId() . "'
                                                               AND    [invoiceId]=   '" . $invoiceId . "'
                                                           )
                                                    )
            AND        [isNational] =   1
            AND        [isState]    =   1
            AND        [isWeekend]  =   1

            GROUP BY   [invoiceId]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      COUNT(*) AS \"totalHoliday\"
            FROM       LEAVEHOLIDAYS
            WHERE      ISACTIVE  =   1
            AND        COMPANYID =   '" . $this->getCompanyId() . "'
            AND    	   INVOICEID =	  '" . $invoiceId . "'
            AND        LEAVEHOLIDAYSDATE (BETWEEN
                                                           (
                                                               SELECT MIN(INVOICETRACKINGDATE)
                                                               FROM   INVOICETRACKING
                                                               WHERE  COMPANYID     =   '" . $this->getCompanyId() . "'
                                                               AND    INVOICEID  =   '" . $invoiceId . "'
                                                           )
                                                        AND
                                                           (
                                                               SELECT MAX(INVOICETRACKINGDATE)
                                                               FROM   INVOICETRACKING
                                                               WHERE  COMPANYID     =   '" . $this->getCompanyId() . "'
                                                               AND    INVOICEID  =   '" . $invoiceId . "'
                                                           )
                                                    )
            AND        ISNATIONAL =   1
            AND        ISSTATE    =   1
            AND        ISWEEKEND  =   1

            GROUP BY   INVOICEID";
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
            $totalHoliday = $row['totalHoliday'];
        }
        return $totalHoliday;
    }

    /**
     * Return Setup Tracking Invoice Warning Day
     * @return int $invoiceTrackingWarningDay Setup Tracking Invoice Warning Day
     */
    private function getTrackingInvoiceWarningDay() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $invoiceTrackingWarningDay = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `invoiceTrackingWarningDay`
            FROM `tracking`
            WHERE `companyId`='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  [invoiceTrackingWarningDay]
            FROM [tracking]
            WHERE [companyId]='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  INVOICETRACKINGWARNINGDAY
            FROM TRACKING
            WHERE COMPANYID='" . $this->getCompanyId() . "'";
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
            $invoiceTrackingWarningDay = $row['invoiceTrackingWarningDay'];
        }
        return $invoiceTrackingWarningDay;
    }

    /**
     * Return Setup Tracking Invoice Warning Hour
     * @return int $invoiceTrackingWarningHour Setup Tracking Invoice Warning Hour
     */
    private function getTrackingInvoiceWarningHour() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $invoiceTrackingWarningHour = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `invoiceTrackingWarningHour`
            FROM `tracking`
            WHERE `companyId`='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  `invoiceTrackingWarningHour`
            FROM `tracking `
            WHERE `companyId`='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  INVOICETRACKINGWARNINGHOUR
            FROM    TRACKING
            WHERE  COMPANYID    ='" . $this->getCompanyId() . "'";
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
            $invoiceTrackingWarningHour = $row['invoiceTrackingWarningHour'];
        }
        return $invoiceTrackingWarningHour;
    }

    /**
     * Return Tracking Invoice By Day
     * @param int $invoiceId Invoice Primary Key
     * @return int|bool
     */
    public function getTrackingWarningStatusInvoiceByDay($invoiceId) {
        if ($this->getTotalInvoiceTrackingDay($invoiceId) - $this->getTotalTrackingHolidayInvoice($invoiceId) > $this->getTrackingInvoiceWarningDay()) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Return Tracking Invoice By Day
     * @param int $invoiceId Invoice Primary Key
     * @return int|bool
     */
    public function getTrackingWarningStatusInvoiceByHour($invoiceId) {
        if ($this->getTotalInvoiceTrackingHour($invoiceId) - ($this->getTotalTrackingHolidayInvoice($invoiceId) * 24) > $this->getTrackingInvoiceWarningHour()) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Return Tracking Invoice By Day
     * @param int $invoiceId Invoice Primary Key
     * @return int
     */
    public function getTrackingInvoiceByDay($invoiceId) {
        return (int) $this->getTotalInvoiceTrackingDay($invoiceId) - $this->getTotalTrackingHolidayInvoice($invoiceId);
    }

    /**
     * Return Tracking Invoice By Day
     * @param int $invoiceId Invoice Primary Key
     * @return int
     */
    public function getTrackingInvoiceByHour($invoiceId) {
        return (int) $this->getTotalInvoiceTrackingHour($invoiceId) - ($this->getTotalTrackingHolidayInvoice($invoiceId) * 24);
    }

    /**
     * Return Aging Setup
     * For basic just using one type
     * @return mixed|array
     * */
    private function getAgingSetup() {
        $row = array();
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  *
            FROM    `invoiceaging`
			JOIN		`invoiceagingdetail`
			USING	 (`companyId`,`invoiceAgingId`)
            WHERE 	`invoiceaging`.`companyId`='" . $this->getCompanyId() . "'
			ORDER 	 BY `invoiceAgingDetailSequence` ASC";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  *
            FROM    [invoiceAging]
			JOIN		[invoiceAgingDetail]
			ON		[invoiceAging].[companyId] = [invoiceAgingDetail].[companyId]
			And		[invoiceAging].[invoiceAgingId] = [invoiceAgingDetail].[invoiceAgingId]
            WHERE 			[invoiceAging].[companyId]='" . $this->getCompanyId() . "'
			ORDER 	 BY 	[invoiceAgingDetailSequence] ASC";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  *
            FROM    		INVOICEAGING
			JOIN				INVOICEAGINGDETAIL
			ON				INVOICEAGING.COMPANYID = INVOICEAGINGDETAIL.COMPANYID
			AND				INVOICEAGING.INVOICEAGINGID = INVOICEAGINGDETAIL.INVOICEAGINGID
            WHERE 			INVOICEAGING.COMPANYID='" . $this->getCompanyId() . "'
			ORDER 	 BY 	INVOICEAGINGDETAILSEQUENCE ASC";
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
        }
        return $row;
    }

    /**
     * Return Amount Allocated to INVOICE
     * @param int $invoiceId Invoice Primary Key
     * @return int $amountDue Amount Due
     * */
    private function getAmountDue($invoiceId) {
        $amountDue = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  SUM(`collectionAllocationAmount`) As `amountDue`
            FROM    `collectionallocation`
            WHERE 	`companyId`='" . $this->getCompanyId() . "'
			And	      `invoiceId`	='" . $invoiceId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  SUM([collectionAllocationAmount]) As [amountDue]
            FROM    [collectionAllocation]
            WHERE 	[companyId]='" . $this->getCompanyId() . "'
			And	      [invoiceId]	='" . $invoiceId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  SUM(COLLECTIONALLOCATIONAMOUNT) AS \"amountDue\"
            FROM    COLLECTIONALLOCATION
            WHERE 	COMPANYID`='" . $this->getCompanyId() . "'
			AND	     INVOICEID`	='" . $invoiceId . "'";
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
            $amountDue = $row['amountDue'];
        }
        return $amountDue;
    }

    /**
     * Return Invoice Aging. Min 5.
     * @param int $invoiceId Invoice Primary Key
     * @return int $amountDue Amount Due
     * */
    public function getCalculateInvoiceAging($invoiceId) {
        $row = array();
        $amountDue = $this->getAmountDue($invoiceId);
        // this is an array
        $invoiceAging = $this->getAgingSetup();
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT	DATEDIFF(CURDATE(), invoiceDueDate) AS days_past_due,
						SUM(IF(days_past_due = 0, '" . $amountDue . "', 0)) AS first,
						SUM(IF(days_past_due BETWEEN '" . ($invoiceAging[0] + 1) . "' AND '" . ($invoiceAging[1]) . "' , '" . $amountDue . "', 0)) AS Second,
						SUM(IF(days_past_due BETWEEN '" . ($invoiceAging[1] + 1) . "' AND  '" . ($invoiceAging[2]) . "', '" . $amountDue . "', 0)) AS Third,
						SUM(IF(days_past_due BETWEEN '" . ($invoiceAging[2] + 1) . "' AND  '" . ($invoiceAging[3]) . "' , '" . $amountDue . "', 0)) AS Fourth,
						SUM(IF('" . ($invoiceAging[3] + 1) . "'>  '" . ($invoiceAging[4]) . "', '" . $amountDue . "', 0)) AS `Over`
			FROM invoices
			WHERE	`companyId`='" . $this->getCompanyId() . "'
			And		`invoiceId`='" . $invoiceId . "'
			GROUP BY invoiceId ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT	DATEDIFF(getDate(), invoiceDueDate) AS days_past_due,
						SUM(IF(days_past_due = 0, '" . $amountDue . "', 0)) AS first,
						SUM(IF(days_past_due BETWEEN '" . ($invoiceAging[0] + 1) . "' AND '" . ($invoiceAging[1]) . "' , '" . $amountDue . "', 0)) AS Second,
						SUM(IF(days_past_due BETWEEN '" . ($invoiceAging[1] + 1) . "' AND  '" . ($invoiceAging[2]) . "', '" . $amountDue . "', 0)) AS Third,
						SUM(IF(days_past_due BETWEEN '" . ($invoiceAging[2] + 1) . "' AND  '" . ($invoiceAging[3]) . "' , '" . $amountDue . "', 0)) AS Fourth,
						SUM(IF('" . ($invoiceAging[3] + 1) . "'>  '" . ($invoiceAging[4]) . "', '" . $amountDue . "', 0)) AS `Over`
			FROM invoices
			WHERE	[companyId]='" . $this->getCompanyId() . "'
			And		[invoiceId]='" . $invoiceId . "'
			GROUP BY invoiceId ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT	DATEDIFF(CURDATE(), INVOICEDUEDATE) AS DAYS_PAST_DUE,
						SUM(IF(DAYS_PAST_DUE = 0, '" . $amountDue . "', 0)) AS \"First\",
						SUM(IF(DAYS_PAST_DUE BETWEEN '" . ($invoiceAging[0] + 1) . "' AND '" . ($invoiceAging[1]) . "' , '" . $amountDue . "', 0)) AS \"Second\",
						SUM(IF(DAYS_PAST_DUE BETWEEN '" . ($invoiceAging[1] + 1) . "' AND  '" . ($invoiceAging[2]) . "', '" . $amountDue . "', 0)) AS \'Third\",
						SUM(IF(DAYS_PAST_DUE BETWEEN '" . ($invoiceAging[2] + 1) . "' AND  '" . ($invoiceAging[3]) . "' , '" . $amountDue . "', 0)) AS \"Fourth\",
						SUM(IF('" . ($invoiceAging[3] + 1) . "'>  '" . ($invoiceAging[4]) . "', '" . $amountDue . "', 0)) AS \"Over\"
			FROM INVOICES
			WHERE	`COMPANYID`='" . $this->getCompanyId() . "'
			AND		`INVOICEID`='" . $invoiceId . "'
			GROUP BY INVOICEID ";
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
        }
        return $row;
    }

    /**
     * Return UnitOfMeasurement Default Value
     * @return int
     */
    public function getUnitOfMeasurementDefaultValue() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $unitOfMeasurementId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
         SELECT      `unitOfMeasurementId`
         FROM        	`unitofmeasurement`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      TOP 1 [unitOfMeasurementId],
         FROM        [unitOfMeasurement]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
         SELECT      UNITOFMEASUREMENTID AS \"unitOfMeasurementId\",
         FROM        UNITOFMEASUREMENT  
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
            $unitOfMeasurementId = $row['unitOfMeasurementId'];
        }
        return $unitOfMeasurementId;
    }

    /**
     * Return Allowed Extension
     * @return array|string
     */
    public function getAllowedExtensions() {
        return $this->allowedExtensions;
    }

    /**
     * Set Allowed Extensions
     * @param string $value Value
     * @return \Core\System\Management\Staff\Service\StaffService
     */
    public function setAllowedExtensions($value) {
        $this->allowedExtensions = $value;
        return $this;
    }

    /**
     * Return size Limit Of Staff Upload File
     * @return int
     */
    public function getSizeLimit() {
        return $this->sizeLimit;
    }

    /**
     * Set size Limit Of Staff Upload File
     * @param int $value Value
     * @return \Core\System\Management\Staff\Service\StaffService
     */
    public function setSizeLimit($value) {
        $this->sizeLimit = $value;
        return $this;
    }

    /**
     * Return Upload Path
     * @return string
     */
    public function getUploadPath() {
        return $this->uploadPath;
    }

    /**
     * Return Discount Default Value
     * @return int
     * @throws \Exception
     */
    public function getDiscountDefaultValue() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $discountId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
         SELECT      `discountId`
         FROM        	`discount`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      TOP 1 [discountId],
         FROM        [discount]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
         SELECT      DISCOUNTID AS \"discountId\",
         FROM        DISCOUNT  
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
            $discountId = $row['discountId'];
        }
        return $discountId;
    }

    /**
     * Set Upload Path
     * @param string $value Value
     * @return \Core\System\Management\Staff\Service\StaffService
     */
    public function setUploadPath($value) {
        $this->uploadPath = $value;
        return $this;
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

class InvoiceDetailService extends ConfigClass {

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
     * Model 
     * @var \Core\Financial\AccountReceivable\Invoice\Model\InvoiceDetailMultiModel 
     */
    public $model;

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
     * @return array|string
     * @throws \Exception
     */
    public function getInvoice() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
         SELECT      `invoiceId`,
                     `invoiceDescription`
         FROM        `invoice`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      [invoiceId],
                     [invoiceDescription]
         FROM        [invoice]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
         SELECT      INVOICEID AS \"invoiceId\",
                     INVOICEDESCRIPTION AS \"invoiceDescription\"
         FROM        INVOICE  
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
                    $str.="<option value='" . $row['invoiceId'] . "'>" . $d . ". " . $row['invoiceDescription'] . "</option>";
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
     * Return Invoice Default Value
     * @return int
     * @throws \Exception
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
     * Return Product
     * @return array|string
     * @throws \Exception
     */
    public function getProduct() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
         SELECT      `productId`,
                     `productDescription`
         FROM        `product`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      [productId],
                     [productDescription]
         FROM        [product]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
         SELECT      PRODUCTID AS \"productId\",
                     PRODUCTDESCRIPTION AS \"productDescription\"
         FROM        PRODUCT  
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
                    $str.="<option value='" . $row['productId'] . "'>" . $d . ". " . $row['productDescription'] . "</option>";
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
     * Return Product Default Value
     * @return int
     * @throws \Exception
     */
    public function getProductDefaultValue() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $productId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
         SELECT      `productId`
         FROM        	`product`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      TOP 1 [productId],
         FROM        [product]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
         SELECT      PRODUCTID AS \"productId\",
         FROM        PRODUCT  
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
            $productId = $row['productId'];
        }
        return $productId;
    }

    /**
     * Return UnitOfMeasurement
     * @return array|string
     * @throws \Exception
     */
    public function getUnitOfMeasurement() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
         SELECT      `unitOfMeasurementId`,
                     `unitOfMeasurementDescription`
         FROM        `unitofmeasurement`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      [unitOfMeasurementId],
                     [unitOfMeasurementDescription]
         FROM        [unitOfMeasurement]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
         SELECT      UNITOFMEASUREMENTID AS \"unitOfMeasurementId\",
                     UNITOFMEASUREMENTDESCRIPTION AS \"unitOfMeasurementDescription\"
         FROM        UNITOFMEASUREMENT  
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
                    $str.="<option value='" . $row['unitOfMeasurementId'] . "'>" . $d . ". " . $row['unitOfMeasurementDescription'] . "</option>";
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
     * Return Unit   Of   Measurement Default Value
     * @return int
     * @throws \Exception
     */
    public function getUnitOfMeasurementDefaultValue() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $unitOfMeasurementId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
         SELECT      `unitOfMeasurementId`
         FROM        	`unitofmeasurement`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      TOP 1 [unitOfMeasurementId],
         FROM        [unitOfMeasurement]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
         SELECT      UNITOFMEASUREMENTID AS \"unitOfMeasurementId\",
         FROM        UNITOFMEASUREMENT  
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
            $unitOfMeasurementId = $row['unitOfMeasurementId'];
        }
        return $unitOfMeasurementId;
    }

    /**
     * Return Discount
     * @return array|string
     * @throws \Exception
     */
    public function getDiscount() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
         SELECT      `discountId`,
                     `discountDescription`
         FROM        `discount`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      [discountId],
                     [discountDescription]
         FROM        [discount]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
         SELECT      DISCOUNTID AS \"discountId\",
                     DISCOUNTDESCRIPTION AS \"discountDescription\"
         FROM        DISCOUNT  
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
                    $str.="<option value='" . $row['discountId'] . "'>" . $d . ". " . $row['discountDescription'] . "</option>";
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
     * Return Discount Default Value
     * @return int
     * @throws \Exception
     */
    public function getDiscountDefaultValue() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $discountId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
         SELECT      `discountId`
         FROM        	`discount`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      TOP 1 [discountId],
         FROM        [discount]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
         SELECT      DISCOUNTID AS \"discountId\",
         FROM        DISCOUNT  
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
            $discountId = $row['discountId'];
        }
        return $discountId;
    }

    /**
     * Return Tax
     * @return array|string
     * @throws \Exception
     */
    public function getTax() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
         SELECT      `taxId`,
                     `taxDescription`
         FROM        `tax`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      [taxId],
                     [taxDescription]
         FROM        [tax]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
         SELECT      TAXID AS \"taxId\",
                     TAXDESCRIPTION AS \"taxDescription\"
         FROM        TAX  
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
                    $str.="<option value='" . $row['taxId'] . "'>" . $d . ". " . $row['taxDescription'] . "</option>";
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
     * Return Tax Default Value
     * @return int
     * @throws \Exception
     */
    public function getTaxDefaultValue() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $taxId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
         SELECT      `taxId`
         FROM        	`tax`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      TOP 1 [taxId],
         FROM        [tax]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
         SELECT      TAXID AS \"taxId\",
         FROM        TAX  
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
            $taxId = $row['taxId'];
        }
        return $taxId;
    }

    /** Create
     * @see config::create()
     *  @return void
     * */
    public function create() {
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            try {
                $this->q->fast($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        $this->model->create();
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `invoicedetail` 
            (
                 `companyId`,
                 `invoiceId`,
                 `productId`,
                 `unitOfMeasurementId`,
                 `discountId`,
                 `taxId`,
                 `invoiceDetailLineNumber`,
                 `invoiceDetailQuantity`,
                 `invoiceDetailDescription`,
                 `invoiceDetailPrice`,
                 `invoiceDetailDiscount`,
                 `invoiceDetailTax`,
                 `invoiceDetailTotalPrice`,
                 `isDefault`,
                 `isNew`,
                 `isDraft`,
                 `isUpdate`,
                 `isDelete`,
                 `isActive`,
                 `isApproved`,
                 `isReview`,
                 `isPost`,
                 `executeBy`,
                 `executeTime`
       ) VALUES
( 
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getInvoiceId() . "',
                 '" . $this->model->getProductId() . "',
                 '" . $this->model->getUnitOfMeasurementId() . "',
                 '" . $this->model->getDiscountId() . "',
                 '" . $this->model->getTaxId() . "',
                 '" . $this->model->getInvoiceDetailLineNumber() . "',
                 '" . $this->model->getInvoiceDetailQuantity() . "',
                 '" . $this->model->getInvoiceDetailDescription() . "',
                 '" . $this->model->getInvoiceDetailPrice() . "',
                 '" . $this->model->getInvoiceDetailDiscount() . "',
                 '" . $this->model->getInvoiceDetailTax() . "',
                 '" . $this->model->getInvoiceDetailTotalPrice() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),

( 
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getInvoiceId() . "',
                 '" . $this->model->getProductId() . "',
                 '" . $this->model->getUnitOfMeasurementId() . "',
                 '" . $this->model->getDiscountId() . "',
                 '" . $this->model->getTaxId() . "',
                 '" . $this->model->getInvoiceDetailLineNumber() . "',
                 '" . $this->model->getInvoiceDetailQuantity() . "',
                 '" . $this->model->getInvoiceDetailDescription() . "',
                 '" . $this->model->getInvoiceDetailPrice() . "',
                 '" . $this->model->getInvoiceDetailDiscount() . "',
                 '" . $this->model->getInvoiceDetailTax() . "',
                 '" . $this->model->getInvoiceDetailTotalPrice() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),

( 
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getInvoiceId() . "',
                 '" . $this->model->getProductId() . "',
                 '" . $this->model->getUnitOfMeasurementId() . "',
                 '" . $this->model->getDiscountId() . "',
                 '" . $this->model->getTaxId() . "',
                 '" . $this->model->getInvoiceDetailLineNumber() . "',
                 '" . $this->model->getInvoiceDetailQuantity() . "',
                 '" . $this->model->getInvoiceDetailDescription() . "',
                 '" . $this->model->getInvoiceDetailPrice() . "',
                 '" . $this->model->getInvoiceDetailDiscount() . "',
                 '" . $this->model->getInvoiceDetailTax() . "',
                 '" . $this->model->getInvoiceDetailTotalPrice() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),

( 
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getInvoiceId() . "',
                 '" . $this->model->getProductId() . "',
                 '" . $this->model->getUnitOfMeasurementId() . "',
                 '" . $this->model->getDiscountId() . "',
                 '" . $this->model->getTaxId() . "',
                 '" . $this->model->getInvoiceDetailLineNumber() . "',
                 '" . $this->model->getInvoiceDetailQuantity() . "',
                 '" . $this->model->getInvoiceDetailDescription() . "',
                 '" . $this->model->getInvoiceDetailPrice() . "',
                 '" . $this->model->getInvoiceDetailDiscount() . "',
                 '" . $this->model->getInvoiceDetailTax() . "',
                 '" . $this->model->getInvoiceDetailTotalPrice() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),

( 
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getInvoiceId() . "',
                 '" . $this->model->getProductId() . "',
                 '" . $this->model->getUnitOfMeasurementId() . "',
                 '" . $this->model->getDiscountId() . "',
                 '" . $this->model->getTaxId() . "',
                 '" . $this->model->getInvoiceDetailLineNumber() . "',
                 '" . $this->model->getInvoiceDetailQuantity() . "',
                 '" . $this->model->getInvoiceDetailDescription() . "',
                 '" . $this->model->getInvoiceDetailPrice() . "',
                 '" . $this->model->getInvoiceDetailDiscount() . "',
                 '" . $this->model->getInvoiceDetailTax() . "',
                 '" . $this->model->getInvoiceDetailTotalPrice() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            INSERT INTO [invoiceDetail] 
            (
                 [invoiceDetailId],
                 [companyId],
                 [invoiceId],
                 [productId],
                 [unitOfMeasurementId],
                 [discountId],
                 [taxId],
                 [invoiceDetailLineNumber],
                 [invoiceDetailQuantity],
                 [invoiceDetailDescription],
                 [invoiceDetailPrice],
                 [invoiceDetailDiscount],
                 [invoiceDetailTax],
                 [invoiceDetailTotalPrice],
                 [isDefault],
                 [isNew],
                 [isDraft],
                 [isUpdate],
                 [isDelete],
                 [isActive],
                 [isApproved],
                 [isReview],
                 [isPost],
                 [executeBy],
                 [executeTime]
) VALUES
(
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getInvoiceId(1) . "',
                 '" . $this->model->getProductId(1) . "',
                 '" . $this->model->getUnitOfMeasurementId(1) . "',
                 '" . $this->model->getDiscountId(1) . "',
                 '" . $this->model->getTaxId(1) . "',
                 '" . $this->model->getInvoiceDetailLineNumber(1) . "',
                 '" . $this->model->getInvoiceDetailQuantity(1) . "',
                 '" . $this->model->getInvoiceDetailDescription(1) . "',
                 '" . $this->model->getInvoiceDetailPrice(1) . "',
                 '" . $this->model->getInvoiceDetailDiscount(1) . "',
                 '" . $this->model->getInvoiceDetailTax(1) . "',
                 '" . $this->model->getInvoiceDetailTotalPrice(1) . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),
(
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getInvoiceId(2) . "',
                 '" . $this->model->getProductId(2) . "',
                 '" . $this->model->getUnitOfMeasurementId(2) . "',
                 '" . $this->model->getDiscountId(2) . "',
                 '" . $this->model->getTaxId(2) . "',
                 '" . $this->model->getInvoiceDetailLineNumber(2) . "',
                 '" . $this->model->getInvoiceDetailQuantity(2) . "',
                 '" . $this->model->getInvoiceDetailDescription(2) . "',
                 '" . $this->model->getInvoiceDetailPrice(2) . "',
                 '" . $this->model->getInvoiceDetailDiscount(2) . "',
                 '" . $this->model->getInvoiceDetailTax(2) . "',
                 '" . $this->model->getInvoiceDetailTotalPrice(2) . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),
(
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getInvoiceId(3) . "',
                 '" . $this->model->getProductId(3) . "',
                 '" . $this->model->getUnitOfMeasurementId(3) . "',
                 '" . $this->model->getDiscountId(3) . "',
                 '" . $this->model->getTaxId(3) . "',
                 '" . $this->model->getInvoiceDetailLineNumber(3) . "',
                 '" . $this->model->getInvoiceDetailQuantity(3) . "',
                 '" . $this->model->getInvoiceDetailDescription(3) . "',
                 '" . $this->model->getInvoiceDetailPrice(3) . "',
                 '" . $this->model->getInvoiceDetailDiscount(3) . "',
                 '" . $this->model->getInvoiceDetailTax(3) . "',
                 '" . $this->model->getInvoiceDetailTotalPrice(3) . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),
(
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getInvoiceId(4) . "',
                 '" . $this->model->getProductId(4) . "',
                 '" . $this->model->getUnitOfMeasurementId(4) . "',
                 '" . $this->model->getDiscountId(4) . "',
                 '" . $this->model->getTaxId(4) . "',
                 '" . $this->model->getInvoiceDetailLineNumber(4) . "',
                 '" . $this->model->getInvoiceDetailQuantity(4) . "',
                 '" . $this->model->getInvoiceDetailDescription(4) . "',
                 '" . $this->model->getInvoiceDetailPrice(4) . "',
                 '" . $this->model->getInvoiceDetailDiscount(4) . "',
                 '" . $this->model->getInvoiceDetailTax(4) . "',
                 '" . $this->model->getInvoiceDetailTotalPrice(4) . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),
(
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getInvoiceId(5) . "',
                 '" . $this->model->getProductId(5) . "',
                 '" . $this->model->getUnitOfMeasurementId(5) . "',
                 '" . $this->model->getDiscountId(5) . "',
                 '" . $this->model->getTaxId(5) . "',
                 '" . $this->model->getInvoiceDetailLineNumber(5) . "',
                 '" . $this->model->getInvoiceDetailQuantity(5) . "',
                 '" . $this->model->getInvoiceDetailDescription(5) . "',
                 '" . $this->model->getInvoiceDetailPrice(5) . "',
                 '" . $this->model->getInvoiceDetailDiscount(5) . "',
                 '" . $this->model->getInvoiceDetailTax(5) . "',
                 '" . $this->model->getInvoiceDetailTotalPrice(5) . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
)";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            INSERT INTO INVOICEDETAIL 
            (
                 COMPANYID,
                 INVOICEID,
                 PRODUCTID,
                 UNITOFMEASUREMENTID,
                 DISCOUNTID,
                 TAXID,
                 INVOICEDETAILLINENUMBER,
                 INVOICEDETAILQUANTITY,
                 INVOICEDETAILDESCRIPTION,
                 INVOICEDETAILPRICE,
                 INVOICEDETAILDISCOUNT,
                 INVOICEDETAILTAX,
                 INVOICEDETAILTOTALPRICE,
                 ISDEFAULT,
                 ISNEW,
                 ISDRAFT,
                 ISUPDATE,
                 ISDELETE,
                 ISACTIVE,
                 ISAPPROVED,
                 ISREVIEW,
                 ISPOST,
                 EXECUTEBY,
                 EXECUTETIME
            ) VALUES 
(
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getInvoiceId(1) . "',
                 '" . $this->model->getProductId(1) . "',
                 '" . $this->model->getUnitOfMeasurementId(1) . "',
                 '" . $this->model->getDiscountId(1) . "',
                 '" . $this->model->getTaxId(1) . "',
                 '" . $this->model->getInvoiceDetailLineNumber(1) . "',
                 '" . $this->model->getInvoiceDetailQuantity(1) . "',
                 '" . $this->model->getInvoiceDetailDescription(1) . "',
                 '" . $this->model->getInvoiceDetailPrice(1) . "',
                 '" . $this->model->getInvoiceDetailDiscount(1) . "',
                 '" . $this->model->getInvoiceDetailTax(1) . "',
                 '" . $this->model->getInvoiceDetailTotalPrice(1) . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),(
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getInvoiceId(2) . "',
                 '" . $this->model->getProductId(2) . "',
                 '" . $this->model->getUnitOfMeasurementId(2) . "',
                 '" . $this->model->getDiscountId(2) . "',
                 '" . $this->model->getTaxId(2) . "',
                 '" . $this->model->getInvoiceDetailLineNumber(2) . "',
                 '" . $this->model->getInvoiceDetailQuantity(2) . "',
                 '" . $this->model->getInvoiceDetailDescription(2) . "',
                 '" . $this->model->getInvoiceDetailPrice(2) . "',
                 '" . $this->model->getInvoiceDetailDiscount(2) . "',
                 '" . $this->model->getInvoiceDetailTax(2) . "',
                 '" . $this->model->getInvoiceDetailTotalPrice(2) . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),(
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getInvoiceId(3) . "',
                 '" . $this->model->getProductId(3) . "',
                 '" . $this->model->getUnitOfMeasurementId(3) . "',
                 '" . $this->model->getDiscountId(3) . "',
                 '" . $this->model->getTaxId(3) . "',
                 '" . $this->model->getInvoiceDetailLineNumber(3) . "',
                 '" . $this->model->getInvoiceDetailQuantity(3) . "',
                 '" . $this->model->getInvoiceDetailDescription(3) . "',
                 '" . $this->model->getInvoiceDetailPrice(3) . "',
                 '" . $this->model->getInvoiceDetailDiscount(3) . "',
                 '" . $this->model->getInvoiceDetailTax(3) . "',
                 '" . $this->model->getInvoiceDetailTotalPrice(3) . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),(
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getInvoiceId(4) . "',
                 '" . $this->model->getProductId(4) . "',
                 '" . $this->model->getUnitOfMeasurementId(4) . "',
                 '" . $this->model->getDiscountId(4) . "',
                 '" . $this->model->getTaxId(4) . "',
                 '" . $this->model->getInvoiceDetailLineNumber(4) . "',
                 '" . $this->model->getInvoiceDetailQuantity(4) . "',
                 '" . $this->model->getInvoiceDetailDescription(4) . "',
                 '" . $this->model->getInvoiceDetailPrice(4) . "',
                 '" . $this->model->getInvoiceDetailDiscount(4) . "',
                 '" . $this->model->getInvoiceDetailTax(4) . "',
                 '" . $this->model->getInvoiceDetailTotalPrice(4) . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),(
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getInvoiceId(5) . "',
                 '" . $this->model->getProductId(5) . "',
                 '" . $this->model->getUnitOfMeasurementId(5) . "',
                 '" . $this->model->getDiscountId(5) . "',
                 '" . $this->model->getTaxId(5) . "',
                 '" . $this->model->getInvoiceDetailLineNumber(5) . "',
                 '" . $this->model->getInvoiceDetailQuantity(5) . "',
                 '" . $this->model->getInvoiceDetailDescription(5) . "',
                 '" . $this->model->getInvoiceDetailPrice(5) . "',
                 '" . $this->model->getInvoiceDetailDiscount(5) . "',
                 '" . $this->model->getInvoiceDetailTax(5) . "',
                 '" . $this->model->getInvoiceDetailTotalPrice(5) . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
";
        }
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Read
     * @see config::read()
     * @return void
     */
    public function read() {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "
       SELECT                    `invoicedetail`.`invoiceDetailId`,
                    `company`.`companyDescription`,
                    `invoicedetail`.`companyId`,
                    `invoice`.`invoiceDescription`,
                    `invoicedetail`.`invoiceId`,
                    `product`.`productDescription`,
                    `invoicedetail`.`productId`,
                    `unitofmeasurement`.`unitOfMeasurementDescription`,
                    `invoicedetail`.`unitOfMeasurementId`,
                    `discount`.`discountDescription`,
                    `invoicedetail`.`discountId`,
                    `tax`.`taxDescription`,
                    `invoicedetail`.`taxId`,
                    `invoicedetail`.`invoiceDetailLineNumber`,
                    `invoicedetail`.`invoiceDetailQuantity`,
                    `invoicedetail`.`invoiceDetailDescription`,
                    `invoicedetail`.`invoiceDetailPrice`,
                    `invoicedetail`.`invoiceDetailDiscount`,
                    `invoicedetail`.`invoiceDetailTax`,
                    `invoicedetail`.`invoiceDetailTotalPrice`,
                    `invoicedetail`.`isDefault`,
                    `invoicedetail`.`isNew`,
                    `invoicedetail`.`isDraft`,
                    `invoicedetail`.`isUpdate`,
                    `invoicedetail`.`isDelete`,
                    `invoicedetail`.`isActive`,
                    `invoicedetail`.`isApproved`,
                    `invoicedetail`.`isReview`,
                    `invoicedetail`.`isPost`,
                    `invoicedetail`.`executeBy`,
                    `invoicedetail`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `invoicedetail`
		  JOIN      `staff`
		  ON        `invoicedetail`.`executeBy` = `staff`.`staffId`
		  WHERE    `companyId`= " . $this->companyId() . "' 
		  AND    `invoiceDetailLineNumber` = '" . $this->model->getInvoiceDetailLineNumber(25) . "'";
        } else if ($this->getVendor() == self::MSSQL) {

            $sql = "
		  SELECT                    [invoiceDetail].[invoiceDetailId],
                    [company].[companyDescription],
                    [invoiceDetail].[companyId],
                    [invoice].[invoiceDescription],
                    [invoiceDetail].[invoiceId],
                    [product].[productDescription],
                    [invoiceDetail].[productId],
                    [unitOfMeasurement].[unitOfMeasurementDescription],
                    [invoiceDetail].[unitOfMeasurementId],
                    [discount].[discountDescription],
                    [invoiceDetail].[discountId],
                    [tax].[taxDescription],
                    [invoiceDetail].[taxId],
                    [invoiceDetail].[invoiceDetailLineNumber],
                    [invoiceDetail].[invoiceDetailQuantity],
                    [invoiceDetail].[invoiceDetailDescription],
                    [invoiceDetail].[invoiceDetailPrice],
                    [invoiceDetail].[invoiceDetailDiscount],
                    [invoiceDetail].[invoiceDetailTax],
                    [invoiceDetail].[invoiceDetailTotalPrice],
                    [invoiceDetail].[isDefault],
                    [invoiceDetail].[isNew],
                    [invoiceDetail].[isDraft],
                    [invoiceDetail].[isUpdate],
                    [invoiceDetail].[isDelete],
                    [invoiceDetail].[isActive],
                    [invoiceDetail].[isApproved],
                    [invoiceDetail].[isReview],
                    [invoiceDetail].[isPost],
                    [invoiceDetail].[executeBy],
                    [invoiceDetail].[executeTime],
                    [staff].[staffName] 
		  FROM 	[invoiceDetail]
		  JOIN	[staff]
		  ON	[invoiceDetail].[executeBy] = [staff].[staffId]
		  WHERE    [companyId]='" . $this->getCompanyId() . "'  AND [invoiceDetailLineNumber] = '" . $this->model->getInvoiceDetailLineNumber(0, 'single') . "' ";
            if ($this->model->getInvoiceDetailId(0, 'single')) {
                $sql .= " AND [invoiceDetail].[" . $this->model->getPrimaryKeyName() . "]		=	'" . $this->model->getInvoiceDetailId(0, 'single') . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {

            $sql = "
		  SELECT                    INVOICEDETAIL.INVOICEDETAILID AS \"invoiceDetailId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    INVOICEDETAIL.COMPANYID AS \"companyId\",
                    INVOICE.INVOICEDESCRIPTION AS  \"invoiceDescription\",
                    INVOICEDETAIL.INVOICEID AS \"invoiceId\",
                    PRODUCT.PRODUCTDESCRIPTION AS  \"productDescription\",
                    INVOICEDETAIL.PRODUCTID AS \"productId\",
                    UNITOFMEASUREMENT.UNITOFMEASUREMENTDESCRIPTION AS  \"unitOfMeasurementDescription\",
                    INVOICEDETAIL.UNITOFMEASUREMENTID AS \"unitOfMeasurementId\",
                    DISCOUNT.DISCOUNTDESCRIPTION AS  \"discountDescription\",
                    INVOICEDETAIL.DISCOUNTID AS \"discountId\",
                    TAX.TAXDESCRIPTION AS  \"taxDescription\",
                    INVOICEDETAIL.TAXID AS \"taxId\",
                    INVOICEDETAIL.INVOICEDETAILLINENUMBER AS \"invoiceDetailLineNumber\",
                    INVOICEDETAIL.INVOICEDETAILQUANTITY AS \"invoiceDetailQuantity\",
                    INVOICEDETAIL.INVOICEDETAILDESCRIPTION AS \"invoiceDetailDescription\",
                    INVOICEDETAIL.INVOICEDETAILPRICE AS \"invoiceDetailPrice\",
                    INVOICEDETAIL.INVOICEDETAILDISCOUNT AS \"invoiceDetailDiscount\",
                    INVOICEDETAIL.INVOICEDETAILTAX AS \"invoiceDetailTax\",
                    INVOICEDETAIL.INVOICEDETAILTOTALPRICE AS \"invoiceDetailTotalPrice\",
                    INVOICEDETAIL.ISDEFAULT AS \"isDefault\",
                    INVOICEDETAIL.ISNEW AS \"isNew\",
                    INVOICEDETAIL.ISDRAFT AS \"isDraft\",
                    INVOICEDETAIL.ISUPDATE AS \"isUpdate\",
                    INVOICEDETAIL.ISDELETE AS \"isDelete\",
                    INVOICEDETAIL.ISACTIVE AS \"isActive\",
                    INVOICEDETAIL.ISAPPROVED AS \"isApproved\",
                    INVOICEDETAIL.ISREVIEW AS \"isReview\",
                    INVOICEDETAIL.ISPOST AS \"isPost\",
                    INVOICEDETAIL.EXECUTEBY AS \"executeBy\",
                    INVOICEDETAIL.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\" 
		  FROM 	INVOICEDETAIL 
		  JOIN	STAFF 
		  ON	INVOICEDETAIL.EXECUTEBY = STAFF.STAFFID 
          WHERE     COMPANYID='" . $this->getCompanyId() . "'  AND INVOICEDETAILLINENUMBER = '" . $this->model->getInvoiceDetailLineNumber(25) . "' ";
        }
        try {
            $this->q->read($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $items = array();
        $i = 1;
        while (($row = $this->q->fetchAssoc()) == TRUE) {
            $items [] = $row;
            $i++;
        }
        return $items;
    }

    /**
     * Update
     * @see config::update()
     * @return void
     */
    public function update() {
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            try {
                $this->q->fast($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        $this->q->start();
        $this->model->update();
        // before updating check the id exist or not . if exist continue to update else warning the user 
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
               UPDATE `invoicedetail`
               SET                        `productId` = '" . $this->model->getProductId(1) . "',
                       `unitOfMeasurementId` = '" . $this->model->getUnitOfMeasurementId(1) . "',
                       `discountId` = '" . $this->model->getDiscountId(1) . "',
                       `taxId` = '" . $this->model->getTaxId(1) . "',
                       `invoiceDetailLineNumber` = '" . $this->model->getInvoiceDetailLineNumber(1) . "',
                       `invoiceDetailQuantity` = '" . $this->model->getInvoiceDetailQuantity(1) . "',
                       `invoiceDetailDescription` = '" . $this->model->getInvoiceDetailDescription(1) . "',
                       `invoiceDetailPrice` = '" . $this->model->getInvoiceDetailPrice(1) . "',
                       `invoiceDetailDiscount` = '" . $this->model->getInvoiceDetailDiscount(1) . "',
                       `invoiceDetailTax` = '" . $this->model->getInvoiceDetailTax(1) . "',
                       `invoiceDetailTotalPrice` = '" . $this->model->getInvoiceDetailTotalPrice(1) . "',
                       `isDefault` = '" . $this->model->getIsDefault('0', 'single') . "',
                       `isNew` = '" . $this->model->getIsNew('0', 'single') . "',
                       `isDraft` = '" . $this->model->getIsDraft('0', 'single') . "',
                       `isUpdate` = '" . $this->model->getIsUpdate('0', 'single') . "',
                       `isDelete` = '" . $this->model->getIsDelete('0', 'single') . "',
                       `isActive` = '" . $this->model->getIsActive('0', 'single') . "',
                       `isApproved` = '" . $this->model->getIsApproved('0', 'single') . "',
                       `isReview` = '" . $this->model->getIsReview('0', 'single') . "',
                       `isPost` = '" . $this->model->getIsPost('0', 'single') . "',
                       `executeBy` = '" . $this->model->getExecuteBy() . "',
                       `executeTime` = " . $this->model->getExecuteTime() . "
               WHERE    `companyId` ='" . $this->getCompanyId() . "'
AND 	   `invoiceDetailLineNumber` = '" . $this->model->getInvoiceDetailLineNumber(1) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                UPDATE [invoiceDetail] SET 
                       [invoiceId] = '" . $this->model->getInvoiceId(1) . "',
                       [productId] = '" . $this->model->getProductId(1) . "',
                       [unitOfMeasurementId] = '" . $this->model->getUnitOfMeasurementId(1) . "',
                       [discountId] = '" . $this->model->getDiscountId(1) . "',
                       [taxId] = '" . $this->model->getTaxId(1) . "',
                       [invoiceDetailLineNumber] = '" . $this->model->getInvoiceDetailLineNumber(1) . "',
                       [invoiceDetailQuantity] = '" . $this->model->getInvoiceDetailQuantity(1) . "',
                       [invoiceDetailDescription] = '" . $this->model->getInvoiceDetailDescription(1) . "',
                       [invoiceDetailPrice] = '" . $this->model->getInvoiceDetailPrice(1) . "',
                       [invoiceDetailDiscount] = '" . $this->model->getInvoiceDetailDiscount(1) . "',
                       [invoiceDetailTax] = '" . $this->model->getInvoiceDetailTax(1) . "',
                       [invoiceDetailTotalPrice] = '" . $this->model->getInvoiceDetailTotalPrice(1) . "',
                       [isDefault] = '" . $this->model->getIsDefault(0, 'single') . "',
                       [isNew] = '" . $this->model->getIsNew(0, 'single') . "',
                       [isDraft] = '" . $this->model->getIsDraft(0, 'single') . "',
                       [isUpdate] = '" . $this->model->getIsUpdate(0, 'single') . "',
                       [isDelete] = '" . $this->model->getIsDelete(0, 'single') . "',
                       [isActive] = '" . $this->model->getIsActive(0, 'single') . "',
                       [isApproved] = '" . $this->model->getIsApproved(0, 'single') . "',
                       [isReview] = '" . $this->model->getIsReview(0, 'single') . "',
                       [isPost] = '" . $this->model->getIsPost(0, 'single') . "',
                       [executeBy] = '" . $this->model->getExecuteBy() . "',
                       [executeTime] = " . $this->model->getExecuteTime() . "
                WHERE  [companyId]='" . $this->getCompanyId() . "'  AND [invoiceDetailLineNumber] = '" . $this->model->getInvoiceDetailLineNumber(1) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                UPDATE INVOICEDETAIL SET
                        INVOICEID = '" . $this->model->getInvoiceId(1) . "',
                       PRODUCTID = '" . $this->model->getProductId(1) . "',
                       UNITOFMEASUREMENTID = '" . $this->model->getUnitOfMeasurementId(1) . "',
                       DISCOUNTID = '" . $this->model->getDiscountId(1) . "',
                       TAXID = '" . $this->model->getTaxId(1) . "',
                       INVOICEDETAILLINENUMBER = '" . $this->model->getInvoiceDetailLineNumber(1) . "',
                       INVOICEDETAILQUANTITY = '" . $this->model->getInvoiceDetailQuantity(1) . "',
                       INVOICEDETAILDESCRIPTION = '" . $this->model->getInvoiceDetailDescription(1) . "',
                       INVOICEDETAILPRICE = '" . $this->model->getInvoiceDetailPrice(1) . "',
                       INVOICEDETAILDISCOUNT = '" . $this->model->getInvoiceDetailDiscount(1) . "',
                       INVOICEDETAILTAX = '" . $this->model->getInvoiceDetailTax(1) . "',
                       INVOICEDETAILTOTALPRICE = '" . $this->model->getInvoiceDetailTotalPrice(1) . "',
                       ISDEFAULT = '" . $this->model->getIsDefault(0, 'single') . "',
                       ISNEW = '" . $this->model->getIsNew(0, 'single') . "',
                       ISDRAFT = '" . $this->model->getIsDraft(0, 'single') . "',
                       ISUPDATE = '" . $this->model->getIsUpdate(0, 'single') . "',
                       ISDELETE = '" . $this->model->getIsDelete(0, 'single') . "',
                       ISACTIVE = '" . $this->model->getIsActive(0, 'single') . "',
                       ISAPPROVED = '" . $this->model->getIsApproved(0, 'single') . "',
                       ISREVIEW = '" . $this->model->getIsReview(0, 'single') . "',
                       ISPOST = '" . $this->model->getIsPost(0, 'single') . "',
                       EXECUTEBY = '" . $this->model->getExecuteBy() . "',
                       EXECUTETIME = " . $this->model->getExecuteTime() . "
              WHERE COMPANYID='" . $this->getCompanyId() . "'  AND INVOICEDETAILLINENUMBER = '" . $this->model->getInvoiceDetailLineNumber(1) . "' ";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
               UPDATE `invoicedetail`
               SET                        `productId` = '" . $this->model->getProductId(2) . "',
                       `unitOfMeasurementId` = '" . $this->model->getUnitOfMeasurementId(2) . "',
                       `discountId` = '" . $this->model->getDiscountId(2) . "',
                       `taxId` = '" . $this->model->getTaxId(2) . "',
                       `invoiceDetailLineNumber` = '" . $this->model->getInvoiceDetailLineNumber(2) . "',
                       `invoiceDetailQuantity` = '" . $this->model->getInvoiceDetailQuantity(2) . "',
                       `invoiceDetailDescription` = '" . $this->model->getInvoiceDetailDescription(2) . "',
                       `invoiceDetailPrice` = '" . $this->model->getInvoiceDetailPrice(2) . "',
                       `invoiceDetailDiscount` = '" . $this->model->getInvoiceDetailDiscount(2) . "',
                       `invoiceDetailTax` = '" . $this->model->getInvoiceDetailTax(2) . "',
                       `invoiceDetailTotalPrice` = '" . $this->model->getInvoiceDetailTotalPrice(2) . "',
                       `isDefault` = '" . $this->model->getIsDefault('0', 'single') . "',
                       `isNew` = '" . $this->model->getIsNew('0', 'single') . "',
                       `isDraft` = '" . $this->model->getIsDraft('0', 'single') . "',
                       `isUpdate` = '" . $this->model->getIsUpdate('0', 'single') . "',
                       `isDelete` = '" . $this->model->getIsDelete('0', 'single') . "',
                       `isActive` = '" . $this->model->getIsActive('0', 'single') . "',
                       `isApproved` = '" . $this->model->getIsApproved('0', 'single') . "',
                       `isReview` = '" . $this->model->getIsReview('0', 'single') . "',
                       `isPost` = '" . $this->model->getIsPost('0', 'single') . "',
                       `executeBy` = '" . $this->model->getExecuteBy() . "',
                       `executeTime` = " . $this->model->getExecuteTime() . "
               WHERE    `companyId` ='" . $this->getCompanyId() . "'
AND 	   `invoiceDetailLineNumber` = '" . $this->model->getInvoiceDetailLineNumber(2) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                UPDATE [invoiceDetail] SET 
                       [invoiceId] = '" . $this->model->getInvoiceId(2) . "',
                       [productId] = '" . $this->model->getProductId(2) . "',
                       [unitOfMeasurementId] = '" . $this->model->getUnitOfMeasurementId(2) . "',
                       [discountId] = '" . $this->model->getDiscountId(2) . "',
                       [taxId] = '" . $this->model->getTaxId(2) . "',
                       [invoiceDetailLineNumber] = '" . $this->model->getInvoiceDetailLineNumber(2) . "',
                       [invoiceDetailQuantity] = '" . $this->model->getInvoiceDetailQuantity(2) . "',
                       [invoiceDetailDescription] = '" . $this->model->getInvoiceDetailDescription(2) . "',
                       [invoiceDetailPrice] = '" . $this->model->getInvoiceDetailPrice(2) . "',
                       [invoiceDetailDiscount] = '" . $this->model->getInvoiceDetailDiscount(2) . "',
                       [invoiceDetailTax] = '" . $this->model->getInvoiceDetailTax(2) . "',
                       [invoiceDetailTotalPrice] = '" . $this->model->getInvoiceDetailTotalPrice(2) . "',
                       [isDefault] = '" . $this->model->getIsDefault(0, 'single') . "',
                       [isNew] = '" . $this->model->getIsNew(0, 'single') . "',
                       [isDraft] = '" . $this->model->getIsDraft(0, 'single') . "',
                       [isUpdate] = '" . $this->model->getIsUpdate(0, 'single') . "',
                       [isDelete] = '" . $this->model->getIsDelete(0, 'single') . "',
                       [isActive] = '" . $this->model->getIsActive(0, 'single') . "',
                       [isApproved] = '" . $this->model->getIsApproved(0, 'single') . "',
                       [isReview] = '" . $this->model->getIsReview(0, 'single') . "',
                       [isPost] = '" . $this->model->getIsPost(0, 'single') . "',
                       [executeBy] = '" . $this->model->getExecuteBy() . "',
                       [executeTime] = " . $this->model->getExecuteTime() . "
                WHERE  [companyId]='" . $this->getCompanyId() . "'  AND [invoiceDetailLineNumber] = '" . $this->model->getInvoiceDetailLineNumber(2) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                UPDATE INVOICEDETAIL SET
                        INVOICEID = '" . $this->model->getInvoiceId(2) . "',
                       PRODUCTID = '" . $this->model->getProductId(2) . "',
                       UNITOFMEASUREMENTID = '" . $this->model->getUnitOfMeasurementId(2) . "',
                       DISCOUNTID = '" . $this->model->getDiscountId(2) . "',
                       TAXID = '" . $this->model->getTaxId(2) . "',
                       INVOICEDETAILLINENUMBER = '" . $this->model->getInvoiceDetailLineNumber(2) . "',
                       INVOICEDETAILQUANTITY = '" . $this->model->getInvoiceDetailQuantity(2) . "',
                       INVOICEDETAILDESCRIPTION = '" . $this->model->getInvoiceDetailDescription(2) . "',
                       INVOICEDETAILPRICE = '" . $this->model->getInvoiceDetailPrice(2) . "',
                       INVOICEDETAILDISCOUNT = '" . $this->model->getInvoiceDetailDiscount(2) . "',
                       INVOICEDETAILTAX = '" . $this->model->getInvoiceDetailTax(2) . "',
                       INVOICEDETAILTOTALPRICE = '" . $this->model->getInvoiceDetailTotalPrice(2) . "',
                       ISDEFAULT = '" . $this->model->getIsDefault(0, 'single') . "',
                       ISNEW = '" . $this->model->getIsNew(0, 'single') . "',
                       ISDRAFT = '" . $this->model->getIsDraft(0, 'single') . "',
                       ISUPDATE = '" . $this->model->getIsUpdate(0, 'single') . "',
                       ISDELETE = '" . $this->model->getIsDelete(0, 'single') . "',
                       ISACTIVE = '" . $this->model->getIsActive(0, 'single') . "',
                       ISAPPROVED = '" . $this->model->getIsApproved(0, 'single') . "',
                       ISREVIEW = '" . $this->model->getIsReview(0, 'single') . "',
                       ISPOST = '" . $this->model->getIsPost(0, 'single') . "',
                       EXECUTEBY = '" . $this->model->getExecuteBy() . "',
                       EXECUTETIME = " . $this->model->getExecuteTime() . "
              WHERE COMPANYID='" . $this->getCompanyId() . "'  AND INVOICEDETAILLINENUMBER = '" . $this->model->getInvoiceDetailLineNumber(2) . "' ";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
               UPDATE `invoicedetail`
               SET                        `productId` = '" . $this->model->getProductId(3) . "',
                       `unitOfMeasurementId` = '" . $this->model->getUnitOfMeasurementId(3) . "',
                       `discountId` = '" . $this->model->getDiscountId(3) . "',
                       `taxId` = '" . $this->model->getTaxId(3) . "',
                       `invoiceDetailLineNumber` = '" . $this->model->getInvoiceDetailLineNumber(3) . "',
                       `invoiceDetailQuantity` = '" . $this->model->getInvoiceDetailQuantity(3) . "',
                       `invoiceDetailDescription` = '" . $this->model->getInvoiceDetailDescription(3) . "',
                       `invoiceDetailPrice` = '" . $this->model->getInvoiceDetailPrice(3) . "',
                       `invoiceDetailDiscount` = '" . $this->model->getInvoiceDetailDiscount(3) . "',
                       `invoiceDetailTax` = '" . $this->model->getInvoiceDetailTax(3) . "',
                       `invoiceDetailTotalPrice` = '" . $this->model->getInvoiceDetailTotalPrice(3) . "',
                       `isDefault` = '" . $this->model->getIsDefault('0', 'single') . "',
                       `isNew` = '" . $this->model->getIsNew('0', 'single') . "',
                       `isDraft` = '" . $this->model->getIsDraft('0', 'single') . "',
                       `isUpdate` = '" . $this->model->getIsUpdate('0', 'single') . "',
                       `isDelete` = '" . $this->model->getIsDelete('0', 'single') . "',
                       `isActive` = '" . $this->model->getIsActive('0', 'single') . "',
                       `isApproved` = '" . $this->model->getIsApproved('0', 'single') . "',
                       `isReview` = '" . $this->model->getIsReview('0', 'single') . "',
                       `isPost` = '" . $this->model->getIsPost('0', 'single') . "',
                       `executeBy` = '" . $this->model->getExecuteBy() . "',
                       `executeTime` = " . $this->model->getExecuteTime() . "
               WHERE    `companyId` ='" . $this->getCompanyId() . "'
AND 	   `invoiceDetailLineNumber` = '" . $this->model->getInvoiceDetailLineNumber(3) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                UPDATE [invoiceDetail] SET 
                       [invoiceId] = '" . $this->model->getInvoiceId(3) . "',
                       [productId] = '" . $this->model->getProductId(3) . "',
                       [unitOfMeasurementId] = '" . $this->model->getUnitOfMeasurementId(3) . "',
                       [discountId] = '" . $this->model->getDiscountId(3) . "',
                       [taxId] = '" . $this->model->getTaxId(3) . "',
                       [invoiceDetailLineNumber] = '" . $this->model->getInvoiceDetailLineNumber(3) . "',
                       [invoiceDetailQuantity] = '" . $this->model->getInvoiceDetailQuantity(3) . "',
                       [invoiceDetailDescription] = '" . $this->model->getInvoiceDetailDescription(3) . "',
                       [invoiceDetailPrice] = '" . $this->model->getInvoiceDetailPrice(3) . "',
                       [invoiceDetailDiscount] = '" . $this->model->getInvoiceDetailDiscount(3) . "',
                       [invoiceDetailTax] = '" . $this->model->getInvoiceDetailTax(3) . "',
                       [invoiceDetailTotalPrice] = '" . $this->model->getInvoiceDetailTotalPrice(3) . "',
                       [isDefault] = '" . $this->model->getIsDefault(0, 'single') . "',
                       [isNew] = '" . $this->model->getIsNew(0, 'single') . "',
                       [isDraft] = '" . $this->model->getIsDraft(0, 'single') . "',
                       [isUpdate] = '" . $this->model->getIsUpdate(0, 'single') . "',
                       [isDelete] = '" . $this->model->getIsDelete(0, 'single') . "',
                       [isActive] = '" . $this->model->getIsActive(0, 'single') . "',
                       [isApproved] = '" . $this->model->getIsApproved(0, 'single') . "',
                       [isReview] = '" . $this->model->getIsReview(0, 'single') . "',
                       [isPost] = '" . $this->model->getIsPost(0, 'single') . "',
                       [executeBy] = '" . $this->model->getExecuteBy() . "',
                       [executeTime] = " . $this->model->getExecuteTime() . "
                WHERE  [companyId]='" . $this->getCompanyId() . "'  AND [invoiceDetailLineNumber] = '" . $this->model->getInvoiceDetailLineNumber(3) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                UPDATE INVOICEDETAIL SET
                        INVOICEID = '" . $this->model->getInvoiceId(3) . "',
                       PRODUCTID = '" . $this->model->getProductId(3) . "',
                       UNITOFMEASUREMENTID = '" . $this->model->getUnitOfMeasurementId(3) . "',
                       DISCOUNTID = '" . $this->model->getDiscountId(3) . "',
                       TAXID = '" . $this->model->getTaxId(3) . "',
                       INVOICEDETAILLINENUMBER = '" . $this->model->getInvoiceDetailLineNumber(3) . "',
                       INVOICEDETAILQUANTITY = '" . $this->model->getInvoiceDetailQuantity(3) . "',
                       INVOICEDETAILDESCRIPTION = '" . $this->model->getInvoiceDetailDescription(3) . "',
                       INVOICEDETAILPRICE = '" . $this->model->getInvoiceDetailPrice(3) . "',
                       INVOICEDETAILDISCOUNT = '" . $this->model->getInvoiceDetailDiscount(3) . "',
                       INVOICEDETAILTAX = '" . $this->model->getInvoiceDetailTax(3) . "',
                       INVOICEDETAILTOTALPRICE = '" . $this->model->getInvoiceDetailTotalPrice(3) . "',
                       ISDEFAULT = '" . $this->model->getIsDefault(0, 'single') . "',
                       ISNEW = '" . $this->model->getIsNew(0, 'single') . "',
                       ISDRAFT = '" . $this->model->getIsDraft(0, 'single') . "',
                       ISUPDATE = '" . $this->model->getIsUpdate(0, 'single') . "',
                       ISDELETE = '" . $this->model->getIsDelete(0, 'single') . "',
                       ISACTIVE = '" . $this->model->getIsActive(0, 'single') . "',
                       ISAPPROVED = '" . $this->model->getIsApproved(0, 'single') . "',
                       ISREVIEW = '" . $this->model->getIsReview(0, 'single') . "',
                       ISPOST = '" . $this->model->getIsPost(0, 'single') . "',
                       EXECUTEBY = '" . $this->model->getExecuteBy() . "',
                       EXECUTETIME = " . $this->model->getExecuteTime() . "
              WHERE COMPANYID='" . $this->getCompanyId() . "'  AND INVOICEDETAILLINENUMBER = '" . $this->model->getInvoiceDetailLineNumber(3) . "' ";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
               UPDATE `invoicedetail`
               SET                        `productId` = '" . $this->model->getProductId(4) . "',
                       `unitOfMeasurementId` = '" . $this->model->getUnitOfMeasurementId(4) . "',
                       `discountId` = '" . $this->model->getDiscountId(4) . "',
                       `taxId` = '" . $this->model->getTaxId(4) . "',
                       `invoiceDetailLineNumber` = '" . $this->model->getInvoiceDetailLineNumber(4) . "',
                       `invoiceDetailQuantity` = '" . $this->model->getInvoiceDetailQuantity(4) . "',
                       `invoiceDetailDescription` = '" . $this->model->getInvoiceDetailDescription(4) . "',
                       `invoiceDetailPrice` = '" . $this->model->getInvoiceDetailPrice(4) . "',
                       `invoiceDetailDiscount` = '" . $this->model->getInvoiceDetailDiscount(4) . "',
                       `invoiceDetailTax` = '" . $this->model->getInvoiceDetailTax(4) . "',
                       `invoiceDetailTotalPrice` = '" . $this->model->getInvoiceDetailTotalPrice(4) . "',
                       `isDefault` = '" . $this->model->getIsDefault('0', 'single') . "',
                       `isNew` = '" . $this->model->getIsNew('0', 'single') . "',
                       `isDraft` = '" . $this->model->getIsDraft('0', 'single') . "',
                       `isUpdate` = '" . $this->model->getIsUpdate('0', 'single') . "',
                       `isDelete` = '" . $this->model->getIsDelete('0', 'single') . "',
                       `isActive` = '" . $this->model->getIsActive('0', 'single') . "',
                       `isApproved` = '" . $this->model->getIsApproved('0', 'single') . "',
                       `isReview` = '" . $this->model->getIsReview('0', 'single') . "',
                       `isPost` = '" . $this->model->getIsPost('0', 'single') . "',
                       `executeBy` = '" . $this->model->getExecuteBy() . "',
                       `executeTime` = " . $this->model->getExecuteTime() . "
               WHERE    `companyId` ='" . $this->getCompanyId() . "'
AND 	   `invoiceDetailLineNumber` = '" . $this->model->getInvoiceDetailLineNumber(4) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                UPDATE [invoiceDetail] SET 
                       [invoiceId] = '" . $this->model->getInvoiceId(4) . "',
                       [productId] = '" . $this->model->getProductId(4) . "',
                       [unitOfMeasurementId] = '" . $this->model->getUnitOfMeasurementId(4) . "',
                       [discountId] = '" . $this->model->getDiscountId(4) . "',
                       [taxId] = '" . $this->model->getTaxId(4) . "',
                       [invoiceDetailLineNumber] = '" . $this->model->getInvoiceDetailLineNumber(4) . "',
                       [invoiceDetailQuantity] = '" . $this->model->getInvoiceDetailQuantity(4) . "',
                       [invoiceDetailDescription] = '" . $this->model->getInvoiceDetailDescription(4) . "',
                       [invoiceDetailPrice] = '" . $this->model->getInvoiceDetailPrice(4) . "',
                       [invoiceDetailDiscount] = '" . $this->model->getInvoiceDetailDiscount(4) . "',
                       [invoiceDetailTax] = '" . $this->model->getInvoiceDetailTax(4) . "',
                       [invoiceDetailTotalPrice] = '" . $this->model->getInvoiceDetailTotalPrice(4) . "',
                       [isDefault] = '" . $this->model->getIsDefault(0, 'single') . "',
                       [isNew] = '" . $this->model->getIsNew(0, 'single') . "',
                       [isDraft] = '" . $this->model->getIsDraft(0, 'single') . "',
                       [isUpdate] = '" . $this->model->getIsUpdate(0, 'single') . "',
                       [isDelete] = '" . $this->model->getIsDelete(0, 'single') . "',
                       [isActive] = '" . $this->model->getIsActive(0, 'single') . "',
                       [isApproved] = '" . $this->model->getIsApproved(0, 'single') . "',
                       [isReview] = '" . $this->model->getIsReview(0, 'single') . "',
                       [isPost] = '" . $this->model->getIsPost(0, 'single') . "',
                       [executeBy] = '" . $this->model->getExecuteBy() . "',
                       [executeTime] = " . $this->model->getExecuteTime() . "
                WHERE  [companyId]='" . $this->getCompanyId() . "'  AND [invoiceDetailLineNumber] = '" . $this->model->getInvoiceDetailLineNumber(4) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                UPDATE INVOICEDETAIL SET
                        INVOICEID = '" . $this->model->getInvoiceId(4) . "',
                       PRODUCTID = '" . $this->model->getProductId(4) . "',
                       UNITOFMEASUREMENTID = '" . $this->model->getUnitOfMeasurementId(4) . "',
                       DISCOUNTID = '" . $this->model->getDiscountId(4) . "',
                       TAXID = '" . $this->model->getTaxId(4) . "',
                       INVOICEDETAILLINENUMBER = '" . $this->model->getInvoiceDetailLineNumber(4) . "',
                       INVOICEDETAILQUANTITY = '" . $this->model->getInvoiceDetailQuantity(4) . "',
                       INVOICEDETAILDESCRIPTION = '" . $this->model->getInvoiceDetailDescription(4) . "',
                       INVOICEDETAILPRICE = '" . $this->model->getInvoiceDetailPrice(4) . "',
                       INVOICEDETAILDISCOUNT = '" . $this->model->getInvoiceDetailDiscount(4) . "',
                       INVOICEDETAILTAX = '" . $this->model->getInvoiceDetailTax(4) . "',
                       INVOICEDETAILTOTALPRICE = '" . $this->model->getInvoiceDetailTotalPrice(4) . "',
                       ISDEFAULT = '" . $this->model->getIsDefault(0, 'single') . "',
                       ISNEW = '" . $this->model->getIsNew(0, 'single') . "',
                       ISDRAFT = '" . $this->model->getIsDraft(0, 'single') . "',
                       ISUPDATE = '" . $this->model->getIsUpdate(0, 'single') . "',
                       ISDELETE = '" . $this->model->getIsDelete(0, 'single') . "',
                       ISACTIVE = '" . $this->model->getIsActive(0, 'single') . "',
                       ISAPPROVED = '" . $this->model->getIsApproved(0, 'single') . "',
                       ISREVIEW = '" . $this->model->getIsReview(0, 'single') . "',
                       ISPOST = '" . $this->model->getIsPost(0, 'single') . "',
                       EXECUTEBY = '" . $this->model->getExecuteBy() . "',
                       EXECUTETIME = " . $this->model->getExecuteTime() . "
              WHERE COMPANYID='" . $this->getCompanyId() . "'  AND INVOICEDETAILLINENUMBER = '" . $this->model->getInvoiceDetailLineNumber(4) . "' ";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
               UPDATE `invoicedetail`
               SET                        `productId` = '" . $this->model->getProductId(5) . "',
                       `unitOfMeasurementId` = '" . $this->model->getUnitOfMeasurementId(5) . "',
                       `discountId` = '" . $this->model->getDiscountId(5) . "',
                       `taxId` = '" . $this->model->getTaxId(5) . "',
                       `invoiceDetailLineNumber` = '" . $this->model->getInvoiceDetailLineNumber(5) . "',
                       `invoiceDetailQuantity` = '" . $this->model->getInvoiceDetailQuantity(5) . "',
                       `invoiceDetailDescription` = '" . $this->model->getInvoiceDetailDescription(5) . "',
                       `invoiceDetailPrice` = '" . $this->model->getInvoiceDetailPrice(5) . "',
                       `invoiceDetailDiscount` = '" . $this->model->getInvoiceDetailDiscount(5) . "',
                       `invoiceDetailTax` = '" . $this->model->getInvoiceDetailTax(5) . "',
                       `invoiceDetailTotalPrice` = '" . $this->model->getInvoiceDetailTotalPrice(5) . "',
                       `isDefault` = '" . $this->model->getIsDefault('0', 'single') . "',
                       `isNew` = '" . $this->model->getIsNew('0', 'single') . "',
                       `isDraft` = '" . $this->model->getIsDraft('0', 'single') . "',
                       `isUpdate` = '" . $this->model->getIsUpdate('0', 'single') . "',
                       `isDelete` = '" . $this->model->getIsDelete('0', 'single') . "',
                       `isActive` = '" . $this->model->getIsActive('0', 'single') . "',
                       `isApproved` = '" . $this->model->getIsApproved('0', 'single') . "',
                       `isReview` = '" . $this->model->getIsReview('0', 'single') . "',
                       `isPost` = '" . $this->model->getIsPost('0', 'single') . "',
                       `executeBy` = '" . $this->model->getExecuteBy() . "',
                       `executeTime` = " . $this->model->getExecuteTime() . "
               WHERE    `companyId` ='" . $this->getCompanyId() . "'
AND 	   `invoiceDetailLineNumber` = '" . $this->model->getInvoiceDetailLineNumber(5) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                UPDATE [invoiceDetail] SET 
                       [invoiceId] = '" . $this->model->getInvoiceId(5) . "',
                       [productId] = '" . $this->model->getProductId(5) . "',
                       [unitOfMeasurementId] = '" . $this->model->getUnitOfMeasurementId(5) . "',
                       [discountId] = '" . $this->model->getDiscountId(5) . "',
                       [taxId] = '" . $this->model->getTaxId(5) . "',
                       [invoiceDetailLineNumber] = '" . $this->model->getInvoiceDetailLineNumber(5) . "',
                       [invoiceDetailQuantity] = '" . $this->model->getInvoiceDetailQuantity(5) . "',
                       [invoiceDetailDescription] = '" . $this->model->getInvoiceDetailDescription(5) . "',
                       [invoiceDetailPrice] = '" . $this->model->getInvoiceDetailPrice(5) . "',
                       [invoiceDetailDiscount] = '" . $this->model->getInvoiceDetailDiscount(5) . "',
                       [invoiceDetailTax] = '" . $this->model->getInvoiceDetailTax(5) . "',
                       [invoiceDetailTotalPrice] = '" . $this->model->getInvoiceDetailTotalPrice(5) . "',
                       [isDefault] = '" . $this->model->getIsDefault(0, 'single') . "',
                       [isNew] = '" . $this->model->getIsNew(0, 'single') . "',
                       [isDraft] = '" . $this->model->getIsDraft(0, 'single') . "',
                       [isUpdate] = '" . $this->model->getIsUpdate(0, 'single') . "',
                       [isDelete] = '" . $this->model->getIsDelete(0, 'single') . "',
                       [isActive] = '" . $this->model->getIsActive(0, 'single') . "',
                       [isApproved] = '" . $this->model->getIsApproved(0, 'single') . "',
                       [isReview] = '" . $this->model->getIsReview(0, 'single') . "',
                       [isPost] = '" . $this->model->getIsPost(0, 'single') . "',
                       [executeBy] = '" . $this->model->getExecuteBy() . "',
                       [executeTime] = " . $this->model->getExecuteTime() . "
                WHERE  [companyId]='" . $this->getCompanyId() . "'  AND [invoiceDetailLineNumber] = '" . $this->model->getInvoiceDetailLineNumber(5) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                UPDATE INVOICEDETAIL SET
                        INVOICEID = '" . $this->model->getInvoiceId(5) . "',
                       PRODUCTID = '" . $this->model->getProductId(5) . "',
                       UNITOFMEASUREMENTID = '" . $this->model->getUnitOfMeasurementId(5) . "',
                       DISCOUNTID = '" . $this->model->getDiscountId(5) . "',
                       TAXID = '" . $this->model->getTaxId(5) . "',
                       INVOICEDETAILLINENUMBER = '" . $this->model->getInvoiceDetailLineNumber(5) . "',
                       INVOICEDETAILQUANTITY = '" . $this->model->getInvoiceDetailQuantity(5) . "',
                       INVOICEDETAILDESCRIPTION = '" . $this->model->getInvoiceDetailDescription(5) . "',
                       INVOICEDETAILPRICE = '" . $this->model->getInvoiceDetailPrice(5) . "',
                       INVOICEDETAILDISCOUNT = '" . $this->model->getInvoiceDetailDiscount(5) . "',
                       INVOICEDETAILTAX = '" . $this->model->getInvoiceDetailTax(5) . "',
                       INVOICEDETAILTOTALPRICE = '" . $this->model->getInvoiceDetailTotalPrice(5) . "',
                       ISDEFAULT = '" . $this->model->getIsDefault(0, 'single') . "',
                       ISNEW = '" . $this->model->getIsNew(0, 'single') . "',
                       ISDRAFT = '" . $this->model->getIsDraft(0, 'single') . "',
                       ISUPDATE = '" . $this->model->getIsUpdate(0, 'single') . "',
                       ISDELETE = '" . $this->model->getIsDelete(0, 'single') . "',
                       ISACTIVE = '" . $this->model->getIsActive(0, 'single') . "',
                       ISAPPROVED = '" . $this->model->getIsApproved(0, 'single') . "',
                       ISREVIEW = '" . $this->model->getIsReview(0, 'single') . "',
                       ISPOST = '" . $this->model->getIsPost(0, 'single') . "',
                       EXECUTEBY = '" . $this->model->getExecuteBy() . "',
                       EXECUTETIME = " . $this->model->getExecuteTime() . "
              WHERE COMPANYID='" . $this->getCompanyId() . "'  AND INVOICEDETAILLINENUMBER = '" . $this->model->getInvoiceDetailLineNumber(5) . "' ";
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
     * Delete
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

class InvoiceTransactionService extends ConfigClass {

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
     * Model 
     * @var \Core\Financial\AccountReceivable\Invoice\Model\InvoiceTransactionMultiModel 
     */
    public $model;

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
     * Return Country
     * @return array|string
     * @throws \Exception
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
            while (($row = $this->q->fetchArray($result)) == TRUE) {
                if ($this->getServiceOutput() == 'option') {
                    $str.="<option value='" . $row['countryId'] . "'>" . $d . ". " . $row['countryDescription'] . "</option>";
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
     * Return Country Default Value
     * @return int
     * @throws \Exception
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
     * Return Invoice
     * @return array|string
     * @throws \Exception
     */
    public function getInvoice() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
         SELECT      `invoiceId`,
                     `invoiceDescription`
         FROM        `invoice`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      [invoiceId],
                     [invoiceDescription]
         FROM        [invoice]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
         SELECT      INVOICEID AS \"invoiceId\",
                     INVOICEDESCRIPTION AS \"invoiceDescription\"
         FROM        INVOICE  
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
                    $str.="<option value='" . $row['invoiceId'] . "'>" . $d . ". " . $row['invoiceDescription'] . "</option>";
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
     * Return Invoice Default Value
     * @return int
     * @throws \Exception
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
     * Return Chart Of Account
     * @return array|string
     * @throws \Exception
     */
    public function getChartOfAccount() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
         SELECT      `chartOfAccountId`,
                     `chartOfAccountTitle`
         FROM        `chartofaccount`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      [chartOfAccountId],
                     [chartOfAccountTitle]
         FROM        [chartOfAccount]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
         SELECT      CHARTOFACCOUNTID AS \"chartOfAccountId\",
                     CHARTOFACCOUNTTITLE AS \"chartOfAccountTitle\"
         FROM        CHARTOFACCOUNT  
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
                    $str.="<option value='" . $row['chartOfAccountId'] . "'>" . $d . ". " . $row['chartOfAccountTitle'] . "</option>";
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
     * Return Chart   Of   Account Default Value
     * @return int
     * @throws \Exception
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

    /** Create
     * @see config::create()
     *  @return void
     * */
    public function create() {
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            try {
                $this->q->fast($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        $this->model->create();
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `invoicetransaction` 
            (
                 `companyId`,
                 `countryId`,
                 `invoiceId`,
                 `chartOfAccountId`,
                 `journalNumber`,
                 `invoiceTransactionPrincipalAmount`,
                 `invoiceTransactionInterestAmount`,
                 `invoiceTransactionCoupunRateAmount`,
                 `invoiceTransactionTaxAmount`,
                 `invoiceTransactionAmount`,
                 `isDefault`,
                 `isNew`,
                 `isDraft`,
                 `isUpdate`,
                 `isDelete`,
                 `isActive`,
                 `isApproved`,
                 `isReview`,
                 `isPost`,
                 `executeBy`,
                 `executeTime`
       ) VALUES
( 
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getInvoiceId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getJournalNumber() . "',
                 '" . $this->model->getInvoiceTransactionPrincipalAmount() . "',
                 '" . $this->model->getInvoiceTransactionInterestAmount() . "',
                 '" . $this->model->getInvoiceTransactionCoupunRateAmount() . "',
                 '" . $this->model->getInvoiceTransactionTaxAmount() . "',
                 '" . $this->model->getInvoiceTransactionAmount() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),

( 
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getInvoiceId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getJournalNumber() . "',
                 '" . $this->model->getInvoiceTransactionPrincipalAmount() . "',
                 '" . $this->model->getInvoiceTransactionInterestAmount() . "',
                 '" . $this->model->getInvoiceTransactionCoupunRateAmount() . "',
                 '" . $this->model->getInvoiceTransactionTaxAmount() . "',
                 '" . $this->model->getInvoiceTransactionAmount() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),

( 
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getInvoiceId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getJournalNumber() . "',
                 '" . $this->model->getInvoiceTransactionPrincipalAmount() . "',
                 '" . $this->model->getInvoiceTransactionInterestAmount() . "',
                 '" . $this->model->getInvoiceTransactionCoupunRateAmount() . "',
                 '" . $this->model->getInvoiceTransactionTaxAmount() . "',
                 '" . $this->model->getInvoiceTransactionAmount() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),

( 
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getInvoiceId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getJournalNumber() . "',
                 '" . $this->model->getInvoiceTransactionPrincipalAmount() . "',
                 '" . $this->model->getInvoiceTransactionInterestAmount() . "',
                 '" . $this->model->getInvoiceTransactionCoupunRateAmount() . "',
                 '" . $this->model->getInvoiceTransactionTaxAmount() . "',
                 '" . $this->model->getInvoiceTransactionAmount() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),

( 
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getInvoiceId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getJournalNumber() . "',
                 '" . $this->model->getInvoiceTransactionPrincipalAmount() . "',
                 '" . $this->model->getInvoiceTransactionInterestAmount() . "',
                 '" . $this->model->getInvoiceTransactionCoupunRateAmount() . "',
                 '" . $this->model->getInvoiceTransactionTaxAmount() . "',
                 '" . $this->model->getInvoiceTransactionAmount() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            INSERT INTO [invoiceTransaction] 
            (
                 [invoiceTransactionId],
                 [companyId],
                 [countryId],
                 [invoiceId],
                 [chartOfAccountId],
                 [journalNumber],
                 [invoiceTransactionPrincipalAmount],
                 [invoiceTransactionInterestAmount],
                 [invoiceTransactionCoupunRateAmount],
                 [invoiceTransactionTaxAmount],
                 [invoiceTransactionAmount],
                 [isDefault],
                 [isNew],
                 [isDraft],
                 [isUpdate],
                 [isDelete],
                 [isActive],
                 [isApproved],
                 [isReview],
                 [isPost],
                 [executeBy],
                 [executeTime]
) VALUES
(
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getCountryId(1) . "',
                 '" . $this->model->getInvoiceId(1) . "',
                 '" . $this->model->getChartOfAccountId(1) . "',
                 '" . $this->model->getJournalNumber(1) . "',
                 '" . $this->model->getInvoiceTransactionPrincipalAmount(1) . "',
                 '" . $this->model->getInvoiceTransactionInterestAmount(1) . "',
                 '" . $this->model->getInvoiceTransactionCoupunRateAmount(1) . "',
                 '" . $this->model->getInvoiceTransactionTaxAmount(1) . "',
                 '" . $this->model->getInvoiceTransactionAmount(1) . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),
(
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getCountryId(2) . "',
                 '" . $this->model->getInvoiceId(2) . "',
                 '" . $this->model->getChartOfAccountId(2) . "',
                 '" . $this->model->getJournalNumber(2) . "',
                 '" . $this->model->getInvoiceTransactionPrincipalAmount(2) . "',
                 '" . $this->model->getInvoiceTransactionInterestAmount(2) . "',
                 '" . $this->model->getInvoiceTransactionCoupunRateAmount(2) . "',
                 '" . $this->model->getInvoiceTransactionTaxAmount(2) . "',
                 '" . $this->model->getInvoiceTransactionAmount(2) . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),
(
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getCountryId(3) . "',
                 '" . $this->model->getInvoiceId(3) . "',
                 '" . $this->model->getChartOfAccountId(3) . "',
                 '" . $this->model->getJournalNumber(3) . "',
                 '" . $this->model->getInvoiceTransactionPrincipalAmount(3) . "',
                 '" . $this->model->getInvoiceTransactionInterestAmount(3) . "',
                 '" . $this->model->getInvoiceTransactionCoupunRateAmount(3) . "',
                 '" . $this->model->getInvoiceTransactionTaxAmount(3) . "',
                 '" . $this->model->getInvoiceTransactionAmount(3) . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),
(
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getCountryId(4) . "',
                 '" . $this->model->getInvoiceId(4) . "',
                 '" . $this->model->getChartOfAccountId(4) . "',
                 '" . $this->model->getJournalNumber(4) . "',
                 '" . $this->model->getInvoiceTransactionPrincipalAmount(4) . "',
                 '" . $this->model->getInvoiceTransactionInterestAmount(4) . "',
                 '" . $this->model->getInvoiceTransactionCoupunRateAmount(4) . "',
                 '" . $this->model->getInvoiceTransactionTaxAmount(4) . "',
                 '" . $this->model->getInvoiceTransactionAmount(4) . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),
(
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getCountryId(5) . "',
                 '" . $this->model->getInvoiceId(5) . "',
                 '" . $this->model->getChartOfAccountId(5) . "',
                 '" . $this->model->getJournalNumber(5) . "',
                 '" . $this->model->getInvoiceTransactionPrincipalAmount(5) . "',
                 '" . $this->model->getInvoiceTransactionInterestAmount(5) . "',
                 '" . $this->model->getInvoiceTransactionCoupunRateAmount(5) . "',
                 '" . $this->model->getInvoiceTransactionTaxAmount(5) . "',
                 '" . $this->model->getInvoiceTransactionAmount(5) . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
)";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            INSERT INTO INVOICETRANSACTION 
            (
                 COMPANYID,
                 COUNTRYID,
                 INVOICEID,
                 CHARTOFACCOUNTID,
                 JOURNALNUMBER,
                 INVOICETRANSACTIONPRINCIPALAMOUNT,
                 INVOICETRANSACTIONINTERESTAMOUNT,
                 INVOICETRANSACTIONCOUPUNRATEAMOUNT,
                 INVOICETRANSACTIONTAXAMOUNT,
                 INVOICETRANSACTIONAMOUNT,
                 ISDEFAULT,
                 ISNEW,
                 ISDRAFT,
                 ISUPDATE,
                 ISDELETE,
                 ISACTIVE,
                 ISAPPROVED,
                 ISREVIEW,
                 ISPOST,
                 EXECUTEBY,
                 EXECUTETIME
            ) VALUES 
(
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getCountryId(1) . "',
                 '" . $this->model->getInvoiceId(1) . "',
                 '" . $this->model->getChartOfAccountId(1) . "',
                 '" . $this->model->getJournalNumber(1) . "',
                 '" . $this->model->getInvoiceTransactionPrincipalAmount(1) . "',
                 '" . $this->model->getInvoiceTransactionInterestAmount(1) . "',
                 '" . $this->model->getInvoiceTransactionCoupunRateAmount(1) . "',
                 '" . $this->model->getInvoiceTransactionTaxAmount(1) . "',
                 '" . $this->model->getInvoiceTransactionAmount(1) . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),(
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getCountryId(2) . "',
                 '" . $this->model->getInvoiceId(2) . "',
                 '" . $this->model->getChartOfAccountId(2) . "',
                 '" . $this->model->getJournalNumber(2) . "',
                 '" . $this->model->getInvoiceTransactionPrincipalAmount(2) . "',
                 '" . $this->model->getInvoiceTransactionInterestAmount(2) . "',
                 '" . $this->model->getInvoiceTransactionCoupunRateAmount(2) . "',
                 '" . $this->model->getInvoiceTransactionTaxAmount(2) . "',
                 '" . $this->model->getInvoiceTransactionAmount(2) . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),(
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getCountryId(3) . "',
                 '" . $this->model->getInvoiceId(3) . "',
                 '" . $this->model->getChartOfAccountId(3) . "',
                 '" . $this->model->getJournalNumber(3) . "',
                 '" . $this->model->getInvoiceTransactionPrincipalAmount(3) . "',
                 '" . $this->model->getInvoiceTransactionInterestAmount(3) . "',
                 '" . $this->model->getInvoiceTransactionCoupunRateAmount(3) . "',
                 '" . $this->model->getInvoiceTransactionTaxAmount(3) . "',
                 '" . $this->model->getInvoiceTransactionAmount(3) . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),(
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getCountryId(4) . "',
                 '" . $this->model->getInvoiceId(4) . "',
                 '" . $this->model->getChartOfAccountId(4) . "',
                 '" . $this->model->getJournalNumber(4) . "',
                 '" . $this->model->getInvoiceTransactionPrincipalAmount(4) . "',
                 '" . $this->model->getInvoiceTransactionInterestAmount(4) . "',
                 '" . $this->model->getInvoiceTransactionCoupunRateAmount(4) . "',
                 '" . $this->model->getInvoiceTransactionTaxAmount(4) . "',
                 '" . $this->model->getInvoiceTransactionAmount(4) . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
),(
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getCountryId(5) . "',
                 '" . $this->model->getInvoiceId(5) . "',
                 '" . $this->model->getChartOfAccountId(5) . "',
                 '" . $this->model->getJournalNumber(5) . "',
                 '" . $this->model->getInvoiceTransactionPrincipalAmount(5) . "',
                 '" . $this->model->getInvoiceTransactionInterestAmount(5) . "',
                 '" . $this->model->getInvoiceTransactionCoupunRateAmount(5) . "',
                 '" . $this->model->getInvoiceTransactionTaxAmount(5) . "',
                 '" . $this->model->getInvoiceTransactionAmount(5) . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . ",
";
        }
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Read
     * @see config::read()
     * @return void
     */
    public function read() {
        if ($this->getVendor() == self::MYSQL) {

            $sql = "
       SELECT                    `invoicetransaction`.`invoiceTransactionId`,
                    `company`.`companyDescription`,
                    `invoicetransaction`.`companyId`,
                    `country`.`countryDescription`,
                    `invoicetransaction`.`countryId`,
                    `invoice`.`invoiceDescription`,
                    `invoicetransaction`.`invoiceId`,
                    `chartofaccount`.`chartOfAccountTitle`,
                    `invoicetransaction`.`chartOfAccountId`,
                    `invoicetransaction`.`journalNumber`,
                    `invoicetransaction`.`invoiceTransactionPrincipalAmount`,
                    `invoicetransaction`.`invoiceTransactionInterestAmount`,
                    `invoicetransaction`.`invoiceTransactionCoupunRateAmount`,
                    `invoicetransaction`.`invoiceTransactionTaxAmount`,
                    `invoicetransaction`.`invoiceTransactionAmount`,
                    `invoicetransaction`.`isDefault`,
                    `invoicetransaction`.`isNew`,
                    `invoicetransaction`.`isDraft`,
                    `invoicetransaction`.`isUpdate`,
                    `invoicetransaction`.`isDelete`,
                    `invoicetransaction`.`isActive`,
                    `invoicetransaction`.`isApproved`,
                    `invoicetransaction`.`isReview`,
                    `invoicetransaction`.`isPost`,
                    `invoicetransaction`.`executeBy`,
                    `invoicetransaction`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `invoicetransaction`
		  JOIN      `staff`
		  ON        `invoicetransaction`.`executeBy` = `staff`.`staffId`
		  WHERE    `companyId`= " . $this->companyId() . "' 
		  AND    `invoiceTransactionLineNumber` = '" . $this->model->getInvoiceTransactionLineNumber(22) . "'";
        } else if ($this->getVendor() == self::MSSQL) {

            $sql = "
		  SELECT                    [invoiceTransaction].[invoiceTransactionId],
                    [company].[companyDescription],
                    [invoiceTransaction].[companyId],
                    [country].[countryDescription],
                    [invoiceTransaction].[countryId],
                    [invoice].[invoiceDescription],
                    [invoiceTransaction].[invoiceId],
                    [chartOfAccount].[chartOfAccountTitle],
                    [invoiceTransaction].[chartOfAccountId],
                    [invoiceTransaction].[journalNumber],
                    [invoiceTransaction].[invoiceTransactionPrincipalAmount],
                    [invoiceTransaction].[invoiceTransactionInterestAmount],
                    [invoiceTransaction].[invoiceTransactionCoupunRateAmount],
                    [invoiceTransaction].[invoiceTransactionTaxAmount],
                    [invoiceTransaction].[invoiceTransactionAmount],
                    [invoiceTransaction].[isDefault],
                    [invoiceTransaction].[isNew],
                    [invoiceTransaction].[isDraft],
                    [invoiceTransaction].[isUpdate],
                    [invoiceTransaction].[isDelete],
                    [invoiceTransaction].[isActive],
                    [invoiceTransaction].[isApproved],
                    [invoiceTransaction].[isReview],
                    [invoiceTransaction].[isPost],
                    [invoiceTransaction].[executeBy],
                    [invoiceTransaction].[executeTime],
                    [staff].[staffName] 
		  FROM 	[invoiceTransaction]
		  JOIN	[staff]
		  ON	[invoiceTransaction].[executeBy] = [staff].[staffId]
		  WHERE    [companyId]='" . $this->getCompanyId() . "'  AND [invoiceTransactionLineNumber] = '" . $this->model->getInvoiceTransactionLineNumber(0, 'single') . "' ";
            if ($this->model->getInvoiceTransactionId(0, 'single')) {
                $sql .= " AND [invoiceTransaction].[" . $this->model->getPrimaryKeyName() . "]		=	'" . $this->model->getInvoiceTransactionId(0, 'single') . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {

            $sql = "
		  SELECT                    INVOICETRANSACTION.INVOICETRANSACTIONID AS \"invoiceTransactionId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    INVOICETRANSACTION.COMPANYID AS \"companyId\",
                    COUNTRY.COUNTRYDESCRIPTION AS  \"countryDescription\",
                    INVOICETRANSACTION.COUNTRYID AS \"countryId\",
                    INVOICE.INVOICEDESCRIPTION AS  \"invoiceDescription\",
                    INVOICETRANSACTION.INVOICEID AS \"invoiceId\",
                    CHARTOFACCOUNT.CHARTOFACCOUNTTITLE AS  \"chartOfAccountTitle\",
                    INVOICETRANSACTION.CHARTOFACCOUNTID AS \"chartOfAccountId\",
                    INVOICETRANSACTION.JOURNALNUMBER AS \"journalNumber\",
                    INVOICETRANSACTION.INVOICETRANSACTIONPRINCIPALAMOUNT AS \"invoiceTransactionPrincipalAmount\",
                    INVOICETRANSACTION.INVOICETRANSACTIONINTERESTAMOUNT AS \"invoiceTransactionInterestAmount\",
                    INVOICETRANSACTION.INVOICETRANSACTIONCOUPUNRATEAMOUNT AS \"invoiceTransactionCoupunRateAmount\",
                    INVOICETRANSACTION.INVOICETRANSACTIONTAXAMOUNT AS \"invoiceTransactionTaxAmount\",
                    INVOICETRANSACTION.INVOICETRANSACTIONAMOUNT AS \"invoiceTransactionAmount\",
                    INVOICETRANSACTION.ISDEFAULT AS \"isDefault\",
                    INVOICETRANSACTION.ISNEW AS \"isNew\",
                    INVOICETRANSACTION.ISDRAFT AS \"isDraft\",
                    INVOICETRANSACTION.ISUPDATE AS \"isUpdate\",
                    INVOICETRANSACTION.ISDELETE AS \"isDelete\",
                    INVOICETRANSACTION.ISACTIVE AS \"isActive\",
                    INVOICETRANSACTION.ISAPPROVED AS \"isApproved\",
                    INVOICETRANSACTION.ISREVIEW AS \"isReview\",
                    INVOICETRANSACTION.ISPOST AS \"isPost\",
                    INVOICETRANSACTION.EXECUTEBY AS \"executeBy\",
                    INVOICETRANSACTION.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\" 
		  FROM 	INVOICETRANSACTION 
		  JOIN	STAFF 
		  ON	INVOICETRANSACTION.EXECUTEBY = STAFF.STAFFID 
          WHERE     COMPANYID='" . $this->getCompanyId() . "'  AND INVOICETRANSACTIONLINENUMBER = '" . $this->model->getInvoiceTransactionLineNumber(22) . "' ";
        }
        try {
            $this->q->read($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $items = array();
        $i = 1;
        while (($row = $this->q->fetchAssoc()) == TRUE) {
            $items [] = $row;
            $i++;
        }
        return $items;
    }

    /**
     * Update
     * @see config::update()
     * @return void
     */
    public function update() {
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            try {
                $this->q->fast($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        $this->q->start();
        $this->model->update();
        // before updating check the id exist or not . if exist continue to update else warning the user 
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
               UPDATE `invoicetransaction`
               SET                        `countryId` = '" . $this->model->getCountryId(1) . "',
                       `chartOfAccountId` = '" . $this->model->getChartOfAccountId(1) . "',
                       `journalNumber` = '" . $this->model->getJournalNumber(1) . "',
                       `invoiceTransactionPrincipalAmount` = '" . $this->model->getInvoiceTransactionPrincipalAmount(1) . "',
                       `invoiceTransactionInterestAmount` = '" . $this->model->getInvoiceTransactionInterestAmount(1) . "',
                       `invoiceTransactionCoupunRateAmount` = '" . $this->model->getInvoiceTransactionCoupunRateAmount(1) . "',
                       `invoiceTransactionTaxAmount` = '" . $this->model->getInvoiceTransactionTaxAmount(1) . "',
                       `invoiceTransactionAmount` = '" . $this->model->getInvoiceTransactionAmount(1) . "',
                       `isDefault` = '" . $this->model->getIsDefault('0', 'single') . "',
                       `isNew` = '" . $this->model->getIsNew('0', 'single') . "',
                       `isDraft` = '" . $this->model->getIsDraft('0', 'single') . "',
                       `isUpdate` = '" . $this->model->getIsUpdate('0', 'single') . "',
                       `isDelete` = '" . $this->model->getIsDelete('0', 'single') . "',
                       `isActive` = '" . $this->model->getIsActive('0', 'single') . "',
                       `isApproved` = '" . $this->model->getIsApproved('0', 'single') . "',
                       `isReview` = '" . $this->model->getIsReview('0', 'single') . "',
                       `isPost` = '" . $this->model->getIsPost('0', 'single') . "',
                       `executeBy` = '" . $this->model->getExecuteBy() . "',
                       `executeTime` = " . $this->model->getExecuteTime() . "
               WHERE    `companyId` ='" . $this->getCompanyId() . "'
AND 	   `invoiceTransactionLineNumber` = '" . $this->model->getInvoiceTransactionLineNumber(1) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                UPDATE [invoiceTransaction] SET 
                       [countryId] = '" . $this->model->getCountryId(1) . "',
                       [invoiceId] = '" . $this->model->getInvoiceId(1) . "',
                       [chartOfAccountId] = '" . $this->model->getChartOfAccountId(1) . "',
                       [journalNumber] = '" . $this->model->getJournalNumber(1) . "',
                       [invoiceTransactionPrincipalAmount] = '" . $this->model->getInvoiceTransactionPrincipalAmount(1) . "',
                       [invoiceTransactionInterestAmount] = '" . $this->model->getInvoiceTransactionInterestAmount(1) . "',
                       [invoiceTransactionCoupunRateAmount] = '" . $this->model->getInvoiceTransactionCoupunRateAmount(1) . "',
                       [invoiceTransactionTaxAmount] = '" . $this->model->getInvoiceTransactionTaxAmount(1) . "',
                       [invoiceTransactionAmount] = '" . $this->model->getInvoiceTransactionAmount(1) . "',
                       [isDefault] = '" . $this->model->getIsDefault(0, 'single') . "',
                       [isNew] = '" . $this->model->getIsNew(0, 'single') . "',
                       [isDraft] = '" . $this->model->getIsDraft(0, 'single') . "',
                       [isUpdate] = '" . $this->model->getIsUpdate(0, 'single') . "',
                       [isDelete] = '" . $this->model->getIsDelete(0, 'single') . "',
                       [isActive] = '" . $this->model->getIsActive(0, 'single') . "',
                       [isApproved] = '" . $this->model->getIsApproved(0, 'single') . "',
                       [isReview] = '" . $this->model->getIsReview(0, 'single') . "',
                       [isPost] = '" . $this->model->getIsPost(0, 'single') . "',
                       [executeBy] = '" . $this->model->getExecuteBy() . "',
                       [executeTime] = " . $this->model->getExecuteTime() . "
                WHERE  [companyId]='" . $this->getCompanyId() . "'  AND [invoiceTransactionLineNumber] = '" . $this->model->getInvoiceTransactionLineNumber(1) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                UPDATE INVOICETRANSACTION SET
                        COUNTRYID = '" . $this->model->getCountryId(1) . "',
                       INVOICEID = '" . $this->model->getInvoiceId(1) . "',
                       CHARTOFACCOUNTID = '" . $this->model->getChartOfAccountId(1) . "',
                       JOURNALNUMBER = '" . $this->model->getJournalNumber(1) . "',
                       INVOICETRANSACTIONPRINCIPALAMOUNT = '" . $this->model->getInvoiceTransactionPrincipalAmount(1) . "',
                       INVOICETRANSACTIONINTERESTAMOUNT = '" . $this->model->getInvoiceTransactionInterestAmount(1) . "',
                       INVOICETRANSACTIONCOUPUNRATEAMOUNT = '" . $this->model->getInvoiceTransactionCoupunRateAmount(1) . "',
                       INVOICETRANSACTIONTAXAMOUNT = '" . $this->model->getInvoiceTransactionTaxAmount(1) . "',
                       INVOICETRANSACTIONAMOUNT = '" . $this->model->getInvoiceTransactionAmount(1) . "',
                       ISDEFAULT = '" . $this->model->getIsDefault(0, 'single') . "',
                       ISNEW = '" . $this->model->getIsNew(0, 'single') . "',
                       ISDRAFT = '" . $this->model->getIsDraft(0, 'single') . "',
                       ISUPDATE = '" . $this->model->getIsUpdate(0, 'single') . "',
                       ISDELETE = '" . $this->model->getIsDelete(0, 'single') . "',
                       ISACTIVE = '" . $this->model->getIsActive(0, 'single') . "',
                       ISAPPROVED = '" . $this->model->getIsApproved(0, 'single') . "',
                       ISREVIEW = '" . $this->model->getIsReview(0, 'single') . "',
                       ISPOST = '" . $this->model->getIsPost(0, 'single') . "',
                       EXECUTEBY = '" . $this->model->getExecuteBy() . "',
                       EXECUTETIME = " . $this->model->getExecuteTime() . "
              WHERE COMPANYID='" . $this->getCompanyId() . "'  AND INVOICETRANSACTIONLINENUMBER = '" . $this->model->getInvoiceTransactionLineNumber(1) . "' ";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
               UPDATE `invoicetransaction`
               SET                        `countryId` = '" . $this->model->getCountryId(2) . "',
                       `chartOfAccountId` = '" . $this->model->getChartOfAccountId(2) . "',
                       `journalNumber` = '" . $this->model->getJournalNumber(2) . "',
                       `invoiceTransactionPrincipalAmount` = '" . $this->model->getInvoiceTransactionPrincipalAmount(2) . "',
                       `invoiceTransactionInterestAmount` = '" . $this->model->getInvoiceTransactionInterestAmount(2) . "',
                       `invoiceTransactionCoupunRateAmount` = '" . $this->model->getInvoiceTransactionCoupunRateAmount(2) . "',
                       `invoiceTransactionTaxAmount` = '" . $this->model->getInvoiceTransactionTaxAmount(2) . "',
                       `invoiceTransactionAmount` = '" . $this->model->getInvoiceTransactionAmount(2) . "',
                       `isDefault` = '" . $this->model->getIsDefault('0', 'single') . "',
                       `isNew` = '" . $this->model->getIsNew('0', 'single') . "',
                       `isDraft` = '" . $this->model->getIsDraft('0', 'single') . "',
                       `isUpdate` = '" . $this->model->getIsUpdate('0', 'single') . "',
                       `isDelete` = '" . $this->model->getIsDelete('0', 'single') . "',
                       `isActive` = '" . $this->model->getIsActive('0', 'single') . "',
                       `isApproved` = '" . $this->model->getIsApproved('0', 'single') . "',
                       `isReview` = '" . $this->model->getIsReview('0', 'single') . "',
                       `isPost` = '" . $this->model->getIsPost('0', 'single') . "',
                       `executeBy` = '" . $this->model->getExecuteBy() . "',
                       `executeTime` = " . $this->model->getExecuteTime() . "
               WHERE    `companyId` ='" . $this->getCompanyId() . "'
AND 	   `invoiceTransactionLineNumber` = '" . $this->model->getInvoiceTransactionLineNumber(2) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                UPDATE [invoiceTransaction] SET 
                       [countryId] = '" . $this->model->getCountryId(2) . "',
                       [invoiceId] = '" . $this->model->getInvoiceId(2) . "',
                       [chartOfAccountId] = '" . $this->model->getChartOfAccountId(2) . "',
                       [journalNumber] = '" . $this->model->getJournalNumber(2) . "',
                       [invoiceTransactionPrincipalAmount] = '" . $this->model->getInvoiceTransactionPrincipalAmount(2) . "',
                       [invoiceTransactionInterestAmount] = '" . $this->model->getInvoiceTransactionInterestAmount(2) . "',
                       [invoiceTransactionCoupunRateAmount] = '" . $this->model->getInvoiceTransactionCoupunRateAmount(2) . "',
                       [invoiceTransactionTaxAmount] = '" . $this->model->getInvoiceTransactionTaxAmount(2) . "',
                       [invoiceTransactionAmount] = '" . $this->model->getInvoiceTransactionAmount(2) . "',
                       [isDefault] = '" . $this->model->getIsDefault(0, 'single') . "',
                       [isNew] = '" . $this->model->getIsNew(0, 'single') . "',
                       [isDraft] = '" . $this->model->getIsDraft(0, 'single') . "',
                       [isUpdate] = '" . $this->model->getIsUpdate(0, 'single') . "',
                       [isDelete] = '" . $this->model->getIsDelete(0, 'single') . "',
                       [isActive] = '" . $this->model->getIsActive(0, 'single') . "',
                       [isApproved] = '" . $this->model->getIsApproved(0, 'single') . "',
                       [isReview] = '" . $this->model->getIsReview(0, 'single') . "',
                       [isPost] = '" . $this->model->getIsPost(0, 'single') . "',
                       [executeBy] = '" . $this->model->getExecuteBy() . "',
                       [executeTime] = " . $this->model->getExecuteTime() . "
                WHERE  [companyId]='" . $this->getCompanyId() . "'  AND [invoiceTransactionLineNumber] = '" . $this->model->getInvoiceTransactionLineNumber(2) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                UPDATE INVOICETRANSACTION SET
                        COUNTRYID = '" . $this->model->getCountryId(2) . "',
                       INVOICEID = '" . $this->model->getInvoiceId(2) . "',
                       CHARTOFACCOUNTID = '" . $this->model->getChartOfAccountId(2) . "',
                       JOURNALNUMBER = '" . $this->model->getJournalNumber(2) . "',
                       INVOICETRANSACTIONPRINCIPALAMOUNT = '" . $this->model->getInvoiceTransactionPrincipalAmount(2) . "',
                       INVOICETRANSACTIONINTERESTAMOUNT = '" . $this->model->getInvoiceTransactionInterestAmount(2) . "',
                       INVOICETRANSACTIONCOUPUNRATEAMOUNT = '" . $this->model->getInvoiceTransactionCoupunRateAmount(2) . "',
                       INVOICETRANSACTIONTAXAMOUNT = '" . $this->model->getInvoiceTransactionTaxAmount(2) . "',
                       INVOICETRANSACTIONAMOUNT = '" . $this->model->getInvoiceTransactionAmount(2) . "',
                       ISDEFAULT = '" . $this->model->getIsDefault(0, 'single') . "',
                       ISNEW = '" . $this->model->getIsNew(0, 'single') . "',
                       ISDRAFT = '" . $this->model->getIsDraft(0, 'single') . "',
                       ISUPDATE = '" . $this->model->getIsUpdate(0, 'single') . "',
                       ISDELETE = '" . $this->model->getIsDelete(0, 'single') . "',
                       ISACTIVE = '" . $this->model->getIsActive(0, 'single') . "',
                       ISAPPROVED = '" . $this->model->getIsApproved(0, 'single') . "',
                       ISREVIEW = '" . $this->model->getIsReview(0, 'single') . "',
                       ISPOST = '" . $this->model->getIsPost(0, 'single') . "',
                       EXECUTEBY = '" . $this->model->getExecuteBy() . "',
                       EXECUTETIME = " . $this->model->getExecuteTime() . "
              WHERE COMPANYID='" . $this->getCompanyId() . "'  AND INVOICETRANSACTIONLINENUMBER = '" . $this->model->getInvoiceTransactionLineNumber(2) . "' ";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
               UPDATE `invoicetransaction`
               SET                        `countryId` = '" . $this->model->getCountryId(3) . "',
                       `chartOfAccountId` = '" . $this->model->getChartOfAccountId(3) . "',
                       `journalNumber` = '" . $this->model->getJournalNumber(3) . "',
                       `invoiceTransactionPrincipalAmount` = '" . $this->model->getInvoiceTransactionPrincipalAmount(3) . "',
                       `invoiceTransactionInterestAmount` = '" . $this->model->getInvoiceTransactionInterestAmount(3) . "',
                       `invoiceTransactionCoupunRateAmount` = '" . $this->model->getInvoiceTransactionCoupunRateAmount(3) . "',
                       `invoiceTransactionTaxAmount` = '" . $this->model->getInvoiceTransactionTaxAmount(3) . "',
                       `invoiceTransactionAmount` = '" . $this->model->getInvoiceTransactionAmount(3) . "',
                       `isDefault` = '" . $this->model->getIsDefault('0', 'single') . "',
                       `isNew` = '" . $this->model->getIsNew('0', 'single') . "',
                       `isDraft` = '" . $this->model->getIsDraft('0', 'single') . "',
                       `isUpdate` = '" . $this->model->getIsUpdate('0', 'single') . "',
                       `isDelete` = '" . $this->model->getIsDelete('0', 'single') . "',
                       `isActive` = '" . $this->model->getIsActive('0', 'single') . "',
                       `isApproved` = '" . $this->model->getIsApproved('0', 'single') . "',
                       `isReview` = '" . $this->model->getIsReview('0', 'single') . "',
                       `isPost` = '" . $this->model->getIsPost('0', 'single') . "',
                       `executeBy` = '" . $this->model->getExecuteBy() . "',
                       `executeTime` = " . $this->model->getExecuteTime() . "
               WHERE    `companyId` ='" . $this->getCompanyId() . "'
AND 	   `invoiceTransactionLineNumber` = '" . $this->model->getInvoiceTransactionLineNumber(3) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                UPDATE [invoiceTransaction] SET 
                       [countryId] = '" . $this->model->getCountryId(3) . "',
                       [invoiceId] = '" . $this->model->getInvoiceId(3) . "',
                       [chartOfAccountId] = '" . $this->model->getChartOfAccountId(3) . "',
                       [journalNumber] = '" . $this->model->getJournalNumber(3) . "',
                       [invoiceTransactionPrincipalAmount] = '" . $this->model->getInvoiceTransactionPrincipalAmount(3) . "',
                       [invoiceTransactionInterestAmount] = '" . $this->model->getInvoiceTransactionInterestAmount(3) . "',
                       [invoiceTransactionCoupunRateAmount] = '" . $this->model->getInvoiceTransactionCoupunRateAmount(3) . "',
                       [invoiceTransactionTaxAmount] = '" . $this->model->getInvoiceTransactionTaxAmount(3) . "',
                       [invoiceTransactionAmount] = '" . $this->model->getInvoiceTransactionAmount(3) . "',
                       [isDefault] = '" . $this->model->getIsDefault(0, 'single') . "',
                       [isNew] = '" . $this->model->getIsNew(0, 'single') . "',
                       [isDraft] = '" . $this->model->getIsDraft(0, 'single') . "',
                       [isUpdate] = '" . $this->model->getIsUpdate(0, 'single') . "',
                       [isDelete] = '" . $this->model->getIsDelete(0, 'single') . "',
                       [isActive] = '" . $this->model->getIsActive(0, 'single') . "',
                       [isApproved] = '" . $this->model->getIsApproved(0, 'single') . "',
                       [isReview] = '" . $this->model->getIsReview(0, 'single') . "',
                       [isPost] = '" . $this->model->getIsPost(0, 'single') . "',
                       [executeBy] = '" . $this->model->getExecuteBy() . "',
                       [executeTime] = " . $this->model->getExecuteTime() . "
                WHERE  [companyId]='" . $this->getCompanyId() . "'  AND [invoiceTransactionLineNumber] = '" . $this->model->getInvoiceTransactionLineNumber(3) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                UPDATE INVOICETRANSACTION SET
                        COUNTRYID = '" . $this->model->getCountryId(3) . "',
                       INVOICEID = '" . $this->model->getInvoiceId(3) . "',
                       CHARTOFACCOUNTID = '" . $this->model->getChartOfAccountId(3) . "',
                       JOURNALNUMBER = '" . $this->model->getJournalNumber(3) . "',
                       INVOICETRANSACTIONPRINCIPALAMOUNT = '" . $this->model->getInvoiceTransactionPrincipalAmount(3) . "',
                       INVOICETRANSACTIONINTERESTAMOUNT = '" . $this->model->getInvoiceTransactionInterestAmount(3) . "',
                       INVOICETRANSACTIONCOUPUNRATEAMOUNT = '" . $this->model->getInvoiceTransactionCoupunRateAmount(3) . "',
                       INVOICETRANSACTIONTAXAMOUNT = '" . $this->model->getInvoiceTransactionTaxAmount(3) . "',
                       INVOICETRANSACTIONAMOUNT = '" . $this->model->getInvoiceTransactionAmount(3) . "',
                       ISDEFAULT = '" . $this->model->getIsDefault(0, 'single') . "',
                       ISNEW = '" . $this->model->getIsNew(0, 'single') . "',
                       ISDRAFT = '" . $this->model->getIsDraft(0, 'single') . "',
                       ISUPDATE = '" . $this->model->getIsUpdate(0, 'single') . "',
                       ISDELETE = '" . $this->model->getIsDelete(0, 'single') . "',
                       ISACTIVE = '" . $this->model->getIsActive(0, 'single') . "',
                       ISAPPROVED = '" . $this->model->getIsApproved(0, 'single') . "',
                       ISREVIEW = '" . $this->model->getIsReview(0, 'single') . "',
                       ISPOST = '" . $this->model->getIsPost(0, 'single') . "',
                       EXECUTEBY = '" . $this->model->getExecuteBy() . "',
                       EXECUTETIME = " . $this->model->getExecuteTime() . "
              WHERE COMPANYID='" . $this->getCompanyId() . "'  AND INVOICETRANSACTIONLINENUMBER = '" . $this->model->getInvoiceTransactionLineNumber(3) . "' ";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
               UPDATE `invoicetransaction`
               SET                        `countryId` = '" . $this->model->getCountryId(4) . "',
                       `chartOfAccountId` = '" . $this->model->getChartOfAccountId(4) . "',
                       `journalNumber` = '" . $this->model->getJournalNumber(4) . "',
                       `invoiceTransactionPrincipalAmount` = '" . $this->model->getInvoiceTransactionPrincipalAmount(4) . "',
                       `invoiceTransactionInterestAmount` = '" . $this->model->getInvoiceTransactionInterestAmount(4) . "',
                       `invoiceTransactionCoupunRateAmount` = '" . $this->model->getInvoiceTransactionCoupunRateAmount(4) . "',
                       `invoiceTransactionTaxAmount` = '" . $this->model->getInvoiceTransactionTaxAmount(4) . "',
                       `invoiceTransactionAmount` = '" . $this->model->getInvoiceTransactionAmount(4) . "',
                       `isDefault` = '" . $this->model->getIsDefault('0', 'single') . "',
                       `isNew` = '" . $this->model->getIsNew('0', 'single') . "',
                       `isDraft` = '" . $this->model->getIsDraft('0', 'single') . "',
                       `isUpdate` = '" . $this->model->getIsUpdate('0', 'single') . "',
                       `isDelete` = '" . $this->model->getIsDelete('0', 'single') . "',
                       `isActive` = '" . $this->model->getIsActive('0', 'single') . "',
                       `isApproved` = '" . $this->model->getIsApproved('0', 'single') . "',
                       `isReview` = '" . $this->model->getIsReview('0', 'single') . "',
                       `isPost` = '" . $this->model->getIsPost('0', 'single') . "',
                       `executeBy` = '" . $this->model->getExecuteBy() . "',
                       `executeTime` = " . $this->model->getExecuteTime() . "
               WHERE    `companyId` ='" . $this->getCompanyId() . "'
AND 	   `invoiceTransactionLineNumber` = '" . $this->model->getInvoiceTransactionLineNumber(4) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                UPDATE [invoiceTransaction] SET 
                       [countryId] = '" . $this->model->getCountryId(4) . "',
                       [invoiceId] = '" . $this->model->getInvoiceId(4) . "',
                       [chartOfAccountId] = '" . $this->model->getChartOfAccountId(4) . "',
                       [journalNumber] = '" . $this->model->getJournalNumber(4) . "',
                       [invoiceTransactionPrincipalAmount] = '" . $this->model->getInvoiceTransactionPrincipalAmount(4) . "',
                       [invoiceTransactionInterestAmount] = '" . $this->model->getInvoiceTransactionInterestAmount(4) . "',
                       [invoiceTransactionCoupunRateAmount] = '" . $this->model->getInvoiceTransactionCoupunRateAmount(4) . "',
                       [invoiceTransactionTaxAmount] = '" . $this->model->getInvoiceTransactionTaxAmount(4) . "',
                       [invoiceTransactionAmount] = '" . $this->model->getInvoiceTransactionAmount(4) . "',
                       [isDefault] = '" . $this->model->getIsDefault(0, 'single') . "',
                       [isNew] = '" . $this->model->getIsNew(0, 'single') . "',
                       [isDraft] = '" . $this->model->getIsDraft(0, 'single') . "',
                       [isUpdate] = '" . $this->model->getIsUpdate(0, 'single') . "',
                       [isDelete] = '" . $this->model->getIsDelete(0, 'single') . "',
                       [isActive] = '" . $this->model->getIsActive(0, 'single') . "',
                       [isApproved] = '" . $this->model->getIsApproved(0, 'single') . "',
                       [isReview] = '" . $this->model->getIsReview(0, 'single') . "',
                       [isPost] = '" . $this->model->getIsPost(0, 'single') . "',
                       [executeBy] = '" . $this->model->getExecuteBy() . "',
                       [executeTime] = " . $this->model->getExecuteTime() . "
                WHERE  [companyId]='" . $this->getCompanyId() . "'  AND [invoiceTransactionLineNumber] = '" . $this->model->getInvoiceTransactionLineNumber(4) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                UPDATE INVOICETRANSACTION SET
                        COUNTRYID = '" . $this->model->getCountryId(4) . "',
                       INVOICEID = '" . $this->model->getInvoiceId(4) . "',
                       CHARTOFACCOUNTID = '" . $this->model->getChartOfAccountId(4) . "',
                       JOURNALNUMBER = '" . $this->model->getJournalNumber(4) . "',
                       INVOICETRANSACTIONPRINCIPALAMOUNT = '" . $this->model->getInvoiceTransactionPrincipalAmount(4) . "',
                       INVOICETRANSACTIONINTERESTAMOUNT = '" . $this->model->getInvoiceTransactionInterestAmount(4) . "',
                       INVOICETRANSACTIONCOUPUNRATEAMOUNT = '" . $this->model->getInvoiceTransactionCoupunRateAmount(4) . "',
                       INVOICETRANSACTIONTAXAMOUNT = '" . $this->model->getInvoiceTransactionTaxAmount(4) . "',
                       INVOICETRANSACTIONAMOUNT = '" . $this->model->getInvoiceTransactionAmount(4) . "',
                       ISDEFAULT = '" . $this->model->getIsDefault(0, 'single') . "',
                       ISNEW = '" . $this->model->getIsNew(0, 'single') . "',
                       ISDRAFT = '" . $this->model->getIsDraft(0, 'single') . "',
                       ISUPDATE = '" . $this->model->getIsUpdate(0, 'single') . "',
                       ISDELETE = '" . $this->model->getIsDelete(0, 'single') . "',
                       ISACTIVE = '" . $this->model->getIsActive(0, 'single') . "',
                       ISAPPROVED = '" . $this->model->getIsApproved(0, 'single') . "',
                       ISREVIEW = '" . $this->model->getIsReview(0, 'single') . "',
                       ISPOST = '" . $this->model->getIsPost(0, 'single') . "',
                       EXECUTEBY = '" . $this->model->getExecuteBy() . "',
                       EXECUTETIME = " . $this->model->getExecuteTime() . "
              WHERE COMPANYID='" . $this->getCompanyId() . "'  AND INVOICETRANSACTIONLINENUMBER = '" . $this->model->getInvoiceTransactionLineNumber(4) . "' ";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
               UPDATE `invoicetransaction`
               SET                        `countryId` = '" . $this->model->getCountryId(5) . "',
                       `chartOfAccountId` = '" . $this->model->getChartOfAccountId(5) . "',
                       `journalNumber` = '" . $this->model->getJournalNumber(5) . "',
                       `invoiceTransactionPrincipalAmount` = '" . $this->model->getInvoiceTransactionPrincipalAmount(5) . "',
                       `invoiceTransactionInterestAmount` = '" . $this->model->getInvoiceTransactionInterestAmount(5) . "',
                       `invoiceTransactionCoupunRateAmount` = '" . $this->model->getInvoiceTransactionCoupunRateAmount(5) . "',
                       `invoiceTransactionTaxAmount` = '" . $this->model->getInvoiceTransactionTaxAmount(5) . "',
                       `invoiceTransactionAmount` = '" . $this->model->getInvoiceTransactionAmount(5) . "',
                       `isDefault` = '" . $this->model->getIsDefault('0', 'single') . "',
                       `isNew` = '" . $this->model->getIsNew('0', 'single') . "',
                       `isDraft` = '" . $this->model->getIsDraft('0', 'single') . "',
                       `isUpdate` = '" . $this->model->getIsUpdate('0', 'single') . "',
                       `isDelete` = '" . $this->model->getIsDelete('0', 'single') . "',
                       `isActive` = '" . $this->model->getIsActive('0', 'single') . "',
                       `isApproved` = '" . $this->model->getIsApproved('0', 'single') . "',
                       `isReview` = '" . $this->model->getIsReview('0', 'single') . "',
                       `isPost` = '" . $this->model->getIsPost('0', 'single') . "',
                       `executeBy` = '" . $this->model->getExecuteBy() . "',
                       `executeTime` = " . $this->model->getExecuteTime() . "
               WHERE    `companyId` ='" . $this->getCompanyId() . "'
AND 	   `invoiceTransactionLineNumber` = '" . $this->model->getInvoiceTransactionLineNumber(5) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                UPDATE [invoiceTransaction] SET 
                       [countryId] = '" . $this->model->getCountryId(5) . "',
                       [invoiceId] = '" . $this->model->getInvoiceId(5) . "',
                       [chartOfAccountId] = '" . $this->model->getChartOfAccountId(5) . "',
                       [journalNumber] = '" . $this->model->getJournalNumber(5) . "',
                       [invoiceTransactionPrincipalAmount] = '" . $this->model->getInvoiceTransactionPrincipalAmount(5) . "',
                       [invoiceTransactionInterestAmount] = '" . $this->model->getInvoiceTransactionInterestAmount(5) . "',
                       [invoiceTransactionCoupunRateAmount] = '" . $this->model->getInvoiceTransactionCoupunRateAmount(5) . "',
                       [invoiceTransactionTaxAmount] = '" . $this->model->getInvoiceTransactionTaxAmount(5) . "',
                       [invoiceTransactionAmount] = '" . $this->model->getInvoiceTransactionAmount(5) . "',
                       [isDefault] = '" . $this->model->getIsDefault(0, 'single') . "',
                       [isNew] = '" . $this->model->getIsNew(0, 'single') . "',
                       [isDraft] = '" . $this->model->getIsDraft(0, 'single') . "',
                       [isUpdate] = '" . $this->model->getIsUpdate(0, 'single') . "',
                       [isDelete] = '" . $this->model->getIsDelete(0, 'single') . "',
                       [isActive] = '" . $this->model->getIsActive(0, 'single') . "',
                       [isApproved] = '" . $this->model->getIsApproved(0, 'single') . "',
                       [isReview] = '" . $this->model->getIsReview(0, 'single') . "',
                       [isPost] = '" . $this->model->getIsPost(0, 'single') . "',
                       [executeBy] = '" . $this->model->getExecuteBy() . "',
                       [executeTime] = " . $this->model->getExecuteTime() . "
                WHERE  [companyId]='" . $this->getCompanyId() . "'  AND [invoiceTransactionLineNumber] = '" . $this->model->getInvoiceTransactionLineNumber(5) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
                UPDATE INVOICETRANSACTION SET
                        COUNTRYID = '" . $this->model->getCountryId(5) . "',
                       INVOICEID = '" . $this->model->getInvoiceId(5) . "',
                       CHARTOFACCOUNTID = '" . $this->model->getChartOfAccountId(5) . "',
                       JOURNALNUMBER = '" . $this->model->getJournalNumber(5) . "',
                       INVOICETRANSACTIONPRINCIPALAMOUNT = '" . $this->model->getInvoiceTransactionPrincipalAmount(5) . "',
                       INVOICETRANSACTIONINTERESTAMOUNT = '" . $this->model->getInvoiceTransactionInterestAmount(5) . "',
                       INVOICETRANSACTIONCOUPUNRATEAMOUNT = '" . $this->model->getInvoiceTransactionCoupunRateAmount(5) . "',
                       INVOICETRANSACTIONTAXAMOUNT = '" . $this->model->getInvoiceTransactionTaxAmount(5) . "',
                       INVOICETRANSACTIONAMOUNT = '" . $this->model->getInvoiceTransactionAmount(5) . "',
                       ISDEFAULT = '" . $this->model->getIsDefault(0, 'single') . "',
                       ISNEW = '" . $this->model->getIsNew(0, 'single') . "',
                       ISDRAFT = '" . $this->model->getIsDraft(0, 'single') . "',
                       ISUPDATE = '" . $this->model->getIsUpdate(0, 'single') . "',
                       ISDELETE = '" . $this->model->getIsDelete(0, 'single') . "',
                       ISACTIVE = '" . $this->model->getIsActive(0, 'single') . "',
                       ISAPPROVED = '" . $this->model->getIsApproved(0, 'single') . "',
                       ISREVIEW = '" . $this->model->getIsReview(0, 'single') . "',
                       ISPOST = '" . $this->model->getIsPost(0, 'single') . "',
                       EXECUTEBY = '" . $this->model->getExecuteBy() . "',
                       EXECUTETIME = " . $this->model->getExecuteTime() . "
              WHERE COMPANYID='" . $this->getCompanyId() . "'  AND INVOICETRANSACTIONLINENUMBER = '" . $this->model->getInvoiceTransactionLineNumber(5) . "' ";
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
     * Delete
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