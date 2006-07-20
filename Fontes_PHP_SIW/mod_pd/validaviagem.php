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
<!-- #INCLUDE FILE="../cp_upload/_upload.php" -->
<!-- #INCLUDE FILE="DB_Geral.php" -->
<!-- #INCLUDE FILE="DB_Viagem.php" -->
<!-- #INCLUDE FILE="DML_Viagem.php" -->
<!-- #INCLUDE FILE="VisualViagem.php" -->
<!-- #INCLUDE FILE="Funcoes.php" -->
<!-- #INCLUDE FILE="DB_Tabelas.php" -->
<? 
// =========================================================================

// Rotina de valida��o dos dados da miss�o

// -------------------------------------------------------------------------

function ValidaViagem($p_cliente,$p_chave,$p_sg1,$p_sg2,$p_sg3,$p_sg4,$p_tramite)
{
  extract($GLOBALS);



// Se n�o encontrar erro, esta fun��o retorna cadeia fazia.

// Se o retorno for diferente de cadeia vazia, o primeiro byte indica o tipo de erro

// 0 - Erro de integridade. A solicita��o s� pode ser devolvida

// 1 - Erro de regra de neg�cio. Apenas gestores podem encaminhar a solicita��o

// 2 - Alerta. O sistema indica uma situa��o n�o desej�vel mas permite que o usu�rio

//     encaminhe o projeto

//-----------------------------------------------------------------------------------

// Cria recordsets e vari�veis de trabalho.

// l_rs1 at� l_rs4 s�o recordsets que podem ser usados para armazenar dados de blocos

// de dados espec�ficos da solicita��o que est� sendo validada.

//-----------------------------------------------------------------------------------

// $l_rs_modulo is of type "ADODB.RecordSet"

// $l_rs_solic is of type "ADODB.RecordSet"

// $l_rs_tramite is of type "ADODB.RecordSet"

// $l_rs1 is of type "ADODB.RecordSet"

// $l_rs2 is of type "ADODB.RecordSet"

// $l_rs3 is of type "ADODB.RecordSet"

// $l_rs4 is of type "ADODB.RecordSet"

// $l_rs5 is of type "ADODB.RecordSet"



//-----------------------------------------------------------------------------------

// Esta primeira parte carrega recordsets com os diferentes blocos de dados que

// comp�em a solicita��o

//-----------------------------------------------------------------------------------

// Recupera os dados da solicita��o

  DB_GetSolicData($p_chave,$p_sg1);

// Se a solicita��o informada n�o existir, abandona a execu��o

  if (($l_rs_solic==0))
  {

    $ValidaViagem="0<li>N�o existe registro no banco de dados com o n�mero informado.";
    
    return $function_ret;

  } 


// Verifica se o cliente tem o m�dulo de viagens contratado

  DB_GetSiwCliModLis($p_cliente,null,"PD");
  if (!($l_rs_modulo==0))
  {
    $l_viagem="S";
  }
    else
  {
    $l_viagem="N";
  }
;
} 


$l_erro="";
$l_tipo="";

// Recupera o tr�mite atual da solicita��o

DB_GetTramiteData($l_rs_solic["sq_siw_tramite"]);

// Recupera os dados do proposto

DB_GetBenef($p_cliente,Nvl($l_rs_solic["sq_prop"],0),null,null,null,null,null,null);
if (($l_rs1==0))
{
  $l_existe_rs1=0;
}
  else
{
  $l_existe_rs1=mysql_num_rows($l_rs1_query);
}
;
} 

// Recupera os par�metros do m�dulo de viagem

DB_GetPDParametro($p_cliente,null,null);
if (($l_rs2==0))
{
$l_existe_rs2=0;
}
  else
{
$l_existe_rs2=mysql_num_rows($l_rs2_query);
}
;
} 

// Recupera os deslocamentos da viagem

$DB_GetPD_Deslocamento$p_chave$null$p_sg2;
if (($l_rs3==0))
{
$l_existe_rs3=0;
}
  else
{
$l_existe_rs3=mysql_num_rows($l_rs3_query);
}
;
} 

// Recupera as vincula��es da viagem

$DB_GetSolicList_IS$l_rs_solic["sq_menu"]$w_usuario//PDVINC", 5, _$null$null$null$null$null$null$null$null$null$null$p_chave$null$null$null$null$null$null$null$null$null$null$null$null$null$null$null$null$w_ano;
if (($l_rs4==0))
{
$l_existe_rs4=0;
}
  else
{
$l_existe_rs4=mysql_num_rows($l_rs4_query);
}
;
} 

