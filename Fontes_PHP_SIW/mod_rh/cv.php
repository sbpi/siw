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
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getCVList.php');
include_once($w_dir_volta.'classes/sp/db_getCV.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCVAcadForm.php');
include_once($w_dir_volta.'classes/sp/db_getCVIdioma.php');
include_once($w_dir_volta.'classes/sp/db_getGPColaborador.php');
include_once($w_dir_volta.'classes/sp/db_getFoneList.php');
include_once($w_dir_volta.'classes/sp/db_getAddressList.php');
include_once($w_dir_volta.'classes/sp/db_getContaBancoList.php');
include_once($w_dir_volta.'classes/sp/db_getCVAcadForm.php');
include_once($w_dir_volta.'classes/sp/db_getIdiomList.php');
include_once($w_dir_volta.'classes/sp/db_getGPContrato.php');
include_once($w_dir_volta.'classes/sp/db_getKnowArea.php');
include_once($w_dir_volta.'classes/sp/db_getTipoPostoList.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/db_getCV_Pessoa.php');
include_once($w_dir_volta.'classes/sp/dml_putCVIdent.php');
include_once($w_dir_volta.'classes/sp/dml_putCVProducao.php');
include_once($w_dir_volta.'classes/sp/dml_putCVEscola.php');
include_once($w_dir_volta.'classes/sp/dml_putCVIdioma.php');
include_once($w_dir_volta.'classes/sp/dml_putCVCargo.php');
include_once($w_dir_volta.'classes/sp/dml_putCVCurso.php');
include_once($w_dir_volta.'classes/sp/dml_putCVHist.php');
include_once($w_dir_volta.'classes/sp/dml_putCVExperiencia.php');
include_once($w_dir_volta.'classes/sp/dml_putGPContrato.php');
include_once($w_dir_volta.'classes/sp/dml_putSiwUsuario.php');
include_once($w_dir_volta.'funcoes/selecaoCargo.php');
include_once($w_dir_volta.'funcoes/selecaoSexo.php');
include_once($w_dir_volta.'funcoes/selecaoEstadoCivil.php');
include_once($w_dir_volta.'funcoes/selecaoFormacao.php');
include_once($w_dir_volta.'funcoes/selecaoEtnia.php');
include_once($w_dir_volta.'funcoes/selecaoDeficiencia.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoIdioma.php');
include_once($w_dir_volta.'funcoes/selecaoTipoPosto.php');
include_once($w_dir_volta.'funcoes/selecaoModalidade.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoLocalizacao.php');
include_once($w_dir_volta.'funcoes/selecaoVinculo.php');
include_once('visualCurriculo.php');

// =========================================================================
//  /cv.php
// ------------------------------------------------------------------------
// Nome     : Billy Jones Leal dos Santos
// Descricao: Gerencia telas do currículo do colaborador
// Mail     : billy@sbpi.com.br
// Criacao  : 31/09/2006 14:25
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

if(nvl($_REQUEST['p_cliente'],'nulo')!='nulo') $_SESSION['CLIENTE'] = $_REQUEST['p_cliente'];
if(nvl($_REQUEST['p_portal'],'nulo')!='nulo')  $_SESSION['PORTAL']  = $_REQUEST['p_portal'];
if(nvl($_REQUEST['p_logon'],'nulo')!='nulo')   $_SESSION['LOGON']   = $_REQUEST['p_LogOn'];
if(nvl($_REQUEST['p_dbms'],'nulo')!='nulo')    $_SESSION['DBMS']    = $_REQUEST['p_dbms'];

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par          = upper($_REQUEST['par']);
$w_pagina     = 'cv.php?par=';
$w_dir        = 'mod_rh/';
$w_Disabled   = 'ENABLED';
$SG           = upper($_REQUEST['SG']);
$O            = upper($_REQUEST['O']);
$w_cliente    = RetornaCliente();
$w_usuario    = RetornaUsuario();
$P1           = Nvl($_REQUEST['P1'],0);
$P2           = Nvl($_REQUEST['P2'],0);
$P3           = Nvl($_REQUEST['P3'],1);
$P4           = Nvl($_REQUEST['P4'],$conPageSize);
$TP           = $_REQUEST['TP'];
$R            = $_REQUEST['R'];
$w_troca      = lower($_REQUEST['w_troca']);
$w_copia      = $_REQUEST['w_copia'];
$w_assinatura = $_REQUEST['w_assinatura'];
  
// Verifica se o usuário está autenticado
if ($_SESSION['LOGON'] !='Sim') EncerraSessao();

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ($_SESSION['PORTAL'] >'') $_SESSION['SQ_PESSOA'] = $w_usuario;

if (nvl($SG,'nulo')!='nulo' && nvl($SG,'nulo')!='CVCARGOS') $w_menu = RetornaMenu($w_cliente,$SG);

