<?php

namespace Interteleco\Smsbox\Model;

use Magento\Framework\Model\AbstractModel;

class History extends AbstractModel
{
    protected $_eventPrefix = 'interteleco_smsbox';

    public function _construct()
    {
        $this->_init('Interteleco\Smsbox\Model\ResourceModel\History');
    }
}
