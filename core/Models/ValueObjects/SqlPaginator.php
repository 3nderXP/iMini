<?php

namespace Core\Models\ValueObjects;

use Core\Models\Interfaces\DatabasePaginatorInterface;
use Core\Utils\Minmax;
use InvalidArgumentException;

class SqlPaginator implements DatabasePaginatorInterface {

    const DEFAULT_LIMIT = 10;
    const MAX_LIMIT = 100;

    public function __construct(
        private ?int $page,
        private ?int $limit
    ) {

        $this->page = Minmax::clamp($page, 1, INF);
        $this->limit = Minmax::clamp($limit ?? self::DEFAULT_LIMIT, 1, self::MAX_LIMIT);

    }

    public function getPage(): int {
        return $this->page;
    }

    public function getOffset(): int {
        return ($this->page - 1) * $this->limit;
    }

    public function getLimit(): int {
        return $this->limit;
    }

}