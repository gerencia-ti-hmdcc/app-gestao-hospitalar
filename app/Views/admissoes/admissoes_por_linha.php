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

        echo "<span class='text-center font-weight-bold'>$linha_cuidado</span>";
        echo "<span class='text-center text-sm font-weight-bold'>METAS GERAIS</span>";
        
        $porcentagem_arredondado    = (int)$porcentagem_geral;
        $porcentagem_grafico        = ceil($porcentagem_arredondado/5)*5;

        if($porcentagem_arredondado<75){
            $cor_grafico = "danger";
            $cor_grafico = 'bg-gradient-'."$cor_grafico";
        }else if($porcentagem_arredondado>=75 && $porcentagem_arredondado<=85){
            $cor_grafico = "bg-warning";
        }else if($porcentagem_arredondado>85){
            $cor_grafico = "success";
            $cor_grafico = 'bg-gradient-'."$cor_grafico";
        }
        
        if($_GET["s"]==13){
            if($porcentagem_grafico>100){
                $porcentagem_grafico = 100;
            }
            $admissoes_condicao_linha = '<span class="mt-4 text-xs">Meta: '.$meta_externa.' - Realizado: '.$total_realizado.'</span>
                                        <span class="text-center text-xs">Porcentagem de 1/'.$mes_consulta.' à '.$dia_atual_realizado.'/'.$mes_consulta.': <b>'.$porcentagem_realizado_externo.'%</b></span>
                                        <span class="text-center text-xs">Ideal realizado de 1/'.$mes_consulta.' à '.$dia_atual_realizado.'/'.$mes_consulta.': <b>'.(int)$ideal_realizado_externo.'</b></span>
                                        <div class="progress-wrapper w-75 mx-auto mb-4">
                                            <div class="progress-info">
                                                <div class="progress-percentage">
                                                    <span class="text-xs font-weight-bold">'. number_format($porcentagem_geral, 2,',','').'%</span>
                                                </div>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar '.$cor_grafico.' w-'.$porcentagem_grafico.'" role="progressbar" aria-valuenow="'.$porcentagem_grafico.'" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>';
        }else if($_GET["s"]==1){
            $porcentagem_arredondado_interna    = ($admissoes_internas_gerais/$meta_interna)*100;
            $porcentagem_arredondado_externa    = ($admissoes_externas_gerais/$meta_externa)*100;
            $porcentagem_grafico_interna        = ceil($porcentagem_arredondado_interna/5)*5;
            $porcentagem_grafico_externa        = ceil($porcentagem_arredondado_externa/5)*5;

            if($porcentagem_grafico_externa>100){
                $porcentagem_grafico_externa = 100;
            }

            if($porcentagem_grafico_interna>100){
                $porcentagem_grafico_interna = 100;
            }

            if($porcentagem_arredondado_interna<75){
                $cor_grafico_interna = "danger";
                $cor_grafico_interna = 'bg-gradient-'."$cor_grafico_interna";
            }else if($porcentagem_arredondado_interna>=75 && $porcentagem_arredondado_interna<=85){
                $cor_grafico_interna = "bg-warning";
            }else if($porcentagem_arredondado_interna>85){
                $cor_grafico_interna = "success";
                $cor_grafico_interna = 'bg-gradient-'."$cor_grafico_interna";
            }

            if($porcentagem_arredondado_externa<75){
                $cor_grafico_externa = "danger";
                $cor_grafico_externa = 'bg-gradient-'."$cor_grafico_externa";
            }else if($porcentagem_arredondado_externa>=75 && $porcentagem_arredondado_externa<=85){
                $cor_grafico_externa = "bg-warning";
            }else if($porcentagem_arredondado_externa>85){
                $cor_grafico_externa = "success";
                $cor_grafico_externa = 'bg-gradient-'."$cor_grafico_externa";
            }

            $admissoes_condicao_linha = '<span class="mt-4 text-xs">Meta interna: '.$meta_interna.' - Realizado: '.$admissoes_internas_gerais.'</span>
                                        <span class="text-center text-xs">Porcentagem de 1/'.$mes_consulta.' à '.$dia_atual_realizado.'/'.$mes_consulta.': <b>'.$porcentagem_realizado_interno.'%</b></span>
                                        <span class="text-center text-xs">Ideal realizado de 1/'.$mes_consulta.' à '.$dia_atual_realizado.'/'.$mes_consulta.': <b>'.(int)$ideal_realizado_interno.'</b></span>
                                        <div class="progress-wrapper w-75 mx-auto mb-4">
                                            <div class="progress-info">
                                                <div class="progress-percentage">
                                                    <span class="text-xs font-weight-bold">'. number_format($porcentagem_arredondado_interna, 2,',','').'%</span>
                                                </div>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar '.$cor_grafico_interna.' w-'.$porcentagem_grafico_interna.'" role="progressbar" aria-valuenow="'.$porcentagem_grafico_interna.'" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                        <span class="mt-4 text-xs">Meta externa: '.$meta_externa.' - Realizado: '.$admissoes_externas_gerais.'</span>
                                        <span class="text-center text-xs">Porcentagem de 1/'.$mes_consulta.' à '.$dia_atual_realizado.'/'.$mes_consulta.': <b>'.$porcentagem_realizado_externo.'%</b></span>
                                        <span class="text-center text-xs">Ideal realizado de 1/'.$mes_consulta.' à '.$dia_atual_realizado.'/'.$mes_consulta.': <b>'.(int)$ideal_realizado_externo.'</b></span>
                                        <div class="progress-wrapper w-75 mx-auto mb-4">
                                            <div class="progress-info">
                                                <div class="progress-percentage">
                                                    <span class="text-xs font-weight-bold">'. number_format($porcentagem_arredondado_externa, 2,',','').'%</span>
                                                </div>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar '.$cor_grafico_externa.' w-'.$porcentagem_grafico_externa.'" role="progressbar" aria-valuenow="'.$porcentagem_grafico_externa.'" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>';
        }else{
            if($porcentagem_grafico>100){
                $porcentagem_grafico = 100;
            }
            $admissoes_condicao_linha = '<span class="mt-4 text-xs">Meta: '.$meta_externa.' - Realizado: '.$total_realizado.'</span>
                                        <span class="text-xs">Admissões Internas: '.$admissoes_internas_gerais.' </span>
                                        <span class="text-xs">Admissões Externas: '.$admissoes_externas_gerais.' </span>
                                        <span class="text-center text-xs">Porcentagem de 1/'.$mes_consulta.' à '.$dia_atual_realizado.'/'.$mes_consulta.': <b>'.$porcentagem_realizado_externo.'%</b></span>
                                        <span class="text-center text-xs">Ideal realizado de 1/'.$mes_consulta.' à '.$dia_atual_realizado.'/'.$mes_consulta.': <b>'.(int)$ideal_realizado_externo.'</b></span>
                                        <div class="progress-wrapper w-75 mx-auto mb-4">
                                            <div class="progress-info">
                                                <div class="progress-percentage">
                                                    <span class="text-xs font-weight-bold">'. number_format($porcentagem_geral, 2,',','').'%</span>
                                                </div>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar '.$cor_grafico.' w-'.$porcentagem_grafico.'" role="progressbar" aria-valuenow="'.$porcentagem_grafico.'" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>';
        }
        echo    $admissoes_condicao_linha;

        
        if($ano_calendario==0 || $mes_calendario==0){
            $calendario->display();
        }else{
            $calendario->stylesheet();
            echo($calendario->draw($ano_calendario.'-'.$mes_calendario.'-01'));
        }
        
        echo '<a href="'.$diretorio_raiz.'admissoes/meses?a='.$ano_consulta.'" class="mt-4 azul-hospital botao-inverso-hospital btn btn-rounded">Calendário anual</a>';
        
        // echo    '<h3><b>Setembro 2022</b></h3>
        //         <h5 style="text-align: right;color: green">Meta mês: -</h5>
        //         <h5 style="text-align: right;color: green">Meta realizado: -</h5>
        //         <div class="w-full flex justify-center flex-wrap">';
        // for($i = 1; $i<=27; $i++){
        //     echo "<div class='card w-1/7 mx-2 my-2 flex'>
        //                 <div class='card-header'>
        //                     <div class='row'>
        //                         <div class='col-lg-6 col-7'>
        //                             <h6>Dia $i</h6>
        //                         </div>
        //                         <div class='col-lg-6 col-5 my-auto text-end'>
        //                             <div class='dropdown float-lg-end pe-4'>
        //                                 <a class='cursor-pointer' onclick='abrirModalInformacoes(\"modal_grafico_geral\")' aria-expanded='false'>
        //                                     <i class='fa fa-question-circle text-secondary'></i>
        //                                 </a>
        //                             </div>
        //                         </div>
        //                     </div>
        //                 </div>
        //                 <div class='card-body p-3'>
        //                     <span>Ofertadas: 10</span><br />
        //                     <span>Admissoes: 9</span><br />
        //                     <span>Saldo: - </span>
        //                 </div>
        //             </div>";
        // }
        // echo '</div>';
    ?>
    <!--<div class="col-lg-12 mb-<?php /*echo $variavel_controle_margem_tv;*/?>">
         <div class="card z-index-2">
            <div class="card-header pb-0">
                <div class="row">
                    <div class="col-lg-6 col-7">
                        <h6>Gráfico Geral de Ocupação - <span style='font-size: 12px' id='data_ult_att' name='data_ult_att'></span></h6>
                    </div>
                    <div class="col-lg-6 col-5 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="cursor-pointer" onclick="abrirModalInformacoes('modal_grafico_geral')" aria-expanded="false">
                                <i class="fa fa-question-circle text-secondary"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="bg-gradient-dark border-radius-lg py-3 pe-1 mb-3">
                    <div class="chart">
                    <canvas id="chart-bars" class="chart-canvas" height="<?php /*echo $tamanho_grafico;*/?>"></canvas>
                    </div>
                </div>
            </div>
        </div> 
    </div>
    <div class="row" id="divPercentualGeral" name="divPercentualGeral">

    </div>-->
    
