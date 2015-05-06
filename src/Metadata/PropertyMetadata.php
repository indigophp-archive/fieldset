<?php

/*
 * This file is part of the Indigo Fieldset package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Fieldset\Metadata;

use Metadata\PropertyMetadata as BasePropertyMetadata;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class PropertyMetadata extends BasePropertyMetadata
{
    /**
     * @var string[]
     */
    public $attributes = [];

    /**
     * @var string
     */
    public $label;

    /**
     * @var array
     */
    public $meta = [];

    /**
     * @var array
     */
    public $rules = [];

    /**
     * @var string
     */
    public $type;
}
