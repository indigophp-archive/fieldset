<?php

/*
 * This file is part of the Indigo Fieldset package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Fieldset\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;
use Metadata\ClassMetadata;
use Metadata\Driver\DriverInterface;
use Indigo\Fieldset\Metadata\PropertyMetadata;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Annotation implements DriverInterface
{
    /**
     * @var Reader
     */
    protected $reader;

    /**
     * @var array
     */
    protected $annotations = [
        'type'       => 'Indigo\Fieldset\Metadata\Annotation\Form\Type',
        'attributes' => 'Indigo\Fieldset\Metadata\Annotation\Form\Attributes',
        'meta'       => 'Indigo\Fieldset\Metadata\Annotation\Form\Meta',
        'rules'      => 'Indigo\Fieldset\Metadata\Annotation\Validation\Rules',
        'label'      => 'Indigo\Fieldset\Metadata\Annotation\Label',
    ];

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $classMetadata = new ClassMetadata($class->getName());

        foreach ($class->getProperties() as $property) {
            $propertMetadata = new PropertyMetadata($class->getName(), $property->getName());

            foreach ($this->annotations as $field => $annnotationClass) {
                $propertMetadata->$field = $this->reader->getPropertyAnnotation($property, $annnotationClass);
            }

            $classMetadata->addPropertyMetadata($propertMetadata);
        }

        return $classMetadata;
    }
}
