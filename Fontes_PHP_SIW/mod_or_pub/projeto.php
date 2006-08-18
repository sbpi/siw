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
<!-- #INCLUDE FILE="../DB_Cliente.php" -->
<!-- #INCLUDE FILE="../DB_Seguranca.php" -->
<!-- #INCLUDE FILE="../DB_Link.php" -->
<!-- #INCLUDE FILE="../DB_EO.php" -->
<!-- #INCLUDE FILE="../DML_Projeto.php" -->
<!-- #INCLUDE FILE="../DML_Solic.php" -->
<!-- #INCLUDE FILE="../jScript.php" -->
<!-- #INCLUDE FILE="../Funcoes.php" -->
<!-- #INCLUDE FILE="../cp_upload/_upload.php" -->
<!-- #INCLUDE FILE="VisualProjeto.php" -->
<!-- #INCLUDE FILE="Funcoes.php" -->
<!-- #INCLUDE FILE="DB_Tabelas.php" -->
<!-- #INCLUDE FILE="DML_Tabelas.php" -->
<!-- #INCLUDE FILE="DB_SIAFI.php" -->
<? 
header("Expires: ".-1500);
// =========================================================================

//  /Projeto.asp

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
$w_pagina="Projeto.asp?par=";
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
  $p_sq_acao_ppa=strtoupper($ul->Texts.$Item["p_sq_acao_ppa"]);
  $p_sq_orprioridade=strtoupper($ul->Texts.$Item["p_sq_orprioridade"]);

  $P1=Nvl($ul->Texts.$Item["P1"],0);
  $P2=Nvl($ul->Texts.$Item["P2"],0);
  $P3=$cDbl[Nvl($ul->Texts.$Item["P3"],1)];
  $P4=$cDbl[Nvl($ul->Texts.$Item["P4"],$conPagesize)];
  $TP=$ul->Texts.$Item["TP"];
  $R=strtoupper($ul->Texts.$Item["R"]);
  $w_Assinatura=strtoupper($ul->Texts.$Item["w_Assinatura"]);
  $w_SG=strtoupper($ul->Texts.$Item["w_SG"]);

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
  $p_sq_acao_ppa=strtoupper(${"p_sq_acao_ppa"});
  $p_sq_orprioridade=strtoupper(${"p_sq_orprioridade"});

  $P1=Nvl(${"P1"},0);
  $P2=Nvl(${"P2"},0);
  $P3=$cDbl[Nvl(${"P3"},1)];
  $P4=$cDbl[Nvl(${"P4"},$conPagesize)];
  $TP=${"TP"};
  $R=strtoupper(${"R"});
  $w_Assinatura=strtoupper(${"w_Assinatura"});
  $w_SG=strtoupper(${"w_SG"});


  if ($SG=="ORRECURSO" || $SG=="ORETAPA" || $SG=="ORINTERESS" || $SG=="ORAREAS" || $SG=="ORRESP" || $SG=="ORANEXO")
  {

    if ($O!="I" && $O!="E" && ${"w_chave_aux"}=="")
    {
      $O="L";
    }
;
    } 
  }
    else
  if ($SG=="ORENVIO")
  {

    $O="V";
  }
    else
  if ($SG=="ORFINANC" && $P1==5)
  {

    $O="L";
    $P1="";
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

//If SG <> "ETAPAREC" Then 

//   w_menu         = RetornaMenu(w_cliente, SG) 

//Else

//   w_menu         = RetornaMenu(w_cliente, w_SG) 

//End If


// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.

//If SG <> "ETAPAREC" Then 

//   DB_GetLinkSubMenu RS, Session("p_cliente"), SG

//Else

//   DB_GetLinkSubMenu RS, Session("p_cliente"), w_SG

//End If


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

$p_sq_acao_ppa=null;

$p_sq_orprioridade=null;


$RS=null;

$RS1=null;

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

  if ((strpos(strtoupper($R),"GR_") ? strpos(strtoupper($R),"GR_")+1 : 0)>0)
  {

    $w_filtro="";
    if ($p_projeto>"")
    {

      DB_GetSolicData($RS,$p_projeto,"ORGERAL");
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Ação <td><font size=1>[<b>".$RS["titulo"]."</b>]";
    } 

    if ($p_atividade>"")
    {

      DB_GetSolicEtapa($RS,$p_projeto,$p_atividade,"REGISTRO",null);
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Etapa <td><font size=1>[<b>".$RS["titulo"]."</b>]";
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

    if ($p_sqcc>"")
    {

      DB_GetCCData($RS,$p_sqcc);
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Classificação <td><font size=1>[<b>".$RS["nome"]."</b>]";
    } 

    if ($p_chave>"")
    {
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Demanda nº <td><font size=1>[<b>".$p_chave."</b>]";
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

//If p_prioridade  > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Prioridade <td><font size=1>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]"   End If

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
      $w_filtro=$w_filtro."<tr valign=\"top\"><td align=\"right\"><font size=1>Parcerias internas <td><font size=1>[<b>".$p_palavra."</b>]";
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


  DB_GetLinkData($RS,$w_cliente,"ORCAD");

  if ($w_copia>"")
  {
// Se for cópia, aplica o filtro sobre todas as demandas visíveis pelo usuário

    DB_GetSolicList($RS,$RS["sq_menu"],$w_usuario,$SG,3,
    $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
    $p_unidade,$p_prioridade,$p_ativo,$p_proponente);
  }
    else
  {

    DB_GetSolicList($RS,$RS["sq_menu"],$w_usuario,$SG,$P1,
    $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
    $p_unidade,$p_prioridade,$p_ativo,$p_proponente);
    switch (${"p_agrega"})
    {
      case "GRPRRESPATU":
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
$RS->sort="fim, prioridade";
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

ShowHTML("<TITLE>".$conSgSistema." - Listagem de ações</TITLE>");
ScriptOpen("Javascript");
CheckBranco();
FormataData();
ValidateOpen("Validacao");
if ((strpos("CP",$O) ? strpos("CP",$O)+1 : 0)>0)
{

if ($P1!=1 || $O=="C")
{
// Se não for cadastramento ou se for cópia

  Validate("p_chave","Número da ação","","","1","18","","0123456789");
  Validate("p_prazo","Dias para a data limite","","","1","2","","0123456789");
  Validate("p_proponente","Proponente externo","","","2","90","1","");
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

if ($P1!=1 || $O=="C")
{
// Se for cadastramento

  BodyOpen("onLoad='document.Form.p_chave.focus()';");
}
  else
{

  BodyOpen("onLoad='document.Form.p_ordena.focus()';");
} 

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

ShowHTML("<tr><td><font size=\"2\">");
if ($P1==1 && $w_copia=="")
{
// Se for cadastramento e não for resultado de busca para cópia

  if ($w_submenu>"")
  {

    DB_GetLinkSubMenu($RS1,$w_cliente,${"SG"});
    ShowHTML("<tr><td><font size=\"1\">");
    ShowHTML("    <a accesskey=\"I\" class=\"SS\" href=\"".$w_dir.$w_pagina."Geral&R=".$w_pagina.$par."&O=I&SG=".$RS1["sigla"]."&w_menu=".$w_menu."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP.MontaFiltro("GET")."\"><u>I</u>ncluir</a>&nbsp;");
    ShowHTML("    <a accesskey=\"C\" class=\"SS\" href=\"".$w_dir.$w_pagina.$par."&R=".$w_Pagina.$par."&O=C&P1=".$P1."&P2=".$P2."&P3=1&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\"><u>C</u>opiar</a>");
  }
    else
  {

    ShowHTML("<tr><td><font size=\"1\"><a accesskey=\"I\" class=\"SS\" href=\"".$w_dir.$w_pagina.$par."&R=".$w_Pagina.$par."&O=I&P1=".$P1."&P2=".$P2."&P3=1&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\"><u>I</u>ncluir</a>&nbsp;");
  } 

} 

if ((strpos(strtoupper($R),"GR_") ? strpos(strtoupper($R),"GR_")+1 : 0)==0)
{

  if ($w_copia>"")
  {
// Se for cópia

    if (MontaFiltro("GET")>"")
    {

      ShowHTML("                         <a accesskey=\"F\" class=\"SS\" href=\"".$w_dir.$w_pagina.$par."&R=".$w_Pagina.$par."&O=C&P1=".$P1."&P2=".$P2."&P3=1&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\"><u><font color=\"#BC5100\">F</u>iltrar (Ativo)</font></a>");
    }
      else
    {

      ShowHTML("                         <a accesskey=\"F\" class=\"SS\" href=\"".$w_dir.$w_pagina.$par."&R=".$w_Pagina.$par."&O=C&P1=".$P1."&P2=".$P2."&P3=1&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\"><u>F</u>iltrar (Inativo)</a>");
    } 

  }
    else
  {

    if (MontaFiltro("GET")>"")
    {

      ShowHTML("                         <a accesskey=\"F\" class=\"SS\" href=\"".$w_dir.$w_pagina.$par."&R=".$w_Pagina.$par."&O=P&P1=".$P1."&P2=".$P2."&P3=1&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\"><u><font color=\"#BC5100\">F</u>iltrar (Ativo)</font></a>");
    }
      else
    {

      ShowHTML("                         <a accesskey=\"F\" class=\"SS\" href=\"".$w_dir.$w_pagina.$par."&R=".$w_Pagina.$par."&O=P&P1=".$P1."&P2=".$P2."&P3=1&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\"><u>F</u>iltrar (Inativo)</a>");
    } 

  } 

} 

ShowHTML("    <td align=\"right\"><font size=\"1\"><b>Registros: ".$RS->RecordCount);
ShowHTML("<tr><td align=\"center\" colspan=3>");
ShowHTML("    <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">");
ShowHTML("        <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">");
ShowHTML("          <td rowspan=2><font size=\"1\"><b>Nº</font></td>");
ShowHTML("          <td rowspan=2><font size=\"1\"><b>Responsável</font></td>");
ShowHTML("          <td rowspan=2><font size=\"1\"><b>Executor</font></td>");
if ($P1==1 || $P1==2)
{
// Se for cadastramento ou mesa de trabalho

  ShowHTML("          <td rowspan=2><font size=\"1\"><b>Título</font></td>");
  ShowHTML("          <td colspan=2><font size=\"1\"><b>Execução</font></td>");
}
  else
{

  ShowHTML("          <td rowspan=2><font size=\"1\"><b>Parcerias</font></td>");
  ShowHTML("          <td rowspan=2><font size=\"1\"><b>Título</font></td>");
  ShowHTML("          <td colspan=2><font size=\"1\"><b>Execução</font></td>");
  ShowHTML("          <td rowspan=2><font size=\"1\"><b>Valor</font></td>");
  ShowHTML("          <td rowspan=2><font size=\"1\"><b>Fase atual</font></td>");
} 

ShowHTML("          <td rowspan=2><font size=\"1\"><b>Operações</font></td>");
ShowHTML("        </tr>");
ShowHTML("        <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">");
ShowHTML("          <td><font size=\"1\"><b>De</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Até</font></td>");
ShowHTML("        </tr>");
if ($RS->EOF)
{

  ShowHTML("      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=10 align=\"center\"><font size=\"2\"><b>Não foram encontrados registros.</b></td></tr>");
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

  ShowHTML("        <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."Visual&R=".$w_pagina.$par."&O=L&w_chave=".$RS["sq_siw_solicitacao"]."&w_tipo=Volta&P1=2&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Exibe as informações deste registro.\">".$RS["sq_siw_solicitacao"]."&nbsp;</a>");

  ShowHTML("        <td><font size=\"1\">".ExibePessoa("../",$w_cliente,$RS["solicitante"],$TP,$RS["nm_solic"])."</A></td>");
  if (Nvl($RS["nm_exec"],"---")>"---")
  {

    ShowHTML("        <td><font size=\"1\">".ExibePessoa("../",$w_cliente,$RS["executor"],$TP,$RS["nm_exec"])."</td>");
  }
    else
  {

    ShowHTML("        <td><font size=\"1\">---</td>");
  } 

  if ($P1!=1 && $P1!=2)
  {
// Se não for cadastramento nem mesa de trabalho

    ShowHTML("        <td><font size=\"1\">".Nvl($RS["proponente"],"---")."</td>");
  } 

// Verifica se foi enviado o parâmetro p_tamanho = N. Se chegou, o assunto deve ser exibido sem corte.

// Este parâmetro é enviado pela tela de filtragem das páginas gerenciais

  if (${"p_tamanho"}=="N")
  {

    ShowHTML("        <td><font size=\"1\">".Nvl($RS["titulo"],"-")."</td>");
  }
    else
  {

    if (strlen(Nvl($RS["titulo"],"-"))>50)
    {
      $w_titulo=substr(Nvl($RS["titulo"],"-"),0,50)."...";
    }
      else
    {
      $w_titulo=Nvl($RS["titulo"],"-");
    }
;
  } 
  if ($RS["sg_tramite"]=="CA")
  {

    ShowHTML("        <td title=\"".str_replace("\r\n","\n",str_replace("\"","\'",str_replace("'","\'",$RS["titulo"])))."\"><font size=\"1\"><strike>".$w_titulo."</strike></td>");
  }
    else
  {

    ShowHTML("        <td title=\"".str_replace("\r\n","\n",str_replace("\"","\'",str_replace("'","\'",$RS["titulo"])))."\"><font size=\"1\">".$w_titulo."</td>");
  } 

} 

ShowHTML("        <td align=\"center\"><font size=\"1\">&nbsp;".FormataDataEdicao($RS["inicio"])."</td>");
ShowHTML("        <td align=\"center\"><font size=\"1\">&nbsp;".FormataDataEdicao($RS["fim"])."</td>");
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

      ShowHTML("          <A class=\"HL\" HREF=\"Menu.asp?par=ExibeDocs&O=A&w_chave=".$RS["sq_siw_solicitacao"]."&R=".$w_Pagina.$par."&SG=".$SG."&TP=".$TP."&w_documento=Nr. ".$RS["sq_siw_solicitacao"].MontaFiltro("GET")."\" title=\"Altera as informações cadastrais da ação\" TARGET=\"menu\">Alterar</a>&nbsp;");
    }
      else
    {

      ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina.$par."&R=".$w_Pagina.$par."&O=A&w_chave=".$RS["sq_siw_solicitacao"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Altera as informações cadastrais da ação\">Alterar</A>&nbsp");
    } 

    ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."Excluir&R=".$w_pagina.$par."&O=E&w_chave=".$RS["sq_siw_solicitacao"]."&w_tipo=Volta&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Exclusão da ação.\">Excluir</A>&nbsp");
  }
    else
  if ($P1==2)
  {
// Se for execução

    if ($cDbl[$w_usuario]==$cDbl[$RS["executor"]])
    {

      if ($cDbl[Nvl($RS["solicitante"],0)]==$cDbl[$w_usuario] || 
         $cDbl[Nvl($RS["titular"],0)]==$cDbl[$w_usuario] || 
         $cDbl[Nvl($RS["substituto"],0)]==$cDbl[$w_usuario] || 
         $cDbl[Nvl($RS["tit_exec"],0)]==$cDbl[$w_usuario] || 
         $cDbl[Nvl($RS["executor"],0)]==$cDbl[$w_usuario] || 
         $cDbl[Nvl($RS["subst_exec"],0)]==$cDbl[$w_usuario]
         )
      {

        ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."AtualizaEtapa&R=".$w_pagina.$par."&O=L&w_chave=".$RS["sq_siw_solicitacao"]."&w_tipo=Volta&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Atualiza as metas físicas da ação.\" target=\"Metas\">Metas</A>&nbsp");
      } 

// Coloca as operações dependendo do trâmite

      if ($RS["sg_tramite"]=="EA")
      {

        ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."Anotacao&R=".$w_pagina.$par."&O=V&w_chave=".$RS["sq_siw_solicitacao"]."&w_tipo=Volta&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Registra anotações para a ação, sem enviá-la.\">Anotar</A>&nbsp");
        ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."Envio&R=".$w_pagina.$par."&O=V&w_chave=".$RS["sq_siw_solicitacao"]."&w_tipo=Volta&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Envia a ação para outro responsável.\">Enviar</A>&nbsp");
      }
        else
      if ($RS["sg_tramite"]=="EE")
      {

        ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."Anotacao&R=".$w_pagina.$par."&O=V&w_chave=".$RS["sq_siw_solicitacao"]."&w_tipo=Volta&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Registra anotações para a ação, sem enviá-la.\">Anotar</A>&nbsp");
        ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."Envio&R=".$w_pagina.$par."&O=V&w_chave=".$RS["sq_siw_solicitacao"]."&w_tipo=Volta&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Envia a ação para outro responsável.\">Enviar</A>&nbsp");
        ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."Concluir&R=".$w_pagina.$par."&O=V&w_chave=".$RS["sq_siw_solicitacao"]."&w_tipo=Volta&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Conclui a execução da ação.\">Concluir</A>&nbsp");
      } 

    }
      else
    {

      ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."AtualizaEtapa&R=".$w_pagina.$par."&O=L&w_chave=".$RS["sq_siw_solicitacao"]."&w_tipo=Volta&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Atualiza as metas físicas da ação.\" target=\"Metas\">Metas</A>&nbsp");
      ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."Envio&R=".$w_pagina.$par."&O=V&w_chave=".$RS["sq_siw_solicitacao"]."&w_tipo=Volta&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Envia a ação para outro responsável.\">Enviar</A>&nbsp");
    } 

  } 

}
  else
{

  if ($cDbl[Nvl($RS["solicitante"],0)]==$cDbl[$w_usuario] || 
     $cDbl[Nvl($RS["titular"],0)]==$cDbl[$w_usuario] || 
     $cDbl[Nvl($RS["substituto"],0)]==$cDbl[$w_usuario] || 
     $cDbl[Nvl($RS["resp_etapa"],0)]>$cDbl[0] || 
     $cDbl[Nvl($RS["tit_exec"],0)]==$cDbl[$w_usuario] || 
     $cDbl[Nvl($RS["subst_exec"],0)]==$cDbl[$w_usuario]
     )
  {

// Se o usuário for responsável por uma ação, titular/substituto do setor responsável 

// ou titular/substituto da unidade executora,

// pode enviar.

    if ($cDbl[Nvl($RS["solicitante"],0)]==$cDbl[$w_usuario] || 
       $cDbl[Nvl($RS["titular"],0)]==$cDbl[$w_usuario] || 
       $cDbl[Nvl($RS["substituto"],0)]==$cDbl[$w_usuario] || 
       $cDbl[Nvl($RS["tit_exec"],0)]==$cDbl[$w_usuario] || 
       $cDbl[Nvl($RS["subst_exec"],0)]==$cDbl[$w_usuario]
       )
    {

      ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."envio&R=".$w_pagina.$par."&O=V&w_chave=".$RS["sq_siw_solicitacao"]."&w_tipo=Volta&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Envia a ação para outro responsável.\">Enviar</A>&nbsp");
    } 

  } 


  ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."AtualizaEtapa&R=".$w_pagina.$par."&O=L&w_chave=".$RS["sq_siw_solicitacao"]."&w_tipo=Volta&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."\" title=\"Atualiza as metas físicas da ação.\" target=\"Metas\">Metas</A>&nbsp");

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

if ($P1!=1)
{

ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td><div align=\"justify\"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>");
}
  else
if ($O=="C")
{
// Se for cópia

ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td><div align=\"justify\"><font size=2>Para selecionar a ação que deseja copiar, informe nos campos abaixo os critérios de seleção e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>");
} 

ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">");
ShowHTML("      <tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\" valign=\"top\"><table border=0 width=\"90%\" cellspacing=0>");
AbreForm("Form",$w_dir.$w_pagina.$par,"POST","return(Validacao(this));",null,$P1,$P2,$P3,null,$TP,$SG,$R,"L");
ShowHTML("<INPUT type=\"hidden\" name=\"w_troca\" value=\"\">");
if ($O=="C")
{
// Se for cópia, cria parâmetro para facilitar a recuperação dos registros

ShowHTML("<INPUT type=\"hidden\" name=\"w_copia\" value=\"OK\">");
} 

if ($P1!=1 || $O=="C")
{
// Se não for cadastramento ou se for cópia

// Recupera dados da opçãa açãos

ShowHTML("      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>");
ShowHTML("      <tr>");
DB_GetLinkData($RS,$w_cliente,"ORCAD");
SelecaoProjeto("Pro<u>j</u>eto:","J","Selecione a ação da atividade na relação.",$p_projeto,$w_usuario,$RS["sq_menu"],"p_projeto","ORLIST",null);
DesconectaBD();
ShowHTML("      </tr>");
if ($RS_menu["solicita_cc"]=="S")
{

ShowHTML("      <tr>");
SelecaoCC("C<u>l</u>assificação:","L","Selecione um dos itens relacionados.",$p_sqcc,null,"p_sqcc","SIWSOLIC");
ShowHTML("      </tr>");
} 

ShowHTML("          </table>");

ShowHTML("      <tr valign=\"top\">");
ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>Número da <U>d</U>emanda:<br><INPUT ACCESSKEY=\"D\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"p_chave\" size=\"18\" maxlength=\"18\" value=\"".$p_chave."\"></td>");
ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY=\"T\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"p_prazo\" size=\"2\" maxlength=\"2\" value=\"".$p_prazo."\"></td>");
ShowHTML("      <tr valign=\"top\">");
SelecaoPessoa("Respo<u>n</u>sável:","N","Selecione o responsável pela ação na relação.",$p_solicitante,null,"p_solicitante","USUARIOS");
SelecaoUnidade("<U>S</U>etor responsável:","S",null,$p_unidade,null,"p_unidade",null,null);
ShowHTML("      <tr valign=\"top\">");
SelecaoPessoa("Responsável atua<u>l</u>:","L","Selecione o responsável atual pela ação na relação.",$p_usu_resp,null,"p_usu_resp","USUARIOS");
SelecaoUnidade("<U>S</U>etor atual:","S","Selecione a unidade onde a ação se encontra na relação.",$p_uorg_resp,null,"p_uorg_resp",null,null);
ShowHTML("      <tr>");
SelecaoPais("<u>P</u>aís:","P",null,$p_pais,null,"p_pais",null,"onChange=\"document.Form.action='".$w_dir.$w_pagina.$par."'; document.Form.O.value='".$O."'; document.Form.w_troca.value='p_regiao'; document.Form.submit();\"");
SelecaoRegiao("<u>R</u>egião:","R",null,$p_regiao,$p_pais,"p_regiao",null,"onChange=\"document.Form.action='".$w_dir.$w_pagina.$par."'; document.Form.O.value='".$O."'; document.Form.w_troca.value='p_uf'; document.Form.submit();\"");
ShowHTML("      <tr>");
SelecaoEstado("E<u>s</u>tado:","S",null,$p_uf,$p_pais,"N","p_uf",null,"onChange=\"document.Form.action='".$w_dir.$w_pagina.$par."'; document.Form.O.value='".$O."'; document.Form.w_troca.value='p_cidade'; document.Form.submit();\"");
SelecaoCidade("<u>C</u>idade:","C",null,$p_cidade,$p_pais,$p_uf,"p_cidade",null,null);
ShowHTML("      <tr>");
SelecaoPrioridade("<u>P</u>rioridade:","P","Informe a prioridade desta ação.",$p_prioridade,null,"p_prioridade",null,null);
ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>Propo<U>n</U>ente externo:<br><INPUT ACCESSKEY=\"N\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"p_proponente\" size=\"25\" maxlength=\"90\" value=\"".$p_proponente."\"></td>");
ShowHTML("      <tr>");
ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>A<U>s</U>sunto:<br><INPUT ACCESSKEY=\"N\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"p_assunto\" size=\"25\" maxlength=\"90\" value=\"".$p_assunto."\"></td>");
ShowHTML("          <td valign=\"top\" colspan=2><font size=\"1\"><b>Pala<U>v</U>ras-chave:<br><INPUT ACCESSKEY=\"N\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"p_palavra\" size=\"25\" maxlength=\"90\" value=\"".$p_palavra."\"></td>");
ShowHTML("      <tr>");
ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>Data de re<u>c</u>ebimento entre:</b><br><input ".$w_Disabled." accesskey=\"C\" type=\"text\" name=\"p_ini_i\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$p_ini_i."\" onKeyDown=\"FormataData(this,event);\"> e <input ".$w_Disabled." accesskey=\"C\" type=\"text\" name=\"p_ini_f\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$p_ini_f."\" onKeyDown=\"FormataData(this,event);\"></td>");
ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>Limi<u>t</u>e para conclusão entre:</b><br><input ".$w_Disabled." accesskey=\"T\" type=\"text\" name=\"p_fim_i\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$p_fim_i."\" onKeyDown=\"FormataData(this,event);\"> e <input ".$w_Disabled." accesskey=\"T\" type=\"text\" name=\"p_fim_f\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$p_fim_f."\" onKeyDown=\"FormataData(this,event);\"></td>");
if ($O!="C")
{
// Se não for cópia

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
} 

} 

