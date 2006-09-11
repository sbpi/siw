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
?>
<!-- #INCLUDE FILE="../Constants.inc" -->
<!-- #INCLUDE FILE="../DB_Geral.php" -->
<!-- #INCLUDE FILE="../DB_Cliente.php" -->
<!-- #INCLUDE FILE="../DB_Seguranca.php" -->
<!-- #INCLUDE FILE="../jScript.php" -->
<!-- #INCLUDE FILE="../Funcoes.php" -->
<!-- #INCLUDE FILE="DB_CV.php" -->
<!-- #INCLUDE FILE="DML_CV.php" -->
<!-- #INCLUDE FILE="Funcoes.php" -->
<!-- #INCLUDE FILE="../cp_upload/_upload.php" -->
<? 
// =========================================================================

// Rotina de visualiza��o do curr�culo

// -------------------------------------------------------------------------

function VisualCurriculo($p_cliente,$p_usuario,$O)
{
  extract($GLOBALS);




  if ($O=="L")
  {
// Se for listagem dos dados


// Identifica��o pessoal

    DB_GetCV($RS,$p_cliente,$p_usuario,"CVIDENT","DADOS");

    $w_nome=$RS["nome"];
    $HTML="<div align=center><center>";
    $HTML="\r\n".$HTML."<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
    $HTML="\r\n".$HTML."<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">";
    $HTML="\r\n".$HTML."    <table width=\"99%\" border=\"0\">";
    $HTML="\r\n".$HTML."      <tr><td align=\"center\" colspan=\"3\"><font size=5><b>".$RS["nome"]."</b></font></td></tr>";
    $HTML="\r\n".$HTML."      <tr><td valign=\"top\" colspan=\"3\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Identifica��o</td>";
    $HTML="\r\n".$HTML."      <tr valign=\"top\">";
    $HTML="\r\n".$HTML."          <td><font size=\"1\">Nome:<br><b>".$RS["nome"]." </b></td>";
    $HTML="\r\n".$HTML."          <td><font size=\"1\">Nome resumido:<br><b>".$RS["nome_resumido"]." </b></td>";
    $HTML="\r\n".$HTML."          <td><font size=\"1\">Data nascimento:<br><b>".FormataDataEdicao($RS["nascimento"])." </b></td>";
    $HTML="\r\n".$HTML."      <tr valign=\"top\">";
    $HTML="\r\n".$HTML."          <td><font size=\"1\">Sexo:<br><b>".$RS["nm_sexo"]." </b></td>";
    $HTML="\r\n".$HTML."          <td><font size=\"1\">Estado civil:<br><b>".$RS["nm_estado_civil"]." </b></td>";
    if (nvl($RS["sq_siw_arquivo"],"nulo")!="nulo" && $P2==0)
    {

      $HTML="\r\n".$HTML."          <td rowspan=3><font size=\"1\">".LinkArquivo("HL",$w_cliente,$RS["sq_siw_arquivo"],"_blank",null,"<img title=\"clique para ver em tamanho original.\" border=1 width=100 length=80 src=\"".LinkArquivo(null,$w_cliente,$RS["sq_siw_arquivo"],null,null,null,"EMBED")."\">",null)."</td>";

    }
      else
    {

      $HTML="\r\n".$HTML."          <td rowspan=3><font size=\"1\"></td>";
    } 

    $HTML="\r\n".$HTML."      <tr valign=\"top\">";
    $HTML="\r\n".$HTML."          <td><font size=\"1\">Forma��o acad�mica:<br><b>".$RS["nm_formacao"]." </b></td>";
    $HTML="\r\n".$HTML."          <td><font size=\"1\">&nbsp;</td>";
    $HTML="\r\n".$HTML."      <tr><td colspan=2><font size=\"1\">&nbsp;</td>";

    $HTML="\r\n".$HTML."      <tr><td valign=\"top\" colspan=\"3\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Local de nascimento</td>";
    $HTML="\r\n".$HTML."      <tr valign=\"top\">";
    $HTML="\r\n".$HTML."          <td><font size=\"1\">Pa�s:<br><b>".$RS["nm_pais_nascimento"]." </b></td>";
    $HTML="\r\n".$HTML."          <td><font size=\"1\">Estado:<br><b>".$RS["nm_uf_nascimento"]." </b></td>";
    $HTML="\r\n".$HTML."          <td><font size=\"1\">Cidade:<br><b>".$RS["nm_cidade_nascimento"]." </b></td>";

    $HTML="\r\n".$HTML."      <tr><td valign=\"top\" colspan=\"3\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Documenta��o</td>";
    $HTML="\r\n".$HTML."      <tr valign=\"top\">";
    $HTML="\r\n".$HTML."          <td><font size=\"1\">Identidade:<br><b>".$RS["rg_numero"]." </b></td>";
    $HTML="\r\n".$HTML."          <td><font size=\"1\">Emissor:<br><b>".$RS["rg_emissor"]." </b></td>";
    $HTML="\r\n".$HTML."          <td><font size=\"1\">Data de emiss�o:<br><b>".FormataDataEdicao($RS["rg_emissao"])." </b></td>";
    $HTML="\r\n".$HTML."      <tr valign=\"top\">";
    $HTML="\r\n".$HTML."          <td><font size=\"1\">CPF:<br><b>".$RS["cpf"]."</b></td>";
    $HTML="\r\n".$HTML."          <td><font size=\"1\">Passaporte:<br><b>".Nvl($RS["passaporte_numero"],"---")." </b></td>";
    $HTML="\r\n".$HTML."          <td valign=\"top\"><font size=\"1\">Pa�s emissor:<br><b>".Nvl($RS["nm_pais_passaporte"],"---")." </b></td>";
    $HTML="\r\n".$HTML."          </table>";
    DesconectaBD();

// Telefones

    DB_GetFoneList($RS,$p_usuario,null,null,null);
$RS->Sort="tipo_telefone, numero";
    $HTML="\r\n".$HTML."      <tr><td valign=\"top\" colspan=\"3\"><font size=\"1\">&nbsp;</td>";
    $HTML="\r\n".$HTML."      <tr><td valign=\"top\" colspan=\"3\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Telefones</td>";
    $HTML="\r\n".$HTML."<tr><td align=\"center\" colspan=3>";
    $HTML="\r\n".$HTML."    <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
    $HTML="\r\n".$HTML."        <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
    $HTML="\r\n".$HTML."          <td><font size=\"1\"><b>Tipo</font></td>";
    $HTML="\r\n".$HTML."          <td><font size=\"1\"><b>DDD</font></td>";
    $HTML="\r\n".$HTML."          <td><font size=\"1\"><b>N�mero</font></td>";
    $HTML="\r\n".$HTML."          <td><font size=\"1\"><b>Padr�o</font></td>";
    $HTML="\r\n".$HTML."        </tr>";
    if ($RS->EOF)
    {
// Se n�o foram selecionados registros, exibe mensagem

      $HTML="\r\n".$HTML."      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=6 align=\"center\"><font size=\"1\"><b>N�o foram encontrados registros.</b></td></tr>";
    }
      else
    {

// Lista os registros selecionados para listagem

      $w_cor=$conTrBgColor;
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
      $HTML="\r\n".$HTML."      <tr bgcolor=\"".$w_cor."\" valign=\"top\">";
      $HTML="\r\n".$HTML."        <td><font size=\"1\">".$RS["tipo_telefone"]."</td>";
      $HTML="\r\n".$HTML."        <td align=\"center\"><font size=\"1\">".$RS["ddd"]."</td>";
      $HTML="\r\n".$HTML."        <td><font size=\"1\">".$RS["numero"]."</td>";
      $HTML="\r\n".$HTML."        <td align=\"center\"><font size=\"1\">".$RS["padrao"]."</td>";
      $HTML="\r\n".$HTML."      </tr>";
$RS->MoveNext;
    } 
  } 

  $HTML="\r\n".$HTML."      </center>";
  $HTML="\r\n".$HTML."    </table>";
  $HTML="\r\n".$HTML."  </td>";
  $HTML="\r\n".$HTML."</tr>";
  DesconectaBD();

//Endere�os de e-mail e internet

  DB_GetAddressList($RS,$p_usuario,null,"EMAILINTERNET",null);
$RS->Sort="tipo_endereco, endereco";
  $HTML="\r\n".$HTML."      <tr><td valign=\"top\" colspan=\"3\"><font size=\"1\">&nbsp;</td>";
  $HTML="\r\n".$HTML."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Endere�os de e-Mail e Internet</td>";
  $HTML="\r\n".$HTML."      <tr><td align=\"center\" colspan=\"2\">";
  $HTML="\r\n".$HTML."        <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
  $HTML="\r\n".$HTML."          <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
  $HTML="\r\n".$HTML."            <td><font size=\"1\"><b>Endere�o</font></td>";
  $HTML="\r\n".$HTML."            <td><font size=\"1\"><b>Padr�o</font></td>";
  $HTML="\r\n".$HTML."          </tr>";
  if ($RS->EOF)
  {

    $HTML="\r\n".$HTML."      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=2 align=\"center\"><font size=\"1\"><b>N�o foi informado nenhum endere�o de e-Mail ou Internet.</b></td></tr>";
  }
    else
  {

    $w_cor=$conTrBgColor;
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
    $HTML="\r\n".$HTML."      <tr bgcolor=\"".$w_cor."\" valign=\"top\">";
    if ($RS["email"]=="S")
    {

      $HTML="\r\n".$HTML."        <td><font size=\"1\"><a href=\"mailto:".$RS["logradouro"]."\">".$RS["logradouro"]."</a></td>";
    }
      else
    {

      $HTML="\r\n".$HTML."        <td><font size=\"1\"><a href=\"://".str_replace("://","",$RS["logradouro"])."\" target=\"_blank\">".$RS["logradouro"]."</a></td>";
    } 

    $HTML="\r\n".$HTML."        <td align=\"center\"><font size=\"1\">".$RS["padrao"]."</td>";
    $HTML="\r\n".$HTML."      </tr>";
$Rs->MoveNext;
  } 
} 

