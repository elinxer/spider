<?php

$str = 'pickup_city=2314&force_refresh=1&new=1&pickup_landmark=98875&from_date_0=2017-02-02&from_date_1=10%3A00&dropoff_city=2314&dropoff_landmark=98875&to_date_0=2017-02-09&to_date_1=10%3A00';

print_r(explode('&', $str));