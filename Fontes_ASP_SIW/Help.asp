<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DB_Help.asp" -->
<!-- #INCLUDE FILE="DB_Tabela_SIW.asp" -->
<!-- #INCLUDE FILE="DB_Seguranca.asp" -->
<!-- #INCLUDE FILE="jScript.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Help.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia o módulo de demandas
REM Mail     : alex@sbpi.com.br
REM Criacao  : 15/10/2003 12:25
REM Versao   : 1.0.0.0
REM Local    : Brasília - DF
REM -------------------------------------------------------------------------
REM
REM Parâmetros recebidos:
REM    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
REM    O (operação)   = L   : Listagem
REM                   = P   : Filtragem
REM                   = V   : Geração de gráfico
REM                   = W   : Geração de documento no formato MS-Word (Office 2003)

' Verifica se o usuário está autenticado
If Session("LogOn") <> "Sim" Then
   EncerraSessao
End If

' Declaração de variáveis
Dim dbms, sp, RS, RS1, RS2, RS3, RS4
Dim P1, P2, P3, P4, TP, SG, FS, w_ImagemPadrao
Dim R, O, w_Pagina, w_Disabled, w_TP
Dim w_Assinatura, w_imagem
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu
Dim w_submenu
Dim w_modulo, w_segmento, w_sq_modulo
Dim w_nivel, w_cont1, w_cont2, w_cont3, w_cont4
Dim w_dir_volta
Set RS  = Server.CreateObject("ADODB.RecordSet")
Set RS1 = Server.CreateObject("ADODB.RecordSet")
Set RS2 = Server.CreateObject("ADODB.RecordSet")
Set RS3 = Server.CreateObject("ADODB.RecordSet")
Set RS4 = Server.CreateObject("ADODB.RecordSet")

w_troca            = Request("w_troca")
  
Private Par

AbreSessao

' Carrega variáveis locais com os dados dos parâmetros recebidos
Par          = ucase(Request("Par"))
P1           = Nvl(Request("P1"),0)
P2           = Nvl(Request("P2"),0)
P3           = cDbl(Nvl(Request("P3"),1))
P4           = cDbl(Nvl(Request("P4"),conPagesize))
TP           = Request("TP")
SG           = ucase(Request("SG"))
R            = uCase(Request("R"))
O            = uCase(Request("O"))
w_Assinatura = uCase(Request("w_Assinatura"))

w_Pagina     = "Help.asp?par="
w_Disabled   = "ENABLED"

If O = "" Then O = "L" End If

w_TP = TP

w_cliente         = RetornaCliente()
w_usuario         = RetornaUsuario()
w_menu            = RetornaMenu(w_cliente, SG)

Main

FechaSessao

Set w_sq_modulo   = Nothing
Set w_submenu     = Nothing
Set w_nivel       = Nothing
Set w_cont1       = Nothing
Set w_cont2       = Nothing
Set w_cont3       = Nothing
Set w_cont4       = Nothing
Set w_menu        = Nothing
Set w_modulo      = Nothing
Set w_segmento    = Nothing
Set w_usuario     = Nothing
Set w_cliente     = Nothing
Set w_filter      = Nothing
Set w_cor         = Nothing
Set w_troca       = Nothing

Set RS            = Nothing
Set RS1           = Nothing
Set RS2           = Nothing
Set Par           = Nothing
Set P1            = Nothing
Set P2            = Nothing
Set P3            = Nothing
Set P4            = Nothing
Set TP            = Nothing
Set SG            = Nothing
Set FS            = Nothing
Set R             = Nothing
Set O             = Nothing
Set w_Pagina      = Nothing
Set w_Disabled    = Nothing
Set w_TP          = Nothing
Set w_Assinatura  = Nothing