DesconectaBD();
$HTML="\r\n".$HTML."         </table></td></tr>";

//Endere�os f�sicos

$HTML="\r\n".$HTML."      <tr><td valign=\"top\" colspan=\"3\"><font size=\"1\">&nbsp;</td>";
DB_GetAddressList($RS,$p_usuario,null,"FISICO",null);
$RS->Sort="tipo_endereco, endereco";
$HTML="\r\n".$HTML."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Endere�os F�sicos</td>";
if ($RS->EOF)
{

  $HTML="\r\n".$HTML."      <tr bgcolor=\"".$conTrBgColor."\"><font size=\"1\"><b>N�o foi encontrado nenhum endere�o.</b></td></tr>";
}
  else
{

  $HTML="\r\n".$HTML."      <tr><td align=\"center\" colspan=\"2\"><TABLE WIDTH=\"100%\" BORDER=\"0\" CELLSPACING=\"0\" CELLPADDING=\"0\">";
  while(!$Rs->EOF)
  {

    $HTML="\r\n".$HTML."          <tr><td colspan=4><font size=\"1\"><b>".$RS["tipo_endereco"]."</font></td>";
    $HTML="\r\n".$HTML."          <tr><td width=\"5%\"><td colspan=3><font size=\"1\">Logradouro:<br><b>".$RS["logradouro"]."</font></td></tr>";
    $HTML="\r\n".$HTML."          <tr valign=\"top\"><td>";
    $HTML="\r\n".$HTML."              <td valign=\"top\"><font size=\"1\">Complemento:<br><b>".Nvl($RS["complemento"],"---")." </b></td>";
    $HTML="\r\n".$HTML."              <td valign=\"top\"><font size=\"1\">Bairro:<br><b>".$RS["bairro"]." </b></td>";
    $HTML="\r\n".$HTML."              <td valign=\"top\"><font size=\"1\">CEP:<br><b>".$RS["cep"]." </b></td>";
    $HTML="\r\n".$HTML."          <tr valign=\"top\"><td>";
    $HTML="\r\n".$HTML."              <td valign=\"top\" colspan=2><font size=\"1\">Cidade:<br><b>".$RS["cidade"]." </b></td>";
    $HTML="\r\n".$HTML."              <td valign=\"top\"><font size=\"1\">Pa�s:<br><b>".$RS["nm_pais"]." </b></td>";
    $HTML="\r\n".$HTML."          <tr><td><td colspan=3><font size=\"1\">Padr�o?<br><b>".$RS["padrao"]."</font></td></tr>";
$RS->MoveNext;
    $HTML="\r\n".$HTML."          <tr><td colspan=\"4\"><hr>";
  } 
  $HTML="\r\n".$HTML."          </table></td></tr>";
} 

