<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getCcData.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getMoeda.php');
include_once($w_dir_volta.'classes/sp/db_getSolicCL.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicEtapa.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'funcoes/selecaoMoeda.php');
// =========================================================================
//  gr_pedido.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Mapa resumido de licitações, solicitado pela UNESCO
// Mail     : alex@sbpi.com.br
// Criação  : 26/08/2014 10:00
// Versao   : 1.0.0.0
// Local    : Brasília - DF
// -------------------------------------------------------------------------
// 
// Parâmetros recebidos:
//    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
//    O (operação)   = L   : Listagem
//                   = P   : Filtragem
//                   = V   : Geração de gráfico
//                   = W   : Geração de documento no formato MS-Word (Office 2003)

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

$w_assinatura   = $_REQUEST['w_assinatura'];
$w_pagina       = 'gr_pedido.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'cl_unesco/';
$w_troca        = $_REQUEST['w_troca'];
$w_embed        = '';

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ($O=='') $O='P';

switch ($O) {
  case 'P': $w_TP = $TP . ' - Filtragem';   break;
  case 'V': $w_TP = $TP . ' - Gráfico';     break;
  default:  $w_TP = $TP . ' - Listagem';    break;
} 

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = $P2;
$w_ano      = RetornaAno();

$p_tipo          = upper($_REQUEST['w_tipo']);
$p_projeto       = upper($_REQUEST['p_projeto']);
$p_atividade     = upper($_REQUEST['p_atividade']);
$p_graf          = upper($_REQUEST['p_graf']);
$p_ativo         = upper($_REQUEST['p_ativo']);
$p_solicitante   = upper($_REQUEST['p_solicitante']);
$p_prioridade    = upper($_REQUEST['p_prioridade']);
$p_unidade       = upper($_REQUEST['p_unidade']);
$p_proponente    = upper($_REQUEST['p_proponente']);
$p_usu_resp      = upper($_REQUEST['p_usu_resp']);
$p_ordena        = lower($_REQUEST['p_ordena']);
$p_ini_i         = upper($_REQUEST['p_ini_i']);
$p_ini_f         = upper($_REQUEST['p_ini_f']);
$p_fim_i         = upper($_REQUEST['p_fim_i']);
$p_fim_f         = upper($_REQUEST['p_fim_f']);
$p_atraso        = upper($_REQUEST['p_atraso']);
$p_acao_ppa      = upper($_REQUEST['p_acao_ppa']);
$p_empenho       = upper($_REQUEST['p_empenho']);
$p_chave         = upper($_REQUEST['p_chave']);
$p_assunto       = upper($_REQUEST['p_assunto']);
$p_tipo_material = upper($_REQUEST['p_tipo_material']);
$p_seq_protocolo = upper($_REQUEST['p_seq_protocolo']);
$p_situacao      = upper($_REQUEST['p_situacao']);
$p_ano_protocolo = upper($_REQUEST['p_ano_protocolo']);
$p_pais          = upper($_REQUEST['p_pais']);
$p_regiao        = upper($_REQUEST['p_regiao']);
$p_uf            = upper($_REQUEST['p_uf']);
$p_cidade        = upper($_REQUEST['p_cidade']);
$p_uorg_resp     = upper($_REQUEST['p_uorg_resp']);
$p_palavra       = upper($_REQUEST['p_palavra']);
$p_prazo         = upper($_REQUEST['p_prazo']);
$p_fase          = explodeArray($_REQUEST['p_fase']);
$p_sqcc          = upper($_REQUEST['p_sqcc']);
$p_agrega        = upper($_REQUEST['p_agrega']);
$p_tamanho       = upper($_REQUEST['p_tamanho']);
$p_ano           = $_REQUEST['p_ano'];
$p_inicio        = $_REQUEST['p_inicio'];
$p_final         = $_REQUEST['p_final'];
$p_moeda         = $_REQUEST['p_moeda']; 

// Recupera a configuração do serviço
$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);

// Variável para identificar a sigla do serviço
$sigla = 'GRCL';