REM =========================================================================
REM Pesquisa gerencial
REM -------------------------------------------------------------------------
Sub Help
  
  Dim w_nome_modulo, w_objetivo_geral, w_objetivo_espec, RS_Tramite
  
  w_sq_modulo = Request("w_sq_modulo")

  If w_sq_modulo = "" Then
     DB_GetLinkData RS, w_cliente, SG
     w_modulo = RS("sq_modulo")
  Else
     w_modulo = w_sq_modulo
  End If

  DB_GetModData RS, w_modulo
  w_nome_modulo    = RS("Nome")
  w_objetivo_geral = RS("objetivo_geral")

  DB_GetCustomerData RS, w_cliente
  w_segmento = RS("sq_segmento")

  DB_GetSegModData RS, w_segmento, w_modulo
  If Not RS.Eof Then
     w_objetivo_espec = RS("objetivo_especif")
  Else
     w_objetivo_espec = "Não informado"
  End If

  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & w_TP & "</TITLE>"
  ShowHTML "</HEAD>"
  BodyOpenClean "onLoad=document.focus();"
  If O = "L" Then
     ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</font></B>"
     ShowHTML "<HR>"
  End If
  ShowHTML "<div align=center><center>"
  If w_sq_modulo > "" Then
     ShowHTML "<center><B>Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</b></center>"
  End If

  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  If O = "L" Then
     ShowHTML "      <tr valign=""top""><td colspan=2>"
     ShowHTML "         <font face=""Arial"" size=""3""><b>Módulo: " & w_nome_modulo & "</font></b>"
     ShowHTML "         <font size=""2""><DL>"
     ShowHTML "         <DT><b>Objetivo geral:</b>"
     ShowHTML "         <DD>" & w_objetivo_geral
     ShowHTML "         <DT><br><b>Objetivo(s) específico(s):</b>"
     ShowHTML "         <DD><UL><LI>" & replace(w_objetivo_espec,VbCrLf,"<LI>") & "</UL>"
     ShowHTML "      <tr><td><BR>"
     ShowHTML "      <tr align=""center"" valign=""top""><td><td bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""2""><b>Funcionalidades</td>"
     DB_GetLinkDataHelp RS, w_cliente, w_modulo, 0, "IS NULL"
     ShowHTML "      <tr valign=""top""><td colspan=2><font size=2><br>"
     If Rs.EOF Then
        ShowHTML "      <b>Não há funcionalidades disponíveis.</b>"
     Else
        w_cont1 = 0
        While Not RS.EOF
           w_nivel = 1
           w_cont1 = w_cont1 + 1
           w_cont2 = 0
           w_cont3 = 0
           w_cont4 = 0
           ShowHTML "         <DL><DT><b>" & w_cont1 & ". "& RS("nome") & "</b>"
           ShowHTML "             <DD>Finalidade: " & ExibeTexto(RS("finalidade"))
           If RS("tramite") = "S" Then
               ShowHTML "        <DD><BR>Como funciona: " & ExibeTexto(RS("como_funciona"))
           End If
           If cDbl(RS("Filho")) > 0 Then
              DB_GetLinkDataHelp RS1, w_cliente, w_modulo, 0, RS("sq_menu")
              
              If RS1("ultimo_nivel") = "S" Then
                 w_submenu = "S"
                 ShowHTML "             <DD><BR>Telas contidas: "
                 ShowHTML "             <blockquote>"
              End If
              While Not RS1.EOF
                 w_cont2 = w_cont2 + 1
                 w_cont3 = 0
                 w_cont4 = 0
                 ShowHTML "             <DT><BR><b>" & w_cont1 & "." & w_cont2 & ". " & RS1("nome") & "</b>"
                 ShowHTML "             <DD>Finalidade: " & ExibeTexto(RS1("finalidade"))
                 If RS1("tramite") = "S" Then
                     ShowHTML "        <DD><BR>Como funciona: " & ExibeTexto(RS1("como_funciona"))
                     ' Verifica se têm trâmites e exibe
                     DB_GetTramiteList RS_Tramite, RS1("sq_menu"), null
                     RS_Tramite.Sort = "Ordem"
                     If Not RS_Tramite.EOF Then
                        ShowHTML "    <DD><BR>Fases:"
                        ShowHTML "    <DD><TABLE bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
                        ShowHTML "        <tr align=""center"" valign=""top"">"
                        ShowHTML "          <td><b>Ordem</td>"    
                        ShowHTML "          <td><b>Nome</td>"    
                        ShowHTML "          <td><b>Descricao</td>"
                        ShowHTML "          <td><b>Quem cumpre</td>"
                        ShowHTML "        </tr>"
                        While Not RS_Tramite.EOF
                          ShowHTML "      <tr valign=""top"">"
                          ShowHTML "        <td align=""center"">" & RS_Tramite("ordem") & "</td>"
                          ShowHTML "        <td>" & RS_Tramite("nome") & "</td>"
                          ShowHTML "        <td>" & Nvl(RS_Tramite("descricao"),"---") & "</td>"
                          ShowHTML "        <td>" & Nvl(RS_Tramite("nm_chefia"),"---") & "</td>"
                          ShowHTML "        </td>"
                          ShowHTML "      </tr>"
                          RS_Tramite.MoveNext
                        wend
                        ShowHTML "    </table>"
                     End If
                     RS_Tramite.Close
                 End If
                 If cDbl(RS1("Filho")) > 0 Then
                    DB_GetLinkDataHelp RS2, w_cliente, w_modulo, 0, RS1("sq_menu")

                    If RS2("ultimo_nivel") = "S" Then
                       w_submenu = "S"
                       ShowHTML "             <DD><BR>Telas contidas: "
                       ShowHTML "             <blockquote>"
                    End If
                    While Not RS2.EOF
                       w_cont3 = w_cont3 + 1
                       w_cont4 = 0
                       If w_submenu = "S" and w_cont3 = 1 Then
                          ShowHTML "             <DT><b>" & w_cont1 & "." & w_cont2 & "." & w_cont3 & ". " & RS2("nome") & "</b>"
                       Else
                          ShowHTML "             <DT><BR><b>" & w_cont1 & "." & w_cont2 & "." & w_cont3 & ". " & RS2("nome") & "</b>"
                       End If
                       ShowHTML "             <DD>Finalidade: " & ExibeTexto(RS2("finalidade"))
                       If RS2("tramite") = "S" Then
                          w_submenu = "S"
                          ShowHTML "        <DD><BR>Como funciona: " & ExibeTexto(RS2("como_funciona"))
                          If RS2("ultimo_nivel") = "S" and w_submenu = "N" Then
                              ' Verifica se têm trâmites e exibe
                              DB_GetTramiteList RS_Tramite, RS2("sq_menu"), null
                              RS_Tramite.Sort = "Ordem"
                              If Not RS_Tramite.EOF Then
                                 ShowHTML "    <DD><BR>Fases:"
                                 ShowHTML "    <DD><TABLE WIDTH=""70%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
                                 ShowHTML "        <tr align=""center"" valign=""top"">"
                                 ShowHTML "          <td><b>Ordem</td>"    
                                 ShowHTML "          <td><b>Nome</td>"    
                                 ShowHTML "          <td><b>Descricao</td>"
                                 ShowHTML "          <td><b>Quem cumpre</td>"
                                 ShowHTML "        </tr>"
                                 While Not RS_Tramite.EOF
                                   ShowHTML "      <tr valign=""top"">"
                                   ShowHTML "        <td align=""center"">" & RS_Tramite("ordem") & "</td>"
                                   ShowHTML "        <td>" & RS_Tramite("nome") & "</td>"
                                   ShowHTML "        <td>" & Nvl(RS_Tramite("descricao"),"---") & "</td>"
                                   ShowHTML "        <td>" & Nvl(RS_Tramite("nm_chefia"),"---") & "</td>"
                                   ShowHTML "        </td>"
                                   ShowHTML "      </tr>"
                                   RS_Tramite.MoveNext
                                 wend
                                 ShowHTML "    </table><br>"
                              End If
                              RS_Tramite.Close
                          End If
                       End If
                       If cDbl(RS2("Filho")) > 0 Then
                          DB_GetLinkDataHelp RS3, w_cliente, w_modulo, 0, RS2("sq_menu")

                          If RS3("ultimo_nivel") = "S" Then
                              w_submenu = "S"
                              ShowHTML "             <DD><BR>Telas contidas: "
                              ShowHTML "             <blockquote>"
                          End If
                          While Not RS3.EOF
                             w_cont4 = w_cont4 + 1
                             ShowHTML "             <DT><BR><b>" & w_cont1 & "." & w_cont2 & "." & w_cont3 & "." & w_cont4 & ". " & RS3("nome") & "</b>"
                             ShowHTML "             <DD>Finalidade: " & ExibeTexto(RS3("finalidade"))
                             If RS3("tramite") = "S" Then
                                 ShowHTML "        <DD><BR>Como funciona: " & ExibeTexto(RS3("como_funciona"))
                             End If
                             If RS3("ultimo_nivel") = "S" and w_submenu = "N" Then
                                 ' Verifica se têm trâmites e exibe
                                 DB_GetTramiteList RS_Tramite, RS3("sq_menu"), null
                                 RS_Tramite.Sort = "Ordem"
                                 If Not RS_Tramite.EOF Then
                                    ShowHTML "    <DD><BR>Fases:"
                                    ShowHTML "    <DD><TABLE WIDTH=""70%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
                                    ShowHTML "        <tr align=""center"" valign=""top"">"
                                    ShowHTML "          <td><b>Ordem</td>"    
                                    ShowHTML "          <td><b>Nome</td>"    
                                    ShowHTML "          <td><b>Descricao</td>"
                                    ShowHTML "          <td><b>Quem cumpre</td>"
                                    ShowHTML "        </tr>"
                                    While Not RS_Tramite.EOF
                                      ShowHTML "      <tr valign=""top"">"
                                      ShowHTML "        <td align=""center"">" & RS_Tramite("ordem") & "</td>"
                                      ShowHTML "        <td>" & RS_Tramite("nome") & "</td>"
                                      ShowHTML "        <td>" & Nvl(RS_Tramite("descricao"),"---") & "</td>"
                                      ShowHTML "        <td>" & Nvl(RS_Tramite("nm_chefia"),"---") & "</td>"
                                      ShowHTML "        </td>"
                                      ShowHTML "      </tr>"
                                      RS_Tramite.MoveNext
                                    wend
                                    ShowHTML "    </table><br>"
                                 End If
                                 RS_Tramite.Close
                             End If
                             RS3.MoveNext
                          Wend
                          If w_submenu = "S" Then
                             ShowHTML "       </blockquote>"
                             w_submenu =  "N"
                          End If
                       End If
                       RS2.MoveNext
                    Wend
                    If w_submenu = "S" Then
                       ShowHTML "       </blockquote>"
                       w_submenu =  "N"
                    End If
                 End If
                 RS1.MoveNext
              Wend
              If w_submenu = "S" Then
                 ShowHTML "       </blockquote>"
                 w_submenu =  "N"
              End If
           End If
           RS.MoveNext
           ShowHTML "         </DL>"
        Wend
        If w_submenu = "S" Then
           ShowHTML "       </blockquote>"
           w_submenu =  "N"
        End If
     End If
     DesconectaBD
     ShowHTML "         </table></td></tr>"
     ShowHTML "     </tr></tr></td></table>"

     ShowHTML "</table>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"

  If w_sq_modulo > "" Then
     ShowHTML "<center><B>Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</b></center>"
  End If

  ShowHTML "</center>"
  Rodape
  
  Set RS_Tramite = Nothing
