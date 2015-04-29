<?php

/*
 * This file is part of the Indigo Fieldset package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Fieldset;

use Fuel\Validation\Validator;
use Fuel\Fieldset\Form;
use Fuel\Fieldset\Builder\BuilderInterface;
use Metadata\MetadataFactory;

/**
 * Builds Forms and Validators
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class FieldsetManager
{
    /**
     * @var MetadataFactory
     */
    protected $metadataFactory;

    /**
     * @var BuilderInterface
     */
    protected $formBuilder;

    /**
     * @param MetadataFactory  $metadataFactory
     * @param BuilderInterface $formBuilder
     */
    public function __construct(MetadataFactory $metadataFactory, BuilderInterface $formBuilder)
    {
        $this->metadataFactory = $metadataFactory;
        $this->formBuilder = $formBuilder;
    }

    /**
     * Builds a form for a class
     *
     * @param string $class
     * @param Form   $form
     */
    public function buildForm($class, Form $form)
    {
        $classMetadata = $this->metadataFactory->getMetadataForClass($class);
        $data = [];

        foreach ($classMetadata->propertyMetadata as $propertyMetadata) {
            $data[$propertyMetadata->name] = [
                'type'       => $propertyMetadata->type,
                'name'       => $propertyMetadata->name,
                'label'      => $propertyMetadata->label,
                'attributes' => $propertyMetadata->attributes,
                'meta'       => $propertyMetadata->meta,
            ];
        }

        $generatedForm = $this->formBuilder->generate($data);
        $form->setContents($generatedForm);
    }

    /**
     * Generate and build a form for a class
     *
     * @param string $class
     *
     * @return Form
     */
    public function generateForm($class)
    {
        $form = new Form;

        $this->buildForm($class, $form);

        return $form;
    }

    /**
     * Populates a form for an object
     *
     * @param object $object
     * @param Form   $form
     */
    public function populateForm($object, Form $form)
    {
        $classMetadata = $this->metadataFactory->getMetadataForClass($class);
        $data = [];

        foreach ($classMetadata->propertyMetadata as $propertyMetadata) {
            $data[$propertyMetadata->name] = $propertyMetadata->getValue($object);
        }

        $form->populate($data);
    }

    /**
     * Builds validation for a class
     *
     * @param string    $class
     * @param Validator $validator
     */
    public function buildValidation($class, Validator $validator)
    {
        $classMetadata = $this->metadataFactory->getMetadataForClass($class);

        foreach ($classMetadata->propertyMetadata as $propertyMetadata) {
            $validator->addField($propertyMetadata->name, $propertyMetadata->label);

            foreach ($propertyMetadata->rules as $name => $arguments) {
                if (is_int($name)) {
                    $name = $arguments;
                    $arguments = [];
                }

                $rule = $validator->createRuleInstance($name, $arguments);
                $validator->addRule($propertyMetadata->name, $rule);
            }
        }
    }

    /**
     * Generates validation for a class
     *
     * @param string $class
     *
     * @return Validator
     */
    public function generateValidation($class)
    {
        $validator = new Validator;

        $this->buildValidation($class, $validator);

        return $validator;
    }
}