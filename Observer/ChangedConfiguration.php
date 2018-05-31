<?php

namespace Interteleco\Smsbox\Observer;

use \Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\Event\Observer       as Observer;
use \Magento\Framework\View\Element\Context as Context;
use \Magento\Framework\Exception\LocalizedException;
use \Interteleco\Smsbox\Helper\Data as Helper;

class ChangedConfiguration implements ObserverInterface
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
     * Constructor
     *
     * @param Context $context
     * @param Helper $helper
     */
    public function __construct(
        Context $context,
        Helper $helper
    ) {
        $this->_request = $context->getRequest();
        $this->_layout  = $context->getLayout();
        $this->_helper  = $helper;
    }

    /**
     * The execute class
     *
     * @param  Observer $observer
     * @return void
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        $observer->getEvent()->getName();
        $result = $this->_helper->verifyApi();

        if ($result === false) {
            $this->_helper->setConfigEmpty();
            throw new LocalizedException(
                __(
                    "error in username and/or password 
                    is incorrect and/or customer id is invalid"
                )
            );
        }
    }
}
