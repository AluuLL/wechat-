<?php
//此程序在Index.bak1.php的基础上增加了自定义菜单的功能
header('Content-type:text');
define("TOKEN", "weixin");
define("ACCESS_TOKEN", "vdBUmMURq_LMsa-V639VdQxPXLmusXGoBjrTM3XVqEa_GBJHmIrP2pDQwslPL9t9D8B1FoTiGxh_rt5DakcgyK1YPQkb4Y8xQJb4aY27gSMFnrQs3SvxE-wflvUZ0i2nRSMbAAAWXR");
//创建菜单
function createMenu($data){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".ACCESS_TOKEN);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$tmpInfo = curl_exec($ch);
if (curl_errno($ch)) {
  return curl_error($ch);
}

curl_close($ch);
return $tmpInfo;

}

//获取菜单
function getMenu(){
return file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".ACCESS_TOKEN);
}

//删除菜单
function deleteMenu(){
return file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".ACCESS_TOKEN);
}





$data = '{
     "button":[
     {
          "type":"click",
          "name":"首页",
          "key":"home"
      },
      {
           "type":"click",
           "name":"简介",
           "key":"introduct"
      },
      {
           "name":"菜单",
           "sub_button":[
            {
               "type":"view",
               "name":"语音舌位图",
               "url":"http://www.aluublcusait.top/wzjbs/Demo/index.html"
            },
           {
               "type":"view",
               "name":"失歌症实验",
               "url":"http://www.aluublcusait.top/wechat_MBEA/voiceTest/testMessage.php?projectCode=mbea"
            },
            {
               "type":"view",
               "name":"情感语音感知实验",
               "url":"http://www.aluublcusait.top/wechat_MBEA/voiceTest/testMessage.php?projectCode=affective"
            },
            {
               "type":"view",
               "name":"低通滤波情感实验",
               "url":"http://www.aluublcusait.top/wechat_MBEA/voiceTest/testMessage.php?projectCode=affective1"
            },
            {
	      "type":"view",
	      "name":"汉语词典",
              "url":"http://www.zdic.net" 
            }]
       }]
}';




echo createMenu($data);
//echo getMenu();
//echo deleteMenu();
$wechatObj = new wechatCallbackapiTest();
if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            header('content-type:text');
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $ev = $postObj->Event;
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
			if ($ev == "subscribe"){
  $msgType = "text";
  $contentStr = "欢迎关注北京语言大学智能语音习得SAIT实验室！";
  $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
  echo $resultStr;
}

			
            if(!empty($keyword)){
            
                $msgType = "text";
                switch($keyword){
                
                    case "你好":$contentStr ="<a href='http://www.aluublcusait.top/wechat_MBEA/voiceTest/testMessage.php?projectCode=mbea'>失歌症测试</a>"."\n"."<a href='http://www.aluublcusait.top/wechat_MBEA/voiceTest/testMessage.php?projectCode=affective'>情感语音感知实验</a>"."\n"."<a href='http://www.aluublcusait.top/wzjbs/Demo/index.html'>语音舌位图展示</a>"."\n"."<a href='http://www.aluublcusait.top/wechat_MBEA/voiceTest/testMessage.php?projectCode=affective1'>低通滤波情感实验</a>";break;
                    case "测试":$contentStr ="<a href='http://www.aluublcusait.top/wechat_MBEA/voiceTest/testMessage.php?projectCode=mbea'>失歌症测试</a>"."\n"."\n"."<a href='http://www.aluublcusait.top/wzjbs/Demo/index.html'>语音舌位图展示</a>"."\n"."\n"."<a href='http://www.aluublcusait.top/wechat_MBEA/voiceTest/testMessage.php?projectCode=affective'>情感语音感知实验</a>"."\n"."\n"."<a href='http://www.aluublcusait.top/wechat_MBEA/voiceTest/testMessage.php?projectCode=affective1'>低通滤波情感实验</a>";break;
                    case "嗨":$contentStr ="<a href='http://www.aluublcusait.top/wechat_MBEA/voiceTest/testMessage.php?projectCode=mbea '>点击进入失歌症测试，=>来看看你是否是失歌症吧</a>";break;
                    case "哈喽":$contentStr="<a href='http://www.aluublcusait.top/wzjbs/Demo/index.html'>语音舌位图展示=></a>";break;
                    case "test":$contentStr="<a href='http://www.zdic.net'>汉典</a>";break; 
                    default:$contentStr ="你好，谢谢关注，可以输入测试进入现有功能展示哦";break;
                    
                }
                
                 $time = time();
            }
            
		
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
			echo $resultStr;
            exit;
        }else{
            echo "";
            exit;
        }
    }
}

?>
