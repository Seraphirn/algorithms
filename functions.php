<?php

function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function startWithTime(&$func, $n=1, $progressbar=true){
	if ($progressbar==true && $n>=10) {
		$progressbar=true;
	} else {
		$progressbar=false;
	};
	$duration = 0;
	if ($progressbar) {
		printf("[");
	};
	for ($i=0; $i<$n; $i++) {
		if ($progressbar) {
			if ($i % round($n/10) == 0 )  {
				printf("%d", ($i/$n)*100);
			} elseif ($i % round($n/50) == 0 ) {
				printf("-");
			};

		};
		$startTime = microtime_float();
		$func();
		$endTime = microtime_float();
		$duration += $endTime - $startTime;
	};
	$duration/=$n;
	if ($progressbar) {
		printf("100]\n");
	};
	printf( "Среднее время выполнения %d раз : %f мкс \n", $n, $duration*1000000);
};

?>