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

    /**
     * Get all data for login, validate it, make sure user is not
     * locked out, and then call the loginAttempt method
     */
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
     *
     * @return bool True if logged successfull, false otherwise
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
     * @return bool True if logged in, false otherwise
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
    * If user not an admin, redirect to panel homepage, with error message
     */
    public function redirectIfNotAdmin()
    {
        // Redirect to login page, if isLoggedIn() method returns false
        if (!$this->isLoggedIn() || $this->getUserRole() !== 'admin') {
            $this->msg->error('You are now allowed to access this page.', '/admin');
            die();
        }
    }


    /**
     * Update users password
     */
    public function updatePassword()
    {
        // Only allow post requests to access this method
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $this->getUserId();
            $userData = $this->getUserById($userId);

            $oldPassCorrect = password_verify($_POST['old-password'], $userData['password']);
            $newPasswordsMatch = ($_POST['new-password'] === $_POST['new-password-verify']) ? true : false;

            // Making sure that old password entered matches currently active password,
            // and that new password and password verification match, and then updating the pass
            if ($oldPassCorrect && $newPasswordsMatch && strlen($_POST['new-password']) > 4 && $userData['username'] !== 'admin') {
                $newPass = password_hash($_POST['new-password'], PASSWORD_DEFAULT, ['cost' => '12']);
                $this->userModel->updatePassword($userId, $newPass);
                $this->msg->success('Password updated successfully!', '/admin');
                die();
            } else { // Verification failed, so redirect with error msg
                $this->msg->error('Old password is incorrect, passwords do not match, password too short, or you tried changing
                                  password of user with name "admin", which is disabled on this demo site!', '/admin/settings.php');
                die();
            }
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
                // Decode jwt cookie
                $decoded = JWT::decode($jwt, JWT_KEY, ['HS512']);
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

    /**
     * Get role of currently logged in user from jwt cookie
     *
     * @return str/bool if cookie is set, return role, false if error
     */
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
     * Grab all users and their data, and return them
     *
     * @return Array Associative array of users
     */
    public function getAllUsers()
    {
        $users = $this->userModel->getAllUsers();

        return $users;
    }

    /**
     * Get single user by its ID, and return it
     *
     * @param  int $id ID of user
     *
     * @return array     Array of users data
     */
    public function getUserById($id)
    {
        return $this->userModel->getUserById($id);
    }

    public function getLoggedInUserData()
    {
        $userId = $this->getUserId();
        $userData = $this->userModel->getUserById($userId);

        return $userData;
    }

    public function getUsersWithTotalAndLatestArticleTime()
    {
        return $this->userModel->getUsersWithTotalAndLatestArticleTime();
    }

    /**
     * Check if username already exists in database,
     * except, ignore one user by its ID in that check
     *
     * @param  str $username Username to check for
     * @param  int $id       Id of user to ignore
     *
     * @return bool           True if user exists, false otherwise
     */
    public function checkUsernameExistsExceptOneUserId($username, $id)
    {
        return $this->userModel->checkUsernameExistsExceptOneUserId($username, $id);
    }


    /**
     * Logout user by deleting the jwt cookie
     */
    public function logout()
    {
        if ($this->isLoggedIn()) {
            // Setting jwt cookie to past time (it expires)
            setcookie('jwt', '', 1, '/', SITE_URL, false, true);
        }
    }

    /**
     * Check if logged in user is allowed to modify the article.
     * User is allowed to modify article if hes the author, admin or mod
     * Method takes argument aurhorId, instead of articleId, since that
     * is all we need here to check if user is allowed to modify article.
     *
     * @param  int $authorId Id of articles author
     * @return bool           True if allowed to modify it, false otherwise
     */
    public function allowedToModifyArticle($authorId)
    {
        // Get role and ID of logged in user
        $userRole = $this->getUserRole();
        $loggedInUserId = $this->getUserId();

        // If logged in user is admin, mod or author, return true, false otherwise
        if ($userRole == 'admin' || $userRole == 'mod' || $loggedInUserId == $authorId) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Validate new user data, make sure all is valid, and then
     * register new user, and set appropriate messages
     */
    public function newUser()
    {
        // If user came to this page via POST request
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $this->getUserRole() == 'admin') {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT, ['cost' => '12']);
            $userRole = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
            $allowedUserRoles = ['mod', 'writer'];

            // If username, password and role are all set, not empty, username doesnt exist yet,
            // and user role is in allowed array fomr above, register user
            if (!empty($username) && strlen($username) > 3 && !empty($password) && strlen($password) > 4
                && !empty($userRole) && in_array($userRole, $allowedUserRoles) && !$this->userModel->getUserDataFromUsername($username)
                && $userRole !== 'admin')
            {
                $isRegistered = $this->userModel->registerNewUser($username, $password, $userRole);

                // If registration was successfull, redirect back with success msg
                if ($isRegistered) {
                    $this->msg->success("New user '$username' successfully registered.", '/admin/users.php');
                    die();
                } else { // If registration failed, set errors and redirect back
                    $this->msg->error("Registration of user '$username' failed..", '/admin/users.php');
                    die();
                }
            } else { // Otherwise, redirect back, with error message
                $this->msg->error('All fields are required. Make sure username is unique, and longer than 3 characters, and that password is longer than 4 characters.', '/admin/users.php');
                die();
            }


        } else { // User came to this page directly. Redirect him to homepage
            $this->msg->error('You cannot do this...', '/admin');
            die();
        }
    }

    /**
     * Edit existing user. Only admins can use this method.
     */
    public function editUserAsAdmin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $this->getUserRole() == 'admin') {
            // Setting all the variables for validation and user updating
            $userId = filter_input(INPUT_POST, 'userId', FILTER_SANITIZE_NUMBER_INT);
            $oldUserData = $this->getUserById($userId);
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $oldUsername = $oldUserData['username'];
            $usernameIsValid = (!$this->checkUsernameExistsExceptOneUserId($username, $userId)) ? true : false;
            $password = $_POST['password'];
            $userRole = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
            $oldUserRole = $oldUserData['role'];
            $allowedUserRoles = ['mod', 'writer'];

            // Make sure username is not empty, longer than 3 chars, role of user were editing
            // not empty, and not admin, and that username is valid (from above)
            if (!empty($username) && strlen($username) > 3 && !empty($userRole) && $oldUserRole !== 'admin' && in_array($userRole, $allowedUserRoles) && $usernameIsValid) {
                // New password was set
                if (!empty($password)) {
                    // Validate that password is longer than 4 characters, hash it, store new data and redirect
                    if (strlen($password) > 4) {
                        $password = password_hash($_POST['password'], PASSWORD_DEFAULT, ['cost' => '12']);
                        $this->userModel->editUser($userId, $username, $password, $userRole);
                        $this->msg->success('User successfully updated.', '/admin/users.php');
                        die();
                    } else { // Password too short - redirect with msg
                        $this->msg->error('Password is too short!', '/admin/users.php');
                        die();
                    }
                } else { // New password was not set. Update user
                    // Set $password to false, so we dont update it in the model
                    $password = false;
                    $this->userModel->editUser($userId, $username, $password, $userRole);
                    $this->msg->success('User successfully updated.', '/admin/users.php');
                    die();
                }
            } else { // Any of the validation methods failed
                $this->msg->error('All fields except password, are required. Make sure username is unique, and longer than 3 characters, and that password is longer than 4 characters.', '/admin/users.php');
                die();
            }
        } else { // User that is editing a user is not admin, or did not come here via POST request
            $this->msg->error('You cannot do that...', '/');
            die();
        }
    }

    /**
     * Make sure logged in user is allowed to delete articles, validate
     * userId passed through GET request, and then delete user
     */
    public function deleteUser()
    {
        // If GET variable 'id' is set, and logged in user is admin, proceed with user deletion
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET['id']) && $this->getUserRole() == 'admin') {
            $userId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            $wasDeleted = $this->userModel->deleteUser($userId);

            // Set message based on result of user deletion, and redirect
            if ($wasDeleted) {
                $this->msg->success('User successfully deleted.', '/admin/users.php');
                die();
            } else {
                $this->msg->error('Failed to delete user.', '/admin/users.php');
                die();
            }

        } else { // If 'id' was not set, or user is not admin, set error and redirect
            $this->msg->error('You cannot do this...', '/admin');
            die();
        }
    }


}
