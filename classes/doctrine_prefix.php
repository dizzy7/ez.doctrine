<?php


namespace DoctrineExtensions;
use \Doctrine\ORM\Event\LoadClassMetadataEventArgs;


/**
 * Расширение для Doctrine ORM
 * Позволяет отслеживать и работать не со всей базой, а только с таблицами с префиксом
 * Необходимо для уживания с битриксом
 *
 * Class TablePrefix
 * @package DoctrineExtensions
 */
class TablePrefix
{
    protected $prefix = '';

    public function __construct($prefix)
    {
        $this->prefix = (string) $prefix;
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();
        $classMetadata->setTableName($this->prefix . $classMetadata->getTableName());
        foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
            if ($mapping['type'] == \Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_MANY) {
                $mappedTableName = $classMetadata->associationMappings[$fieldName]['joinTable']['name'];
                $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . $mappedTableName;
            }
        }
    }

}