<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'funcoes_valida.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getEsquema.php');
include_once($w_dir_volta.'classes/sp/db_getEsquemaTabela.php');
include_once($w_dir_volta.'classes/sp/db_getEsquemaAtributo.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getTabela.php');
include_once($w_dir_volta.'classes/sp/db_getColuna.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLAcao_PPA.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLAcao_SIG.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLBase_Geografica.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLDadoFinanceiro_PPA.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLDadoFinanceiro_SIG.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLDadoFisico_PPA.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLDadoFisico_SIG.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLEsfera.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLFonte_PPA.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLFonte_SIG.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLFuncao.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLIndicador_PPA.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLIndicador_SIG.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLLocalizador_PPA.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLMacro_Objetivo.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLMunicipio.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLNatureza.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLOpcao_Estrat.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLOrgao_PPA.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLOrgao_SIG.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLOrgao_Siorg_PPA.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLPeriodicidade.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLPeriodicidade_PPA.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLProduto_PPA.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLProduto_SIG.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLPrograma_PPA.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLPrograma_SIG.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLRegiao.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLSubfuncao.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLTipo_Acao.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLTipo_Acao_PPA.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLTipo_Atualizacao.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLTipo_Despesa.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLTipo_Inclusao_Acao.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLTipo_Orgao_SIG.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLTipo_Programa.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLTipo_Programa_PPA.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLTipo_Restricao.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLTipo_Situacao.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLUnidade_Medida_PPA.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLUnidade_Medida_SIG.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLUnidade_PPA.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLUnidade_SIG.php');
include_once($w_dir_volta.'classes/sp/dml_putXMLRestricaoAcao_SIG.php');
include_once($w_dir_volta.'classes/sp/dml_putDcOcorrencia.php');
include_once($w_dir_volta.'classes/sp/dml_putEsquema.php');
include_once($w_dir_volta.'classes/sp/dml_putEsquemaTabela.php');
include_once($w_dir_volta.'classes/sp/dml_putEsquemaAtributo.php');
include_once($w_dir_volta.'funcoes/formataDataXML.php');
include_once($w_dir_volta.'funcoes/selecaoFormato.php');
include_once($w_dir_volta.'funcoes/selecaoSistema.php');
include_once($w_dir_volta.'funcoes/selecaoUsuario.php');
include_once($w_dir_volta.'funcoes/selecaoTipoTabela.php');
// =========================================================================
//  /sigplan.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Rotinas de integração com o SIGPLAN/MP
// Mail     : celso@sbpi.com.br
// Criacao  : 25/10/2006, 16:20
// Versao   : 1.0.0.0
// Local    : Brasília - DF
// -------------------------------------------------------------------------
// 
// parâmetros recebidos:
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
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);
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
$w_assinatura   = upper($_REQUEST['w_assinatura']);
$w_pagina       = 'sigplan.php?par=';
$w_disabled     = 'ENABLED';
$w_dir          = 'mod_is/';
$w_troca        = $_REQUEST['w_troca'];
$p_nome         = upper($_REQUEST['p_nome']);
$p_tipo         = upper($_REQUEST['p_tipo']);
$p_formato      = upper($_REQUEST['p_formato']);
$p_sq_modulo    = upper($_REQUEST['p_sq_modulo']);
$p_dt_ini       = $_REQUEST['p_dt_ini'];
$p_dt_fim       = $_REQUEST['p_dt_fim'];
$p_ref_ini      = $_REQUEST['p_ref_ini'];
$p_ref_fim      = $_REQUEST['p_ref_fim'];
$p_ordena       = lower($_REQUEST['p_ordena']);
if ($O=='') {
  if ($par=='REL_PPA' || $par=='REL_INICIATIVA')    $O='P';
  else                                              $O='L';
} 
if ($P1==1) $p_tipo='I';
else        $p_tipo='E';
switch ($O) {
  case 'I':   $w_TP=$TP.' - Inclusão';    break;
  case 'A':   $w_TP=$TP.' - Alteração';   break;
  case 'E':   $w_TP=$TP.' - Exclusão';    break;
  case 'P':   $w_TP=$TP.' - Filtragem';   break;
  case 'C':   $w_TP=$TP.' - Cópia';       break;
  case 'V':   $w_TP=$TP.' - Envio';       break;
  case 'H':   $w_TP=$TP.' - Herança';     break;
  case 'O':   $w_TP=$TP.' - Orientações'; break;
  default:    $w_TP=$TP.' - Listagem';    break;
} 
// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();
$w_caminho  = $conFilePhysical.$w_cliente.'/';
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'IS');
foreach($RS as $row){$RS=$row; break;}
$w_sq_modulo = f($RS,'sq_modulo');
// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$sql = new db_getLinkSubMenu; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu = 'Existe';
} else {
  $w_submenu = '';
} 
// Recupera a configuração do serviço
if ($P2>0) {
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$P2);
} else {
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);
}
// Se for sub-menu, pega a configuração do pai
if (f($RS_Menu,'ultimo_nivel')=='S') { 
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 
Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Rotina de importação de arquivos físicos para atualização de dados financeiros
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  $w_sq_esquema     = $_REQUEST['w_sq_esquema'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_sq_esquema   = $_REQUEST['w_sq_esquema'];
    $w_nome         = $_REQUEST['w_nome'];
    $w_descricao    = $_REQUEST['w_descricao'];
    $w_tipo         = $_REQUEST['w_tipo'];
    $w_ativo        = $_REQUEST['w_ativo'];
    $w_formato      = $_REQUEST['w_formato'];
    $w_ws_servidor  = $_REQUEST['w_ws_servidor'];
    $w_ws_url       = $_REQUEST['w_ws_url'];
    $w_ws_acao      = $_REQUEST['w_ws_acao'];
    $w_ws_mensagem  = $_REQUEST['w_ws_mensagem'];
    $w_no_raiz      = $_REQUEST['w_no_raiz'];
  } elseif ($O=='L') {
    // Recupera todos os ws_url para a listagem
    $sql = new db_getEsquema; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,null,$w_sq_modulo,$p_nome,$p_tipo,$p_formato,$p_dt_ini,$p_dt_fim,$p_ref_ini,$p_ref_fim);
    $RS = SortArray($RS,'nome','asc');
  } elseif (!(strpos('AE',$O)===false)) {
    // Recupera todos os ws_url para a listagem
    $sql = new db_getEsquema; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,$w_sq_esquema,null,null,null,null,null,null,null,null);
    foreach($RS as $row){$RS=$row; break;}
    $w_sq_esquema   = f($RS,'sq_esquema');
    $w_nome         = f($RS,'nome');
    $w_descricao    = f($RS,'descricao');
    $w_tipo         = f($RS,'tipo');
    $w_ativo        = f($RS,'ativo');
    $w_formato      = f($RS,'formato');
    $w_ws_servidor  = f($RS,'ws_servidor');
    $w_ws_url       = f($RS,'ws_url');
    $w_ws_acao      = f($RS,'ws_acao');
    $w_ws_mensagem  = f($RS,'ws_mensagem');
    $w_no_raiz      = f($RS,'no_raiz');
  }
  Cabecalho();
  head();
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if (!(strpos('IAE',$O)===false)) {
      if (!(strpos('IA',$O)===false)) {
        Validate('w_nome','Nome','1','1',3,60,'1','1');
        Validate('w_descricao','Descricao','1','',3,500,'1','1');
        Validate('w_no_raiz','Nó raiz','1','1',3,50,'1','1');
        if ($P1==2) Validate('w_formato','Formato','SELECT',1,1,10,'1','1');
        if ($w_formato=='W') {
          Validate('w_ws_servidor','Servidor','1','1',3,100,'1','1');
          Validate('w_ws_url','URL','1','1',3,100,'1','1');
          Validate('w_ws_acao','Ação','1','1',3,100,'1','1');
          Validate('w_ws_mensagem','Mensagem','1','1',3,4000,'1','1');
        } 
      }
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('IA',$O)===false)) {
    BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif (!(strpos('E',$O)===false)) {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de ws_url apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><font size="1">');
    ShowHTML('        <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_sq_esquema='.$w_sq_esquema.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('        <a accesskey="O" class="SS" href="'.$w_dir.$w_pagina.'Help&R='.$w_pagina.$par.'&O=O&w_sq_esquema='.$w_sq_esquema.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" target="help"><u>O</u>rientações</a>&nbsp;');
    ShowHTML('    <td align="right"><font size="1"><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan=2><font size="1"><b>'.LinkOrdena('Nome','nome').'</font></td>');
    ShowHTML('          <td colspan=2><font size="1"><b>Data</font></td>');
    ShowHTML('          <td colspan=3><font size="1"><b>Registros</font></td>');
    ShowHTML('          <td rowspan=2><font size="1"><b>Operações</font></td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Ocorrência','data_ocorrencia').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Referência','data_referencia').'</font></td>');
    ShowHTML('          <td><font size="1"><b>Total</font></td>');
    ShowHTML('          <td><font size="1"><b>Aceitos</font></td>');
    ShowHTML('          <td><font size="1"><b>Rejeitados</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados ws_url, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os ws_url selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;    ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        if ($P1==1) ShowHTML('        <td><font size="1">'.f($row,'nome').'</td>');
        else        ShowHTML('        <td><font size="1">'.LinkArquivo('HL',$w_cliente,f($row,'nome').'.xml','_blank','Exibe os dados do arquivo importado.',f($row,'nome'),null).'&nbsp;</td>');
        if (Nvl(f($row,'data_ocorrencia'),'')>'')    ShowHTML('        <td align="center"><font size="1">'.FormataDataEdicao(f($row,'data_ocorrencia')).'</td>');
        else                                        ShowHTML('        <td align="center"><font size="1">---</td>');
        if (Nvl(f($row,'data_referencia'),'')>'')    ShowHTML('        <td align="center"><font size="1">'.FormataDataEdicao(f($row,'data_referencia')).'</td>');
        else                                        ShowHTML('        <td align="center"><font size="1">---</td>');
        if (Nvl(f($row,'processados'),0)>0)   ShowHTML('        <td align="right"><font size="1">'.LinkArquivo('HL',$w_cliente,f($row,'chave_recebido'),'_blank','Exibe os dados do arquivo importado.',Nvl(f($row,'processados'),0),null).'&nbsp;</td>');
        else                                 ShowHTML('        <td align="right"><font size="1">'.Nvl(f($row,'processados'),0).'&nbsp;</td>');
        ShowHTML('        <td align="right"><font size="1">'.(Nvl(f($row,'processados'),0)-Nvl(f($row,'rejeitados'),0)).'&nbsp;</td>');
        if (Nvl(f($row,'rejeitados'),0)>0)    ShowHTML('        <td align="right"><font size="1">'.LinkArquivo('HL',$w_cliente,f($row,'chave_result'),'_blank','Exibe o registro da importação.',Nvl(f($row,'rejeitados'),0),null).'&nbsp;</td>');
        else                                 ShowHTML('        <td align="right"><font size="1">'.Nvl(f($row,'rejeitados'),0).'&nbsp;</td>');
        ShowHTML('        <td align="top" nowrap><font size="1">');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_esquema='.f($row,'sq_esquema').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informações do esquema">AL</A>&nbsp');
        if (Nvl(f($row,'sq_ocorrencia'),'')>'') ShowHTML('          <A class="hl" onClick="alert(\'Este esquema possui ocorrências, para desabilita-lo, inative-o!\');"title="Exclui o esquema">EX</A>&nbsp');
        else                                    ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_esquema='.f($row,'sq_esquema').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclui o esquema">EX</A>&nbsp');
        ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Tabela&R='.$w_dir.$w_pagina.'Tabela&O=L&w_sq_esquema='.f($row,'sq_esquema').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Tabelas&SG=ISSIGTAB&w_menu='.$w_menu.MontaFiltro('GET')).'\',\'Tabelas\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Relaciona as tabelas que compõem o esquema">Tabelas</A>&nbsp');
        if (Nvl(f($row,'qtd_tabela'),0)>0)    {
          if ($P1==1)   ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'IMPORTACAO&R='.$w_pagina.$par.'&O=I&w_sq_esquema='.f($row,'sq_esquema').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.MontaFiltro('GET').'" title="Importa a partir da definição do esquema">Importar</A>&nbsp');
          else          ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'EXPORTACAO&R='.$w_pagina.$par.'&O=I&w_sq_esquema='.f($row,'sq_esquema').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.MontaFiltro('GET').'" title="Exporta a partir da definição do esquema" onClick="return(confirm(\'Confirma geração do arquivo de exportação?\'))">Exportar</A>&nbsp');
        } 
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAE',$O)===false)) {
    if (!(strpos('E',$O)===false))  $w_disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,1,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_sq_esquema" value="'.$w_sq_esquema.'">');
    ShowHTML('<INPUT type="hidden" name="w_tipo" value="'.$p_tipo.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    if ($P1==2) ShowHTML('<INPUT type="hidden" name="w_formato" value="A">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><font size="1"><b><u>N</u>ome:<br><INPUT ACCESSKEY="N" TYPE="TEXT" CLASS="sti" NAME="w_nome" SIZE=60 MAXLENGTH=60 VALUE="'.$w_nome.'" '.$w_disabled.' title="Nome do esquema."></td>');
    ShowHTML('      <tr><td><font size="1"><b><U>D</U>escrição:<br><TEXTAREA ACCESSKEY="D" class="sti" name="w_descricao" rows=3 cols=80 '.$w_disabled.' title="Descreva sucintamente a finalidade deste esquema.">'.$w_descricao.'</textarea></td>');
    ShowHTML('      <tr><td><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('          <td><font size="1"><b>Nó <u>r</u>aiz:<br><INPUT ACCESSKEY="R" TYPE="TEXT" CLASS="sti" NAME="w_no_raiz" SIZE=50 MAXLENGTH=50 VALUE="'.$w_no_raiz.'" '.$w_disabled.' title="Informe o nome do nó raiz do documento XML."></td>');
    if ($P1==1) SelecaoFormato('Formato','F',null,$w_formato,null,'w_formato',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_formato\'; document.Form.submit();"');
    if ($w_formato!='W') {
      MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
      ShowHTML('              </table>');
    } else {
      ShowHTML('              </table>');
      ShowHTML('      <tr><td><font size="1"><b><u>S</u>ervidor:<br><INPUT ACCESSKEY="S" TYPE="TEXT" CLASS="sti" NAME="w_ws_servidor" SIZE=70 MAXLENGTH=100 VALUE="'.$w_ws_servidor.'" '.$w_disabled.' title="Informe o nome do servidor onde o Web Service está instalado."></td>');
      ShowHTML('      <tr><td><font size="1"><b><u>U</u>RL:<br><INPUT ACCESSKEY="U" TYPE="TEXT" CLASS="sti" NAME="w_ws_url" SIZE=70 MAXLENGTH=100 VALUE="'.$w_ws_url.'" '.$w_disabled.' title="Informe a URL para execução do Web Service."></td>');
      ShowHTML('      <tr><td><font size="1"><b>A<u>ç</u>ão:<br><INPUT ACCESSKEY="C" TYPE="TEXT" CLASS="sti" NAME="w_ws_acao" SIZE=70 MAXLENGTH=100 VALUE="'.$w_ws_acao.'" '.$w_disabled.' title="Informe a ação que deseja executar no Web Service."></td>');
      ShowHTML('      <tr><td><font size="1"><b><U>M</U>ensagem:<br><TEXTAREA ACCESSKEY="M" class="sti" name="w_ws_mensagem" rows=10 cols=80 '.$w_disabled.' title="Escreva o envelope da mensagem a ser enviada ao Web Service.">'.$w_ws_mensagem.'</textarea></td>');
      ShowHTML('      <tr>');
      MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
    } 
    ShowHTML('      <tr><td align="LEFT"><font size="1"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E')    ShowHTML('          <input class="STB" type="submit" name="Botao" value="Excluir">');
    else            ShowHTML('          <input class="STB" type="submit" name="Botao" value="Gravar">');
    ShowHTML('          <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de inclusão de tabelas no esquema
// -------------------------------------------------------------------------
function Tabela() {
  extract($GLOBALS);
  $w_sq_esquema_tabela  = $_REQUEST['w_sq_esquema_tabela'];
  $w_sq_esquema         = $_REQUEST['w_sq_esquema'];
  $p_nome               = $_REQUEST['p_nome'];
  $p_sq_sistema         = $_REQUEST['p_sq_sistema'];
  $p_sq_usuario         = $_REQUEST['p_sq_usuario'];
  $p_sq_tabela_tipo     = $_REQUEST['p_sq_tabela_tipo'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_ordem            = $_REQUEST['w_ordem'];
    $w_elemento         = $_REQUEST['w_elemento'];
    $p_nome             = $_REQUEST['p_nome'];
    $p_sq_sistema       = $_REQUEST['p_sq_sistema'];
    $p_sq_usuario       = $_REQUEST['p_sq_usuario'];
    $p_sq_tabela_tipo   = $_REQUEST['p_sq_tabela_tipo'];
  } elseif ($O=='L') {
    // Recupera todos os ws_url para a listagem
    $sql = new db_getEsquemaTabela; $RS = $sql->getInstanceOf($dbms,null,$w_sq_esquema,null);
    $RS = SortArray($RS,'ordem','asc','nm_tabela','asc','or_coluna','asc');
  } elseif (!(strpos('I',$O)===false)) {
    $sql = new db_getTabela; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,$w_sq_esquema,$p_sq_sistema,$p_sq_usuario,$p_sq_tabela_tipo,$p_nome,$SG);
    $RS = SortArray($RS,'sg_sistema','asc','nm_usuario','asc','nome','asc');
  } elseif (!(strpos('A',$O)===false)) {
    // Recupera todos os ws_url para a listagem
    $sql = new db_getEsquemaTabela; $RS = $sql->getInstanceOf($dbms,null,$w_sq_esquema,$w_sq_esquema_tabela);
    foreach($RS as $row){$RS=$row; break;}
    $w_ordem    = f($RS,'ordem');
    $w_elemento = f($RS,'elemento');
  }
  Cabecalho();
  head();
  if (!(strpos('IAPM',$O)===false)) {
    ScriptOpen('JavaScript');
    if (!(strpos('I',$O)===false)) {
      ShowHTML('  function valor(p_indice) {');
      ShowHTML('    if (document.Form["w_sq_tabela[]"][p_indice].checked) { ');
      ShowHTML('       document.Form["w_ordem[]"][p_indice].disabled=false; ');
      ShowHTML('       document.Form["w_elemento[]"][p_indice].disabled=false; ');
      ShowHTML('       document.Form["w_elemento[]"][p_indice].focus(); ');
      ShowHTML('    } else {');
      ShowHTML('       document.Form["w_ordem[]"][p_indice].disabled=true; ');
      ShowHTML('       document.Form["w_elemento[]"][p_indice].disabled=true; ');
      ShowHTML('       document.Form["w_ordem[]"][p_indice].value=\'\'; ');
      ShowHTML('       document.Form["w_elemento[]"][p_indice].value=\'\'; ');
      ShowHTML('    }');
      ShowHTML('  }');
      ShowHTML('  function MarcaTodos() {');
      ShowHTML('    if (document.Form["w_sq_tabela[]"].value==undefined) ');
      ShowHTML('       for (i=0; i < document.Form["w_sq_tabela[]"].length; i++) {');
      ShowHTML('         document.Form["w_sq_tabela[]"][i].checked=true;');
      ShowHTML('         document.Form["w_ordem[]"][i].disabled=false;');
      ShowHTML('         document.Form["w_elemento[]"][i].disabled=false;');
      ShowHTML('       } ');
      ShowHTML('    else document.Form["w_sq_tabela[]"].checked=true;');
      ShowHTML('  }');
      ShowHTML('  function DesmarcaTodos() {');
      ShowHTML('    if (document.Form["w_sq_tabela[]"].value==undefined) ');
      ShowHTML('       for (i=0; i < document.Form["w_sq_tabela[]"].length; i++) {');
      ShowHTML('         document.Form["w_sq_tabela[]"][i].checked=false;');
      ShowHTML('         document.Form["w_ordem[]"][i].disabled=true;');
      ShowHTML('         document.Form["w_elemento[]"][i].disabled=true;');
      ShowHTML('         document.Form["w_ordem[]"][i].value=\'\'; ');
      ShowHTML('         document.Form["w_elemento[]"][i].value=\'\'; ');
      ShowHTML('       } ');
      ShowHTML('    ');
      ShowHTML('    else document.Form["w_sq_tabela[]"]=false;');
      ShowHTML('  }');
    } 
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if (!(strpos('IAP',$O)===false)) {
      if (!(strpos('P',$O)===false)) {
        ShowHTML('  if (theForm.p_nome.value==\'\' && theForm.p_sq_sistema.selectedIndex==0 && theForm.p_sq_usuario.selectedIndex==0 && theForm.p_sq_tabela_tipo.selectedIndex==0) {');
        ShowHTML('     alert(\'Você deve escolher pelo menos um critério de filtragem!\');');
        ShowHTML('     return false;');
        ShowHTML('  }');
        Validate('P4','Linhas por página','1','1','1','4','','0123456789');
      } elseif (!(strpos('I',$O)===false)) {
        ShowHTML('  var i; ');
        ShowHTML('  var w_erro=true; ');
        ShowHTML('  if (theForm["w_sq_tabela[]"].value==undefined) {');
        ShowHTML('     for (i=0; i < theForm["w_sq_tabela[]"].length; i++) {');
        ShowHTML('       if (theForm["w_sq_tabela[]"][i].checked) w_erro=false;');
        ShowHTML('     }');
        ShowHTML('  }');
        ShowHTML('  else {');
        ShowHTML('     if (theForm["w_sq_tabela[]"].checked) w_erro=false;');
        ShowHTML('  }');
        ShowHTML('  if (w_erro) {');
        ShowHTML('    alert(\'Você deve informar pelo menos uma tabela!\'); ');
        ShowHTML('    return false;');
        ShowHTML('  }');
        ShowHTML('  for (i=0; i < theForm["w_sq_tabela[]"].length; i++) {');
        ShowHTML('    if((theForm["w_sq_tabela[]"][i].checked)&&(theForm["w_elemento[]"][i].value==\'\')){');
        ShowHTML('      alert(\'Para todas as tabelas selecionadas vc deve informar o elemento da tabela!\'); ');
        ShowHTML('      return false;');
        ShowHTML('    }');
        ShowHTML('  }');
        ShowHTML('  for (i=0; i < theForm["w_sq_tabela[]"].length; i++) {');
        ShowHTML('    if((theForm["w_sq_tabela[]"][i].checked)&&(theForm["w_ordem[]"][i].value==\'\')){');
        ShowHTML('      alert(\'Para todas as tabelas selecionadas vc deve informar a ordem da tabela para a importação do esquema!\'); ');
        ShowHTML('      return false;');
        ShowHTML('    }');
        ShowHTML('  }');
      } elseif (!(strpos('A',$O)===false)) {
        Validate('w_elemento','Elemento','1','1',2,50,'1','1');
        Validate('w_ordem','Ordem','1','1',1,18,'','0123456789');
      }
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    if ($O=='P') ShowHTML('  theForm.Botao[2].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('P',$O)===false)) {
    BodyOpen('onLoad=\'document.Form.p_sq_sistema.focus()\';');
  } else {
    BodyOpen(null);
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  $sql = new db_getEsquema; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,null,$w_sq_esquema,null,null,null,null,null,null,null,null);
  foreach($RS1 as $row1){$RS1=$row1; break;}
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr><td colspan=3 bgcolor="#FAEBD7"><table border=1 width="100%"><tr><td>');
  ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('      <tr><td colspan="3"><font size="1">Esquema: <b>'.f($RS1,'nome').'</font></b></td>');
  ShowHTML('      <tr><td colspan="3"><font size="1">Descrição: <b>'.Nvl(f($RS1,'Descricao'),'---').'</font></b></td>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('          <td><font size="1">Tipo: <b>'.f($RS1,'nm_tipo').'</b></td>');
  ShowHTML('          <td><font size="1">Formato: <b>'.f($RS1,'nm_formato').'</b></td>');
  ShowHTML('          <td><font size="1">Ativo: <b>'.f($RS1,'nm_ativo').'</b></td>');
  if (!(strpos('AM',$O)===false)) {
    $sql = new db_getEsquemaTabela; $RS1 = $sql->getInstanceOf($dbms,null,$w_sq_esquema,$w_sq_esquema_tabela);
    foreach($RS1 as $row1){$RS1=$row1; break;}
    ShowHTML('      <tr><td colspan="3"><font size="1">Tabela: <b>'.Nvl(f($RS1,'nm_tabela'),'---').'</font></b></td>');
  } 
  ShowHTML('    </TABLE>');
  ShowHTML('</TABLE>');
  ShowHTML('</TABLE>');
  ShowHTML('<tr><td>&nbsp');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    //Listagem das tabelas do esquema
    // Exibe a quantidade de ws_url apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><font size="1">');
    ShowHTML('        <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&w_sq_esquema='.$w_sq_esquema.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('        <a accesskey="F" class="ss" href="javascript:window.close(); opener.location.reload(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right"><font size="1"><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan="1" colspan="2"><font size="1"><b>Tabelas</font></td>');
    ShowHTML('          <td rowspan="1" colspan="2"><font size="1"><b>Campos</font></td>');
    ShowHTML('          <td rowspan="2"><font size="1"><b>Operações</font></td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Ordem','ordem').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Nome','nm_tabela').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Ordem','or_coluna').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Nome','Campo_externo').'</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados ws_url, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os ws_url selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        if ($w_atual!=f($row,'nm_tabela')) {
          ShowHTML('        <td rowspan="'.f($row,'qtd_coluna').'" align="center"><font size="1">'.f($row,'ordem').'</td>');
          ShowHTML('        <td rowspan="'.f($row,'qtd_coluna').'"><font size="1">'.f($row,'nm_tabela').'</td>');
        } 
        ShowHTML('        <td align="center"><font size="1">'.Nvl(f($row,'or_coluna'),'---').'</td>');
        ShowHTML('        <td><font size="1">'.Nvl(f($row,'campo_externo'),'---').'</td>');
        if ($w_atual!=f($row,'nm_tabela')) {
          ShowHTML('        <td rowspan="'.f($row,'qtd_coluna').'"><font size="1">');
          ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_esquema='.f($row,'sq_esquema').'&w_sq_esquema_tabela='.f($row,'sq_esquema_tabela').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.MontaFiltro('GET').'" title="Altera a os dados da tabela deste esquema">AL</A>&nbsp');
          ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Grava'.'&R='.$w_pagina.$par.'&O=E&w_sq_esquema='.f($row,'sq_esquema').'&w_sq_esquema_tabela='.f($row,'sq_esquema_tabela').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.MontaFiltro('GET').'" title="Exclui a tabela deste esquema" onClick="return confirm(\'Confirma a exclusão do registro?\');">EX</A>&nbsp');
          ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'MAPEAMENTO&R='.$w_pagina.$par.'&O=I&w_sq_esquema='.f($row,'sq_esquema').'&w_sq_esquema_tabela='.f($row,'sq_esquema_tabela').'&w_sq_tabela='.f($row,'sq_tabela').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.MontaFiltro('GET').'" title="Relaciona os campos da tabela">Mapear</A>&nbsp');
        } 
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
        $w_atual = f($row,'nm_tabela');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('P',$O)===false)) {
    //Filtro para inclusão de um tabela no esquema
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    AbreForm('Form',$w_dir.$R,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'I');
    ShowHTML('<INPUT type="hidden" name="w_sq_esquema" value="'.$w_sq_esquema.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('  <tr bgcolor="'.$conTrBgColor.'"><td colspan=2><div align="justify"><font size=2><b><ul>Instruções</b>:<li>Informe os parâmetros desejados para recuperar a lista de tabelas.<li>Quando a relação de tabelas for exibida, selecione as tabelas desejadas clicando sobre a caixa ao lado do nome.<li>Você pode informar o nome de uma tabela , selecionar as tabelas de um sistema, ou ainda as tabelas de um usuário.<li>Após informar os parâmetros desejados, clique sobre o botão <i>Aplicar filtro</i>.</ul><hr><b>Filtro</b></div>');
    ShowHTML('  <tr bgcolor="'.$conTrBgColor.'"><td colspan=2>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr>');
    SelecaoSistema('<u>S</u>istema:','S',null,$p_sq_sistema,$w_cliente,'p_sq_sistema',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_sq_usuario\'; document.Form.submit();"');
    SelecaoUsuario('<u>U</u>suário:','S',null,$w_cliente,$p_sq_usuario,Nvl($p_sq_sistema,0),'p_sq_usuario',null,null);
    ShowHTML('      <tr><td valign="top"><font size="1"><b><u>N</u>ome:</b><br><input '.$w_disabled.' accesskey="N" type="text" name="p_nome" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$p_nome.'"></td>');
    SelecaoTipoTabela('<u>T</u>ipo:','T',null,$p_sq_tabela_tipo,null,'p_sq_tabela_tipo',null,null);
    ShowHTML('      <tr><td valign="top"><font size="1"><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY="L" '.$w_disabled.' class="sti" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_sq_esquema='.$w_sq_esquema.'&R='.$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&O=P&SG='.$SG.'&w_menu='.$w_menu).'\';" name="Botao" value="Limpar campos">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_sq_esquema='.$w_sq_esquema.'&R='.$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</form>');
  } elseif (!(strpos('I',$O)===false)) {
    //Rotina de escolha e gravação de tabelas para o esquema
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_sq_esquema" value="'.$w_sq_esquema.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_tabela[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_ordem[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_elemento[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<tr><td><font size="1">');
    ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_sq_esquema='.$w_sq_esquema.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a></font>');
    ShowHTML('    <td align="right"><font size="1"><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center" valign="top">');
    ShowHTML('            <td width="70"NOWRAP><font size="2"><U ID="INICIO" CLASS="hl" onClick="javascript:MarcaTodos();" TITLE="Marca todos os itens da relação"><IMG SRC="images/NavButton/BookmarkAndPageActivecolor.gif" BORDER="1" width="15" height="15"></U>&nbsp;');
    ShowHTML('                                      <U CLASS="hl" onClick="javascript:DesmarcaTodos();" TITLE="Desmarca todos os itens da relação"><IMG SRC="images/NavButton/BookmarkAndPageInactive.gif" BORDER="1" width="15" height="15"></U>');
    ShowHTML('          <td><font size="1"><b>Sistema</b></font></td>');
    ShowHTML('          <td><font size="1"><b>Tabela</b></font></td>');
    ShowHTML('          <td><font size="1"><b>Descrição</b></font></td>');
    ShowHTML('          <td><font size="1"><b>Elemento</b></font></td>');
    ShowHTML('          <td><font size="1"><b>Ordem</b></font></td>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_cont = 0;
      $w_parcial=0;
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row) {
        $w_cont += 1;
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center"><input type="checkbox" name="w_sq_tabela[]" value="'.f($row,'chave').'" onClick="valor('.$w_cont.');">');
        ShowHTML('        <td><font size="1">'.f($row,'sg_sistema').'</td>');
        ShowHTML('        <td><font size="1">'.lower(f($row,'nm_usuario').'.'.f($row,'nome')).'</td>');
        ShowHTML('        <td><font size="1">'.f($row,'descricao').'</td>');
        ShowHTML('        <td><font size="1"><input disabled type="text" name="w_elemento[]" class="sti" SIZE="20" MAXLENGTH="50" VALUE="'.$w_elemento.'"></td>');
        ShowHTML('        <td><font size="1"><input disabled type="text" name="w_ordem[]" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_ordem.'"></td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('<tr><td align="center" colspan="3"><font size="1">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_sq_esquema='.$w_sq_esquema.'&R='.$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('  </td>');
    ShowHTML('</FORM>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&w_sq_esquema='.$w_sq_esquema.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&w_sq_esquema='.$w_sq_esquema.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');
  } elseif (!(strpos('A',$O)===false)) {
    //Rotina para alteração do dados da tabela de um esquema
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_sq_esquema" value="'.$w_sq_esquema.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_esquema_tabela" value="'.$w_sq_esquema_tabela.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('  <tr bgcolor="'.$conTrBgColor.'"><td colspan=2>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td valign="top"><font size="1"><b><u>E</u>lemento:</b><br><input '.$w_disabled.' accesskey="E" type="text" name="w_elemento" class="sti" SIZE="30" MAXLENGTH="50" VALUE="'.$w_elemento.'"></td>');
    ShowHTML('      <tr><td valign="top"><font size="1"><b><u>O</u>rdem:</b><br><input '.$w_disabled.' accesskey="O" type="text" name="w_ordem" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_ordem.'"></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_sq_esquema='.$w_sq_esquema.'&R='.$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</form>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de inclusão de tabelas no esquema
// -------------------------------------------------------------------------
function Mapeamento() {
  extract($GLOBALS);
  $w_sq_esquema_atributo    = $_REQUEST['w_sq_esquema_atributo'];
  $w_sq_esquema_tabela      = $_REQUEST['w_sq_esquema_tabela'];
  $w_sq_esquema             = $_REQUEST['w_sq_esquema'];
  $w_sq_tabela              = $_REQUEST['w_sq_tabela'];
  $w_sq_coluna              = $_REQUEST['w_sq_coluna'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_ordem            = $_REQUEST['w_ordem'];
    $w_campo_externo    = $_REQUEST['w_campo_externo'];
  } elseif ($O=='I') {
    // Recupera todos os ws_url para a listagem
    $sql = new db_getColuna; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,$w_sq_tabela,null,null,null,null,$w_sq_esquema_tabela);
    $RS = SortArray($RS,'ordem','asc','nm_coluna','asc');
  } 
  Cabecalho();
  head();
  if (!(strpos('I',$O)===false)) {
    ScriptOpen('JavaScript');
    ShowHTML('  function valor(p_indice) {');
    ShowHTML('    if (document.Form["w_sq_coluna[]"][p_indice].checked) { ');
    ShowHTML('       document.Form["w_ordem[]"][p_indice].disabled=false; ');
    ShowHTML('       document.Form["w_campo_externo[]"][p_indice].disabled=false; ');
    ShowHTML('       document.Form["w_campo_externo[]"][p_indice].focus(); ');
    ShowHTML('    } else {');
    ShowHTML('       document.Form["w_ordem[]"][p_indice].disabled=true; ');
    ShowHTML('       document.Form["w_campo_externo[]"][p_indice].disabled=true; ');
    ShowHTML('       document.Form["w_ordem[]"][p_indice].value=\'\'; ');
    ShowHTML('       document.Form["w_campo_externo[]"][p_indice].value=\'\'; ');
    ShowHTML('    }');
    ShowHTML('  }');
    ShowHTML('  function MarcaTodos() {');
    ShowHTML('    if (document.Form["w_sq_coluna[]"].value==undefined) ');
    ShowHTML('       for (i=0; i < document.Form["w_sq_coluna[]"].length; i++) {');
    ShowHTML('         document.Form["w_sq_coluna[]"][i].checked=true;');
    ShowHTML('         document.Form["w_ordem[]"][i].disabled=false;');
    ShowHTML('         document.Form["w_campo_externo[]"][i].disabled=false;');
    ShowHTML('       } ');
    ShowHTML('    else document.Form["w_sq_coluna[]"].checked=true;');
    ShowHTML('  }');
    ShowHTML('  function DesmarcaTodos() {');
    ShowHTML('    if (document.Form["w_sq_coluna[]"].value==undefined) ');
    ShowHTML('       for (i=0; i < document.Form["w_sq_coluna[]"].length; i++) {');
    ShowHTML('         document.Form["w_sq_coluna[]"][i].checked=false;');
    ShowHTML('         document.Form["w_ordem[]"][i].disabled=true;');
    ShowHTML('         document.Form["w_campo_externo[]"][i].disabled=true;');
    ShowHTML('         document.Form["w_ordem[]"][i].value=\'\'; ');
    ShowHTML('         document.Form["w_campo_externo[]"][i].value=\'\'; ');
    ShowHTML('       } ');
    ShowHTML('    ');
    ShowHTML('    else document.Form["w_sq_coluna[]"].checked=false;');
    ShowHTML('  }');
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    ShowHTML('  var i; ');
    ShowHTML('  var w_erro=true; ');
    ShowHTML('  if (theForm["w_sq_coluna[]"].value==undefined) {');
    ShowHTML('     for (i=0; i < theForm["w_sq_coluna[]"].length; i++) {');
    ShowHTML('       if (theForm["w_sq_coluna[]"][i].checked) w_erro=false;');
    ShowHTML('     }');
    ShowHTML('  }');
    ShowHTML('  else {');
    ShowHTML('     if (theForm["w_sq_coluna[]"].checked) w_erro=false;');
    ShowHTML('  }');
    ShowHTML('  if (w_erro) {');
    ShowHTML('    alert(\'Você deve informar pelo menos uma coluna!\'); ');
    ShowHTML('    return false;');
    ShowHTML('  }');
    ShowHTML('  for (i=0; i < theForm["w_sq_coluna[]"].length; i++) {');
    ShowHTML('    if((theForm["w_sq_coluna[]"].checked)&&(theForm["w_campo_externo[]"][i].value==\'\')){');
    ShowHTML('      alert(\'Para todas as colunas selecionadas vc deve informar o campo externo(XML) da coluna!\'); ');
    ShowHTML('      return false;');
    ShowHTML('    }');
    ShowHTML('  }');
    ShowHTML('  for (i=0; i < theForm["w_sq_coluna[]"].length; i++) {');
    ShowHTML('    if((theForm["w_sq_coluna[]"][i].checked)&&(theForm["w_ordem[]"][i].value==\'\')){');
    ShowHTML('      alert(\'Para todas as colunas selecionadas vc deve informar a ordem da coluna para a importação do esquema!\'); ');
    ShowHTML('      return false;');
    ShowHTML('    }');
    ShowHTML('  }');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen(null);
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  $sql = new db_getEsquema; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,null,$w_sq_esquema,null,null,null,null,null,null,null,null);
  foreach($RS1 as $row1){$RS1=$row1; break;}
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr><td colspan=3 bgcolor="#FAEBD7"><table border=1 width="100%"><tr><td>');
  ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('      <tr><td colspan="3"><font size="1">Esquema: <b>'.f($RS1,'nome').'</font></b></td>');
  ShowHTML('      <tr><td colspan="3"><font size="1">Descrição: <b>'.Nvl(f($RS1,'Descricao'),'---').'</font></b></td>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('          <td><font size="1">Tipo: <b>'.f($RS1,'nm_tipo').'</b></td>');
  ShowHTML('          <td><font size="1">Formato: <b>'.f($RS1,'nm_formato').'</b></td>');
  ShowHTML('          <td><font size="1">Ativo: <b>'.f($RS1,'nm_ativo').'</b></td>');
  $sql = new db_getEsquemaTabela; $RS1 = $sql->getInstanceOf($dbms,null,$w_sq_esquema,$w_sq_esquema_tabela);
  foreach($RS1 as $row1){$RS1=$row1; break;}
  ShowHTML('      <tr><td colspan="3"><font size="1">Tabela: <b>'.Nvl(f($RS1,'nm_tabela'),'---').'</font></b></td>');
  ShowHTML('    </TABLE>');
  ShowHTML('</TABLE>');
  ShowHTML('</TABLE>');
  ShowHTML('<tr><td>&nbsp');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (!(strpos('I',$O)===false)) {
    //Rotina de escolha e gravação das colunas para a tabela
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'ISSIGMAP',$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_esquema" value="'.$w_sq_esquema.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_esquema_tabela" value="'.$w_sq_esquema_tabela.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_coluna[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_ordem[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_campo_externo[]" value="">');
    ShowHTML('<tr><td><font size="1">');
    ShowHTML('    <td align="right"><font size="1"><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center" valign="top">');
    ShowHTML('            <td width="70"NOWRAP><font size="2"><U ID="INICIO" CLASS="hl" onClick="javascript:MarcaTodos();" TITLE="Marca todos os itens da relação"><IMG SRC="images/NavButton/BookmarkAndPageActivecolor.gif" BORDER="1" width="15" height="15"></U>&nbsp;');
    ShowHTML('                                      <U CLASS="hl" onClick="javascript:DesmarcaTodos();" TITLE="Desmarca todos os itens da relação"><IMG SRC="images/NavButton/BookmarkAndPageInactive.gif" BORDER="1" width="15" height="15"></U>');
    ShowHTML('          <td><font size="1"><b>Coluna</b></font></td>');
    ShowHTML('          <td><font size="1"><b>Descricao</b></font></td>');
    ShowHTML('          <td><font size="1"><b>Tipo</b></font></td>');
    ShowHTML('          <td><font size="1"><b>Obrig.</b></font></td>');
    ShowHTML('          <td><font size="1"><b>Campo externo</b></font></td>');
    ShowHTML('          <td><font size="1"><b>Ordem</b></font></td>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_disabled='disabled';
      $w_cont = 0;
      foreach($RS as $row) {
        $w_cont += 1;
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        $sql = new db_getEsquemaAtributo; $RS1 = $sql->getInstanceOf($dbms,null,$w_sq_esquema_tabela,null,f($row,'chave'));
        if (count($RS1)>0) {
          foreach($RS1 as $row1){$RS1=$row1; break;}
          ShowHTML('        <td align="center"><input type="checkbox" name="w_sq_coluna[]" value="'.f($row,'chave').'" onClick="valor('.$w_cont.');" CHECKED>');
          $w_ordem          = f($RS1,'ordem');
          $w_campo_externo  = f($RS1,'campo_externo');
          $w_disabled       = '';
        } else {
          ShowHTML('        <td align="center"><input type="checkbox" name="w_sq_coluna[]" value="'.f($row,'chave').'" onClick="valor('.$w_cont.');">');
        } 
        ShowHTML('        <td><font size="1">'.f($row,'nm_coluna').'</td>');
        ShowHTML('        <td><font size="1">'.Nvl(f($row,'descricao'),'---').'</td>');
        ShowHTML('        <td nowrap><font size="1">'.f($row,'nm_coluna_tipo').' (');
        if (upper(f($row,'nm_coluna_tipo'))=='NUMERIC') ShowHTML(Nvl(f($row,'precisao'),f($row,'tamanho')).','.Nvl(f($row,'escala'),0));
        else                                                ShowHTML(f($row,'tamanho'));
        ShowHTML(')</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'obrigatorio').'</td>');
        ShowHTML('        <td><font size="1"><input '.$w_disabled.' type="text" name="w_campo_externo[]" class="sti" SIZE="20" MAXLENGTH="30" VALUE="'.$w_campo_externo.'"></td>');
        ShowHTML('        <td><font size="1"><input '.$w_disabled.' type="text" name="w_ordem[]" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_ordem.'"></td>');
        ShowHTML('      </tr>');
        $w_ordem            = '';
        $w_campo_externo    = '';
        $w_disabled         = 'disabled';
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('<tr><td align="center" colspan="3"><font size="1">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.'Tabela'.'&w_sq_esquema='.$w_sq_esquema.'&R='.$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('  </td>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de importação de arquivos físicos para atualização a partir do SIGPLAN
// -------------------------------------------------------------------------
function Importacao() {
  extract($GLOBALS);
  $w_sq_esquema = $_REQUEST['w_sq_esquema'];
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
  $w_upload_maximo = f($RS,'upload_maximo');
  if ($O=='I') {
    // Recupera todos os ws_url para a listagem
    $sql = new db_getEsquema; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,$w_sq_esquema,null,null,null,null,null,null,null,null);
    foreach($RS as $row){$RS=$row; break;}
  } elseif (!(strpos('AE',$O)===false)) {
  } 
  Cabecalho();
  head();
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataDataHora();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if (!(strpos('I',$O)===false)) {
      Validate('w_data_arquivo','Data e hora','DATAHORA','1','17','17','','0123456789 /:,');
      Validate('w_caminho','Arquivo de dados','1','1','1','255','1','1');
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('I',$O)===false)) {
    if (f($RS,'formato')=='A') {
      BodyOpen('onLoad=\'document.Form.w_data_arquivo.focus()\';');
    } elseif (f($RS,'formato')=='W') {
      BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
    } 
  } elseif (!(strpos('E',$O)===false)) {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen(null);
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
  } elseif (!(strpos('I',$O)===false)) {
    if (f($RS,'formato')!='W') {
      ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG=IMparQ&O='.$O.'&w_menu='.$w_menu.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
      ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
      ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
      ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
      ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
      ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
      ShowHTML('<INPUT type="hidden" name="R" value="'.$R.'">');
    } else {
      AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,1,$P4,$TP,'IMPWEB',$R,$O);
      ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    } 
    ShowHTML('<INPUT type="hidden" name="w_sq_esquema" value="'.$w_sq_esquema.'">');
    ShowHTML('<INPUT type="hidden" name="w_tipo" value="'.$p_tipo.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td bgcolor="#FAEBD7"><table border=1 width="100%"><tr><td>');
    ShowHTML('      <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('      <tr><td><font size="1">Nome:<b> '.f($RS,'Nome').'</b></td>');
    ShowHTML('      <tr><td><font size="1">Descrição:<b> '.f($RS,'Descricao').'</b></td>');
    ShowHTML('      <tr><td><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('          <td><font size="1">Nó raiz:<b> '.f($RS,'no_raiz').'</b></td>');
    ShowHTML('          <td><font size="1">Formato:<b> '.f($RS,'nm_formato').'</b></td>');
    if (f($RS,'formato')!='W') {
      ShowHTML('       <td><font size="1">Ativo:<b> '.f($RS,'nm_ativo').'</b></td>');
      ShowHTML('              </table>');
    } else {
      ShowHTML('              </table>');
      ShowHTML('      <tr><td><font size="1">Servidor:<b> '.f($RS,'ws_servidor').'</b></td>');
      ShowHTML('      <tr><td><font size="1">URL:<b> '.f($RS,'ws_url').'</b></td>');
      ShowHTML('      <tr><td><font size="1">Ação:<b> '.f($RS,'ws_acao').'</b></td>');
      ShowHTML('      <tr><td><font size="1">Mensagem:<b> '.f($RS,'ws_mensagem').'</b></td>');
      ShowHTML('      <tr>');
      ShowHTML('       <td><font size="1">Ativo:<b>'.f($RS,'nm_ativo').'</b></td>');
    } 
    ShowHTML('    </TABLE>');
    ShowHTML('</TABLE>');
    if (f($RS,'formato')=='A') {
      ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b><font color="#BC3131">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de '.($w_upload_maximo/1024).' KBytes</b>.</font></td>');
      ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.$w_upload_maximo.'">');
      ShowHTML('      <tr><td><font size="1"><b><u>D</u>ata/hora extração:</b><br><input '.$w_disabled.' accesskey="D" type="text" name="w_data_arquivo" class="sti" SIZE="17" MAXLENGTH="17" VALUE="'.$w_data_arquivo.'"  onKeyDown="FormataDataHora(this, event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);" title="OBRIGATÓRIO. Informe a data e hora da extração do aquivo. Digite apenas números. O sistema colocará os separadores automaticamente."></td>');
      ShowHTML('      <tr><td><font size="1"><b>A<u>r</u>quivo:</b><br><input accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OBRIGATÓRIO. Clique no botão ao lado para localizar o arquivo. Ele será transferido automaticamente para o servidor.">');
    } 
    ShowHTML('      <tr><td align="LEFT"><font size="1"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('          <input class="STB" type="submit" name="Botao" value="Importar">');
    ShowHTML('          <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.'ISSIGIMP'.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de exportação de arquivos físicos para atualização do SIGPLAN
// -------------------------------------------------------------------------
function Exportacao() {
  extract($GLOBALS);
  $w_sq_esquema = $_REQUEST['w_sq_esquema'];

  // Recupera os dados do esquema selecionado
  $sql = new db_getEsquema; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,$w_sq_esquema,null,null,null,null,null,null,null,null);
  foreach ($RS as $row) { $RS = $row; break; }
  // Recupera cada uma das tabelas referenciadas pelo esquema
  $sql = new db_getEsquemaTabela; $RS1 = $sql->getInstanceOf($dbms,null,$w_sq_esquema,null);
  $RS1 = SortArray($RS1,'ordem','asc','nm_tabela','asc','or_coluna','asc');
  if (count($RS1)<=0) {
    Cabecalho();
    ShowHTML('<Body>');
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'Não foram informadas tabelas para o esquema informado\');');
    ShowHTML('  window.close();');
    ScriptClose();
    ShowHTML('</Body>');
    ShowHTML('</html>');
  } else {
    $w_where = ' where cliente = '.$w_cliente.$crlf.
               '   and ano     = '.RetornaAno();
    $w_atual    = '';
    $w_cont     = 0;
    $j          = 0;
    foreach($RS1 as $row1) {
      if (f($row1,'elemento')!=$w_atual) {
        if ($w_cont>0) {
          $i[$w_cont]     = $j;
          $w_campos       = substr($w_campos,2,strlen($w_campos));
          $w_sql[$w_cont] ='select '.$w_campos.$crlf.
                           $w_from.$crlf.
                           $w_where.$crlf.
                           ' order by '.$w_campos;
        } 
        $w_cont += 1;
        $w_elemento[$w_cont] = f($row1,'elemento');
        $w_atual             = f($row1,'elemento');
        $j = 0;
        $w_campos = '';
        $w_from='  from '.$strschema_is.f($row1,'nm_tabela');
      } 
      $j += 1;
      $w_atributo[$w_cont][$j]  =   f($row1,'campo_externo');
      $w_coluna[f($row1,'cl_nome')]['tipo'] = f($row1,'nm_tipo');
      $w_coluna[f($row1,'cl_nome')]['precisao'] = f($row1,'precisao');
      $w_coluna[f($row1,'cl_nome')]['escala'] = f($row1,'escala');
      $w_campo[$w_cont][$j]     =   f($row1,'cl_nome');
      $w_campos                 =   $w_campos.', '.f($row1,'cl_nome');
    }

    $i[$w_cont] = $j;
    $w_campos       = substr($w_campos,2,strlen($w_campos));
    $w_sql[$w_cont] = 'select '.$w_campos.$crlf.
                        $w_from.$crlf.
                       $w_where.$crlf.
                      ' order by '.$w_campos;
    // Gera o arquivo de exportação
    // Configura o nome dos arquivo recebido e do arquivo registro
    $w_arquivo_processamento = f($RS,'nome').'.xml';
    $F1 = fopen($w_caminho.$w_arquivo_processamento, 'w');
    fwrite($F1,utf8_encode('<?phpxml version="1.0" encoding="utf-8"?>').$crlf);
    //fwrite($F1,utf8_encode('<'.f($RS,'no_raiz').' xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sigplan.gov.br/xml/">').$crlf);
    fwrite($F1,utf8_encode('<'.f($RS,'no_raiz').'>').$crlf);
    // Processa cada um dos esquemas recuperados
    for ($j=1; $j<=$w_cont; $j=$j+1) {
      $sql = new DatabaseQueriesFactory; $RS2 = $sql->getInstanceOf($w_sql[$j], $dbms, null, DB_TYPE);
      if(!$RS2->executeQuery()) die("Cannot query"); else $RS2 = $sql->getResultData();
      foreach($RS2 as $row2) {
        fwrite($F1,utf8_encode('  <'.$w_elemento[$j].'>').$crlf);
        // Processa cada um dos atributos recuperados
        for ($k=1; $k<=$i[$j]; $k=$k+1) {
          // Se o valor do banco for nulo, exporta tag fechada; 
          // caso contrário, exporta tag abre/fecha contendo o valor
          if (trim(Nvl(f($row2,$w_campo[$j][$k]),''))>'') {
            // O bloco de IFs abaixo executa transformações nos dados para o formato esperado pelo SIGPLAN
            if (f($row2,$w_campo[$j][$k])=='S') {
              $w_valor = 'true';
            } elseif (f($row2,$w_campo[$j][$k])=='N') {
              $w_valor = 'false';
            } elseif (($w_coluna[$w_campo[$j][$k]]['tipo']=='B_INTEGER') || ($w_coluna[$w_campo[$j][$k]]['tipo']=='B_NUMERIC')) {
              // Se o valor for igual a zero, exporta 0;
              // caso contrário, verifica o número de decimais e exporta o valor 
              // com nenhuma ou com 4 decimais, usando o ponto como separador de decimais
              if (nvl(f($row2,$w_campo[$j][$k]),0)!=0) {
                $w_valor = str_replace(',','.',str_replace('.','',FormatNumber(f($row2,$w_campo[$j][$k]),$w_coluna[$w_campo[$j][$k]]['escala'])));
              } else {
                $w_valor = '0';
              } 
            } elseif ($w_coluna[$w_campo[$j][$k]]['tipo']=='B_DATE') {
              $w_valor = FormataDataXML(f($row2,$w_campo[$j][$k]));
            } else {
              $w_valor = f($row2,$w_campo[$j][$k]);
            } 
            fwrite($F1,'    <'.$w_atributo[$j][$k].'>'.utf8_encode(htmlspecialchars($w_valor)).'</'.$w_atributo[$j][$k].'>'.$crlf);
          } 
        } 
        fwrite($F1,utf8_encode('  </'.$w_elemento[$j].'>').$crlf);
      }
    } 
    fwrite($F1,utf8_encode('</'.f($RS,'no_raiz').'>').$crlf);
    fclose($F1);
    // Grava o resultado da importação no banco de dados
    //dml_putDcOcorrencia O, _
    //    w_sq_esquema, w_cliente,   w_usuario,     ul.Texts.Item('w_data_arquivo'), _
    //    w_nome_recebido, _
    //    w_arquivo_processamento, w_tamanho_recebido,  w_tipo_recebido, _
    //    w_arquivo_registro,      w_arquivo_rejeicao, w_tamanho_registro, w_tipo_registro, _
    //    w_reg,       w_erro
    Cabecalho();
    ShowHTML('<Body>');
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'Exportação concluída com sucesso!\');');
    ShowHTML('  history.back(1);');
    ScriptClose();
    ShowHTML('</Body>');
    ShowHTML('</html>');
  } 
} 
// =========================================================================
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpenClean(null);
  switch ($SG) {
    case 'IMPARQ':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if (UPLOAD_ERR_OK==0) {
          $w_maximo = $_REQUEST['w_upload_maximo'];
          foreach ($_FILES as $Chv => $Field) {
            if (!($Field['error']==UPLOAD_ERR_OK || $Field['error']==UPLOAD_ERR_NO_FILE)) {
              // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!\');');
              ShowHTML('  history.go(-1);');
              ScriptClose();
              exit();
            }
            if ($Field['size'] > 0) {
              // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
              if ($Field['size'] > $w_maximo) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!\');');
                ShowHTML('  history.back(1);');
                ScriptClose();
                exit();
              } 
              // Se já há um nome para o arquivo, mantém 
              $w_arquivo_processamento  = str_replace('.tmp','',basename($Field['tmp_name']));
              $w_tamanho_recebido       = $Field['size'];
              $w_tipo_recebido          = $Field['type'];
              $w_nome_recebido          = $Field['name'];
              if ($w_arquivo_processamento>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_arquivo_processamento.'.xml');
              $w_arquivo_rejeicao       = basename($w_arquivo_processamento).'r';
            } 
          } 
          // Gera o arquivo registro da importação
          $F1 = fopen($w_caminho.$w_arquivo_rejeicao, 'w');      
          //Abre o arquivo recebido para gerar o arquivo registro
          if (file_exists($w_caminho.$w_arquivo_processamento.'.xml')) {
            // Carrega o conteúdo do arquivo origem em uma variável local
            $handle = fopen ($w_caminho.$w_arquivo_processamento, 'r');
            $conteudo = fread ($handle, filesize ($w_caminho.$w_arquivo_processamento));
            fclose ($handle);
            // Reescreve o arquivo, removendo caracteres 26 (hexa 1A)
            $handle = fopen ($w_caminho.$w_arquivo_processamento, 'w');
            fwrite($handle, str_replace('&#x1A;','-',$conteudo));
            fclose ($handle);
            
            $xml = simplexml_load_file($w_caminho.$w_arquivo_processamento.'.xml');
            echo $xml;
            exit;
            // Recupera os dados do esquema a ser importado
            $sql = new db_getEsquema; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,$_REQUEST['w_sq_esquema'],$w_sq_modulo,null,null,null,null,null,null,null);
            foreach($RS as $row){$RS=$row; break;}
            // Verifica se o nó raiz consta do arquivo
            // Recupera cada uma das tabelas referenciadas pelo esquema
            $sql = new db_getEsquemaTabela; $RS1 = $sql->getInstanceOf($dbms,null,$_REQUEST['w_sq_esquema'],null);
            $RS1 = SortArray($RS1,'ordem','asc','nm_tabela','asc','or_coluna','asc');
            $w_reg = 0;
            $w_erro = 0;
            foreach($RS1 as $row1) {              
              if (count($xml->xpath(f($row1,'elemento')))==0) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'Atenção: Elemento não localizado no arquivo XML!\');');
                ShowHTML('  history.back(1);');
                ScriptClose();
                exit();
              } else {
                // Recupera cada um dos campos referenciados pelo elemento
                $sql = new db_getEsquemaAtributo; $RS2 = $sql->getInstanceOf($dbms,null,f($row1,'sq_esquema_tabela'),null,null);
                $RS2 = SortArray($RS2,'ordem','asc');
                $w_cont = 0;
                foreach($RS2 as $row2) {
                  $w_cont += 1;
                  $w_atributo[$w_cont] = f($row2,'campo_externo');
                }
                $w_limite = $w_cont;
                if ($w_atual!=f($row1,'nm_tabela')) {
                  $w_campos = $xml->xpath(f($row1,'elemento'));
                  foreach ($xml->xpath(f($row1,'elemento')) as $w_dados) {
                    $w_val = array_map(trim,array_map(utf8_decode,get_object_vars($w_dados)));
                    $w_reg += 1;
                    for ($w_cont=1; $w_cont<=$w_limite; $w_cont+=1) {
                      if (!is_object($w_val[$w_atributo[$w_cont]])) {
                        if (is_object($w_val[$w_atributo[$w_cont]])) { $w_param[$w_cont] = get_object_vars($w_val[$w_atributo[$w_cont]]); }  
                        // Recupera cada um dos campos referenciados pelo elemento
                        if (upper($w_val[$w_atributo[$w_cont]])    =='TRUE')  $w_param[$w_cont]='S';
                        elseif (upper($w_val[$w_atributo[$w_cont]])=='FALSE') $w_param[$w_cont]='N';
                        else                                                       $w_param[$w_cont]=$w_val[$w_atributo[$w_cont]];
                      }
                    }
                    $w_resultado='';
                    switch (f($row1,'nm_tabela')) {
                      case 'IS_PPA_ESFERA':         $SQL = new dml_putXMLEsfera; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_PPA_PERIODICIDADE':  $SQL = new dml_putXMLPeriodicidade_PPA; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_PPA_UNIDADE_MEDIDA': $SQL = new dml_putXMLUnidade_Medida_PPA; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_PPA_ORGAO':          $SQL = new dml_putXMLOrgao_PPA; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],$w_param[3],$w_param[4],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_PPA_ORGAO_SIORG':    $SQL = new dml_putXMLOrgao_Siorg_PPA; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],$w_param[3],$w_param[4],$w_param[5],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_PPA_UNIDADE':        $SQL = new dml_putXMLUnidade_PPA; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],$w_param[3],$w_param[4],$w_param[5]); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_PPA_TIPO_ACAO':      $SQL = new dml_putXMLTipo_Acao_PPA; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_PPA_TIPO_DESPESA':   $SQL = new dml_putXMLTipo_Despesa; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_TIPO_ATUALIZACAO':   $SQL = new dml_putXMLTipo_Atualizacao; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_PPA_TIPO_PROGRAMA':  $SQL = new dml_putXMLTipo_Programa_PPA; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_TIPO_INCLUSAO_ACAO': $SQL = new dml_putXMLTipo_Inclusao_Acao; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_PPA_NATUREZA':       $SQL = new dml_putXMLNatureza; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],$w_param[3],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_PPA_FUNCAO':         $SQL = new dml_putXMLFuncao; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_PPA_SUBFUNCAO':      $SQL = new dml_putXMLSubFuncao; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],$w_param[3]); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_PPA_FONTE':          $SQL = new dml_putXMLFonte_PPA; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],$w_param[3],$w_param[4]); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_REGIAO':             $SQL = new dml_putXMLREGIAO; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],$w_param[3],$w_param[4]); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_MUNICIPIO':          $SQL = new dml_putXMLMunicipio; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],$w_param[3]); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_PPA_PRODUTO':        $SQL = new dml_putXMLProduto_PPA; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_PPA_PROGRAMA':       $SQL = new dml_putXMLPrograma_PPA; $SQL->getInstanceOf($dbms,&$w_resultado,$w_cliente,$w_ano,$w_param[1],$w_param[2],$w_param[3],$w_param[4],$w_param[5],$w_param[6],$w_param[7],$w_param[8],$w_param[9],$w_param[10],$w_param[11],$w_param[12],$w_param[13],$w_param[14],$w_param[15],$w_param[16],$w_param[17],$w_param[18]); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_PPA_INDICADOR':      $SQL = new dml_putXMLIndicador_PPA; $SQL->getInstanceOf($dbms,&$w_resultado,$w_cliente,$w_ano,$w_param[1],$w_param[2],$w_param[3],$w_param[4],$w_param[5],$w_param[6],$w_param[7],$w_param[8],$w_param[9],$w_param[10],$w_param[11],$w_param[12],$w_param[13],$w_param[14],$w_param[15],$w_param[16],$w_param[17],$w_param[18],$w_param[19],$w_param[20],$w_param[21],$w_param[22],$w_param[23],$w_param[24],$w_param[25],$w_param[26]); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_PPA_ACAO':           $SQL = new dml_putXMLAcao_PPA; $SQL->getInstanceOf($dbms,&$w_resultado,$w_cliente,$w_ano,$w_param[1],$w_param[2],$w_param[3],$w_param[4],$w_param[5],$w_param[6],$w_param[7],$w_param[8],$w_param[9],$w_param[10],$w_param[11],$w_param[12],$w_param[13],$w_param[14],$w_param[15],$w_param[16],$w_param[17],$w_param[18],$w_param[19],$w_param[20],$w_param[21],$w_param[22],$w_param[23],$w_param[24],$w_param[25],$w_param[26],$w_param[27],$w_param[28],$w_param[29],$w_param[30],$w_param[31],$w_param[32],$w_param[33],$w_param[34],$w_param[35],$w_param[36],$w_param[37],$w_param[38],$w_param[39],$w_param[40],$w_param[41],$w_param[42],$w_param[43],$w_param[44],$w_param[45]); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_PPA_LOCALIZADOR':    $SQL = new dml_putXMLLocalizador_PPA; $SQL->getInstanceOf($dbms,&$w_resultado,$w_cliente,$w_ano,$w_param[1],$w_param[2],$w_param[3],$w_param[4],$w_param[5],$w_param[6],$w_param[7],$w_param[8],$w_param[9],$w_param[10],$w_param[11],$w_param[12],$w_param[13],$w_param[14],$w_param[15],$w_param[16],$w_param[17],$w_param[18],$w_param[19],$w_param[20],$w_param[21],$w_param[22]); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_PPA_DADO_FISICO':    $SQL = new dml_putXMLDadoFisico_PPA; $SQL->getInstanceOf($dbms,&$w_resultado,$w_cliente,$w_ano,$w_param[1],$w_param[2],$w_param[3],$w_param[4],$w_param[5],$w_param[6],$w_param[7],$w_param[8],$w_param[9],$w_param[10],$w_param[11]); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_PPA_DADO_FINANCEIRO':$SQL = new dml_putXMLDadoFinanceiro_PPA; $SQL->getInstanceOf($dbms,&$w_resultado,$w_cliente,$w_ano,$w_param[1],$w_param[2],$w_param[3],$w_param[4],$w_param[5],$w_param[6],$w_param[7],$w_param[8],$w_param[9],$w_param[10],$w_param[11],$w_param[12],$w_param[13]); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_SIG_BASE_GEOGRAFICA':$SQL = new dml_putXMLBase_Geografica; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_SIG_FONTE':          $SQL = new dml_putXMLFonte_SIG; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],$w_param[3],$w_param[4],$w_param[5],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_SIG_OPCAO_ESTRAT':   $SQL = new dml_putXMLOpcao_Estrat; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_SIG_PERIODICIDADE':  $SQL = new dml_putXMLPeriodicidade; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_SIG_PRODUTO':        $SQL = new dml_putXMLProduto_SIG; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_SIG_TIPO_ACAO':      $SQL = new dml_putXMLTipo_Acao; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_SIG_TIPO_ORGAO':     $SQL = new dml_putXMLTipo_Orgao_SIG; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_SIG_TIPO_PROGRAMA':  $SQL = new dml_putXMLTipo_Programa; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_SIG_TIPO_RESTRICAO': $SQL = new dml_putXMLTipo_Restricao; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_SIG_TIPO_SITUACAO':  $SQL = new dml_putXMLTipo_Situacao; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],$w_param[3],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_SIG_UNIDADE_MEDIDA': $SQL = new dml_putXMLUnidade_Medida_SIG; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],$w_param[3],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_SIG_MACRO_OBJETIVO': $SQL = new dml_putXMLMacro_Objetivo; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],$w_param[3],'S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_SIG_ORGAO':          $SQL = new dml_putXMLOrgao_SIG; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],$w_param[3],$w_param[4],'---','S'); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_SIG_UNIDADE':        $SQL = new dml_putXMLUnidade_SIG; $SQL->getInstanceOf($dbms,&$w_resultado,$w_param[1],$w_param[2],$w_param[3],$w_param[4],$w_param[5],$w_param[6]); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_SIG_PROGRAMA':       $SQL = new dml_putXMLPrograma_SIG; $SQL->getInstanceOf($dbms,&$w_resultado,$w_cliente,$w_param[1],$w_param[2],$w_param[3],$w_param[4],$w_param[5],$w_param[6],$w_param[7],$w_param[8],$w_param[9],$w_param[10],$w_param[11],$w_param[12],$w_param[13],$w_param[14],$w_param[15],$w_param[16],$w_param[17],$w_param[18],$w_param[19],$w_param[20],$w_param[21],$w_param[22],$w_param[23],$w_param[24],$w_param[25],$w_param[26],$w_param[27],$w_param[28],$w_param[29],$w_param[30],$w_param[31],$w_param[32]);  if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_SIG_INDICADOR':      $SQL = new dml_putXMLIndicador_SIG; $SQL->getInstanceOf($dbms,&$w_resultado,$w_cliente,$w_param[1],$w_param[2],$w_param[3],$w_param[4],$w_param[5],$w_param[6],$w_param[7],$w_param[8],$w_param[9],$w_param[10],$w_param[11],$w_param[12],$w_param[13],$w_param[14],$w_param[15],$w_param[16],$w_param[17],$w_param[18],$w_param[19],$w_param[20],$w_param[21],$w_param[22],$w_param[23],$w_param[24],$w_param[25]); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_SIG_ACAO':           
                        // Só carrega ações que não sejam RAP (Restos a pagar)
                        if ($w_param[38]=='N')  { $SQL = new dml_putXMLAcao_SIG; $SQL->getInstanceOf($dbms,&$w_resultado,$w_cliente,$w_param[1],$w_param[2],$w_param[3],$w_param[4],$w_param[5],$w_param[6],$w_param[7],$w_param[8],$w_param[9],$w_param[10],$w_param[11],$w_param[12],$w_param[13],$w_param[14],$w_param[15],$w_param[16],$w_param[17],$w_param[18],$w_param[19],$w_param[20],$w_param[21],$w_param[22],$w_param[23],$w_param[24],$w_param[25],$w_param[26],$w_param[27],$w_param[28],$w_param[29],$w_param[30],$w_param[31],$w_param[32],$w_param[33],$w_param[34],$w_param[35],$w_param[36],$w_param[37],$w_param[38],$w_param[39],$w_param[40],$w_param[41],$w_param[42],$w_param[43],$w_param[44]); }
                        else                    $w_reg -= 1;
                        if($w_resultado>'')     $w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); 
                      break;
                      case 'IS_SIG_DADO_FISICO':    $SQL = new dml_putXMLDadoFisico_SIG; $SQL->getInstanceOf($dbms,&$w_resultado,$w_cliente,$w_param[1],$w_param[2],$w_param[3],$w_param[4],$w_param[5],$w_param[6],$w_param[7],$w_param[8],$w_param[9],$w_param[10],$w_param[11],$w_param[12],$w_param[13],$w_param[14],$w_param[15],$w_param[16],$w_param[17],$w_param[18],$w_param[19],$w_param[20],$w_param[21],$w_param[22],$w_param[23],$w_param[24],$w_param[25],$w_param[26],$w_param[27],$w_param[28],$w_param[29],$w_param[30],$w_param[31],$w_param[32],$w_param[33],$w_param[34],$w_param[35],$w_param[36],$w_param[37],$w_param[38],$w_param[39],$w_param[40],$w_param[41],$w_param[42],$w_param[43],$w_param[44],$w_param[45],$w_param[46],$w_param[47]); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_SIG_DADO_FINANCEIRO':$SQL = new dml_putXMLDadoFinanceiro_SIG; $SQL->getInstanceOf($dbms,&$w_resultado,$w_cliente,$w_param[1],$w_param[2],$w_param[3],$w_param[4],$w_param[5],$w_param[6],$w_param[7],$w_param[8],$w_param[9],$w_param[10],$w_param[11],$w_param[12],$w_param[13],$w_param[14],$w_param[15],$w_param[16],$w_param[17],$w_param[18],$w_param[19],$w_param[20],$w_param[21],$w_param[22],$w_param[23],$w_param[24],$w_param[25],$w_param[26],$w_param[27],$w_param[28],$w_param[29],$w_param[30],$w_param[31],$w_param[32],$w_param[33],$w_param[34],$w_param[35],$w_param[36],$w_param[37],$w_param[38],$w_param[39],$w_param[40],$w_param[41],$w_param[42],$w_param[43],$w_param[44],$w_param[45],$w_param[46],$w_param[47],$w_param[48]); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                      case 'IS_SIG_RESTRICAO_ACAO': $SQL = new dml_putXMLRestricaoAcao_SIG; $SQL->getInstanceOf($dbms,&$w_resultado,$w_cliente,$w_param[1],$w_param[2],$w_param[3],$w_param[4],$w_param[5],$w_param[6],$w_param[7],$w_param[8],$w_param[9],$w_param[10],$w_param[11],$w_param[12],$w_param[13],$w_param[14],$w_param[15]); if($w_resultado>'')$w_erro = $w_erro + RegistraErro($F1,$w_atributo,$w_param,$w_resultado); break;
                    } 
                  } 
                }   
                $w_atual = f($row1,'nm_tabela');
              } 
            } 
            fwrite($F1,'     Registros lidos: '.$w_reg.$crlf);
            fwrite($F1,'   Registros aceitos: '.($w_reg-$w_erro).$crlf);
            fwrite($F1,'Registros rejeitados: '.$w_erro.$crlf);
            fclose($F1);
            $w_arquivo_registro   = 'Arquivoregistro'.substr($w_arquivo_rejeicao,(strpos($w_arquivo_rejeicao,'.') ? strpos($w_arquivo_rejeicao,'.')+1 : 0)-1,30);
            $w_tamanho_registro   = filesize($w_caminho.$w_arquivo_rejeicao);
            $w_tipo_registro      = '';
            // Grava o resultado da importação no banco de dados
            $SQL = new dml_putDcOcorrencia; $SQL->getInstanceOf($dbms,$O,
              $_REQUEST['w_sq_esquema'],$w_cliente,$w_usuario,$_REQUEST['w_data_arquivo'],
              basename($w_arquivo_processamento),basename($w_arquivo_processamento),$w_tamanho_recebido,$w_tipo_recebido,
              basename($w_arquivo_registro),basename($w_arquivo_rejeicao),$w_tamanho_registro,$w_tipo_registro,
              $w_reg,$w_erro,basename($w_arquivo_processamento),basename($w_arquivo_registro));
          } else {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Atenção: O arquivo XML não pode ser carregado!\');');
            ShowHTML('  history.back(1);');
            ScriptClose();
            exit();
          }
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
          ScriptClose();
        } 
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.'ISSIGIMP'.MontaFiltro('UL')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      }
    break;
    case 'ISSIGIMP':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $SQL = new dml_putEsquema; $SQL->getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_sq_esquema'],$w_sq_modulo,$_REQUEST['w_nome'],$_REQUEST['w_descricao'],$_REQUEST['w_tipo'],
          $_REQUEST['w_ativo'],$_REQUEST['w_formato'],$_REQUEST['w_ws_servidor'],$_REQUEST['w_ws_url'],
          $_REQUEST['w_ws_acao'],$_REQUEST['w_ws_mensagem'],$_REQUEST['w_no_raiz'],null,null,null,null,0,null,null,null,null,null,null,null);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_sq_esquema='.$_REQUEST['w_sq_esquema'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
    break;
    case 'ISSIGEXP':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $SQL = new dml_putEsquema; $SQL->getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_sq_esquema'],$w_sq_modulo,$_REQUEST['w_nome'],$_REQUEST['w_descricao'],$_REQUEST['w_tipo'],
          $_REQUEST['w_ativo'],$_REQUEST['w_formato'],$_REQUEST['w_ws_servidor'],$_REQUEST['w_ws_url'],
          $_REQUEST['w_ws_acao'],$_REQUEST['w_ws_mensagem'],$_REQUEST['w_no_raiz']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_sq_esquema='.$_REQUEST['w_sq_esquema'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
    break;
    case 'ISSIGTAB':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $SQL = new dml_putEsquemaTabela; 
        $SQL1 = new dml_putEsquemaAtributo; 
        if ($O=='I') {
          for ($i=0; $i<=count($_POST['w_sq_tabela'])-1; $i=$i+1) {
            if ($_REQUEST['w_sq_tabela'][$i]>'') { $SQL->getInstanceOf($dbms,$O,null,$_REQUEST['w_sq_esquema'],$_REQUEST['w_sq_tabela'][$i],$_REQUEST['w_ordem'][$i],$_REQUEST['w_elemento'][$i]); }
          } 
        } elseif ($O=='A') {
          $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_sq_esquema_tabela'],$_REQUEST['w_sq_esquema'],null,$_REQUEST['w_ordem'],$_REQUEST['w_elemento']);
        } elseif ($O=='E') {
          $SQL1->getInstanceOf($dbms,$O,null,$_REQUEST['w_sq_esquema_tabela'],null,null,null);
          $SQL = new dml_putEsquemaTabela; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_sq_esquema_tabela'],null,null,null,null);
        } 
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_sq_esquema='.$_REQUEST['w_sq_esquema'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
    break;
    case 'ISSIGMAP':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $SQL = new dml_putEsquemaAtributo; 
        $SQL->getInstanceOf($dbms,'E',null,$_REQUEST['w_sq_esquema_tabela'],null,null,null);
        if ($O=='I') {
          for ($i=0; $i<=count($_POST['w_sq_coluna'])-1; $i=$i+1) {
            if ($_REQUEST['w_sq_coluna'][$i]>'') { $SQL->getInstanceOf($dbms,$O,null,$_REQUEST['w_sq_esquema_tabela'],$_REQUEST['w_sq_coluna'][$i],$_REQUEST['w_ordem'][$i],$_REQUEST['w_campo_externo'][$i]); }
          } 
        } 
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$w_pagina.'Tabela'.'&O=L&w_sq_esquema='.$_REQUEST['w_sq_esquema'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.'ISSIGTAB'.'&w_menu='.$w_menu.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
    break;
    default:
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Bloco de dados não encontrado: '.$SG.'\');');
      ShowHTML('  history.back(1);');
      ScriptClose();
    break;
  } 
} 
// =========================================================================
// Rotina de registro dos erros
// -------------------------------------------------------------------------
function RegistraErro($F1,$w_atributo,$w_param,$w_resultado) {
  extract($GLOBALS);
  for($j=1;$j<count($w_atributo)+1;$j+=1) {
    if($w_atributo[$j]>'')fwrite($F1,$w_atributo[$j].':['.$w_param[$j].']'.$crlf);
  } 
  fwrite($F1,$w_resultado.$crlf);
  fwrite($F1,'------------------------------------------------------------------------'.$crlf);
  $i = null;
  return 1;
} 
// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
  case 'INICIAL':       Inicial();      break;
  case 'HELP':          Help();         break;
  case 'GRAVA':         Grava();        break;
  case 'TABELA':        Tabela();       break;
  case 'MAPEAMENTO':    Mapeamento();   break;
  case 'IMPORTACAO':    Importacao();   break;
  case 'EXPORTACAO':    Exportacao();   break;
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
  } 
} 
?>
