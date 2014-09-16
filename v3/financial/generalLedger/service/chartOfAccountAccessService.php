<?php

namespace Core\Financial\GeneralLedger\ChartOfAccountAccess\Service;

use Core\ConfigClass;

if (!isset($_SESSION)) {
    session_start();
}
// using absolute path instead of relative path..
// start fake document root. it's absolute path
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
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot);
require_once($newFakeDocumentRoot . "library/class/classAbstract.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");

/**
 * Class ChartOfAccountAccessService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\GeneralLedger\ChartOfAccountAccess\Service
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ChartOfAccountAccessService extends ConfigClass {

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
     * Class Loader
     */
    function execute() {
        parent::__construct();
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
            ORDER BY    `chartofaccount`.`chartOfAccountNumber` ASC ;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT      [chartOfAccount].[chartOfAccountId],
                        [chartOfAccount].[chartOfAccountNumber],
                        [chartOfAccount].[chartOfAccountTitle],
                        [chartOfAccountType].[chartOfAccountTypeDescription]
            FROM        [chartOfAccount]

            JOIN        [chartOfAccountType]
            ON          [chartOfAccountType].[companyId] = [chartOfAccount][companyId]
            AND         [chartOfAccountType].[chartOfAccountTypeId] = [chartOfAccount].[chartOfAccountTypeId]

            WHERE       [chartOfAccount].[isActive]  =   1
            AND         [chartOfAccount].[companyId] =   '" . $this->getCompanyId() . "'

            ORDER BY    [chartOfAccount].[chartOfAccountNumber] ASC ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT      CHARTOFACCOUNT.CHARTOFACCOUNTID AS \"chartOfAccountId\",
                        CHARTOFACCOUNT.CHARTOFACCOUNTNUMBER AS \"chartOfAccountNumber\",
                        CHARTOFACCOUNT.CHARTOFACCOUNTTITLE AS \"chartOfAccountTitle\",
                        CHARTOFACCOUNTTYPE.CHARTOFACCOUNTTYPEDESCRIPTION AS \"chartOfAccountTypeDescription\"
            FROM        CHARTOFACCOUNT

            JOIN        CHARTOFACCOUNTTYPE
            ON          CHARTOFACCOUNTTYPE.COMPANYID = CHARTOFACCOUNTCOMPANYID
            AND         CHARTOFACCOUNTTYPE.CHARTOFACCOUNTTYPEID = CHARTOFACCOUNT.CHARTOFACCOUNTTYPEID

            WHERE       CHARTOFACCOUNT.ISACTIVE     =   1
            AND         CHARTOFACCOUNT.COMPANYID    =   '" . $this->getCompanyId() . "'
            ORDER BY    CHARTOFACCOUNT.CHARTOFACCOUNTNUMBER ASC ";
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
        $chartOfAccountTypeDescription = null;
        if ($result) {
            $d = 0;
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
                    $str .= "<option value='" . $row['chartOfAccountId'] . "'>" . $row['chartOfAccountNumber'] . ". " . $row['chartOfAccountTitle'] . "</option>";
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
     * Return Staff
     * @return string
     */
    public function getStaff() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
            SELECT      `staffId`,
                        `staffName`,
                        `roleDescription`
            FROM        `staff`
            JOIN        `role`
            USING       (`companyId`,`roleId`)
            WHERE       `staff`.`isActive`  =   1
            AND         `staff`.`companyId` =   '" . $this->getCompanyId() . "'
            ORDER BY    `staff`.`staffId`,
                        `staff`.`isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT      [staffId],
                        [staffName],
                        [roleDescription]
            FROM        [staff]
            JOIN        [role]
            ON          [staff].[companyId] =  [role].[companyId]
            AND         [staff].[roleId]    =  [role].[roleId]
            WHERE       [staff].[isActive]  =   1
            AND         [staff].[companyId] =   '" . $this->getCompanyId() . "'
            ORDER BY    [staff].[staffId],
                        [staff].[isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT      STAFFID         AS  \"staffId\",
                        STAFFNAME       AS  \"staffName\",
                        ROLEDESCRIPTION AS  \"roleDescription\"
            FROM        STAFF
            JOIN        ROLE
            ON          STAFF.COMPANYID   =  ROLE.COMPANYID
            AND         STAFF.ROLEID      =  ROLE.ROLEID
            WHERE       STAFF.ISACTIVE    =   1
            AND         STAFF.COMPANYID   =   '" . $this->getCompanyId() . "'
            ORDER BY    STAFF.STAFFID,
                        STAFF.ISDEFAULT";
                } else {
                    header('Content-Type:application/json; charset=utf-8');
                    echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
                    exit();
                }
            }
        }
        $result = $this->q->fast($sql);
        if ($result) {
            $items = array();
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['staffId'] . "'>" . $d . " " . $row['staffName'] . "</option>";
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
                return "<option value=''>" . $this->t['pleaseSelectTextLabel'] . "</option>" . $str;
            } else {
                return "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
            }
        } else {
            if ($this->getServiceOutput() == 'html') {
                return $items;
            }
        }
        // fake return
        return $items;
    }

    /**
     * Create
     * @see config::create()
     */
    public function create() {
        
    }

    /**
     * Read
     * @see config::read()
     */
    public function read() {
        
    }

    /**
     * Update
     * @see config::update()
     */
    public function update() {
        
    }

    /**
     * Delete
     * @see config::delete()
     */
    public function delete() {
        
    }

    /**
     * Reporting
     * @see config::excel()
     */
    public function excel() {
        
    }

}

?>