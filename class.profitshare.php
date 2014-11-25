<?php 
class profitshare {
    
    const API_URL = "http://api.profitshare.ro";
    
    private $api_user;
    private $api_key;
    
    public function __construct($api_user, $api_key){
        $this->api_user = $api_user;
        $this->api_key = $api_key;
    }
    
    public function getAdvertisersList() {
        $return = $this->apiCall("affiliate-advertisers");
        
        return $return;
    }
    
    public function getAdvertisersCampaigns($page) {
        $return = $this->apiCall("affiliate-campaigns", array("page" => $page));

        return $return;
    }
    
    public function getLinks($links) {
        $return = $this->apiCall("affiliate-links", $links, "POST");
        
        return $return;
    }
    
    public function getCommissions($filters, $page) {
        $return = $this->apiCall("affiliate-commissions", array("filters" => $filters, "page" => $page));
    
        return $return;
    }
    
    public function getProducts($advertisers, $page) {
        $return = $this->apiCall("affiliate-products", array("filters" => array("advertiser" => $advertisers), "page" => $page));
    
        return $return;
    }
    
    private function apiCall($api, $params = array(), $type = "GET"){
        $url = self::API_URL . '/' . $api . '/?';
        $query_string = '';
        
        if(!empty($params) && is_array($params) && $type == "GET") {
            $query_string = http_build_query($params);
            $query_string = urldecode($query_string);
        }

        $spider = curl_init();
        curl_setopt($spider, CURLOPT_HEADER, false);
        curl_setopt($spider, CURLOPT_URL, $url. $query_string);
        curl_setopt($spider, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($spider, CURLOPT_TIMEOUT, 10);
        curl_setopt($spider, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($spider, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($spider, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)');
        
        if($type == "POST") {
            curl_setopt($spider, CURLOPT_POST, true);
            curl_setopt($spider, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        $profitshare_login = array(
            'api_user'  => $this->api_user,
            'api_key'   => $this->api_key,
        );
        $date = gmdate('D, d M Y H:i:s T', time());
        $signature_string = $type . $api . '/?' . $query_string . '/'.$profitshare_login['api_user'] . $date;
        $auth = hash_hmac('sha1', $signature_string, $profitshare_login['api_key']);
        $extra_headers = array( "Date: {$date}",
            "X-PS-Client: {$profitshare_login['api_user']}",
            "X-PS-Accept: json",
            "X-PS-Auth: {$auth}"
        );
        
        curl_setopt($spider, CURLOPT_HTTPHEADER, $extra_headers);
        $output = curl_exec($spider); curl_close($spider);
        
        $respons = json_decode($output);
        if(isset($respons->error)) {
            throw new Exception($respons->error->message);
        }

        return $respons->result;
    }
}
?>
