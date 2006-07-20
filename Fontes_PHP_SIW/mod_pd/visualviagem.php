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
<!-- #INCLUDE FILE="../jScript.php" -->
<!-- #INCLUDE FILE="../Funcoes.php" -->
<!-- #INCLUDE FILE="../DML_Demanda.php" -->
<!-- #INCLUDE FILE="DB_Geral.php" -->
<!-- #INCLUDE FILE="DB_Viagem.php" -->
<!-- #INCLUDE FILE="DML_Viagem.php" -->
<!-- #INCLUDE FILE="Funcoes.php" -->
<!-- #INCLUDE FILE="DB_Tabelas.php" -->
<? 
// =========================================================================

// Rotina de visualiza��o dos dados da miss�o

// -------------------------------------------------------------------------

function VisualViagem($w_chave,$O,$w_usuario,$P1,$P4)
{
  extract($GLOBALS);



// $RS is of type "ADODB.RecordSet"

// $RSQuery is of type "ADODB.RecordSet"


  if ($P4==1)
  {
    $w_TrBgColor="";
  }
    else
  {
    $w_TrBgColor=$conTrBgColor;
  }
;
} 

$w_html="";

// Recupera os dados da viagem

DB_GetSolicData($w_chave,substr($SG,0,3)."GERAL");
$w_tramite=$RS["sq_siw_tramite"];
$w_valor=$cDbl[$RS["valor"]];
$w_fim=$cDate[$RS["fim"]];
$w_sg_tramite=$RS["sg_tramite"];
$w_ativo=$RS["ativo"];

// Recupera o tipo de vis�o do usu�rio

if ($cDbl[Nvl($RS["solicitante"],0)]==$cDbl[$w_usuario] || 
   $cDbl[Nvl($RS["executor"],0)]==$cDbl[$w_usuario] || 
   $cDbl[Nvl($RS["cadastrador"],0)]==$cDbl[$w_usuario] || 
   $cDbl[Nvl($RS["titular"],0)]==$cDbl[$w_usuario] || 
   $cDbl[Nvl($RS["substituto"],0)]==$cDbl[$w_usuario] || 
   $cDbl[Nvl($RS["tit_exec"],0)]==$cDbl[$w_usuario] || 
   $cDbl[Nvl($RS["subst_exec"],0)]==$cDbl[$w_usuario] || 
   SolicAcesso($w_chave,$w_usuario)>=8)
{

// Se for solicitante, executor ou cadastrador, tem vis�o completa

  $w_tipo_visao=0;
}
  else
{

  if (SolicAcesso($w_chave,$w_usuario)>2)
  {
    $w_tipo_visao=1;
  }
;
  } 
} 


// Se for listagem ou envio, exibe os dados de identifica��o do acordo

