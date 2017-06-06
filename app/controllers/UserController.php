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

			}
			else {
				return false;
			}
		}

    }
}
