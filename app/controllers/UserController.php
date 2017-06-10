<?php

class UserController
{
    private $userModel;
    private $msg;

    /**
     * Set property $db to argument, which
     * has to be an instance of Db object
     *
     * @param Db $db Object Db
     */
    public function __construct(User $userModel, $msg)
    {
        $this->userModel = $userModel;
        $this->msg = $msg;
    }

    /**
     * Login user
     */
    public function login()
    {
        if (isset($_POST['username']) && isset($_POST['password']) && !empty($_POST['username']) && !empty($_POST['password']))
        {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $password = $_POST['password'];
            $userData = $this->userModel->getUserDataFromUsername($username);

            // If user was found, check if hashed password matches, and redirect accordingly
    		if (!empty($userData) && password_verify($password, $userData['password'])) {
                $_SESSION['userId'] = $userData['id'];
                header('location: /admin');
                die();

			} else {
                $this->msg->error('Wrong username/password combination.', '/admin/login.php');
				die();
			}
        } else {
            $this->msg->error('Both fields are required.', '/admin/login.php');
            die();
        }
    }

    /**
     * Checks if user is logged in
     * @return boolean True if logged in, false otherwise
     */
    public function isLoggedIn()
    {
        if (isset($_SESSION['userId']) && !empty($_SESSION['userId'])) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * If user not logged in, redirect to login page, with error message
     */
    public function redirectIfNotLoggedIn()
    {
        if (!isset($_SESSION['userId']) || empty($_SESSION['userId'])) {
            $this->msg->error('You must login, before you can access this page!', '/admin/login.php');
            die();
        }
    }

    /**
     * Logout user by unsetting his session ID
     */
    public function logout()
    {
        if ($this->isLoggedIn()) {
            unset($_SESSION['userId']);
        }
    }
}
