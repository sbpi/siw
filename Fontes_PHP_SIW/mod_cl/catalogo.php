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
include_once($w_dir_volta.'classes/sp/db_getCcData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getUnidadeMedida.php');
include_once($w_dir_volta.'classes/sp/db_getUnidade_PE.php');
include_once($w_dir_volta.'classes/sp/db_getMatServ.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putMatServ.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoTipoMatServ.php');
include_once($w_dir_volta.'funcoes/selecaoMatServ.php');
include_once($w_dir_volta.'funcoes/selecaoUnidadeMedida.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'classes/sp/dml_putMatServ.php');

// =========================================================================
//  /catalogo.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerenciar os dados dos materiais e serviços da organização
// Mail     : alex@sbpi.com.br
// Criacao  : 01/07/2007, 10:29
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
$w_pagina     = 'catalogo.php?par=';
$w_Disabled   = 'ENABLED';
$w_dir        = 'mod_cl/';
$w_troca      = $_REQUEST['w_troca'];

$p_chave         = $_REQUEST['p_chave'];
$p_tipo_material = $_REQUEST['p_tipo_material'];
$p_sq_cc         = $_REQUEST['p_sq_cc'];
$p_codigo        = $_REQUEST['p_codigo'];
$p_nome          = $_REQUEST['p_nome'];
$p_ativo         = $_REQUEST['p_ativo'];
$p_ordena        = $_REQUEST['p_ordena'];
$p_volta         = strtoupper($_REQUEST['p_volta']);

if ($SG=='CLMATSERV') {
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

// Recupera as informações da opçao de menu;
$RS_Menu = db_getMenuData::getInstanceOf($dbms,$w_menu);
// Se for sub-menu, pega a configuração do pai
if (f($RS_Menu,'ultimo_nivel') == 'S') {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 

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
    $w_gestora         = $_REQUEST['w_gestora'];
    $w_sq_cc           = $_REQUEST['w_sq_cc'];
    $w_unidade_medida  = $_REQUEST['w_unidade_medida'];
    $w_nome            = $_REQUEST['w_nome'];
    $w_descricao       = $_REQUEST['w_descricao'];
    $w_detalhamento    = $_REQUEST['w_detalhamento'];
    $w_apresentacao    = $_REQUEST['w_apresentacao'];
    $w_codigo_interno  = $_REQUEST['w_codigo_interno'];
    $w_codigo_externo  = $_REQUEST['w_codigo_externo'];
    $w_exibe_catalogo  = $_REQUEST['w_exibe_catalogo'];
    $w_vida_util       = $_REQUEST['w_vida_util'];
    $w_ativo           = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    if (montaFiltro('GET')!='') {
      $w_filtro='';

      if ($p_codigo>'')     $w_filtro.='<tr valign="top"><td align="right">Código <td>[<b>'.$p_codigo.'</b>] em qualquer parte';
      if ($p_nome>'')    $w_filtro.='<tr valign="top"><td align="right">Nome <td>[<b>'.$p_nome.'</b>] em qualquer parte';
      if ($p_tipo_material>'') {
        $RS = db_getTipoMatServ::getInstanceOf($dbms,$w_cliente,$p_tipo_material,null,null,null,null,null,null,'REGISTROS');
        foreach ($RS as $row) { $RS = $row; break; }
        $w_filtro.='<tr valign="top"><td align="right">Tipo <td>[<b>'.f($RS,'nome_completo').'</b>]';
      } 
      if ($p_sq_cc>'') {
        $RS = db_getCCData::getInstanceOf($dbms,$p_sq_cc);
        $w_filtro.='<tr valign="top"><td align="right">Classificação <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_ativo=='S') {
        $w_filtro.='<tr valign="top"><td align="right">Situação <td>[<b>Apenas itens ativos</b>]';
      } elseif ($p_ativo=='N') {
        $w_filtro.='<tr valign="top"><td align="right">Situação <td>[<b>Apenas itens inativos</b>]';
      } else {
        $w_filtro.='<tr valign="top"><td align="right">Situação <td>[<b>Itens ativos e inativos</b>]';
      }
      if ($w_filtro>'')     $w_filtro='<div align="left"><table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table></div>';
    } 

    $RS = db_getMatServ::getInstanceOf($dbms,$w_cliente,$w_usuario,null,$p_tipo_material,$p_sq_cc,$p_codigo,$p_nome,$p_ativo,$w_restricao);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nm_tipo_material_pai','asc','nm_tipo_material','asc','nome','asc');
    } else {
      $RS = SortArray($RS,'nm_tipo_material_pai','asc','nm_tipo_material','asc','nome','asc'); 
    }
  } elseif (strpos('MCAEV',$O)!==false) {
    $RS = db_getMatServ::getInstanceOf($dbms,$w_cliente,$w_usuario,$w_chave,null,null,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_gestora         = f($RS,'unidade_gestora');
    $w_tipo_material   = f($RS,'sq_tipo_material');
    $w_sq_cc           = f($RS,'sq_cc');
    $w_unidade_medida  = f($RS,'sq_unidade_medida');
    $w_nome            = f($RS,'nome');
    $w_descricao       = f($RS,'descricao');
    $w_detalhamento    = f($RS,'detalhamento');
    $w_apresentacao    = f($RS,'apresentacao');
    $w_codigo_interno  = f($RS,'codigo_interno');
    $w_codigo_externo  = f($RS,'codigo_externo');
    $w_exibe_catalogo  = f($RS,'exibe_catalogo');
    $w_vida_util       = f($RS,'vida_util');
    $w_ativo           = f($RS,'ativo');
  } 

  // Recupera informações sobre o tipo do material ou serviço
  if (nvl($w_tipo_material,'')!='') {
    $RS_Tipo = db_getTipoMatServ::getInstanceOf($dbms,$w_cliente,$w_tipo_material,null,null,null,null,null,null,'REGISTROS');
    foreach ($RS_Tipo as $row) { $RS_Tipo = $row; break; }
    $w_classe = f($RS_Tipo,'classe');
  } 

  if ($w_tipo=='WORD') {
    HeaderWord(); 
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    Estrutura_CSS($w_cliente);
    ShowHTML('<TITLE>'.$conSgSistema.' - Materiais e Serviços</TITLE>');
    Estrutura_CSS($w_cliente);
    if (strpos('PCIAE',$O)!==false) {
      ScriptOpen('JavaScript');
      modulo();
      FormataValor();
      ValidateOpen('Validacao');
      if (strpos('CIA',$O)!==false) {
        Validate('w_nome','Nome','1','1','3','110','1','1');
        Validate('w_codigo_interno','Código interno','1','1','2','30','1','1');
        Validate('w_tipo_material','Tipo do material ou serviço','SELECT','1','1','18','','1');
        Validate('w_sq_cc','Classificação','SELECT','1','1','18','','1');
        Validate('w_unidade_medida','Unidade de alocação','SELECT','1','1','18','','1');
        Validate('w_descricao','Descricao','','',1,130,'1','1');
        Validate('w_detalhamento','Detalhamento','','',1,2000,'1','1');
        if ($w_classe==1) Validate('w_apresentacao','Apresentação (embalagem)','','1',1,200,'1','1');
        Validate('w_codigo_externo','Código externo','1','','2','30','1','1');
        if ($w_classe==4) Validate('w_vida_util','Vida útil','','1',1,2,'','0123456789');
        Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
      } elseif ($O=='P') {
        Validate('p_nome','Nome','1','','3','30','1','1');
        Validate('p_codigo','Código interno','1','','2','30','1','1');
        Validate('p_tipo_material','Tipo do material ou serviço','SELECT','','1','18','','1');
        Validate('p_sq_cc','Classificação','SELECT','','1','18','','1');
      } elseif ($O=='E') {
        Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
        ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\'));');
        ShowHTML('     { return (true); }; ');
        ShowHTML('     { return (false); }; ');
      }
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
      ValidateClose();
      ScriptClose();
    } 
    ShowHTML('</HEAD>');
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus();"');
  } elseif ($w_tipo=='WORD'){
    BodyOpenWord(null);
  } elseif ($O=='P'){
    BodyOpen('onLoad="document.Form.p_nome.focus();"');
  } elseif (strpos('CIA',$O)!==false) {
    BodyOpen('onLoad="document.Form.w_nome.focus();"');
  } elseif ($O=='L'){
    BodyOpen('onLoad="this.focus();"');
  } else {
    BodyOpen('onLoad="document.Form.w_assinatura.focus();"');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  if ($w_filtro > '') ShowHTML($w_filtro);
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    if ($w_tipo!='WORD') {
      ShowHTML('<tr><td><font size="2"><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
      if (MontaFiltro('GET')>'') ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
      else                       ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
    }
    ShowHTML('    <td align="right">');
    if ($w_tipo!='WORD') {
      ShowHTML('&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      ShowHTML('&nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&O=L&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.count($RS).'&TP='.$TP.'&SG='.$SG.'&w_tipo=WORD'.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
    } 
    ShowHTML('    <b>Registros: '.count($RS));        
    ShowHTML('<tr><td align="center" colspan=3>');  
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    if ($w_tipo!='WORD') {
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Tipo','nm_tipo_material').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Código','codigo_interno').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Nome','nome').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Un.','sg_unidade_medida').'</td>');
      ShowHTML('          <td bgColor="#f0f0f0" colspan=3><b>Última pesquisa</b></td>');
      ShowHTML('          <td rowspan=2><b> Operações </td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr align="center">');
      ShowHTML('          <td bgColor="#f0f0f0" colspan=2><b>'.LinkOrdena('Validade','pesquisa_validade').'</b></td>');
      ShowHTML('          <td bgColor="#f0f0f0"><b>'.LinkOrdena('$ Médio','pesquisa_preco_medio').'</b></td>');
      ShowHTML('        </tr>');
    } else {
      ShowHTML('          <td rowspan=2><b>Tipo</td>');
      ShowHTML('          <td rowspan=2><b>Código</td>');
      ShowHTML('          <td rowspan=2><b>Nome</td>');
      ShowHTML('          <td rowspan=2><b>Un.</td>');
      ShowHTML('          <td bgColor="#f0f0f0" colspan=3><b>Última pesquisa</b></td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr align="center">');
      ShowHTML('          <td bgColor="#f0f0f0" colspan=2><b>Validade</b></td>');
      ShowHTML('          <td bgColor="#f0f0f0"><b>$ Médio</b></td>');
      ShowHTML('        </tr>');
    }  
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row){ 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nm_tipo_material').'</td>');
        ShowHTML('        <td>'.f($row,'codigo_interno').'</td>');
        if ($w_tipo!='WORD') ShowHTML('        <td>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'chave'),$TP,null).'</td>');
        else                 ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>');
        if (nvl(f($row,'pesquisa_data'),'')=='') {
          ShowHTML('            <td colspan=3 align="center">&nbsp;</td>');
        } else {
          ShowHTML('            <td align="center" width="1%" nowrap>'.ExibeSinalPesquisa(false,f($row,'pesquisa_data'),f($row,'pesquisa_validade'),f($row,'pesquisa_aviso')).'</td>');
          ShowHTML('            <td align="center">'.nvl(formataDataEdicao(f($row,'pesquisa_validade'),5),'---').'</td>');
          ShowHTML('            <td align="center">'.nvl(formatNumber(f($row,'pesquisa_preco_medio'),4),'---').'</td>');
        }
        if ($w_tipo!='WORD') {
          ShowHTML('        <td align="top" nowrap>');
          ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Altera os dados deste registro.">AL</A>&nbsp');
          ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Exclui deste registro.">EX</A>&nbsp');
          ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Inclui um novo item a partir dos dados deste registro.">CO</A>&nbsp');
          ShowHTML('        </td>');
          ShowHTML('      </tr>');
        } 
      }
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('      <tr colspan=2><table border=0><tr><td colspan=3><b>Legenda:</b><tr><td>'.ExibeSinalPesquisa(true,null,null,null).'</td></tr></table>');
    ShowHTML('  </td>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($w_tipo!='WORD') {
      if ($R>'') {
        MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
      } else {
        MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
      } 
    }
    ShowHTML('</tr>');
  } elseif (strpos('CIAEV',$O)!==false) {
    //Aqui começa a manipulação de registros
    if ($O=='C') ShowHTML('      <tr><td colspan=3 align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATENÇÃO: Dados importados de outro registro. Altere os dados necessários antes de executar a inclusão..</b></font>.</td>');
    if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED '; 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML(montaFiltro('POST'));
    if ($O!='C') ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.nvl($w_copia,$w_chave).'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0"><tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="40" MAXLENGTH="100" VALUE="'.$w_nome.'"></td>');
    ShowHTML('          <td><b><u>C</u>ódigo:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_codigo_interno" class="sti" SIZE="20" MAXLENGTH="30" VALUE="'.$w_codigo_interno.'"></td>');
    ShowHTML('      <tr valign="top">');
    selecaoTipoMatServ('T<U>i</U>po:','I',null,$w_tipo_material,null,'w_tipo_material','FOLHA','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_cc\'; document.Form.submit();"');
    ShowHTML('      <tr valign="top">');
    SelecaoCC('C<u>l</u>assificação:','L','Selecione a classificação desejada.',nvl($w_sq_cc,f($RS_Solic,'sq_cc')),null,'w_sq_cc','SIWSOLIC');
    selecaoUnidadeMedida('Unidade de f<U>o</U>rnecimento:','O','Selecione a unidade de fornecimento do material ou serviço',$w_unidade_medida,null,'w_unidade_medida','REGISTROS','S');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><u>C</u>ódigo externo:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_codigo_externo" class="sti" SIZE="20" MAXLENGTH="30" VALUE="'.$w_codigo_externo.'"></td>');
    if ($w_classe==4) ShowHTML('          <td><b><u>V</u>ida útil (em anos):</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_vida_util" class="sti" SIZE="2" MAXLENGTH="2" VALUE="'.$w_vida_util.'"></td>');
    ShowHTML('      <tr><td colspan=3><b><U>D</U>escrição:<br><TEXTAREA ACCESSKEY="D" class="sti" name="w_descricao" rows=2 cols=80." '.$w_Disabled.'>'.$w_descricao.'</textarea></td>');
    ShowHTML('      <tr><td colspan=3><b>D<U>e</U>talhamento:<br><TEXTAREA ACCESSKEY="E" class="sti" name="w_detalhamento" rows=5 cols=80." '.$w_Disabled.'>'.$w_detalhamento.'</textarea></td>');
    if ($w_classe==1) ShowHTML('      <tr><td colspan=3><b>A<U>p</U>resentação (embalagem):<br><TEXTAREA ACCESSKEY="P" class="sti" name="w_apresentacao" rows=3 cols=80." '.$w_Disabled.'>'.$w_apresentacao.'</textarea></td>');
    ShowHTML('      <tr valign="top">');
    MontaRadioSN('<b>Exibe item no catálogo de materiais e serviços?</b>',$w_exibe_catalogo,'w_exibe_catalogo');
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
    ShowHTML('      <tr><td colspan=3><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan=5><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="stb" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I' || $O=='C') {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O=='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr><td colspan=2><table border=0 width="100%" cellspacing=0><tr valign="top">');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><u>N</u>ome:</b><br><input '.$p_Disabled.' accesskey="N" type="text" name="p_nome" class="sti" SIZE="40" MAXLENGTH="100" VALUE="'.$p_nome.'"></td>');
    ShowHTML('          <td><b><u>C</u>ódigo:</b><br><input '.$p_Disabled.' accesskey="C" type="text" name="p_codigo" class="sti" SIZE="20" MAXLENGTH="30" VALUE="'.$p_codigo.'"></td>');
    ShowHTML('      <tr valign="top">');
    selecaoTipoMatServ('T<U>i</U>po:','I',null,$p_tipo_material,null,'p_tipo_material','FOLHA',null);
    ShowHTML('      <tr valign="top">');
    SelecaoCC('C<u>l</u>assificação:','L','Selecione a classificação desejada.',nvl($p_sq_cc,f($RS_Solic,'sq_cc')),null,'p_sq_cc','SIWSOLIC');
    ShowHTML('          <td><b>Recuperar:</b><br>');
    if (Nvl($p_ativo,'S')=='S') {
      ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="S" checked> Apenas ativos<br><input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="N"> Apenas inativos<br><input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value=""> Tanto faz');
    } elseif ($p_ativo=='N') {
      ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="S"> Apenas ativos<br><input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="N" checked> Apenas inativos<br><input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value=""> Tanto faz');
    } else {
      ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="S"> Apenas ativos<br><input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="N"> Apenas inativos<br><input '.$w_Disabled.' class="str" type="radio" name="p_ativo" value="" checked> Tanto faz');
    } 
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="sti" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    if ($O=='C') {
      // Se for cópia
      ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Abandonar cópia">');
    } else {
      ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
    } 
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
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
} 