// Executar a consulta com os parâmetros abaixo
$p_graf         = 'S'; // Inibe exibição do gráfico
$O              = 'L'; // Executa a consulta ao invés de pedir os critérios de busca
$p_tamanho      = 'S'; // Limita exibição do objeto
$p_agrega       = 'GRCLCAPA'; // Agrega por unidade solicitante, retornando licitações do ano indicado.'
$p_fase         = '3315,3316'; // Em execução e Concluída

// Trata o valor inicial da moeda, que deve ser Real.
$sql = new db_getMoeda; $RS = $sql->getInstanceOf($dbms, $p_moeda, 'ATIVO', null, null, ((nvl($p_moeda,'')=='') ? 'BRL' : ''), null);
foreach($RS as $row) {
  $p_moeda   = f($row,'sq_moeda');
  $w_simbolo = f($row,'simbolo'); 
}

// Verifica as datas inicial e final de existência de licitações
$sql = new db_getSolicCL; $RS1 = $sql->getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
    null,null,null,null,$p_atraso,$p_solicitante,
    $p_unidade,null,$p_ativo,$p_proponente,
    $p_chave, $p_assunto, $p_tipo_material, $p_seq_protocolo, $p_situacao, $p_ano_protocolo, $p_usu_resp,
    $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade,
    $p_acao_ppa, null, $p_empenho, null, null,null,null,null,null,null,null,null,null);

foreach($RS1 as $row) {
  if (nvl($p_inicio,'')=='') {
    // Define valores iniciais
    $p_inicio = f($row,'inicio');
    $p_final = f($row,'inicio');
  }
  // Trata data inicial
  if (nvl(f($row,'inicio'),$p_inicio)<$p_inicio)        $p_inicio = f($row,'inicio');
  if (nvl(f($row,'data_abertura'),$p_inicio)<$p_inicio) $p_inicio = f($row,'data_abertura');
  if (nvl(f($row,'envelope_1'),$p_inicio)<$p_inicio)    $p_inicio = f($row,'envelope_1');
  if (nvl(f($row,'envelope_2'),$p_inicio)<$p_inicio)    $p_inicio = f($row,'envelope_2');
  if (nvl(f($row,'envelope_3'),$p_inicio)<$p_inicio)    $p_inicio = f($row,'envelope_3');

  // Trata data final
  if (nvl(f($row,'inicio'),$p_final)>$p_final)              $p_final = f($row,'inicio');
  if (nvl(f($row,'data_abertura'),$p_final)>$p_final)       $p_final = f($row,'data_abertura');
  if (nvl(f($row,'envelope_1'),$p_final)>$p_final)          $p_final = f($row,'envelope_1');
  if (nvl(f($row,'envelope_2'),$p_final)>$p_final)          $p_final = f($row,'envelope_2');
  if (nvl(f($row,'envelope_3'),$p_final)>$p_final)          $p_final = f($row,'envelope_3');
  if (nvl(f($row,'data_homologacao'),$p_final)>$p_final)    $p_final = f($row,'data_homologacao');
  if (nvl(f($row,'data_diario_oficial'),$p_final)>$p_final) $p_final = f($row,'data_diario_oficial');
  if (nvl(f($row,'conclusao'),$p_final)>$p_final)           $p_final = f($row,'conclusao');
}
// Coloca as datas no formato texto
if (nvl($p_inicio,'')!='') $p_inicio = formataDataEdicao($p_inicio);
if (nvl($p_final,'')!='') $p_final = formataDataEdicao($p_final);

