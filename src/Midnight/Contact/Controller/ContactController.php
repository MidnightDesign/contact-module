<?php

namespace Midnight\Contact\Controller;

use Doctrine\ORM\EntityManager;
use Midnight\Contact\Form\ContactForm;
use Midnight\Settings\Entity\Setting;
use Midnight\Settings\Repository\SettingRepository;
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

                $mail->setTo($this->getRecipient());
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

    /**
     * @return string
     */
    private function getRecipient()
    {
        /** @var EntityManager $em */
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        /** @var SettingRepository $settings_repo */
        $settings_repo = $em->getRepository('Midnight\Settings\Entity\Setting');
        /** @var Setting $recipient */
        $recipient = $settings_repo->get('Midnight\Contact', 'recipient');
        return $recipient->getValue();
    }
}