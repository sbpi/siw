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
  session_register("nome_session");
  session_register("Username_session");
  session_register("lotacao_session");
  session_register("LOTACAO_session");
  session_register("LOCALIZACAO_session");
?>
<? // asp2php (vbscript) converted
?>
<? // Option $Explicit; ?>
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
<!-- #INCLUDE FILE="VisualDemanda.php" -->
<!-- #INCLUDE FILE="Funcoes.php" -->
<!-- #INCLUDE FILE="DB_Tabelas.php" -->
<? 
header("Expires: ".-1500);
// =========================================================================

//  /projetoativ.asp

// ------------------------------------------------------------------------

// Nome     : Alexandre Vinhadelli Papadópolis

// Descricao: Gerencia o módulo de demandas

// Mail     : alex@sbpi.com.br

// Criacao  : 15/10/2003 12:25

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

if ($LogOn_session!="Sim")
{

  EncerraSessao();
} 


// Declaração de variáveis

// $RS is of type "ADODB.RecordSet"

// $RS1 is of type "ADODB.RecordSet"

// $RS2 is of type "ADODB.RecordSet"

// $RS3 is of type "ADODB.RecordSet"

// $RS4 is of type "ADODB.RecordSet"

// $RS_Menu is of type "ADODB.RecordSet"



AbreSessao();

// Carrega variáveis locais com os dados dos parâmetros recebidos

$Par=strtoupper(${"Par"});
$w_pagina="projetoativ.asp?par=";
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
  $p_projeto=strtoupper($ul->Texts.$Item["p_projeto"]);
  $p_atividade=strtoupper($ul->Texts.$Item["p_atividade"]);
  $p_ativo=strtoupper($ul->Texts.$Item["p_ativo"]);
  $p_solicitante=strtoupper($ul->Texts.$Item["p_solicitante"]);
  $p_prioridade=strtoupper($ul->Texts.$Item["p_prioridade"]);
  $p_unidade=strtoupper($ul->Texts.$Item["p_unidade"]);
  $p_proponente=strtoupper($ul->Texts.$Item["p_proponente"]);
  $p_ordena=strtoupper($ul->Texts.$Item["p_ordena"]);
  $p_ini_i=strtoupper($ul->Texts.$Item["p_ini_i"]);
  $p_ini_f=strtoupper($ul->Texts.$Item["p_ini_f"]);
  $p_fim_i=strtoupper($ul->Texts.$Item["p_fim_i"]);
  $p_fim_f=strtoupper($ul->Texts.$Item["p_fim_f"]);
  $p_atraso=strtoupper($ul->Texts.$Item["p_atraso"]);
  $p_chave=strtoupper($ul->Texts.$Item["p_chave"]);
  $p_assunto=strtoupper($ul->Texts.$Item["p_assunto"]);
  $p_pais=strtoupper($ul->Texts.$Item["p_pais"]);
  $p_regiao=strtoupper($ul->Texts.$Item["p_regiao"]);
  $p_uf=strtoupper($ul->Texts.$Item["p_uf"]);
  $p_cidade=strtoupper($ul->Texts.$Item["p_cidade"]);
  $p_usu_resp=strtoupper($ul->Texts.$Item["p_usu_resp"]);
  $p_uorg_resp=strtoupper($ul->Texts.$Item["p_uorg_resp"]);
  $p_palavra=strtoupper($ul->Texts.$Item["p_palavra"]);
  $p_prazo=strtoupper($ul->Texts.$Item["p_prazo"]);
  $p_fase=strtoupper($ul->Texts.$Item["p_fase"]);
  $p_sqcc=strtoupper($ul->Texts.$Item["p_sqcc"]);

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
  $p_projeto=strtoupper(${"p_projeto"});
  $p_atividade=strtoupper(${"p_atividade"});
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
  $p_pais=strtoupper(${"p_pais"});
  $p_regiao=strtoupper(${"p_regiao"});
  $p_uf=strtoupper(${"p_uf"});
  $p_cidade=strtoupper(${"p_cidade"});
  $p_usu_resp=strtoupper(${"p_usu_resp"});
  $p_uorg_resp=strtoupper(${"p_uorg_resp"});
  $p_palavra=strtoupper(${"p_palavra"});
  $p_prazo=strtoupper(${"p_prazo"});
  $p_fase=strtoupper(${"p_fase"});
  $p_sqcc=strtoupper(${"p_sqcc"});

  $P1=Nvl(${"P1"},0);
  $P2=Nvl(${"P2"},0);
  $P3=$cDbl[Nvl(${"P3"},1)];
  $P4=$cDbl[Nvl(${"P4"},$conPagesize)];
  $TP=${"TP"};
  $R=strtoupper(${"R"});
  $w_Assinatura=strtoupper(${"w_Assinatura"});

  if ($SG=="ORPANEXO" || $SG=="ORPINTERES" || $SG=="ORPAREAS")
  {

    if ($O!="I" && ${"w_chave_aux"}=="")
    {
      $O="L";
    }
;
    } 
  }
    else
  if ($SG=="ORPENVIO")
  {

    $O="V";
  }
    else
  if ($O=="")
  {

// Se for acompanhamento, entra na filtragem  

    if ($P1==3)
    {
      $O="P";
    }
      else
    {
      $O="L";
    }
;
  } 
} 

} 


switch ($O)
{
case "I":
  $w_TP=$TP." - Inclusão";
  break;
case "A":
  $w_TP=$TP." - Alteração";
  break;
case "E":
  $w_TP=$TP." - Exclusão";
  break;
case "P":
  $w_TP=$TP." - Filtragem";
  break;
case "C":
  $w_TP=$TP." - Cópia";
  break;
case "V":
  $w_TP=$TP." - Envio";
  break;
case "H":
  $w_TP=$TP." - Herança";
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

// Recupera a configuração do serviço

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
) // Se for sub-menu, pega a configuração do pai
{

DB_GetMenuData($RS_menu["sq_menu_pai"]);
} 


Main();

FechaSessao();

$UploadID=null;

$w_dir=null;

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

$p_atividade=null;

$p_ordena=null;

$p_chave=null;

$p_assunto=null;

$p_pais=null;

$p_regiao=null;

$p_uf=null;

$p_cidade=null;

$p_usu_resp=null;

$p_uorg_resp=null;

$p_palavra=null;

$p_prazo=null;

$p_fase=null;

$p_sqcc=null;


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

$w_pagina=null;

$w_Disabled=null;

$w_TP=null;

$w_Assinatura=null;

$w_dir=null;

$w_dir_volta=null;


// =========================================================================

// Rotina de visualização resumida dos registros

// -------------------------------------------------------------------------

function Inicial()
{
  extract($GLOBALS);




if ($O=="L")
{

  if ((strpos(strtoupper($R),"GR_") ? strpos(strtoupper($R),"GR_")+1 : 0)>0 || (strpos(strtoupper($R),"PROJETO") ? strpos(strtoupper($R),"PROJETO")+1 : 0)>0)
  {

    $w_filtro="";
    if ($p_projeto>"")
    {

      DB_GetSolicData($RS,$p_projeto,"PJGERAL");
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Ação <td><font size=1>[<b><A class=\"HL\" HREF=\"".$w_dir."Projeto.asp?par=Visual&O=L&w_chave=".$p_projeto."&w_tipo=Volta&P1=2&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\" title=\"Exibe as informações do projeto.\">".$RS["titulo"]."</a></b>]";
    } 

    if ($p_atividade>"")
    {

      DB_GetSolicEtapa($RS,$p_projeto,$p_atividade,"REGISTRO",null);
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Etapa <td><font size=1>[<b>".MontaOrdemEtapa($RS["sq_projeto_etapa"])." - ".$RS["titulo"]."</b>]";
    } 

    if ($p_sqcc>"")
    {

      DB_GetCCData($RS,$p_sqcc);
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Classificação <td><font size=1>[<b>".$RS["nome"]."</b>]";
    } 

    if ($p_chave>"")
    {
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Atividade nº <td><font size=1>[<b>".$p_chave."</b>]";
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

    if ($p_pais>"")
    {

      DB_GetCountryData($RS,$p_pais);
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>País <td><font size=1>[<b>".$RS["nome"]."</b>]";
    } 

    if ($p_regiao>"")
    {

      DB_GetRegionData($RS,$p_regiao);
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Região <td><font size=1>[<b>".$RS["nome"]."</b>]";
    } 

    if ($p_uf>"")
    {

      DB_GetStateData($RS,$p_pais,$p_uf);
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Estado <td><font size=1>[<b>".$RS["nome"]."</b>]";
    } 

    if ($p_cidade>"")
    {

      DB_GetCityData($RS,$p_cidade);
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Cidade <td><font size=1>[<b>".$RS["nome"]."</b>]";
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
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Responsável <td><font size=1>[<b>".$p_palavra."</b>]";
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
  } 


  DB_GetLinkData($RS,$w_cliente,"ORPCAD");
  if ($w_copia>"")
  {
// Se for cópia, aplica o filtro sobre todas as demandas visíveis pelo usuário

    DB_GetSolicList($rs,$RS["sq_menu"],$w_usuario,$SG,3,
    $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
    $p_unidade,$p_prioridade,$p_ativo,$p_proponente);
  }
    else
  {

    DB_GetSolicList($rs,$RS["sq_menu"],$w_usuario,$SG,$P1,
    $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
    $p_unidade,$p_prioridade,$p_ativo,$p_proponente);
    switch (${"p_agrega"})
    {
      case "GRDMRESPATU":
$RS->Filter="executor <> null";
        break;
    } 
  } 


  if ($p_ordena>"")
  {
$RS->sort=$p_ordena;
  }
    else
  {
$RS->sort="ordem, fim, prioridade";
  }
;
} 
} 


Cabecalho();
ShowHTML("<HEAD>");
if ($P1==2)
{
ShowHTML;
}
("<meta http-equiv=\"Refresh\" content=\"300; URL=../".MontaURL("MESA")."\">");} 

ShowHTML("<TITLE>".$conSgSistema." - Listagem de atividades</TITLE>");
ScriptOpen("Javascript");
CheckBranco();
FormataData();
ValidateOpen("Validacao");
if ((strpos("CP",$O) ? strpos("CP",$O)+1 : 0)>0)
{

if ($P1!=1 || $O=="C")
{
// Se não for cadastramento ou se for cópia

  Validate("p_chave","Número da tarefa","","","1","18","","0123456789");
  Validate("p_prazo","Dias para a data limite","","","1","2","","0123456789");
  Validate("p_proponente","Parcerias externas","","","2","90","1","");
  Validate("p_assunto","Assunto","","","2","90","1","1");
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
} 

Validate("P4","Linhas por página","1","1","1","4","","0123456789");
} 

ValidateClose();
ScriptClose();
ShowHTML("</HEAD>");
ShowHTML("<BASE HREF=\"".$conRootSIW."\">");
if ($w_Troca>"")
{
// Se for recarga da página

BodyOpen("onLoad='document.Form.".$w_Troca.".focus();'");
}
  else
if ($O=="I")
{

BodyOpen("onLoad='document.Form.w_smtp_server.focus();'");
}
  else
if ($O=="A")
{

BodyOpen("onLoad='document.Form.w_nome.focus();'");
}
  else
if ($O=="E")
{

BodyOpen("onLoad='document.Form.w_assinatura.focus()';");
}
  else
if ((strpos("CP",$O) ? strpos("CP",$O)+1 : 0)>0)
{

BodyOpen("onLoad='document.Form.p_projeto.focus()';");
}
  else
{

BodyOpen("onLoad=document.focus();");
} 

ShowHTML("<B><FONT COLOR=\"#000000\">".$w_TP."</FONT></B>");
ShowHTML("<HR>");
if ($w_filtro>"")
{
ShowHTML;
}
($w_filtro);} 

ShowHTML("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">");
if ($O=="L")
{

ShowHTML("<tr><td><font size=\"1\">");
if ($P1==1 && $w_copia=="")
{
// Se for cadastramento e não for resultado de busca para cópia

  if ($w_submenu>"")
  {

    DB_GetLinkSubMenu($RS1,$w_cliente,${"SG"});
    ShowHTML("<tr><td><font size=\"1\">");
    ShowHTML("    <a accesskey=\"I\" class=\"SS\" href=\"".$w_dir.$w_pagina."Geral&R=".$w_pagina.$par."&O=I&SG=".$RS1["sigla"]."&w_menu=".$w_menu."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP.MontaFiltro("GET")."\"><u>I</u>ncluir</a>&nbsp;");
    ShowHTML("    <a accesskey=\"C\" class=\"SS\" href=\"".$w_dir.$w_pagina.$par."&R=".$w_pagina.$par."&O=C&P1=".$P1."&P2=".$P2."&P3=1&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\"><u>C</u>opiar</a>");
  }
    else
  {

    ShowHTML("<tr><td><font size=\"1\"><a accesskey=\"I\" class=\"SS\" href=\"".$w_dir.$w_pagina.$par."&R=".$w_pagina.$par."&O=I&P1=".$P1."&P2=".$P2."&P3=1&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\"><u>I</u>ncluir</a>&nbsp;");
  } 

} 

if ((strpos(strtoupper($R),"GR_") ? strpos(strtoupper($R),"GR_")+1 : 0)==0 && (strpos(strtoupper($R),"PROJETO") ? strpos(strtoupper($R),"PROJETO")+1 : 0)==0)
{

  if ($w_copia>"")
  {
// Se for cópia

    if (MontaFiltro("GET")>"")
    {

      ShowHTML("                         <a accesskey=\"F\" class=\"SS\" href=\"".$w_dir.$w_pagina.$par."&R=".$w_pagina.$par."&O=C&P1=".$P1."&P2=".$P2."&P3=1&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\"><u><font color=\"#BC5100\">F</u>iltrar (Ativo)</font></a>");
    }
      else
    {

      ShowHTML("                         <a accesskey=\"F\" class=\"SS\" href=\"".$w_dir.$w_pagina.$par."&R=".$w_pagina.$par."&O=C&P1=".$P1."&P2=".$P2."&P3=1&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\"><u>F</u>iltrar (Inativo)</a>");
    } 

  }
    else
  {

    if (MontaFiltro("GET")>"")
    {

      ShowHTML("                         <a accesskey=\"F\" class=\"SS\" href=\"".$w_dir.$w_pagina.$par."&R=".$w_pagina.$par."&O=P&P1=".$P1."&P2=".$P2."&P3=1&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\"><u><font color=\"#BC5100\">F</u>iltrar (Ativo)</font></a>");
    }
      else
    {

      ShowHTML("                         <a accesskey=\"F\" class=\"SS\" href=\"".$w_dir.$w_pagina.$par."&R=".$w_pagina.$par."&O=P&P1=".$P1."&P2=".$P2."&P3=1&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\"><u>F</u>iltrar (Inativo)</a>");
    } 

  } 

} 

ShowHTML("    <td align=\"right\"><font size=\"1\"><b>Registros: ".$RS->RecordCount);
ShowHTML("<tr><td align=\"center\" colspan=3>");
ShowHTML("    <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">");
ShowHTML("        <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">");
ShowHTML("          <td><font size=\"1\"><b>Nº</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Ação</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Responsável</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Executor</font></td>");
if ($P1==1 || $P1==2)
{
// Se for cadastramento ou mesa de trabalho

  ShowHTML("          <td><font size=\"1\"><b>Detalhamento</font></td>");
  ShowHTML("          <td><font size=\"1\"><b>Fim previsto</font></td>");
}
  else
{

  ShowHTML("          <td><font size=\"1\"><b>Parcerias</font></td>");
  ShowHTML("          <td><font size=\"1\"><b>Detalhamento</font></td>");
  ShowHTML("          <td><font size=\"1\"><b>Fim previsto</font></td>");
  ShowHTML("          <td><font size=\"1\"><b>Valor</font></td>");
  ShowHTML("          <td><font size=\"1\"><b>Fase atual</font></td>");
} 

ShowHTML("          <td><font size=\"1\"><b>Operações</font></td>");
ShowHTML("        </tr>");
if ($RS->EOF)
{

  ShowHTML("      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=10 align=\"center\"><font size=\"1\"><b>Não foram encontrados registros.</b></td></tr>");
}
  else
{

$rs->PageSize=$P4;
$rs->AbsolutePage=$P3;
  $w_parcial=0;
  while(!$RS->EOF && $RS->AbsolutePage==$P3)
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
  ShowHTML("        <td nowrap><font size=\"1\">");
  if ($RS["concluida"]=="N")
  {

    if ($RS["fim"]<time()())
    {

      ShowHTML("           <img src=\"".$conImgAtraso."\" border=0 width=15 heigth=15 align=\"center\">");
    }
      else
    if ($RS["aviso_prox_conc"]=="S" && ($RS["aviso"]<=time()()))
    {

      ShowHTML("           <img src=\"".$conImgAviso."\" border=0 width=15 height=15 align=\"center\">");
    }
      else
    {

      ShowHTML("           <img src=\"".$conImgNormal."\" border=0 width=15 height=15 align=\"center\">");
    } 

  }
    else
  {

    if ($RS["fim"]<Nvl($RS["fim_real"],$RS["fim"]))
    {

      ShowHTML("           <img src=\"".$conImgOkAtraso."\" border=0 width=15 heigth=15 align=\"center\">");
    }
      else
    {

      ShowHTML("           <img src=\"".$conImgOkNormal."\" border=0 width=15 height=15 align=\"center\">");
    } 

  } 

  ShowHTML("        <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."Visual&R=".$w_pagina.$par."&O=L&w_chave=".$RS["sq_siw_solicitacao"]."&w_tipo=Volta&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Exibe as informações deste registro.\">".$RS["sq_siw_solicitacao"]."&nbsp;</a>");
  ShowHTML("        <td><font size=\"1\"><A class=\"HL\" HREF=\"".$w_dir."Projeto.asp?par=Visual&O=L&w_chave=".$RS["sq_solic_pai"]."&w_tipo=Volta&P1=2&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\" title=\"Exibe as informações do projeto.\">".$RS["nm_projeto"]."</a></td>");
  ShowHTML("        <td><font size=\"1\">".ExibePessoa("../",$w_cliente,$RS["solicitante"],$TP,$RS["nm_solic"])."</td>");
  ShowHTML("        <td><font size=\"1\">".ExibePessoa("../",$w_cliente,$RS["executor"],$TP,$RS["nm_exec"])."</td>");
  if ($P1!=1 && $P1!=2)
  {
// Se não for cadastramento nem mesa de trabalho

    ShowHTML("        <td><font size=\"1\">".Nvl($RS["proponente"],"---")."</td>");
  } 

// Verifica se foi enviado o parâmetro p_tamanho = N. Se chegou, o assunto deve ser exibido sem corte.

// Este parâmetro é enviado pela tela de filtragem das páginas gerenciais

  if (${"p_tamanho"}=="N")
  {

    ShowHTML("        <td><font size=\"1\">".Nvl($RS["assunto"],"-")."</td>");
  }
    else
  {

//If Len(Nvl(RS("assunto"),"-")) > 50 Then w_titulo = Mid(Nvl(RS("assunto"),"-"),1,50) & "..." Else w_titulo = Nvl(RS("assunto"),"-") End If

    if ($RS["sg_tramite"]=="CA")
    {

//, "\'"), """", "\'"),VbCrLf,"\n") & """><font size=""1""><strike>" & w_titulo & "</strike></td>"      ShowHTML("        <td><font size=\"1\"><strike>".Nvl($RS["assunto"],"-")."</strike></td>");
    }
      else
    {

//, "\'"), """", "\'"),VbCrLf,"\n") & """><font size=""1"">" & w_titulo & "</td>"      ShowHTML("        <td><font size=\"1\">".Nvl($RS["assunto"],"-")."</td>");
    } 

  } 

  ShowHTML("        <td align=\"center\"><font size=\"1\">&nbsp;".Nvl($FormatDateTime[$RS["fim"]][2],"-")."</td>");
  if ($P1!=1 && $P1!=2)
  {
// Se não for cadastramento nem mesa de trabalho

    if ($RS["sg_tramite"]=="AT")
    {

      ShowHTML("        <td align=\"right\"><font size=\"1\">".$FormatNumber[$RS["custo_real"]][2]."&nbsp;</td>");
      $w_parcial=$w_parcial+$cDbl[$RS["custo_real"]];
    }
      else
    {

      ShowHTML("        <td align=\"right\"><font size=\"1\">".$FormatNumber[$RS["valor"]][2]."&nbsp;</td>");
      $w_parcial=$w_parcial+$cDbl[$RS["valor"]];
    } 

    ShowHTML("        <td nowrap><font size=\"1\">".$RS["nm_tramite"]."</td>");
  } 

  ShowHTML("        <td align=\"top\" nowrap><font size=\"1\">");
  if ($P1!=3)
  {
// Se não for acompanhamento

    if ($w_copia>"")
    {
// Se for listagem para cópia

      DB_GetLinkSubMenu($RS1,$w_cliente,${"SG"});
      ShowHTML("          <a accesskey=\"I\" class=\"HL\" href=\"".$w_dir.$w_pagina."Geral&R=".$w_pagina.$par."&O=I&SG=".$RS1["sigla"]."&w_menu=".$w_menu."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&w_copia=".$RS["sq_siw_solicitacao"].MontaFiltro("GET")."\">Copiar</a>&nbsp;");
    }
      else
    if ($P1==1)
    {
// Se for cadastramento

      if ($w_submenu>"")
      {

        ShowHTML("          <A class=\"HL\" HREF=\"Menu.asp?par=ExibeDocs&O=A&w_chave=".$RS["sq_siw_solicitacao"]."&R=".$w_pagina.$par."&SG=".$SG."&TP=".$TP."&w_documento=Nr. ".$RS["sq_siw_solicitacao"].MontaFiltro("GET")."\" title=\"Altera as informações cadastrais da tarefa\" TARGET=\"menu\">Alterar</a>&nbsp;");
      }
        else
      {

        ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina.$par."&R=".$w_pagina.$par."&O=A&w_chave=".$RS["sq_siw_solicitacao"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Altera as informações cadastrais da tarefa\">Alterar</A>&nbsp");
      } 

      ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."Excluir&R=".$w_pagina.$par."&O=E&w_chave=".$RS["sq_siw_solicitacao"]."&w_tipo=Volta&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Exclusão da tarefa.\">Excluir</A>&nbsp");
    }
      else
    if ($P1==2)
    {
// Se for execução

      if ($cDbl[$w_usuario]==$cDbl[$RS["executor"]])
      {

        ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."Anotacao&R=".$w_pagina.$par."&O=V&w_chave=".$RS["sq_siw_solicitacao"]."&w_tipo=Volta&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Registra anotações para a tarefa, sem enviá-la.\">Anotar</A>&nbsp");
        ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."envio&R=".$w_pagina.$par."&O=V&w_chave=".$RS["sq_siw_solicitacao"]."&w_tipo=Volta&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Envia a tarefa para outro responsável.\">Enviar</A>&nbsp");
        ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."Concluir&R=".$w_pagina.$par."&O=V&w_chave=".$RS["sq_siw_solicitacao"]."&w_tipo=Volta&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Conclui a execução da tarefa.\">Concluir</A>&nbsp");
      }
        else
      {

        ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."envio&R=".$w_pagina.$par."&O=V&w_chave=".$RS["sq_siw_solicitacao"]."&w_tipo=Volta&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Envia a tarefa para outro responsável.\">Enviar</A>&nbsp");
      } 

    } 

  }
    else
  {

    if ($cDbl[Nvl($RS["solicitante"],0)]==$cDbl[$w_usuario] || 
       $cDbl[Nvl($RS["titular"],0)]==$cDbl[$w_usuario] || 
       $cDbl[Nvl($RS["substituto"],0)]==$cDbl[$w_usuario] || 
       $cDbl[Nvl($RS["tit_exec"],0)]==$cDbl[$w_usuario] || 
       $cDbl[Nvl($RS["subst_exec"],0)]==$cDbl[$w_usuario]
       )
    {

      ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."envio&R=".$w_pagina.$par."&O=V&w_chave=".$RS["sq_siw_solicitacao"]."&w_tipo=Volta&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Envia a tarefa para outro responsável.\">Enviar</A>&nbsp");
    }
      else
    {

      ShowHTML("          ---&nbsp");
    } 

  } 

  ShowHTML("        </td>");
  ShowHTML("      </tr>");
