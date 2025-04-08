<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

function hello()
{
    $content = "<div class='main'>Hello, world!</div> <style>.main {color: red;}</style> <script src='/jquery.nicescroll.js'></script>";

    echo $content;
}
