<?php
/**
 * Created by PhpStorm.
 * User: ice
 * Date: 2019/8/20
 * Time: 下午2:48
 */

namespace Anegllce\Wechat\PayType;


use Angellce\Wechat\Config;

abstract class AbstractPay
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    abstract function pay(Array $data);

    abstract function requestUri():string;
}