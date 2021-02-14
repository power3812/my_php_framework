<?php

abstract class Controller_AppBase extends Controller_Base
{
    protected function getPage()
    {
        $page = $this->getParam('page');

        return (is_empty($page)) ? 1 : (int) $page;
    }
}
