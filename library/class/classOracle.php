<?php
namespace Core\Database\Oracle;
/**
 *  a specific class for connection to mysql.Either mysql or mysqli
 * @author hafizan
 * @copyright IDCMS
 * @version 1.0
 * @version 1.1 new support for Microsoft Sql Server. 02/12/2011
 * @version 1.2 new support for Oracle 02/15/2011
 * @version 1.3 change for provider to vendor instead of mysqldb
 */
class Vendor
{
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
     * Message
     * @var string
     */
    private $message;
    /**
     * Bind Param Name
     * @var array
     */
    private $bindParamName;
    /**
     * Bind Param Value
     * @var array
     */
    private $bindParamValue;

    /**
     /**
* Constructor 
     */
    public function __construct()
    {
        $this->bindParamName = array();
        $this->bindParamValue = array();
        $connection = '//localhost/oracle';


        $username = 'ICORE';


        $password = "pa\$\$word4SPH";


        // set basic info
        $this->setConnection($connection);
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
     * To connect oracle database
     * @throws \Exception
     */
    public function connect()
    {
        if (function_exists('oci_connect')) {
            echo "lol takde driver la";
        } else {
            echo "Ada driver ?";
        }

        $this->setLink(oci_connect($this->getUserName(), $this->getPassword(), $this->getConnection(), 'AL32UTF8'));
        if (!$this->getLink()) {

            $errorArray = oci_error();
            $error = "<!DOCTYPE html><link rel=\"stylesheet\" href=\"./library/twitter2/docs/assets/css/bootstrap.css\"> <table width=\"100%\"><tr>";
            $error .= "<tr>
                            <td width=\"100px\"><b>Code</b></td>\n
                            <td width=\"1px\">:</td>\n
                            <td> " . $errorArray["code"] . "</td>\n
                        </tr>\n";
            $error .= "<tr>
                            <td width='100px'><b>Message<b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . $errorArray["message"] . "</td>\n
                        </tr>";
            $error .= "<tr>
                            <td width='100px'><b>Position</b></td>\n
                            <td width='1px'>:</td>\n
                            <td>" . $errorArray["offset"] . "</td>\n
                        </tr>";
            $error .= "<tr>
                            <td width='100px'><b>Statement</b></td>\n
                            <td width='1px'>:</td>\n
                            <td>Connection Problem.Please Check Username/Password Via Oracle Setting Class</td>\n
                        </tr>";
            $error .= "</tr></table>";
            if ($this->getIsAdmin() == 1) {
                $this->exceptionMessage($error);
                $this->setResponse($error);
            } else {
                echo "patut sini";
                $this->exceptionMessage(
                    "Sorry our System had some issue.Please Contact Administrator : " . $this->getAdministratorEmail()
                );
            }
            exit();
        } else {

        }
    }

    /**
     * Turns on or off auto-commit mode on queries for the database connection.
     *
     * To determine the current state of autocommit use the SQL command SELECT @autocommit.
     */
    public function start()
    {
        // $this->oracleCommit = 1;
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
        $this->result = null;
        $this->countRecord = null;
        $this->setSql($sql);
        $error = 0;
        $resultRow = 0;
        //$this->traceError();
        $this->setResult(@oci_parse($this->getLink(), $this->getSql()));
        if (is_array($this->getBindParamName()) && count($this->getBindParamName()) > 0) {

            if ($this->getResult() != false) {
                // bind param can suppress warning but quite dangerous also because unknown  actual convert/cast  to char/to number issue.
                // oracle ask it because of SGA  but actually just recache technology like current mysql code
                if (is_array($this->getBindParamName()) && count($this->getBindParamName()) > 0) {
                    $name = $this->getBindParamName();
                    $value = $this->getBindParamValue();
                    $total = count($name);
                    for ($i = 0; $i < $total; $i++) {
                        $this->bindParameter($name[$i], $value[$i]);
                    }
                }
                unset($i);
                $test = @oci_execute($this->getResult()); //suspress warning message.Only handle by exception
                if ($test) {
                    //oracle don't have return resources.depend on oci_parse
                    $resultRow = 1;
                    $error = 0;
                } else {
                    $errorArray = oci_error($this->getResult());

                    $error = "<table width=\"100%\"><tr>";
                    $error .= "<tr>
                            <td width=\"100px\"><b>Code</b></td>\n
                            <td width=\"1px\">:</td>\n
                            <td> " . $errorArray["code"] . "</td>\n
                        </tr>\n";
                    $error .= "<tr>
                            <td width='100px'><b>Message<b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . $errorArray["message"] . "</td>\n
                        </tr>";
                    $error .= "<tr>
                            <td width='100px'><b>Position</b></td>\n
                            <td width='1px'>:</td>\n
                            <td>" . $errorArray["offset"] . "</td>\n
                        </tr>";
                    $error .= "<tr>
                            <td width='100px'><b>Statement</b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . addslashes($errorArray["sqltext"]) . "</td>\n
                        </tr>";
                    $error .= "</tr></table>";
                    $this->setResponse($error);
                    $error = 1;
                }
            } else {
                $errorArray = oci_error($this->getResult());
                $error .= "Code: " . $errorArray["code"] . "<br>";
                $error .= "Message: " . $errorArray["message"] . "<br>";
                $error .= "Position: " . $errorArray["offset"] . "<br>";
                $error .= "Statement: " . $errorArray["sqltext"] . "<br>";
                $this->setResponse($error);
                $error = 1;

            }
        } else {
            // just freakin execute  because no bind param
            $test = @oci_execute($this->getResult()); //suspress warning message.Only handle by exception
            if ($test) {
                //oracle don't have return resources.depend on oci_parse
                $resultRow = 1;
                $error = 0;
            } else {
                $errorArray = oci_error($this->getResult());
                $error = "<table width=\"100%\"><tr>";
                $error .= "<tr>
                            <td width=\"100px\"><b>Code</b></td>\n
                            <td width=\"1px\">:</td>\n
                            <td> " . $errorArray["code"] . "</td>\n
                        </tr>\n";
                $error .= "<tr>
                            <td width='100px'><b>Message<b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . $errorArray["message"] . "</td>\n
                        </tr>";
                $error .= "<tr>
                            <td width='100px'><b>Position</b></td>\n
                            <td width='1px'>:</td>\n
                            <td>" . $errorArray["offset"] . "</td>\n
                        </tr>";
                $error .= "<tr>
                            <td width='100px'><b>Statement</b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . addslashes($errorArray["sqltext"]) . "</td>\n
                        </tr>";
                $error .= "</tr></table>";
                $this->setResponse($error);
                $error = 1;
            }
        }
        if ($error == 1) {

            $operation = null;
            $access = null;

            $sqlLog = "
             INSERT INTO \"LOGERROR\" (
                  \"COMPANYID\",
                     \"APPLICATIONID\",
                     \"MODULEID\",
                     \"FOLDERID\",
                     \"LEAFID\",
                     \"LOGERROROPERATION\",
                     \"LOGERRORSQL\",
                     \"LOGERRORDATE\",
                     \"ROLEID\",
                     \"STAFFID\",
                     \"LOGERRORACCESS\",
                     \"LOGERROR\",
                     \"LOGERRORGUID\"
         ) VALUES (
             \"" . intval($this->getCompanyId()) . "\",
             \"" . intval($this->getApplicationId()) . "\",
             \"" . intval($this->getModuleId()) . "\",
             \"" . intval($this->getFolderId()) . "\",
             \"" . intval($this->getLeafId()) . "\",
             \" " . strval($this->realEscapeString($operation)) . "\",
             \" " . strval(trim($this->realEscapeString($this->getSql()))) . "\",
             \"to_date('" . date("Y-m-d H:i:s") . "','YYYY-MM-DD HH24:MI:SS')\",
             \" " . intval($this->getRoleId()) . "\",
             \" " . intval($this->getStaffId()) . "\",
             \" " . strval($this->realEscapeString($access)) . "\",
             \" " . strval($this->realEscapeString($this->getSql())) . "\",
             \" " . strval($this->getMysqlBatchGuid()) . "\"
         )";
            // since  no bind param
            //This function does not validate sql_text. The only way to find out if sql_text is a valid SQL or PL/SQL statement is to execute it.
            $resultSqlLog = @oci_parse($this->getLink(), $sqlLog);
            $test = @oci_execute($resultSqlLog); //suspress warning message.Only handle by exception
            if ($test) {
                //oracle don't have return resources.depend on oci_parse
            } else {
                if ($resultSqlLog && strlen($resultSqlLog) > 0) {
                    $errorArray = oci_error($resultSqlLog);
                } else {
                    $errorArray = oci_error($this->getLink());
                }
                if (empty($errorArray['sqltext']) || $errorArray['sqlTtext'] == '' || $errorArray['sqltext'] == null) {
                    $errorArray['sqltext'] = $sqlLog;
                } else {
                    $errorArray['sqltext'] = "ada ke" . $errorArray['sqltext'] . "'";
                }
                $error = "<table width=\"100%\"><tr>";
                $error .= "<tr>
                            <td width=\"100px\"><b>Code</b></td>\n
                            <td width=\"1px\">:</td>\n
                            <td> " . $errorArray["code"] . "</td>\n
                        </tr>\n";
                $error .= "<tr>
                            <td width='100px'><b>Message<b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . $errorArray["message"] . "</td>\n
                        </tr>";
                $error .= "<tr>
                            <td width='100px'><b>Position</b></td>\n
                            <td width='1px'>:</td>\n
                            <td>" . $errorArray["offset"] . "</td>\n
                        </tr>";
                $error .= "<tr>
                            <td width='100px'><b>Statement</b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . addslashes($errorArray["sqltext"]) . "</td>\n
                        </tr>";
                $error .= "</tr></table>";
                $this->setResponse($error);
                echo json_encode(
                    array(
                        "success" => false,
                        "message" => 'Fail To PUT EXECUTE LOG: ' . $this->getResponse(),
                        "previousSqlError" => $this->getSql()
                    )
                );
                exit();
            }

        }

        //	$this->rollback();
        return $resultRow;
    }

