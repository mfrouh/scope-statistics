## ScopeStatistics

#### Is Package Used In model To Get Count Data In Day , Hour , Month , Term Using Scope

#

#### Add Trait in Your Model

<br/>

```php

use MFrouh\ScopeStatistics\Traits\ScopeStatistics;

class Order extends Model
{
    use ScopeStatistics;

}

```

#

### Package Has 4 Methods

#

### 1- Statistic In Day

```php

 // by default using column 'created_at'

 $statistic_in_day = Order::statisticInDay();


 // or can use custom column

 $statistic_in_day = Order::statisticInDay('updated_at');

 $cancelled_statistic_in_day = Order::where('status','cancelled')->statisticInDay();

// response will be with 100 Order

 $statistic_in_day =
 [
     "monday" => 8,
     "tuesday" => 16,
     "wednesday" => 13,
     "thursday" => 16,
     "friday" => 12,
     "saturday" => 18,
     "sunday" => 17,
 ]

```

### 2- Statistic In Hour

```php

 // by default using column 'created_at'
 // by default using count_hours 6
 // count_hours can be  1, 2, 3, 4, 6, 8, 12

 // every 6 hours
 $statistic_in_hour = Order::statisticInHour();

 // every 3 hours
 $statistic_in_hour = Order::statisticInHour('created_at',3);


 // or can use custom column

 $statistic_in_hour = Order::statisticInHour('updated_at');

 $cancelled_statistic_in_hour = Order::where('status','cancelled')->statisticInHour();

// response will be with 100 Order

$statistic_in_hour =
[
    "00:00:00-05:59:59" => 34,
    "06:00:00-11:59:59" => 17,
    "12:00:00-17:59:59" => 28,
    "18:00:00-23:59:59" => 21,
]

```

### 3- Statistic In Month

```php

 // by default using column 'created_at'

 $statistic_in_month = Order::statisticInMonth();


 // or can use custom column

 $statistic_in_month = Order::statisticInMonth('updated_at');

 $cancelled_statistic_in_month = Order::where('status','cancelled')->statisticInMonth();

// response will be with 100 Order

 $statistic_in_month =
 [
     "january" => 12,
     "february" => 7,
     "march" => 10,
     "april" => 12,
     "may" => 13,
     "june" => 5,
     "july " => 8,
     "august" => 6,
     "september" => 7,
     "october" => 6,
     "november" => 6,
     "december" => 8,
 ]

```

### 4- Statistic In Term

```php

 // by default using column 'created_at'
 // by default using count_months 3
 // count_months can be 3,6
 // term1 in 3 month will data in january , february , march

 // every 3 month
 $statistic_in_term = Order::statisticInTerm();

 // every 6 month
 $statistic_in_term = Order::statisticInTerm('created_at',6);


 // or can use custom column

 $statistic_in_term = Order::statisticInTerm('updated_at');

 $cancelled_statistic_in_term = Order::where('status','cancelled')->statisticInTerm();

// response will be with 100 Order

$statistic_in_term =
[
     "term1" => 29,
     "term2" => 30,
     "term3" => 21,
     "term4" => 20,
]

```

### 5- Statistic In One Query

```php

 $array = [
     'completed_order' => Order::where('status', 'completed'),
     'cancelled_order' => Order::where('status', 'cancelled'),
 ];

 $statistic_in_one_query = Order::statisticInTerm($array);


// response will be 100 Order

$statistic_in_one_query =
[
  "completed_order" => 0
  "cancelled_order" => 0

]

```
