<?php
	include_once('../structures.php');
	include_once('../algorithms.php');
	include_once('../functions.php');

	$lol = function() {
		$list = new DynamicList();
		$item1 = new ListItem('First');
		$item2 = new ListItem('Second');
		$item3 = new ListItem('Third');
		$list->insert($item1,1);
		$list->insert($item2,1);		
		$list->insert($item3,2);
		$list->delete('Third');
		print_r($list->show());
		unset($list);
	};
	startWithTime($lol);
	//print_r($list->show());

?>