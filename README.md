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

### 4- Statistic In Term