DesconectaBD();

// Escolaridade

DB_GetCVAcadForm($RS,$p_usuario,null,"ACADEMICA");
$RS->Sort="ordem desc, inicio desc";
$HTML="\r\n".$HTML."      <tr><td valign=\"top\" colspan=\"3\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Escolaridade</td>";
$HTML="\r\n".$HTML."<tr><td align=\"center\" colspan=3>";
$HTML="\r\n".$HTML."    <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
$HTML="\r\n".$HTML."        <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
$HTML="\r\n".$HTML."          <td><font size=\"1\"><b>N�vel</font></td>";
$HTML="\r\n".$HTML."          <td><font size=\"1\"><b>�rea</font></td>";
$HTML="\r\n".$HTML."          <td><font size=\"1\"><b>Institui��o</font></td>";
$HTML="\r\n".$HTML."          <td><font size=\"1\"><b>Curso</font></td>";
$HTML="\r\n".$HTML."          <td><font size=\"1\"><b>In�cio</font></td>";
$HTML="\r\n".$HTML."          <td><font size=\"1\"><b>T�rmino</font></td>";
$HTML="\r\n".$HTML."        </tr>";
if ($RS->EOF)
{
// Se n�o foram selecionados registros, exibe mensagem

  $HTML="\r\n".$HTML."      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=6 align=\"center\"><font size=\"1\"><b>N�o foram encontrados registros.</b></td></tr>";
}
  else
{

// Lista os registros selecionados para listagem

  $w_cor=$conTrBgColor;
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
  $HTML="\r\n".$HTML."      <tr bgcolor=\"".$w_cor."\" valign=\"top\">";
  $HTML="\r\n".$HTML."        <td><font size=\"1\">".$RS["nm_formacao"]."</td>";
  $HTML="\r\n".$HTML."        <td><font size=\"1\">".Nvl($RS["nm_area"],"---")."</td>";
  $HTML="\r\n".$HTML."        <td><font size=\"1\">".Nvl($RS["instituicao"],"---")."</td>";
  $HTML="\r\n".$HTML."        <td><font size=\"1\">".Nvl($RS["nome"],"---")."</td>";
  $HTML="\r\n".$HTML."        <td align=\"center\"><font size=\"1\">".$RS["inicio"]."</td>";
  $HTML="\r\n".$HTML."        <td align=\"center\"><font size=\"1\">".Nvl($RS["fim"],"---")."</td>";
  $HTML="\r\n".$HTML."      </tr>";
$RS->MoveNext;
} 
} 

