<?
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getDeskTop_Recurso.php');
include_once($w_dir_volta.'classes/sp/db_exec.php');
include_once($w_dir_volta.'classes/sp/db_getDeskTop.php');
include_once($w_dir_volta.'classes/sp/db_getAlerta.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicEtapa.php');
include_once($w_dir_volta.'classes/sp/db_getEtapaAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicResultado.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoPrograma.php');
include_once($w_dir_volta.'visualalerta.php');
// =========================================================================
//  trabalho.php
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

// Declara��o de vari�veis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega vari�veis locais com os dados dos par�metros recebidos
$par        = strtoupper($_REQUEST['par']);
$P1         = $_REQUEST['P1'];
$P2         = $_REQUEST['P2'];
$P3         = $_REQUEST['P3'];
$P4         = $_REQUEST['P4'];
$TP         = $_REQUEST['TP'];
$SG         = strtoupper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = strtoupper($_REQUEST['O']);

$p_agenda      = $_REQUEST['p_agenda'];
$p_evento      = $_REQUEST['p_evento'];
$p_reuniao     = $_REQUEST['p_reuniao'];
$p_programa    = $_REQUEST['p_programa'];
$p_unidade     = $_REQUEST['p_unidade'];
$p_projeto     = $_REQUEST['p_projeto'];
$p_texto       = $_REQUEST['p_texto'];
$p_ordena      = strtolower($_REQUEST['p_ordena']);

$w_assinatura   = strtoupper($_REQUEST['w_assinatura']);
$w_pagina       = 'trabalho.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'cl_pitce/';

$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_ano      = 2008;//RetornaAno();
$w_mes      = $_REQUEST['w_mes'];

// Configura vari�veis para montagem do calend�rio
if (nvl($w_mes,'')=='') $w_mes = date('m',time());
$w_inicio  = first_day(toDate('01/'.substr(100+(intVal($w_mes)-1),1,2).'/'.$w_ano));
$w_fim     = last_day(toDate('01/'.substr(100+(intVal($w_mes)+1),1,2).'/'.$w_ano));
$w_mes1    = substr(100+intVal($w_mes)-1,1,2);
$w_mes3    = substr(100+intVal($w_mes)+1,1,2);
$w_ano1    = $w_ano;
$w_ano3    = $w_ano;
// Ajusta a mudan�a de ano
if ($w_mes1=='00') { $w_mes1 = '12'; $w_ano1 = $w_ano-1; }
if ($w_mes3=='13') { $w_mes3 = '01'; $w_ano3 = $w_ano+1; }

