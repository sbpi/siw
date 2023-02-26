<?php
session_start();
$w_dir_volta = substr(realpath(dirname(__FILE__)),0,strrpos(realpath(dirname(__FILE__)),'/')).'/';
include_once($w_dir_volta.'constants.inc');

// =========================================================================
//  cotacoes_bc_auto.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papad�polis
// Descricao: Recupera cota��es de pagamentos a partir de web service do BACEN
// Mail     : alex@sbpi.com.br
// Criacao  : 24.02.2023, 11:03
// Versao   : 1.0.0.0
// Local    : Bras�lia - DF
// -------------------------------------------------------------------------
// 
// Par�metros recebidos de forma posicional:
//    Primeiro: chave de SIW_CLIENTE. Indica o cliente que est� executando a rotina.
//    Segundo : banco de dados em uso. [1] Oracle 9 em diante; [2] MS-SQL Server; [3] Oracle 8; [4] PostgreSql
//    Terceiro: esquema do banco a ser usado.
//    Quarto  : chave de CO_PESSOA. Indica o usu�rio respons�vel pela opera��o.
//    
// Observa��es: 
//    a) O segundo par�metro deve corresponder ao campo hidden "p_dbms" da tela de autentica��o web (verificar c�digo fonte dessa tela)
//    b) O terceiro par�metro deve corresponder ao valor da vari�vel "<banco>_DATABASE_NAME" do arquivo "classes/db/db_constants.php".
//       Existe uma vari�vel "<banco>_DATABASE_NAME" para cada banco de dados dispon�vel. Verificar a que corresponde com o banco em uso.
//

//L� os par�metros de chamada
$w_cliente = $argv[1];
$w_dbms    = $argv[2];
$w_esquema = $argv[3];
$w_usuario = $argv[4];

// Verifica se os par�metros de chamada est�o corretos
if (!isset($w_cliente)) {
  echo 'ERRO: � necess�rio informar o c�digo do cliente como primeiro par�metro de chamada.'.$crlf;
  exit();
};
if (!isset($w_dbms)) {
  echo 'ERRO: � necess�rio informar o banco de dados como segundo par�metro de chamada.'.$crlf;
  exit();
};

// Se foi disparado da interface Web, guarda os dados para uso futuro
if (!isset($_SESSION['P_CLIENTE'])) $w_cliente_old  = $_SESSION['P_CLIENTE'];
if (!isset($_SESSION['DBMS']))      $w_dbms_old     = $_SESSION['DBMS'];

// Configura par�metros de funcionamento
$_SESSION['P_CLIENTE'] = $w_cliente;
$_SESSION['DBMS']      = $w_dbms;

// Includes t�m que ficar depois da defini��o das vari�veis de sess�o
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_exec.php');
include_once($w_dir_volta.'classes/sp/dml_CotacaoBacen.php');
include_once($w_dir_volta.'classes/sp/db_getUserData.php');

// Abre conex�o como banco de dados
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

// Recupera informa��es do usu�rio respons�vel pela grava��o dos dados
$sql = new DB_GetUserData; $RS = $sql->getInstanceOf($dbms, $_SESSION['P_CLIENTE'], 'suporte');
$_SESSION['USERNAME']        = f($RS,'USERNAME');
$_SESSION['SQ_PESSOA']       = f($RS,'SQ_PESSOA');
$_SESSION['NOME']            = f($RS,'NOME');
$_SESSION['EMAIL']           = f($RS,'EMAIL');
$_SESSION['NOME_RESUMIDO']   = f($RS,'NOME_RESUMIDO');
$_SESSION['LOTACAO']         = f($RS,'SQ_UNIDADE');
$_SESSION['LOCALIZACAO']     = f($RS,'SQ_LOCALIZACAO');
$_SESSION['INTERNO']         = f($RS,'INTERNO');
$_SESSION['LOGON']           = 'Sim';
$_SESSION['ENDERECO']        = f($RS,'SQ_PESSOA_ENDERECO');
$_SESSION['ANO']             = Date('Y');
$_SESSION['USUARIO']         = ((nvl(f($RS,'sexo'),'M')=='M') ? 'Usu�rio' : 'Usu�ria');
      
Principal();

FechaSessao($dbms);