$HTML="\r\n".$HTML."      </center>";
$HTML="\r\n".$HTML."    </table>";
$HTML="\r\n".$HTML."  </td>";
$HTML="\r\n".$HTML."</tr>";
DesconectaBD();

// Extens�o acad�mica

DB_GetCVAcadForm($RS,$p_usuario,null,"CURSO");
$RS->Sort="ordem desc, carga_horaria desc";
$HTML="\r\n".$HTML."      <tr><td valign=\"top\" colspan=\"3\"><font size=\"1\">&nbsp;</td>";
$HTML="\r\n".$HTML."      <tr><td valign=\"top\" colspan=\"3\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Extens�o acad�mica</td>";
$HTML="\r\n".$HTML."<tr><td align=\"center\" colspan=3>";
$HTML="\r\n".$HTML."    <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
$HTML="\r\n".$HTML."        <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
$HTML="\r\n".$HTML."          <td><font size=\"1\"><b>N�vel</font></td>";
$HTML="\r\n".$HTML."          <td><font size=\"1\"><b>�rea</font></td>";
$HTML="\r\n".$HTML."          <td><font size=\"1\"><b>Institui��o</font></td>";
$HTML="\r\n".$HTML."          <td><font size=\"1\"><b>Curso</font></td>";
$HTML="\r\n".$HTML."          <td><font size=\"1\"><b>C.H.</font></td>";
$HTML="\r\n".$HTML."          <td><font size=\"1\"><b>Conclus�o</font></td>";
$HTML="\r\n".$HTML."        </tr>";
if ($RS->EOF)
{
// Se n�o foram selecionados registros, exibe mensagem

$HTML="\r\n".$HTML."      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=6 align=\"center\"><font size=\"1\"><b>N�o foram encontrados registros.</b></td></tr>";
}
  else
{

// Lista os registros selecionados para listagem

$w_cor=$conTrBgColor;
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
$HTML="\r\n".$HTML."      <tr bgcolor=\"".$w_cor."\" valign=\"top\">";
$HTML="\r\n".$HTML."        <td><font size=\"1\">".$RS["nm_formacao"]."</td>";
$HTML="\r\n".$HTML."        <td><font size=\"1\">".$RS["nm_area"]."</td>";
$HTML="\r\n".$HTML."        <td><font size=\"1\">".$RS["instituicao"]."</td>";
$HTML="\r\n".$HTML."        <td><font size=\"1\">".$RS["nome"]."</td>";
$HTML="\r\n".$HTML."        <td align=\"center\"><font size=\"1\">".$RS["carga_horaria"]."</td>";
$HTML="\r\n".$HTML."        <td align=\"center\"><font size=\"1\">".Nvl(FormataDataEdicao($RS["conclusao"]),"---")."</td>";
$HTML="\r\n".$HTML."      </tr>";
$RS->MoveNext;
} 
} 

