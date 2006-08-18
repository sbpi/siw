<?
  session_start();
  session_register("dbms_session");
  session_register("schema_session");
  session_register("p_cliente_session");
  session_register("sq_pessoa_session");
  session_register("ano_session");
  session_register("siw_email_conta_session");
  session_register("siw_email_nome_session");
  session_register("siw_email_senha_session");
  session_register("smtp_server_session");
  session_register("schema_is_session");
  session_register("LogOn_session");
  session_register("Username_session");
?>
<? // asp2php (vbscript) converted
?>
<? // Option $Explicit; ?>
<!-- #INCLUDE FILE="../Constants.inc" -->
<!-- #INCLUDE FILE="../DB_Geral.php" -->
<!-- #INCLUDE FILE="../DB_Cliente.php" -->
<!-- #INCLUDE FILE="../DB_Seguranca.php" -->
<!-- #INCLUDE FILE="../jScript.php" -->
<!-- #INCLUDE FILE="../Funcoes.php" -->
<!-- #INCLUDE FILE="../Funcoes_Valida.php" -->
<!-- #INCLUDE FILE="../cp_upload/_upload.php" -->
<!-- #INCLUDE FILE="DB_SIAFI.php" -->
<!-- #INCLUDE FILE="DML_SIAFI.php" -->
<!-- #INCLUDE FILE="Funcoes.php" -->
<!-- #INCLUDE FILE="DB_Tabelas.php" -->
<!-- #INCLUDE FILE="DML_Tabelas.php" -->
<? 
header("Expires: ".-1500);
// =========================================================================

//  /SIAFI.asp

// ------------------------------------------------------------------------

// Nome     : Alexandre Vinhadelli Papad�polis

// Descricao: Rotinas de importa��o de dados financeiros a partir do SIAFI

// Mail     : alex@sbpi.com.br

// Criacao  : 30/07/2004, 11:03

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

if ($LogOn_session!="Sim")
{

  EncerraSessao();
} 


// Declara��o de vari�veis

// $RS is of type "ADODB.RecordSet"

// $RS1 is of type "ADODB.RecordSet"

// $RS2 is of type "ADODB.RecordSet"

// $RS3 is of type "ADODB.RecordSet"

// $RS4 is of type "ADODB.RecordSet"

// $RS_Menu is of type "ADODB.RecordSet"




AbreSessao();

// Carrega vari�veis locais com os dados dos par�metros recebidos

$Par=strtoupper(${"Par"});
$w_Pagina="SIAFI.asp?par=";
$w_Dir="mod_or_pub/";
$w_dir_volta="../";
$w_Disabled="ENABLED";

$SG=strtoupper(${"SG"});
$O=strtoupper(${"O"});
$w_cliente=RetornaCliente();
$w_usuario=RetornaUsuario();
$w_menu=RetornaMenu($w_cliente,$SG);

$ul=new ASPForm()

if (${"UploadID"}>"")
{

  $UploadID=${"UploadID"};
}
  else
{

  $UploadID=$ul->NewUploadID;
} 


// Configura o caminho para grava��o f�sica de arquivos

$w_caminho=$conFilePhysical.$w_cliente."\";

if ((strpos(strtoupper($_SERVER["http_content_type"]),"MULTIPART/FORM-DATA") ? strpos(strtoupper($_SERVER["http_content_type"]),"MULTIPART/FORM-DATA")+1 : 0)>0)
{

set_time_limit(2000);
$ul->SizeLimit=0x$A00000;
  if ($UploadID>0)
  {

$ul->UploadID=$UploadID;
  } 

  $w_troca=$ul->Texts.$Item["w_troca"];
  $w_copia=$ul->Texts.$Item["w_copia"];
  $p_responsavel=strtoupper($ul->Texts.$Item["p_responsavel"]);
  $p_dt_ini=$ul->Texts.$Item["p_dt_ini"];
  $p_dt_fim=$ul->Texts.$Item["p_dt_fim"];
  $p_imp_ini=$ul->Texts.$Item["p_imp_ini"];
  $p_imp_fim=$ul->Texts.$Item["p_imp_fim"];

  $P1=$ul->Texts.$Item["P1"];
  $P2=$ul->Texts.$Item["P2"];
  $P3=$ul->Texts.$Item["P3"];
  $P4=$ul->Texts.$Item["P4"];
  $TP=$ul->Texts.$Item["TP"];
  $R=strtoupper($ul->Texts.$Item["R"]);
  $w_Assinatura=strtoupper($ul->Texts.$Item["w_Assinatura"]);
}
  else
{

  $w_troca=${"w_troca"};
  $w_copia=${"w_copia"};
  $p_responsavel=strtoupper(${"p_responsavel"});
  $p_dt_ini=${"p_dt_ini"};
  $p_dt_fim=${"p_dt_fim"};
  $p_imp_ini=${"p_imp_ini"};
  $p_imp_fim=${"p_imp_fim"};

  $P1=Nvl(${"P1"},0);
  $P2=Nvl(${"P2"},0);
  $P3=$cDbl[Nvl(${"P3"},1)];
  $P4=$cDbl[Nvl(${"P4"},$conPagesize)];
  $TP=${"TP"};
  $R=strtoupper(${"R"});
  $w_Assinatura=strtoupper(${"w_Assinatura"});
} 


