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
  session_register("LOTACAO_session");
  session_register("LOCALIZACAO_session");
?>
<? // asp2php (vbscript) converted
?>
<? // Option $Explicit; ?>
<!-- #INCLUDE FILE="../Constants.inc" -->
<!-- #INCLUDE FILE="../DB_Geral.php" -->
<!-- #INCLUDE FILE="../DB_EO.php" -->
<!-- #INCLUDE FILE="../DB_Gerencial.php" -->
<!-- #INCLUDE FILE="../DB_Cliente.php" -->
<!-- #INCLUDE FILE="../DB_Seguranca.php" -->
<!-- #INCLUDE FILE="../DB_Link.php" -->
<!-- #INCLUDE FILE="../jScript.php" -->
<!-- #INCLUDE FILE="../Funcoes.php" -->
<!-- #INCLUDE FILE="DB_Tabelas.php" -->
<!-- #INCLUDE FILE="Funcoes.php" -->
<!-- #INCLUDE FILE="VisualDemanda.php" -->
<? 
header("Expires: ".-1500);
// =========================================================================

//  /GR_DemandaEventual.asp

// ------------------------------------------------------------------------

// Nome     : Alexandre Vinhadelli Papad�polis

// Descricao: Gerencia o m�dulo de demandas

// Mail     : alex@sbpi.com.br

// Criacao  : 15/10/2003 12:25

// Versao   : 1.0.0.0

// Local    : Bras�lia - DF

// -------------------------------------------------------------------------

// 

// Par�metros recebidos:

//    R (refer�ncia) = usado na rotina de grava��o, com conte�do igual ao par�metro T

//    O (opera��o)   = L   : Listagem

//                   = P   : Filtragem

//                   = V   : Gera��o de gr�fico

//                   = W   : Gera��o de documento no formato MS-Word (Office 2003)


// Verifica se o usu�rio est� autenticado

if ($LogOn_session!="Sim")
{

  EncerraSessao();
} 


// Declara��o de vari�veis


$w_troca=${"w_troca"};
$p_projeto=strtoupper(${"p_projeto"});
$p_tipo=strtoupper(${"p_tipo"});
$p_ativo=strtoupper(${"p_ativo"});
$p_solicitante=strtoupper(${"p_solicitante"});
$p_prioridade=strtoupper(${"p_prioridade"});
$p_unidade=strtoupper(${"p_unidade"});
$p_proponente=strtoupper(${"p_proponente"});
$p_ordena=strtoupper(${"p_ordena"});
$p_ini_i=strtoupper(${"p_ini_i"});
$p_ini_f=strtoupper(${"p_ini_f"});
$p_fim_i=strtoupper(${"p_fim_i"});
$p_fim_f=strtoupper(${"p_fim_f"});
$p_atraso=strtoupper(${"p_atraso"});
$p_chave=strtoupper(${"p_chave"});
$p_assunto=strtoupper(${"p_assunto"});
$p_usu_resp=strtoupper(${"p_usu_resp"});
$p_uorg_resp=strtoupper(${"p_uorg_resp"});
$p_palavra=strtoupper(${"p_palavra"});
$p_prazo=strtoupper(${"p_prazo"});
$p_fase=strtoupper(${"p_fase"});
$p_sqcc=strtoupper(${"p_sqcc"});
$p_agrega=strtoupper(${"p_agrega"});
$p_tamanho=strtoupper(${"p_tamanho"});


AbreSessao();

// Carrega vari�veis locais com os dados dos par�metros recebidos

$Par=strtoupper(${"Par"});
$P1=Nvl(${"P1"},0);
$P2=Nvl(${"P2"},0);
$P3=$cDbl[Nvl(${"P3"},1)];
$P4=$cDbl[Nvl(${"P4"},$conPagesize)];
$TP=${"TP"};
$SG=strtoupper(${"SG"});
$R=strtoupper(${"R"});
$O=strtoupper(${"O"});
$w_Assinatura=strtoupper(${"w_Assinatura"});

$w_Pagina="GR_DemandaEventual.asp?par=";
$w_Dir="mod_or_pub/";
$w_dir_volta="../";
$w_Disabled="ENABLED";

if ($O=="")
{
  $O="P";
}
;
} 

switch ($O)
{
  case "V":
    $w_TP=$TP." - Gr�fico";
    break;
  case "P":
    $w_TP=$TP." - Filtragem";
    break;
  default:

    $w_TP=$TP." - Listagem";
    break;
} 

$w_cliente=RetornaCliente();
$w_usuario=RetornaUsuario();
$w_menu=$P2;

// Recupera a configura��o do servi�o

DB_GetMenuData($RS_menu,$w_menu);

Main();

FechaSessao();

$t_valor=null;

$t_acima=null;

$t_totvalor=null;

$t_totacima=null;

$t_aviso=null;

$t_solic=null;

$t_cad=null;

$t_tram=null;

$t_conc=null;

$t_atraso=null;

$w_filtro=null;

$w_qt_quebra=null;

$w_nm_quebra=null;

$w_linha=null;

$w_pag=null;

$w_menu=null;

$w_usuario=null;

$w_cliente=null;

$w_filter=null;

$w_cor=null;

$ul=null;

$File=null;

$w_sq_pessoa=null;

$w_troca=null;

$w_reg=null;

$p_ini_i=null;

$p_ini_f=null;

$p_fim_i=null;

$p_fim_f=null;

$p_atraso=null;

$p_unidade=null;

$p_prioridade=null;

$p_solicitante=null;

$p_ativo=null;

$p_proponente=null;

$p_projeto=null;

$p_tipo=null;

$p_ordena=null;

$p_chave=null;

$p_assunto=null;

$p_usu_resp=null;

$p_uorg_resp=null;

$p_palavra=null;

$p_prazo=null;

$p_fase=null;

$p_sqcc=null;

$p_agrega=null;

$p_tamanho=null;


$RS=null;

$RS1=null;

$RS2=null;

$RS_menu=null;

$Par=null;

$P1=null;

$P2=null;

$P3=null;

$P4=null;

$TP=null;

$SG=null;

$FS=null;

$w_file=null;

$R=null;

$O=null;

$w_Classe=null;

$w_Cont=null;

$w_Pagina=null;

$w_Disabled=null;

$w_TP=null;

$w_Assinatura=null;


// =========================================================================

// Pesquisa gerencial

// -------------------------------------------------------------------------

