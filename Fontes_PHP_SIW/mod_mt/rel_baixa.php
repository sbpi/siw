<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getAddressData.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getUnidadeMedida.php');
include_once($w_dir_volta.'classes/sp/db_getUnidade_PE.php');
include_once($w_dir_volta.'classes/sp/db_getMTBem.php');
include_once($w_dir_volta.'classes/sp/db_getMtSituacao.php');
include_once($w_dir_volta.'classes/sp/db_getBenef.php');
include_once($w_dir_volta.'classes/sp/db_getParametro.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicMT.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'funcoes/selecaoSolic.php');
include_once($w_dir_volta.'funcoes/selecaoAlmoxarifado.php');
include_once($w_dir_volta.'funcoes/selecaoEndereco.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoLocalizacao.php');
include_once($w_dir_volta.'funcoes/selecaoTipoMatServ.php');
include_once($w_dir_volta.'funcoes/selecaoMatServ.php');
include_once($w_dir_volta.'funcoes/selecaoMtSituacao.php');

// =========================================================================
//  /rel_baixa.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Relatório de depreciação de bens
// Mail     : alex@sbpi.com.br
// Criacao  : 21/03/2021, 21h46
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
//                   = M   : Configuração de serviços

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par        = upper($_REQUEST['par']);
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);
$w_assinatura = $_REQUEST['w_assinatura'];
$w_pagina     = 'rel_baixa.php?par=';
$w_Disabled   = 'ENABLED';
$w_dir        = 'mod_mt/';
$w_troca      = $_REQUEST['w_troca'];

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

$p_chave          = $_REQUEST['p_chave'];
$p_tipo_material  = $_REQUEST['p_tipo_material'];
$p_financeiro     = $_REQUEST['p_financeiro'];
$p_descricao      = $_REQUEST['p_descricao'];
$p_marca          = $_REQUEST['p_marca'];
$p_modelo         = $_REQUEST['p_modelo'];
$p_observacao     = $_REQUEST['p_observacao'];
$p_rgp            = $_REQUEST['p_rgp'];
$p_sqcc           = $_REQUEST['p_sqcc'];
$p_projeto        = $_REQUEST['p_projeto'];
$p_material       = $_REQUEST['p_material'];
$p_almoxarifado   = $_REQUEST['p_almoxarifado'];
$p_unidade        = $_REQUEST['p_unidade'];
$p_localizacao    = $_REQUEST['p_localizacao'];
$p_situacao       = $_REQUEST['p_situacao'];
$p_inicio         = $_REQUEST['p_inicio'];
$p_fim            = $_REQUEST['p_fim'];
$p_endereco       = $_REQUEST['p_endereco'];
$p_codigo_externo = $_REQUEST['p_codigo_externo'];

$p_ordena         = $_REQUEST['p_ordena'];
$p_volta          = upper($_REQUEST['p_volta']);

if ($SG=='MTRELBX') {
  if ($O=='') $O='P';
} elseif ($O=='') $O='L';

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';        break;
  case 'A': $w_TP=$TP.' - Alteração';       break;
  case 'E': $w_TP=$TP.' - Exclusão';        break;
  case 'P': $w_TP=$TP.' - Filtragem';       break;
  case 'C': $w_TP=$TP.' - Cópia';           break;
  case 'V': $w_TP=$TP.' - Envio';           break;
  case 'H': $w_TP=$TP.' - Herança';         break;
  case 'M': $w_TP=$TP.' - Serviços';        break;
  default:  $w_TP=$TP.' - Listagem';        break;
}

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();

// Recupera as informações do cliente
$sql = new db_getCustomerData; $RS_Cliente = $sql->getInstanceOf($dbms,$w_cliente);

// Recupera as informações da opçao de menu;
$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);
// Se for sub-menu, pega a configuração do pai
if (f($RS_Menu,'ultimo_nivel') == 'S') {
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 

// Verifica se o cliente tem o módulo de materiais
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'AL');
if (count($RS)>0) $w_al='S'; else $w_al='N'; 

// Verifica se o cliente tem o módulo de projetos
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PR');
if (count($RS)>0) $w_pr='S'; else $w_pr='N'; 

// Verifica se o cliente tem o módulo financeiro
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'FN');
if (count($RS)>0) $w_fn='S'; else $w_fn='N'; 

