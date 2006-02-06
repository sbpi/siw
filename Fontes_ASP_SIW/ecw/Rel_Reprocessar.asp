<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DB_Tipo_Curso.asp" -->
<!-- #INCLUDE FILE="DB_Relatorio.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Rel_Reprocessar.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Emite lista de unidades
REM Mail     : alex@sbpi.com.br
REM Criacao  : 08/09/2003, 10:30
REM Versao   : 1.0.0.0
REM Local    : Brasília - DF
REM -------------------------------------------------------------------------
REM
REM Parâmetros recebidos:
REM    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
REM    O (operação)   = I   : Inclusão
REM                   = A   : Alteração
REM                   = C   : Cancelamento
REM                   = E   : Exclusão
REM                   = L   : Listagem
REM                   = P   : Pesquisa
REM                   = D   : Detalhes
REM                   = N   : Nova solicitação de envio

' Verifica se o usuário está autenticado
If Session("LogOn") <> "Sim" Then
   EncerraSessao
End If

' Declaração de variáveis
Dim dbms, sp, RS, RS1, RS2, RS3
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Pagina, w_Disabled, w_TP, w_troca, w_cor, w_Dir
Dim w_ContOut
Dim w_Titulo
Dim w_Imagem
Dim w_ImagemPadrao
Dim w_Assinatura, w_Cliente, w_Classe, w_filter
Private Par, w_linha, w_pag

AbreSessao

' Carrega variáveis locais com os dados dos parâmetros recebidos
Par          = ucase(Request("Par"))
P1           = Request("P1")
P2           = Request("P2")
P3           = Request("P3")
P4           = Request("P4")
TP           = Request("TP")
SG           = ucase(Request("SG"))
R            = uCase(Request("R"))
O            = uCase(Request("O"))
w_troca      = Request("w_troca")
w_Assinatura = uCase(Request("w_Assinatura"))
w_Pagina     = "Rel_Reprocessar.asp?par="
w_Dir        = "ecw/"
w_Disabled   = "ENABLED"

If P3 = "" Then P3 = 1           Else P3 = cDbl(P3) End If
If P4 = "" Then P4 = conPageSize Else P4 = cDbl(P4) End If

If O = "" Then O = "P" End If

Select Case O
  Case "I" 
     w_TP = TP & " - Inclusão"
  Case "A" 
     w_TP = TP & " - Alteração"
  Case "E" 
     w_TP = TP & " - Exclusão"
  Case "P" 
     w_TP = TP & " - Filtragem"
  Case "D" 
     w_TP = TP & " - Desativar"
  Case "T" 
     w_TP = TP & " - Ativar"
  Case "H" 
     w_TP = TP & " - Herança"
  Case Else
     w_TP = TP & " - Listagem"
End Select

w_cliente         = RetornaCliente()
  
If Request("Regional") > "" Then Session("Regional") = Request("Regional") End If
If Request("Periodo") > ""  Then Session("Periodo") = Request("Periodo")   End If

VerificaParametros

Main

FechaSessao

Set w_pag           = Nothing
Set w_linha         = Nothing
Set w_filter        = Nothing
Set w_cor           = Nothing
Set w_classe        = Nothing
Set w_cliente       = Nothing

Set RS              = Nothing
Set RS1             = Nothing
Set RS2             = Nothing
Set RS3             = Nothing
Set Par             = Nothing
Set P1              = Nothing
Set P2              = Nothing
Set P3              = Nothing
Set P4              = Nothing
Set TP              = Nothing
Set SG              = Nothing
Set R               = Nothing
Set O               = Nothing
Set w_ImagemPadrao  = Nothing
Set w_Imagem        = Nothing
Set w_Titulo        = Nothing
Set w_ContOut       = Nothing
Set w_Cont          = Nothing
Set w_Pagina        = Nothing
Set w_Disabled      = Nothing
Set w_TP            = Nothing
Set w_troca         = Nothing
Set w_Assinatura    = Nothing

