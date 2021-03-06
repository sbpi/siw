create or replace procedure SP_PutViagemBenef
   ( p_operacao            in varchar2,
     p_restricao           in varchar2,
     p_chave               in number    default null,
     p_chave_aux           in number    default null,
     p_sq_pessoa           in number    default null,
     p_cpf                 in varchar2  default null,
     p_nome                in varchar2  default null,
     p_nome_resumido       in varchar2  default null,
     p_sexo                in varchar2  default null,
     p_rg_numero           in varchar2  default null,
     p_rg_emissao          in date      default null,
     p_rg_emissor          in varchar2  default null,
     p_ddd                 in varchar2  default null,
     p_nr_telefone         in varchar2  default null,
     p_nr_fax              in varchar2  default null,
     p_nr_passaporte       in varchar2  default null,
     p_sq_pais_passaporte  in number    default null,
     p_saida               in date      default null,
     p_retorno             in date      default null,
     p_valor               in number    default null,
     p_origem              in number    default null,
     p_destino             in number    default null,
     p_reserva             in varchar2  default null,
     p_bilhete             in varchar2  default null,
     p_trechos             in varchar2  default null,
     p_sq_viagem           in number    default null
   ) is
   
   w_existe          number(4);
   w_chave_pessoa    number(18) := Nvl(p_sq_pessoa,0);
   w_cidade          number(18);
   w_tipo_fone       number(18);
   w_chave_fone      number(18);
   w_sq_tipo_pessoa  number(18);
   w_sq_tipo_vinculo number(18);
