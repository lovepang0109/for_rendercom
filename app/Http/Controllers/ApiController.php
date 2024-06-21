<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use stdClass;

use function PHPSTORM_META\map;

class ApiController extends Controller
{
  //
  public function login(Request $request)
  {

    // Log::debug('An informational message.');

    try{

      $validateUser = Validator::make($request->all(), 
      [
          'username' => 'required',
          'password' => 'required'
      ]);

      if($validateUser->fails()){
          return response()->json([
              'status' => false,
              'message' => 'validation error',
              'errors' => $validateUser->errors()
          ], 401);
      }

      //WS connect      
      $soap = $this->getSoap();

      if(!$soap){
        return response()->json([
          'status' => false,
          'message' => "SOAP Object didn't created successfully!",
        ]);
      }

      $encPass = md5( $request->password."R2Tr0||D2" );

      $response = $soap->ConsultarLoginDashBoardBulk(["cLogin" => $request->username, "cPassword" => $encPass, "cIP" => $request->ip()]);

      if($response->cDescripcion == 'OK'){

        if($response->cLastFecha){
          $tmp = urldecode($response->cLastFecha);
          $time = explode(',', $tmp);
          $response->cLastFecha = date('Y-m-d H:i:s', strtotime($time[0]));
        }

        return response()->json([
          'status' => true,
          'message' => $response,
        ]);

      }else{

        return response()->json([
            'status'  => false,
            'message' => 'Incorrect Credentional',
            'errors'  => "User does not exist"
        ], 401);
      }        

    }catch(Exception $e){

      return response()->json([
        'status' => false,
        'message' => $e->getMessage(),
      ]);
    }

  }

