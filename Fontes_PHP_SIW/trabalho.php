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
include_once($w_dir_volta.'classes/sp/db_getLinkDataUser.php');
include_once($w_dir_volta.'classes/sp/db_getUserResp.php');
include_once($w_dir_volta.'classes/sp/db_getSolicFN.php');
include_once($w_dir_volta.'visualalerta.php');
include_once($w_dir_volta.'mod_fn/visuallancamento.php');
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

$w_assinatura   = $_REQUEST['w_assinatura'];
$w_pagina       = 'trabalho.php?par=';
$w_Disabled     = 'ENABLED';

// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declara��o de vari�veis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_ano      = RetornaAno();
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
  
  // Vari�vel para decidir se exibe link para a rotina de tesouraria
  $w_tesouraria = false;

  // Recupera os dados do cliente
  $sql = new db_getCustomerData; $RS_Cliente = $sql->getInstanceOf($dbms,$w_cliente);

  if ($O=="L") {
    // Verifica se o cliente tem o m�dulo de telefonia contratado
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

    // Apenas para usu�rios internos da organiza��o
    if ($_SESSION['INTERNO']=='S') {
      // Verifica se h� algum indicador com aferi��o
      $sql = new db_getIndicador; $RS_Indicador = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,null,null,null,null,null,'S',null,null,null,null,null,null,null,null,null,'TIPOINDIC');
      $RS_Indicador = SortArray($RS_Indicador,'nome','asc');
      if (count($RS_Indicador)>0) $w_indicador='S'; else $w_indicador='N';
      
      // Verifica os m�dulos que o usu�rio seja gestor
      $sql = new db_getUserModule; $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_usuario);
      foreach($RS as $row) {
        $w_user[f($row,'sigla')] = true;
      }
      
      if ($w_user['FN']) $w_tesouraria = true;
      else {
        $sql = new db_getLinkDataUser; $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_usuario, 'TESOURARIA');
        if (count($RS)) $w_tesouraria = true;
      }
    }
  }
  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.';">');
  if (nvl($w_financeiro,'')!='') {
    ScriptOpen('Javascript');
    ValidateOpen('Validacao');
    Validate('w_codigo','C�digo lan�amento','','1','8','90','1','1');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('</HEAD>');
  if (nvl($w_financeiro,'')!='') {
    BodyOpen('onLoad="document.Form.w_codigo.focus()";');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');   
  }
  AbreForm('Form',$w_pagina.'Consulta','POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
  ShowHTML('<table border="0" width="100%">');
  ShowHTML('<tr><td><b><FONT COLOR="#000000"><font size=2>'.$w_TP.'</font></b>');
  ShowHTML('    <td align="right" valign="top" NOWRAP>');
  
  // Se o m�dulo financeiro estiver habilitado e o usu�rio for gestor desse m�dulo, exibe link para a tela de tesouraria
  if (nvl($w_financeiro,'')!='' && $_SESSION['DBMS']!=8) {
    ShowHTML('Buscar pagamento: <INPUT CLASS="sti" TYPE="text" NAME="w_codigo" size="18" maxlength="18" VALUE="" title="Informe o c�digo do pagamento ou recebimento desejado.">');
    ShowHTML('<input type="image" name="submit" src="images/Folder/Explorer.gif" style="vertical-align:middle;width:15px;height:15px" border=0>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
    
    if ($w_tesouraria) {
      ShowHTML('      <A HREF="mod_fn/tesouraria.php?par=inicial&O=L&TP='.$TP.' - Tesouraria" title="Clique para acessar a tela da tesouraria."><img src="'.$conImgFin.'" style="vertical-align:middle;width:15px;height:15px" border=0></a></font></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
    }
  }
  
  // Se o m�dulo de pessoal estiver habilitado para o cliente, exibe link para acesso � folha de ponto
  if (nvl($w_pessoal,'')!='' && $_SESSION['DBMS']!=8) {
    // Verifica se o usu�rio tem contrato de trabalho  
    $sql = new db_getGPContrato; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,null,$w_usuario,null,null,null,null,null,null,null,null,null,null);
    
    // Verifica se � chefe de unidade
    $sql = new db_getUserResp; $RS2 = $sql->getInstanceOf($dbms,$w_usuario,null);
    
    if (count($RS1) || count($RS2)) {
      $w_erro = false;
      if (count($RS1)) {
        //Verifica se os hor�rios da jornada di�ria foram preenchidos
        foreach($RS1 as $row) { $RS1 = $row; break; }
        if (nvl(f($row,'entrada_manha'),'')=='' && nvl(f($row,'entrada_tarde'),'')=='' && nvl(f($row,'entrada_noite'),'')=='') {
          $w_erro = true;
        }
      }
      
      if ($w_erro) {
        ShowHTML('      <a HREF="javascript:this.status.value;" onClick="alert(\'Jornada di�ria n�o informada no contrato. Entre em contato com os gestores de pessoal!\');" title="Pendente gestores de pessoal informarem jornada di�ria de trabalho no contrato."><img src="'.$conRootSIW.'images/relogio.gif" style="vertical-align:middle;width:15px;height:15px" border=0></a></font></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
      } else {
        ShowHTML('      <a HREF="javascript:this.status.value;" onClick="javascript:window.open(\''.montaURL_JS($w_dir,'mod_rh/folha.php?par=inicial&O=L&SG=COINICIAL&TP='.$TP.' - Folha de ponto').'\',\'Folha\',\'toolbar=no,resizable=yes,width=780,height=550,top=20,left=10,scrollbars=yes\');" title="Clique para acessar a folha de ponto."><img src="'.$conRootSIW.'images/relogio.gif" style="vertical-align:middle;width:15px;height:15px" border=0></a></font></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
      }
    }
  }

  // Se o georeferenciamento estiver habilitado para o cliente, exibe link para acesso � visualiza��o
  if (f($RS_Cliente,'georeferencia')=='S') {
    ShowHTML('      <a HREF="javascript:this.status.value;" onClick="javascript:window.open(\''.montaURL_JS($w_dir,'mod_gr/exibe.php?par=inicial&O=L&TP='.$TP.' - Georeferenciamento').'\',\'Folha\',\'toolbar=no,resizable=yes,width=780,height=550,top=20,left=10,scrollbars=yes\');" title="Clique para visualizar os mapas georeferenciados."><img src="'.$conImgGeo.'" style="vertical-align:middle;width:15px;height:15px" border=0></a></font></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
  }

  // Link para a tela de alertas
  //ShowHTML('      <a href="'.$w_pagina.'alerta&O=L&TP='.$TP.' - Alertas" title="Clique para ver alertas de atraso e proximidade da data de conclus�o."><img src="'.$conImgAlLow.'" style="vertical-align:middle;width:15px;height:15px" border=0></a></font></b>');

  ShowHTML('<tr><td colspan=2><hr>');
  ShowHTML('</table>');
  ShowHtml('</form>');
  ShowHTML('<table border="0" width="100%">');
  if ($O=="L") {
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor='.$conTableBgColor.' BORDER='.$conTableBorder.' CELLSPACING='.$conTableCellSpacing.' CELLPADDING='.$conTableCellPadding.' BorderColorDark='.$conTableBorderColorDark.' BorderColorLight='.$conTableBorderColorLight.'>');
    ShowHTML('        <tr bgcolor='.$conTrBgColor.' align="center">');
    if ($_SESSION['INTERNO']=='S') {
      ShowHTML('          <td rowspan=2><b>M�dulo</td>');
      ShowHTML('          <td rowspan=2><b>Servi�o</td>');
      ShowHTML('          <td colspan=2><b>Em andamento</td>');
      ShowHTML('        <tr bgcolor='.$conTrBgColor.' align="center">');
      ShowHTML('          <td width="15%" nowrap><b>Total</td>');
      ShowHTML('          <td width="15%" nowrap><b>Necessita interven��o</td>');
    } else {
      ShowHTML('          <td><b>M�dulo</td>');
      ShowHTML('          <td><b>Servi�o</td>');
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

    // Verifica se � necess�rio colocar as liga��es telef�nicas
    if ($w_telefonia>'' && $_SESSION['INTERNO']=='S') {
      $sql = new db_getDeskTop_TT; $RS = $sql->getInstanceOf($dbms, $w_usuario);
      foreach ($RS as $row) $w_telefonia_qtd=f($row,'existe');
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      if ($w_telefonia_qtd>0) $w_negrito='<b>'; else $w_negrito='';
      ShowHTML('      <tr bgcolor="'.$w_cor.'">');
      ShowHTML('        <td>'.$w_telefonia.'</td>');
      ShowHTML('        <td>Liga��es</td>');
      if ($_SESSION['INTERNO']=='S') {
        ShowHTML('        <td align="right">---&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>');
      }
      ShowHTML('        <td align="right"><A class="HL" HREF="tarifacao.php?par=Informar&R='.$w_pagina.$par.'&O=L&P1=1&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'- Liga��es&SG=LIGACAO'.montaFiltro('GET').'">'.$w_telefonia_qtd.'</A>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
      ShowHTML('        </td>');
      ShowHTML('      </tr>');
    }

    // Monta a mesa de trabalho para os outros servi�os do SIW
    $sql = new db_getDeskTop; $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_usuario, $w_ano);
    $w_nm_modulo='-';
    foreach ($RS as $row) {
      if ($w_nm_modulo!=f($row,'nm_modulo')) $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      
      ShowHTML('    <tr valign="top" bgcolor='.$w_cor.'>');

      // Evita que o nome do  m�dulo seja repetido
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

    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    flush();

    // Exibe o calend�rio da organiza��o
    include_once($w_dir_volta.'classes/sp/db_getDataEspecial.php');
    $sql = new db_getDataEspecial; 
    for ($i=$w_ano1;$i<=$w_ano3;$i++) {
      $RS_Ano[$i] = $sql->getInstanceOf($dbms,$w_cliente,null,$i,'S',null,null,null);
      $RS_Ano[$i] = SortArray($RS_Ano[$i],'data_formatada','asc');
    }
    
    $w_datas = array();

    if ($_SESSION['DBMS']!=8) {
      // Recupera os dados da unidade de lota��o do usu�rio
      include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
      $sql = new db_getUorgData; $RS_Unidade = $sql->getInstanceOf($dbms,$_SESSION['LOTACAO']);
      
      if (nvl($w_compras,'')!='') {
        $sql = new db_getLinkData; $RSMenu_Compras = $sql->getInstanceOf($dbms,$w_cliente,'CLLCCAD');
        $sql = new db_getSolicCL; $RS_Compras = $sql->getInstanceOf($dbms,f($RSMenu_Compras,'sq_menu'),$w_usuario,'MESA',2,
            formataDataEdicao($w_inicio),formataDataEdicao($w_fim),null,null,null,null,null,null,null,null,null, null, null, null, null, 
            null, null,null, null, null, null, null, null, null, null, null, null, null, null,null,null,null,null,null,null,null,null);
        $RS_Compras = SortArray($RS_Compras,'codigo_interno', 'asc');

        // Cria arrays com cada dia do per�odo, definindo o texto e a cor de fundo para exibi��o no calend�rio
        foreach($RS_Compras as $row) {
          if (nvl(f($row,'data_abertura'),'')!='') retornaArrayDias(f($row,'phpdt_data_abertura'), f($row,'phpdt_data_abertura'), $w_datas, f($row,'codigo_interno').': Recebimento propostas'.((date('H:i',f($row,'phpdt_data_abertura'))!='00:00') ? ' ('.date('H:i',f($row,'phpdt_data_abertura')).')' : ''), 'S');
          if (nvl(f($row,'envelope_1'),'')!='')    retornaArrayDias(f($row,'phpdt_envelope_1'), f($row,'phpdt_envelope_1'), $w_datas, f($row,'codigo_interno').': Abertura envelope 1 '.((date('H:i',f($row,'phpdt_envelope_1'))!='00:00') ? ' ('.date('H:i',f($row,'phpdt_envelope_1')).')' : ''), 'S');
          if (nvl(f($row,'envelope_2'),'')!='')    retornaArrayDias(f($row,'phpdt_envelope_2'), f($row,'phpdt_envelope_2'), $w_datas, f($row,'codigo_interno').': Abertura envelope 2 '.((date('H:i',f($row,'phpdt_envelope_2'))!='00:00') ? ' ('.date('H:i',f($row,'phpdt_envelope_2')).')' : ''), 'S');
          if (nvl(f($row,'envelope_3'),'')!='')    retornaArrayDias(f($row,'phpdt_envelope_3'), f($row,'phpdt_envelope_3'), $w_datas, f($row,'codigo_interno').': Abertura envelope 3 '.((date('H:i',f($row,'phpdt_envelope_3'))!='00:00') ? ' ('.date('H:i',f($row,'phpdt_envelope_3')).')' : ''), 'S');
        }
        reset($RS_Compras);
        foreach($RS_Compras as $row) {
          if (nvl(f($row,'data_abertura'),'')!='') retornaArrayDias(f($row,'data_abertura'), f($row,'data_abertura'), $w_cores, $conTrBgColorLightBlue2, 'S');
          if (nvl(f($row,'envelope_1'),'')!='')    retornaArrayDias(f($row,'envelope_1'), f($row,'envelope_1'), $w_cores, $conTrBgColorLightBlue2, 'S');
          if (nvl(f($row,'envelope_2'),'')!='')    retornaArrayDias(f($row,'envelope_2'), f($row,'envelope_2'), $w_cores, $conTrBgColorLightBlue2, 'S');
          if (nvl(f($row,'envelope_3'),'')!='')    retornaArrayDias(f($row,'envelope_3'), f($row,'envelope_3'), $w_cores, $conTrBgColorLightBlue2, 'S');
        }
      }

      if (nvl($w_viagem,'')!='') {
        $sql = new db_getLinkData; $RSMenu_Viagem = $sql->getInstanceOf($dbms,$w_cliente,'PDINICIAL');
        
        // Pend�ncias de presta��o de contas
        $sql = new db_getSolicList; $RS_Pendencia = $sql->getInstanceOf($dbms,f($RSMenu_Viagem,'sq_menu'),$w_usuario,'PDMESA',4,
            null,null,null,null,'S',null,null,null,null,null,null,
            null, null, null, null, null, null, null,null, null, null, null, null, null, null, $w_usuario);
        $RS_Pendencia = SortArray($RS_Pendencia,'inicio', 'desc', 'fim', 'desc');
        
        // Viagens no per�odo do calend�rio da mesa de trabalho
        $sql = new db_getSolicList; $RS_Viagem = $sql->getInstanceOf($dbms,f($RSMenu_Viagem,'sq_menu'),$w_usuario,'PDMESA',4,
            formataDataEdicao($w_inicio),formataDataEdicao($w_fim),null,null,null,null,null,null,null,null,null,
            null, null, null, null, null, null, null,null, null, null, null, null, null, null, $w_usuario);
        $RS_Viagem = SortArray($RS_Viagem,'inicio', 'desc', 'fim', 'desc');

        // Cria arrays com cada dia do per�odo, definindo o texto e a cor de fundo para exibi��o no calend�rio
        foreach($RS_Viagem as $row) {
          $w_saida   = f($row,'phpdt_saida');
          $w_chegada = f($row,'phpdt_chegada');
          if (f($row,'concluida')=='S') {
            retornaArrayDias(f($row,'phpdt_saida'), f($row,'phpdt_chegada'), $w_datas, 'Viagem a servi�o\r\nSitua��o: Finalizada', 'N');
          } elseif (f($row,'sg_tramite')=='AE' ||f($row,'sg_tramite')=='EE') {
            retornaArrayDias(f($row,'phpdt_saida'), f($row,'phpdt_chegada'), $w_datas, 'Viagem a servi�o\r\nSitua��o: Confirmada', 'N');
          } else {
            retornaArrayDias(f($row,'phpdt_saida'), f($row,'phpdt_chegada'), $w_datas, 'Viagem a servi�o\r\nSitua��o: Prevista', 'N');
          }
          $w_datas[formataDataEdicao($w_saida)]['valor']= str_replace('servi�o','servi�o (sa�da �s '.date('H:i',$w_saida).'h)',$w_datas[formataDataEdicao($w_saida)]['valor']);
          $w_datas[formataDataEdicao($w_chegada)]['valor']= str_replace('servi�o','servi�o (chegada �s '.date('H:i',$w_chegada).'h)',$w_datas[formataDataEdicao($w_chegada)]['valor']);
        }
        reset($RS_Viagem);
        foreach($RS_Viagem as $row) {
          $w_saida   = f($row,'phpdt_saida');
          $w_chegada = f($row,'phpdt_chegada');
          retornaArrayDias(f($row,'phpdt_saida'), f($row,'phpdt_chegada'), $w_cores, $conTrBgColorLightRed1, 'N');
          if (date('H',$w_saida)>13)   $w_cores[formataDataEdicao($w_saida)]['valor']   = $conTrBgColorLightRed2;
          if (date('H',$w_chegada)<14) $w_cores[formataDataEdicao($w_chegada)]['valor'] = $conTrBgColorLightRed2;
        }
      }

      if (nvl($w_pessoal,'')!='') {
        $sql = new db_getAfastamento; $RS_Afast = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,null,null,null,formataDataEdicao($w_inicio),formataDataEdicao($w_fim),null,null,null,null);
        $RS_Afast = SortArray($RS_Afast,'inicio_data','desc','inicio_periodo','asc','fim_data','desc','inicio_periodo','asc'); 
        // Cria arrays com cada dia do per�odo, definindo o texto e a cor de fundo para exibi��o no calend�rio
        foreach($RS_Afast as $row) retornaArrayDias(f($row,'inicio_data'), f($row,'fim_data'), $w_datas, f($row,'nm_tipo_afastamento'), 'S');
        foreach($RS_Afast as $row) retornaArrayDias(f($row,'inicio_data'), f($row,'fim_data'), $w_cores, $conTrBgColorLightRed1, 'S');
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
    
    // Exibe calend�rio e suas ocorr�ncias ==============
    ShowHTML('          <td width="'.$width.'" align="center"><table border="1" cellpadding=0 cellspacing=0>');
    ShowHTML('            <tr><td colspan=3 width="100%"><table width="100%" border=0 cellpadding=0 cellspacing=0><tr>');
    ShowHTML('              <td bgcolor="'.$conTrBgColor.'"><A class="hl" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&w_mes='.$w_mes1.'&w_ano='.$w_ano1.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'"><<<</A>');
    ShowHTML('              <td align="center" bgcolor="'.$conTrBgColor.'"><b>Calend�rio '.f($RS_Cliente,'nome_resumido').' ('.f($RS_Unidade,'nm_cidade').')</td>');
    ShowHTML('              <td align="right" bgcolor="'.$conTrBgColor.'"><A class="hl" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&w_mes='.$w_mes3.'&w_ano='.$w_ano3.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'">>>></A>');
    ShowHTML('              </table>');
    // Vari�veis para controle de exibi��o do cabe�alho das datas especiais
    $w_detalhe1 = false;
    $w_detalhe2 = false;
    $w_detalhe3 = false;
    ShowHTML('            <tr valign="top">');
    ShowHTML('              <td align="center">'.montaCalendario($RS_Ano[$w_ano1],$w_mes1.$w_ano1,$w_datas,$w_cores,$w_detalhe1).' </td>');
    ShowHTML('              <td align="center">'.montaCalendario($RS_Ano[$w_ano],$w_mes.$w_ano,$w_datas,$w_cores,$w_detalhe2).' </td>');
    ShowHTML('              <td align="center">'.montaCalendario($RS_Ano[$w_ano3],$w_mes3.$w_ano3,$w_datas,$w_cores,$w_detalhe3).' </td>');
      
    if ($w_detalhe1 || $w_detalhe2 || $w_detalhe3) {
      ShowHTML('            <tr><td colspan=3 bgcolor="'.$conTrBgColor.'">');
      if ((count($RS_Viagem)>0 && nvl($w_viagem ,'')!='') || (count($RS_Afast)>0 && nvl($w_pessoal,'')!='')) {
        ShowHTML('              <b>Observa��es:<ul>');
        ShowHTML('              <li>Clique sobre o dia em destaque para ver detalhes.');
        ShowHTML('              <li>A cor vermelha indica aus�ncias de '.$_SESSION['NOME_RESUMIDO'].'.');
        ShowHTML('              </ul>');
      } else {
        ShowHTML('              <b>Clique sobre o dia em destaque para ver detalhes.</b>');
      }
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
    // Final da exibi��o do calend�rio e suas ocorr�ncias ==============
    if ($w_indicador=='S' || nvl($w_viagem ,'')!='' || nvl($w_compras ,'')!='' || nvl($w_pessoal,'')!='') {
      ShowHTML('          <td width="'.$width.'" align="center">');

      // Exibi��o de indicadores que tenham alguma aferi��o ==============
      if ($w_indicador=='S') {
        // Recupera o menu da p�gina de indicadorees
        ShowHTML('            <table border=0 cellpadding=0 cellspacing=0 width="100%">');
        ShowHTML('              <tr><td><b>INDICADORES</b>');
        foreach($RS_Indicador as $row) ShowHTML('              <tr><td><A class="HL" HREF="mod_pe/indicador.php?par=FramesAfericao&R='.$w_pagina.$par.'&O=L&w_troca=p_indicador&p_tipo_indicador='.f($row,'chave').'&p_pesquisa=livre&p_volta=mesa&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe os indicadores deste tipo.">'.f($row,'nome').'</a></td></tr>');
        ShowHTML('            </table><br>');
      }
      // Final da exibi��o de indicadores ================================

      $w_nome_mes1 = upper(mesAno(date('F',toDate('01/'.$w_mes1.'/'.$w_ano1)),'resumido'));
      $w_nome_mes3 = upper(mesAno(date('F',toDate('01/'.$w_mes3.'/'.$w_ano3)),'resumido').'/'.$w_ano3);

      // Pend�ncias de presta��o de contas de viagens
      if (count($RS_Pendencia)>0 && nvl($w_viagem ,'')!='') {
        ShowHTML('          <P>');
        ShowHTML('          <table border=1 cellpadding=0 cellspacing=0 width="100%">');
        ShowHTML('            <tr><td><table border=0 cellpadding=0 cellspacing=0 width="100%">');
        ShowHTML('              <tr><td align="center"><b>PEND�NCIAS DE PRESTA��O DE CONTAS DE VIAGENS</b><br>');
        ShowHTML('                <b><font color="red">ATEN��O: <u>Preste contas das viagens abaixo e envie para a fase seguinte</u> para evitar bloqueio dos adiantamentos de di�rias!</font></b>');
        ShowHTML('              <tr><td>');
        ShowHTML('                <table width="100%" border="1" cellspacing=0 bgcolor="'.$conTrBgColor.'">');
        ShowHTML('                  <tr align="center" valign="middle">');
        ShowHTML('                    <td><b>In�cio</td>');
        ShowHTML('                    <td><b>T�rmino</td>');
        ShowHTML('                    <td><b>N�</td>');
        ShowHTML('                    <td><b>Destinos</td>');
        ShowHTML('                    <td><b>Opera��o</td>');
        reset($RS_Pendencia);
        $w_cor = $w_cor=$conTrBgColor;
        $i = 0;
        foreach($RS_Pendencia as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('                  <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('                    <td align="center">'.Nvl(date(d.'/'.m.', '.H.':'.i,f($row,'phpdt_saida')),'-').'</td>');
          ShowHTML('                    <td align="center">'.Nvl(date(d.'/'.m.', '.H.':'.i,f($row,'phpdt_chegada')),'-').'</td>');
          ShowHTML('                    <td nowrap>');
          ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
          ShowHTML('                      <A class="HL" HREF="'.substr(f($RSMenu_Viagem,'link'),0,strpos(f($RSMenu_Viagem,'link'),'=')).'=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.f($RSMenu_Viagem,'p1').'&P2='.f($RSMenu_Viagem,'p2').'&P3='.f($RSMenu_Viagem,'p3').'&P4='.f($RSMenu_Viagem,'p4').'&TP='.$TP.'&SG='.f($RSMenu_Viagem,'sigla').MontaFiltro('GET').'" title="Exibe as informa��es deste registro.">'.f($row,'codigo_interno').'&nbsp;</a>');
          ShowHTML('                    <td>'.f($row,'trechos').'&nbsp;</td>');
          if (!$i) ShowHTML('                    <td rowspan="'.count($RS_Pendencia).'"><A class="hl" HREF="'.f($RSMenu_Viagem,'link').'&O=L&p_atraso=S&P1=2&P2='.f($RSMenu_Viagem,'p2').'&P3='.f($RSMenu_Viagem,'p3').'&P4='.f($RSMenu_Viagem,'p4').'&TP='.$TP.'&SG='.f($RSMenu_Viagem,'sigla').MontaFiltro('GET').'" title="Informar os dados da presta��o de contas.">Prestar contas</A>&nbsp</td>');
          ShowHTML('                  </tr>');
          $i++;
        }
        ShowHTML('                </table>');
        ShowHTML('              </table>');
        ShowHTML('          </P>');
      }
      // Final da exibi��o das pend�ncias de presta��o de contas de viagens
  
      // Exibi��o de datas de licita��es ==============
      if (count($RS_Compras)>0 && nvl($w_compras ,'')!='') {
        // Cria array ordenado por data, a partir do cursor
        unset($w_array);
        foreach($RS_Compras as $row) {
          if (nvl(f($row,'data_abertura'),'')!='') {
            $w_array[date(Ymd,f($row,'data_abertura')).'-'.f($row,'codigo_interno').'A'] = $row;
            $w_array[date(Ymd,f($row,'data_abertura')).'-'.f($row,'codigo_interno').'A']['data'] = f($row,'phpdt_data_abertura');
            $w_array[date(Ymd,f($row,'data_abertura')).'-'.f($row,'codigo_interno').'A']['evento'] = 'Receb.prop.';
          }
          if (nvl(f($row,'envelope_1'),'')!='') {
            $w_array[date(Ymd,f($row,'envelope_1')).'-'.f($row,'codigo_interno').'E1'] = $row;
            $w_array[date(Ymd,f($row,'envelope_1')).'-'.f($row,'codigo_interno').'E1']['data'] = f($row,'phpdt_envelope_1');
            $w_array[date(Ymd,f($row,'envelope_1')).'-'.f($row,'codigo_interno').'E1']['evento'] = 'Abert.env.1';
          }
          if (nvl(f($row,'envelope_2'),'')!='') {
            $w_array[date(Ymd,f($row,'envelope_2')).'-'.f($row,'codigo_interno').'E2'] = $row;
            $w_array[date(Ymd,f($row,'envelope_2')).'-'.f($row,'codigo_interno').'E2']['data'] = f($row,'phpdt_envelope_2');
            $w_array[date(Ymd,f($row,'envelope_2')).'-'.f($row,'codigo_interno').'E2']['evento'] = 'Abert.env.2';
          }
          if (nvl(f($row,'envelope_3'),'')!='') {
            $w_array[date(Ymd,f($row,'envelope_3')).'-'.f($row,'codigo_interno').'E3'] = $row;
            $w_array[date(Ymd,f($row,'envelope_3')).'-'.f($row,'codigo_interno').'E3']['data'] = f($row,'phpdt_envelope_3');
            $w_array[date(Ymd,f($row,'envelope_3')).'-'.f($row,'codigo_interno').'E3']['evento'] = 'Abert.env.3';
          }
        }

        if (count($w_array)) {
          // Ordena o array pela data
          $w_array = SortArray($w_array,'data','asc','codigo_interno','asc'); 
          
          ShowHTML('          <table border=1 cellpadding=0 cellspacing=0 width="100%">');
          ShowHTML('            <tr><td><table border=0 cellpadding=0 cellspacing=0 width="100%">');
          if ($w_ano1!=$w_ano3) {
            ShowHTML('              <tr><td align="center"><b>LICITA��ES ('.$w_nome_mes1.'/'.$w_ano1.' - '.$w_nome_mes3.')</b><br>');
          } else {
            ShowHTML('              <tr><td align="center"><b>LICITA��ES ('.$w_nome_mes1.'-'.$w_nome_mes3.')</b><br>');
          }
          // Exibe as licita��es permitidas ao usu�rio logado
          ShowHTML('              <tr><td>');
          ShowHTML('                <table width="100%" border="1" cellspacing=0 bgcolor="'.$conTrBgColor.'">');
          ShowHTML('                  <tr align="center" valign="middle">');
          ShowHTML('                    <td><b>Data</td>');
          ShowHTML('                    <td><b>Evento</td>');
          ShowHTML('                    <td><b>N�mero</td>');
          ShowHTML('                    <td width="1">&nbsp;</td>');
          ShowHTML('                    <td><b>Vincula��o</td>');
          ShowHTML('                    <td><b>Solicitante</td>');
          ShowHTML('                    <td><b>Executor</td>');
          $w_cor = $w_cor=$conTrBgColor;
          // Exibe o array com as datas
          foreach($w_array as $row) {
            if (nvl(f($row,'data_abertura'),'')!='' || nvl(f($row,'envelope_1'),'')!='' || nvl(f($row,'envelope_2'),'')!='' || nvl(f($row,'envelope_3'),'')!='') {
              $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
              ShowHTML('                  <tr bgcolor="'.$w_cor.'" valign="top">');
              ShowHTML('                    <td nowrap align="center">&nbsp;'.formataDataEdicao(f($row,'data'),5).((date('H:i',f($row,'data'))!='00:00') ? ' '.date('H:i',f($row,'data')) : '').'&nbsp;</td>');
              ShowHTML('                    <td>'.f($row,'evento').'</td>');
              ShowHTML('                    <td nowrap width="1%">');
              ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),f($row,'data_abertura'),f($row,'data_homologacao'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
              ShowHTML('                      <A class="HL" HREF="'.substr(f($RSMenu_Compras,'link'),0,strpos(f($RSMenu_Compras,'link'),'=')).'=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.f($RSMenu_Compras,'p1').'&P2='.f($RSMenu_Compras,'p2').'&P3='.f($RSMenu_Compras,'p3').'&P4='.f($RSMenu_Compras,'p4').'&TP='.$TP.'&SG='.f($RSMenu_Compras,'sigla').MontaFiltro('GET').'" title="Exibe as informa��es deste registro.">'.f($row,'codigo_interno').'&nbsp;</a>');
              ShowHTML('                    </td>');
              ShowHTML('                    <td width="1">'.ExibeAnotacao('../',$w_cliente,null,f($row,'sq_siw_solicitacao'),f($row,'codigo_interno')).'</td>');
              if ($_SESSION['INTERNO']=='S') {
                if ($w_cliente==6881)                    ShowHTML('                    <td>'.f($row,'sg_cc').'</td>');
                elseif (Nvl(f($row,'dados_pai'),'')!='') ShowHTML('                    <td>'.exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai')).'</td>');
                else                                     ShowHTML('                    <td>---</td>');
              }
              ShowHTML('                    <td>&nbsp;'.ExibeUnidade('../',$w_cliente,f($row,'sg_unidade_resp'),f($row,'sq_unidade'),$TP).'&nbsp;</td>');
              ShowHTML('                    <td>'.Nvl(f($row,'nm_exec'),'---').'</td>');
              ShowHTML('                  </tr>');
            }
          }
          ShowHTML('                </table>');
          ShowHTML('              </table>');
          
        }
      }
      // Final da exibi��o de licita��es ================================
      
      if ((count($RS_Viagem)>0 && nvl($w_viagem ,'')!='') || (count($RS_Afast)>0 && nvl($w_pessoal,'')!='')) {
        ShowHTML('          <P>');
        ShowHTML('          <table border=1 cellpadding=0 cellspacing=0 width="100%">');
        ShowHTML('            <tr><td><table border=0 cellpadding=0 cellspacing=0 width="100%">');
        if ($w_ano1!=$w_ano3) {
          ShowHTML('              <tr><td align="center"><b>AUS�NCIAS ('.$w_nome_mes1.'/'.$w_ano1.' - '.$w_nome_mes3.')</b><br>');
        } else {
          ShowHTML('              <tr><td align="center"><b>AUS�NCIAS ('.$w_nome_mes1.'-'.$w_nome_mes3.')</b><br>');
        }
        // Exibe as viagens a servi�o do usu�rio logado
        if (count($RS_Viagem)>0 && nvl($w_viagem ,'')!='') {
          ShowHTML('              <tr><td>');
          ShowHTML('                <b>VIAGENS A SERVI�O</b>');
          ShowHTML('                <table width="100%" border="1" cellspacing=0 bgcolor="'.$conTrBgColor.'">');
          ShowHTML('                  <tr align="center" valign="middle">');
          ShowHTML('                    <td><b>In�cio</td>');
          ShowHTML('                    <td><b>T�rmino</td>');
          ShowHTML('                    <td><b>N�</td>');
          ShowHTML('                    <td><b>Destinos</td>');
          reset($RS_Viagem);
          $w_cor = $w_cor=$conTrBgColor;
          if (count($RS_Viagem)==0) {
            ShowHTML('                  <tr bgcolor="'.$w_cor.'" valign="top"><td colspan=4 align="center"><b>N�o foram encontrados registros.');
          } else {
            foreach($RS_Viagem as $row) {
              $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
              ShowHTML('                  <tr bgcolor="'.$w_cor.'" valign="top">');
              ShowHTML('                    <td align="center">'.Nvl(date(d.'/'.m.', '.H.':'.i,f($row,'phpdt_saida')),'-').'</td>');
              ShowHTML('                    <td align="center">'.Nvl(date(d.'/'.m.', '.H.':'.i,f($row,'phpdt_chegada')),'-').'</td>');
              ShowHTML('                    <td nowrap>');
              ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
              ShowHTML('                      <A class="HL" HREF="'.substr(f($RSMenu_Viagem,'link'),0,strpos(f($RSMenu_Viagem,'link'),'=')).'=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.f($RSMenu_Viagem,'p1').'&P2='.f($RSMenu_Viagem,'p2').'&P3='.f($RSMenu_Viagem,'p3').'&P4='.f($RSMenu_Viagem,'p4').'&TP='.$TP.'&SG='.f($RSMenu_Viagem,'sigla').MontaFiltro('GET').'" title="Exibe as informa��es deste registro.">'.f($row,'codigo_interno').'&nbsp;</a>');
              ShowHTML('                    <td>'.f($row,'trechos').'&nbsp;</td>');
              ShowHTML('                  </tr>');
            }
          }
          ShowHTML('                </table>');
        }

        // Exibe afastamentos do usu�rio logado
        if (count($RS_Afast)>0 && nvl($w_pessoal,'')!='') {
          ShowHTML('              <tr><td><br>');
          // Mostra os per�odos de indisponibilidade
          ShowHTML('                <b>AFASTAMENTOS</b>');
          ShowHTML('                <table width="100%" border="1" cellspacing=0 bgcolor="'.$conTrBgColor.'">');
          ShowHTML('                  <tr align="center" valign="top"><td><b>In�cio<td><b>T�rmino<td><b>Dias<td><b>Tipo');
          reset($RS_Afast);
          $w_cor = $w_cor=$conTrBgColor;
          if (count($RS_Afast)==0) {
            ShowHTML('                  <tr bgcolor="'.$w_cor.'" valign="top"><td colspan=6 align="center"><b>N�o foram encontrados registros.');
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
        ShowHTML('          </P>');
      }
    }
    ShowHTML('        </table>');
    ShowHTML('      </table>');
  } else {
    ScriptOpen("JavaScript");
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
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
  ShowHTML('  <td align="right"><a class="SS" href="'.$conRootSIW.f($RS_Volta,'link').'&P1='.f($RS_Volta,'p1').'&P2='.f($RS_Volta,'p2').'&P3='.f($RS_Volta,'p3').'&P4='.f($RS_Volta,'p4').'&TP='.f($RS_Volta,'nome').'&SG='.f($RS_Volta,'sigla').montaFiltro('GET').'" target="content">Voltar para '.f($RS_Volta,'nome').'</a>');
  ShowHTML('<tr><td colspan=2><hr>');
  ShowHTML('</table>');
  ShowHTML('<table border="0" width="100%">');
  if ($O=='L') {
  // Recupera solicita��es a serem listadas
    $sql = new db_getAlerta; $RS_Solic = $sql->getInstanceOf($dbms, $w_cliente, $w_usuario, 'SOLICGERAL', 'N', null);
    $RS_Solic = SortArray($RS_Solic, 'cliente', 'asc', 'usuario', 'asc', 'nm_modulo','asc', 'nm_servico', 'asc', 'titulo', 'asc');

    // Recupera pacotes de trabalho a serem listados
    $sql = new db_getAlerta; $RS_Pacote = $sql->getInstanceOf($dbms, $w_cliente, $w_usuario, 'PACOTE', 'N', null);
    $RS_Pacote = SortArray($RS_Pacote, 'cliente', 'asc', 'usuario', 'asc', 'nm_projeto','asc', 'cd_ordem', 'asc');

    // Recupera banco de horas
    $sql = new db_getAlerta; $RS_Horas = $sql->getInstanceOf($dbms, $w_cliente, $w_usuario, 'HORAS', 'N', null);
    
    $texto = VisualAlerta($w_cliente, $w_usuario, 'TELA', $RS_Solic, $RS_Pacote, $RS_Horas);
    if (!$texto) {
      ShowHtml('<div align="center"><font color="red"><b>Nenhum registro encontrado!</b></font></div>');
    } else {
      ShowHtml($texto);
    }
  } else {
    ScriptOpen("JavaScript");
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  Rodape();
}

// =========================================================================
// Rotina de consulta a lan�amentos
// -------------------------------------------------------------------------
function Consulta() {
  extract($GLOBALS);
  global $SG, $w_TP;
  
  $w_TP = $TP.' - Consulta lan�amento financeiro';
  
  $w_codigo = $_REQUEST['w_codigo'];
  $sql = new db_getSolicFN; $RS = $sql->getInstanceOf($dbms,null,$w_usuario,null,5,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
        $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
        $p_uorg_resp, $w_codigo, $p_prazo, $p_fase, $p_sqcc, $p_projeto, null, $p_sq_acao_ppa, $p_sq_orprior, $p_empenho);
  if ($p_ordena>'') {
    $lista = explode(',',str_replace(' ',',',$p_ordena));
    $RS = SortArray($RS,$lista[0],$lista[1],'dt_pagamento','asc','ord_codigo_interno','asc');
  } else {
    $RS = SortArray($RS,'dt_pagamento','asc','ord_codigo_interno','asc');
  }

  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=tesouraria.php?par=inicial&P1=0&P2=1&P3='.$P3.'&P4='.$P4.'&TP='.$TP.montaFiltro('GET').'">');
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem</TITLE>');
  ScriptOpen('Javascript');
  openBox('reload');
  ValidateOpen('Validacao');
  Validate('w_codigo','C�digo lan�amento','','1','8','90','1','1');
  ShowHTML('  disAll();');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');

  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad="document.Form.w_codigo.focus();"');
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();

  AbreForm('Form',$w_pagina.'Consulta','POST','return(Validacao(this));',null,0,3,1,null,$TP,$SG,$R,'L');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');

  // Filtro
  ShowHTML('<tr><td>');
  ShowHTML('  <table border="0" cellpadding="5" cellspacing="0" width="100%">');
  ShowHTML('  <tr valign="top">');
  ShowHTML('    <td>');
  ShowHTML('    Buscar pagamento: <INPUT CLASS="sti" TYPE="text" NAME="w_codigo" size="18" maxlength="18" VALUE="'.$w_codigo.'" title="Informe o c�digo do pagamento ou recebimento desejado.">');
  ShowHTML('    <input type="image" name="submit" src="images/Folder/Explorer.gif" style="vertical-align:middle;width:15px;height:15px" border=0>');
  $sql = new db_getLinkData; $RS_Volta = $sql->getInstanceOf($dbms,$w_cliente,'MESA');
  ShowHTML('    <td align="right"><a class="SS" href="'.$conRootSIW.f($RS_Volta,'link').'&P1='.f($RS_Volta,'p1').'&P2='.f($RS_Volta,'p2').'&P3='.f($RS_Volta,'p3').'&P4='.f($RS_Volta,'p4').'&TP=<img src='.f($RS_Volta,'imagem').' BORDER=0>'.f($RS_Volta,'nome').'&SG='.f($RS_Volta,'sigla').'">[Voltar para '.f($RS_Volta,'nome').']</a>');
  ShowHTML('  </table>');

  ShowHTML('<tr><td><hr>');

  ShowHTML('<tr><td>');
  ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  if (count($RS)!=1) {
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center"><b>N�o foi encontrado pagamento nem recebimento com o c�digo <b>'.$w_codigo.'</b>.</td></tr>');
  } else {
    // Exibe a visualiza��o do documento
    foreach($RS as $row) {
      $SG = f($row,'sigla');
      ShowHTML(VisualLancamento(f($row,'sq_siw_solicitacao'),'L',$w_usuario,1,'HTML'));
    }
  }
  ShowHTML('    </table>');
  ShowHTML('  </td>');
  ShowHTML('</tr>');
  ShowHTML('</table>');

  ShowHtml('    </form');
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

  switch ($par) {
  case 'MESA':      Mesa();       break;
  case 'ALERTA':    Alerta();     break;
  case 'CONSULTA':  Consulta();   break;
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

