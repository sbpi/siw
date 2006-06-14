<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_EO.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_is/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_is/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_is/VisualViagem.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_is/DB_Tabelas.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Relatorios.asp
REM ------------------------------------------------------------------------
REM Nome     : Celso Miguel Lago Filho
REM Descricao: Diversos tipos de relatórios para fazer o acompanhamento gerencial 
REM Mail     : celso@sbpi.com.br
REM Criacao  : 21/04/2004 11:00
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
Dim dbms, sp, RS, RS1, RS2, RS3, RS4, RS_menu
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Reg, w_Pagina, w_Disabled, w_TP, w_classe, w_submenu, w_filtro, w_copia
Dim w_Assinatura
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu, w_dir, w_chave, w_dir_volta
Dim w_sq_pessoa, w_ano
Dim ul,File
Dim w_pag, w_linha
Set RS  = Server.CreateObject("ADODB.RecordSet")
Set RS1 = Server.CreateObject("ADODB.RecordSet")
Set RS2 = Server.CreateObject("ADODB.RecordSet")
Set RS3 = Server.CreateObject("ADODB.RecordSet")
Set RS4 = Server.CreateObject("ADODB.RecordSet")
Set RS_Menu = Server.CreateObject("ADODB.RecordSet")

w_troca            = Request("w_troca")
w_copia            = Request("w_copia")
  
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

w_Pagina     = "Relatorios.asp?par="
w_Dir        = "mod_pd/"
w_dir_volta  = "../"  
w_Disabled   = "ENABLED"

If O = "" Then
   O = "P"
End If

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

w_cliente         = RetornaCliente()
w_usuario         = RetornaUsuario()
w_menu            = RetornaMenu(w_cliente, SG)
w_ano             = RetornaAno()

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

Set w_chave       = Nothing
Set w_copia       = Nothing
Set w_filtro      = Nothing
Set w_menu        = Nothing
Set w_usuario     = Nothing
Set w_cliente     = Nothing
Set w_filter      = Nothing
Set w_cor         = Nothing
Set ul            = Nothing
Set File          = Nothing
Set w_sq_pessoa   = Nothing
Set w_troca       = Nothing
Set w_submenu     = Nothing
Set w_reg         = Nothing

Set RS            = Nothing
Set RS1           = Nothing
Set RS2           = Nothing
Set RS3           = Nothing
Set RS4           = Nothing
Set RS_menu       = Nothing
Set Par           = Nothing
Set P1            = Nothing
Set P2            = Nothing
Set P3            = Nothing
Set P4            = Nothing
Set TP            = Nothing
Set SG            = Nothing
Set R             = Nothing
Set O             = Nothing
Set w_Classe      = Nothing
Set w_Cont        = Nothing
Set w_Pagina      = Nothing
Set w_Disabled    = Nothing
Set w_TP          = Nothing
Set w_Assinatura  = Nothing
Set w_dir         = Nothing
Set w_dir_volta   = Nothing
Set w_ano         = Nothing