  /**
   * Dashobard API
   */
  public function dashboard(Request $request)
  {
    try{

      $validateUser = Validator::make($request->all(), 
      [
          'username' => 'required',
          'password' => 'required'
      ]);

      if($validateUser->fails()){
          return response()->json([
              'status' => false,
              'message' => 'validation error',
              'errors' => $validateUser->errors()
          ], 401);
      }
      
      //###################################################################
      
      $tuition = date('G') > 8 ? date('Ymd')."8" : date('Ymd', time()-86400)."8";

      // @$file = json_decode( file_get_contents(public_path('json/0_indexAjax.json')), true );
      @$file = json_decode( file_get_contents(base_path('tmp/0_indexAjax.json')), true );


      if( isset($file['source']) && $file['source'] == $tuition ){
        $principal = $file['data']['principal'];
        $arrayCliente = $file['data']['arrayCliente'];
        $arrayClienteAyer = $file['data']['arrayClienteAyer'];
        $arrayProveedor = $file['data']['arrayProveedor'];
        $arrayProveedorAyer = $file['data']['arrayProveedorAyer'];
        $arrayPaises = $file['data']['arrayPaises'];
        $arrayPaisesAyer = $file['data']['arrayPaisesAyer'];
        $countChartPais = $file['data']['countChartPais'];
        $arrayChartPais = $file['data']['arrayChartPais'];
        $arrayChartPaisKeys = $file['data']['arrayChartPaisKeys'];        
        
      }else{

        //get SOAP
        $soap = $this->getSoap();

        $encPass    = md5( $request->password."R2Tr0||D2" );
        $yesterday  = date('d-m-Y', time()-86400);
        $week       = date('d-m-Y',time()-604800);        
        
        $principal    = $soap->mostrarDashboardBulkPrincipal(["cLogin"    => $request->username, 
                                                              "cPassword" => $encPass]);
        $topPrincipal = $soap->mostrarDashboardBulkPrincipal_TopPaises(["cLogin"        => $request->username, 
                                                                        "cPassword"     => $encPass, 
                                                                        "cFechaInicio"  => $week, 
                                                                        "cFechaFin"     => $yesterday]);        

        // CLIENTES //
        $rowname = 'tTop10ClientesHoy';
        $arrayCliente = $this->objectToArray ($principal, $rowname, 'IDCliente');
        $arrayPaisesAyer = null;
        
        // Clientes_ayer to array con cambio de estructura.
        $rowname = 'tTop10ClientesMes';
        $rownameRow = $rowname.'Row';
        if(isset($principal->$rowname->$rownameRow)) {
          $arrayClienteAyer = $principal->$rowname->$rownameRow;
        }
        if (isset($arrayClienteAyer)) {
          if (!is_array($arrayClienteAyer)){
            $temp = (array)$arrayClienteAyer;
            $arrayClienteAyer->$temp->IDCliente = $temp;
          }else{
            $aux = array();
            foreach ($arrayClienteAyer as $value) {
              $aux[$value->IDCliente] = $value;
            }
            $arrayClienteAyer = $aux;
          }
        }

        
        // PROVEEDORES //
        $rowname = 'tTop10ProveedoresHoy';
        $arrayProveedor = $this->objectToArray ($principal, $rowname, 'IDProveedor');
        
        // Proveedores_ayer to array con cambio de estructura.
        $rowname = 'tTop10ProveedoresMes';
        $rownameRow = $rowname.'Row';

        if(isset($principal->$rowname->$rownameRow)) {
          $arrayProveedorAyer = $principal->$rowname->$rownameRow;
        }

        if (isset($arrayProveedorAyer)) {

          if (!is_array($arrayProveedorAyer)){
            $temp = (array)$arrayProveedorAyer;
            $arrayProveedorAyer->$temp->IDProveedor = $temp;
          } else {
            $aux = array();
            foreach ($arrayProveedorAyer as $value) {
              $aux[$value->IDProveedor] = $value;
            }
            $arrayProveedorAyer = $aux;
          }

        }

        // PAISES - TABLA //
        $rowname = 'tTopPaisesAyer';
        $arrayPaises = $this->objectToArray($topPrincipal, $rowname, 'Pais_Destino');

        $xml = simplexml_load_file(public_path("langs/en.xml"));

        $arrayPaisesCVT = array_map(function($paise) use ($xml){
          $key = 'p'.$paise->Pais_Destino;
          return [
            "Pais_Destino" => $paise->Pais_Destino,
            "Mensajes"     => $paise->Mensajes,
            "code"         => (string)$xml->listapaises->$key
          ];
        }, $arrayPaises);

        // Paises_ayer to array con cambio de estructura.
        $rowname = 'tTopPaisesMes';
        $rownameRow = $rowname.'Row';
        if(isset($topPrincipal->$rowname->$rownameRow)) {
          $arrayPaisesAyer = $topPrincipal->$rowname->$rownameRow;
        }

        if (isset($arrayPaisesAyer)) {
          if (!is_array($arrayPaisesAyer)){
            $temp = (array)$arrayPaisesAyer;
            $arrayPaisesAyer->$temp->Pais_Destino = $temp;
          } else {
            $aux = array();
            foreach ($arrayPaisesAyer as $value) {
              $aux[$value->Pais_Destino] = $value;
            }
            $arrayPaisesAyer = $aux;
          }
        }

        // PAISES - GRAFICA //
        $rowname = 'tResumenPaisFecha';
        $arrayChartPais = $this->objectToArray($topPrincipal, $rowname);
        $countChartPais = is_array($arrayChartPais) ? count($arrayChartPais) : 0;

        $aux = array();
        if( $countChartPais > 0 ){
          foreach ($arrayChartPais as $value) {
            @$aux[$value->Fecha][$value->Pais_Destino] += $value->Enviados;
            @$arrayChartPaisKeys = array_keys($aux[$value->Fecha]);
            unset($arrayChartPaisKeys[array_search('0',$arrayChartPaisKeys)]);
          }
        }
        $arrayChartPaisKeys[] = 0;
        $arrayChartPais = $aux;

        $arrayChartPaisKeysCVT = [];
        foreach($arrayChartPaisKeys as $key => $value){
          $key = "p$value";
          array_push($arrayChartPaisKeysCVT, [(string)$xml->listapaises->$key => $value]);
        }
        
        $principal		 	    = json_decode(json_encode($principal),true);
        $arrayCliente	 	    = json_decode(json_encode($arrayCliente),true);
        $arrayClienteAyer	  = json_decode(json_encode($arrayClienteAyer),true);
        $arrayProveedor	 	  = json_decode(json_encode($arrayProveedor),true);
        $arrayProveedorAyer	= json_decode(json_encode($arrayProveedorAyer),true);
        $arrayPaises		    = json_decode(json_encode($arrayPaisesCVT),true);
        $arrayPaisesAyer	  = json_decode(json_encode($arrayPaisesAyer),true);
        $countChartPais		  = json_decode(json_encode($countChartPais),true);
        $arrayChartPais		  = json_decode(json_encode($arrayChartPais),true);
        $arrayChartPaisKeys	= $arrayChartPaisKeysCVT;

        $file = [
          "source" => $tuition,
          "data" => [
            "principal" => $principal,
            "arrayCliente" => $arrayCliente,
            "arrayClienteAyer" => $arrayClienteAyer,
            "arrayProveedor" => $arrayProveedor,
            "arrayProveedorAyer" => $arrayProveedorAyer,
            "arrayPaises" => $arrayPaises,
            "arrayPaisesAyer" => $arrayPaisesAyer,
            "countChartPais" => $countChartPais,
            "arrayChartPais" => $arrayChartPais,
            "arrayChartPaisKeys" => $arrayChartPaisKeys
          ]
        ];
        file_put_contents(public_path('json/0_indexAjax.json'), json_encode($file));


      }

      return response()->json([
        'status' => true,
        'message' => [          
          "major" => $principal,
          "customers" => $arrayCliente,
          "yesterdayCustomers" => $arrayClienteAyer,
          "suppliers"   => $arrayProveedor,
          "yesterdaySuppliers"  => $arrayProveedorAyer,
          "countries" => $arrayPaises,
          "yesterdayCountries" => $arrayPaisesAyer,          
          "countChartCountry" => $countChartPais,
          "chartCountries" => $arrayChartPais,
          "chartCountriesKeys" => $arrayChartPaisKeys,
        ],
      ]); 

    }catch(Exception $e){

      return response()->json([
        'status' => false,
        'message' => $e->getMessage(),
      ]);
    }

  }