if ($O=="")
{

  if ($par=="REL_PPA" || $par=="REL_INICIATIVA")
  {

    $O="P";
  }
    else
  {

    $O="L";
  } 

} 


switch ($O)
{
  case "I":
    $w_TP=$TP." - Inclus�o";
    break;
  case "A":
    $w_TP=$TP." - Altera��o";
    break;
  case "E":
    $w_TP=$TP." - Exclus�o";
    break;
  case "P":
    $w_TP=$TP." - Filtragem";
    break;
  case "C":
    $w_TP=$TP." - C�pia";
    break;
  case "V":
    $w_TP=$TP." - Envio";
    break;
  case "H":
    $w_TP=$TP." - Heran�a";
    break;
  case "O":
    $w_TP=$TP." - Orienta��es";
    break;
  default:

    $w_TP=$TP." - Listagem";
    break;
} 

// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.

DB_GetLinkSubMenu($p_cliente_session,$SG);
if (mysql_num_rows($RS_query)>0)
{

  $w_submenu="Existe";
}
  else
{

  $w_submenu="";
} 

DesconectaBD();

// Recupera a configura��o do servi�o

if ($P2>0)
{
  DB_GetMenuData($P2);} 
}
  else
{
DB_GetMenuData;
}
($w_menu);
if ($RS_menu["ultimo_nivel"]="S"$Then;
) // Se for sub-menu, pega a configura��o do pai
{

  DB_GetMenuData($RS_menu["sq_menu_pai"]);
} 


Main();

FechaSessao();

$p_responsavel=null;

$p_dt_ini=null;

$p_dt_fim=null;

$p_imp_ini=null;

$p_imp_fim=null;

$w_chave=null;

$w_copia=null;

$w_filtro=null;

$w_menu=null;

$w_usuario=null;

$w_cliente=null;

$w_filter=null;

$w_cor=null;

$ul=null;

$File=null;

$w_sq_pessoa=null;

$w_troca=null;

$w_submenu=null;

$w_reg=null;

$w_caminho=null;


$RS=null;

$RS1=null;

$RS2=null;

$RS3=null;

$RS4=null;

$RS_menu=null;

$Par=null;

$P1=null;

$P2=null;

$P3=null;

$P4=null;

$TP=null;

$SG=null;

$R=null;

$O=null;

$w_Classe=null;

$w_Cont=null;

$w_Pagina=null;

$w_Disabled=null;

$w_TP=null;

$w_Assinatura=null;

$w_dir=null;

$w_dir_volta=null;

$UploadID=null;


// =========================================================================

// Rotina de importa��o de arquivos f�sicos para atualiza��o de dados financeiros

// -------------------------------------------------------------------------

