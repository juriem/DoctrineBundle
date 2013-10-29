<?php
// ClassNotFoundException.php
/**
 * Created by JetBrains PhpStorm.
 * User: juriem
 * Date: 28/10/13
 * Time: 17:32
 * To change this template use File | Settings | File Templates.
 */

namespace Gizlab\Bundle\DoctrineBundle\Exception;


/**
 * Class ClassNotFoundException
 * @package Gizlab\Bundle\DoctrineBundle\Exception
 *
 *
 */
class ClassNotFoundException extends \Exception
{
    public function __construct($discriminatorMap)
    {
        $this->message = sprintf('Class for %s not found!', $discriminatorMap);
    }
}