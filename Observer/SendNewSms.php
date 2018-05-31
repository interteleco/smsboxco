<?php

namespace Interteleco\Smsbox\Observer;

use Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\Event\Observer       as Observer;
use \Magento\Framework\View\Element\Context as Context;

class SendNewSms implements ObserverInterface
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

    private $historyObject;

    /**
     * Constructor
     *
     * @param Context $context
     */
    public function __construct(
        Context $context,
        \Interteleco\Smsbox\Model\History $_historyObject
    ) {
        $this->request = $context->getRequest();
        $this->layout  = $context->getLayout();
        $this->historyObject = $_historyObject;
    }
    /**
     * The execute class
     *
     * @param  Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $resultArray = $observer->getEvent()->getResult();
        $this->historyObject->setStatus($resultArray['flag']);
        $this->historyObject->setResponse($resultArray['response']);
        $this->historyObject->setSentAt(time());
        $this->historyObject->setNumber($resultArray['phone']);
        $this->historyObject->setSender($resultArray['sender_id']);
        $this->historyObject->setType($resultArray['type']);
        if ($resultArray['type'] === 'New Order') {
            $this->historyObject->setOrderId($resultArray['order_id']);
        }
        $this->historyObject->setMessage($resultArray['message']);
        $this->historyObject->setIsObjectNew(true);
        $this->historyObject->save();
        $this->historyObject->unsetData();
    }
}
