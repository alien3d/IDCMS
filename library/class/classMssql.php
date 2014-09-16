<?php
namespace Core\Database\Mssql;
/**
 * a specific class for connection to mysql.Either mysql or mysqli
 * @author hafizan
 * @copyright IDCMS
 * @version 1.0
 * @version 1.1 new support for Microsoft Sql Server. 02/12/2011
 * @version 1.2 new support for Oracle 02/15/2011
 * @version 1.3 change for provider to vendor instead of mysqldb
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
     * @var resource
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
     * Display Message
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
        $connection = 'localhost';

        $socket = '3306';

        $username = 'root';


        $password = '123456';


        // set basic info
        $this->setConnection($connection);
        $this->setSocket($socket);
        $this->setUsername($username);
        $this->setPassword($password);


        // set database
        if (isset($_SESSION['database'])) {
            $this->setCoreDatabase($_SESSION['database']);
        } else {
            $this->setCoreDatabase('icore');
        }

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
     **/
    public function connect()
    {

        $this->setLink(
            sqlsrv_connect(
                $this->getConnection(),
                array(
                    "UID" => $this->getUsername(),
                    "PWD" => $this->getPassword(),
                    "Database" => $this->getCoreDatabase(),
                    "CharacterSet" => "UTF-8",
                    "ReturnDatesAsStrings" => true,
                    "MultipleActiveResultSets" => false
                )
            )
        );
        if (!$this->getLink()) {
            $error = null;
            $errorArray = sqlsrv_errors();
            $this->setExecute('fail');
            $error .= " CE Sql State : " . $errorArray[0]['SQLSTATE'];
            $error .= " Code : " . $errorArray[0]['code'];
            $error .= " Message : " . $errorArray[0]['message'];
            $this->setResponse($error);
            $this->exceptionMessage($error);
        } else {
        }
    }

    /**
     * Turns on or off auto-commit mode on queries for the database connection.
     *
     * To determine the current state of autocommit use the SQL command SELECT @@autocommit.
     */
    public function start()
    {
        if (sqlsrv_begin_transaction($this->getLink())) {
            $error = null;
            $errorArray = sqlsrv_errors();
            $this->setExecute('fail');
            $error .= " CE Sql State : " . $errorArray[0]['SQLSTATE'];
            $error .= " Code : " . $errorArray[0]['code'];
            $error .= " Message : " . $errorArray[0]['message'];
            $this->setResponse($error);
            echo json_encode(
                array(
                    "success" => false,
                    "message" => 'Fail To Commit Transaction : ' . $this->getResponse()
                )
            );
            exit();
        }
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
        $this->type = null;
        $this->result = null;
        $this->countRecord = null;
        $this->setSql($sql);
        $error = 0;
        $this->setResult(sqlsrv_query($this->link, $this->getSql()));
        if (!$this->getResult()) {
            $error = null;
            $errorArray = sqlsrv_errors();
            $this->setExecute('fail');
            $error .= " <table width=\"100%\">";
            $error .= " <tr><td valign=top align=left width=100px><b>CE Sql State</b></td><td  valign=top align=center width=5px>:</td><td valign=top align=left>" . $errorArray[0]['SQLSTATE'] . "</td></tr>\n";
            $error .= " <tr><td valign=top align=left><b>Code</b></td><td valign=top align=center ><b>:</b></td><td valign=top align=left>" . $errorArray[0]['code'] . "</td></tr>\n";
            $error .= " <tr><td valign=top align=left><b>Message</b></td><td valign=top align=center><b>:</b></td><td valign=top align=left>" . $errorArray[0]['message'] . "</td></tr>\n";
            $error .= " <tr><td valign=top align=left><b>SQL Statement</b></td><td valign=top align=center><b>:</b></td><td valign=top align=left>" . $this->getColorQuery(
                    $this->sql
                ) . "</td></tr>\n";
            $error .= " </table>";

            $this->setResponse($error);
            $error = 1;
        }
        if ($error == 1) {
            $access = null;
            $operation = null;
            $sqlLog = "
			INSERT INTO [logerror] (
                        [companyId],
                        [applicationId],
                        [moduleId],
                        [folderId],
                        [leafId],
						[operation],
						[sql],
						[date],
						[roleId],
						[staffId],
						[access],
						[logError],
						[guid]
			) values (
                '" . $this->getCompanyId() . "',
                '" . $this->getApplicationId() . "',
                '" . $this->getModuleId() . "',
                '" . $this->getFolderId() . "',
                '" . $this->getLeafId() . "',
                '" . $this->realEscapeString($operation) . "',
                '" . trim($this->realEscapeString($sql)) . "',
                '" . date("Y-m-d H:i:s") . "',
                '" . $this->getRoleId() . "',
                '" . $this->getStaffId() . "',
                '" . $this->realEscapeString($access) . "',
                '" . $this->realEscapeString($sql) . "',
                '" . $this->getMysqlBatchGuid() . "'
            )";
            $resultRow = sqlsrv_query($this->getLink(), $sqlLog);
            if (!$resultRow) {
                $error = null;
                $errorArray = sqlsrv_errors();
                $this->setExecute('fail');
                $error .= " CE Sql State : " . $errorArray[0]['SQLSTATE'];
                $error .= " Code : " . $errorArray[0]['code'];
                $error .= " Message : " . $errorArray[0]['message'];
                $error .= " Sql Statement " . $sqlLog;
                $this->setResponse($error);
            }
            throw new \Exception($this->getResponse());
        }
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
        $access = null;
        $resultRow = null;
        $this->operation = null;
        $access = "";
        $this->setOperation($operation);

        if ($type == 'application') {
            $sql = "
            SELECT 	*
            FROM 	[applicationaccess]
            WHERE  	[applicationaccess].[companyId]         =   '" . intval($this->getCompanyId()) . "'
            AND     [applicationaccess].[folderaccessValue] =   '1'
            AND   	[applicationaccess].[roleId]            =   '" . intval($this->getRoleId()) . "'";
        } else if ($type == 'module') {
            $sql = "
            SELECT 	*
            FROM 	[moduleaccess]
            WHERE  	[moduleaccess].[moduleId]           =	'" . intval($this->getModuleId()) . "'
            AND   	[moduleaccess].[moduleaccessValue]  =	'1'
            AND   	[moduleaccess].[roleId]             =	'" . intval($this->getRoleId()) . "'";
        } else if ($type == 'folder') {
            $sql = "
            SELECT 	*
            FROM 	[folderaccess]
            WHERE  	[folderaccess].[folderId]           =	'" . intval($this->getFolderId()) . "'
            AND   	[folderaccess].[moduleaccessValue]  =	'1'
            AND   	[folderaccess].[roleId]             =	'" . intval($this->getRoleId()) . "'";
        } else {
            if ($type == 'leaf') {
                $sql = "
            SELECT 	*
            FROM 	[leafaccess]
            WHERE  	[leafaccess].[leafId`			=	'" . $this->getLeafId() . "'
            AND   	[leafaccess].[" . $this->getOperation() . "]	=	'1'
            AND   	[leafaccess].[staffId`		=	'" . intval($this->getStaffId()) . "'";
            } else {
                // $this->setResponse("Must check if anything wrong :[".$type."->".$this->getOperation()."]");
                // $this->setNotification();
                return 1;
            }
        }
        $this->setResult(
            sqlsrv_query(
                $this->getLink(),
                $sql,
                array(),
                array("Scrollable" => SQLSRV_CURSOR_KEYSET)
            )
        );
        if (!$this->getResult()) {
            $error = null;
            $errorArray = sqlsrv_errors();
            $this->setExecute('fail');
            $error .= " CE Sql State : " . $errorArray[0]['SQLSTATE'];
            $error .= " Code : " . $errorArray[0]['code'];
            $error .= " Message : " . $errorArray[0]['message'];
            $this->setResponse($error);
            $resultRow = 0;
        } else {
            $rowCount = sqlsrv_num_rows($this->getResult());
            if ($rowCount === false) {
                $this->setResponse($sql . sqlsrv_errors());
            } else
                if ($rowCount >= 0) {
                    $resultRow = $rowCount;
                }
        }
        if ($resultRow == 1) {
            $access = 'Granted';
        } elseif ($resultRow == 0) {
            $access = 'Denied';
        }
        //echo "access".$access;
        /*
         *  Only disable and Error Sql Statement will be log
         */
        if ($resultRow == 0 || $this->getLog() == 1) {
            // only trim out the last operation query.per limit query doesn't require because it's the same sql statement to track
            //	$operation = str_replace("leaf","",$operation);
            //	$operation = str_replace("Access","",$operation);
            //	$operation = str_replace("Value","",$operation);

            $sqlLog = "
			INSERT INTO [logerror] (
            [companyId],
                        [applicationId],
                        [moduleId],
                        [folderId],
                        [leafId],
						[operation],
						[sql],
						[date],
						[roleId],
						[staffId],
						[access],
						[logError],
						[guid]
			) values (
                '" . $this->getCompanyId() . "',
                '" . $this->getApplicationId() . "',
                '" . $this->getModuleId() . "',
                '" . $this->getFolderId() . "',
                '" . $this->getLeafId() . "',
                '" . $this->realEscapeString($operation) . "',
                '" . trim($this->realEscapeString($sql)) . "',
                '" . date("Y-m-d H:i:s") . "',
                '" . $this->getRoleId() . "',
                '" . $this->getStaffId() . "',
                '" . $access . "',
                '" . $this->realEscapeString($sql) . "',
                '" . $this->getMysqlBatchGuid() . "'
            )";
            sqlsrv_query($this->link, $sqlLog);
            $error = null;
            $errorArray = sqlsrv_errors();
            $this->setExecute('fail');
            $error .= " CE Sql State : " . $errorArray[0]['SQLSTATE'];
            $error .= " Code : " . $errorArray[0]['code'];
            $error .= " Message : " . $errorArray[0]['message'];
            $error .= " Sql Statement " . $sqlLog;
            $this->setResponse($error);
        }
        return $resultRow;
    }

    /**
     * This is for certain page which don't required to check access page
     * @param string $sql
     * @return mixed|resource
     * @throws \Exception
     */
    public function queryPage($sql)
    {
        $this->setSql($sql);
        if (strlen($this->getSql())  ==  0) {
            $this->setResponse('fail');
            $this->setResponse("Where's the query forgot Yax! ..[" . $this->getSql() . "]");
        }
        return ($this->query($this->sql));
    }

    /**
     * @depreciated
     */
    public function delete($sql)
    {
    }


    /**
     * Create / Insert Record
     * @param string $sql Structured Query Language
     * @return bool|mixed
     * @throws \Exception
     */
    public function create($sql)
    {
        $this->setSql($sql);
        if (strlen($this->getSql()) > 0) {
            if ($this->module('leafAccessCreateValue') == 1) {
                return ($this->query($this->getSql()));
            } else {
                echo json_encode(array("success" => false, "message" => $this->getResponse()));
                exit();
            }
        } else {
            $this->setExecute('fail');
            $this->setResponse("Where's the query forgot Ya!");
        }
        return false;
    }


    /**
     * Update Record
     * @param string $sql Structured Query Language
     * @return void
     * @throws \Exception
     */
    public function update($sql)
    {
        $this->setSql($sql);
        if (strlen($this->getSql()) > 0) {
            if ($this->module('leafAccessUpdateValue') == 1) {
                $this->query($this->getSql());
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
        /**
         * initialize dummy value for database column access value.
         * @var string type
         */
        $type = null;
        /*
		 *  Test string of sql statement.If forgot or not
		 */
        if (strlen($this->getSql()) > 0) {
            if ($this->module('leafAccessReadValue') == 1) {
                return ($this->query($this->getSql()));
            } else {
                $this->setResponse('fail');
                $this->setResponse(" Access Denied View ");
            }
        } else {
            $this->setResponse('fail');
            $this->setResponse("Where's the query forgot Ya!");
        }
        return false;
    }

    /**
     * Fast Query Without Log and Return like normal resources query
     * @param string $sql
     * @return bool|\mysqli_result
     * @throws \Exception
     */
    public function file($sql)
    {
        $this->setSql($sql);
        $result = null;
        /*
		 *  check if the programmer put query on sql or not
		 */
        if (strlen($this->getSql()) > 0) {
            $result = sqlsrv_query($this->getLink(), $this->getSql());
            if (!$result) {
                $error = null;
                $errorArray = sqlsrv_errors();
                $this->setExecute('fail');
                $error .= " CE Sql State : " . $errorArray[0]['SQLSTATE'];
                $error .= " Code : " . $errorArray[0]['code'];
                $error .= " Message : " . $errorArray[0]['message'];
                $this->setResponse($error);
                throw new \Exception($this->getResponse());
            }
        } else {
            $this->setResponse('fail');
            $this->setResponse("Where's the query forgot Ya!");
        }
        return $result;
    }

    /**
     * Fast Query Without Log and Return like normal resources query
     * @param string $sql Structured Query Language
     * @return bool|null|resource
     * @throws \Exception
     */
    public function fast($sql)
    {
        $this->setSql($sql);
        if (strlen($this->getSql()) > 0) {
            $this->setResult(
                sqlsrv_query(
                    $this->getLink(),
                    $this->getSql(),
                    array(),
                    array("Scrollable" => SQLSRV_CURSOR_KEYSET)
                )
            );
            if (!$this->getResult()) {
                $error = null;
                $errorArray = sqlsrv_errors();
                $this->setExecute('fail');
                $error .= " CE Sql State : " . $errorArray[0]['SQLSTATE'] . "<br>\n";
                $error .= " Code : " . $errorArray[0]['code'] . "<br>\n";
                $error .= " Message : " . $errorArray[0]['message'] . "<br>\n";
                $error .= " Sql : " . $this->sql . "<br>\n";
                $this->setResponse($error);
                throw new \Exception($this->getResponse());
            }
        } else {
            $this->setResponse('fail');
            $this->setResponse("Where's the query forgot Ya!");
        }
        return $this->getResult();
    }

    /**
     * Retrieves the number of rows from a result set
     * @param null $result
     * @param null $sql Structured Query Language
     * @return bool|int|void
     */
    public function numberRows($result = null, $sql = null)
    {
        $this->setSql($sql);
        if ($result) {
            $rowCount = sqlsrv_num_rows($result);
            if ($rowCount === false) {
                echo print_r(sqlsrv_errors());
            } else
                if ($rowCount >= 0) {
                    $this->setCountRecord($rowCount);
                }
        } else {
            $result = sqlsrv_query(
                $this->getLink(),
                $this->getSql(),
                array(),
                array("Scrollable" => SQLSRV_CURSOR_KEYSET)
            );
            if ($result) {
                $rowCount = sqlsrv_num_rows($result);
                if ($rowCount === false) {
                    echo print_r(sqlsrv_errors());
                } else
                    if ($rowCount >= 0) {
                        $this->setCountRecord($rowCount);
                    }
            } else {
            }
        }
        return $this->getCountRecord();
    }

    /**
     * Retrieves the ID generated for an AUTO_INCREMENT column by the previous query (usually INSERT).
     * @return number
     */
    public function lastInsertId()
    {
        // must include this before q->commit; after commit will no output
        $resultId = \sqlsrv_query($this->getLink(), "SELECT LAST_INSERT_ID=@@IDENTITY");
        $rowId = \sqlsrv_fetch_array($resultId, SQLSRV_FETCH_ASSOC);
        $this->setInsertId($rowId['LAST_INSERT_ID']);
        //	echo "This come from class ".$this->insert_id;
        return $this->insertId;
    }

    /**
     * Get the number of affected rows by the last INSERT, UPDATE, REPLACE or DELETE query associated with link_identifier.
     * By default  if not changes the affected rows are null but in this system effected also because of update time and create time.Consider not harmfull bug.
     * @depreciate function not exist da
     */
    public function affectedRows()
    {
        //return sqlsrv_affected_rows($this->link);
        // no information from sql server
    }

    /**
     * Commits the current transaction for the database connection.
     */
    public function commit()
    {
        \sqlsrv_commit($this->getLink());
    }

    /**
     * Rollbacks the current transaction for the database.
     */
    public function rollback()
    {
        \sqlsrv_rollback($this->getLink());
    }


    /**
     * Returns an associative array
     * @param null $result
     * @return array|null
     */
    public function fetchArray($result = null)
    {
        if ($this->getResult()) {
            return @sqlsrv_fetch_array($this->getResult(), SQLSRV_FETCH_BOTH);
        }
        if ($result) {
            return @sqlsrv_fetch_array($result, SQLSRV_FETCH_BOTH);
        }
        return false;
    }

    /**
     *
     * this only solve problem if  looping /inserting data.result error
     * @version 0.1 using  fetch_array
     * @version 0.2 using fetch_assoc  for faster json
     * @version 0.3 added result future .No Sql Logging
     */
    public function activeRecord($result = null)
    {
        $d = array();
        if ($result) {
            while (($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) == true) {
                $d[] = $row;
            }
        } else {
            while (($row = sqlsrv_fetch_array($this->getResult(), SQLSRV_FETCH_ASSOC)) == true) {
                $d[] = $row;
            }
        }
        return $d;
    }


    /**
     * Returns an associative array
     * @param null $result
     * @return array|null
     */
    public function fetchAssoc($result = null)
    {
        if ($this->result && is_null($result)) {
            return sqlsrv_fetch_array($this->getResult(), SQLSRV_FETCH_ASSOC);
        }
        if ($result) {
            return sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        }
        return false;
    }

    /**
     * Frees the memory associated with the result.
     * @version 0.1 added result future.No Sql Logging
     */
    public function freeResult($result = null)
    {
        if ($this->getResult()) {
            sqlsrv_free_stmt($this->getResult());
        }
        if ($result) {
            sqlsrv_free_stmt($result);
        }
    }

    /**
     * Closes a previously opened database connection
     */
    public function close($result = null)
    {
        if (isset($result) && is_resource($result)) {
            \sqlsrv_close($result);
            unset($result);
        } else {
            unset($this->result);
        }
    }

//    /**
//     * To compare value from old value and new value
//     * @param string $fieldValue come from column name
//     * @param string $curr_value come from mysql loop
//     * @param string $prev_value come from first value before edit.
//     * @return string
//     */
//    private function compare($fieldValue, $curr_value, $prev_value)
//    {
//        $textComparison = null;
//        if (is_array($fieldValue)) {
//            foreach ($fieldValue as $field) {
//                switch ($curr_value [$field]) {
//                    case is_float($curr_value [$field]) :
//                        // $type='float';
//                        $type = 'double';
//                        $diff = $curr_value [$field] - $prev_value [$field];
//                        break;
//                    case is_numeric($curr_value [$field]) :
//                        $type = 'integer';
//                        $diff = $curr_value [$field] - $prev_value [$field];
//                        break;
//                    case $this->isDatetime($curr_value [$field]) :
//                        $type = 'datetime';
//                        $DownTime = strtotime($curr_value [$field]) - strtotime($prev_value [$field]);
//                        $days = floor($DownTime / 86400); //    60*60*24 is one day
//                        $SecondsRemaining = $DownTime % 86400;
//                        $hours = floor($SecondsRemaining / 3600); // 60*60 is one hour
//                        $SecondsRemaining = $SecondsRemaining % 3600;
//                        $minutes = floor($SecondsRemaining / 60); // minutes
//                        $seconds = $SecondsRemaining % 60;
//                        if ($days > 0) {
//                            $days = $days . ", ";
//                        } else {
//                            $days = NULL;
//                        }
//                        $diff = $days . $hours . ":" . $minutes . ":" . $seconds;
//                        break;
//                    case is_string($curr_value [$field]) :
//                        $type = 'string';
//                        $diff = "No Checking Please";
//                        break;
//                    case is_array($curr_value [$field]) :
//                        $type = 'array';
//                        $diff = "No Checking Please";
//                        break;
//                    case is_null($curr_value [$field]) :
//                        $type = 'NULL';
//                        $diff = "Record have been empty";
//                        break;
//                    case is_bool($curr_value [$field]) :
//                        $type = 'boolean';
//                        $diff = "Cannot Compare bolean record";
//                        break;
//                    case is_object($curr_value [$field]) :
//                        $type = 'object';
//                        $diff = "Something wrong here why object";
//                        break;
//                    case is_resource($curr_value [$field]) :
//                        $type = 'resource';
//                        $diff = "Something wrong here why object";
//                        break;
//                    default :
//                        $type = 'unknown type';
//                        $diff = "System Headache Cannot Figure out  :(";
//                        break;
//                }
//                // json format ?
//                $textComparison .= "\"" . $field . "\":[{ \"prev\":\"" . $prev_value [$field] . "\"},
//														{ \"curr\":\"" . $curr_value [$field] . "\"},
//														{ \"type\":\"" . $type . "\"},
//														{ \"diff\":\"" . $diff . "\"}],";
//            }
//        }
//        return $textComparison;
//    }

    /**
     * Escape string
     * @param string $data
     * @return string
     */
    public function realEscapeString($data)
    {
        /*
		 /*
		 * @depreciate
		 	
		 $singQuotePattern = "'";
		 $singQuoteReplace = "''";
		 return (stripslashes(eregi_replace($singQuotePattern, $singQuoteReplace, $data)));
		 **/
        if (!isset($data) or empty($data))
            return ' ';
        if (is_numeric($data))
            return $data;
        $x = array(
            '/%0[0-8bcef]/', // url encoded 00-08, 11, 12, 14, 15
            '/%1[0-9a-f]/', // url encoded 16-31
            '/[\x00-\x08]/', // 00-08
            '/\x0b/', // 11
            '/\x0c/', // 12
            '/[\x0e-\x1f]/'
        );
        // 14-31

        foreach ($x as $regex)
            $data = preg_replace($regex, '', $data);
        $data = str_replace("'", "''", $data);
        return $data;
    }


    /**
     *  to send filter result.Quick Search mode
     * @param $tableArray
     * @param $filterArray
     * @return string
     */
    public function quickSearch($tableArray, $filterArray)
    {
        $result = null;
        $i = 0;
        $strSearch = null;
        $strSearch = "AND ( ";
        foreach ($tableArray as $tableSearch) {
            $sql = "
				select *
  				from information_schema.columns
 				where table_name = '" . $tableSearch . "'
 				order by ordinal_position";
            try {
                $result = $this->fast($sql);
            } catch (\Exception $e) {
                $this->exceptionMessage($e->getMessage());
            }
            if (intval($this->numberRows($result)) > 0) {
                while (($row = $this->fetchArray($result)) == true) {

                    $strField = "[" . $tableSearch . "].[" .
                        $row['COLUMN_NAME'] . "]";

                    $key = array_search($strField, $filterArray, true);
                    if ($i > 0 && strlen($key) == 0) {
                        $strSearch .= " OR  ";
                    }
                    if (strlen($key) == 0) {
                        $strSearch .= $strField . " like '%" . $this->getFieldQuery() .
                            "%'";
                    }
                    $i++;
                }
            } else {
                return false;
            }
        }
        $strSearch .= ")";
        return $strSearch;
    }

    /**
     * to send filter result.
     * @return string filter
     */
    public function searching()
    {
        $qs = null;
        $str = null;
        $filter = $this->getGridQuery();
        if (is_array($filter)) {
            for ($i = 0; $i < count($filter); $i++) {
                switch ($filter[$i]['data']['type']) {
                    case 'string':
                        $qs .= " AND [" . $filter[$i]['table'] . "].[" .
                            $filter[$i]['column'] . "] LIKE '%" .
                            $this->realEscapeString($filter[$i]['data']['value']) .
                            "%'";
                        break;
                    case 'list':
                        $split = explode(",", $filter[$i]['data']['value']);
                        $str = null;
                        foreach ($split as $split_a) {
                            $str .= "'" . $split_a . "',";
                        }
                        $str = $this->removeComa($str);
                        if (count($split) > 0 &&
                            strlen($filter[$i]['data']['value']) > 0
                        ) {
                            $qs .= " AND [" . $filter[$i]['table'] . "].[" .
                                $filter[$i]['column'] . "]  IN ($str)";
                        }
                        break;
                    case 'boolean':
                        $qs .= " AND [" . $filter[$i]['column'] . "] = " .
                            $this->realEscapeString($filter[$i]['data']['value']);
                        break;
                    case 'numeric':
                        switch ($filter[$i]['data']['comparison']) {
                            case 'ne':
                                $qs .= " AND [" . $filter[$i]['table'] . "].[" .
                                    $filter[$i]['column'] . "] != " . $this->realEscapeString(
                                        $filter[$i]['data']['value']
                                    );
                                break;
                            case 'eq':
                                $qs .= " AND [" . $filter[$i]['table'] . "].[" .
                                    $filter[$i]['column'] . "] = " . $this->realEscapeString(
                                        $filter[$i]['data']['value']
                                    );
                                break;
                            case 'lt':
                                $qs .= " AND [" . $filter[$i]['table'] . "].[" .
                                    $filter[$i]['column'] . "] < " . $this->realEscapeString(
                                        $filter[$i]['data']['value']
                                    );
                                break;
                            case 'gt':
                                $qs .= " AND [" . $filter[$i]['table'] . "].[" .
                                    $filter[$i]['column'] . "] > " . $this->realEscapeString(
                                        $filter[$i]['data']['value']
                                    );
                                break;
                        }
                        break;
                    case 'date':
                        switch ($filter[$i]['data']['comparison']) {
                            case 'ne':
                                $qs .= " AND [" . $filter[$i]['table'] . "].[" .
                                    $filter[$i]['column'] . "] != '" .
                                    date(
                                        'Y-m-d',
                                        strtotime($filter[$i]['data']['value'])
                                    ) . "'";
                                break;
                            case 'eq':
                                $qs .= " AND [" . $filter[$i]['table'] . "].[" .
                                    $filter[$i]['column'] . "] = '" .
                                    date(
                                        'Y-m-d',
                                        strtotime($filter[$i]['data']['value'])
                                    ) . "'";
                                break;
                            case 'lt':
                                $qs .= " AND [" . $filter[$i]['table'] . "].[" .
                                    $filter[$i]['column'] . "] < '" .
                                    date(
                                        'Y-m-d',
                                        strtotime($filter[$i]['data']['value'])
                                    ) . "'";
                                break;
                            case 'gt':
                                $qs .= " AND [" . $filter[$i]['table'] . "].[" .
                                    $filter[$i]['column'] . "] > '" .
                                    date(
                                        'Y-m-d',
                                        strtotime($filter[$i]['data']['value'])
                                    ) . "'";
                                break;
                        }
                        break;
                }
            }
            //$where .= $qs;
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
        $this->setTableName($tableName);
        $this->setColumnName($columnName);
        $this->setStartDate($startDate);
        $this->setEndDate($endDate);

        $this->setDateFilterTypeQuery($dateFilterTypeQuery);
        $this->setDateFilterExtraTypeQuery($dateFilterExtraTypeQuery);

        $dateStartArray = explode('-', $this->getStartDate());
        $dayStart = $this->setZero($dateStartArray[0]);
        $monthStart = $this->setZero($dateStartArray[1]);
        $yearStart = $dateStartArray[2];

        $this->setStartDate($yearStart . '-' . $monthStart . "-" . $dayStart);
        if ($this->getEndDate()) {
            $dateEndArray = explode('-', $this->getEndDate());
            $dayEnd = $this->setZero($dateEndArray[0]);
            $monthEnd = $this->setZero($dateEndArray[1]);
            $yearEnd = $dateEndArray[2];
            $this->setEndDate($yearEnd . '-' . $monthEnd . "-" . $dayEnd);
        }

        if ($this->getDateFilterTypeQuery() == 'day') {
            if ($this->getDateFilterExtraTypeQuery() == 'previous') {
                $dayPrevious = date("Y-m-d", mktime(0, 0, 0, $monthStart, (intval($dayStart) - 1), $yearStart));
                $this->setStartDate($dayPrevious);
                $str = (" and [" . $this->getTableName() . "].[" . $this->getColumnName(
                    ) . "] like '%" . $this->getStartDate() . "%'");
            } else if ($this->getDateFilterExtraTypeQuery() == 'next') {
                $dayNext = date("Y-m-d", mktime(0, 0, 0, $monthStart, (intval($dayStart) + 1), $yearStart));

                $this->setStartDate($dayNext);
                $str = (" and [" . $this->getTableName() . "].[" . $this->getColumnName(
                    ) . "] like '%" . $this->getStartDate() . "%'");
            } else {

                $str = (" and [" . $this->getTableName() . "].[" . $this->getColumnName(
                    ) . "] like '%" . $this->getStartDate() . "%'");
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
                $str = (" and ([" . $this->getTableName() . "].[" . $this->getColumnName(
                    ) . "] between '" . $this->getStartDate() . " 00:00:00' and '" . $this->getEndDate(
                    ) . " 23:59:59')");
            } else if ($this->getDateFilterExtraTypeQuery() == 'next') {

                $d = new \DateTime(date('Y-m-d', mktime(0, 0, 0, $monthStart, ($dayStart), $yearStart)));
                $weekday = $d->format('w');
                $diff = ($weekday == 0 ? 6 : $weekday - 1) - 7; // Monday=0, Sunday=6
                $d->modify("-$diff day");
                $this->setStartDate($d->format('Y-m-d'));
                $d->modify('+6 day');
                $this->setEndDate($d->format('Y-m-d'));
                $str = (" and ([" . $this->getTableName() . "].[" . $this->getColumnName(
                    ) . "] between '" . $this->getStartDate() . " 00:00:00' and '" . $this->getEndDate(
                    ) . " 23:59:59')");
            } else {


                $d = new \DateTime(date('Y-m-d', mktime(0, 0, 0, $monthStart, ($dayStart), $yearStart)));
                $weekday = $d->format('w');
                $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
                $d->modify("-$diff day");
                $this->setStartDate($d->format('Y-m-d'));
                $d->modify('+6 day');
                $this->setEndDate($d->format('Y-m-d'));

                $str = (" and ([" . $this->getTableName() . "].[" . $this->getColumnName(
                    ) . "] between '" . $this->getStartDate() . " 00:00:00' and '" . $this->getEndDate(
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

                $str = (" and (month([" . $this->getTableName() . "].[" . $this->getColumnName(
                    ) . "])='" . $monthStart . "')  and (year([" . $this->getTableName() . "].[" . $this->getColumnName(
                    ) . "])='" . $yearStart . "')");
            } else if ($this->getDateFilterExtraTypeQuery() == 'next') {

                if ((intval($monthStart) + 1) == 13) {
                    $monthStart = 1;
                    $yearStart++;
                } else {

                    $monthStart++;
                }
                $str = (" and (month([" . $this->getTableName() . "].[" . $this->getColumnName(
                    ) . "])='" . $monthStart . "')  and (year([" . $this->getTableName() . "].[" . $this->getColumnName(
                    ) . "])='" . $yearStart . "')");
            } else {
                $str = (" and (month([" . $this->getTableName() . "].[" . $this->getColumnName(
                    ) . "])='" . $monthStart . "')  and (year([" . $this->getTableName() . "].[" . $this->getColumnName(
                    ) . "])='" . $yearStart . "')");
            }
        } elseif ($this->getDateFilterTypeQuery() == 'year') {
            if ($this->getDateFilterExtraTypeQuery() == 'previous') {
                $yearStart--;
                $str = (" and (year([" . $this->getTableName() . "].[" . $this->getColumnName(
                    ) . "])='" . $yearStart . "')");
            } else if ($this->getDateFilterExtraTypeQuery() == 'next') {
                $yearStart++;
                $str = (" and (year([" . $this->getTableName() . "].[" . $this->getColumnName(
                    ) . "])='" . $yearStart . "')");
            } else {
                $str = (" and (year([" . $this->getTableName() . "].[" . $this->getColumnName(
                    ) . "])='" . $yearStart . "')");
            }
        } elseif ($this->getDateFilterTypeQuery() == 'between') {
            $str = (" and ([" . $this->getTableName() . "].[" . $this->getColumnName(
                ) . "] between '" . $this->getStartDate() . " 00:00:00' and '" . $this->getEndDate() . " 23:59:59')");
        }
        return $str;
    }

    /**
     * Checking date if  TRUE or false
     * @param string $dateTime
     * @return boolean
     */
    public function isDatetime($dateTime)
    {
        if (preg_match("/^(\\d{4})-(\\d{2})-(\\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/",
            $dateTime,
            $matches
        )
        ) {
            if (checkdate($matches[2], $matches[3], $matches[1])) {
                return true;
            }
        }
        return false;
    }

    /**
     * remove coma trail
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
// Creates a new UASparser object and set cache dir (this php scrimt must right write to cache dir)


        $this->setSql(
            "
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
                '" . $_SESSION['staffId'] . "',   0,
                1,                              0,
                0,                              0,
                1,                              0,
                0,                              0,
                '" . $_SESSION['staffId'] . "',  NOW()
             );
        "
        );


        try {
            $this->fast($this->getSql());
        } catch (\Exception $e) {
            $this->rollback();
            echo $e->getMessage();
        }

    }

    /**
     * Return Optional Log Configuration
     * @return array|bool
     */
    public function getLeafLogData()
    {
        $data = array();
        $this->setSql(" SELECT [isAudit],[isLog] FROM [leafLog] WHERE [companyId] = '".$this->getCompanyId()."'");
        try {
            $result = $this->fast($this->getSql());
            $data = $this->fetchAssoc($result);
        } catch (\Exception $e) {
            $this->rollback();
            echo $e->getMessage();
        }

        return $data;
    }

    /**
     * Give Nice Coler Imperssion
     * @param string $query
     * @return string $text
     */
    function getColorQuery($query)
    {
        $query = preg_replace("/['\"]([^'\"]*)['\"]/i", "'<FONT COLOR='#FF6600'>$1</FONT>'", $query, -1);
        $query = str_ireplace(
            array(
                '*',
                'SELECT ',
                'UPDATE ',
                'DELETE ',
                'INSERT ',
                'INTO',
                'VALUES',
                'FROM',
                'LEFT',
                'JOIN',
                'WHERE',
                'LIMIT',
                'ORDER BY',
                'AND',
                'OR ', //[dv] note the space. otherwise you match to 'COLOR' ;-)
                'DESC',
                'ASC',
                'ON ',
                'SET',
                'UNION',
                'CONCAT'
            ),
            array(
                "<FONT COLOR='#FF6600'><B>*</B></FONT>",
                "<FONT COLOR='#00AA00'><B>SELECT</B> </FONT>",
                "<FONT COLOR='#00AA00'><B>UPDATE</B> </FONT>",
                "<FONT COLOR='#00AA00'><B>DELETE</B> </FONT>",
                "<FONT COLOR='#00AA00'><B>INSERT</B> </FONT>",
                "<FONT COLOR='#00AA00'><B>INTO</B></FONT>",
                "<FONT COLOR='#00AA00'><B>VALUES</B></FONT>",
                "<FONT COLOR='#00AA00'><B>FROM</B></FONT>",
                "<FONT COLOR='#00CC00'><B>LEFT</B></FONT>",
                "<FONT COLOR='#00CC00'><B>JOIN</B></FONT>",
                "<FONT COLOR='#00AA00'><B>WHERE</B></FONT>",
                "<FONT COLOR='#AA0000'><B>LIMIT</B></FONT>",
                "<FONT COLOR='#00AA00'><B>ORDER BY</B></FONT>",
                "<FONT COLOR='#0000AA'><B>AND</B></FONT>",
                "<FONT COLOR='#0000AA'><B>OR</B> </FONT>",
                "<FONT COLOR='#0000AA'><B>DESC</B></FONT>",
                "<FONT COLOR='#0000AA'><B>ASC</B></FONT>",
                "<FONT COLOR='#00DD00'><B>ON</B> </FONT>",
                "<FONT COLOR='#00AA00'><B>SET</B></FONT>",
                "<FONT COLOR='#00AA00'><B>UNION</B></FONT>",
                "<FONT COLOR='#00AA00'><B>CONCAT</B> </FONT>"
            ),
            $query
        );
        $text = "<br><FONT COLOR='#0000FF'> " . $query . "<FONT COLOR='#FF0000'>;</FONT></FONT>";
        return wordwrap($text);
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
     * @param resource $value
     * @return \Core\Database\Mysql\Vendor
     */
    public function setLink($value)
    {
        $this->link = $value;
        return $this;
    }

    /**
     * Return Link
     * @return resource
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
     * @param number $countRecord
     */
    public function setCountRecord($countRecord)
    {
        $this->countRecord = $countRecord;
    }

    /**
     * @return number
     */
    public function getCountRecord()
    {
        return $this->countRecord;
    }

    /**
     * @param int $insertId
     */
    public function setInsertId($insertId)
    {
        $this->insertId = $insertId;
    }

    /**
     * @return int
     */
    public function getInsertId()
    {
        return $this->insertId;
    }

    /**
     * @param string $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return string|resource
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
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
        if (strlen($this->getMessage()) > 0) {
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
