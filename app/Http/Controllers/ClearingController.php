<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClearingController extends Controller
{
    /**
    * clearingMccMnc API
    */
    public function clearingMccMnc(Request $request)
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
            
            $data = $soap->MostrarDashboardBulkMastersNew([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "cFechaInicio"      => date('d-m-Y', strtotime($request->cFechaInicio)) ?? date('d-m-Y', strtotime('-1 days')),
                "cFechaFin"         => date('d-m-Y', strtotime($request->cFechaFin))    ?? date('d-m-Y'),            
                "iIDMasterBulk"     => $request->iIDMasterBulk ?? -1,                
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));  

            $dd = $data?->tResumenMaster?->tResumenMasterRow ?? null;

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
                    }
                    
                    //
                    $item->NombreMaster = urldecode($item->NombreMaster);                    

                    return $item;          
                }, $dd);
            }else{
                $result['data'] = $dd ? [$dd] : [];
            } 

            //MasterProvider List ##############################
            $result['masterProviderList'] = $this->getMasterProviderList($request->username, $encPass);   
            
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
    * clearingDate API
    */
    public function clearingDate(Request $request)
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
            
            $data = $soap->MostrarDashboardBulkMastersNew([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "cFechaInicio"      => date('d-m-Y', strtotime($request->cFechaInicio)) ?? date('d-m-Y', strtotime('-1 days')),
                "cFechaFin"         => date('d-m-Y', strtotime($request->cFechaFin))    ?? date('d-m-Y'),            
                "iIDMasterBulk"     => $request->iIDMasterBulk ?? -1,                
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));  

            $dd = $data?->tResumenMaster?->tResumenMasterRow ?? null;

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
                    }
                    
                    //
                    $item->NombreMaster = urldecode($item->NombreMaster);                    

                    return $item;          
                }, $dd);
            }else{
                $result['data'] = $dd ? [$dd] : [];
            } 

            //MasterProvider List ##############################
            $result['masterProviderList'] = $this->getMasterProviderList($request->username, $encPass);   
            
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
    * clearingProviderMccMnc API
    */
    public function clearingProviderMccMnc(Request $request)
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
            
            $data = $soap->MostrarDashboardBulkMastersNew([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "cFechaInicio"      => date('d-m-Y', strtotime($request->cFechaInicio)) ?? date('d-m-Y', strtotime('-1 days')),
                "cFechaFin"         => date('d-m-Y', strtotime($request->cFechaFin))    ?? date('d-m-Y'),            
                "iIDMasterBulk"     => $request->iIDMasterBulk ?? -1,                
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));  

            $dd = $data?->tResumenMaster?->tResumenMasterRow ?? null;

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
                    }
                    
                    //
                    $item->NombreMaster = urldecode($item->NombreMaster);                    

                    return $item;          
                }, $dd);
            }else{
                $result['data'] = $dd ? [$dd] : [];
            } 

            //MasterProvider List ##############################
            $result['masterProviderList'] = $this->getMasterProviderList($request->username, $encPass);   
            
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
    * clearingProviderDate API
    */
    public function clearingProviderDate(Request $request)
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
            
            $data = $soap->MostrarDashboardBulkMastersNew([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "cFechaInicio"      => date('d-m-Y', strtotime($request->cFechaInicio)) ?? date('d-m-Y', strtotime('-1 days')),
                "cFechaFin"         => date('d-m-Y', strtotime($request->cFechaFin))    ?? date('d-m-Y'),            
                "iIDMasterBulk"     => $request->iIDMasterBulk ?? -1,                
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));  

            $dd = $data?->tResumenMaster?->tResumenMasterRow ?? null;

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
                    }
                    
                    //
                    $item->NombreMaster = urldecode($item->NombreMaster);                    

                    return $item;          
                }, $dd);
            }else{
                $result['data'] = $dd ? [$dd] : [];
            } 

            //MasterProvider List ##############################
            $result['masterProviderList'] = $this->getMasterProviderList($request->username, $encPass);   
            
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
