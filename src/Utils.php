<?php
/**
 * Created by PhpStorm.
 * User: ice
 * Date: 2019/8/20
 * Time: 上午11:39
 */
namespace Angellce\Wechat;

/**
 * 工具类
 * Class Utils
 * @author : Ice <709896100@qq.com>
 * @package Angellce\Wechat
 */
class Utils
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * 检查支付参数
     */
    public function checkPayData($data)
    {
        if (!isset($data['body'])) { //商品描述
            $data['body'] = '支付';
        }
        if (!isset($data['trade_type'])) { //交易类型
            $data['trade_type'] = 'JSAPI';
        }
        //异步通知url未设置，则使用配置文件中的url
        if(!isset($data['notify_url'])){
            $data['notify_url'] = $data['notify_url']??$this->config->getNotifyUrl();//异步通知url
        }
//        if (!isset($data['time_start'])) { //交易类型
//            $data['time_start'] = date("YmdHis");
//        }
//        if (!isset($data['time_expire'])) { //交易类型
//            $data['time_expire'] = date("YmdHis") + 600;
//        }
        return $data;
    }

    /**
     * 输出xml字符
     **/
    public function ToXml($data)
    {
        $xml = "<xml>";
        foreach ($data as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }
    /**
     * 以post方式提交xml到对应的接口url
     */
    public function postXmlCurl($xml, $url, $useCert = false, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //如果有配置代理这里就设置代理
        if($this->curl_proxy_host != "0.0.0.0" && $this->curl_proxy_port != 0){
            curl_setopt($ch,CURLOPT_PROXY, $this->curl_proxy_host);
            curl_setopt($ch,CURLOPT_PROXYPORT, $this->curl_proxy_port);
        }
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if($useCert == true){
            //设置证书
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLCERT, $this->sslcert_path);
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLKEY, $this->sslkey_path);
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        curl_close($ch);
        //返回结果
        if($data){
            return $data;
        } else {
            return 0;
        }
    }


    /**
     * 检查响应
     */
    public function checkResponse($xml)
    {
        $data = $this->FromXml($xml);
        $bool = $this->CheckSign($data);
        if (!$bool) {
            return false;
        }
        return $data;
    }

    /**
     * 检测签名
     */
    public function CheckSign($data)
    {
        //fix异常
        if(!isset($data['sign'])){
            return false;
        }
        $sign = $this->MakeSign($data);
        if($data['sign'] != $sign){
            return false;
        }
        return true;
    }

    /**
     * 生成签名
     */
    public function MakeSign($data)
    {
        ksort($data);
        $string = $this->ToUrlParams($data);
        if (isset($data['sign_type']) && $data['sign_type'] == 'HMAC-SHA256') {
            $string = hash_hmac('sha256', $string . '&key=' . $this->config->getKey(), $this->config->getKey());
        } else {
            $string = md5($string . '&key=' . $this->config->getKey());
        }
        return strtoupper($string);
    }

    /**
     * 格式化参数格式化成url参数
     */
    public function ToUrlParams($data)
    {
        $buff = "";
        foreach ($data as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 将xml转为array
     */
    public function FromXml($xml)
    {
        //将XML转为array
        libxml_disable_entity_loader(true);
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $data;
    }

    /**
     * 产生随机字符串，不长于32位
     */
    public function getNonceStr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }
}