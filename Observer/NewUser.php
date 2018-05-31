<?php

namespace Interteleco\Smsbox\Observer;

use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\Event\Observer       as Observer;
use \Magento\Framework\View\Element\Context as Context;
use \Interteleco\Smsbox\Helper\Data as Helper;

class NewUser implements ObserverInterface
{

    /**
     * Https request
     *
     * @var \Zend\Http\Request
     */
    private $request;
    /**
     * Layout Interface
     *
     * @var \Magento\Framework\View\LayoutInterface
     */
    private $layout;
    /**
     * Helper
     *
     * @var \Interteleco\Smsbox\Helper\Data
     */
    private $helper;
    /**
     * Sender ID
     *
     * @var $senderId
     */
    private $senderId;
    /**
     * phone
     *
     * @var $phone
     */
    private $phone;
    /**
     * Message
     *
     * @var $message
     */
    private $message;
    /**
     * Core event manager proxy
     *
     * @var ManagerInterface
     */
    private $eventManager;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Helper $helper
     * @internal param ManagerInterface $eventManager
     */
    public function __construct(
        Context $context,
        Helper $helper
    ) {
        $this->helper  = $helper;
        $this->request = $context->getRequest();
        $this->layout  = $context->getLayout();
        $this->eventManager = $context->getEventManager();
    }
    /**
     * The execute class
     *
     * @param  Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if ($this->helper->isCustomerNotificationsOnRegisterStatus() == 1
            && $this->helper->getSmsboxApiCustomerId() != null
            && $this->helper->getSmsboxApiCustomerId() != ""
        ) {
            $event = $observer->getEvent();

            $customer = [
                'firstname' =>$event->getCustomer()->getFirstname(),
                'lastname'  =>$event->getCustomer()->getLastname()
            ];
            $this->message  = $this->helper
                ->isCustomerNotificationsOnRegisterMessage();

            $this->message  = str_replace(
                ['{firstname}', '{lastname}'],
                $customer,
                $this->message
            );
            $this->senderId = $this->helper
                ->isCustomerNotificationsOnRegisterSenderId();

            $this->phone    = $this->request->getPost('telephone');
            if ($this->phone != null) {
                $result = $this->helper->sendSms(
                    $this->senderId,
                    $this->phone,
                    $this->message,
                    'New User'
                );
                $this->eventManager->dispatch(
                    'smsbox_on_send_new_sms',
                    ['result' => $result]
                );
            }
        }
    }
}
