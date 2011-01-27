<?php
if(!defined('APP')) require_once '../pathBuilder.php';
require_once APP.'datamanager/DataBaseManager.php';

	class Line
	{
		public $json = array();
		
		public function Line($id){
			$code = DataBaseManager::fetchArray(DataBaseManager::query("SELECT text FROM `{lines}` WHERE id =$id"));
			$this->json['id'] = $id;
			$this->json['text'] = $code[0];
		}
		public function modify($id){
			
		}
		private function update($id){
			
		}
		private function insert($id){
			
		}
		
	}
?>