<?php

namespace Core\Financial\GeneralLedger\Budget\Model;

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
 * Class Budget
 * This is budget model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\GeneralLedger\Budget\Model;
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class BudgetModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $budgetId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Chart Of Account Category
     * @var int
     */
    private $chartOfAccountCategoryId;

    /**
     * Chart Of Account Type
     * @var int
     */
    private $chartOfAccountTypeId;

    /**
     * Chart Of Account Level / Deep / Segment
     * @var int
     */
    private $chartOfAccountLevel;

    /**
     * Chart Of Account
     * @var int
     */
    private $chartOfAccountId;

    /**
     * Finance Year
     * @var int
     */
    private $financeYearId;

    /**
     * Finance Period Range
     * @var int
     */
    private $financePeriodRangeId;

    /**
     * Target One
     * @var double
     */
    private $budgetTargetMonthOne;

    /**
     * Actual One (1)
     * @var double
     */
    private $budgetActualMonthOne;

    /**
     * Target Two (2)
     * @var double
     */
    private $budgetTargetMonthTwo;

    /**
     * Actual Two (2)
     * @var double
     */
    private $budgetActualMonthTwo;

    /**
     * Target Three (3) (III)
     * @var double
     */
    private $budgetTargetMonthThree;

    /**
     * Actual Three (3) (III)
     * @var double
     */
    private $budgetActualMonthThree;

    /**
     * Target Fourth (4) (IV)
     * @var double
     */
    private $budgetTargetMonthFourth;

    /**
     * Actual Fourth (4) (IV)
     * @var double
     */
    private $budgetActualMonthFourth;

    /**
     * Target Fifth (5) (V)
     * @var double
     */
    private $budgetTargetMonthFifth;

    /**
     * Actual Fifth (5) (V)
     * @var double
     */
    private $budgetActualMonthFifth;

    /**
     * Target Six (6) (VI)
     * @var double
     */
    private $budgetTargetMonthSix;

    /**
     * Actual Six (6) (VI)
     * @var double
     */
    private $budgetActualMonthSix;

    /**
     * Actual Seven (7) (VII)
     * @var double
     */
    private $budgetActualMonthSeven;

    /**
     * Target Seven (7) (VII)
     * @var double
     */
    private $budgetTargetMonthSeven;

    /**
     * Actual Eight (8) (VIII)
     * @var double
     */
    private $budgetActualMonthEight;

    /**
     * Target Eight (8) (VIII)
     * @var double
     */
    private $budgetTargetMonthEight;

    /**
     * Actual Nine (9) (IX)
     * @var double
     */
    private $budgetActualMonthNine;

    /**
     * Target Nine (9) (IX)
     * @var double
     */
    private $budgetTargetMonthNine;

    /**
     * Target Ten (10) X
     * @var double
     */
    private $budgetTargetMonthTen;

    /**
     * Actual Ten (10) X
     * @var double
     */
    private $budgetActualMonthTen;

    /**
     * Target Eleven (11) (XI)
     * @var double
     */
    private $budgetTargetMonthEleven;

    /**
     * Actual Eleven (11) (XI)
     * @var double
     */
    private $budgetActualMonthEleven;

    /**
     * Target Twelve (12) (XII)
     * @var double
     */
    private $budgetTargetMonthTwelve;

    /**
     * Actual Twelve (12) (XII)
     * @var double
     */
    private $budgetActualMonthTwelve;

    /**
     * Target Month Third Teen (13) (XIII)
     * @var double
     */
    private $budgetTargetMonthThirteen;

    /**
     * Actual Month Third Teen (13) (XIII)
     * @var double
     */
    private $budgetActualMonthThirteen;

    /**
     * Target Month Fourth Teen (14) (XIV)
     * @var double
     */
    private $budgetTargetMonthFourteen;

    /**
     * Actual Month Fourth Teen (14) (XIV)
     * @var double
     */
    private $budgetActualMonthFourteen;

    /**
     * Target Fifteen (15) (XV)
     * @var double
     */
    private $budgetTargetMonthFifteen;

    /**
     * Actual Fifteen (15) (XV)
     * @var double
     */
    private $budgetActualMonthFifteen;

    /**
     * Buget Sixteen (16)(XVI)
     * @var double
     */
    private $budgetTargetMonthSixteen;

    /**
     * Actual Sixteen (16)(XVI)
     * @var double
     */
    private $budgetActualMonthSixteen;

    /**
     * Target Seventeen (17) (XVII)
     * @var double
     */
    private $budgetTargetMonthSeventeen;

    /**
     * Actual Seventeen (17) (XVII)
     * @var double
     */
    private $budgetActualMonthSeventeen;

    /**
     * Target Eighteen (18) (XVIII)
     * @var double
     */
    private $budgetTargetMonthEighteen;

    /**
     * Actual Eighteen (18) (XVIII)
     * @var double
     */
    private $budgetActualMonthEighteen;

    /**
     * Target Year
     * @var double
     */
    private $budgetTargetTotalYear;

    /**
     * Actual Year
     * @var double
     */
    private $budgetActualTotalYear;

    /**
     * Version
     * @var string
     */
    private $budgetVersion;

    /**
     * Is Lock
     * @var bool
     */
    private $isLock;

    /**
     * Budget Field Name
     * @var string
     */
    private $budgetFieldName;

    /**
     * Budget Field Value
     * @var float
     */
    private $budgetFieldValue;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('budget');
        $this->setPrimaryKeyName('budgetId');
        $this->setMasterForeignKeyName('budgetId');
        $this->setFilterCharacter('budgetDescription');
        //$this->setFilterCharacter('budgetNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['budgetId'])) {
            $this->setBudgetId($this->strict($_POST ['budgetId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['chartOfAccountId'])) {
            $this->setChartOfAccountId($this->strict($_POST ['chartOfAccountId'], 'integer'));
        }
        if (isset($_POST ['chartOfAccountCategoryId'])) {
            $this->setChartOfAccountCategoryId($this->strict($_POST ['chartOfAccountCategoryId'], 'integer'));
        }
        if (isset($_POST ['chartOfAccountTypeId'])) {
            $this->setChartOfAccountTypeId($this->strict($_POST ['chartOfAccountTypeId'], 'integer'));
        }
        if (isset($_POST ['chartOfAccountLevel'])) {
            $this->setChartOfAccountLevel($this->strict($_POST ['chartOfAccountLevel'], 'integer'));
        }
        if (isset($_POST ['financeYearId'])) {
            $this->setFinanceYearId($this->strict($_POST ['financeYearId'], 'integer'));
        }
        if (isset($_POST ['financePeriodRangeId'])) {
            $this->setFinancePeriodRangeId($this->strict($_POST ['financePeriodRangeId'], 'integer'));
        }
        if (isset($_POST ['budgetTargetMonthOne'])) {
            $this->setBudgetTargetMonthOne($this->strict($_POST ['budgetTargetMonthOne'], 'double'));
        }
        if (isset($_POST ['budgetActualMonthOne'])) {
            $this->setBudgetActualMonthOne($this->strict($_POST ['budgetActualMonthOne'], 'double'));
        }
        if (isset($_POST ['budgetTargetMonthTwo'])) {
            $this->setBudgetTargetMonthTwo($this->strict($_POST ['budgetTargetMonthTwo'], 'double'));
        }
        if (isset($_POST ['budgetActualMonthTwo'])) {
            $this->setBudgetActualMonthTwo($this->strict($_POST ['budgetActualMonthTwo'], 'double'));
        }
        if (isset($_POST ['budgetTargetMonthThree'])) {
            $this->setBudgetTargetMonthThree($this->strict($_POST ['budgetTargetMonthThree'], 'double'));
        }
        if (isset($_POST ['budgetActualMonthThree'])) {
            $this->setBudgetActualMonthThree($this->strict($_POST ['budgetActualMonthThree'], 'double'));
        }
        if (isset($_POST ['budgetTargetMonthFourth'])) {
            $this->setBudgetTargetMonthFourth($this->strict($_POST ['budgetTargetMonthFourth'], 'double'));
        }
        if (isset($_POST ['budgetActualMonthFourth'])) {
            $this->setBudgetActualMonthFourth($this->strict($_POST ['budgetActualMonthFourth'], 'double'));
        }
        if (isset($_POST ['budgetTargetMonthFifth'])) {
            $this->setBudgetTargetMonthFifth($this->strict($_POST ['budgetTargetMonthFifth'], 'double'));
        }
        if (isset($_POST ['budgetActualMonthFifth'])) {
            $this->setBudgetActualMonthFifth($this->strict($_POST ['budgetActualMonthFifth'], 'double'));
        }
        if (isset($_POST ['budgetTargetMonthSix'])) {
            $this->setBudgetTargetMonthSix($this->strict($_POST ['budgetTargetMonthSix'], 'double'));
        }
        if (isset($_POST ['budgetActualMonthSix'])) {
            $this->setBudgetActualMonthSix($this->strict($_POST ['budgetActualMonthSix'], 'double'));
        }
        if (isset($_POST ['budgetActualMonthSeven'])) {
            $this->setBudgetActualMonthSeven($this->strict($_POST ['budgetActualMonthSeven'], 'double'));
        }
        if (isset($_POST ['budgetTargetMonthSeven'])) {
            $this->setBudgetTargetMonthSeven($this->strict($_POST ['budgetTargetMonthSeven'], 'double'));
        }
        if (isset($_POST ['budgetActualMonthEight'])) {
            $this->setBudgetActualMonthEight($this->strict($_POST ['budgetActualMonthEight'], 'double'));
        }
        if (isset($_POST ['budgetTargetMonthEight'])) {
            $this->setBudgetTargetMonthEight($this->strict($_POST ['budgetTargetMonthEight'], 'double'));
        }
        if (isset($_POST ['budgetActualMonthNine'])) {
            $this->setBudgetActualMonthNine($this->strict($_POST ['budgetActualMonthNine'], 'double'));
        }
        if (isset($_POST ['budgetTargetMonthNine'])) {
            $this->setBudgetTargetMonthNine($this->strict($_POST ['budgetTargetMonthNine'], 'double'));
        }
        if (isset($_POST ['budgetTargetMonthTen'])) {
            $this->setBudgetTargetMonthTen($this->strict($_POST ['budgetTargetMonthTen'], 'double'));
        }
        if (isset($_POST ['budgetActualMonthTen'])) {
            $this->setBudgetActualMonthTen($this->strict($_POST ['budgetActualMonthTen'], 'double'));
        }
        if (isset($_POST ['budgetTargetMonthEleven'])) {
            $this->setBudgetTargetMonthEleven($this->strict($_POST ['budgetTargetMonthEleven'], 'double'));
        }
        if (isset($_POST ['budgetActualMonthEleven'])) {
            $this->setBudgetActualMonthEleven($this->strict($_POST ['budgetActualMonthEleven'], 'double'));
        }
        if (isset($_POST ['budgetTargetMonthTwelve'])) {
            $this->setBudgetTargetMonthTwelve($this->strict($_POST ['budgetTargetMonthTwelve'], 'double'));
        }
        if (isset($_POST ['budgetActualMonthTwelve'])) {
            $this->setBudgetActualMonthTwelve($this->strict($_POST ['budgetActualMonthTwelve'], 'double'));
        }
        if (isset($_POST ['budgetTargetMonthThirteen'])) {
            $this->setBudgetTargetMonthThirteen($this->strict($_POST ['budgetTargetMonthThirteen'], 'double'));
        }
        if (isset($_POST ['budgetActualMonthThirteen'])) {
            $this->setBudgetActualMonthThirteen($this->strict($_POST ['budgetActualMonthThirteen'], 'double'));
        }
        if (isset($_POST ['budgetTargetMonthFourteen'])) {
            $this->setBudgetTargetMonthFourteen($this->strict($_POST ['budgetTargetMonthFourteen'], 'double'));
        }
        if (isset($_POST ['budgetActualMonthFourteen'])) {
            $this->setBudgetActualMonthFourteen($this->strict($_POST ['budgetActualMonthFourteen'], 'double'));
        }
        if (isset($_POST ['budgetTargetMonthFifteen'])) {
            $this->setBudgetTargetMonthFifteen($this->strict($_POST ['budgetTargetMonthFifteen'], 'double'));
        }
        if (isset($_POST ['budgetActualMonthFifteen'])) {
            $this->setBudgetActualMonthFifteen($this->strict($_POST ['budgetActualMonthFifteen'], 'double'));
        }
        if (isset($_POST ['budgetTargetMonthSixteen'])) {
            $this->setBudgetTargetMonthSixteen($this->strict($_POST ['budgetTargetMonthSixteen'], 'double'));
        }
        if (isset($_POST ['budgetActualMonthSixteen'])) {
            $this->setBudgetActualMonthSixteen($this->strict($_POST ['budgetActualMonthSixteen'], 'double'));
        }
        if (isset($_POST ['budgetTargetMonthSeventeen'])) {
            $this->setBudgetTargetMonthSeventeen($this->strict($_POST ['budgetTargetMonthSeventeen'], 'double'));
        }
        if (isset($_POST ['budgetActualMonthSeventeen'])) {
            $this->setBudgetActualMonthSeventeen($this->strict($_POST ['budgetActualMonthSeventeen'], 'double'));
        }
        if (isset($_POST ['budgetTargetMonthEighteen'])) {
            $this->setBudgetTargetMonthEighteen($this->strict($_POST ['budgetTargetMonthEighteen'], 'double'));
        }
        if (isset($_POST ['budgetActualMonthEighteen'])) {
            $this->setBudgetActualMonthEighteen($this->strict($_POST ['budgetActualMonthEighteen'], 'double'));
        }
        if (isset($_POST ['budgetTargetTotalYear'])) {
            $this->setBudgetTargetTotalYear($this->strict($_POST ['budgetTargetTotalYear'], 'double'));
        }
        if (isset($_POST ['budgetActualTotalYear'])) {
            $this->setBudgetActualTotalYear($this->strict($_POST ['budgetActualTotalYear'], 'double'));
        }
        if (isset($_POST ['budgetVersion'])) {
            $this->setBudgetVersion($this->strict($_POST ['budgetVersion'], 'string'));
        }
        if (isset($_POST ['isLock'])) {
            $this->setIsLock($this->strict($_POST ['isLock'], 'bool'));
        }
        if (isset($_POST ['budgetFieldName'])) {
            $this->setBudgetFieldName($this->strict($_POST ['budgetFieldName'], 'string'));
        }
        if (isset($_POST ['budgetFieldValue'])) {
            $this->setBudgetFieldValue($this->strict($_POST ['budgetFieldValue'], 'double'));
        }
        if (isset($_POST ['from'])) {
            $this->setFrom($this->strict($_POST ['from'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['budgetId'])) {
            $this->setBudgetId($this->strict($_GET ['budgetId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['chartOfAccountId'])) {
            $this->setChartOfAccountId($this->strict($_GET ['chartOfAccountId'], 'integer'));
        }
        if (isset($_GET ['chartOfAccountCategoryId'])) {
            $this->setChartOfAccountCategoryId($this->strict($_GET ['chartOfAccountCategoryId'], 'integer'));
        }
        if (isset($_GET ['chartOfAccountTypeId'])) {
            $this->setChartOfAccountTypeId($this->strict($_GET ['chartOfAccountTypeId'], 'integer'));
        }
        if (isset($_GET ['chartOfAccountLevel'])) {
            $this->setChartOfAccountLevel($this->strict($_GET ['chartOfAccountLevel'], 'integer'));
        }
        if (isset($_GET ['financeYearId'])) {
            $this->setFinanceYearId($this->strict($_GET ['financeYearId'], 'integer'));
        }
        if (isset($_GET ['financePeriodRangeId'])) {
            $this->setFinancePeriodRangeId($this->strict($_GET ['financePeriodRangeId'], 'integer'));
        }
        if (isset($_GET ['budgetTargetMonthOne'])) {
            $this->setBudgetTargetMonthOne($this->strict($_GET ['budgetTargetMonthOne'], 'double'));
        }
        if (isset($_GET ['budgetActualMonthOne'])) {
            $this->setBudgetActualMonthOne($this->strict($_GET ['budgetActualMonthOne'], 'double'));
        }
        if (isset($_GET ['budgetTargetMonthTwo'])) {
            $this->setBudgetTargetMonthTwo($this->strict($_GET ['budgetTargetMonthTwo'], 'double'));
        }
        if (isset($_GET ['budgetActualMonthTwo'])) {
            $this->setBudgetActualMonthTwo($this->strict($_GET ['budgetActualMonthTwo'], 'double'));
        }
        if (isset($_GET ['budgetTargetMonthThree'])) {
            $this->setBudgetTargetMonthThree($this->strict($_GET ['budgetTargetMonthThree'], 'double'));
        }
        if (isset($_GET ['budgetActualMonthThree'])) {
            $this->setBudgetActualMonthThree($this->strict($_GET ['budgetActualMonthThree'], 'double'));
        }
        if (isset($_GET ['budgetTargetMonthFourth'])) {
            $this->setBudgetTargetMonthFourth($this->strict($_GET ['budgetTargetMonthFourth'], 'double'));
        }
        if (isset($_GET ['budgetActualMonthFourth'])) {
            $this->setBudgetActualMonthFourth($this->strict($_GET ['budgetActualMonthFourth'], 'double'));
        }
        if (isset($_GET ['budgetTargetMonthFifth'])) {
            $this->setBudgetTargetMonthFifth($this->strict($_GET ['budgetTargetMonthFifth'], 'double'));
        }
        if (isset($_GET ['budgetActualMonthFifth'])) {
            $this->setBudgetActualMonthFifth($this->strict($_GET ['budgetActualMonthFifth'], 'double'));
        }
        if (isset($_GET ['budgetTargetMonthSix'])) {
            $this->setBudgetTargetMonthSix($this->strict($_GET ['budgetTargetMonthSix'], 'double'));
        }
        if (isset($_GET ['budgetActualMonthSix'])) {
            $this->setBudgetActualMonthSix($this->strict($_GET ['budgetActualMonthSix'], 'double'));
        }
        if (isset($_GET ['budgetActualMonthSeven'])) {
            $this->setBudgetActualMonthSeven($this->strict($_GET ['budgetActualMonthSeven'], 'double'));
        }
        if (isset($_GET ['budgetTargetMonthSeven'])) {
            $this->setBudgetTargetMonthSeven($this->strict($_GET ['budgetTargetMonthSeven'], 'double'));
        }
        if (isset($_GET ['budgetActualMonthEight'])) {
            $this->setBudgetActualMonthEight($this->strict($_GET ['budgetActualMonthEight'], 'double'));
        }
        if (isset($_GET ['budgetTargetMonthEight'])) {
            $this->setBudgetTargetMonthEight($this->strict($_GET ['budgetTargetMonthEight'], 'double'));
        }
        if (isset($_GET ['budgetActualMonthNine'])) {
            $this->setBudgetActualMonthNine($this->strict($_GET ['budgetActualMonthNine'], 'double'));
        }
        if (isset($_GET ['budgetTargetMonthNine'])) {
            $this->setBudgetTargetMonthNine($this->strict($_GET ['budgetTargetMonthNine'], 'double'));
        }
        if (isset($_GET ['budgetTargetMonthTen'])) {
            $this->setBudgetTargetMonthTen($this->strict($_GET ['budgetTargetMonthTen'], 'double'));
        }
        if (isset($_GET ['budgetActualMonthTen'])) {
            $this->setBudgetActualMonthTen($this->strict($_GET ['budgetActualMonthTen'], 'double'));
        }
        if (isset($_GET ['budgetTargetMonthEleven'])) {
            $this->setBudgetTargetMonthEleven($this->strict($_GET ['budgetTargetMonthEleven'], 'double'));
        }
        if (isset($_GET ['budgetActualMonthEleven'])) {
            $this->setBudgetActualMonthEleven($this->strict($_GET ['budgetActualMonthEleven'], 'double'));
        }
        if (isset($_GET ['budgetTargetMonthTwelve'])) {
            $this->setBudgetTargetMonthTwelve($this->strict($_GET ['budgetTargetMonthTwelve'], 'double'));
        }
        if (isset($_GET ['budgetActualMonthTwelve'])) {
            $this->setBudgetActualMonthTwelve($this->strict($_GET ['budgetActualMonthTwelve'], 'double'));
        }
        if (isset($_GET ['budgetTargetMonthThirteen'])) {
            $this->setBudgetTargetMonthThirteen($this->strict($_GET ['budgetTargetMonthThirteen'], 'double'));
        }
        if (isset($_GET ['budgetActualMonthThirteen'])) {
            $this->setBudgetActualMonthThirteen($this->strict($_GET ['budgetActualMonthThirteen'], 'double'));
        }
        if (isset($_GET ['budgetTargetMonthFourteen'])) {
            $this->setBudgetTargetMonthFourteen($this->strict($_GET ['budgetTargetMonthFourteen'], 'double'));
        }
        if (isset($_GET ['budgetActualMonthFourteen'])) {
            $this->setBudgetActualMonthFourteen($this->strict($_GET ['budgetActualMonthFourteen'], 'double'));
        }
        if (isset($_GET ['budgetTargetMonthFifteen'])) {
            $this->setBudgetTargetMonthFifteen($this->strict($_GET ['budgetTargetMonthFifteen'], 'double'));
        }
        if (isset($_GET ['budgetActualMonthFifteen'])) {
            $this->setBudgetActualMonthFifteen($this->strict($_GET ['budgetActualMonthFifteen'], 'double'));
        }
        if (isset($_GET ['budgetTargetMonthSixteen'])) {
            $this->setBudgetTargetMonthSixteen($this->strict($_GET ['budgetTargetMonthSixteen'], 'double'));
        }
        if (isset($_GET ['budgetActualMonthSixteen'])) {
            $this->setBudgetActualMonthSixteen($this->strict($_GET ['budgetActualMonthSixteen'], 'double'));
        }
        if (isset($_GET ['budgetTargetMonthSeventeen'])) {
            $this->setBudgetTargetMonthSeventeen($this->strict($_GET ['budgetTargetMonthSeventeen'], 'double'));
        }
        if (isset($_GET ['budgetActualMonthSeventeen'])) {
            $this->setBudgetActualMonthSeventeen($this->strict($_GET ['budgetActualMonthSeventeen'], 'double'));
        }
        if (isset($_GET ['budgetTargetMonthEighteen'])) {
            $this->setBudgetTargetMonthEighteen($this->strict($_GET ['budgetTargetMonthEighteen'], 'double'));
        }
        if (isset($_GET ['budgetActualMonthEighteen'])) {
            $this->setBudgetActualMonthEighteen($this->strict($_GET ['budgetActualMonthEighteen'], 'double'));
        }
        if (isset($_GET ['budgetTargetTotalYear'])) {
            $this->setBudgetTargetTotalYear($this->strict($_GET ['budgetTargetTotalYear'], 'double'));
        }
        if (isset($_GET ['budgetActualTotalYear'])) {
            $this->setBudgetActualTotalYear($this->strict($_GET ['budgetActualTotalYear'], 'double'));
        }
        if (isset($_GET ['budgetVersion'])) {
            $this->setBudgetVersion($this->strict($_GET ['budgetVersion'], 'string'));
        }
        if (isset($_GET ['isLock'])) {
            $this->setIsLock($this->strict($_GET ['isLock'], 'bool'));
        }
        if (isset($_GET ['budgetFieldName'])) {
            $this->setBudgetFieldName($this->strict($_GET ['budgetFieldName'], 'string'));
        }
        if (isset($_GET ['budgetFieldValue'])) {
            $this->setBudgetFieldValue($this->strict($_GET ['budgetFieldValue'], 'double'));
        }
        if (isset($_GET ['from'])) {
            $this->setFrom($this->strict($_GET ['from'], 'string'));
        }
        if (isset($_GET ['budgetId'])) {
            $this->setTotal(count($_GET ['budgetId']));
            if (is_array($_GET ['budgetId'])) {
                $this->budgetId = array();
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
            if (isset($_GET ['budgetId'])) {
                $this->setBudgetId($this->strict($_GET ['budgetId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getBudgetId($i, 'array') . ",";
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
    public function getBudgetId($key, $type) {
        if ($type == 'single') {
            return $this->budgetId;
        } else {
            if ($type == 'array') {
                return $this->budgetId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getbudgetId ?")
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
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetId($value, $key, $type) {
        if ($type == 'single') {
            $this->budgetId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->budgetId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setbudgetId?")
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
     * To Return  Company
     * @return int $companyId
     */
    public function getCompanyId() {
        return $this->companyId;
    }

    /**
     * To Set Company
     * @param int $companyId Company
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Chart Of Account
     * @return int $chartOfAccountId
     */
    public function getChartOfAccountId() {
        return $this->chartOfAccountId;
    }

    /**
     * To Set Chart Of Account
     * @param int $chartOfAccountId Chart Account
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setChartOfAccountId($chartOfAccountId) {
        $this->chartOfAccountId = $chartOfAccountId;
        return $this;
    }

    /**
     * To Return Chart Of Account Category
     * @return int $chartOfAccountId
     */
    public function getChartOfAccountCategoryId() {
        return $this->chartOfAccountCategoryId;
    }

    /**
     * To Set Chart Of Account
     * @param int $chartOfAccountCategoryId Chart Category Account
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setChartOfAccountCategoryId($chartOfAccountCategoryId) {
        $this->chartOfAccountCategoryId = $chartOfAccountCategoryId;
        return $this;
    }

    /**
     * To Return Chart Of Account Type
     * @return int $chartOfAccountTypeId
     */
    public function getChartOfAccountTypeId() {
        return $this->chartOfAccountTypeId;
    }

    /**
     * To Set Chart Of Account Type
     * @param int $chartOfAccountTypeId Chart Account
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setChartOfAccountTypeId($chartOfAccountTypeId) {
        $this->chartOfAccountTypeId = $chartOfAccountTypeId;
        return $this;
    }

    /**
     * To Return Chart Of Account Level
     * @return int $chartOfAccountLevel
     */
    public function getChartOfAccountLevel() {
        return $this->chartOfAccountLevel;
    }

    /**
     * To Set Chart Of Account Level
     * @param int $chartOfAccountLevel Chart Of Account Level
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setChartOfAccountLevel($chartOfAccountLevel) {
        $this->chartOfAccountLevel = $chartOfAccountLevel;
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
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setFinanceYearId($financeYearId) {
        $this->financeYearId = $financeYearId;
        return $this;
    }

    /**
     * To Return Finance Period Range
     * @return int $financePeriodRangeId
     */
    public function getFinancePeriodRangeId() {
        return $this->financePeriodRangeId;
    }

    /**
     * To Set Finance Period Range
     * @param int $financePeriodRangeId Finance Period
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setFinancePeriodRangeId($financePeriodRangeId) {
        $this->financePeriodRangeId = $financePeriodRangeId;
        return $this;
    }

    /**
     * To Return  Target Month One (1)
     * @return double $budgetTargetMonthOne
     */
    public function getBudgetTargetMonthOne() {
        return $this->budgetTargetMonthOne;
    }

    /**
     * To Set Target Month One (1)
     * @param double $budgetTargetMonthOne Target One
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetTargetMonthOne($budgetTargetMonthOne) {
        $this->budgetTargetMonthOne = $budgetTargetMonthOne;
        return $this;
    }

    /**
     * To Return  Actual Month One (1)
     * @return double $budgetActualMonthOne
     */
    public function getBudgetActualMonthOne() {
        return $this->budgetActualMonthOne;
    }

    /**
     * To Set Actual Month One (1)
     * @param double $budgetActualMonthOne Actual One
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetActualMonthOne($budgetActualMonthOne) {
        $this->budgetActualMonthOne = $budgetActualMonthOne;
        return $this;
    }

    /**
     * To Return  Target Month Two (2)
     * @return double $budgetTargetMonthTwo
     */
    public function getBudgetTargetMonthTwo() {
        return $this->budgetTargetMonthTwo;
    }

    /**
     * To Set Target Month Two (2)
     * @param double $budgetTargetMonthTwo Target Two
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetTargetMonthTwo($budgetTargetMonthTwo) {
        $this->budgetTargetMonthTwo = $budgetTargetMonthTwo;
        return $this;
    }

    /**
     * To Return Actual Month Two (2)
     * @return double $budgetActualMonthTwo
     */
    public function getBudgetActualMonthTwo() {
        return $this->budgetActualMonthTwo;
    }

    /**
     * To Set Actual Month Two (2)
     * @param double $budgetActualMonthTwo Actual Two
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetActualMonthTwo($budgetActualMonthTwo) {
        $this->budgetActualMonthTwo = $budgetActualMonthTwo;
        return $this;
    }

    /**
     * To Return Target Month Three (3)
     * @return double $budgetTargetMonthThree
     */
    public function getBudgetTargetMonthThree() {
        return $this->budgetTargetMonthThree;
    }

    /**
     * To Set Target Month Three (3)
     * @param double $budgetTargetMonthThree Target Three
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetTargetMonthThree($budgetTargetMonthThree) {
        $this->budgetTargetMonthThree = $budgetTargetMonthThree;
        return $this;
    }

    /**
     * To Return Actual Month Three (3)
     * @return double $budgetActualMonthThree
     */
    public function getBudgetActualMonthThree() {
        return $this->budgetActualMonthThree;
    }

    /**
     * To Set Actual Month Three (3)
     * @param double $budgetActualMonthThree Actual Three
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetActualMonthThree($budgetActualMonthThree) {
        $this->budgetActualMonthThree = $budgetActualMonthThree;
        return $this;
    }

    /**
     * To Return  Target Month Fourth (4)
     * @return double $budgetTargetMonthFourth
     */
    public function getBudgetTargetMonthFourth() {
        return $this->budgetTargetMonthFourth;
    }

    /**
     * To Set Target Month Fourth (4)
     * @param double $budgetTargetMonthFourth Target Fourth
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetTargetMonthFourth($budgetTargetMonthFourth) {
        $this->budgetTargetMonthFourth = $budgetTargetMonthFourth;
        return $this;
    }

    /**
     * To Return Actual Month Fourth (4)
     * @return double $budgetActualMonthFourth
     */
    public function getBudgetActualMonthFourth() {
        return $this->budgetActualMonthFourth;
    }

    /**
     * To Set Actual Month Fourth (4)
     * @param double $budgetActualMonthFourth Actual Fourth
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetActualMonthFourth($budgetActualMonthFourth) {
        $this->budgetActualMonthFourth = $budgetActualMonthFourth;
        return $this;
    }

    /**
     * To Return Target Month Fifth (5)
     * @return double $budgetTargetMonthFifth
     */
    public function getBudgetTargetMonthFifth() {
        return $this->budgetTargetMonthFifth;
    }

    /**
     * To Set Target Month Fifth (5)
     * @param double $budgetTargetMonthFifth Target Fifth
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetTargetMonthFifth($budgetTargetMonthFifth) {
        $this->budgetTargetMonthFifth = $budgetTargetMonthFifth;
        return $this;
    }

    /**
     * To Return  Actual Month Fifth (5)
     * @return double $budgetActualMonthFifth
     */
    public function getBudgetActualMonthFifth() {
        return $this->budgetActualMonthFifth;
    }

    /**
     * To Set Actual Month Fifth (5)
     * @param double $budgetActualMonthFifth Actual Fifth
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetActualMonthFifth($budgetActualMonthFifth) {
        $this->budgetActualMonthFifth = $budgetActualMonthFifth;
        return $this;
    }

    /**
     * To Return Target Month Six (6)
     * @return double $budgetTargetMonthSix
     */
    public function getBudgetTargetMonthSix() {
        return $this->budgetTargetMonthSix;
    }

    /**
     * To Set Target Month Six (6)
     * @param double $budgetTargetMonthSix Target Six
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetTargetMonthSix($budgetTargetMonthSix) {
        $this->budgetTargetMonthSix = $budgetTargetMonthSix;
        return $this;
    }

    /**
     * To Return Actual Month Six (6)
     * @return double $budgetActualMonthSix
     */
    public function getBudgetActualMonthSix() {
        return $this->budgetActualMonthSix;
    }

    /**
     * To Set Actual Month Six (6)
     * @param double $budgetActualMonthSix Actual Six
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetActualMonthSix($budgetActualMonthSix) {
        $this->budgetActualMonthSix = $budgetActualMonthSix;
        return $this;
    }

    /**
     * To Return Actual Month Seven (7)
     * @return double $budgetActualMonthSeven
     */
    public function getBudgetActualMonthSeven() {
        return $this->budgetActualMonthSeven;
    }

    /**
     * To Set Actual Month Seven (7)
     * @param double $budgetActualMonthSeven Actual Seven
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetActualMonthSeven($budgetActualMonthSeven) {
        $this->budgetActualMonthSeven = $budgetActualMonthSeven;
        return $this;
    }

    /**
     * To Return Target Month Seven (7)
     * @return double $budgetTargetMonthSeven
     */
    public function getBudgetTargetMonthSeven() {
        return $this->budgetTargetMonthSeven;
    }

    /**
     * To Set TargetMonthSeven
     * @param double $budgetTargetMonthSeven Target Seven
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetTargetMonthSeven($budgetTargetMonthSeven) {
        $this->budgetTargetMonthSeven = $budgetTargetMonthSeven;
        return $this;
    }

    /**
     * To Return Actual Month Eight (8)
     * @return double $budgetActualMonthEight
     */
    public function getBudgetActualMonthEight() {
        return $this->budgetActualMonthEight;
    }

    /**
     * To Set Actual Month Eight (8)
     * @param double $budgetActualMonthEight Actual Eight
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetActualMonthEight($budgetActualMonthEight) {
        $this->budgetActualMonthEight = $budgetActualMonthEight;
        return $this;
    }

    /**
     * To Return Target Month Eight (8)
     * @return double $budgetTargetMonthEight
     */
    public function getBudgetTargetMonthEight() {
        return $this->budgetTargetMonthEight;
    }

    /**
     * To Set Target Month Eight (8)
     * @param double $budgetTargetMonthEight Target Eight
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetTargetMonthEight($budgetTargetMonthEight) {
        $this->budgetTargetMonthEight = $budgetTargetMonthEight;
        return $this;
    }

    /**
     * To Return Actual Month Nine (9)
     * @return double $budgetActualMonthNine
     */
    public function getBudgetActualMonthNine() {
        return $this->budgetActualMonthNine;
    }

    /**
     * To Set Actual Month Nine (9)
     * @param double $budgetActualMonthNine Actual Nine
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetActualMonthNine($budgetActualMonthNine) {
        $this->budgetActualMonthNine = $budgetActualMonthNine;
        return $this;
    }

    /**
     * To Return Target Month Nine (9)
     * @return double $budgetTargetMonthNine
     */
    public function getBudgetTargetMonthNine() {
        return $this->budgetTargetMonthNine;
    }

    /**
     * To Set Target Month Nine (9)
     * @param double $budgetTargetMonthNine Target Nine
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetTargetMonthNine($budgetTargetMonthNine) {
        $this->budgetTargetMonthNine = $budgetTargetMonthNine;
        return $this;
    }

    /**
     * To Return Target Month Ten (10)
     * @return double $budgetTargetMonthTen
     */
    public function getBudgetTargetMonthTen() {
        return $this->budgetTargetMonthTen;
    }

    /**
     * To Set Target Month Ten (10)
     * @param double $budgetTargetMonthTen Target Ten
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetTargetMonthTen($budgetTargetMonthTen) {
        $this->budgetTargetMonthTen = $budgetTargetMonthTen;
        return $this;
    }

    /**
     * To Return  Actual Month Ten (10)
     * @return double $budgetActualMonthTen
     */
    public function getBudgetActualMonthTen() {
        return $this->budgetActualMonthTen;
    }

    /**
     * To Set Actual Month Ten (10)
     * @param double $budgetActualMonthTen Actual Ten
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetActualMonthTen($budgetActualMonthTen) {
        $this->budgetActualMonthTen = $budgetActualMonthTen;
        return $this;
    }

    /**
     * To Return  Target Month Eleven (11)
     * @return double $budgetTargetMonthEleven
     */
    public function getBudgetTargetMonthEleven() {
        return $this->budgetTargetMonthEleven;
    }

    /**
     * To Set Target Month Eleven (11)
     * @param double $budgetTargetMonthEleven Target Eleven
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetTargetMonthEleven($budgetTargetMonthEleven) {
        $this->budgetTargetMonthEleven = $budgetTargetMonthEleven;
        return $this;
    }

    /**
     * To Return Actual Month Eleven (11)
     * @return double $budgetActualMonthEleven
     */
    public function getBudgetActualMonthEleven() {
        return $this->budgetActualMonthEleven;
    }

    /**
     * To Set Actual Month Eleven (11)
     * @param double $budgetActualMonthEleven Actual Eleven
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetActualMonthEleven($budgetActualMonthEleven) {
        $this->budgetActualMonthEleven = $budgetActualMonthEleven;
        return $this;
    }

    /**
     * To Return Target Month Twelve (12)
     * @return double $budgetTargetMonthTwelve
     */
    public function getBudgetTargetMonthTwelve() {
        return $this->budgetTargetMonthTwelve;
    }

    /**
     * To Set Target Month Twelve (12)
     * @param double $budgetTargetMonthTwelve Target Twelve
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetTargetMonthTwelve($budgetTargetMonthTwelve) {
        $this->budgetTargetMonthTwelve = $budgetTargetMonthTwelve;
        return $this;
    }

    /**
     * To Return Actual Month Twelve (12)
     * @return double $budgetActualMonthTwelve
     */
    public function getBudgetActualMonthTwelve() {
        return $this->budgetActualMonthTwelve;
    }

    /**
     * To Set Actual Month Twelve (12)
     * @param double $budgetActualMonthTwelve Actual Twelve
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetActualMonthTwelve($budgetActualMonthTwelve) {
        $this->budgetActualMonthTwelve = $budgetActualMonthTwelve;
        return $this;
    }

    /**
     * To Return Target Month Third Teen   (13)
     * @return double $budgetTargetMonthThirteen
     */
    public function getBudgetTargetMonthThirteen() {
        return $this->budgetTargetMonthThirteen;
    }

    /**
     * To Set Target Month Third Teen (13)
     * @param double $budgetTargetMonthThirdTeen Target Month Third   Teen
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetTargetMonthThirteen($budgetTargetMonthThirdTeen) {
        $this->budgetTargetMonthThirteen = $budgetTargetMonthThirdTeen;
        return $this;
    }

    /**
     * To Return Actual Month Third Teen (13)
     * @return double $budgetActualMonthThirteen
     */
    public function getBudgetActualMonthThirteen() {
        return $this->budgetActualMonthThirteen;
    }

    /**
     * To Set Actual Month Third Teen (13)
     * @param double $budgetActualMonthThirdTeen Actual Month Third   Teen
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetActualMonthThirteen($budgetActualMonthThirdTeen) {
        $this->budgetActualMonthThirteen = $budgetActualMonthThirdTeen;
        return $this;
    }

    /**
     * To Return TargetMonthFourthTeen (14)
     * @return double $budgetTargetMonthFourteen
     */
    public function getBudgetTargetMonthFourteen() {
        return $this->budgetTargetMonthFourteen;
    }

    /**
     * To Set Target Month Fourth Teen (14)
     * @param double $budgetTargetMonthFourthTeen Target Month Fourth   Teen
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetTargetMonthFourteen($budgetTargetMonthFourthTeen) {
        $this->budgetTargetMonthFourteen = $budgetTargetMonthFourthTeen;
        return $this;
    }

    /**
     * To Return Actual Month Fourth Teen (14)
     * @return double $budgetActualMonthFourteen
     */
    public function getBudgetActualMonthFourteen() {
        return $this->budgetActualMonthFourteen;
    }

    /**
     * To Set Actual Month Fourth Teen (14)
     * @param double $budgetActualMonthFourthTeen Actual Month Fourth   Teen
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetActualMonthFourteen($budgetActualMonthFourthTeen) {
        $this->budgetActualMonthFourteen = $budgetActualMonthFourthTeen;
        return $this;
    }

    /**
     * To Return Target Month Fifteen (15)
     * @return double $budgetTargetMonthFifteen
     */
    public function getBudgetTargetMonthFifteen() {
        return $this->budgetTargetMonthFifteen;
    }

    /**
     * To Set Target Month Fifteen (15)
     * @param double $budgetTargetMonthFifteen Target Fifteen
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetTargetMonthFifteen($budgetTargetMonthFifteen) {
        $this->budgetTargetMonthFifteen = $budgetTargetMonthFifteen;
        return $this;
    }

    /**
     * To Return Actual Month Fifteen (15)
     * @return double $budgetActualMonthFifteen
     */
    public function getBudgetActualMonthFifteen() {
        return $this->budgetActualMonthFifteen;
    }

    /**
     * To Set Actual Month Fifteen (15)
     * @param double $budgetActualMonthFifteen Actual Fifteen
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetActualMonthFifteen($budgetActualMonthFifteen) {
        $this->budgetActualMonthFifteen = $budgetActualMonthFifteen;
        return $this;
    }

    /**
     * To Return Actual Month Sixteen (16)
     * @return double $budgetActualMonthSixteen
     */
    public function getBudgetTargetMonthSixteen() {
        return $this->budgetTargetMonthSixteen;
    }

    /**
     * To Set Target Month Sixteen (16)
     * @param double $budgetTargetMonthSixteen Actual Sixteen
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetTargetMonthSixteen($budgetTargetMonthSixteen) {
        $this->budgetTargetMonthSixteen = $budgetTargetMonthSixteen;
        return $this;
    }

    /**
     * To Return Actual Month Sixteen (16)
     * @return double $budgetActualMonthSixteen
     */
    public function getBudgetActualMonthSixteen() {
        return $this->budgetActualMonthSixteen;
    }

    /**
     * To Set Actual Month Sixteen (16)
     * @param double $budgetActualMonthSixteen Actual Sixteen
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetActualMonthSixteen($budgetActualMonthSixteen) {
        $this->budgetActualMonthSixteen = $budgetActualMonthSixteen;
        return $this;
    }

    /**
     * To Return Target Month Seventeen (17)
     * @return double $budgetTargetMonthSeventeen
     */
    public function getBudgetTargetMonthSeventeen() {
        return $this->budgetTargetMonthSeventeen;
    }

    /**
     * To Set Target Month Seventeen (17)
     * @param double $budgetTargetMonthSeventeen Target Seventeen
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetTargetMonthSeventeen($budgetTargetMonthSeventeen) {
        $this->budgetTargetMonthSeventeen = $budgetTargetMonthSeventeen;
        return $this;
    }

    /**
     * To Return Actual Month Seventeen (17)
     * @return double $budgetActualMonthSeventeen
     */
    public function getBudgetActualMonthSeventeen() {
        return $this->budgetActualMonthSeventeen;
    }

    /**
     * To Set Actual Month Seventeen (17)
     * @param double $budgetActualMonthSeventeen Actual Seventeen
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetActualMonthSeventeen($budgetActualMonthSeventeen) {
        $this->budgetActualMonthSeventeen = $budgetActualMonthSeventeen;
        return $this;
    }

    /**
     * To Return Target Month Eighteen (18)
     * @return double $budgetTargetMonthEighteen
     */
    public function getBudgetTargetMonthEighteen() {
        return $this->budgetTargetMonthEighteen;
    }

    /**
     * To Set Target Month Eighteen(18)
     * @param double $budgetTargetMonthEighteen Target Eighteen
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetTargetMonthEighteen($budgetTargetMonthEighteen) {
        $this->budgetTargetMonthEighteen = $budgetTargetMonthEighteen;
        return $this;
    }

    /**
     * To Return Actual Month Eighteen (18)
     * @return double $budgetActualMonthEighteen
     */
    public function getBudgetActualMonthEighteen() {
        return $this->budgetActualMonthEighteen;
    }

    /**
     * To Set Actual Month Eighteen
     * @param double $budgetActualMonthEighteen Actual Eighteen
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetActualMonthEighteen($budgetActualMonthEighteen) {
        $this->budgetActualMonthEighteen = $budgetActualMonthEighteen;
        return $this;
    }

    /**
     * To Return Target Year Transaction
     * @return double $budgetTargetTotalYear
     */
    public function getBudgetTargetTotalYear() {
        return $this->budgetTargetTotalYear;
    }

    /**
     * To Set Target Year Transaction
     * @param double $budgetTargetTotalYear Target Year
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetTargetTotalYear($budgetTargetTotalYear) {
        $this->budgetTargetTotalYear = $budgetTargetTotalYear;
        return $this;
    }

    /**
     * To Return Actual Year Transaction
     * @return double $budgetActualTotalYear
     */
    public function getBudgetActualTotalYear() {
        return $this->budgetActualTotalYear;
    }

    /**
     * To Set Actual Year Transaction
     * @param double $budgetActualTotalYear Actual Year
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetActualTotalYear($budgetActualTotalYear) {
        $this->budgetActualTotalYear = $budgetActualTotalYear;
        return $this;
    }

    /**
     * To Return Version
     * @return string $budgetVersion
     */
    public function getBudgetVersion() {
        return $this->budgetVersion;
    }

    /**
     * To Set Version
     * @param string $budgetVersion Version
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetVersion($budgetVersion) {
        $this->budgetVersion = $budgetVersion;
        return $this;
    }

    /**
     * To Return is Lock.Once Lock,only Transfer Budget is Permit
     * @return bool $isLock
     */
    public function getIsLock() {
        return $this->isLock;
    }

    /**
     * To Set is Lock. Once lock,Only Transfer Budget is Permit
     * @param bool $isLock Is Lock
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setIsLock($isLock) {
        $this->isLock = $isLock;
        return $this;
    }

    /**
     * To Return Budget Field Name
     * @return string $budgetFieldName
     */
    public function getBudgetFieldName() {
        return $this->budgetFieldName;
    }

    /**
     * To Set Budget Field Name
     * @param bool $budgetFieldName Field Name
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetFieldname($budgetFieldName) {
        $this->budgetFieldName = $budgetFieldName;
        return $this;
    }

    /**
     * To Return Budget Field Value
     * @return float $budgetFieldValue
     */
    public function getBudgetFieldValue() {
        return $this->budgetFieldValue;
    }

    /**
     * To Set Budget Field Value
     * @param float $budgetFieldValue Value
     * @return \Core\Financial\GeneralLedger\Budget\Model\BudgetModel
     */
    public function setBudgetFieldValue($budgetFieldValue) {
        $this->budgetFieldValue = $budgetFieldValue;
        return $this;
    }

}

?>