  /**
   * reportCountries API
   */
  public function reportCountries(Request $request)
  {
    try{

      $validateUser = Validator::make($request->all(), 
      [
          'username' => 'required',
          'password' => 'required'
      ]);

      if($validateUser->fails()){
          return response()->json([
              'status' => false,
              'message' => 'validation error',
              'errors' => $validateUser->errors()
          ], 401);
      }

      //get SOAP
      $soap = $this->getSoap();

      $encPass    = md5( $request->password."R2Tr0||D2" );

      $startDate  = $request->cFechaInicio  ? date('d-m-Y', strtotime($request->cFechaInicio)) : date('d-m-Y',time()); 
      $endDate    = $request->cFechaFin     ? date('d-m-Y', strtotime($request->cFechaFin)) : date('d-m-Y', time()-86400);

      $result     = [];
      
      $data = $soap->MostrarDashboardBulkGeneral_Paises([
        "cLogin"        => $request->username, 
        "cPassword"     => $encPass, 
        "cFechaInicio"  => $startDate, 
        "cFechaFin"     => $endDate,
        "cPaises"       => $request->cPaises ?? "0",
        "iIDCliente"    => $request->iIDCliente ?? "",
        "iIDProveedor"  => $request->iIDProveedor ?? "",
        "iIDTipoRuta"   => $request->iIDTipoRuta ?? "",
        "cMccMnc"       => $request->cMccMnc ?? "",
      ]);

      $xml = simplexml_load_file(public_path("langs/en.xml"));

      $dd = $data?->tResumenPais?->tResumenPaisRow ?? "";

      if(is_array($dd)){
        $result['data'] = array_map(function($item) use ($xml){
  
          $key = 'p'.$item->Pais_Destino;
          $item->state = (string)$xml->listapaises->$key;
  
          return $item;
        }, $data?->tResumenPais?->tResumenPaisRow);
      }else{
        $result['data'] = $dd;
      }


      //countries #########################
      $countryLists = [];
      foreach ((array)$xml->listapaises as $key => $value) {        
        $countryLists[] = [
          "code"  => str_replace('p','',$key), 
          "state" => $value
        ];
      }

      $result['countryList'] = $countryLists;

      //providers #########################
      $result['providerList'] = $this->getProviderList($request->username, $encPass);

      //customers ########################
      $result['customerList'] = $this->getCustomerList($request->username, $encPass);      

      //typeRoute ########################
      $result['typeRoute']    = (array)$xml->id_tipo;

      return response()->json([
        'status' => true,
        'message' => $result,
      ]);                

    }catch(Exception $e){

      return response()->json([
        'status' => false,
        'message' => $e->getMessage(),
      ]);
    }
  }

