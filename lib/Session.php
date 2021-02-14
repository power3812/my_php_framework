<?php

class Session
{
    public function __construct()
    {
        if (!isset($_SESSION)) {
            ini_set('session.gc_divisor', 1);
            ini_set('session.gc_maxlifetime', SESSION_TIME);

            session_start();
        }
    }

    public function getId()
    {
        return (isset($_SESSION['id'])) ? $_SESSION['id'] : null;
    }

    public function getName()
    {
        return (isset($_SESSION['name'])) ? $_SESSION['name'] : null;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return (isset($_SESSION[$key])) ? $_SESSION[$key] : null;
    }

    public function delete($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);

            return true;
        }

        return false;
    }

    public function destroy()
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 3600, $params['path']);
        }

        session_destroy();
    }
}