ShowHTML("      <tr>");
ShowHTML("          <td valign=\"top\"><font size=\"1\"><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=\"O\" ".$w_Disabled." class=\"STS\" name=\"p_ordena\" size=\"1\">");
if ($p_Ordena=="ASSUNTO")
{

ShowHTML("          <option value=\"assunto\" SELECTED>Assunto<option value=\"inicio\">Data de recebimento<option value=\"\">Data limite para conclusão<option value=\"nm_tramite\">Fase atual<option value=\"prioridade\">Prioridade<option value=\"proponente\">Proponente externo");
}
  else
if ($p_Ordena=="INICIO")
{

ShowHTML("          <option value=\"assunto\">Assunto<option value=\"inicio\" SELECTED>Data de recebimento<option value=\"\">Data limite para conclusão<option value=\"nm_tramite\">Fase atual<option value=\"prioridade\">Prioridade<option value=\"proponente\">Proponente externo");
}
  else
if ($p_Ordena=="NM_TRAMITE")
{

ShowHTML("          <option value=\"assunto\">Assunto<option value=\"inicio\">Data de recebimento<option value=\"\">Data limite para conclusão<option value=\"nm_tramite\" SELECTED>Fase atual<option value=\"prioridade\">Prioridade<option value=\"proponente\">Proponente externo");
}
  else
if ($p_Ordena=="PRIORIDADE")
{

ShowHTML("          <option value=\"assunto\">Assunto<option value=\"inicio\">Data de recebimento<option value=\"\">Data limite para conclusão<option value=\"nm_tramite\">Fase atual<option value=\"prioridade\" SELECTED>Prioridade<option value=\"proponente\">Proponente externo");
}
  else
if ($p_Ordena=="PROPONENTE")
{

ShowHTML("          <option value=\"assunto\">Assunto<option value=\"inicio\">Data de recebimento<option value=\"\">Data limite para conclusão<option value=\"nm_tramite\">Fase atual<option value=\"prioridade\">Prioridade<option value=\"proponente\" SELECTED>Proponente externo");
}
  else
{

ShowHTML("          <option value=\"assunto\">Assunto<option value=\"inicio\">Data de recebimento<option value=\"\" SELECTED>Data limite para conclusão<option value=\"nm_tramite\">Fase atual<option value=\"prioridade\">Prioridade<option value=\"proponente\">Proponente externo");
} 

ShowHTML("          </select></td>");
ShowHTML("          <td valign=\"top\"><font size=\"1\"><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=\"L\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"P4\" size=\"4\" maxlength=\"4\" value=\"".$P4."\"></td></tr>");
ShowHTML("      <tr><td align=\"center\" colspan=\"2\" height=\"1\" bgcolor=\"#000000\">");
ShowHTML("      <tr><td align=\"center\" colspan=\"2\">");
ShowHTML("            <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Aplicar filtro\">");
if ($O=="C")
{
// Se for cópia

ShowHTML("            <input class=\"STB\" type=\"button\" onClick=\"location.href='".$w_Pagina.$par."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."';\" name=\"Botao\" value=\"Abandonar cópia\">");
}
  else
{

ShowHTML("            <input class=\"STB\" type=\"button\" onClick=\"location.href='".$w_Pagina.$par."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."';\" name=\"Botao\" value=\"Remover filtro\">");
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
$w_titulo=${"w_titulo"};
$w_prioridade=${"w_prioridade"};
$w_aviso=${"w_aviso"};
$w_dias=${"w_dias"};
$w_inicio_real=${"w_inicio_real"};
$w_fim_real=${"w_fim_real"};
$w_concluida=${"w_concluida"};
$w_data_conclusao=${"w_data_conclusao"};
$w_nota_conclusao=${"w_nota_conclusao"};
$w_custo_real=${"w_custo_real"};

$w_chave=${"w_chave"};
$w_chave_pai=${"w_chave_pai"};
$w_chave_aux=${"w_chave_aux"};
$w_sq_menu=${"w_sq_menu"};
$w_sq_unidade=${"w_sq_unidade"};
$w_sq_tramite=${"w_sq_tramite"};
$w_solicitante=${"w_solicitante"};
$w_cadastrador=${"w_cadastrador"};
$w_executor=${"w_executor"};
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
$w_sq_acao_ppa=${"w_sq_acao_ppa"};
$w_sq_orprioridade=${"w_sq_orprioridade"};
$w_descricao=${"w_descricao"};
$w_justificativa=${"w_justificativa"};
if ($w_sq_acao_ppa>"")
{

DB_GetAcaoPPA($RS,$w_sq_acao_ppa,$w_cliente,null,null,null,null,null,null,null,null);
$w_selecionada_mpog=$RS["selecionada_mpog"];
$w_selecionada_relevante=$RS["selecionada_relevante"];
$w_titulo=$RS["nome"];
}
  else
if ($w_sq_orprioridade>"")
{

DB_GetOrPrioridade($RS,null,$w_cliente,$w_sq_orprioridade,null,null,null);
$w_titulo=$RS["nome"];
} 

}
  else
{

if ((strpos("AEV",$O) ? strpos("AEV",$O)+1 : 0)>0 || $w_copia>"")
{

// Recupera os dados da ação

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
$w_titulo=$RS["titulo"];
$w_prioridade=$RS["prioridade"];
$w_aviso=$RS["aviso_prox_conc"];
$w_dias=$RS["dias_aviso"];
$w_inicio_real=$RS["inicio_real"];
$w_fim_real=$RS["fim_real"];
$w_concluida=$RS["concluida"];
$w_data_conclusao=$RS["data_conclusao"];
$w_nota_conclusao=$RS["nota_conclusao"];
$w_custo_real=$RS["custo_real"];

$w_chave_pai=$RS["sq_solic_pai"];
$w_chave_aux=null;
$w_sq_menu=$RS["sq_menu"];
$w_sq_unidade=$RS["sq_unidade"];
$w_sq_tramite=$RS["sq_siw_tramite"];
$w_solicitante=$RS["solicitante"];
$w_cadastrador=$RS["cadastrador"];
$w_executor=$RS["executor"];
$w_inicio=FormataDataEdicao($RS["inicio"]);
$w_fim=FormataDataEdicao($RS["fim"]);
$w_inclusao=$RS["inclusao"];
$w_ultima_alteracao=$RS["ultima_alteracao"];
$w_conclusao=$RS["conclusao"];
$w_valor=$FormatNumber[$RS["valor"]][2];
$w_opiniao=$RS["opiniao"];
$w_data_hora=$RS["data_hora"];
$w_sqcc=$RS["sq_cc"];
$w_sq_acao_ppa=$RS["sq_acao_ppa"];
$w_sq_orprioridade=$RS["sq_orprioridade"];
$w_selecionada_mpog=$RS["mpog_ppa"];
$w_selecionada_relevante=$RS["relev_ppa"];
$w_pais=$RS["sq_pais"];
$w_uf=$RS["co_uf"];
$w_cidade=$RS["sq_cidade_origem"];
$w_palavra_chave=$RS["palavra_chave"];
$w_descricao=$RS["descricao"];
$w_justificativa=$RS["justificativa"];
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
//Validate "w_titulo", "Ação", "1", 1, 5, 100, "1", "1"

if ($RS_menu["solicita_cc"]=="S")
{

Validate("w_sqcc","Classificação","SELECT",1,1,18,"","0123456789");
} 

//Validate "w_sq_orprioridade", "Iniciativa prioritária", "SELECT", "", 1, 18, "", "0123456789"

//Validate "w_sq_acao_ppa", "Ação PPA", "SELECT", "", 1, 18, "", "0123456789"

ShowHTML("  if (theForm.w_sq_acao_ppa.selectedIndex==0 && theForm.w_sq_orprioridade.selectedIndex==0) {");
ShowHTML("     alert('Informe a iniciativa prioritária e/ou a ação do PPA!');");
ShowHTML("     theForm.w_sq_orprioridade.focus();");
ShowHTML("     return false;");
ShowHTML("  }");
Validate("w_solicitante","Responsável monitoramento","HIDDEN",1,1,18,"","0123456789");
Validate("w_sq_unidade_resp","Setor responsável","HIDDEN",1,1,18,"","0123456789");
switch ($RS_menu["data_hora"])
{
case 1:
Validate("w_fim","Fim previsto","DATA",1,10,10,"","0123456789/");
break;
case 2:
Validate("w_fim","Fim previsto","DATAHORA",1,17,17,"","0123456789/");
break;
case 3:
Validate("w_inicio","Início previsto","DATA",1,10,10,"","0123456789/");
Validate("w_fim","Fim previsto","DATA",1,10,10,"","0123456789/");
CompData("w_inicio","Data de recebimento","<=","w_fim","Limite para conclusão");
break;
case 4:
Validate("w_inicio","Início previsto","DATAHORA",1,17,17,"","0123456789/,: ");
Validate("w_fim","Fim previsto","DATAHORA",1,17,17,"","0123456789/,: ");
CompData("w_inicio","Início previsto","<=","w_fim","Limite para conclusão");
break;
} 
Validate("w_valor","Recurso programado","VALOR","1",4,18,"","0123456789.,");
Validate("w_proponente","Parcerias externas","","",2,90,"1","1");
Validate("w_palavra_chave","Parcerias internas","","",2,90,"1","1");
//Validate "w_pais", "País", "SELECT", 1, 1, 18, "", "0123456789"

//Validate "w_uf", "Estado", "SELECT", 1, 1, 3, "1", "1"

//Validate "w_cidade", "Cidade", "SELECT", 1, 1, 18, "", "0123456789"

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

BodyOpen("onLoad='document.Form.".$w_troca.".focus()';");
}
  else
if ((strpos("EV",$O) ? strpos("EV",$O)+1 : 0)>0)
{

BodyOpen("onLoad='document.focus()';");
}
  else
{

BodyOpen("onLoad='document.Form.w_titulo.focus()';");
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
ShowHTML("<INPUT type=\"hidden\" name=\"w_prioridade\" value=\"\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_aviso\" value=\"S\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_descricao\" value=\"".$w_descricao."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_justificativa\" value=\"".$w_justificativa."\">");
//Passagem da cidade padrão como brasília, pelo retidara do impacto geográfico da tela

DB_GetCustomerData($RS,$w_cliente);
ShowHTML("<INPUT type=\"hidden\" name=\"w_cidade\" value=\"".$RS["sq_cidade_padrao"]."\">");
DesconectaBD();

ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">");
ShowHTML("    <table width=\"97%\" border=\"0\">");
ShowHTML("      <tr><td align=\"center\" height=\"2\" bgcolor=\"#000000\"></td></tr>");
ShowHTML("      <tr><td align=\"center\" height=\"1\" bgcolor=\"#000000\"></td></tr>");
ShowHTML("      <tr><td valign=\"top\" align=\"center\" bgcolor=\"#D0D0D0\"><font size=\"2\"><b>Identificação</td></td></tr>");
ShowHTML("      <tr><td align=\"center\" height=\"1\" bgcolor=\"#000000\"></td></tr>");
ShowHTML("      <tr><td><font size=1>Os dados deste bloco serão utilizados para identificação da ação, bem como para o controle de sua execução.</font></td></tr>");
ShowHTML("      <tr><td align=\"center\" height=\"1\" bgcolor=\"#000000\"></td></tr>");
if ($w_sq_acao_ppa>"")
{

ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b><u>A</u>ção:</b><br><INPUT READONLY ACCESSKEY=\"A\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"w_titulo\" size=\"90\" maxlength=\"100\" value=\"".$w_titulo."\" ></td>");
}
  else
{

ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b><u>A</u>ção:</b><br><INPUT ACCESSKEY=\"A\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"w_titulo\" size=\"90\" maxlength=\"100\" value=\"".$w_titulo."\" ></td>");
} 

if ($RS_menu["solicita_cc"]=="S")
{

ShowHTML("          <tr>");
SelecaoCC("C<u>l</u>assificação:","L","Selecione um dos itens relacionados.",$w_sqcc,null,"w_sqcc","SIWSOLIC");
ShowHTML("          </tr>");
} 

ShowHTML("          <tr>");
SelecaoOrPrioridade("<u>I</u>niciativa prioritária:","I",null,$w_sq_orprioridade,null,"w_sq_orprioridade","VINCULACAO","onchange=\"document.Form.action='".$w_dir.$w_pagina.$par."'; document.Form.w_troca.value='w_sq_acao_ppa'; document.Form.submit();\"");
ShowHTML("          </tr>");
ShowHTML("          <tr>");
if ($O=="I" || $w_sq_acao_ppa=="")
{

SelecaoAcaoPPA("Ação <u>P</u>PA:","P",null,$w_sq_acao_ppa,null,"w_sq_acao_ppa","IDENTIFICACAO","onchange=\"document.Form.action='".$w_dir.$w_pagina.$par."'; document.Form.w_troca.value='w_solicitante'; document.Form.submit();\"");
}
  else
{

SelecaoAcaoPPA("Ação <u>P</u>PA:","P",null,$w_sq_acao_ppa,null,"w_sq_acao_ppa",null,"disabled");
ShowHTML("<INPUT type=\"hidden\" name=\"w_sq_acao_ppa\" value=\"".$w_sq_acao_ppa."\">");
} 

ShowHTML("          </tr>");
ShowHTML("      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>");
MontaRadioNS("<b>Selecionada pelo MP?</b>",$w_selecionada_mpog,"w_selecionada_mpog");
MontaRadioNS("<b>SE/MS?</b>",$w_selecionada_relevante,"w_selecionada_relevante");
ShowHTML("          </table>");
ShowHTML("      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>");
SelecaoPessoa("Respo<u>n</u>sável monitoramento:","N","Selecione o responsável pelo monitoramento da ação na relação.",$w_solicitante,null,"w_solicitante","USUARIOS");
SelecaoUnidade("<U>S</U>etor responsável monitoramento:","S","Selecione o setor responsável pelo monitoramento da execução da ação",$w_sq_unidade_resp,null,"w_sq_unidade_resp",null,null);
//SelecaoPrioridade "<u>P</u>rioridade:", "P", "Informe a prioridade desta ação.", w_prioridade, null, "w_prioridade", null, null

ShowHTML("          </table>");
ShowHTML("      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>");
ShowHTML("          <tr>");
switch ($RS_menu["data_hora"])
{
case 1:
ShowHTML("              <td valign=\"top\"><font size=\"1\"><b><u>F</u>im previsto:</b><br><input ".$w_Disabled." accesskey=\"F\" type=\"text\" name=\"w_fim\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$w_fim."\" onKeyDown=\"FormataData(this,event);\"></td>");
break;
case 2:
ShowHTML("              <td valign=\"top\"><font size=\"1\"><b><u>F</u>im previsto:</b><br><input ".$w_Disabled." accesskey=\"F\" type=\"text\" name=\"w_fim\" class=\"STI\" SIZE=\"17\" MAXLENGTH=\"17\" VALUE=\"".$w_fim."\" onKeyDown=\"FormataDataHora(this,event);\"></td>");
break;
case 3:
ShowHTML("              <td valign=\"top\"><font size=\"1\"><b>Iní<u>c</u>io previsto:</b><br><input ".$w_Disabled." accesskey=\"C\" type=\"text\" name=\"w_inicio\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".Nvl($w_inicio,FormataDataEdicao(time()()))."\" onKeyDown=\"FormataData(this,event);\"></td>");
ShowHTML("              <td valign=\"top\"><font size=\"1\"><b><u>F</u>im previsto:</b><br><input ".$w_Disabled." accesskey=\"F\" type=\"text\" name=\"w_fim\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$w_fim."\" onKeyDown=\"FormataData(this,event);\"></td>");
break;
case 4:
ShowHTML("              <td valign=\"top\"><font size=\"1\"><b>Iní<u>c</u>io previsto:</b><br><input ".$w_Disabled." accesskey=\"C\" type=\"text\" name=\"w_inicio\" class=\"STI\" SIZE=\"17\" MAXLENGTH=\"17\" VALUE=\"".$w_inicio."\" onKeyDown=\"FormataDataHora(this,event);\"></td>");
ShowHTML("              <td valign=\"top\"><font size=\"1\"><b><u>F</u>im previsto:</b><br><input ".$w_Disabled." accesskey=\"F\" type=\"text\" name=\"w_fim\" class=\"STI\" SIZE=\"17\" MAXLENGTH=\"17\" VALUE=\"".$w_fim."\" onKeyDown=\"FormataDataHora(this,event);\"></td>");
break;
} 
ShowHTML("              <td valign=\"top\"><font size=\"1\"><b><u>R</u>ecurso programado:</b><br><input ".$w_Disabled." accesskey=\"O\" type=\"text\" name=\"w_valor\" class=\"STI\" SIZE=\"18\" MAXLENGTH=\"18\" VALUE=\"".$w_valor."\" onKeyDown=\"FormataValor(this,18,2,event);\"></td>");
ShowHTML("          </table>");
ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>Parc<u>e</u>rias externas:<br><INPUT ACCESSKEY=\"E\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"w_proponente\" size=\"90\" maxlength=\"90\" value=\"".$w_proponente."\" title=\"Parceria externa da ação. Preencha apenas se houver.\"></td>");
ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b><u>P</u>arcerias internas:<br><INPUT ACCESSKEY=\"P\" ".$w_Disabled." class=\"STI\" type=\"text\" name=\"w_palavra_chave\" size=\"90\" maxlength=\"90\" value=\"".$w_palavra_chave."\" title=\"Se desejar, informe palavras-chave adicionais aos campos informados e que permitam a identificação desta ação.\"></td>");
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

//ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"

//ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"

//ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Alerta de atraso</td></td></tr>"

//ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"

//ShowHTML "      <tr><td><font size=1>Os dados abaixo indicam como deve ser tratada a proximidade da data limite para conclusão da ação.</font></td></tr>"

//ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"

//ShowHTML "      <tr><td><table border=""0"" width=""100""">"

//ShowHTML "          <tr>"

//MontaRadioNS "<b>Emite alerta?</b>", w_aviso, "w_aviso"

//ShowHTML "              <td valign=""top""><font size=""1""><b>Quantos <U>d</U>ias antes da data limite?<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_dias"" size=""2"" maxlength=""2"" value=""" & w_dias & """ title=""Número de dias para emissão do alerta de proximidade da data limite para conclusão da ação.""></td>"

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

$w_selecionada_mpog=null;

$w_selecionada_relevante=null;

$w_sq_acao_ppa=null;

$w_sq_orprioridade=null;

$w_proponente=null;

$w_sq_unidade_resp=null;

$w_titulo=null;

$w_prioridade=null;

$w_aviso=null;

$w_dias=null;

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

// =========================================================================

// Rotina das informações adicionais

// -------------------------------------------------------------------------

function InfoAdic()
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


