<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<%
Response.Expires = 0
REM =========================================================================
REM  /sicof.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia o módulo de envio importação de imagens de comprovantes de pagamento
REM Mail     : alex@sbpi.com.br
REM Criacao  : 18/05/2002 19:12
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
REM                   = V   : Envio
REM                   = L   : Listagem
REM                   = P   : Pesquisa
REM                   = D   : Detalhes
REM                   = N   : Nova solicitação de envio

' Verifica se o usuário está autenticado
If Session("LogOn") <> "Sim" Then
   EncerraSessao
End If

' Declaração de variáveis
Dim dbms, sp, RS, RS1, RS2, RS3, RS4, RS_menu, sql
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Reg, w_pagina, w_Disabled, w_TP, w_classe, w_submenu, w_filtro, w_copia
Dim w_Assinatura, w_SG
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu, w_dir, w_dir_volta
Dim w_sq_pessoa, w_ano
Dim ul,File
Set RS  = Server.CreateObject("ADODB.RecordSet")
Set RS1 = Server.CreateObject("ADODB.RecordSet")
Set RS2 = Server.CreateObject("ADODB.RecordSet")
Set RS3 = Server.CreateObject("ADODB.RecordSet")
Set RS4 = Server.CreateObject("ADODB.RecordSet")
Set RS_Menu = Server.CreateObject("ADODB.RecordSet")

Private Par

AbreSessao

' Carrega variáveis locais com os dados dos parâmetros recebidos
w_Dir        = "mod_sf/"
w_dir_volta  = "../"  

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
w_Pagina     = "sicof.asp?par="
w_Disabled   = "ENABLED"

' Configura o valor de O quando ele é nulo. Se for tela inicial de vinculação, chama filtragem
If O = "" and par = "INICIAL" Then 
   O="P" 
ElseIf O = "" Then 
   O = "L" 
End If

Select Case O
  Case "I" 
     w_TP = TP & " - Inclusão"
  Case "A" 
     w_TP = TP & " - Alteração"
  Case "E" 
     w_TP = TP & " - Exclusão"
  Case "V" 
     w_TP = TP & " - Envio"
  Case "P" 
     w_TP = TP & " - Filtragem"
  Case Else
     w_TP = TP & " - Listagem"
End Select

Main

FechaSessao

Set w_dir         = Nothing
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
Set w_pagina      = Nothing
Set w_Disabled    = Nothing
Set w_TP          = Nothing
Set w_Assinatura  = Nothing
Set w_dir         = Nothing
Set w_dir_volta   = Nothing
Set w_ano         = Nothing

