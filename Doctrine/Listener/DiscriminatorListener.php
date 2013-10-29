<?php
// DiscriminatorListener.php
/**
 * Created by JetBrains PhpStorm.
 * User: juriem
 * Date: 28/10/13
 * Time: 14:19
 * To change this template use File | Settings | File Templates.
 */
namespace Gizlab\Bundle\DoctrineBundle\Doctrine\Listener;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;

/**
 * Class DiscriminatorListener
 * @package Gizlab\Bundle\DoctrineBundle\Doctrine\Listener
 *
 */
class DiscriminatorListener implements EventSubscriber
{
    /**
     * (non-PHPdoc)
     * @see \Doctrine\Common\EventSubscriber::getSubscribedEvents()
     */
    public function getSubscribedEvents()
    {
        return array( Events::loadClassMetadata );
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'garbage_references.doctrine.reference_listener';
    }

    /**
     *
     * @var array
     */
    private $parentClasses = array();

    /**
     *
     * @param array $parentClasses
     */
    public function __construct(array $parentClasses = array())
    {
        $this->parentClasses = $parentClasses;
    }


    /**
     *
     * @param LoadClassMetadataEventArgs $event
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $event)
    {

        $this->map = array();

        $class = $event->getClassMetadata()->name;


        if (count($event->getClassMetadata()->discriminatorMap) !== 0
            &&  ($this->checkParent($event->getClassMetadata()->parentClasses)
                || in_array($class, $this->parentClasses))) {

            $reader = new AnnotationReader();

            // Processing all
            $discriminatorMap = array();

            /*
             * Перебор и замена всех
             */
            foreach($event->getClassMetadata()->discriminatorMap as $_class) {

                $annotations = $reader->getClassAnnotations(new \ReflectionClass($_class));

                foreach($annotations as $annotation) {
                    if ($annotation instanceof \Gizlab\Bundle\DoctrineBundle\Annotation\DiscriminatorMapEntry) {

                        $discriminatorMap[$annotation->getDiscriminatorColumn()] = $_class;

                        if ($class === $_class) {
                            // Set discriminator value
                            $event->getClassMetadata()->discriminatorValue = $annotation->getDiscriminatorColumn();
                        }
                    }
                }
            }
            $event->getClassMetadata()->discriminatorMap = $discriminatorMap;
        }
    }

    /**
     *
     * @param string $class
     * @return boolean
     */
    private function checkParent($classes)
    {

        foreach($classes as $class) {
            if (in_array($class, $this->parentClasses)) {
                return true;
            }
        }
        return false;
    }

}