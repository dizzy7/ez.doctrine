Интеграция Doctrine2 ORM в 1С-Битрикс
=========

Модуль представляет из себя прослойку интеграции Doctrine2 в битрикс оформленную в виде модуля.

Требования
----------
- Любая редакция 1С-Битрикс
- Версия php >=5.3.2 (для версии php 5.5 потребуется установка параметра opcache.save_comments=1)

Установка
---------

**Внимание! Модуль рассчитан на работу только из папки /local/modules/**

  - Скачать код в виде zip-архива, и распаковать в папку /local/modules/ или клонировать репозиторий `cd local/modules/ && git clone https://github.com/dizzy7/ez.doctrine.git`
  - Установить модуль из административной панели (Marketplace->Установленные решения)

Использование
-------------

После инсталяции консольная утилита для управления будет доступна как /local/doctrine

Сущности размещаются в папку /local/Entity. Поддерживаются вложенные простанства имен, т.е. можно размещать сущности например в /local/Entity/Items с namespace Items.
 
 
Пример:

```php
<?php

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Item
 * @Entity
 */
class Item {
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(type="string")
     * @Assert\NotBlank()
     */
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

$items = D::$em->getRepository('Item')->findAll();
/** @var Item $item */
foreach ($items as $item) {
    echo $item->getId().': '.$item->getTitle().'<br>';
}
```





