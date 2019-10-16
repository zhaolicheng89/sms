<?php
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
    $a=$t->send('13152059307',$params,'20190812');
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
     $tt=$t->send('13152059307',$params,'SMS_162520217');//验证码
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
//alyun_sms();
//twitter_sms();
//assist_sms();
