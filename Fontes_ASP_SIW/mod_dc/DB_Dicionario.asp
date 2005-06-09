<%
REM =========================================================================
REM Recupera Arquivos
REM -------------------------------------------------------------------------
Sub DB_GetArquivo (p_rs, p_cliente, p_chave, p_sq_sistema, p_nome, p_tipo_arquivo)
  Dim l_cliente, l_chave, l_sq_sistema, l_nome, l_tipo_arquivo
  
  Set l_cliente      = Server.CreateObject("ADODB.Parameter")
  Set l_chave        = Server.CreateObject("ADODB.Parameter")
  Set l_sq_sistema   = Server.CreateObject("ADODB.Parameter")
  Set l_nome         = Server.CreateObject("ADODB.Parameter")
  Set l_tipo_arquivo = Server.CreateObject("ADODB.Parameter")
    
  with sp
    
    set l_cliente      = .CreateParameter("l_cliente"      , adInteger, adParamInput,   , p_cliente)
    set l_chave        = .CreateParameter("l_chave"        , adInteger, adParamInput,   , tvl(p_chave))
    set l_sq_sistema   = .CreateParameter("l_sq_sistema"   , adInteger, adParamInput,   , tvl(p_sq_sistema))
    set l_nome         = .CreateParameter("l_nome"         , adVarchar, adParamInput, 30, tvl(p_nome))
    set l_tipo_arquivo = .CreateParameter("l_tipo_arquivo" , adVarchar, adParamInput,  1, tvl(p_tipo_arquivo))  
     
     .parameters.Append l_cliente
     .parameters.Append l_chave
     .parameters.Append l_sq_sistema
     .parameters.Append l_nome
     .parameters.Append l_tipo_arquivo
   
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetArquivo"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_sq_sistema"
     .Parameters.Delete         "l_nome"
     .Parameters.Delete         "l_tipo_arquivo"
  end with
End Sub
REM =========================================================================
REM Final da rotina de arquivos 
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera Colunas
REM -------------------------------------------------------------------------

Sub DB_GetColuna(p_rs, p_cliente, p_chave, w_sq_tabela, w_sq_dado_tipo, p_sq_sistema, p_sq_usuario, p_nome)
  Dim l_cliente, l_chave,l_tabela, l_dado_tipo, l_sq_sistema, l_sq_usuario, l_nome
  
  Set l_cliente         = Server.CreateObject("ADODB.Parameter")
  Set l_chave           = Server.CreateObject("ADODB.Parameter")
  Set l_tabela          = Server.CreateObject("ADODB.Parameter")
  Set l_dado_tipo       = Server.CreateObject("ADODB.Parameter")
  Set l_sq_sistema      = Server.CreateObject("ADODB.Parameter")
  Set l_sq_usuario      = Server.CreateObject("ADODB.Parameter")
  Set l_nome            = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_cliente         = .CreateParameter("l_cliente"        ,   adInteger, adParamInput,   , p_cliente)
     set l_chave           = .CreateParameter("l_chave"          ,   adInteger, adParamInput,   , tvl(p_chave))
     set l_tabela          = .CreateParameter("l_tabela"         ,   adInteger, adParamInput,   , tvl(w_sq_tabela))
     set l_dado_tipo       = .CreateParameter("l_dado_tipo"      ,   adInteger, adParamInput,   , tvl(w_sq_dado_tipo))
     set l_sq_sistema      = .CreateParameter("l_sq_sistema"     ,   adInteger, adParamInput,   , tvl(p_sq_sistema))
     set l_sq_usuario      = .CreateParameter("l_sq_usuario"     ,   adInteger, adParamInput,   , tvl(p_sq_usuario))
     set l_nome            = .CreateParameter("l_nome"           ,   adVarchar, adParamInput,  30,tvl(p_nome))
     
     
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_tabela
     .parameters.Append         l_dado_tipo
     .parameters.Append         l_sq_sistema
     .parameters.Append         l_sq_usuario
     .parameters.Append         l_nome
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetColuna"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_tabela"
     .parameters.Delete         "l_dado_tipo"
     .parameters.Delete         "l_sq_sistema"
     .parameters.Delete         "l_sq_usuario"
     .parameters.Delete         "l_nome"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os recursos de um projeto
