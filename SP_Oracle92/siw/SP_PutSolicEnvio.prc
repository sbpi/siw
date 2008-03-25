create or replace procedure SP_PutSolicEnvio
   (p_menu                in number,
    p_chave               in number,
    p_pessoa              in number,
    p_tramite             in number,
    p_novo_tramite        in number    default null,    
    p_devolucao           in varchar2,
    p_despacho            in varchar2,
    p_caminho             in varchar2  default null,
    p_tamanho             in number    default null,
    p_tipo                in varchar2  default null,
    p_nome_original       in varchar2  default null
   ) is
   w_chave         number(18) := null;
   w_chave_arq     number(18) := null;
   w_tramite       number(18);
   w_sg_tramite    varchar2(2);
   w_menu          siw_menu%rowtype;
   w_cont          number(4);

begin
   -- Recupera os dados do serviço
   select * into w_menu from siw_menu where sq_menu = p_menu;
   
   -- Se houve mudança no trâmite atual, recupera o trâmite para o qual está sendo enviada a solicitação
   If p_tramite <> nvl(p_novo_tramite, 0) Then
      If p_devolucao = 'N' Then
         select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
            from siw_tramite a
           where a.sq_menu = p_menu
             and a.ordem   = (select ordem+1 from siw_tramite where sq_siw_tramite = p_tramite);
         
         If w_sg_tramite = 'PP' Then
            -- Se o trâmite for de pesquisa de preços e tiver o número necessário de pesquisas, pula para o próximo.
            select count(*)
              into w_cont
              from (select a.sq_solicitacao_item, coalesce(i.qtd_cotacao,0) as qtd
                      from cl_solicitacao_item a
                           left join (select y.sq_solicitacao_item, count(z.sq_item_fornecedor) as qtd_cotacao
                                        from siw_solicitacao                  x
                                             inner   join cl_solicitacao_item y on (x.sq_siw_solicitacao  = y.sq_siw_solicitacao)
                                               left  join cl_item_fornecedor  z on (y.sq_material         = z.sq_material and
                                                                                    'S'                   = z.pesquisa)
                                       where z.fim >= trunc(sysdate)
                                      group by y.sq_solicitacao_item
                                     )                        i on (a.sq_solicitacao_item  = i.sq_solicitacao_item)
                     where a.sq_siw_solicitacao = p_chave
                   )
             where qtd < 2;
            
            If w_cont = 0 Then
               select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
                  from siw_tramite a
                 where a.sq_menu = p_menu
                   and a.ordem   = (select ordem+1 from siw_tramite where sq_siw_tramite = w_tramite);
            End If;
         End If;
      Else
         select sq_siw_tramite, sigla into w_tramite, w_sg_tramite
            from siw_tramite a
           where a.sq_siw_tramite = p_novo_tramite;
      End If;
   Else
      w_tramite := p_tramite;
   End If;
   
   -- Recupera a próxima chave
   select sq_siw_solic_log.nextval into w_chave from dual;
    
   -- Se houve mudança de fase, grava o log
   Insert Into siw_solic_log 
       (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
        sq_siw_tramite,            data,               devolucao, 
        observacao
       )
   (Select 
        w_chave,                   p_chave,            p_pessoa,
        p_tramite,                 sysdate,            p_devolucao,
        case p_tramite 
          when p_novo_tramite then 'Anotação: '||chr(13)||chr(10)||p_despacho
          else case p_devolucao 
                   when 'S' then 'Devolução da fase "' 
                            else 'Envio da fase "' 
               end ||a.nome||'" '||
               ' para a fase "'||b.nome||'".'||
               case p_devolucao
                   when 'S' then chr(13)||chr(10)||'Despacho: '||chr(13)||chr(10)||p_despacho
                   else ''
               end
       end
       from siw_tramite a,
            siw_tramite b
      where a.sq_siw_tramite = p_tramite
        and b.sq_siw_tramite = w_tramite
   );
   Update siw_solicitacao set
      sq_siw_tramite        = w_tramite,
      conclusao             = null,
      executor              = null,
      observacao            = null,
      valor                 = case substr(w_menu.sigla,1,2) when 'CL' then valor else null end,
      opiniao               = null
   Where sq_siw_solicitacao = p_chave;

   -- Se foi informado um arquivo, grava.
   If p_caminho is not null Then
      -- Recupera a próxima chave
      select sq_siw_arquivo.nextval into w_chave_arq from dual;
       
      -- Insere registro em SIW_ARQUIVO
      insert into siw_arquivo (sq_siw_arquivo, cliente, nome, descricao, inclusao, tamanho, tipo, caminho, nome_original)
      (select w_chave_arq, sq_pessoa_pai, p_chave||' - Anexo', null, sysdate, 
              p_tamanho,   p_tipo,        p_caminho, p_nome_original
         from co_pessoa a
        where a.sq_pessoa = p_pessoa
      );
      
      -- Insere registro em SIW_SOLIC_LOG_ARQ
      insert into siw_solic_log_arq (sq_siw_solic_log, sq_siw_arquivo) values (w_chave, w_chave_arq);
   End If;

   commit;
      
end SP_PutSolicEnvio;
/