$HTML="\r\n".$HTML."      </center>";
$HTML="\r\n".$HTML."    </table>";
$HTML="\r\n".$HTML."  </td>";
$HTML="\r\n".$HTML."</tr>";
DesconectaBD();

// Produ��o t�cnica

DB_GetCVAcadForm($RS,$p_usuario,null,"PRODUCAO");
$RS->Sort="ordem desc, data desc";
$HTML="\r\n".$HTML."      <tr><td valign=\"top\" colspan=\"3\"><font size=\"1\">&nbsp;</td>";
$HTML="\r\n".$HTML."      <tr><td valign=\"top\" colspan=\"3\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Produ��o t�cnica</td>";
$HTML="\r\n".$HTML."<tr><td align=\"center\" colspan=3>";
$HTML="\r\n".$HTML."    <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
$HTML="\r\n".$HTML."        <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
$HTML="\r\n".$HTML."          <td><font size=\"1\"><b>Tipo</font></td>";
$HTML="\r\n".$HTML."          <td><font size=\"1\"><b>�rea</font></td>";
$HTML="\r\n".$HTML."          <td><font size=\"1\"><b>Nome</font></td>";
$HTML="\r\n".$HTML."          <td><font size=\"1\"><b>Meio</font></td>";
$HTML="\r\n".$HTML."          <td><font size=\"1\"><b>Data</font></td>";
$HTML="\r\n".$HTML."        </tr>";
if ($RS->EOF)
{
// Se n�o foram selecionados registros, exibe mensagem

$HTML="\r\n".$HTML."      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=6 align=\"center\"><font size=\"1\"><b>N�o foram encontrados registros.</b></td></tr>";
}
  else
{

// Lista os registros selecionados para listagem

$w_cor=$conTrBgColor;
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
$HTML="\r\n".$HTML."      <tr bgcolor=\"".$w_cor."\" valign=\"top\">";
$HTML="\r\n".$HTML."        <td><font size=\"1\">".$RS["nm_formacao"]."</td>";
$HTML="\r\n".$HTML."        <td><font size=\"1\">".$RS["nm_area"]."</td>";
$HTML="\r\n".$HTML."        <td><font size=\"1\">".$RS["nome"]."</td>";
$HTML="\r\n".$HTML."        <td><font size=\"1\">".$RS["meio"]."</td>";
$HTML="\r\n".$HTML."        <td align=\"center\"><font size=\"1\">".$RS["data"]."</td>";
$HTML="\r\n".$HTML."      </tr>";
$RS->MoveNext;
} 
} 

$HTML="\r\n".$HTML."      </center>";
$HTML="\r\n".$HTML."    </table>";
$HTML="\r\n".$HTML."  </td>";
$HTML="\r\n".$HTML."</tr>";
DesconectaBD();

// Idiomas