// Recupera os parâmetros do módulo de compras e licitações
$sql = new db_getParametro; $RS_Parametro = $sql->getInstanceOf($dbms,$w_cliente,'CL',null);
foreach($RS_Parametro as $row){$RS_Parametro=$row;}

Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Rotina de dados gerais
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave              = $_REQUEST['w_chave'];
  $w_copia              = $_REQUEST['w_copia'];
  $w_tipo               = $_REQUEST['w_tipo'];
  $w_tipo_material      = $_REQUEST['w_tipo_material'];
  
  // Configuração do nível de acesso
  $w_restricao = 'EDICAOT';
  if ($p_acesso=='I') $w_restricao = 'EDICAOP';

  if ($w_troca>'' && $O <> 'E') {
    $w_sqcc            = $_REQUEST['w_sqcc'];
    $w_projeto         = $_REQUEST['w_projeto'];
    $w_almoxarifado    = $_REQUEST['w_almoxarifado'];
    $w_unidade         = $_REQUEST['w_unidade'];
    $w_localizacao     = $_REQUEST['w_localizacao'];
    $w_rgp             = $_REQUEST['w_rgp'];
    $w_entrada         = $_REQUEST['w_entrada'];
    $w_forn_garantia   = $_REQUEST['w_forn_garantia'];
    $w_fim_garantia    = $_REQUEST['w_fim_garantia'];
    $w_tombamento      = formataDataEdicao($_REQUEST['w_tombamento']);
    $w_vida_util       = $_REQUEST['w_vida_util'];
    $w_descricao       = $_REQUEST['w_descricao'];
    $w_observacao      = $_REQUEST['w_observacao'];
    $w_tipo_material   = $_REQUEST['w_tipo_material'];
    $w_material        = $_REQUEST['w_material'];
    $w_situacao        = $_REQUEST['w_situacao'];
    $w_codigo_externo  = $_REQUEST['w_codigo_externo'];
    $w_marca           = $_REQUEST['w_marca'];
    $w_modelo          = $_REQUEST['w_modelo'];
    $w_numero_serie    = $_REQUEST['w_numero_serie'];
    $w_valor_brl       = formatNumber($_REQUEST['w_valor_brl']);
    $w_data_brl        = FormataDataEdicao($_REQUEST['w_data_brl']);
    $w_valor_usd       = formatNumber($_REQUEST['w_valor_usd']);
    $w_data_usd        = FormataDataEdicao($_REQUEST['w_data_usd']);
    $w_valor_eur       = formatNumber($_REQUEST['w_valor_eur']);
    $w_data_eur        = FormataDataEdicao($_REQUEST['w_data_eur']);
    $w_ativo           = $_REQUEST['w_ativo'];
  }

  if ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']); 
  } else {
    Cabecalho();
    head();
    Estrutura_CSS($w_cliente);
    ShowHTML('<TITLE>'.$conSgSistema.' - Materiais e Serviços</TITLE>');
    Estrutura_CSS($w_cliente);
    if (strpos('PCIAE',$O)!==false) {
      ScriptOpen('JavaScript');
      CheckBranco();
      FormataData();
      FormataValor();
      SaltaCampo();
      ValidateOpen('Validacao');
      Validate('p_rgp','RGP Atual','1','','1','18','','0123456789');
      Validate('p_material','Bem','1','','2','90','1','1');
      Validate('p_financeiro','Código interno','1','','2','30','1','1');
      Validate('p_projeto','Projeto','SELECT','','1','18','','1');
      Validate('p_descricao','Descrição complementar','','','2','2000','1','1');
      Validate('p_marca','Marca','','',1,50,'1','1');
      Validate('p_modelo','Modelo','','',1,50,'1','1');
      Validate('p_observacao','Observação','','',1,2000,'1','1');
      Validate('p_codigo_externo','Código externo','','',1,30,'1','1');
      Validate('p_inicio','Início do período', 'DATA', '', '10', '10', '', '0123456789/');
      Validate('p_fim','Fim do período', 'DATA', '', '10', '10', '', '0123456789/');
      ShowHTML('  if ((theForm.p_inicio.value != \'\' && theForm.p_fim.value == \'\') || (theForm.p_inicio.value == \'\' && theForm.p_fim.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
      ShowHTML('     theForm.p_inicio.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      CompData('p_inicio','Início do período','<=','p_fim','Fim do período');
      ValidateClose();
      ScriptClose();
    } 
  
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</HEAD>');
  }
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus();"');
  } elseif ($w_tipo=='WORD'){
    BodyOpenWord(null);
  } elseif ($O=='P'){
    BodyOpen('onLoad="document.Form.p_rgp.focus();"');
  } elseif ($O=='L'){
    BodyOpen('onLoad="this.focus();"');
  } else {
    BodyOpen('onLoad="document.Form.w_assinatura.focus();"');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td valign="top"><table border=0 width="90%" cellspacing=0>');
  if ($SG=='MTRELBX') $rel = 'baixados';
  else $rel = 'detDesp';
  AbreForm('Form',$w_dir.$w_pagina.$rel,'POST','return(Validacao(this));','Baixa',$P1,'',$P3,null,$TP,$SG,$R,'L');
  ShowHTML(montaFiltro('POST',true));
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('      <tr><td colspan=2><table border=0 width="100%" cellspacing=0><tr valign="top">');
  ShowHTML('      <tr valign="top">');
  ShowHTML('          <td><b><u>R</u>GP Atual:</b><br><input '.$p_Disabled.' accesskey="R" type="text" name="p_rgp" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.$p_rgp.'"></td>');
  ShowHTML('          <td><b><u>B</u>em:</b><br><input '.$p_Disabled.' accesskey="B" type="text" name="p_material" class="sti" SIZE="40" MAXLENGTH="40" VALUE="'.$p_material.'"></td>');
  ShowHTML('          <td><b><u>C</u>ódigo do lançamento financeiro:</b><br><input '.$p_Disabled.' accesskey="C" type="text" name="p_financeiro" class="sti" SIZE="20" MAXLENGTH="30" VALUE="'.$p_financeiro.'"></td>');

  ShowHTML('      <tr>');
  SelecaoEndereco('En<u>d</u>ereço principal:','d',null,$p_endereco,$w_cliente,'p_endereco','FISICO', 'onChange="document.Form.target=\'\'; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_endereco\'; document.Form.submit();"', 3);
  ShowHTML('      </tr>');

  ShowHTML('      <tr valign="top">');
  selecaoalmoxarifado('Al<u>m</u>oxarifado:','M', 'Selecione o almoxarifado ao qual o bem pertence.', $p_almoxarifado,'p_almoxarifado',null,null);

  ShowHTML('      <tr valign="top">');
  SelecaoUnidade('<U>U</U>nidade solicitante:','U','Selecione a unidade solicitante do pedido',$p_unidade,$p_endereco,'p_unidade','MOD_MT','onChange="document.Form.target=\'\'; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_unidade\'; document.Form.submit();"', 3);

  ShowHTML('      <tr>');
  selecaoLocalizacao('Lo<U>c</U>alização:','C',null,$p_localizacao,$p_unidade,'p_localizacao',null,null,3);
  ShowHTML('      </tr>');

  ShowHTML('      <tr valign="top">');
  selecaoTipoMatServ('T<U>i</U>po de material:','I',null,$p_tipo_material,null,'p_tipo_material','FOLHAPER',null,3);

  ShowHTML('          <tr>');
  $sql = new db_getLinkData; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
  SelecaoSolic('Projeto:',null,null,$w_cliente,$p_projeto,$w_sq_menu_relac,f($RS1,'sq_menu'),'p_projeto',f($RS_Menu,'sigla'),null,$p_projeto,'<BR />',3);
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td><b><u>M</u>arca:</b><br><input accesskey="M" type="text" name="p_marca" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.$p_marca.'"></td>');
  ShowHTML('        <td><b><u>M</u>odelo:</b><br><input accesskey="M" type="text" name="p_modelo" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.$p_modelo.'"></td>');
  selecaoMtSituacao('<u>S</u>ituação física:','S', null, $p_situacao,'p_situacao','BEM',null);
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td><b><U>D</U>escrição complementar:<br><input accesskey="M" type="text" name="p_descricao" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.$p_descricao.'"></td>');
  ShowHTML('        <td><b><U>O</U>bservação:<br><input accesskey="M" type="text" name="p_observacao" class="sti" SIZE="25" MAXLENGTH="50" VALUE="'.$p_observacao.'"></td>');
  ShowHTML('        <td><b><u>C</u>ódigo externo:</b><br><input accesskey="C" type="text" name="p_codigo_externo" class="sti" SIZE="25" MAXLENGTH="30" VALUE="'.$p_codigo_externo.'"></td>');
  ShowHTML('      <tr><td colspan="3" valign="top"><b><u>P</u>eríodo:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="p_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_inicio.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_inicio').' e <input '.$w_Disabled.' accesskey="C" type="text" name="p_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim').' ');

  ShowHTML('      <tr valign="top">');
  ShowHTML('          <td><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="sti" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
  ShowHTML('          </table>');
  ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
  ShowHTML('      <tr><td align="center" colspan="3">');
  ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir">');
  ShowHTML('            <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $w_pagina . $par . '&R=' . $R . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&O=P&SG=' . $SG) . '\';" name="Botao" value="Limpar campos">');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
} 

