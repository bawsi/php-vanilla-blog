<?php
session_start();

class User {
	private $db;

	/**
	 * Just set property $db to argument, which
	 * has to be an instance of Db object
	 *
	 * @param Db  $db  Object Db
	 */
	public function __construct(Db $db)
	{
		$this->db = $db->getConnection();
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	/**
	 * Will try to login the user. If data is valid, it will
	 * save the username to session variable logged_in_user.
	 * Will return true if it logged in, or false otherwise
	 *
	 * @param  string   $username   Username string
	 * @param  string   $password   Password string
	 * @return bool                 True if logged in, false otherwise
	 */
	public function login($username, $password) {
		$username = filter_var($username, FILTER_SANITIZE_SPECIAL_CHARS);

		$stmt = $this->db->prepare(
		'SELECT * FROM users
		 WHERE username = :username
		 LIMIT 1
		');
		$stmt->bindParam(':username', $username);
		$stmt->execute();

		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		// If user was found, check if hashed password matches,
		// and redirect accordingly
		if (!empty($user)) {
			if (password_verify($password, $user['password'])) {
				return true;
			}
			else {
				return false;
			}
		}
	}

}


?>
