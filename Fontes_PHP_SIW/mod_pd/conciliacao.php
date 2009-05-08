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
include_once($w_dir_volta.'classes/sp/db_getDescontoAgencia.php');
include_once($w_dir_volta.'classes/sp/db_getPDImportacao.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putPDImportacao.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
// =========================================================================
//  /conciliacao.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Rotinas de conciliação da fatura eletrônica da agência de viagem
// Mail     : alex@sbpi.com.br
// Criacao  : 07/04/2006, 13:20
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
$w_pagina       = 'conciliacao.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_pd/';
$w_troca        = $_REQUEST['w_troca'];
// Configura o caminho para gravação física de arquivos<u></u>
$p_responsavel  = strtoupper($_REQUEST['p_responsavel']);
$p_dt_ini       = $_REQUEST['p_dt_ini'];
$p_dt_fim       = $_REQUEST['p_dt_fim'];
$p_imp_ini      = $_REQUEST['p_imp_ini'];
$p_imp_fim      = $_REQUEST['p_imp_fim'];
if ($O=='') {
  if ($par=='REL_PPA' || $par=='REL_INICIATIVA') $O='P'; else $O='L';
} 
switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';    break;
  case 'A': $w_TP=$TP.' - Alteração';   break;
  case 'E': $w_TP=$TP.' - Exclusão';    break;
  case 'P': $w_TP=$TP.' - Filtragem';   break;
  case 'C': $w_TP=$TP.' - Cópia';       break;
  case 'V': $w_TP=$TP.' - Envio';       break;
  case 'H': $w_TP=$TP.' - Herança';     break;
  case 'O': $w_TP=$TP.' - Orientações'; break;
  default:  $w_TP=$TP.' - Listagem';    break;
} 
// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
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
// Rotina de importação de arquivos físicos para atualização de dados financeiros
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  $w_chave  = $_REQUEST['w_chave'];
  $w_troca  = $_REQUEST['w_troca'];
  if ($w_troca>'') {
    // Se for recarga da página
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
      Validate('w_agencia','Agência de viagem','SELECT','1','1','18','','0123456789');
      Validate('w_data_arquivo','Data e hora','DATAHORA','1','17','17','','0123456789 /:,');
      Validate('w_arquivo_recebido','Arquivo de dados','1','1','1','255','1','1');
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'')                      BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  elseif (!(strpos('I',$O)===false))    BodyOpen('onLoad=\'document.Form.w_agencia.focus()\';');
  else                                  BodyOpen('onLoad=\'this.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><font size="1">');
    ShowHTML('        <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('        <a accesskey="O" class="SS" href="'.$w_dir.$w_pagina.'Help&R='.$w_pagina.$par.'&O=O&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" target="help"><u>O</u>rientações</a>&nbsp;');
    ShowHTML('    <td align="right"><font size="1"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan=2><font size="1"><b>Data</font></td>');
    ShowHTML('          <td rowspan=2><font size="1"><b>Executado em</font></td>');
    ShowHTML('          <td rowspan=2><font size="1"><b>Responsável</font></td>');
    ShowHTML('          <td colspan=3><font size="1"><b>Registros</font></td>');
    ShowHTML('          <td rowspan=2><font size="1"><b>Operações</font></td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>Total</font></td>');
    ShowHTML('          <td><font size="1"><b>Aceitos</font></td>');
    ShowHTML('          <td><font size="1"><b>Rejeitados</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
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
        ShowHTML('          '.LinkArquivo('HL',$w_cliente,f($row,'chave_result'),'_blank','Exibe o registro da importação.','Registro',null).'&nbsp');
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
      ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b><font color="#BC3131">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de '.(f($RS,'upload_maximo')/1024).' KBytes</b>.</font></td>');
      ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
    } 
    ShowHTML('       <tr valign="top">');
    SelecaoPessoa('Agê<u>n</u>cia de viagem:','N','Selecione a agência de viagem emissora da fatura.',$w_agencia,null,'w_agencia','FORNECPD');
    ShowHTML('       <tr>');
    ShowHTML('      <tr><td><font size="1"><b><u>D</u>ata/hora extração:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data_arquivo" class="sti" SIZE="17" MAXLENGTH="17" VALUE="'.$w_data_arquivo.'"  onKeyDown="FormataDataHora(this, event);" onKeyUp="SaltaCampo(this.form.name,this,17,event);" title="OBRIGATÓRIO. Informe a data e hora da extração do aquivo. Digite apenas números. O sistema colocará os separadores automaticamente."></td>');
    ShowHTML('      <tr><td><font size="1"><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_arquivo_recebido" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OBRIGATÓRIO. Clique no botão ao lado para localizar o arquivo (sua extensão deve ser .TXT). Ele será transferido automaticamente para o servidor.">');
    ShowHTML('      <tr><td align="LEFT"><font size="1"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
    ShowHTML(' alert(\'Opção não disponível\');');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Exibe orientações sobre o processo de importação
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
  ShowHTML('    <p align="justify">Esta tela tem o objetivo de conciliar os bilhetes de uma fatura eletrônica com os registrados pelo sistema de viagens.');
  ShowHTML('    <p align="justify">Os critérios adotados para a conciliação estão descritos abaixo, nesta página.');
  ShowHTML('    <p align="justify">Para ser executada corretamente, o procedimento de conciliação deve cumprir os passos abaixo.');
  ShowHTML('    <ol>');
  ShowHTML('    <p align="justify"><b>FASE 1 - Preparação do arquivo a ser importado:</b><br></p>');
  ShowHTML('      <li>Obtenha junto à agência de viagens um arquivo CSV, <u>exatamente igual</u> à exibida neste');
  ShowHTML('          '.LinkArquivo('HL',$w_cliente,'FATURA_AEREA_IMPORT.CSV','ExemploAgencia','Exibe o registro da importação.','exemplo',null).';');
  ShowHTML('    <p align="justify"><b>FASE 2 - Importação do arquivo e conciliação dos dados:</b><br></p>');
  ShowHTML('      <li>Na tela anterior, clique sobre a operação "Incluir";');
  ShowHTML('      <li>Quando a tela de inclusão for apresentada, preencha o formulário seguindo as instruções disponíveis em cada campo ');
  ShowHTML('          (passe o mouse sobre o campo desejado para o sistema exibir a instrução de preenchimento);');
  ShowHTML('      <li>Aguarde o término da importação e conciliação dos dados. O sistema irá, numa única execução, transferir o arquivo ');
  ShowHTML('          selecionado para o servidor, ler cada uma das suas linhas, verificar se os dados estão corretos e gerar um arquivo com o registro ');
  ShowHTML('          da verificação. Este processamento pode demorar alguns minutos. Não clique em nenhum botão até o sistema voltar para ');
  ShowHTML('          para a listagem das importações já executadas;');
  ShowHTML('    <p align="justify"><b>FASE 3 - Verificação do arquivo de registro:</b><br></p>');
  ShowHTML('      <li>Verifique se ocorreu erro na importação de alguma linha do arquivo de origem. Na lista de importações, existem três colunas: ');
  ShowHTML('          "Registros" indica o número total de linhas do arquivo, "Aceitos" indica o número de linhas que atenderam às condições de importação e');
  ShowHTML('          "Rejeitados" indica o número de linhas que foram descartadas pela validação; ');
  ShowHTML('      <li>Verifique cada linha descartada pela rotina de importação. Clique sobre a operação "Registro" na coluna "Operações" e verifique ');
  ShowHTML('          os erros detectados em cada uma das linhas descartadas. O conteúdo do arquivo é similar ao deste ');
  ShowHTML('          '.LinkArquivo('HL',$w_cliente,'FATURA_AEREA_REGISTRO.TXT','ExemploAgencia','Exibe o registro da importação.','exemplo',null).';');
  ShowHTML('      <li>Se desejar, gere um novo arquivo somente com as linhas descartadas, corrija os erros e faça uma nova importação.');
  ShowHTML('    </ol>');
  ShowHTML('    <p align="justify"><b>Observações:</b><br></p>');
  ShowHTML('    <ul>');
  ShowHTML('      <li>Para restringir a importação às linhas que realmente são úteis, abra o arquivo obtido no passo 1 com o Bloco de Notas (Notepad) ');
  ShowHTML('          e remova eventuais linhas em branco, não esquecendo de salvá-lo;');
  ShowHTML('      <li>Uma vez concluída uma importação, não há necessidade de você manter em seu computador/disquete o arquivo utilizado. O sistema ');
  ShowHTML('          grava no servidor uma cópia do arquivo usado pela importação e uma cópia do arquivo de registro;');
  ShowHTML('      <li>Toda importação registra os dados de quem a executou, de quando ela foi executada, bem como os arquivos de origem e de registro; ');
  ShowHTML('      <li>Não há como cancelar uma importação, nem de reverter os valores existentes antes da sua execução. Assim, certifique-se de que o ');
  ShowHTML('          arquivo de origem está correto e que a importação deve realmente ser executada.');
  ShowHTML('    </ul>');
  ShowHTML('    <p align="justify"><b>Verificações dos dados:</b><br></p>');
  ShowHTML('    <p align="justify">Uma linha do arquivo origem só é aceita se atender aos seguintes critérios:</p>');
  ShowHTML('    <table border=1>');
  ShowHTML('      <tr align="center">');
  ShowHTML('        <td>Coluna</td>');
  ShowHTML('        <td>Campo</td>');
  ShowHTML('        <td>Tipo</td>');
  ShowHTML('        <td>Obrigatório</td>');
  ShowHTML('        <td>Domínio</td>');
  ShowHTML('        <td>Formato</td>');
  ShowHTML('        <td>Observações</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>1</td>');
  ShowHTML('        <td>Número da fatura</td>');
  ShowHTML('        <td>VARCHAR(30)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>0123456789</td>');
  ShowHTML('        <td>AAAAA...</td>');
  ShowHTML('        <td>');
  ShowHTML('          <li>Cada arquivo deve conter apenas uma fatura e cada fatura deve relacionar bilhetes de um mesmo projeto.</li>');
  ShowHTML('          <li>Faturas já processadas e aceitas não podem ser reprocessadas.</li>');
  ShowHTML('        </td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>2</td>');
  ShowHTML('        <td>Início do decêndio</td>');
  ShowHTML('        <td>CHAR(10)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Data válida</td>');
  ShowHTML('        <td>DD/MM/YYYY</td>');
  ShowHTML('        <td>Esse valor deve ser repetido em todas as linhas do arquivo.<td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>3</td>');
  ShowHTML('        <td>Fim do decêndio</td>');
  ShowHTML('        <td>CHAR(10)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Data válida</td>');
  ShowHTML('        <td>DD/MM/YYYY</td>');
  ShowHTML('        <td>Idem ao anterior.</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>4</td>');
  ShowHTML('        <td>Emissão da fatura</td>');
  ShowHTML('        <td>CHAR(10)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Data válida</td>');
  ShowHTML('        <td>DD/MM/YYYY</td>');
  ShowHTML('        <td>Esse valor deve ser repetido em todas as linhas do arquivo.</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>5</td>');
  ShowHTML('        <td>Vencimento da fatura</td>');
  ShowHTML('        <td>CHAR(10)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Data válida</td>');
  ShowHTML('        <td>DD/MM/YYYY</td>');
  ShowHTML('        <td>Esse valor deve ser repetido em todas as linhas do arquivo.</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>6</td>');
  ShowHTML('        <td>Valor da fatura</td>');
  ShowHTML('        <td nowrap>VARCHAR(18)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Valor monetário na notação brasileira (separador de milhar = ponto e de decimal = vírgula)</td>');
  ShowHTML('        <td>000.000.009,99</td>');
  ShowHTML('        <td>Esse valor deve ser repetido em todas as linhas do arquivo.</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>7</td>');
  ShowHTML('        <td>Cia emissora do bilhete</td>');
  ShowHTML('        <td nowrap>CHAR(2)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Sigla da companhia aérea</td>');
  ShowHTML('        <td>AA</td>');
  ShowHTML('        <td>Verificar companhias de transporte aéreo cadastradas na opção "Viagens - Tabelas - Companhias de transporte".</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>8</td>');
  ShowHTML('        <td>Número do bilhete</td>');
  ShowHTML('        <td>VARCHAR(20)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>0123456789</td>');
  ShowHTML('        <td>AAAAA...</td>');
  ShowHTML('        <td>A combinação Cia emissora / Número do bilhete deve ser única no arquivo e não pode ter sido processada em outra fatura.</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>9</td>');
  ShowHTML('        <td>Emissão do bilhete</td>');
  ShowHTML('        <td>CHAR(10)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Data válida</td>');
  ShowHTML('        <td>DD/MM/YYYY</td>');
  ShowHTML('        <td>Deve estar contida no decêndio da fatura');
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
  ShowHTML('        <td>Projeto (código orçamentário)</td>');
  ShowHTML('        <td nowrap>VARCHAR(30)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Código de projeto existente no sistema</td>');
  ShowHTML('        <td>&nbsp;</td>');
  ShowHTML('        <td>');
  ShowHTML('          <li>Verificar códigos válidos de projetos na opção "Mesa de trabalho - Projetos", coluna "Consultar".</li>');
  ShowHTML('          <li>Cada arquivo deve conter bilhetes de um mesmo projeto. Assim, esse valor deve ser repetido em todas as linhas do arquivo.</li>');
  ShowHTML('        </td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>12</td>');
  ShowHTML('        <td>Valor pleno do bilhete</td>');
  ShowHTML('        <td nowrap>VARCHAR(18)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Valor monetário na notação brasileira (separador de milhar = ponto e de decimal = vírgula)</td>');
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
  ShowHTML('        <td>Valor monetário na notação brasileira (separador de milhar = ponto e de decimal = vírgula)</td>');
  ShowHTML('        <td>000.000.009,99</td>');
  ShowHTML('        <td>Igual ao valor informado pelo agente interno de viagens quando registrou o bilhete.</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>15</td>');
  ShowHTML('        <td>Valor da retenção da tarifa</td>');
  ShowHTML('        <td nowrap>VARCHAR(18)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Valor monetário na notação brasileira (separador de milhar = ponto e de decimal = vírgula)</td>');
  ShowHTML('        <td>000.000.009,99</td>');
  ShowHTML('        <td>&nbsp;</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>16</td>');
  ShowHTML('        <td>Valor das taxas</td>');
  ShowHTML('        <td nowrap>VARCHAR(18)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Valor monetário na notação brasileira (separador de milhar = ponto e de decimal = vírgula)</td>');
  ShowHTML('        <td>000.000.009,99</td>');
  ShowHTML('        <td>Igual taxa de embarque mais outras taxas, campos informados pelo agente interno de viagens quando o bilhete foi registrado.</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>17</td>');
  ShowHTML('        <td>Valor da retenção da taxa</td>');
  ShowHTML('        <td nowrap>VARCHAR(18)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Valor monetário na notação brasileira (separador de milhar = ponto e de decimal = vírgula)</td>');
  ShowHTML('        <td>000.000.009,99</td>');
  ShowHTML('        <td>&nbsp;</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>18</td>');
  ShowHTML('        <td>Valor do desconto contratual</td>');
  ShowHTML('        <td nowrap>VARCHAR(18)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Valor monetário na notação brasileira (separador de milhar = ponto e de decimal = vírgula)</td>');
  ShowHTML('        <td>000.000.009,99</td>');
  ShowHTML('        <td>&nbsp;</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('        <td>19</td>');
  ShowHTML('        <td>Valor total do bilhete</td>');
  ShowHTML('        <td nowrap>VARCHAR(18)</td>');
  ShowHTML('        <td align="center">Sim</td>');
  ShowHTML('        <td>Valor monetário na notação brasileira (separador de milhar = ponto e de decimal = vírgula)</td>');
  ShowHTML('        <td>000.000.009,99</td>');
  ShowHTML('        <td>Valor do bilhete (coluna 14) - valor do desconto contratual (coluna 18) + valor das taxas (coluna 16)</td>');
  ShowHTML('      </tr>');
  ShowHTML('    </table>');
  ShowHTML('    <ul>');
  ShowHTML('    Legenda da coluna formato:');
  ShowHTML('      <li>A = caractere alfanumérico (A..Z,a..z,0..9)');
  ShowHTML('      <li>0 = caractere numérico exibido apenas se diferente de zero');
  ShowHTML('      <li>9 = caractere numérico obrigatório');
  ShowHTML('      <li>DD = dia com duas posições (01..30)');
  ShowHTML('      <li>MM = mês com duas posições (01..12)');
  ShowHTML('      <li>YYYY = ano com século (1999, 2000, 2001...)');
  ShowHTML('    <ul>');
  ShowHTML('  </td>');
  ShowHTML('</tr>');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');  
  BodyOpen(null);
  switch ($SG) {
    case 'PDFATURA':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
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
          
          // Gera o arquivo registro da importação
          $F1 = fopen($w_caminho.$w_caminho_registro, 'w');
          //Abre o arquivo recebido para gerar o arquivo registro
          $F2 = csv($w_caminho.$w_caminho_recebido);
          if (is_array($F2[""])) {
            // Recupera dados da agência de viagens emissora da fatura
            $RS_Agencia = db_getPersonList::getInstanceOf($dbms, $w_cliente, $_REQUEST['w_agencia'], 'TODOS', null, null, null, null);
            foreach($RS_Agencia as $row) { $RS_Agencia = $row; break; }
            
            // Recupera a tabela de descontos da agência informada
            $RS_Desconto = db_getDescontoAgencia::getInstanceOf($dbms,$w_cliente,null,$_REQUEST['w_agencia'],null,null,null,null,'S');
            $RS_Desconto = SortArray($RS_Desconto,'nome','asc','faixa_inicio','asc');
            
            // Varre o arquivo recebido, linha a linha
            foreach($F2[""] as $row) {
              $w_hn_cia         = false; // indica a companhia aérea emissora do bilhete
              $w_hn_solic       = false; // indica solicitação do bilhete
              $w_hn_bilhete     = false; // indica se foi encontrado bilhete no banco de dados
              $w_hn_desconto    = false; // indica o desconto contratual aplicado ao bilhete
              $desconto_padrao  = 0;     // percentual de desconto a ser aplicado no valor do bilhete
              $desconto_bilhete = 0;     // valor do desconto contratual do bilhete
              if ($w_cont==0) {
                $w_cont+=1;
                continue;
              } elseif ($w_cont==1) {
                // Se for a primeira linha, recupera os dados que devem ser repetidos em todo o arquivo
                $fatura     = trim($row[0]);
                $inicio     = trim($row[1]);
                $w_temp = explode('/',$inicio); $inicio = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
                $fim        = trim($row[2]);
                $w_temp = explode('/',$fim); $fim = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
                $emissao    = trim($row[3]);
                $w_temp = explode('/',$emissao); $emissao = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
                $vencimento = trim($row[4]);
                $w_temp = explode('/',$vencimento); $vencimento = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
                $valor      = trim($row[5]);
                if (strpos($w_valor,',')===false) $w_valor .= ',00';
                $projeto    = trim($row[10]);  
                $inicio_dec = toDate($inicio);
                $fim_dec    = toDate($fim);
                
                fwrite($F1,'=================================================================================');                
                fwrite($F1,$crlf.'Resultado do processamento do arquivo '.$w_nome_recebido);                
                fwrite($F1,$crlf.'Agência de viagens: '.f($RS_Agencia,'nome').' - CNPJ: '.f($RS_Agencia,'codigo'));                
                fwrite($F1,$crlf.'Fatura: '.$fatura);                
                fwrite($F1,$crlf.'Decêndio: '.$inicio.' a '.$fim);                
                fwrite($F1,$crlf.'Emissão: '.$emissao);                
                fwrite($F1,$crlf.'Valor: '.$valor);                
                fwrite($F1,$crlf.'Projeto: '.$projeto);                
                fwrite($F1,$crlf.'=================================================================================');                
              }
              $w_linha = '';
              foreach($row as $k => $v) {
                $w_linha .= '"'.trim($v).'",';
              }
              $w_linha = substr($w_linha,0,-1);
              fwrite($F1,$crlf.$crlf.'[Linha '.$w_cont.'] '.$w_linha);
              $w_erro     = 0;
              
              $w_fatura     = trim($row[0]);
              // Só pode haver uma fatura no arquivo
              if ($fatura!=$w_fatura) {
                $w_erro=1; 
                fwrite($F1,$crlf.'Número da fatura: só é possível conciliar uma fatura em cada arquivo'); 
              }
              // Valida o campo Número da Fatura
              $w_result = fValidate(1,$w_fatura,'Fatura','',1,1,30,'','0123456789');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Número da fatura: '.$w_result); }
              
              $w_inicio     = trim($row[1]);
              $w_temp = explode('/',$w_inicio); $w_inicio = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
              // Só pode haver um decêndio no arquivo
              if ($inicio!=$w_inicio) {
                $w_erro=1; 
                fwrite($F1,$crlf.'Início do decêndio: todos as linhas devem ter o mesmo valor'); 
              }
              // Valida o campo início do decêndio
              $w_result = fValidate(1,$w_inicio,'início do decêndio','DATA',1,10,10,'','0123456789/');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Início do Decêndio: '.$w_result); }
              
              $w_fim        = trim($row[2]);
              $w_temp = explode('/',$w_fim); $w_fim = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
              // Só pode haver um decêndio no arquivo
              if ($fim!=$w_fim) {
                $w_erro=1; 
                fwrite($F1,$crlf.'Fim do decêndio: todos as linhas devem ter o mesmo valor'); 
              }
              // Valida o campo fim do decêndio
              $w_result = fValidate(1,$w_fim,'fim do decêndio','DATA',1,10,10,'','0123456789/');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Fim do Decêndio: '.$w_result); }
              
              $w_emissao_fat= trim($row[3]);
              $w_temp = explode('/',$w_emissao_fat); $w_emissao_fat = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
              // Só pode haver uma emissão de fatura no arquivo
              if ($emissao!=$w_emissao_fat) {
                $w_erro=1; 
                fwrite($F1,$crlf.'Data de emissão da fatura: todos as linhas devem ter o mesmo valor'); 
              }
              // Valida o campo emissao da fatura
              $w_result = fValidate(1,$w_emissao_fat,'data de emissão da fatura','DATA',1,10,10,'','0123456789/');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Data de emissão da fatura: '.$w_result); }
              
              $w_venc       = trim($row[4]);
              $w_temp = explode('/',$w_venc); $w_venc = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
              // Só pode haver uma emissão de fatura no arquivo
              if ($vencimento!=$w_venc) {
                $w_erro=1; 
                fwrite($F1,$crlf.'Data de vencimento da fatura: todos as linhas devem ter o mesmo valor'); 
              }
              // Valida o campo vencimento da fatura
              $w_result = fValidate(1,$w_venc,'data de vencimento da fatura','DATA',1,10,10,'','0123456789/');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Data de vencimento da fatura: '.$w_result); }
              
              $w_valor_fat  = trim($row[5]);
              if (strpos($w_valor_fat,',')===false) $w_valor_fat .= ',00';
              // Só pode haver uma emissão de fatura no arquivo
              if ($valor!=$w_valor_fat) {
                $w_erro=1; 
                fwrite($F1,$crlf.'Valor da fatura: todos as linhas devem ter o mesmo valor'); 
              }
              // Valida o campo valor da fatura
              $w_result = fValidate(1,$w_valor_fat,'valor da fatura','VALOR',1,3,18,'','0123456789,.');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Valor da fatura: '.$w_result); }
              
              $w_cia        = trim(strtoupper($row[6]));
              // Valida o campo cia aérea
              $w_result = fValidate(1,$w_cia,'cia aérea','',1,2,20,'1','1');
              if ($w_result>'') { 
                $w_erro=1; 
                fwrite($F1,$crlf.'Cia aérea: '.$w_result); 
              } else {
                $RS = db_getCiaTrans::getInstanceOf($dbms,$w_cliente,null,null,$w_cia,'S',null,null,null,null,null,null);
                if (count($RS)==0) {
                  $w_erro=1; 
                  fwrite($F1,$crlf.'Cia aérea: na base de dados não há companhia com a sigla "'.$w_cia.'"');
                } elseif (count($RS)>1) {
                  $w_erro=1; 
                  fwrite($F1,$crlf.'Cia aérea: há mais de uma companhia com a sigla "'.$w_cia.'"');
                } else {
                  foreach($RS as $row1) { $w_hn_cia = f($row1,'chave'); break; } 
                }
              }
              
              $w_bilhete    = trim($row[7]);
              // Valida o campo numero do bilhete
              $w_result = fValidate(1,$w_bilhete,'número do bilhete','',1,1,20,'1','1');
              if ($w_result>'') { 
                $w_erro=1; fwrite($F1,$crlf.'Número do bilhete: '.$w_result); 
              } else {
                // Verifica se o bilhete já foi relacionado no arquivo
                if (isset($bilhetes[$w_cia][$w_bilhete])) {
                  $w_erro=1; 
                  fwrite($F1,$crlf.'Número do bilhete: bilhete "'.$w_bilhete.'" da cia "'.$w_cia.'" duplicado no arquivo (Linha '.$bilhetes[$w_cia][$w_bilhete].')'); 
                } else {
                  $bilhetes[$w_cia][$w_bilhete] = $w_cont;
                }
                if ($w_hn_cia) {
                  // Verificações se a companhia aérea for localizada na base de dados
                  $RS_Bilhete = db_getPD_Bilhete::getInstanceOf($dbms,null,null,null,null,$w_bilhete,$w_hn_cia,'S',null);
                  if (count($RS_Bilhete)==0) {
                    $w_erro=1; 
                    fwrite($F1,$crlf.'Número do bilhete: na base de dados não há bilhete com o número "'.$w_bilhete.'" da cia "'.$w_cia.'"');
                  } elseif (count($RS_Bilhete)>1) {
                    $w_erro=1; 
                    fwrite($F1,$crlf.'Número do bilhete: bilhete número "'.$w_bilhete.'" da cia "'.$w_cia.'" duplicado na base de dados');
                  } else {
                    foreach($RS_Bilhete as $row1) { $RS_Bil = $row1; break; }
                    $w_hn_bilhete = f($RS_Bil,'chave');
                    $w_hn_solic   = f($RS_Bil,'sq_siw_solicitacao');
                    fwrite($F1,$crlf.f($RS_Bil,'codigo_interno').' - '.f($RS_Bil,'nm_beneficiario'));
                    if (f($RS_Bil,'faturado')=='S') {
                      $w_erro=1; 
                      fwrite($F1,$crlf.'Número do bilhete: já há fatura para o bilhete número "'.$w_bilhete.'" da cia "'.$w_cia.'"');
                    }
                    if (f($RS_Bil,'cd_pai')!=$projeto) {
                      $w_erro=1; 
                      fwrite($F1,$crlf.'Número do bilhete: '.f($RS_Bil,'codigo_interno').' está vinculada ao projeto '.f($RS_Bil,'cd_pai').', divergindo do projeto da fatura');
                    }
                  }
                }
              }
              
              $w_emissao_bil= trim($row[8]);
              $w_temp = explode('/',$w_emissao_bil); $w_emissao_bil = substr(100+$w_temp[0],1,2).'/'.substr(100+$w_temp[1],1,2).'/'.substr(10000+$w_temp[2],1,4);
              // Valida o campo data de emissão do bilhete
              $w_result = fValidate(1,$w_emissao_bil,'data de emissão do bilhete','DATA',1,10,10,'','0123456789/');
              if ($w_result>'') { 
                $w_erro=1; 
                fwrite($F1,$crlf.'Data de emissão do bilhete: '.$w_result); 
              } else {
                $w_temp = toDate($w_emissao_bil);
                if ($w_temp<$inicio_dec || $w_temp>$fim_dec) {
                  $w_erro=1; 
                  fwrite($F1,$crlf.'Data de emissão do bilhete ('.$w_emissao_bil.'): deve estar contida no decêndio da fatura'); 
                }
                if ($w_hn_bilhete) {
                  if (f($RS_Bil,'data')!=$w_temp) {
                    $w_erro=1; fwrite($F1,$crlf.'Data de emissão do bilhete ('.$w_emissao_bil.'): valor constante do arquivo diverge do valor registrado na base de dados ('.formataDataEdicao(f($RS_Bil,'data')).')'); 
                  }
                }
              }
              
              $w_trechos    = trim($row[9]);
              // Valida o campo trechos
              $w_result = fValidate(1,$w_trechos,'trechos','',1,3,60,'1','1');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Trechos: '.$w_result); }
              
              $w_projeto    = trim($row[10]);
              if ($projeto!=$w_projeto) {
                $w_erro=1; 
                fwrite($F1,$crlf.'Código do projeto: todos as linhas devem ter o mesmo valor'); 
              }
              // Valida o campo código do projeto
              $w_result = fValidate(1,$w_projeto,'código do projeto','',1,3,60,'ABCDEFGHIJKLMNOPQRSTUVWXYZ ','0123456789 ');
              if ($w_result>'') { 
                $w_erro=1; 
                fwrite($F1,$crlf.'Código do projeto: '.$w_result); 
              } else {
                // Verifica se o projeto existe
                $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PJCAD');
                $RS = db_getSolicList::getInstanceOf($dbms, f($RS,'sq_menu'), $w_usuario, 'PJLIST', 5, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, $w_projeto, null, null, null, null, null, null, null);
                if (count($RS)==0) {
                  $w_erro=1; 
                  fwrite($F1,$crlf.'Código do projeto: na base de dados não há projeto ativo com o código "'.$w_projeto.'"');
                } elseif (count($RS)>1) {
                  $w_erro=1; 
                  fwrite($F1,$crlf.'Código do projeto: há mais de um projeto ativo com o código "'.$w_projeto.'"');
                } else {
                  foreach($RS as $row1) { $w_hn_projeto = f($row1,'sq_siw_solicitacao'); break; } 
                }
              }
              
              $w_valor_pleno= trim($row[11]);
              if (strpos($w_valor_pleno,',')===false) $w_valor_pleno .= ',00';
              // Valida o campo valor pleno do bilhete
              $w_result = fValidate(1,$w_valor_pleno,'valor pleno do bilhete','VALOR',1,3,18,'','0123456789,.');
              if ($w_result>'') { 
                $w_erro=1; fwrite($F1,$crlf.'Valor pleno do bilhete: '.$w_result); 
              } elseif ($w_hn_bilhete) {
                if (formatNumber(f($RS_Bil,'valor_bilhete_cheio'))!=$w_valor_pleno) {
                  $w_erro=1; fwrite($F1,$crlf.'Valor pleno do bilhete: valor constante do arquivo ('.$w_valor_pleno.') diverge do valor registrado na base de dados ('.formatNumber(f($RS_Bil,'valor_bilhete_cheio')).')'); 
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
                $w_erro=1; fwrite($F1,$crlf.'Percentual de desconto: '.$w_result); 
              } else {
                if (count($RS_Desconto)==0) {
                  $w_erro=1; fwrite($F1,$crlf.'Percentual de desconto: não há tabela ativa de desconto cadastrada para a agência de viagens emissora da fatura'); 
                } else {
                  $desconto = round(100*(1-(toNumber($w_valor_bil)/toNumber($w_valor_pleno))),2);
                  if (formatNumber($desconto)!=$w_desconto) {
                    // Verifica se o desconto está correto
                    $w_erro=1; fwrite($F1,$crlf.'Percentual de desconto: valor constante do arquivo ('.$w_desconto.') diverge do valor calculado ('.formatNumber($desconto).')');
                  } else {
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
                $w_erro=1; fwrite($F1,$crlf.'Valor do bilhete: '.$w_result); 
              } elseif ($w_hn_bilhete) {
                if (formatNumber(f($RS_Bil,'valor_bilhete'))!=$w_valor_bil) {
                  $w_erro=1; fwrite($F1,$crlf.'Valor do bilhete: valor constante do arquivo ('.$w_valor_bil.') diverge do valor registrado na base de dados ('.formatNumber(f($RS_Bil,'valor_bilhete')).') '.f($RS_Bil,'chave')); 
                }
              }
              
              $w_ret_tarifa = trim($row[14]);
              if (strpos($w_ret_tarifa,',')===false) $w_ret_tarifa .= ',00';
              // Valida o campo valor da tarifa retornado
              $w_result = fValidate(1,$w_ret_tarifa,'valor retido da tarifa','VALOR',1,3,18,'','0123456789,.');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Valor retido da tarifa: '.$w_result); }
              
              $w_taxa       = trim($row[15]);
              if (strpos($w_taxa,',')===false) $w_taxa .= ',00';
              // Valida o campo valor da taxa
              $w_result = fValidate(1,$w_taxa,'valor da taxa','VALOR',1,3,18,'','0123456789,.');
              if ($w_result>'') { 
                $w_erro=1; fwrite($F1,$crlf.'Valor da taxa: '.$w_result); 
              } elseif ($w_hn_bilhete) {
                if (formatNumber(f($RS_Bil,'valor_taxa_embarque')+f($RS_Bil,'valor_pta'))!=$w_taxa) {
                  $w_erro=1; fwrite($F1,$crlf.'Valor da taxa: valor constante do arquivo ('.$w_taxa.') diverge do valor registrado na base de dados ('.formatNumber(f($RS_Bil,'valor_taxa_embarque')+f($RS_Bil,'valor_pta')).')'); 
                }
              }
              
              $w_ret_taxa   = trim($row[16]);
              if (strpos($w_ret_taxa,',')===false) $w_ret_taxa .= ',00';
              // Valida o campo valor da taxa retornado
              $w_result = fValidate(1,$w_ret_taxa,'valor retido da taxa','VALOR',1,3,18,'','0123456789,.');
              if ($w_result>'') { $w_erro=1; fwrite($F1,$crlf.'Valor retido da taxa: '.$w_result); }
              
              $w_desc_contr = trim($row[17]);
              if (strpos($w_desc_contr,',')===false) $w_desc_contr .= ',00';
              // Valida o campo valor do desconto contratual
              $w_result = fValidate(1,$w_desc_contr,'valor do desconto contratual','VALOR',1,3,18,'','0123456789,.');
              if ($w_result>'') { 
                $w_erro=1; fwrite($F1,$crlf.'Valor do desconto contratual: '.$w_result); 
              } else {
                $desconto_bilhete = round((toNumber($w_valor_bil) * $desconto_padrao / 100),2);
                if ($w_desc_contr!=formatNumber($desconto_bilhete)) {
                  $w_erro=1; fwrite($F1,$crlf.'Valor do desconto contratual: valor constante do arquivo ('.$w_desc_contr.') diverge do valor calculado ('.formatNumber($desconto_bilhete).')'); 
                }
              }
              
              $w_valor_total= trim($row[18]);
              if (strpos($w_valor_total,',')===false) $w_valor_total .= ',00';
              // Valida o campo valor total do bilhete
              $w_result = fValidate(1,$w_valor_total,'valor total do bilhete','VALOR',1,3,18,'','0123456789,.');
              if ($w_result>'') { 
                $w_erro=1; fwrite($F1,$crlf.'Valor total do bilhete: '.$w_result); 
              } elseif ($w_hn_bilhete) {
                $valor_total = formatNumber(f($RS_Bil,'valor_bilhete')-$desconto_bilhete+f($RS_Bil,'valor_taxa_embarque')+f($RS_Bil,'valor_pta'));
                if ($valor_total!=$w_valor_total) {
                  $w_erro=1; fwrite($F1,$crlf.'Valor total do bilhete: valor constante do arquivo ('.$w_valor_total.') diverge do valor calculado ('.$valor_total.')'); 
                }
              }
              
              // Guarda dados para gravação
              $faturas[$w_fatura]['fatura'] = $w_fatura;
              $faturas[$w_fatura]['projeto'] = $w_hn_projeto;
              $faturas[$w_fatura]['inicio'] = $w_inicio;
              $faturas[$w_fatura]['fim'] = $w_fim;
              $faturas[$w_fatura]['emissao'] = $w_emissao_fat;
              $faturas[$w_fatura]['vencimento'] = $w_venc;
              $faturas[$w_fatura]['valor'] = $w_valor_fat;
              if ($w_erro>1) $faturas[$w_fatura]['erro'] = 'erro';
              
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
              
              $w_cont      += 1;
              $w_registros += 1;
              if ($w_erro==0)   $w_importados += 1;
              else              $w_rejeitados += 1;
            }
          }
          fclose($F1);
          if (is_array($faturas)) {
            foreach($faturas as $row) {
              if (nvl(f($row,'erro'),'')!='xxx') {
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
                  $bilhete_grava[$w_bilhete]['fatura'] = $w_fatura;
                  $bilhete_grava[$w_bilhete]['solicitacao'] = f($row1,'solicitacao');
                  $bilhete_grava[$w_bilhete]['cia'] = f($row1,'cia');
                  $bilhete_grava[$w_bilhete]['desconto'] = f($row1,'desconto');
                  $bilhete_grava[$w_bilhete]['emissao'] = f($row1,'emissao');
                  $bilhete_grava[$w_bilhete]['numero'] = f($row1,'numero');
                  $bilhete_grava[$w_bilhete]['trecho'] = f($row1,'trecho');
                  $bilhete_grava[$w_bilhete]['valor_cheio'] = f($row1,'valor_cheio');
                  $bilhete_grava[$w_bilhete]['valor'] = f($row1,'valor');
                  $bilhete_grava[$w_bilhete]['embarque'] = f($row1,'embarque');
                }
              }
            }
          }
          //print_r($faturas);
          //print_r($bilhetes);
          //print_r($bilhete_grava);
          //exit;
          // Configura o valor dos campos necessários para gravação
          $w_arquivo_registro   = 'registro.txt';
          $w_tamanho_registro   = filesize($w_caminho.$w_caminho_registro);
          $w_tipo_registro      = mime_content_type($w_caminho.$w_caminho_registro);
          // Grava o resultado da importação no banco de dados
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
          ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
          ScriptClose();
        } 
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