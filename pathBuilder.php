<?php
/**
 * This file creates paths the app root it must be included for all the files
 * that include some other file.
 * And requires some constant files
 */
if(!defined('APP')){
	define('APP',$_SERVER[DOCUMENT_ROOT]."/documents/pissarra/");//The project root relative to the server
}

?>