<?
class Session
{
	private $username = '';
	private $sessionKey = '';
	private $valid = false;

	private $COOKIE_NAME = '__csse_session';
	private $COOKIE_TIME = 1209600;
	private $COOKIE_PATH = '';
	private $COOKIE_DOMAIN = '';
	private $COOKIE_SECURE = true;
	private $COOKIE_HTTPONLY = true;

	private function __construct()
	{
		$this->COOKIE_PATH = getWebRoot();
		$this->COOKIE_DOMAIN = getHost();
	}

	public function getUsername()
	{ return $this->username; }
	public function isValid()
	{ return $this->valid; }

	private function getKeyFromCookie()
	{
		$unsafeKey = '';
		if( isset($_COOKIE[$this->COOKIE_NAME]))
			$unsafeKey = $_COOKIE[$this->COOKIE_NAME];
		$valid = preg_match('/^[a-z0-9].*$/i', $unsafeKey);
		$this->sessionKey = '0';
		if($valid == 1)
			$this->sessionKey = $unsafeKey;
	}

	private static function createSessionTable()
	{
		$db = DB::getDB();
		if( !$db->tableExists('session') )
		{
			$q = "CREATE TABLE session(
				id INT NOT NULL AUTO_INCREMENT UNSIGNED,
				username VARCHAR(30),
				session_key CHAR(32),
				ip CHAR(15),
				first_used DATETIME,
				last_used DATETIME,
				PRIMARY KEY(id)
			)";
			$db->query($q);
		}
	}

	private function deleteOldSession($username)
	{
		$db = DB::getDB();
		$this->createSessionTable();
		$q = "delete from session where username='$username'";
		$db->query($q);
	}

	private function createSession($username)
	{
		$sessionKey = md5( rand() );
		$ip = getClientIP();

		$time = time();
		$db = DB::getDB();
		$q = 
			"insert into session (username, session_key, ip, first_used, last_used) 
			values ('$username', '$sessionKey', '$ip', '$time', '$time')";
		$db->query($q);

		$this->sessionKey = $sessionKey;
		$this->username = $username;
		$this->valid = true;
	}

	private function deleteSessionCookie()
	{
		$name = $this->COOKIE_NAME;
		$value = '';
		$expire = time()-$this->COOKIE_TIME;
		$path = $this->COOKIE_PATH;
		$domain = $this->COOKIE_DOMAIN;
		$secure = $this->COOKIE_SECURE;
		$httponly = $this->COOKIE_HTTPONLY;
		setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
	}

	private function setSessionCookie($sessionKey)
	{
		$name = $this->COOKIE_NAME;
		$value = $sessionKey;
		$expire = time()+$this->COOKIE_TIME;
		$path = $this->COOKIE_PATH;
		$domain = $this->COOKIE_DOMAIN;
		$secure = $this->COOKIE_SECURE;
		$httponly = $this->COOKIE_HTTPONLY;
		setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
	}

	public function login($username, $passwordText)
	{
		$validUser = authenticateUser($username, $passwordText);

		if($validUser)
		{
			$this->deleteOldSession($username);
			$this->createSession($username);
			$this->setSessionCookie($this->sessionKey);
		}
		else
		{
			$this->deleteSessionCookie();
			$this->clearSession();
		}
	}

	private function getMatchingUsername()
	{
		$this->createSessionTable();

		$ip = getClientIP();
		$sessionKey = $this->sessionKey;
		$db = DB::getDB();
		$q = "select * from session where session_key='$sessionKey'";
		$t = $db->query($q);
		//print "client: $sessionKey<br>\n";
		//print "server: ". $t[0]['session_key']."<br>\n";
		//print "ip $ip<br>";
		//print_r($t);

		$hasRows = count($t) > 0;
		$hasCols = $hasRows ? count($t[0]) > 0 : false;

		if($hasRows && $hasCols)
		{
			if( $t[0]['ip'] == $ip )
				return $t[0]['username'];
		}
		return false;
	}

	public static function init()
	{
		$s = new Session;
		$s->check();
		return $s;
	}

	private function check()
	{
		$sessionKey = $this->getKeyFromCookie();
		$username = $this->getMatchingUsername();
		if($username)
		{
			$this->username = $username;
			$this->sessionKey = $sessionKey;
			$this->valid = true;
		}
		else
		{
			$this->clearSession();
			$this->deleteSessionCookie();
		}
	}

	private function clearSession()
	{
		$this->username = '';
		$this->sessionKey = '';
		$this->valid = false;
	}

	public function logout()
	{
		$this->deleteOldSession($this->username);
		$this->deleteSessionCookie();
		$this->clearSession();
	}
}

?>
