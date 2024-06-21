<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PricingController extends Controller
{
    /**
    * pricingCountries API
    */
    public function pricingCountries(Request $request)
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
            
            $data = $soap->ListarPricingPais([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "cPaisDestino"      => $request->cPaisDestino ?? 0,
                "iIDTipoBulk"       => $request->iIDTipoBulk ?? 0,            
                "iIDMasterBulk"     => $request->iIDMasterBulk ?? 0,
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));

            $dd = $data?->TPricingPais?->TPricingPaisRow ?? null;

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

                    //NOMBRE_PROVEEDOR
                    $tmp = str_replace("+", " ", urldecode($item->NOMBRE_PROVEEDOR));
                    $item->NOMBRE_PROVEEDOR = $tmp;

                    //ARRAY_PRECIO_TIPOPAGO
                    $tmp = explode('-', urldecode($item->ARRAY_PRECIO_TIPOPAGO));
                    foreach($tmp as $key => $value){
                        $item->paymentPrices["payment$key"] = $value;
                    }

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

            //providers #########################
            $result['masterProviderList'] = $this->getMasterProviderList($request->username, $encPass);

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
    * pricingProviders API
    */
    public function pricingProviders(Request $request)
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
            
            $data = $soap->ListarPricingProveedor([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "iIDProveedor"      => $request->iIDProveedor ?? 0,                
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));

            $dd = $data?->TTarifasProveedor?->TTarifasProveedorRow ?? null;

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

                    //NOMBRE_PROVEEDOR
                    $tmp = str_replace("+", " ", urldecode($item->NOMBRE_PROVEEDOR));
                    $item->NOMBRE_PROVEEDOR = $tmp;

                    //ARRAY_PRECIO_TIPOPAGO
                    $tmp = explode('-', urldecode($item->ARRAY_PRECIO_TIPOPAGO));
                    foreach($tmp as $key => $value){
                        $item->paymentPrices["payment$key"] = $value;
                    }

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

    /**
    * pricingCustomers API
    */
    public function pricingCustomers(Request $request)
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
            
            $data = $soap->ListarPricingCliente([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "iIDProveedor"      => $request->iIDProveedor ?? 0,                
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));

            $dd = $data?->TPricingCliente?->TPricingClienteRow ?? null;

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

                    //NOMBRE_PROVEEDOR
                    $tmp = str_replace("+", " ", urldecode($item->NOMBRE_PROVEEDOR));
                    $item->NOMBRE_PROVEEDOR = $tmp;

                    //ARRAY_PRECIO_TIPOPAGO
                    $tmp = explode('-', urldecode($item->ARRAY_PRECIO_TIPOPAGO));
                    foreach($tmp as $key => $value){
                        $item->paymentPrices["payment$key"] = $value;
                    }

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

            //customrs ########################
            $result['customerList'] = $this->getDashCustomerList($request->username, $encPass);

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
    * pricingDefaults API
    */
    public function pricingDefaults(Request $request)
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
            
            $data = $soap->ListarPricingMaster([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "iIDMasterBulk"     => $request->iIDMasterBulk ?? "",                
                "cPaisDestino"      => $request->cPaisDestino ?? "",                
                "iIDTipoBulk"       => $request->iIDTipoBulk ?? "",                
                "iIDMasterBulkOut"  => $request->iIDMasterBulkOut ?? -1,                
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));

            $dd = $data?->TPrincingMaster?->TPrincingMasterRow ?? null;

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

                    //NOMBRE_PROVEEDOR
                    // $tmp = str_replace("+", " ", urldecode($item->NOMBRE_PROVEEDOR));
                    // $item->NOMBRE_PROVEEDOR = $tmp;

                    //ARRAY_PRECIO_TIPOPAGO
                    // $tmp = explode('-', urldecode($item->ARRAY_PRECIO_TIPOPAGO));
                    // foreach($tmp as $key => $value){
                    //     $item->paymentPrices["payment$key"] = $value;
                    // }

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

            //customrs ########################
            $result['customerList'] = $this->getDashCustomerList($request->username, $encPass);

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
