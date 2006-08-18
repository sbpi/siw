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
<!-- #INCLUDE FILE="VisualProjeto.php" -->
<? 
header("Expires: ".-1500);
// =========================================================================

//  /GR_Projeto.asp

// ------------------------------------------------------------------------

// Nome     : Alexandre Vinhadelli Papadópolis

// Descricao: Gerencia o módulo de projetos

// Mail     : alex@sbpi.com.br

// Criacao  : 15/10/2003 12:25

// Versao   : 1.0.0.0

// Local    : Brasília - DF

// -------------------------------------------------------------------------

// 

// Parâmetros recebidos:

//    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T

//    O (operação)   = L   : Listagem

//                   = P   : Filtragem

//                   = V   : Geração de gráfico

//                   = W   : Geração de documento no formato MS-Word (Office 2003)


// Verifica se o usuário está autenticado

if ($LogOn_session!="Sim")
{

  EncerraSessao();
} 


// Declaração de variáveis


$w_troca=${"w_troca"};
$p_tipo=strtoupper(${"p_tipo"});
$p_ativo=strtoupper(${"p_ativo"});
$p_solicitante=strtoupper(${"p_solicitante"});
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
$p_agrega=strtoupper(${"p_agrega"});
$p_tamanho=strtoupper(${"p_tamanho"});
$p_sqcc=strtoupper(${"p_sqcc"});
$p_sq_acao_ppa=strtoupper(${"p_sq_acao_ppa"});
$p_sq_orprioridade=strtoupper(${"p_sq_orprioridade"});
$p_mpog=strtoupper(${"p_mpog"});
$p_relevante=strtoupper(${"p_relevante"});


AbreSessao();

// Carrega variáveis locais com os dados dos parâmetros recebidos

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

$w_Pagina="GR_Projeto.asp?par=";
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
    $w_TP=$TP." - Gráfico";
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
//w_menu            = RetornaMenu(w_cliente, SG) 


// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.

DB_GetLinkSubMenu($RS,$p_cliente_session,$SG);
if ($RS->RecordCount>0)
{

  $w_submenu="Existe";
}
  else
{

  $w_submenu="";
} 

DesconectaBD();

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

$w_submenu=null;

$w_reg=null;

$p_ini_i=null;

$p_ini_f=null;

$p_fim_i=null;

$p_fim_f=null;

$p_atraso=null;

$p_unidade=null;

$p_solicitante=null;

$p_ativo=null;

$p_proponente=null;

$p_tipo=null;

$p_ordena=null;

$p_chave=null;

$p_assunto=null;

$p_usu_resp=null;

$p_uorg_resp=null;

$p_palavra=null;

$p_prazo=null;

$p_fase=null;

$p_agrega=null;

$p_tamanho=null;

$p_projeto=null;

$p_atividade=null;

$p_sqcc=null;

$p_sq_acao_ppa=null;

$p_sq_orprioridade=null;

$p_mpog=null;

$p_relevante=null;


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
    if ($p_sqcc>"")
    {

      DB_GetCCData($RS,$p_sqcc);
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Classificação <td><font size=1>[<b>".$RS["nome"]."</b>]";
    } 

    if ($p_sq_acao_ppa>"")
    {

      DB_GetAcaoPPA($RS,$p_sq_acao_ppa,$w_cliente,null,null,null,null,null,null,null,null);
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Ação PPA <td><font size=1>[<b>".$RS["nome"]." (".$RS["codigo"].")"."</b>]";
    } 

    if ($p_sq_orprioridade>"")
    {

      DB_GetOrPrioridade($RS,null,$w_cliente,$p_sq_orprioridade,null,null,null);
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Iniciativa Prioritária <td><font size=1>[<b>".$RS["nome"]."</b>]";
    } 

    if ($p_chave>"")
    {
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Projeto nº <td><font size=1>[<b>".$p_chave."</b>]";
    }
