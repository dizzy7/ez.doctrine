<?php
IncludeModuleLangFile(__FILE__);


if (class_exists('ez_doctrine')) {
    return;
}

class ez_doctrine extends CModule
{
    public $MODULE_ID = 'ez.doctrine';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME = 'Евгений Зацепин';
    public $PARTNER_URI = 'http://www.github.com/dizzy7';

    public $START_TYPE = 'WINDOW';
    public $WIZARD_TYPE = "INSTALL";

    public function ez_doctrine()
    {
        $arModuleVersion = array();
        include(__DIR__ . '/version.php');
        $this->MODULE_VERSION      = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME         = "Doctrine2 ORM";
        $this->MODULE_DESCRIPTION  = "Интеграция в битрикс Doctrine2 ORM + валидаторы из Symfony2";
    }

    public function DoInstall()
    {
        $this->installDB();
        $this->InstallEvents();
        $this->InstallFiles();
    }

    public function DoUninstall()
    {
        $this->unInstallDB();
        $this->UnInstallEvents();
        $this->UninstallFiles();
    }

    function InstallEvents($arParams = array())
    {
        RegisterModuleDependences('main', 'OnBeforeProlog', $this->MODULE_ID, 'Doctrine', 'OnBeforeProlog');
        return true;
    }

    function UnInstallEvents($arParams = array())
    {
        UnRegisterModuleDependences('main', 'OnBeforeProlog', $this->MODULE_ID, 'Doctrine', 'OnBeforeProlog');
        return true;
    }

    function InstallDB()
    {
        RegisterModule("ez.doctrine");
    }

    function UnInstallDB()
    {
        UnRegisterModule("ez.doctrine");
    }

    function InstallFiles(){
        mkdir(__DIR__.'/../../../Entity/_proxy',0755,true);
        chdir(__DIR__.'/../../../');
        symlink('modules/ez.doctrine/vendor/bin/doctrine','doctrine');
        copy(__DIR__.'/cli-config.php',__DIR__.'/../../../cli-config.php');
    }

    function UninstallFiles(){
        unlink(__DIR__.'/../../../doctrine');
        unlink(__DIR__.'/../../../cli-config.php');

    }


}