create or replace trigger SIG.UN_TG_SIW_SOLIC_UP
  after update on SIG.siw_solicitacao
  for each row
/* Trigger específica para UNESCO
   Alimenta o portal de licitações quando uma licitação é enviada da fase de cadastramento para a fase de execução.
*/
declare
  w_dispara      number(1);
  w_codigo       siw_solicitacao.codigo_interno%type;
  w_nr_processo  dslicit.scl_lics.nr_processo%type;
  w_nr_lic       dslicit.scl_lics.nr_lic%type;
  w_situacao     lc_situacao.codigo_externo%type;
  lic            cl_solicitacao%rowtype;
  w_conducao_ubo varchar2(1);
  w_existe       number(2);
  w_chave        number(10);
begin
  -- Verifica se a trigger deve ser disparada
  select count(*) into w_dispara
    from cl_solicitacao a, siw_menu b, siw_tramite c, siw_tramite d
   where a.sq_siw_solicitacao = :new.sq_siw_solicitacao and :old.sq_siw_tramite  <> :new.sq_siw_tramite
     and b.sq_menu            = :new.sq_menu            and b.sigla = 'CLLCCAD' -- É licitação
     and c.sq_siw_tramite     = :old.sq_siw_tramite -- Trâmite anterior
     and d.sq_siw_tramite     = :new.sq_siw_tramite -- Trâmite atual
     and ((-- Estava em cadastramento e foi para execução
           c.sigla = 'CI' and d.sigla = 'EE'
          ) or
          (-- Foi concluída, não importando o trâmite anterior
           d.sigla = 'AT'
          )
         );

  If w_dispara > 0 Then
     -- Recupera o código da licitação no SIG
     w_codigo := upper(:new.codigo_interno);
     
     -- Ajusta valor do campo NR_PROCESSO
     select lpad(replace(substr(w_codigo,1,case instr(w_codigo,'/') when 0 then 50 else instr(w_codigo,'/')-1 end),a.prefixo,''),4,'0')||'/'||
            substr(w_codigo,-4) -- Ano
       into w_nr_processo
       from siw_menu a
      where a.sq_menu = :new.sq_menu;
     
     -- Ajusta valor do campo NR_LIC
     w_nr_lic := substr(w_nr_processo,instr(w_nr_processo,'/')+1)||substr(w_nr_processo,1,instr(w_nr_processo,'/')-1);
     
     w_nr_processo := w_codigo;
     
     -- Recupera dados da tabela de licitação
     select * into lic from cl_solicitacao where sq_siw_solicitacao = :new.sq_siw_solicitacao;
     
     -- Recupera o código da situação no DSLICIT
     If lic.sq_lcsituacao is not null Then
        select codigo_externo into w_situacao from lc_situacao where sq_lcsituacao = lic.sq_lcsituacao;
     End If;
     -- Evita que o valor seja nulo
     w_situacao := nvl(w_situacao,1);
     
     -- Verifica se a condução da licitação é pelo UBO
     If lic.sq_lcmodalidade is not null Then
        select case sigla when 'CP' then 'N' else 'S' end into w_conducao_ubo
          from lc_modalidade
         where sq_lcmodalidade = lic.sq_lcmodalidade;
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
               null,                        null,                    lic.dias_validade_proposta,  0,
               lic.data_abertura,           lic.envelope_1,          null,                        null,
               w_situacao,                  c.handle,                u.cd_usuario,                :new.valor,
               sysdate,                     w_nr_lic,                :new.descricao,              null,
               null,                        null,                    0,                           null,
               null,                        null,                    null,                        'S',
               null,                        null,                    null,                        null,
               null,                        w_conducao_ubo
          from ct_cc                               b
               inner   join corporativo.ct_cc      c on (b.descricao     = c.handle),
               co_moeda                            e
               left    join dslicit.apo_moedas    e1 on (e1.cd_moeda     = case e.sigla when 'BRL' then 1 when 'USD' then 2 else 0 end),
               (select max(cd_usuario) cd_usuario from DSLICIT.APO_USUARIOS t where sn_administracao = 1) u              
         where b.sq_cc    = :new.sq_cc
           and e.sq_moeda = :new.sq_moeda;
     Else
        -- Caso contrário, atualiza dados manipulados no SIG
        update DSLICIT.SCL_LICS a
           set (cd_acordo,                  cd_projeto,              cd_moeda,                    nr_propostavalidadedias,
                dh_propostarecebimento,     dh_propostaabertura,     cd_projetoinc,               vl_estimativa,
                sn_conduzidaunesco,         nr_processo,             ds_obs,                      cd_situacao
               ) = 
        (select c.nivelsuperior,            c.handle,                e1.cd_moeda,                 lic.dias_validade_proposta,
                lic.data_abertura,          lic.envelope_1,          c.handle,                    :new.valor,
                w_conducao_ubo,             w_codigo,                :new.descricao,              w_situacao
           from ct_cc                               b
                inner   join corporativo.ct_cc      c on (b.descricao     = c.handle),
                co_moeda                            e
                left    join dslicit.apo_moedas    e1 on (e1.cd_moeda     = case e.sigla when 'BRL' then 1 when 'USD' then 2 else 0 end),
                (select max(cd_usuario) cd_usuario from DSLICIT.APO_USUARIOS t where sn_administracao = 1) u              
          where b.sq_cc    = :new.sq_cc
            and e.sq_moeda = :new.sq_moeda
        )
        where a.nr_lic = w_nr_lic;
     End If;
  End If;
end UN_TG_SIW_SOLIC_UP;
/
