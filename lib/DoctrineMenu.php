<?php
namespace Ez\Doctrine;

use D;
use Doctrine\Common\Annotations\AnnotationReader;
use Ez\Doctrine\Mapping\Title;

class DoctrineMenu
{

    public function getEntitiesList()
    {
        $finder = new \Symfony\Component\Finder\Finder();
        $finder->files()->in(__DIR__ . '/../../../Entity')->exclude('_proxy');

        $entities = array();

        /** @var \Symfony\Component\Finder\SplFileInfo $item */
        foreach ($finder as $item) {
            $class = str_replace('/', '\\', substr($item->getRelativePathname(), 0, -4));

            $reflection = D::$em->getClassMetadata($class)->getReflectionClass();

            $reader = new AnnotationReader();
            /** @var Title $title */
            $title = $reader->getClassAnnotation($reflection,'Ez\\Doctrine\\Mapping\\Title');


            $entities[] = array(
                'class' => $class,
                'path'  => $item->getRealPath(),
                'title' => ($title instanceof Title) ? $title->getName() : $class
            );
        }

        return $entities;

    }
}