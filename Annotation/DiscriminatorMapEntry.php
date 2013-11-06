<?php
// DiscriminatorMap.php
/**
 * Created by JetBrains PhpStorm.
 * User: juriem
 * Date: 28/10/13
 * Time: 14:13
 * To change this template use File | Settings | File Templates.
 */

namespace Gizlab\Bundle\DoctrineBundle\Annotation;

/**
 * Class DiscriminatorMap
 * @package Gizlab\Bundle\DoctrineBundle\Annotation
 *
 * @Annotation
 */
class DiscriminatorMapEntry
{
    private $discriminatorColumn;

    public function __construct(array $data)
    {
        foreach($data as $key => $value)
        {
            if ($key == 'value'){
                $this->discriminatorColumn = $value;
            }
        }
    }

    public function getDiscriminatorColumn()
    {
        return $this->discriminatorColumn;
    }
}