<?php
if(!defined('APP')) require_once '../pathBuilder.php';
require_once APP.'datamanager/DataBaseManager.php';
require_once APP.'domain/Line.php';
class Load
{
	public $json = array();

	public function Load($from = 0, $to = null ){
		if(isset($_GET['from'])) $from = round($_GET['from']/1000);
		$this->json['from'] = $from;
		$this->json['to'] = $to;
		if($to == null) $to = time();
		$selection = DataBaseManager::query("SELECT * FROM `lines` WHERE time >= $from and time <= $to ORDER BY id ASC");
		$count = 0;
		while($array = DataBaseManager::fetchArray($selection)){
				$this->json['lines'][$count]['id'] = $array['id'];
				$this->json['lines'][$count]['text'] = $array['text']; 
				$count++;
		}
		$this->json['size'] = DataBaseManager::numRows(DataBaseManager::query("SELECT * FROM `lines`"));
	}
}