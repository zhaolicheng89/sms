# 环境要求

最低php5.4,建议在`PHP7`上运行以获取最佳性能。

#SDK介绍# 

* **git地址**：https://github.com/zhaolicheng89/sms
* **packagist地址**：https://packagist.org/packages/zhaolicheng89/sms

**若对您有帮助，可以赞助并支持下作者哦，谢谢！**
--
![](http://www.iexiong.com/pay.png)

* **开发交流QQ群：945105509**
**`SDK`版权声明**
--
* 此SDK基于`MIT`协议发布，任何人可以用在任何地方，不受约束
* 此SDK部分代码来自互联网，若有异议，可以联系作者进行删除

#SDK相关介绍

目前支持以下短信sdk:
* 阿里云大云短信(https://www.aliyun.com/product/sms?spm=5176.12825654.eofdhaal5.136.7f1f2c4aI6RKx0&aly_as=F2qr3iy0)
* 助通(http://www.ztinfo.cn/)
* 个推短信(http://docs.getui.com/sms/)

#使用方法
* 1、使用`composer`安装
```
composer require zhaolicheng89/sms
```
```php
/*
* 个推短信 http://docs.getui.com/sms/
 */
 function twitter_sms(){
     $config=[
         'appId'=>'',
         'appkey'=>'',
         'secret'=>'',
     ];
    // $params为短信模板中字段的值
     $t=new \sms\TwitterSms($config);
     $params=[
         'code'=>rand (1000,9999),
     ];
     $a=$t->send('13152059301',$params,'20190812');
     var_dump($a);
     return 1;
 }
 /*
 * 阿里云大鱼短信 https://www.aliyun.com/product/sms?spm=5176.12825654.eofdhaal5.136.7f1f2c4aI6RKx0&aly_as=F2qr3iy0
  */
 function alyun_sms()
 {
     $config=[
         'accessKeyId'=>'',
         'AccessKeySecret'=>'',
         'setSignName'=>'',
     ];
     $t=new \sms\AliyunSms($config);
     //$params为短信模板中字段的值
     $params=[
         "name"=>'赵先生',
         "store"=>'清华科技园',
     ];
     //$tt=$t->sendSms('13152059301','SMS_162520217',$params);//验证码
      $tt=$t->send('13152059301',$params,'SMS_164511626');//短信
      print_r($tt);
      return $tt;
 }
 /*
  * 助通短信 http://www.ztinfo.cn/
 */
 function assist_sms(){
     $config=[
         'accessKeyId'=>'',
         'AccessKeySecret'=>'',
     ];
     $sms_code = rand(1000, 9999);
     $params='您的验证码是' . $sms_code . '，请不要把验证码泄露给其他人。如非本人操作可忽略本条信息！【迪尔西科技】';//短信内容
     $sendAPI = new \sms\Assist($config);
     $return = $sendAPI->send('13152059301',$params);
     print_r($return);
     return $return;
 }
```
* 2、引入类调用
```php
require_once 'src/TwitterSms.php';
require_once 'src/AliyunSms.php';
require_once 'src/Assist.php';
/*
* 个推短信 http://docs.getui.com/sms/
 */
function twitter_sms(){
    $config=[
        'appId'=>'',
        'appkey'=>'',
        'secret'=>'',
    ];
   // $params为短信模板中字段的值
    $t=new \sms\TwitterSms($config);
    $params=[
        'code'=>rand (1000,9999),
    ];
    $a=$t->send('13152059301',$params,'20190812');
    var_dump($a);
    return 1;
}
/*
* 阿里云大鱼短信 https://www.aliyun.com/product/sms?spm=5176.12825654.eofdhaal5.136.7f1f2c4aI6RKx0&aly_as=F2qr3iy0
 */
function alyun_sms()
{
    $config=[
        'accessKeyId'=>'',
        'AccessKeySecret'=>'',
        'setSignName'=>'',
    ];
    $t=new \sms\AliyunSms($config);
    //$params为短信模板中字段的值
    $params=[
        "name"=>'赵先生',
        "store"=>'清华科技园',
    ];
    //$tt=$t->sendSms('13152059301','SMS_162520217',$params);//验证码
     $tt=$t->send('13152059301',$params,'SMS_164511626');//短信
     print_r($tt);
     return $tt;
}
/*
 * 助通短信 http://www.ztinfo.cn/
*/
function assist_sms(){
    $config=[
        'accessKeyId'=>'',
        'AccessKeySecret'=>'',
    ];
    $sms_code = rand(1000, 9999);
    $params='您的验证码是' . $sms_code . '，请不要把验证码泄露给其他人。如非本人操作可忽略本条信息！【迪尔西科技】';//短信内容
    $sendAPI = new \sms\Assist($config);
    $return = $sendAPI->send('13152059301',$params);
    print_r($return);
    return $return;
}
alyun_sms();
twitter_sms();
assist_sms();

```