REM -------------------------------------------------------------------------
Sub DB_GetTrigEvento(p_rs, p_chave, p_chave_aux)
  Dim l_chave, l_chave_aux
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",         adInteger, adParamInput,   , p_chave)
     set l_chave_aux            = .CreateParameter("l_chave_aux",     adInteger, adParamInput,   , Tvl(p_chave_aux))
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetTrigEvento"
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_chave_aux"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os recursos de um projeto
REM -------------------------------------------------------------------------
Sub DB_GetSPTabs(p_rs, p_chave, p_chave_aux)
  Dim l_chave, l_chave_aux
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",     adInteger, adParamInput,   , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux", adInteger, adParamInput,   , Tvl(p_chave_aux))
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSPTabs"
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_chave_aux"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os recursos de um projeto
REM -------------------------------------------------------------------------
Sub DB_GetSPSP(p_rs, p_chave, p_chave_aux)
  Dim l_chave, l_chave_aux
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",         adInteger, adParamInput,   , p_chave)
     set l_chave_aux            = .CreateParameter("l_chave_aux",     adInteger, adParamInput,   , Tvl(p_chave_aux))
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSPSP"
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_chave_aux"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os recursos de um projeto
REM -------------------------------------------------------------------------
Sub DB_GetSPParametro(p_rs, p_chave, p_chave_aux, p_sq_dado_tipo)
  Dim l_chave, l_chave_aux, l_sq_dado_tipo 
  
  Set l_chave          = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux      = Server.CreateObject("ADODB.Parameter")
  Set l_sq_dado_tipo   = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave         = .CreateParameter("l_chave"         , adInteger, adParamInput, , p_chave)
     set l_chave_aux     = .CreateParameter("l_chave_aux"     , adInteger, adParamInput, , p_chave_aux)
     set l_sq_dado_tipo  = .CreateParameter("l_sq_dado_tipo"  , adInteger, adParamInput, , Tvl(p_sq_dado_tipo))
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_sq_dado_tipo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSPParametro"
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_chave_aux"
     .Parameters.Delete         "l_sq_dado_tipo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------



REM =========================================================================
REM Recupera tipos de dado
REM -------------------------------------------------------------------------
Sub DB_GetSistema(p_rs, p_chave, p_cliente)
  Dim l_cliente, l_chave
  Set l_cliente         = Server.CreateObject("ADODB.Parameter")
  Set l_chave           = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave               = .CreateParameter("l_chave",        adInteger, adParamInput,   , tvl(p_chave))
     set l_cliente             = .CreateParameter("l_cliente"      , adInteger, adParamInput,   , p_cliente)
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSistema"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_cliente"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera Usuario
REM -------------------------------------------------------------------------
Sub DB_GetUsuario(p_rs, p_cliente, p_chave, p_chave_aux)
  Dim l_cliente, l_chave, l_chave_aux
  Set l_chave           = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux       = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente             = .CreateParameter("l_cliente"      , adInteger, adParamInput,   , p_cliente)
     set l_chave               = .CreateParameter("l_chave",        adInteger, adParamInput,   , tvl(p_chave))
     set l_chave_aux           = .CreateParameter("l_chave_aux",    adInteger, adParamInput,   , tvl(p_chave_aux))
     .parameters.Append        l_cliente
     .parameters.Append        l_chave
     .parameters.Append        l_chave_aux
     if Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetUsuario"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_chave_aux"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera Usuario
REM -------------------------------------------------------------------------
Sub DB_GetUsuarioTabs(p_rs, p_chave, p_chave_aux, p_sq_tabela)
  Dim l_chave, l_chave_aux, l_sq_tabela
  
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux = Server.CreateObject("ADODB.Parameter")
  Set l_sq_tabela = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave     = .CreateParameter("l_chave",     adInteger, adParamInput,   , tvl(p_chave))
     set l_chave_aux = .CreateParameter("l_chave_aux", adInteger, adParamInput,   , tvl(p_chave_aux))
     set l_sq_tabela = .CreateParameter("l_sq_tabela", adInteger, adParamInput,   , tvl(p_sq_tabela))
     .parameters.Append l_chave
     .parameters.Append l_chave_aux
     .parameters.Append l_sq_tabela
     if Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetUsuarioTabs"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete "l_chave"
     .Parameters.Delete "l_chave_aux"
     .Parameters.Delete "l_sq_tabela"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera Tabelas
