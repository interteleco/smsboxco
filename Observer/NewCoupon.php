<?php

namespace Interteleco\Smsbox\Observer;

use Magento\Framework\Event\ManagerInterface;
use Magento\Customer\Model\Customer;
use Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\Event\Observer       as Observer;
use \Magento\Framework\View\Element\Context as Context;
use \Interteleco\Smsbox\Helper\Data as Helper;

class NewCoupon implements ObserverInterface
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
     * $customer
     *
     * @var $customer
     */
    private $customer;
    /**
     * Sender ID
     *
     * @var $senderId
     */
    private $senderId;
    /**
     * Phone
     *
     * @var $phone
     */
    private $phone;
    /**
     * Message
     *
     * @var $message
     */
    private $message;
    /**
     * Core event manager proxy
     *
     * @var ManagerInterface
     */
    private $eventManager;
    /**
     * Constructor
     *
     * @param Context          $context
     * @param Helper           $helper
     * @param Customer         $customers
     */
    public function __construct(
        Context $context,
        Helper $helper,
        Customer $customers
    ) {
        $this->request = $context->getRequest();
        $this->layout  = $context->getLayout();
        $this->helper  = $helper;
        $this->customer = $customers;
        $this->eventManager = $context->getEventManager();
    }

    /**
     * The execute class
     *
     * @param  Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {

        $rule = $observer->getEvent()->getRule();

        if ($this->helper->isCustomerNotificationsOnNewCouponStatus() == 1
            && $rule->isObjectNew()
            && $this->helper->getSmsboxApiCustomerId() != null
            && $this->helper->getSmsboxApiCustomerId() != ""
        ) {
            $couponData          =   [
                'couponName' =>  $rule->getName(),
                'couponCode' =>  $rule->getCouponCode()
            ];

            $this->message  =
                $this->helper->isCustomerNotificationsOnNewCouponMessage();

            $this->message  = str_replace(
                ['{couponName}', '{couponCode}'],
                $couponData,
                $this->message
            );

            $this->senderId =
                $this->helper->isCustomerNotificationsOnNewCouponSenderId();

            $this->phone    =
                $this->getFilteredCustomerCollection(
                    $rule->getCustomerGroupIds()
                );

            foreach ($this->phone as $phone) {
                $result = $this->helper->sendSms(
                    $this->senderId,
                    $phone,
                    $this->message,
                    'New Coupon'
                );
                $this->eventManager->dispatch(
                    'smsbox_on_send_new_sms',
                    ['result' => $result]
                );
            }
        }
    }
    /**
     * Get Customer By Group Id
     *
     * @param $groupIds
     * @return array
     */
    private function getFilteredCustomerCollection($groupIds)
    {
        $collection = $this->customer->getCollection()
            ->addAttributeToFilter("group_id", $groupIds);

        $result = [];
        foreach ($collection as $customer) {
            $result[] =
                $customer->getDefaultBillingAddress(
                    $customer->getId()
                )->getTelephone();
        }
        return $result;
    }
}
