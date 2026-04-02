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

    <!-- <div class="col-lg-12 mb-<?php echo $variavel_controle_margem_tv; ?>">
        <a class='btn bg-gradient-hmdcc text-white mb-4' href='../detalhada'><i class="fas fa-arrow-left me-2"></i>
            Voltar</a>
        <div class="card glass-card z-index-2">
            <div class="card-header pb-0 bg-transparent">
                <div
                    class="row justify-center lead text-hmdcc-green active breadcrumb-item font-weight-bolder text-uppercase">
                    <i class="ni ni-building text-lg me-2"></i> Setores de atendimento
                </div>
                <div class="text-sm flex row justify-center text-secondary font-weight-bold opacity-7">
                    <?php echo $linha_cuidado["DS_LINHA_CUIDADO"]; ?>
                </div>
            </div>
            <div class="card-body p-3">
            </div>
        </div>
    </div> -->

    <?php include __DIR__ .'/../../templates/components/busca_ocupacao_detalhada.php';?> 

    <?php

    echo '<div class="flex flex-wrap col-12 pt-personalizado">';
    for ($i = 0; $i < count($setores); $i++) {
        echo '<div class="w-full md:w-1/2 mb-2 px-2">
                    <div class="card glass-card cursor-pointer w-full h-100 hover-scale" onclick="abrirSetorAtendimento(' . $setores[$i]["CD_SETOR_ATENDIMENTO"] . ')">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <div class="numbers">
                                        <h5 class="font-weight-bolder mb-0 text-dark">
                                            ' . $setores[$i]["DS_SETOR_ATENDIMENTO"] . '
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-3 text-end">
                                    <div class="icon icon-shape bg-gradient-hmdcc shadow text-center border-radius-md">
                                        <i class="ni ni-building text-lg opacity-10 text-white" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
    }
    echo '</div><input id="cd_agrupamento" name="cd_agrupamento" type="hidden" value="' . $_GET["l"] . '"/>';
    ?>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content glass-card">
            <div class="modal-header">
                <h5 class="modal-title text-hmdcc-green" id="exampleModalLabel">Informações</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-dark" id="corpo_modal" name="corpo_modal">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-hmdcc text-white" data-bs-dismiss="modal">OK</button>
                <!-- <button type="button" class="btn bg-gradient-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>

<script>

    function abrirSetorAtendimento(cd_setor_atendimento) {
        let cd_agrupamento = $("#cd_agrupamento").val();

        window.location.href = "leitos?l=" + cd_agrupamento + "&s=" + cd_setor_atendimento;
    }

    function abrirLeitos(cd_agrupamento, cd_setor_atendimento, aux, aux2, nr_atendimento) {
        
        let ds_leito_atual = aux

        if (aux2 != 0) {
            ds_leito_atual = aux + " " + aux2
        }

        window.location.href = "leitos?l=" + cd_agrupamento + "&s=" + cd_setor_atendimento + "&lei=" + ds_leito_atual + "&a=" + nr_atendimento; 
    }
</script>

