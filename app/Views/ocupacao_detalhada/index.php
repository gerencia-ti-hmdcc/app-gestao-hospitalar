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
        <div class="card glass-card z-index-2">
            <div class="card-header pb-0 bg-transparent">
                <div
                    class="row justify-center lead text-hmdcc-green active breadcrumb-item font-weight-bolder text-uppercase">
                    <i class="ni ni-building text-lg me-2"></i> Linhas de cuidado
                </div>
            </div>
            <div class="card-body p-3">
            </div>
        </div>
    </div> -->

    <?php include __DIR__ .'/../templates/components/busca_ocupacao_detalhada.php';?> 
    
    <?php

        // echo '<pre>';
        // print_r($detalhes_leito);
        // echo '</pre>';
    
    echo '<div class="flex flex-wrap col-12 mb-auto pt-personalizado">';
    for ($i = 0; $i < count($linhas_de_cuidado); $i++) {

        echo '<div class="w-full md:w-1/2 lg:w-1/3 xl:w-1/4 mb-2 px-2">
                    <div class="card glass-card cursor-pointer w-full h-100 hover-scale" onclick="abrirLinhaDeCuidado(' . $linhas_de_cuidado[$i]["CD_CLASSIF_SETOR"] . ')">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <div class="numbers">
                                        <h5 class="font-weight-bolder mb-0 text-dark">
                                            ' . $linhas_de_cuidado[$i]["DS_LINHA_CUIDADO"] . '
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-3 text-end">
                                    <div class="icon icon-shape bg-gradient-hmdcc shadow text-center border-radius-md">
                                        <i class="ni ni-ambulance text-lg opacity-10 text-white" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
    }
    echo '</div>';
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

    function abrirLinhaDeCuidado(cd_agrupamento) {
    
        window.location.href = "detalhada/setores?l=" + cd_agrupamento;
    }

    function abrirLeitos(cd_agrupamento, cd_setor_atendimento, aux, aux2, nr_atendimento) {

        let ds_leito_atual = aux

        if (aux2 != 0) {
            ds_leito_atual = aux + " " + aux2
        }
        
        window.location.href = "detalhada/leitos?l=" + cd_agrupamento + "&s=" + cd_setor_atendimento + "&lei=" + ds_leito_atual + "&a=" + nr_atendimento;  
    }

</script>