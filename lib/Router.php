<?php

final class Router {

    protected ? Request $request = null;
    protected ? array $conf      = null;
    protected array $path_params = [];

    public function __construct() {
        $this->request = new Request();
    }

    public function run(): void {
        foreach ($this->conf['map'] as $routing_path => $order) {
            $this->router($routing_path, $order['controller'], $order['method']);
        }
    }

    public function push(array $map, string $namespace, ?string $group_name = null): void {
        if (!is_empty($group_name)) {
            $_map = [];

            foreach($map as $uri => $routing_params) {
                if ($uri === '/') {
                    $uri = '';
                }

                if (!strpos($group_name, '/')) {
                    $_uri = $group_name . $uri;
                } else {
                    $_uri = '/' . $group_name . $uri;
                }

                $_map[$_uri] = $routing_params;
            }
        } else {
            $_map = $map;
        }

        if (!is_empty($this->conf) && !is_empty($this->conf['map'])) {
            $this->conf['map'] = array_merge($this->conf['map'], $_map);
        } else {
            $this->conf['map'] = $_map;
        }

        $this->conf['namespace'] = $namespace;
    }

    protected function router(string $routing_path, string $controller_name, string $method): void {
        $this->dispatch($this->request->getSeparatedUri(), separate_uri($routing_path), $controller_name, $method);
    }

    protected function dispatch(array $separated_uri, array $separated_routing_path, string $controller_name, string $method): void {
        foreach (array_map(null, $separated_uri, $separated_routing_path) as [$uri_param, $routing_path_param]) {

            if (! ($uri_param ===  $routing_path_param or strpos($routing_path_param, ':') === 0) or  $routing_path_param === null) {
                return;

            } elseif (strpos($routing_path_param, ':') === 0) {
                $this->setPathParam($uri_param, $routing_path_param);
            }
        }

        $this->execute($controller_name, $method);
    }

    protected function execute(string $controller_name, string $method): void {
        $controller = $this->createController($controller_name);

        $controller->setParams(array_merge($_GET, $_POST));
        $controller->setFiles($_FILES);
        $controller->setEnvs($_SERVER);

        $controller->execute($method);
    }

    protected function setPathParam(string $uri_param, string $routing_path_param): void {
        $param = substr($routing_path_param, 1);
        $this->path_params[$param] = $uri_param;
    }

    protected function createController(string $controller): object {
        $controller_name = $this->conf['namespace'] . $controller;
        return new $controller_name($this->path_params);
    }
}
