<?php
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Validator\Validation;

/**
 * Created by PhpStorm.
 * User: dizzy
 * Date: 19.04.14
 * Time: 14:11
 */
class D
{

    /**
     * @var EntityManager
     */
    public static $em;

    /**
     * @var Validation
     */
    public static $v;

    /**
     *  Инициализация
     */
    public static function init()
    {
        self::initAutoloader();
        self::initEntityManager();
        self::initValidator();
    }

    /**
     * Инициализация автозагрузчика сущностей (должны располагаться в папке /local/Entity/ и быть в пространстве имен Entity\*
     */
    private static function initAutoloader()
    {
        spl_autoload_register(
            function ($class) {
                if (strpos($class, 'Entity') !== 0) {
                    return false;
                }

                $path = __DIR__.'/../../..';
                $class = explode('\\', $class);
                foreach ($class as $part) {
                    $path .= '/' . $part;
                }
                $path .= '.php';
                if (file_exists($path)) {
                    require_once($path);
                    return true;
                } else {
                    return false;
                }

            },
            true,
            false
        );
    }

    /**
     * Инициализация доктрины
     */
    private static function initEntityManager()
    {
        $bxConnectionConfig = Bitrix\Main\Application::getConnection()->getConfiguration();
        $conn = array(
            'driver'   => 'pdo_mysql',
            'user'     => $bxConnectionConfig['login'],
            'password' => $bxConnectionConfig['password'],
            'dbname'   => $bxConnectionConfig['database'],
            'host'     => $bxConnectionConfig['host'],
            'charset'  => 'utf8',
        );

        AnnotationRegistry::registerFile(
            __DIR__ . "/../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php"
        );
        AnnotationRegistry::registerAutoloadNamespace(
            'Symfony\Component\Validator\Constraint',
            __DIR__ . '/../vendor/symfony/validator'
        );

        $config = Setup::createAnnotationMetadataConfiguration(
            array(__DIR__. "/../../../Entity"),
            false,
            __DIR__ . '/../../../Entity/_proxy/'
        );

        require_once __DIR__ . '/doctrine_prefix.php';

        $tablePrefix = new \DoctrineExtensions\TablePrefix('a_');
        $evm         = new \Doctrine\Common\EventManager;
        $evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $tablePrefix);
        self::$em = EntityManager::create($conn, $config, $evm);
    }

    /**
     * Инициализация валидатора
     */
    private static function initValidator()
    {
        self::$v = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
    }

} 