// =========================================================================
// Relatório de bens baixados
// -------------------------------------------------------------------------
function baixados() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave              = $_REQUEST['w_chave'];
  $w_copia              = $_REQUEST['w_copia'];
  $w_tipo               = $_REQUEST['w_tipo'];
  $w_tipo_material      = $_REQUEST['w_tipo_material'];
  
  $sql = new db_getMTBem;
  $RSQuery = $sql->getInstanceOf($dbms,$w_cliente, $w_usuario, $p_chave, $p_sqcc, 
          $p_projeto, $p_financeiro, $p_tipo_material, $p_material, $p_rgp, $p_descricao,
          $p_marca, $p_modelo, $p_observacao, 'N', $p_almoxarifado, $p_endereco, 
          $p_unidade,  $p_localizacao, $p_situacao, $p_inicio, $p_fim, $p_codigo_externo,
          $p_restricao);
  $RSQuery = SortArray($RSQuery,'numero_rgp','asc');

  $w_embed  = '';
  $l_html   = '';
  $w_filtro = '';
  
  headerGeral('P', $w_tipo, $w_chave, $conSgSistema.' - Patrimônio', $w_embed, null, null, $w_linha_pag,$w_filtro);
  if ($w_embed!='WORD') {
    Cabecalho();
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Patrimônio</TITLE>');
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    ShowHTML('</HEAD>');
    BodyOpenClean('onLoad="this.focus()";');
    CabecalhoRelatorio($w_cliente, f($RS_Menu,'nome'), 4, $w_chave);
    ShowHTML('<HR>');
  }
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');

  if ($w_tipo!='EXCEL') {
    if ($p_rgp>'')          $w_filtro.='<tr><td align="right">RGP <td>[<b>'.$p_rgp.'</b>]';
    if ($p_material>'')     $w_filtro.='<tr><td align="right">Bem <td>[<b>'.$p_material.'</b>] em qualquer parte';
    if ($p_financeiro>'')   $w_filtro.='<tr><td align="right">Financeiro <td>[<b>'.$p_financeiro.'</b>]';
    if ($p_endereco>'') {
      $sql = new db_getAddressData; $RS = $sql->getInstanceOf($dbms, $p_endereco);
      $w_filtro.='<tr><td align="right">Endereço <td>[<b>'.f($RS,'endereco_completo').'</b>]';
    } 
    if ($p_almoxarifado>'') {
      $sql = new db_getAlmoxarifado; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_almoxarifado,null,null,null,null,'OUTROS');
      $w_filtro.='<tr><td align="right">Almoxarifado <td>[<b>'.f($RS[0],'nome').'</b>]';
    } 
    if ($p_tipo_material>'') {
      $sql = new db_getTipoMatServ; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_tipo_material,null,null,null,null,null,null,'REGISTROS');
      $w_filtro.='<tr><td align="right">Tipo <td>[<b>'.f($RS[0],'nome_completo').'</b>]';
    } 
    if ($p_projeto>'') {
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
      $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações do projeto." target="_blank">'.f($RS,'titulo').'</a></b>]';
    } 
    if ($p_marca>'')        $w_filtro.='<tr><td align="right">Marca <td>[<b>'.$p_marca.'</b>] em qualquer parte';
    if ($p_modelo>'')       $w_filtro.='<tr><td align="right">Modelo <td>[<b>'.$p_modelo.'</b>] em qualquer parte';
    if ($p_situacao>'') {
      $sql = new db_getMtSituacao; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,$p_situacao,null,null,null);
      $w_filtro.='<tr><td align="right">Situação física <td>[<b>'.f($RS[0],'nome').'</b>]';
    } 
    if ($p_descricao>'')    $w_filtro.='<tr><td align="right">Descrição <td>[<b>'.$p_descricao.'</b>] em qualquer parte';
    if ($p_observacao>'')   $w_filtro.='<tr><td align="right">Observação <td>[<b>'.$p_observacao.'</b>] em qualquer parte';
    if ($p_fim>'')          $w_filtro .= '<tr valign="top"><td align="right">Período <td>[<b>'.$p_inicio.'-'.$p_fim.'</b>]';
    if ($w_filtro) {
      $l_html .= '<div align="left"><table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table></div>';
    }
  } 
  
  $w_resumo = array();
  $w_qtd_geral = 0;
  $w_aquisicao_geral = 0;
  $w_acumulado_geral = 0;
  $w_atual_geral = 0;
  $w_ativos = 0;
  $w_vl_ativos = 0;
  $w_baixados = 0;
  $w_vl_baixados = 0;
  $w_transferidos = 0;
  $w_vl_transferidos = 0;
  $w_atual = '*0*'; // valor qualquer apenas para marcar o início da execução

  $l_html .= '<tr><td align="center" colspan=3><div align="center">';
  $l_html .= '    <TABLE class="tudo" width=99%  border="1" bordercolor="#00000">';
  baixados_cab($w_tipo, $l_html, $colspan, f($row,'nm_tipo_material'));
  if (count($RSQuery)<=0) {
    // Se não foram selecionados registros, exibe mensagem
    $l_html .= '      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>';
  } else {
    $w_linha = 1;
    foreach($RSQuery as $row){ 
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      $l_html .= '      <tr valign="top">';
      $l_html .= '        <td align="center">'.$w_linha++.'</td>';
      $l_html .= '        <td align="center"'.((nvl(f($row,'observacao'),'')!='') ? ' title="'.  CRLF2BR(f($row,'observacao')).'"' : '').'>'.f($row,'numero_rgp').'</td>';
      $l_html .= '        <td>'.ExibePermanente($w_dir_volta,$w_cliente,f($row,'nome_completo').((nvl(f($row,'descricao_complementar'),'')!='') ? ' '.f($row,'descricao_complementar') : ''),f($row,'chave'),$TP,null,null,$w_tipo).'</td>';
      $l_html .= '        <td align="center">'.formataDataEdicao(f($row,'data_baixa'),5).'</td>';
      if ($w_tipo!='WORD') $l_html .= '        <td align="center"><A target="'.f($row,'codigo_interno').'" class="hl" HREF="'.$w_dir.'baixa_bem.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_baixa').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">'.f($row,'cd_baixa').'</a></td>';
      else                 $l_html .= '        <td align="center">'.f($row,'codigo_interno').'</td>'; 
      if ($w_mod_pa=='S') {
        if ($w_embed!='WORD' && nvl(f($row,'protocolo_siw'),'')!='') {
          $l_html .= '        <td align="right"><A class="HL" HREF="mod_pa/documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'protocolo_siw').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=PADGERAL'.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="processo">'.f($row,'protocolo').'</a>';
        } else {
          $l_html .= '        <td align="right">'.f($row,'protocolo');
        }
      }
      $l_html .= '        <td>'.f($row,'nm_tipo_baixa').'</td>';
      $l_html .= '        <td>'.(($w_tipo=='WORD') ? f($row,'nm_destino') : ExibePessoa('../',$w_cliente,f($row,'sq_pessoa_destino'),$TP,f($row,'nm_destino'))).'</td>';
    }
  }

  $l_html .= '    </table></div>';
  $l_html .= '  </td>';
  $l_html .= '</tr>';


  ShowHTML($l_html);
  
  ShowHTML('</table>');

  if($w_tipo=='PDF') RodapePdf();
  else               Rodape();
} 

