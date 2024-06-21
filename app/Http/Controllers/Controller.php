<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\CssSelector\XPath\Extension\FunctionExtension;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function getSoap($url="193.219.96.244:8100", $urn="panel"){
        //WS connect
        $url = "http://$url/wsa/wsa1/wsdl?targetURI=urn:$urn";
        
        $soapOptions = array(
            'proxy_host'         => env('PROXY_HOST'),
            'proxy_port'         => env('PROXY_PORT'),
            'proxy_login'        => env('PROXY_USER'),
            'proxy_password'     => env('PROXY_PASS'),
            'trace'              => 1,
            'keep_alive'         => false,
            'connection_timeout' => 5000,
            'cache_wsdl'         => WSDL_CACHE_NONE,
            'compression'        => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
        );

        // return new \SoapClient($url, $soapOptions);
        try{
            return new \SoapClient($url, $soapOptions);
        }catch(\Exception $e){
            $error = $e->getMessage();
            return false;
        }
    }

    public function objectToArray ($obj, $rowname, $element='')
	{
		$rownameRow = $rowname.'Row';
		if(isset($obj->$rowname->$rownameRow)) {
			$arrayTrabajo = $obj->$rowname->$rownameRow;
		}
		if (isset($arrayTrabajo)) {
			if (!is_array($arrayTrabajo)){
				$temp = (array)$arrayTrabajo;
				$arrayTrabajo = array();
				$arrayTrabajo[] = $temp;
				if($element!=''){
					$arrayTrabajo["$temp->$element"] = $temp;
				}
			}
		}else{
			$arrayTrabajo = false;
		}
		return $arrayTrabajo;
	}

    /**
     * Get the Provider List
     */
    public function getProviderList($username, $password){

        // @$file = json_decode(file_get_contents(public_path('json/0_ListarProveedoresDashBoardBulk.json')),true);
        @$file = json_decode(file_get_contents(base_path('tmp/0_ListarProveedoresDashBoardBulk.json')),true);

        if( isset($file['source']) && $file['source'] == date('Ymd') ){
            $listadoProveedores = $file['data'];
        }else{

            $soap = $this->getSoap();
            $res = $soap->ListarProveedoresDashBoardBulk([
                "cLogin"    => $username,
                "cPassword" => $password
            ]);

            $encode = json_encode($res);
            $decode = json_decode($encode,true);
            $listadoProveedores = $decode['tProveedor']['tProveedorRow'];

            $file = [
                "source" => date('Ymd'),
                "data" => $listadoProveedores
            ];

            file_put_contents(public_path('json/0_ListarProveedoresDashBoardBulk.json'), json_encode($file));

            
        }

        return $listadoProveedores;
    }

    /**
     * Get the Master Provider List
     */
    public function getMasterProviderList($username, $password){

        // @$file = json_decode(file_get_contents(public_path('json/0_ListarMasterProveedorDashBoardBulk.json')),true);
        @$file = json_decode(file_get_contents(base_path('tmp/0_ListarMasterProveedorDashBoardBulk.json')),true);


        if( isset($file['source']) && $file['source'] == date('Ymd') ){
            $listadoProveedores = $file['data'];
        }else{

            $soap = $this->getSoap();
            $res = $soap->ListarMasterProveedorDashBoardBulk([
                "cLogin"    => $username,
                "cPassword" => $password
            ]);

            $encode = json_encode($res);
            $decode = json_decode($encode,true);
            $listadoProveedores = $decode['tMasterProveedor']['tMasterProveedorRow'];

            $file = [
                "source" => date('Ymd'),
                "data" => $listadoProveedores
            ];

            file_put_contents(public_path('json/0_ListarMasterProveedorDashBoardBulk.json'), json_encode($file));

        }

        return $listadoProveedores;
    }

    /**
    * Get the customer List
    */
    public function getCustomerList($username, $password){

        // @$file = json_decode(file_get_contents(public_path('json/0_ListarClientesDashBoardBulk.json')), true);
        @$file = json_decode(file_get_contents(base_path('tmp/0_ListarClientesDashBoardBulk.json')), true);


        if( isset($file['source']) && $file['source'] == date('Ymd') ){

            $listadoClientes = $file['data'];
        }else{

            $soap = $this->getSoap();
            $res = $soap->ListarClientesDashBoardBulk([
                "cLogin"    => $username,
                "cPassword" => $password
            ]);

            $encode = json_encode($res);
            $decode = json_decode($encode,true);
            $listadoClientes = $decode['TCliente']['TClienteRow'];           
    
            $file = [
                "source" => date('Ymd'),
                "data" => $listadoClientes
            ];
            
            file_put_contents(public_path('json/0_ListarClientesDashBoardBulk.json'), json_encode($file));

            
        }

        return $listadoClientes;
    }

    /**
    * Get the Dash Customer List
    */
    public function getDashCustomerList($username, $password){

        // @$file = json_decode(file_get_contents(public_path('json/0_ListarClientesDashBoardBulk.json')), true);
        @$file = json_decode(file_get_contents(base_path('tmp/0_ListarClientesDashBoardBulk.json')), true);


        if( isset($file['source']) && $file['source'] == date('Ymd') ){

            $listadoClientes = $file['data'];
        }else{

            $soap = $this->getSoap();
            $res = $soap->ListarClientesDashBoardBulk([
                "cLogin"    => $username,
                "cPassword" => $password
            ]);

            $encode = json_encode($res);
            $decode = json_decode($encode,true);
            $listadoClientes = $decode['TCliente']['TClienteRow'];           
    
            $file = [
                "source" => date('Ymd'),
                "data" => $listadoClientes
            ];
            
            file_put_contents(public_path('json/0_ListarClientesDashBoardBulk.json'), json_encode($file));

            
        }

        foreach ($listadoClientes as $client){
            $name = str_replace(' ','_',$client['EMPRESA']);
            $auxlistado[$name] = $client; 
        }
        ksort($auxlistado);
        $listadoClientes = $auxlistado;

        return $listadoClientes;
    }

    /**
    * Get the masters provider List
    */
    public function getMasterProviderLists($username, $password){

        // @$file = json_decode(file_get_contents(public_path('json/0_ListarMasterProveedorDashBoardBulk.json')), true);
        @$file = json_decode(file_get_contents(base_path('tmp/0_ListarMasterProveedorDashBoardBulk.json')), true);


        if( isset($file['source']) && $file['source'] == date('Ymd') ){

            $listadoMasterProveedor = $file['data'];
        }else{

            $soap = $this->getSoap();
            $res = $soap->ListarMasterProveedorDashBoardBulk([
                "cLogin"    => $username,
                "cPassword" => $password
            ]);

            $encode = json_encode($res);
            $decode = json_decode($encode,true);
            $listadoMasterProveedor = $decode['tMasterProveedor']['tMasterProveedorRow'];           
    
            $file = [
                "source" => date('Ymd'),
                "data" => $listadoMasterProveedor
            ];
            
            file_put_contents(public_path('json/0_ListarMasterProveedorDashBoardBulk.json'), json_encode($file));

            
        }        

        return $listadoMasterProveedor;
    }

    /**
    * Get the MccMnc (Mobile Country Code, MobileNetworkCode) List
    */
    public function getMccMnc($country){

        $soap = $this->getSoap();
        $res = $soap->ListarMccMncPais([
            "cPaisDestino"    => $country,
        ]);

        $encode = json_encode($res);
        $decode = json_decode($encode,true);
        $tMccMncRow = $decode['tMccMnc']['tMccMncRow'] ?? [];

        return array_map(function($item){
            return [
                "MCC"      => $item['MCC'],
                "MNC"      => $item['MNC'],
                "OPERATOR" => str_replace("+", " ", urldecode($item['OPERATOR']))
            ];
        }, $tMccMncRow);        
    }

    /**
     * sendSMS 
     */
    public function sendSMS($tlf,$sms)
	{
		// ENVÍO DE SMS // 
		$sms = urlencode($sms);
		$curl = curl_init();
			curl_setopt_array($curl, array(
			CURLOPT_URL => "http://www.panelsms.com/httpinput/input.php?to=%20".$tlf."&text=".$sms."&user=push&password=perrypush&senderId=Verify&confirmation=1",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
			),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			$mensaje[0] = "KO";
			$mensaje[1] = "SMS FAILED [cURL Error #:" . $err . "]";
		} else {
			$mensaje[0] = "OK";
		}
		return $mensaje;
	}

    /**
     * get the country code
     */
    public function getCountryCode($num){

        $PAISMAP[93] = 'AF';
        $PAISMAP[355] = 'AL';
        $PAISMAP[213] = 'DZ';
        $PAISMAP[684] = 'AS';
        $PAISMAP[376] = 'AD';
        $PAISMAP[244] = 'AO';
        $PAISMAP[1268] = 'AG';
        $PAISMAP[54] = 'AR';
        $PAISMAP[374] = 'AM';
        $PAISMAP[61] = 'AU';
        $PAISMAP[43] = 'AT';
        $PAISMAP[994] = 'AZ';
        $PAISMAP[973] = 'BH';
        $PAISMAP[880] = 'BD';
        $PAISMAP[1246] = 'BB';
        $PAISMAP[375] = 'BY';
        $PAISMAP[32] = 'BE';
        $PAISMAP[501] = 'BZ';
        $PAISMAP[229] = 'BJ';
        $PAISMAP[975] = 'BT';
        $PAISMAP[591] = 'BO';
        $PAISMAP[387] = 'BA';
        $PAISMAP[267] = 'BW';
        $PAISMAP[55] = 'BR';
        $PAISMAP[673] = 'BN';
        $PAISMAP[359] = 'BG';
        $PAISMAP[226] = 'BF';
        $PAISMAP[257] = 'BI';
        $PAISMAP[855] = 'KH';
        $PAISMAP[237] = 'CM';
        $PAISMAP[15] = 'CA';
        $PAISMAP[238] = 'CV';
        $PAISMAP[236] = 'CF';
        $PAISMAP[235] = 'TD';
        $PAISMAP[56] = 'CL';
        $PAISMAP[86] = 'CN';
        $PAISMAP[57] = 'CO';
        $PAISMAP[269] = 'KM';
        $PAISMAP[506] = 'CR';
        $PAISMAP[385] = 'HR';
        $PAISMAP[53] = 'CU';
        $PAISMAP[357] = 'CY';
        $PAISMAP[420] = 'CZ';
        $PAISMAP[243] = 'CD';
        $PAISMAP[45] = 'DK';
        $PAISMAP[253] = 'DJ';
        $PAISMAP[767] = 'DM';
        $PAISMAP[18] = 'DO';
        $PAISMAP[670] = 'TL';
        $PAISMAP[593] = 'EC';
        $PAISMAP[20] = 'EG';
        $PAISMAP[503] = 'SV';
        $PAISMAP[240] = 'GQ';
        $PAISMAP[291] = 'ER';
        $PAISMAP[372] = 'EE';
        $PAISMAP[251] = 'ET';
        $PAISMAP[298] = 'FO';
        $PAISMAP[691] = 'FM';
        $PAISMAP[679] = 'FJ';
        $PAISMAP[358] = 'FI';
        $PAISMAP[33] = 'FR';
        $PAISMAP[241] = 'GA';
        $PAISMAP[220] = 'GM';
        $PAISMAP[995] = 'GE';
        $PAISMAP[49] = 'DE';
        $PAISMAP[233] = 'GH';
        $PAISMAP[30] = 'GR';
        $PAISMAP[299] = 'GL';
        $PAISMAP[473] = 'GD';
        $PAISMAP[671] = 'GU';
        $PAISMAP[502] = 'GT';
        $PAISMAP[224] = 'GN';
        $PAISMAP[245] = 'GW';
        $PAISMAP[592] = 'GY';
        $PAISMAP[509] = 'HT';
        $PAISMAP[504] = 'HN';
        $PAISMAP[36] = 'HU';
        $PAISMAP[354] = 'IS';
        $PAISMAP[91] = 'IN';
        $PAISMAP[62] = 'ID';
        $PAISMAP[98] = 'IR';
        $PAISMAP[964] = 'IQ';
        $PAISMAP[353] = 'IE';
        $PAISMAP[972] = 'IL';
        $PAISMAP[39] = 'IT';
        $PAISMAP[225] = 'CI';
        $PAISMAP[1876] = 'JM';
        $PAISMAP[81] = 'JP';
        $PAISMAP[962] = 'JO';
        $PAISMAP[76] = 'KZ';
        $PAISMAP[254] = 'KE';
        $PAISMAP[686] = 'KI';
        $PAISMAP[383] = 'KV';
        $PAISMAP[965] = 'KW';
        $PAISMAP[996] = 'KG';
        $PAISMAP[856] = 'LA';
        $PAISMAP[371] = 'LV';
        $PAISMAP[961] = 'LB';
        $PAISMAP[266] = 'LS';
        $PAISMAP[231] = 'LR';
        $PAISMAP[218] = 'LY';
        $PAISMAP[423] = 'LI';
        $PAISMAP[370] = 'LT';
        $PAISMAP[352] = 'LU';
        $PAISMAP[389] = 'MK';
        $PAISMAP[261] = 'MG';
        $PAISMAP[265] = 'MW';
        $PAISMAP[60] = 'MY';
        $PAISMAP[960] = 'MV';
        $PAISMAP[223] = 'ML';
        $PAISMAP[356] = 'MT';
        $PAISMAP[692] = 'MH';
        $PAISMAP[222] = 'MR';
        $PAISMAP[230] = 'MU';
        $PAISMAP[52] = 'MX';
        $PAISMAP[373] = 'MD';
        $PAISMAP[377] = 'MC';
        $PAISMAP[976] = 'MN';
        $PAISMAP[382] = 'ME';
        $PAISMAP[212] = 'MA';
        $PAISMAP[258] = 'MZ';
        $PAISMAP[95] = 'MM';
        $PAISMAP[264] = 'NA';
        $PAISMAP[674] = 'NR';
        $PAISMAP[977] = 'NP';
        $PAISMAP[31] = 'NL';
        $PAISMAP[64] = 'NZ';
        $PAISMAP[505] = 'NI';
        $PAISMAP[227] = 'NE';
        $PAISMAP[234] = 'NG';
        $PAISMAP[850] = 'KP';
        $PAISMAP[392] = 'NC';
        $PAISMAP[1670] = 'MP';
        $PAISMAP[47] = 'NO';
        $PAISMAP[968] = 'OM';
        $PAISMAP[92] = 'PK';
        $PAISMAP[680] = 'PW';
        $PAISMAP[507] = 'PA';
        $PAISMAP[675] = 'PG';
        $PAISMAP[595] = 'PY';
        $PAISMAP[51] = 'PE';
        $PAISMAP[63] = 'PH';
        $PAISMAP[48] = 'PL';
        $PAISMAP[351] = 'PT';
        $PAISMAP[787] = 'PR';
        $PAISMAP[974] = 'QA';
        $PAISMAP[243] = 'CG';
        $PAISMAP[381] = 'RS';
        $PAISMAP[40] = 'RO';
        $PAISMAP[7] = 'RU';
        $PAISMAP[250] = 'RW';
        $PAISMAP[869] = 'KN';
        $PAISMAP[758] = 'LC';
        $PAISMAP[1784] = 'VC';
        $PAISMAP[685] = 'WS';
        $PAISMAP[378] = 'SM';
        $PAISMAP[239] = 'ST';
        $PAISMAP[966] = 'SA';
        $PAISMAP[221] = 'SN';
        $PAISMAP[57] = 'SW';
        $PAISMAP[248] = 'SC';
        $PAISMAP[91] = 'JK';
        $PAISMAP[232] = 'SL';
        $PAISMAP[65] = 'SG';
        $PAISMAP[421] = 'SK';
        $PAISMAP[386] = 'SI';
        $PAISMAP[677] = 'SB';
        $PAISMAP[252] = 'SO';
        $PAISMAP[252] = 'SX';
        $PAISMAP[27] = 'ZA';
        $PAISMAP[82] = 'KR';
        $PAISMAP[211] = 'SS';
        $PAISMAP[34] = 'ES';
        $PAISMAP[94] = 'LK';
        $PAISMAP[249] = 'SD';
        $PAISMAP[597] = 'SR';
        $PAISMAP[268] = 'SZ';
        $PAISMAP[46] = 'SE';
        $PAISMAP[41] = 'CH';
        $PAISMAP[963] = 'SY';
        $PAISMAP[886] = 'TW';
        $PAISMAP[992] = 'TJ';
        $PAISMAP[66] = 'TH';
        $PAISMAP[1242] = 'BS';
        $PAISMAP[228] = 'TG';
        $PAISMAP[676] = 'TO';
        $PAISMAP[868] = 'TT';
        $PAISMAP[216] = 'TN';
        $PAISMAP[90] = 'TR';
        $PAISMAP[993] = 'TM';
        $PAISMAP[688] = 'TV';
        $PAISMAP[256] = 'UG';
        $PAISMAP[380] = 'UA';
        $PAISMAP[971] = 'AE';
        $PAISMAP[44] = 'GB';
        $PAISMAP[255] = 'TZ';
        $PAISMAP[0] = 'UM';
        $PAISMAP[1] = 'US';
        $PAISMAP[340] = 'VI';
        $PAISMAP[598] = 'UY';
        $PAISMAP[998] = 'UZ';
        $PAISMAP[678] = 'VU';
        $PAISMAP[379] = 'VA';
        $PAISMAP[58] = 'VE';
        $PAISMAP[84] = 'VN';
        $PAISMAP[212] = 'EH';
        $PAISMAP[967] = 'YE';
        $PAISMAP[260] = 'ZM';
        $PAISMAP[263] = 'ZW';
        $PAISMAP[79] = 'RU';

        return $PAISMAP[$num] ?? "";
    }
}
