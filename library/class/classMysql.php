<?php

namespace Core\Database\Mysql;

/**
 * a specific class for connection to mysql.Either mysql or mysqli
 * @author hafizan
 * @copyright IDCMS
 */
class Vendor
{

    // private property

    /**
     * Connection
     * @var string
     */
    private $connection;

    /**
     * Company Identification
     * @var string
     */
    private $companyId;

    /**
     * Username
     * @var string
     */
    private $username;

    /**
     * Password
     * @var string
     */
    private $password;

    /**
     * Sql Statement Operation
     * @var string
     */
    private $operation;

    /**
     * Database Port .Default 3306
     * @var string
     */
    private $port;

    /**
     * sql statement
     * @var string
     */
    private $socket;
    /**
     * sql statement
     * @var string
     */
    private $sql;

    /**
     * link resources
     * @var string
     */
    public $link;

    /**
     * result statement
     * @var string
     */
    public $result;

    /**
     * @var string
     */
    private $type;

    /**
     * table name for advance log purpose
     * @var string
     */
    private $tableName;

    /**
     * primary key  for advance log purpose
     * @var string
     */
    private $primaryKeyName;

    /**
     * primary key value for advance log purpose
     * @var string
     */
    private $primaryKeyValue;
    /**
     * All Unique Primary Key
     * @var string
     */
    private $primaryKeyAll;

    /**
     * Column Name for advance log purpose
     * @var string
     */
    private $columnName;

    /**
     * Date Filtering Type.E.g Day,Week,Month,Year,Between
     * @var string
     */
    private $dateFilterTypeQuery;

    /**
     * Date Filtering $Extra Type.Next and Previous Day,Week,Month
     * @var string
     */
    private $dateFilterExtraTypeQuery;

    /**
     * Date Filtering Start
     * @var string
     */
    private $startDate;

    /**
     * Date Filtering End
     * @var string
     */
    private $endDate;

    /**
     * Audit Row Trail  1 Audit  0 for not
     * @var boolean $audit
     */
    private $audit;

    /**
     * Audit Log 1 Audit 0 for not
     * @var boolean $log
     */
    private $log;

    /**
     * total record
     * @var number
     */
    private $countRecord;

    /**
     * program identification
     * @var int
     */
    private $applicationId;

    /**
     * module identification
     * @var int
     */
    private $moduleId;

    /**
     * folder identification
     * @var int
     */
    private $folderId;

    /**
     * program identification
     * @var int
     */
    private $leafId;

    /**
     * Database response  if any query fail
     * @var string
     */
    private $response;

    /**
     * to inform user if error
     * @var string $execute
     */
    private $execute;

    /**
     * Field Query UX
     * @var string $fieldQuery
     */
    private $fieldQuery;

    /**
     * Grid  Filter Plugin
     * @var string $gridQuery
     */
    private $gridQuery;

    /**
     * Staff Identification
     * @var int $staffId
     */
    private $staffId;

    /**
     * Is Admin
     */
    private $isAdmin;
    /**
     * Admin Id ?
     * @var int
     */
    private $isAdminId;

    /**
     * Last Insert Id
     * @var int
     */
    public $insertId;

    /**
     *  Core database
     * @var string
     */
    private $coreDatabase;

    /**
     *  Core database
     * @var string
     */
    private $requestDatabase;

    /**
     *  Core database
     * @var string
     */
    private $currentDatabase;
    /**
     * Set Exception Message
     * @var string
     */
    private $exceptionMessage;


    /**
     * Administrator Email
     * @var string
     */
    private $administratorEmail;

    /**
     * Role Primary Key
     * @var int
     */
    private $roleId;
    /**
     * Batch Id
     * @var string
     */
    private $mysqlBatchGuid;
    /**
     * Multi Unique Identification
     * @var array
     */
    private $multiId;
    /**
     * Message
     * @var string
     */
    private $message;