// =========================================================================
// Rotina de tela de exibição do recurso
// -------------------------------------------------------------------------
function TelaMaterial() {
  extract($GLOBALS);
  global $w_Disabled, $w_TP;

  $w_chave = $_REQUEST['w_chave'];
  $w_solic = $_REQUEST['w_solic'];

  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  ShowHTML('<TITLE>Materiais e serviços</TITLE>');
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad="this.focus();"');
  $w_TP = 'Materiais e serviços - Visualização de dados';
  Estrutura_Texto_Abre();
  ShowHTML(visualMatServ($w_chave,true,$w_solic));
  Estrutura_Texto_Fecha();
} 

// =========================================================================
// Monta string com os dados do material ou serviço
// -------------------------------------------------------------------------
function visualMatServ($l_chave,$l_navega=true,$l_solic) {
  extract($GLOBALS);

  // Recupera os dados do material ou serviço
  $l_rs = db_getMatServ::getInstanceOf($dbms,$w_cliente,$w_usuario,$l_chave,null,null,null,null,null,null);
  foreach ($l_rs as $row) { $l_rs = $row; break; }

  // Se for listagem dos dados
  $l_html = '';
  $l_html.=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
  $l_html.=chr(13).'<tr><td align="center">';

  $l_html.=chr(13).'    <table width="99%" border="0">';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><b>['.f($l_rs,'codigo_interno').'] '.f($l_rs,'nome').'</font></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  $l_html .= chr(13).'    <tr><td width="30%"><b>Tipo:<b></td><td>'.f($l_rs,'nm_tipo_material_completo').' </td></tr>';
  $l_html .= chr(13).'    <tr><td width="30%"><b>Classificação:<b></td><td>'.f($l_rs,'nm_cc').' </td></tr>';
  $l_html .= chr(13).'    <tr><td width="30%"><b>Exibe no catálogo:<b></td><td>'.f($l_rs,'nm_exibe_catalogo').' </td></tr>';
  $l_html .= chr(13).'    <tr><td width="30%"><b>Item ativo:<b></td><td>'.f($l_rs,'nm_ativo').' </td></tr>';
  if (f($l_rs,'classe')==4) $l_html .= chr(13).'    <tr><td width="30%"><b>Vida útil:<b></td><td>'.nvl(f($l_rs,'vida_util'),'---').' </td></tr>';
  $l_html.=chr(13).'      <tr valign="top"><td><b>Descrição:</b></td><td>'.CRLF2BR(nvl(f($l_rs,'descricao'),'---')).' </td></tr>';
  $l_html.=chr(13).'      <tr valign="top"><td><b>Apresentação:</b></td><td>'.CRLF2BR(nvl(f($l_rs,'apresentacao'),'---')).' </td></tr>';
  if (f($l_rs,'classe')==1) $l_html.=chr(13).'      <tr valign="top"><td><b>Detalhamento:</b></td><td>'.CRLF2BR(nvl(f($l_rs,'detalhamento'),'---')).' </td></tr>';

  $l_html.=chr(13).'      <tr><td colspan="2" align="center"><br>';
  $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';    
  $l_html.=chr(13).'          <tr align="center">';
  $l_html.=chr(13).'            <td bgColor="#f0f0f0" colspan=3><b>ÚLTIMA PESQUISA</b></td>';
  $l_html.=chr(13).'            <td bgColor="#f0f0f0" colspan=3><b>PREÇOS</b></td>';
  $l_html.=chr(13).'          </tr>';
  $l_html.=chr(13).'          <tr align="center">';
  $l_html.=chr(13).'            <td bgColor="#f0f0f0" colspan=2><b>Cotação</b></td>';
  $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Validade</b></td>';
  $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Menor</b></td>';
  $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Maior</b></td>';
  $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Médio</b></td>';
  $l_html.=chr(13).'          </tr>';
  $l_html.=chr(13).'          <tr align="center">';
  if (nvl(f($l_rs,'pesquisa_data'),'')=='') {
    $l_html.=chr(13).'            <td colspan=6 align="center">Nenhuma pesquisa encontrada</td>';
  } else {
    $l_html.=chr(13).'            <td align="center" width="1%" nowrap>'.ExibeSinalPesquisa(false,f($l_rs,'pesquisa_data'),f($l_rs,'pesquisa_validade'),f($l_rs,'pesquisa_aviso')).'</td>';
    $l_html.=chr(13).'            <td align="center"><b>'.nvl(formataDataEdicao(f($l_rs,'pesquisa_data')),'---').'</b></td>';
    $l_html.=chr(13).'            <td align="center"><b>'.nvl(formataDataEdicao(f($l_rs,'pesquisa_validade')),'---').'</b></td>';
    $l_html.=chr(13).'            <td align="center"><b>'.nvl(formatNumber(f($l_rs,'pesquisa_preco_menor'),4),'---').'</b></td>';
    $l_html.=chr(13).'            <td align="center"><b>'.nvl(formatNumber(f($l_rs,'pesquisa_preco_maior'),4),'---').'</b></td>';
    $l_html.=chr(13).'            <td align="center"><b>'.nvl(formatNumber(f($l_rs,'pesquisa_preco_medio'),4),'---').'</b></td>';
  }
  $l_html.=chr(13).'          </tr>';
  $l_html.=chr(13).'         </table></td></tr>';


  // Exibe pesquisas de preço
  $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PESQUISAS DE PREÇO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
  $l_rs = db_getMatServ::getInstanceOf($dbms,$w_cliente,$w_usuario,$l_chave,null,null,null,null,null,'PESQMAT');
  if (count($l_rs)==0) {
    $l_html.=chr(13).'      <tr><td colspan="2" align="center">Nenhuma pesquisa encontrada</td></tr>';
  } else {
    $l_html.=chr(13).'      <tr><td colspan="2" align="center">';
    $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';    
    $l_html.=chr(13).'          <tr align="center">';
    $l_html.=chr(13).'            <td></td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Fornecedor</b></td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Cotação</b></td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Validade</b></td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Preço</b></td>';
    $l_html.=chr(13).'          </tr>';
    $w_cor=$conTrBgColor;
    foreach($l_rs as $row) {
      $l_html.=chr(13).'      <tr valign="top">';
      $l_html.=chr(13).'        <td width="1%" nowrap>'.ExibeSinalPesquisa(false,f($row,'phpdt_inicio'),f($row,'phpdt_fim'),f($row,'aviso')).'</td>';
      $l_html.=chr(13).'        <td>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'fornecedor'),$TP,f($row,'nm_fornecedor')).'</td>';
      $l_html.=chr(13).'        <td align="center">'.FormataDataEdicao(f($row,'phpdt_inicio'),6).'</td>';
      $l_html.=chr(13).'        <td align="center">'.FormataDataEdicao(f($row,'phpdt_fim'),6).'</td>';
      $l_html.=chr(13).'        <td align="right" nowrap>'.formatNumber(f($row,'valor_unidade'),4).'</td>';
      $l_html.=chr(13).'      </tr>';
    } 
    $l_html.=chr(13).'         </table></td></tr>';
    $l_html.=chr(13).'      <tr colspan=2><table border=0><tr><td colspan=3><b>Legenda:</b><tr><td>'.ExibeSinalPesquisa(true,null,null,null).'</td></tr></table>';
    $l_html.=chr(13).'</table>';
  }

  $l_html.=chr(13).'    </table>';
  $l_html.=chr(13).'</table>';
  return $l_html;

} 

