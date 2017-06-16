<?php
use \Firebase\JWT\JWT;

class UserController
{
    private $userModel;
    private $msg;

    public function __construct(User $userModel, $msg)
    {
        $this->userModel = $userModel;
        $this->msg = $msg;
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Check that both fields are set, and are not empty
            if (isset($_POST['username']) && isset($_POST['password']) && !empty($_POST['username']) && !empty($_POST['password'])) {
                // Sanitizing all the received data, and getting userdata, based on the username
                $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                $password = $_POST['password'];
                $userData = $this->userModel->getUserDataFromUsername($username);

                // Settings for login throttling, in case of too many failed login attempts
                $badLoginLimit = 3;
                $lockoutTime = 600;
                $firstFailedLogin = $userData['first_failed_login'];
                $loginAttempts = (int)$userData['login_attempts'];
                $userId = $userData['id'];

                // user failed to login for $badLoginLimit times, and is still in lockout
                if ($loginAttempts >= $badLoginLimit && $firstFailedLogin > time() - $lockoutTime) {
                    $this->msg->error('Too many failed login attempts.. You are temporarily locked out!', '/admin/login.php');
                    die();
                }

                // Attemp to log user in in, and it fails
                elseif (!$this->loginAttempt($userData, $password)) {
                    // Previous lockout time has expired
                    if ($firstFailedLogin < time() - $lockoutTime) {
                        // Set first_failed_login to current time, and login_attempts to 1
                        $this->userModel->updateFirstFailedLoginAndLoginAttempts(time(), 1, $userId);

                        // Redirect to login page, with error message
                        $this->msg->error('Wrong username / password combination.. Please try again!', '/admin/login.php');
                        die();

                    } else { // Lockout time has not yet expired
                        $this->userModel->updateFirstFailedLoginAndLoginAttempts($firstFailedLogin, ++$loginAttempts, $userId);

                        // Redirect to login page, with error message
                        $this->msg->error('Wrong username / password combination.. Please try again!', '/admin/login.php');
                        die();
                    }
                } else { // Login attempt is successfull
                    // Reset both first_failed_login and login_attempts
                    $this->userModel->updateFirstFailedLoginAndLoginAttempts(1, 0, $userId);

                    // Redirect to admin page
                    header('location: /admin');
                    die();
                }
            } else { // If either username or password field is empty, redirect to login page with error
                $this->msg->error('Both fields are required.', '/admin/login.php');
                die();
            }
        }
    }


    /**
     * Login user
     */
    public function loginAttempt($userData, $password)
    {
        // If user was found, check if hashed password matches
            if (!empty($userData) && password_verify($password, $userData['password'])) {
                // Username and password entered are correct. Set data for jwt
                $data = array(
                    "iat"    =>    time(),
                    "exp"    =>    time() + 3600,    // expires in 1 hour
                    "userId" =>    $userData['id'],
                    "userRole" =>  $userData['role']
                );

                // Encode JWT data from above, key and algorithm together
                $jwt = JWT::encode($data, JWT_KEY, 'HS512');

                // Set cookie, which expires in 30min, with http only enabled, so javascript cant access it
                setcookie('jwt', $jwt, 0, '/', SITE_URL, false, true);

                return true;
            } else {
                // wrong username or password, return false
                return false;
            }
    }

    /**
     * Checks if user is logged in, and return bool
     *
     * @return boolean True if logged in, false otherwise
     */
    public function isLoggedIn()
    {
        // Check if jwt cookie is set
        if (isset($_COOKIE['jwt'])) {
            $jwt = $_COOKIE['jwt'];

            // Try to decode the JWT. If is hasnt expired yet, and
            // userId is set and is > than 0, return true, false otherwise
            try {
                $decoded = JWT::decode($jwt, JWT_KEY, ['HS512']);
                // Reeturn true if userId is set in jwd, and userId is bigger than 0
                return (isset($decoded->userId) && $decoded->userId > 0) ? true : false;
            } catch (Firebase\JWT\ExpiredException $e) {
                // Since JWT expired, unset it, and return false
                setcookie('jwt', '', 1, '/', SITE_URL, false, true);
                return false;
            }
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
        // Redirect to login page, if isLoggedIn() method returns false
        if (!$this->isLoggedIn()) {
            $this->msg->error('You must login, before you can access this page!', '/admin/login.php');
            die();
        }
    }

    /**
     * Get logged in users ID, from jwt stored in cookie,
     * or return false if not set, or decoding fails
     *
     * @return int/bool  Users ID, or false bool value
     */
    public function getUserId()
    {
        // If cookie jwt is set, it means user is logged in
        if (isset($_COOKIE['jwt'])) {
            $jwt = $_COOKIE['jwt'];

            // Try to decode the jwt using the key from config file,
            // and return users id stored in that jwt
            try {
                // Decode jwt
                $decoded = JWT::decode($jwt, JWT_KEY, ['HS512']);
                // Return id of logged in user
                return $decoded->userId;
            } catch (Exception $e) {
                // Failed decoding jwt, return false
                return false;
            }
        } else {
            // Cookie not set, meaning user is not logged in. Return false
            return false;
        }
    }

    public function getUserRole()
    {
        // If cookie jwt is set, it means user is logged in
        if (isset($_COOKIE['jwt'])) {
            $jwt = $_COOKIE['jwt'];

            // Try to decode the jwt using the key from config file,
            // and return users id stored in that jwt
            try {
                // Decode jwt
                $decoded = JWT::decode($jwt, JWT_KEY, ['HS512']);
                // Return id of logged in user
                return $decoded->userRole;
            } catch (Exception $e) {
                // Failed decoding jwt, return false
                return false;
            }
        } else {
            // Cookie not set, meaning user is not logged in. Return false
            return false;
        }
    }

    /**
     * Logout user by deleting the jwt cookie
     */
    public function logout()
    {
        if ($this->isLoggedIn()) {
            // Setting jwt cookie to 1, which is in the past
            // (1 is first second of unix timestamp)
            setcookie('jwt', '', 1, '/', SITE_URL, false, true);
        }
    }
}