function Gerencial()
{
  extract($GLOBALS);



  if ($O=="L" || $O=="V" || $O=="W")
  {

    $w_filtro="";
    if ($p_projeto>"")
    {

      DB_GetSolicData($RS,$p_projeto,"PJGERAL");
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>A��o <td><font size=1>[<b><A class=\"HL\" HREF=\"Projeto.asp?par=Visual&O=L&w_chave=".$p_projeto."&w_tipo=Volta&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\" title=\"Exibe as informa��es do projeto.\">".$RS["titulo"]."</a></b>]";
    } 

    if ($p_sqcc>"")
    {

      DB_GetCCData($RS,$p_sqcc);
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Classifica��o <td><font size=1>[<b>".$RS["nome"]."</b>]";
    } 

    if ($p_chave>"")
    {
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Demanda n� <td><font size=1>[<b>".$p_chave."</b>]";
    }
;
    } 
    if ($p_prazo>"")
    {
      $w_filtro=$w_filtro." <tr valign=\"top\"><td align=\"right\"><font size=1>Prazo para conclus�o at�<td><font size=1>[<b>".$FormatDateTime[$DateAdd["d"][$p_prazo][time()()]][1]."</b>]";
    }
;
    } 
    if ($p_solicitante>"")
    {

      DB_GetPersonData($RS,$w_cliente,$p_solicitante,null,null);
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Respons�vel <td><font size=1>[<b>".$RS["nome_resumido"]."</b>]";
    } 

    if ($p_unidade>"")
    {

      DB_GetUorgData($RS,$p_unidade);
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Unidade respons�vel <td><font size=1>[<b>".$RS["nome"]."</b>]";
    } 

    if ($p_usu_resp>"")
    {

      DB_GetPersonData($RS,$w_cliente,$p_usu_resp,null,null);
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Executor <td><font size=1>[<b>".$RS["nome_resumido"]."</b>]";
    } 

    if ($p_uorg_resp>"")
    {

      DB_GetUorgData($RS,$p_uorg_resp);
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Unidade atual <td><font size=1>[<b>".$RS["nome"]."</b>]";
    } 

    if ($p_prioridade>"")
    {
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Prioridade <td><font size=1>[<b>".RetornaPrioridade($p_prioridade)."</b>]";
    }
;
    } 
    if ($p_proponente>"")
    {
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Parceria externa <td><font size=1>[<b>".$p_proponente."</b>]";
    }
;
    } 
    if ($p_assunto>"")
    {
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Detalhamento <td><font size=1>[<b>".$p_assunto."</b>]";
    }
;
    } 
    if ($p_palavra>"")
    {
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Respons�vel <td><font size=1>[<b>".$p_palavra."</b>]";
    }
;
    } 
    if ($p_ini_i>"")
    {
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Data recebimento <td><font size=1>[<b>".$p_ini_i."-".$p_ini_f."</b>]";
    }
;
    } 
    if ($p_fim_i>"")
    {
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Limite conclus�o <td><font size=1>[<b>".$p_fim_i."-".$p_fim_f."</b>]";
    }
;
    } 
    if ($p_atraso=="S")
    {
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Situa��o <td><font size=1>[<b>Apenas atrasadas</b>]";
    }
;
    } 
    if ($w_filtro>"")
    {
      $w_filtro="<table border=0><tr valign=\"top\"><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>".$w_filtro."</ul></tr></table>";
    }
