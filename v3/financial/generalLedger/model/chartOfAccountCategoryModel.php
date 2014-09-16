<?php

namespace Core\Financial\GeneralLedger\ChartOfAccountCategory\Model;

use Core\Validation\ValidationClass;

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
require_once($newFakeDocumentRoot . "library/class/classValidation.php");

/**
 * Class ChartOfAccountCategory
 * This is chartOfAccountCategory model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\GeneralLedger\ChartOfAccountCategory\Model;
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ChartOfAccountCategoryModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $chartOfAccountCategoryId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Chart Of Account Report   Type
     * @var int
     */
    private $chartOfAccountReportTypeId;

    /**
     * Code
     * @var string
     */
    private $chartOfAccountCategoryCode;

    /**
     * Title
     * @var string
     */
    private $chartOfAccountCategoryTitle;

    /**
     * Description
     * @var string
     */
    private $chartOfAccountCategoryDescription;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('chartOfAccountCategory');
        $this->setPrimaryKeyName('chartOfAccountCategoryId');
        $this->setMasterForeignKeyName('chartOfAccountCategoryId');
        $this->setFilterCharacter('chartOfAccountCategoryDescription');
        //$this->setFilterCharacter('chartOfAccountCategoryNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['chartOfAccountCategoryId'])) {
            $this->setChartOfAccountCategoryId($this->strict($_POST ['chartOfAccountCategoryId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['chartOfAccountReportTypeId'])) {
            $this->setChartOfAccountReportTypeId($this->strict($_POST ['chartOfAccountReportTypeId'], 'int'));
        }
        if (isset($_POST ['chartOfAccountCategoryCode'])) {
            $this->setChartOfAccountCategoryCode($this->strict($_POST ['chartOfAccountCategoryCode'], 'string'));
        }
        if (isset($_POST ['chartOfAccountCategoryTitle'])) {
            $this->setChartOfAccountCategoryTitle($this->strict($_POST ['chartOfAccountCategoryTitle'], 'string'));
        }
        if (isset($_POST ['chartOfAccountCategoryDescription'])) {
            $this->setChartOfAccountCategoryDescription(
                    $this->strict($_POST ['chartOfAccountCategoryDescription'], 'string')
            );
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['chartOfAccountCategoryId'])) {
            $this->setChartOfAccountCategoryId($this->strict($_GET ['chartOfAccountCategoryId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['chartOfAccountReportTypeId'])) {
            $this->setChartOfAccountReportTypeId($this->strict($_GET ['chartOfAccountReportTypeId'], 'int'));
        }
        if (isset($_GET ['chartOfAccountCategoryCode'])) {
            $this->setChartOfAccountCategoryCode($this->strict($_GET ['chartOfAccountCategoryCode'], 'string'));
        }
        if (isset($_GET ['chartOfAccountCategoryTitle'])) {
            $this->setChartOfAccountCategoryTitle($this->strict($_GET ['chartOfAccountCategoryTitle'], 'string'));
        }
        if (isset($_GET ['chartOfAccountCategoryDescription'])) {
            $this->setChartOfAccountCategoryDescription(
                    $this->strict($_GET ['chartOfAccountCategoryDescription'], 'string')
            );
        }
        if (isset($_GET ['chartOfAccountCategoryId'])) {
            $this->setTotal(count($_GET ['chartOfAccountCategoryId']));
            if (is_array($_GET ['chartOfAccountCategoryId'])) {
                $this->chartOfAccountCategoryId = array();
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
            if (isset($_GET ['chartOfAccountCategoryId'])) {
                $this->setChartOfAccountCategoryId(
                        $this->strict($_GET ['chartOfAccountCategoryId'] [$i], 'numeric'), $i, 'array'
                );
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
                }
                if ($_GET ['isUpdate'] [$i] == 'false') {
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
            $primaryKeyAll .= $this->getChartOfAccountCategoryId($i, 'array') . ",";
        }
        $this->setPrimaryKeyAll((substr($primaryKeyAll, 0, -1)));
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
     * Return Primary Key Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getChartOfAccountCategoryId($key, $type) {
        if ($type == 'single') {
            return $this->chartOfAccountCategoryId;
        } else if ($type == 'array') {
            return $this->chartOfAccountCategoryId [$key];
        } else {
            echo json_encode(
                    array(
                        "success" => false,
                        "message" => "Cannot Identify Type String Or Array:getchartOfAccountCategoryId ?"
                    )
            );
            exit();
        }
    }

    /**
     * Set Primary Key Value
     * @param int|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return \Core\Financial\GeneralLedger\ChartOfAccountCategory\Model\ChartOfAccountCategoryModel
     */
    public function setChartOfAccountCategoryId($value, $key, $type) {
        if ($type == 'single') {
            $this->chartOfAccountCategoryId = $value;
            return $this;
        } else if ($type == 'array') {
            $this->chartOfAccountCategoryId[$key] = $value;
            return $this;
        } else {
            echo json_encode(
                    array(
                        "success" => false,
                        "message" => "Cannot Identify Type String Or Array:setchartOfAccountCategoryId?"
                    )
            );
            exit();
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
     * To Return Company
     * @return int $companyId
     */
    public function getCompanyId() {
        return $this->companyId;
    }

    /**
     * To Set Company
     * @param int $companyId Company
     * @return \Core\Financial\GeneralLedger\ChartOfAccountCategory\Model\ChartOfAccountCategoryModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Chart Of Account Report   Type
     * @return int $chartOfAccountReportTypeId
     */
    public function getChartOfAccountReportTypeId() {
        return $this->chartOfAccountReportTypeId;
    }

    /**
     * To Set Chart Of Account Report   Type
     * @param int $chartOfAccountReportTypeId Chart Of Account Report   Type
     * @return \Core\Financial\GeneralLedger\ChartOfAccountCategory\Model\ChartOfAccountCategoryModel
     */
    public function setChartOfAccountReportTypeId($chartOfAccountReportTypeId) {
        $this->chartOfAccountReportTypeId = $chartOfAccountReportTypeId;
        return $this;
    }

    /**
     * To Return Code
     * @return string $chartOfAccountCategoryCode
     */
    public function getChartOfAccountCategoryCode() {
        return $this->chartOfAccountCategoryCode;
    }

    /**
     * To Set Code
     * @param string $chartOfAccountCategoryCode Code
     * @return \Core\Financial\GeneralLedger\ChartOfAccountCategory\Model\ChartOfAccountCategoryModel
     */
    public function setChartOfAccountCategoryCode($chartOfAccountCategoryCode) {
        $this->chartOfAccountCategoryCode = $chartOfAccountCategoryCode;
        return $this;
    }

    /**
     * To Return Title
     * @return string $chartOfAccountCategoryTitle
     */
    public function getChartOfAccountCategoryTitle() {
        return $this->chartOfAccountCategoryTitle;
    }

    /**
     * To Set Title
     * @param string $chartOfAccountCategoryTitle Title
     * @return \Core\Financial\GeneralLedger\ChartOfAccountCategory\Model\ChartOfAccountCategoryModel
     */
    public function setChartOfAccountCategoryTitle($chartOfAccountCategoryTitle) {
        $this->chartOfAccountCategoryTitle = $chartOfAccountCategoryTitle;
        return $this;
    }

    /**
     * To Return Description
     * @return string $chartOfAccountCategoryDescription
     */
    public function getChartOfAccountCategoryDescription() {
        return $this->chartOfAccountCategoryDescription;
    }

    /**
     * To Set Description
     * @param string $chartOfAccountCategoryDescription Description
     * @return \Core\Financial\GeneralLedger\ChartOfAccountCategory\Model\ChartOfAccountCategoryModel
     */
    public function setChartOfAccountCategoryDescription($chartOfAccountCategoryDescription) {
        $this->chartOfAccountCategoryDescription = $chartOfAccountCategoryDescription;
        return $this;
    }

}

?>