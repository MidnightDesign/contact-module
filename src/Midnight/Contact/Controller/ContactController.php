<?php

namespace Midnight\Contact\Controller;

use Midnight\Contact\Form\ContactForm;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ContactController extends AbstractActionController
{
    public function indexAction()
    {
        $form = new ContactForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $mail = new Message();
                // TODO Get recipient from settings module
                $mail->setTo('r.gottesheim@loot.at');
                $mail->setSubject(sprintf('Neue Nachricht von %s', $data['email']));
                $mail->setBody($data['message']);
                $mail->setFrom($data['email']);
                /** @var TransportInterface $transport */
                $transport = $this->getServiceLocator()->get('contact.transport');
                $transport->send($mail);
                $this->flashMessenger()->addMessage('Ihre Nachricht wurde verschickt.');
                return $this->redirect()->toRoute('contact');
            }
        }

        return $this->getViewModel(
            array(
                'form' => $form,
            )
        );
    }

    private function getViewModel($variables = null)
    {
        $vm = new ViewModel($variables);
        $vm->setTemplate('contact/contact/' . $this->params()->fromRoute('action') . '.phtml');
        return $vm;
    }
}