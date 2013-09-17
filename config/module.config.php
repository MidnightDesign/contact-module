<?php

namespace Midnight\Contact;

use Zend\Mail\Transport\Sendmail;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\ServiceManager\ServiceManager;

return array(
    'router' => array(
        'routes' => array(
            'contact' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/kontakt[/]',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\Contact',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            __NAMESPACE__ . '\Controller\Contact' => __NAMESPACE__ . '\Controller\ContactController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'contact.transport' => function (ServiceManager $serviceManager) {
                $config = $serviceManager->get('Config');
                $config = $config['mail']['transport'];

                if ($config['type'] === 'smtp') {
                    $transport = new Smtp();
                    $transport->setOptions(new SmtpOptions($config['options']));
                } elseif ($config['type'] === 'sendmail') {
                    $transport = new Sendmail();
                }

                return $transport;
            },
        ),
    ),
    'settings' => [
        __NAMESPACE__ => [
            'recipient' => [
                'form_element' => [
                    'type' => 'email',
                    'options' => [
                        'label' => 'Empfänger für Kontaktanfragen',
                    ],
                ],
            ],
        ],
    ],
);
