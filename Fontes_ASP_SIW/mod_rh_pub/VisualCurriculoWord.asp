<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="jScript.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="VisualCurriculo.asp" -->
<%
REM =========================================================================
REM  /VisualCurriculoWord.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Recebe os dados do currículo
REM Mail     : alex@sbpi.com.br
REM Criacao  : 22/07/2003 16:00
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
ShowHTML "<TITLE>Curriculum Vitae</TITLE>"
ShowHTML "</HEAD>"  
ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TD ALIGN=""RIGHT""><B><FONT SIZE=5 COLOR=""#000000"">"
ShowHTML "Curriculum Vitae"
ShowHTML "</FONT><TR><TD ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">" & w_data_banco & "</B></TD></TR>"
ShowHTML "</FONT></B></TD></TR></TABLE>"
ShowHTML "<HR>"

HTML = VisualCurriculo(w_cpf, "L")
ShowHTML "" & HTML
OraDatabase.close

%>