if ($p_ano=='T') {
  $p_ini_i      = $p_inicio;
  $p_ini_f      = $p_final;
} else {
  $p_ano        = nvl($p_ano,$w_ano);
  $p_ini_i      = '01/01/'.$p_ano;
  $p_ini_f      = '31/12/'.$p_ano;
}

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Pesquisa gerencial
// -------------------------------------------------------------------------
function Gerencial() {
  extract($GLOBALS);
  global $w_embed;
  
  $w_pag   = 1;
  $w_linha = 0;
  
  if ($O=='L' || $O=='V' || $p_tipo == 'WORD' || $p_tipo=='PDF') {
    // Recupera os dados a partir do filtro
    $sql = new db_getSolicCL; $RS1 = $sql->getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,$p_unidade,null,$p_ativo,$p_proponente,
        $p_chave, $p_assunto, $p_tipo_material, $p_seq_protocolo, $p_situacao, $p_ano_protocolo, $p_usu_resp,$p_uorg_resp, $p_palavra, $p_prazo, $p_fase, 
        $p_sqcc, $p_projeto, $p_atividade,$p_acao_ppa, null, $p_empenho, null, $p_moeda, $p_vencedor, $p_externo, $p_cnpj, $p_fornecedor,
        $p_pais, $p_regiao, $p_uf, $p_cidade);
    $RS1 = SortArray($RS1,'or_unidade_resp', 'asc', 'sg_unidade_resp','asc');
  } 

  $w_linha_filtro = $w_linha;
  $w_linha_pag    = 0;
  headerGeral('P', $p_tipo, $w_chave, 'Consulta de '.f($RS_Menu,'nome'), $w_embed, null, null, $w_linha_pag,$w_filtro);

  if ($w_embed!='WORD') {
    $w_embed = 'HTML';
    Cabecalho();
    head();
    ShowHTML('<TITLE>'.$w_TP.'</TITLE>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ScriptOpen('Javascript');
    ValidateOpen('ValidaBusca');
    ShowHTML('  if (theForm.p_ano.selectedIndex) {');
    ShowHTML('    theForm.p_ini_i.value="01/01/"+theForm.p_ano[theForm.p_ano.selectedIndex].value;');
    ShowHTML('    theForm.p_ini_f.value="31/12/"+theForm.p_ano[theForm.p_ano.selectedIndex].value;');
    ShowHTML('  } else {');
    ShowHTML('    theForm.p_ini_i.value=theForm.p_inicio.value');
    ShowHTML('    theForm.p_ini_f.value=theForm.p_final.value;');
    ShowHTML('  }');
    ShowHtml('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
    BodyOpenClean('onLoad=this.focus();');

    CabecalhoRelatorio($w_cliente,'Consulta de '.f($RS_Menu,'nome'),4);
    ShowHTML('<HR>');
  } 

  ShowHTML('<div align="center"><table width="99%" border=0><tr valign="top"><td><font size=2><b>Licitações processadas entre </b></font><font size=2 color="red"><b>'.$p_ini_i.' e '.$p_ini_f.'</b></font>');
  if ($w_embed!='WORD') {
    $w_ano_i = substr($p_inicio,6);
    $w_ano_f = substr($p_final,6);
    AbreForm('FormBusca',$w_dir.$w_pagina.$par,'POST','return(ValidaBusca(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<input type="Hidden" name="p_atraso" value="N">');
    ShowHTML('<input type="Hidden" name="p_prazo" value="">');
    ShowHTML('<input type="Hidden" name="p_agrega" value="'.$p_agrega.'">');
    ShowHTML('<input type="Hidden" name="p_fase" value="'.$p_fase.'">');
    ShowHTML('<input type="Hidden" name="p_ini_i" value="'.$p_ini_i.'">');
    ShowHTML('<input type="Hidden" name="p_ini_f" value="'.$p_ini_f.'">');
    ShowHTML('<input type="Hidden" name="p_inicio" value="'.$p_inicio.'">');
    ShowHTML('<input type="Hidden" name="p_final" value="'.$p_final.'">');
    ShowHTML('<input type="Hidden" name="p_unidade" value="">');
    ShowHTML(MontaFiltro('POST',true));
    selecaoMoeda('<u>M</u>oeda:','M','Selecione a moeda na relação.',$p_moeda,null,'p_moeda','ATIVO',null,1,'&nbsp;');
    if ($w_ano_f-$w_ano_i || $p_ano=='T') {
      ShowHTML('  <td align="right" TITLE="Se desejar executar a busca em outro período, selecione uma das opções."><b>Buscar em:</b> <SELECT class="STS" NAME="p_ano">');
      ShowHTML('      <option value="T">Todos os anos');
      for ($i=$w_ano_f; $i>=$w_ano_i; $i--) {
        ShowHTML('      <option value="'.$i.'"'.(($p_ano==$i) ? ' SELECTED' : '').'>'.$i);
      }
      ShowHTML('    </select>');
      ShowHTML('    <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
      ShowHTML('  </td>');
      ShowHTML('</tr>');
      ShowHTML('</FORM>');
    }
  }
  ShowHTML('<tr><td>&nbsp;</td></tr>');
  ShowHTML('</table>');

  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="99%">');
  if ($O=='L' || $w_embed == 'WORD') {
    ImprimeCabecalho();
    if (count($RS1)<=0) { 
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      if ($w_embed != 'WORD') {
        ShowHTML('<SCRIPT LANGUAGE="JAVASCRIPT">');
        ShowHTML('  function lista (filtro, cad, exec, conc, atraso) {');
        ShowHTML('    if (filtro != -1) {');
        ShowHTML('     document.Form.p_unidade.value=filtro;');
        ShowHTML('    }');
        ShowHTML('    else document.Form.p_unidade.value="'.$_REQUEST['p_unidade'].'";');
        $sql = new db_getTramiteList; $RS2 = $sql->getInstanceOf($dbms,$P2,null,null,null);
        $RS2 = SortArray($RS2,'ordem','asc');
        $w_fase_exec='';
        foreach($RS2 as $row) {
          if (f($row,'sigla')=='CI') {
            $w_fase_cad=f($row,'sq_siw_tramite');
          } elseif (f($row,'sigla')=='AT') {
            $w_fase_conc=f($row,'sq_siw_tramite');
          } elseif (f($row,'ativo')=='S') {
            $w_fase_exec=$w_fase_exec.','.f($row,'sq_siw_tramite');
          } 
        } 
        ShowHTML('    if (cad >= 0) { document.Form.p_fase.value="'.$w_fase_cad.'"; }');
        ShowHTML('    if (exec >= 0) { document.Form.p_fase.value="'.substr($w_fase_exec,1,100).'"; }');
        ShowHTML('    document.Form.p_prazo.value="";');
        ShowHTML('    if (conc >= 0) {document.Form.p_fase.value='.$w_fase_conc.'; document.Form.p_prazo.value=1;}');
        ShowHTML('    if (cad==-1 && exec==-1 && conc==-1) { document.Form.p_fase.value="'.$p_fase.'"; }');
        ShowHTML('    if (atraso >= 0) { document.Form.p_atraso.value="S"; } else { document.Form.p_atraso.value="'.$_REQUEST['p_atraso'].'"; }');
        ShowHTML('    document.Form.submit();');
        ShowHTML('  }');
        ShowHTML('</SCRIPT>');
        $sql = new db_getMenuData; $RS2 = $sql->getInstanceOf($dbms,$P2);
        AbreForm('Form',f($RS2,'link'),'POST','return(Validacao(this));','Lista',3,$P2,f($RS2,'P3'),null,$w_TP,f($RS2,'sigla'),$w_dir.$w_pagina.$par,'L');
        ShowHTML(MontaFiltro('POST'));
        if ($_REQUEST['p_atraso']=='')  ShowHTML('<input type="Hidden" name="p_atraso" value="N">');
        if ($_REQUEST['p_prazo']=='')   ShowHTML('<input type="Hidden" name="p_prazo" value="">');
        if ($_REQUEST['p_agrega']=='')  ShowHTML('<input type="Hidden" name="p_agrega" value="'.$p_agrega.'">');
        if ($_REQUEST['p_fase']=='')    ShowHTML('<input type="Hidden" name="p_fase" value="'.$p_fase.'">');
        if ($_REQUEST['p_ini_i']=='')   ShowHTML('<input type="Hidden" name="p_ini_i" value="'.$p_ini_i.'">');
        if ($_REQUEST['p_ini_f']=='')   ShowHTML('<input type="Hidden" name="p_ini_f" value="'.$p_ini_f.'">');
        if ($_REQUEST['p_unidade']=='') ShowHTML('<input type="Hidden" name="p_unidade" value="">');
        if ($_REQUEST['p_moeda']=='')   ShowHTML('<input type="Hidden" name="p_moeda" value="'.$p_moeda.'">');
      } 
      $w_nm_quebra  = '';
      $w_reg        = 0;
      $w_qt_quebra  = 0;
      $t_solic      = 0;
      $t_cad        = 0;
      $t_tram       = 0;
      $t_conc       = 0;
      $t_atraso     = 0;
      $t_aviso      = 0;
      $t_valor      = 0;
      $t_acima      = 0;
      $t_custo      = 0;
      $t_totcusto   = 0;
      $t_totsolic   = 0;
      $t_totcad     = 0;
      $t_tottram    = 0;
      $t_totconc    = 0;
      $t_totatraso  = 0;
      $t_totaviso   = 0;
      $t_totvalor   = 0;
      $t_totacima   = 0;
      $v_totcad     = 0;
      $v_tottram    = 0;
      $v_totconc    = 0;
      $v_totatraso  = 0;
      $w_cor = conTrBgColor;
      foreach($RS1 as $row) {
        if ($w_nm_quebra!=f($row,'sg_unidade_resp')) {
          if ($w_qt_quebra>0) {
            ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
            $g_linha[$w_reg]['qtd']  = $t_solic;
            $g_linha[$w_reg]['vlr']  = $t_valor;
          } 
          if ($w_embed != 'WORD' || ($w_embed == 'WORD' && $w_linha<=$w_linha_pag)) {
            // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
            $w_cor = (($w_cor==$conTrBgColor || $w_cor=='') ? $conTrAlternateBgColor : $conTrBgColor);
            ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td align="center"><b>'.f($row,'sg_unidade_resp'));
          } 
          $w_reg       += 1;
          $g_linha[$w_reg]['nome'] = f($row,'sg_unidade_resp');
          $w_nm_quebra  = f($row,'sg_unidade_resp');
          $w_chave      = f($row,'sq_unidade');
          $w_qt_quebra  = 0;
          $t_solic      = 0;
          $t_cad        = 0;
          $t_tram       = 0;
          $t_conc       = 0;
          $t_atraso     = 0;
          $t_aviso      = 0;
          $t_valor      = 0;
          $t_acima      = 0;
          $t_custo      = 0;
          $w_linha     += 1;
        } 
        if (nvl(f($row,'conclusao'),'')=='') {
          if (f($row,'aviso_prox_conc') == 'S' && (f($row,'aviso') <= addDays(time(),-1))) {
            $t_aviso    = $t_aviso+1;
            $t_totaviso = $t_totaviso+1;
          }
          
          if (f($row,'or_tramite')==1) {
            $t_cad      += 1;
            $t_totcad   += 1;
            $v_totcad  += Nvl(f($row,'valor'),0);
          } else {
            $t_tram     += 1;
            $t_tottram  += 1;
            $v_tottram  += Nvl(f($row,'valor'),0);
          } 
        } else {
          // Para a UNESCO t_atraso significa licitações concluídas com situação "Licitação cancelada", 
          // enquanto que t_cont significa licitações concluídas com todas as outras situações.
          if (strpos(upper(f($row,'nm_lcsituacao')),'CANCELADA')!==false) {
            $t_atraso    = $t_atraso + 1;
            $t_totatraso = $t_totatraso + 1;
            $v_totatraso += Nvl(f($row,'valor'),0);
          } else {
            $t_conc=$t_conc+1;
            $t_totconc=$t_totconc+1;
            $v_totconc += Nvl(f($row,'valor'),0);
            if (Nvl(f($row,'valor'),0)<Nvl(f($row,'custo_real'),0)) {
              $t_acima    += 1;
              $t_totacima += 1;
            } 
          }
        } 
        $t_solic        += 1;
        $t_valor        += Nvl(f($row,'valor'),0);
        $t_custo        += Nvl(f($row,'custo_real'),0);

        $t_totvalor     += Nvl(f($row,'valor'),0);
        $t_totcusto     += Nvl(f($row,'custo_real'),0);
        $t_totsolic     += 1;
        $w_qt_quebra    += 1;
      } 
      ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave,$p_agrega);
      $g_linha[$w_reg]['qtd']  = $t_solic;
      $g_linha[$w_reg]['vlr']  = $t_valor;
      //ShowHTML('      <tr><td colspan=11 align="center"><hr noshadow></td></tr>');
       $w_cor = (($w_cor==$conTrBgColor || $w_cor=='') ? $conTrAlternateBgColor : $conTrBgColor);
      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top" align="right">');
      ShowHTML('          <td><b>Totais&nbsp;</td>');
      ImprimeLinha($t_totsolic,$t_totcad,$t_tottram,$t_totconc,$t_totatraso,$t_totaviso,$t_totvalor,$t_totcusto,$t_totacima,-1,$p_agrega);
    } 
    if ($w_embed!='WORD') {
      ShowHTML('      </FORM>');
    }
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    if ($t_totvalor > 0 && $t_totsolic > 0) {
      // Exibe tabelas com quantitativos
      $w_filler = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
      ShowHTML('<tr><td align="center"><br>');
      ShowHTML('<table border="1" width="95%" cellspacing="15">');
      ShowHTML('  <tr valign="top">');
      ShowHTML('    <td width="47%" align="center" bgcolor="#B8DFF5"><br><font size="3"><b>Status das Licitações (Quantidade)</b></font><br><br>');
      ShowHTML('      <table border="1">');
      ShowHTML('        <tr valign="top" bgcolor="'.$conTrAlternateBgColor.'"><td><font size="2"><b>'.$w_filler.'Em andamento'.$w_filler.'<td align="center"><font size="2"><b>'.$w_filler.$t_tottram.$w_filler.'<td align="right"><font size="2"><b>'.$w_filler.formatNumber(100*$t_tottram/$t_totsolic,1).'%'.$w_filler.'</tr>');
      ShowHTML('        <tr valign="top" bgcolor="'.$conTrBgColor.'"><td><font size="2"><b>'.$w_filler.'Cancelada'.$w_filler.'<td align="center"><font size="2"><b>'.$w_filler.$t_totatraso.$w_filler.'<td align="right"><font size="2"><b>'.$w_filler.formatNumber(100*$t_totatraso/$t_totsolic,1).'%'.$w_filler.'</tr>');
      ShowHTML('        <tr valign="top" bgcolor="'.$conTrAlternateBgColor.'"><td><font size="2"><b>'.$w_filler.'Concluída'.$w_filler.'<td align="center"><font size="2"><b>'.$w_filler.$t_totconc.$w_filler.'<td align="right"><font size="2"><b>'.$w_filler.formatNumber(100*$t_totconc/$t_totsolic,1).'%'.$w_filler.'</tr>');
      ShowHTML('      </table><br>');
      ShowHTML('    </td>');
      ShowHTML('    <td width="47%" align="center" bgcolor="#B8DFF5"><br><font size="3"><b>Status das Licitações (Valores '.$w_simbolo.')</b></font><br><br>');
      ShowHTML('      <table border="1">');
      ShowHTML('        <tr valign="top" bgcolor="'.$conTrAlternateBgColor.'"><td><font size="2"><b>'.$w_filler.'Em andamento'.$w_filler.'<td align="right"><font size="2"><b>'.$w_filler.formatNumber($v_tottram).$w_filler.'<td align="right"><font size="2"><b>'.$w_filler.formatNumber(100*$v_tottram/$t_totvalor,1).'%'.$w_filler.'</tr>');
      ShowHTML('        <tr valign="top" bgcolor="'.$conTrBgColor.'"><td><font size="2"><b>'.$w_filler.'Cancelada'.$w_filler.'<td align="right"><font size="2"><b>'.$w_filler.formatNumber($v_totatraso).$w_filler.'<td align="right"><font size="2"><b>'.$w_filler.formatNumber(100*$v_totatraso/$t_totvalor,1).'%'.$w_filler.'</tr>');
      ShowHTML('        <tr valign="top" bgcolor="'.$conTrAlternateBgColor.'"><td><font size="2"><b>'.$w_filler.'Concluída'.$w_filler.'<td align="right"><font size="2"><b>'.$w_filler.formatNumber($v_totconc).$w_filler.'<td align="right"><font size="2"><b>'.$w_filler.formatNumber(100*$v_totconc/$t_totvalor,1).'%'.$w_filler.'</tr>');
      ShowHTML('      </table><br>');
      ShowHTML('    </td>');
      ShowHTML('  </tr>');
      ShowHTML('  <tr valign="top">');
      ShowHTML('    <td width="47%" align="center" bgcolor="#B8DFF5"><br><font size="3"><b>Quantidade de Licitações por Setor</b></font><br><br>');
      ShowHTML('      <table border="1">');
      $w_cor = '';
      foreach($g_linha as $k=>$v) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('        <tr valign="top" bgcolor="'.$w_cor.'"><td><font size="2"><b>'.$w_filler.$v['nome'].$w_filler.'<td align="center"><font size="2"><b>'.$w_filler.$v['qtd'].$w_filler.'<td align="right"><font size="2"><b>'.$w_filler.formatNumber(100*$v['qtd']/$t_totsolic,1).'%'.$w_filler.'</tr>');
      }
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      ShowHTML('        <tr valign="top" bgcolor="'.$w_cor.'"><td><font size="2"><b>'.$w_filler.'TOTAL'.$w_filler.'<td align="center"><font size="2"><b>'.$w_filler.$t_totsolic.$w_filler.'<td align="right">&nbsp;</tr>');
      ShowHTML('      </table><br>');
      ShowHTML('    </td>');
      ShowHTML('    <td width="47%" align="center" bgcolor="#B8DFF5"><br><font size="3"><b>Valor das Licitações por Setor ('.$w_simbolo.')</b></font><br><br>');
      ShowHTML('      <table border="1">');
      $w_cor = '';
      foreach($g_linha as $k=>$v) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('        <tr valign="top" bgcolor="'.$w_cor.'"><td><font size="2"><b>'.$w_filler.$v['nome'].$w_filler.'<td align="right"><font size="2"><b>'.$w_filler.formatNumber($v['vlr']).$w_filler.'<td align="right"><font size="2"><b>'.$w_filler.formatNumber(round(100*$v['vlr']/$t_totvalor,1),1).'%'.$w_filler.'</tr>');
      }
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      ShowHTML('        <tr valign="top" bgcolor="'.$w_cor.'"><td><font size="2"><b>'.$w_filler.'TOTAL'.$w_filler.'<td align="center"><font size="2"><b>'.$w_filler.formatNumber($t_totvalor).$w_filler.'<td align="right">&nbsp;</tr>');
      ShowHTML('      </table><br>');
      ShowHTML('    </td>');
      ShowHTML('  </tr>');
      ShowHTML('</table>');
      ShowHTML('</tr>');
    }
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table></div>');
  if($p_tipo == 'PDF') RodapePdf();
  else                 Rodape();
}

