<?php
require_once 'pathBuilder.php';
require_once APP.'datamanager/constants.php';
require_once APP.'domain/Load.php';
require_once APP.'domain/Updater.php';

class Principal
{
	public static function index()
	{

		if(isset($_GET['send'])){
			$ajax = new Updater();	
				
		}else if(isset($_GET['wall'])){
			$wall = new Load();
			echo json_encode($wall->json);
		}else
		{
			include_once 'presentation/wallpresentation.html';
		}
		
	}


}

Principal::index();