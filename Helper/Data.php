<?php
namespace Interteleco\Smsbox\Helper;

use \Magento\Framework\App\Helper\Context;
use \Magento\Store\Model\ScopeInterface as ScopeInterface;
use \Magento\Framework\App\Helper\AbstractHelper as AbstractHelper;
use \Magento\Framework\HTTP\Client\Curl as Curl;
use \Magento\Framework\App\Config\ConfigResource\ConfigInterface;

class Data extends AbstractHelper
{
    const CONFIG      = 'interteleco_smsbox_configuration/basic_configuration/';
    const USER_CONFIG = 'interteleco_smsbox_users_configuration/';

    /**
     * @var \Magento\Framework\App\Config\ConfigResource\ConfigInterface
     */
    private $configInterface;

    /**
     * To be used by the API
     *
     * @var string
     */
    private $host = 'https://www.smsbox.com/';

    /**
     * To be used by the API
     *
     * @var string
     */
    private $uri = 'SMSGateway/Services/Messaging.asmx/';

    /**
     * @var \Magento\Framework\HTTP\Client\Curl
     */
    private $curl;

    /**
     * Data constructor.
     * @param Context $context
     * @param Curl $_curl
     * @param ConfigInterface $configInterface
     * @internal param $ConfigInterface
     * @internal param ConfigInterface $ConfigInterface
     */
    public function __construct(
        Context $context,
        Curl $_curl,
        ConfigInterface $configInterface
    ) {
        parent::__construct($context);
        $this->configInterface = $configInterface;
        $this->curl = $_curl;
    }
    /**
     * Getting Basic Configuration
     * These functions to get the api
     * username and password And Customer ID
     */
    /**
     * Getting smsbox API Username
     *
     * @return string
     */
    public function getSmsboxApiUsername()
    {
        return $this->getConfig(self::CONFIG . 'smsbox_username');
    }
    /**
     * Getting smsbox API Password
     *
     * @return string
     */
    public function getSmsboxApiPassword()
    {
        return $this->getConfig(self::CONFIG . 'smsbox_password');
    }
    /**
     * Getting smsbox API CustomerId
     *
     * @return string
     */
    public function getSmsboxApiCustomerId()
    {
        return (int) $this->getConfig(self::CONFIG . 'smsbox_key');
    }
    /**
     * Checking Customer on order SMS is enabled or not
     *
     * @return string
     */
    public function isCustomerNotificationsOnOrderStatus()
    {
        return $this->getConfig(
            self::USER_CONFIG
            . 'new_order/interteleco_smsbox_new_order_enabled'
        );
    }
    /**
     * Get Sender ID
     *
     * @return string
     */
    public function isCustomerNotificationsOnOrderSenderId()
    {
        return $this->getConfig(
            self::USER_CONFIG
            . 'new_order/interteleco_smsbox_new_order_sender_id'
        );
    }
    /**
     * Get Message
     *
     * @return string
     */
    public function isCustomerNotificationsOnOrderMessage()
    {
        return $this->getConfig(
            self::USER_CONFIG
            . 'new_order/interteleco_smsbox_new_order_message'
        );
    }
    /**
     * Checking Customer on Register SMS is enabled or not
     *
     * @return string
     */
    public function isCustomerNotificationsOnRegisterStatus()
    {
        return $this->getConfig(
            self::USER_CONFIG
            . 'new_register/interteleco_smsbox_new_register_enabled'
        );
    }
    /**
     * Get Sender ID
     *
     * @return string
     */
    public function isCustomerNotificationsOnRegisterSenderId()
    {
        return $this->getConfig(
            self::USER_CONFIG
            . 'new_register/interteleco_smsbox_new_register_sender_id'
        );
    }
    /**
     * Get Message
     *
     * @return string
     */
    public function isCustomerNotificationsOnRegisterMessage()
    {
        return $this->getConfig(
            self::USER_CONFIG
            . 'new_register/interteleco_smsbox_new_register_message'
        );
    }
    /**
     * Checking Customer on New Coupon SMS is enabled or not
     *
     * @return string
     */
    public function isCustomerNotificationsOnNewCouponStatus()
    {
        return $this->getConfig(
            self::USER_CONFIG
            . 'new_coupon/interteleco_smsbox_new_coupon_enabled'
        );
    }
    /**
     * Get Sender ID
     *
     * @return string
     */
    public function isCustomerNotificationsOnNewCouponSenderId()
    {
        return $this->getConfig(
            self::USER_CONFIG
            . 'new_coupon/interteleco_smsbox_new_coupon_sender_id'
        );
    }
    /**
     * Get Message
     *
     * @return string
     */
    public function isCustomerNotificationsOnNewCouponMessage()
    {
        return $this->getConfig(
            self::USER_CONFIG
            . 'new_coupon/interteleco_smsbox_new_coupon_message'
        );
    }

