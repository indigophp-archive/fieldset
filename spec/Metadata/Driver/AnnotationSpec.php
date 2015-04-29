<?php

namespace spec\Indigo\Fieldset\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AnnotationSpec extends ObjectBehavior
{
    function let(Reader $reader)
    {
        $this->beConstructedWith($reader);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Fieldset\Metadata\Driver\Annotation');
    }

    function it_is_a_metadata_driver()
    {
        $this->shouldImplement('Metadata\Driver\DriverInterface');
    }

    function it_loads_metadata_for_a_class(Reader $reader)
    {
        $class = new \ReflectionClass('Indigo\Fieldset\Stubs\Entity');
        $reader->getPropertyAnnotation($class->getProperties()[0], Argument::type('string'))->shouldBeCalledTimes(5);

        $this->loadMetadataForClass($class)->shouldHaveType('Metadata\ClassMetadata');
    }
}
