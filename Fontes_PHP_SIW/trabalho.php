<?
header('Expires: '.-1500);
session_start();
include_once('constants.inc');
include_once('jscript.php');
include_once('funcoes.php');
include_once('classes/db/abreSessao.php');
include_once('classes/sp/db_getSiwCliModLis.php');
include_once('classes/sp/db_getCustomerData.php');
include_once('classes/sp/db_getPersonData.php');
include_once('classes/sp/db_getDeskTop_TT.php');
include_once('classes/sp/db_getDeskTop.php');
include_once('classes/sp/db_getSolicList.php');
include_once('classes/sp/db_getAfastamento.php');
include_once('classes/sp/db_getLinkData.php');
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
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }


// Declaração de variáveis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par        = strtoupper($_REQUEST["par"]);
$P1         = $_REQUEST["P1"];
$P2         = $_REQUEST["P2"];
$P3         = $_REQUEST["P3"];
$P4         = $_REQUEST["P4"];
$TP         = $_REQUEST["TP"];
$SG         = strtoupper($_REQUEST["SG"]);
$R          = $_REQUEST["R"];
$O          = strtoupper($_REQUEST["O"]);

$w_Assinatura   = strtoupper(${"w_Assinatura"});
$w_pagina       = "trabalho.php?par=";
$w_Disabled     = "ENABLED";

