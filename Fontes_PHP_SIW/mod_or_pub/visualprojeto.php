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
<!-- #INCLUDE FILE="../DB_Link.php" -->
<!-- #INCLUDE FILE="../DB_EO.php" -->
<!-- #INCLUDE FILE="../DML_Projeto.php" -->
<!-- #INCLUDE FILE="../DML_Solic.php" -->
<!-- #INCLUDE FILE="../jScript.php" -->
<!-- #INCLUDE FILE="../Funcoes.php" -->
<!-- #INCLUDE FILE="../cp_upload/_upload.php" -->
<!-- #INCLUDE FILE="Funcoes.php" -->
<!-- #INCLUDE FILE="DB_Tabelas.php" -->
<!-- #INCLUDE FILE="DML_Tabelas.php" -->
<!-- #INCLUDE FILE="DB_SIAFI.php" -->
<? 

// =========================================================================

// Rotina de visualização dos dados da ação

// -------------------------------------------------------------------------

function VisualProjeto($w_chave,$O,$w_usuario,$P1,$P4)
{
  extract($GLOBALS);




  $w_html="";

// Recupera os dados da ação

  DB_GetSolicData($RS,$w_chave,"PJGERAL");

// O código abaixo foi comentado em 23/11/2004, devido à mudança na regra definida pelo usuário,

// que agora permite visão geral para todos os usuários


// Recupera o tipo de visão do usuário

//If cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) or _

//   cDbl(Nvl(RS("executor"),0))    = cDbl(w_usuario) or _

//   cDbl(Nvl(RS("cadastrador"),0)) = cDbl(w_usuario) or _

//   cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _

//   cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) or _

//   cDbl(Nvl(RS("tit_exec"),0))    = cDbl(w_usuario) or _

//   cDbl(Nvl(RS("subst_exec"),0))  = cDbl(w_usuario) Then

//   ' Se for solicitante, executor ou cadastrador, tem visão completa//   w_tipo_visao = 0

//Else

//   DB_GetSolicInter Rsquery, w_chave, w_usuario, "REGISTRO"

//   If Not RSquery.EOF Then

//      ' Se for interessado, verifica a visão cadastrada para ele.//      w_tipo_visao = cDbl(RSquery("tipo_visao"))

//   Else

//      DB_GetSolicAreas Rsquery, w_chave, Session("sq_lotacao"), "REGISTRO"

//      If Not RSquery.EOF Then

//         ' Se for de uma das unidades envolvidas, tem visão parcial//         w_tipo_visao = 1

//      Else

//         ' Caso contrário, tem visão resumida//         w_tipo_visao = 2

//      End If

//   End If

//End If


  $w_tipo_visao=0;

//Se for para exibir só a ficha resumo da ação.

  if ($P1==1 || $P1==2)
  {

    $w_html=$w_html."\r\n"."<div align=center><center>";
    $w_html=$w_html."\r\n"."  <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
    $w_html=$w_html."\r\n"."    <tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\" colspan=\"2\">";
    $w_html=$w_html."\r\n"."      <table width=\"100%\" border=\"0\">";
    if (!$P4==1)
    {

      $w_html=$w_html."\r\n"."      <tr><td align=\"right\" colspan=\"3\"><font size=\"1\"><b><A class=\"HL\" HREF=\"".$w_dir."Projeto.asp?par=Visual&O=L&w_chave=".$RS["sq_siw_solicitacao"]."&w_tipo=volta&P1=&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\" title=\"Exibe as informações da ação.\">Exibir todas as informações</a></td></tr>";
    } 


// Se a iniciativa prioritária for informada, exibe.

    if (!!isset($RS["sq_orprioridade"]))
    {

      $w_html=$w_html."\r\n"." <tr><td valign=\"top\" colspan=\"3\"><font size=\"1\">Iniciativa prioritária:<br><b>".$RS["nm_pri"]."</b></td></tr>";
    } 


// Se a ação no PPA for informada, exibe.

    if (!!isset($RS["sq_acao_ppa"]))
    {

      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"3\"><font size=\"1\">Programa PPA:<br><b>".$RS["nm_ppa_pai"]."</b></td></tr>";
      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"3\"><font size=\"1\">Cód:<br><b>".$RS["cd_ppa_pai"]."</b></td></tr>";
      $w_html=$w_html."\r\n"."      <tr bgcolor=\"#D0D0D0\"><td valign=\"top\" colspan=\"3\"><font size=\"1\">Ação PPA:<br><b>".$RS["nm_ppa"]." </b></td></tr>";
      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"1\"><font size=\"1\">Cód:<br><b>".$RS["cd_ppa"]."</b></td>";
    }
      else
    {

      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"1\"><font size=\"1\">Ação:<br><b>".$RS["titulo"]."</b></td>";
    } 

    if ($w_tipo_visao==0)
    {
// Se for visão completa

      $w_html=$w_html."\r\n"."          <td valign=\"top\" colspan=\"2\"><font size=\"1\">Recurso programado:<br><b>".$FormatNumber[$RS["valor"]][2]." </b></td></tr>";
    } 

    DB_GetPersonData($RS1,$w_cliente,$RS["Solicitante"],null,null);
    if ($P4==1)
    {

      $w_html=$w_html."\r\n"."         <tr><td valign=\"top\"><font size=\"1\">Responsável monitoramento:<br><b>".$RS["nm_sol"]."</b></td>";
      $w_html=$w_html."\r\n"."             <td valign=\"top\"><font size=\"1\">E-mail:<br><b>".$RS1["email"]."</b></td></tr>";
    }
      else
    {

      $w_html=$w_html."\r\n"."         <tr><td valign=\"top\"><font size=\"1\">Responsável monitoramento:<br><b>".ExibePessoa("../",$w_cliente,$RS["solicitante"],$TP,$RS["nm_sol"])."</b></td>";
      $w_html=$w_html."\r\n"."             <td valign=\"top\"><font size=\"1\">E-mail:<br><b><A class=\"HL\" HREF=\"mailto:".$RS1["email"]."\">".$RS1["email"]."</a></b></td>";
    } 

    $w_html=$w_html."\r\n"."                <td valign=\"top\"><font size=\"1\">Telefone:<br><b>".Nvl($RS1["telefone"],"---")." </b></td>";
    $w_html=$w_html."\r\n"."            </tr>";
$RS1->Close;
    $w_html=$w_html."\r\n"."         </table></td></tr>";

    if ($w_tipo_visao==0 || $w_tipo_visao==1)
    {

// Metas da ação

// Recupera todos os registros para a listagem     

      DB_GetSolicEtapa($RS1,$w_chave,null,"LSTNULL",null);
$RS1->Sort="ordem";
      if (!$RS1->EOF)
      {
// Se não foram selecionados registros, exibe mensagem

        $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"left\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"2\"><b>&nbsp;Metas Cadastradas</td></tr>";
        $w_html=$w_html."\r\n"."      <tr><td align=\"center\" colspan=\"2\">";
        $w_html=$w_html."\r\n"."       <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
        while(!$RS1->EOF)
        {

          $w_html=$w_html."\r\n"."         <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
          $w_html=$w_html."\r\n"."           <td rowspan=\"2\"><font size=\"1\"><b>Produto</font></td>";
          $w_html=$w_html."\r\n"."           <td rowspan=\"2\"><font size=\"1\"><b>Unidade medida</font></td>";
          $w_html=$w_html."\r\n"."           <td rowspan=\"2\"><font size=\"1\"><b>LOA</font></td>";
          $w_html=$w_html."\r\n"."           <td rowspan=\"2\"><font size=\"1\"><b>Cumulativa</font></td>";
          $w_html=$w_html."\r\n"."           <td rowspan=\"2\"><font size=\"1\"><b>Será cumprida</font></td>";
          $w_html=$w_html."\r\n"."           <td rowspan=\"1\" colspan=\"3\"><font size=\"1\"><b>Quantitativo</font></td>";
          $w_html=$w_html."\r\n"."         </tr>";
          $w_html=$w_html."\r\n"."         <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
          $w_html=$w_html."\r\n"."           <td><font size=\"1\"><b>Programado</font></td>";
          $w_html=$w_html."\r\n"."           <td><font size=\"1\"><b>Realizado</font></td>";
          $w_html=$w_html."\r\n"."           <td><font size=\"1\"><b>% Realizado</font></td>";
          $w_html=$w_html."\r\n"."         </tr>";
          $w_html=$w_html."\r\n"."         <tr bgcolor=\"".$conTrAlternateBgColor."\" valign=\"top\">";
          $w_html=$w_html."\r\n"."           <td nowrap><font size=\"1\">";
          if (($RS1["fim_previsto"]<time()()) && ($cDbl[Nvl($RS1["perc_conclusao"],0)]<100))
          {

            $w_html=$w_html."\r\n"."           <img src=\"".$conImgAtraso."\" border=0 width=15 height=15 align=\"center\">";
          }
            else
          if ($cDbl[Nvl($RS1["perc_conclusao"],0)]<100)
          {

            $w_html=$w_html."\r\n"."           <img src=\"".$conImgNormal."\" border=0 width=15 height=15 align=\"center\">";
          }
            else
          {

            $w_html=$w_html."\r\n"."           <img src=\"".$conImgOkNormal."\" border=0 width=15 height=15 align=\"center\">";
          } 

          if ($cDbl[$P4]==1)
          {

            $w_html=$w_html."\r\n".$RS1["titulo"]."</td>";
          }
            else
          {

            $w_html=$w_html."\r\n"."<A class=\"HL\" HREF=\"#\" onClick=\"window.open('Projeto.asp?par=AtualizaEtapa&O=V&w_chave=".$RS1["sq_siw_solicitacao"]."&w_chave_aux=".$RS1["sq_projeto_etapa"]."&w_tipo=Volta&P1=10&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."','Meta','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;\" title=\"Clique para exibir os dados!\">".$RS1["titulo"]."</A></td>";
          } 

          $w_html=$w_html."\r\n"."          <td><font size=\"1\">".Nvl($RS1["unidade_medida"],"---")."</font></td>";
          $w_html=$w_html."\r\n"."          <td align=\"center\"><font size=\"1\">".Nvl($RS1["nm_programada"],"---")."</font></td>";
          $w_html=$w_html."\r\n"."          <td align=\"center\"><font size=\"1\">".Nvl($RS1["nm_cumulativa"],"---")."</font></td>";
          $w_html=$w_html."\r\n"."          <td align=\"center\"><font size=\"1\">".Nvl($RS1["nm_exequivel"],"---")."</font></td>";
          $w_html=$w_html."\r\n"."          <td align=\"right\"><font size=\"1\">".$cDbl[Nvl($RS1["quantidade"],0)]."</font></td>";
          $w_html=$w_html."\r\n"."          <td align=\"right\"><font size=\"1\">".($cDbl[Nvl($RS1["quantidade"],0)]*$cDbl[Nvl($RS1["perc_conclusao"],0)])/100."</font></td>";
          $w_html=$w_html."\r\n"."          <td align=\"right\"><font size=\"1\">".$cDbl[Nvl($RS1["perc_conclusao"],0)]."</font></td></tr>";
          $w_html=$w_html."\r\n"."      <tr><td colspan=\"8\"><font size=\"1\"><DD>Especifição do produto: <b>".Nvl($RS1["descricao"],"---")."</DD></font></td></tr>";
          $w_html=$w_html."\r\n"."      <tr><td colspan=\"8\"><font size=\"1\"><DD>Situação atual: <b>".Nvl($RS1["situacao_atual"],"---")."</DD></font></td></tr>";
          if ($RS1["exequivel"]=="N")
          {

            $w_html=$w_html."\r\n"."      <tr><td colspan=\"8\"><font size=\"1\"><DD>Quais os motivos para o não cumprimento da meta? <b>".Nvl($RS1["justificativa_inexequivel"],"---")."</DD></font></td></tr>";
            $w_html=$w_html."\r\n"."      <tr><td colspan=\"8\"><font size=\"1\"><DD>Quais as medidas necessárias para o cumprimento da meta? <b>".Nvl($RS1["outras_medidas"],"---")."</DD></font></td></tr>";
          } 

$RS1->MoveNext;
        } 
        $w_html=$w_html."\r\n"."         </table></td></tr>";
      } 

$RS1->Close;
    } 

    if ($w_tipo_visao==0)
    {

//Financiamento

      DB_GetFinancAcaoPPA($RS1,$w_chave,RetornaCliente(),null);
      if ($RS["cd_ppa"]>"")
      {

        $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"left\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"2\"><b>&nbsp;Financiamento</b>";
        DB_GetOrImport($RS2,null,$w_cliente,null,null,null,null,null);
$RS2->Sort="data_arquivo desc";
        $w_html=$w_html."\r\n"."          <font size=\"2\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fonte: SIAFI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Atualização: ".Nvl(FormataDataEdicao($RS2["data_arquivo"]),"-")."</td></tr>";
$RS2->Close;
        $w_html=$w_html."\r\n"."      <tr><td align=\"center\" colspan=\"2\">";
        $w_html=$w_html."\r\n"."        <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
        $w_html=$w_html."\r\n"."          <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
        $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Cód. Prog.</font></td>";
        $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Cód. Ação</font></td>";
        $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Aprovado</font></td>";
        $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Empenhado</font></td>";
        $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Saldo</font></td>";
        $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Liquidado</font></td>";
        $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>A Liquidar</font></td>";
        $w_html=$w_html."\r\n"."          </tr>";
        $w_html=$w_html."\r\n"."      <tr valign=\"top\">";
        $w_html=$w_html."\r\n"."        <td><font size=\"1\">".$RS["cd_ppa_pai"]."</td>";
        $w_html=$w_html."\r\n"."        <td><font size=\"1\">".$RS["cd_ppa"]."</td>";
        $w_html=$w_html."\r\n"."        <td align=\"right\"><font size=\"1\">".$FormatNumber[$RS["aprovado"]][2]."</td>";
        $w_html=$w_html."\r\n"."        <td align=\"right\"><font size=\"1\">".$FormatNumber[$RS["empenhado"]][2]."</td>";
        $w_html=$w_html."\r\n"."        <td align=\"right\"><font size=\"1\">".$FormatNumber[$cDbl[Nvl($RS["aprovado"],0.00)]-$cDbl[Nvl($RS["empenhado"],0.00)]][2]."</td>";
        $w_html=$w_html."\r\n"."        <td align=\"right\"><font size=\"1\">".$FormatNumber[$RS["liquidado"]][2]."</td>";
        $w_html=$w_html."\r\n"."        <td align=\"right\"><font size=\"1\">".$FormatNumber[($cDbl[Nvl($RS["empenhado"],0.00)]-$cDbl[Nvl($RS["liquidado"],0.00)])][2]."</td>";
        $w_html=$w_html."\r\n"."      </tr>";
        if (!$RS1->EOF)
        {

          while(!$RS1->EOF)
          {

            $w_html=$w_html."\r\n"."      <tr valign=\"top\">";
            $w_html=$w_html."\r\n"."        <td><font size=\"1\">".$RS1["cd_ppa_pai"]."</td>";
            $w_html=$w_html."\r\n"."        <td><font size=\"1\">".$RS1["cd_ppa"]."</td>";
            $w_html=$w_html."\r\n"."        <td align=\"right\"><font size=\"1\">".$FormatNumber[$RS1["aprovado"]][2]."</td>";
            $w_html=$w_html."\r\n"."        <td align=\"right\"><font size=\"1\">".$FormatNumber[$RS1["empenhado"]][2]."</td>";
            $w_html=$w_html."\r\n"."        <td align=\"right\"><font size=\"1\">".$FormatNumber[$cDbl[Nvl($RS1["aprovado"],0.00)]-$cDbl[Nvl($RS1["empenhado"],0.00)]][2]."</td>";
            $w_html=$w_html."\r\n"."        <td align=\"right\"><font size=\"1\">".$FormatNumber[$RS1["liquidado"]][2]."</td>";
            $w_html=$w_html."\r\n"."        <td align=\"right\"><font size=\"1\">".$FormatNumber[($cDbl[Nvl($RS1["empenhado"],0.00)]-$cDbl[Nvl($RS1["liquidado"],0.00)])][2]."</td>";
            $w_html=$w_html."\r\n"."      </tr>";
            $w_html=$w_html."\r\n"."      <tr valign=\"top\" bgcolor=\"".$conTrAlternateBgColor."\">";
            $w_html=$w_html."\r\n"."        <td colspan=7><DD><font size=\"1\"><b>Observação:</b> ".Nvl($RS1["observacao"],"---")."</DD></td>";
            $w_html=$w_html."\r\n"."      </tr>";
$RS1->MoveNext;
          } 
        } 

        $w_html=$w_html."\r\n"."         </table></td></tr>";
      }
        else
      if (!$RS1->EOF)
      {

        $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"left\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"2\"><b>&nbsp;Financiamento</b>";
        DB_GetOrImport($RS2,null,$w_cliente,null,null,null,null,null);
$RS2->Sort="data_arquivo desc";
        $w_html=$w_html."\r\n"."          <font size=\"2\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fonte: SIAFI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Atualização: ".Nvl(FormataDataEdicao($RS2["data_arquivo"]),"-")."</td></tr>";
$RS2->Close;
        $w_html=$w_html."\r\n"."      <tr><td align=\"center\" colspan=\"2\">";
        $w_html=$w_html."\r\n"."        <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
        $w_html=$w_html."\r\n"."          <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
        $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Cód. Prog.</font></td>";
        $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Cód. Ação</font></td>";
        $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Aprovado</font></td>";
        $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Empenhado</font></td>";
        $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Saldo</font></td>";
        $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Liquidado</font></td>";
        $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>A Liquidar</font></td>";
        $w_html=$w_html."\r\n"."          </tr>";
        while(!$RS1->EOF)
        {

          $w_html=$w_html."\r\n"."      <tr valign=\"top\">";
          $w_html=$w_html."\r\n"."        <td><font size=\"1\">".$RS1["cd_ppa_pai"]."</td>";
          $w_html=$w_html."\r\n"."        <td><font size=\"1\">".$RS1["cd_ppa"]."</td>";
          $w_html=$w_html."\r\n"."        <td align=\"right\"><font size=\"1\">".$FormatNumber[$RS1["aprovado"]][2]."</td>";
          $w_html=$w_html."\r\n"."        <td align=\"right\"><font size=\"1\">".$FormatNumber[$RS1["empenhado"]][2]."</td>";
          $w_html=$w_html."\r\n"."        <td align=\"right\"><font size=\"1\">".$FormatNumber[$cDbl[Nvl($RS1["aprovado"],0.00)]-$cDbl[Nvl($RS1["empenhado"],0.00)]][2]."</td>";
          $w_html=$w_html."\r\n"."        <td align=\"right\"><font size=\"1\">".$FormatNumber[$RS1["liquidado"]][2]."</td>";
          $w_html=$w_html."\r\n"."        <td align=\"right\"><font size=\"1\">".$FormatNumber[($cDbl[Nvl($RS1["empenhado"],0.00)]-$cDbl[Nvl($RS1["liquidado"],0.00)])][2]."</td>";
          $w_html=$w_html."\r\n"."      </tr>";
          $w_html=$w_html."\r\n"."      <tr valign=\"top\" bgcolor=\"".$conTrAlternateBgColor."\">";
          $w_html=$w_html."\r\n"."        <td colspan=7><DD><font size=\"1\"><b>Observação:</b> ".Nvl($RS1["observacao"],"---")."</DD></td>";
          $w_html=$w_html."\r\n"."      </tr>";
$RS1->MoveNext;
        } 
$RS1->close;
        $w_html=$w_html."\r\n"."         </table></td></tr>";
      } 


// Listagem das tarefas na visualização da ação, rotina adquirida apartir da rotina exitente na ProjetoAtiv.asp para listagem das tarefas

      DB_GetLinkData($RS,RetornaCliente(),"ORPCAD");
      DB_GetSolicList($rs,$RS["sq_menu"],RetornaUsuario(),"ORPCAD",5,
      null,null,null,null,null,null,
      null,null,null,null);
$RS->sort="ordem, fim, prioridade";
      if (!$RS->EOF)
      {

        $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"left\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"2\"><b>&nbsp;Tarefas Cadastradas</td></tr>";
        $w_html=$w_html."\r\n"."      <tr><td align=\"center\" colspan=\"2\">";
        $w_html=$w_html."\r\n"."        <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
        $w_html=$w_html."\r\n"."          <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
        $w_html=$w_html."\r\n"."            <td nowrap><font size=\"1\"><b>Nº</font></td>";
        $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Detalhamento</font></td>";
        $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Responsável</font></td>";
        $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Parcerias</font></td>";
        $w_html=$w_html."\r\n"."            <td nowrap><font size=\"1\"><b>Fim<br>previsto</font></td>";
        $w_html=$w_html."\r\n"."            <td nowrap><font size=\"1\"><b>Programado<br>R$ 1,00</font></td>";
        $w_html=$w_html."\r\n"."            <td nowrap><font size=\"1\"><b>Executado<br>R$ 1,00</font></td>";
        $w_html=$w_html."\r\n"."            <td nowrap><font size=\"1\"><b>Fase atual</font></td>";
        $w_html=$w_html."\r\n"."            <td nowrap><font size=\"1\"><b>Prioridade</font></td>";
        $w_html=$w_html."\r\n"."          </tr>";
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
        $w_html=$w_html."\r\n"."       <tr bgcolor=\"".$w_cor."\" valign=\"top\">";
        $w_html=$w_html."\r\n"."         <td nowrap><font size=\"1\">";
        if ($RS["concluida"]=="N")
        {

          if ($RS["fim"]<time()())
          {

            $w_html=$w_html."\r\n"."          <img src=\"".$conImgAtraso."\" border=0 width=15 heigth=15 align=\"center\">";
          }
            else
          if ($RS["aviso_prox_conc"]=="S" && ($RS["aviso"]<=time()()))
          {

            $w_html=$w_html."\r\n"."          <img src=\"".$conImgAviso."\" border=0 width=15 height=15 align=\"center\">";
          }
            else
          {

            $w_html=$w_html."\r\n"."          <img src=\"".$conImgNormal."\" border=0 width=15 height=15 align=\"center\">";
          } 

        }
          else
        {

          if ($RS["fim"]<Nvl($RS["fim_real"],$RS["fim"]))
          {

            $w_html=$w_html."\r\n"."          <img src=\"".$conImgOkAtraso."\" border=0 width=15 heigth=15 align=\"center\">";
          }
            else
          {

            $w_html=$w_html."\r\n"."          <img src=\"".$conImgOkNormal."\" border=0 width=15 height=15 align=\"center\">";
          } 

        } 

        if ($P4==1)
        {

          $w_html=$w_html."\r\n".$RS["sq_siw_solicitacao"]."</td>";
        }
          else
        {

          $w_html=$w_html."\r\n"."         <A class=\"HL\" HREF=\"".$w_dir."Projetoativ.asp?par=Visual&R=".$w_pagina.$par."&O=L&w_chave=".$RS["sq_siw_solicitacao"]."&w_tipo=&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Exibe as informações deste registro.\" target=\"blank\">".$RS["sq_siw_solicitacao"]."&nbsp;</a></td>";
        } 

//If Len(Nvl(RS("assunto"),"-")) > 80 Then w_titulo = Mid(Nvl(RS("assunto"),"-"),1,50) & "..." Else w_titulo = Nvl(RS("assunto"),"-") End If

        if ($RS["sg_tramite"]=="CA")
        {

          $w_html=$w_html."\r\n"."      <td><font size=\"1\"><strike>".Nvl($RS["assunto"],"-")."</strike></td>";
        }
          else
        {

          $w_html=$w_html."\r\n"."      <td><font size=\"1\">".Nvl($RS["assunto"],"-")."</td>";
        } 

        $w_html=$w_html."\r\n"."         <td><font size=\"1\">".Nvl($RS["palavra_chave"],"---")."</td>";
        $w_html=$w_html."\r\n"."         <td><font size=\"1\">".Nvl($RS["proponente"],"---")."</td>";
        $w_html=$w_html."\r\n"."         <td align=\"center\"><font size=\"1\">&nbsp;".Nvl($FormatDateTime[$RS["fim"]][2],"-")."</td>";
        $w_html=$w_html."\r\n"."         <td align=\"right\" nowrap><font size=\"1\">".$FormatNumber[$cDbl[Nvl($RS["valor"],0)]][2]."</td>";
        $w_html=$w_html."\r\n"."         <td align=\"right\" nowrap><font size=\"1\">".$FormatNumber[$cDbl[Nvl($RS["custo_real"],0)]][2]."</td>";
        $w_html=$w_html."\r\n"."         <td nowrap><font size=\"1\">".$RS["nm_tramite"]."</td>";
        $w_html=$w_html."\r\n"."         <td nowrap><font size=\"1\">".RetornaPrioridade($RS["prioridade"])."</td></tr>";
$RS->MoveNext;
      } 
      $w_html=$w_html."\r\n"."         </table></td></tr>";
    } 

    DesconectaBD();
  } 

  $w_html=$w_html."\r\n"."</table>";
  $w_html=$w_html."\r\n"."</center>";
  $w_html=$w_html."\r\n"."</div>";
}
  else
{

  if ($O=="L" || $O=="V")
  {
// Se for listagem dos dados

    $w_html=$w_html."\r\n"."<div align=center><center>";
    $w_html=$w_html."\r\n"."<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
    $w_html=$w_html."\r\n"."<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">";

    $w_html=$w_html."\r\n"."    <table width=\"99%\" border=\"0\">";
    $w_html=$w_html."\r\n"."      <tr><td><font size=2>Ação: <b>".$RS["titulo"]."</b></font></td></tr>";

// Identificação da ação

    $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Identificação</td>";

// Se a classificação foi informada, exibe.

    if (!!isset($RS["sq_cc"]))
    {

      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Classificação:<br><b>".$RS["cc_nome"]." </b></td>";
    } 


// Se a iniciativa prioritária for informada, exibe.

    if (!!isset($RS["sq_orprioridade"]))
    {

      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Iniciativa prioritária:<br><b>".$RS["nm_pri"];
      if (!!isset($RS["cd_pri"]))
      {

        $w_html=$w_html."\r\n"." (".$RS["cd_pri"].")";
      } 

      if (!!isset($RS["resp_pri"]))
      {

        $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>";
        $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Responsável iniciativa prioritária:<br><b>".$RS["resp_pri"]." </b></td>";
        if (!!isset($RS["fone_pri"]))
        {

          $w_html=$w_html."\r\n"."          <td><font size=\"1\">Telefone:<br><b>".$RS["fone_pri"]." </b></td>";
        } 

        if (!!isset($RS["mail_ppa_pai"]))
        {

          $w_html=$w_html."\r\n"."          <td><font size=\"1\">Email:<br><b>".$RS["mail_pri"]." </b></td>";
        } 

        $w_html=$w_html."\r\n"."          </table>";
      } 

      $w_html=$w_html."\r\n"."          </b></td>";
    } 


// Se a ação no PPA for informada, exibe.

    if (!!isset($RS["sq_acao_ppa"]))
    {

      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Programa PPA:<br><b>".$RS["nm_ppa_pai"]." (".$RS["cd_ppa_pai"].")"." </b></td>";
      if (!!isset($RS["resp_ppa_pai"]))
      {

        $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>";
        $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Gerente executivo:<br><b>".$RS["resp_ppa_pai"]." </b></td>";
        if (!!isset($RS["fone_ppa_pai"]))
        {

          $w_html=$w_html."\r\n"."          <td><font size=\"1\">Telefone:<br><b>".$RS["fone_ppa_pai"]." </b></td>";
        } 

        if (!!isset($RS["mail_ppa_pai"]))
        {

          $w_html=$w_html."\r\n"."          <td><font size=\"1\">Email:<br><b>".$RS["mail_ppa_pai"]." </b></td>";
        } 

        $w_html=$w_html."\r\n"."          </table>";
      } 

      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Ação PPA:<br><b>".$RS["nm_ppa"]." (".$RS["cd_ppa"].")"." </b></td>";
      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>";
      $w_html=$w_html."\r\n"."          <tr valign=\"top\">";
      if ($RS["mpog_ppa"]=="S")
      {

        $w_html=$w_html."\r\n"."          <td><font size=\"1\">Selecionada MP:<br><b>Sim</b></td>";
      }
        else
      {

        $w_html=$w_html."\r\n"."          <td><font size=\"1\">Selecionada MP:<br><b>Não</b></td>";
      } 

      if ($RS["relev_ppa"]=="S")
      {

        $w_html=$w_html."\r\n"."          <td><font size=\"1\">Selecionada SE/MS:<br><b>Sim</b></td>";
      }
        else
      {

        $w_html=$w_html."\r\n"."          <td><font size=\"1\">Selecionada SE/MS:<br><b>Não</b></td>";
      } 

      $w_html=$w_html."\r\n"."          </table>";
      if (!!isset($RS["resp_ppa"]))
      {

        $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>";
        $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Coordenador:<br><b>".$RS["resp_ppa"]." </b></td>";
        if (!!isset($RS["fone_ppa"]))
        {

          $w_html=$w_html."\r\n"."          <td><font size=\"1\">Telefone:<br><b>".$RS["fone_ppa"]." </b></td>";
        } 

        if (!!isset($RS["mail_ppa"]))
        {

          $w_html=$w_html."\r\n"."          <td><font size=\"1\">Email:<br><b>".$RS["mail_ppa"]." </b></td>";
        } 

        $w_html=$w_html."\r\n"."          </table>";
      } 

    } 

    $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>";
//w_html = w_html & VbCrLf & "          <tr valign=""top"">"

//w_html = w_html & VbCrLf & "          <td colspan=3><font size=""1"">Abrangência da ação:(Quando Brasília-DF, impacto nacional. Quando a capital de um estado, impacto estadual.)<br><b>" & RS("nm_cidade") & " (" & RS("co_uf") & ")</b></td>"

    $w_html=$w_html."\r\n"."          <tr valign=\"top\">";
    $w_html=$w_html."\r\n"."          <td><font size=\"1\">Responsável monitoramento:<br><b>".ExibePessoa("../",$w_cliente,$RS["solicitante"],$TP,$RS["nm_sol"])."</b></td>";
    $w_html=$w_html."\r\n"."          <td><font size=\"1\">Setor responsável monitoramento:<br><b>".$RS["nm_unidade_resp"]." </b></td>";
    if ($w_tipo_visao==0)
    {
// Se for visão completa

      $w_html=$w_html."\r\n"."          <td><font size=\"1\">Recurso programado:<br><b>".$FormatNumber[$RS["valor"]][2]." </b></td>";
    } 

    $w_html=$w_html."\r\n"."          <tr valign=\"top\">";
    $w_html=$w_html."\r\n"."          <td><font size=\"1\">Início previsto:<br><b>".FormataDataEdicao($RS["inicio"])." </b></td>";
    $w_html=$w_html."\r\n"."          <td><font size=\"1\">Fim previsto:<br><b>".FormataDataEdicao($RS["fim"])." </b></td>";
//w_html = w_html & VbCrLf & "          <td><font size=""1"">Prioridade:<br><b>" & RetornaPrioridade(RS("prioridade")) & " </b></td>"

    $w_html=$w_html."\r\n"."          <tr valign=\"top\"><td><font size=\"1\">Parcerias externas:<br><b>".CRLF2BR(Nvl($RS["proponente"],"---"))." </b></td>";
    $w_html=$w_html."\r\n"."          <tr valign=\"top\"><td><font size=\"1\">Parcerias internas:<br><b>".CRLF2BR(Nvl($RS["palavra_chave"],"---"))." </b></td>";
    $w_html=$w_html."\r\n"."          </table>";

    if ($w_tipo_visao==0 || $w_tipo_visao==1)
    {

// Informações adicionais

      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Informações adicionais</td>";
      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Situação problema:<br><b>".CRLF2BR(Nvl($RS["problema"],"---"))." </b></td>";
      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Objetivo da ação:<br><b>".CRLF2BR(Nvl($RS["objetivo"],"---"))." </b></td>";
      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Descrição da ação:<br><b>".CRLF2BR(Nvl($RS["ds_acao"],"---"))." </b></td>";
      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Público alvo:<br><b>".CRLF2BR(Nvl($RS["publico_alvo"],"---"))." </b></td>";
      if (Nvl($RS["descricao"],"")>"")
      {

        $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Resultados da ação:<br><b>".CRLF2BR($RS["descricao"])." </b></td>";
      } 

      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Estratégia de implantação:<br><b>".CRLF2BR(Nvl($RS["estrategia"],"---"))." </b></td>";
      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Indicadores de desempenho:<br><b>".CRLF2BR(Nvl($RS["indicadores"],"---"))." </b></td>";
      if ($w_tipo_visao==0 && Nvl($RS["justificativa"],"")>"")
      {
// Se for visão completa

        $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Observações:<br><b>".CRLF2BR($RS["justificativa"])." </b></td>";
      } 

    } 


    if ($w_tipo_visao==0 || $w_tipo_visao==1)
    {

// Outras iniciativas

      DB_GetOrPrioridadeList($RS1,$w_chave,RetornaCliente(),null);
$RS1->Sort="Existe desc";
      if (!$RS1->EOF)
      {

        if ($cDbl[Nvl($RS1["Existe"],0)]>0)
        {

          $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Outras iniciativas</td>";
          $w_html=$w_html."\r\n"."      <tr><td align=\"center\" colspan=\"2\">";
          $w_html=$w_html."\r\n"."        <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
          $w_html=$w_html."\r\n"."          <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
          $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Nome</font></td>";
          $w_html=$w_html."\r\n"."          </tr>";
          while(!$RS1->EOF)
          {

            if ($cDbl[Nvl($RS1["Existe"],0)]>0)
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
            $w_html=$w_html."\r\n"."        <td><font size=\"1\"><ul><li>".$RS1["nome"]."</td>";
            $w_html=$w_html."\r\n"."      </tr>";
          } 

$RS1->MoveNext;
        } 
        $w_html=$w_html."\r\n"."         </table></td></tr>";
      } 

    } 

$RS1->close;
  } 


// Dados da conclusão da ação, se ela estiver nessa situação

  if ($RS["concluida"]=="S" && Nvl($RS["data_conclusao"],"")>"")
  {

    $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Dados da conclusão</td>";
    $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>";
    $w_html=$w_html."\r\n"."          <tr valign=\"top\">";
    $w_html=$w_html."\r\n"."          <td><font size=\"1\">Início da execução:<br><b>".FormataDataEdicao($RS["inicio_real"])." </b></td>";
    $w_html=$w_html."\r\n"."          <td><font size=\"1\">Término da execução:<br><b>".FormataDataEdicao($RS["fim_real"])." </b></td>";
    if ($w_tipo_visao==0)
    {

      $w_html=$w_html."\r\n"."          <td><font size=\"1\">Recurso executado:<br><b>".$FormatNumber[$RS["custo_real"]][2]." </b></td>";
    } 

    $w_html=$w_html."\r\n"."          </table>";
    if ($w_tipo_visao==0)
    {

      $w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Nota de conclusão:<br><b>".CRLF2BR($RS["nota_conclusao"])." </b></td>";
    } 

  } 

} 


