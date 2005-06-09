<%
REM =========================================================================
REM Mantém a tabela de tipos de dado
REM -------------------------------------------------------------------------
Sub DML_PutTipoDado(Operacao, p_chave, p_nome, p_descricao)

  Dim l_Operacao, l_Chave, l_nome, l_descricao
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao           = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  30, Tvl(p_nome))
     set l_descricao			= .CreateParameter("l_descricao",			adVarchar, adParamInput,4000, Tvl(p_descricao))

     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_descricao

     .CommandText               = Session("schema") & "SP_PutTipoDado"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_descricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de tipos de dado
REM -------------------------------------------------------------------------
Sub DML_PutEventoTrigger(Operacao, p_chave, p_nome, p_descricao)

  Dim l_Operacao, l_Chave, l_nome, l_descricao
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao           = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  30, Tvl(p_nome))
     set l_descricao			= .CreateParameter("l_descricao",			adVarchar, adParamInput,4000, Tvl(p_descricao))

     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_descricao

     .CommandText               = Session("schema") & "SP_PutEventoTrigger"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_descricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de tipos de dado
REM -------------------------------------------------------------------------
Sub DML_PutTipoIndice(Operacao, p_chave, p_nome, p_descricao)

  Dim l_Operacao, l_Chave, l_nome, l_descricao
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao           = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  30, Tvl(p_nome))
     set l_descricao			= .CreateParameter("l_descricao",			adVarchar, adParamInput,4000, Tvl(p_descricao))

     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_descricao

     .CommandText               = Session("schema") & "SP_PutTipoIndice"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_descricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de tipos de stored procedure
REM -------------------------------------------------------------------------
Sub DML_PutTipoSP(Operacao, p_chave, p_nome, p_descricao)

  Dim l_Operacao, l_Chave, l_nome, l_descricao
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao           = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  30, Tvl(p_nome))
     set l_descricao			= .CreateParameter("l_descricao",			adVarchar, adParamInput,4000, Tvl(p_descricao))

     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_descricao

     .CommandText               = Session("schema") & "SP_PutTipoSP"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_descricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------


REM =========================================================================
REM Mantém a tabela de tipos de stored procedure
REM -------------------------------------------------------------------------
Sub DML_PutTipoTabela(Operacao, p_chave, p_nome, p_descricao)

  Dim l_Operacao, l_Chave, l_nome, l_descricao
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao           = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  30, Tvl(p_nome))
     set l_descricao			= .CreateParameter("l_descricao",			adVarchar, adParamInput,4000, Tvl(p_descricao))

     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_descricao

     .CommandText               = Session("schema") & "SP_PutTipoTabela"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_descricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>