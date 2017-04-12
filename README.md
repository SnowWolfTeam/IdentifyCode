#获取验证码

#### 异常：
* 异常文件 : IdentifyCodeException.php
* 类 : IdentifyCodeException
###### 接口
返回码 :  CODE_TIME_OUT = -1               验证码过期 
         CODE_NOT_TIME_OUT = -2           验证码没过期
         ErrorRepeatTime_NOT_PASS = -3    错误重输入期限没过
         CODE_ERROR = -4                  验证码错误
         SEND_FAILED = -5                 发送失败
         
* 1 . checkCodeTimeOut($lastStampe, $timeInterval) //检查是否过了获取验证码的期限，防止不停获取验证码
```
    $lastStampe = 最后一次获取验证码的时间戳，可以是int，也可以是"2017-02-22 10:10:10"格式的字符串,也可以为''
    $timeInterval = 获取验证码的间隔，单位秒，例如 120秒后才能获取验证码
    返回 : 过期true,没过期false
```
* 2 . checkErrorRepeatTime($lastStampe, $timeInterval) //验证码输入多次错误对用户获取验证码进行限制，检查是否过了错误限期
```
    $lastStampe = 最后一次因为多次输入错误被限制的时间戳，可以是int，也可以是"2017-02-22 10:10:10"格式的字符串,也可以为''
    $timeInterval = 获取验证码的间隔，单位秒，例如 120秒后才能获取验证码
    返回: 可以从输入true，不可以false
```
* 3 . send($function, $data)
```
    $function = 匿名函数，因为每个公司的短信发送接口调用方式不同，所以根据实际编写方法
    $data = 匿名函数传入的参数
    返回 失败返回false,成功返回匿名函数返回的数据
```
* 4 . checkIdentifyCode($realCode, $getCode)
```
    $realCode = 服务器发送后保存起来的验证码
    $getCode = 用户传过来的验证码
    返回:相同返回true，否则返回false
```
* 5 . checkAndSendCode($lastStampe, $timeInterval, $function, $data)
```
    $lastStampe = 最后一次获取验证码的时间戳，可以是int，也可以是"2017-02-22 10:10:10"格式的字符串,也可以为''
    $timeInterval = 获取验证码的间隔，单位秒，例如 120秒后才能获取验证码
    $function = 匿名函数，因为每个公司的短信发送接口调用方式不同，所以根据实际编写方法
    $data = 匿名函数传入的参数
    返回:
        CODE_NOT_TIME_OUT 
        ErrorRepeatTime_NOT_PASS 
        SEND_FAILED
        成功返回匿名函数返回的数据
```
* 6 . checkGetCode($lastStampe, $timeInterval, $realCode, $getCode)
```
    $lastStampe = 最后一次获取验证码的时间戳，可以是int，也可以是"2017-02-22 10:10:10"格式的字符串,也可以为''
    $timeInterval = 获取验证码的间隔，单位秒，例如 120秒后才能获取验证码
    $realCode = 服务器发送后保存起来的验证码
    $getCode = 用户传过来的验证码
    返回:
        CODE_NOT_TIME_OUT 
        ErrorRepeatTime_NOT_PASS 
        CODE_ERROR
        成功返回true
```