;
    } 
    if ($p_prazo>"")
    {
      $w_filtro=$w_filtro." <tr valign=\"top\"><td align=\"right\"><font size=1>Prazo para conclusão até<td><font size=1>[<b>".$FormatDateTime[$DateAdd["d"][$p_prazo][time()()]][1]."</b>]";
    }
;
    } 
    if ($p_solicitante>"")
    {

      DB_GetPersonData($RS,$w_cliente,$p_solicitante,null,null);
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Responsável <td><font size=1>[<b>".$RS["nome_resumido"]."</b>]";
    } 

    if ($p_unidade>"")
    {

      DB_GetUorgData($RS,$p_unidade);
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Unidade responsável <td><font size=1>[<b>".$RS["nome"]."</b>]";
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

    if ($p_mpog>"")
    {
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Selecionada SE/MS <td><font size=1>[<b>".$p_relevante."</b>]";
    }
;
    } 
    if ($p_relevante>"")
    {
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Selecionada MP <td><font size=1>[<b>".$p_mpog."</b>]";
    }
;
    } 
    if ($p_proponente>"")
    {
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Parcerias externas<td><font size=1>[<b>".$p_proponente."</b>]";
    }
;
    } 
    if ($p_assunto>"")
    {
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Assunto <td><font size=1>[<b>".$p_assunto."</b>]";
    }
;
    } 
    if ($p_palavra>"")
    {
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Parcerias internas<td><font size=1>[<b>".$p_palavra."</b>]";
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
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Limite conclusão <td><font size=1>[<b>".$p_fim_i."-".$p_fim_f."</b>]";
    }