$w_chave=${"w_chave"};
$w_chave_pai=${"w_chave_pai"};
$w_chave_aux=${"w_chave_aux"};
$w_sq_menu=${"w_sq_menu"};
$w_sq_unidade=${"w_sq_unidade"};
$w_descricao=${"w_descricao"};
$w_justificativa=${"w_justificativa"};
$w_ds_acao=${"w_ds_acao"};
$w_problema=${"w_problema"};
$w_publico_alvo=${"w_publico_alvo"};
$w_estrategia=${"w_estrategia"};
$w_indicadores=${"w_indicadores"};
$w_objetivo=${"w_objetivo"};
}
  else
{

if ((strpos("AEV",$O) ? strpos("AEV",$O)+1 : 0)>0 || $w_copia>"")
{

// Recupera os dados da ação

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

$w_chave_pai=$RS["sq_solic_pai"];
$w_chave_aux=null;
$w_sq_menu=$RS["sq_menu"];
$w_sq_unidade=$RS["sq_unidade"];
$w_descricao=$RS["descricao"];
$w_justificativa=$RS["justificativa"];
$w_ds_acao=$RS["ds_acao"];
$w_problema=$RS["problema"];
$w_publico_alvo=$RS["publico_alvo"];
$w_estrategia=$RS["estrategia"];
$w_indicadores=$RS["indicadores"];
$w_objetivo=$RS["objetivo"];
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
Validate("w_problema","Situação problema","1","",5,2000,"1","1");
Validate("w_objetivo","Objetivo da ação","1","",5,2000,"1","1");
Validate("w_ds_acao","Descrição da ação","1","",5,2000,"1","1");
Validate("w_publico_alvo","Publico alvo","1","",5,2000,"1","1");
if ($RS_menu["descricao"]=="S")
{

Validate("w_descricao","Resultados da ação","1","",5,2000,"1","1");
} 

Validate("w_estrategia","Estratégia de implantação","1","",5,2000,"1","1");
Validate("w_indicadores","Indicadores de desempenho","1","",5,2000,"1","1");
if ($RS_menu["justificativa"]=="S")
{

Validate("w_justificativa","Observações","1","",5,2000,"1","1");
} 

} 

ValidateClose();
ScriptClose();
ShowHTML("</HEAD>");
ShowHTML("<BASE HREF=\"".$conRootSIW."\">");
if ($w_troca>"")
{

BodyOpen("onLoad='document.Form.".$w_troca.".focus()';");
}
  else
if ((strpos("EV",$O) ? strpos("EV",$O)+1 : 0)>0)
{

BodyOpen("onLoad='document.focus()';");
}
  else
{

BodyOpen("onLoad='document.Form.w_descricao.focus()';");
} 

ShowHTML("<B><FONT COLOR=\"#000000\">".$w_TP."</FONT></B>");
ShowHTML("<HR>");
ShowHTML("<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">");
if ((strpos("IAEV",$O) ? strpos("IAEV",$O)+1 : 0)>0)
{

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

ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">");
ShowHTML("    <table width=\"97%\" border=\"0\">");
ShowHTML("      <tr><td align=\"center\" height=\"2\" bgcolor=\"#000000\"></td></tr>");
ShowHTML("      <tr><td align=\"center\" height=\"1\" bgcolor=\"#000000\"></td></tr>");
ShowHTML("      <tr><td valign=\"top\" align=\"center\" bgcolor=\"#D0D0D0\"><font size=\"2\"><b>Informações adicionais</td></td></tr>");
ShowHTML("      <tr><td align=\"center\" height=\"1\" bgcolor=\"#000000\"></td></tr>");
ShowHTML("      <tr><td><font size=1>Os dados deste bloco visam orientar os executores da ação.</font></td></tr>");
ShowHTML("      <tr><td align=\"center\" height=\"1\" bgcolor=\"#000000\"></td></tr>");
ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>Situação <u>p</u>roblema:</b><br><textarea ".$w_Disabled." accesskey=\"P\" name=\"w_problema\" class=\"STI\" ROWS=5 cols=75 title=\"Destacar os elementos essenciais que explicam a situação-problema (determinantes/causas).\">".$w_problema."</TEXTAREA></td>");
ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b><u>O</u>bjetivo da ação:</b><br><textarea ".$w_Disabled." accesskey=\"O\" name=\"w_objetivo\" class=\"STI\" ROWS=5 cols=75 title=\"Descreva o objetivo a ser alcançado com a execução desta ação.\">".$w_objetivo."</TEXTAREA></td>");
ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b><u>D</u>escrição da ação:</b><br><textarea ".$w_Disabled." accesskey=\"D\" name=\"w_ds_acao\" class=\"STI\" ROWS=5 cols=75 title=\"Destacar os elementos essenciais que compõem e explicam a ação (tarefas).\">".$w_ds_acao."</TEXTAREA></td>");
ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>Pú<u>b</u>lico alvo :</b><br><textarea ".$w_Disabled." accesskey=\"B\" name=\"w_publico_alvo\" class=\"STI\" ROWS=5 cols=75 title=\"Especifique os segmentos da sociedade aos quais o programa se destina e que se beneficiam direta e legitimamente com sua execução. Exemplos: crianças desnutridas de 6 a 23 meses de idade; gestantes de risco nutricional; grupos vulnerávei e os obesos.\">".$w_publico_alvo."</TEXTAREA></td>");
if ($RS_menu["descricao"]=="S")
{

ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>Res<u>u</u>ltados da ação:</b><br><textarea ".$w_Disabled." accesskey=\"U\" name=\"w_descricao\" class=\"STI\" ROWS=5 cols=75 title=\"Indicar os principais resultados qeu se pretende alcançar nos sistemas de gestão e na saúde da população em consequência da execução da ação.\">".$w_descricao."</TEXTAREA></td>");
} 

ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b><u>E</u>estrategia de implantação:</b><br><textarea ".$w_Disabled." accesskey=\"E\" name=\"w_estrategia\" class=\"STI\" ROWS=5 cols=75 title=\"Indicar os meios a empregar ou métodos a seguir com a finalidade de implementar a ação. Relacionar mecanismos e instrumentos disponíveis ou a serem constituídos e a forma de execução. Relacionar as parcerias e responsabilidades e os mecanismos utilizados no monitoramento.\">".$w_estrategia."</TEXTAREA></td>");
ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b><u>I</u>ndicadores de desempenho:</b><br><textarea ".$w_Disabled." accesskey=\"I\" name=\"w_indicadores\" class=\"STI\" ROWS=5 cols=75 title=\"Indicar os parâmetros que medem a diferença entre a situação atual e a situação desejada. É geralmente apresentado como uma relação ou taxa entre variáveis relevantes para quantificar o processo ou os resultados alcançados com a execução da ação. Mede o trabalho realizado.\">".$w_indicadores."</TEXTAREA></td>");
if ($RS_menu["justificativa"]=="S")
{

ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>Obse<u>r</u>vações:</b><br><textarea ".$w_Disabled." accesskey=\"R\" name=\"w_justificativa\" class=\"STI\" ROWS=5 cols=75 title=\"Informar fatos ou situações que sejam relevantes para uma melhor compreensão da ação e/ou descrever situações que não tenham sido descritas em outros campos do formulário e que devam ser consideradas para a viabilidade da mesma. Indicar as fragilidades já identificadas.\">".$w_justificativa."</TEXTAREA></td>");
} 


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

$w_chave=null;

$w_chave_pai=null;

$w_chave_aux=null;

$w_sq_menu=null;

$w_descricao=null;

$w_justificativa=null;

$w_ds_acao=null;

$w_problema=null;

$w_publico_alvo=null;

$w_estrategia=null;

$w_indicadores=null;

$w_objetivo=null;


$w_troca=null;

$i=null;

$w_erro=null;

$w_como_funciona=null;

$w_cor=null;


return $function_ret;
} 

// =========================================================================

// Rotina das outras iniciativas

// -------------------------------------------------------------------------