//-----------------------------------------------------------------------------------

// O bloco abaixo faz as valida��es na solicita��o que n�o s�o poss�veis de fazer

// atrav�s do JavaScript por envolver mais de uma tela

//-----------------------------------------------------------------------------------


//-----------------------------------------------------------------------------

// Verifica��es de integridade de dados da solicita��o, feitas sempre que houver

// um encaminhamento.

//-----------------------------------------------------------------------------


// Verifica se foi indicada a outra parte e se seus dados est�o completos

if ($l_existe_rs1==0)
{

$l_erro=$l_erro."<li>A outra parte n�o foi informada";
$l_tipo=0;
}
  else
{

// Verifica se o benefici�rio tem os dados banc�rios cadastrados

if (nvl($l_rs1["sq_banco"],"")=="" || nvl($l_rs1["sq_agencia"],"")=="" || nvl($l_rs1["nr_conta"],"")=="")
{

$l_erro=$l_erro."<li>Dados banc�rios incompletos.";
$l_tipo=0;
} 

} 


// Verifica se foram cadastrados pelo menos 2 deslocamentos

if ($l_existe_rs3<2)
{

$l_erro=$l_erro."<li>� obrigat�rio informar pelo menos 2 deslocamentos.";
$l_tipo=0;
} 


// Verifica se a viagem foi vinculada a pelo menos uma tarefa

//If l_existe_rs4 < 1 Then

//   l_erro = l_erro & "<li>� obrigat�rio vincular a PCD a pelo menos uma tarefa."

//   l_tipo = 0

//End If

if (!($l_rs_tramite==0))
{

if (Nvl($l_rs_tramite["ordem"],"---")>"1" && (strpos("CH,DF,EA",Nvl($l_rs_tramite["sigla"],"CH")) ? strpos("CH,DF,EA",Nvl($l_rs_tramite["sigla"],"CH"))+1 : 0)>0)
{

// Verifica se o in�cio da miss�o atende ao n�mero de dias de anteced�ncia

// regulamentares. Se n�o atender, deve ser informada justificativa.

if (($l_rs_solic["inicio"]-$cDbl[$l_rs2["dias_antecedencia"]]<time()()) && nvl($l_rs_solic["justificativa"],"")=="")
{

$l_erro=$l_erro."<li>No encaminhamento da PCD deve ser informada a justificativa para n�o cumprimento dos ".$l_rs2["dias_antecedencia"]." dias de anteced�ncia do pedido.";
$l_tipo=2;
} 

} 

if (Nvl($l_rs_tramite["ordem"],"---")>"1")
{

// Este bloco faz verifica��es em solicita��es que est�o em fases posteriores ao

// cadastramento inicial

if (Nvl($l_rs_tramite["sigla"],"---")=="DF")
{

$DB_GetPD_Deslocamento$p_chave$null$l_rs_tramite["sigla"]
if ($cDbl[$l_rs5["existe"]]==0)
{

$l_erro=$l_erro."<li>� obrigat�rio informar as di�rias, mesmo que os valores sejam zeros.";
$l_tipo=0;
} 

}
  else
if (Nvl($l_rs_tramite["sigla"],"---")=="AE")
{

if ((Nvl($l_rs_solic["pta"],"")=="" && $cDbl[Nvl($l_rs_solic["valor_passagem"],0)]==0))
{

$l_erro=$l_erro."<li>� obrigat�rio informar os dados das passagens.";
$l_tipo=0;
} 

} 

$l_erro=$l_erro;
} 

} 




$l_erro=$l_tipo.$l_erro;

//-----------------------------------------------------------------------------------

// Ap�s as verifica��es feitas, devolve cadeia vazia se n�o encontrou erros, ou string

// para ser usada com a tag <UL>.

//-----------------------------------------------------------------------------------


$ValidaViagem=$l_erro;

//-----------------------------------------------------------------------------------

// Fecha recordsets e libera vari�veis de trabalho.

//-----------------------------------------------------------------------------------




$l_rs1=null;

$l_rs2=null;

$l_rs3=null;

$l_rs4=null;

$l_rs5=null;

$l_rs_solic=null;

$l_rs_tramite=null;

$l_rs_modulo=null;


$l_existe_rs1=null;

$l_existe_rs2=null;

$l_existe_rs3=null;

$l_existe_rs4=null;

$l_existe_rs5=null;

$l_erro=null;

$l_tipo=null;

$l_viagem=null;

return $function_ret;
} 
?>