  /**
   * reportProviders API
   */
  public function reportProviders(Request $request)
  {
    try{

      $validateUser = Validator::make($request->all(), 
      [
          'username' => 'required',
          'password' => 'required'
      ]);

      if($validateUser->fails()){
          return response()->json([
              'status' => false,
              'message' => 'validation error',
              'errors' => $validateUser->errors()
          ], 401);
      }

      //get SOAP
      $soap = $this->getSoap();

      $encPass    = md5( $request->password."R2Tr0||D2" );

      $startDate  = $request->cFechaInicio  ? date('d-m-Y', strtotime($request->cFechaInicio)) : date('d-m-Y',time()); 
      $endDate    = $request->cFechaFin     ? date('d-m-Y', strtotime($request->cFechaFin)) : date('d-m-Y', time()-86400);

      $result     = [];
      
      $data = $soap->MostrarDashboardBulkGeneral_Proveedor([
        "cLogin"        => $request->username, 
        "cPassword"     => $encPass, 
        "cFechaInicio"  => $startDate, 
        "cFechaFin"     => $endDate,
        "cPaises"       => $request->cPaises ?? "0",
        "iIDCliente"    => $request->iIDCliente ?? "",
        "iIDProveedor"  => $request->iIDProveedor ?? "",
        "iIDTipoRuta"   => $request->iIDTipoRuta ?? "",
        "cMccMnc"       => $request->cMccMnc ?? "",
      ]);

      $xml = simplexml_load_file(public_path("langs/en.xml"));

      $dd = $data?->tResumenProveedor?->tResumenProveedorRow ?? null;

      if(is_array($dd)){
        $result['data'] = array_map(function($item) use ($xml){
  
          $key = 'p'.$item->Pais_Destino;
          $item->state = (string)$xml->listapaises->$key;
  
          return $item;
        }, $dd);
      }else{
        $result['data'] = $dd ? [$dd] : [];
      }


      //countries #########################
      $countryLists = [];
      foreach ((array)$xml->listapaises as $key => $value) {        
        $countryLists[] = [
          "code"  => str_replace('p','',$key), 
          "state" => $value
        ];
      }

      $result['countryList'] = $countryLists;

      //providers #########################
      $result['providerList'] = $this->getProviderList($request->username, $encPass);

      //customers ########################
      $result['customerList'] = $this->getCustomerList($request->username, $encPass);      

      //typeRoute ########################
      $result['typeRoute']    = (array)$xml->id_tipo;

      return response()->json([
        'status' => true,
        'message' => $result,
      ]);                

    }catch(Exception $e){

      return response()->json([
        'status' => false,
        'message' => $e->getMessage(),
      ]);
    }
  }