begin
  
   -- Recupera a 
   If p_operacao = 'E' Then
      delete pd_viagem where sq_viagem = p_sq_viagem;
   Else
      -- Recupera a chave da tabela CO_TIPO_PESSOA para pessoa f�sica
      select sq_tipo_pessoa into w_sq_tipo_pessoa from co_tipo_pessoa where nome = 'F�sica';
       
      -- Recupera a cidade padr�o do cliente para definir a cidade
      select sq_cidade_padrao into w_cidade from siw_cliente where sq_pessoa = p_chave_aux;

      select count(*) into w_existe from co_pessoa_fisica where cliente = p_chave_aux and cpf = p_cpf;
      If w_existe > 0 Then
         select sq_pessoa into w_chave_pessoa from co_pessoa_fisica where cliente = p_chave_aux and cpf = p_cpf;
      End If;

      -- Carrega a chave da tabela CO_TIPO_VINCULO, dependendo do tipo da solicita��o
      select sq_tipo_vinculo into w_sq_tipo_vinculo from co_tipo_vinculo where nome = 'Cliente' and sq_tipo_pessoa = w_sq_tipo_pessoa and cliente = p_chave_aux;
       
      If w_chave_pessoa = 0 Then -- Se a chave da pessoa n�o foi informada, insere
    
         -- recupera a pr�xima chave da pessoa
         select sq_pessoa.nextval into w_chave_pessoa from dual;
         
         -- insere os dados da pessoa
         insert into co_pessoa
           (sq_pessoa,      sq_pessoa_pai, sq_tipo_vinculo,   sq_tipo_pessoa,   nome,   nome_resumido)
         values
           (w_chave_pessoa, p_chave_aux,   w_sq_tipo_vinculo, w_sq_tipo_pessoa, p_nome, p_nome_resumido);
      Else
         -- Se a pessoa existir, altera o nome e o nome resumido
         update co_pessoa set
            nome          = p_nome,
            nome_resumido = p_nome_resumido
         where sq_pessoa  = w_chave_pessoa;
      End If;
       
      -- Verifica se os dados de pessoa f�sica j� existem
      select count(*) into w_existe from co_pessoa_fisica where sq_pessoa = w_chave_pessoa;
       
      If w_existe = 0 Then -- Se n�o existir insere
         insert into co_pessoa_fisica
           (sq_pessoa,         rg_numero,   rg_emissor,   rg_emissao,   cpf,   cliente,
            passaporte_numero, sq_pais_passaporte, sexo)
         values
           (w_chave_pessoa, p_rg_numero, p_rg_emissor, p_rg_emissao, p_cpf, p_chave_aux,
            p_nr_passaporte, p_sq_pais_passaporte, p_sexo);
      Else -- Caso contr�rio, altera
         update co_pessoa_fisica
            set rg_numero          = p_rg_numero,
                rg_emissor         = p_rg_emissor,
                rg_emissao         = p_rg_emissao,
                cpf                = p_cpf,
                passaporte_numero  = p_nr_passaporte,
                sq_pais_passaporte = p_sq_pais_passaporte,
                sexo               = p_sexo
          where sq_pessoa = w_chave_pessoa;
      End If;
    
      If p_nr_telefone is not null Then
         -- Grava o telefone
         select count(*) into w_existe
           from co_pessoa_telefone          a
                inner join co_tipo_telefone b on (a.sq_tipo_telefone = b.sq_tipo_telefone)
          where a.sq_pessoa      = w_chave_pessoa
            and b.sq_tipo_pessoa = w_sq_tipo_pessoa
            and b.nome           = 'Comercial'
            and b.ativo          = 'S'
            and a.padrao         = 'S';
          
         If w_existe = 0 Then
            select sq_tipo_telefone into w_tipo_fone
              from co_tipo_telefone b
             where b.sq_tipo_pessoa = w_sq_tipo_pessoa
               and b.nome           = 'Comercial'
               and b.ativo          = 'S';
             
            insert into co_pessoa_telefone
              (sq_pessoa_telefone,         sq_pessoa,      sq_tipo_telefone, 
               sq_cidade,                  ddd,            numero, 
               padrao
              )
            values
              (sq_pessoa_telefone.nextval, w_chave_pessoa, w_tipo_fone, 
               w_cidade,                   p_ddd,          p_nr_telefone, 
               'S'
              );
         Else
            select sq_pessoa_telefone into w_chave_fone
              from co_pessoa_telefone          a
                   inner join co_tipo_telefone b on (a.sq_tipo_telefone = b.sq_tipo_telefone)
             where a.sq_pessoa      = w_chave_pessoa
               and b.sq_tipo_pessoa = w_sq_tipo_pessoa
               and b.nome           = 'Comercial'
               and b.ativo          = 'S'
               and a.padrao         = 'S';
                
            update co_pessoa_telefone
               set sq_cidade = w_cidade,
                   ddd       = p_ddd,
                   numero    = p_nr_telefone
             where sq_pessoa_telefone = w_chave_fone;
         End If;
      End If;
    
      -- Se foi informado o fax, grava. Caso contr�rio remove.
      select count(*) into w_existe
        from co_pessoa_telefone          a
             inner join co_tipo_telefone b on (a.sq_tipo_telefone = b.sq_tipo_telefone)
       where a.sq_pessoa      = w_chave_pessoa
         and b.sq_tipo_pessoa = w_sq_tipo_pessoa
         and b.nome           = 'Fax'
         and b.ativo          = 'S'
         and a.padrao         = 'S';
      
      If w_existe > 0 Then
         select sq_pessoa_telefone into w_chave_fone
           from co_pessoa_telefone          a
                inner join co_tipo_telefone b on (a.sq_tipo_telefone = b.sq_tipo_telefone)
          where a.sq_pessoa      = w_chave_pessoa
            and b.sq_tipo_pessoa = w_sq_tipo_pessoa
            and b.nome           = 'Fax'
            and b.ativo          = 'S'
            and a.padrao         = 'S';
      End If;
    
      If p_nr_fax is not null Then
         If w_existe = 0 Then
            select sq_tipo_telefone into w_tipo_fone
              from co_tipo_telefone b
             where b.sq_tipo_pessoa = w_sq_tipo_pessoa
               and b.nome           = 'Fax'
               and b.ativo          = 'S';
           
            insert into co_pessoa_telefone
              (sq_pessoa_telefone,         sq_pessoa,      sq_tipo_telefone, 
               sq_cidade,                  ddd,            numero, 
               padrao
              )
            values
              (sq_pessoa_telefone.nextval, w_chave_pessoa, w_tipo_fone, 
               w_cidade,                   p_ddd,          p_nr_fax, 
               'S'
              );
         Else
            update co_pessoa_telefone
               set sq_cidade = w_cidade,
                   ddd       = p_ddd,
                   numero    = p_nr_fax
             where sq_pessoa_telefone = w_chave_fone;
         End If;
      Else
         If w_existe > 0 Then
            delete co_pessoa_telefone where sq_pessoa_telefone = w_chave_fone;
         End If;
      End If;
      
      If p_operacao = 'I' Then
         insert into pd_viagem (sq_viagem, cliente, sq_siw_solicitacao, pessoa, origem, destino,
                                reserva,   trechos, bilhete,            saida,  retorno, valor) 
         values (sq_viagem.nextval, p_chave_aux, p_chave, w_chave_pessoa, p_origem, p_destino, 
                 p_reserva, p_trechos, p_bilhete, p_saida, p_retorno, Nvl(p_valor,0));
      ElsIf p_operacao = 'A' Then
         update pd_viagem
            set saida     = Nvl(p_saida, saida),
                retorno   = Nvl(p_retorno, retorno),
                valor     = Nvl(p_valor,valor),
                origem    = Nvl(p_origem, origem),
                destino   = Nvl(p_destino, destino),
                reserva   = Nvl(p_reserva, reserva),
                trechos   = Nvl(p_trechos, trechos),
                bilhete   = Nvl(p_bilhete, bilhete)
          where p_sq_viagem = sq_viagem;
      End If;
   End If;

end SP_PutViagemBenef;
/

