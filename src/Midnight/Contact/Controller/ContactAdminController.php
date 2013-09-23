<?php

namespace Midnight\Contact\Controller;

use Midnight\Admin\Controller\AbstractAdminController;
use Zend\View\Model\ViewModel;

class ContactAdminController extends AbstractAdminController
{
    public function setTextAction()
    {
        $key = $this->params()->fromRoute('key');
        $text = $this->params()->fromPost('text');
        if (is_null($text)) {
            throw new \Exception('Didn\'t get a text.');
        }
        $this->settings('Midnight\Contact', $key, $text);
        return $this->getResponse();
    }

    private function getViewModel($variables = null)
    {
        $vm = new ViewModel($variables);
        $vm->setTemplate('contact/contact/' . $this->params()->fromRoute('action') . '.phtml');
        return $vm;
    }
}