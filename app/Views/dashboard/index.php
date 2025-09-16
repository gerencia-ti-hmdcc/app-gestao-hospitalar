<?php
    $this->session = \Config\Services::session();
    
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
        } else {
            $usuario_logado["painel_variavel_controle"] = 0;
        }
    } ?>

<?php  ?>
<div class="row">
    <div class="col-lg-12 mb-<?php echo $variavel_controle_margem_tv;?>">
        <div class="card z-index-2">
            <div class="card-header pb-0">
                <div class="row">
                    <div class="col-lg-6 col-7">
                        <h6>Gráfico Geral de Ocupação - <span style='font-size: 12px' id='data_ult_att' name='data_ult_att'></span></h6>
                    </div>
                    <div class="col-lg-6 col-5 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="cursor-pointer" onclick="abrirModalInformacoes('modal_grafico_geral')" aria-expanded="false"> <!-- id="dropdownTable" data-bs-toggle="dropdown" -->
                                <i class="fa fa-question-circle text-secondary"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="bg-gradient-dark border-radius-lg py-3 pe-1 mb-3">
                    <div class="chart">
                    <canvas id="chart-bars" class="chart-canvas" height="<?php echo $tamanho_grafico;?>"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row w-full flex" id="divPercentualGeral" name="divPercentualGeral">

    </div>
    
</div>


<!-- tabela -->