function Inicial()
{
  extract($GLOBALS);



  $w_Chave=${"w_Chave"};
  $w_troca=${"w_troca"};

  if ($w_troca>"")
  {
// Se for recarga da p�gina

    $w_data=${"w_data"};
    $w_sq_pessoa=${"w_sq_pessoa"};
    $w_data_arquivo=${"w_data_arquivo"};
    $w_arquivo_recebido=${"w_arquivo_recebido"};
    $w_arquivo_registro=${"w_arquivo_registro"};
    $w_registros=${"w_registros"};
    $w_importados=${"w_importados"};
    $w_rejeitados=${"w_rejeitados"};
    $w_situacao=${"w_situacao"};
  }
    else
  if ($O=="L")
  {

// Recupera todos os registros para a listagem

    DB_GetOrImport($RS,$w_chave,$w_cliente,$p_responsavel,$p_dt_ini,$p_dt_fim,$p_imp_ini,$p_imp_fim);
$RS->Sort="data_arquivo desc";
  }
    else
  if ((strpos("AEV",$O) ? strpos("AEV",$O)+1 : 0)>0 && $w_Troca=="")
  {

// Recupera os dados do endere�o informado

    DB_GetOrPrioridade($RS,null,$w_cliente,$w_chave,null,null,null);
    $w_data=$RS["data"];
    $w_sq_pessoa=$RS["sq_pessoa"];
    $w_data_arquivo=$RS["data_arquivo"];
    $w_arquivo_recebido=$RS["arquivo_recebido"];
    $w_arquivo_registro=$RS["arquivo_registro"];
    $w_registros=$RS["registros"];
    $w_importados=$RS["importados"];
    $w_rejeitados=$RS["rejeitados"];
    $w_situacao=$RS["situacao"];
    DesconectaBD();
  } 


  Cabecalho();
  ShowHTML("<HEAD>");
  if ((strpos("IAEP",$O) ? strpos("IAEP",$O)+1 : 0)>0)
  {

    ScriptOpen("JavaScript");
    CheckBranco();
    FormataDataHora();
    ProgressBar($w_dir_volta,$UploadID);
    ValidateOpen("Validacao");
    if ((strpos("IA",$O) ? strpos("IA",$O)+1 : 0)>0)
    {

      Validate("w_data_arquivo","Data e hora","DATAHORA","1","17","17","","0123456789 /:,");
      Validate("w_arquivo_recebido","Arquivo de dados","1","1","1","255","1","1");
      Validate("w_assinatura","Assinatura Eletr�nica","1","1","6","30","1","1");
    }
      else
    if ($O=="E")
    {

      Validate("w_assinatura","Assinatura Eletr�nica","1","1","6","30","1","1");
      ShowHTML("  if (confirm('Confirma a exclus�o deste registro?')) ");
      ShowHTML("     { return (true); }; ");
      ShowHTML("     { return (false); }; ");
    } 

    ShowHTML("  theForm.Botao[0].disabled=true;");
    ShowHTML("  theForm.Botao[1].disabled=true;");
    ShowHTML("if (theForm.w_arquivo_recebido.value != '') {return ProgressBar();}");
    ValidateClose();
    ScriptClose();
  } 

  ShowHTML("</HEAD>");
  ShowHTML("<BASE HREF=\"".$conRootSIW."\">");
  if ($w_troca>"")
  {

    BodyOpen("onLoad='document.Form.".$w_troca.".focus()';");
  }
    else
  if ((strpos("IA",$O) ? strpos("IA",$O)+1 : 0)>0)
  {

    BodyOpen("onLoad='document.Form.w_data_arquivo.focus()';");
  }
    else
  if ($O=="E")
  {

    BodyOpen("onLoad='document.Form.w_assinatura.focus()';");
  }
    else
  {

    BodyOpen("onLoad='document.focus()';");
  } 

  ShowHTML("<B><FONT COLOR=\"#000000\">".$w_TP."</FONT></B>");
  ShowHTML("<HR>");
  ShowHTML("<div align=center><center>");
  ShowHTML("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">");
  if ($O=="L")
  {

    AbreSessao();
// Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem

    ShowHTML("<tr><td><font size=\"1\">");
    ShowHTML("        <a accesskey=\"I\" class=\"SS\" href=\"".$w_dir.$w_Pagina.$par."&R=".$w_Pagina.$par."&O=I&w_chave=".$w_chave."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\"><u>I</u>ncluir</a>&nbsp;");
    ShowHTML("        <a accesskey=\"O\" class=\"SS\" href=\"".$w_dir.$w_Pagina."Help&R=".$w_Pagina.$par."&O=O&w_chave=".$w_chave."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\" target=\"help\"><u>O</u>rienta��es</a>&nbsp;");
    ShowHTML("    <td align=\"right\"><font size=\"1\"><b>Registros existentes: ".$RS->RecordCount);
    ShowHTML("<tr><td align=\"center\" colspan=3>");
    ShowHTML("    <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">");
    ShowHTML("        <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">");
    ShowHTML("          <td rowspan=2><font size=\"1\"><b>Data</font></td>");
    ShowHTML("          <td rowspan=2><font size=\"1\"><b>Executado em</font></td>");
    ShowHTML("          <td rowspan=2><font size=\"1\"><b>Respons�vel</font></td>");
    ShowHTML("          <td colspan=3><font size=\"1\"><b>Registros</font></td>");
    ShowHTML("          <td rowspan=2><font size=\"1\"><b>Opera��es</font></td>");
    ShowHTML("        </tr>");
    ShowHTML("        <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">");
    ShowHTML("          <td><font size=\"1\"><b>Total</font></td>");
    ShowHTML("          <td><font size=\"1\"><b>Aceitos</font></td>");
    ShowHTML("          <td><font size=\"1\"><b>Rejeitados</font></td>");
    ShowHTML("        </tr>");
    if ($RS->EOF)
    {
// Se n�o foram selecionados registros, exibe mensagem

      ShowHTML("      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=7 align=\"center\"><font size=\"1\"><b>N�o foram encontrados registros.</b></td></tr>");
    }
      else
    {

// Lista os registros selecionados para listagem

      while(!$RS->EOF)
      {

        if ($w_cor==$conTrBgColor || $w_cor=="")
        {
          $w_cor=$conTrAlternateBgColor;
        }
          else
        {
          $w_cor=$conTrBgColor;
        }
;
      } 
      ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\">");
      ShowHTML("        <td align=\"center\"><font size=\"1\">".FormataDataEdicao($RS["data_arquivo"])."</td>");
      ShowHTML("        <td align=\"center\"><font size=\"1\">".FormataDataEdicao($RS["data"])."</td>");
      ShowHTML("        <td title=\"".$RS["nm_resp"]."\"><font size=\"1\">".$RS["nm_resumido_resp"]."</td>");
      ShowHTML("        <td align=\"right\"><font size=\"1\">".$RS["registros"]."&nbsp;</td>");
      ShowHTML("        <td align=\"right\"><font size=\"1\">".$RS["importados"]."&nbsp;</td>");
      ShowHTML("        <td align=\"right\"><font size=\"1\">".$RS["rejeitados"]."&nbsp;</td>");
      ShowHTML("        <td align=\"top\" nowrap><font size=\"1\">");
      ShowHTML("          ".LinkArquivo("HL",$w_cliente,$RS["chave_recebido"],"_blank","Exibe os dados do arquivo importado.","Arquivo",null)."&nbsp");

      ShowHTML("          ".LinkArquivo("HL",$w_cliente,$RS["chave_result"],"_blank","Exibe o registro de importa��o.","Registro",null)."&nbsp");
      ShowHTML("        </td>");
      ShowHTML("      </tr>");
$RS->MoveNext;
    } 
  } 

  ShowHTML("      </center>");
  ShowHTML("    </table>");
  ShowHTML("  </td>");
  ShowHTML("</tr>");
  DesconectaBD();
}
  else
if ((strpos("IAEV",$O) ? strpos("IAEV",$O)+1 : 0)>0)
{

  if ((strpos("EV",$O) ? strpos("EV",$O)+1 : 0))
  {
    $w_Disabled=" DISABLED ";
  }
;
  } 
  ShowHTML("<FORM action=\"".$w_dir.$w_pagina."Grava&SG=".$SG."&O=".$O."&UploadID=".$UploadID."\" name=\"Form\" onSubmit=\"return(Validacao(this));\" enctype=\"multipart/form-data\" method=\"POST\">");
  ShowHTML("<INPUT type=\"hidden\" name=\"P1\" value=\"".$P1."\">");
  ShowHTML("<INPUT type=\"hidden\" name=\"P2\" value=\"".$P2."\">");
  ShowHTML("<INPUT type=\"hidden\" name=\"P3\" value=\"".$P3."\">");
  ShowHTML("<INPUT type=\"hidden\" name=\"P4\" value=\"".$P4."\">");
  ShowHTML("<INPUT type=\"hidden\" name=\"TP\" value=\"".$TP."\">");
  ShowHTML("<INPUT type=\"hidden\" name=\"R\" value=\"".$R."\">");

  ShowHTML("<INPUT type=\"hidden\" name=\"w_chave\" value=\"".$w_chave."\">");
  ShowHTML("<INPUT type=\"hidden\" name=\"w_troca\" value=\"\">");

  ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">");
  ShowHTML("    <table width=\"97%\" border=\"0\">");
  if ($O=="I" || $O=="A")
  {

    DB_GetCustomerData($RS,$w_cliente);
    ShowHTML("      <tr><td align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"2\"><b><font color=\"#BC3131\">ATEN��O</font>: o tamanho m�ximo aceito para o arquivo � de ".$cDbl[$RS["upload_maximo"]]/1024." KBytes</b>.</font></td>");
    ShowHTML("<INPUT type=\"hidden\" name=\"w_upload_maximo\" value=\"".$RS["upload_maximo"]."\">");
  } 


  ShowHTML("      <tr><td><font size=\"1\"><b><u>D</u>ata/hora extra��o:</b><br><input ".$w_Disabled." accesskey=\"D\" type=\"text\" name=\"w_data_arquivo\" class=\"sti\" SIZE=\"17\" MAXLENGTH=\"17\" VALUE=\"".$w_data_arquivo."\"  onKeyDown=\"FormataDataHora(this, event);\" title=\"OBRIGAT�RIO. Informe a data e hora da extra��o do aquivo. Digite apenas n�meros. O sistema colocar� os separadores automaticamente.\"></td>");
  ShowHTML("      <tr><td><font size=\"1\"><b>A<u>r</u>quivo:</b><br><input ".$w_Disabled." accesskey=\"R\" type=\"file\" name=\"w_arquivo_recebido\" class=\"STI\" SIZE=\"80\" MAXLENGTH=\"100\" VALUE=\"\" title=\"OBRIGAT�RIO. Clique no bot�o ao lado para localizar o arquivo (sua extens�o deve ser .TXT). Ele ser� transferido automaticamente para o servidor.\">");
  ShowHTML("      <tr><td align=\"LEFT\"><font size=\"1\"><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY=\"A\" class=\"sti\" type=\"PASSWORD\" name=\"w_assinatura\" size=\"30\" maxlength=\"30\" value=\"\"></td></tr>");
  ShowHTML("      <tr><td align=\"center\"><hr>");
  if ($O=="E")
  {

    ShowHTML("          <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Excluir\">");
  }
    else
  {

    ShowHTML("          <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Incluir\">");
  } 

  ShowHTML("          <input class=\"STB\" type=\"button\" onClick=\"history.back(1);\" name=\"Botao\" value=\"Cancelar\">");
  ShowHTML("          </td>");
  ShowHTML("      </tr>");
  ShowHTML("    </table>");
  ShowHTML("    </TD>");
  ShowHTML("</tr>");
  ShowHTML("</FORM>");
}
  else
{

  ScriptOpen("JavaScript");
  ShowHTML(" alert('Op��o n�o dispon�vel');");
  ScriptClose();
} 

ShowHTML("</table>");
ShowHTML("</center>");
Rodape();

$w_data=null;

$w_sq_pessoa=null;

$w_data_arquivo=null;

$w_arquivo_recebido=null;

$w_arquivo_registro=null;

$w_registros=null;

$w_importados=null;

$w_rejeitados=null;

$w_situacao=null;

return $function_ret;
} 
// =========================================================================

