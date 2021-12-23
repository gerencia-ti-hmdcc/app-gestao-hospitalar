<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card z-index-2">
            <div class="card-header pb-0">
                <div class="row">
                    <div class="col-lg-6 col-7">
                        <h6>Gráfico Geral de ocupação</h6>
                        <!-- <p class="text-sm">
                            <i class="fa fa-arrow-up text-success"></i>
                            <span class="font-weight-bold">4% more</span> in 2021
                        </p> -->
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
                    <canvas id="chart-bars" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="divPercentualGeral" name="divPercentualGeral">

    </div>
    <!-- <div class="col-xl-6 col-sm-6 mb-xl-0 mb-4">
        <div class="card cursor-pointer" onclick="abrirDivDetalhes(3)">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                        <p class="text-sm mb-0 text-capitalize font-weight-bold">UTI</p>
                        <h5 class="font-weight-bolder mb-0">
                            55%
                            <span class="text-success text-sm font-weight-bolder">+55%</span>
                        </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                        <i class="ni ni-building text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-sm-6 mb-xl-0 mb-4">
        <div class="card cursor-pointer">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Unidades de internação</p>
                        <h5 class="font-weight-bolder mb-0">
                            89%
                            <span class="text-success text-sm font-weight-bolder">+3%</span>
                        </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                        <i class="ni ni-building text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
        <div class="card-body p-3">
            <div class="row">
            <div class="col-8">
                <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">New Clients</p>
                <h5 class="font-weight-bolder mb-0">
                    +3,462
                    <span class="text-danger text-sm font-weight-bolder">-2%</span>
                </h5>
                </div>
            </div>
            <div class="col-4 text-end">
                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
        <div class="card-body p-3">
            <div class="row">
            <div class="col-8">
                <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Sales</p>
                <h5 class="font-weight-bolder mb-0">
                    $103,430
                    <span class="text-success text-sm font-weight-bolder">+5%</span>
                </h5>
                </div>
            </div>
            <div class="col-4 text-end">
                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div> -->
</div>


<!-- tabela -->


