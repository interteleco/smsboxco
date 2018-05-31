<?php

namespace Interteleco\Smsbox\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class History extends AbstractDb
{

    /**
     * Resource initialization
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('interteleco_smsbox', 'id');
    }
}
