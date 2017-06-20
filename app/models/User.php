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

	public function getUserById($id)
	{
		$stmt = $this->db->prepare(
			'SELECT * FROM users WHERE id = :id LIMIT 1'
		);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();

		return ($stmt) ? $stmt->fetch(PDO::FETCH_ASSOC) : false;
	}

	/**
	 * Check if username already exists in database, and
	 * ignore one user in that query, by its ID
	 *
	 * @param  str    $username Username to search for
	 * @param  int    $id       User to ignore in that search
	 * 
	 * @return bool          	True if user was found, false otherwise
	 */
	public function checkUsernameExistsExceptOneUserId($username, $id)
	{
		$stmt = $this->db->prepare(
			'SELECT * FROM users WHERE username = :username AND id != :id LIMIT 1'
		);
		$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();

		return ($stmt->fetch()) ? true : false;
	}

	/**
	 * Update first_failed_login and login_attempts, with new values
	 *
	 * @param  int $firstFailedLogin Unix timestamp of first failed login attempt
	 * @param  int $loginAttempts    Number of failed login attempts
	 * @param  int $userId           Id of user
	 * @return bool                  True if success, false otherwise
	 */
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

	/**
	 * Get a list of all users and their data and return it
	 *
	 * @return array Array of all users and their data
	 */
	public function getAllUsers()
	{
		$stmt = $this->db->prepare(
            'SELECT * FROM users'
        );
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $users;
	}

	/**
	 * Add new user to database
	 *
	 * @param  str $username Unique username
	 * @param  str $password Bcrypt hashed password
	 * @param  str $role     User role (admin, mod, writer)
	 * @return bool
	 */
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

	/**
	 * Update existing user data
	 *
	 * @param  int $userId   Id of user to update
	 * @param  str $username New username
	 * @param  str $password New password
	 * @param  str $userRole New user role
	 * @return bool          True if success, false otherwise
	 */
	public function editUser($userId, $username, $password, $userRole)
	{
		$passwordQuery = ($password !== false) ? 'password = :password, ' : '';
		$stmt = $this->db->prepare(
			"UPDATE users
			SET username = :username, $passwordQuery role = :userRole
			WHERE id = :userId"
		);
		$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		($password !== false) ? $stmt->bindParam(':password', $password, PDO::PARAM_STR) : '';
		$stmt->bindParam(':userRole', $userRole, PDO::PARAM_STR);
		$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
		$stmt->execute();

		return ($stmt->rowCount()) ? true : false;
	}

	/**
	 * Delete user from users table
	 *
	 * @param  int $userId Id of user to delete
	 * @return bool
	 */
	public function deleteUser($userId)
	{
		$stmt = $this->db->prepare(
			'DELETE FROM users WHERE id = :id'
		);
		$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
		$stmt->execute();

		return ($stmt->rowCount() > 0) ? true : false;
	}


}


?>
