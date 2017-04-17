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
    const CODE_ERROR_TIMES_REACH = -6;
    public $maxErrorTimes = 0;

    public function checkCodeTimeOut($lastStampe, $timeInterval)
    {
        $this->paramsCheck($lastStampe, $timeInterval);
        return $this->checkGetCodeTimeInterval($lastStampe, $timeInterval);
    }

    public function checkErrorRepeatTime($lastStampe, $timeInterval)
    {
        $this->paramsCheck($lastStampe, $timeInterval);
        $result = $this->checkCodeErrorRepeatTime($lastStampe, $timeInterval);
        return $result;
    }

    public function checkIdentifyCode($realCode,
                                      $getCode,
                                      &$nowErrorTimes = -1,
                                      $maxErrorTimes = -1)
    {
        $result = $this->checkCodeError($getCode, $realCode);
        if ($nowErrorTimes != -1) {
            if (!is_int($nowErrorTimes) || $nowErrorTimes < 0)
                throw new IdentifyCodeException('错误次数参数格式错误');
            $maxErrorTimes = $maxErrorTimes == -1 ? $this->maxErrorTimes : $maxErrorTimes;
            $maxErrorTimes = (is_int($maxErrorTimes) && $maxErrorTimes >= 0) ? $maxErrorTimes : 0;
            if ($result) {
                return true;
            } else {
                $nowErrorTimes++;
                if ($nowErrorTimes >= $maxErrorTimes) {
                    $nowErrorTimes = $maxErrorTimes;
                    return self::CODE_ERROR_TIMES_REACH;
                } else
                    return false;
            }
        }
        return $result;
    }

    public function send($function, $data=NULL)
    {
        if ($function instanceof \Closure) {
            $data = $function($data);
            return $data;
        } else
            return false;
    }

    /**
     * 发送验证码
     */
    public function checkAndSendCode($lastStampe,
                                     $timeInterval,
                                     $errorLastStampe,
                                     $errorTimeInterval,
                                     $function,
                                     $data = NULL
    )
    {
        if (!$this->checkCodeTimeOut($lastStampe, $timeInterval))
            return self::CODE_NOT_TIME_OUT;
        if (!$this->checkErrorRepeatTime($errorLastStampe, $errorTimeInterval)) {
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
    public function checkGetCode($lastStampe,
                                 $timeInterval,
                                 $errorLastStampe,
                                 $errorTimeInterval,
                                 $realCode,
                                 $getCode,
                                 &$nowErrorTimes = -1,
                                 $maxErrorTimes = -1
    )
    {
        if ($this->checkCodeTimeOut($lastStampe, $timeInterval))
            return self::CODE_TIME_OUT;
        if (!$this->checkErrorRepeatTime($errorLastStampe, $errorTimeInterval)) {
            return self::ErrorRepeatTime_NOT_PASS;
        }
        $result = $this->checkIdentifyCode($realCode, $getCode, $nowErrorTimes, $maxErrorTimes);
        if ($result == self::CODE_ERROR_TIMES_REACH)
            return $result;
        elseif ($result === false)
            return self::CODE_ERROR;
        else
            return true;
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

}