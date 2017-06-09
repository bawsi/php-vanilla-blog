<?php

class UserController
{
    private $userModel;
    /**
     * Set property $db to argument, which
     * has to be an instance of Db object
     *
     * @param Db $db Object Db
     */
    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function login($username, $password) {
        $username = filter_var($username, FILTER_SANITIZE_SPECIAL_CHARS);
        $userData = $this->userModel->getUserDataFromUsername($username);

        // If user was found, check if hashed password matches,
		// and redirect accordingly
		if (!empty($userData)) {
			if (password_verify($password, $userData['password'])) {
                $_SESSION['userId'] = $userData['id'];
                return true;
			} else {
				return false;
			}
		}

    }

    /**
     * If user not logged in, redirect to login page, with error message
     */
    public function redirectIfNotLoggedIn()
    {
        if (!isset($_SESSION['userId']) || empty($_SESSION['userId'])) {
            $_SESSION['error_messages'][] = 'You must login, before you can access this page!';
            header('location: /admin/login.php');
            die();
        }
    }

    public function logout()
    {
        if ($this->isLoggedIn()) {
            unset($_SESSION['userId']);
        }
    }
}
