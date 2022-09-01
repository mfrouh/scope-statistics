<?php

namespace MFrouh\ScopeStatistics\Traits;

use Exception;
use Illuminate\Database\Eloquent\Builder;


trait ScopeStatistics
{
    public function ScopeStatisticInDay(Builder $query, $column = 'created_at')
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        if (is_int($column)) {
            throw new Exception('Column Name Must Be String Given ' . $column);
        }

        foreach ($days as $key => $value) {
            $$value = $query->clone()->whereRaw('WEEKDAY(' . $column . ') = ' . $key)->count();
        }

        return compact($days);
    }

    public function ScopeStatisticInTerm(Builder $query, $column = 'created_at', $count_month = 3)
    {
        $statistic_in_term = [];
        $key = 0;

        if (!in_array($count_month, [3, 6])) {
            throw new Exception('Count Month Must Be 3 Or 6 Given ' . $count_month);
        }

        if (is_int($column)) {
            throw new Exception('Column Name Must Be String Given ' . $column);
        }

        for ($i = 1; $i <= 12; $i +=  $count_month) {
            $key++;
            $array = implode("','", range($i, $i + $count_month - 1));
            $statistic_in_term['term' . $key] = $query->clone()->whereRaw("MONTH(" . $column . ") In ('" . $array . "')")->count();
        }

        return $statistic_in_term;
    }

    public function ScopeStatisticInMonth(Builder $query, $column = 'created_at')
    {
        $months = ['january', 'february', 'march', 'april', 'may', 'june', 'july ', 'august', 'september', 'october', 'november', 'december'];

        if (is_int($column)) {
            throw new Exception('Column Name Must Be String Given ' . $column);
        }

        foreach ($months as $key => $value) {
            $$value = $query->clone()->whereMonth($column, $key + 1)->count();
        }

        return compact($months);
    }

    public function ScopeStatisticInHour(Builder $query, $column = 'created_at', $count_hours = 6)
    {
        $current_hour = 0;
        $times = [];

        if ($count_hours > 24 || 24 % $count_hours != 0) {
            throw new Exception('Count Hours Must Be 1, 2, 3, 4, 6, 8, 12 Given ' . $count_hours);
        }

        if (is_int($column)) {
            throw new Exception('Column Name Must Be String Given ' . $column);
        }

        do {
            $times[] = $current_hour >= 10 ? $current_hour . ':00:00' : '0' . $current_hour . ':00:00';
            $times[] = $current_hour >= 10 || ($current_hour + $count_hours - 1) >= 10 ? ($current_hour + $count_hours - 1) . ':59:59' : '0' . ($current_hour + $count_hours - 1) . ':59:59';
            $current_hour += $count_hours;
        } while ($current_hour != 24);



        foreach ($times as $key => $value) {
            if ($key % 2 == 0) {
                $new_key = $value . '-' . $times[$key + 1];
                $time[$new_key] = $query->clone()->whereTime($column, '>=', $value)->whereTime($column, '<=', $times[$key + 1])->count();
            }
        }

        return $time;
    }
}