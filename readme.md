DoctrineBundle
==============

Extending discriminator map with annotation and discriminator map helper

Installation:

1. Add information into composer.json

{
    require:{
        ...
        "gizlab/doctrine-bundle":"dev-master"
    }
}

2. Update vendors with composer

php composer.phar update

3. Register extension in AppKernel.php


    $bundles = array(
        new Gizlab\Bundle\DoctrineBundle\GizlabDoctrineBundle(),
    );


    and in app/config/config.yml

    gizlab_doctrine:
      discriminator_listener:
        classes: [ ... here all classes for using with discriminator helper ... ]

4. How to use

    For example, configuration for next base class

``` yaml
# app/config/config.yml

...

gizlab_doctrine:
    discriminator_listener:
        classes: [Acme\Bundle\DemoBundle\Entity\BaseEntity ]
```


``` php
    // src\Acme\Bundle\DemoBundle\Entity\BaseEntity.php

    namespace Acme/Bundle/DemoBundle/Entity

    use Doctrine\ORM\Mapping as ORM;

    /**
     *
     * @ORM\Entity
     * @ORM\InheritanceType("SINGLE_TABLE" or "JOINED")
     * @ORM\DiscriminatorColumn(name="<name_of_column>", type="<type_of_column>" ...)
     *
     */
    abstract class BaseEntity
    {
        ... add fields for your entity ...
    }
```

``` php
    // src\Acme\Bundle\DemoBundle\Entity\SomeEntity.php

    namespace Acme/Bundle/DemoBundle/Entity

    use Gizlab\Bundle\DoctrineBundle\Annotation\DiscriminatorMapEntry;

    /**
     *
     * @ORM\Entity
     * @DiscriminatorMapEntry("some_reference_type")
     */
    class SomeEntity extends BaseEntity
    {}
```

``` php
    // src\Acme\Bundle\DemoBundle\Entity\OtherEntity.php

    namespace Acme/Bundle/DemoBundle/Entity

    use Gizlab\Bundle\DoctrineBundle\Annotation\DiscriminatorMapEntry;

    /**
     *
     * @ORM\Entity
     * @DiscriminatorMapEntry("other_reference_type")
     */
    class OtherEntity extends BaseEntity
    {}
```

and others too ...

5. How to use DiscriminatorMapHelper service


Some where in controller ...

    public function someAction()
    {
        $service = $this->get('gizlab.doctrine.discriminator_map_helper');

        // Try to find global

        // To get repository
        $repository = $service->findRepositroy('some_reference_type');

        // To get entity class
        $class = $service->findClass('some_reference_type');

        // To find with exact parent class,

        $repository = $service->findRepositroy('some_reference_type', 'Acme\Bundle\DemoBundle\Entity\BaseEntity');

        $class = $service->findClass('some_reference_type', 'Acme\Bundle\DemoBundle\Entity\BaseEntity');

    }


