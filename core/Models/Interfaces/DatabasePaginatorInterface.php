<?php

namespace Core\Models\Interfaces;

interface DatabasePaginatorInterface {

    public function getOffset(): int;
    public function getLimit(): int;

}