REM -------------------------------------------------------------------------
Sub DB_GetTabela(p_rs, p_cliente, p_chave, p_chave_aux, p_sistema, p_usuario, p_sq_tabela_tipo, p_nome, p_restricao)
  Dim l_cliente, l_chave,l_chave_aux, l_sistema, l_usuario, l_sq_sistema, l_sq_usuario, l_sq_tabela_tipo, l_nome, l_restricao
  Set l_cliente         = Server.CreateObject("ADODB.Parameter")
  Set l_chave           = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux       = Server.CreateObject("ADODB.Parameter")
  Set l_sistema         = Server.CreateObject("ADODB.Parameter")
  Set l_usuario         = Server.CreateObject("ADODB.Parameter")
  Set l_sq_tabela_tipo  = Server.CreateObject("ADODB.Parameter")
  Set l_nome            = Server.CreateObject("ADODB.Parameter")
  Set l_restricao       = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente             = .CreateParameter("l_cliente",          adInteger, adParamInput,   , p_cliente)
     set l_chave               = .CreateParameter("l_chave",            adInteger, adParamInput,   , tvl(p_chave))
     set l_chave_aux           = .CreateParameter("l_chave_aux",        adInteger, adParamInput,   , tvl(p_chave_aux))
     set l_sistema             = .CreateParameter("l_sistema",          adInteger, adParamInput,   , tvl(p_sistema))
     set l_usuario             = .CreateParameter("l_usuario",          adInteger, adParamInput,   , tvl(p_usuario))
     set l_sq_tabela_tipo      = .CreateParameter("l_sq_tabela_tipo",   adInteger, adParamInput,   , tvl(p_sq_tabela_tipo))
     set l_nome                = .CreateParameter("l_nome",             adVarchar, adParamInput, 30, tvl(p_nome))
     set l_restricao           = .CreateParameter("l_restricao",        adVarchar, adParamInput, 20, tvl(p_restricao))
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_sistema
     .parameters.Append         l_usuario
     .parameters.Append         l_sq_tabela_tipo
     .parameters.Append         l_nome
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetTabela"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_chave_aux"
     .Parameters.Delete         "l_sistema"
     .Parameters.Delete         "l_usuario"
     .Parameters.Delete         "l_sq_tabela_tipo"
     .Parameters.Delete         "l_nome"
     .Parameters.Delete         "l_restricao"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera Triggers
REM -------------------------------------------------------------------------
Sub DB_GetTrigger (p_rs, p_cliente, p_chave, p_sq_tabela, p_sq_sistema, p_sq_usuario)
  Dim l_cliente, l_chave, l_sq_tabela, l_sq_sistema, l_sq_usuario
  Set l_cliente                = Server.CreateObject("ADODB.Parameter")
  Set l_chave                  = Server.CreateObject("ADODB.Parameter")
  Set l_sq_tabela              = Server.CreateObject("ADODB.Parameter")
  Set l_sq_sistema             = Server.CreateObject("ADODB.Parameter")
  Set l_sq_usuario             = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente             = .CreateParameter("l_cliente",      adInteger, adParamInput,   , p_cliente)
     set l_chave               = .CreateParameter("l_chave"     ,   adInteger, adParamInput,   , tvl(p_chave))
     set l_sq_tabela           = .CreateParameter("l_sq_tabela" ,   adInteger, adParamInput,   , tvl(p_sq_tabela))
     set l_sq_sistema          = .CreateParameter("l_sq_sistema",   adInteger, adParamInput,   , tvl(p_sq_sistema))
     set l_sq_usuario          = .CreateParameter("l_sq_usuario",   adInteger, adParamInput,   , tvl(p_sq_usuario))
     .parameters.Append        l_cliente
     .parameters.Append        l_chave
     .parameters.Append        l_sq_tabela
     .parameters.Append        l_sq_sistema
     .parameters.Append        l_sq_usuario
     if Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText              = Session("schema") & "SP_GetTrigger"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype           = adOpenStatic
     p_rs.cursorlocation       = adUseClient
     On error Resume Next
     Set p_rs                  = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_sq_tabela"
     .Parameters.Delete         "l_sq_sistema"
     .Parameters.Delete         "l_sq_usuario"
   
   end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera Procedure