if ($O=="L" || $O=="V")
{
// Se for listagem dos dados

  $w_html=$w_html."\r\n"."<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
  $w_html=$w_html."\r\n"."<tr bgcolor=\"".$w_TrBgColor."\"><td>";

  $w_html=$w_html."\r\n"."    <table width=\"99%\" border=\"0\">";

  $w_html=$w_html."\r\n"."      <tr><td colspan=2>N�mero: <b>".$RS["codigo_interno"]." (".$w_chave.")<br>"."</b></td></tr>";

// Identifica��o do acordo

  $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><b>Identifica��o</td>";
  $w_html=$w_html."\r\n"."      <tr><td>Descri��o:<br><b>".$RS["descricao"]."</b></td>";
  $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>";
  $w_html=$w_html."\r\n"."          <tr valign=\"top\">";
  if (!$P4==1)
  {

    $w_html=$w_html."\r\n"."          <td>Unidade proponente:<br><b>".ExibeUnidade($w_dir_volta,$w_cliente,$RS["nm_unidade_resp"],$RS["sq_unidade_resp"],$TP)."</b></td>";
  }
    else
  {

    $w_html=$w_html."\r\n"."          <td>Unidade proponente:<br><b>".$RS["nm_unidade_resp"]."</b></td>";
  } 

  $w_html=$w_html."\r\n"."          <td valign=\"top\" colspan=\"2\">Tipo:<br><b>".$RS["nm_tipo_missao"]." </b></td>";
  $w_html=$w_html."\r\n"."          <td>Primeira sa�da:<br><b>".FormataDataEdicao($RS["inicio"])." </b></td>";
  $w_html=$w_html."\r\n"."          <td>�ltimo retorno:<br><b>".FormataDataEdicao($RS["fim"])." </b></td>";
  $w_html=$w_html."\r\n"."          </table>";

  if (Nvl($RS["justificativa_dia_util"],"")>"")
  {
// Se o campo de justificativa de dias �teis para estiver preenchido, exibe

    $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\">Justificativa para in�cio e t�rmino de viagens em sextas-feiras, s�bados, domingos e feriados:<br><b>".CRLF2BR($RS["justificativa_dia_util"])." </b></td>";
  } 


  if (Nvl($RS["justificativa"],"")>"")
  {
// Se o campo de justificativa estiver preenchido, exibe

    $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\">Justificativa do n�o cumprimento do prazo de solicita��o:<br><b>".CRLF2BR($RS["justificativa"])." </b></td>";
  } 


// Dados da conclus�o da demanda, se ela estiver nessa situa��o

  if (Nvl($RS["conclusao"],"")>"")
  {

    $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><b>Dados do encerramento</td>";
    $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>";
    $w_html=$w_html."\r\n"."          <tr valign=\"top\">";
    $w_html=$w_html."\r\n"."          <td>In�cio da vig�ncia:<br><b>".FormataDataEdicao($RS["inicio_real"])." </b></td>";
    $w_html=$w_html."\r\n"."          <td>T�rmino da vig�ncia:<br><b>".FormataDataEdicao($RS["fim_real"])." </b></td>";
    if ($w_tipo_visao==0)
    {

      $w_html=$w_html."\r\n"."          <td>Valor realizado:<br><b>".$FormatNumber[$RS["custo_real"]][2]." </b></td>";
    } 

    $w_html=$w_html."\r\n"."          </table>";
    if ($w_tipo_visao==0)
    {

      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\">Nota de conclus�o:<br><b>".CRLF2BR($RS["observacao"])." </b></td>";
    } 

  } 


// Vincula��es a tarefas

  DB_GetLinkData($RS1,$w_cliente,"ISTCAD");

  $DB_GetSolicList_IS$RS1  $RS1["sq_menu"]  $w_usuario//PDVINC", 5, _  $null  $null  $null  $null  $null  $null  $null  $null  $null  $null  $w_chave  $null  $null  $null  $null  $null  $null  $null  $null  $null  $null  $null  $null  $null  $null  $null  $null  $w_ano;
$RS1->Sort="titulo";
  if (!$RS1->EOF)
  {

    $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><b>Vinculada �s Tarefas</td>";
    $w_html=$w_html."\r\n"."      <tr><td align=\"center\" colspan=\"2\">";
    $w_html=$w_html."\r\n"."        <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
    $w_html=$w_html."\r\n"."          <tr bgcolor=\"".$w_TrBgColor."\" align=\"center\">";
    $w_html=$w_html."\r\n"."          <td><b>N�</td>";
    $w_html=$w_html."\r\n"."          <td><b>Tarefa</td>";
    $w_html=$w_html."\r\n"."          <td><b>In�cio</td>";
    $w_html=$w_html."\r\n"."          <td><b>Fim</td>";
    $w_html=$w_html."\r\n"."          <td><b>Situa��o</td>";
    $w_html=$w_html."\r\n"."          </tr>";
    $w_cor=$w_TrBgColor;
    $w_total=0;
    while(!$RS1->EOF)
    {

      if ($w_cor==$w_TrBgColor || $w_cor=="")
      {
        $w_cor=$conTrAlternateBgColor;
      }
        else
      {
        $w_cor=$w_TrBgColor;
      }
;
    } 
    $w_html=$w_html."\r\n"."      <tr valign=\"top\" bgcolor=\"".$w_cor."\">";
    $w_html=$w_html."\r\n"."        <td nowrap>";
    if ($RS1["concluida"]=="N")
    {

      if ($RS1["fim"]<time()())
      {

        $w_html=$w_html."\r\n"."           <img src=\"".$conImgAtraso."\" border=0 width=15 heigth=15 align=\"center\">";
      }
        else
      if ($RS1["aviso_prox_conc"]=="S" && ($RS1["aviso"]<=time()()))
      {

        $w_html=$w_html."\r\n"."           <img src=\"".$conImgAviso."\" border=0 width=15 height=15 align=\"center\">";
      }
        else
      {

        $w_html=$w_html."\r\n"."           <img src=\"".$conImgNormal."\" border=0 width=15 height=15 align=\"center\">";
      } 

    }
      else
    {

      if ($RS1["fim"]<Nvl($RS1["fim_real"],$RS1["fim"]))
      {

        $w_html=$w_html."\r\n"."           <img src=\"".$conImgOkAtraso."\" border=0 width=15 heigth=15 align=\"center\">";
      }
        else
      {

        $w_html=$w_html."\r\n"."           <img src=\"".$conImgOkNormal."\" border=0 width=15 height=15 align=\"center\">";
      } 

    } 

    $w_html=$w_html."\r\n"."        <A class=\"HL\" HREF=\"".$w_dir."Tarefas.asp?par=visual&R=".$w_pagina.$par."&O=L&w_chave=".$RS1["sq_siw_solicitacao"]."&w_tipo=Volta&P1=2&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Exibe as informa��es da tarefa.\">".$RS1["sq_siw_solicitacao"]."</a>";
    if (strlen(Nvl($RS1["titulo"],"-"))>50)
    {
      $w_titulo=substr(Nvl($RS1["titulo"],"-"),0,50)."...";
    }
      else
    {
      $w_titulo=Nvl($RS1["titulo"],"-");
    }
;
  } 
  if ($RS1["sg_tramite"]=="CA")
  {

    $w_html=$w_html."\r\n"."        <td title=\"".str_replace("\r\n","\n",str_replace("\"","\'",str_replace("'","\'",$RS1["titulo"])))."\"><strike>".$w_titulo."</strike></td>";
  }
    else
  {

    $w_html=$w_html."\r\n"."        <td title=\"".str_replace("\r\n","\n",str_replace("\"","\'",str_replace("'","\'",$RS1["titulo"])))."\">".$w_titulo."</td>";
  } 

  if ($RS1["concluida"]=="N")
  {

    $w_html=$w_html."\r\n"."        <td align=\"center\">".FormataDataEdicao($RS1["inicio"])."</td>";
    $w_html=$w_html."\r\n"."        <td align=\"center\">".FormataDataEdicao($RS1["fim"])."</td>";
  }
    else
  {

    $w_html=$w_html."\r\n"."        <td align=\"center\">".FormataDataEdicao($RS1["inicio_real"])."</td>";
    $w_html=$w_html."\r\n"."        <td align=\"center\">".FormataDataEdicao($RS1["fim_real"])."</td>";
  } 

  $w_html=$w_html."\r\n"."        <td>".$RS1["nm_tramite"]."</td>";
  $w_html=$w_html."\r\n"."      </tr>";
$RS1->MoveNext;
} 
$w_html=$w_html."\r\n"."         </table></td></tr>";
} 

