<?php


class Doctrine {
    public function OnBeforeProlog(){
        require_once __DIR__.'/../vendor/autoload.php';
        D::init();
    }
}