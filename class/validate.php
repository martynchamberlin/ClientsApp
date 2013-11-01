<?

class Validate
{
	public static $errorsArray;

	public static function genVal($data = array())
	{
		foreach ($data as $key)
		{
			if ($_POST[$key] == '')
			{
				self::$errorsArray[$key] = "Please enter text for this field";
			}
		}
		return self::$errorsArray;
  }

	public static function check_ssl()
	{
		$using_ssl = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on';
    if ( ! $using_ssl  )
    {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: https://' . $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
        exit;
  	}
	}
}

?>
