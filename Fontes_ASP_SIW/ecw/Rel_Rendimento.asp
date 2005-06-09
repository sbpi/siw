<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DB_Relatorio.asp" -->
<!-- #INCLUDE FILE="DB_Tipo_Curso.asp" -->
<!-- #INCLUDE FILE="DB_Turno.asp" -->
<!-- #INCLUDE FILE="DB_Serie.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Rel_Rendimento.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Emite lista de professores
REM Mail     : alex@sbpi.com.br
REM Criacao  : 29/08/2003, 08:48
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
w_Pagina     = "Rel_Rendimento.asp?par="
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
REM Rotina de consulta de professores
REM -------------------------------------------------------------------------
Sub Inicial

  Dim p_unidade, p_bimestre, p_infrequentes, p_causas
  Dim p_1, p_2, p_3, p_4, p_5, p_6, p_inf, p_por2, p_eda2, p_edf2, p_mat2, p_cfb, p_geo2
  Dim p_por3, p_eda3, p_edf3, p_mat3, p_geo3, p_fis, p_qui, p_bio, p_soc, p_fil, p_his3, p_lem3
  Dim p_his2, p_lem2, p_enr2, p_enr3, p_area
  Dim p_Ordena, w_regional, w_atual
  Dim w_tot1, w_tot2, p_display1, p_display2

  p_unidade          = uCase(Request("p_unidade"))
  p_bimestre         = uCase(Request("p_bimestre"))
  p_infrequentes     = uCase(Request("p_infrequentes"))
  p_1                = uCase(Request("p_1"))
  p_2                = uCase(Request("p_2"))
  p_3                = uCase(Request("p_3"))
  p_4                = uCase(trim(Request("p_4")))
  p_ordena           = uCase(Request("p_ordena"))
  p_inf              = uCase(Request("p_inf"))
  p_por2             = uCase(Request("p_por2"))
  p_por3             = uCase(Request("p_por3"))
  p_eda2             = uCase(Request("p_eda2"))
  p_eda3             = uCase(Request("p_eda3"))
  p_edf2             = uCase(Request("p_edf2"))
  p_edf3             = uCase(Request("p_edf3"))
  p_mat2             = uCase(Request("p_mat2"))
  p_mat3             = uCase(Request("p_mat3"))
  p_cfb              = uCase(Request("p_cfb"))
  p_geo2             = uCase(Request("p_geo2"))
  p_geo3             = uCase(Request("p_geo3"))
  p_his2             = uCase(Request("p_his2"))
  p_his3             = uCase(Request("p_his3"))
  p_lem2             = uCase(Request("p_lem2"))
  p_lem3             = uCase(Request("p_lem3"))
  p_enr2             = uCase(Request("p_enr2"))
  p_enr3             = uCase(Request("p_enr3"))
  p_fis              = uCase(Request("p_fis"))
  p_qui              = uCase(Request("p_qui"))
  p_bio              = uCase(Request("p_bio"))
  p_fil              = uCase(Request("p_fil"))
  p_soc              = uCase(Request("p_soc"))
    
  If O = "L" or O = "W" Then
     DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
     RS1.Sort = "ds_ordem_imp"
  End If
  
  If O = "W" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 6
     CabecalhoWord w_cliente, "Rendimento Escolar", w_pag
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
           Validate "p_unidade", "Unidade", "SELECT", "1", "1", "10", "1", "1"
           Validate "p_1", "Modalidade de ensino", "SELECT", "1", "1", "10", "1", "1"
           Validate "p_bimestre", "Bimestre", "SELECT", "1", "1", "10", "1", "1"
           'Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
        End If
        ValidateClose
        ShowHTML "function exibircampos(src){"
        ShowHTML "   if  (src.selectedIndex == 1) {"
        ShowHTML "          document.all(""tr3"").style.display  ="""";"
        ShowHTML "          document.all(""tr4"").style.display  ="""";"
        ShowHTML "          document.all(""tr5"").style.display  ="""";"
        ShowHTML "          document.all(""tr6"").style.display  ="""";"
        ShowHTML "          document.all(""tr7"").style.display  ="""";"
        ShowHTML "          document.all(""tr8"").style.display  ="""";"
        ShowHTML "          document.all(""tr9"").style.display  ="""";"
        ShowHTML "          document.all(""tr10"").style.display ="""";"
        ShowHTML "          document.all(""tr11"").style.display ="""";"
        ShowHTML "          document.all(""tr12"").style.display ="""";"
        ShowHTML "   }else {"
        ShowHTML "          if (src.selectedIndex == 2) {" 
        ShowHTML "             document.all(""tr14"").style.display ="""";"
        ShowHTML "             document.all(""tr15"").style.display ="""";"
        ShowHTML "             document.all(""tr16"").style.display ="""";"
        ShowHTML "             document.all(""tr17"").style.display ="""";"
        ShowHTML "             document.all(""tr18"").style.display ="""";"
        ShowHTML "             document.all(""tr19"").style.display ="""";"
        ShowHTML "             document.all(""tr20"").style.display ="""";"
        ShowHTML "             document.all(""tr21"").style.display ="""";"
        ShowHTML "             document.all(""tr22"").style.display ="""";"
        ShowHTML "             document.all(""tr23"").style.display ="""";"
        ShowHTML "             document.all(""tr24"").style.display ="""";"
        ShowHTML "             document.all(""tr25"").style.display ="""";"
        ShowHTML "             document.all(""tr26"").style.display ="""";"
        ShowHTML "             document.all(""tr27"").style.display ="""";"
        ShowHTML "          }"
        ShowHTML "   }" 
        ShowHTML "}"
        ScriptClose
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
     If O = "L" or O = "W" Then
        BodyOpenClean "onLoad=document.focus();"
     Else
        BodyOpen "onLoad=document.focus();"
     End If
     If O = "L" Then
        CabecalhoRelatorio w_cliente, "Rendimento Escolar"
        ExibeParametrosRel w_cliente
        ShowHTML "<BR>"
     ElseIf O = "W" Then
        CabecalhoWord w_cliente, "Rendimento Escolar", w_pag
        ExibeParametrosRel w_cliente
     Else
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
        ShowHTML "<HR>"
     End If
  End If
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" or O = "W" Then
      DB_GetCourseTypeData RS, p_1
      ShowHTML "<tr><td align=""center"" colspan=6><font size=""3""><b>" & RS("ds_tipo_curso") & "</td></tr>"
      ShowHTML "<tr><td align=""center"" colspan=6><font size=""2""><b>Instrumento de Registro do Rendimento Insatisfatório do Total de Turmas da Escola</td></tr>"
      ShowHTML "<tr><td align=""center"" colspan=6>&nbsp;"
     If RS1.EOF Then
        ShowHTML "<tr><td align=""center"" colspan=6>"
        ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
     Else
      ShowHTML "<tr valign=""top""><td bgcolor=""#FAEBD7"" colspan=6><table border=1 cellpadding=2 cellspacing=5 width=""100%"">"
      ShowHTML "  <tr valign=""top"">"
      ShowHTML "    <td colspan=2><font size=1>Unidade:<br><b>" & Nvl(Trim(RS1("ds_unidade")),"---")
      ShowHTML "    <td colspan=1><font size=1>Turno:<br><b>" & Nvl(p_4,"---")
      ShowHTML "    <td colspan=1><font size=1>Série:<br><b>" & Nvl(p_2,"---")
      If p_3 > "" Then
         DB_GetTurmaList RS, Session("Periodo"), p_unidade
         RS.Filter = " co_turma = " & p_3
         ShowHTML "    <td colspan=1><font size=1>Turma:<br><b>" & Nvl(RS("co_letra_turma"),"---")
      Else
         ShowHTML "    <td colspan=1><font size=1>Turma:<br><b>---"
      End If
      ShowHTML "  <tr valign=""top"">"
      ShowHTML "    <td colspan=1><font size=1 colspan=1>Nº de Turmas:<br><b>" & Nvl(RS1("turma"),"---")
      ShowHTML "    <td colspan=1><font size=1 colspan=1>Nº de Alunos Matriculados:<br><b>" & Nvl(RS1("matriculados"),"---")    
      ShowHTML "    <td colspan=1><font size=1 colspan=1>Nº de Alunos Frequentes:<br><b>" & Nvl(RS1("frequentes"),"---")
      ShowHTML "    <td colspan=1><font size=1 colspan=1>Nº de Alunos Infrequentes:<br><b>" & Nvl(p_infrequentes,"---")
      ShowHTML "    <td colspan=1><font size=1 colspan=1>Bimestre/Ano:<br><b>" & p_bimestre &"º/"& Mid(Session("periodo"),1,4)     
      ShowHTML "  </table>"
      ShowHTML "<tr><td align=""center"" colspan=6>"
      ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
      RS1.PageSize     = P4
      RS1.AbsolutePage = P3
      ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
      ShowHTML "          <td align=""center"" rowspan=2><font size=""1""><b>Área de Conhecimento</font></td>"
      ShowHTML "          <td align=""center"" rowspan=2><font size=""1""><b>Componente Curricular</font></td>"
      ShowHTML "          <td align=""center"" rowspan=2><font size=""1""><b>Total de Alunos</font></td>"
      ShowHTML "          <td align=""center"" rowspan=1 colspan=2><font size=""1""><b>Rendimento Insatisfatório</font></td>"
      ShowHTML "          <td align=""center"" rowspan=2><font size=""1""><b>Causas</font></td>"
      ShowHTML "        </tr>"
      ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
      ShowHTML "          <td align=""center"" rowspan=1><font size=""1""><b>Nº Alunos</font></td>"
      ShowHTML "          <td align=""center"" rowspan=1><font size=""1""><b>%</font></td>"         
      ShowHTML "        </tr>"
      'While Not RS1.EOF and (RS1.AbsolutePage = P3 or O = "W")
         
         'If w_linha > 30 and O = "W" Then
         '   ShowHTML "    </table>"
         '   ShowHTML "  </td>"
         '   ShowHTML "</tr>"
         '   ShowHTML "</table>"
         '   ShowHTML "</center></div>"
         '   ShowHTML "    <br style=""page-break-after:always"">"
         '   w_linha = 9
         '   w_pag   = w_pag + 1
         '   CabecalhoWord w_cliente, "Rendimento Escolar", w_pag
         '   ExibeParametrosRel w_cliente
         '   ShowHTML "<div align=center><center>"
         '   ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
         '   ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=6><font size=""2""><b>Unidade: " & RS1("ds_unidade") & "</b></td></tr>"
         '   ShowHTML "</table>"
         '   ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
         '   ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
         '   ShowHTML "          <td align=""center"" rowspan=2><font size=""1""><b>Área de Conhecimento</font></td>"
         '   ShowHTML "          <td align=""center"" rowspan=2><font size=""1""><b>Componente Curricular</font></td>"
         '   ShowHTML "          <td align=""center"" rowspan=2><font size=""1""><b>Total de Alunos</font></td>"
         '   ShowHTML "          <td align=""center"" rowspan=1><font size=""1""><b>Rendimento Insatisfatório</font></td>"
         '   ShowHTML "          <td align=""center"" rowspan=2><font size=""1""><b>Causas</font></td>"
         '   ShowHTML "        </tr>"
         '   ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
         '   ShowHTML "          <td align=""center"" colspan=1><font size=""1""><b>Nº Alunos</font></td>"
         '   ShowHTML "          <td align=""center"" colspan=1><font size=""1""><b>%</font></td>"         
         '   ShowHTML "        </tr>"
         'End If
         'w_cor = conTrBgColor
         'Select Case trim(RS1("co_disciplina"))
         '   Case "POR2"
         '      p_causas = p_por2
         '      p_area   = "Linguagem, códigos e suas Tecnologias"
         '   Case "EDA2"
         '      p_causas = p_eda2 
         '      p_area   = "Linguagem, códigos e suas Tecnologias"
         '   Case "EDF2"
         '      p_causas = p_edf2
         '      p_area   = "Linguagem, códigos e suas Tecnologias"
          '  Case "MAT2"
         '      p_causas = p_mat2
         '      p_area   = "Ciências da Natureza, Matemática e suas Tecnologias"
         '   Case "CFB"
         '      p_causas = p_cfb
         '      p_area   = "Ciências da Natureza, Matemática e suas Tecnologias"
         '   Case "GEO2"
         '      p_causas = p_geo2
         '      p_area   = "Ciências Humanas e suas Tecnologias"
         '   Case "HIS2"
         '      p_causas = p_his2
         '      p_area   = "Ciências Humanas e suas Tecnologias"
         '   Case "LEM2"
         '      p_causas = p_lem2
         '      p_area   = "Parte Diversificada"
         '   Case "ENR"
         '      p_causas = p_enr2&p_enr3
         '      p_area   = "Parte Diversificada"
         '   Case "POR3"
         '      p_causas = p_por3
         '      p_area   = "Linguagem, códigos e suas Tecnologias"
         '   Case "EDA3"
         '      p_causas = p_eda3
         '      p_area   = "Linguagem, códigos e suas Tecnologias"
         '   Case "EDF3"
         '      p_causas = p_edf3
         '      p_area   = "Linguagem, códigos e suas Tecnologias"
         '   Case "MAT3"
         '      p_causas = p_mat3
         '      p_area   = "Ciências da Natureza, Matemática e suas Tecnologias"
         '   Case "FIS"
         '      p_causas = p_fis
         '      p_area   = "Ciências da Natureza, Matemática e suas Tecnologias"
         '   Case "QUI"
         '      p_causas = p_qui
         '      p_area   = "Ciências da Natureza, Matemática e suas Tecnologias"
         '   Case "BIO"
         '      p_causas = p_bio
         '      p_area   = "Ciências da Natureza, Matemática e suas Tecnologias"
         '   Case "GEO3"
         '      p_causas = p_geo3
         '      p_area   = "Ciências Humanas e suas Tecnologias"
         '   Case "HIS3"
         '      p_causas = p_his3
         '      p_area   = "Ciências Humanas e suas Tecnologias"
         '   Case "FIL"
         '      p_causas = p_fil
         '      p_area   = "Ciências Humanas e suas Tecnologias"
         '   Case "SOC"
         '      p_causas = p_soc
         '      p_area   = "Ciências Humanas e suas Tecnologias"
         '   Case "LEM3"
         '      p_causas = p_lem3
         '      p_area   = "Parte Diversificada"
         'End Select
         'ShowHTML "      <tr bgcolor=""" & w_cor & """>"
         'ShowHTML "        <td><font size=""1"">" & p_area & "</td>"
         'ShowHTML "        <td><font size=""1"">" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
         'ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
         'ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
         'ShowHTML "        <td align=""center""><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>" 
         'ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(p_causas,"---") & "</td>"
         'w_linha = w_linha + 1
         'RS1.MoveNext
         'w_tot1  = w_tot1 + 1
      'wend
    If w_linha > 30 and O = "W" Then
            ShowHTML "    </table>"
            ShowHTML "  </td>"
            ShowHTML "</tr>"
            ShowHTML "</table>"
            ShowHTML "</center></div>"
            ShowHTML "    <br style=""page-break-after:always"">"
            w_linha = 9
            w_pag   = w_pag + 1
            CabecalhoWord w_cliente, "Rendimento Escolar", w_pag
            ExibeParametrosRel w_cliente
            ShowHTML "<div align=center><center>"
            ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
            ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=6><font size=""2""><b>Unidade: " & RS1("ds_unidade") & "</b></td></tr>"
            ShowHTML "</table>"
            ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
            ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
            ShowHTML "          <td align=""center"" rowspan=2><font size=""1""><b>Área de Conhecimento</font></td>"
            ShowHTML "          <td align=""center"" rowspan=2><font size=""1""><b>Componente Curricular</font></td>"
            ShowHTML "          <td align=""center"" rowspan=2><font size=""1""><b>Total de Alunos</font></td>"
            ShowHTML "          <td align=""center"" rowspan=1><font size=""1""><b>Rendimento Insatisfatório</font></td>"
            ShowHTML "          <td align=""center"" rowspan=2><font size=""1""><b>Causas</font></td>"
            ShowHTML "        </tr>"
            ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
            ShowHTML "          <td align=""center"" colspan=1><font size=""1""><b>Nº Alunos</font></td>"
            ShowHTML "          <td align=""center"" colspan=1><font size=""1""><b>%</font></td>"         
            ShowHTML "        </tr>"
         End If
         If p_1 = 2 Then
            w_cor = conTrBgColor
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            ShowHTML "        <td rowspan=3><font size=""1"">Linguagem, códigos e suas Tecnologias</td>"
            DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
            RS1.Filter = " co_disciplina = 'POR2'"
            If Not RS1.EOF Then
               ShowHTML "        <td rowspan=1><font size=""1"">" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_por2,"---") & "</td>"
            Else
               ShowHTML "        <td rowspan=1><font size=""1"">LÍNGUA PORTUGUESA</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_por2,"---") & "</td>"
            End If
            DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
            RS1.Filter = " co_disciplina = 'EDA2'"
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            If Not RS1.EOF Then
               ShowHTML "        <td rowspan=1><font size=""1"" >" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_eda2,"---") & "</td>"
            Else
               ShowHTML "        <td rowspan=1><font size=""1"">ARTE</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_eda2,"---") & "</td>"
            End If
            DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
            RS1.Filter = " co_disciplina = 'EDF2'"
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            If Not RS1.EOF Then
               ShowHTML "        <td rowspan=1><font size=""1"">" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>" 
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(p_edf2,"---") & "</td>"
            Else
               ShowHTML "        <td rowspan=1><font size=""1"">EDUCAÇÃO FÍSICA</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_edf2,"---") & "</td>"
            End If
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            ShowHTML "        <td rowspan=2><font size=""1"">Ciências da Natureza, Matemática e suas Tecnologias</td>"
            DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
            RS1.Filter = " co_disciplina = 'MAT2'"
            If Not RS1.EOF Then
               ShowHTML "        <td rowspan=1><font size=""1"">" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>" 
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(p_mat2,"---") & "</td>"
            Else
               ShowHTML "        <td rowspan=1><font size=""1"">MATEMÁTICA</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_mat2,"---") & "</td>"
            End If
            DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
            RS1.Filter = " co_disciplina = 'CFB'"
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            If Not RS1.EOF Then
               ShowHTML "        <td rowspan=1><font size=""1"">" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>" 
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(p_cfb,"---") & "</td>"
               ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            Else
               ShowHTML "        <td rowspan=1><font size=""1"">CIÊNCIAS NATURAIS</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_cfb,"---") & "</td>"
            End If
            ShowHTML "        <td rowspan=2><font size=""1"">Ciências Humanas e suas Tecnologias</td>"
            DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
            RS1.Filter = " co_disciplina = 'GEO2'"
            If Not RS1.EOF Then
               ShowHTML "        <td rowspan=1><font size=""1"">" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>" 
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(p_geo2,"---") & "</td>"
            Else
               ShowHTML "        <td rowspan=1><font size=""1"">GEOGRAFIA</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_geo2,"---") & "</td>"
            End If
            DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
            RS1.Filter = " co_disciplina = 'HIS2'"
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            If Not RS1.EOF Then
               ShowHTML "        <td rowspan=1><font size=""1"">" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>" 
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(p_his2,"---") & "</td>"
            Else
               ShowHTML "        <td rowspan=1><font size=""1"">HISTÓRIA</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_his2,"---") & "</td>"
            End If
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            ShowHTML "        <td rowspan=2><font size=""1"">Parte Diversificada</td>"
            DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
            RS1.Filter = " co_disciplina = 'LEM2'"
            If Not RS1.EOF Then
               ShowHTML "        <td rowspan=1><font size=""1"">" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>" 
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(p_lem2,"---") & "</td>"
            Else
               ShowHTML "        <td rowspan=1><font size=""1"">LEM</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_lem2,"---") & "</td>"
            End If
            DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
            RS1.Filter = " co_disciplina = 'ENR'"
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            If Not RS1.EOF Then
               ShowHTML "        <td rowspan=1><font size=""1"">" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>" 
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(p_enr2,"---") & "</td>"
            Else
               ShowHTML "        <td rowspan=1><font size=""1"">ENSINO RELIGIOSO</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_enr2,"---") & "</td>"
            End If
         ElseIf p_1 = 3 Then
            w_cor = conTrBgColor
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            ShowHTML "        <td rowspan=3><font size=""1"">Linguagem, códigos e suas Tecnologias</td>"
            DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
            RS1.Filter = " co_disciplina = 'POR3'"
            If Not RS1.EOF Then
               ShowHTML "        <td rowspan=1><font size=""1"">" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_por3,"---") & "</td>"
            Else
               ShowHTML "        <td rowspan=1><font size=""1"">LÍNGUA PORTUGUESA</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_por3,"---") & "</td>"
            End If
            DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
            RS1.Filter = " co_disciplina = 'EDA3'"
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            If Not RS1.EOF Then
               ShowHTML "        <td rowspan=1><font size=""1"" >" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_eda3,"---") & "</td>"
            Else
               ShowHTML "        <td rowspan=1><font size=""1"">ARTE</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_eda3,"---") & "</td>"
            End If
            DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
            RS1.Filter = " co_disciplina = 'EDF3'"
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            If Not RS1.EOF Then
               ShowHTML "        <td rowspan=1><font size=""1"">" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>" 
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(p_edf3,"---") & "</td>"
            Else
               ShowHTML "        <td rowspan=1><font size=""1"">EDUAÇÃO FÍSICA</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_edf3,"---") & "</td>"
            End If
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            ShowHTML "        <td rowspan=4><font size=""1"">Ciências da Natureza, Matemática e suas Tecnologias</td>"
            DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
            RS1.Filter = " co_disciplina = 'MAT3'"
            If Not RS1.EOF Then
               ShowHTML "        <td rowspan=1><font size=""1"">" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>" 
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(p_mat3,"---") & "</td>"
            Else
               ShowHTML "        <td rowspan=1><font size=""1"">MATEMÁTICA</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_mat3,"---") & "</td>"
            End If
            DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
            RS1.Filter = " co_disciplina = 'FIS'"
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            If Not RS1.EOF Then
               ShowHTML "        <td rowspan=1><font size=""1"">" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>" 
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(p_fis,"---") & "</td>"
            Else
               ShowHTML "        <td rowspan=1><font size=""1"">FÍSICA</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_fis,"---") & "</td>"
            End If
            DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
            RS1.Filter = " co_disciplina = 'QUI'"
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            If Not RS1.EOF Then
               ShowHTML "        <td rowspan=1><font size=""1"">" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>" 
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(p_qui,"---") & "</td>"
            Else
               ShowHTML "        <td rowspan=1><font size=""1"">QUÍMICA</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_qui,"---") & "</td>"
            End If
            DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
            RS1.Filter = " co_disciplina = 'BIO'"
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            If Not RS1.EOF Then
               ShowHTML "        <td rowspan=1><font size=""1"">" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>" 
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(p_bio,"---") & "</td>"
            Else
               ShowHTML "        <td rowspan=1><font size=""1"">BIOLOGIA</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_bio,"---") & "</td>"
            End If
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            ShowHTML "        <td rowspan=4><font size=""1"">Ciências Humanas e suas Tecnologias</td>"
            DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
            RS1.Filter = " co_disciplina = 'GEO3'"
            If Not RS1.EOF Then
               ShowHTML "        <td rowspan=1><font size=""1"">" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>" 
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(p_geo3,"---") & "</td>"
            Else
               ShowHTML "        <td rowspan=1><font size=""1"">GEOGRAFIA</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_geo3,"---") & "</td>"
            End If
            DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
            RS1.Filter = " co_disciplina = 'HIS3'"
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            If Not RS1.EOF Then
               ShowHTML "        <td rowspan=1><font size=""1"">" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>" 
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(p_his3,"---") & "</td>"
            Else
               ShowHTML "        <td rowspan=1><font size=""1"">HISTÓRIA</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_his3,"---") & "</td>"
            End If
            DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
            RS1.Filter = " co_disciplina = 'FIL'"
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            If Not RS1.EOF Then
               ShowHTML "        <td rowspan=1><font size=""1"">" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>" 
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(p_fil,"---") & "</td>"
            Else
               ShowHTML "        <td rowspan=1><font size=""1"">FILOSOFIA</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_fil,"---") & "</td>"
            End If
            DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
            RS1.Filter = " co_disciplina = 'SOC'"
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            If Not RS1.EOF Then
               ShowHTML "        <td rowspan=1><font size=""1"">" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>" 
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(p_soc,"---") & "</td>"
            Else
               ShowHTML "        <td rowspan=1><font size=""1"">SOCIOLOGIA</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_soc,"---") & "</td>"
            End If
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            ShowHTML "        <td rowspan=2><font size=""1"">Parte Diversificada</td>"
            DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
            RS1.Filter = " co_disciplina = 'LEM3'"
            If Not RS1.EOF Then
               ShowHTML "        <td rowspan=1><font size=""1"">" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>" 
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(p_lem3,"---") & "</td>"
            Else
               ShowHTML "        <td rowspan=1><font size=""1"">LEM</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_lem3,"---") & "</td>"
            End If
            DB_GetRendRel RS1, Session("periodo"), p_unidade, p_1, p_2, p_3, p_4, p_bimestre
            RS1.Filter = " co_disciplina = 'ENR'"
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            If Not RS1.EOF Then
               ShowHTML "        <td rowspan=1><font size=""1"">" & Nvl(RS1("ds_disciplina"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("frequentes"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(RS1("qtd_abaixo"),"---") & "</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & FormatNumber(100 * cDbl(Nvl(RS1("qtd_abaixo"),0))/cDbl(Nvl(RS1("frequentes"),0)),1) & "%</td>"
               ShowHTML "        <td rowspan=1 align=""center""><font size=""1"">" & Nvl(p_enr3,"---") & "</td>"
            Else
               ShowHTML "        <td rowspan=1><font size=""1"">ENSINO RELIGIOSO</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>"
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">---</td>" 
               ShowHTML "        <td align=""center"" rowspan=1><font size=""1"">" & Nvl(p_enr3,"---") & "</td>"
            End If
         End If
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    If p_inf > "" Then
       ShowHTML "<tr><td colspan=6><font size=""1""><b>Informações Complementares: " & p_inf
    Else
       ShowHTML "<tr><td colspan=6><font size=""1""><b>Informações Complementares: ---"
    End If
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    If O = "L" Then
       ShowHTML "<tr><td align=""center"" colspan=6>"
       MontaBarra w_dir&w_pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&SG="&SG, RS1.PageCount, P3, P4, RS1.RecordCount
       ShowHTML "</tr>"
    End If
    DesConectaBD     
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Dir&w_Pagina&par, "POST", "return(Validacao(this));", "RelDuplic",P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Visualizar</i> para exibir a relação na tela ou sobre <i>Gerar Word</i> para gerar um arquivo no formato Word. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    SelecaoPeriodoLetivo "Perío<u>d</u>o letivo:", "D", null, Session("periodo"), null, "periodo", null
    SelecaoRegional "<u>R</u>egional:", "R", null, Session("regional"), null, "regional", "informal = 'N'", "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    If Session("regional") = "00" or IsNull(Tvl(Session("regional"))) Then
       SelecaoUnidadeEnsino "<u>U</u>nidade de ensino:", "U", null, p_unidade, null, "p_unidade", null, "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    Else
       SelecaoUnidadeEnsino "<u>U</u>nidade de ensino:", "U", null, p_unidade, null, "p_unidade", "co_sigre like '" & Session("regional") & "*'", "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    End IF
    SelecaoModEnsino "<u>M</u>odalidade de ensino:", "M", null, p_1, null, "p_1", "ds_tipo_curso = 'ENSINO FUNDAMENTAL' or ds_tipo_curso = 'ENSINO MÉDIO'", "onChange=""document.Form.target='';document.Form.O.value='P';document.Form.submit();"""
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    If p_1 > "" Then
       SelecaoSerie "<u>S</u>érie:", "S", null, p_2, null, "p_2", "co_tipo_curso = " & Nvl(p_1,0), "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    Else
       SelecaoSerie "<u>S</u>érie:", "S", null, p_2, null, "p_2", null, "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    End If
    w_filter = ""
    If p_1      > ""      Then w_filter = w_filter & " and co_tipo_curso = " & p_1 End If
    If p_2      > ""      Then w_filter = w_filter & " and sg_serie = '" & p_2 & "'"    End If
    If w_filter > ""      Then w_filter = mid(w_filter,6,200) Else w_filter = null End If
    SelecaoTurma "T<u>u</u>rma:", "U", null, p_3, Nvl(p_unidade,0), "p_3", w_filter, null
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    SelecaoTurno "<u>T</u>urno:", "T", null, p_4, null, "p_4", null, null
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>B</U>imestre:<br><SELECT ACCESSKEY=""B"" " & w_Disabled & " class=""STS"" name=""p_bimestre"" size=""1"">"
    ShowHTML "          <option value="""">---"
    If p_bimestre="1" Then
       ShowHTML "          <option value=""1"" SELECTED>1ºBimestre<option value=""2"">2ºBimestre<option value=""3"">3ºBimestre<option value=""4"">4ºBimestre<option value=""FINAL"">Média Final"
    ElseIf p_bimestre="2" Then
       ShowHTML "          <option value=""1"">1ºBimestre<option value=""2"" SELECTED>2ºBimestre<option value=""3"">3ºBimestre<option value=""4"">4ºBimestre<option value=""FINAL"">Média Final"
    ElseIf p_bimestre="3" Then
       ShowHTML "          <option value=""1"">1ºBimestre<option value=""2"">2ºBimestre<option value=""3"" SELECTED>3ºBimestre<option value=""4"">4ºBimestre<option value=""FINAL"">Média Final"
    ElseIf p_bimestre="4" Then
       ShowHTML "          <option value=""1"">1ºBimestre<option value=""2"">2ºBimestre<option value=""3"">3ºBimestre<option value=""4"" SELECTED>4ºBimestre<option value=""FINAL"">Média Final"
    ElseIf p_bimestre="FINAL" Then
       ShowHTML "          <option value=""1"">1ºBimestre<option value=""2"">2ºBimestre<option value=""3"">3ºBimestre<option value=""4"">4ºBimestre<option value=""FINAL"" SELECTED>Média Final"
    Else
       ShowHTML "          <option value=""1"">1ºBimestre<option value=""2"">2ºBimestre<option value=""3"">3ºBimestre<option value=""4"">4ºBimestre<option value=""FINAL"">Média Final"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><font size=""1""><b>Nº de Alunos <U>I</U>nfreqüentes:<br><INPUT ACCESSKEY=""I"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_infrequentes"" size=""6"" maxlength=""6"" value=""" & p_infrequentes & """></td>"
    ShowHTML "      <tr><td colspan=3><table border=0 cellpadding=0 cellspacing=0 width=""100%"">"
    If p_1 = "" Then
       p_display1 = "None"
       p_display2 = "None"
    ElseIf p_1 = 2 Then
       p_display1 = ""
       p_display2 = "None" 
    ElseIf p_1 = 3 Then
       p_display1 = "None"
       p_display2 = ""
    End If
    ShowHTML "      <tr id=""tr3"" name=""tr3"" style=""display:"&p_display1&";""><td><font size=""1""><b>Ensino Fundamental<td><font size=""1""><b>Causas/Observações"
    ShowHTML "      <tr id=""tr4"" name=""tr4"" style=""display:"&p_display1&";""><td><font size=""1"">Lingua Portuquesa: <td><INPUT " & w_Disabled & " class=""STI"" type=""text"" name=""p_por2"" size=""40""  value=""" & p_por2 & """></td>"
    ShowHTML "      <tr id=""tr5"" name=""tr5"" style=""display:"&p_display1&";""><td><font size=""1"">Arte: <td><INPUT " & w_Disabled & " class=""STI"" type=""text"" name=""p_eda2"" size=""40""  value=""" & p_eda2 & """></td>"
    ShowHTML "      <tr id=""tr6"" name=""tr6"" style=""display:"&p_display1&";""><td><font size=""1"">Educação Física: <td><INPUT " & w_Disabled & " class=""STI"" type=""text"" name=""p_edf2"" size=""40""  value=""" & p_edf2 & """></td>"
    ShowHTML "      <tr id=""tr7"" name=""tr7"" style=""display:"&p_display1&";""><td><font size=""1"">Matemática: <td><INPUT " & w_Disabled & " class=""STI"" type=""text"" name=""p_mat2"" size=""40""  value=""" & p_mat2 & """></td>"
    ShowHTML "      <tr id=""tr8"" name=""tr8"" style=""display:"&p_display1&";""><td><font size=""1"">Ciência Naturais: <td><INPUT " & w_Disabled & " class=""STI"" type=""text"" name=""p_cfb"" size=""40""  value=""" & p_cfb & """></td>"
    ShowHTML "      <tr id=""tr9"" name=""tr9"" style=""display:"&p_display1&";""><td><font size=""1"">Geografia <td><INPUT " & w_Disabled & " class=""STI"" type=""text"" name=""p_geo2"" size=""40""  value=""" & p_geo2 & """></td>"
    ShowHTML "      <tr id=""tr10"" name=""tr10"" style=""display:"&p_display1&";""><td><font size=""1"">História: <td><INPUT " & w_Disabled & " class=""STI"" type=""text"" name=""p_his2"" size=""40""  value=""" & p_his2 & """></td>"
    ShowHTML "      <tr id=""tr11"" name=""tr11"" style=""display:"&p_display1&";""><td><font size=""1"">LEM: <td><INPUT " & w_Disabled & " class=""STI"" type=""text"" name=""p_lem2"" size=""40""  value=""" & p_lem2 & """></td>"
    ShowHTML "      <tr id=""tr12"" name=""tr12"" style=""display:"&p_display1&";""><td><font size=""1"">Ensino Religioso: <td><INPUT " & w_Disabled & " class=""STI"" type=""text"" name=""p_enr2"" size=""40""  value=""" & p_enr2 & """></td>"
    ShowHTML "      <tr id=""tr14"" name=""tr14"" style=""display:"&p_display2&";""><td><font size=""1""><b>Ensino Médio<td><font size=""1""><b>Causas/Observações"
    ShowHTML "      <tr id=""tr15"" name=""tr15"" style=""display:"&p_display2&";""><td><font size=""1"">Lingua Portuquesa: <td><INPUT " & w_Disabled & " class=""STI"" type=""text"" name=""p_por3"" size=""40""  value=""" & p_por3 & """></td>"
    ShowHTML "      <tr id=""tr16"" name=""tr16"" style=""display:"&p_display2&";""><td><font size=""1"">Arte: <td><INPUT " & w_Disabled & " class=""STI"" type=""text"" name=""p_eda3"" size=""40""  value=""" & p_eda3 & """></td>"
    ShowHTML "      <tr id=""tr17"" name=""tr17"" style=""display:"&p_display2&";""><td><font size=""1"">Educação Física: <td><INPUT " & w_Disabled & " class=""STI"" type=""text"" name=""p_edf3"" size=""40""  value=""" & p_edf3 & """></td>"
    ShowHTML "      <tr id=""tr18"" name=""tr18"" style=""display:"&p_display2&";""><td><font size=""1"">Matemática: <td><INPUT " & w_Disabled & " class=""STI"" type=""text"" name=""p_mat3"" size=""40""  value=""" & p_mat3 & """></td>"
    ShowHTML "      <tr id=""tr19"" name=""tr19"" style=""display:"&p_display2&";""><td><font size=""1"">Física: <td><INPUT " & w_Disabled & " class=""STI"" type=""text"" name=""p_fis"" size=""40""  value=""" & p_fis & """></td>"
    ShowHTML "      <tr id=""tr20"" name=""tr20"" style=""display:"&p_display2&";""><td><font size=""1"">Química: <td><INPUT " & w_Disabled & " class=""STI"" type=""text"" name=""p_qui"" size=""40""  value=""" & p_qui & """></td>"
    ShowHTML "      <tr id=""tr21"" name=""tr21"" style=""display:"&p_display2&";""><td><font size=""1"">Biologia: <td><INPUT " & w_Disabled & " class=""STI"" type=""text"" name=""p_bio"" size=""40""  value=""" & p_bio & """></td>"
    ShowHTML "      <tr id=""tr22"" name=""tr22"" style=""display:"&p_display2&";""><td><font size=""1"">Geografia <td><INPUT " & w_Disabled & " class=""STI"" type=""text"" name=""p_geo3"" size=""40""  value=""" & p_geo3 & """></td>"
    ShowHTML "      <tr id=""tr23"" name=""tr23"" style=""display:"&p_display2&";""><td><font size=""1"">História: <td><INPUT " & w_Disabled & " class=""STI"" type=""text"" name=""p_his3"" size=""40""  value=""" & p_his3 & """></td>"
    ShowHTML "      <tr id=""tr24"" name=""tr24"" style=""display:"&p_display2&";""><td><font size=""1"">Filosofia: <td><INPUT " & w_Disabled & " class=""STI"" type=""text"" name=""p_fil"" size=""40""  value=""" & p_fil & """></td>"
    ShowHTML "      <tr id=""tr25"" name=""tr25"" style=""display:"&p_display2&";""><td><font size=""1"">Sociologia: <td><INPUT " & w_Disabled & " class=""STI"" type=""text"" name=""p_soc"" size=""40""  value=""" & p_soc & """></td>"
    ShowHTML "      <tr id=""tr26"" name=""tr26"" style=""display:"&p_display2&";""><td><font size=""1"">LEM: <td><INPUT " & w_Disabled & " class=""STI"" type=""text"" name=""p_lem3"" size=""40""  value=""" & p_lem3 & """></td>"
    ShowHTML "      <tr id=""tr27"" name=""tr27"" style=""display:"&p_display2&";""><td><font size=""1"">Ensino Religioso: <td><INPUT " & w_Disabled & " class=""STI"" type=""text"" name=""p_enr3"" size=""40""  value=""" & p_enr3 & """></td>"
    ShowHTML "      </table>"
    ShowHTML "      <tr><td><font size=""1""><b>Informações <U>C</U>omplementares:<br><TEXTAREA ACCESSKEY=""C"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_inf"" ROWS=4 COLS=70 >" & p_inf & "</TEXTAREA></td>"
    'ShowHTML "      <tr><td colspan=3><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    'ShowHTML "         <td><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td>"
    'ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td align=""center"" colspan=""6"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""6"">"
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
  Set p_unidade          = Nothing
  Set p_bimestre         = Nothing
  Set p_1                = Nothing
  Set p_2                = Nothing
  Set p_3                = Nothing
  Set p_4                = Nothing
  Set p_5                = Nothing
  Set p_infrequentes     = Nothing
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

