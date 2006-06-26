<%
REM =========================================================================
REM Manipula registros de S_CARGO
REM -------------------------------------------------------------------------
Sub DML_SCargo(Operacao, Chave, co_cargo, ds_cargo)
  Dim l_Operacao, l_Chave, l_co_cargo, l_ds_cargo
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_Chave           = Server.CreateObject("ADODB.Parameter")
  Set l_co_cargo        = Server.CreateObject("ADODB.Parameter")
  Set l_ds_cargo        = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao        = .CreateParameter("l_operacao",   adVarchar, adParamInput,  1, Operacao)
     set l_chave           = .CreateParameter("l_chave",      adVarchar, adParamInput, 17, chave)
     set l_co_cargo        = .CreateParameter("l_co_cargo",   adVarchar, adParamInput, 17, Tvl(co_cargo))
     set l_ds_cargo        = .CreateParameter("l_ds_cargo",      adChar, adParamInput, 30, Tvl(ds_cargo))
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_co_cargo
     .parameters.Append         l_ds_cargo
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_PutSCARGO"
     Else
        .CommandText               = "ecw.SP_PutSCARGO"
     End If
     .Execute
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_co_cargo"
     .parameters.Delete         "l_ds_cargo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros da tabela Alberguista
REM -------------------------------------------------------------------------
Sub DML_ALBCAD(operacao, chave, carteira, nome, nascimento, endereco, bairro,_
               cep, cidade, uf, ddd, fone, cpf, rg_Numero, rg_Emissor, email, sexo, formacao,_
               trabalha, email_Trabalho, conhece_Albergue, visitas, classificacao, destino,_
               destino_Outros, motivo_Viagem, motivo_Outros, forma_Conhece, forma_Outros,_
               sq_Cidade, carteira_Emissao, carteira_Validade)

  Dim l_operacao,     l_chave,         l_carteira,         l_nome,             l_nascimento,    l_endereco,       l_bairro
  Dim l_cep,          l_cidade,        l_uf,               l_ddd,              l_fone,          l_cpf,            l_rg_numero
  Dim l_rg_emissor,   l_email,         l_sexo,             l_formacao,         l_trabalha,      l_email_trabalho, l_conhece_albergue
  Dim l_visitas,      l_classificacao, l_destino,          l_destino_outros,   l_motivo_viagem, l_motivo_outros,  l_forma_conhece
  Dim l_forma_outros, l_sq_cidade,     l_carteira_emissao, l_carteira_validade 

  Set l_Operacao          = Server.CreateObject("ADODB.Parameter")
  Set l_Chave             = Server.CreateObject("ADODB.Parameter")
  Set l_carteira          = Server.CreateObject("ADODB.Parameter")
  Set l_nome              = Server.CreateObject("ADODB.Parameter")
  Set l_nascimento        = Server.CreateObject("ADODB.Parameter")
  Set l_endereco          = Server.CreateObject("ADODB.Parameter")
  Set l_bairro            = Server.CreateObject("ADODB.Parameter")
  Set l_cep               = Server.CreateObject("ADODB.Parameter")
  Set l_cidade            = Server.CreateObject("ADODB.Parameter")
  Set l_uf                = Server.CreateObject("ADODB.Parameter")
  Set l_ddd               = Server.CreateObject("ADODB.Parameter")
  Set l_fone              = Server.CreateObject("ADODB.Parameter")
  Set l_cpf               = Server.CreateObject("ADODB.Parameter")
  Set l_rg_numero         = Server.CreateObject("ADODB.Parameter")
  Set l_rg_emissor        = Server.CreateObject("ADODB.Parameter")
  Set l_email             = Server.CreateObject("ADODB.Parameter")
  Set l_sexo              = Server.CreateObject("ADODB.Parameter")
  Set l_formacao          = Server.CreateObject("ADODB.Parameter")
  Set l_trabalha          = Server.CreateObject("ADODB.Parameter")
  Set l_email_trabalho    = Server.CreateObject("ADODB.Parameter")
  Set l_conhece_albergue  = Server.CreateObject("ADODB.Parameter")
  Set l_visitas           = Server.CreateObject("ADODB.Parameter")
  Set l_classificacao     = Server.CreateObject("ADODB.Parameter")
  Set l_destino           = Server.CreateObject("ADODB.Parameter")
  Set l_destino_outros    = Server.CreateObject("ADODB.Parameter")
  Set l_motivo_viagem     = Server.CreateObject("ADODB.Parameter")
  Set l_motivo_outros     = Server.CreateObject("ADODB.Parameter")
  Set l_forma_conhece     = Server.CreateObject("ADODB.Parameter")
  Set l_forma_outros      = Server.CreateObject("ADODB.Parameter")
  Set l_sq_cidade         = Server.CreateObject("ADODB.Parameter")
  Set l_carteira_emissao  = Server.CreateObject("ADODB.Parameter")
  Set l_carteira_validade = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_Operacao          = .CreateParameter("l_operacao",          adVarchar, adParamInput,  1, Operacao)
     set l_chave             = .CreateParameter("l_chave",             adInteger, adParamInput,   , Tvl(chave))
     set l_carteira          = .CreateParameter("l_carteira",          adVarchar, adParamInput, 20, carteira)
     set l_nome              = .CreateParameter("l_nome",              adVarchar, adParamInput, 60, nome)
     set l_nascimento        = .CreateParameter("l_nascimento",           adDate, adParamInput,   , Tvl(nascimento))
     set l_endereco          = .CreateParameter("l_endereco",          adVarchar, adParamInput, 60, Tvl(endereco))
     set l_bairro            = .CreateParameter("l_bairro",            adVarchar, adParamInput, 30, Tvl(bairro))
     set l_cep               = .CreateParameter("l_cep",               adVarchar, adParamInput,  9, Tvl(cep))
     set l_cidade            = .CreateParameter("l_cidade",            adVarchar, adParamInput, 40, Tvl(cidade))
     set l_uf                = .CreateParameter("l_uf",                adVarchar, adParamInput,  2, Tvl(uf))
     set l_ddd               = .CreateParameter("l_ddd",               adVarchar, adParamInput,  3, Tvl(ddd))
     set l_fone              = .CreateParameter("l_fone",              adVarchar, adParamInput, 50, Tvl(fone))
     set l_cpf               = .CreateParameter("l_cpf",               adVarchar, adParamInput, 14, Tvl(cpf))
     set l_rg_numero         = .CreateParameter("l_rg_numero",         adVarchar, adParamInput, 20, Tvl(rg_Numero))
     set l_rg_emissor        = .CreateParameter("l_rg_emissor",        adVarchar, adParamInput, 20, Tvl(rg_Emissor))
     set l_email             = .CreateParameter("l_email",             adVarchar, adParamInput, 60, Tvl(email))
     set l_sexo              = .CreateParameter("l_sexo",              adVarchar, adParamInput,  1, Tvl(sexo))
     set l_formacao          = .CreateParameter("l_formacao",          adVarchar, adParamInput,  1, Tvl(formacao))
     set l_trabalha          = .CreateParameter("l_trabalha",          adVarchar, adParamInput,  1, Tvl(trabalha))
     set l_email_trabalho    = .CreateParameter("l_email_trabalho",    adVarchar, adParamInput, 60, Tvl(email_Trabalho))
     set l_conhece_albergue  = .CreateParameter("l_conhece_albergue",  adVarchar, adParamInput,  1, Tvl(conhece_Albergue))
     set l_visitas           = .CreateParameter("l_visitas",           adInteger, adParamInput,   , Tvl(visitas))
     set l_classificacao     = .CreateParameter("l_classificacao",     adVarchar, adParamInput,  1, Tvl(classificacao))
     set l_destino           = .CreateParameter("l_destino",           adVarchar, adParamInput,  1, Tvl(destino))
     set l_destino_outros    = .CreateParameter("l_destino_outros",    adVarchar, adParamInput, 50, Tvl(destino_Outros))
     set l_motivo_viagem     = .CreateParameter("l_motivo_viagem",     adVarchar, adParamInput,  1, Tvl(motivo_Viagem))
     set l_motivo_outros     = .CreateParameter("l_motivo_outros",     adVarchar, adParamInput, 50, Tvl(motivo_Outros))
     set l_forma_conhece     = .CreateParameter("l_forma_conhece",     adVarchar, adParamInput,  1, Tvl(forma_Conhece))
     set l_forma_outros      = .CreateParameter("l_forma_outros",      adVarchar, adParamInput, 50, Tvl(forma_Outros))
     set l_sq_cidade         = .CreateParameter("l_sq_cidade",         adInteger, adParamInput,   , Tvl(sq_Cidade))
     set l_carteira_emissao  = .CreateParameter("l_carteira_emissao",     adDate, adParamInput,   , Tvl(carteira_Emissao))
     set l_carteira_validade = .CreateParameter("l_carteira_validade",    adDate, adParamInput,   , Tvl(carteira_Validade))
     
     .parameters.Append l_Operacao
     .parameters.Append l_Chave
     .parameters.Append l_carteira
     .parameters.Append l_nome     
     .parameters.Append l_nascimento
     .parameters.Append l_endereco
     .parameters.Append l_bairro
     .parameters.Append l_cep
     .parameters.Append l_cidade
     .parameters.Append l_uf
     .parameters.Append l_ddd
     .parameters.Append l_fone
     .parameters.Append l_cpf
     .parameters.Append l_rg_Numero
     .parameters.Append l_rg_Emissor
     .parameters.Append l_email
     .parameters.Append l_sexo
     .parameters.Append l_formacao
     .parameters.Append l_trabalha
     .parameters.Append l_email_Trabalho
     .parameters.Append l_conhece_Albergue
     .parameters.Append l_visitas
     .parameters.Append l_classificacao
     .parameters.Append l_destino
     .parameters.Append l_destino_Outros
     .parameters.Append l_motivo_Viagem
     .parameters.Append l_motivo_Outros
     .parameters.Append l_forma_Conhece
     .parameters.Append l_forma_Outros
     .parameters.Append l_sq_Cidade
     .parameters.Append l_carteira_Emissao
     .parameters.Append l_carteira_Validade
     
     
     'If Session("dbms") = 2 Then
     '   .CommandText      = "fbaj.fbaj.SP_PutALBCAD"
     'Else
     '   .CommandText      = "FBAJ.SP_PutAlbCad"
     'End If
     .CommandText      = "FBAJ.SP_PutAlbCad"
        
     'On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete "l_Operacao"
     .parameters.Delete "l_Chave"
     .parameters.Delete "l_carteira"
     .parameters.Delete "l_nome"
     .parameters.Delete "l_nascimento"
     .parameters.Delete "l_endereco"
     .parameters.Delete "l_bairro"
     .parameters.Delete "l_cep"
     .parameters.Delete "l_cidade"
     .parameters.Delete "l_uf"
     .parameters.Delete "l_ddd"
     .parameters.Delete "l_fone"
     .parameters.Delete "l_cpf"
     .parameters.Delete "l_rg_Numero"
     .parameters.Delete "l_rg_Emissor"
     .parameters.Delete "l_email"
     .parameters.Delete "l_sexo"
     .parameters.Delete "l_formacao"
     .parameters.Delete "l_trabalha"
     .parameters.Delete "l_email_Trabalho"
     .parameters.Delete "l_conhece_Albergue"
     .parameters.Delete "l_visitas"
     .parameters.Delete "l_classificacao"
     .parameters.Delete "l_destino"
     .parameters.Delete "l_destino_Outros"
     .parameters.Delete "l_motivo_Viagem"
     .parameters.Delete "l_motivo_Outros"
     .parameters.Delete "l_forma_Conhece"
     .parameters.Delete "l_forma_Outros"
     .parameters.Delete "l_sq_Cidade"
     .parameters.Delete "l_carteira_Emissao"
     .parameters.Delete "l_carteira_Validade"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>