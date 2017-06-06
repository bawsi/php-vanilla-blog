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
	 * Validate that the data passed to registerNewUser method is valid
	 * Meaning, its not empty, or taken.
	 *
	 * @param  string $username
	 * @param  string $password
	 * @param  string $email
	 * @return bool          	Returns true if data is valid, false otherwise
	 */
	// TODO make verification more complex (eg. check for username length, make sure email is valid...
	public function validateUserRegistration($username, $password, $email)
	{
		// Check if username, password, or email were left empty
		// and set errors if they were, then redirect back to register page,
		// otherwise, continue to checking if username or email are taken
		if (empty($username) || empty($email) || empty($password)) {
			if (empty($username)) {
				$_SESSION['error_messages'][] = 'Username is required';
			}
			if (empty($email)) {
				$_SESSION['error_messages'][] = 'Email is required';
			}
			if (empty($password)) {
				$_SESSION['error_messages'][] = 'Password is required';
			}

			// TODO set redirection, for when any of the fields for registration are empty
			// Redirect to registration page
			header('location: /');
		}

		// If above verification passes, make sure username isnt taken
		$stmt = $this->db->prepare(
		'SELECT *
		 FROM users
		 WHERE username = :username
		 LIMIT 1
		');
		$stmt->bindParam(':username', $username);
		$stmt->execute();
		$username = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($username) {
			$_SESSION['error_messages'][] = 'This username is already taken';
		}

		// make sure email isnt taken
		$stmt = $this->db->prepare(
		'SELECT email
		 FROM users
		 WHERE email = :email
		 LIMIT 1
		');
		$stmt->bindParam(':email', $email);
		$stmt->execute();
		$email = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($email) {
			$_SESSION['error_messages'][] = 'This email is already taken';
		}

		// If either the email or the password is taken, return false, true otherwise
		if ($username || $email) {
			return false;
		}
		else {
			return true;
		}

	}

	/**
	 * Will pass $username, $password and $email to validateUserRegistration()
	 * and if that returns true, it means data is valid, so it will
	 * register new user
	 *
	 * @param  string   $username   Username string
	 * @param  string   $password   Password string
	 * @param  string   $email      Email string
	 * @return bool                 True if registered, false otherwise
	 */
	public function registerNewUser($username, $password, $email)
	{
		$dataIsValid = $this->validateUserRegistration($username, $password, $email);

		// TODO register user, if data entered is valid
		if ($dataIsValid) {
			header('location: /valid');
		}
		else {
			header('location: /not_valid');
		}
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

		//TODO finish login system
		if (!empty($user)) {
			if (password_verify($password, $user['password'])) {
				echo "YAY, username matches";
			}
			else {
				echo 'Failed to login';
			}
		}
	}

}


?>