$RS1->Close;

// Outra parte

DB_GetBenef($w_cliente,Nvl($RS["sq_prop"],0),null,null,null,1,null,null);
$w_html=$w_html."\r\n"."      <tr><td colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><b>Proposto</td>";
if (($RSQuery==0))
{

$w_html=$w_html."\r\n"."      <tr><td colspan=2><b>Proposto n�o informado.";
}
  else
{

$w_html=$w_html."\r\n"."      <tr><td colspan=2><table border=0 width=\"100%\">";
$w_html=$w_html."\r\n"."      <tr valign=\"top\">";
$w_html=$w_html."\r\n"."          <td>CPF:<b><br>".$RSQuery["cpf"]."</td>";
$w_html=$w_html."\r\n"."          <td>Nome:<b><br>".$RSQuery["nm_pessoa"]."</td>";
$w_html=$w_html."\r\n"."          <td>Nome resumido:<b><br>".$RSQuery["nome_resumido"]."</td>";
$w_html=$w_html."\r\n"."          <td>Sexo:<b><br>".$RSQuery["nm_sexo"]."</td>";
$w_html=$w_html."\r\n"."      <tr valign=\"top\">";
$w_html=$w_html."\r\n"."          <td>Matr�cula SIAPE:<b><br>".Nvl($RS["matricula"],"---")."</td>";
$w_html=$w_html."\r\n"."          <td>Identidade:<b><br>".Nvl($RSQuery["rg_numero"],"---")."</td>";
$w_html=$w_html."\r\n"."          <td>Data de emiss�o:<b><br>".Nvl($RSQuery["rg_emissao"],"---")."</td>";
$w_html=$w_html."\r\n"."          <td>�rg�o emissor:<b><br>".Nvl($RSQuery["rg_emissor"],"---")."</td>";
$w_html=$w_html."\r\n"."      <tr><td colspan=\"4\" align=\"center\" style=\"border: 1px solid rgb(0,0,0);\"><b>Telefones</td>";
$w_html=$w_html."\r\n"."      <tr valign=\"top\">";
$w_html=$w_html."\r\n"."          <td>Telefone:<b><br>(".Nvl($RSQuery["ddd"],"---").") ".Nvl($RSQuery["nr_telefone"],"---")."</td>";
$w_html=$w_html."\r\n"."          <td>Fax:<b><br>".Nvl($RSQuery["nr_fax"],"---")."</td>";
$w_html=$w_html."\r\n"."          <td>Celular:<b><br>".Nvl($RSQuery["nr_celular"],"---")."</td>";
$w_html=$w_html."\r\n"."      <tr><td colspan=\"4\" align=\"center\" style=\"border: 1px solid rgb(0,0,0);\"><b>Dados banc�rios</td>";
if (1==1)
{
//Instr("CREDITO,DEPOSITO",RS("sg_forma_pagamento")) > 0 Then

  $w_html=$w_html."\r\n"."          <tr valign=\"top\">";
  if (Nvl($RS["cd_banco"],"")>"")
  {

    $w_html=$w_html."\r\n"."          <td>Banco:<b><br>".$RS["cd_banco"]." - ".$RS["nm_banco"]."</td>";
    $w_html=$w_html."\r\n"."          <td>Ag�ncia:<b><br>".$RS["cd_agencia"]." - ".$RS["nm_agencia"]."</td>";
    $w_html=$w_html."\r\n"."          <td>Opera��o:<b><br>".Nvl($RS["operacao_conta"],"---")."</td>";
    $w_html=$w_html."\r\n"."          <td>N�mero da conta:<b><br>".Nvl($RS["numero_conta"],"---")."</td>";
  }
    else
  {

    $w_html=$w_html."\r\n"."          <td>Banco:<b><br>---</td>";
    $w_html=$w_html."\r\n"."          <td>Ag�ncia:<b><br>---</td>";
    $w_html=$w_html."\r\n"."          <td>Opera��o:<b><br>---</td>";
    $w_html=$w_html."\r\n"."          <td>N�mero da conta:<b><br>---</td>";
  } 

}
  else
if ($RS["sg_forma_pagamento"]="ORDEM"$Then;
$w_html==$w_html."\r\n"."          <tr valign=\"top\">")
{
  if (Nvl($RS["cd_banco"],"")>"")
  {

    $w_html=$w_html."\r\n"."          <td>Banco:<b><br>".$RS["cd_banco"]." - ".$RS["nm_banco"]."</td>";
    $w_html=$w_html."\r\n"."          <td>Ag�ncia:<b><br>".$RS["cd_agencia"]." - ".$RS["nm_agencia"]."</td>";
  }
    else
  {

    $w_html=$w_html."\r\n"."          <td>Banco:<b><br>---</td>";
    $w_html=$w_html."\r\n"."          <td>Ag�ncia:<b><br>---</td>";
  } 

}
  else
if ($RS["sg_forma_pagamento"]="EXTERIOR"$Then;
$w_html==$w_html."\r\n"."          <tr valign=\"top\">")
{
  $w_html=$w_html."\r\n"."          <td>Banco:<b><br>".$RS["banco_estrang"]."</td>";
  $w_html=$w_html."\r\n"."          <td>ABA Code:<b><br>".Nvl($RS["aba_code"],"---")."</td>";
  $w_html=$w_html."\r\n"."          <td>SWIFT Code:<b><br>".Nvl($RS["swift_code"],"---")."</td>";
  $w_html=$w_html."\r\n"."          <tr><td colspan=3>Endere�o da ag�ncia:<b><br>".Nvl($RS["endereco_estrang"],"---")."</td>";
  $w_html=$w_html."\r\n"."          <tr valign=\"top\">";
  $w_html=$w_html."\r\n"."          <td colspan=2>Ag�ncia:<b><br>".Nvl($RS["agencia_estrang"],"---")."</td>";
  $w_html=$w_html."\r\n"."          <td>N�mero da conta:<b><br>".Nvl($RS["numero_conta"],"---")."</td>";
  $w_html=$w_html."\r\n"."          <tr valign=\"top\">";
  $w_html=$w_html."\r\n"."          <td colspan=2>Cidade:<b><br>".$RS["nm_cidade"]."</td>";
  $w_html=$w_html."\r\n"."          <td>Pa�s:<b><br>".$RS["nm_pais"]."</td>";
} 

$w_html=$w_html."\r\n"."        </table>";
} 

} 


