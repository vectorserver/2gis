<?php


class tWogis
{

    protected $login;
    protected $pass;
    protected $org;

    public function __construct($params)
    {
        $this->login = $params['login'];
        $this->pass = $params['pass'];
        $this->org = $params['org'];
    }


    public function getTokens(){
        //{login: "pr@golodnaya-panda.ru", password: "******"}
        $r = array("login"=>$this->login,"password"=>$this->pass);
        $url = "https://api.account.2gis.com/api/1.0/users/auth";
        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => 'Content-Type: Application/json; charset=utf-8',
                'content' => json_encode($r)
            )
        );


        $context = stream_context_create($opts);

        $result = @file_get_contents($url, false, $context);
        if($http_response_header[0] !="HTTP/1.1 200 OK"){
            $result = json_encode(array("st"=>"ERROR","data"=>$http_response_header));
        }
        return $result;
    }

    public function userData($token){
        $url = "https://api.account.2gis.com/api/1.0/users";
        $headers = array(
            'Content-Type: Application/json; charset=utf-8',
            'authorization: Bearer '.$token
        );

        $opts = array('http' =>
            array(
                'method' => 'GET',
                'header' => implode("\r\n",$headers),
               // 'content' => json_encode($params)
            )
        );


        $context = stream_context_create($opts);

        $result = @file_get_contents($url, false, $context);

        if($http_response_header[0] !="HTTP/1.1 200 OK"){
            $result = json_encode(array("st"=>"ERROR","data"=>$http_response_header));
        }
        return $result;
    }

    /*
     * @param $from | DD-MM-YYY
     * @param $to = DD-MM-YYY
     * */
    public function getStats($typeStat=false,$from,$to,$token){
        $group = 'month';
        $apiUrl = "https://api.account.2gis.com/api/1.0";

        switch ($typeStat){
            case 'bc':
                $url = "$apiUrl/stat/bc?start=$from&end=$to&group=$group&usePartnerStat=1&id=$this->org";
                break;
            case 'stat_position':
                $url = "$apiUrl/stat/proxy?method=position%2Ffirm&params=id%3D$this->org%26start%3D$from%26end%3D$to%26group%3D$group%26usePartnerStat%3D1";
                break;
            case 'stat_impression':
                $url = "$apiUrl/stat/proxy?method=impression%2Ffirm&params=id%3D$this->org%26start%3D$from%26end%3D$to%26group%3D$group%26usePartnerStat%3D1";
                break;
            case 'productsBc':
                $url = "$apiUrl/stat/productsBc?start=$from&end=$to&group=$group&id=$this->org";
                break;
            default:
                $url = $apiUrl;
        }

        $headers = array(
            'Content-Type: Application/json; charset=utf-8',
            'authorization: Bearer '.$token
        );

        $opts = array('http' =>
            array(
                'method' => 'GET',
                'header' => implode("\r\n",$headers),
                // 'content' => json_encode($params)
            )
        );


        $context = stream_context_create($opts);

        $result = @file_get_contents($url, false, $context);

        if($http_response_header[0] !="HTTP/1.1 200 OK"){
            $result = json_encode(array("st"=>"ERROR","data"=>$http_response_header));
        }
        return $result;
    }

}
