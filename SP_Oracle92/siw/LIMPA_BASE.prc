create or replace procedure LIMPA_BASE(p_cliente in varchar2) is
  w_cliente number(18);
begin
  select a.sq_pessoa into w_cliente from co_pessoa a join siw_cliente b on (a.sq_pessoa = b.sq_pessoa) where a.nome_resumido_ind = upper(p_cliente);
  
  delete lignate;
  delete ligori;
  delete ligrec;
  delete tt_ligacao_log;
  delete tt_ligacao;
  delete tt_ramal_usuario;
  delete tt_usuario;
  delete tt_ramal;
  delete tt_tronco;
  delete tt_cc;
  delete tt_central;
  delete tt_prefixos;
  delete or_acao_prioridade;
  delete or_acoes;
  delete or_acao_financ;
  delete or_acao;
  delete or_importacao;
  delete or_prioridade;
  delete pd_diaria;
  delete pd_deslocamento;
  delete pd_missao_solic;
  delete pd_missao;
  delete pd_unidade_limite;
  delete pd_unidade;
  delete pd_usuario;
  delete pd_viagem;
  delete pd_cia_transporte;
  delete gd_demanda_log_arq;
  delete gd_demanda_log;
  delete gd_demanda_interes;
  delete gd_demanda_envolv;
  delete gd_demanda;
  delete pj_etapa_demanda;
  delete pj_etapa_mensal;
  delete pj_recurso_etapa;
  delete pj_etapa_contrato;
  delete pj_projeto_etapa;
  delete pj_projeto_envolv;
  delete pj_projeto_representante;
  delete pj_projeto_interes;
  delete pj_projeto_recurso;
  delete siw_solic_apoio;
  delete siw_solic_log_arq;
  delete pj_projeto_log_arq;
  delete pj_projeto_log;
  delete fn_lancamento_rubrica;
  delete fn_documento_item;
  delete pj_rubrica;
  delete pj_projeto;
  delete fn_imposto_doc;
  delete fn_imposto_incid;
  delete fn_imposto;
  delete fn_lancamento_log_arq;
  delete fn_lancamento_log;
  delete fn_lancamento_doc;
  delete fn_lancamento;
  delete fn_parametro;
  delete fn_tipo_documento;
  delete fn_tipo_lancamento;
  delete ac_acordo_log_arq;
  delete ac_acordo_log;
  delete ac_acordo_parcela;
  delete ac_acordo_outra_rep;
  delete ac_acordo_representante;
  delete ac_acordo_preposto;
  delete ac_acordo_outra_parte;
  delete ac_acordo;
  delete ac_parametro;
  delete ac_tipo_acordo;
  delete pe_programa_log_arq;
  delete pe_programa_log;
  delete pe_programa;
  delete pa_documento_log;
  delete pa_documento_interessado;
  delete pa_documento_assunto;
  delete pa_arquivo;
  delete pa_documento;
  delete pa_parametro;
  delete pa_especie_documento;
  delete pa_assunto;
  delete pa_tipo_guarda;
  delete pa_natureza_documento;
  delete pa_tipo_despacho;
  delete pa_unidade;
  delete siw_solic_log;
  delete lc_arquivo;
  delete lc_portal_contrato_item;
  delete lc_portal_contrato;
  delete lc_portal_lic_item;
  delete lc_portal_lic;
  delete lc_unidade_fornec;
  delete lc_unidade;
  delete lc_situacao;
  delete lc_modalidade;
  delete lc_julgamento;
  delete lc_fonte_recurso;
  delete lc_finalidade;
  delete gp_ferias_aviso;
  delete gp_ferias_interrupcao;
  delete gp_ferias;
  delete gp_afastamento_envio;
  delete gp_afastamento_modalidade;
  delete gp_afastamento;
  delete gp_contrato_colaborador;
  delete gp_colaborador;
  delete gp_modalidade_contrato;
  delete gp_tipo_afastamento;
  delete gp_parametro;
  delete siw_solic_arquivo;
  delete siw_solicitacao_interessado;
  delete siw_solic_indicador;
  delete siw_solic_meta;
  delete siw_solic_recurso_log;
  delete siw_solic_recurso_alocacao;
  delete siw_solic_recurso;
  delete eo_indicador_afericao;
  delete eo_indicador_aferidor;
  delete eo_indicador_agenda;
  delete eo_indicador;
  delete eo_recurso_indisponivel;
  delete eo_recurso_menu;
  delete eo_recurso_disponivel;
  delete eo_recurso;
  delete pe_plano_menu;
  delete pe_plano_arq;
  delete siw_solicitacao;
  delete pe_objetivo;
  delete pe_plano;
  delete pe_horizonte;
  delete pe_natureza;
  delete pe_unidade;
  delete siw_solicitacao_interessado;
  delete siw_tipo_interessado;
  delete siw_tipo_apoio;
  delete eo_tipo_recurso;
  delete eo_tipo_indicador;

  delete sg_perfil_menu a where a.sq_menu in (select sq_menu from siw_menu a where a.sq_pessoa not in (1,w_cliente));
  delete sg_pessoa_menu a where a.sq_menu in (select sq_menu from siw_menu a where a.sq_pessoa not in (1,w_cliente));
  delete sg_tramite_pessoa a where a.sq_siw_tramite in (select sq_siw_tramite from siw_tramite a where a.sq_menu in (select sq_menu from siw_menu a where a.sq_pessoa not in (1,w_cliente)));
  delete siw_menu_relac a where a.servico_cliente in (select sq_menu from siw_menu a where a.sq_pessoa not in (1,w_cliente));
  delete siw_tramite a where a.sq_menu in (select sq_menu from siw_menu a where a.sq_pessoa not in (1,w_cliente));
  delete siw_menu_forma_pag a where a.sq_menu in (select sq_menu from siw_menu a where a.sq_pessoa not in (1,w_cliente));
  delete siw_menu_endereco a where a.sq_menu in (select sq_menu from siw_menu a where a.sq_pessoa not in (1,w_cliente));
  delete siw_menu_arquivo a where a.sq_menu in (select sq_menu from siw_menu a where a.sq_pessoa not in (1,w_cliente));
  delete siw_pessoa_cc a where a.sq_menu in (select sq_menu from siw_menu a where a.sq_pessoa not in (1,w_cliente));
  delete siw_menu a where a.sq_pessoa not in (1,w_cliente);
  
  delete sg_autenticacao a where a.sq_pessoa in (select sq_pessoa from co_pessoa where sq_pessoa_pai not in (1,w_cliente));
  delete sg_pessoa_modulo a where a.sq_pessoa in (select sq_pessoa from co_pessoa where sq_pessoa_pai not in (1,w_cliente));

  delete co_forma_pagamento a where a.cliente in (select sq_pessoa from co_pessoa where sq_pessoa <> w_cliente and sq_pessoa_pai <> w_cliente);
  delete co_pessoa_conta a where a.sq_pessoa in (select sq_pessoa from co_pessoa where sq_pessoa <> w_cliente and sq_pessoa_pai <> w_cliente);
  delete co_pessoa_endereco a where a.sq_pessoa in (select sq_pessoa from co_pessoa where (sq_pessoa not in (1,w_cliente) and sq_pessoa_pai not in (1,w_cliente)));
  delete co_pessoa_telefone a where a.sq_pessoa in (select sq_pessoa from co_pessoa where sq_pessoa <> w_cliente and sq_pessoa_pai <> w_cliente);
  delete co_pessoa_fisica a where a.sq_pessoa in (select sq_pessoa from co_pessoa where sq_pessoa <> w_cliente and sq_pessoa_pai <> w_cliente);
  delete co_pessoa_juridica a where a.sq_pessoa not in (1,w_cliente) and a.sq_pessoa not in (1,w_cliente);
  delete co_pessoa_segmento a where a.sq_pessoa not in (1,w_cliente);

  delete cv_pessoa_area a where a.sq_pessoa in (select sq_pessoa from co_pessoa where sq_pessoa_pai <> w_cliente);
  delete cv_pessoa_cargo a where a.sq_cvpesexp in (select x.sq_cvpesexp from cv_pessoa_exp x where x.sq_pessoa in (select sq_pessoa from co_pessoa where sq_pessoa_pai <> w_cliente));
  delete cv_pessoa_curso a where a.sq_pessoa in (select sq_pessoa from co_pessoa where sq_pessoa_pai <> w_cliente);
  delete cv_pessoa_escol a where a.sq_pessoa in (select sq_pessoa from co_pessoa where sq_pessoa_pai <> w_cliente);
  delete cv_pessoa_exp a where a.sq_pessoa in (select sq_pessoa from co_pessoa where sq_pessoa_pai <> w_cliente);
  delete cv_pessoa_hist a where a.sq_pessoa in (select sq_pessoa from co_pessoa where sq_pessoa_pai <> w_cliente);
  delete cv_pessoa_idioma a where a.sq_pessoa in (select sq_pessoa from co_pessoa where sq_pessoa_pai <> w_cliente);
  delete cv_pessoa_prod a where a.sq_pessoa in (select sq_pessoa from co_pessoa where sq_pessoa_pai <> w_cliente);
  delete cv_pessoa a where a.sq_pessoa in (select sq_pessoa from co_pessoa where sq_pessoa_pai <> w_cliente);

  delete eo_unidade_resp a where a.sq_pessoa in (select sq_pessoa from co_pessoa where sq_pessoa_pai not in (1,w_cliente));

  delete co_forma_pagamento a where a.cliente in (select sq_pessoa from co_pessoa where sq_pessoa not in (1,w_cliente) and sq_pessoa_pai not in (1,w_cliente));

  delete eo_data_especial a where a.cliente not in (1,w_cliente);
  delete eo_institucional a where a.sq_unidade in (select sq_unidade from eo_unidade a where a.sq_pessoa not in (1,w_cliente));
  delete eo_posto_trabalho a where a.cliente not in (1,w_cliente);
  delete eo_produto a where a.cliente not in (1,w_cliente);
  delete eo_servico a where a.cliente not in (1,w_cliente);
  delete eo_tipo_posto a where a.cliente not in (1,w_cliente);
  delete eo_localizacao a where a.sq_unidade in (select sq_unidade from eo_unidade a where a.sq_pessoa not in (1,w_cliente));
  delete eo_unidade a where a.sq_pessoa not in (1,w_cliente);
  delete eo_area_atuacao a where a.sq_pessoa not in (1,w_cliente);
  delete eo_tipo_unidade a where a.sq_pessoa not in (1,w_cliente);
  
  delete ct_cc a where a.cliente not in (1,w_cliente);
  delete siw_cliente_modulo a where a.sq_pessoa not in (1,w_cliente);
  
  delete dc_arquivo a where a.sq_sistema in (select sq_sistema from dc_sistema x where x.cliente <> w_cliente);
  delete dc_indice_cols a where a.sq_indice in (select sq_indice from dc_indice z where z.sq_sistema in (select sq_sistema from dc_sistema x where x.cliente <> w_cliente));
  delete dc_esquema_atributo a where a.sq_coluna in (select sq_coluna from dc_coluna z where z.sq_tabela in (select sq_tabela from dc_tabela where sq_sistema in (select sq_sistema from dc_sistema x where x.cliente <> w_cliente)));
  delete dc_relac_cols a where a.sq_relacionamento in (select sq_relacionamento from dc_relacionamento z where z.sq_sistema in (select sq_sistema from dc_sistema x where x.cliente <> w_cliente));
  delete dc_esquema_insert a where a.sq_esquema_tabela in (select sq_esquema_tabela from dc_esquema_tabela a where a.sq_esquema in (select sq_esquema from dc_esquema x where x.cliente <> w_cliente));
  delete dc_coluna a where a.sq_tabela in (select sq_tabela from dc_tabela where sq_sistema in (select sq_sistema from dc_sistema x where x.cliente <> w_cliente));
  delete dc_esquema_tabela a where a.sq_esquema in (select sq_esquema from dc_esquema x where x.cliente <> w_cliente);
  delete dc_ocorrencia a where a.sq_esquema in (select sq_esquema from dc_esquema x where x.cliente <> w_cliente);
  delete dc_esquema a where a.cliente <> w_cliente;
  delete dc_indice a where a.sq_sistema in (select sq_sistema from dc_sistema x where x.cliente <> w_cliente);
  delete dc_proc_param a where a.sq_procedure in (select sq_procedure from dc_procedure where sq_sistema in (select sq_sistema from dc_sistema x where x.cliente <> w_cliente));
  delete dc_proc_sp a where a.sq_procedure in (select sq_procedure from dc_procedure where sq_sistema in (select sq_sistema from dc_sistema x where x.cliente <> w_cliente));
  delete dc_proc_tabela a where a.sq_procedure in (select sq_procedure from dc_procedure where sq_sistema in (select sq_sistema from dc_sistema x where x.cliente <> w_cliente));
  delete dc_procedure a where a.sq_sistema in (select sq_sistema from dc_sistema x where x.cliente <> w_cliente);
  delete dc_relacionamento a where a.sq_sistema in (select sq_sistema from dc_sistema x where x.cliente <> w_cliente);
  delete dc_sp_param a where a.sq_stored_proc in (select sq_stored_proc from dc_stored_proc where sq_sistema in (select sq_sistema from dc_sistema x where x.cliente <> w_cliente));
  delete dc_sp_sp a where a.sp_pai in (select sq_stored_proc from dc_stored_proc where sq_sistema in (select sq_sistema from dc_sistema x where x.cliente <> w_cliente));
  delete dc_sp_tabs a where a.sq_stored_proc in (select sq_stored_proc from dc_stored_proc where sq_sistema in (select sq_sistema from dc_sistema x where x.cliente <> w_cliente));
  delete dc_stored_proc a where sq_sistema in (select sq_sistema from dc_sistema x where x.cliente <> w_cliente);
  delete dc_trigger_evento a where a.sq_trigger in (select sq_trigger from dc_trigger where sq_sistema in (select sq_sistema from dc_sistema x where x.cliente <> w_cliente));
  delete dc_trigger a where a.sq_sistema in (select sq_sistema from dc_sistema x where x.cliente <> w_cliente);
  delete dc_tabela a where a.sq_sistema in (select sq_sistema from dc_sistema x where x.cliente <> w_cliente);
  delete dc_usuario a where a.sq_sistema in (select sq_sistema from dc_sistema x where x.cliente <> w_cliente);
  delete dc_sistema a where a.cliente <> w_cliente;
  delete siw_arquivo;
  update co_pessoa a set a.sq_tipo_vinculo = null where sq_pessoa not in (1,w_cliente) and sq_pessoa_pai not in (1,w_cliente);
  delete co_tipo_vinculo a where a.cliente not in (1,w_cliente);
  delete siw_cliente a where a.sq_pessoa not in (1,w_cliente);
  delete co_pessoa where sq_pessoa not in (1,w_cliente) and sq_pessoa_pai not in (1,w_cliente);

end LIMPA_BASE;
/