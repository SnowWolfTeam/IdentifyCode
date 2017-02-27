<?php
namespace IdentifyCode\Exception;
class IdentifyCodeException extends \Exception
{
    const PARAMS_NULL = 0x1000;
    const LAST_STAMPE_TYPE_ERROR = 0x1001;
    const LAST_STAPME_BIGGER_EQUEAL_0 = 0x1002;
    const TIME_INTERVAL_INT = 0x1003;
    const TIME_INTERVAL_BIGGER_EQUEAL_0 = 0x1004;

    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }

    public function __toString()
    {
        return __CLASS__ . ":[{Line:$this->line}]: {$this->message}\n";
    }
}