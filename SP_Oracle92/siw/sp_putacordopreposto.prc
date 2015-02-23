create or replace procedure SP_PutAcordoPreposto
   ( p_operacao               in varchar2,
     p_tipo                   in number,
     p_chave                  in number    default null,
     p_sq_acordo_outra_parte  in number    default null,
     p_sq_pessoa              in number    default null,
     p_cliente                in number    default null,
     p_cargo                  in varchar2  default null,
     p_nome                   in varchar2  default null,
     p_nome_resumido          in varchar2  default null,
     p_sexo                   in varchar2  default null,
     p_rg_numero              in varchar2  default null,
     p_rg_emissao             in date      default null,
     p_rg_emissor             in varchar2  default null,
     p_passaporte             in varchar2  default null,
     p_sq_pais_passaporte     in number    default null,
     p_ddd                    in varchar2  default null,
     p_nr_telefone            in varchar2  default null,
     p_nr_fax                 in varchar2  default null,
     p_nr_celular             in varchar2  default null,
     p_email                  in varchar2  default null
   ) is
   
   w_existe          number(18);
   w_chave           number(18) := p_chave;
   w_chave_pessoa    number(18) := Nvl(p_sq_pessoa,0);
   w_outra_parte1    number(18);
   w_outra_parte2    number(18);
   w_preposto        number(18);
   w_cidade          co_cidade.sq_cidade%type;
   w_pessoa          co_pessoa%rowtype;
   w_pf              co_pessoa_fisica%rowtype;
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into ac_acordo_outra_rep
         (sq_siw_solicitacao, sq_acordo_outra_parte,   sq_pessoa,      cargo,   tipo)
      values
         (p_chave,            p_sq_acordo_outra_parte, w_chave_pessoa, p_cargo, p_tipo);
      
      select nvl(preposto,0) into w_existe from ac_acordo where sq_siw_solicitacao = p_chave;
      If w_existe = 0 Then
         select outra_parte into w_outra_parte1 from ac_acordo             where sq_siw_solicitacao = p_chave;
         select outra_parte into w_outra_parte2 from ac_acordo_outra_parte where sq_acordo_outra_parte = p_sq_acordo_outra_parte;
         If w_outra_parte1 = w_outra_parte2 Then
           update ac_acordo set preposto = w_chave_pessoa where sq_siw_solicitacao = p_chave;
         End If;
      End If;      
   Elsif  p_operacao = 'A' Then 
      -- Altera cargo do PREPOSTO
      update ac_acordo_outra_rep
         set cargo = p_cargo
      where sq_pessoa             = w_chave_pessoa
        and sq_acordo_outra_parte = p_sq_acordo_outra_parte
        and sq_siw_solicitacao    = p_chave;
   Elsif p_operacao = 'E' Then
      select count(*) into w_existe from ac_acordo_outra_rep where tipo = p_tipo and sq_acordo_outra_parte = p_sq_acordo_outra_parte;
         
      delete ac_acordo_outra_rep
       where sq_acordo_outra_parte = p_sq_acordo_outra_parte
         and tipo                  = p_tipo
         and sq_pessoa             = w_chave_pessoa;

      If p_tipo = 1 Then
         -- Se representante legal (preposto)
         If w_existe > 1 Then
           select sq_pessoa into w_preposto 
             from ac_acordo_outra_rep 
            where sq_acordo_outra_parte = p_sq_acordo_outra_parte
              and tipo                  = p_tipo
              and rownum = 1;
            
           update ac_acordo set preposto = w_preposto
            where sq_siw_solicitacao = w_chave
              and preposto           = w_chave_pessoa;
         Else
           update ac_acordo set preposto = null         
            where sq_siw_solicitacao = w_chave
              and preposto           = w_chave_pessoa;
         End If;
      End If;
   End If;
   If p_operacao = 'I' or p_operacao = 'A' Then
      -- Recupera a cidade padrão do cliente para definir a cidade
      select sq_cidade_padrao into w_cidade from siw_cliente where sq_pessoa = p_cliente;

      -- Recupera dados da pessoa informada
      select * into w_pessoa from co_pessoa        where sq_pessoa = w_chave_pessoa;
      select * into w_pf     from co_pessoa_fisica where sq_pessoa = w_chave_pessoa;
      
      -- Atualiza os dados da pessoa
      sp_putpessoa(p_operacao           => 'A',
                   p_cliente            => p_cliente,
                   p_restricao          => 'FORNECEDOR',
                   p_tipo_pessoa        => w_pessoa.sq_tipo_pessoa,
                   p_tipo_vinculo       =>w_pessoa.sq_tipo_vinculo,
                   p_sq_pessoa          => w_chave_pessoa,
                   p_cpf                => w_pf.cpf,
                   p_cnpj               => null,
                   p_nome               => p_nome,
                   p_nome_resumido      => p_nome_resumido,
                   p_sexo               => p_sexo,
                   p_nascimento         => w_pf.nascimento,
                   p_rg_numero          => p_rg_numero,
                   p_rg_emissao         => p_rg_emissao,
                   p_rg_emissor         => p_rg_emissor,
                   p_passaporte         => p_passaporte,
                   p_sq_pais_passaporte => p_sq_pais_passaporte,
                   p_inscricao_estadual => null,
                   p_logradouro         => null,
                   p_complemento        => null,
                   p_bairro             => null,
                   p_sq_cidade          => w_cidade,
                   p_cep                => null,
                   p_ddd                => p_ddd,
                   p_nr_telefone        => p_nr_telefone,
                   p_nr_fax             => p_nr_fax,
                   p_nr_celular         => p_nr_celular,
                   p_email              => p_email,
                   p_codigo_externo     => w_pessoa.codigo_externo,
                   p_chave_nova         => w_chave_pessoa);
   End If;
end SP_PutAcordoPreposto;
/
