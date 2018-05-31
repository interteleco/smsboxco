<?php

namespace Interteleco\Smsbox\Block\Adminhtml\Test;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Container;
use Magento\Framework\Registry;

class Edit extends Container
{

    /**
     * Core registry
     *
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @param Context  $context
     * @param Registry $registry
     * @param array    $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize blog post edit block
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();

        $this->_blockGroup = 'Interteleco_Smsbox';
        $this->_controller = 'adminhtml_test';
        $this->buttonList->remove('back');
        $this->buttonList->remove('delete');
        $this->buttonList->remove('saveandcontinue');
        $this->buttonList->update('save', 'label', __('Send Test Sms'));
    }

    /**
     * Retrieve text for header element depending on loaded post
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        return __('Test SMS');
    }
}
