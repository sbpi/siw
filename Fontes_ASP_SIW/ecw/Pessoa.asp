<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DML_Seguranca.asp" -->
<%
Response.Expires = 0
REM =========================================================================
REM  /pessoa.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia o módulo de formulários do sistema
REM Mail     : alex@sbpi.com.br
REM Criacao  : 25/11/2002 16:17
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
Dim dbms, sp, RS
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Pagina, w_Disabled, w_TP, w_cliente
Dim w_Assinatura, w_dir
Public w_Data_Banco
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
w_Assinatura = uCase(Request("w_Assinatura"))
w_Pagina     = "pessoa.asp?par="
w_Dir        = "ecw/"
w_Disabled   = " ENABLED "

If (par="DESPESA" or par="TRECHO" or par="VISUAL") and O = "A" and Request("w_Handle") = "" Then O = "L" End If ' Configura o valor de O se for a tela de listagem
  
Select Case O
  Case "I" 
     w_TP = TP & " - Novo Acesso"
  Case "A" 
     ' Se a chamada for para as rotinas de visualização, não concatena nada
     If par="VISUAL" or par="ENVIAR" Then
        w_TP = TP
     Else
        w_TP = TP & " - Alteração"
     End If
  Case "D" 
     If SG="SGUSU" or SG="CLUSUARIO" Then
        w_TP = TP & " - Bloqueio de Acesso"
     ElseIf SG="RHUSU" Then
        w_TP = TP & " - Desligamento"
     End If
  Case "T" 
     w_TP = TP & " - Ativação"
  Case "E" 
     w_TP = TP & " - Exclusao"
  Case "V" 
     w_TP = TP & " - Envio"
  Case "P"
     w_TP = TP & " - Filtragem"
  Case Else
     w_TP = TP & " - Listagem"
End Select

w_data_banco = Date()

' Se for acesso do módulo de gerenciamento de clientes do SIW, o cliente será determinado por parâmetro;
' caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
If SG="CLUSUARIO" Then
   w_cliente = Request("w_cliente")
Else
   w_cliente = Session("p_cliente")
End If
  
Main

FechaSessao

Set w_dir        = Nothing
Set w_cliente    = Nothing
Set w_Data_Banco = Nothing

Set RS           = Nothing
Set Par          = Nothing
Set P1           = Nothing
Set P2           = Nothing
Set P3           = Nothing
Set P4           = Nothing
Set TP           = Nothing
Set SG           = Nothing
Set R            = Nothing
Set O            = Nothing
Set w_Cont       = Nothing
Set w_Pagina     = Nothing
Set w_Disabled   = Nothing
Set w_TP         = Nothing
Set w_Assinatura = Nothing

