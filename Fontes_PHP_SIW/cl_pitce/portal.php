<?php
header('Expires: '.-1500);

$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getUserData.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getDeskTop_Recurso.php');
include_once($w_dir_volta.'classes/sp/db_exec.php');
include_once($w_dir_volta.'classes/sp/db_getDeskTop.php');
include_once($w_dir_volta.'classes/sp/db_getAlerta.php');
include_once($w_dir_volta.'classes/sp/db_getUserList.php');
include_once($w_dir_volta.'classes/sp/db_getUorgList.php');
include_once($w_dir_volta.'classes/sp/db_getUorgAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicResultado.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoPrograma.php');
include_once($w_dir_volta.'funcoes/selecaoTipoEventoCheck.php');
include_once($w_dir_volta.'funcoes/selecaoMes.php');
include_once($w_dir_volta.'funcoes/selecaoTipoArquivoTab.php');
include_once($w_dir_volta.'visualalerta.php');
// Garante que a sess�o ser� reinicializada.
session_start();

if ($_SESSION['DBMS']=='' || isset($_REQUEST['p_dbms'])) {
  $_SESSION['DBMS']  = $_REQUEST['p_dbms']; 
  $_SESSION['LOGON'] = 'Sim';
  $_SESSION['P_CLIENTE'] = $_REQUEST['p_cliente'];
}

/*if ($_SESSION['DBMS']=='' || $_SESSION['LOGON']=='' || $_SESSION['P_CLIENTE']=='') {
  die('Par�metros de chamada inv�lidos.');
}*/

// Declara��o de vari�veis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Recupera informa��es a serem usadas na montagem das telas para o usu�rio
$RS = DB_GetUserData::getInstanceOf($dbms, $_SESSION['P_CLIENTE'], '000.000.001-91');
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


// =========================================================================
//  portal.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papad�polis
// Descricao: Gerencia a atualiza��o das tabelas do sistema
// Mail     : alex@sbpi.com.br
// Criacao  : 24/03/2003 16:55
// Versao   : 1.0.0.0
// Local    : Bras�lia - DF
// -------------------------------------------------------------------------
//
// Par�metros recebidos:
//    R (refer�ncia) = usado na rotina de grava��o, com conte�do igual ao par�metro T
//    O (opera��o)   = I   : Inclus�o
//                   = A   : Altera��o
//                   = C   : Cancelamento
//                   = E   : Exclus�o
//                   = L   : Listagem
//                   = P   : Pesquisa
//                   = D   : Detalhes
//                   = N   : Nova solicita��o de envio
// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Carrega vari�veis locais com os dados dos par�metros recebidos
$par        = upper($_REQUEST['par']);
$P1         = $_REQUEST['P1'];
$P2         = $_REQUEST['P2'];
$P3         = $_REQUEST['P3'];
$P4         = $_REQUEST['P4'];
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);

$p_agenda      = $_REQUEST['p_agenda'];
$p_programa    = $_REQUEST['p_programa'];
$p_projeto     = $_REQUEST['p_projeto'];
$p_unidade     = $_REQUEST['p_unidade'];
$p_projeto     = $_REQUEST['p_projeto'];
$p_texto       = $_REQUEST['p_texto'];
$p_tipo_evento = explodeArray($_REQUEST['p_tipo_evento']);
$p_ordena     = $_REQUEST['p_ordena'];
$p_descricao   = $_REQUEST['p_descricao'];
$p_situacao    = $_REQUEST['p_situacao'];

$w_assinatura   = upper($_REQUEST['w_assinatura']);
$w_pagina       = 'portal.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'cl_pitce/';

$w_cliente      = RetornaCliente();
$w_usuario      = RetornaUsuario();
$w_troca        = $_REQUEST['w_troca'];
$w_mesano      = $_REQUEST['w_mesano'];
$w_ano          = RetornaAno();

// Configura vari�veis para montagem do calend�rio
if (nvl($w_mes,'')=='') $w_mes = date('m',time());

if (nvl($w_mesano,'')=='') {
  $w_mesano = $w_mes.'/'.$w_ano;
} else {
  $w_mes = substr($w_mesano,0,2);
  $w_ano = substr($w_mesano,3);
}

$w_inicio  = first_day(toDate('01/'.substr(100+(intVal($w_mes)),1,2).'/'.$w_ano));
$w_fim     = last_day(toDate('01/'.substr(100+(intVal($w_mes)),1,2).'/'.$w_ano));

// Define visualiza��es dispon�veis para o usu�rio
$RS_Usuario = db_getPersonData::getInstanceOf($dbms,$w_cliente,$w_usuario,null,null);
  
// Identifica se o v�nculo do usu�rio � com a a Secretaria executiva
if (upper(f($RS_Usuario,'nome_vinculo'))=='SECRETARIA EXECUTIVA') {
  $w_usuario_se = true;
} else {
  $w_usuario_se = false;
}

$SQL = "select a.sq_plano ".$crlf. 
       "  from siw_solicitacao        a ".$crlf. 
       "       inner join siw_menu    b on (a.sq_menu = b.sq_menu and b.sq_pessoa = 14014) ".$crlf. 
       "       inner join siw_tramite d on (a.sq_siw_tramite = d.sq_siw_tramite and ".$crlf. 
       "                                    d.ativo          = 'S'".$crlf. 
       "                                   ) ".$crlf. 
       "  where a.codigo_interno='PDE' ";
