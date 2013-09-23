<?php

namespace Midnight\Contact\Controller;

use Doctrine\ORM\EntityManager;
use Midnight\Contact\Form\ContactForm;
use Midnight\Settings\Entity\Setting;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ContactController extends AbstractActionController
{
    public function indexAction()
    {
//        var_dump(
//            $this
//                ->getServiceLocator()
//                ->get('doctrine.entitymanager.orm_default')
//                ->getRepository('Midnight\Settings\Entity\Setting')
//                ->findOneBy(array('namespace' => 'Midnight\Contact', 'key' => 'text'))
//        );
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

        $text = $this->getSettingValue('text');
        $form_intro = $this->getSettingValue('form_intro');

        return $this->getViewModel(
            array(
                'text' => $text,
                'form' => $form,
                'formIntro' => $form_intro,
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
        return $this->getSettingValue('recipient');
    }

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    }

    /**
     * @param string $key
     * @param string $namespace
     * @return Setting
     */
    private function getSettingValue($key, $namespace = 'Midnight\Contact')
    {
        return $this->settings($namespace, $key);
    }
}