// Se for listagem, exibe os outros dados dependendo do tipo de vis�o  do usu�rio

if ($w_tipo_visao!=2 && ($O=="L" || $O=="T"))
{

if ($RS["aviso_prox_conc"]="S"$Then;
) // Configura��o dos alertas de proximidade da data limite para conclus�o do acordo
{

$w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><b>Alertas</td>";
$w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>";
$w_html=$w_html."\r\n"."          <td valign=\"top\">Emite aviso:<br><b>".str_replace("N","N�o",str_replace("S","Sim",$RS["aviso_prox_conc"]))." </b></td>";
$w_html=$w_html."\r\n"."          <td valign=\"top\">Dias:<br><b>".$RS["dias_aviso"]." </b></td>";
$w_html=$w_html."\r\n"."          </table>";
} 

} 


// Deslocamentos

$DB_GetPD_Deslocamento$w_chave$null//PDGERAL"echo "saida, chegada";
if (!($Rs==0))
{

$w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><b>Deslocamentos</td>";
$w_html=$w_html."\r\n"."      <tr><td align=\"center\" colspan=\"2\">";
$w_html=$w_html."\r\n"."        <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
$w_html=$w_html."\r\n"."          <tr bgcolor=\"".$w_TrBgColor."\" align=\"center\">";
$w_html=$w_html."\r\n"."          <td><b>Origem</td>";
$w_html=$w_html."\r\n"."          <td><b>Destino</td>";
$w_html=$w_html."\r\n"."          <td><b>Saida</td>";
$w_html=$w_html."\r\n"."          <td><b>Chegada</td>";
$w_html=$w_html."\r\n"."          </tr>";
$w_cor=$w_TrBgColor;
$w_total=0;
while(!($Rs==0))
{

if ($w_cor==$w_TrBgColor || $w_cor=="")
{
  $w_cor=$conTrAlternateBgColor;
}
  else
{
  $w_cor=$w_TrBgColor;
}
;
} 
$w_html=$w_html."\r\n"."      <tr valign=\"top\" bgcolor=\"".$w_cor."\">";
$w_html=$w_html."\r\n"."        <td>".$RS["nm_origem"]."</td>";
$w_html=$w_html."\r\n"."        <td>".$RS["nm_destino"]."</td>";
$w_html=$w_html."\r\n"."        <td align=\"center\">".FormataDataEdicao($FormatDateTime[$RS["saida"]][2]).", ".substr($FormatDateTime[$RS["saida"]][3],0,5)."</td>";
$w_html=$w_html."\r\n"."        <td align=\"center\">".FormataDataEdicao($FormatDateTime[$RS["chegada"]][2]).", ".substr($FormatDateTime[$RS["chegada"]][3],0,5)."</td>";
$w_html=$w_html."\r\n"."      </tr>";
$Rs=mysql_fetch_array($Rs_query);

} 
$w_html=$w_html."\r\n"."         </table></td></tr>";
} 

