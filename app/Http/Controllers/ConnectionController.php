<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use stdClass;


class ConnectionController extends Controller
{
    /**
    * connectionClient API
    */
    public function connectionClient(Request $request)
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
            $soap = $this->getSoap(urn: "smpp");

            $encPass    = md5( $request->password."R2Tr0||D2" );

            $startDate  = $request->cFechaInicio  ? date('d-m-Y', strtotime($request->cFechaInicio)) : date('d-m-Y',time()); 
            $endDate    = $request->cFechaFin     ? date('d-m-Y', strtotime($request->cFechaFin)) : date('d-m-Y', time()-86400);

            $result     = [];
            
            $data = $soap->ListarConexionesClientes([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "iIDMasterProvider" => $request->iIDMasterProvider ?? -1,
                "iIDCliente"        => $request->iIDCliente ?? "",
                "iIDTipoBulk"       => $request->iIDTipoBulk ?? "",            
            ]);

            $dd = $data?->tConexion?->tConexionRow ?? null;

            // $dd = $dd ?? [];

            if(is_array($dd)){
                $result['data'] = array_map(function($item){   
                    $tmp = $item->Ultimo_Enquire; 
                    if(empty($tmp)){
                        $item->lastDate = "";
                    }else{
                        $timestamp = strtotime(str_replace('/', '-', substr($tmp, 0, 19)));
                        $item->lastDate = date("Y-m-d H:i:s", $timestamp);
                    }            
                    return $item;          
                }, $dd);
            }else{
                $result['data'] = $dd ? [$dd] : [];
            }

            //providers #########################
            $result['masterProviderList'] = $this->getMasterProviderList($request->username, $encPass);
            
            //customers ########################
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
    * connectionProvider API
    */
    public function connectionProvider(Request $request)
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
            $soap = $this->getSoap(urn: "smpp");

            $encPass    = md5( $request->password."R2Tr0||D2" );

            $startDate  = $request->cFechaInicio  ? date('d-m-Y', strtotime($request->cFechaInicio)) : date('d-m-Y',time()); 
            $endDate    = $request->cFechaFin     ? date('d-m-Y', strtotime($request->cFechaFin)) : date('d-m-Y', time()-86400);

            $result     = [];
            
            $data = $soap->ListarConexionesProveedor([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "iIDMasterProvider" => $request->iIDMasterProvider ?? -1,
                "iIDProveedor"      => $request->iIDProveedor ?? "",
                "iIDTipoBulk"       => $request->iIDTipoBulk ?? "",            
                "cRuta"             => $request->cRuta ?? "",            
            ]);

            $dd = $data?->tConexion?->tConexionRow ?? null;

            $result['data'] = $dd ?? [];

            //providers #########################
            $result['masterProviderList'] = $this->getMasterProviderList($request->username, $encPass);
            
            //providers ########################
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