  /**
   * reportCustomers API
   */
  public function reportCustomers(Request $request)
  {
    try{

      $validateUser = Validator::make($request->all(), 
      [
          'username' => 'required',
          'password' => 'required'
      ]);

      if($validateUser->fails()){
          return response()->json([
              'status' => false,
              'message' => 'validation error',
              'errors' => $validateUser->errors()
          ], 401);
      }

      //get SOAP
      $soap = $this->getSoap();

      $encPass    = md5( $request->password."R2Tr0||D2" );

      $startDate  = $request->cFechaInicio  ? date('d-m-Y', strtotime($request->cFechaInicio)) : date('d-m-Y',time()); 
      $endDate    = $request->cFechaFin     ? date('d-m-Y', strtotime($request->cFechaFin)) : date('d-m-Y', time()-86400);

      $result     = [];
      
      $data = $soap->MostrarDashboardBulkGeneral_Cliente([
        "cLogin"        => $request->username, 
        "cPassword"     => $encPass, 
        "cFechaInicio"  => $startDate, 
        "cFechaFin"     => $endDate,
        "cPaises"       => $request->cPaises ?? "0",
        "iIDCliente"    => $request->iIDCliente ?? "",
        "iIDProveedor"  => $request->iIDProveedor ?? "",
        "iIDTipoRuta"   => $request->iIDTipoRuta ?? "",
        "cMccMnc"       => $request->cMccMnc ?? "",
      ]);

      $xml = simplexml_load_file(public_path("langs/en.xml"));

      $masterList = $this->getMasterProviderLists($request->username, $encPass);

      $dd = $data?->tResumenCliente?->tResumenClienteRow ?? null;

      if(is_array($dd)){
        $result['data'] = array_map(function($item) use ($xml, $masterList){
  
          $key = 'p'.$item->Pais_Destino;
          $item->state = (string)$xml->listapaises->$key;

          $tmp = array_filter($masterList, function($row) use($item){
            return $row['ID_MASTER'] == $item->ID_MasterCliente;
          });
          
          $item->masterName = array_values($tmp)[0]['NOMBRE'] ?? "";

          return $item;
        }, $dd);
      }else{
        $result['data'] = $dd ? [$dd] : [];
      }


      //countries #########################
      $countryLists = [];
      foreach ((array)$xml->listapaises as $key => $value) {        
        $countryLists[] = [
          "code"  => str_replace('p','',$key), 
          "state" => $value
        ];
      }

      $result['countryList'] = $countryLists;

      //providers #########################
      $result['providerList'] = $this->getProviderList($request->username, $encPass);

      //customers ########################
      $result['customerList'] = $this->getCustomerList($request->username, $encPass);      

      //typeRoute ########################
      $result['typeRoute']    = (array)$xml->id_tipo;

      return response()->json([
        'status' => true,
        'message' => $result,
      ]);                

    }catch(Exception $e){

      return response()->json([
        'status' => false,
        'message' => $e->getMessage(),
      ]);
    }
  }

  /**
   * reportMccMnc API
   */
  public function reportMccMnc(Request $request)
  {
    try{

      $validateUser = Validator::make($request->all(), 
      [
          'username' => 'required',
          'password' => 'required'
      ]);

      if($validateUser->fails()){
          return response()->json([
              'status' => false,
              'message' => 'validation error',
              'errors' => $validateUser->errors()
          ], 401);
      }

      //get SOAP
      $soap = $this->getSoap();

      $encPass    = md5( $request->password."R2Tr0||D2" );

      $startDate  = $request->cFechaInicio  ? date('d-m-Y', strtotime($request->cFechaInicio)) : date('d-m-Y',time()); 
      $endDate    = $request->cFechaFin     ? date('d-m-Y', strtotime($request->cFechaFin)) : date('d-m-Y', time()-86400);

      $result     = [];
      
      $data = $soap->MostrarDashboardBulkGeneral_MccMnc([
        "cLogin"        => $request->username, 
        "cPassword"     => $encPass, 
        "cFechaInicio"  => $startDate, 
        "cFechaFin"     => $endDate,
        "cPaises"       => $request->cPaises ?? "0",
        "iIDCliente"    => $request->iIDCliente ?? "",
        "iIDProveedor"  => $request->iIDProveedor ?? "",
        "iIDTipoRuta"   => $request->iIDTipoRuta ?? "",
        "cMccMnc"       => $request->cMccMnc ?? "",
      ]);

      $xml = simplexml_load_file(public_path("langs/en.xml"));

      $dd = $data?->tResumenMccMnc?->tResumenMccMncRow ?? null;

      if(is_array($dd)){
        $result['data'] = array_map(function($item) use ($xml){
  
          $key = 'p'.$item->Pais_Destino;
          $item->state = (string)$xml->listapaises->$key;
  
          return $item;
        }, $dd);
      }else{
        $result['data'] = $dd ? [$dd] : [];
      }


      //countries #########################
      $countryLists = [];
      foreach ((array)$xml->listapaises as $key => $value) {        
        $countryLists[] = [
          "code"  => str_replace('p','',$key), 
          "state" => $value
        ];
      }

      $result['countryList'] = $countryLists;

      //providers #########################
      $result['providerList'] = $this->getProviderList($request->username, $encPass);

      //customers ########################
      $result['customerList'] = $this->getCustomerList($request->username, $encPass);      

      //typeRoute ########################
      $result['typeRoute']    = (array)$xml->id_tipo;

      return response()->json([
        'status' => true,
        'message' => $result,
      ]);                

    }catch(Exception $e){

      return response()->json([
        'status' => false,
        'message' => $e->getMessage(),
      ]);
    }
  }