DesconectaBD();

// Benef�cios servidor

DB_GetSolicData($w_chave,"PDGERAL");
if (!($RS==0))
{

$w_html=$w_html."\r\n"."        <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><b>Benef�cios recebidos pelo servidor</td>";
$w_html=$w_html."\r\n"."        <tr><td align=\"center\" colspan=\"2\">";
$w_html=$w_html."\r\n"."          <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
$w_html=$w_html."\r\n"."            <tr valign=\"top\">";
if ($cDbl[Nvl($RS["valor_alimentacao"],0)]>0)
{

$w_html=$w_html."\r\n"."           <td>Aux�lio-alimenta��o: <b>Sim</b></td>";
}
  else
{

$w_html=$w_html."\r\n"."           <td>Aux�lio-alimenta��o: <b>N�o</b></td>";
} 

$w_html=$w_html."\r\n"."              <td>Valor R$: <b>".$FormatNumber[Nvl($RS["valor_alimentacao"],0)][2]."</b></td>";
$w_html=$w_html."\r\n"."            </tr>";
$w_html=$w_html."\r\n"."            <tr valign=\"top\">";
if ($cDbl[Nvl($RS["valor_transporte"],0)]>0)
{

$w_html=$w_html."\r\n"."           <td>Aux�lio-transporte: <b>Sim</b></td>";
}
  else
{

$w_html=$w_html."\r\n"."           <td>Aux�lio-transporte: <b>N�o</b></td>";
} 

$w_html=$w_html."\r\n"."              <td>Valor R$: <b>".$FormatNumber[Nvl($RS["valor_transporte"],0)][2]."</b></td>";
$w_html=$w_html."\r\n"."            </tr>";
$w_html=$w_html."\r\n"."          </table></td></tr>";
} 

DesconectaBD();

//Dados da viagem

