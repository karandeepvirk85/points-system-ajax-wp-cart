<?php 
// Exit if accessed directly
defined('ABSPATH') OR exit;
header("Access-Control-Allow-Origin: *");

class Login_Controller{
  function __construct(){

  }
  /*
  * This function make cUrl post request to get user data 
  */
  public static function getDataFromToken(){
    // // Set Return 
      $arrFakeReturn = array(
        'email' => 'k@yopmail.com',
        'first_name' =>'Cynthia',
        'last_name' => 'Acosta',
        'password' => 'nextpageit',
        'phone' => '918295458574',
        'points_balance' =>'250',
        'id' => 6,
        'file_id' => 345
      );
      // // Set Constants
      // $strToken = '9da12e9d-76a2-4c55-a4ae-ce97fcfda8c1';
      // $strUrl = 'https://survey-api.npit.at/api/User/Me';
      
      // $arrHeaders = array(
      //   'Content-Type: application/json',
      //   sprintf('Authorization: Bearer %s',$strToken)
      // );

      // $objCurl = curl_init($strUrl);
      // curl_setopt($objCurl, CURLOPT_HTTPHEADER, $arrHeaders);
      // curl_setopt($objCurl, CURLOPT_POST, true);
      // curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, true);
      // $arrResult = curl_exec($objCurl);
      // if(!empty($arrResult)){
      //   $arrResult = json_decode($arrResult);
      //   $arrResult = (array) $arrResult;
      //   $arrUser = (array) $arrResult['data'];
      // }
      return $arrFakeReturn;
  }
}
new Login_Controller;
?>