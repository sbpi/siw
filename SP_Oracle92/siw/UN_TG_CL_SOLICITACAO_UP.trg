create or replace trigger SIG.UN_TG_CL_SOLICITACAO_UP
  after update on SIG.Cl_Solicitacao
  for each row
/* Trigger específica para UNESCO
   Alimenta o portal de licitações quando uma licitação é atualizada
*/
declare
  w_dispara      number(1);
  w_codigo       siw_solicitacao.codigo_interno%type;
  w_nr_processo  dslicit.scl_lics.nr_processo%type;
  w_nr_lic       dslicit.scl_lics.nr_lic%type;
  w_situacao     lc_situacao.codigo_externo%type;
  lic            siw_solicitacao%rowtype;
  w_conducao_ubo varchar2(1);
  w_existe       number(2);
  w_chave        number(10);
begin
  -- Verifica se a trigger deve ser disparada
  select count(*) into w_dispara
    from siw_solicitacao a inner join siw_tramite c on (a.sq_siw_tramite = c.sq_siw_tramite)
   where a.sq_siw_solicitacao = :new.sq_siw_solicitacao
     and c.sigla              in ('EE', 'AT');

  If w_dispara > 0 Then
     -- Recupera os dados da solicitação
     select * into lic from siw_solicitacao where sq_siw_solicitacao = :new.sq_siw_solicitacao;
     
     -- Recupera o código da licitação no SIG
     w_codigo := upper(lic.codigo_interno);
     
     -- Ajusta valor do campo NR_PROCESSO
     select lpad(replace(substr(w_codigo,1,case instr(w_codigo,'/') when 0 then 50 else instr(w_codigo,'/')-1 end),nvl(a.prefixo,'LIC-'),''),4,'0')||'/'||
            substr(w_codigo,-4) -- Ano
       into w_nr_processo
       from siw_menu a
      where a.sq_menu = lic.sq_menu;
     
     -- Ajusta valor do campo NR_LIC
     w_nr_lic := substr(w_nr_processo,instr(w_nr_processo,'/')+1)||substr(w_nr_processo,1,instr(w_nr_processo,'/')-1);
     
     w_nr_processo := w_codigo;
     
     -- Recupera o código da situação no DSLICIT
     If :new.sq_lcsituacao is not null Then
        select codigo_externo into w_situacao from lc_situacao where sq_lcsituacao = :new.sq_lcsituacao;
     End If;
     -- Evita que o valor seja nulo
     w_situacao := nvl(w_situacao,1);
     
     -- Verifica se a condução da licitação é pelo UBO
     If :new.sq_lcmodalidade is not null Then
        select case sigla when 'CP' then 'N' else 'S' end into w_conducao_ubo
          from lc_modalidade
         where sq_lcmodalidade = :new.sq_lcmodalidade;
     Else
        -- Se a modalidade não foi indicada, atribui o valor 'N' para evitar que seja exibida no follow-up de licitações do FABS-WEB.
        w_conducao_ubo := 'N';
     End If;
     
      -- Verifica se a licitação já foi exportada para o DSLICIT
     select count(*) into w_existe from DSLICIT.SCL_LICS where nr_lic = w_nr_lic;
     
     If w_existe = 0 Then
        -- Se não existir, cria o registro
        
        -- Recupera a próxima chave
        select nvl(max(cd_lic),0)+1 into w_chave from DSLICIT.SCL_LICS;
        
        -- Insere o registro
        insert into DSLICIT.SCL_LICS
              (cd_lic,                      nr_processo,             cd_instituicao,              cd_acordo,                
               cd_projeto,                  cd_liccat,               cd_moeda,                    cd_agentefin,
               cd_agentefinrule,            ds_lic,                  cd_licenquad,                sn_noobj,
               nr_ref,                      dt_noobj,                cd_licobjeto,                cd_localentrega,
               ds_logradouro,               ds_bairro,               ds_municipio,                cd_uf,
               cd_cep,                      ds_especificacoes,       nr_propostavalidadedias,     cd_propostaapres,
               dh_propostarecebimento,      dh_propostaabertura,     dh_propostafechamento,       cd_setor, 
               cd_situacao,                 cd_projetoinc,           cd_usuarioinc,               vl_estimativa,
               dh_licinc,                   nr_lic,                  ds_obs,                      ds_justificativacontratacao,
               cd_ultimasituacao,           cd_historico,            vl_estimativareal,           cd_situacaoanterior, 
               id_usuarioinclusao,          nr_liccat_a,             nr_liccat_b,                 cd_ativo,
               ds_objetivoingles,           sn_exibesite,            dt_inclusaoweb,              nr_oficio,
               dt_oficio,                   sn_conduzidaunesco
              )
        select w_chave,                     w_nr_processo,           null,                        c.nivelsuperior,
               c.handle,                    0,                       e1.cd_moeda,                 null,
               null,                        null,                    null,                        0,
               null,                        null,                    null,                        0,
               null,                        null,                    null,                        null,
               null,                        null,                    :new.dias_validade_proposta,  0,
               :new.data_abertura,          :new.envelope_1,         null,                        null,
               w_situacao,                  c.handle,                u.cd_usuario,                lic.valor,
               sysdate,                     w_nr_lic,                lic.descricao,               null,
               null,                        null,                    0,                           null,
               null,                        null,                    null,                        'S',
               null,                        null,                    null,                        null,
               null,                        w_conducao_ubo
          from ct_cc                               b
               inner   join corporativo.ct_cc      c on (b.descricao     = c.handle),
               co_moeda                            e
               left    join dslicit.apo_moedas    e1 on (e1.cd_moeda     = case e.sigla when 'BRL' then 1 when 'USD' then 2 else 0 end),
               (select max(cd_usuario) cd_usuario from DSLICIT.APO_USUARIOS t where sn_administracao = 1) u              
         where b.sq_cc    = lic.sq_cc
           and e.sq_moeda = lic.sq_moeda;
     Else
        -- Caso contrário, atualiza dados manipulados no SIG
        update DSLICIT.SCL_LICS a
           set (cd_acordo,                  cd_projeto,              cd_moeda,                    nr_propostavalidadedias,
                dh_propostarecebimento,     dh_propostaabertura,     cd_projetoinc,               vl_estimativa,
                sn_conduzidaunesco,         nr_processo,             ds_obs,                      cd_situacao
               ) = 
        (select c.nivelsuperior,            c.handle,                e1.cd_moeda,                 :new.dias_validade_proposta,
                :new.data_abertura,         :new.envelope_1,         c.handle,                    lic.valor,
                w_conducao_ubo,             w_codigo,                lic.descricao,               w_situacao
           from ct_cc                               b
                inner   join corporativo.ct_cc      c on (b.descricao     = c.handle),
                co_moeda                            e
                left    join dslicit.apo_moedas    e1 on (e1.cd_moeda     = case e.sigla when 'BRL' then 1 when 'USD' then 2 else 0 end),
                (select max(cd_usuario) cd_usuario from DSLICIT.APO_USUARIOS t where sn_administracao = 1) u              
          where b.sq_cc    = lic.sq_cc
            and e.sq_moeda = lic.sq_moeda
        )
        where a.nr_lic = w_nr_lic;
     End If;
  End If;
end UN_TG_CL_SOLICITACAO_UP;
/