;
    } 

    switch ($p_agrega)
    {
      case "GRDMPROJ":
        DB_GetSolicList($RS1,$P2,$w_usuario,$p_agrega,5,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,$p_prioridade,$p_ativo,$p_proponente);
        $w_TP=$TP." - Por projeto";
$RS1->sort="nm_projeto";
        break;
      case "GRDMPROP":
        DB_GetSolicList($RS1,$P2,$w_usuario,$p_agrega,5,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,$p_prioridade,$p_ativo,$p_proponente);
        $w_TP=$TP." - Por proponente";
$RS1->Filter="proponente <> null";
$RS1->sort="proponente";
        break;
      case "GRDMRESP":
        DB_GetSolicList($RS1,$P2,$w_usuario,$p_agrega,5,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,$p_prioridade,$p_ativo,$p_proponente);
        $w_TP=$TP." - Por respons�vel";
$RS1->sort="nm_solic";
        break;
      case "GRDMRESPATU":
        DB_GetSolicList($RS1,$P2,$w_usuario,$p_agrega,5,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,$p_prioridade,$p_ativo,$p_proponente);
        $w_TP=$TP." - Por executor";
$RS1->Filter="executor <> null";
$RS1->sort="nm_exec";
        break;
      case "GRDMCC":
        DB_GetSolicList($RS1,$P2,$w_usuario,$p_agrega,5,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,$p_prioridade,$p_ativo,$p_proponente);
        $w_TP=$TP." - Por classifica��o";
$RS1->sort="sg_cc";
        break;
      case "GRDMSETOR":
        DB_GetSolicList($RS1,$P2,$w_usuario,$p_agrega,5,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,$p_prioridade,$p_ativo,$p_proponente);
        $w_TP=$TP." - Por setor respons�vel";
$RS1->sort="nm_unidade_resp";
        break;
      case "GRDMPRIO":
        $w_TP=$TP." - Por prioridade";
        DB_GetSolicList($RS1,$P2,$w_usuario,$p_agrega,5,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,$p_prioridade,$p_ativo,$p_proponente);
$RS1->sort="nm_prioridade";
        break;
      case "GRDMAREA":
        $w_TP=$TP." - Por �rea envolvida";
        DB_GetSolicGRA($RS1,$P2,$w_usuario,$p_agrega,5,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,$p_prioridade,$p_ativo,$p_proponente);
$RS1->sort="nm_envolv";
        break;
    } 
  } 


  if ($O=="W")
  {

    HeaderWord(null);
    $w_pag=1;
    $w_linha=0;
    ShowHTML("<BASE HREF=\"".$conRootSIW."\">");
    CabecalhoWord($w_cliente,$w_TP,$w_pag);
    if ($w_filtro>"")
    {
      ShowHTML;
    }
($w_filtro);    } 

  }
    else
  {

    Cabecalho();
    ShowHTML("<HEAD>");
    if ($O=="P")
    {

      ScriptOpen("Javascript");
      CheckBranco();
      FormataData();
      ValidateOpen("Validacao");
      Validate("p_chave","N�mero da demanda","","","1","18","","0123456789");
      Validate("p_prazo","Dias para a data limite","","","1","2","","0123456789");
      Validate("p_proponente","Parcerias externas","","","2","90","1","");
      Validate("p_assunto","Detalhamento","","","2","90","1","1");
      Validate("p_palavra","Palavras-chave","","","2","90","1","1");
      Validate("p_ini_i","Recebimento inicial","DATA","","10","10","","0123456789/");
      Validate("p_ini_f","Recebimento final","DATA","","10","10","","0123456789/");
      ShowHTML("  if ((theForm.p_ini_i.value != '' && theForm.p_ini_f.value == '') || (theForm.p_ini_i.value == '' && theForm.p_ini_f.value != '')) {");
      ShowHTML("     alert ('Informe ambas as datas de recebimento ou nenhuma delas!');");
      ShowHTML("     theForm.p_ini_i.focus();");
      ShowHTML("     return false;");
      ShowHTML("  }");
      CompData("p_ini_i","Recebimento inicial","<=","p_ini_f","Recebimento final");
      Validate("p_fim_i","Conclus�o inicial","DATA","","10","10","","0123456789/");
      Validate("p_fim_f","Conclus�o final","DATA","","10","10","","0123456789/");
      ShowHTML("  if ((theForm.p_fim_i.value != '' && theForm.p_fim_f.value == '') || (theForm.p_fim_i.value == '' && theForm.p_fim_f.value != '')) {");
      ShowHTML("     alert ('Informe ambas as datas de conclus�o ou nenhuma delas!');");
      ShowHTML("     theForm.p_fim_i.focus();");
      ShowHTML("     return false;");
      ShowHTML("  }");
      CompData("p_fim_i","Conclus�o inicial","<=","p_fim_f","Conclus�o final");
      if ($SG=="PROJETO")
      {

        ShowHTML("  if (theForm.p_agrega[theForm.p_agrega.selectedIndex].value == 'GRDMETAPA' && theForm.p_projeto.selectedIndex == 0) {");
        ShowHTML("     alert ('A agrega��o por etapa exige a sele��o de um projeto!');");
        ShowHTML("     theForm.p_projeto.focus();");
        ShowHTML("     return false;");
        ShowHTML("  }");
      } 

      ValidateClose();
      ScriptClose();
    }
      else
    {

      ShowHTML("<TITLE>".$w_TP."</TITLE>");
    } 

    ShowHTML("</HEAD>");
    ShowHTML("<BASE HREF=\"".$conRootSIW."\">");
    if ($w_Troca>"")
    {
// Se for recarga da p�gina

      BodyOpen("onLoad='document.Form.".$w_Troca.".focus();'");
    }
      else
    if ((strpos("P",$O) ? strpos("P",$O)+1 : 0)>0)
    {

      if ($P1==1)
      {
// Se for cadastramento

        BodyOpen("onLoad='document.Form.p_ordena.focus()';");
      }
        else
      {

        BodyOpen("onLoad='document.Form.p_agrega.focus()';");
      } 

    }
      else
    {

      BodyOpenClean("onLoad=document.focus();");
    } 

    if ($O=="L")
    {

      ShowHTML("<B><FONT COLOR=\"#000000\">".$w_TP."</FONT></B>");
      ShowHTML("<HR>");
      if ($w_filtro>"")
      {
        ShowHTML;
      }
($w_filtro);      } 

    }
      else
    {

      ShowHTML("<B><FONT COLOR=\"#000000\">".$w_TP."</FONT></B>");
      ShowHTML("<HR>");
    } 

  } 


  ShowHTML("<div align=center><center>");
  ShowHTML("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">");
  if ($O=="L" || $O=="W")
  {

    if ($O=="L")
    {

      ShowHTML("<tr><td><font size=\"1\">");
      if (MontaFiltro("GET")>"")
      {

        ShowHTML("                         <a accesskey=\"F\" class=\"SS\" href=\"".$w_dir.$w_Pagina.$par."&R=".$w_Pagina.$par."&O=P&P1=".$P1."&P2=".$P2."&P3=1&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\"><u><font color=\"#BC5100\">F</u>iltrar (Ativo)</font></a>");
      }
        else
      {

        ShowHTML("                         <a accesskey=\"F\" class=\"SS\" href=\"".$w_dir.$w_Pagina.$par."&R=".$w_Pagina.$par."&O=P&P1=".$P1."&P2=".$P2."&P3=1&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\"><u>F</u>iltrar (Inativo)</a>");
      } 

    } 

    ImprimeCabecalho();
    if ($RS1->EOF)
    {

      ShowHTML("      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=10 align=\"center\"><font size=\"1\"><b>N�o foram encontrados registros.</b></td></tr>");
    }
      else
    {

      if ($O=="L")
      {

        ShowHTML("<SCRIPT LANGUAGE=\"JAVASCRIPT\">");
        ShowHTML("  function lista (filtro, cad, exec, conc, atraso) {");
        ShowHTML("    if (filtro != -1) {");
        switch ($p_agrega)
        {
          case "GRDMPROJ":
            ShowHTML("      document.Form.p_projeto.value=filtro;");
            break;
          case "GRDMPROP":
            ShowHTML("      document.Form.p_proponente.value=filtro;");
            break;
          case "GRDMRESP":
            ShowHTML("      document.Form.p_solicitante.value=filtro;");
            break;
          case "GRDMRESPATU":
            ShowHTML("      document.Form.p_usu_resp.value=filtro;");
            break;
          case "GRDMCC":
            ShowHTML("      document.Form.p_sqcc.value=filtro;");
            break;
          case "GRDMSETOR":
            ShowHTML("      document.Form.p_unidade.value=filtro;");
            break;
          case "GRDMPRIO":
            ShowHTML("      document.Form.p_prioridade.value=filtro;");
            break;
          case "GRDMAREA":
            ShowHTML("      document.Form.p_area.value=filtro;");
            break;
        } 
        ShowHTML("    }");
        switch ($p_agrega)
        {
          case "GRDMPROJ":
            ShowHTML("    else document.Form.p_projeto.value='".${"p_projeto"}."';");
            break;
          case "GRDMPROP":
            ShowHTML("    else document.Form.p_proponente.value=\"".${"p_proponente"}."\";");
            break;
          case "GRDMRESP":
            ShowHTML("    else document.Form.p_solicitante.value='".${"p_solicitante"}."';");
            break;
          case "GRDMRESPATU":
            ShowHTML("    else document.Form.p_usu_resp.value='".${"p_usu_resp"}."';");
            break;
          case "GRDMCC":
            ShowHTML("    else document.Form.p_sqcc.value='".${"p_sqcc"}."';");
            break;
          case "GRDMSETOR":
            ShowHTML("    else document.Form.p_unidade.value='".${"p_unidade"}."';");
            break;
          case "GRDMPRIO":
            ShowHTML("    else document.Form.p_prioridade.value='".${"p_prioridade"}."';");
            break;
          case "GRDMAREA":
            ShowHTML("    else document.Form.p_area.value='".${"p_area"}."';");
            break;
        } 
        DB_GetTramiteList($RS2,$P2,null,null);
$RS2->Sort="ordem";
        $w_fase_exec="";
        while(!$RS2->EOF)
        {

          if ($RS2["sigla"]=="CI")
          {

            $w_fase_cad=$RS2["sq_siw_tramite"];
          }
            else
          if ($RS2["sigla"]=="AT")
          {

            $w_fase_conc=$RS2["sq_siw_tramite"];
          }
            else
          if ($RS2["ativo"]=="S")
          {

            $w_fase_exec=$w_fase_exec.",".$RS2["sq_siw_tramite"];
          } 

$RS2->MoveNext;
        } 
        ShowHTML("    if (cad >= 0) document.Form.p_fase.value=".$w_fase_cad.";");
        ShowHTML("    if (exec >= 0) document.Form.p_fase.value='".substr($w_fase_exec,1,100)."';");
        ShowHTML("    if (conc >= 0) document.Form.p_fase.value=".$w_fase_conc.";");
        ShowHTML("    if (cad==-1 && exec==-1 && conc==-1) document.Form.p_fase.value='".${"p_fase"}."'; ");
        ShowHTML("    if (atraso >= 0) document.Form.p_atraso.value='S'; else document.Form.p_atraso.value='".${"p_atraso"}."'; ");
        ShowHTML("    document.Form.submit();");
        ShowHTML("  }");
        ShowHTML("</SCRIPT>");
        ShowHTML("<BASE HREF=\"".$conRootSIW."\">");
        DB_GetMenuData($RS2,$P2);
        AbreForm("Form",$RS2["link"],"POST","return(Validacao(this));","Lista",3,$P2,$RS2["P3"],null,$w_TP,$RS2["sigla"],$w_dir.$w_pagina.$par,"L");
        ShowHTML(MontaFiltro("POST"));
        switch ($p_agrega)
        {
          case "GRDMPROJ":
            if (${"p_projeto"}=="")
            {
              ShowHTML;
            }
("<input type=\"Hidden\" name=\"p_projeto\" value=\"\">");            } 

            break;
          case "GRDMPROP":
            if (${"p_proponente"}=="")
            {
              ShowHTML;
            }
("<input type=\"Hidden\" name=\"p_proponente\" value=\"\">");            } 

            break;
          case "GRDMRESP":
            if (${"p_solicitante"}=="")
            {
              ShowHTML;
            }
("<input type=\"Hidden\" name=\"p_solicitante\" value=\"\">");            } 

            break;
          case "GRDMRESPATU":
            if (${"p_usu_resp"}=="")
            {
              ShowHTML;
            }
("<input type=\"Hidden\" name=\"p_usu_resp\" value=\"\">");            } 

            break;
          case "GRDMCC":
            if (${"p_sqcc"}=="")
            {
              ShowHTML;
            }
("<input type=\"Hidden\" name=\"p_sqcc\" value=\"\">");            } 

            break;
          case "GRDMSETOR":
            if (${"p_unidade"}=="")
            {
              ShowHTML;
            }