if ($w_tipo_visao==0)
{

//Financiamento

  DB_GetFinancAcaoPPA($RS1,$w_chave,RetornaCliente(),null);
  if ($RS["cd_ppa"]>"")
  {

    $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Financiamento</td>";
    $w_html=$w_html."\r\n"."      <tr><td align=\"center\" colspan=\"2\">";
    $w_html=$w_html."\r\n"."        <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
    $w_html=$w_html."\r\n"."          <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
    $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Código</font></td>";
    $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Nome</font></td>";
    $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Dotação autorizada</font></td>";
    $w_html=$w_html."\r\n"."          </tr>";
    $w_html=$w_html."\r\n"."      <tr valign=\"top\">";
    $w_html=$w_html."\r\n"."        <td><font size=\"1\">".$RS["cd_ppa_pai"].".".$RS["cd_ppa"]."</td>";
    $w_html=$w_html."\r\n"."        <td><font size=\"1\">".$RS["nm_ppa"]."</td>";
    $w_html=$w_html."\r\n"."        <td align=\"right\"><font size=\"1\">".$FormatNumber[$RS["aprovado"]][2]."</td>";
    $w_html=$w_html."\r\n"."      </tr>";
    if (!$RS1->EOF)
    {

      while(!$RS1->EOF)
      {

        $w_html=$w_html."\r\n"."      <tr valign=\"top\">";
        $w_html=$w_html."\r\n"."        <td><font size=\"1\">".$RS1["cd_ppa_pai"].".".$RS1["cd_ppa"]."</td>";
        $w_html=$w_html."\r\n"."        <td><font size=\"1\">".$RS1["nome"]."</td>";
        $w_html=$w_html."\r\n"."        <td align=\"right\"><font size=\"1\">".$FormatNumber[$RS1["aprovado"]][2]."</td>";
        $w_html=$w_html."\r\n"."      </tr>";
        $w_html=$w_html."\r\n"."      <tr valign=\"top\" bgcolor=\"".$conTrAlternateBgColor."\">";
        $w_html=$w_html."\r\n"."        <td colspan=3><DD><font size=\"1\"><b>Observação:</b> ".Nvl($RS1["observacao"],"---")."</DD></td>";
        $w_html=$w_html."\r\n"."      </tr>";
$RS1->MoveNext;
      } 
    } 

    $w_html=$w_html."\r\n"."         </table></td></tr>";
  }
    else
  if (!$RS1->EOF)
  {

    $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Financiamento</td>";
    $w_html=$w_html."\r\n"."      <tr><td align=\"center\" colspan=\"2\">";
    $w_html=$w_html."\r\n"."        <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
    $w_html=$w_html."\r\n"."          <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
    $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Código</font></td>";
    $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Nome</font></td>";
    $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Aprovado</font></td>";
    $w_html=$w_html."\r\n"."          </tr>";
    while(!$RS1->EOF)
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
    $w_html=$w_html."\r\n"."        <td><font size=\"1\">".$RS1["cd_ppa_pai"].".".$RS1["cd_ppa"]."</td>";
    $w_html=$w_html."\r\n"."        <td><font size=\"1\">".$RS1["nome"]."</td>";
    $w_html=$w_html."\r\n"."        <td align=\"right\"><font size=\"1\">".$FormatNumber[$RS1["aprovado"]][2]."</td>";
    $w_html=$w_html."\r\n"."      </tr>";
    $w_html=$w_html."\r\n"."      <tr valign=\"top\" bgcolor=\"".$w_cor."\">";
    $w_html=$w_html."\r\n"."        <td colspan=3><DD><font size=\"1\"><b>Observação:</b> ".Nvl($RS1["observacao"],"---")."</DD></td>";
    $w_html=$w_html."\r\n"."      </tr>";
$RS1->MoveNext;
  } 
$RS1->close;
  $w_html=$w_html."\r\n"."         </table></td></tr>";
} 

} 

// Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário

if ($O=="L" && $w_tipo_visao!=2)
{

if ($RS["aviso_prox_conc"]=="S")
{

// Configuração dos alertas de proximidade da data limite para conclusão da demanda

  $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Alerta</td>";
  $w_html=$w_html."\r\n"."      <tr><td align=\"center\" height=\"1\" bgcolor=\"#000000\"></td></tr>";
  $w_html=$w_html."\r\n"."      <tr><td><font size=1>Será enviado aviso a partir de <b>".$RS["dias_aviso"]."</b> dias antes de <b>".FormataDataEdicao($RS["fim"])."</b></font></td></tr>";
//w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100""" cellspacing=0>"

//w_html = w_html & VbCrLf & "          <td valign=""top""><font size=""1"">Emite aviso:<br><b>" & Replace(Replace(RS("aviso_prox_conc"),"S","Sim"),"N","Não") & " </b></td>"

//w_html = w_html & VbCrLf & "          <td valign=""top""><font size=""1"">Dias:<br><b>" & RS("dias_aviso") & " </b></td>"

//w_html = w_html & VbCrLf & "          </table>"

} 


// Interessados na execução da ação

DB_GetSolicInter($RS,$w_chave,null,"LISTA");
$RS->Sort="nome_resumido";
if (!$Rs->EOF)
{

  $TP=RemoveTP($TP)." - Interessados";
  $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Interessados na execução</b></center></td>";
  $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\"><font size=\"1\"><b><center><B><font size=1>Clique <a class=\"HL\" HREF=\"".$w_dir."Projeto.asp?par=interess&R=".$w_Pagina.$par."&O=L&w_chave=".$w_chave."&P1=4&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\" target=\"blank\">aqui</a> para visualizar os Interessados na execução</font></b></center></td>";
//w_html = w_html & VbCrLf & "      <tr><td align=""center"" colspan=""2"">"

//w_html = w_html & VbCrLf & "        <TABLE WIDTH=""100""" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"

//w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"

//w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Nome</font></td>"

//w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Tipo de visão</font></td>"

//w_html = w_html & VbCrLf & "            <td><font size=""1""><b>Envia e-mail</font></td>"

//w_html = w_html & VbCrLf & "          </tr>"    

//While Not Rs.EOF

//  If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If

//  w_html = w_html & VbCrLf & "      <tr valign=""top"" bgcolor=""" & w_cor & """>"

//  w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RS("nome_resumido") & "</td>"

//  w_html = w_html & VbCrLf & "        <td><font size=""1"">" & RetornaTipoVisao(RS("tipo_visao")) & "</td>"

//  w_html = w_html & VbCrLf & "        <td align=""center""><font size=""1"">" & Replace(Replace(RS("envia_email"),"S","Sim"),"N","Não") & "</td>"

//  w_html = w_html & VbCrLf & "      </tr>"

//  Rs.MoveNext

//wend

//w_html = w_html & VbCrLf & "         </table></td></tr>"

} 

DesconectaBD();

// Áreas envolvidas na execução da ação

DB_GetSolicAreas($RS,$w_chave,null,"LISTA");
$RS->Sort="nome";
if (!$Rs->EOF)
{

  $w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Áreas/Instituições envolvidas</td>";
  $w_html=$w_html."\r\n"."      <tr><td align=\"center\" colspan=\"2\">";
  $w_html=$w_html."\r\n"."        <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
  $w_html=$w_html."\r\n"."          <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
  $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Nome</font></td>";
  $w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Papel</font></td>";
  $w_html=$w_html."\r\n"."          </tr>";
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

// Etapas da ação

// Recupera todos os registros para a listagem

DB_GetSolicEtapa($RS,$w_chave,null,"LISTA",null);
$RS->Sort="ordem";

// Recupera o código da opção de menu  a ser usada para listar as atividades

$w_p2="";
while(!$RS->EOF)
{

if ($cDbl[Nvl($RS["P2"],0)]>$cDbl[0])
{

  $w_p2=$RS["P2"];
$RS->MoveLast;
} 

$RS->MoveNext;
} 
DesconectaBD();

DB_GetSolicEtapa($RS,$w_chave,null,"LSTNULL",null);
$RS->Sort="ordem";
if (!$RS->EOF)
{
// Se não foram selecionados registros, exibe mensagem


// Monta função JAVASCRIPT para fazer a chamada para a lista de atividades

if ($w_p2>"")
{

  $w_html=$w_html."\r\n"."<SCRIPT LANGUAGE=\"JAVASCRIPT\">";
  $w_html=$w_html."\r\n"."  function lista (projeto, etapa) {";
  $w_html=$w_html."\r\n"."    document.Form.p_projeto.value=projeto;";
  $w_html=$w_html."\r\n"."    document.Form.p_atividade.value=etapa;";
  $w_html=$w_html."\r\n"."    document.Form.p_agrega.value='GRDMETAPA';";
  DB_GetTramiteList($RS1,$w_P2,null,null);
$RS1->Sort="ordem";
  $w_html=$w_html."\r\n"."    document.Form.p_fase.value='';";
  $w_fases="";
  while(!$RS1->EOF)
  {

    if ($RS1["sigla"]!="CA")
    {

      $w_fases=$w_fases.",".$RS1["sq_siw_tramite"];
    } 

$RS1->MoveNext;
  } 
  $w_html=$w_html."\r\n"."    document.Form.p_fase.value='".substr($w_fases,1,100)."';";
  $w_html=$w_html."\r\n"."    document.Form.submit();";
  $w_html=$w_html."\r\n"."  }";
  $w_html=$w_html."\r\n"."</SCRIPT>";
  DB_GetMenuData($RS1,$w_p2);
  AbreForm("Form",$RS1["link"],"POST","return(Validacao(this));","Atividades",3,$w_P2,1,null,$w_TP,$RS1["sigla"],$w_pagina.$par,"L");
  $w_html=$w_html."\r\n".MontaFiltro("POST");
  $w_html=$w_html."\r\n"."<input type=\"Hidden\" name=\"p_projeto\" value=\"\">";
  $w_html=$w_html."\r\n"."<input type=\"Hidden\" name=\"p_atividade\" value=\"\">";
  $w_html=$w_html."\r\n"."<input type=\"Hidden\" name=\"p_agrega\" value=\"\">";
  $w_html=$w_html."\r\n"."<input type=\"Hidden\" name=\"p_fase\" value=\"\">";
} 


$w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Metas físicas</td>";
$w_html=$w_html."\r\n"."      <tr><td align=\"center\" colspan=\"2\">";
$w_html=$w_html."\r\n"."        <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
$w_html=$w_html."\r\n"."          <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
$w_html=$w_html."\r\n"."          <td><font size=\"1\"><b>Metas</font></td>";
//w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Produto</font></td>"

//w_html = w_html & VbCrLf & "          <td rowspan=2><font size=""1""><b>Responsável</font></td>"

//w_html = w_html & VbCrLf & "          <td rowspan=2><font size=""1""><b>Setor</font></td>"

$w_html=$w_html."\r\n"."          <td><font size=\"1\"><b>Execução até</font></td>";
$w_html=$w_html."\r\n"."          <td><font size=\"1\"><b>Conc.</font></td>";
//w_html = w_html & VbCrLf & "          <td rowspan=2><font size=""1""><b>Ativ.</font></td>"

$w_html=$w_html."\r\n"."          </tr>";
//w_html = w_html & VbCrLf & "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"

//w_html = w_html & VbCrLf & "          <td><font size=""1""><b>De</font></td>"

//w_html = w_html & VbCrLf & "          <td><font size=""1""><b>Até</font></td>"

//w_html = w_html & VbCrLf & "          </tr>"

while(!$RS->EOF)
{

  $w_html=$w_html."\r\n".$EtapaLinha[$w_chave][$Rs["sq_projeto_etapa"]][$Rs["titulo"]][$RS["nm_resp"]][$RS["sg_setor"]];

// Recupera as etapas vinculadas ao nível acima

  DB_GetSolicEtapa($RS1,$w_chave,$RS["sq_projeto_etapa"],"LSTNIVEL",null);
$RS1->Sort="ordem";
  while(!$RS1->EOF)
  {

    $w_html=$w_html."\r\n".$EtapaLinha[$w_chave][$RS1["sq_projeto_etapa"]][$RS1["titulo"]][$RS1["nm_resp"]][$RS1["sg_setor"]];

// Recupera as etapas vinculadas ao nível acima

    DB_GetSolicEtapa($RS2,$w_chave,$RS1["sq_projeto_etapa"],"LSTNIVEL",null);
$RS2->Sort="ordem";
    while(!$RS2->EOF)
    {

      $w_html=$w_html."\r\n".$EtapaLinha[$w_chave][$RS2["sq_projeto_etapa"]][$RS2["titulo"]][$RS2["nm_resp"]][$RS2["sg_setor"]];

// Recupera as etapas vinculadas ao nível acima

      DB_GetSolicEtapa($RS3,$w_chave,$RS2["sq_projeto_etapa"],"LSTNIVEL",null);
$RS3->Sort="ordem";
      while(!$RS3->EOF)
      {

        $w_html=$w_html."\r\n".$EtapaLinha[$w_chave][$RS3["sq_projeto_etapa"]][$RS3["titulo"]][$RS3["nm_resp"]][$RS3["sg_setor"]];

// Recupera as etapas vinculadas ao nível acima

        DB_GetSolicEtapa($RS4,$w_chave,$RS3["sq_projeto_etapa"],"LSTNIVEL",null);
$RS4->Sort="ordem";
        while(!$RS4->EOF)
        {

          $w_html=$w_html."\r\n".$EtapaLinha[$w_chave][$RS4["sq_projeto_etapa"]][$RS4["titulo"]][$RS4["nm_resp"]][$RS4["sg_setor"]];
$RS4->MoveNext;
        } 

$RS3->MoveNext;
      } 

$RS2->MoveNext;
    } 

$RS1->MoveNext;
  } 

$RS->MoveNext;
} 
$w_html=$w_html."\r\n"."      </form>";
$w_html=$w_html."\r\n"."      </center>";
$w_html=$w_html."\r\n"."         </table></td></tr>";
} 

DesconectaBD();

// Listagem das tarefas na visualização da ação, rotina adquirida apartir da rotina exitente na ProjetoAtiv.asp para listagem das tarefas

DB_GetLinkData($RS,RetornaCliente(),"ORPCAD");
DB_GetSolicList($rs,$RS["sq_menu"],RetornaUsuario(),"ORPCAD",5,
null,null,null,null,null,null,
null,null,null,null);
$RS->sort="ordem, fim, prioridade";
if (!$RS->EOF)
{

$w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Tarefas</td>";
$w_html=$w_html."\r\n"."      <tr><td align=\"center\" colspan=\"2\">";
$w_html=$w_html."\r\n"."        <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
$w_html=$w_html."\r\n"."          <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
$w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Nº</font></td>";
$w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Responsável</font></td>";
$w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Detalhamento</font></td>";
$w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Fim previsto</font></td>";
$w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Fase atual</font></td>";
$w_html=$w_html."\r\n"."          </tr>";
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
$w_html=$w_html."\r\n"."       <tr valign=\"top\" bgcolor=\"".$w_cor."\">";
$w_html=$w_html."\r\n"."       <tr bgcolor=\"".$w_cor."\" valign=\"top\">";
$w_html=$w_html."\r\n"."         <td nowrap><font size=\"1\">";
if ($RS["concluida"]=="N")
{

  if ($RS["fim"]<time()())
  {

    $w_html=$w_html."\r\n"."          <img src=\"".$conImgAtraso."\" border=0 width=15 heigth=15 align=\"center\">";
  }
    else
  if ($RS["aviso_prox_conc"]=="S" && ($RS["aviso"]<=time()()))
  {

    $w_html=$w_html."\r\n"."          <img src=\"".$conImgAviso."\" border=0 width=15 height=15 align=\"center\">";
  }
    else
  {

    $w_html=$w_html."\r\n"."          <img src=\"".$conImgNormal."\" border=0 width=15 height=15 align=\"center\">";
  } 

}
  else
{

  if ($RS["fim"]<Nvl($RS["fim_real"],$RS["fim"]))
  {

    $w_html=$w_html."\r\n"."          <img src=\"".$conImgOkAtraso."\" border=0 width=15 heigth=15 align=\"center\">";
  }
    else
  {

    $w_html=$w_html."\r\n"."          <img src=\"".$conImgOkNormal."\" border=0 width=15 height=15 align=\"center\">";
  } 

} 

$w_html=$w_html."\r\n"."         <A class=\"HL\" HREF=\"".$w_dir."Projetoativ.asp?par=Visual&R=".$w_pagina.$par."&O=L&w_chave=".$RS["sq_siw_solicitacao"]."&w_tipo=&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Exibe as informações deste registro.\" target=\"blank\">".$RS["sq_siw_solicitacao"]."&nbsp;</a>";
$w_html=$w_html."\r\n"."         <td><font size=\"1\">".ExibePessoa("../",$w_cliente,$RS["solicitante"],$TP,$RS["nm_solic"])."</td>";
if (strlen(Nvl($RS["assunto"],"-"))>50)
{
  $w_titulo=substr(Nvl($RS["assunto"],"-"),0,50)."...";
}
  else
{
  $w_titulo=Nvl($RS["assunto"],"-");
}
;
} 
if ($RS["sg_tramite"]=="CA")
{

$w_html=$w_html."\r\n"."      <td><font size=\"1\"><strike>".$w_titulo."</strike></td>";
}
  else
{

$w_html=$w_html."\r\n"."      <td><font size=\"1\">".$w_titulo."</td>";
} 

$w_html=$w_html."\r\n"."         <td align=\"center\"><font size=\"1\">&nbsp;".Nvl($FormatDateTime[$RS["fim"]][2],"-")."</td>";
$w_html=$w_html."\r\n"."         <td nowrap><font size=\"1\">".$RS["nm_tramite"]."</td>";
$RS->MoveNext;
} 
$w_html=$w_html."\r\n"."         </table></td></tr>";
} 