    /**
     * Send Configuration path to this function and get data
     *
     * @param  @var $configPath
     * @return string
     */
    public function getConfig($configPath)
    {
        return $this->scopeConfig->getValue(
            $configPath,
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * logIn of API Account
     *
     * @param $userName
     * @param $password
     * @param $customerId
     * @return mixed
     */
    public function logInApi($userName, $password, $customerId)
    {
        $postData = [
            'username'   => $userName,
            'password'   => $password,
            'customerId' => $customerId
        ];
        $response = $this->curlMain('Http_AuthenticateUser', $postData);
        $xmlData = simplexml_load_string($response);
        if (false !== $xmlData) {
            $result['message'] = $xmlData->Message;
            if ($xmlData->Result == 'true') {
                $result['status'] = true;
                return $result;
            }
        }
        $result['status'] = false;
        $result['message'] = $response;
        return $result;
    }
    /**
     * Verification of API Account
     *
     * @return bool
     */
    public function verifyApi()
    {
        $customerId = $this->getSmsboxApiCustomerId();
        if ($customerId != null && $customerId != '') {
            $postData = [
                'username'   => $this->getSmsboxApiUsername(),
                'password'   => $this->getSmsboxApiPassword(),
                'customerId' => $customerId
            ];
            $response = $this->curlMain('Http_AuthenticateUser', $postData);
            $xmlData = simplexml_load_string($response);

            if (false !== $xmlData) {
                if ($xmlData->Result == 'true') {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * get user Information of API Account
     *
     * @return array
     */
    public function getInformation()
    {
        $customerId = $this->getSmsboxApiCustomerId();
        if ($customerId != null && $customerId != '') {
            $postData = [
                'username'   => $this->getSmsboxApiUsername(),
                'password'   => $this->getSmsboxApiPassword(),
                'customerId' => $customerId
            ];
            $response = $this->curlMain('Http_AuthenticateUser', $postData);
            $xmlData = simplexml_load_string($response);
            $result['status'] = false;
            $result['points'] = 'N/A';
            if (false !== $xmlData) {
                if ($xmlData->Result == 'true') {
                    $choose[''] = "Please Select Sender ID";
                    foreach ($xmlData->Senders->string as $sender) {
                        $sender = (string) $sender;
                        $choose[$sender] = $sender;
                    }
                    $result['status']  = true;
                    $result['senders'] = $choose;
                    $result['points']  = (string)$xmlData->NetPoints;
                } else {
                    $result['senders'] = [null=>"You don't have any sender ID"];
                }
            } else {
                $result['senders']  = [null=>"You don't have any sender ID"];
            }
        } else {
            $result['status']  = false;
            $result['senders']  = [null=>"You don't have any sender ID"];
            $result['points']  = 'N/A';
        }
        return $result;
    }

    /**
     * Send new sms  of API Account
     *
     * @param $senderId
     * @param $phone
     * @param $msg
     * @param $type
     * @return bool
     */
    public function sendSms($senderId, $phone, $msg, $type)
    {
        $customerId = $this->getSmsboxApiCustomerId();
        if ($customerId != null && $customerId != '') {
            $postData = [
                'username'         => $this->getSmsboxApiUsername(),
                'password'         => $this->getSmsboxApiPassword(),
                'customerId'       => $customerId,
                'senderText'       => $senderId,
                'recipientNumbers' => $phone,
                'messageBody'      => $msg,
                'defDate'          => "",
                'isBlink'          => 'false',
                'isFlash'          => 'false',
            ];
            $result['sender_id'] = $senderId;
            $result['phone']     = $phone;
            $result['message']   = $msg;
            $result['type']      = $type;
            $response = $this->curlMain('Http_SendSMS', $postData);
            $xmlData = simplexml_load_string($response);
            if (false !== $xmlData) {
                $result['response'] = $xmlData->Message;
                if ((string)$xmlData->Result === 'true') {
                    $result['status'] = true;
                    $result['flag']   = 'Success';
                    return $result;
                }
            } else {
                $result['response'] = (string) $response;
            }
            $result['status'] = false;
            $result['flag']   = 'Failed';
            return $result;
        }

        return false;
    }

    /**
     * curl Main handling requests
     *
     * @param  @var $uri
     * @param  @var $postData
     * @return string
     */
    public function curlMain($uri, $postData)
    {
        $this->curl->post($this->host . $this->uri . $uri, $postData);
        $response = $this->curl->getBody();
        return $response;
    }

    /**
     * Insert Admin Config Values in the message using data
     *
     * @param  @var $message
     * @param  @var $data
     * @return string
     */
    public function manipulateSMS($message, $data)
    {
        $keywords   = [
            '{order_id}',
            '{firstname}',
            '{lastname}',
            '{totalPrice}'
        ];
        $message = str_replace($keywords, $data, $message);
        return $message;
    }

    /**
     * set Config Empty(smsbox_username|smsbox_password|smsbox_key)
     */
    public function setConfigEmpty()
    {
        $this->setConfigItem('smsbox_username');
        $this->setConfigItem('smsbox_password');
        $this->setConfigItem('smsbox_key');
    }

    private function setConfigItem($item)
    {
        $this->configInterface->saveConfig(
            self::CONFIG . $item,
            null,
            'default',
            0
        );
    }
}