("<input type=\"Hidden\" name=\"p_unidade\" value=\"\">");            } 

            break;
          case "GRDMPRIO":
            if (${"p_prioridade"}=="")
            {
              ShowHTML;
            }
("<input type=\"Hidden\" name=\"p_prioridade\" value=\"\">");            } 

            break;
          case "GRDMAREA":
            if (${"p_area"}=="")
            {
              ShowHTML;
            }
("<input type=\"Hidden\" name=\"p_area\" value=\"\">");            } 

            break;
        } 
      } 


$RS1->PageSize=$P4;
$RS1->AbsolutePage=$P3;
      $w_nm_quebra="";
      $w_qt_quebra=0;
      $t_solic=0;
      $t_cad=0;
      $t_tram=0;
      $t_conc=0;
      $t_atraso=0;
      $t_aviso=0;
      $t_valor=0;
      $t_acima=0;
      $t_custo=0;
      $t_totcusto=0;
      $t_totsolic=0;
      $t_totcad=0;
      $t_tottram=0;
      $t_totconc=0;
      $t_totatraso=0;
      $t_totaviso=0;
      $t_totvalor=0;
      $t_totacima=0;
      while(!$RS1->EOF)
      {

        switch ($p_agrega)
        {
          case "GRDMPROJ":
            if ($w_nm_quebra!=$RS1["nm_projeto"])
            {

              if ($w_qt_quebra>0)
              {

                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 

              if ($O!="W" || ($O=="W" && $w_linha<=25))
              {

// Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite

                ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["nm_projeto"]);
              } 

              $w_nm_quebra=$RS1["nm_projeto"];
              $w_chave=$RS1["sq_solic_pai"];
              $w_qt_quebra=0;
              $t_solic=0;
              $t_cad=0;
              $t_tram=0;
              $t_conc=0;
              $t_atraso=0;
              $t_aviso=0;
              $t_valor=0;
              $t_acima=0;
              $t_custo=0;
              $w_linha=$w_linha+1;
            } 

            break;
          case "GRDMPROP":
            if ($w_nm_quebra!=$RS1["proponente"])
            {

              if ($w_qt_quebra>0)
              {

                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 

              if ($O!="W" || ($O=="W" && $w_linha<=25))
              {

// Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite

                ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["proponente"]);
              } 

              $w_nm_quebra=$RS1["proponente"];
              $w_chave=$RS1["proponente"];
              $w_qt_quebra=0;
              $t_solic=0;
              $t_cad=0;
              $t_tram=0;
              $t_conc=0;
              $t_atraso=0;
              $t_aviso=0;
              $t_valor=0;
              $t_acima=0;
              $t_custo=0;
              $w_linha=$w_linha+1;
            } 

            break;
          case "GRDMRESP":
            if ($w_nm_quebra!=$RS1["nm_solic"])
            {

              if ($w_qt_quebra>0)
              {

                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 

              if ($O!="W" || ($O=="W" && $w_linha<=25))
              {

// Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite

                ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["nm_solic"]);
              } 

              $w_nm_quebra=$RS1["nm_solic"];
              $w_chave=$RS1["solicitante"];
              $w_qt_quebra=0;
              $t_solic=0;
              $t_cad=0;
              $t_tram=0;
              $t_conc=0;
              $t_atraso=0;
              $t_aviso=0;
              $t_valor=0;
              $t_acima=0;
              $t_custo=0;
              $w_linha=$w_linha+1;
            } 

            break;
          case "GRDMRESPATU":
            if ($w_nm_quebra!=$RS1["nm_exec"])
            {

              if ($w_qt_quebra>0)
              {

                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 

              if ($O!="W" || ($O=="W" && $w_linha<=25))
              {

// Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite

                ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["nm_exec"]);
              } 

              $w_nm_quebra=$RS1["nm_exec"];
              $w_chave=$RS1["executor"];
              $w_qt_quebra=0;
              $t_solic=0;
              $t_cad=0;
              $t_tram=0;
              $t_conc=0;
              $t_atraso=0;
              $t_aviso=0;
              $t_valor=0;
              $t_acima=0;
              $t_custo=0;
              $w_linha=$w_linha+1;
            } 

            break;
          case "GRDMCC":
            if ($w_nm_quebra!=$RS1["sg_cc"])
            {

              if ($w_qt_quebra>0)
              {

                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 

              if ($O!="W" || ($O=="W" && $w_linha<=25))
              {

// Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite

                ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["sg_cc"]);
              } 

              $w_nm_quebra=$RS1["sg_cc"];
              $w_chave=$RS1["sq_cc"];
              $w_qt_quebra=0;
              $t_solic=0;
              $t_cad=0;
              $t_tram=0;
              $t_conc=0;
              $t_atraso=0;
              $t_aviso=0;
              $t_valor=0;
              $t_acima=0;
              $t_custo=0;
              $w_linha=$w_linha+1;
            } 

            break;
          case "GRDMSETOR":
            if ($w_nm_quebra!=$RS1["nm_unidade_resp"])
            {

              if ($w_qt_quebra>0)
              {

                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 

              if ($O!="W" || ($O=="W" && $w_linha<=25))
              {

// Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite

                ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["nm_unidade_resp"]);
              } 

              $w_nm_quebra=$RS1["nm_unidade_resp"];
              $w_chave=$RS1["sq_unidade_resp"];
              $w_qt_quebra=0;
              $t_solic=0;
              $t_cad=0;
              $t_tram=0;
              $t_conc=0;
              $t_atraso=0;
              $t_aviso=0;
              $t_valor=0;
              $t_acima=0;
              $t_custo=0;
              $w_linha=$w_linha+1;
            } 

            break;
          case "GRDMPRIO":
            if ($w_nm_quebra!=$RS1["nm_prioridade"])
            {

              if ($w_qt_quebra>0)
              {

                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 

              if ($O!="W" || ($O=="W" && $w_linha<=25))
              {

// Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite

                ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["nm_prioridade"]);
              } 

              $w_nm_quebra=$RS1["nm_prioridade"];
              $w_chave=$RS1["prioridade"];
              $w_qt_quebra=0;
              $t_solic=0;
              $t_cad=0;
              $t_tram=0;
              $t_conc=0;
              $t_atraso=0;
              $t_aviso=0;
              $t_valor=0;
              $t_acima=0;
              $t_custo=0;
              $w_linha=$w_linha+1;
            } 

            break;
          case "GRDMAREA":
            if ($w_nm_quebra!=$RS1["nm_envolv"])
            {

              if ($w_qt_quebra>0)
              {

                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 

              if ($O!="W" || ($O=="W" && $w_linha<=25))
              {

                ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["nm_envolv"]);
              } 

              $w_nm_quebra=$RS1["nm_envolv"];
              $w_chave=$RS1["sq_unidade"];
              $w_qt_quebra=0;
              $t_solic=0;
              $t_cad=0;
              $t_tram=0;
              $t_conc=0;
              $t_atraso=0;
              $t_aviso=0;
              $t_valor=0;
              $t_acima=0;
              $t_custo=0;
              $w_linha=$w_linha+1;
            } 

            break;
        } 
        if ($O=="W" && $w_linha>25)
        {
// Se for gera��o de MS-Word, quebra a p�gina

          ShowHTML("    </table>");
          ShowHTML("  </td>");
          ShowHTML("</tr>");
          ShowHTML("</table>");
          ShowHTML("</center></div>");
          ShowHTML("    <br style=\"page-break-after:always\">");
          $w_linha=0;
          $w_pag=$w_pag+1;
          CabecalhoWord($w_cliente,$w_TP,$w_pag);
          if ($w_filtro>"")
          {
            ShowHTML;
          }
($w_filtro);          } 

          ShowHTML("<div align=center><center>");
          ShowHTML("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">");
          ImprimeCabecalho();
          switch ($p_agrega)
          {
            case "GRDMPROJ":
              ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["nm_projeto"]);
              break;
            case "GRDMPROP":
              ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["proponente"]);
              break;
            case "GRDMRESP":
              ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["nm_solic"]);
              break;
            case "GRDMRESPATU":
              ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["nm_exec"]);
              break;
            case "GRDMCC":
              ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["sg_cc"]);
              break;
            case "GRDMSETOR":
              ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["nm_unidade_resp"]);
              break;
            case "GRDMPRIO":
              ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["nm_prioridade"]);
              break;
            case "GRDMAREA":
              ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["nm_envolv"]);
              break;
          } 
          $w_linha=$w_linha+1;
        } 

        if ($RS1["concluida"]=="N")
        {

          if ($RS1["fim"]<time()())
          {

            $t_atraso=$t_atraso+1;
            $t_totatraso=$t_totatraso+1;
          }
            else
          if ($RS1["aviso_prox_conc"]=="S" && ($RS1["aviso"]<=time()()))
          {

            $t_aviso=$t_aviso+1;
            $t_totaviso=$t_totaviso+1;
          } 


          if ($cDbl[$RS1["or_tramite"]]==1)
          {

            $t_cad=$t_cad+1;
            $t_totcad=$t_totcad+1;
          }
            else
          {

            $t_tram=$t_tram+1;
            $t_tottram=$t_tottram+1;
          } 

        }
          else
        {

          $t_conc=$t_conc+1;
          $t_totconc=$t_totconc+1;
          if ($cDbl[$RS1["valor"]]<$cDbl[$RS1["custo_real"]])
          {

            $t_acima=$t_acima+1;
            $t_totacima=$t_totacima+1;
          } 

        } 

        $t_solic=$t_solic+1;
        $t_valor=$t_valor+Nvl($cDbl[$RS1["valor"]],0);
        $t_custo=$t_custo+Nvl($cDbl[$RS1["custo_real"]],0);

        $t_totvalor=$t_totvalor+Nvl($cDbl[$RS1["valor"]],0);
        $t_totcusto=$t_totcusto+Nvl($cDbl[$RS1["custo_real"]],0);
        $t_totsolic=$t_totsolic+1;
        $w_qt_quebra=$w_qt_quebra+1;
