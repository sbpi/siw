<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Tabelas.asp" -->
<!-- #INCLUDE FILE="DB_Dicionario.asp" -->
<!-- #INCLUDE FILE="DML_Dicionario.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<%
Response.Expires     = -1500
Server.ScriptTimeOut = 1000
REM =========================================================================
REM  /DC_Consulta.asp
REM ------------------------------------------------------------------------
REM Nome     : Egisberto Vicente da Silva
REM Descricao: Exibir dicionário
REM Mail     : beto@sbpi.com.br
REM Criacao  : 08/06/2004 14:27
REM Versao   : 1.0.0.0
REM Local    : Brasília - DF
REM -------------------------------------------------------------------------
REM
REM Parâmetros recebidos:
REM    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
REM    O (operação)   = I      : Inclusão
REM                   = A      : Alteração
REM                   = C      : Cancelamento
REM                   = E      : Exclusão
REM                   = L      : Listagem
REM                   = P      : Pesquisa
REM                   = D      : Detalhes
REM                   = N      : Nova solicitação de envio
REM                   = NIVEL2 : Segundo nível de consulta

' Verifica se o usuário está autenticado
If Session("LogOn") <> "Sim" Then
  EncerraSessao
End If

' Declaração de variáveis
Dim dbms, sp, RS, RS1, RS2, RS3, RS4, RS_menu, w_tipo
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Reg, w_Pagina, w_Disabled, w_TP, w_classe, w_submenu, w_filtro, w_copia
Dim w_Assinatura
Dim w_troca, w_cor, w_filter, w_cliente, w_usuario, w_menu, w_dir, w_chave, w_sq_usuario, w_sq_tabela, w_sq_trigger
Dim w_sq_sp, w_sq_indice, w_sq_coluna, w_sq_arquivo, w_sq_procedure, w_sq_relacionamento
Dim w_sq_pessoa
Dim ul,File

w_troca = Request("w_troca")
w_copia = Request("w_copia")
  
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

w_Pagina     = "DC_Consulta.asp?par="
w_Dir        = "mod_dc/"
w_Disabled   = "ENABLED"

If O = "" Then O = "L" End If

Select Case O
  Case "I" 
    w_TP = TP & " - Inclusão"
  Case "A" 
    w_TP = TP & " - Alteração"
  Case "E" 
    w_TP = TP & " - Exclusão"
  Case "P" 
    w_TP = TP & " - Filtragem"
  Case "C"
    w_TP = TP & " - Cópia"
  Case "V" 
    w_TP = TP & " - Envio"
  Case "H" 
    w_TP = TP & " - Herança"
  Case Else
    w_TP = TP & " - Listagem"
End Select

w_cliente = RetornaCliente()
w_usuario = RetornaUsuario()
w_menu    = RetornaMenu(w_cliente, SG)

' Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
DB_GetLinkSubMenu RS, Session("p_cliente"), SG
If RS.RecordCount > 0 Then
  w_submenu = "Existe"
Else
  w_submenu = ""
End If
DesconectaBD

' Recupera a configuração do serviço
If P2 > 0 Then DB_GetMenuData RS_menu, P2 Else DB_GetMenuData RS_menu, w_menu End If
If RS_menu("ultimo_nivel") = "S" Then
  ' Se for sub-menu, pega a configuração do pai
  DB_GetMenuData RS_menu, RS_menu("sq_menu_pai")
End If

Main

FechaSessao

Set w_chave      = Nothing
Set w_copia      = Nothing
Set w_filtro     = Nothing
Set w_menu       = Nothing
Set w_usuario    = Nothing
Set w_cliente    = Nothing
Set w_filter     = Nothing
Set w_cor        = Nothing
Set ul           = Nothing
Set File         = Nothing
Set w_sq_pessoa  = Nothing
Set w_troca      = Nothing
Set w_submenu    = Nothing
Set w_reg        = Nothing

Set RS           = Nothing
Set RS1          = Nothing
Set RS2          = Nothing
Set RS3          = Nothing
Set RS4          = Nothing
Set RS_menu      = Nothing
Set Par          = Nothing
Set P1           = Nothing
Set P2           = Nothing
Set P3           = Nothing
Set P4           = Nothing
Set TP           = Nothing
Set SG           = Nothing
Set R            = Nothing
Set O            = Nothing
Set w_Classe     = Nothing
Set w_Cont       = Nothing
Set w_Pagina     = Nothing
Set w_Disabled   = Nothing
Set w_TP         = Nothing
Set w_Assinatura = Nothing