End Sub
REM =========================================================================
REM Fim da tabela de clientes
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de menu do help
REM -------------------------------------------------------------------------
Sub Menu
  If O = "L" Then
     ' Recupera os módulos contratados pelo cliente
     DB_GetSiwCliModLis RS, w_cliente, null
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "</HEAD>"
  BodyOpen "onLoad='document.focus()';"
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</font></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    AbreSessao
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td align=""right""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><b>Módulo</td>"
    ShowHTML "          <td><b>Objetivo geral</td>"
    ShowHTML "          <td><b>Operações</td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><b>Não foram encontradas despesas adicionais cadastradas.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td nowrap>" & RS("nome") & "</td>"
        ShowHTML "        <td>" & RS("objetivo_geral") & "</td>"
        ShowHTML "        <td align=""top"" nowrap>"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & "Inicial&R=" & w_Pagina & par & "&O=L&w_sq_modulo=" & Rs("sq_modulo") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Detalhar</A>&nbsp"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesConectaBD
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

End Sub
REM =========================================================================
REM Fim da tela de módulos contratados
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  Select Case Par
    Case "INICIAL"
       Help
    Case "MENU"
       Menu
    Case Else
       Cabecalho
       BodyOpen "onLoad=document.focus();"
       ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</font></B>"
       ShowHTML "<HR>"
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
       Rodape
  End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>

