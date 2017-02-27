<?php
namespace IdentifyCode;

use IdentifyCode\BaseCode\IdentifyCodeBase;
use IdentifyCode\Exception\IdentifyCodeException;

class IdentifyCodeSend extends IdentifyCodeBase
{
    const IDENTIFY_CODE_TIMEOUT = 0x1000;
    const IDENTIFY_CODE_ERROR_NOT_REPEAT = 0x1001;
    const CUSTOM_FUNC_ERROR = 0x1002;
    public $exceptionCode = -1;
    public $exceptionMsg = '';
    public $execuResult = true;

    public function checkCodeTimeOut($lastStampe, $timeInterval)
    {
        if ($this->execuResult) {
            try {
                $this->paramsCheck($lastStampe, $timeInterval);
                $result = $this->checkGetCodeTimeInterval($lastStampe, $timeInterval);
                if ($result)
                    return $this;
                else
                    throw new IdentifyCodeException('获取验证码不能过于频繁', self::IDENTIFY_CODE_TIMEOUT);
            } catch (\Exception $e) {
                $this->exceptionCode = $e->getCode();
                $this->exceptionMsg = $e->getMessage();
                $this->execuResult = false;
                return $this;
            }
        } else
            return $this;
    }

    public function checkErrorRepeatTime($lastStampe, $timeInterval)
    {
        if ($this->execuResult) {
            try {
                $this->paramsCheck($lastStampe, $timeInterval);
                echo $lastStampe;
                $result = $this->checkCodeErrorRepeatTime($lastStampe, $timeInterval);
                if ($result)
                    return $this;
                else
                    throw new IdentifyCodeException('错误码错误次数限制还没恢复', self::IDENTIFY_CODE_ERROR_NOT_REPEAT);
            } catch (\Exception $e) {
                $this->exceptionCode = $e->getCode();
                $this->exceptionMsg = $e->getMessage();
                $this->execuResult = false;
                return $this;
            }
        } else
            return $this;
    }

    public function checkIdentifyCode($realCode, $getCode)
    {
        return $this->checkCodeError($getCode, $realCode);
    }

    public function send($function, $data)
    {
        if ($this->execuResult) {
            if ($function instanceof \Closure) {
                $data = $function($data);
                return $data;
            } else
                $this->exceptionCode = self::CUSTOM_FUNC_ERROR;
            $this->exceptionMsg = '发送验证码函数必须以参数传进来';
            $this->execuResult = false;
            return false;
        } else
            return false;
    }

    private function paramsCheck(&$stampe, $timeInterval)
    {
        if ($stampe === NULL || $timeInterval === NULL || $timeInterval == '')
            throw new IdentifyCodeException('参数为空', IdentifyCodeException::PARAMS_NULL);
        $stampe = empty($stampe) ? 0 : $stampe;
        $stampe = is_string($stampe) ? strtotime($stampe) : $stampe;
        if ($stampe === false)
            throw new IdentifyCodeException('日期格式错误', IdentifyCodeException::LAST_STAMPE_TYPE_ERROR);
        if ($stampe < 0)
            throw new IdentifyCodeException('日期时间戳小于0', IdentifyCodeException::LAST_STAPME_BIGGER_EQUEAL_0);
        if (!is_int($timeInterval))
            throw new IdentifyCodeException('时间间隔为int型', IdentifyCodeException::TIME_INTERVAL_INT);
        if ($stampe < 0)
            throw new IdentifyCodeException('', IdentifyCodeException::TIME_INTERVAL_BIGGER_EQUEAL_0);
    }
}