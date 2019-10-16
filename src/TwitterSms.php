<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/9
 * Time: 9:45
 * 个推短信 http://docs.getui.com/sms/
 */
namespace sms;
class TwitterSms
{
    public $appId;
    public $appkey;
    public $secret;
    public function __construct($config){
        $this->appId=$config['appId'];
        $this->appkey=$config['appkey'];
        $this->secret=$config['secret'];
    }
    /**
     * 鉴权接口
     */
    public function send($phone,$data,$smsTemplateId='20190812'){
        //鉴权url
        $url="https://openapi-smsp.getui.com/v1/sps/auth_sign";
        //将个推短信服务提供的app对应的appkey和masterSecret，可自行替换
        $appkey=$this->appkey;
        $masterSecret=$this->secret;
        $appId= $this->appId;
        $timestamp = $this->micro_time();
        $signCombination = $appkey.$timestamp.$masterSecret;
        $sign = hash("sha256", $signCombination);
        $params = array();
        $params["sign"] = $sign;
        $params["timestamp"] = $timestamp;
        $params["appId"] = $appId;
        //json序列化
        $params=json_encode($params);
        $result=$this->cscs($url,$params);
        $data_arr = json_decode($result);
       // print_r($data_arr);
        if($data_arr->result == '20000'){
            //表示鉴权成功
            $reslut = $this->SmsPushListTest($phone,$data_arr->data->authToken,$smsTemplateId,$data);
            if(json_decode($reslut)->result== '20000'){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /**
     * 群推接口
     */
    public function SmsPushListTest($phone,$authToken,$smsTemplateId,$params){
        //短信群推url
        $url="https://openapi-smsp.getui.com/v1/sps/push_sms_list";
        $appId= $this->appId;
        $requestDataObject = array();
        $requestDataObject["appId"] = $appId;
        $requestDataObject["authToken"] = $authToken;
        $requestDataObject["smsTemplateId"] = $smsTemplateId;
        $requestDataObject["smsParam"] = $params;
        $phoneNum = array();
        for($i = 0; $i < 1; $i++){
            $phoneNum[0] = md5($phone);
        }
        $requestDataObject["recNum"] = $phoneNum;
        //json序列化
        $params=json_encode($requestDataObject);
        return $this->cscs($url,$params);
    }
    public function cscs($url, $data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
    public function micro_time()
    {
        list($usec, $sec) = explode(" ", microtime());
        $time = ($sec . substr($usec, 2, 3));
        return $time;
    }
}
