<?php
class ajaxHandler
{
	public $time;
	public $changes = array();
	private $prov = array();

	public function ajaxHandler(){
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
		DataBaseManager::query("DELETE FROM `lines` WHERE id > ".$this->changes['size']);

		if(isset($this->changes['loc'][0])){
			foreach($this->changes['loc'] as $key => $value){
				echo $value.'<br>';
				if($value > 0){
					$this->prov[] = DataBaseManager::fetchArray(DataBaseManager::query("SELECT text FROM `lines` WHERE id =".($value+1)));
					DataBaseManager::query("DELETE FROM `lines` WHERE id = ".($value+1));
				}
				echo 'prov<br>';
				print_r($this->prov);
			}
		}
		echo 'prov:  ';
		print_r($this->prov);
		$cont = 0;
		if(isset($this->changes['lines'][0])){

			foreach ($this->changes['lines'] as $key => $value){
				if($value == "null"){echo 'key:'.$this->changes['id'][$key];
				DataBaseManager::query("DELETE FROM `lines` WHERE id = ".($value+1));
				DataBaseManager::query("INSERT INTO `lines` (`id`, `text`, `time`) VALUES ('".($this->changes['id'][$key]+1)."', '".$this->prov[$cont][0]."', '".$this->time."')");
				$cont ++;
				}else{
					//if($value == "null" || $value == null) $value = "";
						
					if(DataBaseManager::numRows(DataBaseManager::query("SELECT id FROM `lines` WHERE `id`=".($this->changes['id'][$key]+1))) > 0){
						DataBaseManager::query("UPDATE `lines` SET `text`='$value' , `time` ='".$this->time."' WHERE `id`=".($this->changes['id'][$key]+1));
					}else{
						DataBaseManager::query("INSERT INTO `snipets`.`lines` (`id`, `text`, `time`) VALUES ('".($this->changes['id'][$key]+1)."', ' $value', '".$this->time."')");

					}
				}
			}
		}
	}
}