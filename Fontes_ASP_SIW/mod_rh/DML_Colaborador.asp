<%
REM =========================================================================
REM Mantém os dados de um do colaborador
REM -------------------------------------------------------------------------
Sub DML_PutGPColaborador(Operacao, p_cliente, p_sq_pessoa, p_ctps_numero, p_ctps_serie, p_ctps_emissor, p_ctps_emissao, _
                         p_pis_pasep, p_pispasep_numero, p_pispasep_cadastr, p_te_numero, p_te_zona, _
                         p_te_secao, p_reservista_numero, p_reservista_csm, p_tipo_sangue, p_doador_sangue, _
                         p_doador_orgaos, p_observacoes)

  Dim l_Operacao, l_cliente, l_sq_pessoa, l_ctps_numero, l_ctps_serie, l_ctps_emissor, l_ctps_emissao
  Dim l_pis_pasep, l_pispasep_numero, l_pispasep_cadastr, l_te_numero, l_te_zona, l_te_secao
  Dim l_reservista_numero, l_reservista_csm, l_tipo_sangue, l_doador_sangue, l_doador_orgaos, l_observacoes
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_cliente             = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_pessoa           = Server.CreateObject("ADODB.Parameter") 
  Set l_ctps_numero         = Server.CreateObject("ADODB.Parameter") 
  Set l_ctps_serie          = Server.CreateObject("ADODB.Parameter") 
  Set l_ctps_emissor        = Server.CreateObject("ADODB.Parameter")
  Set l_ctps_emissao        = Server.CreateObject("ADODB.Parameter")
  Set l_pis_pasep           = Server.CreateObject("ADODB.Parameter")
  Set l_pispasep_numero     = Server.CreateObject("ADODB.Parameter") 
  Set l_pispasep_cadastr    = Server.CreateObject("ADODB.Parameter")
  Set l_te_numero           = Server.CreateObject("ADODB.Parameter") 
  Set l_te_zona             = Server.CreateObject("ADODB.Parameter") 
  Set l_te_secao            = Server.CreateObject("ADODB.Parameter") 
  Set l_reservista_numero   = Server.CreateObject("ADODB.Parameter") 
  Set l_reservista_csm      = Server.CreateObject("ADODB.Parameter")
  Set l_tipo_sangue         = Server.CreateObject("ADODB.Parameter") 
  Set l_doador_sangue       = Server.CreateObject("ADODB.Parameter") 
  Set l_doador_orgaos       = Server.CreateObject("ADODB.Parameter") 
  Set l_observacoes         = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,    , p_cliente)
     set l_sq_pessoa            = .CreateParameter("l_sq_pessoa",           adInteger, adParamInput,    , p_sq_pessoa)
     set l_ctps_numero          = .CreateParameter("l_ctps_numero",         adVarchar, adParamInput,  20, Tvl(p_ctps_numero))
     set l_ctps_serie           = .CreateParameter("l_ctps_serie",          adVarchar, adParamInput,   5, Tvl(p_ctps_serie))
     set l_ctps_emissor         = .CreateParameter("l_ctps_emissor",        adVarchar, adParamInput,  30, Tvl(p_ctps_emissor))
     set l_ctps_emissao         = .CreateParameter("l_ctps_emissao",        adDate,    adParamInput,    , Tvl(p_ctps_emissao))
     set l_pis_pasep            = .CreateParameter("l_pis_pasep",           adVarchar, adParamInput,   1, p_pis_pasep)
     set l_pispasep_numero      = .CreateParameter("l_pispasep_numero",     adVarchar, adParamInput,  20, Tvl(p_pispasep_numero))
     set l_pispasep_cadastr     = .CreateParameter("l_pispasep_cadastr",    adDate,   adParamInput,    ,  Tvl(p_pispasep_cadastr))
     set l_te_numero            = .CreateParameter("l_te_numero",           adVarchar, adParamInput,  20, Tvl(p_te_numero))
     set l_te_zona              = .CreateParameter("l_te_zona",             adVarchar, adParamInput,   3, Tvl(p_te_zona))
     set l_te_secao             = .CreateParameter("l_te_secao",            adVarchar, adParamInput,   4, Tvl(p_te_secao))
     set l_reservista_numero    = .CreateParameter("l_reservista_numero",   adVarchar, adParamInput,  15, Tvl(p_reservista_numero))
     set l_reservista_csm       = .CreateParameter("l_reservista_csm",      adVarchar, adParamInput,   4, Tvl(p_reservista_csm))
     set l_tipo_sangue          = .CreateParameter("l_tipo_sangue",         adVarchar, adParamInput,   5, Tvl(p_tipo_sangue))
     set l_doador_sangue        = .CreateParameter("l_doador_sangue",       adVarchar, adParamInput,   1, Tvl(p_doador_sangue))
     set l_doador_orgaos        = .CreateParameter("l_doador_orgaos",       adVarchar, adParamInput,   1, Tvl(p_doador_orgaos))
     set l_observacoes          = .CreateParameter("l_observacoes",         adVarchar, adParamInput,2000, Tvl(p_observacoes))
     
     .parameters.Append         l_Operacao
     .parameters.Append         l_cliente
     .parameters.Append         l_sq_pessoa
     .parameters.Append         l_ctps_numero
     .parameters.Append         l_ctps_serie
     .parameters.Append         l_ctps_emissor
     .parameters.Append         l_ctps_emissao
     .parameters.Append         l_pis_pasep
     .parameters.Append         l_pispasep_numero
     .parameters.Append         l_pispasep_cadastr
     .parameters.Append         l_te_numero
     .parameters.Append         l_te_zona
     .parameters.Append         l_te_secao
     .parameters.Append         l_reservista_numero
     .parameters.Append         l_reservista_csm
     .parameters.Append         l_tipo_sangue
     .parameters.Append         l_doador_sangue
     .parameters.Append         l_doador_orgaos
     .parameters.Append         l_observacoes
     
     .CommandText               = Session("schema") & "SP_PutGPColaborador"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_sq_pessoa"
     .parameters.Delete         "l_ctps_numero"
     .parameters.Delete         "l_ctps_serie"
     .parameters.Delete         "l_ctps_emissor"
     .parameters.Delete         "l_ctps_emissao"
     .parameters.Delete         "l_pis_pasep"
     .parameters.Delete         "l_pispasep_numero"
     .parameters.Delete         "l_pispasep_cadastr"
     .parameters.Delete         "l_te_numero"
     .parameters.Delete         "l_te_zona"
     .parameters.Delete         "l_te_secao"
     .parameters.Delete         "l_reservista_numero"
     .parameters.Delete         "l_reservista_csm"
     .parameters.Delete         "l_tipo_sangue"
     .parameters.Delete         "l_doador_sangue"
     .parameters.Delete         "l_doador_orgaos"
     .parameters.Delete         "l_observacao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>