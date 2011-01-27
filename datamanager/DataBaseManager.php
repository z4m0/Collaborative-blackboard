<?php
if(!defined('APP')){
	require_once '../pathBuilder.php';
}
require_once APP.'datamanager/constants.php';
/**
 *
 * This is the class that manages the data base the only one.
 * Only uses one connection to the database (singleton)
 * @package datamanager
 * @var private _connection
 *
 */
class DataBaseManager
{
	static private $_connection = NULL;

	/**
	 *
	 * Creates a database connection
	 * @param String $server
	 * @param String $user
	 * @param String $password
	 * @param String $dataBaseName
	 */
	static protected  function set_connection($server = "localhost", $user = "root" ,$password = "root" ,$dataBaseName = "test")
	{

		self::$_connection=mysql_connect("$server","$user","$password")or die("Bad connection");

		$db = mysql_select_db("$dataBaseName", self::$_connection) or die ("bad db name");

	}

	/**
	 *
	 * A getter method that returns the connection or creates a new one if not exists
	 */
	static protected function get_connection()
	{
		if(self::$_connection == NULL){
			self::set_connection(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		}
		return self::$_connection;
	}


	/**
	 *
	 * Queries in the database
	 * @param String $query
	 * @return $result
	 */
	static public function query($query)
	{
		$query = DataBaseManager::dbPrefixTables($query);
		$result = mysql_query($query, self::get_connection()) or die($query."  ".mysql_error());

		return $result;
	}

	/**
	 *
	 * Fetches an array from a MySQL query
	 * @param String $query
	 */
	public static function fetchArray($queryResult){
		if($queryResult){
			return mysql_fetch_array($queryResult);
		}
	}

	/**
	 *
	 * Append a database prefix to all tables in a query.
	 * @param String $query
	 * @return String The properly-prefixed string.
	 */
	protected static function dbPrefixTables($query)
	{
		return strtr($query, array('{' => DB_PREFIX, '}' => ''));
	}

	/**
	 *
	 * @param $queryResult
	 * @return int The number of selected rows
	 */
	public static function numRows($queryResult)
	{
		return mysql_num_rows($queryResult);
	}


}

?>