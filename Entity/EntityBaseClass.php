<?php
// EntityBaseClass.php
/**
 * Created by JetBrains PhpStorm.
 * User: juriem
 * Date: 04/11/13
 * Time: 19:21
 * To change this template use File | Settings | File Templates.
 */

namespace Gizlab\Bundle\DoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class EntityBaseClass
 * @package Gizlab\Bundle\DoctrineBundle\Entity
 *
 * @ORM\Entity
 * @ORM\MappedSuperclass
 */
abstract class EntityBaseClass
{
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", name="_created_at")
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", name="_updated_at")
     * @var \DateTime
     */
    private $updatedAt;

    final function getId()
    {
        return $this->getId();
    }


    final function getCreatedAt()
    {
        return $this->createdAt;
    }

    final public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    final public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    final public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

}