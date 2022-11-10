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

        $one_query = $this->query();
        foreach ($days as $key => $value) {
            $query_sql = $query->clone()->whereRaw('WEEKDAY(' . $column . ') = ' . $key)->selectRaw('count(*)')->toSql();
            $q = vsprintf(str_replace(['?'], ['\'%s\''], $query_sql), $query->getBindings());
            $one_query->selectRaw('(' . $q . ') as ' . $value);
        }

        return $one_query->first()->toArray();
    }

    public function ScopeStatisticInTerm(Builder $query, $column = 'created_at', $count_month = 3)
    {
        $key = 0;

        if (!in_array($count_month, [3, 6])) {
            throw new Exception('Count Month Must Be 3 Or 6 Given ' . $count_month);
        }

        if (is_int($column)) {
            throw new Exception('Column Name Must Be String Given ' . $column);
        }

        $one_query = $this->query();
        for ($i = 1; $i <= 12; $i +=  $count_month) {
            $key++;
            $array = implode("','", range($i, $i + $count_month - 1));
            $query_sql = $query->clone()->whereRaw("MONTH(" . $column . ") In ('" . $array . "')")->selectRaw('count(*)')->toSql();
            $q = vsprintf(str_replace(['?'], ['\'%s\''], $query_sql), $query->getBindings());
            $one_query->selectRaw('(' . $q . ') as ' . 'term' . $key);
        }

        return $one_query->first()->toArray();
    }

    public function ScopeStatisticInMonth(Builder $query, $column = 'created_at')
    {
        $months = ['january', 'february', 'march', 'april', 'may', 'june', 'july ', 'august', 'september', 'october', 'november', 'december'];

        if (is_int($column)) {
            throw new Exception('Column Name Must Be String Given ' . $column);
        }

        $one_query = $this->query();
        foreach ($months as $key => $value) {
            $new_key = $key + 1;
            $query_sql = $query->clone()->whereRaw("MONTH(" . $column . ") = $new_key")->selectRaw('count(*)')->toSql();
            $q = vsprintf(str_replace(['?'], ['\'%s\''], $query_sql), $query->getBindings());
            $one_query->selectRaw('(' . $q . ') as ' . $value);
        }

        return $one_query->first()->toArray();
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



        $one_query = $this->query();
        $init_key = 0;
        $array_keys = [];
        foreach ($times as $key => $value) {
            if ($key % 2 == 0) {
                $end_time = $times[$key + 1];
                $new_key = 'key' . $init_key;
                $array_keys[$new_key] = $value . "-" . $end_time;
                $query_sql = $query->clone()->whereRaw("time(" . $column . ") >= '$value' and time(" . $column . ") <= '$end_time'")->selectRaw('count(*)')->toSql();
                $q = vsprintf(str_replace(['?'], ['\'%s\''], $query_sql), $query->getBindings());
                $one_query->selectRaw('(' . $q . ') as ' . $new_key);
                $init_key++;
            }
        }

        return array_combine($array_keys, $one_query->first()->toArray());
    }
}
