<?php

namespace Interteleco\Smsbox\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use \Interteleco\Smsbox\Helper\Data as Helper;

class Senders implements ArrayInterface
{
    /**
     * @var $information
     */
    private $information;
    /**
     * @var Helper
     */
    private $helper;
    /**
     * @param Helper $helper
     */
    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
        $this->information = $this->helper->getInformation();
    }

    public function toOptionArray()
    {
        $arr = $this->toArray();
        $ret = [];

        foreach ($arr as $key => $value) {
            $ret[] = ['value' => $key,'label' => $value];
        }

        return $ret;
    }

    public function toArray()
    {
        return $this->information['senders'];
    }
}
