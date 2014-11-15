<?php
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Ez\Doctrine\Mapping\Title;

/** @var CMain $APPLICATION */
/** @var CUser $USER */

define("ADMIN_MODULE_NAME", "ez.doctrine");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

if (!$USER->IsAdmin()) {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

if (!CModule::IncludeModule(ADMIN_MODULE_NAME)) {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");


$APPLICATION->SetTitle('Список записей сущности');


$sTableID = 'doctrine2_' . $_GET['ENTITY_ID'];
$lAdmin   = new CAdminList($sTableID);

$headers = [];
/** @var EntityRepository $entity */
$reflection = D::$em->getClassMetadata($_GET['ENTITY_ID'])->getReflectionClass();

$reader = new AnnotationReader();
/** @var Title $title */
$title = $reader->getClassAnnotation($reflection,'Ez\\Doctrine\\Mapping\\Title');


$properties = $reflection->getProperties();
foreach ($properties as $property) {
    $title = $reader->getPropertyAnnotation($property,'Ez\\Doctrine\\Mapping\\Property');
    $column = $reader->getPropertyAnnotation($property,'Doctrine\\ORM\\Mapping\\Column');
    if($column===null){
        continue;
    }
    if($title instanceof \Ez\Doctrine\Mapping\Property) {
        $title = $title->getName();
    } else {
        $title = $property->getName();
    }

    $headers[] =array(
        "id" => $property->getName(),
        "content" => $title,
        "sort" => $property->getName(),
        "default" => true,
    );
}

$data = D::$em->createQuery('SELECT t FROM '.$reflection->getName().' t')->getArrayResult();

foreach ($data as $item) {
    $row = $lAdmin->AddRow($item['id'],$item,'/bitrix/admin/doctrine2_edit_entity?ENTITY_ID='.$_GET['ENTITY_ID'].'&ID='.$item['id']);
    $arActions[] = array(
        "ICON"=>"edit",
        "DEFAULT"=>true,
        "TEXT"=>"Редактировать",
        "ACTION"=>$lAdmin->ActionRedirect('/bitrix/admin/doctrine_edit_entity?ENTITY_ID='.$_GET['ENTITY_ID'].'&ID='.$item['id']),
    );
    $row->AddActions($arActions);
}

$lAdmin->AddHeaders($headers);

$lAdmin->Display();

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
