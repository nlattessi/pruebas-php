<?php

class URLHelper
{
    public static function getUrl($id, $url = '')
    {
        if (! empty($url)) {
            return $url;
        }

        return "https://news.ycombinator.com/item?id={$id}";
    }
}