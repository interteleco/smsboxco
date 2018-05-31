<?php

namespace Interteleco\Smsbox\Model\ResourceModel\History;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    public function _construct()
    {
        $this->_init(
            'Interteleco\Smsbox\Model\History',
            'Interteleco\Smsbox\Model\ResourceModel\History'
        );
    }
}
