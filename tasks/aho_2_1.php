<?php
	include_once('../structures.php');
	include_once('../algorithms.php');
	include_once('../functions.php');

	
	$lol = function() {
		$list = new DynamicList();
		$list->insert('Item');
		unset($list);
	};
	startWithTime($lol, 100000);

	//print_r($list->show());

?>