</div>


<!-- tabela -->


<!-- <div class="row my-4">
    <div class="col-lg-12 col-md-12 mb-<?php /*echo $variavel_controle_margem_tv;*/?>" id="divGeral" name="divGeral">
        <div class="card" id="tabela_detalhes" name="tabela_detalhes">
            <div class="card-header pb-0">
                <div class="row">
                    <div class="col-lg-6 col-7">
                        <h6 id='nome_area' name='nome_area'></h6>
                    </div>
                    <div class="col-lg-6 col-5 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="cursor-pointer" onclick="abrirModalInformacoes('modal_tabela_setor')" aria-expanded="false"> 
                                <i class="fa fa-question-circle text-secondary"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                 <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th style="background-color: white" class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Setor</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Linha Cuidado</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ocupação</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total de leitos</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Isolados</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ocupados</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Livres</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Reservados</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Higienização</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Manutenção</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alta</th>
                            </tr>
                        </thead>
                        <tbody id="corpo_tabela_ocupacao" name="corpo_tabela_ocupacao">
                            
                        </tbody>
                    </table>
                </div>
                <input type='hidden' value='<?php /*echo $usuario_logado["painel_variavel_controle"];*/?>' id='painel_variavel_controle' name='painel_variavel_controle'/>
            </div>
        </div>
    </div>
