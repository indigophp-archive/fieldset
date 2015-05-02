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
use Metadata\MergeableClassMetadata;
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
        'Indigo\Fieldset\Metadata\Annotation\Form\Type'        => 'type',
        'Indigo\Fieldset\Metadata\Annotation\Form\Attributes'  => 'attributes',
        'Indigo\Fieldset\Metadata\Annotation\Form\Meta'        => 'meta',
        'Indigo\Fieldset\Metadata\Annotation\Validation\Rules' => 'rules',
        'Indigo\Fieldset\Metadata\Annotation\Label'            => 'label',
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
        $classMetadata = new MergeableClassMetadata($class->getName());

        foreach ($class->getProperties() as $property) {
            $propertyMetadata = new PropertyMetadata($class->getName(), $property->getName());
            $annotations = $this->reader->getPropertyAnnotations($property);

            if (empty($annotations)) {
                continue;
            }

            foreach ($annotations as $annotation) {
                $annotationClass = get_class($annotation);

                if (!isset($this->annotations[$annotationClass])) {
                    $propertyMetadata->$field = null;

                    continue;
                }

                $field = $this->annotations[$annotationClass];

                $propertyMetadata->$field = $annotation->value;
            }

            $classMetadata->addPropertyMetadata($propertyMetadata);
        }

        return $classMetadata;
    }
}
