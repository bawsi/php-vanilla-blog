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

    public function sendContactMail()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['subject']) && !empty($_POST['body'] && !empty($_POST['g-recaptcha-response']))) {
                $captcha  = $_POST['g-recaptcha-response'];
                $secretRecaptchaKey = SECRET_RECAPTCHA_KEY;
                $userIp = $_SERVER['REMOTE_ADDR'];

                $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret="
                            . $secretRecaptchaKey . "&response=" . $captcha . "&remoteip=" . $userIp), true); // True means we get back array from json_decode, instead of object (false is default)

                if (!$response['success']) {
                    $this->msg->error('You failed the captcha.. Please, do not try to spam!', '/contact.php');
                    die();
                }

                $fromName = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
                $fromMail = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $subject  = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
                $body     = nl2br(filter_input(INPUT_POST, 'body', FILTER_SANITIZE_STRING));

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

                if(!$this->mail->send()) {
                    $this->msg->error('Message could not be sent:<ul><li>' . $this->mail->ErrorInfo . '</li></ul>', '/contact.php');
                    die();
                } else {
                    $this->msg->success('Message has been sent. I will do my best to respond within 24 hours.', '/contact.php');
                    die();
                }
            } else {
                $this->msg->error('All fields are required, including the captcha.', '/contact.php');
                die();
            }
        }







    }
}


 ?>
