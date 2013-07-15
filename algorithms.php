<?php
include_once('structures.php');

function mysort(&$array, $method='bubble'){
	switch($method){
		case 'bubble':
			$n = count($array);
			for ($i=0; $i<$n-1; $i++){ // 1..n-1 == 0..n-2
				for ($j=$n-1; $j>$i; $j--){
					if ($array[$j-1] > $array[$j]){
						$temp = $array[$j-1];
						$array[$j-1] = $array[$j];
						$array[$j] = $temp;
					};
				};
			};
			break;
	};
	return false;
};

function greedy(&$graph, $color_id, &$color_arr) {
	$color_arr = [];
	for ($i=0; $i<$graph->count; $i++) {
		if (is_null($graph->getVertice($i)->getParameter('color'))) {
			$found = true;
			for ($j=0; $j<count($color_arr); $j++) {
				if ($graph->hasEdge($graph->getVertice($i)->name, $color_arr[$j])) {
					$found = false;
				};
			};
			if ($found) {
				$color_arr[] = $graph->getVertice($i)->name;
				$graph->getVertice($i)->setParameter('color', $color_id);
			};
		};
	}
};

function trafficlight(&$graph) {
	$color_id = 0;
	$color_arr = [];
	while (count($color_arr) < $graph->count) {
		$color_id += 1;
		$tmp_arr = [];
		greedy($graph, $color_id, $tmp_arr);
		$color_arr = array_merge($color_arr, $tmp_arr);		
	};
	return $color_id;
}
?>