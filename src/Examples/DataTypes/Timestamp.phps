<?php

namespace MyLanous\DataTypes;

class Timestamp implements \Lanous\db\Structure\DataType {
    const Query = "timestamp";
    private $data;
    public $Date;
    public function __construct($data) {
        $this->data = $data;
        $this->Date = new Date($data);
    }
    public function Injection($data) { return date("Y-m-d H:i:s",$data); }
    public function Extraction($data) { return strtotime($data); }
    public function Validation($data): bool { return true; }
}

class Date {
    private $data;
    public function __construct($data) { $this->data = strtotime($data); }
    /**
     * Time formatting
     */
    public function Format($date_format) {
        return \date($date_format,$this->data);
    }
    /**
     * calculate the time difference between two timestamps in seconds.
     * If the output is negative, it means that the time recorded in the column has not reached the specified time.
     * @param int $time The timestamp value you want to compare with the time recorded in the column
     * @return int A positive or negative number
     */
    public function Interval(int $time=null) : int {
        $time = ($time == null) ? time() : $time;
        return $time - $this->data;
    }
    /**
     * calculate the time difference between two timestamps in seconds.
     * If the output is negative, it means that the time recorded in the column has not reached the specified time.
     * @param int $time The timestamp value you want to compare with the time recorded in the column
     * @return array An array with columns for Years, Months, Days, Hours, Minutes, Secounds, Exceeded
     */
    public function MakeArray(int $time=null) : array {
        $interval = $this->Interval($time);
        $exceeded = ($interval < 0) ? false : true;
        $interval = ($interval < 0) ? $interval * -1 : $interval;
        $years = floor($interval / 31104000);
        $interval = $interval - (31104000 * $years);
        $months = floor($interval / 2592000);
        $interval = $interval - (2592000 * $months);
        $days = floor($interval / 86400);
        $interval = $interval - (86400 * $days);
        $hours = floor($interval / 3600);
        $interval = $interval - (3600 * $hours);
        $minutes = floor($interval / 60);
        $interval = $interval - (60 * $minutes);
        return ['Years'=>$years,'Months'=>$months,'Days'=>$days,'Hours'=>$hours,'Minutes'=>$minutes,'Secounds'=>$interval,'Exceeded'=>$exceeded];
    }
}