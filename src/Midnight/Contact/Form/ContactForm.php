<?php

namespace Midnight\Contact\Form;

use Zend\Form\Form;
use Zend\Stdlib\PriorityQueue;

class ContactForm extends Form
{

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->setInputFilter(new ContactInputFilter());

        $this->add(
            array(
                'name' => 'email',
                'type' => 'email',
                'options' => array(
                    'label' => 'E-Mail',
                ),
            )
        );

        $this->add(
            array(
                'name' => 'message',
                'type' => 'textarea',
                'options' => array(
                    'label' => 'Nachricht',
                ),
            )
        );

        $this->add(
            array(
                'name' => 'csrf',
                'type' => 'csrf',
            )
        );

        $this->add(
            array(
                'name' => 'submit',
                'type' => 'submit',
                'attributes' => array(
                    'value' => 'Nachricht abschicken',
                ),
            )
        );
    }
}