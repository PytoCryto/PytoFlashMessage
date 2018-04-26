<?php

require __DIR__ . '/vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    // start the session
    session_start();
}

// create a new FlashMessage instance
$flash = \PytoCryto\Flash\FlashMessage::getInstance();

/**
 * Configuration (optional)
*/
$flash->config([
    'sticky'     => false, // sticky: Render all messages without a close button
    'fadeOut'    => true, // fadeOut on close
    'withTitles' => true, // render messages with the message type as a title if none specified
]);

/**
 * Flash messages
*/
$flash->info('Hello! This is a sticky info message with a title.', false, 'Title here'); // sticky
$flash->error('Hello! This is a error message.');
$flash->success('Hello! This is a success message.');
$flash->warning('Hello! This is a warning message. Number: #{counter}');
$flash->warning('Hello! This is a warning message. Number: #{counter}');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP Flashmessages</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <?php
                    $flash->display();
                ?>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>