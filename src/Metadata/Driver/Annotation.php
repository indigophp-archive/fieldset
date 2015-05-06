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
use Metadata\Driver\DriverInterface;
use Indigo\Fieldset\Metadata\ClassMetadata;
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

        $form = $this->reader->getClassAnnotation($class, 'Indigo\Fieldset\Metadata\Annotation\Form');

        // There is a form which should be processed
        if ($form = $this->reader->getClassAnnotation($class, 'Indigo\Fieldset\Metadata\Annotation\Form')) {
            $classMetadata->action = $form->action;
            $classMetadata->attributes = $form->attributes;
            $classMetadata->method = $form->method;

            // Process fieldsets
            foreach ($form->fieldsets as $fieldset) {
                $classMetadata->fieldsets[$fieldset->name] = [
                    'fields' => $fieldset->fields,
                ];

                if (isset($fieldset->legend)) {
                    $classMetadata->fieldsets[$fieldset->name]['legend'] = $fieldset->legend;
                }
            }

            // Process form fields
            foreach ($class->getProperties() as $property) {
                $field = $this->reader->getPropertyAnnotation($property, 'Indigo\Fieldset\Metadata\Annotation\Form\Field');

                if (!isset($field)) {
                    continue;
                }

                $propertyMetadata = new PropertyMetadata($class->getName(), $property->getName());

                $propertyMetadata->attributes = $field->attributes;
                $propertyMetadata->meta = $field->meta;
                $propertyMetadata->type = $field->type;

                if ($label = $this->reader->getPropertyAnnotation($property, 'Indigo\Fieldset\Metadata\Annotation\Label')) {
                    $propertyMetadata->label = $label->value;
                }

                if ($rules = $this->reader->getPropertyAnnotation($property, 'Indigo\Fieldset\Metadata\Annotation\Validation\Rules')) {
                    $propertyMetadata->rules = $rules->value;
                }

                $classMetadata->addPropertyMetadata($propertyMetadata);
            }
        }

        return $classMetadata;
    }
}
