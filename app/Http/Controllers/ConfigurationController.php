<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfigurationController extends Controller
{
    /**
    * configAccount API
    */
    public function configAccount(Request $request)
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
            
            $data = $soap->ListarUsuariosDashBoardBulk([
                "cLogin"       => $request->username, 
                "cPassword"    => $encPass,                 
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));  

            $dd = $data?->tAccesos?->tAccesosRow ?? null;

            $providerList = $this->getMasterProviderList($request->username, $encPass); 

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($xml, $providerList){

                    $name = "";
                    array_filter($providerList, function($aa) use($item, &$name){

                        if($aa['ID_MASTER'] == $item->IDMasterBulk){
                            $name = $aa['NOMBRE'];
                        }

                        if($item->IDMasterBulk == -1){
                            $name = "Global Master";
                        }                        
                    });

                    $item->master = $name;
                    $item->report = 0;
                    return $item;

                }, $dd);
            }else{
                $result['data'] = $dd ? [$dd] : [];
            }

            $result['masterList'] = $providerList;
            
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
    * configAccountUserUpdate API
    */
    public function configAccountUserUpdate(Request $request)
    {
        try{

            $validateUser = Validator::make($request->all(), 
            [
                'username'    => 'required',
                'password'    => 'required',
                'cLoginNuevo' => 'required',
                'cEmail'      => 'required|email',
                'cMovil'      => 'required',
                'cPermisos'   => 'required',

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

            
            $data = $soap->CrearModificarUsuariosDashBoardBulk([
                "cLogin"       => $request->username, 
                "cPassword"    => $encPass,                 
                'iIDUsuario'   => $request->iIDUsuario,
                'cLoginNuevo'  => $request->cLoginNuevo,
                'cEmail'       => $request->cEmail,
                'cMovil'       => $request->cMovil,                
                'cPermisos'    => $request->cPermisos,
                'iIDMasterBulk'=> $request->iIDMasterBulk,
            ]);

            if($data?->cDescripcion == 'OK'){
                return $this->configAccount($request);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => $data?->cDescripcion,
                ]);
            }

        }catch(Exception $e){

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
    * configAccountUserPasswordReset API
    */
    public function configAccountUserPasswordReset(Request $request)
    {
        try{

            $validateUser = Validator::make($request->all(), 
            [
                'username'    => 'required',
                'password'    => 'required',
                'iIDUsuario'  => 'required',
                'cLoginNuevo' => 'required',
                'cEmail'      => 'required|email',
                'cMovil'      => 'required',
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

            $data = $soap->CrearModificarUsuariosDashBoardBulk([
                "cLogin"        => $request->username, 
                "cPassword"     => $encPass,                 
                'iIDUsuario'    => $request->iIDUsuario,                
                'cLoginNuevo'   => $request->cLoginNuevo,
                'cEmail'        => $request->cEmail,
                'cMovil'        => $request->cMovil,   
                'cPermisos'     => -1,
                'iIDMasterBulk' => 'REC',
            ]);

            if($data?->cDescripcion == 'OK'){
                return $this->configAccount($request);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => $data?->cDescripcion,
                ]);
            }

        }catch(Exception $e){

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
    * configAccountUserBlock API
    */
    public function configAccountUserBlock(Request $request)
    {
        try{

            $validateUser = Validator::make($request->all(), 
            [
                'username'    => 'required',
                'password'    => 'required',
                'iIDUsuario'  => 'required',                
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

            $encPass = md5( $request->password."R2Tr0||D2" );            

            $data = $soap->BloquearDesbloquearUsuariosDashBoardBulk([
                "cLogin"        => $request->username, 
                "cPassword"     => $encPass,                 
                'iIDUsuario'    => $request->iIDUsuario,                
                'iEstado'       => 1,                
            ]);

            if($data?->cDescripcion == 'OK'){
                return $this->configAccount($request);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => $data?->cDescripcion,
                ]);
            }

        }catch(Exception $e){

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
    * configAccountMineUpdate API
    */
    public function configAccountMineUpdate(Request $request)
    {
        try{

            $validateUser = Validator::make($request->all(), 
            [
                'username'       => 'required',
                'password'       => 'required',                
                'cEmail'         => 'required|email',                
                'cMovil'         => 'required',                
                'cPasswordNuevo' => 'required',
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

            $encPass = md5( $request->password."R2Tr0||D2" );            

            $data = $soap->CrearModificarUsuariosDashBoardBulk([
                "cLogin"        => $request->username, 
                "cPassword"     => $encPass,   
                "iIDUsuario"    => "",
                "cLoginNuevo"   => "",
                "cPasswordNuevo"=> "",
                "cEmail"        => "",
                "cMovil"        => "",
                "cPermisos"     => "",
                "iIDMasterBulk" => "",
            ]);

            if($data?->cDescripcion == 'OK'){
                // return $this->configAccount($request);
                $ruta = $_SERVER['SERVER_NAME'] . '/' . substr( $_SERVER['SCRIPT_NAME'], 1, strripos($_SERVER['SCRIPT_NAME'],"/") );
                $sms = ( 
                    $ruta.'
                    User: '.$request->username. '
                    Password: '.md5( $request->cPasswordNuevo."R2Tr0||D2" )
                );
                
                // ENVÍO DE SMS //
                if( isset($request->cMovil) ){
                    $cMovil = trim($request->cMovil,'+ ');
                    $this->sendSMS($cMovil,$sms);
                }

                return response()->json([
                    'status' => true,
                    'message' => "User info updated successfully",
                ]);

            }else{
                return response()->json([
                    'status' => false,
                    'message' => $data?->cDescripcion,
                ]);
            }

        }catch(Exception $e){

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
    * configAccountAddNewUser API
    */
    public function configAccountAddNewUser(Request $request)
    {
        try{

            $validateUser = Validator::make($request->all(), 
            [
                'username'      => 'required',
                'password'      => 'required',                
                'Login'         => 'required',                
                'Email'         => 'required|email',                
                'Mobile'        => 'required',                
                'Permission'    => 'required',
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

            $encPass = md5( $request->password."R2Tr0||D2" );    
            
            //Generate Password for new user.
            $userNewPass = "";
			$characters = '23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ';			
			for ($i = 0; $i < 6; $i++) {
				$userNewPass .= $characters[mt_rand(0, strlen($characters) - 1)];
			}
			$userNewPass = md5( $userNewPass."R2Tr0||D2" );    

            $data = $soap->CrearModificarUsuariosDashBoardBulk([
                "cLogin"         => $request->username, 
                "cPassword"      => $encPass,   
                "iIDUsuario"     => 0,
                "cLoginNuevo"    => $request->Login,
                "cPasswordNuevo" => $userNewPass,
                "cEmail"         => $request->Email,
                "cMovil"         => $request->Mobile,
                "cPermisos"      => $request->Permission,
                "iIDMasterBulk"  => -1,
            ]);

            if($data?->cDescripcion == 'OK'){
                return $this->configAccount($request);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => $data?->cDescripcion,
                ]);
            }

        }catch(Exception $e){

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
    * configCustomers API
    */
    public function configCustomers(Request $request)
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
            
            $data = $soap->ListarClientesDashBoardBulk([
                "cLogin"       => $request->username, 
                "cPassword"    => $encPass,                 
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));  

            $dd = $data?->TCliente?->TClienteRow ?? null;

            $providerList = $this->getMasterProviderList($request->username, $encPass); 

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($xml, $providerList){
                    return $item;
                }, $dd);
            }else{
                $result['data'] = $dd ? [$dd] : [];
            }

            //Master & Customer List 
            $result['masterList'] = $providerList;

            //customer List
            $result['customerList'] = $this->getCustomerList($request->username, $encPass);
            
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
    * configCustomerUpdate API
    */
    public function configCustomerUpdate(Request $request)
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
            
            $data = $soap->CrearModificarDatosCliente([
                "iIDCliente"       => $request->iIDCliente, 
                "iIDMasterCliente" => $request->iIDMasterCliente,                 
                "cEmpresa"         => $request->cEmpresa,                 
                "cFirmante"        => $request->cFirmante,                 
                "cCif"             => $request->cCif,                 
                "cIva"             => $request->cIva,                 
                "cDireccion"       => $request->cDireccion,
                "cPais"            => $request->cPais,
                "cEmail_Admin"     => $request->cEmail_Admin,
                "cEmail_Comercial" => $request->cEmail_Comercial,
                "cEmail_Routing"   => $request->cEmail_Routing,
                "cEmail_Tecnico"   => $request->cEmail_Tecnico,
            ]);

            return $this->configAccount($request);

            // Do not del
            // if($data?->cDescripcion == 'OK'){
            //     return $this->configAccount($request);
            // }else{
            //     return response()->json([
            //         'status' => false,
            //         'message' => $data?->cDescripcion,
            //     ]);
            // }                       

        }catch(Exception $e){

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
    * configCustomerAddNew API
    */
    public function configCustomerAddNew(Request $request)
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
            
            $data = $soap->CrearModificarDatosCliente([
                "iIDCliente"       => $request->iIDCliente, 
                "iIDMasterCliente" => $request->iIDMasterCliente,                 
                "cEmpresa"         => $request->cEmpresa,                 
                "cFirmante"        => $request->cFirmante,                 
                "cCif"             => $request->cCif,                 
                "cIva"             => $request->cIva,                 
                "cDireccion"       => $request->cDireccion,
                "cPais"            => $request->cPais,
                "cEmail_Admin"     => $request->cEmail_Admin,
                "cEmail_Comercial" => $request->cEmail_Comercial,
                "cEmail_Routing"   => $request->cEmail_Routing,
                "cEmail_Tecnico"   => $request->cEmail_Tecnico,
            ]);

            return $this->configAccount($request);

            // Do not del
            // if($data?->cDescripcion == 'OK'){
            //     return $this->configAccount($request);
            // }else{
            //     return response()->json([
            //         'status' => false,
            //         'message' => $data?->cDescripcion,
            //     ]);
            // }                       

        }catch(Exception $e){

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