REM =========================================================================
REM Rotina de consulta ao SICOF
REM -------------------------------------------------------------------------
Sub Consulta

  Dim w_sq_pessoa,Rsquery, w_total

  cabecalho

  w_sq_pessoa = Session("sq_pessoa")

  If O = "L" and Request("p_documento") = "" Then
     If Request("p_sq_pessoa") > "" Then SQL = "select cgccpf, nome from corporativo.gn_pessoas@sicof where handle = " & Request("p_sq_pessoa")   End If
     If Request("p_cnpj") > ""      Then SQL = "select cgccpf, nome from corporativo.gn_pessoas@sicof where cgccpf = '" & Request("p_cnpj") & "'" End If
     If Request("p_cpf") > ""       Then SQL = "select cgccpf, nome from corporativo.gn_pessoas@sicof where cgccpf = '" & Request("p_cpf") & "'"  End If
     ConectaBD SQL
  End If

  ShowHTML "<HEAD>"
  If InStr("P",O) Then
    ScriptOpen "JavaScript"
	Modulo
	FormataCNPJ
	FormataCPF
	FormataData
	CheckBranco
	ShowHTML "function procura() {"
	ShowHTML "  if (document.Form.p_beneficiario.value.length < 3) {"
	ShowHTML "    alert('Informe o nome a ser procurado com, pelo menos, três letras!');"
	ShowHTML "    document.Form.p_beneficiario.focus();"
	ShowHTML "    return false;"
	ShowHTML "  } else {"
	ShowHTML "    document.Form.O.value='P';"
	ShowHTML "    document.Form.target='content';"
	ShowHTML "    document.Form.submit();"
	ShowHTML "  }"
	ShowHTML "}"
	validateOpen "Validacao"
	If Session("Gestor_Sistema") = "S" or Session("Gestor_Seguranca") = "S" or P1 = 2 Then
       Validate "p_sq_pessoa", "Beneficiário", "SELECT", "", "1", "10", "", "1"
       Validate "p_cnpj", "CNPJ do beneficiário", "CNPJ", "", "18", "18", "", "1"
       Validate "p_cpf", "CPF do beneficiário", "CPF", "", "14", "14", "", "1"
       Validate "p_documento", "Nº do documento", "", "", "9", "15", "1", "1"
       Validate "p_inicio", "Data início", "DATA", "", "10", "10", "", "0123456789/"
       Validate "p_fim", "Data fim", "DATA", "", "10", "10", "", "0123456789/"
       ShowHTML "  if ((theForm.p_inicio.value != '' && theForm.p_fim.value == '') || (theForm.p_inicio.value == '' && theForm.p_fim.value != '')) { "
       ShowHTML "     alert('Informe o período completo ou nenhuma das datas!');"
       ShowHTML "     theForm.p_inicio.focus();"
       ShowHTML "     return false;"
       ShowHTML "  }"
       CompData "p_inicio", "Data início", "<=", "p_fim", "Data fim"
       Validate "p_comprovante", "Comprovante", "", "", "1", "10", "1", "1"
       Validate "p_inicio_nf", "Data início", "DATA", "", "10", "10", "", "0123456789/"
       Validate "p_fim_nf", "Data fim", "DATA", "", "10", "10", "", "0123456789/"
       ShowHTML "  if ((theForm.p_inicio_nf.value != '' && theForm.p_fim_nf.value == '') || (theForm.p_inicio_nf.value == '' && theForm.p_fim_nf.value != '')) { "
       ShowHTML "     alert('Informe o período completo ou nenhuma das datas!');"
       ShowHTML "     theForm.p_inicio_nf.focus();"
       ShowHTML "     return false;"
       ShowHTML "  }"
       ShowHTML "  if (theForm.p_inicio_nf.value != '' && theForm.p_comprovante.value == '') { "
       ShowHTML "     alert('Informe o comprovante a ser pesquisado no período selecionado!');"
       ShowHTML "     theForm.p_comprovante.focus();"
       ShowHTML "     return false;"
       ShowHTML "  }"
       CompData "p_inicio_nf", "Data início", "<=", "p_fim_nf", "Data fim"
       ShowHTML "  var w_string = theForm.p_sq_pessoa.selectedIndex + theForm.p_cnpj.value.length + theForm.p_cpf.value.length + theForm.p_documento.value.length + theForm.p_inicio.value.length + + theForm.p_fim.value.length + + theForm.p_comprovante.value.length + + theForm.p_inicio_nf.value.length + + theForm.p_fim_nf.value.length;"
       ShowHTML "  if (w_string == 0) {"
       ShowHTML "     alert('Você deve informar um dos parâmetros!');"
       ShowHTML "     eval('theForm.p_beneficiario.focus()');"
       ShowHTML "     return false;"
       ShowHTML "  }"
	Else
       Validate "p_documento", "Nº do documento", "", "1", "9", "15", "1", "1"
	End If
	ShowHTML "  theForm.target='docs';"
	ValidateClose
	ScriptClose
  End If

  ShowHTML "</HEAD>"  
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If O = "P" Then
     BodyOpenClean "onLoad='document.Form.p_beneficiario.focus()';"
  Else
     BodyOpenClean "onLoad='document.focus()';"
  End If

  If InStr("L",O) Then
     ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><TD ALIGN=""RIGHT""><B><FONT SIZE=5 COLOR=""#000000"">"
     ShowHTML "CONSULTA AO SICOF - UNESCO"
	 ShowHTML "</FONT><TR><TD ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">" & FormataDataEdicao(FormatDateTime(Date(),2)) & "</B></TD></TR>"
     ShowHTML "</FONT></B></TD></TR></TABLE>"
  Else
     ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  End If

  ShowHTML "<HR>"
  ShowHTML "<div align=""center""><center>"
  If Instr("L",O) > 0 Then
	 ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
	 
	 ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
	 ShowHTML "    <table width=""99%"" border=""0"">"
     ShowHTML "      <tr><td align=""left"" colspan=""2""><font size=""2"">Critério(s) de busca:<ul>"
     If Request("p_sq_pessoa") > "" or Request("p_cpf") > "" or Request("p_cnpj") > "" Then ShowHTML "<li>Beneficiário: <b>"  & RS("cgccpf") & " - " & RS("nome") & "</b>" End If
     If Request("p_ctcc") > "" Then
        SQL = "select nome from corporativo.ct_cc@sicof where handle = " & Request("p_ctcc")
        ConectaBD SQL
        ShowHTML "<li>Projeto: <b>"  & RS("nome") & "</b>"
        DesconectaBD
     End If
     If Request("p_documento") > "" Then ShowHTML "<li>Documento: <b>"  & Request("p_documento") & "</b>" End If
     If Request("p_inicio") > "" Then
        ShowHTML "<li>Documentos com vigência (SA), vencimento (SP) ou missão (SPD) entre <b>" & Request("p_inicio") & "</b> e <b>" & Request("p_fim") & "</b>"
     End If
     If Request("p_comprovante") > "" Then
        ShowHTML "<li>Comprovantes com contém: <b>"  & Request("p_comprovante") & "</b>"
        If Request("p_inicio_nf") > "" Then
           ShowHTML " com data entre <b>" & Request("p_inicio_nf") & "</b> e <b>" & Request("p_fim_nf") & "</b>"
        End If
     End If
     ShowHTML "</ul>"
     ShowHTML "      <tr><td align=""center"" colspan=""2""><font size=""2"">Clique <a accesskey=""F"" class=""SS"" href=""#"" onClick=""window.close(); opener.focus();"">aqui</a> para fechar esta janela.</font>"

     If Request("p_sq_pessoa") > "" or Request("p_cpf") > "" or Request("p_cnpj") > "" or (Request("p_documento") = "" and Request("p_inicio") > "") or (Request("p_documento") > "" and Mid(uCase(Request("p_documento")),1,3) = "SA-") Then
        'CONTRATOS
        SQL = ""
        SQL = "select a.automatico_sa Documento, to_char(c.duracaoinicio,'dd/mm/yyyy') inicio, c.duracaoinicio, " & VbCrLf & _
              "       to_char(c.duracaofim,'dd/mm/yyyy') fim, " & VbCrLf & _
              "       d.codigounesco projeto,  " & VbCrLf & _
              "       decode(c.tipodepagamento,1,'Permanente',2,'Consultor',3,'Produto',4,'Financiamento de atividades')||' ('|| " & VbCrLf & _
              "       decode(a.alteracao,1,'Contrato',2,'Emenda')||')' Modalidade, " & VbCrLf & _
              "       seguranca.fcfaseatual@sicof(a.automatico_sa) fase_atual, e.nome, c.totcontratacao " & VbCrLf & _
              "  from corporativo.un_solicitacaoadministrativa@sicof a, " & VbCrLf & _
              "       corporativo.un_sol_adm_certifica@sicof         b, " & VbCrLf & _
              "       corporativo.ct_cc@sicof                        d, " & VbCrLf & _
              "       corporativo.un_termoreferenciapf@sicof         c, " & VbCrLf & _
              "       corporativo.gn_pessoas@sicof                   e " & VbCrLf & _
              " where a.handle     = b.numsolicitacao " & VbCrLf & _
              "   and b.acordo     = d.handle " & VbCrLf & _
              "   and a.handle     = c.numerosolicitacao " & VbCrLf & _
              "   and a.contratado = e.handle " & VbCrLf
        If Request("p_sq_pessoa") > ""    Then SQL = SQL & "  and e.handle = " & Request("p_sq_pessoa")                      & VbCrLf End If
        If Request("p_ctcc") > ""         Then SQL = SQL & "  and b.acordo = " & Request("p_ctcc")                           & VbCrLf End If
        If Request("p_cnpj") > ""         Then SQL = SQL & "  and e.cgccpf = '" & Request("p_cnpj") & "'"                    & VbCrLf End If
        If Request("p_cpf") > ""          Then SQL = SQL & "  and e.cgccpf = '" & Request("p_cpf") & "'"                     & VbCrLf End If
        If Request("p_documento") > ""    Then SQL = SQL & "  and a.automatico_sa = '" & uCase(Request("p_documento")) & "'" & VbCrLf End If
        If Request("p_inicio") > ""       Then 
           SQL = SQL & _
                 "  and (c.duracaoinicio between to_date('" & Request("p_inicio") & "', 'dd/mm/yyyy') and to_date('" & Request("p_fim") & "', 'dd/mm/yyyy') or " & VbCrLf & _
                 "       c.duracaofim    between to_date('" & Request("p_inicio") & "', 'dd/mm/yyyy') and to_date('" & Request("p_fim") & "', 'dd/mm/yyyy') or " & VbCrLf & _
                 "       to_date('" & Request("p_inicio") & "', 'dd/mm/yyyy') between c.duracaoinicio and c.duracaofim or " & VbCrLf & _
                 "       to_date('" & Request("p_fim") & "', 'dd/mm/yyyy')    between c.duracaoinicio and c.duracaofim " & VbCrLf & _
                 "      ) " & VbCrLf
        End If
        SQL = SQL & _
              "UNION " & VbCrLf & _
              "select a.automatico_sa Documento, to_char(c.duracaoinicio,'dd/mm/yyyy') inicio, c.duracaoinicio, " & VbCrLf & _
              "       to_char(c.duracaofim,'dd/mm/yyyy') fim, " & VbCrLf & _
              "       d.codigounesco projeto,  " & VbCrLf & _
              "       decode(c.tipodepagamento,1,'Serviços',2,'Aquis.Mat/Bens',3,'Pub/Serv.Gráf.',4,'Promoção Eventos','Financiamento de atividades')||' ('|| " & VbCrLf & _
              "       decode(a.alteracao,1,'Contrato',2,'Emenda')||')' Modalidade, " & VbCrLf & _
              "       seguranca.fcfaseatual@sicof(a.automatico_sa) fase_atual, e.nome, c.totcontratacao " & VbCrLf & _
              "  from corporativo.un_solicitacaoadministrativa@sicof a, " & VbCrLf & _
              "       corporativo.un_sol_adm_certifica@sicof         b, " & VbCrLf & _
              "       corporativo.ct_cc@sicof                        d, " & VbCrLf & _
              "       corporativo.un_termoreferenciapj@sicof         c, " & VbCrLf & _
              "       corporativo.gn_pessoas@sicof                   e " & VbCrLf & _
              " where a.handle     = b.numsolicitacao " & VbCrLf & _
              "   and b.acordo     = d.handle " & VbCrLf & _
              "   and a.handle     = c.solicitacao " & VbCrLf & _
              "   and a.contratado = e.handle " & VbCrLf
        If Request("p_sq_pessoa") > ""    Then SQL = SQL & "  and e.handle = " & Request("p_sq_pessoa")                      & VbCrLf End If
        If Request("p_ctcc") > ""         Then SQL = SQL & "  and b.acordo = " & Request("p_ctcc")                           & VbCrLf End If
        If Request("p_cnpj") > ""         Then SQL = SQL & "  and e.cgccpf = '" & Request("p_cnpj") & "'"                    & VbCrLf End If
        If Request("p_cpf") > ""          Then SQL = SQL & "  and e.cgccpf = '" & Request("p_cpf") & "'"                     & VbCrLf End If
        If Request("p_documento") > ""    Then SQL = SQL & "  and a.automatico_sa = '" & uCase(Request("p_documento")) & "'" & VbCrLf End If
        If Request("p_inicio") > ""       Then 
           SQL = SQL & _
                 "  and (c.duracaoinicio between to_date('" & Request("p_inicio") & "', 'dd/mm/yyyy') and to_date('" & Request("p_fim") & "', 'dd/mm/yyyy') or " & VbCrLf & _
                 "       c.duracaofim    between to_date('" & Request("p_inicio") & "', 'dd/mm/yyyy') and to_date('" & Request("p_fim") & "', 'dd/mm/yyyy') or " & VbCrLf & _
                 "       to_date('" & Request("p_inicio") & "', 'dd/mm/yyyy') between c.duracaoinicio and c.duracaofim or " & VbCrLf & _
                 "       to_date('" & Request("p_fim") & "', 'dd/mm/yyyy')    between c.duracaoinicio and c.duracaofim " & VbCrLf & _
                 "      ) " & VbCrLf
        End If
        SQL = SQL & " order by duracaoinicio desc" & VbCrLf
        ConectaBD SQL
        ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
        ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
	    ShowHTML "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font  size=""2""><b>Contratos</td>"
        ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
        ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
        ShowHTML "      <tr><td align=""right"" colspan=""2""><font size=""1""><b>Registros: " & RS.RecordCount
        ShowHTML "      <tr><td align=""center"" colspan=""2"">"
        ShowHTML "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        ShowHTML "            <td rowspan=2><font size=""1""><b>Documento</font></td>"
        ShowHTML "            <td rowspan=2><font size=""1""><b>Beneficiário</font></td>"
        ShowHTML "            <td colspan=2><font size=""1""><b>Vigência</font></td>"
        ShowHTML "            <td rowspan=2><font size=""1""><b>Acordo</font></td>"
        ShowHTML "            <td rowspan=2><font size=""1""><b>Valor</font></td>"
        ShowHTML "            <td rowspan=2><font size=""1""><b>Modalidade</font></td>"
        ShowHTML "            <td rowspan=2><font size=""1""><b>Fase atual</font></td>"
        ShowHTML "          </tr>"    
        ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        ShowHTML "            <td><font size=""2""><b>Início</font></td>"
        ShowHTML "            <td><font size=""2""><b>Término</font></td>"
        ShowHTML "          </tr>"    
        If Rs.EOF Then
           ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font  size=""2""><b>Nenhum registro encontrado.</b></td></tr>"
        Else
		    w_total = 0
		    While Not Rs.EOF
	         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
             ShowHTML "        <td nowrap><font size=""1""><a class=""HL"" href=""https://honda.unesco.org.br/pls/seguranca/Frm_SA.Visualizar?p_usuario=167&p_Documento=111800&p_Acesso=C&p_Nro_Doc=" & RS("Documento") & "&P1=0&P2=0&P3=0&TP=Consultar&p_ValidaTempo=Nao"">" & RS("documento") & "</a>"
	         ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
	         ShowHTML "        <td align=""center""><font size=""1"">" & RS("inicio") & "</td>"
	         ShowHTML "        <td align=""center""><font size=""1"">" & RS("fim") & "</td>"
	         ShowHTML "        <td align=""center""><font size=""1"">" & RS("projeto") & "</td>"
	         ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("totcontratacao"),2) & "</td>"
	         ShowHTML "        <td><font size=""1"">" & RS("modalidade") & "</td>"
	         ShowHTML "        <td><font size=""1"">" & RS("fase_atual") & "</td>"
	         ShowHTML "      </tr>"
	         w_total = w_total + cDbl(RS("totcontratacao"))
	         Rs.MoveNext
		    wend
	    End If
	    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
        ShowHTML "        <td colspan=5 align=""right""><font size=""1""><b>Total</b></font></td>"
	    ShowHTML "        <td align=""right""><font size=""1""><b>" & FormatNumber(w_total,2) & "</b></font></td>"
	    ShowHTML "        <td colspan=2><font size=""1"">&nbsp;</td>"
	    ShowHTML "      </tr>"
        ShowHTML "         </table></td></tr>"
        ShowHTML "      <tr><td align=""right"" colspan=""2""><font size=""1"">&nbsp;"
        Rs.close
     End If

     If (Request("p_comprovante") > "") or Request("p_sq_pessoa") > "" or Request("p_cpf") > "" or Request("p_cnpj") > "" or (Request("p_documento") = "" and Request("p_inicio") > "") or (Request("p_documento") > "" and Mid(uCase(Request("p_documento")),1,3) = "SP-") Then
        'PAGAMENTOS
        SQL = ""
        SQL = "select a.handle, a.automatico_sp documento, Decode(c.handle,null,a.proposito_pgto,c.ds_portugues) historico, " & VbCrLf & _
              "       Nvl(to_char(a.dt_vcto,'dd/mm/yyyy'),'-') inicio,  " & VbCrLf & _
              "       d.codigounesco projeto, " & VbCrLf & _
              "       (Nvl(a.valornominal,0) - Nvl(a.abatimento,0)) Valor, " & VbCrLf & _
              "       seguranca.fcfaseatual@sicof(a.automatico_sp) fase_atual, b.nome " & VbCrLf & _
              "from corporativo.Un_Sol_Pgto@sicof a, " & VbCrLf & _
              "    corporativo.Gn_Pessoas@sicof b, " & VbCrLf & _
              "    corporativo.Un_HistoricoPadrao@sicof c, " & VbCrLf & _
              "    corporativo.ct_cc@sicof d " & VbCrLf & _
              "where a.Favorecido     = b.Handle " & VbCrLf & _
              "  and a.historicopadrao= c.handle (+) " & VbCrLf & _
              "  and a.acordo         = d.handle " & VbCrLf 
        If Request("p_sq_pessoa") > ""    Then SQL = SQL & "  and b.handle = " & Request("p_sq_pessoa")                      & VbCrLf End If
        If Request("p_ctcc") > ""         Then SQL = SQL & "  and a.acordo = " & Request("p_ctcc")                           & VbCrLf End If
        If Request("p_cnpj") > ""         Then SQL = SQL & "  and b.cgccpf = '" & Request("p_cnpj") & "'"                    & VbCrLf End If
        If Request("p_cpf") > ""          Then SQL = SQL & "  and b.cgccpf = '" & Request("p_cpf") & "'"                     & VbCrLf End If
        If Request("p_documento") > ""    Then SQL = SQL & "  and a.automatico_sp = '" & ucase(Request("p_documento") & "'") & VbCrLf End If
        If Request("p_inicio") > ""       Then SQL = SQL & "  and a.dt_vcto between to_date('" & Request("p_inicio") & "', 'dd/mm/yyyy') and to_date('" & Request("p_fim") & "', 'dd/mm/yyyy') " & VbCrLf End If
        If Request("p_comprovante") > ""       Then 
           SQL = SQL & _
                 "  and a.handle in " & VbCrLf & _
                 "      (select a.automatico_sp " & VbCrLf & _
                 "         from corporativo.un_sol_pgto_doc_anexos@sicof a " & VbCrLf & _
                 "        where a.numerodoc like '%" & Request("p_comprovante") & "%' " & VbCrLf
           If Request("p_inicio_nf") > "" Then SQL = SQL & "          and a.data between to_date('" & Request("p_inicio_nf") & "', 'dd/mm/yyyy') and to_date('" & Request("p_fim_nf") & "', 'dd/mm/yyyy') " & VbCrLf End If
           SQL = SQL & "      ) " & VbCrLf
        End If
        SQL = SQL & " order by a.dt_vcto desc " & VbCrLf 
        ConectaBD SQL
        ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
        ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
	    ShowHTML "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font  size=""2""><b>Pagamentos</td>"
        ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
        ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
        ShowHTML "      <tr><td align=""right"" colspan=""2""><font size=""1""><b>Registros: " & RS.RecordCount
        ShowHTML "      <tr><td align=""center"" colspan=""2"">"
        ShowHTML "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        ShowHTML "            <td><font size=""1""><b>Documento</font></td>"
        ShowHTML "            <td><font size=""1""><b>Comprovante</font></td>"
        ShowHTML "            <td><font size=""1""><b>Beneficiário</font></td>"
        ShowHTML "            <td><font size=""1""><b>Vencimento</font></td>"
        ShowHTML "            <td><font size=""1""><b>Acordo</font></td>"
        ShowHTML "            <td><font size=""1""><b>Valor</font></td>"
        If Request("p_documento") = "" Then
           ShowHTML "            <td><font size=""1""><b>Histórico</font></td>"
        End If
        ShowHTML "            <td><font size=""1""><b>Fase atual</font></td>"
        ShowHTML "          </tr>"    
        If Rs.EOF Then
           ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font  size=""2""><b>Nenhum registro encontrado.</b></td></tr>"
        Else
		    w_total = 0
		    While Not Rs.EOF
	         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
             ShowHTML "        <td nowrap><font size=""1""><a class=""HL"" href=""https://honda.unesco.org.br/pls/seguranca/Frm_SP.Visualizar?p_usuario=167&p_Documento=111800&p_Acesso=C&p_Nro_Doc=" & RS("Documento") & "&P1=0&P2=0&P3=0&TP=Consultar&p_ValidaTempo=Nao"">" & RS("documento") & "</a>"
	         ShowHTML "        <td nowrap><font size=""1"">"
	         SQL = "select numerodoc from corporativo.un_sol_pgto_doc_anexos@sicof a where a.automatico_sp = " & RS("handle") & " order by a.numerodoc "
             RS1.Open SQL, dbms
	         If RS1.EOF Then
	            Response.Write "---"
	         Else
	            While Not RS1.EOF
	               Response.Write RS1("numerodoc") & "&nbsp;<br>"
	               RS1.MoveNext
	            Wend
	         End If
	         RS1.Close
	         ShowHTML "            </td>"
	         ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
	         ShowHTML "        <td align=""center"" nowrap><font size=""1"">" & RS("inicio") & "</td>"
	         ShowHTML "        <td nowrap><font size=""1"">" & RS("projeto") & "</td>"
	         ShowHTML "        <td align=""right"" nowrap><font size=""1"">" & FormatNumber(RS("valor"),2) & "</td>"
             If Request("p_documento") = "" Then
	            ShowHTML "        <td><font size=""1"">" & RS("historico") & "</td>"
	         End If
	         ShowHTML "        <td><font size=""1"">" & RS("fase_atual") & "</td>"
	         ShowHTML "      </tr>"
	         w_total = w_total + cDbl(RS("valor"))
	         Rs.MoveNext
		    wend
	        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
            ShowHTML "        <td colspan=5 align=""right""><font size=""1""><b>Total</b></font></td>"
	        ShowHTML "        <td align=""right""><font size=""1""><b>" & FormatNumber(w_total,2) & "</b></font></td>"
	        ShowHTML "        <td colspan=2><font size=""1"">&nbsp;</td>"
	        ShowHTML "      </tr>"
	    End If
        Rs.close
        ShowHTML "         </table></td></tr>"     
        ShowHTML "      <tr><td align=""right"" colspan=""2""><font size=""1"">&nbsp;"
     End If

     If Request("p_sq_pessoa") > "" or Request("p_cpf") > "" or Request("p_cnpj") > "" or (Request("p_documento") = "" and Request("p_inicio") > "") or (Request("p_documento") > "" and Mid(uCase(Request("p_documento")),1,3) = "SPD") Then
        'VIAGENS A SERVIÇO
        SQL = ""
        SQL = "select a.handle, a.automatico_spd documento, a.finalidade historico, " & VbCrLf & _
              "       nvl(to_char(a.dt_inicio,'dd/mm/yyyy'),'-') inicio, " & VbCrLf & _ 
              "       nvl(to_char(a.dt_fim,'dd/mm/yyyy'),'-') fim,  " & VbCrLf & _
              "       d.codigounesco projeto, " & VbCrLf & _
              "       seguranca.fValor@sicof(a.valortotal) Valor, " & VbCrLf & _
              "       seguranca.fcfaseatual@sicof(a.automatico_spd) fase_atual, b.nome " & VbCrLf & _
              "from corporativo.Un_SolicitacaoPD@sicof a, " & VbCrLf & _
              "    corporativo.Gn_Pessoas@sicof b, " & VbCrLf & _
              "    corporativo.ct_cc@sicof d " & VbCrLf & _
              "where a.contratado     = b.Handle " & VbCrLf & _
              "  and a.acordo         = d.handle " & VbCrLf 
        If Request("p_sq_pessoa") > ""    Then SQL = SQL & "  and b.handle = " & Request("p_sq_pessoa")                       & VbCrLf End If
        If Request("p_ctcc") > ""         Then SQL = SQL & "  and a.acordo = " & Request("p_ctcc")                            & VbCrLf End If
        If Request("p_cnpj") > ""         Then SQL = SQL & "  and b.cgccpf = '" & Request("p_cnpj") & "'"                     & VbCrLf End If
        If Request("p_cpf") > ""          Then SQL = SQL & "  and b.cgccpf = '" & Request("p_cpf") & "'"                      & VbCrLf End If
        If Request("p_documento") > ""    Then SQL = SQL & "  and a.automatico_spd = '" & uCase(Request("p_documento")) & "'" & VbCrLf End If
        If Request("p_inicio") > ""       Then 
           SQL = SQL & _
                 "  and (a.dt_inicio  between to_date('" & Request("p_inicio") & "', 'dd/mm/yyyy') and to_date('" & Request("p_fim") & "', 'dd/mm/yyyy') or " & VbCrLf & _
                 "       a.dt_fim     between to_date('" & Request("p_inicio") & "', 'dd/mm/yyyy') and to_date('" & Request("p_fim") & "', 'dd/mm/yyyy') " & VbCrLf & _
                 "      ) " & VbCrLf
        End If
        SQL = SQL & " order by a.dt_inicio desc" & VbCrLf 
        ConectaBD SQL
        ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
        ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
	    ShowHTML "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font  size=""2""><b>Passagens e Diárias</td>"
        ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
        ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
        ShowHTML "      <tr><td align=""right"" colspan=""2""><font size=""1""><b>Registros: " & RS.RecordCount
        ShowHTML "      <tr><td align=""center"" colspan=""2"">"
        ShowHTML "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        ShowHTML "            <td rowspan=2><font size=""1""><b>Documento</font></td>"
        ShowHTML "            <td rowspan=2><font size=""1""><b>Beneficiário</font></td>"
        ShowHTML "            <td colspan=2><font size=""1""><b>Missão</font></td>"
        ShowHTML "            <td rowspan=2><font size=""1""><b>Acordo</font></td>"
        If Request("p_documento") = "" Then
           ShowHTML "            <td rowspan=2><font size=""1""><b>Histórico</font></td>"
        End If
        ShowHTML "            <td rowspan=2><font size=""1""><b>Fase atual</font></td>"
        ShowHTML "          </tr>"    
        ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        ShowHTML "            <td><font size=""2""><b>Início</font></td>"
        ShowHTML "            <td><font size=""2""><b>Término</font></td>"
        ShowHTML "          </tr>"    
        If Rs.EOF Then
           ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font  size=""2""><b>Nenhum registro encontrado..</b></td></tr>"
        Else
		    While Not Rs.EOF
	         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
             ShowHTML "        <td nowrap><font size=""1""><a class=""HL"" href=""https://honda.unesco.org.br/pls/seguranca/Frm_SPD.Visualizar?p_usuario=167&p_Documento=111800&p_Acesso=C&p_Nro_Doc=" & RS("Documento") & "&P1=0&P2=0&P3=0&TP=Consultar&p_ValidaTempo=Nao"">" & RS("documento") & "</a>"
	         ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
	         ShowHTML "        <td align=""center"" nowrap><font size=""1"">" & RS("inicio") & "</td>"
	         ShowHTML "        <td align=""center"" nowrap><font size=""1"">" & RS("fim") & "</td>"
	         ShowHTML "        <td><font size=""1"">" & RS("projeto") & "</td>"
             If Request("p_documento") = "" Then
	            ShowHTML "        <td><font size=""1"">" & RS("historico") & "</td>"
	         End If
	         ShowHTML "        <td><font size=""1"">" & RS("fase_atual") & "</td>"
	         ShowHTML "      </tr>"
	         Rs.MoveNext
		    wend
	    End If
        Rs.close
        ShowHTML "         </table></td></tr>"     
     End If
     
     ShowHTML "      <tr><td align=""center"" colspan=""2""><font size=""2"">Clique <a accesskey=""F"" class=""SS"" href=""#"" onClick=""window.close(); opener.focus();"">aqui</a> para fechar esta janela.</font>"
     ShowHTML "     </tr></tr></td></table>"

     ShowHTML "</table>"
     ShowHTML "</center>"
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""left""><font size=2>Instruções:<ul>"
    ShowHTML "  <li>Informe um dos critérios apresentados abaixo e clique sobre o botão <i>Aplicar filtro</i>."
    ShowHTML "  <li>A procura pelo nome do beneficiário é feita em duas partes. Primeiro, informe parte dele em <i>Procurar nome</i> e clique sobre o botão <i>Procura</i>. Em seguida, selecione o nome desejado na lista disponível em <i>Beneficiário</i> e clique no botão <i>Aplicar Filtro</i>;"
    ShowHTML "  <li>Você pode informar quantos critérios desejar."
    ShowHTML "  <li>O resultado será apresentado em outra janela."
    ShowHTML "  </ul></div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr valign=""top""><td valign=""top""><font  size=""1"">"
    ShowHTML "            <b>Pr<U>o</U>curar nome:<br> <INPUT TYPE=""TEXT"" ACCESSKEY=""O"" class=""BTM"" name=""p_beneficiario"" size=40 maxlength=40>"
    ShowHTML "            <input class=""BTM"" type=""button"" name=""Procura"" value=""Procura"" onClick=""procura()"">"
    If Request("p_beneficiario") > "" Then 
       SQL = "select b.handle, b.nome from corporativo.gn_pessoas@sicof b where upper(b.nome) like '%" & uCase(Replace(Request("p_beneficiario"),"'","''")) & "%' order by seguranca.acentos@sicof(nome)"
    Else
       SQL = "select * from corporativo.gn_pessoas@sicof where handle < 0"
    End If
    ConectaBD SQL
    ShowHTML "      <tr valign=""top""><td valign=""top""><font  size=""1""><b><U>B</U>eneficiário:<br> <SELECT ACCESSKEY=""B"" class=""BTM"" name=""p_sq_pessoa"" size=""1"">"
    ShowHTML "          <OPTION VALUE="""">---"
    If Not RS.EOF Then
       While Not RS.EOF
         If cDbl(RS("handle")) = cDbl(Nvl(Request("p_sq_pessoa"),0)) Then
            ShowHTML "          <OPTION VALUE=" & RS("handle") & " selected>" & RS("nome")
         Else
            ShowHTML "          <OPTION VALUE=" & RS("handle") & ">" & RS("nome")
         End If
         RS.MoveNext
       Wend
    End If
    DesconectaBD
    ShowHTML "          </SELECT></td>"
    ShowHTML "      </tr>"    

    SQL = "select a.HANDLE, a.NOME, a.CODIGOUNESCO, a.INICIO, a.TERMINO from CORPORATIVO.CT_CC@sicof a where a.ultimonivel='S' order by a.nome"
    ConectaBD SQL
    ShowHTML "      <tr valign=""top""><td valign=""top""><font  size=""1""><b>Pro<U>j</U>eto:<br> <SELECT ACCESSKEY=""J"" class=""BTM"" name=""p_ctcc"" size=""1"">"
    ShowHTML "          <OPTION VALUE="""">---"
    While Not RS.EOF
      If cDbl(RS("handle")) = cDbl(Nvl(Request("p_ctcc"),0)) Then
         ShowHTML "          <OPTION VALUE=" & RS("handle") & " selected>" & RS("nome")
      Else
         ShowHTML "          <OPTION VALUE=" & RS("handle") & ">" & RS("nome")
      End If
      RS.MoveNext
    Wend
    DesconectaBD
    ShowHTML "          </SELECT></td>"
    ShowHTML "      </tr>"    
    ShowHTML "      <tr valign=""top""><td valign=""top""><table border=0 width=""100%"" cellpadding=0 cellspacing=0><tr valign=""top"">"
    ShowHTML "          <td><font  size=""1""><b><U>C</U>NPJ:<br> <INPUT TYPE=""TEXT"" ACCESSKEY=""C"" class=""BTM"" name=""p_cnpj"" size=18 maxlength=18 onKeyPress=""FormataCNPJ(this,event);""  value=""" & Request("p_cnpj") & """></td>"
    ShowHTML "          <td><font  size=""1""><b>C<U>P</U>F:<br> <INPUT TYPE=""TEXT"" ACCESSKEY=""C"" class=""BTM"" name=""p_cpf"" size=14 maxlength=14 onKeyPress=""FormataCPF(this,event);"" value=""" & Request("p_cpf") & """></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr valign=""top""><td valign=""top""><table border=0 width=""100%"" cellpadding=0 cellspacing=0><tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font  size=""1""><b>SA/SP/SP<U>D</U>:</b> (identificação completa)<br> <INPUT TYPE=""TEXT"" ACCESSKEY=""D"" class=""BTM"" name=""p_documento"" size=15 maxlength=15 value=""" & Request("p_documento") & """></td>"
    ShowHTML "          <td><font  size=""1"">Período: <b>D<U>e</U>: <INPUT TYPE=""TEXT"" ACCESSKEY=""E"" class=""BTM"" name=""p_inicio"" size=10 maxlength=10 onKeyPress=""FormataData(this,event);""  value=""" & Request("p_inicio") & """>"
    ShowHTML "                                <U>a</U>té: <INPUT TYPE=""TEXT"" ACCESSKEY=""A"" class=""BTM"" name=""p_fim"" size=10 maxlength=10 onKeyPress=""FormataData(this,event);"" value=""" & Request("p_fim") & """></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr valign=""top""><td valign=""top""><table border=0 width=""100%"" cellpadding=0 cellspacing=0><tr valign=""top"">"
    ShowHTML "          <td><font  size=""1""><b>Co<U>m</U>provante (NF/Fatura/Recibo):<br><INPUT TYPE=""TEXT"" ACCESSKEY=""M"" class=""BTM"" name=""p_comprovante"" size=10 maxlength=10 value=""" & Request("p_comprovante") & """>"
    ShowHTML "          <td><font  size=""1"">Período: <b>D<U>e</U>: <INPUT TYPE=""TEXT"" ACCESSKEY=""E"" class=""BTM"" name=""p_inicio_nf"" size=10 maxlength=10 onKeyPress=""FormataData(this,event);""  value=""" & Request("p_inicio_nf") & """>"
    ShowHTML "                                <b><U>a</U>té: <INPUT TYPE=""TEXT"" ACCESSKEY=""A"" class=""BTM"" name=""p_fim_nf"" size=10 maxlength=10 onKeyPress=""FormataData(this,event);"" value=""" & Request("p_fim_nf") & """></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""BTM"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
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

  Rodape

  Set w_sq_pessoa           = Nothing
  Set Rsquery               = Nothing

End Sub

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main

  Select Case Par
    Case "CONSULTA" Consulta
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
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>

