<?php

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
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicFN.php');
include_once($w_dir_volta.'classes/sp/db_getCountryData.php');
include_once($w_dir_volta.'classes/sp/db_getTipoLancamento.php');

// =========================================================================
//  /rel_pais.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Relatório de lançamentos por país
// Mail     : alex@sbpi.com.br
// Criacao  : 12/11/2018 09:17
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
if ($_SESSION['LOGON'] != 'Sim') { EncerraSessao(); }

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par = upper($_REQUEST['par']);
$P1 = nvl($_REQUEST['P1'], 0);
$P2 = nvl($_REQUEST['P2'], 0);
$P3 = nvl($_REQUEST['P3'], 1);
$P4 = nvl($_REQUEST['P4'], $conPageSize);
$TP = $_REQUEST['TP'];
$SG = upper($_REQUEST['SG']);
$R = $_REQUEST['R'];
$O = upper($_REQUEST['O']);
$w_assinatura = $_REQUEST['w_assinatura'];
$w_pagina = 'rel_pais.php?par=';
$w_Disabled = 'ENABLED';
$w_dir = 'mod_fn/';
$w_troca = $_REQUEST['w_troca'];
$w_embed = '';


$p_inicio       = nvl($_REQUEST['p_inicio'],'2018');
$p_fim          = nvl($_REQUEST['p_fim'],Date('Y'));
$p_projeto      = $_REQUEST['p_projeto'];
$p_pais         = $_REQUEST['p_pais'];
$p_sq_orprior   = $_REQUEST['p_sq_orprior'];

$w_inicio = '01/01/'.$p_inicio;
$w_fim = '31/12/'.$p_fim;

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ($O == '') {
  $O = 'L';
}
switch ($O) {
  case 'P': $w_TP = $TP . ' - Filtragem'; break;
  default: $w_TP = $TP . ' - Listagem';   break;
}
// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente = RetornaCliente();
$w_usuario = RetornaUsuario();
$w_menu = RetornaMenu($w_cliente, $SG);

