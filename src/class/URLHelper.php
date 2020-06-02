<?php

namespace App;

class URLHelper
{
    /**
     * @param mixed $param
     * @param mixed $val
     * 
     * @return tout le contenu du tableau get dans l'url
     */
    public static function withParam($data, $param, $val)
    {
        return http_build_query(array_merge($data, [$param => $val])); 
    }
    
    public static function withParams($data, $params)
    {
        return http_build_query(array_merge($data, $params));
    }
}