DesconectaBD();

// Recursos envolvidos na execução da ação

DB_GetSolicRecurso($RS,$w_chave,null,"LISTA");
$RS->Sort="tipo, nome";
if (!$Rs->EOF)
{

$w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Recursos</td>";
$w_html=$w_html."\r\n"."      <tr><td align=\"center\" colspan=\"2\">";
$w_html=$w_html."\r\n"."        <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
$w_html=$w_html."\r\n"."          <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
$w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Tipo</font></td>";
$w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Nome</font></td>";
$w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Finalidade</font></td>";
$w_html=$w_html."\r\n"."          </tr>";
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
$w_html=$w_html."\r\n"."        <td><font size=\"1\">".RetornaTipoRecurso($RS["tipo"])."</td>";
$w_html=$w_html."\r\n"."        <td><font size=\"1\">".$RS["nome"]."</td>";
$w_html=$w_html."\r\n"."        <td><font size=\"1\">".CRLF2BR(Nvl($RS["finalidade"],"---"))."</td>";
$w_html=$w_html."\r\n"."      </tr>";
$Rs->MoveNext;
} 
} 

DesconectaBD();
$w_html=$w_html."\r\n"."         </table></td></tr>";

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
$w_html=$w_html."\r\n"."          <td><font size=\"1\"><b>Título</font></td>";
$w_html=$w_html."\r\n"."          <td><font size=\"1\"><b>Descrição</font></td>";
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
$RS->Sort="data desc";
$w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Ocorrências e Anotações</td>";
$w_html=$w_html."\r\n"."      <tr><td align=\"center\" colspan=\"2\">";
$w_html=$w_html."\r\n"."        <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">";
$w_html=$w_html."\r\n"."          <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">";
$w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Data</font></td>";
$w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Despacho/Observação</font></td>";
$w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Responsável</font></td>";
$w_html=$w_html."\r\n"."            <td><font size=\"1\"><b>Fase / Destinatário</font></td>";
$w_html=$w_html."\r\n"."          </tr>";
if ($Rs->EOF)
{

$w_html=$w_html."\r\n"."      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=6 align=\"center\"><font size=\"1\"><b>Não foram encontrados encaminhamentos.</b></td></tr>";
}
  else
{

$w_html=$w_html."\r\n"."      <tr bgcolor=\"".$conTrBgColor."\" valign=\"top\">";
$w_html=$w_html."\r\n"."        <td colspan=6><font size=\"1\">Fase atual: <b>".$RS["fase"]."</b></td>";
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
$w_html=$w_html."\r\n"."        <td><font size=\"1\">".CRLF2BR(Nvl($RS["despacho"],"---"))."</td>";
$w_html=$w_html."\r\n"."        <td nowrap><font size=\"1\">".ExibePessoa("../",$w_cliente,$RS["sq_pessoa"],$TP,$RS["responsavel"])."</td>";
if ((!!isset(Tvl($RS["sq_projeto_log"]))) && (!!isset(Tvl($RS["destinatario"]))))
{

$w_html=$w_html."\r\n"."        <td nowrap><font size=\"1\">".ExibePessoa("../",$w_cliente,$RS["sq_pessoa_destinatario"],$TP,$RS["destinatario"])."</td>";
}
  else
if ((!!isset(Tvl($RS["sq_projeto_log"]))) && !isset(Tvl($RS["destinatario"])))
{

$w_html=$w_html."\r\n"."        <td nowrap><font size=\"1\">Anotação</td>";
}
  else
{

$w_html=$w_html."\r\n"."        <td nowrap><font size=\"1\">".Nvl($RS["tramite"],"---")."</td>";
} 

$w_html=$w_html."\r\n"."      </tr>";
$Rs->MoveNext;
} 
$w_html=$w_html."\r\n"."         </table></td></tr>";
} 

DesconectaBD();

$w_html=$w_html."\r\n"."</table>";
} 

} 


$VisualProjeto=$w_html;

$w_p2=null;

$w_fases=null;

$RS2=null;

$RS3=null;

$RS4=null;


$w_tipo_visao=null;

$w_erro=null;

$Rsquery=null;

$w_ImagemPadrao=null;

$w_Imagem=null;


return $function_ret;
} 
// =========================================================================

// Fim da visualização dos dados do cliente

// -------------------------------------------------------------------------


?>


