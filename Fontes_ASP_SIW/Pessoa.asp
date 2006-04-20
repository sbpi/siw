<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="jScript.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DML_Seguranca.asp" -->
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
Dim R, O, w_Cont, w_Pagina, w_Disabled, w_TP, w_cliente, w_usuario
Dim w_Assinatura
Dim w_dir, w_dir_volta, w_submenu
Public w_Data_Banco
Private Par
Set RS  = Server.CreateObject("ADODB.RecordSet")

AbreSessao

' Carrega variáveis locais com os dados dos parâmetros recebidos
Par          = ucase(Request("Par"))
P1           = nvl(Request("P1"),0)
P2           = nvl(Request("P2"),0)
P3           = nvl(Request("P3"),0)
P4           = nvl(Request("P4"),0)
TP           = Request("TP")
SG           = ucase(Request("SG"))
R            = uCase(Request("R"))
O            = uCase(Request("O"))
w_Assinatura = uCase(Request("w_Assinatura"))
w_Pagina     = "pessoa.asp?par="
w_Disabled   = " ENABLED "

If (par="DESPESA" or par="TRECHO" or par="VISUAL") and O = "A" and Request("w_Handle") = "" Then O = "L" End If ' Configura o valor de O se for a tela de listagem
  
Select Case O
  Case "I" 
     If SG="SGUSU" or SG="CLUSUARIO" Then
        w_TP = TP & " - Novo Acesso"
     ElseIf SG="RHUSU" Then
        w_TP = TP & " - Nova Pessoa"
     Else
        w_TP = TP & " - Inclusão"
     End If
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
     If par="BUSCAUSUARIO" Then
        w_TP = TP & " - Busca usuário"
     Else
        w_TP = TP & " - Listagem"
     End If
End Select

w_data_banco = Date()

' Se for acesso do módulo de gerenciamento de clientes do SIW, o cliente será determinado por parâmetro;
' caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
w_cliente         = RetornaCliente()
w_usuario         = RetornaUsuario()
  
Main

FechaSessao

