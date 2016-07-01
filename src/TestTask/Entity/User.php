<?php
/**
 * Created by PhpStorm.
 * User: darke_000
 * Date: 29.06.2016
 * Time: 21:32
 */

namespace TestTask\Entity;


class User
{
    private $userId;
    private $hold;
    private $earned = array();
    private $paid = array();

    public $totalEarned;
    public $totalPaid;
    public $eightPeriods;
    public $balance;
    public $payNow;
    public $payNext;


    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setHold($hold)
    {
        $this->hold = $hold;
    }

    public function setEarned($earned)
    {
        $this->earned = $earned;
    }

    public function setPaid($paid)
    {
        $this->paid = $paid;
    }

    public function calcOutput()
    {
        $this->calculateTotalEarned();
        $this->calculateTotalPaid();
        $this->calculateEightPeriods();
        $this->calculateBalance();
        $this->calculatePayNext();
        $this->calculatePayNow();
    }

    private function calculateTotalEarned()
    {
        foreach ($this->earned as $earning) {
            $this->totalEarned += $earning['earned'];
        }
    }

    private function calculateTotalPaid()
    {
        foreach ($this->paid as $paiment)
            $this->totalPaid += $paiment;
    }

    private function calculateEightPeriods()
    {
        $earned = array_reverse($this->earned);
        for ($i=0; $i<8; $i++)
            if ($earned[$i]['earned'] !== null)
                $eightPeriods[$i] = $earned[$i]['earned'];
            else break;
        $eightPeriods = array_reverse($eightPeriods);
        foreach ($eightPeriods as $period)
            $this->eightPeriods.=$period.' ';
    }

    private function calculateBalance()
    {
        $this->balance = $this->totalEarned - $this->totalPaid;
    }

    private function calculatePayNow()
    {
        $payday = date('Y-m').'-01';
        $payday2 = date('Y-m').'-16';
        $currentDate = date('Y-m-d');
        $prevM =  strftime('%m', strtotime('first day of previous month'));

        if ($this->hold == 1) {
            if ($currentDate == $payday)
                $this->payNow = $this->earned[$this->searchForDate(date('Y-') . $prevM . '-16', $this->earned)]['earned'];
            else if ($currentDate == $payday2)
                $this->payNow = $this->earned[$this->searchForDate(date('Y-m') . '-01', $this->earned)]['earned'];
        } elseif ($this->hold == 2){
            if ($currentDate == $payday)
                $this->payNow = $this->earned[$this->searchForDate(date('Y-') . $prevM . '-01', $this->earned)]['earned'];
            else if ($currentDate == $payday2)
                $this->payNow = $this->earned[$this->searchForDate(date('Y-') . $prevM. '-16', $this->earned)]['earned'];
        }
    }


    private function calculatePayNext()
    {
        $currentDay = date('d');
        $prevM =  strftime('%m', strtotime('first day of previous month'));
        $lastDayOfMonth = date('t');

        if ($this->hold == 1) {
            if (($currentDay > 1) AND ($currentDay < 16))
                $this->payNext = $this->earned[$this->searchForDate(date('Y-m') . '-01', $this->earned)]['earned'];
            else if (($currentDay > 15) AND ($currentDay <= $lastDayOfMonth))
                $this->payNext = $this->earned[$this->searchForDate(date('Y-m') . '-16', $this->earned)]['earned'];
        } elseif ($this->hold == 2) {
            if (($currentDay > 1) AND ($currentDay < 16))
                $this->payNext = $this->earned[$this->searchForDate(date('Y-') . $prevM . '-16', $this->earned)]['earned'];
            else if (($currentDay > 15) AND ($currentDay <= $lastDayOfMonth))
                $this->payNext = $this->earned[$this->searchForDate(date('Y-m') . '-01', $this->earned)]['earned'];
        }
    }

    function searchForDate($date, $array) {
        foreach ($array as $key => $val) {
            if ($val['date'] === $date) {
                return $key;
            }
        }
        return null;
    }
}