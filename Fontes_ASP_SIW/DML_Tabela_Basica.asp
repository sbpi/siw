<%
REM =========================================================================
REM Manipula registros de CO_Etnia
REM -------------------------------------------------------------------------
Sub DML_COEtnia(Operacao, Chave, nome, codigo_siape, ativo)
  Dim l_Operacao, l_Chave, l_nome, l_codigo_siape, l_ativo
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_Chave           = Server.CreateObject("ADODB.Parameter")
  Set l_nome            = Server.CreateObject("ADODB.Parameter")
  Set l_codigo_siape    = Server.CreateObject("ADODB.Parameter")
  Set l_ativo           = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",    adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,    , Tvl(chave))
     set l_nome                 = .CreateParameter("l_nome",        adVarChar, adParamInput,  10, nome)
     set l_codigo_siape         = .CreateParameter("l_codigo_siape",adVarChar, adParamInput,   2, codigo_siape)
     set l_ativo                = .CreateParameter("l_ativo",       adVarchar, adParamInput,   1, ativo)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_codigo_siape
     .parameters.Append         l_ativo
     .CommandText               = Session("schema") & "SP_PutCOEtnia"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_codigo_siape"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de CO_FORMACAO
REM -------------------------------------------------------------------------
Sub DML_COForm(Operacao, Chave, tipo, nome, ordem, ativo)
  Dim l_Operacao, l_Chave, l_tipo, l_nome, l_ordem, l_ativo
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_Chave           = Server.CreateObject("ADODB.Parameter")
  Set l_tipo            = Server.CreateObject("ADODB.Parameter")
  Set l_nome            = Server.CreateObject("ADODB.Parameter")
  Set l_ordem           = Server.CreateObject("ADODB.Parameter")
  Set l_ativo           = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",    adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,    , Tvl(chave))
     set l_tipo                 = .CreateParameter("l_tipo",        adVarChar, adParamInput,   1, tipo)
     set l_nome                 = .CreateParameter("l_nome",        adVarChar, adParamInput,  50, nome)
     set l_ordem                = .CreateParameter("l_ordem",       adInteger, adParamInput,    , ordem)
     set l_ativo                = .CreateParameter("l_ativo",       adVarchar, adParamInput,   1, ativo)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_tipo
     .parameters.Append         l_nome
     .parameters.Append         l_ordem
     .parameters.Append         l_ativo
     .CommandText               = Session("schema") & "SP_PutCOForm"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_tipo"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ordem"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de CO_IDIOMA
REM -------------------------------------------------------------------------
Sub DML_COIdioma(Operacao, Chave, nome, padrao, ativo)
  Dim l_Operacao, l_Chave, l_nome, l_padrao, l_ativo
  Set l_Operacao  = Server.CreateObject("ADODB.Parameter")
  Set l_Chave     = Server.CreateObject("ADODB.Parameter")
  Set l_nome      = Server.CreateObject("ADODB.Parameter")
  Set l_padrao    = Server.CreateObject("ADODB.Parameter")
  Set l_ativo     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",    adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,    , Tvl(chave))
     set l_nome                 = .CreateParameter("l_nome",        adVarChar, adParamInput,  20, nome)
     set l_padrao               = .CreateParameter("l_padrao",      adVarchar, adParamInput,   1, padrao)
     set l_ativo                = .CreateParameter("l_ativo",       adVarchar, adParamInput,   1, ativo)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_padrao
     .parameters.Append         l_ativo
     .CommandText               = Session("schema") & "SP_PutCOIdioma"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_padrao"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de CO_GRUPO_DEFICIENCIA
REM -------------------------------------------------------------------------
Sub DML_COGRDEF(Operacao, Chave, nome, ativo)
  Dim l_Operacao, l_Chave, l_nome, l_ativo
  Set l_Operacao  = Server.CreateObject("ADODB.Parameter")
  Set l_Chave     = Server.CreateObject("ADODB.Parameter")
  Set l_nome      = Server.CreateObject("ADODB.Parameter")
  Set l_ativo     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",    adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,    , Tvl(chave))
     set l_nome                 = .CreateParameter("l_nome",        adVarChar, adParamInput,  50, nome)
     set l_ativo                = .CreateParameter("l_ativo",       adVarchar, adParamInput,   1, ativo)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
     .CommandText               = Session("schema") & "SP_PutCOGRDEF"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de CO_DEFICIENCIA
REM -------------------------------------------------------------------------
Sub DML_COTPDEF(Operacao, Chave, sq_grupo_deficiencia, codigo, nome, descricao, ativo)
  Dim l_Operacao, l_Chave, l_sq_grupo_deficiencia, l_codigo, l_nome, l_descricao, l_ativo
  Set l_Operacao              = Server.CreateObject("ADODB.Parameter")
  Set l_Chave                 = Server.CreateObject("ADODB.Parameter")
  Set l_sq_grupo_deficiencia  = Server.CreateObject("ADODB.Parameter")
  Set l_codigo                = Server.CreateObject("ADODB.Parameter")
  Set l_nome                  = Server.CreateObject("ADODB.Parameter")
  Set l_descricao             = Server.CreateObject("ADODB.Parameter")
  Set l_ativo                 = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",              adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",                 adInteger, adParamInput,    , Tvl(chave))
     set l_sq_grupo_deficiencia = .CreateParameter("l_sq_grupo_deficiencia",  adInteger, adParamInput,    , Tvl(sq_grupo_deficiencia))
     set l_codigo               = .CreateParameter("l_codigo",                adVarChar, adParamInput,   3, codigo)
     set l_nome                 = .CreateParameter("l_nome",                  adVarChar, adParamInput,  50, nome)
     set l_descricao            = .CreateParameter("l_descricao",             adVarChar, adParamInput, 200, descricao)
     set l_ativo                = .CreateParameter("l_ativo",                 adVarchar, adParamInput,   1, ativo)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_sq_grupo_deficiencia
     .parameters.Append         l_codigo
     .parameters.Append         l_nome
     .parameters.Append         l_descricao
     .parameters.Append         l_ativo
     .CommandText               = Session("schema") & "SP_PutCOTPDEF"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sq_grupo_deficiencia"
     .parameters.Delete         "l_codigo"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de CO_TIPO_ENDERECO