$w_html=$w_html."\r\n"."        <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><b>Dados da viagem/c�lculo das di�rias</td>";
$DB_GetPD_Deslocamento$w_chave$null//DADFIN"echo "saida, chegada";
if (!($RSQuery==0))
{

$w_html=$w_html."\r\n"."     <tr><td align=\"center\" colspan=\"2\">";
$w_html=$w_html."\r\n"."       <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
$w_html=$w_html."\r\n"."         <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
$w_html=$w_html."\r\n"."         <td><b>Destino</td>";
$w_html=$w_html."\r\n"."         <td><b>Saida</td>";
$w_html=$w_html."\r\n"."         <td><b>Chegada</td>";
$w_html=$w_html."\r\n"."         <td><b>Quantidade de di�rias</td>";
$w_html=$w_html."\r\n"."         <td><b>Valor unit�rio R$</td>";
$w_html=$w_html."\r\n"."         <td><b>Total por localidade - R$</td>";
$w_html=$w_html."\r\n"."         </tr>";
$w_cor=$conTrBgColor;
$w_total=0;
while(!($RSQuery==0))
{

$w_html=$w_html."\r\n"."     <tr valign=\"top\" bgcolor=\"".$conTrBgColor."\">";
$w_html=$w_html."\r\n"."       <td>".$RSQuery["nm_destino"]."</td>";
$w_html=$w_html."\r\n"."       <td align=\"center\">".FormataDataEdicao($FormatDateTime[$RSQuery["saida"]][2]).", ".substr($FormatDateTime[$RSQuery["saida"]][3],0,5)."</td>";
$w_html=$w_html."\r\n"."       <td align=\"center\">".FormataDataEdicao($FormatDateTime[$RSQuery["chegada"]][2]).", ".substr($FormatDateTime[$RSQuery["chegada"]][3],0,5)."</td>";
$w_html=$w_html."\r\n"."       <td align=\"right\">".$FormatNumber[Nvl($RSQuery["quantidade"],0)][1]."</td>";
$w_html=$w_html."\r\n"."       <td align=\"right\">".$FormatNumber[Nvl($RSQuery["valor"],0)][2]."</td>";
$w_html=$w_html."\r\n"."       <td align=\"right\" bgcolor=\"".$conTrAlternateBgColor."\">".$FormatNumber[$cDbl[$FormatNumber[Nvl($RSQuery["quantidade"],0)][1]]*$cDbl[$FormatNumber[Nvl($RSQuery["valor"],0)][2]]][2]."</td>";
$w_html=$w_html."\r\n"."     </tr>";
$w_total=$w_total+($cDbl[$FormatNumber[Nvl($RSQuery["quantidade"],0)][1]]*$cDbl[$FormatNumber[Nvl($RSQuery["valor"],0)][2]]);
$RSQuery=mysql_fetch_array($RSQuery_query);

} 

$w_html=$w_html."\r\n"."        <tr bgcolor=\"".$conTrBgColor."\">";
$w_html=$w_html."\r\n"."          <td rowspan=\"5\" align=\"right\" colspan=\"3\">&nbsp;</td>";
$w_html=$w_html."\r\n"."          <td colspan=\"2\"><b>(a) subtotal:</b></td>";
$w_html=$w_html."\r\n"."          <td align=\"right\" bgcolor=\"".$conTrAlternateBgColor."\">".$FormatNumber[Nvl($w_total,0)][2]."</td>";
$w_html=$w_html."\r\n"."        </tr>";
$w_html=$w_html."\r\n"."        <tr bgcolor=\"".$conTrBgColor."\">";
$w_html=$w_html."\r\n"."          <td colspan=\"2\"><b>(b) adicional:</b></td>";
$w_html=$w_html."\r\n"."          <td align=\"right\" bgcolor=\"".$conTrBgColor."\">".$FormatNumber[Nvl($RS["valor_adicional"],0)][2]."</td>";
$w_html=$w_html."\r\n"."        </tr>";
$w_html=$w_html."\r\n"."        <tr bgcolor=\"".$conTrBgColor."\">";
$w_html=$w_html."\r\n"."          <td colspan=\"2\"><b>(c) desconto aux�lio-alimenta��o:</b></td>";
$w_html=$w_html."\r\n"."          <td align=\"right\" bgcolor=\"".$conTrBgColor."\">".$FormatNumber[Nvl($RS["desconto_alimentacao"],0)][2]."</td>";
$w_html=$w_html."\r\n"."        </tr>";
$w_html=$w_html."\r\n"."        <tr bgcolor=\"".$conTrBgColor."\">";
$w_html=$w_html."\r\n"."          <td colspan=\"2\"><b>(d) desconto aux�lio-transporte:</b></td>";
$w_html=$w_html."\r\n"."          <td align=\"right\" bgcolor=\"".$conTrBgColor."\">".$FormatNumber[Nvl($RS["desconto_transporte"],0)][2]."</td>";
$w_html=$w_html."\r\n"."        </tr>";
$w_html=$w_html."\r\n"."        <tr bgcolor=\"".$conTrBgColor."\">";
$w_html=$w_html."\r\n"."          <td colspan=\"2\"><b>Total(a + b - c - d):</b></td>";
$w_html=$w_html."\r\n"."          <td align=\"right\" bgcolor=\"".$conTrAlternateBgColor."\">".$FormatNumber[$cDbl[$FormatNumber[Nvl($w_total,0)][2]]+$cDbl[$FormatNumber[Nvl($RS["valor_adicional"],0)][2]]-$cDbl[$FormatNumber[Nvl($RS["desconto_alimentacao"],0)][2]]-$cDbl[$FormatNumber[Nvl($RS["desconto_transporte"],0)][2]]][2]."</td>";
$w_html=$w_html."\r\n"."        </tr>";
$w_html=$w_html."\r\n"."        </table></td></tr>";
} 

DesconectaBD();

// Bilhete de passagem