// Fim da rotina

// -------------------------------------------------------------------------


// =========================================================================

// Exibe orienta��es sobre o processo de importa��o

// -------------------------------------------------------------------------

function Help()
{
  extract($GLOBALS);


Cabecalho();
ShowHTML("<BASE HREF=\"".$conRootSIW."\">");
BodyOpen("onLoad='document.focus()';");
ShowHTML("<B><FONT COLOR=\"#000000\">".$w_TP."</FONT></B>");
ShowHTML("<HR>");
ShowHTML("<div align=center><center>");
ShowHTML("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"90%\">");
ShowHTML("<tr valign=\"top\">");
ShowHTML("  <td><font size=2>");
ShowHTML("    <p align=\"justify\">Esta tela tem o objetivo de atualizar os dados or�ament�rios e financeiros");
ShowHTML("        da tabela de programas e a��es do PPA, atrav�s da importa��o de arquivo extra�do do SIAFI.");
ShowHTML("    <p align=\"justify\">A atualiza��o est� restrita aos dados sobre dota��o autorizada, total empenhado e total liquidado.");
ShowHTML("    <p align=\"justify\">Para ser executada corretamente, a importa��o deve cumprir os passos abaixo.");
ShowHTML("    <ol>");
ShowHTML("    <p align=\"justify\"><b>FASE 1 - Prepara��o do arquivo a ser importado:</b><br></p>");
ShowHTML("      <li>Use o m�dulo extrator do SIAFI para obter uma planilha Excel (extens�o XLS), <u>exatamente igual</u> � exibida neste");
ShowHTML("          ".LinkArquivo("HL",$w_cliente,"SIAFI_exemplo.xls","ExemploSIAFI","Exibe os dados do arquivo importado.","Exemplo",null).";");
ShowHTML("      <li>Abra a planilha gerada no passo anterior com o Excel e use a op��o \"Arquivo -> Salvar como\". Escolha o nome que desejar");
ShowHTML("          para o arquivo e, na lista \"Salvar como tipo\", escolha a op��o \"<b>CSV (Separado por v�rgulas) (*.csv)</b>\"; ");
ShowHTML("      <li> Feche o ");
ShowHTML("          Excel e renomeie a extens�o do arquivo, de CSV para TXT. Ap�s cumprir este passo, voc� dever� ter um arquivo com extens�o TXT, como o deste ");
ShowHTML("          ".LinkArquivo("HL",$w_cliente,"SIAFI_exemplo.TXT","ExemploSIAFI","Exibe os dados do arquivo importado.","Exemplo",null).";");
ShowHTML("    <p align=\"justify\"><b>FASE 2 - Importa��o do arquivo e atualiza��o dos dados:</b><br></p>");
ShowHTML("      <li>Na tela anterior, clique sobre a opera��o \"Incluir\";");
ShowHTML("      <li>Quando a tela de inclus�o for apresentada, preencha o formul�rio seguindo as instru��es dispon�veis em cada campo ");
ShowHTML("          (passe o mouse sobre o campo desejado para o sistema exibir a instru��o de preenchimento);");
ShowHTML("      <li>Aguarde o t�rmino da importa��o e atualiza��o dos dados. O sistema ir�, numa �nica execu��o, transferir o arquivo ");
ShowHTML("          selecionado para o servidor, ler cada uma das suas linhas, verificar se os dados est�o corretos e, em caso positivo, ");
ShowHTML("          atualizar os campos. Este processamento pode demorar alguns minutos. N�o clique em nenhum bot�o at� o sistema voltar para ");
ShowHTML("          para a listagem das importa��es j� executadas;");
ShowHTML("    <p align=\"justify\"><b>FASE 3 - Verifica��o do arquivo de registro:</b><br></p>");
ShowHTML("      <li>Verifique se ocorreu erro na importa��o de alguma linha do arquivo de origem. Na lista de importa��es, existem tr�s colunas: ");
ShowHTML("          \"Registros\" indica o n�mero total de linhas do arquivo, \"Importados\" indica o n�mero de linhas que atendeu �s condi��es de importa��o ");
ShowHTML("          e que geraram atualiza��o nos dados existentes, \"Rejeitados\" indica o n�mero de linhas que foram descartadas pela valida��o; ");
ShowHTML("      <li>Verifique cada linha descartada pela rotina de importa��o. Clique sobre a opera��o \"Registro\" na coluna \"Opera��es\" e verifique ");
ShowHTML("          os erros detectados em cada uma das linhas descartadas. O conte�do do arquivo � similar ao deste ");
ShowHTML("          ".LinkArquivo("HL",$w_cliente,"SIAFI_registro.TXT","ExemploSIAFI","Exibe os dados do arquivo importado.","Exemplo",null).";");
ShowHTML("      <li>Se desejar, gere um novo arquivo somente com as linhas descartadas, corrija os erros e fa�a uma nova importa��o.");
ShowHTML("    </ol>");
ShowHTML("    <p align=\"justify\"><b>Observa��es:</b><br></p>");
ShowHTML("    <ul>");
ShowHTML("      <li>Para restringir a importa��o �s linhas que realmente s�o �teis, abra o arquivo obtido no passo (3) com o Bloco de Notas (Notepad) ");
ShowHTML("          e remova as linhas que n�o disserem respeito aos programas e a��es do PPA, n�o esquecendo de salv�-lo;");
ShowHTML("      <li>Uma vez conclu�da uma importa��o, n�o h� necessidade de voc� manter em seu computador/disquete o arquivo utilizado. O sistema ");
ShowHTML("          grava no servidor uma c�pia do arquivo usado pela importa��o e uma c�pia do arquivo de registro;");
ShowHTML("      <li>Toda importa��o registra os dados de quem a executou, de quando ela foi executada, bem como os arquivos de origem e de registro; ");
ShowHTML("      <li>N�o h� como cancelar uma importa��o, nem de reverter os valores existentes antes da sua execu��o. Assim, certifique-se de que o ");
ShowHTML("          arquivo de origem est� correto e que a importa��o deve realmente ser executada.");
ShowHTML("    </ul>");
ShowHTML("    <p align=\"justify\"><b>Verifica��es dos dados:</b><br></p>");
ShowHTML("    <ul>");
ShowHTML("      <p align=\"justify\">Uma linha do arquivo origem s� gera atualiza��o da tabela de programas e a��es do PPA se atender aos seguintes crit�rios:</p>");
ShowHTML("      <li>O c�digo do programa deve estar na segunda posi��o da linha e deve conter 4 posi��es n�mericas;");
ShowHTML("      <li>A c�digo da a��o deve estar na quarta posi��o da linha e deve conter entre 4 e 5 posi��es, sendo que as quatro primeiras s�o n�meros;");
ShowHTML("           e a quinta posi��o deve ser uma letra mai�scula ");
ShowHTML("      <li>A dota��o autorizada deve estar na sexta posi��o da linha e deve estar na nota��o brasileira de valor (separador de milhar = ponto e de decimal = v�rgula);");
ShowHTML("      <li>O total empenhado deve estar na s�tima posi��o da linha e deve estar na nota��o brasileira de valor (separador de milhar = ponto e de decimal = v�rgula);");
ShowHTML("      <li>O total liquidado deve estar na s�tima posi��o da linha e deve estar na nota��o brasileira de valor (separador de milhar = ponto e de decimal = v�rgula);");
ShowHTML("      <li>O sistema s� atualizar� a tabela se encontrar um, e apenas um registro com o mesmo c�digo de a��o e programa;");
ShowHTML("      <li>Cada posi��o da linha � separada pelo caracter ponto-e-v�rgula;");
ShowHTML("      <li>Os valores de cada posi��o <u>n�o</u> devem estar entre aspas simples nem duplas. Ex: <b>;1606;...</b> � v�lido, mas <b>;\"1606\";...</b> e <b>;'1606';...</b> s�o inv�lidos; ");
ShowHTML("      <p align=\"justify\">Qualquer situa��o diferente das relacionadas acima causar� a rejei��o da linha.</p>");
ShowHTML("    <ul>");
ShowHTML("  </td>");
ShowHTML("</tr>");
ShowHTML("</table>");
ShowHTML("</center>");
Rodape();
return $function_ret;
} 
// =========================================================================

