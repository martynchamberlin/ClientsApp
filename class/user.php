<?
class User
{

	// Tired of having one variable hold all 3 of these potential values, 
	// So I'm making them separate. 
	static $user_info;
	static $bool_login;
	static $login_error_message = array();
	static $create_error_message = array();

	static function color_scheme( $color )
	{
		return isset($_SESSION['loggedIn']['color_scheme'])	&& $_SESSION['loggedIn']['color_scheme'] == $color;
	}
	
	static function get_color_scheme()
	{
		if (isset($_SESSION['loggedIn']['color_scheme']))
		{
			return $_SESSION['loggedIn']['color_scheme'];
		}
		else 
		{
			return "default";
		}
	}
	// for when they're first creating their account
	static function sanitize(& $data)
	{
		$errors = array();
	
		foreach ( $data as $key => $value )
		{
			$data[$key] = trim( $value );
		}

		if ( empty( $data['first_name'] ) )
		{
			$errors['first_name'] = 'Please provide your first name.';
		}

		if ( empty( $data['last_name'] ) )
		{
			$errors['last_name'] = 'Please provide your last name.';
		}

		if ( ! filter_var($data['email'], FILTER_VALIDATE_EMAIL) )
		{
			$errors['email'] = "Please provide a valid email address.";
		}

		else if ( self::db_contains_email( $data['email'] ) )
		{
			$errors['email'] = "Email address already registered.";
		}

		if ( strlen( $data['password'] ) < 8 )
		{
			$errors['password'] = "Password must be 8 characters or greater.";
		}

		self::$create_error_message = $errors;

		return $errors;

	}
	// for when they're updating their account
	static function sanitize_updates(& $data)
	{
		$errors = array();
	
		foreach ( $data as $key => $value )
		{
			$data[$key] = trim( $value );
		}

		if ( empty( $data['first_name'] ) )
		{
			$errors['first_name'] = 'Please provide your first name.';
		}

		if ( empty( $data['last_name'] ) )
		{
			$errors['last_name'] = 'Please provide your last name.';
		}

		if ( ! filter_var($data['email'], FILTER_VALIDATE_EMAIL))
		{
			$errors['email'] = "Please provide a valid email address.";
		}

		else if ( self::db_contains_email( $data['email'] ) && $data['email'] != $_SESSION['loggedIn']['email'] )
		{
			$errors['email'] = "Email address already registered.";
		}

		if ( strlen( $data['password'] ) > 0 && strlen( $data['password'] ) < 8  )
		{
			$errors['password'] = "Password must be 8 characters or greater.";
		}
		
		else if (strlen( $data['password'] ) >= 8 )
		{
			$data['password'] = md5($data['password'] );
		}

		else if ( empty( $data['password'] ) )
		{
			 $data['password'] = $_SESSION['loggedIn']['password'];
		}

		self::$create_error_message = $errors;

		return $errors;

	}



	static function create($data)
	{
		// don't forget, $data is passed by reference.
		$errors = self::sanitize($data);

		if (empty($errors) )
		{
			$core = Core::getInstance();
			$pdo = $core->pdo;
			$sql = 'INSERT INTO users
			SET first_name = :first_name,
			last_name = :last_name,
			email = :email,
			creation = '. time() . ', 
			password = :password';
			$s = $pdo->prepare($sql);
			$s->bindValue(':email', $data['email']);
			$s->bindValue(':password', md5($data['password']));
			$s->bindValue(':last_name', $data['last_name']);
			$s->bindValue(':first_name', $data['first_name']);

			$s->execute();
			return true;
		}
	}

	static function delete($data)
	{
		$password = md5($data['password']);
		$email = $_SESSION['loggedIn']['email'];
		$userID = $_SESSION['loggedIn']['userID'];

		$core = Core::getInstance();
		$pdo = $core->pdo;
		$sql = 'SELECT * FROM users
			WHERE email = :email AND password = :password AND userID = :userID';
		$s = $pdo->prepare($sql);
		$s->bindValue(':email', $email);
		$s->bindValue(':userID', $userID);
		$s->bindValue(':password', $password);
		$s->execute();
		// Russell would utterly kill me
		if ( $s->rowCount() > 0 ) 
		{
			$sql = 'DELETE U, E, L, Ta, Ti, C FROM users U
				LEFT JOIN expenses E ON E.userID = U.userID
				LEFT JOIN lookup L on L.userID = U.userID
				LEFT JOIN tasks Ta on Ta.userID = U.userID
				LEFT JOIN times Ti on Ti.userID = U.userID
				LEFT JOIN clients C on C.userID = U.userID
			WHERE U.userID = :userID';
			$s = $pdo->prepare($sql);
			$s->bindValue(':userID', $userID);
			$s->execute();
			session_destroy();
			header('Location: /');
			exit;
		}
		else
		{
			self::$login_error_message['password'] = "That's not your password!";
		}
	}


