<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Alberguista.asp" -->
<!-- #INCLUDE FILE="DML_Alberguista.asp" -->
<!-- #INCLUDE FILE="VisualAlberguista.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DML_Seguranca.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Alberguista.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Contém rotinas relativas ao cadastro de alberguistas
REM Mail     : alex@sbpi.com.br
REM Criacao  : 03/03/2004, 08:30
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
REM                   = H   : Herança
REM                   = T   : Ativar
REM                   = E   : Exclusão
REM                   = L   : Listagem
REM                   = P   : Pesquisa
REM                   = D   : Desativar
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
Private Par

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
w_Pagina     = "Alberguista.asp?par="
w_Dir        = "fbaj/"
w_Disabled   = "ENABLED"

If P1 = "" Then P1 = 0           Else P1 = cDbl(P1) End if
If P3 = "" Then P3 = 1           Else P3 = cDbl(P3) End If
If P4 = "" Then P4 = conPageSize Else P4 = cDbl(P4) End If

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
  
VerificaParametros

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
REM Rotina de cadastro
REM -------------------------------------------------------------------------
Sub Cadastro

  Dim w_chave, w_carteira, w_nome, w_nascimento, w_endereco, w_bairro, w_cep, w_cidade
  Dim w_uf, w_ddd, w_fone, w_cpf, w_rg_numero, w_rg_emissor, w_email, w_sexo, w_formacao
  Dim w_trabalha, w_email_trabalho, w_conhece_albergue, w_visitas, w_classificacao, w_destino
  Dim w_destino_outros, w_motivo_viagem, w_motivo_outros, w_forma_conhece, w_forma_outros
  Dim w_sq_cidade, w_carteira_emissao, w_carteira_validade
  
  Dim w_troca, i, w_erro, w_como_funciona, w_cor

  Dim p_carteira, p_nome, p_ordena, p_sexo, p_uf, p_conhece_albergue, p_visitas, p_classificacao
  Dim p_destino, p_motivo_viagem, p_forma_conhece

  If O = "" Then O = "P" End If

  w_erro            = ""
  w_troca           = Request("w_troca")
  w_chave           = Request("w_chave")
  p_ordena          = uCase(Request("p_ordena"))
  p_nome            = uCase(Request("p_nome"))
  p_carteira        = uCase(Request("p_carteira"))
  p_sexo            = uCase(Request("p_sexo"))
  p_uf              = uCase(Request("p_uf"))
  p_conhece_albergue= uCase(Request("p_conhece_albergue"))
  p_visitas         = uCase(Request("p_visitas"))
  p_classificacao   = uCase(Request("p_classificacao"))
  p_destino         = uCase(Request("p_destino"))
  p_motivo_viagem   = uCase(Request("p_motivo_viagem"))
  p_forma_conhece   = uCase(Request("p_forma_conhece"))
  
  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página
     w_chave            = Request("w_chave")
     w_carteira         = Request("w_carteira")
     w_nome             = Request("w_nome")
     w_nascimento       = Request("w_nascimento")
     w_endereco         = Request("w_endereco")
     w_bairro           = Request("w_bairro")
     w_cep              = Request("w_cep")
     w_cidade           = Request("w_cidade")
     w_uf               = Request("w_uf")
     w_ddd              = Request("w_ddd")
     w_fone             = Request("w_fone")
     w_cpf              = Request("w_cpf")
     w_rg_numero        = Request("w_rg_numero")
     w_rg_emissor       = Request("w_rg_emissor")
     w_email            = Request("w_email")
     w_sexo             = Request("w_sexo")
     w_formacao         = Request("w_formacao")
     w_trabalha         = Request("w_trabalha")
     w_email_trabalho   = Request("w_email_trabalho")
     w_conhece_albergue = Request("w_conhece_albergue")
     w_visitas          = Request("w_visitas")
     w_classificacao    = Request("w_classificacao")
     w_destino          = Request("w_destino")
     w_destino_outros   = Request("w_destino_outros")
     w_motivo_viagem    = Request("w_motivo_viagem")
     w_motivo_outros    = Request("w_motivo_outros")
     w_forma_conhece    = Request("w_forma_conhece")
     w_forma_outros     = Request("w_forma_outros")
     w_sq_cidade        = Request("w_sq_cidade")
     w_carteira_emissao = Request("w_carteira_emissao")
     w_carteira_validade= Request("w_carteira_validade")
  ElseIf O = "L" Then
     DB_GetAlberList RS, p_carteira, p_nome, p_sexo, p_uf, p_conhece_albergue, p_visitas, p_classificacao, p_destino, p_motivo_viagem, p_forma_conhece
     If p_ordena > "" Then RS.sort = p_ordena Else RS.sort = "nome" End If
  Else
     
     ' Impede que uma carteira já existente seja inserida
     If O = "I" and w_chave = "" and w_carteira > "" Then
        DB_GetAlberData RS, null, w_carteira
        If RS.RecordCount > 0 Then 
           ScriptOpen "JavaScript"
           ShowHTML "  alert('Carteira já existente!');"
           ShowHTML "  history.back(1);"
           ScriptClose
        End If
     End If
     
     If InStr("ATDEV",O) > 0 Then
        ' Recupera os dados do beneficiário em co_pessoa
        DB_GetAlberData RS, w_chave, null
        w_chave            = RS("sq_alberguista")
        w_carteira         = RS("carteira")
        w_nome             = RS("nome")
        w_nascimento       = FormataDataEdicao(RS("nascimento"))
        w_endereco         = RS("endereco")
        w_bairro           = RS("bairro")
        w_cep              = RS("cep")
        w_cidade           = RS("cidade")
        w_uf               = RS("uf")
        w_ddd              = RS("ddd")
        w_fone             = RS("fone")
        w_cpf              = RS("cpf")
        w_rg_numero        = RS("rg_numero")
        w_rg_emissor       = RS("rg_emissor")
        w_email            = RS("email")
        w_sexo             = RS("sexo")
        w_formacao         = RS("formacao")
        w_trabalha         = RS("trabalha")
        w_email_trabalho   = RS("email_trabalho")
        w_conhece_albergue = RS("conhece_albergue")
        w_visitas          = RS("visitas")
        w_classificacao    = RS("classificacao")
        w_destino          = RS("destino")
        w_destino_outros   = RS("destino_outros")
        w_motivo_viagem    = RS("motivo_viagem")
        w_motivo_outros    = RS("motivo_outros")
        w_forma_conhece    = RS("forma_conhece")
        w_forma_outros     = RS("forma_outros")
        w_sq_cidade        = RS("sq_cidade")
        w_carteira_emissao = FormataDataEdicao(RS("carteira_emissao"))
        w_carteira_validade= FormataDataEdicao(RS("carteira_validade"))
        DesconectaBD
     End If
     
  End If  
  Cabecalho
  ShowHTML "<HEAD>"
  ' Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  ' tratando as particularidades de cada serviço
  ScriptOpen "JavaScript"
  Modulo
  FormataCPF
  FormataCEP
  CheckBranco
  FormataValor
  FormataData
  ValidateOpen "Validacao"
  If Instr("P",O) > 0 Then
     Validate "p_carteira", "Carteira", "", "", "10", "20", "", "0123456789"
     Validate "p_nome", "Nome", "", "", "4", "50", "1", ""
     Validate "p_sexo", "Sexo", "SELECT", "", 1, 1, "MF", ""
     Validate "p_uf", "UF", "SELECT", "", 2, 2, "1", ""
     Validate "p_conhece_albergue", "Conhece Albergue da Juventude", "SELECT", "", 1, 1, "SN", ""
     Validate "p_visitas", "Visitas", "SELECT", "", 1, 1, "", "0123456789"
     Validate "p_classificacao", "Classificação", "SELECT", "", 1, 1, "", "0123456789"
     Validate "p_destino", "Destino da viagem", "SELECT", "", 1, 1, "", "0123456789"
     Validate "p_motivo_viagem", "Motivo da viagem", "SELECT", "", 1, 1, "", "0123456789"
     Validate "p_forma_conhece", "Como conheceu", "SELECT", "", 1, 1, "", "0123456789"
     Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
     ShowHTML "  if (theForm.p_carteira.value == '' && theForm.p_nome.value == '' && theForm.p_sexo.value == '' && theForm.p_uf.value == '' && theForm.p_conhece_albergue.value == '' && theForm.p_visitas.value == '' && theForm.p_classificacao.value == '' && theForm.p_destino.value == '' && theForm.p_motivo_viagem.value == '' && theForm.p_forma_conhece.value == '') {"
     ShowHTML "     alert('Informe pelo menos um critério de filtragem!');"
     ShowHTML "     theForm.p_carteira.focus();"
     ShowHTML "     return false;"
     ShowHTML "  }"
  ElseIf O = "I" or O = "A" Then
     ShowHTML "  if (theForm.Botao.value == ""Troca"") { return true; }"
     Validate "w_carteira",          "Carteira",                      "",       "1", "10", "20",   "", "0123456789"
     Validate "w_carteira_emissao",  "Data emissão carteira",         "DATA",    "1",  10,   10,   "", "0123456789/"
     Validate "w_carteira_validade", "Validade carteira",             "DATA",    "1",  10,   10,   "", "0123456789/"
     Validate "w_nome",              "Nome",                          "1",        1,    4,   60,  "1", "1"
     Validate "w_cpf",               "CPF",                           "CPF",     "",   14,   14,   "", "0123456789.-"
     Validate "w_nascimento",        "Data de nascimento",            "DATA",     1,   10,   10,   "", "0123456789/"
     Validate "w_sexo",              "Sexo",                          "SELECT",   1,    1,    1, "MF", ""
     Validate "w_rg_numero",         "RG número",                     "1",       "",    3,   20,  "1", "1"
     Validate "w_rg_emissor",        "RG emissor",                    "1",       "",    3,   20,  "1", "1"
     Validate "w_endereco",          "Endereço",                      "1",        1,    4,   60,  "1", "1"
     Validate "w_bairro",            "Bairro",                        "1",        1,    2,   30,  "1", "1"
     Validate "w_cidade",            "Cidade",                        "1",        1,    2,   40,  "1", "1"
     Validate "w_uf",                "UF",                            "SELECT",   1,    2,    2,  "1", ""
     Validate "w_cep",               "CEP",                           "1",        1,    9,    9,   "", "0123456789-"
     Validate "w_ddd",               "DDD",                           "1",       "",    3,    3,   "", "0123456789"
     Validate "w_fone",              "Telefone",                      "1",       "",    6,   50,  "1", "1"
     Validate "w_email",             "E-Mail pessoal",                "1",       "",    4,   60,  "1", "1"
     Validate "w_email_trabalho",    "E-Mail trabalho",               "1",       "",    4,   60,  "1", "1"
     Validate "w_conhece_albergue",  "Conhece Albergue da Juventude", "SELECT",  "",    1,    1, "SN", ""
     Validate "w_visitas",           "Visitas",                       "SELECT",  "",    1,    1,   "", "0123456789"
     Validate "w_classificacao",     "Classificação",                 "SELECT",  "",    1,    1,   "", "0123456789"
     Validate "w_destino",           "Destino da viagem",             "SELECT",  "",    1,    1,   "", "0123456789"
     Validate "w_destino_outros",    "Outro destino",                 "1",       "",    2,   50,  "1", "1"
     Validate "w_motivo_viagem",     "Motivo da viagem",              "SELECT",  "",    1,    1,   "", "0123456789"
     Validate "w_motivo_outros",     "Outro motivo",                  "1",       "",    2,   50,  "1", "1"
     Validate "w_forma_conhece",     "Como conheceu",                 "SELECT",  "",    1,    1,   "", "0123456789"
     Validate "w_forma_outros",      "Outro modo",                    "1",       "",    2,   50,  "1", "1"
     Validate "w_assinatura",        "Assinatura Eletrônica",         "1",      "1",  "6", "30",  "1", "1"
  ElseIf O = "E" or O = "T" or O = "D" Then
     Validate "w_assinatura",       "Assinatura Eletrônica",          "1",      "1",  "6", "30",  "1", "1"
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & Request.ServerVariables("server_name") & "/siw/"">"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf Instr("IA",O) > 0 Then 
     BodyOpen "onLoad='document.Form.w_carteira.focus()';"
  ElseIf Instr("ETDV",O) > 0 Then
     BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_carteira.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""1"">"
    ShowHTML "                         <a accesskey=""I"" class=""SS"" href=""" &  w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
    If p_nome & p_carteira & p_sexo & p_uf & p_conhece_albergue & p_visitas & p_classificacao & p_destino & p_motivo_viagem & p_forma_conhece & p_Ordena > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_dir & w_Pagina & par & "&O=P&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_dir & w_Pagina & par & "&O=P&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Carteira</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nascimento</font></td>"
    ShowHTML "          <td><font size=""1""><b>Emissão</font></td>"
    ShowHTML "          <td><font size=""1""><b>Validade</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td align=""center"" nowrap><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_pagina & "Visualizar&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_alberguista") & "&w_tipo=Volta&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste alberguista."">" & RS("carteira") & "</td>"
        ShowHTML "        <td align=""left""><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS("nascimento")),"---") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS("carteira_emissao")),"---") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS("carteira_validade")),"---") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_cliente=" & w_cliente & "&w_chave=" & RS("sq_alberguista") & "&w_carteira=" & RS("carteira") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Altera as informações cadastrais do usuário"">Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_cliente=" & w_cliente & "&w_chave=" & RS("sq_alberguista") & "&w_carteira=" & RS("carteira") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exclui o usuário do banco de dados"">Excluir</A>&nbsp"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    MontaBarra w_dir&w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
    ShowHTML "</tr>"    
    DesConectaBD     
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_dir & w_Pagina & par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente & """>"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr valign=""top""><td valign=""top"">"
    ShowHTML "        <table width=""100%"" border=""0""><tr valign=""top"">"    
    ShowHTML "          <tr><td colspan=""3"" valign=""top""><font size=""1""><b><U>C</U>arteira:<br><INPUT ACCESSKEY=""C"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_carteira"" size=""20"" maxlength=""20"" value=""" & p_carteira & """></td>"
    ShowHTML "          <tr><td colspan=""3"" valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_nome"" size=""50"" maxlength=""50"" value=""" & p_nome & """></td>"
    ShowHTML "          <tr valign=""top"">"
    SelecaoSexo "Se<u>x</u>o:", "X", null, p_sexo, null, "p_sexo", null, null
    SelecaoUF "<u>U</u>F:", "U", null, p_uf, null, "p_uf", null, null
    ShowHTML "          <tr valign=""top"">"
    SelecaoConhece_Albergue "<u>C</u>onhece Albergue da Juventude?", "C", null, p_conhece_albergue, null, "p_conhece_albergue", null, null
    SelecaoVisitas "<u>V</u>isitas como alberguista:", "V", null, p_visitas, null, "p_visitas", null, null
    SelecaoClassificacao "Como c<u>l</u>assifica:", "L", null, p_classificacao, null, "p_classificacao", null, null
    ShowHTML "          <tr valign=""top"">"
    SelecaoDestino "De<u>s</u>tino da viagem:", "S", null, p_destino, null, "p_destino", null, null
    SelecaoMotivo_Viagem "<u>M</u>otivo da viagem:", "M", null, p_motivo_viagem, null, "p_motivo_viagem", null, null
    SelecaoForma_Conhece "Como con<u>h</u>eceu?", "H", null, p_forma_conhece, null, "p_forma_conhece", null, null
    ShowHTML "          <tr><td colspan=""3"" valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_ordena"" size=""1"">"
    If p_Ordena="CARTEIRA" Then
       ShowHTML "          <option value=""carteira"" SELECTED>Carteira<option value="""">Nome"
    Else
       ShowHTML "          <option value=""carteira"">Carteira<option value="""" SELECTED>Nome"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "          <tr><td colspan=""3"" valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
    ShowHTML "        </table></td></tr>"    
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&R=" & w_pagina & par & "&O=I&&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Incluir"">"
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Remover filtro"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("IAETDV",O) > 0 Then

    If InStr("ETDV",O) > 0 Then w_Disabled = " DISABLED " End If

    AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML MontaFiltro("POST")
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td><table border=0 width=""100%""><tr valign=""top"">"
    ShowHTML "		    <td><font size=""1""><b><u>C</u>arteira:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_carteira"" class=""STI"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_carteira & """></td>"
    ShowHTML "		    <td><font size=""1""><b><u>E</u>missão:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_carteira_emissao"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_carteira_emissao & """ onKeyDown=""FormataData(this,event);""></td>"
    ShowHTML "		    <td><font size=""1""><b><u>V</u>alidade:</b><br><input " & w_Disabled & " accesskey=""V"" type=""text"" name=""w_carteira_validade"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_carteira_validade & """ onKeyDown=""FormataData(this,event);""></td>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "		    <td colspan=2><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""STI"" SIZE=""45"" MAXLENGTH=""60"" VALUE=""" & w_nome & """></td>"
    ShowHTML "		    <td><font size=""1""><b>C<u>P</u>F:</b><br><input " & w_Disabled & " accesskey=""P"" type=""text"" name=""w_cpf"" class=""STI"" SIZE=""14"" MAXLENGTH=""14"" VALUE=""" & w_cpf & """ onKeyDown=""FormataCPF(this,event);""></td>"
    ShowHTML "		    <td><font size=""1""><b><u>D</u>ata de nascimento:</b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_nascimento"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_nascimento & """ onKeyDown=""FormataData(this,event);""></td>"
    ShowHTML "        <tr valign=""top"">"
    SelecaoSexo "Se<u>x</u>o:", "X", null, w_sexo, null, "w_sexo", null, null
    ShowHTML "		    <td><font size=""1""><b>R<u>G</u> número:</b><br><input " & w_Disabled & " accesskey=""R"" type=""text"" name=""w_rg_numero"" class=""STI"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_rg_numero & """></td>"
    ShowHTML "		    <td><font size=""1""><b>RG emi<u>s</u>sor:</b><br><input " & w_Disabled & " accesskey=""S"" type=""text"" name=""w_rg_emissor"" class=""STI"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_rg_emissor & """></td>"
    ShowHTML "        <tr><td colspan=4><font size=""1""><b>Ende<u>r</u>eço:</b><br><input " & w_Disabled & " accesskey=""R"" type=""text"" name=""w_endereco"" class=""STI"" SIZE=""60"" MAXLENGTH=""60"" VALUE=""" & w_endereco & """></td>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "		    <td><font size=""1""><b><u>B</u>airro:</b><br><input " & w_Disabled & " accesskey=""B"" type=""text"" name=""w_bairro"" class=""STI"" SIZE=""20"" MAXLENGTH=""30"" VALUE=""" & w_bairro & """></td>"
    ShowHTML "		    <td><font size=""1""><b><u>C</u>idade:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_cidade"" class=""STI"" SIZE=""25"" MAXLENGTH=""40"" VALUE=""" & w_cidade & """></td>"
    SelecaoUF "<u>U</u>F:", "U", null, w_uf, null, "w_uf", null, null
    ShowHTML "		    <td><font size=""1""><b>CE<u>P</u>:</b><br><input " & w_Disabled & " accesskey=""P"" type=""text"" name=""w_cep"" class=""STI"" SIZE=""9"" MAXLENGTH=""9"" VALUE=""" & w_cep & """ onKeyDown=""FormataCEP(this,event);""></td>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "		    <td><font size=""1""><b><u>D</u>DD:</b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_ddd"" class=""STI"" SIZE=""3"" MAXLENGTH=""3"" VALUE=""" & w_ddd & """></td>"
    ShowHTML "		    <td><font size=""1""><b><u>F</u>one:</b><br><input " & w_Disabled & " accesskey=""F"" type=""text"" name=""w_fone"" class=""STI"" SIZE=""20"" MAXLENGTH=""50"" VALUE=""" & w_fone & """></td>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "		    <td colspan=2><font size=""1""><b>e-<u>M</u>ail pessoal:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_email"" class=""STI"" SIZE=""30"" MAXLENGTH=""60"" VALUE=""" & w_email & """></td>"
    ShowHTML "		    <td colspan=2><font size=""1""><b>e-<u>M</u>ail trabalho:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_email_trabalho"" class=""STI"" SIZE=""30"" MAXLENGTH=""60"" VALUE=""" & w_email_trabalho & """></td>"
    ShowHTML "        <tr valign=""top"">"
    SelecaoConhece_Albergue "<u>C</u>onhece Albergue da Juventude?", "C", null, w_conhece_albergue, null, "w_conhece_albergue", null, null
    SelecaoVisitas "<u>V</u>isitas como alberguista:", "V", null, w_visitas, null, "w_visitas", null, null
    SelecaoClassificacao "Como c<u>l</u>assifica:", "L", null, w_classificacao, null, "w_classificacao", null, null
    ShowHTML "        <tr valign=""top"">"
    SelecaoDestino "De<u>s</u>tino da viagem:", "S", null, w_destino, null, "w_destino", null, null
    ShowHTML "		    <td colspan=3><font size=""1""><b><u>O</u>utro destino:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_destino_outros"" class=""STI"" SIZE=""50"" MAXLENGTH=""50"" VALUE=""" & w_destino_outros & """></td>"
    ShowHTML "        <tr valign=""top"">"
    SelecaoMotivo_Viagem "<u>M</u>otivo da viagem:", "M", null, w_motivo_viagem, null, "w_motivo_viagem", null, null
    ShowHTML "		    <td colspan=3><font size=""1""><b><u>O</u>utro motivo:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_motivo_outros"" class=""STI"" SIZE=""50"" MAXLENGTH=""50"" VALUE=""" & w_motivo_outros & """></td>"
    ShowHTML "        <tr valign=""top"">"
    SelecaoForma_Conhece "Como con<u>h</u>eceu?", "H", null, w_forma_conhece, null, "w_forma_conhece", null, null
    ShowHTML "		    <td colspan=3><font size=""1""><b><u>O</u>utro modo:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_forma_outros"" class=""STI"" SIZE=""50"" MAXLENGTH=""50"" VALUE=""" & w_forma_outros & """></td>"
    ShowHTML "        <tr><td colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      </table>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"

    ' Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
       ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & R & "&O=L&w_cliente=" & Request("w_cliente") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
    ElseIf O = "A" Then
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
       ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & R & "&O=L&w_cliente=" & Request("w_cliente") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
    Else
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
       ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & R & "&O=P&w_cliente=" & Request("w_cliente") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
    End If
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    'ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_chave               = Nothing 
  Set w_carteira            = Nothing 
  Set w_nome                = Nothing 
  Set w_nascimento          = Nothing 
  Set w_endereco            = Nothing 
  Set w_bairro              = Nothing 
  Set w_cep                 = Nothing 
  Set w_cidade              = Nothing
  Set w_uf                  = Nothing 
  Set w_ddd                 = Nothing 
  Set w_fone                = Nothing 
  Set w_cpf                 = Nothing 
  Set w_rg_numero           = Nothing 
  Set w_rg_emissor          = Nothing 
  Set w_email               = Nothing 
  Set w_sexo                = Nothing 
  Set w_formacao            = Nothing
  Set w_trabalha            = Nothing 
  Set w_email_trabalho      = Nothing 
  Set w_conhece_albergue    = Nothing 
  Set w_visitas             = Nothing 
  Set w_classificacao       = Nothing 
  Set w_destino             = Nothing
  Set w_destino_outros      = Nothing 
  Set w_motivo_viagem       = Nothing 
  Set w_motivo_outros       = Nothing 
  Set w_forma_conhece       = Nothing 
  Set w_forma_outros        = Nothing
  Set w_sq_cidade           = Nothing 
  Set w_carteira_emissao    = Nothing 
  Set w_carteira_validade   = Nothing
  
  Set w_troca               = Nothing 
  Set i                     = Nothing 
  Set w_erro                = Nothing 
  Set w_como_funciona       = Nothing 
  Set w_cor                 = Nothing 


  Set p_carteira            = Nothing 
  Set p_nome                = Nothing 
  Set p_sexo                = Nothing 
  Set p_uf                  = Nothing 
  Set p_conhece_albergue    = Nothing 
  Set p_visitas             = Nothing 
  Set p_classificacao       = Nothing 
  Set p_destino             = Nothing 
  Set p_motivo_viagem       = Nothing 
  Set p_forma_conhece       = Nothing 
  Set p_ordena              = Nothing
End Sub
REM =========================================================================
REM Fim da tela de beneficiário
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabela de bancos
REM -------------------------------------------------------------------------
Sub Cargo

  Dim w_co_cargo, p_codigo
  Dim w_ds_cargo, p_ds_cargo
  Dim p_Ordena

  p_ds_cargo         = uCase(Request("p_ds_cargo"))
  p_codigo           = uCase(Request("p_codigo"))
  p_ordena           = uCase(Request("p_ordena"))
  
  If O = "L" Then
     DB_GetPositionList RS
     If p_ds_cargo & p_codigo > "" Then
        w_filter = ""
        If p_codigo   > ""   Then w_filter = w_filter & " and co_cargo  = '" & p_codigo & "'"       End If
        If p_ds_cargo > ""   Then w_filter = w_filter & " and ds_cargo like '*" & p_ds_cargo & "*'" End If
        RS.Filter = Mid(w_filter,6,255)
     End If
     If p_ordena > "" Then RS.sort = p_ordena Else RS.sort = "co_cargo" End If
  ElseIf O = "A" or O = "E" Then
     w_co_cargo = Request("w_co_cargo")
     DB_GetPositionData RS, w_co_cargo
     w_ds_cargo       = RS("ds_cargo")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_co_cargo1", "Código", "1", "1", "3", "17", "1", "1"
        Validate "w_ds_cargo", "Descrição", "1", "1", "3", "52", "1", "1"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     ElseIf O="P" Then
        Validate "p_codigo", "Código", "1", "", "3", "17", "1", "1"
        Validate "p_ds_cargo", "Descrição", "1", "", "3", "52", "1", "1"
        Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If InStr("IAE",O) > 0 Then
     If O = "E" Then
        BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
     Else
        BodyOpen "onLoad='document.Form.w_co_cargo1.focus()';"
     End If
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_codigo.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    w_filter = ""
    If p_codigo > "" Then 
       w_filter = w_filter & "[Código: <b>" & p_codigo & "</b>]&nbsp;"
    End If    
    If p_ds_cargo > "" Then 
       w_filter = w_filter & "[Descrição: <b>" & p_ds_cargo & "</b>]&nbsp;"
    End If
    If w_filter > ""  Then ShowHTML "<tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=5><font size=1><b>&nbsp;Filtro:&nbsp;</b>" & w_filter & "</font><BR>"  
    ShowHTML "<tr><td><font size=""2"">"
    If P1 <> 1 Then
       ShowHTML "                            <a accesskey=""I"" class=""SS"" href=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_codigo=" & p_codigo & "&p_ds_cargo=" &p_ds_cargo& "&p_ordena=" & p_ordena & """><u>I</u>ncluir</a>&nbsp;"
    End If
    If p_ds_cargo & p_Ordena & p_codigo > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_codigo=" &p_codigo& "&p_ds_cargo=" & p_ds_cargo & "&p_ordena=" & p_ordena & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_codigo=" &p_codigo& "&p_ds_cargo=" & p_ds_cargo & "&p_ordena=" & p_ordena & """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""2""><b>Código</font></td>"
    ShowHTML "          <td><font size=""2""><b>Descrição</font></td>"
    If P1 <> 1 Then
       ShowHTML "          <td width=""20%""><font size=""2""><b>Operações</font></td>"
    End If
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=3 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("co_cargo") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("ds_cargo") & "</td>"
        If P1 <> 1 Then
           ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
           ShowHTML "          <A class=""HL"" HREF=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_co_cargo=" & RS("co_cargo") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_codigo=" &p_codigo& "&p_ds_cargo=" & p_ds_cargo & "&p_ordena=" & p_ordena & """>Alterar</A>&nbsp"
           ShowHTML "          <A class=""HL"" HREF=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_co_cargo=" & RS("co_cargo") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_codigo=" &p_codigo& "&p_ds_cargo=" & p_ds_cargo & "&p_ordena=" & p_ordena & """>Excluir</A>&nbsp"
           ShowHTML "        </td>"
        End If
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    MontaBarra w_dir&w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG, RS.PageCount, P3, P4, RS.RecordCount
    ShowHTML "</tr>"
    DesConectaBD     
  ElseIf Instr("IAE",O) > 0 Then
    If O = "E" Then
       w_Disabled = "DISABLED"
    End If
    AbreForm "Form", w_Dir&w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""p_ds_cargo"" value=""" & p_ds_cargo &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_codigo"" value=""" & p_codigo &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ordena"" value=""" & p_ordena &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_co_cargo"" value=""" & w_co_cargo &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>C</U>ódigo:<br><INPUT ACCESSKEY=""C"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_co_cargo1"" size=""7"" maxlength=""17"" value=""" & w_co_cargo & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>D</U>escrição:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_ds_cargo"" size=""50"" maxlength=""50"" value=""" & w_ds_cargo & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_ds_cargo=" & p_ds_cargo & "&p_codigo=" & p_codigo & "&p_ordena=" & p_ordena & "';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Dir&w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>C</U>ódigo:<br><INPUT ACCESSKEY=""C"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_codigo"" size=""7"" maxlength=""17"" value=""" & p_codigo & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>D</U>escrição:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_ds_cargo"" size=""50"" maxlength=""50"" value=""" & p_ds_cargo & """></td>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_ordena"" size=""1"">"
    If p_Ordena="DS_CARGO" Then
       ShowHTML "          <option value="""">Código<option value=""ds_cargo"" SELECTED>Descrição"
    Else
       ShowHTML "          <option value="""" SELECTED>Código<option value=""ds_cargo"">Descrição"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td>"
    ShowHTML "      </table>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
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

  Set w_co_cargo         = Nothing
  Set w_ds_cargo         = Nothing
  Set p_codigo           = Nothing
  Set p_ordena           = Nothing

