<?php
/**
 * Created by PhpStorm.
 * User: ice
 * Date: 2019/8/20
 * Time: 下午3:10
 */

namespace Anegllce\Wechat\PayType;

use Angellce\Wechat\Utils;

class OfficialAccount extends AbstractPay
{

    public function pay(Array $data)
    {
        $utils = new Utils($this->config);
        $data = $utils->checkPayData($data);
        $data['appid'] = $this->config->getAppId();
        $data['mch_id'] = $this->config->getMchId();
        $data['nonce_str'] = $utils->getNonceStr();
        $data['sign'] = $utils->MakeSign($data);
        $xml = $utils->ToXml($data);
        $response = $utils->postXmlCurl($xml, $this->requestUri());
        $result = $utils->checkResponse($response);
        if (isset($result['return_code']) && $result['return_code'] == 'SUCCESS') {
            $ret_data = [
                'appId' => $result['appid'],
                'timeStamp' => (string)time(),
                'nonceStr' => $utils->getNonceStr(),
                'package' => "prepay_id=" . $result['prepay_id'],
                'signType' => "MD5",
            ];
            $ret_data['paySign'] = $utils->MakeSign($ret_data);
            return ['msg'=>'请求成功', 'data'=> $ret_data, 'code'=>0];
        } else {
            return ['msg'=>'请求错误','code'=>-1];
        }
    }

    public function requestUri(): string
    {
        return 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    }
}