// =========================================================================
// Rotina de impressao do cabecalho
// -------------------------------------------------------------------------
function ImprimeCabecalho() {
  extract($GLOBALS);

  ShowHTML('<tr><td align="center">');
  ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr bgcolor="#DCDCDC" align="center">');
  ShowHTML('          <td><b>Unidade solicitante</td>');
  //ShowHTML('          <td><b>Cadastramento</td>');
  ShowHTML('          <td><b>Em andamento</td>');
  ShowHTML('          <td><b>Canceladas</td>');
  ShowHTML('          <td><b>Concluídas</td>');
  ShowHTML('          <td><b>Total</td>');
  //ShowHTML('          <td><b>Aviso</td>');
  ShowHTML('          <td><b>Valor '.$w_simbolo.'</td>');
  //ShowHTML('          <td><b>$ Real</td>');
  //ShowHTML('          <td><b>Real > Previsto</td>');
  ShowHTML('        </tr>');
} 

// =========================================================================
// Rotina de impressao da linha resumo
// -------------------------------------------------------------------------
function ImprimeLinha($l_solic,$l_cad,$l_tram,$l_conc,$l_atraso,$l_aviso,$l_valor,$l_custo,$l_acima,$l_chave,$l_agrega) {
  extract($GLOBALS);
  
  if ($l_chave<0) $b = '<b>'; else $b = '';

  //if ($l_cad>0 && $w_embed != 'WORD')      ShowHTML('          <td align="center"><a class="hl" href="javascript:lista(\''.$l_chave.'\', 0, -1, -1, -1);" onMouseOver="window.status=\'Exibe os registros.\'; return true" onMouseOut="window.status=\'\'; return true">'.$b.number_format($l_cad,0,',','.').'</a>&nbsp;</td>');                   else ShowHTML('          <td align="center">'.$b.number_format($l_cad,0,',','.').'&nbsp;</td>');
  if ($l_tram>0 && $w_embed != 'WORD')     ShowHTML('          <td align="center"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, 0, -1, -1);" onMouseOver="window.status=\'Exibe os registros.\'; return true" onMouseOut="window.status=\'\'; return true">'.$b.number_format($l_tram,0,',','.').'</a>&nbsp;</td>');                  else ShowHTML('          <td align="center">'.$b.number_format($l_tram,0,',','.').'&nbsp;</td>');
  if ($l_atraso>0 && $w_embed != 'WORD')   ShowHTML('          <td align="center"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, 0);" onMouseOver="window.status=\'Exibe os registros.\'; return true" onMouseOut="window.status=\'\'; return true">'.$b.number_format($l_atraso,0,',','.').'</a>&nbsp;</td>');                else ShowHTML('          <td align="center">'.$b.$l_atraso.'&nbsp;</td>');
  if ($l_conc>0 && $w_embed != 'WORD')     ShowHTML('          <td align="center"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, 0, -1);" onMouseOver="window.status=\'Exibe os registros.\'; return true" onMouseOut="window.status=\'\'; return true">'.$b.number_format($l_conc,0,',','.').'</a>&nbsp;</td>');                  else ShowHTML('          <td align="center">'.$b.number_format($l_conc,0,',','.').'&nbsp;</td>');
  if ($w_embed != 'WORD')                  ShowHTML('          <td align="center"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, -1);" onMouseOver="window.status=\'Exibe os registros.\'; return true" onMouseOut="window.status=\'\'; return true">'.$b.number_format($l_solic,0,',','.').'</a>&nbsp;</td>');         else ShowHTML('          <td align="center">'.$b.number_format($l_solic,0,',','.').'&nbsp;</td>');
  ShowHTML('          <td align="right">'.$b.number_format($l_valor,2,',','.').'&nbsp;</td>');
  //ShowHTML('          <td align="right">'.$b.number_format($l_custo,2,',','.').'&nbsp;</td>');
  /*
  if ($l_aviso>0 && $O=='L') {
    ShowHTML('          <td align="right"><font color="red"><b>'.$b.number_format($l_aviso,0,',','.').'&nbsp;</td>');
  } else {
    ShowHTML('          <td align="right"><b>'.$b.$l_aviso.'&nbsp;</td>');
  } 
  if ($l_acima>0) {
    ShowHTML('          <td align="right"><font color="red"><b>'.$b.number_format($l_acima,0,',','.').'&nbsp;</td>');
  } else {
    ShowHTML('          <td align="right"><b>'.$b.$l_acima.'&nbsp;</td>');
  } 
  */
  ShowHTML('        </tr>');
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);

  switch ($par) {
  case 'GERENCIAL': Gerencial(); break;
  default:
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
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
    break;
  } 
} 
?>