End Sub
REM =========================================================================
REM Fim da tabela de area de atuação
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualização
REM -------------------------------------------------------------------------
Sub Visualizar

  Dim w_erro, w_logo, w_chave, w_tipo
  
  w_chave = Request("w_chave")
  w_tipo  = Request("w_tipo")

  If cDbl(Nvl(P2,0)) = 1 Then
     Response.ContentType = "application/msword"
  Else 
     cabecalho
  End If
  
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>Dados do cadastro</TITLE>"
  ShowHTML "</HEAD>" 
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If cDbl(Nvl(P2,0)) = 0 Then 
     BodyOpen "onLoad='document.focus()'; "
  End If
  ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR>"
  If cDbl(Nvl(P2,0)) = 0 Then
     DB_GetCustomerData RS, w_cliente
     ShowHTML "  <TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, "img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30), null, null, null, "EMBED") & """>"
     DesconectaBD
  End If
  ShowHTML "  <TD ALIGN=""RIGHT""><B><FONT SIZE=5 COLOR=""#000000"">"
  ShowHTML "Dados do cadastro"
  ShowHTML "</FONT><TR><TD ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">" & DataHora() & "</B>"
  If cDbl(Nvl(P2,0)) = 0 Then
     ShowHTML "&nbsp;&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & "Visualizar&P2=1&SG=ALVISUAL','VisualAlberguistaWord','menubar=yes resizable=yes scrollbars=yes');"">"
  End If
  ShowHTML "</TD></TR>"
  ShowHTML "</FONT></B></TD></TR></TABLE>"
  If cDbl(Nvl(P2,0)) = 0 Then
     ShowHTML "<HR>"
  End If
  
  If w_tipo > "" and w_tipo <> "WORD" Then
     ShowHTML "<center><B><font size=1>Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</font></b></center>"
  End If
  ' Chama a função de visualização dos dados do Alberguista, na opção "Listagem"
  VisualAlberguista w_chave, "L"
  
  If w_tipo > "" and w_tipo <> "WORD" Then
     ShowHTML "<center><B><font size=1>Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</font></b></center>"
  End If
  
  If cDbl(Nvl(P2,0)) = 0 Then
     Rodape
  End If
  
  Set w_erro            = Nothing 
  Set w_logo            = Nothing 
  Set w_chave           = Nothing 
  Set w_tipo            = Nothing

End Sub
REM =========================================================================
REM Fim da rotina de visualização
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava

  Dim p_codigo, p_carteira, p_nome
  Dim p_co_cargo
  Dim p_ds_cargo
  Dim p_ordena
  Dim w_Null

  Cabecalho
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao    
  Select Case SG
    Case "MESA"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          DML_ALBCAD O, _
                   Nvl(Request("w_chave"),""), Request("w_carteira"),  Request("w_nome"),Request("w_nascimento"),_
                   Request("w_endereco"), Request("w_bairro"), Request("w_cep"), Request("w_cidade"),_
                   Request("w_uf"), Request("w_ddd"), Request("w_fone"), Nvl(Request("w_cpf"),""),_
                   Nvl(Request("w_rg_numero"),""), Nvl(Request("w_rg_emissor"),""), Nvl(Request("w_email"),""), Request("w_sexo"),_
                   null, Nvl(Request("w_trabalha"),""), Nvl(Request("w_email_Trabalho"),""),_
                   Nvl(Request("w_conhece_Albergue"),""), Nvl(Request("w_visitas"),""),Nvl(Request("w_classificacao"),""),_
                   Nvl(Request("w_destino"),""), Nvl(Request("w_destino_Outros"),""), Nvl(Request("w_motivo_Viagem"),""),_
                   Nvl(Request("w_motivo_Outros"),""), Nvl(Request("w_forma_Conhece"),""), Nvl(Request("w_forma_Outros"),""),null,_
                   Nvl(Request("w_carteira_Emissao"),""), Nvl(Request("w_carteira_Validade"),"")
                   
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_carteira=" & Request("p_carteira") & "&p_nome=" & Request("p_nome") & "&p_ordena=" & Request("p_ordena") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    
    Case "SCARGO"
       p_ds_cargo        = uCase(Request("p_ds_cargo"))
       p_codigo          = uCase(Request("p_codigo"))
       p_ordena          = uCase(Request("p_ordena"))
  
       ' Verifica se a Assinatura Eletrônica é válida
       
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_SCARGO O, _
                   Request("w_co_cargo"), Request("w_co_cargo1"),  Request("w_ds_cargo")
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_ds_cargo=" & p_ds_cargo & "&p_codigo=" & p_codigo & "&p_ordena=" & p_ordena & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
       
  End Select

  Set p_co_cargo        = Nothing
  Set p_codigo          = Nothing
  Set p_ds_cargo        = Nothing
  Set p_ordena          = Nothing
  Set w_Null            = Nothing
End Sub
REM -------------------------------------------------------------------------
REM Fim do procedimento que executa as operações de BD
REM =========================================================================

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  ' Verifica se o usuário tem lotação e localização
  Select Case Par
    Case "CAD"        Cadastro
    Case "CARGO"      Cargo
    Case "GRAVA"      Grava
    Case "VISUALIZAR" Visualizar
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