$RS1->MoveNext;
      } 
      ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);

      ShowHTML("      <tr bgcolor=\"#DCDCDC\" valign=\"top\" align=\"right\">");
      ShowHTML("          <td><font size=\"1\"><b>Totais</font></td>");
      ImprimeLinha($t_totsolic,$t_totcad,$t_tottram,$t_totconc,$t_totatraso,$t_totaviso,$t_totvalor,$t_totcusto,$t_totacima,-1);
    } 

    ShowHTML("      </FORM>");
    ShowHTML("      </center>");
    ShowHTML("    </table>");
    ShowHTML("  </td>");
    ShowHTML("</tr>");

    if ($RS1->RecordCount>0 && $p_tipo=="N")
    {
// Coloca o gr�fico somente se o usu�rio desejar

      ShowHTML("<tr><td align=\"center\" height=20>");
      ShowHTML("<tr><td align=\"center\"><IMG SRC=\"".$conPHP4."geragrafico.php?p_genero=F&p_objeto=".$RS_Menu["nome"]."&p_tipo=".$SG."&p_grafico=Barra&p_tot=".$t_totsolic."&p_cad=".$t_totcad."&p_tram=".$t_tottram."&p_conc=".$t_totconc."&p_atraso=".$t_totatraso."&p_aviso=".$t_totaviso."&p_acima=".$t_totacima."\">");
      ShowHTML("<tr><td align=\"center\" height=20>");
      if (($t_totcad+$t_tottram)>0)
      {

        ShowHTML("<tr><td align=\"center\"><IMG SRC=\"".$conPHP4."geragrafico.php?p_genero=F&p_objeto=".$RS_Menu["nome"]."&p_tipo=".$SG."&p_grafico=Pizza&p_tot=".$t_totsolic."&p_cad=".$t_totcad."&p_tram=".$t_tottram."&p_conc=".$t_totconc."&p_atraso=".$t_totatraso."&p_aviso=".$t_totaviso."&p_acima=".$t_totacima."\">");
      } 

    } 


  }
    else
  if ((strpos("P",$O) ? strpos("P",$O)+1 : 0)>0)
  {

    ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td><div align=\"justify\"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>");
    ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">");
    AbreForm("Form",$w_dir.$w_Pagina.$par,"POST","return(Validacao(this));",null,$P1,$P2,$P3,null,$TP,$SG,$R,"L");

