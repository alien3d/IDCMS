<?php

namespace Core\HumanResource\Leave\Leave\Service;

use Core\ConfigClass;

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

/**
 * Class LeaveService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\HumanResource\Leave\Leave\Service
 * @subpackage Leave
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class LeaveService extends ConfigClass {

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
     * Return LeaveType
     * @return array|string
     */
    public function getLeaveType() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `leaveTypeId`,
                     `leaveTypeDescription`
         FROM        `leavetype`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [leaveTypeId],
                     [leaveTypeDescription]
         FROM        [leaveType]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      LEAVETYPEID AS \"leaveTypeId\",
                     LEAVETYPEDESCRIPTION AS \"leaveTypeDescription\"
         FROM        LEAVETYPE
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         ORDER BY    ISDEFAULT";
                } else {
                    header('Content-Type:application/json; charset=utf-8');
                    echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
                    exit();
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
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['leaveTypeId'] . "'>" . $d . ". " . $row['leaveTypeDescription'] . "</option>";
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
     * Return Employee
     * @return array|string
     */
    public function getEmployee() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `employeeId`,
                     `employeeFirstName` AS \"employeeName\"
         FROM        `employee`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [employeeId],
                     [employeeFirstName] AS \"employeeName\"
         FROM        [employee]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      EMPLOYEEID AS \"employeeId\",
                     EMPLOYEEFIRSTNAME AS \"employeeName\"
         FROM        EMPLOYEE
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         ORDER BY    ISDEFAULT";
                } else {
                    header('Content-Type:application/json; charset=utf-8');
                    echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
                    exit();
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
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['employeeId'] . "'>" . $d . ". " . $row['employeeName'] . "</option>";
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
     * Return Employee Leave Balance
     * @param int $employeeId Employee
     * @param int $leaveTypeId Leave Category
     * @param null|int $leaveBalanceYear Year
     * @return void
     */
    public function setLeaveBalance($employeeId, $leaveTypeId, $leaveBalanceYear = null) {
        $sql = null;
        if ($leaveBalanceYear == '') {
            $leaveBalanceYear = date('Y');
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			UPDATE `leavebalance`
			SET	   `leaveEmployeeBalance` = `leaveEmployeeBalance` - 1
			WHERE  `employeeId` 		  = '" . $employeeId . "'
			AND    `leaveTypeId`		  =	'" . $leaveTypeId . "'
			AND    `leaveTypeYear		  = '" . $leaveBalanceYear . "'
			";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
			UPDATE [leavebalance]
			SET	   [leaveEmployeeBalance] = [leaveEmployeeBalance] - 1
			WHERE  [employeeId] 		  = '" . $employeeId . "'
			AND    [leaveTypeId]		  =	'" . $leaveTypeId . "'
			AND    [leaveTypeYear]		  = '" . $leaveBalanceYear . "'
			";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
			UPDATE LEAVEBALANCE
			SET	   LEAVEEMPLOYEEBALANCE 	= 	LEAVEEMPLOYEEBALANCE - 1
			WHERE  EMPLOYEEID 		  		= 	'" . $employeeId . "'
			AND    LEAVETYPEID		  		=	'" . $leaveTypeId . "'
			AND    LEAVETYPEYEAR		  	= 	'" . $leaveBalanceYear . "'
			";
                }
            }
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
     * Set Employee Leave Balance  transfer to next year.. (transfer day only)
     * @param int $employeeId Employee
     * @param int $leaveTypeId Leave Type
     * @return void
     */
    public function setTransferLeaveBalance($employeeId, $leaveTypeId) {
        
    }

    /**
     * Set Convert Employee Leave Balance  to certain amount
     * @param int $employeeId Employee
     * @param int $leaveTypeId Leave Category
     * @param int $countryId Country
     * @return double $leaveRateAmount
     */
    public function setConvertLeaveBalance($employeeId, $leaveTypeId, $countryId) {
        $sql = null;
        $leaveRateAmount = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "

			SELECT `leaveRateAmount`
			FROM   `leaverate`
			WHERE  `jobId`		=	(
										SELECT	`jobId`
										FROM 	`employee`
										WHERE 	`employeeId`='" . $employeeId . "'
									)
			AND	  `leaveTypeId`	=	'" . $leaveTypeId . "'
			AND	  `countryId`	=	'" . $countryId . "'
			";
        } else {
            if ($this->getVendor() == self ::MSSQL) {
                $sql = "
			SELECT [leaveRateAmount]
			FROM   [leaverate]
			WHERE  [jobId]		=	(
										SELECT	[jobId]
										FROM 	[employee]
										WHERE 	[employeeId]='" . $employeeId . "'
								)
			AND	  [leaveTypeId]	=	'" . $leaveTypeId . "'
			AND	  [countryId]	=	'" . $countryId . "'
			";
            } else {
                if ($this->getVendor() == self ::ORACLE) {
                    $sql = "
			SELECT LEAVERATEAMOUNT
			FROM   LEAVERATE
			WHERE  JOBID		=	(
										SELECT	`JOBID`
										FROM 	`EMPLOYEE`
										WHERE 	`EMPLOYEEID`='" . $employeeId . "'
									)
			AND	  LEAVETYPEID	=	'" . $leaveTypeId . "'
			AND	  COUNTRYID		=	'" . $countryId . "'
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
            $leaveRateAmount = $row['leaveRateAmount'];
        }
        $totalDays = $this->getLeaveBalance($employeeId, $leaveTypeId);
        $totalAmount = $totalDays * $leaveRateAmount;
        return $totalAmount;
    }

    /**
     * Return Employee Leave Balance
     * @param int $employeeId Employee
     * @param int $leaveTypeId Leave Category
     * @param int $leaveBalanceYear Year
     * @return int $totalBalance
     */
    public function getLeaveBalance($employeeId, $leaveTypeId, $leaveBalanceYear = null) {
        $sql = null;
        $totalBalance = 0;
        if ($leaveBalanceYear == '') {
            $leaveBalanceYear = date('Y');
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
		    SELECT `leaveEmployeeBalance`
			FROM	`leaveemployee`
			WHERE  `employeeId` 		  = '" . $employeeId . "'
			AND    `leaveTypeId`		  =	'" . $leaveTypeId . "'
			AND    `leaveEmployeeYear`	  = '" . $leaveBalanceYear . "'
			";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
			SELECT [leaveEmployee]
			FROM   [leaveemployee]
			WHERE  [employeeId] 		  = '" . $employeeId . "'
			AND    [leaveTypeId]		  =	'" . $leaveTypeId . "'
			AND    [leaveEmployeeYear]	  = '" . $leaveBalanceYear . "'
			";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
			SELECT LEAVEEMPLOYEEBALANCE AS \"leaveEmployeeBalance\"
			FROM   LEAVEEMPLOYEE
			WHERE  EMPLOYEEID 		  		= 	'" . $employeeId . "'
			AND    LEAVETYPEID		  		=	'" . $leaveTypeId . "'
			AND    LEAVEEMPLOYEEYEAR		= 	'" . $leaveBalanceYear . "'
			";
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
            $totalBalance = $row['leaveEmployeeBalance'];
        }

        return $totalBalance;
    }

    /**
     * Transfer Figure Unpaid to minus back timeSheet/payroll
     * @param int $employeeId
     */
    public function unPaidTransaction($employeeId) {
        // Get Total Month -  total workday -  official holiday
        // another one formula  total paidout + allowance  / total month  * day leave.
    }

    /**
     * Approved Leave If this rejected Send Notification
     * @param int $leaveId
     * @param $employeeId
     * @return void
     */
    public function approvedLeave($leaveId, $employeeId) {
        $sql = null;
        $this->q->start();
        $staffId = $this->getStaffId($employeeId);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			UPDATE  `leave`
			SET		`isReview`		=	0
					`isApproved`	=	1
			WHERE 	`leaveId`		=	'" . $leaveId . "'";
        } else {
            if ($this->getVendor() == self ::MSSQL) {
                $sql = "
			UPDATE  [leave]
			SET		[isReview]		=	0
					[isApproved]	=	1
			WHERE 	[leaveId]	=	'" . $leaveId . "'";
            } else {
                if ($this->getVendor() == self ::ORACLE) {
                    $sql = "
			UPDATE   LEAVE
			SET		 ISREVIEW		=	0
					 ISAPPROVED		=	1
			WHERE 	 LEAVEID		=	'" . $leaveId . "'";
                }
            }
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
        }
        $this->createNotification($this->t['leaveRequestDisapprovedTextLabel'], $staffId);
        $this->q->commit();
    }

    /**
     * Reject Leave.Sent Message If this approved Send Notification
     * @param int $leaveId Leave
     * @param int $employeeId Employee
     * @param null|int $staffId Staff
     * @return void
     */
    public function rejectedLeave($leaveId, $employeeId, $staffId = null) {
        $sql = null;
        $this->q->start();
        if (empty($staffId)) {
            $staffId = $this->getStaffId($employeeId);
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			UPDATE  `leave`
			SET		`isReview`		=	0
					`isRejected`	=	1
			WHERE 	`leaveId`		=	'" . $leaveId . "'";
        } else {
            if ($this->getVendor() == self ::MSSQL) {
                $sql = "
			UPDATE  [leave]
			SET		[isReview]		=	0
					[isRejected]	=	1
			WHERE 	[leaveId]		=	'" . $leaveId . "'";
            } else {
                if ($this->getVendor() == self ::ORACLE) {
                    $sql = "
			UPDATE   LEAVE
			SET		 ISREVIEW		=	0
					 ISREJECTED		=	1
			WHERE 	 LEAVEID		=	'" . $leaveId . "'";
                }
            }
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
        }
        $this->createNotification($this->t['leaveRequestDisapprovedTextLabel'], $staffId);
        $this->q->commit();
    }

    /**
     * Return Staff Id
     * @param int $employeeId Employee
     * @return int $staffId
     */
    public function getStaffIdSpecial($employeeId) {
        $sql = null;
        $staffId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT	`staffId` 
			FROM 	`employeestaffreference` 
			WHERE 	`employeeId`='" . $employeeId . "'
			";
        } else {
            if ($this->getVendor() == self ::MSSQL) {
                $sql = "
			SELECT	[staffId]
			FROM 	[employeestaffreference]
			WHERE 	[employeeId]='" . $employeeId . "'
			";
            } else {
                if ($this->getVendor() == self ::ORACLE) {
                    $sql = "
			SELECT	`STAFFID` AS \"staffId\"
			FROM 	`EMPLOYEESTAFFREFERENCE`
			WHERE 	`EMPLOYEEID`='" . $employeeId . "'
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
            $staffId = $row['staffId'];
        }
        return $staffId;
    }

    /**
     * Return Employee Id
     * @param int $staffId Employee
     * @return int $staffId
     */
    public function getEmployeeIdSpecial($staffId) {
        $sql = null;
        $employeeId = null;
        if ($this->getVendor() == self::MYSQL) {

            $sql = "
			SELECT	`employeeId` 
			FROM 	`employeestaffreference` 
			WHERE 	`staffId`='" . $staffId . "'
			";
        } else {
            if ($this->getVendor() == self ::MSSQL) {
                $sql = "
			SELECT	[employeeId]
			FROM 	[employeestaffreference]
			WHERE 	[staffId]='" . $staffId . "'
			";
            } else {
                if ($this->getVendor() == self ::ORACLE) {
                    $sql = "
			SELECT	EMPLOYEEID AS \"employeeId\"
			FROM 	EMPLOYEESTAFFREFERENCE
			WHERE 	EMPLOYEEID`='" . $staffId . "'
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
            $employeeId = $row['employeeId'];
        }
        return $employeeId;
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