REM ==========================================================================
REM Rotina de Sistema - Usuário
REM --------------------------------------------------------------------------
Sub Usuario
  Dim w_tab, w_col, w_ind, w_rel, w_trg, w_sp, w_arq, w_prc
  
  w_Chave      = Request("w_Chave")
  w_troca      = Request("w_troca")
  w_sq_usuario = Request("w_sq_usuario")
  
  If O = "L" Then
    ' Recupera todos os registros para a listagem
    DB_GetUsuario RS, w_cliente, w_sq_usuario, w_chave
    RS.Sort = "chave"
    Cabecalho
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    BodyOpen "onLoad='document.focus()';"
    ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
    ShowHTML "<HR>"
    ShowHTML "<table border=0 width=""100%"">"
    ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"" align=""center"">"
    ShowHTML "  <tr><td><font size=""1""><B>Usuários do sistema " & RS("sg_sistema") & " - " & RS("nm_sistema") & "</B>"
    ShowHTML "        <font size=""1""><B>(" & RS.RecordCount & ")</B></td>"
    ShowHTML "  <tr><td colspan=2>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"" valign=""center"">"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Usuário</b></font></td>"
    ShowHTML "          <td colspan=8><font size=""1""><b>Objetos</b></font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Descrição</b></font></td>"
    ShowHTML "        </tr>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"" valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Tab</b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Col</b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Índ</b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Rel</b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Trg</b></font></td>"
    ShowHTML "          <td><font size=""1""><b>SP</b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Arq</b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Prc</b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=2 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      w_tab = 0
      w_col = 0
      w_ind = 0
      w_rel = 0
      w_trg = 0
      w_sp  = 0
      w_arq = 0
      w_prc = 0
      While Not RS.EOF 
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        If cDbl(RS("qt_tabela")) = 0 Then
           ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("qt_tabela"),0) & "&nbsp;&nbsp;</td>"
        Else
           ShowHTML "        <td align=""right""><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_Pagina & "TABELA&R=" & w_Pagina & par & "&O=L&w_chave=" & RS("sq_sistema") & "&w_sq_usuario=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ Title=""Tabelas"">" & FormatNumber(RS("qt_tabela"),0) & "</a>&nbsp;&nbsp;</td>"
        End If
        If cDbl(RS("qt_coluna")) = 0 Then
           ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("qt_coluna"),0) & "&nbsp;&nbsp;</td>"
        Else
           ShowHTML "        <td align=""right""><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_Pagina & "COLUNA&R=" & w_Pagina & par & "&O=L&w_chave=" & w_chave & "&w_sq_usuario=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""COLUNAS""          >" & FormatNumber(RS("qt_coluna"),0) & "</a>&nbsp;&nbsp;</td>"
        End If
        If cDbl(RS("qt_indice")) = 0 Then
           ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("qt_indice"),0) & "&nbsp;&nbsp;</td>"
        Else
           ShowHTML "        <td align=""right""><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_Pagina & "INDICE&R=" & w_Pagina & par & "&O=L&w_chave=" & w_chave & "&w_sq_usuario=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""INDICES""          >" & FormatNumber(RS("qt_indice"),0) & "</a>&nbsp;&nbsp;</td>"
        End If
        If cDbl(RS("qt_relacionamento")) = 0 Then
           ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("qt_relacionamento"),0) & "&nbsp;&nbsp;</td>"
        Else
           ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("qt_relacionamento"),0) & "</a>&nbsp;&nbsp;</td>"
        End If
        If cDbl(RS("qt_trigger")) = 0 Then
           ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("qt_trigger"),0) & "&nbsp;&nbsp;</td>"
        Else
           ShowHTML "        <td align=""right""><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_Pagina & "TRIGGER&R=" & w_Pagina & par & "&O=L&w_chave=" & w_chave & "&w_sq_usuario=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""TRIGGERS""         >" & FormatNumber(RS("qt_trigger"),0) & "</a>&nbsp;&nbsp;</td>"
        End If
        If cDbl(RS("qt_sp")) = 0 Then
           ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("qt_sp"),0) & "&nbsp;&nbsp;</td>"
        Else
           ShowHTML "        <td align=""right""><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_Pagina & "STOREDPROCEDURE&R=" & w_Pagina & par & "&O=L&w_chave=" & w_chave & "&w_sq_usuario=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""STOREDPROCEDURES"" >" & FormatNumber(RS("qt_sp"),0) & "</a>&nbsp;&nbsp;</td>"
        End If
        If cDbl(RS("qt_arquivo")) = 0 Then
           ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("qt_arquivo"),0) & "&nbsp;&nbsp;</td>"
        Else
           ShowHTML "        <td align=""right""><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_Pagina & "ARQUIVO&R=" & w_Pagina & par & "&O=L&w_chave=" & w_chave & "&w_sq_usuario=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""ARQUIVOS""         >" & FormatNumber(RS("qt_arquivo"),0) & "</a>&nbsp;&nbsp;</td>"
        End If
        If cDbl(RS("qt_procedure")) = 0 Then
           ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("qt_procedure"),0) & "&nbsp;&nbsp;</td>"
        Else
           ShowHTML "        <td align=""right""><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_Pagina & "PROCEDURE&R=" & w_Pagina & par & "&O=L&w_chave=" & w_chave & "&w_sq_usuario=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""PROCEDURES""       >" & FormatNumber(RS("qt_procedure"),0) & "</a>&nbsp;&nbsp;</td>"
        End If
        ShowHTML "        <td><font size=""1"">" & RS("descricao") & "</td>"
        ShowHTML "      </tr>"
        w_tab = w_tab + cDbl(RS("qt_tabela"))
        w_col = w_col + cDbl(RS("qt_coluna"))
        w_ind = w_ind + cDbl(RS("qt_indice"))
        w_rel = w_rel + cDbl(RS("qt_relacionamento"))
        w_trg = w_trg + cDbl(RS("qt_trigger"))
        w_sp  = w_sp  + cDbl(RS("qt_sp"))
        w_arq = cDbl(RS("qt_arquivo"))
        w_prc = cDbl(RS("qt_procedure"))
        RS.MoveNext
      wend
      If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
      ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
      ShowHTML "        <td align=""right""><font size=""1""><b>Totais</td>"
      If cDbl(w_tab) = 0 Then
         ShowHTML "        <td align=""right""><font size=""1""><b>" & FormatNumber(w_tab,0) & "&nbsp;&nbsp;</td>"
      Else
         ShowHTML "        <td align=""right""><font size=""1""><b><A class=""HL"" HREF=""" & w_dir & w_Pagina & "TABELA&R="          & w_Pagina & par & "&O=L&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""TABELAS""          >" & FormatNumber(w_tab,0) & "</a>&nbsp;&nbsp;</td>"
      End If
      If cDbl(w_col) = 0 Then
         ShowHTML "        <td align=""right""><font size=""1""><b>" & FormatNumber(w_col,0) & "&nbsp;&nbsp;</td>"
      Else
         ShowHTML "        <td align=""right""><font size=""1""><b><A class=""HL"" HREF=""" & w_dir & w_Pagina & "COLUNA&R="          & w_Pagina & par & "&O=L&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""COLUNAS""          >" & FormatNumber(w_col,0) & "</a>&nbsp;&nbsp;</td>"
      End If
      If cDbl(w_ind) = 0 Then
         ShowHTML "        <td align=""right""><font size=""1""><b>" & FormatNumber(w_ind,0) & "&nbsp;&nbsp;</td>"
      Else
         ShowHTML "        <td align=""right""><font size=""1""><b><A class=""HL"" HREF=""" & w_dir & w_Pagina & "INDICE&R="          & w_Pagina & par & "&O=L&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""INDICES""          >" & FormatNumber(w_ind,0) & "</a>&nbsp;&nbsp;</td>"
      End If
      If cDbl(w_rel) = 0 Then
         ShowHTML "        <td align=""right""><font size=""1""><b>" & FormatNumber(w_rel,0) & "&nbsp;&nbsp;</td>"
      Else
         ShowHTML "        <td align=""right""><font size=""1""><b>" & FormatNumber(w_rel,0) & "</a>&nbsp;&nbsp;</td>"
      End If
      If cDbl(w_trg) = 0 Then
         ShowHTML "        <td align=""right""><font size=""1""><b>" & FormatNumber(w_trg,0) & "&nbsp;&nbsp;</td>"
      Else
         ShowHTML "        <td align=""right""><font size=""1""><b><A class=""HL"" HREF=""" & w_dir & w_Pagina & "TRIGGER&R="         & w_Pagina & par & "&O=L&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""TRIGGERS""         >" & FormatNumber(w_trg,0) & "</a>&nbsp;&nbsp;</td>"
      End If
      If cDbl(w_sp) = 0 Then
         ShowHTML "        <td align=""right""><font size=""1""><b>" & FormatNumber(w_sp,0) & "&nbsp;&nbsp;</td>"
      Else
         ShowHTML "        <td align=""right""><font size=""1""><b><A class=""HL"" HREF=""" & w_dir & w_Pagina & "STOREDPROCEDURE&R=" & w_Pagina & par & "&O=L&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""STOREDPROCEDURES"" >" & FormatNumber(w_sp,0) & "</a>&nbsp;&nbsp;</td>"
      End If
      If cDbl(w_arq) = 0 Then
         ShowHTML "        <td align=""right""><font size=""1""><b>" & FormatNumber(w_arq,0) & "&nbsp;&nbsp;</td>"
      Else
         ShowHTML "        <td align=""right""><font size=""1""><b><A class=""HL"" HREF=""" & w_dir & w_Pagina & "ARQUIVO&R="         & w_Pagina & par & "&O=L&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""ARQUIVOS""         >" & FormatNumber(w_arq,0) & "</a>&nbsp;&nbsp;</td>"
      End If
      If cDbl(w_prc) = 0 Then
         ShowHTML "        <td align=""right""><font size=""1""><b>" & FormatNumber(w_prc,0) & "&nbsp;&nbsp;</td>"
      Else
         ShowHTML "        <td align=""right""><font size=""1""><b><A class=""HL"" HREF=""" & w_dir & w_Pagina & "PROCEDURE&R="       & w_Pagina & par & "&O=L&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""PROCEDURES""       >" & FormatNumber(w_prc,0) & "</a>&nbsp;&nbsp;</td>"
      End If
      ShowHTML "        <td><font size=""1"">&nbsp;</td>"
      ShowHTML "      </tr>"
      ShowHTML "    </table>"
      ShowHTML "  </td>"
      ShowHTML "</tr>"
      DesconectaBD
    End If
    ShowHTML "  <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "    <td colspan=2><font size=""1""><b>Legenda:</b>"
    ShowHTML "      <ul>"
    ShowHTML "      <li>Tab: tabelas"
    ShowHTML "      <li>Col: colunas"
    ShowHTML "      <li>Ind: índices"
    ShowHTML "      <li>Rel: relacionamentos"
    ShowHTML "      <li>Trg: triggers"
    ShowHTML "      <li>SP: stored procedures (funções e procedures)"
    ShowHTML "      <li>Arq: arquivos físicos (.asp, .java, .pas etc.)"
    ShowHTML "      <li>Prc: procedures contidas nos arquivos físicos"
    ShowHTML "      </ul>"
    ShowHTML "    </td>"
    ShowHTML "  </tr>"
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape
  
  Set w_chave      = Nothing 
  Set w_troca      = Nothing
  Set w_sq_usuario = Nothing  
  
