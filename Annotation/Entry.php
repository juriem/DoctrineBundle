<?php
// InheritanceEntry.php
/**
 * Created by JetBrains PhpStorm.
 * User: juriem
 * Date: 06/11/13
 * Time: 18:14
 * To change this template use File | Settings | File Templates.
 */

namespace Gizlab\Bundle\DoctrineBundle\Annotation;
use Doctrine\Common\Annotations\AnnotationException;

/**
 * Class InheritanceEntry
 * @package Gizlab\Bundle\DoctrineBundle\Annotation
 *
 * @Annotation
 */
class Entry
{
    private $name;

    /**
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        if (!array_key_exists('value', $data)){
            throw new AnnotationException('Please specify value for annotation InheritanceEntry');
        }

        $this->name = $data['value'];

        if ($this->name == ''){
            throw new AnnotationException('Please specify value for annotation InheritanceEntry');
        }
    }

    /**
     * Getter for name
     * @return mixed
     */
    final public function getName()
    {
        return $this->name;
    }


}