;
    } 
    if ($p_atraso=="S")
    {
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Situação <td><font size=1>[<b>Apenas atrasadas</b>]";
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
      case "GRPRPROJ":
        DB_GetSolicList($RS1,$P2,$w_usuario,$p_agrega,5,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,null,$p_ativo,$p_proponente);
        $w_TP=$TP." - Por projeto";
$RS1->sort="titulo";
        break;
      case "GRPRPROP":
        DB_GetSolicList($RS1,$P2,$w_usuario,$p_agrega,5,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,null,$p_ativo,$p_proponente);
        $w_TP=$TP." - Por parcerias externas";
$RS1->Filter="proponente <> null";
$RS1->sort="proponente";
        break;
      case "GRPRRESP":
        DB_GetSolicList($RS1,$P2,$w_usuario,$p_agrega,5,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,null,$p_ativo,$p_proponente);
        $w_TP=$TP." - Por responsável";
$RS1->sort="nm_solic";
        break;
      case "GRPRRESPATU":
        DB_GetSolicList($RS1,$P2,$w_usuario,$p_agrega,5,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,null,$p_ativo,$p_proponente);
        $w_TP=$TP." - Por executor";
$RS1->Filter="executor <> null";
$RS1->sort="nm_exec";
        break;
      case "GRPRCC":
        DB_GetSolicList($RS1,$P2,$w_usuario,$p_agrega,5,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,null,$p_ativo,$p_proponente);
        $w_TP=$TP." - Por classificação";
$RS1->sort="sg_cc";
        break;
      case "GRPRSETOR":
        $w_TP=$TP." - Por setor responsável";
        DB_GetSolicList($RS1,$P2,$w_usuario,$p_agrega,5,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,null,$p_ativo,$p_proponente);
$RS1->sort="nm_unidade_resp";
        break;
      case "GRPRAREA":
        $w_TP=$TP." - Por área";
        DB_GetSolicGRA($RS1,$P2,$w_usuario,$p_agrega,5,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,null,$p_ativo,$p_proponente);
$RS1->sort="nm_envolv";
        break;
      case "GRPRINTER":
        $w_TP=$TP." - Por interessado";
        DB_GetSolicGRI($RS1,$P2,$w_usuario,$p_agrega,5,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,null,$p_ativo,$p_proponente);
$RS1->sort="nm_inter";
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
//Validate "p_chave", "Número do projeto", "", "", "1", "18", "", "0123456789"

      Validate("p_prazo","Dias para a data limite","","","1","2","","0123456789");
      Validate("p_proponente","Proponente externo","","","2","90","1","");
//Validate "p_assunto", "Assunto", "", "", "2", "90", "1", "1"

      Validate("p_palavra","Palavras-chave","","","2","90","1","1");
      Validate("p_ini_i","Recebimento inicial","DATA","","10","10","","0123456789/");
      Validate("p_ini_f","Recebimento final","DATA","","10","10","","0123456789/");
      ShowHTML("  if ((theForm.p_ini_i.value != '' && theForm.p_ini_f.value == '') || (theForm.p_ini_i.value == '' && theForm.p_ini_f.value != '')) {");
      ShowHTML("     alert ('Informe ambas as datas de recebimento ou nenhuma delas!');");
      ShowHTML("     theForm.p_ini_i.focus();");
      ShowHTML("     return false;");
      ShowHTML("  }");
      CompData("p_ini_i","Recebimento inicial","<=","p_ini_f","Recebimento final");
      Validate("p_fim_i","Conclusão inicial","DATA","","10","10","","0123456789/");
      Validate("p_fim_f","Conclusão final","DATA","","10","10","","0123456789/");
      ShowHTML("  if ((theForm.p_fim_i.value != '' && theForm.p_fim_f.value == '') || (theForm.p_fim_i.value == '' && theForm.p_fim_f.value != '')) {");
      ShowHTML("     alert ('Informe ambas as datas de conclusão ou nenhuma delas!');");
      ShowHTML("     theForm.p_fim_i.focus();");
      ShowHTML("     return false;");
      ShowHTML("  }");
      CompData("p_fim_i","Conclusão inicial","<=","p_fim_f","Conclusão final");
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
// Se for recarga da página

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

      ShowHTML("      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=10 align=\"center\"><font size=\"1\"><b>Não foram encontrados registros.</b></td></tr>");
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
          case "GRPRPROJ":
            ShowHTML("      document.Form.p_projeto.value=filtro;");
            break;
          case "GRPRPROP":
            ShowHTML("      document.Form.p_proponente.value=filtro;");
            break;
          case "GRPRRESP":
            ShowHTML("      document.Form.p_solicitante.value=filtro;");
            break;
          case "GRPRRESPATU":
            ShowHTML("      document.Form.p_usu_resp.value=filtro;");
            break;
          case "GRPRCC":
            ShowHTML("      document.Form.p_sqcc.value=filtro;");
            break;
          case "GRPRSETOR":
            ShowHTML("      document.Form.p_unidade.value=filtro;");
            break;
          case "GRPRAREA":
            ShowHTML("      document.Form.p_area.value=filtro;");
            break;
          case "GRPRINTER":
            ShowHTML("      document.Form.p_inter.value=filtro;");
            break;
        } 
        ShowHTML("    }");
        switch ($p_agrega)
        {
          case "GRPRPROJ":
            ShowHTML("    else document.Form.p_projeto.value='".${"p_projeto"}."';");
            break;
          case "GRPRPROP":
            ShowHTML("    else document.Form.p_proponente.value=\"".${"p_proponente"}."\";");
            break;
          case "GRPRRESP":
            ShowHTML("    else document.Form.p_solicitante.value='".${"p_solicitante"}."';");
            break;
          case "GRPRRESPATU":
            ShowHTML("    else document.Form.p_usu_resp.value='".${"p_usu_resp"}."';");
            break;
          case "GRPRCC":
            ShowHTML("    else document.Form.p_sqcc.value='".${"p_sqcc"}."';");
            break;
          case "GRPRSETOR":
            ShowHTML("    else document.Form.p_unidade.value='".${"p_unidade"}."';");
            break;
          case "GRPRAREA":
            ShowHTML("    else document.Form.p_area.value='".${"p_area"}."';");
            break;
          case "GRPRINTER":
            ShowHTML("    else document.Form.p_inter.value='".${"p_inter"}."';");
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
          case "GRPRPROJ":
            if (${"p_projeto"}=="")
            {
              ShowHTML;
            }
("<input type=\"Hidden\" name=\"p_projeto\" value=\"\">");            } 

            break;
          case "GRPRPROP":
            if (${"p_proponente"}=="")
            {
              ShowHTML;
            }
