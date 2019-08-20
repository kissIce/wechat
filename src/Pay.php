<?php
/**
 * Created by PhpStorm.
 * User: ice
 * Date: 2019/8/20
 * Time: 下午2:39
 */

namespace Angellce\Wechat;


use anegllce\Wechat\PayType\H5;
use anegllce\Wechat\PayType\MiniProgram;
use anegllce\Wechat\PayType\OfficialAccount;

class Pay
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * H5支付
     */
    public function h5($data)
    {
        return (new H5($this->config))->pay($data);
    }

    /**
     * miniProgram支付
     */
    public function miniprogram($data)
    {
        return (new MiniProgram($this->config))->pay($data);
    }

    /**
     * 公众号支付
     */
    public function official($data)
    {
        return (new OfficialAccount($this->config))->pay($data);
    }

    /**
     *
     * 查询订单
     */
    public function orderQuery($data)
    {
        $url = "https://api.mch.weixin.qq.com/pay/orderquery";
        //检测必填参数
        if (!isset($data['out_trade_no']) && !isset($data['transaction_id'])) {
            return ['msg'=>'订单查询接口缺少订单编号参数','code'=>1];
        }
        $utils = new Utils($this->config);
        $data['appid'] = $this->config->getAppId();
        $data['mch_id'] = $this->config->getMchId();
        $data['nonce_str'] = $utils->getNonceStr();
        $data['sign'] = $utils->MakeSign($data);
        $xml = $utils->ToXml($data);
        $response = $utils->postXmlCurl($xml, $url, false);
        $result = $utils->checkResponse($response);
        return $result;
    }
    /**
     * 关闭订单
     */
    public function closeOrder($data)
    {
        $url = "https://api.mch.weixin.qq.com/pay/closeorder";
        //检测必填参数
        if (!isset($data['out_trade_no'])) {
            return ['msg'=>'订单查询接口缺少out_trade_no','code'=>1];
        }
        $utils = new Utils($this->config);
        $data['appid'] = $this->config->getAppId();
        $data['mch_id'] = $this->config->getMchId();
        $data['nonce_str'] = $utils->getNonceStr();
        $data['sign'] = $utils->MakeSign($data);
        $xml = $utils->ToXml($data);
        $response = $utils->postXmlCurl($xml, $url, false);
        $result = $utils->checkResponse($response);
        return $result;
    }

    /**
     * 申请退款
     */
    public function refund($data, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
        //检测必填参数
        if (!isset($data['out_trade_no'])) {
            return ['msg'=>'订单查询接口缺少out_trade_no','code'=>1];
        }
        if(!isset($data['out_refund_no'])){
            return ['msg'=>'订单查询接口缺少out_refund_no','code'=>2];
        }
        if(!isset($data['total_fee'])){
            return ['msg'=>'订单查询接口缺少total_fee','code'=>3];
        }
        if(!isset($data['refund_fee'])){
            return ['msg'=>'订单查询接口缺少refund_fee','code'=>4];
        }
        if(!isset($data['op_user_id'])){
            return ['msg'=>'订单查询接口缺少必填参数op_user_id','code'=>5];
        }
        $utils = new Utils($this->config);
        $data['appid'] = $this->config->getAppId();
        $data['mch_id'] = $this->config->getMchId();
        $data['nonce_str'] = $utils->getNonceStr();
        $data['sign'] = $utils->MakeSign($data);
        $xml = $utils->ToXml($data);
        $response = $utils->postXmlCurl($xml, $url, true, $timeOut);
        $result = $utils->checkResponse($response);
        return $result;
    }

    /**
     * 查询退款
     */
    public function refundQuery($data, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/pay/refundquery";
        //检测必填参数
        if (!isset($data['out_refund_no']) && !isset($data['out_trade_no']) && !isset($data['transaction_id']) && !isset($data['refund_id']))
        {
            return ['msg'=>'退款查询接口中，out_refund_no、out_trade_no、transaction_id、refund_id四个参数必填一个！','code'=>1];
        }
        $utils = new Utils($this->config);
        $data['appid'] = $this->config->getAppId();
        $data['mch_id'] = $this->config->getMchId();
        $data['nonce_str'] = $utils->getNonceStr();
        $data['sign'] = $utils->MakeSign($data);
        $xml = $utils->ToXml($data);
        $response = $utils->postXmlCurl($xml, $url, false, $timeOut);
        $result = $utils->checkResponse($response);
        return $result;
    }

}