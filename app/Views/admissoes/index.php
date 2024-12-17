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
    } 
?>

<div class="row">
    <?php
        //echo $calendario1;
        // echo $calendario->display(date('2022-01-01')); 
        // echo $calendario->display(date('2022-02-01')); 
        if(isset($_GET["a"])){
            if((int)$_GET["a"]==0){
                $ano_consulta = date("Y");
            }else{
                $ano_consulta = $_GET["a"];
            }
        }else{
            $ano_consulta = date("Y");
        }

        if(isset($_GET["m"])){
            if((int)$_GET["m"]==0){
                $mes_consulta = date("m");
            }else{
                $mes_consulta = $_GET["m"];
            }
        }else{
            $mes_consulta = date("m");
        }
        
        echo "<span class='text-center text-sm font-weight-bold'>METAS GERAIS</span>";
        
        ## METAS GERAIS CLÍNICA MEDICA

        $porcentagem_clinico_arredondado    = (int)$porcentagem_geral_clinica_medica;
        $porcentagem_grafico_clinico        = ceil($porcentagem_clinico_arredondado/5)*5;

        if($porcentagem_grafico_clinico>100){
            $porcentagem_grafico_clinico = 100;
        }

        if($porcentagem_clinico_arredondado<75){
            $cor_paciente_clinico = "danger";
            $cor_paciente_clinico = 'bg-gradient-'."$cor_paciente_clinico";
        }else if($porcentagem_clinico_arredondado>=75 && $porcentagem_clinico_arredondado<=85){
            $cor_paciente_clinico = "bg-warning";
        }else if($porcentagem_clinico_arredondado>85){
            $cor_paciente_clinico = "success";
            $cor_paciente_clinico = 'bg-gradient-'."$cor_paciente_clinico";
        }

        // if(date("Y")==$ano_consulta && date("m")==$mes_consulta){
            // echo '<span class="text-sm font-weight-bold">Clínica Médica:</span>
            //         <span class="text-xs">Meta: '.$meta_total_clinica_medica.' - Realizado: '.$total_clinica_medica.'</span>
            //         <span class="text-xs">Admissões Internas: '.$admissoes_internas_c_medica.' </span>
            //         <span class="text-xs">Porcentagem geral: '.number_format($porcentagem_geral_clinica_medica, 2,',','').'% </span>
            //         <div class="progress-wrapper w-75 mx-auto mb-4">
            //             <div class="progress-info">
            //                 <div class="progress-percentage">
            //                     <span class="text-xs font-weight-bold">'. number_format($porcentagem_geral_clinica_medica, 2,',','').'%</span>
            //                 </div>
            //             </div>
            //             <div class="progress">
            //                 <div class="progress-bar '.$cor_paciente_clinico.' w-'.$porcentagem_grafico_clinico.'" role="progressbar" aria-valuenow="'.$porcentagem_grafico_clinico.'" aria-valuemin="0" aria-valuemax="100">
            //                 </div>
            //             </div>
            //         </div>';
        // }else{
            echo '<span class="text-sm font-weight-bold">Clínica Médica:</span>
                    <span class="text-xs">Meta: '.$meta_total_clinica_medica.' - Realizado: '.$total_clinica_medica.'</span>
                    <span class="text-xs">Admissões Internas: '.$admissoes_internas_c_medica.' </span>
                    <span class="text-center text-xs">Porcentagem de 1/'.$mes_consulta.' à '.$dia_atual_realizado.'/'.$mes_consulta.': <b>'.$porcentagem_realizado_clm.'%</b></span>
                    <span class="text-center text-xs">Ideal realizado de 1/'.$mes_consulta.' à '.$dia_atual_realizado.'/'.$mes_consulta.': <b>'.(int)$ideal_realizado_atual_clm.'</b></span>
                    <div class="progress-wrapper w-75 mx-auto mb-4">
                        <div class="progress-info">
                            <div class="progress-percentage">
                                <span class="text-xs font-weight-bold">'. number_format($porcentagem_geral_clinica_medica, 2,',','').'%</span>
                            </div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar '.$cor_paciente_clinico.' w-'.$porcentagem_grafico_clinico.'" role="progressbar" aria-valuenow="'.$porcentagem_grafico_clinico.'" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>';
        // }
            
        ## METAS GERAIS AVC
        
        $porcentagem_avc_arredondado    = (int)$porcentagem_avc;
        $porcentagem_grafico_avc        = ceil($porcentagem_avc_arredondado/5)*5;
        if($porcentagem_grafico_avc>100){
            $porcentagem_grafico_avc = 100;
        }

        if($porcentagem_avc_arredondado<75){
            $cor_paciente_avc = "danger";
            $cor_paciente_avc = 'bg-gradient-'."$cor_paciente_avc";
        }else if($porcentagem_avc_arredondado>=75 && $porcentagem_avc_arredondado<=85){
            $cor_paciente_avc = "bg-warning";
        }else if($porcentagem_avc_arredondado>85){
            $cor_paciente_avc = "success";
            $cor_paciente_avc = 'bg-gradient-'."$cor_paciente_avc";
        }
        echo '<span class="text-sm font-weight-bold">AVC:</span>
                <span class="text-xs">Meta: '.$meta_total_avc.' - Realizado: '.$admissoes_externas_avc.'</span>
                <span class="text-xs">Admissões Internas: '.$admissoes_internas_avc.' </span>
                <span class="text-center text-xs">Porcentagem de 1/'.$mes_consulta.' à '.$dia_atual_realizado.'/'.$mes_consulta.': <b>'.$porcentagem_realizado_avc.'%</b></span>
                <span class="text-center text-xs">Ideal realizado de 1/'.$mes_consulta.' à '.$dia_atual_realizado.'/'.$mes_consulta.': <b>'.(int)$ideal_realizado_atual_avc.'</b></span>
                <div class="progress-wrapper w-75 mx-auto mb-4">
                    <div class="progress-info">
                        <div class="progress-percentage">
                            <span class="text-xs font-weight-bold">'. number_format($porcentagem_avc, 2,',','').'%</span>
                        </div>
                    </div>
                    <div class="progress">
                        <div class="progress-bar '.$cor_paciente_avc.' w-'.$porcentagem_grafico_avc.'" role="progressbar" aria-valuenow="'.$porcentagem_grafico_avc.'" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>';
        

        ## METAS GERAIS CTI
        
        $porcentagem_critico_arredondado    = (int)$porcentagem_geral_paciente_critico;
        $porcentagem_grafico_critico        = ceil($porcentagem_critico_arredondado/5)*5;

        if($porcentagem_grafico_critico>100){
            $porcentagem_grafico_critico = 100;
        }

        if($porcentagem_critico_arredondado<75){
            $cor_paciente_critico = "danger";
            $cor_paciente_critico = 'bg-gradient-'."$cor_paciente_critico";
        }else if($porcentagem_critico_arredondado>=75 && $porcentagem_critico_arredondado<=85){
            $cor_paciente_critico = "bg-warning";
        }else if($porcentagem_critico_arredondado>85){
            $cor_paciente_critico = "success";
            $cor_paciente_critico = 'bg-gradient-'."$cor_paciente_critico";
        }
        echo '<span class="text-sm font-weight-bold">Paciente Crítico:</span>
                <span class="text-xs">Meta: '.$meta_total_paciente_critico.' - Realizado: '.$total_paciente_critico.'</span>
                <span class="text-center text-xs">Porcentagem de 1/'.$mes_consulta.' à '.$dia_atual_realizado.'/'.$mes_consulta.': <b>'.$porcentagem_realizado_cti.'%</b></span>
                <span class="text-center text-xs">Ideal realizado de 1/'.$mes_consulta.' à '.$dia_atual_realizado.'/'.$mes_consulta.': <b>'.(int)$ideal_realizado_atual_cti.'</b></span>
                <div class="progress-wrapper w-75 mx-auto mb-4">
                    <div class="progress-info">
                        <div class="progress-percentage">
                            <span class="text-xs font-weight-bold">'. number_format($porcentagem_geral_paciente_critico, 2,',','').'%</span>
                        </div>
                    </div>
                    <div class="progress">
                        <div class="progress-bar '.$cor_paciente_critico.' w-'.$porcentagem_grafico_critico.'" role="progressbar" aria-valuenow="'.$porcentagem_grafico_critico.'" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>';

        ## METAS GERAIS CLINICA CIRURGICA
        
        $porcentagem_clinica_cirurgica_arredondado    = (int)$porcentagem_geral_clinica_cirurgica;
        $porcentagem_grafico_clinica_cirurgica        = ceil($porcentagem_clinica_cirurgica_arredondado/5)*5;

        if($porcentagem_grafico_clinica_cirurgica>100){
            $porcentagem_grafico_clinica_cirurgica = 100;
        }

        if($porcentagem_clinica_cirurgica_arredondado<75){
            $cor_paciente_clinica_cirurgica = "danger";
            $cor_paciente_clinica_cirurgica = 'bg-gradient-'."$cor_paciente_clinica_cirurgica";
        }else if($porcentagem_clinica_cirurgica_arredondado>=75 && $porcentagem_clinica_cirurgica_arredondado<=85){
            $cor_paciente_clinica_cirurgica = "bg-warning";
        }else if($porcentagem_clinica_cirurgica_arredondado>85){
            $cor_paciente_clinica_cirurgica = "success";
            $cor_paciente_clinica_cirurgica = 'bg-gradient-'."$cor_paciente_clinica_cirurgica";
        }
        echo '<span class="text-sm font-weight-bold">Clínica Cirúrgica:</span>
                <span class="text-xs">Meta: '.$meta_total_clinica_cirurgica.' - Realizado: '.$total_clinica_cirurgica.'</span>
                <span class="text-xs">Admissões Internas: '.$admissoes_internas_c_cirurgica.' </span>
                <span class="text-center text-xs">Porcentagem de 1/'.$mes_consulta.' à '.$dia_atual_realizado.'/'.$mes_consulta.': <b>'.$porcentagem_realizado_cir.'%</b></span>
                <span class="text-center text-xs">Ideal realizado de 1/'.$mes_consulta.' à '.$dia_atual_realizado.'/'.$mes_consulta.': <b>'.(int)$ideal_realizado_atual_cir.'</b></span>
                <div class="progress-wrapper w-75 mx-auto mb-4">
                    <div class="progress-info">
                        <div class="progress-percentage">
                            <span class="text-xs font-weight-bold">'. number_format($porcentagem_geral_clinica_cirurgica, 2,',','').'%</span>
                        </div>
                    </div>
                    <div class="progress">
                        <div class="progress-bar '.$cor_paciente_clinica_cirurgica.' w-'.$porcentagem_grafico_clinica_cirurgica.'" role="progressbar" aria-valuenow="'.$porcentagem_grafico_clinica_cirurgica.'" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>';

        ## METAS GERAIS HD
        
        $porcentagem_hd_arredondado    = (int)$porcentagem_geral_hd;
        $porcentagem_grafico_hd        = ceil($porcentagem_hd_arredondado/5)*5;

        if($porcentagem_grafico_hd>100){
            $porcentagem_grafico_hd = 100;
        }

        if($porcentagem_hd_arredondado<75){
            $cor_paciente_hd = "danger";
            $cor_paciente_hd = 'bg-gradient-'."$cor_paciente_hd";
        }else if($porcentagem_hd_arredondado>=75 && $porcentagem_hd_arredondado<=85){
            $cor_paciente_hd = "bg-warning";
        }else if($porcentagem_hd_arredondado>85){
            $cor_paciente_hd = "success";
            $cor_paciente_hd = 'bg-gradient-'."$cor_paciente_hd";
        }
        echo '<span class="text-sm font-weight-bold">Hospital Dia:</span>
                <span class="text-xs">Meta: '.$meta_admissoes_hd.' - Realizado: '.$admissoes_hd.'</span>
                <span class="text-center text-xs">Porcentagem de 1/'.$mes_consulta.' à '.$dia_atual_realizado.'/'.$mes_consulta.': <b>'.$porcentagem_realizado_hd.'%</b></span>
                <span class="text-center text-xs">Ideal realizado de 1/'.$mes_consulta.' à '.$dia_atual_realizado.'/'.$mes_consulta.': <b>'.(int)$ideal_realizado_atual_hd.'</b></span>
                <div class="progress-wrapper w-75 mx-auto mb-4">
                    <div class="progress-info">
                        <div class="progress-percentage">
                            <span class="text-xs font-weight-bold">'. number_format($porcentagem_geral_hd, 2,',','').'%</span>
                        </div>
                    </div>
                    <div class="progress">
                        <div class="progress-bar '.$cor_paciente_hd.' w-'.$porcentagem_grafico_hd.'" role="progressbar" aria-valuenow="'.$porcentagem_grafico_hd.'" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>';


        if($ano_calendario==0 || $mes_calendario==0){
            $calendario->display();
        }else{
            $calendario->stylesheet();
            echo($calendario->draw($ano_calendario.'-'.$mes_calendario.'-01'));
        }

        echo '<a href="admissoes/meses?a='.$ano_consulta.'" class="mt-4 azul-hospital botao-inverso-hospital btn btn-rounded">Calendário anual</a>';
    ?>
    
    
