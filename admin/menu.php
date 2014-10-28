<?php

use Ez\Doctrine\DoctrineMenu;

IncludeModuleLangFile(__FILE__);

if ($USER->IsAdmin()) {
    if (!CModule::IncludeModule('ez.doctrine')) {
        return false;
    }

    $items = array();

    $helper = new DoctrineMenu();
    $entities = $helper->getEntitiesList();

    foreach ($entities as $entity) {
        $items[] = array(
            "text"      => $entity['title'] ?: $entity['class'],
            "url"       => "highloadblock_rows_list.php?ENTITY_ID=",
            "module_id" => "ez.doctrine",
            "more_url"  => Array(
                "highloadblock_row_edit.php?ENTITY_ID=",
                "highloadblock_entity_edit.php?ID="
            ),
        );
    }

    return array(
        "parent_menu" => "global_menu_content",
        "section"     => "ez.doctrine",
        "sort"        => 400,
        "text"        => "Doctrine2",
        "url"         => "highloadblock_index.php?lang=",
        "icon"        => "highloadblock_menu_icon",
        "page_icon"   => "highloadblock_page_icon",
        "more_url"    => array(
            "highloadblock_entity_edit.php",
            "highloadblock_rows_list.php",
            "highloadblock_row_edit.php"
        ),
        "items_id"    => "menu_highloadblock",
        "items"       => $items
    );
} else {
    return false;
}



