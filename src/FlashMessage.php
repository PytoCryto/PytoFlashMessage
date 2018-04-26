<?php

namespace PytoCryto\Flash;

use RuntimeException;

class FlashMessage
{
    /**
     * The global instance
     * 
     * @var \PytoCryto\Flash\FlashMessage
     */
    protected static $instance;

    /**
     * The configuration for the html, etc
     * 
     * @var array
     */
    protected $config = [
        'session_alias' => 'flash_messages',

        'container' => '<div id="messages">%s</div>',

        'wrapper' => '
        <div class="{message.baseClass} {message.class} {message.fadeClass}">
            {button} {message.title} {message.contents}
        </div>
        ',

        'button' => '
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>',

        'title'  => '<h4 class="alert-title">%s</h4>',

        'baseClass' => 'alert',

        'dismissableClass' => 'alert-dismissible',

        'types' => [
            'success' => 'alert-success',
            'info'    => 'alert-info',
            'error'   => 'alert-danger',
            'warning' => 'alert-warning',
        ],

        'titles' => [
            'success' => 'Success',
            'info'    => 'Info',
            'error'   => 'Error',
            'warning' => 'Warning',
        ],

        'sticky' => false,

        'fadeOut' => true,

        'withTitles' => true,
    ];

    /**
     * Create a new FlashMessage instance
     * 
     * @return void
     */
    private function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            throw new RuntimeException('A session must be started before initializing the FlashMessage library.');
        }

        $this->alias = $this->config['session_alias'];

        if (! array_key_exists($this->alias, $_SESSION)) {
            $_SESSION[$this->alias] = [];

            $this->resetMessageTypes();
        }
    }

    /**
     * Get the global class instance
     * 
     * @return \PytoCryto\Flash\FlashMessage
     */
    public static function getInstance()
    {
        if (! isset(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Clear all the messages but keep an empty array in the session
     * 
     * @return void
     */
    protected function resetMessageTypes()
    {
        foreach (array_keys($this->config('types')) as $type) {
            $_SESSION[$this->alias][$type] = [];
        }
    }

    /**
     * Flash a success message
     * 
     * @param  string      $message 
     * @param  bool        $dismissable 
     * @param  string|null $title 
     * @return $this
     */
    public function success($message, $dismissable = true, $title = null)
    {
        return $this->make($message, $title, $dismissable, __FUNCTION__);
    }

    /**
     * Flash a info message
     * 
     * @param  string      $message 
     * @param  bool        $dismissable 
     * @param  string|null $title 
     * @return $this
     */
    public function info($message, $dismissable = true, $title = null)
    {
        return $this->make($message, $title, $dismissable, __FUNCTION__);
    }

    /**
     * Flash a warning message
     * 
     * @param  string      $message 
     * @param  bool        $dismissable 
     * @param  string|null $title 
     * @return $this
     */
    public function warning($message, $dismissable = true, $title = null)
    {
        return $this->make($message, $title, $dismissable, __FUNCTION__);
    }

    /**
     * Flash a error message
     * 
     * @param  string      $message 
     * @param  bool        $dismissable 
     * @param  string|null $title 
     * @return $this
     */
    public function error($message, $dismissable = true, $title = null)
    {
        return $this->make($message, $title, $dismissable, __FUNCTION__);
    }

    /**
     * Indicate whether the given type is a valid message type
     * 
     * @param  string $type 
     * @return bool
     */
    protected function isValidType($type)
    {
        return array_key_exists($type, $this->config('types'));
    }

    /**
     * Get the message title for the given type
     * 
     * @param  string $type 
     * @return string|null
     */
    protected function getTitle($type)
    {
        return array_key_exists($type, $this->config('titles'))
            ? $this->config('titles')[$type]
            : null;
    }

    /**
     * Create the message and store it in the session
     * 
     * @param  string      $message 
     * @param  string|null $title 
     * @param  bool        $dismissable 
     * @param  string      $type 
     * @return bool|$this
     */
    protected function make($message, $title, $dismissable, $type)
    {
        if (! $this->isValidType($type)) {
            return false;
        }

        if (is_null($title) && $this->config('withTitles') === true) {
            $title = $this->getTitle($type);
        }

        $message = str_replace(
            '{counter}',
            count(@$_SESSION[$this->alias][$type]) + 1,
            $message
        );

        $this->addMessageToSession($message, $title, $dismissable, $type);

        return $this;
    }

    /**
     * Push the message to the session
     * 
     * @param  string      $message 
     * @param  string|null $title 
     * @param  bool        $dismissable 
     * @param  string      $type 
     * @return $this
     */
    protected function addMessageToSession($message, $title, $dismissable, $type)
    {
        $_SESSION[$this->alias][$type][] = compact('message', 'title', 'dismissable', 'type');

        return $this;
    }

    /**
     * Get or set configuration values
     * 
     * @param  string|array $key 
     * @param  mixed|null   $default 
     * @return mixed
     */
    public function config($key, $default = null)
    {
        // if an array is passed as the key, we will assume you want to set an array of values
        if (is_array($key)) {
            return $this->config = array_merge($this->config, $key);
        }

        return array_key_exists($key, $this->config)
                ? $this->config[$key]
                : $default;
    }

    /**
     * Indicate whether there is messages stored on the given type
     * 
     * @param  string $type 
     * @return bool
     */
    public function hasMessages($type)
    {
        return isset($_SESSION[$alias = $this->alias][$type]) && count($_SESSION[$alias][$type]) > 0;
    }

    /**
     * Indicate whether there is error messages
     * 
     * @return bool
     */
    public function hasErrors()
    {
        return $this->hasMessages('error');
    }


    /**
     * Indicate whether there is warning messages
     * 
     * @return bool
     */
    public function hasWarnings()
    {
        return $this->hasMessages('warning');
    }

    /**
     * Get messages from the given type
     * 
     * @param  string $type 
     * @return mixed
     */
    public function getMessages($type)
    {
        return $this->hasMessages($type)
            ? $_SESSION[$this->alias][$type]
            : null;
    }

    /**
     * Display the messages
     * 
     * @param  string|array|null $types 
     * @param  bool              $return 
     * @return string
     */
    public function display($types = null, $return = false)
    {
        $html  = null;
        $types = $types ?: array_keys($this->config('types'));

        foreach ((array)$types as $type) {
            if (! $this->isValidType($type) || ($messages = $this->getMessages($type)) === null) {
                continue;
            }

            foreach ($messages as $message) {
                $html .= $this->formatMessage($message, $type);
            }

            $this->clear($type);
        }

        $html = sprintf($this->config('container'), $html);

        if ($return) {
            return $html;
        }

        echo $html;
    }

    /**
     * Format the given data
     * 
     * @param  array  $data 
     * @param  string $type 
     * @return string
     */
    protected function formatMessage(array $data, $type)
    {
        $shouldBeSticky = $this->config('sticky') === true || $data['dismissable'] !== true;

        $templateData = [
            '{message.baseClass}' => $this->config('baseClass'),
            '{message.class}'     => $this->config('types')[$type],
            '{message.fadeClass}' => $this->config('fadeOut') ? 'fade in' : null,
            '{message.contents}'  => $data['message'],
            '{message.title}'     => null,
            '{button}'            => $shouldBeSticky ? null : $this->config('button')
        ];

        if ($data['dismissable'] === true) {
            $templateData['{message.class}'] .=  ' ' . $this->config('dismissableClass');
        }

        if (! is_null($title = $data['title'])) {
            $templateData['{message.title}'] = sprintf($this->config('title'), $title);
        }

        return str_replace(
            array_keys($templateData), array_values($templateData), $this->config('wrapper')
        );
    }

    /**
     * Flush all messages
     * 
     * @return $this
     */
    public function flush()
    {
        return $this->clear();
    }

    /**
     * Clear all messages for the given type(s)
     * 
     * @param  string|array $types 
     * @return $this
     */
    protected function clear($types = [])
    {
        if (empty($types)) {
            unset($_SESSION[$this->alias]);
        } else {
            foreach ((array)$types as $type) {
                unset($_SESSION[$this->alias][$type]);
            }
        }

        return $this;
    }
}
