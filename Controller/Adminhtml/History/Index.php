<?php

namespace Interteleco\Smsbox\Controller\Adminhtml\History;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class Index extends Action
{

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Interteleco_Smsbox::smsbox');
        $resultPage->getConfig()->getTitle()->prepend(__('SMSBox Sending Log'));

        return $resultPage;
    }
}
