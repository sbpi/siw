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
<!-- #INCLUDE FILE="../DB_Gerencial.php" -->
<!-- #INCLUDE FILE="../DB_Cliente.php" -->
<!-- #INCLUDE FILE="../DB_Seguranca.php" -->
<!-- #INCLUDE FILE="../DB_Link.php" -->
<!-- #INCLUDE FILE="../DB_EO.php" -->
<!-- #INCLUDE FILE="../DML_Solic.php" -->
<!-- #INCLUDE FILE="../DML_Demanda.php" -->
<!-- #INCLUDE FILE="../jScript.php" -->
<!-- #INCLUDE FILE="../Funcoes.php" -->
<!-- #INCLUDE FILE="../cp_upload/_upload.php" -->
<!-- #INCLUDE FILE="Funcoes.php" -->
<!-- #INCLUDE FILE="DB_Tabelas.php" -->
<? 

// =========================================================================

// Rotina de visualiza��o dos dados da tarefa

// -------------------------------------------------------------------------

function VisualDemanda($w_chave,$O,$w_usuario)
{
  extract($GLOBALS);




  $w_html="";

// Recupera os dados da tarefa

  DB_GetSolicData($RS,$w_chave,"GDGERAL");

// O c�digo abaixo foi comentado em 23/11/2004, devido � mudan�a na regra definida pelo usu�rio,

// que agora permite vis�o geral para todos os usu�rios


// Recupera o tipo de vis�o do usu�rio

//If cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) or _

//   cDbl(Nvl(RS("executor"),0))    = cDbl(w_usuario) or _

//   cDbl(Nvl(RS("cadastrador"),0)) = cDbl(w_usuario) or _

//   cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _

//   cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) or _

//   cDbl(Nvl(RS("tit_exec"),0))    = cDbl(w_usuario) or _

//   cDbl(Nvl(RS("subst_exec"),0))  = cDbl(w_usuario) Then

//   ' Se for solicitante, executor ou cadastrador, tem vis�o completa//   w_tipo_visao = 0

//Else

//   DB_GetSolicInter Rsquery, w_chave, w_usuario, "REGISTRO"

//   If Not RSquery.EOF Then

//      ' Se for interessado, verifica a vis�o cadastrada para ele.//      w_tipo_visao = cDbl(RSquery("tipo_visao"))

//   Else

//      DB_GetSolicAreas Rsquery, w_chave, Session("sq_lotacao"), "REGISTRO"

//      If Not RSquery.EOF Then

//         ' Se for de uma das unidades envolvidas, tem vis�o parcial//         w_tipo_visao = 1

//      Else

//         ' Caso contr�rio, tem vis�o resumida//         w_tipo_visao = 2

//      End If

//   End If

//End If


  $w_tipo_visao=0;

// Se for listagem ou envio, exibe os dados de identifica��o da tarefa

  if ($O=="L" || $O=="V")
  {
// Se for listagem dos dados

    $w_html=$w_html."\r\n"."<div align=center><center>";
    $w_html=$w_html."\r\n"."<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
    $w_html=$w_html."\r\n"."<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">";

    $w_html=$w_html."\r\n"."    <table width=\"99%\" border=\"0\">";
    if (!!isset($RS["nm_projeto"]))
    {

// Recupera os dados da a��o

      DB_GetSolicData($RS1,$RS["sq_solic_pai"],"PJGERAL");

// Se a a��o no PPA for informada, exibe.

      if (!!isset($RS1["sq_acao_ppa"]))
      {

        $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Programa PPA:<b>".$RS1["nm_ppa_pai"]." (".$RS1["cd_ppa_pai"].")"." </b></td>";
        $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">A��o PPA:<b>".$RS1["nm_ppa"]." (".$RS1["cd_ppa"].")"." </b></td>";
      } 

// Se a iniciativa priorit�ria for informada, exibe.

      if (!!isset($RS1["sq_orprioridade"]))
      {

        $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Iniciativa priorit�ria:<b>".$RS1["nm_pri"];
        if (!!isset($RS1["cd_pri"]))
        {
          $w_html=$w_html."\r\n"." (".$RS1["cd_pri"].")";
        }
;
        } 
        $w_html=$w_html."\r\n"."          </b></td>";
        $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">A��o: <b>".$RS["nm_projeto"]."</b></td>";
      } 


    } 

//If Not IsNull(RS("nm_etapa")) Then

//   w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">: <b>" & MontaOrdemEtapa(RS("sq_projeto_etapa")) & ". " & RS("nm_etapa") & " </b></td>"

//End If


    $w_html=$w_html."\r\n"."      <tr><td><font size=1>Detalhamento: <b>".CRLF2BR($RS["assunto"])." (".$w_chave.") </b></font></td></tr>";

// Identifica��o da tarefa

    $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Identifica��o</td>";
// Se a classifica��o foi informada, exibe.

    if (!!isset($RS["sq_cc"]))
    {

      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Classifica��o:<br><b>".$RS["cc_nome"]." </b></td>";
    } 

    $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>";
    $w_html=$w_html."\r\n"."          <tr valign=\"top\">";
    $w_html=$w_html."\r\n"."          <td><font size=\"1\">Respons�vel:<br><b>".ExibePessoa("../",$w_cliente,$RS["solicitante"],$TP,$RS["nm_sol"])."</A></b></td>";
    $w_html=$w_html."\r\n"."          <td><font size=\"1\">Unidade respons�vel:<br><b>".$RS["nm_unidade_resp"]." </b></td>";
    if ($w_tipo_visao==0)
    {
// Se for vis�o completa

      $w_html=$w_html."\r\n"."          <td valign=\"top\"><font size=\"1\">Recurso programado:<br><b>".$FormatNumber[$RS["valor"]][2]." </b></td>";
    } 

    $w_html=$w_html."\r\n"."          <tr valign=\"top\">";
    $w_html=$w_html."\r\n"."          <td><font size=\"1\">In�cio previsto:<br><b>".FormataDataEdicao($RS["inicio"])." </b></td>";
    $w_html=$w_html."\r\n"."          <td><font size=\"1\">Fim previsto:<br><b>".FormataDataEdicao($RS["fim"])." </b></td>";
    $w_html=$w_html."\r\n"."          <td><font size=\"1\">Prioridade:<br><b>".RetornaPrioridade($RS["prioridade"])." </b></td>";
    $w_html=$w_html."\r\n"."          <tr>";
    $w_html=$w_html."\r\n"."          <td colspan=2><font size=\"1\">Respons�vel:<br><b>".Nvl($RS["palavra_chave"],"---")." </b></td>";
    $w_html=$w_html."\r\n"."          <td><font size=\"1\">Ordem:<br><b>".$RS["ordem"]." </b></td>";
    $w_html=$w_html."\r\n"."          <tr valign=\"top\">";
    $w_html=$w_html."\r\n"."          <td colspan=3><font size=\"1\">Parcerias externas:<br><b>".Nvl($RS["proponente"],"---")." </b></td>";
//w_html = w_html & VbCrLf & "          <tr valign=""top"">"

//w_html = w_html & VbCrLf & "          <td colspan=3><font size=""1"">Abrang�ncia da a��o:(Quando Bras�lia-DF, impacto nacional. Quando a capital de um estado, impacto estadual.):<br><b>" & RS("nm_cidade") & " (" & RS("co_uf") & ")</b></td>"

    $w_html=$w_html."\r\n"."          </table>";

    if ($w_tipo_visao==0 || $w_tipo_visao==1)
    {

// Informa��es adicionais

      if (Nvl($RS["descricao"],"")>"" || Nvl($RS["justificativa"],"")>"")
      {

        $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Informa��es adicionais</td>";
        if (Nvl($RS["descricao"],"")>"")
        {
          $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Resultados espearados:<br><b>".CRLF2BR($RS["descricao"])." </b></td>";
        }
;
        } 
        if ($w_tipo_visao==0 && Nvl($RS["justificativa"],"")>"")
        {
// Se for vis�o completa

          $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Observa��es:<br><b>".CRLF2BR($RS["justificativa"])." </b></td>";
        } 

      } 

    } 


