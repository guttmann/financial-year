<?php

namespace Guttmann;

use \DateTime;

class FinancialYear
{

    /**
     * @var DateTime
     */
    private $startDate;

    /**
     * @var DateTime
     */
    private $endDate;

    public function __construct(DateTime $startDate, DateTime $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Get the FinancialYear that contains the given $date
     *
     * @param DateTime $date
     * @return FinancialYear
     */
    public static function get_by_date(DateTime $date)
    {
        $month = $date->format('n');
        $year = $date->format('Y');

        if ($month <= 6) {
            $startYear = $year - 1;
        } else {
            $startYear = $year;
        }

        $startDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $startYear . '-07-01 00:00:00');
        $endDateTime = DateTime::createFromFormat('Y-m-d H:i:s', ($startYear + 1) . '-06-30 23:59:59');

        return new FinancialYear($startDateTime, $endDateTime);
    }

    /**
     * Get an array of FinancialYears that encompass the $rangeStart and $rangeEnd dates
     *
     * @param DateTime $rangeStart
     * @param DateTime $rangeEnd
     * @return FinancialYear[]
     */
    public static function get_by_date_range(DateTime $rangeStart, DateTime $rangeEnd)
    {
        $startFinancialYear = self::get_by_date($rangeStart);
        $endFinancialYear = self::get_by_date($rangeEnd);

        if ($startFinancialYear > $endFinancialYear) {
            throw new \Exception('End date is before start date');
        }

        $startYear = $startFinancialYear->getStartDate()->format('Y');
        $endYear = $endFinancialYear->getStartDate()->format('Y');

        $financialYears = array();

        for ($i = $startYear; $i <= $endYear; $i += 1) {
            $financialYears[] = self::get_by_date(
                DateTime::createFromFormat('Y-m-d H:i:s', $i . '-07-01 00:00:00')
            );
        }

        return $financialYears;
    }

}
