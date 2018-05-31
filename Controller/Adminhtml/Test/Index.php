<?php

namespace Interteleco\Smsbox\Controller\Adminhtml\Test;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class Index extends Action
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Interteleco_Smsbox::smsbox');
        $resultPage->getConfig()->getTitle()->prepend(__('Smsbox Test SMS'));

        return $resultPage;
    }
}