$RS = db_exec::getInstanceOf($dbms,$SQL,$recordcount);
foreach($RS as $row) { $RS = $row; break; }
$p_plano = f($RS,'sq_plano');

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Exibe calend�rio da PDP
// -------------------------------------------------------------------------
function Calendario() {
  extract($GLOBALS);
  
  if ($w_troca>'' && $O!='E') {
    $w_acontecimento = $_REQUEST['w_acontecimento'];
    $w_programa      = $_REQUEST['w_programa'];
    $w_projeto       = $_REQUEST['w_projeto'];
    $w_secretaria    = $_REQUEST['w_secretaria'];
    $w_coordenacao   = $_REQUEST['w_coordenacao'];
    $w_comite        = $_REQUEST['w_comite'];
    $w_cc            = $_REQUEST['w_cc'];
    $w_asunto        = $_REQUEST['w_asunto'];
    $w_local         = $_REQUEST['w_local'];
    $w_data_inicio   = $_REQUEST['w_data_inicio'];
    $w_data_termino  = $_REQUEST['w_data_termino'];
    $w_hora_inicio   = $_REQUEST['w_hora_inicio'];
    $w_hora_termino  = $_REQUEST['w_hora_termino'];
    $w_mensagem      = $_REQUEST['w_mensagem'];
  }  
  
  // Recupera os tipos de evento desejados e monta string
  $RS_Projeto = db_getLinkData::getInstanceOf($dbms,$w_cliente,'EVCAD');
  $l_rs = db_getTipoEvento::getInstanceOf($dbms,$w_cliente,f($RS_Projeto,'sq_menu'),null,null,null,'S', 'REGISTROS');
  $l_rs = SortArray($l_rs,'nm_servico','asc','ordem','asc','nome','asc');
  $p_tipo_evento = '';
  foreach($l_rs as $row)  $p_tipo_evento.=','.f($row,'chave');
  $p_tipo_evento = substr($p_tipo_evento,1);
  
  $RS_Resultado = db_getSolicResultado :: getInstanceOf($dbms,$w_cliente,$p_programa,$p_projeto,$p_unidade,$p_solicitante,$p_texto,formataDataEdicao($w_inicio),formataDataEdicao($w_fim),null,null,null,null,null,null,null,null,null,$p_agenda,$p_tipo_evento,'CALEND');
  if ($p_ordena>'') { 
    $lista = explode(',',str_replace(' ',',',$p_ordena));
    $RS_Resultado = SortArray($RS_Resultado,$lista[0],$lista[1],'mes_ano','desc','cd_programa','asc', 'cd_projeto','asc','titulo','asc');
  } else {
    $RS_Resultado = SortArray($RS_Resultado,'mes_ano','desc','cd_programa','asc', 'cd_projeto','asc','titulo','asc');
  }
  if ($O=='L') {
    ShowHTML('<table width="100%">');
    ShowHTML('<tr>');
    ShowHTML('  <td>Per�odo de busca: <b>'.formataDataEdicao($w_inicio).'</b> e <b>'.formataDataEdicao($w_fim).'</b></td>');
    ShowHTML('  <td align="right">Resultados: '.count($RS_Resultado).'</td></tr>');
    ShowHTML('<tr><td align="center" colspan=2>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td nowrap><b>&nbsp;Tipo&nbsp;</td>');
    ShowHTML('          <td nowrap><b>&nbsp;In�cio&nbsp;</td>');
    ShowHTML('          <td nowrap><b>&nbsp;Fim&nbsp;</td>');
    ShowHTML('          <td nowrap><b>&nbsp;Programa&nbsp;</td>');
    ShowHTML('          <td nowrap><b>&nbsp;Evento&nbsp;</td>');
    ShowHTML('          <td nowrap><b>&nbsp;Descri��o&nbsp;</td>');
    ShowHTML('          <td nowrap><b>&nbsp;Local&nbsp;</td>');
    ShowHTML('        </tr>');
    $w_cor = $conTrBgColor;
    if (count($RS_Resultado) == 0) {
      ShowHTML('    <tr align="center"><td colspan="8">Nenhum resultado encontrado para os crit�rios informados.</td>');
    } else {
      foreach ($RS_Resultado as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('    <tr valign="top" bgColor="' . $w_cor . '">');
        ShowHTML('      <td>'.f($row, 'nm_tipo_evento').'</td>');
        ShowHTML('      <td align="center">' . Date('d/m/Y', Nvl(f($row, 'inicio_real'), f($row, 'inicio_previsto'))) . '</td>');
        ShowHTML('      <td align="center">' . Date('d/m/Y', Nvl(f($row, 'fim_real'), f($row, 'fim_previsto'))) . '</td>');
        ShowHTML('      <td>' . Nvl(f($row, 'nm_projeto'), '---') . '</td>');
        ShowHTML('      <td>'.f($row, 'titulo'));
        ShowHTML('      <td>'.f($row, 'descricao'));
        ShowHTML('      <td>'.nvl(f($row, 'motivo_insatisfacao'),'---').' </td>');
        ShowHTML('    </tr>');
      }
      ShowHTML('  </table>');
      ShowHTML('<tr><td>&nbsp;</td></tr>');
    }
    ShowHTML('</table>');
  } else {
    if (count($RS_Resultado) == 0) {
      ShowHTML('    Nenhum evento cadastrado para o m�s indicado!');
    } else {
      ShowHTML('<dl>');
      foreach ($RS_Resultado as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <dt>>&nbsp;' . Date('d/m/Y', Nvl(f($row, 'inicio_real'), f($row, 'inicio_previsto'))) . '</td>');
        ShowHTML('      <dd>'.f($row, 'titulo'));
      }
      ShowHTML('</dl>');
    }
  }
}

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);

  switch ($par) {
  case 'CALENDARIO':    Calendario();   break;
  default:
    Cabecalho();
    BodyOpen('onLoad=this.focus();');
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    Estrutura_Texto_Abre();
    ShowHTML('<center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center>');
    Estrutura_Texto_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
  }
}
?>