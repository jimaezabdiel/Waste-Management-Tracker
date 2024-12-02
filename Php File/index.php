<?php

$schedule = [
    'area1' => [
        'recyclables' => 'Monday',
        'organic' => 'Wednesday',
        'general' => 'Friday'
    ],
    'area2' => [
        'recyclables' => 'Tuesday',
        'organic' => 'Thursday',
        'general' => 'Saturday'
    ]
];


function getCollectionDay($area, $waste_type) {
    global $schedule;
    
    
    if (array_key_exists($area, $schedule) && array_key_exists($waste_type, $schedule[$area])) {
        return "Collection day for $waste_type in $area is " . $schedule[$area][$waste_type] . ".";
    } else {
        return "Collection schedule not available.";
    }
}

$area = 'area1';  
$waste_type = 'recyclables';
echo getCollectionDay($area, $waste_type);
?>