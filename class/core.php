<?php

class Core
{
	public $pdo; // handle of the db connection
	public static $admin = array();

	private static $instance;

	private function __construct()
	{
		// building data source name from config
		$dsn = 'mysql:host=' . Config::read('db.host') .
		';dbname='    . Config::read('db.basename') ;
		// getting DB user from config                
		$user = Config::read('db.user');
		// getting DB password from config                
		$password = Config::read('db.password');
		$this->pdo = new PDO($dsn, $user, $password);
	}

	public static function getInstance()
	{
		if (!isset(self::$instance))
		{
			$object = __CLASS__;
			self::$instance = new $object;
		}
		return self::$instance;
	}

	
	static function isPage($string)
	{
		$pageURL = $_SERVER["REQUEST_URI"];
		$pageURL = explode('?', $pageURL );
		$pageURL = array_shift($pageURL);
		$pageURL = explode('.', $pageURL);
		$pageURL = array_shift($pageURL);
		$pageURL = basename($pageURL);
		if ($pageURL == $string)
			return true;
		return false;
	}

	static function get_page_url()
	{
		$pageURL = $_SERVER["REQUEST_URI"]; 
		$pageURL = explode('?', $pageURL);
		$pageURL = array_shift($pageURL);
		$pageURL = explode('.', $pageURL);
		$pageURL = array_shift($pageURL);
		$pageURL = basename($pageURL);
		 return (empty($pageURL)) ? 'home' : $pageURL;
	}

}

?>
