<?php

final class Request {
    protected ? string $uri = null;

    public function __construct() {
        $this->uri = $_SERVER['REQUEST_URI'];
    }

    public function getUri(): string {
        return $this->uri;
    }

    public function getSeparatedUri(): array {
        return separate_uri($this->uri);
    }
}