// Recupera a configuração do serviço
$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);

Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Relatório de execução orçamentário-financeira de projeto.
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;
  global $w_embed;
  $w_tipo = $_REQUEST['w_tipo'];
  $w_sq_pessoa = upper(trim($_REQUEST['w_sq_pessoa']));
  
  if ($O == 'L') {
    // Prepara o informe executivo por país
    $sql = new db_getSolicFN; $RSQuery = $sql->getInstanceOf($dbms,null,$w_usuario,'INFEXECPAIS',$P1,
          $p_ini_i,$p_ini_f,$w_inicio,$w_fim,$p_atraso,$p_solicitante,$p_unidade,
          $p_prioridade,$p_ativo,$p_proponente,$p_chave, $p_objeto, $p_pais, $p_regiao, 
          $p_uf, $p_cidade, $p_usu_resp,$p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, 
          $p_projeto, null, null, $p_sq_orprior);

    $rel_pais = array();
    $total = 0;
    foreach($RSQuery as $row) {
      // Trata apenas valores diferentes de zero
      if (f($row,'vl_projeto')!=0) {
        // Trata a primeira linha do record set
        if (!is_array($rel_pais[f($row,'nm_pais')])) {
          $rel_pais[f($row,'nm_pais')]['rows'] = 1;
          $rel_pais[f($row,'nm_pais')]['chave'] = f($row,'sq_pais');
          $rel_pais[f($row,'nm_pais')]['vl_unificado'] = 0;
        }

        if (!is_array($rel_pais[f($row,'nm_pais')]['prj'][f($row,'cd_projeto')])) {
          $rel_pais[f($row,'nm_pais')]['rows']++;
          $rel_pais[f($row,'nm_pais')]['prj'][f($row,'cd_projeto')]['rows'] = 1;
          $rel_pais[f($row,'nm_pais')]['prj'][f($row,'cd_projeto')]['chave'] = f($row,'sq_projeto');
          $rel_pais[f($row,'nm_pais')]['prj'][f($row,'cd_projeto')]['titulo'] = f($row,'nm_projeto');
          $rel_pais[f($row,'nm_pais')]['prj'][f($row,'cd_projeto')]['moeda'] = f($row,'sg_pj_moeda');
          $rel_pais[f($row,'nm_pais')]['prj'][f($row,'cd_projeto')]['vl_projeto'] = 0;
          $rel_pais[f($row,'nm_pais')]['prj'][f($row,'cd_projeto')]['vl_unificado'] = 0;
        }

        $rel_pais[f($row,'nm_pais')]['rows']++;
        $rel_pais[f($row,'nm_pais')]['vl_unificado'] += f($row,'vl_unificado');

        $rel_pais[f($row,'nm_pais')]['prj'][f($row,'cd_projeto')]['rows']++;
        $rel_pais[f($row,'nm_pais')]['prj'][f($row,'cd_projeto')]['vl_projeto']   += f($row,'vl_projeto');
        $rel_pais[f($row,'nm_pais')]['prj'][f($row,'cd_projeto')]['vl_unificado'] += f($row,'vl_unificado');

        $rel_pais[f($row,'nm_pais')]['prj'][f($row,'cd_projeto')]['tipo'][f($row,'ds_tipo_lancamento')]['chave'] = f($row,'sq_tipo_lancamento');
        $rel_pais[f($row,'nm_pais')]['prj'][f($row,'cd_projeto')]['tipo'][f($row,'ds_tipo_lancamento')]['vl_projeto'] = f($row,'vl_projeto');
        $rel_pais[f($row,'nm_pais')]['prj'][f($row,'cd_projeto')]['tipo'][f($row,'ds_tipo_lancamento')]['vl_unificado'] = f($row,'vl_unificado');

        // Total geral
        $total += f($row,'vl_unificado');
      }
    }
    
    // Prepara o informe executivo por projeto
    $sql = new db_getSolicFN; $RSQuery = $sql->getInstanceOf($dbms,null,$w_usuario,'INFEXECPROJ',$P1,
          $p_ini_i,$p_ini_f,$w_inicio,$w_fim,$p_atraso,$p_solicitante,$p_unidade,
          $p_prioridade,$p_ativo,$p_proponente,$p_chave, $p_objeto, $p_pais, $p_regiao, 
          $p_uf, $p_cidade, $p_usu_resp,$p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, 
          $p_projeto, null, null, $p_sq_orprior);

    $rel_proj = array();
    $total = 0;
    foreach($RSQuery as $row) {
      // Trata apenas valores diferentes de zero
      if (f($row,'vl_pais')!=0) {
        // Trata a primeira linha do record set
        if (!is_array($rel_proj[f($row,'cd_projeto')])) {
          $rel_proj[f($row,'cd_projeto')]['rows'] = 1;
          $rel_proj[f($row,'cd_projeto')]['titulo'] = f($row,'nm_projeto');
          $rel_proj[f($row,'cd_projeto')]['chave'] = f($row,'sq_projeto');
          $rel_proj[f($row,'cd_projeto')]['moeda'] = f($row,'sg_pj_moeda');
          $rel_proj[f($row,'cd_projeto')]['vl_projeto'] = 0;
          $rel_proj[f($row,'cd_projeto')]['vl_unificado'] = 0;
        }

        if (!is_array($rel_proj[f($row,'cd_projeto')]['pais'][f($row,'nm_pais')])) {
          $rel_proj[f($row,'cd_projeto')]['rows']++;
          $rel_proj[f($row,'cd_projeto')]['pais'][f($row,'nm_pais')]['rows'] = 1;
          $rel_proj[f($row,'cd_projeto')]['pais'][f($row,'nm_pais')]['chave'] = f($row,'sq_pais');
          $rel_proj[f($row,'cd_projeto')]['pais'][f($row,'nm_pais')]['vl_pais'] = 0;
          $rel_proj[f($row,'cd_projeto')]['pais'][f($row,'nm_pais')]['vl_unificado'] = 0;
        }

        $rel_proj[f($row,'cd_projeto')]['rows']++;
        $rel_proj[f($row,'cd_projeto')]['vl_projeto'] += f($row,'vl_pais');
        $rel_proj[f($row,'cd_projeto')]['vl_unificado'] += f($row,'vl_unificado');

        $rel_proj[f($row,'cd_projeto')]['pais'][f($row,'nm_pais')]['rows']++;
        $rel_proj[f($row,'cd_projeto')]['pais'][f($row,'nm_pais')]['vl_pais'] += f($row,'vl_pais');
        $rel_proj[f($row,'cd_projeto')]['pais'][f($row,'nm_pais')]['vl_unificado'] += f($row,'vl_unificado');

        $rel_proj[f($row,'cd_projeto')]['pais'][f($row,'nm_pais')]['tipo'][f($row,'ds_tipo_lancamento')]['chave'] = f($row,'sq_tipo_lancamento');
        $rel_proj[f($row,'cd_projeto')]['pais'][f($row,'nm_pais')]['tipo'][f($row,'ds_tipo_lancamento')]['vl_pais'] = f($row,'vl_pais');
        $rel_proj[f($row,'cd_projeto')]['pais'][f($row,'nm_pais')]['tipo'][f($row,'ds_tipo_lancamento')]['vl_unificado'] = f($row,'vl_unificado');

        // Total geral
        $total += f($row,'vl_unificado');
      }
    }    
  }

  headerGeral('P', $w_tipo, $w_chave, 'Consulta de '.f($RS_Menu,'nome'), $w_embed, null, null, $w_linha_pag,$w_filtro);
  if ($w_embed!='WORD') {
    Cabecalho();
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Relatório</TITLE>');
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    ShowHTML('function montaLink(p_projeto, p_pais, p_tipo) {');
    ShowHTML('  var theForm = document.FormDetalhe;');
    ShowHTML('  theForm.p_projeto.value = p_projeto;');
    ShowHTML('  theForm.p_pais.value = p_pais;');
    ShowHTML('  theForm.p_sq_orprior.value = p_tipo;');
    ShowHTML('  theForm.submit();');
    ShowHTML('  return true;');
    ShowHTML('}');
    ValidateOpen('Validacao');
    Validate('p_inicio', 'Ano inicial', '', '1', '4', '4', '', '0123456789');
    Validate('p_fim', 'Ano final', '', '1', '4', '4', '', '0123456789');
    CompValor('p_inicio', 'Ano inicial', '<=', 'p_fim', 'Ano final');
    CompValor('p_inicio', 'Ano inicial', '>=', '2018', '2018');
    CompValor('p_fim', 'Ano final', '<=', Date('Y'), Date('Y'));
    ValidateClose();
    ScriptClose();
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    ShowHTML('</HEAD>');
    if ($O == 'L') {
      BodyOpenClean('onLoad="this.focus()";');
      CabecalhoRelatorio($w_cliente, f($RS_Menu,'nome'), 4, $w_chave);
    } else {
      BodyOpen('onLoad="document.focus()";');
      ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</font></B>');
    }
    ShowHTML('<HR>');

    
    ShowHTML('<div align="center">');
    // Formulário para definição do período de exibição
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('         <b>Período <input type="text" name="p_inicio" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.nvl($p_inicio,Date('Y')).'">');
    ShowHTML('         a <input type="text" name="p_fim" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.nvl($p_fim,Date('Y')).'">');
    ShowHTML('         <input class="STB" type="submit" name="Botao" value="Exibir"></b>');
    ShowHTML('</FORM>');
    
    // Formulário para tela de detalhamento
    AbreForm('FormDetalhe', $w_dir . $w_pagina . 'DETALHE', 'POST', null, 'detalhe', $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    if (!$_REQUEST['p_inicio']) ShowHTML('<INPUT type="hidden" name="p_inicio" value="'.$p_inicio.'">');
    if (!$_REQUEST['p_fim'])    ShowHTML('<INPUT type="hidden" name="p_fim" value="'.$p_fim.'">');
    ShowHTML('<INPUT type="hidden" name="p_projeto" value="">');
    ShowHTML('<INPUT type="hidden" name="p_pais" value="">');
    ShowHTML('<INPUT type="hidden" name="p_sq_orprior" value="">');
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('</FORM>');
    ShowHTML('</div>');

    ShowHTML('<HR>');
  }
  ShowHTML('<div align="center"><table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    $w_filtro = '';
    
    ShowHTML('<tr><td align="left" colspan=2>');
    if ($w_filtro > '') ShowHTML('<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>' . $w_filtro . '</ul></tr></table>');

    $l_html = '';

    $l_html .= chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="99%">';
    
    $l_html.=chr(13).'      <tr><td align="center">';
    $l_html.=chr(13).'        <table class="tudo" border="1" bordercolor="#00000">';
    $l_html.=chr(13).'          <tr align="center">';
    $l_html.=chr(13).'            <td colspan="5" bgColor="#f0f0f0"><b>VALORES POR PAÍS</td>';
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'          <tr align="center">';
    $cs++; $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0" width="20%%"><b>País</td>';
    $cs++; $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0" width="20%%"><b>Projeto</td>';
    $cs++; $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0" width="20%%"><b>Gasto</td>';
    $cs++; $l_html.=chr(13).'            <td colspan="2" bgColor="#f0f0f0"><b>Valor</td>';
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'          <tr align="center" >';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0" width="20%%"><b>Moeda Projeto</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0" width="20%%"><b>USD</td>';
    $l_html.=chr(13).'          </tr>';      
    $w_cor=$conTrBgColor;
    $w_total_previsto  = 0;
    $i = $j = $k = 0;
    foreach ($rel_pais as $pais => $v) {
      if  ($w_cor=='#d0d0d0') $w_cor='#e0e0e0'; else  $w_cor='#d0d0d0';
      $l_html.=chr(13).'      <tr valign="top">';
      $l_html.=chr(13).'          <td rowspan="'.$v[rows].'" style="vertical-align:middle" bgcolor="'.$w_cor.'" align="center"><span style="cursor:pointer;" onClick="montaLink(null,'.$v['chave'].',null)">'.$pais.'</span>';
      
      $i = 0;
      foreach($v['prj'] as $projeto => $x) {
        if ($i++) $l_html.=chr(13).'      <tr valign="top">';
        $l_html.=chr(13).'          <td nowrap rowspan="'.$x[rows].'" style="vertical-align:middle" title="'.$x['titulo'].'"><span style="cursor:pointer;" onClick="montaLink('.$x['chave'].','.$v['chave'].',null)">'.$projeto.' ('.$x['moeda'].')</span>';

        $j = 0;
        foreach($x['tipo'] as $tipo => $y) {
          if ($j++) $l_html.=chr(13).'      <tr valign="top">';
          $l_html.=chr(13).'          <td><span style="cursor:pointer;" onClick="montaLink('.$x['chave'].','.$v['chave'].','.$y['chave'].')">'.$tipo.'</span>';
          $l_html.=chr(13).'          <td align="right">'.formatNumber($y['vl_projeto']);
          $l_html.=chr(13).'          <td align="right">'.formatNumber($y['vl_unificado']);
        }
        $l_html.=chr(13).'      <tr valign="top">';
        $l_html.=chr(13).'          <td align="right"><b>Totais Projeto</b></td>';
        $l_html.=chr(13).'          <td align="right">'.formatNumber($x['vl_projeto']);
        $l_html.=chr(13).'          <td align="right">'.formatNumber($x['vl_unificado']);
        
      }

      $l_html.=chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
      $l_html.=chr(13).'          <td align="right" colspan="3"><b>Total País</b></td>';
      $l_html.=chr(13).'          <td align="right">'.formatNumber($v['vl_unificado']);
    } 

    $l_html.=chr(13).'          </tr>';
    if ($p_inicio=='2018') $l_html.=chr(13).'          <tr><td colspan=5 align="center"><b>Observação: Ano 2018 alimentado parcialmente.</b></tr></td>';
    $l_html.=chr(13).'        </table></td></tr>';
    
    /*
     * Exibe valores por projeto
     */
    $l_html.=chr(13).'      <tr><td align="center"><p>&nbsp;</p>';
    $l_html.=chr(13).'        <table class="tudo" border="1" bordercolor="#00000">';
    $l_html.=chr(13).'          <tr align="center">';
    $l_html.=chr(13).'            <td colspan="5" bgColor="#f0f0f0"><b>VALORES POR PROJETO</td>';
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'          <tr align="center">';
    $cs++; $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0" width="20%%"><b>Projeto</td>';
    $cs++; $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0" width="20%%"><b>País</td>';
    $cs++; $l_html.=chr(13).'            <td rowspan="2" bgColor="#f0f0f0" width="20%%"><b>Gasto</td>';
    $cs++; $l_html.=chr(13).'            <td colspan="2" bgColor="#f0f0f0"><b>Valor</td>';
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'          <tr align="center" >';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0" width="20%%"><b>Moeda Projeto</td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0" width="20%%"><b>USD</td>';
    $l_html.=chr(13).'          </tr>';      
    $w_cor=$conTrBgColor;
    $w_total_previsto  = 0;
    $i = $j = $k = 0;
    foreach ($rel_proj as $projeto => $v) {
      if  ($w_cor=='#d0d0d0') $w_cor='#e0e0e0'; else  $w_cor='#d0d0d0';
      $l_html.=chr(13).'      <tr valign="top">';
      $l_html.=chr(13).'          <td nowrap rowspan="'.$v[rows].'" style="vertical-align:middle" bgcolor="'.$w_cor.'" title="'.$v['titulo'].'"><span style="cursor:pointer;" onClick="montaLink('.$v['chave'].',null,null)">'.$projeto.' ('.$v['moeda'].')</span>';
      
      $i = 0;
      foreach($v['pais'] as $pais => $x) {
        if ($i++) $l_html.=chr(13).'      <tr valign="top">';
        $l_html.=chr(13).'          <td rowspan="'.$x[rows].'" style="vertical-align:middle" align="center"><span style="cursor:pointer;" onClick="montaLink('.$v['chave'].','.$x['chave'].',null)">'.$pais.'</span>';

        $j = 0;
        foreach($x['tipo'] as $tipo => $y) {
          if ($j++) $l_html.=chr(13).'      <tr valign="top">';
          $l_html.=chr(13).'          <td><span style="cursor:pointer;" onClick="montaLink('.$v['chave'].','.$x['chave'].','.$y['chave'].')">'.$tipo.'</span>';
          $l_html.=chr(13).'          <td align="right">'.formatNumber($y['vl_pais']);
          $l_html.=chr(13).'          <td align="right">'.formatNumber($y['vl_unificado']);
        }
        $l_html.=chr(13).'      <tr valign="top">';
        $l_html.=chr(13).'          <td align="right"><b>Totais País</b></td>';
        $l_html.=chr(13).'          <td align="right">'.formatNumber($x['vl_pais']);
        $l_html.=chr(13).'          <td align="right">'.formatNumber($x['vl_unificado']);
        
      }

      $l_html.=chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
      $l_html.=chr(13).'          <td colspan="2"><b>Totais Projeto</b></td>';
      $l_html.=chr(13).'          <td align="right">'.formatNumber($v['vl_projeto']);
      $l_html.=chr(13).'          <td align="right">'.formatNumber($v['vl_unificado']);
    } 

    $l_html.=chr(13).'          </tr>';
    if ($p_inicio=='2018') $l_html.=chr(13).'          <tr><td colspan=5 align="center"><b>Observação: Ano 2018 alimentado parcialmente.</b></tr></td>';
    $l_html.=chr(13).'        </table></td></tr>';
    $l_html.=chr(13).'</table>';

    ShowHTML($l_html);
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</div>');

  if($w_tipo=='PDF') RodapePdf();
  else               Rodape();
}

// =========================================================================
// Rotina de detalhamento do informe executivo
// -------------------------------------------------------------------------
function Detalhe() {
  extract($GLOBALS);
  global $w_Disabled;

  // Recupera todos os dados do projeto e rubrica
  $sql = new db_getSolicFN; $RSQuery = $sql->getInstanceOf($dbms,null,$w_usuario,'INFEXECLANC',$P1,
        $p_ini_i,$p_ini_f,$w_inicio,$w_fim,$p_atraso,$p_solicitante,$p_unidade,
        $p_prioridade,$p_ativo,$p_proponente,$p_chave, $p_objeto, $p_pais, $p_regiao, 
        $p_uf, $p_cidade, $p_usu_resp,$p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, 
        $p_projeto, null, null, $p_sq_orprior);
  
  cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Relatório</TITLE>');
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</HEAD>');
  BodyOpenClean('onLoad=\'this.focus()\';'); 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<hr/>');
  ShowHTML('<div align=center>');
  ShowHTML('<tr><td colspan="2"><table border="0" width="100%">');
  //ShowHTML('<tr><td colspan="2"><hr NOSHADE color=#000000 size=2></td></tr>');
  //ShowHTML('   <tr><td colspan="2" bgcolor="#f0f0f0"><div align=justify>Projeto:<b> '.$w_projeto.'</b></div></td></tr>');
  //ShowHTML('   <tr><td colspan="2" bgcolor="#f0f0f0"><div align=justify>Rubrica:<b> '.f($RS_Rubrica,'codigo').' - '.f($RS_Rubrica,'nome').' </b></div></td></tr>');
  //ShowHTML('<tr><td colspan="2"><hr NOSHADE color=#000000 size=2></td></tr>');

  $w_filtro = '';
  if ($p_inicio!='')    $w_filtro = $w_filtro . '<tr valign="top"><td align="right">Pagamento realizado de <td><b>' . $p_inicio . '</b> até <b>' . $p_fim . '</b>';
  if ($p_projeto>'') {
    $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
    if ($w_tipo=='WORD') {
      $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b>'.f($RS,'titulo').'</b>]';
    } else {
      $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações do projeto." target="_blank">'.f($RS,'titulo').'</a></b>]';
    }
  } 
  if ($p_pais>'') {
    $sql = new db_getCountryData; $RS = $sql->getInstanceOf($dbms,$p_pais);
    $w_filtro .= '<tr valign="top"><td align="right">País <td>[<b>'.f($RS,'nome').'</b>]';
  } 
  if ($p_sq_orprior>''){
    $sql = new db_getTipoLancamento; $RS = $sql->getInstanceOf($dbms,$p_sq_orprior,null,$w_cliente,null);
    foreach($RS as $row) {$RS = $row; break; }
    $w_filtro .= '<tr valign="top"><td align="right">Tipo do lançamento <td>[<b>'.f($RS,'nome').'</b>]';
  } 
  ShowHTML('<tr><td align="left" colspan=2>');
  if ($w_filtro > '') ShowHTML('<table border=0>' . $w_filtro . '</table>');

  ShowHTML('<tr><td><a accesskey="F" class="ss" HREF="javascript:this.status.value;" onClick="window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
  ShowHTML('        <td align="right">'.exportaOffice().'<b>Registros: '.count($RSQuery));
  ShowHTML('<tr><td align="center" colspan=2>');
  ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="1" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
  if ($w_tipo!='WORD') {
    ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('País','nm_pais').'</td>');
    ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('Projeto','cd_projeto').'</td>');
    ShowHTML('          <td rowspan="2"><b>'.LinkOrdena('Gasto','ds_tipo_lancamento').'</td>');
    ShowHTML('          <td rowspan="2"><b>Lançamento</td>');
    ShowHTML('          <td rowspan="2"><b>Descrição</td>');
    ShowHTML('          <td rowspan="2"><b>Pagamento</td>');
    ShowHTML('          <td colspan="3"><b>Valores</td>');
    ShowHTML('          <td colspan="4"><b>Cotações Banco Central do Brasil</td>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Financeiro</td>');
    ShowHTML('          <td><b>Projeto</td>');
    ShowHTML('          <td><b>Dólar</td>');
    ShowHTML('          <td><b>Data</td>');
    ShowHTML('          <td><b>BRL/USD</td>');
    ShowHTML('          <td><b>BRL/EUR</td>');
    ShowHTML('          <td><b>USD/EUR</td>');
  } else {
    ShowHTML('          <td rowspan="2"><b>País</td>');
    ShowHTML('          <td rowspan="2"><b>Projeto</td>');
    ShowHTML('          <td rowspan="2"><b>Gasto</td>');
    ShowHTML('          <td rowspan="2"><b>Lançamento</td>');
    ShowHTML('          <td rowspan="2"><b>Descrição</td>');
    ShowHTML('          <td colspan="3"><b>Valores</td>');
    ShowHTML('          <td colspan="3"><b>Cotações Banco Central do Brasil</td>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Financeiro</td>');
    ShowHTML('          <td><b>Projeto</td>');
    ShowHTML('          <td><b>Dólar</td>');
    ShowHTML('          <td><b>Data</td>');
    ShowHTML('          <td><b>BRL/USD</td>');
    ShowHTML('          <td><b>BRL/EUR</td>');
    ShowHTML('          <td><b>USD/EUR</td>');
  }
  ShowHTML('        </tr>');
  if (count($RSQuery)==0) {
    // Se não foram selecionados registros, exibe mensagem
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=13 align="center"><b>Não foram encontrados registros.</b></td></tr>');
  } else {
    if ($p_ordena>'') { 
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RSQuery = SortArray($RSQuery,$lista[0],$lista[1],'nm_pais','asc','nm_projeto','asc','ds_tipo_lancamento','asc','quitacao','asc');
    } else {
      $RSQuery = SortArray($RSQuery,'nm_pais','asc','nm_projeto','asc','ds_tipo_lancamento','asc','quitacao','asc');
    }
    $valor = 0;
    foreach ($RSQuery as $row) {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
      ShowHTML('        <td>'.f($row,'nm_pais').'</td>');
      ShowHTML('        <td title="'.f($row,'nm_projeto').'" nowrap>'.f($row,'cd_projeto').'</td>');
      ShowHTML('        <td>'.f($row,'ds_tipo_lancamento').'</td>');
      ShowHTML('        <td nowrap>'.exibeSolic($w_dir,f($row,'sq_financeiro'),f($row,'cd_financeiro'),'N',$w_tipo).'</td>');
      ShowHTML('        <td width="30%">'.f($row,'ds_financeiro').'</td>');
      ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'quitacao')).'</td>');
      ShowHTML('        <td align="right" nowrap>'.f($row,'fn_sb_moeda').' '.formatNumber(f($row,'fn_valor')).'</td>');
      ShowHTML('        <td align="right" nowrap>'.f($row,'sb_pj_moeda').' '.formatNumber(f($row,'vl_projeto')).'</td>');
      ShowHTML('        <td align="right" nowrap>US$ '.formatNumber(f($row,'vl_unificado')).'</td>');
      ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'data_cotacao')).'</td>');
      ShowHTML('        <td align="right" nowrap>'.nvl(formatNumber(f($row,'tx_brl_usd'),4),'???').'</td>');
      ShowHTML('        <td align="right" nowrap>'.nvl(formatNumber(f($row,'tx_brl_eur'),4),'???').'</td>');
      ShowHTML('        <td align="right" nowrap>'.nvl(formatNumber(f($row,'tx_eur_usd'),4),'???').'</td>');
      ShowHTML('      </tr>');
      $valor += f($row,'vl_unificado');
    } 
    $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
    ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
    ShowHTML('        <td align="right" colspan="8"><b>Total&nbsp;</b></td>');
    ShowHTML('        <td align="right" nowrap><b>US$ '.formatNumber($valor)).'</b></td>';
    ShowHTML('        <td align="right" colspan="4">&nbsp;</td>');
    ShowHTML('      </tr>');
  } 
  ShowHTML('    </table>');
  ShowHTML('  </td>');
  ShowHTML('</tr>');
  ShowHTML('<tr><td colspan=2>Observações:');
  ShowHTML('    <ul>');
  ShowHTML('      <li>As células indicadas com "???" não têm cotação do Banco Central do Brasil informada. Use as opções "Cotações" ou "Importar cotações Banco Central" existentes em "Financeiro - Tabelas" para atualizar os dados.');
  ShowHTML('      <li>A conversão para dólar segue as regras abaixo:');
  ShowHTML('        <ol>');
  ShowHTML('          <li>Se foi informado valor em dólar na conclusão do lançamento, ele será usado.');
  ShowHTML('          <li>Se foi informado apenas valor em real na conclusão do lançamento, a conversão será feita usando a taxa BRL/USD exibida na listagem acima.');
  ShowHTML('          <li>Se foi informado apenas valor em euro na conclusão do lançamento, a conversão será feita usando a taxa USD/EUR exibida na listagem acima.');
  ShowHTML('        </ol>');
  ShowHTML('      <li>Os casos (2) e (3), acima, sempre usarão a taxa do dia anterior ao da coluna "Pagamento".');
  ShowHTML('          Por isso, <b><u>o valor do lançamento só será computado se as colunas BRL/USD e BRL/EUR forem diferentes de "???"</b></u>.');
  ShowHTML('    </ul>');

  ShowHTML('</table>');
  
  Rodape();
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'INICIAL': Inicial(); break;
    case 'DETALHE': Detalhe(); break;
    default:
      cabecalho();
      ShowHTML('<BASE HREF="' . $conRootSIW . '">');
      BodyOpen('onLoad=this.focus();');
      Estrutura_Topo_Limpo();
      Estrutura_Menu();
      Estrutura_Corpo_Abre();
      Estrutura_Texto_Abre();
      ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
      Estrutura_Texto_Fecha();
      Estrutura_Fecha();
      Estrutura_Fecha();
      Estrutura_Fecha();
      Rodape();
  }
}
?>
