<?php
    // $variavel_controle_margem_tv = 4;
    // $usuario_logado = $this->session->userdata("usuario_logado");
    // if($usuario_logado["TIPO_PERFIL"]=='P'){ 
    //     $variavel_controle_margem_tv = 2;
    //     echo '<meta http-equiv="refresh" content="300" />';
    // } 
?>

<div class="row">
    <?php 
        echo "<div class='col-lg-12'>
                <div class='flex'>
                    <div class='col-6'>
                        <a class='btn btn-primary' href='../detalhada/leitos?l=".$_GET['l']."&s=".$_GET["s"]."'>Voltar</a>
                    </div>
                    <div class='col-6 text-end'>

                    </div>
                </div>
                
                <div class='card z-index-2'>
                    <div class='card-body text-end text-xs'>
                        <div class='row justify-center lead text-dark active breadcrumb-item font-weight-bolder'>
                            Avaliações Verdes e Vermelhos
                        </div>
                    </div>
                </div>
                <div class='mt-3 flex flex-wrap'>
                    <div class='w-full px-2 botoes-ocupacao-detalhada'>
                        <a class='btn text-xs hmdcc-color-1 flex rounded text-white' href='historicoEvolucoesPaciente?a=".$_GET["a"]."&l=".$_GET["l"]."&s=".$_GET["s"]."'>Últimas evoluções</a>
                    </div>
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
        echo    "<div class='flex flex-wrap'>
                    <div class='card z-index-2 w-full'>
                        <div class='w-full flex card-header pb-0'>
                            
                        </div>
                        <div class='w-full card-body' style='padding-top:0px !important; padding-bottom:0px !important;' id='info_principal' name='info_principal'>
                            
                        </div>";

        if(count($historico_avaliacoes)>0){
            echo "<div class='card-body' style='padding-top:0px !important; padding-bottom:0px !important;' id='historico_avaliacoes' name='historico_avaliacoes'>
                    <table class='align-items-center justify-content-center' style='border-collapse: unset !important;' width='100%'>
                        <tr>
                            <td colspan='2' class='text-center text-uppercase font-weight-bold text-wrap'>
                                Histórico de Avaliações
                            </td>
                        </tr>";
        }else{
            echo "<div class='card-body text-center' id='historico_avaliacoes' name='historico_avaliacoes'>
                    O paciente ainda não possui avaliações verde/ vermelho.";
        }

        for($i=0;$i<count($historico_avaliacoes);$i++){
            $cor    = "";
            if(trim(strtoupper($historico_avaliacoes[$i]["ds_verde_ou_vermelho"]))=="VERDE"){
                $cor = "#00ff00";
            }else{
                $cor = "#ff0000";
            }

            if(strlen($historico_avaliacoes[$i]["ds_motivo_vermelho"])>0){
                
                $motivo_vermelho = "<tr>".
                                        "<td class='font-weight-bold text-wrap'>".
                                            "Motivo".
                                        "</td>".
                                        "<td class='text-wrap text-justify'>".
                                            $historico_avaliacoes[$i]["ds_motivo_vermelho"].
                                        "</td>".
                                    "</tr>";
            }else{
                $motivo_vermelho = "";
            }
            
            echo 
            "<tr>
                <td class='font-weight-bold text-wrap'>
                    Data
                </td>
                <td class='text-wrap text-justify'>
                    ".date("d/m/Y H:i:s", strtotime($historico_avaliacoes[$i]["dt_liberacao"]))."
                </td>
            </tr>
            <tr>
                <td class='font-weight-bold text-wrap'>
                    Leito
                </td>
                <td class='text-wrap text-justify'>
                    ".$historico_avaliacoes[$i]["ds_leito_paciente_ultima_evolucao"]."
                </td>
            </tr>
            <tr> 
                <td class='font-weight-bold text-wrap'>
                    Profissional
                </td>
                <td class='text-wrap text-justify'>
                    ".$historico_avaliacoes[$i]["profissional_verde_vermelho"]."
                </td>
            </tr>
            <tr>
                <td class='font-weight-bold text-wrap'>
                    Condição
                </td>
                <td class='text-wrap'>
                    <div class='w-full text-center' style='border-radius:4px; color:#fff; background-color:".$cor."'>
                        ".trim(strtoupper($historico_avaliacoes[$i]["ds_verde_ou_vermelho"]))."
                    </div>
                </td>
            </tr>"
            .$motivo_vermelho.
            "<tr>
                <td class='font-weight-bold text-wrap'>
                    Previsão Alta
                </td>
                <td class='text-wrap'>
                    ".date("d/m/Y", strtotime($historico_avaliacoes[$i]["dt_previsao_alta"]))." 
                </td>
            </tr>";
            echo "<tr><td colspan='2'><hr style='height: 2px;background-color:#000; color: #000' ></hr></td></tr>";
        }
        if(count($historico_avaliacoes)>0){
            echo        "</table>
                    </div>
                </div>
            </div>";
        }else{
            echo "</div>
                </div>
            </div>";
        }
        
       
        
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