$RS->MoveNext;
} 

if ($P1!=1 && $P1!=2)
{
// Se não for cadastramento nem mesa de trabalho

// Coloca o valor parcial apenas se a listagem ocupar mais de uma página

  if ($RS->PageCount>1)
  {

    ShowHTML("        <tr bgcolor=\"".$conTrBgColor."\">");
    ShowHTML("          <td colspan=7 align=\"right\"><font size=\"1\"><b>Total desta página&nbsp;</font></td>");
    ShowHTML("          <td align=\"right\"><font size=\"1\"><b>".$FormatNumber[$w_parcial][2]."&nbsp;</font></td>");
    ShowHTML("          <td colspan=2><font size=\"1\">&nbsp;</font></td>");
    ShowHTML("        </tr>");
  } 


// Se for a última página da listagem, soma e exibe o valor total

  if ($P3==$RS->PageCount)
  {

$RS->MoveFirst;
    while(!$RS->EOF)
    {

      if ($RS["sg_tramite"]=="AT")
      {

        $w_total=$w_total+$cDbl[$RS["custo_real"]];
      }
        else
      {

        $w_total=$w_total+$cDbl[$RS["valor"]];
      } 

$RS->MoveNext;
    } 
    ShowHTML("        <tr bgcolor=\"".$conTrBgColor."\">");
    ShowHTML("          <td colspan=7 align=\"right\"><font size=\"1\"><b>Total da listagem&nbsp;</font></td>");
    ShowHTML("          <td align=\"right\"><font size=\"1\"><b>".$FormatNumber[$w_total][2]."&nbsp;</font></td>");
    ShowHTML("          <td colspan=2><font size=\"1\">&nbsp;</font></td>");
    ShowHTML("        </tr>");
  } 

} 

} 

ShowHTML("      </center>");
ShowHTML("    </table>");
ShowHTML("  </td>");
ShowHTML("</tr>");
ShowHTML("<tr><td align=\"center\" colspan=3>");
if ($R>"")
{

MontaBarra($w_dir.$w_pagina.$par."&R=".$R."&O=".$O."&P1=".$P1."&P2=".$P2."&TP=".$TP."&SG=".$SG."&w_copia=".$w_copia,$RS->PageCount,$P3,$P4,$RS->RecordCount);
}
  else
{

MontaBarra($w_dir.$w_pagina.$par."&R=".$w_pagina.$par."&O=".$O."&P1=".$P1."&P2=".$P2."&TP=".$TP."&SG=".$SG."&w_copia=".$w_copia,$RS->PageCount,$P3,$P4,$RS->RecordCount);
} 

ShowHTML("</tr>");
DesConectaBD();
}
  else
if ((strpos("CP",$O) ? strpos("CP",$O)+1 : 0)>0)
{

if ($O=="C")
{
// Se for cópia

ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td><div align=\"justify\"><font size=2>Para selecionar a tarefa que deseja copiar, informe nos campos abaixo os critérios de seleção e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>");
}
  else
{

ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td><div align=\"justify\"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>");
} 

ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\" valign=\"top\"><table border=0 width=\"90%\" cellspacing=0>");
AbreForm("Form",$w_dir.$w_pagina.$par,"POST","return(Validacao(this));",null,$P1,$P2,$P3,null,$TP,$SG,$R,"L");
ShowHTML("<INPUT type=\"hidden\" name=\"w_troca\" value=\"\">");
if ($O=="C")
{
// Se for cópia, cria parâmetro para facilitar a recuperação dos registros

ShowHTML("<INPUT type=\"hidden\" name=\"w_copia\" value=\"OK\">");
} 


// Recupera dados da opção Projetos

ShowHTML("      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>");
ShowHTML("      <tr>");
DB_GetLinkData($RS,$w_cliente,"ORCAD");
SelecaoProjeto("Açã<u>o</u>:","O","Selecione a ação da tarefa na relação.",$p_projeto,$w_usuario,$RS["sq_menu"],"p_projeto","PJLIST","onChange=\"document.Form.action='".$w_dir.$w_pagina.$par."'; document.Form.O.value='".$O."'; document.Form.w_troca.value='p_atividade'; document.Form.submit();\"");
DesconectaBD();
ShowHTML("      </tr>");
ShowHTML("      <tr>");
SelecaoEtapa("Eta<u>p</u>a:","P","Se necessário, indique a etapa à qual esta tarefa deve ser vinculada.",$p_atividade,$p_projeto,null,"p_atividade",null,null);
ShowHTML("      </tr>");
ShowHTML("          </table>");
ShowHTML("      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>");

if ($P1!=1 || $O=="C")
{
// Se não for cadastramento ou se for cópia

if ($RS_menu["solicita_cc"]=="S")
{

  ShowHTML("      <tr>");
  SelecaoCC("C<u>l</u>assificação:","L","Selecione a classificação desejada.",$p_sqcc,null,"p_sqcc","SIWSOLIC");
  ShowHTML("      </tr>");
} 

ShowHTML("      <tr valign=\"top\">");
ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>Número da <U>d</U>emanda:<br><INPUT ACCESSKEY=\"D\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"p_chave\" size=\"18\" maxlength=\"18\" value=\"".$p_chave."\"></td>");
ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY=\"T\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"p_prazo\" size=\"2\" maxlength=\"2\" value=\"".$p_prazo."\"></td>");
ShowHTML("      <tr valign=\"top\">");
SelecaoPessoa("Respo<u>n</u>sável:","N","Selecione o responsável pela tarefa na relação.",$p_solicitante,null,"p_solicitante","USUARIOS");
SelecaoUnidade("<U>S</U>etor responsável:","S",null,$p_unidade,null,"p_unidade",null,null);
ShowHTML("      <tr valign=\"top\">");
SelecaoPessoa("Responsável atua<u>l</u>:","L","Selecione o responsável atual pela tarefa na relação.",$p_usu_resp,null,"p_usu_resp","USUARIOS");
SelecaoUnidade("<U>S</U>etor atual:","S","Selecione a unidade onde a tarefa se encontra na relação.",$p_uorg_resp,null,"p_uorg_resp",null,null);
ShowHTML("      <tr>");
SelecaoPais("<u>P</u>aís:","P",null,$p_pais,null,"p_pais",null,"onChange=\"document.Form.action='".$w_dir.$w_pagina.$par."'; document.Form.O.value='".$O."'; document.Form.w_troca.value='p_regiao'; document.Form.submit();\"");
SelecaoRegiao("<u>R</u>egião:","R",null,$p_regiao,$p_pais,"p_regiao",null,"onChange=\"document.Form.action='".$w_dir.$w_pagina.$par."'; document.Form.O.value='".$O."'; document.Form.w_troca.value='p_uf'; document.Form.submit();\"");
ShowHTML("      <tr>");
SelecaoEstado("E<u>s</u>tado:","S",null,$p_uf,$p_pais,"N","p_uf",null,"onChange=\"document.Form.action='".$w_dir.$w_pagina.$par."'; document.Form.O.value='".$O."'; document.Form.w_troca.value='p_cidade'; document.Form.submit();\"");
SelecaoCidade("<u>C</u>idade:","C",null,$p_cidade,$p_pais,$p_uf,"p_cidade",null,null);
ShowHTML("      <tr>");
SelecaoPrioridade("<u>P</u>rioridade:","P","Informe a prioridade desta tarefa.",$p_prioridade,null,"p_prioridade",null,null);
ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>Parcerias exter<u>n</u>as:<br><INPUT ACCESSKEY=\"N\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"p_proponente\" size=\"25\" maxlength=\"90\" value=\"".$p_proponente."\"></td>");
ShowHTML("      <tr>");
ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>A<U>s</U>sunto:<br><INPUT ACCESSKEY=\"N\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"p_assunto\" size=\"25\" maxlength=\"90\" value=\"".$p_assunto."\"></td>");
ShowHTML("          <td valign=\"top\" colspan=2><font size=\"1\"><b>Pala<U>v</U>ras-chave:<br><INPUT ACCESSKEY=\"N\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"p_palavra\" size=\"25\" maxlength=\"90\" value=\"".$p_palavra."\"></td>");
ShowHTML("      <tr>");
ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>Iní<u>c:</b><br><input ".$w_Disabled." accesskey=\"C\" type=\"text\" name=\"p_ini_i\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$p_ini_i."\" onKeyDown=\"FormataData(this,event);\"> e <input ".$w_Disabled." accesskey=\"C\" type=\"text\" name=\"p_ini_f\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$p_ini_f."\" onKeyDown=\"FormataData(this,event);\"></td>");
ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>Limi<u>t</u>e para conclusão entre:</b><br><input ".$w_Disabled." accesskey=\"T\" type=\"text\" name=\"p_fim_i\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$p_fim_i."\" onKeyDown=\"FormataData(this,event);\"> e <input ".$w_Disabled." accesskey=\"T\" type=\"text\" name=\"p_fim_f\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$p_fim_f."\" onKeyDown=\"FormataData(this,event);\"></td>");
if ($O!="C")
{
// Se não for cópia

  ShowHTML("      <tr>");
  ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>Exibe somente atividades em atraso?</b><br>");
  if ($p_atraso=="S")
  {

    ShowHTML("              <input ".$w_Disabled." class=\"STR\" type=\"radio\" name=\"p_atraso\" value=\"S\" checked> Sim <br><input ".$w_Disabled." class=\"STR\" class=\"STR\" type=\"radio\" name=\"p_atraso\" value=\"N\"> Não");
  }
    else
  {

    ShowHTML("              <input ".$w_Disabled." class=\"STR\" type=\"radio\" name=\"p_atraso\" value=\"S\"> Sim <br><input ".$w_Disabled." class=\"STR\" class=\"STR\" type=\"radio\" name=\"p_atraso\" value=\"N\" checked> Não");
  } 

  SelecaoFaseCheck("Recuperar fases:","S",null,$p_fase,$P2,"p_fase",null,null);
} 

} 

