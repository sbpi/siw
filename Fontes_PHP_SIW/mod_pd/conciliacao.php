<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'funcoes_valida.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getCiaTrans.php');
include_once($w_dir_volta.'classes/sp/db_getPD_Bilhete.php');
include_once($w_dir_volta.'classes/sp/db_getPD_Fatura.php');
include_once($w_dir_volta.'classes/sp/db_getDescontoAgencia.php');
include_once($w_dir_volta.'classes/sp/db_getPDImportacao.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putPDImportacao.php');
include_once($w_dir_volta.'classes/sp/dml_putPD_Fatura.php');
include_once($w_dir_volta.'classes/sp/dml_putPD_Fatura_Outros.php');
include_once($w_dir_volta.'classes/sp/dml_putPD_Bilhete.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoTipoFatura.php');
// =========================================================================
//  /conciliacao.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papad�polis
// Descricao: Rotinas de concilia��o da fatura eletr�nica da ag�ncia de viagem
// Mail     : alex@sbpi.com.br
// Criacao  : 07/04/2006, 13:20
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
// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }
// Declara��o de vari�veis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);
// Carrega vari�veis locais com os dados dos par�metros recebidos
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
$w_pagina       = 'conciliacao.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_pd/';
$w_troca        = $_REQUEST['w_troca'];
// Configura o caminho para grava��o f�sica de arquivos<u></u>
$p_responsavel  = upper($_REQUEST['p_responsavel']);
$p_dt_ini       = $_REQUEST['p_dt_ini'];
$p_dt_fim       = $_REQUEST['p_dt_fim'];
$p_imp_ini      = $_REQUEST['p_imp_ini'];
$p_imp_fim      = $_REQUEST['p_imp_fim'];
if ($O=='') {
  if ($par=='REL_PPA' || $par=='REL_INICIATIVA') $O='P'; else $O='L';
} 
switch ($O) {
  case 'I': $w_TP=$TP.' - Inclus�o';    break;
  case 'A': $w_TP=$TP.' - Altera��o';   break;
  case 'E': $w_TP=$TP.' - Exclus�o';    break;
  case 'P': $w_TP=$TP.' - Filtragem';   break;
  case 'C': $w_TP=$TP.' - C�pia';       break;
  case 'V': $w_TP=$TP.' - Envio';       break;
  case 'H': $w_TP=$TP.' - Heran�a';     break;
  case 'O': $w_TP=$TP.' - Orienta��es'; break;
  default:  $w_TP=$TP.' - Listagem';    break;
} 
// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();
$w_caminho  = $conFilePhysical.$w_cliente.'/';
// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$sql = new db_getLinkSubMenu; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu = 'Existe';
} else {
  $w_submenu = '';
} 
// Recupera a configura��o do servi�o
if ($P2>0) {
  $RS_Menu = new db_getMenuData; $RS_Menu = $RS_Menu->getInstanceOf($dbms,$P2);
} else {
  $RS_Menu = new db_getMenuData; $RS_Menu = $RS_Menu->getInstanceOf($dbms,$w_menu);
}
// Se for sub-menu, pega a configura��o do pai
if (f($RS_Menu,'ultimo_nivel')=='S') { 
  $RS_Menu = new db_getMenuData; $RS_Menu = $RS_Menu->getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 
Main();
FechaSessao($dbms);
exit;
// =========================================================================
// Rotina de importa��o de arquivos f�sicos para atualiza��o de dados financeiros
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  $w_chave  = $_REQUEST['w_chave'];
  $w_troca  = $_REQUEST['w_troca'];
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_data             = $_REQUEST['w_data'];
    $w_sq_pessoa        = $_REQUEST['w_sq_pessoa'];
    $w_agencia          = $_REQUEST['w_agencia'];
    $w_data_arquivo     = $_REQUEST['w_data_arquivo'];
    $w_arquivo_recebido = $_REQUEST['w_arquivo_recebido'];
    $w_arquivo_registro = $_REQUEST['w_arquivo_registro'];
    $w_registros        = $_REQUEST['w_registros'];
    $w_importados       = $_REQUEST['w_importados'];
    $w_rejeitados       = $_REQUEST['w_rejeitados'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getPDImportacao; $RS = $sql->getInstanceOf($dbms,$w_chave,$w_cliente,$p_responsavel,$p_dt_ini,$p_dt_fim,$p_imp_ini,$p_imp_fim,null);
    $RS = SortArray($RS,'phpdt_data_arquivo','desc','phpdt_data_importacao','desc');
  } 
  
  // Verifica quantas ag�ncias de viagens est�o cadastradas
  $sql = new db_getPersonList; $RS1 = $sql->getInstanceOf($dbms, $w_cliente, null, 'FORNECPD', null, null, null, null);
  $w_qtd_agencia = count($RS1);
  if ($w_qtd_agencia==1) {
    foreach($RS1 as $row1) $w_agencia = f($row1,'sq_pessoa');
  }
  
  Cabecalho();
  head();
  if (!(strpos('IP',$O)===false)) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataDataHora();
    SaltaCampo();
    ValidateOpen('Validacao');
    if (!(strpos('I',$O)===false)) {
      if ($w_qtd_agencia>1) Validate('w_agencia','Ag�ncia de viagem','SELECT','1','1','18','','0123456789');
      Validate('w_tipo','Conte�do do arquivo','SELECT','1','1','18','','0123456789');
      Validate('w_data_arquivo','Data e hora','DATAHORA','1','17','17','','0123456789 /:,');
      Validate('w_arquivo_recebido','Arquivo de dados','1','1','1','255','1','1');
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'')                      BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  elseif ($O=='I') {
    if ($w_qtd_agencia>1) {
      BodyOpen('onLoad=\'document.Form.w_agencia.focus()\';');
    } else {
      BodyOpen('onLoad=\'document.Form.w_tipo.focus()\';');
    }
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td>');
    ShowHTML('        <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('        <a accesskey="O" class="SS" href="'.$w_dir.$w_pagina.'Help&R='.$w_pagina.$par.'&O=O&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" target="help"><u>O</u>rienta��es</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan=2><b>Data</font></td>');
    ShowHTML('          <td rowspan=2><b>Executado em</font></td>');
    ShowHTML('          <td rowspan=2><b>Respons�vel</font></td>');
    ShowHTML('          <td rowspan=2><b>Tipo da fatura</font></td>');
    ShowHTML('          <td colspan=3><b>Linhas</font></td>');
    ShowHTML('          <td rowspan=2><b>Faturas<br>importadas</font></td>');
    ShowHTML('          <td rowspan=2><b>Opera��es</font></td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Total</font></td>');
    ShowHTML('          <td><b>Aceitas</font></td>');
    ShowHTML('          <td><b>Rejeitadas</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=9 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center">'.substr(FormataDataEdicao(f($row,'phpdt_data_arquivo'),6),0,-3).'</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'phpdt_data_importacao'),6).'</td>');
        ShowHTML('        <td title="'.f($row,'nm_resp').'">'.f($row,'nm_resumido_resp').'</td>');
        ShowHTML('        <td>'.f($row,'tp_fatura').'&nbsp;</td>');
        ShowHTML('        <td align="center">'.f($row,'registros').'&nbsp;</td>');
        ShowHTML('        <td align="center">'.f($row,'importados').'&nbsp;</td>');
        ShowHTML('        <td align="center">'.f($row,'rejeitados').'&nbsp;</td>');
        ShowHTML('        <td align="center">'.((f($row,'qt_fatura')>0) ? f($row,'qt_fatura') : '').'&nbsp;</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          '.LinkArquivo('HL',$w_cliente,f($row,'chave_recebido'),'_blank','Exibe os dados do arquivo importado.','Arquivo',null).'&nbsp');
        ShowHTML('          '.LinkArquivo('HL',$w_cliente,f($row,'chave_result'),'_blank','Exibe o registro da importa��o.','Registro',null).'&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG='.$SG.'&O='.$O.'&UploadID='.$UploadID.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
    ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
    ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
    ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
    ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
    ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
    ShowHTML('<INPUT type="hidden" name="R" value="'.$R.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    if ($O=='I' || $O=='A') {
      $RS = new db_getCustomerData; $RS = $RS->getInstanceOf($dbms,$w_cliente);
      ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b><font color="#BC3131">ATEN��O</font>: o tamanho m�ximo aceito para o arquivo � de '.(f($RS,'upload_maximo')/1024).' KBytes</b>.</font></td>');
      ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
    } 
    if ($w_qtd_agencia>1) {
      ShowHTML('       <tr valign="top">');
      SelecaoPessoa('Ag�<u>n</u>cia de viagem:','N','Selecione a ag�ncia de viagem emissora da fatura.',$w_agencia,null,'w_agencia','FORNECPD');
      ShowHTML('       </tr>');
    } elseif ($w_qtd_agencia==1) {
      ShowHTML('<INPUT type="hidden" name="w_agencia" value="'.$w_agencia.'">');
    }
    ShowHTML('       <tr>');
    SelecaoTipoFatura('C<u>o</u>nte�do do arquivo:','O','Indique o conte�do do arquivo a ser importado.',$w_tipo,null,'w_tipo',null,null,1);
    ShowHTML('      <tr><td><b><u>D</u>ata/hora extra��o:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data_arquivo" class="sti" SIZE="17" MAXLENGTH="17" VALUE="'.$w_data_arquivo.'"  onKeyDown="FormataDataHora(this, event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);" title="OBRIGAT�RIO. Informe a data e hora da extra��o do aquivo. Digite apenas n�meros. O sistema colocar� os separadores automaticamente."></td>');
    ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_arquivo_recebido" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OBRIGAT�RIO. Clique no bot�o ao lado para localizar o arquivo (sua extens�o deve ser .TXT). Ele ser� transferido automaticamente para o servidor.">');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E')
      ShowHTML('          <input class="STB" type="submit" name="Botao" value="Excluir">');
    else
      ShowHTML('          <input class="STB" type="submit" name="Botao" value="Incluir">');
    ShowHTML('          <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Exibe orienta��es sobre o processo de importa��o
// -------------------------------------------------------------------------
function Help() {
  extract($GLOBALS);
  Cabecalho();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen(null);
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="90%">');
  ShowHTML('<tr valign="top">');
  ShowHTML('  <td><font size=2>');
  ShowHTML('    <p align="justify">Esta tela tem o objetivo de conciliar os bilhetes de uma fatura eletr�nica com os registrados pelo sistema de viagens e');
  ShowHTML('      importar faturas relativas a hospedagens, loca��es de ve�culos e seguros de viagem. ');
  ShowHTML('    <p align="justify">Os crit�rios adotados para a concilia��o est�o descritos abaixo, nesta p�gina.');
  ShowHTML('    <p align="justify">Para ser executada corretamente, o procedimento de concilia��o deve cumprir os passos abaixo.');
  ShowHTML('    <ol>');
  ShowHTML('    <p align="justify"><b>FASE 1 - Prepara��o do arquivo a ser importado:</b><br></p>');
  ShowHTML('      <li>Obtenha junto � ag�ncia de viagens arquivos similares aos exibidos nestes exemplos: ');
  ShowHTML('          '.LinkArquivo('HL',$w_cliente,'FATURA_AEREA_IMPORT.TXT','ExemploAgencia','Exibe o registro da importa��o.','Bilhetes A�reos',null).' e ');
  ShowHTML('          '.LinkArquivo('HL',$w_cliente,'FATURA_OUTROS_IMPORT.TXT','ExemploAgencia','Exibe o registro da importa��o.','Hospedagens, Loca��es de Ve�culos e Seguros de Viagem',null));
  ShowHTML('          <br>Formato do arquivo: CSV ');
  ShowHTML('          <br>Codifica��o: ANSI ');
  ShowHTML('          <br>Separador de campo: ponto-e-v�rgula ');
  ShowHTML('          <br>Delimitador de campo: aspas duplas (") ');
  ShowHTML('          <br>Quebra de linha: formato Windows (CR+LF) ');
  ShowHTML('    <p align="justify"><b>FASE 2 - Importa��o do arquivo e concilia��o dos dados:</b><br></p>');
  ShowHTML('      <li>Na tela anterior, clique sobre a opera��o "Incluir";');
  ShowHTML('      <li>Quando a tela de inclus�o for apresentada, preencha o formul�rio seguindo as instru��es dispon�veis em cada campo ');
  ShowHTML('          (passe o mouse sobre o campo desejado para o sistema exibir a instru��o de preenchimento);');
  ShowHTML('      <li>Aguarde o t�rmino da importa��o e concilia��o dos dados. O sistema ir�, numa �nica execu��o, transferir o arquivo ');
  ShowHTML('          selecionado para o servidor, ler cada uma das suas linhas, verificar se os dados est�o corretos e gerar um arquivo com o registro ');
  ShowHTML('          da verifica��o. Este processamento pode demorar alguns minutos. N�o clique em nenhum bot�o at� o sistema voltar para ');
  ShowHTML('          para a listagem das importa��es j� executadas;');
  ShowHTML('    <p align="justify"><b>FASE 3 - Verifica��o do arquivo de registro:</b><br></p>');
  ShowHTML('      <li>Verifique se ocorreu erro na importa��o de alguma linha do arquivo de origem. Na lista de importa��es, existem tr�s colunas: ');
  ShowHTML('          "Bilhetes" indica o n�mero total de linhas do arquivo, "Aceitos" indica o n�mero de linhas que atenderam �s condi��es de importa��o e');
  ShowHTML('          "Rejeitados" indica o n�mero de linhas que foram descartadas pela valida��o; ');
  ShowHTML('      <li>Verifique cada linha descartada pela rotina de importa��o. Clique sobre a opera��o "Registro" na coluna "Opera��es" e verifique ');
  ShowHTML('          os erros detectados em cada uma das linhas descartadas. O conte�do do arquivo � similar ao deste ');
  ShowHTML('          '.LinkArquivo('HL',$w_cliente,'FATURA_AEREA_REGISTRO.TXT','ExemploAgencia','Exibe o registro da importa��o.','exemplo',null).';');
  ShowHTML('      <li>Se desejar, gere um novo arquivo somente com as linhas descartadas, corrija os erros e fa�a uma nova importa��o.');
  ShowHTML('    </ol>');
  ShowHTML('    <p align="justify"><b>Observa��es:</b><br></p>');
  ShowHTML('    <ul>');
  ShowHTML('      <li>Para restringir a importa��o �s linhas que realmente s�o �teis, abra o arquivo obtido no passo 1 com o Bloco de Notas (Notepad) ');
  ShowHTML('          e remova eventuais linhas em branco, n�o esquecendo de salv�-lo;');
  ShowHTML('      <li>Uma vez conclu�da uma importa��o, n�o h� necessidade de voc� manter em seu computador/disquete o arquivo utilizado. O sistema ');
  ShowHTML('          grava no servidor uma c�pia do arquivo usado pela importa��o e uma c�pia do arquivo de registro;');
  ShowHTML('      <li>Toda importa��o registra os dados de quem a executou, de quando ela foi executada, bem como os arquivos de origem e de registro; ');
  ShowHTML('      <li>N�o h� como cancelar uma importa��o, nem de reverter os valores existentes antes da sua execu��o. Assim, certifique-se de que o ');
  ShowHTML('          arquivo de origem est� correto e que a importa��o deve realmente ser executada.');
  ShowHTML('    </ul>');
  ShowHTML('    <p align="justify"><b>Verifica��es dos dados:</b><br></p>');
  ShowHTML('    <p align="justify">Uma linha do arquivo origem s� � aceita se atender aos seguintes crit�rios:</p>');
  ShowHTML('    <ol>');
  ShowHTML('    <li><b>Arquivos contendo faturas de bilhetes a�reos</b>');
  ShowHTML('    <table border=1>');
  ShowHTML('      <tr align="center">');
  ShowHTML('        <td>Coluna</td>');
  ShowHTML('        <td>Campo</td>');
  ShowHTML('        <td>Tipo</td>');
  ShowHTML('        <td>Obrigat�rio</td>');
  ShowHTML('        <td>Dom�nio</td>');
  ShowHTML('        <td>Formato</td>');
  ShowHTML('        <td width="30%">Observa��es</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>1</td>');
  ShowHTML('        <td>N�mero da fatura</td>');
  ShowHTML('        <td>VARCHAR(30)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>0123456789</td>');
  ShowHTML('        <td>AAAAA...</td>');
  ShowHTML('        <td>');
  ShowHTML('          <li>Cada arquivo pode conter bilhetes de mais de uma fatura.</li>');
  ShowHTML('          <li>Cada fatura deve relacionar bilhetes de um mesmo projeto.</li>');
  ShowHTML('          <li>Faturas j� processadas e aceitas n�o podem ser reprocessadas.</li>');
  ShowHTML('        </td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>2</td>');
  ShowHTML('        <td>In�cio do dec�ndio</td>');
  ShowHTML('        <td>CHAR(10)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Data v�lida</td>');
  ShowHTML('        <td>DD/MM/YYYY</td>');
  ShowHTML('        <td>Esse valor deve ser repetido em todas as linhas da fatura.<td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>3</td>');
  ShowHTML('        <td>Fim do dec�ndio</td>');
  ShowHTML('        <td>CHAR(10)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Data v�lida</td>');
  ShowHTML('        <td>DD/MM/YYYY</td>');
  ShowHTML('        <td>Esse valor deve ser repetido em todas as linhas da fatura.</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>4</td>');
  ShowHTML('        <td>Emiss�o da fatura</td>');
  ShowHTML('        <td>CHAR(10)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Data v�lida</td>');
  ShowHTML('        <td>DD/MM/YYYY</td>');
  ShowHTML('        <td>Esse valor deve ser repetido em todas as linhas da fatura.</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>5</td>');
  ShowHTML('        <td>Vencimento da fatura</td>');
  ShowHTML('        <td>CHAR(10)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Data v�lida</td>');
  ShowHTML('        <td>DD/MM/YYYY</td>');
  ShowHTML('        <td>Esse valor deve ser repetido em todas as linhas da fatura.</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>6</td>');
  ShowHTML('        <td>Valor da fatura</td>');
  ShowHTML('        <td nowrap>VARCHAR(18)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Valor monet�rio na nota��o brasileira (separador de milhar = ponto e de decimal = v�rgula)</td>');
  ShowHTML('        <td>000.000.009,99</td>');
  ShowHTML('        <td>Esse valor deve ser repetido em todas as linhas da fatura.</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>7</td>');
  ShowHTML('        <td>Cia emissora do bilhete</td>');
  ShowHTML('        <td nowrap>CHAR(2)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Sigla da companhia a�rea</td>');
  ShowHTML('        <td>AA</td>');
  ShowHTML('        <td>Verificar companhias de transporte a�reo cadastradas na op��o "Viagens - Tabelas - Companhias de transporte".</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>8</td>');
  ShowHTML('        <td>N�mero do bilhete</td>');
  ShowHTML('        <td>VARCHAR(20)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>0123456789</td>');
  ShowHTML('        <td>AAAAA...</td>');
  ShowHTML('        <td>A combina��o Cia emissora / N�mero do bilhete deve ser �nica no arquivo e n�o pode ter sido processada em outra fatura.</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>9</td>');
  ShowHTML('        <td>Emiss�o do bilhete</td>');
  ShowHTML('        <td>CHAR(10)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Data v�lida</td>');
  ShowHTML('        <td>DD/MM/YYYY</td>');
  ShowHTML('        <td>&nbsp;');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>10</td>');
  ShowHTML('        <td>Trechos do bilhete</td>');
  ShowHTML('        <td>VARCHAR(60)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>&nbsp;</td>');
  ShowHTML('        <td>AAAAA...</td>');
  ShowHTML('        <td>Trechos do bilhete separados por barras. Ex: GRU/FCO/GRU</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>11</td>');
  ShowHTML('        <td>Projeto (c�digo or�ament�rio)</td>');
  ShowHTML('        <td nowrap>VARCHAR(30)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>C�digo de projeto existente no sistema</td>');
  ShowHTML('        <td>&nbsp;</td>');
  ShowHTML('        <td>');
  ShowHTML('          <li>Verificar c�digos v�lidos de projetos na op��o "Mesa de trabalho - Projetos", coluna "Consultar".</li>');
  ShowHTML('          <li>Cada fatura deve conter bilhetes de um mesmo projeto. Assim, esse valor deve ser repetido em todas as linhas da fatura.</li>');
  ShowHTML('        </td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>12</td>');
  ShowHTML('        <td>Valor pleno do bilhete</td>');
  ShowHTML('        <td nowrap>VARCHAR(18)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Valor monet�rio na nota��o brasileira (separador de milhar = ponto e de decimal = v�rgula)</td>');
  ShowHTML('        <td>000.000.009,99</td>');
  ShowHTML('        <td>Igual ao valor digitado pelo agente interno de viagens quando registrou o bilhete.</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>13</td>');
  ShowHTML('        <td>% de desconto concedido</td>');
  ShowHTML('        <td nowrap>VARCHAR(18)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Valor percentual de 0,00 a 100,00</td>');
  ShowHTML('        <td>009,99</td>');
  ShowHTML('        <td>Conforme tabela "Viagens - Tabelas - Descontos"</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>14</td>');
  ShowHTML('        <td>Valor TKT / PTA</td>');
  ShowHTML('        <td nowrap>VARCHAR(18)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Valor monet�rio na nota��o brasileira (separador de milhar = ponto e de decimal = v�rgula)</td>');
  ShowHTML('        <td>000.000.009,99</td>');
  ShowHTML('        <td>Igual ao valor informado pelo agente interno de viagens quando registrou o bilhete.</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>15</td>');
  ShowHTML('        <td>Valor da reten��o da tarifa</td>');
  ShowHTML('        <td nowrap>VARCHAR(18)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Valor monet�rio na nota��o brasileira (separador de milhar = ponto e de decimal = v�rgula)</td>');
  ShowHTML('        <td>000.000.009,99</td>');
  ShowHTML('        <td>&nbsp;</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>16</td>');
  ShowHTML('        <td>Valor das taxas</td>');
  ShowHTML('        <td nowrap>VARCHAR(18)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Valor monet�rio na nota��o brasileira (separador de milhar = ponto e de decimal = v�rgula)</td>');
  ShowHTML('        <td>000.000.009,99</td>');
  ShowHTML('        <td>Igual taxa de embarque mais outras taxas, campos informados pelo agente interno de viagens quando o bilhete foi registrado.</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>17</td>');
  ShowHTML('        <td>Valor da reten��o da taxa</td>');
  ShowHTML('        <td nowrap>VARCHAR(18)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Valor monet�rio na nota��o brasileira (separador de milhar = ponto e de decimal = v�rgula)</td>');
  ShowHTML('        <td>000.000.009,99</td>');
  ShowHTML('        <td>&nbsp;</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>18</td>');
  ShowHTML('        <td>Valor do desconto contratual</td>');
  ShowHTML('        <td nowrap>VARCHAR(18)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Valor monet�rio na nota��o brasileira (separador de milhar = ponto e de decimal = v�rgula)</td>');
  ShowHTML('        <td>000.000.009,99</td>');
  ShowHTML('        <td>&nbsp;</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>19</td>');
  ShowHTML('        <td>Valor total do bilhete</td>');
  ShowHTML('        <td nowrap>VARCHAR(18)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Valor monet�rio na nota��o brasileira (separador de milhar = ponto e de decimal = v�rgula)</td>');
  ShowHTML('        <td>000.000.009,99</td>');
  ShowHTML('        <td>Valor do bilhete (coluna 14) - valor do desconto contratual (coluna 18) + valor das taxas (coluna 16)</td>');
  ShowHTML('      </tr>');
  ShowHTML('    </table>');
  ShowHTML('    <ul>');
  ShowHTML('    Legenda da coluna formato:');
  ShowHTML('      <li>A = caractere alfanum�rico (A..Z,a..z,0..9)');
  ShowHTML('      <li>0 = caractere num�rico exibido apenas se diferente de zero');
  ShowHTML('      <li>9 = caractere num�rico obrigat�rio');
  ShowHTML('      <li>DD = dia com duas posi��es (01..30)');
  ShowHTML('      <li>MM = m�s com duas posi��es (01..12)');
  ShowHTML('      <li>YYYY = ano com s�culo (1999, 2000, 2001...)');
  ShowHTML('    </ul><br>');
  ShowHTML('    <li><b>Arquivos contendo faturas de hospedagens, loca��es de ve�culo e seguros de viagem</b>');
  ShowHTML('    <table border=1>');
  ShowHTML('      <tr align="center">');
  ShowHTML('        <td>Coluna</td>');
  ShowHTML('        <td>Campo</td>');
  ShowHTML('        <td>Tipo</td>');
  ShowHTML('        <td>Obrigat�rio</td>');
  ShowHTML('        <td>Dom�nio</td>');
  ShowHTML('        <td>Formato</td>');
  ShowHTML('        <td width="30%">Observa��es</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>1</td>');
  ShowHTML('        <td>Tipo do registro</td>');
  ShowHTML('        <td>NUMBER(1)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>1, 2 ou 3</td>');
  ShowHTML('        <td>9</td>');
  ShowHTML('        <td>');
  ShowHTML('          1: linha relativa a fatura de hospedagem');
  ShowHTML('          <br>2: loca��o de ve�culo');
  ShowHTML('          <br>3: seguro de viagem');
  ShowHTML('        </td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>2</td>');
  ShowHTML('        <td>N�mero da fatura</td>');
  ShowHTML('        <td>VARCHAR(30)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>0123456789</td>');
  ShowHTML('        <td>AAAAA...</td>');
  ShowHTML('        <td>');
  ShowHTML('          <li>Cada arquivo pode conter bilhetes de mais de uma fatura.</li>');
  ShowHTML('          <li>Cada fatura deve relacionar bilhetes de um mesmo projeto.</li>');
  ShowHTML('          <li>Faturas j� processadas e aceitas n�o podem ser reprocessadas.</li>');
  ShowHTML('        </td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>3</td>');
  ShowHTML('        <td>In�cio do dec�ndio</td>');
  ShowHTML('        <td>CHAR(10)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Data v�lida</td>');
  ShowHTML('        <td>DD/MM/YYYY</td>');
  ShowHTML('        <td>Esse valor deve ser repetido em todas as linhas da fatura.<td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>4</td>');
  ShowHTML('        <td>Fim do dec�ndio</td>');
  ShowHTML('        <td>CHAR(10)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Data v�lida</td>');
  ShowHTML('        <td>DD/MM/YYYY</td>');
  ShowHTML('        <td>Esse valor deve ser repetido em todas as linhas da fatura.</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>5</td>');
  ShowHTML('        <td>Emiss�o da fatura</td>');
  ShowHTML('        <td>CHAR(10)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Data v�lida</td>');
  ShowHTML('        <td>DD/MM/YYYY</td>');
  ShowHTML('        <td>Esse valor deve ser repetido em todas as linhas da fatura.</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>6</td>');
  ShowHTML('        <td>Vencimento da fatura</td>');
  ShowHTML('        <td>CHAR(10)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Data v�lida</td>');
  ShowHTML('        <td>DD/MM/YYYY</td>');
  ShowHTML('        <td>Esse valor deve ser repetido em todas as linhas da fatura.</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>7</td>');
  ShowHTML('        <td>Valor da fatura</td>');
  ShowHTML('        <td nowrap>VARCHAR(18)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Valor monet�rio na nota��o brasileira (separador de milhar = ponto e de decimal = v�rgula)</td>');
  ShowHTML('        <td>000.000.009,99</td>');
  ShowHTML('        <td>Esse valor deve ser repetido em todas as linhas da fatura.</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>8</td>');
  ShowHTML('        <td>Projeto (c�digo or�ament�rio)</td>');
  ShowHTML('        <td nowrap>VARCHAR(30)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>C�digo de projeto existente no sistema</td>');
  ShowHTML('        <td>&nbsp;</td>');
  ShowHTML('        <td>');
  ShowHTML('          <li>Verificar c�digos v�lidos de projetos na op��o "Mesa de trabalho - Projetos", coluna "Consultar".</li>');
  ShowHTML('          <li>Cada fatura deve conter bilhetes de um mesmo projeto. Assim, esse valor deve ser repetido em todas as linhas da fatura.</li>');
  ShowHTML('        </td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>9</td>');
  ShowHTML('        <td>C�digo da SV</td>');
  ShowHTML('        <td nowrap>VARCHAR(30)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>C�digo de SV existente no sistema</td>');
  ShowHTML('        <td>SV-00009/9999</td>');
  ShowHTML('        <td>C�digo informado pelo agente interno de viagens, exatamente igual ao que foi gerado automaticamente para a SV no momento da sua inclus�o.</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>10</td>');
  ShowHTML('        <td>CNPJ</td>');
  ShowHTML('        <td>CHAR(18)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>CNPJ v�lido</td>');
  ShowHTML('        <td>99.999.999/9999-99</td>');
  ShowHTML('        <td>CNPJ do hotel ou locadora do ve�culo ou seguradora.</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>11</td>');
  ShowHTML('        <td>Raz�o social</td>');
  ShowHTML('        <td>VARCHAR(60)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Alfanum�rico</td>');
  ShowHTML('        <td>AAAAA...</td>');
  ShowHTML('        <td>Raz�o social do hotel, locadora de ve�culo ou seguradora. Valores com mais de 60 posi��es ser�o truncados');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>12</td>');
  ShowHTML('        <td>In�cio</td>');
  ShowHTML('        <td>CHAR(10)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Data v�lida</td>');
  ShowHTML('        <td>DD/MM/YYYY</td>');
  ShowHTML('        <td>Data de in�cio da hospedagem, loca��o ou seguro.<td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>13</td>');
  ShowHTML('        <td>Fim</td>');
  ShowHTML('        <td>CHAR(10)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Data v�lida</td>');
  ShowHTML('        <td>DD/MM/YYYY</td>');
  ShowHTML('        <td>Data de t�rmino da hospedagem, loca��o ou seguro.</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>14</td>');
  ShowHTML('        <td>Valor</td>');
  ShowHTML('        <td nowrap>VARCHAR(18)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Valor monet�rio na nota��o brasileira (separador de milhar = ponto e de decimal = v�rgula)</td>');
  ShowHTML('        <td>000.000.009,99</td>');
  ShowHTML('        <td>Valor da hospedagem, loca��o ou seguro. </td>');
  ShowHTML('      </tr>');
  ShowHTML('    </table>');
  ShowHTML('    <ul>');
  ShowHTML('    Legenda da coluna formato:');
  ShowHTML('      <li>Idem � tabela anterior');
  ShowHTML('    </ul>');
  ShowHTML('    </ul>');
  ShowHTML('  </td>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');  
  BodyOpen(null);
  switch ($SG) {
    case 'PDFATURA':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if (UPLOAD_ERR_OK==0) {
          $w_maximo = $_REQUEST['w_upload_maximo'];
          foreach ($_FILES as $Chv => $Field) {
            if (!($Field['error']==UPLOAD_ERR_OK || $Field['error']==UPLOAD_ERR_NO_FILE)) {
              // Verifica se o tamanho das fotos est� compat�vel com  o limite de 100KB. 
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Aten��o: o tamanho m�ximo do arquivo n�o pode exceder '.($w_maximo/1024).' KBytes!\');');
              ShowHTML('  history.go(-1);');
              ScriptClose();
              exit();
            }
            if ($Field['size'] > 0) {
              // Verifica se o tamanho das fotos est� compat�vel com  o limite de 100KB. 
              if ($Field['size'] > $w_maximo) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'Aten��o: o tamanho m�ximo do arquivo n�o pode exceder '.($w_maximo/1024).' KBytes!\');');
                ShowHTML('  history.back(1);');
                ScriptClose();
                exit();
              } 
              // Se j� h� um nome para o arquivo, mant�m 
              $w_caminho_recebido   = str_replace('.tmp','',basename($Field['tmp_name']));
              $w_tamanho_recebido   = $Field['size'];
              $w_tipo_recebido      = $Field['type'];
              $w_nome_recebido      = $Field['name'];
              if ($w_caminho_recebido>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_caminho_recebido);
              $w_caminho_registro   = str_replace(substr($w_caminho_recebido,strpos($w_caminho_recebido,'.'),30),'',$w_caminho_recebido).'r'.substr($w_caminho_recebido,strpos($w_caminho_recebido,'.'),30);
            } 
          } 
          $w_registros  = 0;
          $w_importados = 0;
          $w_rejeitados = 0;
          $w_cont       = 0;
          
          ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/relogio.gif" align="center"> Aguarde: importando <b>'.$w_nome_recebido.'</b><br><br><br><br><br><br><br><br><br><br></center></div>');
          Rodape();
          flush();
          
          //Abre o arquivo recebido para gerar o arquivo registro
          $F2 = csv($w_caminho.$w_caminho_recebido);
          if ($_REQUEST['w_tipo']==0) { // Fatura de bilhetes a�reos
            if (is_array($F2[""])) {
              // Recupera dados da ag�ncia de viagens emissora da fatura
              $sql = new db_getPersonList; $RS_Agencia = $sql->getInstanceOf($dbms, $w_cliente, $_REQUEST['w_agencia'], 'TODOS', null, null, null, null);
              foreach($RS_Agencia as $row) { $RS_Agencia = $row; break; }
              
              // Recupera a tabela de descontos da ag�ncia informada
              $sql = new db_getDescontoAgencia; $RS_Desconto = $sql->getInstanceOf($dbms,$w_cliente,null,$_REQUEST['w_agencia'],null,null,null,null,'S');
              $RS_Desconto = SortArray($RS_Desconto,'nome','asc','faixa_inicio','asc');
              
              // Varre o arquivo recebido, linha a linha
              foreach($F2[""] as $row) {
                $w_hn_cia         = false; // indica a companhia a�rea emissora do bilhete
                $w_hn_solic       = false; // indica solicita��o do bilhete
                $w_hn_bilhete     = false; // indica se foi encontrado bilhete no banco de dados
                $w_hn_desconto    = false; // indica o desconto contratual aplicado ao bilhete
                $desconto_padrao  = 0;     // percentual de desconto a ser aplicado no valor do bilhete
                $desconto_bilhete = 0;     // valor do desconto contratual do bilhete
                if ($w_cont==0) {
                  // Se for a primeira linha, recupera identifica��o do arquivo
                  $w_arquivo ='=================================================================================';                
                  $w_arquivo.=$crlf.'Resultado do processamento do arquivo '.$w_nome_recebido;
                  $w_arquivo.=$crlf.'Ag�ncia de viagens: '.f($RS_Agencia,'nome').' - CNPJ: '.f($RS_Agencia,'codigo');                
                }
                $w_linha = '';
                // Recupera o conte�do da linha
                foreach($row as $k => $v) {
                  $w_linha .= '"'.trim($v).'",';
                }
                $w_linha = substr($w_linha,0,-1);
                $w_erro     = '';
                $w_fatura     = trim($row[0]);
                // Valida o campo N�mero da Fatura
                $w_result = fValidate(1,$w_fatura,'Fatura','',1,1,30,'','0123456789.-/');
                if ($w_result>'') { 
                  $w_erro.=$crlf.'N�mero da fatura: '.$w_result; 
                } else {
                  $sql = new db_getPD_Fatura; $RS_Fatura = $sql->getInstanceOf($dbms,$w_cliente,$w_agencia,null, null, $w_fatura, null, null, null,null,
                                  null, null, null, null, null, null, null, null, null, null, null);
                  if (count($RS_Fatura)>0) {
                    foreach($RS_Fatura as $row1) { $RS_Fatura = $row1; break; } 
                    $w_erro.=$crlf.'N�mero da fatura: fatura '.f($RS_Fatura,'nr_fatura').' da ag�ncia de viagem '.f($RS_Fatura,'nm_agencia_res').' j� importada';
                  }
                }
                
                $w_inicio     = trim($row[1]);
                $w_temp = explode('/',$w_inicio); $w_inicio = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
                // Valida o campo in�cio do dec�ndio
                $w_result = fValidate(1,$w_inicio,'in�cio do dec�ndio','DATA',1,10,10,'','0123456789/');
                if ($w_result>'') { $w_erro.=$crlf.'In�cio do Dec�ndio: '.$w_result; }
                // S� pode haver um dec�ndio por fatura no arquivo
                if (isset($faturas[$w_fatura]['inicio'])) {
                  if ($faturas[$w_fatura]['inicio']!=$w_inicio) {
                   $w_erro.=$crlf.'In�cio do dec�ndio: todas as linhas da fatura devem ter o mesmo valor';
                  } 
                } else {
                  $faturas[$w_fatura]['inicio'] = $w_inicio;
                }
                
                $w_fim        = trim($row[2]);
                $w_temp = explode('/',$w_fim); $w_fim = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
                // Valida o campo fim do dec�ndio
                $w_result = fValidate(1,$w_fim,'fim do dec�ndio','DATA',1,10,10,'','0123456789/');
                if ($w_result>'') { $w_erro.=$crlf.'Fim do Dec�ndio: '.$w_result; }
                // S� pode haver um dec�ndio por fatura no arquivo
                if (isset($faturas[$w_fatura]['fim'])) {
                  if ($faturas[$w_fatura]['fim']!=$w_fim) {
                   $w_erro.=$crlf.'Fim do dec�ndio: todas as linhas da fatura devem ter o mesmo valor';
                  } 
                } else {
                  $faturas[$w_fatura]['fim'] = $w_fim;
                }
                
                $w_emissao_fat= trim($row[3]);
                $w_temp = explode('/',$w_emissao_fat); $w_emissao_fat = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
                // Valida o campo emissao da fatura
                $w_result = fValidate(1,$w_emissao_fat,'data de emiss�o da fatura','DATA',1,10,10,'','0123456789/');
                if ($w_result>'') { $w_erro.=$crlf.'Data de emiss�o da fatura: '.$w_result; }
                // S� pode haver uma emiss�o de fatura no arquivo
                if (isset($faturas[$w_fatura]['emissao'])) {
                  if ($faturas[$w_fatura]['emissao']!=$w_emissao_fat) {
                   $w_erro.=$crlf.'Data de emissao da fatura: todas as linhas da fatura devem ter o mesmo valor';
                  } 
                } else {
                  $faturas[$w_fatura]['emissao'] = $w_emissao_fat;
                }
                
                $w_venc       = trim($row[4]);
                $w_temp = explode('/',$w_venc); $w_venc = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
                // Valida o campo vencimento da fatura
                $w_result = fValidate(1,$w_venc,'data de vencimento da fatura','DATA',1,10,10,'','0123456789/');
                if ($w_result>'') { $w_erro.=$crlf.'Data de vencimento da fatura: '.$w_result; }
                // S� pode haver um vencimento de fatura no arquivo
                if (isset($faturas[$w_fatura]['vencimento'])) {
                  if ($faturas[$w_fatura]['vencimento']!=$w_venc) {
                   $w_erro.=$crlf.'Data de vencimento da fatura: todas as linhas da fatura devem ter o mesmo valor';
                  } 
                } else {
                  $faturas[$w_fatura]['vencimento'] = $w_venc;
                }
                
                $w_valor_fat  = trim($row[5]);
                if (strpos($w_valor_fat,',')===false) $w_valor_fat .= ',00';
                // Valida o campo valor da fatura
                $w_result = fValidate(1,$w_valor_fat,'valor da fatura','VALOR',1,3,18,'','0123456789,.');
                if ($w_result>'') { $w_erro.=$crlf.'Valor da fatura: '.$w_result; }
                // S� pode haver um valor para cada fatura no arquivo
                if (isset($faturas[$w_fatura]['valor'])) {
                  if ($faturas[$w_fatura]['valor']!=$w_valor_fat) {
                   $w_erro.=$crlf.'Valor da fatura: todas as linhas da fatura devem ter o mesmo valor';
                  } 
                } else {
                  $faturas[$w_fatura]['valor'] = $w_valor_fat;
                }
                
                $w_cia        = trim(upper($row[6]));
                // Valida o campo cia a�rea
                $w_result = fValidate(1,$w_cia,'cia a�rea','',1,2,20,'1','1');
                if ($w_result>'') { 
                  
                  $w_erro.=$crlf.'Cia a�rea: '.$w_result; 
                } else {
                  $sql = new db_getCiaTrans; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,null,$w_cia,'S',null,null,null,null,null,null);
                  if (count($RS)==0) {
                    $w_erro.=$crlf.'Cia a�rea: na base de dados n�o h� companhia com a sigla "'.$w_cia.'"';
                  } elseif (count($RS)>1) {
                    $w_erro.=$crlf.'Cia a�rea: h� mais de uma companhia com a sigla "'.$w_cia.'"';
                  } else {
                    foreach($RS as $row1) { $w_hn_cia = f($row1,'chave'); break; } 
                  }
                }
                
                $w_projeto    = trim($row[10]);
                // Valida o campo c�digo do projeto
                $w_result = fValidate(1,$w_projeto,'c�digo do projeto','',1,2,60,'1','1');
                if ($w_result>'') { 
                  $w_erro.=$crlf.'C�digo do projeto: '.$w_result; 
                } else {
                  // Verifica se o projeto existe
                  $RS = new db_getLinkData; $RS = $RS->getInstanceOf($dbms,$w_cliente,'PJCAD');
                  $sql = new db_getSolicList; $RS = $sql->getInstanceOf($dbms, f($RS,'sq_menu'), $w_usuario, 'PJLISTIMP', 5, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, $w_projeto, null, null, null, null, null, null, null);
                  if (count($RS)==0) {
                    $w_erro.=$crlf.'C�digo do projeto: na base de dados n�o h� projeto ativo com o c�digo "'.$w_projeto.'"';
                  }
                  foreach($RS as $row1) { $w_hn_projeto = f($row1,'sq_siw_solicitacao'); break; } 
                  if (isset($faturas[$w_fatura]['cd_projeto'])) {
                    if ($faturas[$w_fatura]['cd_projeto']!=$w_projeto) {
                     $w_erro.=$crlf.'C�digo do projeto: todas as linhas da fatura devem ter o mesmo valor';
                    } 
                  } else {
                    $faturas[$w_fatura]['cd_projeto'] = $w_projeto;
                    $faturas[$w_fatura]['projeto'] = $w_hn_projeto;
                  }
                }
                
                $w_bilhete    = trim($row[7]);
                // Valida o campo numero do bilhete
                $w_result = fValidate(1,$w_bilhete,'n�mero do bilhete','',1,1,20,'1','1');
                if ($w_result>'') { 
                  $w_erro.=$crlf.'N�mero do bilhete: '.$w_result; 
                } else {
                  // Verifica se o bilhete j� foi relacionado no arquivo
                  if (isset($bilhete_unico[$w_cia][$w_bilhete])) {
                    $w_erro.=$crlf.'N�mero do bilhete: bilhete "'.$w_bilhete.'" da cia "'.$w_cia.'" duplicado no arquivo (Linha '.$bilhete_unico[$w_cia][$w_bilhete].')'; 
                  } else {
                    $bilhete_unico[$w_cia][$w_bilhete] = $w_cont;
                  }
                  if ($w_hn_cia) {
                    // Verifica��es se a companhia a�rea for localizada na base de dados
                    $sql = new db_getPD_Bilhete; $RS_Bilhete = $sql->getInstanceOf($dbms,null,null,null,null,$w_bilhete,$w_hn_cia,'S',null);
                    $w_hn_bilhete  = '';
                    $w_hn_solic    = '';
                    $w_solicitacao = '';
                    if (count($RS_Bilhete)==0) {
                      $w_erro.=$crlf.'N�mero do bilhete: na base de dados n�o h� bilhete com o n�mero "'.$w_bilhete.'" da cia "'.$w_cia.'"';
                    } elseif (count($RS_Bilhete)>1) {
                      $w_erro.=$crlf.'N�mero do bilhete: bilhete n�mero "'.$w_bilhete.'" da cia "'.$w_cia.'" duplicado na base de dados';
                    } else {
                      foreach($RS_Bilhete as $row1) { $RS_Bil = $row1; break; } 
                      $w_hn_bilhete  = f($RS_Bil,'chave');
                      $w_hn_solic    = f($RS_Bil,'sq_siw_solicitacao');
                      $w_solicitacao = f($RS_Bil,'codigo_interno').' - '.f($RS_Bil,'nm_beneficiario');
                      if (nvl(f($RS_Bil,'cumprimento'),'')!='') {
                        $w_solicitacao = f($RS_Bil,'codigo_interno').' - '.f($RS_Bil,'nm_beneficiario').' - Fase: '.f($RS_Bil,'nm_tramite').' - Viagem alterada? '.f($RS_Bil,'nm_cumprimento');
                      }
                      $sql = new db_getPD_Fatura; $RS_FatBil = $sql->getInstanceOf($dbms,$w_cliente,null,null, null, null, null, $w_hn_cia, null,null,
                                    null, $w_bilhete, null, null, null, null, null, null, null, null, 'BILHETE');
                      if (count($RS_FatBil)>0) {
                        foreach($RS_FatBil as $row1) { $RS_FatBil = $row1; break; } 
                        $w_erro.=$crlf.'N�mero do bilhete: bilhete n�mero "'.$w_bilhete.'" da cia "'.$w_cia.'" consta da fatura '.f($RS_FatBil,'nr_fatura').' da ag�ncia de viagem '.f($RS_FatBil,'nm_agencia_res');
                      }
                      if (f($RS_Bil,'cd_pai')!=$faturas[$w_fatura]['cd_projeto']) {
                        $w_erro.=$crlf.'N�mero do bilhete: '.f($RS_Bil,'codigo_interno').' est� vinculada ao projeto '.f($RS_Bil,'cd_pai').', divergindo do projeto da fatura ('.$faturas[$w_fatura]['cd_projeto'].')';
                      }
                    }
                  }
                }
                
                $w_emissao_bil= trim($row[8]);
                $w_temp = explode('/',$w_emissao_bil); $w_emissao_bil = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
                // Valida o campo data de emiss�o do bilhete
                $w_result = fValidate(1,$w_emissao_bil,'data de emiss�o do bilhete','DATA',1,10,10,'','0123456789/');
                if ($w_result>'') { 
                  $w_erro.=$crlf.'Data de emiss�o do bilhete: '.$w_result; 
                } else {
                  $w_temp = toDate($w_emissao_bil);
                  if ($w_hn_bilhete) {
                    if (f($RS_Bil,'data')!=$w_temp) {
                      $w_erro.=$crlf.'Data de emiss�o do bilhete ('.$w_emissao_bil.'): valor constante do arquivo diverge do valor registrado na base de dados ('.formataDataEdicao(f($RS_Bil,'data')).')'; 
                    }
                  }
                }
                
                $w_trechos    = trim($row[9]);
                // Valida o campo trechos
                $w_result = fValidate(1,$w_trechos,'trechos','',1,3,60,'1','1');
                if ($w_result>'') { $w_erro.=$crlf.'Trechos: '.$w_result; }
                
                $w_valor_pleno= trim($row[11]);
                if (strpos($w_valor_pleno,',')===false) $w_valor_pleno .= ',00';
                // Valida o campo valor pleno do bilhete
                $w_result = fValidate(1,$w_valor_pleno,'valor pleno do bilhete','VALOR',1,3,18,'','0123456789,.');
                if ($w_result>'') { 
                  $w_erro.=$crlf.'Valor pleno do bilhete: '.$w_result; 
                } elseif ($w_hn_bilhete) {
                  if (formatNumber(f($RS_Bil,'valor_bilhete'))!=$w_valor_pleno) {
                    $w_erro.=$crlf.'Valor pleno do bilhete: valor constante do arquivo ('.$w_valor_pleno.') diverge do valor registrado na base de dados ('.formatNumber(f($RS_Bil,'valor_bilhete_cheio')).')'; 
                  }
                }
                
                // Recupera o valor do bilhete aqui para poder calcular o percentual de desconto
                $w_valor_bil  = trim($row[13]);
                if (strpos($w_valor_bil,',')===false) $w_valor_bil .= ',00';
                
                $w_desconto   = trim(str_replace('%','',$row[12]));
                if (strpos($w_desconto,',')===false) $w_desconto .= ',00';
                // Valida o campo percentual de desconto
                $w_result = fValidate(1,$w_desconto,'percentual de desconto','VALOR',1,3,18,'','0123456789,.');
                if ($w_result>'') { 
                  $w_erro.=$crlf.'Percentual de desconto: '.$w_result; 
                } else {
                  if (count($RS_Desconto)==0) {
                    $w_erro.=$crlf.'Percentual de desconto: n�o h� tabela ativa de desconto cadastrada para a ag�ncia de viagens emissora da fatura'; 
                  } elseif (count($RS_Desconto)==1) {
                    foreach($RS_Desconto as $row1) {
                      $w_hn_desconto   = f($row1,'chave');
                      $desconto_padrao = f($row1,'desconto');
                    }
                  } else {
                    if (toNumberPHP($w_valor_pleno)>0) {
                      $desconto = round(100*(1-(toNumberPHP($w_valor_bil)/toNumberPHP($w_valor_pleno))),2);
                    } else {
                      $desconto = 0;
                    }
                    if (formatNumber($desconto)!=$w_desconto) {
                      // Verifica se o desconto est� correto
                      $w_erro.=$crlf.'Percentual de desconto: valor constante do arquivo ('.$w_desconto.') diverge do valor calculado ('.formatNumber($desconto).')';
                    } elseif (toNumberPHP($w_valor_pleno)>0) {
                      // Recupera o desconto contratual a ser aplicado para o bilhete
                      foreach($RS_Desconto as $row1) {
                        if ($desconto>=f($row1,'faixa_inicio') && $desconto<=f($row1,'faixa_fim')) {
                          $w_hn_desconto   = f($row1,'chave');
                          $desconto_padrao = f($row1,'desconto');
                        }
                      }
                    }
                  }
                }
                
                // Valida o campo valor do bilhete
                $w_result = fValidate(1,$w_valor_bil,'valor do bilhete','VALOR',1,3,18,'','0123456789,.');
                if ($w_result>'') { 
                  $w_erro.=$crlf.'Valor do bilhete: '.$w_result; 
                } elseif ($w_hn_bilhete) {
                  if (formatNumber(f($RS_Bil,'valor_bilhete'))!=$w_valor_bil) {
                    $w_erro.=$crlf.'Valor do bilhete: valor constante do arquivo ('.$w_valor_bil.') diverge do valor registrado na base de dados ('.formatNumber(f($RS_Bil,'valor_bilhete')).') '.f($RS_Bil,'chave'); 
                  }
                }
                
                $w_ret_tarifa = trim($row[14]);
                if (strpos($w_ret_tarifa,',')===false) $w_ret_tarifa .= ',00';
                // Valida o campo valor da tarifa retornado
                $w_result = fValidate(1,$w_ret_tarifa,'valor retido da tarifa','VALOR',1,3,18,'','0123456789,.');
                if ($w_result>'') { $w_erro.=$crlf.'Valor retido da tarifa: '.$w_result; }
                
                $w_taxa       = trim($row[15]);
                if (strpos($w_taxa,',')===false) $w_taxa .= ',00';
                // Valida o campo valor da taxa
                $w_result = fValidate(1,$w_taxa,'valor da taxa','VALOR',1,3,18,'','0123456789,.');
                if ($w_result>'') { 
                  $w_erro.=$crlf.'Valor da taxa: '.$w_result; 
                } elseif ($w_hn_bilhete) {
                  if (formatNumber(f($RS_Bil,'valor_taxa_embarque')+f($RS_Bil,'valor_pta'))!=$w_taxa) {
                    $w_erro.=$crlf.'Valor da taxa: valor constante do arquivo ('.$w_taxa.') diverge do valor registrado na base de dados ('.formatNumber(f($RS_Bil,'valor_taxa_embarque')+f($RS_Bil,'valor_pta')).')'; 
                  }
                }
                
                $w_ret_taxa   = trim($row[16]);
                if (strpos($w_ret_taxa,',')===false) $w_ret_taxa .= ',00';
                // Valida o campo valor da taxa retornado
                $w_result = fValidate(1,$w_ret_taxa,'valor retido da taxa','VALOR',1,3,18,'','0123456789,.');
                if ($w_result>'') { $w_erro.=$crlf.'Valor retido da taxa: '.$w_result; }
                
                $w_desc_contr = trim($row[17]);
                if (strpos($w_desc_contr,',')===false) $w_desc_contr .= ',00';
                // Valida o campo valor do desconto contratual
                $w_result = fValidate(1,$w_desc_contr,'valor do desconto contratual','VALOR',1,3,18,'','0123456789,.');
                if ($w_result>'') { 
                  $w_erro.=$crlf.'Valor do desconto contratual: '.$w_result; 
                } else {
                  $desconto_bilhete = round((toNumberPHP($w_valor_bil) * $desconto_padrao / 100),2);
                  if ($w_desc_contr!=formatNumber($desconto_bilhete)) {
                    $w_erro.=$crlf.'Valor do desconto contratual: valor constante do arquivo ('.$w_desc_contr.') diverge do valor calculado ('.formatNumber($desconto_bilhete).')'; 
                  }
                }
                
                $w_valor_total= trim($row[18]);
                if (strpos($w_valor_total,',')===false) $w_valor_total .= ',00';
                // Valida o campo valor total do bilhete
                $w_result = fValidate(1,$w_valor_total,'valor total do bilhete','VALOR',1,3,18,'','0123456789,.');
                if ($w_result>'') { 
                  $w_erro.=$crlf.'Valor total do bilhete: '.$w_result; 
                } elseif ($w_hn_bilhete) {
                  $valor_total = formatNumber(f($RS_Bil,'valor_bilhete')-$desconto_bilhete+f($RS_Bil,'valor_taxa_embarque')+f($RS_Bil,'valor_pta'));
                  if ($valor_total!=$w_valor_total) {
                    $w_erro.=$crlf.'Valor total do bilhete: valor constante do arquivo ('.$w_valor_total.') diverge do valor calculado ('.$valor_total.')'; 
                  }
                }
                
                // Monta array de impressao
                $linhas[$w_cont]['fatura'] = $w_fatura;
                $linhas[$w_cont]['bilhete'] = $w_bilhete;
                $linhas[$w_cont]['solicitacao'] = $w_solicitacao;
                $linhas[$w_cont]['conteudo'] = $w_linha;
                $linhas[$w_cont]['erro'] = substr($w_erro,2);
                
                // Guarda dados para grava��o
                $faturas[$w_fatura]['fatura'] = $w_fatura;
                if ($w_erro!='') $faturas[$w_fatura]['erro'] = 'erro';
                
                $bilhetes[$w_fatura][$w_bilhete]['solicitacao'] = $w_hn_solic;
                $bilhetes[$w_fatura][$w_bilhete]['desconto'] = $w_hn_desconto;
                $bilhetes[$w_fatura][$w_bilhete]['numero'] = $w_bilhete;
                $bilhetes[$w_fatura][$w_bilhete]['cia'] = $w_hn_cia;
                $bilhetes[$w_fatura][$w_bilhete]['emissao'] = $w_emissao_bil;
                $bilhetes[$w_fatura][$w_bilhete]['trecho'] = $w_trechos;
                $bilhetes[$w_fatura][$w_bilhete]['valor_cheio'] = $w_valor_pleno;
                $bilhetes[$w_fatura][$w_bilhete]['valor'] = $w_valor_bil;
                $bilhetes[$w_fatura][$w_bilhete]['embarque'] = $w_taxa;
                $bilhetes[$w_fatura][$w_bilhete]['cia'] = $w_hn_cia;
                $bilhetes[$w_fatura][$w_bilhete]['erro'] = substr($w_erro,2);
                
                $w_cont      += 1;
                $w_registros += 1;
                if ($w_erro=='')  {
                  $faturas[$w_fatura]['aceitos'] = nvl($faturas[$w_fatura]['aceitos'],0) + 1;
                  $w_importados++;
                } else {
                  $faturas[$w_fatura]['rejeitados'] = nvl($faturas[$w_fatura]['rejeitados'],0) + 1;
                  $w_rejeitados += 1;
                }
              }
            }
            if (is_array($faturas)) {
              foreach($faturas as $row) {
                $w_fatura = f($row,'fatura');
                $fatura_grava[$w_fatura]['fatura'] = $w_fatura;
                $fatura_grava[$w_fatura]['projeto'] = f($row,'projeto');
                $fatura_grava[$w_fatura]['inicio'] = f($row,'inicio');
                $fatura_grava[$w_fatura]['fim'] = f($row,'fim');
                $fatura_grava[$w_fatura]['emissao'] = f($row,'emissao');
                $fatura_grava[$w_fatura]['vencimento'] = f($row,'vencimento');
                $fatura_grava[$w_fatura]['valor'] = f($row,'valor');
                $fatura_grava[$w_fatura]['aceitos'] = f($row,'aceitos');
                $fatura_grava[$w_fatura]['rejeitados'] = f($row,'rejeitados');
                $fatura_grava[$w_fatura]['erro'] = f($row,'erro');
                
                foreach($bilhetes[$w_fatura] as $row1) {
                  $w_bilhete = f($row1,'numero');
                  $bilhete_grava[$w_fatura][$w_bilhete]['fatura'] = $w_fatura;
                  $bilhete_grava[$w_fatura][$w_bilhete]['solicitacao'] = f($row1,'solicitacao');
                  $bilhete_grava[$w_fatura][$w_bilhete]['cia'] = f($row1,'cia');
                  $bilhete_grava[$w_fatura][$w_bilhete]['desconto'] = f($row1,'desconto');
                  $bilhete_grava[$w_fatura][$w_bilhete]['emissao'] = f($row1,'emissao');
                  $bilhete_grava[$w_fatura][$w_bilhete]['numero'] = f($row1,'numero');
                  $bilhete_grava[$w_fatura][$w_bilhete]['trecho'] = f($row1,'trecho');
                  $bilhete_grava[$w_fatura][$w_bilhete]['valor_cheio'] = f($row1,'valor_cheio');
                  $bilhete_grava[$w_fatura][$w_bilhete]['valor'] = f($row1,'valor');
                  $bilhete_grava[$w_fatura][$w_bilhete]['embarque'] = f($row1,'embarque');
                  $bilhete_grava[$w_fatura][$w_bilhete]['erro'] = f($row1,'erro');
                }
              }
            }
          } else { // Faturas de hospedagens, loca��es de ve�culos e seguros viagem
            if (is_array($F2[""])) {
              // Recupera dados da ag�ncia de viagens emissora da fatura
              $sql = new db_getPersonList; $RS_Agencia = $sql->getInstanceOf($dbms, $w_cliente, $_REQUEST['w_agencia'], 'TODOS', null, null, null, null);
              foreach($RS_Agencia as $row) { $RS_Agencia = $row; break; }
              
              // Varre o arquivo recebido, linha a linha
              foreach($F2[""] as $row) {
                $w_hn_solic       = false; // indica solicita��o do bilhete
                if ($w_cont==0) {
                  // Se for a primeira linha, recupera identifica��o do arquivo
                  $w_arquivo ='=================================================================================';                
                  $w_arquivo.=$crlf.'Resultado do processamento do arquivo '.$w_nome_recebido;
                  $w_arquivo.=$crlf.'Ag�ncia de viagens: '.f($RS_Agencia,'nome').' - CNPJ: '.f($RS_Agencia,'codigo');                
                }
                $w_linha = '';
                // Recupera o conte�do da linha
                foreach($row as $k => $v) {
                  $w_linha .= '"'.trim($v).'",';
                }
                $w_linha = substr($w_linha,0,-1);
                $w_erro     = '';

                $w_tipo     = trim($row[0]);
                // Valida o tipo do registro
                $w_result = fValidate(1,$w_tipo,'Tipo do registro','',1,1,1,'','123');
                if ($w_result>'') { 
                  $w_erro.=$crlf.'Tipo do registro: '.$w_result; 
                }
                
                $w_fatura     = trim($row[1]);
                // Valida o campo N�mero da Fatura
                $w_result = fValidate(1,$w_fatura,'Fatura','',1,1,30,'','0123456789.-/');
                if ($w_result>'') { 
                  $w_erro.=$crlf.'N�mero da fatura: '.$w_result; 
                } else {
                  $sql = new db_getPD_Fatura; $RS_Fatura = $sql->getInstanceOf($dbms,$w_cliente,$w_agencia,null, null, $w_fatura, null, null, null,null,
                                  null, null, null, null, null, null, null, null, null, null, 'OUTROS');
                  if (count($RS_Fatura)>0) {
                    foreach($RS_Fatura as $row1) { $RS_Fatura = $row1; break; } 
                    $w_erro.=$crlf.'N�mero da fatura: fatura '.f($RS_Fatura,'nr_fatura').' da ag�ncia de viagem '.f($RS_Fatura,'nm_agencia_res').' j� importada';
                  }
                }
                
                $w_inicio     = trim($row[2]);
                $w_temp = explode('/',$w_inicio); $w_inicio = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
                // Valida o campo in�cio do dec�ndio
                $w_result = fValidate(1,$w_inicio,'in�cio do dec�ndio','DATA',1,10,10,'','0123456789/');
                if ($w_result>'') { $w_erro.=$crlf.'In�cio do Dec�ndio: '.$w_result; }
                // S� pode haver um dec�ndio por fatura no arquivo
                if (isset($faturas[$w_fatura]['inicio'])) {
                  if ($faturas[$w_fatura]['inicio']!=$w_inicio) {
                   $w_erro.=$crlf.'In�cio do dec�ndio: todas as linhas da fatura devem ter o mesmo valor';
                  } 
                } else {
                  $faturas[$w_fatura]['inicio'] = $w_inicio;
                }
                
                $w_fim        = trim($row[3]);
                $w_temp = explode('/',$w_fim); $w_fim = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
                // Valida o campo fim do dec�ndio
                $w_result = fValidate(1,$w_fim,'fim do dec�ndio','DATA',1,10,10,'','0123456789/');
                if ($w_result>'') { $w_erro.=$crlf.'Fim do Dec�ndio: '.$w_result; }
                // S� pode haver um dec�ndio por fatura no arquivo
                if (isset($faturas[$w_fatura]['fim'])) {
                  if ($faturas[$w_fatura]['fim']!=$w_fim) {
                   $w_erro.=$crlf.'Fim do dec�ndio: todas as linhas da fatura devem ter o mesmo valor';
                  } 
                } else {
                  $faturas[$w_fatura]['fim'] = $w_fim;
                }
                
                $w_emissao_fat= trim($row[4]);
                $w_temp = explode('/',$w_emissao_fat); $w_emissao_fat = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
                // Valida o campo emissao da fatura
                $w_result = fValidate(1,$w_emissao_fat,'data de emiss�o da fatura','DATA',1,10,10,'','0123456789/');
                if ($w_result>'') { $w_erro.=$crlf.'Data de emiss�o da fatura: '.$w_result; }
                // S� pode haver uma emiss�o de fatura no arquivo
                if (isset($faturas[$w_fatura]['emissao'])) {
                  if ($faturas[$w_fatura]['emissao']!=$w_emissao_fat) {
                   $w_erro.=$crlf.'Data de emissao da fatura: todas as linhas da fatura devem ter o mesmo valor';
                  } 
                } else {
                  $faturas[$w_fatura]['emissao'] = $w_emissao_fat;
                }
                
                $w_venc       = trim($row[5]);
                $w_temp = explode('/',$w_venc); $w_venc = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
                // Valida o campo vencimento da fatura
                $w_result = fValidate(1,$w_venc,'data de vencimento da fatura','DATA',1,10,10,'','0123456789/');
                if ($w_result>'') { $w_erro.=$crlf.'Data de vencimento da fatura: '.$w_result; }
                // S� pode haver um vencimento de fatura no arquivo
                if (isset($faturas[$w_fatura]['vencimento'])) {
                  if ($faturas[$w_fatura]['vencimento']!=$w_venc) {
                   $w_erro.=$crlf.'Data de vencimento da fatura: todas as linhas da fatura devem ter o mesmo valor';
                  } 
                } else {
                  $faturas[$w_fatura]['vencimento'] = $w_venc;
                }
                
                $w_valor_fat  = trim($row[6]);
                if (strpos($w_valor_fat,',')===false) $w_valor_fat .= ',00';
                // Valida o campo valor da fatura
                $w_result = fValidate(1,$w_valor_fat,'valor da fatura','VALOR',1,3,18,'','0123456789,.');
                if ($w_result>'') { $w_erro.=$crlf.'Valor da fatura: '.$w_result; }
                // S� pode haver um valor para cada fatura no arquivo
                if (isset($faturas[$w_fatura]['valor'])) {
                  if ($faturas[$w_fatura]['valor']!=$w_valor_fat) {
                   $w_erro.=$crlf.'Valor da fatura: todas as linhas da fatura devem ter o mesmo valor';
                  } 
                } else {
                  $faturas[$w_fatura]['valor'] = $w_valor_fat;
                }
                
                $w_projeto    = trim($row[7]);
                // Valida o campo c�digo do projeto
                $w_result = fValidate(1,$w_projeto,'c�digo do projeto','',1,2,60,'1','1');
                if ($w_result>'') { 
                  $w_erro.=$crlf.'C�digo do projeto: '.$w_result; 
                } else {
                  // Verifica se o projeto existe
                  $RS = new db_getLinkData; $RS = $RS->getInstanceOf($dbms,$w_cliente,'PJCAD');
                  $sql = new db_getSolicList; $RS = $sql->getInstanceOf($dbms, f($RS,'sq_menu'), $w_usuario, 'PJLISTIMP', 5, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, $w_projeto, null, null, null, null, null, null, null);
                  if (count($RS)==0) {
                    $w_erro.=$crlf.'C�digo do projeto: na base de dados n�o h� projeto ativo com o c�digo "'.$w_projeto.'"';
                  }
                  foreach($RS as $row1) { $w_hn_projeto = f($row1,'sq_siw_solicitacao'); break; } 
                  if (isset($faturas[$w_fatura]['cd_projeto'])) {
                    if ($faturas[$w_fatura]['cd_projeto']!=$w_projeto) {
                     $w_erro.=$crlf.'C�digo do projeto: todas as linhas da fatura devem ter o mesmo valor';
                    } 
                  } else {
                    $faturas[$w_fatura]['cd_projeto'] = $w_projeto;
                    $faturas[$w_fatura]['projeto'] = $w_hn_projeto;
                  }
                }
                
                $w_solic      = trim($row[8]);
                $w_temp       = explode('/',$w_solic);
                $w_solic      = 'SV-'.intVal($w_temp[0]).'/'.$w_temp[1];
                // Valida o campo C�digo da viagem
                $w_result = fValidate(1,$w_solic,'C�digo da viagem','',1,1,60,'1','1');
                if ($w_result>'') { 
                  $w_erro.=$crlf.'C�digo da viagem: '.$w_result; 
                } else {
                  // Verifica se o c�digo da viagem existe
                  $RS = new db_getLinkData; $RS = $RS->getInstanceOf($dbms,$w_cliente,'PDINICIAL');
                  $sql = new db_getSolicList; $RS = $sql->getInstanceOf($dbms, f($RS,'sq_menu'), $w_usuario, f($RS,'sigla'), 5, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, $w_solic, null);
                  $w_hn_solic    = '';
                  $w_solicitacao = '';
                  if (count($RS)==0) {
                    $w_erro.=$crlf.'C�digo da viagem: na base de dados n�o h� viagem com o c�digo "'.$w_solic.'"';
                  } else {
                    foreach($RS as $row1) { $RS = $row1; break; } 
                    $w_hn_solic    = f($RS,'sq_siw_solicitacao');
                    $w_solicitacao = f($RS,'codigo_interno').' - '.f($RS,'nm_prop').' - Fase: '.f($RS,'nm_tramite').' - Viagem alterada? '.f($RS,'nm_cumprimento');
                    $l_array       = explode('|@|', f($RS,'dados_pai'));
                    $l_cd_pai      = $l_array[1];
                    if ($l_cd_pai!=$faturas[$w_fatura]['cd_projeto']) {
                      $w_erro.=$crlf.'C�digo da viagem: '.f($RS,'codigo_interno').' est� vinculada ao projeto '.$l_cd_pai.', divergindo do projeto da fatura ('.$faturas[$w_fatura]['cd_projeto'].')';
                    }
                  }
                }
                
                $w_cnpj       = trim($row[9]);
                // Valida o campo CNPJ
                $w_result = fValidate(1,$w_cnpj,'CNPJ','CNPJ',1,1,18,'','0123456789.-/');
                if ($w_result>'') { $w_erro.=$crlf.'CNPJ: '.$w_result; }
                
                $w_nome       = substr(trim($row[10]),0,60);
                // Valida o campo Raz�o Social
                $w_result = fValidate(1,$w_nome,'Raz�o Social','',1,1,60,'1','1');
                if ($w_result>'') { $w_erro.=$crlf.'Raz�o Social: '.$w_result; }
                
                $w_inicio_reg = trim($row[11]);
                $w_temp = explode('/',$w_inicio_reg); $w_inicio_reg = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
                // Valida o campo In�cio da Hospedagem/Loca��o/Seguro
                $w_result = fValidate(1,$w_inicio_reg,'in�cio do per�odo','DATA',1,10,10,'','0123456789/');
                if ($w_result>'') { $w_erro.=$crlf.'In�cio do per�odo: '.$w_result; }
                
                $w_fim_reg    = trim($row[12]);
                $w_temp = explode('/',$w_fim_reg); $w_fim_reg = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
                // Valida o campo fim_reg da Hospedagem/Loca��o/Seguro
                $w_result = fValidate(1,$w_fim_reg,'fim do per�odo','DATA',1,10,10,'','0123456789/');
                if ($w_result>'') { $w_erro.=$crlf.'Fim do per�odo: '.$w_result; }
                
                $w_valor_reg= trim($row[13]);
                if (strpos($w_valor_reg,',')===false) $w_valor_reg .= ',00';
                // Valida o campo valor do registro
                $w_result = fValidate(1,$w_valor_reg,'valor','VALOR',1,3,18,'','0123456789,.');
                if ($w_result>'') { $w_erro.=$crlf.'Valor: '.$w_result; }
                
                // Monta array de impressao
                $linhas[$w_cont]['fatura'] = $w_fatura;
                $linhas[$w_cont]['solicitacao'] = $w_solicitacao;
                $linhas[$w_cont]['conteudo'] = $w_linha;
                $linhas[$w_cont]['erro'] = substr($w_erro,2);
                
                // Guarda dados para grava��o
                $faturas[$w_fatura]['fatura'] = $w_fatura;
                if ($w_erro!='') $faturas[$w_fatura]['erro'] = 'erro';
                
                $bilhetes[$w_fatura][$w_cont]['solicitacao'] = $w_hn_solic;
                $bilhetes[$w_fatura][$w_cont]['tipo'] = $w_tipo;
                $bilhetes[$w_fatura][$w_cont]['numero'] = $w_cont;
                $bilhetes[$w_fatura][$w_cont]['inicio'] = $w_inicio_reg;
                $bilhetes[$w_fatura][$w_cont]['fim'] = $w_fim_reg;
                $bilhetes[$w_fatura][$w_cont]['valor'] = $w_valor_reg;
                $bilhetes[$w_fatura][$w_cont]['cnpj'] = $w_cnpj;
                $bilhetes[$w_fatura][$w_cont]['nome'] = $w_nome;
                $bilhetes[$w_fatura][$w_cont]['erro'] = substr($w_erro,2);
                
                $w_cont      += 1;
                $w_registros += 1;
                if ($w_erro=='')  {
                  $faturas[$w_fatura]['aceitos'] = nvl($faturas[$w_fatura]['aceitos'],0) + 1;
                  $w_importados++;
                } else {
                  $faturas[$w_fatura]['rejeitados'] = nvl($faturas[$w_fatura]['rejeitados'],0) + 1;
                  $w_rejeitados += 1;
                }
              }
            }
            if (is_array($faturas)) {
              foreach($faturas as $row) {
                $w_fatura = f($row,'fatura');
                $fatura_grava[$w_fatura]['fatura'] = $w_fatura;
                $fatura_grava[$w_fatura]['projeto'] = f($row,'projeto');
                $fatura_grava[$w_fatura]['inicio'] = f($row,'inicio');
                $fatura_grava[$w_fatura]['fim'] = f($row,'fim');
                $fatura_grava[$w_fatura]['emissao'] = f($row,'emissao');
                $fatura_grava[$w_fatura]['vencimento'] = f($row,'vencimento');
                $fatura_grava[$w_fatura]['valor'] = f($row,'valor');
                $fatura_grava[$w_fatura]['aceitos'] = f($row,'aceitos');
                $fatura_grava[$w_fatura]['rejeitados'] = f($row,'rejeitados');
                $fatura_grava[$w_fatura]['erro'] = f($row,'erro');
                
                foreach($bilhetes[$w_fatura] as $row1) {
                  $w_bilhete = f($row1,'numero');
                  $bilhete_grava[$w_fatura][$w_bilhete]['fatura'] = $w_fatura;
                  $bilhete_grava[$w_fatura][$w_bilhete]['solicitacao'] = f($row1,'solicitacao');
                  $bilhete_grava[$w_fatura][$w_bilhete]['tipo'] = f($row1,'tipo');
                  $bilhete_grava[$w_fatura][$w_bilhete]['inicio'] = f($row1,'inicio');
                  $bilhete_grava[$w_fatura][$w_bilhete]['fim'] = f($row1,'fim');
                  $bilhete_grava[$w_fatura][$w_bilhete]['numero'] = f($row1,'numero');
                  $bilhete_grava[$w_fatura][$w_bilhete]['valor'] = f($row1,'valor');
                  $bilhete_grava[$w_fatura][$w_bilhete]['cnpj'] = f($row1,'cnpj');
                  $bilhete_grava[$w_fatura][$w_bilhete]['nome'] = f($row1,'nome');
                  $bilhete_grava[$w_fatura][$w_bilhete]['erro'] = f($row1,'erro');
                }
              }
            }
          }
          // Gera o arquivo registro da importa��o
          $F1 = fopen($w_caminho.$w_caminho_registro, 'w');
          fwrite($F1,$w_arquivo);
          if (is_array($fatura_grava)) {
            fwrite($F1,$crlf.'---------------------------------------------------------------------------------');
            fwrite($F1,$crlf.'    FATURA          BILHETES               ACEITOS             REJEITADOS');
            fwrite($F1,$crlf.'---------------------------------------------------------------------------------');
            foreach($fatura_grava as $row1) {
              fwrite($F1,$crlf.str_pad(f($row1,'fatura'),10,' ',STR_PAD_LEFT));
              fwrite($F1,str_pad(nvl(f($row1,'aceitos'),0)+nvl(f($row1,'rejeitados'),0),18,' ',STR_PAD_LEFT));
              fwrite($F1,str_pad(nvl(f($row1,'aceitos'),0),22,' ',STR_PAD_LEFT));
              fwrite($F1,str_pad(nvl(f($row1,'rejeitados'),0),23,' ',STR_PAD_LEFT));
            }
            fwrite($F1,$crlf.'                  ---------------------------------------------------------------');
            fwrite($F1,$crlf.'    TOTAIS');
            fwrite($F1,str_pad(nvl($w_importados,0)+nvl($w_rejeitados,0),18,' ',STR_PAD_LEFT));
            fwrite($F1,str_pad(nvl($w_importados,0),22,' ',STR_PAD_LEFT));
            fwrite($F1,str_pad(nvl($w_rejeitados,0),23,' ',STR_PAD_LEFT));
            fwrite($F1,$crlf.'=================================================================================');
          }
          if (is_array($linhas)) {
            for ($i=0;$i<$w_cont;$i++) {
              fwrite($F1,$crlf.$crlf.'[Linha '.($i+1).'] '.$linhas[$i]['conteudo']);
              fwrite($F1,$crlf.'Fatura: '.$linhas[$i]['fatura']);
              if (nvl($linhas[$i]['solicitacao'],'')!='') fwrite($F1,' - '.$linhas[$i]['solicitacao']);
              fwrite($F1,$crlf.$linhas[$i]['erro']);
            }
          }
          // Configura o valor dos campos necess�rios para grava��o
          $w_arquivo_registro   = 'registro.txt';
          $w_tamanho_registro   = filesize($w_caminho.$w_caminho_registro);
          $w_tipo_registro  = 'text/plain';
          // Grava o resultado da importa��o no banco de dados
          $SQL = new dml_putPDImportacao; $SQL->getInstanceOf($dbms,$O,
                $_REQUEST['w_chave'],$w_cliente,$w_usuario,$_REQUEST['w_tipo'],$_REQUEST['w_data_arquivo'],
                $w_nome_recebido,$w_caminho_recebido,$w_tamanho_recebido,$w_tipo_recebido,
                $w_arquivo_registro,$w_caminho_registro,$w_tamanho_registro,$w_tipo_registro,
                $w_registros,$w_importados,$w_rejeitados,$w_nome_recebido,$w_arquivo_registro,
                &$w_chave_arq);
          
          foreach($fatura_grava as $row1) {
            //Grava cada uma das faturas sem erro
            if (nvl(f($row1,'erro'),'')=='') {
              $w_fatura = f($row1,'fatura');
              $SQL = new dml_putPD_Fatura; $SQL->getInstanceOf($dbms,'I',
                  null,$w_chave_arq,$_REQUEST['w_agencia'],$_REQUEST['w_tipo'],f($row1,'fatura'),f($row1,'inicio'),f($row1,'fim'),
                  f($row1,'emissao'),f($row1,'vencimento'),f($row1,'valor'),nvl(f($row1,'aceitos'),0)+nvl(f($row1,'rejeitados'),0),
                  nvl(f($row1,'aceitos'),0),nvl(f($row1,'rejeitados'),0),&$w_chave_fatura);
                    
              foreach($bilhete_grava[$w_fatura] as $row2) {
                if ($_REQUEST['w_tipo']==0) {
                  // Grava bilhetes a�reos
                  $SQL = new dml_putPD_Bilhete; $SQL->getInstanceOf($dbms,'I',f($row2,'solicitacao'),null,f($row2,'cia'),$w_chave_fatura,f($row2,'desconto'),
                    f($row2,'emissao'),f($row2,'numero'),f($row2,'trecho'),null,null,f($row2,'valor'),f($row2,'valor_cheio'),
                    f($row2,'embarque'),0,null,'P','S','N',f($row2,'erro'));
                } else {
                // Grava hospedagens, loca��es e seguros
                  $SQL = new dml_putPD_Fatura_Outros; $SQL->getInstanceOf($dbms,'I', $w_cliente, null, f($row2,'solicitacao'), $w_chave_fatura,
                      f($row2,'tipo'),f($row2,'cnpj'),f($row2,'nome'),f($row2,'inicio'),f($row2,'fim'),f($row2,'valor'));
                }
              }
            }
          }
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
          ScriptClose();
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATEN��O: ocorreu um erro na transfer�ncia do arquivo. Tente novamente!\');');
          ScriptClose();
        } 
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    default:
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Bloco de dados n�o encontrado: '.$SG.'\');');
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
  case 'INICIAL': Inicial();  break;
  case 'HELP':    Help();     break;
  case 'GRAVA':   Grava();    break;
  default:
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=this.focus();');
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    Estrutura_Texto_Abre();
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Estrutura_Texto_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
  break;
  } 
} 
?>