REM =========================================================================
REM Rotina de beneficiário
REM -------------------------------------------------------------------------
Sub Benef

  ' Nesta rotina, P1 = 0 indica que não pode haver troca do beneficiário
  '                  = 1 indica que pode haver troca de beneficiário
  '               P2 = 0 indica que não pegará os dados bancários, nem da forma de pagamento
  '                  = 1 indica que pegará os dados bancários, mas não da forma de pagamento
  '                  = 2 indica que pegará os dados bancários e também da forma de pagamento
  '               P3 = 0 indica que não pegará os dados de lotação
  '                  = 1 indica que pegará os dados de lotação
  '               P4 = 0 indica que não permitirá a reinicialização da senha/assinatura
  '                  = 1 indica que permitirá a reinicialização da senha/assinatura
  
  Dim w_beneficiario
  Dim w_frm_pag, w_nome, w_nome_resumido, w_rg, w_username, w_passaporte, w_nascimento, w_entrada
  Dim w_end, w_comple, w_cidade, w_uf, w_cep, w_pais, w_telefone, w_fax, w_email
  Dim w_nrobanco, w_nroagencia, w_operacao, w_contacorrente
  Dim w_sq_unidade_lotacao, w_sq_localizacao
  Dim w_projeto, w_saldo_ferias, w_limite_emprestimo, w_sq_tipo_vinculo
  
  Dim w_troca
  
  Dim i
  Dim w_erro
  Dim w_sq_solicitacao_vinc
  Dim w_como_funciona
  Dim w_cor
  Dim w_identidade
  Dim w_pessoa_autorizada
  Dim w_motivo
  Dim w_sq_pessoa
  Dim w_sq_unidade_entrega
  Dim w_valor
  Dim w_finalidade

  Dim p_gestor, p_lotacao, p_localizacao, p_nome
  Dim p_data_inicio
  Dim p_data_fim
  Dim p_solicitante
  Dim p_numero
  Dim p_ordena

  Dim w_observacao
  Dim w_sq_solicitacao
  Dim w_sq_servico
  Dim w_sq_situacao_servico
  Dim w_descricao
  Dim w_username_solicitante
  Dim w_nome_solicitante
  Dim w_data_inclusao
  Dim w_data_programada
  Dim w_data_programada_fim
  Dim vetor1(50)
  Dim vetor2(50)
  Dim w_readonly
  Dim w_gestor_seguranca, w_gestor_sistema
  
  w_readonly        = ""
  w_erro            = ""
  w_troca           = Request("w_troca")
  w_sq_pessoa       = Request("w_sq_pessoa")
  p_data_inicio     = uCase(Request("p_data_inicio"))
  p_data_fim        = uCase(Request("p_data_fim"))
  p_solicitante     = uCase(Request("p_solicitante"))
  p_numero          = uCase(Request("p_numero"))
  p_ordena          = uCase(Request("p_ordena"))
  p_localizacao     = uCase(Request("p_localizacao"))
  p_lotacao         = uCase(Request("p_lotacao"))
  p_nome            = uCase(Request("p_nome"))
  p_gestor          = uCase(Request("p_gestor"))
  
  w_sq_solicitacao  = Request("w_sq_solicitacao")
  w_username        = Request("w_username")

  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página
     w_username            = Request("w_username")
     w_nome                = Request("w_nome")
     w_nome_resumido       = Request("w_nome_resumido")
     w_rg                  = Request("w_rg")
     w_passaporte          = Request("w_passaporte")
     w_nascimento          = Request("w_nascimento")
     w_end                 = Request("w_end")
     w_comple              = Request("w_comple")
     w_pais                = Request("w_pais")
     w_uf                  = Request("w_uf")
     w_cidade              = Request("w_cidade")
     w_cep                 = Request("w_cep")
     w_telefone            = Request("w_telefone")
     w_fax                 = Request("w_fax")
     w_email               = Request("w_email")
     w_sq_unidade_lotacao  = Request("w_sq_unidade_lotacao")
     w_sq_localizacao      = Request("w_sq_localizacao")
     w_projeto             = Request("w_projeto")
     w_entrada             = Request("w_entrada")
     w_saldo_ferias        = Request("w_saldo_ferias")
     w_limite_emprestimo   = Request("w_limite_emprestimo")
     w_sq_tipo_vinculo     = Request("w_sq_tipo_vinculo")
     w_gestor_seguranca    = Request("w_gestor_seguranca")
     w_gestor_sistema      = Request("w_gestor_sistema")

  Else
     
     If O = "I" and w_sq_pessoa = "" and SG = "SGUSU" and w_username > "" Then
        DB_GetUserData rs, w_cliente, w_username
        If RS.RecordCount > 0 Then 
           ScriptOpen "JavaScript"
           ShowHTML "  alert('Usuário já existente!');"
           ShowHTML "  history.back(1);"
           ScriptClose
        End If
     End If
     
     If InStr("IATDEV",O) > 0 and w_sq_pessoa > "" Then
        ' Recupera os dados do beneficiário em co_pessoa
        DB_GetPersonData RS, w_cliente, w_sq_pessoa, null, null
        If RS.RecordCount > 0 Then 
           If O = "I" and SG = "SGUSU" Then
              ScriptOpen "JavaScript"
              ShowHTML "  alert('Usuário já existente!');"
              ShowHTML "  history.back(1);"
              ScriptClose
           Else
              w_nome               = RS("Nome")
              w_nome_resumido      = RS("Nome_Resumido")
              w_email              = RS("Email")
              w_sq_unidade_lotacao = RS("sq_unidade")
              w_sq_localizacao     = RS("sq_localizacao")
              w_sq_tipo_vinculo    = RS("sq_tipo_vinculo")
              w_gestor_seguranca   = RS("gestor_seguranca")
              w_gestor_sistema     = RS("gestor_sistema")
           End If
        End If
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
  FormataDataHora
  ValidateOpen "Validacao"
  If w_username = "" or Instr(Request("botao"),"Procurar") > 0 or Instr(Request("botao"),"Troca") > 0 Then ' Se o beneficiário ainda não foi selecionado
     ShowHTML "  if (theForm.Botao.value == ""Procurar"") {"
     Validate "w_nome", "Nome", "", "1", "4", "20", "1", ""
     ShowHTML "  theForm.Botao.value = ""Procurar"";"
     ShowHTML "}"
     ShowHTML "else {"
     Validate "w_username", "CPF", "", "1", "14", "14", "", "1"
     ShowHTML "}"
  ElseIf O = "I" or O = "A" Then
     ShowHTML "  if (theForm.Botao.value == ""Troca"") { return true; }"
     Validate "w_nome", "Nome", "1", 1, 5, 60, "1", "1"
     Validate "w_nome_resumido", "Nome resumido", "1", 1, 2, 15, "1", "1"
     Validate "w_email", "E-Mail", "1", "", 4, 50, "1", "1"
     Validate "w_sq_unidade_lotacao", "Unidade de lotação", "SELECT", "", 1, 10, "", "1"
     Validate "w_sq_localizacao", "Localização", "SELECT", 1, 1, 10, "", "1"
     Validate "w_sq_tipo_vinculo", "Vínculo com a organização", "SELECT", 1, 1, 10, "", "1"
     Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
  ElseIf O = "E" or O = "T" or O = "D" Then
     Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & Request.ServerVariables("server_name") & "/siw/"">"
  If P1 <> 0 and (w_username = "" or Instr(Request("botao"),"Troca") > 0 or Instr(Request("botao"),"Procurar") > 0) Then ' Se o beneficiário ainda não foi selecionado
     If Instr(Request("botao"),"Procurar") > 0 Then ' Se está sendo feita busca por nome
        BodyOpen "onLoad='document.focus()';"
     Else
        BodyOpen "onLoad='document.Form.w_username.focus()';"
     End If
  ElseIf w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf Instr("ETDV",O) > 0 Then
     BodyOpen "onLoad='document.focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_nome.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If Instr("IAETDV",O) > 0 Then
    If InStr("ETDV",O) Then
       w_Disabled = " DISABLED "
    End If
    If w_username = "" or Instr(Request("botao"),"Troca") > 0 or Instr(Request("botao"),"Procurar") > 0 Then ' Se o beneficiário ainda não foi selecionado
       ShowHTML "<FORM action=""" & w_dir & w_Pagina & par & """ method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"">"
    Else
       ShowHTML "<FORM action=""" & w_dir & w_Pagina & "Grava"" method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"">"
    End If
    ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>"
    ShowHTML "<INPUT type=""hidden"" name=""SG"" value=""" & SG & """>"
    ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & R & """>"
    ShowHTML "<INPUT type=""hidden"" name=""O"" value=""" & O &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_data_inicio"" value=""" & p_data_inicio &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_data_fim"" value=""" & p_data_fim &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_solicitante"" value=""" & p_solicitante &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_numero"" value=""" & p_numero &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_localizacao"" value=""" & p_localizacao &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_lotacao"" value=""" & p_lotacao &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_nome"" value=""" & p_nome &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_gestor"" value=""" & p_gestor &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ordena"" value=""" & p_ordena &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_solicitacao"" value=""" & w_sq_solicitacao &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_pessoa"" value=""" & w_sq_pessoa &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente &""">"

    If P1 <> 0 and (w_username = "" or InStr(Request("botao"), "Troca") > 0 or Instr(Request("botao"),"Procurar") > 0) Then
       w_frm_pag = Request("w_frm_pag")
       w_nome    = Request("w_nome")
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
       ShowHTML "    <table border=""0"">"
       ShowHTML "        <tr><td colspan=3><font size=2>Informe os dados abaixo e clique no botão ""Selecionar"" para continuar.</TD>"
       ShowHTML "        <tr><td><font size=1><b><u>C</u>PF:<br><INPUT ACCESSKEY=""C"" TYPE=""text"" Class=""STI"" NAME=""w_username"" VALUE=""" & w_username & """ SIZE=""14"" MaxLength=""14"" onKeyPress=""FormataCPF(this,event);"">"
       ShowHTML "            <td valign=""bottom""><INPUT class=""STB"" TYPE=""submit"" NAME=""Botao"" VALUE=""Selecionar"">"
       ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & replace(R,uCase(w_Dir),"") & "&w_cliente=" & Request("w_cliente") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_data_inicio=" & p_data_inicio & "&p_data_fim=" & p_data_fim & "&p_solicitante=" & p_solicitante & "&p_numero=" & p_numero & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_nome=" & p_nome & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & "';"" name=""Botao"" value=""Cancelar"">"
       ShowHTML "        <tr><td colspan=3><p>&nbsp</p>"
       ShowHTML "        <tr><td colspan=3 heigth=1 bgcolor=""#000000"">"
       ShowHTML "        <tr><td colspan=3>"
       ShowHTML "             <font size=1><b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome)<br><INPUT ACCESSKEY=""P"" TYPE=""text"" Class=""STI"" NAME=""w_nome"" VALUE=""" & w_nome & """ SIZE=""20"" MaxLength=""20"">"
       ShowHTML "              <INPUT class=""STB"" TYPE=""submit"" NAME=""Botao"" VALUE=""Procurar"" onClick=""Botao.value=this.value; document.Form.action='" & w_dir & w_Pagina & par &"'"">"
       ShowHTML "      </table>"
       If Request("w_nome") > "" Then
          DB_GetPersonList RS, w_cliente, null, "PESSOA", null, null, null, null
          RS.Filter = "username = null and nome_indice like '*" & Request("w_nome") & "*'"
          ShowHTML "<tr><td align=""center"" colspan=3>"
          ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
          ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
          ShowHTML "          <td><font size=""2""><b>Nome</font></td>"
          ShowHTML "          <td><font size=""2""><b>CPF</font></td>"
          ShowHTML "          <td><font size=""2""><b>Operações</font></td>"
          ShowHTML "        </tr>"
          If RS.EOF Then
              ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=3 align=""center""><font  size=""2""><b>Não foram encontrados nomes que contenham o texto informado.</b></td></tr>"
          Else
            While Not RS.EOF
              ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
              ShowHTML "        <td align=""left""><font  size=""1"">" & RS("nome") & "</td>"
              ShowHTML "        <td align=""center""><font  size=""1"">" & RS("cpf") & "</td>"
              ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
              ShowHTML "          <A class=""HL"" HREF=""" & w_dir & "pessoa.asp?par=BENEF&R=" & R & "&O=I&w_username=" & RS("cgccpf") & "&w_username=" & RS("cgccpf") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_data_inicio=" & p_data_inicio & "&p_data_fim=" & p_data_fim & "&p_solicitante=" & p_solicitante & "&p_numero=" & p_numero & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_nome=" & p_nome & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & """>Selecionar</A>&nbsp"
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
       End If
    Else
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
       ShowHTML "    <table width=""97%"" border=""0"">"
       ShowHTML "      <tr><td><table border=""0"" width=""100%"">"
       ShowHTML "			 <tr><td valign=""top""><font size=1>CPF:</font><br><b><font size=2>" & w_username
       ShowHTML "                   <INPUT type=""hidden"" name=""w_username"" value=""" & w_username & """>"
       ShowHTML "			 <tr><td valign=""top""><font size=""1""><b><u>N</u>ome completo:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""STI"" SIZE=""45"" MAXLENGTH=""60"" VALUE=""" & w_nome & """></td>"
       ShowHTML "                <td valign=""top""><font size=""1""><b><u>N</u>ome resumido:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome_resumido"" class=""STI"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_nome_resumido & """></td>"
       ShowHTML "          </table>"
       ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
       ShowHTML "              <td valign=""top""><font size=""1""><b>e-<u>M</u>ail:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_email"" class=""STI"" SIZE=""40"" MAXLENGTH=""50"" VALUE=""" & w_email & """></td>"
       ShowHTML "          </table>"
       If P3 = 1 Then ' Vide função do parâmetro no cabeçalho desta rotina
          ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
          ShowHTML "          <td valign=""top""><font size=""1""><b><u>R</u>egional de Ensino:</b><br><SELECT ACCESSKEY=""R"" CLASS=""STS"" NAME=""w_sq_unidade_lotacao"" " & w_Disabled & " onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_sq_localizacao'; document.Form.submit();"">"
          DB_GetUorgList RS, w_cliente, null, null, null, null, null
          If Nvl(Session("codigo"),"00") = "00" Then
             ' Recupera os dados da unidade maior da organização
             RS.Filter = "informal='N' and sq_unidade_pai = null"
             ShowHTML "          <option value=""" & RS("sq_unidade") & """>Todas"
             
             ' Recupera as unidades vinculadas para exibição na lista
             RS.Filter = "informal='N' and codigo <> '00'"
          Else
             RS.Filter = "informal='N' and codigo = '" & Session("codigo") & "'"
          End If
          RS.Sort = "codigo"
          While Not RS.EOF
             If cDbl(Nvl(w_sq_unidade_lotacao,0)) = cDbl(Nvl(RS("sq_unidade"),0)) Then
                ShowHTML "          <option value=""" & RS("sq_unidade") & """ selected>" & RS("nome")
             Else
                ShowHTML "          <option value=""" & RS("sq_unidade") & """>" & RS("nome")
             End If
             RS.MoveNext
          Wend
          ShowHTML "          </select>"
          SelecaoLocalizacao "Locali<u>z</u>ação:", "Z", null, w_sq_localizacao, Nvl(w_sq_unidade_lotacao,0), "w_sq_localizacao", null
          ShowHTML "          </table>"

          ShowHTML "      <tr>"
          SelecaoVinculo "<u>P</u>erfil:", "P", null, w_sq_tipo_vinculo, null, "w_sq_tipo_vinculo", "S", "Física", null
          ShowHTML "      </tr>"

          ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
          ShowHTML "          <tr><td valign=""top""><font size=""1""><b>Gestor segurança?</b><br>"
          If w_gestor_seguranca = "S" Then
             ShowHTML "              <input " & w_Disabled & " type=""RADIO"" name=""w_gestor_seguranca"" class=""STR"" VALUE=""S"" CHECKED> Sim<input " & w_Disabled & " type=""RADIO"" name=""w_gestor_seguranca"" class=""STR"" VALUE=""N""> Não</td>"
          Else
             ShowHTML "              <input " & w_Disabled & " type=""RADIO"" name=""w_gestor_seguranca"" class=""STR"" VALUE=""S""> Sim<input " & w_Disabled & " type=""RADIO"" name=""w_gestor_seguranca"" class=""STR"" VALUE=""N"" CHECKED> Não</td>"
          End If
          ShowHTML "              <td valign=""top""><font size=""1""><b>Gestor sistema?</b><br>"
          If w_gestor_sistema = "S" Then
             ShowHTML "              <input " & w_Disabled & " type=""RADIO"" name=""w_gestor_sistema"" class=""STR"" VALUE=""S"" CHECKED> Sim<input " & w_Disabled & " type=""RADIO"" name=""w_gestor_sistema"" class=""STR"" VALUE=""N""> Não</td>"
          Else
             ShowHTML "              <input " & w_Disabled & " type=""RADIO"" name=""w_gestor_sistema"" class=""STR"" VALUE=""S""> Sim<input " & w_Disabled & " type=""RADIO"" name=""w_gestor_sistema"" class=""STR"" VALUE=""N"" CHECKED> Não</td>"
          End If
          If O = "I" Then ' Se for inclusão de funcionário, pergunta se deseja enviar e-mail
             ShowHTML "          <tr><td valign=""top""><font size=""1""><input type=""checkbox"" name=""w_envia_mail"" class=""STC"" VALUE=""S"" CHECKED> Comunica ao usuário a criação do acesso</td>"
          Elseif O = "E" Then ' Se for remoção de funcionário, pergunta se deseja enviar e-mail
             ShowHTML "          <tr><td valign=""top""><font size=""1""><input type=""checkbox"" name=""w_envia_mail"" class=""STC"" VALUE=""S"" CHECKED> Comunica ao usuário sua exclusão</td>"
          Elseif O = "T" Then ' Se for remoção de funcionário, pergunta se deseja enviar e-mail
             ShowHTML "          <tr><td valign=""top""><font size=""1""><input type=""checkbox"" name=""w_envia_mail"" class=""STC"" VALUE=""S"" CHECKED> Comunica ao usuário a ativação do seu acesso</td>"
          Elseif O = "D" Then ' Se for remoção de funcionário, pergunta se deseja enviar e-mail
             ShowHTML "          <tr><td valign=""top""><font size=""1""><input type=""checkbox"" name=""w_envia_mail"" class=""STC"" VALUE=""S"" CHECKED> Comunica ao usuário o bloqueio do seu acesso</td>"
          End If
          ShowHTML "          </table>"
          ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
       End If
    
       ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"

       ' Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
       ShowHTML "      <tr><td align=""center"" colspan=""3"">"
       If O = "E" Then
          ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
          ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & R & "&w_cliente=" & Request("w_cliente") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_data_inicio=" & p_data_inicio & "&p_data_fim=" & p_data_fim & "&p_solicitante=" & p_solicitante & "&p_numero=" & p_numero & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_nome=" & p_nome & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & "';"" name=""Botao"" value=""Cancelar"">"
       ElseIf O = "T" Then
          ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Bloquear Acesso"" onClick=""return(confirm('Confirma a ativação do acesso ao sistema para este usuário?'));"">"
          ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & R & "&w_cliente=" & Request("w_cliente") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_data_inicio=" & p_data_inicio & "&p_data_fim=" & p_data_fim & "&p_solicitante=" & p_solicitante & "&p_numero=" & p_numero & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_nome=" & p_nome & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & "';"" name=""Botao"" value=""Cancelar"">"
       ElseIf O = "D" Then
          ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Bloquear Acesso"" onClick=""return(confirm('Confirma bloqueio do acesso ao sistema para este usuário?'));"">"
          ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & R & "&w_cliente=" & Request("w_cliente") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_data_inicio=" & p_data_inicio & "&p_data_fim=" & p_data_fim & "&p_solicitante=" & p_solicitante & "&p_numero=" & p_numero & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_nome=" & p_nome & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & "';"" name=""Botao"" value=""Cancelar"">"
       Else
          ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
          ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & R & "&w_cliente=" & Request("w_cliente") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_data_inicio=" & p_data_inicio & "&p_data_fim=" & p_data_fim & "&p_solicitante=" & p_solicitante & "&p_numero=" & p_numero & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_nome=" & p_nome & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & "';"" name=""Botao"" value=""Cancelar"">"
       End If
       ShowHTML "          </td>"
       ShowHTML "      </tr>"
       ShowHTML "    </table>"
       ShowHTML "    </TD>"
       ShowHTML "</tr>"
    End If
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

  Set w_gestor_seguranca      = Nothing
  Set w_gestor_sistema        = Nothing
  Set w_readonly              = Nothing
  Set w_projeto               = Nothing
  Set w_saldo_ferias          = Nothing
  Set w_limite_emprestimo     = Nothing
  Set w_sq_tipo_vinculo       = Nothing
  Set w_sq_unidade_lotacao    = Nothing
  Set w_sq_localizacao        = Nothing
  Set w_beneficiario          = Nothing 
  Set w_frm_pag               = Nothing 
  Set w_nome                  = Nothing 
  Set w_nome_resumido         = Nothing 
  Set w_rg                    = Nothing 
  Set w_username              = Nothing 
  Set w_passaporte            = Nothing 
  Set w_nascimento            = Nothing
  Set w_end                   = Nothing 
  Set w_comple                = Nothing 
  Set w_cidade                = Nothing 
  Set w_uf                    = Nothing 
  Set w_cep                   = Nothing 
  Set w_pais                  = Nothing 
  Set w_telefone              = Nothing 
  Set w_fax                   = Nothing 
  Set w_email                 = Nothing
  Set w_nrobanco              = Nothing 
  Set w_nroagencia            = Nothing 
  Set w_operacao              = Nothing 
  Set w_contacorrente         = Nothing

  Set i                       = Nothing
  Set w_troca                 = Nothing
  Set w_finalidade            = Nothing
  Set w_erro                  = Nothing
  Set w_sq_solicitacao_vinc   = Nothing
  
  Set w_cor                   = Nothing
  Set w_sq_solicitacao        = Nothing
  Set w_sq_servico            = Nothing
  Set w_sq_situacao_servico   = Nothing
  Set w_descricao             = Nothing
  Set w_data_inclusao         = Nothing
  Set w_data_programada       = Nothing
  Set w_data_programada_fim   = Nothing
  Set w_pessoa_autorizada     = Nothing
  Set w_sq_pessoa             = Nothing
  Set w_motivo                = Nothing
  Set w_username_solicitante  = Nothing
  Set w_nome_solicitante      = Nothing
  Set p_localizacao           = Nothing
  Set p_lotacao               = Nothing
  Set p_gestor                = Nothing
  Set p_nome                  = Nothing
  Set p_ordena                = Nothing
  Set p_numero                = Nothing
  Set p_data_inicio           = Nothing
  Set p_data_fim              = Nothing
  Set p_solicitante           = Nothing

End Sub
REM =========================================================================
REM Fim da tela de beneficiário
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava

  Dim w_proximo, w_handle, w_tipo, w_documento
  Dim w_Null
  Dim w_solicitacao
  Dim w_ordem
  Dim w_servico
  Dim I

  Dim w_handle_to
  Dim w_sequencial_to
  Dim w_ano_sequencial
  
  Cabecalho
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao	
  ' Verifica se a Assinatura Eletrônica é válida
  If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
     w_assinatura = "" Then

     w_tipo = "Física"

     DML_PutSiwUsuario O, _
        Request("w_sq_pessoa"), Request("w_cliente"), Request("w_Nome"), Request("w_Nome_Resumido"), _
        Request("w_sq_tipo_vinculo"), w_tipo, Request("w_sq_unidade_lotacao"), Request("w_sq_localizacao"), _
        Request("w_username"), Request("w_email"), Request("w_gestor_seguranca"), Request("w_gestor_sistema")

     DB_GetLinkData RS, Session("p_cliente"), SG
     ScriptOpen "JavaScript"
     If Request("w_sq_pessoa") = "" Then
        ShowHTML "  alert('Usuário criado. Senha e assinatura eletrônica igual à username'); "
     End If
     ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_cliente=" & Request("w_cliente") & "&w_sq_solicitacao=" & Request("w_sq_solicitacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_data_inicio=" & Request("p_data_inicio") & "&p_data_fim=" & Request("p_data_fim") & "&p_solicitante=" & Request("p_solicitante") & "&p_numero=" & Request("p_numero") & "&p_localizacao=" & Request("p_localizacao") & "&p_lotacao=" & Request("p_lotacao") & "&p_nome=" & Request("p_nome") & "&p_gestor=" & Request("p_gestor") & "&p_ordena=" & Request("p_ordena") & "';"
     DesconectaBD
     ScriptClose
  Else
     ScriptOpen "JavaScript"
     ShowHTML "  alert('Assinatura Eletrônica inválida!');"
     ShowHTML "  history.back(1);"
     ScriptClose
  End If

  Set w_documento       = Nothing
  Set w_tipo            = Nothing
  Set w_handle          = Nothing
  Set w_proximo         = Nothing
  Set w_ano_sequencial  = Nothing
  Set w_sequencial_to   = Nothing
  Set w_handle_to       = Nothing

  Set I                 = Nothing
  Set w_servico         = Nothing
  Set w_ordem           = Nothing
  Set w_solicitacao     = Nothing
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
  If (len(Session("LOTACAO")&"") = 0 or len(Session("LOCALIZACAO")&"") = 0) and Session("LogOn") = "Sim" Then
    ScriptOpen "JavaScript"
    ShowHTML " alert('Você não tem lotação ou localização definida. Entre em contato com o RH!'); "
    ShowHTML " top.location.href='Default.asp'; "
    ScriptClose
   Exit Sub
  End If

  Select Case Par
    Case "BENEF"
       Benef
    Case "GRAVA"
       Grava
    Case Else
       Cabecalho
       BodyOpen "onLoad=document.focus();"
       ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
       ShowHTML "<HR>"
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Opção não disponível.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
       Rodape
  End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>

