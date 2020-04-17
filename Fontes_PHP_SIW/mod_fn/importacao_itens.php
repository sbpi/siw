<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'funcoes_valida.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getCcData.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicRubrica.php');
include_once($w_dir_volta.'classes/sp/db_getLancamentoDoc.php');
include_once($w_dir_volta.'classes/sp/db_getLancamentoItem.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoItem.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
// =========================================================================
//  /importacao_itens.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Executa procedimento de importação de itens de lançamento financeiro a partir de arquivo CSV
// Mail     : alex@sbpi.com.br
// Criacao  : 02/02/2020, 10:00
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
$P1         = nvl($_REQUEST['P1'],9);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = upper(nvl($_REQUEST['SG'],$_REQUEST['f_SG']));
$R          = $_REQUEST['R'];
$O          = upper(nvl($_REQUEST['O'],$_REQUEST['f_O']));
$w_assinatura   = $_REQUEST['w_assinatura'];
$w_pagina       = 'importacao_itens.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_fn/';
$w_troca        = $_REQUEST['w_troca'];

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = $_REQUEST['w_menu'];
$w_TP       = RetornaTitulo($TP, $O);

$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);

Main();
FechaSessao($dbms);
exit;
// =========================================================================
// Rotina de importação de itens a partir de arquivo CSV
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave              = $_REQUEST['w_chave'];
  $w_sq_lancamento_doc  = $_REQUEST['w_sq_lancamento_doc'];
  $w_incid_tributo      = 'N';
  $w_incid_retencao     = 'N';
  // Recupera os dados do lançamento
  $sql = new db_getSolicData; $RS1 = $sql->getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
  // Estes dados são recuperados de RS1 (DB_GETSOLICDATA)
  $w_moeda          = f($RS1,'sq_moeda');
  $w_nm_moeda       = f($RS1,'nm_moeda');
  $w_dados_pai      = explode('|@|',f($RS1,'dados_pai'));
  $w_sigla_pai      = $w_dados_pai[5];
  $w_modulo_pai     = $w_dados_pai[11];
  $w_solic_vinculo  = f($RS1,'sq_solic_vinculo');

  // Recupera os dados do documento vinculado ao lançamento
  $sql = new db_getLancamentoDoc; $RS_Doc = $sql->getInstanceOf($dbms,$w_chave,$w_sq_lancamento_doc,null,null,null,null,null,null);
  $w_doc_unico = false;
  if (count($RS_Doc)==1) {
    $RS_Doc = $RS_Doc[0];
    if ($O=='L') $O = 'A';
    $w_doc_unico = true; // Se tem apenas um documento, abre direto a tela de alteração.
  }
  
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_chave        = $_REQUEST['w_chave'];
    $w_nome         = $_REQUEST['w_nome'];
    $w_codigo       = $_REQUEST['w_codigo'];
    $w_ativo        = $_REQUEST['w_ativo'];
    $w_pais         = $_REQUEST['w_pais'];
    $w_regiao       = $_REQUEST['w_regiao'];
    $w_uf           = $_REQUEST['w_uf'];
    $w_cidade       = $_REQUEST['w_cidade'];
    $w_pais         = $_REQUEST['w_pais'];
    $w_regiao       = $_REQUEST['w_regiao'];
    $w_uf           = $_REQUEST['w_uf'];
    $w_cidade       = $_REQUEST['w_cidade'];
    $w_peso         = $_REQUEST['w_peso'];
  } elseif (!(strpos('AEV',$O)===false)) {
    // Recupera os dados do endereço informado
    $sql = new db_getTipoRestricao; $RS = $sql->getInstanceOf($dbms,$w_chave,$w_cliente,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_chave    = f($RS,'chave');
    $w_cliente  = f($RS,'cliente');
    $w_nome     = f($RS,'nome');
    $w_codigo   = f($RS,'codigo_externo');
    $w_ativo    = f($RS,'ativo');
  } 
  Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Importação de Itens</TITLE>');
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  Validate('w_caminho','Arquivo','','1','5','255','1','1');
  ShowHTML('    if (theForm.w_caminho.value.toUpperCase().lastIndexOf("CSV")==-1) {');
  ShowHTML('       alert(\'Só é possível escolher arquivos com a extensão ".CSV"!\');');
  ShowHTML('       theForm.w_caminho.focus();');
  ShowHTML('       return false;');
  ShowHTML('    }');
  ShowHTML('  theForm.Botao.disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  } 
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr><td colspan=3 bgcolor="#FAEBD7"><table border=1 width="100%"><tr><td>');
  ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr><td colspan=3><b>'.upper(f($RS1,'nome')).' '.f($RS1,'codigo_interno').' ('.$w_chave.')</b></td>');
  ShowHTML('      <tr><td colspan="3">Finalidade: <b>'.CRLF2BR(f($RS1,'descricao')).'</b></td></tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('          <td>Forma de pagamento:<br><b>'.f($RS1,'nm_forma_pagamento').' </b></td>');
  ShowHTML('          <td>Vencimento:<br><b>'.FormataDataEdicao(f($RS1,'vencimento')).' </b></td>');
  ShowHTML('          <td>Valor:<br><b>'.((nvl($w_moeda,'')=='') ? '' : f($RS1,'sb_moeda').' ').formatNumber(Nvl(f($RS1,'valor'),0)).' </b></td>');
  ShowHTML('    </TABLE>');
  ShowHTML('</TABLE>');
  ShowHTML('  <tr><td>&nbsp;');
  ShowHTML('    ');
  ShowHTML('<tr><td align="center"><table width="97%" border="0">');
  ShowHTML('<tr><td>');
  ShowHTML('  <a class="HL" href="'.montaURL_JS($w_dir,$R.'&O=L&w_menu='.$w_menu.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'">Voltar</a>&nbsp;');
  ShowHTML('<tr><td colspan="3" bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);"><ol>');
  ShowHTML('  A finalidade desta tela é importar itens de um lançamento financeiro. O procedimento consiste em:');
  ShowHTML('  <li>Elabore uma planilha no MS-Excel ou no LibreOffice Calc com os itens que deseja importar. <a href="'.$w_dir.'instrucoes.htm">Clique aqui para ver as instruções</a>.');
  ShowHTML('  <li>Salve a planilha no formato CSV.');
  ShowHTML('  <li>Clique no botão "Procurar..." e localize o arquivo CSV no seu computador.');
  ShowHTML('  <li>Clique no botão "Importar" para executar o procedimento de importação.');
  ShowHTML('  <li>Após o término da importação verifique todos os itens, pois pode ser necessário ajustar seus dados.');
  ShowHTML('  </td>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
  ShowHTML('<tr><td colspan="3" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><ul><b><font color="#BC3131">ATENÇÃO:</font></b>');
  ShowHTML('  <li>TODOS OS ITENS EXISTENTES SERÃO AUTOMATICAMENTE APAGADOS, antes dos itens do arquivo serem incluídos.');
  ShowHTML('  <li>O TAMANHO MÁXIMO aceito para o arquivo é de '.(f($RS,'upload_maximo')/1024).' KBytes');
  ShowHTML('</tr>');
  ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG='.$SG.'&O='.$O.'" name="Form" enctype="multipart/form-data" onSubmit="return(Validacao(this));" method="POST">');
  ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
  ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
  ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
  ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
  ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
  ShowHTML('<INPUT type="hidden" name="R" value="'.$R.'">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_solic_vinculo" value="'.$w_solic_vinculo.'">');
  ShowHTML('<INPUT type="hidden" name="w_sq_lancamento_doc" value="'.$w_sq_lancamento_doc.'">');
  ShowHTML('<INPUT type="hidden" name="w_atual" value="'.$w_caminho.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
  ShowHTML('      <tr><td colspan=3><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OBRIGATÓRIO. Clique no botão ao lado para localizar o arquivo. Ele será transferido automaticamente para o servidor.">');
  if ($w_caminho>'') {
    ShowHTML('              <b>'.LinkArquivo('SS',$w_cliente,$w_caminho,'_blank','Clique para exibir o arquivo atual.','Exibir',null).'</b>');
  } 
  ShowHTML('      <tr><td align="center" colspan=3 ><hr>');
  ShowHTML('            <input class="STB" type="submit" name="Botao" value="Importar">');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape(); 
} 

// =========================================================================
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  
  $w_file       = '';
  $w_tamanho    = '';
  $w_tipo       = '';
  $w_nome       = '';
  Cabecalho();
  head();
  
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  BodyOpen('onLoad=this.focus();');
  
  if ($SG=='IMPORTITEM') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
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
            $w_caminho            = $conFilePhysical.$w_cliente.'/';
            $w_caminho_recebido   = str_replace('.tmp','',basename($Field['tmp_name']));
            $w_tamanho_recebido   = $Field['size'];
            $w_tipo_recebido      = $Field['type'];
            $w_nome_recebido      = $Field['name'];
            if ($w_caminho_recebido>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_caminho_recebido);
            $w_caminho_registro   = str_replace(substr($w_caminho_recebido,strpos($w_caminho_recebido,'.'),30),'',$w_caminho_recebido).'r'.substr($w_caminho_recebido,strpos($w_caminho_recebido,'.'),30);
          } 
        } 
        
        ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/relogio.gif" align="center"> Aguarde: importando <b>'.$w_nome_recebido.'</b><br><br><br><br><br><br><br><br><br><br></center></div>');
        Rodape();
        flush();
        
        //Abre o arquivo recebido para gerar o arquivo registro
        $F2 = file($w_caminho.$w_caminho_recebido);
        
        if (is_array($F2)) {
          
          // Varre o arquivo recebido, linha a linha, e cria tabela com os valores lidos
          $table = array();
          for ($w_cont=0; $w_cont<count($F2); $w_cont++) {
            $linha = explode(';', $F2[$w_cont]);
            if (nvl($linha[0],'')=='') continue; // Linha sem dados
            array_push($table, $linha);
          }
          
          // Cria array com as rubricas e remove espaços em branco antes e depois do valor
          $rub = array();
          $w_cont = 0;
          for ($lin=0; $lin<count($table); $lin++) {
            foreach($table[$lin] as $col => $val) {
              $val = trim($val);
              if ($lin==0) { 
                // Verifica a integridade do cabeçalho (terceira coluna em diante são as rubricas)
                if ($col>1) {
                  if($col % 2 == 0){
                    $w_result = fValidate(1,$val,'Rubrica','',1,1,20,'','0123456789.');
                    if ($w_result>'') { 
                      $w_erro.=$crlf.'<li>Linha '.($lin+1).' Coluna '.($col+1).' ['.$val.'] '.$w_result.' Rubrica deve ser informada como consta no projeto. São aceitos apenas números e o caracter ponto "."'; 
                    } else {
                      // Verifica se a rubrica existe no projeto
                      $sql = new db_getSolicRubrica; 
                      $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_solic_vinculo'],null,'S',null,$val,null,null,null,'SELECAO');
                      if (count($RS)) {
                        $rub[$w_cont]['codigo'] = $val;
                        $rub[$w_cont]['chave']  = $RS[0]['sq_projeto_rubrica'];
                      } else {
                        $w_erro.=$crlf.'<li>Linha '.($lin+1).' Coluna '.($col+1).' ['.$val.'] Rubrica inexistente'; 
                      }                  
                    }
                  } else {
                    $w_result = fValidate(1,$val,'Nome','',1,1,100,'1','1');
                    if ($w_result>'') { 
                      $w_erro.=$crlf.'<li>Linha '.($lin+1).' Coluna '.($col+1).' ['.$val.'] '.$w_result.' Informe o texto que deseja agregar à descrição do item.'; 
                    } else {
                      $rub[$w_cont]['descricao'] = $val;
                      $w_cont++;
                    }
                  }
                }
              } else {
                // verifica a integridade dos dados (2ª linha em diante)
                $w_result = '';
                if ($col==0) {
                  $w_result = fValidate(1,$val,'Ordem','',1,1,4,'','0123456789');
                  if ($w_result>'') { 
                    $w_erro.=$crlf.'<li>Linha '.($lin+1).' Coluna '.($col+1).' ['.$val.'] '.$w_result.' A ordem do item deve ser um número inteiro de até 4 posições e usar o ponto como separador de milhar.'; 
                  }
                } elseif ($col==1) {
                  $w_result = fValidate(1,$val,'Descrição','',1,1,400,'1','1');
                  if ($w_result>'') { 
                    $w_erro.=$crlf.'<li>Linha '.($lin+1).' Coluna '.($col+1).' ['.$val.'] '.$w_result.' A descrição do item deve ser texto de até 400 caracteres, exceto ponto-e-virgula.'; 
                  }
                } else {
                  // As colunas daqui para frente são de quantidades e valores
                  if($col % 2 == 0){
                    $w_result = fValidate(1,$val,'Quantidade','',1,1,10,'','0123456789.');
                    if ($w_result>'') { 
                      $w_erro.=$crlf.'<li>Linha '.($lin+1).' Coluna '.($col+1).' ['.$val.'] '.$w_result.' A quantidade deve ser um número inteiro e usar o ponto como separador de milhar.'; 
                    }
                  } else {
                    $w_result = fValidate(1,$val,'Valor','',1,1,18,'','0123456789,.-');
                    if ($w_result>'') { 
                      $w_erro.=$crlf.'<li>Linha '.($lin+1).' Coluna '.($col+1).' ['.$val.'] '.$w_result.' O valor unitário deve ter duas casas decimais, ponto como separador de milhar e vírgula como separador de decimais.'; 
                    }
                  }
                }
              }
              $table[$lin][$col] = $val;
            }
          }
          
          if ($w_erro) {

            ShowHTML('  <p>ATENÇÃO: a importação não pode ser realizada devido aos erros indicados abaixo. Corrija-os e tente novamente.</p>');
            ShowHTML('  <ul>'.$w_erro.'</ul');
            ShowHTML('  <p>Clique <a href="'.montaURL_JS($w_dir,$R.'&w_menu='.$_REQUEST['w_menu'].'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'">aqui</a> para voltar à tela anterior.</p>');
            

          } else {
            // prepara os dados para gravação
            $itens = array();
            $item  = 0;
            for ($lin=1; $lin<count($table); $lin++) {
              foreach($rub as $k => $v) {
                $itens[$item]['rubrica']     = $rub[$k]['chave'];
                $itens[$item]['ordem']       = $table[$lin][0];
                $itens[$item]['descricao']   = $table[$lin][1].' - '.$rub[$k]['descricao'];
                $itens[$item]['quantidade']  = $table[$lin][2+($k*2)];
                $itens[$item]['valor']       = $table[$lin][3+($k*2)];
                $item++;
              }
            }
            
            // apaga os itens existentes
            $sql = new db_getLancamentoItem; $RS = $sql->getInstanceOf($dbms,null,null,nvl($_REQUEST['w_chave'],0),null,null);
            $SQL = new dml_putLancamentoItem; 
            foreach($RS as $row) {
              $SQL->getInstanceOf($dbms,'E',$_REQUEST['w_sq_lancamento_doc'],f($row,'sq_documento_item'),null,null,null,null,null,null,null,null,null);
            }
            
            // grava apenas os registros que tem as colunas quantidade e valor unitário maiores que zero
            $SQL = new dml_putLancamentoItem; 
            foreach($itens as $row) {
              if (f($row,'quantidade')<>'0' && f($row,'valor') <> '0,00' && f($row,'valor') <> '0') {
                $SQL->getInstanceOf($dbms,'I',$_REQUEST['w_sq_lancamento_doc'],null,f($row,'rubrica'),f($row,'descricao'),
                  f($row,'quantidade'),f($row,'valor'),f($row,'ordem'),null,null,null);
              }
            }

            ScriptOpen('JavaScript');
            ShowHTML('  alert("Importação realizada com sucesso!");');
            ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_menu='.$_REQUEST['w_menu'].'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
            ScriptClose();

          }

        }

      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
        ScriptClose();
      } 
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ShowHTML('  history.back(1);');
      ScriptClose();
    } 
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('  alert("Bloco de dados não encontrado: '.$SG.'");');
    ShowHTML('  history.back(1);');
    ScriptClose();
  }
} 
// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'INICIAL':      Inicial();           break;
    case 'GRAVA':        Grava();             break;
    default:
      cabecalho();
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