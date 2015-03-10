<?php
extract($GLOBALS);
include_once($w_dir_volta.'funcoes.php');
/**
* class bacen
*
* { Description :- 
*    Recupera informações dos web services do Banco Central do Brasil
* }
*/

class ws_getBacen {
  /* 
    This class retrieves data from BACEN Web Services, specifically of the following operations:

       GetValoresSeriesXML(<array of integers>, <string with start date>, <string with final date>):
       => Returns a XML string with exchange rate of the currency or currencies in a given period.

       getUltimoValorXML(<integer>):
       => Returns a XML string with the latest exchange rate of one currency (only one).

       getValor(<integer>,<string with desired date): 
       => Returns a plain text string with the exchange rate of the currency on the specified date.

    For a complete list of operations, so as details of request and response, 
    copy & paste value of $_ws_address (defined below) in a browser.

    The exchange rates returned are always the BRL (R$) multiplication factor to determine the equivalent amount 
    in USD (US$), EUR (€) or any other currency. For exemple:
       (a) You have US$ 1,000 and need to convert it to BRL: amount_BRL = truncate(amount_USD * exchange_rate, 2)
       (b> You have R$ 1,000  and need to convert it to USD: amount_USD = truncate(amount_BRL / exchange_rate, 2)

    To find the code of any historical serie, acess https://www3.bcb.gov.br/sgspub. There are specific historical 
    series for ask (BACEN selling) and bid (BACEN buying) exchange rates.
  */
  
  // Base URL for BACEN web services
  var $_ws_address = "https://www3.bcb.gov.br/sgspub/JSP/sgsgeral/FachadaWSSGS.wsdl";

  /**
  * Method bacen::Get ValoresSeriesXML()
  *
  * { Description :- 
  *    Returns a XML string with exchange rate of the currency or currencies in a given period.
  * 
  *   Parameters :-
  *    $_series: an array that represents historical series. GetValoresSeriesXML can retrieve data 
  *              from multiple series, defined as an simple array of integers: 
  *              $_series = array(1, 10850, ...)
  *    $_start:  a string that represents a date in dd/mm/yyyy format. E.g., 17/01/2015, 02/07/2015.
  *    $_end:    a string that represents a date in dd/mm/yyyy format >= $_start. E.g., 17/01/2015, 02/07/2015.
  * }
  */

  function GetValoresSeriesXML($_series, $_start, $_end) {

    ini_set("soap.wsdl_cache_enabled", "0");
    $_client = new SoapClient($this->_ws_address, array('trace'=>1));
    $operation = "GetValoresSeriesXML";
      
    try {
      $Result = $_client->$operation($_series, $_start, $_end);
      if (isset($Result)) {
        $ResultXML = simplexml_load_string($Result);
        foreach($ResultXML->SERIE as $serie) {
          $Code = intval($serie->attributes()->{ID});
          foreach($serie->ITEM as $item) {
            // Day and month returned by BACEN WS don't have leading zeroes (e.g., 21/1/2015).
            // The result must be normalized to the format dd/mm/yyyy (i.e., 21/01/2015).
            $Date = toDate(normalizeDate(strval($item->DATA)));
            $Rate = floatval($item->VALOR);
            $_result[$Date][$Code] = $Rate;
          }
        }
      } else {
        // Portuguese error message.
        $this->setError($_client, SoapFault, 'Falha ao abrir XML do BACEN.');
        // Equivalent English error message.
        //$this->setError($_client, SoapFault, 'Fail to open BACEN XML.');
        $_result = array(); 
      }
    } catch(SoapFault $SoapFault) {
      $this->setError($_client, $SoapFault, null);
      $_result = array(); 
    }
    Return $_result;
  }

  /**
  * Method bacen::getUltimoValorXML()
  *
  * { Description :- 
  *    Returns a XML string with the latest exchange rate of one currency (only one).
  * 
  *   Parameters :-
  *    $_serie:  an integer that represents a specific historical serie. E.g., 1 or 10350 ...
  * }
  */

  function getUltimoValorXML($_serie) {

    ini_set("soap.wsdl_cache_enabled", "0");
    $_client = new SoapClient($this->_ws_address, array('trace'=>1));
    $operation = "getUltimoValorXML";
      
    try {
      $Result = $_client->$operation($_serie);
      if (isset($Result)) {
        $ResultXML = simplexml_load_string($Result);
        // Cascaded call to UTF8_DECODE to decode default charset of BACEN to ISO-8859-1.
        // If your charset is already set to UTF-8, you must adjust next line.
        $SerieName = utf8_decode(utf8_decode($ResultXML->SERIE->NOME));
        
        $Rate = $ResultXML->SERIE->VALOR;

        $Day = $ResultXML->SERIE->DATA->DIA;
        $Month = $ResultXML->SERIE->DATA->MES;
        $Year = $ResultXML->SERIE->DATA->ANO;
        $Date = normalizeDate("$Day/$Month/$Year");
                
        // Array items to be returned.
        $w_array['name'] = $SerieName;
        $w_array['date'] = toDate($Date);
        $w_array['rate'] = $Rate;
        
        $_result = $w_array;
      } else {
        // Portuguese error message.
        $this->setError($_client, SoapFault, 'Falha ao abrir XML do BACEN.');
        // Equivalent English error message.
        //$this->setError($_client, SoapFault, 'Fail to open BACEN XML.');
        $_result = array();
      }
    } catch(SoapFault $SoapFault) {
      $this->setError($_client, $SoapFault, null);
      $_result = array(); 
    } 
    Return $_result;
  }

  /**
  * Method bacen::getValor()
  *
  * { Description :- 
  *    Returns a plain text string with the exchange rate of the currency on the specified date.
  * 
  *   Parameters :-
  *    $_serie:  an integer that represents a specific historical serie. E.g., 1 or 10350 ...
  *    $_date:   a string that represents a date in dd/mm/yyyy format. E.g., 17/01/2015, 02/07/2015.
  * }
  */
  function getValor($_serie, $_date) {

    ini_set("soap.wsdl_cache_enabled", "0");
    $_client = new SoapClient($this->_ws_address, array('trace'=>1));
    $operation = "getValor";
      
    try {
      $Result = $_client->$operation($_serie, $_date);
      if (isset($Result)) {
        $_result = $Result;
      } else {
        // Portuguese error message.
        $this->setError($_client, SoapFault, 'Falha ao recuperar taxa de câmbio do BACEN.');
        // Equivalent English error message.
        //$this->setError($_client, SoapFault, 'Fail to retrive exchange rate from BACEN Web Service.');
        $_result = array();
      }
    } catch(SoapFault $SoapFault) {
      $this->setError($_client, $SoapFault, null);
      $_result = array(); 
    }
    Return $_result;
  }

  /**
  * Method bacen::setError()
  *
  * { Description :- 
  *    Sets data of the erocurred while calling the web service
  * }
  */

  function setError($_conn, $_soapFault, $_message) { 
    $this->error['message'] = ((isset($_message)) ? $_message : $_soapFault -> getMessage());
    $this->error['request'] = str_replace("\n",'<br>',htmlentities(str_ireplace('><', ">\n<",$_conn->__getLastRequest())));
    $this->error['response'] = str_replace("\n",'<br>',htmlentities(str_ireplace('><', ">\n<",$_conn->__getLastResponse())));
  }

  /**
  * Method bacen::getError()
  *
  * { Description :- 
  *    Returns the error message ocurred while calling the web service
  * }
  */

  function getError() { return $this->error; }
}