  /**
   * reportSenders API
   */
  public function reportSenders(Request $request)
  {
    try{

      $validateUser = Validator::make($request->all(), 
      [
          'username' => 'required',
          'password' => 'required'
      ]);

      if($validateUser->fails()){
          return response()->json([
              'status' => false,
              'message' => 'validation error',
              'errors' => $validateUser->errors()
          ], 401);
      }

      //get SOAP
      $soap = $this->getSoap();

      $encPass    = md5( $request->password."R2Tr0||D2" );

      $startDate  = $request->cFechaInicio  ? date('d-m-Y', strtotime($request->cFechaInicio)) : date('d-m-Y',time()); 
      $endDate    = $request->cFechaFin     ? date('d-m-Y', strtotime($request->cFechaFin)) : date('d-m-Y', time()-86400);

      $result     = [];
      
      $data = $soap->mostrarDashboardBulkGeneral_Remitente([
        "cLogin"        => $request->username, 
        "cPassword"     => $encPass, 
        "cFechaInicio"  => $startDate, 
        "cFechaFin"     => $endDate,
        "cPaises"       => $request->cPaises ?? "0",
        "iIDCliente"    => $request->iIDCliente ?? "",
        "iIDProveedor"  => $request->iIDProveedor ?? "",
        "iIDTipoRuta"   => $request->iIDTipoRuta ?? "",
        "cMccMnc"       => $request->cMccMnc ?? "",
      ]);

      $xml = simplexml_load_file(public_path("langs/en.xml"));

      $dd = $data?->tResumenRemitente?->tResumenRemitenteRow ?? null;

      if(is_array($dd)){
        $result['data'] = array_map(function($item) use ($xml){
          $key = 'p'.$item->Pais_Destino;
          $item->state = (string)$xml->listapaises->$key;
  
          return $item;          
        }, $dd);
      }else{
        $result['data'] = $dd ? [$dd] : [];
      }


      //countries #########################
      $countryLists = [];
      foreach ((array)$xml->listapaises as $key => $value) {        
        $countryLists[] = [
          "code"  => str_replace('p','',$key), 
          "state" => $value
        ];
      }

      $result['countryList'] = $countryLists;

      //providers #########################
      $result['providerList'] = $this->getProviderList($request->username, $encPass);

      //customers ########################
      $result['customerList'] = $this->getCustomerList($request->username, $encPass);      

      //typeRoute ########################
      $result['typeRoute']    = (array)$xml->id_tipo;

      return response()->json([
        'status' => true,
        'message' => $result,
      ]);                

    }catch(Exception $e){

      return response()->json([
        'status' => false,
        'message' => $e->getMessage(),
      ]);
    }
  }

  /**
   * Get the MccMnc code 
   */
  public function getMccMncWithCode(Request $request){
    try{

      $validateUser = Validator::make($request->all(), 
      [
          'code' => 'required',
      ]);

      if($validateUser->fails()){
          return response()->json([
              'status' => false,
              'message' => 'validation error',
              'errors' => $validateUser->errors()
          ], 400);
      }

      //MccMnc ##########################
      $result = $this->getMccMnc($request->code);

      return response()->json([
        'status' => true,
        'message' => $result,
      ]);  

    }catch(Exception $e){

      return response()->json([
        'status' => false,
        'message' => $e->getMessage(),
      ]);
    }

  }

