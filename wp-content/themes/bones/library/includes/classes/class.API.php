<?php
namespace Includes\Classes;

class API {
  
  private $apiKey = 'NGZjZGQzZGFjMWQ1ODAwNzU1N2ZiZjlhYzFjZWZkMGI=';
  private $apiSignature = 'MWI2MjUyYTFhMTY1ODYzMmQzZmJhMTMzNjY4ZWQzMmJlN2FhYjExYQ==';
  private $endpoint = 'https://api.uberflip.com';
  private $version = '0.1';
  private $hubID = 57003;
  
  private $itemId;
  
  private $db;
  
  private $methods = array();
  
  
  protected $dataType = 'JSON';
  
  protected $curl_status;
  protected $curl_response;
  
  public $json;
  public $current_method;
  
  public function __construct( $conn_resource ) {
    $this->db =& $conn_resource;
    $this->setMethods();
    $this->setMethod( 'GetHubs' );
  }
  
  private function setMethods() {
    $methods = array( 
      'GetTitles',
      'GetTitleDetails',
      'GetTitleFlipbookOptions',
      'GetTitleSubscriptionOptions',
      'GetTitleIssues',
      'GetIssueDetails',
      'GetIssueStatus',
      'GetIssuePages',
      'GetPageDetails',
      'GetAccountIssues',
      'GetTitleSubscribers',
      'GetSubscriberDetails',
      'GetSubscriberTitleSubscriptions',
      'GetAccounts',
      'GetLicenseUsageReport',
      'GetEmailUsageReport',
      'GetAnnotations',
      'GetHubs',
      'GetHubStreams',
      'GetHubItems',
      'GetHubItemData' 
    );
    $this->methods = $methods;
  }
  
  public function setDataType( $type ) {
    if ( $type == 'JSON' || $type == 'XML' ) {
      $this->dataType = $type;
    }
  }
  
  public function setMethod( $method ) {
    $this->current_method = $method;
  }
  
  public function setItemId( $itemId ) {
    $this->itemId = $itemId;
  }
  
  public function getMethods() {
    return $this->methods;
  }
  
  public function getDataType() {
    return $this->dataType;
  }
  
  public function getResultJson() {
    return $this->json;
  }
  
  public function makeCurlRequest( $data = NULL ) {
    
    switch ( $this->current_method ) {
      case 'GetHubItems':
      case 'GetHubStreams':
        $data['HubId'] = $this->hubID;

      case 'GetHubItemData':
        $data['ItemId'] = $this->itemId;
      break;
    }
    
    $start = array(
      'APIKey' => $this->apiKey,
      'Signature' => $this->apiSignature,
      'Version' => 0.1,
      'Method' => $this->current_method,
      'ResponseType' => 'JSON'
    );
    
    $extra_params = array();
    if ( count( $data ) ) {
      foreach( $data as $key => $value ) {
        $extra_params[$key] = $value;
      }
      $start = array_merge( $start, $extra_params );
    }
    
    $get_query = '';
    foreach ( $start as $key => $value ) {
      $get_query .= $key . '=' . $value . '&';
    }
    
    $get_query = rtrim( $get_query, '&' );
    
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $this->endpoint . '?' . http_build_query( $start ) );
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array('text/plain; charset=UTF-8') );
    //curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0); // tells curl to include headers in response
		//curl_setopt($ch, CURLOPT_TIMEOUT, 25); // times out after 25 secs
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		//curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // this line makes it work under https
		//curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE); //forces closure of connection when done
		//curl_setopt($ch, CURLOPT_POST, 0); //data sent as GET
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query( $start ) );
		
		$response = curl_exec( $ch );
		
		if ( $response === false ) {
  		echo 'cUrl error: ' . curl_error( $ch );
    } else {
      $this->json = $response;
    }
		$this->curl_status = curl_getinfo( $ch );
		//print '<pre>';var_dump($response);print '</pre>';
		//print '<pre>';var_dump($this->curl_status);print '</pre>';
		curl_close( $ch );
  }
}