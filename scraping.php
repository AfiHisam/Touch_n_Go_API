<?php
function login2($url){

	include "simple_html_dom.php";
   
    $fp = fopen("cookie.txt", "w");
    fclose($fp);
    $login = curl_init();
    curl_setopt($login, CURLOPT_COOKIEJAR, "cookie.txt");
    curl_setopt($login, CURLOPT_COOKIEFILE, "cookie.txt");
    curl_setopt($login, CURLOPT_TIMEOUT, 40000);
    curl_setopt($login, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($login, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($login, CURLOPT_URL, $url);
    $response = curl_exec($login);
    if (curl_errno($login)) die(curl_error($login));

    $html = new simple_html_dom();
    $html->load($response);

   foreach($html->find('input[name=_csrf]') as $elemet)
   	{
   		$token =  $elemet->value;

   	}
   	$data = "_csrf=".$token."&j_username=YOUR_USERNAME&j_password=YOUR_PASSWORD&proceed=Login";
   	 $token;
   	doubleCheck($url,$token);
  
}       


function login($url,$data){

    $fp = fopen("cookie.txt", "w");
    fclose($fp);
    $login = curl_init();
   curl_setopt($login, CURLOPT_COOKIEJAR, "cookie.txt");
    curl_setopt($login, CURLOPT_COOKIEFILE, "cookie.txt");
    curl_setopt($login, CURLOPT_TIMEOUT, 40000);
    curl_setopt($login, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($login, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($login, CURLOPT_URL, $url);
    curl_setopt($login, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($login, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($login, CURLOPT_POST, TRUE);
    curl_setopt($login, CURLOPT_POSTFIELDS, $data);
    ob_start();
   // curl_exec ($login);
   //return curl_exec ($login);
    $response = curl_exec($login);

    $html = new simple_html_dom();
    $html->load($response);


    foreach($html->find('table[id=l_com_xerox_ts_domain_CardRegistration] td[align=right]') as $tr){
	 @$balance[] .= $tr->plaintext;

    }
  //  return print_r($balance);

    	 foreach ($html->find('div[class=w101]') as $div) { 
			@$name[] .= $div->plaintext;
    	 }

    	 //return print_r($name);

    	 
    	 foreach ($html->find('div[class=w50]') as $div2) { 
			@$id[] .= $div2->plaintext;
    	 }

    	 $data = array("Name"=>$name[0],"Balance"=> $balance[0],"Email" =>$name[1], "AccNum" => $id[0]);
    	// return print_r($data);

    	 return str_replace('\t','',json_encode($data));
    	

    ob_end_clean();
    curl_close ($login);
    unset($login);    
}  


function doubleCheck($url,$token){

    $fp = fopen("cookie.txt", "w");
    fclose($fp);
    $login = curl_init();
    curl_setopt($login, CURLOPT_COOKIEJAR, "cookie.txt");
    curl_setopt($login, CURLOPT_COOKIEFILE, "cookie.txt");
    curl_setopt($login, CURLOPT_TIMEOUT, 40000);
    curl_setopt($login, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($login, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($login, CURLOPT_URL, $url);
    $response = curl_exec($login);
    if (curl_errno($login)) die(curl_error($login));

    $html = new simple_html_dom();
    $html->load($response);

   foreach($html->find('input[name=_csrf]') as $elemet)
   	{
   		$token2 =  $elemet->value;

   	}

   	if($token == $token2){
   		//echo "OK";
        echo login("https://tngportal.touchngo.com.my/tngPortal/resources/j_spring_security_check","_csrf=".$token."&j_username=afihisam95&j_password=Afi0163545943&proceed=Login");
   	}else{
   		//echo "Not OK";
   		$url1=$_SERVER['REQUEST_URI'];
        header("Refresh:0.00001; URL=$url1");

   	}
}     

login2("https://tngportal.touchngo.com.my/tngPortal/login");

?>