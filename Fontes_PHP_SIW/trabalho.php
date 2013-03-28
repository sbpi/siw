<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getDeskTop_TT.php');
include_once($w_dir_volta.'classes/sp/db_getUserModule.php');
include_once($w_dir_volta.'classes/sp/db_getDeskTop_Recurso.php');
include_once($w_dir_volta.'classes/sp/db_getDeskTop.php');
include_once($w_dir_volta.'classes/sp/db_getGPContrato.php');
include_once($w_dir_volta.'classes/sp/db_getAlerta.php');
include_once($w_dir_volta.'classes/sp/db_getIndicador.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicCL.php');
include_once($w_dir_volta.'classes/sp/db_getAfastamento.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getUserResp.php');
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
// Carrega variáveis locais com os dados dos parâmetros recebidos
$par        = upper($_REQUEST['par']);
$P1         = $_REQUEST['P1'];
$P2         = $_REQUEST['P2'];
$P3         = $_REQUEST['P3'];
$P4         = $_REQUEST['P4'];
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);

$w_assinatura   = $_REQUEST['w_assinatura'];
$w_pagina       = 'trabalho.php?par=';
$w_Disabled     = 'ENABLED';

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_ano      = RetornaAno();
$w_mes      = $_REQUEST['w_mes'];

// Configura variáveis para montagem do calendário
if (nvl($w_mes,'')=='') $w_mes = date('m',time());
$w_inicio  = first_day(toDate('01/'.substr(100+(intVal($w_mes)-1),1,2).'/'.$w_ano));
$w_fim     = last_day(toDate('01/'.substr(100+(intVal($w_mes)+1),1,2).'/'.$w_ano));
$w_mes1    = substr(100+intVal($w_mes)-1,1,2);
$w_mes3    = substr(100+intVal($w_mes)+1,1,2);
$w_ano1    = $w_ano;
$w_ano3    = $w_ano;
// Ajusta a mudança de ano
if ($w_mes1=='00') { $w_mes1 = '12'; $w_ano1 = $w_ano-1; }
if ($w_mes3=='13') { $w_mes3 = '01'; $w_ano3 = $w_ano+1; }