REM -------------------------------------------------------------------------
Sub DB_GetProcedure (p_rs, p_cliente, p_chave, p_sq_arquivo, p_sq_sistema, p_sq_sp_tipo, p_nome)
  Dim l_chave, l_sq_arquivo, l_sq_sistema, l_sq_sp_tipo, l_nome
  
  Set l_cliente         = Server.CreateObject("ADODB.Parameter")
  Set l_chave           = Server.CreateObject("ADODB.Parameter")
  Set l_sq_arquivo      = Server.CreateObject("ADODB.Parameter")
  Set l_sq_sistema      = Server.CreateObject("ADODB.Parameter")
  Set l_sq_sp_tipo      = Server.CreateObject("ADODB.Parameter")
  Set l_nome            = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente             = .CreateParameter("l_cliente",          adInteger, adParamInput,   , p_cliente)
     set l_chave               = .CreateParameter("l_chave",            adInteger, adParamInput,   , tvl(p_chave))
     set l_sq_arquivo          = .CreateParameter("l_sq_arquivo",       adInteger, adParamInput,   , tvl(p_sq_arquivo))
     set l_sq_sistema          = .CreateParameter("l_sq_sistema",       adInteger, adParamInput,   , tvl(p_sq_sistema))
     set l_sq_sp_tipo          = .CreateParameter("l_sq_sp_tipo",       adInteger, adParamInput,   , tvl(p_sq_sp_tipo))
     set l_nome                = .CreateParameter("l_nome",             adVarchar, adParamInput, 20, tvl(p_nome))

     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_sq_arquivo
     .parameters.Append         l_sq_sistema
     .parameters.Append         l_sq_sp_tipo
     .parameters.Append         l_nome

     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetProcedure"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If

     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_sq_arquivo"
     .parameters.Delete         "l_sq_sistema"
     .parameters.Delete         "l_sq_sp_tipo"
     .parameters.Delete         "l_nome"
     
  end with

End Sub
REM =========================================================================
REM Final da rotina Procedure
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera Stored Procedure
REM -------------------------------------------------------------------------
Sub DB_GetStoredProcedure (p_rs, p_cliente, p_chave, p_chave_aux, p_sq_sp_tipo, p_sq_usuario, p_sq_sistema, p_nome, p_restricao)
  Dim l_cliente, l_chave, l_chave_aux, l_sq_sp_tipo, l_sq_usuario, l_sq_sistema, l_nome, l_restricao
  
  Set l_cliente         = Server.CreateObject("ADODB.Parameter")
  Set l_chave           = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux       = Server.CreateObject("ADODB.Parameter")
  Set l_sq_sp_tipo      = Server.CreateObject("ADODB.Parameter")
  Set l_sq_usuario      = Server.CreateObject("ADODB.Parameter")
  Set l_sq_sistema      = Server.CreateObject("ADODB.Parameter")
  Set l_nome            = Server.CreateObject("ADODB.Parameter")
  Set l_restricao       = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente             = .CreateParameter("l_cliente",          adInteger, adParamInput,   , p_cliente)
     set l_chave               = .CreateParameter("l_chave",            adInteger, adParamInput,   , tvl(p_chave))
     set l_chave_aux           = .CreateParameter("l_chave_aux",        adInteger, adParamInput,   , tvl(p_chave_aux))
     set l_sq_sp_tipo          = .CreateParameter("l_sq_sp_tipo",       adInteger, adParamInput,   , tvl(p_sq_sp_tipo))
     set l_sq_usuario          = .CreateParameter("l_sq_usuario",       adInteger, adParamInput,   , tvl(p_sq_usuario))
     set l_sq_sistema          = .CreateParameter("l_sq_sistema",       adInteger, adParamInput,   , tvl(p_sq_sistema))
     set l_nome                = .CreateParameter("l_nome",             adVarchar, adParamInput, 20, tvl(p_nome))
     set l_restricao           = .CreateParameter("l_restricao",        adVarchar, adParamInput, 20, tvl(p_restricao))

     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_sq_sp_tipo
     .parameters.Append         l_sq_usuario
     .parameters.Append         l_sq_sistema
     .parameters.Append         l_nome
     .parameters.Append         l_restricao

     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetStoredProcedure"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If

     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_sq_sp_tipo"
     .parameters.Delete         "l_sq_usuario"
     .parameters.Delete         "l_sq_sistema"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_restricao"
     
  end with
