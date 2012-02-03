create or replace procedure SP_PutCaixa
   (p_operacao                 in  varchar2,
    p_cliente                  in  number,
    p_chave                    in  number   default null,
    p_sq_unidade               in  number   default null,
    p_sq_arquivo_local         in  number   default null,
    p_assunto                  in  varchar2 default null,
    p_descricao                in  varchar2 default null,
    p_data_limite              in  date     default null,
    p_numero                   in  number   default null,
    p_intermediario            in  varchar2 default null,
    p_destinacao_final         in  varchar2 default null,
    p_arquivo_data             in  date     default null,
    p_arquivo_guia_numero      in  number   default null,
    p_arquivo_guia_ano         in  number   default null,
    p_elimin_data              in  date     default null,
    p_elimin_guia_numero       in  number   default null,
    p_elimin_guia_ano          in  number   default null,
    p_chave_nova               out number
   ) is
   w_chave         number(10);
   w_numero        number(10);
   w_assunto       pa_caixa.assunto%type;
   w_descricao     pa_caixa.descricao%type;

   w_cont          number(18);
   w_dados_caixa   varchar2(4000);
   w_limite        varchar2(255);
   w_intermediario varchar2(255);
   w_final         varchar2(255);
   w_texto         varchar2(1000);
   
  cursor c_caixas is
    select sq_caixa, retornaLimiteCaixa(sq_caixa)||'|@|' dados_caixa
      from (select distinct d.sq_caixa
              from pa_caixa                     d
                   inner   join pa_documento    c on (d.sq_caixa           = c.sq_caixa)
                     inner join siw_solicitacao e on (c.sq_siw_solicitacao = e.sq_siw_solicitacao)
             where d.cliente        = p_cliente
               and (p_chave is null or (p_chave is not null and d.sq_caixa = p_chave))
               and e.sq_siw_tramite <> (select sq_siw_tramite from siw_tramite where sq_menu = e.sq_menu and sigla = 'CA')
           );
begin
   If p_operacao = 'I' Then
      -- Recupera o próximo número da caixa para unidade informada
      select coalesce(numero_caixa,0)+1 into w_numero from pa_unidade where sq_unidade = p_sq_unidade;
      
      -- Atualiza o valor do número da caixa
      update pa_unidade set numero_caixa = w_numero where sq_unidade = p_sq_unidade;
      
      -- Prepara valores para campos obrigatórios caso não sejam recebidos
      w_assunto   := coalesce(p_assunto,'Assunto');
      w_descricao := coalesce(p_descricao,'Descrição');
      
      -- Recupera a próxima chave
      select sq_caixa.nextval into w_chave from dual;
      
      -- Insere registro
      insert into pa_caixa
        (sq_caixa,         cliente,   sq_unidade,    assunto,   descricao,   data_limite,   numero, 
         intermediario,    destinacao_final)
      values
        (w_chave,          p_cliente, p_sq_unidade,  w_assunto, w_descricao, p_data_limite, w_numero, 
         p_intermediario,  p_destinacao_final);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pa_caixa
         set cliente          = p_cliente,
             assunto          = p_assunto,
             descricao        = p_descricao,
             data_limite      = p_data_limite, 
             intermediario    = p_intermediario,
             destinacao_final = p_destinacao_final
       where sq_caixa = p_chave;

   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete pa_caixa where sq_caixa = p_chave;
   Elsif p_operacao = 'U' Then
      -- Atualiza dados da caixa a partir da função RetornaLimiteCaixa
      For crec in c_caixas Loop
         w_cont := 0;
         -- Se estiver em uma caixa, atualiza os dados dela
         w_dados_caixa := crec.dados_caixa;
         Loop
            w_cont := w_cont + 1;
            w_texto := substr(w_dados_caixa,1,instr(w_dados_caixa,'|@|')-1);
            If    w_cont = 1 Then w_limite        := w_texto;
            Elsif w_cont = 2 then w_intermediario := w_texto;
            Elsif w_cont = 3 then w_final         := w_texto;
            Elsif w_cont = 4 then w_assunto       := substr(w_texto,1,800);
            Else                  w_descricao     := substr(w_texto,1,2000);
            End If;
            If w_cont > 4 Then Exit; End If;
            w_dados_caixa := substr(w_dados_caixa,instr(w_dados_caixa,'|@|')+3);
         End Loop;
         update pa_caixa
            set assunto             = substr(w_assunto,1,800),
                descricao           = substr(w_descricao,1,2000),
                data_limite         = w_limite,
                intermediario       = substr(w_intermediario,1,400),
                destinacao_final    = substr(w_final,1,40)
         where sq_caixa = crec.sq_caixa;
      End Loop;
   End If;

   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;
end SP_PutCaixa;
/