("<input type=\"Hidden\" name=\"p_proponente\" value=\"\">");            } 

            break;
          case "GRPRRESP":
            if (${"p_solicitante"}=="")
            {
              ShowHTML;
            }
("<input type=\"Hidden\" name=\"p_solicitante\" value=\"\">");            } 

            break;
          case "GRPRRESPATU":
            if (${"p_usu_resp"}=="")
            {
              ShowHTML;
            }
("<input type=\"Hidden\" name=\"p_usu_resp\" value=\"\">");            } 

            break;
          case "GRPRCC":
            if (${"p_sqcc"}=="")
            {
              ShowHTML;
            }
("<input type=\"Hidden\" name=\"p_sqcc\" value=\"\">");            } 

            break;
          case "GRPRSETOR":
            if (${"p_unidade"}=="")
            {
              ShowHTML;
            }
("<input type=\"Hidden\" name=\"p_unidade\" value=\"\">");            } 

            break;
          case "GRPRAREA":
            if (${"p_area"}=="")
            {
              ShowHTML;
            }
("<input type=\"Hidden\" name=\"p_area\" value=\"\">");            } 

            break;
          case "GRPRINTER":
            if (${"p_inter"}=="")
            {
              ShowHTML;
            }
("<input type=\"Hidden\" name=\"p_inter\" value=\"\">");            } 

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
          case "GRPRPROJ":
            if ($w_nm_quebra!=$RS1["titulo"])
            {

              if ($w_qt_quebra>0)
              {

                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 

              if ($O!="W" || ($O=="W" && $w_linha<=25))
              {

// Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite

                ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["titulo"]);
              } 

              $w_nm_quebra=$RS1["titulo"];
              $w_chave=$RS1["sq_siw_solicitacao"];
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
          case "GRPRPROP":
            if ($w_nm_quebra!=$RS1["proponente"])
            {

              if ($w_qt_quebra>0)
              {

                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 

              if ($O!="W" || ($O=="W" && $w_linha<=25))
              {

// Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite

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
          case "GRPRRESP":
            if ($w_nm_quebra!=$RS1["nm_solic"])
            {

              if ($w_qt_quebra>0)
              {

                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 

              if ($O!="W" || ($O=="W" && $w_linha<=25))
              {

// Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite

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
          case "GRPRRESPATU":
            if ($w_nm_quebra!=$RS1["nm_exec"])
            {

              if ($w_qt_quebra>0)
              {

                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 

              if ($O!="W" || ($O=="W" && $w_linha<=25))
              {

// Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite

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
          case "GRPRCC":
            if ($w_nm_quebra!=$RS1["sg_cc"])
            {

              if ($w_qt_quebra>0)
              {

                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 

              if ($O!="W" || ($O=="W" && $w_linha<=25))
              {

// Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite

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
          case "GRPRSETOR":
            if ($w_nm_quebra!=$RS1["nm_unidade_resp"])
            {

              if ($w_qt_quebra>0)
              {

                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 

              if ($O!="W" || ($O=="W" && $w_linha<=25))
              {

// Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite

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
          case "GRPRAREA":
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
          case "GRPRINTER":
            if ($w_nm_quebra!=$RS1["nm_inter"])
            {

              if ($w_qt_quebra>0)
              {

                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 

              if ($O!="W" || ($O=="W" && $w_linha<=25))
              {

// Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite

                ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["nm_inter"]);
              } 

              $w_nm_quebra=$RS1["nm_inter"];
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
// Se for geração de MS-Word, quebra a página

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
            case "GRPRPROJ":
              ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["titulo"]);
              break;
            case "GRPRPROP":
              ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["proponente"]);
              break;
            case "GRPRRESP":
              ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["nm_solic"]);
              break;
            case "GRPRRESPATU":
              ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["nm_exec"]);
              break;
            case "GRPRCC":
              ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["sg_cc"]);
              break;
            case "GRPRSETOR":
              ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["nm_unidade_resp"]);
              break;
            case "GRPRAREA":
              ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["nm_envolv"]);
              break;
            case "GRPRINTER":
              ShowHTML("      <tr bgcolor=\"".$w_cor."\" valign=\"top\"><td><font size=1><b>".$RS1["nm_inter"]);
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

    ShowHTML("      </center>");
    ShowHTML("    </table>");
    ShowHTML("  </td>");
    ShowHTML("</tr>");

    if ($RS1->RecordCount>0 && $p_tipo=="N")
    {
// Coloca o gráfico somente se o usuário desejar

      ShowHTML("<tr><td align=\"center\" height=20>");
      ShowHTML("<tr><td align=\"center\"><IMG SRC=\"".$conPHP4."geragrafico.php?p_genero=M&p_objeto=".$RS_Menu["nome"]."&p_tipo=".$SG."&p_grafico=Barra&p_tot=".$t_totsolic."&p_cad=".$t_totcad."&p_tram=".$t_tottram."&p_conc=".$t_totconc."&p_atraso=".$t_totatraso."&p_aviso=".$t_totaviso."&p_acima=".$t_totacima."\">");
      ShowHTML("<tr><td align=\"center\" height=20>");
      if (($t_totcad+$t_tottram)>0)
      {

        ShowHTML("<tr><td align=\"center\"><IMG SRC=\"".$conPHP4."geragrafico.php?p_genero=M&p_objeto=".$RS_Menu["nome"]."&p_tipo=".$SG."&p_grafico=Pizza&p_tot=".$t_totsolic."&p_cad=".$t_totcad."&p_tram=".$t_tottram."&p_conc=".$t_totconc."&p_atraso=".$t_totatraso."&p_aviso=".$t_totaviso."&p_acima=".$t_totacima."\">");
      } 

    } 


  }
    else
  if ((strpos("P",$O) ? strpos("P",$O)+1 : 0)>0)
  {

    ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td><div align=\"justify\"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>");
    ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">");
    ShowHTML("      <tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\" valign=\"top\"><table border=0 width=\"90%\" cellspacing=0>");
    AbreForm("Form",$w_dir.$w_Pagina.$par,"POST","return(Validacao(this));",null,$P1,$P2,$P3,null,$TP,$SG,$R,"L");
    ShowHTML("<INPUT type=\"hidden\" name=\"w_troca\" value=\"\">");

// Exibe parâmetros de apresentação

    ShowHTML("         <tr><td colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Parâmetros de Apresentação</td>");
    ShowHTML("         <tr valign=\"top\"><td colspan=2><table border=0 width=\"100%\" cellpadding=0 cellspacing=0><tr valign=\"top\">");
    ShowHTML("          <td><font size=\"1\"><b><U>A</U>gregar por:<br><SELECT ACCESSKEY=\"A\" ".$w_Disabled." class=\"STS\" name=\"p_agrega\" size=\"1\">");
    switch ($p_agrega)
    {
      case :
        break;
      case "GRPRINTER":
        ShowHTML("          <option value=\"GRPRRESPATU\">Executor<option value=\"GRPRPROJ\">Ação<option value=\"GRPRPROP\">Parcerias externas<option value=\"GRPRRESP\">Responsável monitoramento<option value=\"GRPRSETOR\">Setor responsável monitoramento");
        break;
      case "GRPRPROJ":
        ShowHTML("          <option value=\"GRPRRESPATU\">Executor<option value=\"GRPRPROJ\" selected>Ação<option value=\"GRPRPROP\">Parcerias externas<option value=\"GRPRRESP\">Responsável monitoramento<option value=\"GRPRSETOR\">Setor responsável monitoramento");
        break;
      case "GRPRPROP":
        ShowHTML("          <option value=\"GRPRRESPATU\">Executor<option value=\"GRPRPROJ\">Ação<option value=\"GRPRPROP\" selected>Parcerias externas<option value=\"GRPRRESP\">Responsável monitoramento<option value=\"GRPRSETOR\">Setor responsável monitoramento");
        break;
      case "GRPRRESPATU":
        ShowHTML("          <option value=\"GRPRRESPATU\" selected>Executor<option value=\"GRPRPROJ\">Ação<option value=\"GRPRPROP\">Parcerias externas<option value=\"GRPRRESP\">Responsável monitoramento<option value=\"GRPRSETOR\">Setor responsável monitoramento");
        break;
      case "GRPRSETOR":
        ShowHTML("          <option value=\"GRPRRESPATU\">Executor<option value=\"GRPRPROJ\">Ação<option value=\"GRPRPROP\">Parcerias externas<option value=\"GRPRRESP\">Responsável monitoramento<option value=\"GRPRSETOR\" selected>Setor responsável monitoramento");
        break;
      default:
        ShowHTML("          <option value=\"GRPRRESPATU\">Executor<option value=\"GRPRPROJ\">Ação<option value=\"GRPRPROP\">Parcerias externas<option value=\"GRPRRESP\" selected>Responsável monitoramento<option value=\"GRPRSETOR\">Setor responsável monitoramento");
        break;
    } 
    ShowHTML("          </select></td>");
    MontaRadioNS("<b>Inibe exibição do gráfico?</b>",$p_tipo,"p_tipo");
    MontaRadioSN("<b>Limita tamanho do assunto?</b>",$p_tamanho,"p_tamanho");
    ShowHTML("           </table>");
    ShowHTML("         </tr>");
    ShowHTML("         <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>Critérios de Busca</td>");

    ShowHTML("      <tr><td colspan=2><table border=0 width=\"90%\" cellspacing=0><tr valign=\"top\">");
    $p_sq_acao_ppa="";
    SelecaoAcaoPPA("Ação <u>P</u>PA:","P",null,$p_sq_acao_ppa,null,"p_sq_acao_ppa","IDENTIFICACAO",null);
    ShowHTML("          </table>");
    ShowHTML("      <tr><td colspan=2><table border=0 width=\"90%\" cellspacing=0><tr valign=\"top\">");
    SelecaoOrPrioridade("<u>I</u>niciativa prioritária:","I",null,$p_sq_orprioridade,null,"p_sq_orprioridade",null,null);
    ShowHTML("          </table>");
    ShowHTML("      <tr valign=\"top\">");
//ShowHTML "          <td valign=""top""><font size=""1""><b>Número da <U>a</U>ção:<br><INPUT ACCESSKEY=""A"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_chave"" size=""18"" maxlength=""18"" value=""" & p_chave & """></td>"

//ShowHTML "          <td valign=""top""><font size=""1"">"

    ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY=\"T\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"p_prazo\" size=\"2\" maxlength=\"2\" value=\"".$p_prazo."\"></td>");
    ShowHTML("      <tr valign=\"top\">");
    SelecaoPessoa("Respo<u>n</u>sável monitoramento:","N","Selecione o responsável pelo monitoramento da ação na relação.",$p_solicitante,null,"p_solicitante","USUARIOS");
    SelecaoUnidade("Setor responsável monitoramento:","Y",null,$p_unidade,null,"p_unidade",null,null);
    ShowHTML("      <tr valign=\"top\">");
    SelecaoPessoa("E<u>x</u>ecutor:","X","Selecione o executor da ação na relação.",$p_usu_resp,null,"p_usu_resp","USUARIOS");
    SelecaoUnidade("Setor atual:","Y","Selecione a unidade onde a ação se encontra na relação.",$p_uorg_resp,null,"p_uorg_resp",null,null);
//ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100""" cellspacing=0>"

//MontaRadioNS "<b>Selecionada pelo MP?</b>", p_mpog, "w_selecionada_mpog"

//MontaRadioNS "<b>SE/MS?</b>", p_relevante, "w_selecionada_relevante"

//ShowHTML "</table>"

    ShowHTML("      <tr>");
    ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>Parc<U>e</U>rias externas:<br><INPUT ACCESSKEY=\"N\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"p_proponente\" size=\"25\" maxlength=\"90\" value=\"".$p_proponente."\"></td>");
    ShowHTML("          <td valign=\"top\" colspan=2><font size=\"1\"><b>Par<U>c</U>erias internas:<br><INPUT ACCESSKEY=\"N\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"p_palavra\" size=\"25\" maxlength=\"90\" value=\"".$p_palavra."\"></td>");
//ShowHTML "      <tr>"

//ShowHTML "          <td valign=""top""><font size=""1""><b>Açã<U>o</U>:<br><INPUT ACCESSKEY=""O"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_assunto"" size=""25"" maxlength=""90"" value=""" & p_assunto & """></td>"

    ShowHTML("      <tr>");
    ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>Data de re<u>c</u>ebimento entre:</b><br><input ".$w_Disabled." accesskey=\"C\" type=\"text\" name=\"p_ini_i\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$p_ini_i."\" onKeyDown=\"FormataData(this,event);\"> e <input ".$w_Disabled." accesskey=\"C\" type=\"text\" name=\"p_ini_f\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$p_ini_f."\" onKeyDown=\"FormataData(this,event);\"></td>");
    ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>Limi<u>t</u>e para conclusão entre:</b><br><input ".$w_Disabled." accesskey=\"T\" type=\"text\" name=\"p_fim_i\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$p_fim_i."\" onKeyDown=\"FormataData(this,event);\"> e <input ".$w_Disabled." accesskey=\"T\" type=\"text\" name=\"p_fim_f\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$p_fim_f."\" onKeyDown=\"FormataData(this,event);\"></td>");
    ShowHTML("      <tr>");
    ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>Exibe somente ações em atraso?</b><br>");
    if ($p_atraso=="S")
    {

      ShowHTML("              <input ".$w_Disabled." class=\"STR\" type=\"radio\" name=\"p_atraso\" value=\"S\" checked> Sim <br><input ".$w_Disabled." class=\"STR\" class=\"STR\" type=\"radio\" name=\"p_atraso\" value=\"N\"> Não");
    }
      else
    {

      ShowHTML("              <input ".$w_Disabled." class=\"STR\" type=\"radio\" name=\"p_atraso\" value=\"S\"> Sim <br><input ".$w_Disabled." class=\"STR\" class=\"STR\" type=\"radio\" name=\"p_atraso\" value=\"N\" checked> Não");
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
    ShowHTML(" alert('Opção não disponível');");
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
    case "GRPRPROJ":
      ShowHTML("          <td><font size=\"1\"><b>Projeto</font></td>");
      break;
    case "GRPRPROP":
      ShowHTML("          <td><font size=\"1\"><b>Proponente</font></td>");
      break;
    case "GRPRRESP":
      ShowHTML("          <td><font size=\"1\"><b>Responsável</font></td>");
      break;
    case "GRPRRESPATU":
      ShowHTML("          <td><font size=\"1\"><b>Executor</font></td>");
      break;
    case "GRPRCC":
      ShowHTML("          <td><font size=\"1\"><b>Classificação</font></td>");
      break;
    case "GRPRSETOR":
      ShowHTML("          <td><font size=\"1\"><b>Setor responsável</font></td>");
//Case "GRPRPRIO"    ShowHTML "          <td><font size=""1""><b>Prioridade</font></td>"

//Case "GRPRLOCAL"   ShowHTML "          <td><font size=""1""><b>UF</font></td>"

      break;
    case "GRPRAREA":
      ShowHTML("          <td><font size=\"1\"><b>Área envolvida</font></td>");
      break;
    case "GRPRINTER":
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
    ShowHTML("          <td align=\"right\"><font size=\"1\"><a class=\"hl\" href=\"javascript:lista('".$p_chave."', -1, -1, -1, -1);\" onMouseOver=\"window.status='Exibe as ações.'; return true\" onMouseOut=\"window.status=''; return true\">".$FormatNumber[$p_solic][0]."</a>&nbsp;</font></td>");  } 
}
  else
{
  ShowHTML;
}
("          <td align=\"right\"><font size=\"1\">".$FormatNumber[$p_solic][0]."&nbsp;</font></td>");
  if ($p_cad>0 && $O=="L")
  {
    ShowHTML("          <td align=\"right\"><a class=\"hl\" href=\"javascript:lista('".$p_chave."', 0, -1, -1, -1);\" onMouseOver=\"window.status='Exibe as ações.'; return true\" onMouseOut=\"window.status=''; return true\"><font size=\"1\">".$FormatNumber[$p_cad][0]."</a>&nbsp;</font></td>");  } 
}
  else
{
  ShowHTML;
}
("          <td align=\"right\"><font size=\"1\">".$FormatNumber[$p_cad][0]."&nbsp;</font></td>");
  if ($p_tram>0 && $O=="L")
  {
    ShowHTML("          <td align=\"right\"><a class=\"hl\" href=\"javascript:lista('".$p_chave."', -1, 0, -1, -1);\" onMouseOver=\"window.status='Exibe as ações.'; return true\" onMouseOut=\"window.status=''; return true\"><font size=\"1\">".$FormatNumber[$p_tram][0]."</a>&nbsp;</font></td>");  } 
}
  else
{
  ShowHTML;
}
("          <td align=\"right\"><font size=\"1\">".$FormatNumber[$p_tram][0]."&nbsp;</font></td>");
  if ($p_conc>0 && $O=="L")
  {
    ShowHTML("          <td align=\"right\"><a class=\"hl\" href=\"javascript:lista('".$p_chave."', -1, -1, 0, -1);\" onMouseOver=\"window.status='Exibe as ações.'; return true\" onMouseOut=\"window.status=''; return true\"><font size=\"1\">".$FormatNumber[$p_conc][0]."</a>&nbsp;</font></td>");  } 
}
  else
{
  ShowHTML;
}
("          <td align=\"right\"><font size=\"1\">".$FormatNumber[$p_conc][0]."&nbsp;</font></td>");
  if ($p_atraso>0 && $O=="L")
  {
    ShowHTML("          <td align=\"right\"><a class=\"hl\" href=\"javascript:lista('".$p_chave."', -1, -1, -1, 0);\" onMouseOver=\"window.status='Exibe as ações.'; return true\" onMouseOut=\"window.status=''; return true\"><font size=\"1\" color=\"red\"><b>".$FormatNumber[$p_atraso][0]."</a>&nbsp;</font></td>");  } 
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


// Verifica se o usuário tem lotação e localização

  if ((strlen($LOTACAO_session."")==0 || strlen($LOCALIZACAO_session."")==0) && $LogOn_session=="Sim")
  {

    ScriptOpen("JavaScript");
    ShowHTML(" alert('Você não tem lotação ou localização definida. Entre em contato com o RH!'); ");
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
      ShowHTML("<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=\"images/icone/underc.gif\" align=\"center\"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>");
      Rodape();
      break;
  } 
  return $function_ret;
} 
// =========================================================================

// Fim da rotina principal

// -------------------------------------------------------------------------

?>


