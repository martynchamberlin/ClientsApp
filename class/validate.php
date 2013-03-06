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
}

?>
