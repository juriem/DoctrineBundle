<?php
// DiscriminatorMapHelper.php
/**
 * Created by JetBrains PhpStorm.
 * User: juriem
 * Date: 28/10/13
 * Time: 17:03
 * To change this template use File | Settings | File Templates.
 */

namespace Gizlab\Bundle\DoctrineBundle\Service;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Gizlab\Bundle\DoctrineBundle\Exception\ClassNotFoundException;
use Gizlab\Bundle\DoctrineBundle\Exception\RepositoryNotFoundException;

/**
 * Class DiscriminatorMapHelper
 * @package Gizlab\Bundle\DoctrineBundle\Service
 *
 * Special helper for finding repository or classes by reference code
 */
class DiscriminatorMapHelper
{
    /**
     *
     * @var array
     */
    private $parentClasses = array();

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     * @param array $classes
     */
    public function __construct(EntityManager $entityManager, array $classes = array())
    {
        $this->entityManager = $entityManager;

        $this->parentClasses = $classes;
    }

    /**
     * Find repository by discriminator map value
     *
     * @param $discriminatorValue
     * @param null $parentClass
     * @return EntityRepository
     * @throws \Gizlab\Bundle\DoctrineBundle\Exception\RepositoryNotFoundException
     */
    public function findRepository($discriminatorValue, $parentClass = null)
    {
        $entityClass = $this->findClass($discriminatorValue, $parentClass);
        if ($entityClass !== false){

            return $this->entityManager->getRepository($entityClass);
        }

        throw new RepositoryNotFoundException($discriminatorValue);

    }

    /**
     * External method for find class by discriminator value
     *
     * @param $discriminatorValue
     * @param null $parentClass
     * @return bool
     */
    public function findClass($discriminatorValue, $parentClass = null)
    {
        return $this->__findClass($discriminatorValue, $parentClass);
    }

    /**
     * Internal method for find class by discriminator value
     *
     * @param $discriminatorValue
     * @param null $parentClass
     * @param bool $doNotThrowException
     * @return bool
     */
    private function __findClass($discriminatorValue, $parentClass = null, $doNotThrowException = false)
    {
        $classes = $this->parentClasses;
        if ($parentClass != null){
            $classes = array($parentClass);
        }

        foreach($classes as $class){
            $classMetadata = $this->entityManager->getClassMetadata($class);

            if (array_key_exists($discriminatorValue, $classMetadata->discriminatorMap)){
                return $classMetadata->discriminatorMap[$discriminatorValue];
            }
        }

        if ($doNotThrowException){

            return false;
        }

        throw new ClassNotFoundException($discriminatorValue);
    }

}