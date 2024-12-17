<?php
    // $variavel_controle_margem_tv = 4;
    // $usuario_logado = $this->session->userdata("usuario_logado");
    // if($usuario_logado["TIPO_PERFIL"]=='P'){ 
    //     $variavel_controle_margem_tv = 2;
    //     echo '<meta http-equiv="refresh" content="300" />';
    // } 
    // $reader = new RtfReader();
?>

<div class="row">
    <?php 
        echo    "<div class='col-lg-12'>
                    <div class='flex'>
                        <div class='col-6'>
                            <a class='btn btn-primary' href='../detalhada/leitos?l=".$_GET['l']."&s=".$_GET["s"]."'>Voltar</a>
                        </div>
                        <div class='col-6 text-end'>

                        </div>
                    </div>";
        if($_GET["l"]!=4 && $_GET["s"]!=145){
            $botao_verdes_vermelhos = "<div class='w-full px-2 botoes-ocupacao-detalhada'>
                                            <a class='btn text-xs btn-primary flex rounded text-white' href='avaliacoesVerdeVermelho?a=".$_GET["a"]."&l=".$_GET["l"]."&s=".$_GET["s"]."'>Histórico avaliações</a>
                                        </div>";
        }else{
            $botao_verdes_vermelhos = "";
        }

        echo    "</div>
                <div class='card z-index-2'>
                    <div class='card-body text-end text-xs'>
                        <div class='row justify-center lead text-dark active breadcrumb-item font-weight-bolder'>
                            Evoluções
                        </div>
                    </div>
                </div>
                <div class='mt-3 flex flex-wrap'>
                    ".$botao_verdes_vermelhos."
                    <div class='w-full px-2 botoes-ocupacao-detalhada'>
                        <a class='btn text-xs hmdcc-color-2 flex rounded text-white' href='historicoInterconsultasPaciente?a=".$_GET["a"]."&l=".$_GET["l"]."&s=".$_GET["s"]."'>Últimas interconsultas</a>
                    </div>
                    <div class='w-full px-2 botoes-ocupacao-detalhada'>
                        <a class='btn text-xs hmdcc-color-3 flex rounded text-white' href='historicoExamesLabPaciente?a=".$_GET["a"]."&l=".$_GET["l"]."&s=".$_GET["s"]."'>Exames laboratoriais</a>
                    </div>
                    <!--
                    <div class='w-full px-2 botoes-ocupacao-detalhada'>
                        <a class='btn text-xs hmdcc-color-4 flex rounded text-white' href='historicoPrescricoesPaciente?a=".$_GET["a"]."&l=".$_GET["l"]."&s=".$_GET["s"]."'>Últimas prescrições</a>
                    </div>
                    -->
                    <div class='w-full px-2 botoes-ocupacao-detalhada'>
                        <a class='btn text-xs hmdcc-color-5 flex rounded text-white' href='historicoExamesImagemPaciente?a=".$_GET["a"]."&l=".$_GET["l"]."&s=".$_GET["s"]."'>Imagens e laudos</a>
                    </div>
                </div>
                </div>";
            /*.date('d/m/Y H:i:s', strtotime($ultima_atualizacao["ultima_atualizacao"])).*/
    ?>
    
    <?php
        echo $html_evolucoes_paciente;
        
        echo "<input type='hidden' id='cd_setor_atendimento_id' name='cd_setor_atendimento_id' value='".$_GET["s"]."'/>";
        echo "<input type='hidden' id='linha_cuidado_id' name='linha_cuidado_id' value='".$_GET["l"]."'/>";
        echo "<input type='hidden' id='nr_atendimento_id' name='nr_atendimento_id' value='".$_GET["a"]."'/>";
    ?>
</div>

<!-- Modal -->
<div class="modal fade w-full" id="modal_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Informações do leito</h5>
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

<script defer>
    function idade(ano_aniversario, mes_aniversario, dia_aniversario) {
        var d = new Date,
            ano_atual = d.getFullYear(),
            mes_atual = d.getMonth() + 1,
            dia_atual = d.getDate(),

            ano_aniversario = +ano_aniversario,
            mes_aniversario = +mes_aniversario,
            dia_aniversario = +dia_aniversario,

            quantos_anos = ano_atual - ano_aniversario;

        if(mes_atual < mes_aniversario || mes_atual == mes_aniversario && dia_atual < dia_aniversario) {
            quantos_anos--;
        }
        return quantos_anos < 0 ? 0 : quantos_anos;
    }

    
</script>