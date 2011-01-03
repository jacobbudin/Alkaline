<?php

class Page extends Alkaline{
	public $page_id;
	public $page_count;
	public $pages;
	
	public function __construct($page=null){
		parent::__construct();
		
		if(!empty($page)){
			$sql_params = array();
			if(is_int($page)){
				$page_id = $page;
				$query = $this->prepare('SELECT * FROM pages WHERE page_id = ' . $page_id . ';');
			}
			elseif(is_string($page)){
				$query = $this->prepare('SELECT * FROM pages WHERE (LOWER(page_title_url) LIKE :page_title_url);');
				$sql_params[':page_title_url'] = '%' . strtolower($page) . '%';
			}
			elseif(is_array($page)){
				$page_ids = convertToIntegerArray($page);
				$query = $this->prepare('SELECT * FROM pages WHERE page_id = ' . implode(' OR page_id = ', $page_ids) . ';');
			}
			
			if(!empty($query)){
				$query->execute($sql_params);
				$this->pages = $query->fetchAll();
				
				$this->page_count = count($this->pages);
			}
		}
	}
	
	public function __destruct(){
		parent::__destruct();
	}
	
	// Perform object Orbit hook
	public function hook($orbit=null){
		if(!is_object($orbit)){
			$orbit = new Orbit;
		}
		
		$this->pages = $orbit->hook('page', $this->pages, $this->pages);
		return true;
	}
	
	public function create(){
		
	}
	
	public function fetchAll(){
		$query = $this->prepare('SELECT * FROM pages;');
		$query->execute();
		$this->pages = $query->fetchAll();
		
		$this->page_count = count($this->pages);
	}
	
	public function search($search=null){
		
	}
	
	public function update($fields){
		$ids = array();
		foreach($this->pages as $page){
			$ids[] = $page['page_id'];
		}
		return parent::updateRow($fields, 'pages', $ids);
	}
}

?>