ShowHTML("      <tr>");
ShowHTML("          <td valign=\"top\"><font size=\"1\"><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=\"O\" ".$w_Disabled." class=\"STS\" name=\"p_ordena\" size=\"1\">");
if ($p_Ordena=="ASSUNTO")
{

ShowHTML("          <option value=\"assunto\" SELECTED>Detalhamento<option value=\"inicio\">Data de recebimento<option value=\"\">Data limite para conclusão<option value=\"nm_tramite\">Fase atual<option value=\"prioridade\">Prioridade<option value=\"proponente\">Parcerias externas");
}
  else
if ($p_Ordena=="INICIO")
{

ShowHTML("          <option value=\"assunto\">Detalhamento<option value=\"inicio\" SELECTED>Data de recebimento<option value=\"\">Data limite para conclusão<option value=\"nm_tramite\">Fase atual<option value=\"prioridade\">Prioridade<option value=\"proponente\">Parcerias externas");
}
  else
if ($p_Ordena=="NM_TRAMITE")
{

ShowHTML("          <option value=\"assunto\">Detalhamento<option value=\"inicio\">Data de recebimento<option value=\"\">Data limite para conclusão<option value=\"nm_tramite\" SELECTED>Fase atual<option value=\"prioridade\">Prioridade<option value=\"proponente\">Parcerias externas");
}
  else
if ($p_Ordena=="PRIORIDADE")
{

ShowHTML("          <option value=\"assunto\">Detalhamento<option value=\"inicio\">Data de recebimento<option value=\"\">Data limite para conclusão<option value=\"nm_tramite\">Fase atual<option value=\"prioridade\" SELECTED>Prioridade<option value=\"proponente\">Parcerias externas");
}
  else
if ($p_Ordena=="PROPONENTE")
{

ShowHTML("          <option value=\"assunto\">Detalhamento<option value=\"inicio\">Data de recebimento<option value=\"\">Data limite para conclusão<option value=\"nm_tramite\">Fase atual<option value=\"prioridade\">Prioridade<option value=\"proponente\" SELECTED>Parcerias externas");
}
  else
{

ShowHTML("          <option value=\"assunto\">Detalhamento<option value=\"inicio\">Data de recebimento<option value=\"\" SELECTED>Data limite para conclusão<option value=\"nm_tramite\">Fase atual<option value=\"prioridade\">Prioridade<option value=\"proponente\">Parcerias externas");
} 

ShowHTML("          </select></td>");
ShowHTML("          <td valign=\"top\"><font size=\"1\"><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=\"L\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"P4\" size=\"4\" maxlength=\"4\" value=\"".$P4."\"></td></tr>");
ShowHTML("          </table>");
ShowHTML("      <tr><td align=\"center\" colspan=\"3\" height=\"1\" bgcolor=\"#000000\">");
ShowHTML("      <tr><td align=\"center\" colspan=\"3\">");
ShowHTML("            <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Aplicar filtro\">");
if ($O=="C")
{
// Se for cópia

ShowHTML("            <input class=\"STB\" type=\"button\" onClick=\"location.href='".$w_pagina.$par."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."';\" name=\"Botao\" value=\"Abandonar cópia\">");
}
  else
{

ShowHTML("            <input class=\"STB\" type=\"button\" onClick=\"location.href='".$w_pagina.$par."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."';\" name=\"Botao\" value=\"Remover filtro\">");
} 

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
ShowHTML(" alert('Opção não disponível');");
ShowHTML(" history.back(1);");
ScriptClose();
} 

ShowHTML("</table>");
Rodape();

$w_titulo=null;

$w_total=null;

$w_parcial=null;


return $function_ret;
} 

// =========================================================================

// Rotina dos dados gerais

// -------------------------------------------------------------------------

function Geral()
{
  extract($GLOBALS);





$w_chave=${"w_chave"};
$w_readonly="";
$w_erro="";
$w_troca=${"w_troca"};

// Verifica se há necessidade de recarregar os dados da tela a partir

// da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)

if ($w_troca>"")
{
// Se for recarga da página

$w_proponente=${"w_proponente"};
$w_sq_unidade_resp=${"w_sq_unidade_resp"};
$w_assunto=${"w_assunto"};
$w_prioridade=${"w_prioridade"};
$w_aviso=${"w_aviso"};
$w_dias=${"w_dias"};
$w_ordem=${"w_ordem"};
$w_inicio_real=${"w_inicio_real"};
$w_fim_real=${"w_fim_real"};
$w_concluida=${"w_concluida"};
$w_data_conclusao=${"w_data_conclusao"};
$w_nota_conclusao=${"w_nota_conclusao"};
$w_custo_real=${"w_custo_real"};
$w_projeto=${"w_projeto"};
$w_atividade=${"w_atividade"};

$w_chave=${"w_chave"};
$w_chave_pai=${"w_chave_pai"};
$w_chave_aux=${"w_chave_aux"};
$w_sq_menu=${"w_sq_menu"};
$w_sq_unidade=${"w_sq_unidade"};
$w_sq_tramite=${"w_sq_tramite"};
$w_solicitante=${"w_solicitante"};
$w_cadastrador=${"w_cadastrador"};
$w_executor=${"w_executor"};
$w_descricao=${"w_descricao"};
$w_justificativa=${"w_justificativa"};
$w_inicio=${"w_inicio"};
$w_fim=${"w_fim"};
$w_inclusao=${"w_inclusao"};
$w_ultima_alteracao=${"w_ultima_alteracao"};
$w_conclusao=${"w_conclusao"};
$w_valor=${"w_valor"};
$w_opiniao=${"w_opiniao"};
$w_data_hora=${"w_data_hora"};
$w_pais=${"w_pais"};
$w_uf=${"w_uf"};
$w_cidade=${"w_cidade"};
$w_palavra_chave=${"w_palavra_chave"};
$w_sqcc=${"w_sqcc"};
}
  else
{

if ((strpos("AEV",$O) ? strpos("AEV",$O)+1 : 0)>0 || $w_copia>"")
{

// Recupera os dados da tarefa

if ($w_copia>"")
{

  DB_GetSolicData($RS,$w_copia,$SG);
}
  else
{

  DB_GetSolicData($RS,$w_chave,$SG);
} 

if ($RS->RecordCount>0)
{

  $w_proponente=$RS["proponente"];
  $w_sq_unidade_resp=$RS["sq_unidade_resp"];
  $w_assunto=$RS["assunto"];
  $w_prioridade=$RS["prioridade"];
  $w_aviso=$RS["aviso_prox_conc"];
  $w_dias=$RS["dias_aviso"];
  $w_ordem=Nvl($RS["ordem"],0);
  $w_inicio_real=$RS["inicio_real"];
  $w_fim_real=$RS["fim_real"];
  $w_concluida=$RS["concluida"];
  $w_data_conclusao=$RS["data_conclusao"];
  $w_nota_conclusao=$RS["nota_conclusao"];
  $w_custo_real=$RS["custo_real"];
  $w_projeto=$RS["sq_solic_pai"];
  $w_atividade=$RS["sq_projeto_etapa"];
  $w_projeto_ant=$RS["sq_solic_pai"];
  $w_atividade_ant=$RS["sq_projeto_etapa"];

  $w_chave_pai=$RS["sq_solic_pai"];
  $w_chave_aux=null;
  $w_sq_menu=$RS["sq_menu"];
  $w_sq_unidade=$RS["sq_unidade"];
  $w_sq_tramite=$RS["sq_siw_tramite"];
  $w_solicitante=$RS["solicitante"];
  $w_cadastrador=$RS["cadastrador"];
  $w_executor=$RS["executor"];
  $w_descricao=$RS["descricao"];
  $w_justificativa=$RS["justificativa"];
  $w_inicio=FormataDataEdicao($RS["inicio"]);
  $w_fim=FormataDataEdicao($RS["fim"]);
  $w_inclusao=$RS["inclusao"];
  $w_ultima_alteracao=$RS["ultima_alteracao"];
  $w_conclusao=$RS["conclusao"];
  $w_valor=$FormatNumber[$RS["valor"]][2];
  $w_opiniao=$RS["opiniao"];
  $w_data_hora=$RS["data_hora"];
  $w_sqcc=$RS["sq_cc"];
  $w_pais=$RS["sq_pais"];
  $w_uf=$RS["co_uf"];
  $w_cidade=$RS["sq_cidade_origem"];
  $w_palavra_chave=$RS["palavra_chave"];
  DesconectaBD();
} 


} 


} 

Cabecalho();
ShowHTML("<HEAD>");
// Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,

// tratando as particularidades de cada serviço

ScriptOpen("JavaScript");
CheckBranco();
FormataData();
FormataDataHora();
FormataValor();
ValidateOpen("Validacao");
if ($O=="I" || $O=="A")
{

ShowHTML("  if (theForm.Botao.value == \"Troca\") { return true; }");
Validate("w_projeto","Ação","SELECT",1,1,18,"","0123456789");
//Validate "w_atividade", "Atividade", "SELECT", "", 1, 18, "", "0123456789"

//ShowHTML "  if (theForm.w_atividade[theForm.w_atividade.selectedIndex].value==\' && theForm.w_atividade.selectedIndex != 0) {"

//ShowHTML "     alert('A etapa selecionada não permite atividades vinculadas.\n Ela pode estar com  100% de conclusão ou ser usada apenas para agrupamento de outras etapas.');"//ShowHTML "     theForm.w_atividade.focus();"

//ShowHTML "     return false;"

//ShowHTML "  }"

Validate("w_assunto","Detalhamento","1",1,5,2000,"1","1");
if ($RS_menu["solicita_cc"]=="S")
{

Validate("w_sqcc","Classificação","SELECT",1,1,18,"","0123456789");
} 

Validate("w_solicitante","Responsável monitoramento","SELECT",1,1,18,"","0123456789");
Validate("w_sq_unidade_resp","Setor responsável","SELECT",1,1,18,"","0123456789");
Validate("w_ordem","Ordem","1","1","1","3","","0123456789");
switch ($RS_menu["data_hora"])
{
case 1:
  Validate("w_fim","Limite para conclusão","DATA",1,10,10,"","0123456789/");
  break;
case 2:
  Validate("w_fim","Limite para conclusão","DATAHORA",1,17,17,"","0123456789/");
  break;
case 3:
  Validate("w_inicio","Início previsto","DATA",1,10,10,"","0123456789/");
  Validate("w_fim","Fim previsto","DATA",1,10,10,"","0123456789/");
  CompData("w_inicio","Início previsto","<=","w_fim","Fim previsto");
  break;
case 4:
  Validate("w_inicio","Data de recebimento","DATAHORA",1,17,17,"","0123456789/,: ");
  Validate("w_fim","Limite para conclusão","DATAHORA",1,17,17,"","0123456789/,: ");
  CompData("w_inicio","Data de recebimento","<=","w_fim","Limite para conclusão");
  break;
} 
Validate("w_valor","Recurso programado","VALOR","1",4,18,"","0123456789.,");
Validate("w_prioridade","Prioridade","SELECT",1,1,1,"","0123456789");
Validate("w_proponente","Parcerias externas","","",2,90,"1","1");
Validate("w_palavra_chave","Responsável","","",2,90,"1","1");
//Validate "w_pais", "País", "SELECT", 1, 1, 18, "", "0123456789"

//Validate "w_uf", "Estado", "SELECT", 1, 1, 3, "1", "1"

//Validate "w_cidade", "Cidade", "SELECT", 1, 1, 18, "", "0123456789"

if ($RS_menu["descricao"]=="S")
{

Validate("w_descricao","Resultados esperados","1",1,5,2000,"1","1");
} 

if ($RS_menu["justificativa"]=="S")
{

Validate("w_justificativa","Observações","1","",5,2000,"1","1");
} 

//Validate "w_dias", "Dias de alerta", "1", "", 1, 2, "", "0123456789"

//ShowHTML "  if (theForm.w_aviso[0].checked) {"

//ShowHTML "     if (theForm.w_dias.value == \') {"

//ShowHTML "        alert('Informe a partir de quantos dias antes da data limite você deseja ser avisado de sua proximidade!');"//ShowHTML "        theForm.w_dias.focus();"

//ShowHTML "        return false;"

//ShowHTML "     }"

//ShowHTML "  }"

//ShowHTML "  else {"

//ShowHTML "     theForm.w_dias.value = \';"

//ShowHTML "  }"

} 

ValidateClose();
ScriptClose();
ShowHTML("</HEAD>");
ShowHTML("<BASE HREF=\"".$conRootSIW."\">");
if ($w_troca>"")
{

BodyOpen("onLoad='document.Form.w_projeto.focus()';");
}
  else
if ((strpos("EV",$O) ? strpos("EV",$O)+1 : 0)>0)
{

BodyOpen("onLoad='document.focus()';");
}
  else
{

BodyOpen("onLoad='document.Form.w_projeto.focus()';");
} 

ShowHTML("<B><FONT COLOR=\"#000000\">".$w_TP."</FONT></B>");
ShowHTML("<HR>");
ShowHTML("<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">");
if ((strpos("IAEV",$O) ? strpos("IAEV",$O)+1 : 0)>0)
{

if ($w_pais=="")
{

// Carrega os valores padrão para país, estado e cidade

DB_GetCustomerData($RS,$w_cliente);
$w_pais=$RS["sq_pais"];
$w_uf=$RS["co_uf"];
$w_cidade=$RS["sq_cidade_padrao"];
DesconectaBD();
} 


if ((strpos("EV",$O) ? strpos("EV",$O)+1 : 0))
{

$w_Disabled=" DISABLED ";
if ($O=="V")
{

  $w_Erro=$Validacao[$w_sq_solicitacao][$sg];
} 

} 


AbreForm("Form",$w_dir.$w_pagina."Grava","POST","return(Validacao(this));",null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
ShowHTML(MontaFiltro("POST"));
ShowHTML("<INPUT type=\"hidden\" name=\"w_troca\" value=\"\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_copia\" value=\"".$w_copia."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_chave\" value=\"".$w_chave."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_data_hora\" value=\"".$RS_menu["data_hora"]."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_menu\" value=\"".$RS_menu["sq_menu"]."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_projeto_ant\" value=\"".$w_projeto_ant."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_atividade_ant\" value=\"".$w_atividade_ant."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_aviso\" value=\"S\">");
//ShowHTML "<INPUT type=""hidden"" name=""w_solicitante"" value=""" & Session("sq_pessoa") & """>"

//ShowHTML "<INPUT type=""hidden"" name=""w_sq_unidade_resp"" value=""" & Session("lotacao") & """>"


//Passagem da cidade padrão como brasília, pelo retidara do impacto geográfico da tela

DB_GetCustomerData($RS,$w_cliente);
ShowHTML("<INPUT type=\"hidden\" name=\"w_cidade\" value=\"".$RS["sq_cidade_padrao"]."\">");
DesconectaBD();

ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">");
ShowHTML("    <table width=\"97%\" border=\"0\">");
ShowHTML("      <tr><td align=\"center\" height=\"2\" bgcolor=\"#000000\"></td></tr>");
ShowHTML("      <tr><td align=\"center\" height=\"1\" bgcolor=\"#000000\"></td></tr>");
ShowHTML("      <tr><td valign=\"top\" align=\"center\" bgcolor=\"#D0D0D0\"><font size=\"1\"><b>Identificação</td></td></tr>");
ShowHTML("      <tr><td align=\"center\" height=\"1\" bgcolor=\"#000000\"></td></tr>");
ShowHTML("      <tr><td><font size=1>Os dados deste bloco serão utilizados para identificação da tarefa, bem como para o controle de sua execução.</font></td></tr>");
ShowHTML("      <tr><td align=\"center\" height=\"1\" bgcolor=\"#000000\"></td></tr>");

// Recupera dados da opção Projetos

ShowHTML("      <tr>");
DB_GetLinkData($RS,$w_cliente,"ORCAD");
SelecaoProjeto("Açã<u>o</u>:","O","Selecione a ação a qual a tarefa está vinculda.",$w_projeto,$w_usuario,$RS["sq_menu"],"w_projeto","PJLISTCAD",null);
DesconectaBD();
ShowHTML("      </tr>");

//ShowHTML "      <tr>"

//SelecaoEtapa "Eta<u>p</u>a:", "P", "Se necessário, indique a etapa à qual esta tarefa deve ser vinculada.", w_atividade, w_projeto, null, "w_atividade", "Grupo", null

//ShowHTML "      </tr>"


ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>Detalh<u>a</u>mento:</b><br><textarea ".$w_Disabled." accesskey=\"A\" name=\"w_assunto\" class=\"STI\" ROWS=5 cols=75 title=\"Escreva um texto de detalhamento para esta tarefa.\">".$w_assunto."</TEXTAREA></td>");
if ($RS_menu["solicita_cc"]=="S")
{

ShowHTML("          <tr>");
SelecaoCC("C<u>l</u>assificação:","L","Selecione um dos itens relacionados.",$w_sqcc,null,"w_sqcc","SIWSOLIC");
ShowHTML("          </tr>");
} 

ShowHTML("      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>");
SelecaoPessoa("Respo<u>n</u>sável:","N","Selecione o solicitante da tarefa na relação.",$w_solicitante,null,"w_solicitante","USUARIOS");
SelecaoUnidade("<U>S</U>etor responsável:","S","Selecione o setor responsável pela execução da tarefa",$w_sq_unidade_resp,null,"w_sq_unidade_resp",null,null);
ShowHTML("          </table>");
ShowHTML("      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>");
ShowHTML("              <td align=\"left\"><font size=\"1\"><b><u>O</u>rdem:<br><INPUT ACCESSKEY=\"O\" TYPE=\"TEXT\" CLASS=\"STI\" NAME=\"w_ordem\" SIZE=3 MAXLENGTH=3 VALUE=\"".$w_ordem."\" ".$w_Disabled."></td>");
switch ($RS_menu["data_hora"])
{
case 1:
  ShowHTML("              <td valign=\"top\"><font size=\"1\"><b>Fim previs<u>t</u>o:</b><br><input ".$w_Disabled." accesskey=\"T\" type=\"text\" name=\"w_fim\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$w_fim."\" onKeyDown=\"FormataData(this,event);\"></td>");
  break;
case 2:
  ShowHTML("              <td valign=\"top\"><font size=\"1\"><b>Fim previs<u>t</u>o:</b><br><input ".$w_Disabled." accesskey=\"T\" type=\"text\" name=\"w_fim\" class=\"STI\" SIZE=\"17\" MAXLENGTH=\"17\" VALUE=\"".$w_fim."\" onKeyDown=\"FormataDataHora(this,event);\"></td>");
  break;
case 3:
  ShowHTML("              <td valign=\"top\"><font size=\"1\"><b>Iní<u>c</u>io previsto:</b><br><input ".$w_Disabled." accesskey=\"C\" type=\"text\" name=\"w_inicio\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".Nvl($w_inicio,FormataDataEdicao(time()()))."\" onKeyDown=\"FormataData(this,event);\"></td>");
  ShowHTML("              <td valign=\"top\"><font size=\"1\"><b>Fim previs<u>t</u>o:</b><br><input ".$w_Disabled." accesskey=\"T\" type=\"text\" name=\"w_fim\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$w_fim."\" onKeyDown=\"FormataData(this,event);\"></td>");
  break;
case 4:
  ShowHTML("              <td valign=\"top\"><font size=\"1\"><b>Iní<u>c</u>io previsto:</b><br><input ".$w_Disabled." accesskey=\"C\" type=\"text\" name=\"w_inicio\" class=\"STI\" SIZE=\"17\" MAXLENGTH=\"17\" VALUE=\"".$w_inicio."\" onKeyDown=\"FormataDataHora(this,event);\"></td>");
  ShowHTML("              <td valign=\"top\"><font size=\"1\"><b>Fim previs<u>t</u>o:</b><br><input ".$w_Disabled." accesskey=\"T\" type=\"text\" name=\"w_fim\" class=\"STI\" SIZE=\"17\" MAXLENGTH=\"17\" VALUE=\"".$w_fim."\" onKeyDown=\"FormataDataHora(this,event);\"></td>");
  break;
} 
ShowHTML("              <td valign=\"top\"><font size=\"1\"><b><u>R</u>ecurso programado:</b><br><input ".$w_Disabled." accesskey=\"O\" type=\"text\" name=\"w_valor\" class=\"STI\" SIZE=\"18\" MAXLENGTH=\"18\" VALUE=\"".$w_valor."\" onKeyDown=\"FormataValor(this,18,2,event);\" title=\"Informe o recurso programado para execução da tarefa, ou zero se não for o caso.\"></td>");
SelecaoPrioridade("<u>P</u>rioridade:","P","Informe a prioridade desta tarefa.",$w_prioridade,null,"w_prioridade",null,null);
ShowHTML("          </table>");
ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>Parc<u>e</u>rias externas:<br><INPUT ACCESSKEY=\"E\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"w_proponente\" size=\"90\" maxlength=\"90\" value=\"".$w_proponente."\" title=\"Parcerias externas necessárias ao cumprimento da tarefa. Preencha apenas se houver.\"></td>");
ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>Responsáve<u>l</u>:<br><INPUT ACCESSKEY=\"L\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"w_palavra_chave\" size=\"90\" maxlength=\"90\" value=\"".$w_palavra_chave."\" title=\"Informe o responsável pela tarefa\"></td>");
//ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"

//ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"

//ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Impacto geográfico</td></td></tr>"

//ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"

//ShowHTML "      <tr><td><font size=1>Os dados deste bloco identificam o local onde a ação causará efeito. Se abrangência nacional, indique Brasília-DF. Se abrangência estadual, indique a capital do estado.</font></td></tr>"

//ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"

//ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100""" cellspacing=0>"

//ShowHTML "      <tr>"

// & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_uf'; document.Form.submit();"""// & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_cidade'; document.Form.submit();"""//SelecaoCidade "<u>C</u>idade:", "C", null, w_cidade, w_pais, w_uf, "w_cidade", null, null

//ShowHTML "          </table>"

if ($RS_menu["descricao"]=="S" || $RS_menu["justificativa"]=="S")
{

ShowHTML("      <tr><td align=\"center\" height=\"2\" bgcolor=\"#000000\"></td></tr>");
ShowHTML("      <tr><td align=\"center\" height=\"1\" bgcolor=\"#000000\"></td></tr>");
ShowHTML("      <tr><td valign=\"top\" align=\"center\" bgcolor=\"#D0D0D0\"><font size=\"1\"><b>Informações adicionais</td></td></tr>");
ShowHTML("      <tr><td align=\"center\" height=\"1\" bgcolor=\"#000000\"></td></tr>");
ShowHTML("      <tr><td><font size=1>Os dados deste bloco visam orientar os executores da tarefa.</font></td></tr>");
ShowHTML("      <tr><td align=\"center\" height=\"1\" bgcolor=\"#000000\"></td></tr>");
if ($RS_menu["descricao"]=="S")
{

  ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>Res<u>u</u>ultados esperados:</b><br><textarea ".$w_Disabled." accesskey=\"U\" name=\"w_descricao\" class=\"STI\" ROWS=5 cols=75 title=\"Descreva os resultados físicos esperados com a execução da tarefa.\">".$w_descricao."</TEXTAREA></td>");
} 

if ($RS_menu["justificativa"]=="S")
{

  ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>Obse<u>r</u>vações:</b><br><textarea ".$w_Disabled." accesskey=\"R\" name=\"w_justificativa\" class=\"STI\" ROWS=5 cols=75 >".$w_justificativa."</TEXTAREA></td>");
} 

} 

//ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"

//ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"

//ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Alerta de atraso</td></td></tr>"

//ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"

//ShowHTML "      <tr><td><font size=1>Os dados abaixo indicam como deve ser tratada a proximidade da data limite para conclusão da tarefa.</font></td></tr>"

//ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"

//ShowHTML "      <tr><td><table border=""0"" width=""100""">"

//ShowHTML "          <tr>"

//MontaRadioNS "<b>Emite alerta?</b>", w_aviso, "w_aviso"

//ShowHTML "              <td valign=""top""><font size=""1""><b>Quantos <U>d</U>ias antes da data limite?<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_dias"" size=""2"" maxlength=""2"" value=""" & w_dias & """ title=""Número de dias para emissão do alerta de proximidade da data limite para conclusão da tarefa.""></td>"

//ShowHTML "          </table>"

//ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"


// Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação

ShowHTML("      <tr><td align=\"center\" colspan=\"3\">");
ShowHTML("            <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Gravar\">");
if ($O=="I")
{

DB_GetMenuData($RS,$w_menu);
ShowHTML("            <input class=\"STB\" type=\"button\" onClick=\"location.href='".$R."&w_copia=".$w_copia."&O=L&SG=".$RS["sigla"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP.MontaFiltro("GET")."';\" name=\"Botao\" value=\"Cancelar\">");
} 

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
ShowHTML(" alert('Opção não disponível');");
//ShowHTML " history.back(1);"

ScriptClose();
} 

ShowHTML("</table>");
ShowHTML("</center>");
Rodape();

$w_projeto_ant=null;

$w_atividade_ant=null;

$w_projeto=null;

$w_atividade=null;

$w_proponente=null;

$w_sq_unidade_resp=null;

$w_assunto=null;

$w_prioridade=null;

$w_aviso=null;

$w_dias=null;

$w_ordem=null;

$w_inicio_real=null;

$w_fim_real=null;

$w_concluida=null;

$w_data_conclusao=null;

$w_nota_conclusao=null;

$w_custo_real=null;


$w_chave=null;

$w_chave_pai=null;

$w_chave_aux=null;

$w_sq_menu=null;

$w_sq_unidade=null;

$w_sq_tramite=null;

$w_solicitante=null;

$w_cadastrador=null;

$w_executor=null;

$w_descricao=null;

$w_justificativa=null;

$w_inicio=null;

$w_fim=null;

$w_inclusao=null;

$w_ultima_alteracao=null;

$w_conclusao=null;

$w_valor=null;

$w_opiniao=null;

$w_data_hora=null;

$w_sqcc=null;

$w_pais=null;

$w_uf=null;

$w_cidade=null;

$w_palavra_chave=null;


$w_troca=null;

$i=null;

$w_erro=null;

$w_como_funciona=null;

$w_cor=null;


return $function_ret;
} 

// ------------------------------------------------------------------------- 

// Rotina de anexos 

// ------------------------------------------------------------------------- 

