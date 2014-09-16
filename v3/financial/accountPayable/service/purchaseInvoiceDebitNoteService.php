<?php

namespace Core\Financial\AccountPayable\PurchaseInvoiceDebitNote\Service;

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
 * Class PurchaseInvoiceDebitNoteService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountPayable\PurchaseInvoiceDebitNote\Service
 * @subpackage AccountPayable
 * @link http://www.hafizan.com
 * @link http://en.wikipedia.org/wiki/Debit_note WikiPedia Debit Note
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class PurchaseInvoiceDebitNoteService extends ConfigClass {

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
    const QUOTATION = "QOTE";
    const PURCHASE_ORDER = 'PCOD';
    const TRANSFER_TO_GENERAL_LEDGER = 'TSGL';
    const RETURN_ITEM = 'CRMM';
    const GOOD_RECEIVE_NOTE = 'GDNT';
    const COMPLETE_AS_RECEIVE_ITEM = 'CMRI';
    const TRANSFER_AS_ASSET = 'TSAT';
    const TRANSFER_AS_KIT = 'TSAK';
    const ORDER_OUTSIDE_PURCHASE = 'RDROPR';
    const ORDER_REJECT = 'ORRJCT';
    const PRINT_PURCHASE_ORDER = 'PRNTPO';
    const RECEIVE_EDI_PO_ACKNOWLEDGMENT = 'RCEDIPO';
    const RECORD_SUPPLIER_ACKNOWLEDGMENT = 'RSACK';
    const RECORD_SUPPLIER_SHIPMENT = 'RCRDSHPMNT';
    const PRINT_PURCHASER_RECEIVER = 'PRPCRR';
    const RECORD_PURCHASE_RECEIPT = 'RDPR';
    const COMPLETE_READY_TO_PURGE = 'CRTP';

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
     * Upload Purchase Invoice Debit Note Attachment
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
        $this->setUploadPath($this->getFakeDocumentRoot() . "v3/financial/accountPayable/attachment/");
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
     * Return Business Partner Category
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
			WHERE       BUSINESSPARTNER.COMPANYID = '" . $this->getCompanyId() . "'
			AND			ISCREDITOR=1
			AND         BUSINESSPARTNER.ISACTIVE    						=   1";
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
     * Return Purchase Invoice
     * @param null|int $businessPartnerId Business Partner Primary Key
     * @return array|string
     */
    public function getPurchaseInvoice($businessPartnerId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      `purchaseInvoiceId`,
                        `purchaseInvoiceDescription`,
                        `purchaseInvoiceProjectTitle`
            FROM        `purchaseinvoice`
            JOIN        `purchaseinvoiceproject`
            USINg       (`companyId`,`purchaseInvoiceProjectId`)
            WHERE       `purchaseinvoice`.`isActive`  =   1
            AND         `purchaseinvoice`.`companyId` =   '" . $this->getCompanyId() . "'";
            if ($businessPartnerId) {
                $sql.=" AND `purchaseinvoice`.`businessPartnerId`='" . $businessPartnerId . "'";
            }
            $sql.="ORDER BY    `purchaseinvoice`.`isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      [purchaseInvoice].[purchaseInvoiceId],
                        [purchaseInvoice].[purchaseInvoiceDescription],
                        [purchaseInvoiceProject].[purchaseInvoiceProjectTitle]
            FROM        [purchaseInvoice]
            JOIN        [purchaseInvoiceProject]
            ON          [purchaseInvoice].[companyId] = [purchaseInvoiceProject].[companyId]
            AND         [purchaseInvoice].[purchaseInvoiceProjectId] = [purchaseInvoiceProject].[purchaseInvoiceProjectId]
            WHERE       [purchaseInvoice].[isActive]  =   1
            AND         [purchaseInvoice].[companyId] =   '" . $this->getCompanyId() . "'";
            if ($businessPartnerId) {
                $sql.=" AND [purchaseInvoice].[businessPartnerId]='" . $businessPartnerId . "'";
            }
            $sql.="
            ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      PURCHASEINVOICE.PURCHASEINVOICEID AS \"purchaseInvoiceId\",
                        PURCHASEINVOICE.PURCHASEINVOICECEDESCRIPTION AS \"purchaseInvoiceDescription\",
                        PURCHASEINVOICEPROJECT.PURCHASEINVOICEPROJECTTITLE AS \"purchaseInvoiceProjectTitle\"
            FROM        PURCHASEINVOICE
            JOIN        PURCHASEINVOICEPROJECT
            ON          PURCHASEINVOICE.COMPANYID = PURCHASEINVOICEPROJECT.COMPANYID
            AND         PURCHASEINVOICE.PURCHASEINVOICEPROJECTID = PURCHASEINVOICEPROJECT.PURCHASEINVOICEPROJECTID
            WHERE       PURCHASEINVOICE.ISACTIVE    =   1
            AND         PURCHASEINVOICE.COMPANYID   =   '" . $this->getCompanyId() . "'";
            if ($businessPartnerId) {
                $sql.=" AND PURCHASEINVOICE.BUSINESSPARTNERID='" . $businessPartnerId . "'";
            }
            $sql.="
            ORDER BY    PURCHASEINVOICE.ISDEFAULT";
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
            $purchaseInvoiceProjectTitle = null;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($d != 0) {
                    if ($purchaseInvoiceProjectTitle != $row['purchaseInvoiceProjectTitle']) {
                        $str .= "</optgroup><optgroup label=\"" . $row['purchaseInvoiceProjectTitle'] . "\">";
                    }
                } else {
                    $str .= "<optgroup label=\"" . $row['purchaseInvoiceProjectTitle'] . "\">";
                }
                $purchaseInvoiceProjectTitle = $row['purchaseInvoiceProjectTitle'];

                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['purchaseInvoiceId'] . "'>" . $d . ". " . $row['purchaseInvoiceDescription'] . "</option>";
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
        return false;
    }

    /**
     * Return Purchase Invoice Default Value
     * @return int
     */
    public function getPurchaseInvoiceDefaultValue() {
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
     * Set New Fast Business Partner.Company Address And shipping address will be same as defaulted.
     * @param string $businessPartnerCompany Company
     * @param string $businessPartnerAddress Address
     * return int $businessPartnerId
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
     * Post Purchase Invoice To General Ledger
     * @param int $purchaseInvoiceDebitNoteId
     * @param int $leafId
     * @param string $leafName
     */
    public function setPosting($purchaseInvoiceDebitNoteId, $leafId, $leafName) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  *
            FROM    `purchaseinvoicedebitnote`
            WHERE   `purchaseInvoiceDebitNoteId` IN (" . $purchaseInvoiceDebitNoteId . ")
            AND     `isPost`    =   0
            AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  *
            FROM    [purchaseInvoiceDebitNote]
            WHERE   [purchaseInvoiceDebitNoteId] IN (" . $purchaseInvoiceDebitNoteId . ")
            AND     [isPost] =0
            AND     [companyId] =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  *
            FROM    PURCHASEINVOICEDEBITNOTE
            WHERE   PURCHASEINVOICEDEBITNOTEID IN (" . $purchaseInvoiceDebitNoteId . ")
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
                $purchaseInvoiceId = $row['purchaseInvoiceId'];
                $this->setPurchaseInvoiceStatusTracking($purchaseInvoiceDebitNoteId, $purchaseInvoiceId, $this->getPurchaseInvoiceStatusId(self::TRANSFER_TO_GENERAL_LEDGER));
            }
        }
        $journalNumber = $this->getDocumentNumber('GLPT');
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  *
            FROM    `purchaseinvoicedetail`
            JOIN    `purchaseinvoicedebitnote`
            USING   (`companyId`,`purchaseInvoiceDebitNoteId`)
            WHERE   `purchaseinvoicedebitnote`.`purchaseInvoiceDebitNoteId` IN (" . $purchaseInvoiceDebitNoteId . ")
            ORDER BY `purchaseInvoiceDebitNoteId";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  *
            FROM    [purchaseInvoiceDetail]
            JOIN    [purchaseInvoiceDebitNote]
            ON      [purchaseInvoiceDetail].[companyId]         =   [purchaseInvoiceDebitNote].[companyId]
            AND     [purchaseInvoiceDetail].[purchaseInvoiceDebitNoteId] =   [purchaseInvoiceDebitNote].[purchaseInvoiceDebitNoteId]
            WHERE   [purchaseInvoiceDebitNoteId] IN (" . $purchaseInvoiceDebitNoteId . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  *
            FROM    PURCHASEINVOICEDEBITNOTENDETAIL
            JOIN    PURCHASEINVOICEDEBITNOTE
            ON      PURCHASEINVOICEDEBITNOTEDETAIL.COMPANYID         =   PURCHASEINVOICEDEBITNOTE.COMPANYID
            AND     PURCHASEINVOICEDEBITNOTEDETAIL.PURCHASEINVOICEDEBITNOTEID =   PURCHASEINVOICEDEBITNOTE.PURCHASEINVOICEDEBITNOTEID
            WHERE   PURCHASEINVOICEDEBITNOTE.PURCHASEINVOICEDEBITNOTEID IN (" . $purchaseInvoiceDebitNoteId . ")";
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
                $businessPartnerId = $row['businessPartnerId'];
                $chartOfAccountId = $row['chartOfAccountId'];
                $documentNumber = $row['documentNumber'];
                $documentDate = $row['purchaseInvoiceDate'];
                $localAmount = $row['purchaseInvoiceDetailAmount'];
                $description = $row['purchaseInvoiceDescription'];
                $module = 'AP';
                $tableName = 'purchase';
                $tableNameDetail = 'purchaseInvoiceDetail';
                $tableNameId = 'purchaseInvoiceDebitNoteId';
                $tableNameDetailId = 'purchaseInvoiceDebitNoteDetailId';
                $referenceTableNameId = $row['purchaseInvoiceDebitNoteId'];
                $referenceTableNameDetailId = $row['purchaseInvoiceDebitNoteId'];
                $purchaseInvoiceId = $row['purchaseInvoiceId'];
                $this->ledgerService->setPurchaseInvoiceLedger(
                    $businessPartnerId, $chartOfAccountId,$documentNumber, $documentDate, $localAmount, $description, $leafId, $purchaseInvoiceId, $purchaseInvoiceDebitNoteId, $purchaseInvoiceDebitNoteLedgerId = null
                );

                $this->ledgerService->setGeneralLedger($leafId, $leafName, $businessPartnerId, $chartOfAccountId, $documentNumber, $journalNumber, $documentDate, $localAmount, $description, $module, $tableName, $tableNameDetail, $tableNameId, $tableNameDetailId,$referenceTableNameId,$referenceTableNameDetailId);
            }
        }
        // make second batch for detail.. no more loop in loop
        $this->setPurchaseInvoiceDebitNotePosted($purchaseInvoiceDebitNoteId);
    }

    /**
     * Set Purchase Invoice Tracking
     * @param int $purchaseInvoiceDebitNoteId Purchase Invoice Debit Note Primary Key
     * @param int $purchaseInvoiceId Purchase Invoice Primary Key
     * @param int $purchaseInvoiceStatusId Purchase Invoice Status Primary Key
     */
    public function setPurchaseInvoiceStatusTracking($purchaseInvoiceDebitNoteId, $purchaseInvoiceId, $purchaseInvoiceStatusId) {
        $sql = null;
        $purchaseInvoiceTrackingDurationDay = 0;
        $purchaseInvoiceTrackingDurationHour = 0;
        // check if exist previous payment voucher transaction and compare with the current day.
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT DATEDIFF(current_date(),`executeTime`) AS `purchaseInvoiceTrackingDurationDay`,
                   (time_to_sec(timediff(current_date(),executeTime)) / 3600) as `purchaseInvoiceTrackingDurationHour`
            FROM   `purchaseinvoicedebitnote`
            WHERE  `purchaseInvoiceDebitNoteId` ='" . $purchaseInvoiceDebitNoteId . "'
            ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  DATEDIFF(DAY,getDate(),[executeTime]) as [purchaseInvoiceTrackingDurationDay],
                   DATEDIFF(HOUR,getDate(),[executeTime]) as [purchaseInvoiceTrackingDurationHour]
            FROM   [purchaseInvoiceDebitNote]
            WHERE  [purchaseInvoiceDebitNoteId] ='" . $purchaseInvoiceDebitNoteId . "'
            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT TRUNC(SYSDATE - to_date(EXECUTETIME,'dd-mon-yyyy')) AS \"purchaseInvoiceTrackingDurationDay\",
                   TRUNC((SYSDATE - to_date(EXECUTETIME,'dd-mon-yyyy hh24:mi')) / 24) AS \"purchaseInvoiceTrackingDurationHour\"
            FROM   PURCHASEINVOICEDEBITNOTE
            WHERE  PURCHASEINVOICEDEBITNOTEID ='" . $purchaseInvoiceDebitNoteId . "'
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
                $purchaseInvoiceTrackingDurationDay = $row['purchaseInvoiceTrackingDurationDay'];
                $purchaseInvoiceTrackingDurationHour = $row['purchaseInvoiceTrackingDurationHour'];
            }
        }

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `purchaseinvoicetracking`(
                `purchaseInvoiceTrackingId`,                    `companyId`,
                `purchaseInvoiceId`,                            `purchaseInvoiceStatusId`,
                `purchaseInvoiceTrackingDurationDay`,           `purchaseInvoiceTrackingHour`,
                `isDefault`,
                `isNew`,                                        `isDraft`,
                `isUpdate`,                                     `isDelete`,
                `isActive`,                                     `isApproved`,
                `isReview`,                                     `isPost`,
                `executeBy`,                                    `executeTime`
            ) VALUES (
                null,                                           " . $this->getCompanyId() . ",
                '" . $purchaseInvoiceId . "',                   " . $purchaseInvoiceStatusId . ",
                '" . $purchaseInvoiceTrackingDurationDay . "',  '" . $purchaseInvoiceTrackingDurationHour . "',
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
            INSERT INTO [purchaseInvoiceTracking](
                [purchaseInvoiceTrackingId],                [companyId],
                [purchaseInvoiceId],                        [purchaseInvoiceStatusId],
                [purchaseInvoiceTrackingDurationDay],       [purchaseInvoiceTrackingDurationHour],
                [isDefault],
                [isNew],                                    [isDraft],
                [isUpdate],                                 [isDelete],
                [isActive],                                 [isApproved],
                [isReview],                                 [isPost],
                [executeBy],                                [executeTime]
            ) VALUES (
                null,                                       " . $this->getCompanyId() . ",
                '" . $purchaseInvoiceId . "',               " . $purchaseInvoiceStatusId . ",
                '" . $purchaseInvoiceTrackingDurationDay . "', '" . $purchaseInvoiceTrackingDurationHour . "',
                0,
                1,                                          0,
                0,                                          0,
                1,                                          0,
                0,                                          0,
                '" . $this->getStaffId() . "',              " . $this->getExecuteTime() . ")
            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            INSERT INTO PURCHASEINVOICETRACKING (
                PURCHASEINVOICETRACKINGID,                  COMPANYID,
                PURCHASEINVOICEID,                          PURCHASEINVOICETATUSID,
                PURCHASEINVOICETRACKINGDURATIONDAY,         PURCHASEINVOICETRACKINGDURATIONHOUR,
                ISDEFAULT,
                ISNEW,                                      ISDRAFT,
                ISUPDATE,                                   ISDELETE,
                ISACTIVE,                                   ISAPPROVED,
                ISREVIEW,                                   ISPOST,
                EXECUTEBY,                                  EXECUTETIME
            ) VALUES (
                null,                                       " . $this->getCompanyId() . ",
                '" . $purchaseInvoiceId . "',               " . $purchaseInvoiceStatusId . ",
                '" . $purchaseInvoiceTrackingDurationDay . "', '" . $purchaseInvoiceTrackingDurationHour . "',
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
     *  Internal Purchase Invoice Tracking System
     * @param string $purchaseInvoiceDebitNoteStatusCode Code
     * @return int
     */
    private function getPurchaseInvoiceStatusId($purchaseInvoiceDebitNoteStatusCode) {
        $sql = null;
        $purchaseInvoiceDebitNoteStatusId = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `purchaseInvoiceStatusId`
            FROM    `purchaseinvoicestatus`
            WHERE   `purchaseInvoiceStatusCode`  =   '" . $purchaseInvoiceDebitNoteStatusCode . "'
            AND     `companyId`             =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  [purchaseInvoiceStatusId]
            FROM    [purchaseInvoiceStatus]
            WHERE   [purchaseInvoiceStatusCode]  =   '" . $purchaseInvoiceDebitNoteStatusCode . "'
            AND     [companyId]             =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  PURCHASEINVOICEDEBITNOTESTATUSID
            FROM    PURCHASEINVOICEDEBITNOTESTATUS
            WHERE   PURCHASEINVOICEDEBITNOTESTATUSCODE    =   '" . $purchaseInvoiceDebitNoteStatusCode . "'
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
            $purchaseInvoiceDebitNoteStatusId = $row['purchaseInvoiceStatusId'];
        }
        return $purchaseInvoiceDebitNoteStatusId;
    }


    /**
     * Update Purchase Invoice Debit Note Posted Flag
     * @param int $purchaseInvoiceDebitNoteId Purchase Invoice Debit Note Primary Key
     */
    private function setPurchaseInvoiceDebitNotePosted($purchaseInvoiceDebitNoteId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE  `purchaseinvoicedebitnote`
            SET     `isPost`        =  1,
                    `executeBy`     =   '" . $this->getStaffId() . "',
                    `executeTime`   =   " . $this->getExecuteTime() . "
            WHERE   `purchaseInvoiceDebitNoteId` IN (" . $purchaseInvoiceDebitNoteId . ")";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE  [purchaseInvoiceDebitNote]
            SET     [isPost]        =  1,
                    [executeBy]     =   '" . $this->getStaffId() . "',
                    [executeTime]   =   " . $this->getExecuteTime() . "
            WHERE   [purchaseInvoiceDebitNoteId] IN (" . $purchaseInvoiceDebitNoteId . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE  PURCHASEINVOICEDEBITNOTE
            SET     ISPOST              =  1,
                    EXECUTEBY           =   '" . $this->getStaffId() . "',
                    EXECUTETIME         =   " . $this->getExecuteTime() . "
            WHERE   PURCHASEINVOICEDEBITNOTEID IN (" . $purchaseInvoiceDebitNoteId . ")";
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

    /**
     * Upload Purchase Invoice Debit Note Attachment before submitting the form.
     * @throws \Exception
     * @return void
     */
    function setPurchaseInvoiceDebitNoteAttachment() {
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
        INSERT INTO `purchaseinvoicedebitnotetemp`(
             `companyId`,
             `staffId`,
             `leafId`,
             `purchaseInvoiceDebitNoteTempName`, 
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
        INSERT INTO [purchaseInvoiceDebitNoteTemp](
             [companyId],
             [staffId],
             [leafId],
             [purchaseInvoiceDebitNoteTempName],
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
           INSERT INTO PURCHASEINVOICEDEBITNOTETEMP(
             COMPANYID,
             STAFFID,
             LEAFID,
             PURCHASEINVOICEDEBITNOTETEMPNAME,
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
     * Take the last Attachment File
     * @param int $staffId Staff Primary Key
     * @param int $purchaseInvoiceDebitNoteId Purchase Invoice Debit Note Primary Key
     * @throws \Exception
     */
    function transferAttachment($staffId, $purchaseInvoiceDebitNoteId) {
        $sql=null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT      * 
			FROM        `purchaseinvoicedebitnotetemp` 
			WHERE       `isNew`=1
			AND         `staffId`='" . $staffId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      * 
			FROM        [purchaseInvoiceDebitNoteTemp]
			WHERE      [isNew]=1
			AND         [staffId]='" . $staffId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT      * 
			FROM         PURCHASEINVOICEDEBITNOTETEMP
			WHERE       ISNEW=1
			AND            STAFFID='" . $staffId . "'";
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
                $documentPath = $this->getFakeDocumentRoot() . "v3/financial/accountPayable/attachment/" . $row['purchaseInvoiceDebitNoteTempName'];
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
									'" . $row['purchaseInvoiceDebitNoteTempName'] . "',
									'" . $row['purchaseInvoiceDebitNoteTempName'] . "',
									
									'" . $documentPath . "',
									'" . $row['purchaseInvoiceDebitNoteTempName'] . "',
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
									'" . $row['purchaseInvoiceDebitNoteTempName'] . "',
									'" . $row['purchaseInvoiceDebitNoteTempName'] . "',
									
									'" . $documentPath . "',
									'" . $row['purchaseInvoiceDebitNoteTempName'] . "',
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
									'" . $row['purchaseInvoiceDebitNoteTempName'] . "',
									'" . $row['purchaseInvoiceDebitNoteTempName'] . "',
									
									'" . $documentPath . "',
									'" . $row['purchaseInvoiceDebitNoteTempName'] . "',
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
				INSERT INTO `purchaseinvoicedebitnoteattachment`(
									`invoiceDebitNoteAttachmentId`,
									`companyId`, 
									`purchaseInvoiceDebitNoteId`, 
									
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
									'" . $purchaseInvoiceDebitNoteId . "',
									
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
				INSERT INTO [purchaseInvoiceDebitNoteAttachment](
									[purchaseInvoiceDebitNoteAttachmentId],
									[companyId], 
									[purchaseInvoiceDebitNoteId], 
									
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
									'" . $purchaseInvoiceDebitNoteId . "',
									
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
				INSERT INTO PURCHASEINVOICEDEBITNOTEATTACHMENT(
									PURCHASEINVOICEDEBITNOTEATTACHMENTID,
									COMPANYID, 
									PURCHASEINVOICEDEBITNOTEID, 
									
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
									'" . $purchaseInvoiceDebitNoteId . "',
									
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
			UPDATE `purchaseinvoicedebitnotetemp`
			SET    `isNew`    = '0'
			WHERE  `staffId`        = '" . $_SESSION['staffId'] . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			UPDATE `purchaseInvoiceDebitNoteTemp`
			SET    `isNew`    = '0'
			WHERE  `staffId`        = '" . $_SESSION['staffId'] . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			UPDATE PURCHASEINVOICEDEBITNOTETEMP
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
     * Return Allowed Extension
     * @return array|string
     */
    public function getAllowedExtensions() {
        return $this->allowedExtensions;
    }

    /**
     * Set Allowed Extensions
     * @param $value
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
     * @param string $value
     * @return \Core\System\Management\Staff\Service\StaffService
     */
    public function setUploadPath($value) {
        $this->uploadPath = $value;
        return $this;
    }

}

?>