<?php
$variavel_controle_margem_tv = 4;
if ($link_pagina == 'dashboard') {
    if ($tipo_perfil == 'P') {
        $variavel_controle_margem_tv = 2;
        $usuario_logado = $this->session->get("usuario_logado");
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
                //echo $calendario1;
                // echo $calendario->display(date('2022-01-01')); 
                // echo $calendario->display(date('2022-02-01')); 
                if (isset($_GET["a"])) {
                    if ((int) $_GET["a"] == 0) {
                        $ano_consulta = date("Y");
                    } else {
                        $ano_consulta = $_GET["a"];
                    }
                } else {
                    $ano_consulta = date("Y");
                }

                if (isset($_GET["m"])) {
                    if ((int) $_GET["m"] == 0) {
                        $mes_consulta = date("m");
                    } else {
                        $mes_consulta = $_GET["m"];
                    }
                } else {
                    $mes_consulta = date("m");
                }

                // ---- Helper function to determine progress bar color class ----
                function getCorMeta($porcentagem)
                {
                    if ($porcentagem < 75) {
                        return 'bg-gradient-danger';
                    } else if ($porcentagem >= 75 && $porcentagem <= 85) {
                        return 'bg-warning';
                    } else {
                        return 'bg-gradient-success';
                    }
                }

                function clampProgress($val)
                {
                    $rounded = (int) $val;
                    $stepped = ceil($rounded / 5) * 5;
                    return min($stepped, 100);
                }

                // ---- Prepare data for each goal ----
                $metas = [
                    [
                        'titulo' => 'Clínica Médica',
                        'icone' => 'fas fa-stethoscope',
                        'strip' => 'strip-green',
                        'meta' => $meta_total_clinica_medica,
                        'realizado' => $total_clinica_medica,
                        'internas' => $admissoes_internas_c_medica,
                        'pct_geral' => $porcentagem_geral_clinica_medica,
                        'pct_real' => $porcentagem_realizado_clm,
                        'ideal_real' => (int) $ideal_realizado_atual_clm,
                    ],
                    [
                        'titulo' => 'AVC',
                        'icone' => 'fas fa-brain',
                        'strip' => 'strip-green',
                        'meta' => $meta_total_avc,
                        'realizado' => $admissoes_externas_avc,
                        'internas' => $admissoes_internas_avc,
                        'pct_geral' => $porcentagem_avc,
                        'pct_real' => $porcentagem_realizado_avc,
                        'ideal_real' => (int) $ideal_realizado_atual_avc,
                    ],
                    [
                        'titulo' => 'Paciente Crítico',
                        'icone' => 'fas fa-heartbeat',
                        'strip' => 'strip-green',
                        'meta' => $meta_total_paciente_critico,
                        'realizado' => $total_paciente_critico,
                        'internas' => null,
                        'pct_geral' => $porcentagem_geral_paciente_critico,
                        'pct_real' => $porcentagem_realizado_cti,
                        'ideal_real' => (int) $ideal_realizado_atual_cti,
                    ],
                    [
                        'titulo' => 'Clínica Cirúrgica',
                        'icone' => 'fas fa-cut',
                        'strip' => 'strip-green',
                        'meta' => $meta_total_clinica_cirurgica,
                        'realizado' => $total_clinica_cirurgica,
                        'internas' => $admissoes_internas_c_cirurgica,
                        'pct_geral' => $porcentagem_geral_clinica_cirurgica,
                        'pct_real' => $porcentagem_realizado_cir,
                        'ideal_real' => (int) $ideal_realizado_atual_cir,
                    ],
                    [
                        'titulo' => 'Hospital Dia',
                        'icone' => 'fas fa-sun',
                        'strip' => 'strip-green',
                        'meta' => $meta_admissoes_hd,
                        'realizado' => $admissoes_hd,
                        'internas' => null,
                        'pct_geral' => $porcentagem_geral_hd,
                        'pct_real' => $porcentagem_realizado_hd,
                        'ideal_real' => (int) $ideal_realizado_atual_hd,
                    ],
                ];
                ?>

                <!-- METAS GERAIS Header -->
                <div class="d-flex align-items-center mb-3">
                    <div class="me-2"
                        style="width:4px; height:24px; background: var(--premium-gradient); border-radius:2px;"></div>
                    <h6 class="mb-0 text-uppercase font-weight-bold text-petroleum"
                        style="letter-spacing:0.5px; font-size:0.85rem;">Metas Gerais</h6>
                </div>

                <!-- Goals Grid -->
                <div class="row">
                    <?php foreach ($metas as $m):
                        $pct_geral_val = (float) $m['pct_geral'];
                        $pct_barra = clampProgress($pct_geral_val);
                        $cor_barra = getCorMeta((int) $pct_geral_val);
                        ?>
                        <div class="col-lg-6 col-12 mb-3">
                            <div class="list-card meta-goal-card">
                                <div class="list-card-strip <?= $m['strip'] ?>"></div>
                                <div class="list-card-body" style="padding: 16px 20px 12px 24px; display:block;">
                                    <!-- Card Header: Title + Percentage -->
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="font-weight-bold"
                                            style="font-size:0.95rem; color: var(--color-petroleum-blue);">
                                            <i class="<?= $m['icone'] ?> me-1 opacity-6" style="font-size:0.85rem;"></i>
                                            <?= $m['titulo'] ?>
                                        </span>
                                        <span class="font-weight-bolder"
                                            style="font-size:1.2rem; color: var(--color-petroleum-blue);">
                                            <?= number_format($pct_geral_val, 1, ',', '') ?>%
                                        </span>
                                    </div>
                                    <!-- Stats Row -->
                                    <div class="d-flex flex-wrap gap-2 mb-2" style="font-size:0.78rem; color:#8898aa;">
                                        <span><b style="color:var(--color-dark-grey);"><?= $m['meta'] ?></b> Meta</span>
                                        <span>•</span>
                                        <span><b style="color:var(--color-dark-grey);"><?= $m['realizado'] ?></b>
                                            Realizado</span>
                                        <?php if ($m['internas'] !== null): ?>
                                            <span>•</span>
                                            <span><b style="color:var(--color-dark-grey);"><?= $m['internas'] ?></b>
                                                Internas</span>
                                        <?php endif; ?>
                                    </div>
                                    <!-- Period Info -->
                                    <div class="d-flex flex-wrap gap-3 mb-2" style="font-size:0.75rem; color:#8898aa;">
                                        <span>Realizado 1/<?= $mes_consulta ?> -
                                            <?= $dia_atual_realizado ?>/<?= $mes_consulta ?>: <b
                                                style="color:var(--color-dark-grey);"><?= $m['pct_real'] ?>%</b></span>
                                        <span>Ideal: <b
                                                style="color:var(--color-dark-grey);"><?= $m['ideal_real'] ?></b></span>
                                    </div>
                                    <!-- Progress Bar -->
                                    <div class="progress" style="height:6px; border-radius:3px; background:#eef0f3;">
                                        <div class="progress-bar <?= $cor_barra ?>" role="progressbar"
                                            style="width:<?= $pct_barra ?>%; border-radius:3px; transition: width 0.6s ease;"
                                            aria-valuenow="<?= $pct_barra ?>" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php


                if ($ano_calendario == 0 || $mes_calendario == 0) {
                    $calendario->display();
                } else {
                    $calendario->stylesheet();
                    echo ($calendario->draw($ano_calendario . '-' . $mes_calendario . '-01'));
                }

                echo '<a href="admissoes/meses?a=' . $ano_consulta . '" class="mt-4 btn bg-gradient-hmdcc text-white btn-rounded">Calendário anual</a>';
                ?>
            </div>
        </div>
    </div>
</div>


<!-- tabela -->

<!-- Modal -->
<div class="modal fade modal-premium" id="modal_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center" style='align-items: baseline'>
                <h5 class="w-full modal-title text-center" id="exampleModalLabel">Detalhes</h5>
                <button type="button" class="btn-close text-black-50" data-bs-dismiss="modal" aria-label="Close">
                    <span class="h3" aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="corpo_modal" name="corpo_modal">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-hmdcc text-white" data-bs-dismiss="modal">OK</button>
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
        $.ajax({
            url: "<?php echo site_url('/admissoes/retornaDetalhesAdmissoesMes'); ?>",
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

                $.ajax({
                    url: "<?php echo site_url('/admissoes/retornaDetalhesOfertasDiarias'); ?>",
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

</script>