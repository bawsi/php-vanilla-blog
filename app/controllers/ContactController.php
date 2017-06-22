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
            if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['subject']) && !empty($_POST['body'])) {
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
                    $this->msg->success('Message has been sent. I will do my best ro respond within 24 hours.', '/contact.php');
                    die();
                }
            } else {
                $this->msg->error('All fields are required.', '/contact.php');
                die();
            }
        }







    }
}


 ?>
