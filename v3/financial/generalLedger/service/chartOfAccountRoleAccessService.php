<?php

namespace Core\Financial\GeneralLedger\ChartOfAccountRoleAccess\Service;

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
 * Class ChartOfAccountRoleAccessService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\GeneralLedger\ChartOfAccountRoleAccess\Service
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ChartOfAccountRoleAccessService extends ConfigClass {

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
             SELECT      `chartOfAccountId`,
                         `chartOfAccountDesc`
             FROM        `chartofaccount`
             WHERE       `isActive`=1
             ORDER BY    `chartOfAccountSequence`,
                             `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
             SELECT      [chartOfAccountId],
                             [chartOfAccountDesc]
             FROM        [chartOfAccount]
             WHERE       [isActive]=1
             ORDER BY    [chartOfAccountSequence],
                             [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
             SELECT      CHARTOFACCOUNTID,
                         CHARTOFACCOUNTDESC
             FROM        CHARTOFACCOUNT
             WHERE       ISACTIVE=1
             ORDER BY    CHARTOFACCOUNTSEQUENCE,
                             ISDEFAULT";
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
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['chartOfAccountId'] . "'>" . $row['chartOfAccountDesc'] . "</option>";
                } else {
                    if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
                    }
                }
            }
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
     * Return Role
     * @return array|string
     */
    public function getRole() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
             SELECT      `roleId`,
                         `roleDescription`
             FROM        `role`
             WHERE       `isActive`=1
             ORDER BY    `roleSequence`,
                             `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
             SELECT      [roleId],
                             [roleDescription]
             FROM        [role]
             WHERE       [isActive]=1
             ORDER BY    [roleSequence],
                             [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
             SELECT      ROLEID,
                         ROLEDESC
             FROM        ROLE
             WHERE       ISACTIVE=1
             ORDER BY    ROLESEQUENCE,
                             ISDEFAULT";
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
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['roleId'] . "'>" . $row['roleDescription'] . "</option>";
                } else {
                    if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
                    }
                }
            }
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