function Anexos()
{
  extract($GLOBALS);




$w_Chave=${"w_Chave"};
$w_chave_aux=${"w_chave_aux"};
$w_troca=${"w_troca"};

if ($w_troca>"")
{
// Se for recarga da página 

$w_nome=${"w_nome"};
$w_descricao=${"w_descricao"};
$w_caminho=${"w_caminho"};
}
  else
if ($O=="L")
{

// Recupera todos os registros para a listagem 

DB_GetSolicAnexo($RS,$w_chave,null,$w_cliente);
$RS->Sort="nome";
}
  else
if ((strpos("AEV",$O) ? strpos("AEV",$O)+1 : 0)>0 && $w_Troca=="")
{

// Recupera os dados do endereço informado 

DB_GetSolicAnexo($RS,$w_chave,$w_chave_aux,$w_cliente);
$w_nome=$RS["nome"];
$w_descricao=$RS["descricao"];
$w_caminho=$RS["chave_aux"];
DesconectaBD();
} 


Cabecalho();
ShowHTML("<HEAD>");
if ((strpos("IAEP",$O) ? strpos("IAEP",$O)+1 : 0)>0)
{

ScriptOpen("JavaScript");
ProgressBar("/siw/",$UploadID);
ValidateOpen("Validacao");
if ((strpos("IA",$O) ? strpos("IA",$O)+1 : 0)>0)
{

Validate("w_nome","Título","1","1","1","255","1","1");
Validate("w_descricao","Descrição","1","1","1","1000","1","1");
if ($O=="I")
{

  Validate("w_caminho","Arquivo","","1","5","255","1","1");
} 

} 

ShowHTML("  theForm.Botao[0].disabled=true;");
ShowHTML("  theForm.Botao[1].disabled=true;");
ShowHTML("if (theForm.w_caminho.value != '') {return ProgressBar();}");
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
if ($O=="I")
{

BodyOpen("onLoad='document.Form.w_nome.focus()';");
}
  else
if ($O=="A")
{

BodyOpen("onLoad='document.Form.w_descricao.focus()';");
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
// Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem 

ShowHTML("<tr><td><font size=\"1\"><a accesskey=\"I\" class=\"SS\" href=\"".$w_dir.$w_pagina.$par."&R=".$w_pagina.$par."&O=I&w_chave=".$w_chave."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\"><u>I</u>ncluir</a>&nbsp;");
ShowHTML("    <td align=\"right\"><font size=\"1\"><b>Registros existentes: ".$RS->RecordCount);
ShowHTML("<tr><td align=\"center\" colspan=3>");
ShowHTML("    <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">");
ShowHTML("        <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">");
ShowHTML("          <td><font size=\"1\"><b>Título</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Descrição</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Tipo</font></td>");
ShowHTML("          <td><font size=\"1\"><b>KB</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Operações</font></td>");
ShowHTML("        </tr>");
if ($RS->EOF)
{
// Se não foram selecionados registros, exibe mensagem 

ShowHTML("      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=7 align=\"center\"><font size=\"1\"><b>Não foram encontrados registros.</b></td></tr>");
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
ShowHTML("        <td><font size=\"1\">".LinkArquivo("HL",$w_cliente,$RS["chave_aux"],"_blank","Clique para exibir o arquivo em outra janela.",$RS["nome"],null)."</td>");
ShowHTML("        <td><font size=\"1\">".Nvl($RS["descricao"],"---")."</td>");
ShowHTML("        <td><font size=\"1\">".$RS["tipo"]."</td>");
ShowHTML("        <td align=\"right\"><font size=\"1\">".round($cDbl[$RS["tamanho"]]/1024,1)."&nbsp;</td>");
ShowHTML("        <td align=\"top\" nowrap><font size=\"1\">");
ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina.$par."&R=".$w_pagina.$par."&O=A&w_chave=".$w_chave."&w_chave_aux=".$Rs["chave_aux"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\">Alterar</A>&nbsp");
ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina.$par."&R=".$w_pagina.$par."&O=E&w_chave=".$w_chave."&w_chave_aux=".$Rs["chave_aux"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\">Excluir</A>&nbsp");
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

ShowHTML("<FORM action=\"".$w_dir.$w_pagina."Grava&SG=".$SG."&O=".$O."&UploadID=".$UploadID."\" name=\"Form\" onSubmit=\"return(Validacao(this));\" enctype=\"multipart/form-data\" method=\"POST\">");
ShowHTML("<INPUT type=\"hidden\" name=\"P1\" value=\"".$P1."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"P2\" value=\"".$P2."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"P3\" value=\"".$P3."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"P4\" value=\"".$P4."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"TP\" value=\"".$TP."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"R\" value=\"".$R."\">");

ShowHTML("<INPUT type=\"hidden\" name=\"w_chave\" value=\"".$w_chave."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_chave_aux\" value=\"".$w_chave_aux."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_atual\" value=\"".$w_caminho."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_troca\" value=\"\">");

ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">");
ShowHTML("    <table width=\"97%\" border=\"0\">");

if ($O=="I" || $O=="A")
{

DB_GetCustomerData($RS,$w_cliente);
ShowHTML("      <tr><td align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"2\"><b><font color=\"#BC3131\">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de ".$cDbl[$RS["upload_maximo"]]/1024." KBytes</b>.</font></td>");
ShowHTML("<INPUT type=\"hidden\" name=\"w_upload_maximo\" value=\"".$RS["upload_maximo"]."\">");
} 


ShowHTML("      <tr><td><font size=\"1\"><b><u>T</u>ítulo:</b><br><input ".$w_Disabled." accesskey=\"T\" type=\"text\" name=\"w_nome\" class=\"STI\" SIZE=\"75\" MAXLENGTH=\"255\" VALUE=\"".$w_nome."\" title=\"OBRIGATÓRIO. Informe um título para o arquivo.\"></td>");
ShowHTML("      <tr><td><font size=\"1\"><b><u>D</u>escrição:</b><br><textarea ".$w_Disabled." accesskey=\"D\" name=\"w_descricao\" class=\"STI\" ROWS=5 cols=65 title=\"OBRIGATÓRIO. Descreva a finalidade do arquivo.\">".$w_descricao."</TEXTAREA></td>");
ShowHTML("      <tr><td><font size=\"1\"><b>A<u>r</u>quivo:</b><br><input ".$w_Disabled." accesskey=\"R\" type=\"file\" name=\"w_caminho\" class=\"STI\" SIZE=\"80\" MAXLENGTH=\"100\" VALUE=\"\" title=\"OBRIGATÓRIO. Clique no botão ao lado para localizar o arquivo. Ele será transferido automaticamente para o servidor.\">");
if ($w_caminho>"")
{

ShowHTML("              <b>".LinkArquivo("SS",$w_cliente,$w_caminho,"_blank","Clique para exibir o arquivo atual.","Exibir",null)."</b>");
} 

ShowHTML("      <tr><td align=\"center\"><hr>");
if ($O=="E")
{

ShowHTML("   <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Excluir\" onClick=\"return confirm('Confirma a exclusão do registro?');\">");
}
  else
{

if ($O=="I")
{

ShowHTML("            <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Incluir\">");
}
  else
{

ShowHTML("            <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Atualizar\">");
} 

} 

ShowHTML("            <input class=\"STB\" type=\"button\" onClick=\"location.href='".$w_pagina.$par."&w_chave=".$w_chave."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."&O=L';\" name=\"Botao\" value=\"Cancelar\">");
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
ShowHTML(" alert('Opção não disponível');");
//ShowHTML " history.back(1);" 

ScriptClose();
} 

ShowHTML("</table>");
ShowHTML("</center>");
Rodape();

$w_chave=null;

$w_chave_pai=null;

$w_chave_aux=null;

$w_nome=null;

$w_descricao=null;

$w_caminho=null;


$w_troca=null;

$i=null;

$w_erro=null;

return $function_ret;
} 

// =========================================================================

// Rotina de interessados

// -------------------------------------------------------------------------

function Interessados()
{
  extract($GLOBALS);




$w_Chave=${"w_Chave"};
$w_chave_aux=${"w_chave_aux"};
$w_troca=${"w_troca"};

if ($w_troca>"")
{
// Se for recarga da página

$w_tipo_visao=${"w_tipo_visao"};
$w_envia_email=${"w_envia_email"};
}
  else
if ($O=="L")
{

// Recupera todos os registros para a listagem

DB_GetSolicInter($RS,$w_chave,null,"LISTA");
$RS->Sort="nome_resumido";
}
  else
if ((strpos("AEV",$O) ? strpos("AEV",$O)+1 : 0)>0 && $w_Troca=="")
{

// Recupera os dados do endereço informado

DB_GetSolicInter($RS,$w_chave,$w_chave_aux,"REGISTRO");
$w_nome=$RS["nome_resumido"];
$w_tipo_visao=$RS["tipo_visao"];
$w_envia_email=$RS["envia_email"];
DesconectaBD();
} 


Cabecalho();
ShowHTML("<HEAD>");
if ((strpos("IAEP",$O) ? strpos("IAEP",$O)+1 : 0)>0)
{

ScriptOpen("JavaScript");
modulo();
checkbranco();
formatadata();
FormataCEP();
FormataValor();
ValidateOpen("Validacao");
if ((strpos("IA",$O) ? strpos("IA",$O)+1 : 0)>0)
{

Validate("w_chave_aux","Pessoa","HIDDEN","1","1","10","","1");
Validate("w_tipo_visao","Tipo de visão","SELECT","1","1","10","","1");
} 

ShowHTML("  theForm.Botao[0].disabled=true;");
ShowHTML("  theForm.Botao[1].disabled=true;");
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
if ($O=="I")
{

BodyOpen("onLoad='document.Form.w_chave_aux.focus()';");
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
// Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem

ShowHTML("<tr><td><font size=\"1\"><a accesskey=\"I\" class=\"SS\" href=\"".$w_dir.$w_pagina.$par."&R=".$w_pagina.$par."&O=I&w_chave=".$w_chave."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\"><u>I</u>ncluir</a>&nbsp;");
ShowHTML("    <td align=\"right\"><font size=\"1\"><b>Registros existentes: ".$RS->RecordCount);
ShowHTML("<tr><td align=\"center\" colspan=3>");
ShowHTML("    <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">");
ShowHTML("        <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">");
ShowHTML("          <td><font size=\"1\"><b>Pessoa</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Visao</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Envia e-mail</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Operações</font></td>");
ShowHTML("        </tr>");
if ($RS->EOF)
{
// Se não foram selecionados registros, exibe mensagem

ShowHTML("      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=7 align=\"center\"><font size=\"1\"><b>Não foram encontrados registros.</b></td></tr>");
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
ShowHTML("        <td><font size=\"1\">".$RS["nome_resumido"]."</td>");
ShowHTML("        <td><font size=\"1\">".RetornaTipoVisao($RS["tipo_visao"])."</td>");
ShowHTML("        <td align=\"center\"><font size=\"1\">".str_replace("N","Não",str_replace("S","Sim",$RS["envia_email"]))."</td>");
ShowHTML("        <td align=\"top\" nowrap><font size=\"1\">");
ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina.$par."&R=".$w_pagina.$par."&O=A&w_chave=".$w_chave."&w_chave_aux=".$Rs["sq_pessoa"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\">Alterar</A>&nbsp");
ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."GRAVA&R=".$w_pagina.$par."&O=E&w_chave=".$w_chave."&w_chave_aux=".$Rs["sq_pessoa"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\" onClick=\"return confirm('Confirma a exclusão do registro?');\">Excluir</A>&nbsp");
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

AbreForm("Form",$w_dir.$w_pagina."Grava","POST","return(Validacao(this));",null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
ShowHTML("<INPUT type=\"hidden\" name=\"w_chave\" value=\"".$w_chave."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_troca\" value=\"\">");

ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">");
ShowHTML("    <table width=\"97%\" border=\"0\">");
ShowHTML("      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0><tr valign=\"top\">");
if ($O=="I")
{

SelecaoPessoa("<u>P</u>essoa:","N","Selecione o interessado na relação.",$w_chave_aux,null,"w_chave_aux","USUARIOS");
}
  else
{

ShowHTML("<INPUT type=\"hidden\" name=\"w_chave_aux\" value=\"".$w_chave_aux."\">");
ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>Pessoa:</b><br>".$w_nome."</td>");
} 

SelecaoTipoVisao("<u>T</u>ipo de visão:","T","Selecione o tipo de visão que o interessado terá desta tarefa.",$w_tipo_visao,null,"w_tipo_visao",null,null);
ShowHTML("          </table>");
ShowHTML("      <tr>");
MontaRadioNS("<b>Envia e-mail ao interessado quando houver encaminhamento?</b>",$w_envia_email,"w_envia_email");
ShowHTML("      </tr>");
ShowHTML("      <tr><td align=\"center\" colspan=4><hr>");
if ($O=="E")
{

ShowHTML("   <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Excluir\">");
}
  else
{

if ($O=="I")
{

ShowHTML("            <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Incluir\">");
}
  else
{

ShowHTML("            <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Atualizar\">");
} 

} 

ShowHTML("            <input class=\"STB\" type=\"button\" onClick=\"location.href='".$w_pagina.$par."&w_chave=".$w_chave."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."&O=L';\" name=\"Botao\" value=\"Cancelar\">");
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
ShowHTML(" alert('Opção não disponível');");
//ShowHTML " history.back(1);"

ScriptClose();
} 

ShowHTML("</table>");
ShowHTML("</center>");
Rodape();

$w_chave=null;

$w_chave_pai=null;

$w_chave_aux=null;

$w_nome=null;

$w_tipo_visao=null;

$w_envia_email=null;


$w_troca=null;

$i=null;

$w_erro=null;

return $function_ret;
} 

// =========================================================================

// Rotina de áreas envolvidas

// -------------------------------------------------------------------------

function Areas()
{
  extract($GLOBALS);




$w_Chave=${"w_Chave"};
$w_chave_aux=${"w_chave_aux"};
$w_troca=${"w_troca"};

if ($w_troca>"")
{
// Se for recarga da página

$w_papel=${"w_papel"};
}
  else
if ($O=="L")
{

// Recupera todos os registros para a listagem

DB_GetSolicAreas($RS,$w_chave,null,"LISTA");
$RS->Sort="nome";
}
  else
if ((strpos("AEV",$O) ? strpos("AEV",$O)+1 : 0)>0 && $w_Troca=="")
{

// Recupera os dados do endereço informado

DB_GetSolicAreas($RS,$w_chave,$w_chave_aux,"REGISTRO");
$w_nome=$RS["nome"];
$w_papel=$RS["papel"];
DesconectaBD();
} 


Cabecalho();
ShowHTML("<HEAD>");
if ((strpos("IAEP",$O) ? strpos("IAEP",$O)+1 : 0)>0)
{

ScriptOpen("JavaScript");
modulo();
checkbranco();
formatadata();
FormataCEP();
FormataValor();
ValidateOpen("Validacao");
if ((strpos("IA",$O) ? strpos("IA",$O)+1 : 0)>0)
{

Validate("w_chave_aux","Área/Instituição","HIDDEN","1","1","10","","1");
Validate("w_papel","Papel desempenhado","","1","1","2000","1","1");
} 

ShowHTML("  theForm.Botao[0].disabled=true;");
ShowHTML("  theForm.Botao[1].disabled=true;");
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
// Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem

ShowHTML("<tr><td><font size=\"1\"><a accesskey=\"I\" class=\"SS\" href=\"".$w_dir.$w_pagina.$par."&R=".$w_pagina.$par."&O=I&w_chave=".$w_chave."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\"><u>I</u>ncluir</a>&nbsp;");
ShowHTML("    <td align=\"right\"><font size=\"1\"><b>Registros existentes: ".$RS->RecordCount);
ShowHTML("<tr><td align=\"center\" colspan=3>");
ShowHTML("    <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">");
ShowHTML("        <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">");
ShowHTML("          <td><font size=\"1\"><b>Área/Instituição</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Papel</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Operações</font></td>");
ShowHTML("        </tr>");
if ($RS->EOF)
{
// Se não foram selecionados registros, exibe mensagem

ShowHTML("      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=7 align=\"center\"><font size=\"1\"><b>Não foram encontrados registros.</b></td></tr>");
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
ShowHTML("        <td><font size=\"1\">".$RS["nome"]."</td>");
ShowHTML("        <td><font size=\"1\">".$RS["papel"]."</td>");
ShowHTML("        <td align=\"top\" nowrap><font size=\"1\">");
ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina.$par."&R=".$w_pagina.$par."&O=A&w_chave=".$w_chave."&w_chave_aux=".$Rs["sq_unidade"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\">Alterar</A>&nbsp");
ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."GRAVA&R=".$w_pagina.$par."&O=E&w_chave=".$w_chave."&w_chave_aux=".$Rs["sq_unidade"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\" onClick=\"return confirm('Confirma a exclusão do registro?');\">Excluir</A>&nbsp");
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

AbreForm("Form",$w_dir.$w_pagina."Grava","POST","return(Validacao(this));",null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
ShowHTML("<INPUT type=\"hidden\" name=\"w_chave\" value=\"".$w_chave."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_troca\" value=\"\">");

ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">");
ShowHTML("    <table width=\"97%\" border=\"0\">");
ShowHTML("      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0><tr valign=\"top\">");
if ($O=="I")
{

SelecaoUnidade("<U>Á</U>rea/Instituição:","A",null,$w_chave_aux,null,"w_chave_aux",null,null);
}
  else
{

ShowHTML("<INPUT type=\"hidden\" name=\"w_chave_aux\" value=\"".$w_chave_aux."\">");
ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>Área/Instituição:</b><br>".$w_nome."</td>");
} 

ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b><u>P</u>apel desempenhado:</b><br><textarea ".$w_Disabled." accesskey=\"P\" name=\"w_papel\" class=\"STI\" ROWS=5 cols=75 title=\"Descreva o papel desempenhado pela área ou instituição na execução da tarefa.\">".$w_papel."</TEXTAREA></td>");
ShowHTML("          </table>");
ShowHTML("      <tr><td align=\"center\" colspan=4><hr>");
if ($O=="E")
{

ShowHTML("   <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Excluir\">");
}
  else
{

if ($O=="I")
{

ShowHTML("            <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Incluir\">");
}
  else
{

ShowHTML("            <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Atualizar\">");
} 

} 

ShowHTML("            <input class=\"STB\" type=\"button\" onClick=\"location.href='".$w_pagina.$par."&w_chave=".$w_chave."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."&O=L';\" name=\"Botao\" value=\"Cancelar\">");
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
ShowHTML(" alert('Opção não disponível');");
//ShowHTML " history.back(1);"

ScriptClose();
} 

ShowHTML("</table>");
ShowHTML("</center>");
Rodape();

$w_chave=null;

$w_chave_pai=null;

$w_chave_aux=null;

$w_nome=null;

$w_papel=null;


$w_troca=null;

$i=null;

$w_erro=null;

return $function_ret;
} 

// =========================================================================

// Rotina de visualização

// -------------------------------------------------------------------------

function Visual()
{
  extract($GLOBALS);




$w_chave=${"w_chave"};
$w_tipo=strtoupper(trim(${"w_tipo"}));

// Recupera o logo do cliente a ser usado nas listagens

DB_GetCustomerData($RS,$w_cliente);
if ($RS["logo"]>"")
{

$w_logo="\img\logo".substr($RS["logo"],(strpos($RS["logo"],".") ? strpos($RS["logo"],".")+1 : 0)-1,30);
} 

DesconectaBD();

if ($w_tipo=="WORD")
{

header("Content-type: "."application/msword");
}
  else
{

cabecalho();
} 

ShowHTML("<HEAD>");
ShowHTML("<TITLE>".$conSgSistema." - Visualização de Tarefa</TITLE>");
ShowHTML("</HEAD>");
ShowHTML("<BASE HREF=\"".$conRootSIW."\">");
if ($w_tipo!="WORD")
{

BodyOpenClean("onLoad='document.focus()'; ");
} 

ShowHTML("<TABLE WIDTH=\"100%\" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=\"LEFT\" src=\"".LinkArquivo(null,$w_cliente,$w_logo,null,null,null,"EMBED")."\"><TD ALIGN=\"RIGHT\"><B><FONT SIZE=4 COLOR=\"#000000\">");
ShowHTML("Visualização de Tarefa");
ShowHTML("</FONT><TR><TD ALIGN=\"RIGHT\"><B><FONT SIZE=2 COLOR=\"#000000\">".DataHora()."</B>");
if ($w_tipo!="WORD")
{

ShowHTML("&nbsp;&nbsp;<IMG ALIGN=\"CENTER\" TITLE=\"Imprimir\" SRC=\"images/impressora.jpg\" onClick=\"window.print();\">");
ShowHTML("&nbsp;&nbsp;<IMG ALIGN=\"CENTER\" TITLE=\"Gerar word\" SRC=\"images/word.gif\" onClick=\"window.open('".$w_pagina."Visual&R=".$w_pagina.$par."&O=L&w_chave=".$w_chave."&w_tipo=word&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."','VisualAcaoWord','menubar=yes resizable=yes scrollbars=yes');\">");
} 

ShowHTML("</TD></TR>");
ShowHTML("</FONT></B></TD></TR></TABLE>");
ShowHTML("<HR>");
if ($w_tipo>"" && $w_tipo!="WORD")
{

ShowHTML("<center><B><FONT SIZE=2>Clique <a class=\"HL\" href=\"javascript:history.back(1);\">aqui</a> para voltar à tela anterior</font></b></center>");
} 


// Chama a rotina de visualização dos dados da tarefa, na opção "Listagem"

ShowHTML(VisualDemanda($w_chave,"L",$w_usuario));

if ($w_tipo>"" && $w_tipo!="WORD")
{

ShowHTML("<center><B><FONT SIZE=2>Clique <a class=\"HL\" href=\"javascript:history.back(1);\">aqui</a> para voltar à tela anterior</font></b></center>");
} 


if ($w_tipo!="WORD")
{

Rodape();
} 


$w_tipo=null;

$w_erro=null;

$w_logo=null;

$w_chave=null;


return $function_ret;
} 

// =========================================================================

// Rotina de exclusão

// -------------------------------------------------------------------------

function Excluir()
{
  extract($GLOBALS);





$w_Chave=${"w_Chave"};
$w_chave_aux=${"w_chave_aux"};
$w_troca=${"w_troca"};

if ($w_troca>"")
{
// Se for recarga da página

$w_observacao=${"w_observacao"};
} 


Cabecalho();
ShowHTML("<HEAD>");
ShowHTML("<meta http-equiv=\"Refresh\" content=\"300; URL=../".MontaURL("MESA")."\">");
if ((strpos("E",$O) ? strpos("E",$O)+1 : 0)>0)
{

ScriptOpen("JavaScript");
ValidateOpen("Validacao");
Validate("w_assinatura","Assinatura Eletrônica","1","1","6","30","1","1");
if ($P1!=1)
{
// Se não for encaminhamento

ShowHTML("  theForm.Botao[0].disabled=true;");
ShowHTML("  theForm.Botao[1].disabled=true;");
}
  else
{

ShowHTML("  theForm.Botao.disabled=true;");
} 

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
{

BodyOpen("onLoad='document.Form.w_assinatura.focus()';");
} 

ShowHTML("<B><FONT COLOR=\"#000000\">".$w_TP."</FONT></B>");
ShowHTML("<HR>");
ShowHTML("<div align=center><center>");
ShowHTML("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">");

// Chama a rotina de visualização dos dados da tarefa, na opção "Listagem"

ShowHTML(VisualDemanda($w_chave,"V",$w_usuario));

ShowHTML("<HR>");
AbreForm("Form",$w_dir.$w_pagina."Grava","POST","return(Validacao(this));",null,$P1,$P2,$P3,$P4,$TP,"ORPGERAL",$R,$O);
ShowHTML("<INPUT type=\"hidden\" name=\"w_chave\" value=\"".$w_chave."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_troca\" value=\"\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_menu\" value=\"".$w_menu."\">");
$DB_GetSolicData$RS$w_chave//ORPGERAL"ShowHTML("<INPUT type=\"hidden\" name=\"w_tramite\" value=\"".$RS["sq_siw_tramite"]."\">");
DesconectaBD();

ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">");
ShowHTML("  <table width=\"97%\" border=\"0\">");
ShowHTML("      <tr><td align=\"LEFT\" colspan=4><font size=\"1\"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=\"A\" class=\"STI\" type=\"PASSWORD\" name=\"w_assinatura\" size=\"30\" maxlength=\"30\" value=\"\"></td></tr>");
ShowHTML("    <tr><td align=\"center\" colspan=4><hr>");
ShowHTML("      <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Excluir\">");
ShowHTML("      <input class=\"STB\" type=\"button\" onClick=\"history.back(1);\" name=\"Botao\" value=\"Abandonar\">");
ShowHTML("      </td>");
ShowHTML("    </tr>");
ShowHTML("  </table>");
ShowHTML("  </TD>");
ShowHTML("</tr>");
ShowHTML("</FORM>");
ShowHTML("</table>");
ShowHTML("</center>");
Rodape();

$w_chave=null;

$w_chave_pai=null;

$w_chave_aux=null;

$w_observacao=null;


$w_troca=null;

$i=null;

$w_erro=null;

return $function_ret;
} 

// =========================================================================

// Rotina de tramitação

// -------------------------------------------------------------------------

function Encaminhamento()
{
  extract($GLOBALS);





$w_Chave=${"w_Chave"};
$w_chave_aux=${"w_chave_aux"};
$w_troca=${"w_troca"};

if ($w_troca>"")
{
// Se for recarga da página

$w_tramite=${"w_tramite"};
$w_destinatario=${"w_destinatario"};
$w_novo_tramite=${"w_novo_tramite"};
$w_despacho=${"w_despacho"};
}
  else
{

$DB_GetSolicData$RS$w_chave//ORPGERAL"$w_tramite=$RS["sq_siw_tramite"];
$w_novo_tramite=$RS["sq_siw_tramite"];
DesconectaBD();
} 


// Recupera a sigla do trâmite desejado, para verificar a lista de possíveis destinatários.

DB_GetTramiteData($RS,$w_novo_tramite);
$w_sg_tramite=$RS["sigla"];
DesconectaBD();

Cabecalho();
ShowHTML("<HEAD>");
ShowHTML("<meta http-equiv=\"Refresh\" content=\"300; URL=../".MontaURL("MESA")."\">");
if ((strpos("V",$O) ? strpos("V",$O)+1 : 0)>0)
{

ScriptOpen("JavaScript");
ValidateOpen("Validacao");
Validate("w_destinatario","Destinatário","HIDDEN","1","1","10","","1");
Validate("w_despacho","Despacho","","1","1","2000","1","1");
Validate("w_assinatura","Assinatura Eletrônica","1","1","6","30","1","1");
if ($P1!=1)
{
// Se não for encaminhamento

ShowHTML("  theForm.Botao[0].disabled=true;");
ShowHTML("  theForm.Botao[1].disabled=true;");
}
  else
{

ShowHTML("  theForm.Botao.disabled=true;");
} 

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
{

BodyOpen("onLoad='document.Form.w_destinatario.focus()';");
} 

ShowHTML("<B><FONT COLOR=\"#000000\">".$w_TP."</FONT></B>");
ShowHTML("<HR>");
ShowHTML("<div align=center><center>");
ShowHTML("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">");

// Chama a rotina de visualização dos dados da tarefa, na opção "Listagem"

ShowHTML(VisualDemanda($w_chave,"V",$w_usuario));

ShowHTML("<HR>");
AbreForm("Form",$w_dir.$w_pagina."Grava","POST","return(Validacao(this));",null,$P1,$P2,$P3,$P4,$TP,"ORPENVIO",$R,$O);
ShowHTML(MontaFiltro("POST"));
ShowHTML("<INPUT type=\"hidden\" name=\"w_chave\" value=\"".$w_chave."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_troca\" value=\"\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_menu\" value=\"".$w_menu."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_tramite\" value=\"".$w_tramite."\">");

ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">");
ShowHTML("  <table width=\"97%\" border=\"0\">");
ShowHTML("    <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0><tr valign=\"top\">");
if ($P1!=1)
{
// Se não for cadastramento

SelecaoFase("<u>F</u>ase da tarefa:","F","Se deseja alterar a fase atual da tarefa, selecione a fase para a qual deseja enviá-la.",$w_novo_tramite,$w_menu,"w_novo_tramite",null,"onChange=\"document.Form.action='".$w_dir.$w_pagina.$par."'; document.Form.O.value='".$O."'; document.Form.w_troca.value='w_destinatario'; document.Form.submit();\"");
// Se for envio para o cadastramento, exibe apenas as pessoas autorizadas a fazê-lo.

if ($w_sg_tramite=="CI")
{

SelecaoSolicResp("<u>D</u>estinatário:","D","Selecione, na relação, um destinatário para a tarefa.",$w_destinatario,$w_chave,$w_novo_tramite,$w_novo_tramite,"w_destinatario","CADASTRAMENTO");
}
  else
{

SelecaoPessoa("<u>D</u>estinatário:","D","Selecione, na relação, um destinatário para a tarefa.",$w_destinatario,null,"w_destinatario","USUARIOS");
} 

}
  else
{

SelecaoFase("<u>F</u>ase da tarefa:","F","Se deseja alterar a fase atual da tarefa, selecione a fase para a qual deseja enviá-la.",$w_novo_tramite,$w_menu,"w_novo_tramite",null,null);
SelecaoPessoa("<u>D</u>estinatário:","D","Selecione, na relação, um destinatário para a tarefa.",$w_destinatario,null,"w_destinatario","USUARIOS");
} 

ShowHTML("    <tr><td valign=\"top\" colspan=2><font size=\"1\"><b>D<u>e</u>spacho:</b><br><textarea ".$w_Disabled." accesskey=\"E\" name=\"w_despacho\" class=\"STI\" ROWS=5 cols=75 title=\"Descreva o papel desempenhado pela área ou instituição na execução da tarefa.\">".$w_despacho."</TEXTAREA></td>");
ShowHTML("      </table>");
ShowHTML("      <tr><td align=\"LEFT\" colspan=4><font size=\"1\"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=\"A\" class=\"STI\" type=\"PASSWORD\" name=\"w_assinatura\" size=\"30\" maxlength=\"30\" value=\"\"></td></tr>");
ShowHTML("    <tr><td align=\"center\" colspan=4><hr>");
ShowHTML("      <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Enviar\">");
if ($P1!=1)
{
// Se não for cadastramento

// Volta para a listagem

DB_GetMenuData($RS,$w_menu);
ShowHTML("      <input class=\"STB\" type=\"button\" onClick=\"location.href='".str_replace($w_dir,"",$RS["link"])."&O=L&w_chave=".${"w_chave"}."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$rs["sigla"].MontaFiltro("GET")."';\" name=\"Botao\" value=\"Abandonar\">");
DesconectaBD();
} 

ShowHTML("      </td>");
ShowHTML("    </tr>");
ShowHTML("  </table>");
ShowHTML("  </TD>");
ShowHTML("</tr>");
ShowHTML("</FORM>");
ShowHTML("</table>");
ShowHTML("</center>");
Rodape();

$w_novo_tramite=null;

$w_chave=null;

$w_chave_pai=null;

$w_chave_aux=null;

$w_destinatario=null;

$w_despacho=null;


$w_troca=null;

$i=null;

$w_erro=null;

return $function_ret;
} 

// =========================================================================

// Rotina de anotação

// -------------------------------------------------------------------------

function Anotar()
{
  extract($GLOBALS);





$w_Chave=${"w_Chave"};
$w_chave_aux=${"w_chave_aux"};
$w_troca=${"w_troca"};

if ($w_troca>"")
{
// Se for recarga da página

$w_observacao=${"w_observacao"};
} 


Cabecalho();
ShowHTML("<HEAD>");
ShowHTML("<meta http-equiv=\"Refresh\" content=\"300; URL=../".MontaURL("MESA")."\">");
if ((strpos("V",$O) ? strpos("V",$O)+1 : 0)>0)
{

ScriptOpen("JavaScript");
ProgressBar("/siw/",$UploadID);
ValidateOpen("Validacao");
Validate("w_observacao","Anotação","","1","1","2000","1","1");
Validate("w_caminho","Arquivo","","","5","255","1","1");
Validate("w_assinatura","Assinatura Eletrônica","1","1","6","30","1","1");
if ($P1!=1)
{
// Se não for encaminhamento

ShowHTML("  theForm.Botao[0].disabled=true;");
ShowHTML("  theForm.Botao[1].disabled=true;");
}
  else
{

ShowHTML("  theForm.Botao.disabled=true;");
} 

ShowHTML("if (theForm.w_caminho.value != '') {return ProgressBar();}");
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
{

BodyOpen("onLoad='document.Form.w_observacao.focus()';");
} 

ShowHTML("<B><FONT COLOR=\"#000000\">".$w_TP."</FONT></B>");
ShowHTML("<HR>");
ShowHTML("<div align=center><center>");
ShowHTML("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">");

// Chama a rotina de visualização dos dados da tarefa, na opção "Listagem"

ShowHTML($VisualDemanda[$w_chave]["V"][$w_usuario]);

ShowHTML("<HR>");
ShowHTML("<FORM action=\"".$w_dir.$w_pagina."Grava&SG=ORPENVIO&O=".$O."&UploadID=".$UploadID."&w_menu=".$w_menu."\" name=\"Form\" onSubmit=\"return(Validacao(this));\" enctype=\"multipart/form-data\" method=\"POST\">");
ShowHTML("<INPUT type=\"hidden\" name=\"P1\" value=\"".$P1."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"P2\" value=\"".$P2."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"P3\" value=\"".$P3."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"P4\" value=\"".$P4."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"TP\" value=\"".$TP."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"R\" value=\"".$R."\">");

ShowHTML(MontaFiltro("POST"));
ShowHTML("<INPUT type=\"hidden\" name=\"w_chave\" value=\"".$w_chave."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_troca\" value=\"\">");
$DB_GetSolicData$RS$w_chave//ORPGERAL"ShowHTML("<INPUT type=\"hidden\" name=\"w_tramite\" value=\"".$RS["sq_siw_tramite"]."\">");
DesconectaBD();

ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">");
ShowHTML("  <table width=\"97%\" border=\"0\">");
ShowHTML("    <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0><tr valign=\"top\">");
DB_GetCustomerData($RS,$w_cliente);
ShowHTML("      <tr><td align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"2\"><b><font color=\"#BC3131\">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de ".$cDbl[$RS["upload_maximo"]]/1024." KBytes</b>.</font></td>");
ShowHTML("<INPUT type=\"hidden\" name=\"w_upload_maximo\" value=\"".$RS["upload_maximo"]."\">");
ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>A<u>n</u>otação:</b><br><textarea ".$w_Disabled." accesskey=\"N\" name=\"w_observacao\" class=\"STI\" ROWS=5 cols=75 title=\"Redija a anotação desejada.\">".$w_observacao."</TEXTAREA></td>");
ShowHTML("      <tr><td><font size=\"1\"><b>A<u>r</u>quivo:</b><br><input ".$w_Disabled." accesskey=\"R\" type=\"file\" name=\"w_caminho\" class=\"STI\" SIZE=\"80\" MAXLENGTH=\"100\" VALUE=\"\" title=\"OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor.\">");
ShowHTML("      </table>");
ShowHTML("      <tr><td align=\"LEFT\" colspan=4><font size=\"1\"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=\"A\" class=\"STI\" type=\"PASSWORD\" name=\"w_assinatura\" size=\"30\" maxlength=\"30\" value=\"\"></td></tr>");
ShowHTML("    <tr><td align=\"center\" colspan=4><hr>");
ShowHTML("      <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Gravar\">");
ShowHTML("      <input class=\"STB\" type=\"button\" onClick=\"history.back(1);\" name=\"Botao\" value=\"Abandonar\">");
ShowHTML("      </td>");
ShowHTML("    </tr>");
ShowHTML("  </table>");
ShowHTML("  </TD>");
ShowHTML("</tr>");
ShowHTML("</FORM>");
ShowHTML("</table>");
ShowHTML("</center>");
Rodape();

$w_chave=null;

$w_chave_pai=null;

$w_chave_aux=null;

$w_observacao=null;


$w_troca=null;

$i=null;

$w_erro=null;

return $function_ret;
} 

// =========================================================================

// Rotina de conclusão

// -------------------------------------------------------------------------

function Concluir()
{
  extract($GLOBALS);





$w_Chave=${"w_Chave"};
$w_chave_aux=${"w_chave_aux"};
$w_troca=${"w_troca"};

if ($w_troca>"")
{
// Se for recarga da página

$w_inicio_real=${"w_inicio_real"};
$w_fim_real=${"w_fim_real"};
$w_concluida=${"w_concluida"};
$w_data_conclusao=${"w_data_conclusao"};
$w_nota_conclusao=${"w_nota_conclusao"};
$w_custo_real=${"w_custo_real"};
} 


Cabecalho();
ShowHTML("<HEAD>");
ShowHTML("<meta http-equiv=\"Refresh\" content=\"300; URL=../".MontaURL("MESA")."\">");
if ((strpos("V",$O) ? strpos("V",$O)+1 : 0)>0)
{

ScriptOpen("JavaScript");
CheckBranco();
FormataData();
FormataDataHora();
FormataValor();
ProgressBar("/siw/",$UploadID);
ValidateOpen("Validacao");
switch ($RS_menu["data_hora"])
{
case 1:
Validate("w_fim_real","Término da execução","DATA",1,10,10,"","0123456789/");
break;
case 2:
Validate("w_fim_real","Término da execução","DATAHORA",1,17,17,"","0123456789/");
break;
case 3:
Validate("w_inicio_real","Início da execução","DATA",1,10,10,"","0123456789/");
Validate("w_fim_real","Término da execução","DATA",1,10,10,"","0123456789/");
CompData("w_inicio_real","Início da execução","<=","w_fim_real","Término da execução");
CompData("w_fim_real","Término da execução","<=",FormataDataEdicao($FormatDateTime[time()()][2]),"data atual");
break;
case 4:
Validate("w_inicio_real","Início da execução","DATAHORA",1,17,17,"","0123456789/,: ");
Validate("w_fim_real","Término da execução","DATAHORA",1,17,17,"","0123456789/,: ");
CompData("w_inicio_real","Início da execução","<=","w_fim_real","Término da execução");
break;
} 
Validate("w_custo_real","Rercurso executado","VALOR","1",4,18,"","0123456789.,");
Validate("w_nota_conclusao","Nota de conclusão","","1","1","2000","1","1");
Validate("w_assinatura","Assinatura Eletrônica","1","1","6","30","1","1");
if ($P1!=1)
{
// Se não for encaminhamento

ShowHTML("  theForm.Botao[0].disabled=true;");
ShowHTML("  theForm.Botao[1].disabled=true;");
}
  else
{

ShowHTML("  theForm.Botao.disabled=true;");
} 

ShowHTML("if (theForm.w_caminho.value != '') {return ProgressBar();}");
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
{

BodyOpen("onLoad='document.Form.w_inicio_real.focus()';");
} 

ShowHTML("<B><FONT COLOR=\"#000000\">".$w_TP."</FONT></B>");
ShowHTML("<HR>");
ShowHTML("<div align=center><center>");
ShowHTML("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">");

// Chama a rotina de visualização dos dados da tarefa, na opção "Listagem"

ShowHTML($VisualDemanda[$w_chave]["V"][$w_usuario]);

ShowHTML("<HR>");
ShowHTML("<FORM action=\"".$w_dir.$w_pagina."Grava&SG=GDCONC&O=".$O."&w_menu=".$w_menu."&UploadID=".$UploadID."\" name=\"Form\" onSubmit=\"return(Validacao(this));\" enctype=\"multipart/form-data\" method=\"POST\">");
ShowHTML("<INPUT type=\"hidden\" name=\"P1\" value=\"".$P1."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"P2\" value=\"".$P2."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"P3\" value=\"".$P3."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"P4\" value=\"".$P4."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"TP\" value=\"".$TP."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"R\" value=\"".$R."\">");

ShowHTML(MontaFiltro("POST"));
ShowHTML("<INPUT type=\"hidden\" name=\"w_chave\" value=\"".$w_chave."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_troca\" value=\"\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_concluida\" value=\"S\">");
$DB_GetSolicData$RS$w_chave//ORPGERAL"ShowHTML("<INPUT type=\"hidden\" name=\"w_tramite\" value=\"".$RS["sq_siw_tramite"]."\">");
DesconectaBD();

ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">");
ShowHTML("  <table width=\"97%\" border=\"0\">");
DB_GetCustomerData($RS,$w_cliente);
ShowHTML("      <tr><td align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"2\"><b><font color=\"#BC3131\">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de ".$cDbl[$RS["upload_maximo"]]/1024." KBytes</b>.</font></td>");
ShowHTML("<INPUT type=\"hidden\" name=\"w_upload_maximo\" value=\"".$RS["upload_maximo"]."\">");

ShowHTML("      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>");
ShowHTML("          <tr>");
switch ($RS_menu["data_hora"])
{
case 1:
ShowHTML("              <td valign=\"top\"><font size=\"1\"><b><u>T</u>érmino da execução:</b><br><input ".$w_Disabled." accesskey=\"T\" type=\"text\" name=\"w_fim_real\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$w_fim_real."\" onKeyDown=\"FormataData(this,event);\" title=\"Informe a data de término da execução da tarefa.\"></td>");
break;
case 2:
ShowHTML("              <td valign=\"top\"><font size=\"1\"><b><u>T</u>érmino da execução:</b><br><input ".$w_Disabled." accesskey=\"T\" type=\"text\" name=\"w_fim_real\" class=\"STI\" SIZE=\"17\" MAXLENGTH=\"17\" VALUE=\"".$w_fim_real."\" onKeyDown=\"FormataDataHora(this,event);\" title=\"Informe a data/hora de término da execução da tarefa.\"></td>");
break;
case 3:
ShowHTML("              <td valign=\"top\"><font size=\"1\"><b>Iní<u>c</u>io da execução:</b><br><input ".$w_Disabled." accesskey=\"C\" type=\"text\" name=\"w_inicio_real\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$w_inicio_real."\" onKeyDown=\"FormataData(this,event);\" title=\"Informe a data/hora de início da execução da tarefa.\"></td>");
ShowHTML("              <td valign=\"top\"><font size=\"1\"><b><u>T</u>érmino da execução:</b><br><input ".$w_Disabled." accesskey=\"T\" type=\"text\" name=\"w_fim_real\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$w_fim_real."\" onKeyDown=\"FormataData(this,event);\" title=\"Informe a data de término da execução da tarefa.\"></td>");
break;
case 4:
ShowHTML("              <td valign=\"top\"><font size=\"1\"><b>Iní<u>c</u>io da execução:</b><br><input ".$w_Disabled." accesskey=\"C\" type=\"text\" name=\"w_inicio_real\" class=\"STI\" SIZE=\"17\" MAXLENGTH=\"17\" VALUE=\"".$w_inicio_real."\" onKeyDown=\"FormataDataHora(this,event);\" title=\"Informe a data/hora de início da execução da tarefa.\"></td>");
ShowHTML("              <td valign=\"top\"><font size=\"1\"><b><u>T</u>érmino da execução:</b><br><input ".$w_Disabled." accesskey=\"T\" type=\"text\" name=\"w_fim_real\" class=\"STI\" SIZE=\"17\" MAXLENGTH=\"17\" VALUE=\"".$w_fim_real."\" onKeyDown=\"FormataDataHora(this,event);\" title=\"Informe a data de término da execução da tarefa.\"></td>");
break;
} 
ShowHTML("              <td valign=\"top\"><font size=\"1\"><b><u>R</u>ecurso executado:</b><br><input ".$w_Disabled." accesskey=\"O\" type=\"text\" name=\"w_custo_real\" class=\"STI\" SIZE=\"18\" MAXLENGTH=\"18\" VALUE=\"".$w_custo_real."\" onKeyDown=\"FormataValor(this,18,2,event);\" title=\"Informe o recurso utilizado para execução da tarefa, ou zero se não for o caso.\"></td>");
ShowHTML("          </table>");
ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>Nota d<u>e</u> conclusão:</b><br><textarea ".$w_Disabled." accesskey=\"E\" name=\"w_nota_conclusao\" class=\"STI\" ROWS=5 cols=75 title=\"Descreva o quanto a demanda atendeu aos resultados esperados.\">".$w_nota_conclusao."</TEXTAREA></td>");
ShowHTML("      <tr><td><font size=\"1\"><b>A<u>r</u>quivo:</b><br><input ".$w_Disabled." accesskey=\"R\" type=\"file\" name=\"w_caminho\" class=\"STI\" SIZE=\"80\" MAXLENGTH=\"100\" VALUE=\"\" title=\"OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor.\">");
ShowHTML("      <tr><td align=\"LEFT\" colspan=4><font size=\"1\"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=\"A\" class=\"STI\" type=\"PASSWORD\" name=\"w_assinatura\" size=\"30\" maxlength=\"30\" value=\"\"></td></tr>");
ShowHTML("    <tr><td align=\"center\" colspan=4><hr>");
ShowHTML("      <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Concluir\">");
if ($P1!=1)
{
// Se não for cadastramento

ShowHTML("      <input class=\"STB\" type=\"button\" onClick=\"history.back(1);\" name=\"Botao\" value=\"Abandonar\">");
} 

ShowHTML("      </td>");
ShowHTML("    </tr>");
ShowHTML("  </table>");
ShowHTML("  </TD>");
ShowHTML("</tr>");
ShowHTML("</FORM>");
ShowHTML("</table>");
ShowHTML("</center>");
Rodape();

$w_chave=null;

$w_chave_pai=null;

$w_chave_aux=null;

$w_destinatario=null;

$w_nota_conclusao=null;


$w_troca=null;

$i=null;

$w_erro=null;

return $function_ret;
} 

// =========================================================================

// Rotina de preparação para envio de e-mail relativo a tarefas

// Finalidade: preparar os dados necessários ao envio automático de e-mail

// Parâmetro: p_solic: número de identificação da solicitação. 

//            p_tipo:  1 - Inclusão

//                     2 - Tramitação

//                     3 - Conclusão

// -------------------------------------------------------------------------

function SolicMail($p_solic,$p_tipo)
{
  extract($GLOBALS);




$l_solic=$p_solic;
$w_destinatarios="";
$w_resultado="";

$w_html="<HTML>"."\r\n";
$w_html=$w_html.BodyOpenMail(null)."\r\n";
$w_html=$w_html."<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">"."\r\n";
$w_html=$w_html."<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">"."\r\n";
$w_html=$w_html."    <table width=\"97%\" border=\"0\">"."\r\n";
if ($p_tipo==1)
{

$w_html=$w_html."      <tr valign=\"top\"><td align=\"center\"><font size=2><b>INCLUSÃO DE TAREFA</b></font><br><br><td></tr>"."\r\n";
}
  else
if ($p_tipo==2)
{

$w_html=$w_html."      <tr valign=\"top\"><td align=\"center\"><font size=2><b>TRAMITAÇÃO DE TAREFA</b></font><br><br><td></tr>"."\r\n";
}
  else
if ($p_tipo==3)
{

$w_html=$w_html."      <tr valign=\"top\"><td align=\"center\"><font size=2><b>CONCLUSÃO DE TAREFA</b></font><br><br><td></tr>"."\r\n";
} 

$w_html=$w_html."      <tr valign=\"top\"><td><font size=2><b><font color=\"#BC3131\">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></tr>"."\r\n";


// Recupera os dados da tarefa

$DB_GetSolicData$RSM$p_solic//GDGERAL"
$w_nome="Tarefa ".$RSM["sq_siw_solicitacao"];

$w_html=$w_html."\r\n"."<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">";
$w_html=$w_html."\r\n"."    <table width=\"99%\" border=\"0\">";
$w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Ação: <b>".$RSM["nm_projeto"]."</b></td>";
if (!!isset($RSM["nm_etapa"]))
{

$w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Etapa: <b>".MontaOrdemEtapa($RSM["sq_projeto_etapa"]).". ".$RSM["nm_etapa"]." </b></td>";
} 

$w_html=$w_html."\r\n"."      <tr><td><font size=1>Detalhamento: <b>".CRLF2BR($RSM["assunto"])."</b></font></td></tr>";

// Identificação da tarefa

$w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>EXTRATO DA TAREFA</td>";
// Se a classificação foi informada, exibe.

if (!!isset($RSM["sq_cc"]))
{

$w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Classificação:<br><b>".$RSM["cc_nome"]." </b></td>";
} 

$w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>";
$w_html=$w_html."\r\n"."          <tr valign=\"top\">";
$w_html=$w_html."\r\n"."          <td><font size=\"1\">Responsável pelo monitoramento:<br><b>".$RSM["nm_sol"]."</b></td>";
$w_html=$w_html."\r\n"."          <td><font size=\"1\">Unidade responsável pelo monitoramento:<br><b>".$RSM["nm_unidade_resp"]."</b></td>";
$w_html=$w_html."\r\n"."          <tr valign=\"top\">";
$w_html=$w_html."\r\n"."          <td><font size=\"1\">Data de recebimento:<br><b>".FormataDataEdicao($RSM["inicio"])." </b></td>";
$w_html=$w_html."\r\n"."          <td><font size=\"1\">Limite para conclusão:<br><b>".FormataDataEdicao($RSM["fim"])." </b></td>";
$w_html=$w_html."\r\n"."          <td><font size=\"1\">Prioridade:<br><b>".RetornaPrioridade($RSM["prioridade"])." </b></td>";
$w_html=$w_html."\r\n"."          </table>";

// Informações adicionais

if (Nvl($RSM["descricao"],"")>"")
{

if (Nvl($RSM["descricao"],"")>"")
{
$w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Resultados da tarefa:<br><b>".CRLF2BR($RSM["descricao"])." </b></td>";
}
;
} 
} 


$w_html=$w_html."\r\n"."    </table>";
$w_html=$w_html."\r\n"."</tr>";

// Dados da conclusão da tarefa, se ela estiver nessa situação

if ($RSM["concluida"]=="S" && Nvl($RSM["data_conclusao"],"")>"")
{

$w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>DADOS DA CONCLUSÃO</td>";
$w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>";
$w_html=$w_html."\r\n"."          <tr valign=\"top\">";
$w_html=$w_html."\r\n"."          <td><font size=\"1\">Início da execução:<br><b>".FormataDataEdicao($RSM["inicio_real"])." </b></td>";
$w_html=$w_html."\r\n"."          <td><font size=\"1\">Término da execução:<br><b>".FormataDataEdicao($RSM["fim_real"])." </b></td>";
$w_html=$w_html."\r\n"."          </table>";
$w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Nota de conclusão:<br><b>".CRLF2BR($RSM["nota_conclusao"])." </b></td>";
} 


if ($p_tipo==2)
{
// Se for tramitação

// Encaminhamentos

DB_GetSolicLog($RS,$p_solic,null,"LISTA");
$RS->Sort="data desc";
$w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>ÚLTIMO ENCAMINHAMENTO</td>";
$w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>";
$w_html=$w_html."\r\n"."          <tr valign=\"top\">";
$w_html=$w_html."\r\n"."          <td><font size=\"1\">De:<br><b>".$RS["responsavel"]."</b></td>";
$w_html=$w_html."\r\n"."          <td><font size=\"1\">Para:<br><b>".$RS["destinatario"]."</b></td>";
$w_html=$w_html."\r\n"."          <tr valign=\"top\"><td colspan=2><font size=\"1\">Despacho:<br><b>".CRLF2BR(Nvl($RS["despacho"],"---"))." </b></td>";
$w_html=$w_html."\r\n"."          </table>";

// Configura o destinatário da tramitação como destinatário da mensagem

DB_GetPersonData($RS,$w_cliente,$RS["sq_pessoa_destinatario"],null,null);
$w_destinatarios=$RS["email"]."; ";

DesconectaBD();
} 


$w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>OUTRAS INFORMAÇÕES</td>";
$DB_GetCustomerSite$RSsession_register("p_cliente");
${"p_cliente"."_session"}=$w_html=$w_html."      <tr valign=\"top\"><td><font size=2>"."\r\n";
$w_html=$w_html."         Para acessar o sistema use o endereço: <b><a class=\"SS\" href=\"".$RS["logradouro"]."\" target=\"_blank\">".$RS["Logradouro"]."</a></b></li>"."\r\n";
$w_html=$w_html."      </font></td></tr>"."\r\n";
DesconectaBD();

$w_html=$w_html."      <tr valign=\"top\"><td><font size=2>"."\r\n";
$w_html=$w_html."         Dados da ocorrência:<br>"."\r\n";
$w_html=$w_html."         <ul>"."\r\n";
$w_html=$w_html."         <li>Responsável: <b>".$nome_session."</b></li>"."\r\n";
$w_html=$w_html."         <li>Data do servidor: <b>".$FormatDateTime[time()()][1].", ".strftime("%H:%M:%S %p")()."</b></li>"."\r\n";
$w_html=$w_html."         <li>IP de origem: <b>".$_SERVER["REMOTE_HOST"]."</b></li>"."\r\n";
$w_html=$w_html."         </ul>"."\r\n";
$w_html=$w_html."      </font></td></tr>"."\r\n";
$w_html=$w_html."    </table>"."\r\n";
$w_html=$w_html."</td></tr>"."\r\n";
$w_html=$w_html."</table>"."\r\n";
$w_html=$w_html."</BODY>"."\r\n";
$w_html=$w_html."</HTML>"."\r\n";

// Recupera o e-mail do responsável

DB_GetPersonData($RS,$w_cliente,$RSM["solicitante"],null,null);
if ((strpos($w_destinatarios,$RS["email"]."; ") ? strpos($w_destinatarios,$RS["email"]."; ")+1 : 0)==0)
{
$w_destinatarios=$w_destinatarios.$RS["email"]."; ";
}
;
} 
DesconectaBD();

// Recupera o e-mail do titular e do substituto pelo setor responsável

DB_GetUorgResp($RS,$RSM["sq_unidade"]);
if ((strpos($w_destinatarios,$RS["email_titular"]."; ") ? strpos($w_destinatarios,$RS["email_titular"]."; ")+1 : 0)==0 && Nvl($RS["email_titular"],"nulo")!="nulo")
{
$w_destinatarios=$w_destinatarios.$RS["email_titular"]."; ";
}
;
} 
if ((strpos($w_destinatarios,$RS["email_substituto"]."; ") ? strpos($w_destinatarios,$RS["email_substituto"]."; ")+1 : 0)==0 && Nvl($RS["email_substituto"],"nulo")!="nulo")
{
$w_destinatarios=$w_destinatarios.$RS["email_substituto"]."; ";
}
;
} 
DesconectaBD();

// Prepara os dados necessários ao envio

DB_GetCustomerData($RS,${"p_cliente"."_session"});
if ($p_tipo==1 || $p_tipo==3)
{
// Inclusão ou Conclusão

if ($p_tipo==1)
{
$w_assunto="Inclusão - ".$w_nome;
}
  else
{
$w_assunto="Conclusão - ".$w_nome;
}
;
} 
}
  else
if ($p_tipo==2)
{
// Tramitação

$w_assunto="Tramitação - ".$w_nome;
} 

DesconectaBD();

if ($w_destinatarios>"")
{

// Executa o envio do e-mail

$w_resultado=EnviaMail($w_assunto,$w_html,$w_destinatarios,null);
} 


// Se ocorreu algum erro, avisa da impossibilidade de envio

if ($w_resultado>"")
{

ScriptOpen("JavaScript");
ShowHTML("  alert('ATENÇÃO: não foi possível proceder o envio do e-mail.\n".$w_resultado."');");
ScriptClose();
} 


$RSM=null;

$w_html=null;

$p_solic=null;

$w_destinatarios=null;

$w_assunto=null;

return $function_ret;
} 

// =========================================================================

// Procedimento que executa as operações de BD

// -------------------------------------------------------------------------

function Grava()
{
  extract($GLOBALS);



$w_file="";
$w_tamanho="";
$w_tipo="";
$w_nome="";

Cabecalho();
ShowHTML("</HEAD>");
ShowHTML("<BASE HREF=\"".$conRootSIW."\">");
BodyOpen("onLoad=document.focus();");

AbreSessao();
switch ($SG)
{
case "ORPGERAL":
// Verifica se a Assinatura Eletrônica é válida

if ((VerificaAssinaturaEletronica($Username_session,$w_assinatura) && $w_assinatura>"") || 
   $w_assinatura=="")
{

// Se for operação de exclusão, verifica se é necessário excluir os arquivos físicos

if ($O=="E")
{

DB_GetSolicLog($RS,${"w_chave"},null,"LISTA");
// Mais de um registro de log significa que deve ser cancelada, e não excluída.

// Nessa situação, não é necessário excluir os arquivos.

if ($RS->RecordCount<=1)
{

DB_GetSolicAnexo($RS,${"w_chave"},null,$w_cliente);
while(!$RS->EOF)
{

$FS=$CreateObject["Scripting.FileSystemObject"]
if ($FS->FileExists($conFilePhysical.$w_cliente."\".$RS["caminho"]))
{

$FS->DeleteFile$conFilePhysical.$w_cliente."\".$RS["caminho"];
} 

$RS->MoveNext;
} 
} 

}
  else
{

//Recupera 10  dos dias de prazo da tarefa, para emitir o alerta  

$DB_Get10PercentDays$RS${"w_inicio"}${"w_fim"}
$w_dias=$RS["dias"];
DesconectaBD();
} 


DML_PutDemandaGeral($O,
${"w_chave"},${"w_menu"},$lotacao_session,${"w_solicitante"},${"w_proponente"},
${"sq_pessoa"."_session"},null,${"w_sqcc"},${"w_descricao"},${"w_justificativa"},${"w_ordem"},${"w_inicio"},${"w_fim"},${"w_valor"});

ScriptOpen("JavaScript");
if ($O=="I")
{

// Envia e-mail comunicando a inclusão

$SolicMailNvl(${"w_chave"},$w_chave_nova)

// Recupera os dados para montagem correta do menu

$DB_GetMenuData$RS1$w_menu;
ShowHTML("  parent.menu.location='../Menu.asp?par=ExibeDocs&O=A&w_chave=".$w_chave_nova."&w_documento=Nr. ".$w_chave_nova."&R=".$R."&SG=".$RS1["sigla"]."&TP=".$TP.MontaFiltro("GET")."';");
}
  else
if ($O=="E")
{

ShowHTML("  location.href='".$R."&O=L&R=".$R."&SG=ORPCAD&w_menu=".$w_menu."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&p_proponente=".${"p_proponente"}.MontaFiltro("GET")."';");
}
  else
{

// Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link

$DB_GetLinkData$RS1session_register("p_cliente");
${"p_cliente"."_session"}=$SG;
ShowHTML("  location.href='".str_replace($w_dir,"",$RS1["link"])."&O=".$O."&w_chave=".${"w_Chave"}."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."';");
} 

ScriptClose();
}
  else
{

ScriptOpen("JavaScript");
ShowHTML("  alert('Assinatura Eletrônica inválida!');");
ShowHTML("  history.back(1);");
ScriptClose();
} 

break;
case "ORPINTERES":
// Verifica se a Assinatura Eletrônica é válida

if ((VerificaAssinaturaEletronica($Username_session,$w_assinatura) && $w_assinatura>"") || 
   $w_assinatura=="")
{


DML_PutDemandaInter($O,${"w_chave"},${"w_chave_aux"},${"w_tipo_visao"},${"w_envia_email"});

ScriptOpen("JavaScript");
// Recupera a sigla do serviço pai, para fazer a chamada ao menu

$DB_GetLinkData$RSsession_register("p_cliente");
${"p_cliente"."_session"}=$SG;
ShowHTML("  location.href='".str_replace($w_dir,"",$RS["link"])."&O=L&w_chave=".${"w_chave"}."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."';");
DesconectaBD();
ScriptClose();
}
  else
{

ScriptOpen("JavaScript");
ShowHTML("  alert('Assinatura Eletrônica inválida!');");
ShowHTML("  history.back(1);");
ScriptClose();
} 

break;
case "ORPAREAS":
// Verifica se a Assinatura Eletrônica é válida

if ((VerificaAssinaturaEletronica($Username_session,$w_assinatura) && $w_assinatura>"") || 
   $w_assinatura=="")
{


DML_PutDemandaAreas($O,${"w_chave"},${"w_chave_aux"},${"w_papel"});

ScriptOpen("JavaScript");
// Recupera a sigla do serviço pai, para fazer a chamada ao menu

$DB_GetLinkData$RSsession_register("p_cliente");
${"p_cliente"."_session"}=$SG;
ShowHTML("  location.href='".str_replace($w_dir,"",$RS["link"])."&O=L&w_chave=".${"w_chave"}."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."';");
DesconectaBD();
ScriptClose();
}
  else
{

ScriptOpen("JavaScript");
ShowHTML("  alert('Assinatura Eletrônica inválida!');");
ShowHTML("  history.back(1);");
ScriptClose();
} 

break;
case "ORPANEXO":
// Verifica se a Assinatura Eletrônica é válida

if ((VerificaAssinaturaEletronica($Username_session,$w_assinatura) && $w_assinatura>"") || 
   $w_assinatura=="")
{


// Se foi feito o upload de um arquivo  

$FS=$CreateObject["Scripting.FileSystemObject"]
if ($ul->State==0)
{

$w_maximo=$ul->Texts.$Item["w_upload_maximo"];
foreach ($ul->Files as $Field)
{
$Items;
if ($Field->Length>0)
{

// Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 

if ($cDbl[$Field->Length]>$cDbl[$w_maximo])
{

  ScriptOpen("JavaScript");
  ShowHTML("  alert('Atenção: o tamanho máximo do arquivo não pode exceder ".$cDbl[$w_maximo]/1024." KBytes!');");
  ShowHTML("  history.back(1);");
  ScriptClose();
  exit();

  return $function_ret;

} 


// Se já há um nome para o arquivo, mantém 

$FS=$CreateObject["Scripting.FileSystemObject"]
if ($ul->Texts$Item["w_atual"]>"")
{

  DB_GetSolicAnexo($RS,$ul->Texts.$Item["w_chave"],$ul->Texts.$Item["w_atual"],$w_cliente);
$FS->DeleteFile$conFilePhysical.$w_cliente."\".$RS["caminho"];
  $w_file=substr($RS["caminho"],0,(strpos($RS["caminho"],".") ? strpos($RS["caminho"],".")+1 : 0)-1).substr($Field->FileName,(strpos($Field->FileName,".") ? strpos($Field->FileName,".")+1 : 0)-1,30);
}
  else
{

  $w_file=str_replace(".tmp",substr($Field->FileName,(strpos($Field->FileName,".") ? strpos($Field->FileName,".")+1 : 0)-1,30),$FS->GetTempName());
} 

$w_tamanho=$Field->Length;
$w_tipo=$Field->ContentType;
$w_nome=$Field->FileName;
$Field->SaveAs($conFilePhysical.$w_cliente."\".$w_file);

} 

} 
//Response.Write UploadID & "w_file: " & w_file & "<br> " & "w_tamanho: " & w_tamanho & "<br> " & "w_tipo: " & w_tipo & "<br> " & "w_nome: " & w_nome

//Response.End()


// Se for exclusão e houver um arquivo físico, deve remover o arquivo do disco.  

if ($O=="E" && $ul->Texts$Item["w_atual"]>"")
{

DB_GetSolicAnexo($RS,$ul->Texts.$Item["w_chave"],$ul->Texts.$Item["w_atual"],$w_cliente);
$FS->DeleteFile$conFilePhysical.$w_cliente."\".$RS["caminho"];
DesconectaBD();
} 


//Response.Write O& ", " &w_cliente& ", " &ul.Texts.Item("w_chave")& ", " &ul.Texts.Item("w_chave_aux")& ", " &ul.Texts.Item("w_nome")& ", " &ul.Texts.Item("w_descricao")

//Response.End()

DML_PutSolicArquivo($O,
$w_cliente,$ul->Texts.$Item["w_chave"],$ul->Texts.$Item["w_chave_aux"],$ul->Texts.$Item["w_nome"],$ul->Texts.$Item["w_descricao"],
$w_file,$w_tamanho,$w_tipo,$w_nome);
}
  else
{

ScriptOpen("JavaScript");
ShowHTML("  alert('ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!');");
ScriptClose();
exit();

return $function_ret;

} 


ScriptOpen("JavaScript");
// Recupera a sigla do serviço pai, para fazer a chamada ao menu 

$DB_GetLinkData$RSsession_register("p_cliente");
${"p_cliente"."_session"}=$SG;
ShowHTML("  location.href='".str_replace($w_dir,"",$RS["link"])."&O=L&w_chave=".$ul->Texts.$Item["w_chave"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."';");
DesconectaBD();
ScriptClose();
}
  else
{

ScriptOpen("JavaScript");
ShowHTML("  alert('Assinatura Eletrônica inválida!');");
ShowHTML("  history.back(1);");
ScriptClose();
} 

break;
case "ORPENVIO":
// Verifica se a Assinatura Eletrônica é válida 

if ((VerificaAssinaturaEletronica($Username_session,$w_assinatura) && $w_assinatura>"") || 
   $w_assinatura=="")
{


// Trata o recebimento de upload ou dados 

if ((strpos(strtoupper($_SERVER["http_content_type"]),"MULTIPART/FORM-DATA") ? strpos(strtoupper($_SERVER["http_content_type"]),"MULTIPART/FORM-DATA")+1 : 0)>0)
{

// Se foi feito o upload de um arquivo 

if ($ul->State==0)
{

$w_maximo=$ul->Texts.$Item["w_upload_maximo"];
foreach ($ul->Files as $Field)
{
$Items;
if ($Field->Length>0)
{

// Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 

  if ($cDbl[$Field->Length]>$cDbl[$w_maximo])
  {

    ScriptOpen("JavaScript");
    ShowHTML("  alert('Atenção: o tamanho máximo do arquivo não pode exceder ".$cDbl[$w_maximo]/1024." KBytes!');");
    ShowHTML("  history.back(1);");
    ScriptClose();
    exit();

    return $function_ret;

  } 


// Se já há um nome para o arquivo, mantém 

$FS=$CreateObject["Scripting.FileSystemObject"]
  $w_file=nvl($ul->Texts.$Item["w_atual"],str_replace(".tmp",substr($Field->FileName,(strpos($Field->FileName,".") ? strpos($Field->FileName,".")+1 : 0)-1,30),$FS->GetTempName()));
  $w_tamanho=$Field->Length;
  $w_tipo=$Field->ContentType;
  $w_nome=$Field->FileName;
$Field->SaveAs($conFilePhysical.$w_cliente."\".$w_file);

} 

} 
DML_PutDemandaEnvio($w_menu,$ul->Texts.$Item["w_chave"],$w_usuario,$ul->Texts.$Item["w_tramite"],
$ul->Texts.$Item["w_novo_tramite"],"N",$ul->Texts.$Item["w_observacao"],$ul->Texts.$Item["w_destinatario"],$ul->Texts.$Item["w_despacho"],
$w_file,$w_tamanho,$w_tipo,$w_nome);
}
  else
{

ScriptOpen("JavaScript");
ShowHTML("  alert('ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!');");
ScriptClose();
} 


ScriptOpen("JavaScript");
// Volta para a listagem 

$DB_GetMenuData$RS$w_menu;
ShowHTML("  location.href='".str_replace($w_dir,"",$RS["link"])."&O=L&w_chave=".$ul->Texts.$Item["w_chave"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".RemoveTP($TP)."&SG=".$rs["sigla"].MontaFiltro("UPLOAD")."';");
DesconectaBD();
ScriptClose();
}
  else
{

DML_PutDemandaEnvio(${"w_menu"},${"w_chave"},$w_usuario,${"w_tramite"},
${"w_novo_tramite"},"N",${"w_observacao"},${"w_destinatario"},${"w_despacho"},
null,null,null,null);

// Envia e-mail comunicando de tramitação

$SolicMail${"w_chave"}

if ($P1==1)
{
// Se for envio da fase de cadastramento, remonta o menu principal

// Recupera os dados para montagem correta do menu

$DB_GetMenuData$RS$w_menu;
ScriptOpen("JavaScript");
ShowHTML("  parent.menu.location='../Menu.asp?par=ExibeDocs&O=L&R=".$R."&SG=".$RS["sigla"]."&TP=".RemoveTP(RemoveTP($TP)).MontaFiltro("GET")."';");
ScriptClose();
DesconectaBD();
}
  else
{

ScriptOpen("JavaScript");
// Volta para a listagem 

$DB_GetMenuData$RS${"w_menu"}
ShowHTML("  location.href='".str_replace($w_dir,"",$RS["link"])."&O=L&w_chave=".${"w_chave"}."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".RemoveTP($TP)."&SG=".$rs["sigla"].MontaFiltro("GET")."';");
DesconectaBD();
ScriptClose();
} 

} 

}
  else
{

ScriptOpen("JavaScript");
ShowHTML("  alert('Assinatura Eletrônica inválida!');");
ShowHTML("  history.back(1);");
ScriptClose();
} 

break;
case "GDCONC":
// Verifica se a Assinatura Eletrônica é válida

if ((VerificaAssinaturaEletronica($Username_session,$w_assinatura) && $w_assinatura>"") || 
   $w_assinatura=="")
{


$DB_GetSolicData$RS$ul->Texts.$Item["w_chave"]//GDGERAL" if ($cDbl[$RS["sq_siw_tramite"]]!=$cDbl[$ul->Texts.$Item["w_tramite"]])
{

ScriptOpen("JavaScript");
ShowHTML("  alert('ATENÇÃO: Outro usuário já encaminhou esta tarefa para outra fase de execução!');");
ScriptClose();
}
  else
{

// Se foi feito o upload de um arquivo  

if ($ul->State==0)
{

$w_maximo=$ul->Texts.$Item["w_upload_maximo"];
foreach ($ul->Files as $Field)
{
$Items;
if ($Field->Length>0)
{

// Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 

  if ($cDbl[$Field->Length]>$cDbl[$w_maximo])
  {

    ScriptOpen("JavaScript");
    ShowHTML("  alert('Atenção: o tamanho máximo do arquivo não pode exceder ".$cDbl[$w_maximo]/1024." KBytes!');");
    ShowHTML("  history.back(1);");
    ScriptClose();
    exit();

    return $function_ret;

  } 


// Se já há um nome para o arquivo, mantém 

$FS=$CreateObject["Scripting.FileSystemObject"]
  $w_file=nvl($ul->Texts.$Item["w_atual"],str_replace(".tmp",substr($Field->FileName,(strpos($Field->FileName,".") ? strpos($Field->FileName,".")+1 : 0)-1,30),$FS->GetTempName()));
  $w_tamanho=$Field->Length;
  $w_tipo=$Field->ContentType;
  $w_nome=$Field->FileName;
$Field->SaveAs($conFilePhysical.$w_cliente."\".$w_file);

} 

} 
DML_PutDemandaConc($w_menu,$ul->Texts.$Item["w_chave"],$w_usuario,$ul->Texts.$Item["w_tramite"],$ul->Texts.$Item["w_inicio_real"],$ul->Texts.$Item["w_fim_real"],$ul->Texts.$Item["w_nota_conclusao"],$ul->Texts.$Item["w_custo_real"],
$w_file,$w_tamanho,$w_tipo,$w_nome);

}
  else
{

ScriptOpen("JavaScript");
ShowHTML("  alert('ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!');");
ShowHTML("  history.back(1);");
ScriptClose();
exit();

return $function_ret;

} 


// Envia e-mail comunicando a conclusão

$SolicMail$ul->Texts.$Item["w_chave"]

ScriptOpen("JavaScript");
// Volta para a listagem

$DB_GetMenuData$RS$w_menu;
ShowHTML("  location.href='".str_replace($w_dir,"",$RS["link"])."&O=L&w_chave=".$ul->Texts.$Item["w_chave"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$rs["sigla"].MontaFiltro("UPLOAD")."';");
DesconectaBD();
ScriptClose();
} 

}
  else
{

ScriptOpen("JavaScript");
ShowHTML("  alert('Assinatura Eletrônica inválida!');");
ShowHTML("  history.back(1);");
ScriptClose();
} 

break;
default:

ScriptOpen("JavaScript");
ShowHTML("  alert('Bloco de dados não encontrado: ".$SG."');");
ShowHTML("  history.back(1);");
ScriptClose();
break;
} 

$w_chave_nova=null;

$w_file=null;

$FS=null;

$w_Mensagem=null;

$p_sq_endereco_unidade=null;

$p_modulo=null;

$w_Null=null;

return $function_ret;
} 

// =========================================================================

// Rotina principal

// -------------------------------------------------------------------------

function Main()
{
  extract($GLOBALS);


// Verifica se o usuário tem lotação e localização

if ((strlen($LOTACAO_session."")==0 || strlen($LOCALIZACAO_session."")==0) && ${"LogOn"."_session"}=="Sim")
{

ScriptOpen("JavaScript");
ShowHTML(" alert('Você não tem lotação ou localização definida. Entre em contato com o RH!'); ");
ShowHTML(" top.location.href='Default.asp'; ");
$ScriptClose;
return $function_ret;

} 


switch ($Par)
{
case "INICIAL":
$Inicial;
break;
case "GERAL":
$Geral;
break;
case "ANEXO":
$Anexos;
break;
case "INTERESS":
$Interessados;
break;
case "AREAS":
$Areas;
break;
case "VISUAL":
$Visual;
break;
case "EXCLUIR":
$Excluir;
break;
case "ENVIO":
$Encaminhamento;
break;
case "TRAMITE":
$Tramitacao;
break;
case "ANOTACAO":
$Anotar;
break;
case "CONCLUIR":
$Concluir;
break;
case "GRAVA":
$Grava;
break;
default:

Cabecalho();
ShowHTML("<BASE HREF=\"".$conRootSIW."\">");
BodyOpen("onLoad=document.focus();");
ShowHTML("<B><FONT COLOR=\"#000000\">".$w_TP."</FONT></B>");
ShowHTML("<HR>");
ShowHTML("<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=\"images/icone/underc.gif\" align=\"center\"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>");
Rodape();
break;
} 
return $function_ret;
} 
?>


