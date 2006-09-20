<?
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getEsquema.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/db_getEsquemaTabela.php');
include_once($w_dir_volta.'classes/sp/db_getTabela.php');
include_once($w_dir_volta.'classes/sp/db_getColuna.php');
include_once($w_dir_volta.'classes/sp/db_getEsquemaAtributo.php');
include_once($w_dir_volta.'classes/sp/dml_putEsquema.php');
include_once($w_dir_volta.'classes/sp/dml_putEsquemaTabela.php');
include_once($w_dir_volta.'funcoes/selecaoFormato.php');
include_once($w_dir_volta.'funcoes/selecaoModulo.php');
include_once($w_dir_volta.'funcoes/selecaoSistema.php');
include_once($w_dir_volta.'funcoes/selecaoUsuario.php');
include_once($w_dir_volta.'funcoes/selecaoTipoTabela.php');
// =========================================================================
// /etl.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Rotinas de importação de dados
// Mail     : celso@sbpi.com.br
// Criacao  : 19/09/2006, 15:30
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
$w_assinatura   = strtoupper($_REQUEST['w_assinatura']);
$w_pagina       = 'etl.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_dc/';
$w_troca        = $_REQUEST['w_troca'];
$p_ordena       = strtolower($_REQUEST['p_ordena']);
// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();
$p_nome         = strtoupper($_REQUEST['p_nome']);
$p_tipo         = strtoupper($_REQUEST['p_tipo']);
$p_formato      = strtoupper($_REQUEST['p_formato']);
$p_sq_modulo    = strtoupper($_REQUEST['p_sq_modulo']);
$p_dt_ini       = $_REQUEST['p_dt_ini'];
$p_dt_fim       = $_REQUEST['p_dt_fim'];
$p_ref_ini      = $_REQUEST['p_ref_ini'];
$p_ref_fim      = $_REQUEST['p_ref_fim'];
switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';        break;
  case 'A': $w_TP=$TP.' - Alteração';       break;
  case 'E': $w_TP=$TP.' - Exclusão';        break;
  case 'P': $w_TP=$TP.' - Filtragem';       break;
  case 'C': $w_TP=$TP.' - Cópia';           break;
  case 'V': $w_TP=$TP.' - Envio';           break;
  case 'H': $w_TP=$TP.' - Herança';         break;
  case 'O': $w_TP=$TP.' - Orientações';     break;
  default:  $w_TP=$TP.' - Listagem';        break;
} 
if($O=='') $O='L';
if ($P1==1) $p_tipo='I'; else $p_tipo='E';
// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$RS = db_getLinkSubMenu::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu = 'Existe';
} else {
  $w_submenu = '';
}
// Recupera a configuração do serviço
if ($P2>0) {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,$P2);
} else {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,$w_menu);
}
// Se for sub-menu, pega a configuração do pai
if (f($RS_Menu,'ultimo_nivel')=='S') { 
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 
Main();
FechaSessao($dbms);
exit;
// =========================================================================
// Rotina de importação de arquivos físicos
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  $w_sq_esquema = $_REQUEST['w_sq_esquema'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_sq_esquema   = $_REQUEST['w_sq_esquema'];
    $w_sq_modulo    = $_REQUEST['w_sq_modulo'];
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
    $w_bd_hostname  = $_REQUEST['w_bd_hostname'];
    $w_bd_username  = $_REQUEST['w_bd_username'];
    $w_bd_password  = $_REQUEST['w_bd_password'];
    $w_tx_delimitador = $_REQUEST['w_tx_delimitador'];
    
  } elseif ($O=='L') {
    // Recupera todos os ws_url para a listagem
    $RS = db_getEsquema::getInstanceOf($dbms,$w_cliente,null,null,$w_sq_modulo,$p_nome,$p_tipo,$p_formato,$p_dt_ini,$p_dt_fim,$p_ref_ini,$p_ref_fim);
    if (Nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {    
      $RS = SortArray($RS,'nome','asc');
    }
  } elseif (!(strpos('AE',$O)===false)) {
    // Recupera todos os ws_url para a listagem
    $RS = db_getEsquema::getInstanceOf($dbms,$w_cliente,null,$w_sq_esquema,null,null,null,null,null,null,null,null);
    foreach($RS as $row){$RS=$row; break;}
    $w_sq_esquema   = f($RS,'sq_esquema');
    $w_sq_modulo    = f($RS,'sq_modulo');
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
    $w_bd_hostname  = f($RS,'bd_hostname');
    $w_bd_username  = f($RS,'bd_username');
    $w_bd_password  = f($RS,'bd_password');
    $w_tx_delimitador = f($RS,'tx_delimitador');
    
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    ValidateOpen('Validacao');
    if (!(strpos('IAE',$O)===false)) {
      if (!(strpos('IA',$O)===false)) {
        Validate('w_sq_modulo','Módulo','SELECT',1,1,10,'1','1');
        Validate('w_nome','Nome','1','1',3,60,'1','1');
        Validate('w_descricao','Descricao','1','',3,500,'1','1');
        if ($P1==1) Validate('w_formato','Formato','SELECT',1,1,10,'1','1');
        if ($w_formato=='W' || $w_formato=='A') {
          Validate('w_no_raiz','Nó raiz','1','1',3,50,'1','1');
          if ($w_formato=='W') {
            Validate('w_ws_servidor','Servidor','1','1',3,100,'1','1');
            Validate('w_ws_url','URL','1','1',3,100,'1','1');
            Validate('w_ws_acao','Ação','1','1',3,100,'1','1');
            Validate('w_ws_mensagem','Mensagem','1','1',3,4000,'1','1');
          }
        } else {
          Validate('w_bd_hostname','Hostname','1','1',3,50,'1','1');
          Validate('w_bd_username','Username','1','1',3,50,'1','1');
          Validate('w_bd_password','Password','1','1',3,50,'1','1');
          Validate('w_tx_delimitador','Delimitador','1','1',1,5,'1','1');
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
    BodyOpen(null);
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
    ShowHTML('    <td align="right"><font size="1"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan=2><font size="1"><b>'.LinkOrdena('Módulo','nm_modulo').'</font></td>');
    ShowHTML('          <td rowspan=2><font size="1"><b>'.LinkOrdena('Nome','nome').'</font></td>');
    ShowHTML('          <td rowspan=2><font size="1"><b>'.LinkOrdena('Formato','nm_formato').'</font></td>');
    ShowHTML('          <td rowspan=2><font size="1"><b>'.LinkOrdena('Ativo','nm_ativo').'</font></td>');
    ShowHTML('          <td rowspan=2><font size="1"><b>'.LinkOrdena('Tabelas','qtd_tabela').'</font></td>');
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
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=11 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os ws_url selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td><font size="1">'.f($row,'nm_modulo').'</td>');
        if ($P1==1) ShowHTML('        <td><font size="1">'.f($row,'nome').'</td>');
        else        ShowHTML('        <td><font size="1">'.LinkArquivo('HL',$w_cliente,f($row,'nome').'.xml','_blank','Exibe os dados do arquivo importado.',f($row,'nome'),null).'&nbsp;</td>');
        ShowHTML('        <td><font size="1">'.f($row,'nm_formato').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.Nvl(f($row,'qtd_tabela'),0).'</td>');
        if (Nvl(f($row,'data_ocorrencia'),'')>'')   ShowHTML('        <td align="center"><font size="1">'.FormataDataEdicao(f($row,'data_ocorrencia')).'</td>');
        else                                        ShowHTML('        <td align="center"><font size="1">---</td>');
        if (Nvl(f($row,'data_referencia'),'')>'')   ShowHTML('        <td align="center"><font size="1">'.substr(FormataDataEdicao(f($row,'data_referencia')),0,strlen(FormataDataEdicao(f($row,'data_referencia')))-3).'</td>');
        else                                        ShowHTML('        <td align="center"><font size="1">---</td>');
        if (Nvl(f($row,'processados'),0)>0)         ShowHTML('        <td align="right"><font size="1">'.LinkArquivo('HL',$w_cliente,f($row,'chave_recebido'),'_blank','Exibe os dados do arquivo importado.',Nvl(f($row,'processados'),0),null).'&nbsp;</td>');
        else                                        ShowHTML('        <td align="right"><font size="1">'.Nvl(f($row,'processados'),0).'&nbsp;</td>');
        ShowHTML('        <td align="right"><font size="1">'.(Nvl(f($row,'processados'),0)-Nvl(f($row,'rejeitados'),0)).'&nbsp;</td>');
        if (Nvl(f($row,'rejeitados'),0)>0)          ShowHTML('        <td align="right"><font size="1">'.LinkArquivo('HL',$w_cliente,f($row,'chave_result'),'_blank','Exibe o registro da importação.',Nvl(f($row,'rejeitados'),0),null).'&nbsp;</td>');
        else                                        ShowHTML('        <td align="right"><font size="1">'.Nvl(f($row,'rejeitados'),0).'&nbsp;</td>');
        ShowHTML('        <td align="top" nowrap><font size="1">');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_esquema='.f($row,'sq_esquema').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informações do esquema">Alterar</A>&nbsp');
        if (Nvl(f($row,'sq_ocorrencia'),'')>'')     ShowHTML('          <A class="hl" onClick="alert(\'Este esquema possui ocorrências, para desabilita-lo, inative-o!\');"title="Exclui o esquema">Excluir</A>&nbsp');
        else                                        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_esquema='.f($row,'sq_esquema').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclui o esquema">Excluir</A>&nbsp');
        ShowHTML('          <A class="hl" HREF="javascript:location.href=this.location.href;" onClick="window.open(\''.$w_pagina.'Tabela&R='.$w_dir.$w_pagina.'Tabela&O=L&w_sq_esquema='.f($row,'sq_esquema').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Tabelas&SG=ISSIGTAB&w_menu='.$w_menu.MontaFiltro('GET').'\',\'Tabelas\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Relaciona as tabelas que compõem o esquema">Tabelas</A>&nbsp');
        if (Nvl(f($row,'qtd_tabela'),0)>0) {
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
    if (!(strpos('E',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,1,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_sq_esquema" value="'.$w_sq_esquema.'">');
    ShowHTML('<INPUT type="hidden" name="w_tipo" value="'.$p_tipo.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    if ($P1==2) ShowHTML('<INPUT type="hidden" name="w_formato" value="A">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr>');
    selecaoModulo('<u>M</u>ódulo:','M',null,$w_sq_modulo,$w_cliente,'w_sq_modulo',null,'title="Selecione na lista o módulo desejado."');
    ShowHTML('      <tr><td><font size="1"><b><u>N</u>ome:<br><INPUT ACCESSKEY="N" TYPE="TEXT" CLASS="sti" NAME="w_nome" SIZE=60 MAXLENGTH=60 VALUE="'.$w_nome.'" '.$w_Disabled.' title="Nome do esquema."></td>');
    ShowHTML('      <tr><td><font size="1"><b><U>D</U>escrição:<br><TEXTAREA ACCESSKEY="D" class="sti" name="w_descricao" rows=3 cols=80 '.$w_Disabled.' title="Descreva sucintamente a finalidade deste esquema.">'.$w_descricao.'</textarea></td>');
    ShowHTML('      <tr><td><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    if ($P1==1) SelecaoFormato('Formato','F',null,$w_formato,null,'w_formato',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_formato\'; document.Form.submit();"');
    if ($w_formato=='A' || $w_formato=='W') {
      ShowHTML('          <td><font size="1"><b>Nó <u>r</u>aiz:<br><INPUT ACCESSKEY="R" TYPE="TEXT" CLASS="sti" NAME="w_no_raiz" SIZE=50 MAXLENGTH=50 VALUE="'.$w_no_raiz.'" '.$w_Disabled.' title="Informe o nome do nó raiz do documento XML."></td>');
      if ($w_formato=='A') {
        MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
        ShowHTML('              </table>');
      } else {
        ShowHTML('              </table>');
        ShowHTML('      <tr><td><font size="1"><b><u>S</u>ervidor:<br><INPUT ACCESSKEY="S" TYPE="TEXT" CLASS="sti" NAME="w_ws_servidor" SIZE=70 MAXLENGTH=100 VALUE="'.$w_ws_servidor.'" '.$w_Disabled.' title="Informe o nome do servidor onde o Web Service está instalado."></td>');
        ShowHTML('      <tr><td><font size="1"><b><u>U</u>RL:<br><INPUT ACCESSKEY="U" TYPE="TEXT" CLASS="sti" NAME="w_ws_url" SIZE=70 MAXLENGTH=100 VALUE="'.$w_ws_url.'" '.$w_Disabled.' title="Informe a URL para execução do Web Service."></td>');
        ShowHTML('      <tr><td><font size="1"><b>A<u>ç</u>ão:<br><INPUT ACCESSKEY="C" TYPE="TEXT" CLASS="sti" NAME="w_ws_acao" SIZE=70 MAXLENGTH=100 VALUE="'.$w_ws_acao.'" '.$w_Disabled.' title="Informe a ação que deseja executar no Web Service."></td>');
        ShowHTML('      <tr><td><font size="1"><b><U>M</U>ensagem:<br><TEXTAREA ACCESSKEY="M" class="sti" name="w_ws_mensagem" rows=10 cols=80 '.$w_Disabled.' title="Escreva o envelope da mensagem a ser enviada ao Web Service.">'.$w_ws_mensagem.'</textarea></td>');
        ShowHTML('      <tr>');
        MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
      }
    } elseif($w_formato=='T') {
      ShowHTML('      <tr><td><font size="1"><b><u>H</u>ostname:<br><INPUT ACCESSKEY="H" TYPE="TEXT" CLASS="sti" NAME="w_bd_hostname" SIZE=50 MAXLENGTH=50 VALUE="'.$w_bd_hostname.'" '.$w_Disabled.' title="Informe o hostname do banco de dados."></td>');
      ShowHTML('      <tr><td><font size="1"><b><u>U</u>sername:<br><INPUT ACCESSKEY="U" TYPE="TEXT" CLASS="sti" NAME="w_bd_username" SIZE=50 MAXLENGTH=50 VALUE="'.$w_bd_username.'" '.$w_Disabled.' title="Informe o login para acesso ao banco de dados."></td>');
      ShowHTML('      <tr><td><font size="1"><b><u>P</u>assword:<br><INPUT ACCESSKEY="P" TYPE="TEXT" CLASS="sti" NAME="w_bd_password" SIZE=50 MAXLENGTH=50 VALUE="'.$w_bd_password.'" '.$w_Disabled.' title="Informe a senha para acesso ao banco de dados."></td>');
      ShowHTML('      <tr><td><font size="1"><b><u>D</u>elimitador:<br><INPUT ACCESSKEY="D" TYPE="TEXT" CLASS="sti" NAME="w_tx_delimitador" SIZE=5 MAXLENGTH=5 VALUE="'.$w_tx_delimitador.'" '.$w_Disabled.' title="Informe o delimitador quer será usado para separar os campos no arquivo TXT."></td>');
      MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
      ShowHTML('              </table>');    
    } 
    ShowHTML('      <tr><td align="LEFT"><font size="1"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E')    ShowHTML('          <input class="STB" type="submit" name="Botao" value="Excluir">');
    else            ShowHTML('          <input class="STB" type="submit" name="Botao" value="Gravar">');
    ShowHTML('          <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L\';" name="Botao" value="Cancelar">');
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
  $w_troca              = $_REQUEST['w_troca'];
  $p_nome               = $_REQUEST['p_nome'];
  $p_sq_sistema         = $_REQUEST['p_sq_sistema'];
  $p_sq_usuario         = $_REQUEST['p_sq_usuario'];
  $p_sq_tabela_tipo     = $_REQUEST['p_sq_tabela_tipo'];
  //Recupera os dados do esquema para a montagem do cabeçalho
  $RS1 = db_getEsquema::getInstanceOf($dbms,$w_cliente,null,$w_sq_esquema,null,null,null,null,null,null,null,null);
  foreach($RS1 as $row){$RS1=$row; break;}
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
    $RS = db_getEsquemaTabela::getInstanceOf($dbms,null,$w_sq_esquema,null);
    if (Nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {    
      $RS = SortArray($RS,'ordem','asc','nm_tabela','asc','or_coluna','asc');
    }
  } elseif (!(strpos('I',$O)===false)) {
    $RS = db_getTabela::getInstanceOf($dbms,$w_cliente,null,$w_sq_esquema,$p_sq_sistema,$p_sq_usuario,$p_sq_tabela_tipo,$p_nome,$SG);
    $RS = SortArray($RS,'sg_sistema','asc','nm_usuario','asc','nome','asc');
  } elseif (!(strpos('A',$O)===false)) {
    // Recupera todos os ws_url para a listagem
    $RS = db_getEsquemaTabela::getInstanceOf($dbms,null,$w_sq_esquema,$w_sq_esquema_tabela);
    foreach($RS as $row){$RS=$row; break;}
    $w_ordem    = f($RS,'ordem');
    $w_elemento = f($RS,'elemento');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAPM',$O)===false)) {
    ScriptOpen('JavaScript');
    if (!(strpos('I',$O)===false)) {
      ShowHTML('  function valor(p_indice) {');
      ShowHTML('    if (document.Form["w_sq_tabela[]"][p_indice].checked) { ');
      ShowHTML('       document.Form["w_ordem[]"][p_indice].disabled=false; ');
      ShowHTML('       document.Form["w_elemento[]"][p_indice].disabled=false; ');
      ShowHTML('       document.Form["w_elemento[]"][p_indice].focus; ');
      ShowHTML('    } else {');
      ShowHTML('       document.Form["w_ordem[]"][p_indice].disabled=true; ');
      ShowHTML('       document.Form["w_elemento[]"][p_indice].disabled=true; ');
      ShowHTML('       document.Form["w_ordem[]"][p_indice].value=\'\'; ');
      ShowHTML('       document.Form["w_elemento[]"][p_indice].value=\'\'; ');      
      ShowHTML('    }');
      ShowHTML('  }');
      ShowHTML('  function MarcaTodos() {');
      ShowHTML('    if (document.Form["w_elemento[]"].value==undefined) ');
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
      ShowHTML('    else document.Form["w_sq_tabela[]"].checked=false;');
      ShowHTML('  }');
    } 
    CheckBranco();
    FormataData();
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
        if(f($RS1,'formato')=='T')  ShowHTML('      alert(\'Para todas as tabelas selecionadas você deve informar o arquivo!\'); ');
        else                        ShowHTML('      alert(\'Para todas as tabelas selecionadas você deve informar o elemento da tabela!\'); ');
        ShowHTML('      return false;');
        ShowHTML('    }');
        ShowHTML('  }');
        ShowHTML('  for (i=0; i < theForm["w_sq_tabela[]"].length; i++) {');
        ShowHTML('    if((theForm["w_sq_tabela[]"][i].checked)&&(theForm["w_ordem[]"][i].value==\'\')){');
        ShowHTML('      alert(\'Para todas as tabelas selecionadas vc deve informar a ordem da tabela para a importação do esquema!\');');
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
    $RS1 = db_getEsquemaTabela::getInstanceOf($dbms,null,$w_sq_esquema,$w_sq_esquema_tabela);
    foreach($RS1 as $row){$RS1=$row; break;}
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
    ShowHTML('    <td align="right"><font size="1"><b>Registros existentes: '.count($RS));
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
          ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_esquema='.f($row,'sq_esquema').'&w_sq_esquema_tabela='.f($row,'sq_esquema_tabela').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.MontaFiltro('GET').'" title="Altera a os dados da tabela deste esquema">Alterar</A>&nbsp');
          ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Grava'.'&R='.$w_pagina.$par.'&O=E&w_sq_esquema='.f($row,'sq_esquema').'&w_sq_esquema_tabela='.f($row,'sq_esquema_tabela').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.MontaFiltro('GET').'" title="Exclui a tabela deste esquema" onClick="return confirm(\'Confirma a exclusão do registro?\');">Excluir</A>&nbsp');
          ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'MAPEAMENTO&R='.$w_pagina.$par.'&O=I&w_sq_esquema='.f($row,'sq_esquema').'&w_sq_esquema_tabela='.f($row,'sq_esquema_tabela').'&w_sq_tabela='.f($row,'sq_tabela').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.MontaFiltro('GET').'" title="Relaciona os campos da tabela">Mapear</A>&nbsp');
        } 
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
        $w_atual=f($row,'nm_tabela');
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
    ShowHTML('      <tr><td valign="top"><font size="1"><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="p_nome" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$p_nome.'"></td>');
    SelecaoTipoTabela('<u>T</u>ipo:','T',null,$p_sq_tabela_tipo,null,'p_sq_tabela_tipo',null,null);
    ShowHTML('      <tr><td valign="top"><font size="1"><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="sti" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&w_sq_esquema='.$w_sq_esquema.'&R='.$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&O=P&SG='.$SG.'&w_menu='.$w_menu.'\';" name="Botao" value="Limpar campos">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&w_sq_esquema='.$w_sq_esquema.'&R='.$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.'&O=L\';" name="Botao" value="Cancelar">');
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
    ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a></font>');
    ShowHTML('    <td align="right"><font size="1"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center" valign="top">');
    ShowHTML('            <td width="70"NOWRAP><font size="2"><U ID="INICIO" STYLE="cursor:hand;" CLASS="hl" onClick="javascript:MarcaTodos();" TITLE="Marca todos os itens da relação"><IMG SRC="images/NavButton/BookmarkAndPageActivecolor.gif" BORDER="1" width="15" height="15"></U>&nbsp;');
    ShowHTML('                                      <U STYLE="cursor:hand;" CLASS="hl" onClick="javascript:DesmarcaTodos();" TITLE="Desmarca todos os itens da relação"><IMG SRC="images/NavButton/BookmarkAndPageInactive.gif" BORDER="1" width="15" height="15"></U>');
    ShowHTML('          <td><font size="1"><b>Sistema</b></font></td>');
    ShowHTML('          <td><font size="1"><b>Tabela</b></font></td>');
    ShowHTML('          <td><font size="1"><b>Descrição</b></font></td>');
    if(f($RS1,'formato')=='T')  ShowHTML('          <td><font size="1"><b>Arquivo (Insira o caminho completo)</b></font></td>');
    else                        ShowHTML('          <td><font size="1"><b>Elemento</b></font></td>');
    ShowHTML('          <td><font size="1"><b>Ordem</b></font></td>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      $w_cont=0;
      foreach($RS1 as $row) {
        $w_cont+=1;
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;        
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center"><input type="checkbox" name="w_sq_tabela[]" value="'.f($row,'chave').'" onClick="valor('.$w_cont.');">');
        ShowHTML('        <td><font size="1">'.f($row,'sg_sistema').'</td>');
        ShowHTML('        <td><font size="1">'.strtolower(f($row,'nm_usuario').'.'.f($row,'nome')).'</td>');
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
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&w_sq_esquema='.$w_sq_esquema.'&R='.$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.'&O=L\';" name="Botao" value="Cancelar">');
    ShowHTML('  </td>');
    ShowHTML('</FORM>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
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
    ShowHTML('      <tr><td valign="top"><font size="1"><b><u>E</u>lemento:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_elemento" class="sti" SIZE="30" MAXLENGTH="50" VALUE="'.$w_elemento.'"></td>');
    ShowHTML('      <tr><td valign="top"><font size="1"><b><u>O</u>rdem:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_ordem" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_ordem.'"></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&w_sq_esquema='.$w_sq_esquema.'&R='.$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.'&O=L\';" name="Botao" value="Cancelar">');
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
    $RS = db_getColuna::getInstanceOf($dbms,$w_cliente,null,$w_sq_tabela,null,null,null,null,$w_sq_esquema_tabela);
    if (Nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {    
      $RS = SortArray($RS,'ordem','asc','nm_coluna','asc');
    }
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
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
    ShowHTML('    if((theForm["w_sq_coluna[]"][i].checked)&&(theForm["w_campo_externo[]"][i].value==\'\')){');
    ShowHTML('      alert(\'Para todas as colunas selecionadas vc deve informar o campo externo da coluna!\'); ');
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
  $RS1 = db_getEsquema::getInstanceOf($dbms,$w_cliente,null,$w_sq_esquema,null,null,null,null,null,null,null,null);
  foreach($RS1 as $row){$RS1=$row; break;}
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr><td colspan=3 bgcolor="#FAEBD7"><table border=1 width="100%"><tr><td>');
  ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('      <tr><td colspan="3"><font size="1">Esquema: <b>'.f($RS1,'nome').'</font></b></td>');
  ShowHTML('      <tr><td colspan="3"><font size="1">Descrição: <b>'.Nvl(f($RS1,'Descricao'),'---').'</font></b></td>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('          <td><font size="1">Tipo: <b>'.f($RS1,'nm_tipo').'</b></td>');
  ShowHTML('          <td><font size="1">Formato: <b>'.f($RS1,'nm_formato').'</b></td>');
  ShowHTML('          <td><font size="1">Ativo: <b>'.f($RS1,'nm_ativo').'</b></td>');
  $RS1 = db_getEsquemaTabela::getInstanceOf($dbms,null,$w_sq_esquema,$w_sq_esquema_tabela);
  foreach($RS1 as $row){$RS1=$row; break;}
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
    ShowHTML('    <td align="right"><font size="1"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center" valign="top">');
    ShowHTML('            <td width="70"NOWRAP><font size="2"><U ID="INICIO" STYLE="cursor:hand;" CLASS="hl" onClick="javascript:MarcaTodos();" TITLE="Marca todos os itens da relação"><IMG SRC="images/NavButton/BookmarkAndPageActivecolor.gif" BORDER="1" width="15" height="15"></U>&nbsp;');
    ShowHTML('                                      <U STYLE="cursor:hand;" CLASS="hl" onClick="javascript:DesmarcaTodos();" TITLE="Desmarca todos os itens da relação"><IMG SRC="images/NavButton/BookmarkAndPageInactive.gif" BORDER="1" width="15" height="15"></U>');
    ShowHTML('          <td><font size="1"><b>Coluna</b></font></td>');
    ShowHTML('          <td><font size="1"><b>Descricao</b></font></td>');
    ShowHTML('          <td><font size="1"><b>Tipo</b></font></td>');
    ShowHTML('          <td><font size="1"><b>Obrig.</b></font></td>');
    ShowHTML('          <td><font size="1"><b>Campo externo</b></font></td>');
    ShowHTML('          <td><font size="1"><b>Ordem</b></font></td>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_Disabled = 'DISABLED';
      $w_cont=0;
      foreach($RS as $row) {
        $w_cont+=1;
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        $RS1 = db_getEsquemaAtributo::getInstanceOf($dbms,null,$w_sq_esquema_tabela,null,f($row,'chave'));
        foreach($RS1 as $row1){$RS1=$row1; break;}
        if (count($RS1)>0) {
          ShowHTML('        <td align="center"><input type="checkbox" name="w_sq_coluna[]" value="'.f($row,'chave').'" onClick="valor('.$w_cont.');" CHECKED>');
          $w_ordem            = f($row1,'ordem');
          $w_campo_externo    = f($row1,'campo_externo');
          $w_Disabled='';
        } else {
          ShowHTML('        <td align="center"><input type="checkbox" name="w_sq_coluna[]" value="'.f($row,'chave').'" onClick="valor('.$w_cont.');">');
        } 
        ShowHTML('        <td><font size="1">'.f($row,'nm_coluna').'</td>');
        ShowHTML('        <td><font size="1">'.Nvl(f($row,'descricao'),'---').'</td>');
        ShowHTML('        <td nowrap><font size="1">'.f($row,'nm_coluna_tipo').' (');
        if (strtoupper(f($RS,'nm_coluna_tipo'))=='NUMERIC') ShowHTML(Nvl(f($row,'precisao'),f($RS,'tamanho')).','.Nvl(f($row,'escala'),0));
        else                                                ShowHTML(f($row,'tamanho'));
        ShowHTML(')</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'obrigatorio').'</td>');
        ShowHTML('        <td><font size="1"><input '.$w_Disabled.' type="text" name="w_campo_externo[]" class="sti" SIZE="20" MAXLENGTH="30" VALUE="'.$w_campo_externo.'"></td>');
        ShowHTML('        <td><font size="1"><input '.$w_Disabled.' type="text" name="w_ordem[]" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_ordem.'"></td>');
        ShowHTML('      </tr>');
        $w_ordem          = '';
        $w_campo_externo  = '';
        $w_Disabled='DISABLED';
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('<tr><td align="center" colspan="3"><font size="1">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.'Tabela'.'&w_sq_esquema='.$w_sq_esquema.'&R='.$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.'&O=L\';" name="Botao" value="Cancelar">');
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
// Rotina de importação de arquivos físicos para atualização
// -------------------------------------------------------------------------
function Importacao() {
  extract($GLOBALS);
  $w_sq_esquema = $_REQUEST['w_sq_esquema'];
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  $w_upload_maximo = f($RS,'upload_maximo');
  if ($O=='I') {
    // Recupera todos os ws_url para a listagem
    $RS = db_getEsquema::getInstanceOf($dbms,$w_cliente,null,$w_sq_esquema,null,null,null,null,null,null,null,null);
  } elseif (!(strpos('AE',$O)===false)) { } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataDataHora();
    FormataData();
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
    if (!f($RS,'formato')=='W') {
      ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG=IMPARQ&O='.$O.'&w_menu='.$w_menu.'&UploadID='.$UploadID.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
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
    if (!f($RS,'formato')=='W') {
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
      ShowHTML('      <tr><td><font size="1"><b><u>D</u>ata/hora extração:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data_arquivo" class="sti" SIZE="17" MAXLENGTH="17" VALUE="'.$w_data_arquivo.'"  onKeyDown="FormataDataHora(this, event);" title="OBRIGATÓRIO. Informe a data e hora da extração do aquivo. Digite apenas números. O sistema colocará os separadores automaticamente."></td>');
      ShowHTML('      <tr><td><font size="1"><b>A<u>r</u>quivo:</b><br><input accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OBRIGATÓRIO. Clique no botão ao lado para localizar o arquivo. Ele será transferido automaticamente para o servidor.">');
    } 
    ShowHTML('      <tr><td align="LEFT"><font size="1"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('          <input class="STB" type="submit" name="Botao" value="Importar">');
    ShowHTML('          <input class="stb" type="button" onClick="location.href=\''.$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.'ISSIGIMP'.'&O=L\';" name="Botao" value="Cancelar">');
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
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  Cabecalho();
  ShowHTML('</HEAD>');
  BodyOpenClean(null);
  switch ($SG) {
    case 'TIMPORT':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putEsquema::getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_sq_esquema'],$_REQUEST['w_sq_modulo'],$_REQUEST['w_nome'],$_REQUEST['w_descricao'],$_REQUEST['w_tipo'],
                $_REQUEST['w_ativo'],$_REQUEST['w_formato'],$_REQUEST['w_ws_servidor'],$_REQUEST['w_ws_url'],
                $_REQUEST['w_ws_acao'],$_REQUEST['w_ws_mensagem'],$_REQUEST['w_no_raiz'],$_REQUEST['w_bd_hostname'],$_REQUEST['w_bd_username'],
                $_REQUEST['w_bd_password'],$_REQUEST['w_tx_delimitador']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&O=L&w_sq_esquema='.$_REQUEST['w_sq_esquema'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
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
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='I') {
          for ($i=0; $i<=count($_POST['w_sq_tabela'])-1; $i=$i+1) {
            if ($_REQUEST['w_sq_tabela'][$i]>'') {
              dml_putEsquemaTabela::getInstanceOf($dbms,$O,null,$_REQUEST['w_sq_esquema'],$_REQUEST['w_sq_tabela'][$i],$_REQUEST['w_ordem'][$i],$_REQUEST['w_elemento'][$i]);
            } 
          } 
        } elseif ($O=='A') {
          dml_putEsquemaTabela::getInstanceOf($dbms,$O,$_REQUEST['w_sq_esquema_tabela'],$_REQUEST['w_sq_esquema'],null,$_REQUEST['w_ordem'],$_REQUEST['w_elemento']);
        } elseif ($O=='E') {
          dml_putEsquemaTabela::getInstanceOf($dbms,$O,$_REQUEST['w_sq_esquema_tabela'],null,null,null,null);
        } 
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&O=L&w_sq_esquema='.$_REQUEST['w_sq_esquema'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_menu='.$w_menu.MontaFiltro('GET').'\';');
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
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putEsquemaAtributo::getInstanceOf($dbms,'E',null,$_REQUEST['w_sq_esquema_tabela'],null,null,null);
        if ($O=='I') {
          for ($i=0; $i<=count($_POST['w_sq_coluna'])-1; $i=$i+1) {
            if ($_REQUEST['w_sq_coluna'][$i]>'') {
              dml_putEsquemaAtributo::getInstanceOf($dbms,$O,null,$_REQUEST['w_sq_esquema_tabela'],$_REQUEST['w_sq_coluna'][$i],$_REQUEST['w_ordem'][$i],$_REQUEST['w_campo_externo'][$i]);
            }
          } 
        } 
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$w_pagina.'Tabela'.'&O=L&w_sq_esquema='.$_REQUEST['w_sq_esquema'].'&w_sq_esquema_tabela='.$_REQUEST['w_sq_esquema_tabela'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.'ISSIGIMP'.MontaFiltro('GET').'\';');
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
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'INICIAL':     Inicial();      break;
    case 'GRAVA':       Grava();        break;
    case 'TABELA':      Tabela();       break;
    case 'MAPEAMENTO':  Mapeamento();   break;
    case 'IMPORTACAO':  Importacao();   break;
    default:
      cabecalho();
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');      
      BodyOpen('onLoad=document.focus();');
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
