<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15
 * Time: 17:45
 * 阿里云大鱼短信 https://www.aliyun.com/product/sms?spm=5176.12825654.eofdhaal5.136.7f1f2c4aI6RKx0&aly_as=F2qr3iy0
 *
 */
namespace sms;
ini_set("display_errors", "on");
require_once dirname(__DIR__) . '/src/AliyunSms/vendor/autoload.php';
use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\SendBatchSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;
// 加载区域结点配置
Config::load();
class AliyunSms{

    static $acsClient = null;
    static $accessKeyId;
    static $AccessKeySecret;
    public $setSignName;
    public function __construct($config){
        static::$accessKeyId=$config['accessKeyId'];
        static::$AccessKeySecret=$config['AccessKeySecret'];
        $this->setSignName=$config['setSignName'];
    }
    /**
     * 取得AcsClient
     * @return DefaultAcsClient
     */
    public static function getAcsClient() {
        //产品名称:云通信短信服务API产品,开发者无需替换
        $product = "Dysmsapi";
        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";
        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)
        $accessKeyId = static::$accessKeyId; // AccessKeyId
        $accessKeySecret =static::$AccessKeySecret; // AccessKeySecret
        // 暂时不支持多Region
        $region = "cn-hangzhou";
        // 服务结点
        $endPointName = "cn-hangzhou";
        if(static::$acsClient == null) {
            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);
            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }
    /*
     * 发送短信
     * */
    public function send($mobile,$data,$templateCode) {
        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();
        //可选-启用https协议
        //$request->setProtocol("https");
        // 必填，设置短信接收号码
        $request->setPhoneNumbers($mobile);
        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign186
        $request->setSignName($this->setSignName);
        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode($templateCode);//"SMS_163245163"
        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        $request->setTemplateParam(json_encode($data, JSON_UNESCAPED_UNICODE)); // data为短信模板中字段的值
        // 可选，设置流水号
        $request->setOutId("yourOutId");
        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
        $request->setSmsUpExtendCode("1234567");
        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);
        return $acsResponse;
    }
    /**
     * 批量发送短信
     * @return stdClass
     */
    public static function sendBatchSms() {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendBatchSmsRequest();

        //可选-启用https协议
        //$request->setProtocol("https");

        // 必填:待发送手机号。支持JSON格式的批量调用，批量上限为100个手机号码,批量调用相对于单条调用及时性稍有延迟,验证码类型的短信推荐使用单条调用的方式
        $request->setPhoneNumberJson(json_encode(array(
            "1500000000",
            "1500000001",
        ), JSON_UNESCAPED_UNICODE));

        // 必填:短信签名-支持不同的号码发送不同的短信签名
        $request->setSignNameJson(json_encode(array(
            "云通信",
            "云通信"
        ), JSON_UNESCAPED_UNICODE));

        // 必填:短信模板-可在短信控制台中找到
        $request->setTemplateCode("SMS_1000000");

        // 必填:模板中的变量替换JSON串,如模板内容为"亲爱的${name},您的验证码为${code}"时,此处的值为
        // 友情提示:如果JSON中需要带换行符,请参照标准的JSON协议对换行符的要求,比如短信内容中包含\r\n的情况在JSON中需要表示成\\r\\n,否则会导致JSON在服务端解析失败
        $request->setTemplateParamJson(json_encode(array(
            array(
                "name" => "Tom",
                "code" => "123",
            ),
            array(
                "name" => "Jack",
                "code" => "456",
            ),
        ), JSON_UNESCAPED_UNICODE));

        // 可选-上行短信扩展码(扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段)
        // $request->setSmsUpExtendCodeJson("[\"90997\",\"90998\"]");

        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);

        return $acsResponse;
    }

}

