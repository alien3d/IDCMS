<?php  namespace Core\System\Management\Company\Model;
 use Core\Validation\ValidationClass;
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
require_once ($newFakeDocumentRoot."library/class/classValidation.php"); 
/** 
 * Class Company
 * This is company model file.This is to ensure strict setting enable for all variable enter to database 
 * 
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\System\Management\Company\Model;
 * @subpackage Management 
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class CompanyModel extends ValidationClass { 
 /**
  * Primary Key
  * @var int 
  */
  private $companyId; 
 /**
  * Country
  * @var int 
  */
  private $countryId; 
 /**
  * State
  * @var int 
  */
  private $stateId; 
    /**
     * Division->location
     * @var int
     */
    private $divisionId;
	 /**
     * District
     * @var int
     */
    private $districtId;

 /**
  * City
  * @var int 
  */
  private $cityId; 
 /**
  * Code
  * @var string 
  */
  private $companyCode; 
 /**
  * Logo
  * @var string 
  */
  private $companyLogo; 
 /**
  * Registration Number
  * @var string 
  */
  private $companyRegistrationNumber; 
 /**
  * Registration Date
  * @var date 
  */
  private $companyRegistrationDate; 
 /**
  * Tax Number
  * @var string 
  */
  private $companyTaxNumber; 
 /**
  * Description
  * @var string 
  */
  private $companyDescription; 
 /**
  * Name
  * @var string 
  */
  private $companyName; 
 /**
  * Email
  * @var string 
  */
  private $companyEmail; 
 /**
  * Mobile Phone
  * @var string 
  */
  private $companyMobilePhone; 
 /**
  * Office Phone
  * @var string 
  */
  private $companyOfficePhone; 
 /**
  * Office Secondary
  * @var string 
  */
  private $companyOfficePhoneSecondary; 
 /**
  * Fax Number
  * @var string 
  */
  private $companyFaxNumber; 
 /**
  * Address
  * @var string 
  */
  private $companyAddress; 
 /**
  * City
  * @var string 
  */
  private $companyCity; 
 /**
  * State
  * @var string 
  */
  private $companyState; 
 /**
  * Post Code
  * @var string 
  */
  private $companyPostCode; 
 /**
  * Country
  * @var string 
  */
  private $companyCountry; 
 /**
  * Web Page
  * @var string 
  */
  private $companyWebPage; 
 /**
  * Facebook
  * @var string 
  */
  private $companyFacebook; 
 /**
  * Twitter
  * @var string 
  */
  private $companyTwitter; 
 /**
  * Linked In
  * @var string 
  */
  private $companyLinkedIn; 
 /**
  * Maps
  * @var string 
  */
  private $companyMaps; 
 /**
  * Longtitude
  * @var string 
  */
  private $companyLongtitude; 
 /**
  * Latitude
  * @var string 
  */
  private $companyLatitude; 
 /**
  * Class Loader
  * @see ValidationClass::execute()
  */
 public function execute() {
     /**
     *  Basic Information Table
     **/
     $this->setTableName('company');
     $this->setPrimaryKeyName('companyId');
     $this->setMasterForeignKeyName('companyId');
     $this->setFilterCharacter('companyDescription');
     //$this->setFilterCharacter('companyNote');
     $this->setFilterDate('executeTime');
     /**
     * All the $_POST Environment
     */ 
     if (isset($_POST ['companyId'])) { 
          $this->setCompanyId($this->strict($_POST ['companyId'], 'int'), 0, 'single'); 
      } 
      if (isset($_POST ['countryId'])) { 
          $this->setCountryId($this->strict($_POST ['countryId'], 'int')); 
      } 
      if (isset($_POST ['stateId'])) { 
          $this->setStateId($this->strict($_POST ['stateId'], 'int')); 
      } 
      if (isset($_POST ['cityId'])) { 
          $this->setCityId($this->strict($_POST ['cityId'], 'int')); 
      } 
      if (isset($_POST ['companyCode'])) { 
          $this->setCompanyCode($this->strict($_POST ['companyCode'], 'string')); 
      } 
      if (isset($_POST ['companyLogo'])) { 
          $this->setCompanyLogo($this->strict($_POST ['companyLogo'], 'string')); 
      } 
      if (isset($_POST ['companyRegistrationNumber'])) { 
          $this->setCompanyRegistrationNumber($this->strict($_POST ['companyRegistrationNumber'], 'string')); 
      } 
      if (isset($_POST ['companyRegistrationDate'])) { 
          $this->setCompanyRegistrationDate($this->strict($_POST ['companyRegistrationDate'], 'date')); 
      } 
      if (isset($_POST ['companyTaxNumber'])) { 
          $this->setCompanyTaxNumber($this->strict($_POST ['companyTaxNumber'], 'string')); 
      } 
      if (isset($_POST ['companyDescription'])) { 
          $this->setCompanyDescription($this->strict($_POST ['companyDescription'], 'string')); 
      } 
      if (isset($_POST ['companyName'])) { 
          $this->setCompanyName($this->strict($_POST ['companyName'], 'string')); 
      } 
      if (isset($_POST ['companyEmail'])) { 
          $this->setCompanyEmail($this->strict($_POST ['companyEmail'], 'string')); 
      } 
      if (isset($_POST ['companyMobilePhone'])) { 
          $this->setCompanyMobilePhone($this->strict($_POST ['companyMobilePhone'], 'string')); 
      } 
      if (isset($_POST ['companyOfficePhone'])) { 
          $this->setCompanyOfficePhone($this->strict($_POST ['companyOfficePhone'], 'string')); 
      } 
      if (isset($_POST ['companyOfficePhoneSecondary'])) { 
          $this->setCompanyOfficePhoneSecondary($this->strict($_POST ['companyOfficePhoneSecondary'], 'string')); 
      } 
      if (isset($_POST ['companyFaxNumber'])) { 
          $this->setCompanyFaxNumber($this->strict($_POST ['companyFaxNumber'], 'string')); 
      } 
      if (isset($_POST ['companyAddress'])) { 
          $this->setCompanyAddress($this->strict($_POST ['companyAddress'], 'string')); 
      } 
      if (isset($_POST ['companyCity'])) { 
          $this->setCompanyCity($this->strict($_POST ['companyCity'], 'string')); 
      } 
      if (isset($_POST ['companyState'])) { 
          $this->setCompanyState($this->strict($_POST ['companyState'], 'string')); 
      } 
      if (isset($_POST ['companyPostCode'])) { 
          $this->setCompanyPostCode($this->strict($_POST ['companyPostCode'], 'string')); 
      } 
      if (isset($_POST ['companyCountry'])) { 
          $this->setCompanyCountry($this->strict($_POST ['companyCountry'], 'string')); 
      } 
      if (isset($_POST ['companyWebPage'])) { 
          $this->setCompanyWebPage($this->strict($_POST ['companyWebPage'], 'string')); 
      } 
      if (isset($_POST ['companyFacebook'])) { 
          $this->setCompanyFacebook($this->strict($_POST ['companyFacebook'], 'string')); 
      } 
      if (isset($_POST ['companyTwitter'])) { 
          $this->setCompanyTwitter($this->strict($_POST ['companyTwitter'], 'string')); 
      } 
      if (isset($_POST ['companyLinkedIn'])) { 
          $this->setCompanyLinkedIn($this->strict($_POST ['companyLinkedIn'], 'string')); 
      } 
      if (isset($_POST ['companyMaps'])) { 
          $this->setCompanyMaps($this->strict($_POST ['companyMaps'], 'string')); 
      } 
      if (isset($_POST ['companyLongtitude'])) { 
          $this->setCompanyLongtitude($this->strict($_POST ['companyLongtitude'], 'string')); 
      } 
      if (isset($_POST ['companyLatitude'])) { 
          $this->setCompanyLatitude($this->strict($_POST ['companyLatitude'], 'string')); 
      } 
      /**
     * All the $_GET Environment
     */
     if (isset($_GET ['companyId'])) { 
          $this->setCompanyId($this->strict($_GET ['companyId'], 'int'), 0, 'single'); 
      } 
      if (isset($_GET ['countryId'])) { 
          $this->setCountryId($this->strict($_GET ['countryId'], 'int')); 
      } 
      if (isset($_GET ['stateId'])) { 
          $this->setStateId($this->strict($_GET ['stateId'], 'int')); 
      } 
      if (isset($_GET ['cityId'])) { 
          $this->setCityId($this->strict($_GET ['cityId'], 'int')); 
      } 
      if (isset($_GET ['companyCode'])) { 
          $this->setCompanyCode($this->strict($_GET ['companyCode'], 'string')); 
      } 
      if (isset($_GET ['companyLogo'])) { 
          $this->setCompanyLogo($this->strict($_GET ['companyLogo'], 'string')); 
      } 
      if (isset($_GET ['companyRegistrationNumber'])) { 
          $this->setCompanyRegistrationNumber($this->strict($_GET ['companyRegistrationNumber'], 'string')); 
      } 
      if (isset($_GET ['companyRegistrationDate'])) { 
          $this->setCompanyRegistrationDate($this->strict($_GET ['companyRegistrationDate'], 'date')); 
      } 
      if (isset($_GET ['companyTaxNumber'])) { 
          $this->setCompanyTaxNumber($this->strict($_GET ['companyTaxNumber'], 'string')); 
      } 
      if (isset($_GET ['companyDescription'])) { 
          $this->setCompanyDescription($this->strict($_GET ['companyDescription'], 'string')); 
      } 
      if (isset($_GET ['companyName'])) { 
          $this->setCompanyName($this->strict($_GET ['companyName'], 'string')); 
      } 
      if (isset($_GET ['companyEmail'])) { 
          $this->setCompanyEmail($this->strict($_GET ['companyEmail'], 'string')); 
      } 
      if (isset($_GET ['companyMobilePhone'])) { 
          $this->setCompanyMobilePhone($this->strict($_GET ['companyMobilePhone'], 'string')); 
      } 
      if (isset($_GET ['companyOfficePhone'])) { 
          $this->setCompanyOfficePhone($this->strict($_GET ['companyOfficePhone'], 'string')); 
      } 
      if (isset($_GET ['companyOfficePhoneSecondary'])) { 
          $this->setCompanyOfficePhoneSecondary($this->strict($_GET ['companyOfficePhoneSecondary'], 'string')); 
      } 
      if (isset($_GET ['companyFaxNumber'])) { 
          $this->setCompanyFaxNumber($this->strict($_GET ['companyFaxNumber'], 'string')); 
      } 
      if (isset($_GET ['companyAddress'])) { 
          $this->setCompanyAddress($this->strict($_GET ['companyAddress'], 'string')); 
      } 
      if (isset($_GET ['companyCity'])) { 
          $this->setCompanyCity($this->strict($_GET ['companyCity'], 'string')); 
      } 
      if (isset($_GET ['companyState'])) { 
          $this->setCompanyState($this->strict($_GET ['companyState'], 'string')); 
      } 
      if (isset($_GET ['companyPostCode'])) { 
          $this->setCompanyPostCode($this->strict($_GET ['companyPostCode'], 'string')); 
      } 
      if (isset($_GET ['companyCountry'])) { 
          $this->setCompanyCountry($this->strict($_GET ['companyCountry'], 'string')); 
      } 
      if (isset($_GET ['companyWebPage'])) { 
          $this->setCompanyWebPage($this->strict($_GET ['companyWebPage'], 'string')); 
      } 
      if (isset($_GET ['companyFacebook'])) { 
          $this->setCompanyFacebook($this->strict($_GET ['companyFacebook'], 'string')); 
      } 
      if (isset($_GET ['companyTwitter'])) { 
          $this->setCompanyTwitter($this->strict($_GET ['companyTwitter'], 'string')); 
      } 
      if (isset($_GET ['companyLinkedIn'])) { 
          $this->setCompanyLinkedIn($this->strict($_GET ['companyLinkedIn'], 'string')); 
      } 
      if (isset($_GET ['companyMaps'])) { 
          $this->setCompanyMaps($this->strict($_GET ['companyMaps'], 'string')); 
      } 
      if (isset($_GET ['companyLongtitude'])) { 
          $this->setCompanyLongtitude($this->strict($_GET ['companyLongtitude'], 'string')); 
      } 
      if (isset($_GET ['companyLatitude'])) { 
          $this->setCompanyLatitude($this->strict($_GET ['companyLatitude'], 'string')); 
      } 
      if (isset($_GET ['companyId'])) {
         $this->setTotal(count($_GET ['companyId']));
         if (is_array($_GET ['companyId'])) {
             $this->companyId = array();
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
         if (isset($_GET ['companyId'])) {
             $this->setCompanyId($this->strict($_GET ['companyId'] [$i], 'numeric'), $i, 'array');
         }
         if (isset($_GET ['isDefault'])) {
             if ($_GET ['isDefault'] [$i] == 'true') {
                 $this->setIsDefault(1, $i, 'array');
             } else if ($_GET ['isDefault'] [$i] == 'false') {
                 $this->setIsDefault(0, $i, 'array');
		}
         }
         if (isset($_GET ['isNew'])) {
             if ($_GET ['isNew'] [$i] == 'true') {
                 $this->setIsNew(1, $i, 'array');
		} else if ($_GET ['isNew'] [$i] == 'false') {
                 $this->setIsNew(0, $i, 'array');
             }
         }
         if (isset($_GET ['isDraft'])) {
             if ($_GET ['isDraft'] [$i] == 'true') {
                 $this->setIsDraft(1, $i, 'array');
             } else if ($_GET ['isDraft'] [$i] == 'false') {
                 $this->setIsDraft(0, $i, 'array');
             }
         }
         if (isset($_GET ['isUpdate'])) {
             if ($_GET ['isUpdate'] [$i] == 'true') {
                 $this->setIsUpdate(1, $i, 'array');
             } if ($_GET ['isUpdate'] [$i] == 'false') {
                 $this->setIsUpdate(0, $i, 'array');
             }
         }
         if (isset($_GET ['isDelete'])) {
             if ($_GET ['isDelete'] [$i] == 'true') {
                 $this->setIsDelete(1, $i, 'array');
             } else if ($_GET ['isDelete'] [$i] == 'false') {
                 $this->setIsDelete(0, $i, 'array');
             }
         }
         if (isset($_GET ['isActive'])) {
             if ($_GET ['isActive'] [$i] == 'true') {
                 $this->setIsActive(1, $i, 'array');
             } else if ($_GET ['isActive'] [$i] == 'false') {
                 $this->setIsActive(0, $i, 'array');
             }
         }
         if (isset($_GET ['isApproved'])) {
             if ($_GET ['isApproved'] [$i] == 'true') {
                 $this->setIsApproved(1, $i, 'array');
             } else if ($_GET ['isApproved'] [$i] == 'false') {
                 $this->setIsApproved(0, $i, 'array');
             } 
         } 
         if (isset($_GET ['isReview'])) {
             if ($_GET ['isReview'] [$i] == 'true') {
                 $this->setIsReview(1, $i, 'array');
             } else if ($_GET ['isReview'] [$i] == 'false') {
                 $this->setIsReview(0, $i, 'array');
             }
         }
         if (isset($_GET ['isPost'])) {
             if ($_GET ['isPost'] [$i] == 'true') {
                 $this->setIsPost(1, $i, 'array');
             } else if ($_GET ['isPost'] [$i] == 'false') {
                 $this->setIsPost(0, $i, 'array');
             }
         }
         $primaryKeyAll .= $this->getCompanyId($i, 'array') . ",";
     }
     $this->setPrimaryKeyAll((substr($primaryKeyAll, 0, - 1)));
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
     } else if ($this->getVendor() == self::MSSQL) {
         $this->setExecuteTime("'" . date("Y-m-d H:i:s.u") . "'");
     } else if ($this->getVendor() == self::ORACLE) {
         $this->setExecuteTime("to_date('" . date("Y-m-d H:i:s") . "','YYYY-MM-DD HH24:MI:SS')");
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
     * Set Primary Key Value 
     * @param int|array $value 
     * @param array[int]int $key List Of Primary Key. 
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array' 
     * @return \Core\System\Management\Company\Model\CompanyModel
     */ 
     public function setCompanyId($value, $key, $type) { 
        if ($type == 'single') { 
           $this->companyId = $value;
           return $this;
        } else if ($type == 'array') {
            $this->companyId[$key] = $value;
           return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:setcompanyId?"));
            exit(); 
        }
    }
    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getCompanyId($key, $type) {
        if ($type == 'single') {
            return $this->companyId;
        } else if ($type == 'array') {
            return $this->companyId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:getcompanyId ?"));
            exit();
        }
	}
	/**
	 * To Return Country 
	 * @return int $countryId
	 */ 
	public function getCountryId()
	{
	    return $this->countryId;
	}
	/**
	 * To Set Country 
	 * @param int $countryId Country 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCountryId($countryId)
	{
         $this->countryId = $countryId;
         return $this;
	} 
	 /**
     * To Return District
     * @return int $districtId
     */
    public function getDistrictId() {
        return $this->districtId;
    }

    /**
     * To Set District
     * @param int $districtId District
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setDistrictId($districtId) {
        $this->districtId = $districtId;
        return $this;
    }
	/**
     * To Return Division
     * @return int $divisionId
     */
    public function getDivisionId() {
        return $this->divisionId;
    }

    /**
     * To Set Division
     * @param int $divisionId Division
     * @return \Core\System\Management\Branch\Model\BranchModel
     */
    public function setDivisionId($divisionId) {
        $this->divisionId = $divisionId;
        return $this;
    }
	/**
	 * To Return State 
	 * @return int $stateId
	 */ 
	public function getStateId()
	{
	    return $this->stateId;
	}
	/**
	 * To Set State 
	 * @param int $stateId State 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setStateId($stateId)
	{
         $this->stateId = $stateId;
         return $this;
	} 
	/**
	 * To Return City 
	 * @return int $cityId
	 */ 
	public function getCityId()
	{
	    return $this->cityId;
	}
	/**
	 * To Set City 
	 * @param int $cityId City 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCityId($cityId)
	{
         $this->cityId = $cityId;
         return $this;
	} 
	/**
	 * To Return Code 
	 * @return string $companyCode
	 */ 
	public function getCompanyCode()
	{
	    return $this->companyCode;
	}
	/**
	 * To Set Code 
	 * @param string $companyCode Code 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyCode($companyCode)
	{
         $this->companyCode = $companyCode;
         return $this;
	} 
	/**
	 * To Return Logo 
	 * @return string $companyLogo
	 */ 
	public function getCompanyLogo()
	{
	    return $this->companyLogo;
	}
	/**
	 * To Set Logo 
	 * @param string $companyLogo Logo 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyLogo($companyLogo)
	{
         $this->companyLogo = $companyLogo;
         return $this;
	} 
	/**
	 * To Return Registration Number 
	 * @return string $companyRegistrationNumber
	 */ 
	public function getCompanyRegistrationNumber()
	{
	    return $this->companyRegistrationNumber;
	}
	/**
	 * To Set Registration Number 
	 * @param string $companyRegistrationNumber Registration Number 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyRegistrationNumber($companyRegistrationNumber)
	{
         $this->companyRegistrationNumber = $companyRegistrationNumber;
         return $this;
	} 
	/**
	 * To Return Registration Date 
	 * @return date $companyRegistrationDate
	 */ 
	public function getCompanyRegistrationDate()
	{
	    return $this->companyRegistrationDate;
	}
	/**
	 * To Set Registration Date 
	 * @param date $companyRegistrationDate Registration Date 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyRegistrationDate($companyRegistrationDate)
	{
         $this->companyRegistrationDate = $companyRegistrationDate;
         return $this;
	} 
	/**
	 * To Return Tax Number 
	 * @return string $companyTaxNumber
	 */ 
	public function getCompanyTaxNumber()
	{
	    return $this->companyTaxNumber;
	}
	/**
	 * To Set Tax Number 
	 * @param string $companyTaxNumber Tax Number 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyTaxNumber($companyTaxNumber)
	{
         $this->companyTaxNumber = $companyTaxNumber;
         return $this;
	} 
	/**
	 * To Return Description 
	 * @return string $companyDescription
	 */ 
	public function getCompanyDescription()
	{
	    return $this->companyDescription;
	}
	/**
	 * To Set Description 
	 * @param string $companyDescription Description 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyDescription($companyDescription)
	{
         $this->companyDescription = $companyDescription;
         return $this;
	} 
	/**
	 * To Return Name 
	 * @return string $companyName
	 */ 
	public function getCompanyName()
	{
	    return $this->companyName;
	}
	/**
	 * To Set Name 
	 * @param string $companyName Name 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyName($companyName)
	{
         $this->companyName = $companyName;
         return $this;
	} 
	/**
	 * To Return Email 
	 * @return string $companyEmail
	 */ 
	public function getCompanyEmail()
	{
	    return $this->companyEmail;
	}
	/**
	 * To Set Email 
	 * @param string $companyEmail Email 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyEmail($companyEmail)
	{
         $this->companyEmail = $companyEmail;
         return $this;
	} 
	/**
	 * To Return Mobile Phone 
	 * @return string $companyMobilePhone
	 */ 
	public function getCompanyMobilePhone()
	{
	    return $this->companyMobilePhone;
	}
	/**
	 * To Set Mobile Phone 
	 * @param string $companyMobilePhone Mobile Phone 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyMobilePhone($companyMobilePhone)
	{
         $this->companyMobilePhone = $companyMobilePhone;
         return $this;
	} 
	/**
	 * To Return Office Phone 
	 * @return string $companyOfficePhone
	 */ 
	public function getCompanyOfficePhone()
	{
	    return $this->companyOfficePhone;
	}
	/**
	 * To Set Office Phone 
	 * @param string $companyOfficePhone Office Phone 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyOfficePhone($companyOfficePhone)
	{
         $this->companyOfficePhone = $companyOfficePhone;
         return $this;
	} 
	/**
	 * To Return Office Secondary 
	 * @return string $companyOfficePhoneSecondary
	 */ 
	public function getCompanyOfficePhoneSecondary()
	{
	    return $this->companyOfficePhoneSecondary;
	}
	/**
	 * To Set Office Secondary 
	 * @param string $companyOfficePhoneSecondary Office Secondary 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyOfficePhoneSecondary($companyOfficePhoneSecondary)
	{
         $this->companyOfficePhoneSecondary = $companyOfficePhoneSecondary;
         return $this;
	} 
	/**
	 * To Return Fax Number 
	 * @return string $companyFaxNumber
	 */ 
	public function getCompanyFaxNumber()
	{
	    return $this->companyFaxNumber;
	}
	/**
	 * To Set Fax Number 
	 * @param string $companyFaxNumber Fax Number 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyFaxNumber($companyFaxNumber)
	{
         $this->companyFaxNumber = $companyFaxNumber;
         return $this;
	} 
	/**
	 * To Return Address 
	 * @return string $companyAddress
	 */ 
	public function getCompanyAddress()
	{
	    return $this->companyAddress;
	}
	/**
	 * To Set Address 
	 * @param string $companyAddress Address 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyAddress($companyAddress)
	{
         $this->companyAddress = $companyAddress;
         return $this;
	} 
	/**
	 * To Return City 
	 * @return string $companyCity
	 */ 
	public function getCompanyCity()
	{
	    return $this->companyCity;
	}
	/**
	 * To Set City 
	 * @param string $companyCity City 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyCity($companyCity)
	{
         $this->companyCity = $companyCity;
         return $this;
	} 
	/**
	 * To Return State 
	 * @return string $companyState
	 */ 
	public function getCompanyState()
	{
	    return $this->companyState;
	}
	/**
	 * To Set State 
	 * @param string $companyState State 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyState($companyState)
	{
         $this->companyState = $companyState;
         return $this;
	} 
	/**
	 * To Return Post Code 
	 * @return string $companyPostCode
	 */ 
	public function getCompanyPostCode()
	{
	    return $this->companyPostCode;
	}
	/**
	 * To Set Post Code 
	 * @param string $companyPostCode Post Code 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyPostCode($companyPostCode)
	{
         $this->companyPostCode = $companyPostCode;
         return $this;
	} 
	/**
	 * To Return Country 
	 * @return string $companyCountry
	 */ 
	public function getCompanyCountry()
	{
	    return $this->companyCountry;
	}
	/**
	 * To Set Country 
	 * @param string $companyCountry Country 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyCountry($companyCountry)
	{
         $this->companyCountry = $companyCountry;
         return $this;
	} 
	/**
	 * To Return Web Page 
	 * @return string $companyWebPage
	 */ 
	public function getCompanyWebPage()
	{
	    return $this->companyWebPage;
	}
	/**
	 * To Set Web Page 
	 * @param string $companyWebPage Web Page 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyWebPage($companyWebPage)
	{
         $this->companyWebPage = $companyWebPage;
         return $this;
	} 
	/**
	 * To Return Facebook 
	 * @return string $companyFacebook
	 */ 
	public function getCompanyFacebook()
	{
	    return $this->companyFacebook;
	}
	/**
	 * To Set Facebook 
	 * @param string $companyFacebook Facebook 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyFacebook($companyFacebook)
	{
         $this->companyFacebook = $companyFacebook;
         return $this;
	} 
	/**
	 * To Return Twitter 
	 * @return string $companyTwitter
	 */ 
	public function getCompanyTwitter()
	{
	    return $this->companyTwitter;
	}
	/**
	 * To Set Twitter 
	 * @param string $companyTwitter Twitter 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyTwitter($companyTwitter)
	{
         $this->companyTwitter = $companyTwitter;
         return $this;
	} 
	/**
	 * To Return Linked In 
	 * @return string $companyLinkedIn
	 */ 
	public function getCompanyLinkedIn()
	{
	    return $this->companyLinkedIn;
	}
	/**
	 * To Set Linked In 
	 * @param string $companyLinkedIn Linked In 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyLinkedIn($companyLinkedIn)
	{
         $this->companyLinkedIn = $companyLinkedIn;
         return $this;
	} 
	/**
	 * To Return Maps 
	 * @return string $companyMaps
	 */ 
	public function getCompanyMaps()
	{
	    return $this->companyMaps;
	}
	/**
	 * To Set Maps 
	 * @param string $companyMaps Maps 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyMaps($companyMaps)
	{
         $this->companyMaps = $companyMaps;
         return $this;
	} 
	/**
	 * To Return Longtitude 
	 * @return string $companyLongtitude
	 */ 
	public function getCompanyLongtitude()
	{
	    return $this->companyLongtitude;
	}
	/**
	 * To Set Longtitude 
	 * @param string $companyLongtitude Longtitude 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyLongtitude($companyLongtitude)
	{
         $this->companyLongtitude = $companyLongtitude;
         return $this;
	} 
	/**
	 * To Return Latitude 
	 * @return string $companyLatitude
	 */ 
	public function getCompanyLatitude()
	{
	    return $this->companyLatitude;
	}
	/**
	 * To Set Latitude 
	 * @param string $companyLatitude Latitude 
	 * @return \Core\System\Management\Company\Model\CompanyModel
	 */
	public function setCompanyLatitude($companyLatitude)
	{
         $this->companyLatitude = $companyLatitude;
         return $this;
	} 
}
?>