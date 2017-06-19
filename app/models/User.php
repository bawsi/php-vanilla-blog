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

		return ($user) ? $user : false;
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

	public function getAllUsers()
	{
		$stmt = $this->db->prepare(
            'SELECT * FROM users'
        );
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $users;
	}

	public function registerNewUser($username, $password, $role)
	{
		$stmt = $this->db->prepare(
			'INSERT INTO users
			(username, password, role) VALUES(:username, :password, :role)'
		);
		$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->bindParam(':password', $password, PDO::PARAM_STR);
		$stmt->bindParam(':role', $role, PDO::PARAM_STR);
		$stmt->execute();

		return ($stmt) ? true : false;
	}


}


?>