$DB_GetPD_Deslocamento$w_chave$null$SG;
echo "saida, chegada";
if (!($RS==0))
{

if ($RS["sq_cia_transporte"]>"")
{

$w_html=$w_html."\r\n"."  <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
$w_html=$w_html."\r\n"."    <tr bgcolor=\"".$conTrBgColor."\"><td>";
$w_html=$w_html."\r\n"."      <table width=\"99%\" border=\"0\">";
$w_html=$w_html."\r\n"."        <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Bilhete de passagem</td>";
$w_html=$w_html."\r\n"."     <tr><td align=\"center\" colspan=\"2\">";
$w_html=$w_html."\r\n"."       <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
$w_html=$w_html."\r\n"."         <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
$w_html=$w_html."\r\n"."         <td><font size=\"1\"><b>Origem</font></td>";
$w_html=$w_html."\r\n"."         <td><font size=\"1\"><b>Destino</font></td>";
$w_html=$w_html."\r\n"."         <td><font size=\"1\"><b>Saida</font></td>";
$w_html=$w_html."\r\n"."         <td><font size=\"1\"><b>Chegada</font></td>";
$w_html=$w_html."\r\n"."         <td><font size=\"1\"><b>Cia. transporte</font></td>";
$w_html=$w_html."\r\n"."         <td><font size=\"1\"><b>C�digo v�o</font></td>";
$w_html=$w_html."\r\n"."         </tr>";
$w_cor=$conTrBgColor;
while(!($RS==0))
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
$w_html=$w_html."\r\n"."     <tr valign=\"middle\" bgcolor=\"".$w_cor."\">";
$w_html=$w_html."\r\n"."       <td><font size=\"1\">".Nvl($RS["nm_origem"],"---")."</td>";
$w_html=$w_html."\r\n"."       <td><font size=\"1\">".Nvl($RS["nm_destino"],"---")."</td>";
$w_html=$w_html."\r\n"."       <td align=\"center\"><font size=\"1\">".FormataDataEdicao($FormatDateTime[$RS["saida"]][2]).", ".substr($FormatDateTime[$RS["saida"]][3],0,5)."</td>";
$w_html=$w_html."\r\n"."       <td align=\"center\"><font size=\"1\">".FormataDataEdicao($FormatDateTime[$RS["chegada"]][2]).", ".substr($FormatDateTime[$RS["chegada"]][3],0,5)."</td>";
$w_html=$w_html."\r\n"."       <td><font size=\"1\">".Nvl($RS["nm_cia_transporte"],"---")."</td>";
$w_html=$w_html."\r\n"."       <td><font size=\"1\">".Nvl($RS["codigo_voo"],"---")."</td>";
$w_html=$w_html."\r\n"."     </tr>";
$RS=mysql_fetch_array($RS_query);

} 
$w_html=$w_html."\r\n"."        </tr>";
$w_html=$w_html."\r\n"."        </table></td></tr>";
DesconectaBD();
DB_GetSolicData($w_chave,"PDGERAL");
$w_html=$w_html."\r\n"."        <tr><td colspan=\"2\"><font size=\"1\"><b>N� do PTA/Ticket: </b>".$RS["PTA"]."</td>";
$w_html=$w_html."\r\n"."        <tr><td><font size=\"1\"><b>Data da emiss�o: </b>".FormataDataEdicao($RS["emissao_bilhete"])."</td>";
$w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Valor das passagens R$: </b>".$FormatNumber[Nvl($RS["valor_passagem"],0)][2]."</td>";
DesconectaBD();
$w_html=$w_html."\r\n"."      </table>";
$w_html=$w_html."\r\n"."    </td>";
$w_html=$w_html."\r\n"."</tr>";
} 

} 

// Se for envio, executa verifica��es nos dados da solicita��o

$w_erro=$ValidaViagem[$w_cliente][$w_chave][substr($SG,0,2)."GERAL"][null][null];
if ($w_erro>"")
{

$w_html=$w_html."\r\n"."<tr bgcolor=\"".$w_TrBgColor."\"><td colspan=2>";
$w_html=$w_html."\r\n"."<HR>";
if (substr($w_erro,0,1)=="0")
{

$w_html=$w_html."\r\n"."  <font color=\"#BC3131\"><b>ATEN��O:</b> Foram identificados os erros listados abaixo, n�o sendo poss�vel seu encaminhamento para fases posteriores � atual.";
}
  else
if (substr($w_erro,0,1)=="1")
{

$w_html=$w_html."\r\n"."  <font color=\"#BC3131\"><b>ATEN��O:</b> Foram identificados os erros listados abaixo. Seu encaminhamento para fases posteriores � atual s� pode ser feito por um gestor do sistema ou do m�dulo de projetos.";
}
  else
{

$w_html=$w_html."\r\n"."  <font color=\"#BC3131\"><b>ATEN��O:</b> Foram identificados os alertas listados abaixo. Eles n�o impedem o encaminhamento para fases posteriores � atual, mas conv�m sua verifica��o.";
} 

$w_html=$w_html."\r\n"."  <ul>".substr($w_erro,1,1000)."</ul>";
$w_html=$w_html."\r\n"."  </td></tr>";
} 