</div> -->

<!-- Modal -->
<div class="modal fade" id="modal_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detalhes</h5>
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
    function abrirModalInformacoes(dia,mes,ano,agrupamento){
        $.ajax({
            url : "<?php echo site_url('retornaDetalhesAdmissoesMesPorLinha');?>",
            type : 'POST',
            dataType: "JSON",
            data : {
                        "dia": dia,
                        "mes": mes,
                        "ano": ano,
                        "agrupamento": agrupamento
                    },
            success : function(data){
                var result  = data;
                var html_corpo_tabela = "<table class='table align-items-center justify-content-center' width='100%'><thead><tr><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2'>Tipo</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2'>Setor</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2'>Quantidade</th></tr></thead><tbody>";
                var tipo_geral_ad = "";
                for(var i = 0; i<result.length; i++){
                    if(result[i].IE_TIPO_ADMISSAO=='E'){
                        tipo_geral_ad = "Externa";
                    }else if(result[i].IE_TIPO_ADMISSAO=='I'){
                        tipo_geral_ad = "Interna";
                    }else if(result[i].IE_TIPO_ADMISSAO=='HD'){
                        tipo_geral_ad = "Hosp. Dia";
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
                    // if(parseInt(result[i].CD_SETOR_ATENDIMENTO)==129){
                    //     var porcentagem_ocup = (((parseInt(result[i].NR_UNID_OCUP) + parseInt(result[i].NR_UNIDADES_HIGIENIZACAO) + parseInt(result[i].NR_UNID_AGUARD_HIGIEN)) / parseInt(result[i].NR_UNIDADES_SETOR))*100);
                    // }else{
                    //     var porcentagem_ocup = parseInt(result[i].PR_OCUPACAO_TOTAL);
                    // }
                    // unidades_reservadas = parseInt(result[i].NR_UNIDADES_RESERVADAS);
                    
                }

                html_corpo_tabela += "</tbody></table>";
                
                // $("#corpo_tabela_ocupacao").html(html_corpo_tabela);
                // $("#nome_area").text($("#titulo"+id_area).text());
                
                // $("#tabela_detalhes").show();
                // location.href = "#tabela_detalhes";

                $("#corpo_modal").html(html_corpo_tabela);
                $("#modal_info").modal('show');
            },
            error : function(data){
                alert('Não foi possível buscar os detalhes das admissões.');
            }
        });

        
    }
    // function abrirDivDetalhes(id_area){
    //     /*if($("#tabela_detalhes").is(':visible') && $("#id_area_antigo").val()==id_area){
    //         $("#tabela_detalhes").hide();
    //     }else{*/
    //         $("#tabela_detalhes2").remove();
    //         $.ajax({
    //             url : "<?php /*echo site_url('/dashboard/percentuaisSetorOcupacao');*/?>",
    //             type : 'POST',
    //             dataType: "JSON",
    //             data : {
    //                         "id_area": id_area
    //                     },
    //             success : function(data){
    //                 var result  = data;
    //                 var html_corpo_tabela = "";
    //                 var percent = 0.0;
    //                 var livres_1 = 0;

    //                 var unidades_reservadas = 0;
    //                 for(var i = 0; i<result.length; i++){
    //                     // if(parseInt(result[i].CD_SETOR_ATENDIMENTO)==129){
    //                     //     var porcentagem_ocup = (((parseInt(result[i].NR_UNID_OCUP) + parseInt(result[i].NR_UNIDADES_HIGIENIZACAO) + parseInt(result[i].NR_UNID_AGUARD_HIGIEN)) / parseInt(result[i].NR_UNIDADES_SETOR))*100);
    //                     // }else{
    //                     //     var porcentagem_ocup = parseInt(result[i].PR_OCUPACAO_TOTAL);
    //                     // }
    //                     unidades_reservadas = parseInt(result[i].NR_UNIDADES_RESERVADAS);
    //                     temp_indisponiveis  = parseInt(unidades_reservadas)+parseInt(result[i].NR_UNIDADES_HIGIENIZACAO)+parseInt(result[i].QT_UNIDADES_ALTA)+parseInt(result[i].QT_UNIDADE_MANUTENCAO)+parseInt(result[i].NR_UNID_AGUARD_HIGIEN)+parseInt(result[i].QT_UNIDADES_ISOLAMENTO);
    //                     if(parseInt(result[i].CD_SETOR_ATENDIMENTO)==129 || (parseInt(result[i].CD_SETOR_ATENDIMENTO)==145) || (parseInt(result[i].CD_SETOR_ATENDIMENTO)==83)){
    //                         var porcentagem_ocup   = (((parseInt(result[i].NR_UNID_OCUP)) / parseInt(result[i].NR_UNIDADES_SETOR))*100.00).toFixed(2);
    //                         livres_1  = parseInt(result[i].NR_UNIDADES_SETOR) - parseInt(result[i].NR_UNID_OCUP) -temp_indisponiveis; /*- parseInt(result[i].NR_UNIDADES_HIGIENIZACAO) - parseInt(result[i].NR_UNID_AGUARD_HIGIEN) - parseInt(result[i].QT_UNIDADES_ALTA)*/
    //                         if(parseInt(livres_1)<0 && parseInt(result[i].CD_SETOR_ATENDIMENTO)==129){
    //                             //ALTERAÇÃO REALIZADA POR CONTA DO NÚMERO NEGATIVO EM "LEITOS LIVRES" - O NUMERO DE OCUPADOS VEM COM DISTINCT POR LEITO DO BANCO E NO CÓDIGO ABATE-SE AGORA A QUANTIDADE NEGATIVA DOS LEITOS RESERVADOS
    //                             unidades_reservadas = unidades_reservadas - (livres_1*-1);
    //                             livres_1 = 0
    //                         }
    //                     }else{
    //                         var porcentagem_ocup   = parseFloat(result[i].PR_OCUPACAO).toFixed(2);
    //                         livres_1  = result[i].NR_UNIDADES_LIVRES;
    //                     }
    //                     porcentagem_ocup = Math.ceil(porcentagem_ocup/5)*5;
    //                     var cor_per = "";
    //                     // if(porcentagem_ocup<=30){
    //                     //     cor_per = "success";
    //                     // }else if(porcentagem_ocup>30 && porcentagem_ocup<=60){
    //                     //     cor_per = "info";
    //                     // }else if(porcentagem_ocup>60 && porcentagem_ocup<80){
    //                     //     cor_per = "info";
    //                     // }else if(porcentagem_ocup>=80 && porcentagem_ocup<=90){
    //                     //     cor_per = "warning";
    //                     // }else{
    //                     //     cor_per = "danger";
    //                     // }
    //                     if(porcentagem_ocup<=75){
    //                         cor_per = "danger";
    //                         cor_per = 'bg-gradient-'+cor_per;
    //                     }else if(porcentagem_ocup>75 && porcentagem_ocup<=85){
    //                         cor_per = "primary";
    //                         cor_per = 'bg-gradient-'+cor_per;
    //                     }else if(porcentagem_ocup>85 && porcentagem_ocup<=90){
    //                         cor_per = "bg-warning";
    //                     }else if(porcentagem_ocup>90 && porcentagem_ocup<=95){
    //                         cor_per = "info";
    //                         cor_per = 'bg-gradient-'+cor_per;
    //                     }else{
    //                         cor_per = "success";
    //                         cor_per = 'bg-gradient-'+cor_per;
    //                     }
    //                     var qt_ocupadas = result[i].NR_UNID_OCUP;
    //                     //if(result[i].CD_SETOR_ATENDIMENTO==129){qt_ocupadas = (result[i].NR_UNID_OCUP)*-1}else{qt_ocupadas = result[i].NR_UNID_OCUP}
                        
    //                     // if(parseInt(result[i].CD_SETOR_ATENDIMENTO)==129){
    //                     //     percent   = (((parseInt(result[i].NR_UNID_OCUP) + parseInt(result[i].NR_UNIDADES_HIGIENIZACAO) + parseInt(result[i].NR_UNID_AGUARD_HIGIEN)) / parseInt(result[i].NR_UNIDADES_SETOR))*100.00).toFixed(2); 
    //                     //     livres_1  = parseInt(result[i].NR_UNIDADES_SETOR) - parseInt(result[i].NR_UNID_OCUP) - parseInt(result[i].NR_UNIDADES_HIGIENIZACAO) - parseInt(result[i].NR_UNID_AGUARD_HIGIEN);
    //                     // }else{
    //                     //     percent   = result[i].PR_OCUPACAO_TOTAL;
    //                     //     livres_1  = result[i].NR_UNIDADES_LIVRES;
    //                     // }

    //                     unidades_reservadas = parseInt(result[i].NR_UNIDADES_RESERVADAS);
    //                     if(parseInt(result[i].CD_SETOR_ATENDIMENTO)==129 || (parseInt(result[i].CD_SETOR_ATENDIMENTO)==145) || (parseInt(result[i].CD_SETOR_ATENDIMENTO)==83)){
    //                         percent   = (((parseInt(result[i].NR_UNID_OCUP)) / parseInt(result[i].NR_UNIDADES_SETOR))*100.00).toFixed(2);
    //                         livres_1  = parseInt(result[i].NR_UNIDADES_SETOR) - parseInt(result[i].NR_UNID_OCUP) -temp_indisponiveis; /*- parseInt(result[i].NR_UNIDADES_HIGIENIZACAO) - parseInt(result[i].NR_UNID_AGUARD_HIGIEN) - parseInt(result[i].QT_UNIDADES_ALTA)*/
    //                         if(parseInt(livres_1)<0 && parseInt(result[i].CD_SETOR_ATENDIMENTO)==129){
    //                             unidades_reservadas = unidades_reservadas - (livres_1*-1);
    //                             livres_1 = 0
    //                         }
    //                     }else{
    //                         percent   = parseFloat(result[i].PR_OCUPACAO).toFixed(2);
    //                         livres_1  = result[i].NR_UNIDADES_LIVRES;
    //                     }

    //                     html_corpo_tabela += '<tr>'+
    //                                             '<td>'+
    //                                                 result[i]["DS_SETOR_ATENDIMENTO"]+
    //                                             '</td>'+
    //                                             '<td>'+
    //                                                 '<div class="d-flex px-2 py-1">'+
    //                                                     result[i].DS_LINHA_CUIDADO+
    //                                                 '</div>'+
    //                                             '</td>'+
    //                                             '<td class="align-middle">'+
    //                                                '<div class="progress-wrapper w-75 mx-auto">'+
    //                                                     '<div class="progress-info">'+
    //                                                         '<div class="progress-percentage">'+
    //                                                             '<span class="text-xs font-weight-bold">'+percent+'%</span>'+
    //                                                         '</div>'+
    //                                                     '</div>'+
    //                                                     '<div class="progress">'+
    //                                                         '<div class="progress-bar '+cor_per+' w-'+porcentagem_ocup.toString()+'" role="progressbar" aria-valuenow="'+porcentagem_ocup+'" aria-valuemin="0" aria-valuemax="100"></div>'+
    //                                                     '</div>'+
    //                                                 '</div>'+
    //                                             '</td>'+
    //                                             '<td class="align-middle text-center text-sm">'+
    //                                                 '<span class="text-xs font-weight-bold">'+result[i].NR_UNIDADES_SETOR+'</span>'+
    //                                             '</td>'+
    //                                             '<td class="align-middle text-center text-sm">'+
    //                                                 '<span class="text-xs font-weight-bold">'+result[i].QT_UNIDADES_ISOLAMENTO+'</span>'+
    //                                             '</td>'+
    //                                             '<td class="align-middle text-center text-sm">'+
    //                                                 '<span class="text-xs font-weight-bold">'+qt_ocupadas+'</span>'+
    //                                             '</td>'+
    //                                             '<td class="align-middle text-center text-sm">'+
    //                                                 '<span class="text-xs font-weight-bold">'+livres_1+'</span>'+
    //                                             '</td>'+
    //                                             '<td class="align-middle text-center text-sm">'+
    //                                                 '<span class="text-xs font-weight-bold">'+unidades_reservadas+'</span>'+
    //                                             '</td>'+
    //                                             '<td class="align-middle text-center text-sm">'+
    //                                                 '<span class="text-xs font-weight-bold">'+(parseInt(result[i].NR_UNIDADES_HIGIENIZACAO) + parseInt(result[i].NR_UNID_AGUARD_HIGIEN))+'</span>'+
    //                                             '</td>'+
    //                                             '<td class="align-middle text-center text-sm">'+
    //                                                 '<span class="text-xs font-weight-bold">'+(parseInt(result[i].NR_UNIDADES_INTERDITADAS) + parseInt(result[i].QT_UNIDADE_MANUTENCAO))+'</span>'+
    //                                             '</td>'+
    //                                             '<td class="align-middle text-center text-sm">'+
    //                                                 '<span class="text-xs font-weight-bold">'+result[i].QT_UNIDADES_ALTA+'</span>'+
    //                                             '</td>'+
    //                                         '</tr>';
    //                 }
                    
    //                 $("#corpo_tabela_ocupacao").html(html_corpo_tabela);
    //                 $("#nome_area").text($("#titulo"+id_area).text());
                    
    //                 $("#tabela_detalhes").show();
    //                 location.href = "#tabela_detalhes";
    //             },
    //             error : function(data){
    //                 alert('Não foi possível definir os detalhes do setor');
    //             }
    //         });
    //     //}
    // }
</script>