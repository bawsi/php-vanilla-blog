<?php

class ContactController
{
    private $mail;
    private $msg;

    public function __construct($mail, $msg)
    {
        $this->mail = $mail;
        $this->msg  = $msg;
    }

    /**
     * Validates all the data submited on /contat.php,
     * validates captcha, sends email
     */
    public function sendContactMail()
    {
        // Only let POST requests access this method
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Make sure everything is filled in (including the recaptcha)
            if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['subject']) && !empty($_POST['body'] && !empty($_POST['g-recaptcha-response']))) {
                // Getting data, needed for captcha validation
                $captcha            = $_POST['g-recaptcha-response'];
                $secretRecaptchaKey = SECRET_RECAPTCHA_KEY;
                $userIp             = $_SERVER['REMOTE_ADDR'];

                // Response from recaptcha api's validation
                $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret="
                            . $secretRecaptchaKey . "&response=" . $captcha . "&remoteip=" . $userIp), true); // True means we get back array from json_decode, instead of object (false is default)

                // If recaptchas validation failed, set error, and return
                if (!$response['success']) {
                    die(var_dump($response));
                    $this->msg->error('You failed the captcha.. Please, do not try to spam!', '/contact.php');
                    die();
                }

                // Getting data that user submited, and sanitizing it
                $fromName = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
                $fromMail = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $subject  = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
                $body     = nl2br(filter_input(INPUT_POST, 'body', FILTER_SANITIZE_STRING));

                // Setting up PHPMailer
                $this->mail->isSMTP();
                $this->mail->Host       = SMTP_HOST;
                $this->mail->SMTPAuth   = true;
                $this->mail->Username   = SMTP_USERNAME;
                $this->mail->Password   = SMTP_PASSWORD;
                $this->mail->SMTPSecure = 'tls';
                $this->mail->Port       = SMTP_PORT;

                $this->mail->setFrom($fromMail, $fromName);
                $this->mail->addAddress(SMTP_CONTACT_TO, SMTP_CONTACT_TO_NAME);
                $this->mail->addReplyTo($fromMail, $fromName);

                $this->mail->isHTML(true);

                $this->mail->Subject = $subject;
                $this->mail->Body    = $body;

                // Sending the email, and validating it was sent, then setting approprate messages
                if(!$this->mail->send()) {
                    $this->msg->error('Message could not be sent:<ul><li>' . $this->mail->ErrorInfo . '</li></ul>', '/contact.php');
                    die();
                } else {
                    $this->msg->success('Message has been sent. I will do my best to respond within 24 hours.', '/contact.php');
                    die();
                }
            } else { // If empty fields, or recaptcha not clicked
                $this->msg->error('All fields are required, including the captcha.', '/contact.php');
                die();
            }
        }
    }
}


 ?>
