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
include_once($w_dir_volta.'classes/sp/db_getPDImportacao.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putPDImportacao.php');
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
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);
// Carrega vari�veis locais com os dados dos par�metros recebidos
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
$w_pagina       = 'conciliacao.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_pd/';
$w_troca        = $_REQUEST['w_troca'];
// Configura o caminho para grava��o f�sica de arquivos<u></u>
$p_responsavel  = strtoupper($_REQUEST['p_responsavel']);
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
$RS = db_getLinkSubMenu::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu = 'Existe';
} else {
  $w_submenu = '';
} 
// Recupera a configura��o do servi�o
if ($P2>0) {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,$P2);
} else {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,$w_menu);
}
// Se for sub-menu, pega a configura��o do pai
if (f($RS_Menu,'ultimo_nivel')=='S') { 
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
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
    $w_data_arquivo     = $_REQUEST['w_data_arquivo'];
    $w_arquivo_recebido = $_REQUEST['w_arquivo_recebido'];
    $w_arquivo_registro = $_REQUEST['w_arquivo_registro'];
    $w_registros        = $_REQUEST['w_registros'];
    $w_importados       = $_REQUEST['w_importados'];
    $w_rejeitados       = $_REQUEST['w_rejeitados'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getPDImportacao::getInstanceOf($dbms,$w_chave,$w_cliente,$p_responsavel,$p_dt_ini,$p_dt_fim,$p_imp_ini,$p_imp_fim);
    $RS = SortArray($RS,'phpdt_data_arquivo','desc','phpdt_data_importacao','desc');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IP',$O)===false)) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataDataHora();
    SaltaCampo();
    ValidateOpen('Validacao');
    if (!(strpos('I',$O)===false)) {
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
  elseif (!(strpos('I',$O)===false))    BodyOpen('onLoad=\'document.Form.w_data_arquivo.focus()\';');
  else                                  BodyOpen('onLoad=\'this.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td><font size="1">');
    ShowHTML('        <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('        <a accesskey="O" class="SS" href="'.$w_dir.$w_pagina.'Help&R='.$w_pagina.$par.'&O=O&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" target="help"><u>O</u>rienta��es</a>&nbsp;');
    ShowHTML('    <td align="right"><font size="1"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan=2><font size="1"><b>Data</font></td>');
    ShowHTML('          <td rowspan=2><font size="1"><b>Executado em</font></td>');
    ShowHTML('          <td rowspan=2><font size="1"><b>Respons�vel</font></td>');
    ShowHTML('          <td colspan=3><font size="1"><b>Registros</font></td>');
    ShowHTML('          <td rowspan=2><font size="1"><b>Opera��es</font></td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>Total</font></td>');
    ShowHTML('          <td><font size="1"><b>Aceitos</font></td>');
    ShowHTML('          <td><font size="1"><b>Rejeitados</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font size="1"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center"><font size="1">'.FormataDataEdicao(f($row,'phpdt_data_arquivo'),3).'</td>');
        ShowHTML('        <td align="center"><font size="1">'.FormataDataEdicao(f($row,'phpdt_data_importacao'),3).'</td>');
        ShowHTML('        <td title="'.f($row,'nm_resp').'"><font size="1">'.f($row,'nm_resumido_resp').'</td>');
        ShowHTML('        <td align="right"><font size="1">'.f($row,'registros').'&nbsp;</td>');
        ShowHTML('        <td align="right"><font size="1">'.f($row,'importados').'&nbsp;</td>');
        ShowHTML('        <td align="right"><font size="1">'.f($row,'rejeitados').'&nbsp;</td>');
        ShowHTML('        <td align="top" nowrap><font size="1">');
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
      $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
      ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b><font color="#BC3131">ATEN��O</font>: o tamanho m�ximo aceito para o arquivo � de '.(f($RS,'upload_maximo')/1024).' KBytes</b>.</font></td>');
      ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
    } 
    ShowHTML('      <tr><td><font size="1"><b><u>D</u>ata/hora extra��o:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data_arquivo" class="sti" SIZE="17" MAXLENGTH="17" VALUE="'.$w_data_arquivo.'"  onKeyDown="FormataDataHora(this, event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);" title="OBRIGAT�RIO. Informe a data e hora da extra��o do aquivo. Digite apenas n�meros. O sistema colocar� os separadores automaticamente."></td>');
    ShowHTML('      <tr><td><font size="1"><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_arquivo_recebido" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OBRIGAT�RIO. Clique no bot�o ao lado para localizar o arquivo (sua extens�o deve ser .TXT). Ele ser� transferido automaticamente para o servidor.">');
    ShowHTML('      <tr><td align="LEFT"><font size="1"><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
  ShowHTML('    <p align="justify">Esta tela tem o objetivo de atualizar os dados or�ament�rios e financeiros');
  ShowHTML('        da tabela de programas e a��es do PPA, atrav�s da importa��o de arquivo extra�do do conciliacao.');
  ShowHTML('    <p align="justify">A atualiza��o est� restrita aos dados sobre dota��o autorizada, total empenhado e total liquidado.');
  ShowHTML('    <p align="justify">Para ser executada corretamente, a importa��o deve cumprir os passos abaixo.');
  ShowHTML('    <ol>');
  ShowHTML('    <p align="justify"><b>FASE 1 - Prepara��o do arquivo a ser importado:</b><br></p>');
  ShowHTML('      <li>Use o m�dulo extrator do SIAFI para obter uma planilha Excel (extens�o XLS), <u>exatamente igual</u> � exibida neste');
  ShowHTML('          '.LinkArquivo('HL',$w_cliente,'SIAFI_Exemplo.xls','ExemploSIAFI','Exibe o registro da importa��o.','exemplo',null).';');
  ShowHTML('      <li>Abra a planilha gerada no passo anterior com o Excel e use a op��o "Arquivo -> Salvar como". Escolha o nome que desejar');
  ShowHTML('          para o arquivo e, na lista "Salvar como tipo", escolha a op��o "<b>CSV (Separado por v�rgulas) (*.csv)</b>"; ');
  ShowHTML('      <li> Feche o ');
  ShowHTML('          Excel e renomeie a extens�o do arquivo, de CSV para TXT. Ap�s cumprir este passo, voc� dever� ter um arquivo com extens�o TXT, como o deste ');
  ShowHTML('          '.LinkArquivo('HL',$w_cliente,'SIAFI_exemplo.TXT','ExemploSIAFI','Exibe o registro da importa��o.','exemplo',null).';');
  ShowHTML('    <p align="justify"><b>FASE 2 - Importa��o do arquivo e atualiza��o dos dados:</b><br></p>');
  ShowHTML('      <li>Na tela anterior, clique sobre a opera��o "Incluir";');
  ShowHTML('      <li>Quando a tela de inclus�o for apresentada, preencha o formul�rio seguindo as instru��es dispon�veis em cada campo ');
  ShowHTML('          (passe o mouse sobre o campo desejado para o sistema exibir a instru��o de preenchimento);');
  ShowHTML('      <li>Aguarde o t�rmino da importa��o e atualiza��o dos dados. O sistema ir�, numa �nica execu��o, transferir o arquivo ');
  ShowHTML('          selecionado para o servidor, ler cada uma das suas linhas, verificar se os dados est�o corretos e, em caso positivo, ');
  ShowHTML('          atualizar os campos. Este processamento pode demorar alguns minutos. N�o clique em nenhum bot�o at� o sistema voltar para ');
  ShowHTML('          para a listagem das importa��es j� executadas;');
  ShowHTML('    <p align="justify"><b>FASE 3 - Verifica��o do arquivo de registro:</b><br></p>');
  ShowHTML('      <li>Verifique se ocorreu erro na importa��o de alguma linha do arquivo de origem. Na lista de importa��es, existem tr�s colunas: ');
  ShowHTML('          "Registros" indica o n�mero total de linhas do arquivo, "Importados" indica o n�mero de linhas que atendeu �s condi��es de importa��o ');
  ShowHTML('          e que geraram atualiza��o nos dados existentes, "Rejeitados" indica o n�mero de linhas que foram descartadas pela valida��o; ');
  ShowHTML('      <li>Verifique cada linha descartada pela rotina de importa��o. Clique sobre a opera��o "Registro" na coluna "Opera��es" e verifique ');
  ShowHTML('          os erros detectados em cada uma das linhas descartadas. O conte�do do arquivo � similar ao deste ');
  ShowHTML('          '.LinkArquivo('HL',$w_cliente,'SIAFI_Registro.TXT','ExemploSIAFI','Exibe o registro da importa��o.','exemplo',null).';');
  ShowHTML('      <li>Se desejar, gere um novo arquivo somente com as linhas descartadas, corrija os erros e fa�a uma nova importa��o.');
  ShowHTML('    </ol>');
  ShowHTML('    <p align="justify"><b>Observa��es:</b><br></p>');
  ShowHTML('    <ul>');
  ShowHTML('      <li>Para restringir a importa��o �s linhas que realmente s�o �teis, abra o arquivo obtido no passo (3) com o Bloco de Notas (Notepad) ');
  ShowHTML('          e remova as linhas que n�o disserem respeito aos programas e a��es do PPA, n�o esquecendo de salv�-lo;');
  ShowHTML('      <li>Uma vez conclu�da uma importa��o, n�o h� necessidade de voc� manter em seu computador/disquete o arquivo utilizado. O sistema ');
  ShowHTML('          grava no servidor uma c�pia do arquivo usado pela importa��o e uma c�pia do arquivo de registro;');
  ShowHTML('      <li>Toda importa��o registra os dados de quem a executou, de quando ela foi executada, bem como os arquivos de origem e de registro; ');
  ShowHTML('      <li>N�o h� como cancelar uma importa��o, nem de reverter os valores existentes antes da sua execu��o. Assim, certifique-se de que o ');
  ShowHTML('          arquivo de origem est� correto e que a importa��o deve realmente ser executada.');
  ShowHTML('    </ul>');
  ShowHTML('    <p align="justify"><b>Verifica��es dos dados:</b><br></p>');
  ShowHTML('    <ul>');
  ShowHTML('      <p align="justify">Uma linha do arquivo origem s� gera atualiza��o da tabela de programas e a��es do PPA se atender aos seguintes crit�rios:</p>');
  ShowHTML('      <li>O c�digo do programa deve estar na segunda posi��o da linha e deve conter 4 posi��es n�mericas;');
  ShowHTML('      <li>A c�digo da a��o deve estar na quarta posi��o da linha e deve conter entre 4 e 5 posi��es, sendo que as quatro primeiras s�o n�meros;');
  ShowHTML('           e a quinta posi��o deve ser uma letra mai�scula ');
  ShowHTML('      <li>A dota��o autorizada deve estar na sexta posi��o da linha e deve estar na nota��o brasileira de valor (separador de milhar = ponto e de decimal = v�rgula);');
  ShowHTML('      <li>O total empenhado deve estar na s�tima posi��o da linha e deve estar na nota��o brasileira de valor (separador de milhar = ponto e de decimal = v�rgula);');
  ShowHTML('      <li>O total liquidado deve estar na s�tima posi��o da linha e deve estar na nota��o brasileira de valor (separador de milhar = ponto e de decimal = v�rgula);');
  ShowHTML('      <li>O sistema s� atualizar� a tabela se encontrar um, e apenas um registro com o mesmo c�digo de a��o e programa;');
  ShowHTML('      <li>Cada posi��o da linha � separada pelo caracter ponto-e-v�rgula;');
  ShowHTML('      <li>Os valores de cada posi��o <u>n�o</u> devem estar entre aspas simples nem duplas. Ex: <b>;1606;...</b> � v�lido, mas <b>;"1606";...</b> e <b>;\'1606\';...</b> s�o inv�lidos; ');
  ShowHTML('      <p align="justify">Qualquer situa��o diferente das relacionadas acima causar� a rejei��o da linha.</p>');
  ShowHTML('    <ul>');
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
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
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
          
          // Gera o arquivo registro da importa��o
          $F1 = fopen($w_caminho.$w_caminho_registro, 'w');
          //Abre o arquivo recebido para gerar o arquivo registro
          $F2 = csv($w_caminho.$w_caminho_recebido);
          if (is_array($F2[""])) {
            // Varre o arquivo recebido, linha a linha
            foreach($F2[""] as $row) {
              if ($w_cont==0) {
                $w_cont+=1;
                continue;
              }
              $w_linha = '';
              foreach($row as $k => $v) {
                $w_linha .= '"'.trim($v).'",';
              }
              $w_linha = substr($w_linha,0,-1);
              fwrite($F1,(($w_cont>1) ? $crlf.$crlf : '').'[Linha '.$w_cont.'] '.$w_linha);
              $w_erro     = 0;
              
              $w_fatura     = trim($row[0]);
              // Valida o campo N�mero da Fatura
              $w_result = fValidate(1,$w_fatura,'Fatura','',1,1,30,'','0123456789');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'N�mero da fatura: '.$w_result); }
              
              $w_inicio     = trim($row[1]);
              $w_temp = explode('/',$w_inicio); $w_inicio = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
              // Valida o campo in�cio do dec�ndio
              $w_result = fValidate(1,$w_inicio,'in�cio do dec�ndio','DATA',1,10,10,'','0123456789/');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'In�cio do Dec�ndio: '.$w_result); }
              
              $w_fim        = trim($row[2]);
              $w_temp = explode('/',$w_fim); $w_fim = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
              // Valida o campo fim do dec�ndio
              $w_result = fValidate(1,$w_fim,'fim do dec�ndio','DATA',1,10,10,'','0123456789/');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Fim do Dec�ndio: '.$w_result); }
              
              $w_emissao_fat= trim($row[3]);
              $w_temp = explode('/',$w_emissao_fat); $w_emissao_fat = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
              // Valida o campo emissao da fatura
              $w_result = fValidate(1,$w_emissao_fat,'data de emiss�o da fatura','DATA',1,10,10,'','0123456789/');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Data de emiss�o da fatura: '.$w_result); }
              
              $w_venc       = trim($row[4]);
              $w_temp = explode('/',$w_venc); $w_venc = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
              // Valida o campo vencimento da fatura
              $w_result = fValidate(1,$w_venc,'data de vencimento da fatura','DATA',1,10,10,'','0123456789/');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Data de vencimento da fatura: '.$w_result); }
              
              $w_valor_fat  = trim($row[5]);
              if (strpos($w_valor_fat,',')===false) $w_valor_fat .= ',00';
              // Valida o campo valor da fatura
              $w_result = fValidate(1,$w_valor_fat,'valor da fatura','VALOR',1,3,18,'','0123456789,.');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Valor da fatura: '.$w_result); }
              
              $w_cia        = trim(strtoupper($row[6]));
              // Valida o campo numero do bilhete
              $w_result = fValidate(1,$w_cia,'cia a�rea','',1,2,20,'1','1');
              if ($w_result>'') { 
                $w_erro=1; 
                fwrite($F1,$crlf.'Cia a�rea: '.$w_result); 
              } else {
                $RS = db_getCiaTrans::getInstanceOf($dbms,$w_cliente,null,null,$w_cia,'S',null,null,null,null,null,null);
                if (count($RS)==0) {
                  $w_erro=1; 
                  fwrite($F1,$crlf.'Cia a�rea: n�o h� companhia com a sigla "'.$w_cia.'"');
                } elseif (count($RS)>1) {
                  $w_erro=1; 
                  fwrite($F1,$crlf.'Cia a�rea: h� mais de uma companhia com a sigla "'.$w_projeto.'"');
                } else {
                  foreach($RS as $row1) { $w_hn_cia = f($row1,'chave'); break; } 
                }
              }
              
              $w_bilhete    = trim($row[7]);
              // Valida o campo numero do bilhete
              $w_result = fValidate(1,$w_bilhete,'n�mero do bilhete','',1,1,20,'1','1');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'N�mero do bilhete: '.$w_result); }
              
              $w_emissao_bil= trim($row[8]);
              $w_temp = explode('/',$w_emissao_bil); $w_emissao_bil = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
              // Valida o campo data de emiss�o do bilhete
              $w_result = fValidate(1,$w_emissao_bil,'data de emiss�o do bilhete','DATA',1,10,10,'','0123456789/');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Data de emiss�o do bilhete: '.$w_result); }
              
              $w_trechos    = trim($row[9]);
              // Valida o campo trechos
              $w_result = fValidate(1,$w_trechos,'trechos','',1,3,60,'1','1');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Trechos: '.$w_result); }
              
              $w_projeto    = trim($row[10]);
              // Valida o campo c�digo do projeto
              $w_result = fValidate(1,$w_projeto,'c�digo do projeto','',1,3,60,'ABCDEFGHIJKLMNOPQRSTUVWXYZ ','0123456789 ');
              if ($w_result>'') { 
                $w_erro=1; 
                fwrite($F1,$crlf.'C�digo do projeto: '.$w_result); 
              } else {
                // Verifica se o programa/a��o existe para o cliente
                $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PJCAD');
                $RS = db_getSolicList::getInstanceOf($dbms, f($RS,'sq_menu'), $w_usuario, 'PJLIST', 5, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, $w_projeto, null, null, null, null, null, null, null);
                if (count($RS)==0) {
                  $w_erro=1; 
                  fwrite($F1,$crlf.'C�digo do projeto: n�o h� projeto ativo com o c�digo "'.$w_projeto.'"');
                } elseif (count($RS)>1) {
                  $w_erro=1; 
                  fwrite($F1,$crlf.'C�digo do projeto: h� mais de um projeto ativo com o c�digo "'.$w_projeto.'"');
                } else {
                  foreach($RS as $row1) { $w_hn_projeto = f($row1,'sq_siw_solicitacao'); break; } 
                }
              }
              
              $w_valor_pleno= trim($row[11]);
              if (strpos($w_valor_pleno,',')===false) $w_valor_pleno .= ',00';
              // Valida o campo valor pleno do bilhete
              $w_result = fValidate(1,$w_valor_pleno,'valor pleno do bilhete','VALOR',1,3,18,'','0123456789,.');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Valor pleno do bilhete: '.$w_result); }
              
              $w_desconto   = trim(str_replace('%','',$row[12]));
              if (strpos($w_desconto,',')===false) $w_desconto .= ',00';
              // Valida o campo percentual de desconto
              $w_result = fValidate(1,$w_desconto,'percentual de desconto','VALOR',1,3,18,'','0123456789,.');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Percentual de desconto: '.$w_result); }
              
              $w_valor_bil  = trim($row[13]);
              if (strpos($w_valor_bil,',')===false) $w_valor_bil .= ',00';
              // Valida o campo valor do bilhete
              $w_result = fValidate(1,$w_valor_bil,'valor do bilhete','VALOR',1,3,18,'','0123456789,.');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Valor do bilhete: '.$w_result); }
              
              $w_ret_tarifa = trim($row[14]);
              if (strpos($w_ret_tarifa,',')===false) $w_ret_tarifa .= ',00';
              // Valida o campo valor da tarifa retornado
              $w_result = fValidate(1,$w_ret_tarifa,'valor da tarifa retornado','VALOR',1,3,18,'','0123456789,.');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Valor da tarifa retornado: '.$w_result); }
              
              $w_taxa       = trim($row[15]);
              if (strpos($w_taxa,',')===false) $w_taxa .= ',00';
              // Valida o campo valor da taxa
              $w_result = fValidate(1,$w_taxa,'valor da taxa','VALOR',1,3,18,'','0123456789,.');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Valor da taxa: '.$w_result); }
              
              $w_ret_taxa   = trim($row[16]);
              if (strpos($w_ret_taxa,',')===false) $w_ret_taxa .= ',00';
              // Valida o campo valor da taxa retornado
              $w_result = fValidate(1,$w_ret_taxa,'valor da taxa retornado','VALOR',1,3,18,'','0123456789,.');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Valor da taxa retornado: '.$w_result); }
              
              $w_desc_contr = trim($row[17]);
              if (strpos($w_desc_contr,',')===false) $w_desc_contr .= ',00';
              // Valida o campo valor do desconto contratual
              $w_result = fValidate(1,$w_desc_contr,'valor do desconto contratual','VALOR',1,3,18,'','0123456789,.');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Valor do desconto contratual: '.$w_result); }
              
              $w_valor_total= trim($row[18]);
              if (strpos($w_valor_total,',')===false) $w_valor_total .= ',00';
              // Valida o campo valor total do bilhete
              $w_result = fValidate(1,$w_valor_total,'valor total do bilhete','VALOR',1,3,18,'','0123456789,.');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Valor total do bilhete: '.$w_result); }
              
              // Guarda dados para grava��o
              $faturas[$w_fatura]['fatura'] = $w_fatura;
              $faturas[$w_fatura]['projeto'] = $w_hn_projeto;
              $faturas[$w_fatura]['inicio'] = $w_inicio;
              $faturas[$w_fatura]['fim'] = $w_fim;
              $faturas[$w_fatura]['emissao'] = $w_emissao_fat;
              $faturas[$w_fatura]['vencimento'] = $w_venc;
              $faturas[$w_fatura]['valor'] = $w_valor_fat;
              if ($w_erro>1) $faturas[$w_fatura]['erro'] = 'erro';
              
              $bilhetes[$w_fatura][$w_bilhete]['numero'] = $w_bilhete;
              $bilhetes[$w_fatura][$w_bilhete]['cia'] = $w_hn_cia;
              $bilhetes[$w_fatura][$w_bilhete]['emissao'] = $w_emissao_bil;
              $bilhetes[$w_fatura][$w_bilhete]['trecho'] = $w_trechos;
              $bilhetes[$w_fatura][$w_bilhete]['valor_cheio'] = $w_valor_pleno;
              $bilhetes[$w_fatura][$w_bilhete]['valor'] = $w_valor_bil;
              $bilhetes[$w_fatura][$w_bilhete]['embarque'] = $w_taxa;
              $bilhetes[$w_fatura][$w_bilhete]['cia'] = $w_hn_cia;
              
              $w_cont      += 1;
              $w_registros += 1;
              if ($w_erro==0)   $w_importados += 1;
              else              $w_rejeitados += 1;
            }
          }
          fclose($F1);
          foreach($faturas as $row) {
            if (nvl(f($row,'erro'),'')=='') {
              $w_fatura = f($row,'fatura');
              $fatura_grava[$w_fatura]['fatura'] = $w_fatura;
              $fatura_grava[$w_fatura]['projeto'] = f($row,'projeto');
              $fatura_grava[$w_fatura]['inicio'] = f($row,'inicio');
              $fatura_grava[$w_fatura]['fim'] = f($row,'fim');
              $fatura_grava[$w_fatura]['emissao'] = f($row,'emissao');
              $fatura_grava[$w_fatura]['vencimento'] = f($row,'vencimento');
              $fatura_grava[$w_fatura]['valor'] = f($row,'valor');
              
              foreach($bilhetes[$w_fatura] as $row1) {
                $w_bilhete = f($row1,'numero');
                $bilhete_grava[$w_bilhete]['numero'] = f($row1,'numero');
                $bilhete_grava[$w_bilhete]['fatura'] = $w_fatura;
                $bilhete_grava[$w_bilhete]['cia'] = f($row1,'cia');
                $bilhete_grava[$w_bilhete]['emissao'] = f($row1,'emissao');
                $bilhete_grava[$w_bilhete]['trecho'] = f($row1,'trecho');
                $bilhete_grava[$w_bilhete]['valor_cheio'] = f($row1,'valor_cheio');
                $bilhete_grava[$w_bilhete]['valor'] = f($row1,'valor');
                $bilhete_grava[$w_bilhete]['embarque'] = f($row1,'embarque');
                $bilhete_grava[$w_bilhete]['cia'] = f($row1,'cia');
              }
            }
          }
          var_dump($fatura_grava);
          var_dump($bilhete_grava);
          exit;
          // Configura o valor dos campos necess�rios para grava��o
          $w_arquivo_registro   = 'registro.txt';
          $w_tamanho_registro   = filesize($w_caminho.$w_caminho_registro);
          $w_tipo_registro      = mime_content_type($w_caminho.$w_caminho_registro);
          // Grava o resultado da importa��o no banco de dados
          dml_putPDImportacao::getInstanceOf($dbms,$O,
                $_REQUEST['w_chave'],$w_cliente,$w_usuario,$_REQUEST['w_data_arquivo'],
                $w_nome_recebido,$w_caminho_recebido,$w_tamanho_recebido,$w_tipo_recebido,
                $w_arquivo_registro,$w_caminho_registro,$w_tamanho_registro,$w_tipo_registro,
                $w_registros,$w_importados,$w_rejeitados,$w_nome_recebido,$w_arquivo_registro);
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