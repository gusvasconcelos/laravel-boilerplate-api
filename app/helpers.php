<?php

if (!function_exists('json_pretty')) {
    function json_pretty($data): string {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
