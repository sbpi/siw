<?php
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
<!-- #INCLUDE FILE="../mod_pd/DB_Tabelas.php" -->
<!-- #INCLUDE FILE="../mod_pd/DB_Viagem.php" -->
<!-- #INCLUDE FILE="../mod_rh/DB_Tabelas.php" -->
<!-- #INCLUDE FILE="../cp_upload/_upload.php" -->
<!-- #INCLUDE FILE="DB_Geral.php" -->
<!-- #INCLUDE FILE="DB_Viagem.php" -->
<!-- #INCLUDE FILE="DML_Viagem.php" -->
<!-- #INCLUDE FILE="ValidaViagem.php" -->
<!-- #INCLUDE FILE="VisualViagem.php" -->
<!-- #INCLUDE FILE="DB_Tabelas.php" -->
<? 
// =========================================================================

// Montagem da seleção de tipos de acordo

// -------------------------------------------------------------------------

function SelecaoTipoAcordo($label,$accesskey,$hint,$chave,$chaveAux,$chaveAux2,$campo,$restricao,$atributo)
{
  extract($GLOBALS);


  DB_GetAgreeType($RS,null,$chaveAux,$chaveAux2,$restricao);
$RS->Sort="nm_tipo";
  if (!isset($hint))
  {

    ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>".$Label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" class=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
  }
    else
  {

    ShowHTML("          <td valign=\"top\" TITLE=\"".$hint."\"><font size=\"1\"><b>".$Label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" class=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
  } 

  ShowHTML("          <option value=\"\">---");
  while(!$RS->EOF)
  {

    if ($cDbl[nvl($RS["sq_tipo_acordo"],0)]==$cDbl[nvl($chave,0)])
    {

      ShowHTML("          <option value=\"".$RS["sq_tipo_acordo"]."\" SELECTED>".$RS["nm_tipo"]);
    }
      else
    {

      ShowHTML("          <option value=\"".$RS["sq_tipo_acordo"]."\">".$RS["nm_tipo"]);
    } 

$RS->MoveNext;
  } 
  ShowHTML("          </select>");
  return $function_ret;
} 

// =========================================================================

// Montagem da seleção de tipo de PCD

// -------------------------------------------------------------------------

function SelecaoTipoPCD($label,$accesskey,$hint,$chave,$campo,$restricao,$atributo)
{
  extract($GLOBALS);


  if (!isset($hint))
  {

    ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>".$Label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
  }
    else
  {

    ShowHTML("          <td valign=\"top\" title=\"".$hint."\"><font size=\"1\"><b>".$Label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
  } 

  ShowHTML("          <option value=\"\">---");
  if (nvl($chave,"")=="I")
  {
    ShowHTML("          <option value=\"I\" SELECTED>Inicial");  } 
}
  else
{
  ShowHTML;
}
("          <option value=\"I\">Inicial");
  if (nvl($chave,"")=="P")
  {
    ShowHTML("          <option value=\"P\" SELECTED>Prorrogação");  } 
}
  else
{
  ShowHTML;
}
("          <option value=\"P\">Prorrogação");
  if (nvl($chave,"")=="C")
  {
    ShowHTML("          <option value=\"C\" SELECTED>Complementação");  } 
}
  else
{
  ShowHTML;
}
("          <option value=\"C\">Complementação");
  ShowHTML("          </select>");
  return $function_ret;
} 

// =========================================================================

// Montagem da seleção de companhias de viagem

// -------------------------------------------------------------------------

function SelecaoCiaTrans($label,$accesskey,$hint,$cliente,$chave,$chaveAux,$campo,$restricao,$atributo)
{
  extract($GLOBALS);


  DB_GetCiaTrans($RS,$cliente,null,null,null,null,null,null,null,null,null);
$RS->Sort="padrao desc, nome";
  if ($restricao=="S")
  {

$RS->Filter="ativo = 'S'";
  } 

  if (!isset($hint))
  {

    if ($Label=="")
    {

      ShowHTML("          <td><SELECT ACCESSKEY=\"".$accesskey."\" class=\"STS\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
    }
      else
    {

      ShowHTML("          <td><b>".$Label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" class=\"STS\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
    } 

  }
    else
  {

    if ($Label=="")
    {

      ShowHTML("          <td><SELECT ACCESSKEY=\"".$accesskey."\" class=\"STS\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
    }
      else
    {

      ShowHTML("          <td valign=\"top\" TITLE=\"".$hint."\"><font size=\"1\"><b>".$Label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" class=\"STS\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
    } 

  } 

  ShowHTML("          <option value=\"\">---");
  while(!$RS->EOF)
  {

    if ($cDbl[nvl($RS["chave"],0)]==$cDbl[nvl($chave,0)])
    {

      ShowHTML("          <option value=\"".$RS["chave"]."\" SELECTED>".$RS["nome"]);
    }
      else
    {

      ShowHTML("          <option value=\"".$RS["chave"]."\">".$RS["nome"]);
    } 

$RS->MoveNext;
  } 
  ShowHTML("          </select>");
  return $function_ret;
} 

// =========================================================================

// Função que retorna S/N indicando se o usuário informado pode cadastrar

// viagens para qualquer pessoa ou somente para ele mesmo

// -------------------------------------------------------------------------

function RetornaCadastrador_PD($p_menu,$p_usuario)
{
  extract($GLOBALS);



  $l_acesso="";

  $DB_GetCadastrador_PD$p_menu  $p_usuario  $l_acesso;
  $RetornaCadastrador_PD=$l_acesso;

  $l_acesso=null;

  return $function_ret;
} 
?>