// Exibe par�metros de apresenta��o

    ShowHTML("      <tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\" valign=\"top\"><table border=0 width=\"90%\" cellspacing=0>");
    ShowHTML("         <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Par�metros de Apresenta��o</td>");
    ShowHTML("         <tr valign=\"top\"><td colspan=2><table border=0 width=\"100%\" cellpadding=0 cellspacing=0><tr valign=\"top\">");
    ShowHTML("          <td><font size=\"1\"><b><U>A</U>gregar por:<br><SELECT ACCESSKEY=\"A\" ".$w_Disabled." class=\"STS\" name=\"p_agrega\" size=\"1\">");
//If p_agrega = "GRDMAREA"        Then                             ShowHTML " <option value=""GRDMAREA"" selected>�rea envolvida"                   Else ShowHTML " <option value=""GRDMAREA"">�rea envolvida"                    End If

    if ($RS_menu["solicita_cc"]=="S")
    {
      if ($p_agrega=="GRDMCC")
      {
        ShowHTML(" <option value=\"GRDMCC\" selected>Classifica��o");      } 
    } 
  }
    else
  {
    ShowHTML;
  }
;
  }
(" <option value=\"GRDMCC\">Classifica��o");
    if ($p_agrega=="GRDMPRIO")
    {
      ShowHTML(" <option value=\"GRDMPRIO\" selected>Prioridade");    } 
  }
    else
  {
    ShowHTML;
  }
(" <option value=\"GRDMPRIO\">Prioridade");
    if ($p_agrega=="GRDMRESPATU")
    {
      ShowHTML(" <option value=\"GRDMRESPATU\" selected>Executor");    } 
  }
    else
  {
    ShowHTML;
  }
(" <option value=\"GRDMRESPATU\">Executor");
    if ($p_agrega=="GRDMPROP")
    {
      ShowHTML(" <option value=\"GRDMPROP\" selected>Parceria externa");    } 
  }
    else
  {
    ShowHTML;
  }
(" <option value=\"GRDMPROP\">Parceria externa");
    if (Nvl($p_agrega,"GRDMRESP")=="GRDMRESP")
    {
      ShowHTML(" <option value=\"GRDMRESP\" selected>Respons�vel monitoramento");    } 
  }
    else
  {
    ShowHTML;
  }
(" <option value=\"GRDMRESP\">Respons�vel pelo monitoramento");
    if ($p_agrega=="GRDMSETOR")
    {
      ShowHTML(" <option value=\"GRDMSETOR\" selected>Setor respons�vel monitoramento");    } 
  }
    else
  {
    ShowHTML;
  }
