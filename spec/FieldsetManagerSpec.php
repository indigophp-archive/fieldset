<?php

namespace spec\Indigo\Fieldset;

use Fuel\Fieldset\Builder\BuilderInterface;
use Fuel\Fieldset\Form;
use Fuel\Fieldset\Input\Text;
use Fuel\Validation\RuleInterface;
use Fuel\Validation\Validator;
use Indigo\Fieldset\Metadata\PropertyMetadata;
use Metadata\ClassMetadata;
use Metadata\MetadataFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FieldsetManagerSpec extends ObjectBehavior
{
    function let(MetadataFactory $metadataFactory, BuilderInterface $formBuilder)
    {
        $this->beConstructedWith($metadataFactory, $formBuilder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Fieldset\FieldsetManager');
    }

    function it_builds_a_form(MetadataFactory $metadataFactory, BuilderInterface $formBuilder, Form $form, Text $text)
    {
        $classMetadata = $this->getClassMetadata();
        $data = $this->getData();

        $metadataFactory->getMetadataForClass('Indigo\Fieldset\Stubs\Entity')->willReturn($classMetadata);
        $formBuilder->generate($data)->willReturn([$text]);
        $form->setContents([$text])->shouldBeCalled();

        $this->buildForm('Indigo\Fieldset\Stubs\Entity', $form);
    }

    function it_generates_a_form(MetadataFactory $metadataFactory, BuilderInterface $formBuilder, Text $text)
    {
        $classMetadata = $this->getClassMetadata();
        $data = $this->getData();

        $metadataFactory->getMetadataForClass('Indigo\Fieldset\Stubs\Entity')->willReturn($classMetadata);
        $formBuilder->generate($data)->willReturn([$text]);

        $this->generateForm('Indigo\Fieldset\Stubs\Entity')->shouldHaveType('Fuel\Fieldset\Form');
    }

    // function it_builds_a_validation(MetadataFactory $metadataFactory, Validator $validator, RuleInterface $rule)
    function it_builds_a_validation(MetadataFactory $metadataFactory, RuleInterface $rule)
    {
        $validator = new Validator;
        $classMetadata = $this->getClassMetadata();
        $classMetadata->propertyMetadata['prop']->rules = ['required'];
        $data = $this->getData();
        $data['prop']['rules'] = ['required'];

        $metadataFactory->getMetadataForClass('Indigo\Fieldset\Stubs\Entity')->willReturn($classMetadata);
        // $validator->addField('prop', null)->shouldBeCalled();
        // $validator->createRuleInstance('required', [])->willReturn($rule);
        // $validator->addRule('prop', $rule)->shouldBeCalled();

        $this->buildValidation('Indigo\Fieldset\Stubs\Entity', $validator);
    }

    function it_generates_a_validation(MetadataFactory $metadataFactory)
    {
        $classMetadata = $this->getClassMetadata();
        $classMetadata->propertyMetadata['prop']->rules = ['required'];
        $data = $this->getData();
        $data['prop']['rules'] = ['required'];

        $metadataFactory->getMetadataForClass('Indigo\Fieldset\Stubs\Entity')->willReturn($classMetadata);

        $this->generateValidation('Indigo\Fieldset\Stubs\Entity')->shouldHaveType('Fuel\Validation\Validator');
    }

    protected function getClassMetadata()
    {
        $classMetadata = new ClassMetadata('Indigo\Fieldset\Stubs\Entity');
        $propertMetadata = new PropertyMetadata('Indigo\Fieldset\Stubs\Entity', 'prop');
        $classMetadata->addPropertyMetadata($propertMetadata);

        return $classMetadata;
    }

    protected function getData()
    {
        return [
            'prop' => [
                'type'       => null,
                'name'       => 'prop',
                'label'      => null,
                'attributes' => null,
                'meta'       => null,
            ],
        ];
    }
}