<div class="row my-4">
    <div class="col-lg-12 col-md-12 mb-4" id="divGeral" name="divGeral"><!--mb-md-0-->
        <div class="card" id="tabela_detalhes" name="tabela_detalhes">
            <div class="card-header pb-0">
                <div class="row">
                    <div class="col-lg-6 col-7">
                        <h6 id='nome_area' name='nome_area'></h6>
                        <!-- <p class="text-sm mb-0">
                        <i class="fa fa-check text-info" aria-hidden="true"></i>
                        <span class="font-weight-bold ms-1">30 done</span> this month
                        </p> -->
                    </div>
                    <div class="col-lg-6 col-5 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="cursor-pointer" onclick="abrirModalInformacoes('modal_tabela_setor')" aria-expanded="false"> <!-- id="dropdownTable" data-bs-toggle="dropdown" -->
                                <i class="fa fa-question-circle text-secondary"></i>
                            </a>
                            <!-- <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                                <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                                <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a></li>
                                <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else here</a></li>
                            </ul> -->
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
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alta</th>
                            </tr>
                        </thead>
                        <tbody id="corpo_tabela_ocupacao" name="corpo_tabela_ocupacao">
                            
                        </tbody>
                    </table>
                </div>
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
                                    '<b>Alta</b> - Leitos em processo de alta.');
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
                    for(var i = 0; i<result.length; i++){
                        var porcentagem_ocup = parseInt(result[i].PR_OCUPACAO_TOTAL);
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
                        if(porcentagem_ocup<=75){
                            cor_per = "danger";
                            cor_per = 'bg-gradient-'+cor_per;
                        }else if(porcentagem_ocup>75 && porcentagem_ocup<=85){
                            cor_per = "primary";
                            cor_per = 'bg-gradient-'+cor_per;
                        }else if(porcentagem_ocup>85 && porcentagem_ocup<=90){
                            cor_per = "bg-warning";
                        }else if(porcentagem_ocup>90 && porcentagem_ocup<=95){
                            cor_per = "info";
                            cor_per = 'bg-gradient-'+cor_per;
                        }else{
                            cor_per = "success";
                            cor_per = 'bg-gradient-'+cor_per;
                        }
                        var qt_ocupadas = result[i].QT_OCUPADAS;
                        //if(result[i].CD_SETOR_ATENDIMENTO==129){qt_ocupadas = (result[i].QT_OCUPADAS)*-1}else{qt_ocupadas = result[i].QT_OCUPADAS}

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
                                                                '<span class="text-xs font-weight-bold">'+result[i].PR_OCUPACAO_TOTAL+'%</span>'+
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
                                                    '<span class="text-xs font-weight-bold">'+result[i].QT_LIVRES+'</span>'+
                                                '</td>'+
                                                '<td class="align-middle text-center text-sm">'+
                                                    '<span class="text-xs font-weight-bold">'+result[i].NR_UNIDADES_RESERVADAS+'</span>'+
                                                '</td>'+
                                                '<td class="align-middle text-center text-sm">'+
                                                    '<span class="text-xs font-weight-bold">'+(parseInt(result[i].NR_UNIDADES_HIGIENIZACAO) + parseInt(result[i].NR_UNID_AGUARD_HIGIEN))+'</span>'+
                                                '</td>'+
                                                '<td class="align-middle text-center text-sm">'+
                                                    '<span class="text-xs font-weight-bold">'+result[i].QT_UNIDADES_ALTA+'</span>'+
                                                '</td>'+
                                            '</tr>';
                    }
                    
                    $("#corpo_tabela_ocupacao").html(html_corpo_tabela);
                    $("#nome_area").text($("#titulo"+id_area).text());
                    // if(id_area==3){
                    //     $.ajax({
                    //         url : "<?php echo site_url('/dashboard/percentuaisSetorOcupacaoClinicaCirurgica')?>",
                    //         type : 'POST',
                    //         dataType: "JSON",
                    //         data : {
                    //                     "id_area": 99
                    //                 },
                    //         success : function(data){
                    //             var result  = data;
                    //             var html_tabela2 = '<div class="card" id="tabela_detalhes2" name="tabela_detalhes2">'+
                    //                                     '<div class="card-header pb-0">'+
                    //                                         '<div class="row">'+
                    //                                             '<div class="col-lg-6 col-7">'+
                    //                                                 '<h6>Clínica Cirúrgica</h6>'+
                    //                                             '</div>'+
                    //                                             '<!--<div class="col-lg-6 col-5 my-auto text-end">'+
                    //                                                 '<div class="dropdown float-lg-end pe-4">'+
                    //                                                     '<a class="cursor-pointer" onclick="abrirModalInformacoes(\'modal_tabela_setor\')" aria-expanded="false">'+
                    //                                                         '<i class="fa fa-question-circle text-secondary"></i>'+
                    //                                                     '</a>'+
                    //                                                 '</div>'+
                    //                                             '</div>-->'+
                    //                                         '</div>'+
                    //                                     '</div>'+
                    //                                     '<div class="card-body px-0 pb-2">'+
                    //                                         '<div class="table-responsive">'+
                    //                                             '<table class="table align-items-center mb-0">'+
                    //                                                 '<thead>'+
                    //                                                     '<tr>'+
                    //                                                         '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Linha Cuidado</th>'+
                    //                                                         '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Setor</th>'+
                    //                                                         '<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ocupação</th>'+
                    //                                                         '<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total de leitos</th>'+
                    //                                                         '<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ocupados</th>'+
                    //                                                         '<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Livres</th>'+
                    //                                                         '<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Reservados</th>'+
                    //                                                         '<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Isolados</th>'+
                    //                                                         '<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Higienização</th>'+
                    //                                                         '<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alta</th>'+
                    //                                                     '</tr>'+
                    //                                                 '</thead>'+
                    //                                                 '<tbody id="corpo_tabela_2" name="corpo_tabela_2">';
                    //             for(var i = 0; i<result.length; i++){
                    //                 var porcentagem_ocup = parseInt(result[i].PR_OCUPACAO_TOTAL);
                    //                 porcentagem_ocup = Math.ceil(porcentagem_ocup/5)*5;
                    //                 var cor_per = "";
                    //                 if(porcentagem_ocup<=30){
                    //                     cor_per = "success";
                    //                 }else if(porcentagem_ocup>30 && porcentagem_ocup<=60){
                    //                     cor_per = "info";
                    //                 }else if(porcentagem_ocup>60 && porcentagem_ocup<80){
                    //                     cor_per = "info";
                    //                 }else if(porcentagem_ocup>=80 && porcentagem_ocup<=90){
                    //                     cor_per = "warning";
                    //                 }else{
                    //                     cor_per = "danger";
                    //                 }
                    //                 html_tabela2 += '<tr>'+
                    //                                         '<td>'+
                    //                                             '<div class="d-flex px-2 py-1">'+
                    //                                                 result[i].DS_LINHA_CUIDADO+
                    //                                             '</div>'+
                    //                                         '</td>'+
                    //                                         '<td>'+
                    //                                             result[i]["DS_SETOR_ATENDIMENTO"]+
                    //                                         '</td>'+
                    //                                         '<td class="align-middle">'+
                    //                                             '<div class="progress-wrapper w-75 mx-auto">'+
                    //                                                 '<div class="progress-info">'+
                    //                                                     '<div class="progress-percentage">'+
                    //                                                         '<span class="text-xs font-weight-bold">'+result[i].PR_OCUPACAO_TOTAL+'%</span>'+
                    //                                                     '</div>'+
                    //                                                 '</div>'+
                    //                                                 '<div class="progress">'+
                    //                                                     '<div class="progress-bar bg-gradient-'+cor_per+' w-'+porcentagem_ocup.toString()+'" role="progressbar" aria-valuenow="'+porcentagem_ocup+'" aria-valuemin="0" aria-valuemax="100"></div>'+
                    //                                                 '</div>'+
                    //                                             '</div>'+
                    //                                         '</td>'+
                    //                                         '<td class="align-middle text-center text-sm">'+
                    //                                             '<span class="text-xs font-weight-bold">'+result[i].NR_UNIDADES_SETOR+'</span>'+
                    //                                         '</td>'+
                    //                                         '<td class="align-middle text-center text-sm">'+
                    //                                             '<span class="text-xs font-weight-bold">'+result[i].QT_UNIDADES_ISOLAMENTO+'</span>'+
                    //                                         '</td>'+
                    //                                         '<td class="align-middle text-center text-sm">'+
                    //                                             '<span class="text-xs font-weight-bold">'+result[i].QT_OCUPADAS+'</span>'+
                    //                                         '</td>'+
                    //                                         '<td class="align-middle text-center text-sm">'+
                    //                                             '<span class="text-xs font-weight-bold">'+result[i].QT_LIVRES+'</span>'+
                    //                                         '</td>'+
                    //                                         '<td class="align-middle text-center text-sm">'+
                    //                                             '<span class="text-xs font-weight-bold">'+result[i].NR_UNIDADES_RESERVADAS+'</span>'+
                    //                                         '</td>'+
                    //                                         '<td class="align-middle text-center text-sm">'+
                    //                                             '<span class="text-xs font-weight-bold">'+(parseInt(result[i].NR_UNIDADES_HIGIENIZACAO) + parseInt(result[i].NR_UNID_AGUARD_HIGIEN))+'</span>'+
                    //                                         '</td>'+
                    //                                         '<td class="align-middle text-center text-sm">'+
                    //                                             '<span class="text-xs font-weight-bold">'+result[i].QT_UNIDADES_ALTA+'</span>'+
                    //                                         '</td>'+
                    //                                     '</tr>';
                    //             }
                    //             html_tabela2 += '</tbody>'+
                    //                         '</table>'+
                    //                     '</div>'+
                    //                 '</div>'+
                    //             '</div>';
                    //             $("#tabela_detalhes").append(html_tabela2); 
                    //         },
                    //         error : function(data){
                    //             alert('erro');
                    //         }
                    //     });
                    // }
                    $("#tabela_detalhes").show();
                },
                error : function(data){
                    alert('erro');
                }
            });
        //}
    }
</script>