<?php
use \Firebase\JWT\JWT;

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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['username']) && isset($_POST['password']) && !empty($_POST['username']) && !empty($_POST['password'])) {
                $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                $password = $_POST['password'];
                $userData = $this->userModel->getUserDataFromUsername($username);

                // If user was found, check if hashed password matches
        		if (!empty($userData) && password_verify($password, $userData['password'])) {
                    // Username and password entered are correct. Set data for jwt
                    $data = array(
                        "iat"    => time(),
                        "exp"    => time() + 3600,    // expires in 1 hour
                        "userId" => $userData['id']
                    );

                    // Encode JWT data from above, key and algorithm together
                    $jwt = JWT::encode($data, JWT_KEY, 'HS512');

                    // Set cookie, which expires in 30min, with http only enabled, so javascript cant access it
                    setcookie('jwt', $jwt, time() + 1800, '/', SITE_URL, false, true);


    			} else {
                    $this->msg->error('Wrong username/password combination.', '/admin/login.php');
    				die();
    			}
            } else {
                $this->msg->error('Both fields are required.', '/admin/login.php');
                die();
            }
        }
    }

    /**
     * Checks if user is logged in
     * @return boolean True if logged in, false otherwise
     */
    public function isLoggedIn()
    {
        // Check if jwt cookie is set
        if (isset($_COOKIE['jwt'])) {
            $jwt = $_COOKIE['jwt'];

            // Decode the cookie
            $decoded = JWT::decode($jwt, JWT_KEY, ['HS512']);

            // Reeturn true if userId is set, and userId is int, and is bigger than 0
            return (isset($decoded->userId) && is_int($decoded->userId) && $decoded->userId > 0) ? true : false;

        } else {
            // Return false, if jwt cookie is not set
            return false;
        }


    }

    /**
     * If user not logged in, redirect to login page, with error message
     */
    public function redirectIfNotLoggedIn()
    {
        if (!$this->isLoggedIn()) {
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
