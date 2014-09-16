<?php

namespace Core\Portal\Main\StaffWebAccess\Service;

// start fake document root. it's absolute path

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
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot);
require_once($newFakeDocumentRoot . "library/class/classAbstract.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");

/**
 * Class StaffWebAccessBrowserService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Main\StaffWebAccess\Service
 * @subpackage Management
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class StaffWebAccessBrowserService extends ConfigClass {

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
     * Get The Browser Type
     * @return mixed
     * @throws \Exception
     */
    function getDistinctBrowserType() {
        $data = array();
        $sql = "
        SELECT  COUNT( * ) AS `Rows` , 
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE    `companyId`='" . $this->getCompanyId() . "'
        GROUP BY `ua_type`
        ORDER BY `ua_type`";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($row['ua_type'] == 'robot') {
                    $data['robot'] = $row['Rows'];
                } else {
                    if ($row['ua_type'] == 'browser') {
                        $data['browser'] = $row['Rows'];
                    } elseif ($row['ua_type'] == 'mobile browser') {
                        $data['mobile browser'] = $row['Rows'];
                    } elseif ($row['ua_type'] == 'email client') {
                        $data['email client'] = $row['Rows'];
                    } elseif ($row['ua_type'] == 'wap browser') {
                        $data['wap browser'] = $row['Rows'];
                    } elseif ($row['ua_type'] == 'offline browser') {
                        $data['offline browser'] = $row['Rows'];
                    } elseif ($row['ua_type'] == 'ua anonymizer') {
                        $data['ua anonymizer'] = $row['Rows'];
                    } elseif ($row['ua_type'] == 'library') {
                        $data['library'] = $row['Rows'];
                    } else {
                        if ($row['ua_type'] == 'other') {
                            $data['other'] = intval($row['Rows'] + 0);
                        }
                    }
                }
            }
        }
        return $data;
    }

    /**
     * Get Total Robot
     * @return mixed
     * @throws \Exception
     */
    function getRobot() {
        $total = 0;
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM   `staffwebaccess`
        WHERE    `companyId`='" . $this->getCompanyId() . "'
        AND    `ua_type` ='robot'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            if (is_array($row)) {
                $total = intval($row['Rows']);
            }
        }
        return $total;
    }

    /**
     * Get Total Browser(Pc or Laptop)
     * @return mixed
     * @throws \Exception
     */
    function getBrowser() {
        $total = 0;
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE   `ua_type` ='browser'
		AND		`companyId`	=	'" . $this->getCompanyId() . "'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            if (is_array($row)) {
                $total = intval($row['Rows']);
            }
        }
        return $total;
    }

    /**
     * Get Total Mobile Browser
     * @return mixed
     * @throws \Exception
     */
    function getMobileBrowser() {
        $total = 0;
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE   `ua_type` ='mobile browser'
		AND		`companyId`	=	'" . $this->getCompanyId() . "'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            if (is_array($row)) {
                $total = intval($row['Rows']);
            }
        }
        return $total;
    }

    /**
     * Get Total Email Client Browser
     * @return mixed
     * @throws \Exception
     */
    function getEmailClient() {
        $total = 0;
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM   `staffwebaccess`
        WHERE    `companyId`='" . $this->getCompanyId() . "'
        AND    `ua_type` ='email client'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            if (is_array($row)) {
                $total = intval($row['Rows']);
            }
        }
        return $total;
    }

    /**
     * Get Total Wap Browser Client Browser
     * @return mixed
     * @throws \Exception
     */
    function getWapBrowser() {
        $total = 0;
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM   `staffwebaccess`
        WHERE    `companyId`='" . $this->getCompanyId() . "'
        AND    `ua_type` ='wap browser'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            if (is_array($row)) {
                $total = intval($row['Rows']);
            }
        }
        return $total;
    }

    /**
     * Get Total Offline Browser
     * return int $total Total Record
     * @throws \Exception
     */
    function getOfflineBrowser() {
        $total = 0;
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM   `staffwebaccess`
        WHERE   `companyId`='" . $this->getCompanyId() . "'
        AND     `ua_type` ='offline browser'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            if (is_array($row)) {
                $total = intval($row['Rows']);
            }
        }
        return $total;
    }

    /**
     * Get Total Ua Anonymizer..????
     * return int $total Total Record
     * @throws \Exception
     */
    function getUaAnonymizer() {
        $total = 0;
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM   `staffwebaccess`
        WHERE    `companyId`='" . $this->getCompanyId() . "'
        AND    `ua_type` ='ua anonymizer'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            if (is_array($row)) {
                $total = intval($row['Rows']);
            }
        }
        return $total;
    }

    /**
     * Get Total Library Browser
     * return int $total Total Record
     * @throws \Exception
     */
    function getLibraryBrowser() {
        $total = 0;
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM   `staffwebaccess`
        WHERE   `companyId`='" . $this->getCompanyId() . "'
        AND     `ua_type` ='library'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            if (is_array($row)) {
                $total = intval($row['Rows']);
            }
        }
        return $total;
    }

    /**
     * Get Total Others Browser
     * return int $total Total Record
     * @throws \Exception
     */
    function getOthersBrowserType() {
        $total = 0;
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM   `staffwebaccess`
        WHERE    `companyId`='" . $this->getCompanyId() . "'
        AND    `ua_type` ='other'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            if (is_array($row)) {
                $total = intval($row['Rows']);
            }
        }
        return $total;
    }

    /**
     * Get Total Common Type vs unknown above such as library,unknown anoymizer ,offline browser
     * return int $total Total Record
     * @throws \Exception
     */
    function getOthersBrowserSpecialType() {
        $total = 0;
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM   `staffwebaccess`
        WHERE    `companyId`='" . $this->getCompanyId() . "'
        AND    `ua_type` NOT IN('browser','mobile browser','robot');";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            if (is_array($row)) {
                $total = intval($row['Rows']);
            }
        }
        return $total;
    }

    /**
     * Return Popular Browser
     * IE,FireFox,Chrome,Safari,Opera...
     * return array $data Total Record
     * @throws \Exception
     */
    function getPopularBrowser() {
        $data = array();
        // FireFox
        $sql = "
        SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE    `companyId`='" . $this->getCompanyId() . "'
        AND     `ua_family` = 'Firefox'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        $data['Firefox'] = intval($row['Rows'] + 0);

        // FireFox
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE     `companyId`='" . $this->getCompanyId() . "'
        AND    `ua_family` = 'Chrome'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        $data['chrome'] = intval($row['Rows'] + 0);

        // FireFox
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE     `companyId`='" . $this->getCompanyId() . "'
        AND    `ua_family` = 'Opera'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        $data['Opera'] = intval($row['Rows'] + 0);

        // FireFox
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE   `companyId`='" . $this->getCompanyId() . "'
        AND    `ua_family` = 'Safari'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        $data['Safari'] = intval($row['Rows'] + 0);

        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE   `companyId`='" . $this->getCompanyId() . "'
        AND    `ua_family` = 'IE'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        $data['IE'] = intval($row['Rows'] + 0);

        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE     `companyId`='" . $this->getCompanyId() . "'
        AND    `ua_family` NOT IN ('IE','Safari','Opera','Chrome','Firefox');";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        $data['others'] = intval($row['Rows'] + 0);

        return $data;
    }

    /**
     * Get Total Record Internet Explorer
     * return int $total Total Record
     * @throws \Exception
     */
    function getInternetExplorer() {
        $total = 0;
        $sql = "
		SELECT count(*) AS Rows,
				`ua_type` 
		FROM    `staffwebaccess`
		WHERE  `ua_family` = 'IE'
		AND		`companyId`	=	'" . $this->getCompanyId() . "'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            if (is_array($row)) {
                $total = intval($row['Rows']);
            }
        }
        return $total;
    }

    /**
     * Get Total Record FireFox
     * return int $total Total Record
     * @throws \Exception
     */
    function getFireFox() {
        $total = 0;
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE    `companyId`='" . $this->getCompanyId() . "'
        AND    `ua_family` = 'Firefox'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            if (is_array($row)) {
                $total = intval($row['Rows']);
            }
        }
        return $total;
    }

    /**
     * Get Total Record Google Chrome
     * return int $total Total Record
     * @throws \Exception
     */
    function getChrome() {
        $total = 0;
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE    `companyId`='" . $this->getCompanyId() . "'
        AND     `companyId`='" . $this->getCompanyId() . "'
        AND     `ua_family` = 'Chrome'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            if (is_array($row)) {
                $total = intval($row['Rows']);
            }
        }
        return $total;
    }

    /**
     * Get Total Record Opera Browser
     * return int $total Total Record
     * @throws \Exception
     */
    function getOpera() {
        $total = 0;
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE    `companyId`='" . $this->getCompanyId() . "'
        AND    `ua_family` = 'Opera'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            if (is_array($row)) {
                $total = intval($row['Rows']);
            }
        }
        return $total;
    }

    /**
     *  Get Total Record Apple Safari
     * return int $total Total Record
     * @throws \Exception
     */
    function getSafari() {
        $total = 0;
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE   `companyId`='" . $this->getCompanyId() . "'
        AND    `ua_family` = 'Safari'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            if (is_array($row)) {
                $total = intval($row['Rows']);
            }
        }
        return $total;
    }

    /**
     * Get  Total Record Other Browser
     * return mixed
     * @throws \Exception
     */
    function getOtherBrowser() {
        $total = 0;
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM   `staffwebaccess`
        WHERE    `companyId`='" . $this->getCompanyId() . "'
        AND    `ua_family` NOT IN ('IE','Safari','Opera','Chrome','Firefox');";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            if (is_array($row)) {
                $total = intval($row['Rows']);
            }
        }
        return $total;
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

