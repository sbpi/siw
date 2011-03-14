create or replace procedure sp_putDocumentoVincula
   (p_chave               in  number,
    p_chave_dest          in  number,
    p_usuario             in  number
   ) is
   
   w_atual   number(18);
   w_texto   siw_solic_log.observacao%type;
   w_reg_atu pa_documento%rowtype;
   w_reg_dst pa_documento%rowtype;
begin
   -- Verifica se o processo já está vinculado
   select coalesce(protocolo_siw,0) into w_atual from siw_solicitacao where sq_siw_solicitacao = p_chave;
   
   
   If coalesce(p_chave_dest,-1) <> w_atual Then
     
     If w_atual > 0 Then
        -- Recupera dados do vínculo atual
        select * into w_reg_atu from pa_documento where sq_siw_solicitacao = w_atual;
     End If;
     
     If p_chave_dest is not null Then
        -- Recupera dados do novo vínculo
        select * into w_reg_dst from pa_documento where sq_siw_solicitacao = p_chave_dest;
     End If;
     
     If w_atual = 0 and p_chave_dest is null Then
        w_texto := null;
     Elsif w_atual = 0 and p_chave_dest is not null Then
       w_texto := 'Vinculação ao processo '||to_char(w_reg_dst.prefixo)||'.'||substr(1000000+to_char(w_reg_dst.numero_documento),2,6)||'/'||to_char(w_reg_dst.ano)||'-'||substr(100+to_char(w_reg_dst.digito),2,2)||'.';
     Elsif w_atual > 0 and p_chave_dest is null Then
       w_texto := 'Vinculação ao processo '||to_char(w_reg_dst.prefixo)||'.'||substr(1000000+to_char(w_reg_dst.numero_documento),2,6)||'/'||to_char(w_reg_dst.ano)||'-'||substr(100+to_char(w_reg_dst.digito),2,2)||' removida.';
     Elsif w_atual > 0 and p_chave_dest is not null Then
       w_texto := 'Vinculação ao processo '||
                  to_char(w_reg_atu.prefixo)||'.'||substr(1000000+to_char(w_reg_atu.numero_documento),2,6)||'/'||to_char(w_reg_atu.ano)||'-'||substr(100+to_char(w_reg_atu.digito),2,2)||' alterada para o processo '||
                  to_char(w_reg_dst.prefixo)||'.'||substr(1000000+to_char(w_reg_dst.numero_documento),2,6)||'/'||to_char(w_reg_dst.ano)||'-'||substr(100+to_char(w_reg_dst.digito),2,2)||'.';
     End If;

     If w_texto is not null Then
       -- Atualiza a vinculação
       update siw_solicitacao set protocolo_siw = p_chave_dest where sq_siw_solicitacao = p_chave;
       
        -- Registra os dados da autuação
        Insert Into siw_solic_log 
            (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
             sq_siw_tramite,            data,               devolucao, 
             observacao
            )
        (Select 
             sq_siw_solic_log.nextval,  p_chave,            p_usuario,
             a.sq_siw_tramite,          sysdate,            'N',
             w_texto
            from siw_solicitacao a
           where a.sq_siw_solicitacao = p_chave
        );
     End If;
   End If;
end sp_putDocumentoVincula;
/
