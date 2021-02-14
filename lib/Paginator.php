<?php

class Paginator
{
    protected $item_count;
    protected $page_uri        = '/';
    protected $params          = [];
    protected $max_view_number = 5;
    protected $items_per_page  = 10;
    protected $current_page    = 1;
    protected $max_page_number = 1;

    public function __construct(int $item_count, ?int $max_view_number = null, ?int $items_per_page = null)
    {
        $this->setItemCount($item_count);

        if ($max_view_number !== null) {
            $this->setMaxViewNumber($max_view_number);
        }

        if ($items_per_page !== null) {
            $this->setItemsPerPage($items_per_page);
        }

        $this->max_page_number = $this->calculateMaxPageNumber();
    }

    public function getItemCount()
    {
        return $this->item_count;
    }

    public function getMaxViewNumber()
    {
        return $this->max_view_number;
    }

    public function getItemsPerPage()
    {
        return $this->items_per_page;
    }

    public function getCurrentPage()
    {
        return $this->current_page;
    }

    public function getPreviousPage()
    {
        return $this->current_page - 1;
    }

    public function getNextPage()
    {
        return $this->current_page + 1;
    }

    public function getViewPageNumbers()
    {
        $this->max_page_number = $this->calculateMaxPageNumber();
        $offset_left           = (int) ceil(($this->max_view_number - 1) / 2);
        $offset_right          = $this->max_view_number - $offset_left - 1;
        $start_number          = $this->current_page - $offset_left;
        $end_number            = $this->current_page + $offset_right;

        if ($start_number < 1) {
            $start_number = 1;
            if ($this->max_view_number < $this->max_page_number) {
                $end_number = $this->max_view_number;
            } else {
                $end_number = $this->max_page_number;
            }
        }

        if ($end_number > $this->max_page_number) {
            $end_number = $this->max_page_number;
            if ($this->max_view_number < $this->max_page_number) {
                $start_number = $this->max_page_number - $this->max_view_number + 1;
            } else {
                $start_number = 1;
            }
        }

        if ($end_number === 0) {
            $end_number = 1;
        }

        return range($start_number, $end_number);
    }

    public function getOffset()
    {
        return ($this->current_page - 1) * $this->items_per_page;
    }

    public function getMaxPageNumber()
    {
        return $this->max_page_number;
    }

    public function setUri($uri, $params = [])
    {
        $parsed = parse_url($uri);

        if (isset($parsed['path'])) {
            $this->page_uri = $parsed['path'];
        }

        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $_params);
            $params = array_merge($_params, $params);
        }

        $this->setParams($params);
    }

    public function createUri($page = null)
    {
        $params = $this->params;

        if (is_empty($page)) {
            unset($params['page']);
        } else {
            $params['page'] = $page;
        }

        if (is_empty($params)) {
            return $this->page_uri;
        } else {
            return $this->page_uri . '?' . http_build_query($params, '', '&');
        }
    }

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function setItemCount(int $item_count)
    {
        $this->item_count = $item_count;
    }

    public function setMaxViewNumber(int $max_view_number)
    {
        $this->max_view_number = $max_view_number;
    }

    public function setItemsPerPage(int $items_per_page)
    {
        $this->items_per_page = $items_per_page;
    }

    public function setCurrentPage($page)
    {
        if (is_empty($page)) {
            $page = 1;
        }

        if (!is_numeric($page)) {
            throw new RuntimeException('ページが存在しません。');
        }

        if ($this->isOverMaxPageNumber($page)) {
            $page = $this->calculateMaxPageNumber();
        } elseif ($page < 1) {
            $page = 1;
        }

        $this->current_page = $page;
    }

    public function previousPageExist()
    {
        return ($this->current_page > 1);
    }

    public function nextPageExist()
    {
        return ($this->current_page < $this->calculateMaxPageNumber());
    }

    public function isCurrentPage(int $page)
    {
        return ($this->current_page === $page);
    }

    public function isOverMaxPageNumber(int $page)
    {
        return ($this->calculateMaxPageNumber() < $page);
    }

    protected function calculateMaxPageNumber()
    {
        if ($this->item_count === 0) {
            return 1;
        } else {
            return (int) ceil(($this->item_count / $this->items_per_page));
        }
    }
}
