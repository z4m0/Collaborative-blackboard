<?php
class Updater
{
	public $time;
	public $changes = array();
	private $prov = array();

	public function Updater(){
		if(isset($_GET['send'])){
			foreach ($_POST as $key => $value){
				echo $key.' '.print_r($value);
				switch($key){
					case "from":
						$this->time = round($value/1000);
						break;
					case "id":
						$this->changes['id'] = $value;
						break;
					case "loc":
						$this->changes['loc'] = $value;
						break;
					case "lines":
						$this->changes['lines'] = $value;
						break;
					case "size":
						$this->changes['size'] = $value;
						break;
				}
			}
			$this->store();
		}
	}

	private function store(){
		if(isset($this->changes['loc'][0])){
			foreach($this->changes['loc'] as $key => $value){
				if($value > 0){
					$this->prov[] = DataBaseManager::fetchArray(DataBaseManager::query("SELECT text FROM `lines` WHERE id =".($value+1)));
				}
			}
		}
		$cont = 0;
		if(isset ($this->changes['lines'][0])){
			foreach ($this->changes['lines'] as $key => $value){
				//delete ids
				DataBaseManager::query("DELETE FROM `lines` WHERE id = ".($this->changes['id'][$key]+1));
				echo 'id: '.($this->changes['id'][$key]+1).'\n';
				if($value == "null"){
					//insert locs
					DataBaseManager::query("INSERT INTO `lines` (`id`, `text`, `time`) VALUES ('".($this->changes['id'][$key]+1)."', '".$this->prov[$cont][0]."', '".$this->time."')");
					$cont ++;
				}else{
					//insert lines
					DataBaseManager::query("INSERT INTO `lines` (`id`, `text`, `time`) VALUES ('".($this->changes['id'][$key]+1)."', '$value', '".$this->time."')");
				}
			}
		}
		DataBaseManager::query("DELETE FROM `lines` WHERE id > ".$this->changes['size']);
	}
}