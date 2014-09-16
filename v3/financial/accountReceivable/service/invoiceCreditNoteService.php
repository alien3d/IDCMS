<?php

namespace Core\Financial\AccountReceivable\InvoiceCreditNote\Service;

use Core\ConfigClass;
use Core\Financial\Ledger\Service\LedgerService;

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
require_once($newFakeDocumentRoot . "v3/financial/shared/service/sharedService.php");

/**
 * Class InvoiceCreditNoteService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountReceivable\InvoiceCreditNote\Service
 * @subpackage AccountReceivable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class InvoiceCreditNoteService extends ConfigClass {

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
     * Upload Invoice Credit Note Attachment
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
							[businessPartner].[businessPartnerRegistrationNumber],
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
							BUSINESSPARTNER.BUSINESSPARTNERREGISTRATIONNUMBER,
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
     * Return Invoice
     * @param  null|int $businessPartnerId Business Partner Primary Key
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
     * Set New Fast Business Partner.Company Address And shipping address will be same as defaulted.
     * @param string $businessPartnerCompany Company/Name
     * @param string $businessPartnerAddress Address
     * return int $businessPartnerId Business Partner Primary key
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
        if ($this->getVendor() == self::MYSQL) {
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
        } else if ($this->getVendor() == self::MSSQL) {
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
        } else if ($this->getVendor() == self::ORACLE) {
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
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
        $countryId = 0;
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
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
        $stateId = 0;
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
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
     * Return Business Partner Shipping City Default Value
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
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
     * Post Invoice credit Note To General Ledger
     * @param int $invoiceCreditNoteId Invoice Credit Note Primary Key
     * @param int $leafId Leaf Primary Key
     * @param string $leafName Leaf Name
     */
    public function setPosting($invoiceCreditNoteId, $leafId, $leafName) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  *
            FROM    `invoicecreditnote`
            WHERE   `invoiceCreditNoteId` IN (" . $invoiceCreditNoteId . ")
			AND		`isActive`= 1
            AND     `isPost`    =   0
            AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  *
            FROM    [invoiceCreditNote]
            WHERE   [invoiceCreditNoteId] IN (" . $invoiceCreditNoteId . ")
            AND		[isActive]= 1
			AND     [isPost] =0
            AND     [companyId] =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  *
            FROM    INVOICECREDITNOTE
            WHERE   INVOICECREDITNOTEID IN (" . $invoiceCreditNoteId . ")
			AND    ISACTIVE = 1
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
                $invoiceCreditNoteId = $row['invoiceCreditNoteId'];
                $this->setInvoiceStatusTracking(
                        $invoiceCreditNoteId, $invoiceId, $this->getInvoiceStatusId(self::TRANSFER_TO_GENERAL_LEDGER)
                );
            }
        }
        $journalNumber = $this->getDocumentNumber('GLPT');
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  *
            FROM    `invoicecreditnotedetail`
            JOIN    `invoicecreditnote`
            USING   (`companyId`,`invoiceCreditNoteId`)
            WHERE   `invoicecreditnote`.`invoiceCreditNoteId` IN (" . $invoiceCreditNoteId . ")
            ORDER BY `invoiceCreditNoteId";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  *
            FROM    [InvoiceCreditNoteDetail]
            JOIN    [invoiceCreditNote]
            ON      [invoiceCreditNoteDetail].[companyId]         =   [invoiceCreditNote].[companyId]
            AND     [invoiceCreditNoteDetail].[invoiceCreditNoteId] =   [invoiceCreditNote].[invoiceCreditNoteId]
            WHERE   [invoiceCreditNoteId] IN (" . $invoiceCreditNoteId . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  *
            FROM    INVOICECREDITNOTENDETAIL
            JOIN    INVOICECREDITNOTE
            ON      INVOICECREDITNOTEDETAIL.COMPANYID         =   INVOICECREDITNOTE.COMPANYID
            AND     INVOICECREDITNOTEDETAIL.INVOICECREDITNOTEID =   INVOICECREDITNOTE.INVOICECREDITNOTEID
            WHERE   INVOICECREDITNOTE.INVOICECREDITNOTEID IN (" . $invoiceCreditNoteId . ")";
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
                $businessPartnerId = $row['businessPartnerId'];
                $chartOfAccountId = $row['chartOfAccountId'];
                $documentNumber = $row['documentNumber'];
                $documentDate = $row['invoiceCreditNoteDate'];
                $localAmount = $row['invoiceCreditNoteDetailAmount'];
                $description = $row['invoiceCreditNoteDescription'];
                $module = 'AR';
                $tableName = 'invoiceCreditNote';
                $tableNameDetail = 'invoiceCreditNoteDetail';
                $tableNameId = 'invoiceCreditNoteId';
                $tableNameDetailId = 'invoiceCreditNoteDetailId';
                $referenceTableNameId = $row['invoiceCreditNoteId'];
                $referenceTableNameDetailId = $row['invoiceCreditNoteDetailId'];

                // special field
                $invoiceProjectId = $row['invoiceProjectId'];
                // null field
                $invoiceDueDate = null;
                $invoiceAdjustmentId = null;
                $invoiceDebitNoteId = null;
                $collectionId = null;

                $this->ledgerService->setInvoiceLedger($businessPartnerId, $chartOfAccountId, $documentNumber, $documentDate, $invoiceDueDate, $description, $localAmount, $leafId, $invoiceId, $invoiceProjectId, $invoiceAdjustmentId, $invoiceDebitNoteId, $invoiceCreditNoteId, $collectionId
                );

                $this->ledgerService->setGeneralLedger($leafId, $leafName, $businessPartnerId, $chartOfAccountId, $documentNumber, $journalNumber, $documentDate, $localAmount, $description, $module, $tableName, $tableNameDetail, $tableNameId, $tableNameDetailId, $referenceTableNameId, $referenceTableNameDetailId);
            }
        }
        // make second batch for detail.. no more loop in loop
        $this->setInvoiceCreditNotePosted($invoiceCreditNoteId);
    }

    /**
     * Set Invoice Tracking
     * @param int $invoiceCreditNoteId Credit Note Primary Key
     * @param int $invoiceId Invoice Primary Key
     * @param int $invoiceStatusId Status Primary Key
     */
    public function setInvoiceStatusTracking($invoiceCreditNoteId, $invoiceId, $invoiceStatusId) {
        $sql = null;
        $invoiceTrackingDurationDay = 0;
        $invoiceTrackingDurationHour = 0;
        // check if exist previous payment voucher transaction and compare with the current day.
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT DATEDIFF(current_date(),`executeTime`) AS `invoiceTrackingDurationDay`,
                   (time_to_sec(timediff(current_date(),executeTime)) / 3600) as `invoiceTrackingDurationHour`
            FROM   `invoicecreditnote`
            WHERE  `invoiceCreditNoteId` ='" . $invoiceCreditNoteId . "'
            ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT DATEDIFF(DAY,getDate(),[executeTime]) as [invoiceTrackingDurationDay],
                   DATEDIFF(HOUR,getDate(),[executeTime]) as [invoiceTrackingDurationHour]
            FROM   [invoiceCreditNote]
            WHERE  [invoiceCreditNoteId] ='" . $invoiceCreditNoteId . "'
            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT TRUNC(SYSDATE - to_date(EXECUTETIME,'dd-mon-yyyy')) AS \"invoiceTrackingDurationDay\",
                   TRUNC((SYSDATE - to_date(EXECUTETIME,'dd-mon-yyyy hh24:mi')) / 24) AS \"invoiceTrackingDurationHour\"
            FROM   INVOICECREDITNOTE
            WHERE  INVOICECREDITNOTEID ='" . $invoiceCreditNoteId . "'
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
     * @param string $invoiceStatusCode Code
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
     * Upload Invoice Credit Note Attachment before submitting the form.
     * @throws \Exception
     * @return void
     */
    function setInvoiceCreditNoteAttachment() {
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
        INSERT INTO `invoicecreditnotetemp`(
             `companyId`,
             `staffId`,
             `leafId`,
             `invoiceCreditNoteTempName`, 
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
        INSERT INTO [invoiceCreditNoteTemp](
             [companyId],
             [staffId],
             [leafId],
             [invoiceCreditNoteTempName],
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
           INSERT INTO INVOICECREDITNOTETEMP(
             COMPANYID,
             STAFFID,
             LEAFID,
             INVOICECREDITNOTETEMPNAME,
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
     * @param int $invoiceCreditNoteId Invoice Credit Note Primary Key
     * @throws \Exception
     */
    function transferAttachment($staffId, $invoiceCreditNoteId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT      * 
			FROM        `invoicecreditnotetemp` 
			WHERE       `isNew`=1
			AND         `staffId`='" . $staffId . "'
			ORDER BY    `imageTempId` DESC
			LIMIT        1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      * 
			FROM        [invoiceCreditNoteTemp]
			WHERE      [isNew]=1
			AND         [staffId]='" . $staffId . "'
			ORDER BY    [imageTempId] DESC
			LIMIT        1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT      * 
			FROM         INVOICECREDITNOTETEMP
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
                $documentPath = $this->getFakeDocumentRoot() . "v3/financial/accountReceivable/attachment/" . $row['invoiceCreditNoteTempName'];
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
									'" . $row['invoiceCreditNoteTempName'] . "',
									'" . $row['invoiceCreditNoteTempName'] . "',
									
									'" . $documentPath . "',
									'" . $row['invoiceCreditNoteTempName'] . "',
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
									'" . $row['invoiceCreditNoteTempName'] . "',
									'" . $row['invoiceCreditNoteTempName'] . "',
									
									'" . $documentPath . "',
									'" . $row['invoiceCreditNoteTempName'] . "',
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
									'" . $row['invoiceCreditNoteTempName'] . "',
									'" . $row['invoiceCreditNoteTempName'] . "',
									
									'" . $documentPath . "',
									'" . $row['invoiceCreditNoteTempName'] . "',
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
                $documentId = $this->q->lastInsertId("documentAttachment");
                // insert into invoice attachment image table
                if ($this->getVendor() == self::MYSQL) {
                    $sql = "
				INSERT INTO `invoicecreditnoteattachment`(
									`invoiceCreditNoteAttachmentId`,
									`companyId`, 
									`invoiceCreditNoteId`, 
									
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
									'" . $invoiceCreditNoteId . "',
									
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
				INSERT INTO [invoiceCreditNoteAttachment](
									[invoiceCreditNoteAttachmentId],
									[companyId], 
									[invoiceCreditNoteId], 
									
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
									'" . $invoiceCreditNoteId . "',
									
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
				INSERT INTO INVOICECREDITNOTEATTACHMENT(
									INVOICECREDITNOTEATTACHMENTID,
									COMPANYID, 
									INVOICECREDITNOTEID, 
									
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
									'" . $invoiceCreditNoteId . "',
									
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
			UPDATE `invoicecreditnotetemp`
			SET    `isNew`    = '0'
			WHERE  `staffId`        = '" . $_SESSION['staffId'] . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			UPDATE [invoiceCreditNoteTemp]
			SET    [isNew]    = '0'
			WHERE  [staffId]        = '" . $_SESSION['staffId'] . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			UPDATE INVOICECREDITNOTETEMP
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
     * @param string $attachmentCode Code
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
     * Update Invoice Posted Flag
     * @param int $invoiceCreditNoteId Invoice Credit Note Primary Key
     */
    private function setInvoiceCreditNotePosted($invoiceCreditNoteId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE  `invoicecreditnote`
            SET     `isPost`        =  1,
                    `executeBy`     =   '" . $this->getStaffId() . "',
                    `executeTime`   =   " . $this->getExecuteTime() . "
            WHERE   `invoiceCreditNoteId` IN (" . $invoiceCreditNoteId . ")";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE  [invoiceCreditNote]
            SET     [isPost]        =  1,
                    [executeBy]     =   '" . $this->getStaffId() . "',
                    [executeTime]   =   " . $this->getExecuteTime() . "
            WHERE   [invoiceCreditNoteId] IN (" . $invoiceCreditNoteId . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE  INVOICECREDITNOTE
            SET     ISPOST              =  1,
                    EXECUTEBY           =   '" . $this->getStaffId() . "',
                    EXECUTETIME         =   " . $this->getExecuteTime() . "
            WHERE   INVOICECREDITNOTEID IN (" . $invoiceCreditNoteId . ")";
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
     * @param int $value
     * @return \Core\System\Management\Staff\Service\StaffService
     */
    public function setSizeLimit($value) {
        $this->sizeLimit = $value;
        return $this;
    }

    /**
     * Return Upload PAth
     * @return string
     */
    public function getUploadPath() {
        return $this->uploadPath;
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