if ($O=='') $O='L';

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão'; break;
  case 'A': $w_TP=$TP.' - Alteração'; break;
  case 'E': $w_TP=$TP.' - Exclusão'; break;
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
  $sql = new db_getCustomerData; $RS_Cliente = $sql->getInstanceOf($dbms,$w_cliente);

  if ($O=="L") {
    // Verifica se o cliente tem o módulo de telefonia contratado
    $sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms, $w_cliente, null, null);
    foreach($RS as $row) {
      if (f($row,'sigla')=='TT') $w_telefonia = f($row,'nome');
      if ($_SESSION['INTERNO']=='S') {
        if (f($row,'sigla')=='GP') $w_pessoal    = f($row,'nome');
        if (f($row,'sigla')=='FN') $w_financeiro = f($row,'nome');
        if (f($row,'sigla')=='PD') $w_viagem     = f($row,'nome');
        if (f($row,'sigla')=='CO') $w_compras    = f($row,'nome');
      }
      $w_user[f($row,'sigla')] = false;
    }

    // Apenas para usuários internos da organização
    if ($_SESSION['INTERNO']=='S') {
      // Verifica se há algum indicador com aferição
      $sql = new db_getIndicador; $RS_Indicador = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,null,null,null,null,null,'S',null,null,null,null,null,null,null,null,null,'TIPOINDIC');
      $RS_Indicador = SortArray($RS_Indicador,'nome','asc');
      if (count($RS_Indicador)>0) $w_indicador='S'; else $w_indicador='N';
      
      // Verifica os módulos que o usuário seja gestor
      $sql = new db_getUserModule; $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_usuario);
      foreach($RS as $row) {
        $w_user[f($row,'sigla')] = true;
      }
    }
  }
  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.';">');
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=\'this.focus()\';');
  ShowHTML('<table border="0" width="100%">');
  ShowHTML('<tr><td><b><FONT COLOR="#000000"><font size=2>'.$w_TP.'</font></b>');
  ShowHTML('    <td align="right">');
  
  // Se o módulo financeiro estiver habilitado e o usuário for gestor desse módulo, exibe link para a tela de tesouraria
  if (nvl($w_financeiro,'')!='' && $_SESSION['DBMS']!=8) {
    if ($w_user['FN']) {
      //ShowHTML('      <a HREF="javascript:this.status.value;" onClick="javascript:window.open(\''.montaURL_JS($w_dir,'mod_fn/tesouraria.php?par=inicial&O=L&TP='.$TP.' - Tesouraria').'\',\'Tesouraria\',\'toolbar=no,resizable=yes,width=780,height=550,top=20,left=10,scrollbars=yes\');" title="Clique para acessar a tela da tesouraria."><img src="'.$conImgFin.'" border=0></a></font></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
      ShowHTML('      <A HREF="mod_fn/tesouraria.php?par=inicial&O=L&TP='.$TP.' - Tesouraria" title="Clique para acessar a tela da tesouraria."><img src="'.$conImgFin.'" border=0></a></font></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
    }
  }
  
  // Se o módulo de pessoal estiver habilitado para o cliente, exibe link para acesso à folha de ponto
  if (nvl($w_pessoal,'')!='' && $_SESSION['DBMS']!=8) {
    // Verifica se o usuário tem contrato de trabalho  
    $sql = new db_getGPContrato; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,null,$w_usuario,null,null,null,null,null,null,null,null,null,null);
    
    // Verifica se é chefe de unidade
    $sql = new db_getUserResp; $RS2 = $sql->getInstanceOf($dbms,$w_usuario,null);
    
    if (count($RS1) || count($RS2)) {
      $w_erro = false;
      if (count($RS1)) {
        //Verifica se os horários da jornada diária foram preenchidos
        foreach($RS1 as $row) { $RS1 = $row; break; }
        if (nvl(f($row,'entrada_manha'),'')=='' && nvl(f($row,'entrada_tarde'),'')=='' && nvl(f($row,'entrada_noite'),'')=='') {
          $w_erro = true;
        }
      }
      
      if ($w_erro) {
        ShowHTML('      <a HREF="javascript:this.status.value;" onClick="alert(\'Jornada diária não informada no contrato. Entre em contato com os gestores de pessoal!\');" title="Pendente gestores de pessoal informarem jornada diária de trabalho no contrato."><img src="'.$conRootSIW.'images/relogio.gif" width=16 height=16 border=0></a></font></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
      } else {
        ShowHTML('      <a HREF="javascript:this.status.value;" onClick="javascript:window.open(\''.montaURL_JS($w_dir,'mod_rh/folha.php?par=inicial&O=L&SG=COINICIAL&TP='.$TP.' - Folha de ponto').'\',\'Folha\',\'toolbar=no,resizable=yes,width=780,height=550,top=20,left=10,scrollbars=yes\');" title="Clique para acessar a folha de ponto."><img src="'.$conRootSIW.'images/relogio.gif" width=16 height=16 border=0></a></font></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
      }
    }
  }

  // Se o georeferenciamento estiver habilitado para o cliente, exibe link para acesso à visualização
  if (f($RS_Cliente,'georeferencia')=='S') {
    ShowHTML('      <a HREF="javascript:this.status.value;" onClick="javascript:window.open(\''.montaURL_JS($w_dir,'mod_gr/exibe.php?par=inicial&O=L&TP='.$TP.' - Georeferenciamento').'\',\'Folha\',\'toolbar=no,resizable=yes,width=780,height=550,top=20,left=10,scrollbars=yes\');" title="Clique para visualizar os mapas georeferenciados."><img src="'.$conImgGeo.'" border=0></a></font></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
  }

  if ($_SESSION['DBMS']!=8) {
    // Exibe, se necessário, sinalizador para alerta
    $sql = new db_getAlerta; $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_usuario, 'SOLICGERAL', 'N', null);
    if (count($RS)>0) {
      $w_sinal = $conImgAlLow;
      $w_msg   = 'Clique para ver alertas de atraso e proximidade da data de conclusão.';
      foreach($RS as $row) {
        if ($w_usuario==f($row,'solicitante')) {
          $w_sinal = $conImgAlMed;
          $w_msg   = 'Há alertas nos quais sua você é o responsável ou o solicitante. Clique para vê-los.';
        }
        if ($w_usuario==nvl(f($row,'sq_exec'),f($row,'solicitante')))  {
          $w_sinal = $conImgAlHigh;
          $w_msg   = 'Há alertas nos quais sua intervenção é necessária. Clique para vê-los.';
          break;
        }
      }
      ShowHTML('      <a href="'.$w_pagina.'alerta&O=L&TP='.$TP.' - Alertas" title="'.$w_msg.'"><img src="'.$w_sinal.'" border=0></a></font></b>');
    }
  }

  ShowHTML('<tr><td colspan=2><hr>');
  ShowHTML('</table>');
  ShowHTML('<center>');
  ShowHTML('<table border="0" width="100%">');
  if ($O=="L") {
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor='.$conTableBgColor.' BORDER='.$conTableBorder.' CELLSPACING='.$conTableCellSpacing.' CELLPADDING='.$conTableCellPadding.' BorderColorDark='.$conTableBorderColorDark.' BorderColorLight='.$conTableBorderColorLight.'>');
    ShowHTML('        <tr bgcolor='.$conTrBgColor.' align="center">');
    if ($_SESSION['INTERNO']=='S') {
      ShowHTML('          <td rowspan=2><b>Módulo</td>');
      ShowHTML('          <td rowspan=2><b>Serviço</td>');
      ShowHTML('          <td colspan=2><b>Em andamento</td>');
      ShowHTML('        <tr bgcolor='.$conTrBgColor.' align="center">');
      ShowHTML('          <td><b>Consultar</td>');
      ShowHTML('          <td><b>Intervir</td>');
    } else {
      ShowHTML('          <td><b>Módulo</td>');
      ShowHTML('          <td><b>Serviço</td>');
      ShowHTML('          <td><b>Em andamento</td>');
    }
    ShowHTML('        </tr>');

    if ($_SESSION['DBMS']!=8) {
      $sql = new db_getDeskTop_Recurso; $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_usuario);
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        ShowHTML('        <td colspan=2 align="right"><b>'.f($row,'nm_opcao').'&nbsp;&nbsp;&nbsp;&nbsp;</b></td>');
        ShowHTML('        <td align="right"><A class="HL" HREF="'.f($row,'link').'&R='.$w_pagina.$par.'&O=L&P1='.f($row,'p1').'&P2='.f($row,'p2').'&P3='.f($row,'p3').'&P4='.f($row,'p4').'&TP='.f($row,'nm_opcao').'&SG='.f($row,'sigla').'&p_volta=mesa&p_acesso=T'.montaFiltro('GET').'">'.f($row,'qt_visao').'</A>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
        if (f($row,'qt_gestao')>0) {
          ShowHTML('        <td align="right"><A class="HL" HREF="'.f($row,'link').'&R='.$w_pagina.$par.'&O=L&P1='.f($row,'p1').'&P2='.f($row,'p2').'&P3='.f($row,'p3').'&P4='.f($row,'p4').'&TP='.f($row,'nm_opcao').'&SG='.f($row,'sigla').'&p_volta=mesa&p_acesso=I'.montaFiltro('GET').'">'.f($row,'qt_gestao').'</A>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
        } else {
          ShowHTML('        <td align="right">&nbsp;</td>');
        }
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }

    // Verifica se é necessário colocar as ligações telefônicas
    if ($w_telefonia>'' && $_SESSION['INTERNO']=='S') {
      $sql = new db_getDeskTop_TT; $RS = $sql->getInstanceOf($dbms, $w_usuario);
      foreach ($RS as $row) $w_telefonia_qtd=f($row,'existe');
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      if ($w_telefonia_qtd>0) $w_negrito='<b>'; else $w_negrito='';
      ShowHTML('      <tr bgcolor="'.$w_cor.'">');
      ShowHTML('        <td>'.$w_telefonia.'</td>');
      ShowHTML('        <td>Ligações</td>');
      if ($_SESSION['INTERNO']=='S') {
        ShowHTML('        <td align="right">---&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>');
      }
      ShowHTML('        <td align="right"><A class="HL" HREF="tarifacao.php?par=Informar&R='.$w_pagina.$par.'&O=L&P1=1&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'- Ligações&SG=LIGACAO'.montaFiltro('GET').'">'.$w_telefonia_qtd.'</A>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
      ShowHTML('        </td>');
      ShowHTML('      </tr>');
    }

    // Monta a mesa de trabalho para os outros serviços do SIW
    $sql = new db_getDeskTop; $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_usuario, $w_ano);
    $w_nm_modulo='-';
    foreach ($RS as $row) {
      if ($w_nm_modulo!=f($row,'nm_modulo')) $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      
      ShowHTML('    <tr valign="top" bgcolor='.$w_cor.'>');

      // Evita que o nome do  módulo seja repetido
      if ($w_nm_modulo!=f($row,'nm_modulo')) {
        ShowHTML('      <td>'.f($row,'nm_modulo').'</td>');
        $w_nm_modulo=f($row,'nm_modulo');
      } else {
        ShowHTML('      <td>&nbsp;</td>');
      }

      ShowHTML('      <td>'.f($row,'nm_servico').'</td>');
      if ($_SESSION['INTERNO']=='S') {
        ShowHTML('      <td align="right"><A CLASS="HL" HREF="'.lower(f($row,'link')).'&O=L&P1=6&P2='.f($row,'p2').'&P3='.f($row,'p3').'&P4='.f($row,'p4').'&TP='.$TP.' - '.f($row,'nm_servico').'&SG='.f($row,'sg_servico').montaFiltro('GET').'">'.formatNumber(f($row,'qtd_solic'),0).'</A>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
      }
      if (f($row,'qtd')>0) {
        if ($_SESSION['INTERNO']=='S') {
          ShowHTML('      <td align="right"><A CLASS="HL" HREF="'.lower(f($row,'link')).'&P1=2&P2='.f($row,'p2').'&P3='.f($row,'p3').'&P4='.f($row,'p4').'&TP='.$TP.' - '.f($row,'nm_servico').'&SG='.f($row,'sg_servico').montaFiltro('GET').'">'.formatNumber(f($row,'qtd'),0).'</A>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
        } else {
          ShowHTML('      <td align="right"><A CLASS="HL" HREF="'.lower(f($row,'link')).'&O=L&P1=6&P2='.f($row,'p2').'&P3='.f($row,'p3').'&P4='.f($row,'p4').'&TP='.$TP.' - '.f($row,'nm_servico').'&SG='.f($row,'sg_servico').montaFiltro('GET').'">'.formatNumber(f($row,'qtd'),0).'</A>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
        }
      } else {
        ShowHTML('      <td align="right">&nbsp;</td>');
      }
      ShowHTML('      </td>');
      ShowHTML('    </tr>');
    }

    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    flush();

    // Exibe o calendário da organização
    include_once($w_dir_volta.'classes/sp/db_getDataEspecial.php');
    $sql = new db_getDataEspecial; 
    for ($i=$w_ano1;$i<=$w_ano3;$i++) {
      $RS_Ano[$i] = $sql->getInstanceOf($dbms,$w_cliente,null,$i,'S',null,null,null);
      $RS_Ano[$i] = SortArray($RS_Ano[$i],'data_formatada','asc');
    }

    if ($_SESSION['DBMS']!=8) {
      // Recupera os dados da unidade de lotação do usuário
      include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
      $sql = new db_getUorgData; $RS_Unidade = $sql->getInstanceOf($dbms,$_SESSION['LOTACAO']);

      if (nvl($w_compras,'')!='') {
        $sql = new db_getLinkData; $RSMenu_Compras = $sql->getInstanceOf($dbms,$w_cliente,'CLLCCAD');
        $sql = new db_getSolicCL; $RS_Compras = $sql->getInstanceOf($dbms,f($RSMenu_Compras,'sq_menu'),$w_usuario,'CLLCCAD',2,
            formataDataEdicao($w_inicio),formataDataEdicao($w_fim),null,null,null,null,null,null,null,null,null, null, null, 
            null, null, null, null,null, null, null, null, null, null, null, null, null, null, null);
        $RS_Compras = SortArray($RS_Compras,'codigo_interno', 'asc');

        // Cria arrays com cada dia do período, definindo o texto e a cor de fundo para exibição no calendário
        foreach($RS_Compras as $row) {
          if (nvl(f($row,'data_abertura'),'')!='') retornaArrayDias(f($row,'data_abertura'), f($row,'data_abertura'), &$w_datas, f($row,'codigo_interno').': Recebimento propostas', 'S');
          if (nvl(f($row,'envelope_1'),'')!='')    retornaArrayDias(f($row,'envelope_1'), f($row,'envelope_1'), &$w_datas, f($row,'codigo_interno').': Abertura envelope 1 ', 'S');
          if (nvl(f($row,'envelope_2'),'')!='')    retornaArrayDias(f($row,'envelope_2'), f($row,'envelope_2'), &$w_datas, f($row,'codigo_interno').': Abertura envelope 2 ', 'S');
          if (nvl(f($row,'envelope_3'),'')!='')    retornaArrayDias(f($row,'envelope_3'), f($row,'envelope_3'), &$w_datas, f($row,'codigo_interno').': Abertura envelope 3 ', 'S');
        }
        reset($RS_Compras);
        foreach($RS_Compras as $row) {
          if (nvl(f($row,'data_abertura'),'')!='') retornaArrayDias(f($row,'data_abertura'), f($row,'data_abertura'), &$w_cores, $conTrBgColorLightBlue2, 'S');
          if (nvl(f($row,'envelope_1'),'')!='')    retornaArrayDias(f($row,'envelope_1'), f($row,'envelope_1'), &$w_cores, $conTrBgColorLightBlue2, 'S');
          if (nvl(f($row,'envelope_2'),'')!='')    retornaArrayDias(f($row,'envelope_2'), f($row,'envelope_2'), &$w_cores, $conTrBgColorLightBlue2, 'S');
          if (nvl(f($row,'envelope_3'),'')!='')    retornaArrayDias(f($row,'envelope_3'), f($row,'envelope_3'), &$w_cores, $conTrBgColorLightBlue2, 'S');
        }
      }

      if (nvl($w_viagem,'')!='') {
        $sql = new db_getLinkData; $RSMenu_Viagem = $sql->getInstanceOf($dbms,$w_cliente,'PDINICIAL');
        $sql = new db_getSolicList; $RS_Viagem = $sql->getInstanceOf($dbms,f($RSMenu_Viagem,'sq_menu'),$w_usuario,'PD',4,
            formataDataEdicao($w_inicio),formataDataEdicao($w_fim),null,null,null,null,null,null,null,null,null,
            null, null, null, null, null, null, null,null, null, null, null, null, null, null, $w_usuario);
        $RS_Viagem = SortArray($RS_Viagem,'inicio', 'desc', 'fim', 'desc');

        // Cria arrays com cada dia do período, definindo o texto e a cor de fundo para exibição no calendário
        foreach($RS_Viagem as $row) {
          $w_saida   = f($row,'phpdt_saida');
          $w_chegada = f($row,'phpdt_chegada');
          if (f($row,'concluida')=='S') {
            retornaArrayDias(f($row,'phpdt_saida'), f($row,'phpdt_chegada'), &$w_datas, 'Viagem a serviço\r\nSituação: Finalizada', 'N');
          } elseif (f($row,'sg_tramite')=='AE' ||f($row,'sg_tramite')=='EE') {
            retornaArrayDias(f($row,'phpdt_saida'), f($row,'phpdt_chegada'), &$w_datas, 'Viagem a serviço\r\nSituação: Confirmada', 'N');
          } else {
            retornaArrayDias(f($row,'phpdt_saida'), f($row,'phpdt_chegada'), &$w_datas, 'Viagem a serviço\r\nSituação: Prevista', 'N');
          }
          $w_datas[formataDataEdicao($w_saida)]['valor']= str_replace('serviço','serviço (saída às '.date('H:i',$w_saida).'h)',$w_datas[formataDataEdicao($w_saida)]['valor']);
          $w_datas[formataDataEdicao($w_chegada)]['valor']= str_replace('serviço','serviço (chegada às '.date('H:i',$w_chegada).'h)',$w_datas[formataDataEdicao($w_chegada)]['valor']);
        }
        reset($RS_Viagem);
        foreach($RS_Viagem as $row) {
          $w_saida   = f($row,'phpdt_saida');
          $w_chegada = f($row,'phpdt_chegada');
          retornaArrayDias(f($row,'phpdt_saida'), f($row,'phpdt_chegada'), &$w_cores, $conTrBgColorLightRed1, 'N');
          if (date('H',$w_saida)>13)   $w_cores[formataDataEdicao($w_saida)]['valor']   = $conTrBgColorLightRed2;
          if (date('H',$w_chegada)<14) $w_cores[formataDataEdicao($w_chegada)]['valor'] = $conTrBgColorLightRed2;
        }
      }

      if (nvl($w_pessoal,'')!='') {
        $sql = new db_getAfastamento; $RS_Afast = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,null,null,null,formataDataEdicao($w_inicio),formataDataEdicao($w_fim),null,null,null,null);
        $RS_Afast = SortArray($RS_Afast,'inicio_data','desc','inicio_periodo','asc','fim_data','desc','inicio_periodo','asc'); 
        // Cria arrays com cada dia do período, definindo o texto e a cor de fundo para exibição no calendário
        foreach($RS_Afast as $row) retornaArrayDias(f($row,'inicio_data'), f($row,'fim_data'), &$w_datas, f($row,'nm_tipo_afastamento'), 'S');
        foreach($RS_Afast as $row) retornaArrayDias(f($row,'inicio_data'), f($row,'fim_data'), &$w_cores, $conTrBgColorLightRed1, 'S');
      }
    }
      
    // Verifica a quantidade de colunas a serem exibidas
    $w_colunas = 1;
    if ($w_indicador=='S' || nvl($w_viagem ,'')!='' || nvl($w_compras ,'')!='' || nvl($w_pessoal,'')!='') $w_colunas += 1;

    // Configura a largura das colunas
    switch ($w_colunas) {
    case 1:  $width = "100%";  break;
    case 2:  $width = "50%";  break;
    case 3:  $width = "33%";  break;
    case 4:  $width = "25%";  break;
    default: $width = "100%";
    }

    ShowHTML('      <tr><td colspan=3><p>&nbsp;</p>');
    ShowHTML('        <table width="100%" border="0" align="center" CELLSPACING=0 CELLPADDING=0 BorderColorDark='.$conTableBorderColorDark.' BorderColorLight='.$conTableBorderColorLight.'><tr valign="top">');
    
    // Exibe calendário e suas ocorrências ==============
    ShowHTML('          <td width="'.$width.'" align="center"><table border="1" cellpadding=0 cellspacing=0>');
    ShowHTML('            <tr><td colspan=3 width="100%"><table width="100%" border=0 cellpadding=0 cellspacing=0><tr>');
    ShowHTML('              <td bgcolor="'.$conTrBgColor.'"><A class="hl" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&w_mes='.$w_mes1.'&w_ano='.$w_ano1.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'"><<<</A>');
    ShowHTML('              <td align="center" bgcolor="'.$conTrBgColor.'"><b>Calendário '.f($RS_Cliente,'nome_resumido').' ('.f($RS_Unidade,'nm_cidade').')</td>');
    ShowHTML('              <td align="right" bgcolor="'.$conTrBgColor.'"><A class="hl" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&w_mes='.$w_mes3.'&w_ano='.$w_ano3.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'">>>></A>');
    ShowHTML('              </table>');
    // Variáveis para controle de exibição do cabeçalho das datas especiais
    $w_detalhe1 = false;
    $w_detalhe2 = false;
    $w_detalhe3 = false;
    ShowHTML('            <tr valign="top">');
    ShowHTML('              <td align="center">'.montaCalendario($RS_Ano[$w_ano1],$w_mes1.$w_ano1,$w_datas,$w_cores,&$w_detalhe1).' </td>');
    ShowHTML('              <td align="center">'.montaCalendario($RS_Ano[$w_ano],$w_mes.$w_ano,$w_datas,$w_cores,&$w_detalhe2).' </td>');
    ShowHTML('              <td align="center">'.montaCalendario($RS_Ano[$w_ano3],$w_mes3.$w_ano3,$w_datas,$w_cores,&$w_detalhe3).' </td>');

    if ($w_detalhe1 || $w_detalhe2 || $w_detalhe3) {
      ShowHTML('            <tr><td colspan=3 bgcolor="'.$conTrBgColor.'">');
      if ((count($RS_Viagem)>0 && nvl($w_viagem ,'')!='') || (count($RS_Afast)>0 && nvl($w_pessoal,'')!='')) {
        ShowHTML('              <b>Observações:<ul>');
        ShowHTML('              <li>Clique sobre o dia em destaque para ver detalhes.');
        ShowHTML('              <li>A cor vermelha indica ausências de '.$_SESSION['NOME_RESUMIDO'].'.');
        ShowHTML('              </ul>');
      } else {
        ShowHTML('              <b>Clique sobre o dia em destaque para ver detalhes.</b>');
      }
    }

    // Exibe informações complementares sobre o calendário
    ShowHTML('            <tr valign="top" bgcolor="'.$conTrBgColor.'">');
    ShowHTML('              <td colspan=3 align="center">');
    if ($w_detalhe1 || $w_detalhe2 || $w_detalhe3) {
      ShowHTML('                <table width="100%" border="0" cellspacing=1>');
      if (count($RS_Ano)==0) {
        ShowHTML('                  <tr valign="top"><td align="center">&nbsp;');
      } else {
        ShowHTML('                  <tr valign="top"><td align="center"><b>Data<td><b>Ocorrências');
        reset($RS_Ano);
        for ($i=$w_ano1;$i<=$w_ano3;$i++) {
          $RS_Ano_Atual = $RS_Ano[$i];
          foreach($RS_Ano_Atual as $row_ano) {
            // Exibe apenas as ocorrências do trimestre selecionado
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
    // Final da exibição do calendário e suas ocorrências ==============
    if ($w_indicador=='S' || nvl($w_viagem ,'')!='' || nvl($w_compras ,'')!='' || nvl($w_pessoal,'')!='') {
      ShowHTML('          <td width="'.$width.'" align="center">');

      // Exibição de indicadores que tenham alguma aferição ==============
      if ($w_indicador=='S') {
        // Recupera o menu da página de indicadorees
        ShowHTML('            <table border=0 cellpadding=0 cellspacing=0 width="100%">');
        ShowHTML('              <tr><td><b>INDICADORES</b>');
        foreach($RS_Indicador as $row) ShowHTML('              <tr><td><A class="HL" HREF="mod_pe/indicador.php?par=FramesAfericao&R='.$w_pagina.$par.'&O=L&w_troca=p_indicador&p_tipo_indicador='.f($row,'chave').'&p_pesquisa=livre&p_volta=mesa&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe os indicadores deste tipo.">'.f($row,'nome').'</a></td></tr>');
        ShowHTML('            </table><br>');
      }
      // Final da exibição de indicadores ================================

      $w_nome_mes1 = upper(mesAno(date('F',toDate('01/'.$w_mes1.'/'.$w_ano1)),'resumido'));
      $w_nome_mes3 = upper(mesAno(date('F',toDate('01/'.$w_mes3.'/'.$w_ano3)),'resumido').'/'.$w_ano3);
  
      // Exibição de datas de licitações ==============
      if (count($RS_Compras)>0 && nvl($w_compras ,'')!='') {
        // Cria array ordenado por data, a partir do cursor
        unset($w_array);
        foreach($RS_Compras as $row) {
          if (nvl(f($row,'data_abertura'),'')!='') {
            $w_array[date(Ymd,f($row,'data_abertura')).'-'.f($row,'codigo_interno')] = $row;
            $w_array[date(Ymd,f($row,'data_abertura')).'-'.f($row,'codigo_interno')]['data'] = f($row,'data_abertura');
            $w_array[date(Ymd,f($row,'data_abertura')).'-'.f($row,'codigo_interno')]['evento'] = 'Recebimento das propostas';
          }
          if (nvl(f($row,'envelope_1'),'')!='') {
            $w_array[date(Ymd,f($row,'envelope_1')).'-'.f($row,'codigo_interno')] = $row;
            $w_array[date(Ymd,f($row,'envelope_1')).'-'.f($row,'codigo_interno')]['data'] = f($row,'envelope_1');
            $w_array[date(Ymd,f($row,'envelope_1')).'-'.f($row,'codigo_interno')]['evento'] = 'Abertura do envelope 1';
          }
          if (nvl(f($row,'envelope_2'),'')!='') {
            $w_array[date(Ymd,f($row,'envelope_2')).'-'.f($row,'codigo_interno')] = $row;
            $w_array[date(Ymd,f($row,'envelope_2')).'-'.f($row,'codigo_interno')]['data'] = f($row,'envelope_2');
            $w_array[date(Ymd,f($row,'envelope_2')).'-'.f($row,'codigo_interno')]['evento'] = 'Abertura do envelope 2';
          }
          if (nvl(f($row,'envelope_3'),'')!='') {
            $w_array[date(Ymd,f($row,'envelope_3')).'-'.f($row,'codigo_interno')] = $row;
            $w_array[date(Ymd,f($row,'envelope_3')).'-'.f($row,'codigo_interno')]['data'] = f($row,'envelope_3');
            $w_array[date(Ymd,f($row,'envelope_3')).'-'.f($row,'codigo_interno')]['evento'] = 'Abertura do envelope 3';
          }
        }
        // Ordena o array pela data
        $w_array = SortArray($w_array,'data','asc','codigo_interno','asc'); 

        ShowHTML('          <table border=1 cellpadding=0 cellspacing=0 width="100%">');
        ShowHTML('            <tr><td><table border=0 cellpadding=0 cellspacing=0 width="100%">');
        if ($w_ano1!=$w_ano3) {
          ShowHTML('              <tr><td align="center"><b>LICITAÇÕES ('.$w_nome_mes1.'/'.$w_ano1.' - '.$w_nome_mes3.')</b><br>');
        } else {
          ShowHTML('              <tr><td align="center"><b>LICITAÇÕES ('.$w_nome_mes1.'-'.$w_nome_mes3.')</b><br>');
        }
        // Exibe as licitações permitidas ao usuário logado
        ShowHTML('              <tr><td>');
        ShowHTML('                <table width="100%" border="1" cellspacing=0 bgcolor="'.$conTrBgColor.'">');
        ShowHTML('                  <tr align="center" valign="middle">');
        ShowHTML('                    <td><b>Data</td>');
        ShowHTML('                    <td><b>Evento</td>');
        ShowHTML('                    <td><b>Número</td>');
        $w_cor = $w_cor=$conTrBgColor;
        // Exibe o array com as datas
        foreach($w_array as $row) {
          if (nvl(f($row,'data_abertura'),'')!='' || nvl(f($row,'envelope_1'),'')!='' || nvl(f($row,'envelope_2'),'')!='' || nvl(f($row,'envelope_3'),'')!='') {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            ShowHTML('                  <tr bgcolor="'.$w_cor.'" valign="top">');
            ShowHTML('                    <td align="center">'.formataDataEdicao(f($row,'data')).'</td>');
            ShowHTML('                    <td>'.f($row,'evento').'</td>');
            ShowHTML('                    <td nowrap>');
            ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),f($row,'data_abertura'),f($row,'data_homologacao'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
            ShowHTML('                      <A class="HL" HREF="'.substr(f($RSMenu_Compras,'link'),0,strpos(f($RSMenu_Compras,'link'),'=')).'=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.f($RSMenu_Compras,'p1').'&P2='.f($RSMenu_Compras,'p2').'&P3='.f($RSMenu_Compras,'p3').'&P4='.f($RSMenu_Compras,'p4').'&TP='.$TP.'&SG='.f($RSMenu_Compras,'sigla').MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'codigo_interno').'&nbsp;</a>');
            ShowHTML('                  </tr>');
          }
        }
        ShowHTML('                </table>');
        ShowHTML('              </table>');
      }
      // Final da exibição de licitações ================================

  
      if ((count($RS_Viagem)>0 && nvl($w_viagem ,'')!='') || (count($RS_Afast)>0 && nvl($w_pessoal,'')!='')) {
        ShowHTML('          <table border=1 cellpadding=0 cellspacing=0 width="100%">');
        ShowHTML('            <tr><td><table border=0 cellpadding=0 cellspacing=0 width="100%">');
        if ($w_ano1!=$w_ano3) {
          ShowHTML('              <tr><td align="center"><b>AUSÊNCIAS ('.$w_nome_mes1.'/'.$w_ano1.' - '.$w_nome_mes3.')</b><br>');
        } else {
          ShowHTML('              <tr><td align="center"><b>AUSÊNCIAS ('.$w_nome_mes1.'-'.$w_nome_mes3.')</b><br>');
        }
        // Exibe as viagens a serviço do usuário logado
        if (count($RS_Viagem)>0 && nvl($w_viagem ,'')!='') {
          ShowHTML('              <tr><td>');
          ShowHTML('                <b>VIAGENS A SERVIÇO</b>');
          ShowHTML('                <table width="100%" border="1" cellspacing=0 bgcolor="'.$conTrBgColor.'">');
          ShowHTML('                  <tr align="center" valign="middle">');
          ShowHTML('                    <td><b>Início</td>');
          ShowHTML('                    <td><b>Término</td>');
          ShowHTML('                    <td><b>Nº</td>');
          ShowHTML('                    <td><b>Destinos</td>');
          reset($RS_Viagem);
          $w_cor = $w_cor=$conTrBgColor;
          if (count($RS_Viagem)==0) {
            ShowHTML('                  <tr bgcolor="'.$w_cor.'" valign="top"><td colspan=4 align="center"><b>Não foram encontrados registros.');
          } else {
            foreach($RS_Viagem as $row) {
              $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
              ShowHTML('                  <tr bgcolor="'.$w_cor.'" valign="top">');
              ShowHTML('                    <td align="center">'.Nvl(date(d.'/'.m.', '.H.':'.i,f($row,'phpdt_saida')),'-').'</td>');
              ShowHTML('                    <td align="center">'.Nvl(date(d.'/'.m.', '.H.':'.i,f($row,'phpdt_chegada')),'-').'</td>');
              ShowHTML('                    <td nowrap>');
              ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
              ShowHTML('                      <A class="HL" HREF="'.substr(f($RSMenu_Viagem,'link'),0,strpos(f($RSMenu_Viagem,'link'),'=')).'=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.f($RSMenu_Viagem,'p1').'&P2='.f($RSMenu_Viagem,'p2').'&P3='.f($RSMenu_Viagem,'p3').'&P4='.f($RSMenu_Viagem,'p4').'&TP='.$TP.'&SG='.f($RSMenu_Viagem,'sigla').MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'codigo_interno').'&nbsp;</a>');
              ShowHTML('                    <td>'.f($row,'trechos').'&nbsp;</td>');
              ShowHTML('                  </tr>');
            }
          }
          ShowHTML('                </table>');
        }

        // Exibe afastamentos do usuário logado
        if (count($RS_Afast)>0 && nvl($w_pessoal,'')!='') {
          ShowHTML('              <tr><td><br>');
          // Mostra os períodos de indisponibilidade
          ShowHTML('                <b>AFASTAMENTOS</b>');
          ShowHTML('                <table width="100%" border="1" cellspacing=0 bgcolor="'.$conTrBgColor.'">');
          ShowHTML('                  <tr align="center" valign="top"><td><b>Início<td><b>Término<td><b>Dias<td><b>Tipo');
          reset($RS_Afast);
          $w_cor = $w_cor=$conTrBgColor;
          if (count($RS_Afast)==0) {
            ShowHTML('                  <tr bgcolor="'.$w_cor.'" valign="top"><td colspan=6 align="center"><b>Não foram encontrados registros.');
          } else {
            foreach($RS_Afast as $row) {
              $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
              ShowHTML('                <tr bgcolor="'.$w_cor.'" valign="top">');
              ShowHTML('                    <td align="center">'.date(d.'/'.m,f($row,'inicio_data')).' ('.f($row,'nm_inicio_periodo').')');
              ShowHTML('                    <td align="center">'.date(d.'/'.m,f($row,'fim_data')).' ('.f($row,'nm_fim_periodo').')');
              ShowHTML('                    <td align="center">'.crlf2br(f($row,'dias')));
              ShowHTML('                    <td>'.f($row,'nm_tipo_afastamento'));
            }
          }
          ShowHTML('                </table>');
        }
        ShowHTML('              </table>');
      }
    }
    ShowHTML('        </table>');
    ShowHTML('      </table>');
  } else {
    ScriptOpen("JavaScript");
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Exibe alertas de atraso e proximidade da data de conclusao
// -------------------------------------------------------------------------
function Alerta() {
  extract($GLOBALS);
  
  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.';">');
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=this.focus();');
  ShowHTML('<table border="0" width="100%">');
  ShowHTML('<tr><td><b><FONT COLOR="#000000"><font size=2>'.$w_TP.'</font></b>');
  $sql = new db_getLinkData; $RS_Volta = $sql->getInstanceOf($dbms,$w_cliente,'MESA');
  ShowHTML('  <td align="right"><a class="SS" href="'.$conRootSIW.f($RS_Volta,'link').'&P1='.f($RS_Volta,'p1').'&P2='.f($RS_Volta,'p2').'&P3='.f($RS_Volta,'p3').'&P4='.f($RS_Volta,'p4').'&TP=<img src='.f($RS_Volta,'imagem').' BORDER=0>'.f($RS_Volta,'nome').'&SG='.f($RS_Volta,'sigla').montaFiltro('GET').'" target="content">Voltar para '.f($RS_Volta,'nome').'</a>');
  ShowHTML('<tr><td colspan=2><hr>');
  ShowHTML('</table>');
  ShowHTML('<center>');
  ShowHTML('<table border="0" width="100%">');
  if ($O=='L') {
  // Recupera solicitações a serem listadas
    $sql = new db_getAlerta; $RS_Solic = $sql->getInstanceOf($dbms, $w_cliente, $w_usuario, 'SOLICGERAL', 'N', null);
    $RS_Solic = SortArray($RS_Solic, 'cliente', 'asc', 'usuario', 'asc', 'nm_modulo','asc', 'nm_servico', 'asc', 'titulo', 'asc');

    // Recupera pacotes de trabalho a serem listados
    $sql = new db_getAlerta; $RS_Pacote = $sql->getInstanceOf($dbms, $w_cliente, $w_usuario, 'PACOTE', 'N', null);
    $RS_Pacote = SortArray($RS_Pacote, 'cliente', 'asc', 'usuario', 'asc', 'nm_projeto','asc', 'cd_ordem', 'asc');

    // Recupera banco de horas
    $sql = new db_getAlerta; $RS_Horas = $sql->getInstanceOf($dbms, $w_cliente, $w_usuario, 'HORAS', 'N', null);
    
    ShowHTML(VisualAlerta($w_cliente, $w_usuario, 'TELA', $RS_Solic, $RS_Pacote, $RS_Horas));
  } else {
    ScriptOpen("JavaScript");
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);

  switch ($par) {
  case 'MESA':    Mesa();   break;
  case 'ALERTA':  Alerta(); break;
  default:
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