// Dados da conclus�o da tarefa, se ela estiver nessa situa��o

    if ($RS["concluida"]=="S" && Nvl($RS["data_conclusao"],"")>"")
    {

      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Dados da conclus�o</td>";
      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>";
      $w_html=$w_html."\r\n"."          <tr valign=\"top\">";
      $w_html=$w_html."\r\n"."          <td><font size=\"1\">In�cio da execu��o:<br><b>".FormataDataEdicao($RS["inicio_real"])." </b></td>";
      $w_html=$w_html."\r\n"."          <td><font size=\"1\">T�rmino da execu��o:<br><b>".FormataDataEdicao($RS["fim_real"])." </b></td>";
      if ($w_tipo_visao==0)
      {

        $w_html=$w_html."\r\n"."          <td><font size=\"1\">Rercuso executado:<br><b>".$FormatNumber[$RS["custo_real"]][2]." </b></td>";
      } 

      $w_html=$w_html."\r\n"."          </table>";
      if ($w_tipo_visao==0)
      {

        $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Nota de conclus�o:<br><b>".CRLF2BR($RS["nota_conclusao"])." </b></td>";
      } 

    } 

  } 


// Se for listagem, exibe os outros dados dependendo do tipo de vis�o  do usu�rio

  if ($O=="L" && $w_tipo_visao!=2)
  {

    if ($RS["aviso_prox_conc"]=="S")
    {

// Configura��o dos alertas de proximidade da data limite para conclus�o da tarefa

      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Alerta</td>";
      $w_html=$w_html."\r\n"."      <tr><td align=\"center\" height=\"1\" bgcolor=\"#000000\"></td></tr>";
      $w_html=$w_html."\r\n"."      <tr><td><font size=1>Ser� enviado aviso a partir de <b>".$RS["dias_aviso"]."</b> dias antes de <b>".FormataDataEdicao($RS["fim"])."</b></font></td></tr>";
//w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100""" cellspacing=0>"

//w_html = w_html & VbCrLf & "          <td valign=""top""><font size=""1"">Emite aviso:<br><b>" & Replace(Replace(RS("aviso_prox_conc"),"S","Sim"),"N","N�o") & " </b></td>"

//w_html = w_html & VbCrLf & "          <td valign=""top""><font size=""1"">Dias:<br><b>" & RS("dias_aviso") & " </b></td>"

//w_html = w_html & VbCrLf & "          </table>"

    } 


// Interessados na execu��o da tarefa

    DB_GetSolicInter($RS,$w_chave,null,"LISTA");
$RS->Sort="nome_resumido";
    if (!$Rs->EOF)
    {

      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Interessados na execu��o</td>";
      $w_html=$w_html."\r\n"."      <tr><td align=\"center\" colspan=\"2\">";
      $w_html=$w_html."\r\n"."        <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
      $w_html=$w_html."\r\n"."          <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
      $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Nome</font></td>";
      $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Tipo de vis�o</font></td>";
      $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Envia e-mail</font></td>";
      $w_html=$w_html."\r\n"."          </tr>";
      $w_cor=$conTrBgColor;
      while(!$Rs->EOF)
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
      $w_html=$w_html."\r\n"."      <tr valign=\"top\" bgcolor=\"".$w_cor."\">";
      $w_html=$w_html."\r\n"."        <td><font size=\"1\">".$RS["nome_resumido"]."</td>";
      $w_html=$w_html."\r\n"."        <td><font size=\"1\">".RetornaTipoVisao($RS["tipo_visao"])."</td>";
      $w_html=$w_html."\r\n"."        <td align=\"center\"><font size=\"1\">".str_replace("N","N�o",str_replace("S","Sim",$RS["envia_email"]))."</td>";
      $w_html=$w_html."\r\n"."      </tr>";
$Rs->MoveNext;
    } 
    $w_html=$w_html."\r\n"."         </table></td></tr>";
  } 

  DesconectaBD();

