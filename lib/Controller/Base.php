<?php

abstract class Controller_Base
{
    protected $method = 'GET';
    protected $params = [];
    protected $files  = [];
    protected $logger = null;
    protected $envs   = [
        'http-host'       => 'localhost',
        'server-name'     => 'localhost',
        'server-port'     => '80',
        'server-protocol' => 'HTTP/1.0',
        'remote-addr'     => '127.0.0.1',
        'request-uri'     => '/',
    ];

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function setParam($key, $value)
    {
        $this->params[$key] = $value;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getParam($key)
    {
        $params = $this->params;
        if (isset($params[$key]) && $params[$key] !== '') {
            return $params[$key];
        }
    }

    public function setEnvs(array $envs)
    {
        foreach ($envs as $key => $value) {
            $this->setEnv($key, $value);
        }

        if ($_method = $this->getEnv('Request-Method')) {
            $this->setMethod($_method);
        }
    }

    public function setEnv($key, $value)
    {
        $this->envs[$this->normalizeEnvKey($key)] = $value;
    }

    public function getEnvs()
    {
        return $this->envs;
    }

    public function getEnv($key)
    {
        $_key = $this->normalizeEnvKey($key);

        if (isset($this->envs[$_key])) {
            return $this->envs[$_key];
        }
    }

    public function setMethod($method)
    {
        $_method = strtoupper($method);

        if (in_array($_method, ['GET', 'POST', 'PUT', 'DELETE'])) {
            $this->method = $_method;
        } else {
            trigger_error(__METHOD__ . '() Invalid method: ' . $method, E_USER_ERROR);
        }
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setFiles(array $files)
    {
        $this->files = $files;
    }

    public function getFile($key)
    {
        $files = $this->files;
        if (isset($files[$key]) && !is_empty($files[$key])) {
            $file = $files[$key];
            if (!is_empty($file['tmp_name']) && $file['size'] >= 1) {
                $file['data'] = file_get_contents($file['tmp_name']);

                return $file;
            }
        }
    }

    public function getFilePath($key)
    {
        $files = $this->files;
        if (isset($files[$key]) && !is_empty($files[$key])) {
            $file = $files[$key];
            if (!is_empty($file['tmp_name']) && $file['size'] >= 1) {
                return $file['tmp_name'];
            }
        }
    }

    public function setUp()
    {
        $this->logger = new Logger();

        set_error_handler([$this, 'errorHandler']);
    }

    public function execute($action)
    {
        try {
            $this->setUp();

            if (!method_exists($this, $action)) {
                throw new Exception(__METHOD__ . '() Action not found. ' . $action);
            }

            $this->$action();
        } catch (Exception $e) {
            $this->err500($e->getMessage());
        }
    }

    public function redirect($uri, $params = [], $exit = true)
    {
        if (!is_empty($params)) {
            $glue = (strpos($uri, '?') === false) ? '?' : '&';
            $uri .= $glue . http_build_query($params, '', '&');
        }

        header('Location: ' . BASE_URI_PATH . '/' . $uri);

        if ($exit) {
            exit;
        }
    }

    public function errorHandler($errno, $errstr, $err_file, $err_line)
    {
        $message = $errstr;

        if (!is_empty($err_file)) {
            $message .= ' file: ' . $err_file;
        }

        if (!is_empty($err_line)) {
            $message .= ' line: ' . $err_line;
        }

        $this->log($message, $errno);

        return false;
    }

    public function log($message, $err_type = E_ALL)
    {
        if ($this->logger) {
            $message = $this->getEnv('Remote-Addr') . ' '
                . $this->getEnv('Request-Uri') . ' '
                . $message;

            $this->logger->write($message, $err_type);
        }
    }

    public function err400($message = '', $exit = true)
    {
        $protocol = $this->getEnv('Server-Protocol');
        header($protocol . ' 400 Bad Request');

        $this->render('common/error/400.php', [
            'message'    => $message,
            'requestUri' => $this->getEnv('Request-Uri'),
        ]);

        $this->log('400 Bad Request ' . $message, E_WARNING);

        if ($exit) {
            exit;
        }
    }

    public function err404($message = '', $exit = true)
    {
        $protocol = $this->getEnv('Server-Protocol');
        header($protocol . ' 404 Not Found');

        $this->render('common/error/404.php', [
            'message'    => $message,
            'requestUri' => $this->getEnv('Request-Uri'),
        ]);

        $this->log('404 Not Found ' . $message, E_NOTICE);

        if ($exit) {
            exit;
        }
    }

    public function err500($message = '', $exit = true)
    {
        $protocol = $this->getEnv('Server-Protocol');
        header($protocol . ' 500 Internal Server Error');

        $this->render('common/error/500.php', [
            'message'    => $message,
            'requestUri' => $this->getEnv('Request-Uri'),
        ]);

        $this->log('500 Internal Server Error ' . $message, E_ERROR);

        if ($exit) {
            exit;
        }
    }

    protected function render($name, $data = [])
    {
        if ($template = $this->getTemplate($name)) {
            extract(array_merge(get_object_vars($this), $data), EXTR_OVERWRITE);
            include($template);
        } else {
            trigger_error(__METHOD__ . '() Template not found: ' . $name, E_USER_ERROR);
        }
    }

    protected function getTemplate($name)
    {
        $path = HTML_FILES_DIR . '/' . $name;

        if (file_exists($path)) {
            return $path;
        }
    }

    protected function normalizeEnvKey($key)
    {
        return strtolower(str_replace('_', '-', $key));
    }
}
