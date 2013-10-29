<?php
// RepositoryNotFoundException.php
/**
 * Created by JetBrains PhpStorm.
 * User: juriem
 * Date: 28/10/13
 * Time: 17:35
 * To change this template use File | Settings | File Templates.
 */

namespace Gizlab\Bundle\DoctrineBundle\Exception;

/**
 * Class RepositoryNotFoundException
 * @package Gizlab\Bundle\DoctrineBundle\Exception
 *
 */
class RepositoryNotFoundException extends \Exception
{
    public function __construct($discriminatorValue)
    {
        $this->message = sprintf('Repository for child class where discriminator value "%", not found!', $discriminatorValue);
    }
}