	static function update($data)
	{
		$errors = self::sanitize_updates($data);

		if (empty($errors) )
		{
			$core = Core::getInstance();
			$pdo = $core->pdo;
			$sql = 'UPDATE users
			SET first_name = :first_name,
			last_name = :last_name,
			email = :email,
			password = :password,
			color_scheme = :color_scheme
			WHERE email = :formerEmail';
			$s = $pdo->prepare($sql);
			$s->bindValue(':email', $data['email']);
			$s->bindValue(':password', $data['password']);
			$s->bindValue(':color_scheme', $data['color_scheme']);
			$s->bindValue(':last_name', $data['last_name']);
			$s->bindValue(':first_name', $data['first_name']);
			$s->bindValue(':formerEmail', $_SESSION['loggedIn']['email']);
			$s->execute();
			foreach ($data as $key=>$value)
			{
				$_SESSION['loggedIn'][$key] = $value;
			}
			return true;
		}
		return false;
	}		

	static function logged_in()
	{

		if ( !isset( self::$bool_login ) )
		{

			if (isset($_POST['login']))
			{
			
				$email = $_POST['email'];
				$password = $_POST['password'];
				
				// Mustn't validate this to be valid email address, because they might be entering their username instead, which is perfectly valid
				if (empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL) )
				{
					self::$login_error_message['email'] = 'Please enter a valid email address';
				}

				if (empty($password) || strlen($password) < 8) 
				{
					self::$login_error_message['password'] = 'Password must be at least 8 characters.';
				}

				else
				{
					/**
					 * We'll assume that most of the time, legit users are logging in.
					 * This is going to massively speed up loading time for the login
					 * experience.
					 */
					$userDetails = self::is_active($email, md5($password), false);
					if ($userDetails)
					{
						foreach ($userDetails as $key=>$value)
						{
								$_SESSION['loggedIn'][$key] = $value;
						}
						self::$bool_login = true;
					}
			
					else if (! self::db_contains_email( $email ) )
					{
						self::$login_error_message['email'] = 'Oops, this email address or username isn\'t in database.';
					}
			
					else if ( self::db_contains_email($email) )
					{
						self::$login_error_message['password'] = 'Incorrect password.';
						//<br/><a href="'. Config::home() .'/reset-password/">Forget your password?</a>';
					}
			
					else if ( ! self::is_active($email, $password))
					{
						self::$login_error_message['email'] = 'Please active your account.';
					}

				}
			}

			else // if ! isset( $_POST['login'] )
			{
				if (isset($_SESSION['loggedIn']['email']) )
				{
					self::$bool_login = self::is_active($_SESSION['loggedIn']['email'], $_SESSION['loggedIn']['password']);
				}

				else
				{
					return false;
				}
			}
		}		
		return self::$bool_login;

	}

	// This function's third parameter determines whether it returns data or "true" when the is_active is true
	static function is_active($email, $password, $bool = true)
	{

		$core = Core::getInstance();
		$pdo = $core->pdo;
		$sql = 'SELECT * FROM users
			WHERE email = :email AND password = :password';
		$s = $pdo->prepare($sql);
		$s->bindValue(':email', $email);
		$s->bindValue(':password', $password);
		$s->execute();
		// Russell would utterly kill me
		return $bool ? ($s->rowCount() > 0 ? true : false) : ($s->rowCount() > 0 ? $s->fetch() : false);
	}

	static function db_contains_email($email)
	{
		$core = Core::getInstance();
		$pdo = $core->pdo;
	
		$sql = 'SELECT * FROM users WHERE email = :email';	

		$s = $pdo->prepare($sql);
		$s->bindValue(':email', $email);
		$s->execute();

		return $s->rowCount() > 0 ? true : false;
	}
}