$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_ano      = RetornaAno();

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
  
  if ($O=="L") {
     // Verifica se o cliente tem o módulo de telefonia contratado
     $RS = db_getSiwCliModLis::getInstanceOf($dbms, $w_cliente, null, 'TT');
     foreach ($RS as $row) $w_telefonia = f($row,'nome');

     // Verifica se o cliente tem o módulo de colaboradores contratado
     $RS = db_getSiwCliModLis::getInstanceOf($dbms, $w_cliente, null, 'GP');
     foreach ($RS as $row) $w_pessoal = f($row,'nome');

     // Verifica se o cliente tem o módulo de viagens
     $RS = db_getSiwCliModLis::getInstanceOf($dbms, $w_cliente, null, 'PD');
     foreach ($RS as $row) $w_viagem = f($row,'nome');

     // Verifica se o usuário tem acesso ao módulo de telefonia
     //$RS = db_getPersonData::getInstanceOf($dbms, $w_cliente, $w_usuario, null, null);
     //if (f($RS,'sq_usuario_central')=='') $w_telefonia='';
  }

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="300;">');
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=this.focus();');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" width="100%">');
  if ($O=="L") {
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor='.$conTableBgColor.' BORDER='.$conTableBorder.' CELLSPACING='.$conTableCellSpacing.' CELLPADDING='.$conTableCellPadding.' BorderColorDark='.$conTableBorderColorDark.' BorderColorLight='.$conTableBorderColorLight.'>');
    ShowHTML('        <tr bgcolor='.$conTrBgColor.' align="center">');
    ShowHTML('          <td><b>Módulo</td>');
    ShowHTML('          <td><b>Serviço</td>');
    ShowHTML('          <td><b>Em aberto</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (!($w_workflow.$w_telefonia.$w_demandas.$w_agenda=='')) {
      if ($w_telefonia>'') {
        $RS = db_getDeskTop_TT::getInstanceOf($dbms, $w_usuario);
        foreach ($RS as $row) $w_telefonia_qtd=f($row,'existe');
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        if ($w_telefonia_qtd>0) $w_negrito='<b>'; else $w_negrito='';
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        ShowHTML('        <td>'.$w_telefonia.'</td>');
        ShowHTML('        <td>Ligações</td>');
        ShowHTML('        <td align="right">'.$w_negrito.$w_telefonia_qtd.'&nbsp;</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="tarifacao.php?par=Informar&R='.$w_pagina.$par.'&O=L&P1=1&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'- Ligações&SG=LIGACAO">Exibir</A> ');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }

    // Monta a mesa de trabalho para os outros serviços do SIW
    $RS = db_getDeskTop::getInstanceOf($dbms, $w_cliente, $w_usuario, $w_ano);
    $w_nm_modulo='-';
    foreach ($RS as $row) {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      
      if (f($row,'qtd')>0) $w_negrito="<b>"; else $w_negrito=''; 
    
      ShowHTML('    <tr bgcolor='.$w_cor.'>');
      // Evita que o nome do  módulo seja repetido
      if ($w_nm_modulo!=f($row,'nm_modulo')) {
        ShowHTML('      <td>'.f($row,'nm_modulo').'</td>');
        $w_nm_modulo=f($row,'nm_modulo');
      } else {
        ShowHTML('      <td>&nbsp;</td>');
      }

      ShowHTML('      <td>'.f($row,'nm_servico').'</td>');
      ShowHTML('      <td align="right">'.$w_negrito.f($row,'qtd').'&nbsp;</td>');
      ShowHTML('      <td align="top" nowrap>');
      ShowHTML('        <A CLASS="HL" HREF="'.strtolower(f($row,'link')).'&P1=2&P2='.f($row,'p2').'&P3='.f($row,'p3').'&P4='.f($row,'p4').'&TP='.$TP.' - '.f($row,'nm_servico').'&SG='.f($row,'sg_servico').'">Exibir</A>');
      ShowHTML('      </td>');
      ShowHTML('    </tr>');
    }

    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');

    // Exibe o calendário da organização
    include_once($w_dir_volta.'classes/sp/db_getDataEspecial.php');
    $RS_Ano = db_getDataEspecial::getInstanceOf($dbms,$w_cliente,null,$w_ano,'S',null,null,null);
    $RS_Ano = SortArray($RS_Ano,'data_formatada','asc');

    // Recupera os dados da unidade de lotação do usuário
    include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
    $RS_Unidade = db_getUorgData::getInstanceOf($dbms,$_SESSION['LOTACAO']);
    
    // Recupera os dados da unidade de lotação do usuário
    $RS_Cliente = db_getCustomerData::getInstanceOf($dbms,$w_cliente);

    if (nvl($w_viagem,'nulo')!='nulo') {
      $RSMenu_Viagem = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PDINICIAL');
      $RS_Viagem = db_getSolicList::getInstanceOf($dbms,f($RSMenu_Viagem,'sq_menu'),$w_usuario,'PD',4,
          '01/01/'.$w_ano,'31/12/'.$w_ano,null,null,null,null,null,null,null,null,null,
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
      foreach($RS_Viagem as $row) {
        retornaArrayDias(f($row,'phpdt_saida'), f($row,'phpdt_chegada'), &$w_cores, $conTrBgColorLightRed1, 'N');
        if (date('H',$w_saida)>13)   $w_cores[formataDataEdicao($w_saida)]['valor']   = $conTrBgColorLightRed2;
        if (date('H',$w_chegada)<14) $w_cores[formataDataEdicao($w_chegada)]['valor'] = $conTrBgColorLightRed2;
      }
    }

    if (nvl($w_pessoal,'nulo')!='nulo') {
      $RS_Afast = db_getAfastamento::getInstanceOf($dbms,$w_cliente,$w_usuario,null,null,null,'01/01/'.$w_ano,'31/12/'.$w_ano,null,null,null,null);
      $RS_Afast = SortArray($RS_Afast,'inicio_data','desc','inicio_periodo','asc','fim_data','desc','inicio_periodo','asc'); 
      // Cria arrays com cada dia do período, definindo o texto e a cor de fundo para exibição no calendário
      foreach($RS_Afast as $row) retornaArrayDias(f($row,'inicio_data'), f($row,'fim_data'), &$w_datas, f($row,'nm_tipo_afastamento'), 'S');
      foreach($RS_Afast as $row) retornaArrayDias(f($row,'inicio_data'), f($row,'fim_data'), &$w_cores, $conTrBgColorLightRed1, 'S');
    }

    ShowHTML('      <tr><td colspan=3><p>&nbsp;</p>');
    ShowHTML('        <table border="1" align="center" bgcolor='.$conTableBgColor.' CELLSPACING=0 CELLPADDING=0 BorderColorDark='.$conTableBorderColorDark.' BorderColorLight='.$conTableBorderColorLight.'>');
    ShowHTML('          <tr valign="top">');
    ShowHTML('            <td bgcolor="'.$conTrBgColor.'"><A class="hl" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&w_ano='.($w_ano-1).'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Exibe calendário do ano anterior."><<< '.($w_ano-1).'</A>');
    ShowHTML('            <td colspan=4 align="center" bgcolor="'.$conTrBgColor.'"><b>CALENDÁRIO '.f($RS_Cliente,'nome_resumido').' '.$w_ano.' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ('.strtoupper(f($RS_Unidade,'nm_cidade')).')</td>');
    ShowHTML('            <td align="right" bgcolor="'.$conTrBgColor.'"><A class="hl" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&w_ano='.($w_ano+1).'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Exibe calendário do ano seguinte.">'.($w_ano+1).' >>></A>');
    ShowHTML('          <tr valign="top">');
    ShowHTML('            <td>'.montaCalendario($RS_Ano,'01'.$w_ano,$w_datas,$w_cores).' </td>');
    ShowHTML('            <td>'.montaCalendario($RS_Ano,'02'.$w_ano,$w_datas,$w_cores).' </td>');
    ShowHTML('            <td>'.montaCalendario($RS_Ano,'03'.$w_ano,$w_datas,$w_cores).' </td>');
    ShowHTML('            <td>'.montaCalendario($RS_Ano,'04'.$w_ano,$w_datas,$w_cores).' </td>');
    ShowHTML('            <td>'.montaCalendario($RS_Ano,'05'.$w_ano,$w_datas,$w_cores).' </td>');
    ShowHTML('            <td>'.montaCalendario($RS_Ano,'06'.$w_ano,$w_datas,$w_cores).' </td>');
    ShowHTML('          <tr valign="top">');
    ShowHTML('            <td>'.montaCalendario($RS_Ano,'07'.$w_ano,$w_datas,$w_cores).' </td>');
    ShowHTML('            <td>'.montaCalendario($RS_Ano,'08'.$w_ano,$w_datas,$w_cores).' </td>');
    ShowHTML('            <td>'.montaCalendario($RS_Ano,'09'.$w_ano,$w_datas,$w_cores).' </td>');
    ShowHTML('            <td>'.montaCalendario($RS_Ano,'10'.$w_ano,$w_datas,$w_cores).' </td>');
    ShowHTML('            <td>'.montaCalendario($RS_Ano,'11'.$w_ano,$w_datas,$w_cores).' </td>');
    ShowHTML('            <td>'.montaCalendario($RS_Ano,'12'.$w_ano,$w_datas,$w_cores).' </td>');
    
    // Verifica a quantidade de blocos a serem exibidos
    $w_blocos = 0;
    if (nvl($w_viagem ,'nulo')!='nulo') $w_blocos += 1;
    if (nvl($w_pessoal,'nulo')!='nulo') $w_blocos += 1;

    ShowHTML('          <tr><td colspan=6 bgcolor="'.$conTrBgColor.'">');
    if ($w_blocos>0) {
      ShowHTML('            <b>Observações:<ul>');
      ShowHTML('            <li>Clique sobre o dia em destaque para ver detalhes.');
      ShowHTML('            <li>As datas destacadas em vermelho indicam a indisponibilidade do usuário '.$_SESSION['NOME_RESUMIDO'].'.');
      ShowHTML('            </ul>');
    } else {
      ShowHTML('            <b>Clique sobre o dia em destaque para ver detalhes.</b>');
    }

    // Exibe informações complementares sobre o calendário
    ShowHTML('          <tr valign="top" bgcolor="'.$conTrBgColor.'">');
    // Exibe descritivo das datas especiais
    if ($w_blocos>0) {
      ShowHTML('            <td colspan=2 rowspan="'.$w_blocos.'" align="center">');
    } else {
      ShowHTML('            <td colspan=6 rowspan="'.$w_blocos.'" align="center">');
    }
    ShowHTML('              <table width="100%" border="0" cellspacing=1>');
    if (count($RS_Ano)==0) {
      ShowHTML('                <tr valign="top"><td align="center">&nbsp;');
    } else {
      ShowHTML('                <tr valign="top"><td align="center"><b>Data<td><b>Ocorrência');
      reset($RS_Ano);
      foreach($RS_Ano as $row_ano) {
        ShowHTML('                <tr valign="top">');
        ShowHTML('                  <td align="center">'.date(d.'/'.m,f($row_ano,'data_formatada')));
        ShowHTML('                  <td>'.f($row_ano,'nome'));
      }
      ShowHTML('              </table>');
    }

    if ($w_blocos>0) {
      // Exibe as viagens a serviço do usuário logado
      if (nvl($w_viagem,'nulo')!='nulo') {
        ShowHTML('            <td colspan=4 align="center">');
        ShowHTML('              <b>VIAGENS A SERVIÇO</b>');
        ShowHTML('              <table width="100%" border="1" cellspacing=0>');
        ShowHTML('                <tr align="center" valign="middle">');
        ShowHTML('                  <td><b>Início</td>');
        ShowHTML('                  <td><b>Término</td>');
        ShowHTML('                  <td><b>Nº</td>');
        ShowHTML('                  <td><b>Trechos</td>');
        reset($RS_Viagem);
        $w_cor = $w_cor=$conTrBgColor;
        if (count($RS_Viagem)==0) {
          ShowHTML('                <tr bgcolor="'.$w_cor.'" valign="top"><td colspan=6 align="center"><b>Não foram encontrados registros.');
        } else {
          foreach($RS_Viagem as $row) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            ShowHTML('                <tr bgcolor="'.$w_cor.'" valign="top">');
            ShowHTML('                <td align="center">&nbsp;'.Nvl(substr(formataDataEdicao(f($row,'phpdt_saida'),3),0,-3),'-').'</td>');
            ShowHTML('                <td align="center">&nbsp;'.Nvl(substr(formataDataEdicao(f($row,'phpdt_chegada'),3),0,-3),'-').'</td>');
            ShowHTML('                  <td nowrap>');
            if (f($row,'concluida')=='N') {
              if (f($row,'fim')<addDays(time(),-1)) {
                ShowHTML('                     <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">');
              } elseif (f($row,'aviso_prox_conc')=='S' && (f($row,'aviso')<=addDays(time(),-1))) {
                ShowHTML('                     <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">');
              } else {
                ShowHTML('                     <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">');
              } 
            } else {
              if (f($row,'fim')<Nvl(f($row,'fim_real'),f($row,'fim'))) {
                ShowHTML('                     <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">');
              } else {
                ShowHTML('                     <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">');
              } 
            } 
            ShowHTML('                    <A class="HL" HREF="'.substr(f($RSMenu_Viagem,'link'),0,strpos(f($RSMenu_Viagem,'link'),'=')).'=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.f($RSMenu_Viagem,'p1').'&P2='.f($RSMenu_Viagem,'p2').'&P3='.f($RSMenu_Viagem,'p3').'&P4='.f($RSMenu_Viagem,'p4').'&TP='.$TP.'&SG='.f($RSMenu_Viagem,'sigla').MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'codigo_interno').'&nbsp;</a>');
            ShowHTML('                  <td nowrap>'.f($row,'trechos').'</td>');
            ShowHTML('                </tr>');
          }
        }
        ShowHTML('              </table>');
      }

      // Exibe afastamentos do usuário logado
      if (nvl($w_pessoal,'nulo')!='nulo') {
        if ($w_blocos>1) ShowHTML('            <tr valign="top" bgcolor="'.$conTrBgColor.'">');
        ShowHTML('              <td colspan=4 align="center">');
        // Mostra os períodos de indisponibilidade
        ShowHTML('              <b>AFASTAMENTOS</b>');
        ShowHTML('              <table width="100%" border="1" cellspacing=0>');
        ShowHTML('                <tr align="center" valign="top"><td><b>Início<td><b>Término<td><b>Dias<td><b>Tipo');
        reset($RS_Afast);
        $w_cor = $w_cor=$conTrBgColor;
        if (count($RS_Afast)==0) {
          ShowHTML('                <tr bgcolor="'.$w_cor.'" valign="top"><td colspan=6 align="center"><b>Não foram encontrados registros.');
        } else {
          foreach($RS_Afast as $row) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            ShowHTML('              <tr bgcolor="'.$w_cor.'" valign="top">');
            ShowHTML('                  <td align="center">'.formataDataEdicao(f($row,'inicio_data')).' ('.f($row,'nm_inicio_periodo').')');
            ShowHTML('                  <td align="center">'.formataDataEdicao(f($row,'fim_data')).' ('.f($row,'nm_fim_periodo').')');
            ShowHTML('                  <td align="center">'.crlf2br(f($row,'dias')));
            ShowHTML('                  <td>'.f($row,'nm_tipo_afastamento'));
          }
        }
        ShowHTML('              </table>');
      }
    }
    ShowHTML('        </table>');
  } else {
    ScriptOpen("JavaScript");
    ShowHTML(' alert(\'Opção não disponível\');');
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
  case 'MESA': Mesa(); break;
  default:
    Cabecalho();
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

