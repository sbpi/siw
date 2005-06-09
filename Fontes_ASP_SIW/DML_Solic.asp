<%
REM =========================================================================
REM Mantém a tabela de arquivos
REM -------------------------------------------------------------------------
Sub DML_PutSolicArquivo(Operacao, p_cliente, p_chave, p_chave_aux, p_nome, p_descricao, _
    p_caminho, p_tamanho, p_tipo)
    
  Dim l_Operacao, l_Chave, l_cliente, l_chave_aux, l_nome, l_descricao
  Dim l_caminho, l_tamanho, l_tipo
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_cliente             = Server.CreateObject("ADODB.Parameter") 
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux           = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao           = Server.CreateObject("ADODB.Parameter") 
  Set l_caminho             = Server.CreateObject("ADODB.Parameter") 
  Set l_tamanho             = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo                = Server.CreateObject("ADODB.Parameter") 

  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_cliente              = .CreateParameter("l_cliente",         adInteger, adParamInput,    , p_cliente)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",       adInteger, adParamInput,    , Tvl(p_chave_aux))
     set l_nome                 = .CreateParameter("l_nome",            adVarchar, adParamInput, 255, Tvl(p_nome))
     set l_descricao            = .CreateParameter("l_descricao",       adVarchar, adParamInput,1000, Tvl(p_descricao))
     set l_caminho              = .CreateParameter("l_caminho",         adVarchar, adParamInput, 255, Tvl(p_caminho))
     set l_tamanho              = .CreateParameter("l_tamanho",         adInteger, adParamInput,    , Tvl(p_tamanho))
     set l_tipo                 = .CreateParameter("l_tipo",            adVarchar, adParamInput,  60, Tvl(p_tipo))
     .parameters.Append         l_Operacao
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_nome
     .parameters.Append         l_descricao
     .parameters.Append         l_caminho
     .parameters.Append         l_tamanho
     .parameters.Append         l_tipo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_PutSolicArquivo"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_caminho"
     .parameters.Delete         "l_tamanho"
     .parameters.Delete         "l_tipo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>