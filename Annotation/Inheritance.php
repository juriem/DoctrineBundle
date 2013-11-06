<?php
// Inheritance.php
/**
 * Created by JetBrains PhpStorm.
 * User: juriem
 * Date: 06/11/13
 * Time: 17:58
 * To change this template use File | Settings | File Templates.
 */

namespace Gizlab\Bundle\DoctrineBundle\Annotation;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Class Inheritance
 * @package Gizlab\Bundle\DoctrineBundle\Annotation
 *
 * @Annotation
 */
class Inheritance
{
    /**
     *
     * @var array
     */
    private $typeHash = array('SINGLE_TABLE' => ClassMetadata::INHERITANCE_TYPE_SINGLE_TABLE, 'JOINED'=> ClassMetadata::INHERITANCE_TYPE_JOINED);

    /**
     * Type
     * @var string
     */
    private $type;

    /**
     * @var InheritanceDiscriminatorColumn
     */
    private $discriminatorColumn;

    /**
     * @var string
     */
    private $discriminatorValuePrefix = '';


    public function __construct(array $data)
    {
        foreach($data as $key => $value){

            if ($key == 'value'){

                if (!in_array($value, array_keys($this->typeHash))){
                    throw new AnnotationException(sprintf('Wrong values for inheritance type. Allowed %s', implode(',', array_keys($this->typeHash))));
                }

                $this->type = $value;
            } elseif ($key == 'column'){
                if (!$value instanceof InheritanceDiscriminatorColumn){
                    throw new AnnotationException(sprintf('Wrong type of value for column argument. Must be Gizlab\Bundle\DoctrineBundle\Annotation\InheritanceDiscriminatorColumn annotation'));
                }
                $this->discriminatorColumn = $value;
            } elseif ($key == 'prefix'){
                $this->discriminatorValuePrefix = $value;
            }

        }
    }

    /**
     * Get type of inheritance
     *
     * @return mixed
     */
    final public function getType()
    {
        return $this->typeHash[$this->type];
    }

    /**
     * @return DiscriminatorColumn|InheritanceDiscriminatorColumn
     */
    final public function getDiscriminatorColumn()
    {

        return $this->discriminatorColumn;
    }

    /**
     * Get definition for discriminator prefix
     *
     * @return string
     */
    final public function getDiscriminatorValuePrefix()
    {
        return $this->discriminatorValuePrefix;
    }
}