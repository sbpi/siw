<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="jScript.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="VisualListaTel.asp" -->
<%
REM =========================================================================
REM  /VisualCurriculoWord.asp
REM ------------------------------------------------------------------------
REM Nome     : Egisberto Vicente da Silva
REM Descricao: Faz a listagem da Lista de Telefones
REM Mail     : beto@sbpi.com.br
REM Criacao  : 28/07/2004 10:00
REM Versao   : 1.0.0.0
REM Local    : Brasília - DF
REM -------------------------------------------------------------------------
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

w_cpf = Request("w_cpf")
Response.ContentType = "application/msword"

ShowHTML "<HEAD>"
ShowHTML "<TITLE>Lista Telefônica</TITLE>"
ShowHTML "</HEAD>"  
ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TD ALIGN=""RIGHT""><B><FONT SIZE=5 COLOR=""#000000"">"
ShowHTML "Lista Telefônica"
ShowHTML "</FONT><TR><TD ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">" & w_data_banco & "</B></TD></TR>"
ShowHTML "</FONT></B></TD></TR></TABLE>"
ShowHTML "<HR>"

HTML = VisualListaTel(w_cliente)
ShowHTML "" & HTML
OraDatabase.close

%>