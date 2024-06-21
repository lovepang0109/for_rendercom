<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BillingController extends Controller
{
    /**
    * billingDefaults API
    */
    public function billingDefaults(Request $request)
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
            
            $data = $soap->ListarFacturasBulk([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "iMes"              => $request->iMes ?? -1,
                "iAnyo"             => $request->iAnyo ?? date('Y'),            
                "iIDMasterBulk"     => $request->iIDMasterBulk ?? -1,
                "iIDEstado"         => $request->iIDEstado ?? "",
                "iTipoCobro"        => $request->iTipoCobro ?? "",
            ]);

            //providers #########################
            $mpList = $this->getMasterProviderList($request->username, $encPass); 
            $result['masterProviderList'] = $mpList;  
            
            //billstatus #######################
            $xml = simplexml_load_file(public_path("langs/en.xml"));
            $result['statusList'] = $xml->billstatus;

            //
            $dd = $data?->tFactura?->tFacturaRow ?? null;

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($mpList, $xml){
                    //
                    $item->RAZON_SOCIAL = urldecode($item->RAZON_SOCIAL);

                    //
                    $tmp = str_replace("+", " ", urldecode($item->COMENTARIOS));
                    $item->COMENTARIOS = $tmp;

                    //NombreCliente
                    $tmp = str_replace("+", " ", urldecode($item->NombreCliente));
                    $item->NombreCliente = $tmp;

                    //FECHA_MODIFICACION
                    $item->FECHA_MODIFICACION = date('Y-m-d H:i:s', strtotime($item->FECHA_MODIFICACION));

                    //Master Name
                    $masterName = "";
                    array_filter($mpList, function($aa) use($item, &$masterName) { 
                        if($aa['ID_MASTER'] == $item->ID_MasterBulk){
                            $masterName = $aa['NOMBRE'];
                        }
                    });
                    $item->masterName = $masterName;

                    //Month Name
                    $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                    $item->month =  $months[$item->MES_FACTURACION-1];

                    //status
                    $key = "s$item->ID_ESTADO";
                    $item->statusName = get_object_vars($xml->billstatus->$key)[0] ?? "";

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
    * billingDefaultsItem API
    */
    public function billingDefaultsItem(Request $request)
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
            
            $data = $soap->DetalleFacturaBulk([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "iIDMasterBulk"     => $request->iIDMasterBulk ?? -1,
                "iIDCliente"        => $request->iIDCliente ?? "",
                "iAnyoFacturacion"  => $request->iAnyoFacturacion ?? "",
                "iIDFactura"        => $request->iIDFactura ?? "",
            ]);

            //
            $dd_bill = $data?->TFactura?->TFacturaRow ?? null;
            if(isset($dd_bill)){
                $dd_bill->INFO_PROVEEDOR = trim($dd_bill->INFO_PROVEEDOR);
                $dd_bill->INFO_CLIENTE = trim($dd_bill->INFO_CLIENTE);
                $dd_bill->BANCO_PROVEEDOR = trim($dd_bill->BANCO_PROVEEDOR);
            }
            $result['bill'] = $dd_bill;

            $dd_billconcept = $data?->tConceptoFactura?->tConceptoFacturaRow ?? null;
            if(is_array($dd_billconcept)){
                $result['billConcept'] = $dd_billconcept;
            }else{
                $result['billConcept'] = $dd_billconcept ? [$dd_billconcept] : [];
            }            
            
            
            $xml = simplexml_load_file(public_path("langs/en.xml"));  

            $dd_billdetail = $data?->tDetalleFactura?->tDetalleFacturaRow ?? null;

            if (is_array($dd_billdetail)) {
                $billDetails = $dd_billdetail;
            } else {
                $billDetails = $dd_billdetail ? [$dd_billdetail] : [];
            }
            
            $result['billDetail'] = array_map(function($item) use ($xml) {
                // state
                if (!(int)$item->PAIS_DESTINO) {
                    $tmp = $item->PAIS_DESTINO;
                    $item->PAIS_DESTINO = $tmp == '*' ? '-1' : '0';
                    $item->state = $tmp == '*' ? "Default" : "International";
                } else {
                    $key = 'p' . $item->PAIS_DESTINO;
                    $item->state = (string)$xml->listapaises->$key;
                }
                return $item;          
            }, $billDetails);
            
            $dd_billhistory = $data?->tHistoricoFactura?->tHistoricoFacturaRow ?? null;
            if(is_array($dd_billhistory)){
                $result['billHistory'] = $dd_billhistory;
            }else{
                $result['billHistory'] = $dd_billhistory ? [$dd_billhistory] : [];
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
    * billingCustomerDate API
    */
    public function billingCustomerDate(Request $request)
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
            
            $data = $soap->FacturacionDashboardBulk_Cliente([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "cFechaInicio"      => date('d-m-Y', strtotime($request->cFechaInicio)) ?? date('d-m-Y', strtotime('-1 week')),
                "cFechaFin"         => date('d-m-Y', strtotime($request->cFechaFin))    ?? date('d-m-Y'),            
                "cPaises"           => $request->cPaises ?? 0,
                "iIDCliente"        => $request->iIDCliente ?? "",
                "iIDProveedor"      => $request->iIDProveedor ?? "",
                "iIDTipoRuta"       => $request->iIDTipoRuta ?? "",
                "cMccMnc"           => $request->cMccMnc ?? "",
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));  

            $dd = $data?->tResumenCliente?->tResumenClienteRow ?? null;

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($xml){
                    //
                    $item->Master_Proveedor = urldecode($item->Master_Proveedor);

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
                    $tmp = str_replace("+", " ", urldecode($item->Cliente));
                    $item->Cliente = $tmp;

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
            
            //Provider List ##############################
            $result['providerList'] = $this->getProviderList($request->username, $encPass);   
            
            //customrs ########################
            $result['customerList'] = $this->getDashCustomerList($request->username, $encPass);

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
    * billingCustomerMccMnc API
    */
    public function billingCustomerMccMnc(Request $request)
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
            
            $data = $soap->FacturacionDashboardBulk_Cliente_MccMnc([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "cFechaInicio"      => date('d-m-Y', strtotime($request->cFechaInicio)) ?? date('d-m-Y', strtotime('-1 week')),
                "cFechaFin"         => date('d-m-Y', strtotime($request->cFechaFin))    ?? date('d-m-Y'),            
                "cPaises"           => $request->cPaises ?? 0,
                "iIDCliente"        => $request->iIDCliente ?? "",
                "iIDProveedor"      => $request->iIDProveedor ?? "",
                "iIDTipoRuta"       => $request->iIDTipoRuta ?? "",
                "cMccMnc"           => $request->cMccMnc ?? "",
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));  

            $dd = $data?->tResumenCliente?->tResumenClienteRow ?? null;

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($xml){
                    //
                    $item->Master_Proveedor = urldecode($item->Master_Proveedor);

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
                    $tmp = str_replace("+", " ", urldecode($item->Cliente));
                    $item->Cliente = $tmp;

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
            
            //Provider List ##############################
            $result['providerList'] = $this->getProviderList($request->username, $encPass);   
            
            //customrs ########################
            $result['customerList'] = $this->getDashCustomerList($request->username, $encPass);

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
    * billingProviderDate API
    */
    public function billingProviderDate(Request $request)
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
            
            $data = $soap->FacturacionDashboardBulk_Proveedor([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "cFechaInicio"      => date('d-m-Y', strtotime($request->cFechaInicio)) ?? date('d-m-Y', strtotime('-1 week')),
                "cFechaFin"         => date('d-m-Y', strtotime($request->cFechaFin))    ?? date('d-m-Y'),            
                "cPaises"           => $request->cPaises ?? 0,
                "iIDCliente"        => $request->iIDCliente ?? "",
                "iIDProveedor"      => $request->iIDProveedor ?? "",
                "iIDTipoRuta"       => $request->iIDTipoRuta ?? "",
                "cMccMnc"           => $request->cMccMnc ?? "",
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));  

            $dd = $data?->tResumenProveedor?->tResumenProveedorRow ?? null;

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($xml){
                    //
                    $item->Master_Proveedor = urldecode($item->Master_Proveedor);

                    //state
                    if(!(int)$item->Pais_Destino){
                        $tmp = $item->Pais_Destino;
                        $item->Pais_Destino = $tmp == '*' ? '-1' : '0';
                        $item->state        = $tmp == '*' ? "Default" : "International";
                    }else{
                        $key = 'p'.$item->Pais_Destino;
                        $item->state = (string)$xml->listapaises->$key;
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
            
            //Provider List ##############################
            $result['providerList'] = $this->getProviderList($request->username, $encPass);   
            
            //customrs ########################
            $result['customerList'] = $this->getDashCustomerList($request->username, $encPass);

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
    * billingProviderMccMnc API
    */
    public function billingProviderMccMnc(Request $request)
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
            
            $data = $soap->FacturacionDashboardBulk_Proveedor_MccMnc([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "cFechaInicio"      => date('d-m-Y', strtotime($request->cFechaInicio)) ?? date('d-m-Y', strtotime('-1 week')),
                "cFechaFin"         => date('d-m-Y', strtotime($request->cFechaFin))    ?? date('d-m-Y'),            
                "cPaises"           => $request->cPaises ?? 0,
                "iIDCliente"        => $request->iIDCliente ?? "",
                "iIDProveedor"      => $request->iIDProveedor ?? "",
                "iIDTipoRuta"       => $request->iIDTipoRuta ?? "",
                "cMccMnc"           => $request->cMccMnc ?? "",
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));  

            $dd = $data?->tResumenProveedor?->tResumenProveedorRow ?? null;

            if(is_array($dd)){
                $result['data'] = array_map(function($item) use ($xml){
                    //
                    $item->Master_Proveedor = urldecode($item->Master_Proveedor);

                    //state
                    if(!(int)$item->Pais_Destino){
                        $tmp = $item->Pais_Destino;
                        $item->Pais_Destino = $tmp == '*' ? '-1' : '0';
                        $item->state        = $tmp == '*' ? "Default" : "International";
                    }else{
                        $key = 'p'.$item->Pais_Destino;
                        $item->state = (string)$xml->listapaises->$key;
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
            
            //Provider List ##############################
            $result['providerList'] = $this->getProviderList($request->username, $encPass);   
            
            //customrs ########################
            $result['customerList'] = $this->getDashCustomerList($request->username, $encPass);

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
    * billingDetails API
    */
    public function billingDetails(Request $request)
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
            
            $data = $soap->FacturacionDashboardBulk_Detalle_Fecha([
                "cLogin"            => $request->username, 
                "cPassword"         => $encPass, 
                "cFechaInicio"      => date('d-m-Y', strtotime($request->cFechaInicio)) ?? date('d-m-Y', strtotime('-1 days')),
                "cFechaFin"         => date('d-m-Y', strtotime($request->cFechaFin))    ?? date('d-m-Y'),            
                "cPaises"           => $request->cPaises ?? 0,
                "iIDCliente"        => $request->iIDCliente ?? "",
                "iIDProveedor"      => $request->iIDProveedor ?? "",
                "iIDTipoRuta"       => $request->iIDTipoRuta ?? "",
                "cMccMnc"           => $request->cMccMnc ?? "",
            ]);

            $xml = simplexml_load_file(public_path("langs/en.xml"));  

            $dd = $data?->tResumenDetalle?->tResumenDetalleRow ?? null;

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
                    $item->NombreMasterProveedor = urldecode($item->NombreMasterProveedor);

                    //
                    $tmp = str_replace("+", " ", urldecode($item->Cliente));
                    $item->Cliente = $tmp;

                    //
                    $tmp = str_replace("+", " ", urldecode($item->NombreProveedor));
                    $item->NombreProveedor = $tmp;

                    //
                    $item->NombreMasterCliente = urldecode($item->NombreMasterCliente);

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
            
            //Provider List ##############################
            $result['providerList'] = $this->getProviderList($request->username, $encPass);   
            
            //customrs ########################
            $result['customerList'] = $this->getDashCustomerList($request->username, $encPass);

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
}
