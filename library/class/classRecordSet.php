<?php

namespace Core\RecordSet;

use Core\ConfigClass;

require_once("classAbstract.php");

/**
 * Class RecordSet
 * Adodb Like function query .. moveFirst-> firstRecord,moveNext ->nextRecord,movePrevious->previousRecord,moveLast->lastRecord
 * Futured might depreciated if computer fast enough to use iterator for first,next,previous,end record.For now there are company using iterator concept and quite sluggish upon many/large record.JSP have their own iterator for it.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\RecordSet
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class RecordSet extends ConfigClass
{
     /**
* Connection to the database
* @var \Core\Database\Mysql\Vendor
*/
public $q;
    /**
     * @var string
     */
    private $PrimaryKeyName;
    /**
     * Special Case Adding Extra sql statement to the  record set
     * @var string
     */
    private $overrideMysqlStatement;
    /**
     *  Special Case Adding Extra sql statement to the  record set
     * @var string
     */
    private $overrideMicrosoftStatement;
    /**
     * Special Case Adding Extra sql statement to the  record set
     * @var string
     */
    private $overrideOracleStatement;

    /**
     /**
* Constructor 
     */
    function __construct()
    {
        if (isset($_SESSION['companyId'])) {
            $this->setCompanyId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
    }

    /**
     * Class Loader
     */
    public function execute()
    {
        parent::__construct();

    }

    /**
     * Read
     * @see config::read()
     * @return void
     */
    public function create()
    {

    }

    /**
     * Read
     * @see config::read()
     * @return void
     */
    public function read()
    {

    }

    /**
     * Update
     * @see config::update()
     * @return void
     */
    public function update()
    {

    }

    /**
     * Delete
     * @see config::delete()
     * @return void
     */
    public function delete()
    {

    }

    /**  Reporting
     * @see config::excel()
     * @return void
     */

    public function excel()
    {

    }

    /**
     * Return The First Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @return int
     * @throws \Exception
     */
    public function firstRecord($value)
    {
        if ($value == 'json') {
            header('Content-Type:application/json; charset=utf-8');
        }
        $firstRecord = 0;
        $total = 0;
		$value = (string)$this->strict($value,'string');
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  MIN(`" . $this->getPrimaryKeyName() . "`) AS `firstRecord`
            FROM    `" . strtolower($this->getCurrentTable()) . "`
            WHERE   `companyId` =   '" . $this->getCompanyId() . "'";
            if (isset($_SESSION['isAdmin'])) {
                if ($_SESSION['isAdmin'] == 0) {
                    $sql .= " 
                    AND `isActive` = 1 ";
                }
            }
            if ($this->getOverrideMysqlStatement()) {
                $sql .= $this->getOverrideMysqlStatement();
            }

        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  MIN([" . $this->getPrimaryKeyName() . "]) AS [firstRecord]
            FROM    [" . $this->getCurrentTable() . "]
            WHERE   [companyId] =   '" . $this->getCompanyId() . "'";
            if (isset($_SESSION['isAdmin'])) {
                if ($_SESSION['isAdmin'] == 0) {
                    $sql .= " 
                    AND [isActive] = 1 ";
                }
            }
            if ($this->getOverrideMicrosoftStatement()) {
                $sql .= $this->getOverrideMicrosoftStatement();
            }
        } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                SELECT MIN(" . strtoupper($this->getPrimaryKeyName()) . ") AS \"firstRecord\"
                FROM    " . strtoupper($this->getCurrentTable()) . "
                WHERE   COMPANYID   =   '" . $this->getCompanyId() . "'";
                if (isset($_SESSION['isAdmin'])) {
                    if ($_SESSION['isAdmin'] == 0) {
                        $sql .= " 
                        AND ISACTIVE = 1 ";
                    }
                }
                if ($this->getOverrideOracleStatement()) {
                    $sql .= $this->getOverrideOracleStatement();
                }
            
        }
        $result = $this->q->fast($sql);
        if ($result) {
            $total = $this->q->numberRows($result);
            if ($total > 0) {
                $row = $this->q->fetchAssoc($result);
                $firstRecord = $row ['firstRecord'];
            } else {
                $firstRecord = 0;
            }
        } else {
            $this->exceptionMessage($this->q->getResponse());
        }
		if($firstRecord === null || $firstRecord ==='') {
			$firstRecord=0;
		}
        if ($value == 'value') {

            return intval($firstRecord);
        } else {
            echo $json_encode = json_encode(array('success' => true, 'total' => $total, 'firstRecord' => $firstRecord));
            exit();
        }
    }

    /**
     * Return Next record
     * @param string $value Value
     * @param int $primaryKeyValue Primary Key Value
     * @return int
     * @throws \Exception
     */
    public function nextRecord($value, $primaryKeyValue)
    {
        if ($value == 'json') {
            header('Content-Type:application/json; charset=utf-8');
        }
		$value = (string)$this->strict($value,'string');
		$primaryKeyValue = (int)$this->strict($primaryKeyValue,'numeric');
        $sql = "";
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  (`" . $this->getPrimaryKeyName() . "`) AS `nextRecord`
            FROM    `" . strtolower($this->getCurrentTable()) . "`
            WHERE   `" . $this->getPrimaryKeyName() . "` > " . $primaryKeyValue . "
            AND     `companyId` =   '".$this->getCompanyId()."'
            ";
            if (isset($_SESSION['isAdmin'])) {
                if ($_SESSION['isAdmin'] == 0) {
                    $sql .= " 
                    AND `isActive` = 1 ";
                }
            }
            if ($this->getOverrideMysqlStatement()) {
                $sql .= $this->getOverrideMysqlStatement();
            }
            $sql .= " 
            LIMIT 1 ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  TOP 1 ([" . $this->getPrimaryKeyName() . "]) AS [nextRecord]
            FROM 	[" . $this->getCurrentTable() . "]
            WHERE 	[" . $this->getPrimaryKeyName() . "] > " . $primaryKeyValue . "
            AND     [companyId] =   '" . $this->getCompanyId() . "'";
            if (isset($_SESSION['isAdmin'])) {
                if ($_SESSION['isAdmin'] == 0) {
                    $sql .= " 
                    AND [isActive] = 1 ";
                }
            }
            if ($this->getOverrideMicrosoftStatement()) {
                $sql .= $this->getOverrideMicrosoftStatement();
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  (" . strtoupper($this->getPrimaryKeyName()) . ") AS \"nextRecord\"
            FROM    " . strtoupper($this->getCurrentTable()) . "
            WHERE   " . strtoupper($this->getPrimaryKeyName()) . " > " . $primaryKeyValue . "
            AND     COMPANYID   =   '" . $this->getCompanyId() . "'
            AND     ROWNUM = 1";

            if (isset($_SESSION['isAdmin'])) {
                if ($_SESSION['isAdmin'] == 0) {
                    $sql .= " 
                    AND ISACTIVE = 1 ";
                }
            }
            if ($this->getOverrideOracleStatement()) {
                $sql .= $this->getOverrideOracleStatement();
            }
        }
        $result = $this->q->fast($sql);
        $total = $this->q->numberRows($result);
        if ($total > 0) {
            $row = $this->q->fetchAssoc($result);
            $nextRecord = $row ['nextRecord'];
        } else {
            $nextRecord = 0;
        }
		if($nextRecord === null || $nextRecord ==='') {
			$nextRecord=0;
		}
        if ($value == 'value') {
            return intval($nextRecord);
        } else {
            echo $json_encode = json_encode(array('success' => true, 'total' => $total, 'nextRecord' => $nextRecord));
            exit();
        }
    }

    /**
     * Return Previous Record
     * @param string $value Value
     * @param int $primaryKeyValue Primary Key Value
     * @return int
     * @throws \Exception
     */
    public function previousRecord($value, $primaryKeyValue)
    {
        if ($value == 'json') {
            header('Content-Type:application/json; charset=utf-8');
        }
		$value = (string)$this->strict($value,'string');
		$primaryKeyValue = (int)$this->strict($primaryKeyValue,'numeric');
        $sql = "";
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT (`" . $this->getPrimaryKeyName() . "`) AS `previousRecord`
            FROM    `" . strtolower($this->getCurrentTable()) . "`
            WHERE   `" . $this->getPrimaryKeyName() . "` < " . $primaryKeyValue . "
            AND     `companyId` =   '".$this->getCompanyId()."'";
            if (isset($_SESSION['isAdmin'])) {
                if ($_SESSION['isAdmin'] == 0) {
                    $sql .= "
                    AND `isActive` = 1 ";
                }
            }
            if ($this->getOverrideMysqlStatement()) {
                $sql .= $this->getOverrideMysqlStatement();
            }
            $sql .= "
            ORDER BY `" . $this->getPrimaryKeyName() . "` DESC
            LIMIT 1   ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT TOP 1 ([" . $this->getPrimaryKeyName() . "]) AS [previousRecord]
            FROM    [" . $this->getCurrentTable() . "]
            WHERE   [" . $this->getPrimaryKeyName() . "] < " . $primaryKeyValue . "
            AND     [companyId] =   '" . $this->getCompanyId() . "'";
            if (isset($_SESSION['isAdmin'])) {
                if ($_SESSION['isAdmin'] == 0) {
                    $sql .= " 
                    AND [isActive] = 1 ";
                }
            }
            if ($this->getOverrideMicrosoftStatement()) {
                $sql .= $this->getOverrideMicrosoftStatement();
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT (" . strtoupper($this->getPrimaryKeyName()) . ") AS \"previous\"
            FROM    " . strtoupper($this->getCurrentTable()) . "
            WHERE   " . strtoupper($this->getPrimaryKeyName()) . " < " . $primaryKeyValue . "
            AND     COMPANYID   =   '" . $this->getCompanyId() . "'
            AND     ROWNUM  = 1";

            if (isset($_SESSION['isAdmin'])) {
                if ($_SESSION['isAdmin'] == 0) {
                    $sql .= "
                    AND ISACTIVE = 1 ";
                }
            }
            if ($this->getOverrideOracleStatement()) {
                $sql .= $this->getOverrideOracleStatement();
            }
        }
        $result = $this->q->fast($sql);
        $total = $this->q->numberRows($result);
        if ($total > 0) {
            $row = $this->q->fetchAssoc($result);
            $previousRecord = $row ['previousRecord'];
        } else {
            $previousRecord = 0;
        }
		if($previousRecord === null || $previousRecord ==='') {
			$previousRecord=0;
		}
        if ($value == 'value') {
            return intval($previousRecord);
        } else {
            echo $json_encode = json_encode(
                array('success' => true, 'total' => $total, 'previousRecord' => $previousRecord)
            );
            exit();
        }
    }

    /**
     * Return Last Record
     * @param string $value
     * @return int
     * @throws \Exception
     */
    public function lastRecord($value)
    {
        if ($value == 'json') {
            header('Content-Type:application/json; charset=utf-8');
        }
		$value = (string)$this->strict($value,'string');
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  MAX(`" . $this->getPrimaryKeyName() . "`) AS `lastRecord`
            FROM    `" . strtolower($this->getCurrentTable()) . "`
            WHERE   companyId   =   '".$this->getCompanyId()."'";
            if (isset($_SESSION['isAdmin'])) {
                if ($_SESSION['isAdmin'] == 0) {
                    $sql .= " 
                    AND `isActive` = 1 ";
                }
            }
            if ($this->getOverrideMysqlStatement()) {
                $sql .= $this->getOverrideMysqlStatement();
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  MAX([" . $this->getPrimaryKeyName() . "]) AS [lastRecord]
            FROM    [" . $this->getCurrentTable() . "]
            WHERE   [companyId] =   '" . $this->getCompanyId() . "'";
            if (isset($_SESSION['isAdmin'])) {
                if ($_SESSION['isAdmin'] == 0) {
                    $sql .= " 
                    AND [isActive] = 1 ";
                }
            }
            if ($this->getOverrideMicrosoftStatement()) {
                $sql .= $this->getOverrideMicrosoftStatement();
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  MAX(" . strtoupper($this->getPrimaryKeyName()) . ") AS \"lastRecord\"
            FROM    " . strtoupper($this->getCurrentTable()) . " 
            WHERE   COMPANYID   =   '" . $this->getCompanyId() . "'";
            if (isset($_SESSION['isAdmin'])) {
                if ($_SESSION['isAdmin'] == 0) {
                    $sql .= "
                    AND ISACTIVE = 1 ";
                }
            }
            if ($this->getOverrideOracleStatement()) {
                $sql .= $this->getOverrideOracleStatement();
            }
        } 
        $result = $this->q->fast($sql);
        $total = $this->q->numberRows($result);
        if ($total > 0) {
            $row = $this->q->fetchAssoc($result);
            $lastRecord = $row ['lastRecord'];
        } else {
            $lastRecord = 0;
        }
		if($lastRecord === null || $lastRecord ==='') {
			$lastRecord=0;
		}
        if ($value == 'value') {
            return intval($lastRecord);
        } else {
            echo $json_encode = json_encode(array('success' => true, 'total' => $total, 'lastRecord' => $lastRecord));
            exit();
        }
    }

    /**
     * Generate Sequence Order
     * @depreciate
     * @param null|int $moduleId Module Primary Key
     * @param null|int $folderId Folder Primary Key
     * @throws \Exception
     */
    public function nextSequence($moduleId = null, $folderId = null)
    {
        header('Content-Type:application/json; charset=utf-8');
        if ($this->getVendor() == self::MYSQL) {

            $sql = "SET NAMES \"utf8\"";
            $this->q->fast($sql);
        }
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT 	(MAX(`" . $this->getCurrentTable() . "Sequence`)+1) AS `nextSequence`
            FROM 	.`" . strtolower($this->getCurrentTable()) . "`
            WHERE	`isActive` = 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT 	(MAX([" . $this->getCurrentTable() . "Sequence])+1) AS [nextSequence]
            FROM 	[" . $this->getCurrentTable() . "]
            WHERE 	[isActive]=1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT 	(MAX('" . $this->getCurrentTable() . "Sequence\")+1) AS \"nextSequence\"
            FROM 	'" . $this->getCurrentTable() . "'
            WHERE	ISACTIVE=1";
        }
        if ($this->getCurrentTable() == 'folder') {
            if (isset($moduleId)) {
                $sql .= " AND `moduleId`='" . $moduleId . "'";
            } else {
                echo json_encode(array("success" => false, "message" => "Module Identification Not Found"));
                exit();
            }
        }
        if ($this->getCurrentTable() == 'leaf') {
            if (isset($moduleId)) {
                $sql .= " AND `moduleId`='" . $moduleId . "'";
            } else {
                echo json_encode(array("success" => false, "message" => "Module Identification Not Found"));
                exit();
            }
            if (isset($folderId)) {
                $sql .= " AND `folderId`='" . $folderId . "'";
            } else {
                echo json_encode(array("success" => false, "message" => "Folder Identification Not Found"));
                exit();
            }
        }

        $result = $this->q->fast($sql);
        $row = $this->q->fetchAssoc($result);
        $nextSequence = $row ['nextSequence'];
        if ($nextSequence == 0) {
            $nextSequence = 1;
        }
        //return $nextSequence;
		if($nextSequence === null || $nextSequence ==='') {
			$nextSequence=0;
		}
        echo json_encode(array("success" => true, "nextSequence" => $nextSequence));
        exit();
    }

    /**
     * Return Primary Key Name
     * @return string
     */
    public function getPrimaryKeyName()
    {
        return $this->PrimaryKeyName;
    }

    /**
     * Set Primary Key Name
     * @param string $PrimaryKeyName
     * @return \Core\RecordSet\RecordSet
     */
    public function setPrimaryKeyName($PrimaryKeyName)
    {
        $this->PrimaryKeyName = $PrimaryKeyName;
        return $this;
    }

    /**
     * set Special Case Adding Extra sql statement to the  record set
     * @param string $overrideMicrosoftStatement
     ** @return \Core\RecordSet\RecordSet
     */
    public function setOverrideMicrosoftStatement($overrideMicrosoftStatement)
    {
        $this->overrideMicrosoftStatement = $overrideMicrosoftStatement;
        return $this;
    }

    /**
     * Return Special Case Adding Extra sql statement to the  record set
     * @return string
     */
    public function getOverrideMicrosoftStatement()
    {
        return $this->overrideMicrosoftStatement;
    }

    /**
     * Set Special Case Adding Extra sql statement to the  record set
     * @param string $overrideMysqlStatement
     * @return \Core\RecordSet\RecordSet
     */
    public function setOverrideMysqlStatement($overrideMysqlStatement)
    {
        $this->overrideMysqlStatement = $overrideMysqlStatement;
        return $this;
    }

    /**
     * Return Special Case Adding Extra sql statement to the  record set
     * @return string
     */
    public function getOverrideMysqlStatement()
    {
        return $this->overrideMysqlStatement;
    }

    /**
     * Set Special Case Adding Extra sql statement to the  record set
     * @param string $overrideOracleStatement
     * @return \Core\RecordSet\RecordSet
     */
    public function setOverrideOracleStatement($overrideOracleStatement)
    {
        $this->overrideOracleStatement = $overrideOracleStatement;
        return $this;
    }

    /**
     * Return Special Case Adding Extra sql statement to the  record set
     * @return string
     */
    public function getOverrideOracleStatement()
    {
        return $this->overrideOracleStatement;
    }


}