DB_GetCVIdioma($RS,$p_usuario,null);
$RS->Sort="nome";
$HTML="\r\n".$HTML."      <tr><td valign=\"top\" colspan=\"3\"><font size=\"1\">&nbsp;</td>";
$HTML="\r\n".$HTML."      <tr><td valign=\"top\" colspan=\"3\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Idiomas</td>";
$HTML="\r\n".$HTML."<tr><td align=\"center\" colspan=3>";
$HTML="\r\n".$HTML."    <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
$HTML="\r\n".$HTML."        <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
$HTML="\r\n".$HTML."          <td><font size=\"1\"><b>Idioma</font></td>";
$HTML="\r\n".$HTML."          <td><font size=\"1\"><b>Leitura</font></td>";
$HTML="\r\n".$HTML."          <td><font size=\"1\"><b>Escrita</font></td>";
$HTML="\r\n".$HTML."          <td><font size=\"1\"><b>Conversa��o</font></td>";
$HTML="\r\n".$HTML."          <td><font size=\"1\"><b>Compreens�o</font></td>";
$HTML="\r\n".$HTML."        </tr>";
if ($RS->EOF)
{
// Se n�o foram selecionados registros, exibe mensagem

$HTML="\r\n".$HTML."      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=6 align=\"center\"><font size=\"1\"><b>N�o foram encontrados registros.</b></td></tr>";
}
  else
{

// Lista os registros selecionados para listagem

$w_cor=$conTrBgColor;
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
$HTML="\r\n".$HTML."      <tr bgcolor=\"".$w_cor."\" valign=\"top\">";
$HTML="\r\n".$HTML."        <td><font size=\"1\">".$RS["nome"]."</td>";
$HTML="\r\n".$HTML."        <td align=\"center\"><font size=\"1\">".$RS["nm_leitura"]."</td>";
$HTML="\r\n".$HTML."        <td align=\"center\"><font size=\"1\">".$RS["nm_escrita"]."</td>";
$HTML="\r\n".$HTML."        <td align=\"center\"><font size=\"1\">".$RS["nm_conversacao"]."</td>";
$HTML="\r\n".$HTML."        <td align=\"center\"><font size=\"1\">".$RS["nm_compreensao"]."</td>";
$HTML="\r\n".$HTML."      </tr>";
$RS->MoveNext;
} 
} 

$HTML="\r\n".$HTML."      </center>";
$HTML="\r\n".$HTML."    </table>";
$HTML="\r\n".$HTML."  </td>";
$HTML="\r\n".$HTML."</tr>";
DesconectaBD();

// Experiencia profissional

