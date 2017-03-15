<?php
namespace IdentifyCode\Exception;
class IdentifyCodeException extends \Exception
{
    const PARAMS_NULL = 1000;
    const TIME_INTERVAL_INT = 1001;
    const LAST_STAMPE_TYPE_ERROR = 1002;
    const LAST_STAPME_BIGGER_EQUEAL_0 = 0x1003;
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