<?php

namespace Core\Console\Interfaces;

interface CommandInterface {

    public function handle(array $params = []);

}