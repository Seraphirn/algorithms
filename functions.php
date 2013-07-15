<?php

function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function startWithTime(&$func){
	$startTime = microtime_float();
	$func();
	$endTime = microtime_float();
	$duration = $endTime - $startTime;
	printf( "Время выполнения: %f мкс \n", $duration*1000000);
};

?>