(" <option value=\"GRDMSETOR\">Setor respons�vel monitoramento");
    ShowHTML("          </select></td>");
    MontaRadioNS("<b>Inibe exibi��o do gr�fico?</b>",$p_tipo,"p_tipo");
    MontaRadioSN("<b>Limita tamanho do detalhamento?</b>",$p_tamanho,"p_tamanho");
    ShowHTML("           </table>");
    ShowHTML("         </tr>");
    ShowHTML("         <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Crit�rios de Busca</td>");

    ShowHTML("<INPUT type=\"hidden\" name=\"w_troca\" value=\"\">");
    ShowHTML("      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>");
    ShowHTML("      <tr>");
    DB_GetLinkData($RS,$w_cliente,"ORCAD");
    SelecaoProjeto("A��<u>o</u>:","O","Selecione a a��o da tarefa na rela��o.",$p_projeto,$w_usuario,$RS["sq_menu"],"p_projeto","PJLIST",null);
    DesconectaBD();
    ShowHTML("</table>");
    if ($RS_menu["solicita_cc"]=="S")
    {

      ShowHTML("      <tr><td colspan=2><table border=0 width=\"90%\" cellspacing=0><tr valign=\"top\">");
      SelecaoCC("C<u>l</u>assifica��o:","C","Selecione um dos itens relacionados.",$p_sqcc,null,"p_sqcc","SIWSOLIC");
      ShowHTML("          </table>");
    } 

    ShowHTML("      <tr valign=\"top\">");
    ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>N�mero da tare<U>f</U>a:<br><INPUT ACCESSKEY=\"F\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"p_chave\" size=\"18\" maxlength=\"18\" value=\"".$p_chave."\"></td>");
    ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY=\"T\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"p_prazo\" size=\"2\" maxlength=\"2\" value=\"".$p_prazo."\"></td>");
    ShowHTML("      <tr valign=\"top\">");
    SelecaoPessoa("Re<u>s</u>pons�vel monitoramento:","S","Selecione o respons�vel pelo monitoramento na rela��o.",$p_solicitante,null,"p_solicitante","USUARIOS");
    SelecaoUnidade("Setor respons�vel monitoramento:","Y",null,$p_unidade,null,"p_unidade",null,null);
    ShowHTML("      <tr valign=\"top\">");
    SelecaoPessoa("E<u>x</u>ecutor:","X","Selecione o executor da tarefa na rela��o.",$p_usu_resp,null,"p_usu_resp","USUARIOS");
    SelecaoUnidade("<U>S</U>etor atual:","Y","Selecione a unidade onde a tarefa se encontra na rela��o.",$p_uorg_resp,null,"p_uorg_resp",null,null);
    ShowHTML("      <tr>");
    SelecaoPrioridade("<u>P</u>rioridade:","P","Informe a prioridade desta demanda.",$p_prioridade,null,"p_prioridade",null,null);
    ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>Pa<U>r</U>ceria externa:<br><INPUT ACCESSKEY=\"R\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"p_proponente\" size=\"25\" maxlength=\"90\" value=\"".$p_proponente."\"></td>");
    ShowHTML("      <tr>");
    ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>Detalha<U>m</U>ento:<br><INPUT ACCESSKEY=\"M\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"p_assunto\" size=\"25\" maxlength=\"90\" value=\"".$p_assunto."\"></td>");
    ShowHTML("          <td valign=\"top\" colspan=2><font size=\"1\"><b>R<U>e</U>spons�vel:<br><INPUT ACCESSKEY=\"E\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"p_palavra\" size=\"25\" maxlength=\"90\" value=\"".$p_palavra."\"></td>");
    ShowHTML("      <tr>");
    ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>Data de re<u>c</u>ebimento entre:</b><br><input ".$w_Disabled." accesskey=\"C\" type=\"text\" name=\"p_ini_i\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$p_ini_i."\" onKeyDown=\"FormataData(this,event);\"> e <input ".$w_Disabled." accesskey=\"C\" type=\"text\" name=\"p_ini_f\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$p_ini_f."\" onKeyDown=\"FormataData(this,event);\"></td>");
    ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>Lim<u>i</u>te para conclus�o entre:</b><br><input ".$w_Disabled." accesskey=\"I\" type=\"text\" name=\"p_fim_i\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$p_fim_i."\" onKeyDown=\"FormataData(this,event);\"> e <input ".$w_Disabled." accesskey=\"I\" type=\"text\" name=\"p_fim_f\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$p_fim_f."\" onKeyDown=\"FormataData(this,event);\"></td>");
    ShowHTML("      <tr>");
    ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>Exibe somente tarefas em atraso?</b><br>");
    if ($p_atraso=="S")
    {

      ShowHTML("              <input ".$w_Disabled." class=\"STR\" type=\"radio\" name=\"p_atraso\" value=\"S\" checked> Sim <br><input ".$w_Disabled." class=\"STR\" class=\"STR\" type=\"radio\" name=\"p_atraso\" value=\"N\"> N�o");
    }
      else
    {

      ShowHTML("              <input ".$w_Disabled." class=\"STR\" type=\"radio\" name=\"p_atraso\" value=\"S\"> Sim <br><input ".$w_Disabled." class=\"STR\" class=\"STR\" type=\"radio\" name=\"p_atraso\" value=\"N\" checked> N�o");
    } 

    SelecaoFaseCheck("Recuperar fases:","S",null,$p_fase,$P2,"p_fase",null,null);
    ShowHTML("      <tr><td align=\"center\" colspan=\"2\" height=\"1\" bgcolor=\"#000000\">");
    ShowHTML("      <tr><td align=\"center\" colspan=\"2\">");
    ShowHTML("            <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Exibir\" onClick=\"javascript:document.Form.O.value='L';\">");
    ShowHTML("            <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Gerar Word\" onClick=\"javascript:document.Form.O.value='W'; document.Form.target='Word'\">");
    ShowHTML("          </td>");
    ShowHTML("      </tr>");
    ShowHTML("    </table>");
    ShowHTML("    </TD>");
    ShowHTML("</tr>");
    ShowHTML("</FORM>");
    ShowHTML("</table>");
  }
    else
  {

    ScriptOpen("JavaScript");
    ShowHTML(" alert('Op��o n�o dispon�vel');");
    ShowHTML(" history.back(1);");
    ScriptClose();
  } 

  ShowHTML("</table>");
  ShowHTML("</center>");
  Rodape();

  $w_fase_cad=null;

  $w_fase_exec=null;

  $w_fase_conc=null;

  $w_chave=null;

  return $function_ret;
} 
// =========================================================================

// Fim da rotina

// -------------------------------------------------------------------------


// =========================================================================

// Rotina de impressao do cabecalho

// -------------------------------------------------------------------------

