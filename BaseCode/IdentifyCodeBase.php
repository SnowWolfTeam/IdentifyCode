<?php
namespace IdentifyCode\BaseCode;

class IdentifyCodeBase
{
    /**
     * 检查获取验证码的时间间隔
     */
    protected function checkGetCodeTimeInterval($getCodeStampe, $timeInterval)
    {
        $nowStampe = time();
        return ($nowStampe > ($getCodeStampe + $timeInterval)) ? true : false;
    }

    /**
     * 检查验证码是否错误
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