$HTML="\r\n".$HTML."      <tr><td valign=\"top\" colspan=\"3\"><font size=\"1\">&nbsp;</td>";
$HTML="\r\n".$HTML."      <tr><td valign=\"top\" colspan=\"3\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Experi�ncia Profissional</td>";
DB_GetCVAcadForm($RS,$p_usuario,null,"EXPERIENCIA");
$RS->Sort="entrada desc";
$HTML="\r\n".$HTML."      <tr><td align=\"center\" colspan=\"3\">";
$HTML="\r\n".$HTML."        <TABLE WIDTH=\"99%\" border=\"0\">";
if ($RS->EOF)
{

$HTML="\r\n".$HTML."      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=\"3\" align=\"center\"><font size=\"1\"><b>N�o foi informada nenhuma experi�ncia profissional.</b></td></tr>";
}
  else
{

while(!$RS->EOF)
{

$HTML="\r\n".$HTML."          <tr> ";
$HTML="\r\n".$HTML."          <tr><td valign=\"top\"><font size=\"1\">Empregador:<br><b>".$RS["empregador"]."</b></td>";
$HTML="\r\n".$HTML."              <td valign=\"top\"><font size=\"1\">�rea de conhecimento:<br><b>".$RS["nm_area"]."</b></td></tr>";
$HTML="\r\n".$HTML."          <tr> ";
$HTML="\r\n".$HTML."          <tr><td valign=\"top\"><font size=\"1\">Entrada: <br><b>".FormataDataEdicao($RS["entrada"])."</b></td>";
$HTML="\r\n".$HTML."              <td valign=\"top\"><font size=\"1\">Saida: <br><b>".Nvl(FormataDataEdicao($RS["saida"]),"---")."</b></td>";
$HTML="\r\n".$HTML."          <tr> ";
$HTML="\r\n".$HTML."          <tr><td valign=\"top\"><font size=\"1\">Motivo sa�da: <br><b>".Nvl($RS["motivo_saida"],"---")."</b></td></tr>";
$HTML="\r\n".$HTML."          <tr> ";
$HTML="\r\n".$HTML."          <tr><td valign=\"top\"><font size=\"1\">Pa�s: <br><b>".$RS["nm_pais"]."</b></td>";
$HTML="\r\n".$HTML."              <td valign=\"top\"><font size=\"1\">Estado: <br><b>".$RS["nm_estado"]."</b></td>";
$HTML="\r\n".$HTML."              <td valign=\"top\"><font size=\"1\">Cidade: <br><b>".$RS["nm_cidade"]."</b></td></tr>";
$HTML="\r\n".$HTML."          <tr> ";
$HTML="\r\n".$HTML."          <tr><td valign=\"top\" colspan=3><font size=\"1\">Principal atividade desempenhada: <br><b>".$RS["ds_tipo_posto"]."</b></td></tr>";
$HTML="\r\n".$HTML."          <tr> ";
$HTML="\r\n".$HTML."          <tr><td valign=\"top\" colspan=3><font size=\"1\">Atividades desempenhadas: <br><b>".$RS["atividades"]."</b></td></tr>";
// Cargos da experi�ncia profissional

DB_GetCVAcadForm($RS1,$RS["sq_cvpesexp"],null,"CARGO");
if (!$RS1->EOF)
{

$HTML="\r\n".$HTML."      <tr><td valign=\"top\"><font size=\"1\">Cargos:<br></td></tr>";
$HTML="\r\n".$HTML."      <tr><td align=\"center\" colspan=\"3\">";
$HTML="\r\n".$HTML."        <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
$HTML="\r\n".$HTML."          <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
$HTML="\r\n".$HTML."            <td><font size=\"1\"><b>�rea</font></td>";
$HTML="\r\n".$HTML."            <td><font size=\"1\"><b>Especialidades</font></td>";
$HTML="\r\n".$HTML."            <td><font size=\"1\"><b>In�cio</font></td>";
$HTML="\r\n".$HTML."            <td><font size=\"1\"><b>Fim</font></td>";
$HTML="\r\n".$HTML."          </tr>";
while(!$RS1->EOF)
{

$HTML="\r\n".$HTML."      <tr bgcolor=\"".$conTrBgColor."\" valign=\"top\">";
$HTML="\r\n".$HTML."        <td><font size=\"1\">".$RS1["nm_area"]."</td>";
$HTML="\r\n".$HTML."        <td><font size=\"1\">".$RS1["especialidades"]."</td>";
$HTML="\r\n".$HTML."        <td align=\"center\"><font size=\"1\">".FormataDataEdicao($RS1["inicio"])."</td>";
$HTML="\r\n".$HTML."        <td align=\"center\"><font size=\"1\">".Nvl(FormataDataEdicao($RS1["fim"]),"---")."</td>";
$HTML="\r\n".$HTML."      </tr>";
$RS1->MoveNext;
} 
$HTML="\r\n".$HTML."         </table></td></tr>";
} 

$RS1->Close;
$RS->MoveNext;
$HTML="\r\n".$HTML."          <tr><td colspan=\"3\"><hr>";
} 
} 

DesconectaBD();
$HTML="\r\n".$HTML."         </table></td></tr>";

$HTML="\r\n".$HTML."</table>";

}
  else
{

ScriptOpen("JavaScript");
$HTML="\r\n".$HTML." alert('Op��o n�o dispon�vel');";
$HTML="\r\n".$HTML." history.back(1);";
ScriptClose();
} 


ShowHTML("".$HTML);

$w_nome=null;

$HTML=null;

$w_erro=null;

$Rsquery=null;

$RS1=null;

$SQL1=null;

$RS2=null;

$SQL2=null;

$RS3=null;

$SQL3=null;

$SQLopcao=null;

$w_ImagemPadrao=null;

$w_Imagem=null;


return $function_ret;
} 
// =========================================================================

// Fim da rotina

// -------------------------------------------------------------------------


?>
