<?php
	include_once('../structures.php');
	include_once('../algorithms.php');

	$teams = [
		new Vertice('Соколы'),
		new Vertice('Львы'),
		new Vertice('Орлы'),
		new Vertice('Бобры'),
		new Vertice('Тигры'),
		new Vertice('Скунсы')];
	$teamstable = new Graph($teams);
	$teamstable->setEdge('Соколы', 'Львы');
	$teamstable->setEdge('Соколы', 'Орлы');
	$teamstable->setEdge('Львы', 'Бобры');
	$teamstable->setEdge('Львы', 'Скунсы');
	$teamstable->setEdge('Тигры', 'Орлы');
	$teamstable->setEdge('Тигры', 'Скунсы');

	$teammatch = [];
	for ($i=0; $i<$teamstable->count; $i++) {
		for ($j=$i; $j<$teamstable->count; $j++) {
			list($teami, $teamj) = [$teamstable->getVertice($i)->name, $teamstable->getVertice($j)->name];
			if ($teamstable->hasEdge($teami, $teamj)) {
				$teammatch[] = new Vertice($name=$teami.'-'.$teamj);
			}
		};
	};

	$gteammatch = new Graph($teammatch);

	for ($i=0; $i<$gteammatch->count; $i++) {
		for ($j=$i+1; $j<$gteammatch->count; $j++) {
			$iteams = split('-', $gteammatch->getVertice($i)->name);			
			$jteams = split('-', $gteammatch->getVertice($j)->name);
			$diff = array_intersect($iteams, $jteams);
			if (!empty($diff)) {
				$gteammatch->setEdge($gteammatch->getVertice($i)->name, $gteammatch->getVertice($j)->name);
			}
		};
	};

	$count_weeks = trafficlight($gteammatch);
	print_r($count_weeks);
	

?>