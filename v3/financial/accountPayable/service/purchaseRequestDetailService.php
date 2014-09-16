<?php namespace Core\Financial\AccountPayable\PurchaseRequestDetail\Service; 
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
require_once ($newFakeDocumentRoot."library/class/classAbstract.php"); 
require_once ($newFakeDocumentRoot."library/class/classShared.php");
    require_once($newFakeDocumentRoot . "v3/financial/shared/service/sharedService.php");

/** 
 * Class PurchaseRequestDetailService
 * Contain extra processing function / method.
 * @name IDCMS 
 * @version 2 
 * @author hafizan 
 * @package Core\Financial\AccountPayable\PurchaseRequestDetail\Service
 * @subpackage AccountPayable 
 * @link http://www.hafizan.com 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL 
 */ 
class PurchaseRequestDetailService extends ConfigClass { 
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
       if($_SESSION['companyId']){
           $this->setCompanyId($_SESSION['companyId']);
       }else{
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
        if ($_SESSION['branchId']) {
            $this->setBranchId($_SESSION['branchId']);
        }
        $this->ledgerService = new LedgerService();
        $this->ledgerService->q = $this->q;
        $this->ledgerService->t = $this->t;
        $this->ledgerService->execute();
    } 
  
  /**
   * Return Purchase Request
   * @return array|string
   * @throws \Exception
    */
    public function getPurchaseRequest() { 
     //initialize dummy value.. no content header.pure html  
     $sql=null; 
     $str=null; 
     $items=array(); 
     if($this->getVendor()==self::MYSQL) { 
         $sql ="  
         SELECT      `purchaseRequestId`,
                     `purchaseRequestDescription`
         FROM        `purchaserequest`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '".$this->getCompanyId()."'
         ORDER BY    `isDefault`;"; 
     } else if ($this->getVendor()==self::MSSQL) { 
         $sql =" 
         SELECT      [purchaseRequestId],
                     [purchaseRequestDescription]
         FROM        [purchaseRequest]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '".$this->getCompanyId()."'
         ORDER BY    [isDefault]"; 
     } else if ($this->getVendor()==self::ORACLE) { 
         $sql =" 
         SELECT      PURCHASEREQUESTID AS \"purchaseRequestId\",
                     PURCHASEREQUESTDESCRIPTION AS \"purchaseRequestDescription\"
         FROM        PURCHASEREQUEST  
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '".$this->getCompanyId()."'
         ORDER BY    ISDEFAULT"; 
     }  else {
         header('Content-Type:application/json; charset=utf-8');
         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
         exit();
     }
     try {
       $result =$this->q->fast($sql);
     } catch (\Exception $e) {
       echo json_encode(array("success" => false, "message" => $e->getMessage()));
       exit();
    }
     if($result) { 
          $d=1;
         while(($row = $this->q->fetchArray($result))==TRUE) { 
             if($this->getServiceOutput()=='option'){
                  $str.="<option value='".$row['purchaseRequestId']."'>".$d.". ".$row['purchaseRequestDescription']."</option>";
             } else if ($this->getServiceOutput()=='html')  { 
                 $items[] = $row; 
             }
              $d++;
         }
         unset($d);
     }
     if($this->getServiceOutput()=='option'){
          if (strlen($str) > 0) {
            $str = "<option value=''>".$this->t['pleaseSelectTextLabel']."</option>" . $str; 
         } else {
             $str= "<option value=''>".$this->t['notAvailableTextLabel']."</option>";
         }
         header('Content-Type:application/json; charset=utf-8');
         echo json_encode(array("success"=>true,"message"=>"complete","data"=>$str));
         exit();
     } else if ($this->getServiceOutput()=='html')  { 
         return $items; 
      }
         return false; 
  }
  /**
   * Return Purchase Request Default Value
   * @return int
   * @throws \Exception
    */
    public function getPurchaseRequestDefaultValue() { 
     //initialize dummy value.. no content header.pure html  
     $sql=null; 
     $str=null; 
      $purchaseRequestId=null;
     if($this->getVendor()==self::MYSQL) { 
         $sql ="  
         SELECT      `purchaseRequestId`
         FROM        	`purchaserequest`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '".$this->getCompanyId()."'
         AND    	  `isDefault` =	  1
         LIMIT 1"; 
     } else if ($this->getVendor()==self::MSSQL) { 
         $sql =" 
         SELECT      TOP 1 [purchaseRequestId],
         FROM        [purchaseRequest]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '".$this->getCompanyId()."'
         AND    	  [isDefault] =   1"; 
     } else if ($this->getVendor()==self::ORACLE) { 
         $sql =" 
         SELECT      PURCHASEREQUESTID AS \"purchaseRequestId\",
         FROM        PURCHASEREQUEST  
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '".$this->getCompanyId()."'
         AND    	  ISDEFAULT	  =	   1
         AND 		  ROWNUM	  =	   1";
     }  else {
         header('Content-Type:application/json; charset=utf-8');
         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
         exit();
     }
     try {
       $result =$this->q->fast($sql);
     } catch (\Exception $e) {
       echo json_encode(array("success" => false, "message" => $e->getMessage()));
       exit();
    }
     if($result) { 
         $row = $this->q->fetchArray($result); 
          $purchaseRequestId = $row['purchaseRequestId'];
     }
     return $purchaseRequestId;
 }
  /**
   * Return Product
   * @return array|string
   * @throws \Exception
    */
    public function getProduct() { 
     //initialize dummy value.. no content header.pure html  
     $sql=null; 
     $str=null; 
     $items=array(); 
     if($this->getVendor()==self::MYSQL) { 
         $sql ="  
         SELECT      `productId`,
                     `productDescription`
         FROM        `product`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '".$this->getCompanyId()."'
         ORDER BY    `isDefault`;"; 
     } else if ($this->getVendor()==self::MSSQL) { 
         $sql =" 
         SELECT      [productId],
                     [productDescription]
         FROM        [product]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '".$this->getCompanyId()."'
         ORDER BY    [isDefault]"; 
     } else if ($this->getVendor()==self::ORACLE) { 
         $sql =" 
         SELECT      PRODUCTID AS \"productId\",
                     PRODUCTDESCRIPTION AS \"productDescription\"
         FROM        PRODUCT  
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '".$this->getCompanyId()."'
         ORDER BY    ISDEFAULT"; 
     }  else {
         header('Content-Type:application/json; charset=utf-8');
         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
         exit();
     }
     try {
       $result =$this->q->fast($sql);
     } catch (\Exception $e) {
       echo json_encode(array("success" => false, "message" => $e->getMessage()));
       exit();
    }
     if($result) { 
          $d=1;
         while(($row = $this->q->fetchArray($result))==TRUE) { 
             if($this->getServiceOutput()=='option'){
                  $str.="<option value='".$row['productId']."'>".$d.". ".$row['productDescription']."</option>";
             } else if ($this->getServiceOutput()=='html')  { 
                 $items[] = $row; 
             }
              $d++;
         }
         unset($d);
     }
     if($this->getServiceOutput()=='option'){
          if (strlen($str) > 0) {
            $str = "<option value=''>".$this->t['pleaseSelectTextLabel']."</option>" . $str; 
         } else {
             $str= "<option value=''>".$this->t['notAvailableTextLabel']."</option>";
         }
         header('Content-Type:application/json; charset=utf-8');
         echo json_encode(array("success"=>true,"message"=>"complete","data"=>$str));
         exit();
     } else if ($this->getServiceOutput()=='html')  { 
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
     $sql=null; 
     $str=null; 
      $productId=null;
     if($this->getVendor()==self::MYSQL) { 
         $sql ="  
         SELECT      `productId`
         FROM        	`product`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '".$this->getCompanyId()."'
         AND    	  `isDefault` =	  1
         LIMIT 1"; 
     } else if ($this->getVendor()==self::MSSQL) { 
         $sql =" 
         SELECT      TOP 1 [productId],
         FROM        [product]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '".$this->getCompanyId()."'
         AND    	  [isDefault] =   1"; 
     } else if ($this->getVendor()==self::ORACLE) { 
         $sql =" 
         SELECT      PRODUCTID AS \"productId\",
         FROM        PRODUCT  
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '".$this->getCompanyId()."'
         AND    	  ISDEFAULT	  =	   1
         AND 		  ROWNUM	  =	   1";
     }  else {
         header('Content-Type:application/json; charset=utf-8');
         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
         exit();
     }
     try {
       $result =$this->q->fast($sql);
     } catch (\Exception $e) {
       echo json_encode(array("success" => false, "message" => $e->getMessage()));
       exit();
    }
     if($result) { 
         $row = $this->q->fetchArray($result); 
          $productId = $row['productId'];
     }
     return $productId;
 }
  /**
   * Return Unit Of Measurement
   * @return array|string
   * @throws \Exception
    */
    public function getUnitOfMeasurement() { 
     //initialize dummy value.. no content header.pure html  
     $sql=null; 
     $str=null; 
     $items=array(); 
     if($this->getVendor()==self::MYSQL) { 
         $sql ="  
         SELECT      `unitOfMeasurementId`,
		 `unitOfMeasurementCode`,
                     `unitOfMeasurementDescription`
         FROM        `unitofmeasurement`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '".$this->getCompanyId()."'
         ORDER BY    `isDefault`;"; 
     } else if ($this->getVendor()==self::MSSQL) { 
         $sql =" 
         SELECT      [unitOfMeasurementId],
		  [unitOfMeasurementCode],
                     [unitOfMeasurementDescription]
         FROM        [unitOfMeasurement]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '".$this->getCompanyId()."'
         ORDER BY    [isDefault]"; 
     } else if ($this->getVendor()==self::ORACLE) { 
         $sql =" 
         SELECT      UNITOFMEASUREMENTID AS \"unitOfMeasurementId\",
		  UNITOFMEASUREMENTCODE AS \"unitOfMeasurementCode\",
                     UNITOFMEASUREMENTDESCRIPTION AS \"unitOfMeasurementDescription\"
         FROM        UNITOFMEASUREMENT  
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '".$this->getCompanyId()."'
         ORDER BY    ISDEFAULT"; 
     }  else {
         header('Content-Type:application/json; charset=utf-8');
         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
         exit();
     }
     try {
       $result =$this->q->fast($sql);
     } catch (\Exception $e) {
       echo json_encode(array("success" => false, "message" => $e->getMessage()));
       exit();
    }
     if($result) { 
          $d=1;
         while(($row = $this->q->fetchArray($result))==TRUE) { 
             if($this->getServiceOutput()=='option'){
                  $str.="<option value='".$row['unitOfMeasurementId']."'>".$row['unitOfMeasurementCode'].". ".$row['unitOfMeasurementDescription']."</option>";
             } else if ($this->getServiceOutput()=='html')  { 
                 $items[] = $row; 
             }
              $d++;
         }
         unset($d);
     }
     if($this->getServiceOutput()=='option'){
          if (strlen($str) > 0) {
            $str = "<option value=''>".$this->t['pleaseSelectTextLabel']."</option>" . $str; 
         } else {
             $str= "<option value=''>".$this->t['notAvailableTextLabel']."</option>";
         }
         header('Content-Type:application/json; charset=utf-8');
         echo json_encode(array("success"=>true,"message"=>"complete","data"=>$str));
         exit();
     } else if ($this->getServiceOutput()=='html')  { 
         return $items; 
      }
         return false; 
  }
  /**
   * Return Unit Of Measurement Default Value
   * @return int
   * @throws \Exception
    */
    public function getUnitOfMeasurementDefaultValue() { 
     //initialize dummy value.. no content header.pure html  
     $sql=null; 
     $str=null; 
      $unitOfMeasurementId=null;
     if($this->getVendor()==self::MYSQL) { 
         $sql ="  
         SELECT      `unitOfMeasurementId`
         FROM        	`unitofmeasurement`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '".$this->getCompanyId()."'
         AND    	  `isDefault` =	  1
         LIMIT 1"; 
     } else if ($this->getVendor()==self::MSSQL) { 
         $sql =" 
         SELECT      TOP 1 [unitOfMeasurementId],
         FROM        [unitOfMeasurement]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '".$this->getCompanyId()."'
         AND    	  [isDefault] =   1"; 
     } else if ($this->getVendor()==self::ORACLE) { 
         $sql =" 
         SELECT      UNITOFMEASUREMENTID AS \"unitOfMeasurementId\",
         FROM        UNITOFMEASUREMENT  
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '".$this->getCompanyId()."'
         AND    	  ISDEFAULT	  =	   1
         AND 		  ROWNUM	  =	   1";
     }  else {
         header('Content-Type:application/json; charset=utf-8');
         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
         exit();
     }
     try {
       $result =$this->q->fast($sql);
     } catch (\Exception $e) {
       echo json_encode(array("success" => false, "message" => $e->getMessage()));
       exit();
    }
     if($result) { 
         $row = $this->q->fetchArray($result); 
          $unitOfMeasurementId = $row['unitOfMeasurementId'];
     }
     return $unitOfMeasurementId;
 }
       /**
     * Return Business Partner Category
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
                    $str .= "<option value='" . $row['businessPartnerId'] . "'>" . ($d+1) . ". " . $row['businessPartnerCompany'] . "</option>";
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
	 * Return Job based on job
	 * @param int $staffId Staff Primary Key
	 * @return int $jobId Job Primary Key
	 **/
	public function getJob($staffId) {
		 //initialize dummy value.. no content header.pure html
        $sql = null;
        $unitOfMeasurementId = null;
        if ($this->getVendor() == self::MYSQL) {
			$sql = "
			SELECT      `jobId`
			FROM        	`job`
			WHERE       `isActive`  =   1
			AND         `companyId` =   '" . $this->getCompanyId() . "'
			AND    	  `employeeId` =	  ( SELECT employeeId FROM `employeestaffreference` WHERE `staffId`='".$staffId."' LIMIT 1)
			LIMIT 1";
        } else  if ($this->getVendor() == self::MSSQL) {
               $sql = "
			SELECT      TOP 1 `jobId`
			FROM        	`job`
			WHERE       `isActive`  =   1
			AND         `companyId` =   '" . $this->getCompanyId() . "'
			AND    	  `employeeId` =	  ( SELECT employeeId FROM `employeestaffreference` WHERE `staffId`='".$staffId."'  AND ROWNUM=1)";
            } else if ($this->getVendor() == self::ORACLE) {
                    $sql = "
			SELECT      JOBID AS \"jobId\"
			FROM        	`JOB`
			WHERE       `ISACTIVE`  =   1
			AND         `COMPANYID` =   '" . $this->getCompanyId() . "'
			AND    	  `EMPLOYEEID` =	  ( SELECT EMPLOYEEID FROM `EMPLOYEESTAFFREFERENCE` WHERE `STAFFID`='".$staffId."'  AND ROWNUM=1)";
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
	 * Return Purchase Request Approval Amount based on jobId
	 * @param int $purchaseRequestDetailId Purchase Request Detail Primary Key
	 * @param float $amount Amount Requested
	 *  @return bool
	 **/
	public function getPurchaseRequestJobApprovalValue($purchaseRequestDetailId,$amount){
		 //initialize dummy value.. no content header.pure html
		 $jobId=$this->getJob($this->getStaffId());
        $sql = null;
        $unitOfMeasurementId = null;
        if ($this->getVendor() == self::MYSQL) {
			$sql = "
			SELECT      `purchaseRequestJobApprovalAmount`
			FROM        `purchaserequestjob`
			WHERE       `isActive`  =   1
			AND         `companyId` =   '" . $this->getCompanyId() . "'
			AND    	  `jobId` =	  '".$jobId."'
			LIMIT 1";
		} else  if ($this->getVendor() == self::MSSQL) {
			$sql = "
			SELECT      [purchaseRequestJobApprovalAmount]
			FROM        	[purchaseRequestJob]
			WHERE       [isActive]  =   1
			AND         [companyId] =   '" . $this->getCompanyId() . "'
			AND    	  [jobId] =	  '".$jobId."'
			LIMIT 1";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
			SELECT      PURCHASEREQUESTJOBAPPROVALAMOUNT AS  \"purchaseRequestJobApprovalAmount\"
			FROM        	PURCHASEREQUESTJOB
			WHERE       ISACTIVE  =   1
			AND         COMPANYID =   '" . $this->getCompanyId() . "'
			AND    	  JOBID =	  '".$jobId."'
			LIMIT 1";
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
            $purchaseRequestJobApprovalAmount = $row['purchaseRequestJobApprovalAmount'];
			if($amount> $purchaseRequestJobApprovalAmount ) {
				$this->setPurchaseRequestDetailApproved($purchaseRequestDetailId,0);
				echo json_encode(array("success"=>false,"message"=>$this->t['belowRowHaveBeenRejectedLabel']));
				return false;
			} 
        }
        $this->setPurchaseRequestDetailApproved($purchaseRequestDetailId,1);
		echo json_encode(array("success"=>true,"message"=>$this->t['belowRowHaveBeenApprovedLabel']));
		exit();
	}
    /**
     * Reject Request and inform back to requested
     * @param int $purchaseRequestDetailId Purchase Request Detail Primary Key
     * @return void
     */
    public function setPurchaseRequestDetailApproved($purchaseRequestDetailId){
        header('Content-Type:application/json; charset=utf-8');
        //initialize dummy value.. no content header.pure html
        $sql=null;

        if($this->getVendor()==self::MYSQL) {
            $sql ="
            UPDATE  `purchaserequestdetail`
            SET     `isApproved`  =  1
            WHERE   `purchaseRequestDetailId`='".$purchaseRequestDetailId."'";
        } else if ($this->getVendor()==self::MSSQL) {
            $sql ="
            UPDATE  [purchaseRequestDetail]
            SET     [isApproved]  =  1
            WHERE   [purchaseRequestDetailId]='".$purchaseRequestDetailId."'";
        } else if ($this->getVendor()==self::ORACLE) {
            $sql ="
            UPDATE  PURCHASEREQUESTDETAIL
            SET     ISAPPROVED  =  1
            WHERE   PURCHASEREQUESTDETAILID='".$purchaseRequestDetailId."'";
        }  else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $this->createNotification($this->t['approveNotificationLabel']);
    }
    /**
     * Reject Request and inform back to requested
     * @param int $purchaseRequestDetailId Purchase Request Detail Primary Key
     * @return void
     */
    public function setPurchaseRequestDetailRejected($purchaseRequestDetailId){
        header('Content-Type:application/json; charset=utf-8');
        //initialize dummy value.. no content header.pure html
        $sql=null;

        if($this->getVendor()==self::MYSQL) {
            $sql ="
            UPDATE  `purchaserequestdetail`
            SET     `isReject`  =  1
            WHERE   `purchaseRequestId`='".$purchaseRequestDetailId."'";
        } else if ($this->getVendor()==self::MSSQL) {
            $sql ="
            UPDATE  [purchaseRequestDetail]
            SET     [isReject]  =  1
            WHERE   [purchaseRequestId]='".$purchaseRequestDetailId."'";
        } else if ($this->getVendor()==self::ORACLE) {
            $sql ="
            UPDATE  PURCHASEREQUESTDETAIL
            SET     ISREJECT  =  1
            WHERE   PURCHASEREQUESTDETAILID='".$purchaseRequestDetailId."'";
        }  else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $this->createNotification($this->t['rejectNotificationLabel']);
        echo json_encode(array("success"=>"true","message"=>$this->t['rejectNotificationLabel']));
        exit();
    }

    /**
     * Reject Request and inform back to requested
     * @param int $purchaseRequestDetailId Purchase Request Detail Primary Key
     * @return void
     */
    public function setPurchaseRequestDetailReview($purchaseRequestDetailId){
        header('Content-Type:application/json; charset=utf-8');
        //initialize dummy value.. no content header.pure html
        $sql=null;

        if($this->getVendor()==self::MYSQL) {
            $sql ="
            UPDATE  `purchaserequestdetail`
            SET     `isReview`  =  1
            WHERE   `purchaseRequestId`='".$purchaseRequestDetailId."'";
        } else if ($this->getVendor()==self::MSSQL) {
            $sql ="
            UPDATE  [purchaseRequestDetail]
            SET     [isReview]  =  1
            WHERE   [purchaseRequestId]='".$purchaseRequestDetailId."'";
        } else if ($this->getVendor()==self::ORACLE) {
            $sql ="
            UPDATE  PURCHASEREQUESTDETAIL
            SET     ISREVIEW  =  1
            WHERE   PURCHASEREQUESTDETAILID='".$purchaseRequestDetailId."'";
        }  else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $this->createNotification($this->t['reviewNotificationLabel']);
        echo json_encode(array("success"=>"true","message"=>$this->t['reviewNotificationLabel']));
        exit();
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
	 * Return Budget Base On Chart Of Account Primary Key
	 * @param int $chartOfAccountId Chart Of Account Primary Key
	 * @param string $documentDate Document Date
	 * @return double $budgetAmount  Budget Amount
	 */
	public function getBudget($chartOfAccountId,$documentDate) {
		 //initialize dummy value.. no content header.pure html
		$this->ledgerService->setFinancePeriodInformation($documentDate) ;
		$budgetAmount = $this->ledgerService->getBalanceBudget($chartOfAccountId, $this->ledgerService->getFinanceYearId(), $this->ledgerService->getFinancePeriodRangeId()) ;

        return$budgetAmount;
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