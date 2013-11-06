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
class Column
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $length;

    /**
     *
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        foreach($data as $key=>$value){
            if ($key == 'value'){
                $this->name = $value;
            } elseif($key == 'type'){
                $this->type = $value;
            } elseif ($key == 'length'){
                $this->length = $value;
            }
        }
    }


    public function getArray()
    {
        array('name' => $this->name, 'fieldName'=>$this->name, 'type'=>$this->type, 'length'=>$this->length);
    }
}