// Fim da rotina

// -------------------------------------------------------------------------


// =========================================================================

// Procedimento que executa as opera��es de BD

// -------------------------------------------------------------------------

function Grava()
{
  extract($GLOBALS);


$ForReading=1$ForWriting=2$ForAppend=8;
$TristateUsedefault=-2; //Abre o arquivo usando o sistema default
;
$TristateTrue=-1; //Abre o arquivo como Unicode
;
$TristateFalse=0; //Abre o arquivo como ASCII
;



Cabecalho();
ShowHTML("</HEAD>");
BodyOpen("onLoad=document.focus();");

AbreSessao();
switch ($SG)
{
  case "ORIMPSIAFI":
// Verifica se a Assinatura Eletr�nica � v�lida

    if ((VerificaAssinaturaEletronica($Username_session,$w_assinatura) && $w_assinatura>"") || 
       $w_assinatura=="")
    {

$FS=$CreateObject["Scripting.FileSystemObject"]
// Verifica se o tamanho das fotos est� compat�vel com  o limite de 100KB

      if ($ul->State==0)
      {

        $w_maximo=$ul->Texts.$Item["w_upload_maximo"];
foreach ($ul->Files as $Field)
        {
          $Items;
          if ($Field->Length>0)
          {

// Verifica se o tamanho das fotos est� compat�vel com  o limite de 100KB. 

            if ($cDbl[$Field->Length]>$cDbl[$w_maximo])
            {

              ScriptOpen("JavaScript");
              ShowHTML("  alert('Aten��o: o tamanho m�ximo do arquivo n�o pode exceder ".$cDbl[$w_maximo]/1024." KBytes!');");
              ShowHTML("  history.back(1);");
              ScriptClose();
              exit();

              return $function_ret;

            } 


// Se j� h� um nome para o arquivo, mant�m 

            $w_caminho_recebido=str_replace(".tmp",substr($Field->FileName,(strpos($Field->FileName,".") ? strpos($Field->FileName,".")+1 : 0)-1,30),$FS->GetTempName());
            $w_tamanho_recebido=$Field->Length;
            $w_tipo_recebido=$Field->ContentType;
            $w_nome_recebido=$Field->FileName;
$Field->SaveAs($conFilePhysical.$w_cliente."\".$w_caminho_recebido);

            $w_caminho_registro=str_replace(substr($w_caminho_recebido,(strpos($w_caminho_recebido,".") ? strpos($w_caminho_recebido,".")+1 : 0)-1,30),"",$w_caminho_recebido)."r".substr($w_caminho_recebido,(strpos($w_caminho_recebido,".") ? strpos($w_caminho_recebido,".")+1 : 0)-1,30);
          } 

        } // Gera o arquivo registro da importa��o

$FS=$CreateObject["Scripting.FileSystemObject"]
$F1=$FS->CreateTextFile        $w_caminho.$w_caminho_registro);

//Abre o arquivo recebido para gerar o arquivo registro

$F2=$FS->OpenTextFile        $w_caminho.$w_caminho_recebido);

// Varre o arquivo recebido, linha a linha

        $w_registros=0;
        $w_importados=0;
        $w_rejeitados=0;
        $w_cont=0;
        while(!$F2->AtEndOfStream)
        {

          $w_linha=$F2->ReadLine;
          $w_cont=$w_cont+1;
$F1->WriteLine"[Linha ".$w_cont."] ".$w_linha;
          $w_programa=Nvl(trim(Piece($w_linha,"",";",2)),$w_programa);
          $w_acao=trim(Piece($w_linha,"",";",4));
          $w_dotacao=trim(Piece($w_linha,"",";",6));
          $w_empenhado=trim(Piece($w_linha,"",";",7));
          $w_liquidado=trim(Piece($w_linha,"",";",8));

          $w_erro=0;

// Valida o campo Programa

          $w_result=fValidate(1,$w_programa,"Programa","",1,4,4,"","0123456789");
          if ($w_result>"")
          {
$F1->WriteLine"=== Erro campo Programa: ".$w_result;           } 
          $w_erro=1;
        }
;

// Valida o campo A��o

          $w_result=fValidate(1,$w_acao,"A��o","",1,4,5,"","0123456789ABCDEFGHIJKLMNOPQRSTUWVXYZ");
          if ($w_result>"")
          {
$F1->WriteLine"=== Erro campo A��o: ".$w_result;           } 
          $w_erro=1;
        }
;

// Valida o campo Dota��o

          $w_result=fValidate(1,$w_dotacao,"Dota��o Autorizada","VALOR",1,3,18,"","0123456789,.");
          if ($w_result>"")
          {
$F1->WriteLine"=== Erro campo Dota��o Autorizada: ".$w_result;           } 
          $w_erro=1;
        }
;

// Valida o campo Empenhado

          $w_result=fValidate(1,$w_empenhado,"Total Empenhado","VALOR",1,3,18,"","0123456789,.");
          if ($w_result>"")
          {
$F1->WriteLine"=== Erro campo Total Empenhado: ".$w_result;           } 
          $w_erro=1;
        }
