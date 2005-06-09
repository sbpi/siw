<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="jScript.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<%
Response.Expires = 0
REM =========================================================================
REM  /Mensagem.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papad�polis
REM Descricao: Gerencia o m�dulo de envio eletr�nico de mensagens
REM Mail     : alex@sbpi.com.br
REM Criacao  : 07/11/2001 9:02
REM Versao   : 1.0.0.0
REM Local    : Bras�lia - DF
REM -------------------------------------------------------------------------
REM
REM Par�metros recebidos:
REM    R (refer�ncia) = usado na rotina de grava��o, com conte�do igual ao par�metro T
REM    O (opera��o)   = I   : Inclus�o
REM                   = A   : Altera��o
REM                   = C   : Cancelamento
REM                   = E   : Exclus�o
REM                   = V   : Envio
REM                   = L   : Listagem
REM                   = P   : Pesquisa
REM                   = D   : Detalhes
REM                   = N   : Nova solicita��o de envio

' Verifica se o usu�rio est� autenticado
If Session("LogOn") <> "Sim" Then
  ScriptOpen "JavaScript"
  ShowHTML " alert('Voc� precisa autenticar-se para utilizar o sistema!'); "
  ShowHTML " top.location.href='Default.asp'; "
  ScriptClose
End If

' Declara��o de vari�veis
Dim dbms, sp, RS
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Pagina, w_Disabled, w_TP
Dim w_Assinatura
Dim w_dir_volta
Public Upload,File
Private Par

AbreSessao

' Carrega vari�veis locais com os dados dos par�metros recebidos
Par          = ucase(Request("Par"))
P1           = Request("P1")
P2           = Request("P2")
P3           = Request("P3")
P4           = Request("P4")
TP           = Request("TP")
SG           = ucase(Request("SG"))
R            = uCase(Request("R"))
O            = uCase(Request("O"))
w_Assinatura = uCase(Request("w_Assinatura"))
If InStr(uCase(Request.ServerVariables("http_content_type")),"MULTIPART/FORM-DATA") > 0 Then  
   ' Cria o objeto de upload
   Set Upload   = Server.CreateObject("Dundas.Upload.2")
   ul.SaveToMemory  

   P1           = Upload.Form("P1")
   P2           = Upload.Form("P2")
   P3           = Upload.Form("P3")
   P4           = Upload.Form("P4")
   TP           = Upload.Form("TP")
   SG           = ucase(Upload.Form("SG"))
   R            = uCase(Upload.Form("R"))
   O            = uCase(Upload.Form("O"))
End If
w_Pagina     = "Mensagem.asp?par="
w_Disabled   = "ENABLED"

If O = "" Then O = "L" End If

Select Case O
  Case "I" 
     w_TP = TP & " - Inclus�o"
  Case "A" 
     w_TP = TP & " - Altera��o"
  Case "E" 
     w_TP = TP & " - Exclus�o"
  Case "V" 
     w_TP = TP & " - Envio"
  Case "P" 
     w_TP = TP & " - Filtragem"
  Case Else
     w_TP = TP & " - Listagem"
End Select
Main

FechaSessao


Set RS          = Nothing
Set Par         = Nothing
Set P1          = Nothing
Set P2          = Nothing
Set P3          = Nothing
Set P4          = Nothing
Set TP          = Nothing
Set SG          = Nothing
Set R           = Nothing
Set O           = Nothing
Set w_Cont      = Nothing
Set w_Pagina    = Nothing
Set w_Disabled  = Nothing
Set w_TP        = Nothing
Set w_Assinatura= Nothing
Set Upload      = Nothing
Set File        = Nothing

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main

  Select Case Par
    Case Else
       Cabecalho
       BodyOpen "onLoad=document.focus();"
       ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
       ShowHTML "<HR>"
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
       Rodape
  End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>