function baixados_cab($w_tipo, &$l_html, &$colspan, $titulo) {
  extract($GLOBALS);
  $texto = '';
  
  $texto .= '        <tr align="center">';
  $colspan++; $texto .= '          <td><b>Item</td>';
  $colspan++; $texto .= '          <td><b>Bem</td>';
  $colspan++; $texto .= '          <td><b>RGP</td>';
  $colspan++; $texto .= '          <td><b>Efetivação</td>';
  $colspan++; $texto .= '          <td><b>Solicitação</b></td>';
  if ($w_mod_pa=='S') {
    $colspan++; $texto .= '          <td><b>Processo</td>';
  }
  $colspan++; $texto .= '          <td><b>Tipo</td>';
  $colspan++; $texto .= '          <td><b>Beneficiário</td>';
  $texto .= '        </tr>';
  $texto .= '        <tr align="center">';

  $l_html .= '      <tr valign="middle" style="height: 40px;" bgcolor="'.$w_cor.'">';
  $l_html .= '        <td colspan='.$colspan.' align="center"><b>'.$titulo.'<b></td>';
  $l_html .= $texto;

  $l_html .= '        </tr>';
}

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  global $w_Disabled;
  switch ($par) {
    case 'INICIAL':            Inicial();           break;
    case 'BAIXADOS':           Baixados();          break;
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