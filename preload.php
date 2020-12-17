<?php

// symfony/polyfill-php80("php": ">=7.1")
if (!class_exists('ValueError', false)) {
    class ValueError extends Error
    {
    }
}