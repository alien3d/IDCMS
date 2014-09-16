<?php

namespace Core\Financial\GeneralLedger\FinancePeriodRange\Model;

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
 * Class FinancePeriodRange
 * This is financePeriodRange model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\GeneralLedger\FinancePeriodRange\Model;
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class FinancePeriodRangeModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $financePeriodRangeId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Finance Year
     * @var int
     */
    private $financeYearId;

    /**
     * Period
     * @var int
     */
    private $financePeriodRangePeriod;

    /**
     * Start Date
     * @var string
     */
    private $financePeriodRangeStartDate;

    /**
     * End Date
     * @var string
     */
    private $financePeriodRangeEndDate;

    /**
     * Is Close
     * @var bool
     */
    private $isClose;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('financePeriodRange');
        $this->setPrimaryKeyName('financePeriodRangeId');
        $this->setMasterForeignKeyName('financePeriodRangeId');
        $this->setFilterCharacter('financePeriodRangePeriod');
        //$this->setFilterCharacter('financePeriodRangeNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['financePeriodRangeId'])) {
            $this->setFinancePeriodRangeId($this->strict($_POST ['financePeriodRangeId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['financeYearId'])) {
            $this->setFinanceYearId($this->strict($_POST ['financeYearId'], 'integer'));
        }
        if (isset($_POST ['financePeriodRangePeriod'])) {
            $this->setFinancePeriodRangePeriod($this->strict($_POST ['financePeriodRangePeriod'], 'integer'));
        }
        if (isset($_POST ['financePeriodRangeStartDate'])) {
            $this->setFinancePeriodRangeStartDate($this->strict($_POST ['financePeriodRangeStartDate'], 'date'));
        }
        if (isset($_POST ['financePeriodRangeEndDate'])) {
            $this->setFinancePeriodRangeEndDate($this->strict($_POST ['financePeriodRangeEndDate'], 'date'));
        }
        if (isset($_POST ['isClose'])) {
            $this->setIsClose($this->strict($_POST ['isClose'], 'integer'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['financePeriodRangeId'])) {
            $this->setFinancePeriodRangeId($this->strict($_GET ['financePeriodRangeId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['financeYearId'])) {
            $this->setFinanceYearId($this->strict($_GET ['financeYearId'], 'integer'));
        }
        if (isset($_GET ['financePeriodRangePeriod'])) {
            $this->setFinancePeriodRangePeriod($this->strict($_GET ['financePeriodRangePeriod'], 'integer'));
        }
        if (isset($_GET ['financePeriodRangeStartDate'])) {
            $this->setFinancePeriodRangeStartDate($this->strict($_GET ['financePeriodRangeStartDate'], 'date'));
        }
        if (isset($_GET ['financePeriodRangeEndDate'])) {
            $this->setFinancePeriodRangeEndDate($this->strict($_GET ['financePeriodRangeEndDate'], 'date'));
        }
        if (isset($_GET ['isClose'])) {
            $this->setIsClose($this->strict($_GET ['isClose'], 'integer'));
        }
        if (isset($_GET ['financePeriodRangeId'])) {
            $this->setTotal(count($_GET ['financePeriodRangeId']));
            if (is_array($_GET ['financePeriodRangeId'])) {
                $this->financePeriodRangeId = array();
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
            if (isset($_GET ['financePeriodRangeId'])) {
                $this->setFinancePeriodRangeId(
                        $this->strict($_GET ['financePeriodRangeId'] [$i], 'numeric'), $i, 'array'
                );
            }
            if (isset($_GET ['isDefault'])) {
                if ($_GET ['isDefault'] [$i] == 'true') {
                    $this->setIsDefault(1, $i, 'array');
                } else {
                    if ($_GET ['isDefault'] [$i] == 'false') {
                        $this->setIsDefault(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isNew'])) {
                if ($_GET ['isNew'] [$i] == 'true') {
                    $this->setIsNew(1, $i, 'array');
                } else {
                    if ($_GET ['isNew'] [$i] == 'false') {
                        $this->setIsNew(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isDraft'])) {
                if ($_GET ['isDraft'] [$i] == 'true') {
                    $this->setIsDraft(1, $i, 'array');
                } else {
                    if ($_GET ['isDraft'] [$i] == 'false') {
                        $this->setIsDraft(0, $i, 'array');
                    }
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
                } else {
                    if ($_GET ['isDelete'] [$i] == 'false') {
                        $this->setIsDelete(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isActive'])) {
                if ($_GET ['isActive'] [$i] == 'true') {
                    $this->setIsActive(1, $i, 'array');
                } else {
                    if ($_GET ['isActive'] [$i] == 'false') {
                        $this->setIsActive(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isApproved'])) {
                if ($_GET ['isApproved'] [$i] == 'true') {
                    $this->setIsApproved(1, $i, 'array');
                } else {
                    if ($_GET ['isApproved'] [$i] == 'false') {
                        $this->setIsApproved(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isReview'])) {
                if ($_GET ['isReview'] [$i] == 'true') {
                    $this->setIsReview(1, $i, 'array');
                } else {
                    if ($_GET ['isReview'] [$i] == 'false') {
                        $this->setIsReview(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isPost'])) {
                if ($_GET ['isPost'] [$i] == 'true') {
                    $this->setIsPost(1, $i, 'array');
                } else {
                    if ($_GET ['isPost'] [$i] == 'false') {
                        $this->setIsPost(0, $i, 'array');
                    }
                }
            }
            $primaryKeyAll .= $this->getFinancePeriodRangeId($i, 'array') . ",";
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
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $this->setExecuteTime("'" . date("Y-m-d H:i:s.u") . "'");
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $this->setExecuteTime("to_date('" . date("Y-m-d H:i:s") . "','YYYY-MM-DD HH24:MI:SS');");
                }
            }
        }
    }

    /**
     * Return Primary Key Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getFinancePeriodRangeId($key, $type) {
        if ($type == 'single') {
            return $this->financePeriodRangeId;
        } else {
            if ($type == 'array') {
                return $this->financePeriodRangeId [$key];
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:getFinancePeriodRangeId ?"
                        )
                );
                exit();
            }
        }
    }

    /**
     * Set Primary Key Value
     * @param int|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return \Core\Financial\GeneralLedger\FinancePeriodRange\Model\FinancePeriodRangeModel
     */
    public function setFinancePeriodRangeId($value, $key, $type) {
        if ($type == 'single') {
            $this->financePeriodRangeId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->financePeriodRangeId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:setFinancePeriodRangeId?"
                        )
                );
                exit();
            }
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
     * @return \Core\Financial\GeneralLedger\FinancePeriodRange\Model\FinancePeriodRangeModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Finance Year
     * @return int $financeYearId
     */
    public function getFinanceYearId() {
        return $this->financeYearId;
    }

    /**
     * To Set Finance Year
     * @param int $financeYearId Finance Year
     * @return \Core\Financial\GeneralLedger\FinancePeriodRange\Model\FinancePeriodRangeModel
     */
    public function setFinanceYearId($financeYearId) {
        $this->financeYearId = $financeYearId;
        return $this;
    }

    /**
     * To Return Period
     * @return int $financePeriodRangePeriod
     */
    public function getFinancePeriodRangePeriod() {
        return $this->financePeriodRangePeriod;
    }

    /**
     * To Set Period
     * @param int $financePeriodRangePeriod Period
     * @return \Core\Financial\GeneralLedger\FinancePeriodRange\Model\FinancePeriodRangeModel
     */
    public function setFinancePeriodRangePeriod($financePeriodRangePeriod) {
        $this->financePeriodRangePeriod = $financePeriodRangePeriod;
        return $this;
    }

    /**
     * To Return Start Date
     * @return string $financePeriodRangeStartDate
     */
    public function getFinancePeriodRangeStartDate() {
        return $this->financePeriodRangeStartDate;
    }

    /**
     * To Set Start Date
     * @param string $financePeriodRangeStartDate Start Date
     * @return \Core\Financial\GeneralLedger\FinancePeriodRange\Model\FinancePeriodRangeModel
     */
    public function setFinancePeriodRangeStartDate($financePeriodRangeStartDate) {
        $this->financePeriodRangeStartDate = $financePeriodRangeStartDate;
        return $this;
    }

    /**
     * To Return End Date
     * @return string $financePeriodRangeEndDate
     */
    public function getFinancePeriodRangeEndDate() {
        return $this->financePeriodRangeEndDate;
    }

    /**
     * To Set End Date
     * @param string $financePeriodRangeEndDate End Date
     * @return \Core\Financial\GeneralLedger\FinancePeriodRange\Model\FinancePeriodRangeModel
     */
    public function setFinancePeriodRangeEndDate($financePeriodRangeEndDate) {
        $this->financePeriodRangeEndDate = $financePeriodRangeEndDate;
        return $this;
    }

    /**
     * To Return Is Close
     * @return bool $isClose
     */
    public function getIsClose() {
        return $this->isClose;
    }

    /**
     * To Set Is Close
     * @param bool $isClose Is Close
     * @return \Core\Financial\GeneralLedger\FinancePeriodRange\Model\FinancePeriodRangeModel
     */
    public function setIsClose($isClose) {
        $this->isClose = $isClose;
        return $this;
    }

}

?>