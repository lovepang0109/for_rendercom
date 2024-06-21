<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoutingController extends Controller
{
    /**
    * routeGeneralCountries API
    */
    public function routeGeneralCountries(Request $request)
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
            
            $data = $soap->ListarRoutingDefault([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "iIDMasterBulk"     => $request->iIDMasterBulk ?? -1,
                "cPaisDestino"      => $request->cPaisDestino ?? "",
                "iIDTipoBulk"       => $request->iIDTipoBulk ?? "",            
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));

            $dd = $data?->TRutaPais?->TRutaPaisRow ?? null;

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($xml){
                    //state
                    if(!(int)$item->PAIS_DESTINO){
                        $tmp = $item->PAIS_DESTINO;
                        $item->PAIS_DESTINO = $tmp == '*' ? '-1' : '0';
                        $item->state        = $tmp == '*' ? "Default" : "International";
                    }else{
                        $key = 'p'.$item->PAIS_DESTINO;
                        $item->state = (string)$xml->listapaises->$key;
                    }

                    //'+' mark remove
                    $tmp = str_replace("+", " ", urldecode($item->NOMBRE_PROVEEDOR));
                    $item->NOMBRE_PROVEEDOR = $tmp;

                    //type
                    $key = 't'.$item->ID_TIPO;
                    $item->tyepRoute = (string)$xml->id_tipo->$key;

                    //
                    $tmp = explode(';', $item->RUTA);
                    $item->RUTA = count($tmp) > 1 ? "[$tmp[0]] $tmp[1]%" : $item->RUTA;
            
                    return $item;          
                }, $dd);
            }else{
                $result['data'] = $dd ? [$dd] : [];
            }

            //countries #########################
            $countryList = []; 
            foreach ((array)$xml->listapaises as $key => $value) {        
                $countryList[] = [
                    "code"  => str_replace('p','',$key), 
                    "state" => $value,
                ];                
            }
            $result['countryList'] = $countryList;

            //routes #########################
            $routeList = []; 
            foreach ((array)$xml->id_tipo as $key => $value) {        
                $routeList[] = [
                    "id"    => substr($key, 1) * 1,
                    "route" => $value
                ];                
            }
            $result['routeList'] = $routeList;
            
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
    * routeGeneralCustomers API
    */
    public function routeGeneralCustomers(Request $request)
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
            
            $data = $soap->ListarRoutingCliente([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "cPaisDestino"      => $request->cPaisDestino   ?? "",
                "iIDCliente"        => $request->iIDCliente     ?? -1,            
                "iIDTipoBulk"       => $request->iIDTipoBulk    ?? "",            
                "iIDMasterBulk"     => $request->iIDMasterBulk  ?? -1,
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));

            $dd = $data?->TRutaClientePais?->TRutaClientePaisRow ?? null;

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($xml){
                    //state
                    if(!(int)$item->PAIS_DESTINO){
                        $tmp = $item->PAIS_DESTINO;
                        $item->PAIS_DESTINO = $tmp == '*' ? '-1' : '0';
                        $item->state        = $tmp == '*' ? "Default" : "International";
                    }else{
                        $key = 'p'.$item->PAIS_DESTINO;
                        $item->state = (string)$xml->listapaises->$key;
                    }

                    //'+' mark remove
                    $tmp = str_replace("+", " ", urldecode($item->NOMBRE_PROVEEDOR));
                    $item->NOMBRE_PROVEEDOR = $tmp;

                    //type
                    $key = 't'.$item->ID_TIPO;
                    $item->tyepRoute = (string)$xml->id_tipo->$key;

                    //RUTA
                    $tmp = explode(';', $item->RUTA);
                    $item->RUTA = count($tmp) > 1 ? "[$tmp[0]] $tmp[1]%" : $item->RUTA;

                    //
                    $tmp = str_replace("+", " ", urldecode($item->NOMBRE_CLIENTE));
                    $item->NOMBRE_CLIENTE = $tmp;
            
                    return $item;          
                }, $dd);
            }else{
                $result['data'] = $dd ? [$dd] : [];
            }

            //countries #########################
            $countryList = []; 
            foreach ((array)$xml->listapaises as $key => $value) {        
                $countryList[] = [
                    "code"  => str_replace('p','',$key), 
                    "state" => $value,
                ];                
            }
            $result['countryList'] = $countryList;

            //customrs ########################
            $result['customerList'] = $this->getDashCustomerList($request->username, $encPass);

            //routes #########################
            $routeList = []; 
            foreach ((array)$xml->id_tipo as $key => $value) {        
                $routeList[] = [
                    "id"    => substr($key, 1) * 1,
                    "route" => $value
                ];                
            }
            $result['routeList'] = $routeList;
            
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
    * routeGeneralMccMnc API
    */
    public function routeGeneralMccMnc(Request $request)
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
            
            $data = $soap->ListarRoutingDefault([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "iIDMasterBulk"     => $request->iIDMasterBulk ?? -1,
                "cPaisDestino"      => $request->cPaisDestino ?? "",
                "iIDTipoBulk"       => $request->iIDTipoBulk ?? "",            
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));

            $dd = $data?->TRutaMccMnc?->TRutaMccMncRow ?? null;

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($xml){
                    //state
                    if(!(int)$item->PAIS_DESTINO){
                        $tmp = $item->PAIS_DESTINO;
                        $item->PAIS_DESTINO = $tmp == '*' ? '-1' : '0';
                        $item->state        = $tmp == '*' ? "Default" : "International";
                    }else{
                        $key = 'p'.$item->PAIS_DESTINO;
                        $item->state = (string)$xml->listapaises->$key;
                    }

                    //'+' mark remove
                    $tmp = str_replace("+", " ", urldecode($item->NOMBRE_PROVEEDOR));
                    $item->NOMBRE_PROVEEDOR = $tmp;

                    //type
                    $key = 't'.$item->ID_TIPO;
                    $item->tyepRoute = (string)$xml->id_tipo->$key;

                    //
                    $tmp = explode(';', $item->RUTA);
                    $item->RUTA = count($tmp) > 1 ? "[$tmp[0]] $tmp[1]%" : $item->RUTA;

                    //Operator
                    $tmp = str_replace("+", " ", urldecode($item->OPERATOR));
                    $item->OPERATOR = $tmp;
            
                    return $item;          
                }, $dd);
            }else{
                $result['data'] = $dd ? [$dd] : [];
            }

            //countries #########################
            $countryList = []; 
            foreach ((array)$xml->listapaises as $key => $value) {        
                $countryList[] = [
                    "code"  => str_replace('p','',$key), 
                    "state" => $value,
                ];                
            }
            $result['countryList'] = $countryList;

            //routes #########################
            $routeList = []; 
            foreach ((array)$xml->id_tipo as $key => $value) {        
                $routeList[] = [
                    "id"    => substr($key, 1) * 1,
                    "route" => $value
                ];                
            }
            $result['routeList'] = $routeList;
            
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
    * routeGeneralMccMncCustomers API
    */
    public function routeGeneralMccMncCustomers(Request $request)
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
            
            $data = $soap->ListarRoutingCliente([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "cPaisDestino"      => $request->cPaisDestino   ?? "",
                "iIDCliente"        => $request->iIDCliente     ?? -1,            
                "iIDTipoBulk"       => $request->iIDTipoBulk    ?? "",            
                "iIDMasterBulk"     => $request->iIDMasterBulk  ?? -1,
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));

            $dd = $data?->TRutaClienteMccMnc?->TRutaClienteMccMncRow ?? null;

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($xml){
                    //state
                    if(!(int)$item->PAIS_DESTINO){
                        $tmp = $item->PAIS_DESTINO;
                        $item->PAIS_DESTINO = $tmp == '*' ? '-1' : '0';
                        $item->state        = $tmp == '*' ? "Default" : "International";
                    }else{
                        $key = 'p'.$item->PAIS_DESTINO;
                        $item->state = (string)$xml->listapaises->$key;
                    }

                    //'+' mark remove
                    $tmp = str_replace("+", " ", urldecode($item->NOMBRE_PROVEEDOR));
                    $item->NOMBRE_PROVEEDOR = $tmp;

                    //type
                    $key = 't'.$item->ID_TIPO;
                    $item->tyepRoute = (string)$xml->id_tipo->$key;

                    //RUTA
                    $tmp = explode(';', $item->RUTA);
                    $item->RUTA = count($tmp) > 1 ? "[$tmp[0]] $tmp[1]%" : $item->RUTA;

                    //
                    $tmp = str_replace("+", " ", urldecode($item->NOMBRE_CLIENTE));
                    $item->NOMBRE_CLIENTE = $tmp;
            
                    return $item;          
                }, $dd);
            }else{
                $result['data'] = $dd ? [$dd] : [];
            }

            //countries #########################
            $countryList = []; 
            foreach ((array)$xml->listapaises as $key => $value) {        
                $countryList[] = [
                    "code"  => str_replace('p','',$key), 
                    "state" => $value,
                ];                
            }
            $result['countryList'] = $countryList;

            //customrs ########################
            $result['customerList'] = $this->getDashCustomerList($request->username, $encPass);

            //routes #########################
            $routeList = []; 
            foreach ((array)$xml->id_tipo as $key => $value) {        
                $routeList[] = [
                    "id"    => substr($key, 1) * 1,
                    "route" => $value
                ];                
            }
            $result['routeList'] = $routeList;
            
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
    * routeSenderCountries API
    */
    public function routeSenderCountries(Request $request)
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
            
            $data = $soap->ListarRutaRemitentePais([
                "cLogin"      => $request->username, 
                "cPassword"   => $encPass, 
                "cPais"       => $request->cPais ?? "",            
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));

            $dd = $data?->tRemitePais?->tRemitePaisRow ?? null;

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($xml){
                    //state
                    if(!(int)$item->PAIS_DESTINO){
                        $tmp = $item->PAIS_DESTINO;
                        $item->PAIS_DESTINO = $tmp == '*' ? '-1' : '0';
                        $item->state        = $tmp == '*' ? "Default" : "International";
                    }else{
                        $key = 'p'.$item->PAIS_DESTINO;
                        $item->state = (string)$xml->listapaises->$key;
                    }

                    //'+' mark remove
                    $tmp = str_replace("+", " ", urldecode($item->REMITENTE));
                    $item->REMITENTE = $tmp;

                    //type
                    $key = 't'.$item->ID_TIPO;
                    $item->tyepRoute = (string)$xml->id_tipo->$key;

                    //
                    $tmp = explode(';', $item->RUTA);
                    $item->RUTA = count($tmp) > 1 ? "[$tmp[0]] $tmp[1]%" : $item->RUTA;
            
                    return $item;          
                }, $dd);
            }else{
                $result['data'] = $dd ? [$dd] : [];
            }

            //countries #########################
            $countryList = []; 
            foreach ((array)$xml->listapaises as $key => $value) {        
                $countryList[] = [
                    "code"  => str_replace('p','',$key), 
                    "state" => $value,
                ];                
            }
            $result['countryList'] = $countryList;

            //routes #########################
            $routeList = []; 
            foreach ((array)$xml->id_tipo as $key => $value) {        
                $routeList[] = [
                    "id"    => substr($key, 1) * 1,
                    "route" => $value
                ];                
            }
            $result['routeList'] = $routeList;
            
            
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
    * routeSenderCustomers API
    */
    public function routeSenderCustomers(Request $request)
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
            
            $data = $soap->ListarRutaRemitenteClientePais([
                "cLogin"      => $request->username, 
                "cPassword"   => $encPass, 
                "cPais"       => $request->cPais ?? "",            
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));

            $dd = $data?->tRemiteClientePais?->tRemiteClientePaisRow ?? null;

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($xml){
                    //state
                    if(!(int)$item->PAIS_DESTINO){
                        $tmp = $item->PAIS_DESTINO;
                        $item->PAIS_DESTINO = $tmp == '*' ? '-1' : '0';
                        $item->state        = $tmp == '*' ? "Default" : "International";
                    }else{
                        $key = 'p'.$item->PAIS_DESTINO;
                        $item->state = (string)$xml->listapaises->$key;
                    }

                    //'+' mark remove
                    $tmp = str_replace("+", " ", urldecode($item->REMITENTE));
                    $item->REMITENTE = $tmp;

                    //
                    $tmp = str_replace("+", " ", urldecode($item->NOMBRE_CLIENTE));
                    $item->NOMBRE_CLIENTE = $tmp;

                    //
                    $tmp = explode(';', $item->RUTA);
                    $item->RUTA = count($tmp) > 1 ? "[$tmp[0]] $tmp[1]%" : $item->RUTA;
            
                    return $item;          
                }, $dd);
            }else{
                $result['data'] = $dd ? [$dd] : [];
            }

            //countries #########################
            $countryList = []; 
            foreach ((array)$xml->listapaises as $key => $value) {        
                $countryList[] = [
                    "code"  => str_replace('p','',$key), 
                    "state" => $value,
                ];                
            }
            $result['countryList'] = $countryList;

            //routes #########################
            $routeList = []; 
            foreach ((array)$xml->id_tipo as $key => $value) {        
                $routeList[] = [
                    "id"    => substr($key, 1) * 1,
                    "route" => $value
                ];                
            }
            $result['routeList'] = $routeList;
            
            
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
    * routeSenderRestrictedOperators API
    */
    public function routeSenderRestrictedOperators(Request $request)
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
            
            $data = $soap->ListarRestriccionRutaPais([
                "cLogin"      => $request->username, 
                "cPassword"   => $encPass, 
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));

            $dd = $data?->tRestriccionRemiteRutaPais?->tRestriccionRemiteRutaPaisRow ?? null;

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($xml){
                    //state
                    if(!(int)$item->PAIS_DESTINO){
                        $tmp = $item->PAIS_DESTINO;
                        $item->PAIS_DESTINO = $tmp == '*' ? '-1' : '0';
                        $item->state        = $tmp == '*' ? "Default" : "International";
                    }else{
                        $key = 'p'.$item->PAIS_DESTINO;
                        $item->state = (string)$xml->listapaises->$key;
                    }                    

                    //
                    $tmp = explode(';', $item->RUTA_ALTERNATIVA);
                    $item->RUTA_ALTERNATIVA = count($tmp) > 1 ? "[$tmp[0]] $tmp[1]%" : $item->RUTA_ALTERNATIVA;
            
                    return $item;          
                }, $dd);
            }else{
                $result['data'] = $dd ? [$dd] : [];
            }

            //countries #########################
            $countryList = []; 
            foreach ((array)$xml->listapaises as $key => $value) {        
                $countryList[] = [
                    "code"  => str_replace('p','',$key), 
                    "state" => $value,
                ];                
            }
            $result['countryList'] = $countryList;
            
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
    * routeBlacklistCountries API
    */
    public function routeBlacklistCountries(Request $request)
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
            
            $data = $soap->ListarBlackListRemitePais([
                "cLogin"      => $request->username, 
                "cPassword"   => $encPass, 
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));

            $dd = $data?->tRemitePais?->tRemitePaisRow ?? null;

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($xml){
                    //state
                    if(!(int)$item->PAIS_DESTINO){
                        $tmp = $item->PAIS_DESTINO;
                        $item->PAIS_DESTINO = $tmp == '*' ? '-1' : '0';
                        $item->state        = $tmp == '*' ? "Default" : "International";
                    }else{
                        $key = 'p'.$item->PAIS_DESTINO;
                        $item->state = (string)$xml->listapaises->$key;
                    }

                    //type
                    $key = 't'.$item->ID_TIPO;
                    $item->tyepRoute = (string)$xml->id_tipo->$key;

                    //Date
                    $item->FECHA_HORA = date("Y-m-d H:i:s", strtotime($item->FECHA_HORA));

                    return $item;          
                }, $dd);
            }else{
                $result['data'] = $dd ? [$dd] : [];
            }

            //countries #########################
            $countryList = []; 
            foreach ((array)$xml->listapaises as $key => $value) {        
                $countryList[] = [
                    "code"  => str_replace('p','',$key), 
                    "state" => $value,
                ];                
            }
            $result['countryList'] = $countryList;

            //routes #########################
            $routeList = []; 
            foreach ((array)$xml->id_tipo as $key => $value) {        
                $routeList[] = [
                    "id"    => substr($key, 1) * 1,
                    "route" => $value
                ];                
            }
            $result['routeList'] = $routeList;
            
            
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
    * routeBlacklistCustomers API
    */
    public function routeBlacklistCustomers(Request $request)
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
            
            $data = $soap->ListarBlackListRemiteClientePais([
                "cLogin"      => $request->username, 
                "cPassword"   => $encPass, 
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));

            $dd = $data?->tRemiteCliente?->tRemiteClienteRow ?? null;

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($xml){
                    //state
                    if(!(int)$item->PAIS_DESTINO){
                        $tmp = $item->PAIS_DESTINO;
                        $item->PAIS_DESTINO = $tmp == '*' ? '-1' : '0';
                        $item->state        = $tmp == '*' ? "Default" : "International";
                    }else{
                        $key = 'p'.$item->PAIS_DESTINO;
                        $item->state = (string)$xml->listapaises->$key;
                    }

                    //'+' mark remove
                    $tmp = str_replace("+", " ", urldecode($item->NOMBRE_CLIENTE));
                    $item->NOMBRE_CLIENTE = $tmp;

                    //'+' mark remove
                    $tmp = str_replace("+", " ", urldecode($item->REMITENTE));
                    $item->REMITENTE = $tmp;

                    //Date
                    $item->FECHA_HORA = date("Y-m-d H:i:s", strtotime($item->FECHA_HORA));

                    return $item;          
                }, $dd);
            }else{
                $result['data'] = $dd ? [$dd] : [];
            }

            //countries #########################
            $countryList = []; 
            foreach ((array)$xml->listapaises as $key => $value) {        
                $countryList[] = [
                    "code"  => str_replace('p','',$key), 
                    "state" => $value,
                ];                
            }
            $result['countryList'] = $countryList;

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
    * routeBackupCountries API
    */
    public function routeBackupCountries(Request $request)
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
            
            $data = $soap->ListarBkRutaPais([
                "cLogin"      => $request->username, 
                "cPassword"   => $encPass, 
                "iIDTipoBulk" => $request->iIDTipoBulk ?? -1, 
                "cPais"       => $request->cPais ?? "",
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));

            $dd = $data?->tBKRuta?->tBKRutaRow ?? null;

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($xml){
                    //state
                    if(!(int)$item->PAIS_DESTINO){
                        $tmp = $item->PAIS_DESTINO;
                        $item->PAIS_DESTINO = $tmp == '*' ? '-1' : '0';
                        $item->state        = $tmp == '*' ? "Default" : "International";
                    }else{
                        $key = 'p'.$item->PAIS_DESTINO;
                        $item->state = (string)$xml->listapaises->$key;
                    }

                    //type
                    $key = 't'.$item->ID_TIPO;
                    $item->tyepRoute = (string)$xml->id_tipo->$key;

                    //
                    $item->RUTA_BK = urldecode($item->RUTA_BK);

                    return $item;          
                }, $dd);

            }elseif( is_object($dd) ){

                if(!(int)$dd->PAIS_DESTINO){
                    $tmp = $dd->PAIS_DESTINO;
                    $dd->PAIS_DESTINO = $tmp == '*' ? '-1' : '0';
                    $dd->state        = $tmp == '*' ? "Default" : "International";
                }else{
                    $key = 'p'.$dd->PAIS_DESTINO;
                    $dd->state = (string)$xml->listapaises->$key;
                }

                //type
                $key = 't'.$dd->ID_TIPO;
                $dd->tyepRoute = (string)$xml->id_tipo->$key;

                //
                $dd->RUTA_BK = urldecode($dd->RUTA_BK);

                //
                $backList = [];
                array_map(function($row) use (&$backList){

                    $tmp = explode(';', $row);
                    $aa = count($tmp) > 1 ? "[$tmp[0]] $tmp[1]%" : $row;

                    array_push($backList, $aa);

                }, explode(',', $dd->RUTA_BK));

                $dd->backupList = $backList;


                $result['data'] = $dd ? [$dd] : [];

            }else{
                $result['data'] = $dd ? [$dd] : [];
            }

            //countries #########################
            $countryList = []; 
            foreach ((array)$xml->listapaises as $key => $value) {        
                $countryList[] = [
                    "code"  => str_replace('p','',$key), 
                    "state" => $value,
                ];                
            }
            $result['countryList'] = $countryList;

            //routes #########################
            $routeList = []; 
            foreach ((array)$xml->id_tipo as $key => $value) {        
                $routeList[] = [
                    "id"    => substr($key, 1) * 1,
                    "route" => $value
                ];                
            }
            $result['routeList'] = $routeList;

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
    * routeBackupCustomers API
    */
    public function routeBackupCustomers(Request $request)
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
            
            $data = $soap->ListarBkRutaCliente([
                "cLogin"      => $request->username, 
                "cPassword"   => $encPass, 
                "iIDTipoBulk" => $request->iIDTipoBulk ?? -1, 
                "iIDCliente" => $request->iIDCliente ?? -1, 
                "cPais"       => $request->cPais ?? "",
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));

            $dd = $data?->tBKRuta?->tBKRutaRow ?? null;

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($xml){
                    //state
                    if(!(int)$item->PAIS_DESTINO){
                        $tmp = $item->PAIS_DESTINO;
                        $item->PAIS_DESTINO = $tmp == '*' ? '-1' : '0';
                        $item->state        = $tmp == '*' ? "Default" : "International";
                    }else{
                        $key = 'p'.$item->PAIS_DESTINO;
                        $item->state = (string)$xml->listapaises->$key;
                    }

                    //type
                    $key = 't'.$item->ID_TIPO;
                    $item->tyepRoute = (string)$xml->id_tipo->$key;

                    //
                    $item->RUTA_BK = urldecode($item->RUTA_BK);

                    return $item;          
                }, $dd);

            }else{
                $result['data'] = $dd ? [$dd] : [];
            }

            //customrs ########################
            $result['customerList'] = $this->getDashCustomerList($request->username, $encPass);

            //countries #########################
            $countryList = []; 
            foreach ((array)$xml->listapaises as $key => $value) {        
                $countryList[] = [
                    "code"  => str_replace('p','',$key), 
                    "state" => $value,
                ];                
            }
            $result['countryList'] = $countryList;

            //routes #########################
            $routeList = []; 
            foreach ((array)$xml->id_tipo as $key => $value) {        
                $routeList[] = [
                    "id"    => substr($key, 1) * 1,
                    "route" => $value
                ];                
            }
            $result['routeList'] = $routeList;

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
    * routeBackupMccMnc API
    */
    public function routeBackupMccMnc(Request $request)
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
            
            $data = $soap->ListarBkRutaMccMnc([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "iMcc"              => $request->iMcc ?? -1,
                "iMnc"              => $request->iMnc ?? "",
                "iIDTipoBulk"       => $request->iIDTipoBulk ?? "",            
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));

            $dd = $data?->tBKRuta?->tBKRutaRow ?? null;

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($xml){
                    //state
                    if(!(int)$item->PAIS_DESTINO){
                        $tmp = $item->PAIS_DESTINO;
                        $item->PAIS_DESTINO = $tmp == '*' ? '-1' : '0';
                        $item->state        = $tmp == '*' ? "Default" : "International";
                    }else{
                        $key = 'p'.$item->PAIS_DESTINO;
                        $item->state = (string)$xml->listapaises->$key;
                    }

                    //'+' mark remove
                    $tmp = str_replace("+", " ", urldecode($item->NOMBRE_PROVEEDOR));
                    $item->NOMBRE_PROVEEDOR = $tmp;

                    //type
                    $key = 't'.$item->ID_TIPO;
                    $item->tyepRoute = (string)$xml->id_tipo->$key;

                    //
                    $tmp = explode(';', $item->RUTA);
                    $item->RUTA = count($tmp) > 1 ? "[$tmp[0]] $tmp[1]%" : $item->RUTA;

                    //Operator
                    $tmp = str_replace("+", " ", urldecode($item->OPERATOR));
                    $item->OPERATOR = $tmp;
            
                    return $item;          
                }, $dd);
            }else{
                $result['data'] = $dd ? [$dd] : [];
            }

            //countries #########################
            $countryList = []; 
            foreach ((array)$xml->listapaises as $key => $value) {        
                $countryList[] = [
                    "code"  => str_replace('p','',$key), 
                    "state" => $value,
                ];                
            }
            $result['countryList'] = $countryList;

            //routes #########################
            $routeList = []; 
            foreach ((array)$xml->id_tipo as $key => $value) {        
                $routeList[] = [
                    "id"    => substr($key, 1) * 1,
                    "route" => $value
                ];                
            }
            $result['routeList'] = $routeList;
            
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
    * routeBackupRoutes API
    */
    public function routeBackupRoutes(Request $request)
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
            
            $data = $soap->ListarBkRutaFallo([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "cPais"             => $request->cPais ?? "",
                "cRutaFallo"        => $request->cRutaFallo ?? "",            
                "iIDTipoBulk"       => $request->iIDTipoBulk ?? -1,
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));

            $dd = $data?->tBKRuta?->tBKRutaRow ?? null;

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($xml){
                    //state
                    if(!(int)$item->PAIS_DESTINO){
                        $tmp = $item->PAIS_DESTINO;
                        $item->PAIS_DESTINO = $tmp == '*' ? '-1' : '0';
                        $item->state        = $tmp == '*' ? "Default" : "International";
                    }else{
                        $key = 'p'.$item->PAIS_DESTINO;
                        $item->state = (string)$xml->listapaises->$key;
                    }

                    //type
                    $key = 't'.$item->ID_TIPO;
                    $item->tyepRoute = (string)$xml->id_tipo->$key;

                    //RUTA_BK
                    $backList = [];
                    array_map(function($row) use (&$backList){

                        $tmp = explode(';', $row);
                        $aa = count($tmp) > 1 ? "[$tmp[0]] $tmp[1]%" : $row;

                        array_push($backList, $aa);

                    }, explode(',', urldecode($item->RUTA_BK)));

                    $item->backupList = $backList;

                    return $item;          
                }, $dd);
            }else{
                $result['data'] = $dd ? [$dd] : [];
            }

            //countries #########################
            $countryList = []; 
            foreach ((array)$xml->listapaises as $key => $value) {        
                $countryList[] = [
                    "code"  => str_replace('p','',$key), 
                    "state" => $value,
                ];                
            }
            $result['countryList'] = $countryList;

            //customrs ########################
            $result['customerList'] = $this->getDashCustomerList($request->username, $encPass);

            //routes #########################
            $routeList = []; 
            foreach ((array)$xml->id_tipo as $key => $value) {        
                $routeList[] = [
                    "id"    => substr($key, 1) * 1,
                    "route" => $value
                ];                
            }
            $result['routeList'] = $routeList;
            
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
