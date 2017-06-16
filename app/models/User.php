<?php
class User {
	private $db;

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
	 *
	 * @return bool                 True if logged in, false otherwise
	 */
	public function getUserDataFromUsername($username)
	{
		$stmt = $this->db->prepare(
		'SELECT * FROM users
		 WHERE username = :username
		 LIMIT 1
		');
		$stmt->bindParam(':username', $username);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		return $user;
	}

	public function updateFirstFailedLoginAndLoginAttempts($firstFailedLogin, $loginAttempts, $userId) {
		$stmt = $this->db->prepare(
			'UPDATE users
			SET first_failed_login = :firstFailedLogin, login_attempts = :loginAttempts
			WHERE id = :userId'
		);
		$stmt->bindParam(':firstFailedLogin', $firstFailedLogin, PDO::PARAM_INT);
		$stmt->bindParam(':loginAttempts', $loginAttempts, PDO::PARAM_INT);
		$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

		$stmt->execute();

		return ($stmt) ? true : false;
	}



}


?>