function ImprimeCabecalho()
{
  extract($GLOBALS);


  ShowHTML("<tr><td align=\"center\">");
  ShowHTML("    <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">");
  ShowHTML("        <tr bgcolor=\"#DCDCDC\" align=\"center\">");
  switch ($p_agrega)
  {
    case "GRDMPROJ":
      ShowHTML("          <td><font size=\"1\"><b>Projeto</font></td>");
      break;
    case "GRDMPROP":
      ShowHTML("          <td><font size=\"1\"><b>Proponente</font></td>");
      break;
    case "GRDMRESP":
      ShowHTML("          <td><font size=\"1\"><b>Respons�vel</font></td>");
      break;
    case "GRDMRESPATU":
      ShowHTML("          <td><font size=\"1\"><b>Executor</font></td>");
      break;
    case "GRDMCC":
      ShowHTML("          <td><font size=\"1\"><b>Classifica��o</font></td>");
      break;
    case "GRDMSETOR":
      ShowHTML("          <td><font size=\"1\"><b>Setor respons�vel</font></td>");
      break;
    case "GRDMPRIO":
      ShowHTML("          <td><font size=\"1\"><b>Prioridade</font></td>");
      break;
    case "GRDMAREA":
      ShowHTML("          <td><font size=\"1\"><b>�rea envolvida</font></td>");
      break;
    case "GRDMINTER":
      ShowHTML("          <td><font size=\"1\"><b>Interessado</font></td>");
      break;
  } 
  ShowHTML("          <td><font size=\"1\"><b>Total</font></td>");
  ShowHTML("          <td><font size=\"1\"><b>Cad.</font></td>");
  ShowHTML("          <td><font size=\"1\"><b>Exec.</font></td>");
  ShowHTML("          <td><font size=\"1\"><b>Conc.</font></td>");
  ShowHTML("          <td><font size=\"1\"><b>Atraso</font></td>");
  ShowHTML("          <td><font size=\"1\"><b>Aviso</font></td>");
  ShowHTML("          <td><font size=\"1\"><b>$ Prev.</font></td>");
  ShowHTML("          <td><font size=\"1\"><b>$ Real</font></td>");
  ShowHTML("          <td><font size=\"1\"><b>Real > Previsto</font></td>");
  ShowHTML("        </tr>");
  return $function_ret;
} 
// =========================================================================

// Fim da rotina

// -------------------------------------------------------------------------


// =========================================================================

// Rotina de impressao da linha resumo

// -------------------------------------------------------------------------

function ImprimeLinha($p_solic,$p_cad,$p_tram,$p_conc,$p_atraso,$p_aviso,$p_valor,$p_custo,$p_acima,$p_chave)
{
  extract($GLOBALS);


  if ($O=="L")
  {
    ShowHTML("          <td align=\"right\"><font size=\"1\"><a class=\"hl\" href=\"javascript:lista('".$p_chave."', -1, -1, -1, -1);\" onMouseOver=\"window.status='Exibe as tarefas.'; return true\" onMouseOut=\"window.status=''; return true\">".$FormatNumber[$p_solic][0]."</a>&nbsp;</font></td>");  } 
}
  else
{
  ShowHTML;
}
("          <td align=\"right\"><font size=\"1\">".$FormatNumber[$p_solic][0]."&nbsp;</font></td>");
  if ($p_cad>0 && $O=="L")
  {
    ShowHTML("          <td align=\"right\"><a class=\"hl\" href=\"javascript:lista('".$p_chave."', 0, -1, -1, -1);\" onMouseOver=\"window.status='Exibe as tarefas.'; return true\" onMouseOut=\"window.status=''; return true\"><font size=\"1\">".$FormatNumber[$p_cad][0]."</a>&nbsp;</font></td>");  } 
}
  else
{
  ShowHTML;
}
("          <td align=\"right\"><font size=\"1\">".$FormatNumber[$p_cad][0]."&nbsp;</font></td>");
  if ($p_tram>0 && $O=="L")
  {
    ShowHTML("          <td align=\"right\"><a class=\"hl\" href=\"javascript:lista('".$p_chave."', -1, 0, -1, -1);\" onMouseOver=\"window.status='Exibe as tarefas.'; return true\" onMouseOut=\"window.status=''; return true\"><font size=\"1\">".$FormatNumber[$p_tram][0]."</a>&nbsp;</font></td>");  } 
}
  else
{
  ShowHTML;
}
("          <td align=\"right\"><font size=\"1\">".$FormatNumber[$p_tram][0]."&nbsp;</font></td>");
  if ($p_conc>0 && $O=="L")
  {
    ShowHTML("          <td align=\"right\"><a class=\"hl\" href=\"javascript:lista('".$p_chave."', -1, -1, 0, -1);\" onMouseOver=\"window.status='Exibe as tarefas.'; return true\" onMouseOut=\"window.status=''; return true\"><font size=\"1\">".$FormatNumber[$p_conc][0]."</a>&nbsp;</font></td>");  } 
}
  else
{
  ShowHTML;
}
("          <td align=\"right\"><font size=\"1\">".$FormatNumber[$p_conc][0]."&nbsp;</font></td>");
  if ($p_atraso>0 && $O=="L")
  {
    ShowHTML("          <td align=\"right\"><a class=\"hl\" href=\"javascript:lista('".$p_chave."', -1, -1, -1, 0);\" onMouseOver=\"window.status='Exibe as tarefas.'; return true\" onMouseOut=\"window.status=''; return true\"><font size=\"1\" color=\"red\"><b>".$FormatNumber[$p_atraso][0]."</a>&nbsp;</font></td>");  } 
}
  else
{
  ShowHTML;
}
("          <td align=\"right\"><font size=\"1\"><b>".$p_atraso."&nbsp;</font></td>");
  if ($p_aviso>0 && $O=="L")
  {
    ShowHTML("          <td align=\"right\"><font size=\"1\" color=\"red\"><b>".$FormatNumber[$p_aviso][0]."&nbsp;</font></td>");  } 
}
  else
{
  ShowHTML;
}
("          <td align=\"right\"><font size=\"1\"><b>".$p_aviso."&nbsp;</font></td>");
  ShowHTML("          <td align=\"right\"><font size=\"1\">".$FormatNumber[$p_valor][2]."&nbsp;</font></td>");
  ShowHTML("          <td align=\"right\"><font size=\"1\">".$FormatNumber[$p_custo][2]."&nbsp;</font></td>");
  if ($p_acima>0)
  {
    ShowHTML("          <td align=\"right\"><font size=\"1\" color=\"red\"><b>".$FormatNumber[$p_acima][0]."&nbsp;</font></td>");  } 
}
  else
{
  ShowHTML;
}
("          <td align=\"right\"><font size=\"1\"><b>".$p_acima."&nbsp;</font></td>");
  ShowHTML("        </tr>");
  return $function_ret;
} 
// =========================================================================

// Fim da rotina

// -------------------------------------------------------------------------


// =========================================================================

// Rotina principal

// -------------------------------------------------------------------------

function Main()
{
  extract($GLOBALS);


// Verifica se o usu�rio tem lota��o e localiza��o

  if ((strlen($LOTACAO_session."")==0 || strlen($LOCALIZACAO_session."")==0) && $LogOn_session=="Sim")
  {

    ScriptOpen("JavaScript");
    ShowHTML(" alert('Voc� n�o tem lota��o ou localiza��o definida. Entre em contato com o RH!'); ");
    ShowHTML(" top.location.href='Default.asp'; ");
    ScriptClose();
    return $function_ret;

  } 


  switch ($Par)
  {
    case "GERENCIAL":
      Gerencial();
      break;
    default:

      Cabecalho();
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


