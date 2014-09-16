<?php

namespace Core\Financial\Cashbook\PaymentVoucher\Service;

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
 * Class PaymentVoucherService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version    2
 * @author     hafizan
 * @package    Core\Financial\Cashbook\PaymentVoucher\Service
 * @subpackage Cashbook
 * @link       http://www.hafizan.com
 * @license    http://www.gnu.org/copyleft/lesser.html LGPL
 */
class PaymentVoucherService extends ConfigClass {

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
    const NEW_PAYMENT_VOUCHER = 'CTVC';
    const PRINT_PAYMENT_VOUCHER = 'PTVC';
    const CHEQUE_NUMBER = 'PTCN';
    const PRINT_CHEQUE_NUMBER = 'PTCQ';
    const TRANSFER_TO_GL = 'TSGL';
    const CANCEL_PAYMENT_VOUCHER = 'PYSL';

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
        if ($_SESSION['staffId']) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            // fall back to default database if anything wrong
            $this->setStaffId(1);
        }
        if ($_SESSION['countryCurrencyCode']) {
            $this->setCountryCurrencyCode($_SESSION['countryCurrencyCode']);
        } else {
            $this->setCountryCurrencyCode('ms-My');
        }
      
    }

    /**
     * Class Loader
     */
    public function execute() {
        parent::__construct();
        if ($_SESSION['companyId']) {
            $this->setCompanyId($_SESSION['companyId']);
        } else {
            // fall back to default database if anything wrong
            $this->setCompanyId(1);
        }
		 $this->ledgerService = new LedgerService();
		$this->ledgerService->q = $this->q;
		$this->ledgerService->t = $this->t;
		$this->ledgerService->execute();
    }

    /**
     * Return Bank
     * @return array|string
     */
    public function getBank() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `bankId`,
                     `bankDescription`
         FROM        `bank`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [bankId],
                     [bankDescription]
         FROM        [bank]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      BANKID AS \"bankId\",
                     BANKDESCRIPTION AS \"bankDescription\"
         FROM        BANK
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
                    $str
                            .=
                            "<option value='" . $row['bankId'] . "'>" . $d . ". " . $row['bankDescription'] . "</option>";
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
     * Return Bank Default Value
     * @return int
     */
    public function getBankDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $bankId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `bankId`
         FROM        	`bank`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [bankId],
         FROM        [bank]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      BANKID AS \"bankId\",
         FROM        BANK
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
            $bankId = $row['bankId'];
        }
        return $bankId;
    }

    /**
     * Return Bank Default Value
     * @return int
     */
    public function getPettyCashDefaultAccount() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $chartOfAccountId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `chartOfAccountId`
         FROM        	`bank`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isPettyCash` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [chartOfAccountId],
         FROM        [bank]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isPettyCash] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      CHARTOFACCOUNTID AS \"chartOfAccountId\",
         FROM        BANK
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         AND    	  	  ISPETTYCASH=	   1
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
     * Return Bank Default Value
     * @return int
     */
    public function getExpensesDefaultAccount() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $chartOfAccountId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `chartOfAccountId`
         FROM        `chartOfAccount`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	 `isDefaultExpenses` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      TOP 1 [chartOfAccountId],
			FROM        [chartOfAccount]
			WHERE       [isActive]  			=   1
			AND			[companyId] 			=	'" . $this->getCompanyId() . "'
			AND			isDefaultExpenses]	=	1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT	CHARTOFACCOUNTID
			FROM	CHARTOFACCOUNT
			WHERE	ISACTIVE`  =   1
			AND		COMPANYID` =  '" . $this->getCompanyId() . "'
			AND		ISDEFAULTEXPENSES	=	   1
			AND		ROWNUM	  			=	   1";
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
     * Return Bank Default Value
     * @return int
     */
    public function getCreditorDefaultAccount() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $chartOfAccountId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT  `chartOfAccountId`
			FROM	`chartOfAccount`
			WHERE	`isActive`  =   1
			AND		`companyId` =   '" . $this->getCompanyId() . "'
			AND		`isCreditor` =	  1
			LIMIT 1";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT  TOP 1
					[chartOfAccountId],
			FROM	[chartOfAccount]
			WHERE	[isActive]		=   1
			AND		[companyId]		=	'" . $this->getCompanyId() . "'
			AND		[isCreditor]	=	1";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT	CHARTOFACCOUNTID
			FROM	CHARTOFACCOUNT
			WHERE	ISACTIVE`  	=   1
			AND		COMPANYID` 	=  	'" . $this->getCompanyId() . "'
			AND		ISCREDITOR	=	1
			AND		ROWNUM	  	=	1";
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
     * Return BusinessPartnerCategory
     * @return array|string
     */
    public function getBusinessPartnerCategory() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `businessPartnerCategoryId`,
                     `businessPartnerCategoryDescription`
         FROM        `businesspartnercategory`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [businessPartnerCategoryId],
                     [businessPartnerCategoryDescription]
         FROM        [businessPartnerCategory]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      BUSINESSPARTNERCATEGORYID AS \"businessPartnerCategoryId\",
                     BUSINESSPARTNERCATEGORYDESCRIPTION AS \"businessPartnerCategoryDescription\"
         FROM        BUSINESSPARTNERCATEGORY
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
                    $str .= "<option value='" . $row['businessPartnerCategoryId'] . "'>" . $d . ". "
                            . $row['businessPartnerCategoryDescription'] . "</option>";
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
     * Return BusinessPartnerCategory Default Value
     * @return int
     */
    public function getBusinessPartnerCategoryDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $businessPartnerCategoryId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `businessPartnerCategoryId`
         FROM        	`businesspartnercategory`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [businessPartnerCategoryId],
         FROM        [businessPartnerCategory]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      BUSINESSPARTNERCATEGORYID AS \"businessPartnerCategoryId\",
         FROM        BUSINESSPARTNERCATEGORY
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
            $businessPartnerCategoryId = $row['businessPartnerCategoryId'];
        }
        return $businessPartnerCategoryId;
    }

    /**
     * Return Business Partner
     * @param null|int $businessPartnerCategoryId Business Partner Primary Key
     * @return array|string
     * @throws \Exception
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
			AND         `businesspartner`.`companyId` =   '" . $this->getCompanyId() . "'";
            if ($businessPartnerCategoryId) {
                $sql .= " AND `businesspartner`.`businessPartnerCategoryId`='" . $businessPartnerCategoryId . "'";
            }
            $sql .= "
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
			AND         [businessPartner].[companyId] 							=   '" . $this->getCompanyId() . "'";
            if ($businessPartnerCategoryId) {
                $sql .= " AND [businessPartner].[businessPartnerCategoryId]='" . $businessPartnerCategoryId . "'";
            }
            $sql .= "
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
			WHERE       BUSINESSPARTNER.ISACTIVE 							=	1
			AND         BUSINESSPARTNER.COMPANYID 							=   '" . $this->getCompanyId() . "'";
            if ($businessPartnerCategoryId) {
                $sql .= " AND BUSINESSPARTNER.BUSINESSPARTNERCATEGORYID='" . $businessPartnerCategoryId . "'";
            }
            $sql .= "
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
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [businessPartnerId],
         FROM        [businessPartner]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else
        if ($this->getVendor() == self::ORACLE) {
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
     * Return Payment Type
     * @return array|string
     */
    public function getPaymentType() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `paymentTypeId`,
                     `paymentTypeDescription`
         FROM        `paymenttype`
         WHERE       `isActive`  	=   1
         AND         `companyId` 	=   '" . $this->getCompanyId() . "'
		 AND         `isCollection`	=   1
         ORDER BY    `paymentTypeDescription`;";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [paymentTypeId],
                     [paymentTypeDescription]
         FROM        [paymentType]
         WHERE       [isActive]  	=   1
         AND         [companyId] 	=   '" . $this->getCompanyId() . "'
		 AND		 [isCollection]	=   1
         ORDER BY    [paymentTypeDescription]";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      PAYMENTTYPEID AS \"paymentTypeId\",
                     PAYMENTTYPEDESCRIPTION AS \"paymentTypeDescription\"
         FROM        PAYMENTTYPE
         WHERE       ISACTIVE    	=   1
         AND         COMPANYID   	=   '" . $this->getCompanyId() . "'
		 AND		 ISCOLLECTION	=   1
         ORDER BY    PAYMENTTYPEDESCRIPTION";
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
                    $str .= "<option value='" . $row['paymentTypeId'] . "'>" . $d . ". "
                            . $row['paymentTypeDescription'] . "</option>";
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
     * Return Payment Type Default Value
     * @return int
     */
    public function getPaymentTypeDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $paymentTypeId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `paymentTypeId`
         FROM        	`paymenttype`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [paymentTypeId],
         FROM        [paymentType]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      PAYMENTTYPEID AS \"paymentTypeId\",
         FROM        PAYMENTTYPE
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
            $paymentTypeId = $row['paymentTypeId'];
        }
        return $paymentTypeId;
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
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [chartOfAccountId],
         FROM        [chartOfAccount]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else
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
     * UPDATE / INSERT Procedure For Purchase Invoice
     * @param int $businessPartnerId
     * @param string $documentNumber
     * @param string $purchaseInvoiceDate
     * @param string $purchaseInvoiceDescription
     * @param double $purchaseInvoiceAmount
     * @param null $purchaseInvoiceId
     * @return int|null
     * @throws \Exception
     */
    public function setPurchaseInvoice(
    $businessPartnerId, $documentNumber, $purchaseInvoiceDate, $purchaseInvoiceDescription, $purchaseInvoiceAmount, $purchaseInvoiceId = null
    ) {
        $sql = null;
        if (intval($purchaseInvoiceId) > 0) {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
                UPDATE  `purchaseinvoice`
                SET     `businessPartnerId`             =   '" . $businessPartnerId . "',
                        `purchaseInvoiceAmount`         =   '" . $purchaseInvoiceAmount . "',
                        `purchaseInvoiceDate`           =   '" . $purchaseInvoiceDate . "',
                        `purchaseInvoiceDescription`    =   '" . $purchaseInvoiceDescription . "',
                        `isNew`                         =   0,
                        `isUpdate`                      =   1,
                        `executeBy`                     =   '" . $this->getStaffId() . "',
                        `executeTime`                   =   " . $this->getExecuteTime() . "
                WHERE   `purchaseInvoiceId`             =   '" . $purchaseInvoiceId . "'";
            } else
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE  [purchaseInvoice]
                SET     [businessPartnerId]             =   '" . $businessPartnerId . "',
                        [purchaseInvoiceAmount]         =   '" . $purchaseInvoiceAmount . "',
                        [purchaseInvoiceDate]           =   '" . $purchaseInvoiceDate . "',
                        [purchaseInvoiceDescription]    =   '" . $purchaseInvoiceDescription . "',
                        [isNew]                         =   0,
                        [isUpdate]                      =   1,
                        [executeBy]                     =   '" . $this->getStaffId() . "',
                        [executeTime]                   =   " . $this->getExecuteTime() . "
                WHERE   [purchaseInvoiceId]             =   '" . $purchaseInvoiceId . "'";
            } else
            if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE  PURCHASEINVOICE
                SET     BUSINESSPARTNERID             =   '" . $businessPartnerId . "',
                        PURCHASEINVOICEAMOUNT         =   '" . $purchaseInvoiceAmount . "',
                        PURCHASEINVOICEDATE           =   '" . $purchaseInvoiceDate . "',
                        PURCHASEINVOICEDESCRIPTION    =   '" . $purchaseInvoiceDescription . "',
                        ISNEW                         =   0,
                        ISUPDATE                      =   1,
                        EXECUTEBY                     =   '" . $this->getStaffId() . "',
                        EXECUTETIME                   =   " . $this->getExecuteTime() . "
                WHERE   PURCHASEINVOICEID             =   '" . $purchaseInvoiceId . "'";
            }

            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
                INSERT INTO `purchaseinvoice`
                        (
                            `companyId`,
                            `businessPartnerId`,
                            `purchaseInvoiceProjectId`,
                            `documentNumber`,
                            `referenceNumber`,
                            `purchaseInvoiceAmount`,
                            `purchaseInvoiceDate`,
                            `purchaseInvoiceCreditTerm`,
                            `purchaseInvoiceDescription`,
                            `isDefault`,
                            `isNew`,
                            `isDraft`,
                            `isUpdate`,
                            `isDelete`,
                            `isActive`,
                            `isApproved`,
                            `isReview`,
                            `isPost`,
                            `isAllocated`,
                            `executeBy`,
                            `executeTime`
                        ) VALUES (
                            '" . $this->getCompanyId() . "',
                            '" . $businessPartnerId . "',
                            '" . $this->getPurchaseInvoiceProjectDefaultValue() . "',
                            '" . $documentNumber . "',
                            ' ',
                            '" . $purchaseInvoiceAmount . "',
                            '" . $purchaseInvoiceDate . "',
                            0,
                            '" . $purchaseInvoiceDescription . "',
                            0,
                            1,
                            0,
                            0,
                            0,
                            1,
                            0,
                            0,
                            0,
                            1,
                            '" . $this->getStaffId() . "',
                            " . $this->getExecuteTime() . ")";
            } else
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
                INSERT INTO `purchaseinvoice`
                        (
                            `purchaseInvoiceId`,
                            `companyId`,
                            `businessPartnerId`,
                            `purchaseInvoiceProjectId`,
                            `documentNumber`,
                            `referenceNumber`,
                            `purchaseInvoiceAmount`,
                            `purchaseInvoiceDate`,
                            `purchaseInvoiceCreditTerm`,
                            `purchaseInvoiceDescription`,
                            `isDefault`,
                            `isNew`,
                            `isDraft`,
                            `isUpdate`,
                            `isDelete`,
                            `isActive`,
                            `isApproved`,
                            `isReview`,
                            `isPost`,
                            `isAllocated`,
                            `executeBy`,
                            `executeTime`
                        ) VALUES (
                            null,
                            '" . $this->getCompanyId() . "',
                            '" . $businessPartnerId . "',
                            '" . $this->getPurchaseInvoiceProjectDefaultValue() . "',
                            '" . $documentNumber . "',
                            ' ',
                            '" . $purchaseInvoiceAmount . "',
                            '" . $purchaseInvoiceDate . "',
                            0,
                            '" . $purchaseInvoiceDescription . "',
                            0,
                            1,
                            0,
                            0,
                            0,
                            1,
                            0,
                            0,
                            0,
                            1,
                            '" . $this->getStaffId() . "',
                            " . $this->getExecuteTime() . ")";
            } else
            if ($this->getVendor() == self::ORACLE) {
                $sql = "
                INSERT INTO `purchaseinvoice`
                        (
                            `purchaseInvoiceId`,
                            `companyId`,
                            `businessPartnerId`,
                            `purchaseInvoiceProjectId`,
                            `documentNumber`,
                            `referenceNumber`,
                            `purchaseInvoiceAmount`,
                            `purchaseInvoiceDate`,
                            `purchaseInvoiceCreditTerm`,
                            `purchaseInvoiceDescription`,
                            `isDefault`,
                            `isNew`,
                            `isDraft`,
                            `isUpdate`,
                            `isDelete`,
                            `isActive`,
                            `isApproved`,
                            `isReview`,
                            `isPost`,
                            `isAllocated`,
                            `executeBy`,
                            `executeTime`
                        ) VALUES (
                            null,
                            '" . $this->getCompanyId() . "',
                            '" . $businessPartnerId . "',
                            '" . $this->getPurchaseInvoiceProjectDefaultValue() . "',
                            '" . $documentNumber . "',
                            ' ',
                            '" . $purchaseInvoiceAmount . "',
                            '" . $purchaseInvoiceDate . "',
                            0,
                            '" . $purchaseInvoiceDescription . "',
                            0,
                            1,
                            0,
                            0,
                            0,
                            1,
                            0,
                            0,
                            0,
                            1,
                            '" . $this->getStaffId() . "',
                            " . $this->getExecuteTime() . ")";
            }

            try {
                $this->q->create($sql);
            } catch (\Exception $e) {
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
            $purchaseInvoiceId = $this->q->lastInsertId('purchaseInvoice');
            //echo "aaa".$purchaseInvoiceId;
        }
        return $purchaseInvoiceId;
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
            FROM        `purchaseinvoiceproject`
            WHERE       `isActive`  =   1
            AND         `companyId` =   '" . $this->getCompanyId() . "'
            AND    	    `isDefault` =	  1
            LIMIT 1";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      TOP 1 [purchaseInvoiceProjectId],
            FROM        [purchaseInvoiceProject]
            WHERE       [isActive]  =   1
            AND         [companyId] =   '" . $this->getCompanyId() . "'
            AND    	    [isDefault] =   1";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      PURCHASEINVOICEPROJECTID AS \"purchaseInvoiceProjectId\",
            FROM        PURCHASEINVOICEPROJECT
            WHERE       ISACTIVE    =   1
            AND         COMPANYID   =   '" . $this->getCompanyId() . "'
            AND    	    ISDEFAULT	=	   1
            AND 		ROWNUM	    =	   1";
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
            $purchaseInvoiceProjectId = $row['purchaseInvoiceProjectId'];
        }
        return $purchaseInvoiceProjectId;
    }

    /**
     * Return Purchase Invoice Adjustment Default Value
     * @return int
     */
    public function getPurchaseInvoiceAdjustmentDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $purchaseInvoiceAdjustmentId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      `purchaseInvoiceAdjustmentId`
            FROM        `purchaseinvoiceproject`
            WHERE       `isActive`  =   1
            AND         `companyId` =   '" . $this->getCompanyId() . "'
            AND    	    `isDefault` =	  1
            LIMIT 1";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      TOP 1 [purchaseInvoiceAdjustmentId],
            FROM        [purchaseInvoiceAdjustment]
            WHERE       [isActive]  =   1
            AND         [companyId] =   '" . $this->getCompanyId() . "'
            AND    	    [isDefault] =   1";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      PURCHASEINVOICEADJUSTMENTID AS \"purchaseInvoiceAdjustmentId\",
            FROM        PURCHASEINVOICEADJUSTMENT
            WHERE       ISACTIVE    =   1
            AND         COMPANYID   =   '" . $this->getCompanyId() . "'
            AND    	    ISDEFAULT	=	   1
            AND 		ROWNUM	    =	   1";
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
            $purchaseInvoiceAdjustmentId = $row['purchaseInvoiceAdjustmentId'];
        }
        return $purchaseInvoiceAdjustmentId;
    }

    /**
     * Set Payment Voucher Detail
     * @param int $paymentVoucherId Payment Voucher
     * @param int $businessPartnerId Business Partner / Customer / Vendor / Shipper
     * @param int $chartOfAccountId Chart Of Account
     * @param float $paymentVoucherDetailAmount Amount
     * @param string $documentNumber Document Number
     * @param string $journalNumber Journal / Posting Number
     * @param null $paymentVoucherDetailId Primary Key
     * @return int|null
     * @throws \Exception
     */
    public function setPaymentVoucherDetail(
    $paymentVoucherId, $businessPartnerId, $chartOfAccountId, $paymentVoucherDetailAmount, $documentNumber, $journalNumber, $paymentVoucherDetailId = null
    ) {
        $sql = null;
        if (intval($paymentVoucherDetailId) > 0) {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
				UPDATE 	`paymentvoucherdetail`
				SET 	`businessPartnerId`				=	'" . $businessPartnerId . "',
						`chartOfAccountId`				=	'" . $chartOfAccountId . "',
						`paymentVoucherDetailAmount`	=	'" . $paymentVoucherDetailAmount . "',
						`isNew`							=	0,
						`isUpdate`						=	1,
						`executeBy`						=	'" . $this->getStaffId() . "',
						`executeTime`					=	" . $this->getExecuteTime() . "
				WHERE 	`paymentVoucherDetailId`		=	'" . $paymentVoucherDetailId . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
				UPDATE 	[paymentVoucherDetail]
				SET 	[businessPartnerId]				=	'" . $businessPartnerId . "',
						[chartOfAccountId]				=	'" . $chartOfAccountId . "',
						[paymentVoucherDetailAmount]	=	'" . $paymentVoucherDetailAmount . "',
						[isNew]							=	0,
						[isUpdate]						=	1,
						[executeBy]						=	'" . $this->getStaffId() . "',
						[executeTime]					=	" . $this->getExecuteTime() . "
				WHERE 	[paymentVoucherDetailId]		=	'" . $paymentVoucherDetailId . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
				UPDATE 	PAYMENTVOUCHERDETAIL
				SET 	BUSINESSPARTNERID				=	'" . $businessPartnerId . "',
						CHARTOFACCOUNTID				=	'" . $chartOfAccountId . "',
						PAYMENTVOUCHERDETAILAMOUNT		=	'" . $paymentVoucherDetailAmount . "',
						ISNEW							=	0,
						ISUPDATE						=	1,
						EXECUTEBY						=	'" . $this->getStaffId() . "',
						EXECUTETIME						=	" . $this->getExecuteTime() . "
				WHERE 	PAYMENTVOUCHERDETAILID			=	'" . $paymentVoucherDetailId . "'";
            }
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
				 INSERT INTO `paymentvoucherdetail`(
				 `paymentVoucherDetailId`, 				`companyId`,
				 `paymentVoucherId`, 					`businessPartnerId`,
				 `chartOfAccountId`, 					`countryId`,
				 `documentNumber`, 						`journalNumber`,
				 `paymentVoucherDetailAmount`, 			`isDefault`,
				 `isNew`, 								`isDraft`,
				 `isUpdate`, 							`isDelete`,
				 `isActive`, 							`isApproved`,
				 `isReview`, 							`isPost`,
				 `executeBy`, 							`executeTime`
				) VALUES (
					null,								'" . $this->getCompanyId() . "',
					'" . $paymentVoucherId . "',			'" . $businessPartnerId . "',
					'" . $chartOfAccountId . "',			'" . $this->ledgerService->getCountryId() . "',
					'" . $documentNumber . "',				'" . $journalNumber . "',
					'" . $paymentVoucherDetailAmount . "',	0,
					1,									0,
					0,									0,
					1,									0,
					0,									0,
					'" . $this->getStaffId() . "',			" . $this->getExecuteTime() . ")
        ";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
				 INSERT INTO [paymentVoucherDetail](
				 [paymentVoucherDetailId], 				[companyId],
				 [paymentVoucherId], 					[businessPartnerId],
				 [chartOfAccountId], 					[countryId],
				 [documentNumber], 						[journalNumber],
				 [paymentVoucherDetailAmount], 			[isDefault],
				 [isNew], 								[isDraft],
				 [isUpdate], 							[isDelete],
				 [isActive], 							[isApproved],
				 [isReview], 							[isPost],
				 [executeBy], 							[executeTime]
				) VALUES (
					null,								'" . $this->getCompanyId() . "',
					'" . $paymentVoucherId . "',			'" . $businessPartnerId . "',
					'" . $chartOfAccountId . "',			'" . $this->ledgerService->getCountryId() . "',
					'" . $documentNumber . "',				'" . $journalNumber . "',
					'" . $paymentVoucherDetailAmount . "',	0,
					1,									0,
					0,									0,
					1,									0,
					0,									0,
					'" . $this->getStaffId() . "',			" . $this->getExecuteTime() . ")
        ";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
				 INSERT INTO PAYMENTVOUCHERDETAIL(
				 PAYMENTVOUCHERDETAILID, 				COMPANYID,
				 PAYMENTVOUCHERID, 					BUSINESSPARTNERID,
				 CHARTOFACCOUNTID, 					COUNTRYID,
				 DOCUMENTNUMBER, 						JOURNALNUMBER,
				 PAYMENTVOUCHERDETAILAMOUNT, 			ISDEFAULT,
				 ISNEW, 								ISDRAFT,
				 ISUPDATE, 							ISDELETE,
				 ISACTIVE, 							ISAPPROVED,
				 ISREVIEW, 							ISPOST,
				 EXECUTEBY, 							EXECUTETIME
				) VALUES (
					null,								'" . $this->getCompanyId() . "',
					'" . $paymentVoucherId . "',			'" . $businessPartnerId . "',
					'" . $chartOfAccountId . "',			'" . $this->ledgerService->getCountryId() . "',
					'" . $documentNumber . "',				'" . $journalNumber . "',
					'" . $paymentVoucherDetailAmount . "',	0,
					1,									0,
					0,									0,
					1,									0,
					0,									0,
					'" . $this->getStaffId() . "',			" . $this->getExecuteTime() . ")
        ";
            }
            try {
                $this->q->create($sql);
            } catch (\Exception $e) {
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
            $paymentVoucherDetailId = $this->q->lastInsertId('paymentVourcherDetail');
        }
        return $paymentVoucherDetailId;
    }

    /**
     * Set Purchase Invoice Detail
     * @param int $purchaseInvoiceId
     * @param int $businessPartnerId
     * @param int $chartOfAccountId
     * @param float $purchaseInvoiceDetailAmount
     * @param string $documentNumber
     * @param string $journalNumber
     * @param null|int $purchaseInvoiceDetailId
     * @return int|null
     * @throws \Exception
     */
    public function setPurchaseInvoiceDetail(
    $purchaseInvoiceId, $businessPartnerId, $chartOfAccountId, $purchaseInvoiceDetailAmount, $documentNumber, $journalNumber, $purchaseInvoiceDetailId = null
    ) {
        $sql = null;

        if (intval($purchaseInvoiceDetailId) > 0) {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
				UPDATE 	`purchaseinvoicedetail`
				SET 	`chartOfAccountId`				=	'" . $chartOfAccountId . "',
						`purchaseInvoiceDetailAmount`	=	'" . $purchaseInvoiceDetailAmount . "',
						`isNew`							=	0,
						`isActive`						=	1,
						`executeBy`						=	'" . $this->getStaffId() . "',
						`executeTime`					=	" . $this->getExecuteTime() . "
				WHERE 	`purchaseInvoiceDetailId`		=	'" . $purchaseInvoiceDetailId . "'";
            } else
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
				UPDATE 	[purchaseInvoiceDetail]
				SET 	[chartOfAccountId]				=	'" . $chartOfAccountId . "',
						[purchaseInvoiceDetailAmount]	=	'" . $purchaseInvoiceDetailAmount . "',
						[isNew]							=	0,
						[isActive]						=	1,
						[executeBy]						=	'" . $this->getStaffId() . "',
						[executeTime]					=	" . $this->getExecuteTime() . "
				WHERE 	[purchaseInvoiceDetailId]		=	'" . $purchaseInvoiceDetailId . "'";
            } else
            if ($this->getVendor() == self::ORACLE) {
                $sql = "
				UPDATE 	PURCHASEINVOICEDETAIL
				SET 	CHARTOFACCOUNTID				=	'" . $chartOfAccountId . "',
						PURCHASEINVOICEDETAILAMOUNT		=	'" . $purchaseInvoiceDetailAmount . "',
						ISNEW							=	0,
						ISACTIVE						=	1,
						EXECUTEBY						=	'" . $this->getStaffId() . "',
						EXECUTETIME						=	" . $this->getExecuteTime() . "
				WHERE 	PURCHASEINVOICEDETAILID			=	'" . $purchaseInvoiceDetailId . "'";
            }

            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
				INSERT INTO `purchaseinvoicedetail`(
							`purchaseInvoiceDetailId`,								`companyId`,
							`purchaseInvoiceProjectId`, 							`purchaseInvoiceId`,
							`countryId`, 											`chartOfAccountId`,
							`journalNumber`, 										`purchaseInvoiceDetailAmount`,
							`isDefault`, 											`isNew`,
							`isDraft`, 												`isUpdate`,
							`isDelete`, 											`isActive`,
							`isApproved`, 											`isReview`,
							`isPost`, 												`executeBy`,
							`executeTime`,                                          `businessPartnerId`,
							`documentNumber`
				) VALUES (
							null,													'" . $this->getCompanyId() . "',
							'" . $this->getPurchaseInvoiceProjectDefaultValue() . "',	'" . $purchaseInvoiceId . "',
							'" . $this->ledgerService->getCountryId() . "',							'" . $chartOfAccountId . "',
							'" . $journalNumber . "',									'"
                        . $purchaseInvoiceDetailAmount . "',
							0,														1,
							0,														0,
							0,														0,
							0,														1,
							0,														'" . $this->getStaffId() . "',
							" . $this->getExecuteTime() . ",                            '" . $businessPartnerId . "',
							'" . $documentNumber . "');";
            } else
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
				INSERT INTO [purchaseInvoiceDetail](
							[purchaseInvoiceDetailId],								[companyId],
							[purchaseInvoiceProjectId], 							[purchaseInvoiceId],
							[countryId], 											[chartOfAccountId],
							[journalNumber], 										[purchaseInvoiceDetailAmount],
							[isDefault], 											[isNew],
							[isDraft], 												[isUpdate],
							[isDelete], 											[isActive],
							[isApproved], 											[isReview],
							[isPost], 												[executeBy],
							[executeTime],                                          [businessPartnerId],
							[documentNumber]
				) VALUES (
							null,													'" . $this->getCompanyId() . "',
							'" . $this->getPurchaseInvoiceProjectDefaultValue() . "',	'" . $purchaseInvoiceId . "',
							'" . $this->ledgerService->getCountryId() . "',							'" . $chartOfAccountId . "',
							'" . $journalNumber . "',									'"
                        . $purchaseInvoiceDetailAmount . "',
							0,														1,
							0,														0,
							0,														0,
							0,														1,
							0,														'" . $this->getStaffId() . "',
							" . $this->getExecuteTime() . ",                            '" . $businessPartnerId . "',
							'" . $documentNumber . "');";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
				INSERT INTO PURCHASEINVOICEDETAIL(
							PURCHASEINVOICEDETAILID,								COMPANYID,
							PURCHASEINVOICEPROJECTID, 								PURCHASEINVOICEID,
							COUNTRYID, 												CHARTOFACCOUNTID,
							JOURNALNUMBER, 											PURCHASEINVOICEDETAILAMOUNT,
							ISDEFAULT, 												ISNEW,
							ISDRAFT, 												ISUPDATE,
							ISDELETE, 												ISACTIVE,
							ISAPPROVED, 											ISREVIEW,
							ISPOST, 												EXECUTEBY,
							EXECUTETIME,                                            BUSINESSPARTNERID,
							DOCUMENTNUMBER
				) VALUES (
							null,													'" . $this->getCompanyId() . "',
							'" . $this->getPurchaseInvoiceProjectDefaultValue() . "',	'" . $purchaseInvoiceId . "',
							'" . $this->ledgerService->getCountryId() . "',							'" . $chartOfAccountId . "',
							'" . $journalNumber . "',									'"
                        . $purchaseInvoiceDetailAmount . "',
							0,														1,
							0,														0,
							0,														0,
							0,														1,
							0,														'" . $this->getStaffId() . "',
							" . $this->getExecuteTime() . ",                            '" . $businessPartnerId . "',
							'" . $documentNumber . "');";
            }

            try {
                $this->q->create($sql);
            } catch (\Exception $e) {
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
            $purchaseInvoiceDetailId = $this->q->lastInsertId('purchaseInvoiceDetail');
        }
        return $purchaseInvoiceDetailId;
    }

    /**
     * Set Payment Voucher Allocation
     * @param int $paymentVoucherId Payment Voucher
     * @param int $purchaseInvoiceId Purchase Invoice
     * @param int $businessPartnerId Business Partner
     * @param float $paymentVoucherAllocationAmount Amount / Figure
     * @param null|int $paymentVoucherAllocationId Primary Key
     * @return null|void
     * @throws \Exception
     */
    public function setPaymentVoucherAllocation($paymentVoucherId, $purchaseInvoiceId, $businessPartnerId, $paymentVoucherAllocationAmount, $paymentVoucherAllocationId = null) {
        $sql = null;
        if (intval($paymentVoucherAllocationId) > 0) {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
				UPDATE 	`paymentvoucherallocation`
				SET    	`paymentVoucherAllocationAmount`	=	'" . $paymentVoucherAllocationAmount . "',
						`isNew`								=	0,
						`isUpdate`							=	1,
						`executeBy`							=	'" . $this->getStaffId() . "',
						`executeTime`						=	" . $this->getExecuteTime() . "
				WHERE	`paymentVoucherAllocationId`		=	'" . $paymentVoucherAllocationId . "'";
            } else
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
				UPDATE 	[paymentVoucherAllocation]
				SET    	[paymentVoucherAllocationAmount]	=	'" . $paymentVoucherAllocationAmount . "',
						[isNew]								=	0,
						[isUpdate]							=	1,
						[executeBy]							=	'" . $this->getStaffId() . "',
						[executeTime]						=	" . $this->getExecuteTime() . "
				WHERE	[paymentVoucherAllocationId]		=	'" . $paymentVoucherAllocationId . "'";
            } else
            if ($this->getVendor() == self::ORACLE) {
                $sql = "
				UPDATE 	PAYMENTVOUCHERALLOCATION
				SET    	PAYMENTVOUCHERALLOCATIONAMOUNT		=	'" . $paymentVoucherAllocationAmount . "',
						ISNEW								=	0,
						ISUPDATE							=	1,
						EXECUTEBY							=	'" . $this->getStaffId() . "',
						EXECUTETIME							=	" . $this->getExecuteTime() . "
				WHERE	PAYMENTVOUCHERALLOCATIONID			=	'" . $paymentVoucherAllocationId . "'";
            }
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
				INSERT INTO `paymentvoucherallocation`(
					`paymentVoucherAllocationId`, 			`companyId`,
					`businessPartnerId`, 					`countryId`,
					`purchaseInvoiceId`, 					`paymentVoucherId`,
					`paymentVoucherAllocationAmount`, 		`isDefault`,
					`isNew`, 								`isDraft`,
					`isUpdate`, 							`isDelete`,
					`isActive`, 							`isApproved`,
					`isReview`, 							`isPost`,
					`executeBy`, 							`executeTime`
				) VALUES (
					null,									'" . $this->getCompanyId() . "',
					'" . $businessPartnerId . "',				'" . $this->ledgerService->getCountryId() . "',
					'" . $purchaseInvoiceId . "',				'" . $paymentVoucherId . "',
					'" . $paymentVoucherAllocationAmount . "',	0,
					1,										0,
					0,										0,
					1,										0,
					0,										0,
					'" . $this->getStaffId() . "',				" . $this->getExecuteTime() . ")";
            } else
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
				INSERT INTO [paymentVoucherAllocation](
					[paymentVoucherAllocationId], 			[companyId],
					[businessPartnerId], 					[countryId],
					[purchaseInvoiceId], 					[paymentVoucherId],
					[paymentVoucherAllocationAmount], 		[isDefault],
					[isNew], 								[isDraft],
					[isUpdate], 							[isDelete],
					[isActive], 							[isApproved],
					[isReview], 							[isPost],
					[executeBy], 							[executeTime]
				) VALUES (
					null,									'" . $this->getCompanyId() . "',
					'" . $businessPartnerId . "',				'" . $this->ledgerService->getCountryId() . "',
					'" . $purchaseInvoiceId . "',				'" . $paymentVoucherId . "',
					'" . $paymentVoucherAllocationAmount . "',	0,
					1,										0,
					0,										0,
					1,										0,
					0,										0,
					'" . $this->getStaffId() . "',				" . $this->getExecuteTime() . ")";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
				INSERT INTO PAYMENTVOUCHERALLOCATION(
					PAYMENTVOUCHERALLOCATIONID, 			COMPANYID,
					BUSINESSPARTNERID, 						COUNTRYID,
					PURCHASEINVOICEID, 						PAYMENTVOUCHERID,
					PAYMENTVOUCHERALLOCATIONAMOUNT, 		ISDEFAULT,
					ISNEW, 									ISDRAFT,
					ISUPDATE, 								ISDELETE,
					ISACTIVE, 								ISAPPROVED,
					ISREVIEW, 								ISPOST,
					EXECUTEBY, 								EXECUTETIME
				) VALUES (
					null,									'" . $this->getCompanyId() . "',
					'" . $businessPartnerId . "',				'" . $this->ledgerService->getCountryId() . "',
					'" . $purchaseInvoiceId . "',				'" . $paymentVoucherId . "',
					'" . $paymentVoucherAllocationAmount . "',	0,
					1,										0,
					0,										0,
					1,										0,
					0,										0,
					'" . $this->getStaffId() . "',				" . $this->getExecuteTime() . ")";
            }
            try {
                $this->q->create($sql);
            } catch (\Exception $e) {
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
            $paymentVoucherAllocationId = $this->q->lastInsertId('paymentVoucherAllocation');
        }
        return $paymentVoucherAllocationId;
    }

    /**
     * Update Cheque Number ,Date .This Process before printing
     * @param string|int $chequeNumber Cheque Number
     * @param string $chequeDate Cheque Date
     * @param int $paymentVoucherId Payment Voucher Primary Key
     * @throws \Exception
     */
    public function setChequeInformation($chequeNumber, $chequeDate, $paymentVoucherId) {
        header('Content-Type:application/json; charset=utf-8');
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $this->q->start();
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE  `paymentVoucher`
            SET     `paymentVoucherChequeNumber`    =   '" . $chequeNumber . "',
                    `paymentVoucherChequeDate`      =   '" . $chequeDate . "',
                    `executeBy`                     =   '" . $this->getStaffId() . "',
                    `executeTime`                   =   " . $this->getExecuteTime() . "
            WHERE   `paymentVoucherId`              =   '" . $paymentVoucherId . "'";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE  [paymentVoucher]
            SET     [paymentVoucherChequeNumber]    =   '" . $chequeNumber . "',
                    [paymentVoucherChequeDate]      =   '" . $chequeDate . "',
                    [executeBy]                     =   '" . $this->getStaffId() . "',
                    [executeTime]                   =   " . $this->getExecuteTime() . "
            WHERE   [paymentVoucherId]              =   '" . $paymentVoucherId . "'";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE  PAYMENTVOUCHER
            SET     PAYMENTVOUCHERCHEQUENUMBER      =     '" . $chequeNumber . "',
                    PAYMENTVOUCHERCHEQUEDATE        =     '" . $chequeDate . "',
                    EXECUTEBY                       =     '" . $this->getStaffId() . "',
                    EXECUTETIME                     =     " . $this->getExecuteTime() . "
            WHERE   PAYMENTVOUCHERID                =     '" . $paymentVoucherId . "'";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $this->setPaymentVoucherStatusTracking(
                $paymentVoucherId, $this->getPaymentVoucherStatusId(self::CHEQUE_NUMBER)
        );
        $this->q->commit();
        echo json_encode(array("success" => true));
        exit();
    }

    /**
     * Set Payment Voucher Document Tracking
     * @param int $paymentVoucherId Payment Voucher Primary Key
     * @param int $paymentVoucherStatusId Payment Voucher Status Primary Key
     * @return void
     */
    public function setPaymentVoucherStatusTracking($paymentVoucherId, $paymentVoucherStatusId) {
        $sql = null;
        $paymentVoucherTrackingDuration = 0;
        // check if exist previous payment voucher transaction and compare with the current day.
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  DATEDIFF(NOW(),`paymentVoucherTrackingDate`) AS `paymentVoucherTrackingDuration`
            FROM   `paymentVoucher`
            WHERE  `paymentVoucherId` ='" . $paymentVoucherId . "'
            ";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT [executeTime]
            FROM   [paymentVoucher]
            WHERE  [paymentVoucherId] ='" . $paymentVoucherId . "'
            ";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT EXECUTETIME
            FROM   PAYMENTVOUCHER
            WHERE  PAYMENTVOUCHERID ='" . $paymentVoucherId . "'
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
            $paymentVoucherTrackingDuration = intval($row['paymentVoucherTrackingDuration']);
        }

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `paymentvouchertracking`(
                `paymentVoucherTrackingId`,               `companyId`,
                `paymentVoucherId`,                       `paymentVoucherStatusId`,
                `paymentVoucherTrackingDuration`,         `isDefault`,
                `isNew`,                                  `isDraft`,
                `isUpdate`,                               `isDelete`,
                `isActive`,                               `isApproved`,
                `isReview`,                               `isPost`,
                `executeBy`,                              `executeTime`,
				`paymentVoucherTrackingDate`
            ) VALUES (
                null,                                   " . $this->getCompanyId() . ",
                '" . $paymentVoucherId . "',                 " . $paymentVoucherStatusId . ",
                '" . $paymentVoucherTrackingDuration . "',           0,
                1,                                       0,
                0,                                       0,
                1,                                       0,
                0,                                       0,
                '" . $this->getStaffId() . "',               " . $this->getExecuteTime() . ",
				NOW()
             )
            ";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            INSERT INTO [paymentVoucherTracking](
                [paymentVoucherTrackingId],               [companyId],
                [paymentVoucherId],                       [paymentVoucherStatusId],
                [paymentVoucherTrackingDuration],                 [isDefault],
                [isNew],                                  [isDraft],
                [isUpdate],                               [isDelete],
                [isActive],                               [isApproved],
                [isReview],                               [isPost],
                [executeBy],                              [executeTime]
            ) VALUES (
                null,                                   " . $this->getCompanyId() . ",
                '" . $paymentVoucherId . "',                 " . $paymentVoucherStatusId . ",
                '" . $paymentVoucherTrackingDuration . "',           0,
                1,                                       0,
                0,                                       0,
                1,                                       0,
                0,                                       0,
                '" . $this->getStaffId() . "',               " . $this->getExecuteTime() . "
            )
            ";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            INSERT INTO PAYMENTVOUCHERTRACKING(
                PAYMENTVOUCHERTRACKINGID,               COMPANYID,
                PAYMENTVOUCHERID,                       PAYMENTVOUCHERSTATUSID,
                PAYMENTVOUCHERTRACKINGDURATION,                 ISDEFAULT,
                ISNEW,                                  ISDRAFT,
                ISUPDATE,                               ISDELETE,
                ISACTIVE,                               ISAPPROVED,
                ISREVIEW,                               ISPOST,
                EXECUTEBY,                              EXECUTETIME
            ) VALUES (
                null,                                   " . $this->getCompanyId() . ",
                '" . $paymentVoucherId . "',                 " . $paymentVoucherStatusId . ",
                '" . $paymentVoucherTrackingDuration . "',           0,
                1,                                       0,
                0,                                       0,
                1,                                       0,
                0,                                       0,
                '" . $this->getStaffId() . "',               " . $this->getExecuteTime() . "
             )
            ";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Internal Payment Voucher Tracking System
     * @param string $paymentVoucherStatusCode Code
     * @return int
     */
    private function getPaymentVoucherStatusId($paymentVoucherStatusCode) {
        $sql = null;
        $paymentVoucherStatusId = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `paymentVoucherStatusId`
            FROM    `paymentvoucherstatus`
            WHERE   `paymentVoucherStatusCode`  =   '" . $paymentVoucherStatusCode . "'
            AND     `companyId`                 =   '" . $this->getCompanyId() . "'";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  [paymentVoucherStatusId]
            FROM    [paymentVoucherStatus]
            WHERE   [paymentVoucherStatusCode]  =   '" . $paymentVoucherStatusCode . "'
            AND     [companyId]                 =   '" . $this->getCompanyId() . "'";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  PAYMENTVOUCHERSTATUSID
            FROM    PAYMENTVOUCHERSTATUS
            WHERE   PAYMENTVOUCHERSTATUSCODE    =   '" . $paymentVoucherStatusCode . "'
            AND     COMPANYID                   =   '" . $this->getCompanyId() . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $paymentVoucherStatusId = $row['paymentVoucherStatusId'];
        }
        return $paymentVoucherStatusId;
    }

    /**
     * Post Payment Voucher To General Ledger
     * @param int $paymentVoucherId Payment Voucher
     * @param int $leafId Leaf
     * @param string $leafName Leaf Name
     * @throws \Exception
     */
    public function setPosting($paymentVoucherId, $leafId, $leafName) {
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  *
            FROM    `paymentvoucher`
            WHERE   `paymentVoucherId` IN (" . $paymentVoucherId . ")";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  *
            FROM    [paymentVoucher]
            WHERE   [paymentVoucherId] IN (" . $paymentVoucherId . ")";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  *
            FROM    PAYMENTVOUCHER
            WHERE   PAYMENTVOUCHERID IN (" . $paymentVoucherId . ")";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            while (($row = $this->q->fetchArray($result)) == true) {
                $businessPartnerId = $row['businessPartnerId'];
                $documentNumber = $row['documentNumber'];
                $cashBookDate = $row['paymentVoucherDate'];
                $cashBookAmount = $row['paymentVoucherAmount'];
                $cashBookDescription = $row['paymentVoucherDescription'];
                $paymentVoucherId = $row['paymentVoucherId'];
                $this->ledgerService->setCashBookLedger(
                        $businessPartnerId, $documentNumber, $cashBookDate, $cashBookAmount, $cashBookDescription, $leafId, $paymentVoucherId, $cashBookLedgerId = null
                );
                // update back status posted

                $this->setPaymentVoucherStatusTracking(
                        $paymentVoucherId, $this->getPaymentVoucherStatusId(self::TRANSFER_TO_GL)
                );
            }
        }
        $sql = null;
        $journalNumber = $this->getDocumentNumber('GLPT');
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  *
            FROM    `paymentvoucherdetail`
            JOIN    `paymentvoucher`
            USING   (`companyId`,`paymentVoucherId`)
            WHERE   `paymentVoucherId` IN (" . $paymentVoucherId . ")
            ORDER BY `paymentVoucherId";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  *
            FROM    [paymentVoucherDetail]
            WHERE   [paymentVoucherId] IN (" . $paymentVoucherId . ")";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  *
            FROM    PAYMENTVOUCHERDETAIL
            WHERE   PAYMENTVOUCHERID IN (" . $paymentVoucherId . ")";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            while (($row = $this->q->fetchArray($result)) == true) {
                $businessPartnerId = $row['businessPartnerId'];
                $chartOfAccountId = $row['chartOfAccountId'];
                $documentNumber = $row['documentNumber'];
                $documentDate = $row['paymentVoucherDate'];
                $localAmount = $row['paymentVoucherDetailAmount'];
                $description = $row['paymentVoucherDescription'];
                $module = 'CB';
                $tableName = 'paymentVoucher';
                $tableNameDetail = 'paymentVoucherDetail';
                $tableNameId = 'paymentVoucherId';
                $tableNameDetailId = 'paymentVoucherDetailId';
                $referenceTableNameId = $row['paymentVoucherId'];
                $referenceTableNameDetailId = $row['paymentVoucherDetailId'];
                $purchaseInvoiceId = null;
                $this->ledgerService->setPurchaseInvoiceLedger(
                        $businessPartnerId, $chartOfAccountId, $documentNumber, $documentDate, $localAmount, $description, $leafId, $purchaseInvoiceId, $purchaseInvoiceLedgerId = null
                );

                $this->ledgerService->setGeneralLedger($leafId, $leafName, $businessPartnerId, $chartOfAccountId, $documentNumber, $journalNumber, $documentDate, $localAmount, $description, $module, $tableName, $tableNameDetail, $tableNameId, $tableNameDetailId, $referenceTableNameId, $referenceTableNameDetailId);
            }
        }
        // make second batch for detail.. no more loop in loop
        $this->setPaymentVoucherPosted($paymentVoucherId);
    }

    /**
     * Update Payment Voucher Posted Flag
     * @param int $paymentVoucherId Payment Voucher Primary Key
     * @return void
     */
    private function setPaymentVoucherPosted($paymentVoucherId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE  `paymentVoucher`
            SET     `isPost`        =  1,
                    `executeBy`     =   '" . $this->getStaffId() . "',
                    `executeTime`   =   " . $this->getExecuteTime() . "
            WHERE   `paymentVoucherId` IN (" . $paymentVoucherId . ")";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE  [paymentVoucher]
            SET     [isPost]        =  1,
                    [executeBy]     =   '" . $this->getStaffId() . "',
                    [executeTime]   =   " . $this->getExecuteTime() . "
            WHERE   [paymentVoucherId] IN (" . $paymentVoucherId . ")";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE  PAYMENTVOUCHER
            SET     ISPOST        =  1,
                    EXECUTEBY     =   '" . $this->getStaffId() . "',
                    EXECUTETIME   =   " . $this->getExecuteTime() . "
            WHERE   PAYMENTVOUCHERID IN (" . $paymentVoucherId . ")";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Set New Fast Business Partner.Company Address And shipping address will be same as defaulted.
     * @param string $businessPartnerCompany
     * @param string $businessPartnerAddress
     * return int $businessPartnerId
     * @throws \Exception
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
        } else
        if ($this->getVendor() == self::MSSQL) {
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
        } else
        if ($this->getVendor() == self::ORACLE) {
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
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      TOP 1 [businessPartnerCategoryId]
			FROM        [businessPartnerCategory]
			WHERE       [isActive]  			        =   1
			AND         [companyId] 			        =   '" . $this->getCompanyId() . "'
			AND		 	[businessPartnerCategoryCode]	=	'" . $businessPartnerCategoryCode . "'";
        } else
        if ($this->getVendor() == self::ORACLE) {
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
     * Return BusinessPartnerShippingCountry Default Value
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
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [countryId],
         FROM        [country]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else
        if ($this->getVendor() == self::ORACLE) {
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
     * Return BusinessPartnerShippingState Default Value
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
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [stateId],
         FROM        [state]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else
        if ($this->getVendor() == self::ORACLE) {
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
        $cityId = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `cityId`
         FROM        `city`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [cityId],
         FROM        [city]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else
        if ($this->getVendor() == self::ORACLE) {
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
     * @param int $businessPartnerId
     * @param null|string $businessPartnerContactName
     * @param null|string $businessPartnerContactPhone
     * @param null|string $businessPartnerContactEmail
     * return @void
     * @throws \Exception
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
        } else {
            if ($this->getVendor() == self::MSSQL) {
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
            } else {
                if ($this->getVendor() == self::ORACLE) {
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
            }
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
     * Return Cheque Character
     * @param double $amount Amount
     * @return string $amountText Amount Character
     */
    public function chequeCharacter($amount) {
        $f = new \NumberFormatter($this->getCountryCurrencyLocale(), \NumberFormatter::SPELLOUT);
        //@ explode problem if not cast to string.
        $amount = (string) $amount;
        $amountArray = explode(".", $amount);
        if (is_array($amountArray) && intval($amountArray[1]) > 0) {
            $centDecimal = $f->format($amountArray[1]);
            $amount = (float) $amount;
            if ($this->getCountryCurrencyLocale() == 'ms-MY') {
                $amountTextTemp = explode('TITIK', $f->format($amount));
                $amountText = str_replace("TITIK", " ", strtoupper($amountTextTemp[0] . " DAN SEN " . $centDecimal . " SAHAJA "));
            } else {
                $amountTextTemp = explode("DOT", $f->format($amount));
                $amountText = str_replace("DOT", " ", strtoupper($amountTextTemp[0] . " DAN SEN " . $centDecimal . " ONLY "));
            }
        } else {
            if ($this->getCountryCurrencyLocale() == 'ms-MY') {
                $amount = (float) $amount;
                $amountText = strtoupper($f->format($amount) . " SAHAJA ");
            } else {
                $amount = (float) $amount;
                $amountText = strtoupper($f->format($amount) . " ONLY ");
            }
        }
        return $amountText;
    }

    /**
     * Return Total Payment Voucher Day.
     * @param int $paymentVoucherId PaymentVoucher Primary Key
     * @return int $totalPaymentVoucherTrackingDay Total Day
     * @throw exception
     */
    private function getTotalPaymentVoucherTrackingDay($paymentVoucherId) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $totalPaymentVoucherTrackingDay = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      SUM(`paymentVoucherTrackingDurationDay`) AS `totalPaymentVoucherTrackingDay`
            FROM        `paymentvouchertracking`
            WHERE       `isActive`  =   1
            AND         `companyId` =   '" . $this->getCompanyId() . "'
            AND    	  `paymentvoucherId` =	  '" . $paymentVoucherId . "'
            GROUP BY   `paymentvoucherId`";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      SUM([paymentVoucherTrackingDurationDay]) AS [totalPaymentVoucherTrackingDay]
            FROM        [paymentVoucherTracking]
            WHERE       [isActive]  =   1
            AND         [companyId] =   '" . $this->getCompanyId() . "'
            AND    	    [paymentVoucherId] =	  '" . $paymentVoucherId . "'
            GROUP BY    [paymentVoucherId] ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      SUM(PAYMENTVOUCHERTRACKINGDURATIONDAY) AS \"totalPaymentVoucherTrackingDay\"
            FROM         PAYMENTVOUCHERTRACKING
            WHERE       ISACTIVE  =   1
            AND          COMPANYID =   '" . $this->getCompanyId() . "'
            AND    	    PAYMENTVOUCHERID =	  '" . $paymentVoucherId . "'
            GROUP BY    PAYMENTVOUCHERID ";
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
            $totalPaymentVoucherTrackingDay = (int) $row['totalPaymentVoucherTrackingDay'];
        }
        return $totalPaymentVoucherTrackingDay;
    }

    /**
     * Return Total Payment Voucher Hour.
     * @param int $paymentVoucherId PaymentVoucher Primary Key
     * @return int $totalPaymentVoucherTrackingHour Total Day
     * @depreciated  Save it as emengency
     * @throw exception
     */
    private function getTotalPaymentVoucherTrackingHour($paymentVoucherId) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $totalPaymentVoucherTrackingHour = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      SUM(`paymentvoucherTrackingDurationHour`) AS `totalPaymentVoucherTrackingHour`
            FROM        `paymentvouchertracking`
            WHERE       `isActive`  =   1
            AND         `companyId` =   '" . $this->getCompanyId() . "'
            AND    	  `paymentvoucherId` =	  '" . $paymentVoucherId . "'
            GROUP BY   `paymentvoucherId`";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      SUM([paymentVoucherTrackingDurationDay]) AS [totalPaymentVoucherTrackingHour]
            FROM        [paymentVoucherTracking]
            WHERE       [isActive]  =   1
            AND         [companyId] =   '" . $this->getCompanyId() . "'
            AND    	    [paymentVoucherId] =	  '" . $paymentVoucherId . "'
            GROUP BY    [paymentVoucherId] ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      SUM(PAYMENTVOUCHERTRACKINGDURATIONHOUR) AS \"totalPaymentVoucherTrackingHour\"
            FROM         PAYMENTVOUCHERTRACKING
            WHERE       ISACTIVE  =   1
            AND          COMPANYID =   '" . $this->getCompanyId() . "'
            AND    	    PAYMENTVOUCHERID =	  '" . $paymentVoucherId . "'
            GROUP BY    PAYMENTVOUCHERID ";
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
            $totalPaymentVoucherTrackingHour = $row['totalPaymentVoucherTrackingHour'];
        }
        return $totalPaymentVoucherTrackingHour;
    }

    /**
     * Return Total Tracking Holiday
     * @param int $paymentVoucherId PaymentVoucher Primary Key
     * @return int $totalHoliday Total Holiday
     */
    private function getTotalTrackingHolidayPaymentVoucher($paymentVoucherId) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $totalHoliday = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      count(*) AS total
            FROM        `leaveholidays`
            WHERE       `isActive`  =   1
            AND         `companyId` =   '" . $this->getCompanyId() . "'
            AND    	    `paymentvoucherId` =	  '" . $paymentVoucherId . "'
            AND        `leaveHolidaysDate` BETWEEN (
                                                           (
                                                               SELECT MIN(paymentVoucherTrackingDate)
                                                               FROM   `paymentvouchertracking`
                                                               WHERE  `companyId`   =   '" . $this->getCompanyId() . "'
                                                               AND    `paymentvoucherId`=   '" . $paymentVoucherId . "'
                                                           )
                                                        AND
                                                           (
                                                               SELECT MAX(paymentVoucherTrackingDate)
                                                               FROM   `paymentvouchertracking`
                                                               WHERE  `companyId`   =   '" . $this->getCompanyId() . "'
                                                               AND    `paymentvoucherId`=   '" . $paymentVoucherId . "'
                                                           )
                                                    )
            AND        `isNational` =   1
            AND        `isState`    =   1
            AND        `isWeekend`  =   1

            GROUP BY   `paymentvoucherId`";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      count(*) AS [totalHoliday]
            FROM       [leaveHolidays]
            WHERE      [isActive]  =   1
            AND        [companyId] =   '" . $this->getCompanyId() . "'
            AND    	   [paymentVoucherId] =	  '" . $paymentVoucherId . "'
            AND        [leaveHolidaysDate` BETWEEN (
                                                           (
                                                               SELECT MIN(paymentVoucherTrackingDate)
                                                               FROM   [paymentVoucherTracking]
                                                               WHERE  [companyId]   =   '" . $this->getCompanyId() . "'
                                                               AND    [paymentVoucherId]=   '" . $paymentVoucherId . "'
                                                           )
                                                        AND
                                                           (
                                                               SELECT MAX([paymentVoucherTrackingDate])
                                                               FROM   [paymentVoucherTracking]
                                                               WHERE  [companyId]   =   '" . $this->getCompanyId() . "'
                                                               AND    [paymentVoucherId]=   '" . $paymentVoucherId . "'
                                                           )
                                                    )
            AND        [isNational] =   1
            AND        [isState]    =   1
            AND        [isWeekend]  =   1

            GROUP BY   [paymentVoucherId]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      COUNT(*) AS \"totalHoliday\"
            FROM       LEAVEHOLIDAYS
            WHERE      ISACTIVE  =   1
            AND        COMPANYID =   '" . $this->getCompanyId() . "'
            AND    	   PAYMENTVOUCHERID =	  '" . $paymentVoucherId . "'
            AND        LEAVEHOLIDAYSDATE (BETWEEN
                                                           (
                                                               SELECT MIN(PAYMENTVOUCHERTRACKINGDATE)
                                                               FROM   PAYMENTVOUCHERTRACKING
                                                               WHERE  COMPANYID     =   '" . $this->getCompanyId() . "'
                                                               AND    PAYMENTVOUCHERID  =   '" . $paymentVoucherId . "'
                                                           )
                                                        AND
                                                           (
                                                               SELECT MAX(PAYMENTVOUCHERTRACKINGDATE)
                                                               FROM   PAYMENTVOUCHERTRACKING
                                                               WHERE  COMPANYID     =   '" . $this->getCompanyId() . "'
                                                               AND    PAYMENTVOUCHERID  =   '" . $paymentVoucherId . "'
                                                           )
                                                    )
            AND        ISNATIONAL =   1
            AND        ISSTATE    =   1
            AND        ISWEEKEND  =   1

            GROUP BY   PAYMENTVOUCHERID";
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
     * Return Setup Tracking PaymentVoucher Warning Day
     * @return int $paymentVoucherTrackingWarningDay Setup Tracking PaymentVoucher Warning Day
     */
    private function getTrackingPaymentVoucherWarningDay() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $paymentVoucherTrackingWarningDay = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `paymentvoucherTrackingWarningDay`
            FROM `tracking`
            WHERE `companyId`='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  [paymentVoucherTrackingWarningDay]
            FROM [tracking]
            WHERE [companyId]='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  PAYMENTVOUCHERTRACKINGWARNINGDAY
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
            $paymentVoucherTrackingWarningDay = $row['paymentVoucherTrackingWarningDay'];
        }
        return $paymentVoucherTrackingWarningDay;
    }

    /**
     * Return Setup Tracking PaymentVoucher Warning Hour
     * @return int $paymentVoucherTrackingWarningHour Setup Tracking PaymentVoucher Warning Hour
     */
    private function getTrackingPaymentVoucherWarningHour() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $paymentVoucherTrackingWarningHour = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `paymentvoucherTrackingWarningHour`
            FROM `tracking`
            WHERE `companyId`='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  `paymentvoucherTrackingWarningHour`
            FROM `tracking `
            WHERE `companyId`='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  PAYMENTVOUCHERTRACKINGWARNINGHOUR
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
            $paymentVoucherTrackingWarningHour = $row['paymentVoucherTrackingWarningHour'];
        }
        return $paymentVoucherTrackingWarningHour;
    }

    /**
     * Return Tracking Payment Voucher By Day
     * @param int $paymentVoucherId PaymentVoucher Primary Key
     * @return int|bool
     */
    public function getTrackingWarningStatusPaymentVoucherByDay($paymentVoucherId) {
        if ($this->getTotalPaymentVoucherTrackingDay($paymentVoucherId) - $this->getTotalTrackingHolidayPaymentVoucher($paymentVoucherId) > $this->getTrackingPaymentVoucherWarningDay()) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Return Tracking Payment Voucher By Day
     * @param int $paymentVoucherId PaymentVoucher Primary Key
     * @return int|bool
     */
    public function getTrackingWarningStatusPaymentVoucherByHour($paymentVoucherId) {
        if ($this->getTotalPaymentVoucherTrackingHour($paymentVoucherId) - ($this->getTotalTrackingHolidayPaymentVoucher($paymentVoucherId) * 24) > $this->getTrackingPaymentVoucherWarningHour()) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Return Tracking Payment Voucher By Day
     * @param int $paymentVoucherId PaymentVoucher Primary Key
     * @return int
     */
    public function getTrackingPaymentVoucherByDay($paymentVoucherId) {
        return (int) $this->getTotalPaymentVoucherTrackingDay($paymentVoucherId) - $this->getTotalTrackingHolidayPaymentVoucher($paymentVoucherId);
    }

    /**
     * Return Tracking Payment Voucher By Day
     * @param int $paymentVoucherId PaymentVoucher Primary Key
     * @return int
     */
    public function getTrackingPaymentVoucherByHour($paymentVoucherId) {
        return (int) $this->getTotalPaymentVoucherTrackingHour($paymentVoucherId) - ($this->getTotalTrackingHolidayPaymentVoucher($paymentVoucherId) * 24);
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