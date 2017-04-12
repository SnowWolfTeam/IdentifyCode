<?php
namespace IdentifyCode;

use IdentifyCode\BaseCode\IdentifyCodeBase;
use IdentifyCode\Exception\IdentifyCodeException;

class IdentifyCodeSend extends IdentifyCodeBase
{
    const CODE_TIME_OUT = -1;
    const CODE_NOT_TIME_OUT = -2;
    const ErrorRepeatTime_NOT_PASS = -3;
    const CODE_ERROR = -4;
    const SEND_FAILED = -5;

    public function checkCodeTimeOut($lastStampe, $timeInterval)
    {
        $this->paramsCheck($lastStampe, $timeInterval);
        return $this->checkGetCodeTimeInterval($lastStampe, $timeInterval);
    }

    public function checkErrorRepeatTime($lastStampe, $timeInterval)
    {
        $this->paramsCheck($lastStampe, $timeInterval);
        return $this->checkCodeErrorRepeatTime($lastStampe, $timeInterval);
    }

    public function checkIdentifyCode($realCode, $getCode)
    {
        return $this->checkCodeError($getCode, $realCode);
    }

    public function send($function, $data)
    {
        if ($function instanceof \Closure) {
            $data = $function($data);
            return $data;
        } else
            return false;
    }

    private function paramsCheck(&$stampe, $timeInterval)
    {
        if ($stampe === NULL || $timeInterval === NULL || $timeInterval == '')
            throw new IdentifyCodeException('参数为空');
        $stampe = empty($stampe) ? 0 : $stampe;
        $stampe = is_string($stampe) ? strtotime($stampe) : $stampe;
        if ($stampe === false)
            throw new IdentifyCodeException('日期格式错误');
        if ($stampe < 0)
            throw new IdentifyCodeException('日期时间戳小于0');
        if (!is_int($timeInterval))
            throw new IdentifyCodeException('时间间隔为int型');
    }

    /**
     * 发送验证码
     */
    public function checkAndSendCode($lastStampe, $timeInterval, $function, $data)
    {
        if (!$this->checkCodeTimeOut($lastStampe, $timeInterval))
            return self::CODE_NOT_TIME_OUT;
        if (!$this->checkErrorRepeatTime($lastStampe, $timeInterval)) {
            return self::ErrorRepeatTime_NOT_PASS;
        }
        $result = $this->send($function, $data);
        if (!$result)
            return self::SEND_FAILED;
        else
            return $result;
    }

    /**
     * 验证验证码
     */
    public function checkGetCode($lastStampe, $timeInterval, $realCode, $getCode)
    {
        if ($this->checkCodeTimeOut($lastStampe, $timeInterval))
            return self::CODE_TIME_OUT;
        if (!$this->checkErrorRepeatTime($lastStampe, $timeInterval)) {
            return self::ErrorRepeatTime_NOT_PASS;
        }
        if ($this->checkIdentifyCode($realCode, $getCode))
            return true;
        else
            return self::CODE_ERROR;
    }
}