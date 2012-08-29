<?php
class eBayShopping{
    //variable instantiation
    private $uri_shopping = 'http://open.api.ebay.com/shopping';    //Production Shopping API endpoint
    private $appid = 'YOUR APPLICATION ID';                         //Production application ID
    private $version = '787';                                       //API version
    private $format = 'JSON';                                       //API response format
    private $siteid = '0';                                          //Site to search (Currently U.S.) - full site list at http://developer.ebay.com/devzone/shopping/docs/callref/types/SiteCodeType.html
    private $standard_qstring = '';                                 //Standard query string parameters to be applied to all requests
    
    /**
    * Constructor
    *
    * Sets a standard set of query string parameters that are shared
    * amongst all Shopping API HTTP requests
    * 
    */
    public function __construct(){
        $this->standard_qstring = sprintf("%s?responseencoding=%s&appid=%s&siteid=%s&version=%s", $this->uri_shopping, $this->format, $this->appid, $this->siteid, $this->version);
    }
    
    /**
    * Find
    *
    * Provides functionality for product discovery by providing access to all
    * finding endpoints from the Shopping API.  Available request types are:
    * FindHalfProducts || FindPopularItems || FindPopularSearches ||
    * FindProducts || FindReviewsAndGuides
    * 
    */
    public function find($request, $query, $num_entries = 5, $alt_fields = null){
        //determine if requested API call is available from endpoints
        $request_types = array('FindHalfProducts', 'FindPopularItems', 'FindPopularSearches', 'FindProducts', 'FindReviewsAndGuides');
        if (! in_array($request, $request_types)){
            return 'Invalid request type, please use one of the following: ' . implode(', ', $request_types);
        }
        
        //build out string of alternate parameters that have been supplied to method
        $concat_fields = '';
        if (count($alt_fields) > 0){
            foreach ($alt_fields as $field => $value){
                $concat_fields .= "&$field=$value";
            }
        }
        
        //create correct max results string depending on API request
        $num_string = ($request == 'FindPopularSearches' || $request == 'FindReviewsAndGuides') ? 'MaxResultsPerPage=' . $num_entries : 'MaxEntries=' . $num_entries;
        
        //build API HTTP request string
        $uri = sprintf("%s&callname=$request&QueryKeywords=%s&%s%s",
                       $this->standard_qstring,
                       urlencode($query),
                       $num_string,
                       $concat_fields);
        
        return json_decode($this->curl($uri));
    }
    
    /**
    * Get
    *
    * Provides product information extraction by introducing access to all 
    * get API methods from the Shopping API, with the exception of GeteBayTime.
    * Available request types are: FindHalfProducts || FindPopularItems ||
    * FindPopularSearches || FindProducts || FindReviewsAndGuides
    */
    public function get($request, $ids, $alt_fields = null){
        //determine if requested API call is available from endpoints
        $request_types = array('GetCategoryInfo', 'GetItemStatus', 'GetMultipleItems', 'GetShippingCosts', 'GetSingleItem', 'GetUserProfile');
        if (! in_array($request, $request_types)){
            return 'Invalid request type, please use one of the following: ' . implode(', ', $request_types);
        }
        
        //build out string of alternate parameters that have been supplied to method
        $concat_fields = '';
        if (count($alt_fields) > 0){
            foreach ($alt_fields as $field => $value){
                $concat_fields .= "&$field=$value";
            }
        }
        
        //prepare key field for id search - category, user or item
        $id_field_key = 'ItemID';
        if ($request == 'GetCategoryInfo'){ $id_field_key = 'CategoryID'; }
        if ($request == 'GetUserProfile'){ $id_field_key = 'UserID'; }
        
        //build API HTTP request string
        $uri = sprintf("%s&callname=%s&%s=%s%s",
                       $this->standard_qstring,
                       $request,
                       $id_field_key,
                       $ids,
                       $concat_fields);
        
        return json_decode($this->curl($uri));
    }
    
    /**
    * Get eBay Time
    *
    * Obtains the current eBay server time
    * 
    */
    public function getTime(){
        //build API HTTP request string
        $uri = sprintf("%s&callname=GeteBayTime",
                       $this->standard_qstring);
        
        return json_decode($this->curl($uri));
    }
    
    /**
    * cURL
    *
    * Standard cURL function to run GET & POST requests
    * 
    */
    private function curl($url, $method = 'GET', $headers = null, $postvals = null){
        $ch = curl_init($url);
           
        if ($method == 'GET'){
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        } else {
            $options = array(
                CURLOPT_HEADER => true,
                CURLINFO_HEADER_OUT => true,
                CURLOPT_VERBOSE => true,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $postvals,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_TIMEOUT => 3
            );
            curl_setopt_array($ch, $options);
        }
           
        $response = curl_exec($ch);
        curl_close($ch);
            
        return $response;
    }
}
?>