  /**
   * getLastMessages API
   */
  public function getLastMessages(Request $request)
  {
    try{

      $validateUser = Validator::make($request->all(), 
      [
          'username' => 'required',
          'password' => 'required'
      ]);

      if($validateUser->fails()){
          return response()->json([
              'status' => false,
              'message' => 'validation error',
              'errors' => $validateUser->errors()
          ], 401);
      }

      //get SOAP
      $soap = $this->getSoap();

      $encPass    = md5( $request->password."R2Tr0||D2" );

      $result     = [];
      
      $data = $soap->VerMensajesUltimos_Filtro([
        "cLogin"        => $request->username, 
        "cPassword"     => $encPass,         
        "cPaises"       => $request->cPaises ?? "",
        "iIDCliente"    => $request->iIDCliente ?? "",
        "iIDProveedor"  => $request->iIDProveedor ?? "",
        "iIDTipoRuta"   => $request->iIDTipoRuta ?? "",
        "cMccMnc"       => $request->cMccMnc ?? "",
      ]);

      $xml = simplexml_load_file(public_path("langs/en.xml"));

      $dd = $data?->tMensaje?->tMensajeRow ?? null;

      if(is_array($dd)){
        $result['data'] = array_map(function($item) use ($xml){
          $key = 'p'.$item->Pais_Destino;
          $item->state = (string)$xml->listapaises->$key;

          $tmp = str_replace("+", " ", urldecode($item->Texto));
          $item->Texto = $tmp;
  
          return $item;          
        }, $dd);
      }else{
        $result['data'] = $dd ? [$dd] : [];
      }


      //countries #########################
      $countryLists = [];
      foreach ((array)$xml->listapaises as $key => $value) {        
        $countryLists[] = [
          "code"  => str_replace('p','',$key), 
          "state" => $value
        ];
      }

      $result['countryList'] = $countryLists;

      //providers #########################
      $result['providerList'] = $this->getProviderList($request->username, $encPass);

      //customers ########################
      $result['customerList'] = $this->getCustomerList($request->username, $encPass);      

      //typeRoute ########################
      $result['typeRoute']    = (array)$xml->id_tipo;

      return response()->json([
        'status' => true,
        'message' => $result,
      ]);                

    }catch(Exception $e){

      return response()->json([
        'status' => false,
        'message' => $e->getMessage(),
      ]);
    }
  }

  /**
   * mobileIncident API
   */
  public function mobileIncident(Request $request)
  {
    try{

      $validateUser = Validator::make($request->all(), 
      [
          'username' => 'required',
          'password' => 'required'
      ]);

      if($validateUser->fails()){
          return response()->json([
              'status' => false,
              'message' => 'validation error',
              'errors' => $validateUser->errors()
          ], 401);
      }

      //get SOAP
      $soap = $this->getSoap();

      $encPass    = md5( $request->password."R2Tr0||D2" );

      $startDate  = $request->cFechaInicio  ? date('d-m-Y', strtotime($request->cFechaInicio)) : date('d-m-Y',time()); 
      $endDate    = $request->cFechaFin     ? date('d-m-Y', strtotime($request->cFechaFin)) : date('d-m-Y', time()-86400);

      $result     = [];
      
      $data = $soap->VerMensajesIncidenciaBulk([
        "cLogin"        => $request->username, 
        "cPassword"     => $encPass, 
        "cMovil"        => $request->cMovil ?? "", /* 34660948970 */
        "cRutaMsgID"    => $request->cRutaMsgID ?? "",
        "cMsgID"        => $request->cMsgID ?? "",
        "cFechaInicio"  => $startDate, 
        "cFechaFin"     => $endDate,
        "iPagina"       => $request->iPagina ?? "0",
      ]);

      $xml = simplexml_load_file(public_path("langs/en.xml"));

      $dd = $data?->TMensajeAux?->TMensajeAuxRow ?? null;

      if(is_array($dd)){
        $result['data'] = array_map(function($item) use ($xml){
          $key = 'p'.$item->Pais_Destino;
          $item->state = (string)$xml->listapaises->$key;
          
          $tmp = str_replace("+", " ", urldecode($item->Texto));
          $item->Texto = $tmp;
          return $item;          
        }, $dd);
      }else{
        $result['data'] = $dd ? [$dd] : [];
      }

      //providers #########################
      $result['providerList'] = $this->getProviderList($request->username, $encPass);

      return response()->json([
        'status' => true,
        'message' => $result,
      ]);                

    }catch(Exception $e){

      return response()->json([
        'status' => false,
        'message' => $e->getMessage(),
      ]);
    }
  }

  

}