REM =========================================================================
REM Rotina de consulta de alunos
REM -------------------------------------------------------------------------
Sub Inicial

  Dim p_arquivo, p_remessa, p_total
  
  p_arquivo          = uCase(Request("p_arquivo"))
  p_remessa          = uCase(Request("p_remessa"))
  p_total            = uCase(Request("p_total")) 
  
  
  Cabecalho
  ShowHTML "<HEAD>"
  ScriptOpen "JavaScript"
  CheckBranco
  FormataData
  ValidateOpen "Validacao"
  Validate "periodo", "Período", "SELECT", "1", "1", "10", "1", "1"
  ShowHTML "if (theForm.p_remessa.value == '' && theForm.p_total.checked == false)"
  ShowHTML "{"
  ShowHTML "  alert('Favor informar um valor para o campo Nº da remessa');"
  ShowHTML "  theForm.p_remessa.focus();"
  ShowHTML "  return (false);"
  ShowHTML "}"
  ShowHTML ""
  ShowHTML "if (theForm.p_remessa.value.length < 1 && theForm.p_remessa.value != '')"
  ShowHTML "{"
  ShowHTML "  alert('Favor digitar pelo menos 1 posições no campo Nº da remessa');"
  ShowHTML "  theForm.p_remessa.focus();"
  ShowHTML "  return (false);"
  ShowHTML "}"
  ShowHTML "if (theForm.p_remessa.value.length > 4 && theForm.p_remessa.value != '')"
  ShowHTML "{"
  ShowHTML "  alert('Favor digitar no máximo 4 posições no campo Nº da remessa');"
  ShowHTML "  theForm.p_remessa.focus();"
  ShowHTML "  return (false);"
  ShowHTML "}"
  ShowHTML "var checkOK = '0123456789';"
  ShowHTML "var checkStr = theForm.p_remessa.value;"
  ShowHTML "var allValid = true;"
  ShowHTML "for (i = 0;  i < checkStr.length;  i++)"
  ShowHTML "{"
  ShowHTML "  ch = checkStr.charAt(i);"
  ShowHTML "  if ((checkStr.charCodeAt(i) != 13) && (checkStr.charCodeAt(i) != 10) && (checkStr.charAt(i) != '\\')) {"
  ShowHTML "     for (j = 0;  j < checkOK.length;  j++) {"
  ShowHTML "       if (ch == checkOK.charAt(j))"
  ShowHTML "         break;"
  ShowHTML "     } "
  ShowHTML "     if (j == checkOK.length)"
  ShowHTML "     {"
  ShowHTML "       allValid = false;"
  ShowHTML "       break;"
  ShowHTML "     }"
  ShowHTML "  }" 
  ShowHTML "}"
  ShowHTML "if (!allValid)"
  ShowHTML "{"
  ShowHTML "alert('Favor digitar apenas números no campo Nº da remessa.');"
  ShowHTML "  theForm.p_remessa.focus();"
  ShowHTML "  return (false);"
  ShowHTML "}"
  ShowHTML " if (theForm.p_total.checked == true && theForm.p_remessa.value != '') {" 
  ShowHTML "    alert('Para reprocessamento total o campo Nº da Remessa deve estar nulo');" 
  ShowHTML "    return (false);"
  ShowHTML " }"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad=document.focus();"
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  
  AbreForm "Form", w_Dir&w_Pagina&"Gerar", "POST", "return(Validacao(this));",null,P1,P2,P3,P4,TP,SG,R,O
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Visualizar</i> para exibir a relação na tela ou sobre <i>Gerar Word</i> para gerar um arquivo no formato Word. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "    <table width=""70%"" border=""0"">"
  ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
  SelecaoPeriodoLetivo "Perío<u>d</u>o letivo:", "D", null, Session("periodo"), null, "periodo", null
  ShowHTML "          <td valign=""top""><font size=""1""><b>Nº da Remessa:<br><input type=""text"" name=""p_remessa"" class=""STS"" size=""4"">"
  ShowHTML "      </tr>"
  ShowHTML "          </table>"
  ShowHTML "      <tr><td valign=""top""><b><font size=""1""><input type=""checkbox"" name=""p_total"" class=""STS"">Reprocessamento total"
  ShowHTML "      </table>"
  ShowHTML "      </tr>"
  ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td align=""center"" colspan=""3"">"
  ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gerar Arquivo"">"
  ShowHTML "          </td>"
  ShowHTML "      </tr>"
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set p_arquivo          = Nothing 
  Set p_remessa          = Nothing 
  Set p_total            = Nothing
  
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina geração do arquivo .INI
REM -------------------------------------------------------------------------
Sub Gerar

   Dim p_arquivo
   Dim p_remessa, URL
   Dim p_total
   
   p_total   = Request("p_total")
   p_remessa = Request("p_remessa")
   If Len(p_remessa) = 1 Then
      p_remessa = "000"&p_remessa
   ElseIf Len(p_remessa) = 2 Then
      p_remessa = "00"&p_remessa
   ElseIf Len(p_remessa) = 3 Then
      p_remessa = "0"&p_remessa
   ElseIf Len(p_remessa) = 4 Then
      p_remessa = p_remessa
   Else
      p_remessa = "0000"
   End If
   p_remessa = Mid(Session("Periodo"),3,2)&p_remessa
   
   p_arquivo =Request.ServerVariables("APPL_PHYSICAL_PATH")&"Files\"&w_cliente&"\reprocessar.ini"
   Const ForReading = 1, ForWriting = 2  
   Dim fso, f
   Set fso = CreateObject("Scripting.FileSystemObject")
   Set f = fso.OpenTextFile(""&p_arquivo&"", ForWriting, True)
   f.WriteLine "[REPROCESSAR]"
   f.WriteLine "REMESSA="&p_remessa
   Set f = fso.OpenTextFile(""&p_arquivo&"", ForReading)
   URL = conFileVirtual&w_cliente&"/reprocessar.ini"
   ScriptOpen "JavaScript"
   ShowHTML " function sair(){"
   ShowHTML " location.href='" & w_Pagina & "Inicial" & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
   ShowHTML " window.open('"& URL & "')"
   ShowHTML " }"
   ShowHTML "sair()"
   ScriptClose

End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  ' Verifica se o usuário tem lotação e localização
  Select Case Par
    Case "INICIAL"
       Inicial
    Case "GERAR"
       Gerar
    Case Else
       Cabecalho
       ShowHTML "<BASE HREF=""" & Request.ServerVariables("server_name") & "/siw/"">"
       BodyOpen "onLoad=document.focus();"
       ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
       ShowHTML "<HR>"
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
       Rodape
  End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>