;

// Valida o campo Liquidado

          $w_result=fValidate(1,$w_liquidado,"Total Liquidado","VALOR",1,3,18,"","0123456789,.");
          if ($w_result>"")
          {
$F1->WriteLine"=== Erro campo Total Liquidado: ".$w_result;           } 
          $w_erro=1;
        }
;

          if ($w_erro==0)
          {

// Verifica se o programa/a��o existe para o cliente

            DB_GetAcaoPPA($RS,null,$w_cliente,null,null,null,null,null,null,$w_programa,$w_acao);
            if ($RS->EOF)
            {

$F1->WriteLine"=== Programa/a��o n�o encontrado";
              $w_erro=1;
            }
              else
            {

// Se existir, atualiza os dados financeiros

              DML_PutAcaoPPA("U",
              null,$w_cliente,null,null,null,null,null,null,null,null,$w_dotacao,null,
              $w_empenhado,$w_liquidado);
            } 

          } 


          $w_registros=$w_registros+1;
          if ($w_erro==0)
          {

            $w_importados=$w_importados+1;
          }
            else
          {

            $w_rejeitados=$w_rejeitados+1;
          } 

        } 
$F2->Close;
$F1->Close;

// Configura o valor dos campos necess�rios para grava��o

        if ($w_rejeitados==0)
        {
          $w_situacao=0;
        }
          else
        {
          $w_situacao=1;
        }