    /**
     * for checking sql statement either it works or not.If no log table error
     * @param string $operation
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
        $this->setOperation($operation);
        if (isset($type)) {
            $this->setType($type);
        } else {
            $this->setType('');
        }
        if ($this->getType() == 'application') {
            $this->setSql(
                "
                            SELECT 	*
                            FROM 	APPLICATIONACCESS
                            WHERE  	APPLICATIONACCESS.FOLDERID          =   '" . intval($this->getApplicationId()) . "'
            AND   	APPLICATIONACCESS.FOLDERACCESSVALUE =   '1'
            AND   	APPLICATIONACCESS.ROLEID            =   '" . intval($this->getRoleId()) . "'"
            );
        } else if ($this->getType() == 'module') {
            $this->setSql(
                "
                            SELECT 	*
                            FROM 	MODULEACCESS
                            WHERE  	MODULEACCESS.MODULEID           =	'" . intval($this->getModuleId()) . "'
            AND   	MODULEACCESS.MODULEACCESSVALUE  =	'1'
            AND   	MODULEACCESS.ROLEID             =	'" . intval($this->getRoleId()) . "'"
            );
        } else if ($this->getType() == 'folder') {
            $this->setSql(
                "
                            SELECT 	*
                            FROM 	FOLDERACCESS
                            WHERE  	FOLDERACCESS.FOLDERID           =	'" . intval($this->getFolderId()) . "'
            AND   	FOLDERACCESS.MODULEACCESSVALUE  =	'1'
            AND   	FOLDERACCESS.ROLEID             =	'" . intval($this->getRoleId()) . "'"
            );
        } else {
            if ($this->getType() == 'leaf') {
                $this->setSql(
                    "
                                SELECT 	*
                                FROM 	LEAFACCESS
                                WHERE  	LEAFACCESS.LEAFID			=	'" . intval($this->getLeafId()) . "'
            AND   	LEAFACCESS." . $this->getOperation() . "	=	'1'
            AND   	LEAFACCESS.STAFFID		=	'" . intval($this->getStaffId()) . "'"
                );
            } else {
                // $this->setResponse("Must check if anything wrong :[".$type."->".$this->getOperation()."]");
                // $this->setNotification();
                return 1;
            }
        }
        $array = array();
        $oracleNumRows = oci_parse($this->getLink(), $this->getSql());
        if ($oracleNumRows != false) {
            $test = @oci_execute($oracleNumRows); //suspress warning message.Only handle by exception
            if ($test) {
                //oracle don't have return resources.depend on oci_parse
                oci_fetch_all($oracleNumRows, $array);
                $resultRow = oci_num_rows($oracleNumRows);
                //echo "Total record [".$resultRow."]";
            } else {

                $errorArray = oci_error($oracleNumRows);
                $error = "<table width=\"100%\"><tr>";
                $error .= "<tr>
                            <td width=\"100px\"><b>Code</b></td>\n
                            <td width=\"1px\">:</td>\n
                            <td> " . $errorArray["code"] . "</td>\n
                        </tr>\n";
                $error .= "<tr>
                            <td width='100px'><b>Message<b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . $errorArray["message"] . "</td>\n
                        </tr>";
                $error .= "<tr>
                            <td width='100px'><b>Position</b></td>\n
                            <td width='1px'>:</td>\n
                            <td>" . $errorArray["offset"] . "</td>\n
                        </tr>";
                $error .= "<tr>
                            <td width='100px'><b>Statement</b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . addslashes($errorArray["sqltext"]) . "</td>\n
                        </tr>";
                $error .= "</tr></table>";
                $this->setResponse($error);
                echo json_encode(
                    array("success" => false, "message" => 'Fail To Execute Query X : ' . $this->getResponse())
                );
                exit();
            }
        } else {

            $errorArray = oci_error($oracleNumRows);
            $error = "<table width=\"100%\"><tr>";
            $error .= "<tr>
                            <td width=\"100px\"><b>Code</b></td>\n
                            <td width=\"1px\">:</td>\n
                            <td> " . $errorArray["code"] . "</td>\n
                        </tr>\n";
            $error .= "<tr>
                            <td width='100px'><b>Message<b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . $errorArray["message"] . "</td>\n
                        </tr>";
            $error .= "<tr>
                            <td width='100px'><b>Position</b></td>\n
                            <td width='1px'>:</td>\n
                            <td>" . $errorArray["offset"] . "</td>\n
                        </tr>";
            $error .= "<tr>
                            <td width='100px'><b>Statement</b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . addslashes($errorArray["sqltext"]) . "</td>\n
                        </tr>";
            $error .= "</tr></table>";
            $this->setResponse($error);
            echo json_encode(array("success" => false, "message" => 'Fail To Parse Query : ' . $this->getResponse()));
            exit();
        }
        if ($resultRow == 1) {
            $access = 'Granted';
        } elseif ($resultRow == 0) {
            $access = 'Denied';
        }
        //	echo $access;
        /*
         *  Only disable and Error Sql Statement will be log
         */
        if ($resultRow == 0 || $this->log == 1) {
            // only trim out the last operation query.per limit query doesn't require because it's the same sql statement to track
            //	$operation = str_replace("leaf","",$operation);
            //	$operation = str_replace("Access","",$operation);
            //	$operation = str_replace("Value","",$operation);
            $operation = null;
            $sqlLog = "
				INSERT INTO \"LOGERROR\" (
                        \"COMPANYID\",
                        \"APPLICATIONID\",
                        \"MODULEID\",
                        \"FOLDERID\",
                        \"LEAFID\",
						\"OPERATION\",
						\"SQL\",
						\"DATE_\",
						\"ROLEID\",
						\"STAFFID\",
						\"ACCESS_\",
						\"LOGERROR\",
						\"GUID\"
			) VALUES (
                 \"" . intval($this->getCompanyId()) . "\",
                \"" . intval($this->getApplicationId()) . "\",
                \"" . intval($this->getModuleId()) . "\",
                \"" . intval($this->getFolderId()) . "\",
                \"" . intval($this->getLeafId()) . "\",
                \"" . strval($this->realEscapeString($operation)) . "\",
                \"" . strval(trim($this->realEscapeString($this->getSql()))) . "\",
                \"to_date('" . date("Y-m-d H:i:s") . "','YYYY-MM-DD HH24:MI:SS')\",
                \"" . intval($this->getRoleId()) . "\",
                \"" . intval($this->getStaffId()) . "\",
                \"" . strval($this->realEscapeString($access)) . "\",
                \"" . strval($this->realEscapeString($this->getSql())) . "\",
                \"" . strval($this->getMysqlBatchGuid()) . "\"
            )";
            echo $sqlLog . "<br><br>";
            $resultSqlLog = oci_parse($this->getLink(), $sqlLog);
            if ($resultSqlLog != false) {
                $test = @oci_execute($resultSqlLog); //suspress warning message.Only handle by exception
                if ($test) {
                    //oracle don't have return resources.depend on oci_parse
                } else {

                    $errorArray = oci_error($resultSqlLog);
                    $error = "<table width=\"100%\"><tr>";
                    $error .= "<tr>
                            <td width=\"100px\"><b>Code</b></td>\n
                            <td width=\"1px\">:</td>\n
                            <td> " . $errorArray["code"] . "</td>\n
                        </tr>\n";
                    $error .= "<tr>
                            <td width='100px'><b>Message<b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . $errorArray["message"] . "</td>\n
                        </tr>";
                    $error .= "<tr>
                            <td width='100px'><b>Position</b></td>\n
                            <td width='1px'>:</td>\n
                            <td>" . $errorArray["offset"] . "</td>\n
                        </tr>";
                    $error .= "<tr>
                            <td width='100px'><b>Statement</b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . addslashes($errorArray["sqltext"]) . "</td>\n
                        </tr>";
                    $error .= "</tr></table>";
                    $this->setResponse($error);
                    echo json_encode(
                        array("success" => false, "message" => 'Fail To PUT EXECUTE LOG: ' . $this->getResponse())
                    );
                    exit();
                }
            } else {

                $errorArray = oci_error($resultSqlLog);
                $error = "<table width=\"100%\"><tr>";
                $error .= "<tr>
                            <td width=\"100px\"><b>Code</b></td>\n
                            <td width=\"1px\">:</td>\n
                            <td> " . $errorArray["code"] . "</td>\n
                        </tr>\n";
                $error .= "<tr>
                            <td width='100px'><b>Message<b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . $errorArray["message"] . "</td>\n
                        </tr>";
                $error .= "<tr>
                            <td width='100px'><b>Position</b></td>\n
                            <td width='1px'>:</td>\n
                            <td>" . $errorArray["offset"] . "</td>\n
                        </tr>";
                $error .= "<tr>
                            <td width='100px'><b>Statement</b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . addslashes($errorArray["sqltext"]) . "</td>\n
                        </tr>";
                $error .= "</tr></table>";
                $this->setResponse($error);
                echo json_encode(
                    array("success" => false, "message" => 'Fail To Parse Query : ' . $this->getResponse())
                );
                exit();
            }
        }
        return ($resultRow);
    }

    /**
     * this is for certain page which don't required to check access page
     * @param string $sql
     * @return resource
     */
    public function queryPage($sql)
    {
        $this->setSql($sql);
        if (strlen($this->getSql()) > 0) {
            return ($this->query($this->getSql()));
        } else {
            $this->setExecute('fail');
            $this->setResponse("Where's the query forgot Yax! ..[" . $this->getSql() . "]");
        }
        return false;
    }

    /**
     * @depreciated
     */
    public function delete()
    {
    }

    /**
     * for insert record
     * @param string $sql Structured Query Language
     * @param null $bindParamName
     * @param null $bindParamValue
     * @return string statement
     */
    public function create($sql, $bindParamName = null, $bindParamValue = null)
    {
        // optional bind parameter
        if (is_array($bindParamName)) {
            $this->setBindParamName($bindParamName);
            $this->setBindParamValue($bindParamValue);
        }
        $this->setSql($sql);
        if (strlen($this->getSql()) > 0) {
            if ($this->module('leafAccessCreateValue') == 1) {
                return ($this->query($this->getSql()));
            } else {
                $this->setResponse("no access insert ");
            }
        } else {
            $this->setExecute('fail');
            $this->setResponse("Where's the query forgot Ya!");
        }
        return false;
    }

    /**
     * update record
     * @param string $sql Structured Query Language
     * @param null $bindParamName
     * @param null $bindParamValue
     * @throws \Exception
     */
    public function update($sql, $bindParamName = null, $bindParamValue = null)
    {
        // optional bind parameter
        if (is_array($bindParamName)) {
            $this->setBindParamName($bindParamName);
            $this->setBindParamValue($bindParamValue);
        }
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
     * @param null $bindParamName
     * @param null $bindParamValue
     * @return mixed
     */
    public function read($sql, $bindParamName = null, $bindParamValue = null)
    {

        /**
         *  redefine variable
         * @var string sql
         */
        $this->setSql($sql);
        /**
         *  initialize dummy value for database column access value.
         * @var string type
         */
        // optional bind parameter
        if (is_array($bindParamName)) {
            $this->setBindParamName($bindParamName);
            $this->setBindParamValue($bindParamValue);
        }
        /*
         *  Test string of sql statement.If forgot or not
         */
        if (strlen($sql) > 0) {
            if ($this->module('leafAccessReadValue') == 1) {
                return ($this->query($this->sql));
            } else {
                $this->setExecute('fail');
                $this->setResponse(" Access Denied View ");
            }
        } else {
            $this->setExecute('fail');
            $this->setResponse("Where's the query forgot Ya!");
        }
        return false;
    }

    /**
     * Same As Fast Query
     * @param string $sql
     * @return resource|string
     */
    public function file($sql)
    {
        $this->sql = null;
        $this->sql = $sql;
        /*
         *  check if the programmer put query on sql or not
         */
        if (strlen($sql) > 0) {
            $this->setResult(oci_parse($this->getLink(), $this->getSql()));
            if ($this->getResult() != false) {
                $test = @oci_execute($this->getResult()); //suspress warning message.Only handle by exception
                if ($test) {
                    //oracle don't have return resources.depend on oci_parse
                } else {

                    $errorArray = oci_error($this->getResult());
                    $error = "<table width=\"100%\"><tr>";
                    $error .= "<tr>
                            <td width=\"100px\"><b>Code</b></td>\n
                            <td width=\"1px\">:</td>\n
                            <td> " . $errorArray["code"] . "</td>\n
                        </tr>\n";
                    $error .= "<tr>
                            <td width='100px'><b>Message<b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . $errorArray["message"] . "</td>\n
                        </tr>";
                    $error .= "<tr>
                            <td width='100px'><b>Position</b></td>\n
                            <td width='1px'>:</td>\n
                            <td>" . $errorArray["offset"] . "</td>\n
                        </tr>";
                    $error .= "<tr>
                            <td width='100px'><b>Statement</b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . addslashes($errorArray["sqltext"]) . "</td>\n
                        </tr>";
                    $error .= "</tr></table>";
                    $this->setResponse($error);
                    echo json_encode(
                        array("success" => false, "message" => 'Fail To Execute Query X : ' . $this->getResponse())
                    );
                    exit();
                }
            } else {
                $errorArray = oci_error($this->getResult());
                $error = "<table width=\"100%\"><tr>";
                $error .= "<tr>
                            <td width=\"100px\"><b>Code</b></td>\n
                            <td width=\"1px\">:</td>\n
                            <td> " . $errorArray["code"] . "</td>\n
                        </tr>\n";
                $error .= "<tr>
                            <td width='100px'><b>Message<b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . $errorArray["message"] . "</td>\n
                        </tr>";
                $error .= "<tr>
                            <td width='100px'><b>Position</b></td>\n
                            <td width='1px'>:</td>\n
                            <td>" . $errorArray["offset"] . "</td>\n
                        </tr>";
                $error .= "<tr>
                            <td width='100px'><b>Statement</b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . addslashes($errorArray["sqltext"]) . "</td>\n
                        </tr>";
                $error .= "</tr></table>";
                $this->setResponse($error);
                echo json_encode(
                    array("success" => false, "message" => 'Fail To Parse Query : ' . $this->getResponse())
                );
                exit();
            }
            return $this->getResult();

        } else {
            $this->setExecute('fail');
            $this->setResponse("Where's the query forgot Ya!");
        }
        return false;
    }

    /**
     * Fast Query Without Log and Return like normal resources query
     * @param string $sql
     * @return resource
     */
    public function fast($sql)
    {
        $this->setSql($sql);
        /*
         *  check if the programmer put query on sql or not
         */
        if (strlen($this->getSql()) > 0) {
            $x = (oci_parse($this->getLink(), $this->getSql()));
            if ($x != false) {
                $test = @oci_execute($x, OCI_NO_AUTO_COMMIT);
                if ($test) {
                    //oracle don't have return resources.depend on oci_parse
                } else {

                    $errorArray = oci_error($x);
                    $error = null;
                    $error .= "Code: " . $errorArray["code"] . "<br>";
                    $error .= "Message: " . $errorArray["message"] . "<br>";
                    $error .= "Position: " . $errorArray["offset"] . "<br>";
                    $error .= "Statement: " . $errorArray["sqltext"] . "<br>";
                    $this->setResponse($error);
                    echo json_encode(
                        array("success" => false, "message" => 'Fail To Execute Query X : ' . $this->getResponse())
                    );
                    exit();
                }
            } else {

                $errorArray = oci_error($x);
                $error = null;
                $error .= "Code: " . $errorArray["code"] . "<br>";
                $error .= "Message: " . $errorArray["message"] . "<br>";
                $error .= "Position: " . $errorArray["offset"] . "<br>";
                $error .= "Statement: " . $errorArray["sqltext"] . "<br>";
                $this->setResponse($error);
                echo json_encode(
                    array("success" => false, "message" => 'Fail To Parse Query : ' . $this->getResponse())
                );
                exit();
            }


            return $x;
        } else {
            $this->setExecute('fail');
            $this->setResponse("Where's the query forgot Ya!");
        }
        return false;
    }


    /**
     * Retrieves the number of rows from a result set.
     * @param null $result
     * @param null $sql
     * @return int
     */
    public function numberRows($result = null, $sql = null)
    {
        if ($result) {
            $this->setResult($result);
        }
        if ($sql) {
            $this->setSql($sql);
        } else {
            // override internal.
        }
        $x = oci_parse($this->getLink(), $this->getSql());
        if ($x != false) {
            $test = @oci_execute($x); //suspress warning message.Only handle by exception
            if ($test) {
                //oracle don't have return resources.depend on oci_parse
                $results = array();
                $y = oci_fetch_all($x, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
                $this->setCountRecord($y);
                //
                //	echo "Total record [".$this->countRecord."]";
            } else {

                $errorArray = oci_error($x);
                $error = "<table width=\"100%\"><tr>";
                $error .= "<tr>
                            <td width=\"100px\"><b>Code</b></td>\n
                            <td width=\"1px\">:</td>\n
                            <td> " . $errorArray["code"] . "</td>\n
                        </tr>\n";
                $error .= "<tr>
                            <td width='100px'><b>Message<b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . $errorArray["message"] . "</td>\n
                        </tr>";
                $error .= "<tr>
                            <td width='100px'><b>Position</b></td>\n
                            <td width='1px'>:</td>\n
                            <td>" . $errorArray["offset"] . "</td>\n
                        </tr>";
                $error .= "<tr>
                            <td width='100px'><b>Statement</b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . addslashes($errorArray["sqltext"]) . "</td>\n
                        </tr>";
                $error .= "</tr></table>";
                $this->setResponse($error);
                echo json_encode(
                    array("success" => false, "message" => 'Fail To Execute Query X : ' . $this->getResponse())
                );
                exit();
            }
        } else {

            $errorArray = oci_error($x);
            $error = "<table width=\"100%\"><tr>";
            $error .= "<tr>
                            <td width=\"100px\"><b>Code</b></td>\n
                            <td width=\"1px\">:</td>\n
                            <td> " . $errorArray["code"] . "</td>\n
                        </tr>\n";
            $error .= "<tr>
                            <td width='100px'><b>Message<b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . $errorArray["message"] . "</td>\n
                        </tr>";
            $error .= "<tr>
                            <td width='100px'><b>Position</b></td>\n
                            <td width='1px'>:</td>\n
                            <td>" . $errorArray["offset"] . "</td>\n
                        </tr>";
            $error .= "<tr>
                            <td width='100px'><b>Statement</b></td>\n
                            <td width='1px'>:</td>\n
                            <td> " . addslashes($errorArray["sqltext"]) . "</td>\n
                        </tr>";
            $error .= "</tr></table>";
            $this->setResponse($error);
            echo json_encode(array("success" => false, "message" => 'Fail To Parse Query : ' . $this->getResponse()));
            exit();
        }
        return ($this->countRecord);
    }

    /**
     * Retrieves the ID generated for an AUTO_INCREMENT column by the previous query (usually INSERT).
     * Sequence are trigger made
     * @param int $sequence
     */
    public function lastInsertId($sequence)
    {
        $this->setResult(oci_parse($this->getLink(), "SELECT '" . $sequence . ".CURRVAL FROM DUAL"));
        @oci_execute($this->getResult());
        /**
         * optional constant OCI_BOTH,OCI_ASSOC,OCI_NUM,OCI_RETURN_NULLS,OCI_RETURN_LOBS
         */
        $row = oci_fetch_assoc($this->getResult(), 'OCI_BOTH');
        if (is_array($row)) {
            $this->setInsertId($row['CURRVAL']);
        }
    }

    /**
     * Get the number of affected rows by the last INSERT, UPDATE, REPLACE or DELETE query associated with link_identifier.
     * By default  if not changes the affected rows are null but in this system effected also because of update time and create time.Consider not harmfull bug.
     */
    public function affectedRows()
    {
        //return mysqli_affected_rows($this->link);

        // no information from sql server
    }

    /**
     * Commits the current transaction for the database connection.
     */
    public function commit()
    {
        oci_commit($this->getLink());
    }

    /**
     * Rollbacks the current transaction for the database.
     */
    private function rollback()
    {

        oci_rollback($this->getLink());
        $this->setExecute('fail');
    }

    /**
     * Returns an associative array that corresponds to the fetched row and moves the internal data pointer ahead
     * @param null $result
     * @return array
     */
    public function fetchArray($result = null)
    {
        if ($this->getResult()) {
            return oci_fetch_array($this->getResult(), OCI_BOTH);
        }
        if ($result) {
            return oci_fetch_array($result, OCI_BOTH);
        }
        return false;
    }

    /**
     *
     *  this only solve problem if  looping /inserting data.result error
     * @version 0.1 using  fetch_array
     * @version 0.2 using fetch_assoc  for faster json
     * @version 0.3 added result future .No Sql Logging
     */
    public function activeRecord($result = null)
    {
        $d = array();
        if ($result) {
            while (($row = oci_fetch_array($result, OCI_ASSOC)) == true) {
                $d[] = $row;
            }
        } else {
            while (($row = oci_fetch_array($this->getResult(), OCI_ASSOC)) == true) {
                $d[] = $row;
            }
        }
        return $d;
    }

    /**
     * Returns an associative array that corresponds to the fetched row and moves the internal data pointer ahead.Return lobs also
     * @param null $result
     * @return array
     */
    public function fetchAssoc($result = null)
    {
        if ($this->getResult() && is_null($result)) {
            return oci_fetch_assoc($this->getResult());
        }
        if ($result) {
            return oci_fetch_assoc($result);

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
            oci_free_statement($this->getResult());
        }
        if ($result) {
            oci_free_statement($result);
            unset($result);
        }
    }

    /**
     * Bind Parameter
     * @param string $name
     * @param string $value
     * @param string|resource $result
     */
    public function bindParameter($name, $value, $result = null)
    {
        if (empty($result)) {
            oci_bind_by_name($this->getResult(), $name, $value);
        } else {
            oci_bind_by_name($result, $name, $value);
        }
    }

    /**
     * Closes a previously opened database connection
     */
    public function close()
    {
        \oci_close($this->getLink());

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
    private function realEscapeString($data)
    {

        /*
        if (!isset($data) or empty($data)) {
            return ' ';
        }
        if (is_numeric($data)) {
            return intval($data);
        }
        $x = array('/%0[0-8bcef]/', // url encoded 00-08, 11, 12, 14, 15
            '/%1[0-9a-f]/', // url encoded 16-31
            '/[\x00-\x08]/', // 00-08
            '/\x0b/', // 11
            '/\x0c/', // 12
            '/[\x0e-\x1f]/');
        // 14-31

        foreach ($x as $regex) {
            $data = preg_replace($regex, '', $data);
            $data = str_replace("'", "''", $data);
        }
        */
        return addslashes($data);
    }

    /**
     * to send filter result.Quick Search mode
     * @param array $tableArray
     * @param array $filterArray
     * @return string
     */
    public function quickSearch($tableArray, $filterArray)
    {
        $i = 0;
        $strSearch = "AND ( ";
        foreach ($tableArray as $tableSearch) {
            $sql = "
            SELECT      *
            FROM        user_tab_columns
            WHERE       table_name = '" . $tableSearch . "'
            ORDER BY    column_id";
            $this->setResult($this->fast($sql));
            if ($this->numberRows($this->getResult()) > 0) {
                while (($row = $this->fetchAssoc($this->getResult())) == true) {
                    $strField = " Initcap(" . strtoupper($tableSearch) . "." . strtoupper($row['COLUMN_NAME']) . ") ";
                    $key = array_search($strField, $filterArray, true);
                    if ($i > 0 && strlen($key) == 0) {
                        $strSearch .= " OR  ";
                    }
                    if (strlen($key) == 0) {
                        $strSearch .= $strField . " like Initcap('%" . $this->getFieldQuery() . "%')";
                    }
                    $i++;
                }
            } else {
                echo "something wrong here";
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
                        $qs .= " AND " . strtoupper($filter[$i]['table']) . "." .
                            strtoupper($filter[$i]['column']) . " LIKE '%" .
                            $this->realEscapeString($filter[$i]['data']['value']) .
                            "%'";
                        break;
                    case 'list':
                        $split = explode(",", $filter[$i]['data']['value']);
                        foreach ($split as $split_a) {
                            $str .= "'" . $split_a . "',";
                        }
                        $str = $this->removeComa($str);
                        if (count($split) > 0 &&
                            strlen($filter[$i]['data']['value']) > 0
                        ) {
                            $qs .= " AND " . strtoupper($filter[$i]['table']) . "." .
                                strtoupper($filter[$i]['column']) . "  IN ($str)";
                        }
                        break;
                    case 'boolean':
                        $qs .= " AND " . strtoupper($filter[$i]['column']) . " = " .
                            $this->realEscapeString($filter[$i]['data']['value']);
                        break;
                    case 'numeric':
                        switch ($filter[$i]['data']['comparison']) {
                            case 'ne':
                                $qs .= " AND " . strtoupper($filter[$i]['table']) . "." .
                                    strtoupper($filter[$i]['column']) . "] != " . $this->realEscapeString(
                                        $filter[$i]['data']['value']
                                    );
                                break;
                            case 'eq':
                                $qs .= " AND " . strtoupper($filter[$i]['table']) . "." .
                                    strtoupper($filter[$i]['column']) . " = " . $this->realEscapeString(
                                        $filter[$i]['data']['value']
                                    );
                                break;
                            case 'lt':
                                $qs .= " AND " . strtoupper($filter[$i]['table']) . "." .
                                    strtoupper($filter[$i]['column']) . " < " . strtoupper(
                                        $this->realEscapeString(
                                            $filter[$i]['data']['value']
                                        )
                                    );
                                break;
                            case 'gt':
                                $qs .= " AND " . strtoupper($filter[$i]['table']) . "." .
                                    strtoupper($filter[$i]['column']) . " > " . strtoupper(
                                        $this->realEscapeString(
                                            $filter[$i]['data']['value']
                                        )
                                    );
                                break;
                        }
                        break;
                    case 'date':
                        switch ($filter[$i]['data']['comparison']) {
                            case 'ne':
                                $qs .= " AND " . strtoupper($filter[$i]['table']) . "." .
                                    strtoupper($filter[$i]['column']) . " != '" .
                                    date(
                                        'Y-m-d',
                                        strtotime($filter[$i]['data']['value'])
                                    ) . "'";
                                break;
                            case 'eq':
                                $qs .= " AND " . strtoupper($filter[$i]['table']) . "." .
                                    strtoupper($filter[$i]['column']) . " = '" .
                                    date(
                                        'Y-m-d',
                                        strtotime($filter[$i]['data']['value'])
                                    ) . "'";
                                break;
                            case 'lt':
                                $qs .= " AND " . strtoupper($filter[$i]['table']) . "." .
                                    strtoupper($filter[$i]['column']) . " < '" .
                                    date(
                                        'Y-m-d',
                                        strtotime($filter[$i]['data']['value'])
                                    ) . "'";
                                break;
                            case 'gt':
                                $qs .= " AND " . strtoupper($filter[$i]['table']) . "." .
                                    strtoupper($filter[$i]['column']) . " > '" .
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
        if (isset($qs)) {
            return $qs;
        }
        return false;
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
                $str = (" and trunc(to_date(" . strtoupper($this->getTableName()) . "." . strtoupper(
                        $this->getColumnName()
                    ) . ",'YYYY-MM-DD hh24:mi:ss')) like '%" . $this->getStartDate() . "%'");
            } else if ($this->getDateFilterExtraTypeQuery() == 'next') {
                $dayNext = date("Y-m-d", mktime(0, 0, 0, $monthStart, (intval($dayStart) + 1), $yearStart));

                $this->setStartDate($dayNext);
                $str = (" and trunc(to_date(" . strtoupper($this->getTableName()) . "." . strtoupper(
                        $this->getColumnName()
                    ) . ",'YYYY-MM-DD hh24:mi:ss')) like '%" . $this->getStartDate() . "%'");
            } else {

                $str = (" and trunc(to_date(" . strtoupper($this->getTableName()) . "." . strtoupper(
                        $this->getColumnName()
                    ) . ",'YYYY-MM-DD hh24:mi:ss')) like '%" . $this->getStartDate() . "%'");
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
                $str = (" and (" . strtoupper($this->getTableName()) . "." . strtoupper(
                        $this->getColumnName()
                    ) . " between to_timestamp('" . $this->getStartDate(
                    ) . " 00:00:00' and to_timestamp('" . $this->getEndDate() . " 23:59:59','YYYY-MM-DD hh24:mi:ss'))");
            } else if ($this->getDateFilterExtraTypeQuery() == 'next') {

                $d = new \DateTime(date('Y-m-d', mktime(0, 0, 0, $monthStart, ($dayStart), $yearStart)));
                $weekday = $d->format('w');
                $diff = ($weekday == 0 ? 6 : $weekday - 1) - 7; // Monday=0, Sunday=6
                $d->modify("-$diff day");
                $this->setStartDate($d->format('Y-m-d'));
                $d->modify('+6 day');
                $this->setEndDate($d->format('Y-m-d'));
                $str = (" and (" . strtoupper($this->getTableName()) . "." . strtoupper(
                        $this->getColumnName()
                    ) . " between to_timestamp('" . $this->getStartDate(
                    ) . " 00:00:00','YYYY-MM-DD hh24:mi:ss') and to_timestamp('" . $this->getEndDate(
                    ) . " 23:59:59','YYYY-MM-DD hh24:mi:ss')')");
            } else {


                $d = new \DateTime(date('Y-m-d', mktime(0, 0, 0, $monthStart, ($dayStart), $yearStart)));
                $weekday = $d->format('w');
                $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
                $d->modify("-$diff day");
                $this->setStartDate($d->format('Y-m-d'));
                $d->modify('+6 day');
                $this->setEndDate($d->format('Y-m-d'));

                $str = (" and (" . strtoupper($this->getTableName()) . "." . strtoupper(
                        $this->getColumnName()
                    ) . " between to_timestamp('" . $this->getStartDate(
                    ) . " 00:00:00','YYYY-MM-DD hh24:mi:ss') and to_timestamp('" . $this->getEndDate(
                    ) . " 23:59:59','YYYY-MM-DD hh24:mi:ss'))");
            }
        } elseif ($this->getDateFilterTypeQuery() == 'month') {

            if ($this->getDateFilterExtraTypeQuery() == 'previous') {
                if (($monthStart - 1) == 0) {
                    $monthStart = 12;
                    $yearStart--;
                } else {
                    $monthStart--;
                }

                $str = (" and (to_number(to_char(" . strtoupper($this->getTableName()) . "." . strtoupper(
                        $this->getColumnName()
                    ) . ",'MM'))='" . $monthStart . "')  and (to_number(to_char(" . strtoupper(
                        $this->getTableName()
                    ) . "." . strtoupper($this->getColumnName()) . ",'SYYYY')))='" . $yearStart . "')");
            } else if ($this->getDateFilterExtraTypeQuery() == 'next') {

                if ((intval($monthStart) + 1) == 13) {
                    $monthStart = 1;
                    $yearStart++;
                } else {

                    $monthStart++;
                }
                $str = (" and (to_number(to_char(" . strtoupper($this->getTableName()) . "." . strtoupper(
                        $this->getColumnName()
                    ) . ",'MM'))='" . $monthStart . "')  and (to_number(to_char(" . strtoupper(
                        $this->getTableName()
                    ) . "." . strtoupper($this->getColumnName()) . ",'SYYYY'))='" . $yearStart . "')");
            } else {
                $str = (" and (to_number(to_char(" . strtoupper($this->getTableName()) . "." . strtoupper(
                        $this->getColumnName()
                    ) . ",'MM'))='" . $monthStart . "')  and (to_number(to_char(" . strtoupper(
                        $this->getTableName()
                    ) . "." . strtoupper($this->getColumnName()) . ",'SYYYY'))='" . $yearStart . "')");
            }
        } elseif ($this->getDateFilterTypeQuery() == 'year') {
            if ($this->getDateFilterExtraTypeQuery() == 'previous') {
                $yearStart--;
                $str = (" and (to_number(to_char(" . strtoupper($this->getTableName()) . "." . strtoupper(
                        $this->getColumnName()
                    ) . ",'SYYYY'))='" . $yearStart . "')");
            } else if ($this->getDateFilterExtraTypeQuery() == 'next') {
                $yearStart++;
                $str = (" and (to_number(to_char(" . strtoupper($this->getTableName()) . "." . strtoupper(
                        $this->getColumnName()
                    ) . ",'SYYYY'))='" . $yearStart . "')");
            } else {
                $str = (" and (to_number(to_char(" . strtoupper($this->getTableName()) . "." . strtoupper(
                        $this->getColumnName()
                    ) . ",'SYYYY'))='" . $yearStart . "')");
            }
        } elseif ($this->getDateFilterTypeQuery() == 'between') {
            $str = (" and (" . strtoupper($this->getTableName()) . "." . strtoupper(
                    $this->getColumnName()
                ) . " between  to_timestamp('" . $this->getStartDate(
                ) . " 00:00:00','YYYY-MM-DD hh24:mi:ss') and  to_timestamp('" . $this->getEndDate(
                ) . " 23:59:59','YYYY-MM-DD hh24:mi:ss'))");
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
        $matches= null;
        if (preg_match("/^(\\d{4})-(\\d{2})-(\\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $dateTime, $matches)) {
            if (checkdate($matches[2], $matches[3], $matches[1])) {
                return TRUE;
            }
        }
        return false;
        
    }

    /**
     * // this is for extjs .remove coma trail
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
        $this->setSql("SELECT ISAUDIT,ISLOG FROM LEAFLOG WHERE COMPANYID='".$this->getCompanyId()."'");
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
     */
    public function setSocket($value)
    {
        $this->socket = $value;
        return $this;
    }

    /**
     * Set Link
     * @param string|resource $value
     * @return \Core\Database\Oracle\Vendor
     */
    public function setLink($value)
    {
        $this->link = $value;
        return $this;
    }

    /**
     * Return Link
     * @return string|resource
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
     */
    public function setRoleId($value)
    {
        $this->roleId = $value;
        return $this;
    }

    /**
     *  Set Is Admin(Role Only) Identification
     * @param int $value
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
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
     * @return \Core\Database\Oracle\Vendor
     */
    public function setAdministratorEmail($value)
    {
        $this->administratorEmail = $value;
        return $this;
    }

    /**
     * Set Exception Message
     * @param string $value message
     * @return \Core\Database\Oracle\Vendor
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
        } else {
            echo "<div class='alert alert-error'><a class='close' data-dismiss='alert'>x</a><img src='./images/icons/smiley-nerd.png'>Weird no message meh</div>";

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
            $this->setMessage( "<div class='alert alert-error'><a class='close' data-dismiss='alert'>x</a><img src='./images/icons/smiley-nerd.png'> " . $this->getMessage(
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
     * Return Bind Parameter Name
     * @param array $bindParamName
     */
    public function setBindParamName($bindParamName)
    {
        $this->bindParamName = $bindParamName;
    }

    /**
     * Set Bind Param Name
     * @return array
     */
    public function getBindParamName()
    {
        return $this->bindParamName;
    }

    /**
     * Set Bind Parameter Value
     */
    public function setBindParamValue($bindParamValue)
    {
        $this->bindParamValue = $bindParamValue;
    }

    /**
     * Return Bind Paramter value
     * @return array
     */
    public function getBindParamValue()
    {
        return $this->bindParamValue;
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