if ($O=='') $O='L';

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclus�o'; break;
  case 'A': $w_TP=$TP.' - Altera��o'; break;
  case 'E': $w_TP=$TP.' - Exclus�o'; break;
  case 'V': $w_TP=$TP.' - Envio'; break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  default : $w_TP=$TP.' - Listagem'; 
}

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Controle da mesa de trabalho
// -------------------------------------------------------------------------
function Mesa() {
  extract($GLOBALS);

  // Recupera os dados do cliente
  $RS_Cliente = db_getCustomerData::getInstanceOf($dbms,$w_cliente);

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.';">');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('<style>');
  ShowHTML('#menu_superior{);');
  ShowHTML('   float:right;');
  ShowHTML(' }');
  ShowHTML('#calendario{');
  ShowHTML('  cursor:pointer;');
  ShowHTML('  width: 128px;');
  ShowHTML('  height: 128px;');
  ShowHTML('  background:url('.$w_dir.'calendario.gif) no-repeat;');
  ShowHTML('}');
  ShowHTML('#resultados{');
  ShowHTML('  cursor:pointer;');
  ShowHTML('  width: 128px;');
  ShowHTML('  height: 128px;');
  ShowHTML('  background-image:url('.$w_dir.'resultados.gif);');
  ShowHTML('}');
  ShowHTML('#download{');
  ShowHTML('  cursor:pointer;');
  ShowHTML('  width: 128px;');
  ShowHTML('  height: 128px;');
  ShowHTML('  background-image:url('.$w_dir.'download.gif);');
  ShowHTML('}');
  
  ShowHTML('</style>');
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=this.focus();');
  
  ShowHTML('<table border="0" width="100%">');
  ShowHTML('<tr><td><b><FONT COLOR="#000000"><font size=2>'.$w_TP.'</font></b>');
  ShowHTML('    <td align="right">');

  // Se o geo-referenciamento estiver habilitado para o cliente, exibe link para acesso � visualiza��o
  if (f($RS_Cliente,'georeferencia')=='S') {
    ShowHTML('      <a href="mod_gr/exibe.php?par=inicial&O=L&TP='.$TP.' - Geo-referenciamento" title="Clique para visualizar os mapas geo-referenciados." target="_blank"><img src="'.$conImgGeo.'" border=0></a></font></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
  }

  if ($_SESSION['DBMS']!=5) {
    // Exibe, se necess�rio, sinalizador para alerta
    $RS = db_getAlerta::getInstanceOf($dbms, $w_cliente, $w_usuario, 'SOLICGERAL', 'N', null);
    if (count($RS)>0) {
      $w_sinal = $conImgAlLow;
      $w_msg   = 'Clique para ver alertas de atraso e proximidade da data de conclus�o.';
      foreach($RS as $row) {
        if ($w_usuario==f($row,'solicitante')) {
          $w_sinal = $conImgAlMed;
          $w_msg   = 'H� alertas nos quais sua voc� � o respons�vel ou o solicitante. Clique para v�-los.';
        }
        if ($w_usuario==nvl(f($row,'sq_exec'),f($row,'solicitante')))  {
          $w_sinal = $conImgAlHigh;
          $w_msg   = 'H� alertas nos quais sua interven��o � necess�ria. Clique para v�-los.';
          break;
        }
      }
      ShowHTML('      <a href="'.$w_pagina.'alerta&O=L&TP='.$TP.' - Alertas" title="'.$w_msg.'"><img src="'.$w_sinal.'" border=0></a></font></b>');
    }
  }

  ShowHTML('<tr><td colspan=2><hr>');
  ShowHTML('</table>');
  ShowHTML('<center>');
  if ($O=="L") {
    $w_pde   = 0;
    $w_pns   = 0;
    $w_pne   = 0;
    $w_pne1  = 0;
    $w_pne2  = 0;
    $w_pne3  = 0;
    $w_todos = 0;
    $SQL = "select b.sq_solic_pai, b.sq_siw_solicitacao, b.codigo_interno, b.sq_plano, count(a.sq_siw_solicitacao) as qtd from siw_solicitacao a join siw_solicitacao b on (a.sq_solic_pai = b.sq_siw_solicitacao) inner join siw_menu c on (b.sq_menu = c.sq_menu and c.sigla = 'PEPROCAD' and c.sq_pessoa = ".$w_cliente.") group by b.sq_solic_pai, b.sq_siw_solicitacao, b.codigo_interno, b.sq_plano";
    $RS = db_exec::getInstanceOf($dbms,$SQL,$recordcount);
    foreach($RS as $row) { 
      switch (f($row,'codigo_interno')) {
      case 'PDE':  $w_plano = f($row,'sq_plano'); $c_pde  = f($row,'sq_siw_solicitacao'); $w_pde  = f($row,'qtd'); $w_todos += f($row,'qtd'); break;
      case 'PNS':  $c_pns  = f($row,'sq_siw_solicitacao'); $w_pns  = f($row,'qtd'); $w_todos += f($row,'qtd'); break;
      case 'PNE1': $c_pne = f($row,'sq_solic_pai'); $c_pne1 = f($row,'sq_siw_solicitacao'); $w_pne1 = f($row,'qtd'); $w_pne += f($row,'qtd'); $w_todos += f($row,'qtd'); break;
      case 'PNE2': $c_pne = f($row,'sq_solic_pai'); $c_pne2 = f($row,'sq_siw_solicitacao'); $w_pne2 = f($row,'qtd'); $w_pne += f($row,'qtd'); $w_todos += f($row,'qtd'); break;
      case 'PNE3': $c_pne = f($row,'sq_solic_pai'); $c_pne3 = f($row,'sq_siw_solicitacao'); $w_pne3 = f($row,'qtd'); $w_pne += f($row,'qtd'); $w_todos += f($row,'qtd'); break;
      }
    }
    ShowHTML('<div id="menu_superior">');
    ShowHTML('<a href="'.$w_dir.'resultados.php?par=inicial&TP='.$TP.' - Resultados&SG='.$SG.'" title="Consulta aos resultados da PDP."><div id="resultados"></div></a>');
    ShowHTML('<a href="'.$w_dir.$w_pagina.'calendario&TP='.$TP.' - Calend�rio&SG='.$SG.'" title="Consulta de agendas de a��o, eventos e reuni�es da PDP."><div id="calendario"></div></a>');
    ShowHTML('<a title="Estrutura de governan�a da PDP" href="'.LinkArquivo(null,$w_cliente,'governanca.pdf',null,null,null,'EMBED').'" target="download"><div id="download"></div></a>');
    ShowHTML('</div>');
    ShowHTML('<img name="pdp" src="'.$w_dir.'pdp.gif" width="611" height="402" border="0" id="pdp" usemap="#m_pdp" alt="" /><map name="m_pdp" id="m_pdp">');
    ShowHTML('<area shape="poly" coords="376,374,608,374,608,402,376,402,376,374" target="programa" href="'.$w_dir.'pe_relatorios.php?par=rel_executivo&O=L&p_plano='.$w_plano.'&p_legenda=S&p_projeto=S" target="PRG"" title="Agendas de a��o: '.$w_todos.'" />');
    ShowHTML('<area shape="poly" coords="258,248,261,240,269,237,593,237,600,240,603,248,603,253,600,260,593,263,269,263,261,260,258,253,258,248,258,248" target="programa" href="'.$w_dir.'pe_relatorios.php?par=rel_executivo&p_programa='.$c_pne.'&O=L&p_sinal=S&p_plano='.$w_plano.'&p_legenda=S&p_projeto=S" title="Agendas de a��o: '.$w_pne.'" />');
    ShowHTML('<area shape="poly" coords="409,79,410,76,412,73,421,71,597,71,606,73,608,76,609,79,609,110,608,113,606,116,597,118,421,118,412,116,410,113,409,110,409,79,409,79" target="arquivo" href="'.$w_dir.$w_pagina.'arquivos&p_codigo=CG-PDP - Docs&TP='.$TP.' - Documentos do Conselho Gestor da PDP" title="Documentos do Conselho Gestor da PDP" />');
    ShowHTML('<area shape="poly" coords="495,292,496,287,499,283,503,280,509,279,596,279,602,280,606,283,609,287,610,292,610,336,609,341,606,345,602,348,596,349,509,349,503,348,499,345,496,341,495,336,495,292,495,292" target="programa" href="'.$w_dir.'pe_relatorios.php?par=rel_executivo&p_plano='.$w_plano.'&p_programa='.$c_pne2.'&O=L&p_sinal=S&p_legenda=S&p_projeto=S" title="Agendas de a��o: '.$w_pne2.'" />');
    ShowHTML('<area shape="poly" coords="372,293,373,288,376,284,380,281,386,281,473,281,479,281,483,284,486,288,487,293,487,338,486,343,483,347,479,350,473,351,386,351,380,350,376,347,373,343,372,338,372,293,372,293" target="programa" href="'.$w_dir.'pe_relatorios.php?par=rel_executivo&p_plano='.$w_plano.'&p_programa='.$c_pne3.'&O=L&p_sinal=S&p_legenda=S&p_projeto=S" title="Agendas de a��o: '.$w_pne3.'" />');
    ShowHTML('<area shape="poly" coords="251,293,252,288,254,284,259,281,265,281,351,281,357,281,362,284,364,288,366,293,366,338,364,343,362,347,357,350,351,351,265,351,259,350,254,347,252,343,251,338,251,293,251,293" target="programa" href="'.$w_dir.'pe_relatorios.php?par=rel_executivo&p_plano='.$w_plano.'&p_programa='.$c_pne1.'&O=L&p_sinal=S&p_legenda=S&p_projeto=S" title="Agendas de a��o: '.$w_pne1.'" />');
    ShowHTML('<area shape="poly" coords="127,292,128,287,131,283,135,280,141,279,228,279,234,280,238,283,241,287,242,292,242,336,241,341,238,345,234,348,228,349,141,349,135,348,131,345,128,341,127,336,127,292,127,292" target="programa" href="'.$w_dir.'pe_relatorios.php?par=rel_executivo&p_plano='.$w_plano.'&p_programa='.$c_pde.'&O=L&p_sinal=S&p_legenda=S&p_projeto=S" title="Agendas de a��o: '.$w_pde.'" />');
    ShowHTML('<area shape="poly" coords="1,292,2,287,5,283,9,280,15,279,102,279,108,280,112,283,115,287,116,292,116,336,115,341,112,345,108,348,102,349,15,349,9,348,5,345,2,341,1,336,1,292,1,292" target="programa" href="'.$w_dir.'pe_relatorios.php?par=rel_executivo&p_programa='.$c_pns.'&O=L&p_sinal=S&p_plano='.$w_plano.'&p_legenda=S&p_projeto=S" target="PNS" title="Agendas de a��o: '.$w_pns.'" />');
    ShowHTML('<area shape="poly" coords="233,84,234,79,237,75,242,72,247,71,369,71,374,72,379,75,382,79,383,84,383,105,382,110,379,114,374,117,369,118,247,118,242,117,237,114,234,110,233,105,233,84,233,84" target="arquivo" href="'.$w_dir.$w_pagina.'arquivos&p_codigo=MDIC-PDP - Docs&TP='.$TP.' - Documentos do MDIC" title="Documentos do MDIC" />');
    ShowHTML('<area shape="poly" coords="233,154,234,149,237,145,242,142,247,141,369,141,374,142,379,145,382,149,383,154,383,175,382,180,379,184,374,187,369,188,247,188,242,187,237,184,234,180,233,175,233,154,233,154" target="arquivo" href="'.$w_dir.$w_pagina.'arquivos&p_codigo=SE-PDP - Docs&TP='.$TP.' - Documentos da Secretaria Executiva" title="Documentos da Secretaria Executiva da PDP" />');
    ShowHTML('<area shape="poly" coords="233,14,234,9,237,5,242,2,247,1,369,1,374,2,379,5,382,9,383,14,383,35,382,40,379,44,374,47,369,48,247,48,242,47,237,44,234,40,233,35,233,14,233,14" target="arquivo" href="'.$w_dir.$w_pagina.'arquivos&p_codigo=CNDI-PDP - Docs&TP='.$TP.' - Documentos do CNDI" title="Documentos do CNDI" />');
    ShowHTML('</map>');
    ShowHTML('<table border="0" width="100%">');
    ShowHTML('      <tr><td colspan=3><p>&nbsp;</p>');
  } else {
    ScriptOpen("JavaScript");
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  ShowHTML('</body>');
  ShowHTML('</html>');
  //Rodape();
}

// =========================================================================
// Exibe alertas de atraso e proximidade da data de conclusao
// -------------------------------------------------------------------------
function Alerta() {
  extract($GLOBALS);
  
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.';">');
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');
  ShowHTML('<table border="0" width="100%">');
  ShowHTML('<tr><td><b><FONT COLOR="#000000"><font size=2>'.$w_TP.'</font></b>');
  $RS_Volta = db_getLinkData::getInstanceOf($dbms,$w_cliente,'MESA');
  ShowHTML('  <td align="right"><a class="SS" href="'.$conRootSIW.f($RS_Volta,'link').'&P1='.f($RS_Volta,'p1').'&P2='.f($RS_Volta,'p2').'&P3='.f($RS_Volta,'p3').'&P4='.f($RS_Volta,'p4').'&TP=<img src='.f($RS_Volta,'imagem').' BORDER=0>'.f($RS_Volta,'nome').'&SG='.f($RS_Volta,'sigla').'" target="content">Voltar para '.f($RS_Volta,'nome').'</a>');
  ShowHTML('<tr><td colspan=2><hr>');
  ShowHTML('</table>');
  ShowHTML('<center>');
  ShowHTML('<table border="0" width="100%">');
  if ($O=='L') {
    // Recupera solicita��es a serem listadas
    $RS_Solic = db_getAlerta::getInstanceOf($dbms, $w_cliente, $w_usuario, 'SOLICGERAL', 'N', null);
    $RS_Solic = SortArray($RS_Solic, 'cliente', 'asc', 'usuario', 'asc', 'nm_modulo','asc', 'nm_servico', 'asc', 'titulo', 'asc');

    // Recupera pacotes de trabalho a serem listados
    $RS_Pacote = db_getAlerta::getInstanceOf($dbms, $w_cliente, $w_usuario, 'PACOTE', 'N', null);
    $RS_Pacote = SortArray($RS_Pacote, 'cliente', 'asc', 'usuario', 'asc', 'nm_projeto','asc', 'cd_ordem', 'asc');

    ShowHTML(VisualAlerta($w_cliente, $w_usuario, 'TELA', $RS_Solic, $RS_Pacote));
  } else {
    ScriptOpen("JavaScript");
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Exibe calend�rio da PDP
// -------------------------------------------------------------------------
function Arquivos() {
  extract($GLOBALS);

  $p_codigo = $_REQUEST['p_codigo'];
  
  $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PJCAD');
  $RS1 = db_getSolicList::getInstanceOf($dbms, f($RS,'sq_menu'), $w_usuario, f($RS,'sigla'), 6, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
  $RS1 = SortArray($RS1,'codigo_interno','asc');
  foreach($RS1 as $row) {
    if (f($row,'codigo_interno')==$p_codigo) {
      $RS_Solic = $row;
      break;
    }
  }

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.';">');
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');
  ShowHTML('<table border="0" width="100%">');
  ShowHTML('<tr><td><b><FONT COLOR="#000000"><font size=2>'.$w_TP.'</font></b>');
  ShowHTML('<tr><td colspan=2><hr>');
  
  if (!is_array($RS_Solic)) {
    ShowHTML('<tr><td colspan=2>Registro n�o encontrado');
  } else {
    // Recupera a EAP
    $RS_Etapa = db_getSolicEtapa::getInstanceOf($dbms,f($RS_Solic,'sq_siw_solicitacao'),null,'ARVORE',null);
    
    foreach($RS_Etapa as $row) {
      // Se tiver anexos, exibe
      if (f($row,'qt_anexo')>0) {
        // Exibe arquivos vinculados
        $RS = db_getEtapaAnexo::getInstanceOf($dbms,f($row,'sq_projeto_etapa'),null,$w_cliente);
        $RS = SortArray($RS,'nome','asc');
        if (count($RS) > 0) {
          ShowHTML('<tr><td colspan="2"><b>'.strtoupper(f($row,'titulo')).' ('.count($RS).')</b>');
          ShowHTML('<tr><td align="center" colspan=2>');
          ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
          ShowHTML('          <td width="20%"><b>T�tulo</td>');
          ShowHTML('          <td width="50%"><b>Resumo</td>');
          ShowHTML('          <td width="10%"><b>Data</td>');
          ShowHTML('          <td width="20%"><b>Tipo</td>');
          ShowHTML('          <td width="10%"><b>Tamanho</td>');
          ShowHTML('        </tr>');
          $w_cor=$conTrBgColor;
          foreach ($RS as $row) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            ShowHTML('    <tr bgColor="'.$w_cor.'">');
            ShowHTML('     <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>');
            ShowHTML('     <td>'.Nvl(f($row,'descricao'),'---').'</td>');
            ShowHTML('     <td>'.formataDataEdicao(f($row,'inclusao')).'</td>');
            ShowHTML('     <td>'.f($row,'tipo').'</td>');
            ShowHTML('     <td align="right">'.round(f($row,'tamanho')/1024,1).' KB&nbsp;</td>');
          } 
          ShowHTML('  </table>');
          ShowHTML('<tr><td>&nbsp;</td></tr>');
        }           
      }
    }
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Exibe calend�rio da PDP
// -------------------------------------------------------------------------
function Calendario() {
  extract($GLOBALS);
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.';">');
  ShowHTML('<link href="xPandMenu.css" rel="stylesheet" type="text/css">');
  ScriptOpen('Javascript');
  ValidateOpen('Validacao');
  Validate('p_programa','Programa','SELECT',1,1,18,'','0123456789');
  Validate('p_texto','Local','','',3,50,'1','1');
  ShowHTML('  theForm.p_pesquisa.value="OK";');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');
  ShowHTML('<table border="0" width="100%">');
  ShowHTML('<tr><td><b><FONT COLOR="#000000"><font size=2>'.$w_TP.'</font></b>');
  ShowHTML('<tr><td colspan=5><hr>');
  if($O == 'L'){ 
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));', null, $P1, $P2, $P3, null, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="p_pesquisa" value="">');
    ShowHTML(' <fieldset><table width="100%" bgcolor="'.$conTrBgColor.'">');
    ShowHTML('   <tr><td><b>');
    ShowHTML('     <input type="checkbox" '.((nvl($p_agenda,'')!='') ? 'checked' : '').' name="p_agenda" value="0" /> Agenda de A��o');
    ShowHTML('     <input type="checkbox" '.((nvl($p_evento,'')!='') ? 'checked' : '').' name="p_evento" value="1" />Evento ');
    ShowHTML('     <input type="checkbox" '.((nvl($p_reuniao,'')!='') ? 'checked' : '').' name="p_reuniao" value="2" />Reuni�o PDP');
    ShowHTML('   </tr>');
    ShowHTML('   <tr>');
    SelecaoPrograma('P<u>r</u>ograma:', 'R', 'Se desejar, selecione um dos programas.', $p_programa, $p_plano, $p_objetivo, 'p_programa', null, null /*'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.target=\'\'; document.Form.O.value=\'' . $O . '\'; document.Form.w_troca.value=\'p_projeto\'; document.Form.submit();"'*/);
    ShowHTML('   <tr>');
    SelecaoUnidade('Org�o Respons�vel:', null, null, $p_unidade, null, 'p_unidade', null, null);
    ShowHTML('   <tr><td><b><u>L</u>ocal:<br><input class="STI" accesskey="L" type="text" size="80" maxlength="80" name="p_texto" id="p_texto" value="'. $p_texto .'"></td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr valign="top"><td colspan="3">');
    ShowHTML('     <input class="STB" type="submit" name="Botao" value="BUSCAR">');
    ShowHTML('     <input class="STB" type="button" name="Botao" value="INSERIR" onClick="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\'I\'; document.Form.submit();">');
    ShowHTML('   </tr>');
    ShowHTML(' </table></fieldset>');
    ShowHTML('          </form>');
    
	  // Exibe o calend�rio da organiza��o
	  include_once($w_dir_volta.'classes/sp/db_getDataEspecial.php');
	  for ($i=$w_ano1;$i<=$w_ano3;$i++) {
	    $RS_Ano[$i] = db_getDataEspecial::getInstanceOf($dbms,$w_cliente,null,$i,'S',null,null,null);
	    $RS_Ano[$i] = SortArray($RS_Ano[$i],'data_formatada','asc');
	  }
	
	  // Recupera os dados da unidade de lota��o do usu�rio
	  include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
	  $RS_Unidade = db_getUorgData::getInstanceOf($dbms,$_SESSION['LOTACAO']);
	  if (nvl($_REQUEST['p_pesquisa'],'')!='') {
      $RS_Resultado = db_getSolicResultado :: getInstanceOf($dbms,$w_cliente,$p_programa,$p_projeto,$p_unidade,$p_solicitante,$p_texto,formataDataEdicao($w_inicio),formataDataEdicao($w_fim),'LISTA');
      if ($p_ordena>'') { 
        $lista = explode(',',str_replace(' ',',',$p_ordena));
        $RS_Resultado = SortArray($RS_Resultado,$lista[0],$lista[1],'mes_ano','desc','cd_programa','asc', 'cd_projeto','asc','titulo','asc');
      } else {
        $RS_Resultado = SortArray($RS_Resultado,'mes_ano','desc','cd_programa','asc', 'cd_projeto','asc','titulo','asc');
      }
      $RS_ResultCal = SortArray($RS_Resultado,'mes_ano','asc','cd_programa','asc', 'cd_projeto','asc','titulo','asc');
      
	    // Cria arrays com cada dia do per�odo, definindo o texto e a cor de fundo para exibi��o no calend�rio
	    foreach($RS_ResultCal as $row) {
	      $w_saida   = f($row,'mes_ano');
	      $w_chegada = f($row,'mes_ano');
	      //retornaArrayDias(f($row,'mes_ano'), f($row,'mes_ano'), &$w_datas, f($row,'titulo'), 'N');
	      $w_datas[formataDataEdicao($w_saida)]['valor']= str_replace('agenda',f($row,'titulo'),$w_datas[formataDataEdicao($w_saida)]['valor']);
	      $w_datas[formataDataEdicao($w_chegada)]['valor']= str_replace('agenda',f($row,'titulo'),$w_datas[formataDataEdicao($w_chegada)]['valor']);
	    }
	    reset($RS_ResultCal);
	    foreach($RS_ResultCal as $row) {
	      $w_saida   = f($row,'mes_ano');
	      $w_chegada = f($row,'mes_ano');
	      retornaArrayDias(f($row,'mes_ano'), f($row,'mes_ano'), &$w_cores, $conTrBgColorLightYellow2, 'N');
	    }
	  }
	
	  // Verifica a quantidade de colunas a serem exibidas
	  $w_colunas = 1;
	
	  // Configura a largura das colunas
	  switch ($w_colunas) {
	  case 1:  $width = "100%";  break;
	  case 2:  $width = "50%";  break;
	  case 3:  $width = "33%";  break;
	  case 4:  $width = "25%";  break;
	  default: $width = "100%";
	  }
	  ShowHTML('        <table width="100%" border="0" align="center" CELLSPACING=0 CELLPADDING=0 BorderColorDark='.$conTableBorderColorDark.' BorderColorLight='.$conTableBorderColorLight.'><tr valign="top">');
	  // Exibe calend�rio e suas ocorr�ncias ==============
	  ShowHTML('          <td width="'.$width.'" align="center"><table border="1" cellpadding=0 cellspacing=0>');
	  ShowHTML('            <tr><td colspan=3 width="100%"><table width="100%" border=0 cellpadding=0 cellspacing=0><tr>');
	  ShowHTML('              <td bgcolor="'.$conTrBgColor.'"><A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&w_mes='.$w_mes1.'&w_ano='.$w_ano1.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'"><<<</A>');
	  ShowHTML('              <td align="center" bgcolor="'.$conTrBgColor.'"><b>Calend�rio '.f($RS_Cliente,'nome_resumido').' ('.f($RS_Unidade,'nm_cidade').')</td>');
	  ShowHTML('              <td align="right" bgcolor="'.$conTrBgColor.'"><A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&w_mes='.$w_mes3.'&w_ano='.$w_ano3.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'">>>></A>');
	  ShowHTML('              </table>');
	  // Vari�veis para controle de exibi��o do cabe�alho das datas especiais
	  $w_detalhe1 = false;
	  $w_detalhe2 = false;
	  $w_detalhe3 = false;
	  ShowHTML('            <tr valign="top">');
	  ShowHTML('              <td align="center">'.montaCalendario($RS_Ano[$w_ano1],$w_mes1.$w_ano1,$w_datas,$w_cores,&$w_detalhe1).' </td>');
	  ShowHTML('              <td align="center">'.montaCalendario($RS_Ano[$w_ano],$w_mes.$w_ano,$w_datas,$w_cores,&$w_detalhe2).' </td>');
	  ShowHTML('              <td align="center">'.montaCalendario($RS_Ano[$w_ano3],$w_mes3.$w_ano3,$w_datas,$w_cores,&$w_detalhe3).' </td>');
	
	  if ($w_detalhe1 || $w_detalhe2 || $w_detalhe3) {
	    ShowHTML('            <tr><td colspan=3 bgcolor="'.$conTrBgColor.'">');
	    ShowHTML('              <b>Clique sobre o dia em destaque para ver detalhes.</b>');
	  }
	
	  // Exibe informa��es complementares sobre o calend�rio
	  ShowHTML('            <tr valign="top" bgcolor="'.$conTrBgColor.'">');
	  ShowHTML('              <td colspan=3 align="center">');
	  if ($w_detalhe1 || $w_detalhe2 || $w_detalhe3) {
	    ShowHTML('                <table width="100%" border="0" cellspacing=1>');
	    if (count($RS_Ano)==0) {
	      ShowHTML('                  <tr valign="top"><td align="center">&nbsp;');
	    } else {
	      ShowHTML('                  <tr valign="top"><td align="center"><b>Data<td><b>Ocorr�ncias');
	      reset($RS_Ano);
	      for ($i=$w_ano1;$i<=$w_ano3;$i++) {
	        $RS_Ano_Atual = $RS_Ano[$i];
	        foreach($RS_Ano_Atual as $row_ano) {
	          // Exibe apenas as ocorr�ncias do trimestre selecionado
	          if (f($row_ano,'data_formatada') >= $w_inicio && f($row_ano,'data_formatada') <= $w_fim) {
	            ShowHTML('                  <tr valign="top">');
	            ShowHTML('                    <td align="center">'.date(d.'/'.m,f($row_ano,'data_formatada')));
	            ShowHTML('                    <td>'.f($row_ano,'nome'));
	          }
	        }
	      }
	      ShowHTML('              </table>');
	    }
	  }
	  ShowHTML('          </table>');
    ShowHTML('  </table>');
    if (nvl($_REQUEST['p_pesquisa'],'')!='') {
	    ShowHTML('<table width="100%">');
	    ShowHTML('<tr><td align="right" colspan="2">');      
	    if ($w_embed!='WORD') {
	      CabecalhoRelatorio($w_cliente,'Visualiza��o de resultados',4,$w_chave,null);
	    }
		  // Final da exibi��o do calend�rio e suas ocorr�ncias ==============
	    ShowHTML('<tr><td align="right" colspan="2"><b>Resultados: ' . count($RS_Resultado) . ' </b></td></tr>');
	    ShowHTML('<tr><td align="center" colspan=2>');
	    ShowHTML('    <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
	    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
	    if($w_embed != 'WORD'){
	      ShowHTML('          <td width="10">&nbsp;</td>');
	      ShowHTML('          <td nowrap><b>&nbsp;'.linkOrdena('Data','mes_ano').'&nbsp;</td>');
	      ShowHTML('          <td nowrap><b>&nbsp;'.linkOrdena('Programa','cd_programa').'&nbsp;</td>');
	      ShowHTML('          <td><b>&nbsp;'.linkOrdena('Agenda de A��o','cd_projeto').'&nbsp;</td>');
	      ShowHTML('          <td nowrap><b>&nbsp;'.linkOrdena('Assunto/Resultado','titulo').'&nbsp;</td>');
	      ShowHTML('          <td nowrap><b>&nbsp;'.linkOrdena('Realizador','sg_setor').'&nbsp;</td>');
	      ShowHTML('          <td nowrap><b>&nbsp;'.linkOrdena('Local','local').'&nbsp;</td>');
	      ShowHTML('          <td nowrap><b>&nbsp;A��o&nbsp;</td>');
	    } else {
	      ShowHTML('          <td width="10">&nbsp;</td>');
	      ShowHTML('          <td nowrap><b>&nbsp;Data&nbsp;</td>');
	      ShowHTML('          <td nowrap><b>&nbsp;Programa&nbsp;</td>');
	      ShowHTML('          <td nowrap><b>&nbsp;Agenda de A��o&nbsp;</td>');
	      ShowHTML('          <td nowrap><b>&nbsp;Assunto/Resultado&nbsp;</td>');
	      ShowHTML('          <td nowrap><b>&nbsp;Realizador&nbsp;</td>');
	      ShowHTML('          <td nowrap><b>&nbsp;Local&nbsp;</td>');
	    }      
	    ShowHTML('        </tr>');
	    $w_cor = $conTrBgColor;
	    if (count($RS_Resultado) == 0) {
	      ShowHTML('    <tr align="center"><td colspan="5">Nenhum resultado encontrado para os crit�rios informados.</td>');
	    } else {
	      foreach ($RS_Resultado as $row) {
	        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
	        ShowHTML('    <tr valign="top" bgColor="' . $w_cor . '">');
	        if (nvl(f($row, 'sq_projeto_etapa'),'')!='') {
	          ShowHTML('      <td bgcolor="'.$conTrBgColorLightYellow2.'">&nbsp;</td>');
	        } else {
	          ShowHTML('      <td bgcolor="'.$conTrBgColorLightGreen2.'">&nbsp;</td>');
	        }
	        ShowHTML('      <td align="center" width="1%" nowrap>' . Date('d/m/Y', Nvl(f($row, 'mes_ano'), '---')) . '</td>');
	        ShowHTML('      <td align="center" width="1%" title="'.f($row, 'nm_programa').'" nowrap>' . Nvl(f($row, 'cd_programa'), '---') . '</td>');
	        ShowHTML('      <td align="center" width="1%" title="'.f($row, 'nm_projeto').'" nowrap>' . Nvl(f($row, 'cd_projeto'), '---') . '</td>');
	        ShowHTML('      <td title="'.f($row, 'descricao').'">'.f($row, 'titulo').' </td>');
	        ShowHTML('      <td>'.ExibeUnidade(null,$w_cliente,f($row,'sg_setor'),f($row,'sq_unidade'),$TP).' </td>');
	        ShowHTML('      <td>'.nvl(f($row, 'local'),'---').' </td>');
	        if($w_embed != 'WORD'){
	          if (nvl(f($row, 'sq_projeto_etapa'),'')!='') {
	            ShowHTML('      <td nowrap><A target="item" class="HL" href="cl_pitce/projeto.php?par=atualizaetapa&R='.$w_pagina.$par.'&O=V&w_chave='.f($row, 'sq_siw_solicitacao').'&w_chave_aux='.f($row, 'sq_projeto_etapa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe dados do item">Exibir</A></td>');
	          } else {
	            ShowHTML('      <td nowrap><A target="item" class="HL" href="cl_pitce/projeto.php?par=atualizaetapa&R='.$w_pagina.$par.'&O=V&w_chave='.f($row, 'sq_siw_solicitacao').'&w_chave_aux='.f($row, 'sq_projeto_etapa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe dados do item">Exibir evento</A></td>');
	          }
	        }
	        ShowHTML('    </tr>');
	      }
	      ShowHTML('  </table>');
	      ShowHTML('<tr><td>&nbsp;</td></tr>');
	    }
	    ShowHTML('</center>');
    }
  } elseif ($O=='I') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));', null, $P1, $P2, $P3, null, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML(' <fieldset><table width="100%" bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('   <tr>');
    ShowHTML('     <td colspan="4"><p>');
    ShowHTML('         <label>');
    ShowHTML('         <input name="w_tipo" type="radio" class="STR" value="0" />');
    ShowHTML('         Reuni&atilde;o PDP</label>');
    ShowHTML('         <spacer>&nbsp;&nbsp;</spacer>');
    ShowHTML('         <label>');
    ShowHTML('         <input name="w_tipo" type="radio" class="STR" value="1" />');
    ShowHTML('         Evento</label>');
    ShowHTML('       </p></td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr>');
    SelecaoPrograma('P<u>r</u>ograma:', 'R', 'Se desejar, selecione um dos programas.', $p_programa, $p_plano, $p_objetivo, 'p_programa', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.target=\'\'; document.Form.O.value=\'' . $O . '\'; document.Form.w_troca.value=\'p_projeto\'; document.Form.submit();"',1,4);
    ShowHTML('   </tr>');
    ShowHTML('   <tr>');
    $RS = db_getLinkData :: getInstanceOf($dbms, $w_cliente, 'PJCAD');
    SelecaoProjeto('Agenda de a��<u>o</u>:', 'O', 'Selecione um item na rela��o.', $p_projeto, $w_usuario, f($RS, 'sq_menu'), $p_programa, $p_objetivo, $p_plano, 'p_projeto', 'PJLIST', 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.target=\'\'; document.Form.O.value=\'' . $O . '\'; document.Form.w_troca.value=\'p_projeto\'; document.Form.submit();"',1,4);
  	// Recupera os dados do projeto
    $RS = db_getSolicData::getInstanceOf($dbms,$p_projeto,'PJGERAL');
    // var_dump($RS);
    ShowHTML('   </tr>');
    ShowHTML('   <tr><td nowrap width="30%"><b>�rg�o respons�vel: </b></td><td>'.ExibeUnidade(null,$w_cliente,f($RS,'sg_unidade_resp'),f($RS,'sq_unidade_resp'),$TP).'</td>');
    ShowHTML('   <tr><td width="30%"><b>Respons�vel: </b></td><td nowrap> '.nvl(f($RS,'proponente'),'---'). '</td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr>');
    //SelecaoPessoa('Respo<u>n</u>s�vel:', 'N', 'Selecione o respons�vel pelo projeto na rela��o.', $p_solicitante, null, 'p_solicitante', 'USUARIOS');
    ShowHTML('   </tr>');
    ShowHTML('   <tr>');
    ShowHTML('     <td colspan="4">&nbsp;</td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr>');
    ShowHTML('     <td colspan="4"><b>Envolvidos:<br>');
    ShowHTML('       <input name="w_secretaria" type="checkbox" class="STC" id="w_secretaria" accesskey="E" tabindex="1" value="0" checked />');
    ShowHTML('       Secretaria Executiva');
    ShowHTML('       <input name="w_coordenacao" type="checkbox" class="STC" id="w_coordenacao" accesskey="R" tabindex="2" value="1" checked />');
    ShowHTML('       Coordena&ccedil;&atilde;o');
    ShowHTML('       <input name="w_comite" type="checkbox" class="STC" id="w_comite" accesskey="R" tabindex="2" value="2" checked />');
    ShowHTML('       Comit&ecirc; Executivo');
    ShowHTML('       <label></label></td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr>');
    ShowHTML('     <td colspan="4">&nbsp;</td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr>');
    ShowHTML('     <td colspan="4" align="left"  nowrap="nowrap"><strong>Assunto<br/>');
    ShowHTML('       </strong>');
    ShowHTML('       <input name="textfield" type="text" class="STI" size="80" maxlength="80" /></td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr>');
    ShowHTML('     <td colspan="2" align="left"  nowrap="nowrap"><label><strong>Local<br>');
    ShowHTML('       </strong></label>');
    ShowHTML('       <input name="textfield2" type="text" class="STI" size="80" maxlength="80" /></td>');
    ShowHTML('     <td  width="50%" align="left" nowrap="nowrap"><strong>');
    ShowHTML('       <label>Arquivo<br>');
    ShowHTML('       </label>');
    ShowHTML('       </strong>');
    ShowHTML('       <input name="w_arquivo" type="file" class="STI" size="20" /></td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr>');
    ShowHTML('     <td colspan="4">&nbsp;</td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr>');
    ShowHTML('     <td colspan="4"><label><strong>In&iacute;cio</strong></label>');
    ShowHTML('       <br>');
    ShowHTML('       <input name="w_data_inicio" type="text" class="STI" size="15" />');
    ShowHTML('       <spacer>&nbsp;&nbsp;</spacer>');
    ShowHTML('       <input name="textfield3" type="text" class="STI" size="10" /></td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr>');
    ShowHTML('     <td colspan="4"><strong>');
    ShowHTML('       <label>T&eacute;rmino</label>');
    ShowHTML('       <br>');
    ShowHTML('       </strong>');
    ShowHTML('       <input name="w_data_termino" type="text" class="STI"  size="15" />');
    ShowHTML('       <spacer>&nbsp;&nbsp;</spacer>');
    ShowHTML('       <input name="textfield32" type="text" class="STI" size="10" /></td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr>');
    ShowHTML('     <td colspan="4"><b>Descri��o:</b><br><textarea class="STI" name="w_mensagem" cols="80" rows="10" id="w_mensagem"></textarea></td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr>');
    ShowHTML('     <td colspan="4">&nbsp;</td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr>');
    ShowHTML('     <td align="right" colspan="4"><input name="w_enviar" type="submit" class="STB" id="w_enviar" accesskey="S" tabindex="0" value="ENVIAR" />');
    ShowHTML('       <spacer>&nbsp;&nbsp;</spacer>');
    ShowHTML('       <input name="w_reenviar" type="submit" class="STB" id="w_reenviar" tabindex="6" value="RE-ENVIAR" />');
    ShowHTML('       <spacer>&nbsp;&nbsp;</spacer>');
    ShowHTML('       <input name="w_excluir" type="reset" class="STB" id="w_cancelar" accesskey="I" tabindex="7" value="EXCLUIR" />');
    ShowHTML('     </td>');
    ShowHTML('   </tr>');
    ShowHTML(' </table>');
    ShowHTML(' </fieldset>');
    ShowHTML(' </form>');
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  if     ($w_tipo=='PDF')  RodapePDF();
  elseif ($w_tipo!='WORD') Rodape();
}

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);

  switch ($par) {
  case 'MESA':          Mesa();         break;
  case 'ARQUIVOS':      Arquivos();   break;
  case 'CALENDARIO':    Calendario();   break;
  case 'ALERTA':        Alerta();       break;
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