End Sub
REM =========================================================================
REM Fim da rotina Sistema - Usuários
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de Sistema - Tabela
REM -------------------------------------------------------------------------
Sub Tabela
   
  w_Chave      = Request("w_Chave")
  w_troca      = Request("w_troca")
  w_sq_tabela  = Request("w_sq_tabela")
  w_sq_usuario = Request("w_sq_usuario")
  w_sq_relacionamento = Request("w_sq_relacionamento")
   
  If O = "L" Then
    DB_GetTabela RS, w_cliente, null, null, w_chave, w_sq_usuario, null, null, null
    RS.Sort = "nm_usuario, nome"
    Cabecalho
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    BodyOpen "onLoad='document.focus()';"
    ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
    ShowHTML "<HR>"
    ShowHTML "<table border=0 width=""100%"">"
    ShowHTML "  <tr>"
    ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"" align=""center"">"
    If Nvl(w_sq_usuario,"nulo") = "nulo" Then
       ShowHTML "    <td><font size=""1""><B>Tabelas do Sistema " & RS("sg_sistema") & " - " & RS("nm_sistema") & "</B></td>"
    Else
       ShowHTML "    <td><font size=""1""><B>Tabelas do Usuário " & RS("nm_usuario") & "</B>"
    End If
    ShowHTML "        <font size=""1""><B>(" & RS.RecordCount & ")</B></td>"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Tabela    </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Tipo      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Descrição </b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=3 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & "TABELA&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_tabela="& RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Nome"">"&lCase(RS("nm_usuario")&"."&RS("nome"))&"</A>&nbsp"
        ShowHTML "        <td><font size=""1"">" & RS("nm_tipo")   & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("descricao") & "</td>"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        ShowHTML "      </center>"
        RS.MoveNext
      wend
    End If
    
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    If R > "" Then
      MontaBarra w_dir&w_pagina&par&"&R="&R&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
    Else
      MontaBarra w_dir&w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
    End If
    ShowHTML "</tr>"
    DesconectaBD
    
  ElseIf O = "NIVEL2" Then
    DB_GetTabela RS, w_cliente, w_sq_tabela, null, w_chave, w_sq_usuario, null, null, null
    RS.Sort = "chave"
    Cabecalho
    ShowHTML "<HEAD>"
    ShowHTML "<TITLE>" & conSgSistema & " - Dicionário</TITLE>"
    ShowHTML "</HEAD>"
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    BodyOpen "onLoad='document.focus()';"
    ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
    ShowHTML "<HR>"
    ShowHTML "<div align=center><center>"
    ShowHTML "<td><font size=""1""><B>Dados da Tabela</B></td>"
    ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
    ShowHTML "<tr><td align=""center"" bgcolor=""#FAEBD7"" colspan=3><table border=1 width=""100%""><tr><td>"
    ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "          <td><font size=""1"">Nome:      <br><b>"& RS("nome")       &"</td>"
    ShowHTML "          <td><font size=""1"">Tipo:      <br><b>"& RS("nm_tipo")    &"</td>"
    ShowHTML "          <td><font size=""1"">Descrição: <br><b>"& RS("descricao")  &"</td>"
    ShowHTML "          <td><font size=""1"">Usuário:   <br><b>"& RS("nm_usuario") &"</td>"
    ShowHTML "          <td><font size=""1"">Sistema:   <br><b>"& RS("nm_sistema") &"</td>"
    ShowHTML "    </TABLE>"
    ShowHTML "  </TABLE>"
    ShowHTML "</TABLE>"
    ShowHTML "</font>"
    DesconectaBD
    
    ShowHTML "<tr><td><HR>"
    ShowHTML "<tr><td><table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%""><tr><td>"
    ShowHTML ExibeColuna(w_sq_usuario, w_sq_tabela, "ordem")
    ShowHTML "  </table></td></tr>"
    
    ShowHTML "<tr><td><HR>"
    ShowHTML "<tr><td><table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%""><tr><td>"
    ShowHTML ExibeIndice(null, w_sq_tabela, "nm_indice")
    ShowHTML "  </table></td></tr>"

    DB_GetRelacionamento RS, w_cliente, null, null, w_sq_tabela, w_chave, null
    RS.Sort = "nm_relacionamento"
    Cabecalho
    ShowHTML "<tr><td><HR>"
    ShowHTML "<tr><td><table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%""><tr><td>"
    ShowHTML "<tr><td><font size=""1""><B>Relacionamentos (" & RS.RecordCount & ")</B></td>"
    ShowHTML "<tr><td><TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Relacionamento </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Tabela        </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Referenciada</b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=3 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      w_cor=""
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td nowrap><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_Pagina & "RELACIONAMENTO&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_tabela="& RS("tabela_filha") & "&w_sq_relacionamento="& RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"">"&lCase(RS("nm_relacionamento"))&"</A>&nbsp"
        If cDbl(w_sq_tabela) = cDbl(RS("tabela_filha")) Then
           ShowHTML "        <td nowrap><font size=""1"">"&RS("nm_usuario_tab_filha")&"."&RS("nm_tabela_filha")&"</font></td>"
        Else
           ShowHTML "        <td nowrap><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_Pagina & "TABELA&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_tabela="& RS("tabela_filha") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"">"&lCase(RS("nm_usuario_tab_filha")&"."&RS("nm_tabela_filha"))&"</A>&nbsp"
        End If
        If cDbl(w_sq_tabela) = cDbl(RS("tabela_pai")) Then
           ShowHTML "        <td nowrap><font size=""1"">"&RS("nm_usuario_tab_pai")&"."&RS("nm_tabela_pai")&"</font></td>"
        Else
           ShowHTML "        <td nowrap><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_Pagina & "TABELA&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_tabela="& RS("tabela_pai") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"">"&lCase(RS("nm_usuario_tab_pai")&"."&RS("nm_tabela_pai"))&"</A>&nbsp"
        End If
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    ShowHTML "</table>"
    DesconectaBD
    
    ShowHTML "<tr><td><HR>"
    ShowHTML "<tr><td><table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%""><tr><td>"
    ShowHTML ExibeTrigger (null, w_sq_usuario, w_sq_tabela, "nm_trigger")
    ShowHTML "  </table></td></tr>"
    w_cor=""
    
    DB_GetSPTabs RS, null, w_sq_tabela
    RS.Sort = "nm_sp_tipo, nm_usuario, nome"
    Cabecalho
    ShowHTML "<tr><td><HR>"
    ShowHTML "<tr><td><table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%""><tr><td>"
    ShowHTML "<tr><td><font size=""1""><B>Stored Procedures (" & RS.RecordCount & ")</B></td>"
    ShowHTML "<tr><td><TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nome      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Tipo </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Descrição </b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=3 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td nowrap><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_Pagina & "STOREDPROCEDURE&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_sp="& RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Stored procedure"">"&lCase(RS("nm_usuario")&"."&RS("nome"))&"</A>&nbsp"
        ShowHTML "        <td><font size=""1"">" & RS("nm_sp_tipo") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("descricao") & "</td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    ShowHTML "</table>"
    DesconectaBD
    
    DB_GetProcTabela RS, null, w_sq_tabela
    RS.Sort = "chave"
    Cabecalho
    ShowHTML "<tr><td><HR>"
    ShowHTML "<tr><td><table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%""><tr><td>"
    ShowHTML "<tr><td><font size=""1""><B>Procedures (" & RS.RecordCount & ")</B></td>"
    ShowHTML "<tr><td><TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nome      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Descrição </b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=2 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      w_cor=""   
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nm_procedure") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("ds_procedure") & "</td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    ShowHTML "</table>"
    DesconectaBD
  End If
  
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape
  
  Set w_chave      = Nothing 
  Set w_troca      = Nothing
  Set w_sq_tabela  = Nothing
  Set w_sq_usuario = Nothing
  
End Sub
REM =========================================================================
REM Fim da rotina tabela de tabelas
REM -------------------------------------------------------------------------

