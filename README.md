# Flash messages with PHP sessions

This library is by default Bootstrap compatible but you can customize it how you want.

## Installation (require composer)
### With Composer

````shell
composer require pytocryto/flash
````

## Usage example

````php
// require the composer autoload
require __DIR__ . '/vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    // start the session
    session_start();
}

// create a new FlashMessage instance
$flash = PytoCryto\Flash\FlashMessage::getInstance();

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
$flash->info('Hello! This is a sticky info message with a title.', $dismissable = false, 'Title here'); // sticky message
$flash->error('Hello! This is a error message.');
$flash->success('Hello! This is a success message.');
$flash->warning('Hello! This is a warning message. Number: {counter}');
$flash->warning('Hello! This is a warning message. Number: {counter}');

// you can check if certain types of messages exist, this is useful for form validation
if ($flash->hasErrors()) {
    // error messages has been set
}
if ($flash->hasWarnings()) {
    // warning messages has been set
}

// display all messages
$flash->display();

// or just display the errors and warnings
$flash->display(['error', 'warning']);

````
