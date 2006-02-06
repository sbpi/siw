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
REM  /Rel_Unidade.asp
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
w_Pagina     = "Rel_Unidade.asp?par="
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

  Dim p_modalidade
  Dim p_atualizsim_ini, p_atualiznao_ini
  Dim p_Ordena, w_regional, w_atual, p_tipo, p_versao
  Dim w_tot1, w_tot2
  Dim w_data_atual
  Dim w_matriculados, w_corporativo, w_ativo_unidade, w_sem_turma, w_alunos_eja
  Dim w_soma_eja
  Dim p_dif
  
  p_modalidade       = uCase(Request("p_modalidade")) 
  p_atualizsim_ini   = uCase(Request("p_atualizsim_ini")) 
  p_atualiznao_ini   = uCase(Request("p_atualiznao_ini")) 
  p_ordena           = uCase(Request("p_ordena"))
  p_tipo             = uCase(Request("p_tipo"))
  p_versao           = uCase(Request("p_versao"))
  p_dif              = Request("p_dif")

  w_atual            = uCase(Request("w_atual"))
  w_matriculados     = Request("w_matriculados")
  w_corporativo      = Request("w_corporativo")
  w_ativo_unidade    = Request("w_ativo_unidade")
  w_sem_turma        = Request("w_sem_turma")
  w_alunos_eja       = Request("w_alunos_eja")
  
  w_data_atual = Date
  
  
  If O = "L" or O = "W" Then
     If p_modalidade > "" Then
        DB_GetUnidadeRel RS1, Session("periodo"), Session("regional"), cDbl(trim(p_modalidade)), p_dif
     Else
        DB_GetUnidadeRel RS1, Session("periodo"), Session("regional"), null, p_dif
     End If
     If p_atualizsim_ini & p_atualiznao_ini &p_versao > "" Then
        w_filter = ""
        If p_atualizsim_ini > ""  Then w_filter = w_filter & " and dt_atualizacao > '" & cDate(p_atualizsim_ini) & "' "    End If
        If p_atualiznao_ini > ""  Then w_filter = w_filter & " and dt_atualizacao < '" & cDate(p_atualiznao_ini) & "' "    End If
        If p_versao         > ""  Then w_filter = w_filter & " and ds_versao like '%" & p_versao & "%' "    End If
        RS1.Filter = Mid(w_filter,6,255)
     End If
     If p_ordena > "" Then
        RS1.Sort = "ds_gre, " & p_ordena
     Else
        RS1.Sort = "ds_gre, ds_escola"
     End If
  End If

  If O = "W" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 6
     CabecalhoWord w_cliente, "Resumo das Unidades de Ensino", w_pag
     ExibeParametrosRel w_cliente
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     If InStr("P",O) > 0 Then
        ScriptOpen "JavaScript"
        CheckBranco
        FormataData
        ValidateOpen "Validacao"
        If O="P" Then
           Validate "periodo", "Período", "SELECT", "1", "1", "10", "1", "1"
           Validate "regional", "Regional", "SELECT", "", "1", "10", "1", "1"
           Validate "p_atualizsim_ini", "Comunica - data inicial", "DATA", "", "10", "10", "", "0123456789/"
           Validate "p_atualiznao_ini", "Não comunica - data inicial", "DATA", "", "10", "10", "", "0123456789/"
           Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
        End If
        ValidateClose
        ScriptClose
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If O = "L" or O = "W" Then
        BodyOpenClean "onLoad=document.focus();"
     Else
        BodyOpen "onLoad=document.focus();"
     End If
     If O = "L" Then
        CabecalhoRelatorio w_cliente, "Resumo das Unidades de Ensino"
        If w_regional > "" Then
           ExibeParametrosRel w_cliente
        End If
        ShowHTML "<BR>"
     Else
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
        ShowHTML "<HR>"
     End If
  End If

  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" or O = "W" Then
    ShowHTML "<tr><td align=""center"" colspan=9>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    If RS1.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=10 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      RS1.PageSize     = P4
      RS1.AbsolutePage = P3
      w_atual          = ""
      w_regional       = "a"
      w_tot1           = 0
      w_tot2           = 0
      w_matriculados   = 0
      w_corporativo    = 0
      w_ativo_unidade  = 0
      w_sem_turma      = 0
      w_alunos_eja     = 0
      While Not RS1.EOF and (RS1.AbsolutePage = P3 or O = "W" or p_tipo = "S")
        If w_regional <> RS1("regional") or w_atual <> RS1("co_unidade") Then
           If w_atual > "" Then
              w_tot2 = w_tot2 + w_tot1
              w_tot1 = 0
              If w_regional <> RS1("regional") Then
                 If p_tipo = "S" or O = "W" Then
                    ShowHTML "      <tr bgcolor=""" & conTrAlternateBgcolor & """ valign=""top"" align=""right"">"
                    ShowHTML "        <td colspan=3><font size=""2""><b>Totais da regional: </b></td>"
                    ShowHTML "        <td><font size=""2""><b>" & FormatNumber(w_matriculados,0) & "&nbsp;</b></td>"
                    ShowHTML "        <td><font size=""2""><b>" & FormatNumber(w_corporativo,0) & "&nbsp;</b></td>"
                    ShowHTML "        <td><font size=""2""><b>" & FormatNumber(w_ativo_unidade,0) & "&nbsp;</b></td>"
                    ShowHTML "        <td><font size=""2""><b>" & FormatNumber(w_sem_turma,0) & "&nbsp;</b></td>"
                    ShowHTML "        <td><font size=""2""><b>" & FormatNumber(w_alunos_eja,0) & "</b></td>"
                    ShowHTML "        <td><font size=""2"">&nbsp;</td>"
                    ShowHTML "        <td><font size=""2"">&nbsp;</td></tr>"
                 End If
                 w_matriculados  = 0
                 w_corporativo   = 0
                 w_ativo_unidade = 0
                 w_sem_turma     = 0
                 w_alunos_eja    = 0
                 If p_tipo = "S" or O = "W" Then 
                    ShowHTML "      <tr bgcolor=""" & conTrTotalBgcolor & """><td colspan=10><font size=""2""><b>Total de Unidades da Regional: " & FormatNumber(w_tot2,0) & "</b></td></tr>"
                    w_linha = w_linha + 3
                 End If
                 w_tot2 = 0
              End If
           End If
           If w_regional <> RS1("regional") Then
              ShowHTML "      <tr><td colspan=10><font size=""2""><b>&nbsp;</b></td></tr>"
              ShowHTML "      <tr bgcolor=""" & conTrTotalBgcolor & """><td colspan=10><font size=""2""><b>REGIONAL DE ENSINO: " & ucase(RS1("ds_gre")) & "</b></td></tr>"
              'If p_tipo = "N" Then
                 ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                 ShowHTML "          <td><font size=""1""><b>SIGRH</font></td>"
                 ShowHTML "          <td><font size=""1""><b>Unidade</font></td>"
                 ShowHTML "          <td width=""23%""><font size=""1""><b>Nome da unidade</font></td>"
                 ShowHTML "          <td><font size=""1""><b>Matriculados</font></td>"
                 ShowHTML "          <td><font size=""1""><b>Ativos no corporativo</font></td>"
                 ShowHTML "          <td><font size=""1""><b>Ativos na unidade</font></td>"
                 ShowHTML "          <td><font size=""1""><b>Sem turma</font></td>"
                 ShowHTML "          <td><font size=""1""><b>EJA</font></td>"
                 ShowHTML "          <td><font size=""1""><b>Versão Instalada</font></td>"
                 ShowHTML "          <td><font size=""1""><b>Última transferência</font></td>"
                 ShowHTML "        </tr>"
              'End If
              w_regional = RS1("regional")
           End If
           'ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=8><font size=""2""><b>Unidade: " & RS1("ds_escola") & "</b></td></tr>"
           w_atual    = RS1("co_unidade")
        End If
        If w_linha > 30 and O = "W" Then
           ShowHTML "    </table>"
           ShowHTML "  </td>"
           ShowHTML "</tr>"
           ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td colspan=9><font size=""1""><b>* :</b> Unidade de ensino que não transfere arquivo há mais de 7 dias - <b>Versão:</b> Versão instalada</font></td>"
           ShowHTML "</table>"
           ShowHTML "</center></div>"
           ShowHTML "    <br style=""page-break-after:always"">"
           w_linha = 6
           w_pag   = w_pag + 1
           CabecalhoWord w_cliente, "Resumo das Unidades de Ensino", w_pag
           ExibeParametrosRel w_cliente
           ShowHTML "<div align=center><center>"
           ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
           ShowHTML "<tr><td align=""center"" colspan=9>"
           'If p_unidade > "" Then
           '   ShowHTML "<br>Unidade de Ensino: <b>" & RS1("ds_escola") & "</b>"
           'End If
           If (w_regional <> RS1("regional")) Then
              ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
              ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
              ShowHTML "          <td><font size=""1""><b>SIGRH</font></td>"
              ShowHTML "          <td><font size=""1""><b>Unidade</font></td>"
              ShowHTML "          <td width=""23%""><font size=""1""><b>Nome da unidade</font></td>"
              ShowHTML "          <td><font size=""1""><b>Matriculados</font></td>"
              ShowHTML "          <td><font size=""1""><b>Ativos no corporativo</font></td>"
              ShowHTML "          <td><font size=""1""><b>Ativos na unidades</font></td>"
              ShowHTML "          <td><font size=""1""><b>Sem turma</font></td>"
              ShowHTML "          <td><font size=""1""><b>EJA</font></td>"
              ShowHTML "          <td><font size=""1""><b>Versão Instalada</font></td>"
              ShowHTML "          <td><font size=""1""><b>Última transferência</font></td>"
              ShowHTML "        </tr>"
           End If
        End If
        If p_tipo = "N" Then
           If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
           ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"" align=""right"">"
           ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS1("co_sigre"),"---") & "</td>"
           ShowHTML "        <td align=""left""><font size=""1"">" & Nvl(RS1("co_unidade"),"---") & "</td>"
           If DateDiff("d",RS1("dt_atualizacao"),w_data_atual) > 7 Then
              ShowHTML "        <td align=""left""><font size=""1"">" & Nvl(RS1("ds_escola"),"---") & "*</td>"
           Else
              ShowHTML "        <td align=""left""><font size=""1"">" & Nvl(RS1("ds_escola"),"---") & "</td>"
           End If 
           ShowHTML "        <td><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("nu_matriculados"),0)),0) & "&nbsp;</td>"
           ShowHTML "        <td><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("nu_ativos"),0)),0) & "&nbsp;</td>"
           ShowHTML "        <td><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("nu_alunosativos"),0)),0) & "&nbsp;</td>"
           ShowHTML "        <td><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("nu_semturma"),0)),0) & "&nbsp;</td>"
           w_soma_eja = cDbl(Nvl(RS1("nu_alunoseja1"),0)) + cDbl(Nvl(RS1("nu_alunoseja2"),0))
           If w_soma_eja = 0 Then
              ShowHTML "        <td><font size=""1"">---&nbsp;</td>"
            Else
              ShowHTML "        <td><font size=""1"">" & FormatNumber(w_soma_eja,0) & "&nbsp;</td>"
           End If
           ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS1("ds_versao"),"---") & "</td>"
           If RS1("dt_atualizacao") > "" Then
              ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS1("dt_atualizacao")),"---") & "</td>"
           Else
              ShowHTML "        <td align=""center""><font size=""1"">---</td>"
           End If
           ShowHTML "      </tr>"
           w_linha = w_linha + 1
        End If
        If RS1("nu_matriculados") > ""   Then w_matriculados = w_matriculados + cDbl(RS1("nu_matriculados"))     End If
        If RS1("nu_ativos") > ""         Then w_corporativo = w_corporativo + cDbl(RS1("nu_ativos"))             End If
        If RS1("nu_alunosativos") > ""   Then w_ativo_unidade = w_ativo_unidade + cDbl(RS1("nu_alunosativos"))   End If
        If RS1("nu_semturma") > ""       Then w_sem_turma = w_sem_turma + cDbl(RS1("nu_semturma"))               End If
        If RS1("nu_alunoseja1") > "" or RS1("nu_alunoseja2") > ""  Then
           w_alunos_eja = w_alunos_eja + cDbl(Nvl(RS1("nu_alunoseja1"),0)) + cDbl(Nvl(RS1("nu_alunoseja2"),0))
        End If
        RS1.MoveNext
        w_tot1  = w_tot1 + 1
      Wend
    End If
    If p_tipo = "S" or O = "W" Then
       ShowHTML "      <tr bgcolor=""" & conTrAlternateBgcolor & """ valign=""top"" align=""right"">"
       ShowHTML "        <td colspan=3><font size=""2""><b>Totais da regional: </b></td>"
       ShowHTML "        <td><font size=""2""><b>" & FormatNumber(w_matriculados,0) & "&nbsp;</b></td>"
       ShowHTML "        <td><font size=""2""><b>" & FormatNumber(w_corporativo,0) & "&nbsp;</b></td>"
       ShowHTML "        <td><font size=""2""><b>" & FormatNumber(w_ativo_unidade,0) & "&nbsp;</b></td>"
       ShowHTML "        <td><font size=""2""><b>" & FormatNumber(w_sem_turma,0) & "&nbsp;</b></td>"
       ShowHTML "        <td><font size=""2""><b>" & FormatNumber(w_alunos_eja,0) & "</b></td>"
       ShowHTML "        <td><font size=""2"">&nbsp;</td>"
       ShowHTML "        <td><font size=""2"">&nbsp;</td></tr>"
    End If
    w_matriculados  = 0
    w_corporativo   = 0
    w_ativo_unidade = 0
    w_sem_turma     = 0
    w_alunos_eja    = 0 
    If (w_atual > "" and p_tipo = "S") or (w_atual > "" and O = "W")  Then
       'ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=8><font size=""1""><b>Total da unidade: " & FormatNumber(w_tot1,0) & "</b></td></tr>"
       w_tot2 = w_tot2 + w_tot1
       ShowHTML "      <tr bgcolor=""" & conTrTotalBgcolor & """><td colspan=10><font size=""2""><b>Total de Unidades da Regional: " & FormatNumber(w_tot2,0) & "</b></td></tr>"
    End If
    If p_tipo = "S" or O = "W" Then
       If Session("regional") = 00 or Session("regional") = "" Then
          ShowHTML "      <tr bgcolor=""" & conTrAlternateBgcolor & """><td colspan=10><font size=""2""><b>Total de Unidades da Rede: " & FormatNumber(RS1.RecordCount,0) & "</b></td></tr>"
       End If
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    If p_tipo = "N" or O = "W" Then  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td colspan=10><font size=""1""><b>* :</b> Unidade de ensino que não transfere arquivo há mais de 7 dias - <b>Versão:</b> Versão instalada</font></td>" End If
    If O = "L" and p_tipo = "N" Then
       ShowHTML "<tr><td align=""center"" colspan=9>"
       MontaBarra w_dir&w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG, RS1.PageCount, P3, P4, RS1.RecordCount
       ShowHTML "</tr>"
    End If
         
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Dir&w_Pagina&par, "POST", "return(Validacao(this));", "RelDuplic",P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Visualizar</i> para exibir a relação na tela ou sobre <i>Gerar Word</i> para gerar um arquivo no formato Word. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    SelecaoPeriodoLetivo "Perío<u>d</u>o letivo:", "D", null, Session("periodo"), null, "periodo", null
    SelecaoRegional "<u>R</u>egional:", "R", null, Session("regional"), null, "regional", "informal = 'N'", null
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    SelecaoModEnsino "<u>M</u>odalidade de ensino:", "M", null, p_modalidade, null, "p_modalidade", null, null
    SelecaoVersao "<u>V</u>ersão:", "V", null, p_versao, null, "p_versao", null
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Somente unidades que não comunicam desde: &nbsp;&nbsp;&nbsp;<input type=""text"" class=""sti"" size=10 maxlength=10 name=""p_atualiznao_ini"" value=""" & p_atualiznao_ini & """ onKeyDown=""FormataData(this,event);"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Somente unidades que comunicam desde: &nbsp;&nbsp;&nbsp;<input type=""text"" class=""sti"" size=10 maxlength=10 name=""p_atualizsim_ini"" value=""" & p_atualizsim_ini & """ onKeyDown=""FormataData(this,event);"">"
    ShowHTML "      <tr>"
    MontaRadioNS "<b>Somente unidades com diferença de alunos?</b>", p_dif, "p_dif"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_ordena"" size=""1"">"
    If p_Ordena="DT_ATUALIZACAO" Then
       ShowHTML "          <option value="""" SELECTED>Descrição unidades<option value=""CO_SIGRE"">SIGRH<option value=""CO_UNIDADE"">Código unidade<option value=""DT_ATUALIZACAO"" SELECTED>Última atualização"
    ElseIf p_Ordena="CO_SIGRE" Then
       ShowHTML "          <option value="""">Descrição unidades<option value=""CO_SIGRE"" SELECTED>SIGRH<option value=""CO_UNIDADE"">Código unidade<option value=""DT_ATUALIZACAO"">Última atualização"
    ElseIf p_Ordena="CO_UNIDADE" Then
       ShowHTML "          <option value="""">Descrição unidades<option value=""CO_SIGRE"">SIGRH<option value=""CO_UNIDADE"" SELECTED>Código unidade<option value=""DT_ATUALIZACAO"">Última atualização"
    Else
       ShowHTML "          <option value="""" SELECTED>Descrição unidades<option value=""CO_SIGRE"">SIGRH<option value=""CO_UNIDADE"">Código unidade<option value=""DT_ATUALIZACAO"">Última atualização"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td>"
    ShowHTML "      </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Exibir:</b><br>"
    If p_tipo = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_tipo"" value=""S""> Apenas totais <input " & w_Disabled & " type=""radio"" name=""p_tipo"" value=""N"" checked> Totais e detalhes&nbsp;&nbsp;&nbsp;"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_tipo"" value=""S"" checked> Apenas totais <input " & w_Disabled & " type=""radio"" name=""p_tipo"" value=""N""> Totais e detalhes&nbsp;&nbsp;&nbsp;"
    End If
    ShowHTML "          </table>"
    ShowHTML "       </tr>"    
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Visualizar"" onClick=""document.Form.O.value='L'"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gerar Word"" onClick=""document.Form.O.value='W'"">"
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Remover filtro"">"
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
  ShowHTML "</center>"
  Rodape

  Set w_regional         = Nothing 
  Set w_tot1             = Nothing 
  Set w_tot2             = Nothing 
  Set p_tipo             = Nothing
  Set p_modalidade       = Nothing 
  Set p_atualizsim_ini   = Nothing 
  Set p_atualiznao_ini   = Nothing
  Set p_versao           = Nothing
  Set w_matriculados     = Nothing
  Set w_corporativo      = Nothing
  Set w_ativo_unidade    = Nothing
  Set p_ordena           = Nothing

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