;
      } 

      $w_arquivo_registro="Arquivo registro";
$F1=$FS->GetFile($w_caminho.$w_caminho_registro)
      $w_tamanho_registro=$F1->size;
      $w_tipo_registro=$w_tipo_recebido;

// Grava o resultado da importa��o no banco de dados

      DML_PutOrImport($O,
      $ul->Texts.$Item["w_chave"],$w_cliente,$w_usuario,$ul->Texts.$Item["w_data_arquivo"],
      $w_nome_recebido,
      $w_caminho_recebido,$w_tamanho_recebido,$w_tipo_recebido,
      $w_arquivo_registro,$w_caminho_registro,$w_tamanho_registro,$w_tipo_registro,
      $w_registros,$w_importados);

      ScriptOpen("JavaScript");
      ShowHTML("  location.href='".$R."&w_chave=".$ul->Texts.$Item["w_Chave"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("UL")."';");
      ScriptClose();
    }
      else
    {

      ScriptOpen("JavaScript");
      ShowHTML("  alert('ATEN��O: ocorreu um erro na transfer�ncia do arquivo. Tente novamente!');");
      ScriptClose();
    } 

  }
    else
  {

    ScriptOpen("JavaScript");
    ShowHTML("  alert('Assinatura Eletr�nica inv�lida!');");
    ShowHTML("  history.back(1);");
    ScriptClose();
  } 

  break;