// =========================================================================
// Rotina de tratamento do envio
// -------------------------------------------------------------------------
function Principal() {
  extract($GLOBALS, EXTR_PREFIX_SAME, 'strchema');

  // Identifica cota��es que faltam no banco de dados => Crit�rio: pagamentos conclu�dos em datas sem cota��o 
  $params=array("p_cliente " =>array($w_cliente,  B_NUMERIC,     32),
                "p_result"   =>array(null,        B_CURSOR,      -1)
               );
  $sql = new db_exec; $par = $sql->normalize($params); extract($par,EXTR_OVERWRITE);

  $SQL = "select distinct data, to_char(data,'MM-DD-YYYY') data_formatada$crlf" .
         "  from (select d.quitacao data$crlf" .
         "          from siw_solicitacao                a$crlf" .
         "               inner   join siw_menu          b on (a.sq_menu            = b.sq_menu)$crlf" .
         "               inner   join siw_tramite       c on (a.sq_siw_tramite     = c.sq_siw_tramite and c.sigla = 'AT')$crlf" .
         "               inner   join fn_lancamento     d on (a.sq_siw_solicitacao = d.sq_siw_solicitacao)$crlf" .
         "                 left  join co_moeda_cotacao  f on (a.sq_moeda           = f.sq_moeda and$crlf" .
         "                                                    d.quitacao           = f.data$crlf" .
         "                                                   )$crlf" .
         "         where b.sq_pessoa        = $w_cliente$crlf" .
         "           and f.sq_moeda_cotacao is null$crlf" .
         "           and a.sq_moeda         <> 34$crlf" .
         "           and 6                  > to_char(d.quitacao, 'd') -- BACEN n�o tem cota��o s�bados e domingos$crlf" .
         "        UNION$crlf" .
         "        select d.quitacao data$crlf" .
         "          from siw_solicitacao                a$crlf" .
         "               inner   join siw_menu          b on (a.sq_menu            = b.sq_menu)$crlf" .
         "               inner   join siw_tramite       c on (a.sq_siw_tramite     = c.sq_siw_tramite and c.sigla = 'AT')$crlf" .
         "               inner   join fn_lancamento     d on (a.sq_siw_solicitacao = d.sq_siw_solicitacao)$crlf" .
         "                 inner join co_pessoa_conta   e on (d.sq_pessoa_conta    = e.sq_pessoa_conta and e.sq_moeda <> 34)$crlf" .
         "                 left  join co_moeda_cotacao  f on (e.sq_moeda           = f.sq_moeda and$crlf" .
         "                                                    d.quitacao           = f.data$crlf" .
         "                                                   )$crlf" .
         "         where b.sq_pessoa        = $w_cliente$crlf" .
         "           and f.sq_moeda_cotacao is null$crlf" .
         "           and a.sq_moeda         <> 34$crlf" .
         "           and 6                  > to_char(d.quitacao, 'd') -- BACEN n�o tem cota��o s�bados e domingos$crlf" .
         "       )$crlf" .
         "order by 1$crlf";
  $l_rs = $sql->getInstanceOf($dbms, $SQL, $params);

  // Configura caminhos para recupera��o de arquivos de configura��o e arquivos de dados
  $w_caminho = $conFilePhysical.$w_cliente.'/bacen_log';
  $w_arquivo = $w_caminho.'/'.$w_cliente.'_'.date(Ymd.'_'.Gis.'_'.time()).'.log';

  // Cria o diret�rio, caso n�o exista
  if (!file_exists($w_caminho)) {
    mkdir($w_caminho);
  } 

  // Abre o arquivo de log
  $w_log = @fopen($w_arquivo, 'w');

  if (count($l_rs)) {
    
    fwrite($w_log, '### COTA��ES IMPORTADAS'.$crlf);
    
    $sp = new dml_CotacaoBacen;
    $url_base = "https://olinda.bcb.gov.br/olinda/servico/PTAX/versao/v1/odata/CotacaoMoedaDia(moeda=@moeda,dataCotacao=@dataCotacao)?%40moeda='%MOEDA%'&%40dataCotacao='%DATA%'&%24format=json&%24filter=tipoBoletim%20eq%20'Fechamento%20PTAX'";
    foreach($l_rs as $row) {
      $data = formataDataEdicao(f($row,'data'));
      $url_data = str_replace('%DATA%',f($row,'data_formatada'),$url_base);
      $url_dolar = str_replace('%MOEDA%','USD',$url_data);
      $url_euro = str_replace('%MOEDA%','EUR',$url_data);

      // Recupera e grava cota��es do D�lar
      $valores = getValuesBacen($url_dolar);
      $moeda = 70;
      $valorCompra = formatNumber(nvl($valores["cotacaoCompra"],'0.00'),4);
      $valorVenda = formatNumber(nvl($valores["cotacaoVenda"],'0.00'),4);
      
      //echo '['.$data.'] [DOLAR] compra: ['.$valorCompra.'] venda ['.$valorVenda.']'.$crlf;
      $sp = new dml_CotacaoBacen; $sp->getInstanceOf($dbms, $w_cliente, $moeda, $data, 'C', $valorCompra);
      $sp = new dml_CotacaoBacen; $sp->getInstanceOf($dbms, $w_cliente, $moeda, $data, 'V', $valorVenda);
      fwrite($w_log, '['.$data.'] [DOLAR] compra: ['.$valorCompra.'] venda ['.$valorVenda.']'.$crlf);

      // Recupera e grava cota��es do Euro
      $valores = getValuesBacen($url_euro);
      $moeda = 218;
      $valorCompra = formatNumber(nvl($valores["cotacaoCompra"],'0.00'),4);
      $valorVenda = formatNumber(nvl($valores["cotacaoVenda"],'0.00'),4);
      
      //echo '['.$data.'] [EURO ] compra: ['.$valorCompra.'] venda ['.$valorVenda.']'.$crlf;
      $sp = new dml_CotacaoBacen; $sp->getInstanceOf($dbms, $w_cliente, $moeda, $data, 'C', $valorCompra);
      $sp = new dml_CotacaoBacen; $sp->getInstanceOf($dbms, $w_cliente, $moeda, $data, 'V', $valorVenda);
      fwrite($w_log, '['.$data.'] [EURO ] compra: ['.$valorCompra.'] venda ['.$valorVenda.']'.$crlf);
    }


    // Vincular as cota��es com os lan�amentos financeiros

  } else {
    fwrite($w_log, 'Nenhuma cota��o importada'.$crlf);
  }

  // Fecha o arquivo de log
  @fclose($w_log);
  @closedir($w_caminho);
} 


// =========================================================================
// Rotina de dele��o de arquivos em disco
// -------------------------------------------------------------------------
function getValuesBacen($url) {
      
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $res_curl = curl_exec($ch);
  
  if(curl_error($ch)) {
    echo curl_error($ch);
  } else {
    $resultado = json_decode($res_curl, true);
    $valores = $resultado["value"][0];
  }
  
  curl_close($ch);

	return $valores;
}

?>
