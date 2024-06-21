<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QualityController extends Controller
{
    /**
    * qualityMap API
    */
    public function qualityMap(Request $request)
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
            $soap = $this->getSoap(urn: 'smpp');

            $encPass    = md5( $request->password."R2Tr0||D2" );

            $result     = [];
            
            $data = $soap->MostrarCalidadPaises([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "cFechaInicio"      => date('d-m-Y'),                
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));  

            $dd = $data?->tResumenCalidadFecha?->tResumenCalidadFechaRow ?? null;

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($xml){
                    
                    //state
                    if(!(int)$item->Pais_Destino){
                        $tmp = $item->Pais_Destino;
                        $item->Pais_Destino = $tmp == '*' ? '-1' : '0';
                        $item->state        = $tmp == '*' ? "Default" : "International";
                    }else{
                        $key = 'p'.$item->Pais_Destino;
                        $item->state = (string)$xml->listapaises->$key;
                        $item->code  = $this->getCountryCode($item->Pais_Destino);
                    }

                    $item->Promedio_Submitted = $item->Sms_Submitted != 0 ? round($item->Tiempo_Submitted / $item->Sms_Submitted, 2) : 0;
                    $item->Promedio_Delivered = $item->Sms_Delivered != 0 ? round($item->Tiempo_Delivered / $item->Sms_Delivered, 2) : 0;                    
                    
                    return $item;          
                }, $dd);
            }else{
                $result['data'] = $dd ? [$dd] : [];
            }             
            
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
    * qualityCountry API
    */
    public function qualityCountry(Request $request)
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
            $soap = $this->getSoap(urn: 'smpp');

            $encPass    = md5( $request->password."R2Tr0||D2" );

            $result     = [];
            
            $data = $soap->MostrarCalidadRutaPaisProveedor([
                "cLogin"       => $request->username, 
                "cPassword"    => $encPass, 
                "cFechaInicio" => $request->cFechaInicio ? date('d-m-Y', strtotime($request->cFechaInicio)) : date('d-m-Y'),
                "iHora"        => date('H'),
                "cRuta"        => $request->cRuta ?? null,
                "cPaises"      => $request->cPaises ?? null,
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));  

            $dd = $data?->tResumenCalidadFecha?->tResumenCalidadFechaRow ?? null;

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($xml){
                    
                    //state
                    if(!(int)$item->Pais_Destino){
                        $tmp = $item->Pais_Destino;
                        $item->Pais_Destino = $tmp == '*' ? '-1' : '0';
                        $item->state        = $tmp == '*' ? "Default" : "International";
                    }else{
                        $key = 'p'.$item->Pais_Destino;
                        $item->state = (string)$xml->listapaises->$key;
                        $item->code  = $this->getCountryCode($item->Pais_Destino);
                    }

                    //
                    $tmp = str_replace("+", " ", urldecode($item->NombreProveedor));
                    $item->NombreProveedor = $tmp;

                    $item->Promedio_Submitted = $item->Sms_Submitted != 0 ? round($item->Tiempo_Submitted / $item->Sms_Submitted, 2) : 0;
                    $item->Promedio_Delivered = $item->Sms_Delivered != 0 ? round($item->Tiempo_Delivered / $item->Sms_Delivered, 2) : 0;                    
                    
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
    * qualityMccMnc API
    */
    public function qualityMccMnc(Request $request)
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
            $soap = $this->getSoap(urn: 'smpp');

            $encPass    = md5( $request->password."R2Tr0||D2" );

            $result     = [];
            
            $data = $soap->MostrarCalidadRutaPaisMccMncProveedor([
                "cLogin"       => $request->username, 
                "cPassword"    => $encPass, 
                "cFechaInicio" => $request->cFechaInicio ? date('d-m-Y', strtotime($request->cFechaInicio)) : date('d-m-Y'),
                "iHora"        => date('H'),
                "cRuta"        => $request->cRuta ?? null,
                "cPaises"      => $request->cPaises ?? null,                
                "cMccMnc"      => $request->cMccMnc ?? null,                
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));  

            $dd = $data?->tResumenCalidadFecha?->tResumenCalidadFechaRow ?? null;

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($xml){
                    
                    //state
                    if(!(int)$item->Pais_Destino){
                        $tmp = $item->Pais_Destino;
                        $item->Pais_Destino = $tmp == '*' ? '-1' : '0';
                        $item->state        = $tmp == '*' ? "Default" : "International";
                    }else{
                        $key = 'p'.$item->Pais_Destino;
                        $item->state = (string)$xml->listapaises->$key;
                        $item->code  = $this->getCountryCode($item->Pais_Destino);
                    }

                    //
                    $tmp = str_replace("+", " ", urldecode($item->NombreProveedor));
                    $item->NombreProveedor = $tmp;

                    $item->Promedio_Submitted = $item->Sms_Submitted != 0 ? round($item->Tiempo_Submitted / $item->Sms_Submitted, 2) : 0;
                    $item->Promedio_Delivered = $item->Sms_Delivered != 0 ? round($item->Tiempo_Delivered / $item->Sms_Delivered, 2) : 0;                    
                    
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
}
