<?php

namespace Core\Helpers;

class View {

    private static function getViewContent(string $view){

        $viewDir = __DIR__."/../Views/";

        $file = is_dir($viewDir.$view) ? $viewDir.$view."/index.html" : $viewDir.$view.".html";

        return file_exists($file) ? file_get_contents($file) : '';

    }

    public static function render(string $view, Array $vars = []){

        $viewContent = self::getViewContent($view);

        $varsKeys = array_keys($vars);
        $varsKeys = array_map(fn($key) => "{{{$key}}}", $varsKeys);

        $vars = array_map(function ($value, $key) {

            if(is_bool($value)) $value = $key;
            
            return $value;

        }, $vars, array_keys($vars));

        return str_replace($varsKeys, $vars, $viewContent);

    }

}