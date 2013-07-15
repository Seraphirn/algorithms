<?php
class Vertice {
	public $name;
	private $parameters;

	function __construct($name, $parameters=[]) {
		$this->name = $name;
		$this->parameters = $parameters;
	}

	public function setParameter($var, $name) {
		$this->parameters[$var] = $name;
	}

	public function getParameter($var) {
		if (isset($this->parameters[$var])) {
			return $this->parameters[$var];
		} else {
			return NULL;
		};
	}

	public function show() {
		return $this->name;
	}
};

class Graph {
	public $vertices = [];
	public $edges = [];
	public $count = 0;
	

	function __construct($vertices) {
		$this->vertices = $vertices;
		$this->count = count($vertices);
		for ($i=0; $i<$this->count; $i++) {
			for ($j=$i; $j<$this->count; $j++) {
				$this->edges[$i][$j] = 0;
				$this->edges[$j][$i] = 0;
			};
		};
	}

	public function getVertice($index) {
		return $this->vertices[$index];
	}

	function getIndex($name) {
		for ($i=0; $i<$this->count; $i++) {
			if ($this->getVertice($i)->name == $name) {
				return $i;
			};
		};
		return -1;
	}

	public function setEdge($vi, $vj, $symmetric=true) {
		$this->setEdgeWeight($vi, $vj, 1, $symmetric);
	}

	public function removeEdge($vi, $vj, $symmetric=true) {
		$this->setEdgeWeight($vi, $vj, 0, $symmetric);
	}

	public function setEdgeWeight($vi, $vj, $weight, $symmetric=true) {
		list($i, $j) = [$this->getIndex($vi),  $this->getIndex($vj)];
		$this->edges[$i][$j] = $weight;
		if ($symmetric == true) {
			$this->edges[$j][$i] = $weight;
		};
	}

	public function getEdgeWeight($vi, $vj) {
		list($i, $j) = [$this->getIndex($vi),  $this->getIndex($vj)];
		return $this->edges[$i][$j];
	}	

	public function hasEdge($vi, $vj) {
		return ($this->getEdgeWeight($vi, $vj) != 0);
	}

};

class ListItem extends Vertice{
	private $next;
	private $prev;
	
	function __construct($name, $parameters=[]) {
		parent::__construct($name, $parameters);
		$this->next = NULL;
		$this->prev = NULL;			
	}

	public function next(){
		return $this->next;
	}

	public function prev(){
		return $this->prev;
	}

	public function setNext($next){
		$this->next = $next;
	}

	public function setPrev($prev){
		$this->prev = $prev;
	}

	


};

abstract class BaseList {
	abstract public function retrive($index);
	abstract public function locate($name);
	abstract public function insert(&$item, $index=NULL);
	abstract public function delete($input);
	abstract public function show();

};

# List made from array
class ArrayList extends BaseList {
	private $count;
	private $array;

	function __construct() {
		$this->count = 0;
		$this->array = [];
	}

	function __destruct() {
		for($i=0; $i<$this->count; $i++) {
			unset($this->array[$i]);
		};
		unset($this->count);
		unset($this->array);
	}

	public function retrive($index) {		
		return $this->array[$index-1];
	}

	public function locate($name) {
		for($i=0; $i<$this->count; $i++) {
			if (strcmp($this->array[$i]->name, $name) == 0) {
				return $i+1;
			};
		};
		return $i+1;
	}

	public function insert(&$item, $input=NULL) {
		switch (gettype($input)) {
			case 'string':
				$index = $this->locate($input);
				break;
			case 'integer':
				$index = $input;
				break;
			case 'NULL':
				$index = $this->count+1;
				break;
			default:
				return false;
		};
		$replaceItem = &$item;		
		for ($i=$index-1; $i<$this->count; $i++) {
			$tmpItem = &$this->array[$i];
			$this->array[$i] = &$replaceItem;
			$replaceItem = &$tmpItem;
		};
		$this->array[$i] = &$replaceItem;
		$this->count++;		
	}

	public function delete($input) {
		
		switch (gettype($input)) {
			case 'string':
				$index = $this->locate($input);
				break;
			case 'integer':
				$index = $input;
				break;
			default:
				return false;
		}
		
		if (($index < 1) or ($index > $this->count)) {
			return false;
		};
		$delItem = $this->retrive($index);		
		for ($i=$index-1; $i<$this->count-1; $i++) {
			$this->array[$i] = &$this->array[$i+1];
		};

		unset($this->array[$i+1]);
		unset($delItem);
		$this->count--;		
	}