// =========================================================================
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  global $w_Disabled;
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');
  switch ($SG) {
    case 'CLMATSERV':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='C' || $O=='I' || $O=='A') {
          // Testa a existência do nome
          $RS = db_getMatServ::getInstanceOf($dbms,$w_cliente,$w_usuario,Nvl($_REQUEST['w_chave'],''),null,null,null,$_REQUEST['w_nome'],null,'EXISTE');
          if (count($RS)>0) {
            foreach ($RS as $row) { $RS = $row; break; }
            if (f($RS,'existe')>0) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Já existe material ou serviço com este nome!\');');
              ScriptClose(); 
              retornaFormulario('w_nome');
              break;
            } 
          }

          if (nvl($_REQUEST['w_codigo_interno'],'nulo')!='nulo') {
            // Testa a existência do código
            $RS = db_getMatServ::getInstanceOf($dbms,$w_cliente,$w_usuario,nvl($_REQUEST['w_chave'],''),null,null,$_REQUEST['w_codigo_interno'],null,null,'EXISTE');
            if (count($RS)>0) {
              foreach ($RS as $row) { $RS = $row; break; }
              if (f($RS,'existe')>0) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'Já existe material ou serviço com este código!\');');
                ScriptClose(); 
                retornaFormulario('w_codigo_interno');
                break;
              } 
            }
          }
        } elseif ($O=='E') {
          $RS = db_getMatServ::getInstanceOf($dbms,$w_cliente,$w_usuario,$_REQUEST['w_chave'],null,null,null,null,null,'EXISTE');
          if (f($RS,'existe')>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Não é possível excluir este material ou serviço. Ele está ligado a algum documento!\');');
            ScriptClose();
            break;
            retornaFormulario('w_assinatura');
          } 
        } 
        dml_putMatServ::getInstanceOf($dbms,$O,$w_cliente,$w_usuario, $_REQUEST['w_chave'],$_REQUEST['w_copia'],
            $_REQUEST['w_tipo_material'],$_REQUEST['w_unidade_medida'],$_REQUEST['w_sq_cc'],$_REQUEST['w_nome'],
            $_REQUEST['w_descricao'],$_REQUEST['w_detalhamento'],$_REQUEST['w_apresentacao'],$_REQUEST['w_codigo_interno'],
            $_REQUEST['w_codigo_externo'], $_REQUEST['w_exibe_catalogo'], $_REQUEST['w_vida_util'], $_REQUEST['w_ativo'],
            &$w_chave_nova);

        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    default:
      exibevariaveis();
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Bloco de dados não encontrado: '.$SG.'\');');
      ScriptClose();
      break;
  } 
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  global $w_Disabled;
  switch ($par) {
    case 'INICIAL':            Inicial();           break;
    case 'DISPONIVEL':         Disponivel();        break;
    case 'INDISPONIVEL':       Indisponivel();      break;
    case 'TELAMATERIAL':       TelaMaterial();      break;
    case 'SOLIC':              Solic();             break;
    case 'SOLICPERIODO':       SolicPeriodo();      break;
    case 'GRAVA':              Grava();             break;
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