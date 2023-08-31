<?php
    $variavel_controle_margem_tv = 4;
    if($link_pagina=='dashboard'){ 
        if($tipo_perfil=='P'){ 
            $variavel_controle_margem_tv = 2;
            $usuario_logado = $this->session->userdata("usuario_logado");
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
                    Leitos
                </div>
                <div class="text-sm flex row justify-center">
                    <?php echo $setor_atend["DS_SETOR_ATENDIMENTO"];?>
                </div>
            </div>
            <div class="card-body p-3">
            </div>
        </div>
    </div>
    <?php
        echo '<div class="flex flex-wrap">';
        for($i = 0; $i < count($leitos); $i++) {
            
            echo '<div class="card-wrapper responsividade_leitos p-2">';

            $icone_fugulin          = "";
            $icone_news             = "";
            $icones_sinais_vitais   = "";

            $cor_card_avaliacao_verde_vermelho ="<div class='mt-4' style='height:15px;'></div>";
            

            if($leitos[$i]["cd_agrupamento"]!=4){
                //FUGULIN, 'VERDE E VERMELHO' E NEWS NAO DEVEM APARECER NO CTI
                if(isset($leitos[$i]["nr_seq_gradacao"])){
                    //SE HOUVE AVALIAÇÃO FUGULIN
                    if($leitos[$i]["nr_seq_gradacao"]!=0){
                        $funcao_isolada = 'onclick="detalhesIsolados('.$leitos[$i]["nr_atendimento"].',\''.$leitos[$i]["ds_leito_atual"].'\',\'fugulin\')"';
                        if($leitos[$i]["nr_seq_gradacao"]==2){
                            $icone_fugulin = '<div '.$funcao_isolada.' class="col-2"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="Fugulin: Mínimo" width="100%" src ="'.base_url($diretorio_raiz).'\assets\img\icons\fugulin_minimo.png"/></div>';
                        }else if($leitos[$i]["nr_seq_gradacao"]==3){
                            $icone_fugulin = '<div '.$funcao_isolada.' class="col-2"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="Fugulin: Intermediário" width="100%" src ="'.base_url($diretorio_raiz).'\assets\img\icons\fugulin_intermediario.png"/></div>';
                        }else if($leitos[$i]["nr_seq_gradacao"]==4){
                            $icone_fugulin = '<div '.$funcao_isolada.' class="col-2"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="Fugulin: Alta Dependência" width="100%" src ="'.base_url($diretorio_raiz).'\assets\img\icons\fugulin_alta_dependencia.png"/></div>';
                        }else if($leitos[$i]["nr_seq_gradacao"]==5){
                            $icone_fugulin = '<div '.$funcao_isolada.' class="col-2"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="Fugulin: Semi-Intensivo" width="100%" src ="'.base_url($diretorio_raiz).'\assets\img\icons\fugulin_semi_intensivo.png"/></div>';
                        }else if($leitos[$i]["nr_seq_gradacao"]==6){
                            $icone_fugulin = '<div '.$funcao_isolada.' class="col-2"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="Fugulin: Intensivo" width="100%" src ="'.base_url($diretorio_raiz).'\assets\img\icons\fugulin_intensivo.png"/></div>';
                        }
                    }
                }
    
                if(isset($leitos[$i]["score"])){
                    if(strlen($leitos[$i]["score"])>0){
                        //SE HOUVE AVALIAÇÃO NEWS
                        $funcao_isolada = 'onclick="detalhesIsolados('.$leitos[$i]["nr_atendimento"].',\''.$leitos[$i]["ds_leito_atual"].'\',\'news\')"';
                        if($leitos[$i]["score"]>=0 && $leitos[$i]["score"]<=3){
                            $icone_news = '<div '.$funcao_isolada.' class="col-2"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="News: nota <= 3" width="100%" src ="'.base_url($diretorio_raiz).'\assets\img\icons\news_verde.png"/></div>';
                        }else if($leitos[$i]["score"]>=4 && $leitos[$i]["score"]<=6){
                            $icone_news = '<div '.$funcao_isolada.' class="col-2"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="News: nota >= 4 e <= 6" width="100%" src ="'.base_url($diretorio_raiz).'\assets\img\icons\news_laranja.png"/></div>';
                        }else if($leitos[$i]["score"]>=7){
                            $icone_news = '<div '.$funcao_isolada.' class="col-2"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="News: nota >= 7" width="100%" src ="'.base_url($diretorio_raiz).'\assets\img\icons\news_vermelho.png"/></div>';
                        }
                    }
                }

                if($leitos[$i]["ds_verde_ou_vermelho"]){
                    //SE HOUVE AVALIAÇÃO VERDE_VERMELHO
                    if(trim($leitos[$i]["ds_verde_ou_vermelho"])=='Verde'){
                        $cor_card_avaliacao_verde_vermelho = '<div class="mt-4" style="height:15px; background-color:#20E200; border-radius:2px"></div>';
                    }else{
                        $cor_card_avaliacao_verde_vermelho = '<div class="mt-4" style="height:15px; background-color:#FF2E00; border-radius:2px"></div>';
                    }
                }
            }
            
            if(isset($leitos[$i]["qt_freq_cardiac"])){
                //SE HOUVE MEDIÇÃO CARDIACA
                $funcao_isolada = 'onclick="detalhesIsolados('.$leitos[$i]["nr_atendimento"].',\''.$leitos[$i]["ds_leito_atual"].'\',\'cardiaca\')"';
                if($leitos[$i]["qt_freq_cardiac"]>90){
                    $icones_sinais_vitais .= '<div '.$funcao_isolada.' class="col-2"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="Frequência Cardíaca > 90 bpm" width="100%" src ="'.base_url($diretorio_raiz).'\assets\img\icons\coracao.png"/></div>';
                }
            }

            if(isset($leitos[$i]["qt_freq_resp"])){
                //SE HOUVE MEDIÇÃO RESPIRATORIA
                $funcao_isolada = 'onclick="detalhesIsolados('.$leitos[$i]["nr_atendimento"].',\''.$leitos[$i]["ds_leito_atual"].'\',\'respiratoria\')"';
                if($leitos[$i]["qt_freq_resp"]>20){
                    $icones_sinais_vitais .= '<div '.$funcao_isolada.' class="col-2"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="Frequência Respiratória > 20 irpm" width="100%" src ="'.base_url($diretorio_raiz).'\assets\img\icons\pulmao.png"/></div>';
                }
            }

            if(isset($leitos[$i]["qt_temp"])){
                //SE HOUVE MEDIÇÃO TEMPERATURA
                $funcao_isolada = 'onclick="detalhesIsolados('.$leitos[$i]["nr_atendimento"].',\''.$leitos[$i]["ds_leito_atual"].'\',\'temperatura\')"';
                if($leitos[$i]["qt_temp"]>0){
                    if($leitos[$i]["qt_temp"]<=35){
                        $icones_sinais_vitais .= '<div '.$funcao_isolada.' class="col-2"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="Temperatura <= 35º" width="100%" src ="'.base_url($diretorio_raiz).'\assets\img\icons\temperatura_azul.png"/></div>';
                    }else if($leitos[$i]["qt_temp"]>=37.8){
                        $icones_sinais_vitais .= '<div '.$funcao_isolada.' class="col-2"><img data-bs-toggle="tooltip" data-bs-placement="top" class="btn-tooltip" title="Temperatura >= 37.8º" width="100%" src ="'.base_url($diretorio_raiz).'\assets\img\icons\temperatura_vermelho.png"/></div>';
                    }
                }
            }

            if($leitos[$i]["nr_atendimento"] != 0) {
                $classes_adicionais_card    =  "cursor-pointer text-dark";
                $leito                      = $leitos[$i]["ds_leito_atual"];
                $gambsClick                 = 'onclick="detalhesDoLeito('.$leitos[$i]["nr_atendimento"].',\''.$leito.'\')"';
                $dados_atd                  = "<div class='text-end text-xs'>".$leitos[$i]["ds_nome_paciente"]."<br />".$leitos[$i]["nr_atendimento"]."</div>";

                // $this->load->model('detalhada_model');
                // $movimentacoes_atendimento  = $this->detalhada_model->retornaMovimentacoesAtendimento($leitos[$i]["nr_atendimento"]);

                // //GUARDANDO PERMANENCIA (DIAS) NA MESMA LINHA DE CUIDADO
                // $somatoria_dias_linha_cuidado_atendimento = 0;
                // for($k=count($movimentacoes_atendimento)-1;$k>=0;$k--){
                //     if($movimentacoes_atendimento[$k]["cd_agrupamento"]==$_GET["l"]){
                //         $somatoria_dias_linha_cuidado_atendimento = $movimentacoes_atendimento[$k]["qt_dias_unidade"] + $somatoria_dias_linha_cuidado_atendimento;
                //     }else{
                //         break;
                //     }
                // }

                if($leitos[$i]["cd_agrupamento"]==4){
                    //SE CTI            
                    // if($somatoria_dias_linha_cuidado_atendimento>=6){
                    //     $classes_adicionais_card .= " bg-gray-700 text-white";
                    // }
                    // if($leitos[$i]["tempo_internacao"]>=6){
                    //     $classes_adicionais_card .= " bg-gray-700 text-white";
                    // }    
                    if($leitos[$i]["permanencia_linha_cuidado"]>=6){
                        $classes_adicionais_card .= " bg-gray-700 text-white";
                    }
                }else if($leitos[$i]["cd_agrupamento"]==99){
                    //SE CIRURGICA
                    // if($somatoria_dias_linha_cuidado_atendimento>=8){
                    //     $classes_adicionais_card .= " bg-gray-700 text-white";
                    // } 
                    // if($leitos[$i]["tempo_internacao"]>=8){
                    //     $classes_adicionais_card .= " bg-gray-700 text-white";
                    // } 
                    if($leitos[$i]["permanencia_linha_cuidado"]>=8){
                        $classes_adicionais_card .= " bg-gray-700 text-white";
                    }
                }else if($leitos[$i]["cd_agrupamento"]==3){
                    //SE INTERNACAO
                    if($leitos[$i]["cd_setor_atendimento"]==145){
                        //SE AVC
                        // if($somatoria_dias_linha_cuidado_atendimento>=6){
                        //     $classes_adicionais_card .= " bg-gray-700 text-white";
                        // }
                        // if($leitos[$i]["tempo_internacao"]>=6){
                        //     $classes_adicionais_card .= " bg-gray-700 text-white";
                        // }
                        if($leitos[$i]["permanencia_linha_cuidado"]>=6){
                            $classes_adicionais_card .= " bg-gray-700 text-white";
                        }
                    }
                    if(in_array($leitos[$i]["cd_setor_atendimento"],[33,76,34,55,36,56])){
                        //SE 5 AO 7 ANDAR
                        // if($somatoria_dias_linha_cuidado_atendimento>=10){
                        //     $classes_adicionais_card .= " bg-gray-700 text-white";
                        // }
                        // if($leitos[$i]["tempo_internacao"]>=10){
                        //     $classes_adicionais_card .= " bg-gray-700 text-white";
                        // }
                        if($leitos[$i]["permanencia_linha_cuidado"]>=10){
                            $classes_adicionais_card .= " bg-gray-700 text-white";
                        }
                    }
                }
                $total_dias_internacao_atendimento = '<div class="text-xs mt-1">* '.$leitos[$i]["tempo_internacao"].' dia(s) de internação</div>';
            } else {
                $classes_adicionais_card    = "bg-card-leito-livre text-white";
                $gambsClick                 = "";
                if(trim($leitos[$i]["ie_status_unidade"])!="Paciente"){
                    $dados_atd                      = "<div class='text-right text-xs'>".$leitos[$i]["ie_status_unidade"]."<br /></div>";
                }else{
                    $dados_atd                      = "<div class='text-right text-xs'><br /><br /></div>";
                }
                $total_dias_internacao_atendimento = "";
            }
            echo '<div class="h-full card '.$classes_adicionais_card.' my-1">
                    <div class="card-body">
                        <div class="flex">
                            <div class="col-4">
                                <p class="lead font-weight-bolder" '.$gambsClick.'>' . $leitos[$i]["ds_leito_atual"] . '</p>
                            </div>
                            <div style="" class="col-8 gap-1 text-xs justify-content-end text-end flex">
                                '.$icone_fugulin.' '.$icone_news.' '.$icones_sinais_vitais.'
                            </div>
                        </div> 
                        <div '.$gambsClick.'>
                            '.$dados_atd.'
                            '.$cor_card_avaliacao_verde_vermelho .'
                            '.$total_dias_internacao_atendimento.'
                        </div>
                    </div>
                </div>
            </div>';
        }
        echo '</div>';
        echo "<input type='hidden' id='cd_setor_atendimento_id' name='cd_setor_atendimento_id' value='".$_GET["s"]."'/>";
        echo "<input type='hidden' id='linha_cuidado_id' name='linha_cuidado_id' value='".$_GET["l"]."'/>";
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

<script>
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

    function detalhesDoLeito(nr_atendimento,leito_atual){
        let html_leito="";
        $.ajax({
            url : "<?php echo site_url('retornaDadosLeito');?>",
            type : 'POST',
            data: 
            {
                "nr_atendimento" : nr_atendimento,
                "leito_atual": leito_atual,
                "cd_setor_atendimento": $("#cd_setor_atendimento_id").val()
            },
            dataType: "json",
            success : function(result){

                $.ajax({
                    url : "<?php echo site_url('retornaMovimentacoesAtendimento');?>",
                    type : 'POST',
                    data: 
                    {
                        "nr_atendimento" : nr_atendimento
                    },
                    dataType: "json",
                    success : function(resultadoMovimentacoes){
                        let data_entrada    = new Date(result["dt_entrada"]);
                        let data_prev_alta  = new Date(result["dt_previsao_alta"]);
                        let dt_liberacao    = new Date(result["dt_liberacao"]);

                        let verde_verm = " - ";

                        // if(data_entrada.toLocaleString()!="Invalid Date"){
                        //     data_entrada = data_entrada.toLocaleString().replace(',','');
                        // }else{
                        //     data_entrada = " - ";
                        // }

                        let hora_entrada_geral = result["dt_entrada"].split(" ");
                        hora_entrada_geral = hora_entrada_geral[1];
                        data_entrada = result["dt_entrada"].substr(0, 10).split('-').reverse().join('/')+" "+hora_entrada_geral;
                        if(data_entrada=="00/00/0000 00:00:00"){
                            data_entrada = " - ";
                        }

                        let hora_liberacao = result["dt_liberacao"].split(" ");
                        hora_liberacao = hora_liberacao[1];
                        dt_liberacao = result["dt_liberacao"].substr(0, 10).split('-').reverse().join('/')+" "+hora_liberacao;
                        if(dt_liberacao=="00/00/0000 00:00:00"){
                            dt_liberacao = " - ";
                        }

                        if (data_prev_alta.toLocaleString() !== "Invalid Date"){
                            let dt_arr = result["dt_previsao_alta"].split('-');
                            data_prev_alta = dt_arr[2]+'/'+dt_arr[1]+'/'+dt_arr[0];
                        } else {
                            data_prev_alta = " - ";
                        }

                        let motivo_vermelho                 = "";
                        let conteudo_template_avaliacao     = "";
                        let html_movimentacoes_atendimento  = "";
                        let html_avaliacao_isolada          = "";

                        let data_nascimento     = result["dt_nascimento"].split("/");
                        let idade_paciente      = idade(data_nascimento[2],data_nascimento[1],data_nascimento[0]); 

                        /*INÍCIO PREENCHIMENTO TABELA DE AVALIAÇÃO VERDE/VERMELHO*/

                        if(result["ds_verde_ou_vermelho"] && result["cd_agrupamento"]!=4){
                            let cor         = "";
                            if(result["ds_verde_ou_vermelho"]=="Verde"){
                                cor = "#4CAF50";
                            }else{
                                cor = "#FF5252";
                                motivo_vermelho = "<tr>" +
                                                        "<td class='font-weight-bold text-wrap'>" +
                                                            "Motivo vermelho" +
                                                        "</td>" +
                                                        "<td class='text-wrap text-justify'>" +
                                                            result["ds_motivo_vermelho"]+
                                                        "</td>" +
                                                    "</tr>";
                            }
                            
                            conteudo_template_avaliacao =   "<table class='table align-items-center justify-content-center' width='100%'>"+
                                                                "<tr>" +
                                                                    "<td colspan='2' class='text-center text-uppercase font-weight-bold text-wrap'>" +
                                                                        "Avaliação" +
                                                                    "</td>" +
                                                                "</tr>"+
                                                                "<tr>" +
                                                                    "<td class='font-weight-bold text-wrap'>" +
                                                                        "Data avaliação" +
                                                                    "</td>" +
                                                                    "<td class='text-wrap text-justify'>" +
                                                                        dt_liberacao.toLocaleString().replace(',','')+
                                                                    "</td>" +
                                                                "</tr>"+
                                                                "<tr>" +
                                                                    "<td class='font-weight-bold text-wrap'>" +
                                                                        "Profissional" +
                                                                    "</td>" +
                                                                    "<td class='text-wrap text-justify'>" +
                                                                        result["profissional_verde_vermelho"]+
                                                                    "</td>" +
                                                                "</tr>"+
                                                                "<tr>" +
                                                                    "<td class='font-weight-bold text-wrap'>" +
                                                                        "Condição" +
                                                                    "</td>" +
                                                                    "<td class='text-wrap'>" +
                                                                        "<div class='w-full text-center' style='border-radius:4px; color:#fff; background-color:"+cor+"'>"+
                                                                            result["ds_verde_ou_vermelho"]+
                                                                        "</div>"+
                                                                    "</td>" +
                                                                "</tr>" +
                                                                motivo_vermelho+
                                                                "<tr>" +
                                                                    "<td class='font-weight-bold text-wrap'>" +
                                                                        "Previsão de alta" +
                                                                    "</td>" +
                                                                    "<td class='text-wrap'>" +
                                                                        data_prev_alta +
                                                                    "</td>" +
                                                                "</tr>"+
                                                            "</table>";
                        }

                        /*FIM PREENCHIMENTO TABELA DE AVALIAÇÃO VERDE/VERMELHO*/

                        /*INÍCIO PROCESSAMENTO MOVIMENTAÇÕES*/ 

                        if(resultadoMovimentacoes.length>0){
                            html_movimentacoes_atendimento =    "<table class='table align-items-center justify-content-center' width='100%'>"+
                                                                    "<tr>" +
                                                                        "<td class='text-center text-uppercase text-wrap font-weight-bold' colspan='5'>" +
                                                                            "Movimentações" +
                                                                        "</td>" +
                                                                    "</tr>"+
                                                                    "<tr>" +
                                                                        "<td class='text-xs font-weight-bold text-wrap'>" +
                                                                            "Setor" +
                                                                        "</td>" +
                                                                        "<td class='text-xs font-weight-bold text-wrap'>" +
                                                                            "Leito" +
                                                                        "</td>" +
                                                                        "<td class='text-xs text-wrap font-weight-bold '>" +
                                                                            "Entrada"+
                                                                        "</td>"+
                                                                        "<td class='text-xs text-wrap font-weight-bold '>" +
                                                                            "Saída"+
                                                                        "</td>" +
                                                                        "<td class='text-xs text-wrap font-weight-bold '>" +
                                                                            "Dias"+
                                                                        "</td>" +
                                                                    "</tr>";

                            let data_entrada_unidade    = "";
                            let data_saida_unidade      = "";

                            for(let j=0;j<resultadoMovimentacoes.length;j++){

                                data_entrada_unidade    = new Date(resultadoMovimentacoes[j]["dt_entrada_unidade"]);
                                data_saida_unidade      = new Date(resultadoMovimentacoes[j]["dt_saida_unidade"]);

                                let hora_entrada = resultadoMovimentacoes[j]["dt_entrada_unidade"].split(" ");
                                hora_entrada = hora_entrada[1];
                                data_entrada_unidade = resultadoMovimentacoes[j]["dt_entrada_unidade"].substr(0, 10).split('-').reverse().join('/')+" "+hora_entrada;
                                if(data_entrada_unidade=="00/00/0000 00:00:00"){
                                    data_entrada_unidade = " - ";
                                }

                                let hora_saida = resultadoMovimentacoes[j]["dt_saida_unidade"].split(" ");
                                hora_saida = hora_saida[1];
                                data_saida_unidade = resultadoMovimentacoes[j]["dt_saida_unidade"].substr(0, 10).split('-').reverse().join('/')+" "+hora_saida;
                                if(data_saida_unidade=="00/00/0000 00:00:00"){
                                    data_saida_unidade = " - ";
                                }

                                let cor_linha_cond_mesmo_setor = "";
                                if($("#linha_cuidado_id").val()==resultadoMovimentacoes[j]["cd_agrupamento"]){
                                    cor_linha_cond_mesmo_setor = "text-white cor_card_mesma_linha_cuidado";
                                }


                                html_movimentacoes_atendimento += "<tr>" +
                                                                    "<td class='"+cor_linha_cond_mesmo_setor+" font-weight-bold text-wrap text-xs'>" +
                                                                        resultadoMovimentacoes[j]["ds_setor_atendimento"]+
                                                                    "</td>" +
                                                                    "<td class='"+cor_linha_cond_mesmo_setor+" text-wrap text-xs'>" +
                                                                        resultadoMovimentacoes[j]["leito"]+' '+resultadoMovimentacoes[j]["ds_complemento_leito"] +
                                                                    "</td>" +
                                                                    "<td class='"+cor_linha_cond_mesmo_setor+" text-wrap text-xs'>" +
                                                                        data_entrada_unidade+
                                                                    "</td>"+
                                                                    "<td class='"+cor_linha_cond_mesmo_setor+" text-wrap text-xs'>" +
                                                                        data_saida_unidade+
                                                                    "</td>" +
                                                                    "<td class='"+cor_linha_cond_mesmo_setor+" text-wrap text-xs'>" +
                                                                        resultadoMovimentacoes[j]["qt_dias_unidade"]+
                                                                    "</td>" +
                                                                "</tr>";
                            }

                            //GUARDANDO PERMANENCIA (DIAS) NA MESMA LINHA DE CUIDADO
                            let somatoria_dias_linha_cuidado = 0;
                            for(let k=resultadoMovimentacoes.length-1;k>=0;k--){
                                if(resultadoMovimentacoes[k]["cd_agrupamento"]==$("#linha_cuidado_id").val()){
                                    somatoria_dias_linha_cuidado = parseInt(resultadoMovimentacoes[k]["qt_dias_unidade"])+parseInt(somatoria_dias_linha_cuidado);
                                }else{
                                    break;
                                }
                            }

                            html_movimentacoes_atendimento  +=      "<tr class='my-4'>" +
                                                                        "<td colspan='4' class='text-xs font-weight-bold text-wrap text-white '>" +
                                                                            
                                                                        "</td>" +
                                                                        "<td class='text-xs text-wrap font-weight-bold text-white'>" +
                                                                            
                                                                        "</td>" +
                                                                    "</tr>"+
                                                                    "<tr>" +
                                                                        "<td colspan='4' class='text-xs font-weight-bold text-wrap text-white cor_card_mesma_linha_cuidado'>" +
                                                                            "Total atual na Linha de Cuidado" +
                                                                        "</td>" +
                                                                        "<td class='text-xs text-wrap font-weight-bold text-white cor_card_mesma_linha_cuidado'>" +
                                                                            somatoria_dias_linha_cuidado+" dia(s)"+
                                                                        "</td>" +
                                                                    "</tr>"+
                                                                    "<tr >" +
                                                                        "<td colspan='4' class='text-xs font-weight-bold text-wrap'>" +
                                                                            "Total" +
                                                                        "</td>" +
                                                                        "<td class='text-xs text-wrap font-weight-bold'>" +
                                                                            resultadoMovimentacoes[0]["total_dias_unidade"]+" dia(s)"+
                                                                        "</td>" +
                                                                    "</tr>"+
                                                                "</table>";
                        }
                        
                        /*FIM PROCESSAMENTO MOVIMENTAÇÕES*/ 
                        

                        html_leito = "<table class='table align-items-center justify-content-center' width='100%'>" +
                            "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                    "Paciente" +
                                "</td>" +
                                "<td class='text-wrap'>" +
                                    result['ds_nome_paciente'] +
                                "</td>" +
                            "</tr>" +
                            "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                    "Idade" +
                                "</td>" +
                                "<td class='text-wrap'>" +
                                    idade_paciente + " anos"+
                                "</td>" +
                            "</tr>" +
                            "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                    "Nº atendimento" +
                                "</td>" +
                                "<td class='text-wrap'>" +
                                    result['nr_atendimento'] +
                                "</td>" +
                            "</tr>" +
                            "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                    "Leito" +
                                "</td>" +
                                "<td class='text-wrap'>" +
                                    result['ds_leito_atual'] +
                                "</td>" +
                            "</tr>" +
                            "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                    "Data de entrada" +
                                "</td>" +
                                "<td class='text-wrap'>" +
                                    data_entrada +
                                "</td>" +
                            "</tr>" +
                            "<tr>" +
                                "<td class='font-weight-bold text-wrap'>" +
                                    "Tempo de internação" +
                                "</td>" +
                                "<td class='text-wrap'>" +
                                    result['tempo_internacao'] + " dia(s)"+
                                "</td>" +
                            "</tr>"+
                            html_avaliacao_isolada+
                        "</table>"+
                        html_movimentacoes_atendimento+
                        conteudo_template_avaliacao;

                        $("#corpo_modal").html(html_leito);
                        $("#modal_info").modal('show');
                    },
                    error : function(data){
                        alert('Não foi possível abrir o detalhamento. Confira sua conexão!');
                    }
                });
                
            },
            error : function(data){
                alert('Não foi possível abrir o detalhamento. Confira sua conexão!');
            }
        });
    }

    function detalhesIsolados(nr_atendimento,leito_atual,tipo_avaliacao){
        $.ajax({
            url : "<?php echo site_url('retornaDadosLeito');?>",
            type : 'POST',
            data: 
            {
                "nr_atendimento" : nr_atendimento,
                "leito_atual": leito_atual,
                "cd_setor_atendimento": $("#cd_setor_atendimento_id").val()
            },
            dataType: "json",
            success : function(result){
                let data_entrada    = new Date(result["dt_entrada"]);
                let data_prev_alta  = new Date(result["dt_previsao_alta"]);

                let data_nascimento     = result["dt_nascimento"].split("/");
                let idade_paciente      = idade(data_nascimento[2],data_nascimento[1],data_nascimento[0]); 
                // if(data_entrada.toLocaleString()!="Invalid Date"){
                //     data_entrada = data_entrada.toLocaleString().replace(',','');
                // }else{
                //     data_entrada = " - ";
                // }

                let hora_entrada_geral = result["dt_entrada"].split(" ");
                hora_entrada_geral = hora_entrada_geral[1];
                data_entrada = result["dt_entrada"].substr(0, 10).split('-').reverse().join('/')+" "+hora_entrada_geral;
                if(data_entrada=="00/00/0000 00:00:00"){
                    data_entrada = " - ";
                }

                if (data_prev_alta.toLocaleString() !== "Invalid Date"){
                    let dt_arr = result["dt_previsao_alta"].split('-');
                    data_prev_alta = dt_arr[2]+'/'+dt_arr[1]+'/'+dt_arr[0];
                } else {
                    data_prev_alta = " - ";
                }

                if(tipo_avaliacao=='fugulin'){
                    let hora_entrada_fugulin = result["data_fugulin"].split(" ");
                    hora_entrada_fugulin = hora_entrada_fugulin[1];
                    let data_fugulin = result["data_fugulin"].substr(0, 10).split('-').reverse().join('/')+" "+hora_entrada_fugulin;
                    if(data_fugulin=="00/00/0000 00:00:00"){
                        data_fugulin = " - ";
                    }
                    html_avaliacao_isolada = "<tr>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    "Fugulin" +
                                                "</td>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    result['ds_gradacao'] + ' - ' + result['qt_pontuacao'] + " pontos"+
                                                "</td>" +
                                            "</tr>"+
                                            "<tr>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    "Profissional" +
                                                "</td>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    result['profissional_fugulin']+
                                                "</td>" +
                                            "</tr>"+
                                            "<tr>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    "Data Avaliação Fugulin" +
                                                "</td>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    data_fugulin+
                                                "</td>" +
                                            "</tr>";
                }else if(tipo_avaliacao=='news'){
                    let hora_entrada_news = result["dt_liberacao_news"].split(" ");
                    hora_entrada_news = hora_entrada_news[1];
                    let data_news = result["dt_liberacao_news"].substr(0, 10).split('-').reverse().join('/')+" "+hora_entrada_news;
                    if(data_news=="00/00/0000 00:00:00"){
                        data_news = " - ";
                    }
                    html_avaliacao_isolada = "<tr>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    "News" +
                                                "</td>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    result['score'] +
                                                "</td>" +
                                            "</tr>"+
                                            "<tr>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    "Profissional" +
                                                "</td>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    result['profissional_news']+
                                                "</td>" +
                                            "</tr>"+
                                            "<tr>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    "Data Avaliação News" +
                                                "</td>" +
                                                "<td class='font-weight-bold text-wrap'>" +
                                                    data_news+
                                                "</td>" +
                                            "</tr>";

                }else {
                    //SINAIS VITAIS
                    if(tipo_avaliacao=='temperatura'){
                        let hora_temperatura = result["dt_qt_temp"].split(" ");
                        hora_temperatura = hora_temperatura[1];
                        let data_temp = result["dt_qt_temp"].substr(0, 10).split('-').reverse().join('/')+" "+hora_temperatura;
                        if(data_temp=="00/00/0000 00:00:00"){
                            data_temp = " - ";
                        }
                        html_avaliacao_isolada = "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Temperatura" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        result['qt_temp'] +"º"+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Profissional" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        result['profissional_qt_temp']+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Data Medição" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        data_temp+
                                                    "</td>" +
                                                "</tr>";

                    }else if(tipo_avaliacao=='cardiaca'){
                        let hora_cardiaca = result["dt_qt_freq_cardiac"].split(" ");
                        hora_cardiaca = hora_cardiaca[1];
                        let data_card = result["dt_qt_freq_cardiac"].substr(0, 10).split('-').reverse().join('/')+" "+hora_cardiaca;
                        if(data_card=="00/00/0000 00:00:00"){
                            data_card = " - ";
                        }
                        html_avaliacao_isolada = "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Frequência Cardíaca" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        result['qt_freq_cardiac'] +" bpm"+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Profissional" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        result['profissional_qt_freq_cardiac']+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Data Medição" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        data_card+
                                                    "</td>" +
                                                "</tr>";

                    }else if(tipo_avaliacao=='respiratoria'){
                        let hora_respiratoria = result["dt_qt_freq_resp"].split(" ");
                        hora_respiratoria = hora_respiratoria[1];
                        let data_respiratoria = result["dt_qt_freq_resp"].substr(0, 10).split('-').reverse().join('/')+" "+hora_respiratoria;
                        if(data_respiratoria=="00/00/0000 00:00:00"){
                            data_respiratoria = " - ";
                        }
                        html_avaliacao_isolada = "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Frequência Respiratória" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        result['qt_freq_resp'] +" irpm"+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Profissional" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        result['profissional_qt_freq_resp']+
                                                    "</td>" +
                                                "</tr>"+
                                                "<tr>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        "Data Medição" +
                                                    "</td>" +
                                                    "<td class='font-weight-bold text-wrap'>" +
                                                        data_respiratoria+
                                                    "</td>" +
                                                "</tr>";

                    }else if(tipo_avaliacao=='leococitos'){
                

                    }
                }

                html_leito = "<table class='table align-items-center justify-content-center' width='100%'>" +
                    "<tr>" +
                        "<td class='font-weight-bold text-wrap'>" +
                            "Paciente" +
                        "</td>" +
                        "<td class='text-wrap'>" +
                            result['ds_nome_paciente'] +
                        "</td>" +
                    "</tr>" +
                    "<tr>" +
                        "<td class='font-weight-bold text-wrap'>" +
                            "Idade" +
                        "</td>" +
                        "<td class='text-wrap'>" +
                            idade_paciente + " anos"+
                        "</td>" +
                    "</tr>" +
                    "<tr>" +
                        "<td class='font-weight-bold text-wrap'>" +
                            "Nº atendimento" +
                        "</td>" +
                        "<td class='text-wrap'>" +
                            result['nr_atendimento'] +
                        "</td>" +
                    "</tr>" +
                    "<tr>" +
                        "<td class='font-weight-bold text-wrap'>" +
                            "Leito" +
                        "</td>" +
                        "<td class='text-wrap'>" +
                            result['ds_leito_atual'] +
                        "</td>" +
                    "</tr>" +
                    "<tr>" +
                        "<td class='font-weight-bold text-wrap'>" +
                            "Data de entrada" +
                        "</td>" +
                        "<td class='text-wrap'>" +
                            data_entrada +
                        "</td>" +
                    "</tr>" +
                    "<tr>" +
                        "<td class='font-weight-bold text-wrap'>" +
                            "Tempo de internação" +
                        "</td>" +
                        "<td class='text-wrap'>" +
                            result['tempo_internacao'] + " dia(s)"+
                        "</td>" +
                    "</tr>" +
                    html_avaliacao_isolada+
                "</table>";

                $("#corpo_modal").html(html_leito);
                $("#modal_info").modal('show');
            },
            error : function(data){
                alert('Não foi possível abrir o detalhamento. Confira sua conexão!');
            }
        });
    }
    
</script>