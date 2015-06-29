<?php

class crawl{
    
    public function setcookie(){
        $email='254430304@qq.com';
        $password='lys920507';
        $getloginurl='http://www.zhihu.com/#signin';
        $ch=curl_init($getloginurl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($ch);
        curl_close($ch);
        //模拟登陆，设置cookie
        $loginurl='http://www.zhihu.com/login';
        $postdata=array('email'=>$email,'password'=>$password);//模拟表单
        $cookie=dirname(__FILE__)."/cookie.txt";
      
        $ch=curl_init($loginurl);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        $info=curl_exec($ch);
            $res=curl_getinfo($ch);
//            var_dump($res);
            $htmlfile=fopen('login.html', 'w+');
            fwrite($htmlfile, $info);
            fclose($htmlfile);
        
        curl_close($ch);
        
       
        
    }
    /*
     * 获取页面信息，将questionurl提取出来然后进行请求
     */    
    public function getpageinfo(){
        $cookie=dirname(__FILE__)."/cookie.txt";
        $turnurl='http://www.zhihu.com/';
        $ch=curl_init($turnurl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        $info=curl_exec($ch);
//        var_dump($info);
         $htmlfile2=fopen('login2.html', 'w+');
            fwrite($htmlfile2, $info);
            fclose($htmlfile2);
            if(preg_match_all('/<a\b class="question_link".*<\/a>/', $info,$match)){
                foreach ($match[0] as $val){
                if(preg_match_all('/\"\/question\/.*\"/', $val,$url)){
                    $str=str_replace(array("'", '"'),"",$url[0][0]);
                    $questionurl="http://www.zhihu.com".$str;
                    echo $questionurl;
                    $this->getquestion($questionurl);
                }
                }
            }  else {
                die('did not preg_match');
            }
    }
    
    public function getquestion($questionurl){
        $cookie=dirname(__FILE__)."/cookie.txt";
        $ch=curl_init($questionurl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        $questioninfo=curl_exec($ch);
        $htmlfile2=fopen('question.html', 'w+');
            fwrite($htmlfile2, $questioninfo);
            fclose($htmlfile2);
    }
}



$crawl= new crawl();

//$crawl ->setcookie();

$crawl->getpageinfo();