/**
 * Class StaffWebAccessOperatingSystemService
 * this is message setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Main\StaffWebAccess\Service
 * @subpackage Management
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class StaffWebAccessOperatingSystemService extends ConfigClass {

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
     * Get popular operating system
     * @return mixed
     */
    public function getPopularOperatingSystem() {
        $data = array();
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family` = 'Windows'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        $data['Windows'] = $row['Rows'] + 0;

        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE    `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family` = 'Linux'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        $data['Linux'] = $row['Rows'] + 0;

        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family` IN ('Mac OS X','Mac OS');";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        $data['mac'] = $row['Rows'] + 0;

        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family` NOT IN  ('Windows','Linux','Mac OS X','Mac OS');";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        $data['others'] = $row['Rows'] + 0;
        return $data;
    }

    /**
     * Get Total Microsoft Operating System including mobile
     * @return mixed
     */
    public function getMicrosoftWindows() {
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family` = 'Windows'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        return $row['Rows'] + 0;
    }

    /**
     * Get Total Operating System Mac Os
     * @return mixed
     */
    public function getMacOs() {
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family` IN ('Mac OS');";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        return $row['Rows'] + 0;
    }

    /**
     * Get Total Apple Operating System Max Os x
     * @return mixed
     */
    public function getMacOsx() {
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family` IN ('Mac OS X');";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        return ($row['Rows'] + 0);
    }

    /**
     * Get Total Apple Operating System  8x 9x x
     * @return mixed
     */
    public function getAppleMac() {
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family` IN ('Mac OS X','Mac OS');";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        return ($row['Rows'] + 0);
    }

    /**
     * Get Total Linux
     * @return mixed
     */
    public function getLinux() {
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE    `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family` = 'Linux'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        return ($row['Rows'] + 0);
    }

    /**
     * Get Total Android..
     * @return mixed
     */
    public function getAndroid() {
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		 `os_family` = 'Android'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        return ($row['Rows'] + 0);
    }

    /**
     * Get Total Ios.. Iphone,Ipad,itouch
     * @return mixed
     */
    public function getiOS() {
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE    `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family` = 'iOS'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        return ($row['Rows'] + 0);
    }

    /**
     * Get Total Bsd
     * @return mixed
     */
    public function getBsd() {
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		 `os_family` = 'BSD'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        return ($row['Rows'] + 0);
    }

    /**
     * Get Total Solaris
     * @return mixed
     */
    public function getSolaris() {
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		 `os_family` = 'Solaris'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        return ($row['Rows'] + 0);
    }

    /**
     * Other Operating system
     * @return mixed
     */
    public function getOtherOperatingSystem() {
        $sql = "SELECT count(*) AS Rows,
                `ua_type` 
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family` NOT IN  ('Windows','Linux','Mac OS X','Mac OS');";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        return ($row['Rows'] + 0);
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

/**
 * Class StaffWebAccessInternetProtocolService
 * this is message setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Main\StaffWebAccess\Service
 * @subpackage Management
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class StaffWebAccessInternetProtocolService extends ConfigClass {

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
     * @return mixed
     */
    function getNewTicket() {
        $sql = "
        SELECT  count(*) AS Rows
        FROM    `ticket` 
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`isNew`=1";
        if ($_SESSION['isAdmin'] == 0) {
            $sql .= " AND `staffIdFrom`='" . $_SESSION['staffId'] . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        return ($row['Rows'] + 0);
    }

    /**
     * @return mixed
     */
    function getReviewTicket() {
        $sql = "
        SELECT  count(*) AS Rows
        FROM    `ticket` 
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`isReview`=1";
        if ($_SESSION['isAdmin'] == 0) {
            $sql .= " AND `staffIdFrom`='" . $_SESSION['staffId'] . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        return ($row['Rows'] + 0);
    }

    /**
     * Return Solved Ticket
     * @return mixed
     */
    function getSolveTicket() {
        $sql = "
        SELECT  count(*) AS Rows
        FROM    `ticket` 
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`isSolve`=1";
        if ($_SESSION['isAdmin'] == 0) {
            $sql .= " AND `staffIdFrom`='" . $_SESSION['staffId'] . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        return ($row['Rows'] + 0);
    }

    /**
     * @return mixed
     */
    function getTotalTicket() {
        $sql = "
        SELECT  count(*) AS Rows
        FROM    `ticket` 
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "' ";
        if ($_SESSION['isAdmin'] == 0) {
            $sql .= " AND `staffIdFrom`='" . $_SESSION['staffId'] . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $row = $this->q->fetchArray($result);
        return ($row['Rows'] + 0);
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

/**
 * Class StaffWebAccessCrossTabBrowserService
 * this is message setting files.This sample template file for master record
 * @property mixed week
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Main\StaffWebAccess\Service
 * @subpackage Management
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class StaffWebAccessCrossTabBrowserService extends ConfigClass {

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
     * Day
     * @var int
     */
    private $day;

    /**
     * Month
     * @var int
     */
    private $month;

    /**
     * Year
     * @var int
     */
    private $year;

    /**
     * Total Day In Month
     * @var int
     */
    private $totalDayInMonth;

    /**
     * Constructor
     */
    public function __construct() {
        
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
    }

    /**
     * Return Cross Tab Time All Browser
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeAllBrowser($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        $hour = null;
        while ($hour++ < 23) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                            $hour
                    ) . "%',1,0)) ,0)as `" . $hour . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
		WHERE	`companyId`	=	'" . $this->getCompanyId() . "'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Get Month
     * @return int
     */
    function getMonth() {
        return $this->month;
    }

    /**
     * Set Month
     * @param int $value
     */
    function setMonth($value) {
        $this->month = $value;
    }

    /**
     *
     * @return int
     */
    function getDay() {
        return $this->day;
    }

    /**
     *
     * @param int $value
     */
    function setDay($value) {
        $this->day = $value;
    }

    /**
     * Return Year
     * @return int
     */
    function getYear() {
        return $this->year;
    }

    /**
     * Set Year
     * @param int $value
     */
    function setYear($value) {
        $this->year = $value;
    }

    /**
     *
     * @param string $dateInfo
     * @return string
     */
    function changeZero($dateInfo) {
        if (strlen($dateInfo) == 1) {
            $dateInfo = '0' . $dateInfo;
        }
        return ($dateInfo);
    }

    /**
     * Return Cross Tab Time Internet Explorer
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeInternetExplorer($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        $hour = null;
        while ($hour++ < 23) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                            $hour
                    ) . "%',1,0)) ,0)as `" . $hour . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`ua_family`='IE'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Time FireFox
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeFireFox($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        $hour = null;
        while ($hour++ < 23) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                            $hour
                    ) . "%',1,0)) ,0)as `" . $hour . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`ua_family`='Firefox'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Time Google Chrome
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeChrome($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        $hour = null;
        while ($hour++ < 23) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                            $hour
                    ) . "%',1,0)) ,0)as `" . $hour . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`ua_family`='Chrome'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Time Safari
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeSafari($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        $hour = null;
        while ($hour++ < 23) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                            $hour
                    ) . "%',1,0)) ,0)as `" . $hour . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`ua_family`='Safari'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Time Other Browser
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeOtherBrowser($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        $hour = null;
        while ($hour++ < 23) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                            $hour
                    ) . "%',1,0)) ,0)as `" . $hour . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`ua_family` not in ('IE','Firefox','Chrome','Safari');";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Daily All Browser
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyAllBrowser($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        for ($i = 0; $i < $this->getTotalDayInMonth(); $i++) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($i) . "%',1,0)) ,0)as `" . $i . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		MONTH(`staffWebAccessLogIn`) = '" . $this->getMonth() . "'
        AND     YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Total Day In month
     * @return int
     */
    function getTotalDayInMonth() {
        return $this->totalDayInMonth;
    }

    /**
     * Set Total Day In Month
     * @param int $value
     */
    function setTotalDayInMonth($value) {
        $this->totalDayInMonth = $value;
    }

    /**
     * Return Cross Tab Daily Internet Explorer
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyInternetExplorer($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($i) . "%',1,0)) ,0)as `" . $i . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		MONTH(`staffWebAccessLogIn`) = '" . $this->getMonth() . "'
        AND     YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'
        AND     `ua_family`='IE'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Daily FireFox
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyFireFox($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($i) . "%',1,0)),0)as `" . $i . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		MONTH(`staffWebAccessLogIn`) = '" . $this->getMonth() . "'
        AND     YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'
        AND     `ua_family`='Firefox'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Daily Google Chrome
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyChrome($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($i) . "%',1,0)) ,0)as `" . $i . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		MONTH(`staffWebAccessLogIn`) = '" . $this->getMonth() . "'
        AND     YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'
        AND     `ua_family`='Chrome'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Daily Safari
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailySafari($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($i) . "%',1,0) ) ,0)as `" . $i . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		MONTH(`staffWebAccessLogIn`) = '" . $this->getMonth() . "'
        AND     YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'
        AND     `ua_family`='Safari'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Daily Other Browser
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyOtherBrowser($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($i) . "%',1,0)) ,0)as `" . $i . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		MONTH(`staffWebAccessLogIn`) = '" . $this->getMonth() . "'
        AND     YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'
        AND     `ua_family` not in ('IE','Firefox','Chrome','Safari');";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Weekly All Browser
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyAllBrowser($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $d = new \DateTime(date("Y-m-d", mktime(0, 0, 0, $this->getMonth(), $this->getDay(), $this->getYear())));
        $weekday = $d->format('w');
        $diff = ($weekday == 0 ? 6 : $weekday - 1) - 7; // Monday=0, Sunday=6
        $d->modify("-$diff day");
        $d->format('Y-m-d');

        $sql = "
        SELECT ";
        $strInside = null;
        for ($i = 0; $i < 7; $i++) {

            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn` like '" . $d->modify(
                            '+' . $i . ' day'
                    ) . "%',1,0) ,0)as `" . $i . "`,";
        }

        $sql .= substr($strInside, 0, -1);
        $sql .= "FROM    `staffwebaccess`
		WHERE	`companyId`	=	'" . $this->getCompanyId() . "'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Weekly Internet Explorer
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyInternetExplorer($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $d = new \DateTime(date("Y-m-d", mktime(0, 0, 0, $this->getMonth(), $this->getDay(), $this->getYear())));
        $weekday = $d->format('w');
        $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
        $d->modify("-$diff day");

        $sql = "
        SELECT ";
        $strInside = null;
        for ($i = 0; $i < 8; $i++) {

            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn` like '" . $d->format(
                            'Y-m-d'
                    ) . "%',1,0) ) ,0)as `" . $i . "`,";
            $d->modify('+1 day');
        }

        $sql .= substr($strInside, 0, -1);
        $sql .= "
		FROM    `staffwebaccess`
		WHERE  `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`ua_family`='IE'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Weekly FireFox
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyFireFox($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $d = new \DateTime(date("Y-m-d", mktime(0, 0, 0, $this->getMonth(), $this->getDay(), $this->getYear())));
        $weekday = $d->format('w');
        $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
        $d->modify("-$diff day");
        $d->format('Y-m-d');

        $sql = "
        SELECT ";
        $strInside = null;
        for ($i = 0; $i < 8; $i++) {

            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn` like '" . $d->format(
                            'Y-m-d'
                    ) . "%',1,0) ) ,0)as `" . $i . "`,";
            $d->modify('+1 day');
        }

        $sql .= substr($strInside, 0, -1);
        $sql .= "
		FROM    `staffwebaccess`
        WHERE  `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`ua_family`='Firefox'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Weekly Google Chrome
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyChrome($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $d = new \DateTime(date("Y-m-d", mktime(0, 0, 0, $this->getMonth(), $this->getDay(), $this->getYear())));
        $weekday = $d->format('w');
        $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
        $d->modify("-$diff day");

        $sql = "
        SELECT ";
        $strInside = null;
        for ($i = 0; $i < 8; $i++) {

            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn` like '" . $d->format(
                            'Y-m-d'
                    ) . "%',1,0) ) ,0)as `" . $i . "`,";
            $d->modify('+1 day');
        }

        $sql .= substr($strInside, 0, -1);
        $sql .= "
		FROM    `staffwebaccess`
		WHERE  `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`ua_family`='Chrome'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Weekly Safari
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklySafari($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $d = new \DateTime(date("Y-m-d", mktime(0, 0, 0, $this->getMonth(), $this->getDay(), $this->getYear())));
        $weekday = $d->format('w');
        $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
        $d->modify("-$diff day");

        $sql = "
        SELECT ";
        $strInside = null;
        for ($i = 0; $i < 8; $i++) {

            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn` like '" . $d->format(
                            'Y-m-d'
                    ) . "%',1,0) ) ,0)as `" . $i . "`,";
            $d->modify('+1 day');
        }

        $sql .= substr($strInside, 0, -1);
        $sql .= "
		FROM    `staffwebaccess`
		WHERE  `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`ua_family`='Safari'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Weekly Others Browser
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyOtherBrowser($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $d = new \DateTime(date("Y-m-d", mktime(0, 0, 0, $this->getMonth(), $this->getDay(), $this->getYear())));
        $weekday = $d->format('w');
        $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
        $d->modify("-$diff day");

        $sql = "
        SELECT ";
        $strInside = null;
        for ($i = 0; $i < 8; $i++) {

            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn` like '" . $d->format(
                            'Y-m-d'
                    ) . "%',1,0) ) ,0)as `" . $i . "`,";
            $d->modify('+1 day');
        }

        $sql .= substr($strInside, 0, -1);
        $sql .= "
		FROM    `staffwebaccess`
        WHERE  `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`ua_family` not in ('IE','Firefox','Chrome','Safari');";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Monthly All Browser
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyAllBrowser($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $sql = "
        SELECT  IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 1,1,0)) ,0)as `jan`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 2,1,0)),0)as `feb`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 3,1,0)),0)as `mac`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 4,1,0)),0)as `apr`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 5,1,0)),0)as `may`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 6,1,0)),0)as `jun`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 7,1,0)),0)as `jul`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 8,1,0)),0)as `aug`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 9,1,0)),0)as `sep`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 10,1,0)),0)as `oct`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 11,1,0)),0)as `nov`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 12,1,0)),0)as `dec`
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Monthly Internet Explorer
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyInternetExplorer($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $sql = "
        SELECT  IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 1,1,0)),0)as `jan`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 2,1,0)),0)as `feb`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 3,1,0)),0)as `mac`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 4,1,0)),0)as `apr`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 5,1,0)),0)as `may`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 6,1,0)),0)as `jun`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 7,1,0)),0)as `jul`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 8,1,0)),0)as `aug`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 9,1,0)),0)as `sep`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 10,1,0)) ,0)as oct,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 11,1,0)),0)as `nov`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 12,1,0)),0)as`dec`
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'
        AND     `ua_family`='IE'    ";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Monthly FireFox
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyFireFox($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $sql = "
        SELECT  IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 1,1,0)),0)as `jan`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 2,1,0)),0)as `feb`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 3,1,0)),0)as `mac`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 4,1,0)),0)as `apr`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 5,1,0)),0)as `may`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 6,1,0)),0)as `jun`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 7,1,0)),0)as `jul`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 8,1,0)),0)as `aug`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 9,1,0)),0)as `sep`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 10,1,0)),0)as `oct`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 11,1,0)),0)as `nov`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 12,1,0)),0)as`dec`
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'
        AND     `ua_family`='Firefox'    ";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Monthly Google Chrome
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyChrome($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $sql = "
        SELECT  IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 1,1,0)) ,0)as `jan`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 2,1,0)),0)as `feb`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 3,1,0)),0)as `mac`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 4,1,0)),0)as `apr`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 5,1,0)),0)as `may`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 6,1,0)),0)as `jun`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 7,1,0)),0)as `jul`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 8,1,0)),0)as `aug`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 9,1,0)),0)as `sep`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 10,1,0)),0)as `oct`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 11,1,0)),0)as `nov`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 12,1,0)),0)as `dec`
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'
        AND     `ua_family`='Chrome'    ";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Monthly Safari
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlySafari($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $sql = "
        SELECT  IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 1,1,0)) ,0)as `jan`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 2,1,0)),0)as `feb`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 3,1,0)),0)as `mac`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 4,1,0)),0)as `apr`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 5,1,0)),0)as `may`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 6,1,0)),0)as `jun`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 7,1,0)),0)as`jul`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 8,1,0)),0)as `aug`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 9,1,0)),0)as `sep`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 10,1,0)),0)as `oct`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 11,1,0)),0)as `nov`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 12,1,0)),0)as `dec`
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'
        AND     `ua_family`='Safari'    ";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Monthly Other Browser
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyOtherBrowser($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $sql = "
        SELECT  IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 1,1,0)),0)as `jan`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 2,1,0)),0)as `feb`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 3,1,0)),0)as `mac`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 4,1,0)),0)as `apr`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 5,1,0)),0)as `may`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 6,1,0)),0)as `jun`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 7,1,0)),0)as `jul`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 8,1,0)),0)as `aug`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 9,1,0)),0) as `sep`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 10,1,0)),0) as `oct`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 11,1,0)),0)as `nov`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 12,1,0)),0)as `dec`
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'
        AND     `ua_family` not in ('IE','Firefox','Chrome','Safari')    ";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Yearly All Browser
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabYearlyAllBrowser($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $sql = "
        SELECT  IFNULL(SUM(IF(`ua_family` = 'IE',1,0)),0) as IE,
                IFNULL(SUM(IF(`ua_family`='Firefox',1,0)),0) as FireFox,
                IFNULL(SUM(IF(`ua_family`='Chrome',1,0)),0) as chrome,
                IFNULL(SUM(IF(`ua_family`='Safari',1,0)),0) as safari,
                IFNULL(SUM(IF(`ua_family` not in ('IE','Firefox','Chrome','Safari'),1,0)),0) as others
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		YEAR(`staffWebAccessLogIn`) =  '" . $this->getYear() . "'
        AND     YEAR(`staffWebAccessLogIn`)
        BETWEEN YEAR(DATE_SUB(NOW(), INTERVAL 3 YEAR)) AND YEAR(NOW())";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Date Range All Browser
     * @param string $dateStart
     * @param string $dateEnd
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabRangeAllBrowser($dateStart, $dateEnd) {
        $row = array();
        $dateStartArray = explode("-", $dateStart);

        $dayStart = $dateStartArray[0];
        $monthStart = $dateStartArray[1];
        $yearStart = $dateStartArray[2];

        $dateEndArray = explode("-", $dateEnd);

        $dayEnd = $dateEndArray[0];
        $monthEnd = $dateEndArray[1];
        $yearEnd = $dateEndArray[2];

        $sql = "
        SELECT  IFNULL(SUM(IF(`ua_family` = 'IE',1,0)),0) as `IE`,
                IFNULL(SUM(IF(`ua_family`='Firefox',1,0)),0) as `FireFox`,
                IFNULL(SUM(IF(`ua_family`='Chrome',1,0)),0) as `chrome`,
                IFNULL(SUM(IF(`ua_family`='Safari',1,0)),0) as `safari`,
                IFNULL(SUM(IF(`ua_family` not in ('IE','Firefox','Chrome','Safari'),1,0)),0)  as `others`
        FROM    `staffwebaccess`
        WHERE  `companyId`	=	'" . $this->getCompanyId() . "'
		AND		(`staffWebAccessLogIn` between '" . $yearStart . "-" . $monthStart . "-" . $dayStart . " 00:00:00' and '" . $yearEnd . "-" . $monthEnd . "-" . $dayEnd . " 23:59:59');";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     *
     * @param string $value
     */
    function setWeek($value) {
        $this->week = $value;
    }

    /**
     * Set Week
     * @return string
     */
    function getWeek() {
        return $this->week;
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

/**
 * Class StaffWebAccessCrossTabOperatingSystemService
 * Cross Tab Operating System For Highchart
 * @property mixed week
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Main\StaffWebAccess\Service
 * @subpackage Security
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class StaffWebAccessCrossTabOperatingSystemService extends ConfigClass {

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
     * Day
     * @var int
     */
    private $day;

    /**
     * Month
     * @var int
     */
    private $month;

    /**
     * Year
     * @var int
     */
    private $year;

    /**
     * Total Day In Month
     * @var int
     */
    private $totalDayInMonth;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
    }

    /**
     * Return Cross Tab All Operating System
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeAllOperatingSystem($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        for ($i = 0; $i < $this->getTotalDayInMonth(); $i++) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($i) . "%',1,0)),0) as total,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
		WHERE	`companyId`	=	'" . $this->getCompanyId() . "'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Get Month
     * @return int
     */
    function getMonth() {
        return $this->month;
    }

    /**
     * Set Month
     * @param int $value
     */
    function setMonth($value) {
        $this->month = $value;
    }

    /**
     * Return Day
     * @return int
     */
    function getDay() {
        return $this->day;
    }

    /**
     * Set Day
     * @param int $value
     */
    function setDay($value) {
        $this->day = $value;
    }

    /**
     * Return Year
     * @return int
     */
    function getYear() {
        return $this->year;
    }

    /**
     * Set Year
     * @param int $value
     */
    function setYear($value) {
        $this->year = $value;
    }

    /**
     * Total Day In month
     * @return int
     */
    function getTotalDayInMonth() {
        return $this->totalDayInMonth;
    }

    /**
     * Set Total Day In Month
     * @param int $value
     */
    function setTotalDayInMonth($value) {
        $this->totalDayInMonth = $value;
    }

    /**
     *
     * @param string $dateInfo
     * @return string
     */
    function changeZero($dateInfo) {
        if (strlen($dateInfo) == 1) {
            $dateInfo = '0' . $dateInfo;
        }
        return ($dateInfo);
    }

    /**
     * Return Cross Tabb Time Microsoft Windows Operating System including mobile
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeWindows($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        $hour = 0;
        while ($hour++ < 23) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                            $hour
                    ) . "%',1,0)),0) as `" . $hour . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE  `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family`='Windows'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Linux Operating System
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeLinux($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        $hour = 0;
        while ($hour++ < 23) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                            $hour
                    ) . "%',1,0)),0) as `" . $hour . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family`='Linux'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Apple Mac Os Operating System.. Including 8.x,9.x Os X.. excluding iOs
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeMac($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        $hour = 0;
        while ($hour++ < 23) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                            $hour
                    ) . "%',1,0)),0) as `" . $hour . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family` in ('Mac OS X','Mac OS');";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Android Operating System(mobile)
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeAndroid($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        $hour = 0;
        while ($hour++ < 23) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                            $hour
                    ) . "%',1,0)),0) as `" . $hour . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family`='Android'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return iOs Operating System(mobile)
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeiOS($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        $hour = 0;
        while ($hour++ < 23) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                            $hour
                    ) . "%',1,0)),0) as `" . $hour . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family`='iOS'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Time Other Operating System
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabTimeOtherOperatingSystem($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        $hour = 0;
        while ($hour++ < 23) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($this->getDay()) . " " . $this->changeZero(
                            $hour
                    ) . "%',1,0)),0) as `" . $hour . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family` not in  ('Windows','Linux','Mac OS X','Mac OS','Android','iOS');";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab All Operating System
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyAllOperatingSystem($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($i) . "%',1,0)),0) as `" . $i . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		MONTH(`staffWebAccessLogIn`) = '" . $this->getMonth() . "'
        AND     YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tabb Daily Microsoft Windows Operating System including mobile
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyWindows($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($i) . "%',1,0) ),0) as `" . $i . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		MONTH(`staffWebAccessLogIn`) = '" . $this->getMonth() . "'
        AND     YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'
        AND     `os_family`='Windows'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Linux Operating System
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyLinux($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($i) . "%',1,0) ),0) as `" . $i . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		MONTH(`staffWebAccessLogIn`) = '" . $this->getMonth() . "'
        AND     YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'
        AND     `os_family`='Linux'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Apple Mac Os Operating System.. Including 8.x,9.x Os X.. excluding iOs
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyMac($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($i) . "%',1,0) ),0) as `" . $i . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		MONTH(`staffWebAccessLogIn`) = '" . $this->getMonth() . "'
        AND     YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'
        AND     `os_family` in ('Mac OS X','Mac OS');";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Android Operating System(mobile)
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyAndroid($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($i) . "%',1,0) ),0) as `" . $i . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		MONTH(`staffWebAccessLogIn`) = '" . $this->getMonth() . "'
        AND     YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'
        AND     `os_family`='Android'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return iOs Operating System(mobile)
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyiOS($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($i) . "%',1,0) ),0) as `" . $i . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		MONTH(`staffWebAccessLogIn`) = '" . $this->getMonth() . "'
        AND     YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'
        AND     `os_family`='iOS'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Daily Other Operating System
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabDailyOtherOperatingSystem($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $sql = "
        SELECT  ";
        $strInside = null;
        for ($i = 1; $i <= $this->getTotalDayInMonth(); $i++) {
            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn`  like '" . $this->getYear() . "-" . $this->changeZero(
                            $this->getMonth()
                    ) . "-" . $this->changeZero($i) . "%',1,0) ),0) as `" . $i . "`,";
        }
        $sql .= substr($strInside, 0, -1);
        $sql .= "
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		MONTH(`staffWebAccessLogIn`) = '" . $this->getMonth() . "'
        AND     YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'
        AND     `os_family` not in  ('Windows','Linux','Mac OS X','Mac OS','Android','iOS');";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Weekly All Operating System
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyAllOperatingSystem($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $d = new \DateTime(date("Y-m-d", mktime(0, 0, 0, $this->getMonth(), $this->getDay(), $this->getYear())));
        $weekday = $d->format('w');
        $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
        $d->modify("-$diff day");

        $sql = "
        SELECT ";
        $strInside = null;
        for ($i = 0; $i < 8; $i++) {

            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn` like '" . $d->format(
                            'Y-m-d'
                    ) . "%',1,0) ),0) AS `" . $i . "`,";
            $d->modify('+1 day');
        }

        $sql .= substr($strInside, 0, -1);
        $sql .= "FROM    `staffwebaccess` ";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Weekly Microsoft Operating System
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyWindows($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $d = new \DateTime(date("Y-m-d", mktime(0, 0, 0, $this->getMonth(), $this->getDay(), $this->getYear())));
        $weekday = $d->format('w');
        $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
        $d->modify("-$diff day");

        $sql = "
        SELECT ";
        $strInside = null;
        for ($i = 0; $i < 8; $i++) {

            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn` like '" . $d->format(
                            'Y-m-d'
                    ) . "%',1,0) ),0) AS `" . $i . "`,";
            $d->modify('+1 day');
        }

        $sql .= substr($strInside, 0, -1);
        $sql .= "FROM    `staffwebaccess`
               WHERE  `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family`='Windows'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Weekly Linux Operating System
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyLinux($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $d = new \DateTime(date("Y-m-d", mktime(0, 0, 0, $this->getMonth(), $this->getDay(), $this->getYear())));
        $weekday = $d->format('w');
        $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
        $d->modify("-$diff day");

        $sql = "
        SELECT ";
        $strInside = null;
        for ($i = 0; $i < 8; $i++) {

            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn` like '" . $d->format(
                            'Y-m-d'
                    ) . "%',1,0) ),0) AS `" . $i . "`,";
            $d->modify('+1 day');
        }

        $sql .= substr($strInside, 0, -1);
        $sql .= "FROM    `staffwebaccess`
               WHERE  `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family`='Linux'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Weekly Apple Operating System including  8.x 9.x  x.x
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyMac($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $d = new \DateTime(date("Y-m-d", mktime(0, 0, 0, $this->getMonth(), $this->getDay(), $this->getYear())));
        $weekday = $d->format('w');
        $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
        $d->modify("-$diff day");

        $sql = "
        SELECT ";
        $strInside = null;
        for ($i = 0; $i < 8; $i++) {

            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn` like '" . $d->format(
                            'Y-m-d'
                    ) . "%',1,0) ),0) AS `" . $i . "`,";
            $d->modify('+1 day');
        }

        $sql .= substr($strInside, 0, -1);
        $sql .= "FROM    `staffwebaccess`
               WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family` in ('Mac OS X','Mac OS');";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Weekly Android
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyAndroid($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $d = new \DateTime(date("Y-m-d", mktime(0, 0, 0, $this->getMonth(), $this->getDay(), $this->getYear())));
        $weekday = $d->format('w');
        $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
        $d->modify("-$diff day");

        $sql = "
        SELECT ";
        $strInside = null;
        for ($i = 0; $i < 8; $i++) {

            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn` like '" . $d->format(
                            'Y-m-d'
                    ) . "%',1,0) ),0) AS `" . $i . "`,";
            $d->modify('+1 day');
        }

        $sql .= substr($strInside, 0, -1);
        $sql .= "FROM    `staffwebaccess`
               WHERE  `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family`='Android'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Weekly iOS
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyiOS($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $d = new \DateTime(date("Y-m-d", mktime(0, 0, 0, $this->getMonth(), $this->getDay(), $this->getYear())));
        $weekday = $d->format('w');
        $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
        $d->modify("-$diff day");

        $sql = "
        SELECT ";
        $strInside = null;
        for ($i = 0; $i < 8; $i++) {

            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn` like '" . $d->format(
                            'Y-m-d'
                    ) . "%',1,0) ),0) AS `" . $i . "`,";
            $d->modify('+1 day');
        }

        $sql .= substr($strInside, 0, -1);
        $sql .= "FROM    `staffwebaccess`
               WHERE  `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family`='Android'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Weekly Others Operating System
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabWeeklyOtherOperatingSystem($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(
                date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );

        $d = new \DateTime(date("Y-m-d", mktime(0, 0, 0, $this->getMonth(), $this->getDay(), $this->getYear())));
        $weekday = $d->format('w');
        $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
        $d->modify("-$diff day");

        $sql = "
        SELECT ";
        $strInside = null;
        for ($i = 0; $i < 8; $i++) {

            $strInside .= "IFNULL(SUM(IF(`staffWebAccessLogIn` like '" . $d->format(
                            'Y-m-d'
                    ) . "%',1,0) ),0) AS `" . $i . "`,";
            $d->modify('+1 day');
        }

        $sql .= substr($strInside, 0, -1);
        $sql .= "FROM    `staffwebaccess`
               WHERE  `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family` not in  ('Windows','Linux','Mac OS X','Mac OS','Android','iOS');";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Monthly All Operating System
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyAllOperatingSystem($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $sql = "
        SELECT  IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 1,1,0)),0) as `jan`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 2,1,0)),0) as `feb`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 3,1,0)),0) as `mac`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 4,1,0)),0) as `apr`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 5,1,0)),0) as `may`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 6,1,0)),0) as `jun`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 7,1,0)),0) as `jul`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 8,1,0)),0) as `aug`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 9,1,0)),0) as `sep`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 10,1,0)),0) as `oct`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 11,1,0)),0) as `nov`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 12,1,0)),0) as `dec`
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Monthly Microsoft Operating System including mobile
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyWindows($date) {
        $row = array();
        $dateArray = explode("-", $date);
        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $sql = "
        SELECT  IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 1,1,0)),0)  as `jan`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 2,1,0)),0) as `feb`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 3,1,0)),0) as `mac`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 4,1,0)),0) as `apr`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 5,1,0)),0) as `may`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 6,1,0)),0) as `jun`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 7,1,0)),0) as `jul`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 8,1,0)),0) as `aug`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 9,1,0)),0) as `sep`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 10,1,0)),0) as `oct`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 11,1,0)),0) as `nov`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 12,1,0)),0) as `dec`
        FROM    `staffwebaccess`
        WHERE   YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'
        AND     `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family`='Windows'    ";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Monthly Linux
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyLinux($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $sql = "
        SELECT  IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 1,1,0)),0)  as `jan`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 2,1,0)),0) as `feb`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 3,1,0)),0) as `mac`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 4,1,0)),0) as `apr`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 5,1,0)),0) as `may`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 6,1,0)),0) as `jun`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 7,1,0)),0) as `jul`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 8,1,0)),0) as `aug`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 9,1,0)),0) as `sep`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 10,1,0)),0) as `oct`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 11,1,0)),0) as `nov`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 12,1,0)),0) as `dec`
        FROM    `staffwebaccess`
        WHERE   YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'
        AND     `companyId`	=	'" . $this->getCompanyId() . "'
		AND		`os_family`='Linux'    ";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Monthly Apple Operating System  .including 8.x,9.x x.x
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyMac($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $sql = "
        SELECT  IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 1,1,0)),0)  as `jan`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 2,1,0)),0) as `feb`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 3,1,0)),0) as `mac`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 4,1,0)),0) as `apr`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 5,1,0)),0) as `may`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 6,1,0)),0) as `jun`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 7,1,0)),0) as `jul`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 8,1,0)),0) as `aug`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 9,1,0)),0) as `sep`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 10,1,0)),0) as `oct`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 11,1,0)),0) as `nov`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 12,1,0)),0) as `dec`
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'
        AND     `os_family` not in  ('Windows','Linux','Mac OS X','Mac OS')    ";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Monthly Android
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyAndroid($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $sql = "
        SELECT  IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 1,1,0)),0)  as `jan`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 2,1,0)),0) as `feb`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 3,1,0)),0) as `mac`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 4,1,0)),0) as `apr`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 5,1,0)),0) as `may`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 6,1,0)),0) as `jun`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 7,1,0)),0) as `jul`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 8,1,0)),0) as `aug`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 9,1,0)),0) as `sep`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 10,1,0)),0) as `oct`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 11,1,0)),0) as `nov`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 12,1,0)),0) as `dec`
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'
        AND     `os_family`='Android'    ";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Monthly iOS
     * @param string $date
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyiOS($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $sql = "
        SELECT  IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 1,1,0)),0)  as `jan`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 2,1,0)),0) as `feb`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 3,1,0)),0) as `mac`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 4,1,0)),0) as `apr`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 5,1,0)),0) as `may`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 6,1,0)),0) as `jun`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 7,1,0)),0) as `jul`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 8,1,0)),0) as `aug`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 9,1,0)),0) as `sep`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 10,1,0)),0) as `oct`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 11,1,0)),0) as `nov`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 12,1,0)),0) as `dec`
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'
        AND     `os_family`='iOS'    ";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Monthly Others Operating System
     * @param string $date
     * @return array|mixed
     * @throws \Exception
     */
    function getCrossTabMonthlyOtherOperatingSystem($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $sql = "
        SELECT  IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 1,1,0)),0)  as `jan`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 2,1,0)),0) as `feb`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 3,1,0)),0) as `mac`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 4,1,0)),0) as `apr`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 5,1,0)),0) as `may`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 6,1,0)),0) as `jun`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 7,1,0)),0) as `jul`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 8,1,0)),0) as `aug`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 9,1,0)),0) as `sep`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 10,1,0)),0) as `oct`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 11,1,0)),0) as `nov`,
                IFNULL(SUM(IF(month(`staffWebAccessLogIn`) = 12,1,0)),0) as `dec`
        FROM    `staffwebaccess`
        WHERE  `companyId`	=	'" . $this->getCompanyId() . "'
		AND		 YEAR(`staffWebAccessLogIn`) = '" . $this->getYear() . "'
        AND     `os_family` not in  ('Windows','Linux','Mac OS X','Mac OS','Android','iOS')    ";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Yearly All Operating System
     * @param string $date
     * @return array|mixed
     * @throws \Exception
     */
    function getCrossTabYearlyAllOperatingSystem($date) {
        $row = array();
        $dateArray = explode("-", $date);

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $sql = "
        SELECT  IFNULL(SUM(IF(`os_family` = 'Windows',1,0)),0) as Windows,
                IFNULL(SUM(IF(`os_family`='Linux',1,0)),0) as Linux,
                IFNULL(SUM(IF(`os_family` in ('Mac OS X','Mac OS'),1,0)),0) as Mac,
                IFNULL(SUM(IF(`os_family`='Android',1,0)),0) as Android,
                IFNULL(SUM(IF(`os_family`='iOS',1,0)),0) as iOS,
                IFNULL(SUM(IF(`os_family` not in  ('Windows','Linux','Mac OS X','Mac OS','Android','iOS'),1,0)) as others
        FROM    `staffwebaccess`
        WHERE  `companyId`	=	'" . $this->getCompanyId() . "'
		AND		YEAR(`staffWebAccessLogIn`) =  '" . $this->getYear() . "'
        AND     YEAR(`staffWebAccessLogIn`)
        BETWEEN YEAR(DATE_SUB(NOW(), INTERVAL 3 YEAR)) AND YEAR(NOW())";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     * Return Cross Tab Date Range All Operating System
     * @param string $dateStart
     * @param string $dateEnd
     * @return mixed
     * @throws \Exception
     */
    function getCrossTabRangeAllOperatingSystem($dateStart, $dateEnd) {
        $row = array();
        $dateStartArray = explode("-", $dateStart);

        $dayStart = $dateStartArray[0];
        $monthStart = $dateStartArray[1];
        $yearStart = $dateStartArray[2];

        $dateEndArray = explode("-", $dateEnd);

        $dayEnd = $dateEndArray[0];
        $monthEnd = $dateEndArray[1];
        $yearEnd = $dateEndArray[2];

        $sql = "
        SELECT  IFNULL(SUM(IF(`os_family` = 'Windows',1,0)),0) as `Windows`,
                IFNULL(SUM(IF(`os_family`='Linux',1,0)),0) as `Linux`,
                IFNULL(SUM(IF(`os_family` in ('Mac OS X','Mac OS'),1,0)),0) as `Mac`,
                IFNULL(SUM(IF(`os_family`='Android',1,0)),0) as `Android`,
                IFNULL(SUM(IF(`os_family`='iOS',1,0)),0) as `iOS`,
                IFNULL(SUM(IF(`os_family` not in  ('Windows','Linux','Mac OS X','Mac OS','Android','iOS'),1,0)),0) as `others`
        FROM    `staffwebaccess`
        WHERE   `companyId`	=	'" . $this->getCompanyId() . "'
		AND		(`staffWebAccessLogIn` between '" . $yearStart . "-" . $monthStart . "-" . $dayStart . " 00:00:00' and '" . $yearEnd . "-" . $monthEnd . "-" . $dayEnd . " 23:59:59');";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
            }
        }
        return $row;
    }

    /**
     *
     * @param string $value
     */
    function setWeek($value) {
        $this->week = $value;
    }

    /**
     * Set Week
     * @return string
     */
    function getWeek() {
        return $this->week;
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