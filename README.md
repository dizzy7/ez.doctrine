Интеграция Doctrine2 ORM в 1С-Битрикс
=========

Модуль представляет из себя прослойку интеграции Doctrine2 в битрикс оформленную в виде модуля.

Установка
---------

Два варианта установки:
  - Скачать код в виде zip-архива, и распаковать в папку /local/modules/, либо
  - Клонировать репозиторий `cd local/modules/ && git clone https://github.com/dizzy7/ez.doctrine.git`

**Внимание! Модуль рассчитан на работу только из папки /local/modules/**

Установить модуль из административной панели (Marketplace->Установленные решения)

Использование
-------------

После инсталяции консольная утилита для управления будет доступна как /local/doctrine

Модели размещаются в папку /local/Entity в пространстве имен ```Entity```. Пример модели:
```php
<?php

namespace Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;

/**
 * Class Item
 * @Entity
 */
class Item {
    /** @Id @Column(type="integer") @GeneratedValue */
    private $id;

    /** @Column(type="string") */
    private $title;

    /** @Column(type="text",nullable=true) */
    private $message;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }   
} 
```

В коде битрикса для работы доступны хелперы
  - ```D::$em``` - EntityManager
  - ```D::$v``` - Валидация моделей из symfony2

Пример использования
------

```php
<?php
use Entity\Item;
use Symfony\Component\Validator\ConstraintViolation;

$item = new Item();
$item->setMessage('test');

$validator = D::$v->validate($item);

if($validator->count()){
    /** @var ConstraintViolation $error */
    foreach ($validator as $error) {
        echo $error->getMessage().'<br>';
    }
}

$item->setTitle('Title');

D::$em->persist($item);
D::$em->flush();

$items = D::$em->getRepository('Entity\Item')->findAll();
/** @var Item $item */
foreach ($items as $item) {
    echo $item->getId().': '.$item->getTitle().'<br>';
}
```





