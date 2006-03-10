<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Trabalho.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papad�polis
REM Descricao: Gerencia a atualiza��o das tabelas de tipo de salas
REM Mail     : alex@sbpi.com.br
REM Criacao  : 18/08/2003, 11:00
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
REM                   = L   : Listagem
REM                   = P   : Pesquisa
REM                   = D   : Detalhes
REM                   = N   : Nova solicita��o de envio

' Verifica se o usu�rio est� autenticado
If Session("LogOn") <> "Sim" Then
   EncerraSessao
End If

' Declara��o de vari�veis
Dim dbms, sp, RS, RS1, RS2, RS3
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Pagina, w_Disabled, w_TP, w_troca, w_cor, w_Dir
Dim w_ContOut
Dim w_Titulo
Dim w_Imagem
Dim w_ImagemPadrao
Dim w_Assinatura, w_Cliente, w_Classe, w_filter
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
w_troca      = Request("w_troca")
w_Assinatura = uCase(Request("w_Assinatura"))
w_Pagina     = "Trabalho.asp?par="
w_Dir        = "ecw/"
w_Disabled   = "ENABLED"

If P3 = "" Then P3 = 1           Else P3 = cDbl(P3) End If
If P4 = "" Then P4 = conPageSize Else P4 = cDbl(P4) End If

If O = "" Then O = "L" End If

Select Case O
  Case "I" 
     w_TP = TP & " - Inclus�o"
  Case "A" 
     w_TP = TP & " - Altera��o"
  Case "E" 
     w_TP = TP & " - Exclus�o"
  Case "P" 
     w_TP = TP & " - Filtragem"
  Case "D" 
     w_TP = TP & " - Desativar"
  Case "T" 
     w_TP = TP & " - Ativar"
  Case "H" 
     w_TP = TP & " - Heran�a"
  Case Else
     w_TP = TP & " - Listagem"
End Select

w_cliente         = RetornaCliente()
  
Main

FechaSessao

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
REM Rotina de sele��o de per�odo e regional
REM -------------------------------------------------------------------------
Sub Selecao

  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpenImage "onLoad=document.focus();", LinkArquivo(null, Session("p_cliente"), "img\logo_sge.jpg", null, null, null, "EMBED"), "FIXED"
  

  ShowHTML "<B><FONT COLOR=""#000000"">" & TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<blockquote><div align=justify><font size=2><b>"
  ShowHTML "<p align=""center""><font size=3>Bem-vindo ao Sistema de Gest�o Escolar - SGE Corporativo.</font><p>"
  ShowHTML "O SGE - Corporativo comp�e a Solu��o Integrada de Gest�o Educacional para o Distrito Federal, como parte desta solu��o, permite implementar, de forma automatizada, todo o controle acad�mico dos Estabelecimentos de Ensino, de acordo com a nova LDB (Lei de Diretrizes e Bases) e o Regimento Educacional em vigor."
  ShowHTML "O SGE � Corporativo, por medida de seguran�a, possui um controle de n�vel de acesso de seus usu�rios, visando a integridade e o sigilo de suas informa��es, de acordo com o n�vel de acesso do usu�rio o SGE - Corporativo permite:"
  ShowHTML "<ul><li>Manter as informa��es corporativas de responsabilidade da secretaria de educa��o."
  ShowHTML "<li>Consultar as informa��es de todas as unidades de ensino da secretaria de educa��o."
  ShowHTML "<li>Consultar as informa��es de servidores e professores ligados � secretaria de educa��o, inclusive disponibilizando a grade hor�ria do professor, possibilitando assim uma melhor adequa��o dos hor�rios e locais de trabalho dos mesmos."
  ShowHTML "<li>Consultar as informa��es acad�micas de desempenho e freq��ncia dos alunos, disponibilizando tamb�m as informa��es de seus respons�veis."
  ShowHTML "<li>Visualizar e imprimir relat�rios com informa��es das unidades de ensino, servidores, professores, alunos e seus respons�veis, tendo como caracter�stica a flexibilidade e um grande n�mero de par�metros, contribuindo para o detalhamento das informa��es de acordo com a necessidade."
  ShowHTML "</ul></p>"

  ShowHTML "</font><br><br>"
  ShowHTML "</div></blockquote"
  Rodape

End Sub
REM =========================================================================
REM Fim da tela de apresenta��o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  ' Verifica se o usu�rio tem lota��o e localiza��o
  Select Case Par
    Case "SELECAO"
       Selecao
    Case Else
       Cabecalho
       ShowHTML "<BASE HREF=""" & Request.ServerVariables("server_name") & "/siw/"">"
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

