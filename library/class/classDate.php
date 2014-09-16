<?php

namespace Core\Date;

/**
 * a specific class for connection to mysql.Either mysql or mysqli
 * @author hafizan
 * @copyright IDCMS
 *
 */
class DateClass {

    /**
     * @var string
     */
    private $dateVariable;

    /**
     * Day
     * @var int
     */
    private $day;

    /**
     * Day ( only used at week )
     * @var int
     */
    private $dayTemp;

    /**
     * Week
     * @var int
     */
    private $week;

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
     * Date Choosen,Pick
     * @var string
     */
    private $dateRetrieve;

    /**
     * Date Filter Type, day,month,week,year
     * @var string
     */
    private $dateFilterType;

    /**
     * Start Date
     * @var string
     */
    private $startDate;

    /**
     * End Date
     * @var string
     */
    private $endDate;

    /**
     * Set Time Zone
     */
    function setTimezone() {
        if (function_exists("date_default_timezone_set") and
                function_exists("date_default_timezone_get")
        ) {
            date_default_timezone_set(date_default_timezone_get());
        }
    }

    /**
     * Calculate total daays
     * @param string $date1
     * @param string $date2
     * @return type
     */
    function getCountDays($date1, $date2) {
        $d1 = new DateTime($date1);
        $d2 = new DateTime($date2);
        $diff = $d2->diff($d1);
        return $diff->y;
    }

    /**
     * Calculate total month
     * @param string $date1
     * @param string $date2
     * @return type
     */
    function getCountMonth($date1, $date2) {
        $d1 = new DateTime($date1);
        $d2 = new DateTime($date2);
        $diff = $d2->diff($d1);
        return $diff->m;
    }

    /**
     * Calculate total year 
     * @param string $date1
     * @param string $date2
     * @return type
     */
    function getCountYear($date1, $date2) {
        $d1 = new DateTime($date1);
        $d2 = new DateTime($date2);
        $diff = $d2->diff($d1);
        return $diff->y;
    }

    /**
     *
     * @param string $time1
     * @param string $time2
     * @return string
     */
    function getCountTime($time1, $time2) {
        $hour2 = substr($time2, 0, 2);
        $minute2 = intval(substr($time2, 3, 2));
        $hour1 = substr($time1, 0, 2);
        $minute1 = intval(substr($time1, 3, 2));
        $total_hour = $hour2 - $hour1;
        $total_minute = $minute2 - $minute1;
        if ($total_minute < 0) {
            $total_hour--;
            $total_minute = ($minute2 + 60) - $minute1;
        }
        $test = ' ' . $total_hour . ' Hour ' . $total_minute . ' minute ';
        return ($test);
    }

    /**
     *
     * @param string $time1
     * @param string $time2
     * @return string
     */
    function getCountTime2($time1, $time2) {
        $hour2 = substr($time2, 0, 2);
        $minute2 = intval(substr($time2, 3, 2));
        $hour1 = substr($time1, 0, 2);
        $minute1 = intval(substr($time1, 3, 2));
        $total_hour = $hour2 - $hour1;
        $total_minute = $minute2 - $minute1;
        if ($total_minute < 0) {
            $total_hour--;
            $total_minute = ($minute2 + 60) - $minute1;
        }
        $total = (60 * $total_hour) + $total_minute;
        return ($total);
    }

    function getStatusleapYear($year) {
        return ((($year % 4) == 0) && ((($year % 100) != 0) || (($year % 400) == 0)));
    }

    /**
     *
     * @param string $dateVariable
     * @return string
     */
    function convertDateMysql($dateVariable) {
        $this->dateVariable = $dateVariable;
        $checkLengthDate = strlen($this->dateVariable);
        if ($checkLengthDate == 6) {
            $this->day = substr($this->dateVariable, 6, 2);
            $this->month = substr($this->dateVariable, 4, 2);
            $this->year = substr($this->dateVariable, 0, 4);
        } elseif ($checkLengthDate == 10) {
            $this->day = substr($this->dateVariable, 8, 2);
            $this->month = substr($this->dateVariable, 5, 2);
            $this->year = substr($this->dateVariable, 0, 4);
        }
        return $this->day . "-" . $this->month . "-" . $this->year;
    }

