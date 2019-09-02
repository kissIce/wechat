<?php
/**
 * Created by PhpStorm.
 * User: ice
 * Date: 2019/8/30
 * Time: 上午10:03
 */

namespace Angellce\Wechat;


class Notify
{

    protected $key;

    function __construct($key)
    {
        $this->key = $key;
    }

    function handle($xml)
    {
        $ret = ['code' => -1, 'msg' => '支付成功', 'xml' => ''];
        if (empty($xml)) {
            $ret['xml']  = $this->arrayToXml(['return_code' => 'FAIL']);
            $ret['code'] = 1;
            $ret['msg']  = '支付失败';
        } else {
            //如果返回成功则验证签名
            $result_arr = $this->xmlToArr($xml);
            //回应微信
            if ($result_arr["return_code"] == "FAIL" || $result_arr["result_code"] == "FAIL") {
                $ret['xml']  = $this->arrayToXml(['return_code' => 'FAIL', 'return_msg' => '支付失败']);
                $ret['code'] = 2;
                $ret['msg']  = '支付失败';
            } else {
                if(!array_key_exists("return_code", $result_arr)
                    ||(array_key_exists("return_code", $result_arr) && $result_arr['return_code'] != "SUCCESS")) {
                    $ret['xml']  = $this->arrayToXml(['return_code' => 'FAIL', 'return_msg' => '支付失败']);
                    $ret['code'] = 3;
                    $ret['msg']  = '支付失败';
                }
                if(!array_key_exists("transaction_id", $result_arr)){
                    $ret['xml']  = $this->arrayToXml(['return_code' => 'FAIL', 'return_msg' => '参数缺失']);
                    $ret['code'] = 4;
                    $ret['msg']  = '支付失败';
                }
                $checkResult = $this->CheckSign($result_arr);
                if($checkResult == false){
                    $ret['xml']  = $this->arrayToXml(['return_code' => 'FAIL', 'return_msg' => '签名校验失败']);
                    $ret['code'] = 5;
                    $ret['msg']  = '支付失败';
                } else {
                    $ret['xml']  = $this->arrayToXml(['return_code' => 'SUCCESS', 'return_msg' => '支付成功']);
                    $ret['code'] = 0;
                    $ret['msg']  = '支付成功';
                }
            }
        }
        return $ret;
    }

    public function xmlToArr($xml)
    {
        libxml_disable_entity_loader(true);
        $arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $arr;
    }

    public function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
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
        //签名步骤一：按字典序排序参数
        ksort($data);
        $string = $this->ToUrlParams($data);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".$this->key;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
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

}