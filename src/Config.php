<?php
/**
 * Created by PhpStorm.
 * User: ice
 * Date: 2019/8/20
 * Time: 下午2:10
 */

namespace Angellce\Wechat;


class Config
{
    /**
     * @var string
     */
    protected $appid; // 公众号 APPID
    /**
     * @var string
     */
    protected $appAppId; // APP APPID
    /**
     * @var string
     */
    protected $miniAppId;  // 小程序 APPID
    /**
     * @var string
     */
    protected $mchId;
    /**
     * @var string
     */
    protected $key;
    /**
     * @var string
     */
    protected $notifyUrl;
    /**
     * @var string
     */
    protected $apiClientCert; //api客户端证书
    /**
     * @var string
     */
    protected $apiClientKey; // api客户端证书秘钥
    protected $signType;//签名方式
    protected $gateWay = 'https://api.mch.weixin.qq.com';
    /**
     * @return string
     */
    public function getAppId(): string
    {
        return $this->appid;
    }
    /**
     * @param string $appid
     */
    public function setAppId(string $appid): void
    {
        $this->appid = $appid;
    }
    /**
     * @return string
     */
    public function getAppAppId(): string
    {
        return $this->appAppId;
    }
    /**
     * @param string $appAppId
     */
    public function setAppAppId(string $appAppId): void
    {
        $this->appAppId = $appAppId;
    }
    /**
     * @return string
     */
    public function getMiniAppId(): string
    {
        return $this->miniAppId;
    }
    /**
     * @param string $miniAppId
     */
    public function setMiniAppId(string $miniAppId): void
    {
        $this->miniAppId = $miniAppId;
    }
    /**
     * @return string
     */
    public function getMchId(): string
    {
        return $this->mchId;
    }
    /**
     * @param string $mchId
     */
    public function setMchId(string $mchId): void
    {
        $this->mchId = $mchId;
    }
    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }
    /**
     * @param string $key
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }
    /**
     * @return string
     */
    public function getNotifyUrl(): ?string
    {
        return $this->notifyUrl;
    }
    /**
     * @param string $notifyUrl
     */
    public function setNotifyUrl(string $notifyUrl): void
    {
        $this->notifyUrl = $notifyUrl;
    }
    /**
     * @return string
     */
    public function getApiClientCert(): ?string
    {
        return $this->apiClientCert;
    }
    /**
     * @param string $apiClientCert
     */
    public function setApiClientCert(string $apiClientCert): void
    {
        $this->apiClientCert = $apiClientCert;
    }
    /**
     * @return string
     */
    public function getApiClientKey(): ?string
    {
        return $this->apiClientKey;
    }
    /**
     * @param string $apiClientKey
     */
    public function setApiClientKey(string $apiClientKey): void
    {
        $this->apiClientKey = $apiClientKey;
    }
    /**
     * @return string
     */
    public function getGateWay(): string
    {
        return $this->gateWay;
    }
    /**
     * @param string $gateWay
     */
    public function setGateWay(string $gateWay): void
    {
        $this->gateWay = $gateWay;
    }

}