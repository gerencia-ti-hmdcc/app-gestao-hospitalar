<?php
$variavel_controle_margem_tv = 4;
if ($link_pagina == 'dashboard') {
    if ($tipo_perfil == 'P') {
        $variavel_controle_margem_tv = 2;
        $usuario_logado = $this->session->userdata("usuario_logado");
        if (isset($usuario_logado["painel_variavel_controle"])) {
            $usuario_logado["painel_variavel_controle"] = $usuario_logado["painel_variavel_controle"];
        } else {
            $usuario_logado["painel_variavel_controle"] = $setor_ultimo_painel;
        }
        ?>
        <meta http-equiv="refresh" content="180" />
        <?php
    }
}
?>

<div class="row">
    <div class="col-12">
        <div class="card glass-card">
            <div class="card-body">
                <?php
                if (isset($_GET["a"])) {
                    $ano_consulta = (int) $_GET["a"] == 0 ? date("Y") : $_GET["a"];
                } else {
                    $ano_consulta = date("Y");
                }

                if (isset($_GET["m"])) {
                    $mes_consulta = (int) $_GET["m"] == 0 ? date("m") : $_GET["m"];
                } else {
                    $mes_consulta = date("m");
                }
                ?>

                <!-- Update Badges -->
                <?php if (isset($dia_ultima_atualizacao_ofertas) || isset($dia_ultima_atualizacao_admissoes_hd) || isset($dia_ultima_atualizacao_admissoes_internas) || isset($dia_ultima_atualizacao_admissoes_externas)): ?>
                    <h6 class="mb-0 text-uppercase font-weight-bold text-hmdcc-green pb-2"
                        style="letter-spacing:0.5px; font-size:0.85rem;">Últimas Atualizações</h6>
                    <div class="row g-2 mb-4">
                        <?php if (isset($dia_ultima_atualizacao_ofertas)): ?>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="badge bg-gradient-info d-flex align-items-center justify-content-center px-3 py-1 w-100"
                                    style="border-radius: 8px; min-height: 42px;">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    <span style="font-size: 0.75rem; letter-spacing: 0.3px;">
                                        Ofertas:
                                        <?= str_pad($dia_ultima_atualizacao_ofertas, 2, "0", STR_PAD_LEFT) ?>/<?= str_pad($mes_ultima_atualizacao_ofertas, 2, "0", STR_PAD_LEFT) ?>
                                        <?= $ultima_atualizacao_ofertas ?>
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($dia_ultima_atualizacao_admissoes_hd)): ?>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="badge bg-gradient-warning d-flex align-items-center justify-content-center px-3 py-1 w-100"
                                    style="border-radius: 8px; min-height: 42px;">
                                    <i class="fas fa-sun me-2"></i>
                                    <span style="font-size: 0.75rem; letter-spacing: 0.3px;">
                                        HD:
                                        <?= str_pad($dia_ultima_atualizacao_admissoes_hd, 2, "0", STR_PAD_LEFT) ?>/<?= str_pad($mes_ultima_atualizacao_admissoes_hd, 2, "0", STR_PAD_LEFT) ?>
                                        <?= $ultima_atualizacao_admissoes_hd ?>
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($dia_ultima_atualizacao_admissoes_internas)): ?>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="badge bg-gradient-success d-flex align-items-center justify-content-center px-3 py-1 w-100"
                                    style="border-radius: 8px; min-height: 42px;">
                                    <i class="fas fa-door-open me-2"></i>
                                    <span style="font-size: 0.7rem; letter-spacing: 0.2px; white-space: nowrap;">
                                        Adm. Internas:
                                        <?= str_pad($dia_ultima_atualizacao_admissoes_internas, 2, "0", STR_PAD_LEFT) ?>/<?= str_pad($mes_ultima_atualizacao_admissoes_internas, 2, "0", STR_PAD_LEFT) ?>
                                        <?= $ultima_atualizacao_admissoes_internas ?>
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($dia_ultima_atualizacao_admissoes_externas)): ?>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="badge bg-gradient-primary d-flex align-items-center justify-content-center px-3 py-1 w-100"
                                    style="border-radius: 8px; min-height: 42px;">
                                    <i class="fas fa-external-link-alt me-2"></i>
                                    <span style="font-size: 0.7rem; letter-spacing: 0.2px; white-space: nowrap;">
                                        Adm. Externas:
                                        <?= str_pad($dia_ultima_atualizacao_admissoes_externas, 2, "0", STR_PAD_LEFT) ?>/<?= str_pad($mes_ultima_atualizacao_admissoes_externas, 2, "0", STR_PAD_LEFT) ?>
                                        <?= $ultima_atualizacao_admissoes_externas ?>
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php
                if ($ano_calendario == 0 || $mes_calendario == 0) {
                    $calendario->display();
                } else {
                    $calendario->stylesheet();
                    echo ($calendario->draw($ano_calendario . '-' . $mes_calendario . '-01'));
                }
                ?>

                <!-- Section Details -->
                <div class="d-flex align-items-center mt-5 mb-4">
                    <div class="me-2"
                        style="width:4px; height:24px; background: var(--premium-gradient); border-radius:2px;"></div>
                    <h6 class="mb-0 text-uppercase font-weight-bold text-petroleum"
                        style="letter-spacing:0.5px; font-size:0.85rem;">Metas Gerais</h6>
                </div>

                <?php
                // Helper functions (guard against redeclaration)
                if (!function_exists('getCorMetaMonitoramento')) {
                    function getCorMetaMonitoramento($porcentagem)
                    {
                        if ($porcentagem < 75)
                            return 'bg-gradient-danger';
                        else if ($porcentagem >= 75 && $porcentagem <= 85)
                            return 'bg-warning';
                        else
                            return 'bg-gradient-success';
                    }
                    function clampProgressMonitoramento($val)
                    {
                        $rounded = (int) $val;
                        $stepped = ceil($rounded / 5) * 5;
                        return min($stepped, 100);
                    }
                }

                $metas = [
                    [
                        'label' => 'Clínica Médica',
                        'meta' => $meta_total_clinica_medica,
                        'realizado' => $total_clinica_medica,
                        'porcentagem' => $porcentagem_geral_clinica_medica,
                        'realizado_periodo' => $porcentagem_realizado_clm,
                        'ideal' => (int) $ideal_realizado_atual_clm,
                        'internas' => $admissoes_internas_c_medica,
                        'icon' => 'fas fa-stethoscope',
                        'strip' => 'strip-green'
                    ],
                    [
                        'label' => 'AVC',
                        'meta' => $meta_total_avc,
                        'realizado' => $admissoes_externas_avc,
                        'porcentagem' => $porcentagem_avc,
                        'realizado_periodo' => $porcentagem_realizado_avc,
                        'ideal' => (int) $ideal_realizado_atual_avc,
                        'internas' => $admissoes_internas_avc,
                        'icon' => 'fas fa-brain',
                        'strip' => 'strip-green'
                    ],
                    [
                        'label' => 'Paciente Crítico',
                        'meta' => $meta_total_paciente_critico,
                        'realizado' => $total_paciente_critico,
                        'porcentagem' => $porcentagem_geral_paciente_critico,
                        'realizado_periodo' => $porcentagem_realizado_cti,
                        'ideal' => (int) $ideal_realizado_atual_cti,
                        'icon' => 'fas fa-heartbeat',
                        'strip' => 'strip-green'
                    ],
                    [
                        'label' => 'Clínica Cirúrgica',
                        'meta' => $meta_total_clinica_cirurgica,
                        'realizado' => $total_clinica_cirurgica,
                        'porcentagem' => $porcentagem_geral_clinica_cirurgica,
                        'realizado_periodo' => $porcentagem_realizado_cir,
                        'ideal' => (int) $ideal_realizado_atual_cir,
                        'internas' => $admissoes_internas_c_cirurgica,
                        'icon' => 'fas fa-cut',
                        'strip' => 'strip-green'
                    ],
                    [
                        'label' => 'Hospital Dia',
                        'meta' => $meta_admissoes_hd,
                        'realizado' => $admissoes_hd,
                        'porcentagem' => $porcentagem_geral_hd,
                        'realizado_periodo' => $porcentagem_realizado_hd,
                        'ideal' => (int) $ideal_realizado_atual_hd,
                        'icon' => 'fas fa-sun',
                        'strip' => 'strip-green'
                    ],
                ];
                ?>

                <div class="row">
                    <?php foreach ($metas as $meta):
                        $progresso = clampProgressMonitoramento($meta['porcentagem']);
                        $cor = getCorMetaMonitoramento($meta['porcentagem']);
                        ?>
                        <div class="col-lg-6 col-md-12 mb-3">
                            <div class="list-card meta-goal-card h-100">
                                <div class="list-card-strip <?= $meta['strip'] ?>"></div>
                                <div class="list-card-body" style="padding: 16px 20px 12px 24px; display:block;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="font-weight-bold"
                                            style="font-size:0.95rem; color: var(--color-petroleum-blue);">
                                            <i class="<?= $meta['icon'] ?> me-1 opacity-6" style="font-size:0.85rem;"></i>
                                            <?= $meta['label'] ?>
                                        </span>
                                        <span class="font-weight-bolder"
                                            style="font-size:1.1rem; color: var(--color-petroleum-blue);">
                                            <?= number_format($meta['porcentagem'], 1, ',', '') ?>%
                                        </span>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 mb-2" style="font-size:0.75rem; color:#8898aa;">
                                        <span>Meta: <b style="color:var(--color-dark-grey);"><?= $meta['meta'] ?></b></span>
                                        <span>•</span>
                                        <span>Realizado: <b
                                                style="color:var(--color-dark-grey);"><?= $meta['realizado'] ?></b></span>
                                        <?php if (isset($meta['internas'])): ?>
                                            <span>•</span>
                                            <span>Internas: <b
                                                    style="color:var(--color-dark-grey);"><?= $meta['internas'] ?></b></span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-3" style="font-size:0.72rem; color:#8898aa;">
                                        Realizado 1/<?= $mes_consulta ?> - <?= $dia_atual_realizado ?>/<?= $mes_consulta ?>:
                                        <b style="color:var(--color-dark-grey);"><?= $meta['realizado_periodo'] ?>%</b>
                                        <span class="ms-2">Ideal: <b
                                                style="color:var(--color-dark-grey);"><?= $meta['ideal'] ?></b></span>
                                    </div>

                                    <div class="progress" style="height:6px; border-radius:3px; background:#eef0f3;">
                                        <div class="progress-bar <?= $cor ?>" role="progressbar"
                                            style="width: <?= $progresso ?>%; border-radius:3px; transition: width 0.6s ease;"
                                            aria-valuenow="<?= $progresso ?>" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modal_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-premium modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center">
                    <div class="me-2"
                        style="width:4px; height:20px; background: var(--premium-gradient); border-radius:2px;"></div>
                    <h5 class="modal-title font-weight-bold text-petroleum mb-0" id="exampleModalLabel">Detalhes</h5>
                </div>
                <button type="button" class="bg-white" data-bs-dismiss="modal" aria-label="Close" style="border:none;">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body pt-3" id="corpo_modal" name="corpo_modal">

            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn bg-gradient-hmdcc text-white px-4" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    function abrirModalInformacoes(dia, mes, ano) {
        var dateObj = new Date();
        var month = dateObj.getUTCMonth() + 1; //months from 1-12
        var day = dateObj.getUTCDate();
        var year = dateObj.getUTCFullYear();
        $("#exampleModalLabel").html("Detalhes " + ("00" + dia).slice(-2) + "/" + ("00" + mes).slice(-2) + "/" + ano + "");

        if (dia == day && mes == month && year == ano) {
            //SE DIA, MES E ANO ATUAIS (HOJE) - MOSTRAR DADOS PERIÓDICOS (2 EM 2 HORAS)
            //ADMISSOES DIARIAS PERIÓDICAS
            $.ajax({
                url: "<?php echo site_url('monitoramento/retornaDetalhesAdmissoesMesPeriodicas'); ?>",
                type: 'POST',
                dataType: "JSON",
                data: {
                    "dia": dia,
                    "mes": mes,
                    "ano": ano
                },
                success: function (data) {
                    var result = data;
                    var html_corpo_tabela = "<span class='font-weight-bolder justify-center'>Admissões</span><table class='table align-items-center justify-content-center' width='100%'><thead><tr><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2'>Tipo</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2'>Setor</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2'>Quantidade</th></tr></thead><tbody>";
                    var tipo_geral_ad = "";
                    let total = 0;
                    let total_internas = 0;
                    let total_externas = 0;
                    let total_hd = 0;

                    for (var i = 0; i < result.length; i++) {
                        if (result[i].IE_TIPO_ADMISSAO == 'E') {
                            tipo_geral_ad = "Externa";
                        } else if (result[i].IE_TIPO_ADMISSAO == 'I') {
                            tipo_geral_ad = "Interna";
                        } else if (result[i].IE_TIPO_ADMISSAO == 'HD') {
                            tipo_geral_ad = "Hosp. Dia";
                        }

                        total = total + parseInt(result[i].QUANTIDADE);

                        if (tipo_geral_ad == 'Interna') {
                            total_internas = total_internas + parseInt(result[i].QUANTIDADE);
                        } else if (tipo_geral_ad == 'Externa') {
                            total_externas = total_externas + parseInt(result[i].QUANTIDADE);
                        } else if (tipo_geral_ad == 'Hosp. Dia') {
                            total_hd = total_hd + parseInt(result[i].QUANTIDADE);
                        }

                        html_corpo_tabela += "<tr>" +
                            "<td class='text-xs text-center font-weight-bold'>" +
                            tipo_geral_ad +
                            "</td>" +
                            "<td class='text-xs text-center font-weight-bold'>" +
                            result[i].DS_SETOR_ATENDIMENTO +
                            "</td>" +
                            "<td class='text-xs text-center font-weight-bold'>" +
                            result[i].QUANTIDADE +
                            "</td>" +
                            "</tr>";

                    }
                    html_corpo_tabela += "<tr>" +
                        "<td colspan='2' class='text-xs text-center font-weight-bolder'>" +
                        "Total Internas" +
                        "</td>" +
                        "<td class='text-xs text-center font-weight-bold'>" +
                        total_internas +
                        "</td>" +
                        "</tr>" +
                        "<tr>" +
                        "<td colspan='2' class='text-xs text-center font-weight-bolder'>" +
                        "Total Externas" +
                        "</td>" +
                        "<td class='text-xs text-center font-weight-bold'>" +
                        total_externas +
                        "</td>" +
                        "</tr>" +
                        "<tr>" +
                        "<td colspan='2' class='text-xs text-center font-weight-bolder'>" +
                        "Total HD" +
                        "</td>" +
                        "<td class='text-xs text-center font-weight-bold'>" +
                        total_hd +
                        "</td>" +
                        "</tr>" +
                        "<tr>" +
                        "<td colspan='2' class='text-xs text-center font-weight-bolder'>" +
                        "Total Geral" +
                        "</td>" +
                        "<td class='text-xs text-center font-weight-bold'>" +
                        total +
                        "</td>" +
                        "</tr>";
                    html_corpo_tabela += "</tbody></table>";
                    $("#corpo_modal").html(html_corpo_tabela);

                    //OFERTAS DIARIAS PERIÓDICAS
                    $.ajax({
                        url: "<?php echo site_url('monitoramento/retornaDetalhesOfertasDiariasPeriodicas'); ?>",
                        type: 'POST',
                        dataType: "JSON",
                        data: {
                            "dia": dia,
                            "mes": mes,
                            "ano": ano
                        },
                        success: function (data1) {
                            var result1 = data1;
                            if (result1.length > 0) {
                                var html_corpo_adicional = "<span class='font-weight-bolder justify-center mt-2'>Ofertas</span><table class='table align-items-center justify-content-center' width='100%'><thead><tr><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2'>Setor</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2'>Tipo</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2'>Quantidade</th></tr></thead><tbody>";
                                let total = 0;
                                for (var i = 0; i < result1.length; i++) {
                                    if (result1[i].ds_setor_solicitado == "UDC - Unidade de Decisão Clínica") {
                                        result1[i].ds_setor_solicitado = "UDC";
                                    }
                                    if (result1[i].ds_setor_solicitado == "UEC – Unidade de Estabilização Clínica") {
                                        result1[i].ds_setor_solicitado = "UEC";
                                    }
                                    total = total + parseInt(result1[i].quantidade);

                                    html_corpo_adicional += "<tr>" +
                                        "<td class='text-xs text-center font-weight-bold'>" +
                                        result1[i].ds_setor_solicitado +
                                        "</td>" +
                                        "<td class='text-xs text-center font-weight-bold'>" +
                                        result1[i].ds_tipo_vaga +
                                        "</td>" +
                                        "<td class='text-xs text-center font-weight-bold'>" +
                                        result1[i].quantidade +
                                        "</td>" +
                                        "</tr>";

                                }
                                html_corpo_adicional += "<tr>" +
                                    "<td colspan='2' class='text-xs text-center font-weight-bolder'>" +
                                    "Total" +
                                    "</td>" +
                                    "<td class='text-xs text-center font-weight-bold'>" +
                                    total +
                                    "</td>" +
                                    "</tr>";
                                html_corpo_adicional += "</tbody></table>";

                                $("#corpo_modal").append(html_corpo_adicional);
                            }

                        },
                        error: function (data1) {
                            alert('Não foi possível buscar os detalhes das ofertas.');
                        }
                    });

                    $("#modal_info").modal('show');
                },
                error: function (data) {
                    alert('Não foi possível buscar os detalhes das admissões.');
                }
            });
        } else {
            //ADMISSOES DIARIAS
            $.ajax({
                url: "<?php echo site_url('monitoramento/retornaDetalhesAdmissoesMes'); ?>",
                type: 'POST',
                dataType: "JSON",
                data: {
                    "dia": dia,
                    "mes": mes,
                    "ano": ano
                },
                success: function (data) {
                    var result = data;
                    var html_corpo_tabela = "<span class='font-weight-bolder justify-center'>Admissões</span><table class='table align-items-center justify-content-center' width='100%'><thead><tr><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2'>Tipo</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2'>Setor</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2'>Quantidade</th></tr></thead><tbody>";
                    var tipo_geral_ad = "";
                    let total = 0;
                    let total_internas = 0;
                    let total_externas = 0;
                    let total_hd = 0;

                    for (var i = 0; i < result.length; i++) {
                        if (result[i].IE_TIPO_ADMISSAO == 'E') {
                            tipo_geral_ad = "Externa";
                        } else if (result[i].IE_TIPO_ADMISSAO == 'I') {
                            tipo_geral_ad = "Interna";
                        } else if (result[i].IE_TIPO_ADMISSAO == 'HD') {
                            tipo_geral_ad = "Hosp. Dia";
                        }

                        total = total + parseInt(result[i].QUANTIDADE);

                        if (tipo_geral_ad == 'Interna') {
                            total_internas = total_internas + parseInt(result[i].QUANTIDADE);
                        } else if (tipo_geral_ad == 'Externa') {
                            total_externas = total_externas + parseInt(result[i].QUANTIDADE);
                        } else if (tipo_geral_ad == 'Hosp. Dia') {
                            total_hd = total_hd + parseInt(result[i].QUANTIDADE);
                        }

                        html_corpo_tabela += "<tr>" +
                            "<td class='text-xs text-center font-weight-bold'>" +
                            tipo_geral_ad +
                            "</td>" +
                            "<td class='text-xs text-center font-weight-bold'>" +
                            result[i].DS_SETOR_ATENDIMENTO +
                            "</td>" +
                            "<td class='text-xs text-center font-weight-bold'>" +
                            result[i].QUANTIDADE +
                            "</td>" +
                            "</tr>";

                    }
                    html_corpo_tabela += "<tr>" +
                        "<td colspan='2' class='text-xs text-center font-weight-bolder'>" +
                        "Total Internas" +
                        "</td>" +
                        "<td class='text-xs text-center font-weight-bold'>" +
                        total_internas +
                        "</td>" +
                        "</tr>" +
                        "<tr>" +
                        "<td colspan='2' class='text-xs text-center font-weight-bolder'>" +
                        "Total Externas" +
                        "</td>" +
                        "<td class='text-xs text-center font-weight-bold'>" +
                        total_externas +
                        "</td>" +
                        "</tr>" +
                        "<tr>" +
                        "<td colspan='2' class='text-xs text-center font-weight-bolder'>" +
                        "Total HD" +
                        "</td>" +
                        "<td class='text-xs text-center font-weight-bold'>" +
                        total_hd +
                        "</td>" +
                        "</tr>" +
                        "<tr>" +
                        "<td colspan='2' class='text-xs text-center font-weight-bolder'>" +
                        "Total Geral" +
                        "</td>" +
                        "<td class='text-xs text-center font-weight-bold'>" +
                        total +
                        "</td>" +
                        "</tr>";
                    html_corpo_tabela += "</tbody></table>";


                    // $("#corpo_tabela_ocupacao").html(html_corpo_tabela);
                    // $("#nome_area").text($("#titulo"+id_area).text());

                    // $("#tabela_detalhes").show();
                    // location.href = "#tabela_detalhes";

                    $("#corpo_modal").html(html_corpo_tabela);


                    //OFERTAS DIARIAS
                    $.ajax({
                        url: "<?php echo site_url('monitoramento/retornaDetalhesOfertasDiarias'); ?>",
                        type: 'POST',
                        dataType: "JSON",
                        data: {
                            "dia": dia,
                            "mes": mes,
                            "ano": ano
                        },
                        success: function (data1) {
                            var result1 = data1;
                            if (result1.length > 0) {
                                var html_corpo_adicional = "<span class='font-weight-bolder justify-center mt-2'>Ofertas</span><table class='table align-items-center justify-content-center' width='100%'><thead><tr><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2'>Setor</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2'>Tipo</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2'>Quantidade</th></tr></thead><tbody>";
                                let total = 0;
                                for (var i = 0; i < result1.length; i++) {
                                    if (result1[i].ds_setor_solicitado == "UDC - Unidade de Decisão Clínica") {
                                        result1[i].ds_setor_solicitado = "UDC";
                                    }
                                    if (result1[i].ds_setor_solicitado == "UEC – Unidade de Estabilização Clínica") {
                                        result1[i].ds_setor_solicitado = "UEC";
                                    }
                                    total = total + parseInt(result1[i].quantidade);

                                    html_corpo_adicional += "<tr>" +
                                        "<td class='text-xs text-center font-weight-bold'>" +
                                        result1[i].ds_setor_solicitado +
                                        "</td>" +
                                        "<td class='text-xs text-center font-weight-bold'>" +
                                        result1[i].ds_tipo_vaga +
                                        "</td>" +
                                        "<td class='text-xs text-center font-weight-bold'>" +
                                        result1[i].quantidade +
                                        "</td>" +
                                        "</tr>";

                                }
                                html_corpo_adicional += "<tr>" +
                                    "<td colspan='2' class='text-xs text-center font-weight-bolder'>" +
                                    "Total" +
                                    "</td>" +
                                    "<td class='text-xs text-center font-weight-bold'>" +
                                    total +
                                    "</td>" +
                                    "</tr>";
                                html_corpo_adicional += "</tbody></table>";

                                $("#corpo_modal").append(html_corpo_adicional);
                            }

                        },
                        error: function (data1) {
                            alert('Não foi possível buscar os detalhes das admissões.');
                        }
                    });

                    $("#modal_info").modal('show');
                },
                error: function (data) {
                    alert('Não foi possível buscar os detalhes das admissões.');
                }
            });
        }
    }

</script>