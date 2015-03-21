<?php

namespace Guttmann;

use DateTime;

class FinancialYearTest extends \PHPUnit_Framework_TestCase
{

    public function testGetByDatePostJuly()
    {
        $financialYear = FinancialYear::get_by_date(DateTime::createFromFormat('Y-m-d H:i:s', '2013-07-26 15:16:00'));

        $this->checkFinancialYear(2013, $financialYear);
    }

    private function checkFinancialYear($year, FinancialYear $financialYear)
    {
        $this->assertEquals($year . '-07-01 00:00:00', $financialYear->getStartDate()->format('Y-m-d H:i:s'));
        $this->assertEquals(($year + 1) . '-06-30 23:59:59', $financialYear->getEndDate()->format('Y-m-d H:i:s'));
    }

    public function testGetByDatePreJuly()
    {
        $financialYear = FinancialYear::get_by_date(DateTime::createFromFormat('Y-m-d H:i:s', '2013-06-26 15:16:00'));

        $this->checkFinancialYear(2012, $financialYear);
    }

    public function testGetByDateRangePostJuly()
    {
        $financialYears = FinancialYear::get_by_date_range(
            DateTime::createFromFormat('Y-m-d H:i:s', '2013-07-26 15:16:00'),
            DateTime::createFromFormat('Y-m-d H:i:s', '2016-03-26 15:16:00')
        );

        $this->checkFinancialYearRange(3, 2013, $financialYears);
    }

    private function checkFinancialYearRange($expectedNumberOfYears, $startYear, $financialYears)
    {
        $this->assertEquals($expectedNumberOfYears, count($financialYears));

        $endYear = ($startYear + $expectedNumberOfYears);

        for ($i = $startYear; $i < $endYear; $i += 1) {
            $financialYear = array_shift($financialYears);

            $this->checkFinancialYear($i, $financialYear);
        }
    }

    public function testGetByDateRangePreJuly()
    {
        $financialYears = FinancialYear::get_by_date_range(
            DateTime::createFromFormat('Y-m-d H:i:s', '2013-04-22 15:16:00'),
            DateTime::createFromFormat('Y-m-d H:i:s', '2016-07-26 15:16:00')
        );

        $this->checkFinancialYearRange(5, 2012, $financialYears);
    }

    public function testGetByDateRangeOneYear()
    {
        $financialYears = FinancialYear::get_by_date_range(
            DateTime::createFromFormat('Y-m-d H:i:s', '2013-04-22 15:16:00'),
            DateTime::createFromFormat('Y-m-d H:i:s', '2013-04-26 15:16:00')
        );

        $this->checkFinancialYearRange(1, 2012, $financialYears);
    }

}