End Sub
REM =========================================================================
REM Final da rotina Stored Procedure
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera Relacionamentos entre tabelas
REM -------------------------------------------------------------------------
Sub DB_GetRelacionamento (p_rs, p_cliente, p_chave, p_nome, p_sq_tabela, p_sq_sistema, p_sq_usuario)
  Dim l_cliente, l_chave, l_nome, l_sq_tabela, l_sq_sistema, l_sq_usuario
  
  Set l_cliente         = Server.CreateObject("ADODB.Parameter")
  Set l_chave           = Server.CreateObject("ADODB.Parameter")
  Set l_nome            = Server.CreateObject("ADODB.Parameter")
  Set l_sq_tabela       = Server.CreateObject("ADODB.Parameter")
  Set l_sq_sistema      = Server.CreateObject("ADODB.Parameter")
  Set l_sq_usuario      = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_cliente         = .CreateParameter("l_cliente",         adInteger, adParamInput,   , p_cliente)
     set l_chave           = .CreateParameter("l_chave"           ,adInteger, adParamInput,   , tvl(p_chave))
     set l_nome            = .CreateParameter("l_nome"            ,adVarchar, adParamInput, 20, tvl(p_nome))
     set l_sq_tabela       = .CreateParameter("l_sq_tabela"       ,adInteger, adParamInput,   , tvl(p_sq_tabela))
     set l_sq_sistema      = .CreateParameter("l_sq_sistema"      ,adInteger, adParamInput,   , tvl(p_sq_sistema))
     set l_sq_usuario      = .CreateParameter("l_sq_usuario"      ,adInteger, adParamInput,   , tvl(p_sq_usuario))
     
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_nome
     .parameters.Append         l_sq_tabela
     .parameters.Append         l_sq_sistema
     .parameters.Append         l_sq_usuario
     

     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetRelacionamento"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If

     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_sq_tabela"
     .parameters.Delete         "l_sq_sistema"
     .parameters.Delete         "l_sq_usuario"

     
  end with

End Sub
REM =========================================================================
REM Final da rotina Relacionamento
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera Relacionamento
REM -------------------------------------------------------------------------
Sub DB_GetColunaColuna (p_rs, p_chave, p_sq_coluna_pai, p_sq_coluna_filha)
  Dim l_chave, l_sq_coluna_pai, l_sq_coluna_filha
  
  Set l_chave           = Server.CreateObject("ADODB.Parameter")
  Set l_sq_coluna_pai   = Server.CreateObject("ADODB.Parameter")
  Set l_sq_coluna_filha = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_chave           = .CreateParameter("l_chave"          , adInteger, adParamInput,   , tvl(p_chave))
     set l_sq_coluna_pai   = .CreateParameter("l_sq_coluna_pai"  , adInteger, adParamInput,   , tvl(p_sq_coluna_pai))
     set l_sq_coluna_filha = .CreateParameter("l_sq_coluna_filha", adInteger, adParamInput,   , tvl(p_sq_coluna_filha))
     
     .parameters.Append         l_chave
     .parameters.Append         l_sq_coluna_pai  
     .parameters.Append         l_sq_coluna_filha
     

     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetColunaColuna"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If

     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_sq_coluna_pai"
     .parameters.Delete         "l_sq_coluna_filha"

     
  end with