REM -------------------------------------------------------------------------
Sub DML_COTPENDER(Operacao, Chave, sq_tipo_pessoa, nome, padrao, ativo, email, internet)
  Dim l_Operacao, l_Chave, l_sq_tipo_pessoa, l_nome, l_padrao, l_ativo, l_email, l_internet
  Set l_Operacao              = Server.CreateObject("ADODB.Parameter")
  Set l_Chave                 = Server.CreateObject("ADODB.Parameter")
  Set l_sq_tipo_pessoa        = Server.CreateObject("ADODB.Parameter")
  Set l_nome                  = Server.CreateObject("ADODB.Parameter")
  Set l_padrao                = Server.CreateObject("ADODB.Parameter")
  Set l_ativo                 = Server.CreateObject("ADODB.Parameter")
  Set l_email                 = Server.CreateObject("ADODB.Parameter")
  Set l_internet              = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",              adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",                 adInteger, adParamInput,    , Tvl(chave))
     set l_sq_tipo_pessoa       = .CreateParameter("l_sq_tipopessoa",         adInteger, adParamInput,    , Tvl(sq_tipo_pessoa))
     set l_nome                 = .CreateParameter("l_nome",                  adVarChar, adParamInput,  30, nome)
     set l_padrao               = .CreateParameter("l_padrao",                adVarchar, adParamInput,   1, padrao)
     set l_ativo                = .CreateParameter("l_ativo",                 adVarchar, adParamInput,   1, ativo)
     set l_email                = .CreateParameter("l_email",                 adVarchar, adParamInput,   1, email)     
     set l_internet             = .CreateParameter("l_internet",              adVarchar, adParamInput,   1, internet)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_sq_tipo_pessoa
     .parameters.Append         l_nome
     .parameters.Append         l_padrao
     .parameters.Append         l_ativo
     .parameters.Append         l_email     
     .parameters.Append         l_internet
     .CommandText               = Session("schema") & "SP_PutCOTPENDER"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sq_tipo_pessoa"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_padrao"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_email"
     .parameters.Delete         "l_internet"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de CO_TPPESSOA
REM -------------------------------------------------------------------------
Sub DML_COTPPESSOA(Operacao, Chave, nome, padrao, ativo)
  Dim l_Operacao, l_Chave, l_nome, l_padrao, l_ativo
  Set l_Operacao  = Server.CreateObject("ADODB.Parameter")
  Set l_Chave     = Server.CreateObject("ADODB.Parameter")
  Set l_nome      = Server.CreateObject("ADODB.Parameter")
  Set l_padrao    = Server.CreateObject("ADODB.Parameter")
  Set l_ativo     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",    adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,    , Tvl(chave))
     set l_nome                 = .CreateParameter("l_nome",        adVarChar, adParamInput,  60, nome)
     set l_padrao               = .CreateParameter("l_padrao",      adVarchar, adParamInput,   1, padrao)
     set l_ativo                = .CreateParameter("l_ativo",       adVarchar, adParamInput,   1, ativo)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_padrao
     .parameters.Append         l_ativo
     .CommandText               = Session("schema") & "SP_PutCOTPPESSOA"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_padrao"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de CO_TIPO_TELEFONE
REM -------------------------------------------------------------------------
Sub DML_COTPFONE(Operacao, Chave, sq_tipo_pessoa, nome, padrao, ativo)
  Dim l_Operacao, l_Chave, l_sq_tipo_pessoa, l_nome, l_padrao, l_ativo
  Set l_Operacao              = Server.CreateObject("ADODB.Parameter")
  Set l_Chave                 = Server.CreateObject("ADODB.Parameter")
  Set l_sq_tipo_pessoa        = Server.CreateObject("ADODB.Parameter")
  Set l_nome                  = Server.CreateObject("ADODB.Parameter")
  Set l_padrao                = Server.CreateObject("ADODB.Parameter")
  Set l_ativo                 = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",              adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",                 adInteger, adParamInput,    , Tvl(chave))
     set l_sq_tipo_pessoa       = .CreateParameter("l_sq_tipopessoa",         adInteger, adParamInput,    , Tvl(sq_tipo_pessoa))
     set l_nome                 = .CreateParameter("l_nome",                  adVarChar, adParamInput,  25, nome)
     set l_padrao               = .CreateParameter("l_padrao",                adVarchar, adParamInput,   1, padrao)
     set l_ativo                = .CreateParameter("l_ativo",                 adVarchar, adParamInput,   1, ativo)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_sq_tipo_pessoa
     .parameters.Append         l_nome
     .parameters.Append         l_padrao
     .parameters.Append         l_ativo
     .CommandText               = Session("schema") & "SP_PutCOTPFONE"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sq_tipo_pessoa"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_padrao"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

