<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="jScript.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="VisualResumoLigacaoParticular.asp" -->
<%
REM ====================================================================================================
REM  /VisualResumoLigacaoParticularWord.asp
REM ----------------------------------------------------------------------------------------------------
REM Nome     : Egisberto Vicente da Silva
REM Descricao: Recebe os dados das ligações particulares efetudas em um pre-determinado espaço de tempo.
REM Mail     : beto@sbpi.com.br
REM Criacao  : 03/08/2004 17:07
REM Versao   : 1.0.0.0
REM Local    : Brasília - DF
REM ----------------------------------------------------------------------------------------------------
REM
Dim Oradatabase
Dim RS, SQL
Dim w_data_banco, HTML, w_cpf

Abresessao
'DATA DO BANCO DE DADOS
SQL = "SELECT TO_CHAR(SYSDATE,'DD/MM/YYYY,HH24:MM') DATA FROM DUAL"
ConectaBD
w_data_banco = RS("DATA")
DesconectaBD
Response.Write "[" & Request("w_sq_usuario") & "]"
Response.Write "[" & inicio & "]"
Response.Write "[" & fim & "]"
Response.Write "[" & ativo & "]"
Response.Write "[" & O & "]"
w_cpf = Request("w_cpf")
Response.ContentType = "application/msword"

ShowHTML "<HEAD>"
ShowHTML "<TITLE>Resumo de Ligações Particulares</TITLE>"
ShowHTML "</HEAD>"  
ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TD ALIGN=""RIGHT""><B><FONT SIZE=5 COLOR=""#000000"">"
ShowHTML "Resumo de Ligações Particulares"
ShowHTML "</FONT><TR><TD ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">" & w_data_banco & "</B></TD></TR>"
ShowHTML "</FONT></B></TD></TR></TABLE>"
ShowHTML "<HR>"

HTML = ResumLigPart (w_sq_usuario, inicio, fim, "N", O)
ShowHTML "" & HTML
OraDatabase.close

%>