End Sub
REM =========================================================================
REM Final da rotina Relacionamento
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera Índice
REM -------------------------------------------------------------------------
Sub DB_GetIndice (p_rs, p_cliente, p_chave, p_sq_indice_tipo, p_sq_usuario, p_sq_sistema, p_nome, p_sq_tabela)
  Dim l_cliente, l_chave, l_sq_indice_tipo, l_sq_usuario, l_sq_sistema, l_nome, l_sq_tabela
  
  Set l_cliente        = Server.CreateObject("ADODB.Parameter")
  Set l_chave          = Server.CreateObject("ADODB.Parameter")
  Set l_sq_indice_tipo = Server.CreateObject("ADODB.Parameter")
  Set l_sq_usuario     = Server.CreateObject("ADODB.Parameter")
  Set l_sq_sistema     = Server.CreateObject("ADODB.Parameter")
  Set l_nome           = Server.CreateObject("ADODB.Parameter")
  Set l_sq_tabela      = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente        = .CreateParameter("l_cliente",        adInteger, adParamInput,   , p_cliente)
     set l_chave          = .CreateParameter("l_chave"          ,adInteger, adParamInput,   , tvl(p_chave))
     set l_sq_indice_tipo = .CreateParameter("l_sq_indice_tipo" ,adInteger, adParamInput,   , tvl(p_sq_indice_tipo))
     set l_sq_usuario     = .CreateParameter("l_sq_usuario"     ,adInteger, adParamInput,   , tvl(p_sq_usuario))
     set l_sq_sistema     = .CreateParameter("l_sq_sistema"     ,adInteger, adParamInput,   , tvl(p_sq_sistema))
     set l_nome           = .CreateParameter("l_nome"           ,adVarchar, adParamInput, 30, tvl(p_nome))
     set l_sq_tabela      = .CreateParameter("l_sq_tabela"      ,adInteger, adParamInput,   , tvl(p_sq_tabela))

     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_sq_indice_tipo
     .parameters.Append         l_sq_usuario
     .parameters.Append         l_sq_sistema
     .parameters.Append         l_nome
     .parameters.Append         l_sq_tabela

     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetIndice"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If

     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_sq_indice_tipo"
     .parameters.Delete         "l_sq_usuario"
     .parameters.Delete         "l_sq_sistema"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_sq_tabela"
     
  end with

End Sub
REM =========================================================================
REM Final da rotina Índice
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera Índice
REM -------------------------------------------------------------------------

Sub DB_GetIndiceTabs (p_rs, p_chave, p_sq_usuario, p_sq_sistema, p_sq_tabela)
  Dim l_chave, l_sq_usuario, l_sq_sistema, l_sq_tabela
  
  Set l_chave      = Server.CreateObject("ADODB.Parameter")
  Set l_sq_usuario = Server.CreateObject("ADODB.Parameter")
  Set l_sq_sistema = Server.CreateObject("ADODB.Parameter")
  Set l_sq_tabela  = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave      = .CreateParameter("l_chave"     , adInteger, adParamInput,   , tvl(p_chave))
     set l_sq_usuario = .CreateParameter("l_sq_usuario", adInteger, adParamInput,   , tvl(p_sq_usuario))
     set l_sq_sistema = .CreateParameter("l_sq_sistema", adInteger, adParamInput,   , tvl(p_sq_sistema))
     set l_sq_tabela  = .CreateParameter("l_sq_tabela" , adInteger, adParamInput,   , tvl(p_sq_tabela))
  
     .parameters.Append         l_chave
     .parameters.Append         l_sq_usuario
     .parameters.Append         l_sq_sistema
     .parameters.Append         l_sq_tabela 
  
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetIndiceTabs"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If

     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_sq_usuario"
     .parameters.Delete         "l_sq_sistema"
     .parameters.Delete         "l_sq_tabela" 
  end with

End Sub
REM =========================================================================
REM Final da rotina Índice
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera Índice de colunas
REM -------------------------------------------------------------------------

Sub DB_GetIndiceCols (p_rs, p_sq_indice, p_sq_coluna)
  
  Dim l_sq_indice, l_sq_coluna
  
  Set l_sq_coluna  = Server.CreateObject("ADODB.Parameter")
  Set l_sq_indice  = Server.CreateObject("ADODB.Parameter")
    
  with sp
     
     set l_sq_indice = .CreateParameter("l_sq_indice"  ,adInteger, adParamInput,   , tvl(p_sq_indice))
     set l_sq_coluna = .CreateParameter("l_sq_coluna"  ,adInteger, adParamInput,   , tvl(p_sq_coluna))
             
     .parameters.Append l_sq_indice
     .parameters.Append l_sq_coluna
         
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetIndiceCols"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If

     .parameters.Delete "l_sq_indice"
     .parameters.Delete "l_sq_coluna"
                    
  end with