	public function show() {
		$str = sprintf("[%4s]%7s| \n", 'N', 'name');
		for($i=0; $i<$this->count; $i++) {
			$str .= sprintf("[%4d]%7s| \n", $i+1, $this->array[$i]->name);
		};
		$str .= sprintf("\n");
		return $str;
	}

};

# List of pointers
class DynamicList extends BaseList {
	private $head;
	private $tail;
	private $count;

	function __construct() {
		$this->head = NULL;
		$this->tail = NULL;
		$this->count = 0;
	}

	function __destruct() {
		
		$tmpItem = $this->head;
		while (!is_null($tmpItem)) {
			$delItem = &$tmpItem;
			$tmpItem = $tmpItem->next();
			unset($delItem);
		};

		unset($this->head);
		unset($this->tail);
		unset($this->count);
	}

	public function retrive($index) {
		$tmpItem = $this->head;
		for ($i=1; $i<$index; $i++) {
			
			if (is_null($tmpItem)) {

				return NULL;
			};
			$tmpItem = $tmpItem->next();
		};
		return $tmpItem;
	}

	public function locate($name) {
		$tmpItem = $this->head;
		$index = 1;
		while ($tmpItem != NULL) {
			if (strcmp($tmpItem->name, $name) == 0) {
				return $index;
			};
			$tmpItem = $tmpItem->next();
			$index++;
		};
		return $index;
	}

	public function insert(&$item, $index=NULL) {
		
		switch (gettype($item)) {
			case 'string':
				$item = new ListItem($item);
				break;
			case 'object':
				break;
			default:
				return false;
		}

		if (is_null($index)) {
			$index = $this->count+1;
		};

		if (is_null($this->head)) {			
			$this->head = $item;
			$this->tail = $item;
		} else if ($index == 1) {
			$item->setNext($this->head);
			$this->head->setPrev($item);
			$this->head = &$item;
		} else if ($index == $this->count+1) {
			$item->setPrev($this->tail);
			$this->tail->setNext($item);
			$this->tail = &$item;
		} else {			
			$oldItem = $this->retrive($index);		
			$item->setNext($oldItem);
			$item->setPrev($oldItem->prev());
			$oldItem->prev()->setNext($item);
			$oldItem->setPrev($item);
		};
		$this->count++;		
	}

	public function delete($input) {
		
		switch (gettype($input)) {
			case 'string':
				$index = $this->locate($input);
				print($index);
				break;
			case 'integer':
				$index = $input;
				break;
			default:
				return false;
		}
		
		if (($index < 1) or ($index > $this->count)) {
			return false;
		};
		$delItem = $this->retrive($index);		
		if ($delItem === $this->head) {			
			$this->head = $delItem->next();
			$this->head->setPrev(NULL);
		} else if ($delItem === $this->tail) {
			$this->tail = $delItem->prev();
			$this->tail->setNext(NULL);
		} else {			
			$delItem->prev()->setNext($delItem->next());
			$delItem->next()->setPrev($delItem->prev());
		};
		unset($delItem);
		$this->count--;		
	}

	public function show() {
		$tmpItem = $this->head;
		$i = 1;
		$str = sprintf("[%4s]%7s|%7s|%7s| \n", 'N', 'name', 'prev', 'next' );
		$str .= sprintf("[%4s]%7s|%7s|%7s| \n", 'HEAD', $this->f($this->head), $this->f($this->head->prev()), $this->f($this->head->next()));
		while ($tmpItem != NULL) {
			$str .= sprintf("[%4d]%7s|%7s|%7s| \n", $i++, $this->f($tmpItem), $this->f($tmpItem->prev()), $this->f($tmpItem->next()));
			$tmpItem = $tmpItem->next();
		};
		$str .= sprintf("[%4s]%7s|%7s|%7s| \n\n", 'TAIL', $this->f($this->tail), $this->f($this->tail->prev()), $this->f($this->tail->next()));
		return $str;
	}

	# formating item
	static function f($item) {
		if (!is_null($item)) {
			return $item->show();
		} else {
			return 'NULL';
		};
	}


};

?>