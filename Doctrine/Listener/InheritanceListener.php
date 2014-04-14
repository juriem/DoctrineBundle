<?php
// InheritanceListener.php
/**
 * Created by JetBrains PhpStorm.
 * User: juriem
 * Date: 06/11/13
 * Time: 18:10
 * To change this template use File | Settings | File Templates.
 */

namespace Gizlab\Bundle\DoctrineBundle\Doctrine\Listener;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\ORMException;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class InheritanceListener
 * @package Gizlab\Bundle\DoctrineBundle\Doctrine\Listener
 */
class InheritanceListener implements EventSubscriber
{

    const INHERITANCE_ANNOTATION = '\Gizlab\Bundle\DoctrineBundle\Annotation\Inheritance';

    const ENTRY_ANNOTATION = '\Gizlab\Bundle\DoctrineBundle\Annotation\Entry';

    /**
     * (non-PHPdoc)
     * @see \Doctrine\Common\EventSubscriber::getSubscribedEvents()
     */
    public function getSubscribedEvents()
    {
        return array(Events::loadClassMetadata);
    }

    public function getName()
    {
        return 'gizlab_doctrine.inheritance_listener';
    }

    /**
     *
     * @param LoadClassMetadataEventArgs $event
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $event)
    {

        $class = $event->getClassMetadata()->name;



        /*
         * Checking annotation
         */
        $reader = new AnnotationReader();
        $classReflection = new \ReflectionClass($class);
        $inheritanceAnnotation = $reader->getClassAnnotation($classReflection, self::INHERITANCE_ANNOTATION);


        if ($inheritanceAnnotation !== null) {
            /* @var $inheritanceAnnotation \Gizlab\Bundle\DoctrineBundle\Annotation\Inheritance */

            /*
             * Processing prefix
             */
            $prefix = $inheritanceAnnotation->getDiscriminatorValuePrefix();
            if ($prefix == '') {
                /*
                 * Generate prefix based on class name
                 */
                $prefix = $this->generateNameFromClass($class);
            }


            /*
             * Processing annotation
             */
            if (!$classReflection->isAbstract()) {



                /*
                 * Checking Inheritance type
                 */
                if ($inheritanceAnnotation->getType() == 'PROXY') {
                    throw new ORMException(sprintf('Class <%s> with type of inheritance "PROXY" must be an abstract!', $class));
                }

                /*
                 * Checking InheritanceEntry Annotation
                 */
                /* @var $inheritanceAnnotationEntry \Gizlab\Bundle\DoctrineBundle\Annotation\InheritanceEntry */
                $inheritanceAnnotationEntry = $reader->getClassAnnotation($classReflection, self::ENTRY_ANNOTATION);


                if ($inheritanceAnnotationEntry == null) {
                    throw new ORMException(sprintf('Please specify InheritanceEntry for class %s or make it abstract.', $class));
                }

                $event->getClassMetadata()->addDiscriminatorMapClass($prefix . '.' . $inheritanceAnnotationEntry->getName(), $class);

            } else {
                if ($inheritanceAnnotation->getType() == 'PROXY') {
                    // Skip processing PROXY inheritance
                    return;
                }
            }

            /*
             * Processing children
             */
            $driver = $event->getEntityManager()->getConfiguration()->getMetadataDriverImpl();
            foreach ($driver->getAllClassNames() as $_class) {


                $classReflection = new \ReflectionClass($_class);

                if ($classReflection->getParentClass() && $this->checkFamily($classReflection, $class)) {


                    /*
                     * Checking annotation
                     */
                    if (!$classReflection->isAbstract()) {
                        $inheritanceAnnotationEntry = $reader->getClassAnnotation($classReflection, self::ENTRY_ANNOTATION);

                        if ($inheritanceAnnotationEntry !== null) {
                            $name = $inheritanceAnnotationEntry->getName();
                        } else {
                            throw new ORMException(sprintf('Please specify @Entry annotation for class <%s>', $_class));
                        }

                        $_prefix = $this->getClassPrefix($classReflection, $class);

                        /*
                         * Add to discriminator map
                         */
                        $event->getClassMetadata()->addDiscriminatorMapClass($prefix . '.' . $_prefix, $_class);

                    }

                }
            }

            /*
             * Processing discriminator map value
             */

            $event->getClassMetadata()->setInheritanceType($inheritanceAnnotation->getType());

            $event->getClassMetadata()->setDiscriminatorColumn($inheritanceAnnotation->getDiscriminatorColumn()->getArray());

        }
    }

    /**
     *
     * @param $class
     * @return string
     */
    private function generateNameFromClass($class)
    {

        preg_match('/(.*)\\\(.*)$/i', $class, $matches);
        $name = ContainerBuilder::underscore($matches[2]);

        return $name;
    }

    /**
     * @param \ReflectionClass $class
     * @param $parentClass
     * @return bool
     */
    private function checkFamily(\ReflectionClass $class, $parentClass)
    {

        if ($class->getParentClass()) {
            if ($class->getParentClass()->name == $parentClass) {
                return true;
            } else {
                return $this->checkFamily($class->getParentClass(), $parentClass);
            }
        }

        return false;

    }


    /**
     * @param \ReflectionClass $class
     * @param $parentClass
     */
    private function getClassPrefix(\ReflectionClass $class, $parentClass)
    {


        $prefix = '';
        $reader = new AnnotationReader();

        $entryAnnotation = $reader->getClassAnnotation($class, self::ENTRY_ANNOTATION);
        if ($entryAnnotation !== null) {
            $prefix = $entryAnnotation->getName();
        }

        $inheritanceAnnotation = $reader->getClassAnnotation($class, self::INHERITANCE_ANNOTATION);
        if ($inheritanceAnnotation !== null && $inheritanceAnnotation->getType() == 'PROXY') {
            $prefix = $inheritanceAnnotation->getDiscriminatorValuePrefix();
        }

        if ($class->getParentClass()) {
            if ($class->getParentClass()->name !== $parentClass) {

                $_prefix = $this->getClassPrefix($class->getParentClass(), $parentClass);
                if ($_prefix !== '') {
                    $prefix = $_prefix . '.' . $prefix;
                }
            }
        }


        return $prefix;

    }
}