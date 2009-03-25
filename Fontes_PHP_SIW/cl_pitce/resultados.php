<?php
header('Expires: ' .-1500);
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
include_once($w_dir_volta.'classes/sp/db_getSolicResultado.php');
include_once($w_dir_volta.'classes/sp/db_getEtapaAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoPrograma.php');
include_once($w_dir_volta.'visualalerta.php');
// =========================================================================
//  trabalho.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerencia a atualização das tabelas do sistema
// Mail     : alex@sbpi.com.br
// Criacao  : 24/03/2003 16:55
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
if ($_SESSION['LOGON'] != 'Sim') {
  EncerraSessao();
}

// Declaração de variáveis
$dbms = abreSessao :: getInstanceOf($_SESSION['DBMS']);

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par = strtoupper($_REQUEST['par']);
$P1 = $_REQUEST['P1'];
$P2 = $_REQUEST['P2'];
$P3 = $_REQUEST['P3'];
$P4 = $_REQUEST['P4'];
$TP = $_REQUEST['TP'];
$SG = strtoupper($_REQUEST['SG']);
$R = $_REQUEST['R'];
$O = strtoupper($_REQUEST['O']);

$p_programa    = $_REQUEST['p_programa'];
$p_projeto     = $_REQUEST['p_projeto'];
$p_solicitante = strtoupper($_REQUEST['p_solicitante']);
$p_unidade     = strtoupper($_REQUEST['p_unidade']);
$p_texto       = $_REQUEST['p_texto'];
$p_ordena      = strtolower($_REQUEST['p_ordena']);
$p_atrasado    = $_REQUEST['p_atrasado'];
$p_adiantado   = $_REQUEST['p_adiantado'];
$p_concluido   = $_REQUEST['p_concluido'];

$w_assinatura = strtoupper($_REQUEST['w_assinatura']);
$w_pagina = 'resultados.php?par=';
$w_Disabled = 'ENABLED';
$w_dir = 'cl_pitce/';

$w_cliente = RetornaCliente();
$w_usuario = RetornaUsuario();
$w_ano     = RetornaAno();
$w_mes     = $_REQUEST['w_mes'];

// Configura variáveis para montagem do calendário
if (nvl($w_mes, '') == '')    $w_mes = date('m', time());
$w_inicio = first_day(toDate('01/'.substr(100 + (intVal($w_mes) - 1), 1, 2).'/'.$w_ano));
$w_fim = last_day(toDate('01/'.substr(100 + (intVal($w_mes) + 1), 1, 2).'/'.$w_ano));
$w_mes1 = substr(100 + intVal($w_mes) - 1, 1, 2);
$w_mes3 = substr(100 + intVal($w_mes) + 1, 1, 2);
$w_ano1 = $w_ano;
$w_ano3 = $w_ano;
// Ajusta a mudança de ano
if ($w_mes1 == '00') {
  $w_mes1 = '12';
  $w_ano1 = $w_ano -1;
}
if ($w_mes3 == '13') {
  $w_mes3 = '01';
  $w_ano3 = $w_ano +1;
}

if ($O == '') $O = 'L';