function Iniciativas()
{
  extract($GLOBALS);






$w_chave=${"w_chave"};
$w_readonly="";
$w_erro="";
$w_troca=${"w_troca"};


DB_GetSolicData($RS,$w_chave,$SG);
if ($RS->RecordCount>0)
{

$w_chave_pai=$RS["sq_solic_pai"];
$w_chave_aux=null;
$w_sq_menu=$RS["sq_menu"];
$w_sq_unidade=$RS["sq_unidade"];
$w_nm_ppa_pai=$RS["nm_ppa_pai"];
$w_cd_ppa_pai=$RS["cd_ppa_pai"];
$w_nm_ppa=$RS["nm_ppa"];
$w_cd_ppa=$RS["cd_ppa"];
$w_nm_pri=$RS["nm_pri"];
$w_cd_pri=$RS["cd_pri"];
$w_sq_orprioridade=$RS["sq_orprioridade"];
DesconectaBD();
}
  else
{

ScriptOpen("JavaScript");
ShowHTML(" alert('Opção não disponível');");
ShowHTML(" history.back(1);");
ScriptClose();
} 

if ($cDbl[Nvl($w_sq_orprioridade,0)]==0)
{

ScriptOpen("JavaScript");
ShowHTML(" alert('Para inserir outras iniciativas, cadastre a iniciativa prioritária primeiro!');");
ShowHTML(" history.back(1);");
ScriptClose();
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
ShowHTML("  theForm.Botao.disabled=true;");
ValidateClose();
ScriptClose();
ShowHTML("</HEAD>");
ShowHTML("<BASE HREF=\"".$conRootSIW."\">");

BodyOpen("onLoad='document.Form.focus()';");
ShowHTML("<B><FONT COLOR=\"#000000\">".$w_TP."</FONT></B>");
ShowHTML("<HR>");
ShowHTML("<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">");

AbreForm("Form",$w_dir.$w_pagina."Grava","POST","return(Validacao(this));",null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
ShowHTML(MontaFiltro("POST"));
ShowHTML("<INPUT type=\"hidden\" name=\"w_troca\" value=\"\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_chave\" value=\"".$w_chave."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_data_hora\" value=\"".$RS_menu["data_hora"]."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_menu\" value=\"".$RS_menu["sq_menu"]."\">");

ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">");
ShowHTML("    <table width=\"97%\" border=\"0\">");
ShowHTML("      <tr><td align=\"center\" height=\"2\" bgcolor=\"#000000\"></td></tr>");
ShowHTML("      <tr><td align=\"center\" height=\"1\" bgcolor=\"#000000\"></td></tr>");
ShowHTML("      <tr><td valign=\"top\" align=\"center\" bgcolor=\"#D0D0D0\"><font size=\"2\"><b>Outras iniciativas</td></td></tr>");
ShowHTML("      <tr><td align=\"center\" height=\"1\" bgcolor=\"#000000\"></td></tr>");
ShowHTML("      <tr><td><font size=1>Os dados deste bloco visa informar as outras iniciativas da ação.</font></td></tr>");
ShowHTML("      <tr><td align=\"center\" height=\"1\" bgcolor=\"#000000\"></td></tr>");
if ($w_cd_ppa>"")
{

ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>Programa PPA: </b><br>".$w_cd_ppa_pai." - ".$w_nm_ppa_pai." </b>");
ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>Ação PPA: </b><br>".$w_cd_ppa." - ".$w_nm_ppa." </b>");
} 

if ($w_sq_orprioridade>"")
{

ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>Iniciativa prioritária: </b><br>".$w_nm_pri." </b>");
} 


DB_GetOrPrioridadeList($RS,$w_chave,$w_cliente,$w_sq_orprioridade);
ShowHTML("      <tr><td valign=\"top\"><br>");
ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>Selecione outras iniciativas prioritárias as quais a ação está relacionada:</b>");
while(!$RS->EOF)
{

if ($cDbl[Nvl($RS["Existe"],0)]>0)
{

ShowHTML("      <tr><td valign=\"top\"><font size=\"1\">&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"w_outras_iniciativas\" value=\"".$RS["chave"]."\" checked>".$RS["nome"]."</td>");
}
  else
{

ShowHTML("      <tr><td valign=\"top\"><font size=\"1\">&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"w_outras_iniciativas\" value=\"".$RS["chave"]."\">".$RS["nome"]."</td>");
} 

$RS->MoveNext;
} 
ShowHTML("      <tr><td align=\"center\" colspan=\"3\">");
ShowHTML("            <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Gravar\">");
ShowHTML("          </td>");
ShowHTML("      </tr>");
ShowHTML("    </table>");
ShowHTML("    </TD>");
ShowHTML("</tr>");
ShowHTML("</FORM>");
ShowHTML("</table>");
ShowHTML("</center>");
Rodape();

$w_chave=null;

$w_chave_pai=null;

$w_chave_aux=null;

$w_sq_menu=null;

$w_outras_iniciativas=null;


$w_troca=null;

$i=null;

$w_erro=null;

$w_como_funciona=null;

$w_cor=null;


return $function_ret;
} 

// =========================================================================

// Rotina de financiamento

// -------------------------------------------------------------------------

function Financiamento()
{
  extract($GLOBALS);




$w_Chave=${"w_Chave"};
$w_chave_aux=${"w_chave_aux"};
$w_troca=${"w_troca"};

if ($w_troca>"")
{
// Se for recarga da página

$w_sq_acao_ppa=${"w_sq_acao_ppa"};
$w_obs_financ=${"w_obs_financ"};
}
  else
if ($O=="L")
{

// Recupera todos os registros para a listagem

DB_GetFinancAcaoPPA($RS,$w_chave,$w_cliente,null);
}
  else
if ((strpos("AEV",$O) ? strpos("AEV",$O)+1 : 0)>0 && $w_Troca=="")
{

// Recupera os dados do financiamento

DB_GetFinancAcaoPPA($RS,$w_chave,$w_cliente,${"w_sq_acao_ppa"});
$w_sq_acao_ppa=$RS["sq_acao_ppa"];
$w_obs_financ=$RS["observacao"];
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

Validate("w_sq_acao_ppa","Ação PPA","SELECT","1","1","10","","1");
Validate("w_obs_financ","Observações","1","",5,2000,"1","1");
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

BodyOpen("onLoad='document.Form.w_sq_acao_ppa.focus()';");
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

ShowHTML("<tr><td><font size=\"2\"><a accesskey=\"I\" class=\"SS\" href=\"".$w_dir.$w_pagina.$par."&R=".$w_Pagina.$par."&O=I&w_chave=".$w_chave."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\"><u>I</u>ncluir</a>&nbsp;");
ShowHTML("    <td align=\"right\"><font size=\"1\"><b>Registros existentes: ".$RS->RecordCount);
ShowHTML("<tr><td align=\"center\" colspan=3>");
ShowHTML("    <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">");
ShowHTML("        <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">");
ShowHTML("          <td><font size=\"1\"><b>Código</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Nome</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Operações</font></td>");
ShowHTML("        </tr>");
if ($RS->EOF)
{
// Se não foram selecionados registros, exibe mensagem

ShowHTML("      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=7 align=\"center\"><font size=\"2\"><b>Não foram encontrados registros.</b></td></tr>");
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
ShowHTML("        <td><font size=\"1\">".$RS["cd_ppa_pai"].".".$RS["cd_ppa"]."</td>");
ShowHTML("        <td><font size=\"1\">".$RS["nome"]."</td>");
ShowHTML("        <td align=\"top\" nowrap><font size=\"1\">");
ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina.$par."&R=".$w_pagina.$par."&O=A&w_chave=".$w_chave."&w_sq_acao_ppa=".$RS["sq_acao_ppa"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\">Alterar</A>&nbsp");
ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina.$par."&R=".$w_pagina.$par."&O=E&w_chave=".$w_chave."&w_sq_acao_ppa=".$RS["sq_acao_ppa"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\">Excluir</A>&nbsp");
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
ShowHTML("<INPUT type=\"hidden\" name=\"w_troca\" value=\"\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_chave\" value=\"".$w_chave."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_data_hora\" value=\"".$RS_menu["data_hora"]."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_menu\" value=\"".$RS_menu["sq_menu"]."\">");
$DB_GetSolicData$RS$w_chave$SG;
ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">");
ShowHTML("    <table width=\"97%\" border=\"0\">");
if ($RS["sq_acao_ppa"]>"")
{

ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>Programa PPA: </b><br>".$RS["cd_ppa_pai"]." - ".$RS["nm_ppa_pai"]." </b>");
ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>Ação PPA: </b><br>".$RS["cd_ppa"]." - ".$RS["nm_ppa"]." </b>");
} 

if ($RS["sq_orprioridade"]>"")
{

ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>Iniciativa prioritária: </b><br>".$RS["nm_pri"]." </b>");
} 

DesconectaBD();
ShowHTML("      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0><tr valign=\"top\">");
if ($O=="I")
{

SelecaoAcaoPPA("Ação <u>P</u>PA:","P",null,$w_sq_acao_ppa,$w_chave,"w_sq_acao_ppa","FINANCIAMENTO",null);
}
  else
{

ShowHTML("<INPUT type=\"hidden\" name=\"w_sq_acao_ppa\" value=\"".$w_sq_acao_ppa."\">");
SelecaoAcaoPPA("Ação <u>P</u>PA:","P",null,$w_sq_acao_ppa,$w_chave,"w_sq_acao_ppa",null,"disabled");
} 

ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>Obse<u>r</u>vações:</b><br><textarea ".$w_Disabled." accesskey=\"R\" name=\"w_obs_financ\" class=\"STI\" ROWS=5 cols=75 title=\"Informar fatos ou situações que sejam relevantes para uma melhor compreensão do financiamento da ação.\">".$w_obs_financ."</TEXTAREA></td>");
ShowHTML("      <tr><td align=\"center\" colspan=4><hr>");
if ($O=="E")
{

ShowHTML("   <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Excluir\">");
}
  else
if ($O=="I")
{

ShowHTML("            <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Incluir\">");
}
  else
if ($O=="A")
{

ShowHTML("            <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Alterar\">");
} 

ShowHTML("            <input class=\"STB\" type=\"button\" onClick=\"location.href='".$w_Pagina.$par."&w_chave=".$w_chave."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."&O=L';\" name=\"Botao\" value=\"Cancelar\">");
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

$w_sq_acao_ppa=null;

$w_obs_financ=null;


$w_troca=null;

$i=null;

$w_erro=null;

return $function_ret;
} 

// =========================================================================

// Rotina dos responsaveis

// -------------------------------------------------------------------------

function Responsaveis()
{
  extract($GLOBALS);




$w_Chave=${"w_Chave"};
$w_chave_aux=${"w_chave_aux"};
$w_sq_acao_ppa=${"w_sq_acao_ppa"};
$w_sq_acao_ppa_pai=${"w_sq_acao_ppa_pai"};
$w_sq_orprioridade=${"w_sq_orprioridade"};

if ($O=="L")
{

// Recupera todos os registros para a listagem

$DB_GetSolicData$RS$w_chave$SG;
}
  else
if ((strpos("A",$O) ? strpos("A",$O)+1 : 0)>0)
{

if ($w_sq_acao_ppa_pai>"")
{

$w_tipo=1;
DB_GetAcaoPPA($RS,$w_sq_acao_ppa_pai,$w_cliente,null,null,null,null,null,null,null,null);
}
  else
if ($w_sq_acao_ppa>"")
{

$w_tipo=2;
DB_GetAcaoPPA($RS,$w_sq_acao_ppa,$w_cliente,null,null,null,null,null,null,null,null);
}
  else
if ($w_sq_orprioridade>"")
{

$w_tipo=3;
DB_GetOrPrioridade($RS,null,$w_cliente,$w_sq_orprioridade,null,null,null);
} 

//DB_GetSolicData RS, w_chave, SG

if (!$RS->EOF)
{

$w_responsavel=$RS["responsavel"];
$w_telefone=$RS["telefone"];
$w_email=$RS["email"];
$w_nome=$RS["nome"];
$w_codigo=$RS["codigo"];
if ($w_tipo==2)
{

$w_nome_pai=$RS["nm_acao_pai"];
$w_codigo_pai=$RS["cd_pai"];
} 

} 

DesconectaBD();
} 


Cabecalho();
ShowHTML("<HEAD>");
if ((strpos("A",$O) ? strpos("A",$O)+1 : 0)>0)
{

ScriptOpen("JavaScript");
modulo();
checkbranco();
formatadata();
FormataCEP();
FormataValor();
ValidateOpen("Validacao");
if ((strpos("A",$O) ? strpos("A",$O)+1 : 0)>0)
{

Validate("w_responsavel","Responsável","","1","3","60","1","1");
Validate("w_telefone","Telenfone","1","","7","14","1","1");
Validate("w_email","Email","","","3","60","1","1");
} 

ShowHTML("  theForm.Botao[0].disabled=true;");
ShowHTML("  theForm.Botao[1].disabled=true;");
ValidateClose();
ScriptClose();
} 

ShowHTML("</HEAD>");
ShowHTML("<BASE HREF=\"".$conRootSIW."\">");
BodyOpen("onLoad='document.focus()';");
ShowHTML("<B><FONT COLOR=\"#000000\">".$w_TP."</FONT></B>");
ShowHTML("<HR>");
ShowHTML("<div align=center><center>");
ShowHTML("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">");
if ($O=="L")
{

AbreSessao();
ShowHTML("<tr><td align=\"center\" colspan=3>&nbsp;");
ShowHTML("<tr><td align=\"center\" colspan=3>");
ShowHTML("    <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">");
ShowHTML("        <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">");
ShowHTML("          <td><font size=\"1\"><b>Tipo</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Nome</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Operações</font></td>");
ShowHTML("        </tr>");
if ($RS->EOF)
{
// Se não foram selecionados registros, exibe mensagem

ShowHTML("      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=7 align=\"center\"><font size=\"2\"><b>Não foram encontrados registros.</b></td></tr>");
}
  else
{

// Lista os registros selecionados para listagem

if (!!isset($RS["sq_acao_ppa"]))
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
ShowHTML("        <td><font size=\"1\">Programa PPA</td>");
ShowHTML("        <td><font size=\"1\">".$RS["nm_ppa_pai"]."</td>");
ShowHTML("        <td align=\"top\" nowrap><font size=\"1\">");
ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina.$par."&R=".$w_Pagina.$par."&O=A&w_chave=".$w_chave."&w_sq_acao_ppa_pai=".$RS["sq_acao_ppa_pai"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."&w_chave_aux=".$RS["sq_acao_ppa"]."\">Gerente Executivo</A>&nbsp");
ShowHTML("        </td>");
ShowHTML("      </tr>");
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
ShowHTML("        <td><font size=\"1\">Ação PPA</td>");
ShowHTML("        <td><font size=\"1\">".$RS["nm_ppa"]."</td>");
ShowHTML("        <td align=\"top\" nowrap><font size=\"1\">");
ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina.$par."&R=".$w_Pagina.$par."&O=A&w_chave=".$w_chave."&w_sq_acao_ppa=".$RS["sq_acao_ppa"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."&w_chave_aux=".$RS["sq_siw_solicitacao"]."\">Coordenador</A>&nbsp");
ShowHTML("        </td>");
ShowHTML("      </tr>");
} 

if (!!isset($RS["sq_orprioridade"]))
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
ShowHTML("        <td><font size=\"1\">Iniciativa</td>");
ShowHTML("        <td><font size=\"1\">".$RS["nm_pri"]."</td>");
ShowHTML("        <td align=\"top\" nowrap><font size=\"1\">");
ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina.$par."&R=".$w_Pagina.$par."&O=A&w_chave=".$w_chave."&w_sq_orprioridade=".$RS["sq_orprioridade"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."&w_chave_aux=".$RS["sq_orprioridade"]."\">Responsável</A>&nbsp");
ShowHTML("        </td>");
ShowHTML("      </tr>");
} 

} 

ShowHTML("      </center>");
ShowHTML("    </table>");
ShowHTML("  </td>");
ShowHTML("</tr>");
DesconectaBD();
}
  else
if ((strpos("A",$O) ? strpos("A",$O)+1 : 0)>0)
{

AbreForm("Form",$w_dir.$w_pagina."Grava","POST","return(Validacao(this));",null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
ShowHTML("<INPUT type=\"hidden\" name=\"w_troca\" value=\"\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_chave\" value=\"".$w_chave."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_tipo\" value=\"".$w_tipo."\">");
if ($w_tipo==1)
{

$w_label="Programa PPA";
$w_chave_aux=$w_sq_acao_ppa_pai;
}
  else
if ($w_tipo==2)
{

$w_label="Ação PPA";
$w_chave_aux=$w_sq_acao_ppa;
}
  else
if ($w_tipo==3)
{

$w_label="Iniciativa prioritária";
$w_chave_aux=$w_sq_orprioridade;
} 

ShowHTML("<INPUT type=\"hidden\" name=\"w_chave_aux\" value=\"".$w_chave_aux."\">");
ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">");
ShowHTML("    <table width=\"97%\" border=\"0\">");
if ($w_tipo==2)
{

ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>Programa PPA: </b>".$w_codigo_pai." - ".$w_nome_pai." </b>");
} 

ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b>".$w_label.": </b>");
if (!$w_tipo==3)
{

ShowHTML("".$w_codigo." - ");
} 

ShowHTML("".$w_nome."</td>");
if ($w_tipo==1)
{

ShowHTML("      <tr><td><font size=\"1\"><b><u>G</u>erente executivo:</b><br><input ".$w_Disabled." accesskey=\"G\" type=\"text\" name=\"w_responsavel\" class=\"STI\" SIZE=\"50\" MAXLENGTH=\"60\" VALUE=\"".$w_responsavel."\" title=\"Informe um gerente executivo.\"></td>");
}
  else
if ($w_tipo==2)
{

ShowHTML("      <tr><td><font size=\"1\"><b><u>C</u>oordenador:</b><br><input ".$w_Disabled." accesskey=\"C\" type=\"text\" name=\"w_responsavel\" class=\"STI\" SIZE=\"50\" MAXLENGTH=\"60\" VALUE=\"".$w_responsavel."\" title=\"Informe um coordenador.\"></td>");
}
  else
if ($w_tipo==3)
{

ShowHTML("      <tr><td><font size=\"1\"><b>Res<u>p</u>onsável:</b><br><input ".$w_Disabled." accesskey=\"P\" type=\"text\" name=\"w_responsavel\" class=\"STI\" SIZE=\"50\" MAXLENGTH=\"60\" VALUE=\"".$w_responsavel."\" title=\"Informe um responsável.\"></td>");
} 

ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b><u>T</u>elefone:</b><br><input ".$w_Disabled." accesskey=\"T\" type=\"text\" name=\"w_telefone\" class=\"STI\" SIZE=\"15\" MAXLENGTH=\"14\" VALUE=\"".$w_telefone."\"></td>");
ShowHTML("      <tr><td><font size=\"1\"><b>E<u>m</u>ail:</b><br><input ".$w_Disabled." accesskey=\"M\" type=\"text\" name=\"w_email\" class=\"STI\" SIZE=\"50\" MAXLENGTH=\"60\" VALUE=\"".$w_email."\" title=\"Informe o email do responsável.\"></td>");
ShowHTML("      <tr><td align=\"center\" colspan=4><hr>");
ShowHTML("            <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Gravar\">");
ShowHTML("            <input class=\"STB\" type=\"button\" onClick=\"location.href='".$w_Pagina.$par."&w_chave=".$w_chave."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."&O=L';\" name=\"Botao\" value=\"Cancelar\">");
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

$w_responsavel=null;

$w_telefone=null;

$w_email=null;

$w_codigo=null;


$w_troca=null;

$i=null;

$w_erro=null;

return $function_ret;
} 

// =========================================================================

// Rotina de etapas da ação

// -------------------------------------------------------------------------

function Etapas()
{
  extract($GLOBALS);




$w_Chave=${"w_Chave"};
$w_Chave_pai=${"w_Chave_pai"};
$w_chave_aux=${"w_chave_aux"};
$w_troca=${"w_troca"};

if ($w_troca>"")
{
// Se for recarga da página

$w_ordem=${"w_ordem"};
$w_titulo=${"w_titulo"};
$w_descricao=${"w_descricao"};
$w_inicio=${"w_inicio"};
$w_fim=${"w_fim"};
$w_inicio_real=${"w_inicio_real"};
$w_fim_real=${"w_fim_real"};
$w_perc_conclusao=${"w_perc_conclusao"};
$w_orcamento=${"w_orcamento"};
$w_sq_pessoa=${"w_sq_pessoa"};
$w_sq_unidade=${"w_sq_unidade"};
$w_vincula_atividade=${"w_vincula_atividade"};
$w_unidade_medida=${"w_unidade_medida"};
$w_quantidade=${"w_quantidade"};
$w_cumulativa=${"w_cumulativa"};
$w_programada=${"w_programada"};
}
  else
if ($O=="L")
{

// Recupera todos os registros para a listagem

$DB_GetSolicEtapa$RS$w_chave$null//LISTA", null$RS->Sort="ordem";
}
  else
if ((strpos("AEV",$O) ? strpos("AEV",$O)+1 : 0)>0 && $w_Troca=="")
{

// Recupera os dados do endereço informado

$DB_GetSolicEtapa$RS$w_chave$w_chave_aux//REGISTRO", null$w_chave_pai=$RS["sq_etapa_pai"];
$w_titulo=$RS["titulo"];
$w_ordem=$RS["ordem"];
$w_descricao=$RS["descricao"];
$w_inicio=$RS["inicio_previsto"];
$w_fim=$RS["fim_previsto"];
$w_inicio_real=$RS["inicio_real"];
$w_fim_real=$RS["fim_real"];
$w_perc_conclusao=$RS["perc_conclusao"];
$w_orcamento=$RS["orcamento"];
$w_sq_pessoa=$RS["sq_pessoa"];
$w_sq_unidade=$RS["sq_unidade"];
$w_vincula_atividade=$RS["vincula_atividade"];
$w_unidade_medida=$RS["unidade_medida"];
$w_quantidade=$RS["quantidade"];
$w_cumulativa=$RS["cumulativa"];
$w_programada=$RS["programada"];
DesconectaBD();
}
  else
if (Nvl($w_sq_pessoa,"")=="")
{

// Se a etapa não tiver responsável atribuído, recupera o responsável pela ação

$DB_GetSolicData$RS$w_chave//ORGERAL"
$w_sq_pessoa=$RS["solicitante"];
$w_sq_unidade=$RS["sq_unidade_resp"];
} 


Cabecalho();
ShowHTML("<HEAD>");
if ((strpos("IAEP",$O) ? strpos("IAEP",$O)+1 : 0)>0)
{

ScriptOpen("JavaScript");
modulo();
checkbranco();
formatadata();
FormataValor();
ValidateOpen("Validacao");
if ((strpos("IA",$O) ? strpos("IA",$O)+1 : 0)>0)
{

Validate("w_ordem","Tipo de visão","SELECT","1","1","10","","1");
Validate("w_titulo","Título","","1","2","100","1","1");
Validate("w_quantidade","Quantitativo programado","","1","2","18","","1");
Validate("w_unidade_medida","Unidade de medida","","1","2","100","1","1");
Validate("w_descricao","Descricao","","1","2","2000","1","1");
Validate("w_ordem","Ordem","1","1","1","3","","0123456789");
//Validate "w_chave_pai", "Subordinação", "SELECT", "", "1", "10", "", "1"

Validate("w_inicio","Início previsto","DATA","1","10","10","","0123456789/");
Validate("w_fim","Fim previsto","DATA","1","10","10","","0123456789/");
CompData("w_inicio","Início previsto","<=","w_fim","Fim previsto");
//Validate "w_orcamento", "Recurso programado", "VALOR", "1", "4", "18", "", "0123456789.,"

//Validate "w_perc_conclusao", "Percentual de conclusão", "", "1", "1", "3", "", "0123456789"

Validate("w_sq_pessoa","Responsável","SELECT","","1","10","","1");
Validate("w_sq_unidade","Setor responsável","SELECT","","1","10","","1");
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

BodyOpen("onLoad='document.Form.w_titulo.focus()';");
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

ShowHTML("<tr><td><font size=\"2\"><a accesskey=\"I\" class=\"SS\" href=\"".$w_dir.$w_pagina.$par."&R=".$w_Pagina.$par."&O=I&w_chave=".$w_chave."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\"><u>I</u>ncluir</a>&nbsp;");
ShowHTML("    <td align=\"right\"><font size=\"1\"><b>Registros existentes: ".$RS->RecordCount);
ShowHTML("<tr><td align=\"center\" colspan=3>");
ShowHTML("    <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">");
ShowHTML("        <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">");
ShowHTML("          <td><font size=\"1\"><b>Metas</font></td>");
//ShowHTML "          <td><font size=""1""><b>Produto</font></td>"

//ShowHTML "          <td rowspan=2><font size=""1""><b>Responsável</font></td>"

//ShowHTML "          <td rowspan=2><font size=""1""><b>Setor</font></td>"

ShowHTML("          <td><font size=\"1\"><b>Execução até</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Conc.</font></td>");
//ShowHTML "          <td rowspan=2><font size=""1""><b>Ativ.</font></td>"

ShowHTML("          <td><font size=\"1\"><b>Operações</font></td>");
ShowHTML("        </tr>");
//ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"

//ShowHTML "          <td><font size=""1""><b>De</font></td>"

//ShowHTML "          <td><font size=""1""><b>Até</font></td>"

//ShowHTML "        </tr>"

// Recupera as etapas principais

$DB_GetSolicEtapa$RS$w_chave$null//LSTNULL", null$RS->Sort="ordem";
if ($RS->EOF)
{
// Se não foram selecionados registros, exibe mensagem

ShowHTML("      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=9 align=\"center\"><font size=\"2\"><b>Não foram encontrados registros.</b></td></tr>");
}
  else
{

while(!$RS->EOF)
{

ShowHtml($EtapaLinha[$w_chave][$Rs["sq_projeto_etapa"]][$Rs["titulo"]][$Rs["nm_resp"]][$Rs["sg_setor"]]);

// Recupera as etapas vinculadas ao nível acima

$DB_GetSolicEtapa$RS1$w_chave$RS["sq_projeto_etapa"]//LSTNIVEL", null$RS1->Sort="ordem";
while(!$RS1->EOF)
{

ShowHTML($EtapaLinha[$w_chave][$RS1["sq_projeto_etapa"]][$RS1["titulo"]][$RS1["nm_resp"]][$RS1["sg_setor"]]);

// Recupera as etapas vinculadas ao nível acima

$DB_GetSolicEtapa$w_chave$RS1["sq_projeto_etapa"]//LSTNIVEL", nullecho "ordem";
while(!($RS2==0))
{

ShowHTML($EtapaLinha[$w_chave][$RS2["sq_projeto_etapa"]][$RS2["titulo"]][$RS2["nm_resp"]][$RS2["sg_setor"]]);

// Recupera as etapas vinculadas ao nível acima

$DB_GetSolicEtapa$w_chave$RS2["sq_projeto_etapa"]//LSTNIVEL", nullecho "ordem";
while(!($RS3==0))
{

ShowHTML($EtapaLinha[$w_chave][$RS3["sq_projeto_etapa"]][$RS3["titulo"]][$RS3["nm_resp"]][$RS3["sg_setor"]]);

// Recupera as etapas vinculadas ao nível acima

$DB_GetSolicEtapa$w_chave$RS3["sq_projeto_etapa"]//LSTNIVEL", nullecho "ordem";
while(!($RS4==0))
{

ShowHTML($EtapaLinha[$w_chave][$RS4["sq_projeto_etapa"]][$RS4["titulo"]][$RS4["nm_resp"]][$RS4["sg_setor"]]);
$RS4=mysql_fetch_array($RS4_query);

} 

$RS3=mysql_fetch_array($RS3_query);

} 

$RS2=mysql_fetch_array($RS2_query);

} 

$RS1->MoveNext;
} 

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
ShowHTML("<INPUT type=\"hidden\" name=\"w_chave_aux\" value=\"".$w_chave_aux."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_troca\" value=\"\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_sq_pessoa\" value=\"".$w_sq_pessoa."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_sq_unidade\" value=\"".$w_sq_unidade."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_orcamento\" value=\"0,00\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_vincula_atividade\" value=\"N\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_perc_conclusao\" value=\"0\">");

ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">");
ShowHTML("    <table width=\"97%\" border=\"0\">");
ShowHTML("      <tr><td><font size=\"1\"><b>Prod<u>u</u>to:</b><br><input ".$w_Disabled." accesskey=\"U\" type=\"text\" name=\"w_titulo\" class=\"STI\" SIZE=\"90\" MAXLENGTH=\"90\" VALUE=\"".$w_titulo."\" title=\"Bem ou serviço que resulta da ação, destinado ao público-alvo ou o investimento para a produção deste bem ou serviço. Para cada ação deve haver um só produto. Em situações especiais, expressa a quantidade de beneficiários atendidos pela ação.\"></td>");
ShowHTML("     <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0><tr valign=\"top\">");
MontaRadioNS("<b>Meta LOA?</b>",$w_programada,"w_programada");
MontaRadioNS("<b>Meta cumulativa?</b>",$w_cumulativa,"w_cumulativa");
ShowHTML("         </table></td></tr>");
ShowHTML("     <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0><tr valign=\"top\">");
ShowHTML("         <tr><td align=\"left\"><font size=\"1\"><b><u>Q</u>uantitativo:<br><INPUT ACCESSKEY=\"Q\" TYPE=\"TEXT\" CLASS=\"STI\" NAME=\"w_quantidade\" SIZE=5 MAXLENGTH=18 VALUE=\"".$w_quantidade."\" ".$w_Disabled."></td>");
ShowHTML("             <td align=\"left\"><font size=\"1\"><b><u>U</u>nidade de medida:<br><INPUT ACCESSKEY=\"U\" TYPE=\"TEXT\" CLASS=\"STI\" NAME=\"w_unidade_medida\" SIZE=15 MAXLENGTH=30 VALUE=\"".$w_unidade_medida."\" ".$w_Disabled."></td>");
ShowHTML("         </table></td></tr>");

ShowHTML("      <tr><td><font size=\"1\"><b><u>E</u>specificação do produto:</b><br><textarea ".$w_Disabled." accesskey=\"E\" name=\"w_descricao\" class=\"STI\" ROWS=5 cols=75 title=\"Expresse as características do produto acabado visando sua melhor identificação.\">".$w_descricao."</TEXTAREA></td>");
//ShowHTML "      <tr>"

//SelecaoEtapa "Me<u>t</u>a superior:", "T", "Se necessário, indique a meta superior a esta.", w_chave_pai, w_chave, w_chave_aux, "w_chave_pai", "Pesquisa", null

//ShowHTML "      </tr>"

ShowHTML("      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0><tr valign=\"top\">");
// Recupera o número de ordem das outras opções irmãs à selecionada

DB_GetEtapaOrder($RS,$w_chave,$w_chave_pai);
if (!$RS->EOF)
{

$w_texto="<b>Nºs de ordem em uso para esta subordinação:</b><br>".
  "<table border=1 width=100% cellpadding=0 cellspacing=0>".
  "<tr><td align=center><b><font size=1>Ordem".
  "    <td><b><font size=1>Descrição";
while(!$RS->EOF)
{

$w_texto=$w_texto."<tr><td valign=top align=center><font size=1>".$RS["ordem"]."<td valign=top><font size=1>".$RS["titulo"];
$RS->MoveNext;
} 
$w_texto=$w_texto."</table>";
}
  else
{

$w_texto="Não há outros números de ordem subordinados a esta etapa.";
} 

ShowHTML("              <td align=\"left\"><font size=\"1\"><b><u>O</u>rdem:<br><INPUT ACCESSKEY=\"O\" TYPE=\"TEXT\" CLASS=\"STI\" NAME=\"w_ordem\" SIZE=3 MAXLENGTH=3 VALUE=\"".$w_ordem."\" ".$w_Disabled." title=\"".str_replace(chr(13).chr(10),"<BR>",$w_texto)."\"></td>");
ShowHTML("              <td><font size=\"1\"><b>Previsão iní<u>c</u>io:</b><br><input ".$w_Disabled." accesskey=\"C\" type=\"text\" name=\"w_inicio\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".FormataDataEdicao(Nvl($w_inicio,time()()))."\" onKeyDown=\"FormataData(this,event);\" title=\"Data prevista para início da meta.\"></td>");
ShowHTML("              <td><font size=\"1\"><b>Previsão <u>t</u>érmino:</b><br><input ".$w_Disabled." accesskey=\"T\" type=\"text\" name=\"w_fim\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".FormataDataEdicao($w_fim)."\" onKeyDown=\"FormataData(this,event);\" title=\"Data prevista para término da meta.\"></td>");
//ShowHTML "          <tr valign=""top"">"

//ShowHTML "              <td><font size=""1""><b>Orça<u>m</u>ento previsto:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_orcamento"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & FormatNumber(w_orcamento,2) & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Recurso programado para execução desta etapa.""></td>"

//ShowHTML "              <td align=""left""><font size=""1""><b>Percentual de co<u>n</u>clusão:<br><INPUT ACCESSKEY=""N"" TYPE=""TEXT"" CLASS=""STI"" NAME=""w_perc_conclusao"" SIZE=3 MAXLENGTH=3 VALUE=""" & nvl(w_perc_conclusao,0) & """ " & w_Disabled & " title=""Informe o percentual de conclusão atual da meta.""></td>"

//MontaRadioSN "<b>Permite vinculação de atividades?</b>", w_vincula_atividade, "w_vincula_atividade"

ShowHTML("          </table>");
ShowHTML("      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>");
//SelecaoPessoa "Respo<u>n</u>sável pela etapa:", "N", "Selecione o responsável pela etapa na relação.", w_sq_pessoa, null, "w_sq_pessoa", "USUARIOS"

//SelecaoUnidade "<U>S</U>etor responsável pela etapa:", "S", "Selecione o setor responsável pela execução da etapa", w_sq_unidade, null, "w_sq_unidade", null, null

ShowHTML("          <tr>");
ShowHTML("      <tr>");
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

ShowHTML("            <input class=\"STB\" type=\"button\" onClick=\"location.href='".$w_Pagina.$par."&w_chave=".$w_chave."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."&O=L';\" name=\"Botao\" value=\"Cancelar\">");
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

$w_inicio=null;

$w_fim=null;

$w_perc_conclusao=null;

$w_orcamento=null;

$w_sq_pessoa=null;

$w_sq_unidade=null;

$w_vincula_atividade=null;


$w_chave=null;

$w_chave_pai=null;

$w_chave_aux=null;

$w_titulo=null;

$w_ordem=null;

$w_descricao=null;


$w_troca=null;

$i=null;

$w_texto=null;

return $function_ret;
} 

// =========================================================================

// Rotina de atualização das etapas da ação

// -------------------------------------------------------------------------

function AtualizaEtapa()
{
  extract($GLOBALS);




$w_Chave=${"w_Chave"};
$w_Chave_pai=${"w_Chave_pai"};
$w_chave_aux=${"w_chave_aux"};
$w_troca=${"w_troca"};
$w_tipo=strtoupper(trim(${"w_tipo"}));

$DB_GetSolicData$RS$w_chave//ORGERAL"$w_cabecalho="      <tr><td valign=\"top\" colspan=\"3\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font  size=\"2\"><b>Ação: ".$RS["titulo"]." (".$w_chave.")</td></tr>";

// Configura uma variável para testar se as etapas podem ser atualizadas.

// Ações concluídas ou canceladas não podem ter permitir a atualização.

if (Nvl($RS["sg_tramite"],"--")=="EE")
{

$w_fase="S";
}
  else
{

$w_fase="N";
} 

DesconectaBD();

if ($w_troca>"")
{
// Se for recarga da página

$w_ordem=${"w_ordem"};
$w_titulo=${"w_titulo"};
$w_descricao=${"w_descricao"};
$w_inicio=${"w_inicio"};
$w_fim=${"w_fim"};
$w_inicio_real=${"w_inicio_real"};
$w_fim_real=${"w_fim_real"};
$w_perc_conclusao=${"w_perc_conclusao"};
$w_orcamento=${"w_orcamento"};
$w_sq_pessoa=${"w_sq_pessoa"};
$w_sq_unidade=${"w_sq_unidade"};
$w_vincula_atividade=${"w_vincula_atividade"};
$w_unidade_medida=${"w_unidade_medida"};
$w_quantidade=${"w_quantidade"};
$w_cumulativa=${"w_cumulativa"};
$w_programada=${"w_programada"};
for ($i=0; $i<=$i=12; $i=$i+1)
{

$w_execucao_fisica[i]=${"w_execucao_fisica[i]"};
$w_execucao_financeira[i]=${"w_execucao_financeira[i]"};

} 

}
  else
if ($O=="L")
{

// Recupera todos os registros para a listagem

$DB_GetSolicEtapa$RS$w_chave$null//LISTA", null$RS->Sort="ordem";

// Recupera o código da opção de menu  a ser usada para listar as atividades

$w_p2="";
if (!$RS->EOF)
{

while(!$RS->EOF)
{

if ($cDbl[Nvl($RS["P2"],0)]>$cDbl[0])
{

$w_p2=$RS["P2"];
$RS->MoveLast;
} 

$RS->MoveNext;
} 
$RS->MoveFirst;
} 

}
  else
if ((strpos("AEV",$O) ? strpos("AEV",$O)+1 : 0)>0 && $w_Troca=="")
{

// Recupera os dados do endereço informado

$DB_GetSolicEtapa$RS$w_chave$w_chave_aux//REGISTRO", null$w_chave_pai=$RS["sq_etapa_pai"];
$w_titulo=$RS["titulo"];
$w_ordem=$RS["ordem"];
$w_descricao=$RS["descricao"];
$w_inicio=$RS["inicio_previsto"];
$w_fim=$RS["fim_previsto"];
$w_inicio_real=$RS["inicio_real"];
$w_fim_real=$RS["fim_real"];
$w_perc_conclusao=$RS["perc_conclusao"];
$w_orcamento=$RS["orcamento"];
$w_sq_pessoa=$RS["sq_pessoa"];
$w_sq_unidade=$RS["sq_unidade"];
$w_vincula_atividade=$RS["vincula_atividade"];
$w_ultima_atualizacao=$RS["ultima_atualizacao"];
$w_sq_pessoa_atualizacao=$RS["sq_pessoa_atualizacao"];
$w_situacao_atual=$RS["situacao_atual"];
$w_unidade_medida=$RS["unidade_medida"];
$w_quantidade=$RS["quantidade"];
$w_cumulativa=$RS["cumulativa"];
$w_programada=$RS["programada"];
$w_exequivel=$RS["exequivel"];
$w_justificativa_inex=$RS["justificativa_inexequivel"];
$w_outras_medidas=$RS["outras_medidas"];
$w_nm_programada=$RS["nm_programada"];
$w_nm_cumulativa=$RS["nm_cumulativa"];
DesconectaBD();
DB_GetEtapaMensal($RS,$w_chave_aux);
if (!$RS->EOF)
{

while(!$RS->EOF)
{

switch (strftime("%m",($cDate[$RS["referencia"]])))
{
case 1$w_quantitativo_1=$RS["execucao_fisica"];
:
break;
case 2$w_quantitativo_2=$RS["execucao_fisica"];
:
break;
case 3$w_quantitativo_3=$RS["execucao_fisica"];
:
break;
case 4$w_quantitativo_4=$RS["execucao_fisica"];
:
break;
case 5$w_quantitativo_5=$RS["execucao_fisica"];
:
break;
case 6$w_quantitativo_6=$RS["execucao_fisica"];
:
break;
case 7$w_quantitativo_7=$RS["execucao_fisica"];
:
break;
case 8$w_quantitativo_8=$RS["execucao_fisica"];
:
break;
case 9$w_quantitativo_9=$RS["execucao_fisica"];
:
break;
case 10$w_quantitativo_10=$RS["execucao_fisica"];
:
break;
case 11$w_quantitativo_11=$RS["execucao_fisica"];
:
break;
case 12$w_quantitativo_12=$RS["execucao_fisica"];
:
break;
} 
$RS->MoveNext;
} 
} 

DesconectaBD();
}
  else
if (Nvl($w_sq_pessoa,"")=="")
{

// Se a etapa não tiver responsável atribuído, recupera o responsável pela ação

$DB_GetSolicData$RS$w_chave//ORGERAL"
$w_sq_pessoa=$RS["solicitante"];
$w_sq_unidade=$RS["sq_unidade_resp"];
} 

if ($w_tipo=="WORD")
{

header("Content-type: "."application/msword");
}
  else
{

Cabecalho();
} 

ShowHTML("<HEAD>");
ShowHTML("<TITLE>".$conSgSistema." - Meta da ação</TITLE>");
if ((strpos("IAEP",$O) ? strpos("IAEP",$O)+1 : 0)>0)
{

ScriptOpen("JavaScript");
FormataValor();
ValidateOpen("Validacao");
if ((strpos("IA",$O) ? strpos("IA",$O)+1 : 0)>0)
{

Validate("w_quantitativo_1","Quantitativo de Janeiro","","","1","10","","0123456789");
Validate("w_quantitativo_2","Quantitativo de Fevereiro","","","1","10","","0123456789");
Validate("w_quantitativo_3","Quantitativo de Março","","","1","10","","0123456789");
Validate("w_quantitativo_4","Quantitativo de Abril","","","1","10","","0123456789");
Validate("w_quantitativo_5","Quantitativo de Maio","","","1","10","","0123456789");
Validate("w_quantitativo_6","Quantitativo de Junho","","","1","10","","0123456789");
Validate("w_quantitativo_7","Quantitativo de Julho","","","1","10","","0123456789");
Validate("w_quantitativo_8","Quantitativo de Agosto","","","1","10","","0123456789");
Validate("w_quantitativo_9","Quantitativo de Setembro","","","1","10","","0123456789");
Validate("w_quantitativo_10","Quantitativo de Outubro","","","1","10","","0123456789");
Validate("w_quantitativo_11","Quantitativo de Novembro","","","1","10","","0123456789");
Validate("w_quantitativo_12","Quantitativo de Dezembro","","","1","10","","0123456789");
Validate("w_situacao_atual","Situação atual","","","2","4000","1","1");
ShowHTML("  if (theForm.w_exequivel[1].checked && theForm.w_justificativa_inex.value == '') {");
ShowHTML("     alert ('Justifique porque a meta não será cumprida!');");
ShowHTML("     theForm.w_justificativa_inex.focus();");
ShowHTML("     return false;");
ShowHTML("  } else { if (theForm.w_exequivel[0].checked) ");
ShowHTML("     theForm.w_justificativa_inex.value = '';");
ShowHTML("   }");
ShowHTML("  if (theForm.w_exequivel[1].checked && theForm.w_outras_medidas.value == '') {");
ShowHTML("     alert ('Indique quais são as medidas necessárias para o cumprimento da meta!');");
ShowHTML("     theForm.w_outras_medidas.focus();");
ShowHTML("     return false;");
ShowHTML("  } else { if (theForm.w_exequivel[0].checked) ");
ShowHTML("     theForm.w_outras_medidas.value = '';");
ShowHTML("   }");
Validate("w_justificativa_inex","Justificativa","","","2","4000","1","1");
Validate("w_outras_medidas","Medidas","","","2","4000","1","1");
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
if ($O=="I" || $O=="A")
{

BodyOpen("onLoad='document.Form.focus()';");
}
  else
{

BodyOpen("onLoad='document.focus()';");
} 

//ShowHTML "<B><FONT COLOR=""#000000"">" & Mid(w_TP,1, Instr(w_TP,"-")-1) & "- Metas" & "</FONT></B>"

//ShowHTML "<HR>"

ShowHTML("<div align=center><center>");
ShowHTML("  <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">");
ShowHTML($w_cabecalho);
if ($w_tipo!="WORD" && $O=="V")
{

ShowHTML("<tr><td align=\"right\"colspan=\"2\">");
ShowHTML("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<IMG ALIGN=\"CENTER\" TITLE=\"Imprimir\" SRC=\"images/impressora.jpg\" onClick=\"window.print();\">");
ShowHTML("&nbsp;&nbsp;<IMG ALIGN=\"CENTER\" TITLE=\"Gerar word\" SRC=\"images/word.gif\" onClick=\"window.open('Projeto.asp?par=AtualizaEtapa&O=V&w_chave=".$w_chave."&w_chave_aux=".$w_chave_aux."&w_tipo=WORD&P1=10&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."','MetaWord','width=600, height=350, top=65, left=65, menubar=yes, scrollbars=yes, resizable=yes, status=no');\">");
ShowHTML("</td></tr>");
} 

if ($O=="L")
{

AbreSessao();
// Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem

ShowHTML("  <tr><td colspan=\"2\"><font size=\"3\"></td>");
ShowHTML("    <td align=\"right\"><font size=\"1\"><b>Registros existentes: ".$RS->RecordCount."</td></tr>");
ShowHTML("  <tr><td align=\"center\" colspan=\"3\">");
ShowHTML("      <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">");
ShowHTML("        <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">");
ShowHTML("          <td><font size=\"1\"><b>Metas</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Execução até</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Conc.</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Operações</font></td>");
ShowHTML("        </tr>");
// Recupera as etapas principais

$DB_GetSolicEtapa$RS$w_chave$null//LSTNULL", null$RS->Sort="ordem";
if ($RS->EOF)
{
// Se não foram selecionados registros, exibe mensagem

ShowHTML("    <tr bgcolor=\"".$conTrBgColor."\"><td colspan=4 align=\"center\"><font size=\"2\"><b>Não foi encontrado nenhum registro.</b></td></tr>");
}
  else
{

while(!$RS->EOF)
{

if ($cDbl[Nvl($RS["tit_exec"],0)]==$cDbl[$w_usuario] || 
   $cDbl[Nvl($RS["sub_exec"],0)]==$cDbl[$w_usuario] || 
   $cDbl[Nvl($RS["titular"],0)]==$cDbl[$w_usuario] || 
   $cDbl[Nvl($RS["substituto"],0)]==$cDbl[$w_usuario] || 
   $cDbl[Nvl($RS["executor"],0)]==$cDbl[$w_usuario] || 
   $cDbl[Nvl($RS["solicitante"],0)]==$cDbl[$w_usuario] || 
   $cDbl[Nvl($RS["sq_pessoa"],0)]==$cDbl[$w_usuario]
   )
{

ShowHtml($EtapaLinha[$w_chave][$Rs["sq_projeto_etapa"]][$Rs["titulo"]][$RS["nm_resp"]][$RS["sg_setor"]]);
}
  else
{

ShowHtml($EtapaLinha[$w_chave][$Rs["sq_projeto_etapa"]][$Rs["titulo"]][$RS["nm_resp"]][$RS["sg_setor"]]);
} 


// Recupera as etapas vinculadas ao nível acima

$DB_GetSolicEtapa$RS1$w_chave$RS["sq_projeto_etapa"]//LSTNIVEL", null$RS1->Sort="ordem";
while(!$RS1->EOF)
{

if ($cDbl[Nvl($RS1["titular"],0)]==$cDbl[$w_usuario] || $cDbl[Nvl($RS1["substituto"],0)]==$cDbl[$w_usuario] || $cDbl[Nvl($RS1["sq_pessoa"],0)]==$cDbl[$w_usuario])
{

ShowHTML($EtapaLinha[$w_chave][$RS1["sq_projeto_etapa"]][$RS1["titulo"]][$RS1["nm_resp"]][$RS1["sg_setor"]]);
}
  else
{

ShowHTML($EtapaLinha[$w_chave][$RS1["sq_projeto_etapa"]][$RS1["titulo"]][$RS1["nm_resp"]][$RS1["sg_setor"]]);
} 


// Recupera as etapas vinculadas ao nível acima

$DB_GetSolicEtapa$w_chave$RS1["sq_projeto_etapa"]//LSTNIVEL", nullecho "ordem";
while(!($RS2==0))
{

if ($cDbl[Nvl($RS2["titular"],0)]==$cDbl[$w_usuario] || $cDbl[Nvl($RS2["substituto"],0)]==$cDbl[$w_usuario] || $cDbl[Nvl($RS2["sq_pessoa"],0)]==$cDbl[$w_usuario])
{

ShowHTML($EtapaLinha[$w_chave][$RS2["sq_projeto_etapa"]][$RS2["titulo"]][$RS2["nm_resp"]][$RS2["sg_setor"]]);
}
  else
{

ShowHTML($EtapaLinha[$w_chave][$RS2["sq_projeto_etapa"]][$RS2["titulo"]][$RS2["nm_resp"]][$RS2["sg_setor"]]);
} 


// Recupera as etapas vinculadas ao nível acima

$DB_GetSolicEtapa$w_chave$RS2["sq_projeto_etapa"]//LSTNIVEL", nullecho "ordem";
while(!($RS3==0))
{

if ($cDbl[Nvl($RS3["titular"],0)]==$cDbl[$w_usuario] || $cDbl[Nvl($RS3["substituto"],0)]==$cDbl[$w_usuario] || $cDbl[Nvl($RS3["sq_pessoa"],0)]==$cDbl[$w_usuario])
{

ShowHTML($EtapaLinha[$w_chave][$RS3["sq_projeto_etapa"]][$RS3["titulo"]][$RS3["nm_resp"]][$RS3["sg_setor"]]);
}
  else
{

ShowHTML($EtapaLinha[$w_chave][$RS3["sq_projeto_etapa"]][$RS3["titulo"]][$RS3["nm_resp"]][$RS3["sg_setor"]]);
} 


// Recupera as etapas vinculadas ao nível acima

$DB_GetSolicEtapa$w_chave$RS3["sq_projeto_etapa"]//LSTNIVEL", nullecho "ordem";
while(!($RS4==0))
{

if ($cDbl[Nvl($RS4["titular"],0)]==$cDbl[$w_usuario] || $cDbl[Nvl($RS4["substituto"],0)]==$cDbl[$w_usuario] || $cDbl[Nvl($RS4["sq_pessoa"],0)]==$cDbl[$w_usuario])
{

  ShowHTML($EtapaLinha[$w_chave][$RS4["sq_projeto_etapa"]][$RS4["titulo"]][$RS4["nm_resp"]][$RS4["sg_setor"]]);
}
  else
{

  ShowHTML($EtapaLinha[$w_chave][$RS4["sq_projeto_etapa"]][$RS4["titulo"]][$RS4["nm_resp"]][$RS4["sg_setor"]]);
} 

$RS4=mysql_fetch_array($RS4_query);

} 

$RS3=mysql_fetch_array($RS3_query);

} 

$RS2=mysql_fetch_array($RS2_query);

} 

$RS1->MoveNext;
} 

$RS->MoveNext;
} 
ShowHTML("      </FORM>");
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

if ($w_tipo!="WORD")
{

AbreForm("Form",$w_dir.$w_pagina."Grava","POST","return(Validacao(this));",null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
ShowHTML("<INPUT type=\"hidden\" name=\"w_chave\" value=\"".$w_chave."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_chave_aux\" value=\"".$w_chave_aux."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_perc_ant\" value=\"".$w_perc_conclusao."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_troca\" value=\"\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_cumulativa\" value=\"".$w_cumulativa."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_quantidade\" value=\"".$w_quantidade."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_referencia_1\" value=\"01/01/2004\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_referencia_2\" value=\"01/02/2004\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_referencia_3\" value=\"01/03/2004\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_referencia_4\" value=\"01/04/2004\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_referencia_5\" value=\"01/05/2004\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_referencia_6\" value=\"01/06/2004\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_referencia_7\" value=\"01/07/2004\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_referencia_8\" value=\"01/08/2004\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_referencia_9\" value=\"01/09/2004\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_referencia_10\" value=\"01/10/2004\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_referencia_11\" value=\"01/11/2004\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_referencia_12\" value=\"01/12/2004\">");
} 

ShowHTML("    <tr><td align=\"center\" bgcolor=\"#FAEBD7\" colspan=\"2\">");
ShowHTML("      <table border=1 width=\"100%\">");
ShowHTML("        <tr><td valign=\"top\" colspan=\"2\">");
ShowHTML("          <TABLE border=0 WIDTH=\"100%\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">");
ShowHTML("            <tr><td colspan=\"2\"><font size=\"1\">Meta:<b><br><font size=2>".MontaOrdemEtapa($w_chave_aux).". ".$w_titulo."</font></td></tr>");
ShowHTML("            <tr><td colspan=\"2\"><font size=\"1\">Descrição:<b><br>".$w_descricao."</td></tr>");
ShowHTML("            <tr><td valign=\"top\" colspan=\"2\">");
ShowHTML("              <table border=0 width=\"100%\" cellspacing=0><tr valign=\"top\">");
ShowHTML("                <td><font size=\"1\">Meta LOA?<b><br>".$w_nm_programada."</td>");
ShowHTML("                <td><font size=\"1\">Meta cumulativa:<b><br>".$w_nm_cumulativa."</td></tr>");
ShowHTML("              </table></td></tr>");
ShowHTML("            <tr><td valign=\"top\" colspan=\"2\">");
ShowHTML("              <table border=0 width=\"100%\" cellspacing=0><tr valign=\"top\">");
ShowHTML("                <td><font size=\"1\">Quantitativo:<b><br>".$w_quantidade."</td>");
ShowHTML("                <td><font size=\"1\">Unidade de medida:<b><br>".Nvl($w_unidade_medida,"---")."</td></tr>");
ShowHTML("              </table></td></tr>");
ShowHTML("            <tr><td valign=\"top\" colspan=\"2\">");
ShowHTML("              <table border=0 width=\"100%\" cellspacing=0><tr valign=\"top\">");
ShowHTML("                <td><font size=\"1\">Previsão início:<b><br>".FormataDataEdicao(Nvl($w_inicio,time()()))."</td>");
ShowHTML("                <td><font size=\"1\">Previsão término:<b><br>".FormataDataEdicao($w_fim)."</td></tr>");
ShowHTML("                <tr valign=\"top\">");
DB_GetPersonData($RS,$w_cliente,$w_sq_pessoa,null,null);
ShowHTML("                  <td><font size=\"1\">Responsável pela meta:<b><br>".$RS["nome_resumido"]."</td>");
DesconectaBD();
DB_GetUorgData($RS,$w_sq_unidade);
ShowHTML("                  <td><font size=\"1\">Setor responsável pela meta:<b><br>".$RS["nome"]." (".$RS["sigla"].")</td></tr>");
DesconectaBD();
DB_GetPersonData($RS,$w_cliente,$w_sq_pessoa_atualizacao,null,null);
ShowHTML("                <tr><td colspan=\"2\"><font size=\"1\">Criação/última atualização:<b><br><font size=1>".FormataDataEdicao($w_ultima_atualizacao)."</b>, feita por <b>".$RS["nome_resumido"]." (".$RS["sigla"].")</b></font></td></tr>");
DesconectaBD();
ShowHTML("              </table></td></tr>");
ShowHTML("          </TABLE>");
ShowHTML("      </table>");
ShowHTML("    <tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\" colspan=\"2\">");
ShowHTML("      <table width=\"100%\" border=\"0\">");
if ($O=="V")
{

ShowHTML("     <tr><td valign=\"top\">");
ShowHTML("       <table border=0 width=\"100%\" cellspacing=0><tr valign=\"top\">");
ShowHTML("         <tr><td>&nbsp<td><font size=\"1\"><br><b>Quantitativo realizado</b></td>");
ShowHTML("             <td>&nbsp<td><font size=\"1\"><br><b>Quantitativo realizado</b></td>");
ShowHTML("         <tr><td width=\"10%\" align=\"right\"><font size=\"1\"><b>Janeiro:");
ShowHTML("             <td width=\"30%\"><font size=\"1\">".Nvl($w_quantitativo_1,"---")."</td>");
ShowHTML("             <td width=\"20%\" align=\"right\"><font size=\"1\"><b>Julho:");
ShowHTML("             <td><font size=\"1\">".Nvl($w_quantitativo_7,"---")."</td>");
ShowHTML("         <tr><td align=\"right\"><font size=\"1\"><b>Fevereiro:");
ShowHTML("             <td><font size=\"1\">".Nvl($w_quantitativo_2,"---")."</td>");
ShowHTML("             <td align=\"right\"><font size=\"1\"><b>Agosto:");
ShowHTML("             <td><font size=\"1\">".Nvl($w_quantitativo_8,"---")."</td>");
ShowHTML("         <tr><td align=\"right\"><font size=\"1\"><b>Março:");
ShowHTML("             <td><font size=\"1\">".Nvl($w_quantitativo_3,"---")."</td>");
ShowHTML("             <td align=\"right\"><font size=\"1\"><b>Setembro:");
ShowHTML("             <td><font size=\"1\">".Nvl($w_quantitativo_9,"---")."</td>");
ShowHTML("         <tr><td align=\"right\"><font size=\"1\"><b>Abril:");
ShowHTML("             <td><font size=\"1\">".Nvl($w_quantitativo_4,"---")."</td>");
ShowHTML("             <td align=\"right\"><font size=\"1\"><b>Outubro:");
ShowHTML("             <td><font size=\"1\">".Nvl($w_quantitativo_10,"---")."</td>");
ShowHTML("         <tr><td align=\"right\"><font size=\"1\"><b>Maio:");
ShowHTML("             <td><font size=\"1\">".Nvl($w_quantitativo_5,"---")."</td>");
ShowHTML("             <td align=\"right\"><font size=\"1\"><b>Novembro:");
ShowHTML("             <td><font size=\"1\">".Nvl($w_quantitativo_11,"---")."</td>");
ShowHTML("         <tr><td align=\"right\"><font size=\"1\"><b>Junho:");
ShowHTML("             <td><font size=\"1\">".Nvl($w_quantitativo_6,"---")."</td>");
ShowHTML("             <td align=\"right\"><font size=\"1\"><b>Dezembro:");
ShowHTML("             <td><font size=\"1\">".Nvl($w_quantitativo_12,"---")."</td>");
ShowHTML("       </table>");
ShowHTML("     <tr><td><font size=\"1\">Percentual de conlusão:<br><b>".nvl($w_perc_conclusao,0)."%</b></td>");
ShowHTML("     <tr><td valign=\"top\"><font size=\"1\">Situação atual da meta:<b><br>".Nvl($w_situacao_atual,"---")."</td>");
ShowHTML("     <tr><td valign=\"top\"><font size=\"1\">Justificar os motivos casso de não cumprimento da meta:<b><br>".Nvl($w_justificativa_inex,"---")."</td>");
ShowHTML("     <tr><td valign=\"top\"><font size=\"1\">Quais medidas necessárias para o cumprimento da meta:<b><br>".Nvl($w_outras_medidas,"---")."</td>");
}
  else
{

ShowHTML("     <tr><td><font size=\"1\">Percentual de conlusão:<br><b>".nvl($w_perc_conclusao,0)."%</b></td>");
ShowHTML("     <tr><td valign=\"top\" colspan=\"1\">");
ShowHTML("       <table border=0 width=\"100%\" cellspacing=0>");
ShowHTML("         <tr><td>&nbsp<td><font size=\"1\"><br><b>Quantitativo realizado</b></td>");
ShowHTML("             <td>&nbsp<td><font size=\"1\"><br><b>Quantitativo realizado</b></td>");
ShowHTML("         <tr><td width=\"8%\" align=\"right\"><font size=\"1\"><b>Janeiro:");
ShowHTML("             <td width=\"15%\"><font size=\"1\"><INPUT TYPE=\"TEXT\" CLASS=\"STI\" NAME=\"w_quantitativo_1\" SIZE=10 MAXLENGTH=18 VALUE=\"".$w_quantitativo_1."\" ".$w_Disabled."></td>");
ShowHTML("             <td width=\"5%\" align=\"right\"><font size=\"1\"><b>Julho:");
ShowHTML("             <td><font size=\"1\"><INPUT TYPE=\"TEXT\" CLASS=\"STI\" NAME=\"w_quantitativo_7\" SIZE=10 MAXLENGTH=18 VALUE=\"".$w_quantitativo_7."\" ".$w_Disabled."></td>");
ShowHTML("         <tr><td align=\"right\"><font size=\"1\"><b>Fevereiro:");
ShowHTML("             <td><font size=\"1\"><INPUT TYPE=\"TEXT\" CLASS=\"STI\" NAME=\"w_quantitativo_2\" SIZE=10 MAXLENGTH=18 VALUE=\"".$w_quantitativo_2."\" ".$w_Disabled."></td>");
ShowHTML("             <td align=\"right\"><font size=\"1\"><b>Agosto:");
ShowHTML("             <td><font size=\"1\"><INPUT TYPE=\"TEXT\" CLASS=\"STI\" NAME=\"w_quantitativo_8\" SIZE=10 MAXLENGTH=18 VALUE=\"".$w_quantitativo_8."\" ".$w_Disabled."></td>");
ShowHTML("         <tr><td align=\"right\"><font size=\"1\"><b>Março:");
ShowHTML("             <td><font size=\"1\"><INPUT TYPE=\"TEXT\" CLASS=\"STI\" NAME=\"w_quantitativo_3\" SIZE=10 MAXLENGTH=18 VALUE=\"".$w_quantitativo_3."\" ".$w_Disabled."></td>");
ShowHTML("             <td align=\"right\"><font size=\"1\"><b>Setembro:");
ShowHTML("             <td><font size=\"1\"><INPUT TYPE=\"TEXT\" CLASS=\"STI\" NAME=\"w_quantitativo_9\" SIZE=10 MAXLENGTH=18 VALUE=\"".$w_quantitativo_9."\" ".$w_Disabled."></td>");
ShowHTML("         <tr><td align=\"right\"><font size=\"1\"><b>Abril:");
ShowHTML("             <td><font size=\"1\"><INPUT TYPE=\"TEXT\" CLASS=\"STI\" NAME=\"w_quantitativo_4\" SIZE=10 MAXLENGTH=18 VALUE=\"".$w_quantitativo_4."\" ".$w_Disabled."></td>");
ShowHTML("             <td align=\"right\"><font size=\"1\"><b>Outubro:");
ShowHTML("             <td><font size=\"1\"><INPUT TYPE=\"TEXT\" CLASS=\"STI\" NAME=\"w_quantitativo_10\" SIZE=10 MAXLENGTH=18 VALUE=\"".$w_quantitativo_10."\" ".$w_Disabled."></td>");
ShowHTML("         <tr><td align=\"right\"><font size=\"1\"><b>Maio:");
ShowHTML("             <td><font size=\"1\"><INPUT TYPE=\"TEXT\" CLASS=\"STI\" NAME=\"w_quantitativo_5\" SIZE=10 MAXLENGTH=18 VALUE=\"".$w_quantitativo_5."\" ".$w_Disabled."></td>");
ShowHTML("             <td align=\"right\"><font size=\"1\"><b>Novembro:");
ShowHTML("             <td><font size=\"1\"><INPUT TYPE=\"TEXT\" CLASS=\"STI\" NAME=\"w_quantitativo_11\" SIZE=10 MAXLENGTH=18 VALUE=\"".$w_quantitativo_11."\" ".$w_Disabled."></td>");
ShowHTML("         <tr><td align=\"right\"><font size=\"1\"><b>Junho:");
ShowHTML("             <td><font size=\"1\"><INPUT TYPE=\"TEXT\" CLASS=\"STI\" NAME=\"w_quantitativo_6\" SIZE=10 MAXLENGTH=18 VALUE=\"".$w_quantitativo_6."\" ".$w_Disabled."></td>");
ShowHTML("             <td align=\"right\"><font size=\"1\"><b>Dezembro:");
ShowHTML("             <td><font size=\"1\"><INPUT TYPE=\"TEXT\" CLASS=\"STI\" NAME=\"w_quantitativo_12\" SIZE=10 MAXLENGTH=18 VALUE=\"".$w_quantitativo_12."\" ".$w_Disabled." ></td>");
ShowHTML("       </table>");
ShowHTML("     <tr><td valign=\"top\"><font size=\"1\"><b><u>S</u>ituação atual da meta:</b><br><textarea ".$w_Disabled." accesskey=\"S\" name=\"w_situacao_atual\" class=\"STI\" ROWS=5 cols=75 title=\"Descreva a situação em a etapa encontra-se.\">".$w_situacao_atual."</TEXTAREA></td>");
ShowHTML("     <tr valign=\"top\">");
MontaRadioSN("<b>A meta será cumprida?</b>",$w_exequivel,"w_exequivel");
ShowHTML("     </tr>");
ShowHTML("     <tr><td valign=\"top\"><font size=\"1\"><b><u>J</u>ustificar os motivos casso de não cumprimento da meta:</b><br><textarea ".$w_Disabled." accesskey=\"J\" name=\"w_justificativa_inex\" class=\"STI\" ROWS=5 cols=75>".$w_justificativa_inex."</TEXTAREA></td>");
ShowHTML("     <tr><td valign=\"top\"><font size=\"1\"><b><u>Q</u>uais medidas necessárias para o cumprimento da meta?</b><br><textarea ".$w_Disabled." accesskey=\"Q\" name=\"w_outras_medidas\" class=\"STI\" ROWS=5 cols=75>".$w_outras_medidas."</TEXTAREA></td>");
} 

ShowHTML("        <tr><td align=\"center\"><hr>");
if ($w_tipo!="WORD")
{

if ($O=="A")
{

ShowHTML("            <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Atualizar\">");
} 

if ($P1==10)
{

ShowHTML("            <input class=\"STB\" type=\"button\" onClick=\"window.close();\" name=\"Botao\" value=\"Fechar\">");
}
  else
{

ShowHTML("            <input class=\"STB\" type=\"button\" onClick=\"history.back(-1);\" name=\"Botao\" value=\"Voltar\">");
} 

} 

ShowHTML("            </td>");
ShowHTML("        </tr>");
ShowHTML("      </table>");
ShowHTML("    </TD>");
ShowHTML("</tr>");
if ($w_tipo!="WORD")
{

ShowHTML("</FORM>");
} 

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
if ($w_tipo!="WORD")
{

Rodape();
} 

$RS1=null;

$RS2=null;

$RS3=null;

$RS4=null;

$w_inicio=null;

$w_fim=null;

$w_perc_conclusao=null;

$w_orcamento=null;

$w_sq_pessoa=null;

$w_sq_unidade=null;

$w_vincula_atividade=null;

$w_ultima_atualizacao=null;

$w_sq_pessoa_atualizacao=null;

$w_situacao_atual=null;

$w_fase=null;

$w_p2=null;


$w_chave=null;

$w_chave_pai=null;

$w_chave_aux=null;

$w_titulo=null;

$w_ordem=null;

$w_descricao=null;


$w_troca=null;

$i=null;

$w_texto=null;

return $function_ret;
} 

// =========================================================================

// Rotina de recursos da ação

// -------------------------------------------------------------------------

function Recursos()
{
  extract($GLOBALS);




$w_Chave=${"w_Chave"};
$w_Chave_pai=${"w_Chave_pai"};
$w_chave_aux=${"w_chave_aux"};
$w_troca=${"w_troca"};

if ($w_troca>"")
{
// Se for recarga da página

$w_nome=${"w_nome"};
$w_tipo=${"w_tipo"};
$w_descricao=${"w_descricao"};
$w_finalidade=${"w_finalidade"};
}
  else
if ($O=="L")
{

// Recupera todos os registros para a listagem

$DB_GetSolicRecurso$RS$w_chave$null//LISTA"$RS->Sort="TIPO, NOME";
}
  else
if ((strpos("AEV",$O) ? strpos("AEV",$O)+1 : 0)>0 && $w_Troca=="")
{

// Recupera os dados do endereço informado

$DB_GetSolicRecurso$RS$w_chave$w_chave_aux//REGISTRO"$w_nome=$RS["nome"];
$w_tipo=$RS["tipo"];
$w_descricao=$RS["descricao"];
$w_finalidade=$RS["finalidade"];
DesconectaBD();
} 


Cabecalho();
ShowHTML("<HEAD>");
if ((strpos("IAEP",$O) ? strpos("IAEP",$O)+1 : 0)>0)
{

ScriptOpen("JavaScript");
ValidateOpen("Validacao");
if ((strpos("IA",$O) ? strpos("IA",$O)+1 : 0)>0)
{

Validate("w_nome","Nome","","1","2","100","1","1");
Validate("w_tipo","Tipo do recurso","SELECT","1","1","10","","1");
Validate("w_descricao","Descricao","","","2","2000","1","1");
Validate("w_finalidade","Finalidade","","","2","2000","1","1");
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
if ($O=="I" || $O=="A")
{

BodyOpen("onLoad='document.Form.w_nome.focus()';");
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

ShowHTML("<tr><td><font size=\"2\"><a accesskey=\"I\" class=\"SS\" href=\"".$w_dir.$w_pagina.$par."&R=".$w_Pagina.$par."&O=I&w_chave=".$w_chave."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\"><u>I</u>ncluir</a>&nbsp;");
ShowHTML("    <td align=\"right\"><font size=\"1\"><b>Registros existentes: ".$RS->RecordCount);
ShowHTML("<tr><td align=\"center\" colspan=3>");
ShowHTML("    <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">");
ShowHTML("        <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">");
ShowHTML("          <td><font size=\"1\"><b>Tipo</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Nome</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Finalidade</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Operações</font></td>");
ShowHTML("        </tr>");

if ($RS->EOF)
{
// Se não foram selecionados registros, exibe mensagem

ShowHTML("      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=7 align=\"center\"><font size=\"2\"><b>Não foram encontrados registros.</b></td></tr>");
}
  else
{

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
ShowHTML("        <td><font size=\"1\">".RetornaTipoRecurso($RS["tipo"])."</td>");
ShowHTML("        <td><font size=\"1\">".$RS["nome"]."</td>");
ShowHTML("        <td><font size=\"1\">".CRLF2BR(Nvl($RS["finalidade"],"---"))."</td>");
ShowHTML("        <td align=\"top\" nowrap><font size=\"1\">");
ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina.$par."&R=".$w_Pagina.$par."&O=A&w_chave=".$w_chave."&w_chave_aux=".$Rs["sq_projeto_recurso"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\">Alterar</A>&nbsp");
ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."GRAVA&R=".$w_pagina.$par."&O=E&w_chave=".$w_chave."&w_chave_aux=".$Rs["sq_projeto_recurso"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\" onClick=\"return confirm('Confirma a exclusão do registro?');\">Excluir</A>&nbsp");
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
ShowHTML("<INPUT type=\"hidden\" name=\"w_chave_aux\" value=\"".$w_chave_aux."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_troca\" value=\"\">");

ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">");
ShowHTML("    <table width=\"97%\" border=\"0\">");
ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b><u>N</u>ome:</b><br><input ".$w_Disabled." accesskey=\"N\" type=\"text\" name=\"w_nome\" class=\"STI\" SIZE=\"90\" MAXLENGTH=\"100\" VALUE=\"".$w_nome."\" title=\"Informe o nome do recurso.\"></td>");
ShowHTML("      <tr>");
SelecaoTipoRecurso("<u>T</u>ipo:","T","Selecione o tipo deste recurso.",$w_tipo,null,"w_tipo",null,null);
ShowHTML("      </tr>");
ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b><u>D</u>escrição:</b><br><textarea ".$w_Disabled." accesskey=\"D\" name=\"w_descricao\" class=\"STI\" ROWS=5 cols=75 title=\"Descreva, se necessário, características deste recurso (conhecimentos, habilidades, perfil, capacidade etc).\">".$w_descricao."</TEXTAREA></td>");
ShowHTML("      <tr>");
ShowHTML("      </tr>");
ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b><u>F</u>inalidade:</b><br><textarea ".$w_Disabled." accesskey=\"F\" name=\"w_finalidade\" class=\"STI\" ROWS=5 cols=75 title=\"Descreva, se necessário, a finalidade deste recurso para a ação (funções desempenhadas, papel, objetivos etc).\">".$w_finalidade."</TEXTAREA></td>");
ShowHTML("      <tr>");
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

ShowHTML("            <input class=\"STB\" type=\"button\" onClick=\"location.href='".$w_Pagina.$par."&w_chave=".$w_chave."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."&O=L';\" name=\"Botao\" value=\"Cancelar\">");
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

$w_nome=null;

$w_tipo=null;

$w_descricao=null;

$w_finalidade=null;


$w_chave=null;

$w_chave_pai=null;

$w_chave_aux=null;


$w_troca=null;

$i=null;

$w_texto=null;

return $function_ret;
} 

// =========================================================================

// Rotina de alteração dos recursos da etapa

// -------------------------------------------------------------------------

function EtapaRecursos()
{
  extract($GLOBALS);




$w_troca=${"w_troca"};
$w_chave=${"w_chave"};
$w_chave_aux=${"w_chave_aux"};
$w_chave_pai=${"w_chave_pai"};

DB_GetSolicEtpRec($RS,$w_chave_aux,null,null);
$RS->Sort="tipo, nome";
Cabecalho();
ShowHTML("<HEAD>");
ScriptOpen("JavaScript");
ValidateOpen("Validacao");
//ShowHTML "  for (i = 0; i < theForm.w_recurso.length; i++) {"

//ShowHTML "      if (theForm.w_recurso[i].checked) break;"

//ShowHTML "      if (i == theForm.w_recurso.length-1) {"

//ShowHTML "         alert('Você deve selecionar pelo menos um recurso!');"//ShowHTML "         return false;"

//ShowHTML "      }"

//ShowHTML "  }"

ShowHTML("  theForm.Botao[0].disabled=true;");
ShowHTML("  theForm.Botao[1].disabled=true;");
ValidateClose();
ScriptClose();
ShowHTML("</HEAD>");
ShowHTML("<BASE HREF=\"".$conRootSIW."\">");
BodyOpen("onLoad=document.focus();");
ShowHTML("<B><FONT COLOR=\"#000000\">".$w_TP."</FONT></B>");
ShowHTML("<HR>");
ShowHTML("<div align=center><center>");
ShowHTML("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">");
ShowHTML("<tr><td align=\"center\" bgcolor=\"#FAEBD7\"><table border=1 width=\"100%\"><tr><td>");
ShowHTML("    <TABLE WIDTH=\"100%\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">");
ShowHTML("        <tr valign=\"top\">");
ShowHTML("          <td><font size=\"1\">Etapa:<br><b>".MontaOrdemEtapa($w_chave_aux)." - ".$RS["titulo"]."</font></td>");
ShowHTML("          <td><font size=\"1\">Início:<br> <b>".FormataDataEdicao($RS["inicio_previsto"])."</font></td>");
ShowHTML("          <td><font size=\"1\">Término:<br><b>".FormataDataEdicao($RS["fim_previsto"])."</font></td>");
ShowHTML("        <tr colspan=3><td><font size=\"1\">Descrição:<br><b>".CRLF2BR($RS["descricao"])."</font></td></tr>");
ShowHTML("    </TABLE>");
ShowHTML("</table>");
ShowHTML("<tr><td align=\"right\"><font size=\"1\">&nbsp;");
AbreForm("Form",$w_dir.$w_pagina."Grava","POST","return(Validacao(this));",null,$P1,$P2,$P3,$P4,$TP,"ETAPAREC",$R,$O);
ShowHTML("<INPUT type=\"hidden\" name=\"w_chave\" value=\"".$w_chave."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_chave_aux\" value=\"".$w_chave_aux."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_sg\" value=\"".${"w_sg"}."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_recurso\" value=\"\">");
ShowHTML("<tr><td><font size=\"1\"><ul><b>Informações:</b><li>Indique abaixo quais recursos estarão alocados a esta etapa da ação.<li>A princípio, uma etapa não tem nenhum recurso alocado.<li>Para remover um recurso, desmarque o quadrado ao seu lado.</ul>");
ShowHTML("<tr><td align=\"center\" colspan=3>");
ShowHTML("    <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">");
ShowHTML("        <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">");
ShowHTML("          <td><font size=\"1\"><b>&nbsp;</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Tipo</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Recurso</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Finalidade</font></td>");
ShowHTML("        </tr>");
if ($RS->EOF)
{

ShowHTML("      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=6 align=\"center\"><font  size=\"2\"><b>Não foram encontrados registros.</b></td></tr>");
}
  else
{

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
if ($cDbl[Nvl($RS["existe"],0)]>0)
{

ShowHTML("        <td align=\"center\"><font  size=\"1\"><input type=\"checkbox\" name=\"w_recurso\" value=\"".$RS["sq_projeto_recurso"]."\" checked></td>");
}
  else
{

ShowHTML("        <td align=\"center\"><font  size=\"1\"><input type=\"checkbox\" name=\"w_recurso\" value=\"".$RS["sq_projeto_recurso"]."\"></td>");
} 

ShowHTML("        <td align=\"left\"><font  size=\"1\">".RetornaTipoRecurso($RS["tipo"])."</td>");
ShowHTML("        <td align=\"left\"><font  size=\"1\">".$RS["nome"]."</td>");
ShowHTML("        <td align=\"left\"><font  size=\"1\">".CRLF2BR(Nvl($RS["finalidade"],"---"))."</td>");
ShowHTML("      </tr>");
$RS->MoveNext;
} 
} 

ShowHTML("      </center>");
ShowHTML("    </table>");
ShowHTML("  </td>");
ShowHTML("</tr>");
DesConectaBD();
ShowHTML("      <tr><td align=\"center\"><font size=1>&nbsp;");
ShowHTML("      <tr><td align=\"center\" height=\"1\" bgcolor=\"#000000\">");
ShowHTML("      <tr><td align=\"center\">");
ShowHTML("            <input class=\"STB\" type=\"submit\" name=\"Botao\" value=\"Gravar\">");
ShowHTML("            <input class=\"STB\" type=\"button\" onClick=\"history.back(1);\" name=\"Botao\" value=\"Cancelar\">");
ShowHTML("          </td>");
ShowHTML("      </tr>");
ShowHTML("</table>");
ShowHTML("</center>");
ShowHTML("</FORM>");
Rodape();

$w_chave=null;

$w_chave_pai=null;

$w_chave_aux=null;

$w_troca=null;

$w_texto=null;

$w_cont=null;

$w_contaux=null;


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

$DB_GetSolicInter$RS$w_chave$null//LISTA"$RS->Sort="nome_resumido";
}
  else
if ((strpos("AEV",$O) ? strpos("AEV",$O)+1 : 0)>0 && $w_Troca=="")
{

// Recupera os dados do endereço informado

$DB_GetSolicInter$RS$w_chave$w_chave_aux//REGISTRO"$w_nome=$RS["nome_resumido"];
$w_tipo_visao=$RS["tipo_visao"];
$w_envia_email=$RS["envia_email"];
DesconectaBD();
} 


Cabecalho();
ShowHTML("<HEAD>");
if ((strpos("IAEP",$O) ? strpos("IAEP",$O)+1 : 0)>0)
{

$ScriptOpen"JavaScript";
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
ShowHTML("      <tr><td colspan=3><font size=1>Usuários que terão acesso à visualização dos dados desta ação.</font></td></tr>");
ShowHTML("      <tr><td colspan=3 align=\"center\" height=\"1\" bgcolor=\"#000000\"></td></tr>");
// Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem

if ($P1!=4)
{

ShowHTML("<tr><td><font size=\"2\"><a accesskey=\"I\" class=\"SS\" href=\"".$w_dir.$w_pagina.$par."&R=".$w_Pagina.$par."&O=I&w_chave=".$w_chave."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\"><u>I</u>ncluir</a>&nbsp;");
}
  else
{

$DB_GetSolicData$RS1$w_chave//ORVISUAL"ShowHTML("<tr><td colspan=3 align=\"center\" bgcolor=\"#FAEBD7\"><table border=1 width=\"100%\"><tr><td>");
ShowHTML("    <TABLE WIDTH=\"100%\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">");
ShowHTML("        <tr valign=\"top\">");
if ($RS1["sq_acao_ppa"]>"")
{

ShowHTML("          <td><font size=\"1\"><b>Ação PPA: </b><br>".$RS1["nm_ppa"]." (".$RS1["cd_ppa"].".".$RS1["cd_ppa_pai"].")</b>");
} 

if ($RS1["sq_orprioridade"]>"")
{

ShowHTML("        <td><font size=\"1\"><b>Iniciativa prioritária: </b><br>".$RS1["nm_pri"]." </b>");
} 

ShowHTML("    </TABLE>");
ShowHTML("</table>");
ShowHTML("<tr><td colspan=3>&nbsp;");
ShowHTML("<tr><td colspan=2><font size=\"2\"><a accesskey=\"F\" class=\"SS\" href=\"javascript:window.close();\"><u>F</u>echar</a>&nbsp;");
} 

ShowHTML("    <td align=\"right\"><font size=\"1\"><b>Registros existentes: ".$RS->RecordCount);
ShowHTML("<tr><td align=\"center\" colspan=3>");
ShowHTML("    <TABLE WIDTH=\"100%\" bgcolor=\"".$conTableBgColor."\" BORDER=\"".$conTableBorder."\" CELLSPACING=\"".$conTableCellSpacing."\" CELLPADDING=\"".$conTableCellPadding."\" BorderColorDark=\"".$conTableBorderColorDark."\" BorderColorLight=\"".$conTableBorderColorLight."\">");
ShowHTML("        <tr bgcolor=\"".$conTrBgColor."\" align=\"center\">");
ShowHTML("          <td><font size=\"1\"><b>Pessoa</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Visao</font></td>");
ShowHTML("          <td><font size=\"1\"><b>Envia e-mail</font></td>");
if ($P1!=4)
{

ShowHTML("          <td><font size=\"1\"><b>Operações</font></td>");
} 

ShowHTML("        </tr>");
if ($RS->EOF)
{
// Se não foram selecionados registros, exibe mensagem

ShowHTML("      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=7 align=\"center\"><font size=\"2\"><b>Não foram encontrados registros.</b></td></tr>");
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
ShowHTML("        <td><font size=\"1\">".ExibePessoa("../",$w_cliente,$RS["sq_pessoa"],$TP,$RS["nome"]." (".$RS["lotacao"].")")."</td>");
ShowHTML("        <td><font size=\"1\">".RetornaTipoVisao($RS["tipo_visao"])."</td>");
ShowHTML("        <td align=\"center\"><font size=\"1\">".str_replace("N","Não",str_replace("S","Sim",$RS["envia_email"]))."</td>");
if ($P1!=4)
{

ShowHTML("        <td align=\"top\" nowrap><font size=\"1\">");
ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina.$par."&R=".$w_Pagina.$par."&O=A&w_chave=".$w_chave."&w_chave_aux=".$Rs["sq_pessoa"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\">Alterar</A>&nbsp");
ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."GRAVA&R=".$w_pagina.$par."&O=E&w_chave=".$w_chave."&w_chave_aux=".$Rs["sq_pessoa"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\" onClick=\"return confirm('Confirma a exclusão do registro?');\">Excluir</A>&nbsp");
ShowHTML("        </td>");
} 

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

SelecaoTipoVisao("<u>T</u>ipo de visão:","T","Selecione o tipo de visão que o interessado terá desta ação.",$w_tipo_visao,null,"w_tipo_visao",null,null);
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

ShowHTML("            <input class=\"STB\" type=\"button\" onClick=\"location.href='".$w_Pagina.$par."&w_chave=".$w_chave."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."&O=L';\" name=\"Botao\" value=\"Cancelar\">");
ShowHTML("          </td>");
ShowHTML("      </tr>");
ShowHTML("    </table>");
ShowHTML("    </TD>");
ShowHTML("</tr>");
ShowHTML("</FORM>");
}
  else
{

$ScriptOpen"JavaScript";
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

$ScriptOpen"JavaScript";
$modulo;
$checkbranco;
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

ShowHTML("<tr><td><font size=\"2\"><a accesskey=\"I\" class=\"SS\" href=\"".$w_dir.$w_pagina.$par."&R=".$w_Pagina.$par."&O=I&w_chave=".$w_chave."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\"><u>I</u>ncluir</a>&nbsp;");
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

ShowHTML("      <tr bgcolor=\"".$conTrBgColor."\"><td colspan=7 align=\"center\"><font size=\"2\"><b>Não foram encontrados registros.</b></td></tr>");
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
ShowHTML("          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina.$par."&R=".$w_Pagina.$par."&O=A&w_chave=".$w_chave."&w_chave_aux=".$Rs["sq_unidade"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\">Alterar</A>&nbsp");
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

ShowHTML("      <tr><td valign=\"top\"><font size=\"1\"><b><u>P</u>apel desempenhado:</b><br><textarea ".$w_Disabled." accesskey=\"P\" name=\"w_papel\" class=\"STI\" ROWS=5 cols=75 title=\"Descreva o papel desempenhado pela área ou instituição na execução da ação.\">".$w_papel."</TEXTAREA></td>");
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

ShowHTML("            <input class=\"STB\" type=\"button\" onClick=\"location.href='".$w_Pagina.$par."&w_chave=".$w_chave."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."&O=L';\" name=\"Botao\" value=\"Cancelar\">");
ShowHTML("          </td>");
ShowHTML("      </tr>");
ShowHTML("    </table>");
ShowHTML("    </TD>");
ShowHTML("</tr>");
ShowHTML("</FORM>");
}
  else
{

$ScriptOpen"JavaScript";
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

Cabecalho();
} 


ShowHTML("<HEAD>");
ShowHTML("<TITLE>".$conSgSistema." - Visualização de Ação</TITLE>");
ShowHTML("</HEAD>");
ShowHTML("<BASE HREF=\"".$conRootSIW."\">");
if ($w_tipo!="WORD")
{

BodyOpenClean("onLoad='document.focus()'; ");
} 

ShowHTML("<TABLE WIDTH=\"100%\" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=\"LEFT\" src=\"".LinkArquivo(null,$w_cliente,$w_logo,null,null,null,"EMBED")."\"><TD ALIGN=\"RIGHT\"><B><FONT SIZE=4 COLOR=\"#000000\">");
if ($P1==1)
{

ShowHTML("Iniciativas Prioritárias do Governo <BR> Relatório Geral por Ação");
}
  else
if ($P1==2)
{

ShowHTML("Plano Plurianual 2004 - 2007 <BR> Relatório Geral por Ação");
}
  else
{

ShowHTML("Visualização de Ação");
} 

ShowHTML("</FONT><TR><TD ALIGN=\"RIGHT\"><B><font size=1 COLOR=\"#000000\">".DataHora()."</B>");
if ($w_tipo!="WORD")
{

ShowHTML("&nbsp;&nbsp;<IMG ALIGN=\"CENTER\" TITLE=\"Imprimir\" SRC=\"images/impressora.jpg\" onClick=\"window.print();\">");
ShowHTML("&nbsp;&nbsp;<IMG ALIGN=\"CENTER\" TITLE=\"Gerar word\" SRC=\"images/word.gif\" onClick=\"window.open('".$w_pagina."Visual&R=".$w_pagina.$par."&O=L&w_chave=".$w_chave."&w_tipo=word&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=1&TP=".$TP."&SG=".$SG.MontaFiltro("GET")."','VisualAcaoWord','menubar=yes resizable=yes scrollbars=yes');\">");
} 

ShowHTML("</TD></TR>");
ShowHTML("</FONT></B></TD></TR></TABLE>");
ShowHTML("<HR>");
if ($w_tipo>"" && $w_tipo!="WORD")
{

ShowHTML("<center><B><font size=1>Clique <a class=\"HL\" href=\"javascript:history.back(1);\">aqui</a> para voltar à tela anterior</font></b></center>");
} 


// Chama a rotina de visualização dos dados da ação, na opção "Listagem"

ShowHTML(VisualProjeto($w_chave,"L",$w_usuario,$P1,$P4));


if ($w_tipo>"" && $w_tipo!="WORD")
{

ShowHTML("<center><B><font size=1>Clique <a class=\"HL\" href=\"javascript:history.back(1);\">aqui</a> para voltar à tela anterior</font></b></center>");
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

$ScriptOpen"JavaScript";
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

// Chama a rotina de visualização dos dados da ação, na opção "Listagem"

ShowHTML(VisualProjeto($w_chave,"V",$w_usuario,$P1,$P4));

ShowHTML("<HR>");
AbreForm("Form",$w_dir.$w_pagina."Grava","POST","return(Validacao(this));",null,$P1,$P2,$P3,$P4,$TP,"ORGERAL",$R,$O);
ShowHTML("<INPUT type=\"hidden\" name=\"w_chave\" value=\"".$w_chave."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_troca\" value=\"\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_menu\" value=\"".$w_menu."\">");
$DB_GetSolicData$RS$w_chave//ORGERAL"ShowHTML("<INPUT type=\"hidden\" name=\"w_tramite\" value=\"".$RS["sq_siw_tramite"]."\">");
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

$ScriptOpen"JavaScript";
$ProgressBar"/siw/"$UploadID;
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

$ScriptOpen"JavaScript";
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

$DB_GetSolicData$RS$w_chave//ORGERAL"$w_tramite=$RS["sq_siw_tramite"];
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

$ScriptOpen"JavaScript";
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

// Chama a rotina de visualização dos dados da ação, na opção "Listagem"

ShowHTML(VisualProjeto($w_chave,"V",$w_usuario,$P1,$P4));

ShowHTML("<HR>");
AbreForm("Form",$w_dir.$w_pagina."Grava","POST","return(Validacao(this));",null,$P1,$P2,$P3,$P4,$TP,"ORENVIO",$R,$O);
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

SelecaoFase("<u>F</u>ase da ação:","F","Se deseja alterar a fase atual da ação, selecione a fase para a qual deseja enviá-la.",$w_novo_tramite,$w_menu,"w_novo_tramite",null,"onChange=\"document.Form.action='".$w_dir.$w_pagina.$par."'; document.Form.O.value='".$O."'; document.Form.w_troca.value='w_destinatario'; document.Form.submit();\"");
// Se for envio para o cadastramento, exibe apenas as pessoas autorizadas a fazê-lo.

if ($w_sg_tramite=="CI")
{

SelecaoSolicResp("<u>D</u>estinatário:","D","Selecione, na relação, um destinatário para a ação.",$w_destinatario,$w_chave,$w_novo_tramite,$w_novo_tramite,"w_destinatario","CADASTRAMENTO");
}
  else
{

SelecaoPessoa("<u>D</u>estinatário:","D","Selecione, na relação, um destinatário para a ação.",$w_destinatario,null,"w_destinatario","USUARIOS");
} 

}
  else
{

SelecaoFase("<u>F</u>ase da ação:","F","Se deseja alterar a fase atual da ação, selecione a fase para a qual deseja enviá-la.",$w_novo_tramite,$w_menu,"w_novo_tramite",null,null);
SelecaoPessoa("<u>D</u>estinatário:","D","Selecione, na relação, um destinatário para a ação.",$w_destinatario,null,"w_destinatario","USUARIOS");
} 

ShowHTML("    <tr><td valign=\"top\" colspan=2><font size=\"1\"><b>D<u>e</u>spacho:</b><br><textarea ".$w_Disabled." accesskey=\"E\" name=\"w_despacho\" class=\"STI\" ROWS=5 cols=75 title=\"Descreva o papel desempenhado pela área ou instituição na execução da ação.\">".$w_despacho."</TEXTAREA></td>");
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

$w_tramite=null;

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
if ((strpos("V",O()) ? strpos("V",O())+1 : 0)>0)
{

$ScriptOpen"JavaScript";
ValidateOpen("Validacao");
Validate("w_observacao","Anotação","","1","1","2000","1","1");
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

BodyOpen("onLoad='document.Form.w_observacao.focus()';");
} 

ShowHTML("<B><FONT COLOR=\"#000000\">".$w_TP."</FONT></B>");
ShowHTML("<HR>");
ShowHTML("<div align=center><center>");
ShowHTML("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">");

// Chama a rotina de visualização dos dados da ação, na opção "Listagem"

ShowHTML(VisualProjeto($w_chave,"V",$w_usuario,$P1,$P4));

ShowHTML("<HR>");
AbreForm("Form",$w_dir.$w_pagina."Grava","POST","return(Validacao(this));",null,$P1,$P2,$P3,$P4,$TP,"ORENVIO",$R,O());
ShowHTML($MontaFiltro["POST"]);
ShowHTML("<INPUT type=\"hidden\" name=\"w_chave\" value=\"".$w_chave."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_troca\" value=\"\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_menu\" value=\"".$w_menu."\">");
$DB_GetSolicData$RS$w_chave//ORGERAL"ShowHTML("<INPUT type=\"hidden\" name=\"w_tramite\" value=\"".$RS["sq_siw_tramite"]."\">");
DesconectaBD();

ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">");
ShowHTML("  <table width=\"97%\" border=\"0\">");
ShowHTML("    <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0><tr valign=\"top\">");
ShowHTML("    <tr><td valign=\"top\"><font size=\"1\"><b>A<u>n</u>otação:</b><br><textarea ".$w_Disabled." accesskey=\"N\" name=\"w_observacao\" class=\"STI\" ROWS=5 cols=75 title=\"Redija a anotação desejada.\">".$w_observacao."</TEXTAREA></td>");
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
if ((strpos("V",O()) ? strpos("V",O())+1 : 0)>0)
{

$ScriptOpen"JavaScript";
$CheckBranco;
FormataData();
FormataDataHora();
FormataValor();
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
Validate("w_custo_real","Recurso executado","VALOR","1",4,18,"","0123456789.,");
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

// Chama a rotina de visualização dos dados da ação, na opção "Listagem"

ShowHTML(VisualProjeto($w_chave,"V",$w_usuario,$P1,$P4));

ShowHTML("<HR>");
ShowHTML("<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">");
ShowHTML("  <table width=\"97%\" border=\"0\">");
ShowHTML("      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>");
ShowHTML("          <tr>");

// Verifica se a ação tem etapas em aberto e avisa o usuário caso isso ocorra.

$DB_GetSolicEtapa$RS$w_chave$null//LISTA", null$w_cont=0;
while(!$RS->EOF)
{

if ($cDbl[$RS["perc_conclusao"]]!=100)
{

$w_cont=$w_cont+1;
} 

$RS->MoveNext;
} 
if ($w_cont>0)
{

$ScriptOpen"JavaScript";
ShowHTML("  alert('ATENÇÃO: das ".$RS->RecordCount." etapas desta ação, ".$w_cont." não têm 100% de conclusão!\n\nAinda assim você poderá concluir esta ação.');");
ScriptClose();
} 

DesconectaBD();

AbreForm("Form",$w_dir.$w_pagina."Grava","POST","return(Validacao(this));",null,$P1,$P2,$P3,$P4,$TP,"ORCONC",$R,O());
ShowHTML($MontaFiltro["POST"]);
ShowHTML("<INPUT type=\"hidden\" name=\"w_chave\" value=\"".$w_chave."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_troca\" value=\"\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_menu\" value=\"".$w_menu."\">");
ShowHTML("<INPUT type=\"hidden\" name=\"w_concluida\" value=\"S\">");
$DB_GetSolicData$RS$w_chave//ORGERAL"ShowHTML("<INPUT type=\"hidden\" name=\"w_tramite\" value=\"".$RS["sq_siw_tramite"]."\">");
DesconectaBD();
switch ($RS_menu["data_hora"])
{
case 1:
ShowHTML("              <td valign=\"top\"><font size=\"1\"><b><u>T</u>érmino da execução:</b><br><input ".$w_Disabled." accesskey=\"T\" type=\"text\" name=\"w_fim_real\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$w_fim_real."\" onKeyDown=\"FormataData(this,event);\" title=\"Informe a data de término da execução da ação.\"></td>");
break;
case 2:
ShowHTML("              <td valign=\"top\"><font size=\"1\"><b><u>T</u>érmino da execução:</b><br><input ".$w_Disabled." accesskey=\"T\" type=\"text\" name=\"w_fim_real\" class=\"STI\" SIZE=\"17\" MAXLENGTH=\"17\" VALUE=\"".$w_fim_real."\" onKeyDown=\"FormataDataHora(this,event);\" title=\"Informe a data/hora de término da execução da ação.\"></td>");
break;
case 3:
ShowHTML("              <td valign=\"top\"><font size=\"1\"><b>Iní<u>c</u>io da execução:</b><br><input ".$w_Disabled." accesskey=\"C\" type=\"text\" name=\"w_inicio_real\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$w_inicio_real."\" onKeyDown=\"FormataData(this,event);\" title=\"Informe a data/hora de início da execução da ação.\"></td>");
ShowHTML("              <td valign=\"top\"><font size=\"1\"><b><u>T</u>érmino da execução:</b><br><input ".$w_Disabled." accesskey=\"T\" type=\"text\" name=\"w_fim_real\" class=\"STI\" SIZE=\"10\" MAXLENGTH=\"10\" VALUE=\"".$w_fim_real."\" onKeyDown=\"FormataData(this,event);\" title=\"Informe a data de término da execução da ação.\"></td>");
break;
case 4:
ShowHTML("              <td valign=\"top\"><font size=\"1\"><b>Iní<u>c</u>io da execução:</b><br><input ".$w_Disabled." accesskey=\"C\" type=\"text\" name=\"w_inicio_real\" class=\"STI\" SIZE=\"17\" MAXLENGTH=\"17\" VALUE=\"".$w_inicio_real."\" onKeyDown=\"FormataDataHora(this,event);\" title=\"Informe a data/hora de início da execução da ação.\"></td>");
ShowHTML("              <td valign=\"top\"><font size=\"1\"><b><u>T</u>érmino da execução:</b><br><input ".$w_Disabled." accesskey=\"T\" type=\"text\" name=\"w_fim_real\" class=\"STI\" SIZE=\"17\" MAXLENGTH=\"17\" VALUE=\"".$w_fim_real."\" onKeyDown=\"FormataDataHora(this,event);\" title=\"Informe a data de término da execução da ação.\"></td>");
break;
} 
ShowHTML("              <td valign=\"top\"><font size=\"1\"><b><u>R</u>ecurso executado:</b><br><input ".$w_Disabled." accesskey=\"O\" type=\"text\" name=\"w_custo_real\" class=\"STI\" SIZE=\"18\" MAXLENGTH=\"18\" VALUE=\"".$w_custo_real."\" onKeyDown=\"FormataValor(this,18,2,event);\" title=\"Informe o recurso utilizado para execução da ação, ou zero se não for o caso.\"></td>");
ShowHTML("          </table>");
ShowHTML("    <tr><td valign=\"top\"><font size=\"1\"><b>Nota d<u>e</u> conclusão:</b><br><textarea ".$w_Disabled." accesskey=\"E\" name=\"w_nota_conclusao\" class=\"STI\" ROWS=5 cols=75 title=\"Descreva o quanto a ação atendeu aos resultados esperados.\">".$w_nota_conclusao."</TEXTAREA></td>");
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

// Gera uma linha de apresentação da tabela de etapas

// -------------------------------------------------------------------------

function EtapaLinha($p_chave,$p_chave_aux,$p_titulo,$p_resp,$p_setor,$p_inicio,$p_fim,$p_perc,$p_word,$p_destaque,$p_oper,$p_tipo)
{
  extract($GLOBALS);


$l_recurso="";

DB_GetSolicEtpRec($RSQuery,$p_chave_aux,null,"EXISTE");
if (!$RSQuery->EOF)
{

$l_recurso=$l_recurso."\r\n"."      <tr bgcolor=w_cor valign=\"top\"><td colspan=3><table border=0 width=\"100%\"><tr><td><font size=\"1\">Recurso(s): ";
while(!$RsQuery->EOF)
{

$l_recurso=$l_recurso."\r\n".$RSQuery["nome"]."; ";
$RSQuery->MoveNext;
} 
$l_recurso=$l_recurso."\r\n"."      </tr></td></table></td></tr>";
} 

$RSQuery->Close();

if ($l_recurso>"")
{
$l_row="rowspan=2";
}
  else
{
$l_row="";
}
;
} 

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
$l_html=$l_html."\r\n"."      <tr bgcolor=\"".$w_cor."\" valign=\"top\">";
$l_html=$l_html."\r\n"."        <td nowrap ".$l_row."><font size=\"1\">";
if ($p_fim<time()() && $cDbl[$p_perc]<100)
{

$l_html=$l_html."\r\n"."           <img src=\"".$conImgAtraso."\" border=0 width=15 height=15 align=\"center\">";
}
  else
if ($cDbl[$p_perc]<100)
{

$l_html=$l_html."\r\n"."           <img src=\"".$conImgNormal."\" border=0 width=15 height=15 align=\"center\">";
}
  else
{

$l_html=$l_html."\r\n"."           <img src=\"".$conImgOkNormal."\" border=0 width=15 height=15 align=\"center\">";
} 

if ($cDbl[$p_word]==1)
{

$l_html=$l_html."\r\n"."        <td><font size=\"1\">".$p_destaque.$p_titulo."</b>";
}
  else
{

$l_html=$l_html."\r\n"."<A class=\"HL\" HREF=\"#\" onClick=\"window.open('Projeto.asp?par=AtualizaEtapa&O=V&w_chave=".$RS["sq_siw_solicitacao"]."&w_chave_aux=".$p_chave_aux."&w_tipo=Volta&P1=10&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."','Meta','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;\" title=\"Clique para exibir os dados!\">".$p_destaque.$p_titulo."</A>";
} 

$l_html=$l_html."\r\n"."        <td align=\"center\" ".$l_row."><font size=\"1\">".FormataDataEdicao($p_fim)."</td>";
$l_html=$l_html."\r\n"."        <td nowrap align=\"right\" ".$l_row."><font size=\"1\">".$p_perc." %</td>";
if ($p_oper=="S")
{

$l_html=$l_html."\r\n"."        <td align=\"top\" nowrap ".$l_row."><font size=\"1\">";
// Se for listagem de etapas no cadastramento da ação, exibe operações de alteração, exclusão e recursos

if ($p_tipo=="PROJETO")
{

$l_html=$l_html."\r\n"."          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina.$par."&R=".$w_Pagina.$par."&O=A&w_chave=".$p_chave."&w_chave_aux=".$p_chave_aux."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\" title=\"Alterar\">Alt</A>&nbsp";
$l_html=$l_html."\r\n"."          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina."GRAVA&R=".$w_pagina.$par."&O=E&w_chave=".$p_chave."&w_chave_aux=".$p_chave_aux."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\" onClick=\"return confirm('Confirma a exclusão do registro?');\" title=\"Excluir\">Excl</A>&nbsp";
// Caso contrário, é listagem de atualização de etapas. Neste caso, coloca apenas a opção de alteração

}
  else
{

$l_html=$l_html."\r\n"."          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina.$par."&R=".$w_Pagina.$par."&O=A&w_chave=".$p_chave."&w_chave_aux=".$p_chave_aux."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\" title=\"Atualiza dados da etapa\">Atualizar</A>&nbsp";
} 

$l_html=$l_html."\r\n"."        </td>";
}
  else
{

if ($p_tipo=="ETAPA")
{

$l_html=$l_html."\r\n"."        <td align=\"top\" nowrap ".$l_row."><font size=\"1\">";
$l_html=$l_html."\r\n"."          <A class=\"HL\" HREF=\"".$w_dir.$w_pagina.$par."&R=".$w_Pagina.$par."&O=V&w_chave=".$p_chave."&w_chave_aux=".$p_chave_aux."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."\" title=\"Atualiza dados da etapa\">Exibir</A>&nbsp";
$l_html=$l_html."\r\n"."        </td>";
} 

} 

$l_html=$l_html."\r\n"."      </tr>";
if ($l_recurso>"")
{
$l_html=$l_html."\r\n".str_replace("w_cor",$w_cor,$l_recurso);
}
;
} 
$EtapaLinha=$l_html;

$RsQuery=null;

$l_row=null;

$l_recurso=null;

$l_html=null;

return $function_ret;
} 

// =========================================================================

// Rotina de preparação para envio de e-mail relativo a projetos

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

$w_html=$w_html."      <tr valign=\"top\"><td align=\"center\"><font size=2><b>INCLUSÃO DE AÇÃO</b></font><br><br><td></tr>"."\r\n";
}
  else
if ($p_tipo==2)
{

$w_html=$w_html."      <tr valign=\"top\"><td align=\"center\"><font size=2><b>TRAMITAÇÃO DE AÇÃO</b></font><br><br><td></tr>"."\r\n";
}
  else
if ($p_tipo==3)
{

$w_html=$w_html."      <tr valign=\"top\"><td align=\"center\"><font size=2><b>CONCLUSÃO DE AÇÃO</b></font><br><br><td></tr>"."\r\n";
} 

$w_html=$w_html."      <tr valign=\"top\"><td><font size=2><b><font color=\"#BC3131\">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></tr>"."\r\n";


// Recupera os dados da ação

$DB_GetSolicData$RSM$p_solic//PJGERAL"
$w_nome="Ação ".$RSM["titulo"];

$w_html=$w_html."\r\n"."<tr bgcolor=\"".$conTrBgColor."\"><td align=\"center\">";
$w_html=$w_html."\r\n"."    <table width=\"99%\" border=\"0\">";
$w_html=$w_html."\r\n"."      <tr><td><font size=2>Ação: <b>".$RSM["titulo"]."</b></font></td>";

// Identificação da ação

$w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>EXTRATO DA AÇÃO</td>";
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
$w_html=$w_html."\r\n"."          </table>";

// Informações adicionais

if (Nvl($RSM["descricao"],"")>"")
{

if (Nvl($RSM["descricao"],"")>"")
{
$w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Resultados da ação:<br><b>".$CRLF2BR[$RSM["descricao"]]." </b></td>";
}
;
} 
} 


$w_html=$w_html."\r\n"."    </table>";
$w_html=$w_html."\r\n"."</tr>";

// Dados da conclusão da ação, se ela estiver nessa situação

if ($RSM["concluida"]=="S" && Nvl($RSM["data_conclusao"],"")>"")
{

$w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\" align=\"center\" bgcolor=\"#D0D0D0\" style=\"border: 2px solid rgb(0,0,0);\"><font size=\"1\"><b>DADOS DA CONCLUSÃO</td>";
$w_html=$w_html."\r\n"."      <tr><td valign=\"top\" colspan=\"2\"><table border=0 width=\"100%\" cellspacing=0>";
$w_html=$w_html."\r\n"."          <tr valign=\"top\">";
$w_html=$w_html."\r\n"."          <td><font size=\"1\">Início da execução:<br><b>".FormataDataEdicao($RSM["inicio_real"])." </b></td>";
$w_html=$w_html."\r\n"."          <td><font size=\"1\">Término da execução:<br><b>".FormataDataEdicao($RSM["fim_real"])." </b></td>";
$w_html=$w_html."\r\n"."          </table>";
$w_html=$w_html."\r\n"."      <tr><td valign=\"top\"><font size=\"1\">Nota de conclusão:<br><b>".$CRLF2BR[$RSM["nota_conclusao"]]." </b></td>";
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
$w_html=$w_html."\r\n"."          <tr valign=\"top\"><td colspan=2><font size=\"1\">Despacho:<br><b>".$CRLF2BR[Nvl($RS["despacho"],"---")]." </b></td>";
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
$w_html=$w_html."         <li>Responsável: <b>".${"nome"."_session"}."</b></li>"."\r\n";
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

$ScriptOpen"JavaScript";
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
case "ORGERAL":
// Verifica se a Assinatura Eletrônica é válida

if ((VerificaAssinaturaEletronica(${"Username"."_session"},$w_assinatura) && $w_assinatura>"") || 
   $w_assinatura=="")
{

// Se for operação de exclusão, verifica se é necessário excluir os arquivos físicos

if (O(="E"$Then))
{
DB_GetSolicLog($RS,${"w_chave"},null,"LISTA");} 

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

//No caso de mudança da ação PPA, os regitros de outras iniciativas devem se apagadas. Caso a ação PPA seja

//nula, deve-se apagar todas os registros e caso seja outra ação deve-se apagar aquela ação das outras iniciativas, caso exista.

if (${"w_sq_orprioridade"}=="")
{

$DML_PutProjetoOutras"E"${"w_chave"}$null;
}
  else
{

$DML_PutProjetoOutras"E"${"w_chave"}${"w_sq_prioridade"}
} 

$DML_PutProjetoGeralO(,
${"w_chave"},${"w_menu"},${"lotacao"."_session"},${"w_solicitante"},${"w_proponente"},
${"sq_pessoa"."_session"},null,${"w_sqcc"},${"w_descricao"},${"w_justificativa"},${"w_inicio"},${"w_fim"},${"w_valor"},
${"w_data_hora"});

$ScriptOpen"JavaScript";
if (O(="I"$Then))
{
// Envia e-mail comunicando a inclusão

$SolicMailNvl(${"w_chave"},$w_chave_nova)

// Recupera os dados para montagem correta do menu

DB_GetMenuData($RS1,$w_menu);
ShowHTML("  parent.menu.location='../Menu.asp?par=ExibeDocs&O=A&w_chave=".$w_chave_nova."&w_documento=Nr. ".$w_chave_nova."&R=".$R."&SG=".$RS1["sigla"]."&TP=".$TP.$MontaFiltro["GET"]."';");
}
  else
if (O(="E"$Then))
{
ShowHTML("  location.href='".$R."&O=L&R=".$R."&SG=ORCAD&w_menu=".$w_menu."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP.$MontaFiltro["GET"]."';");
}
  else
{

// Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link

$DB_GetLinkData$RS1session_register("p_cliente");
${"p_cliente"."_session"}=$SG;
ShowHTML("  location.href='".str_replace($w_dir,"",$RS1["link"])."&O=".O(."&w_chave=".${"w_Chave"}."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.$MontaFiltro["GET"]."';"));
} 

ScriptClose();
}
  else
{

$ScriptOpen"JavaScript";
ShowHTML("  alert('Assinatura Eletrônica inválida!');");
ShowHTML("  history.back(1);");
ScriptClose();
break;
case :
break;
case "ORINFO":
// Verifica se a Assinatura Eletrônica é válida

if ((VerificaAssinaturaEletronica(${"Username"."_session"},$w_assinatura) && $w_assinatura>"") || 
   $w_assinatura=="")
{


$DML_PutProjetoInfo
  ${"w_chave"}${"w_descricao"}${"w_justificativa"}${"w_problema"}${"w_ds_acao"}${"w_publico_alvo"}${"w_estrategia"}${"w_indicadores"}${"w_objetivo"}

$ScriptOpen"JavaScript";
if (O(="I"$Then))
{
// Recupera os dados para montagem correta do menu

DB_GetMenuData($RS1,$w_menu);
ShowHTML("  parent.menu.location='../Menu.asp?par=ExibeDocs&O=A&w_chave=".$w_chave_nova."&w_documento=Nr. ".$w_chave_nova."&R=".$R."&SG=".$RS1["sigla"]."&TP=".$TP.$MontaFiltro["GET"]."';");
}
  else
if (O(="E"$Then))
{
ShowHTML("  location.href='".$R."&O=L&R=".$R."&SG=ORCAD&w_menu=".$w_menu."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP.$MontaFiltro["GET"]."';");
}
  else
{

// Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link

$DB_GetLinkData$RS1session_register("p_cliente");
${"p_cliente"."_session"}=$SG;
ShowHTML("  location.href='".str_replace($w_dir,"",$RS1["link"])."&O=".O(."&w_chave=".${"w_Chave"}."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.$MontaFiltro["GET"]."';"));
} 

ScriptClose();
}
  else
{

$ScriptOpen"JavaScript";
ShowHTML("  alert('Assinatura Eletrônica inválida!');");
ShowHTML("  history.back(1);");
ScriptClose();
} 

break;
case "OROUTRAS":
// Verifica se a Assinatura Eletrônica é válida

if ((VerificaAssinaturaEletronica(${"Username"."_session"},$w_assinatura) && $w_assinatura>"") || 
   $w_assinatura=="")
{

$DML_PutProjetoOutras"E"${"w_chave"}$null;
for ($w_cont=1; $w_cont<=$_POST["w_outras_iniciativas"].$Count; $w_cont=$w_cont+1)
{

if (${"w_outras_iniciativas"}($w_cont)>"")
{

$DML_PutProjetoOutras"I"${"w_chave"}${"w_outras_iniciativas"}$w_cont);
} 


} 

$ScriptOpen"JavaScript";
if (O(="I"$Then))
{
// Recupera os dados para montagem correta do menu

DB_GetMenuData($RS1,$w_menu);
ShowHTML("  parent.menu.location='../Menu.asp?par=ExibeDocs&O=A&w_chave=".$w_chave_nova."&w_documento=Nr. ".$w_chave_nova."&R=".$R."&SG=".$RS1["sigla"]."&TP=".$TP.$MontaFiltro["GET"]."';");
}
  else
if (O(="E"$Then))
{
ShowHTML("  location.href='".$R."&O=L&R=".$R."&SG=ORCAD&w_menu=".$w_menu."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP.$MontaFiltro["GET"]."';");
}
  else
{

// Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link

$DB_GetLinkData$RS1session_register("p_cliente");
${"p_cliente"."_session"}=$SG;
ShowHTML("  location.href='".str_replace($w_dir,"",$RS1["link"])."&O=".O(."&w_chave=".${"w_Chave"}."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.$MontaFiltro["GET"]."';"));
} 

ScriptClose();
}
  else
{

$ScriptOpen"JavaScript";
ShowHTML("  alert('Assinatura Eletrônica inválida!');");
ShowHTML("  history.back(1);");
ScriptClose();
} 

break;
case "ORFINANC":
// Verifica se a Assinatura Eletrônica é válida

if ((VerificaAssinaturaEletronica(${"Username"."_session"},$w_assinatura) && $w_assinatura>"") || 
   $w_assinatura=="")
{

DML_PutProjetoFinancAcao(O(,${"w_chave"},${"w_sq_acao_ppa"},${"w_obs_financ"}));
$ScriptOpen"JavaScript";
// Recupera a sigla do serviço pai, para fazer a chamada ao menu

$DB_GetLinkData$RSsession_register("p_cliente");
${"p_cliente"."_session"}=$SG;
ShowHTML("  location.href='".str_replace($w_dir,"",$RS["link"])."&O=L&w_chave=".${"w_chave"}."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."';");
DesconectaBD();
ScriptClose();
}
  else
{

$ScriptOpen"JavaScript";
ShowHTML("  alert('Assinatura Eletrônica inválida!');");
ShowHTML("  history.back(1);");
ScriptClose();
} 

break;
case "ORRESP":
// Verifica se a Assinatura Eletrônica é válida

if ((VerificaAssinaturaEletronica(${"Username"."_session"},$w_assinatura) && $w_assinatura>"") || 
   $w_assinatura=="")
{


$DML_PutRespAcao${"w_chave_aux"}${"w_responsavel"}${"w_telefone"}${"w_email"}${"w_tipo"}

$ScriptOpen"JavaScript";
// Recupera a sigla do serviço pai, para fazer a chamada ao menu

$DB_GetLinkData$RSsession_register("p_cliente");
${"p_cliente"."_session"}=$SG;
ShowHTML("  location.href='".str_replace($w_dir,"",$RS["link"])."&O=L&w_chave=".${"w_chave"}."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."';");
DesconectaBD();
ScriptClose();
}
  else
{

$ScriptOpen"JavaScript";
ShowHTML("  alert('Assinatura Eletrônica inválida!');");
ShowHTML("  history.back(1);");
ScriptClose();
} 


break;
case "ORETAPA":
// Verifica se a Assinatura Eletrônica é válida

if ((VerificaAssinaturaEletronica(${"Username"."_session"},$w_assinatura) && $w_assinatura>"") || 
   $w_assinatura=="")
{

DML_PutProjetoEtapa(o(,${"w_chave"},${"w_chave_aux"},${"w_chave_pai"},
${"w_titulo"},${"w_descricao"},${"w_ordem"},${"w_inicio"},
${"w_fim"},${"w_perc_conclusao"},${"w_orcamento"},
${"w_sq_pessoa"},${"w_sq_unidade"},${"w_vincula_atividade"},$w_usuario));

$ScriptOpen"JavaScript";
// Recupera a sigla do serviço pai, para fazer a chamada ao menu

$DB_GetLinkData$RSsession_register("p_cliente");
${"p_cliente"."_session"}=$SG;
ShowHTML("  location.href='".str_replace($w_dir,"",$RS["link"])."&O=L&w_chave=".${"w_chave"}."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."';");
DesconectaBD();
ScriptClose();
}
  else
{

$ScriptOpen"JavaScript";
ShowHTML("  alert('Assinatura Eletrônica inválida!');");
ShowHTML("  history.back(1);");
ScriptClose();
} 

break;
case "ORCAD":
// Verifica se a Assinatura Eletrônica é válida

if ((VerificaAssinaturaEletronica(${"Username"."_session"},$w_assinatura) && $w_assinatura>"") || 
   $w_assinatura=="")
{


// Verifica se a meta é cumulativa ou não para o calculo do percentual de conclusão

if (${"w_cumulativa"}=="S")
{

$i=1;
// Faz a varredura do campos de quantidade e irá armazenar o percentual de conclusão do ultimo mês atualizazado

while($i<13)
{

if ($cDbl[Nvl(${"w_quantitativo_".$i.""},0)]>0)
{

$w_perc_conclusao=(${"w_quantitativo_".$i.""}*100)/${"w_quantidade"};
} 

$i=$i+1;
} 
}
  else
{

//Se não for cumulativa faz o percentual de conclusão com todos os valores do formulário

$w_quantitativo_total=$cDbl[Nvl(${"w_quantitativo_1"},0)]+$cDbl[Nvl(${"w_quantitativo_2"},0)]+$cDbl[Nvl(${"w_quantitativo_3"},0)]+$cDbl[Nvl(${"w_quantitativo_4"},0)]+
  $cDbl[Nvl(${"w_quantitativo_5"},0)]+$cDbl[Nvl(${"w_quantitativo_6"},0)]+$cDbl[Nvl(${"w_quantitativo_7"},0)]+$cDbl[Nvl(${"w_quantitativo_8"},0)]+
  $cDbl[Nvl(${"w_quantitativo_9"},0)]+$cDbl[Nvl(${"w_quantitativo_10"},0)]+$cDbl[Nvl(${"w_quantitativo_11"},0)]+$cDbl[Nvl(${"w_quantitativo_12"},0)];
if ($cDbl[Nvl(${"w_quantidade"},0)]>0)
{

$w_perc_conclusao=($w_quantitativo_total*100)/$cDbl[${"w_quantidade"}];
} 

} 

$DML_PutAtualizaEtapa${"w_chave"}${"w_chave_aux"}$w_usuarioNvl($w_perc_conclusao,0);${"w_situacao_atual"}${"w_exequivel"}${"w_justificativa_inex"}${"w_outras_medidas"}
$i=1;
// Gravação da execução física e feita mês por mês

DML_PutEtapaMensal("E",${"w_chave_aux"},${"w_quantitativo_".$i.""},${"w_referencia_".$i.""});
while($i<13)
{

if ($cDbl[Nvl(${"w_quantitativo_".$i.""},0)]>0)
{

DML_PutEtapaMensal("I",${"w_chave_aux"},${"w_quantitativo_".$i.""},${"w_referencia_".$i.""});
} 

$i=$i+1;
} 

$ScriptOpen"JavaScript";
// Recupera a sigla do serviço pai, para fazer a chamada ao menu

ShowHTML("  location.href='".$R."&O=L&w_chave=".${"w_chave"}."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG.$MontaFiltro["GET"]."';");
ScriptClose();
}
  else
{

$ScriptOpen"JavaScript";
ShowHTML("  alert('Assinatura Eletrônica inválida!');");
ShowHTML("  history.back(1);");
ScriptClose();
} 

break;
case "ORRECURSO":
// Verifica se a Assinatura Eletrônica é válida

if ((VerificaAssinaturaEletronica(${"Username"."_session"},$w_assinatura) && $w_assinatura>"") || 
   $w_assinatura=="")
{


DML_PutProjetoRec(O(,${"w_chave"},${"w_chave_aux"},${"w_nome"},${"w_tipo"},${"w_descricao"},${"w_finalidade"}));

$ScriptOpen"JavaScript";
// Recupera a sigla do serviço pai, para fazer a chamada ao menu

$DB_GetLinkData$RSsession_register("p_cliente");
${"p_cliente"."_session"}=$SG;
ShowHTML("  location.href='".str_replace($w_dir,"",$RS["link"])."&O=L&w_chave=".${"w_chave"}."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."';");
DesconectaBD();
ScriptClose();
}
  else
{

$ScriptOpen"JavaScript";
ShowHTML("  alert('Assinatura Eletrônica inválida!');");
ShowHTML("  history.back(1);");
ScriptClose();
} 

break;
case "ETAPAREC":
// Verifica se a Assinatura Eletrônica é válida

if ((VerificaAssinaturaEletronica(${"Username"."_session"},$w_assinatura) && $w_assinatura>"") || 
   $w_assinatura=="")
{


// Inicialmente, desativa a opção em todos os endereços

$DML_PutSolicEtpRec"E"${"w_chave_aux"}$null;

// Em seguida, ativa apenas para os endereços selecionados

for ($w_cont=1; $w_cont<=$_POST["w_recurso"].$Count; $w_cont=$w_cont+1)
{

if (${"w_recurso"}($w_cont)>"")
{

$DML_PutSolicEtpRec"I"${"w_chave_aux"}${"w_recurso"}$w_cont);
} 


} 


$ScriptOpen"JavaScript";
// Recupera a sigla do serviço pai, para fazer a chamada ao menu

$DB_GetLinkData$RSsession_register("p_cliente");
${"p_cliente"."_session"}=${"w_SG"};
ShowHTML("  location.href='".str_replace($w_dir,"",$RS["link"])."&O=L&w_chave=".${"w_chave"}."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$RS["sigla"]."';");
DesconectaBD();
ScriptClose();
}
  else
{

$ScriptOpen"JavaScript";
ShowHTML("  alert('Assinatura Eletrônica inválida!');");
ShowHTML("  history.back(1);");
ScriptClose();
} 

break;
case "ORINTERESS":
// Verifica se a Assinatura Eletrônica é válida

if ((VerificaAssinaturaEletronica(${"Username"."_session"},$w_assinatura) && $w_assinatura>"") || 
   $w_assinatura=="")
{


DML_PutProjetoInter(O(,${"w_chave"},${"w_chave_aux"},${"w_tipo_visao"},${"w_envia_email"}));

$ScriptOpen"JavaScript";
// Recupera a sigla do serviço pai, para fazer a chamada ao menu

$DB_GetLinkData$RSsession_register("p_cliente");
${"p_cliente"."_session"}=$SG;
ShowHTML("  location.href='".str_replace($w_dir,"",$RS["link"])."&O=L&w_chave=".${"w_chave"}."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."';");
DesconectaBD();
ScriptClose();
}
  else
{

$ScriptOpen"JavaScript";
ShowHTML("  alert('Assinatura Eletrônica inválida!');");
ShowHTML("  history.back(1);");
ScriptClose();
} 

break;
case "ORAREAS":
// Verifica se a Assinatura Eletrônica é válida

if ((VerificaAssinaturaEletronica(${"Username"."_session"},$w_assinatura) && $w_assinatura>"") || 
   $w_assinatura=="")
{


$DML_PutProjetoAreasO(,${"w_chave"},${"w_chave_aux"},${"w_papel"});

$ScriptOpen"JavaScript";
// Recupera a sigla do serviço pai, para fazer a chamada ao menu

$DB_GetLinkData$RSsession_register("p_cliente");
${"p_cliente"."_session"}=$SG;
ShowHTML("  location.href='".str_replace($w_dir,"",$RS["link"])."&O=L&w_chave=".${"w_chave"}."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."';");
DesconectaBD();
ScriptClose();
}
  else
{

$ScriptOpen"JavaScript";
ShowHTML("  alert('Assinatura Eletrônica inválida!');");
ShowHTML("  history.back(1);");
ScriptClose();
} 

break;
case "ORANEXO":
// Verifica se a Assinatura Eletrônica é válida

if ((VerificaAssinaturaEletronica(${"Username"."_session"},$w_assinatura) && $w_assinatura>"") || 
   $w_assinatura=="")
{

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

$ScriptOpen["JavaScript"];
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

if (O(="E"&$ul->Texts.$Item["w_atual"]>""$Then))
{
DB_GetSolicAnexo($RS,$ul->Texts.$Item["w_chave"],$ul->Texts.$Item["w_atual"],$w_cliente);} 

$FS->DeleteFile$conFilePhysical.$w_cliente."\".$RS["caminho"];
DesconectaBD();
} 


//Response.Write O& ", " &w_cliente& ", " &ul.Texts.Item("w_chave")& ", " &ul.Texts.Item("w_chave_aux")& ", " &ul.Texts.Item("w_nome")& ", " &ul.Texts.Item("w_descricao")

//Response.End()

$DML_PutSolicArquivoO(,
$w_cliente,$ul->Texts.$Item["w_chave"],$ul->Texts.$Item["w_chave_aux"],$ul->Texts.$Item["w_nome"],$ul->Texts.$Item["w_descricao"],
$w_file,$w_tamanho,$w_tipo,$w_nome);
}
  else
{

$ScriptOpen"JavaScript";
ShowHTML("  alert('ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!');");
ScriptClose();
exit();

return $function_ret;

} 


$ScriptOpen"JavaScript";
// Recupera a sigla do serviço pai, para fazer a chamada ao menu 

$DB_GetLinkData$RSsession_register("p_cliente");
${"p_cliente"."_session"}=$SG;
ShowHTML("  location.href='".str_replace($w_dir,"",$RS["link"])."&O=L&w_chave=".$ul->Texts.$Item["w_chave"]."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$SG."';");
DesconectaBD();
ScriptClose();
}
  else
{

$ScriptOpen"JavaScript";
ShowHTML("  alert('Assinatura Eletrônica inválida!');");
ShowHTML("  history.back(1);");
ScriptClose();
break;
case :
break;
case "ORENVIO":
// Verifica se a Assinatura Eletrônica é válida

if ((VerificaAssinaturaEletronica(${"Username"."_session"},$w_assinatura) && $w_assinatura>"") || 
   $w_assinatura=="")
{


$DB_GetSolicData$RS${"w_chave"}//ORGERAL"if ($cDbl[$RS["sq_siw_tramite"]]!=$cDbl[${"w_tramite"}])
{

$ScriptOpen"JavaScript";
ShowHTML("  alert('ATENÇÃO: Outro usuário já encaminhou esta ação para outra fase de execução!');");
ScriptClose();
}
  else
{

DML_PutProjetoEnvio(${"w_menu"},${"w_chave"},$w_usuario,${"w_tramite"},${"w_novo_tramite"},"N",${"w_observacao"},${"w_destinatario"},${"w_despacho"},null,null,null);

// Envia e-mail comunicando a tramitação

if (${"w_novo_tramite"}>"")
{

$SolicMail${"w_chave"}
} 


if ($P1==1)
{
// Se for envio da fase de cadastramento, remonta o menu principal

// Recupera os dados para montagem correta do menu

DB_GetMenuData($RS,$w_menu);
$ScriptOpen"JavaScript";
ShowHTML("  parent.menu.location='../Menu.asp?par=ExibeDocs&O=L&R=".$R."&SG=".$RS["sigla"]."&TP=".RemoveTP(RemoveTP($TP)).$MontaFiltro["GET"]."';");
ScriptClose();
DesconectaBD();
}
  else
{

$ScriptOpen"JavaScript";
// Volta para a listagem

DB_GetMenuData($RS,$w_menu);
ShowHTML("  location.href='".str_replace($w_dir,"",$RS["link"])."&O=L&w_chave=".${"w_chave"}."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".RemoveTP($TP)."&SG=".$rs["sigla"].$MontaFiltro["GET"]."';");
DesconectaBD();
ScriptClose();
} 

} 

}
  else
{

$ScriptOpen"JavaScript";
ShowHTML("  alert('Assinatura Eletrônica inválida!');");
ShowHTML("  history.back(1);");
ScriptClose();
} 

break;
case "ORCONC":
// Verifica se a Assinatura Eletrônica é válida

if ((VerificaAssinaturaEletronica(${"Username"."_session"},$w_assinatura) && $w_assinatura>"") || 
   $w_assinatura=="")
{


$DB_GetSolicData$RS${"w_chave"}//PJGERAL"if ($cDbl[$RS["sq_siw_tramite"]]!=$cDbl[${"w_tramite"}])
{

$ScriptOpen"JavaScript";
ShowHTML("  alert('ATENÇÃO: Outro usuário já encaminhou esta ação para outra fase de execução!');");
ScriptClose();
}
  else
{

DML_PutProjetoConc(${"w_menu"},${"w_chave"},$w_usuario,${"w_tramite"},${"w_inicio_real"},${"w_fim_real"},${"w_nota_conclusao"},${"w_custo_real"});

// Envia e-mail comunicando a conclusão

$SolicMail${"w_chave"}

$ScriptOpen"JavaScript";
// Volta para a listagem

DB_GetMenuData($RS,$w_menu);
ShowHTML("  location.href='".str_replace($w_dir,"",$RS["link"])."&O=L&w_chave=".${"w_chave"}."&P1=".$P1."&P2=".$P2."&P3=".$P3."&P4=".$P4."&TP=".$TP."&SG=".$rs["sigla"].$MontaFiltro["GET"]."';");
DesconectaBD();
ScriptClose();
} 

}
  else
{

$ScriptOpen"JavaScript";
ShowHTML("  alert('Assinatura Eletrônica inválida!');");
ShowHTML("  history.back(1);");
ScriptClose();
} 

break;
default:

$ScriptOpen"JavaScript";
ShowHTML("  alert('Bloco de dados não encontrado: ".$SG."');");
ShowHTML("  history.back(1);");
ScriptClose();
break;
} 

$FS=null;

$w_Mensagem=null;

$w_chave_nova=null;

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

if ((strlen(${"LOTACAO"."_session"}."")==0 || strlen(${"LOCALIZACAO"."_session"}."")==0) && ${"LogOn"."_session"}=="Sim")
{

$ScriptOpen"JavaScript";
ShowHTML(" alert('Você não tem lotação ou localização definida. Entre em contato com o RH!'); ");
ShowHTML(" top.location.href='Default.asp'; ");
ScriptClose();
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
case "INFOADIC":
$InfoAdic;
break;
case "OUTRAS":
$Iniciativas;
break;
case "FINANC":
$Financiamento;
break;
case "RESP":
$Responsaveis;
break;
case "ETAPA":
$Etapas;
break;
case "RECURSO":
$Recursos;
break;
case "ETAPARECURSO":
$EtapaRecursos;
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
case "VISUALE":
$VisualE;
break;
case "EXCLUIR":
$Excluir;
break;
case "ENVIO":
$Encaminhamento;
break;
case "ANEXO":
$Anexos;
break;
case "ANOTACAO":
$Anotar;
break;
case "CONCLUIR":
$Concluir;
break;
case "ATUALIZAETAPA":
$AtualizaEtapa;
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


