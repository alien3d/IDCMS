<?php

namespace Core\Financial\AccountPayable\PurchaseInvoice\Service;

use Core\ConfigClass;
use Core\Financial\Ledger\Service\LedgerService;
use Core\Date;

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
require_once($newFakeDocumentRoot . "library/class/classDate.php");
require_once($newFakeDocumentRoot . "v3/financial/shared/service/sharedService.php");

/**
 * Class PurchaseInvoiceService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountPayable\PurchaseInvoice\Service
 * @subpackage AccountPayable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class PurchaseInvoiceService extends ConfigClass {

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
     * Upload Purchase Invoice Attachment
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
                    $str .= "<option value='" . $row['businessPartnerId'] . "'>" . ($d + 1) . ". " . $row['businessPartnerCompany'] . "</option>";
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
     * Return Purchase Invoice Project
     * @return array|string
     */
    public function getPurchaseInvoiceProject() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `purchaseInvoiceProjectId`,
                     `purchaseInvoiceProjectDescription`
         FROM        `purchaseinvoiceproject`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [purchaseInvoiceProjectId],
                     [purchaseInvoiceProjectDescription]
         FROM        [purchaseInvoiceProject]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      PURCHASEINVOICEPROJECTID AS \"purchaseInvoiceProjectId\",
                     PURCHASEINVOICEPROJECTDESCRIPTION AS \"purchaseInvoiceProjectDescription\"
         FROM        PURCHASEINVOICEPROJECT
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['purchaseInvoiceProjectId'] . "'>" . $d . ". " . $row['purchaseInvoiceProjectDescription'] . "</option>";
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
     * Return Purchase Invoice Project Default Value
     * @return int
     */
    public function getPurchaseInvoiceProjectDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $purchaseInvoiceProjectId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `purchaseInvoiceProjectId`
         FROM        	`purchaseinvoiceproject`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [purchaseInvoiceProjectId],
         FROM        [purchaseInvoiceProject]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      PURCHASEINVOICEPROJECTID AS \"purchaseInvoiceProjectId\",
         FROM        PURCHASEINVOICEPROJECT
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
            $purchaseInvoiceProjectId = $row['purchaseInvoiceProjectId'];
        }
        return $purchaseInvoiceProjectId;
    }

    /**
     * Set New Fast Business Partner.Company Address And shipping address will be same as defaulted.
     * @param string $businessPartnerCompany
     * @param string $businessPartnerAddress
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
     * @param int $businessPartnerId Business Partner
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
     * @param int $purchaseInvoiceId Purchase Invoice Primary Key
     * @param int $leafId Leaf Primary Key
     * @param string $leafName Leaf Name
     */
    public function setPosting($purchaseInvoiceId, $leafId, $leafName) {
        $sql = null;

        $journalNumber = $this->getDocumentNumber('GLPT');
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  *
            FROM    `purchaseinvoicedetail`
            JOIN    `purchaseinvoice`
            USING   (`companyId`,`purchaseInvoiceId`)
            WHERE   `purchaseinvoice`.`purchaseInvoiceId` IN (" . $purchaseInvoiceId . ")
            ORDER BY `purchaseInvoiceId";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  *
            FROM    [purchaseInvoiceDetail]
            JOIN    [purchaseInvoice]
            ON      [purchaseInvoiceDetail].[companyId]         =   [purchaseInvoice].[companyId]
            AND     [purchaseInvoiceDetail].[purchaseInvoiceId] =   [purchaseInvoice].[purchaseInvoiceId]
            WHERE   [purchaseInvoiceId] IN (" . $purchaseInvoiceId . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  *
            FROM    PURCHASEINVOICENDETAIL
            JOIN    PURCHASEINVOICE
            ON      PURCHASEINVOICEDETAIL.COMPANYID         =   PURCHASEINVOICE.COMPANYID
            AND     PURCHASEINVOICEDETAIL.PURCHASEINVOICEID =   PURCHASEINVOICE.PURCHASEINVOICEID
            WHERE   PURCHASEINVOICE.PURCHASEINVOICEID IN (" . $purchaseInvoiceId . ")";
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
                $tableNameId = 'purchaseInvoiceId';
                $tableNameDetailId = 'purchaseInvoiceDetailId';
                $referenceTableNameId = $row['purchaseInvoiceId'];
                $referenceTableNameDetailId = $row['purchaseInvoiceDetailId'];
                $this->ledgerService->setPurchaseInvoiceLedger($businessPartnerId, $chartOfAccountId, $documentNumber, $documentDate, $localAmount, $description, $leafId, $purchaseInvoiceId, $purchaseInvoiceLedgerId = null
                );

                $this->ledgerService->setGeneralLedger($leafId, $leafName, $businessPartnerId, $chartOfAccountId, $documentNumber, $journalNumber, $documentDate, $localAmount, $description, $module, $tableName, $tableNameDetail, $tableNameId, $tableNameDetailId, $referenceTableNameId, $referenceTableNameDetailId);
            }
        }
        // make second batch for detail.. no more loop in loop
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  *
            FROM    `purchaseinvoice`
            WHERE   `purchaseInvoiceId` IN (" . $purchaseInvoiceId . ")
            AND     `isPost`    =   0
            AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  *
            FROM    [purchaseInvoice]
            WHERE   [purchaseInvoiceId] IN (" . $purchaseInvoiceId . ")
            AND     [isPost] =0
            AND     [companyId] =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  *
            FROM    PURCHASEINVOICE
            WHERE   PURCHASEINVOICEID IN (" . $purchaseInvoiceId . ")
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
                $this->setPurchaseInvoiceStatusTracking($purchaseInvoiceId, $this->getPurchaseInvoiceStatusId(self::TRANSFER_TO_GENERAL_LEDGER));
                $this->setPurchaseInvoicePosted($purchaseInvoiceId);
            }
        }
    }

    /**
     * SELECT / UPDATE / INSERT Procedure For Purchase Invoice Ledger
     * @param int $businessPartnerId Business Partner Primary Key
     * @param int $chartOfAccountId Chart Of Account Primary Key
     * @param string $documentNumber Document Number
     * @param string $purchaseInvoiceDate Date
     * @param string $purchaseInvoiceDueDate Due Date
     * @param double $purchaseInvoiceAmount Amount
     * @param string $purchaseInvoiceDescription Description
     * @param int $leafId Leaf Primary Key
     * @param null|int $purchaseInvoiceId Purchase Invoice Primary Key
     * @param null|int $purchaseInvoiceProjectId Purchase Invoice Project Primary Key
     * @param null|int $purchaseInvoiceAdjustmentId Payment Voucher Adjustment Primary Key
     * @param null|int $purchaseInvoiceDebitNoteId Payment Voucher Adjustment Primary Key
     * @param null|int $purchaseInvoiceCreditNoteId Payment Voucher Adjustment Primary Key
     * @param null|int $paymentVoucherId Payment Voucher Adjustment Primary Key
     * @return void
     */
    public function setPurchaseInvoiceLedger(
    $businessPartnerId, $chartOfAccountId, $documentNumber, $purchaseInvoiceDate, $purchaseInvoiceDueDate, $purchaseInvoiceAmount, $purchaseInvoiceDescription, $leafId, $purchaseInvoiceId, $purchaseInvoiceProjectId = null, $purchaseInvoiceAdjustmentId = null, $purchaseInvoiceDebitNoteId = null, $purchaseInvoiceCreditNoteId = null, $paymentVoucherId = null
    ) {
        $sql = null;
        $total = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `purchaseInvoiceId`
            FROM    `purchaseinvoiceledger`
            WHERE   `companyId`                     =   '" . $this->getCompanyId() . "'
            AND     `purchaseInvoiceId`             =   '" . $purchaseInvoiceId . "'
            AND     `documentNumber`                =   '" . $documentNumber . "'
            ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  [purchaseInvoiceId]
            FROM    [purchaseInvoiceLedger]
            WHERE   [companyId]                     =   '" . $this->getCompanyId() . "'
            AND     [purchaseInvoiceId]             =   '" . $purchaseInvoiceId . "'
            AND     [documentNumber]                =   '" . $documentNumber . "'
            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  PURCHASEINVOICEID
            FROM    PURCHASEINVOICELEDGER
            WHERE   COMPANYID                       =   '" . $this->getCompanyId() . "'
            AND     PURCHASEINVOICEID               =   '" . $purchaseInvoiceId . "'
            AND     DOCUMENTNUMBER                  =   '" . $documentNumber . "'
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
            $total = $this->q->numberRows($result);
        }
        if (intval($total) > 0) {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
                UPDATE  `purchaseinvoiceledger`
                SET     `businessPartnerId`            =   '" . $businessPartnerId . "',
                        `purchaseInvoiceDate`          =   '" . $purchaseInvoiceDate . "',
                        `purchaseInvoiceDueDate`       =   '" . $purchaseInvoiceDueDate . "',
                        `purchaseInvoiceAmount`        =   '" . $purchaseInvoiceAmount . "',
                        `purchaseInvoiceDescription`   =   '" . $purchaseInvoiceDescription . "',
                        `executeBy`                    =   '" . $this->getStaffId() . "',
                        `executeTime`                  =   " . $this->getExecuteTime() . "
                WHERE   `purchaseInvoiceId`            =   '" . $purchaseInvoiceId . "'
                AND     `documentNumber`               =   '" . $documentNumber . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE  [purchaseInvoiceLedger]
                SET     [businessPartnerId]        =   '" . $businessPartnerId . "',
                        [purchaseInvoiceDate]      =   '" . $purchaseInvoiceDate . "',
                        [purchaseInvoiceDueDate]   =   '" . $purchaseInvoiceDueDate . "',
                        [purchaseInvoiceAmount]           =   '" . $purchaseInvoiceAmount . "',
                        [purchaseInvoiceDescription]      =   '" . $purchaseInvoiceDescription . "',
                        [executeBy]                =   '" . $this->getStaffId() . "',
                        [executeTime]              =    " . $this->getExecuteTime() . "
                WHERE   [purchaseInvoiceId]        =   '" . $purchaseInvoiceId . "'
                AND     [documentNumber]           =   '" . $documentNumber . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
              UPDATE PURCHASEINVOICELEDGER
              SET    BUSINESSPARTNERID              =   '" . $businessPartnerId . "',
                     PURCHASEINVOICEDATE            =   '" . $purchaseInvoiceDate . "',
                     PURCHASEINVOICEMOUNT           =   '" . $purchaseInvoiceAmount . "',
                     PURCHASEINVOICEDESCRIPTION     =   '" . $purchaseInvoiceDescription . "',
                     EXECUTEBY                      =   '" . $this->getStaffId() . "',
                     EXECUTETIME                    =   " . $this->getExecuteTime() . "
              WHERE  PURCHASEINVOICEID              =   '" . $purchaseInvoiceId . "'";
            }
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
                INSERT INTO `purchaseinvoiceledger`(
                    `purchaseInvoiceLedgerId`,                  `companyId`,                            `businessPartnerId`,
                    `purchaseInvoiceProjectId`,                 `purchaseInvoiceId`,                    `purchaseInvoiceAdjustmentId`,
                    `purchaseInvoiceDebitNoteId`,                `purchaseInvoiceCreditNoteId`,          `paymentVoucherId`,
                    `documentNumber`,                           `purchaseInvoiceDate`,                  `purchaseInvoiceDueDate`,
                    `purchaseInvoiceAmount`,                    `purchaseInvoiceDescription`,
                    `purchaseInvoiceDebitNoteId`,               `purchaseInvoiceCreditNoteId`,          `paymentVoucherId`,
                    `leafId`,                                   `isDefault`,                            `isNew`,
                    `isDraft`,                                  `isUpdate`,                             `isDelete`,
                    `isActive`,                                 `isApproved`,                           `isReview`,
                    `isPost`,                                   `executeBy`,                            `executeTime`,
                    `chartOfAccountId`
                ) VALUES (
                    null,                                       '" . $this->getCompanyId(
                    ) . "',        '" . $businessPartnerId . "',
                    '" . $purchaseInvoiceProjectId . "',        '" . $purchaseInvoiceId . "',           '" . $purchaseInvoiceAdjustmentId . "',
                    '" . $documentNumber . "',                  '" . $purchaseInvoiceDate . "',         '" . $purchaseInvoiceDueDate . "',
                    '" . $purchaseInvoiceDebitNoteId . "',       '" . $purchaseInvoiceCreditNoteId . "',  '" . $paymentVoucherId . "',
                    '" . $purchaseInvoiceAmount . "',           '" . $purchaseInvoiceDescription . "',
                    '" . $purchaseInvoiceDebitNoteId . "',      '" . $purchaseInvoiceCreditNoteId . "', '" . $paymentVoucherId . "',
                    '" . $leafId . "',                          0,                                      1,
                    0,                                          0,                                      0,
                    0,                                          0,                                      0,
                    0,                                          '" . $this->getStaffId() . "',          " . $this->getExecuteTime() . ",
                    '" . $chartOfAccountId . "'
                )
                ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
                INSERT INTO [purchaseInvoiceLedger](
                    [purchaseInvoiceLedgerId],                         [companyId],                            [businessPartnerId],
                    [purchaseInvoiceProjectId],                 [purchaseInvoiceId],                    [purchaseInvoiceAdjustmentId],
                    [purchaseInvoiceDebitNoteId],               [purchaseInvoiceCreditNoteId],          [paymentVoucherId],
                    [documentNumber],                           [purchaseInvoiceDate],                  [purchaseInvoiceDueDate],
                    [purchaseInvoiceAmount],                    [purchaseInvoiceDescription],
                    [purchaseInvoiceDebitNoteId],               [purchaseInvoiceCreditNoteId],          [paymentVoucherId],
                    [leafId],                                   [isDefault],                            [isNew],
                    [isDraft],                                  [isUpdate],                             [isDelete],
                    [isActive],                                 [isApproved],                           [isReview],
                    [isPost],                                   [executeBy],                            [executeTime],
                    [chartOfAccountId]
                ) VALUES (
                    null,                                       '" . $this->getCompanyId(
                    ) . "',        '" . $businessPartnerId . "',
                    '" . $purchaseInvoiceProjectId . "',        '" . $purchaseInvoiceId . "',           '" . $purchaseInvoiceAdjustmentId . "',
                    '" . $documentNumber . "',                  '" . $purchaseInvoiceDate . "',         '" . $purchaseInvoiceDueDate . "',
                    '" . $purchaseInvoiceDebitNoteId . "',       '" . $purchaseInvoiceCreditNoteId . "',  '" . $paymentVoucherId . "',
                    '" . $purchaseInvoiceAmount . "',           '" . $purchaseInvoiceDescription . "',
                    '" . $purchaseInvoiceDebitNoteId . "',      '" . $purchaseInvoiceCreditNoteId . "', '" . $paymentVoucherId . "',
                    '" . $leafId . "',                          0,                                      1,
                    0,                                          0,                                      0,
                    0,                                          0,                                      0,
                    0,                                          '" . $this->getStaffId() . "',          " . $this->getExecuteTime() . ",
                    '" . $chartOfAccountId . "'
                )
                ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            INSERT INTO PURCHASEINVOICELEDGER(
                    PURCHASEINVOICELEDGERID,                    COMPANYID,                            BUSINESSPARTNERID,
                    PURCHASEINVOICEPROJECTID,                 PURCHASEINVOICEID,                    PURCHASEINVOICEADJUSTMENTID,
                    DOCUMENTNUMBER,                           PURCHASEINVOICEDATE,                  PURCHASEINVOICEDUEDATE,
                    PURCHASEINVOICEAMOUNT,                    PURCHASEINVOICEDESCRIPTION,
                    PURCHASEINVOICEDEBITNOTEID,               PURCHASEINVOICECREDITNOTEID,          PAYMENTVOUCHERID,
                    LEAFID,                                   ISDEFAULT,                            ISNEW,
                    ISDRAFT,                                  ISUPDATE,                             ISDELETE,
                    ISACTIVE,                                 ISAPPROVED,                           ISREVIEW,
                    ISPOST,                                   EXECUTEBY,                            EXECUTETIME,
                    CHARTOFACCOUNTID
     ) VALUES (
                    null,                                       '" . $this->getCompanyId(
                    ) . "',        '" . $businessPartnerId . "',
                    '" . $purchaseInvoiceProjectId . "',        '" . $purchaseInvoiceId . "',           '" . $purchaseInvoiceAdjustmentId . "',
                    '" . $documentNumber . "',                  '" . $purchaseInvoiceDate . "',         '" . $purchaseInvoiceDueDate . "',
                    '" . $purchaseInvoiceDebitNoteId . "',       '" . $purchaseInvoiceCreditNoteId . "',  '" . $paymentVoucherId . "',
                    '" . $purchaseInvoiceAmount . "',           '" . $purchaseInvoiceDescription . "',
                    '" . $purchaseInvoiceDebitNoteId . "',      '" . $purchaseInvoiceCreditNoteId . "', '" . $paymentVoucherId . "',
                    '" . $leafId . "',                          0,                                      1,
                    0,                                          0,                                      0,
                    0,                                          0,                                      0,
                    0,                                          '" . $this->getStaffId() . "',          " . $this->getExecuteTime() . ",
                    '" . $chartOfAccountId . "'
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
    }

    /**
     * Set Purchase Invoice Tracking
     * @param int $purchaseInvoiceId Purchase Invoice Primary Key
     * @param int $purchaseInvoiceStatusId Purchase Invoice Status Primary Key
     * @return void
     */
    public function setPurchaseInvoiceStatusTracking($purchaseInvoiceId, $purchaseInvoiceStatusId) {
        $sql = null;
        $purchaseInvoiceTrackingDurationDay = 0;
        $purchaseInvoiceTrackingDurationHour = 0;
        // check if exist previous payment voucher transaction and compare with the current day.
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT DATEDIFF(current_date(),`executeTime`) AS `purchaseInvoiceTrackingDurationDay`,
                   (time_to_sec(timediff(current_date(),executeTime)) / 3600) as `purchaseInvoiceTrackingDurationHour`
            FROM   `purchaseinvoice`
            WHERE  `purchaseInvoiceId` ='" . $purchaseInvoiceId . "'
            ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT DATEDIFF(DAY,getDate(),[executeTime]) as [purchaseInvoiceTrackingDurationDay],
                   DATEDIFF(DAY,getDate(),[executeTime]) as [purchaseInvoiceTrackingDurationHour]
            FROM   [purchaseInvoice]
            WHERE  [purchaseInvoiceId] ='" . $purchaseInvoiceId . "'
            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT TRUNC(SYSDATE - to_date(EXECUTETIME,'dd-mon-yyyy')) AS \"purchaseInvoiceTrackingDurationDay\",
                   TRUNC((SYSDATE - to_date(EXECUTETIME,'dd-mon-yyyy hh24:mi')) / 24) AS \"purchaseInvoiceTrackingDurationHour\"
            FROM   PURCHASEINVOICE
            WHERE  PURCHASEINVOICEID ='" . $purchaseInvoiceId . "'
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
     * @param string $purchaseInvoiceStatusCode
     * @return int
     */
    private function getPurchaseInvoiceStatusId($purchaseInvoiceStatusCode) {
        $sql = null;
        $purchaseInvoiceStatusId = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `purchaseInvoiceStatusId`
            FROM    `purchaseinvoicestatus`
            WHERE   `purchaseInvoiceStatusCode`  =   '" . $purchaseInvoiceStatusCode . "'
            AND     `companyId`             =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  [purchaseInvoiceStatusId]
            FROM    [purchaseInvoiceStatus]
            WHERE   [purchaseInvoiceStatusCode]  =   '" . $purchaseInvoiceStatusCode . "'
            AND     [companyId]             =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  PURCHASEINVOICESTATUSID
            FROM    PURCHASEINVOICESTATUS
            WHERE   PURCHASEINVOICESTATUSCODE    =   '" . $purchaseInvoiceStatusCode . "'
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
            $purchaseInvoiceStatusId = $row['purchaseInvoiceStatusId'];
        }
        return $purchaseInvoiceStatusId;
    }

    /**
     * Update Purchase Invoice Posted Flag
     * @param int $purchaseInvoiceId
     */
    private function setPurchaseInvoicePosted($purchaseInvoiceId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE  `purchaseinvoice`
            SET     `isPost`        =  1,
                    `executeBy`     =   '" . $this->getStaffId() . "',
                    `executeTime`   =   " . $this->getExecuteTime() . "
            WHERE   `purchaseInvoiceId` IN (" . $purchaseInvoiceId . ")";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE  [purchaseInvoice]
            SET     [isPost]        =  1,
                    [executeBy]     =   '" . $this->getStaffId() . "',
                    [executeTime]   =   " . $this->getExecuteTime() . "
            WHERE   [purchaseInvoiceId] IN (" . $purchaseInvoiceId . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE  PURCHASEINVOICE
            SET     ISPOST              =  1,
                    EXECUTEBY           =   '" . $this->getStaffId() . "',
                    EXECUTETIME         =   " . $this->getExecuteTime() . "
            WHERE   PURCHASEINVOICEID IN (" . $purchaseInvoiceId . ")";
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

    /**
     * Upload Purchase Invoice Attachment before submitting the form.
     * @throws \Exception
     * @return void
     */
    function setPurchaseInvoiceAttachment() {
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
        INSERT INTO `purchaseinvoicetemp`(
             `companyId`,
             `staffId`,
             `leafId`,
             `purchaseInvoiceTempName`, 
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
        INSERT INTO [purchaseInvoiceTemp](
             [companyId],
             [staffId],
             [leafId],
             [purchaseInvoiceTempName],
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
           INSERT INTO PURCHASEINVOICETEMP(
             COMPANYID,
             STAFFID,
             LEAFID,
             PURCHASEINVOICETEMPNAME,
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
     * @param int $purchaseInvoiceId Purchase Invoice Primary Key
     * @throws \Exception
     */
    function transferAttachment($staffId, $purchaseInvoiceId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT      * 
			FROM        `purchaseinvoicetemp` 
			WHERE       `isNew`=1
			AND         `staffId`='" . $staffId . "'
			ORDER BY    `imageTempId` DESC
			LIMIT        1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      * 
			FROM        [purchaseInvoiceTemp]
			WHERE      [isNew]=1
			AND         [staffId]='" . $staffId . "'
			ORDER BY    [imageTempId] DESC
			LIMIT        1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT      * 
			FROM         PURCHASEINVOICETEMP
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
                $documentPath = $this->getFakeDocumentRoot() . "v3/financial/accountPayable/attachment/" . $row['purchaseInvoiceTempName'];
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
									'" . $row['purchaseInvoiceTempName'] . "',
									'" . $row['purchaseInvoiceTempName'] . "',
									
									'" . $documentPath . "',
									'" . $row['purchaseInvoiceTempName'] . "',
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
									'" . $row['purchaseInvoiceTempName'] . "',
									'" . $row['purchaseInvoiceTempName'] . "',
									
									'" . $documentPath . "',
									'" . $row['purchaseInvoiceTempName'] . "',
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
									'" . $row['purchaseInvoiceTempName'] . "',
									'" . $row['purchaseInvoiceTempName'] . "',
									
									'" . $documentPath . "',
									'" . $row['purchaseInvoiceTempName'] . "',
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
				INSERT INTO `purchaseinvoiceattachment`(
									`invoiceAttachmentId`,
									`companyId`, 
									`purchaseInvoiceId`, 
									
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
									'" . $purchaseInvoiceId . "',
									
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
				INSERT INTO [purchaseInvoiceAttachment](
									[purchaseInvoiceAttachmentId],
									[companyId], 
									[purchaseInvoiceId], 
									
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
									'" . $purchaseInvoiceId . "',
									
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
				INSERT INTO PURCHASEINVOICEATTACHMENT(
									PURCHASEINVOICEATTACHMENTID,
									COMPANYID, 
									PURCHASEINVOICEID, 
									
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
									'" . $purchaseInvoiceId . "',
									
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
			UPDATE `purchaseinvoicetemp`
			SET    `isNew`    = '0'
			WHERE  `staffId`        = '" . $_SESSION['staffId'] . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			UPDATE `purchaseInvoiceTemp`
			SET    `isNew`    = '0'
			WHERE  `staffId`        = '" . $_SESSION['staffId'] . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			UPDATE PURCHASEINVOICETEMP
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
     * Return Total PurchaseInvoice Day.
     * @param int $purchasePurchaseInvoiceId PurchaseInvoice Primary Key
     * @return int $totalPurchaseInvoiceTrackingDay Total Day
     * @throw exception
     */
    private function getTotalPurchaseInvoiceTrackingDay($purchasePurchaseInvoiceId) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $totalPurchaseInvoiceTrackingDay = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      SUM(`purchasePurchaseInvoiceTrackingDurationDay`) AS `totalPurchaseInvoiceTrackingDay`
            FROM        `purchasePurchaseInvoicetracking`
            WHERE       `isActive`  =   1
            AND         `companyId` =   '" . $this->getCompanyId() . "'
            AND    	  `purchasePurchaseInvoiceId` =	  '" . $purchasePurchaseInvoiceId . "'
            GROUP BY   `purchasePurchaseInvoiceId`";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      SUM([purchasePurchaseInvoiceTrackingDurationDay]) AS [totalPurchaseInvoiceTrackingDay]
            FROM        [purchasePurchaseInvoiceTracking]
            WHERE       [isActive]  =   1
            AND         [companyId] =   '" . $this->getCompanyId() . "'
            AND    	    [purchasePurchaseInvoiceId] =	  '" . $purchasePurchaseInvoiceId . "'
            GROUP BY    [purchasePurchaseInvoiceId] ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      SUM(PURCHASEINVOICETRACKINGDURATIONDAY) AS \"totalPurchaseInvoiceTrackingDay\"
            FROM         PURCHASEINVOICETRACKING
            WHERE       ISACTIVE  =   1
            AND          COMPANYID =   '" . $this->getCompanyId() . "'
            AND    	    PURCHASEINVOICEID =	  '" . $purchasePurchaseInvoiceId . "'
            GROUP BY    PURCHASEINVOICEID ";
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
            $totalPurchaseInvoiceTrackingDay = (int) $row['totalPurchaseInvoiceTrackingDay'];
        }
        return $totalPurchaseInvoiceTrackingDay;
    }

    /**
     * Return Total PurchaseInvoice Hour.
     * @param int $purchasePurchaseInvoiceId PurchaseInvoice Primary Key
     * @return int $totalPurchaseInvoiceTrackingHour Total Day
     * @depreciated  Save it as emergency
     * @throw exception
     */
    private function getTotalPurchaseInvoiceTrackingHour($purchasePurchaseInvoiceId) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $totalPurchaseInvoiceTrackingHour = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      SUM(`purchasePurchaseInvoiceTrackingDurationHour`) AS `totalPurchaseInvoiceTrackingHour`
            FROM        `purchasePurchaseInvoicetracking`
            WHERE       `isActive`  =   1
            AND         `companyId` =   '" . $this->getCompanyId() . "'
            AND    	  `purchasePurchaseInvoiceId` =	  '" . $purchasePurchaseInvoiceId . "'
            GROUP BY   `purchasePurchaseInvoiceId`";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      SUM([purchasePurchaseInvoiceTrackingDurationDay]) AS [totalPurchaseInvoiceTrackingHour]
            FROM        [purchasePurchaseInvoiceTracking]
            WHERE       [isActive]  =   1
            AND         [companyId] =   '" . $this->getCompanyId() . "'
            AND    	    [purchasePurchaseInvoiceId] =	  '" . $purchasePurchaseInvoiceId . "'
            GROUP BY    [purchasePurchaseInvoiceId] ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      SUM(PURCHASEINVOICETRACKINGDURATIONHOUR) AS \"totalPurchaseInvoiceTrackingHour\"
            FROM         PURCHASEINVOICETRACKING
            WHERE       ISACTIVE  =   1
            AND          COMPANYID =   '" . $this->getCompanyId() . "'
            AND    	    PURCHASEINVOICEID =	  '" . $purchasePurchaseInvoiceId . "'
            GROUP BY    PURCHASEINVOICEID ";
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
            $totalPurchaseInvoiceTrackingHour = $row['totalPurchaseInvoiceTrackingHour'];
        }
        return $totalPurchaseInvoiceTrackingHour;
    }

    /**
     * Return Total Tracking Holiday
     * @param int $purchasePurchaseInvoiceId PurchaseInvoice Primary Key
     * @return int $totalHoliday Total Holiday
     */
    private function getTotalTrackingHolidayPurchaseInvoice($purchasePurchaseInvoiceId) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $totalHoliday = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      count(*) AS total
            FROM        `leaveholidays`
            WHERE       `isActive`  =   1
            AND         `companyId` =   '" . $this->getCompanyId() . "'
            AND    	    `purchasePurchaseInvoiceId` =	  '" . $purchasePurchaseInvoiceId . "'
            AND        `leaveHolidaysDate` BETWEEN (
                                                           (
                                                               SELECT MIN(purchasePurchaseInvoiceTrackingDate)
                                                               FROM   `purchasePurchaseInvoicetracking`
                                                               WHERE  `companyId`   =   '" . $this->getCompanyId() . "'
                                                               AND    `purchasePurchaseInvoiceId`=   '" . $purchasePurchaseInvoiceId . "'
                                                           )
                                                        AND
                                                           (
                                                               SELECT MAX(purchasePurchaseInvoiceTrackingDate)
                                                               FROM   `purchasePurchaseInvoicetracking`
                                                               WHERE  `companyId`   =   '" . $this->getCompanyId() . "'
                                                               AND    `purchasePurchaseInvoiceId`=   '" . $purchasePurchaseInvoiceId . "'
                                                           )
                                                    )
            AND        `isNational` =   1
            AND        `isState`    =   1
            AND        `isWeekend`  =   1

            GROUP BY   `purchasePurchaseInvoiceId`";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      count(*) AS [totalHoliday]
            FROM       [leaveHolidays]
            WHERE      [isActive]  =   1
            AND        [companyId] =   '" . $this->getCompanyId() . "'
            AND    	   [purchasePurchaseInvoiceId] =	  '" . $purchasePurchaseInvoiceId . "'
            AND        [leaveHolidaysDate` BETWEEN (
                                                           (
                                                               SELECT MIN(purchasePurchaseInvoiceTrackingDate)
                                                               FROM   [purchasePurchaseInvoiceTracking]
                                                               WHERE  [companyId]   =   '" . $this->getCompanyId() . "'
                                                               AND    [purchasePurchaseInvoiceId]=   '" . $purchasePurchaseInvoiceId . "'
                                                           )
                                                        AND
                                                           (
                                                               SELECT MAX([purchasePurchaseInvoiceTrackingDate])
                                                               FROM   [purchasePurchaseInvoiceTracking]
                                                               WHERE  [companyId]   =   '" . $this->getCompanyId() . "'
                                                               AND    [purchasePurchaseInvoiceId]=   '" . $purchasePurchaseInvoiceId . "'
                                                           )
                                                    )
            AND        [isNational] =   1
            AND        [isState]    =   1
            AND        [isWeekend]  =   1

            GROUP BY   [purchasePurchaseInvoiceId]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      COUNT(*) AS \"totalHoliday\"
            FROM       LEAVEHOLIDAYS
            WHERE      ISACTIVE  =   1
            AND        COMPANYID =   '" . $this->getCompanyId() . "'
            AND    	   PURCHASEINVOICEID =	  '" . $purchasePurchaseInvoiceId . "'
            AND        LEAVEHOLIDAYSDATE (BETWEEN
                                                           (
                                                               SELECT MIN(PURCHASEINVOICETRACKINGDATE)
                                                               FROM   PURCHASEINVOICETRACKING
                                                               WHERE  COMPANYID     =   '" . $this->getCompanyId() . "'
                                                               AND    PURCHASEINVOICEID  =   '" . $purchasePurchaseInvoiceId . "'
                                                           )
                                                        AND
                                                           (
                                                               SELECT MAX(PURCHASEINVOICETRACKINGDATE)
                                                               FROM   PURCHASEINVOICETRACKING
                                                               WHERE  COMPANYID     =   '" . $this->getCompanyId() . "'
                                                               AND    PURCHASEINVOICEID  =   '" . $purchasePurchaseInvoiceId . "'
                                                           )
                                                    )
            AND        ISNATIONAL =   1
            AND        ISSTATE    =   1
            AND        ISWEEKEND  =   1

            GROUP BY   PURCHASEINVOICEID";
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
     * Return Setup Tracking PurchaseInvoice Warning Day
     * @return int $purchasePurchaseInvoiceTrackingWarningDay Setup Tracking PurchaseInvoice Warning Day
     */
    private function getTrackingPurchaseInvoiceWarningDay() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $purchasePurchaseInvoiceTrackingWarningDay = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `purchasePurchaseInvoiceTrackingWarningDay`
            FROM `tracking`
            WHERE `companyId`='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  [purchasePurchaseInvoiceTrackingWarningDay]
            FROM [tracking]
            WHERE [companyId]='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  PURCHASEINVOICETRACKINGWARNINGDAY
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
            $purchasePurchaseInvoiceTrackingWarningDay = $row['purchasePurchaseInvoiceTrackingWarningDay'];
        }
        return $purchasePurchaseInvoiceTrackingWarningDay;
    }

    /**
     * Return Setup Tracking PurchaseInvoice Warning Hour
     * @return int $purchasePurchaseInvoiceTrackingWarningHour Setup Tracking PurchaseInvoice Warning Hour
     */
    private function getTrackingPurchaseInvoiceWarningHour() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $purchasePurchaseInvoiceTrackingWarningHour = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `purchasePurchaseInvoiceTrackingWarningHour`
            FROM `tracking`
            WHERE `companyId`='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  `purchasePurchaseInvoiceTrackingWarningHour`
            FROM `tracking `
            WHERE `companyId`='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  PURCHASEINVOICETRACKINGWARNINGHOUR
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
            $purchasePurchaseInvoiceTrackingWarningHour = $row['purchasePurchaseInvoiceTrackingWarningHour'];
        }
        return $purchasePurchaseInvoiceTrackingWarningHour;
    }

    /**
     * Return Tracking PurchaseInvoice By Day
     * @param int $purchasePurchaseInvoiceId PurchaseInvoice Primary Key
     * @return int|bool
     */
    public function getTrackingWarningStatusPurchaseInvoiceByDay($purchasePurchaseInvoiceId) {
        if ($this->getTotalPurchaseInvoiceTrackingDay($purchasePurchaseInvoiceId) - $this->getTotalTrackingHolidayPurchaseInvoice($purchasePurchaseInvoiceId) > $this->getTrackingPurchaseInvoiceWarningDay()) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Return Tracking PurchaseInvoice By Day
     * @param int $purchasePurchaseInvoiceId PurchaseInvoice Primary Key
     * @return int|bool
     */
    public function getTrackingWarningStatusPurchaseInvoiceByHour($purchasePurchaseInvoiceId) {
        if ($this->getTotalPurchaseInvoiceTrackingHour($purchasePurchaseInvoiceId) - ($this->getTotalTrackingHolidayPurchaseInvoice($purchasePurchaseInvoiceId) * 24) > $this->getTrackingPurchaseInvoiceWarningHour()) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Return Tracking PurchaseInvoice By Day
     * @param int $purchasePurchaseInvoiceId PurchaseInvoice Primary Key
     * @return int
     */
    public function getTrackingPurchaseInvoiceByDay($purchasePurchaseInvoiceId) {
        return (int) $this->getTotalPurchaseInvoiceTrackingDay($purchasePurchaseInvoiceId) - $this->getTotalTrackingHolidayPurchaseInvoice($purchasePurchaseInvoiceId);
    }

    /**
     * Return Tracking PurchaseInvoice By Day
     * @param int $purchasePurchaseInvoiceId PurchaseInvoice Primary Key
     * @return int
     */
    public function getTrackingPurchaseInvoiceByHour($purchasePurchaseInvoiceId) {
        return (int) $this->getTotalPurchaseInvoiceTrackingHour($purchasePurchaseInvoiceId) - ($this->getTotalTrackingHolidayPurchaseInvoice($purchasePurchaseInvoiceId) * 24);
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

class PurchaseInvoiceInterestService extends ConfigClass {

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
    private $d;

    const NORMAL_YEAR = 365;
    const LEAP_YEAR = 366;

    function __construct() {
        $this->d = new Date\DateClass();
    }

    /**
     * Create
     * @see config::create()
     * @return void
     */
    public function create() {
        
    }

    /**
     * Calculate Coupun Rate based on 
     * Interest} = {Principal} * {CouponRate} * {Factor} 
     */
    function getCoupunRate($principal, $interest, $date1, $date2, $type = null) {
        $factor = $this->getFactor($date1, $date2, $type);
        $coupunRate = $interest / $principal * $factor;
        return $coupunRate;
    }

    /**
     * 
     * @link
     * @param type $date1
     * @param type $date2
     * @param type $type
     * @return type
     */
    function getFactor($date1, $date2, $type = null) {
        $startYear = substr($date1, 0, 4);

        $totalYear = $this->d->getCountYear($date1, $date2);
        if ($totalYear > 1) {
            $d = 0;
            for ($i = $startYear; $i < count($totalYear); $i++) {
                // check if were leap year or not
                if ($this->d->getStatusleapYear($startYear)) {
                    $year[] = LEAP_YEAR;
                } else {
                    $year[] = NORMAL_YEAR;
                }
                $d++;
            }
            if ($d == 2) {
                $factor = $this->d->getCountDays($date1, substr($date1, 0, 4) + "01-01") / $year[0] + $this->d->getCountDays(substr($date2, 0, 4) + "01-01", $date2) / $year[1];
            } else {
                $loopArrayMiddle = 0;
                for ($i = $startYear + 1; $i < $totalYear - 2; $i++) {
                    if ($this->d->getStatusleapYear($startYear)) {
                        $loopArrayMiddle+=LEAP_YEAR;
                    } else {
                        $loopArrayMiddle+=NORMAL_YEAR;
                    }
                }
                $factor = $this->d->getCountDays($date1, substr($date1, 0, 4) + "01-01") / $year[0] + ( $loopArrayMiddle) + $this->d->getCountDays(substr($date2, 0, 4) + "01-01", $date2) / $year[1];
            }
        } else {

            if ($this->d->getStatusleapYear($startYear)) {
                $factor = $this->d->getCountDays($date1, $date2) / LEAP_YEAR;
            } else {
                $factor = $this->d->getCountDays($date1, $date2) / NORMAL_YEAR;
            }
        }
        return $factor;
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