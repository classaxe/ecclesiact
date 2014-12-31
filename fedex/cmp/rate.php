<?php
define("FEDEX_VERSION","1.0.4");
/*
1.0.4 (2008-11-05)
  1) Changed package weight to 0.5KG so that FEDEX_ENVELOPE can be used without
     FEDEX substituting the product for FEDEX_PAK
1.0.3 (2008-05-15)
  1) Change to fedex_get_rate() to only log request when called from ajax
     directory, now ignores log attempt if called by server directly.
1.0.2 (2008-05-14)
  1) Data for shipping address now urldecoded - prevents spaces from becoming %20
1.0.1 (2008-04-03)
  1) Changes to use version 3 of Fedex services
*/

function fedex_get_rate($config,$data) {
//  y($config);die;
  require_once('fedex-common.php5');

  ini_set("soap.wsdl_cache_enabled", "0");
  $Obj_country =    new Country();
  $Obj_XML =        new SimpleXMLElement(urldecode($data));
  $ship =           $Obj_XML;
  $client =
    new SoapClient(
      SYS_SHARED.'fedex/'.(isset($config['LiveGateway']) && $config['LiveGateway']==1 ? "RateService_v3_live.wsdl" : "RateService_v3_test.wsdl"),
      array('trace' => 1)
    );
  $request['Version'] =
    array(
      'ServiceId' => 'crs',
      'Major' => '3',
      'Intermediate' => '0',
      'Minor' => '0'
    );
  $request['WebAuthenticationDetail'] =
    array('UserCredential' =>
      array(
        'Key' =>            $config['UserCredential_key'],
        'Password' =>       $config['UserCredential_password']
      )
    );
  $request['ClientDetail'] =
    array(
      'AccountNumber' =>    $config['AccountNumber'],
      'MeterNumber' =>      $config['MeterNumber']
    );
  $request['TransactionDetail'] =
    array(
      'CustomerTransactionId' => ' *** Rate Request v3 using PHP ***'
    );

  $request['Origin'] =
    array(
      'StreetLines' =>            array ($config['AAddress1'],$config['AAddress2']),
      'City' =>                   $config['ACity'],
      'StateOrProvinceCode' =>    str_replace('_','',$config['ASpID']),
      'PostalCode' =>             str_replace(' ','',$config['APostal']),
      'CountryCode' =>            $Obj_country->get_iso3166($config['ACountryID'])
    );
  $request['ServiceType'] =     $config['ServiceType'];
  $request['PackagingType'] =   $config['PackagingType'];
  $request['DropoffType'] =     $config['DropoffType'];
  $request['Payment'] =
    array(
      'PaymentType' => 'SENDER'
    );

  $request['ShipDate'] = date('Y-m-d');
  $request['RateRequestTypes'] = 'ACCOUNT';
  $request['PackageCount'] = 1; // currently only one occurrence of RequestedPackage is supported

  $request['Destination'] = array(
    'StreetLines' =>        array((string)$ship->SAddress1,(string)$ship->SAddress2), // Destination details
    'City' =>               (string)$ship->SCity,
    'StateOrProvinceCode' =>str_replace('_','',(string)$ship->SSpID),
    'PostalCode' =>         str_replace(' ','',(string)$ship->SPostal),
    'CountryCode' =>        $Obj_country->get_iso3166((string)$ship->SCountryID)
  );

  $request['Packages'] =
    array(
      0 => array(
        'Weight' => array(
          'Value' => 0.5,
          'Units' => 'KG'
        ),
        'InsuredValue' => array(
          'Amount' => 1,
          'Currency' => 'CAD'
        ),
        'Dimensions' => array(
          'Length' => '30',
          'Width' => '30',
          'Height' => '1',
          'Units' => 'CM'
        )
      )
    );
  try {
    $response =     $client->getRate($request);
    $_request =     str_replace("\n","\r\n",print_r($request,true));
    $_response =    str_replace("\n","\r\n",print_r($response,true));
    $data =
       "REQUEST:\r\n".$_request."\r\n\r\n"
      ."RESPONSE:\r\n".$_response;
    $Obj = new FileSystem();
    if(strpos($_SERVER['SCRIPT_NAME'],'ajax')) {
      // Then being called by ajax rather than server-side
      $Obj->write_file('../logs/fedex_latest_request.txt',$data);
    }
    if ($response -> HighestSeverity == 'FAILURE' || $response -> HighestSeverity == 'ERROR'){
      writeToLog($client);    // Write to log file
    }
    return $response;
  }
  catch (SoapFault $exception) {
    $result = array(
      'error' =>    $exception,
      'method' =>   $client,
      'cost' =>     0) ;
    writeToLog($exception);
    return $result;
  }
}
?>