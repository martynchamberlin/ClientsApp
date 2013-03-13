<?
class User
{
	static function login()
	{	
		if (isset($_POST['mysql']))
			$_SESSION['loggedIn']['mysql'] = 'localhost';

		$sql = 'SELECT * FROM users WHERE email=:email AND password=:password';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('email', $_POST['email']);
		$s->bindValue('password', md5($_POST['password']));
		$s->execute();
		$row = $s->fetch();
		if ($s->rowCount() > 0)
		{
			$_SESSION['loggedIn']['email']= $_POST['email'];
			$_SESSION['loggedIn']['id'] = $row['id'];

		}
			header('Location: /');
			exit;
	}
}
