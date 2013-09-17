<?php

namespace Midnight\Contact\Form;

use Zend\InputFilter\InputFilter;

class ContactInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(
            array(
                'name' => 'email',
                'filters' => array(
                    array('name' => 'StringTrim')
                ),
                'validators' => array(
                    array('name' => 'EmailAddress'),
                ),
            )
        );
        $this->add(
            array(
                'name' => 'message',
                'filters' => array(
                    array('name' => 'StringTrim')
                ),
            )
        );
    }
}