</div>


<!-- tabela -->

<!-- Modal -->
<div class="modal fade" id="modal_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
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
        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<script>
    function abrirModalInformacoes(dia,mes,ano){
        var dateObj = new Date();
        var month   = dateObj.getUTCMonth()+1; //months from 1-12
        var day     = dateObj.getUTCDate();
        var year    = dateObj.getUTCFullYear();
        $("#exampleModalLabel").html("Detalhes "+("00" + dia).slice(-2)+"/"+("00" + mes).slice(-2)+"/"+ano+"");
        $.ajax({
            url : "<?php echo site_url('/admissoes/retornaDetalhesAdmissoesMes');?>",
            type : 'POST',
            dataType: "JSON",
            data : {
                        "dia": dia,
                        "mes": mes,
                        "ano": ano
                    },
            success : function(data){
                var result  = data;
                var html_corpo_tabela = "<span class='font-weight-bolder justify-center'>Admissões</span><table class='table align-items-center justify-content-center' width='100%'><thead><tr><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2'>Tipo</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2'>Setor</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2'>Quantidade</th></tr></thead><tbody>";
                var tipo_geral_ad = "";
                let total = 0;
                let total_internas = 0;
                let total_externas = 0;
                let total_hd       = 0;

                for(var i = 0; i<result.length; i++){
                    if(result[i].IE_TIPO_ADMISSAO=='E'){
                        tipo_geral_ad = "Externa";
                    }else if(result[i].IE_TIPO_ADMISSAO=='I'){
                        tipo_geral_ad = "Interna";
                    }else if(result[i].IE_TIPO_ADMISSAO=='HD'){
                        tipo_geral_ad = "Hosp. Dia";
                    }

                    total = total+parseInt(result[i].QUANTIDADE);

                    if(tipo_geral_ad=='Interna'){
                        total_internas = total_internas+parseInt(result[i].QUANTIDADE);
                    }else if(tipo_geral_ad=='Externa'){
                        total_externas = total_externas+parseInt(result[i].QUANTIDADE);
                    }else if(tipo_geral_ad=='Hosp. Dia'){
                        total_hd = total_hd+parseInt(result[i].QUANTIDADE);
                    }

                    html_corpo_tabela += "<tr>"+
                                            "<td class='text-xs text-center font-weight-bold'>"+
                                                tipo_geral_ad+
                                            "</td>"+
                                            "<td class='text-xs text-center font-weight-bold'>"+
                                                result[i].DS_SETOR_ATENDIMENTO+
                                            "</td>"+
                                            "<td class='text-xs text-center font-weight-bold'>"+
                                                result[i].QUANTIDADE+
                                            "</td>"+
                                        "</tr>";
                    
                }
                html_corpo_tabela +=    "<tr>"+
                                            "<td colspan='2' class='text-xs text-center font-weight-bolder'>"+
                                                "Total Internas"+
                                            "</td>"+
                                            "<td class='text-xs text-center font-weight-bold'>"+
                                                total_internas+
                                            "</td>"+
                                        "</tr>"+
                                        "<tr>"+
                                            "<td colspan='2' class='text-xs text-center font-weight-bolder'>"+
                                                "Total Externas"+
                                            "</td>"+
                                            "<td class='text-xs text-center font-weight-bold'>"+
                                                total_externas+
                                            "</td>"+
                                        "</tr>"+
                                        "<tr>"+
                                            "<td colspan='2' class='text-xs text-center font-weight-bolder'>"+
                                                "Total HD"+
                                            "</td>"+
                                            "<td class='text-xs text-center font-weight-bold'>"+
                                                total_hd+
                                            "</td>"+
                                        "</tr>"+
                                        "<tr>"+
                                            "<td colspan='2' class='text-xs text-center font-weight-bolder'>"+
                                                "Total Geral"+
                                            "</td>"+
                                            "<td class='text-xs text-center font-weight-bold'>"+
                                                total+
                                            "</td>"+
                                        "</tr>";
                html_corpo_tabela += "</tbody></table>";
                
                
                // $("#corpo_tabela_ocupacao").html(html_corpo_tabela);
                // $("#nome_area").text($("#titulo"+id_area).text());
                
                // $("#tabela_detalhes").show();
                // location.href = "#tabela_detalhes";

                $("#corpo_modal").html(html_corpo_tabela);

                $.ajax({
                    url : "<?php echo site_url('/admissoes/retornaDetalhesOfertasDiarias');?>",
                    type : 'POST',
                    dataType: "JSON",
                    data : {
                                "dia": dia,
                                "mes": mes,
                                "ano": ano
                            },
                    success : function(data1){
                        var result1  = data1;
                        if(result1.length>0){
                            var html_corpo_adicional = "<span class='font-weight-bolder justify-center mt-2'>Ofertas</span><table class='table align-items-center justify-content-center' width='100%'><thead><tr><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2'>Setor</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2'>Tipo</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2'>Quantidade</th></tr></thead><tbody>";
                            let total = 0;
                            for(var i = 0; i<result1.length; i++){
                                if(result1[i].ds_setor_solicitado=="UDC - Unidade de Decisão Clínica"){
                                    result1[i].ds_setor_solicitado = "UDC";
                                }
                                total = total+parseInt(result1[i].quantidade);
                            
                                html_corpo_adicional += "<tr>"+
                                                            "<td class='text-xs text-center font-weight-bold'>"+
                                                                result1[i].ds_setor_solicitado+
                                                            "</td>"+
                                                            "<td class='text-xs text-center font-weight-bold'>"+
                                                                result1[i].ds_tipo_vaga+
                                                            "</td>"+
                                                            "<td class='text-xs text-center font-weight-bold'>"+
                                                                result1[i].quantidade+
                                                            "</td>"+
                                                        "</tr>";
                                
                            }
                            html_corpo_adicional += "<tr>"+
                                                        "<td colspan='2' class='text-xs text-center font-weight-bolder'>"+
                                                            "Total"+
                                                        "</td>"+
                                                        "<td class='text-xs text-center font-weight-bold'>"+
                                                            total+
                                                        "</td>"+
                                                    "</tr>";
                            html_corpo_adicional += "</tbody></table>";
                            
                            $("#corpo_modal").append(html_corpo_adicional);
                        }
                    },
                    error : function(data1){
                        alert('Não foi possível buscar os detalhes das admissões.');
                    }
                });

                $("#modal_info").modal('show');
            },
            error : function(data){
                alert('Não foi possível buscar os detalhes das admissões.');
            }
        });

        
    }
   
</script>