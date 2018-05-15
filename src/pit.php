<?php
	namespace Pit;
	class Pit
	{
		function __construct()
		{
			$this->cc = new cURL();
			$this->cc->setReferer('http://www.pit.gob.pe/pit2007/Consultas.aspx');
			//$this->cc->setCookiFileLocation( __DIR__ . '/cookie.txt' );
		}
		function check( $placa )
		{
			$data = array(
				"__VIEWSTATE" 			=> "/wEPDwUKLTY1NDY4NDk1OA9kFgICAw9kFgICBw9kFgICAQ88KwANAGQYAQULZ3JkQ2FwdHVyYXMPZ2RFXXcr3cxQMfNI8PHVyIYLs/5Tcg==",
				"__EVENTVALIDATION" 	=> "/wEWAwKs66eGDgL31tvdDAK674/pDJxRMfUWqoqRTUA94MQ+oNQYYOkL",
				"txtPlaca" 				=> $placa,
				"btnBuscar" 			=> "Buscar"
			);
			
			$url = "http://www.pit.gob.pe/pit2007/capturas.aspx";
			$response = $this->cc->send( $url, $data );
			if( $this->cc->getHttpStatus() == 200 && $response != "")
			{
				libxml_use_internal_errors(true);

				$doc = new \DOMDocument();
				$doc->strictErrorChecking = FALSE;
				$doc->loadHTML(mb_convert_encoding($response, 'HTML-ENTITIES',  'UTF-8'));
				libxml_use_internal_errors(false);

				$xml = simplexml_import_dom($doc);
				$message = $xml->xpath("//span[@id='lblMensajeVacio']");
				$details = $xml->xpath("//div[@id='divCapturas']/div/table/tr[@class='celda3']");
				
				$requisition = false;
				$list = array();
				if( count($details) > 0 )
				{
					$requisition = true;
					foreach($details as $obj)
					{
						$list[] = array( (string)$obj->td[0]->span, (string)$obj->td[1]->span, (string)$obj->td[2]->span, (string)$obj->td[3]->span, (string)$obj->td[4]->span, (string)$obj->td[5]->span );
					}
				}
				else
				{
					$rpt = array(
						"success" 		=> true,
						"requisition" 	=> $requisition,
						"message" 		=> (string)$message[0]
					);
					return $rpt;
				}
				
				$rpt = array(
					"success" 		=> true,
					"requisition" 	=> $requisition,
					"message" 		=> (string)$message[0],
					"details" 		=> $list,
				);
				return $rpt;
			}
			$rpt = array(
				"success" 		=> false,
				"message" 		=> "failed connection"
			);
			return $rpt;
		}
	}
?>
