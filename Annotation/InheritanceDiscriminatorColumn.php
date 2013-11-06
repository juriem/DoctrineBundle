<?php
// InheritanceDiscriminatorColumn.php
/**
 * Created by JetBrains PhpStorm.
 * User: juriem
 * Date: 06/11/13
 * Time: 18:45
 * To change this template use File | Settings | File Templates.
 */

namespace Gizlab\Bundle\DoctrineBundle\Annotation;

/**
 * Class InheritanceDiscriminatorColumn
 * @package Gizlab\Bundle\DoctrineBundle\Annotation
 *
 * @Annotation
 */
class InheritanceDiscriminatorColumn
{
    /** @var string */
    public $name;
    /** @var string */
    public $type;
    /** @var integer */
    public $length;
    /** @var mixed */
    public $fieldName; // field name used in non-object hydration (array/scalar)
    /** @var string */
    public $columnDefinition;


    public function getArray()
    {
        array('name' => $this->name, 'type'=>$this->type, 'fieldName'=>$this->fieldName, 'columnDefinition'=>$this->columnDefinition);
    }
}