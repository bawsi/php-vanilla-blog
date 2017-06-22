<?php
//bootstrap
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));
$currentPage = 'contact';

// If POST request, send email
$contact->sendContactMail();

include(TEMPLATES_PATH . '/_header.php');
?>

<!-- Main content -->
<div class="container container-contact">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h1 class="text-center">Get in touch with me</h1>
            <p class="text-center">I will respond within 24 hours (Normally, within few hours)</p>
            <hr>

            <form class="contact-form" action="" method="post">
                <label for="name">Your name</label>
                <input required class="form-control" type="text" name="name" placeholder="Your name here">
                <label for="email">Your email</label>
                <input required class="form-control" type="email" name="email" placeholder="johndoe@mail.com">
                <label for="subject">Subject</label>
                <input required class="form-control" type="text" name="subject" placeholder="Message subject">
                <label for="body">Message</label>
                <textarea required class="form-control" name="body" rows="8" cols="80"></textarea>
                <div class="g-recaptcha" data-sitekey="6LcugCYUAAAAAHgv0uxMTivL9kTM7PmBqz8reHkx"></div>
                <button class="btn btn-danger btn-block" type="submit" name="submit">Send</button>
            </form>

        </div>
    </div>
</div>

<?php include(TEMPLATES_PATH . '/_footer.php') ?>