default:

  ScriptOpen("JavaScript");
  ShowHTML("  alert('Bloco de dados n�o encontrado: ".$SG."');");
  ShowHTML("  history.back(1);");
  ScriptClose();
  break;
} 

$w_result=null;

$w_erro=null;

$w_caminho_recebido=null;

$w_tamanho_recebido=null;

$w_nome_recebido=null;

$w_tipo_recebido=null;

$w_arquivo_registro=null;

$w_caminho_registro=null;

$w_tamanho_registro=null;

$w_tipo_registro=null;

$w_registros=null;

$w_importados=null;

$w_rejeitados=null;

$w_situacao=null;

$w_chave_nova=null;

$F1=null;

$F2=null;

$w_linha=null;

$FS=null;

$w_Mensagem=null;

$w_Null=null;

return $function_ret;
} 
// -------------------------------------------------------------------------

// Fim do procedimento que executa as opera��es de BD

// =========================================================================


// =========================================================================

// Rotina principal

// -------------------------------------------------------------------------

function Main()
{
  extract($GLOBALS);


switch ($Par)
{
case "INICIAL":
  Inicial();
  break;
case "HELP":
  Help();
  break;
case "GRAVA":
  Grava();
  break;
default:

  Cabecalho();
  ShowHTML("<BASE HREF=\"".$conRootSIW."\">");
  BodyOpen("onLoad=document.focus();");
  ShowHTML("<B><FONT COLOR=\"#000000\">".$w_TP."</FONT></B>");
  ShowHTML("<HR>");
  ShowHTML("<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=\"images/icone/underc.gif\" align=\"center\"> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>");
  Rodape();
  break;
} 
return $function_ret;
} 
// =========================================================================

// Fim da rotina principal

// -------------------------------------------------------------------------

?>
