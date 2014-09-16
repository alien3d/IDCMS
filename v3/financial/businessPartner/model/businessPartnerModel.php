<?php

namespace Core\Financial\BusinessPartner\BusinessPartner\Model;

use Core\Validation\ValidationClass;

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
require_once($newFakeDocumentRoot . "library/class/classValidation.php");

/**
 * Class BusinessPartner
 * This is Business Partner model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\BusinessPartner\BusinessPartner\Model;
 * @subpackage BusinessPartner
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class BusinessPartnerModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $businessPartnerId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Category
     * @var int
     */
    private $businessPartnerCategoryId;

    /**
     * Office Country
     * @var int
     */
    private $businessPartnerOfficeCountryId;

    /**
     * Office State
     * @var int
     */
    private $businessPartnerOfficeStateId;

    /**
     * Office City
     * @var int
     */
    private $businessPartnerOfficeCityId;

    /**
     * Office District
     * @var int
     */
    private $businessPartnerOfficeDistrictId;

    /**
     * Office Division** not department a like or branch..is a location
     * @var int
     */
    private $businessPartnerOfficeDivisionId;

    /**
     * Shipping Country
     * @var int
     */
    private $businessPartnerShippingCountryId;

    /**
     * Shipping State
     * @var int
     */
    private $businessPartnerShippingStateId;

    /**
     * Shipping District
     * @var int
     */
    private $businessPartnerShippingDistrictId;

    /**
     * Shipping Division** not department a like or branch..is a location
     * @var int
     */
    private $businessPartnerShippingDivisionId;

    /**
     * Shipping City
     * @var int
     */
    private $businessPartnerShippingCityId;

    /**
     * Code
     * @var string
     */
    private $businessPartnerCode;

    /**
     * Registration Number
     * @var string
     */
    private $businessPartnerRegistrationNumber;

    /**
     * Tax Number
     * @var string
     */
    private $businessPartnerTaxNumber;

    /**
     * Company
     * @var string
     */
    private $businessPartnerCompany;

    /**
     * Image
     * @var string
     */
    private $businessPartnerPicture;

    /**
     * Business Phone
     * @var string
     */
    private $businessPartnerBusinessPhone;

    /**
     * Mobile Phone
     * @var string
     */
    private $businessPartnerMobilePhone;

    /**
     * Fax Num
     * @var string
     */
    private $businessPartnerFaxNumber;

    /**
     * Office Address
     * @var string
     */
    private $businessPartnerOfficeAddress;
	
	 /**
     * Office Address Line 1
     * @var string
     */
    private $businessPartnerOfficeAddress1;
	
	 /**
     * Office Address Line 2
     * @var string
     */
    private $businessPartnerOfficeAddress2;
	
	 /**
     * Office Address Line 3
     * @var string
     */
    private $businessPartnerOfficeAddress3;
	

    /**
     * Shipping Address
     * @var string
     */
    private $businessPartnerShippingAddress;
	
	 /**
     * Shipping Address Line 1
     * @var string
     */
    private $businessPartnerShippingAddress1;
	
	 /**
     * Shipping Address Line 2
     * @var string
     */
    private $businessPartnerShippingAddress2;
	
	 /**
     * Shipping Address Line 3
     * @var string
     */
    private $businessPartnerShippingAddress3;

    /**
     * Office Code
     * @var string
     */
    private $businessPartnerOfficePostCode;

    /**
     * Shipping Code
     * @var string
     */
    private $businessPartnerShippingPostCode;

    /**
     * Email
     * @var string
     */
    private $businessPartnerEmail;

    /**
     * Web Page
     * @var string
     */
    private $businessPartnerWebPage;

    /**
     * Facebook
     * @var string
     */
    private $businessPartnerFacebook;

    /**
     * Twitter
     * @var string
     */
    private $businessPartnerTwitter;

    /**
     * Notes
     * @var string
     */
    private $businessPartnerNotes;

    /**
     * Date
     * @var string
     */
    private $businessPartnerDate;

    /**
     * Cheque Printing
     * @var string
     */
    private $businessPartnerChequePrinting;

    /**
     * Credit Term
     * @var string
     */
    private $businessPartnerCreditTerm;

    /**
     * Credit Limit
     * @var double
     */
    private $businessPartnerCreditLimit;

    /**
     * Analysis Date
     * @var string Analysis Date
     */
    private $analysisDate;

    /**
     * Name
     * @var string
     */
    private $businessPartnerContactName;

    /**
     * Title
     * @var string
     */
    private $businessPartnerContactTitle;

    /**
     * Phone
     * @var string
     */
    private $businessPartnerContactPhone;

    /**
     * Email
     * @var string
     */
    private $businessPartnerContactEmail;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('businessPartner');
        $this->setPrimaryKeyName('businessPartnerId');
        $this->setMasterForeignKeyName('businessPartnerId');
        $this->setFilterCharacter('businessPartnerCompany');
        //$this->setFilterCharacter('businessPartnerNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_POST ['businessPartnerId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'string'));
        }
        if (isset($_POST ['businessPartnerCategoryId'])) {
            $this->setBusinessPartnerCategoryId($this->strict($_POST ['businessPartnerCategoryId'], 'integer'));
        }
        if (isset($_POST ['businessPartnerOfficeCountryId'])) {
            $this->setBusinessPartnerOfficeCountryId(
                    $this->strict($_POST ['businessPartnerOfficeCountryId'], 'integer')
            );
        }
        if (isset($_POST ['businessPartnerOfficeStateId'])) {
            $this->setBusinessPartnerOfficeStateId($this->strict($_POST ['businessPartnerOfficeStateId'], 'integer'));
        }
        if (isset($_POST ['businessPartnerOfficeCityId'])) {
            $this->setBusinessPartnerOfficeCityId($this->strict($_POST ['businessPartnerOfficeCityId'], 'integer'));
        }
        if (isset($_POST ['businessPartnerOfficeDistrictId'])) {
            $this->setBusinessPartnerOfficeDistrictId(
                    $this->strict($_POST ['businessPartnerOfficeDistrictId'], 'integer')
            );
        }
        if (isset($_POST ['businessPartnerOfficeDivisionId'])) {
            $this->setBusinessPartnerOfficeDivisionId(
                    $this->strict($_POST ['businessPartnerOfficeDivisionId'], 'integer')
            );
        }
        if (isset($_POST ['businessPartnerShippingCountryId'])) {
            $this->setBusinessPartnerShippingCountryId(
                    $this->strict($_POST ['businessPartnerShippingCountryId'], 'integer')
            );
        }
        if (isset($_POST ['businessPartnerShippingStateId'])) {
            $this->setBusinessPartnerShippingStateId(
                    $this->strict($_POST ['businessPartnerShippingStateId'], 'integer')
            );
        }
        if (isset($_POST ['businessPartnerShippingCityId'])) {
            $this->setBusinessPartnerShippingCityId($this->strict($_POST ['businessPartnerShippingCityId'], 'integer'));
        }
        if (isset($_POST ['businessPartnerShippingDistrictId'])) {
            $this->setBusinessPartnerShippingDistrictId(
                    $this->strict($_POST ['businessPartnerShippingDistrictId'], 'integer')
            );
        }
        if (isset($_POST ['businessPartnerShippingDivisionId'])) {
            $this->setBusinessPartnerShippingDivisionId(
                    $this->strict($_POST ['businessPartnerShippingDivisionId'], 'integer')
            );
        }
        if (isset($_POST ['businessPartnerCode'])) {
            $this->setBusinessPartnerCode($this->strict($_POST ['businessPartnerCode'], 'string'));
        }
        if (isset($_POST ['businessPartnerRegistrationNumber'])) {
            $this->setBusinessPartnerRegistrationNumber(
                    $this->strict($_POST ['businessPartnerRegistrationNumber'], 'string')
            );
        }
        if (isset($_POST ['businessPartnerTaxNumber'])) {
            $this->setBusinessPartnerTaxNumber($this->strict($_POST ['businessPartnerTaxNumber'], 'string'));
        }
        if (isset($_POST ['businessPartnerCompany'])) {
            $this->setBusinessPartnerCompany($this->strict($_POST ['businessPartnerCompany'], 'string'));
        }
        if (isset($_POST ['businessPartnerPicture'])) {
            $this->setBusinessPartnerPicture($this->strict($_POST ['businessPartnerPicture'], 'string'));
        }
        if (isset($_POST ['businessPartnerBusinessPhone'])) {
            $this->setBusinessPartnerBusinessPhone($this->strict($_POST ['businessPartnerBusinessPhone'], 'string'));
        }
        if (isset($_POST ['businessPartnerMobilePhone'])) {
            $this->setBusinessPartnerMobilePhone($this->strict($_POST ['businessPartnerMobilePhone'], 'string'));
        }
        if (isset($_POST ['businessPartnerFaxNumber'])) {
            $this->setBusinessPartnerFaxNumber($this->strict($_POST ['businessPartnerFaxNumber'], 'string'));
        }
        if (isset($_POST ['businessPartnerOfficeAddress'])) {
            $this->setBusinessPartnerOfficeAddress($this->strict($_POST ['businessPartnerOfficeAddress1'].$_POST ['businessPartnerOfficeAddress2'].$_POST ['businessPartnerOfficeAddress3'], 'string'));
        }
		if (isset($_POST ['businessPartnerOfficeAddress1'])) {
            $this->setBusinessPartnerOfficeAddress1($this->strict($_POST ['businessPartnerOfficeAddress1'], 'string'));
        }
		if (isset($_POST ['businessPartnerOfficeAddress2'])) {
            $this->setBusinessPartnerOfficeAddress2($this->strict($_POST ['businessPartnerOfficeAddress2'], 'string'));
        }
		if (isset($_POST ['businessPartnerOfficeAddress3'])) {
            $this->setBusinessPartnerOfficeAddress3($this->strict($_POST ['businessPartnerOfficeAddress3'], 'string'));
        }
        if (isset($_POST ['businessPartnerShippingAddress'])) {
            $this->setBusinessPartnerShippingAddress(
                    $this->strict($_POST ['businessPartnerShippingAddress1'].$_POST ['businessPartnerShippingAddress2'].$_POST ['businessPartnerShippingAddress3'], 'string')
            );
        }
		if (isset($_POST ['businessPartnerShippingAddress1'])) {
            $this->setBusinessPartnerShippingAddress1($this->strict($_POST ['businessPartnerShippingAddress1'], 'string'));
        }
		if (isset($_POST ['businessPartnerShippingAddress2'])) {
            $this->setBusinessPartnerShippingAddress2($this->strict($_POST ['businessPartnerShippingAddress2'], 'string'));
        }
		if (isset($_POST ['businessPartnerShippingAddress3'])) {
            $this->setBusinessPartnerShippingddress3($this->strict($_POST ['businessPartnerShippingAddress3'], 'string'));
        }
        if (isset($_POST ['businessPartnerOfficePostCode'])) {
            $this->setBusinessPartnerOfficePostCode($this->strict($_POST ['businessPartnerOfficePostCode'], 'string'));
        }
        if (isset($_POST ['businessPartnerShippingPostCode'])) {
            $this->setBusinessPartnerShippingPostCode(
                    $this->strict($_POST ['businessPartnerShippingPostCode'], 'string')
            );
        }
        if (isset($_POST ['businessPartnerEmail'])) {
            $this->setBusinessPartnerEmail($this->strict($_POST ['businessPartnerEmail'], 'string'));
        }
        if (isset($_POST ['businessPartnerWebPage'])) {
            $this->setBusinessPartnerWebPage($this->strict($_POST ['businessPartnerWebPage'], 'string'));
        }
        if (isset($_POST ['businessPartnerFacebook'])) {
            $this->setBusinessPartnerFacebook($this->strict($_POST ['businessPartnerFacebook'], 'string'));
        }
        if (isset($_POST ['businessPartnerTwitter'])) {
            $this->setBusinessPartnerTwitter($this->strict($_POST ['businessPartnerTwitter'], 'string'));
        }
        if (isset($_POST ['businessPartnerNotes'])) {
            $this->setBusinessPartnerNotes($this->strict($_POST ['businessPartnerNotes'], 'string'));
        }
        if (isset($_POST ['businessPartnerDate'])) {
            $this->setBusinessPartnerDate($this->strict($_POST ['businessPartnerDate'], 'date'));
        }
        if (isset($_POST ['businessPartnerChequePrinting'])) {
            $this->setBusinessPartnerChequePrinting($this->strict($_POST ['businessPartnerChequePrinting'], 'string'));
        }
        if (isset($_POST ['businessPartnerCreditTerm'])) {
            $this->setBusinessPartnerCreditTerm($this->strict($_POST ['businessPartnerCreditTerm'], 'string'));
        }
        if (isset($_POST ['businessPartnerCreditLimit'])) {
            $this->setBusinessPartnerCreditLimit($this->strict($_POST ['businessPartnerCreditLimit'], 'string'));
        }
        if (isset($_POST ['analysisDate'])) {
            $this->setAnalysisDate($this->strict($_POST ['analysisDate'], 'date'));
        }
        if (isset($_POST ['businessPartnerContactName'])) {
            $this->setBusinessPartnerContactName($this->strict($_POST ['businessPartnerContactName'], 'string'));
        }
        if (isset($_POST ['businessPartnerContactTitle'])) {
            $this->setBusinessPartnerContactTitle($this->strict($_POST ['businessPartnerContactTitle'], 'string'));
        }
        if (isset($_POST ['businessPartnerContactPhone'])) {
            $this->setBusinessPartnerContactPhone($this->strict($_POST ['businessPartnerContactPhone'], 'string'));
        }
        if (isset($_POST ['businessPartnerContactEmail'])) {
            $this->setBusinessPartnerContactEmail($this->strict($_POST ['businessPartnerContactEmail'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_GET ['businessPartnerId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['businessPartnerCategoryId'])) {
            $this->setBusinessPartnerCategoryId($this->strict($_GET ['businessPartnerCategoryId'], 'integer'));
        }
        if (isset($_GET ['businessPartnerOfficeCountryId'])) {
            $this->setBusinessPartnerOfficeCountryId(
                    $this->strict($_GET ['businessPartnerOfficeCountryId'], 'integer')
            );
        }
        if (isset($_GET ['businessPartnerOfficeStateId'])) {
            $this->setBusinessPartnerOfficeStateId($this->strict($_GET ['businessPartnerOfficeStateId'], 'integer'));
        }
        if (isset($_GET ['businessPartnerOfficeCityId'])) {
            $this->setBusinessPartnerOfficeCityId($this->strict($_GET ['businessPartnerOfficeCityId'], 'integer'));
        }
        if (isset($_GET ['businessPartnerOfficeDistrictId'])) {
            $this->setBusinessPartnerOfficeDistrictId(
                    $this->strict($_GET ['businessPartnerOfficeDistrictId'], 'integer')
            );
        }
        if (isset($_GET ['businessPartnerOfficeDivisionId'])) {
            $this->setBusinessPartnerOfficeDivisionId(
                    $this->strict($_GET ['businessPartnerOfficeDivisionId'], 'integer')
            );
        }
        if (isset($_GET ['businessPartnerShippingCountryId'])) {
            $this->setBusinessPartnerShippingCountryId(
                    $this->strict($_GET ['businessPartnerShippingCountryId'], 'integer')
            );
        }
        if (isset($_GET ['businessPartnerShippingStateId'])) {
            $this->setBusinessPartnerShippingStateId(
                    $this->strict($_GET ['businessPartnerShippingStateId'], 'integer')
            );
        }
        if (isset($_GET ['businessPartnerShippingCityId'])) {
            $this->setBusinessPartnerShippingCityId($this->strict($_GET ['businessPartnerShippingCityId'], 'integer'));
        }
        if (isset($_GET ['businessPartnerShippingDistrictId'])) {
            $this->setBusinessPartnerShippingDistrictId(
                    $this->strict($_GET ['businessPartnerShippingDistrictId'], 'integer')
            );
        }
        if (isset($_GET ['businessPartnerShippingDivisionId'])) {
            $this->setBusinessPartnerShippingDivisionId(
                    $this->strict($_GET ['businessPartnerShippingDivisionId'], 'integer')
            );
        }
        if (isset($_GET ['businessPartnerCode'])) {
            $this->setBusinessPartnerCode($this->strict($_GET ['businessPartnerCode'], 'string'));
        }
        if (isset($_GET ['businessPartnerRegistrationNumber'])) {
            $this->setBusinessPartnerRegistrationNumber(
                    $this->strict($_GET ['businessPartnerRegistrationNumber'], 'string')
            );
        }
        if (isset($_GET ['businessPartnerTaxNumber'])) {
            $this->setBusinessPartnerTaxNumber($this->strict($_GET ['businessPartnerTaxNumber'], 'string'));
        }
        if (isset($_GET ['businessPartnerCompany'])) {
            $this->setBusinessPartnerCompany($this->strict($_GET ['businessPartnerCompany'], 'string'));
        }
        if (isset($_GET ['businessPartnerPicture'])) {
            $this->setBusinessPartnerPicture($this->strict($_GET ['businessPartnerPicture'], 'string'));
        }
        if (isset($_GET ['businessPartnerBusinessPhone'])) {
            $this->setBusinessPartnerBusinessPhone($this->strict($_GET ['businessPartnerBusinessPhone'], 'string'));
        }
        if (isset($_GET ['businessPartnerMobilePhone'])) {
            $this->setBusinessPartnerMobilePhone($this->strict($_GET ['businessPartnerMobilePhone'], 'string'));
        }
        if (isset($_GET ['businessPartnerFaxNumber'])) {
            $this->setBusinessPartnerFaxNumber($this->strict($_GET ['businessPartnerFaxNumber'], 'string'));
        }
        if (isset($_GET ['businessPartnerOfficeAddress'])) {
            $this->setBusinessPartnerOfficeAddress($this->strict($_GET ['businessPartnerOfficeAddress'], 'string'));
        }
		
		if (isset($_GET ['businessPartnerOfficeAddress1'])) {
            $this->setBusinessPartnerOfficeAddress1($this->strict($_GET ['businessPartnerOfficeAddress1'], 'string'));
        }
		
		   if (isset($_GET ['businessPartnerOfficeAddress2'])) {
            $this->setBusinessPartnerOfficeAddress2($this->strict($_GET ['businessPartnerOfficeAddress2'], 'string'));
        }
		
		if (isset($_GET ['businessPartnerOfficeAddress3'])) {
            $this->setBusinessPartnerOfficeAddress3($this->strict($_GET ['businessPartnerOfficeAddress3'], 'string'));
        }
        if (isset($_GET ['businessPartnerShippingAddress'])) {
            $this->setBusinessPartnerShippingAddress($this->strict($_GET ['businessPartnerShippingAddress'], 'string'));
        }
		
		 if (isset($_GET ['businessPartnerShippingAddress1'])) {
            $this->setBusinessPartnerShippingAddress1($this->strict($_GET ['businessPartnerShippingAddress1'], 'string'));
        }
		
		 if (isset($_GET ['businessPartnerShippingAddress2'])) {
            $this->setBusinessPartnerShippingAddress2($this->strict($_GET ['businessPartnerShippingAddress2'], 'string'));
        }
		
		 if (isset($_GET ['businessPartnerShippingAddress3'])) {
            $this->setBusinessPartnerShippingAddress3($this->strict($_GET ['businessPartnerShippingAddress3'], 'string'));
        }
		
        if (isset($_GET ['businessPartnerOfficePostCode'])) {
            $this->setBusinessPartnerOfficePostCode($this->strict($_GET ['businessPartnerOfficePostCode'], 'string'));
        }
        if (isset($_GET ['businessPartnerShippingPostCode'])) {
            $this->setBusinessPartnerShippingPostCode(
                    $this->strict($_GET ['businessPartnerShippingPostCode'], 'string')
            );
        }
        if (isset($_GET ['businessPartnerEmail'])) {
            $this->setBusinessPartnerEmail($this->strict($_GET ['businessPartnerEmail'], 'string'));
        }
        if (isset($_GET ['businessPartnerWebPage'])) {
            $this->setBusinessPartnerWebPage($this->strict($_GET ['businessPartnerWebPage'], 'string'));
        }
        if (isset($_GET ['businessPartnerFacebook'])) {
            $this->setBusinessPartnerFacebook($this->strict($_GET ['businessPartnerFacebook'], 'string'));
        }
        if (isset($_GET ['businessPartnerTwitter'])) {
            $this->setBusinessPartnerTwitter($this->strict($_GET ['businessPartnerTwitter'], 'string'));
        }
        if (isset($_GET ['businessPartnerNotes'])) {
            $this->setBusinessPartnerNotes($this->strict($_GET ['businessPartnerNotes'], 'string'));
        }
        if (isset($_GET ['businessPartnerDate'])) {
            $this->setBusinessPartnerDate($this->strict($_GET ['businessPartnerDate'], 'date'));
        }
        if (isset($_GET ['businessPartnerChequePrinting'])) {
            $this->setBusinessPartnerChequePrinting($this->strict($_GET ['businessPartnerChequePrinting'], 'string'));
        }
        if (isset($_GET ['businessPartnerCreditTerm'])) {
            $this->setBusinessPartnerCreditTerm($this->strict($_GET ['businessPartnerCreditTerm'], 'string'));
        }
        if (isset($_GET ['businessPartnerCreditLimit'])) {
            $this->setBusinessPartnerCreditLimit($this->strict($_GET ['businessPartnerCreditLimit'], 'string'));
			
        }
        if (isset($_GET ['analysisDate'])) {
            $this->setAnalysisDate($this->strict($_GET ['analysisDate'], 'date'));
        }
        if (isset($_GET ['businessPartnerContactName'])) {
            $this->setBusinessPartnerContactName($this->strict($_GET ['businessPartnerContactName'], 'string'));
        }
        if (isset($_GET ['businessPartnerContactTitle'])) {
            $this->setBusinessPartnerContactTitle($this->strict($_GET ['businessPartnerContactTitle'], 'string'));
        }
        if (isset($_GET ['businessPartnerContactPhone'])) {
            $this->setBusinessPartnerContactPhone($this->strict($_GET ['businessPartnerContactPhone'], 'string'));
        }
        if (isset($_GET ['businessPartnerContactEmail'])) {
            $this->setBusinessPartnerContactEmail($this->strict($_GET ['businessPartnerContactEmail'], 'string'));
        }
        // upload file
        if (isset($_GET['qqfile'])) {
            $this->setBusinessPartnerPicture($_GET['qqfile']);
        }
        if (isset($_GET ['businessPartnerId'])) {
            $this->setTotal(count($_GET ['businessPartnerId']));
            if (is_array($_GET ['businessPartnerId'])) {
                $this->businessPartnerId = array();
            }
        }
        if (isset($_GET ['isDefault'])) {
            $this->setIsDefaultTotal(count($_GET['isDefault']));
            if (is_array($_GET ['isDefault'])) {
                $this->isDefault = array();
            }
        }
        if (isset($_GET ['isNew'])) {
            $this->setIsNewTotal(count($_GET['isNew']));
            if (is_array($_GET ['isNew'])) {
                $this->isNew = array();
            }
        }
        if (isset($_GET ['isDraft'])) {
            $this->setIsDraftTotal(count($_GET['isDraft']));
            if (is_array($_GET ['isDraft'])) {
                $this->isDraft = array();
            }
        }
        if (isset($_GET ['isUpdate'])) {
            $this->setIsUpdateTotal(count($_GET['isUpdate']));
            if (is_array($_GET ['isUpdate'])) {
                $this->isUpdate = array();
            }
        }
        if (isset($_GET ['isDelete'])) {
            $this->setIsDeleteTotal(count($_GET['isDelete']));
            if (is_array($_GET ['isDelete'])) {
                $this->isDelete = array();
            }
        }
        if (isset($_GET ['isActive'])) {
            $this->setIsActiveTotal(count($_GET['isActive']));
            if (is_array($_GET ['isActive'])) {
                $this->isActive = array();
            }
        }
        if (isset($_GET ['isApproved'])) {
            $this->setIsApprovedTotal(count($_GET['isApproved']));
            if (is_array($_GET ['isApproved'])) {
                $this->isApproved = array();
            }
        }
        if (isset($_GET ['isReview'])) {
            $this->setIsReviewTotal(count($_GET['isReview']));
            if (is_array($_GET ['isReview'])) {
                $this->isReview = array();
            }
        }
        if (isset($_GET ['isPost'])) {
            $this->setIsPostTotal(count($_GET['isPost']));
            if (is_array($_GET ['isPost'])) {
                $this->isPost = array();
            }
        }
        $primaryKeyAll = '';
        for ($i = 0; $i < $this->getTotal(); $i++) {
            if (isset($_GET ['businessPartnerId'])) {
                $this->setBusinessPartnerId($this->strict($_GET ['businessPartnerId'] [$i], 'numeric'), $i, 'array');
            }
            if (isset($_GET ['isDefault'])) {
                if ($_GET ['isDefault'] [$i] == 'true') {
                    $this->setIsDefault(1, $i, 'array');
                } else {
                    if ($_GET ['isDefault'] [$i] == 'false') {
                        $this->setIsDefault(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isNew'])) {
                if ($_GET ['isNew'] [$i] == 'true') {
                    $this->setIsNew(1, $i, 'array');
                } else {
                    if ($_GET ['isNew'] [$i] == 'false') {
                        $this->setIsNew(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isDraft'])) {
                if ($_GET ['isDraft'] [$i] == 'true') {
                    $this->setIsDraft(1, $i, 'array');
                } else {
                    if ($_GET ['isDraft'] [$i] == 'false') {
                        $this->setIsDraft(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isUpdate'])) {
                if ($_GET ['isUpdate'] [$i] == 'true') {
                    $this->setIsUpdate(1, $i, 'array');
                }
                if ($_GET ['isUpdate'] [$i] == 'false') {
                    $this->setIsUpdate(0, $i, 'array');
                }
            }
            if (isset($_GET ['isDelete'])) {
                if ($_GET ['isDelete'] [$i] == 'true') {
                    $this->setIsDelete(1, $i, 'array');
                } else {
                    if ($_GET ['isDelete'] [$i] == 'false') {
                        $this->setIsDelete(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isActive'])) {
                if ($_GET ['isActive'] [$i] == 'true') {
                    $this->setIsActive(1, $i, 'array');
                } else {
                    if ($_GET ['isActive'] [$i] == 'false') {
                        $this->setIsActive(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isApproved'])) {
                if ($_GET ['isApproved'] [$i] == 'true') {
                    $this->setIsApproved(1, $i, 'array');
                } else {
                    if ($_GET ['isApproved'] [$i] == 'false') {
                        $this->setIsApproved(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isReview'])) {
                if ($_GET ['isReview'] [$i] == 'true') {
                    $this->setIsReview(1, $i, 'array');
                } else {
                    if ($_GET ['isReview'] [$i] == 'false') {
                        $this->setIsReview(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isPost'])) {
                if ($_GET ['isPost'] [$i] == 'true') {
                    $this->setIsPost(1, $i, 'array');
                } else {
                    if ($_GET ['isPost'] [$i] == 'false') {
                        $this->setIsPost(0, $i, 'array');
                    }
                }
            }
            $primaryKeyAll .= $this->getBusinessPartnerId($i, 'array') . ",";
        }
        $this->setPrimaryKeyAll((substr($primaryKeyAll, 0, -1)));
        /**
         * All the $_SESSION Environment
         */
        if (isset($_SESSION ['staffId'])) {
            $this->setExecuteBy($_SESSION ['staffId']);
        }
        /**
         * TimeStamp Value.
         */
        if ($this->getVendor() == self::MYSQL) {
            $this->setExecuteTime("'" . date("Y-m-d H:i:s") . "'");
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $this->setExecuteTime("'" . date("Y-m-d H:i:s.u") . "'");
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $this->setExecuteTime("to_date('" . date("Y-m-d H:i:s") . "','YYYY-MM-DD HH24:MI:SS');");
                }
            }
        }
    }

    /**
     * Return Primary Key Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getBusinessPartnerId($key, $type) {
        if ($type == 'single') {
            return $this->businessPartnerId;
        } else {
            if ($type == 'array') {
                return $this->businessPartnerId [$key];
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:getBusinessPartnerId ?"
                        )
                );
                exit();
            }
        }
    }

    /**
     * Set Primary Key Value
     * @param int|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerId($value, $key, $type) {
        if ($type == 'single') {
            $this->businessPartnerId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->businessPartnerId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setBusinessPartnerId?")
                );
                exit();
            }
        }
    }

    /**
     * Create
     * @see ValidationClass::create()
     * @return void
     */
    public function create() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(1, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(1, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(0, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Update
     * @see ValidationClass::update()
     * @return void
     */
    public function update() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(0, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(1, '', 'single');
        $this->setIsActive(1, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(0, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Delete
     * @see ValidationClass::delete()
     * @return void
     */
    public function delete() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(0, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(0, '', 'single');
        $this->setIsDelete(1, '', 'single');
        $this->setIsApproved(0, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Draft
     * @see ValidationClass::draft()
     * @return void
     */
    public function draft() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(1, 0, 'single');
        $this->setIsDraft(1, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(0, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(0, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Approved
     * @see ValidationClass::approved()
     * @return void
     */
    public function approved() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(1, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(0, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(1, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Review
     * @see ValidationClass::review()
     * @return void
     */
    public function review() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(1, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(0, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(0, 0, 'single');
        $this->setIsReview(1, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Post
     * @see ValidationClass::post()
     * @return void
     */
    public function post() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(1, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(0, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(1, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(1, 0, 'single');
    }

    /**
     * To Return Company
     * @return int $companyId
     */
    public function getCompanyId() {
        return $this->companyId;
    }

    /**
     * To Set Company
     * @param int $companyId Company
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return  Category
     * @return int $businessPartnerCategoryId
     */
    public function getBusinessPartnerCategoryId() {
        return $this->businessPartnerCategoryId;
    }

    /**
     * To Set Category
     * @param int $businessPartnerCategoryId Category
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerCategoryId($businessPartnerCategoryId) {
        $this->businessPartnerCategoryId = $businessPartnerCategoryId;
        return $this;
    }

    /**
     * To Return Office Country
     * @return int $businessPartnerOfficeCountryId
     */
    public function getBusinessPartnerOfficeCountryId() {
        return $this->businessPartnerOfficeCountryId;
    }

    /**
     * To Set Office Country
     * @param int $businessPartnerOfficeCountryId Office Country
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerOfficeCountryId($businessPartnerOfficeCountryId) {
        $this->businessPartnerOfficeCountryId = $businessPartnerOfficeCountryId;
        return $this;
    }

    /**
     * To Return Office State
     * @return int $businessPartnerOfficeStateId
     */
    public function getBusinessPartnerOfficeStateId() {
        return $this->businessPartnerOfficeStateId;
    }

    /**
     * To Set Office State
     * @param int $businessPartnerOfficeStateId Office State
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerOfficeStateId($businessPartnerOfficeStateId) {
        $this->businessPartnerOfficeStateId = $businessPartnerOfficeStateId;
        return $this;
    }

    /**
     * To Return Office City
     * @return int $businessPartnerOfficeCityId
     */
    public function getBusinessPartnerOfficeCityId() {
        return $this->businessPartnerOfficeCityId;
    }

    /**
     * To Set Office City
     * @param int $businessPartnerOfficeCityId Office City
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerOfficeCityId($businessPartnerOfficeCityId) {
        $this->businessPartnerOfficeCityId = $businessPartnerOfficeCityId;
        return $this;
    }

    /**
     * To Return Office District
     * @return int $businessPartnerOfficeDistrictId
     */
    public function getBusinessPartnerOfficeDistrictId() {
        return $this->businessPartnerOfficeDistrictId;
    }

    /**
     * To Set Office District
     * @param int $businessPartnerOfficeCityId Office District
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerOfficeDistrictId($businessPartnerOfficeDistrictId) {
        $this->businessPartnerOfficeDistrictId = $businessPartnerOfficeDistrictId;
        return $this;
    }

    /**
     * To Return Office Division->location
     * @return int $businessPartnerOfficeCityId
     */
    public function getBusinessPartnerOfficeDivisionId() {
        return $this->businessPartnerOfficeDivisionId;
    }

    /**
     * To Set Office Division->location
     * @param int $businessPartnerOfficeCityId Office Division
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerOfficeDivisionId($businessPartnerOfficeDivisionId) {
        $this->businessPartnerOfficeDivisionId = $businessPartnerOfficeDivisionId;
        return $this;
    }

    /**
     * To Return Shipping Country
     * @return int $businessPartnerShippingCountryId
     */
    public function getBusinessPartnerShippingCountryId() {
        return $this->businessPartnerShippingCountryId;
    }

    /**
     * To Set Shipping Country
     * @param int $businessPartnerShippingCountryId Shipping Country
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerShippingCountryId($businessPartnerShippingCountryId) {
        $this->businessPartnerShippingCountryId = $businessPartnerShippingCountryId;
        return $this;
    }

    /**
     * To Return Shipping State
     * @return int $businessPartnerShippingStateId
     */
    public function getBusinessPartnerShippingStateId() {
        return $this->businessPartnerShippingStateId;
    }

    /**
     * To Set Shipping State
     * @param int $businessPartnerShippingStateId Shipping State
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerShippingStateId($businessPartnerShippingStateId) {
        $this->businessPartnerShippingStateId = $businessPartnerShippingStateId;
        return $this;
    }

    /**
     * To Return Shipping City
     * @return int $businessPartnerShippingCityId
     */
    public function getBusinessPartnerShippingCityId() {
        return $this->businessPartnerShippingCityId;
    }

    /**
     * To Set Shipping City
     * @param int $businessPartnerShippingCityId Shipping City
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerShippingCityId($businessPartnerShippingCityId) {
        $this->businessPartnerShippingCityId = $businessPartnerShippingCityId;
        return $this;
    }

    /**
     * To Return Shipping District
     * @return int $businessPartnerShippingDistrictId
     */
    public function getBusinessPartnerShippingDistrictId() {
        return $this->businessPartnerShippingDistrictId;
    }

    /**
     * To Set Shipping District
     * @param int $businessPartnerShippingCityId Shipping District
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerShippingDistrictId($businessPartnerShippingDistrictId) {
        $this->businessPartnerShippingDistrictId = $businessPartnerShippingDistrictId;
        return $this;
    }

    /**
     * To Return Shipping Division -> location
     * @return int $businessPartnerShippingCityId
     */
    public function getBusinessPartnerShippingDivisionId() {
        return $this->businessPartnerShippingDivisionId;
    }

    /**
     * To Set Shipping Division -> location
     * @param int $businessPartnerShippingDivisionId Shipping Division
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerShippingDivisionId($businessPartnerShippingDivisionId) {
        $this->businessPartnerShippingDivisionId = $businessPartnerShippingDivisionId;
        return $this;
    }

    /**
     * To Return Code
     * @return string $businessPartnerCode
     */
    public function getBusinessPartnerCode() {
        return $this->businessPartnerCode;
    }

    /**
     * To Set Code
     * @param string $businessPartnerCode Code
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerCode($businessPartnerCode) {
        $this->businessPartnerCode = $businessPartnerCode;
        return $this;
    }

    /**
     * To Return Registration Number
     * @return string $businessPartnerRegistrationNumber
     */
    public function getBusinessPartnerRegistrationNumber() {
        return $this->businessPartnerRegistrationNumber;
    }

    /**
     * To Set Registration Number
     * @param string $businessPartnerRegistrationNumber Registration Number
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerRegistrationNumber($businessPartnerRegistrationNumber) {
        $this->businessPartnerRegistrationNumber = $businessPartnerRegistrationNumber;
        return $this;
    }

    /**
     * To Return Tax Number
     * @return string $businessPartnerTaxNumber
     */
    public function getBusinessPartnerTaxNumber() {
        return $this->businessPartnerTaxNumber;
    }

    /**
     * To Set Tax Number
     * @param string $businessPartnerTaxNumber Tax Number
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerTaxNumber($businessPartnerTaxNumber) {
        $this->businessPartnerTaxNumber = $businessPartnerTaxNumber;
        return $this;
    }

    /**
     * To Return Company
     * @return string $businessPartnerCompany
     */
    public function getBusinessPartnerCompany() {
        return $this->businessPartnerCompany;
    }

    /**
     * To Set Company
     * @param string $businessPartnerCompany Company
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerCompany($businessPartnerCompany) {
        $this->businessPartnerCompany = $businessPartnerCompany;
        return $this;
    }

    /**
     * To Return Image
     * @return string $businessPartnerPicture
     */
    public function getBusinessPartnerPicture() {
        return $this->businessPartnerPicture;
    }

    /**
     * To Set Image
     * @param string $businessPartnerImage Image
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerPicture($businessPartnerImage) {
        $this->businessPartnerPicture = $businessPartnerImage;
        return $this;
    }

    /**
     * To Return Business Phone
     * @return string $businessPartnerBusinessPhone
     */
    public function getBusinessPartnerBusinessPhone() {
        return $this->businessPartnerBusinessPhone;
    }

    /**
     * To Set Business Phone
     * @param string $businessPartnerBusinessPhone Business Phone
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerBusinessPhone($businessPartnerBusinessPhone) {
        $this->businessPartnerBusinessPhone = $businessPartnerBusinessPhone;
        return $this;
    }

    /**
     * To Return Mobile Phone
     * @return string $businessPartnerMobilePhone
     */
    public function getBusinessPartnerMobilePhone() {
        return $this->businessPartnerMobilePhone;
    }

    /**
     * To Set Mobile Phone
     * @param string $businessPartnerMobilePhone Mobile Phone
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerMobilePhone($businessPartnerMobilePhone) {
        $this->businessPartnerMobilePhone = $businessPartnerMobilePhone;
        return $this;
    }

    /**
     * To Return Fax Number
     * @return string $businessPartnerFaxNumber
     */
    public function getBusinessPartnerFaxNumber() {
        return $this->businessPartnerFaxNumber;
    }

    /**
     * To Set Fax Number
     * @param string $businessPartnerFaxNumber Fax Num
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerFaxNumber($businessPartnerFaxNumber) {
        $this->businessPartnerFaxNumber = $businessPartnerFaxNumber;
        return $this;
    }

    /**
     * To Return Office Address
     * @return string $businessPartnerOfficeAddress
     */
    public function getBusinessPartnerOfficeAddress() {
        return $this->businessPartnerOfficeAddress;
    }

    /**
     * To Set Office Address
     * @param string $businessPartnerOfficeAddress Office Address
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerOfficeAddress($businessPartnerOfficeAddress) {
        $this->businessPartnerOfficeAddress = $businessPartnerOfficeAddress;
        return $this;
    }
	
	/**
     * To Return Office Address Line 1
     * @return string $businessPartnerShippingAddress1
     */
    public function getBusinessPartnerOfficeAddress1() {
        return $this->businessPartnerOfficeddress1;
    }
	
   /**
     * To Set Office Address Line 1
     * @param string $businessPartnerOfficeAddress Office Address Line 1 
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerOfficeAddress1($businessPartnerOfficeAddress1) {
        $this->businessPartnerOfficeAddress1 = $businessPartnerOfficeAddress1;
        return $this;
    }
	
	/**
     * To Return Office Address Line 2
     * @return string $businessPartnerShippingAddress1
     */
    public function getBusinessPartnerOfficeAddress2() {
        return $this->businessPartnerOfficeddress2;
    }
	
   /**
     * To Set Office Address Line 2
     * @param string $businessPartnerOfficeAddress Office Address Line 2
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerOfficeAddress2($businessPartnerOfficeAddress2) {
        $this->businessPartnerOfficeAddress2 = $businessPartnerOfficeAddress2;
        return $this;
    }

	/**
     * To Return Office Address Line 3
     * @return string $businessPartnerShippingAddress3
     */
    public function getBusinessPartnerOfficeAddress3() {
        return $this->businessPartnerOfficeddress3;
    }
	
   /**
     * To Set Office Address Line 3
     * @param string $businessPartnerOfficeAddress Office Address Line 3 
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerOfficeAddress3($businessPartnerOfficeAddress3) {
        $this->businessPartnerOfficeAddress3 = $businessPartnerOfficeAddress3;
        return $this;
    }
	
    /**
     * To Return Shipping Address Line 
     * @return string $businessPartnerShippingAddress
     */
    public function getBusinessPartnerShippingAddress() {
        return $this->businessPartnerShippingAddress;
    }

    /**
     * To Set Shipping Address
     * @param string $businessPartnerShippingAddress Shipping Address
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerShippingAddress($businessPartnerShippingAddress) {
        $this->businessPartnerShippingAddress = $businessPartnerShippingAddress;
        return $this;
    }
	
	
    /**
     * To Return Shipping Address Line 1
     * @return string $businessPartnerShippingAddress1
     */
    public function getBusinessPartnerShippingAddress1() {
        return $this->businessPartnerShippingAddress1;
    }

    /**
     * To Set Shipping Address Line 1
     * @param string $businessPartnerShippingAddress Shipping Address Line 1
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerShippingAddress1($businessPartnerShippingAddress1) {
        $this->businessPartnerShippingAddress1 = $businessPartnerShippingAddress1;
		return $this;
     }
	 
	 /**
     * To Return Shipping Address Line 2
     * @return string $businessPartnerShippingAddress2
     */
    public function getBusinessPartnerShippingAddress2() {
        return $this->businessPartnerShippingAddress2;
    }

    /**
     * To Set Shipping Address Line 2
     * @param string $businessPartnerShippingAddress Shipping Address Line 2
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerShippingAddress2($businessPartnerShippingAddress2) {
        $this->businessPartnerShippingAddress2 = $businessPartnerShippingAddress2;
		return $this;
     }
	 
	 /**
     * To Return Shipping Address Line 3
     * @return string $businessPartnerShippingAddress3
     */
    public function getBusinessPartnerShippingAddress3() {
        return $this->businessPartnerShippingAddress3;
    }

    /**
     * To Set Shipping Address Line 3
     * @param string $businessPartnerShippingAddress Shipping Address Line 1
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerShippingAddress3($businessPartnerShippingAddress3) {
        $this->businessPartnerShippingAddress3 = $businessPartnerShippingAddress3;
		return $this;
     }
	 

    /**
     * To Return Office Post Code
     * @return string $businessPartnerOfficePostCode
     */
    public function getBusinessPartnerOfficePostCode() {
        return $this->businessPartnerOfficePostCode;
    }

    /**
     * To Set Office Post Code
     * @param string $businessPartnerOfficePostCode Office Code
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerOfficePostCode($businessPartnerOfficePostCode) {
        $this->businessPartnerOfficePostCode = $businessPartnerOfficePostCode;
        return $this;
    }

    /**
     * To Return Shipping Post Code
     * @return string $businessPartnerShippingPostCode
     */
    public function getBusinessPartnerShippingPostCode() {
        return $this->businessPartnerShippingPostCode;
    }

    /**
     * To Set Shipping Post Code
     * @param string $businessPartnerShippingPostCode Shipping Code
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerShippingPostCode($businessPartnerShippingPostCode) {
        $this->businessPartnerShippingPostCode = $businessPartnerShippingPostCode;
        return $this;
    }

    /**
     * To Return Email
     * @return string $businessPartnerEmail
     */
    public function getBusinessPartnerEmail() {
        return $this->businessPartnerEmail;
    }

    /**
     * To Set Email
     * @param string $businessPartnerEmail Email
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerEmail($businessPartnerEmail) {
        $this->businessPartnerEmail = $businessPartnerEmail;
        return $this;
    }

    /**
     * To Return WebPage
     * @return string $businessPartnerWebPage
     */
    public function getBusinessPartnerWebPage() {
        return $this->businessPartnerWebPage;
    }

    /**
     * To Set WebPage
     * @param string $businessPartnerWebPage Web Page
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerWebPage($businessPartnerWebPage) {
        $this->businessPartnerWebPage = $businessPartnerWebPage;
        return $this;
    }

    /**
     * To Return Facebook
     * @return string $businessPartnerFacebook
     */
    public function getBusinessPartnerFacebook() {
        return $this->businessPartnerFacebook;
    }

    /**
     * To Set Facebook
     * @param string $businessPartnerFacebook Facebook
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerFacebook($businessPartnerFacebook) {
        $this->businessPartnerFacebook = $businessPartnerFacebook;
        return $this;
    }

    /**
     * To Return Twitter
     * @return string $businessPartnerTwitter
     */
    public function getBusinessPartnerTwitter() {
        return $this->businessPartnerTwitter;
    }

    /**
     * To Set Twitter
     * @param string $businessPartnerTwitter Twitter
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerTwitter($businessPartnerTwitter) {
        $this->businessPartnerTwitter = $businessPartnerTwitter;
        return $this;
    }

    /**
     * To Return Notes
     * @return string $businessPartnerNotes
     */
    public function getBusinessPartnerNotes() {
        return $this->businessPartnerNotes;
    }

    /**
     * To Set Notes
     * @param string $businessPartnerNotes Notes
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerNotes($businessPartnerNotes) {
        $this->businessPartnerNotes = $businessPartnerNotes;
        return $this;
    }

    /**
     * To Return Date
     * @return string $businessPartnerDate
     */
    public function getBusinessPartnerDate() {
        return $this->businessPartnerDate;
    }

    /**
     * To Set Date
     * @param string $businessPartnerDate Date
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerDate($businessPartnerDate) {
        $this->businessPartnerDate = $businessPartnerDate;
        return $this;
    }

    /**
     * To Return Cheque Printing
     * @return string $businessPartnerChequePrinting
     */
    public function getBusinessPartnerChequePrinting() {
        return $this->businessPartnerChequePrinting;
    }

    /**
     * To Set Cheque Printing
     * @param string $businessPartnerChequePrinting Cheque Printing
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerChequePrinting($businessPartnerChequePrinting) {
        $this->businessPartnerChequePrinting = $businessPartnerChequePrinting;
        return $this;
    }

    /**
     * To Return Credit Term
     * @return string $businessPartnerCreditTerm
     */
    public function getBusinessPartnerCreditTerm() {
        return $this->businessPartnerCreditTerm;
    }

    /**
     * To Set Credit Term
     * @param string $businessPartnerCreditTerm Credit Term
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerCreditTerm($businessPartnerCreditTerm) {
        $this->businessPartnerCreditTerm = $businessPartnerCreditTerm;
        return $this;
    }

    /**
     * To Return Credit Limit
     * @return double $businessPartnerCreditLimit
     */
    public function getBusinessPartnerCreditLimit() {
        return $this->businessPartnerCreditLimit;
    }

    /**
     * To Set Credit Limit
     * @param double $businessPartnerCreditLimit Credit Limit
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerCreditLimit($businessPartnerCreditLimit) {
        $this->businessPartnerCreditLimit = $businessPartnerCreditLimit;
        return $this;
    }

    /**
     * To Return Google Maps
     * @return double $businessPartnerMaps
     */
    public function getBusinessPartnerMaps() {
        return $this->businessPartnerMaps;
    }

    /**
     * To Set Google Maps
     * @param double $businessPartnerMaps Google Maps
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerMaps($businessPartnerMaps) {
        $this->businessPartnerMaps = $businessPartnerMaps;
        return $this;
    }

    /**
     * To Return Analysis Date
     * @return string $analysisDate
     */
    public function getAnalysisDate() {
        return $this->analysisDate;
    }

    /**
     * To Set Analysis Date
     * @param double $analysisDate Analysis Date
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setAnalysisDate($analysisDate) {
        $this->analysisDate = $analysisDate;
        return $this;
    }

    /**
     * To Return Name
     * @return string $businessPartnerContactName
     */
    public function getBusinessPartnerContactName() {
        return $this->businessPartnerContactName;
    }

    /**
     * To Set Name
     * @param string $businessPartnerContactName Name
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerContactName($businessPartnerContactName) {
        $this->businessPartnerContactName = $businessPartnerContactName;
        return $this;
    }

    /**
     * To Return Title
     * @return string $businessPartnerContactTitle
     */
    public function getBusinessPartnerContactTitle() {
        return $this->businessPartnerContactTitle;
    }

    /**
     * To Set Title
     * @param string $businessPartnerContactTitle Title
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerContactTitle($businessPartnerContactTitle) {
        $this->businessPartnerContactTitle = $businessPartnerContactTitle;
        return $this;
    }

    /**
     * To Return Phone
     * @return string $businessPartnerContactPhone
     */
    public function getBusinessPartnerContactPhone() {
        return $this->businessPartnerContactPhone;
    }

    /**
     * To Set Phone
     * @param string $businessPartnerContactPhone Phone
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerContactPhone($businessPartnerContactPhone) {
        $this->businessPartnerContactPhone = $businessPartnerContactPhone;
        return $this;
    }

    /**
     * To Return Email
     * @return string $businessPartnerContactEmail
     */
    public function getBusinessPartnerContactEmail() {
        return $this->businessPartnerContactEmail;
    }

    /**
     * To Set Email
     * @param string $businessPartnerContactEmail Email
     * @return \Core\Financial\BusinessPartner\BusinessPartner\Model\BusinessPartnerModel
     */
    public function setBusinessPartnerContactEmail($businessPartnerContactEmail) {
        $this->businessPartnerContactEmail = $businessPartnerContactEmail;
        return $this;
    }

}

?>