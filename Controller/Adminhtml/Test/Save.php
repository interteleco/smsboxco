<?php
namespace Interteleco\Smsbox\Controller\Adminhtml\Test;

use \Magento\Backend\App\Action;
use \Magento\Backend\App\Action\Context;

class Save extends Action
{

    /**
     * Helper for Smsbox Module
     *
     * @var \Interteleco\Smsbox\Helper\Data
     */
    private $helper;
    /**
     * Constructor
     *
     * @param Context $context
     * @param \Interteleco\Smsbox\Helper\Data  $_helper
     */
    public function __construct(
        Context $context,
        \Interteleco\Smsbox\Helper\Data $_helper
    ) {
        parent::__construct($context);
        $this->helper  = $_helper;
    }
    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $senderId   = $this->getRequest()->getParam('sender_id');
        $phone      = $this->getRequest()->getParam('phone_number');
        $langTest   = $this->getRequest()->getParam('lang_test');

        $message    = ($langTest == 'ar')
            ? 'هذه رسالة تجريبية من ماجينتو'
            : 'Test SMS FROM MAGENTO 2' ;
        $result     = $this->helper->sendSms(
            $senderId,
            $phone,
            $message,
            'Test API'
        );
        $result['status'] === true ?
        $this->messageManager->addSuccessMessage(__($result['response'])):
        $this->messageManager->addWarningMessage(__($result['response']));
        $this->_eventManager->dispatch(
            'smsbox_on_send_new_sms',
            ['result' => $result]
        );
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
    }
}
