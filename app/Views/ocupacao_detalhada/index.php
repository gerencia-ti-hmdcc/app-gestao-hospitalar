<?php
    $variavel_controle_margem_tv = 4;
    if($link_pagina=='dashboard'){ 
        if($tipo_perfil=='P'){ 
            $variavel_controle_margem_tv = 2;
            $usuario_logado = $this->session->get("usuario_logado");
            if(isset($usuario_logado["painel_variavel_controle"])){
                $usuario_logado["painel_variavel_controle"] = $usuario_logado["painel_variavel_controle"];
            }else{
                $usuario_logado["painel_variavel_controle"] = $setor_ultimo_painel;
            }
?>
            <meta http-equiv="refresh" content="180" />
<?php 
        } 
    } ?>

<div class="row">
    <div class="col-lg-12 mb-<?php echo $variavel_controle_margem_tv;?>">
        <div class="card z-index-2">
            <div class="card-header pb-0">
                <div class="row justify-center lead text-dark active breadcrumb-item font-weight-bolder">
                    Linhas de cuidado
                </div>
            </div>
            <div class="card-body p-3">
            </div>
        </div>
    </div>
    <?php
        echo '<div class="flex flex-wrap col-12 mb-auto">';
        for($i=0;$i<count($linhas_de_cuidado);$i++){
            echo '<div class="card cursor-pointer w-full my-1" onclick="abrirLinhaDeCuidado('.$linhas_de_cuidado[$i]["CD_CLASSIF_SETOR"].')">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-10">
                                    <div class="numbers flex">
                                        <p class="lead" >'.$linhas_de_cuidado[$i]["DS_LINHA_CUIDADO"].'</p>
                                    </div>
                                </div>
                                <div class="col-2 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="ni ni-building text-lg opacity-10" aria-hidden="true"></i>
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
<div class="modal fade" id="modal_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Informações</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="corpo_modal" name="corpo_modal">
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">OK</button>
        <!-- <button type="button" class="btn bg-gradient-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>

<script>
    function abrirLinhaDeCuidado(cd_agrupamento){
        window.location.href="detalhada/setores?l="+cd_agrupamento;
    }
    
</script>