// �reas envolvidas na execu��o da tarefa

  DB_GetSolicAreas($RS,$w_chave,null,"LISTA");
$RS->Sort="nome";
  if (!$Rs->EOF)
  {

    $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>�reas/Institui��es envolvidas</td>";
    $w_html=$w_html."\r\n"."      <tr><td align=\"center\" colspan=\"2\">";
    $w_html=$w_html."\r\n"."        <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
    $w_html=$w_html."\r\n"."          <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
    $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Nome</font></td>";
    $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Papel</font></td>";
    $w_html=$w_html."\r\n"."          </tr>";
    $w_cor=$conTrBgColor;
    while(!$Rs->EOF)
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
    $w_html=$w_html."\r\n"."      <tr valign=\"top\" bgcolor=\"".$w_cor."\">";
    $w_html=$w_html."\r\n"."        <td><font size=\"1\">".$RS["nome"]."</td>";
    $w_html=$w_html."\r\n"."        <td><font size=\"1\">".$RS["papel"]."</td>";
    $w_html=$w_html."\r\n"."      </tr>";
$Rs->MoveNext;
  } 
  $w_html=$w_html."\r\n"."         </table></td></tr>";
} 

DesconectaBD();
} 


if ($O=="L" || $O=="V")
{
// Se for listagem dos dados

if ($w_tipo_visao!=2)
{

// Arquivos vinculados

  DB_GetSolicAnexo($RS,$w_chave,null,$w_cliente);
$RS->Sort="nome";
  if (!$Rs->EOF)
  {

    $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Arquivos anexos</td>";
    $w_html=$w_html."\r\n"."      <tr><td align=\"center\" colspan=\"2\">";
    $w_html=$w_html."\r\n"."        <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
    $w_html=$w_html."\r\n"."          <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
    $w_html=$w_html."\r\n"."          <td><font size=\"1\"><b>T�tulo</font></td>";
    $w_html=$w_html."\r\n"."          <td><font size=\"1\"><b>Descri��o</font></td>";
    $w_html=$w_html."\r\n"."          <td><font size=\"1\"><b>Tipo</font></td>";
    $w_html=$w_html."\r\n"."          <td><font size=\"1\"><b>KB</font></td>";
    $w_html=$w_html."\r\n"."          </tr>";
    $w_cor=$conTrBgColor;
    while(!$Rs->EOF)
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
    $w_html=$w_html."\r\n"."      <tr valign=\"top\" bgcolor=\"".$w_cor."\">";
    $w_html=$w_html."\r\n"."        <td><font size=\"1\">".LinkArquivo("HL",$w_cliente,$RS["chave_aux"],"_blank","Clique para exibir o arquivo em outra janela.",$RS["nome"],null)."</td>";
    $w_html=$w_html."\r\n"."        <td><font size=\"1\">".Nvl($RS["descricao"],"---")."</td>";
    $w_html=$w_html."\r\n"."        <td><font size=\"1\">".$RS["tipo"]."</td>";
    $w_html=$w_html."\r\n"."        <td align=\"right\"><font size=\"1\">".round($cDbl[$RS["tamanho"]]/1024,1)."&nbsp;</td>";
    $w_html=$w_html."\r\n"."      </tr>";
$Rs->MoveNext;
  } 
  $w_html=$w_html."\r\n"."         </table></td></tr>";
} 

DesconectaBD();
} 