    /**
     *
     * @param string $dateVariable
     * @return string
     */
    function ext_date($dateVariable) {
        $this->dateVariable = $dateVariable;
        $checkLengthDate = strlen($this->dateVariable);
        if ($checkLengthDate == 6) {
            $this->day = substr($this->dateVariable, 6, 2);
            $this->month = substr($this->dateVariable, 4, 2);
            $this->year = substr($this->dateVariable, 2, 2);
        } elseif ($checkLengthDate == 10) {
            $this->day = substr($this->dateVariable, 8, 2);
            $this->month = substr($this->dateVariable, 5, 2);
            $this->year = substr($this->dateVariable, 2, 2);
        }
        return $this->month . "/" . $this->day . "/" . $this->year;
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
     * Return Future Date
     * @param string $x Current  / Chosen Date
     * @param string $type day,week,month,year
     * @return string
     */
    function getForwardDate($x, $type) {

        $this->setDateRetrieve($x);
        $this->setDateFilterType($type);

        $dateArray = explode("-", $this->getDateRetrieve());

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $this->setTotalDayInMonth(date('t', mktime('0', '0', '0', $this->getMonth(), $this->getDay(), $this->getYear()))
        );
        if ($this->getDateFilterType() == 'day') {
            if ($this->getDay() >= $this->getTotalDayInMonth()) {
                $this->setDay(1);
                if ($this->getMonth() == 12) {
                    $this->setYear($this->getYear() + 1);
                    $this->setMonth(1);
                } else {
                    $this->setMonth($this->getMonth() + 1);
                }
            } else {
                $this->setDay($this->getDay() + 1);
            }
            $this->setDay($this->changeZero($this->getDay()));
            $this->setMonth($this->changeZero($this->getMonth()));
            return $this->getDay() . "-" . $this->getMonth() . "-" . $this->getYear();
        } elseif ($this->getDateFilterType() == 'week') {
            $d = new \DateTime(date("Y-m-d", mktime(0, 0, 0, $this->getMonth(), $this->getDay(), $this->getYear())));
            $weekday = $d->format('w');
            $diff = ($weekday == 0 ? 6 : $weekday - 1) - 7; // Monday=0, Sunday=6
            $d->modify("-$diff day");
            $this->setStartDate($d->format('Y-m-d'));
            $d->modify('+6 day');
            $this->setEndDate($d->format('Y-m-d'));
            return ($this->getStartDate() . "-" . $this->getEndDate());
        } elseif ($this->getDateFilterType() == 'month') {
            if ($this->getMonth() == 12) {
                $this->setYear($this->getYear() + 1);
                $this->setMonth(1);
            } else {
                $this->setMonth($this->getMonth() + 1);
            }
            $this->setDay($this->changeZero($this->getDay()));
            $this->setMonth($this->changeZero($this->getMonth()));
            return $this->getDay() . "-" . $this->getMonth() . "-" . $this->getYear();
        } elseif ($this->getDateFilterType() == 'year') {
            $this->setDay($this->changeZero($this->getDay()));
            $this->setMonth($this->changeZero($this->getMonth()));
            $this->setYear($this->getYear() + 1);
            return $this->getDay() . "-" . $this->getMonth() . "-" . $this->getYear();
        }
        return false;
    }

    /**
     * Return Previous  Date Based On Type
     * @param string $date Current Date / Choosen  Date
     * @param string $type day,week,month,year
     * @return string
     */
    function getPreviousDate($date, $type) {
        // return false if not valid date
        $this->setDateRetrieve($date);
        $this->setDateFilterType($type);
        $dateArray = explode("-", $this->getDateRetrieve());
        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        if ($this->getDateFilterType() == 'day') {
            $this->setDay($this->getDay() - 1);
            if ($this->getDay() == 0) {
                $this->setMonth($this->getMonth() - 1);
                $this->setDay(date('t', mktime(0, 0, 0, $this->getMonth(), 1, $this->getYear())));
                if ($this->getMonth() == 0) {
                    $this->setDay(31);
                    $this->setMonth(12);
                    $this->setYear($this->getYear() - 1);
                }
            }
            // leaping year issue
            if ($this->getMonth() == 2 && $this->getDay() > 27) {
                $this->setDay(28);
            }
            $this->setDay($this->changeZero($this->getDay()));
            $this->setMonth($this->changeZero($this->getMonth()));
            return $this->getDay() . "-" . $this->getMonth() . "-" . $this->getYear();
        } elseif ($this->getDateFilterType() == 'week') {
            $d = new \DateTime(date("Y-m-d", mktime(0, 0, 0, $this->getMonth(), $this->getDay(), $this->getYear())));
            $weekday = $d->format('w');
            $diff = ($weekday == 0 ? 6 : $weekday - 1) + 7; // Monday=0, Sunday=6
            $d->modify("-$diff day");
            $this->setStartDate($d->format('Y-m-d'));
            $d->modify('+6 day');
            $this->setEndDate($d->format('Y-m-d'));
            return ($this->getStartDate() . "-" . $this->getEndDate());
        } elseif ($this->getDateFilterType() == 'month') {
            $this->setMonth($this->getMonth() - 1);
            if ($this->getMonth() == 0) {
                $this->setMonth(12);
                $this->setYear($this->getYear() - 1);
            }

            if ($this->getMonth() == 2 && $this->getDay() > 27) {
                $this->setDay(28);
            }
            $this->setDay($this->changeZero($this->getDay()));
            $this->setMonth($this->changeZero($this->getMonth()));
            return $this->getDay() . "-" . $this->getMonth() . "-" . $this->getYear();
        } elseif ($this->getDateFilterType() == 'year') {
            $this->setDay($this->changeZero($this->getDay()));
            $this->setMonth($this->changeZero($this->getMonth()));
            $this->setYear($this->getYear() - 1);
            return $this->getDay() . "-" . $this->getMonth() . "-" . $this->getYear();
        }
        return false;
    }

    /**
     * Return Current Information.Only available for week only
     * @param string $date Date
     * @param string $type Previous,Current,Next
     * @return string
     */
    function getCurrentWeekInfo($date, $type) {
        $diff = null;
        $this->setDateRetrieve($date);
        $this->setDateFilterType($type);

        $dateArray = explode("-", $this->getDateRetrieve());

        $this->setDay($dateArray[0]);
        $this->setMonth($dateArray[1]);
        $this->setYear($dateArray[2]);

        $d = new \DateTime(date("Y-m-d", mktime(0, 0, 0, $this->getMonth(), $this->getDay(), $this->getYear())));
        $weekday = $d->format('w');
        if ($type == 'previous') {
            $diff = ($weekday == 0 ? 6 : $weekday - 1) + 7; // Monday=0, Sunday=6
        } else {
            if ($type == 'current') {
                $diff = ($weekday == 0 ? 6 : $weekday - 1); // Monday=0, Sunday=6
            } else {
                if ($type == 'next' || $type == 'forward') {
                    $diff = ($weekday == 0 ? 6 : $weekday - 1) - 7; // Monday=0, Sunday=6
                }
            }
        }
        $d->modify("-$diff day");
        $this->setStartDate($d->format('d-m-Y'));
        $d->modify('+6 day');
        $this->setEndDate($d->format('d-m-Y'));
        return $this->getStartDate() . ">" . $this->getEndDate();
    }

    /**
     *
     * @param string $dateLoop
     * @param string $type
     * @param string $counter
     * @return string
     */
    function addDate($dateLoop, $type, $counter) {
        for ($i = 0; $i < $counter; $i++) {
            $dateLoop = $this->getForwardDate($dateLoop, $type);
        }
        return $dateLoop;
    }

    /**
     *
     * @param string $dateLoop
     * @param string $type
     * @param string $counter
     * @return string
     */
    function subDate($dateLoop, $type, $counter) {
        for ($i = 0; $i < $counter; $i++) {
            $dateLoop = $this->getPreviousDate($dateLoop, $type);
        }
        if ($counter == -1) {
            $dateLoop = $this->getForwardDate($dateLoop, $type);
        }
        return $dateLoop;
    }

    /**
     * GEt Total Week In Month
     * @param string $date_receive
     * @return int
     */
    function getWeekInMonth($date_receive) {
        $this->day = substr($date_receive, 6, 2);
        $this->month = substr($date_receive, 4, 2);
        $this->year = substr($date_receive, 0, 4);
        $numberOfWeekFirstMonth = date('W', mktime(0, 0, 0, $this->month, '01', $this->year));
        $numberOfWeek = date('W', mktime(0, 0, 0, $this->month, $this->day, $this->year));
        $date_receive = $numberOfWeek - $numberOfWeekFirstMonth + 1;
        return ($date_receive);
    }

    /**
     * Differences Date
     * @param string $date1
     * @param string $date2
     * @return int
     */
    function day($date1, $date2) {
        $checkLengthDate = strlen($date1);
        $totalDayMonth = 0;
        $totalDayYear = 0;
        $day1 = 0;
        $day2 = 0;
        $month1 = 0;
        $month2 = 0;
        $year1 = 0;
        $year2 = 0;
        if ($checkLengthDate == 6) {
            $day1 = substr($date1, 6, 2);
            $month1 = substr($date1, 4, 2);
            $year1 = substr($date1, 2, 2);
            $day2 = substr($date2, 6, 2);
            $month2 = substr($date2, 4, 2);
            $year2 = substr($date2, 2, 2);
        } elseif ($checkLengthDate == 10) {
            $day1 = substr($date1, 8, 2);
            $month1 = substr($date1, 5, 2);
            $year1 = substr($date1, 0, 4);
            $day2 = substr($date2, 8, 2);
            $month2 = substr($date2, 5, 2);
            $year2 = substr($date2, 0, 4);
        }
        $totalYear = $year2 - $year1;
        if ($totalYear > 1) {
            for ($i = 1; $i < $totalYear; $i++) {
                $totalDayYear = $totalDayYear + 365;
            }
        } else {
            $totalDayYear = 0;
        }
        $totalMonth = $month2 - $month1;
        if ($totalMonth > 1) {
            for ($i = $month1++; $i < $month2; $i++) {
                $totalDayMonth = $totalDayMonth + date('j', $i);
            }
        } else {
            $totalDayMonth = 0;
        }
        if ($day1 > $day2) {
            $totalDay = (date('j', $month1) - $day1) + $day2;
        } else {
            $totalDay = $day2 - $day1;
        }
        $totalDay = $totalDay + $totalDayMonth + $totalDayYear;
        return ($totalDay . " day");
    }

    /**
     *
     * @param string $date
     */
    function firstDayWeek($date) {
        $lowEnd = date("w", $date);
        $lowEnd =-$lowEnd;
        $highEnd = $lowEnd + 6;
        $weekday = 0;
        $WeekDate = array();
        for ($i = $lowEnd; $i <= $highEnd; $i++) {
            $WeekDate[$weekday] = date(
                    "Y-m-d", mktime(0, 0, 0, date("m", $date), date("d", $date) + $i + 1, date("Y", $date))
            );
            $weekday++;
        }
        echo $WeekDate[0];
    }

    /**
     * Return Javascript Date
     * @param string $javascriptDate
     * @return int
     */
    function js2PhpTime($javascriptDate) {
        $returnDate = null;
        if (preg_match('@(\d+)/(\d+)/(\d+)\s+(\d+):(\d+)@', $javascriptDate, $matches) == 1) {
            $returnDate = mktime($matches[4], $matches[5], 0, $matches[1], $matches[2], $matches[3]);
            //echo $matches[4] ."-". $matches[5] ."-". 0  ."-". $matches[1] ."-". $matches[2] ."-". $matches[3];
        } else if (preg_match('@(\d+)/(\d+)/(\d+)@', $javascriptDate, $matches) == 1) {
            $returnDate = mktime(0, 0, 0, $matches[1], $matches[2], $matches[3]);
            //echo 0 ."-". 0 ."-". 0 ."-". $matches[1] ."-". $matches[2] ."-". $matches[3];
        }
        return $returnDate;
    }

    function php2JsTime($phpDate) {
        //echo $phpDate;
        //return "/Date(" . $phpDate*1000 . ")/";
        return date("m/d/Y H:i", $phpDate);
    }

    function php2MySqlTime($phpDate) {
        return date("Y-m-d H:i:s", $phpDate);
    }

    function mySql2PhpTime($sqlDate) {
        $arr = date_parse($sqlDate);
        return mktime($arr["hour"], $arr["minute"], $arr["second"], $arr["month"], $arr["day"], $arr["year"]);
    }

    /**
     * Check Valid Date
     * @param array $data
     * @return bool
     */
    function checkDate($data) {
        if (date('Y-m-d', strtotime($data)) == $data) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check Valid Datetime
     * @param array $data
     * @return bool
     */
    function checkDateTime($data) {
        if (date('Y-m-d H:i:s', strtotime($data)) == $data) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param int $value
     * @return \Core\Date\DateClass
     */
    function setDay($value) {
        $this->day = $value;
        return $this;
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
     * @return \Core\Date\DateClass
     */
    function setDayTemp($value) {
        $this->dayTemp = $value;
        return $this;
    }

    /**
     *
     * @return int
     */
    function getDayTemp() {
        return $this->dayTemp;
    }

    /**
     *
     * @param string $value
     * @return \Core\Date\DateClass
     */
    function setWeek($value) {
        $this->week = $value;
        return $this;
    }

    /**
     * Set Week
     * @return string
     */
    function getWeek() {
        return $this->week;
    }

    /**
     * Set Month
     * @param int $value
     * @return \Core\Date\DateClass
     */
    function setMonth($value) {
        $this->month = $value;
        return $this;
    }

    /**
     * Get Month
     * @return int
     */
    function getMonth() {
        return $this->month;
    }

    /**
     * Set Year
     * @param int $value
     * @return \Core\Date\DateClass
     */
    function setYear($value) {
        $this->year = $value;
        return $this;
    }

    /**
     * Return Year
     * @return int
     */
    function getYear() {
        return $this->year;
    }

    /**
     * Set Total Day In Month
     * @param int $value
     */
    function setTotalDayInMonth($value) {
        $this->totalDayInMonth = $value;
    }

    /**
     * Total Day In month
     * @return int
     */
    function getTotalDayInMonth() {
        return $this->totalDayInMonth;
    }

    /**
     * Set Retreive date
     * @param string $value
     */
    function setDateRetrieve($value) {
        $this->dateRetrieve = $value;
    }

    /**
     * GET date retrieve
     * @return string
     */
    function getDateRetrieve() {
        return $this->dateRetrieve;
    }

    /**
     * Set Date Filter Type
     * @param string $value
     */
    function setDateFilterType($value) {
        $this->dateFilterType = $value;
    }

    /**
     * GET Date Filter Type
     * @return string
     */
    function getDateFilterType() {
        return $this->dateFilterType;
    }

    /**
     * Return Start Date
     * @return string
     */
    public function getStartDate() {
        return $this->startDate;
    }

    /**
     * Set Start Date
     * @param string $startDate
     * @return \Core\Date\DateClass
     */
    public function setStartDate($value) {
        $this->startDate = $value;
        return $this;
    }

    /**
     * Return End Date
     * @return
     */
    public function getEndDate() {
        return $this->endDate;
    }

    /**
     * Set End Date
     * @param string $value
     * @return \Core\Date\DateClass
     */
    public function setEndDate($value) {
        $this->endDate = $value;
        return $this;
    }

}

?>