    /**
     /**
* Constructor 
     */
    public function __construct()
    {
        // connection property

        $connection = '127.0.0.1';

        $socket = '3306';

        $username = 'root';


        $password = '123456';


        // set basic info
        $this->setConnection($connection);
        $this->setSocket($socket);
        $this->setUsername($username);
        $this->setPassword($password);


        // set database
        $this->setCoreDatabase('icore');

        if (isset($_SESSION['companyId'])) {
            $this->setCompanyId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        }
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        }
        if (isset($_SESSION['isAdmin'])) {
            $this->setIsAdmin($_SESSION['isAdmin']);
        }
        $this->setAdministratorEmail('hafizanil@gmail.com');
    }

    /**
     * To connect mysql database
     * @throws \Exception
     */
    public function connect()
    {
        $this->setMysqlBatchGuid();
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            $this->setStaffId(2);
        }
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        } else {
            $this->setRoleId(2);
        }
        if (isset($_SESSION['isAdmin'])) {
            $this->setIsAdmin($_SESSION['isAdmin']);
        }
        if (isset($_SESSION['companyId'])) {
            $this->setCompanyId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }

        $this->setLink(@mysqli_connect($this->getConnection(), $this->getUsername(), $this->getPassword()));
        if (!$this->link) {
            $this->setExecute('fail');
            if (mysqli_connect_errno()) {
                $this->setResponse(mysqli_connect_errno());
                header('Content-Type:application/json; charset=utf-8');
                if ($this->getIsAdmin() == 1) {

                    // enable exception message
                    $this->setResponse('Fail To Connect Database : ' . $this->getResponse());

                    echo json_encode(array("success" => false, "message" => $this->getResponse()));
                    exit();
                } else {
                    $this->setResponse(
                        '1. System Error.Please Contact ' . $this->getAdministratorEmail(
                        ) . " Mysql error :" . mysqli_connect_errno()
                    );

                    echo json_encode(array("success" => false, "message" => $this->getResponse()));
                    exit();
                }
            }
        } else {
            $resources = mysqli_select_db($this->getLink(), $this->getCoreDatabase());
            if (!$resources) {
                header('Content-Type:application/json; charset=utf-8');
                $this->setResponse(\mysqli_error($this->getLink()) . "Error Code" . mysqli_errno($this->getLink()));
                $this->setNotification();
                throw new \Exception($this->getResponse());

            }
        }

    }

    /**
     * Turns on or off auto-commit mode on queries for the database connection.
     * To determine the current state of autocommit use the SQL command SELECT @@autocommit.
     */
    public function start()
    {
        mysqli_autocommit($this->getLink(), false);
    }

    /**
     * Query database
     * @param string $sql Structured Query Language
     * @throws \Exception
     * @internal param string $type to identify the query is for view or total record.Available type 1. result type 2 total record* to identify the query is for view or total record.Available type 1. result type 2 total record
     * @return mixed
     */
    private function query($sql)
    {
        $this->sql = null;
        $this->type = null;
        $this->result = null;
        $this->countRecord = null;
        $this->setSql($sql);

        if ($this->getLog() == 1) {
            // push all query here
            $operation = "";
            $access = "";
            $sqlLog = "
			INSERT INTO `logerror` (
                        `companyId`,
                        `applicationId`,
                        `moduleId`,
                        `folderId`,
                        `leafId`,
						`logErrorOperation`,
						`logErrorSql`,
						`logErrorDate`,
						`staffId`,
						`logErrorAccess`,
						`logError`,
						`logErrorGuid`
			) values (
                        '" . $this->getCompanyId() . "',
                        '" . $this->getApplicationId() . "',
                        '" . $this->getModuleId() . "',
                        '" . $this->getFolderId() . "',
                        '" . $this->getLeafId() . "',
                        '" . $this->realEscapeString($operation) . "',
						'" . trim($this->realEscapeString($this->getSql())) . "',
						'" . date("Y-m-d H:i:s") . "',
						'" . $this->getStaffId() . "',
						'" . $access . "',
						'" . $this->realEscapeString($this->getSql()) . "',
						'" . $this->getMysqlBatchGuid() . "'
					)";
            $test1 = mysqli_query($this->getLink(), $sqlLog);
            if (!$test1) {
                if ($this->getIsAdmin() == 1) {
                    $this->setExecute('fail');
                    $this->setResponse($sqlLog . "[" . mysqli_error($this->getLink()) . "]");
                    $this->setNotification();
                    throw new \Exception($this->getResponse());
                } else {
                    $this->setExecute('fail');
                    $this->setResponse("4 System Error.Please Contact " . $this->getAdministratorEmail());
                    throw new \Exception($this->getResponse());
                }
            }
        }
        $this->setResult(mysqli_query($this->getLink(), $this->getSql()));
        if (!$this->getResult()) {
            $this->setExecute('fail');
            $this->setResponse(
                $sql . "<br> sini ada" . mysqli_error($this->getLink()) . "<br> Error Code :y " . mysqli_errno(
                    $this->getLink()
                )
            );

            $sqlLog = "
            INSERT  INTO    `logerror`
            (

                            `moduleId`,
                            `logErrorOperation`,
                            `logErrorsql`,
                            `logErrorDate`,
							`roleId`,
                            `staffId`,
                            `logerror`,
                            `logErrorGuid`
            )   values  (
                '" . $this->getModuleId() . "',
                '" . trim(addslashes($this->getOperation())) . "',
                '" . trim(addslashes($this->getSql())) . "',
                '" . date("Y-m-d H:i:s") . "',
				'" . $this->getRoleId() . "',
                '" . $this->getStaffId() . "',
                '" . trim(addslashes($this->getResponse())) . "',
                '" . $this->getMysqlBatchGuid() . "'
            )";
            $resultRow = mysqli_query($this->getLink(), $sqlLog);
            if (!$resultRow) {
                if ($this->getIsAdmin() == 1) {
                    $this->setExecute('fail');
                    $this->setResponse(
                        $sql . "<br> not log issue" . mysqli_error(
                            $this->getLink()
                        ) . "<br> Error Code :y " . mysqli_errno($this->getLink())
                    );
                    throw new \Exception($this->getResponse());
                } else {
                    $this->setExecute('fail');
                    $this->setResponse("2 System Error.Please Contact " . $this->getAdministratorEmail());
                    throw new \Exception($this->getResponse());
                }
            }
            if ($this->getIsAdmin() == 1) {
                $this->setExecute('fail');
                if ($this->getIsAdmin() == 1) {
                    $this->setResponse(
                        "<br><b>Structured Query Language(SQL) Error </b> :   " . $this->sql . " \n\r" . mysqli_error(
                            $this->getLink()
                        ) . "  <b>Error Code</b> : [ " . mysqli_errno($this->getLink()) . " ] "
                    );
                } else {
                    $this->setResponse(
                        "<img src='./images/icons/smiley-roll.png'> System Have Error .Please Contact Administrator : " . $this->getAdministratorEmail(
                        )
                    );
                }
                $this->setNotification();
                //throw new \Exception($this->getResponse());
                echo json_encode(array("success" => false, "message" => $this->getResponse()));
                exit();
            } else {
                // here might have session issue problem
                $this->setExecute('fail');
                if (empty($_SESSION['staffId'])) {
                    $this->setResponse("Session Timout. Please Refresh the page ");
                    $this->exceptionMessage($this->getResponse());
                    exit();
                } else {
                    $this->setResponse("M. System Error.Please Contact " . $this->getAdministratorEmail());
                    //throw new \Exception($this->getResponse());
                    echo json_encode(array("success" => false, "message" => $this->getResponse()));
                    exit();
                }
            }
        }

        //	$this->rollback();
        return 0;
    }

    /**
     * for checking sql statement either it works or not.If no log table error
     * @param string $operation Structured Query Language operation add,edit,update,delete,review
     * @param null $type application,module,folder,leaf
     * @throws \Exception
     * @return int
     */
    private function module($operation, $type = null)
    {
        // for more secure option must SET at mysql access grant level
        // if 1 access granted which mean 1 record if null no mean no access to the db level
        $access = "";
        $this->setOperation($operation);

        if ($type == 'application') {
            $sql = "
            SELECT 	*
            FROM 	`applicationaccess`
            WHERE  	`applicationaccess`.`folderId`          =   '" . intval($this->getApplicationId()) . "'
            AND   	`applicationaccess`.`folderaccessValue` =   '1'
            AND   	`applicationaccess`.`roleId`            =   '" . intval($this->getRoleId()) . "'";
        } else if ($type == 'module') {
            $sql = "
            SELECT 	*
            FROM 	`moduleaccess`
            WHERE  	`moduleaccess`.`moduleId`           =	'" . intval($this->getModuleId()) . "'
            AND   	`moduleaccess`.`moduleaccessValue`  =	'1'
            AND   	`moduleaccess`.`roleId`             =	'" . intval($this->getRoleId()) . "'";
        } else if ($type == 'folder') {
            $sql = "
            SELECT 	*
            FROM 	`folderaccess`
            WHERE  	`folderaccess`.`folderId`           =	'" . intval($this->getFolderId()) . "'
            AND   	`folderaccess`.`moduleaccessValue`  =	'1'
            AND   	`folderaccess`.`roleId`             =	'" . intval($this->getRoleId()) . "'";
        } else if ($type == 'leaf') {
                $sql = "
            SELECT 	*
            FROM 	`leafaccess`
            WHERE  	`leafaccess`.`leafId`			=	'" . intval($this->getLeafId()) . "'
            AND   	`leafaccess`.`" . $this->getOperation() . "`	=	'1'
            AND   	`leafaccess`.`staffId`		=	'" . intval($this->getStaffId()) . "'";
            } else {
                // $this->setResponse("Must check if anything wrong :[".$type."->".$this->getOperation()."]");
                // $this->setNotification();
                return 1;
            }
        
        $result = mysqli_query($this->getLink(), $sql);
        if (!$result) {
            if ($this->getIsAdmin() == 1) {
                $this->setExecute('fail');
                $this->setResponse("<" . $sql . ">" . mysqli_error($this->getLink()));
                $this->setNotification();
                throw new \Exception($this->getResponse());
            } else {
                $this->setExecute('fail');
                $this->setResponse("3 System Error.Please Contact " . $this->getAdministratorEmail());
                throw new \Exception($this->getResponse());
            }
        } else {
            $resultRow = intval(@mysqli_num_rows($result));
        }
        if ($resultRow == 1) {
            $access = 'Granted';
        } elseif ($resultRow == 0) {
            $this->setResponse("Denied <" . $sql . ">");
            $access = 'Denied';
        }

        if ($resultRow == 0) {
            $logError = $this->response;

            $sqlLog = "
			INSERT INTO `logerror`
					(
						`companyId`,
						`applicationId`,
						`moduleId`,
						`folderId`,
						`leafId`,
						`logErrorOperation`,
						`logErrorSql`,
						`logErrorDate`,
						`roleId`,
						`staffId`,		
						`logErrorAccess`,
						`logError`,
						`logErrorGuid`
					)
			values
					(
						'" . $this->getCompanyId() . "',
						'" . $this->getApplicationId() . "',
						'" . $this->getModuleId() . "',
						'" . $this->getFolderId() . "',
					    '" . $this->getLeafId() . "',
					    '" . $operation . "',
						'" . trim($this->realEscapeString($this->sql)) . "',		'" . date("Y-m-d H:i:s") . "',
						'" . $this->getStaffId() . "',								'" . $this->getStaffId() . "',						
						'" . $this->realEscapeString(
                    $access
                ) . "',											'" . $this->realEscapeString($logError) . "',
						'" . $this->getMysqlBatchGuid() . "'
					)";
            $test1 = mysqli_query($this->getLink(), $sqlLog);
            if (!$test1) {
                if ($this->getIsAdmin() == 1) {
                    $this->setExecute('fail');
                    $this->setResponse($sqlLog . "[" . mysqli_error($this->getLink()) . "]");
                    $this->setNotification();
                    throw new \Exception($this->getResponse());
                } else {
                    $this->setExecute('fail');
                    $this->setResponse("4 System Error.Please Contact " . $this->getAdministratorEmail());
                    throw new \Exception($this->getResponse());
                }
            }
        }

        return ($resultRow);
    }

    /**
     * this is for certain page which don't required to check access page
     * @param string $sql Structured Query Language
     * @return int
     * @throws \Exception
     */
    public function queryPage($sql)
    {
        if (strlen($sql) > 0) {
            $this->sql = $sql;
            return ($this->query($this->sql));
        } else {
            if ($this->getIsAdmin() == 1) {
                $this->setExecute('fail');
                $this->setResponse("16x Where's the query forgot Yax! ..[" . $sql . "]");
                $this->setNotification();
                throw new \Exception($this->getResponse());
            } else {
                $this->setExecute('fail');
                $this->setResponse("5 System Error.Please Contact " . $this->getAdministratorEmail());
                throw new \Exception($this->getResponse());
            }
        }
    }


    public function delete($sql)
    {
		$this->sql = null;
        $this->sql = $sql;
        $text = null;
        $textComparison = null;
        $fieldValue = array();
        $previous = "";
        if (strlen($sql) > 0) {
            if ($this->module("leafAccessDeleteValue","leaf") == 1) {
                if ($this->audit == 1) {
                    $logAdvanceType = 'U';
                    $sqlColumn = "SHOW COLUMNS FROM `" . strtolower($this->getTableName()) . "`";
                    $resultColumn = mysqli_query($this->getLink(), $sqlColumn);
                    if (!$resultColumn) {
                        if ($this->getIsAdmin() == 1) {
                            $this->setExecute('fail');
                            $this->setResponse(
                                "Error Message : [ " . mysqli_error(
                                    $this->getLink()
                                ) . "]. Error Code : [" . mysqli_errno(
                                    $this->getLink()
                                ) . " ]. Error Sql Statement : [" . $sqlColumn . "]"
                            );
                            $this->setNotification();
                            throw new \Exception($this->getResponse());
                        } else {
                            $this->setExecute('fail');
                            $this->setResponse("11 System Error.Please Contact " . $this->getAdministratorEmail());
                            throw new \Exception($this->getResponse());
                        }
                    } else {
                        while (($rowColumn = \mysqli_fetch_array($resultColumn, MYSQLI_BOTH)) == true) {
                            $fieldValue [] = $rowColumn ['Field'];
                        }
                    }
                    $sqlPrevious = null;
                    if ($this->getMultiId() == 1) {
                        $sqlPrevious = "
                        SELECT 	*
                        FROM 	`" . strtolower($this->getTableName()) . "`
                        WHERE 	`" . $this->getPrimaryKeyName() . "` IN (" . $this->getPrimaryKeyAll() . ")";
                    } else {
                        $sqlPrevious = "
                        SELECT 	*
                        FROM 	`" . strtolower($this->getTableName()) . "`
                        WHERE 	`" . $this->getPrimaryKeyName() . "` = '" . $this->getPrimaryKeyValue() . "'";
                    }
                    $resultPrevious = \mysqli_query($this->getLink(), $sqlPrevious);
                    if (!$resultPrevious) {
                        if ($this->getIsAdmin() == 1) {
                            $this->setExecute('fail');
                            $this->setResponse(
                                "Error Message : [ " . mysqli_error(
                                    $this->getLink()
                                ) . "]. Error Code : [" . mysqli_errno(
                                    $this->getLink()
                                ) . " ]. Error Sql Statement : [" . $sqlPrevious . "]"
                            );
                            $this->setNotification();
                            throw new \Exception($this->getResponse());
                        } else {
                            $this->setExecute('fail');
                            $this->setResponse("12 .. System Error.Please Contact " . $this->getAdministratorEmail());
                            throw new \Exception($this->getResponse());
                        }
                    } else {
                        while (($rowPrevious = mysqli_fetch_array($resultPrevious, MYSQLI_BOTH)) == true) {
                            foreach ($fieldValue as $field) {
                                $text .= "\"" . $field . "\":\"" . $rowPrevious [$field] . "\",";
                                $previous [$field] = $rowPrevious [$field];
                            }
                        }
                    }
                    $text = $this->removeComa($text);
                    $text = "{" . $text . "}";
                    $sqlLogAdvance = "
					INSERT INTO	`logadvance`
							(
								`companyId`,
								`logAdvanceText`,
								`logAdvanceType`,
								`logAdvanceRefTableName`,
								`leafId`,
								`logAdvanceGuid`
							)
					VALUES
							(
								'" . $this->getCompanyId() . "',
								'" . $this->realEscapeString($text) . "',
								'" . $this->realEscapeString($logAdvanceType) . "',
								'" . $this->getTableName() . "',
								'" . $this->getLeafId() . "',
								'" . $this->getMysqlBatchGuid() . "'
					)";
                    $resultLogAdvance = mysqli_query($this->getLink(), $sqlLogAdvance);
                    if ($resultLogAdvance) {
                        $logAdvanceId = mysqli_insert_id($this->getLink());
                    } else {
                        if ($this->getIsAdmin() == 1) {
                            $this->setExecute('fail');
                            $this->setResponse(
                                "Error Message : [ " . mysqli_error(
                                    $this->getLink()
                                ) . "]. Error Code : [" . mysqli_errno(
                                    $this->getLink()
                                ) . " ]. Error Sql Statement : [" . $sqlLogAdvance . "]"
                            );
                            $this->setNotification();
                            throw new \Exception($this->getResponse());
                        } else {
                            $this->setExecute('fail');
                            $this->setResponse("13 System Error.Please Contact " . $this->getAdministratorEmail());
                            throw new \Exception($this->getResponse());
                        }
                    }
                }

                $this->query($this->sql);
                $recordAffected = $this->affectedRows();
                if ($this->getAudit() == 1) {
                    $sqlCurrent = null;
                    if ($this->getMultiId() == 1) {
                        $sqlCurrent = "
                        SELECT 	*
                        FROM 	`" . strtolower($this->getTableName() ). "`
                        WHERE 	`" . $this->getPrimaryKeyName() . "` IN (" . $this->getPrimaryKeyAll() . ")";
                    } else {
                        $sqlCurrent = "
                        SELECT 	*
                        FROM 	`" . strtolower($this->getTableName()) . "`
                        WHERE 	`" . $this->getPrimaryKeyName() . "`='" . $this->getPrimaryKeyValue() . "'";
                    }
                    $resultCurrent = mysqli_query($this->getLink(), $sqlCurrent);
                    if (!$resultCurrent) {
                        if ($this->getIsAdmin() == 1) {
                            $this->setExecute('fail');
                            $this->setResponse(
                                "Error Message : [ " . mysqli_error(
                                    $this->getLink()
                                ) . "]. Error Code : [" . mysqli_errno(
                                    $this->getLink()
                                ) . " ]. Error Sql Statement : [" . $sqlCurrent . "]"
                            );
                            $this->setNotification();
                            throw new \Exception($this->getResponse());
                        } else {
                            $this->setExecute('fail');
                            $this->setResponse("14 System Error.Please Contact " . $this->getAdministratorEmail());
                            throw new \Exception($this->getResponse());
                        }
                    } else {
                        while (($rowCurrent = mysqli_fetch_array($resultCurrent, MYSQLI_BOTH)) == true) {
                            $textComparison .= $this->compare($fieldValue, $rowCurrent, $previous);
                        }
                    }
                    $textComparison = substr($textComparison, 0, -1);
                    $textComparison = "{ \"table\":\"" . $this->getTableName(
                        ) . "\",\"leafId\":\"" . $this->getPrimaryKeyName() . "\"," . $textComparison . "}";
                    if (isset($logAdvanceId)) {
                        if (intval($logAdvanceId) > 0) {
                            $sql = "
                    UPDATE  `logadvance`
                    SET     `logAdvanceComparison` =   '" . $this->realEscapeString($textComparison) . "',
                            `executeBy`             =   '" . $this->getStaffId() . "',
                            `executeTime`           =	'" . date("Y-m-d H:i:s") . "'
                    WHERE   `logAdvanceId`          =	'" . $logAdvanceId . "'";
                        }
                    }
                    $result = mysqli_query($this->getLink(), $sql);
                    if (!$result) {
                        if ($this->getIsAdmin() == 1) {
                            $this->setExecute('fail');
                            $this->setResponse(
                                "Error Message : [ " . mysqli_error(
                                    $this->getLink()
                                ) . "]. Error Code : [" . mysqli_errno(
                                    $this->getLink()
                                ) . " ]. Error Sql Statement : [" . $sql . "]"
                            );
                            $this->setNotification();
                            throw new \Exception($this->getResponse());
                        } else {
                            $this->setExecute('fail');
                            $this->setResponse("15 System Error.Please Contact " . $this->getAdministratorEmail());
                            throw new \Exception($this->getResponse());
                        }
                    }
                }
                return $recordAffected;
            } else {
                if ($this->getIsAdmin() == 1) {
                    $this->setExecute('fail');
                    $this->setResponse('access denied lol.update');
                    $this->setNotification();
                    throw new \Exception($this->getResponse());
                } else {
                    $this->setExecute('fail');
                    $this->setResponse('access denied ');
                    throw new \Exception($this->getResponse());
                }
            }
        } else {
            if ($this->getIsAdmin() == 1) {
                //$this->traceError();
                $this->setExecute('fail');
                $this->setResponse("12x Where's the query forgot Ya!");
                $this->setNotification();
                throw new \Exception($this->getResponse());
            } else {
                $this->setExecute('fail');
                $this->setResponse("17 System Error.Please Contact " . $this->getAdministratorEmail());
                throw new \Exception($this->getResponse());
            }
        }
    }

    /**
     * Insert Record
     * @param string $sql Structured Query Language
     * @throws \Exception
     */
    public function create($sql)
    {

        $this->sql = null;
        $this->sql = $sql;
        $text = null;
        $fieldValue = array();
        $previous = array();
        if (strlen($sql) > 0) {
            if ($this->module('leafAccessCreateValue') == 1) {
                $this->query($this->sql);
                if ($this->getAudit() == 1) {
                    $this->insertId = $this->lastInsertId();
                }
                if ($this->getAudit() == 1) {

                    $logAdvanceType = 'C';
                    $sqlColumn = "SHOW COLUMNS FROM `" . strtolower($this->tableName) . "`";
                    $resultColumn = mysqli_query($this->getLink(), $sqlColumn);
                    if (!$resultColumn) {
                        if ($this->getIsAdmin() == 1) {
                            $this->setExecute('fail');
                            $this->setResponse(
                                "Error Message : [ " . mysqli_error(
                                    $this->getLink()
                                ) . "]. Error Code : [" . mysqli_errno(
                                    $this->getLink()
                                ) . " ]. Error Sql Statement : [" . $sqlColumn . "] (CSC)"
                            );
                            throw new \Exception($this->getResponse());
                        } else {
                            $this->setExecute('fail');
                            $this->setResponse("6 System Error.Please Contact " . $this->getAdministratorEmail());
                            throw new \Exception($this->getResponse());
                        }
                    } else {
                        while (($rowColumn = mysqli_fetch_array($resultColumn, MYSQLI_BOTH)) == true) {
                            $fieldValue [] = $rowColumn ['Field'];
                        }
                    }
                    $sqlPrevious = "
                    SELECT 	*
                    FROM 	`" . strtolower($this->getTableName()) . "`
                    WHERE 	`" . $this->getPrimaryKeyName() . "` = '" . $this->lastInsertId() . "'";
                    $resultPrevious = mysqli_query($this->getLink(), $sqlPrevious);
                    if (!$resultPrevious) {
                        if ($this->getIsAdmin() == 1) {
                            $this->setExecute('fail');
                            $this->setResponse(
                                "Error Message : [ " . mysqli_error(
                                    $this->getLink()
                                ) . "]. Error Code : [" . mysqli_errno(
                                    $this->getLink()
                                ) . " ]. Error Sql Statement : [" . $sqlPrevious . "]"
                            );
                            $this->setNotification();
                            throw new \Exception($this->getResponse());
                        } else {
                            $this->setExecute('fail');
                            $this->setResponse("7 System Error.Please Contact " . $this->getAdministratorEmail());
                            throw new \Exception($this->getResponse());
                        }
                    } else {
                        while (($rowPrevious = \mysqli_fetch_array($resultPrevious, MYSQLI_BOTH)) == true) {
                            foreach ($fieldValue as $field) {
                                $text .= "'" . $field . "':'" . $rowPrevious [$field] . "',";
                                $previous [$field] = $rowPrevious [$field];
                            }
                        }
                    }
                    $text = $this->removeComa($text);
                    $text = "{" . $text . "}";
                    $sqlLogAdvance = "
					INSERT INTO	`logadvance`
							(
								`logAdvanceText`,
								`logAdvanceType`,
								`logAdvanceRefTableName`,
								`moduleId`,
								`executeBy`,
								`executeTime`,
								`logAdvanceGuid`
							)
					VALUES
							(
								'" . $this->realEscapeString($text) . "',
								'" . $this->realEscapeString($logAdvanceType) . "',
								'" . $this->getTableName() . "',
								'" . $this->getModuleId() . "',
								'" . $this->getStaffId() . "',
								'" . date("Y-m-d H:i:s") . "',
								'" . $this->mysqlBatchGuid . "'
							)";
                    $resultLogAdvance = mysqli_query($this->getLink(), $sqlLogAdvance);
                    if (!$resultLogAdvance) {
                        if ($this->getIsAdmin() == 1) {
                            $this->setExecute('fail');
                            $this->setResponse(
                                "Error Message : [ " . mysqli_error(
                                    $this->getLink()
                                ) . "]. Error Code : [" . mysqli_errno(
                                    $this->getLink()
                                ) . " ]. Error Sql Statement : [" . $sqlLogAdvance . "]"
                            );
                            $this->setNotification();
                        } else {
                            $this->setExecute('fail');
                            $this->setResponse("8 System Error.Please Contact " . $this->getAdministratorEmail());
                        }
                    }
                }
            } else {
                if ($this->getIsAdmin() == 1) {
                    $this->setExecute('fail');
                    $this->setResponse("No access ");
                    $this->setNotification();
                    throw new \Exception($this->getResponse());
                } else {
                    $this->setExecute('fail');
                    $this->setResponse("9 System Error.Please Contact " . $this->getAdministratorEmail());
                    throw new \Exception($this->getResponse());
                }
            }
        } else {
            if ($this->getIsAdmin() == 1) {
                $this->setExecute('fail');
                $this->setResponse("10 Where's the query forgot Ya!");
                $this->setNotification();
                throw new \Exception($this->getResponse());
            } else {
                $this->setExecute('fail');
                $this->setResponse("10 System Error.Please Contact " . $this->getAdministratorEmail());
                throw new \Exception($this->getResponse());
            }
        }
    }

    /**
     * Update record
     * @param string $sql Structured Query Language
     * @return int
     * @throws \Exception
     */
    public function update($sql)
    {
        $this->sql = null;
        $this->sql = $sql;
        $text = null;
        $textComparison = null;
        $fieldValue = array();
        $previous = "";
        if (strlen($sql) > 0) {
            if ($this->module("leafAccessUpdateValue") == 1) {
                if ($this->audit == 1) {
                    $logAdvanceType = 'U';
                    $sqlColumn = "SHOW COLUMNS FROM `" . strtolower($this->getTableName()) . "`";
                    $resultColumn = mysqli_query($this->getLink(), $sqlColumn);
                    if (!$resultColumn) {
                        if ($this->getIsAdmin() == 1) {
                            $this->setExecute('fail');
                            $this->setResponse(
                                "Error Message : [ " . mysqli_error(
                                    $this->getLink()
                                ) . "]. Error Code : [" . mysqli_errno(
                                    $this->getLink()
                                ) . " ]. Error Sql Statement : [" . $sqlColumn . "]"
                            );
                            $this->setNotification();
                            throw new \Exception($this->getResponse());
                        } else {
                            $this->setExecute('fail');
                            $this->setResponse("11 System Error.Please Contact " . $this->getAdministratorEmail());
                            throw new \Exception($this->getResponse());
                        }
                    } else {
                        while (($rowColumn = \mysqli_fetch_array($resultColumn, MYSQLI_BOTH)) == true) {
                            $fieldValue [] = $rowColumn ['Field'];
                        }
                    }
                    $sqlPrevious = null;
                    if ($this->getMultiId() == 1) {
                        $sqlPrevious = "
                        SELECT 	*
                        FROM 	`" . strtolower($this->getTableName()) . "`
                        WHERE 	`" . $this->getPrimaryKeyName() . "` IN (" . $this->getPrimaryKeyAll() . ")";
                    } else {
                        $sqlPrevious = "
                        SELECT 	*
                        FROM 	`" . strtolower($this->getTableName()) . "`
                        WHERE 	`" . $this->getPrimaryKeyName() . "` = '" . $this->getPrimaryKeyValue() . "'";
                    }
                    $resultPrevious = \mysqli_query($this->getLink(), $sqlPrevious);
                    if (!$resultPrevious) {
                        if ($this->getIsAdmin() == 1) {
                            $this->setExecute('fail');
                            $this->setResponse(
                                "Error Message : [ " . mysqli_error(
                                    $this->getLink()
                                ) . "]. Error Code : [" . mysqli_errno(
                                    $this->getLink()
                                ) . " ]. Error Sql Statement : [" . $sqlPrevious . "]"
                            );
                            $this->setNotification();
                            throw new \Exception($this->getResponse());
                        } else {
                            $this->setExecute('fail');
                            $this->setResponse("12 .. System Error.Please Contact " . $this->getAdministratorEmail());
                            throw new \Exception($this->getResponse());
                        }
                    } else {
                        while (($rowPrevious = mysqli_fetch_array($resultPrevious, MYSQLI_BOTH)) == true) {
                            foreach ($fieldValue as $field) {
                                $text .= "\"" . $field . "\":\"" . $rowPrevious [$field] . "\",";
                                $previous [$field] = $rowPrevious [$field];
                            }
                        }
                    }
                    $text = $this->removeComa($text);
                    $text = "{" . $text . "}";
                    $sqlLogAdvance = "
					INSERT INTO	`logadvance`
							(
								`companyId`,
								`logAdvanceText`,
								`logAdvanceType`,
								`logAdvanceRefTableName`,
								`leafId`,
								`logAdvanceGuid`
							)
					VALUES
							(
								'" . $this->getCompanyId() . "',
								'" . $this->realEscapeString($text) . "',
								'" . $this->realEscapeString($logAdvanceType) . "',
								'" . $this->getTableName() . "',
								'" . $this->getLeafId() . "',
								'" . $this->getMysqlBatchGuid() . "'
					)";
                    $resultLogAdvance = mysqli_query($this->getLink(), $sqlLogAdvance);
                    if ($resultLogAdvance) {
                        $logAdvanceId = mysqli_insert_id($this->getLink());
                    } else {
                        if ($this->getIsAdmin() == 1) {
                            $this->setExecute('fail');
                            $this->setResponse(
                                "Error Message : [ " . mysqli_error(
                                    $this->getLink()
                                ) . "]. Error Code : [" . mysqli_errno(
                                    $this->getLink()
                                ) . " ]. Error Sql Statement : [" . $sqlLogAdvance . "]"
                            );
                            $this->setNotification();
                            throw new \Exception($this->getResponse());
                        } else {
                            $this->setExecute('fail');
                            $this->setResponse("13 System Error.Please Contact " . $this->getAdministratorEmail());
                            throw new \Exception($this->getResponse());
                        }
                    }
                }

                $this->query($this->sql);
                $recordAffected = $this->affectedRows();
                if ($this->getAudit() == 1) {
                    $sqlCurrent = null;
                    if ($this->getMultiId() == 1) {
                        $sqlCurrent = "
                        SELECT 	*
                        FROM 	`" . strtolower($this->getTableName() ). "`
                        WHERE 	`" . $this->getPrimaryKeyName() . "` IN (" . $this->getPrimaryKeyAll() . ")";
                    } else {
                        $sqlCurrent = "
                        SELECT 	*
                        FROM 	`" . strtolower($this->getTableName()) . "`
                        WHERE 	`" . $this->getPrimaryKeyName() . "`='" . $this->getPrimaryKeyValue() . "'";
                    }
                    $resultCurrent = mysqli_query($this->getLink(), $sqlCurrent);
                    if (!$resultCurrent) {
                        if ($this->getIsAdmin() == 1) {
                            $this->setExecute('fail');
                            $this->setResponse(
                                "Error Message : [ " . mysqli_error(
                                    $this->getLink()
                                ) . "]. Error Code : [" . mysqli_errno(
                                    $this->getLink()
                                ) . " ]. Error Sql Statement : [" . $sqlCurrent . "]"
                            );
                            $this->setNotification();
                            throw new \Exception($this->getResponse());
                        } else {
                            $this->setExecute('fail');
                            $this->setResponse("14 System Error.Please Contact " . $this->getAdministratorEmail());
                            throw new \Exception($this->getResponse());
                        }
                    } else {
                        while (($rowCurrent = mysqli_fetch_array($resultCurrent, MYSQLI_BOTH)) == true) {
                            $textComparison .= $this->compare($fieldValue, $rowCurrent, $previous);
                        }
                    }
                    $textComparison = substr($textComparison, 0, -1);
                    $textComparison = "{ \"table\":\"" . $this->getTableName(
                        ) . "\",\"leafId\":\"" . $this->getPrimaryKeyName() . "\"," . $textComparison . "}";
                    if (isset($logAdvanceId)) {
                        if (intval($logAdvanceId) > 0) {
                            $sql = "
                    UPDATE  `logadvance`
                    SET     `logAdvanceComparison` =   '" . $this->realEscapeString($textComparison) . "',
                            `executeBy`             =   '" . $this->getStaffId() . "',
                            `executeTime`           =	'" . date("Y-m-d H:i:s") . "'
                    WHERE   `logAdvanceId`          =	'" . $logAdvanceId . "'";
                        }
                    }
                    $result = mysqli_query($this->getLink(), $sql);
                    if (!$result) {
                        if ($this->getIsAdmin() == 1) {
                            $this->setExecute('fail');
                            $this->setResponse(
                                "Error Message : [ " . mysqli_error(
                                    $this->getLink()
                                ) . "]. Error Code : [" . mysqli_errno(
                                    $this->getLink()
                                ) . " ]. Error Sql Statement : [" . $sql . "]"
                            );
                            $this->setNotification();
                            throw new \Exception($this->getResponse());
                        } else {
                            $this->setExecute('fail');
                            $this->setResponse("15 System Error.Please Contact " . $this->getAdministratorEmail());
                            throw new \Exception($this->getResponse());
                        }
                    }
                }
                return $recordAffected;
            } else {
                if ($this->getIsAdmin() == 1) {
                    $this->setExecute('fail');
                    $this->setResponse('access denied lol.update');
                    $this->setNotification();
                    throw new \Exception($this->getResponse());
                } else {
                    $this->setExecute('fail');
                    $this->setResponse("16 System Error.Please Contact " . $this->getAdministratorEmail());
                    throw new \Exception($this->getResponse());
                }
            }
        } else {
            if ($this->getIsAdmin() == 1) {
                //$this->traceError();
                $this->setExecute('fail');
                $this->setResponse("12x Where's the query forgot Ya!");
                $this->setNotification();
                throw new \Exception($this->getResponse());
            } else {
                $this->setExecute('fail');
                $this->setResponse("17 System Error.Please Contact " . $this->getAdministratorEmail());
                throw new \Exception($this->getResponse());
            }
        }
    }

    /**
     * for view record
     * @param string $sql Structured Query Language
     * @return int
     * @throws \Exception
     */
    public function read($sql)
    {
        $this->setSql($sql);

        /*
         *  Test string of sql statement.If forgot or not
         */
        if (strlen($this->getSql()) > 0) {
            if ($this->module("leafAccessReadValue") == 1) {
                return ($this->query($this->sql));
            } else {
                if ($this->getIsAdmin() == 0) {
                    $this->setExecute('fail');
                    $this->setResponse(" Access Denied View ");
                    $this->setNotification();
                    throw new \Exception($this->getResponse());
                } else if ($this->getIsAdmin() == 1) {
                    $this->setExecute('fail');
                    $this->setResponse(" Access Denied :" . $this->sql);
                    $this->setNotification();
                    throw new \Exception($this->getResponse());
                } else {
                    $this->setExecute('fail');
                    $this->setResponse("18 System Error.Please Contact " . $this->getAdministratorEmail());
                    throw new \Exception($this->getResponse());
                }
            }
        } else {
            if ($this->getIsAdmin() == 1) {
                $this->setExecute('fail');
                $this->setResponse("13x Where's the query forgot Ya!");
                if (empty($_SESSION['staffId'])) {
                    $this->setResponse("Sorry.Session out.Please login back");
                }
                //  debug_backtrace();
                $this->setNotification();
                throw new \Exception($this->getResponse());
            } else {
                $this->setExecute('fail');
                $this->setResponse("19 System Error.Please Contact " . $this->getAdministratorEmail());
                throw new \Exception($this->getResponse());
            }
        }
    }

    /**
     * Fast Query Without Log and Return like normal resources query
     * @param string $sql
     * @return bool|\mysqli_result
     * @throws \Exception
     */
    public function file($sql)
    {
        if ($this->getLog() == 1) {
            // push all query here
            $operation = "";
            $access = "";
            $sqlLog = "
			INSERT INTO `logerror` (
                        `companyId`,
                        `applicationId`,
                        `moduleId`,
                        `folderId`,
                        `leafId`,
						`logErrorOperation`,
						`logErrorSql`,
						`logErrorDate`,
						`staffId`,
						`logErrorAccess`,
						`logError`,
						`logErrorGuid`
			) values (
                        '" . $this->getCompanyId() . "',
                        '" . $this->getApplicationId() . "',
                        '" . $this->getModuleId() . "',
                        '" . $this->getFolderId() . "',
                        '" . $this->getLeafId() . "',
                        '" . $this->realEscapeString($operation) . "',
						'" . trim($this->realEscapeString($sql)) . "',
						'" . date("Y-m-d H:i:s") . "',
						'" . $this->getStaffId() . "',
						'" . $access . "',
						'" . $this->realEscapeString($sql) . "',
						'" . $this->getMysqlBatchGuid() . "'
					)";
            $test1 = mysqli_query($this->getLink(), $sqlLog);
            if (!$test1) {
                if ($this->getIsAdmin() == 1) {
                    $this->setExecute('fail');
                    $this->setResponse($sqlLog . "[" . mysqli_error($this->getLink()) . "]");
                    $this->setNotification();
                    throw new \Exception($this->getResponse());
                } else {
                    $this->setExecute('fail');
                    $this->setResponse("4 System Error.Please Contact " . $this->getAdministratorEmail());
                    throw new \Exception($this->getResponse());
                }
            }
        }
        $this->sql = null;
        $this->sql = $sql;
        /*
         *  check if the programmer put query on sql or not
         */
        if (strlen($this->sql) > 0) {
            $result = mysqli_query($this->getLink(), $this->sql);
            if (!$result) {
                if ($this->getIsAdmin() == 1) {
                    $this->setExecute('fail');
                    $this->setResponse(
                        "Sql Statement Error" . $this->sql . " \n\r" . mysqli_error(
                            $this->getLink()
                        ) . " <br> Error Code :x " . mysqli_errno($this->getLink())
                    );
                    $this->setNotification();
                    throw new \Exception($this->getResponse());
                } else {
                    $this->setExecute('fail');
                    $this->setResponse("20 System Error.Please Contact " . $this->getAdministratorEmail());
                    throw new \Exception($this->getResponse());
                }
            } else {
                return $result;
            }
        } else {
            if ($this->getIsAdmin() == 1) {
                $this->setExecute('fail');
                $this->setResponse("14x Where's the query forgot Ya!");
                $this->setNotification();
                throw new \Exception($this->getResponse());
            } else {
                $this->setExecute('fail');
                $this->setResponse("21  " . $this->getAdministratorEmail());
                throw new \Exception($this->getResponse());
            }
        }
    }

    /**
     * Fast Query Without Log and Return like normal resources query
     * @param  string $sql Structured Query Language
     * @throws \Exception
     * @return mixed $result
     */
    public function fast($sql)
    {
        $result = null;
        $this->sql = null;
        $this->setSql($sql);
        if ($this->getLog() == 1) {
            // push all query here
            $operation = "";
            $access = "";
            $sqlLog = "
			INSERT INTO `logerror` (
                        `companyId`,
                        `applicationId`,
                        `moduleId`,
                        `folderId`,
                        `leafId`,
						`logErrorOperation`,
						`logErrorSql`,
						`logErrorDate`,
						`staffId`,
						`logErrorAccess`,
						`logError`,
						`logErrorGuid`
			) values (
                        '" . $this->getCompanyId() . "',
                        '" . $this->getApplicationId() . "',
                        '" . $this->getModuleId() . "',
                        '" . $this->getFolderId() . "',
                        '" . $this->getLeafId() . "',
                        '" . $this->realEscapeString($operation) . "',
						'" . trim($this->realEscapeString($sql)) . "',
						'" . date("Y-m-d H:i:s") . "',
						'" . $this->getStaffId() . "',
						'" . $this->realEscapeString($access) . "',
						'" . $this->realEscapeString($sql) . "',
						'" . $this->getMysqlBatchGuid() . "'
					)";
            $test1 = mysqli_query($this->getLink(), $sqlLog);
            if (!$test1) {
                if ($this->getIsAdmin() == 1) {
                    $this->setExecute('fail');
                    $this->setResponse($sqlLog . "[" . mysqli_error($this->getLink()) . "]");
                    $this->setNotification();
                    throw new \Exception($this->getResponse());
                } else {
                    $this->setExecute('fail');
                    $this->setResponse("4 System Error.Please Contact " . $this->getAdministratorEmail());
                    throw new \Exception($this->getResponse());
                }
            }
        }

        /*
         *  check if the programmer put query on sql or not
         */
        if (strlen($this->getSql()) > 0) {
            $result = @mysqli_query($this->getLink(), $this->getSql());
            if (!$result) {
                echo "<pre>";
                $this->traceError();
                echo "</pre>";
                echo mysqli_error($this->getLink());
                if ($this->getIsAdmin() == 1) {
                    $this->setExecute('fail');
                    $this->setResponse(
                        "Sql Statement Error" . $this->getSql() . " \n\r" . mysqli_error(
                            $this->getLink()
                        ) . " <br> Error Code :x " . mysqli_errno($this->getLink())
                    );
                    $this->setNotification();
                    echo json_encode(array("success" => false, "message" => $this->getResponse()));
                    exit();
                } else {
                    $this->setExecute('fail');
                    $this->setResponse("h System Error.Please Contact " . $this->getAdministratorEmail());
                    $this->setResponse(
                        "Sql Statement Error" . $this->sql . " \n\r" . mysqli_error(
                            $this->getLink()
                        ) . " <br> Error Code :x " . mysqli_errno($this->getLink())
                    );
                    throw new \Exception($this->getResponse());
                }
            }
        } else {
            if ($this->getIsAdmin() == 1) {
                $this->setExecute('fail');
                $this->setResponse("15x Where's the query forgot Ya!");
                $this->setNotification();
                throw new \Exception($this->getResponse());
            } else {
                $this->setExecute('fail');
                $this->setResponse("22 System Error.Please Contact " . $this->getAdministratorEmail());
                throw new \Exception($this->getResponse());
            }
        }
        return $result;
    }
    /**
     * Retrieves the number of rows from a result set. This command is only valid for statements like SELECT or SHOW that return an actual result set.
     * @param null|mysqli_result $result Database Output
     * @param null $sql Structure Query language
     * @return int
     * @throws \Exception
     */
    public function numberRows($result = null, $sql = null)
    {
        if ($result) {
            if (mysqli_num_rows($result)) {
                $this->countRecord = mysqli_num_rows($result);
            } else {
                $this->countRecord = 0;
            }
            return ($this->countRecord);
        } else if ($this->getResult()) {
            if (mysqli_num_rows($this->getResult())) {
                $this->countRecord = mysqli_num_rows($this->getResult());
            } else {
                $this->countRecord = 0;
            }
            return ($this->countRecord);
        } else {
            if ($this->getIsAdmin() == 1) {
                $this->setExecute('fail');
                $this->setResponse("Maybe you should check out previous sql statement" . $this->sql);
                $this->setNotification();
                throw new \Exception($this->getResponse());
            } else {
                $this->setExecute('fail');
                $this->setResponse("23 System Error.Please Contact " . $this->getAdministratorEmail());
                throw new \Exception($this->getResponse());
            }
        }
    }

    /**
     * Vb.Net programmer number of rows
     * @param int $result
     * @return int|void Number of rows
     * @throws \Exception
     */
    public function hasRows($result)
    {
        return $this->numberRows($result);
    }


    /**
     * Retrieves the ID generated for an AUTO_INCREMENT column by the previous query (usually INSERT).
     * @param null|string $table
     * @return int
     * @throws \Exception
     */
    public function lastInsertId($table = null)
    {
        // even in transaction mode,mysql will automatically increase the auto increment.
        // if single insert last insert is enough.if mutiplied insert. mysql only take the first query only
        if ($table) {
            $sql = "
			SELECT 	auto_increment 
			FROM 	information_schema.tables 
			WHERE 	table_name = '" . strtolower($table) . "'
			AND		table_schema = '" . $this->getCoreDatabase() . "'";
            $result = mysqli_query($this->getLink(), $sql);
            if ($result) {
                $row = mysqli_fetch_array($result);
                $this->setInsertId($row['auto_increment'] - 1);
            } else {
                 if ($this->getIsAdmin() == 1) {
					$this->setExecute('fail');
					$this->setResponse("Maybe you should check out previous sql statement" . $this->sql);
					$this->setNotification();
					throw new \Exception($this->getResponse());
				} else {
					$this->setExecute('fail');
					$this->setResponse("33 System Error.Please Contact " . $this->getAdministratorEmail());
					throw new \Exception($this->getResponse());
				}
            }
			
        } else {
            //echo "sana<br> ";
            $this->setInsertId(mysqli_insert_id($this->getLink()));
        }
        //echo "aaa:[".$this->getInsertId()."]";
        return $this->getInsertId();

    }

    /**
     * Retrieves the ID generated for an AUTO_INCREMENT column by the previous query (usually INSERT).
     * @param string $tableName Table Name
     * @param string $primaryKeyName Primary Key
     * @return int $maxId Maximum Primary Key
     * @throws \Exception
     */
    public function lastInsertIdTable($tableName, $primaryKeyName)
    {
        $sql = "
        SELECT  MAX(`" . $primaryKeyName . "`) as `max` FROM `" . $tableName . "`";
        $result = mysqli_query($this->getLink(), $sql);
        if (!$result) {
            if ($this->getIsAdmin() == 1) {
                $this->setExecute('fail');
                $this->setResponse(
                    "Sql Statement Error" . $this->sql . " \n\r" . mysqli_error(
                        $this->getLink()
                    ) . " <br> Error Code :x " . mysqli_errno($this->getLink())
                );
                $this->setNotification();
                throw new \Exception($this->getResponse());
            } else {
                $this->setExecute('fail');
                $this->setResponse("h System Error.Please Contact " . $this->getAdministratorEmail());
                $this->setResponse(
                    "Sql Statement Error" . $this->sql . " \n\r" . mysqli_error(
                        $this->getLink()
                    ) . " <br> Error Code :x " . mysqli_errno($this->getLink())
                );
                throw new \Exception($this->getResponse());
            }
        } else {
            $row = \mysqli_fetch_array($result, MYSQLI_BOTH);
            $maxId = intval($row['max']);
            return $maxId;
        }

    }

    /**
     * Get the number of affected rows by the last INSERT, UPDATE, REPLACE or DELETE query associated with link_identifier.
     * By default  if not changes the affected rows are null but in this system effected also because of update time and create time.Consider not harmfull bug.
     * @return int
     */
    public function affectedRows()
    {
        return intval(@mysqli_affected_rows($this->getLink()));
    }

    /**
     * Commits the current transaction for the database connection.
     */
    public function commit()
    {
        mysqli_commit($this->getLink());
    }

    /***
     * Rollbacks the current transaction for the database.
     */
    public function rollback()
    {
        mysqli_rollback($this->getLink());
    }

    /**
     * Returns an associative array that corresponds to the fetched row and moves the internal data pointer ahead. mysql_fetch_assoc() is equivalent to calling mysql_fetch_array() with MYSQL_ASSOC for the optional second parameter. It only returns an associative array
     * @param mixed $result Resources Link
     * @return mixed
     */
    public function fetchArray($result = null)
    {
        if ($result) {
            return @mysqli_fetch_array($result, MYSQLI_BOTH);
        } else {
            return @mysqli_fetch_array($this->getResult(), MYSQLI_BOTH);
        }
    }

    /**
     * Return Array Record
     * @param mixed $result Resources Link
     * @return mixed
     */
    public function activeRecord($result = null)
    {
        $d = array();
        if ($result) {
            while (($row = mysqli_fetch_assoc($result, MYSQLI_ASSOC)) == true) {
                $d [] = $row;
            }
        } else if ($this->getResult()) {
            while (($row = mysqli_fetch_assoc($this->getResult(), MYSQLI_ASSOC)) == true) {
                $d [] = $row;
            }
        } else {

        }
        return $d;
    }

    /**
     * Returns an associative array that corresponds to the fetched row and moves the internal data pointer ahead. mysql_fetch_assoc() is equivalent to calling mysql_fetch_array() with MYSQL_ASSOC for the optional second parameter. It only returns an associative array
     * @version 0.1 added result future.No Sql Logging
     * @param mixed $result Resources Link
     * @return mixed
     */
    public function fetchAssoc($result = null)
    {
        if ($result) {
            return \mysqli_fetch_assoc($result);
        }
        if ($this->result && is_null($result)) {
            return \mysqli_fetch_assoc($this->getResult());
        }
        return "";
    }

    /**
     * Frees the memory associated with the result.
     * @param mixed $result Resources Link
     * @return void
     */
    public function freeResult($result = null)
    {
        if ($result) {
            \mysqli_free_result($result);
        }
        if ($this->getResult()) {
            \mysqli_free_result($this->getResult());
        }

    }

    /**
     * Closes a previously opened database connection
     * @param null $result
     */
    public function close($result = null)
    {
        if (isset($result)) {
            \mysqli_close($result);
            unset($result);
        } else {
            unset($this->link);
        }
    }

    /**
     * To compare value from old value and new value
     * @param string $fieldValue come from column name
     * @param string $curr_value come from mysql loop
     * @param string $prev_value come from first value before edit.
     * @return string
     */
    private function compare($fieldValue, $curr_value, $prev_value)
    {
        $textComparison = null;
        if (is_array($fieldValue)) {
            foreach ($fieldValue as $field) {
                switch ($curr_value [$field]) {
                    case is_float($curr_value [$field]) :
                        // $type='float';
                        $type = 'double';
                        $diff = $curr_value [$field] - $prev_value [$field];
                        break;
                    case is_numeric($curr_value [$field]) :
                        $type = 'integer';
                        $diff = $curr_value [$field] - $prev_value [$field];
                        break;
                    case $this->isDatetime($curr_value [$field]) :
                        $type = 'datetime';
                        $DownTime = strtotime($curr_value [$field]) - strtotime($prev_value [$field]);
                        $days = floor($DownTime / 86400); //    60*60*24 is one day
                        $SecondsRemaining = $DownTime % 86400;
                        $hours = floor($SecondsRemaining / 3600); // 60*60 is one hour
                        $SecondsRemaining = $SecondsRemaining % 3600;
                        $minutes = floor($SecondsRemaining / 60); // minutes
                        $seconds = $SecondsRemaining % 60;
                        if ($days > 0) {
                            $days = $days . ", ";
                        } else {
                            $days = null;
                        }
                        $diff = $days . $hours . ":" . $minutes . ":" . $seconds;
                        break;
                    case is_string($curr_value [$field]) :
                        $type = 'string';
                        $diff = "No Checking Please";
                        break;
                    case is_array($curr_value [$field]) :
                        $type = 'array';
                        $diff = "No Checking Please";
                        break;
                    case is_null($curr_value [$field]) :
                        $type = 'NULL';
                        $diff = "Record have been empty";
                        break;
                    case is_bool($curr_value [$field]) :
                        $type = 'boolean';
                        $diff = "Cannot Compare bolean record";
                        break;
                    case is_object($curr_value [$field]) :
                        $type = 'object';
                        $diff = "Something wrong here why object";
                        break;
                    case is_resource($curr_value [$field]) :
                        $type = 'resource';
                        $diff = "Something wrong here why object";
                        break;
                    default :
                        $type = 'unknown type';
                        $diff = "System Headache Cannot Figure out  :(";
                        break;
                }
                // json format ?
                $textComparison .= "\"" . $field . "\":[{ \"prev\":\"" . $prev_value [$field] . "\"},
														{ \"curr\":\"" . $curr_value [$field] . "\"},
														{ \"type\":\"" . $type . "\"},
														{ \"diff\":\"" . $diff . "\"}],";
            }
        }
        return $textComparison;
    }

    /**
     * Return filter data
     * @param mixed $data
     * @return mixed
     */
    public function realEscapeString($data)
    {
        return mysqli_real_escape_string($this->getLink(), $data);
    }

    /**
     * to send filter result.Quick Search mode.Table  are lower to prevent phpstorm error
     * @param string $tableArray Table/Table Space
     * @param string $filterArray Filter Array such as unique identification field
     * @return string filter
     * @throws \Exception
     */
    public function quickSearch($tableArray, $filterArray)
    {


        $d = 0;

        $strSearch = null;
        $tableSearchQuery = null;
        if (is_array($tableArray)) {

            $strSearch = "AND ( ";
            foreach ($tableArray as $tableSearch) {
                if (is_array($tableSearch)) {
                    foreach ($tableSearch as $key => $value) {
                        $tableSearchQuery = $value;
                        $tableSearch = $key;
                    }
                } else {
                    $tableSearchQuery = $tableSearch;
                }
                $i = 0;
                $sql = "DESCRIBE	`" . $tableSearch . "`";

                $result = \mysqli_query($this->getLink(), $sql);
                if ($result) {
                    if (@mysqli_num_rows($result) > 0) {
                        while (($row = mysqli_fetch_array($result, MYSQLI_BOTH)) == true) {
                            if ($row ['Field'] != 'executeBy' &&
                                $row ['Field'] != 'executeTime' &&
                                $row ['Field'] != 'isDefault' &&
                                $row ['Field'] != 'isApproved' &&
                                $row ['Field'] != 'isPost' &&
                                $row ['Field'] != 'isDelete' &&
                                $row ['Field'] != 'isNew' &&
                                $row ['Field'] != 'isDraft' &&
                                $row ['Field'] != 'isUpdate' &&
                                $row ['Field'] != 'isActive' &&
                                $row ['Field'] != 'companyId' &&
                                $row ['Field'] != 'isReview'
                            ) {
								 $pos = strpos($row ['Field'], 'Id');
								 if ($pos === false) {
									$strField = "`" . strtolower($tableSearchQuery) . "`.`" . $row ['Field'] . "`";
									$key = array_search($strField, $filterArray, true);
									if (strlen($key) == 0) {
										$strSearch .= " OR  ";
										$i++;
										$d++;

										$strSearch .= $strField . " like '%" . trim($this->getFieldQuery()) . "%' \n";
										if ($i == 1 && $d == 1) {
											$strSearch = str_replace("OR", "", $strSearch);
										}
									}
								}
								
                            }
                        }
                    }
                } else {
                    if ($this->getIsAdmin() == 1) {
                        $this->setExecute('fail');
                        $this->setResponse(
                            "Make Sure Table Generate In Controler Are Actual Table TS: " . $tableSearch . " TSQ " . $tableSearchQuery . " <br>
                       \nSql Statement Error" . $this->sql . " \n\r" . mysqli_error(
                                $this->getLink()
                            ) . " <br> Error Code :x " . mysqli_errno($this->getLink())
                        );
                        $this->setNotification();
                        //throw new \Exception($this->getResponse());
                        header('Content-Type:application/json; charset=utf-8');
                        echo json_encode(array("success" => false, "message" => $this->getResponse()));
                        exit();
                    } else {
                        $this->setExecute('fail');
                        $this->setResponse("24 System Error.Please Contact " . $this->getAdministratorEmail());
                        throw new \Exception($this->getResponse());
                    }
                }
            }
            $strSearch .= ")";
        }

        return $strSearch;
    }

    /**
     * to send filter result.
     * @return string filter
     */
    public function searching()
    {
        $qs = "";
        $str = "";
        $filter = $this->getGridQuery();
        if (is_array($filter)) {
            for ($i = 0; $i < count($filter); $i++) {
                switch ($filter [$i] ['data'] ['type']) {
                    case 'string' :
                        $qs .= " AND `" . strtolower($filter [$i] ['database']) . "`.`" . strtolower(
                                $filter [$i] ['table']
                            ) . "`.`" . $filter [$i] ['column'] . "` LIKE '%" . $this->realEscapeString(
                                $filter [$i] ['data'] ['value']
                            ) . "%'";
                        break;
                    case 'list' :
                        $split = explode(",", $filter [$i] ['data'] ['value']);
                        foreach ($split as $split_a) {
                            $str .= "'" . $split_a . "',";
                        }
                        $str = $this->removeComa($str);
                        if (count($split) > 0 && strlen($filter [$i] ['data'] ['value']) > 0) {
                            $qs .= " AND `" . strtolower($filter [$i] ['database']) . "`.`" . strtolower(
                                    $filter [$i] ['table']
                                ) . "`.`" . $filter [$i] ['column'] . "`  IN ($str)";
                        }
                        break;
                    case 'boolean' :
                        $qs .= " AND `" . strtolower($filter [$i] ['database']) . "`.`" . strtolower(
                                $filter [$i] ['table']
                            ) . "`.`" . $filter [$i] ['column'] . "` = " . $this->realEscapeString(
                                $filter [$i] ['data'] ['value']
                            );
                        break;
                    case 'numeric' :
                        switch ($filter [$i] ['data'] ['Comparison']) {
                            case 'ne' :
                                $qs .= " AND `" . strtolower($filter [$i] ['database']) . "`.`" . strtolower(
                                        $filter [$i] ['table']
                                    ) . "`.`" . $filter [$i] ['column'] . "` != " . $this->realEscapeString(
                                        $filter [$i] ['data'] ['value']
                                    );
                                break;
                            case 'eq' :
                                $qs .= " AND `" . strtolower($filter [$i] ['database']) . "`.`" . strtolower(
                                        $filter [$i] ['table']
                                    ) . "`.`" . $filter [$i] ['column'] . "` = " . $this->realEscapeString(
                                        $filter [$i] ['data'] ['value']
                                    );
                                break;
                            case 'lt' :
                                $qs .= " AND `" . strtolower($filter [$i] ['database']) . "`.`" . strtolower(
                                        $filter [$i] ['table']
                                    ) . "`.`" . $filter [$i] ['column'] . "` < " . $this->realEscapeString(
                                        $filter [$i] ['data'] ['value']
                                    );
                                break;
                            case 'gt' :
                                $qs .= " AND `" . strtolower($filter [$i] ['database']) . "`.`" . strtolower(
                                        $filter [$i] ['table']
                                    ) . "`.`" . $filter [$i] ['column'] . "` > " . $this->realEscapeString(
                                        $filter [$i] ['data'] ['value']
                                    );
                                break;
                        }
                        break;
                    case 'date' :
                        switch ($filter [$i] ['data'] ['Comparison']) {
                            case 'ne' :
                                $qs .= " AND `" . strtolower($filter [$i] ['database']) . "`.`" . strtolower(
                                        $filter [$i] ['table']
                                    ) . "`.`" . $filter [$i] ['column'] . "` != '" . date(
                                        'Y-m-d',
                                        strtotime($filter [$i] ['data'] ['value'])
                                    ) . "'";
                                break;
                            case 'eq' :
                                $qs .= " AND `" . strtolower($filter [$i] ['database']) . "`.`" . strtolower(
                                        $filter [$i] ['table']
                                    ) . "`.`" . $filter [$i] ['column'] . "` = '" . date(
                                        'Y-m-d',
                                        strtotime($filter [$i] ['data'] ['value'])
                                    ) . "'";
                                break;
                            case 'lt' :
                                $qs .= " AND `" . strtolower($filter [$i] ['database']) . "`.`" . strtolower(
                                        $filter [$i] ['table']
                                    ) . "`.`" . $filter [$i] ['column'] . "` < '" . date(
                                        'Y-m-d',
                                        strtotime($filter [$i] ['data'] ['value'])
                                    ) . "'";
                                break;
                            case 'gt' :
                                $qs .= " AND `" . strtolower($filter [$i] ['database']) . "`.`" . strtolower(
                                        $filter [$i] ['table']
                                    ) . "`.`" . $filter [$i] ['column'] . "` > '" . date(
                                        'Y-m-d',
                                        strtotime($filter [$i] ['data'] ['value'])
                                    ) . "'";
                                break;
                        }
                        break;
                }
            }
        }
        return $qs;
    }

    /**
     * To Filter Date Either it was current,previous and next day,month,week,year
     * @param string $tableName Table Name/Tablespace Name
     * @param string $columnName Column Name
     * @param string $startDate Start Date
     * @param string $endDate End Date
     * @param string $dateFilterTypeQuery Filter Type like day,month,week and year
     * @param string $dateFilterExtraTypeQuery Filter Extra Type like next,previous ,start, end
     * @return string
     */
    function dateFilter(
        $tableName,
        $columnName,
        $startDate,
        $endDate,
        $dateFilterTypeQuery,
        $dateFilterExtraTypeQuery = null
    ) {
        $str = "";
        $this->setTableName(strtolower($tableName));
        $this->setColumnName($columnName);
        $this->setStartDate($startDate);
        $this->setEndDate($endDate);

        $this->setDateFilterTypeQuery($dateFilterTypeQuery);
        $this->setDateFilterExtraTypeQuery($dateFilterExtraTypeQuery);

        $dateStartArray = explode('-', $this->getStartDate());
		
	//	if(!is_array($dateStartArray) ==0) { 
			// this come from calendar mode
	//		$dateStartArray  = explode('-',date('d-m-Y', ($this->getStartDate()/ 1000)));
	//	} 
        $dayStart = $this->setZero($dateStartArray[0]);
        $monthStart = $this->setZero($dateStartArray[1]);
        $yearStart = $dateStartArray[2];

        $this->setStartDate($yearStart . '-' . $monthStart . "-" . $dayStart);
        if ($this->getEndDate()) {
            $dateEndArray = explode('-', $this->getEndDate());
			if(!is_array($dateEndArray)==0) { 
				// this come from calendar mode
				$dateEndArray  = explode('-',date('d-m-Y', ($this->getEndDate()/ 1000)));
				
			} 
            $dayEnd = $this->setZero($dateEndArray[0]);
            $monthEnd = $this->setZero($dateEndArray[1]);
            $yearEnd = $dateEndArray[2];
            $this->setEndDate($yearEnd . '-' . $monthEnd . "-" . $dayEnd);
        }

        if ($this->getDateFilterTypeQuery() == 'day') {
            if ($this->getDateFilterExtraTypeQuery() == 'previous') {
                $dayPrevious = date("Y-m-d", mktime(0, 0, 0, $monthStart, (intval($dayStart )- 1), $yearStart));
                $this->setStartDate($dayPrevious);
                $str = (" and `" . $this->getTableName() . "`.`" . $this->getColumnName(
                    ) . "` like '%" . $this->getStartDate() . "%'");
            } else if ($this->getDateFilterExtraTypeQuery() == 'next') {
                $dayNext = date("Y-m-d", mktime(0, 0, 0, $monthStart, (intval($dayStart) + 1), $yearStart));

                $this->setStartDate($dayNext);
                $str = (" and `" . $this->getTableName() . "`.`" . $this->getColumnName(
                    ) . "` like '%" . $this->getStartDate() . "%'");
            } else {

                $str = (" and `" . $this->getTableName() . "`.`" . $this->getColumnName(
                    ) . "` like '%" . $this->getStartDate() . "%'");
            }
        } else if ($this->getDateFilterTypeQuery() == 'week') {
            if ($this->getDateFilterExtraTypeQuery() == 'previous') {


                $d = new \DateTime(date('Y-m-d', mktime(0, 0, 0, $monthStart, ($dayStart), $yearStart)));
                $weekday = $d->format('w');
                $diff = ($weekday == 0 ? 6 : $weekday - 1) + 7; // Monday=0, Sunday=6
                $d->modify("-$diff day");
                $this->setStartDate($d->format('Y-m-d'));
                $d->modify('+6 day');
                $this->setEndDate($d->format('Y-m-d'));
                $str = (" and (`" . $this->getTableName() . "`.`" . $this->getColumnName(
                    ) . "` between '" . $this->getStartDate() . " 00:00:00' and '" . $this->getEndDate(
                    ) . " 23:59:59')");
            } else if ($this->getDateFilterExtraTypeQuery() == 'next') {

                $d = new \DateTime(date('Y-m-d', mktime(0, 0, 0, $monthStart, ($dayStart), $yearStart)));
                $weekday = $d->format('w');
                $diff = ($weekday == 0 ? 6 : $weekday - 1) - 7; // Monday=0, Sunday=6
                $d->modify("-$diff day");
                $this->setStartDate($d->format('Y-m-d'));
                $d->modify('+6 day');
                $this->setEndDate($d->format('Y-m-d'));
                $str = (" and (`" . $this->getTableName() . "`.`" . $this->getColumnName(
                    ) . "` between '" . $this->getStartDate() . " 00:00:00' and '" . $this->getEndDate(
                    ) . " 23:59:59')");
            } else {


                $d = new \DateTime(date('Y-m-d', mktime(0, 0, 0, $monthStart, ($dayStart), $yearStart)));
                $weekday = $d->format('w');
                $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
                $d->modify("-$diff day");
                $this->setStartDate($d->format('Y-m-d'));
                $d->modify('+6 day');
                $this->setEndDate($d->format('Y-m-d'));

                $str = (" and (`" . $this->getTableName() . "`.`" . $this->getColumnName(
                    ) . "` between '" . $this->getStartDate() . " 00:00:00' and '" . $this->getEndDate(
                    ) . " 23:59:59')");
            }
        } elseif ($this->getDateFilterTypeQuery() == 'month') {

            if ($this->getDateFilterExtraTypeQuery() == 'previous') {
                if (($monthStart - 1) == 0) {
                    $monthStart = 12;
                    $yearStart--;
                } else {
                    $monthStart--;
                }

                $str = (" and (month(`" . $this->getTableName() . "`.`" . $this->getColumnName(
                    ) . "`)='" . $monthStart . "')  and (year(`" . $this->getTableName() . "`.`" . $this->getColumnName(
                    ) . "`)='" . $yearStart . "')");
            } else if ($this->getDateFilterExtraTypeQuery() == 'next') {

                if ((intval($monthStart) + 1) == 13) {
                    $monthStart = 1;
                    $yearStart++;
                } else {

                    $monthStart++;
                }
                $str = (" and (month(`" . $this->getTableName() . "`.`" . $this->getColumnName(
                    ) . "`)='" . $monthStart . "')  and (year(`" . $this->getTableName() . "`.`" . $this->getColumnName(
                    ) . "`)='" . $yearStart . "')");
            } else {
                $str = (" and (month(`" . $this->getTableName() . "`.`" . $this->getColumnName(
                    ) . "`)='" . $monthStart . "')  and (year(`" . $this->getTableName() . "`.`" . $this->getColumnName(
                    ) . "`)='" . $yearStart . "')");
            }
        } elseif ($this->getDateFilterTypeQuery() == 'year') {
            if ($this->getDateFilterExtraTypeQuery() == 'previous') {
                $yearStart--;
                $str = (" and (year(`" . $this->getTableName() . "`.`" . $this->getColumnName(
                    ) . "`)='" . $yearStart . "')");
            } else if ($this->getDateFilterExtraTypeQuery() == 'next') {
                $yearStart++;
                $str = (" and (year(`" . $this->getTableName() . "`.`" . $this->getColumnName(
                    ) . "`)='" . $yearStart . "')");
            } else {
                $str = (" and (year(`" . $this->getTableName() . "`.`" . $this->getColumnName(
                    ) . "`)='" . $yearStart . "')");
            }
        } elseif ($this->getDateFilterTypeQuery() == 'between') {
            $str = (" and (`" . $this->getTableName() . "`.`" . $this->getColumnName(
                ) . "` between '" . $this->getStartDate() . " 00:00:00' and '" . $this->getEndDate() . " 23:59:59')");
        }
        return $str;
    }

    /**
     * Checking date if  TRUE or false
     * @param string $dateTime
     * @return bool
     */
    public function isDatetime($dateTime)
    {
        $matches = "";
        if (@preg_match("/^({4})-({2})-({2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $dateTime, $matches)) {
            if (checkdate($matches [2], $matches [3], $matches [1])) {
                return true;
            }
        }
        return false;
    }

    /**
     * // this is for  .remove coma trail
     * @param string $str
     * @return string
     */
    public function removeComa($str)
    {
        return substr($str, 0, -1);
    }

    /**
     * Add 0 figure to the string
     * @param int $str
     * @return string
     */
    public function setZero($str)
    {
        $value = intval($str); // should be numeric only
        if (strlen($value) == 1) {
            return "0" . $value;
        } else {
            return $value;
        }
    }

    /**
     * Insert a notification and push it to the wall
     * @throws \Exception
     */
    function setNotification()
    {
        // Loads the class


        $sql = "
        INSERT INTO `notification`
            (
                
				`companyId`,					`notificationTypeId`,
				`notificationFrom`,             `notificationMessage`,
                `staffId`,                      `isDefault`,        
                `isNew`,                        `isDraft`,          
                `isUpdate`,                     `isDelete`,         
                `isActive`,                     `isApproved`,       
                `isReview`,                     `isPost`,           
                `executeBy`,                    `executeTime`
             ) VALUES (
                '" . $this->getCompanyId() . "',	2,
				3,                 				\"" . $this->realEscapeString($this->getResponse()) . "\",
                '" . $this->getStaffId() . "',   0,
                1,                              0,       
                0,                              0,      
                1,                              0,      
                0,                              0,      
                '" . $this->getStaffId() . "',  NOW()
             );
        ";


        $result = \mysqli_query($this->getLink(), $sql);
        if (!$result) {
            $this->setResponse('notification problem' . $sql);
            throw new \Exception($this->getResponse());
        }

    }

    /**
     * Return Optional Log Configuration
     * @return array|bool
     */
    public function getLeafLogData()
    {
        $data = array();
        $sql = "SELECT isAudit,isLog FROM `leaflog` WHERE `companyId`   =   '".$this->getCompanyId()."'";
        $this->setResult(\mysqli_query($this->getLink(), $sql));
        if ($this->getResult()) {
            $total = intval(\mysqli_num_rows($this->getResult()));
            if ($total > 0) {
                $row = \mysql_fetch_array($this->getResult());
                $data['isAudit'] = $row['isAudit'];
                $data['isLog'] = $row['isLog'];
            } else {
                return false;
            }
        }
        return $data;
    }

    /**
     * Set Structured Query Language
     * @param string
     */
    public function setSql($sql)
    {
        return $this->sql = $sql;
    }

    /**
     * Return Structured Query Language
     * @return string
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * Return Current Connection
     * @return mixed
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Set Current Connection
     * @param string $value Connection
     * @return \Core\Database\Mysql\Vendor
     */
    public function setConnection($value)
    {
        $this->connection = $value;
        return $this;
    }

    /**
     * Return Current Username
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Return Current Username
     * @param string $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setUsername($value)
    {
        $this->username = $value;
        return $this;
    }

    /**
     * Return Password
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set Password
     * @param string $value Password
     * @return \Core\Database\Mysql\Vendor
     */
    public function setPassword($value)
    {
        $this->password = $value;
        return $this;
    }

    /**
     * Set Mysql Port
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set Mysql Port
     * @param string $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setPort($value)
    {
        $this->port = $value;
        return $this;
    }

    /**
     * Return Mysql Socket
     * @return string
     */
    public function getSocket()
    {
        return $this->socket;
    }

    /**
     * Set Mysql Socket
     * @param string $value Mysql Socket
     * @return \Core\Database\Mysql\Vendor
     */
    public function setSocket($value)
    {
        $this->socket = $value;
        return $this;
    }

    /**
     * Set Link
     * @param string $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setLink($value)
    {
        $this->link = $value;
        return $this;
    }

    /**
     * Return Link
     * @return \mysqli|resource
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return string $requestDatabase
     */
    public function getRequestDatabase()
    {
        return $this->requestDatabase;
    }

    /**
     * @param string $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setRequestDatabase($value)
    {
        $this->requestDatabase = $value;
        return $this;
    }

    /**
     * @return string $currentDatabase
     */
    public function getCurrentDatabase()
    {
        return $this->currentDatabase;
    }

    /**
     * @param string $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setCurrentDatabase($value)
    {
        $this->currentDatabase = $value;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getCoreDatabase()
    {
        return $this->coreDatabase;
    }

    /**
     * Set Installation Database
     * @param string $value Database
     * @return \Core\Database\Mysql\Vendor
     */
    public function setCoreDatabase($value)
    {
        $this->coreDatabase = $value;
        return $this;
    }

    /**
     * Return Table Name/Table Space
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * Set Table Name/Table Space
     * @param string $value Table Name/Table Space
     * @return \Core\Database\Mysql\Vendor
     */
    public function setTableName($value)
    {
        $this->tableName = $value;
        return $this;
    }

    /**
     * Return Primary Key Name
     * @return string
     */
    public function getPrimaryKeyName()
    {
        return $this->primaryKeyName;
    }

    /**
     * Set Primary Key Name
     * @param string $value Set Primary Key Name
     * @return \Core\Database\Mysql\Vendor
     */
    public function setPrimaryKeyName($value)
    {
        $this->primaryKeyName = $value;
        return $this;
    }

    /**
     * Return Primary Key Value
     * @return string
     */
    public function getPrimaryKeyValue()
    {
        return $this->primaryKeyValue;
    }

    /**
     * Set Primary Key Value
     * @param int $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setPrimaryKeyValue($value)
    {
        $this->primaryKeyValue = $value;
        return $this;
    }

    /**
     * Return Column Name
     * @return string
     */
    public function getColumnName()
    {
        return $this->columnName;
    }

    /**
     * Set Column Name
     * @param string $value Column Name
     * @return \Core\Database\Mysql\Vendor
     */
    public function setColumnName($value)
    {
        $this->columnName = $value;
        return $this;
    }

    /**
     * Return Field Query
     * @return string
     */
    public function getFieldQuery()
    {
        return $this->fieldQuery;
    }

    /**
     * SEt Grid Query
     * @param string $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setGridQuery($value)
    {
        $this->gridQuery = $value;
        return $this;
    }

    /**
     * Return Grid Query
     * @return string
     */
    public function getGridQuery()
    {
        return $this->gridQuery;
    }

    /**
     * Set Filter Query
     * @param string $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setFieldQuery($value)
    {
        $this->fieldQuery = $value;
        return $this;
    }

    /**
     * Return Start Date
     * @return string
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set Start Date
     * @param string $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setStartDate($value)
    {
        $this->startDate = $value;
        return $this;
    }

    /**
     * Return End Date
     * @return string
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set End Date
     * @param string $value End Date
     * @return \Core\Database\Mysql\Vendor
     */
    public function setEndDate($value)
    {
        $this->endDate = $value;

        return $this;
    }

    /**
     * Return Date Filter Type
     * @return string
     */
    public function getDateFilterTypeQuery()
    {
        return $this->dateFilterTypeQuery;
    }

    /**
     * Set Date Filter Type Date Filter Type
     * @param string $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setDateFilterTypeQuery($value)
    {
        $this->dateFilterTypeQuery = $value;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getDateFilterExtraTypeQuery()
    {
        return $this->dateFilterExtraTypeQuery;
    }

    /**
     * Set Date Filter Extra Type
     * @param string $value Date Filter Extra Type
     * @return \Core\Database\Mysql\Vendor
     */
    public function setDateFilterExtraTypeQuery($value)
    {
        $this->dateFilterExtraTypeQuery = $value;
    }

    /**
     * Return Response Structure Query Language Error
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set Response Structure Query Language Error
     * @param string $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setResponse($value)
    {
        $this->response = $value;
        return $this;
    }

    /**
     * Return Execute Structure Query Language Error
     * @return string
     */
    public function getExecute()
    {
        return $this->execute;
    }

    /**
     * Set Response Structure Query Language Error
     * @param string $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setExecute($value)
    {
        $this->execute = $value;
        return $this;
    }

    /**
     * Return Application Identification
     * @return int
     */
    public function getApplicationId()
    {
        return $this->applicationId;
    }

    /**
     * Set Application Identification
     * @param int $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setApplicationId($value)
    {
        $this->applicationId = $value;
        return $this;
    }

    /**
     * Set Module Identification
     * @return int
     */
    public function getModuleId()
    {
        return $this->moduleId;
    }

    /**
     * Set Module Identification
     * @param int $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setModuleId($value)
    {
        $this->moduleId = $value;
        return $this;
    }

    /**
     * Return Folder Identification
     * @return int
     */
    public function getFolderId()
    {
        return $this->folderId;
    }

    /**
     * Set Folder Identification
     * @param int $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setFolderId($value)
    {
        $this->folderId = $value;
        return $this;
    }

    /**
     * Set Leaf Identification
     * @return int
     */
    public function getLeafId()
    {
        return $this->leafId;
    }

    /**
     * Set Leaf Identification
     * @param int $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setLeafId($value)
    {
        $this->leafId = $value;
        return $this;
    }

    /**
     * Return Staff Identification
     * @return int
     */
    public function getStaffId()
    {
        return $this->staffId;
    }

    /**
     * Set Staff Identification
     * @param int $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setStaffId($value)
    {
        $this->staffId = $value;
        return $this;
    }

    /**
     * Return Role Identification
     * @return int
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     *  Set Role Identification
     * @param int $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setRoleId($value)
    {
        $this->roleId = $value;
        return $this;
    }

    /**
     *  Set Is Admin(Role Only) Identification
     * @param int $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setIsAdminId($value)
    {
        $this->isAdminId = $value;
        return $this;
    }

    /**
     * Return Is Admin(Role Only) Identification
     * @return int
     */
    public function getIsAdminId()
    {
        return $this->isAdminId;
    }

    /**
     *
     * @return string
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     *
     * @param string $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setOperation($value)
    {
        $this->operation = $value;
        return $this;
    }

    /**
     * Return Audit
     * @return int
     */
    public function getAudit()
    {
        return $this->audit;
    }

    /**
     * Set Audit
     * @param int $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setAudit($value)
    {
        $this->audit = $value;
        return $this;
    }

    /**
     * Set log
     * @return string
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Return Log
     * @param int $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setLog($value)
    {
        $this->log = $value;
        return $this;
    }

    /**
     * Set log
     * @return int
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Return Log
     * @param int $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setIsAdmin($value)
    {
        $this->isAdmin = $value;
        return $this;
    }

    /**
     * Set log
     * @return string
     */
    public function getAdministratorEmail()
    {
        return $this->administratorEmail;
    }

    /**
     * Return Log
     * @param string $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setAdministratorEmail($value)
    {
        $this->administratorEmail = $value;
        return $this;
    }

    /**
     * Set Exception Message
     * @param string $value message
     * @return \Core\Database\Mysql\Vendor
     */
    public function setExceptionMessage($value)
    {
        $this->exceptionMessage = $value;
        return $this;
    }

    /**
     * Return Exception Message
     * @return string
     */
    public function getExceptionMessage()
    {
        return $this->exceptionMessage;
    }

    /**
     * Set Company Identification
     * @param int $value
     * @return \Core\ConfigClass
     */
    public function setCompanyId($value)
    {
        $this->companyId = $value;
        return $this;
    }

    /**
     * Return Company Identification
     * @return int
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * Generate GUID
     * @return string
     */
    function gen_uuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    /**
     * Set Mysql Batch GUID
     */
    public function setMysqlBatchGuid()
    {
        $this->mysqlBatchGuid = $this->gen_uuid();
    }

    /**
     * Return Mysql Batch GUID
     * @return string
     */
    public function getMysqlBatchGuid()
    {
        return $this->mysqlBatchGuid;
    }

    /**
     * Return Multi Id Flag
     * @param int $multiId
     */
    public function setMultiId($multiId)
    {
        $this->multiId = $multiId;
    }

    /**
     * Return Multi Id Flag
     * @return int
     */
    public function getMultiId()
    {
        return $this->multiId;
    }

    /**
     * @param string $primaryKeyAll
     */
    public function setPrimaryKeyAll($primaryKeyAll)
    {
        $this->primaryKeyAll = $primaryKeyAll;
    }

    /**
     * @return string
     */
    public function getPrimaryKeyAll()
    {
        return $this->primaryKeyAll;
    }

    /**
     * @param string $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * Return Result.Lot Of Type to cater phpdoc
     * @return string|\mysqli_result|resource
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set Last Return Id
     * @param int $insertId
     */
    public function setInsertId($insertId)
    {
        $this->insertId = $insertId;
    }

    /**
     * Return Last Insert Id
     * @return int
     */
    public function getInsertId()
    {
        return $this->insertId;
    }

    /**
     * Return Count/Total Record
     * @param number $countRecord
     */
    public function setCountRecord($countRecord)
    {
        $this->countRecord = $countRecord;
    }

    /**
     * Return Count /Total Record
     * @return number
     */
    public function getCountRecord()
    {
        return $this->countRecord;
    }

    /**
     * Set Type
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Return Type
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Block of html error message
     * @param string $message .Message of the error
     */
    function exceptionMessage($message)
    {
        $this->setMessage($message);
        if (strlen($this->getMessage()) > 0) {
            echo "<div class='alert alert-error'><a class='close' data-dismiss='alert'>x</a><img src='./images/icons/smiley-nerd.png'> " . $this->getMessage(
                ) . "</div>";
        }
    }

    /**
     * Block of html error message
     * @param string $message
     * @return string
     */
    function exceptionMessageReturn($message)
    {
        $this->setMessage($message);
        if (strlen($this->getMessage()) == 0) {
            $this->setMessage("<div class='alert alert-error'><a class='close' data-dismiss='alert'>x</a><img src='./images/icons/smiley-nerd.png'> " . $this->getMessage(
            ) . "</div>");
        }
        return $this->getMessage();
    }

    /**
     * Block of html error message
     * @param string $message .Message of the error
     */
    function exceptionMessageArray($message)
    {
        $this->setMessage($message);
        if (is_array($this->getMessage())) {
            echo "<pre class=\"prettyprint linenums\" style=\"margin-bottom: 9px;\">" . print_r(
                    $this->getMessage()
                ) . "</pre>";
        } else {
            echo "<pre class=\"prettyprint linenums lang-sql\" id=\"sql-lang\" style=\"margin-bottom: 9px;\">" . ($this->getMessage(
                )) . "</pre>";
        }
    }

    /**
     * Block of html error message
     * @param string $message .Message of the error
     */
    function exceptionMessageObject($message)
    {
        $this->setMessage($message);
        if (is_object($this->getMessage())) {
            echo "<pre class=\"prettyprint linenums\" style=\"margin-bottom: 9px;\">" . var_dump(
                    $this->getMessage()
                ) . "</pre>";
        }
    }

    /**
     * Trace Error .
     */
    function traceError()
    {
        debug_print_backtrace();
        echo "<br><br><br>";
    }


}

?>