// Encaminhamentos

DB_GetSolicLog($RS,$w_chave,null,"LISTA");
$RS->Sort="data desc, sq_siw_solic_log desc";
$w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Ocorr�ncias e Anota��es</td>";
$w_html=$w_html."\r\n"."      <tr><td align=\"center\" colspan=\"2\">";
$w_html=$w_html."\r\n"."        <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
$w_html=$w_html."\r\n"."          <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
$w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Data</font></td>";
$w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Despacho/Observa��o</font></td>";
$w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Respons�vel</font></td>";
$w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Fase / Destinat�rio</font></td>";
$w_html=$w_html."\r\n"."          </tr>";
if ($Rs->EOF)
{

$w_html=$w_html."\r\n"."      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=6 align=\"center\"><font size=\"1\"><b>N�o foram encontrados encaminhamentos.</b></td></tr>";
}
  else
{

$w_html=$w_html."\r\n"."      <tr bgcolor=\"".$conTrBgColor."\" valign=\"top\">";
$w_html=$w_html."\r\n"."        <td colspan=6><font size=\"1\">Fase atual: <b>".$RS["fase"]."</b></td>";
$w_cor=$conTrBgColor;
while(!$Rs->EOF)
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
$w_html=$w_html."\r\n"."      <tr valign=\"top\" bgcolor=\"".$w_cor."\">";
$w_html=$w_html."\r\n"."        <td nowrap><font size=\"1\">".$FormatDateTime[$RS["data"]][2].", ".$FormatDateTime[$RS["data"]][4]."</td>";
if (Nvl($RS["caminho"],"")>"")
{

  $w_html=$w_html."\r\n"."        <td><font size=\"1\">".CRLF2BR(Nvl($RS["despacho"],"---")."<br>[".LinkArquivo("HL",$w_cliente,$RS["sq_siw_arquivo"],"_blank","Clique para exibir o arquivo em outra janela.","Anexo - ".$RS["tipo"]." - ".round($cDbl[$RS["tamanho"]]/1024,1)." KB",null)."]")."</td>";
}
  else
{

  $w_html=$w_html."\r\n"."        <td><font size=\"1\">".CRLF2BR(Nvl($RS["despacho"],"---"))."</td>";
} 

$w_html=$w_html."\r\n"."        <td nowrap><font size=\"1\">".ExibePessoa("../",$w_cliente,$RS["sq_pessoa"],$TP,$RS["responsavel"])."</td>";
if ((!!isset(Tvl($RS["sq_demanda_log"]))) && (!!isset(Tvl($RS["destinatario"]))))
{

  $w_html=$w_html."\r\n"."        <td nowrap><font size=\"1\">".Nvl($RS["destinatario"],"---")."</td>";
}
  else
if ((!!isset(Tvl($RS["sq_demanda_log"]))) && !isset(Tvl($RS["destinatario"])))
{

  $w_html=$w_html."\r\n"."        <td nowrap><font size=\"1\">Anota��o</td>";
}
  else
{

  $w_html=$w_html."\r\n"."        <td nowrap><font size=\"1\">".Nvl($RS["tramite"],"---")."</td>";
} 

$w_html=$w_html."\r\n"."      </tr>";
$Rs->MoveNext;
} 
} 

DesconectaBD();
$w_html=$w_html."\r\n"."         </table></td></tr>";

$w_html=$w_html."\r\n"."</table>";
} 


$VisualDemanda=$w_html;

$w_tipo_visao=null;

$w_erro=null;

$Rsquery=null;

$w_ImagemPadrao=null;

$w_Imagem=null;


return $function_ret;
} 
// =========================================================================

// Fim da visualiza��o dos dados do cliente

// -------------------------------------------------------------------------


?>