if ($O=="L" || $O=="V" || $O=="T")
{
// Se for listagem dos dados

// Encaminhamentos

DB_GetSolicLog($w_chave,null,"LISTA");
echo "data desc, sq_siw_solic_log desc";
//w_html = w_html & VbCrLf & "      <tr><td>&nbsp;</td>"

$w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><b>Ocorr�ncias e Anota��es</td>";
$w_html=$w_html."\r\n"."      <tr><td align=\"center\" colspan=\"2\">";
$w_html=$w_html."\r\n"."        <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
$w_html=$w_html."\r\n"."          <tr bgcolor=\"".$w_TrBgColor."\" align=\"center\">";
$w_html=$w_html."\r\n"."            <td><b>Data</td>";
$w_html=$w_html."\r\n"."            <td><b>Despacho/Observa��o</td>";
$w_html=$w_html."\r\n"."            <td><b>Respons�vel</td>";
$w_html=$w_html."\r\n"."            <td><b>Fase</td>";
$w_html=$w_html."\r\n"."          </tr>";
if (($Rs==0))
{

$w_html=$w_html."\r\n"."      <tr bgcolor=\"".$w_TrBgColor."\"><td colspan=6 align=\"center\"><b>N�o foram encontrados encaminhamentos.</b></td></tr>";
}
  else
{

$w_html=$w_html."\r\n"."      <tr bgcolor=\"".$w_TrBgColor."\" valign=\"top\">";
$w_html=$w_html."\r\n"."        <td colspan=6>Fase atual: <b>".$RS["fase"]."</b></td>";
$w_cor=$w_TrBgColor;
if ($w_ativo=="S")
{

// Recupera os respons�veis pelo tramite

DB_GetTramiteResp($RS1,$w_chave,null,null);
$w_html=$w_html."\r\n"."      <tr bgcolor=\"".$w_TrBgColor."\" valign=\"top\">";
$w_html=$w_html."\r\n"."        <td colspan=6>Respons�veis pelo tramite: <b>";
if (!$RS1->EOF)
{

$w_tramite_resp=$RS1["nome_resumido"];
$w_html=$w_html."\r\n".ExibePessoa($w_dir_volta,$w_cliente,$RS1["sq_pessoa"],$TP,$RS1["nome_resumido"]);
$RS1->MoveNext;
while(!$RS1->EOF)
{

  if ((strpos($w_tramite_resp,$RS1["nome_resumido"]) ? strpos($w_tramite_resp,$RS1["nome_resumido"])+1 : 0)==0)
  {

    $w_html=$w_html."\r\n".", ".ExibePessoa($w_dir_volta,$w_cliente,$RS1["sq_pessoa"],$TP,$RS1["nome_resumido"]);
    $w_tramite_resp=$w_tramite_resp.$RS1["nome_resumido"];
  }
    else
  {

    $w_tramite_resp=$w_tramite_resp.$RS1["nome_resumido"];
  } 

$RS1->MoveNext;
} 
} 

$w_html=$w_html."\r\n"."</b></td>";
} 

while(!($Rs==0))
{

if ($w_cor==$w_TrBgColor || $w_cor=="")
{
$w_cor=$conTrAlternateBgColor;
}
  else
{
$w_cor=$w_TrBgColor;
}
;
} 
$w_html=$w_html."\r\n"."      <tr valign=\"top\" bgcolor=\"".$w_cor."\">";
$w_html=$w_html."\r\n"."        <td nowrap>".$FormatDateTime[$RS["data"]][2].", ".$FormatDateTime[$RS["data"]][4]."</td>";
if (Nvl($RS["caminho"],"")>"")
{

$w_html=$w_html."\r\n"."        <td><font size=\"1\">".CRLF2BR(Nvl($RS["despacho"],"---")."<br>".LinkArquivo("HL",$w_cliente,$RS["sq_siw_arquivo"],"_blank","Clique para exibir o anexo em outra janela.","Anexo - ".$RS["tipo"]." - ".round($cDbl[$RS["tamanho"]]/1024,1)." KB",null))."</td>";
}
  else
{

$w_html=$w_html."\r\n"."        <td><font size=\"1\">".CRLF2BR(Nvl($RS["despacho"],"---"))."</td>";
} 

if (!$P4==1)
{

$w_html=$w_html."\r\n"."        <td nowrap>".ExibePessoa($w_dir_volta,$w_cliente,$RS["sq_pessoa"],$TP,$RS["responsavel"])."</td>";
}
  else
{

$w_html=$w_html."\r\n"."        <td nowrap>".$RS["responsavel"]."</td>";
} 

if ((!!isset(Tvl($RS["sq_demanda_log"]))) && (!!isset(Tvl($RS["destinatario"]))))
{

if (!$P4==1)
{

$w_html=$w_html."\r\n"."        <td nowrap>".ExibePessoa($w_dir_volta,$w_cliente,$RS["sq_pessoa_destinatario"],$TP,$RS["destinatario"])."</td>";
}
  else
{

$w_html=$w_html."\r\n"."        <td nowrap>".$RS["destinatario"]."</td>";
} 

}
  else
if ($RS["origem"]="ANOTACAO"$Then;
$w_html==$w_html."\r\n"."        <td nowrap><font size=\"1\">Anota��o</td>")
{
}
  else
{

$w_html=$w_html."\r\n"."        <td nowrap><font size=\"1\">".Nvl($RS["tramite"],"---")."</td>";
} 

$w_html=$w_html."\r\n"."      </tr>";
$Rs=mysql_fetch_array($Rs_query);

} 
} 

DesconectaBD();
$w_html=$w_html."\r\n"."         </table></td></tr>";

} 

$w_html=$w_html."\r\n"."    </table>";
$w_html=$w_html."\r\n"."</table>";

$VisualViagem=$w_html;

$w_tipo_visao=null;

$w_erro=null;

$Rsquery=null;

$w_ImagemPadrao=null;

$w_Imagem=null;

$w_titulo=null;


return $function_ret;
} 
// =========================================================================

// Fim da visualiza��o dos dados do cliente

// -------------------------------------------------------------------------


?>