REM ==========================================================================
REM Rotina de Sistema - Triggers
REM --------------------------------------------------------------------------
Sub Trigger
  
  w_Chave      = Request("w_Chave")
  w_troca      = Request("w_troca")
  w_sq_usuario = Request("w_sq_usuario")
  w_sq_tabela  = Request("w_sq_tabela")
  w_sq_trigger = Request("w_sq_trigger")
  
  If O = "L" Then
    DB_GetTrigger RS, w_cliente, w_sq_trigger, w_sq_tabela, w_sq_usuario, w_chave
    RS.Sort = "nm_usuario, nm_trigger, nm_tabela"
    Cabecalho
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    BodyOpen "onLoad='document.focus()';"
    ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
    ShowHTML "<HR>"
    ShowHTML "<div align=center>"
    ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"" align=""center"">"
    ShowHTML "<tr><td><font size=""1""><B>Triggers (" & RS.RecordCount & ")</B></td>"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nome      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Tabela    </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Eventos de disparo</b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Descrição </b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=3 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF 
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"&RS("nm_trigger")&"</font></td>"
        ShowHTML "        <td nowrap><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_Pagina & "TABELA&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_tabela="& RS("sq_tabela") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"">"&lCase(RS("nm_usuario")&"."&RS("nm_tabela"))&"</A>&nbsp"
        If RS("eventos") > "" Then
           ShowHTML "        <td><font size=""1"">" & RS("eventos") & "</td>"
        Else
           ShowHTML "        <td align=""center""><font size=""1"">---</td>"
        End If
        ShowHTML "        <td><font size=""1"">" & RS("ds_trigger") & "</td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
      ShowHTML "    </table>"
      ShowHTML "  </td>"
      ShowHTML "</tr>"
      DesconectaBD
    End If
  End If
  Set w_chave      = Nothing 
  Set w_troca      = Nothing
  Set w_sq_trigger = Nothing
  Set w_sq_tabela  = Nothing
  Set w_sq_usuario = Nothing        
  
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de Sistema - Stored Procedure
REM -------------------------------------------------------------------------
Sub StoredProcedure
  Dim w_tipo, w_chave_aux
    
  w_Chave     = Request("w_Chave")
  w_Chave_aux = Request("w_Chave_aux")
  w_troca     = Request("w_troca")
  w_sq_sp     = Request("w_sq_sp")
  w_sq_usuario= Request("w_sq_usuario")
  
  If O = "L" Then
    DB_GetStoredProcedure RS, w_cliente, null, null, null, w_sq_usuario, w_chave, null, null
    RS.Sort = "nm_usuario, nm_sp_tipo, nm_sp"
    Cabecalho
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    BodyOpen "onLoad='document.focus()';"
    ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
    ShowHTML "<HR>"
    ShowHTML "<div align=center>"
    ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"" align=""center"">"
    ShowHTML "<td><font size=""1""><B>StoredProcedures (" & RS.RecordCount & ")</B></td>"
    ShowHTML "<tr><td align=""center"" colspan=3></tr>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nome      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Tipo      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>descrição </b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=3 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & "STOREDPROCEDURE&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_sp=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Nome"">"&lCase(RS("nm_usuario")&"."&RS("nm_sp"))&"</A>&nbsp"
        ShowHTML "        <td><font size=""1"">" & RS("nm_sp_tipo") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("ds_sp")      & "</td>"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "<tr><div align=""center"" colspan=3>"
    If R > "" Then
      MontaBarra w_dir&w_pagina&par&"&R="&R&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
    Else
      MontaBarra w_dir&w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
    End If
    ShowHTML "</tr>"
    DesconectaBD
    
  ElseIf O = "NIVEL2" Then
    DB_GetStoredProcedure RS, w_cliente, w_sq_sp, null, null, w_sq_usuario, w_chave, null, null
    RS.Sort = "chave"
    ShowHTML "<HEAD>"
    ShowHTML "<TITLE>" & conSgSistema & " - Dicionário</TITLE>"
    ShowHTML "</HEAD>"
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    BodyOpen "onLoad='document.focus()';"
    ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
    ShowHTML "<HR>"
    ShowHTML "<div align=center><center>"
    ShowHTML "<td><font size=""1""><B>Dados da Stored Procedure</B></td>"
    ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
    ShowHTML "<tr><td align=""center"" bgcolor=""#FAEBD7"" colspan=3><table border=1 width=""100%""><tr><td>"
    ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "          <td><font size=""1"">Nome:      <br><b>" & RS("nm_sp")      & "</td>"
    ShowHTML "          <td><font size=""1"">Tipo:      <br><b>" & RS("nm_sp_tipo") & "</td>"
    ShowHTML "          <td><font size=""1"">Descrição: <br><b>" & RS("ds_sp")      & "</td>"
    ShowHTML "          <td><font size=""1"">Usuário:   <br><b>" & RS("nm_usuario") & "</td>"
    ShowHTML "          <td><font size=""1"">Sistema:   <br><b>" & RS("nm_sistema") & "</td>"
    ShowHTML "    </TABLE>"
    ShowHTML "  </TABLE>"
    ShowHTML "</TABLE>"
    ShowHTML "</font>"
    Rodape
    DesconectaBD
    
    w_cor=""
    
    DB_GetSpParametro RS, w_sq_sp, null, null
    RS.Sort = "ord_sp_param"
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    ShowHTML "<HR>"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "<div align=center><center>"
    ShowHTML "<td><font size=""1""><B>Parâmetros</B></td>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Parâmetro </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Tipo      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>IN OUT    </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Descrição </b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=4 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nm_sp_param") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nm_dado_tipo") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nm_tipo_param") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("ds_sp_param") & "</td>"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "</table>"
    ShowHTML "</center>"
    DesconectaBD  

    w_cor=""
    
    DB_GetSpTabs RS, w_sq_sp, null
    RS.Sort = "chave"
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    ShowHTML "<HR>"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "<div align=center><center>"
    ShowHTML "<td><font size=""1""><B>Tabelas</B></td>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nome      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Descrição </b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=2 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nm_tabela") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("ds_tabela") & "</td>"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "</table>"
    ShowHTML "</center>"
    DesconectaBD  
    
    w_cor=""
    
    DB_GetSpSP RS, w_sq_sp, w_chave_aux
    RS.Sort = "nm_usuario_pai, nm_pai, nm_usuario_filha, nm_filha"
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    ShowHTML "<HR>"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "<div align=center><center>"
    ShowHTML "<td><font size=""1""><B>Relacionamentos</B></td>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>SP Pai             </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>SP Filha           </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Descrição outra SP </b></font></td>"
    ShowHTML "        </tr>"
    if RS.EOF Then ' Se não esistirem registros, exibe mensagem
       ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=3 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    else
      'Lista os registros selecionados para listagem
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        If Nvl(RS("nm_filha"),"") > "" and Nvl(RS("nm_pai"),"") > "" Then
          ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
          If RS("tipo") = "PAI" Then
            ShowHTML "        <td><font size=""1""><b>" & RS("nm_pai")           & "</td>"
            ShowHTML "        <td><font size=""1"">"    & RS("nm_usuario_filha") & "." & RS("nm_filha") & "</td>"
            ShowHTML "        <td><font size=""1"">"    & RS("ds_filha")         & "</td>"
          Else
            ShowHTML "        <td><font size=""1"">"    & RS("nm_usuario_filha") & "." & RS("nm_filha") & "</td>"
            ShowHTML "        <td><font size=""1""><b>" & RS("nm_pai")           & "</b></td>"
            ShowHTML "        <td><font size=""1"">"    & RS("ds_filha")         & "</td>"
          End If
          ShowHTML "        </td>"
          ShowHTML "      </tr>"
        End If
        RS.MoveNext
      wend
    End If
    ShowHTML "</table>"
    ShowHTML "</center>"
    DesconectaBD  
    
    w_cor=""
    
    DB_GetProcSp RS, null, w_sq_sp
    RS.Sort = "chave"
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    ShowHTML "<HR>"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "<div align=center><center>"
    ShowHTML "<td><font size=""1""><B>Procedures</B></td>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nome             </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Stored Procedure </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Descrição        </b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=3 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nm_procedure") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nm_sp")        & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("ds_procedure") & "</td>"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "</table>"
    ShowHTML "</center>"
    DesconectaBD  
    
    w_cor=""
    
        
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape
  
  Set w_chave     = Nothing
  Set w_chave_aux = Nothing  
  Set w_troca     = Nothing
  Set w_sq_sp     = Nothing
  
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de Sistema - Índice
REM -------------------------------------------------------------------------
Sub Indice
    
  w_Chave      = Request("w_Chave")
  w_troca      = Request("w_troca")
  w_sq_indice  = Request("w_sq_indice")
  w_sq_usuario = Request("w_sq_usuario")
  w_sq_tabela  = Request("w_sq_tabela")
  
  If O = "L" Then
    DB_GetIndice RS, w_cliente, w_sq_indice, null, w_sq_usuario, w_chave, null, w_sq_tabela
    RS.Sort = "nm_indice, nm_usuario, nm_tabela"
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    BodyOpen "onLoad='document.focus()';"
    ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
    ShowHTML "<HR>"
    ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"" align=""center"">"
    ShowHTML "<td><font size=""1""><B>Índices (" & RS.RecordCount & ")</B></td>"
    ShowHTML "<tr><td align=""center"" colspan=3></tr>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nome      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Tipo      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Tabela    </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Colunas   </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Descrição </b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=3 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_pagina & "INDICE&R=" & w_Pagina & par & "&O=l&w_chave=" & RS("sq_sistema") & "&w_sq_indice="&RS("chave")&"&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"" target="""&RS("nm_indice")&""">"&lCase(RS("nm_indice"))&"</A></td>"
        ShowHTML "        <td><font size=""1"">" & RS("nm_indice_tipo") & "</td>"
        ShowHTML "        <td nowrap><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_Pagina & "TABELA&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_tabela="& RS("sq_tabela") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"">"&lCase(RS("nm_usuario")&"."&RS("nm_tabela"))&"</A>&nbsp"
        If RS("Colunas") <> "" then
           'ShowHTML "        <td><font size=""1"">" & RS("colunas")      & "</td>"
           ShowHTML "    <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_Pagina & "COLUNA&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_coluna=" & RS("sq_coluna") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Nome"">"&lCase(RS("colunas"))
        Else
           ShowHTML "        <td align=""center""><font size=""1"">---</td>"
        End If
        ShowHTML "        <td><font size=""1"">" & RS("ds_indice")      & "</td>"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "<tr><div align=""center"" colspan=3>"
    If R > "" Then
      MontaBarra w_dir&w_pagina&par&"&R="&R&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
    Else
      MontaBarra w_dir&w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
    End If
    ShowHTML "</tr>"
    DesconectaBD
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape
  
  Set w_chave     = Nothing 
  Set w_troca     = Nothing
  Set w_sq_indice = Nothing
  
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de Sistema - Coluna
REM -------------------------------------------------------------------------
Sub Coluna
     
  w_Chave      = Request("w_Chave")
  w_troca      = Request("w_troca")
  w_sq_coluna  = Request("w_sq_coluna")
  w_sq_usuario = Request("w_sq_usuario")
     
  If O = "L" Then
    DB_GetColuna RS, w_cliente, null, w_sq_tabela, null, w_chave, w_sq_usuario, null
    RS.Sort = "nm_usuario, nm_coluna, nm_tabela"
    Cabecalho
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    BodyOpen "onLoad='document.focus()';"
    ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
    ShowHTML "<HR>"
    ShowHTML "<div align=center>"
    ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"" align=""center"">"
    ShowHTML "<td><font size=""1""><B>Colunas (" & RS.RecordCount & ")</B></td>"
    ShowHTML "<tr><td align=""center"" colspan=3></tr>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Coluna</b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Tabela</b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Tipo</b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Obrig.</b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Valor Padrão</b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Descrição</b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=8 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & "COLUNA&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_coluna=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Nome"">"&lCase(RS("nm_coluna"))&"</A>&nbsp"
        ShowHTML "        <td nowrap><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_Pagina & "TABELA&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_tabela="& RS("sq_tabela") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"">"&lCase(RS("nm_usuario")&"."&RS("nm_tabela"))&"</A>&nbsp"
        ShowHTML "        <td nowrap><font size=""1"">" & RS("nm_coluna_tipo") & " ("
        If uCase(RS("nm_coluna_tipo")) = "NUMERIC" Then
           ShowHTML Nvl(RS("precisao"), RS("tamanho")) & "," & Nvl(RS("escala"),0)
        Else
           ShowHTML RS("tamanho")
        End If
        ShowHTML ")</td>"
        
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("obrigatorio")  & "</td>"
        if RS("valor_padrao") <> "" then 
          ShowHTML "      <td><font size=""1"">" & RS("valor_padrao") &"</td>"
        else
          ShowHTML "      <td><font size=""1"">---</td>" 
        End If
        
        ShowHTML "        <td><font size=""1"">" & RS("descricao")    & "</td>"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "<tr><div align=""center"" colspan=3>"
    If R > "" Then
      MontaBarra w_dir&w_pagina&par&"&R="&R&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
    Else
      MontaBarra w_dir&w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
    End If
    ShowHTML "</tr>"
    DesconectaBD
    
  ElseIf O = "NIVEL2" Then
    DB_GetColuna RS, w_cliente, w_sq_coluna, w_sq_tabela, null, w_chave, w_sq_usuario, null
    RS.Sort = "chave"
    Cabecalho
    ShowHTML "<HEAD>"
    ShowHTML "<TITLE>" & conSgSistema & " - Dicionário</TITLE>"
    ShowHTML "</HEAD>"
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    BodyOpen "onLoad='document.focus()';"
    ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
    ShowHTML "<HR>"
    ShowHTML "<td><font size=""1""><B>Dados da Coluna</B></td>"
    ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
    ShowHTML "<tr><td align=""center"" bgcolor=""#FAEBD7"" colspan=3><table border=1 width=""100%""><tr><td>"
    ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "          <td><font size=""1"">Nome:      <br><b>" & RS("nm_coluna") & "</td>"
    ShowHTML "          <td><font size=""1"">Descrição: <br><b>" & RS("descricao") & "</td>"
    ShowHTML "          <td><font size=""1"">Tabela:    <br><b>" & RS("nm_tabela") & "</td>"
    ShowHTML "    </TABLE>"
    ShowHTML "  </TABLE>"
    ShowHTML "</TABLE>"
    ShowHTML "</font>"
    DesconectaBD
    
    w_cor=""
    
    DB_GetIndiceCols RS, null, w_sq_coluna 
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    ShowHTML "<HR>"
    ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"" align=""center"">"
    ShowHTML "<tr><td><font size=""1""><B>Índices (" & RS.RecordCount & ")</B></td>"
    ShowHTML "<tr><td><TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nome</b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Tipo</b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Tabela</b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Colunas</b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Descrição</b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
       ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=8 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
       w_cor           = ""
       While Not RS.EOF
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
          ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_pagina & "INDICE&R=" & w_Pagina & par & "&O=l&w_chave=" & RS("sq_sistema") & "&w_sq_indice="&RS("chave")&"&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"" target="""&RS("nm_indice")&""">"&lCase(RS("nm_indice"))&"</A></td>"
          ShowHTML "        <td nowrap><font size=""1"">" & RS("nm_indice_tipo") & "</td>"
          ShowHTML  "       <td nowrap><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_Pagina & "TABELA&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_tabela="& RS("sq_tabela") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"">"&lCase(RS("nm_usuario")&"."&RS("nm_tabela"))&"</A>&nbsp"
          ShowHTML "        <td nowrap><font size=""1"">" & RS("colunas") & "</td>"
          ShowHTML "        <td><font size=""1"">" & RS("ds_indice")    & "</td>"
          ShowHTML "        </td>"
          ShowHTML "      </tr>"
          RS.MoveNext
       wend
    End If
    DesconectaBD
  End If
  ShowHTML "  </table>"
  ShowHTML "</table>"
  Rodape

  Set w_chave     = Nothing 
  Set w_troca     = Nothing
  Set w_sq_coluna = Nothing
  
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de Sistema - Arquivo
REM -------------------------------------------------------------------------
Sub Arquivo
      
  w_Chave      = Request("w_Chave")
  w_troca      = Request("w_troca")
  w_sq_arquivo = Request("w_sq_arquivo")
      
  If O = "L" Then
    DB_GetArquivo RS, w_cliente, null, w_chave, null, null
    RS.Sort = "chave"
    Cabecalho
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    BodyOpen "onLoad='document.focus()';"
    ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
    ShowHTML "<HR>"
    ShowHTML "<div align=center>"
    ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"" align=""center"">"
    ShowHTML "<td><font size=""1""><B>Procedures (" & RS.RecordCount & ")</B></td>"
    ShowHTML "<tr><td align=""center"" colspan=3></tr>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nome      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Diretório </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Tipo      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Descrição </b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & "ARQUIVO&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_arquivo=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Nome"">"&lCase(RS("nm_arquivo"))&"</A>&nbsp"
        if RS("diretorio") <> "" then 
          ShowHTML "      <td><font size=""1"">" & RS("diretorio") &"</td>"
        else
          ShowHTML "      <td><font size=""1"">---</td>" 
        End If
        ShowHTML "        <td><font size=""1"">" & RS("tipo")      & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("descricao") & "</td>"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "<tr><div align=""center"" colspan=3>"
    If R > "" Then
      MontaBarra w_dir&w_pagina&par&"&R="&R&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
    Else
      MontaBarra w_dir&w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
    End If
    ShowHTML "</tr>"
    DesconectaBD
    
  ElseIf O = "NIVEL2" Then
    DB_GetArquivo RS, w_cliente, w_sq_arquivo, w_chave, null, null
    RS.Sort = "chave"
    Cabecalho
    ShowHTML "<HEAD>"
    ShowHTML "<TITLE>" & conSgSistema & " - Dicionário</TITLE>"
    ShowHTML "</HEAD>"
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    BodyOpen "onLoad='document.focus()';"
    ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
    ShowHTML "<HR>"
    ShowHTML "<div align=center><center>"
    ShowHTML "<td><font size=""1""><B>Dados do Arquivo</B></td>"
    ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
    ShowHTML "<tr><td align=""center"" bgcolor=""#FAEBD7"" colspan=3><table border=1 width=""100%""><tr><td>"
    ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "          <td><font size=""1"">Nome:      <br><b>" & RS("nm_arquivo") & "</td>"
    ShowHTML "          <td><font size=""1"">Descrição: <br><b>" & RS("descricao")  & "</td>"
    ShowHTML "          <td><font size=""1"">Sistema:   <br><b>" & RS("nm_sistema") & "</td>"
    ShowHTML "    </TABLE>"
    ShowHTML "  </TABLE>"
    ShowHTML "</TABLE>"
    ShowHTML "</font>"
    Rodape
    DesconectaBD
    
    w_cor=""
    
    DB_GetProcedure RS, w_cliente, null, w_sq_arquivo, w_chave, null, null 
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    ShowHTML "<HR>"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "<div align=center><center>"
    ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"" align=""center"">"
    ShowHTML "<td><font size=""1""><B>Procedures (" & RS.RecordCount & ")</B></td>"
    ShowHTML "<tr><td align=""center"" colspan=3></tr>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nome      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Tipo      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Descrição </b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=3 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else  
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nm_procedure") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nm_sp_tipo")   & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("ds_procedure") & "</td>"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    DesconectaBD
    
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape
  
  Set w_chave      = Nothing 
  Set w_troca      = Nothing
  Set w_sq_arquivo = Nothing
  
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de Sistema - Procedure
REM -------------------------------------------------------------------------
Sub Procedure
      
  w_Chave        = Request("w_Chave")
  w_troca        = Request("w_troca")
  w_sq_arquivo   = Request("w_sq_arquivo")
  w_sq_procedure = Request("w_sq_procedure")
     
  If O = "L" Then
    DB_GetProcedure RS, w_cliente, null, w_sq_arquivo, w_chave, null, null
    RS.Sort = "chave"
    Cabecalho
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    BodyOpen "onLoad='document.focus()';"
    ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
    ShowHTML "<HR>"
    ShowHTML "<div align=center>"
    ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"" align=""center"">"
    ShowHTML "<td><font size=""1""><B>Procedures (" & RS.RecordCount & ")</B></td>"
    ShowHTML "<tr><td align=""center"" colspan=3></tr>"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nome      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Tipo      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Arquivo   </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Descrição </b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & "PROCEDURE&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_procedure=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Nome"">"&lCase(RS("nm_procedure"))&"</A>&nbsp"
        ShowHTML "        <td><font size=""1"">" & RS("nm_sp_tipo")   & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nm_arquivo")   & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("ds_procedure") & "</td>"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "<tr><div align=""center"" colspan=3>"
    If R > "" Then
      MontaBarra w_dir&w_pagina&par&"&R="&R&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
    Else
      MontaBarra w_dir&w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
    End If
    ShowHTML "</tr>"
    DesconectaBD
    
  ElseIf O = "NIVEL2" Then
    DB_GetProcedure RS, w_cliente, w_sq_procedure, w_sq_arquivo, w_chave, null, null
    RS.Sort = "chave"
    Cabecalho
    ShowHTML "<HEAD>"
    ShowHTML "<TITLE>" & conSgSistema & " - Dicionário</TITLE>"
    ShowHTML "</HEAD>"
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    BodyOpen "onLoad='document.focus()';"
    ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
    ShowHTML "<HR>"
    ShowHTML "<div align=center><center>"
    ShowHTML "<td><font size=""1""><B>Dados da Procedure</B></td>"
    ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
    ShowHTML "<tr><td align=""center"" bgcolor=""#FAEBD7"" colspan=3><table border=1 width=""100%""><tr><td>"
    ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "          <td><font size=""1"">Nome:      <br><b>" & RS("nm_procedure") & "</td>"
    ShowHTML "          <td><font size=""1"">Arquivo:   <br><b>" & RS("nm_arquivo")   & "</td>"
    ShowHTML "          <td><font size=""1"">Descrição: <br><b>" & RS("ds_procedure") & "</td>"
    ShowHTML "          <td><font size=""1"">Sistema:   <br><b>" & RS("nm_sistema")   & "</td>"
    ShowHTML "    </TABLE>"
    ShowHTML "  </TABLE>"
    ShowHTML "</TABLE>"
    ShowHTML "</font>"
    DesconectaBD
    
    w_cor=""
    
    DB_GetProcSP RS, w_sq_procedure, null
    RS.Sort = "chave"
    Cabecalho
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    ShowHTML "<HR>"
    ShowHTML "<div align=center><center>"
    ShowHTML "<td><font size=""1""><B>Procedures</B></td>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nome      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Descrição </b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=2 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nm_sp") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("ds_sp") & "</td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesconectaBD
    
    w_cor=""
    
    DB_GetProcTabs RS, w_sq_procedure, null
    RS.Sort = "chave"
    Cabecalho
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    ShowHTML "<HR>"
    ShowHTML "<div align=center><center>"
    ShowHTML "<td><font size=""1""><B>Tabelas</B></td>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nome      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Descrição </b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=2 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nm_sp") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("ds_sp") & "</td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesconectaBD
    
    w_cor=""
    
    DB_GetProcedure RS, w_cliente, w_sq_procedure, w_sq_arquivo, w_chave, null, null
    RS.Sort = "chave"
    Cabecalho
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    ShowHTML "<HR>"
    ShowHTML "<div align=center><center>"
    ShowHTML "<td><font size=""1""><B>Arquivos</B></td>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nome      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Descrição </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Tipo      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Diretório </b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=4 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nm_arquivo")      & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("ds_arquivo")      & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nm_arquivo_tipo") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("diretorio")       & "</td>"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "<tr><div align=""center"" colspan=3>"
    ShowHTML "</tr>"
    DesconectaBD
    
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape
  
  Set w_chave        = Nothing 
  Set w_troca        = Nothing
  Set w_sq_arquivo   = Nothing
  Set w_sq_procedure = Nothing
  
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de Sistema - Procedure
REM -------------------------------------------------------------------------
Sub Relacionamento
  DIM w_sq_relacionamento
      
  w_Chave             = Request("w_Chave")
  w_troca             = Request("w_troca")
  w_sq_relacionamento = Request("w_sq_relacionamento")
  w_sq_tabela         = Request("w_sq_tabela")
     
  If O = "NIVEL2" Then
    DB_GetRelacionamento RS, w_cliente, w_sq_relacionamento, null, w_sq_tabela, w_chave, null
    RS.Sort = "nm_relacionamento"
    Cabecalho
    ShowHTML "<HEAD>"
    ShowHTML "<TITLE>" & conSgSistema & " - Dicionário</TITLE>"
    ShowHTML "</HEAD>"
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    BodyOpen "onLoad='document.focus()';"
    ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
    ShowHTML "<HR>"
    ShowHTML "<div align=center><center>"
    ShowHTML "<td><font size=""1""><B>Dados do Relacionamento</B></td>"
    ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
    ShowHTML "<tr><td align=""center"" bgcolor=""#FAEBD7"" colspan=3><table border=1 width=""100%""><tr><td>"
    ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "          <td><font size=""1"">Nome:         <br><b>" & RS("nm_relacionamento") & "</td>"
    ShowHTML "          <td><font size=""1"">Tabela Pai:   <br><b>" & RS("nm_tabela_pai")   & "</td>"
    ShowHTML "          <td><font size=""1"">Tabela filha: <br><b>" & RS("nm_tabela_filha") & "</td>"
    ShowHTML "          <td><font size=""1"">Sistema:      <br><b>" & RS("sg_sistema")   & "</td>"
    ShowHTML "    </TABLE>"
    ShowHTML "  </TABLE>"
    ShowHTML "</TABLE>"
    ShowHTML "</font>"
    DesconectaBD
    
    w_cor=""
   
    DB_GetRelacCols RS, w_sq_relacionamento, null
    RS.Sort = "nm_relacionamento"
    Cabecalho
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
    ShowHTML "<HR>"
    ShowHTML "<div align=center><center>"
    ShowHTML "<td><font size=""1""><B>Relacionamentos</B></td>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nome      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Tabela Pai </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Coluna Pai      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Tabela Filha </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Coluna Filha </b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nm_relacionamento")      & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nm_tabela_pai")      & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nm_coluna_pai") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nm_tabela_filha")       & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nm_coluna_filha")       & "</td>"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "<tr><div align=""center"" colspan=3>"
    ShowHTML "</tr>"
    DesconectaBD
    
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape
  
  Set w_chave        = Nothing 
  Set w_troca        = Nothing
  Set w_sq_arquivo   = Nothing
  Set w_sq_procedure = Nothing
  
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Função para gerar HTML de exibição das tabelas de um usuário
REM -------------------------------------------------------------------------
Function ExibeTabela (p_sq_usuario, p_sq_tabela, p_ordena)
  Dim w_html
  
  DB_GetTabela RS, w_cliente, null, null, p_sq_tabela, p_sq_usuario, null, null, null
  RS.Sort = p_ordena
  w_html = "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"" align=""center"">"
  w_html = w_html & "<tr><td><font size=""1""><B>Tabelas (" & RS.RecordCount & ")</B></td>"
  w_html = w_html & "<tr><td><TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
  w_html = w_html & "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
  w_html = w_html & "          <td><font size=""1""><b>Nome</b></font></td>"
  w_html = w_html & "          <td><font size=""1""><b>Tipo</b></font></td>"
  w_html = w_html & "          <td><font size=""1""><b>Descrição</b></font></td>"
  w_html = w_html & "        </tr>"
  If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
     w_html = w_html & "      <tr bgcolor=""" & conTrBgColor & """><td colspan=8 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
  Else
     w_cor           = ""
     While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        w_html = w_html & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        w_html = w_html &  "       <td nowrap><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_Pagina & "TABELA&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_tabela="& RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"">"&lCase(RS("nm_usuario")&"."&RS("nome"))&"</A>&nbsp"
        w_html = w_html & "        <td nowrap><font size=""1"">" & RS("nm_tipo")& "</td>"
        w_html = w_html & "        <td><font size=""1"">" & RS("descricao")    & "</td>"
        w_html = w_html & "        </td>"
        w_html = w_html & "      </tr>"
        RS.MoveNext
     wend
  End If
    
  w_html = w_html & "</table>"
  DesconectaBD
  ExibeTabela = w_html
    
  Set w_html = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Função para gerar HTML de exibição das colunas de uma tabela
REM -------------------------------------------------------------------------
Function ExibeColuna (p_sq_usuario, p_sq_tabela, p_ordena)
  Dim w_html
  
  DB_GetColuna RS, w_cliente, null, p_sq_tabela, null, null, p_sq_usuario, null
  RS.Sort = p_ordena
  w_html = "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"" align=""center"">"
  w_html = w_html & "<tr><td><font size=""1""><B>Colunas (" & RS.RecordCount & ")</B></td>"
  If RS.RecordCount < 500 Then
     w_html = w_html & "<tr><td><TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     w_html = w_html & "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     w_html = w_html & "          <td><font size=""1""><b>Coluna</b></font></td>"
     w_html = w_html & "          <td><font size=""1""><b>Tipo</b></font></td>"
     If Nvl(p_sq_tabela,"nulo") = "nulo" Then
        w_html = w_html & "          <td><font size=""1""><b>Tabela</b></font></td>"
     End If
     w_html = w_html & "          <td><font size=""1""><b>Obrig.</b></font></td>"
     w_html = w_html & "          <td><font size=""1""><b>Default</b></font></td>"
     w_html = w_html & "          <td><font size=""1""><b>Descrição</b></font></td>"
     w_html = w_html & "        </tr>"
     If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        w_html = w_html & "      <tr bgcolor=""" & conTrBgColor & """><td colspan=8 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
     Else
        w_cor           = ""
        While Not RS.EOF
           If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
           w_html = w_html & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
           w_html = w_html & "        <td align=""top"" nowrap><font size=""1"">"
           w_html = w_html & "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & "COLUNA&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_coluna=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Nome"">"&lCase(RS("nm_coluna"))
           If Nvl(RS("sq_relacionamento"),"nulo") <> "nulo" Then
              w_html = w_html & "          (FK)"
           End If
           w_html = w_html & "          </A>&nbsp"
           w_html = w_html & "        <td nowrap><font size=""1"">" & RS("nm_coluna_tipo") & " ("
           If uCase(RS("nm_coluna_tipo")) = "NUMERIC" Then
              w_html = w_html & Nvl(RS("precisao"), RS("tamanho")) & "," & Nvl(RS("escala"),0)
           Else
              w_html = w_html & RS("tamanho")
           End If
           w_html = w_html & ")</td>"
          
           If Nvl(p_sq_tabela,"nulo") = "nulo" Then
              w_html = w_html &  "       <td nowrap><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_Pagina & "TABELA&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_tabela="& RS("sq_tabela") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"">"&lCase(RS("nm_usuario")&"."&RS("nm_tabela"))&"</A>&nbsp"
           End If
           w_html = w_html & "        <td align=""center""><font size=""1"">" & RS("obrigatorio")  & "</td>"
           if RS("valor_padrao") <> "" then 
              w_html = w_html & "      <td><font size=""1"">" & RS("valor_padrao") &"</td>"
           else
              w_html = w_html & "      <td><font size=""1"">---</td>" 
           End If
          
           w_html = w_html & "        <td><font size=""1"">" & RS("descricao")    & "</td>"
           w_html = w_html & "        </td>"
           w_html = w_html & "      </tr>"
           RS.MoveNext
        wend
     End If
  End If  
  w_html = w_html & "</table>"
  w_html = w_html & "</center>"
  DesconectaBD
  ExibeColuna = w_html
    
  Set w_html = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------
     
REM =========================================================================
REM Função para gerar HTML de exibição das colunas de uma tabela
REM -------------------------------------------------------------------------
Function ExibeIndice (p_sq_usuario, p_sq_tabela, p_ordena)
  Dim w_html
  
  DB_GetIndiceTabs RS, null, p_sq_usuario, null, p_sq_tabela
  RS.Sort = p_ordena
  w_html = "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"" align=""center"">"
  w_html = w_html & "<tr><td><font size=""1""><B>Índices (" & RS.RecordCount & ")</B></td>"
  If RS.RecordCount < 500 Then
     w_html = w_html & "<tr><td><TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     w_html = w_html & "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     w_html = w_html & "          <td><font size=""1""><b>Nome</b></font></td>"
     w_html = w_html & "          <td><font size=""1""><b>Tipo</b></font></td>"
     If Nvl(p_sq_tabela,"nulo") = "nulo" Then
        w_html = w_html & "          <td><font size=""1""><b>Tabela</b></font></td>"
     End If
     w_html = w_html & "          <td><font size=""1""><b>Colunas</b></font></td>"
     w_html = w_html & "          <td><font size=""1""><b>Descrição</b></font></td>"
     w_html = w_html & "        </tr>"
     If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        w_html = w_html & "      <tr bgcolor=""" & conTrBgColor & """><td colspan=8 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
     Else
        w_cor           = ""
        While Not RS.EOF
           If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
           w_html = w_html & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
           w_html = w_html & "        <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_pagina & "INDICE&R=" & w_Pagina & par & "&O=l&w_chave=" & RS("sq_sistema") & "&w_sq_indice="&RS("chave")&"&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"">"&lCase(RS("nm_indice"))&"</A></td>"
           w_html = w_html & "        <td nowrap><font size=""1"">" & RS("nm_indice_tipo") & "</td>"
           If Nvl(p_sq_tabela,"nulo") = "nulo" Then
              w_html = w_html &  "       <td nowrap><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_Pagina & "TABELA&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_tabela="& RS("sq_tabela") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"">"&lCase(RS("nm_usuario")&"."&RS("nm_tabela"))&"</A>&nbsp"
           End If
           w_html = w_html & "        <td nowrap><font size=""1"">" & RS("colunas") & "</td>"
           w_html = w_html & "        <td><font size=""1"">" & RS("ds_indice")    & "</td>"
           w_html = w_html & "        </td>"
           w_html = w_html & "      </tr>"
           RS.MoveNext
        wend
     End If
  End If  
  w_html = w_html & "</table>"
  w_html = w_html & "</center>"
  DesconectaBD
  ExibeIndice = w_html
    
  Set w_html = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------
     
REM =========================================================================
REM Função para gerar HTML de exibição das triggers
REM -------------------------------------------------------------------------
Function ExibeTrigger (p_sistema, p_sq_usuario, p_sq_tabela, p_ordena)
  Dim w_html
  
  DB_GetTrigger RS, w_cliente, null, p_sq_tabela, p_sq_usuario, w_chave
  RS.Sort = p_ordena
  w_html = "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"" align=""center"">"
  w_html = w_html & "<tr><td><font size=""1""><B>Triggers (" & RS.RecordCount & ")</B></td>"
  If RS.RecordCount < 500 Then
     w_html = w_html & "<tr><td><TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     w_html = w_html & "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     w_html = w_html & "          <td><font size=""1""><b>Nome</b></font></td>"
     If Nvl(p_sq_tabela,"nulo") = "nulo" Then
        w_html = w_html & "          <td><font size=""1""><b>Tabela</b></font></td>"
     End If
     w_html = w_html & "          <td><font size=""1""><b>Eventos de disparo</b></font></td>"
     w_html = w_html & "          <td><font size=""1""><b>Descrição</b></font></td>"
     w_html = w_html & "        </tr>"
     If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        w_html = w_html & "      <tr bgcolor=""" & conTrBgColor & """><td colspan=8 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
     Else
        w_cor           = ""
        While Not RS.EOF
           If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
           w_html = w_html & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
           w_html = w_html & "        <td align=""top"" nowrap><font size=""1"">"&RS("nm_trigger")&"</td>"
           If Nvl(p_sq_tabela,"nulo") = "nulo" Then
              w_html = w_html &  "       <td nowrap><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_Pagina & "TABELA&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_tabela="& RS("sq_tabela") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"">"&lCase(RS("nm_usuario")&"."&RS("nm_tabela"))&"</A>&nbsp"
           End If
           If RS("eventos") <> "" Then
              w_html = w_html & "        <td align=""center""><font size=""1"">" & RS("eventos") & "</td>"
           Else
              w_html = w_html & "        <td align=""center""><font size=""1"">---</td>"
           End If
           w_html = w_html & "        <td><font size=""1"">" & RS("ds_trigger")    & "</td>"
           w_html = w_html & "        </td>"
           w_html = w_html & "      </tr>"
           RS.MoveNext
        wend
     End If
  End If  
  w_html = w_html & "</table>"
  w_html = w_html & "</center>"
  DesconectaBD
  ExibeTrigger = w_html
    
  Set w_html = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------
     
REM =========================================================================
REM Função para gerar HTML de exibição das stored procedures
REM -------------------------------------------------------------------------
Function ExibeSP (p_sistema, p_sq_usuario, p_sq_sp, p_ordena)
  Dim w_html
  
  DB_GetStoredProcedure RS, w_cliente, null, null, null, p_sq_usuario, p_sistema, null, null
  RS.Sort = p_ordena
  w_html = "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"" align=""center"">"
  w_html = w_html & "<tr><td><font size=""1""><B>Stored Procedures (" & RS.RecordCount & ")</B></td>"
  If RS.RecordCount < 500 Then
     w_html = w_html & "<tr><td><TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     w_html = w_html & "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     w_html = w_html & "          <td><font size=""1""><b>Nome</b></font></td>"
     w_html = w_html & "          <td><font size=""1""><b>Tipo</b></font></td>"
     w_html = w_html & "          <td><font size=""1""><b>Descrição</b></font></td>"
     w_html = w_html & "        </tr>"
     If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        w_html = w_html & "      <tr bgcolor=""" & conTrBgColor & """><td colspan=8 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
     Else
        w_cor           = ""
        While Not RS.EOF
           If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
           w_html = w_html & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
           w_html = w_html &  "       <td nowrap><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_Pagina & "STOREDPROCEDURE&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_sp="& RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Stored procedure"">"&lCase(RS("nm_usuario")&"."&RS("nm_sp"))&"</A>&nbsp"
           w_html = w_html & "        <td><font size=""1"">" & RS("nm_sp_tipo") & "</td>"
           w_html = w_html & "        <td><font size=""1"">" & RS("ds_sp")    & "</td>"
           w_html = w_html & "        </td>"
           w_html = w_html & "      </tr>"
           RS.MoveNext
        wend
     End If
  End If  
  w_html = w_html & "</table>"
  w_html = w_html & "</center>"
  DesconectaBD
  ExibeSP = w_html
    
  Set w_html = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  ' Verifica se o usuário tem lotação e localização
  If (len(Session("LOTACAO")&"") = 0 or len(Session("LOCALIZACAO")&"") = 0) and Session("LogOn") = "Sim" Then
     ScriptOpen "JavaScript"
     ShowHTML   " alert('Você não tem lotação ou localização definida. Entre em contato com o RH!'); "
     ShowHTML   " top.location.href='Default.asp'; "
     ScriptClose
     Exit Sub
  End If
  Select Case Par
     Case "USUARIO"         Usuario
     Case "TABELA"          Tabela
     Case "TRIGGER"         Trigger
     Case "RELACIONAMENTO"  Relacionamento
     Case "STOREDPROCEDURE" StoredProcedure
     Case "INDICE"          Indice
     Case "COLUNA"          Coluna
     Case "ARQUIVO"         Arquivo
     Case "PROCEDURE"       Procedure
     Case Else
     Cabecalho
     ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
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

