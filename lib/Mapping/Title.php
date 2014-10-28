<?php

namespace Ez\Doctrine\Mapping;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Title {
    private $name;

    function __construct(array $values)
    {
        $this->name = $values['name'];
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }


} 