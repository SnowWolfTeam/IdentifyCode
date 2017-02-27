#获取验证码

#### 异常：
* 异常错误码
```
    const PARAMS_NULL = 0x1000                      //参数错误
    const LAST_STAMPE_TYPE_ERROR = 0x1001           //时间戳变量类型错误，可以是int型也可以是日期的字符串型
    const LAST_STAPME_BIGGER_EQUEAL_0 = 0x1002      //时间戳必须大于等于0
    const TIME_INTERVAL_INT = 0x1003                //时间间隔变量必须为int型
    const TIME_INTERVAL_BIGGER_EQUEAL_0 = 0x1004    //时间间隔必须大于等于0，单位秒
```
* 如何查看运行后的异常码和异常消息
```
    异常码: IdentifyCodeException::$exceptionCode
    异常消息: IdentifyCodeException::$exceptionMsgg
```
###### 接口
* 1 . checkCodeTimeOut($lastStampe, $timeInterval) //检查是否过了获取验证码的期限，防止不停获取验证码
```
    $lastStampe = 最后一次获取验证码的时间戳，可以是int，也可以是"2017-02-22 10:10:10"格式的字符串,也可以为''
    $timeInterval = 获取验证码的间隔，单位秒，例如 120秒后才能获取验证码
    返回:实例$this
```
* 2 . checkErrorRepeatTime($lastStampe, $timeInterval) //验证码输入多次错误对用户获取验证码进行限制，检查是否过了错误限期
```
    $lastStampe = 最后一次因为多次输入错误被限制的时间戳，可以是int，也可以是"2017-02-22 10:10:10"格式的字符串,也可以为''
    $timeInterval = 获取验证码的间隔，单位秒，例如 120秒后才能获取验证码
    返回:实例$this
```
* 3 . send($function, $data)
```
    $function = 匿名函数，因为每个公司的短信发送接口调用方式不同，所以根据实际编写方法
    $data = 匿名函数传入的参数
    返回
```
* 4 . checkIdentifyCode($realCode, $getCode)
```
    $realCode = 服务器发送后保存起来的验证码
    $getCode = 用户传过来的验证码
    返回:相同返回true，否则返回false
```
* 例子：
```
    链式调用:$test->checkCodeTimeOut('2017-02-22 10:10:10',120)
                  ->checkErrorRepeatTime('2017-01-11 09:09:09, 180)
                  ->send(function($code){
                        sendCode($code);
                  },'7685995');
```