<div class="row my-4">
    <div class="col-lg-12 col-md-12 mb-<?php echo $variavel_controle_margem_tv;?>" id="divGeral" name="divGeral"><!--mb-md-0-->
        <div class="card" id="tabela_detalhes" name="tabela_detalhes">
            <div class="card-header pb-0">
                <div class="row">
                    <div class="col-lg-6 col-7">
                        <h6 id='nome_area' name='nome_area'></h6>
                    </div>
                    <div class="col-lg-6 col-5 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="cursor-pointer" onclick="abrirModalInformacoes('modal_tabela_setor')" aria-expanded="false"> <!-- id="dropdownTable" data-bs-toggle="dropdown" -->
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
                <input type='hidden' value='<?php echo $usuario_logado["painel_variavel_controle"];?>' id='painel_variavel_controle' name='painel_variavel_controle'/>
            </div>
        </div>
    </div>
        
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
    function abrirModalInformacoes(modal){
        if(modal=='modal_grafico_geral'){
            $("#corpo_modal").html("Percentual geral das linhas de cuidado e Clínica Cirúrgica.<br /><small>Clique nos cards abaixo para visualizar o quantitativo das linhas de cuidado por setor.</small>");
            $("#modal_info").modal('show');
        }else if(modal=='modal_tabela_setor'){
            $("#corpo_modal").html('<b>Linha de cuidado</b> - Linha de cuidado ao qual o setor faz parte.<br />'+
                                    '<b>Setor</b> - Setor referente à linha de cuidado do hospital.<br />'+
                                    '<b>Ocupação</b> - Percentual de ocupação de determinado setor.<br />'+
                                    '<b>Total de leitos</b> - Numero de unidades cadastradas no setor desconsiderando os leitos virtuais.<br />'+
                                    '<b>Leitos ocupados</b> - Unidades ocupadas desconsiderando as unidades em higienização.<br />'+
                                    '<b>Leitos livres</b> - Total de unidades disponíveis desconsiderando as reservadas, em higienização e manutenção.<br />'+
                                    '<b>Leitos reservados</b> - Leitos que já estão reservados para um atendimento futuro.<br />'+
                                    '<b>Leitos em isolamento</b> - Quantidade de leitos em isolamento sem pacientes (precaução).<br />'+
                                    '<b>Higienização</b> - Leitos que estão em higienização ou aguardando higienização.<br />'+
                                    '<b>Manutenção</b> - Leitos que estão em manutenção e/ou interditados.<br />'+
                                    '<b>Alta</b> - Leitos em processo de alta.<br />'+
                                    '<b>Indisponíveis</b> - Leitos isolados e em manutenção.<br />'+
                                    '<b>Reserv./ Higien./ Alta</b> - Leitos reservados, em higienização ou aguardando higienização e em processo de alta.');
            $("#modal_info").modal('show');
        }
    }
    function abrirDivDetalhes(id_area){
        /*if($("#tabela_detalhes").is(':visible') && $("#id_area_antigo").val()==id_area){
            $("#tabela_detalhes").hide();
        }else{*/
            $("#tabela_detalhes2").remove();
            $.ajax({
                url : "<?php echo site_url('/dashboard/percentuaisSetorOcupacao')?>",
                type : 'POST',
                dataType: "JSON",
                data : {
                            "id_area": id_area
                        },
                success : function(data){
                    var result  = data;
                    var html_corpo_tabela = "";
                    var percent = 0.0;
                    var livres_1 = 0;

                    var unidades_reservadas = 0;
                    for(var i = 0; i<result.length; i++){
                        // if(parseInt(result[i].CD_SETOR_ATENDIMENTO)==129){
                        //     var porcentagem_ocup = (((parseInt(result[i].NR_UNID_OCUP) + parseInt(result[i].NR_UNIDADES_HIGIENIZACAO) + parseInt(result[i].NR_UNID_AGUARD_HIGIEN)) / parseInt(result[i].NR_UNIDADES_SETOR))*100);
                        // }else{
                        //     var porcentagem_ocup = parseInt(result[i].PR_OCUPACAO_TOTAL);
                        // }
                        unidades_reservadas = parseInt(result[i].NR_UNIDADES_RESERVADAS);
                        temp_indisponiveis  = parseInt(unidades_reservadas)+parseInt(result[i].NR_UNIDADES_HIGIENIZACAO)+parseInt(result[i].QT_UNIDADES_ALTA)+parseInt(result[i].QT_UNIDADE_MANUTENCAO)+parseInt(result[i].NR_UNID_AGUARD_HIGIEN)+parseInt(result[i].QT_UNIDADES_ISOLAMENTO);
                        if(parseInt(result[i].CD_SETOR_ATENDIMENTO)==129 || (parseInt(result[i].CD_SETOR_ATENDIMENTO)==145) || (parseInt(result[i].CD_SETOR_ATENDIMENTO)==83)){
                            var porcentagem_ocup   = (((parseInt(result[i].NR_UNID_OCUP)) / parseInt(result[i].NR_UNIDADES_SETOR))*100.00).toFixed(2);
                            livres_1  = parseInt(result[i].NR_UNIDADES_SETOR) - parseInt(result[i].NR_UNID_OCUP) -temp_indisponiveis; /*- parseInt(result[i].NR_UNIDADES_HIGIENIZACAO) - parseInt(result[i].NR_UNID_AGUARD_HIGIEN) - parseInt(result[i].QT_UNIDADES_ALTA)*/
                            if(parseInt(livres_1)<0 && parseInt(result[i].CD_SETOR_ATENDIMENTO)==129){
                                //ALTERAÇÃO REALIZADA POR CONTA DO NÚMERO NEGATIVO EM "LEITOS LIVRES" - O NUMERO DE OCUPADOS VEM COM DISTINCT POR LEITO DO BANCO E NO CÓDIGO ABATE-SE AGORA A QUANTIDADE NEGATIVA DOS LEITOS RESERVADOS
                                unidades_reservadas = unidades_reservadas - (livres_1*-1);
                                livres_1 = 0
                            }
                        }else{
                            var porcentagem_ocup   = parseFloat(result[i].PR_OCUPACAO).toFixed(2);
                            livres_1  = result[i].NR_UNIDADES_LIVRES;
                        }
                        porcentagem_ocup = Math.ceil(porcentagem_ocup/5)*5;
                        var cor_per = "";
                        // if(porcentagem_ocup<=30){
                        //     cor_per = "success";
                        // }else if(porcentagem_ocup>30 && porcentagem_ocup<=60){
                        //     cor_per = "info";
                        // }else if(porcentagem_ocup>60 && porcentagem_ocup<80){
                        //     cor_per = "info";
                        // }else if(porcentagem_ocup>=80 && porcentagem_ocup<=90){
                        //     cor_per = "warning";
                        // }else{
                        //     cor_per = "danger";
                        // }

                        // if(porcentagem_ocup<=75){
                        //     cor_per = "danger";
                        //     cor_per = 'bg-gradient-'+cor_per;
                        // }else if(porcentagem_ocup>75 && porcentagem_ocup<=85){
                        //     cor_per = "primary";
                        //     cor_per = 'bg-gradient-'+cor_per;
                        // }else if(porcentagem_ocup>85 && porcentagem_ocup<=90){
                        //     cor_per = "bg-warning";
                        // }else if(porcentagem_ocup>90 && porcentagem_ocup<=95){
                        //     cor_per = "info";
                        //     cor_per = 'bg-gradient-'+cor_per;
                        // }else{
                        //     cor_per = "success";
                        //     cor_per = 'bg-gradient-'+cor_per;
                        // }

                        if(porcentagem_ocup<75){
                            cor_per = "danger";
                            cor_per = 'bg-gradient-'+cor_per;
                        }else if(porcentagem_ocup>=75 && porcentagem_ocup<=85){
                            cor_per = "bg-warning";
                        }else{
                            cor_per = "success";
                            cor_per = 'bg-gradient-'+cor_per;
                        }
                        
                        var qt_ocupadas = result[i].NR_UNID_OCUP;
                        //if(result[i].CD_SETOR_ATENDIMENTO==129){qt_ocupadas = (result[i].NR_UNID_OCUP)*-1}else{qt_ocupadas = result[i].NR_UNID_OCUP}
                        
                        // if(parseInt(result[i].CD_SETOR_ATENDIMENTO)==129){
                        //     percent   = (((parseInt(result[i].NR_UNID_OCUP) + parseInt(result[i].NR_UNIDADES_HIGIENIZACAO) + parseInt(result[i].NR_UNID_AGUARD_HIGIEN)) / parseInt(result[i].NR_UNIDADES_SETOR))*100.00).toFixed(2); 
                        //     livres_1  = parseInt(result[i].NR_UNIDADES_SETOR) - parseInt(result[i].NR_UNID_OCUP) - parseInt(result[i].NR_UNIDADES_HIGIENIZACAO) - parseInt(result[i].NR_UNID_AGUARD_HIGIEN);
                        // }else{
                        //     percent   = result[i].PR_OCUPACAO_TOTAL;
                        //     livres_1  = result[i].NR_UNIDADES_LIVRES;
                        // }

                        unidades_reservadas = parseInt(result[i].NR_UNIDADES_RESERVADAS);
                        if(parseInt(result[i].CD_SETOR_ATENDIMENTO)==129 || (parseInt(result[i].CD_SETOR_ATENDIMENTO)==145) || (parseInt(result[i].CD_SETOR_ATENDIMENTO)==83)){
                            percent   = (((parseInt(result[i].NR_UNID_OCUP)) / parseInt(result[i].NR_UNIDADES_SETOR))*100.00).toFixed(2);
                            livres_1  = parseInt(result[i].NR_UNIDADES_SETOR) - parseInt(result[i].NR_UNID_OCUP) -temp_indisponiveis; /*- parseInt(result[i].NR_UNIDADES_HIGIENIZACAO) - parseInt(result[i].NR_UNID_AGUARD_HIGIEN) - parseInt(result[i].QT_UNIDADES_ALTA)*/
                            if(parseInt(livres_1)<0 && parseInt(result[i].CD_SETOR_ATENDIMENTO)==129){
                                unidades_reservadas = unidades_reservadas - (livres_1*-1);
                                livres_1 = 0
                            }
                        }else{
                            percent   = parseFloat(result[i].PR_OCUPACAO).toFixed(2);
                            livres_1  = result[i].NR_UNIDADES_LIVRES;
                        }

                        html_corpo_tabela += '<tr>'+
                                                '<td>'+
                                                    result[i]["DS_SETOR_ATENDIMENTO"]+
                                                '</td>'+
                                                '<td>'+
                                                    '<div class="d-flex px-2 py-1">'+
                                                        result[i].DS_LINHA_CUIDADO+
                                                    '</div>'+
                                                '</td>'+
                                                '<td class="align-middle">'+
                                                   '<div class="progress-wrapper w-75 mx-auto">'+
                                                        '<div class="progress-info">'+
                                                            '<div class="progress-percentage">'+
                                                                '<span class="text-xs font-weight-bold">'+percent+'%</span>'+
                                                            '</div>'+
                                                        '</div>'+
                                                        '<div class="progress">'+
                                                            '<div class="progress-bar '+cor_per+' w-'+porcentagem_ocup.toString()+'" role="progressbar" aria-valuenow="'+porcentagem_ocup+'" aria-valuemin="0" aria-valuemax="100"></div>'+
                                                        '</div>'+
                                                    '</div>'+
                                                '</td>'+
                                                '<td class="align-middle text-center text-sm">'+
                                                    '<span class="text-xs font-weight-bold">'+result[i].NR_UNIDADES_SETOR+'</span>'+
                                                '</td>'+
                                                '<td class="align-middle text-center text-sm">'+
                                                    '<span class="text-xs font-weight-bold">'+result[i].QT_UNIDADES_ISOLAMENTO+'</span>'+
                                                '</td>'+
                                                '<td class="align-middle text-center text-sm">'+
                                                    '<span class="text-xs font-weight-bold">'+qt_ocupadas+'</span>'+
                                                '</td>'+
                                                '<td class="align-middle text-center text-sm">'+
                                                    '<span class="text-xs font-weight-bold">'+livres_1+'</span>'+
                                                '</td>'+
                                                '<td class="align-middle text-center text-sm">'+
                                                    '<span class="text-xs font-weight-bold">'+unidades_reservadas+'</span>'+
                                                '</td>'+
                                                '<td class="align-middle text-center text-sm">'+
                                                    '<span class="text-xs font-weight-bold">'+(parseInt(result[i].NR_UNIDADES_HIGIENIZACAO) + parseInt(result[i].NR_UNID_AGUARD_HIGIEN))+'</span>'+
                                                '</td>'+
                                                '<td class="align-middle text-center text-sm">'+
                                                    '<span class="text-xs font-weight-bold">'+(parseInt(result[i].NR_UNIDADES_INTERDITADAS) + parseInt(result[i].QT_UNIDADE_MANUTENCAO))+'</span>'+
                                                '</td>'+
                                                '<td class="align-middle text-center text-sm">'+
                                                    '<span class="text-xs font-weight-bold">'+result[i].QT_UNIDADES_ALTA+'</span>'+
                                                '</td>'+
                                            '</tr>';
                    }
                    
                    $("#corpo_tabela_ocupacao").html(html_corpo_tabela);
                    $("#nome_area").text($("#titulo"+id_area).text());
                    
                    $("#tabela_detalhes").show();
                    location.href = "#tabela_detalhes";
                },
                error : function(data){
                    alert('Não foi possível definir os detalhes do setor');
                }
            });
        //}
    }
</script>
