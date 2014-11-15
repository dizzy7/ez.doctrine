<?php
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityRepository;
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

$data = D::$em->getRepository($_GET['ENTITY_ID'])->findAll();
/** @var News $row */
foreach ($data as $row) {
    $lAdmin->AddRow($row->getId(),[
            'id' => $row->getId(),
            'title' => $row->getTitle(),
            'body' => $row->getBody()
        ]);
}


$lAdmin->AddHeaders($headers);

$lAdmin->Display();

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
