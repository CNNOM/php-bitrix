<?php
namespace Local\TestModule;

class HelloManager
{
    public static function sayHello()
    {
        $content = "<div class='main'>Hellodddddd, world!</div> 
                   <style>.main {color: red;}</style> 
                   <script src='/jquery.nicescroll.js'></script>";
        
        return $content;
    }
}