End Sub
REM =========================================================================
REM Final da rotina Índice
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera Índice de colunas
REM -------------------------------------------------------------------------

Sub DB_GetProcTabela (p_rs, p_sq_procedure, p_sq_tabela)
  
  Dim l_sq_procedure, l_sq_tabela
  
  Set l_sq_tabela    = Server.CreateObject("ADODB.Parameter")
  Set l_sq_procedure = Server.CreateObject("ADODB.Parameter")
    
  with sp
     
     set l_sq_procedure = .CreateParameter("l_sq_procedure"  ,adInteger, adParamInput,   , tvl(p_sq_procedure))
     set l_sq_tabela    = .CreateParameter("l_sq_tabela"     ,adInteger, adParamInput,   , tvl(p_sq_tabela))
             
     .parameters.Append l_sq_procedure
     .parameters.Append l_sq_tabela
         
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetProcTabela"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If

     .parameters.Delete "l_sq_procedure"
     .parameters.Delete "l_sq_tabela"
                    
  end with

End Sub
REM =========================================================================
REM Final da rotina Índice
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera Índice de colunas
REM -------------------------------------------------------------------------

Sub DB_GetProcSP (p_rs, p_sq_procedure, p_sq_sp)
  
  Dim l_sq_procedure, l_sq_sp
  
  Set l_sq_sp    = Server.CreateObject("ADODB.Parameter")
  Set l_sq_procedure = Server.CreateObject("ADODB.Parameter")
    
  with sp
     
     set l_sq_procedure = .CreateParameter("l_sq_procedure" ,adInteger, adParamInput,   , tvl(p_sq_procedure))
     set l_sq_sp        = .CreateParameter("l_sq_sp"        ,adInteger, adParamInput,   , tvl(p_sq_sp))
             
     .parameters.Append l_sq_procedure
     .parameters.Append l_sq_sp
         
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetProcSP"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If

     .parameters.Delete "l_sq_procedure"
     .parameters.Delete "l_sq_sp"
                    
  end with

End Sub
REM =========================================================================
REM Final da rotina Índice
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera Índice de colunas
REM -------------------------------------------------------------------------

Sub DB_GetProcTabs (p_rs, p_sq_procedure, p_sq_tabela)
  
  Dim l_sq_procedure, l_sq_tabela
  
  Set l_sq_tabela    = Server.CreateObject("ADODB.Parameter")
  Set l_sq_procedure = Server.CreateObject("ADODB.Parameter")
    
  with sp
     
     set l_sq_procedure = .CreateParameter("l_sq_procedure" ,adInteger, adParamInput,   , tvl(p_sq_procedure))
     set l_sq_tabela    = .CreateParameter("l_sq_tabela"    ,adInteger, adParamInput,   , tvl(p_sq_tabela))
             
     .parameters.Append l_sq_procedure
     .parameters.Append l_sq_tabela
         
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetProcSP"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If

     .parameters.Delete "l_sq_procedure"
     .parameters.Delete "l_sq_tabela"
                    
  end with

End Sub
REM =========================================================================
REM Final da rotina Índice
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os recursos de um projeto
REM -------------------------------------------------------------------------
Sub DB_GetTrigEventos(p_rs, p_chave, p_chave_aux)
  Dim l_chave, l_chave_aux
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",         adInteger, adParamInput,   , p_chave)
     set l_chave_aux            = .CreateParameter("l_chave_aux",     adInteger, adParamInput,   , Tvl(p_chave_aux))
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetTrigEventos"
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_chave_aux"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera Relacionamentos entre Colunas Pai e Filha
REM -------------------------------------------------------------------------
Sub DB_GetRelacCols (p_rs, p_chave, p_sq_coluna)
  Dim l_chave, l_sq_coluna
  
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_sq_coluna = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_chave     = .CreateParameter("l_chave"    ,adInteger, adParamInput,  , tvl(p_chave))
     set l_sq_coluna = .CreateParameter("l_sq_coluna",adInteger, adParamInput,  , tvl(p_sq_coluna))
     
     .parameters.Append         l_chave
     .parameters.Append         l_sq_coluna
     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetRelacCols"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If

     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_sq_coluna"
     
  end with

End Sub
REM =========================================================================
REM Final da rotina Relacionamento
REM -------------------------------------------------------------------------

%>