REM =========================================================================
REM Relatório de limites das unidades
REM -------------------------------------------------------------------------
Sub Rel_Limite
  Dim p_sq_unidade, p_cd_acao, p_cd_programa, w_tipo_rel
  Dim w_logo, w_det_pcd, w_unidade_atual, i
  Dim w_diaria_limite, w_tot_diaria_limite, w_trecho_limite, w_tot_trecho_limite
  Dim w_diaria_utilizado, w_tot_diaria_utilizado, w_trecho_utilizado, w_tot_trecho_utilizado
  Dim w_valor(20, 4)
  
  p_sq_unidade               = ucase(Trim(Request("p_sq_unidade")))
  p_cd_programa              = ucase(Trim(Request("p_cd_programa")))
  p_cd_acao                  = ucase(Trim(Request("p_cd_acao")))
  w_tipo_rel                 = uCase(trim(Request("w_tipo_rel")))
  w_det_pcd                  = uCase(trim(Request("w_det_pcd")))
  
  w_cont                  = 0
  w_diaria_limite         = 0
  w_tot_diaria_limite     = 0
  w_trecho_limite         = 0
  w_tot_trecho_limite     = 0  
  w_diaria_utilizado      = 0
  w_tot_diaria_utilizado  = 0
  w_trecho_utilizado      = 0
  w_tot_trecho_utilizado  = 0


  
  If O = "L" Then
     ' Recupera o logo do cliente a ser usado nas listagens
     DB_GetCustomerData RS, w_cliente
     If RS("logo") > "" Then
         w_logo = "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
     End If
     DesconectaBD
  End If

  DB_GetLinkData RS1, RetornaCliente(), "PDINICIAL"
  DB_GetSolicList_IS RS, RS1("sq_menu"), w_usuario, "GRPDUNIDADE", 4, _
     null, null, null, null, null, null, _
     p_sq_unidade, null, null, null, _
     null, null, null, null, null, null, null, _
     null, null, null, null, p_cd_programa, Mid(p_cd_acao,5,4), null, null, null, null, w_ano
  RS.sort = "sq_unidade_resp"
  If w_tipo_rel = "WORD" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 8
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     ShowHTML "<div align=""center"">"
     ShowHTML "<table width=""95%"" border=""0"" cellspacing=""3"">"
     ShowHTML "<tr><td colspan=""2"">"     
     ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """></TD><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
     ShowHTML "RELATÓRIO DE LIMITES<br> Exercício " & w_ano
     ShowHTML "</FONT></TD></TR></TABLE>"
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML "<TITLE>Relatório de Limites - Exercício " & w_ano & "</TITLE>"
     If InStr("P",O) > 0 Then
        ScriptOpen "JavaScript"
        ValidateOpen "Validacao"
        Validate "p_sq_unidade", "Responsável", "HIDDEN", "", "2", "60", "1", "1"
        Validate "p_cd_programa", "Programa", "HIDDEN", "", "1", "18", "1", "1"
        Validate "p_cd_acao", "Ação", "HIDDEN", "", "1", "18", "1", "1"
        ValidateClose
        ScriptClose        
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If O = "L" Then
        BodyOpenClean "onLoad='document.focus()';"
        ShowHTML "<BASE HREF=""" & conRootSIW & """>"
        ShowHTML "<div align=""center"">"
        ShowHTML "<table width=""95%"" border=""0"" cellspacing=""3"">"
        ShowHTML "<tr><td colspan=""2"">"             
        ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """></TD><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
        ShowHTML "RELATÓRIO DE LIMITES<br> Exercício " & w_ano
        ShowHTML "</FONT></B></TD></TR></TABLE>"
     Else
        BodyOpen "onLoad='document.Form.p_cd_programa.focus()';"
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
        ShowHTML "<div align=center><center>"
        ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
        ShowHTML "<HR>"
     End If
  End If
  If O = "L" Then
    ShowHTML "<tr><td colspan=""2""><div align=""center""><hr NOSHADE color=#000000 size=2></div></td></tr>"
    ShowHTML "<tr><td colspan=""2""><div align=""center"">"
    ShowHTML "<table border=""0"" width=""100%"">"
    If p_sq_unidade > "" Then
       DB_GetUorgData RS1, p_sq_unidade
       ShowHTML "<tr><td width=""15%""><font size=""1""><b>Área de planejamento:</b></font></td><td><font size=""1"">" & RS1("nome") & " - " & RS1("sigla")& "</font></td>"
       RS1.Close
    Else
       ShowHTML "<tr><td width=""15%""><font size=""1""><b>Área de planejamento:</b></font></td><td><font size=""1"">Todas</font></td>"
    End If
    If p_cd_programa > "" Then
       DB_GetProgramaPPA_IS RS1, p_cd_programa, w_cliente, w_ano, null, null
       ShowHTML "    <td width=""7%""><font size=""1""><b>Programa:</b></font></td><td nowrap><font size=""1"">" & p_cd_programa & " - " & RS1("ds_programa") & "</font></td></tr>"
       RS1.Close
    Else
       ShowHTML "    <td width=""7%""><font size=""1""><b>Programa:</b></font></td><td><font size=""1"">Todos</font></td></tr>"
    End If
    If p_cd_acao > "" Then
       DB_GetAcaoPPA_IS RS1, w_cliente, w_ano, p_cd_programa, Mid(p_cd_acao,5,4), null, null, null, null, null
       ShowHTML "<tr valign=""top""><td><font size=""1""><b>Ação:</b></font></td><td><font size=""1"">" & Mid(p_cd_acao,5,4) & " - " & RS1("descricao_acao") & "</font></td>"
       RS1.Close
    Else
       ShowHTML "<tr valign=""top""><td><font size=""1""><b>Ação:</b></font></td><td><font size=""1"">Todas</font></td>"
    End If
    
    ShowHTML "</ul></td></tr></table>"
    ShowHTML "</div></td></tr>"
    ShowHTML "<tr><td colspan=""2""><div align=""center""><hr NOSHADE color=#000000 size=2></div></td></tr>"    
    ShowHTML "<tr><td colspan=""2""><div align=""center""><font size=""3""><b>RELATÓRIO DE LIMITES</b></font></div></td></tr>"
    w_linha = 9
    If RS.EOF Then  
       w_linha = w_linha + 1
       ShowHTML "    <tr><td colspan=""13""><div align=""center""><font size=""3"" color=""red""><b><br>Nenhum limite de unidade encontrada</b></div></td></tr>"
    Else
       ShowHTML "   <tr><td colspan=""2"">"
       ShowHTML "     <table width=100%  border=""1"" bordercolor=""#00000"">"
       ShowHTML "       <tr bgcolor=""" & conTrAlternateBgColor & """><td align=""center"" rowspan=""2""><b>Unidade</b></td>"
       ShowHTML "           <td colspan=""3"" align=""center""><b>Passagem</b></td>"
       ShowHTML "           <td colspan=""3"" align=""center""><b>Diária</b></td>"
       ShowHTML "       </tr>"
       ShowHTML "       <tr bgcolor=""" & conTrAlternateBgColor & """>"
       ShowHTML "           <td align=""center""><b>Limite(R$)</b></td>"        
       ShowHTML "           <td align=""center""><b>Utilizado(R$)</b></td>"
       ShowHTML "           <td align=""center""><b>Saldo(R$)</b></td>"
       ShowHTML "           <td align=""center""><b>Limite(R$)</b></td>"        
       ShowHTML "           <td align=""center""><b>Utilizado(R$)</b></td>"
       ShowHTML "           <td align=""center""><b>Saldo(R$)</b></td></tr>"       
       w_unidade_atual = ""
       i = 1
       While Not RS.EOF
          If w_linha > 19 and w_tipo_rel = "WORD" Then
             ShowHTML "    </table>"
             ShowHTML "  </td>"
             ShowHTML "</tr>"
             ShowHTML "</table>"
             ShowHTML "</div>"
             ShowHTML "    <br style=""page-break-after:always"">"
             w_linha = 9
             w_pag   = w_pag + 1
             ShowHTML "<div align=""center"">"
             ShowHTML "<table width=""95%"" border=""0"" cellspacing=""3"">"
             ShowHTML "<tr><td colspan=""2"">"     
             ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """></TD><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
             ShowHTML "RELATÓRIO DE LIMITES<br> Exercício " & w_ano
             ShowHTML "</FONT></TD></TR></TABLE>"
             ShowHTML "<tr><td colspan=""2""><div align=""center""><hr NOSHADE color=#000000 size=2></div></td></tr>"
             ShowHTML "<tr><td colspan=""2""><div align=""center"">"
             ShowHTML "<table border=""0"" width=""100%"">"
             If p_sq_unidade > "" Then
                DB_GetUorgData RS1, p_sq_unidade
                ShowHTML "<tr><td width=""15%""><font size=""1""><b>Área de planejamento:</b></font></td><td><font size=""1"">" & RS1("nome") & " - " & RS1("sigla")& "</font></td>"
                RS1.Close
             Else
                ShowHTML "<tr><td width=""15%""><font size=""1""><b>Área de planejamento:</b></font></td><td><font size=""1"">Todas</font></td>"
             End If
             If p_cd_programa > "" Then
                DB_GetProgramaPPA_IS RS1, p_cd_programa, w_cliente, w_ano, null, null
                ShowHTML "    <td width=""7%""><font size=""1""><b>Programa:</b></font></td><td nowrap><font size=""1"">" & p_cd_programa & " - " & RS1("ds_programa") & "</font></td></tr>"
                RS1.Close
             Else
                ShowHTML "    <td width=""7%""><font size=""1""><b>Programa:</b></font></td><td><font size=""1"">Todos</font></td></tr>"
             End If
             If p_cd_acao > "" Then
                DB_GetAcaoPPA_IS RS1, w_cliente, w_ano, p_cd_programa, Mid(p_cd_acao,5,4), null, null, null, null, null
                ShowHTML "<tr valign=""top""><td><font size=""1""><b>Ação:</b></font></td><td><font size=""1"">" & Mid(p_cd_acao,5,4) & " - " & RS1("descricao_acao") & "</font></td>"
                RS1.Close
             Else
                ShowHTML "<tr valign=""top""><td><font size=""1""><b>Ação:</b></font></td><td><font size=""1"">Todas</font></td>"
             End If 
             ShowHTML "</ul></td></tr></table>"
             ShowHTML "</div></td></tr>"
             ShowHTML "<tr><td colspan=""2""><div align=""center""><hr NOSHADE color=#000000 size=2></div></td></tr>"    
             ShowHTML "<tr><td colspan=""2""><div align=""center""><font size=""3""><b>RELATÓRIO DE LIMITES</b></font></div></td></tr>"
          End If          
          If w_unidade_atual <> "" and (w_unidade_atual <> RS("nm_unidade_resp")) Then
             ShowHTML "    <tr><td>" & w_unidade_atual & "</td>"
             ShowHTML "        <td align=""right"">" & FormatNumber(cDbl(Nvl(w_trecho_limite,0)),2) & "</td>"
             ShowHTML "        <td align=""right"">" & FormatNumber(cDbl(Nvl(w_trecho_utilizado,0)),2) & "</td>"
             ShowHTML "        <td align=""right"">" & FormatNumber(FormatNumber(cDbl(Nvl(w_trecho_limite,0)),2) - FormatNumber(cDbl(Nvl(w_trecho_utilizado,0)),2),2) & "</td>"
             ShowHTML "        <td align=""right"">" & FormatNumber(cDbl(Nvl(w_diaria_limite,0)),2) & "</td>"
             ShowHTML "        <td align=""right"">" & FormatNumber(cDbl(Nvl(w_diaria_utilizado,0)),2) & "</td>"
             ShowHTML "        <td align=""right"">" & FormatNumber(FormatNumber(cDbl(Nvl(w_diaria_limite,0)),2) - FormatNumber(cDbl(Nvl(w_diaria_utilizado,0)),2),2) & "</td>"
             
             w_tot_trecho_limite = w_tot_trecho_limite + cDbl(Nvl(w_trecho_limite,0))
             w_valor (i,1) = FormatNumber(cDbl(Nvl(w_trecho_limite,0)),2)
             w_valor (i,2) = FormatNumber(cDbl(Nvl(w_trecho_utilizado,0)),2)
             w_trecho_utilizado  = 0
             
             w_tot_diaria_limite = w_tot_diaria_limite + cDbl(Nvl(w_diaria_limite,0))
             w_valor (i,3) = FormatNumber(cDbl(Nvl(w_diaria_limite,0)),2)
             w_valor (i,4) = FormatNumber(cDbl(Nvl(w_diaria_utilizado,0)),2)
             w_diaria_utilizado  = 0
                          
             i = i + 1
          End If
          w_trecho_limite    = RS("limite_passagem")
          w_trecho_utilizado = w_trecho_utilizado + cDbl(Nvl(RS("valor_trecho"),0))
          w_tot_trecho_utilizado = w_tot_trecho_utilizado + cDbl(Nvl(RS("valor_trecho"),0))

          w_diaria_limite    = RS("limite_diaria")
          w_diaria_utilizado = w_diaria_utilizado + cDbl(Nvl(RS("valor_diaria"),0)) + cDbl(Nvl(RS("valor_adicional"),0)) - cDbl(Nvl(RS("desconto_alimentacao"),0)) - cDbl(Nvl(RS("desconto_transporte"),0))
          w_tot_diaria_utilizado = w_tot_diaria_utilizado + cDbl(Nvl(RS("valor_diaria"),0)) + cDbl(Nvl(RS("valor_adicional"),0)) - cDbl(Nvl(RS("desconto_alimentacao"),0)) - cDbl(Nvl(RS("desconto_transporte"),0))
          
          w_unidade_atual = RS("nm_unidade_resp")
          RS.MoveNext
       wend
       w_linha = w_linha + 8
       ShowHTML "    <tr><td>" & w_unidade_atual & "</td>"
       ShowHTML "        <td align=""right"">" & FormatNumber(cDbl(Nvl(w_trecho_limite,0)),2) & "</td>"
       ShowHTML "        <td align=""right"">" & FormatNumber(cDbl(Nvl(w_trecho_utilizado,0)),2) & "</td>"
       ShowHTML "        <td align=""right"">" & FormatNumber(FormatNumber(cDbl(Nvl(w_trecho_limite,0)),2) - FormatNumber(cDbl(Nvl(w_trecho_utilizado,0)),2),2) & "</td>"
       ShowHTML "        <td align=""right"">" & FormatNumber(cDbl(Nvl(w_diaria_limite,0)),2) & "</td>"
       ShowHTML "        <td align=""right"">" & FormatNumber(cDbl(Nvl(w_diaria_utilizado,0)),2) & "</td>"
       ShowHTML "        <td align=""right"">" & FormatNumber(FormatNumber(cDbl(Nvl(w_diaria_limite,0)),2) - FormatNumber(cDbl(Nvl(w_diaria_utilizado,0)),2),2) & "</td>"
             
       w_tot_trecho_limite = w_tot_trecho_limite + cDbl(Nvl(w_trecho_limite,0))
       w_valor (i,1) = FormatNumber(cDbl(Nvl(w_trecho_limite,0)),2)
       w_valor (i,2) = FormatNumber(cDbl(Nvl(w_trecho_utilizado,0)),2)
             
       w_tot_diaria_limite = w_tot_diaria_limite + cDbl(Nvl(w_diaria_limite,0))
       w_valor (i,3) = FormatNumber(cDbl(Nvl(w_diaria_limite,0)),2)
       w_valor (i,4) = FormatNumber(cDbl(Nvl(w_diaria_utilizado,0)),2)


       ShowHTML "    <tr bgcolor=""" & conTrAlternateBgColor & """><td align=""right""><b>Totais</b></td>"
       ShowHTML "        <td align=""right""><b>" & FormatNumber(cDbl(Nvl(w_tot_trecho_limite,0)),2) & "</b></td>"
       ShowHTML "        <td align=""right""><b>" & FormatNumber(cDbl(Nvl(w_tot_trecho_utilizado,0)),2) & "</b></td>"
       ShowHTML "        <td align=""right""><b>" & FormatNumber(FormatNumber(cDbl(Nvl(w_tot_trecho_limite,0)),2) - FormatNumber(cDbl(Nvl(w_tot_trecho_utilizado,0)),2),2) & "</b></td>"
       ShowHTML "        <td align=""right""><b>" & FormatNumber(cDbl(Nvl(w_tot_diaria_limite,0)),2) & "</b></td>"
       ShowHTML "        <td align=""right""><b>" & FormatNumber(cDbl(Nvl(w_tot_diaria_utilizado,0)),2) & "</b></td>"
       ShowHTML "        <td align=""right""><b>" & FormatNumber(FormatNumber(cDbl(Nvl(w_tot_diaria_limite,0)),2) - FormatNumber(cDbl(Nvl(w_tot_diaria_utilizado,0)),2),2) & "</b></td>"
       ShowHTML "</table>"
       If uCase(w_det_pcd) = uCase("sim") Then
          ShowHTML "<br><br><br><tr><td colspan=""2""><div align=""center""><font size=""3""><b>DETALHAMENTO DAS PCD'S</b></font></div></td></tr>"       
          RS.MoveFirst
          w_unidade_atual = ""
          i = 1
          w_tot_trecho_limite = 0
          w_tot_diaria_limite = 0
          ShowHTML "<tr><td colspan=""2"">"
          ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
          While Not RS.EOF
             If w_linha > 19 and w_tipo_rel = "WORD" Then
                ShowHTML "</table>"
                ShowHTML "</table>"
                ShowHTML "</table>"
                ShowHTML "</div>"
                ShowHTML "    <br style=""page-break-after:always"">"
                w_linha = 9
                w_pag   = w_pag + 1
                ShowHTML "<div align=""center"">"
                ShowHTML "<table width=""95%"" border=""0"" cellspacing=""3"">"
                ShowHTML "<tr><td colspan=""2"">"     
                ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """></TD><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
                ShowHTML "RELATÓRIO DE LIMITES<br> Exercício " & w_ano
                ShowHTML "</FONT></TD></TR></TABLE>"
                ShowHTML "<tr><td colspan=""2""><div align=""center""><hr NOSHADE color=#000000 size=2></div></td></tr>"
                ShowHTML "<tr><td colspan=""2""><div align=""center"">"
                ShowHTML "<table border=""0"" width=""100%"">"
                If p_sq_unidade > "" Then
                   DB_GetUorgData RS1, p_sq_unidade
                   ShowHTML "<tr><td width=""15%""><font size=""1""><b>Área de planejamento:</b></font></td><td><font size=""1"">" & RS1("nome") & " - " & RS1("sigla")& "</font></td>"
                   RS1.Close
                Else
                   ShowHTML "<tr><td width=""15%""><font size=""1""><b>Área de planejamento:</b></font></td><td><font size=""1"">Todas</font></td>"
                End If
                If p_cd_programa > "" Then
                   DB_GetProgramaPPA_IS RS1, p_cd_programa, w_cliente, w_ano, null, null
                   ShowHTML "    <td width=""7%""><font size=""1""><b>Programa:</b></font></td><td nowrap><font size=""1"">" & p_cd_programa & " - " & RS1("ds_programa") & "</font></td></tr>"
                   RS1.Close
                Else
                   ShowHTML "    <td width=""7%""><font size=""1""><b>Programa:</b></font></td><td><font size=""1"">Todos</font></td></tr>"
                End If
                If p_cd_acao > "" Then
                   DB_GetAcaoPPA_IS RS1, w_cliente, w_ano, p_cd_programa, Mid(p_cd_acao,5,4), null, null, null, null, null
                   ShowHTML "<tr valign=""top""><td><font size=""1""><b>Ação:</b></font></td><td><font size=""1"">" & Mid(p_cd_acao,5,4) & " - " & RS1("descricao_acao") & "</font></td>"
                   RS1.Close
                Else
                   ShowHTML "<tr valign=""top""><td><font size=""1""><b>Ação:</b></font></td><td><font size=""1"">Todas</font></td>"
                End If 
                ShowHTML "</ul></td></tr></table>"
                ShowHTML "</div></td></tr>"
                ShowHTML "<tr><td colspan=""2""><div align=""center""><hr NOSHADE color=#000000 size=2></div></td></tr>"    
                ShowHTML "<tr><td colspan=""2""><div align=""center""><font size=""3""><b>RELATÓRIO DE LIMITES</b></font></div></td></tr>"
                ShowHTML "<tr><td colspan=""2"">"
                ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
             End If               
             If w_unidade_atual = "" or w_unidade_atual <> RS("nm_unidade_resp") Then
                If w_unidade_atual <> "" Then
                   ShowHTML "       <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=""2"" align=""right""><b>Totais</b></td>"
                   ShowHTML "           <td align=""right""><b>" & FormatNumber(cDbl(Nvl(w_tot_trecho_limite,0)),2) & "</b></td>"
                   ShowHTML "           <td align=""right""><b>" & FormatNumber(cDbl(Nvl(w_tot_diaria_limite,0)),2) & "</b></td>"                   
                   ShowHTML "</table>"
                   w_tot_trecho_limite = 0 
                   w_tot_diaria_limite = 0
                End If
                ShowHTML "<br><tr><td colspan=""7""><hr NOSHADE color=#000000 size=4></td></tr>"
                ShowHTML "  <tr valign=""top"" bgcolor=""#f0f0f0""><td width=""40%""><font size=""2""><b>" & RS("nm_unidade_resp") & "</b></td>"
                ShowHTML "      <td colspan=""6"">"
                ShowHTML "    <table width=100%  border=""1"">"
                ShowHTML "      <tr><td>"
                ShowHTML "          <td align=""center""><b>Limite(R$)</b></td>"
                ShowHTML "          <td align=""center""><b>Utilizado(R$)</b></td>"
                ShowHTML "          <td align=""center""><b>Saldo(R$)</b></td></tr>"
                ShowHTML "      <tr><td><b>Passagem</b></td>" 
                ShowHTML "          <td align=""right"">" & FormatNumber(w_valor(i,1),2) & "</td>"
                ShowHTML "          <td align=""right"">" & FormatNumber(w_valor(i,2),2) & "</td>"
                ShowHTML "          <td align=""right"">" & FormatNumber(w_valor(i,1) - w_valor(i,2),2) & "</td></tr>"
                ShowHTML "      <tr><td><b>Diária</b></td>" 
                ShowHTML "          <td align=""right"">" & FormatNumber(w_valor(i,3),2) & "</td>" 
                ShowHTML "          <td align=""right"">" & FormatNumber(w_valor(i,4),2) & "</td>"
                ShowHTML "          <td align=""right"">" & FormatNumber(w_valor(i,3) - w_valor(i,4),2) & "</td><tr>"
                ShowHTML "    </table>"
                ShowHTML "   <tr><td colspan=""7""><hr NOSHADE color=#000000 size=4></td></tr>"
                i = i + 1
                ShowHTML "   <tr><td colspan=""7"">"
                ShowHTML "     <table width=100%  border=""1"" bordercolor=""#00000"">"
                ShowHTML "       <tr bgcolor=""" & conTrAlternateBgColor & """><td align=""center""><b>PCD</b></td>"
                ShowHTML "           <td align=""center""><b>Proposto</b></td>"
                ShowHTML "           <td align=""center""><b>Utilizado passagem</b></td>"
                ShowHTML "           <td align=""center""><b>Utilizado diária</b></td></tr>"
                w_linha = w_linha + 4
             End If
             If w_tipo_rel = "WORD" Then
                ShowHTML "       <tr><td>" & RS("codigo_interno") & "</td>"
             Else
                ShowHTML "       <tr><td><A class=""HL"" HREF=""" & "mod_is/" & "Viagem.asp?par=" & "Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste registro."">" & RS("codigo_interno") & "</a></td>"
             End If
             ShowHTML "              <td>" & RS("nm_prop") & "</td>"
             ShowHTML "              <td align=""right"">" & FormatNumber(cDbl(Nvl(RS("valor_trecho"),0)),2) & "</td>"
             ShowHTML "              <td align=""right"">" & FormatNumber((FormatNumber(cDbl(Nvl(RS("valor_diaria"),0)),2) - FormatNumber(cDbl(Nvl(RS("desconto_alimentacao"),0)),2)) + (FormatNumber(cDbl(Nvl(RS("valor_adicional"),0)),2) - FormatNumber(cDbl(Nvl(RS("desconto_transporte"),0)),2)),2) & "</td>"
             w_tot_trecho_limite = w_tot_trecho_limite + FormatNumber(cDbl(Nvl(RS("valor_trecho"),0)),2)
             w_tot_diaria_limite = w_tot_diaria_limite + FormatNumber(cDbl(Nvl(RS("valor_diaria"),0)),2) + FormatNumber(cDbl(Nvl(RS("valor_adicional"),0)),2) - FormatNumber(cDbl(Nvl(RS("desconto_alimentacao"),0)),2) - FormatNumber(cDbl(Nvl(RS("desconto_transporte"),0)),2)
             w_unidade_atual = RS("nm_unidade_resp")
             RS.MoveNext
             w_linha = w_linha + 1
          Wend
          ShowHTML "       <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=""2"" align=""right""><b>Total</b></td>"
          ShowHTML "           <td align=""right""><b>" & FormatNumber(cDbl(Nvl(w_tot_trecho_limite,0)),2) & "</b></td>"
          ShowHTML "           <td align=""right""><b>" & FormatNumber(cDbl(Nvl(w_tot_diaria_limite,0)),2) & "</b></td>"
          ShowHTML "</table>"
          ShowHTML "</table>"
       End If
    End If
    ShowHTML "   <tr><td colspan=""2""><br><font size=""2""><b>DADOS DA CONSULTA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
    ShowHTML "   <tr><td><font size=""1""><b>Consulta Realizada por:</b></font></td>"
    ShowHTML "       <td><font size=""1"">" &  Session("NOME_RESUMIDO") & "</font></td></tr>"
    ShowHTML "   <tr><td><font size=""1""><b>Data da Consulta:</b></font></td>"
    ShowHTML "       <td><font size=""1"">" &  FormataDataEdicao(FormatDateTime(now(),2)) & ", " & FormatDateTime(now(),4) & "</font></td></tr>"
    
    ShowHTML "    </table>"
    ShowHTML "  </div></td>"
    ShowHTML "</tr>"
    DesconectaBD
  ElseIf O = "P" Then
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", "Acao",P1,P2,P3,P4,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    SelecaoUnidade "Á<U>r</U>ea planejamento:", "R", null, p_sq_unidade, null, "p_sq_unidade", null, "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='p_sq_unidade'; document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    SelecaoProgramaPPA "<u>P</u>rograma PPA:", "P", null, w_cliente, w_ano, p_cd_programa, "p_cd_programa", "RELATORIO", "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='p_cd_programa'; document.Form.target=''; document.Form.O.value='P'; document.Form.submit();""", w_menu
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    SelecaoAcaoPPA "<u>A</u>ção PPA:", "A", null, w_cliente, w_ano, p_cd_programa, null, null, null, "p_cd_acao", null, null, null, w_menu
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "        <td><font size=""1""><b><u>T</u>ipo de relatório:</b><br><SELECT ACCESSKEY=""T"" CLASS=""STS"" NAME=""w_tipo_rel"" " & w_Disabled & ">"
    If nvl(w_tipo_rel,"-") = "Word"  Then
       ShowHTML "          <option value="""">Consulta na Tela"
       ShowHTML "          <option value=""Word"" SELECTED>Documento Word"
    Else
       ShowHTML "          <option value="""" SELECTED>Consulta na Tela"
       ShowHTML "          <option value=""Word"">Documento Word"
    End If
    ShowHTML "          </select></td><tr>"
    ShowHTML "    </table></td></tr>"
    ShowHTML "<tr><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"    
    ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=""2""><font size=""1""><b>ESCOLHA OS BLOCOS A SEREM VISUALIZADOS NO RELATÓRIO</b></font></td></tr>"    
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "          <td colspan=""2""><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_det_pcd"" value=""sim""> Detalhamento das pcd's</td>"
    ShowHTML "    </table></td></tr>"
    ShowHTML "    <table width=""100%"" border=""0"">"            
    ShowHTML "      <tr><td align=""center""><hr>"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Exibir"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</DIV>"
  ShowHTML "</BODY>"
  ShowHTML "</HTML>"  
End Sub


REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  Select Case Par
    Case "REL_LIMITE"           Rel_Limite
    Case Else
       Cabecalho
       ShowHTML "<BASE HREF=""" & conRootSIW & """>"
       BodyOpen "onLoad=document.focus();"
       ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
       ShowHTML "<HR>"
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
       Rodape
  End Select
End Sub
%>