<?
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getAddressList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCoordenada.php');
include_once($w_dir_volta.'classes/googlemaps/nxgooglemapsapi.php');

// =========================================================================
//  /exibe.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerenciar tabelas básicas do módulo  
// Mail     : alex@sbpi.com.br
// Criacao  : 19/01/2007, 14:20
// Versao   : 1.0.0.0
// Local    : Brasília - DF
// -------------------------------------------------------------------------
// 
// Parâmetros recebidos:
//    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
//    O (operação)   = I   : Inclusão
//                   = A   : Alteração
//                   = C   : Cancelamento
//                   = E   : Exclusão
//                   = L   : Listagem
//                   = P   : Pesquisa
//                   = D   : Detalhes
//                   = N   : Nova solicitação de envio

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par        = strtoupper($_REQUEST['par']);
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = strtoupper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = strtoupper($_REQUEST['O']);
$w_assinatura = strtoupper($_REQUEST['w_assinatura']);
$w_pagina     = 'exibe.php?par=';
$w_Disabled   = 'ENABLED';
$w_dir        = 'mod_gr/';
$w_troca      = $_REQUEST['w_troca'];
$p_ordena     = $_REQUEST['p_ordena'];

if ($O=='') $O='L';

switch ($O) {
  case 'I': $w_TP=$TP.' - Seleção de coordenadas';        break;
  case 'L': $w_TP=$TP.' - Exibição';                      break;
}

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();

Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Rotina de exibição de pontos geográficos
// -------------------------------------------------------------------------
function inicial(){
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_inicio     = $_REQUEST['w_inicio'];
  $w_endereco   = $_REQUEST['w_endereco'];
  $w_projeto    = $_REQUEST['w_projeto'];
  $w_etapas     = $_REQUEST['w_etapas'];
  $w_sq_pessoa  = $_REQUEST['w_sq_pessoa'];
  $w_cabecalho  = '';

  $api = new NXGoogleMapsAPI();

  // setup the visual design of the control
  $api->setWidth(600);
  $api->setHeight(500);
  $api->addControl(GMapTypeControl);
  $api->addControl(GLargeMapControl);
  $api->setZoomFactor(4);
  $api->setCenter('-15.780148','-47.929169');
  $api->addIcon('house','http://maps.google.com/mapfiles/kml/pal2/icon10.png','http://maps.google.com/mapfiles/kml/pal2/icon10s.png');
  $api->addIcon('project','http://maps.google.com/mapfiles/kml/pal2/icon13.png','http://maps.google.com/mapfiles/kml/pal2/icon13s.png');
  $api->divId = 'mapid';
  
  $w_cont = 1;

  if (nvl($w_endereco,'')!=='') {
    // Recupera todos os endereços do cliente, independente do tipo
    $RS_Endereco = db_getAddressList::getInstanceOf($dbms,$w_cliente,null,'FISICO',null);
    $RS_Endereco = SortArray($RS_Endereco,'padrao','desc','tipo_endereco','asc','endereco','asc');
    foreach($RS_Endereco as $row) {
      $w_lat  = str_replace(',','.',f($row,'latitude'));
      $w_long = str_replace(',','.',f($row,'longitude'));
      $w_html = f($row,'nm_coordenada');
      $w_html .= '<br>Logradouro: '.f($row,'endereco');
      $w_icon = f($row,'icone');
      if (nvl(f($row,'nm_coordenada'),'')!='') {
        $api->addGeoPoint($w_lat,$w_long,$w_html,false,$w_icon,$w_cont);
        $w_enderecos[$w_cont] = '            <br><a href="javascript:myClick('.$w_cont.');">'.f($row,'nm_coordenada').'</a>';
        $w_cont += 1;
      }
    }
  }

  if (nvl($w_projeto,'')!=='') {
    // Recupera todos os endereços do cliente, independente do tipo
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PJCAD');
    $RS_Projeto = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,f($RS,'sigla'),3,
        null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,
        null,null,null,null, null, null, null);
    $RS_Projeto = SortArray($RS_Projeto,'codigo_interno','asc','titulo','asc');
    foreach($RS_Projeto as $row) {
      $w_lat  = str_replace(',','.',f($row,'latitude'));
      $w_long = str_replace(',','.',f($row,'longitude'));
      $w_html = f($row,'nm_coordenada');
      $w_html .= '<br>Código: '.f($row,'codigo_interno');
      $w_html .= '<br>Título: '.f($row,'titulo');
      $w_html .= '<br>IDE: '.formatNumber(f($row,'ide'),2).'%';
      $w_html .= '<br>IGE: '.formatNumber(f($row,'ige'),2).'%';
      $w_html .= '<br>IDC: '.formatNumber(f($row,'idc'),2).'%';
      $w_html .= '<br>IGC: '.formatNumber(f($row,'igc'),2).'%';
      $w_html .= '<br><A class="HL" HREF="'.$conRootSIW.'projeto.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" target="_blank">Ver ficha do projeto</a>'.exibeImagemRestricao(f($row,'restricao'),'P');
      $w_icon = f($row,'icone');
      if (nvl(f($row,'nm_coordenada'),'')!='') {
        $api->addGeoPoint($w_lat,$w_long,$w_html,false,$w_icon,$w_cont);
        $w_projetos[$w_cont] = '            <br><a href="javascript:myClick('.$w_cont.');">'.f($row,'nm_coordenada').'</a>';
        $w_cont += 1;
      }
    }
  }

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML($api->getHeadCode());
  ShowHTML('<TITLE>'.$conSgSistema.' - Geo-referenciamento</TITLE>');
  Estrutura_CSS($w_cliente);
  if (!(strpos('IAET',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    ValidateOpen('Validacao');  
    ShowHTML('  if (theForm.w_latitude.value=="" || theForm.w_longitude.value=="") {');
    ShowHTML('    alert("Selecione um ponto para definição das coordenadas!");');
    ShowHTML('    return false;');
    ShowHTML('  }');
    Validate('w_nome','Nome para exibição','1','1','1','20','1','1'); 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if (nvl($w_inicio,'')!='' && nvl($w_latitude,'')=='') {
    BodyOpen('onLoad="'.$api->getOnLoadCode().' moveToAddressDMarker(document.getElementById(\'address\').value);"');
  } else {
    BodyOpen('onLoad="'.$api->getOnLoadCode().'"');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();

  ShowHTML($w_cabecalho);

  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_tipo" value="'.$w_tipo.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_pessoa" value="'.$w_sq_pessoa.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_icone" value="house">');
    ShowHTML('<tr><td align="center">');
    ShowHTML('    <table border="1"><tr>');
    ShowHTML('      <tr valign="top" bgcolor="'.$conTrBgColor.'">');
    ShowHTML('        <td><div id="side_bar" style="overflow:auto; height:100%;"><table border=0 width="100%">');
    ShowHTML('          <tr><td colspan=2><b>Indique o que exibir:</b>');
    if (nvl($w_endereco,'')!='') $w_checked = true; else $w_checked = false;
    ShowHTML('          <tr valign="top"><td width="1%" nowrap><input type="checkbox" name="w_endereco" value="OK" '.(($w_checked) ? 'CHECKED' : '').' onClick="document.Form.submit();"><td><b>Endereços</b>');
    if (nvl($w_endereco,'')!='' && count($w_enderecos)>0) {
      foreach ($w_enderecos as $k => $v) ShowHTML($v);
    }
    if (nvl($w_projeto,'')!='') $w_checked = true; else $w_checked = false;
    ShowHTML('          <tr valign="top"><td width="1%" nowrap><input type="checkbox" name="w_projeto" value="OK" '.(($w_checked) ? 'CHECKED' : '').' onClick="document.Form.submit();"><td><b>Projetos</b>');
    if (nvl($w_projeto,'')!='' && count($w_projetos)>0) {
      foreach ($w_projetos as $k => $v) ShowHTML($v);
    }
    ShowHTML('        </table>');
    ShowHTML('        </div><td>');
    ShowHTML($api->getBodyCode());
    ShowHTML('        </td></tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>'); 
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  global $w_Disabled;
  switch ($par) {
    case 'INICIAL':             Inicial();           break;
    default:
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=this.focus();');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Rodape();
    exibevariaveis();
  break;
  } 
} 
?>