Set w_dir        = Nothing
Set w_dir_volta  = Nothing
Set w_usuario    = Nothing
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
     w_username              = Request("w_username")
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
     
     If O = "I" and w_sq_pessoa = "" and w_username > "" and SG = "SGUSU" Then
        DB_GetUserData rs, w_cliente, w_username
        If RS.RecordCount > 0 Then 
           ScriptOpen "JavaScript"
           ShowHTML "  alert('Usuário já existente!');"
           ShowHTML "  history.back(1);"
           ScriptClose
           Exit Sub
        End If
     End If
     
     If InStr("IATDEV",O) > 0 and w_sq_pessoa > "" Then
        ' Recupera os dados do beneficiário em co_pessoa
        DB_GetPersonData RS, w_cliente, w_sq_pessoa, null, null
        If RS.RecordCount > 0 Then 
           w_nome               = RS("Nome")
           w_nome_resumido      = RS("Nome_Resumido")
           w_email              = RS("Email")
           w_sq_unidade_lotacao = RS("sq_unidade")
           w_sq_localizacao     = RS("sq_localizacao")
           w_sq_tipo_vinculo    = RS("sq_tipo_vinculo")
           w_gestor_seguranca   = RS("gestor_seguranca")
           w_gestor_sistema     = RS("gestor_sistema")
        End If
        DesconectaBD

     End If
     
     ' O bloco abaixo recupera os dados bancários e a forma de pagamento,
     ' dependendo do valor de P1 e se não for inclusão
     ' O local onde os dados bancários e a forma de pagamento serão recuperados
     ' depende do tipo de documento.
     If O <> "I" and (P2 = 1 or P2 = 2) Then ' Vide finalidade do parâmetro no cabeçalho da rotina

     End If
     
  End If  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
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
     Validate "w_username", "CPF", "CPF", "1", "14", "14", "", "0123456789-."
     If P2 = 2 Then
        Validate "w_frm_pag", "Forma de pagamento", "SELECT", "1", "1", "10", "", "1"
     End If
     ShowHTML "}"
  ElseIf O = "I" or O = "A" Then
     ShowHTML "  if (theForm.Botao.value == ""Troca"") { return true; }"
     'If v_Desabilita = "" or v_Desabilita <> 1 Then
     '   Validate "p_Nome", "Nome", "1", 1, 5, 80, "1", "1"
     'End If
     Validate "w_nome", "Nome", "1", 1, 5, 60, "1", "1"
     Validate "w_nome_resumido", "Nome resumido", "1", 1, 2, 15, "1", "1"
     If SG = "RHUSU" Then
        If Len(w_username) <> 10 then
           Validate "w_rg", "RG", "1", 1, 5, 80, "1", "1"
           Validate "w_passaporte", "Passaporte", "1", "", 1, 15, "1", "1"
        Else
           Validate "w_passaporte", "Passaporte", "1", 1, 1, 15, "1", "1"
        End If
        Validate "w_nascimento", "Data de Nascimento", "DATA", 1, 10, 10, "", 1
        Validate "w_end", "Endereço", "1", 1, 4, 50, "1", "1"
        Validate "w_pais", "País", "SELECT", 1, 1, 10, "1", "1"
        Validate "w_uf", "UF", "SELECT", 1, 1, 10, "1", "1"
        Validate "w_cidade", "Cidade", "SELECT", 1, 1, 10, "", "1"
        If w_pais = "" or w_pais = 1 then
           Validate "w_cep", "CEP", "1", "", 1, 10, "", "1"
        Else
           Validate "w_cep", "CEP", "1", 1, 6, 10, "", "1"
        End If
        Validate "w_telefone", "Telefone", "1", 1, 7, 40, "1", "1"
        Validate "w_fax", "Fax", "1", "", 4, 20, "1", "1"
     ElseIf SG = "SGUSU" or SG = "CLUSUARIO" Then
        Validate "w_email", "E-Mail", "1", "1", 4, 50, "1", "1"
     End If
     Validate "w_sq_unidade_lotacao", "Unidade de lotação", "HIDDEN", 1, 1, 10, "", "1"
     Validate "w_sq_localizacao", "Localização", "SELECT", 1, 1, 10, "", "1"
     Validate "w_sq_tipo_vinculo", "Vínculo com a organização", "SELECT", 1, 1, 10, "", "1"
     If SG="SGUSU" or SG="CLUSUARIO" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     End If
  ElseIf O = "E" or O = "T" or O = "D" Then
     Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  If P1 <> 0 and (w_username = "" or Instr(Request("botao"),"Troca") > 0 or Instr(Request("botao"),"Procurar") > 0) Then ' Se o beneficiário ainda não foi selecionado
     If Instr(Request("botao"),"Procurar") > 0 Then ' Se está sendo feita busca por nome
        If w_troca <> "w_sq_localizacao" Then BodyOpen "onLoad='document.focus()';" End If
     Else
        BodyOpen "onLoad='document.Form.w_username.focus()';"
     End If
  ElseIf w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf Instr("ETDV",O) > 0 Then
     BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_nome.focus()';"
  End If
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If Instr("IAETDV",O) > 0 Then
    If InStr("ETDV",O) Then
       w_Disabled = " DISABLED "
       If O = "V" Then
          w_Erro = Validacao(w_sq_solicitacao, sg)
       End If
    End If
    If w_username = "" or Instr(Request("botao"),"Troca") > 0 or Instr(Request("botao"),"Procurar") > 0 Then ' Se o beneficiário ainda não foi selecionado
       ShowHTML "<FORM action=""" & w_Pagina & par & """ method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"">"
    Else
       ShowHTML "<FORM action=""" & w_Pagina & "Grava"" method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"">"
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
       ShowHTML "        <tr><td><font size=1><b><u>C</u>PF:<br><INPUT ACCESSKEY=""C"" TYPE=""text"" Class=""sti"" NAME=""w_username"" VALUE=""" & w_username & """ SIZE=""14"" MaxLength=""14"" onKeyDown=""FormataCPF(this, event);"">"
       ShowHTML "            <td valign=""bottom""><INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Selecionar"">"
       If SG = "SGUSU" or SG = "RHUSU" or SG = "CLUSUARIO" Then ' Tela de usuários do SG ou RH
          ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & R & "&w_cliente=" & Request("w_cliente") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_data_inicio=" & p_data_inicio & "&p_data_fim=" & p_data_fim & "&p_solicitante=" & p_solicitante & "&p_numero=" & p_numero & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_nome=" & p_nome & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & "';"" name=""Botao"" value=""Cancelar"">"
       End If
       ShowHTML "        <tr><td colspan=3><p>&nbsp</p>"
       ShowHTML "        <tr><td colspan=3 heigth=1 bgcolor=""#000000"">"
       ShowHTML "        <tr><td colspan=3>"
       ShowHTML "             <font size=1><b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY=""P"" TYPE=""text"" Class=""sti"" NAME=""w_nome"" VALUE=""" & w_nome & """ SIZE=""20"" MaxLength=""20"">"
       ShowHTML "              <INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Procurar"" onClick=""Botao.value=this.value; document.Form.action='" & w_Pagina & par &"'"">"
       ShowHTML "      </table>"
       If Request("w_nome") > "" Then
          DB_GetPersonList RS, w_cliente, null, "NOVOUSO", Request("w_nome"), null, null, null
          ShowHTML "<tr><td align=""center"" colspan=3>"
          ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
          ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
          ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
          ShowHTML "          <td><font size=""1""><b>Nome resumido</font></td>"
          ShowHTML "          <td><font size=""1""><b>CPF</font></td>"
          ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
          ShowHTML "        </tr>"
          If RS.EOF Then
              ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=4 align=""center""><font  size=""2""><b>Não há pessoas (não usuárias) que contenham o texto informado.</b></td></tr>"
          Else
            While Not RS.EOF
              ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
              ShowHTML "        <td><font  size=""1"">" & RS("nome") & "</td>"
              ShowHTML "        <td><font  size=""1"">" & RS("nome_resumido") & "</td>"
              ShowHTML "        <td align=""center""><font  size=""1"">" & Nvl(RS("cpf"),"---") & "</td>"
              ShowHTML "        <td nowrap><font size=""1"">"
              ShowHTML "          <A class=""hl"" HREF=""pessoa.asp?par=BENEF&R=" & R & "&O=I&w_username=" & RS("cpf") & "&w_sq_pessoa=" & RS("sq_pessoa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_data_inicio=" & p_data_inicio & "&p_data_fim=" & p_data_fim & "&p_solicitante=" & p_solicitante & "&p_numero=" & p_numero & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_nome=" & p_nome & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & """>Selecionar</A>&nbsp"
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
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
       ShowHTML "    <table width=""97%"" border=""0"">"
       ShowHTML "      <tr><td><table border=""0"" width=""100%"">"
       ShowHTML "			 <tr><td valign=""top""><font size=1>CPF:</font><br><b><font size=2>" & w_username
       ShowHTML "                   <INPUT type=""hidden"" name=""w_username"" value=""" & w_username & """>"
       ShowHTML "			 <tr><td valign=""top""><font size=""1""><b><u>N</u>ome completo:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""45"" MAXLENGTH=""60"" VALUE=""" & w_nome & """></td>"
       ShowHTML "                <td valign=""top""><font size=""1""><b><u>N</u>ome resumido:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome_resumido"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_nome_resumido & """></td>"
       ShowHTML "          </table>"
       If SG = "RHUSU" Then
          ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
          ShowHTML "          <tr><td valign=""top""><font size=""1""><b><u>I</u>dentidade:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_rg"" class=""sti"" SIZE=""14"" MAXLENGTH=""80"" VALUE=""" & w_rg & """></td>"
          ShowHTML "              <td valign=""top""><font size=""1""><b>Passapo<u>r</u>te:</b><br><input " & w_Disabled & " accesskey=""R"" type=""text"" name=""w_passaporte"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_passaporte & """></td>"
          ShowHTML "              <td valign=""top""><font size=""1""><b>Da<u>t</u>a de nascimento:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_nascimento"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_nascimento & """ onKeyDown=""FormataData(this,event);""></td>"
          ShowHTML "          </table>"
          ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
          ShowHTML "          <tr><td valign=""top""><font size=""1""><b>En<u>d</u>ereço:</b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_end"" class=""sti"" SIZE=""35"" MAXLENGTH=""50"" VALUE=""" & w_end & """></td>"
          ShowHTML "              <td valign=""top""><font size=""1""><b>C<u>o</u>mplemento:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_comple"" class=""sti"" SIZE=""30"" MAXLENGTH=""50"" VALUE=""" & w_comple & """></td>"
          ShowHTML "          </table>"
          ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
          ShowHTML "      <tr>"
          SelecaoPais "<u>P</u>aís:", "P", null, w_pais, null, "w_pais", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.w_troca.value='w_uf'; document.Form.submit();"""
          SelecaoEstado "E<u>s</u>tado:", "S", null, w_uf, w_pais, "N", "w_uf", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.w_troca.value='w_cidade'; document.Form.submit();"""
          SelecaoCidade "<u>C</u>idade:", "C", null, w_cidade, w_pais, w_uf, "w_cidade", null, null
          ShowHTML "          </table>"
          ShowHTML "          <tr><td valign=""top""><font size=""1""><b>C<u>E</u>P:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_cep"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_cep & """ onKeyDown=""FormataCEP(this,event);""></td>"
          ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
          ShowHTML "          <tr><td valign=""top""><font size=""1""><b>Te<u>l</u>efone:</b><br><input " & w_Disabled & " accesskey=""L"" type=""text"" name=""w_telefone"" class=""sti"" SIZE=""20"" MAXLENGTH=""40"" VALUE=""" & w_telefone & """></td>"
          ShowHTML "              <td valign=""top""><font size=""1""><b>Fa<u>x</u>:</b><br><input " & w_Disabled & " accesskey=""X"" type=""text"" name=""w_fax"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_fax & """></td>"
          If w_disabled = " DISABLED " Then
             ShowHTML "              <td valign=""top""><font size=""1""><b>e-<u>M</u>ail:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_email1"" class=""sti"" SIZE=""40"" MAXLENGTH=""50"" VALUE=""" & w_email & """></td>"
             ShowHTML "                   <INPUT type=""hidden"" name=""w_email"" value=""" & w_email & """>"
          Else
             ShowHTML "              <td valign=""top""><font size=""1""><b>e-<u>M</u>ail:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_email"" class=""sti"" SIZE=""40"" MAXLENGTH=""50"" VALUE=""" & w_email & """></td>"
          End If
          ShowHTML "          </table>"
       ElseIf SG = "SGUSU" or SG = "CLUSUARIO" Then
          If w_disabled = " DISABLED " Then
             ShowHTML "          <tr><td valign=""top""><font size=""1""><b>e-<u>M</u>ail:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_email1"" class=""sti"" SIZE=""40"" MAXLENGTH=""50"" VALUE=""" & w_email & """></td>"
             ShowHTML "                   <INPUT type=""hidden"" name=""w_email"" value=""" & w_email & """>"
          Else
             ShowHTML "          <tr><td valign=""top""><font size=""1""><b>e-<u>M</u>ail:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_email"" class=""sti"" SIZE=""40"" MAXLENGTH=""50"" VALUE=""" & w_email & """></td>"
          End If
       End If
       ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
       ShowHTML "          <tr>"
       SelecaoUnidade "<U>U</U>nidade de lotação:", "U", null, w_sq_unidade_lotacao, null, "w_sq_unidade_lotacao", null, "onBlur=""document.Form.action='" & w_pagina & par & "'; document.Form.w_troca.value='w_sq_localizacao'; document.Form.submit();"""
       ShowHTML "          <tr>"
       SelecaoLocalizacao "Locali<u>z</u>ação:", "Z", null, w_sq_localizacao, Nvl(w_sq_unidade_lotacao,0), "w_sq_localizacao", null
       ShowHTML "          </table>"

       ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
       ShowHTML "      <tr>"
       If SG = "RHUSU" Then
          SelecaoVinculo "<u>M</u>odalidade de contratação:", "M", null, w_sq_tipo_vinculo, null, "w_sq_tipo_vinculo", "S", "Física", "S"
       Else
          SelecaoVinculo "<u>V</u>ínculo com a organização:", "V", null, w_sq_tipo_vinculo, null, "w_sq_tipo_vinculo", "S", "Física", null
       End If
       ShowHTML "      </tr>"
       ShowHTML "          </table>"

       If SG = "RHUSU" Then ' Tela de usuários do RH
          If O = "A" Then w_readonly = "READONLY" End If ' Se for alteração, bloqueia a edição dos campos
          ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
          ShowHTML "          <tr><td valign=""top""><font size=""1""><b>Da<u>t</u>a de entrada:</b><br><input " & w_Disabled & " " & w_readonly & " accesskey=""T"" type=""text"" name=""w_entrada"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_entrada & """ onKeyDown=""FormataData(this,event);""></td>"
          ShowHTML "              <td valign=""top""><font size=""1""><b><u>L</u>imite para empréstimo:</b><br><input " & w_Disabled & " " & w_readonly & " accesskey=""L"" type=""text"" name=""w_limite_emprestimo"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_limite_emprestimo & """ onKeyDown=""FormataValor(this,11,2,event)""></td>"
          ShowHTML "              <td valign=""top""><font size=""1""><b>Saldo de <u>f</u>érias:</b><br><input " & w_Disabled & " " & w_readonly & " accesskey=""F"" type=""text"" name=""w_saldo_ferias"" class=""sti"" SIZE=""5"" MAXLENGTH=""5"" VALUE=""" & w_saldo_ferias & """ onKeyDown=""FormataValor(this,6,1,event)""></td>"
          If O = "I" Then ' Se for inclusão de funcionário, pergunta se deseja enviar e-mail
             ShowHTML "          <tr><td valign=""top""><font size=""1""><input type=""checkbox"" name=""w_envia_mail"" class=""STC"" VALUE=""S"" CHECKED> Enviar mensagem comunicando admissão de novo funcionário.</td>"
          Elseif O = "E" Then ' Se for remoção de funcionário, pergunta se deseja enviar e-mail
             ShowHTML "          <tr><td valign=""top""><font size=""1""><input type=""checkbox"" name=""w_envia_mail"" class=""STC"" VALUE=""S"" CHECKED> Enviar mensagem comunicando rescisão do contrato de funcionário.</td>"
          End If
          ShowHTML "          </table>"
       ElseIf SG = "SGUSU" or SG="CLUSUARIO" Then ' Tela de cadastramento de usuários
          ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
          ShowHTML "          <tr><td valign=""top""><font size=""1""><b>Gestor segurança?</b><br>"
          If w_gestor_seguranca = "S" Then
             ShowHTML "              <input " & w_Disabled & " type=""RADIO"" name=""w_gestor_seguranca"" class=""str"" VALUE=""S"" CHECKED> Sim<input " & w_Disabled & " type=""RADIO"" name=""w_gestor_seguranca"" class=""str"" VALUE=""N""> Não</td>"
          Else
             ShowHTML "              <input " & w_Disabled & " type=""RADIO"" name=""w_gestor_seguranca"" class=""str"" VALUE=""S""> Sim<input " & w_Disabled & " type=""RADIO"" name=""w_gestor_seguranca"" class=""str"" VALUE=""N"" CHECKED> Não</td>"
          End If
          ShowHTML "              <td valign=""top""><font size=""1""><b>Gestor sistema?</b><br>"
          If w_gestor_sistema = "S" Then
             ShowHTML "              <input " & w_Disabled & " type=""RADIO"" name=""w_gestor_sistema"" class=""str"" VALUE=""S"" CHECKED> Sim<input " & w_Disabled & " type=""RADIO"" name=""w_gestor_sistema"" class=""str"" VALUE=""N""> Não</td>"
          Else
             ShowHTML "              <input " & w_Disabled & " type=""RADIO"" name=""w_gestor_sistema"" class=""str"" VALUE=""S""> Sim<input " & w_Disabled & " type=""RADIO"" name=""w_gestor_sistema"" class=""str"" VALUE=""N"" CHECKED> Não</td>"
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
       End If
       If SG = "RHUSU" or SG="SGUSU" or SG="CLUSUARIO" Then ' Tela de usuários do RH e do SG
          ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
       End If
    
       ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"

       ' Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
       ShowHTML "      <tr><td align=""center"" colspan=""3"">"
       If O = "E" Then
          ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
          ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & R & "&w_cliente=" & Request("w_cliente") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_data_inicio=" & p_data_inicio & "&p_data_fim=" & p_data_fim & "&p_solicitante=" & p_solicitante & "&p_numero=" & p_numero & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_nome=" & p_nome & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & "';"" name=""Botao"" value=""Cancelar"">"
       ElseIf O = "T" Then
          ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Desbloquear Acesso"" onClick=""return(confirm('Confirma a ativação do acesso ao sistema para este usuário?'));"">"
          ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & R & "&w_cliente=" & Request("w_cliente") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_data_inicio=" & p_data_inicio & "&p_data_fim=" & p_data_fim & "&p_solicitante=" & p_solicitante & "&p_numero=" & p_numero & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_nome=" & p_nome & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & "';"" name=""Botao"" value=""Cancelar"">"
       ElseIf O = "D" Then
          If SG = "SGUSU" OR SG="CLUSUARIO" Then ' Tela de usuários do SG
             ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Bloquear Acesso"" onClick=""return(confirm('Confirma bloqueio do acesso ao sistema para este usuário?'));"">"
          ElseIf SG = "RHUSU" Then ' Tela de usuários do RH
             ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Remover do quadro"" onClick=""return(confirm('Confirma remoção do quadro de funcionários e bloqueio do acesso ao sistema para esta pessoa?'));"">"
          Else
             ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
          End If
          ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & R & "&w_cliente=" & Request("w_cliente") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_data_inicio=" & p_data_inicio & "&p_data_fim=" & p_data_fim & "&p_solicitante=" & p_solicitante & "&p_numero=" & p_numero & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_nome=" & p_nome & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & "';"" name=""Botao"" value=""Cancelar"">"
       Else
          ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
          ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & R & "&w_cliente=" & Request("w_cliente") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_data_inicio=" & p_data_inicio & "&p_data_fim=" & p_data_fim & "&p_solicitante=" & p_solicitante & "&p_numero=" & p_numero & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_nome=" & p_nome & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & "';"" name=""Botao"" value=""Cancelar"">"
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
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
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
REM Rotina de busca dos usuários
REM -------------------------------------------------------------------------
Sub BuscaUsuario
 
  Dim w_nome, w_sg_unidade, w_cliente, ChaveAux, restricao, campo, w_cor
  
  w_nome       = UCase(Request("w_nome"))
  w_sg_unidade = UCase(Request("w_sg_unidade"))
  w_cliente    = Request("w_cliente")
  ChaveAux     = Request("ChaveAux")
  restricao    = Request("restricao")
  campo        = Request("campo")
  
  DB_GetPersonList RS, Session("p_cliente"), ChaveAux, restricao, w_nome, w_sg_unidade, null, null
    
  Cabecalho
  ShowHTML "<TITLE>Seleção de pessoa</TITLE>"
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  ScriptOpen "JavaScript"
  ShowHTML "  function volta(l_chave) {"
  ShowHTML "     opener.Form." & campo & ".value=l_chave;"
  ShowHTML "     opener.Form." & campo & ".focus();"
  ShowHTML "     window.close();"
  ShowHTML "     opener.focus();"
  ShowHTML "   }"
  ValidateOpen "Validacao"
  Validate "w_nome", "Nome", "1", "", "4", "100", "1", "1"
  Validate "w_sg_unidade", "Sigla da unidade de lotação", "1", "", "2", "20", "1", "1"
  ShowHTML "  if (theForm.w_nome.value == '' && theForm.w_sg_unidade.value == '') {"
  ShowHTML "     alert ('Informe um valor para o nome ou para a sigla da unidade!');"
  ShowHTML "     theForm.w_nome.focus();"
  ShowHTML "     return false;"
  ShowHTML "  }"
  ShowHTML "  theForm.Botao[0].disabled=true;"
  ShowHTML "  theForm.Botao[1].disabled=true;"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""//" & Request.ServerVariables("server_name") & "/siw/"">"
  BodyOpen "onLoad='document.Form.w_nome.focus();'"
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
  ShowHTML "    <table width=""100%"" border=""0"">"
  AbreForm  "Form", w_dir&w_Pagina&"BuscaUsuario", "POST", "return(Validacao(this))", null, P1, P2, P3, P4, TP, SG, null, null
  ShowHTML "<INPUT type=""hidden"" name=""restricao"" value=""" & restricao &""">"
  ShowHTML "<INPUT type=""hidden"" name=""campo"" value=""" & campo &""">"
  
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2><b><ul>Instruções</b>:<li>Informe parte do nome da ação ou o código da ação.<li>Quando a relação for exibida, selecione a ação desejada clicando sobre o link <i>Selecionar</i>.<li>Após informar o nome da ação ou o código da ação, clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Cancelar</i>, a procura é cancelada.</ul></div>"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
  ShowHTML "    <table width=""100%"" border=""0"">"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Parte do <U>n</U>ome da pessoa:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_nome"" size=""50"" maxlength=""100"" value=""" & w_nome & """>"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>S</U>igla da unidade de lotação:<br><INPUT ACCESSKEY=""S"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_sg_unidade"" size=""6"" maxlength=""20"" value=""" & w_sg_unidade & """>"
  
  ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td align=""center"" colspan=""3"">"
  ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
  ShowHTML "            <input class=""stb"" type=""button"" name=""Botao"" value=""Cancelar"" onClick=""window.close(); opener.focus();"">"
  ShowHTML "          </td>"
  ShowHTML "      </tr>"
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</form>"
  If w_nome > "" or w_sg_unidade > "" Then
     ShowHTML "<tr><td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
     ShowHTML "<tr><td>"
     ShowHTML "    <TABLE WIDTH=""100%"" border=0>"
     If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
     Else
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td>"
        ShowHTML "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        ShowHTML "            <td><font size=""1""><b>Nome resumido</font></td>"
        ShowHTML "            <td><font size=""1""><b>Nome</font></td>"
        ShowHTML "            <td><font size=""1""><b>Lotação</font></td>"
        ShowHTML "            <td><font size=""1""><b>Operações</font></td>"
        ShowHTML "          </tr>"
        While Not RS.EOF
           If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
           ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
           ShowHTML "            <td><font size=""1"">" & RS("nome_resumido") & "</td>"
           ShowHTML "            <td><font size=""1"">" & RS("nome") & "</td>"
           ShowHTML "            <td><font size=""1"">" & RS("sg_unidade") & " (" & RS("nm_local") & ")</td>"
           ShowHTML "            <td><font size=""1""><a class=""ss"" href=""#"" onClick=""javascript:volta('" & RS("sq_pessoa") & "');"">Selecionar</a>"
           RS.MoveNext
        wend
        ShowHTML "        </table></tr>"
        ShowHTML "      </center>"
        ShowHTML "    </table>"
        ShowHTML "  </td>"
        ShowHTML "</tr>"
     End If
     DesConectaBD	 
  End If
  DesConectaBD	 
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"  
  ShowHTML "</table>"
  ShowHTML "</center>"
  Estrutura_Texto_Fecha

  Set w_nome                = Nothing
  Set w_sg_unidade          = Nothing
      
End Sub
REM =========================================================================
REM Fim da rotina de busca de usuários
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava

  Dim w_proximo, w_handle, w_tipo, w_documento
  Dim w_Null
  Dim w_solicitacao
  Dim w_ordem
  Dim w_servico, w_html, w_resultado
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

     If SG = "SGUSU" or SG = "CLUSUARIO" Then
        ' Identifica, a partir do tamanho da variável w_username, se é pessoa física, jurídica ou estrangeiro
        If Len(Request("w_username")) <= 14 Then
           w_tipo = "Física"
        Else
           w_tipo = "Jurídica"
        End If

        DML_PutSiwUsuario O, _
           Request("w_sq_pessoa"), Request("w_cliente"), Request("w_Nome"), Request("w_Nome_Resumido"), _
           Request("w_sq_tipo_vinculo"), w_tipo, Request("w_sq_unidade_lotacao"), Request("w_sq_localizacao"), _
           Request("w_username"), Request("w_email"), Request("w_gestor_seguranca"), Request("w_gestor_sistema")
         
        ' Se o usuário deseja comunicar a ocorrência ao usuário, configura e envia mensagem automática.
        If Request("w_envia_mail") > "" Then
            ' Configuração do texto da mensagem
            w_html = "<HTML>" & VbCrLf
            w_html = w_html & BodyOpenMail(null) & VbCrLf
            w_html = w_html & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">" & VbCrLf
            w_html = w_html & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">" & VbCrLf
            w_html = w_html & "    <table width=""97%"" border=""0"">" & VbCrLf
            w_html = w_html & "      <tr valign=""top""><td><font size=2><b><font color=""#BC3131"">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></tr>" & VbCrLf
            If Instr("IT",O) > 0 Then
               If O = "I" Then
                  w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>CRIAÇÃO DE USUÁRIO</b></font><br><br><td></tr>" & VbCrLf
               ElseIf O = "T" Then
                  w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>DESBLOQUEIO DE USUÁRIO</b></font><br><br><td></tr>" & VbCrLf
               End If
               w_html = w_html & "      <tr valign=""top""><td><font size=2>" & VbCrLf
               If O = "I" Then
                  w_html = w_html & "         Sua senha e assinatura eletrônica para acesso ao sistema foram criadas. Utilize os dados informados abaixo:<br>" & VbCrLf
               ElseIf O = "T" Then
                  w_html = w_html & "         Sua senha e assinatura eletrônica para acesso ao sistema foram desbloqueadas. Utilize os dados informados abaixo:<br>" & VbCrLf
               End If
               w_html = w_html & "         <ul>" & VbCrLf
               DB_GetCustomerSite RS, w_cliente
               w_html = w_html & "         <li>Endereço de acesso ao sistema: <b><a class=""ss"" href=""" & RS("logradouro") & """ target=""_blank"">" & RS("Logradouro") & "</a></b></li>" & VbCrLf
               DesconectaBD
               w_html = w_html & "         <li>CPF: <b>" & Request("w_username") & "</b></li>" & VbCrLf
               w_html = w_html & "         <li>Senha de acesso: <b>" & Request("w_username") & "</b></li>" & VbCrLf
               w_html = w_html & "         <li>Assinatura eletrônica: <b>" & Request("w_username") & "</b></li>" & VbCrLf
               w_html = w_html & "         </ul>" & VbCrLf
               w_html = w_html & "      </font></td></tr>" & VbCrLf
               w_html = w_html & "      <tr valign=""top""><td><font size=2>" & VbCrLf
               w_html = w_html & "         Orientações e observações:<br>" & VbCrLf
               w_html = w_html & "         <ol>" & VbCrLf
               w_html = w_html & "         <li>Troque sua senha de acesso e assinatura no primeiro acesso que fizer ao sistema.</li>" & VbCrLf
               w_html = w_html & "         <li>Para trocar sua senha de acesso, localize no menu a opção <b>Troca senha</b> e clique sobre ela, seguindo as orientações apresentadas.</li>" & VbCrLf
               w_html = w_html & "         <li>Para trocar sua assinatura eletrônica, localize no menu a opção <b>Assinatura eletrônica</b> e clique sobre ela, seguindo as orientações apresentadas.</li>" & VbCrLf
               w_html = w_html & "         <li>Você pode fazer com que a senha de acesso e a assinatura eletrônica tenham o mesmo valor ou valores diferentes. A decisão é sua.</li>" & VbCrLf
               DB_GetCustomerData RS, w_cliente
               w_html = w_html & "         <li>Tanto a senha quanto a assinatura eletrônica têm tempo de vida máximo de <b>" & RS("dias_vig_senha") & "</b> dias. O sistema irá recomendar a troca <b>" & RS("dias_aviso_expir") & "</b> dias antes da expiração do tempo de vida.</li>" & VbCrLf
               w_html = w_html & "         <li>O sistema irá bloquear seu acesso se você errar sua senha de acesso ou sua senha de acesso <b>" & RS("maximo_tentativas") & "</b> vezes consecutivas. Se você tiver dúvidas ou não lembrar sua senha de acesso ou assinatura de acesso, utilize a opção ""Lembrar senha"" na tela de autenticação do sistema.</li>" & VbCrLf
               DesconectaBD
               w_html = w_html & "         <li>Acessos bloqueados por expiração do tempo de vida da senha de acesso ou assinaturas eletrônicas, ou por exceder o máximo de erros consecutivos, só podem ser desbloqueados pelo gestor de segurança do sistema.</li>" & VbCrLf
               w_html = w_html & "         </ol>" & VbCrLf
               w_html = w_html & "      </font></td></tr>" & VbCrLf
            ElseIf Instr("ED",O) > 0 Then
               If O = "E" Then
                  w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>EXCLUSÃO DE USUÁRIO</b></font><br><br><td></tr>" & VbCrLf
               ElseIf O = "D" Then
                  w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>BLOQUEIO DE USUÁRIO</b></font><br><br><td></tr>" & VbCrLf
               End If
               w_html = w_html & "      <tr valign=""top""><td><font size=2>" & VbCrLf
               DB_GetCustomerSite RS, w_cliente
               If O = "E" Then
                  w_html = w_html & "         Seus dados foram excluídos do sistema existente no endereço " & RS("logradouro") & ". A partir de agora você não poderá mais acessá-lo.<br>" & VbCrLf
               ElseIf O = "D" Then
                  w_html = w_html & "         Sua senha e assinatura eletrônica para acesso ao sistema existente no endereço " & RS("logradouro") & " foram bloqueadas pelo gestor de segurança. A partir de agora você não poderá mais acessá-lo.<br>" & VbCrLf
               End If
               DesconectaBD
               w_html = w_html & "         Em caso de dúvidas, entre em contato com o gestor:" & VbCrLf
               w_html = w_html & "         <ul>" & VbCrLf
               w_html = w_html & "         <li>Nome: <b>" & Session("nome") & "</b></li>" & VbCrLf
               w_html = w_html & "         <li>e-Mail: <b><a class=""ss"" href=""mailto:" & Session("EMAIL") & """>" & Session("EMAIL") & "</a></b></li>" & VbCrLf
               w_html = w_html & "         </ul>" & VbCrLf
               w_html = w_html & "      </font></td></tr>" & VbCrLf
            End If
            w_html = w_html & "      <tr valign=""top""><td><font size=2>" & VbCrLf
            w_html = w_html & "         Dados da ocorrência:<br>" & VbCrLf
            w_html = w_html & "         <ul>" & VbCrLf
            w_html = w_html & "         <li>Data do servidor: <b>" & FormatDateTime(Date(),1) & ", " & Time() & "</b></li>" & VbCrLf
            w_html = w_html & "         <li>IP de origem: <b>" & Request.ServerVariables("REMOTE_HOST") & "</b></li>" & VbCrLf
            w_html = w_html & "         </ul>" & VbCrLf
            w_html = w_html & "      </font></td></tr>" & VbCrLf
            w_html = w_html & "    </table>" & VbCrLf
            w_html = w_html & "</td></tr>" & VbCrLf
            w_html = w_html & "</table>" & VbCrLf
            w_html = w_html & "</BODY>" & VbCrLf
            w_html = w_html & "</HTML>" & VbCrLf

            ' Executa a função de envio de e-mail
            If O = "I" Then
               w_resultado = EnviaMail("Aviso de criação de usuário", w_html, Request("w_email"))
            ElseIf O = "E" Then
               w_resultado = EnviaMail("Aviso de exclusão de usuário", w_html, Request("w_email"))
            ElseIf O = "D" Then
               w_resultado = EnviaMail("Aviso de bloqueio de acesso", w_html, Request("w_email"))
            ElseIf O = "T" Then
               w_resultado = EnviaMail("Aviso de desbloqueio de acesso", w_html, Request("w_email"))
            End If
         End If
     End If

     ' Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
     DB_GetLinkData RS, Session("p_cliente"), SG
     ScriptOpen "JavaScript"
     If SG = "SGUSU" or SG = "RHUSU" or SG = "CLUSUARIO" Then
        If Instr("IAD",O) Then
           If w_resultado > "" Then
              ShowHTML "  alert('ATENÇÃO: operação executada mas não foi possível proceder o envio do e-mail.\n" & w_resultado & "');"
           Else
              ShowHTML "  alert('Operação executada!');"
           End If
        End If
        ShowHTML "  location.href='" & RS("link") & "&O=L&w_cliente=" & Request("w_cliente") & "&w_sq_solicitacao=" & Request("w_sq_solicitacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_data_inicio=" & Request("p_data_inicio") & "&p_data_fim=" & Request("p_data_fim") & "&p_solicitante=" & Request("p_solicitante") & "&p_numero=" & Request("p_numero") & "&p_localizacao=" & Request("p_localizacao") & "&p_lotacao=" & Request("p_lotacao") & "&p_nome=" & Request("p_nome") & "&p_gestor=" & Request("p_gestor") & "&p_ordena=" & Request("p_ordena") & "';"
     Else
        ShowHTML "  location.href='" & RS("link") & "&O=" & O & "&w_cliente=" & Request("w_cliente") & "&w_sq_solicitacao=" & Request("w_sq_solicitacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_data_inicio=" & Request("p_data_inicio") & "&p_data_fim=" & Request("p_data_fim") & "&p_solicitante=" & Request("p_solicitante") & "&p_numero=" & Request("p_numero") & "&p_localizacao=" & Request("p_localizacao") & "&p_lotacao=" & Request("p_lotacao") & "&p_nome=" & Request("p_nome") & "&p_gestor=" & Request("p_gestor") & "&p_ordena=" & Request("p_ordena") & "';"
     End If
     ScriptClose
     DesconectaBD
  Else
     ScriptOpen "JavaScript"
     ShowHTML "  alert('Assinatura Eletrônica inválida!');"
     ShowHTML "  history.back(1);"
     ScriptClose
  End If

  Set w_resultado       = Nothing
  Set w_html            = Nothing
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
    Case "BENEF" Benef
    Case "BUSCAUSUARIO" BuscaUsuario
    Case "GRAVA" Grava
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