switch ($O) {
  case 'I' : $w_TP = $TP.' - Inclusão';   break;
  case 'A' : $w_TP = $TP.' - Alteração';  break;
  case 'E' : $w_TP = $TP.' - Exclusão';   break;
  case 'V' : $w_TP = $TP.' - Envio';      break;
  case 'P' : $w_TP = $TP.' - Filtragem';  break;
  default  : $w_TP = $TP.' - Listagem';
}

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Exibe Resultados da PDP
// -------------------------------------------------------------------------
function Inicial() {

  extract($GLOBALS);
  
  $w_tipo=$_REQUEST['w_tipo'];
  
  if ($w_tipo=='PDF') {
    headerpdf('Visualização de resultados',$w_pag);
    $w_embed = 'WORD';
  } elseif ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Visualização de resultados',0);
    $w_embed = 'WORD';
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.';">');
    ScriptOpen('Javascript');
    ValidateOpen('Validacao');
    Validate('p_texto','Texto','','',3,50,'1','1');
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=this.focus();');
    //if ($w_tipo!='WORD') CabecalhoRelatorio($w_cliente,'Visualização de '.f($RS_Menu,'nome'),4,$w_chave);
    $w_embed="HTML";
    ShowHTML('<table border="0" width="100%">');
    ShowHTML('<tr><td><b><FONT COLOR="#000000"><font size=2>'.$w_TP.'</font></b>');
    ShowHTML('<tr><td><hr>');
    ShowHTML(' <fieldset><table width="100%" bgcolor="'.$conTrBgColor.'">');
    AbreForm('Form', $w_dir.$w_pagina.$par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, null, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="p_pesquisa" value="">');
    ShowHTML('<INPUT type="hidden" name="w_mes" value="'.$w_mes.'">');
    ShowHTML('   <tr>');
    selecaoPrograma('<u>M</u>acroprograma', 'R', 'Se desejar, selecione um dos macroprogramas.', $p_programa, $p_plano, $p_objetivo, 'p_programa', null, 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.target=\'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_projeto\'; document.Form.submit();"',1,null,'<td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr>');
    $RS = db_getLinkData :: getInstanceOf($dbms, $w_cliente, 'PJCAD');
    SelecaoProjeto('<u>P</u>rograma', 'P', 'Selecione um item na relação.', $p_projeto, $w_usuario, f($RS, 'sq_menu'), $p_programa, $p_objetivo, $p_plano, 'p_projeto', 'PJLIST', null, null, null, '<td>');
    ShowHTML('   </tr>');
    //ShowHTML('                                                 <input '.$w_Disabled.' accesskey="P" type="text" name="p_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim').'</td>');
    ShowHTML('   <tr>');
    SelecaoUnidade('<u>E</u>ntidade executora', 'E', null, $p_unidade, null, 'p_unidade', null, null, null, '<td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr>');
    SelecaoPessoa('Res<u>p</u>onsável', 'D', 'Selecione o responsável pela atualização do item na relação.', $p_solicitante, null, 'p_solicitante', 'USUARIOS',null,null,'<td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr><td><b>Pesquisa por <u>t</u>exto</b></td>');
    ShowHTML('   <td><input class="STI" accesskey="T" type="text" size="50" maxlength="50" name="p_texto" value="'. $p_texto .'"></td>');
    ShowHTML('   </tr>');
    ShowHTML('   <tr><td><b>Recuperar apenas</b></td>');
    ShowHTML('       <td>');
    ShowHTML('     <input type="checkbox" '.((nvl($p_atrasado,'')!='') ? 'checked' : '').'  name="p_atrasado"  value="1" />Atrasados');
    ShowHTML('     <input type="checkbox" '.((nvl($p_adiantado,'')!='') ? 'checked' : '').' name="p_adiantado" value="1" />Concluídos antes do prazo ');
    ShowHTML('     <input type="checkbox" '.((nvl($p_concluido,'')!='') ? 'checked' : '').' name="p_concluido" value="1" /> Concluídos no prazo');
    ShowHTML('   </td></tr>');
    ShowHTML('   <tr><td>&nbsp;</td>');
    ShowHTML('       <td>');
    ShowHTML('       <input class="STB" type="submit" name="Botao" value="BUSCAR" onClick="document.Form.target=\'\'; javascript:document.Form.O.value=\'L\'; javascript:document.Form.p_pesquisa.value=\'S\';">');
    $RS_Volta = db_getLinkData :: getInstanceOf($dbms, $w_cliente, 'MESA');
    ShowHTML('       <input class="STB" type="button" name="Botao" value="VOLTAR" onClick="javascript:location.href=\''.$conRootSIW.f($RS_Volta, 'link').'&P1='.f($RS_Volta, 'p1').'&P2='.f($RS_Volta, 'p2').'&P3='.f($RS_Volta, 'p3').'&P4='.f($RS_Volta, 'p4').'&TP=<img src='.f($RS_Volta, 'imagem').' BORDER=0>'.f($RS_Volta, 'nome').'&SG='.f($RS_Volta, 'sigla').'\';">');
    ShowHTML('   </td></tr>');
    ShowHTML('</FORM>');
    ShowHTML(' </table></fieldset>');
    // Exibe o calendário da organização
    include_once($w_dir_volta.'classes/sp/db_getDataEspecial.php');
    for ($i = $w_ano1; $i <= $w_ano3; $i++) {
      $RS_Ano[$i] = db_getDataEspecial :: getInstanceOf($dbms, $w_cliente, null, $i, 'S', null, null, null);
      $RS_Ano[$i] = SortArray($RS_Ano[$i], 'data_formatada', 'asc');
    }
  
    // Recupera os dados da unidade de lotação do usuário
    include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
    $RS_Unidade = db_getUorgData :: getInstanceOf($dbms, $_SESSION['LOTACAO']);
  
    if (nvl($w_viagem, '') != '') {
      $RSMenu_Viagem = db_getLinkData :: getInstanceOf($dbms, $w_cliente, 'PDINICIAL');
      $RS_Viagem = db_getSolicList :: getInstanceOf($dbms, f($RSMenu_Viagem, 'sq_menu'), $w_usuario, 'PD', 4, formataDataEdicao($w_inicio), formataDataEdicao($w_fim), null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, $w_usuario);
      $RS_Viagem = SortArray($RS_Viagem, 'inicio', 'desc', 'fim', 'desc');
  
      // Cria arrays com cada dia do período, definindo o texto e a cor de fundo para exibição no calendário
      foreach ($RS_Viagem as $row) {
        $w_saida = f($row,'phpdt_saida');
        $w_chegada = f($row,'phpdt_chegada');
        if (f($row,'concluida') == 'S') {
          retornaArrayDias(f($row,'phpdt_saida'), f($row,'phpdt_chegada'), & $w_datas, 'Viagem a serviço\r\nSituação: Finalizada', 'N');
        }
        elseif (f($row,'sg_tramite') == 'AE' || f($row,'sg_tramite') == 'EE') {
          retornaArrayDias(f($row,'phpdt_saida'), f($row,'phpdt_chegada'), & $w_datas, 'Viagem a serviço\r\nSituação: Confirmada', 'N');
        } else {
          retornaArrayDias(f($row,'phpdt_saida'), f($row,'phpdt_chegada'), & $w_datas, 'Viagem a serviço\r\nSituação: Prevista', 'N');
        }
        $w_datas[formataDataEdicao($w_saida)]['valor'] = str_replace('serviço', 'serviço (saída às '.date('H:i', $w_saida).'h)', $w_datas[formataDataEdicao($w_saida)]['valor']);
        $w_datas[formataDataEdicao($w_chegada)]['valor'] = str_replace('serviço', 'serviço (chegada às '.date('H:i', $w_chegada).'h)', $w_datas[formataDataEdicao($w_chegada)]['valor']);
      }
      reset($RS_Viagem);
      foreach ($RS_Viagem as $row) {
        $w_saida = f($row,'phpdt_saida');
        $w_chegada = f($row,'phpdt_chegada');
        retornaArrayDias(f($row,'phpdt_saida'), f($row,'phpdt_chegada'), & $w_cores, $conTrBgColorLightRed1, 'N');
        if (date('H', $w_saida) > 13)
          $w_cores[formataDataEdicao($w_saida)]['valor'] = $conTrBgColorLightRed2;
        if (date('H', $w_chegada) < 14)
          $w_cores[formataDataEdicao($w_chegada)]['valor'] = $conTrBgColorLightRed2;
      }
    }
  
    // Verifica a quantidade de colunas a serem exibidas
    $w_colunas = 1;
  
    // Configura a largura das colunas
    switch ($w_colunas) {
      case 1  : $width = "100%"; break;
      case 2  : $width = "50%";  break;
      case 3  : $width = "33%";  break;
      case 4  : $width = "25%";  break;
      default : $width = "100%";
    }
  
    ShowHTML('        <table width="100%" border="0" align="center" CELLSPACING=0 CELLPADDING=0 BorderColorDark='.$conTableBorderColorDark.' BorderColorLight='.$conTableBorderColorLight.'><tr valign="top">');
  
    // Exibe calendário e suas ocorrências ==============
    ShowHTML('          <td width="'.$width.'" align="center"><table border="1" cellpadding=0 cellspacing=0>');
    ShowHTML('            <tr><td colspan=3 width="100%"><table width="100%" border=0 cellpadding=0 cellspacing=0><tr>');
    ShowHTML('              <td bgcolor="'.$conTrBgColor.'"><A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&w_mes='.$w_mes1.'&w_ano='.$w_ano1.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'"><<<</A>');
    ShowHTML('              <td align="center" bgcolor="'.$conTrBgColor.'"><b>Calendário '.f($RS_Cliente, 'nome_resumido').' ('.f($RS_Unidade, 'nm_cidade').')</td>');
    ShowHTML('              <td align="right" bgcolor="'.$conTrBgColor.'"><A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&w_mes='.$w_mes3.'&w_ano='.$w_ano3.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'">>>></A>');
    ShowHTML('              </table>');
    // Variáveis para controle de exibição do cabeçalho das datas especiais
    $w_detalhe1 = false;
    $w_detalhe2 = false;
    $w_detalhe3 = false;
    ShowHTML('            <tr valign="top">');
    ShowHTML('              <td align="center">'.montaCalendario($RS_Ano[$w_ano1], $w_mes1.$w_ano1, $w_datas, $w_cores, & $w_detalhe1).' </td>');
    ShowHTML('              <td align="center">'.montaCalendario($RS_Ano[$w_ano], $w_mes.$w_ano, $w_datas, $w_cores, & $w_detalhe2).' </td>');
    ShowHTML('              <td align="center">'.montaCalendario($RS_Ano[$w_ano3], $w_mes3.$w_ano3, $w_datas, $w_cores, & $w_detalhe3).' </td>');
  
    if ($w_detalhe1 || $w_detalhe2 || $w_detalhe3) {
      ShowHTML('            <tr><td colspan=3 bgcolor="'.$conTrBgColor.'">');
      ShowHTML('              <b>Clique sobre o dia em destaque para ver detalhes.</b>');
    }
  
    // Exibe informações complementares sobre o calendário
    ShowHTML('            <tr valign="top" bgcolor="'.$conTrBgColor.'">');
    ShowHTML('              <td colspan=3 align="center">');
    if ($w_detalhe1 || $w_detalhe2 || $w_detalhe3) {
      ShowHTML('                <table width="100%" border="0" cellspacing=1>');
      if (count($RS_Ano) == 0) {
        ShowHTML('                  <tr valign="top"><td align="center">&nbsp;');
      } else {
        ShowHTML('                  <tr valign="top"><td align="center"><b>Data<td><b>Ocorrências');
        reset($RS_Ano);
        for ($i = $w_ano1; $i <= $w_ano3; $i++) {
          $RS_Ano_Atual = $RS_Ano[$i];
          foreach ($RS_Ano_Atual as $row_ano) {
            // Exibe apenas as ocorrências do trimestre selecionado
            if (f($row_ano, 'data_formatada') >= $w_inicio && f($row_ano, 'data_formatada') <= $w_fim) {
              ShowHTML('                  <tr valign="top">');
              ShowHTML('                    <td align="center">'.date(d.'/'.m, f($row_ano, 'data_formatada')));
              ShowHTML('                    <td>'.f($row_ano, 'nome'));
            }
          }
        }
        ShowHTML('              </table>');
      }
    }
    ShowHTML('          </table>');
    // Final da exibição do calendário e suas ocorrências ==============
    ShowHTML('</table>');
    
  }
  if($_REQUEST['p_pesquisa'] == 'S'){
    $RS_Resultado = db_getSolicResultado :: getInstanceOf($dbms,$w_cliente,$p_programa,$p_projeto,$p_unidade,$p_solicitante,$p_texto,formataDataEdicao($w_inicio),formataDataEdicao($w_fim), $p_atrasado, $p_adiantado, $p_concluido,null,null,'LISTA');
    if ($p_ordena>'') { 
  $lista = explode(',',str_replace(' ',',',$p_ordena));
  $RS_Resultado = SortArray($RS_Resultado,$lista[0],$lista[1],'mes_ano','desc','cd_programa','asc', 'cd_projeto','asc','titulo','asc');
} else {
  $RS_Resultado = SortArray($RS_Resultado,'mes_ano','desc','cd_programa','asc', 'cd_projeto','asc','titulo','asc');
}
    ShowHTML('<table width="100%">');
    ShowHTML('<tr><td align="right" colspan="2">');      
    if ($w_embed!='WORD') {
      CabecalhoRelatorio($w_cliente,'Visualização de resultados',4,$w_chave,null);
    }
    ShowHTML('<tr><td align="right" colspan="2"><b>Resultados: '.count($RS_Resultado).' </b></td></tr>');
    ShowHTML('<tr><td align="center" colspan=2>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    if($w_embed != 'WORD'){
      ShowHTML('          <td nowrap><b>&nbsp;'.linkOrdena('Data','mes_ano').'&nbsp;</td>');
      ShowHTML('          <td nowrap><b>&nbsp;'.linkOrdena('Macro-<br>programa','cd_programa').'&nbsp;</td>');
      ShowHTML('          <td><b>&nbsp;'.linkOrdena('Programa','cd_projeto').'&nbsp;</td>');
      ShowHTML('          <td nowrap><b>&nbsp;'.linkOrdena('Situação atual','titulo').'&nbsp;</td>');
      ShowHTML('          <td nowrap><b>&nbsp;Ação&nbsp;</td>');
    }else{
      ShowHTML('          <td nowrap><b>&nbsp;Data&nbsp;</td>');
      ShowHTML('          <td nowrap><b>&nbsp;Macro-<br>programa&nbsp;</td>');
      ShowHTML('          <td nowrap><b>&nbsp;Programa&nbsp;</td>');
      ShowHTML('          <td nowrap><b>&nbsp;Situação atual&nbsp;</td>');  
    }      
    ShowHTML('        </tr>');
    $w_cor = $conTrBgColor;
  
    if (count($RS_Resultado) == 0) {
      ShowHTML('    <tr align="center"><td colspan="5">Nenhum resultado encontrado para os critérios informados.</td>');
    } else {
      foreach ($RS_Resultado as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('    <tr valign="top" bgColor="'.$w_cor.'">');
        ShowHTML('      <td align="center" width="1%" nowrap>'.Date('d/m/Y', Nvl(f($row,'mes_ano'), '---')).'</td>');
        ShowHTML('      <td align="center" width="1%" title="'.f($row,'nm_programa').'" nowrap>'.Nvl(f($row,'cd_programa'), '---').'</td>');
        ShowHTML('      <td align="center" width="1%" title="'.f($row,'nm_projeto').'" nowrap>'.Nvl(f($row,'cd_projeto'), '---').'</td>');
        if($w_embed != 'WORD'){
          ShowHTML('      <td title="'.f($row,'descricao').'">'.ExibeImagemSolic('ETAPA',f($row,'inicio_previsto'),f($row,'fim_previsto'),f($row,'inicio_real'),f($row, 'fim_real'),null,null,null,f($row, 'perc_conclusao')).'&nbsp;'.f($row,'titulo').'</td>');
          ShowHTML('      <td nowrap><A target="item" class="HL" href="cl_pitce/projeto.php?par=atualizaetapa&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_chave_aux='.f($row,'sq_projeto_etapa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe dados do item">Exibir</A></td>');
          ShowHTML('    </tr>');
        }else{
          ShowHTML('      <td title="'.f($row,'descricao').'">'.ExibeImagemSolic('ETAPA',f($row,'inicio_previsto'),f($row,'fim_previsto'),f($row,'inicio_real'),f($row, 'fim_real'),null,null,null,f($row, 'perc_conclusao')).'&nbsp;'.f($row,'titulo').'<br/><b>Situação:</b><br/>'.f($row,'descricao').' </td>');
          ShowHTML('    </tr>');          
        }
      }

    ShowHTML('  </table>');
    ShowHTML('<tr><td>&nbsp;</td></tr>');        
    }
  }
  ShowHTML('</center>');
  if     ($w_tipo=='PDF')  RodapePDF();
  else if ($w_tipo!='WORD') Rodape();
}

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'INICIAL' : Inicial(); break;
    default :
      Cabecalho();
      BodyOpen('onLoad=this.focus();');
      Estrutura_Topo_Limpo();
      Estrutura_Menu();
      Estrutura_Corpo_Abre();
      Estrutura_Texto_Abre();
      ShowHTML('<center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center>');
      Estrutura_Texto_Fecha();
      Estrutura_Fecha();
      Estrutura_Fecha();
      Estrutura_Fecha();
      Rodape();
  }
}
?>