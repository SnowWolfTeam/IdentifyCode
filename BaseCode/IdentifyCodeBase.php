<?php
namespace IdentifyCode\BaseCode;

class IdentifyCodeBase
{

    protected $status = -1;

    /**
     * 检查获取验证码的时间间隔
     * @param $getCodeStampe
     * @param $timeInterval
     * @return bool
     */
    protected function checkGetCodeTimeInterval($getCodeStampe, $timeInterval)
    {
        $nowStampe = time();
        return ($nowStampe > ($getCodeStampe + $timeInterval)) ? true : false;
    }

    /**
     * 检查验证码是否错误
     * @param $getCode
     * @param $realCode
     * @return bool
     */
    protected function checkCodeError($getCode, $realCode)
    {
        return ($getCode === $realCode) ? true : false;
    }

    protected function checkCodeErrorRepeatTime($errorDateStampe, $timeInterval)
    {
        $nowStampe = time();
        return ($nowStampe > ($errorDateStampe + $timeInterval)) ? true : false;
    }
}