if ($SG=='GDPINTERES' || $SG=='GDPAREAS') {
  if ($O!='I' && nvl($_REQUEST['w_chave_aux'],'')=='') $O='L';
} elseif ($SG=='GDPENVIO') {
  $O='V';
} elseif ($O=='') {
  // Se for acompanhamento, entra na filtragem
  if ($P1==3) $O='P'; else $O='L';  
} 
switch ($O) {
  case 'I':   $w_TP=$TP.' - Inclusão';    break;
  case 'A':   $w_TP=$TP.' - Alteração';   break;
  case 'E':   $w_TP=$TP.' - Exclusão';    break;
  case 'P':   $w_TP=$TP.' - Filtragem';   break;
  case 'C':   $w_TP=$TP.' - Cópia';       break;
  case 'V':   $w_TP=$TP.' - Envio';       break;
  case 'H':   $w_TP=$TP.' - Herança';     break;
default:      $w_TP=$TP.' - Listagem';    break;
} 
// Recupera a configuração do serviço
if ($P2>0 && $SG!='CVVISUAL') {
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
// Rotina de visualização resumida dos registros
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;

  $p_sq_idioma   = $_REQUEST['p_sq_idioma'];
  $p_sexo        = $_REQUEST['p_sexo'];
  $p_nome        = upper($_REQUEST['p_nome']);
  $p_sq_formacao = $_REQUEST['p_sq_formacao'];
  if ($O=='L') {
    // Recupera os currículos existentes na base de dados
    $sql = new db_getCVList; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_sq_formacao,$p_sq_idioma,$p_sexo,$p_nome);
    $RS = SortArray($RS,'nome_resumido', 'asc');
  }
  Cabecalho();
  head();
  if ($P1==2) {
    ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.$w_dir_volta.MontaURL('MESA').'">');
  } 
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de atividades</TITLE>');
  ScriptOpen('Javascript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  ValidateOpen('Validacao');
  if (strpos('P',$O)!==false) {
    Validate('p_nome','Nome','1','','3','40','1','1');
    Validate('P4','Linhas por página','1','1','1','4','','0123456789');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca >'') {
    // Se for recarga da página
    BodyOpen('onLoad=\'document.Form.\'.$w_troca.\'.focus();\'');
  } elseif (strpos('P',$O)!==false){
    BodyOpen('onLoad=\'document.Form.P4.focus()\';');
  } else {
    BodyOpen('onLoad=this.focus();');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  if ($w_filtro >'') {
    ShowHTML;
    ($w_filtro);
  }
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td>');
    if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    } else {
    ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
    } 
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>Sexo</td>');
    ShowHTML('          <td><b>Formação</td>');
    ShowHTML('          <td class="remover"><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $RS1 = array_slice($RS,(($P3-1)*$P4), $P4);
      foreach($RS1 as $row){
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor; 
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nome_resumido').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_sexo').'</td>');
        ShowHTML('        <td>'.f($row,'nm_formacao').'</td>');
        ShowHTML('        <td class="remover">');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Visualizar&R='.$w_pagina.$par.'&O=L&w_usuario='.f($row,'sq_pessoa').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe o CV deste colaborador." target="_blank">Exibir</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R >'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET'),(count($RS)/$P4),$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET'),$RS->PageCount,$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');
  } elseif (strpos('P',$O)!==false) {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr><td valign="top" width="50%"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_nome" size="40" maxlength="40" value="'.$p_nome.'"></td>');
    ShowHTML('      <tr>');
    SelecaoFormacao('F<u>o</u>rmação acadêmica:','O',null,$p_sq_formacao,'Acadêmica','p_sq_formacao',null,null);
    ShowHTML('      <tr>');
    SelecaoIdioma('I<u>d</u>ioma:','D',null,$p_sq_idioma,null,'p_sq_idioma',null,null);
    SelecaoSexo('Se<u>x</u>o:','X',null,$p_sexo,null,'p_sexo',null,null);
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  Rodape();
} 
// =========================================================================
// Rotina dos dados de identificação
// -------------------------------------------------------------------------
function Identificacao() {
  extract($GLOBALS);
  global $w_Disabled;
  if (Nvl($P1,0)==1 && $w_troca=='') {
    //$w_chave  = Nvl($_REQUEST['w_sq_pessoa'],$w_usuario);
    $w_cpf    = $_REQUEST['w_cpf'];
    if ($w_cpf>'' || (Nvl($_REQUEST['w_sq_pessoa'],'')>'') || (Nvl($_REQUEST['w_chave'],'')>'')) {
      if ($w_cpf > '') {
        $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,$w_cpf,null);
        if (count($RS)>0) {
          $w_chave  = f($RS,'sq_pessoa');
        } else {
          $w_chave='';
        } 
      } else {
        $w_chave  = Nvl($_REQUEST['w_sq_pessoa'],$_REQUEST['w_chave']);
      }
    } else {
      $w_chave = $w_usuario;
    }
    if (Nvl($w_chave,'')>'' && $O=='I'){
      $sql = new db_getGPColaborador; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,null,'S',null,null,null,null,null,null,null,null,null,null,null,null);
      if (count($RS)>0) {
        ScriptOpen('JavaScript');
        ShowHTML(' alert(\'Colaborador já cadastrado!\');');
        ShowHTML(' history.back(1);');
        ScriptClose();
      } 
    } 
  } else {
    if ($O!='I') $w_chave = Nvl($_REQUEST['w_chave'],$w_usuario);
  } 
  $w_readonly = '';
  $w_erro = '';
  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca >'') {
    // Se for recarga da página
    $w_sq_estado_civil    = $_REQUEST['w_sq_estado_civil'];
    $w_nome               = $_REQUEST['w_nome'];
    $w_nome_resumido      = $_REQUEST['w_nome_resumido'];
    $w_foto               = $_REQUEST['w_foto'];
    $w_nascimento         = $_REQUEST['w_nascimento'];
    $w_rg_numero          = $_REQUEST['w_rg_numero'];
    $w_rg_emissor         = $_REQUEST['w_rg_emissor'];
    $w_rg_emissao         = $_REQUEST['w_rg_emissao'];
    $w_cpf                = $_REQUEST['w_cpf'];
    $w_pais               = $_REQUEST['w_pais'];
    $w_uf                 = $_REQUEST['w_uf'];
    $w_cidade             = $_REQUEST['w_cidade'];
    $w_passaporte_numero  = $_REQUEST['w_passaporte_numero'];
    $w_sq_pais_passaporte = $_REQUEST['w_passaporte_numero'];
    $w_sq_etnia           = $_REQUEST['w_sq_etnia'];
    $w_sq_deficiencia     = $_REQUEST['w_sq_deficiencia'];
    $w_sexo               = $_REQUEST['w_sexo'];
    $w_sq_formacao        = $_REQUEST['w_sq_formacao'];
    if (Nvl($P1,0)==1) {
      $w_posto_trabalho      = $_REQUEST['w_posto_trabalho'];
      $w_modalidade_contrato = $_REQUEST['w_modalidade_contrato'];
      $w_unidade_lotacao     = $_REQUEST['w_unidade_lotacao'];
      $w_unidade_exercicio   = $_REQUEST['w_unidade_exercicio'];
      $w_localizacao         = $_REQUEST['w_localizacao'];
      $w_matricula           = $_REQUEST['w_matricula'];
      $w_dt_ini              = $_REQUEST['w_dt_ini'];
      $w_envio_email         = $_REQUEST['w_envio_email'];
      $w_sq_tipo_vinculo     = $_REQUEST['w_sq_tipo_vinculo'];
      $w_username_pessoa     = $_REQUEST['w_username_pessoa'];
      $w_email               = $_REQUEST['w_email'];
    } 
  } else {
    // Recupera os dados do currículo a partir da chave 
    $sql = new db_getCV; $RS = $sql->getInstanceOf($dbms,$w_cliente,nvl($w_chave,0),$SG,'DADOS');
    foreach ($RS as $row) {$RS=$row; break;}
    if (count($RS)>0) {
      $w_sq_estado_civil    = f($RS,'sq_estado_civil');
      $w_nome               = f($RS,'nome');
      $w_nome_resumido      = f($RS,'nome_resumido');
      $w_foto               = f($RS,'sq_siw_arquivo');
      $w_nascimento         = FormataDataEdicao(f($RS,'nascimento'));
      $w_rg_numero          = f($RS,'rg_numero');
      $w_rg_emissor         = f($RS,'rg_emissor');
      $w_rg_emissao         = FormataDataEdicao(f($RS,'rg_emissao'));
      $w_cpf                = f($RS,'cpf');
      $w_pais               = f($RS,'pais');
      $w_uf                 = f($RS,'uf');
      $w_cidade             = f($RS,'sq_cidade_nasc');
      $w_passaporte_numero  = f($RS,'passaporte_numero');
      $w_sq_pais_passaporte = f($RS,'sq_pais_passaporte');
      $w_sq_etnia           = f($RS,'sq_etnia');
      $w_sq_deficiencia     = f($RS,'sq_deficiencia');
      $w_sexo               = f($RS,'sexo');
      $w_sq_formacao        = f($RS,'sq_formacao');
      $O                    = 'A';
    } else {
      $w_nome = null;
      $O ='I';
    } if (Nvl($P1,0)==1 && Nvl($w_chave,'')>'') {
        $sql = new db_getGPColaborador; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
        foreach ($RS as $row) {$RS=$row; break;}
        if (!(count($RS)<=0)) {
          $w_sq_contrato_colaborador = f($RS,'sq_contrato_colaborador');
          $w_posto_trabalho          = f($RS,'sq_posto_trabalho');
          $w_modalidade_contrato     = f($RS,'sq_modalidade_contrato');
          $w_unidade_lotacao         = f($RS,'sq_unidade_lotacao');
          $w_unidade_exercicio       = f($RS,'sq_unidade_exercicio');
          $w_localizacao             = f($RS,'sq_localizacao');
          $w_matricula               = f($RS,'matricula');
          $w_dt_ini                  = FormataDataEdicao(f($RS,'inicio'));
          $w_sq_tipo_vinculo         = f($RS,'sq_tipo_vinculo');
          $w_email                   = f($RS,'email');
        } 
    } 
  } 
  Cabecalho();
  head();
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara.
  ScriptOpen('JavaScript');
  CheckBranco();
  Modulo();
  FormataData();
  SaltaCampo();
  FormataCPF();
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value == "Troca") { return true; }');
    Validate('w_nome','Nome','1',1,5,60,'1','');
    Validate('w_nome_resumido','Nome resumido','1',1,2,21,'1','');
    Validate('w_nascimento','Data de nascimento','DATA',1,10,10,'',1);
    Validate('w_sexo','Sexo','SELECT','1','1','10','1','');
    Validate('w_sq_estado_civil','Estado civil','SELECT','1','1','10','','1');
    Validate('w_sq_formacao','Formação acadêmica','SELECT','1','1','10','','1');
    Validate('w_sq_etnia','Etnia','SELECT','1','1','10','','1');
    Validate('w_foto','Foto','','','4','200','1','1');
    ShowHTML('  if (theForm.w_foto.value != \'\') {');
    ShowHTML('     if (theForm.w_foto.value.toUpperCase().indexOf(\'.JPG\') < 0 && theForm.w_foto.value.toUpperCase().indexOf(\'.GIF\') < 0) {');
    ShowHTML('        alert(\'A foto informada deve ter extensão JPG ou GIF!\');');
    ShowHTML('        theForm.w_foto.focus();');
    ShowHTML('        return false;');
    ShowHTML('     }');
    ShowHTML('  }');
    Validate('w_pais','País nascimento','SELECT',1,1,18,'','0123456789');
    Validate('w_uf','Estado nascimento','SELECT',1,1,3,'1','1');
    Validate('w_cidade','Cidade nascimento','SELECT',1,1,18,'','0123456789');
    Validate('w_rg_numero','RG','1','1','5','18','1','1');
    Validate('w_rg_emissor','Emissor','1','1','5','80','1','1');
    Validate('w_rg_emissao','Data de emissão','DATA','1','10','10','','0123456789/');
    CompData('w_nascimento','Data de nascimento','<','w_rg_emissao','Data de emissão');
    Validate('w_cpf','CPF','CPF','1','14','14','','0123456789.-');
    Validate('w_passaporte_numero','Passaporte','1','',1,40,'1','1');
    ShowHTML('  if (theForm.w_passaporte_numero.value != \'\') {');
    ShowHTML('     if (theForm.w_sq_pais_passaporte.selectedIndex == 0) {');
    ShowHTML('        alert(\'Indique o país emissor do passaporte!\');');
    ShowHTML('        theForm.w_sq_pais_passaporte.focus();');
    ShowHTML('        return false;');
    ShowHTML('     }');
    ShowHTML('  } else {');
    ShowHTML('     if (theForm.w_sq_pais_passaporte.selectedIndex != 0) {');
    ShowHTML('        theForm.w_sq_pais_passaporte.selectedIndex = 0;');
    ShowHTML('     }');
    ShowHTML('  }');
    if (Nvl($P1,0)==1 && Nvl($w_sq_contrato_colaborador,'')=='') {
      if (Nvl($w_modalidade_contrato,'')>'') {
        $sql = new db_getGPModalidade; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,$w_modalidade_contrato,null,null,null,null,null);
        foreach ($RS1 as $row){
          if (f($row,'username')=='P' || f($row,'username')=='S') {
            $w_username_pessoa = f($row,'username');
          }
        } 
      } 
      Validate('w_posto_trabalho','Cargo','SELECT',1,1,18,'','0123456789');
      Validate('w_modalidade_contrato','Modalidade de contratação','SELECT',1,1,18,'','0123456789');
      Validate('w_unidade_lotacao','Unidade de lotação','SELECT',1,1,18,'','0123456789');
      Validate('w_unidade_exercicio','Unidade de exercício','SELECT',1,1,18,'','0123456789');
      Validate('w_localizacao','Localização','SELECT',1,1,18,'','0123456789');
      Validate('w_sq_tipo_vinculo','Vínculo com a organização','SELECT',1,1,10,'','1');
      Validate('w_matricula','Matrícula','1','1','5','18','1','1');
      Validate('w_dt_ini','Início da vigência','DATA','1','10','10','','0123456789/');
      if ($w_username_pessoa=='S' || $w_username_pessoa=='P') {
        Validate('w_email','e-Mail','1','1','5','60','1','1');
      } 
    } 
    if ($_SESSION['PORTAL']=='') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
    }
    if (Nvl($P1,0)==1) {
      if (Nvl($w_sq_contrato_colaborador,'')=='') {
        ShowHTML('  theForm.Botao[0].disabled=true;');
        ShowHTML('  theForm.Botao[1].disabled=true;');
      } else {
        ShowHTML('  theForm.Botao.disabled=true;');
      } 
    } 
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca >'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } 
    if ($_SESSION['PORTAL']=='')
  {
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
  } 
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (strpos('IAEV',$O)!==false) {
    if ($w_pais=='') {
      // Carrega os valores padrão para país, estado e cidade
      $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
      $w_pais   = f($RS,'sq_pais');
      $w_uf     = f($RS,'co_uf');
      $w_cidade = f($RS,'sq_cidade_padrao');
    } 
    ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG='.$SG.'&O='.$O.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
    ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
    ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
    ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
    ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
    ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
    if ($_SESSION['PORTAL'] >'' && $O=='I') {
      ShowHTML('<INPUT type="hidden" name="R" value="'.$_SERVER['HTTP_REFERER'].'">');
    } else {
      ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
    } 
    ShowHTML(MontaFiltro('UL'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_atual" value="'.$w_foto.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border="0" width="100%">');
    ShowHTML('        <tr><td colspan=3 align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('        <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('        <tr><td colspan=3 valign="top" align="center" bgcolor="#D0D0D0"><b>Identificação</td></td></tr>');
    ShowHTML('        <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('        <tr><td colspan=3>Este bloco deve ser preenchido com dados de identificação e características pessoais.</td></tr>');
    ShowHTML('        <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td title="Informe seu nome completo, sem abreviações."><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="40" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
    ShowHTML('          <td title="Informe o nome pelo qual você prefere ser chamado ou pelo qual é mais conhecido."><b>Nome <u>r</u>esumido:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_nome_resumido" class="sti" SIZE="15" MAXLENGTH="21" VALUE="'.$w_nome_resumido.'"></td>');
    ShowHTML('          <td title="Informe a data do seu nascimento, conforme consta da carteira de identidade."><b>Data <u>n</u>ascimento:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nascimento" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_nascimento.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
    ShowHTML('        <tr valign="top">');
    SelecaoSexo('<u>S</u>exo:','S',null,$w_sexo,null,'w_sexo',null,null);
    ShowHTML('          <td colspan=2><table border="0" width="100%" cellpadding=0 cellspacing=0><tr>');
    SelecaoEstadoCivil('Estado ci<u>v</u>il:','V',null,$w_sq_estado_civil,null,'w_sq_estado_civil',null,null);
    ShowHTML('          </table>');
    ShowHTML('        <tr valign="top">');
    SelecaoFormacao('F<u>o</u>rmação acadêmica:','O','Selecione a formação acadêmica mais alta que você tem como comprovar a conclusão.',$w_sq_formacao,'Acadêmica','w_sq_formacao',null,null);
    SelecaoEtnia('E<u>t</u>nia:','T','Selecione o grupo étnico ao qual você pertence.',$w_sq_etnia,null,'w_sq_etnia',null,null);
    ShowHTML('        <tr valign="top">');
    SelecaoDeficiencia('Portador de de<u>f</u>iciência:','F','Se você for portador de algum tipo de deficiência, selecione a mais adequada.',$w_sq_deficiencia,null,'w_sq_deficiencia',null,null);
    ShowHTML('          <td colspan=2 title="Selecione o arquivo que contém sua foto. Deve ser um arquivo com a extensão JPG ou GIF, com até 50KB."><b><u>F</u>oto:</b><br><input '.$w_Disabled.' accesskey="N" type="file" name="w_foto" class="sti" SIZE="40" MAXLENGTH="200" VALUE="">&nbsp;');
    if ($w_foto>'') ShowHTML(LinkArquivo('SS',$w_cliente,$w_foto,'_blank',null,'Exibir',null)); 
    ShowHTML('        <tr><td colspan=3 align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('        <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('        <tr><td colspan=3 valign="top" align="center" bgcolor="#D0D0D0"><b>Local nascimento</td></td></tr>');
    ShowHTML('        <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('        <tr><td colspan=3>Selecione nos campos abaixo o país, o estado e a cidade de nascimento.</td></tr>');
    ShowHTML('        <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('        <tr valign="top">');
    SelecaoPais('<u>P</u>aís:','P','Selecione o país de nascimento e aguarde a tela carregar os estados.',$w_pais,null,'w_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'&SG='.$SG.'&O='.$O.'\'; document.Form.w_troca.value=\'w_uf\'; document.Form.submit();"');
    SelecaoEstado('E<u>s</u>tado:','S','Selecione o estado de nascimento e aguarde a tela carregar as cidades.',$w_uf,$w_pais,null,'w_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'&SG='.$SG.'&O='.$O.'\'; document.Form.w_troca.value=\'w_cidade\'; document.Form.submit();"');
    SelecaoCidade('<u>C</u>idade:','C','Selecine a cidade de nascimento.',$w_cidade,$w_pais,$w_uf,'w_cidade',null,null);
    ShowHTML('        <tr><td colspan=3 align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('        <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('        <tr><td colspan=3 valign="top" align="center" bgcolor="#D0D0D0"><b>Documentação</td></td></tr>');
    ShowHTML('        <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('        <tr><td colspan=3>Informe, nos campos a seguir, os dados relativos à sua documentação.</td></tr>');
    ShowHTML('        <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td title="Informe o número da sua carteira de identidade (registro geral)."><b><u>I</u>dentidade:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_rg_numero" class="sti" SIZE="15" MAXLENGTH="30" VALUE="'.$w_rg_numero.'"></td>');
    ShowHTML('          <td title="Informe o nome do órgão expedidor de sua carteira de identidade."><b><u>E</u>missor:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_rg_emissor" class="sti" SIZE="10" MAXLENGTH="15" VALUE="'.$w_rg_emissor.'"></td>');
    ShowHTML('          <td title="Informe a data de emissão de sua carteira de identidade."><b><u>D</u>ata emissão:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_rg_emissao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_rg_emissao.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
    ShowHTML('        <tr valign="top">');
    if ($O=='I') {
      ShowHTML('          <td title="Informe seu número no Cadastro de Pessoas Físicas - CPF."><b>CP<u>F</u>:</b><br><input '.$w_Disabled.' accesskey="F" type="text" name="w_cpf" class="sti" SIZE="14" MAXLENGTH="14" VALUE="'.$w_cpf.'" onKeyDown="FormataCPF(this,event);"></td>');
    } else {
    ShowHTML('          <td title="Seu CPF não pode ser alterado."><b>CP<u>F</u>:</b><br><input '.$w_Disabled.' readonly accesskey="F" type="text" name="w_cpf" class="sti" SIZE="14" MAXLENGTH="14" VALUE="'.$w_cpf.'" onKeyDown="FormataCPF(this,event);"></td>');
    } 
    ShowHTML('          <td title="Se possuir um passaporte, informe o número."><b>Número passapo<u>r</u>te:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_passaporte_numero" class="sti" SIZE="15" MAXLENGTH="15" VALUE="'.$w_passaporte_numero.'"></td>');
    SelecaoPais('<u>P</u>aís passaporte:','P','Se possuir um passaporte, selecione o país de emissão.',$w_sq_pais_passaporte,null,'w_sq_pais_passaporte',null,null);
    ShowHTML('      </table>');
    if (Nvl($P1,0)==1) {
      ShowHTML('<INPUT type="hidden" name="w_sq_contrato_colaborador" value="'.$w_sq_contrato_colaborador.'">');
      //ShowHTML('<INPUT type="hidden" name="w_usuario" value="'.$w_usuario.'">');
      if (Nvl($w_sq_contrato_colaborador,'')=='') {
        ShowHTML('        <tr><td colspan=3 align="center" height="2" bgcolor="#000000"></td></tr>');
        ShowHTML('        <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('        <tr><td colspan=3 valign="top" align="center" bgcolor="#D0D0D0"><b>Dados do contrato</td></td></tr>');
        ShowHTML('        <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('        <tr><td colspan=3>Informe, nos campos a seguir, os dados relativos ao contrato do colaborador.</td></tr>');
        ShowHTML('        <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('        <tr valign="top">');
        ShowHTML('        <td colspan="3" valign="top"><table border="0" width="100%" cellpadding=0 cellspacing=0><tr>');
        SelecaoCargo('<u>C</u>argo:','C','Selecione o cargo.',$w_posto_trabalho,null,'w_posto_trabalho',null,null);
        SelecaoModalidade('M<u>o</u>dalidade de contratação:','O',null,$w_modalidade_contrato,null,'w_modalidade_contrato',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'&SG='.$SG.'&O='.$O.'\'; document.Form.w_troca.value=\'w_modalidade_contrato\'; document.Form.submit();"');
        ShowHTML('        </table></td></tr>');
        ShowHTML('        <tr><td colspan="3" valign="top"><table border="0" width="100%" cellpadding=0 cellspacing=0><tr>');
        SelecaoUnidade('Unidade de <U>l</U>otação:','L',null,$w_unidade_lotacao,null,'w_unidade_lotacao',null,null);
        ShowHTML('        </table></td></tr>');
        ShowHTML('        <tr valign="top">');
        ShowHTML('        <td colspan="3" valign="top"><table border="0" width="100%" cellpadding=0 cellspacing=0><tr>');
        SelecaoUnidade('Unidade de <U>e</U>xercício:','E',null,$w_unidade_exercicio,null,'w_unidade_exercicio',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'&SG='.$SG.'&O='.$O.'&w_usuario='.$w_chave.'\'; document.Form.w_troca.value=\'w_localizacao\'; document.Form.submit();"');
        ShowHTML('        </table></td></tr>');
        ShowHTML('        <tr valign="top">');
        ShowHTML('        <td colspan="3" valign="top"><table border="0" width="100%" cellpadding=0 cellspacing=0><tr>');
        SelecaoLocalizacao('Locali<u>z</u>ação:','Z',null,$w_localizacao,Nvl($w_unidade_exercicio,0),'w_localizacao',null);
        ShowHTML('        </table></td></tr>');
        ShowHTML('        <td colspan="3" valign="top"><table border="0" width="100%" cellpadding=0 cellspacing=0><tr>');
        SelecaoVinculo('<u>T</u>ipo de vínculo:','T',null,$w_sq_tipo_vinculo,null,'w_sq_tipo_vinculo','S','Física','S');
        ShowHTML('        </table></td></tr>');
        ShowHTML('        <tr valign="top">');
        ShowHTML('        <td colspan="3" valign="top"><table border="0" width="100%" cellpadding=0 cellspacing=0>');
        ShowHTML('          <tr><td valign="top"><b><u>M</u>atrícula:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_matricula" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_matricula.'"></td>');
        ShowHTML('              <td><b><u>I</u>nício da vigência:</b><br><input accesskey="I" type="text" name="w_dt_ini" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_dt_ini.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">');
        ShowHTML('        </table></td></tr>');
        ShowHTML('        <tr valign="top">');
        ShowHTML('        <td colspan="3" valign="top"><input type="checkbox" name="w_envio_email" value="S"><b>Enviar e-mail comunicando a entrada do colaborador.</b>');
        if ($w_username_pessoa=='P') {
          ShowHTML('        <tr valign="top">');
          ShowHTML('        <td colspan="3" valign="top"><input type="checkbox" name="w_username_pessoa" value="S"><b>Criar username para este colaborador?</b>');
        } 
        if ($w_username_pessoa=='S' || $w_username_pessoa=='P') {
          ShowHTML('        <tr><td valign="top" colspan="3"><b><u>e</u>-Mail:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_email" class="sti" SIZE="60" MAXLENGTH="60" VALUE="'.$w_email.'"></td>');
        } 
      } 
    } 
    if ($_SESSION['PORTAL']=='') {
      ShowHTML('      <tr><td align="LEFT" colspan=3><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    }
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
    // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    if (Nvl($P1,0)==1 && Nvl($w_sq_contrato_colaborador,'')=='') {
      ShowHTML('            <INPUT class="stb" TYPE="button" NAME="Botao" VALUE="Cancelar" onClick="location.href=\''.montaURL_JS($w_dir,'colaborador.php?par=Inicial&R=colaborador.php?par=Inicial&O=P&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=COINICIAL').'\';">');
    } 
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
  ScriptOpen('JavaScript');
  ShowHTML(' alert("Opção não disponível");');
  ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina do historico pessoal
// -------------------------------------------------------------------------
function Historico() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $w_usuario;
  $w_readonly   = '';
  $w_erro       = '';
  $sql = new db_getCV; $RS = $sql->getInstanceOf($dbms,$w_cliente,nvl($w_chave,0),$SG,'DADOS');
  foreach ($RS as $row) {$RS=$row; break;}
  if (Nvl(f($RS,'inclusao'),'')=='') {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Efetue o cadastro da identificação primeiro!\');');
    ShowHTML(' location.href=\''.montaURL_JS($w_dir,'cv.php?par=Identificacao &w_usuario='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
    ScriptClose();
  } 
  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca >'') {
    // Se for recarga da página
    $w_residencia_outro_pais        = $_REQUEST['w_residencia_outro_pais'];
    $w_mudanca_nacionalidade        = $_REQUEST['w_mudanca_nacionalidade'];
    $w_mudanca_nacionalidade_medida = $_REQUEST['w_mudanca_nacionalidade_medida'];
    $w_emprego_seis_meses           = $_REQUEST['w_emprego_seis_meses'];
    $w_impedimento_viagem_aerea     = $_REQUEST['w_impedimento_viagem_aerea'];
    $w_objecao_informacoes          = $_REQUEST['w_objecao_informacoes'];
    $w_prisao_envolv_justica        = $_REQUEST['w_prisao_envolv_justica'];
    $w_motivo_prisao                = $_REQUEST['w_motivo_prisao'];
    $w_fato_relevante_vida          = $_REQUEST['w_fato_relevante_vida'];
    $w_servidor_publico             = $_REQUEST['w_servidor_publico'];
    $w_servico_publico_inicio       = $_REQUEST['w_servico_publico_inicio'];
    $w_servico_publico_fim          = $_REQUEST['w_servico_publico_fim'];
    $w_atividades_civicas           = $_REQUEST['w_atividades_civicas'];
    $w_familiar                     = $_REQUEST['w_familiar'];
  } else {
    // Recupera os dados do currículo a partir da chave
    $sql = new db_getCV; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_chave,$SG,'DADOS');
    foreach($RS as $row){$RS=$row; break;}
    if (count($RS)>0) {
      $w_residencia_outro_pais          = f($RS,'residencia_outro_pais');
      $w_mudanca_nacionalidade          = f($RS,'mudanca_nacionalidade');
      $w_mudanca_nacionalidade_medida   = f($RS,'mudanca_nacionalidade_medida');
      $w_emprego_seis_meses             = f($RS,'emprego_seis_meses');
      $w_impedimento_viagem_aerea       = f($RS,'impedimento_viagem_aerea');
      $w_objecao_informacoes            = f($RS,'objecao_informacoes');
      $w_prisao_envolv_justica          = f($RS,'prisao_envolv_justica');
      $w_motivo_prisao                  = f($RS,'motivo_prisao');
      $w_fato_relevante_vida            = f($RS,'fato_relevante_vida');
      $w_servidor_publico               = f($RS,'servidor_publico');
      $w_servico_publico_inicio         = FormataDataEdicao(f($RS,'servico_publico_inicio'));
      $w_servico_publico_fim            = FormataDataEdicao(f($RS,'servico_publico_fim'));
      $w_atividades_civicas             = f($RS,'atividades_civicas');
      $w_familiar                       = f($RS,'familiar');
      $O='A';
    } else {
      $w_nome=null;
      $O='I';
    } 
  } 
  Cabecalho();
  head();
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara.
  ScriptOpen('JavaScript');
  CheckBranco();
  Modulo();
  FormataData();
  SaltaCampo();
  FormataCPF();
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value == "Troca") { return true; }');
    ShowHTML('  if (theForm.w_mudanca_nacionalidade[0].checked) {');
    Validate('w_mudanca_nacionalidade_medida','Medida','1','1','4','255','1','1');
    ShowHTML('  }');
    ShowHTML('  else theForm.w_mudanca_nacionalidade.value = \'\';');
    ShowHTML('  if (theForm.w_prisao_envolv_justica[0].checked) {');
    Validate('w_motivo_prisao','Motivo','1','1','5','255','1','1');
    ShowHTML('  }');
    ShowHTML('  else theForm.w_motivo_prisao.value = \'\';');
    Validate('w_fato_relevante_vida','Fatos relevantes','1','',5,255,'1','1');
    ShowHTML('  if (theForm.w_servidor_publico[0].checked) {');
    Validate('w_servico_publico_inicio','Entrada','DATA','1','10','10','','0123456789/');
    Validate('w_servico_publico_fim','Saída','DATA','','10','10','','0123456789/');
    ShowHTML('  }');
    ShowHTML('  else {');
    ShowHTML('    theForm.w_servico_publico_inicio.value = \'\';');
    ShowHTML('    theForm.w_servico_publico_fim.value = \'\';');
    ShowHTML('  }');
    Validate('w_atividades_civicas','Atividades cívicas','1','',5,255,'1','1');
    if ($_SESSION['PORTAL']=='') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
    }     
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (strpos('IAEV',$O)!==false) {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_usuario.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr>');
    MontaRadioNS('<b>Você já fixou residência permanente legal em país estrangeiro?</b>',$w_residencia_outro_pais,'w_residencia_outro_pais');
    ShowHTML('      <tr>');
    MontaRadioNS('<b>Você já tomou alguma medida para mudar de nacionalidade?</b>',$w_mudanca_nacionalidade,'w_mudanca_nacionalidade');
    ShowHTML('      <tr><td><textarea '.$w_Disabled.' name="w_mudanca_nacionalidade_medida" class="sti" rows=3 cols=90>'.$w_mudanca_nacionalidade_medida.'</textarea>');
    ShowHTML('      <tr>');
    MontaRadioSN('<b>Você aceitaria um emprego por menos de 6 meses?</b>',$w_emprego_seis_meses,'w_emprego_seis_meses');
    ShowHTML('      <tr>');
    MontaRadioNS('<b>Você possui algum impedimento para efetuar viagens aéreas?</b>',$w_impedimento_viagem_aerea,'w_impedimento_viagem_aerea');
    ShowHTML('      <tr>');
    MontaRadioSN('<b>Você tem algum parente trabalhando nesta organização?</b>',$w_familiar,'w_familiar');
    ShowHTML('      <tr>');
    MontaRadioNS('<b>Você tem alguma objeção a fazer com relação à solicitação de informações a seu respeito para seu último empregador?</b>',$w_objecao_informacoes,'w_objecao_informacoes');
    ShowHTML('      <tr>');
    MontaRadioSN('<b>Você alguma vez já foi preso, acusado ou convocado pela Corte como réu em algum processo criminal ou sentenciado, penalizado ou aprisionado por violação de alguma lei? (excluem-se violações menores de trânsito)</b>',$w_prisao_envolv_justica,'w_prisao_envolv_justica');
    ShowHTML('      <tr><td><textarea '.$w_Disabled.' name="w_motivo_prisao" class="sti" rows=3 cols=90>'.$w_motivo_prisao.'</textarea>');
    ShowHTML('      <tr><td valign="top"><b>Exponha algum outro fato relevante. Inclua informações relacionadas a qualquer residência fora do país de origem:</b><br>');
    ShowHTML('              <textarea '.$w_Disabled.' name="w_fato_relevante_vida" class="sti" rows=3 cols=90>'.$w_fato_relevante_vida.'</textarea>');
    ShowHTML('      <tr>');
    MontaRadioSN('<b>Você é ou foi Funcionário Público?</b>',$w_servidor_publico,'w_servidor_publico');
    ShowHTML('              <tr> <td> de <input '.$w_Disabled.' type="text" name="w_servico_publico_inicio" class="sti" SIZE=10 MAXLENGTH=10 VALUE="'.$w_servico_publico_inicio.'" onKeyDown="FormataData(this, event)" onKeyUp="SaltaCampo(this.form.name,this,10,event);">');
    ShowHTML('              a <input '.$w_Disabled.' type="text" name="w_servico_publico_fim" class="sti" SIZE=10 MAXLENGTH=10 VALUE="'.$w_servico_publico_fim.'" onKeyDown="FormataData(this, event)" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> (dd/mm/aaaa)');
    ShowHTML('      <tr><td valign="top"><b>Informe alguma sociedade profissional ou atividades ligadas a assuntos cívicos, públicos ou internacionais das quais você faz parte:</b><br>');
    ShowHTML('              <textarea '.$w_Disabled.' name="w_atividades_civicas" class="sti" rows=3 cols=90>'.$w_atividades_civicas.'</textarea>');
    if ($_SESSION['PORTAL']=='') {
      ShowHTML('      <tr><td align="LEFT" colspan=3><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>'); 
    }
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
    // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de idiomas
// -------------------------------------------------------------------------
function Idiomas() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave  = $_REQUEST['w_chave'];
  if (Nvl($P1,0)!=1) {
    $sql = new db_getCV; $RS = $sql->getInstanceOf($dbms,$w_cliente,nvl($w_usuario,0),$SG,'DADOS');
    foreach ($RS as $row) {$RS=$row; break;}
    if (Nvl(f($RS,'inclusao'),'')=='') {
      ScriptOpen('JavaScript');
      ShowHTML('alert(\'Efetue o cadastro da identificação primeiro!\');');
      ShowHTML('location.href=\''.montaURL_JS($w_dir,'cv.php?par=Identificacao &w_usuario='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } 
  } 
  if ($w_troca >'') {
    // Se for recarga da página
    $w_leitura      = $_REQUEST['w_leitura'];
    $w_escrita      = $_REQUEST['w_escrita'];
    $w_compreensao  = $_REQUEST['w_compreensao'];
    $w_conversacao  = $_REQUEST['w_conversacao'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getCVIdioma; $RS = $sql->getInstanceOf($dbms,$w_usuario,null);
    $RS = SortArray($RS,'nome','asc');
  } elseif (strpos('AEV',$O)!==false && $w_troca=='') {
    // Recupera os dados do registro informado
    $sql = new db_getCVIdioma; $RS = $sql->getInstanceOf($dbms,$w_usuario,$w_chave);
    foreach ($RS as $row) {$RS=$row; break;}
    $w_nm_idioma    = f($RS,'nome');
    $w_chave        = f($RS,'sq_idioma');
    $w_leitura      = f($RS,'leitura');
    $w_escrita      = f($RS,'escrita');
    $w_compreensao  = f($RS,'compreensao');
    $w_conversacao  = f($RS,'conversacao');
  } 
  Cabecalho();
  head();
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_chave','Idioma','SELECT','1','1','10','','1');
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
    } elseif ($O=='E' && $_SESSION['PORTAL']=='') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
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
  } elseif ($O=='I') {
    BodyOpen('onLoad=\'document.Form.w_chave.focus()\';');
  } elseif ($O=='E' && $_SESSION['PORTAL']==''){
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&w_usuario='.$w_usuario.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Idioma</td>');
    ShowHTML('          <td><b>Leitura</td>');
    ShowHTML('          <td><b>Escrita</td>');
    ShowHTML('          <td><b>Conversação</td>');
    ShowHTML('          <td><b>Compreensão</td>');
    ShowHTML('          <td class="remover"><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor; 
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_leitura').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_escrita').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_conversacao').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_compreensao').'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_idioma').'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_idioma').'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) {
      $w_Disabled=' DISABLED ';
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_usuario" value="'.$w_usuario.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    if ($O=='I') {
      ShowHTML('      <tr>');
      SelecaoIdioma('I<u>d</u>ioma:','D','Selecione o idioma que você deseja informar os dados.',$w_chave,null,'w_chave',null,null);
    } else {
      ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
      ShowHTML('      <tr><td valign="top">Idioma:</b><br><b>'.$w_nm_idioma);
    } 
    ShowHTML('      <tr>');
    MontaRadioNS('<b>Você lê com facilidade textos escritos no idioma selecionado acima?</b>',$w_leitura,'w_leitura');
    ShowHTML('      <tr>');
    MontaRadioNS('<b>Você escreve textos com facilidade no idioma selecionado acima?</b>',$w_escrita,'w_escrita');
    ShowHTML('      <tr>');
    MontaRadioNS('<b>Você compreende com facilidade pessoas conversando no idioma selecionado acima?</b>',$w_compreensao,'w_compreensao');
    ShowHTML('      <tr>');
    MontaRadioNS('<b>Você conversa fluentemente no idioma selecionado acima?</b>',$w_conversacao,'w_conversacao');
    ShowHTML('          </table>');
    if ($_SESSION['PORTAL']=='') {
      ShowHTML('      <tr><td align="LEFT"><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    } 
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de experiencia profissional
// -------------------------------------------------------------------------
function Experiencia() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];
  if (Nvl($P1,0)!=1) {
    $sql = new db_getCV; $RS = $sql->getInstanceOf($dbms,$w_cliente,nvl($w_usuario,0),$SG,'DADOS');
    foreach ($RS as $row) {$RS=$row; break;}
    if (Nvl(f($row,'inclusao'),'')=='') {
      ScriptOpen('JavaScript');
      ShowHTML('alert(\'Efetue o cadastro da identificação primeiro!\');');
      ShowHTML('location.href=\''.montaURL_JS($w_dir,'cv.php?par=Identificacao&w_usuario='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } 
  } 
  if ($w_troca>'' && $O!='E')  {
    // Se for recarga da página
    $w_sq_area_conhecimento   = $_REQUEST['w_sq_area_conhecimento'];
    $w_nm_area                = $_REQUEST['w_nm_area'];
    $w_sq_pais                = $_REQUEST['w_sq_pais'];
    $w_co_uf                  = $_REQUEST['w_co_uf'];
    $w_sq_cidade              = $_REQUEST['w_sq_cidade'];
    $w_sq_eo_tipo_posto       = $_REQUEST['w_sq_eo_tipo_posto'];
    $w_sq_tipo_vinculo        = $_REQUEST['w_sq_tipo_vinculo'];
    $w_atividades             = $_REQUEST['w_atividades'];
    $w_empregador             = $_REQUEST['w_empregador'];
    $w_entrada                = $_REQUEST['w_entrada'];
    $w_saida                  = $_REQUEST['w_saida'];
    $w_duracao_mes            = $_REQUEST['w_duracao_mes'];
    $w_duracao_ano            = $_REQUEST['w_duracao_ano'];
    $w_motivo_saida           = $_REQUEST['w_motivo_saida'];
    $w_ultimo_salario         = $_REQUEST['w_ultimo_salario'];
  } if ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$w_usuario,null,'EXPERIENCIA');
    $RS = SortArray($RS,'saida','asc','entrada','desc');
  } elseif (strpos('AEV',$O)!==false && $w_troca=='') {
    $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$w_usuario,$w_chave,'EXPERIENCIA');
    foreach ($RS as $row) {$RS=$row;    break;}
    $w_sq_area_conhecimento = f($RS,'sq_area_conhecimento');
    if (Nvl(f($RS,'nm_area'),'')=='') {
      $w_nm_area = '';
    } else {
      $w_nm_area = f($RS,'nm_area').' ('.f($RS,'codigo_cnpq').')';
    }
    $w_sq_pais            = f($RS,'sq_pais');
    $w_co_uf              = f($RS,'co_uf');
    $w_sq_cidade          = f($RS,'sq_cidade');
    $w_sq_eo_tipo_posto   = f($RS,'sq_eo_tipo_posto');
    $w_sq_tipo_vinculo    = f($RS,'sq_tipo_vinculo');
    $w_empregador         = f($RS,'empregador');
    $w_atividades         = f($RS,'atividades');
    $w_entrada            = FormataDataEdicao(f($RS,'entrada'));
    $w_saida              = FormataDataEdicao(f($RS,'saida'));
    $w_duracao_mes        = f($RS,'duracao_mes');
    $w_duracao_ano        = f($RS,'duracao_ano');
    $w_motivo_saida       = f($RS,'motivo_saida');
    $w_ultimo_salario     = number_format(Nvl(f($RS,'ultimo_salario'),0),2,',','.');
  } 
  Cabecalho();
  head();
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    formatadata();
    SaltaCampo();
    FormataValor();
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_empregador','Empregador','1','1','1','60','1','1');
      Validate('w_nm_area','Área do conhecimento','','1','1','92','1','1');
      Validate('w_entrada','Data entrada','DATA','1','10','10','','1');      
      Validate('w_saida','Data saída','DATA','','10','10','','1');
      //Validate('w_ultimo_salario','Último salário mensal','VALOR','1','4','15','','0123456789,.');
      CompData('w_entrada','Data entrada','<','w_saida','Data saída');
      ShowHTML('  if (theForm.w_saida.value != \'\' && theForm.w_motivo_saida.value == \'\') {');
      ShowHTML('     alert(\'Informe o motivo da saída!\');');
      ShowHTML('     theForm.w_motivo_saida.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      Validate('w_motivo_saida','Motivo saída','1','','1','255','1','1');
      Validate('w_sq_pais','Pais','SELECT','1','1','10','','1');
      Validate('w_co_uf','Estado','SELECT','1','1','10','1','');
      Validate('w_sq_cidade','Cidade','SELECT','1','1','10','','1');
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  if (theForm.w_sq_eo_tipo_posto.value==undefined) {');
      ShowHTML('     for (i=0; i < theForm.w_sq_eo_tipo_posto.length; i++) {');
      ShowHTML('       if (theForm.w_sq_eo_tipo_posto[i].checked) w_erro=false;');
      ShowHTML('     }');
      ShowHTML('  }');
      ShowHTML('  else {');
      ShowHTML('     if (theForm.w_sq_eo_tipo_posto.checked) w_erro=false;');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert(\'Informe a principal atividade desempenhada!\'); ');
      ShowHTML('    return false;');
      ShowHTML('  }');
      Validate('w_atividades','Atividades desempenhadas','','1','4','4000','1','1');
      if ($_SESSION['PORTAL']=='') {
        Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1'); 
      }
    } elseif ($O=='E') {
      if ($_SESSION['PORTAL']=='') {
        Validate ('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
      }
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($O=='L') {
    BodyOpen(null);
  } elseif ($w_troca >'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_empregador.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem        
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_sq_cvpessoa='.$w_sq_cvpessoa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Área</td>');
    ShowHTML('          <td><b>Empregador</td>');
    ShowHTML('          <td><b>Entrada</td>');
    ShowHTML('          <td><b>Saída</td>');
    ShowHTML('          <td class="remover"><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>Não foram encontradas experiências profissionais cadastradas.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row){
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;       
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nm_area').'</td>');
        ShowHTML('        <td>'.f($row,'empregador').'</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'entrada')).'</td>');
        ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($row,'saida')),'---').'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_cvpessoa='.$w_sq_cvpessoa.'&w_chave='.f($row,'sq_cvpesexp').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_sq_cvpessoa='.$w_sq_cvpessoa.'&w_chave='.f($row,'sq_cvpesexp').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" onClick="return confirm(\'Confirma a exclusão do emprego?\');">EX</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'CARGOS&R='.$w_pagina.'CARGOS&O=L&w_sq_cvpessoa='.$w_sq_cvpessoa.'&w_sq_cvpesexp='.f($row,'sq_cvpesexp').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Cargos&SG=CVCARGOS'.MontaFiltro('GET').'\',\'Cargos').'\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\');" title="Clique aqui para inserir os cargos que ocupou nesse emprego.">Cargos</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');     
    ShowHTML('<tr><td colspan=3><br><b>Instruções:</b>');
    ShowHTML('   <ul>');
    ShowHTML('   <li>A finalidade desta tela é registrar toda a sua experiência profissional;');
    ShowHTML('   <li>Para cada experiência profissional, informe os cargos que desempenhou na organização;');
    ShowHTML('   <li>Indique sempre a que área do conhecimento a experiência está vinculada (Ex: contabilidade, administração etc);');
    ShowHTML('   <li>Se a área do conhecimento ou o cargo desempenhado não forem localizados, busque por um nome mais abrangente ou entre em contato com o gestor do sistema.');
    ShowHTML('   </ul>');
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) {
      $w_Disabled=' DISABLED ';
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this))',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="'.$w_troca.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_cvpessoa" value="'.$w_sq_cvpessoa.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_area_conhecimento" value="'.$w_sq_area_conhecimento.'">');
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td valign="top"><b><u>E</u>mpregador:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_empregador" class="sti" SIZE="60" MAXLENGTH="60" VALUE="'.$w_empregador.'"></td>');
    ShowHTML('      <tr><td valign="top"><b>Área do conhecimento relacionada:</b><br>');
    ShowHTML('              <input READONLY type="text" name="w_nm_area" class="sti" SIZE="50" VALUE="'.$w_nm_area.'">');
    if ($O!='E') {
      ShowHTML('              [<u onMouseOver="this.style.cursor=\'Hand\'" onMouseOut="this.style.cursor=\'Pointer\'" onClick="window.open(\''.montaURL_JS($w_dir,$w_pagina.'BuscaAreaConhecimento&TP='.$TP.'&SG='.$SG.'&P1=1').'\',\'AreaConhecimento\',\'top=70,left=100,width=600,height=400,toolbar=yes,status=yes,resizable=yes,scrollbars=yes\');"><b><font color="#0000FF">Procurar</font></b></u>]');
    } 
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('          <tr><td valign="top"><b>E<U>n</U>trada:</b></br><INPUT ACCESSKEY="n" '.$w_Disabled.' class="sti" type="text" name="w_entrada" size="10" maxlength="10" value="'.$w_entrada.'" onKeyDown="FormataData(this, event)" onKeyUp="SaltaCampo(this.form.name,this,10,event);">');
    ShowHTML('              <td valign="top"><b><U>S</U>aída:</b></br><INPUT ACCESSKEY="S" '.$w_Disabled.' class="sti" type="text" name="w_saida" size="10" maxlength="10" value="'.$w_saida.'" onKeyDown="FormataData(this, event)" onKeyUp="SaltaCampo(this.form.name,this,10,event);">');
    ShowHTML('              <td valign="top"><br><input '.$w_Disabled.' accesskey="U" type="hidden" name="w_ultimo_salario" class="sti" SIZE="15" MAXLENGTH="15" VALUE="0.00" style="text-align:right;" onKeyDown="FormataValor(this,14,2, event)"></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td valign="top"><b>Mo<u>t</u>ivo saída:</b><br><textarea '.$w_Disabled.' accesskey="t"  name="w_motivo_saida" class="sti" cols="80" rows="4">'.$w_motivo_saida.'</textarea></td>');
    ShowHTML('      <tr valign="top"><td colspan="2">');
    ShowHTML('         <table border=0 width="100%" cellspacing=0>');
    ShowHTML('           <tr valign="top">');
    SelecaoPais('<u>P</u>aís:','P','Selecione o país da experiência profissional e aguarde a tela carregar os estados.',$w_sq_pais,null,'w_sq_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_co_uf\'; document.Form.submit();"');
    SelecaoEstado('E<u>s</u>tado:','S','Selecione o estado da experiência profissional e aguarde a tela carregar as cidades.',$w_co_uf,$w_sq_pais,null,'w_co_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_cidade\'; document.Form.submit();"');
    SelecaoCidade('<u>C</u>idade:','C','Selecine a cidade de nascimento.',$w_sq_cidade,$w_sq_pais,$w_co_uf,'w_sq_cidade',null,null);
    ShowHTML('         </table></td></tr>');
    ShowHTML('      <tr valign="top">');
    SelecaoTipoPosto('Informe a principal atividade desempenhada:','T',null,$w_sq_eo_tipo_posto,null,'w_sq_eo_tipo_posto','S');
    ShowHTML('      <tr><td valign="top"><b>At<u>i</u>vidades desempenhadas:</b><br><textarea '.$w_Disabled.' accesskey="i"  name="w_atividades" class="sti" cols="80" rows="4">'.$w_atividades.'</textarea></td>');
    if ($_SESSION['PORTAL']=='') {
      ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    }
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
    // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML('      <tr><td align="center" colspan="3">');
    if ($O=='E') {
      ShowHTML('            <input class="sti" type="submit" name="Botao" value="Excluir">');
      ShowHTML('            <input class="sti" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="sti" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="sti" type="submit" name="Botao" value="Atualizar">');
      }  
    } 
    ShowHTML('            <input class="sti" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L'.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</table>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de cargos
// -------------------------------------------------------------------------
function Cargos() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_sq_cvpescargo  = $_REQUEST['w_sq_cvpescargo'];
  $w_sq_cvpesexp    = $_REQUEST['w_sq_cvpesexp'];
  if (Nvl($P1,0)!=1) {
    $sql = new db_getCV; $RS = $sql->getInstanceOf($dbms,$w_cliente,nvl($w_usuario,0),$SG,'DADOS');
    foreach($RS as $row) { $RS = $row; break; }
    if (Nvl(f($RS,'inclusao'),'')=='') {
      ScriptOpen('JavaScript');
      ShowHTML('alert(\'Efetue o cadastro da identificação primeiro!\');');
      ShowHTML('location.href=\''.montaURL_JS($w_dir,'cv.php?par=Identificacao&w_usuario='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();   
    }
  } 
  $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$w_usuario,$w_sq_cvpesexp,'EXPERIENCIA');
  foreach($RS as $row) { $RS = $row; break; }
  $w_nome_empregador = f($RS,'empregador'); 
  if ($O=='L') {
    $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$w_sq_cvpesexp,null,'CARGO');
  } elseif (strpos('AEV',$O)!==false) {
    // Recupera o conjunto de informações comum a todos os serviços
    $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$w_sq_cvpesexp,$w_sq_cvpescargo,'CARGO');
    foreach($RS as $row) { $RS = $row; break; }
    $w_sq_area_conhecimento = f($RS,'sq_area_conhecimento');
    $w_nm_area              = f($RS,'nm_area');
    $w_especialidades       = f($RS,'especialidades');
    $w_inicio               = FormataDataEdicao(f($RS,'inicio'));
    $w_fim                  = FormataDataEdicao(f($RS,'fim'));
  } 
  Cabecalho();
  head();
  ShowHTML('<title>Cargos de uma experiência profissional</title>');
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    formataData();
    SaltaCampo();
    FormataValor();
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_sq_area_conhecimento','Área do conhecimento','SELECT','1','1','10','','1');
      Validate('w_especialidades','Especialidades','1','1','1','255','QWERTYUIOPASDFGHJKLZXCVBNM; ','1');
      ShowHTML(' if (document.Form.w_especialidades.value.indexOf(\';\')==-1){');
      ShowHTML('   alert(\'Digite apenas palavras maisculas não acentuadas e separados por ponto-virgula.\'); ');
      ShowHTML('   document.Form.w_especialidades.focus();');
      ShowHTML('   return (false);');
      ShowHTML(' }');
      Validate('w_inicio','Início','Data','1','10','10','','1');
      Validate('w_fim','Fim','Data','','10','10','','1');
      Validate('w_nm_area','Área do conhecimento','','1','1','92','1','1');
      if ($_SESSION['PORTAL']=='') {
        Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1'); 
      }
    } elseif ($O=='E') {
      if ($_SESSION['PORTAL']=='') {
        Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
      }
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if (strpos('IA',$O)!==false) {
    BodyOpen('onLoad=\'document.Form.w_especialidades.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td>Empregador:<b> '.$w_nome_empregador.'</b><br>&nbsp;');
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&w_sq_cvpesexp= '.$w_sq_cvpesexp.'&R='.$w_pagina.$par.'&O=I&w_sq_cvpessoa='.$w_sq_cvpessoa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <a accesskey="F" class="SS" href="javascript:opener.focus();javascript:window.close();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Cargo</td>');
    ShowHTML('          <td><b>Início</td>');
    ShowHTML('          <td><b>Fim</td>');
    ShowHTML('          <td class="remover"><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>Não foram encontrados cargos cadastrados.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nm_area').'</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'inicio')).'</td>');
        ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($row,'fim')),'---').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_cvpesexp='.f($row,'sq_cvpesexp').'&w_sq_cvpescargo='.f($row,'sq_cvpescargo').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_sq_cvpesexp='.f($row,'sq_cvpesexp').'&w_sq_cvpescargo='.f($row,'sq_cvpescargo').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" onClick="return confirm(\'Confirma a exclusão do cargo?\');">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this))',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_sq_cvpessoa" value="'.$w_sq_cvpessoa.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_cvpescargo" value="'.$w_sq_cvpescargo.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_cvpesexp" value="'.$w_sq_cvpesexp.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_area_conhecimento" value="'.$w_sq_area_conhecimento.'">');
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td valign="top">Empregador:<br><b>'.$w_nome_empregador.'</b></td>');
    ShowHTML('      <tr><td><b><u>E</u>specialidades(Digite apenas palavras maisculas não acentuadas e separados por ponto-virgula.):</b><br><textarea '.$w_Disabled.' accesskey="N" name="w_especialidades" class="sti" SIZE="255" MAXLENGTH="255" COLS = "90" ROWS="5">'.$w_especialidades.'</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    ShowHTML('            <td valign="top"><b><u>I</u>nício:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
    ShowHTML('            <td valign="top"><b><u>F</u>im:</b><br><input '.$w_Disabled.' accesskey="F" type="text" name="w_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td valign="top"><b>Cargo desempenhado:</b><br>');
    ShowHTML('          <input READONLY type="text" name="w_nm_area" class="sti" SIZE="50" VALUE="'.$w_nm_area.'">');
    ShowHTML('          [<u onMouseOver="this.style.cursor=\'Hand\'" onMouseOut="this.style.cursor=\'Pointer\'" onClick="window.open(\''.montaURL_JS($w_dir,$w_pagina.'BuscaAreaConhecimento&TP='.$TP.'&P1=2').'\',\'SelecaoCargo\',\'top=70,left=100,width=600,height=400,toolbar=yes,status=yes,resizable=yes,scrollbars=yes\');"><b><font color="#0000FF">Procurar</font></b></u>]');
    // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação    
    if ($_SESSION['PORTAL']=='') {
      ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>'); 
    } ShowHTML('      <tr><td align="center" colspan=4><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="sti" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="sti" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="sti" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="sti" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_sq_cvpesexp= '.$w_sq_cvpesexp.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L'.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de formação acadêmica
// -------------------------------------------------------------------------
function Escolaridade() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];
  if (Nvl($P1,0)!=1) {
    $sql = new db_getCV; $RS = $sql->getInstanceOf($dbms,$w_cliente,nvl($w_usuario,0),$SG,'DADOS');
    foreach ($RS as $row) {$RS=$row; break;}
    if (Nvl(f($RS,'inclusao'),'')=='') {
      ScriptOpen('JavaScript');
      ShowHTML('alert(\'Efetue o cadastro da identificação primeiro!\');');
      ShowHTML('location.href=\''.montaURL_JS($w_dir,'cv.php?par=Identificacao&w_usuario='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } 
  }   
  if ($w_troca>'' && $O!='E')  {
    // Se for recarga da página
    $w_sq_area_conhecimento = $_REQUEST['w_sq_area_conhecimento'];
    $w_sq_pais              = $_REQUEST['w_sq_pais'];
    $w_sq_formacao          = $_REQUEST['w_sq_formacao'];
    $w_nome                 = $_REQUEST['w_nome'];
    $w_nm_area              = $_REQUEST['w_nm_area'];
    $w_instituicao          = $_REQUEST['w_instituicao'];
    $w_inicio               = $_REQUEST['w_inicio'];
    $w_fim                  = $_REQUEST['w_fim'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$w_usuario,null,'ACADEMICA');
    $RS = SortArray($RS,'ordem','desc','inicio','desc');
  } elseif (strpos('AEV',$O)!==false && $w_troca=='') {
    // Recupera os dados do endereço informado
    $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$w_usuario,$w_chave,'ACADEMICA');
    foreach ($RS as $row) {$RS=$row; break;}
    $w_sq_area_conhecimento = f($RS,'sq_area_conhecimento');
    if (Nvl(f($RS,'nm_area'),'')=='') {
      $w_nm_area = '';
    } else {
      $w_nm_area = f($RS,'nm_area').' ('.f($RS,'codigo_cnpq').')';
    } 
    $w_sq_pais     = f($RS,'sq_pais');
    $w_sq_formacao = f($RS,'sq_formacao');
    $w_nome        = f($RS,'nome');
    $w_instituicao = f($RS,'instituicao');
    $w_inicio      = f($RS,'inicio');
    $w_fim         = f($RS,'fim');
  } 
  Cabecalho();
  head();
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    checkbranco();
    SaltaCampo();
    formatadatama();
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_sq_formacao','Formação','SELECT','1','1','10','','1');
      ShowHTML('  if (theForm.w_sq_formacao.selectedIndex > 3 && (theForm.w_sq_area_conhecimento.value==\'\' || theForm.w_nome.value==\'\')) { ');
      ShowHTML('     alert(\'Se formação acadêmica for graduação ou acima, informe a área do conhecimento e o nome do curso\'); ');
      ShowHTML('     return false; ');
      ShowHTML('  } ');
      Validate('w_nm_area','Área do conhecimento','','','1','92','1','1');
      Validate('w_nome','Nome','1','','3','80','1','1');
      Validate('w_instituicao','Instituição','1','1','1','100','1','1');
      Validate('w_inicio','Início','DATAMA','1','7','7','','0123456789/');
      Validate('w_fim','Fim','DATAMA','','7','7','','0123456789/');
      Validate('w_sq_pais','País conclusão','SELECT','1','1','10','','1');
      if ($_SESSION['PORTAL']=='') {
        Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1'); 
      }
    } elseif ($O=='E' && $_SESSION['PORTAL']=='') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca >'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='I') {
    BodyOpen('onLoad=\'document.Form.w_sq_formacao.focus()\';');
  } elseif ($O=='A') {
    BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif ($O=='E' && $_SESSION['PORTAL']=='') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }  
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('      <tr><td colspan=3 bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">');
    ShowHTML('        ATENÇÃO:<ul>');
    ShowHTML('        <li>A cada alteração ou adição de novos cursos, os certificados, diplomas, etc. devem ser encaminhados ao departamento de Recursos Humanos.');
    ShowHTML('        </ul></b></font></td>');
    ShowHTML('      </tr>');  
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Nível</td>');
    ShowHTML('          <td><b>Área</td>');
    ShowHTML('          <td><b>Curso</td>');
    ShowHTML('          <td><b>Início</td>');
    ShowHTML('          <td><b>Término</td>');
    ShowHTML('          <td class="remover"><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;       
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nm_formacao').'</td>');
        ShowHTML('        <td>'.Nvl(f($row,'nm_area'),'---').'</td>');
        ShowHTML('        <td>'.Nvl(f($row,'nome'),'---').'</td>');
        ShowHTML('        <td align="center">'.f($row,'inicio').'</td>');
        ShowHTML('        <td align="center">'.Nvl(f($row,'fim'),'---').'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_cvpessoa_escol').'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_cvpessoa_escol').'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) {
      $w_Disabled = ' DISABLED ';
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_usuario" value="'.$w_usuario.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sq_area_conhecimento" value="'.$w_sq_area_conhecimento.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr>');
    SelecaoFormacao('F<u>o</u>rmação acadêmica:','O','Selecione a formação acadêmica que você deseja informar os dados.',$w_sq_formacao,'Acadêmica','w_sq_formacao',null,null);
    ShowHTML('      <tr><td valign="top"><b>Se formação for graduação ou maior, indique a área do conhecimento:</b><br>');
    ShowHTML('              <input READONLY type="text" name="w_nm_area" class="sti" SIZE="50" VALUE="'.$w_nm_area.'">');
    if ($O!='E') {
      ShowHTML('              [<u onMouseOver="this.style.cursor=\'Hand\'" onMouseOut="this.style.cursor=\'Pointer\'" onClick="window.open(\''.montaURL_JS($w_dir,$w_pagina.'BuscaAreaConhecimento&TP='.$TP.'&SG='.$SG.'&P1=1').'\',\'AreaConhecimento\',\'top=70,left=100,width=600,height=400,toolbar=yes,status=yes,resizable=yes,scrollbars=yes\');"><b><font color="#0000FF">Procurar</font></b></u>]');
    } 
    ShowHTML('      <tr><td valign="top"><b><u>N</u>ome curso:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="80" MAXLENGTH="80" VALUE="'.$w_nome.'"></td>');
    ShowHTML('      <tr><td valign="top"><b><u>I</u>nstituição:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_instituicao" class="sti" SIZE="80" MAXLENGTH="100" VALUE="'.$w_instituicao.'"></td>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('          <tr><td valign="top"><b>Íni<u>c</u>io: (mm/aaaa)</b><br><input '.$w_Disabled.' accesskey="c" type="text" name="w_inicio" class="sti" SIZE="7" MAXLENGTH="7" VALUE="'.$w_inicio.'" onKeyDown="FormataDataMA(this,event);" onKeyUp="SaltaCampo(this.form.name,this,7,event);">'.ExibeCalendario('Form','w_inicio').'</td>');
    ShowHTML('              <td valign="top"><b>Fi<u>m</u>: (mm/aaaa)</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_fim" class="sti" SIZE="7" MAXLENGTH="7" VALUE="'.$w_fim.'" onKeyDown="FormataDataMA(this,event);" onKeyUp="SaltaCampo(this.form.name,this,7,event);">'.ExibeCalendario('Form','w_fim').'</td>');
    SelecaoPais('<u>P</u>aís de conclusão:','P','Selecione o país onde concluiu esta formação.',Nvl($w_sq_pais,2),null,'w_sq_pais',null,null);
    ShowHTML('          </table>');
    if ($_SESSION['PORTAL']=='') {
      ShowHTML('      <tr><td align="LEFT"><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>'); 
    }
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      }  
    }  
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de cursos técnicos
// -------------------------------------------------------------------------
function Extensao() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];
  if (Nvl($P1,0)!=1) {
    $sql = new db_getCV; $RS = $sql->getInstanceOf($dbms,$w_cliente,nvl($w_usuario,0),$SG,'DADOS');
    foreach ($RS as $row) {$RS=$row; break;}
    if (Nvl(f($row,'inclusao'),'')=='') {
      ScriptOpen('JavaScript');
      ShowHTML('alert(\'Efetue o cadastro da identificação primeiro!\');');
      ShowHTML('location.href=\''.montaURL_JS($w_dir,'cv.php?par=Identificacao&w_usuario='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } 
  } 
  if ($w_troca>'' && $O!='E')  {
    // Se for recarga da página
    $w_sq_area_conhecimento = $_REQUEST['w_sq_area_conhecimento'];
    $w_sq_formacao          = $_REQUEST['w_sq_formacao'];
    $w_nome                 = $_REQUEST['w_nome'];
    $w_nm_area              = $_REQUEST['w_nm_area'];    
    $w_instituicao          = $_REQUEST['w_instituicao'];
    $w_carga_horaria        = $_REQUEST['w_carga_horaria'];
    $w_conclusao            = $_REQUEST['w_conclusao'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$w_usuario,null,'CURSO');
    $RS = SortArray($RS,'ordem','desc','carga_horaria','desc');
  } elseif (strpos('AEV',$O)!==false && $w_troca=='') {
    // Recupera os dados do endereço informado
    $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$w_usuario,$w_chave,'CURSO');
    foreach ($RS as $row) {$RS=$row; break;}
    $w_sq_area_conhecimento = f($RS,'sq_area_conhecimento');
    $w_nm_area              = f($RS,'nm_area').' ('.f($RS,'codigo_cnpq').')';
    $w_sq_formacao          = f($RS,'sq_formacao');
    $w_nome                 = f($RS,'nome');
    $w_instituicao          = f($RS,'instituicao');
    $w_carga_horaria        = f($RS,'carga_horaria');
    $w_conclusao            = FormataDataEdicao(f($RS,'conclusao'));
  } 
  Cabecalho();
  head();
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    checkbranco();
    formatadata();
    SaltaCampo();
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_sq_formacao','Tipo de extensão','SELECT','1','1','10','','1');
      Validate('w_nm_area','Área do conhecimento','','1','1','92','1','1');
      Validate('w_nome','Nome','1','1','5','80','1','1');
      Validate('w_instituicao','Instituição','1','1','1','100','1','1');
      Validate('w_carga_horaria','Carga horária','','1','2','4','','0123456789');
      Validate('w_conclusao','conclusao','DATA','','10','10','','0123456789/');
      if ($_SESSION['PORTAL']=='') {
        Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1'); 
      }
    } elseif ($O=='E' && $_SESSION['PORTAL']=='') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca >'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='I') {
    BodyOpen('onLoad=\'document.Form.w_sq_formacao.focus()\';');
  } elseif ($O=='A') {
    BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif ($O=='E' && $_SESSION['PORTAL']=='') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Nível</td>');
    ShowHTML('          <td><b>Área</td>');
    ShowHTML('          <td><b>Curso</td>');
    ShowHTML('          <td><b>C.H.</td>');
    ShowHTML('          <td><b>Conclusão</td>');
    ShowHTML('          <td class="remover"><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor; 
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nm_formacao').'</td>');
        ShowHTML('        <td>'.f($row,'nm_area').'</td>');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'carga_horaria').'</td>');
        ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($row,'conclusao')),'---').'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_cvpescurtec').'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_cvpescurtec').'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_usuario" value="'.$w_usuario.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sq_area_conhecimento" value="'.$w_sq_area_conhecimento.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr>');
    SelecaoFormacao('T<u>i</u>po de extensão:','O','Selecione o tipo mais adequado para a extensão acadêmica.',$w_sq_formacao,'Técnica','w_sq_formacao',null,null);
    ShowHTML('      <tr><td valign="top"><b>Área do conhecimento relacionada:</b><br>');
    ShowHTML('              <input READONLY type="text" name="w_nm_area" class="sti" SIZE="50" VALUE="'.$w_nm_area.'">');
    if ($O!='E'){
      ShowHTML('              [<u onMouseOver="this.style.cursor=\'Hand\'" onMouseOut="this.style.cursor=\'Pointer\'" onClick="window.open(\''.montaURL_JS($w_dir,$w_pagina.'BuscaAreaConhecimento&TP='.$TP.'&SG='.$SG.'&P1=1').'\',\'AreaConhecimento\',\'top=70,left=100,width=600,height=400,toolbar=yes,status=yes,resizable=yes,scrollbars=yes\');"><b><font color="#0000FF">Procurar</font></b></u>]');
    }
     
    ShowHTML('      <tr><td valign="top"><b><u>N</u>ome curso:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="80" MAXLENGTH="80" VALUE="'.$w_nome.'"></td>');
    ShowHTML('      <tr><td valign="top"><b><u>I</u>nstituição:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_instituicao" class="sti" SIZE="80" MAXLENGTH="100" VALUE="'.$w_instituicao.'"></td>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('          <tr><td valign="top"><b><u>C</u>arga horária:</b><br><input '.$w_Disabled.' accesskey="c" type="text" name="w_carga_horaria" class="sti" SIZE="7" MAXLENGTH="7" VALUE="'.$w_carga_horaria.'"></td>');
    ShowHTML('              <td valign="top"><b>C<u>o</u>nclusão: (dd/mm/aaaa)</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_conclusao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_conclusao.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
    ShowHTML('          </table>');
    if ($_SESSION['PORTAL']==''){
      ShowHTML('      <tr><td align="LEFT"><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>'); 
      ShowHTML('      <tr><td align="center"><hr>');
    } if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else{
      if ($O=='I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de produção técnica
// -------------------------------------------------------------------------
function Producao() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];
  if (Nvl($P1,0)!=1) {
    $sql = new db_getCV; $RS = $sql->getInstanceOf($dbms,$w_cliente,nvl($w_usuario,0),$SG,'DADOS');
    foreach ($RS as $row) {$RS=$row; break;}
    if (Nvl(f($RS,'inclusao'),'')=='') {
      ScriptOpen('JavaScript');
      ShowHTML('alert(\'Efetue o cadastro da identificação primeiro!\');');
      ShowHTML('location.href=\''.montaURL_JS($w_dir,'cv.php?par=Identificacao&w_usuario='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } 
  } if ($w_troca >'') {
    // Se for recarga da página
    $w_sq_area_conhecimento = $_REQUEST['w_sq_area_conhecimento'];
    $w_sq_formacao          = $_REQUEST['w_sq_formacao'];
    $w_nome                 = $_REQUEST['w_nome'];
    $w_meio                 = $_REQUEST['w_meio'];
    $w_data                 = $_REQUEST['w_data'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$w_usuario,null,'PRODUCAO');
    $RS = SortArray($RS,'ordem','desc','data','desc');
  } elseif (strpos('AEV',$O)!==false && $w_troca=='') {
    // Recupera os dados do endereço informado
    $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$w_usuario,$w_chave,'PRODUCAO');
    foreach ($RS as $row) {$RS=$row;    break;}
    $w_sq_area_conhecimento = f($RS,'sq_area_conhecimento');
    $w_nm_area              = f($RS,'nm_area').' ('.f($RS,'codigo_cnpq').')';
    $w_sq_formacao          = f($RS,'sq_formacao');
    $w_nome                 = f($RS,'nome');
    $w_meio                 = f($RS,'meio');
    $w_data                 = FormataDataEdicao(f($RS,'data'));
  } 
  Cabecalho();
  head();
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    checkbranco();
    formatadata();
    SaltaCampo();
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_sq_formacao','Tipo da produção','SELECT','1','1','10','','1');
      Validate('w_nm_area','Área do conhecimento','','1','5','92','1','1');
      Validate('w_nome','Nome','1','1','1','80','1','1');
      Validate('w_meio','Meio de publicação','','1','2','100','1','1');
      Validate('w_data','Data','DATA','1','10','10','','0123456789/');
        if ($_SESSION['PORTAL']=='') {
          Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
        }
    } elseif ($O=='E' && $_SESSION['PORTAL']=='') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca >'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='I') {
    BodyOpen('onLoad=\'document.Form.w_sq_formacao.focus()\';');
  } elseif ($O=='A'){
    BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif ($O=='E' && $_SESSION['PORTAL']=='') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Tipo da produção</td>');
    ShowHTML('          <td><b>Área</td>');
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>Meio</td>');
    ShowHTML('          <td><b>Data</td>');
    ShowHTML('          <td class="remover"><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;  
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nm_formacao').'</td>');
        ShowHTML('        <td>'.f($row,'nm_area').'</td>');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td>'.f($row,'meio').'</td>');
        ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($row,'data')),'---').'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_cvpessoa_prod').'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_cvpessoa_prod').'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');    
    ShowHTML('</tr>');
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) {
      $w_Disabled = ' DISABLED ';
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_usuario" value="'.$w_usuario.'">');    
    ShowHTML('<INPUT type="hidden" name="w_troca" value=" ">');
    ShowHTML('<INPUT type="hidden" name="w_sq_area_conhecimento" value="'.$w_sq_area_conhecimento.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr>');
    SelecaoFormacao('T<u>i</u>po da produção:','O','Selecione o tipo mais adequado para a produção técnica.',$w_sq_formacao,'Prod.Cient.','w_sq_formacao',null,null);
    ShowHTML('      <tr><td valign="top"><b>Área do conhecimento relacionada:</b><br>');
    ShowHTML('              <input READONLY type="text" name="w_nm_area" class="sti" SIZE="50" VALUE="'.$w_nm_area.'">');
    
    if ($O!='E') {
      ShowHTML('              [<u onMouseOver="this.style.cursor=\'Hand\'" onMouseOut="this.style.cursor=\'Pointer\'" onClick="window.open(\''.montaURL_JS($w_dir,$w_pagina.'BuscaAreaConhecimento&TP='.$TP.'&SG='.$SG.'&P1=1').'\',\'AreaConhecimento\',\'top=70,left=100,width=600,height=400,toolbar=yes,status=yes,resizable=yes,scrollbars=yes\');"><b><font color="#0000FF">Procurar</font></b></u>]');
    } 
    ShowHTML('      <tr><td valign="top"><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="80" MAXLENGTH="80" VALUE="'.$w_nome.'"></td>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('          <tr><td valign="top"><b><u>M</u>eio de divulgação:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_meio" class="sti" SIZE="50" MAXLENGTH="80" VALUE="'.$w_meio.'"></td>');
    ShowHTML('              <td valign="top"><b><u>D</u>ata de publicação: (dd/mm/aaaa)</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
    ShowHTML('          </table>');
    if ($_SESSION['PORTAL']=='') {
      ShowHTML('      <tr><td align="LEFT"><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    }
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I'){
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    }
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de busca da área do conhecimento
// -------------------------------------------------------------------------
function BuscaAreaConhecimento() {
  extract($GLOBALS);
  global $w_Disabled;
  if ($P1=='') {
    $P1=1;
  } 
  $w_nome = $_REQUEST['w_nome'];
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  Validate('w_nome','Nome','1','1','4','30','1','1');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  if ($P1==1) {
    ShowHTML('<B><FONT COLOR="#000000">'.RemoveTP($w_TP).' - Procura Área do Conhecimento</FONT></B>');
  } else {
    ShowHTML('<B><FONT COLOR="#000000">'.RemoveTP($w_TP).' - Procura Cargo</FONT></B>');
  } 
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('    <table width="90%" border="0">');
  AbreForm('Form',$w_dir.$w_pagina.'BuscaAreaConhecimento','POST','return(Validacao(this))',null,$P1,$P2,$P3,$P4,$TP,$SG,null,null);
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2><b><ul>Instruções</b>:<li>Informe parte do nome da área de conhecimento desejada.<li>Quando a relação for exibida, selecione a área desejada clicando sobre a caixa ao seu lado.<li>Após informar o nome da área, clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Cancelar</i>, a procura é cancelada.</ul></div>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('    <table width="90%" border="0">');
  if ($P1==1) {
    ShowHTML('      <tr><td valign="top"><b>Parte do <U>n</U>ome da área do conhecimento:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="w_nome" size="30" maxlength="30" value="'.$w_nome.'">');
  } else {
    ShowHTML('      <tr><td valign="top"><b>Parte do <U>n</U>ome do cargo:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="w_nome" size="30" maxlength="30" value="'.$w_nome.'">');
  } 
  ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
  ShowHTML('      <tr><td align="center" colspan="3">');
  ShowHTML('            <input class="sti" type="submit" name="Botao" value="Aplicar filtro">');
  ShowHTML('            <input class="sti" type="button" name="Botao" value="Cancelar" onClick="window.close(); opener.focus();">');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</form>');
  if ($w_nome >'') {
    if ($P1==1) {
      $sql = new db_getKnowArea; $RS = $sql->getInstanceOf($dbms,null,$w_nome,'A');
    } else {
      $sql = new db_getKnowArea; $RS = $sql->getInstanceOf($dbms,null,$w_nome,'C');
    } 
    $RS = SortArray($RS,'nome','asc');
    ShowHTML('<tr><td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=6>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center" valign="top">');
      if ($P1==1) {
        ShowHTML('          <td><b>Clique sobre a área do conhecimento desejada</td>');
      } else {
        ShowHTML('          <td><b>Clique sobre o cargo desejado</td>');
      } 
      ShowHTML('        </tr>');
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td><ul>');
      foreach ($RS as $row) {
        ShowHTML('        <li><a class="SS" HREF="javascript:this.status.value;" onClick="opener.document.Form.w_nm_area.value=\''.f($row,'nome').'('.f($row,'codigo_cnpq').')\'; opener.document.Form.w_sq_area_conhecimento.value=\''.f($row,'sq_area_conhecimento').'\'; window.close(); opener.focus();">'.f($row,'nome').' ('.f($row,'codigo_cnpq').')</a>');
      } 
      ShowHTML('      </ul></tr>');
      ShowHTML('      </center>');
      ShowHTML('    </table>');
      ShowHTML('  </td>');
      ShowHTML('</tr>');
    } 
  } 
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de visualização
// -------------------------------------------------------------------------
function Visualizar() {
  extract($GLOBALS);
  global $w_Disable;
  if ($P2==1) {
    HeaderWord('PORTRAIT');
  } else {
    cabecalho();
  } 
  head();
  ShowHTML('<TITLE>Curriculum Vitae</TITLE>');
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($P2==0) {
    BodyOpen('onLoad=\'this.focus()\'; ');
  } 
  ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR>');
  if ($P2==0) {
    $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
    ShowHTML('  <TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,'/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30),null,null,null,'EMBED').'">');    
  } 
  ShowHTML('  <TD ALIGN="RIGHT"><B><FONT SIZE=5 COLOR="#000000">Curriculum Vitae</FONT>');
  if ($P2==0) {
    ShowHTML('<TR><TD ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">'.DataHora().'</B>');
    ShowHTML('&nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.'Visualizar&P2=1&SG=CVVISUAL"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
  } 
  ShowHTML('</TD></TR>');
  ShowHTML('</FONT></B></TD></TR></TABLE>');
  if ($P2==0) {
    ShowHTML('<HR>');
  } 
  // Chama a função de visualização dos dados do usuário, na opção 'Listagem'
  VisualCurriculo($w_cliente,$w_usuario,'L',$P2);
  if ($P2==0) {
    Rodape();
  } 
} 
// =========================================================================
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_file='';
  $w_tamanho='';
  $w_tipo='';
  $w_nome='';
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');
  switch ($SG) {
    case 'CVIDENT':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        // Recupera os dados do currículo a partir da chave
        $sql = new db_getCV_Pessoa; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_cpf']);
        if ($O=='I' && count($RS)>0) {
          ScriptOpen('JavaScript');
          ShowHTML('alert(\'CPF já cadastrado. Acesse seu currículo usando a opção "Seu currículo" no menu principal.\');');
          ShowHTML('history.back(1);');
          ScriptClose();
        } 
        // Se foi feito o upload de um arquivo 
        if (UPLOAD_ERR_OK==0) {
          $w_maximo = 51200;
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
                ShowHTML('  history.go(-1);');
                ScriptClose();
                exit();
              } 
              // Se já há um nome para o arquivo, mantém 
              $w_file = basename($Field['tmp_name']);
              if (strpos($Field['name'],'.')!==false) {
                $w_file = $w_file.substr($Field['name'],(strrpos($Field['name'],'.') ? strrpos($Field['name'],'.')+1 : 0)-1,10);
              }
              $w_tamanho = $Field['size'];
              $w_tipo    = $Field['type'];
              $w_nome    = $Field['name'];
              if ($w_file >'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
            } 
          } 
          $SQL = new dml_putCVIdent; $SQL->getInstanceOf($dbms,$O,
              $w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_nome'],$_REQUEST['w_nome_resumido'],$_REQUEST['w_nascimento'],
              $_REQUEST['w_sexo'],$_REQUEST['w_sq_estado_civil'],$_REQUEST['w_sq_formacao'],$_REQUEST['w_sq_etnia'],
              $_REQUEST['w_sq_deficiencia'],$_REQUEST['w_cidade'],$_REQUEST['w_rg_numero'],$_REQUEST['w_rg_emissor'],
              $_REQUEST['w_rg_emissao'],$_REQUEST['w_cpf'],$_REQUEST['w_passaporte_numero'],$_REQUEST['w_sq_pais_passaporte'],
              $w_file,$w_tamanho,$w_tipo,$w_nome,$w_chave_nova);    
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
          ScriptClose();
        }
        $w_username = 'N';            
        //Se for inclusão de colaborador, deve incluir o contrato
        if (Nvl($P1,0)==1 && Nvl($_REQUEST['w_sq_contrato_colaborador'],'')=='') {
          if (Nvl($_REQUEST['w_modalidade_contrato'],'')!='') {
            $sql = new db_getGPModalidade; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_modalidade_contrato'],null,null,null,null,null);
            foreach ($RS as $row){
              if(Nvl(f($row,'ferias'),'') == 'S'){
                $ferias = 'S';
              }elseif(Nvl(f($row,'ferias'),'') == 'N'){
                $ferias = 'N';
              }else{
                $ferias = 'S';
              }
              if(trim(Nvl(f($row,'horas_extras'),'')) == 'S'){
                $extras = 'S';
              }elseif(trim(Nvl(f($row,'horas_extras'),'')) == 'N'){
                $extras = 'N';
              }else{
                $extras = 'S';
              }        
            }
            //Decide pela criação de username 
            if ((Nvl(f($row,'username'),'')=='S') || (Nvl(f($row,'username'),'')=='P' && $_REQUEST['w_username_pessoa']=='S')) {
              $w_username = 'S';
            }
            //Grava os dados do contrato
            $SQL = new dml_putGPContrato; $SQL->getInstanceOf($dbms,$O,
                $w_cliente,$_REQUEST['w_sq_contrato_colaborador'],null,$w_chave_nova,$_REQUEST['w_posto_trabalho'],$_REQUEST['w_modalidade_contrato'],
                $_REQUEST['w_unidade_lotacao'],$_REQUEST['w_unidade_exercicio'],$_REQUEST['w_localizacao'],$_REQUEST['w_matricula'],
                $_REQUEST['w_dt_ini'],null,$w_username,$ferias,$extras,
                $_REQUEST['w_sq_tipo_vinculo'],null,null,null,null,null,null,'N','N','00:00',formataDataEdicao(time()),'0,00',
                null,null,'N','N','N','N','N',null,null,null,null
            );
            //Cria a conta para o usuário
            if ($w_username == 'S') {
              $SQL = new dml_putSiwUsuario; $SQL->getInstanceOf($dbms,'I',
                  $_REQUEST['w_chave'],$w_cliente,$_REQUEST['w_nome'],$_REQUEST['w_nome_resumido'],$_REQUEST['w_cpf'],$_REQUEST['w_sexo'],
                  $_REQUEST['w_sq_tipo_vinculo'],'Física',$_REQUEST['w_unidade_lotacao'],$_REQUEST['w_localizacao'],
                  $_REQUEST['w_cpf'],$_REQUEST['w_email'],null,null,'B');
              $SQL = new dml_putSiwUsuario; $SQL->getInstanceOf($dbms,'T',$_REQUEST['w_chave'],null,null,null,null,null,null,null,null,null,null,null,null,null,null);
            } 
          }
        } 
        ScriptOpen('JavaScript');
        if ($_SESSION['PORTAL']>'' && $O=='I') {
          ShowHTML('  top.location.href=\''.montaURL_JS($w_dir,$R).'\';');
        } else {
          if (Nvl($P1,0)==1) {
            $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$RS1,$w_menu);
            ShowHTML('  parent.menu.location=\''.montaURL_JS(null,$conRootSIW.'menu.php?par=ExibeDocs&O=A&w_usuario='.$w_chave_nova.'&w_sq_pessoa='.$w_chave_nova.'&w_documento='.$_REQUEST['w_nome_resumido'].'&R='.$R.'&SG=COINICIAL&TP='.RemoveTP($TP).MontaFiltro('GET')).'\';');
          } else {
            ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_usuario='.$_REQUEST['w_chave'].'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
          } 
        } 
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'CVHIST':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_putCVHist; $SQL->getInstanceOf($dbms,$O,
            $_REQUEST['w_chave'],$_REQUEST['w_residencia_outro_pais'],$_REQUEST['w_mudanca_nacionalidade'],
            $_REQUEST['w_mudanca_nacionalidade_medida'],$_REQUEST['w_emprego_seis_meses'],$_REQUEST['w_impedimento_viagem_aerea'],
            $_REQUEST['w_objecao_informacoes'],$_REQUEST['w_prisao_envolv_justica'],$_REQUEST['w_motivo_prisao'],
            $_REQUEST['w_fato_relevante_vida'],$_REQUEST['w_servidor_publico'],$_REQUEST['w_servico_publico_inicio'],
            $_REQUEST['w_servico_publico_fim'],$_REQUEST['w_atividades_civicas'],$_REQUEST['w_familiar']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'CVIDIOMA':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_putCVIdioma; $SQL->getInstanceOf($dbms,$O,$w_usuario,
        $_REQUEST['w_chave'],$_REQUEST['w_leitura'],$_REQUEST['w_escrita'],
        $_REQUEST['w_compreensao'],$_REQUEST['w_conversacao']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'CVEXPPER':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_putCVExperiencia; $SQL->getInstanceOf($dbms,$O,$w_usuario,
        $_REQUEST['w_chave'],$_REQUEST['w_sq_area_conhecimento'],$_REQUEST['w_sq_cidade'],$_REQUEST['w_sq_eo_tipo_posto'],
        $_REQUEST['w_sq_tipo_vinculo'],$_REQUEST['w_empregador'],$_REQUEST['w_entrada'],$_REQUEST['w_saida'],
        $_REQUEST['w_duracao_mes'],$_REQUEST['w_duracao_ano'],$_REQUEST['w_motivo_saida'],$_REQUEST['w_ultimo_salario'],
        $_REQUEST['w_atividades']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'CVCARGOS':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_putCVCargo; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_sq_cvpescargo'],
        $_REQUEST['w_sq_cvpesexp'],$_REQUEST['w_sq_area_conhecimento'],$_REQUEST['w_especialidades'],
        $_REQUEST['w_inicio'],$_REQUEST['w_fim']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_sq_cvpesexp='.$_REQUEST['w_sq_cvpesexp'].'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'CVESCOLA':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_putCVEscola; $SQL->getInstanceOf($dbms,$O,$w_usuario,
          $_REQUEST['w_chave'],$_REQUEST['w_sq_area_conhecimento'],$_REQUEST['w_sq_pais'],$_REQUEST['w_sq_formacao'],
          $_REQUEST['w_nome'],$_REQUEST['w_instituicao'],$_REQUEST['w_inicio'],$_REQUEST['w_fim']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'CVCURSO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_putCVCurso; $SQL->getInstanceOf($dbms,$O,$w_usuario,
          $_REQUEST['w_chave'],$_REQUEST['w_sq_area_conhecimento'],$_REQUEST['w_sq_formacao'],
          $_REQUEST['w_nome'],$_REQUEST['w_instituicao'],$_REQUEST['w_carga_horaria'],$_REQUEST['w_conclusao']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'CVTECNICA':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_putCVProducao; $SQL->getInstanceOf($dbms,$O,$w_usuario,
          $_REQUEST['w_chave'],$_REQUEST['w_sq_area_conhecimento'],$_REQUEST['w_sq_formacao'],
          $_REQUEST['w_nome'],$_REQUEST['w_meio'],$_REQUEST['w_data']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&w_usuario='.$w_usuario.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    default:
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Bloco de dados não encontrado: \'.$SG.\'\');');
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
  global $w_Disabled;
  switch ($par) {
    case 'INICIAL':                  Inicial();                 break;
    case 'IDENTIFICACAO':            Identificacao();           break;
    case 'HISTORICO':                Historico();               break;
    case 'IDIOMAS':                  Idiomas();                 break;
    case 'ESCOLARIDADE':             Escolaridade();            break;
    case 'CURSOS':                   Extensao();                break;
    case 'EXPPROF':                  Experiencia();             break;
    case 'DESPESA':                  Despesa();                 break;
    case 'PRODUCAO':                 Producao();                break;
    case 'CARGOS':                   Cargos();                  break;
    case 'VISUALIZAR':               Visualizar();              break;
    case 'BUSCAAREACONHECIMENTO':    BuscaAreaConhecimento();   break;
    case 'GRAVA':                    Grava();                   break;
    default:
      Cabecalho();
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');
      BodyOpen('onLoad=this.focus();');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
      ShowHTML('<HR>');
      ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
      Rodape();
      break;
  } 
} 
?>